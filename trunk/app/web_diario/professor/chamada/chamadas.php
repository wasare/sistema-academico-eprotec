<?php

require_once(dirname(__FILE__) .'/../../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['id'];
$operacao = $_GET['do'];

/*
TODO: verifica o direito de acesso do usuário ao diário informado
*/

if (is_finalizado($diario_id)){

    echo '<script language="javascript" type="text/javascript">';
    echo 'alert("ERRO! Este diário está finalizado e não pode ser alterado!");';
    echo 'window.close();';
    echo '</script>';
    exit;
}

$curso = get_curso($diario_id);
$disciplina = get_disciplina($diario_id);

$sa_ref_periodo = $_SESSION['web_diario_periodo_id'];

$meses = array("Janeiro","Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

?>

<html>
<head>
<a name="topo">
<title><?=$IEnome?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">
</head>

<body onLoad="javascript:document.form1.reset()">

<div align="left" class="titulo">
  <h3>Lan&ccedil;amento de chamadas</h3>
</div>
<br />
<?=papeleta_header($diario_id)?>
<br />

<form name="envia_chamada" id="envia_chamada" method="post" action="<?=$BASE_URL .'app/web_diario/professor/chamada/lanca_faltas.php'?>">
    <input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">
    <input type="hidden" name="operacao" id="operacao" value="<?=$operacao?>">

<p><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Data da chamada:</font>&nbsp; 

	<select name="select_dia" id="select_dia" style="width:50px">
   
<?php
  
    $dia = date("d");
	
	echo '<option value="'. $dia .'" selected="selected">'. $dia .'</option>';
	for($d = 1; $d <= 31; $d++) {
		$dia = str_pad($d, 2, "0", STR_PAD_LEFT);
		echo '<option value="'. $dia .'">'. $dia .'</option>';
    } 
?>
	</select> 

	<select name="select_mes" id="select_mes" style="width:100px;">
<?php
	$mes_atual = date("m");
    foreach($meses as $m => $descricao) {
		$selected = ($mes_atual == $m) ? ' selected="selected"' : ' ';
        echo '<option value="'. str_pad(($m + 1), 2, "0", STR_PAD_LEFT) .'"'. $selected .'>'. $descricao .'</option>';
    }
?>
	</select>

	<select name="select_ano" id="select_ano" >

<?php
	$ano_periodo = get_ano_periodo($sa_ref_periodo);

	foreach($ano_periodo as $ano) {
	
		echo '<option value="'. $ano .'">'. $ano .'</option>'; 
	}
	
?>
	</select>
</p>

<table width="90%" height="73" border="0">
  <tr> 
    <td height="18"> 
      <div align="justify"><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Selecione para quantas Aulas ser&aacute; efetuada a chamada :</font></div></td>
  </tr>
<tr> 
   <td><div align="center"></div>
      <div align="left"> 
        <select name="aula_tipo" id="aula_tipo" style="width:400px">
        <option>--- quantidade de aulas ---</option>
		<option value="1" <?php if($_SESSION['aula_tipo'] == "1") { echo 'selected="selected"';} ?>>1 Aula</option>
		<option value="12" <?php if($_SESSION['aula_tipo'] == "12") { echo 'selected="selected"';} ?>>2 Aulas  (Aula Dupla)</option>
		<option value="123" <?php if($_SESSION['aula_tipo'] == "123") { echo 'selected="selected"';} ?>>3 Aulas (Aula Tripla)</option>
		<option value="1234" <?php if($_SESSION['aula_tipo'] == "1234") { echo 'selected="selected"';} ?>>4 Aulas (Aula Qu&aacute;drupla)</option>
		<option value="12345" <?php if($_SESSION['aula_tipo'] == "12345") { echo 'selected="selected"';} ?>>5 Aulas (5 Aulas seguidas)</option>
		<option value="123456" <?php if($_SESSION['aula_tipo'] == "123456") { echo 'selected="selected"';} ?>>6 Aulas (6 Aulas seguidas)</option>
		<option value="12345678" <?php if($_SESSION['aula_tipo'] == "12345678") { echo 'selected="selected"';} ?>>8 Aulas (8 Aulas seguidas)</option>
        </select>
      </div></td>
  </tr>
</table>
</p>

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Conte&uacute;do dado na(s) aula(s):</td>
  </tr>
  <tr>
	<td><textarea name="conteudo" cols="70" rows="5"><?=$_SESSION['conteudo']?></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
 </table>

 <br />
    <?php
		if($_SESSION['flag_falta'] == 'F') {

			echo '<input type="checkbox" class="checkbox" name="flag_falta" value="F" checked="checked" />';
		}
		else {
			echo '<input type="checkbox" class="checkbox" name="flag_falta" value="F">';
		}
	?>
	<font color="brown"><b>N&atilde;o houve faltas neste dia <br /><span style="font-size: 0.8em">(marque esta op&ccedil;&atilde;o caso n&atilde;o exista faltas neste dia)</span></b></font>

	<br /> <br /> 
	<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
	* As faltas desta chamada, caso exista alguma, dever&atilde;o ser informadas no pr&oacute;ximo passo.
	</font>
    <br />			
	<br />

    <input type="submit" name="Submit" value="Prosseguir">
	&nbsp;&nbsp;ou&nbsp;
	<a href="#" onclick="javascript:window.close();">cancelar</a>

  <br />
  
</form>

<h3>INSTRU&Ccedil;&Otilde;ES</h3>
<font color="#330099">
* As op&ccedil;&otilde;es com mais de uma aula se referem &agrave; aulas seguidas.<br />
* N&atilde;o pode haver mais de uma chamada no mesmo dia para o mesmo di&aacute;rio.<br />
<br />

</body>
</html>
