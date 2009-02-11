<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>
<html>
<head>
<title>Alunos por Período e Dia</title>
<script language="PHP">

function ListaAlunos($ref_periodo, $ref_campus, $dia_semana, $turno)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select distinct " .
          "        A.ref_pessoa, " .
          "        get_dia_semana(C.dia_semana), " .
          "        get_turno(C.turno), " .
          "        pessoa_nome(A.ref_pessoa), " .
          "        pessoa_fone(A.ref_pessoa), " .
    	  "        professor_disciplina_ofer(C.ref_disciplina_ofer), " .
    	  "        C.num_sala, " .
    	  "        get_campus('$ref_campus') " .
          " from contratos A, matricula B, disciplinas_ofer_compl C " .
          " where A.ref_last_periodo='$ref_periodo' and " .
          "       B.ref_periodo='$ref_periodo' and " .
    	  "       C.dia_semana='$dia_semana' and " .
    	  "       C.turno='$turno' and " .
          "       A.ref_campus='$ref_campus' and " .
    	  "       A.dt_desativacao is null and" .
	      "       B.dt_cancelamento is null and" .
          "       A.ref_pessoa = B.ref_pessoa and " .
          "       A.id = B.ref_contrato and " .
    	  "       B.ref_disciplina_ofer = C.ref_disciplina_ofer " .
          " order by pessoa_nome(A.ref_pessoa);";


$query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

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
            $dia,
            $turno,
            $nome, 
            $fone, 
    	    $professor,
	        $sala,
            $campus) = $query->GetRowValues();
   
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Matriculados por Periodo/Campus/Dia da Semana/Turno</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Periodo: $ref_periodo </b></font></td>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Campus: $ref_campus - $campus </b></font></td>");
         echo ("</tr>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Dia da Semana: $dia</b></font></td>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Turno: $turno</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone</b></font></td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Professor</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Sala</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$fone</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$professor</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$sala</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$fone</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$professor</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$sala</td>");
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
    ListaAlunos($ref_periodo, $ref_campus, $dia_semana, $turno);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
