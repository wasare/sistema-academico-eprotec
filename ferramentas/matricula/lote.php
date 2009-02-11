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
<title>Sistema Acadêmico</title>
<!-- InstanceEndEditable -->
<link href="../style.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<script language="JavaScript" src="../lib/selectbox.js"></script>
<!-- InstanceEndEditable -->
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
	    <!-- InstanceBeginEditable name="info" --><form>
<table border="0" cellpadding="0" cellspacing="0" class="pesquisa">
<tbody>
  <tr>
    <th colspan="3">Matrícula por Lote</th>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><strong>Curso:</strong> Técnico em Informática Integrado ao Ensino Médio<br />
      <strong>Semestre:</strong> 1/2008<br /><hr />    </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Disciplinas:</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td>Turma/Disciplina(s):</td>
  </tr>
  <tr>
    <td><select name="list3" style="width:250px" multiple="multiple" size="10" ondblclick="moveSelectedOptions(this.form['list1'],this.form['list2'],true,this.form['movepattern1'].value)">
      <option value="1">Portugues</option>
      <option value="2">Matemática</option>
      <option value="3">História</option>
      <option value="4">Geografia</option>
      <option value="5">Química</option>
      <option value="6">Física</option>
      <option value="7">Educação Física</option>
      <option value="8">Ensino Religioso</option>
                    </select></td>
    <td align="center" valign="middle"><input name="right2" value="&gt;" onclick="moveSelectedOptions(this.form['list3'],this.form['list4'],true,this.form['movepattern1'].value)" type="button" />
      <br />
      <br />
      <input name="right2" value="&gt;&gt;" onclick="moveAllOptions(this.form['list3'],this.form['list4'],true,this.form['movepattern1'].value)" type="button" />
      <br />
      <br />
      <input name="left2" value="&lt;" onclick="moveSelectedOptions(this.form['list4'],this.form['list3'],true,this['form'].movepattern1.value)" type="button" />
      <br />
      <br />
      <input name="left2" value="&lt;&lt;" onclick="moveAllOptions(this.form['list4'],this.form['list3'],true,this.form['movepattern1'].value)" type="button" /></td>
    <td><select name="list4" style="width:250px" multiple="multiple" size="10" ondblclick="moveSelectedOptions(this.form['list4'],this.form['list3'],true,this.form['movepattern1'].value)">
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Aluno:</td>
    <td align="center" valign="middle">&nbsp;</td>
    <td>Turma/Aluno(s):</td>
  </tr>
  <tr>
	<td>
	<select name="list1" style="width:250px" multiple="multiple" size="10" ondblclick="moveSelectedOptions(this.form['list1'],this.form['list2'],true,this.form['movepattern1'].value)">
	  <option value="1">Ciniro Aparecido Leite Nametala</option>
	  <option value="2">Wanderson Santiago dos Reis</option>
	  <option value="3">Manoel Pereira Júnior</option>
	  <option value="4">Santiago Silva Pereira</option>
	  <option value="5">Christiane dos Santos Pereira</option>
	  <option value="6">Aline Martins Chaves</option>
	  <option value="7">Fernando Paim Lima</option>
	</select>	</td>
	<td align="center" valign="middle">
		<input name="right" value="&gt;" onclick="moveSelectedOptions(this.form['list1'],this.form['list2'],true,this.form['movepattern1'].value)" type="button"/><br/><br/>
		<input name="right" value="&gt;&gt;" onclick="moveAllOptions(this.form['list1'],this.form['list2'],true,this.form['movepattern1'].value)" type="button"/><br/><br/>
		<input name="left" value="&lt;" onclick="moveSelectedOptions(this.form['list2'],this.form['list1'],true,this['form'].movepattern1.value)" type="button"/><br/><br/>
		<input name="left" value="&lt;&lt;" onclick="moveAllOptions(this.form['list2'],this.form['list1'],true,this.form['movepattern1'].value)" type="button"/>	</td>
	<td>
	<select name="list2" style="width:250px" multiple="multiple" size="10" ondblclick="moveSelectedOptions(this.form['list2'],this.form['list1'],true,this.form['movepattern1'].value)">
	</select>	</td>
</tr>
<tr>
	<td colspan="3"><div align="center">
	  <input name="movepattern1" value="" type="hidden"/>	  
	  <input type="submit" name="salvar" style="width:100px;" id="salvar" value="  Salvar  " />&nbsp;
      <input type="button" name="cancelar" style="width:100px;" id="cancelar" value="Cancelar" />
        </div>	</td>
</tr>
<tr>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td colspan="3">&nbsp;</td>
</tr>
</tbody></table>
</form><!-- InstanceEndEditable -->
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
