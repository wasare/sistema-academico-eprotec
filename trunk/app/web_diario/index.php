<?php

require_once('../../app/setup.php');	

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

unset($_SESSION['conteudo']);
unset($_SESSION['flag_falta']);

$conn = new connection_factory($param_conn);

// RECUPERA INFORMACOES SOBRE DO PROFESSOR E SEUS PERIODOS
$qry_professor = 'SELECT ref_pessoa FROM usuario WHERE nome = \''. $uid .'\';';

$pessoa_id = $conn->adodb->getOne($qry_professor);

if($pessoa_id === FALSE || !is_numeric($pessoa_id))
{
    die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}


$qryPeriodos = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, disciplinas_ofer_prof dp, periodos p WHERE dp.ref_professor = '. $pessoa_id .' AND o.id = dp.ref_disciplina_ofer AND p.id = o.ref_periodo ORDER BY ref_periodo DESC;';

$periodos = $conn->adodb->getAll($qryPeriodos);

if($periodos === FALSE || !is_array($periodos))
{
    die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}

list($periodo_id,$periodo_descricao) = $periodos[0];

$_SESSION['web_diario_periodos'] = $periodos;
$_SESSION['web_diario_periodo_id'] = $periodo_id;
$_SESSION['web_diario_ref_pessoa'] = $pessoa_id;

// ^ RECUPERA INFORMACOES SOBRE O PROFESSOR E SEUS PERIODOS ^ //


// RECUPERA INFORMACOES SOBRE OS PERIODOS E CURSOS DO COORDENADOR
$sql_coordena = 'SELECT DISTINCT ref_curso
                    FROM coordenadores
                    WHERE
                    ref_professor = '. $pessoa_id .';';

$cursos_coordenacao = $conn->adodb->getCol($sql_coordena);

if($cursos_coordenacao === FALSE)
{
	die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}

$is_coordenador = FALSE;

if(count($cursos_coordenacao) > 0)
{
	$is_coordenador = TRUE;
	$_SESSION['web_diario_cursos_coordenacao'] = $cursos_coordenacao;
}

// ^ RECUPERA INFORMACOES SOBRE OS PERIODOS E CURSOS DO COORDENADOR ^ //


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

<script language="javascript" type="text/javascript">

function setOpcao() {

    document.getElementById("acao").options[0].selected;
}

function enviar(id) {

    var undefined;
    var lst = -1;
    var i;
    var lista = document.change_acao.diario;

    if(lista.length == undefined) {

        if(lista.checked)
            lst = 0;
    }
    else {
		for (i = 0 ; i < lista.length; i++) {
            if( lista[i].checked ) {
                lst = i;
                i = -1;
                break;
            }
        }

    }

    if (lst == -1) {

            alert("ERRO! Primeiro selecione um diário!");
            return false;
    }
    else {

        if(lst == 0 && i != -1) {
            diarios = lista.value;
        }
        else {
            diarios = lista[lst].value;
        }
		
		 diario  = diarios.split("|");

        var disc = diario[0];
        var ofer = diario[1];
        var encerrado = diario[2];

        if (encerrado == 1 && (id == 0 || id ==1)) {

            alert("ERRO! Este diário está finalizado e não pode ser alterado!");
            return false;

        }

    }
       
    var vars = document.getElementById('vars').value + "&disc=" + disc + "&ofer=" + ofer;

    if(id == 0)
        self.location = "movimentos/chamada/chamadas.php?" + vars;
    
    if(id == 1)
        self.location = "movimentos/lancanotas/lancanotas1.php?" + vars;
        
    if(id == 5)
        self.location = "consultas/papeleta.php?" + vars;
        
    if(id == 6)
        self.location = "consultas/conteudo_aula.php?" + vars;
}
</script>

</head>

<body bgcolor="#FFFFFF" text="#000000" >
<div align="center">

<table border="0" cellspacing="0" cellpadding="0" class="papeleta">
    <tr>
	<td>
    <img src="<?=$BASE_URL .'public/images/sa_icon.png'?>" alt="Sistema Acad&ecirc;mico - Web Di&aacute;rio" title="Sistema Acad&ecirc;mico - Web Di&aacute;rio" />
	&nbsp;&nbsp;&nbsp;&nbsp;
	<font style="font-size: 2.2em; font-weight: bold;">Sistema Acad&ecirc;mico - Web Di&aacute;rio</font>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img src="<?=$BASE_URL .'public/images/ifmg.jpg'?>" alt="IFMG - Campus Bambu&iacute;" title="IFMG - Campus Bambu&iacute;" />
    &nbsp;&nbsp;
    <img src="<?=$BASE_URL .'public/images/gti.jpg'?>" alt="Ger&ecirc;ncia TI" title="Ger&ecirc;ncia de TI" width="50" height="34" />
    <?php
		if ($_SERVER['HTTP_HOST'] == 'devv.cefetbambui.edu.br' || $host != '192.168.0.234')
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Servidor de BD: </strong>'. $host;
    ?>
    </td>
    </tr>
</table>

<br /><br />

<div class="tabbed-pane" align="center">
    <ol class="tabs">
        <li><a href="#" class="active" id="pane1">Meus di&aacute;rios</a></li>
		<?php
            if($is_coordenador === TRUE)
                echo '<li><a href="#" id="pane2">Coordena&ccedil;&atilde;o</a></li>';
        ?>
        <li><a href="#" id="pane3">Ferramentas</a></li>
		<li><a href="<?=$BASE_URL .'index.php'?>" style="background-color: #ffe566;">Sair</a></li>
    </ol>
   
    <div id="pane_container" class="tabbed-container">
        <div id="pane_overlay" class="overlay" style="display: none">
            <h2> <img src="<?=$BASE_URL .'public/images/carregando.gif'?>" /> &nbsp;&nbsp; carregando&#8230; </h2>
        </div>
        <div id="web_guias" class="pane"></div>
    </div>
</div>

</div>


<script type="text/javascript">
new TabbedPane('web_guias',
    {
        'pane1': 'lista_diarios.php',
		<?php
            if($is_coordenador === TRUE)
				echo  "'pane2': 'index-manutencao.htm',";
        ?>
		'pane3': 'ferramentas.php',
    },
    {
        onClick: function(e) {
            $('pane_overlay').show();
        },
       
        onSuccess: function(e) {
            $('pane_overlay').hide();
        }
    });
</script>

</body>
</head>
</html>
