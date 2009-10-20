<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);

$id_usuario = $_GET["id_usuario"];

$sqlUsuario = '
SELECT
    u.id,
    u.nome,
    u.ativado,
	u.ref_pessoa,
	p.nome,
    s.nome_setor
FROM
    usuario u, setor s, pessoas p
WHERE
	s.id = u.ref_setor AND
	u.ref_pessoa = p.id AND
	u.id = '.$id_usuario;

$RsUsuario = $conn->Execute($sqlUsuario);

$sqlPapelUsuario = 'SELECT ref_papel FROM usuario_papel WHERE ref_usuario = '.$id_usuario.'; ';
$RsPapelUsuario = $conn->Execute($sqlPapelUsuario);

$RsPapel = $conn->Execute('SELECT papel_id, descricao, nome FROM papel');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Untitled Document</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <form id="form1" name="form1" method="post" action="alterar_action.php">
            <h2>Alterar usu&aacute;rio</h2>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="60">
						<div align="center">
                            <label class="bar_menu_texto">
                                <input name="save" type="image" src="../../public/images/icons/save.png" />
                                <br />
                                Salvar</label></td>
                    <td width="60">
						<div align="center">
							<a href="javascript:history.back();" 
								class="bar_menu_texto">
								<img src="../../public/images/icons/back.png" 
										alt="Voltar" 
										width="20" 
										height="20" />
								<br />
                                Voltar</a>
						</div>
					</td>
                </tr>
            </table>
            <div class="box_geral">
				<p>
                	<strong>Usu&aacute;rio:</strong>
                	<?php echo $RsUsuario->fields[1]; ?>
				</p>
                <strong>Senha:</strong><br />
                <input type="password" name="senha" id="senha" /><br />
				<strong>Digite a senha novamente:</strong><br />
				<input type="password" name="resenha" id="resenha" />
				<p>
    	            <strong>Permiss&atilde;o:</strong><br />
    	            <select name="permissao" id="permissao" multiple="multiple" size="4">
    	            	<?php

							while(!$RsPapelUsuario->EOF){	
								$papelUsuario[] = $RsPapelUsuario->fields[0];
								$RsPapelUsuario->MoveNext();
							}

							while(!$RsPapel->EOF){
								echo '<option value="'.$RsPapel->fields[0].'" '; 	
								if(in_array($RsPapel->fields[0], $papelUsuario)){							
									echo ' checked="checked" ';
								}
								echo ">";
								echo $RsPapel->fields[2]."</option>";								
								$RsPapel->MoveNext();
							}
		                ?>
	                </select>
				</p>
				<strong>Pessoa:</strong><br />
                <?=$RsUsuario->fields[3]?> - <?=$RsUsuario->fields[4]?>
				<p>
	                <?php
	                    if ($RsUsuario->fields[2] == 't') {
	                        echo '<input type="checkbox" checked="checked" name="ativar" id="ativar" />';
	                    }
	                	else {
	                		echo '<input type="checkbox" name="ativar" id="ativar" />';
	                	}
	                ?>
					Ativar/Desativar usu&aacute;rio.
				</p>
            </div>
        </form>
    </body>
</html>
