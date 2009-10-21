<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);

$RsSetor = $conn->Execute('SELECT id, nome_setor FROM setor;');
$RsPapel = $conn->Execute('SELECT papel_id, descricao, nome FROM papel');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
		<script>
			function validarSenha(){
				if(document.form1.senha_atual.checked == 1){
					return true;	
				}else{
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
			}
		</script>
    </head>
    <body>
        <h2>Cadastrar usu&aacute;rio</h2>
        
		<form id="form1" name="form1" method="post" action="cadastrar_action.php" onSubmit="return validarSenha()">
			
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
                <input name="id_pessoa" id="id_pessoa" type="text" maxlenght="8" size="8" value="" /> <br />
				<strong>Setor:</strong><br />
				<select name="setor" id="setor">
					<?php 
						while(!$RsSetor->EOF){
							echo '<option value="'.$RsSetor->fields[0].'" >';
							echo $RsSetor->fields[1]."</option>";							
							$RsSetor->MoveNext();
						}
		            ?>
				</select>
				<p>
                	<strong>Usu&aacute;rio:</strong><br />
                	<input type="text" name="usuario" id="usuario" />
				</p>
                <strong>Senha:</strong><br />
                <input type="password" name="senha" id="senha" /><br />
				<strong>Digite a senha novamente:</strong><br />
				<input type="password" name="resenha" id="resenha" />
				<p>
    	            <strong>Permiss&otilde;es:</strong><br />
    	            <select name="permissao[]" id="permissao[]" multiple="multiple" size="4">
    	            	<?php
							while(!$RsPapel->EOF){
								echo '<option value="'.$RsPapel->fields[0].'" >'; 	
								echo $RsPapel->fields[2]."</option>";							
								$RsPapel->MoveNext();
							}
		                ?>
	                </select>
				</p>
				<p>
					Usu&aacute;rio ativado? 
	                <input type="checkbox" checked="checked" name="ativado" id="ativado" />
					<span class="comentario">Marcado para sim.</span>
				</p>
            </div>
        </form>
    </body>
</html>
