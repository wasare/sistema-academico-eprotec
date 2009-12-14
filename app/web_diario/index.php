<?php

require_once(dirname(__FILE__) .'/../setup.php');	

unset($_SESSION['conteudo']);
unset($_SESSION['flag_falta']);

$conn = new connection_factory($param_conn);

// RECUPERA INFORMACOES SOBRE DO PROFESSOR E SEUS PERIODOS
if(isset($_GET['periodo_id']) && !empty($_GET['periodo_id']))
{
	$_SESSION['web_diario_periodo_id'] = $_GET['periodo_id'];
}
else
{
	$qry_periodo = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, disciplinas_ofer_prof dp, periodos p WHERE dp.ref_professor = '. $sa_ref_pessoa .' AND o.id = dp.ref_disciplina_ofer AND p.id = o.ref_periodo ORDER BY ref_periodo DESC LIMIT 1;';

	$periodo = $conn->get_row($qry_periodo);

	if(empty($periodo))
	{
		die('Falha ao efetuar a consulta: per&iacute;odo n&atilde;o localizado!');
	}

	$_SESSION['web_diario_periodo_id'] = $periodo['ref_periodo'];
}

// ^ RECUPERA INFORMACOES SOBRE O PROFESSOR E SEUS PERIODOS ^ //


// RECUPERA INFORMACOES SOBRE OS PERIODOS E CURSOS DO COORDENADOR
$sql_coordena = 'SELECT DISTINCT ref_curso
                    FROM coordenadores
                    WHERE
                    ref_professor = '. $sa_ref_pessoa .';';

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

/*
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
*/
?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>
<script type="text/javascript" src="<?=$BASE_URL .'lib/tabbed_pane.js'?>"> </script>


<script language="javascript" type="text/javascript">

function abrir(winName, urlLoc, w, h)
{
   var l, t, jw, jh, myWin;

   jw = screen.width;
   jh = screen.height;

   l = ((screen.availWidth-jw)/2);
   t = ((screen.availHeight-jh)/2);

   features  = "toolbar=no";      // yes|no
   features += ",location=no";    // yes|no
   features += ",directories=no"; // yes|no
   features += ",status=no";  // yes|no
   features += ",menubar=no";     // yes|no
   features += ",scrollbars=yes";   // auto|yes|no
   features += ",resizable=no";   // yes|no
   features += ",dependent";  // close the parent, close the popup, omit if you want otherwise
   features += ",height=" + (h?h:jh);
   features += ",width=" + (w?w:jw);
   features += ",left=" + l;
   features += ",top=" + t;

   winName = winName.replace(/[^a-z]/gi,"_");

	myWin = window.open(urlLoc,winName,features);
	myWin.focus();
}

function concluido(diario_id) {
           
    if (! diario_id == "") {
        if (! confirm('Você deseja marcar / desmarcar como concluído o diário ' + diario_id + '?' + '\n\n Como concluído o diário poderá ser "Finalizado" pela coordenação ficando\n bloqueado para alterações!')) {
            return false;
        } 
        else {
            return true;
        }
    }
}


function setthetab(sentobject)
{
	var speciallink = "index.php#NamedLink";
	var sURL1 = document.location.href;
	var x1 = sURL1.substring(sURL1.length-speciallink.length,sURL1.length);
	if(x1==speciallink)
	{
		thePane.load_page('pane2');
	}
}


function altera_periodo() {

	$('pane1').removeClassName('active');
	$('pane2').addClassName('active');

}


function setOpcao() {

    document.getElementById("acao").options[0].selected;
}

function enviar(action) {

    var undefined;
    var lst = -1;
    var i;
    var lista = document.lista_diarios.diario;

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

            alert("Primeiro selecione um diário!");
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

		$('relatorio_acao').selectedIndex = 0; 
        $('outras_acoes').selectedIndex = 0;

        if (encerrado == 1 && (action == 'notas' || action == 'chamada' || action == 'altera_chamada' || action == 'exclui_chamada' || action == 'marca_diario' )) {

            alert("ERRO! Este diário está finalizado e não pode ser alterado!");
            return false;
        }
    }

	if (action != 0)
	{
        if (action == 'marca_diario') {
			if (concluido(ofer)) 
				abrir("<?=$IEnome?>" + '- web diário', 'requisita.php?do=' + action + '&id=' + ofer);
		}
		else
			abrir("<?=$IEnome?>" + '- web diário', 'requisita.php?do=' + action + '&id=' + ofer);
	}
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
		<li><a href="#" id="pane2">Per&iacute;odos</a></li>
		<?php
            if($is_coordenador === TRUE)
                echo '<li><a href="#" id="pane3">Coordena&ccedil;&atilde;o</a></li>';
        ?>
        <li><a href="#" id="pane4">Ferramentas</a></li>
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


<script language="javascript" type="text/javascript">
var thePane = new TabbedPane('web_guias',
    {
        'pane1': 'lista_diarios.php',
		'pane2': 'lista_periodos.php',
		<?php
            if($is_coordenador === TRUE)
				echo  "'pane3': 'secretaria/lista_diarios_secretaria.php?periodo_id=0901&periodo=&curso_id=107&curso=Superior+de+Tecnologia+em+Inform%E1tica&diario_id=&lista_diarios=Listar+di%E1rios',";
        ?>
		'pane4': 'ferramentas.php',
    },
    {
        onClick: function(e) {
            $('pane_overlay').show();
        },
       
        onSuccess: function(e) {
            $('pane_overlay').hide();
        }
    });

function load_periodos()
{
	$('pane_overlay').show();
	thePane.load_page('pane2');
	$('pane1').removeClassName('active');
    $('pane2').addClassName('active');
	$('pane_overlay').hide();
}

</script>

</body>
</head>
</html>
