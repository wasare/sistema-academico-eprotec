<?php

header("Cache-Control: no-cache");
require ("../lib/common.php");
require ("../lib/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modelo.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Sistema AcadÃªmico</title>
<!-- InstanceEndEditable -->
<link href="../style.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>
<body>
<div align="center">
  <table width="778" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../appcode/matricula/images/top.jpg" width="778" height="61" /></td>
    </tr>
    <tr>
      <td class="menu">
      <ul id="MenuBar1" class="MenuBarHorizontal">
      <li><a href="../inicio.php">In&iacute;cio</a></li>
      <li><a href="#" class="MenuBarItemSubmenu">*Administrar</a>
        <ul>
          <li><a href="#">Usu&aacute;rios</a></li>
          <li><a href="#" class="MenuBarItemSubmenu">Gen&eacute;ricos</a>
            <ul>
              <li><a href="#">Empresa</a></li>
              <li><a href="#">Pa&iacute;ses</a></li>
              <li><a href="#">Cidades</a></li>
              <li><a href="#">Turnos</a></li>
              <li><a href="#">Elei&ccedil;&otilde;es</a></li>
              <li><a href="#">Campus</a></li>
              <li><a href="#">Estados</a></li>
              <li><a href="#">Cursos Externos</a></li>
              <li><a href="#">Dias</a></li>
              <li><a href="#">Hor&aacute;rios</a></li>
              <li><a href="#">Calend&aacute;rio Acad&ecirc;mico</a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li><a class="MenuBarItemSubmenu" href="#">*Cadastros</a>
        <ul>
          <li><a href="#">&Aacute;rea de Ensino</a></li>
          <li><a href="#">Contratos</a></li>
          <li><a href="#">Coordenadores</a></li>
          <li><a href="#">Departamento</a></li>
          <li><a href="#">Per&iacute;odos</a></li>
          <li><a href="#">Pessoas</a></li>
          <li><a href="#">Professor</a></li>
        </ul>
      </li>
      <li><a href="#" class="MenuBarItemSubmenu">*Curr&iacute;culo</a>
        <ul>
          <li><a href="#">Curso</a></li>
          <li><a href="#">Curso / Disciplina</a></li>
          <li><a href="#">Disciplina</a></li>
          <li><a href="#">Disciplina Oferecida</a></li>
        </ul>
      </li>
      <li><a href="#" class="MenuBarItemSubmenu">*Matr&iacute;culas</a>
        <ul>
          <li><a href="filtro_individual.php">Individual</a></li>
          <li><a href="filtro_lote.php">Por lote</a></li>
          <li><a href="#">Trancar / Cancelar</a></li>
        </ul>
      </li>
      <li><a class="MenuBarItemSubmenu" href="#">Relat&oacute;rios</a>
        <ul>
          <li><a href="../appcode/consultas/pesquisa_alunos.php">Alunos Matriculados</a></li>
          <li><a href="../appcode/consultas/pesquisa_alunos_nao_aprovados.php">Alunos N&atilde;o Aprovados</a></li>
          <li><a href="../appcode/consultas/boletim/index.php" target="_blank">Boletim</a></li>
          <li><a href="../appcode/consultas/lista_cursos.php" target="_blank">Cursos</a></li>
          <li><a href="#">Dados Alunos</a></li>
        </ul>
      </li>
      <li style=""><a href="../index.php">Sair</a></li>
      </ul>
      </td>
    </tr>
    <tr>
      <td height="400" valign="top" class="corpo">
  	  <div align="center">
	    <!-- InstanceBeginEditable name="info" -->
    <form method="post" action="individual.php">
      <table cellspacing="0" cellpadding="0"  class="pesquisa">
        <tr>
          <th colspan="2" align="center">Filtro Matr&iacute;cula Individual</th>
        </tr>
        <tr>
          <td width="109"></td>
          <td width="329"></td>
        </tr>
        <tr>
          <td><div align="left">Semestre:</div></td>
          <td><select name="ano" id="ano">
              <option value="08" selected="selected">2008/1</option>
              <option value="07">2007/1</option>
              <option value="06">2007/2</option>
            </select></td>
        </tr>
        <tr>
          <td><div align="left">Curso:</div></td>
          <td><input name="curso2" id="curso2" size="10" type="text" style="width:60px;" />
            <select name="semestre" id="semestre" style="width:200px;">
              <option value="" selected="selected">Agronomia</option>
              <option value="01">Alimentos</option>
              <option value="02">Zootecnia</option>
            </select></td>
        </tr>
        <tr>
          <td><div align="left">Código do aluno:</div></td>
          <td><input name="curso" id="curso" size="10" type="text" /></td>
        </tr>
        <tr>
          <td width="109"></td>
          <td width="329"></td>
        </tr>
        <tr>
          <td colspan="2"><input name="Submit" value="Matricular" type="submit" />
          </td>
        </tr>
      </table>
    </form>
    <!-- InstanceEndEditable -->
      </div>
      </td>
    </tr>
    <tr>
      <td class="rodape"><img src="../images/user.gif" /> Usu&aacute;rio: <?php echo $_COOKIE["SessionUsuario"]; ?></td>
    </tr>
</table>
</div>
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
<!-- InstanceEnd --></html>
