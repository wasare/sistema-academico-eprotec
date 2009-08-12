<?php
include ('../webdiario.conf.php');
//////////////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or dir ("Erro 1");

// make select



$sql1="select a.descricao_extenso, b.descricao from disciplinas a, periodos b where a.id='$getdisciplina' and b.id='$getperiodo'";


//$sql1="select a.descricao_extenso, b.descricao from disciplinas a, periodos b where a.id='301002' and b.id='0602'";


$query1 = pg_exec($dbconnect, $sql1);

?>
<html>
<head>
<title>historico.php</title>
<link rel="stylesheet" href="../css/forms.css" type="text/css">

</head>

<body>
<?
$row=pg_fetch_array($query1);
 $descricao_s=$row["descricao"];
 $descricao_extenso_s=$row["descricao_extenso"];


$mes=gmDate("m");
        switch ($mes) {
           case "01":
           $mesdesc = "Janeiro";
           break;
           case "02":
           $mesdesc = "Fevereiro";
           break;
           case "03":
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
	};

$mkDate1=gmDate(d .'\de');
$mkDate2=gmDate('\de' . Y);
?>
<table width='760'>
   <tr>
      <td width='380'><b>Curso: </b><? echo $descricao_s; ?></td>
      <td width='380'><b>Disciplina: </b><? echo $descricao_extenso_s; ?></td>
   </tr>
   <tr> 
      <td width='760' align='right' colspan='2' height='25'><b>Emitido em:</b> <? print("$mkDate1 $mesdesc $mkDate2"); ?></td>
   </tr>
</table>





</body>
</html>

