<?php

require_once('aluno.conf.php');

include_once('topo.htm');

$qryNotas = 'SELECT
   DISTINCT
     c.descricao_disciplina, b.ra_cnec, a.ordem_chamada, a.nota_final, a.ref_curso, a.num_faltas, d.fl_digitada
   FROM matricula a, pessoas b, disciplinas c, disciplinas_ofer d
   WHERE
      a.ref_periodo = \'%s\' AND
      a.ref_disciplina
            IN (
                  SELECT
                     DISTINCT
                        a.ref_disciplina
                     FROM matricula a, disciplinas b
                     WHERE
                        a.ref_disciplina = b.id AND
                        a.ref_periodo = \'%s\' AND
                        a.ref_motivo_matricula = 0 AND
                        a.ref_pessoa = %s
             ) AND
      a.ref_disciplina = c.id AND
      a.ref_pessoa = b.id AND
	  a.ref_disciplina_ofer = d.id AND
      a.ref_curso = %s AND
      a.ref_pessoa = %s
      ORDER BY
         c.descricao_disciplina;';
// a.ref_disciplina_ofer = d.id AND

$aluno = $user;
$data = $DataInicial;

$periodo = $_GET["p"];
$curso = $_GET["c"];

$sql = sprintf($qryNotas,$periodo,$periodo,$aluno,$curso,$aluno);
$RES = $conn->getAll(sprintf($qryNotas,$periodo,$periodo,$aluno,$curso,$aluno));

//echo sprintf($qryCurso,$aluno, $data, $aluno);
//echo sprintf($qryNotas,$periodo,$periodo,$aluno,$curso,$aluno); die;

if ($conn === false) {
   echo $conn->ErrorMsg() . '<br/>';
}
else
{

   $AlunoNome = $conn->getOne('SELECT nome FROM pessoas WHERE id = '.$aluno.';');
   $CursoNome = $conn->getOne('SELECT abreviatura FROM cursos WHERE id = '.$curso.';');
   $PeriodoNome = $conn->getOne('SELECT descricao FROM periodos WHERE id = \''.$periodo.'\';');
   
   
   echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="750">
  <tbody>
    <tr><td class="login"><h2 class="title">Meu Aproveitamento:</h2></td></tr><tr>';
   echo '<td><b>Aluno: </b>'.$AlunoNome.'&nbsp;&nbsp;&nbsp;&nbsp;<b>Registro:  </b>'.str_pad($aluno, 5, "0", STR_PAD_LEFT).'<td/><tr>';
   echo '<tr><td>&nbsp;</td></tr>';
   echo '<td><b>Curso: </b>'.$CursoNome.'&nbsp;&nbsp;&nbsp;&nbsp;<b>Per&iacute;odo:  </b>'.$PeriodoNome.'<td/><tr>';
   
   echo '<tr><td height="30">&nbsp;&nbsp;</td></tr>';

   echo '<tr><td>
   		<table cellpadding="2" cellspacing="2" width="600">
		<tr bgcolor="#000000" >
		<td align="center"><font color="#FFFFFF"><b>Disciplina</b></font></td>
		<td align="center"><font color="#FFFFFF"><b>Nota</b></font></td>
		<td align="center"><font color="#FFFFFF"><b>Faltas</b></font></td>
		</tr>';

   $bgcolor = '';


   //echo count($RES); print_r($RES);

   for($i = 0; $i < count($RES) ; $i++)
   {
      if ( ($i % 2) == 0 ){
	  	$bgcolor = "#FFFFFF";
	  }
      else{
	  	$bgcolor = "#FFFFCC";
	  }
	  
	  if($RES[$i]['fl_digitada'] == 'f') { 
		  $encerrada = '<font color="red" size="-2"><strong>*</strong></font>'; 
	  }
	  else {
		  $encerrada = '';
	  }

      if (empty($RES[$i]["num_faltas"]))
        $faltas = ' - ';
	  else
		$faltas = $RES[$i]["num_faltas"];	  

      echo '<tr bgcolor="'.$bgcolor.'"><td>&nbsp;&nbsp;'.$RES[$i]["descricao_disciplina"].'&nbsp;&nbsp;'.$encerrada.'</td>';
      echo '<td>&nbsp;&nbsp;'.$RES[$i]["nota_final"].'&nbsp;&nbsp;</td>';
      echo '<td>&nbsp;&nbsp;'. $faltas .'&nbsp;&nbsp;</td></tr>';

   }
//   $voltar = '<tr><td><a href="javascript:window.history.back(1);">Voltar</a></td></tr> ';

   $parcial = '<font color="red" size="-2">(<strong>*</strong>) disciplinas com notas parciais, ainda poder&aacute; sofrer altera&ccedil;&otilde;es!</font>';
   $voltar = '<tr><td><br /><a href="lista_cursos.php">Voltar</a></td></tr> ';
   
   echo '</table></td></tr>
   <tr><td>&nbsp;</tr></td>
   <tr><td>'.$parcial.'</td></tr>'.$voltar.'</table>';
}

include_once('rodape.htm');
?>
