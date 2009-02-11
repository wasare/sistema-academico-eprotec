<? header("Cache-Control: no-cache"); ?>
<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title>Lista de Alunos por Cidade</title>

<style>

 .table1 {
 	border: 1px solid #999999;
 }
 
 .title1 {
 	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:18px;
	color:#000000;
	font-weight:bold;
 }
 
 .title2 {
 	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#000000;
 }
 
 .tit_tabela{
 	text-decoration:none;
	font-style:normal;
  	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
	color:#ffffff;
 }
 
 .texto1{
 	text-decoration:none;
	font-style:normal;
  	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10px;
	color:#000000;
 }
 
</style>

<script language="PHP">
function ListaAlunos($ref_periodo, $ref_curso, $ref_campus, $disciplina_ofer_compl=null)
{
   global $opcoes;

   $conn = new Connection;

   $conn->open();

   $total=0;

/*   $sql = " select distinct " .
          "        B.id, " .
          "        B.nome, " .
          "        pessoa_idade(B.id), " .
          "        pessoa_fone(B.id), " .
          "        get_cidade(B.ref_cidade), " .
          "        A.dt_ativacao, " .
          "        curso_desc(A.ref_curso), " .
          "        get_campus('$ref_campus'), " .
          "        is_matriculado('$ref_periodo', A.ref_pessoa) is not null " .
          " from contratos A, pessoas B " .
          " where A.ref_pessoa = B.id and ";
*/

   $sql = " select distinct " .
          "        B.id, " .
          "        B.nome, B.rua || ' ' || B.complemento,  B.bairro, " . 
          "        get_cidade(B.ref_cidade), " .
          "        B.cep, " .
          "        curso_desc(A.ref_curso), " .
          "        get_campus('$ref_campus'), " .
          "        is_matriculado('$ref_periodo', A.ref_pessoa) is not null " .
          " from contratos A, pessoas B " .
          " where A.ref_pessoa = B.id and ";

   if ( $disciplina_ofer_compl )
   {
       $sql .= "        disciplinas_ofer_compl.id = '$disciplina_ofer_compl' and " .
               "        matricula.ref_periodo = '$ref_periodo' and " .
               "        matricula.ref_pessoa = B.id and " .
               "        matricula.ref_contrato = A.id and " .
               "        matricula.ref_disciplina = disciplinas_ofer.ref_disciplina and " .
               "        disciplinas_ofer_compl.ref_disciplina_ofer = disciplinas_ofer.id and "; 
   }
   else
   {      
       $sql .= "       A.ref_curso = '$ref_curso' and " .
               "       A.ref_campus = '$ref_campus' and ";
   }
   
   $sql .= "       A.dt_desativacao is null " .
           " order by B.nome; ";

   $query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

   echo("<center><table width=\"90%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#DBDBDB";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $aux_curso=0;

   while( $query->MoveNext() )
   {
     list ($id,
           $nome,
           $idade,
           $fone,
           $cidade,
    	   $dt_ativacao,
           $curso,
           $campus,
	       $fl_matriculado) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<td colspan=\"8\" height=\"56\" align=\"center\"><span class=\"title1\">".
		 "Listagem de Alunos com contratos ativos</span><br><span class=\"title2\"><b>Curso :</b> " . 
		 $ref_curso . " - " . $curso . " <b>Campus:</b> $campus</span></td>");
		 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"2%\"><span class=\"tit_tabela\"><center><b>C</b></center></span></td>");
         echo ("<td width=\"3%\"><span class=\"tit_tabela\"><center><b>Cód.</b></center></span></td>");
         echo ("<td width=\"25%\"><span class=\"tit_tabela\"><b>Nome</b></span></td>");
		 echo ("<td width=\"25%\"><span class=\"tit_tabela\"><b>Rua</b></span></td>"); // Idade
         //echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Idade</b></font></td>");  
         echo ("<td width=\"15%\"><span class=\"tit_tabela\"><b>Bairro</b></span></td>"); // Fones
         echo ("<td width=\"15%\"><span class=\"tit_tabela\"><b>Cidade</b></span></td>");
         echo ("<td width=\"10%\"><span class=\"tit_tabela\"><b>CEP</b></span></td>");
         echo ("<td width=\"5%\"><span class=\"tit_tabela\"><center><b>Mat.</b></center></span></td>");
         echo ("  </tr>"); 
        }
     
     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$i</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$id&nbsp;</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$nome</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$idade</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$fone</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$cidade</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;" . $dt_ativacao . "</td>");
		  //echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;" . InvData($dt_ativacao) . "</td>"); 
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;" . $opcoes[$fl_matriculado] . "</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$i</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$id&nbsp;</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$nome</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$idade</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$fone</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;$cidade</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;" . $dt_ativacao . "</td>");
		  // echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;" . InvData($dt_ativacao) . "</td>");
          echo ("<td class=\"table1\"><span class=\"texto1\">&nbsp;" . $opcoes[$fl_matriculado] . "</td>");
          echo("  </tr>\n");
         }

     $i++;

   }

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<div align="center"> 
	<input value="Imprimir" onclick="window.print()" type="button">&nbsp;
  	<input type="button" name="Button" value="  Voltar  " onClick="location='../lista_alunos_ps.phtml'">
</div>
<hr>
<script language="PHP">
   ListaAlunos($ref_periodo, $ref_curso, $ref_campus, $disciplina_ofer_compl);
</script>
<hr>
<div align="center"> 
	<input value="Imprimir" onclick="window.print()" type="button">&nbsp;
  	<input type="button" name="Button" value="  Voltar  " onClick="location='../lista_alunos_ps.phtml'">
</div>
</form>
</body>
</html>
