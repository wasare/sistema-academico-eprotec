<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Cidade</title>
<script language="PHP">
function ListaCidades($id_periodo, $bixo, $ref_curso, $ref_campus)
{
   $conn = new Connection;

   $conn->Open();

   $total=0;

   $sql = " select B.ref_cidade, " .
          "        get_cidade(B.ref_cidade) as cidade, " .
          "        count(distinct(A.ref_pessoa)) as n" .
          " from matricula A, pessoas B  " .
          " where A.ref_periodo = '$id_periodo' and " .
          "       A.dt_cancelamento is null and  " .
          "       A.ref_pessoa = B.id and" .
          "       A.ref_curso <> 6 ";

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
	        $sql .= " and is_calouro(B.id, '$id_periodo') = 't' ";
	      }
          
    	  $sql .= " group by B.ref_cidade " .
                  " order by cidade, n desc;  " ;

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

   while( $query->MoveNext() )
   {
     list ( $ref_cidade,
            $cidade, 
            $num ) = $query->GetRowValues();

     $href  = "<a href=\"javascript:Select_Cidade('$ref_cidade', '$id_periodo', '$bixo', '$ref_curso', '$ref_campus')\"> " . $cidade . "</a>";
     $href1  = "<a href=\"javascript:Select_Cidade_Curso('$ref_cidade', '$id_periodo', '$bixo', '$ref_curso', '$ref_campus')\"> " . Curso . "</a>";
     
     if ($i == 1)
     {
        echo ("<tr><td bgcolor=\"#000099\" colspan=\"3\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>UNIVATES - Centro Universitário</b></font></td></tr>");
        echo ("<tr><td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Alunos Matriculados por Cidade</b></font>");
	    if ($bixo == 'sim')
        {
	        echo("<font color=\"red\"><b> - CALOUROS</b></font>");
	    }
	    echo("</td></tr>");
        echo ("<tr>");
        echo ("<td bgcolor=\"#000099\" height=\"28\" colspan=\"2\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . " </b></font></td>");

	    if ($ref_campus)
	    {
            echo ("<td bgcolor=\"#000099\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Campus: " . $ref_campus . " </b></font></td>");
	    }
        else
        {
            echo ("<td bgcolor=\"#000099\" height=\"28\">&nbsp;</td>");
        }
        echo ("</tr>"); 
        echo ("<tr bgcolor=\"#000000\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
        echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição da Cidade</b></font></td>");
        echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
        echo ("  </tr>"); 
     }

     if ( $i % 2 )
     {
        echo("<tr bgcolor=\"$bg1\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
        echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href ...$href1</td>");
        echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num</td>");
        echo("  </tr>");
     }
     else
     {
        echo("<tr bgcolor=\"$bg2\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
        echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href ...$href1</td>");
        echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num</td>");
        echo("  </tr>\n");
     }

     $i++;

     $total=$total+$num;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp;</td>");
   echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS POR CIDADE:</td>");
   echo ("<td width=\"30%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"3\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
<script language="JavaScript">
function Select_Cidade(ref_cidade, id_periodo, bixo, ref_curso, ref_campus)
{
  var url = "cons_numalu_selec_cidade.php3" +
            "?id_cidade=" + escape(ref_cidade) + 
            "&id_periodo=" + escape(id_periodo) +
            "&id_curso=" + escape(ref_curso) +
            "&id_campus=" + escape(ref_campus) +
            "&bixo=" + escape(bixo);

  location = url; 
}

function Select_Cidade_Curso(ref_cidade, id_periodo, bixo, curso, campus)
{
  if (curso == '')
  {
    var curso = prompt("Digite o Código do Curso?\n\nPS.: Se não informar nada o relatório\nserá de todos os cursos...","");
  }
  
  var url = "cons_numalu_selec_cidade_curso.php3" +
            "?id_cidade=" + escape(ref_cidade) + 
            "&id_periodo=" + escape(id_periodo) +
            "&id_campus=" + escape(campus) +
            "&id_curso=" + escape(curso) +
            "&bixo=" + escape(bixo);

  location = url; 
}

</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
    CheckFormParameters(array("ref_periodo",
                              "bixo"));

    ListaCidades($ref_periodo, $bixo, $ref_curso, $ref_campus);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='cons_numalu_cidade.phtml'">
</div>
</form>
</body>
</html>
