<?php

require_once(dirname(__FILE__) .'/../../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/date.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['id'];
$operacao = $_GET['do'];

$sa_ref_periodo = $_SESSION['web_diario_periodo_id'];

//  VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR
if(!acessa_diario($diario_id,$sa_ref_pessoa)) {

    exit('<script language="javascript" type="text/javascript">
            alert(\'Você não tem direito de acesso a estas informações!\');
            window.close();</script>');
}
// ^ VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR ^ //

if (is_finalizado($diario_id)){

    echo '<script language="javascript" type="text/javascript">';
    echo 'alert("ERRO! Este diário está finalizado e não pode ser alterado!");';
    echo 'window.close();';
    echo '</script>';
    exit;
}

if(isset($_POST['exclui_ok']) && $_POST['exclui_ok'] === 'exclui_chamada') {

	// EXCLUI TODAS AS FALTAS ANTERIORES PARA A CHAMADA

    // TODO: registrar no log a operação de exclusão de chamada

	$sql_faltas = " SELECT id, ra_cnec
                     FROM
                        diario_chamadas a
                    WHERE
                        a.ref_disciplina_ofer = ". $_POST['diario_id'] ." AND
                        a.data_chamada = '". $_POST['select_data_chamada'] ."';";

	$faltas = $conn->get_all($sql_faltas);

	if(count($faltas) > 0) {

    	foreach( $faltas as $f )
    	{
        	$valor = $f['id'];
        	$ref_pessoa = $f['ra_cnec'];

        	// DELETA A FALTA DO DIARIO
        	$sql1 = " BEGIN; DELETE FROM diario_chamadas WHERE id = $valor;";

        	falta($ref_pessoa, $_POST['diario_id'], 1, 'SUB', $sql1);
   		}
	}


	$sql1 = "DELETE 
         FROM 
            diario_seq_faltas 
         WHERE  
            ref_disciplina_ofer = ". $_POST['diario_id'] ." AND   
            dia = '". $_POST['select_data_chamada'] ."';";

	$conn->Execute($sql1);


	echo '<script language="javascript" type="text/javascript">  window.alert(\'Foram excluídos com sucesso \n os registros referente ao dia ' . $_POST['select_data_chamada'] . '\'); javascript:window.history.back(1); </script>';
	exit;

}

?>
<head>
<title><?=$IEnome?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script language="javascript" type="text/javascript">
function jsConfirm(dia)
{
   if (! dia == "")
   {
    if (! confirm('Você realmente deseja apagar \n a chamada do dia ' + dia + '?' + '\nTodas as faltas lançadas nesta data, \n caso existam, serão excluídas!'))
      {
         return false; 
      } 
      else 
      {
         document.getElementById('exclui_chamada').submit();
         return true;
      }
   }
   else return false;
}

</script>

</head>

<body>

<div align="left" class="titulo1">
  Exclus&atilde;o de Chamada
</div>

<p style="font-size:0.9em; font-face:Verdana, Arial, Helvetica, sans-serif; font-weight:bold; color:red;"> 
        Este processo exclui a chamada do dia selecionado! <br /> O professor dever&aacute; refazer a chamada posteriormente, caso seja necess&aacute;rio.</p>
<br />
<?=papeleta_header($diario_id)?>
<br />
<?php

 $sql4 = "SELECT DISTINCT(dia)
                 FROM
                 diario_seq_faltas
                 WHERE
                 ref_disciplina_ofer = $diario_id ORDER BY dia DESC; ";

 $chamadas = $conn->get_all($sql4);

 ?>


<form name="exclui_chamada" id="exclui_chamada" action="" method="post">
<input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">
<input type="hidden" name="operacao" id="operacao" value="<?=$operacao?>">
<input type="hidden" name="exclui_ok" id="exclui_ok" value="<?=$operacao?>">


<p>Selecione a data da chamada a excluir:</p>
<select name="select_data_chamada" id="select_data_chamada" class="select" onchange="jsConfirm(this.value);">
<option value="">--- data de chamada ---</option>

<?php
	
	foreach($chamadas as $data)
		echo '<option value="'. date::convert_date($data['dia']) .'">'. date::convert_date($data['dia']) .'</option>';
?>	
	</select>

&nbsp;&nbsp;&nbsp;
<a href="#" onclick="javascript:window.close();">fechar</a>
</form>
</body>
</head>
</html>
