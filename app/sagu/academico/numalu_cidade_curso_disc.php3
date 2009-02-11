<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Curso</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
function ListaCidades($id_periodo)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select get_cidade_pessoa(A.ref_pessoa), ".
          "        get_cidade(get_cidade_pessoa(A.ref_pessoa)), ".
          "        A.ref_curso_atual, curso_desc(A.ref_curso_atual), A.ref_campus_atual, ".
          "        get_campus(A.ref_campus_atual), C.ref_disciplina, ".
          "        descricao_disciplina(C.ref_disciplina), count(*) ".
          " from livro_matricula A, status_matricula B, matricula C ".
          " where A.ref_pessoa = C.ref_pessoa and " .
          "       A.ref_curso_atual = C.ref_curso and " .
          "       A.ref_campus_atual = C.ref_campus and " .
          "       A.ref_status = B.id and " .
          "       A.ref_periodo = '$id_periodo' and " .
          "       A.ref_curso_atual<>6 and " .
          "       B.fl_in_lm = 'f' ".
          " group by get_cidade_pessoa(A.ref_pessoa), " .
          "          A.ref_curso_atual, " .
          "          A.ref_campus_atual, " .
          "          C.ref_disciplina ".
          " order by A.ref_campus_atual, " .
          "          A.ref_curso_atual, " .
          "          C.ref_disciplina, " .
          "          get_cidade(get_cidade_pessoa(A.ref_pessoa))";
          
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

   $aux_curso = -1;
   $aux_campus = -1;
   $aux_disciplina = -1;

   while( $query->MoveNext() )
   {
     list ( $ref_cidade,
            $cidade, 
            $ref_curso,
            $curso,
            $ref_campus,
            $campus,
            $ref_disciplina,
            $disciplina,
            $num ) = $query->GetRowValues();

     $href  = "<a href=\"javascript:Select_Cidade('$ref_cidade', '$id_periodo')\"> " . $ref_cidade . "</a>";
     
     if ($i == 1)
      {
          echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Cidade/Curso</b></font></td>");
          echo ("<tr>");
          echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . "</b></font></td>");
          echo ("</tr>"); 
          echo ("<tr bgcolor=\"#000000\">\n");
          echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
          echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição da Cidade</b></font></td>");
          echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
          echo ("  </tr>"); 
      }

     if(($aux_curso != $ref_curso) || ($aux_campus != $ref_campus) || ($aux_disciplina != $ref_disciplina))
     {

        if($i!=1)
        {
           echo("<tr bgcolor=\"#CCCCCC\">\n");
           echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\">&nbsp;</td>");
           echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total do Curso:</b></td>");
           echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total</b></td>");
           echo("  </tr>");
           $total = 0;
        }

        echo ("<tr>");
        echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><hr></font></td>");
        echo ("</tr>");         

        echo ("<tr>");
        echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>$ref_curso - $curso - $campus - $disciplina</b></font></td>");
        echo ("</tr>");         
        $aux_curso = $ref_curso;
        $aux_campus = $ref_campus;
        $aux_disciplina = $ref_disciplina;
     }

     if ( $i % 2 )
        {
           echo("<tr bgcolor=\"$bg1\">\n");
           echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");
           echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$cidade</td>");
           echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num</td>");
           echo("  </tr>");
         }
      else
         {
           echo("<tr bgcolor=\"$bg2\">\n");
           echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");
           echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$cidade</td>");
           echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num</td>");
           echo("  </tr>\n");
         }

     $i++;

     $total=$total+$num;
     $total_geral=$total_geral+$num;

   }


   echo("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\">&nbsp;</td>");
   echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total do Curso:</b></td>");
   echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total</b></td>");
   echo("  </tr>");
   $total = 0;

        echo ("<tr>");
        echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><hr></font></td>");
        echo ("</tr>");         

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL DE ALUNOS:</b></td>");
   echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_geral</b></td>");
   echo("  </tr>\n");

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
?>
<script language="JavaScript">
function Select_Cidade(ref_cidade, id_periodo)
{
  var url = "cons_numalu_selec_cidade.php3" +
            "?id_cidade=" + escape(ref_cidade) + 
            "&id_periodo=" + escape(id_periodo);

  location = url; 
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<?
   ListaCidades($ref_periodo);
?>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
