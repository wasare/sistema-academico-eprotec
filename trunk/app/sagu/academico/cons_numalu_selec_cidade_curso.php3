<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Lista de Alunos por Cidade</title>
<script language="PHP">
function ListaAlunos($id_cidade, $ref_periodo, $bixo, $curso_id, $campus_id)
{
    $conn = new Connection;

    $conn->open();

    $total=0;

    $sql = " select distinct " .
           "        A.ref_pessoa, " .
           "        C.nome, " .
           "        C.rg_numero, " .
           "        C.cod_cpf_cgc, " .
           "        pessoa_fone(C.id), " .
           "        get_cidade(C.ref_cidade), " .
           "        B.ref_curso, " .
           "        curso_desc(B.ref_curso), " .
           "        B.ref_campus, " .
           "        get_campus(B.ref_campus) " .
           " from matricula A, contratos B, pessoas C " .
           " where A.ref_periodo = B.ref_last_periodo and " .
           "       A.ref_pessoa = C.id and " .
           "       A.ref_pessoa = B.ref_pessoa and " .
           "       C.id = B.ref_pessoa and " .
           "       A.ref_contrato = B.id and " .
           "       A.ref_curso = B.ref_curso and " .
           "       A.dt_cancelamento is null and " .
           "       B.dt_desativacao is null and " .
           "       A.ref_periodo='$ref_periodo' and " .
           "       C.ref_cidade = '$id_cidade'";
   if ($curso_id)
   {
      $sql .= " and A.ref_curso = '$curso_id' ";
   }
  
   if ($campus_id)
   {
      $sql .= " and A.ref_campus = '$campus_id' ";
   }
     
   if ($bixo == 'sim')
   {
     $sql .= " and is_calouro(C.id, '$ref_periodo') = 't' ";
   }

   $sql .= " order by B.ref_campus, B.ref_curso, C.nome; ";
    
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $aux_curso  = 0;
   $aux_campus = 0;

   while( $query->MoveNext() )
   {
     list ($id,
           $nome,
           $rg,
           $cpf,
           $fone,
           $cidade,
           $ref_curso,
           $curso,
           $ref_campus,
           $campus) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b><center>UNIVATES - Centro Universitário</center></b></font></td></tr>");
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Alunos Matriculados</b></font>");
	     if ($bixo == 'sim')
         {
            echo("<font color=\"red\"><b> - CALOUROS</b></font>");
         }
         echo("</td></tr>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Cidade : " . $cidade . " </b></font></td>");
         echo ("<td bgcolor=\"#000099\" colspan=\"1\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . " </b></font></td>");
         if ($campus_id)
         {
            echo ("<td bgcolor=\"#000099\" colspan=\"2\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Campus: " . $campus_id . " </b></font></td>");
         }
         else
         {
            echo ("<td bgcolor=\"#000099\" colspan=\"2\" height=\"28\">&nbsp;</td>");
         }
         echo ("</tr>"); 
     }
     
     if (($aux_curso != $ref_curso) || ($aux_campus != $ref_campus))
     {
         echo ("<tr><td>&nbsp;</td></tr>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td colspan=\"6\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">Curso: $ref_curso - $curso Campus: $ref_campus - $campus</font></td>");
         echo ("  </tr>"); 
         echo ("<tr bgcolor=\"#000099\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fones</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("  </tr>"); 
         
         $aux_curso = $ref_curso;
         $aux_campus = $ref_campus;
     }

     if ( $i % 2 )
     {
        echo("<tr bgcolor=\"$bg1\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$i</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$id</td>");
        echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$nome</td>");
        echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$fone</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$ref_curso</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$ref_campus</td>");
        echo("  </tr>");
      }
      else
      {
        echo("<tr bgcolor=\"$bg2\">\n");
        echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$i</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$id</td>");
        echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$nome</td>");
        echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$fone</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$ref_curso</td>");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$ref_campus</td>");
        echo("  </tr>\n");
      }

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
  ListaAlunos($id_cidade, $id_periodo, $bixo, $id_curso, $id_campus);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
