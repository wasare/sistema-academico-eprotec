<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Lista de Alunos por Cidade</title>
<script language="PHP">
function ListaAlunos($id_cidade, $ref_periodo, $bixo, $ref_curso, $ref_campus)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select distinct " .
          "        A.ref_pessoa, " .
          "        B.nome, " .
          "        B.rg_numero, " .
          "        B.cod_cpf_cgc, " .
          "        pessoa_fone(B.id), " .
          "        get_cidade(B.ref_cidade), " .
          "        A.ref_curso " .
          " from matricula A, pessoas B " .
          " where A.ref_periodo='$ref_periodo' and " .
          "       A.dt_cancelamento is null and " .
          "       A.ref_pessoa = B.id and " .
          "       A.ref_curso <> '6' and " .
          "       B.ref_cidade = '$id_cidade'";

      if ($ref_curso)
	  {
	     $sql .= " and A.ref_curso = '$ref_curso' ";
	  }

      if ($ref_campus)
	  {
	     $sql .= " and A.ref_campus = '$ref_campus' ";
	  }

	  if ($bixo == 'sim')
	  {
	     $sql .= " and is_calouro(B.id, '$ref_periodo') = 't' ";
	  }

	  $sql .= " order by B.nome; ";

   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $aux_curso=0;

   while( $query->MoveNext() )
   {
     list ($id,
           $nome,
           $rg,
           $cpf,
           $fone,
           $cidade,
           $ref_curso) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b><center>UNIVATES - Centro Universitário</center></b></font></td></tr>");
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Alunos Matriculados</b></font>");
	     if ($bixo == 'sim')
         {
             echo("<font color=\"red\"><b> - CALOUROS</b></font>");
         }
         echo("</td></tr>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Cidade : " . $cidade . " </b></font></td>");
         echo ("<td bgcolor=\"#000099\" colspan=\"1\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . " </b></font></td>");
         if ($ref_campus)
         {
            echo ("<td bgcolor=\"#000099\" colspan=\"2\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Campus: " . $ref_campus . " </b></font></td>");
         }
         else
         {
            echo ("<td bgcolor=\"#000099\" colspan=\"2\" height=\"28\">&nbsp;</td>");
         }
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>RG</b></font></td>");
         //echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>CPF</b></font></td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fones</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("  </tr>"); 
     }
     if ( $i % 2 )
     {
         echo("<tr bgcolor=\"$bg1\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$i</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$id</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$nome</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$rg</td>");
         //echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$cpf</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$fone</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$ref_curso</td>");
         echo("  </tr>");
      }
      else
      {
         echo("<tr bgcolor=\"$bg2\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$i</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$id</td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$nome</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$rg</td>");
         //echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$cpf</td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$fone</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$ref_curso</td>");
         echo("  </tr>\n");
      }

     $i++;

   }
    
   echo("<tr><td colspan=\"6\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
  ListaAlunos($id_cidade, $id_periodo, $bixo, $id_curso, $id_campus);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
