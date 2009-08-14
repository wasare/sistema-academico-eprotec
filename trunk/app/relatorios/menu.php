<?php

require("../../configs/configuracao.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
	<title>Menu</title>
</head>

<body>
	<h2>Relat&oacute;rios</h2>

	<input type="image" name="voltar" 
		src="../../public/images/icons/back.png" 
		alt="Voltar" 
		title="Voltar" 
		onclick="history.back(-1);return false;" class="botao" />

<div class="box_geral">
	<a href="aprovados_reprovados/pesquisa_aprovados_reprovados.php" title="Alunos aprovados / reprovados" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Alunos Aprovados/Reprovados
	</a><br />
	<a href="pesquisa_dispensados.php" title="Relat&oacute;rio de Alunos Dispensados" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Alunos Dispensados
	</a><br />
	<a href="pesquisa_alunos.php" title="Alunos matriculados" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Alunos Matriculados
	</a><br />
	<a href="pesquisa_todos_alunos_periodo.php"	title="Todos os alunos matriculado no per&iacute;odo" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Alunos Matriculados (Todos)
	</a><br />		
	<a href="pesquisa_matriculados_por_cidade.php" title="Alunos matriculados por cidade de origem" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Alunos Matriculados por curso e cidade
	</a><br />
	<a href="pesquisa_alunos_novatos.php" title="Listagem de Alunos Novatos (v&iacute;nculo inicial no curso  / per&iacute;odo"	target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Alunos Novatos
	</a><br />
	<a href="pesquisa_boletim.php"	title="Emiss&atilde;o dos Boletins Escolares" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Boletim Escolar
	</a><br />
	<a href="pesquisa_cursos_no_periodo.php" title="Cursos em andamento" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Cursos em andamento
	</a><br />
	<a href="pesquisa_diarios.php" title="Andamento dos di&aacute;rio no per&iacute;odo" target="_self"	class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Di&aacute;rios (andamento no per&iacute;odo)
	</a><br />
	<a href="egressos/pesquisa_egressos.php" title="Relat&oacute;rio de Egressos" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Egressos
	</a><br />
	<a href="pesquisa_faltas_global.php" title="Relat&oacute;rio de Faltas Global por Per&iacute;odo / Curso" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Faltas Global
	</a>
	<br />
	<a href="pesquisa_ficha_academica.php" title="Vida acad&ecirc;mica do aluno por curso" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Ficha Acad&ecirc;mica
	</a>
	<br />
	<a href="declaracao_matricula/pesquisa_declaracao_matricula.php" title="Declara&ccedil;&atilde;o de matr&iacute;cula" target="_self" class="menu_relatorio">
		<img src="../../public/images/icons/lupa.png" />&nbsp;Declara&ccedil;&atilde;o de matr&iacute;cula
	</a>
</div>
</body>
</html>
