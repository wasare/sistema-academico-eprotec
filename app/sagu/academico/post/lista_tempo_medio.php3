<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<? require("../../lib/GetField.php3"); ?>
<html>
<head>
<title>Lista Tempo Médio</title>
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
               "        A.ref_pessoa, " .
               "        pessoa_nome(A.ref_pessoa), " .
               "        count(distinct A.ref_periodo) " .
               " from matricula A, contratos B " .
               " where A.ref_contrato = B.id and " .
    	       "       B.ref_periodo_formatura = '$ref_periodo' and " .
               "       B.fl_formando = '1' and " .
               "       A.dt_cancelamento is null ";
               
	           if (($ref_curso != '0') && ($ref_curso != ''))
	           {
    	          $sql .= " and B.ref_curso = '$ref_curso' ";
	           }

        $sql.= " group by B.ref_curso, " .
               "           A.ref_pessoa, " .
               "           pessoa_nome(A.ref_pessoa) " .
               "  order by B.ref_curso, " .
               "           pessoa_nome(A.ref_pessoa) "; 
    }
    else
    {
        $sql = " select B.ref_curso, " .
               "        A.ref_pessoa, " .
               "        pessoa_nome(A.ref_pessoa), " .
               "        count(distinct A.ref_periodo) " .
               " from matricula A, contratos B " .
               " where A.ref_contrato = B.id and " .
	           "        B.fl_formando = '1' and " .
               "        B.dt_desativacao is not null and " .
               "        B.dt_conclusao < date(now()) and " .
	           "        A.dt_cancelamento is null ";
	         
               if (($ref_tipo_curso != '0') && ($ref_tipo_curso != ''))
               {
    	          $sql .= " and get_tipo_curso(B.ref_curso) = '$ref_tipo_curso' ";
               }

               if (($dt_inicial) && ($dt_final))
               {
                  $datas = "($dt_inicial até $dt_final)";
                  $dt_inicial = InvData($dt_inicial);
                  $dt_final = InvData($dt_final);
                  $sql .= " and B.dt_conclusao between '$dt_inicial' and '$dt_final' ";
               }

               if (($ref_periodo != '0') && ($ref_periodo != ''))
               {
                   $sql .= " and B.ref_periodo_formatura = '$ref_periodo' ";
               }
	     
               if (($ref_curso != '0') && ($ref_curso != ''))
	           {
	               $sql .= " and B.ref_curso = '$ref_curso' ";
	           }

        $sql.= " group by B.ref_curso, " .
               "           A.ref_pessoa, " .
               "           pessoa_nome(A.ref_pessoa) " .
               "  order by B.ref_curso, " .
               "           pessoa_nome(A.ref_pessoa) "; 
  
    }
    
    $query = $conn->CreateQuery($sql);

    $status = ucwords($status) . ' ' . $ref_periodo . ' ' . $datas; 
    
    echo("<center><br><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
    echo("<tr><td align=\"center\" bgcolor=\"#cccccc\" colspan=\"6\" ><font color=\"#000000\" size=\"4\"><b>Tempo Médio de Conclusao de Curso - $status</b></font></td></tr>\n");
  
    // cores fundo
    $bg0 = "#000000";
    $bg1 = "#EEEEFF";
    $bg2 = "#FFFFEE";
 
    // cores fonte
    $fg0 = "#FFFFFF";
    $fg1 = "#000099";
    $fg2 = "#000099";

    //variável para controle dos cursos
    $control_curso = 0;
  
    $i = 1;
    $cont = 0;
    $total_curso = 0;
    $total = 0;
  
    while( $query->MoveNext() )
    {
    list ( $ref_curso,
           $ref_pessoa,
           $pessoa_nome,
           $num_semestres) = $query->GetRowValues();
             
  
    if ( $control_curso != $ref_curso )
    {  
      if ($i != 1)
      {
	  $media_curso = ($total_curso / $cont);
	  $media_curso = sprintf("%.2f", $media_curso);
  
	  echo("<tr bgcolor=\"#cccccc\">\n");
	  echo("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap>&nbsp;</td>");
	  echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap>&nbsp;</td>");
	  echo("<td width=\"70%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Média de Semestres do Curso:</b></td>");
	  echo("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>$media_curso</b></td>");
	  echo("  </tr>\n");
          
	  $total_curso = 0;
	  $cont = 0;

      }
      
      $sql3 = "select descricao from cursos where id = '$ref_curso'";
      $query3 = $conn->CreateQuery($sql3);
      $query3->Movenext();
      $curso = $query3->GetValue(1);

      echo("<tr><td align=\"center\" bgcolor=\"#777777\" colspan=\"6\" ><font color=\"#ffffff\"><b>[$ref_curso] - {$curso}</b></font></td></tr>\n");
      $control_curso = $ref_curso;

      echo("  <tr bgcolor=\"#cccccc\">\n");
      echo("    <td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Cont</b></td>");
      echo("    <td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Código</b></td>");
      echo("    <td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Nome do Aluno</b></td>");
      echo("    <td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Nº de Semestres</b></td>");
      echo("  </tr>");

    }

    if ( $i % 2 )
    {
      echo("  <tr bgcolor=\"$bg1\">\n");
      echo("    <td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$i</td>");
      echo("    <td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$ref_pessoa</td>");
      echo("    <td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$pessoa_nome</td>");
      echo("    <td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\" nowrap>$num_semestres</td>");
      echo("  </tr>");
    }
    else
    {
      echo("  <tr bgcolor=\"$bg2\">\n");
      echo("    <td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$i</td>");
      echo("    <td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$ref_pessoa</td>");
      echo("    <td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$pessoa_nome</td>");
      echo("    <td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\" nowrap>$num_semestres</td>");
      echo("  </tr>\n");
    }
    
    $i++;
    $cont++;
    $total = $total + $num_semestres;
    $total_curso = $total_curso + $num_semestres;
    
    }

    $media_curso = ($total_curso / $cont);
    $media_curso = sprintf("%.2f", $media_curso);

    echo("<tr bgcolor=\"#cccccc\">\n");
    echo("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap>&nbsp;</td>");
    echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap>&nbsp;</td>");
    echo("<td width=\"70%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>Média de Semestres do Curso:</b></td>");
    echo("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\" nowrap><b>$media_curso</b></td>");
    echo("  </tr>\n");

    $i--;
    $media = ($total / $i);
    $media = sprintf("%.2f", $media);
  
    echo("<tr bgcolor=\"#000000\">\n");
    echo("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\" nowrap>&nbsp;</td>");
    echo("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\" nowrap>&nbsp;</td>");
    echo("<td width=\"70%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\" nowrap><b>Média Geral de Semestres</b></td>");
    echo("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\" nowrap><b>$media</b></td>");
    echo("  </tr>\n");


    echo("<tr><td colspan=4><hr></td></tr>");
    echo("</table></center>");

    $query->Close();
    $conn->Close();

?>
    <div align="center">
    <input type="button" name="Button" value="  Voltar  " onClick="location='../alunos_formandos.phtml'">
    </div>
  </form>
  </body>
</html>
