<?php
include ('../conf/webdiario.conf.php');

// CONECT NO BANCO
/////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

// VARS

$sql9 = "SELECT
         a.descricao as cdesc,
         b.descricao_extenso,
         c.descricao as perdesc,
         d.ref_curso
         FROM
          cursos a,
          disciplinas b,
          periodos c,
          disciplinas_ofer d  where
          d.ref_periodo = '$getperiodo' AND
          b.id = '$getdisciplina' AND
          c.id = '$getperiodo' AND
          a.id = d.ref_curso";

$query9 = pg_exec($dbconnect, $sql9);
         while($linha9 = pg_fetch_array($query9)) {
               $getcurso   = $linha9["ref_curso"];
                }


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
               c.ref_disciplina_ofer = b.ref_disciplina_ofer AND
               b.dt_cancelamento isnull
               ORDER BY a.nome
               ";
$query1 = pg_exec($dbconnect, $sql1);
   pg_close($dbconnect);
?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<?php print('<form name="form1" method="post" action="verfaltas.php?id=' . $id. '&periodo=' . $getperiodo. '&curso=' . $getcurso. '&disciplina=' . $getdisciplina. '">');?>
                              <table width="100%" border="0">
  <tr>
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Consulta 
        de Faltas</strong></font></div></td>
  </tr>
</table>
<table width="100%" border="0">
                                      <tr bgcolor="#666666">
                                          <td width="4%" align="center"><font color="#FFFFFF">&nbsp;</font></td>
                                          <td width="10%"><font color="#FFFFFF"><b>&nbsp;RA</b></font></td>
                                          <td width="84%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
                                      </tr>
                       <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["ra_cnec"];
						           $result = $linha1["nome"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print (' <tr bgcolor="'.$st.'">
                                                       <td>
                                                           <input type="radio" class="checkbox" name="ra_cnec_s" value="'.$result2.'">
                                                       </td>
                                                       <td>
                                                           '.$result2.'
                                                       </td>
                                                       <td>
                                                           '.$result.'
                                                       </td>
                                                   </tr>');
					   }
                    	?></table><br>
     <input type="radio" name="ra_cnec_s" class="checkbox" value="dia"><b>Por data da chamada:</b>&nbsp;
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
<p><input type="radio" name="ra_cnec_s" value="periodo"><b>Pelo periodo 
			       de 
                       <select name="periododia" style="width:40px">
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
                               <option value="31">31</option></select>&nbsp;
                               <select name="periodomes" style="width:100px">
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
                               <option value="12">Dezembro</option></select> a                        <select name="periododia2" style="width:40px">
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
                               <option value="31">31</option></select>&nbsp;
                               <select name="periodomes2" style="width:100px">
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

  <input type="submit" name="Submit" value="Pr&oacute;ximo">
</form>
</body>
</html>
