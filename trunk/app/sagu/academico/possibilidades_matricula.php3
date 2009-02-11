<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<title>Gerando Possibilidades de Matrícula</title>
<script language="PHP">

//Função que retorna o total de horas aula cursadas pelo aluno
function get_total_horas($ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, $ref_area)
{
    $conn = new Connection;
    $conn->open();

    $sql = " select sum(B.carga_horaria) ".
           " from matricula A, disciplinas B ".
           " where A.ref_disciplina = B.id and ".
           "       A.ref_pessoa = '$ref_pessoa' and ".
           "       A.ref_curso = '$ref_curso' and ".
           "       A.dt_cancelamento is null and ".
           "       A.fl_liberado <> '1' and " .
           "       A.fl_liberado <> '2' and " .
           "       ((A.nota_final >= get_media_final(A.ref_periodo)) or " .
           "        (A.nota_final = 0 and A.ref_periodo = '$ref_periodo') or " .
           "        (A.conceito <> '') or " .
           "        (A.fl_liberado = '3') or " .
           "        (A.fl_liberado = '4')) and " .
           "       get_curriculo_mco('$ref_curso', '$ref_campus', A.ref_disciplina) != 'A'"; 
       
    if ($ref_area != '0')
    {
        $sql .= " and get_area_disciplina('$ref_curso','$ref_campus',A.ref_disciplina)='$ref_area'";
    }
    $query = $conn->CreateQuery($sql);

    if( $query->MoveNext() )
        list ($horas_cursadas ) = $query->GetRowValues();

    return $horas_cursadas;

}

/*************************************************************************************************/
//Retornos da Função:
//0 - Cursadas
//1 - Liberada para cursar
//2 - Nao Liberada
//9 - Disciplinas Fora do Curriculo do Aluno
/*************************************************************************************************/

function Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $ref_periodo, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora, $ref_disciplina_equivalente=null)
{
    $conn = new Connection;
    $conn->open();
    set_time_limit(0);
   
    //Testar primeiro as disciplinas que foram substituidas por outra no curriculo
    //A disciplina antiga não é mais considerada para base de curriculo, a menos 
    //que o aluno já cursou se o aluno já cursou ignora-se a nova
    if( !empty($disciplina_substituida) && $disciplina_substituida!=0 )
    {
        $sql = " select nota_final " .
               " from matricula " .
               " where ref_pessoa = '$ref_pessoa' and " .
               "       ref_disciplina = '$disciplina_substituida' and " .
               "       dt_cancelamento is null and " . 
               "       fl_liberado <> '1' and " .
    	       "       fl_liberado <> '2' and " .
               "       ((nota_final >= get_media_final(ref_periodo)) or " .
               "        (nota_final = 0 and ref_periodo = '$ref_periodo') or " .
               "        (conceito <> '') or " .
               "        (fl_liberado = '3') or " .
               "        (fl_liberado = '4'));";

        $query = $conn->CreateQuery($sql);

        if( $query->MoveNext() )
        {
            return 9;
        }
    }

    $sql = " select nota_final " .
           " from matricula " .
           " where ref_pessoa = '$ref_pessoa' and ";
          
    if ($ref_disciplina_equivalente)
    { $sql .= " ref_disciplina = '$ref_disciplina_equivalente' and "; }
    else
    { $sql .= " ref_disciplina = '$ref_disciplina' and "; }
   
    $sql .="       dt_cancelamento is null and " . 
           "       fl_liberado <> '1' and " .
     	   "       fl_liberado <> '2' and " .
           "       ((nota_final >= get_media_final(ref_periodo)) or " .
           "        (nota_final = 0 and ref_periodo = '$ref_periodo') or " .
           "        (conceito <> '') or " .
           "        (fl_liberado = '3') or " .
           "        (fl_liberado = '4'));";

    $query = $conn->CreateQuery($sql);

    if( $query->MoveNext() )
    {
        return 0;
    }
    else
    {
        if ($pre_requisito_hora > 0)
        {
            if($pre_requisito_hora > $horas_cursadas)
   	            return 2;
        }
     
        // Somente Pré-requisitos
        $sql_pre_requisito = " select ref_disciplina_pre, " .
                             "        ref_area, " .
                             "        horas_area " .
                             " from pre_requisitos " .
                             " where ref_disciplina = '$ref_disciplina' and " .
                             "       ref_curso = '$ref_curso' and " .
                             "       tipo = 'P'";
 
        $query_pre_requisito = $conn->CreateQuery($sql_pre_requisito);
      
        if( $query_pre_requisito->MoveNext() )
        {
            $query_pre_requisito->MovePrev();

            while($query_pre_requisito->MoveNext())
            {
                list ($disciplina_pre,
                      $ref_area,
                      $horas_area) = $query_pre_requisito->GetRowValues();

                // Pré-requisito por hora área.
                if ($ref_area != '0') 
                {
                    $horas_area_cursada = get_total_horas($ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, $ref_area);
                    if ($horas_area_cursada < $horas_area)
                        return 2;
                }

                // Pré requisito normal e por proficiência.
                $sql2 = " select A.nota_final " .
                        " from matricula A, cursos_disciplinas B " .
                        " where A.ref_curso = B.ref_curso and " .
                        "       A.ref_disciplina = B.ref_disciplina and " .
                        "       A.ref_pessoa = '$ref_pessoa' and " .
                        "       ((A.ref_disciplina = '$disciplina_pre') or (B.disciplina_substituida = '$disciplina_pre')) and " .
                        "       A.dt_cancelamento is null and " .
    		            "       A.fl_liberado <> '1' and " .
    		            "       A.fl_liberado <> '2' and " .
                        "       (((A.nota_final >= get_media_final(A.ref_periodo)) or " .
                        "         (A.nota_final = 0 and A.ref_periodo = '$ref_periodo') or " .
                        "         (A.conceito <> '') or " .
                        "         (A.fl_liberado = '3') or " . 
                        "         (A.fl_liberado = '4') " .
                        "        ) or (get_curriculo_mco($ref_curso, $ref_campus, A.ref_disciplina) = 'P')); ";
             
                $query2 = $conn->CreateQuery($sql2);

                if(!$query2->MoveNext())
                {
                    // Se a disciplina pre-requisito for zero ou vazio, quer 
                    // dizer que a disciplina só tem pré-requisito por hora área.
                    if ( ($disciplina_pre != '0') && (!empty($disciplina_pre)) )
                    {
                        return 2;
                    }
                }
            }
            return 1;
        }
        else
        {
            return 1;
        }
    }
}

function Processa_Aluno($id_aluno, $curso_id, $campus_id, $periodo_id, $bixo, $opcao, $conn)
{

   $sql = "delete from disciplinas_todos_alunos where ref_curso = '$curso_id' and " .
          "                                           ref_campus = '$campus_id' ";

   if( !empty($id_aluno) )
   {
     $sql .= " and ref_pessoa = '$id_aluno'";
   } 

   $ok = $conn->Execute($sql); 

   if ($bixo == "sim") 
   {
      echo("Material para Calouro");

      $sql = " select a.id, " .
             "        b.ref_pessoa, ".
	         "        b.ref_opcao" . $opcao . ", ".
	         "        b.ref_campus" . $opcao . 
	         " from contratos a, vest_inscricoes b  ".
   	         " where a.ref_pessoa = b.ref_pessoa and ".
	         "       a.ref_curso = b.ref_opcao" . $opcao . " and ".
	         "       a.ref_campus = b.ref_campus" . $opcao . " and ".
	         "       b.ref_opcao" . $opcao . "='$curso_id' and  ".
	         "       b.ref_campus" . $opcao . "='$campus_id' and  ".
	         "       a.dt_desativacao is null and  ".
	         "       b.ref_vestibular='$periodo_id' ";

     if(! empty($id_aluno)) 
     {
       $sql .= " and a.ref_pessoa = '$id_aluno'";
     }
   }
   else 
   {
     $sql= " select id, ".
           "        ref_pessoa,  ".
           "        ref_curso,  ".
           "        ref_campus ".
           " from contratos  ".
	       " where ref_last_periodo = '$periodo_id' and  ".
           "       ref_curso = '$curso_id' and  ".
           "       ref_campus = '$campus_id' and ".
           "       dt_desativacao is null ";

     if(! empty($id_aluno)) 
     {
       $sql .= " and ref_pessoa = '$id_aluno'";
     }
   }

   $query = $conn->CreateQuery($sql);

   while($query->MoveNext())
   {      
     list($id,
          $ref_pessoa, 
          $ref_curso,
          $ref_campus )=$query->GetRowValues();
	
   echo("<b>Gerando:</b> $ref_pessoa: ");
   flush();

   $horas_cursadas = get_total_horas($ref_pessoa, $ref_curso, $ref_campus, $periodo_id, '0');

   $sql_cursos_disciplinas = " select distinct ref_curso, " .
                             "                 ref_campus,  " .
                             "                 ref_disciplina,  " .
                             "                 semestre_curso,  " .                           
                             "                 curriculo_mco, " .
                             "                 disciplina_substituida, " .
   		                     "		           pre_requisito_hora, " .
                             "                 cursa_outra_disciplina " .
                             " from cursos_disciplinas " .
                             " where ref_curso = '$curso_id' and  " .
                             "       ref_campus = '$campus_id' and  " .
                             "       curriculo_mco not in ('O','P') and " .
                             "       fl_soma_curriculo is true and " .  
                             "       (dt_final_curriculo >= date(now()) or dt_final_curriculo is null)".
                             " order by semestre_curso, ref_disciplina; ";

    $query_cursos_disciplinas = $conn->CreateQuery($sql_cursos_disciplinas);

    while($query_cursos_disciplinas->MoveNext())
    {
        list($ref_curso,
             $ref_campus,
             $ref_disciplina,
             $semestre_curso,
             $mco,
             $disciplina_substituida, 
             $pre_requisito_hora,
             $cursa_outra_disciplina) = $query_cursos_disciplinas->GetRowValues();

        // Teste das disciplinas que podem ser cursadas uma pela outra...
        if ($cursa_outra_disciplina == '1')
        {
            $vet_disciplina = null;
            $fl_cursou = false;
            
            $status = Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $periodo_id, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora);

            $sql_insert= " insert into disciplinas_todos_alunos " .
                         " values($ref_pessoa,  " .
                         "        $ref_curso,  " .
                         "        $ref_campus,  " .
                         "        $ref_disciplina,  " .
                         "        $status); "; 

            $ok = $conn->Execute($sql_insert); 
            
            SaguAssert($ok,"Nao foi possivel inserir o registro!");
            
            if ($status == 0)
            { $fl_cursou = true; }
            
            $vet_disciplina[] = $ref_disciplina;
            
            $sql_cursos_disc_equiv = " select distinct " .
                                     "        ref_disciplina, " .
                                     "        ref_disciplina_equivalente " .
                                     " from cursos_disciplinas_compl " .
                                     " where ref_curso = '$curso_id' and  " .
                                     "       ref_campus = '$campus_id' and " .
                                     "       ref_disciplina = '$ref_disciplina' " .
                                     " order by ref_disciplina_equivalente; ";

            $query_cursos_disc_equiv = $conn->CreateQuery($sql_cursos_disc_equiv);

            while($query_cursos_disc_equiv->MoveNext())
            {
                list($ref_disciplina,
                     $ref_disciplina_equivalente) = $query_cursos_disc_equiv->GetRowValues();

                $status = Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $periodo_id, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora,$ref_disciplina_equivalente);

                if ($status == 0)
                { $fl_cursou = true; }
                
                $vet_disciplina[] = $ref_disciplina_equivalente;
            
                $sql_insert= " insert into disciplinas_todos_alunos " .
                             " values($ref_pessoa,  " .
                             "        $ref_curso,  " .
                             "        $ref_campus,  " .
                             "        $ref_disciplina_equivalente,  " .
                             "        $status); "; 

                $ok = $conn->Execute($sql_insert); 
            
                SaguAssert($ok,"Nao foi possivel inserir o registro!");

            }

            if ($fl_cursou)
            {
                for ($i=0; $i<count($vet_disciplina); $i++)
                {
                    $sql_update = " UPDATE disciplinas_todos_alunos SET " .
                                  "     status = 0 " .
                                  " WHERE ref_pessoa = '$ref_pessoa' and " .
                                  "       ref_curso = '$ref_curso' and " .
                                  "       ref_campus = '$ref_campus' and " .
                                  "       ref_disciplina = '$vet_disciplina[$i]';";
                    
                    $ok = $conn->Execute($sql_update); 
            
                    SaguAssert($ok,"Nao foi possivel inserir o registro!");
                }
            }
            

            $query_cursos_disc_equiv->Close();
        }
        else
        {
            $status = Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $periodo_id, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora);

            $sql_insert= " insert into disciplinas_todos_alunos " .
                         " values($ref_pessoa,  " .
                         "        $ref_curso,  " .
                         "        $ref_campus,  " .
                         "        $ref_disciplina,  " .
                         "        $status); "; 

            $ok = $conn->Execute($sql_insert); 
            SaguAssert($ok,"Nao foi possivel inserir o registro!");
        }
        echo('. ');
        flush();
   }    

   echo("OK! <br>");
   flush();
 
   }
}


</script>
</head>
<body bgcolor="#FFFFFF">
<script Language="Javascript">
 var NOVAWIN = window.open("/aguarde.html", "NOVAWIN", "status=no,toolbar=no,location=no,menu=no,scrollbars=no,width=260,height=105,left=280,top=235");
</script>
<form method="post" action="possibilidades_matricula_lista.php3" name="myform">
  <div align="center">
   <script language="PHP">
   
   $conn = new Connection;
   $conn->open();
   
   set_time_limit(0);

   if ($ref_tipo_bolsa)
   {
        $sql = " select B.ref_pessoa, " .
               "        B.ref_curso, " .
               "        B.ref_campus " .
               " from bolsas A, contratos B " .
               " where A.ref_contrato = B.id and " .
               "       A.ref_tipo_bolsa = '$ref_tipo_bolsa' and " .
               "       A.dt_validade >= date(now()) and " .
               "       A.percentual <> 0 and " .
               "       B.dt_desativacao is null " .
               " order by pessoa_nome(B.ref_pessoa);";
   
        $query_bolsa = $conn->CreateQuery($sql);
  
        while($query_bolsa->MoveNext())
        {      
          list($id_aluno, 
               $curso_id,
               $campus_id)=$query_bolsa->GetRowValues();
          
          $bixo = 'nao';
   
          Processa_Aluno($id_aluno, $curso_id, $campus_id, $periodo_id, $bixo, $opcao, $conn);
        }
        
        $tipo_bolsa = GetField($ref_tipo_bolsa, "descricao", "aux_bolsas", true);

        echo("<font color=\"#FF0000\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">Possibilidades de Matrícula Geradas.</font></b></font>");
        echo("<hr>");
        echo("<font color=\"#FF0000\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">BOLSA: $ref_tipo_bolsa - $tipo_bolsa<br></b></font>");
   }
   else
   {
        Processa_Aluno($id_aluno, $curso_id, $campus_id, $periodo_id, $bixo, $opcao, $conn);
         
        $curso_desc = GetField($curso_id, "abreviatura", "cursos", true);

        echo("<font color=\"#FF0000\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">Possibilidades de Matrícula Geradas. <br> Clique em <font color=\"#0000FF\">Possibilidades</font> para ver o Resultado</font></b></font>");
        echo("<hr>");
        echo("<font color=\"#FF0000\"><b><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">CURSO: $curso_desc<br></b></font>");

   }

</script>
<script Language="Javascript">
  NOVAWIN.close();
</script>
    <br>
    <input type="hidden" name="periodo_id" value="<?echo($periodo_id);?>">
    <input type="hidden" name="curso_id" value="<?echo($curso_id);?>">
    <input type="hidden" name="campus_id" value="<?echo($campus_id);?>">
    <input type="hidden" name="ref_curso" value="<?echo($ref_curso);?>">
    <br>
    <font color="#FF0000"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
    <br>
    </font> </b> </font> 
    <INPUT type="submit" name="Submit" value="Ver Possibilidades">
  </div>
</form>
</body>
</html>
