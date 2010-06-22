<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once('../../webdiario.conf.php');

// CONECT NO BANCO

$id = $_GET[id];
$getperiodo = $_GET[getperiodo];
$getcurso = $_GET[getcurso];
//echo "\$getcurso = $getcurso";
//exit;

$disciplina = $_GET['getdisciplina'];

$var = explode(":",$_GET['getdisciplina']);
$getdisciplina = $var[0];
$getofer = $var[1];

// $getdisciplina = $_GET[getdisciplina];


//$grupo = ( $id."-".$getperiodo."-".$getdisciplina);
$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);

// VARS

$sql1 ="SELECT DISTINCT
               a.nome,
               a.id,
               a.ra_cnec,
               b.ordem_chamada
               FROM
               pessoas a,
               matricula b,
               disciplinas_ofer_prof c
               WHERE
               c.ref_professor = '$id' AND
               b.ref_periodo = '$getperiodo' AND
               b.ref_disciplina = '$getdisciplina' AND
               b.ref_pessoa = a.id AND
               b.ref_disciplina_ofer = '$getofer' AND
               c.ref_disciplina_ofer = b.ref_disciplina_ofer
               ORDER BY b.ordem_chamada ";
/*               
echo $sql;
exit;
*/
$query1 = pg_exec($dbconnect, $sql1);
//   pg_close($dbconnect);

/*   
$sql9 ="SELECT
               a.descricao as cdesc,
               b.descricao_extenso,
               c.descricao as perdesc
               FROM
               cursos a,
               disciplinas b,
               periodos c
               WHERE
               a.id = '$getcurso' AND
               b.id = '$getdisciplina' AND
               c.id = '$getperiodo'";
*/

$sql9 = "SELECT DISTINCT
         a.descricao as cdesc,
         b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
         c.descricao as perdesc,
         d.ref_curso
         FROM
          cursos a,
          disciplinas b,
          periodos c,
          disciplinas_ofer d  
         WHERE
          d.ref_periodo = '$getperiodo' AND
          b.id = '$getdisciplina' AND
          c.id = '$getperiodo' AND
          d.id = '$getofer' AND
          d.ref_disciplina = '$getdisciplina' AND
          d.is_cancelada = 0 AND
          a.id = d.ref_curso";

$query9 = pg_exec($dbconnect, $sql9);

while($linha9 = pg_fetch_array($query9)) 
{
   $exibecurso = $linha9["cdesc"];
   $exibedisc  = $linha9["descricao_extenso"];
   $exibeper   = $linha9["perdesc"];
}
pg_close($dbconnect);

?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
</head>

<body>
<table width="92%" border="0">
  <tr> 
    <td width="31%"><div align="center">Curso :</div></td>
    <td width="31%"> <div align="center">Disciplina :</div></td>
    <td width="31%"> <div align="center">Periodo :</div></td>
  </tr>
  <tr> 
    <td><div align="center">
     <strong>
      <?php print ($exibecurso); ?>
     </strong></div></td>
    <td><div align="center">
    <strong>
      <?php print ($exibedisc); ?>
      </strong></div></td>
    <td><div align="center"><strong>
       <?php print ($exibeper); ?>
     </strong></div></td>
  </tr>
</table>
 <h4><font color="#330099">* Professor, conforme descrito pela f&oacute;rmula abaixo voc&ecirc; ter&aacute; no m&aacute;ximo seis (6) notas para lan&ccedil;ar.<br /> * Estas notas representam as avalia&ccedil;&otilde;es aplicadas durante o per&iacute;odo(Provas, Trabalhos, Relatórios, Monitorias, etc).<br /> * As notas (de uma a seis) ser&atilde;o somadas e o total final n&atilde;o poder&aacute; exceder a 100 pontos!</font></h4>

<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Voc&ecirc; tem a seguinte f&oacute;rmula cadastrada :</font></p>

<?php
// CONECT NO BANCO
$dbconnect = pg_Pconnect("host=$host user=$user password=$password dbname=$database");


// VARS

$sql1 = "SELECT DISTINCT
                formula
                FROM
                diario_formulas
                WHERE
                grupo = '$grupo'";
                
$query1 = pg_exec($dbconnect, $sql1);

$sql2 = "SELECT
                id,
                prova,
                descricao
                FROM
                diario_formulas
                WHERE
                grupo = '$grupo'";
//echo $sql2;
//exit;
     
$query2 = pg_exec($dbconnect, $sql2);

if ((isset($id)) && (isset($getcurso)) && (isset($getperiodo)) && (isset($getdisciplina))) {
  if (pg_numrows($query1) > 0) { ?>
      <table width="400">
      <tr bgcolor="#CCCCCC">
      <td width="300"><b>Descrição</b></td>
      <!--<td width="20"><b>Ação</b></td>-->
    </tr><?
  while ($row1 = pg_fetch_array($query1)) 
  {
   if($st == '#F3F3F3') 
   {
      $st = '#E3E3E3';
   } 
   else 
   {
      $st ='#F3F3F3';
   }
    $qdesc = $row1['formula'];
    print ("<tr bgcolor=\"$st\">
               <td>$qdesc</td>
              <!-- <td>
	        <a href=\"apaga_prova.php?flag=$id&id=$qid&getcurso=$getcurso&getdisciplina=$getdisciplina&getperiodo=$getperiodo&grupo=$grupo\">
               <div align=\"center\"><img src=\"../../img/erase.gif\" border=\"0\" title=\"APAGAR PROVA\"></div>
               </td>-->
          </tr>");
     }
    } else {
    print ("<strong><font color=\"#FF0000\" size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\">N&atilde;o existe f&oacute;rmula ou provas cadastradas !</font></strong>");
           }
    pg_close($dbconnect);
    }
    else {
      print ("A consulta Falhou ! , Contate o suporte técnico !");
      print ($id);
      print ($getperiodo);
      print ($getcurso);
      print ($getdisciplina);
      }
      ?>
<table width="92%" border="0">
    <td width="31%" height="23"> 
  <tr> 
    <form name="envia" method="post" action="inputnotas.php">
      <td height="20" colspan="3"><div align="left">Lançamento referente à : 
<?php  print("
        <input type=\"hidden\" name=\"id\" value=\"$id\">
		<input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">
		<input type=\"hidden\" name=\"getcurso\" value=\"$getcurso\">
		<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">
		<input type=\"hidden\" name=\"getofer\" value=\"$getofer\">
		<select name=\"getprova\" class=\"select\">
		<option value=\"A\" selected>--- selecione ---</option>");
          while ($row2 = pg_fetch_array($query2)) 
         {
            $qid = $row2['prova'];
            $qdesc = $row2['descricao'];
            print "<option value=$qid>$qdesc</option>";
         }
         print("</select>"); 
?>
          
<input type="submit" name="Submit" value="Lan&ccedil;ar !!">
        </div></td>
    </form>
  <tr> 
    <td height="20" colspan="3">&nbsp;</td>
  <tr> 
    <td height="20" colspan="3">&nbsp;</td>
</table>

<td colspan="4">

<?php

	print ('<a href="resolve_pendencias.php?grupo='.$grupo.'&id='.$id.'&getcurso='.$getcurso.'&getdisciplina='.$disciplina.'&getperiodo='.$getperiodo.'"><font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Verificar e Resolver Pend&ecirc;ncias de Alunos</strong></font></a><br />&nbsp; - Utilize esta fun&ccedil;&atilde;o caso tenha algum aluno com problemas para registar a nota ou tenha algum aluno inclu&iacute;do posteriormente no di&aacute;rio.<br /><br />');
       	
   $getdisciplina = "$getdisciplina:$getofer";
   print ('<a href="testaformula_1.php?grupo='.$grupo.'&id='.$id.'&getcurso='.$getcurso.'&getdisciplina='.$getdisciplina.'&getperiodo='.$getperiodo.'"><font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Testar Fórmula</strong></font></a>'); 

echo '<br />';


//print_r($_GET);

?>

</td>
</body>
</html>
