<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Número de Alunos por Ordem de Matrícula</title>
<script language="PHP">
function ListaAlunos($ref_curso, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select ref_pessoa, " .
          "        pessoa_nome(ref_pessoa),    " .
          "        ref_disciplina_ofer,   " .
          "        ref_disciplina,  " .
          "        descricao_disciplina(ref_disciplina),  " .
          "        get_disciplina_de_disciplina_of(ref_disciplina_ofer), " .
          "        descricao_disciplina(get_disciplina_de_disciplina_of(ref_disciplina_ofer)), " .
          "        ref_curso,   " .
          "        curso_desc(ref_curso), " .
          "        to_char(dt_matricula,'dd-mm-yyyy'), " .
          "        to_char(hora_matricula, 'hh24:mi:ss'), " .
          "        pessoa_email(ref_pessoa) " .
          " from matricula " .
          " where ref_disciplina_ofer='$ref_curso' and " .
    	  "       obs_aproveitamento = '' and ";
          
          if ($anterior)
          {
            $sql .= " (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula') ";
          }
          else
          {
            $sql .= " dt_cancelamento is null ";
          }
          
   $sql.= " order by dt_matricula, hora_matricula;" ;

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
     list ($ref_pessoa,
           $pessoa_nome,
           $ref_disciplina_ofer,
           $ref_disciplina_curriculo,
           $descricao_disciplina_curriculo, 
           $ref_disciplina,
           $descricao_disciplina, 
           $ref_curso,
           $curso,
           $dt_matricula,
           $hora_matricula,
           $email) = $query->GetRowValues();
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Alunos Matriculados</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Disciplina Ofertada: " . $ref_disciplina . " - " . $descricao_disciplina . "</b></font></td>");
         echo ("</tr>"); 
         if ($anterior)
         { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
         else
         { $periodo_antigo = " Não"; }
         echo("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo: <b>$periodo_antigo</b></font></td>");
         echo ("</tr>"); 
         
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Data Matrícula</b></font></td>");
         echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Hora Matrícula</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>E-mail</b></font></td>");
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
     echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$pessoa_nome</td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dt_matricula</td>");
     echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$hora_matricula</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$email</td>");
     echo("  </tr>");
     
     $i++;

   }

   echo("<tr><td colspan=\"6\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaAlunos($ref_curso, $dt_livro_matricula, $anterior);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
