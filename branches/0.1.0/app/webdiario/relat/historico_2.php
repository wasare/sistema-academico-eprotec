<?php
require_once('../webdiario.conf.php');

// VARS

$sql1 ="SELECT DISTINCT
               nome,
               id,
               ra_cnec
               FROM
               pessoas
               WHERE
               nome like '$nomealuno%'
               ORDER BY 1";
$query1 = pg_exec($dbconnect, $sql1);

?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<?php print('<form name="form1" method="post" action="historico_3.php">');?>
</p>
<p><font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Selecione 
  o aluno para gerar o Hist&oacute;rico Escolar</strong></font></p>
<table width="92%" border="0">
  <tr bgcolor="#666666"> 
    <td width="9%" align="center"><font color="#FFFFFF"><strong>Selecione o aluno:</strong></font></td>
    <td width="11%" align="center"><font color="#FFFFFF"><b>&nbsp;RA</b></font></td>
    <td width="80%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
  </tr>
  <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["ra_cnec"];
								   $result1 = $linha1["id"];
						           $result = $linha1["nome"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print (' <tr bgcolor="'.$st.'">
                                                       <td align="center">
                                                           <input type="checkbox" class="checkbox" name="nomes[]" value="'.$result2.'">
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
</table>
<br>

                        
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td>Data de Conclus&atilde;o do Curso: 
      <input name="dtconc" type="text" id="dtconc"></td>
    <td>Data de Cola&ccedil;&atilde;o de Grau : 
      <input name="dtcol" type="text" id="dtcol"></td>
  </tr>
  <tr> 
    <td>Data de Expedi&ccedil;&atilde;o do Diploma : 
      <input name="dtexp" type="text" id="dtexp"></td>
    <td>Carga Hor&aacute;ria Total do Curso : 
      <input name="chtotal" type="text" id="chtotal"></td>
  </tr>
  <tr> 
    <td colspan="2">Texto Adicional : 
      <input name="obs" type="text" id="obs" size="90"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>Pontua&ccedil;&atilde;o Vestibular : 
      <input name="ptvest" type="text" id="ptvest"></td>
    <td>Data Vestibular : 
      <input name="dtvest" type="text" id="dtvest"></td>
  </tr>
  <tr> 
    <td>Data Vestibular: 
      <input name="dtvest" type="text" id="dtvest"></td>
    <td>Local Vestibular: 
      <input name="locvest" type="text" id="locvest" size="50"></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

  
<input type="submit" name="Submit" value="Gerar Hist&oacute;rico Escolar">
</form>
</body>
</html>
