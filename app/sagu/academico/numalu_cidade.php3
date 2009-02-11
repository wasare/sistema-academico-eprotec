<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Cidade</title>
<script language="PHP">
function ListaCidades($id_periodo)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select get_cidade_pessoa(A.ref_pessoa), " .
          "        get_cidade(get_cidade_pessoa(A.ref_pessoa)), " .
          "        count(A.ref_pessoa) " .
          " from livro_matricula A, status_matricula B  " .
          " where A.ref_status = B.id and " .
    	  "       A.ref_periodo = '$id_periodo' and " .
          "       A.ref_curso_atual<>6 and " .
          "       B.fl_in_lm = 'f' " .
          " group by get_cidade_pessoa(A.ref_pessoa) " .
          " order by 3 desc;  " ;

   $query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

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

   while( $query->MoveNext() )
   {
     list ( $ref_cidade,
            $cidade, 
            $num ) = $query->GetRowValues();

     $campus = substr($campus, 7, 3);
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"2\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Cidade</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"2\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . " </b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição da Cidade</b></font></td>");
         echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$cidade</td>");
          echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$cidade</td>");
          echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num</td>");
          echo("  </tr>\n");
         }

     $i++;

     $total=$total+$num;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS POR CIDADE:</td>");
   echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"2\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaCidades($ref_periodo);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='livro_matricula_periodo_selecionado.php3?ref_periodo=<?echo($ref_periodo)?>'">
</div>
</form>
</body>
</html>
