<?php
// http://sagu.cefetbambui.edu.br/diario/movimentos/lancanotas/lanca1.php
include_once('../../webdiario.conf.php');

$getdisciplina = $_POST['disc'];
$getofer = $_POST['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

$getcurso = $_POST['curso'];
$getprova = $_POST['getprova'];

$prova = $getprova;

// $grupo = ($id."-".$getperiodo."-".$getdisciplina);
$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);

$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);


if(($getprova == 'A') or ($getprova == ''))
{
     print '<script language=javascript>
	window.alert("Você deve selecionar qual a prova que será lançada as notas.");
	javascript:window.history.back(1);
	</script>';
	exit;
}
else
{


$sql12 = "SELECT   DISTINCT
					matricula.ordem_chamada, pessoas.nome, pessoas.id, pessoas.ra_cnec, d.nota AS notabanco, d.ref_diario_avaliacao
 			FROM
				matricula
			INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
			INNER JOIN diario_notas d ON (id_ref_pessoas = pessoas.id AND
											d.ra_cnec = matricula.ref_pessoa AND
											d.id_ref_periodos = '$getperiodo' AND
											d.d_ref_disciplina_ofer = '$getofer' AND
											d.ref_diario_avaliacao = '$getprova' )
			WHERE
				(matricula.ref_disciplina_ofer = '$getofer') AND
				(matricula.dt_cancelamento is null) AND
				(matricula.ref_motivo_matricula = 0)
			ORDER BY lower(to_ascii(pessoas.nome));";
//  d.rel_diario_formulas_grupo = '$grupo' AND

$sql12 = 'SELECT * FROM (';
$sql12 .= "SELECT   DISTINCT
                    matricula.ordem_chamada, pessoas.nome, pessoas.id, SUM(d.nota) AS notaparcial
            FROM
                matricula
            INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
            INNER JOIN diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                            d.ra_cnec = matricula.ref_pessoa AND
                                            d.id_ref_periodos = '$getperiodo' AND
                                            d.d_ref_disciplina_ofer = '$getofer' AND
                                            d.ref_diario_avaliacao <> '$prova'  AND
                                            d.ref_diario_avaliacao <> '7')
            WHERE
                (matricula.ref_disciplina_ofer = '$getofer') AND
                (matricula.dt_cancelamento is null) AND
				(matricula.ref_motivo_matricula = 0)

            GROUP BY
                     matricula.ordem_chamada, pessoas.nome, pessoas.id, pessoas.ra_cnec
            ORDER BY pessoas.nome ";

$sql12 .= ') AS T1 INNER JOIN (';

// d.rel_diario_formulas_grupo = '$grupo' AND

$sql12 .= "SELECT DISTINCT
               pessoas.ra_cnec, d.nota AS notabanco
            FROM
               matricula INNER JOIN
               pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN
               diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                 d.ra_cnec = matricula.ref_pessoa AND d.id_ref_periodos = '$getperiodo' AND d.d_ref_disciplina_ofer = '$getofer' AND d.ref_diario_avaliacao = '$prova')
            WHERE
               (matricula.ref_disciplina_ofer = '$getofer') AND (matricula.dt_cancelamento is null) AND (matricula.ref_motivo_matricula = 0)";

// AND d.rel_diario_formulas_grupo = '$grupo'

$sql12 .= ') AS T2 ON (T2.ra_cnec = T1.id) INNER JOIN (';


$sql12 .= "SELECT DISTINCT
               pessoas.ra_cnec AS ref_pessoa, d.nota AS notaextra
            FROM
               matricula INNER JOIN
               pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN
               diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                 d.ra_cnec = matricula.ref_pessoa AND d.id_ref_periodos = '$getperiodo' AND d.d_ref_disciplina_ofer = '$getofer' AND d.ref_diario_avaliacao = '7')
            WHERE
               (matricula.ref_disciplina_ofer = '$getofer') AND (matricula.dt_cancelamento is null) AND (matricula.ref_motivo_matricula = 0)";


// d.rel_diario_formulas_grupo = '$grupo'

$sql12 .= ') AS T3 ON (T3.ref_pessoa = T2.ra_cnec) ORDER BY lower(to_ascii(nome));';



//echo $sql12; die;
/*
if($getprova == 7)
{
   include_once('nota_extra.php');
   exit;
}
*/

$sql1 = "SELECT DISTINCT
  m.ordem_chamada,
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '$getperiodo') AND
  (m.ref_disciplina_ofer = '$getofer') AND
  (m.dt_cancelamento isnull) AND
  (m.ref_motivo_matricula = 0)
ORDER BY
  m.ordem_chamada;";

// (matricula.ref_disciplina = '$getdisciplina') AND

$qry1 = consulta_sql($sql12);

if(is_string($qry1))
{
   echo $qry1;
   exit;
}

/* PROCESSO DE NOTA DISTRIBUIDA */

$sqlNotaDistribuida = "
	SELECT * 
	FROM diario_formulas 
	WHERE 
	grupo ILIKE '%-" . $getofer . "' AND 
	prova = " . $prova;

$qryNotaDistribuida = consulta_sql($sqlNotaDistribuida);

if(is_string($qryNotaDistribuida))
{
   echo $qry1;
   exit;
}

$nota_distribuida = getNumeric2Real(pg_fetch_row($qryNotaDistribuida));

?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<script src="../../js/event-listener.js" type="text/javascript"></script>
<script src="../../js/enter-as-tab.js" type="text/javascript"></script>

<link href="../../style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="90%" height="73" border="0">
  <tr>
    <td width="471">
    	<div align="center">
        <span class="titulo"><span class="titulo">Lan&ccedil;amento / Altera&ccedil;&atilde;o da Nota
        <?php
if($getprova == 7){ 
	echo '<font color="blue"> Nota Extra</font> <font color="red" size="2">(Utilize apenas quando houver reavalia&ccedil;&atilde;o/recupera&ccedil;&atilde;o)</font>.' ;		  
}
else{	
	echo '<font color="blue"> P'.$getprova.'</font>.' ; 
}
?>
    </strong></font></span></div></td>
  </tr>
</table>
<form name="form1" method="post" action="grava_notas.php">
<?php

   echo '<input type="hidden" name="codprova" id="codprova" value="' . $getprova.'">';
   echo '<input type="hidden" name="disc" id="disc" value="' .$getdisciplina.'">';
   echo '<input type="hidden" name="ofer" id="ofer" value="' . $getofer.'">';
   echo '<input type="hidden" name="curso" id="curso" value="' . $getcurso.'">';

   echo getHeaderDisc($getofer);

?>
<p><strong>Nota distribu&iacute;da:</strong>
  <input name="valor_avaliacao" type="text" id="valor_avaliacao" size="8" value="<?php echo $nota_distribuida[8];?>">
</p>
<table width="92%" border="0">
  <tr bgcolor="#666666">

  <td width="6%" align="center"><font color="#FFFFFF"><strong>Nota</strong></font></td>
  <!--<td width="6%" align="center"><font color="#FFFFFF"><strong>N°</strong></font></td>-->
      <td width="10%"><font color="#FFFFFF"><b>&nbsp;Matr&iacute;cula</b></font></td>
  <td width="82%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
 </tr>
 <?php

   $st = '';
   while($linha1 = pg_fetch_array($qry1))
   {
      $result2 = $linha1["ra_cnec"];
      $result3 = $linha1["ordem_chamada"];
      $result = $linha1["nome"];
      $notaprova = $linha1["notabanco"];
      $nota_parcial = $linha1['notaparcial'];

      if($prova == 7 && $nota_parcial > 59.99) {

			continue;
	  }

      if($notaprova < 0 )
      {
      		$notaprova = '';
      }
      else {
      		$notaprova = getNumeric2Real($notaprova);
      }

/*
      // SELECIONA NOTA INDIVIDUAL DOS ALUNOS
      $sql99 ="SELECT
               nota AS notabanco
               FROM
               diario_notas
               WHERE
               id_ref_pessoas = '$result2' AND
               id_ref_periodos = '$getperiodo' AND
               d_ref_disciplina_ofer = '$getofer' AND
               ra_cnec = '$result2' AND
               rel_diario_formulas_grupo = '$grupo' AND
               ref_diario_avaliacao = '$getprova';";
               //  id_ref_curso = '$getcurso' AND

     // echo $sql99; $grupo
     // exit;
// d.id = '$getofer' AND
      $qry99 = consulta_sql($sql99);

	  if(is_string($qry99))
	  {
		  echo $qry99;
		  exit;
	  }

      $rows99 = pg_NumRows($qry99);

      if($rows99 == 0)
      {
         $notaprova = 0;
      }
      else
      {
         while($ntprv = pg_fetch_array($qry99))
         {
            $notaprova = $ntprv["notabanco"];
         }
      }
*/
      if($st == '#F3F3F3')
      {
         $st = '#E3E3E3';
      }
      else
      {
         $st ='#F3F3F3';
      }

      //$notaprova = getNumeric2Real($notaprova);
      echo ' <tr bgcolor="'.$st.'"> <td align="center">';
	  echo ' <input name="notas['.$result2.']" type="text" onBlur="MM_validateForm(\'textfield\',\'\',\'RinRange0:10\');return document.MM_returnValue" value="'.$notaprova.'" size="4" maxlength="4">';
	  echo '<input type="hidden" name="ra_cnec[]" value="'.$result2.'"></td>
            <td>  '.$result2.' </td>
            <td> '.$result.'   </td>
            </tr>'; //<td align="center"> '.$result3.'</td>
   }
?>
 </table><br>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
 </table>
  <input type="submit" name="Submit" value="Gravar Notas -->">
</form>
<br />
<br />
<script type="text/javascript">
//<![CDATA[
      enterAsTab();
//]]>
</script>
</body>
</html>
<?php } ?>
