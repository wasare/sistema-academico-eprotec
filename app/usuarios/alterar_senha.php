<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);

$RsPessoa = $conn->Execute("SELECT nome FROM pessoas WHERE id = $sa_ref_pessoa");


$msg = '';

if($_POST){
	$senha_atual = $_POST['senha_atual'];
	$sqlSenhaAtual = "SELECT senha FROM usuario WHERE id = $sa_usuario_id  AND senha = '".
				hash('sha256',$senha_atual)."';";
	$RsSenhaAtual = $conn->Execute($sqlSenhaAtual);
	if($RsSenhaAtual->RecordCount() != 1){
		$msg = 'A senha atual n&atilde;o confere!';
	}else{
		$senha = $_POST['senha'];
		$sqlUsuario = "UPDATE usuario SET senha='".hash('sha256',$senha)."' WHERE id = $sa_usuario_id;";
		
		if($conn->Execute($sqlUsuario)){
			$msg = '<font color="green">Senha alterada com sucesso!</font>';
		}else{
			$msg = 'Ocorreu alguma falha ao alterar a senha!'; 
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
		<script>
			function validarSenha(){
				senha1 = document.form1.senha.value;
				senha2 = document.form1.resenha.value;
				if(senha1 == ""){
					alert("O campo senha nao pode ser vazio!");
					return false;
				}
				if (senha1 != senha2){
					alert("As senhas nao conferem!");
					return false;
				}
				return true;
			}
		</script>
    </head>
    <body>
        <h2>Alterar senha do usu&aacute;rio "<?=$sa_usuario?>"</h2>
		<form id="form1" name="form1" method="post" action="alterar_senha.php" onSubmit="return validarSenha()">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="60">
						<div align="center">
                            <label class="bar_menu_texto">
                                <input name="save" 
									type="image" 
									src="../../public/images/icons/save.png" />
								<br />Salvar
							</label>
						</div>
					</td>
                    <td width="60">
						<div align="center">
							<a href="javascript:history.back();" 
								class="bar_menu_texto">
								<img src="../../public/images/icons/back.png" 
									alt="Voltar" 
									width="20" 
									height="20" />
								<br />Voltar
							</a>
						</div>
					</td>
                </tr>
            </table>
			
		<div class="box_geral">
			<strong>Pessoa:</strong><br />
	                <?=$RsPessoa->fields[0]?><br />
			<p>
				<strong>Senha atual:</strong><br />
				<input type="password" name="senha_atual" id="senha_atual" />
			</p>
        	        <strong>Nova senha:</strong><br />
                	<input type="password" name="senha" id="senha" /><br />
			<strong>Digite a senha novamente:</strong><br />
			<input type="password" name="resenha" id="resenha" />
		</div>
		<p>
			<font color="red"><?php echo $msg;?></font>
		</p>
        </form>
    </body>
</html>
