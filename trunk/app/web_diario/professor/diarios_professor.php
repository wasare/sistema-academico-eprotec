<?php

require_once(dirname(__FILE__) .'/../../setup.php');

if(empty($_SESSION['web_diario_periodo_id']) OR empty($_SESSION['web_diario_periodo_id']))
{
        exit('<script language="javascript" type="text/javascript">
                window.alert("ERRO! Primeiro informe um período!");
                window.close();
        </script>');
}

$conn = new connection_factory($param_conn);

unset($_SESSION['conteudo']);
unset($_SESSION['flag_falta']);

$diario = @explode("|", $_GET['diario']);

if(isset($_GET['id']) AND ( !is_numeric($diario['0']) OR !is_numeric($diario['1'])) ) {
     exit('<script language="javascript" type="text/javascript">
	 		window.alert("ERRO! Primeiro selecione um diário!"); javascript:window.history.back(1);</script>');
}
else {

	if($diario['2'] === '1' && in_array($_GET['acao'], $Movimento) ) {
 
     exit('<script language="javascript" type="text/javascript">
            window.alert("ERRO! Este diário está fechado e não pode ser alterado!"); javascript:window.history.back(1);
     </script>');
    }
}


$qryPeriodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $_SESSION['web_diario_periodo_id'].'\';';

$periodo = $conn->get_row($qryPeriodo);

$sql3 = 'SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso,
                o.id as idof,
				o.fl_digitada,
                o.fl_concluida
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                f.ref_professor = '. $sa_ref_pessoa .' AND
                o.id = f.ref_disciplina_ofer AND
                o.ref_periodo = \''.$_SESSION['web_diario_periodo_id'].'\' AND
                o.is_cancelada = \'0\' AND
                d.id = o.ref_disciplina;';  

	$diarios = $conn->get_all($sql3);

   if(count($diarios) == 0)
   {
        echo '<script language="javascript">
                window.alert("Nenhum diário encontrado para o filtro selecionado!");
        </script>';
        exit;
   }


// RECUPERA INFORMACOES SOBRE OS PERIODOS DO PROFESSOR
$qry_periodos = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, disciplinas_ofer_prof dp, periodos p WHERE dp.ref_professor = '. $sa_ref_pessoa .' AND o.id = dp.ref_disciplina_ofer AND p.id = o.ref_periodo ORDER BY ref_periodo DESC;';
$periodos = $conn->get_all($qry_periodos);
// ^ RECUPERA INFORMACOES SOBRE OS PERIODOS DO PROFESSOR ^ //

?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>


</head>

<body bgcolor="#FFFFFF" text="#000000" >

<center>
<div align="left">
<br />
  
<strong>
			<font size="4" face="Verdana, Arial, Helvetica, sans-serif">
				Per&iacute;odo: 
				<font color="red" size="4" face="Verdana, Arial, Helvetica, sans-serif"><?=$periodo['descricao']?></font>
			</font>
</strong>
&nbsp;&nbsp;

<span><a href="#" title="alterar o per&iacute;odo" alt="alterar o per&iacute;odo" id="periodos_professor">alterar</a></span>
<br />
<br />
<div id="periodos_professor_pane" style="display:none; border: 0.0015em solid; width:200px; text-align:center;">
<br />

<h4>clique no per&iacute;odo:</h4>
<br />
<?php
    foreach($periodos as $p)
    {
        echo '<a href="#" title="Per&iacute;odo '. $p['descricao'] .'" alt="Per&iacute;odo '. $p['descricao'] .'" onclick="set_periodo(\'periodo_id='. $p['ref_periodo'] .'\');">'. $p['descricao'] .'</a><br />';
    }

?>
<br />
</div>

<br />

<h3>Marque o di&aacute;rio desejado e selecione uma op&ccedil;&atilde;o:</h3>
<br />
<form id="lista_diarios" name="lista_diarios" method="get" action="professor/diarios_professor.php">
<input type="hidden" name="id" id="id" value="<?=$_SESSION['id']?>" />

<table cellspacing="0" cellpadding="0" class="papeleta">
    <tr bgcolor="#cccccc">
	    <td> &nbsp; &nbsp; </td>
        <td align="center"><b>Di&aacute;rio</b></td>
        <td align="center"><b>Descri&ccedil;&atilde;o</b></td>
		<td align="center"><b>Situa&ccedil;&atilde;o</b></td>
    </tr>
<?php

$i = 0;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';
 
// $curso = $_GET["getcurso"];
foreach($diarios as $row3)
{
	$nc = $row3["descricao_extenso"];
    $idnc = $row3["id"];
    $idof = $row3["idof"];
	$fl_digitada = $row3['fl_digitada'];
	$fl_concluida = $row3['fl_concluida'];

    $fl_encerrado = 0;
	
	if($fl_digitada == 'f' && $fl_concluida == 'f') {  
		$fl_situacao = '<font color="green"><b>Aberto</b></font>';  
	} 
	else { 
		if($fl_concluida == 't') {
        	$fl_situacao = '<font color="blue"><b>Conclu&iacute;do</b></font>';
    	}

		if($fl_digitada == 't') {
            $fl_situacao = '<font color="red"><b>Finalizado</b></font>';
            $fl_encerrado = 1;
        }        
	}

    if ( ($i % 2) == 0)
   	{
      $rcolor = $r1;
    }
   	else
   	{
      $rcolor = $r2;
   	}

   	echo '<tr bgcolor="'.$rcolor.'">';
	echo '<td width="3%" align="center"><input  type="radio" name="diario" id="diario" value="'.$idnc.'|'.$idof.'|'.$fl_encerrado.'" /></td>';
   	echo ' <td width="16%" align="center">'.$idof.'</td> <td>'.$nc.'</td> ';		
	echo ' <td width="6%" align="center">'.$fl_situacao.'</td> ';
	echo '</tr> ';

   	$i++;
}

?>
</table>

<br /><br />
<p>

   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<input type="button" id="notas" name="notas" value="Notas" onclick="enviar('notas');"/> &nbsp; &nbsp; &nbsp;

<input type="button" id="chamada" name="chamada" value="Chamada" onclick="enviar('chamada');"/> &nbsp; &nbsp; &nbsp;
<input type="button" id="papeleta" name="papeleta" value="Papeleta" onclick="enviar('papeleta');" /> &nbsp; &nbsp; &nbsp;
<input type="button" id="conteudo" name="conteudo" value="Conte&uacute;do de aula" onclick="enviar('conteudo_aula');" /> &nbsp; &nbsp;  &nbsp; &nbsp;

<select name="relatorio_acao" id="relatorio_acao" class="select" onchange="enviar(this.value);">
    <option value="0">---  relat&oacute;rios     ---</option>
    <option value="papeleta">Papeleta</option>
    <option value="papeleta_completa">Papeleta Completa</option>
	<option value="conteudo_aula">Conte&uacute;do de aula</option>
    <option value="faltas_completo">Relat&oacute;rio de faltas completo</option>
    <option value="caderno_chamada">Imprimir caderno de chamada</option>
</select>

&nbsp;&nbsp;&nbsp; &nbsp;

<select name="outras_acoes" id="outras_acoes" class="select" onchange="enviar(this.value);">
  	<option value="0">---  outras   op&ccedil;&otilde;es     ---</option>
	<option value="marca_diario">Marcar / desmarcar como conclu&iacute;do</option>
	<option value="altera_chamada">Alterar faltas nas chamadas</option>
    <option value="exclui_chamada">Excluir chamada</option>
</select>
	</p>
<br /><br />
<br /><br />
</form>

<script language="javascript" type="text/javascript">

	$('periodos_professor').observe('click', function() { $('periodos_professor_pane').toggle(); });

</script>
</div>
</center>

</body>
</html>
