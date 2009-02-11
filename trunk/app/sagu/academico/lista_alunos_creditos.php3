<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>
<? require("../lib/InvData.php3"); ?>

<html>
<head>
<title>Número de Alunos por Número de Créditos</title>
<script language="PHP">

CheckFormParameters(array("ref_curso",
                          "ref_campus",
                          "creditos",
                          "ref_periodo"));
                                                    
function ListaAlunos($ref_curso, $ref_campus, $creditos, $ref_periodo, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select get_campus($ref_campus), get_curso_abrv($ref_curso);";
   
   $query = $conn->CreateQuery($sql);

   if ($query->MoveNext())
   {
      list ($campus,
            $curso) = $query->GetRowValues();
   }
  
   $sql = " SELECT A.ref_pessoa, " .
          "        B.nome, ".
    	  "        B.rg_numero, " .
          "        B.fone_particular, " .
    	  "        B.fone_profissional, " .
          "        B.fone_celular " .
          " FROM contratos A, pessoas B ".
          " WHERE A.ref_pessoa = B.id AND " .
	      "       A.ref_curso = '$ref_curso' and " .
    	  "       A.ref_campus = '$ref_campus' and ";
          
          if($anterior=='true')
          {
          $sql .= " num_creditos2(A.ref_pessoa, '$ref_periodo', '$dt_livro_matricula') = '$creditos' and " .
	              " A.id in (select distinct ref_contrato " .
                  "          from matricula " .
                  "          where ref_periodo = '$ref_periodo' and " .
                  "                obs_aproveitamento = '' and " .
                  "                (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
	              " A.ref_pessoa in (select distinct ref_pessoa " .
                  "                  from matricula " .
                  "                  where ref_periodo = '$ref_periodo' and " .
                  "                  obs_aproveitamento = '' and " .
                  "                  (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
		          " (A.dt_desativacao is null or A.dt_desativacao > '$dt_livro_matricula') and ";
          }
          else
          {
          $sql .= " num_creditos2(A.ref_pessoa, '$ref_periodo', date(now())+1) = '$creditos' and " .
                  " A.ref_last_periodo='$ref_periodo' and " .
                  " A.id = is_matriculado_cntr('$ref_periodo', A.id) and " .
           	      " A.dt_desativacao is null and ";
	      }

   $sql.= "       A.ref_curso<>6 AND " .
          "       A.fl_ouvinte<>'1' " .
          " GROUP BY A.ref_curso, " .
          "          A.ref_campus, " .
          "          A.ref_pessoa, " .
    	  "          B.nome," .
	      "          B.rg_numero, " .
          "          B.fone_particular, " .
    	  "          B.fone_profissional, " .
          "          B.fone_celular " .
    	  " ORDER BY B.nome";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Alunos Matriculados em $creditos créditos no <br>$ref_curso: $curso - $campus no período $ref_periodo</b></font></td>");

   echo ("</tr>"); 

   if ($anterior == 'true')
   { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
   else
   { $periodo_antigo = " Não"; }
   echo("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo:<b>$periodo_antigo</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Identidade</b></font></td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone Particular</b></font></td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone Profissio</b></font></td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone Celular</b></font></td>");
   echo ("</tr>"); 

   $i=1;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";

   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   while( $query->MoveNext() )
   {
     list ( $ref_pessoa, 
            $pessoa_nome, 
            $rg_numero, 
            $fone_particular, 
            $fone_profissional, 
            $fone_celular) = $query->GetRowValues();
  
     if ( $i % 2 )
     {
        $bg = $bg1;
        $fg = $fg1;
     }
     else
     {
        $bg = $bg2;
        $fg = $fg2;
     }
     
     echo("<tr bgcolor=\"$bg\">\n");
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$i</b></td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_pessoa</td>");
     echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$pessoa_nome</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;$rg_numero</td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;$fone_particular</td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;$fone_profissional</td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;$fone_celular</td>");
     echo("  </tr>");

     $i++;

   }
   
   echo("<tr>");
   echo("<td colspan=\"7\" align=\"center\"><hr></td>");
   echo("</tr>\n");
   
   
   echo("<form>");
   echo("<tr>");
   echo("<td colspan=\"7\" align=\"center\">");
   echo("<input type=\"button\" name=\"Button\" value=\"  Voltar  \" onClick=\"history.go(-1)\"></td>");
   echo("</tr>\n");
   echo("</form>");

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<script language="PHP">
   ListaAlunos($ref_curso, $ref_campus, $creditos, $ref_periodo, $dt_livro_matricula, $anterior);
</script>
</body>
</html>
