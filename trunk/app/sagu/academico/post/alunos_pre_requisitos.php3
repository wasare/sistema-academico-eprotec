<? require("../../../../lib/common.php"); ?>
<? require("../../lib/GetPreRequisito.php3"); ?>
<? require("../../lib/ProcessaMaterial.php3"); ?>

<html>
<head>
<title>Alunos Matriculados em Disciplinas Pré-Requisitos</title>

<? CheckFormParameters(array("ref_periodo",
                             "ref_curso",
                             "ref_campus"));
?>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="">
<?

$conn = new Connection;
$conn->Open();

$sql = " select get_curso_abrv('$ref_curso');";
    
$query = $conn->CreateQuery($sql);
    
if ( $query->MoveNext() )
{
  list ($curso_desc) = $query->GetRowValues();
}

$query->Close();

//Disciplinas matriculadas no Periodo
$sql = " select ref_pessoa, ".
       "        pessoa_nome(ref_pessoa), " .
       "        pessoa_fone(ref_pessoa), " .
       "        ref_disciplina, ".
       "        descricao_disciplina(ref_disciplina) ".
       " from matricula ".
       " where ref_curso = '$ref_curso' and ".
       "       ref_campus = '$ref_campus' and " .
       "       ref_periodo = '$ref_periodo' and " .
       "       dt_cancelamento is null " .
       " order by pessoa_nome(ref_pessoa);";

$query = $conn->CreateQuery($sql);

echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

// cores fundo
$bg0 = "#000000";
$bg1 = "#EEEEFF";
$bg2 = "#FFFFEE";

// cores fonte
$fg0 = "#FFFFFF";
$fg1 = "#000099";
$fg2 = "#000099";

echo ("<tr>");
echo ("<td>&nbsp;</td>");
echo ("<tr>");
echo ("<tr>");
echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de alunos com dependências de Pré-Requisitos.</b></font></td>");
echo ("<tr>");
echo ("<tr>");
echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $ref_curso . " - " . $curso_desc . " / " . $ref_periodo ."</b></font></td>");
echo ("</tr>");
echo ("<tr bgcolor=\"#000000\">\n");
echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
echo ("<td width=\"32%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone</b></font></td>");
echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina<br>Matriculada</b></font></td>");
echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dependencia<br>Pré-Requisito</b></font></td>");
echo ("  </tr>");

$cont = 1;

$aux_pessoa = '-1';

while( $query->MoveNext() )
{
    list ($ref_pessoa,
          $nome_pessoa,
          $fone_pessoa,
          $ref_disciplina,
	      $nome_disciplina) = $query->GetRowValues();
 
    if ($aux_pessoa != $ref_pessoa)
    {
        ProcessaMaterial($ref_periodo, $ref_curso, $ref_campus, $ref_pessoa);    
        $aux_pessoa = $ref_pessoa;
    }

    flush();

    $conn1 = new Connection;
    $conn1->Open();

    $sql_disc =  " select get_status_disciplina('$ref_pessoa', '$ref_curso', '$ref_disciplina'), " .
                 "        status_disciplina " .
                 " from matricula " .
	             " where ref_pessoa = '$ref_pessoa' and " .
	             "       ref_periodo = '$ref_periodo' and " .
	             "       ref_disciplina = '$ref_disciplina' and " .
                 "       dt_cancelamento is null"; 

    $query_disc = $conn1->CreateQuery($sql_disc);

    SaguAssert($query_disc,"Nao foi possível executar a consulta SQL!");
 
    while( $query_disc->MoveNext() )
    {
        list($status_material,
             $status_matricula) = $query_disc->GetRowValues();
    }

    $query_disc->Close();
  
    if (($status_material == '2') && ($status_matricula == 'f'))
    {
        $pre_requisito = GetPreRequisito($ref_disciplina, $ref_curso);
       
        $ref_disciplinas_pre = split(",", $pre_requisito);
       
        $num_pre = count($ref_disciplinas_pre) - 1;           //Nº de pré-requisitos
  
        for ($i=0; $i<$num_pre; $i++)
        {
            $conn2 = new Connection;
            $conn2->Open();

            $sql2 = " select descricao_disciplina($ref_disciplinas_pre[$i]);";
    
            $query2 = $conn2->CreateQuery($sql2);
    
            if ( $query2->MoveNext() )
            {
                list ($nome_disciplina_pre) = $query2->GetRowValues();
            }
    
            $query2->Close();

            $processo = '';
            
            $sql3 =  " select trim(processo) " .
                     " from matricula " .
	                 " where ref_pessoa = '$ref_pessoa' and " .
	                 "       ref_disciplina = '$ref_disciplinas_pre[$i]' and " .
                     "       dt_cancelamento is null"; 

            $query3 = $conn2->CreateQuery($sql3);
    
            if ( $query3->MoveNext() )
            {
                list ($processo) = $query3->GetRowValues();
            }
    
            $query3->Close();

            if ($processo != '')
            {
                $marca = "<font color=red><b>*</b></font>";
            }
            else
            {
                $marca = "&nbsp;";
            }
            
            $href1  = "<img src=\"../images/info.gif\" title='$nome_disciplina' align='absmiddle' border=0>";
            $href2  = "<img src=\"../images/info.gif\" title='$nome_disciplina_pre' align='absmiddle' border=0>";
      
            if ( $cont % 2 )
            {
                echo("<tr bgcolor=\"$bg1\">\n");
                echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$cont</td>");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
                echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
                echo ("<td width=\"32%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$fone_pessoa</td>");
                echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href1 $ref_disciplina</td>");
                echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href2 $ref_disciplinas_pre[$i] $marca</td>");
                echo("  </tr>");
            }
            else
            {
                echo("<tr bgcolor=\"$bg2\">\n");
                echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$cont</td>");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
                echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_pessoa</td>");
                echo ("<td width=\"32%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$fone_pessoa</td>");
                echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href1 $ref_disciplina</td>");
                echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href2 $ref_disciplinas_pre[$i] $marca</td>");
                echo("  </tr>\n");
            }
            $cont ++;
        } // End For
    } // End if
}  // End While

echo("<tr><td colspan=6><hr></td></tr>");

echo("<tr><td colspan=6><font color=red><b>(*)</b></font><font face=Arial, Helvetica, sans-serif size=2 color=#000099> Disciplinas com Processo tramitando.</font></td></tr>");

echo("</table></center>");

$query->Close();
   
@$conn1->Close();
@$conn->Close();

?>
 <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onClick="location='../alunos_pre_requisitos.phtml'">
  </div>
</form>
</body>
</html>
