<?php

//echo hash('sha256','');
require_once(dirname(__FILE__). '/../../setup.php');

if(empty($_GET['periodo_id']) OR empty($_GET['curso_id']))
{
	if(empty($_GET['diario_id']))
	{
		echo '<script language="javascript">
                window.alert("ERRO! Primeiro informe um período e um curso ou um diário!");
				window.close();
		</script>';
		exit;
	}
}

/*
 TODO: verifica o direito de acesso do usuário aos dados, principalmente o coordenador
*/

$conn = new connection_factory($param_conn);

if(!is_numeric($_GET['diario_id']))
{
	$qryCurso = 'SELECT DISTINCT id, descricao as nome FROM cursos WHERE id = '. $_GET['curso_id'].';';
	$qryPeriodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $_GET['periodo_id'].'\';';
}
else
{
	$qryCurso = 'SELECT c.id, c.descricao as nome FROM cursos c, disciplinas_ofer d WHERE d.ref_curso = c.id AND d.id = '. $_GET['diario_id'].';';
	$qryPeriodo = 'SELECT p.id, p.descricao FROM periodos p, disciplinas_ofer d WHERE d.ref_periodo = p.id AND d.id = '. $_GET['diario_id'].';';
}


$curso = $conn->get_row($qryCurso);
$periodo = $conn->get_row($qryPeriodo);


	$sql =  " SELECT id as idof, " . 
           "        ref_campus, " .
           "        get_campus(ref_campus), " .
           "        ref_curso, " .
           "        curso_desc(ref_curso), " .
           "		fl_digitada, fl_concluida, ".
           "        descricao_disciplina(ref_disciplina) as descricao_extenso, " .
           "        ref_disciplina, " .
           "        get_num_matriculados(id) || '/' || num_alunos as qtde_alunos, " .
           "        turma, " .
           "        ref_periodo_turma, " .
		   "     CASE WHEN professor_disciplina_ofer_todos(id) = '' THEN '<font color=\"red\">sem professor</font>' " .
		   "			ELSE professor_disciplina_ofer_todos(id) " .
		   "		END AS \"professor\" " .
           " FROM disciplinas_ofer " .
           " WHERE is_cancelada = '0' ";

			
			if(is_numeric($_GET['diario_id']))
                $sql .= " AND id = ". $_GET['diario_id'];
			else
				if(is_numeric($_GET['periodo_id']) AND is_numeric($_GET['curso_id']) )
				{
					$sql .= " AND ref_periodo = '". $_GET['periodo_id'] ."'";
					$sql .= " AND ref_curso = ". $_GET['curso_id'];
				}

			$sql = 'SELECT * from ('. $sql .') AS T1 ORDER BY lower(to_ascii(descricao_extenso));';


   $diarios = $conn->get_all($sql);

   if(count($diarios) == 0) 
   {
		echo '<script language="javascript">
                window.alert("Nenhum diário encontrado para o filtro selecionado!");
                window.close();
		</script>';
		exit;
   }

?>

<html>
<head>
<title><?=$IEnome?> - consulta di&aacute;rios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>

<script language="javascript">

function finaliza_todos(curso,periodo)
{	
	if ( !(curso == "") && !(periodo == "") ) {
    	if (! confirm('Você deseja realmente finalizar todos os diários no período/curso corrente?\n Depois de finalizados o professor não poderá fazer alterações e \n somente a secretaria poderá abri-los novamente!'))
      	{
        	javascript:window.history.back(1);
            return false;
        }
        else {
        	self.location = "movimentos/finaliza_todos.php?curso_id=" + curso + "&periodo_id=" + periodo;
            return true;
        }
    }
    else {
    	javascript:window.history.back(1);
        return false;
   }
}
</script>

</head>

<body bgcolor="#FFFFFF" text="#000000" >

<script type="text/javascript" src="<?=$BASE_URL .'lib/wz_tooltip.js'?>"> </script>

<center>
<div align="left"><br>
<?php
   print(' <table cellpadding="0" cellspacing="0" class="papeleta">
  <tr>
  <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="red">Per&iacute;odo: '. $periodo['descricao'] .'</font></strong></font></div></td>
  </tr>
</table>
  ');
echo '<h4><strong>Curso: </strong><font color="blue">'. $curso['id'] .' - '. $curso['nome'] .'</font></h4>';
echo '<p><h3>Passe o ponteiro do mouse sobre o di&aacute;rio desejado e selecione a op&ccedil;&atilde;o:</h3></p>';
echo '<form id="change_acao" name="change_acao" method="get" action="diarios_coordenacao.php">';
echo '<input type="hidden" name="id" id="id" value="' . $_SESSION['select_prof'] . '" />';
echo '<input type="hidden" name="vars" id="vars" value="' . $vars_b . '" />';
?>   

<table cellspacing="0" cellpadding="0" class="papeleta">
    <tr bgcolor="#cccccc">
	    <td align="center"><strong>Ordem</strong></td>
		<td align="center"><b>Di&aacute;rio</b></td>
        <td align="center"><b>Descri&ccedil;&atilde;o</b></td>
		<td align="center"><b>Alunos / Vagas</b></td>
		<td align="center"><b>Turma</b></td>
		<td align="center"><b>Professor(es)</b></td>
        <td align="center"><b>Situa&ccedil;&atilde;o</b></td>
    </tr>
<?php
	
$i = 0;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';
 
foreach($diarios as $row3)
{
	$nc = $row3["descricao_extenso"];
    $idnc = $row3["idof"];
    $idof = $row3["idof"];
	$fl_digitada = $row3['fl_digitada'];
    $fl_concluida = $row3['fl_concluida'];
	$professor = $row3['professor'];
	$qtde_alunos = $row3['qtde_alunos'];
	$turma = $row3['turma'];

    $fl_encerrado = 0;

	$fl_professor = TRUE;
	if ( preg_match('/sem professor/i', $professor) ) 
		$fl_professor = FALSE;


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

	$fl_opcoes = 0;

	$opcoes_diario = '';
	
    if($fl_professor === TRUE)
	{
		$opcoes_diario .= '<a href="'. $BASE_URL .'app/relatorios/web_diario/papeleta.php?diario_id='. $idof .'" target="_blank">papeleta</a><br />';
		$opcoes_diario .= '<a href="'. $BASE_URL .'app/relatorios/web_diario/papeleta_completa.php?diario_id='. $idof .'" target="_blank">papeleta completa</a><br />';
		$opcoes_diario .= '<a href="'. $BASE_URL .'app/relatorios/web_diario/faltas_completo.php?diario_id='. $idof .'" target="_blank">relat&oacute;rio de faltas completo</a><br />';
		$opcoes_diario .= '<a href="'. $BASE_URL .'app/relatorios/web_diario/conteudo_aula.php?diario_id='. $idof .'" target="_blank">conte&uacute;do de aula</a><br />';
		$opcoes_diario .= '<a href="'. $BASE_URL .'app/relatorios/web_diario/caderno_chamada.php?diario_id='. $idof .'" target="_blank">caderno de chamada</a><br />';
	    $fl_opcoes = 1;
	
	}

	if($fl_digitada == 'f' && $fl_concluida == 'f') {
        $fl_situacao = '<font color="green"><b>Aberto</b></font>';
    }
    else {
		
		$opcoes_diario .= '<br />';

        if($fl_concluida == 't') {
            $fl_situacao = '<font color="blue"><b>Conclu&iacute;do</b></font>';
        }

        if($fl_digitada == 't') {
            $fl_situacao = '<font color="red"><b>Finalizado</b></font>';
			if(!isset($operacao)) {
				$opcoes_diario .= '<a href="'. $BASE_URL .'app/web_diario/secretaria/marca_aberto.php?diario_id='. $idof .'">abre para lan&ccedil;amentos</a><br />';
				$fl_opcoes = 1;
			}
            $fl_encerrado = 1;
			$fl_opcoes = 1;
        }
        else {
				$opcoes_diario .= '<a href="'. $BASE_URL .'app/web_diario/coordenacao/marca_finalizado.php?diario_id='. $idof .'">finaliza para lan&ccedil;amentos</a><br />';
				$fl_opcoes = 1;
		}
    }
	
	
	if($fl_opcoes == 0)
		$sem_opcoes = '<font color="red">Nenhuma op&ccedil;&atilde;o dispon&iacute;vel.</font>';			
	else
		$sem_opcoes = '';


	echo '<div id="diario_id-'. $idof .'" class="opcoes_diario">';
	echo $sem_opcoes . $opcoes_diario;
	echo '</div>';

    $cont = $i + 1;
	echo "<tr bgcolor=\"$rcolor\">\n";
    echo '<td align="center">'. $cont .'</td>';

    echo '<td align="center"><a href="javascript:void(0);" onmouseover="TagToTip(\'diario_id-'. $idof .'\', ABOVE, true,PADDING, 9, TITLE, \'Op&ccedil;&otilde;es do di&aacute;rio - '. $idof .'\', CLOSEBTN, true,STICKY, true,FONTSIZE, \'0.8em\', COPYCONTENT, false, BGCOLOR, \'#FFFFFF\' )" onmouseout="UnTip()">'. $idof .'</a></td>';
	echo '<td><a href="javascript:void(0);" onmouseover="TagToTip(\'diario_id-'. $idof .'\', ABOVE, true,PADDING, 9, TITLE, \'Op&ccedil;&otilde;es do di&aacute;rio - '. $idof .'\', CLOSEBTN, true,STICKY, true,FONTSIZE, \'0.8em\', COPYCONTENT, false, BGCOLOR, \'#FFFFFF\' )" onmouseout="UnTip()">'. $nc .'</a></td>';
	echo "<td align=\"center\">$qtde_alunos</td>\n";
   	echo " <td align=\"center\">$turma</td>\n <td>$professor</td>\n ";
    echo " <td align=\"center\">$fl_situacao</td>\n ";

    echo "</tr>\n ";

    $i++;
}

echo '</table> <br />';
/*
    echo '<p>';
    echo '<input type="button" id="finaliza_todos" name="finaliza_todos" value="Finaliza todos os di&aacute;rios conclu&iacute;dos" onclick="finaliza_todos('. $curso['id'] .',\''. $periodo['id'] .'\');"/> &nbsp; &nbsp; &nbsp;';
	echo '</p>';
*/
?>
<input type="button" value="finaliza todos os diários concluídos" />
&nbsp;&nbsp;
<a href="#" onclick="javascript:window.close();">Fechar</a>
</form>
</body>
</head>
</html>
