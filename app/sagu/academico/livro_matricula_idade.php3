<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Livro Matrícula por Idade</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF">
<?
    
    $conn = new Connection;
    $conn->open();

    $sql = " select trim(categoria_lvr_mat(pessoa_idade(A.ref_pessoa))), " .
           "        A.ref_curso_atual, " .
           "        curso_desc(A.ref_curso_atual),  " .
           "        A.ref_campus_atual,  " .
           "        get_campus(A.ref_campus_atual), " .
           "        A.ref_status,  " .
           "        get_status(A.ref_status), " .
           "        B.fl_in_lm, " .
    	   "        count(*) " .
           " from livro_matricula A, status_matricula B" .
           " where A.ref_status = B.id and " .
	       "       A.ref_periodo = '$ref_periodo' and ";
          
    if (!empty($sexo))
    {
	    $sql = $sql . " get_sexo(A.ref_pessoa) = '$sexo' and ";
    }
	 
    $sql = $sql . " A.ref_curso_atual<>6 " . 
                  " group by categoria_lvr_mat(pessoa_idade(A.ref_pessoa)), " .
         	      "          A.ref_curso_atual, " .
                  "          A.ref_campus_atual,  " .
                  "          A.ref_status, " .
            	  "          B.fl_in_lm " .
                  " order by A.ref_curso_atual, " .
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
        list ($categoria,
     	      $ref_curso, 
              $curso,
              $ref_campus, 
              $campus,
              $ref_status, 
              $status, 
              $fl_in_lm,
        	  $num) = $result[$j];
     
        $num_categoria[$ref_curso][$ref_status][$categoria][$ref_campus] = $num;
     
        $marcados[$ref_curso][$ref_status]['A'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['B'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['C'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['D'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['E'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['F'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['G'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['H'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['I'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['J'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status]['L'][$ref_campus] = false;
        $marcados[$ref_curso][$ref_status][''][$ref_campus] = false;

    }
    echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

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
    $total_A = 0;
    $total_B = 0;
    $total_C = 0;
    $total_D = 0;
    $total_E = 0;
    $total_F = 0;
    $total_G = 0;
    $total_H = 0;
    $total_I = 0;
    $total_J = 0;
    $total_L = 0;
    $total_NI = 0;
    
    $total_geral_A = 0;
    $total_geral_B = 0;
    $total_geral_C = 0;
    $total_geral_D = 0;
    $total_geral_E = 0;
    $total_geral_F = 0;
    $total_geral_G = 0;
    $total_geral_H = 0;
    $total_geral_I = 0;
    $total_geral_J = 0;
    $total_geral_L = 0;
    $total_geral_NI = 0;
     
    for ($j=0; $j<count($result); $j++)
    {
        list ($categoria,
	          $ref_curso , 
              $curso,
    	      $ref_campus,
           	  $campus,
              $ref_status,
              $status, 
              $fl_in_lm,
              $num) = $result[$j];

        if ($marcados[$ref_curso][$ref_status][$categoria][$ref_campus] == false)
        {
            $total_linha = $num_categoria[$ref_curso][$ref_status]['A'][$ref_campus] + 
                           $num_categoria[$ref_curso][$ref_status]['B'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['C'][$ref_campus] + 
                   		   $num_categoria[$ref_curso][$ref_status]['D'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['E'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['F'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['G'][$ref_campus] + 
                   		   $num_categoria[$ref_curso][$ref_status]['H'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['I'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['J'][$ref_campus] + 
                		   $num_categoria[$ref_curso][$ref_status]['L'][$ref_campus] +
                		   $num_categoria[$ref_curso][$ref_status][''][$ref_campus];
     
            if ($i == 1)
            {
                echo ("<td bgcolor=\"#000099\" colspan=\"15\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Livro Matrícula por Idade - $sexo</b></font></td>");
                echo ("<tr>");
                echo ("<td bgcolor=\"#000099\" colspan=\"15\" height=\"22\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
            }

            if(($aux_curso != $ref_curso) || ($aux_campus != $ref_campus))
            {
                if($i != 1)
                {
                    echo("<tr bgcolor=\"CCCCCC\">\n");
                    echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
                    echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>Total do Curso</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_A</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_B</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_C</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_D</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_E</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_F</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_G</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_H</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_I</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_J</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_L</b></td>");
                    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_NI</b></td>");
                    echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$total</b></td>");
                    echo("  </tr>");
            
            	    $total = 0;
            
            	    $total_geral_A += $total_A;
            	    $total_A = 0;
        
              	    $total_geral_B += $total_B;
                    $total_B = 0;
        
            	    $total_geral_C += $total_C;
                    $total_C = 0;
        
            	    $total_geral_D += $total_D;
                    $total_D = 0;
        
            	    $total_geral_E += $total_E;
                    $total_E = 0;
        
            	    $total_geral_F += $total_F;
                    $total_F = 0;
        
            	    $total_geral_G += $total_G;
                    $total_G = 0;
        
            	    $total_geral_H += $total_H;
                    $total_H = 0;
        
            	    $total_geral_I += $total_I;
                    $total_I = 0;
        
            	    $total_geral_J += $total_J;
                    $total_J = 0;
        
            	    $total_geral_L += $total_L;
                    $total_L = 0;
        
            	    $total_geral_NI += $total_NI;
                    $total_NI = 0;

                }
                echo ("<tr>");
                echo ("<td bgcolor=\"#FFFFFF\" colspan=\"15\" height=\"22\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
                echo ("</tr>"); 
	 
                echo ("<tr>");
                echo ("<td bgcolor=\"#FFFFFF\" colspan=\"15\" height=\"22\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>Curso: " . $ref_curso . " - " . $curso . " - " . $campus . "</b></font></td>");
                echo ("</tr>"); 

                echo ("</tr>"); 
                echo ("<tr bgcolor=\"#000000\">\n");
                echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
                echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Status</b></font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\"> <= 18</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">19..24</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">25..29</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">30..34</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">35..39</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">40..44</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">45..49</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">50..54</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">55..59</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\">60..64</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\"> >= 65</font></td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\"> N. I.</font></td>");
                echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Número</b></font></td>");
                echo ("  </tr>"); 


	 
                $aux_curso = $ref_curso;
                $aux_campus = $ref_campus;
            }
     
            if ($num_categoria[$ref_curso][$ref_status]['A'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['A'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['B'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['B'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['C'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['C'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['D'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['D'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['E'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['E'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['F'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['F'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['G'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['G'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['H'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['H'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['I'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['I'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['J'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['J'][$ref_campus] = 0; }
    
            if ($num_categoria[$ref_curso][$ref_status]['L'][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status]['L'][$ref_campus] = 0; }
     
            if ($num_categoria[$ref_curso][$ref_status][''][$ref_campus] == '')
            { $num_categoria[$ref_curso][$ref_status][''][$ref_campus] = 0; }
    
    
            if ( $i % 2 )
            {
                echo("<tr bgcolor=\"$bg1\">\n");
                echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_status</td>");
                echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$status</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['A'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['B'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['C'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['D'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['E'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['F'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['G'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['H'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['I'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['J'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['L'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status][''][$ref_campus] . "</td>");
    
                echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$total_linha</td>");
                echo("  </tr>");
            }
            else
            {
                echo("<tr bgcolor=\"$bg2\">\n");
                echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_status</td>");
                echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$status</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['A'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['B'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['C'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['D'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['E'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['F'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['G'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['H'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['I'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['J'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status]['L'][$ref_campus] . "</td>");
                echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;" . $num_categoria[$ref_curso][$ref_status][''][$ref_campus] . "</td>");
              
	            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$total_linha</td>");
                echo("  </tr>\n");
            }
	 
            $marcados[$ref_curso][$ref_status]['A'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['B'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['C'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['D'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['E'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['F'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['G'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['H'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['I'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['J'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status]['L'][$ref_campus] = true;
            $marcados[$ref_curso][$ref_status][''][$ref_campus] = true;
     
            $total_A += $num_categoria[$ref_curso][$ref_status]['A'][$ref_campus];
            $total_B += $num_categoria[$ref_curso][$ref_status]['B'][$ref_campus];
            $total_C += $num_categoria[$ref_curso][$ref_status]['C'][$ref_campus];
            $total_D += $num_categoria[$ref_curso][$ref_status]['D'][$ref_campus];
            $total_E += $num_categoria[$ref_curso][$ref_status]['E'][$ref_campus];
            $total_F += $num_categoria[$ref_curso][$ref_status]['F'][$ref_campus];
            $total_G += $num_categoria[$ref_curso][$ref_status]['G'][$ref_campus];
            $total_H += $num_categoria[$ref_curso][$ref_status]['H'][$ref_campus];
            $total_I += $num_categoria[$ref_curso][$ref_status]['I'][$ref_campus];
            $total_J += $num_categoria[$ref_curso][$ref_status]['J'][$ref_campus];
            $total_L += $num_categoria[$ref_curso][$ref_status]['L'][$ref_campus];
            $total_NI += $num_categoria[$ref_curso][$ref_status][''][$ref_campus];
    
            if($fl_in_lm == 'f')
            {
                $total += $total_linha;
                $total_geral = $total_geral + $total_linha;
            }
            $i++;
        }
    }

    echo("<tr bgcolor=\"#CCCCCC\">\n");
    echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
    echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>Total do Curso</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_A</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_B</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_C</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_D</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_E</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_F</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_G</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_H</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_I</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_J</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_L</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>&nbsp;$total_NI</b></td>");
    echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$total</b></td>");
    echo("  </tr>");

    $total_geral_A += $total_A;
    $total_geral_B += $total_B;
    $total_geral_C += $total_C;
    $total_geral_D += $total_D;
    $total_geral_E += $total_E;
    $total_geral_F += $total_F;
    $total_geral_G += $total_G;
    $total_geral_H += $total_H;
    $total_geral_I += $total_I;
    $total_geral_J += $total_J;
    $total_geral_L += $total_L;
    $total_geral_NI += $total_NI;

    echo ("<tr>");
    echo ("<td bgcolor=\"#FFFFFF\" colspan=\"15\" height=\"22\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
    echo ("</tr>"); 
    
    echo("<tr bgcolor=\"#BBBBBB\">\n");
    echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\">&nbsp;</td>");
    echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>TOTAL GERAL</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_A</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_B</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_C</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_D</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_E</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_F</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_G</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_H</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_I</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_J</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_L</b></td>");
    echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral_NI</b></td>");
    echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"$fg1\"><b>$total_geral</b></td>");
    echo("  </tr>");

    echo("</table>");
?>
</body>
</html>
