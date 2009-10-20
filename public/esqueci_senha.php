<?php

if($_POST){

	if(!empty($_POST['user'])){

		require_once('../config/configuracao.php');
		require_once($BASE_DIR .'core/data/connection_factory.php');
	
		$conn = new connection_factory($param_conn);
	
		$sqlUsuario = " SELECT 
				u.id,u.nome,p.email 
				FROM usuario u, pessoas p
				WHERE 
					u.ref_pessoa = p.id AND	
					u.nome = '".$_POST['user']."'; ";
	
		$RsUsuario  = $conn->Execute(iconv("utf-8","iso-8859-1",$sqlUsuario));
	
		if($RsUsuario->RecordCount() === 1)
		{
			$nova_senha = rand(10000000,99999999);
			
			$sqlUpdateUsuario = "UPDATE usuario 
					     SET senha = '".hash('sha256',$nova_senha)."'  
					     WHERE nome = '".$_POST['user']."'; ";
	
			if($conn->Execute($sqlUpdateUsuario))
			{
				$message = 'Nova senha para acessar o SA: '.$nova_senha;
					
				if(mail($RsUsuario->fields[2], 'SA - Envio de senha', $message, 'From: SA')){
					$msg = '<font color=green>Procedimento efetuado com sucesso! 
						Acesse a sua conta de email para ter acesso a nova senha.</font>';
				}else{
					$msg = 'Erro ao enviar email!';
				}
			}else{
				$msg = 'Erro ao atualizar nova senha!';
			}
		}else{
			$msg = 'Usu&aacute;rio n&atilde;o cadastrado! Procure a secretaria do campus.';
		}
	}else{
		$msg = 'O campo <i>Nome do usu&aacute;rio</i> deve ser preenchido!';
	}
}

?>
<html>
<head>
	<title>SA</title>
	<link href="styles/formularios.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<center>
	<img src="images/sa_icon.png" alt="logomarca SA" width="80" height="68" style="margin: 10px;" />
	<h2>Esqueceu a sua senha?</h2>
	Digite o seu nome de usu&aacute;rio do SA para iniciar o processo de recupera&ccedil;&atilde;o da senha. <br />
	O sistema enviar&aacute; uma nova senha para o seu email cadastrado. 
	Caso n&atilde;o tenha email cadastrado ou <br />o email foi alterado procure a secretaria do campus.
	<form method="post" action="esqueci_senha.php">
		<p>
			Nome do usu&aacute;rio:<br />
			<input type="text" name="user" id="user" />
			<br />
			<input type="submit" value="Enviar" />
		</p>
		<font color="red"><?=$msg?></font>
	</form>
	<p>
		<font color="#999999">&copy;2009 IFMG Campus Bambu&iacute; -</font> 
		<a href="../index.php">P&aacute;gina inicial do SA.</a>
	</p>
	</center>
</body>
</html>
