<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>
<html>
<head>
<title>Número de Alunos Calouros Matriculados por Curso</title>
<script language="PHP">
function ListaCursos($id_periodo, $id_vestibular, $anterior)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select ref_curso, " .
          "        ref_campus, " .
          "        get_campus(ref_campus), " .
          "        curso_desc(ref_curso) " .
          " from vest_cursos_disp " .
    	  " where ref_vestibular = '$id_vestibular' and" .
	      "       fl_disponivel = '1' " .
    	  " order by ref_curso";
	  
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

   $total_inscritos = 0;
   $total_classificados = 0;
   $total_opcao1 = 0;
   $total_opcao2 = 0;
   $total_opcao3 = 0;
   $total_matriculados = 0;

   $extrato = "Código do Curso; Campus; Curso; Inscritos 1ª Opção; Classificados 1ª Opção; Matriculados 1ª Opção; Matriculados 2ª Opção; Matriculados 3ª Opçao; Totais de Matriculados\n";
  
   while( $query->MoveNext() )
   {
     list ($ref_curso,
           $ref_campus,
    	   $campus,
           $curso) = $query->GetRowValues();

     // Número de inscritos em primeira opção no curso
     $sql = " select count(*) " .
            " from vest_inscricoes " .
	    " where ref_opcao1 = '$ref_curso' and " .
	    "       ref_campus1 = '$ref_campus' and " .
	    "       ref_vestibular = '$id_vestibular'";
   
     $query_inscritos = $conn->CreateQuery($sql);
     
     $query_inscritos->MoveNext();
     
     $num_inscritos = $query_inscritos->GetValue(1);

     // Número de classificados em primeira opção no curso
     $sql = " select count(*) " .
            " from vest_inscricoes " .
	        " where ref_opcao1 = '$ref_curso' and " .
    	    "       ref_campus1 = '$ref_campus' and " .
	        "       ref_vestibular = '$id_vestibular' and " .
	        "       tipo_classificacao = 'C'";
   
     $query_classificados = $conn->CreateQuery($sql);
     
     $query_classificados->MoveNext();
     
     $num_classificados = $query_classificados->GetValue(1);

     // Matriculados em 1ª opção para o curso
     $sql = " select count(distinct(B.ref_pessoa)) " .
            " from vest_inscricoes A, contratos B " .
    	    " where A.ref_opcao1 = B.ref_curso and " .
    	    "       A.ref_campus1 = B.ref_campus and " .
    	    "       A.ref_pessoa = B.ref_pessoa and " .
    	    "       A.ref_opcao1 = '$ref_curso' and " .
    	    "       A.ref_campus1 = '$ref_campus' and " .
    	    "       B.ref_curso = '$ref_curso' and " .
    	    "       B.ref_last_periodo = '$id_periodo' and " .
    	    "       A.ref_vestibular = '$id_vestibular' and " .
            "       B.dt_desativacao is null and " .
    	    "       A.ref_pessoa in (select distinct ref_pessoa " .
            "                        from matricula " .
            "                        where ref_periodo = '$id_periodo' and " .
            "                              ref_curso = '$ref_curso' and " .
            "                              ref_pessoa = B.ref_pessoa and " .
            "                              dt_cancelamento is null) and ";
            
    	    if ($anterior)
            {
                $sql .= " B.ref_motivo_ativacao = '1';";    // Vestibulandos
            }
            else
            {
                $sql .= " B.cod_status = '1';";    // Vestibulandos
            }
     
     $query_opcao1 = $conn->CreateQuery($sql);
     
     $query_opcao1->MoveNext();
     
     $num_opcao1 = $query_opcao1->GetValue(1);

     // Matriculados em 2ª opção para o curso
     $sql = " select count(distinct(B.ref_pessoa)) " .
            " from vest_inscricoes A, contratos B " .
    	    " where A.ref_opcao2 = B.ref_curso and " .
    	    "       A.ref_campus2 = B.ref_campus and " .
    	    "       A.ref_pessoa = B.ref_pessoa and " .
      	    "       A.ref_opcao2 = '$ref_curso' and " .
    	    "       A.ref_campus2 = '$ref_campus' and " .
	        "       B.ref_curso = '$ref_curso' and " .
    	    "       B.ref_last_periodo = '$id_periodo' and " .
    	    "       A.ref_vestibular = '$id_vestibular' and " .
            "       B.dt_desativacao is null and " .
            "       A.ref_opcao1 || A.ref_campus1 <> '$ref_curso" . "$ref_campus' and " .
    	    "       A.ref_pessoa in (select distinct ref_pessoa " .
            "                        from matricula " .
            "                        where ref_periodo = '$id_periodo' and " .
            "                              ref_curso = '$ref_curso' and " .
            "                              ref_pessoa = B.ref_pessoa and " .
            "                              dt_cancelamento is null) and ";
           
    	    if ($anterior)
            {
                $sql .= " B.ref_motivo_ativacao = '1';";    // Vestibulandos
            }
            else
            {
                $sql .= " B.cod_status = '1';";    // Vestibulandos
            }

     $query_opcao2 = $conn->CreateQuery($sql);
     
     $query_opcao2->MoveNext();
     
     $num_opcao2 = $query_opcao2->GetValue(1);

     // Matriculados em 3ª opção para o curso
     $sql = " select count(distinct(B.ref_pessoa)) " .
            " from vest_inscricoes A, contratos B " .
    	    " where A.ref_opcao3 = B.ref_curso and " .
    	    "       A.ref_campus3 = B.ref_campus and " .
    	    "       A.ref_pessoa = B.ref_pessoa and " .
            "       A.ref_opcao3 = '$ref_curso' and " .
    	    "       A.ref_campus3 = '$ref_campus' and " .
    	    "       B.ref_curso = '$ref_curso' and " .
    	    "       B.ref_last_periodo = '$id_periodo' and " .
    	    "       A.ref_vestibular = '$id_vestibular' and " .
            "       B.dt_desativacao is null and " .
            "       A.ref_opcao1 || A.ref_campus1 <> '$ref_curso" . "$ref_campus' and " .
            "       A.ref_opcao2 || A.ref_campus2 <> '$ref_curso" . "$ref_campus' and " .
    	    "       A.ref_pessoa in (select distinct ref_pessoa " .
            "                        from matricula " .
            "                        where ref_periodo = '$id_periodo' and " .
            "                              ref_curso = '$ref_curso' and " .
            "                              ref_pessoa = B.ref_pessoa and " .
            "                              dt_cancelamento is null) and ";
    	    
            if ($anterior)
            {
                $sql .= " B.ref_motivo_ativacao = '1';";    // Vestibulandos
            }
            else
            {
                $sql .= " B.cod_status = '1';";    // Vestibulandos
            }

     $query_opcao3 = $conn->CreateQuery($sql);
     
     $query_opcao3->MoveNext();
     
     $num_opcao3 = $query_opcao3->GetValue(1);

     $num_matriculados = $num_opcao1 + $num_opcao2 + $num_opcao3;
     
     $href = "<a href=\"lista_calouros_matriculados.php3?ref_curso=$ref_curso&ref_periodo=$id_periodo&ref_campus=$ref_campus&ref_vestibular=$id_vestibular&anterior=$anterior\"> " . $curso . "</a>";
     
     $href1 = "<a href=\"lista_calouros_matriculados_curso.php3?ref_curso=$ref_curso&ref_periodo=$id_periodo&ref_campus=$ref_campus&ref_vestibular=$id_vestibular&anterior=$anterior\"> " . $num_matriculados . "</a>";

     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"9\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Calouros Matriculados por Curso</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"9\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . " </b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Inscritos<br>1ª Opção</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Classific<br>1ª opção</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Matric<br>1ª opção</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Matric<br>2ª opção</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Matric<br>3ª opção</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Totais de<br>Matriculados</b></font></td>");
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
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_curso</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$campus</td>");
     echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>");
     echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_inscritos</td>");
     echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_classificados</td>");
     echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_opcao1</td>");
     echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_opcao2</td>");
     echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_opcao3</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\"><b>$href1</b></td>");
     echo("  </tr>");

     $extrato .= "$ref_curso; $campus; $curso; $num_inscritos; $num_classificados; $num_opcao1; $num_opcao2; $num_opcao3; $num_matriculados\n";

     $i++;

     $total_inscritos += $num_inscritos;
     $total_classificados += $num_classificados;
     $total_opcao1 += $num_opcao1;
     $total_opcao2 += $num_opcao2;
     $total_opcao3 += $num_opcao3;
     $total_matriculados += $num_matriculados;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"50%\" colspan=\"3\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL DE ALUNOS CALOUROS POR CURSO:</b></td>");
   echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_inscritos</b></td>");
   echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_classificados</b></td>");
   echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_opcao1</b></td>");
   echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_opcao2</b></td>");
   echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_opcao3</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_matriculados</b></td>");
   echo("  </tr>\n");
   
   $extrato .= "TOTAL DE ALUNOS CALOUROS POR CURSO:;;; $total_inscritos; $total_classificados; $total_opcao1; $total_opcao2; $total_opcao3; $total_matriculados\n";

   echo("<tr><td colspan=\"9\"><hr></td></tr>");
   echo("</table></center>");
  
   $filename = 'calouros_matriculados.txt';
   $fp = fopen($filename, "w");
   fwrite($fp, $extrato);
   fclose($fp);
  
   echo("<br><center><a href=\"$filename\">Arquivo Texto</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/relat/lista_calouros_ps.php3?ref_periodo=$id_periodo&ref_vestibular=$id_vestibular&anterior=$anterior\">Lista Assinaturas</a></center><br>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaCursos($ref_periodo, $ref_vestibular, $anterior);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='calouros_matriculados_curso.phtml'">
</div>
</form>
</body>
</html>
