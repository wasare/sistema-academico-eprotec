<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>

<html>
<head>
<title>Lista de Alunos Calouros Matriculados por Sala</title>
<script language="PHP">
function ListaAlunos($num_sala, $dia, $turno, $ref_periodo, $ref_vestibular, $ref_disciplina_ofer)
{
   $conn = new Connection;

   $conn->Open();

   $sql = " select ref_pessoa, " .
          "        pessoa_nome(ref_pessoa), " .
          "        ref_curso, " .
          "        ref_disciplina, " .
          "        descricao_disciplina(ref_disciplina), " .
    	  "        get_ref_professor(ref_disciplina_ofer), pessoa_nome(get_ref_professor(ref_disciplina_ofer)), " .
          "        get_turno('$turno'), " .
          "        get_dia_semana('$dia') " .
          " from matricula " .
          " where ref_periodo = '$ref_periodo' and " .
	      "       trim(num_sala_disciplina_ofer_todos(ref_disciplina_ofer))='$num_sala' and " .
          "       dia_disciplina_ofer_todos(ref_disciplina_ofer) = '$dia' and " .
          "       turno_disciplina_ofer_todos(ref_disciplina_ofer) = '$turno' and " .
    	  "       is_calouro(ref_pessoa, '$ref_vestibular') = 't' and " .
	      "       num_creditos(ref_pessoa, '$ref_periodo') > 0 and " .
    	  "       dt_cancelamento is null " .
          " order by 3, 2; ";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"620\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

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
     list ($ref_pessoa,
           $nome,
           $ref_curso,
           $ref_disciplina,
           $descricao_disciplina,
    	   $ref_professor, 
           $nome_professor,
           $turno,
           $dia) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Alunos Calouros Matrículados por Sala</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"2\" width=\"30%\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Sala-Prédio: " . $num_sala . "</b></font></td>");
         echo ("<td bgcolor=\"#000099\" colspan=\"1\" width=\"40%\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Turno: $turno <br> Professor $ref_professor - $nome_professor" . "</b></font></td>");
         echo ("<td bgcolor=\"#000099\" colspan=\"1\" width=\"30%\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Dia: $dia </b></font></td>");
         echo ("</tr>"); 
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Disciplina : " . $ref_disciplina . " - " . $descricao_disciplina . " </b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"75%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("  </tr>"); 
        }
     
     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$ref_pessoa</td>");
          echo ("<td width=\"75%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$nome</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$ref_curso</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$ref_pessoa</td>");
          echo ("<td width=\"75%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$nome</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$ref_curso</td>");
          echo("  </tr>\n");
         }

     $i++;

   }

   echo("<tr><td colspan=\"4\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
 ListaAlunos($num_sala, $dia, $turno, $ref_periodo, $ref_vestibular, $ref_disciplina_ofer);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
