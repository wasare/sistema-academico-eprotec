<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Resumo do Livro Matrícula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF">
<?
    
   $conn = new Connection;
   $conn->open();

   $sql = " select trim(get_sexo(A.ref_pessoa)), " .
          "        A.ref_curso_atual, " .
          "        curso_desc(A.ref_curso_atual),  " .
          "        A.ref_campus_atual,  " .
          "        get_campus(A.ref_campus_atual), " .
          "        A.ref_status,  " .
          "        get_status(A.ref_status), " .
          "        B.fl_in_lm, " .
    	  "        count(*) " .
          " from livro_matricula A, status_matricula B, cursos C" .
          " where A.ref_status = B.id and " .
          "       A.ref_curso_atual = C.id and " .
	      "       A.ref_periodo= '$ref_periodo' and " .
          "       A.ref_curso_atual<>6 " . 
          " group by get_sexo(A.ref_pessoa), " .
          "          C.sequencia, " .
    	  "          A.ref_curso_atual, " .
          "          A.ref_campus_atual,  " .
          "          A.ref_status, " .
	      "          B.fl_in_lm " .
          " order by C.sequencia, " .
          "          A.ref_curso_atual, " .
          "          A.ref_campus_atual, " .
          "          A.ref_status, " .
    	  "          B.fl_in_lm; " ;

   $query = $conn->CreateQuery($sql); 
   
   while( $query->MoveNext() )
   {
     $result[] = $query->GetRowValues();
   }	    
   for ($j=0; $j<count($result); $j++)
   {
     list ( $sexo,
     	    $ref_curso, 
            $curso,
            $ref_campus, 
            $campus,
            $ref_status, 
            $status, 
            $fl_in_lm, 
    	    $num) = $result[$j];
     
     $num_sexo[$ref_curso][$ref_status][$sexo][$ref_campus] = $num;
     $marcados[$ref_curso][$ref_status]['M'][$ref_campus] = false;
     $marcados[$ref_curso][$ref_status]['F'][$ref_campus] = false;
     $marcados[$ref_curso][$ref_status][''][$ref_campus] = false;
   }
   echo("<center><table width=\"550\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $aux_curso = -1; 
   $aux_campus = -1; 
   $i = 1;
   $total = 0;
   $total_geral = 0;
   $total_masculino = 0;
   $total_feminino = 0;
   $total_NI = 0;
   $total_geral_masculino = 0;
   $total_geral_feminino = 0;
   $total_geral_NI = 0;

   for ($j=0; $j<count($result); $j++)
   {
     list ( $sexo,
	    $ref_curso, 
            $curso,
	    $ref_campus,
       	    $campus,
            $ref_status,
            $status, 
            $fl_in_lm, 
            $num) = $result[$j];

     if ($marcados[$ref_curso][$ref_status][$sexo][$ref_campus] == false)
     {
	  
     $total_linha = $num_sexo[$ref_curso][$ref_status]['M'][$ref_campus] + 
                    $num_sexo[$ref_curso][$ref_status]['F'][$ref_campus] +
                    $num_sexo[$ref_curso][$ref_status][''][$ref_campus];
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Resumo do Livro Matrícula</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"22\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
     }

     if(($aux_curso != $ref_curso) || ($aux_campus != $ref_campus))
     {
         if($i != 1)
         {
            echo("<tr bgcolor=\"CCCCCC\">\n");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
            echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>Total do Curso</b></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_masculino</b></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_feminino</b></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_NI</b></td>");
            echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$total</b></td>");
            echo("  </tr>");
	    $total_geral_masculino += $total_masculino;
	    $total_masculino = 0;
	    $total_geral_feminino += $total_feminino;
	    $total_feminino = 0;
	    $total_geral_NI += $total_NI;
	    $total_NI = 0;
            $total = 0;
         }
         echo ("<tr>");
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"6\" height=\"22\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
         echo ("</tr>"); 
         echo ("<tr>");
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"6\" height=\"22\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>Curso: " . $ref_curso . " - " . $curso . " - " . $campus . "</b></font></td>");
         echo ("</tr>"); 
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Status</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Mas</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fem</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>N.I.</b></font></td>");
         echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Número</b></font></td>");
         echo ("  </tr>"); 


	 
         $aux_curso = $ref_curso;
         $aux_campus = $ref_campus;
     }
     
     if ($num_sexo[$ref_curso][$ref_status]['M'][$ref_campus] == '')
     {
       $num_sexo[$ref_curso][$ref_status]['M'][$ref_campus] = 0;
     }
     
     if ($num_sexo[$ref_curso][$ref_status]['F'][$ref_campus] == '')
     {
       $num_sexo[$ref_curso][$ref_status]['F'][$ref_campus] = 0;
     }
     
     if ($num_sexo[$ref_curso][$ref_status][''][$ref_campus] == '')
     {
       $num_sexo[$ref_curso][$ref_status][''][$ref_campus] = 0;
     }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_status</td>");
          echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$status</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_sexo[$ref_curso][$ref_status]['M'][$ref_campus] . "</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_sexo[$ref_curso][$ref_status]['F'][$ref_campus] . "</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_sexo[$ref_curso][$ref_status][''][$ref_campus] . "</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$total_linha</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_status</td>");
          echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$status</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_sexo[$ref_curso][$ref_status]['M'][$ref_campus] . "</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_sexo[$ref_curso][$ref_status]['F'][$ref_campus] . "</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_sexo[$ref_curso][$ref_status][''][$ref_campus] . "</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$total_linha</td>");
          echo("  </tr>\n");
         }
	 
     $marcados[$ref_curso][$ref_status]['M'][$ref_campus] = true;
     $marcados[$ref_curso][$ref_status]['F'][$ref_campus] = true;
     $marcados[$ref_curso][$ref_status][''][$ref_campus] = true;
     
     $total_masculino += $num_sexo[$ref_curso][$ref_status]['M'][$ref_campus];
     $total_feminino += $num_sexo[$ref_curso][$ref_status]['F'][$ref_campus];
     $total_NI += $num_sexo[$ref_curso][$ref_status][''][$ref_campus];
    
     if($fl_in_lm == 'f')
     {
        $total += $total_linha;
        $total_geral = $total_geral + $total_linha;
     }
     $i++;

    }
   }

     echo("<tr bgcolor=\"#CCCCCC\">\n");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
     echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>Total do Curso</b></td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_masculino</b></td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_feminino</b></td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_NI</b></td>");
     echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$total</b></td>");
     echo("  </tr>");

     $total_geral_masculino += $total_masculino;
     $total_geral_feminino += $total_feminino;
     $total_geral_NI += $total_NI;

     echo ("<tr>");
     echo ("<td bgcolor=\"#FFFFFF\" colspan=\"6\" height=\"22\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
     echo ("</tr>"); 
     
     echo("<tr bgcolor=\"#BBBBBB\">\n");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\">&nbsp;</td>");
     echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>TOTAL GERAL</b></td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_masculino</b></td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_feminino</b></td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_NI</b></td>");
     echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral</b></td>");
     echo("  </tr>");

     echo("</table>");
?>
</body>
</html>
