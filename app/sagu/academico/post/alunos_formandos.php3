<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<? require("../../lib/GetField.php3"); ?>
<html>
<head>
<title>Alunos Formandos</title>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post">

<?
if ($status == 'formandos')
{
    CheckFormParameters(array("ref_periodo","status"));
}
else
{
    CheckFormParameters(array("status"));
}

$conn = new Connection;
$conn->open();

if ($status == 'formandos')
{
    $sql = " select B.ref_curso, " .
           "        C.id, " .
           "        pessoa_nome(C.id), " .
           "        pessoa_fone(C.id), " .
           "        C.fone_particular, " .
           "        C.fone_profissional, " .
           "        C.fone_celular, " .
           "        C.rua, " .
	       "        C.complemento, " .
           "        get_cidade(C.ref_cidade), " .
           "        get_estado(C.ref_cidade), " .
           "        count(*) " .
           "  from matricula A, contratos B, pessoas C " .
           "  where B.id = A.ref_contrato and " .
	       "        B.ref_periodo_formatura = '$ref_periodo' and " .
           "        B.fl_formando = '1' and " .
           "        B.dt_desativacao is null and " .
           "        C.id = B.ref_pessoa ";
	 
           if (($ref_curso != '0') && ($ref_curso != ''))
           {
           	$sql .= " and B.ref_curso = '$ref_curso' ";
           }
         
    $sql.= " group by B.ref_curso, " .
           "           C.id, " .
           "           pessoa_nome(C.id), " .
           "           pessoa_fone(C.id), " .
           "           C.fone_particular, " .
           "           C.fone_profissional, " .
           "           C.fone_celular, " .
           "           C.rua, " .
           "           C.complemento, " .
           "           get_cidade(C.ref_cidade), " .
           "           get_estado(C.ref_cidade) " .
           "  order by B.ref_curso, " .
           "           pessoa_nome(C.id), " .
           "           pessoa_fone(C.id) "; 
}
else
{
    $sql = " select A.ref_curso, " .
           "       B.id, " .
           "       pessoa_nome(B.id), " .
           "       pessoa_fone(B.id), " .
           "       B.fone_particular, " .
           "       B.fone_profissional, " .
           "       B.fone_celular, " .
           "       B.rua, " .
	       "       B.complemento, " .
           "       get_cidade(B.ref_cidade), " .
           "       get_estado(B.ref_cidade), " .
           "       count(*) " .
           "  from contratos A, pessoas B " .
           "  where A.ref_pessoa = B.id and " .
	       "        A.fl_formando = '1' and " .
           "        A.dt_desativacao is not null and " .
    	   "        A.dt_conclusao < date(now()) ";
	       
           if (($ref_tipo_curso != '0') && ($ref_tipo_curso != ''))
           {
    	       $sql .= " and get_tipo_curso(A.ref_curso) = '$ref_tipo_curso' ";
           }

           if (($dt_inicial) && ($dt_final))
           {
               $datas = "($dt_inicial até $dt_final)";
               $dt_inicial = InvData($dt_inicial);
               $dt_final = InvData($dt_final);
               $sql .= " and A.dt_conclusao between '$dt_inicial' and '$dt_final' ";
           }
     
           if (($ref_periodo != '0') && ($ref_periodo != ''))
	       {
	 	       $sql .= " and A.ref_periodo_formatura = '$ref_periodo' ";
    	   }
	 
           if (($ref_curso != '0') && ($ref_curso != ''))
	       {
	 	       $sql .= " and A.ref_curso = '$ref_curso' ";
	       }
         
    $sql.= "  group by A.ref_curso, " .
           "           B.id, " .
           "           pessoa_nome(B.id), " .
           "           pessoa_fone(B.id), " .
           "           B.fone_particular, " .
           "           B.fone_profissional, " .
           "           B.fone_celular, " .
           "           B.rua, " .
           "           B.complemento, " .
           "           get_cidade(B.ref_cidade), " .
           "           get_estado(B.ref_cidade) " .
           "  order by A.ref_curso, " .
           "           pessoa_nome(B.id), " .
           "           pessoa_fone(B.id) "; 

}
 
  $query = $conn->CreateQuery($sql);
  
  $status = ucwords($status) . ' ' . $ref_periodo . ' ' . $datas; 

  echo("<center><br><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
  echo("<tr><td align=\"center\" bgcolor=\"#cccccc\" colspan=\"6\" ><font color=\"#000000\" size=\"4\"><b>Consulta de alunos $status</b></font></td></tr>\n");
  $arquivo  = "Consulta de alunos $status\n";
  $arquivo .= "Cont;Código da Pessoa;Nome da Pessoa;Fone Particular;Fone Profissional;Fone Celular;Rua;Cidade;UF\n";
  
  $i=1;

  // cores fundo
  $bg0 = "#000000";
  $bg1 = "#EEEEFF";
  $bg2 = "#FFFFEE";
 
  // cores fonte
  $fg0 = "#FFFFFF";
  $fg1 = "#000099";
  $fg2 = "#000099";

  //variável para controle soa cursos
  $control_curso = 0;
  
  while( $query->MoveNext() )
  {
    list ( $ref_curso,
           $ref_pessoa,
           $pessoa_nome,
           $pessoa_fone,
           $fone_particular,
           $fone_profissional,
           $fone_celular,
           $rua,
	       $complemento,
           $cidade,
           $estado,
           $total) = $query->GetRowValues();
  
    if ( $control_curso != $ref_curso )
    {  
      $sql3 = " select descricao, " .
              "        ref_tipo_curso " .
              " from cursos " .
              " where id = '$ref_curso'";
              
      $query3 = $conn->CreateQuery($sql3);
      $query3->MoveNext();
      
      list ($curso,
            $id_tipo_curso) = $query3->GetRowValues();

      $query3->Close();
      
      $sql4 = " select descricao " .
              " from tipos_curso " .
              " where id = '$id_tipo_curso'";
      
      $query4 = $conn->CreateQuery($sql4);
      $query4->MoveNext();
      $tipo_curso = $query4->GetValue(1);
      $query4->Close();
      
      echo("<tr><td align=\"center\" bgcolor=\"#777777\" colspan=\"6\" ><font color=\"#ffffff\"><b>[$ref_curso] - $curso ($tipo_curso)</b></font></td></tr>\n");

      $arquivo .= "$ref_curso - $curso ($tipo_curso)\n";
      
      $control_curso = $ref_curso;

      echo("  <tr bgcolor=\"#cccccc\">\n");
      echo("    <td width=\"6%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Linha</b></td>");
      echo("    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Código</b></td>");
      echo("    <td width=\"38%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Pessoa</b></td>");
      echo("    <td width=\"36%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Fone</b></td>");
      echo("    <td width=\"36%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Endereço</b></td>");
      echo("    <td width=\"36%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Cidade</b></td>");
      echo("  </tr>");

    }

    if ( $i % 2 )
    {
      echo("  <tr bgcolor=\"$bg1\">\n");
      echo("    <td width=\"6%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$i</td>");
      echo("    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$ref_pessoa</td>");
      echo("    <td width=\"38%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$pessoa_nome</td>");
      echo("    <td width=\"34%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$pessoa_fone</td>");
      echo("    <td width=\"34%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$rua - $complemento</td>");
      echo("    <td width=\"34%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$cidade - $estado</td>");
      echo("  </tr>");
    }
    else
    {
      echo("  <tr bgcolor=\"$bg2\">\n");
      echo("    <td width=\"6%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$i</td>");
      echo("    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$ref_pessoa</td>");
      echo("    <td width=\"38%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$pessoa_nome</td>");
      echo("    <td width=\"36%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$pessoa_fone</td>");
      echo("    <td width=\"36%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$rua - $complemento</td>");
      echo("    <td width=\"36%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$cidade - $estado</td>");
      echo("  </tr>\n");
    }
    
    $arquivo .= "$i;$ref_pessoa;$pessoa_nome;$fone_particular;$fone_profissional;$fone_celular;$rua - $complemento;$cidade;$estado\n";
    
    $i++;
  }
  echo("<tr><td colspan=6><hr></td></tr>");
  echo("</table></center>");
  
  $query->Close();
  $conn->Close();

  $filename = 'lista_alunos.txt';
  $fp = fopen($filename, "w");
  fwrite($fp, $arquivo);
  fclose($fp);
  
</script>
<br>
<center><a href="<? echo($filename); ?>"> Visualizar Arquivo Texto </a></center>
<br>
                  
<div align="center">
<input type="button" name="Button" value="  Voltar  " onClick="location='../alunos_formandos.phtml'">
</div>
</form>
</body>
</html>
