<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Relatório de Cancelamentos</title>

<script language="PHP">
  CheckFormParameters(array("periodo_id","dt_inicial","dt_final"));
  $dt_inicial = InvData($dt_inicial);
  $dt_final   = InvData($dt_final);
</script>

<script language="PHP">
function gera_relatorio($periodo_id, $dt_inicial, $dt_final)
{
   global $href;

   $conn = new Connection;

   $conn->open();
   
   // INICIO NOMES TRANCAMENTOS
 
   $sql = " SELECT count(distinct A.ref_pessoa) " .
          " FROM matricula A, contratos B " .
	  " WHERE A.ref_contrato = B.id and " .
          "       A.dt_cancelamento >= '$dt_inicial' and " .
          "       A.dt_cancelamento <= '$dt_final' and " .
          "       A.ref_periodo = '$periodo_id' and " .
          "       A.dt_cancelamento is not null and " .
          "       B.dt_desativacao is not null " .
	  " GROUP BY A.ref_pessoa";

   $query = $conn->CreateQuery($sql);
  
   if ($query->MoveNext())
   {
      $soma_alunos = $query->GetRowCount();
   }
   
   $query->Close();
 
   $sql = " SELECT A.ref_pessoa, " .
          "        pessoa_nome(A.ref_pessoa), " .
	  "        A.ref_curso, " .
	  "        A.ref_disciplina, " .
	  "        descricao_disciplina(A.ref_disciplina), " .
          "        B.ref_motivo_desativacao, " .
          "        motivo(B.ref_motivo_desativacao), " .
	  "        professor_disciplina_ofer(A.ref_disciplina_ofer) " .
	  " FROM matricula A, contratos B " .
	  " WHERE A.ref_contrato = B.id and " .
	  "       A.dt_cancelamento >= '$dt_inicial' and " .
	  "       A.dt_cancelamento <= '$dt_final' and " .
	  "       A.ref_periodo = '$periodo_id' and " .
	  "       A.dt_cancelamento is not null and " .
	  "       B.dt_desativacao is not null " .
	  "       order by professor_disciplina_ofer(A.ref_disciplina_ofer), pessoa_nome(A.ref_pessoa); ";

   $query = $conn->CreateQuery($sql);

   $soma_disciplinas = 0;
   $aux_nome_professor = '-1';
   $ctrl = 0;
   $i = 1;

   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";

   $fg1 = "#000099";
   $fg2 = "#000099";
   
   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
  
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<tr><td bgcolor=\"#000099\" colspan=\"7\" height=\"40\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Alunos que efetuaram Trancamentos [ " . InvData($dt_inicial) . " a " . InvData($dt_final) . " ]</b></center></font></td></tr>");
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nome</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Motivo</b></font></td>");
   echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Curso</b></font></td>");
   echo ("  </tr>"); 
   
   while( $query->MoveNext() )
   {
       list ($ref_pessoa,
             $nome_pessoa,
             $ref_curso,
             $ref_disciplina,
             $descricao_disciplina,
             $ref_motivo_desativacao,
             $desc_motivo_desativacao,
	     $nome_professor) = $query->GetRowValues();
   
     $ctrl++;
   
     if ($aux_nome_professor != $nome_professor)
     {
       echo ("<tr><td bgcolor=\"#cccccc\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>Professor: $nome_professor</b></font></td></tr>");
       $aux_nome_professor = $nome_professor;
     }
    
     if ( $i % 2 )
     {
         echo ("<tr bgcolor=\"$bg1\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$descricao_disciplina</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_motivo_desativacao</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$desc_motivo_desativacao</td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
         echo ("  </tr>");
     }
     else
     {
         echo ("<tr bgcolor=\"$bg2\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_pessoa</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$descricao_disciplina</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_motivo_desativacao</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$desc_motivo_desativacao</td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
         echo ("  </tr>\n");
     }
   $i = $i + 1;
   $soma_disciplinas = $soma_disciplinas + 1;
   }
   
   if ($ctrl == 0)
   {
   echo ("<tr><td bgcolor=\"#ffffff\" colspan=\"7\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"red\"><b>Não houve trancamentos neste periodo</b></font></td></tr>");
   $soma_alunos = 0;
   }
   
   echo ("<tr><td bgcolor=\"#000000\" colspan=\"7\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE DISCIPLINAS: $soma_disciplinas</b></font></td></tr>");
   echo ("<tr><td bgcolor=\"#000000\" colspan=\"7\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE ALUNOS: $soma_alunos</b></font></td></tr>");

   echo("</table></center>");
   
   $query->Close();
   
   /*
   FIM NOMES TRANCAMENTOS

   INICIO NOMES CANCELAMENTOS
   */

   $sql = " SELECT count(distinct A.ref_pessoa) " .
	  " FROM matricula A, contratos B " .
	  " WHERE A.ref_contrato = B.id and " .
	  "       A.dt_cancelamento >= '$dt_inicial' and " .
	  "       A.dt_cancelamento <= '$dt_final' and " .
	  "       A.ref_periodo = '$periodo_id' and " .
	  "       A.dt_cancelamento is not null and " .
	  "	  B.dt_desativacao is null " .
	  " GROUP BY A.ref_pessoa";

   $query = $conn->CreateQuery($sql);
  
   if ($query->MoveNext())
   {
      $soma_alunos = $query->GetRowCount();
   }
   
   $query->Close();


   $sql = " SELECT A.ref_pessoa, " .
          "        pessoa_nome(A.ref_pessoa), " .
          "        A.ref_curso, " .
          "        A.ref_disciplina, " .
          "        descricao_disciplina(A.ref_disciplina), " .
          "        A.ref_motivo_cancelamento, " .
          "        motivo(A.ref_motivo_cancelamento), " .
	  "        professor_disciplina_ofer(A.ref_disciplina_ofer) " .
	  " FROM matricula A, contratos B " .
	  " WHERE A.ref_contrato = B.id and " .
	  "       A.dt_cancelamento >= '$dt_inicial' and " .
	  "       A.dt_cancelamento <= '$dt_final' and " .
	  "       A.ref_periodo = '$periodo_id' and " .
	  "       A.dt_cancelamento is not null and " .
	  "	  B.dt_desativacao is null " .
	  "       order by professor_disciplina_ofer(A.ref_disciplina_ofer), pessoa_nome(A.ref_pessoa); ";
	  
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"40\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Alunos que efetuaram Cancelamentos [ " . InvData($dt_inicial) . " a " . InvData($dt_final) . " ]</b></center></font></td>");
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nome</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Motivo</b></font></td>");
   echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Curso</b></font></td>");
   echo ("  </tr>"); 
   
   $soma_disciplinas = 0;
   $aux_nome_professor = '-1';
   $ctrl = 0;
   $i = 1;
  
   while( $query->MoveNext() )
   {
       list ($ref_pessoa,
             $nome_pessoa,
             $ref_curso,
             $ref_disciplina,
             $descricao_disciplina,
             $ref_motivo_cancelamento,
             $desc_motivo_cancelamento,
	     $nome_professor) = $query->GetRowValues();
 
     $ctrl++;
 
     if ($aux_nome_professor != $nome_professor)
     {
       echo ("<tr><td bgcolor=\"#cccccc\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>Professor: $nome_professor</b></font></td></tr>");
       $aux_nome_professor = $nome_professor;
     }
   
     if ( $i % 2 )
     {
         echo ("<tr bgcolor=\"$bg1\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$descricao_disciplina</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_motivo_cancelamento</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$desc_motivo_cancelamento</td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
         echo ("  </tr>");
     }
     else
     {
         echo ("<tr bgcolor=\"$bg2\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_pessoa</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$descricao_disciplina</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_motivo_cancelamento</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$desc_motivo_cancelamento</td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
         echo ("  </tr>\n");
     } 
   $i = $i + 1;
   $soma_disciplinas = $soma_disciplinas + 1;
   }

   if ($ctrl == 0)
   {
   echo ("<tr><td bgcolor=\"#ffffff\" colspan=\"7\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"red\"><b>Não houve cancelamentos neste periodo</b></font></td></tr>");
   $soma_alunos = 0;
   }

   echo ("<tr><td bgcolor=\"#000000\" colspan=\"7\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE DISCIPLINAS: $soma_disciplinas</b></font></td></tr>");
   
   echo ("<tr><td bgcolor=\"#000000\" colspan=\"7\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE ALUNOS: $soma_alunos</b></font></td></tr>");

   echo("</table></center>");
    
   $query->Close();

   // FIM CANCELAMENTOS
   
   // INICIO NOMES ACRESCIMOS
  
   $sql = " SELECT count(distinct A.ref_pessoa) " .
	  " FROM matricula A, contratos B " .
	  " WHERE A.ref_contrato = B.id and " .
	  "       A.dt_matricula >= '$dt_inicial' and " .
	  "       A.dt_matricula <= '$dt_final' and " .
	  "       A.ref_periodo = '$periodo_id' and " .
	  "       A.dt_matricula is not null and " .
	  "	  A.dt_cancelamento is null and " .
	  "	  B.dt_desativacao is null " .
	  " GROUP BY A.ref_pessoa";

   $query = $conn->CreateQuery($sql);
  
   if ($query->MoveNext())
   {
      $soma_alunos = $query->GetRowCount();
   }
   
   $query->Close();

  
   $sql = " SELECT A.ref_pessoa, " .
          "        pessoa_nome(A.ref_pessoa), " .
          "        A.ref_curso, " .
          "        A.ref_disciplina, " .
          "        descricao_disciplina(A.ref_disciplina), " .
          "        A.ref_motivo_matricula, " .
          "        motivo(A.ref_motivo_matricula), " .
	  "        professor_disciplina_ofer(A.ref_disciplina_ofer) " .
	  " FROM matricula A, contratos B " .
	  " WHERE A.ref_contrato = B.id and " .
	  "       A.dt_matricula >= '$dt_inicial' and " .
	  "       A.dt_matricula <= '$dt_final' and " .
	  "       A.ref_periodo = '$periodo_id' and " .
	  "       A.dt_matricula is not null and " .
	  "	  A.dt_cancelamento is null and " .
	  "	  B.dt_desativacao is null " .
	  "       order by professor_disciplina_ofer(A.ref_disciplina_ofer), pessoa_nome(A.ref_pessoa); ";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"40\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Alunos que efetuaram Acréscimos [ " . InvData($dt_inicial) . " a " . InvData($dt_final) . " ]</b></center></font></td>");
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nome</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Motivo</b></font></td>");
   echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Curso</b></font></td>");
   echo ("  </tr>"); 
   
   $soma_disciplinas = 0;
   $aux_nome_professor = '-1';
   $ctrl = 0;
   $i = 1;
  
   while( $query->MoveNext() )
   {
       list ($ref_pessoa,
             $nome_pessoa,
             $ref_curso,
             $ref_disciplina,
             $descricao_disciplina,
             $ref_motivo_acrescimo,
             $desc_motivo_acrescimo,
	     $nome_professor) = $query->GetRowValues();
   
     $ctrl++;
   
     if ($aux_nome_professor != $nome_professor)
     {
       echo ("<tr><td bgcolor=\"#cccccc\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>Professor: $nome_professor</b></font></td></tr>");
       $aux_nome_professor = $nome_professor;
     }
   
     if ( $i % 2 )
     {
         echo ("<tr bgcolor=\"$bg1\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$descricao_disciplina</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_motivo_acrescimo</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$desc_motivo_acrescimo</td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
         echo ("  </tr>");
     }
     else
     {
         echo ("<tr bgcolor=\"$bg2\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_pessoa</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$descricao_disciplina</td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_motivo_acrescimo</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$desc_motivo_acrescimo</td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
         echo ("  </tr>\n");
     } 
   $i = $i + 1;
   $soma_disciplinas = $soma_disciplinas + 1;
   }

   if ($ctrl == 0)
   {
   echo ("<tr><td bgcolor=\"#ffffff\" colspan=\"7\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"red\"><b>Não houve acréscimos neste periodo</b></font></td></tr>");
   $soma_alunos = 0;
   }

   echo ("<tr><td bgcolor=\"#000000\" colspan=\"7\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE DISCIPLINAS: $soma_disciplinas</b></font></td></tr>");
   
   echo ("<tr><td bgcolor=\"#000000\" colspan=\"7\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE ALUNOS: $soma_alunos</b></font></td></tr>");

   echo("</table></center>");
    
   $query->Close();

   //FIM NOMES ACRESCIMOS

   @$conn->Close();

}

</script>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="" name="myform">
<script language="PHP">
  gera_relatorio($periodo_id, $dt_inicial, $dt_final);
</script>
</form>
</body>
</html>
