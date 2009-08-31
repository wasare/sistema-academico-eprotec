<?php

include_once('../../webdiario.conf.php');

if (is_finalizado($_GET['ofer'])){
    header("Location: $erro");
    exit;
}

$getdisciplina = $_GET['disc'];

$getofer = $_GET['ofer'];

$getperiodo = $_SESSION['periodo'];


$sql1 ="SELECT DISTINCT
  matricula.ordem_chamada,
  pessoas.nome,
  pessoas.id,
  pessoas.ra_cnec
FROM
  matricula
  INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
WHERE
  (matricula.ref_periodo = '$getperiodo') AND
  (matricula.ref_disciplina_ofer = '$getofer') AND 
  (matricula.dt_cancelamento is null)
ORDER BY
  matricula.ordem_chamada; ";
// (matricula.ref_disciplina = '$getdisciplina') AND


//echo $sql1;
/*exit;
*/

$query1 = pg_exec($dbconnect, $sql1);


$sqlCurso = "SELECT DISTINCT
             d.ref_curso
         FROM
          disciplinas_ofer d
        WHERE
          d.ref_periodo = '$getperiodo' AND
          d.id = '$getofer' AND
          d.is_cancelada = '0';";

//d.ref_disciplina = '$getdisciplina' AND

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
<script src="../js/event-listener.js" type="text/javascript"></script>
<script src="../js/enter-as-tab.js" type="text/javascript"></script>

</head>
<body onLoad="javascript:document.form1.reset()">

<table width="90%" height="73" border="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvet
ica, sans-serif"><strong>Lan&ccedil;amento de Chamadas</strong></font></div></td>
  </tr>

 <tr>
    <td height="14"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><st
rong>Observa&ccedil;&otilde;es:<br />
    * As op&ccedil;&otilde;es com mais de uma aula se referem &agrave; aulas seguidas.<br />
    * N&atilde;o pode haver mais de uma chamada no mesmo dia para o mesmo di&aacute;rio.</strong></font></td>
  </tr>
</table>
<br />
<?php 
  
  print('<form name="form1" method="post" action="lanca_faltas.php">');

  echo '<input type="hidden" name="id" id="id" value="' .$_SESSION['id'].'">';
  echo '<input type="hidden" name="periodo" id="periodo" value="' . $getperiodo.'">';
  echo '<input type="hidden" name="disc" id="disc" value="' .$getdisciplina.'">';
  echo '<input type="hidden" name="ofer" id="ofer" value="' . $getofer.'">';
  echo '<input type="hidden" name="curso" id="curso" value="' . $curso.'">';

  echo getHeaderDisc($_GET['ofer']);   

?>


<p><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Dia da chamada:</font>&nbsp; 

<?php
  
    $dia = date("d");
    echo "<select name=\"selectdia\" style=\"width:40px\">
    <option></option>
    <option value=\"$dia\" selected>$dia</option>
    <option value=\"01\">01</option>
    <option value=\"02\">02</option>
    <option value=\"03\">03</option>
    <option value=\"04\">04</option>
    <option value=\"05\">05</option>
    <option value=\"06\">06</option>
    <option value=\"07\">07</option>
    <option value=\"08\">08</option>
    <option value=\"09\">09</option>
    <option value=\"10\">10</option>
    <option value=\"11\">11</option>
    <option value=\"12\">12</option>
    <option value=\"13\">13</option>
    <option value=\"14\">14</option>
    <option value=\"15\">15</option>
    <option value=\"16\">16</option>
    <option value=\"17\">17</option>
    <option value=\"18\">18</option>
    <option value=\"19\">19</option>
    <option value=\"20\">20</option>
    <option value=\"21\">21</option>
    <option value=\"22\">22</option>
    <option value=\"23\">23</option>
    <option value=\"24\">24</option>
    <option value=\"25\">25</option>
    <option value=\"26\">26</option>
    <option value=\"27\">27</option>
    <option value=\"28\">28</option>
    <option value=\"29\">29</option>
    <option value=\"30\">30</option>
    <option value=\"31\">31</option>
  </select>"; 
?>

&nbsp;<font color="#0000FF" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">M&ecirc;s</font>&nbsp; 
<select name="selectmes" style="width:100px;">
<?php
	$mes_atual = date("m");
	if($mes_atual == 1) echo "<option value='$mes_atual' selected>Janeiro</option>";
	if($mes_atual == 2) echo "<option value='$mes_atual' selected>Fevereiro</option>";
	if($mes_atual == 3) echo "<option value='$mes_atual' selected>Mar&ccedil;o</option>";
	if($mes_atual == 4) echo "<option value='$mes_atual' selected>Abril</option>";
	if($mes_atual == 5) echo "<option value='$mes_atual' selected>Maio</option>";
	if($mes_atual == 6) echo "<option value='$mes_atual' selected>Junho</option>";
	if($mes_atual == 7) echo "<option value='$mes_atual' selected>Julho</option>";
	if($mes_atual == 8) echo "<option value='$mes_atual' selected>Agosto</option>";
	if($mes_atual == 9) echo "<option value='$mes_atual' selected>Setembro</option>";
	if($mes_atual == 10) echo "<option value='$mes_atual' selected>Outubro</option>";
	if($mes_atual == 11) echo "<option value='$mes_atual' selected>Novembro</option>";
	if($mes_atual == 12) echo "<option value='$mes_atual' selected>Dezembro</option>";
?>
  <option value="1">Janeiro</option>
  <option value="2">Fevereiro</option>
  <option value="3">Mar&ccedil;o</option>
  <option value="4">Abril</option>
  <option value="5">Maio</option>
  <option value="6">Junho</option>
  <option value="7">Julho</option>
  <option value="8">Agosto</option>
  <option value="9">Setembro</option>
  <option value="10">Outubro</option>
  <option value="11">Novembro</option>
  <option value="12">Dezembro</option>
</select>

<?php
	
	$meses = array("Janeiro","Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
	
	$DataPeriodo = getAnoPeriodo($_SESSION['periodo']);

	//print_r($DataPeriodo);

	$DataIni = explode("-", $DataPeriodo['0']);
	$DataFim = explode("-", $DataPeriodo['1']);

	$MesIni = $DataIni['1'];
	$MesFim = $DataFim['1'];
	
	$AnoIni = $DataIni['0'];
	$AnoFim = $DataFim['0'];
	
  /*
    echo '<select name="selectmes" style="width:100px">';
	
    if($MesIni < $MesFim) 
	{
	    //echo " <option value=\"$mes\" selected>$mesdesc</option>";

		for ($i = $MesIni; $i <= $MesFim; ++$i)
    	{
          echo ' <option value="'.$i.'">'.$meses[$i-1].'</option>';
    	}
    }
    else
    {
		// aqui sera ¡tratada a condicao de um periodo iniciar e terminar em anos diferentes
		//echo " <option value=\"$mes\" selected>$mesdesc</option>";
    }

	echo '</select>';
	
	*/
   echo '<select name="selectano" id="selectano" >';
     	
   if($AnoIni != $AnoFim)
    {
	     echo " <option value=\"$AnoIni\" selected>$AnoIni</option>";
 		 echo " <option value=\"$AnoFim\">$AnoFim</option> </select>";
    }
    else
    {
		echo " <option value=\"$AnoFim\" selected>$AnoFim</option> </select>";
    }
  
?>
</p>

<table width="90%" height="73" border="0">
  <tr> 
    <td height="18"> 
      <div align="justify"><font color="#0000CC" size="1,5" face="Verdana, Arial, Helvetica, sans-serif">Selecione para quantas Aulas ser&aacute; efetuada a chamada :</font></div></td>
  </tr>
<tr> 
   <td><div align="center"></div>
      <div align="left"> 
        <select name="aulatipo" id="aulatipo" style="width:400px">
        <option>--- quantidade de aulas ---</option>
		<option value="1" <?php if($_SESSION['aulatipo'] == "1") { echo 'selected="selected"';} ?>>1 Aula</option>
		<option value="12" <?php if($_SESSION['aulatipo'] == "12") { echo 'selected="selected"';} ?>>2 Aulas  (Aula Dupla)</option>
		<option value="123" <?php if($_SESSION['aulatipo'] == "123") { echo 'selected="selected"';} ?>>3 Aulas (Aula Tripla)</option>
		<option value="1234" <?php if($_SESSION['aulatipo'] == "1234") { echo 'selected="selected"';} ?>>4 Aulas (Aula Qu&aacute;drupla)</option>
		<option value="123456" <?php if($_SESSION['aulatipo'] == "123456") { echo 'selected="selected"';} ?>>6 Aulas (6 Aulas seguidas)</option>
		<option value="12345678" <?php if($_SESSION['aulatipo'] == "12345678") { echo 'selected="selected"';} ?>>8 Aulas (8 Aulas seguidas)</option>
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
	<td><textarea name="conteudo" cols="70" rows="5"><?php echo $_SESSION['conteudo']; ?></textarea></td>
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
	<font color="brown"><b>N&atilde;o houve faltas neste dia (marque esta op&ccedil;&atilde;o caso n&atilde;o exista faltas neste dia).</b></font>

	<br /> <br /> 
	<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
	* As faltas desta chamada dever&atilde;o ser informadas no pr&oacute;ximo passo.
	</font>
    <br />			
	<br />

    <input type="submit" name="Submit" value="Prosseguir -->">

  <br />
  
</form>

      <script type="text/javascript">
//<![CDATA[

      enterAsTab();

//]]>
      </script>
      
</body>
</html>
