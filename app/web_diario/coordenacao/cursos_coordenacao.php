<?php

require_once(dirname(__FILE__) .'/../../setup.php');

if(empty($_SESSION['web_diario_periodo_coordena_id'])) {
       exit ('<script language="javascript">
                window.alert("ERRO! Primeiro informe um per�odo!");
                window.close();
        </script>');
}

$conn = new connection_factory($param_conn);

unset($_SESSION['conteudo']);
unset($_SESSION['flag_falta']);

$qryPeriodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $_SESSION['web_diario_periodo_coordena_id'].'\';';

$periodo = $conn->get_row($qryPeriodo);

$cursos = '';
$cont = 1;
foreach($_SESSION['web_diario_cursos_coordenacao'] as $c) {
	$cursos .= $c;
    if(count($_SESSION['web_diario_cursos_coordenacao']) > $cont)
		$cursos .= ',';
	$cont++;
}

$sql_cursos = " SELECT DISTINCT
    a.ref_curso || ' - ' || c.descricao AS curso, a.ref_curso, ref_tipo_curso
      FROM
          disciplinas_ofer a, disciplinas_ofer_prof b, cursos c
            WHERE
                a.ref_periodo = '". $_SESSION['web_diario_periodo_coordena_id'] ."' AND
                a.ref_curso IN (". $cursos .") AND
                    a.id = b.ref_disciplina_ofer AND
                        c.id = a.ref_curso 
            ORDER BY ref_tipo_curso;";


$cursos = $conn->get_all($sql_cursos);

if(count($cursos) == 0)
{
    echo '<script language="javascript">
                window.alert("Nenhum curso encontrado!");
        </script>';
        exit;
}

// RECUPERA INFORMACOES SOBRE oS PERIODOS DA COORDENACAO
$qry_periodos = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, periodos p WHERE  o.ref_periodo = p.id AND o.ref_curso IN (SELECT DISTINCT ref_curso FROM coordenador WHERE ref_professor = '. $sa_ref_pessoa .') ORDER BY ref_periodo DESC;';

$periodos = $conn->get_all($qry_periodos);
// ^ RECUPERA INFORMACOES SOBRE oS PERIODOS DA COORDENACAO ^ //

?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>

</head>

<body>

<div align="left">

<strong>
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif">
                Per&iacute;odo de coordena��o: 
                <font color="red" size="4" face="Verdana, Arial, Helvetica, sans-serif"><?=$periodo['descricao']?></font>
            </font>
</strong>
&nbsp;&nbsp;

<span><a href="#" title="alterar o per&iacute;odo" id="periodos_coordenacao">alterar</a></span>
<br />
<br />
<!-- panel para alteracao dos periodos do coordenador // inicio //-->
<div id="periodos_coordenacao_pane" style="display:none; border: 0.0015em solid; width: 200px; text-align:center;">
<br />

<h4>clique no per&iacute;odo:</h4>
<br />
<?php
    foreach($periodos as $p) {
        echo '<a href="#" title="Per&iacute;odo '. $p['descricao'] .'" alt="Per&iacute;odo '. $p['descricao'] .'" onclick="set_periodo(\'periodo_coordena_id='. $p['ref_periodo'] .'\');">'. $p['descricao'] .'</a><br />';
    }
?>
<br />
</div>
<!-- panel para alteracao dos periodos do coordenador \\ fim \\ -->
<br />
<strong>
            <font size="4" face="Verdana, Arial, Helvetica, sans-serif">
                Cursos desta coordena��o 
            </font>
</strong>
<br /> <br /> 

<h5>clique no curso para acessar os di�rios</h5>
<br />

<?php
    foreach($cursos as $c) {
		$onclick = 'onclick="abrir(\''. $IEnome .' - web di�rio\', \'requisita.php?do=lista_diarios_coordenacao&id='. $c['ref_curso'] .'\');"';
        echo '<a href="#" title="Curso '. $c['curso'] .'" alt="Curso '. $c['curso'] .'" '. $onclick .'>'. $c['curso'] .'</a><br />';
    }
?>

<br /><br />

<form name="acessa_diario" id="acesso_diario" method="post" action="">
<strong>Acesso r�pido</strong> <br />
C�digo do di�rio:
<input type="text" name="diario_id" id="diario_id" size="10" />
<input type="button" name="envia_diario" id="envia_diario" value="Consultar" onclick="enviar_diario('pesquisa_diario_coordenacao',null,null);" />
</form>
<br />
</div>
<script language="javascript" type="text/javascript">
    $('periodos_coordenacao').observe('click', function() { $('periodos_coordenacao_pane').toggle(); });
</script>

</body>
</html>
