<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");



//Criando a classe de conex�o ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conex�o persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


$codigo_pessoa = $_GET["id_pessoa"];

$sql = "DELETE FROM public.diario_usuarios WHERE id_nome = '$codigo_pessoa';";


//Exibindo a descricao do curso caso setado
$Result1 = $Conexao->Execute($sql);
	
if ($Result1) {
	
	$msg = "<p class=\"style3\">Exclus�o realizada com sucesso!</p>";
}
else {
	
	$msg = "<p class=\"style2\">Erro ao realizar exclus�o!</p>";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link href="../../css/formularios.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style2 {
	color: #FF0000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
	}

.style3 {
	color: #006633;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
	}

-->
</style>
</head>

<body>
<h2>Excluir acesso ao Web Di�rio</h2>
<?php echo $msg; ?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60"><div align="center"><a href="javascript:history.back();" class="bar_menu_texto"><img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
      Voltar</a></div></td>
  </tr>
</table>
<p class="style2">&nbsp;</p>
</body>

</html>