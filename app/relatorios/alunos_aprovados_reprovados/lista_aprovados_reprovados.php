<?php
  
  require_once("../../../configs/configuracao.php");
  require_once("../../../lib/adodb/tohtml.inc.php");
  require_once("aprovados_reprovados.php");
  
?>
<html>
<head>
<title>Lista de Alunos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
	<div style="width: 760px;">
		<?php 
			/**
			 * FIXME: Inserir cabecalho e carimbo
		 	*/
	
			//header
		?>
		<h2>RELAT&Oacute;RIO DE SITUA&Ccedil;&Atilde;O DE APROVA&Ccedil;&Atilde;O DE ALUNO(S)</h2>
		<?php
			echo $info;
  			rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
		?>
		<br><br>
		<?php //carimbo ?>
	</div>
</body>
</html>
