<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/forms.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <form name="form1" method="post" action="cformula_step_3.php">
    <tr> 
      <td colspan="2"><div align="center"><font color="#0000CC" size="5" face="Arial, Helvetica, sans-serif"><strong>INCLUS&Atilde;O 
          DE AVALIA&Ccedil;&Otilde;ES</strong></font></div></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"><font color="#FF0000" size="2"><strong>Passo 2 :</strong></font></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"><strong>Voc&ecirc; selecionou <font color="#FF0000"><?PHP print($numprovas); ?></font> 
        Provas, se desejar coloque uma breve descri&ccedil;&atilde;o.</strong></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td width="9%" bgcolor="#CCCCCC">Prova :</td>
      <td width="91%" bgcolor="#CCCCCC"> Descri&ccedil;&atilde;o:</td>
    </tr>

<?php
   
for ($cont=1; $cont <= $numprovas; $cont++) 
{
   print('<tr bgcolor="#E6E6E6"> 
      <td><div align="center"><strong><font color="#FF0000" size="2"><em>P'.$cont.'</em></font></strong></div></td>
      <td><input name="prova[]" type="text" value="Prova '.$cont.'" size="80" maxlength="80"></td>
    </tr>'); 
}
	
    print("<input type=\"hidden\" name=\"numprovas\" value=\"$numprovas\">");
    print("<input type=\"hidden\" name=\"id\" value=\"$id\">");
    print("<input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">");
    print("<input type=\"hidden\" name=\"getcurso\" value=\"$getcurso\">");
    print("<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">");

?>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2"> <div align="center"> 
          <input type="submit" name="Submit" value="Pr&oacute;ximo...">
        </div></td>
    </tr>
  </form>
</table>
</body>
</html>
