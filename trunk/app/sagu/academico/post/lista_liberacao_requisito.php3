<? require("../../../../lib/common.php"); ?>
<?
CheckFormParameters(array('ref_periodo'));
?>
<html>
<head>
<title>Listagem de Liberação de Pré-Requisito</title>

<script language="PHP">
function Lista_Alunos($ref_periodo)
{
    $conn = new Connection;

    $conn->open();

    $sql = " select ref_pessoa, " .
           "        pessoa_nome(ref_pessoa), " .
           "        get_curso_contrato(ref_contrato), " .
           "        curso_desc(get_curso_contrato(ref_contrato)), " .
           "        get_campus_contrato(ref_contrato), " .
           "        get_campus(get_campus_contrato(ref_contrato)), " .
           "        ref_disciplina, " .
           "        descricao_disciplina(ref_disciplina) " .
           " from matricula " .
           " where ref_periodo = '$ref_periodo' and " .
           "       status_disciplina = 't' and " .
           "       dt_cancelamento is null and " .
           "       obs_aproveitamento = '' " .
           " order by get_curso_contrato(ref_contrato), " .
           "          get_campus_contrato(ref_contrato), " .
           "          ref_disciplina, " .
           "          pessoa_nome(ref_pessoa);";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $aux_ref_curso = -1;
   $aux_ref_campus = -1;
   $aux_ref_disciplina = -1;
   
   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $i=0;
   $j=1;
   
   while( $query->MoveNext() )
   {
        list ($ref_pessoa,
              $nome_pessoa,
              $ref_curso,
              $nome_curso,
              $ref_campus,
              $nome_campus,
              $ref_disciplina,
              $descricao_disciplina) = $query->GetRowValues();
        
        if ($i == 0)
        {
	        echo("<tr>");
            echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"3\"><font size=\"3\" face=\"Verdana\" color=\"#FFFFFF\"><center><b>Listagem de alunos que se matricularam em $ref_periodo com liberação de pré-requisito</b></center></font></td>");
	        echo("</tr>");
        }

        if (($ref_curso != $aux_ref_curso) || ($ref_campus != $aux_ref_campus))
        {
            $aux_ref_curso = $ref_curso;
            $aux_ref_campus = $ref_campus;

            echo("<tr><td colspan=\"3\" align=\"center\"><hr></td></tr>");

            if ($i!=0)
            {
                $x = $j - 1;
                echo("<tr bgcolor=\"#000000\">\n");
                echo ("<td colspan=\"3\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"white\"><b>Total do Curso: $x</b></td>");
                echo("</tr>");
            }
            
            echo("<tr><td colspan=\"3\" align=\"center\"><hr></td></tr>");
            
            echo("<tr bgcolor=\"#000099\">\n");
            echo ("<td colspan=\"3\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"white\"><b>Curso: $ref_curso - $nome_curso - Campus: $nome_campus</b></td>");
            echo("</tr>");
            
            echo("<tr><td colspan=\"3\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>$ref_disciplina - $descricao_disciplina</b></font></td></tr>");
            $aux_ref_disciplina = $ref_disciplina;
            $j = 1;
        }

        if ($ref_disciplina != $aux_ref_disciplina)
        {
            echo("<tr><td colspan=\"3\" align=\"center\"><hr></td></tr>");
            echo("<tr><td colspan=\"3\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>$ref_disciplina - $descricao_disciplina</b></font></td></tr>");
            $aux_ref_disciplina = $ref_disciplina;
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
        echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$j</b></font></td>");
        echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$ref_pessoa</b></font></td>");
        echo("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$nome_pessoa</b></font></td>");
        echo("</tr>");

        $i++;
        $j++;
        
   }


   echo("<tr><td colspan=\"3\" align=\"center\"><hr></td></tr>");

   $x = $j - 1;
   echo("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"white\"><b>Total do Curso: $x</b></td>");
   echo("</tr>");

   echo("<tr><td colspan=\"3\" align=\"center\"><hr></td></tr>");

   echo("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"white\"><b>Total Geral: $i</b></td>");
   echo("</tr>");

   echo ("<tr><td colspan=\"3\" align=\"center\"><input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\"></td></tr>\n");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Alunos($ref_periodo);
  </script>
</form>
</body>
</html>
