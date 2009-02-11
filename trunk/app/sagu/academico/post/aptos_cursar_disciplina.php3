<? require("../../../../lib/common.php"); ?>
<? require("../../lib/GetField.php3"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title>Total de alunos que cursaram determinada disciplina</title>

<?
 CheckFormParameters(array("ref_periodo",
                           "ref_curso",
                           "ref_campus",
                           "ref_disciplina",
                           "ref_status"));
?>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="">

<?
$conn = new Connection;

$conn->Open();

if ($ref_status == 0)
{
   $nome_status = 'CURSADA';
}
elseif ($ref_status == 1)
{
  $nome_status = 'APTOS A CURSAR';
}
elseif ($ref_status == 2)
{
  $nome_status = 'BLOQUEADA';
}

$sql = " select descricao_disciplina from disciplinas where id = '$ref_disciplina'";

$query = $conn->CreateQuery($sql);

if ( $query->MoveNext() )
{
    list ($nome_disciplina) = $query->GetRowValues();
}
else
{
    SaguAssert(0, "Disciplina Inválida!!!");
}

$query->Close();

$sql = " select ref_pessoa, " .
       "        pessoa_nome(ref_pessoa), " .
       "        pessoa_fone(ref_pessoa), " .
       "        get_email(ref_pessoa), " .
       "        ref_disciplina " .
       " from disciplinas_todos_alunos " .
       " where ref_disciplina = '$ref_disciplina' and " .
       "       ref_curso = '$ref_curso' and " .
       "       ref_campus = '$ref_campus' and " .
       "       status = '$ref_status' " .
       " order by pessoa_nome(ref_pessoa);";

$query = $conn->CreateQuery($sql);

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

echo ("<tr>");
echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de alunos com status $nome_status em determinada disciplina<br>$ref_disciplina - $nome_disciplina</b></font></td>");
echo ("</tr>");

echo ("<tr bgcolor=\"#000000\">\n");
echo ("<td width=\"5%\"><font face=\"verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
echo ("<td width=\"10%\"><font face=\"verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
echo ("<td width=\"35%\"><font face=\"verdana\" size=\"2\" color=\"#ffffff\"><b>Nome do Aluno</b></font></td>");
echo ("<td width=\"35%\"><font face=\"verdana\" size=\"2\" color=\"#ffffff\"><b>Fone</b></font></td>");
echo ("<td width=\"15%\" align=\"right\"><font face=\"verdana\" size=\"2\" color=\"#ffffff\"><b>E-mail</b></font></td>");
echo ("</tr>"); 

while( $query->MoveNext() )
{
   list ( $ref_pessoa,
          $pessoa_nome,
    	  $pessoa_fone,
	      $email,
       	  $ref_disciplina) = $query->GetRowValues();

   $href = "<a href=\"../consultas_diversas.phtml?pessoa=$ref_pessoa&periodo=$ref_periodo\">$i</a>";

   echo ("<tr bgcolor=\"$bg1\">\n");
   echo ("<td width=\"5%\"><font face=\"verdana\" size=\"2\" color=\"$fg1\"><b>$href</b></font></td>");
   echo ("<td width=\"10%\"><font face=\"verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</font></td>");
   echo ("<td width=\"35%\"><font face=\"verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</font></td>");
   echo ("<td width=\"35%\"><font face=\"verdana\" size=\"2\" color=\"$fg1\">$pessoa_fone</font></td>");
   echo ("<td width=\"15%\" align=\"right\"><font face=\"verdana\" size=\"2\" color=\"$fg1\">&nbsp;$email</font></td>");
   echo ("</tr>"); 

   $sql = " select ref_disciplina,  " .
          "        descricao_disciplina(ref_disciplina), " .
	      "        dia_disciplina_ofer(ref_disciplina_ofer), " .
    	  "        turno_disciplina_ofer(ref_disciplina_ofer) " .
          " from matricula " .
          " where ref_pessoa = '$ref_pessoa' and " .
          "       ref_periodo = '$ref_periodo' and " .
          "       dt_cancelamento is null ";

   $query2 = $conn->CreateQuery($sql);

   echo("<tr bgcolor=\"$bg2\">\n");
   echo ("<td colspan=\"5\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Disciplinas Matriculadas em $ref_periodo</b></td>");
   echo("</tr>\n");
   echo("<tr bgcolor=\"$bg2\">\n");
   echo ("<td colspan=\"5\">");

   $loop = 'false';

   while ( $query2->MoveNext() )
   {
     list ( $ref_disciplina,
            $nome_disciplina,
	        $dia_semana,
	        $turno) = $query2->GetRowValues();
       
	  echo ("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">");
	  echo ("<tr>");
      echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\"><img src=\"../images/checkon.gif\"></td>");
      echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
      echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_disciplina</td>");
      echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dia_semana" . "ª-feira</td>");
      echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$turnos[$turno]</td>");
      echo ("</tr>\n");
      echo ("</table>\n");
	  $loop = 'true';
   }
          
  echo ("</td>\n");
  echo ("</tr>\n");
  if ($loop == 'false')
  {
     echo("<tr bgcolor=\"$bg2\">\n");
     echo ("<td colspan=\"5\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"red\"><b>Não está matriculado em nenhuma disciplina no semestre $ref_periodo</b></td>");
     echo("</tr>\n");
  }
  echo("<tr bgcolor=\"$bg2\">\n");
  echo ("<td colspan=\"5\" align=\"center\"><hr></td>");
  echo("</tr>\n");

  
  $query2->Close();
  
  $i++;
}

echo("</table></center>");

$query->Close();

$conn->Close();

?>
 <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
  </div>
</form>
</body>
</html>
