<?php

require_once("../setup.php");

$conn    = new connection_factory($param_conn);

$sqlPapeis = iconv(
		'utf-8',
		'iso-8859-1',
		'SELECT papel_id, nome FROM papel ORDER BY nome'
	     );

$RsPapeis = $conn->Execute($sqlPapeis);

$sqlSetor = iconv(
		'utf-8',
		'iso-8859-1',
		'SELECT id, nome_setor FROM setor ORDER BY nome_setor'
	     );

$RsSetor = $conn->Execute($sqlSetor);

?>
<html>
    <head>
        <title>SA</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Cadastro de usu&aacute;rio</h2>
        <div class="box_geral">
            <form method="post" action="cadastrar_action.php">
                C&oacute;digo da pessoa:<br />
                <input type="text" name="ref_pessoa" id="codigo_pessoa" size="8" />
                <input type="text" name="nome_pessoa" id="nome_pessoa" size="20" disabled />
                <a>Buscar</a>
                <br />
                Permiss&atilde;o:
                <br />
                <select name="papeis" id="papeis" multiple="multiple" size="4">
        	<?php 

		while(!$RsPapeis->EOF) { 
		
		?>
	        	<option value="<?=$RsPapeis->fields[0]?>"><?=$RsPapeis->fields[1]?></option>
            	<?php  
			$RsPapeis->MoveNext(); 
		} 
		?>                    
                </select>
                <br />
                Setor:
                <br />
                <select name="setor" id="setor">
		<?php 

		while(!$RsSetor->EOF) { 
		
		?>
	        	<option value="<?=$RsSetor->fields[0]?>"><?=$RsSetor->fields[1]?></option>
            	<?php  
			$RsSetor->MoveNext(); 
		} 
		?> 
                </select>
                <br />
                <br />
                Usu�rio:
                <br />
                <input type="text" name="nome" id="nome" />
                <br />
                Senha:
                <br />
                <input type="password" name="senha1" id="senha1" />
                <br />
                Digite a senha novamente:
                <br />
                <input type="password" name="senha2" id="senha2" />
                <br />
                <br />
                <br />
                <input type="submit" value="Confirmar" />
            </form>
        </div>
    </body>
</html>
