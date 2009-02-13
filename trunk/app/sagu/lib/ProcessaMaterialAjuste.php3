<? require_once("common_not_login.php3"); ?>

<?

// Fun��o que retorna o total de horas aula cursadas pelo aluno
function get_total_horas($ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, $ref_periodo_anterior, $ref_area)
{
    $conn_material = new Connection;
    $conn_material->open();
 
    $sql_total_horas = " select sum(B.carga_horaria) ".
           		       " from matricula A, disciplinas B ".
       	    	       " where A.ref_disciplina = B.id and ".
       		           "       A.ref_pessoa = '$ref_pessoa' and ".
       		           "       A.ref_curso = '$ref_curso' and ".
           		       "       A.dt_cancelamento is null and ".
    		           "       A.fl_liberado <> '1' and " .
    		           "       A.fl_liberado <> '2' and " .
       	    	       "       ((A.nota_final >= get_media_final(A.ref_periodo)) or " .
                       "        (A.nota_final = 0 and A.ref_periodo = '$ref_periodo_anterior') or ".
                       "        (A.conceito <> '') or " .
                       "        (A.fl_liberado = '3') or " .
                       "        (A.fl_liberado = '4')) and " .
                       "       get_curriculo_mco('$ref_curso','$ref_campus',A.ref_disciplina) != 'A'"; 

    if ($ref_area != '0')
    {
        $sql_total_horas .= " and get_area_disciplina('$ref_curso','$ref_campus', A.ref_disciplina) = '$ref_area'";
    }

    $query_total_horas = $conn_material->CreateQuery($sql_total_horas);

    if( $query_total_horas->MoveNext() )
        list ($horas_cursadas ) = $query_total_horas->GetRowValues();
    
    return $horas_cursadas;

}

/*************************************************************************************************/
//Retornos da Fun��o:
//0 - Cursadas
//1 - Liberada para cursar
//2 - Nao Liberada
//9 - Disciplinas Fora do Curriculo do Aluno
/*************************************************************************************************/

function Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $ref_periodo, $ref_periodo_anterior, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora, $ref_disciplina_equivalente=null)
{
    $conn_material = new Connection;
    $conn_material->open();
 
    set_time_limit(0);
  
    $sql_aprov = " ((nota_final >= get_media_final(ref_periodo)) or " . 
	   	         "  (nota_final = 0 and ref_periodo = '$ref_periodo_anterior') or " .
                 "  (conceito <> '') or " .
                 "  (fl_liberado = '3') or " .
                 "  (fl_liberado = '4') " .
                 " ) ";

    //Testar primeiro as disciplinas que foram substituidas por outra no curriculo
    //A disciplina antiga n�o � mais considerada para base de curriculo, a menos
    //que o aluno j� cursou se o aluno j� cursou ignora-se a nova
    if( !empty($disciplina_substituida) && $disciplina_substituida!=0 )
    {
        $sql_disciplina = " select nota_final " .
            		      " from matricula " .
        	              " where ref_pessoa = '$ref_pessoa' and " .
          	              "       ref_disciplina = '$disciplina_substituida' and " .
                          "       dt_cancelamento is null and " . 
    		              "       fl_liberado <> '1' and " .
    		              "       fl_liberado <> '2' and " .
                          "       $sql_aprov ";

        $query_disciplina = $conn_material->CreateQuery($sql_disciplina);

        if( $query_disciplina->MoveNext() )
        {
            return 9;
        }
    }

    $sql_disciplina =  " select nota_final " .
         	           " from matricula " .
     		           " where ref_pessoa = '$ref_pessoa' and ";

    if ($ref_disciplina_equivalente)
    { $sql_disciplina .= " ref_disciplina = '$ref_disciplina_equivalente' and "; }
    else
    { $sql_disciplina .= " ref_disciplina = '$ref_disciplina' and "; }
        
    $sql_disciplina .= "       dt_cancelamento is null and " .
    		           "       fl_liberado <> '1' and " .
    		           "       fl_liberado <> '2' and " .
                       "       $sql_aprov ";

                      
    $query_disciplina = $conn_material->CreateQuery($sql_disciplina);

    if( $query_disciplina->MoveNext() )
    {
        return 0;
    }
    else
    {
        if ($pre_requisito_hora > 0)
        {
            if ($pre_requisito_hora > $horas_cursadas)
                return 2;
        }
   
        // Somente Pr�-requisitos
        $sql_pre_requisito = " select ref_disciplina_pre, " .
                             "        ref_area, " .
                             "        horas_area " .
                             " from pre_requisitos " .
                             " where ref_disciplina = '$ref_disciplina' and " .
                             "       ref_curso = '$ref_curso' and " .
                             "       tipo = 'P'";

        $query_pre_requisito = $conn_material->CreateQuery($sql_pre_requisito);
	
        if( $query_pre_requisito->MoveNext() )
        {
            $query_pre_requisito->MovePrev();

            while($query_pre_requisito->MoveNext())
            {
                list ($disciplina_pre,
                      $ref_area,
                      $horas_area) = $query_pre_requisito->GetRowValues();

                // Pr�-requisito por hora �rea.
                if ($ref_area != '0')
                {
                    $horas_area_cursada = get_total_horas($ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, $ref_periodo_anterior, $ref_area);
                    if ($horas_area_cursada < $horas_area)
                        return 2;
                }

                // Pr� requisito normal e por profici�ncia.
                $sql2 = " select A.nota_final " .
                        " from matricula A, cursos_disciplinas B " .
                        " where A.ref_curso = B.ref_curso and " .
                        "       A.ref_disciplina = B.ref_disciplina and " .
                        "       A.ref_pessoa = '$ref_pessoa' and " .
                        "       ((A.ref_disciplina = '$disciplina_pre') or (B.disciplina_substituida = '$disciplina_pre')) and " .
                        "       A.dt_cancelamento is null and " .
    		            "       A.fl_liberado <> '1' and " .
    		            "       A.fl_liberado <> '2' and " .
                        "       (($sql_aprov) or " .
                        "        (get_curriculo_mco($ref_curso,$ref_campus,A.ref_disciplina)='P')" .
                        "       );";

                $query2 = $conn_material->CreateQuery($sql2);

                if(!$query2->MoveNext())
                {
                    // Se a disciplina pre-requisito for zero ou vazio, quer 
                    // dizer que a disciplina s� tem pr�-requisito por hora �rea.
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
function ProcessaMaterialAjuste($periodo_id, $curso_id, $campus_id, $id_aluno)
{

    $conn_material = new Connection;
    $conn_material->open();
   
    set_time_limit(0);
   
    $sql_delete = " delete from disciplinas_todos_alunos where ref_curso = '$curso_id' and " .
           	      "                                            ref_campus = '$campus_id' and " .
	 	          "                                            ref_pessoa = '$id_aluno' and " .
                  "                                            fl_autorizado <> 't'";

    $ok = @$conn_material->Execute($sql_delete); 

    $sql_contrato = " select id, ".
      		        "        ref_pessoa,  ".
		            "        ref_curso,  ".
	                "        ref_campus, ".
                    "        get_periodo_anterior('$periodo_id') " .
                    " from contratos  ".
                    " where ".
		 //   (ref_last_periodo = '$periodo_id' or " .
                  //  "        ref_last_periodo = get_periodo_anterior('$periodo_id') ) and  " .
	                "       ref_curso = '$curso_id' and  ".
	                "       ref_campus = '$campus_id' and ".
	                "       dt_desativacao is null and " .
	                "       ref_pessoa = '$id_aluno'";
                   
    $conn_material = new Connection;
    $conn_material->open();
      
    $query_contrato = $conn_material->CreateQuery($sql_contrato);

    while($query_contrato->MoveNext())
    {      
        list($id,
             $ref_pessoa, 
             $ref_curso,
             $ref_campus,
             $periodo_id_anterior) = $query_contrato->GetRowValues();
	
        $horas_cursadas = get_total_horas($ref_pessoa, $ref_curso, $ref_campus, $periodo_id, $periodo_id_anterior, '0');
      
        $sql_cursos_disciplinas = " select distinct ref_curso, " .
                                  "                 ref_campus,  " .
                                  "                 ref_disciplina,  " .
                                  "                 semestre_curso,  " .                           
                                  "                 curriculo_mco, " .
                                  "                 disciplina_substituida, " .
             	  		  	      "		            pre_requisito_hora, " .
                                  "                 cursa_outra_disciplina " .
                                  " from cursos_disciplinas " .
                                  " where ref_curso = '$curso_id' and  " .
                                  "       ref_campus = '$campus_id' and  " .
                                  "       curriculo_mco <> 'O' and " .
                                  "       curriculo_mco <> 'P' and " .
                                  "       fl_soma_curriculo is true and " .
                                  "       (dt_final_curriculo >= date(now()) or dt_final_curriculo is null)".
                                  " order by semestre_curso, ref_disciplina; ";

        $query_cursos_disciplinas = $conn_material->CreateQuery($sql_cursos_disciplinas);

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

                $status = Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $periodo_id, $periodo_id_anterior, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora);

                $sql_insert= " insert into disciplinas_todos_alunos " .
                             " values($ref_pessoa,  " .
                             "        $ref_curso,  " .
                             "        $ref_campus,  " .
                             "        $ref_disciplina,  " .
                             "        $status); "; 

                $ok = $conn_material->Execute($sql_insert); 
            
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
    
                $query_cursos_disc_equiv = $conn_material->CreateQuery($sql_cursos_disc_equiv);

                while($query_cursos_disc_equiv->MoveNext())
                {
                    list($ref_disciplina,
                         $ref_disciplina_equivalente) = $query_cursos_disc_equiv->GetRowValues();

                    $status = Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $periodo_id, $periodo_id_anterior, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora, $ref_disciplina_equivalente);

                    if ($status == 0)
                    { $fl_cursou = true; }
                
                    $vet_disciplina[] = $ref_disciplina_equivalente;
            
                    $sql_insert= " insert into disciplinas_todos_alunos " .
                                 " values($ref_pessoa,  " .
                                 "        $ref_curso,  " .
                                 "        $ref_campus,  " .
                                 "        $ref_disciplina_equivalente,  " .
                                 "        $status); "; 

                    $ok = $conn_material->Execute($sql_insert); 
            
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
                    
                        $ok = $conn_material->Execute($sql_update); 
            
                        SaguAssert($ok,"Nao foi possivel inserir o registro!");
                    }
                }

                $query_cursos_disc_equiv->Close();
            }
            else
            {
                $status = Get_status($ref_pessoa, $ref_disciplina, $ref_curso, $ref_campus, $periodo_id, $periodo_id_anterior, $disciplina_substituida, $horas_cursadas, $pre_requisito_hora);

                $sql_insert= " insert into disciplinas_todos_alunos " .
                             " values($ref_pessoa,  " .
                             "        $ref_curso,  " .
                             "        $ref_campus,  " .
                             "        $ref_disciplina,  " .
                             "        $status); "; 

                $ok = $conn_material->Execute($sql_insert); 
                SaguAssert($ok,"Nao foi possivel inserir o registro!");
            }
        }    
    }
}
?>