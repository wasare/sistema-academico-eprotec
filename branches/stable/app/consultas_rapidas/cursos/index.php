<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Pesquisa de Pessoas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../../../Styles/formularios.css" rel="stylesheet" type="text/css">
<link href="../../../Styles/style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../../../lib/prototype.js"></script>
<script language="javascript" src="index.js"></script>
<script language="JavaScript">
<!--
function send(id,descricao){

    window.opener.document.form1.codigo_curso.value=id;
    window.opener.document.form1.descricao_curso.value=descricao;
    self.close();
}
-->
</script>

</head>
<body onLoad="pesquisar();">
<h2>Pesquisa de Curso </h2>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="60"><div align="center"><a href="javascript:window.close();" class="bar_menu_texto"><img src="../../../images/icons/close.png" alt="Voltar" width="24" height="24" /><br />
      Fechar</a></div></td>
  </tr>
</table>
<form id="form1" name="form1" method="post" action="">
  <table width="101%" border="0" cellpadding="0" cellspacing="0" bgcolor="#E6E6E6">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td>Consultar Descri&ccedil;&atilde;o de Curso:</td>
  </tr>
  <tr>
  <td><input name="nome" type="text" id="nome" size="50" onkeyup="pesquisar();"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

  
</form>
<p>
<span id="listagem"></span>
</p>
</body>
</html>
