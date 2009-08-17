<?php

require_once('../webdiario.conf.php');


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_GET['id'];


$sql1 ="SELECT DISTINCT
               a.nome,
               a.ra_cnec
               FROM
               pessoas a,
               matricula b,
               disciplinas_ofer_prof c
               WHERE
               c.ref_professor = '$id' AND
               b.ref_periodo = '$getperiodo' AND
               b.ref_disciplina = '$getdisciplina' AND
               b.ref_pessoa = a.id AND
               c.ref_disciplina_ofer = b.ref_disciplina_ofer
               ORDER BY a.nome; ";

			   
$query1 = pg_exec($dbconnect, $sql1);

pg_close($dbconnect);

?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<?php print('<form name="form1" method="post" action="concluifaltas.php?id=' . $id. '&periodo=' . $getperiodo. '&curso=' . $getcurso. '&disciplina=' . $getdisciplina. '">');?>
                       <font size="2"><b>Dia da chamada:</b>&nbsp;
                       <select name="selectdia" style="width:40px">
                               <option></option>
                               <option value="01">01</option>
                               <option value="02">02</option>
                               <option value="03">03</option>
                               <option value="04">04</option>
                               <option value="05">05</option>
                               <option value="06">06</option>
                               <option value="07">07</option>
                               <option value="08">08</option>
                               <option value="09">09</option>
                               <option value="10">10</option>
                               <option value="11">11</option>
                               <option value="12">12</option>
                               <option value="13">13</option>
                               <option value="14">14</option>
                               <option value="15">15</option>
                               <option value="16">16</option>
                               <option value="17">17</option>
                               <option value="18">18</option>
                               <option value="19">19</option>
                               <option value="20">20</option>
                               <option value="21">21</option>
                               <option value="22">22</option>
                               <option value="23">23</option>
                               <option value="24">24</option>
                               <option value="25">25</option>
                               <option value="26">26</option>
                               <option value="27">27</option>
                               <option value="28">28</option>
                               <option value="29">29</option>
                               <option value="30">30</option>
                               <option value="31">31</option></select>&nbsp;<b>Mês</b>&nbsp;
                               <select name="selectmes" style="width:100px">
                               <option></option>
                               <option value="01">Janeiro</option>
                               <option value="02">Fevereiro</option>
                               <option value="03">Março</option>
                               <option value="04">Abril</option>
                               <option value="05">Maio</option>
                               <option value="06">Junho</option>
                               <option value="07">Julho</option>
                               <option value="08">Agosto</option>
                               <option value="09">Setembro</option>
                               <option value="10">Outubro</option>
                               <option value="11">Novembro</option>
                               <option value="12">Dezembro</option></select></p>
                               <table width="100%" border="0">
                                      <tr bgcolor="#666666">
                                          <td width="4%" align="center"><font color="#FFFFFF">1ª Aula</font></td>
                                          <td width="4%" align="center"><font color="#FFFFFF">2ª Aula</font></td>
                                          <td width="10%"><font color="#FFFFFF"><b>&nbsp;RA</b></font></td>
                                          <td width="80%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
                                      </tr>
                       <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["ra_cnec"];
						           $result = $linha1["nome"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print (' <tr bgcolor="'.$st.'">
                                                       <td>
                                                           <input type="checkbox" class="checkbox" name="nomes[]" value="$result2">
                                                       </td>
                                                       <td>
                                                           <input type="checkbox" class="checkbox" name="nomes2[]" value="$result2">
                                                       </td>
                                                       <td>
                                                           '.$result2.'
                                                       </td>
                                                       <td>
                                                           '.$result.'
                                                       </td>
                                                   </tr>');
					   }
                    	?></table>
  <input type="submit" name="Submit" value="Pr&oacute;ximo">
</form>
</body>
</html>
