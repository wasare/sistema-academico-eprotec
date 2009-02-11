<? require("../../../../lib/common.php"); ?>
<? require("../../lib/ProcessaMaterial.php3"); ?>
<?
CheckFormParameters(array('ref_periodo',
            			  'ref_curso',
            			  'ref_campus'));
?>
<html>
<head>
<title>Lista de Disciplinas do Currículo Cursadas</title>

<script language="JavaScript">
function Mostra_Alunos(curso_id, campus_id, disciplina_id, periodo_id)
{
  var url = "lista_aptos_cursar_curriculo.php3" +
            "?ref_curso=" + escape(curso_id) +
            "&ref_campus=" + escape(campus_id) +
            "&ref_disciplina=" + escape(disciplina_id) +
            "&ref_periodo=" + escape(periodo_id);

  location = url;
}
</script>

<script language="PHP">
function Lista_Disciplinas($periodo_id, $curso_id, $campus_id)
{

   $conn = new Connection;
   $conn->Open();

   $sql = " select distinct A.ref_pessoa, " .
          "                 B.ref_curso, " .
          "                 B.ref_campus " .
    	  " from matricula A, contratos B " .
	      " where A.ref_contrato = B.id and " .
    	  "	      A.ref_curso = B.ref_curso and " .
    	  "       A.ref_campus = B.ref_campus and " .
          "       A.ref_pessoa = B.ref_pessoa and " .
          "       A.ref_periodo = B.ref_last_periodo and " .
	      "	      B.ref_curso = '$curso_id' and " .
	      "	      B.ref_last_periodo = '$periodo_id' and " .
	      "       B.ref_campus = '$campus_id' and " .
    	  "       A.dt_cancelamento is null and " .
    	  "	      B.dt_desativacao is null ";

   $query = $conn->CreateQuery($sql);

   while( $query->MoveNext() )
   {
     list ($ref_pessoa,
           $ref_curso,
           $ref_campus) = $query->GetRowValues();
        
           $conn = new Connection;
           $conn->Open();
           
           $sql_delete = " delete from disciplinas_todos_alunos " .
                         " where ref_curso = '$ref_curso' and " .
                         "       ref_campus = '$ref_campus' and " .
                         "       ref_pessoa = '$ref_pessoa'";

           $ok = $conn->CreateQuery($sql_delete);
            
           $conn->Close();

           ProcessaMaterial($periodo_id, $ref_curso, $ref_campus, $ref_pessoa);
   }

   $query->Close();
   $conn->Close();

   $conn = new Connection;
   $conn->Open();
   $conn->Begin();
   
   $sql = " select B.ref_disciplina, " .
          "        descricao_disciplina(B.ref_disciplina), " .
          "        A.semestre_curso, " .
          "        sum(case when B.status = 1 then 1 else 0 end) " .
          " from cursos_disciplinas A, disciplinas_todos_alunos B, contratos C " .
          " where A.ref_curso = B.ref_curso and " .
          "       A.ref_campus = B.ref_campus and " .
          "       B.ref_curso = C.ref_curso and " .
          "       B.ref_campus = C.ref_campus and " .
          "       A.ref_curso = C.ref_curso and " .
          "       A.ref_campus = C.ref_campus and " .
          "       A.ref_disciplina = B.ref_disciplina and " .
          "       B.ref_pessoa = C.ref_pessoa and " .
          "       A.ref_curso = '$curso_id' and " .
          "       A.ref_campus = '$campus_id' and " .
	      "	      C.ref_last_periodo = '$periodo_id' and " .
    	  "	      C.dt_desativacao is null and " .
          "       A.curriculo_mco in ('M','C') and " .
          "       A.dt_final_curriculo is null " .
          " group by B.ref_disciplina, " .
          "          A.semestre_curso " .
          " order by A.semestre_curso";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"80%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=0;

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
     list ($ref_disciplina,
           $descricao_disciplina,
           $semestre,
           $count) = $query->GetRowValues();

     if ($i % 2)
     {
        $bg = $bg1;
        $fg = $fg1;
     }
     else
     {
        $bg = $bg2;
        $fg = $fg2;
     }

     if ($i == 0)
     {
         echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"5\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Listagem de Possibilidades de Matrícula do curso $curso_id no campus $campus_id</b></center></font></td>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cod.</b></font></td>");
         echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Semestre</b></font></td>");
         echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Alunos Aptos</b></font></td>");
         echo ("  </tr>"); 
      }
      
     $href = "<a href=\"javascript:Mostra_Alunos('$curso_id','$campus_id','$ref_disciplina','$periodo_id')\"><img src=\"../images/select.gif\" alt='Mostrar Alunos' border=0></a>";
     
     echo("<tr bgcolor=\"$bg\">\n");
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
     echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$descricao_disciplina</td>");
     echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$semestre</td>");
     echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$count</td>");
     echo("  </tr>");
     
     $i++;
     
   }

   echo ("<tr><td bgcolor=\"#000000\" colspan=\"5\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Total de disciplinas do Currículo: $i</b></font></td></tr>");

   echo ("<tr><td colspan=\"5\" align=\"center\"><hr size=\"1\">" .
         "<input type=\"button\" value=' Sair ' onClick=\"javascript:history.go(-1)\">" .
         "</td></tr>\n");
   
   echo("</table></center>");

   $query->Close();
   
   $conn->Finish();
   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF">
<br>
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Disciplinas($ref_periodo, $ref_curso, $ref_campus);
  </script>
</form>
</body>
</html>
