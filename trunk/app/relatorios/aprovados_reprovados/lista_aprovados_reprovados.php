<?php

require_once("aprovados_reprovados.php");
 
?>
<html>
<head>
	<title>Lista de Alunos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body marginwidth="20" marginheight="20">
<div style="width: 760px;" align="center">
	<div align="center" style="text-align:center; font-size:12px;">
        	<?php echo $header->get_empresa($PATH_IMAGES); ?>
            <br /><br />
        </div> 
	<h2>RELAT&Oacute;RIO DE SITUA&Ccedil;&Atilde;O DE APROVA&Ccedil;&Atilde;O DE ALUNO(S)</h2>
	<?php
		echo $info;
  		rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
	?>
	<br /><br />
	<div class="carimbo_box">
           	_______________________________<br>
			<span class="carimbo_nome">
		   		<?php echo $carimbo->get_nome($_POST['carimbo']);?>
			</span><br />
			<span class="carimbo_funcao">
		   		<?php echo $carimbo->get_funcao($_POST['carimbo']);?>
			</span>
		</div>
</div>
</body>
</html>
