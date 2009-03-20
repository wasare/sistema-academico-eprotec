<?php
header("Cache-Control: no-cache");
require ("../../lib/common.php");
require ("../../lib/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../../Styles/formularios.css" rel="stylesheet" type="text/css" />
<style>
.menu_link {
	text-decoration:none;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bold;
	color:#333333;
}
.menu_link:hover {
	text-decoration:underline;
}
img {
	border: 0;
}
.style1 {color: #FF0000}
</style>
<title>Menu</title>
</head>
<body>
   <h2>Relat&oacute;rios</h2>
   <table border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td width="60"><div align="center"><a href="javascript:history.back(-1)" class="bar_menu_texto"><img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
         Voltar</a></div></td>
     </tr>
   </table>
   <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#E6E6E6">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" valign="top"><p><a href="pesquisa_alunos_nao_aprovados.php" title="Alunos aprovados / reprovados" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Alunos Aprovados/Reprovados</a><a href="pesquisa_alunos.php" title="Alunos matriculados" target="_self" class="menu_link"><br />
      <img src="../../images/icons/lupa.png" />&nbsp;Alunos Matriculados</a><br />
        <a href="pesquisa_todos_alunos_periodo.php" title="Todos os alunos matriculado no per&iacute;odo" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Alunos Matriculados (Todos)</a><br />
        <a href="pesquisa_alunos_novatos.php" title="Listagem de Alunos Novatos (v&iacute;nculo inicial no curso  / per&iacute;odo" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Listagem de Alunos Novatos</a><br />
        <a href="pesquisa_matriculados_por_cidade.php" title="Alunos matriculados por cidade de origem" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Alunos Matriculados por curso e cidade</a><br />
        <a href="pesquisa_cursos_no_periodo.php" title="Cursos em andamento" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Cursos em andamento por per&iacute;odo</a><br />
        <a href="pesquisa_diarios.php" title="Andamento dos di&aacute;rio no per&iacute;odo" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Di&aacute;rios (andamento no per&iacute;odo)</a><br />
        <a href="pesquisa_ficha_academica.php" title="Vida acad&ecirc;mica do aluno por curso" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" />&nbsp;Ficha Acad&ecirc;mica</a> / <a href="pesquisa_ficha_global.php" title="Vida acad&ecirc;mica do aluno em todos os cursos matriculados" target="_self" class="menu_link"> Situa&ccedil;&atilde;o Global</a><a href="../../relatorios/pesquisa_todos_alunos_periodo.php" title="Alunos n&atilde;o aprovados" target="_self" class="menu_link"><br />
        <img src="../../images/icons/lupa.png" />&nbsp;Boletim Escolar</a><br />    
        <a href="pesquisa_faltas_global.php" title="Relat&oacute;rio de Faltas Global por Per&iacute;odo / Curso" 
target="_self" class="menu_link"><img src="../../images/icons/lupa.png" />&nbsp;Faltas Global</a><br />    

    </p>
    </td>
    <td valign="top">
    <a href="consultas_diversas/index.php" title="Relat&oacute;rio de consultas diversas" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;Consultas diversas</a>
    <p>Em desenvolvimento:</p>
<a href="matriz/filtro.php" title="Emiss&atilde;o de Hist&oacute;rico Escolar" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" alt="" />&nbsp;<span class="style1">Hist&oacute;rico</span></a><a href="boletim" title="Boletim" target="_self" class="menu_link"><span class="style1"><br />
    </span></a><a href="pesquisa_declaracao.php" title="Emiss&atilde;o de Declara&ccedil;&otilde;es Diversas" target="_self" class="menu_link"><img src="../../images/icons/lupa.png" />&nbsp;<span class="style1">Declara&ccedil;&atilde;o</span></a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
</body>
</html>