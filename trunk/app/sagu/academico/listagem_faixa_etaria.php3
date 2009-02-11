<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Curso e Faixa Etária</title>
<script language="PHP">
function Lista_Cursos_Faixa_Etaria($id_periodo)
{
   $conn = new Connection;

   $conn->Open();

   $total=0;
   $aux_curso = 0;
   $aux_campus = 0;
  
   $total_f1f= $total_f2f = $total_f3f = $total_f4f = $total_f5f = $total_f6f = $total_f7f = $total_f8f = $total_f9f = $total_f10f = $total_f11f = 0;

   $total_f1m = $total_f2m = $total_f3m = $total_f4m = $total_f5m = $total_f6m = $total_f7m = $total_f8m = $total_f9m = $total_f10m = $total_f11m = 0;
   
   $sql = " select A.ref_curso_atual, " .
          "        get_campus(A.ref_campus_atual), " .
          "        curso_desc(A.ref_curso_atual), " .
    	  "        get_sexo(A.ref_pessoa), " .
          "        pessoa_idade(A.ref_pessoa), " .
          "        count(*), " .
          "        A.ref_campus_atual  " .
          " from livro_matricula A, status_matricula B " .
          " where A.ref_status = B.id and " .
	      "       A.ref_periodo='$id_periodo' and " .
    	  "       B.fl_in_lm = 'f' and ".
	      "       B.id <> 7 and " .
    	  "       B.id <> 11 " .
          " group by A.ref_curso_atual, " .
	      "          A.ref_campus_atual,".
    	  "          get_sexo(A.ref_pessoa), " .
	      "	         pessoa_idade(A.ref_pessoa)" ;

/*   $sql = " select ref_curso, " .
          "        get_campus(ref_campus), " .
          "        curso_desc(ref_curso), " .
	  "        get_sexo(ref_pessoa), " .
          "        pessoa_idade(ref_pessoa), " .
          "        count(*), " .
          "        ref_campus  " .
          " from contratos " .
          " where ref_last_periodo='$id_periodo' and" .
          "       dt_desativacao is null and ref_curso<>6 " .  
          " group by ref_curso, ref_campus,".
	  "          get_sexo(ref_pessoa), pessoa_idade(ref_pessoa)" ;
*/	  
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
     list ( $ref_curso,
            $campus, 
            $curso,
            $sexo, 
            $idade,
            $numero, 
            $ref_campus) = $query->GetRowValues();

   
     if ($i == 1)
     {
         $aux_curso = $ref_curso;
         $aux_campus = $ref_campus;
         $aux_nome_curso = $curso;
         $aux_nome_campus = $campus;

         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Idade</b></font></td>");
         echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Masculino</b></font></td>");
         echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Feminino</b></font></td>");
         echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total</b></font></td>");
         echo ("  </tr>"); 
        }

        if(($aux_curso != $ref_curso) || ($aux_campus != $ref_campus))
        {
            echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$aux_curso - $aux_nome_curso - Campus: $aux_nome_campus</b></font></td>");
	    
	    for($x = 1; $x<=11; $x++)
	    {
                 switch($x)
	       {
                 case 1: $faixa = 'Até 18 anos:';
		         $total_fm = $total_f1m;
			 $total_ff = $total_f1f;
			 break;
                 case 2: $faixa = 'De 19 à 24 anos:';
		         $total_fm = $total_f2m;
			 $total_ff = $total_f2f;
		         break;
                 case 3: $faixa = 'De 25 à 29 anos:';
		         $total_fm = $total_f3m;
			 $total_ff = $total_f3f;
	                 break;
                 case 4: $faixa = 'De 30 à 34 anos:';
		         $total_fm = $total_f4m;
			 $total_ff = $total_f4f;
		         break;
                 case 5: $faixa = 'De 35 à 39 anos:';
		         $total_fm = $total_f5m;
			 $total_ff = $total_f5f;
		         break;
                 case 6: $faixa = 'De 40 à 44 anos:';
		         $total_fm = $total_f6m;
			 $total_ff = $total_f6f;
		         break;
                 case 7: $faixa = 'De 45 à 49 anos:';
		         $total_fm = $total_f7m;
			 $total_ff = $total_f7f;
		         break;
                 case 8: $faixa = 'De 50 à 54 anos:';
		         $total_fm = $total_f8m;
			 $total_ff = $total_f8f;
		         break;
                 case 9: $faixa = 'De 55 à 59 anos:';
		         $total_fm = $total_f9m;
			 $total_ff = $total_f9f;
		         break;
                 case 10: $faixa = 'De 60 à 64 anos:';
		          $total_fm = $total_f10m;
			  $total_ff = $total_f10f;
		          break;
                 case 11: $faixa = 'Mais de 65 anos:';
		          $total_fm = $total_f11m;
			  $total_ff = $total_f11f;
		          break;
                 }
		 
               echo("<tr bgcolor=\"$bg1\">\n");
               echo ("<td width=\"50%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$faixa</td>");
               echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$total_fm</td>");
               echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$total_ff</td>");
	       $soma = $total_ff+$total_fm;
               echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$soma</td>");
               echo("  </tr>");
	    }
     
   	    $aux_curso = $ref_curso;
	    $aux_campus = $ref_campus;
	    $aux_nome_curso = $curso;
	    $aux_nome_campus = $campus;
	    $total_f1m = $total_f2m = $total_f3m = $total_f4m = $total_f5m = $total_f6m = $total_f7m = $total_f8m = $total_f9m = $total_f10m = $total_f11m = 0;
	    $total_f1f= $total_f2f = $total_f3f = $total_f4f = $total_f5f = $total_f6f = $total_f7f = $total_f8f = $total_f9f = $total_f10f = $total_f11f = 0;
          
        }


        if($idade <=18 )
	{ 
           if($sexo=='M')
	     $total_f1m += $numero;
	   else
	     $total_f1f += $numero;
	}
	elseif($idade >18 && $idade <=24)
	{
            if($sexo=='M')
               $total_f2m += $numero;
	    else
	       $total_f2f += $numero;
        }
	elseif($idade >24 && $idade <=29)
	{
            if($sexo=='M')
               $total_f3m += $numero;
	    else
	       $total_f3f += $numero;
        }
	elseif($idade >29 && $idade <=34)
	{
            if($sexo=='M')
               $total_f4m += $numero;
	    else
	       $total_f4f += $numero;
        }
	elseif($idade >34 && $idade <=39)
	{
            if($sexo=='M')
               $total_f5m += $numero;
	    else
	       $total_f5f += $numero;
        }
	elseif($idade >39 && $idade <=44)
	{
           if($sexo=='M')
              $total_f6m += $numero;
	   else
	      $total_f6f += $numero;
        }
	elseif($idade >44 && $idade <=49)
	{
           if($sexo=='M')
             $total_f7m += $numero;
	   else
	     $total_f7f += $numero;
        }
	elseif($idade >49 && $idade <=54)
	{
           if($sexo=='M')
              $total_f8m += $numero;
	   else
	      $total_f8f += $numero;
        } 
	elseif($idade >54 && $idade <=59)
	{
           if($sexo=='M')
              $total_f9m += $numero;
	   else
	      $total_f9f += $numero;
        }
	elseif($idade >59 && $idade <=64)
	{
           if($sexo=='M')
              $total_f10m += $numero;
	   else
	      $total_f10f += $numero;
        }
	else
	{
	    if($sexo=='M')
               $total_f11m += $numero;
  	    else
	       $total_f11f += $numero;
        }

    $i++;

     $total=$total+$numero;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"4\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
    Lista_Cursos_Faixa_Etaria($periodo_id);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='listagem_faixa_etaria.phtml'">
</div>
</form>
</body>
</html>
