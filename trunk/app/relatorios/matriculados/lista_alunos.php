<?php

require_once('matriculados.php');

?>
<html>
<head>
	<title>Lista de Alunos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div style="width: 760px;" align="center">
	<div align="center" style="text-align:center; font-size:12px;">
	        <?php echo $header->get_empresa($PATH_IMAGES); ?>
	    	<br /><br />
	</div> 
	<h2><?php echo $titulo; ?></h2>
	<?php echo $info; ?>
	<?php rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); ?>
	<div class="carimbo_box">
		_______________________________<br>
		<span class="carimbo_nome">
			<?php echo $carimbo->get_nome($_POST['carimbo']);?>
		</span>
		<br />
		<span class="carimbo_funcao">
			<?php echo $carimbo->get_funcao($_POST['carimbo']);?>
		</span>
	</div>
</div>
</body>
</html>