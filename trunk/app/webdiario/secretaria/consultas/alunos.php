<?php
/*
====================================
DESENVOLVIDO SOBRE LEIS DA GNU/GPL
====================================

E-CNEC : ti@cneccapviari.br

CNEC CAPVIARI - www.cneccapivari.br
Rua Bar�o do Rio Branco, 347, Centro - Capivari/SP
Tel.: (19)3492-1869
*/

$st = '#F3F3F3';


include_once('../../webdiario.conf.php');

if($_SESSION['periodo'] === "" OR !isset($_SESSION['periodo']) OR $_SESSION['select_prof'] === "" OR !isset($_SESSION['select_prof']))
{
    echo '<script language="javascript">
                window.alert("ERRO! Primeiro selecione um periodo e um professor(a)!");
     </script>';
    sleep(2);
    echo '<meta http-equiv="refresh" content="0;url=../select_profs.php">';
    exit;
}

$ref_prof = $_SESSION['select_prof'];

$ras = @$_GET['ras'];
$nomes = @$_GET['nomes'];

// VARS

//$nomes = strtoupper($nomes);
//$ras = strtoupper($ras);
/*
$sqlprof = "SELECT DISTINCT
      b.ref_professor
      FROM
      diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE
      a.id_nome = '$id'
      AND
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id;";

//echo $sqlprof;
      
$resQry = pg_exec($dbconnect, $sqlprof);

while($row1 = pg_fetch_array($resQry)) 	
{
   $id = $row1["ref_professor"];   
}
*/

//echo $id;


/*      AND
      c.ref_periodo = d.id
      AND
      d.dt_inicial < '$data_postgres'
      AND
      d.dt_final > '$data_postgres'";*/



$sql1 = "SELECT DISTINCT a.ra_cnec, a.nome, a.id, c.ref_professor 
         FROM pessoas a, matricula b, disciplinas_ofer_prof c, disciplinas_ofer d
         WHERE ra_cnec ILIKE '$ras%' 
         AND b.ref_pessoa = a.id
         AND c.ref_professor = '$ref_prof'
         AND c.ref_disciplina_ofer =  d.id;";
/*         
$sql1 = "SELECT DISTINCT a.ra_cnec, a.nome, a.id 
         FROM pessoas a, matricula b 
         WHERE ra_cnec ILIKE '$ras%' 
         AND b.ref_pessoa = a.id";
         
echo $sql1;
*/

$qry1 = consulta_sql($sql1);

if(is_string($qry1))
{
    echo $qry1;
	exit;
}
	

$sql2 = "SELECT DISTINCT a.ra_cnec, a.nome, a.id 
         FROM pessoas a, matricula b 
         WHERE nome ILIKE '$nomes%' 
         AND b.ref_pessoa = a.id;";
		 

$qry2 = consulta_sql($sql2);

if(is_string($qry2))
{
    echo $qry2;
    exit;
}


$sql3 = "SELECT DISTINCT a.ra_cnec, a.nome, a.id 
         FROM pessoas a, matricula b  
         WHERE nome ILIKE '$nomes%' 
         AND ra_cnec LIKE '$ras%' 
         AND b.ref_pessoa = a.id";

$qry3 = consulta_sql($sql3);

if(is_string($qry3))
{
    echo $qry3;
    exit;
}


?>
<html>
<head>
  <title>CONSULTAS DE ALUNOS</title>
  <link rel="stylesheet" href="../../css/forms.css" type="text/css">
  </head>
  <body onLoad="document.busca.<? if ((@$ras == "") && (@$nomes == "")) { print("ras"); } elseif (@$ras != "") { print("ras"); } else { print("nomes"); } ?>.focus()">
  <table width="450">
    <tr>
      <td width="400" bgcolor="#cccccc" colspan="3"><b>&nbsp;Busca de Aluno</b></td>
    </tr>
    <form action="alunos.php" method="get" name="busca">
      <tr>
        <td width="50" valign="botton">Matr&iacute;cula</td>
        <td width="300" valign="botton">Nome</td>
        <td width="50">&nbsp;</td>
      </tr>
      <tr>
        <td width="50" valign="middle"><input name="ras"
          type="text" maxlenght="8" size="8" value="<?php echo $ras; ?>" onChange="document.busca.submit()"></td>
        <td width="300" valign="middle"><input
          name="nomes"type="text" maxlenght="50" size="50"
          value="<?php echo $nomes; ?>" onChange="document.busca.submit()"></td>
        <td width="50"><input type="submit" value=" OK "></td>
      </tr>
    </form>
  </table><br>
<?
   if ((isset($ras)) && (isset($nomes)) && ($ras != "")) 
   {
      if (pg_numrows($qry3) > 0) 
      { 
?>
  <table width="650">
    <tr bgcolor="#CCCCCC">
      <td width="50"><b>Matr&iacute;cula</b></td>
      <td width="560"><b>Nome</b></td>
      <td width="40"><b>A��es</b></td>
    </tr>
<?php
   while($row3 = pg_fetch_array($qry3)) 
   {
      if ($st == '#F3F3F3') 
      {
         $st = '#E3E3E3';
      } 
      else 
      {
         $st ='#F3F3F3';
      }
      $q3ra = $row3['ra_cnec'];
      $q3nome = $row3['nome'];
      $q3id = $row3['id'];
      print ("<tr bgcolor=\"$st\"> <td>$q3ra</td> 		<td>$q3nome</td> <td> <a href=\"faltas_alunos.php?ras=$q3ra&nome=$q3nome\"> 		<img src=\"../../img/edit.gif\" border=\"0\" title=\"Ver faltas\"></a>&nbsp; <a href=\"cadastro_alunos.php?ras=$q3id\"> 	<img src=\"../../img/compose.gif\" border=\"0\" title=\"Ver Cadastro\"></a>&nbsp;</td> </tr>");
    }
    } else {
    print ("N�o foi encontrado nenhum aluno");
    }
//    pg_close($dbconnect);
    unset($_GET['nomes']);
    unset($_GET['ras']);
    }
    if (isset($ras) && ($ras != "")) 
    {
      if (pg_numrows($qry1) > 0) 
      { 
?>
<!--    <table width="450">
      <tr bgcolor="#CCCCCC">
        <td width="50"><b>RA</b></td>
        <td width="380"><b>Nome</b></td>
        <td width="20"<b>A��es</b></td>
		</tr>-->
<?php
/*	
while($row1 = pg_fetch_array($qry1)) 
{
   if($st == '#F3F3F3') 
   {
      $st = '#E3E3E3';
   } 
   else 
   {
      $st = '#F3F3F3';
   }
   $q1ra = $row1['ra_cnec'];
   $q1nome = $row1['nome'];
   $q1id = $row1['id'];
    print ("<tr bgcolor=\"$st\"> <td>$q1ra</td> <td>$q1nome</td> <td> <a href=\"faltas_alunos.php?ras=$q1ra&nome=$q1nome\"> 	<img src=\"../../img/edit.gif\" border=\"0\" title=\"Ver faltas\"></a>&nbsp; <a href=\"cadastro_alunos.php?ras=$q1id\"> 	<img src=\"../../img/compose.gif\" border=\"0\" title=\"Ver Cadastro\"></a>&nbsp;</td> </tr>");
}
*/
} 
else 
{
   print ("N&atilde;o foi encontrado nenhum aluno");
}
//   pg_close($dbconnect);
}


if(isset($nomes) && ($nomes != "")) 
{
   if(pg_numrows($qry2) > 0) 
   { 
?>
      <table width="450">
        <tr bgcolor="#CCCCCC">
          <td width="50"><b>Matr&iacute;cula</b></td>
          <td width="380"><b>Nome</b></td>
          <td width="20"><b>A&ccedil;&otilde;es</b></td>
        </tr>
<?php
while($row2 = pg_fetch_array($qry2)) 
{
   if($st == '#F3F3F3') 
   {
      $st = '#E3E3E3';
   } 
   else 
   {
      $st = '#F3F3F3';
   }
   $q2ra = $row2['ra_cnec'];
   $q2nome = $row2['nome'];
   $q2id = $row2['id'];
   print ("<tr bgcolor=\"$st\"> <td>$q2ra</td> 			<td>$q2nome</td> <td> <a href=\"faltas_alunos.php?ras=$q2ra&nome=$q2nome\"> <img src=\"../../img/edit.gif\" border=\"0\" title=\"Ver faltas\"></a>&nbsp; <a href=\"cadastro_alunos.php?ras=$q2id\">	<img src=\"../../img/compose.gif\" border=\"0\" title=\"Ver Cadastro\"></a>&nbsp;</td></tr>");
} 
print("</table>");
} 
else 
{
   print ("N�o foi encontrado nenhum aluno");
}
//pg_close($dbconnect);
}
?>
      
</body>
</html>
