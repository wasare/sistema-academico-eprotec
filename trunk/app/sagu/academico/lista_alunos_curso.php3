<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<? require("../lib/InvData.php3"); ?>

<html>
<head>
<title>Alunos por Curso</title>
<script language="PHP">
function ListaAlunos($ref_curso, $ref_campus, $ref_periodo, $dt_livro_matricula, $anterior)
{
    $conn = new Connection;

    $conn->Open();

    $total=0;

    $sql = " select A.id, " .
           "        A.ref_pessoa, " .
           "        B.nome, " .
       	   "        B.dt_nascimento, " .
           "        pessoa_fone(ref_pessoa), " .
           "        get_curso_abrv(A.ref_curso), " .
           "        get_campus(A.ref_campus), " .
           "        B.ref_cidade, " .
           "        get_cidade(B.ref_cidade), " .
           "        B.rua, " .
           "        B.complemento, " .
           "        B.bairro, " .
           "        B.fone_particular, " .
           "        B.fone_profissional, " .
           "        B.fone_celular, " .
           "        B.fone_recado, " .
           "        B.email " .
           " from contratos A, pessoas B " .
           " where A.ref_pessoa = B.id and " .
           "       A.ref_curso='$ref_curso' and " .
           "       A.ref_campus='$ref_campus' and ";

          
           if($anterior)
           {
	            $sql .= " A.id in (select distinct ref_contrato " .
                        "          from matricula " .
                        "          where ref_periodo = '$ref_periodo' and " .
                        "                trim(obs_aproveitamento) = '' and " .
                        "                (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
	                    " A.ref_pessoa in (select distinct ref_pessoa " .
                        "                  from matricula " .
                        "                  where ref_periodo = '$ref_periodo' and " .
                        "                        trim(obs_aproveitamento) = '' and " .
                        "                        (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
		                " (A.dt_desativacao is null or A.dt_desativacao > '$dt_livro_matricula') and ";
            }
            else
            {
                $sql .= " A.ref_last_periodo='$ref_periodo' and " .
                        " A.id = is_matriculado_cntr('$ref_periodo', A.id) and " .
               	        " A.dt_desativacao is null and ";
	        }
              
    $sql.= "       A.ref_curso<>6 and " .
           "       A.fl_ouvinte<>'1' " . 
           " order by B.nome;";
   
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

    while( $query->MoveNext() )
    {
        list ($id, 
              $ref_pessoa,
              $nome_pessoa, 
        	  $dt_nascimento,
              $fone, 
              $curso_desc,
              $campus_desc,
              $ref_cidade,
              $cidade,
              $rua,
              $complemento,
              $bairro,
              $fone_particular,
              $fone_profissional,
              $fone_celular,
              $fone_recado,
              $email) = $query->GetRowValues();
    
        $fone_particular    = trim($fone_particular);
        $fone_profissional  = trim($fone_profissional);
        $fone_celular       = trim($fone_celular);
        $complemento        = trim($complemento);
        
        if ($i == 1)
        {
            $listagem_DAs = "Curso de $curso_desc - Campus $campus_desc;;;;;;;;\n";
            $listagem_DAs .= "Nome;Cidade;Rua;Bairro;Complemento;Fone Particular;Fone Profissional;Fone Celular;E-mail\n";
         
            echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Matriculados por Curso</b></font></td>");
            echo ("<tr>");
            echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $curso_desc . "</b></font></td>");
            echo ("</tr>"); 
            if ($anterior)
            { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
            else
            { $periodo_antigo = " Não"; }
            echo("<tr>");
            echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo:<b>$periodo_antigo</b></font></td>");
            echo ("</tr>"); 
            echo ("<tr bgcolor=\"#000000\">\n");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome / E-mail</b></font></td>");
            echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone / Cidade Origem</b></font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nascimento&nbsp;</b></font></td>");
            echo ("  </tr>"); 
        }

        if ( $i % 2 )
        {
            echo("<tr bgcolor=\"$bg1\">\n");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
            echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$fone</td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">" . InvData($dt_nascimento) . "&nbsp;</td>");
            echo("  </tr>");
            echo("<tr bgcolor=\"$bg1\">\n");
            echo ("<td width=\"15%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$email</td>");
            echo ("<td width=\"50%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_cidade - $cidade</td>");
            echo("  </tr>");
        }
        else
        {
            echo("<tr bgcolor=\"$bg2\">\n");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_pessoa</td>");
            echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$fone</td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">" . InvData($dt_nascimento) . "&nbsp;</td>");
            echo("  </tr>\n");
            echo("<tr bgcolor=\"$bg2\">\n");
            echo ("<td width=\"15%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;</td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$email</td>");
            echo ("<td width=\"50%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_cidade - $cidade</td>");
            echo("  </tr>");
        }
     
        $i++;
     
        $listagem_DAs .= "$nome_pessoa;$cidade;$rua;$bairro;$complemento;$fone_particular;$fone_profissional;$fone_celular;$email\n";

    }

    echo("<tr><td colspan=\"5\"><hr></td></tr>");
    echo("</table></center>");

    $filename = 'listagem_DA_' . $ref_curso . $ref_campus . '.csv';
    $fp = fopen($filename, "w");
    fwrite($fp, $listagem_DAs);
    fclose($fp);

    echo("<br><center><a href=\"$filename\">Lista DAs</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/relat/lista_alunos_curso_ps.php3?ref_curso=$ref_curso&ref_campus=$ref_campus&ref_periodo=$ref_periodo&dt_livro_matricula=$dt_livro_matricula&anterior=$anterior\">Lista Assinaturas</a></center><br>");

    $query->Close();

    $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
    ListaAlunos($ref_curso, $ref_campus, $ref_periodo, $dt_livro_matricula, $anterior);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="history.go(-1)">
</div>
</form>
</body>
</html>
