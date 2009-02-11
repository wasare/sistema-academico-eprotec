<?php
/*
function Gera_Senha(){
	$numero_grande=mt_rand(9999,99999);

	if($numero_grande<0)
		$numero_grande*=(-1);
	
	$num_insc=$numero_grande;

	return $num_insc;
}


///converte o e-mail digitado para minusculas
$mailconsulta = strtolower($mailconsulta);

if ($mailconsulta =='') {
    print '<script language=javascript>
    window.alert("Não Foi digitado o e-mail !");
    javascript:window.history.back(1);
    </script>';
    break;
} else {


	$sql = "select
         a.login,
         a.senha,
         b.id,
         a.nome_completo,
         b.email
         from diario_usuarios a, pessoas b
         where
         a.id_nome = b.id AND
         b.email = '$mailconsulta';"; //  echo $sql; die;



    $rs = pg_query($dbconnect, $sql);

    $numrows = pg_numrows($rs);
//    echo $sql; die;
    if($numrows == 1 ) {

            $con1 = pg_exec($dbconnect, $sql);

      		while ($linha = pg_fetch_array($con1)) {
                   $endereco = $linha["email"];
                   $nomec = $linha["nome_completo"];
                   $user = $linha["login"];
                   $pass = $linha["senha"];
                   $id = $linha["id"];
      		}

			$endereco = strtolower($endereco);

			$num_rand = Gera_Senha();
	
			$passwnew1 = md5($num_rand);

			$sql1 = "UPDATE diario_usuarios SET senha = '$passwnew1' WHERE id_nome = $id;";
	
			$query1 =  pg_exec($dbconnect, $sql1);

			$msg_assunto = "Usuario e Senha Web Diario - CEFET-BAMBUI";

			$msg_corpo = "Caro usuario " . $nomec . " ,a seu pedido sua senha do Web Diario foi enviada, segue abaixo seu usuario e sua nova senha provisoria:\n\n";
        	$msg_corpo .= "Usuario: " . $user . "\n";
			$msg_corpo .= "Senha: " . $num_rand . "\n\n";
			$msg_corpo .= "Para sua seguranca altere esta senha ao acessar o sistema!"."\n\n";
			$msg_corpo .= "Atenciosamente, " . "\nWeb Diario CEFET-BAMBUI\n\n\n";
			$msg_corpo .= "OBS: Esta eh uma mensagem automatica e nao deve ser respondida, qualquer duvida envie e-mail para wanderson@cefetbambui.edu.br\n\n\n";
			$msg_corpo .= "MENSAGEM PROPOSITALMENTE SEM ACENTOS.";

			echo '<html>
					<head>
						<title>CONFIRMACAO</title>
						<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
					</head>

					<body>';
	
		        	// EMAIL COM A NOVA SESSAO	
				if ( @mail($mailconsulta, $msg_assunto, $msg_corpo, "FROM: diario@cefetbambui.edu.br") ) {

					echo 	'<p>Sr.(a) <strong>'.$nomec.'</strong> a sua senha foi enviada para o e-mail <strong>' . $mailconsulta . '</strong></p>
					<p>&nbsp;</p>
					<p><a href="../index.php">P&aacute;gina Inicial</a></p>';
				}
				else  {
					echo '	<p>Ocorreu um erro ao enviar a nova senha!</p>
						<p>&nbsp;</p>
						<p><a href="../index.php">P&aacute;gina Inicial</a></p>';
				}

					echo '</body>
					</html>';
	
	} else {
               print '<script language=javascript>
               window.alert("O e-mail não foi encontrado em nossa base de dados !");
               javascript:window.history.back(1);
               </script>';
               break;
	}

}
*/
?>

<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");


$codigo_pessoa = $_POST["codigo_pessoa"];
$senha = $_POST["senha"];
$nivel = $_POST["nivel"];

$manter_senha = $_POST["manter_senha"];


$ativo = $_POST["ativar"];
if ($ativo == true){

	$ativo = 't';
}
else{

	$ativo = 'f';
}

//echo $senha . $nivel . $ativo;
//die();
if($manter_senha == true ){

	$sql = "UPDATE public.diario_usuarios 
		SET nivel = '$nivel', ativo = '$ativo' 
		WHERE id_nome = '$codigo_pessoa';";
}
else {

	$sql = "UPDATE public.diario_usuarios 
		SET senha = md5('$senha'), nivel = '$nivel', ativo = '$ativo' 
		WHERE id_nome = '$codigo_pessoa';";
}


//echo $sql;
//die();



//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Exibindo a descricao do curso caso setado
$Result1 = $Conexao->Execute($sql);
	
//Se executado com sucesso
if ($Result1) {
	
	$msg = "<p class=\"style3\">Alteração realizada com sucesso!</p>";
}
else {
	
	$msg = "<p class=\"style2\">Erro ao realizar alteração!</p>";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SA</title>
<link href="../../Styles/formularios.css" rel="stylesheet" type="text/css" />
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
<h2>Alterar acesso ao Web Diário</h2>
<?php echo $msg; ?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60"><div align="center"><a href="index.php" class="bar_menu_texto"><img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
      Voltar</a></div></td>
  </tr>
</table>
<p class="style2">&nbsp;</p>
</body>

</html>
