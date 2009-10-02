<?php
include ('../../webdiario.conf.php');

// CONECT NO BANCO

$sql1 = "SELECT
                formula
                FROM
                diario_formulas
                WHERE
                grupo = '$grupo'";
$query1 = pg_exec($dbconnect, $sql1);
          $row1 = pg_fetch_array($query1);
          $formula = $row1['formula'];


pg_close($dbconnect);
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
</head>

<body>
<?PHP print('<form action="executa_testaformula.php?grupo='.$grupo.'&id='.$id.'&getcurso='.$getcurso.'&getdisciplina='.$getdisciplina.'&getperiodo='.$getperiodo.'" method="post">'); ?>
<table width="98%" border="0">
  <tr> 
    <td colspan="3"><div align="center"><font color="#0000FF" size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong>TESTE 
        DE F&Oacute;RMULAS</strong></font></div></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="10%"><div align="left"><strong>F&oacute;rmula:</strong></div></td>
    <td colspan="2" bgcolor="#000000"> <div align="center"><font color="#FFFFFF" size="2"><strong><?PHP print $formula; ?></strong></font></div></td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr bgcolor="#666666"> 
    <td><font color="#FFFFFF">Prova</font></td>
    <td colspan="2"><font color="#FFFFFF">Nota</font></td>
  </tr>
  <?PHP
  $numr = pg_num_rows($query1);
  for ($cont=1; $cont <= $numr; $cont++) {
  print('
  <tr bgcolor="#CCCCCC"> 
    <td><strong>Nota (<font color="#FF0000" size="2">P'.$cont.'</font>):</strong></td>
    <td colspan="2"> <input type="text" name="notadaprova[]"></td>
  </tr>');
                               }
  ?>
  
  <tr> 
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="65%"><input type="submit" name="Submit" value="Calcular"></td>
  </tr>
  </form>
</table>
</body>
</html>
