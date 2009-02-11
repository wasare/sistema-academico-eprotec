<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Número de Alunos por Curso e Campus</title>
<script language="PHP">

CheckFormParameters(array("ref_periodo",
                          "ref_campus",
                          "ref_curso"));

function ListaAlunos($ref_curso, $ref_campus, $ref_periodo)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select id, " .
          "        ref_pessoa, " .
    	  "        pessoa_dtnasc(ref_pessoa), " .
          "        pessoa_nome(ref_pessoa), " .
          "        pessoa_fone(ref_pessoa), " .
          "        curso_desc(ref_curso), " .
          "        pessoa_cidade(ref_pessoa), " .
          "        get_cidade(pessoa_cidade(ref_pessoa)), " .
          "        get_email(ref_pessoa) " .
          " from contratos " .
          " where ref_curso='$ref_curso' and " .
          "       ref_campus='$ref_campus' " .
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

   $ativo = 0;
   $passivo = 0;

   while( $query->MoveNext() )
   {
     list ( $ref_contrato, 
            $ref_pessoa,
	        $dt_nascimento,
            $nome, 
            $fone, 
            $curso_desc,
            $ref_cidade,
            $cidade,
            $email) = $query->GetRowValues();

     $sql = " select distinct A.ref_pessoa " .
            " from matricula A, contratos B" .
            " where A.ref_contrato = B.id and " .
            "       A.ref_periodo = B.ref_last_periodo and " .
            "       A.ref_pessoa = B.ref_pessoa and " .
            "       A.ref_curso = B.ref_curso and " .
            "       B.id='$ref_contrato' and " .
            "       B.ref_pessoa='$ref_pessoa' and " .
            "       B.ref_curso='$ref_curso' and " .
            "       B.ref_campus='$ref_campus' and " .
            "       B.ref_last_periodo='$ref_periodo' and " .
            "       A.dt_cancelamento is null and " .
            "       B.dt_desativacao is null and " .
            "       B.ref_curso<>6 and " .
            "       B.fl_ouvinte<>'1' "; 
     
     $query2 = $conn->CreateQuery($sql);
     
     if ( $query2->MoveNext() )
     {
        $info  = "<b>Ativo</b>";
    	$ativo++;
     }
     else
     {
        $info  = "<b><font color=red>Passivo</font></b>";
	    $passivo++;
     }
   
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Alunos por Curso e Campus (Ativo + Passivo)</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $curso_desc . " - Campus: " . $ref_campus . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Status</b></font></td>");
         echo ("<td width=\"40%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód - Nome / E-mail</b></font></td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone / Cidade Origem</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nascimento&nbsp;</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$info</td>");
          echo ("<td width=\"40%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa - $nome</td>");
          echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$fone</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">" . InvData($dt_nascimento) . "&nbsp;</td>");
          echo("  </tr>");
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"15%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
          echo ("<td width=\"40%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$email</td>");
          echo ("<td width=\"45%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_cidade - $cidade</td>");
          echo("  </tr>");

         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$info</td>");
          echo ("<td width=\"40%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa - $nome</td>");
          echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$fone</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">" . InvData($dt_nascimento) . "&nbsp;</td>");
          echo("  </tr>\n");
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"15%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;</td>");
          echo ("<td width=\"40%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$email</td>");
          echo ("<td width=\"45%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_cidade - $cidade</td>");
          echo("  </tr>");
         }

     $i++;

   }
          
   echo ("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\" align=\"right\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Alunos Ativos = " . $ativo . "&nbsp;&nbsp;&nbsp; Alunos Passivos = " . $passivo . "</b></font></td>");
   echo ("</tr>"); 

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
   ListaAlunos($ref_curso, $ref_campus, $ref_periodo);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
