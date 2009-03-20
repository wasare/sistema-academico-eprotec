<?php

include_once('../../webdiario.conf.php');

//altera_faltas.php?flag=146025&data=05/02/2007&chamada=146025&id=2472&getperiodo=0701&disc=107001&ofer=1715

$disciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
//$curso = $_GET['curso'];
$periodo = $_SESSION['periodo'];
$id = $_GET['id'];

$oferecida = $getofer;

$num_aulas = $_GET['chamada'];

$num_faltas = $num_aulas;

$aulatipo = '';

for($i = 1; $i <= $num_aulas; $i++) { $aulatipo .= "$i"; }

$flag = $_GET['flag'];


$data_bd = $selectdia . '/' . $selectmes . '/'.$selectano;
$data_cons = $selectdia . '/' . $selectmes . '/'.$selectano;
$data_ok = $selectdia . "/" . $selectmes . '/'.$selectano;
$data_chamada =  $selectdia . "/" . $selectmes . '/'.$selectano;
$datadehoje = date ("d/m/Y");


if($flag_falta === 'F') {

	include_once('registra_faltas.php');
	exit;
}
	
$sqlChamada = "SELECT DISTINCT
              dia
         FROM
          diario_seq_faltas d
        WHERE
          d.id = '$flag';";

$qryChamada = consulta_sql($sqlChamada);

if(is_string($qryChamada))
{
   echo $qryChamada;
   exit;
}
else
{
    while ( $linha = pg_fetch_array($qryChamada) )
    {
        $data_chamada = $linha['dia'];
    }
}


$sqlFalta = " SELECT
  a.ra_cnec, count(a.ra_cnec) as faltas
  FROM
    diario_chamadas a
	WHERE
	  (a.ref_periodo = '$periodo') AND
	    (a.ref_disciplina_ofer = '$oferecida') AND
		  (a.data_chamada = '$data_chamada')
		  GROUP BY ra_cnec;";


//echo $sqlFalta; die;

$qryFalta = consulta_sql($sqlFalta);

if(is_string($qryFalta))
{
   echo $qryFalta;
   exit;
}

$qryFaltas = pg_fetch_all($qryFalta);


               
$sql1 = "SELECT 
  T1.nome, T1.id, T1.ra_cnec, COUNT(T2.ra_cnec) AS faltas
FROM
  (
    SELECT DISTINCT
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '$periodo') AND
  (m.ref_disciplina_ofer = '$oferecida') AND
  (m.dt_cancelamento is null)
 
  ) AS T1 
  
LEFT OUTER JOIN
  ( 
    SELECT
  a.ra_cnec
FROM  
  diario_chamadas a
  LEFT OUTER JOIN pessoas p ON (p.id = a.ra_cnec)
WHERE
  (a.ref_periodo = '$periodo') AND
  (a.ref_disciplina_ofer = '$oferecida') AND
  (a.data_chamada = '$data_chamada') AND
   (p.id = a.ra_cnec) 
  ) AS T2
USING(ra_cnec)
GROUP BY T1.nome, T1.id, T1.ra_cnec
ORDER BY T1.nome; ";

//$query1 = pg_exec($dbconnect, $sql1);

$sql1 ="SELECT DISTINCT
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '$getperiodo') AND
  (m.ref_disciplina_ofer = '$getofer') AND
  (m.dt_cancelamento is null)
ORDER BY
  p.nome; ";



$query1 = consulta_sql($sql1);

if(is_string($query1))
{
   echo $query1;
   exit;
}


?>


<html>
<head>
<a name="topo">
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">


<script language="JavaScript" type="text/JavaScript">
<!--
function validate(field) {
	var valid = "0" + "<?php echo $aulatipo;?>"
	var ok = "yes";

	var temp;
	for (var i=0; i<field.value.length; i++) {
		temp = "" + field.value.substring(i, i+1);
		if (valid.indexOf(temp) == "-1") ok = "no";
	}
	
	if (ok == "no") {
		alert("Você não pode lançar " + field.value + " faltas para uma chamada de " + <?php echo $num_aulas; ?> + " aulas !");
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
<body onLoad="javascript:document.form1.reset()">

<table width="90%" height="73" border="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvet
ica, sans-serif"><strong>Lan&ccedil;amento de Faltas - Altera&ccedil;&atilde;o</strong></font></div></td>
  </tr>

</table>
<br />
<?php 
   print('<form name="form1" method="post" action="registra_alt_faltas.php">');
   
   echo '<input type="hidden" name="id" id="id" value="' .$id.'">';
   echo '<input type="hidden" name="periodo" id="periodo" value="' . $periodo.'">';
   echo '<input type="hidden" name="disciplina" id="disc" value="' .$disciplina.'">';
   echo '<input type="hidden" name="oferecida" id="ofer" value="' . $oferecida.'">';
   echo '<input type="hidden" name="num_aulas" id="num_aulas" value="' . $num_aulas.'">';
   echo '<input type="hidden" name="aulatipo" id="aulatipo" value="' . $aulatipo.'">';
   echo '<input type="hidden" name="data_chamada" id="data_chamada" value="' . $data_chamada.'">';
   
  echo getHeaderDisc($oferecida);   

  echo '<h3>';
  echo 'Data da Chamada:<font color="blue"> '.$data_chamada.'</font>' ;
  echo '<br />Quantidade de Aulas: <font color="brown"> '.$num_aulas.'</font>';
  echo '</h3>';
								
						 
?>

<div align="justify"><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Informe ou altere a quantidade de faltas para cada aluno:</font></div>
<br />
<table width="92%" border="0">
  <tr bgcolor="#666666">
                                          
    <td width="6%" align="center"><font color="#FFFFFF"><strong>Faltas</strong></font></td>
    <td width="6%" align="center"><font color="#FFFFFF"><b>&nbsp;Registro</b></font></td>
    <td width="88%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
  </tr>
  
<?php 

$st = '';
	
while($linha1 = pg_fetch_array($query1)) 
{
   $result = $linha1['ra_cnec'];
   $result2 = $linha1['nome'];

  	$result3 = '';

	@reset($qryFaltas);


	while(list($key, $value) = @each($qryFaltas)) {

       if($value['ra_cnec'] == $result) {
          
          $result3 = $value['faltas'];
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
            <input type="text" name="faltas['.$result.']" value="'.$result3.'" maxlength="1" size="1" onblur="validate(this);" onkeyup="return autoTab(this, 1, event);"/>
            </td>
            <td align="center"> ' . $result . ' </td>
            <td> ' . $result2 . ' </td>
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
