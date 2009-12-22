<?php

require_once(dirname(__FILE__) .'/../../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_POST['diario_id'];
$operacao = $_POST['operacao'];

//  VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR
if(!acessa_diario($diario_id,$sa_ref_pessoa)) {

    exit('<script language="javascript" type="text/javascript">
            alert(\'Voc� n�o tem direito de acesso a estas informa��es!\');
            window.close();</script>');
}
// ^ VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR ^ //

if (is_finalizado($diario_id)){

    echo '<script language="javascript" type="text/javascript">';
    echo 'alert("ERRO! Este di�rio est� finalizado e n�o pode ser alterado!");';
    echo 'window.close();';
    echo '</script>';
    exit;
}

$periodo = $_SESSION['web_diario_periodo_id'];

$oferecida = $getofer;

$flag_falta = $_POST['flag_falta'];
$_SESSION['flag_falta'] = $flag_falta;

$aula_tipo = $_POST['aula_tipo'];
$conteudo = trim($_POST['conteudo']);

$_SESSION['conteudo'] = $conteudo;

$_SESSION['aula_tipo'] = $aula_tipo;

$num_aulas = $aula_tipo[strlen($aula_tipo) - 1];


if(!is_numeric($aula_tipo) || (strlen($aula_tipo) < 1 || strlen($aula_tipo) > 8 ))
{
   echo '<script language=javascript> window.alert("Voc� dever� selecionar a quantidade de aulas para esta chamada.");     javascript:window.history.back(1);</script>';
   exit;
	
}
 
if($_POST['select_dia'] == "")
{
	 echo '<font size=2><b>Voc&ecirc; n&atilde;o selecionou o DIA ! <a href="javascript:history.go(-1);">Voltar</a>!</b></font>';
  exit;
}
else
{
  $select_dia = $_POST['select_dia'];
}

if($_POST['select_mes'] == "")
{
  echo '<font size=2><b>Voc&ecir;  n&atilde;o selecionou o M&Ecirc;S ! <a href="javascript:history.go(-1);">Voltar</a>!</b></font>';
  exit;
}
else
{
  $select_mes = $_POST['select_mes'];
}


if($_POST['select_ano'] == "")
{
  echo '<font size=2><b>Voc&ecirc; n&atilde;o selecionou o ANO ! <a href"javascript:history.go(-1);">Voltar</a>!</b></font>';
  exit;
}
else
{
  $select_ano = $_POST['select_ano'];
}


// VALIDAR CONTEUDO AQUI

if($conteudo == '')
{
  echo '<script language="javascript" type="text/javascript"> window.alert("Voc� n�o informou o conte�do da(s) aula(s)!"); javascript:window.history.back(1); </script>';
  exit;
}

$data_consulta = $select_ano . '-' . $select_mes . '-'. $select_dia;
$data_chamada = $select_dia . "/" . $select_mes . '/'. $select_ano;

/* SELECIONA A DATA PARA VERIFICA DUPLICADOS */
$sqld1 = "SELECT dia, flag 
	  FROM
      diario_seq_faltas
      WHERE
      dia = '". $data_consulta ."' AND
      periodo = '" . $periodo ."' AND
      ref_disciplina_ofer = ". $diario_id .";";

$seq_faltas = $conn->get_row($sqld1);


$dia = $seq_faltas['dia'];
$flag = $seq_faltas['flag'];


if((@$flag > '0') AND (@$flag <= '8'))
{

	die('<script language="javascript" type="text/javascript"> window.alert("J� existe chamada realizada para esta data.");   javascript:window.history.back(1); </script>');
}
else {

	// N�O HOUVE FALTAS PARA A CHAMADA
	if($flag_falta === 'F') {

		require_once('registra_faltas.php');
		exit;
	}
}
	
 // PREPARA FORMULARIO PARA LANCAMENTO DE FALTAS
               
$sql1 = "SELECT
  matricula.ordem_chamada,
  pessoas.nome,
  pessoas.id,
  matricula.ref_pessoa
FROM
  matricula
  INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
WHERE
  (matricula.ref_periodo = '$periodo') AND
  (matricula.ref_disciplina_ofer = $diario_id) AND 
  (matricula.dt_cancelamento is null) AND
  (matricula.ref_motivo_matricula = 0)
ORDER BY
   lower(to_ascii(pessoas.nome));"; 
  

$alunos = $conn->get_all($sql1);
$curso = get_curso($diario_id);

?>


<html>
<head>
<title><?=$IEnome?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script language="JavaScript" type="text/JavaScript">
<!--
function validate(field) {
	var valid = "0" + "<?=$aula_tipo?>"
	var ok = "yes";
	var temp;
	for (var i=0; i<field.value.length; i++) {
		temp = "" + field.value.substring(i, i+1);
		if (valid.indexOf(temp) == "-1") ok = "no";
	}
	
	if (ok == "no") {
		alert("Voc� n�o pode lan�ar " + field.value + " faltas para uma chamada de " + <?=$num_aulas?> + " aulas !");
		//field.focus();
        field.focus();
		field.value = "<?=$num_faltas?>";
		
   }
}

// Functions de mudanca automatica de foco
function autoTab(input,len, e) {
         var isNN = (navigator.appName.indexOf("Netscape")!=-1);

         var keyCode = (isNN) ? e.which : e.keyCode;
         var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
         if(input.value.length >= len && !containsElement(filter,keyCode)) {
                 input.value = input.value.slice(0, len);
                 input.form[(getIndex(input)+1) % input.form.length].focus();
         }

        function containsElement(arr, ele) {
               var found = false, index = 0;
               while(!found && index < arr.length)
               if(arr[index] == ele)
                  found = true;
               else
               index++;
               return found;
        }

		        function getIndex(input) {
                var index = -1, i = 0, found = false;
                while (i < input.form.length && index == -1)
                if (input.form[i] == input)index = i;
                else i++;
                return index;
        }

        return true;

        /* 
			Usando no formulario
				<input onKeyUp="return autoTab(this, 3, event);" size="4" maxlength="3">
        */
}

//-->
</script>
</head>
<body>

<div align="left" class="titulo">
  <h3>Lan&ccedil;amento de faltas da chamada</h3>
</div>
<br />
<?=papeleta_header($diario_id)?>

<form name="envia_faltas" id="envia_faltas" method="post" action="<?=$BASE_URL .'app/web_diario/professor/chamada/registra_faltas.php'?>">
    <input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">
    <input type="hidden" name="operacao" id="operacao" value="<?=$operacao?>">
	<input type="hidden" name="aula_tipo" id="aula_tipo" value="<?=$aula_tipo?>">
    <input type="hidden" name="num_faltas" id="operacao" value="<?=$num_faltas?>">
    <input type="hidden" name="data_chamada" id="data_chamada" value="<?=$data_chamada?>">

  <h3>
  Data da Chamada: <font color="blue"> <?=$data_chamada?></font>
  <br />Quantidade de Aulas: <font color="brown"> <?=$num_aulas?></font>
  </h3>
	<a href="<?=$BASE_URL .'app/web_diario/requisita.php?do='. $operacao .'&id=' . $diario_id?>"><strong>Alterar a data e/ou quantidade de aulas</strong></a><br />

<br />
<div align="justify"><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Informe a quantidade de faltas, quando houver, para cada aluno:</font></div> <br />

<table cellspacing="0" cellpadding="0" class="papeleta">
  <tr bgcolor="#cccccc">
    
	<td align="center"><b>N&ordm; ordem</b></td>                                      
    <td align="center"><strong>Faltas</strong></td>
    <td align="center"><b>&nbsp;Matr&iacute;cula</b></font></td>
    <td><b>&nbsp;Nome do aluno</b></td>
  </tr>
  
<?php 

$st = '';

$ordem = 1;
	
	foreach($alunos as $aluno) :

		$matricula = $aluno['ref_pessoa'];
		$nome = $aluno['nome'];
   
		$st = ($st == '#F3F3F3') ? '#E3E3E3' : '#F3F3F3'; 
?>
	<tr bgcolor="<?=$st?>">
		<td align="center"><?=$ordem?></td>
		<td align="center">
        <input type="text" name="faltas[<?=$matricula?>]" value="" maxlength="1" size="1" onblur="validate(this);" onkeyup="return autoTab(this, 1, event);"/>
        </td>
        <td align="center"><?=$matricula?></td>
        <td><?=$nome?></td>
        </tr>
<?php
		$ordem++;

	endforeach;
?>
</table>
<br />


  <input type="submit" name="Submit" value="Salvar">
  &nbsp;&nbsp;ou&nbsp;
    <a href="#" onclick="javascript:window.close();">cancelar chamada</a>
  <input type="hidden" name="faltas_ok" value="<?=$_SESSION['flag_falta']?>" />
</form>
<br />      
</body>
</html>
