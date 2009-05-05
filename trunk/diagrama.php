<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header("Cache-Control: no-cache");
require ("lib/common.php");
require("configuracao.php");
require("lib/adodb/adodb.inc.php");


//Criando a classe de conexao
$Conexao = NewADOConnection("postgres");
	
//Setando como conexao persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//EXECUTANDO SQL COM ADODB
$Result1 = $Conexao->Execute("SELECT descricao, data FROM avisos WHERE id = 1");
		
$avisos = array();
$avisos[0] = $Result1->fields[0];
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SA</title>
<script type="text/javascript" src="lib/inicio.js"></script>
<script language="javascript" type="">
function fechar(){
    document.getElementById('popup').style.display = 'none';
}
function abrir(){
    document.getElementById('popup').style.display = 'block';
    setTimeout ("fechar()", 36000);
}
//Abre janela de avisos
function avisos() {
	window.open("app/avisos/cadastrar.php",'Avisos','resizable=yes, toolbar=no,width=550,height=350,scrollbars=yes,top=0,left=0');
}

</script>
<style type="text/css">
<!--
-->
</style>
<link href="Styles/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	font-size: 10
}
a {
	text-decoration:underline;
	color:#0033CC;
}
#popup {
	position: absolute;
	top: 10%;
	left: 1px;
	width: 160px;
	padding: 10px 10px 10px 10px;
	border-width: 2px;
	border-style: solid;
	background: #ffffa0;
	display: none;
}
-->
</style>
</head>
<body onload="javascript: abrir()">

<div id="popup">
  <strong>Avisos:</strong><br />
  <br />
  <?php echo $avisos[0]; ?>
  <p><small>
     <a href="javascript: fechar();">Fechar</a>
     <a href="javascript:avisos()">Alterar</a>
  </small></p>
</div>
<br />
<center>
  <table width="650" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="center"><img src="images/diagrama.gif" width="650" height="340" border="0" usemap="#Map" /></div></td>
    </tr>
    
    <tr>
      <td align="center"><br />
        <p><a href="index.php"> Sair do Sistema</a> - <!--<a href="mapa_do_sistema.php">Mapa do Sistema</a> --> <a href="javascript: abrir();">Avisos</a> - <a href="help.php">Ajuda e Documenta&ccedil;&atilde;o</a> </p>
        <p class="texto1 style1"><strong>Sistema Acad&ecirc;mico - revis&atilde;o <?=$versao?></strong><br />
          &copy;2009  <?=$IEnome?><br />
      </p></td>
    </tr>
  </table>
</center>

<map name="Map" id="Map">
  <area shape="rect" coords="406,40,582,58" href="app/matricula/matricula_aluno.php" alt="Matr&iacute;cula individual" /><area shape="rect" coords="406,62,580,82" href="#" />
  <area shape="rect" coords="406,85,557,102" href="app/matricula/remover_matricula/filtro.php" alt="Remover matr&iacute;cula" /><area shape="rect" coords="230,37,348,87" href="app/sagu/academico/consulta_inclui_contratos.phtml" alt="Contratos" />
  <area shape="rect" coords="16,31,158,89" href="app/sagu/academico/consulta_inclui_pessoa.phtml" alt="Pessoa f&iacute;sica" /><area shape="rect" coords="25,149,114,185" href="app/sagu/generico/index.phtml" alt="Cadastros gen&eacute;ricos" />
  <area shape="rect" coords="229,212,323,246" href="app/sagu/academico/consulta_inclui_professores.phtml" alt="professores" />
  <area shape="rect" coords="412,163,567,223" href="app/sagu/academico/disciplina_ofer.phtml" alt="Disciplinas oferecidas" />
  <area shape="rect" coords="425,277,546,325" href="app/sagu/academico/consulta_inclui_cursos_disciplinas.phtml" alt="cursos / disciplinas" />
  <area shape="rect" coords="227,140,323,175" href="app/sagu/academico/coordenadores.phtml" alt="coordenadores" />
<area shape="rect" coords="585,270,648,330" href="app/relatorios/menu.php" alt="Listar relat&oacute;rios" />
</map></body>
</html>
