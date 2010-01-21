<?php


require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['diario_id'];
$operacao = (string) $_GET['do'];

if($diario_id == 0)
    exit('<script language="javascript" type="text/javascript">window.alert("ERRO! Diario invalido!");window.close();</script>');

// @fixme nao fechar diarios sem prefessor
// MARCA O DIARIO COMO CONCLUIDO
$sql = "SELECT COUNT(*) 
			FROM
            disciplinas_ofer
         WHERE
            fl_concluida = 't' AND
            fl_digitada = 'f' AND
            ref_curso = ". get_curso($diario_id) ." AND
			ref_periodo = periodo_disciplina_ofer($diario_id) AND
            is_cancelada = '0';";


$num_concluida = $conn->get_one($sql);

if($num_concluida == 0) {	
  echo '<script type="text/javascript">alert(\'N�o existe nenhum di�rio conclu�do para ser finalizado!\');</script>';
}
else {
  $sql1 = "UPDATE
			disciplinas_ofer
         SET
            fl_digitada = 't' 
         WHERE  
		    fl_concluida = 't' AND
            fl_digitada = 'f' AND
			ref_curso = ". get_curso($diario_id) ." AND
            ref_periodo = periodo_disciplina_ofer($diario_id) AND
            is_cancelada = '0';";

  $conn->Execute($sql1);

  echo '<script type="text/javascript"> alert(\''.$num_concluida.' di�rio(s) finalizado(s) com sucesso!\'); </script>';
}
	
?>
