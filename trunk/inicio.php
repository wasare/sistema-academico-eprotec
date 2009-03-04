<?php

header("Cache-Control: no-cache");
require ("lib/common.php");
require ("lib/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SA</title>
<link href="Styles/style.css" rel="stylesheet" type="text/css" />
<script src="lib/inicio.js" type="text/javascript"></script>
<script src="lib/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="lib/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="favicon.ico" rel="shortcut icon" />
</head>
<body>
<div align="center">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" valign="middle" height="40"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50" valign="middle"><a href="inicio.php"><img src="images/icon_sa.gif" alt="Principal" width="40" height="34" /></a><a href="inicio.php" class="titulo1"></a></td>
            <td width="230"><a href="inicio.php" class="titulo1">Sistema Acad&ecirc;mico</a></td>
            <td valign="top"><div align="right" class="texto1"><strong>Desenvolvimento: </strong></div></td>
            <td width="200" valign="middle">&nbsp;<a href="http://www.cefetbambui.edu.br" target="_blank"><img src="images/ifmg.jpg" alt="CEFET-Bambu&iacute;" title="CEFET-Bambu&iacute;" /></a>&nbsp;&nbsp;<a href="http://cefetbambui.edu.br/gerencia_ti" target="_blank"><img src="images/gti.jpg" alt="Ger&ecirc;ncia TI" title="Ger&ecirc;ncia de TI" width="50" height="34" /></a></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td width="526" class="menu"><ul id="MenuBar1" class="MenuBarHorizontal">
          <li> <a href="inicio.php">In&iacute;cio</a></li>
          <li> <a class="MenuBarItemSubmenu" href="#">Cadastros</a>
            <ul>
              <li><a href="app/sagu/academico/consulta_inclui_pessoa.phtml" target="frame2">Pessoas F&iacute;sicas</a></li>
              <li><a href="app/sagu/academico/consulta_inclui_contratos.phtml" target="frame2">Contratos</a></li>
              <li><a href="app/sagu/academico/consulta_inclui_professores.phtml" target="frame2">Professores</a></li>
              <li><a href="app/sagu/academico/coordenadores.phtml" target="frame2">Coordenadores</a></li>
              <li><a href="#" class="MenuBarItemSubmenu">Gen&eacute;rico</a>
                  <ul>
                    <li><a href="app/sagu/generico/paises_inclui.phtml" target="frame2">Pa&iacute;s</a></li>
                    <li><a href="app/sagu/generico/consulta_inclui_estados.phtml" target="frame2">Estado</a></li>
                    <li><a href="app/sagu/generico/consulta_cidades.phtml" target="frame2">Cidade</a></li>
                    <li><a href="app/sagu/generico/configuracao_empresa.phtml" target="frame2">Empresa</a></li>
                    <li><a href="app/sagu/generico/campus_inclui.phtml" target="frame2">Campus</a></li>
                    <li><a href="app/sagu/generico/consulta_inclui_instituicoes.phtml" target="frame2">Institui&ccedil;&atilde;o</a></li>
                  </ul>
              </li>
              <li><a href="#" class="MenuBarItemSubmenu">Sistema</a>
                  <ul>
                    <li><a href="app/sagu/academico/consulta_periodos.phtml" target="frame2">Per&iacute;odos</a></li>
                    <li><a href="app/sagu/academico/consulta_inclui_departamentos.phtml" target="frame2">Departamentos</a></li>
                    <li><a href="app/sagu/academico/areas_ensino.phtml" target="frame2">&Aacute;rea de ensino</a></li>
                    <li><a href="app/sagu/academico/cadastro_salas.phtml" target="frame2">Salas</a></li>
                    <li><a href="app/sagu/academico/carimbos.phtml" target="frame2">Carimbos</a></li>
                    <!--<li><a href="app/sagu/academico/setores.phtml" target="frame2">Setores</a></li>-->
                    <li><a href="app/sagu/consulta_inclui_usuarios.phtml" target="frame2">Usu&aacute;rios do sistema</a></li>
                    <li><a><hr></a></li>
                    <li><a href="app/acessowebdiario/index.php" target="frame2">Acesso WebDi&aacute;rio</a></li>
                    <li><a href="<?php echo $BASE_URL . 'app/webdiario'; ?>" target="frame2">WebDi&aacute;rio</a></li>
                  </ul>
              </li>
            </ul>
          </li>
          <li><a href="#" class="MenuBarItemSubmenu">Matrizes</a>
            <ul>
                <li><a href="app/sagu/academico/consulta_cursos.phtml" target="frame2">Cursos</a></li>
                <li><a href="app/sagu/academico/consulta_disciplinas.phtml" target="frame2">Disciplinas</a></li>
                <li><a href="app/sagu/academico/consulta_inclui_cursos_disciplinas.phtml" target="frame2">Curso / Disciplinas</a></li>
                <li><a href="app/sagu/academico/disciplina_ofer.phtml" target="frame2">Disciplina Oferecida (Di&aacute;rio)</a></li>
                <li><a href="app/sagu/academico/consulta_inclui_pre_requisito.phtml" target="frame2">Pr&eacute;-requisitos</a></li>
                <li><a href="app/sagu/academico/consulta_disciplinas_equivalentes.phtml" target="frame2">Disciplina Equivalente</a></li>
            </ul>
          </li>
          <li><a href="#" class="MenuBarItemSubmenu">Matr&iacute;culas</a>
            <ul>
              <li><a href="app/matricula/matricula_aluno.php" target="frame2">Matricular</a></li>
              <li><a href="app/dispensa_disciplina/dispensa_aluno.php" target="frame2">Dispensar Disciplina</a></li>
              <li><a href="app/matricula/remover_matricula/filtro.php" target="frame2">Excluir Matr&iacute;cula</a></li>
            </ul>
          </li>
          <li><a href="app/relatorios/menu.php" title="relatorios" target="frame2">Relat&oacute;rios</a>          </li>
          <li><a href="index.php">Sair</a> </li>
        </ul></td>
      <td width="193" class="menu"><span class="texto1"><strong><img src="images/icons/bola_verde.gif" width="10" height="10" /></strong><?php echo $_COOKIE["SessionUsuario"]; ?></span></td>
    </tr>
    <tr>
      <td height="480" colspan="2" valign="top" class="corpo">

      	<!-- <object type="text/html" name="frame2" id="frame2" onload="iframeAutoHeight(this)" data="atalhos.php"></object> -->
        <iframe id='frame2' name='frame2' src='diagrama.php' onload='iframeAutoHeight(this)' frameborder='0'></iframe>

      </td>
    </tr>
  </table>
</div>
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"lib/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"lib/SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
