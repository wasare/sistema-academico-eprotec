<?php

header("Cache-Control: no-cache");
require_once ("lib/common.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SA</title>
<link href="Styles/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function iframeAutoHeight(quem){

   if(navigator.appName.indexOf("Internet Explorer")>-1){ 
        	
       var func_temp = function(){
          var val_temp = quem.contentWindow.document.body.scrollHeight + 30
          quem.style.height = val_temp + "px";
       }
       setTimeout(function() { func_temp() },100) //ie sucks
        		
   }else {
       var val = quem.contentWindow.document.body.parentNode.offsetHeight + 30
       quem.style.height= val + "px";
   }
}
</script>
<script src="lib/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="lib/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<link href="favicon.ico" rel="shortcut icon" />
</head>
<body style="border: 0; overflow: visible">
<div align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" valign="middle" height="40">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="50" valign="middle"><a href="inicio.php"><img
					src="images/icon_sa.gif" alt="Principal" width="40" height="34" /></a><a
					href="inicio.php" class="titulo1"></a></td>
				<td width="230"><a href="inicio.php" class="titulo1">Sistema
				Acad&ecirc;mico</a></td>
				<td valign="top">
				<div align="right" class="texto1"><strong>Desenvolvimento: </strong></div>
				</td>
				<td valign="middle">&nbsp;<a
					href="<?=$IEurl?>" target="_blank"><img
					src="images/ifmg.jpg" alt="IFMG - Campus Bambu&iacute;"
					title="IFMG - Campus Bambu&iacute;" /></a>&nbsp;&nbsp;<a
					href="http://cefetbambui.edu.br/gerencia_ti" target="_blank"><img
					src="images/gti.jpg" alt="Ger&ecirc;ncia TI"
					title="Ger&ecirc;ncia de TI" width="50" height="34" /></a></td>
                   <?php
                         if ($_SERVER['HTTP_HOST'] == 'dev.cefetbambui.edu.br' || $host != 'dados.cefetbambui.edu.br')
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Servidor de BD: </strong>'. $host;
                    ?>

			</tr>
		</table>
		</td>
	</tr>
	<tr>
		
		<td width="23" class="menu">
			<a href="inicio.php">
				<img src="images/home_icon.gif" alt="P&aacute;gina inicial" title="P&aacute;gina inicial" />
			</a>
		</td>
		<td width="526" class="menu">
		
		<ul id="MenuBar1" class="MenuBarHorizontal">
			<!-- <li><a href="inicio.php">In&iacute;cio</a></li>-->
			<li><a class="MenuBarItemSubmenu" href="#">Sistema</a>
			<ul>
				<li><a href="app/exportar/exportar_sistec.php" target="frame2">Exportar matr&iacute;culas para o SISTEC</a></li>
				<li><a href="#" class="MenuBarItemSubmenu">WebDi&aacute;rio</a>
				<ul>
					<li><a href="<?php echo $BASE_URL . 'app/webdiario'; ?>" target="frame2">Acessar WebDi&aacute;rio</a></li>
					<li><a href="app/acessowebdiario/index.php" target="frame2">Usu&aacute;rios do WebDi&aacute;rio</a></li>
					
				</ul>
				</li>
				<li><a href="#" class="MenuBarItemSubmenu">Configura&ccedil;&otilde;es</a>
				<ul>
					<li><a href="app/sagu/academico/consulta_periodos.phtml"
						target="frame2">Per&iacute;odos</a></li>
					<li><a
						href="app/sagu/academico/consulta_inclui_departamentos.phtml"
						target="frame2">Departamentos</a></li>
					<li><a href="app/sagu/academico/areas_ensino.phtml" target="frame2">&Aacute;rea
					de ensino</a></li>
					<li><a href="app/sagu/academico/cadastro_salas.phtml"
						target="frame2">Salas</a></li>
					<li><a href="app/sagu/academico/carimbos.phtml" target="frame2">Carimbos</a></li>
					<li><a href="app/sagu/usuarios/consulta_inclui_usuarios.phtml"
						target="frame2">Usu&aacute;rios do sistema</a></li>
				</ul>
				</li>
			</ul>
			</li>		
			<li><a class="MenuBarItemSubmenu" href="#">Cadastros</a>
			<ul>
				<li><a href="app/sagu/academico/consulta_inclui_pessoa.phtml"
					target="frame2">Pessoas F&iacute;sicas</a></li>
				<li><a href="app/sagu/academico/consulta_inclui_contratos.phtml"
					target="frame2">Contratos</a></li>
                <li><a href="app/colacao_grau/index.php" target="frame2">Cola&ccedil;&atilde;o de grau</a></li>
				<li><a href="app/sagu/academico/consulta_inclui_professores.phtml"
					target="frame2">Professores</a></li>
				<li><a href="app/sagu/academico/coordenadores.phtml" target="frame2">Coordenadores</a></li>
				<li><a href="#" class="MenuBarItemSubmenu">Gen&eacute;rico</a>
				<ul>
					<li><a href="app/sagu/generico/paises_inclui.phtml" target="frame2">Pa&iacute;ses</a></li>
					<li><a href="app/sagu/generico/consulta_inclui_estados.phtml"
						target="frame2">Estados</a></li>
					<li><a href="app/sagu/generico/consulta_cidades.phtml"
						target="frame2">Cidades</a></li>
					<li><a href="app/sagu/generico/configuracao_empresa.phtml"
						target="frame2">Empresas</a></li>
					<li><a href="app/sagu/generico/campus_inclui.phtml" target="frame2">Campus</a></li>
					<li><a href="app/sagu/generico/consulta_inclui_instituicoes.phtml"
						target="frame2">Institui&ccedil;&otilde;es</a></li>
				</ul>
				</li>
			</ul>
			</li>
			<li><a href="#" class="MenuBarItemSubmenu">Matrizes</a>
			<ul>
				<li><a href="app/sagu/academico/consulta_cursos.phtml"
					target="frame2">Cursos</a></li>
				<li><a href="app/sagu/academico/consulta_disciplinas.phtml"
					target="frame2">Disciplinas</a></li>
				<li><a
					href="app/sagu/academico/consulta_inclui_cursos_disciplinas.phtml"
					target="frame2">Cursos / Disciplinas</a></li>
				<li><a href="app/sagu/academico/disciplina_ofer.phtml"
					target="frame2">Disciplinas Oferecidas (Di&aacute;rios)</a></li>
				<li><a href="app/sagu/academico/consulta_inclui_pre_requisito.phtml"
					target="frame2">Pr&eacute;-requisitos</a></li>
				<li><a
					href="app/sagu/academico/consulta_disciplinas_equivalentes.phtml"
					target="frame2">Disciplinas Equivalentes</a></li>
			</ul>
			</li>
			<li><a href="#" class="MenuBarItemSubmenu">Matr&iacute;culas</a>
			<ul>
				<li><a href="app/matricula/matricula_aluno.php" target="frame2">Matr&iacute;cula</a></li>
				<li><a href="app/dispensa_disciplina/dispensa_aluno.php"
					target="frame2">Dispensa de Disciplina</a></li>
				<li><a href="app/matricula/remover_matricula/filtro.php"
					target="frame2">Excluir Matr&iacute;cula</a></li>
			</ul>
			</li>
			<li><a href="app/relatorios/menu.php" title="relatorios"
				target="frame2">Relat&oacute;rios</a></li>
			<li><a href="index.php">Sair</a></li>
		</ul>
		</td>
		<td width="193" class="menu"><span class="texto1"> 
          <img src="images/icons/bola_verde.gif" width="10" height="10" /> <?php echo $LoginUID; ?> </span>
		</td>
	</tr>
</table>

<iframe id='frame2' name='frame2' src='diagrama.php'
	onload='iframeAutoHeight(this)' frameborder='0'></iframe> 
<!--
<object type="text/html" name="frame2" id="frame2"  
data="diagrama.php" border="1" target="frame2"></object>
-->
</div>
<script type="text/javascript">
<!--
    var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"lib/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"lib/SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>