<?php
include_once('../../webdiario.conf.php');

$var = explode(":",$_GET[getdisciplina]);
$getdisciplina = $var[0];
$getofer = $var[1];

///////////$sql1="select d.id, d.descricao_disciplina, d.descricao_extenso, d.carga_horaria from disciplinas d, disciplinas_ofer o where o.ref_periodo = '$getperiodo' and d.id = o.ref_disciplina order by d.descricao_disciplina";
$sql1 = "SELECT DISTINCT 
         d.id, d.descricao_disciplina, d.descricao_extenso || '  ' || '(' || o.id || ')' as descricao_extenso, d.carga_horaria 
         FROM disciplinas d, disciplinas_ofer o 
         WHERE o.ref_periodo = '$getperiodo' 
         AND d.id = '$getdisciplina' 
         AND o.id = '$getofer'
         ORDER BY d.descricao_disciplina";

//echo $sql1.'<br />';

$query1 = pg_exec($dbconnect, $sql1);
//$sql2="SELECT p.nome, d.descricao_disciplina, COUNT(c.ref_disciplina) AS faltas, ROUND (SUM(n.nota * n.peso)/SUM(n.peso),1) AS nota  FROM disciplinas d LEFT OUTER JOIN matricula m ON m.ref_periodo = '$getperiodo'AND m.ref_disciplina = d.id LEFT OUTER JOIN pessoas p ON p.id = m.ref_pessoa LEFT OUTER JOIN diario_chamadas c ON c.ref_disciplina = m.ref_disciplina AND c.ra_cnec = p.ra_cnec AND c.abono <> 'S' LEFT OUTER JOIN diario_notas n ON n.ra_cnec = p.ra_cnec AND n.d_ref_disciplina = m.ref_disciplina GROUP BY p.nome, d.descricao_disciplina, m.ordem_chamada, m.ref_periodo HAVING m.ref_periodo = '$getperiodo' order by m.ordem_chamada, d.descricao_disciplina";

$sql2 = "SELECT DISTINCT 
         p.nome, m.ordem_chamada, m.ref_pessoa 
         FROM matricula m, pessoas p 
         WHERE m.ref_periodo = '$getperiodo' 
         AND p.id = m.ref_pessoa 
         AND m.ref_disciplina_ofer = '$getofer'
         AND m.dt_cancelamento ISNULL 
         ORDER BY 2 ";

// $sql2 = "select count(ra_cnec) as faltas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(ref_disciplina) as disc from diario_chamadas where ref_periodo = '$getperiodo' and abono <> 'S' group by nome, disc order by nome, disc";

//echo $sql2;

//$sql2 = "select count(ra_cnec) as faltas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(ref_disciplina) as disc from diario_chamadas where ref_periodo = '$getperiodo' and abono <> 'S' group by nome, disc order by nome, disc";



$query2 = pg_exec($dbconnect, $sql2);

// $sqlfaltas = "select count(ra_cnec) as faltas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(ref_disciplina) as disc from diario_chamadas where ref_periodo = '$getperiodo' group by nome, disc";


?>
<html>
<head>
<title>Consulta de Notas e Faltas por Classe</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
<script languague="JavaScript" type="text/JavaScript">
<!--

function MM_openBrWindow(theUrl,winName,features) { //2.1
	window.open(theUrl,winName,features);
}
//-->
</script>
</head>
<body 

<?php 
      
   if ($botao == "1") 
   { 
?>
   onLoad="window.print()"
<?php
}
?>

<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<div align="center">
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>CEFET-BAMBU&Iacute;<br>
 CONSULTA DE NOTAS E FALTAS SELECIONADAS POR DISCIPLINA</strong></font></p>
      <p align="left"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">PER&Iacute;ODO: <?echo getNomePeriodo($getperiodo);?></font></strong></p>
<?php
      
if($botao != "1") 
{
   
?>
<input type="submit" value="Imprimir" onClick="MM_openBrWindow('consultapordisciplina.php?getperiodo=<?echo $getperiodo;?>&getdisciplina=<?echo $getdisciplina;?>&botao=1','imprimir','scrollbars=yes,width=760,height=400,top=0,left=0')">
</div>
<?php
}
$cor1 = "#8FB5DA";
$cor2 = "#FFFFFF";
$cor = $cor2;
print(" <b>LEGENDA</b>
<br>
<table width=95%>
<tr bgcolor=\"#336699\">
<td align=center><b><font color=\"#FFFFFF\">Descrição Abreviada</td>
<td align=center><b><font color=\"#FFFFFF\">Descrição</td>
<td align=\"center\"><b><font color=\"#FFFFFF\">Carga Horaria Prevista</td>
<td align=\"center\"><b><font color=\"#FFFFFF\">Carga Horaria Realizada</td></font></b></tr>
<tr>");

$query1 = pg_exec($dbconnect, $sql1);

while($row1 = pg_fetch_array($query1)) 
{
   if($cor == "#FFFFFF") 
   { 
      $cor=$cor1;
   }
   else
   { 
      $cor=$cor2;
   }
   $carga = $row1[carga_horaria];
   $descricaodis2 = substr($row1["descricao_disciplina"],0,3);
   $descricaodis = $row1["descricao_extenso"];
   $iddisciplina = $row1["id"];
       print ("<tr bgcolor=\"$cor\">");
       print ("<td align=center>$descricaodis2</td>");
       print ("<td align=left>$descricaodis</td>");
       print ("<td align=center>$carga</td>");
      //////    $sqlflag="select flag from diario_seq_faltas where periodo='$getperiodo' and disciplina='$iddisciplina'";
      // $sqlflag="select flag from diario_seq_faltas where periodo = '$getperiodo' and disciplina='$getdisciplina' and ref_disciplina_ofer = '$getofer' ";
       $sqlflag ="
            SELECT 
                  SUM(CAST(flag AS INTEGER)) AS carga
               FROM 
                  diario_seq_faltas 
               WHERE 
                  periodo = '$getperiodo' AND 
                  disciplina = $getdisciplina AND 
                  ref_disciplina_ofer = $getofer ";
                  
       //echo '<br />'.$sqlflag; 
      // and ref_disciplina_ofer = '$getofer'
       $queryflag = pg_exec($dbconnect, $sqlflag);
       
       $rowflag = pg_fetch_array($queryflag);
       
       $result = $rowflag[carga];
       
       //print_r($rowflag);
       /*
       while ($rowflag = pg_fetch_array($queryflag)) 
       {
             $flags = $rowflag["flag"];
             //echo $flags;
             if ($flags == "") 
             {
                $result = $flags;
             } 
             elseif ($flags != "")
               {
                  $result = $result + $flags;   
               }
       }*/
       
       print("<td align=\"center\">$result</td>");
       unset($result);
       print ("</tr>");
}
print("</tr> </table><br><br>");

print('<table width="95%">
      <tr bgcolor="#336699"><td><div align="center"><strong><font color="#FFFFFF"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">MATR&Iacute;CULA/NOME</font></font></strong></div></td>');

$query1 = pg_exec($dbconnect, $sql1);

while($row1 = pg_fetch_array($query1)) 
{
   $iddis = $row1["id"];
   $descricaodis2 = substr($row1["descricao_disciplina"],0,3);
   $descricaodis = $row1["descricao_disciplina"];
   print ('<td colspan="2"> <div align="center"><strong><font color="#FFFFFF"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$descricaodis2.'</font></font></strong></div></td>');
}
print ("</tr>");
print("<tr bgcolor=#FFFFFF><td>&nbsp;</td>");

$query1 = pg_exec($dbconnect, $sql1);

while($row1 = pg_fetch_array($query1)) 
{
   print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Notas</font></div></td>');
   print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Faltas</font></div></td>');
}
print ("</tr>");

$query2 = pg_exec($dbconnect, $sql2);

while($row2 = pg_fetch_array($query2)) 
{
   if ($cor=="#FFFFFF") 
   { 
      $cor = $cor1;
   } 
   else
   { 
      $cor = $cor2;
   }
   $numero = $row2["ordem_chamada"];
   $nomealuno = $row2["nome"];
   //$refpessoa = $row2["ref_pessoa"];
   
   $refpessoa = str_pad($row2["ref_pessoa"], 5, "0", STR_PAD_LEFT) ;
   
   print ("<tr bgcolor=\"$cor\">");   
   print ("<td align=left>$refpessoa - $nomealuno</td>");
   
   $query1 = pg_exec($dbconnect, $sql1);
   
   while($row1 = pg_fetch_array($query1)) 
   { 
      $iddis = $row1["id"];
      ////////  $sqlbusca="select nota_final, num_faltas from matricula where ref_periodo = '$getperiodo' and ref_pessoa = '$refpessoa' and ref_disciplina = '$iddis'";
      $sqlbusca = "select nota_final, num_faltas from matricula where ref_periodo = '$getperiodo' and ref_pessoa = '$refpessoa' and ref_disciplina = '$getdisciplina'";
      
      
     // -- RECUPERA TODAS AS NOTAS E FALTAS PARA TODAS AS DISCIPLINAS DE DETERMINADO PERÍODO POR ALUNO
      $sqlbusca = "
      SELECT 
      DISTINCT 
      c.descricao_disciplina, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas 
      FROM matricula a, pessoas b, disciplinas c 
      WHERE 
      a.ref_periodo = '0601' AND 
      a.ref_disciplina 
      IN ( 
      SELECT 
      DISTINCT 
      a.ref_disciplina
      FROM matricula a, disciplinas b 
      WHERE
      a.ref_periodo = '0601' AND 
      a.ref_disciplina = b.id AND
      a.ref_pessoa = '2064'                      
             ) AND 
      a.ref_disciplina = c.id AND
      a.ref_pessoa = b.id AND
      a.ref_pessoa = '2064'
      ORDER BY
      c.descricao_disciplina";
      
      
      
      $querybusca = pg_exec($dbconnect, $sqlbusca);
      
      $numlinhas = pg_num_rows($querybusca);
      
      if ($numlinhas == "0") 
      {
         print ('<td align=center>N/C</td><td align=center>N/C</td>');
      }
      else
      {
         while($row3 = pg_fetch_array($querybusca)) 
         {
            $nota = $row3["nota_final"];
            $falta = $row3["num_faltas"];
            print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$nota.'</font></div></td>');
            print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$falta.'</font></div></td>');
         }

      }
   }
       print("</tr>");
}
print("
</table>
<br>
</body>
</html>");
?>
