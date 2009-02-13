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
    <td><div align="center"><font color="#0000CC" size="5" face="Arial, Helvetica, sans-serif"><strong>INCLUS&Atilde;O 
        DE AVALIA&Ccedil;&Otilde;ES</strong></font></div></td>
  </tr>
</table>
<p>
<?php
/*
====================================
DESENVOLVIDO SOBRE LEIS DA GNU/GPL
====================================

E-CNEC : ti@cneccapviari.br

CNEC CAPVIARI - www.cneccapivari.br
Rua Barão do Rio Branco, 347, Centro - Capivari/SP
Tel.: (19)3492-1869
*/
$st = '#F3F3F3';
include_once('../../conf/webdiario.conf.php');

// CONECT NO BANCO

// VARS

$sql1 = "SELECT
                id,
                descricao_resumida,
                descricao,
                peso
                FROM
                diario_avaliacao
                WHERE
                id_ref_pessoas = '$id' AND
                id_ref_curso = '$getcurso' AND
                id_ref_periodos = '$getperiodo' AND
                id_ref_disciplina = '$getdisciplina'";

$query1 = pg_exec($dbconnect, $sql1);

if((isset($id)) && (isset($getcurso)) && (isset($getperiodo)) && (isset($getdisciplina))) 
{
  if (pg_numrows($query1) > 0) 
   { 
?>
      <table width="400">
      <tr bgcolor="#CCCCCC">
      <td width="30"><b>Peso</b></td>
      <td width="300"><b>Descricao</b></td>
      <td width="20"><b>Acao</b></td>
    </tr>
<?php
    print("<input type=\"hidden\" name=\"id\" value=\"$id\">
    <input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">
	<input type=\"hidden\" name=\"getcurso\" value=\"$getcurso\">
	<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">");
   while ($row1 = pg_fetch_array($query1)) 
   {
      if ($st == '#F3F3F3') 
      {
         $st = '#E3E3E3';
      } 
      else 
      {
         $st ='#F3F3F3';
      }
    $qid = $row1['id'];
    $qdesc = $row1['descricao_resumida'];
    $qpeso = $row1['peso'];
    print ("<tr bgcolor=\"$st\">
				<td>$qpeso</td>
				<td>$qdesc</td>
				<td>
                        <a href=\"apaga_prova_cad.php?flag=$id&id=$qid&getcurso=$getcurso&getdisciplina=$getdisciplina&getperiodo=$getperiodo\">
						<div align=\"center\"><img src=\"../../img/erase.gif\" border=\"0\" title=\"APAGAR PROVA\"></div>
                        </td>
			</tr>");
    }
    } else {
    print ("<strong><font color=\"#FF0000\" size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">N&atilde;o existe matérias ou provas cadastradas !</font></strong>");
           }
    pg_close($dbconnect);
    }
    else 
    {
      print ("A consulta Falhou ! , Contate o suporte técnico !");
      print ($id);
      print ($getperiodo);
      print ($getcurso);
      print ($getdisciplina);
   }
      
?>
</p>
<?php
 print('<form name="inclui" method="post" action="incluiprovas.php?id=' . $id. '&getperiodo=' . $getperiodo. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina.'">');  
?>
  <p>&nbsp; </p>
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="0%">&nbsp;</td>
    <p>&nbsp;</p>
    <td colspan="4"> <div align="center">SISTEMA DE M&Eacute;DIA PONDERADA </div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <div align="center">Soma (nota x peso) / Soma dos pesos</div></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td width="16%">Descri&ccedil;&atilde;o Resumida: </td>
    <td colspan="3"><input name="descres" type="text" id="descres" maxlength="10"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>Descri&ccedil;&atilde;o:</td>
    <td colspan="3"><input name="descricao" type="text" id="descricao" size="60" maxlength="40"></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>Peso:</td>
    <td colspan="3"><input name="peso" type="text" id="peso" size="4" maxlength="3">
      Ultilize ponto como seperador decimal ex: 2.2</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="5%"><input type="submit" name="Submit" value="Incluir"></td>
    <td width="69%">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4">
<?php
 print ('<a href="lanca3.php?id=' . $id. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina. '&getperiodo=' . $getperiodo. '">Voltar para inclusao de notas</a>'); 
?>
</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
</html>
