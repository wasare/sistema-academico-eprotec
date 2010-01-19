<?php

//echo hash('sha256','');
require_once(dirname(__FILE__). '/../../setup.php');

$conn = new connection_factory($param_conn);
/*
// TODO: verificar se é coordenador quando acessando do web diário
//  VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR
if(isset($_SESSION['sa_modulo']) && $_SESSION['sa_modulo'] == 'web_diario_login') {
  if(!acessa_diario($diario_id,$sa_ref_pessoa)) {

    exit('<script language="javascript" type="text/javascript">
            alert(\'Você não tem direito de acesso a estas informações!\');
            window.close();</script>');
  }
  // ^ VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR ^ //
}
*/

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

if(!is_numeric($_GET['diario_id'])) {
	$qryCurso = 'SELECT DISTINCT id, descricao as nome FROM cursos WHERE id = '. $_GET['curso_id'].';';
	$qryPeriodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $_GET['periodo_id'].'\';';
}
else {
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
<script type="text/javascript" src="<?=$BASE_URL .'app/web_diario/web_diario.js'?>"> </script>

</head>

<body bgcolor="#FFFFFF" text="#000000" >

<script type="text/javascript" src="<?=$BASE_URL .'lib/wz_tooltip.js'?>"> </script>

<center>
<br />
<div align="left">

<table cellpadding="0" cellspacing="0" class="papeleta">
  <tr>
  <td>
    <div align="center">
      <font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif">
        <strong>
          <font color="red">Per&iacute;odo: <?=$periodo['descricao']?></font>
        </strong>
      </font>
    </div>
  </td>
  </tr>
</table>

<h4><strong>Curso: </strong><font color="blue"><?=$curso['id'] .' - '. $curso['nome']?></font></h4>
<h3>Passe o ponteiro do mouse sobre o di&aacute;rio desejado e selecione a op&ccedil;&atilde;o:</h3>

<form id="change_acao" name="change_acao" method="get" action="">

<table cellspacing="0" cellpadding="0" class="papeleta">
    <tr bgcolor="#cccccc">
	    <th align="center"><strong>Ordem</strong></th>
		<th align="center"><b>Di&aacute;rio</b></th>
        <th align="center"><b>Descri&ccedil;&atilde;o</b></th>
		<th align="center"><b>Alunos / Vagas</b></th>
		<th align="center"><b>Turma</b></th>
		<th align="center"><b>Professor(es)</b></th>
        <th align="center"><b>Situa&ccedil;&atilde;o</b></th>
        <th align="center"><b>Op&ccedil;&otilde;es</b></th>
    </tr>

<?php

$i = 0;

$r1 = '#FFFFFF';
$r2 = '#FFFFF';

foreach($diarios as $row3) :

	$descricao_disciplina = $row3["descricao_extenso"];
    $disciplina_id = $row3["idof"];
    $diario_id = $row3["idof"];
	$fl_digitada = $row3['fl_digitada'];
    $fl_concluida = $row3['fl_concluida'];
	$professor = $row3['professor'];
	$qtde_alunos = $row3['qtde_alunos'];
	$turma = $row3['turma'];

    $diarios_pane[] = $diario_id;

    $fl_encerrado = ($fl_digitada == 't')  ? 1 : 0;

    $opcoes_diario = '';
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

    $rcolor = (($i % 2) == 0) ? $r1 : $r2;

	$fl_opcoes = 0;

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
			$fl_opcoes = 1;
        }
        else {				
          $opcoes_diario .= '<a href="#" onclick="enviar_diario(\'marca_finalizado\',\''. $diario_id .'\',\''. $fl_encerrado .'\');">finaliza para lan&ccedil;amentos</a><br /><br />';
          $fl_opcoes = 1;
		}
    }

    if($fl_professor === TRUE) {
      $opcoes_diario .= '<strong>Relat&oacute;rios</strong><hr />';
      $opcoes_diario .= '<a href="#" onclick="enviar_diario(\'papeleta\',\''. $diario_id .'\',\''. $fl_encerrado .'\');">papeleta</a><br />';
      $opcoes_diario .= '<a href="#" onclick="enviar_diario(\'papeleta_completa\',\''. $diario_id .'\',\''. $fl_encerrado .'\');">papeleta completa</a><br />';
      $opcoes_diario .= '<a href="#" onclick="enviar_diario(\'faltas_completo\',\''. $diario_id .'\',\''. $fl_encerrado .'\');">relat&oacute;rio de faltas completo</a><br />';
      $opcoes_diario .= '<a href="#" onclick="enviar_diario(\'conteudo_aula\',\''. $diario_id .'\',\''. $fl_encerrado .'\');">conte&uacute;do de aula</a><br />';
      $opcoes_diario .= '<a href="#" onclick="enviar_diario(\'caderno_chamada\',\''. $diario_id .'\',\''. $fl_encerrado .'\');">caderno de chamada</a>';
	  $fl_opcoes = 1;
	}


	$sem_opcoes = ($fl_opcoes == 0) ? '<font color="red">Nenhuma op&ccedil;&atilde;o dispon&iacute;vel.</font>' : '';

?>
    <?php
      $cont = $i + 1;
    ?>

	<tr bgcolor="<?=$rcolor?>">
      <td align="center"><?=$cont?></td>
      <td align="center"><strong><?=$diario_id?></strong></td>
      <td><strong><?=$descricao_disciplina?></strong></td>
      <td align="center"><?=$qtde_alunos?></td>
      <td align="center"><?=$turma?></td>
      <td><?=$professor?></td>
      <td align="center"><?=$fl_situacao?></td>
      <td align="center">
        <a href="#" id="<?=$diario_id . '_pane'?>" title="clique para visualizar / ocultar">acessar</a>
        <!-- panel com as opções do diário // inicio //-->
        <div id="diario_<?=$diario_id?>_pane" style="display:none; margin: 1.2em; padding: 1em; background-color: <?=$op_color?>" class="opcoes_web_diario">
            <?=$sem_opcoes . $opcoes_diario?>
        </div>
        <!-- panel com as opções do diário \\ fim \\ -->
      </td>
    </tr>

<?php

    $i++;

    endforeach;
?>

</table> <br />
<!--
    echo '<p>';
    echo '<input type="button" id="finaliza_todos" name="finaliza_todos" value="Finaliza todos os di&aacute;rios conclu&iacute;dos" onclick="finaliza_todos('. $curso['id'] .',\''. $periodo['id'] .'\');"/> &nbsp; &nbsp; &nbsp;';
	echo '</p>';
-->
<input type="button" value="finaliza todos os diários concluídos" onclick="enviar_diario('finaliza_todos',<?=$diario_id?>,<?=$fl_encerrado?>);" />
&nbsp;&nbsp;
<a href="#" onclick="javascript:window.close();">Fechar</a>
</form>
<script language="javascript" type="text/javascript">

<?php
    foreach($diarios_pane as $diario_id) :
?>
      $('<?=$diario_id . '_pane'?>').observe('click', function() { $('diario_<?=$diario_id?>_pane').toggle(); });
<?php
   endforeach;
?>
</script>

</div>
</center>
</body>
</head>
</html>
