<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/forms.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">

<?php  

   print('<form name="form1" method="post" action="cformula_step_2.php?id=' . $id. '&getperiodo=' . $getperiodo. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina.'">'); 

?>
  <tr> 
    <td colspan="4"><div align="center"><font color="#0000CC" size="5" face="Arial, Helvetica, sans-serif"><strong>INCLUS&Atilde;O 
        DE AVALIA&Ccedil;&Otilde;ES</strong></font></div></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4"><font color="#FF0000" size="2"><strong>Passo 1 :</strong></font></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td width="41%">Informe o n&uacute;mero de provas / notas total do semestre 
      :</td>
    <td colspan="3"> 
    <select name="numprovas">
        <option value="1">1 Prova (P1)</option>
        <option value="2">2 Provas (P1..P2)</option>
        <option value="3">3 Provas (P1..P3)</option>
        <option value="4">4 Provas (P1..P4)</option>
        <option value="5">5 Provas (P1..P5)</option>
        <option value="6">6 Provas (P1..P6)</option>
        <option value="7">7 Provas (P1..P7)</option>
        <option value="8">8 Provas (P1..P8)</option>
        <option value="9">9 Provas (P1..P9)</option>
        <option value="10">10 Provas (P1..P10)</option>
        <option value="11">11 Provas (P1..P11)</option>
        <option value="12">12 Provas (P1..P12)</option>
        <option value="13">13 Provas (P1..P13)</option>
        <option value="14">14 Provas (P1..P14)</option>
        <option value="15">15 Provas (P1..P15)</option>
        <option value="16">16 Provas (P1..P16)</option>
        <option value="17">17 Provas (P1..P17)</option>
        <option value="18">18 Provas (P1..P18)</option>
        <option value="19">19 Provas (P1..P19)</option>
        <option value="20">20 Provas (P1..P20)</option>
        <option value="21">21 Provas (P1..P21)</option>
        <option value="22">22 Provas (P1..P22)</option>
        <option value="23">23 Provas (P1..P23)</option>
        <option value="24">24 Provas (P1..P24)</option>
        <option value="25">25 Provas (P1..P25)</option>
        <option value="26">26 Provas (P1..P26)</option>
        <option value="27">27 Provas (P1..P27)</option>
        <option value="28">28 Provas (P1..P28)</option>
        <option value="29">29 Provas (P1..P29)</option>
        <option value="30">30 Provas (P1..P30)</option>
      </select> </td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4">
        <div align="center">
          <input type="submit" name="Submit" value="Pr&oacute;ximo...">
        </div>
      </td>
  </tr>
  </form>
</table>
</body>
</html>
