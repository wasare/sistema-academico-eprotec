<?php

include_once('../../conf/webdiario.conf.php');


$disciplina = $_POST['disc'];
$getofer = $_POST['ofer'];
$curso = $_POST['curso'];
$periodo = $_SESSION['periodo'];
$id = $_POST['id'];


$oferecida = $getofer;

$flag_falta = $_POST['flag_falta'];
$_SESSION['flag_falta'] = $flag_falta;

$aulatipo = $_POST['aulatipo'];
$conteudo = trim($_POST['conteudo']);

$_SESSION['conteudo'] = $conteudo;

$_SESSION['aulatipo'] = $aulatipo;

$num_aulas = $aulatipo[strlen($aulatipo) - 1];


if(!is_numeric($aulatipo) || (strlen($aulatipo) < 1 || strlen($aulatipo) > 8 ))
{
   echo '<script language=javascript> window.alert("Você deverá selecionar a quantidade de aulas para esta chamada.");     javascript:window.history.back(1);</script>';
   exit;
	
}
 
if($_POST['selectdia'] == "")
{
  echo '<font size=2><b>Voc&ecirc; n&atilde;o selecionou o DIA ! <a href="javascript:history.go(-1)
">Voltar</a>!</b></font>';
  exit;
}
else
{
  $selectdia = $_POST['selectdia'];
}

if($_POST['selectmes'] == "")
{
  echo '<font size=2><b>Voc&ecir;  n&atilde;o selecionou o M&Ecirc;S ! <a href="javascript:history.
go(-1)">Voltar</a>!</b></font>';
  exit;
}
else
{
  $selectmes = $_POST['selectmes'];
}


if($_POST['selectano'] == "")
{
  echo '<font size=2><b>Voc&ecirc; n&atilde;o selecionou o ANO ! <a href"javascript:history.go(-1)">Voltar</a>!</b></font>';
  exit;
}
else
{
  $selectano = $_POST['selectano'];
}


//VALIDAR CONTEUDO AQUI

if($conteudo == '')
{
  echo '<script language=javascript> window.alert("Você não informou o conteúdo da(s) aula(s)!"); javascript:window.history.back(1); </script>';
  exit;
}


$data_bd = $selectdia . '/' . $selectmes . '/'. $selectano;

//$data_cons = $selectdia . '/' . $selectmes . '/'. $selectano;
$data_cons = $selectano . '-' . $selectmes . '-'. $selectdia;


$data_ok = $selectdia . "/" . $selectmes . '/'. $selectano;
$data_chamada = $selectdia . "/" . $selectmes . '/'. $selectano;

$datadehoje = date ("d/m/Y");


/* SELECIONA A DATA PARA VERIFICA DUPLICADOS */
$sqld1 = 'SELECT dia, flag 
	  FROM
      diario_seq_faltas
      WHERE
      dia = \''.$data_cons.'\' AND
      periodo = \''.$periodo.'\' AND
      ref_disciplina_ofer = '.$getofer.';';
//
//id_prof = \''.$id.'\' AND
//disciplina = \''.$disciplina.'\' AND
//echo $sqld1; die;


$res = consulta_sql($sqld1);

if(!is_string($res))
{
    while($linha = pg_fetch_array($res))
    {
        $dia = $linha['dia'];
        $flag = $linha['flag'];
    }
}
else
{
	echo $res;
    exit;
}
			
if((@$flag > '0') AND (@$flag <= '8'))
{

	echo '<script language=javascript> window.alert("Já existe chamada realizada para esta data.");   javascript:window.history.back(1); </script>';
	//msgJaExiste();
	 die;
}
else {

	// NÃO HOUVE FALTAS PARA A CHAMADA
	if($flag_falta === 'F') {

		include_once('registra_faltas.php');
		exit;
	}
}
	

               
$sql1 = "SELECT
  matricula.ordem_chamada,
  pessoas.nome,
  pessoas.id,
  pessoas.ra_cnec
FROM
  matricula
  INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
WHERE
  (matricula.ref_periodo = '$periodo') AND
  (matricula.ref_disciplina_ofer = '$oferecida') AND 
  (matricula.dt_cancelamento is null)
ORDER BY
   lower(to_ascii(pessoas.nome));"; 
  
  //echo $sql1;
  //die;
  

$query1 = pg_exec($dbconnect, $sql1);


$sqlCurso = "SELECT DISTINCT
             d.ref_curso
         FROM
          disciplinas_ofer d
        WHERE
          d.ref_periodo = '$periodo' AND
          d.id = '$oferecida' AND
          d.is_cancelada = 0;";
//d.ref_disciplina = '$disciplina' AND


$qryCurso = consulta_sql($sqlCurso);

if(is_string($qryCurso))
{
   echo $qryCurso;
   exit;
}
else
{
    while ( $linha = pg_fetch_array($qryCurso) )
    {
        $curso = $linha['ref_curso'];
     }
}



?>


<html>
<head>
<a name="topo">
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php echo $CSS_DIR.'forms.css'; ?>" type="text/css">


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
		field.value = "<? echo $num_faltas; ?>";
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
ica, sans-serif"><strong>Lan&ccedil;amento de Faltas</strong></font></div></td>
  </tr>

</table>
<br />
<?php 
   print('<form name="form1" method="post" action="registra_faltas.php">');
   
   echo '<input type="hidden" name="id" id="id" value="' .$id.'">';
   echo '<input type="hidden" name="periodo" id="periodo" value="' . $periodo.'">';
   echo '<input type="hidden" name="disciplina" id="disc" value="' .$disciplina.'">';
   echo '<input type="hidden" name="oferecida" id="ofer" value="' . $oferecida.'">';
   echo '<input type="hidden" name="curso" id="curso" value="' . $curso.'">'; 
   echo '<input type="hidden" name="aulatipo" id="aulatipo" value="' . $aulatipo.'">';
   echo '<input type="hidden" name="num_aulas" id="num_aulas" value="' . $num_aulas.'">';
   echo '<input type="hidden" name="data_chamada" id="data_chamada" value="' . $data_chamada.'">';
   
  echo getHeaderDisc($oferecida);   

  echo '<h3>';
  echo 'Data da Chamada:<font color="blue"> '.$data_chamada.'</font>' ;
  echo '<br />Quantidade de Aulas: <font color="brown"> '.$num_aulas.'</font>';
  echo '</h3><a href="chamadas.php?id='.$id.'&getperiodo='. $periodo.'&disc='.$disciplina.'&ofer='.$oferecida.'">Alterar a Data e/ou Quantidade de Aulas</a><br />';

						 
?>
<br />
<div align="justify"><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Informe a quantidade de faltas, quando houver, para cada aluno:</font></div> <br />

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
   $result2 = $linha1["ra_cnec"];
   $result3 = $linha1["ordem_chamada"];
   $result = $linha1["nome"];
   
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
            <input type="text" name="faltas['.$result2.']" value="" maxlength="1" size="1" onblur="validate(this);" onkeyup="return autoTab(this, 1, event);"/>
            </td>
            <td align="center"> ' . $result2 . ' </td>
            <td> ' . $result . ' </td>
            </tr>');
}

?>
</table>
<br />


  <input type="submit" name="Submit" value="Salvar Chamada -->">
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" name="cancelar" value="Voltar" onClick="javascript:window.history.back(1);" />
  <input type="hidden" name="faltas_ok" value="<?php echo $_SESSION['flag_falta']; ?>" />
</form>

      <script type="text/javascript">
//<![CDATA[

      enterAsTab();

//]]>
      </script>
      
</body>
</html>
