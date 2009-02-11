<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");


$login = $_POST["login"];
$senha = $_POST["senha"];
$nome_completo = $_POST["nome_completo"];
$nivel = $_POST["nivel"];
$codigo_pessoa = $_POST["codigo_pessoa"];
$ativo = $_POST["ativar"];

if ($ativo == true){

	$ativo = true;
}
else{

	$ativo = false;
}

//echo $login . $senha . $nome_completo . $nivel . $codigo_pessoa . $ativo;
//die();


$sql = "INSERT INTO  public.diario_usuarios(login,senha,nome_completo,nivel,id_nome,ativo) 
VALUES('$login',md5('$senha'),'$nome_completo','$nivel','$codigo_pessoa','$ativo')";

//echo $sql;
//die();



//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");



//Confere se o registro existe
$sql2 = "SELECT id_nome FROM public.diario_usuarios WHERE id_nome = $codigo_pessoa";

$RsConfPessoa = $Conexao->Execute($sql2);

$NumPessoa = $RsConfPessoa->RecordCount();



if($NumPessoa > 0){
	
	$msg = "<p class=\"style2\">Erro: não foi possível cadastrar.<br>Usuário já cadastrado!</p>";
}
else {
	
	//Exibindo a descricao do curso caso setado
	$Result1 = $Conexao->Execute($sql);
	
	if ($Result1) {
	
		$msg = "<p class=\"style3\">Cadastro realizado com sucesso!</p>";
	}
	else {
	
		$msg = "<p class=\"style2\">Erro ao realizar cadastro!</p>";
	}
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
<h2>Cadastrar novo acesso ao Web Diário</h2>
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
