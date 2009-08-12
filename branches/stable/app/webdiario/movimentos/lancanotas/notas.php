<?php
include ('../../webdiario.conf.php');

// CONECT NO BANCO
//////////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

// VARS

$sql1 ="SELECT DISTINCT
               a.nome,
               a.id,
               a.ra_cnec,
               b.ordem_chamada
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
               ORDER BY b.ordem_chamada
               ";
$query1 = pg_exec($dbconnect, $sql1);
//   pg_close($dbconnect);
   
$sql9 ="SELECT
               a.descricao as cdesc,
               b.descricao_extenso,
               c.descricao as perdesc
               FROM
               cursos a,
               disciplinas b,
               periodos c
               WHERE
               a.id = '$getcurso' AND
               b.id = '$getdisciplina' AND
               c.id = '$getperiodo'";
$query9 = pg_exec($dbconnect, $sql9);
         while($linha9 = pg_fetch_array($query9)) {
               $exibecurso = $linha9["cdesc"];
               $exibedisc  = $linha9["descricao_extenso"];
               $exibeper   = $linha9["perdesc"];
                }
pg_close($dbconnect);
?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<?php print('<form name="form1" method="post" action="concluifaltas.php?id=' . $id. '&periodo=' . $getperiodo. '&curso=' . $getcurso. '&disciplina=' . $getdisciplina. '">');?> 
<table width="92%" border="0">
  <tr> 
    <td width="31%"><div align="center">Curso :</div></td>
    <td width="31%">
<div align="center">Disciplina :</div></td>
    <td width="31%">
<div align="center">Periodo :</div></td>
  </tr>
  <tr> 
    <td><div align="center"><strong><?php print ($exibecurso); ?></strong></div></td>
    <td><div align="center"><strong><?php print ($exibedisc); ?></strong></div></td>
    <td><div align="center"><strong><?php print ($exibeper); ?></strong></div></td>
  </tr>
</table>
<p>Dia da chamada:&nbsp; 
  <?php
    $dia=gmDate("d");
    print ("<select name=\"selectdia\" style=\"width:40px\">
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
  </select>"); ?>
  &nbsp;Mês&nbsp; 
  <?php
      $mes=gmDate("m");
      switch ($mes) {
           case "01":
           $mesdesc = "Janeiro";
           break;
           case "02":
           $mesdesc = "Fevereiro";
           break;
           case "03":
           $mesdesc = "Março";
           break;
           case "04":
           $mesdesc = "Abril";
           break;
           case "05":
           $mesdesc = "Maio";
           break;
           case "06":
           $mesdesc = "Junho";
           break;
           case "07":
           $mesdesc = "Julho";
           break;
           case "08":
           $mesdesc = "Agosto";
           break;
           case "09":
           $mesdesc = "Setembro";
           break;
           case "10":
           $mesdesc = "Outubro";
           break;
           case "11":
           $mesdesc = "Novembro";
           break;
           case "12":
           $mesdesc = "Dezembro";
           break;
           }  ;
    print ("<select name=\"selectmes\" style=\"width:100px\">
    <option></option>
    <option value=\"$mes\" selected>$mesdesc</option>
    <option value=\"01\">Janeiro</option>
    <option value=\"02\">Fevereiro</option>
    <option value=\"03\">Março</option>
    <option value=\"04\">Abril</option>
    <option value=\"05\">Maio</option>
    <option value=\"06\">Junho</option>
    <option value=\"07\">Julho</option>
    <option value=\"08\">Agosto</option>
    <option value=\"09\">Setembro</option>
    <option value=\"10\">Outubro</option>
    <option value=\"11\">Novembro</option>
    <option value=\"12\">Dezembro</option>
  </select>"); ?>
</p>
<table width="44%" border="0">
  <tr> 
    <td colspan="2"><div align="center">Selecione qual a aula que ser&aacute; 
        realizada a chamada :</div></td>
  </tr>
  <tr> 
    <td><div align="center">1&ordf; Aula</div></td>
    <td><div align="center">2&ordf; Aula</div></td>
  </tr>
  <tr> 
    <td><div align="center"> 
        <input name="aula1" type="checkbox" class="checkbox" value="aula1">
      </div></td>
    <td><div align="center"> 
        <input type="checkbox" name="aula2" class="checkbox" value="aula2">
      </div></td>
  </tr>
</table>
</p>
<table width="92%" border="0">
  <tr bgcolor="#666666">
                                          
    <td width="9%" align="center"><font color="#FFFFFF"><strong>Falta</strong></font></td>
    <td width="6%" align="center"><font color="#FFFFFF"><strong>N°</strong></font></td>
                                          <td width="10%"><font color="#FFFFFF"><b>&nbsp;RA</b></font></td>
                                          <td width="76%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
                                      </tr>
                       <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["ra_cnec"];
                                   $result3 = $linha1["ordem_chamada"];
						           $result = $linha1["nome"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print (' <tr bgcolor="'.$st.'">
                                                       <td align="center">
                                                           <input type="checkbox" class="checkbox" name="nomes[]" value="'.$result2.'">
                                                       </td>
                                                       <td align="center">
                                                           '.$result3.'
                                                       </td>
                                                       <td>
                                                           '.$result2.'
                                                       </td>
                                                       <td>
                                                           '.$result.'
                                                       </td>
                                                   </tr>');
					   }
                    	?>
                        </table><br>

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Conte&uacute;do dado em aula:</td>
  </tr>
  <tr>
    <td><textarea name="conteudo" cols="70" rows="5"></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
 </table>

  <input type="submit" name="Submit" value="Concluir Chamada -->">
</form>
</body>
</html>