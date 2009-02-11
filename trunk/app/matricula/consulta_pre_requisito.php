<?php 
require("../../lib/common.php");
require("../../lib/config.php");

function ListaPreRequisitos( $ref_ofer )
{
   
   $conn = new Connection;

   $conn->open();

   $sql = " select id, " .
          "        ref_curso, " .
          "        curso_desc(curso_disciplina_ofer($ref_ofer)), " .
          "        ref_disciplina, " .
          "        descricao_disciplina(ref_disciplina), " .
          "        ref_disciplina_pre, " .
          "        descricao_disciplina(ref_disciplina_pre) " .
	      " from pre_requisitos ";
          
   if ( is_numeric($ref_ofer) ) 
   {
      $sql .= " where ref_disciplina IN ( select get_disciplina_de_disciplina_of($ref_ofer) )";
   }
          
   $sql .= " order by ref_curso ;";

   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"8\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Pr&eacute;-requisitos Cadastrados</b></font></td>");
   echo ("</tr>"); 

   $i=1;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#DDDDFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $aux_curso = -1;
   
   while( $query->MoveNext() )
   {
     list ( $id, 
            $ref_curso,
            $nome_curso,
	        $ref_disciplina,
	        $disciplina,
            $ref_disciplina_pre,
	        $disciplina_pre) = $query->GetRowValues();

   
     if ($aux_curso != $ref_curso)
     {
        echo ("<tr>");
        echo ("<td bgcolor=\"#FFFFFF\" colspan=\"7\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000077\"><b>&nbsp;<br>$ref_curso - $nome_curso<br>&nbsp;</b></font></td>");
        echo ("</tr>"); 

        echo ("<tr bgcolor=\"#000000\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
        echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód - Disciplina</b></font></td>");
        echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód - Disciplina Pré-requisito</b></font></td>");
        echo ("<td width=\"34%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso&nbsp;&nbsp;</b></font></td>");
        echo ("</tr>"); 

        $aux_curso = $ref_curso;
     }

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
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
     echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina - $disciplina</td>");
     echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina_pre - $disciplina_pre</td>");
     echo ("<td width=\"34%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso&nbsp;&nbsp;</td>");
     echo("  </tr>");
     
     $i++;

   }

   echo("<tr><td colspan=\"8\" align=\"center\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

}
?> 
<html> 
<head> 
<title>Consulta pr&eacute;-requisito</title> 
</head>

<body bgcolor="#FFFFFF">
  <p>
    <?php ListaPreRequisitos($_GET['o']); ?>
  </p>
</body>
</html>
