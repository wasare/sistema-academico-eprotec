<?php

require_once(dirname(__FILE__) .'/../../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/date.php');

$conn = new connection_factory($param_conn);

// altera_faltas.php?chamada=521005&flag=2
$diario_id = (int) $_GET['diario_id'];
$chamada_id = (int) $_GET['chamada'];
$num_aulas = $num_faltas = $flag = (int) $_GET['flag'];

$operacao = $_POST['operacao'];

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

$aulatipo = '';
for($i = 1; $i <= $num_aulas; $i++) { $aulatipo .= "$i"; }

$flag = $num_aulas;


$data_bd = $selectdia . '/' . $selectmes . '/'.$selectano;
$data_cons = $selectdia . '/' . $selectmes . '/'.$selectano;
$data_ok = $selectdia . "/" . $selectmes . '/'.$selectano;
$data_chamada =  $selectdia . "/" . $selectmes . '/'.$selectano;
$datadehoje = date ("d/m/Y");


if($flag_falta === 'F') {

	require_once($BASE_DIR .'app/web_diario/movimentos/chamada/registra_faltas.php');
	exit;
}
	
$sql_chamada = "SELECT DISTINCT
              dia
         FROM
          diario_seq_faltas d
        WHERE
          d.id = $chamada_id;";

$data_chamada = $conn->get_one($sql_chamada);


$sql_falta = " SELECT
  a.ra_cnec, count(a.ra_cnec) as faltas
  FROM
    diario_chamadas a
	WHERE
	    (a.ref_disciplina_ofer = $diario_id) AND
		  (a.data_chamada = '$data_chamada')
		  GROUP BY ra_cnec;";


//echo $sqlFalta; die;

$faltas_chamada = $conn->get_all($sql_falta);

$sql1 ="SELECT DISTINCT
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_disciplina_ofer = $diario_id) AND
  (m.dt_cancelamento is null)
ORDER BY
  p.nome; ";

//echo $sql1; die;

$alunos = $conn->get_all($sql1);

?>


<html>
<head>
<a name="topo">
<title><?=$IEnome?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script language="JavaScript" type="text/JavaScript">
<!--
function validate(field) {
	var valid = "0" + "<?=$aulatipo?>"
	var ok = "yes";

	var temp;
	for (var i=0; i<field.value.length; i++) {
		temp = "" + field.value.substring(i, i+1);
		if (valid.indexOf(temp) == "-1") ok = "no";
	}
	
	if (ok == "no") {
		alert("Você não pode lançar " + field.value + " faltas para uma chamada de " + <?=$num_aulas?> + " aulas !");
		//field.focus();
		//field.value = nota;
		field.select();
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

        /* Usando no formulario


        <input onKeyUp="return autoTab(this, 3, event);" size="4" maxlength="3">


        */

}

//-->
</script>

<script src="../js/event-listener.js" type="text/javascript"></script>
<script src="../js/enter-as-tab.js" type="text/javascript"></script>

</head>
<body>

<div align="left" class="titulo">
  <h3>Lan&ccedil;amento de Faltas - Altera&ccedil;&atilde;o</h3>
</div>
<br />
<?=papeleta_header($diario_id)?>
<br />
<br />

<form name="altera_faltas" id="altera_faltas" method="post" action="registra_alt_faltas.php"
	<input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">
    <!--<input type="hidden" name="operacao" id="operacao" value="<\?=$operacao?>">-->
	<input type="hidden" name="num_aulas" id="num_aulas" value="<?=$num_aulas?>">
	<input type="hidden" name="aulatipo" id="aulatipo" value="<?=$aulatipo?>">
    <input type="hidden" name="data_chamada" id="data_chamada" value="<?=$data_chamada?>">

  <h3>Data da Chamada:<font color="blue"><?=$data_chamada?></font>
  <br />Quantidade de Aulas: <font color="brown"><?=$num_aulas?></font>
  </h3>

<div align="justify">
<font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Informe ou altere a quantidade de faltas para cada aluno:</font>
</div>
<br />
<table width="92%" border="0">
  <tr bgcolor="#666666">
                                          
    <td width="6%" align="center"><font color="#FFFFFF"><strong>Faltas</strong></font></td>
    <td width="6%" align="center"><font color="#FFFFFF"><b>&nbsp;Registro</b></font></td>
    <td width="88%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
  </tr>
  
<?php 

$st = '';
	
foreach($alunos as $aluno) 
{
	$aluno_id = $aluno['ra_cnec'];
	$nome_aluno = $linha1['nome'];

	$faltas = '';

	@reset($faltas_chamadas);


	while(list($key, $value) = @each($faltas_chamadas)) {

       if($value['ra_cnec'] == $aluno_id) {
          
          $faltas = $value['faltas'];
          break;
	   }
 
	} 

   if($st == '#F3F3F3') 
   {
      $st = '#E3E3E3';
   } 
   else 
   {
      $st ='#F3F3F3';
   } 
   print (' <tr bgcolor="' . $st . '">
            <td align="center">
            <input type="text" name="faltas['. $aluno_id .']" value="'. $faltas .'" maxlength="1" size="1" onblur="validate(this);" onkeyup="return autoTab(this, 1, event);"/>
            </td>
            <td align="center"> ' . $aluno_id . ' </td>
            <td> ' . $nome_aluno . ' </td>
            </tr>');
}

?>
</table>
<br />


  <input type="submit" name="Submit" value="Salvar Faltas -->" />
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" name="cancelar" value="Voltar" onclick="javascript:window.history.back(1);" />

  <input type="hidden" name="faltas_ok" value="F" />
	  
</form>

      <script type="text/javascript">
//<![CDATA[

      enterAsTab();

//]]>
      </script>
      
</body>
</html>
