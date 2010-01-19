<?php

require_once(dirname(__FILE__) .'/../setup.php');	

$conn = new connection_factory($param_conn);

unset($_SESSION['conteudo']);
unset($_SESSION['flag_falta']);

$is_coordenador = FALSE;
$is_professor = FALSE;

// RECUPERA INFORMACOES SOBRE OS PERIODOS DO PROFESSOR
$qry_periodo = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, disciplinas_ofer_prof dp, periodos p WHERE dp.ref_professor = '. $sa_ref_pessoa .' AND o.id = dp.ref_disciplina_ofer AND p.id = o.ref_periodo ORDER BY ref_periodo DESC LIMIT 1;';

$periodo = $conn->get_row($qry_periodo);

if(!empty($periodo))
{
	$_SESSION['web_diario_periodo_id'] = isset($_SESSION['web_diario_periodo_id']) ? $_SESSION['web_diario_periodo_id'] : $periodo['ref_periodo'];
	$is_professor = TRUE;
}
// ^ RECUPERA INFORMACOES SOBRE OS PERIODOS DO PROFESSOR ^ //


// RECUPERA INFORMACOES SOBRE OS PERIODOS DO COORDENADOR
$qry_periodos = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, periodos p WHERE  o.ref_periodo = p.id AND o.ref_curso IN (SELECT DISTINCT ref_curso FROM coordenador WHERE ref_professor = '. $sa_ref_pessoa .') ORDER BY ref_periodo DESC LIMIT 1;';

$periodo = $conn->get_row($qry_periodo);

if(!empty($periodo))
{
    $_SESSION['web_diario_periodo_coordena_id'] = isset($_SESSION['web_diario_periodo_coordena_id']) ? $_SESSION['web_diario_periodo_coordena_id'] : $periodo['ref_periodo'];
	$is_coordenador = TRUE;
}

if ($is_coordenador === TRUE) {
	
	// ^ RECUPERA INFORMACOES SOBRE OS PERIODOS DO COORDENADOR ^ //


	// RECUPERA INFORMACOES SOBRE OS CURSOS DO COORDENADOR
	$sql_coordena = 'SELECT DISTINCT ref_curso
                    FROM coordenador
                    WHERE
                    ref_professor = '. $sa_ref_pessoa .';';

	$cursos_coordenacao = $conn->get_col($sql_coordena);

	if(count($cursos_coordenacao) > 0)
	{
		$is_coordenador = TRUE;
		$_SESSION['web_diario_cursos_coordenacao'] = $cursos_coordenacao;	
	}

	// ^ RECUPERA INFORMACOES SOBRE OS PERIODOS E CURSOS DO COORDENADOR ^ //
}

$class_periodos = $class_coordenacao = '';

if (!$is_professor && $is_coordenador)
    $class_coordenacao = ' class="active"';
else
	$class_diarios = ' class="active"';

?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>
<script type="text/javascript" src="<?=$BASE_URL .'lib/tabbed_pane.js'?>"> </script>
<script type="text/javascript" src="<?=$BASE_URL .'app/web_diario/web_diario.js'?>"> </script>

</head>

<body bgcolor="#FFFFFF" text="#000000">

<div align="center">

<table border="0" cellspacing="0" cellpadding="0" class="papeleta">
    <tr>
	<th>
    <img src="<?=$BASE_URL .'public/images/sa_icon.png'?>" alt="Sistema Acad&ecirc;mico - Web Di&aacute;rio" title="Sistema Acad&ecirc;mico - Web Di&aacute;rio" />
	&nbsp;&nbsp;&nbsp;&nbsp;
	<font style="font-size: 2.2em; font-weight: bold;">Sistema Acad&ecirc;mico - Web Di&aacute;rio</font>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img src="<?=$BASE_URL .'public/images/ifmg.jpg'?>" alt="IFMG - Campus Bambu&iacute;" title="IFMG - Campus Bambu&iacute;" />
    &nbsp;&nbsp;
    <img src="<?=$BASE_URL .'public/images/gti.jpg'?>" alt="Ger&ecirc;ncia TI" title="Ger&ecirc;ncia de TI" width="50" height="34" />
    <img src="<?=$BASE_URL .'public/images/icons/bola_verde.gif'?>" width="10" height="10" />&nbsp;<a href="#" style="background-color: #ffe566;"><?=$sa_usuario?></a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?=$BASE_URL .'index.php'?>" style="background-color: #ffe566;">encerrar a sessão</a>
    <?php
		if ($_SERVER['HTTP_HOST'] == 'dev.cefetbambui.edu.br' || $host != '192.168.0.234')
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<strong>Servidor de BD: </strong>'. $host;
    ?>
    </th>
    </tr>
</table>

<br />
<br />

<div class="tabbed-pane" align="center">
    <ol class="guias">
		<?php
			if($is_professor === TRUE)
				echo '<li><a href="#" '. $class_diarios .' id="pane_diarios">Meus di&aacute;rios</a></li>';

            if($is_coordenador === TRUE)
                echo '<li><a href="#" '. $class_coordenacao .' id="pane_coordenacao">Coordena&ccedil;&atilde;o</a></li>';
        ?>
        
		<li><a href="#" id="pane_ferramentas">Ferramentas</a></li>
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
        <?php
			if($is_professor === TRUE)
                echo "'pane_diarios': 'professor/diarios_professor.php',";

            if($is_coordenador === TRUE)
                echo "'pane_coordenacao': 'coordenacao/cursos_coordenacao.php',";
        ?>
        'pane_ferramentas': 'ferramentas.php',
    },
    {
        onClick: function(e) {
            $('pane_overlay').show();
        },
       
        onSuccess: function(e) {
            $('pane_overlay').hide();
            e = unescape(e.responseText);
            
        },
        contentType: 'text/html',
        encoding: 	'UTF-8'
    });

</script>

</body>
</html>
