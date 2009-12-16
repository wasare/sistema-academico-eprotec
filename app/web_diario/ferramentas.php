<?php

require_once('../../app/setup.php');	

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);


$conn = new connection_factory($param_conn);


	if (isset($_GET['id']) AND $_GET['acao'] === "10")
    {
		
		
        echo '<script language="javascript"> 
		
	      	function jsConcluido(id)
			{
   				if (! id == "") {
    				if (! confirm(\'Você deseja marcar/desmarcar como concluído o diário \' + id + \'?\' + \'\n Como concluído o diário poderá ser "Fechado" pela coordenação ficando\n bloqueado para alterações!\'))      
					{
                        javascript:window.history.back(1);                     
         				return false;
      				} 
					else {
         				self.location = "movimentos/marca_concluido.php?ofer=" + id;
         				return true;
      				}
   				}
   				else {
					javascript:window.history.back(1);
					return false;
				}
			}
					
			jsConcluido('.$diario['1'].');</script>';
		exit;

    }

?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>
<script type="text/javascript" src="<?=$BASE_URL .'lib/tabbed_pane.js'?>"> </script>

</head>

<body bgcolor="#FFFFFF" text="#000000" >
<div align="center">

<br />
		
<div class="tabbed-pane" align="center">
    <ol class="guias">
        <li><a href="#" id="ferramentas_pane1">Consultar alunos</a></li>
	    <li><a href="#" style="background-color: #ffe566;" id="trocar_senha" onclick="abrir('<?=$IEnome?>' + '- web diário', 'requisita.php?do=troca_senha');">Trocar senha</a></li>
        <li><a href="#" id="ferramentas_pane2">Trocar senha</a></li>
		<li><a href="#" class="active" id="ferramentas_pane3">Log de acesso</a></li>
		<li><a href="#" id="ferramentas_pane4">Programas</a></li>
    </ol>
   
    <div id="pane_container_ferramentas" class="tabbed-container">
        <div id="pane_overlay_ferramentas" class="overlay" style="display: none">
            <h2> <img src="<?=$BASE_URL .'public/images/carregando.gif'?>" /> &nbsp;&nbsp; carregando&#8230; </h2>
        </div>
        <div id="web_guias_ferramentas" class="pane"></div>
    </div>
</div>

</div>

<script type="text/javascript">
new TabbedPane('web_guias_ferramentas',
    {
        'ferramentas_pane1': 'consultas/alunos.php',
        'ferramentas_pane2': '<?=$BASE_URL ."app/usuarios/alterar_senha.php"?>',
		'ferramentas_pane3': 'consultas/log_acesso.php',
		'ferramentas_pane4': 'programas.php'
    },
    {
        onClick: function(e) {
            $('pane_overlay_ferramentas').show();
        },
       
        onSuccess: function(e) {
            $('pane_overlay_ferramentas').hide();
        }
    });
</script>

</body>
</head>
</html>
