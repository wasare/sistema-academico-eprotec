<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Calouros Matriculados</title>
<script language="PHP">
function ListaAlunos($ref_curso, $ref_periodo, $ref_campus, $ref_vestibular, $opcao, $anterior)
{
   $conn = new Connection;

   $conn->open();

   $sql =  " select distinct " .
           "        A.ref_pessoa, " .
           "        pessoa_nome(A.ref_pessoa), " .
       	   "        pessoa_dtnasc(A.ref_pessoa), " .
	       "        pessoa_fone(A.ref_pessoa), " .
    	   "        curso_desc(B.ref_curso), " .
 	       "        get_campus(B.ref_campus) " .
           " from vest_inscricoes A, contratos B " .
           " where A.ref_opcao" . $opcao . " = B.ref_curso and " .
           "       A.ref_campus" . $opcao . " = B.ref_campus and " .
           "       A.ref_pessoa = B.ref_pessoa and " .
           "       A.ref_opcao" . $opcao . " = '$ref_curso' and " .
           "       A.ref_campus" . $opcao . " = '$ref_campus' and " .
           "       B.ref_curso = '$ref_curso' and " .
           "       B.ref_last_periodo = '$ref_periodo' and " .
           "       A.ref_vestibular = '$ref_vestibular' and " .
           "       B.dt_desativacao is null and " .
    	   "       A.ref_pessoa in (select distinct ref_pessoa " .
           "                        from matricula " .
           "                        where ref_periodo = '$ref_periodo' and " .
           "                              ref_curso = '$ref_curso' and " .
           "                              ref_pessoa = B.ref_pessoa and " .
           "                              dt_cancelamento is null) ";
           
           if ($anterior)
           {
              $sql .= " and B.ref_motivo_ativacao = '1' ";    // Vestibulandos
           }
           else
           {
              $sql .= " and B.cod_status = '1' ";    // Vestibulandos
           }
	  
	       if ($opcao == '2')
    	   {

              $sql .=  " and A.ref_opcao1 || A.ref_campus1 <> '$ref_curso" . "$ref_campus' ";
    	   }
           
           if ($opcao == '3')
           { 
              $sql .=  " and A.ref_opcao1 || A.ref_campus1 <> '$ref_curso" . "$ref_campus' " .
                       " and A.ref_opcao2 || A.ref_campus2 <> '$ref_curso" . "$ref_campus' ";
	       }
	  
   $sql .= " order by pessoa_nome(A.ref_pessoa);";
  
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
     list ($ref_pessoa,
           $nome, 
    	   $dt_nascimento,
           $fone,
	       $curso_desc,
    	   $campus) = $query->GetRowValues();
   
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Matriculados por Curso - $opcao ª Opção</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $curso_desc . " - " . $campus . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nascimento&nbsp;</b></font></td>");
         echo ("  </tr>"); 
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
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$i</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_pessoa</td>");
     echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome</td>");
     echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$fone</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">" . InvData($dt_nascimento) . "&nbsp;</td>");
     echo("  </tr>");

     $i++;

   }

   echo("<tr><td colspan=\"5\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
  ListaAlunos($ref_curso, $ref_periodo, $ref_campus, $ref_vestibular, 1, $anterior); // Opção 1
  ListaAlunos($ref_curso, $ref_periodo, $ref_campus, $ref_vestibular, 2, $anterior); // Opção 2
  ListaAlunos($ref_curso, $ref_periodo, $ref_campus, $ref_vestibular, 3, $anterior); // Opção 3
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
