<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>

<html>
<head>
<title>Alunos por Dia</title>
<script language="PHP">

CheckFormParameters(array("periodo_id",
                          "campus_id"));
                                                    
function ListaAlunos($ref_periodo, $ref_campus)
{
   global $turnos;

   $conn = new Connection;

   $conn->open();

   $sql = " select nome_campus from campus where id = '$ref_campus';";
   
   $query = $conn->CreateQuery($sql);

   if ($query->MoveNext())
   {
      $nome_campus = $query->GetValue(1); 
   }
   
   $sql = " select distinct " .
          "        A.ref_pessoa, " .
          "        B.nome, " .
    	  "        B.rg_numero, " .
	      "        B.fone_particular, " .
    	  "        B.fone_profissional, " .
	      "        B.fone_celular " .
    	  " from matricula A, pessoas B " .
	      " where A.ref_pessoa = B.id and " .
    	  "       A.ref_campus = '$ref_campus' and " .
	      "       A.ref_periodo = '$ref_periodo' and " .
    	  "       A.dt_cancelamento is null " .
	      " order by B.nome";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Alunos Matriculados em $nome_campus em $ref_periodo</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;Identidade</b></font></td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;Fone Particular</b></font></td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;Fone Profissio</b></font></td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;Fone Celular</b></font></td>");
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
  
     $bg = '#000099';
     $fg = '#FFFFFF'; 
     
     echo("<tr bgcolor=\"$bg\">\n");
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$i</b></td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$ref_pessoa</b></td>");
     echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$pessoa_nome</b></td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;<b>$rg_numero</b></td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;<b>$fone_particular</b></td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;<b>$fone_profissional</b></td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;<b>$fone_celular</b></td>");
     echo("  </tr>");

     $sql = " select A.ref_disciplina, " .
            "        descricao_disciplina(A.ref_disciplina),".
            "        get_dia_semana(dia_disciplina_ofer_todos(B.id)), " .
            "        get_turno(turno_disciplina_ofer_todos(B.id)) " .
            " from matricula A, disciplinas_ofer B, disciplinas C".
            " where A.ref_disciplina_ofer = B.id and ".
            "       A.ref_disciplina = C.id and " .
    	    "       A.dt_cancelamento is null and " .
            "       A.ref_pessoa = $ref_pessoa and ".
            "       A.ref_periodo = '$ref_periodo'".
            " order by 3 ";
      
      $query2 = $conn->CreateQuery($sql);

      while( $query2->MoveNext() )
      {
       list ( $ref_disciplina,
              $desc_disciplina,
              $dia_semana,
              $turno) = $query2->GetRowValues();

     	echo("<tr bgcolor=\"#FFFFFF\">\n");
     	echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\">&nbsp</td>");
    	echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\">$ref_disciplina</td>");
     	echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\">$desc_disciplina</td>");
     	echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\">&nbsp;</td>");
     	echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\">&nbsp;</td>");
     	echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\">&nbsp;$dia_semana</td>");
     	echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\">&nbsp;$turno</td>");
     	echo("  </tr>");

     }	      
     
     $query2->Close();
     
     $i++;

   }

   echo("<tr><td colspan=\"7\"><hr></td></tr>");

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaAlunos($periodo_id, $campus_id);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='alunos_curso_dia.phtml'">
</div>

</form>
</body>
</html>
