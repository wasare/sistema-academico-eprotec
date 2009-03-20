<?PHP
include ('../../webdiario.conf.php');


$grupo = ($id."-".$getperiodo."-".$getdisciplina);

$sqldel = "delete from diario_formulas where grupo='$grupo'";
$querydel =  pg_exec($dbconnect, $sqldel);

$sqldel1 = "delete from diario_notas where rel_diario_formulas_grupo ='$grupo'";

$querydel1 =  pg_exec($dbconnect, $sqldel1);

reset($prova);

while (list($index,$value) = each($prova)) 
{
   $descricao_prova = $prova[$index];
   $num_prova=($index+1);
   $frm='P1';
   $sql1 = "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values('$id','$getperiodo','$getdisciplina','$num_prova','$descricao_prova','$frm','$grupo')";
   
   $query1 =  pg_exec($dbconnect, $sql1);
//print ("<BR>".$sql1."<BR>");

}

/*  print("\n".$numprovas);
    print("\n".$id);
    print("\n".$getperiodo);
    print("\n".$getcurso);
    print("\n".$getdisciplina);
    print("\n".$grupo);    */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>INCLUS&Atilde;O DE AVALIA&Ccedil;&Otilde;ES</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/forms.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><div align="center"><font color="#0000CC" size="5" face="Arial, Helvetica, sans-serif"><strong>CONSTRUTOR 
        DE F&Oacute;RMULAS</strong></font></div></td>
  </tr>
</table>
  <form name="form1" method="post" action="processaformula.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"><font color="#FF0000" size="2"><strong>Passo 3 :</strong></font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
      <td colspan="4">Voc&ecirc; cadastrou o Total de <font color="#FF0000"><strong><?PHP print($numprovas); ?></strong></font> 
        Provas, para fazer a f&oacute;rmula ultilize P1 at&eacute; <?PHP print("P".$numprovas); ?>.</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
      <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td width="0%">&nbsp;</td>
    <p>&nbsp;</p>
    <td colspan="4"> <div align="center">Ultilize o construtor de formulas para 
        gerar a m&eacute;dia semestral</div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"><div align="center"><strong><font color="#FF0000">Exemplo: 
        ((P1+P2+P3)/3)+P4</font></strong></div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <div align="center">No exemplo acima foi ultilizado 3 provas 
        (P1, P2, P3) e mais 1 trabalho (P4)</div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td width="19%">F&oacute;rmula para C&aacute;lculo :</td>
    <td colspan="3"> <input name="formula" type="text" size="70"> </td>
<?php
    print("<input type=\"hidden\" name=\"numprovas\" value=\"$numprovas\">");
    print("<input type=\"hidden\" name=\"id\" value=\"$id\">");
    print("<input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">");
    print("<input type=\"hidden\" name=\"getcurso\" value=\"$getcurso\">");
    print("<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">");
    print("<input type=\"hidden\" name=\"grupo\" value=\"$grupo\">");
?>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp; </td>
    <td colspan="3">Construa a f&oacute;rmula de acordo com o n&uacute;mero de 
      provas P1.. P2.. Etc. </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="7%"><input type="submit" name="Submit" value="Incluir"></td>
    <td width="59%">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
  
</form>
<p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
</html>
