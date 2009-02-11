<?php require("../../../lib/common.php"); 
 require("../lib/GetField.php3"); 
 require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Geração do Livro Matrícula</title>
</head>
<body bgcolor="#FFFFFF">

<?php
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\"><b>Gerando Livro de Matricula $ref_periodo - Prazo $dt_limite.</b><BR><BR> </font>");
   flush();
     
   $periodo_id = $ref_periodo;
   $dt_inicio = InvData($dt_limite);
  
   /****************************************************************
   ** Testa se o Livro de Matricula já foi gerado neste periodo.   /
   ****************************************************************/

   $conn = new Connection;
   $conn->open();
   
   $sql = "select fl_livro_matricula from periodos where id='$periodo_id'";

   $query = $conn->CreateQuery($sql);

   while( $query->MoveNext() )
   {
      list ($fl_livro_matricula) = $query->GetRowValues();
   }

   if ($fl_livro_matricula == '1')
   {
        echo ("<font face=\"Verdana, Arial, Helvetica, sans-serif\"><BR>&nbsp;&nbsp;&nbsp;&nbsp;O Livro de Matrícula <b>$ref_periodo</b> já foi gerado. Para você conseguir gerá-lo novamente, edite o periodo <b>$ref_periodo</b> e mude a opção Status do Livro Matrícula para <b>Não</b>.<BR>&nbsp;&nbsp;&nbsp;&nbsp;No entanto, o Livro de Matrícula tem um prazo limite para ser gerado e se gerado fora deste prazo pode acarretar problemas futuramente.</font>");
   	echo ("<form name=\"myform\" action=\"\" >");
  	echo (" 	 <p align=\"center\">");
   	echo ("  	<input type=\"button\" name=\"botao\" value=\"&lt;&lt; Retornar\" onclick=\"history.go(-1)\">");
   	echo ("	</form>");
   	echo ("</body>");
   	echo ("</html>");
    exit;
   }
   
   $query->Close();
   $conn->Close();

   /****************************************************************
   ** Apaga todas as ocorrências da tabela neste período para novo 
   ** processamento
   *****************************************************************/
   
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">==> Deletando todas as ocorrências da tabela no periodo $ref_periodo...<BR> </font>");
   flush();
   
   $conn = new Connection;
   $conn->open();

   $sql = "delete from livro_matricula where ref_periodo='$periodo_id'";
   $ok = $conn->Execute($sql); 
   
   /****************************************************************
   ** Alunos ativos 
   *****************************************************************/
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">==> Selecionando todos os alunos ativos em $ref_periodo...<BR> </font>");
   flush();
   
   $sql = " select ref_pessoa, " .
          "        ref_curso,  " .
          "        ref_campus, " .
          "        cod_status, " .
          "        get_curso_antigo('$periodo_id', ref_pessoa, 1), " . // Cursos Grad.
          "        get_campus_antigo('$periodo_id', ref_pessoa, 1) " . // Cursos Grad.
          " from contratos " .
          " where dt_desativacao is null and " .
          "       ref_last_periodo = '$periodo_id' and " .
          "       fl_ouvinte <> '1'" ;
          
   $query = $conn->CreateQuery($sql);

   $i=0;

   while( $query->MoveNext() )
   {
      list ( $ref_pessoa,
             $ref_curso, 
             $ref_campus,
             $cod_status, 
             $ref_curso_antigo,
             $ref_campus_antigo) = $query->GetRowValues();
   
       $id = GetIdentity(seq_lm_id, true);

       if( ($cod_status == 7) || ($cod_status == 11) || ($cod_status == 147) )
       {
          $sql_insert = "insert into livro_matricula values " .
                        "('$id', '$ref_pessoa', '$ref_curso_antigo', " .
            			" '$ref_campus_antigo', " .
                        " '$ref_curso', '$ref_campus', " .
                        " '$cod_status', '$periodo_id') ";
       }
       else
       {
          $sql_insert = "insert into livro_matricula values " .
                        "('$id', '$ref_pessoa', '$ref_curso', '$ref_campus', " .
                        " '$ref_curso_antigo', '$ref_campus_antigo', " .
                        " '$cod_status', '$periodo_id') ";
       }

       $ok = @$conn->Execute($sql_insert);

       SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");

       if($cod_status == 7)
       {
          $id = GetIdentity(seq_lm_id, true);

          $sql_insert = "insert into livro_matricula values " .
                     "('$id', '$ref_pessoa', '$ref_curso', " .
        		     " '$ref_campus', " .
                     " '$ref_curso_antigo', '$ref_campus_antigo', " .
                     " 6, '$periodo_id') ";

          $ok = @$conn->Execute($sql_insert);
    
          SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");
       }

       if($cod_status == 11)
       {
          $id = GetIdentity(seq_lm_id, true);

          $sql_insert = "insert into livro_matricula values " .
                     "('$id', '$ref_pessoa', '$ref_curso',".
        		     " '$ref_campus', " .
                     " '$ref_curso_antigo', '$ref_campus_antigo', " .
                     " 10, '$periodo_id') ";

          $ok = @$conn->Execute($sql_insert);
    
          SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");
       }
       
       if($cod_status == 147)
       {
          $id = GetIdentity(seq_lm_id, true);

          $sql_insert = "insert into livro_matricula values " .
                     "('$id', '$ref_pessoa', '$ref_curso',".
        		     " '$ref_campus', " .
                     " '$ref_curso_antigo', '$ref_campus_antigo', " .
                     " 148, '$periodo_id') ";

          $ok = @$conn->Execute($sql_insert);
    
          SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");
       }

   $i++;

   }

   $query->Close();
   $conn->Close();


   /****************************************************************
   ** Dados complementares do Livro Matricula 1 - Trancamentos
   *****************************************************************/
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">==> Dados Complementares I...<BR> </font>");
   flush();

   $conn = new Connection;
   $conn->open();

   $sql = " select distinct A.ref_pessoa, " .
          "                 A.ref_curso, " .
          "                 B.ref_campus, " .
          "                 get_curso_antigo(A.ref_periodo, A.ref_pessoa, 1), " .  // Cursos Grad.
          "                 get_campus_antigo(A.ref_periodo, A.ref_pessoa, 1), " . // Cursos Grad.
          "                 get_status_contrato(B.id) " .
          " from matricula A, contratos B " .
          " where A.ref_contrato = B.id and " .
	      "       A.ref_periodo='$periodo_id' and " .
	      "       ((B.dt_desativacao <= '$dt_inicio') or (B.dt_desativacao is null)) and " .
	      "       B.fl_formando <> '1' and " .
          "       B.fl_ouvinte <> '1' and " .
	      "       A.ref_pessoa not in " .
          "       (select distinct ref_pessoa " .
          "        from matricula " .
          "        where ref_periodo='$periodo_id' and  " .
          "              dt_cancelamento is null and " .
          "              ref_pessoa = A.ref_pessoa); " ;

   $query = $conn->CreateQuery($sql);

   $i=0;

   while( $query->MoveNext() )
   {
      list ( $ref_pessoa,
             $ref_curso, 
             $ref_campus,
             $ref_curso_antigo,
             $ref_campus_antigo,
             $cod_status ) = $query->GetRowValues();

       switch($cod_status)
       {
         case 1: $ref_status = 15; break;
         case 2: $ref_status = 13; break;
         case 3: $ref_status = 12; break;
         case 4: $ref_status = 17; break;
         case 16: $ref_status = 16; break;   //Vestibulando desistencia de vaga
         case 105: $ref_status = 105; break; //Transferência para outra Instituição
         case 152: $ref_status = 105; break; //Transferência para outra Instituição
         default: $ref_status = 99;
       }

       if($ref_status != 0)
       {
          $id = GetIdentity(seq_lm_id, true);

          $sql_insert = "insert into livro_matricula values " .
                        "('$id', '$ref_pessoa', '$ref_curso', '$ref_campus', " .
                        " '$ref_curso_antigo', '$ref_campus_antigo', " .
                        " '$ref_status', '$periodo_id') ";

          $ok = $conn->Execute($sql_insert);
          SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");
       }
   }

   $query->Close();
   $conn->Close();

   /****************************************************************
   ** Dados complementares do Livro Matricula 2 - Trancamentos
   *****************************************************************/
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">==> Dados Complementares II...<BR> </font>");
   flush();

   $periodo_anterior = GetField($periodo_id, "ref_anterior", "periodos", true);
   
   $conn = new Connection;
   $conn->open();

   
   $sql = " select distinct A.ref_pessoa, " .
          "                 A.ref_curso, " .
          "                 B.ref_campus, " .
          "                 get_curso_antigo(A.ref_periodo, A.ref_pessoa, 1), " .  // Cursos Grad.
          "                 get_campus_antigo(A.ref_periodo, A.ref_pessoa, 1), " . // Cursos Grad.
          "                 get_status_contrato(B.id) " .          
	      " from matricula A, contratos B " .
          " where A.ref_contrato = B.id and " .
	      "       A.ref_periodo='$periodo_anterior' and " .
	      "       A.dt_cancelamento is null and " .
	      "       B.fl_formando <> '1' and " .
          "       B.fl_ouvinte <> '1' and " .
	      "       ((B.dt_desativacao <= '$dt_inicio') or (B.dt_desativacao is null)) and " .
	      "       A.ref_pessoa not in (select distinct ref_pessoa " .
          "                            from matricula " .
          "                            where ref_pessoa = A.ref_pessoa and " .
          "                                  ref_periodo = '$periodo_id' and  " .
          "                                  (dt_cancelamento is null or dt_cancelamento <= '$dt_inicio'));";
   
   $query = $conn->CreateQuery($sql);

   $i=0;

   while( $query->MoveNext() )
   {
      list ( $ref_pessoa,
             $ref_curso, 
             $ref_campus,
             $ref_curso_antigo,
             $ref_campus_antigo,
             $cod_status) = $query->GetRowValues();

       $id = GetIdentity(seq_lm_id, true);

       if( ($cod_status == 105) || ($cod_status == 152) || ($cod_status == 16))
       {
          if ($cod_status == 16)
          {
              $sql_insert = "insert into livro_matricula values " .
                            "('$id', '$ref_pessoa', '$ref_curso', '$ref_campus', " .
                			" '$ref_curso_antigo', '$ref_campus_antigo', " .
                            " '16', '$periodo_id') ";
          }
          else
          {
              $sql_insert = "insert into livro_matricula values " .
                            "('$id', '$ref_pessoa', '$ref_curso', '$ref_campus', " .
                			" '$ref_curso_antigo', '$ref_campus_antigo', " .
                            " '105', '$periodo_id') ";
          }
       }
       else
       {
          $sql_insert = "insert into livro_matricula values " .
                        "('$id', '$ref_pessoa', '$ref_curso', '$ref_campus', " .
                        " '$ref_curso_antigo', '$ref_campus_antigo', " .
                        " '13', '$periodo_id') ";
       }
       
       $ok = $conn->Execute($sql_insert);
 
       SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");
   }

   $query->Close();
   $conn->Close();

   /****************************************************************
   ** Dados complementares do Livro Matricula 3
   ** Formados - Alunos que se formaram semestre passado não devem ser 
   ** considerados trancados (status_matricula = 14)
   *****************************************************************/
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">==> Dados Complementares III...<BR> </font>");
   flush();

   $periodo_anterior = GetField($periodo_id, "ref_anterior", "periodos", true);
   
   $conn = new Connection;
   $conn->open();
   
   $sql = " select distinct A.ref_pessoa, " .
          "                 A.ref_curso, " .
          "                 A.ref_campus " .
	      " from contratos A " .
          " where A.fl_formando = '1' and " .
          "       A.fl_ouvinte <> '1' and " .
	      "       A.ref_periodo_formatura = '$periodo_anterior' and " .
	      "       A.ref_pessoa not in " .
          "       ( select distinct B.ref_pessoa " .
          "         from matricula B " .
          "         where B.ref_periodo = '$periodo_id' and " .
	      "               B.ref_curso = A.ref_curso and " .
          "               B.ref_pessoa = A.ref_pessoa and " .
          "               B.dt_cancelamento is null ); " ;
   
   $query = $conn->CreateQuery($sql);

   $i=0;

   while( $query->MoveNext() )
   {
      list ( $ref_pessoa,
             $ref_curso, 
             $ref_campus) = $query->GetRowValues();

       $id = GetIdentity(seq_lm_id, true);

       $sql_insert = "insert into livro_matricula values " .
                     "('$id', '$ref_pessoa', '$ref_curso', '$ref_campus', " .
                     " '', '', " .
                     " '14', '$periodo_id') ";

       $ok = $conn->Execute($sql_insert);
 
       SaguAssert($ok,"Nao foi possivel inserir o registro! $ref_pessoa - $ref_curso - $sql_insert");
   }

   $query->Close();
   $conn->Close();

   /****************************************************************
   ** Limpa tabela
   ****************************************************************/
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">==> Limpando a Tabela...<BR> </font>");
   flush();

   $conn = new Connection;
   $conn->open();

   $sql = " update livro_matricula set " .
          "        ref_curso_anterior = null " .
          " where ref_status <> 6 and " .
          "       ref_status <> 7 and " .
          "       ref_status <> 10 and " .
          "       ref_status <> 11 and " .
          "       ref_status <> 147 and " .
          "       ref_status <> 148 ";

   $ok = @$conn->Execute($sql);
   
   $sql = " delete from livro_matricula " .
          " where ref_status = 99 or " .
          "       ref_status = 16 ";
          
   $ok = @$conn->Execute($sql);

   $query->Close();
   $conn->Close();
   
   /****************************************************************
   ** Update do periodo.
   ****************************************************************/

   $conn = new Connection;
   $conn->open();

   $sql = "update periodos set fl_livro_matricula = 1 where id='$periodo_id'";
   
   $ok = @$conn->Execute($sql);

   $query->Close();
   $conn->Close();
   
   echo("<br><br><b> Livro Matricula $ref_periodo Gerado com sucesso!!!</b><br><br>");
?>
<form name="myform" action="" >
  <p align="center">
     <input type="button" name="botao" value="&lt;&lt; Retornar" onclick="history.go(-1)">
</form>
</body>
</html>
