<?php
if ($ra_cnec_s == "" ) {
  print ("Por favor escolha uma data ou um aluno! <a href=\"javascript:history.go(-1)\">Voltar</a>.");
} else {
  if ($ra_cnec_s == "dia") { 
    if ($selectdia == "") {
        print ("Por favor selecione um dia! <a href=\"javascript:history.go(-1)\">Voltar</a>.");
	exit;
    } else { 
      if ($selectmes == "") {
	print ("Por favor selecione um mes! <a href=\"javascript:history.go(-1)\">Voltar</a>.");
	exit;
      }
    }
  }
  if ($ra_cnec_s == "periodo") {
    if (($periododia == "") or ($periodomes == "") or ($periododia2 == "") or ($periodomes2 == "")) {
      print ("Por favor preencha todos os campos do periodo! <a href=\"javascript:history.go(-1)\">Votlar</a>.");
      exit;
    }
  }

require_once('../webdiario.conf.php');

if ($ra_cnec_s == "dia") {
  $databd= "2003-" . $selectmes . "-" . $selectdia;
}
 //Seleciona o Período
$sql1 = "SELECT
         a.ra_cnec,
         a.data_chamada,
		 a.aula,
         b.nome
         FROM diario_chamadas a, pessoas b
         WHERE a.ra_cnec='$ra_cnec_s' and b.ra_cnec='$ra_cnec_s' and a.ref_curso='$curso' and a.ref_periodo='$periodo' and a.ref_disciplina='$disciplina'";

if ($ra_cnec_s == "dia") {
$sql2 = "SELECT
		 ra_cnec,
		 data_chamada,
		 aula FROM diario_chamadas
		 WHERE
		 data_chamada='$databd' and
		 ref_curso='$curso' and
		 ref_periodo='$periodo' and
		 ref_disciplina='$disciplina' ORDER BY ra_cnec, data_chamada";

 $query2 = pg_exec($dbconnect, $sql2); }

 if ($ra_cnec_s == "periodo") {
   $newdatabd1 =  "2003-" . $periodomes . "-" . $periododia;
   $newdatabd2 =  "2003-" . $periodomes2 . "-" . $periododia2;
$sql2 = "SELECT
                 ra_cnec,
		 data_chamada,
		 aula FROM diario_chamadas
		 WHERE
		 data_chamada >= '$newdatabd1' and
		 data_chamada <= '$newdatabd2'  and
                 ref_curso='$curso' and
		 ref_periodo='$periodo' and
		 ref_disciplina='$disciplina' ORDER BY ra_cnec, data_chamada";
 $query2 = pg_exec($dbconnect, $sql2); 
 }
$query1 = pg_exec($dbconnect, $sql1);

 ?>
<html>
<head>
<title>teste</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0">
  <tr>
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Consulta 
        de Faltas</strong></font></div></td>
  </tr>
</table>

<table width="80%">
       <tr bgcolor="#666666">
           <td width="20%"><font color="#FFFFFF"><b>RA</b></font></td>
           <td width="45%"><font color="#FFFFFF"><b>Nome do Aluno</b></font></td>
           <td width="20%"><font color="#FFFFFF"><b>Data da Falta</b></font></td>
           <td width="15%"><font color="#FFFFFF"><b>Aula da Falta</b></font></td>
       </tr>
<?
$st ="#F3F3F3";
if (($ra_cnec_s != "dia") && ($ra_cnec_s != "periodo")) {
   while ($row1 = pg_fetch_array($query1)) {
             $q1ra_cnec = $row1['ra_cnec'];
             // $q1data_chamada = br_date($row1['data_chamada']);
	     $q1data_chamada = $row1['data_chamada'];
             $q1nome = $row1['nome'];
			 $q1aula = $row1['aula'];
			 print ("<tr bgcolr=\"$st\">
                         <td>$q1ra_cnec</td>
                         <td>$q1nome</td>
                         <td>$q1data_chamada</td>
                         <td>"); 
						 if ($q1aula == "1") {
								print("1 Aula");
						} else {
								print("2 Aula");
						}
						print("</td>
                     </tr>");
                     if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
   } 
} elseif ($ra_cnec_s == "perido") {
  while ($row2 = pg_fetch_array($query2)) { 
    $q3ra_cnec = $row2['ra_cnec'];
   // $q3dta_chamada = br_date($row2['data_chamda']);
    $q3dta_chamada = $row2['data_chamda'];
    $q3aulo = $row2['aula'];
    $sql3 = "SELECT nome FROM pessoas WHERE ra_cnec='$q2ra_cnec'";
    $query3 = pg_exec($dbconnect, $sql3);						
    $row3 = pg_fetch_array($query3);
    print ("<tr bgcolr=\"$st\">
                         <td>$q3ra_cnec</td>
                         <td>" . $row3['nome'] . "</td>
                         <td>$q3data_chamada</td>
                         <td>"); 
    if ($q2aula == "1") {
      print("1 Aula");
    } else {
      print("2 Aula");
    }
    print("</td>
                     </tr>");
    if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
  }
} else {
     while ($row2 = pg_fetch_array($query2)) {
             $q2ra_cnec = $row2['ra_cnec'];
	    // $q2data_chamada = br_date($row2['data_chamada']);
	     $q2data_chamada = $row2['data_chamada'];
			 $q2aula = $row2['aula'];
			 $sql3 = "SELECT nome FROM pessoas WHERE ra_cnec='$q2ra_cnec'";
			 $query3 = pg_exec($dbconnect, $sql3);						
			 $row3 = pg_fetch_array($query3);
             print ("<tr bgcolr=\"$st\">
                         <td>$q2ra_cnec</td>
                         <td>" . $row3['nome'] . "</td>
                         <td>$q2data_chamada</td>
                         <td>"); 
						 if ($q2aula == "1") {
								print("1 Aula");
						} else {
								print("2 Aula");
						}
						print("</td>
                     </tr>");
                     if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
                     }
} 

?>
</table>
</body>
</html>

    <? }
?>
