<?php

require_once(dirname(__FILE__) .'/../../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/number.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_POST['diario_id'];
$periodo = $_SESSION['web_diario_periodo_id'];
$operacao = $_POST['operacao'];


$curso = $_POST['curso'];
$prova = $_POST['getprova'];

$grupo = ($sa_ref_pessoa . "-" . $periodo . "-" . get_disciplina($diario_id) . "-" . $diario_id);
$grupo_novo = ("%-" . $periodo_id . "-%-" . $diario_id);


if(empty($prova))
{
     print '<script language="javascript" type="text/javascript">
	window.alert("Você deve selecionar qual a prova que será lançada as notas.");
	javascript:window.history.back(1);
	</script>';
	exit;
}
else
{

$sql12 = 'SELECT * FROM (';
$sql12 .= "SELECT   DISTINCT
                    matricula.ordem_chamada, pessoas.nome, pessoas.id, SUM(d.nota) AS notaparcial
            FROM
                matricula
            INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
            INNER JOIN diario_notas d ON (d.id_ref_pessoas = pessoas.id AND
                                            d.id_ref_pessoas = matricula.ref_pessoa AND
                                            d.id_ref_periodos = '$periodo' AND
                                            d.d_ref_disciplina_ofer = $diario_id AND
                                            d.ref_diario_avaliacao <> '$prova'  AND
                                            d.ref_diario_avaliacao <> '7')
            WHERE
                (matricula.ref_disciplina_ofer = $diario_id) AND
                (matricula.dt_cancelamento is null) AND
				(matricula.ref_motivo_matricula = 0)

            GROUP BY
                     matricula.ordem_chamada, pessoas.nome, pessoas.id, d.id_ref_pessoas
            ORDER BY pessoas.nome ";

$sql12 .= ') AS T1 INNER JOIN (';

// d.rel_diario_formulas_grupo = '$grupo' AND

$sql12 .= "SELECT DISTINCT
               pessoas.id, d.nota AS notabanco
            FROM
               matricula INNER JOIN
               pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN
               diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                 d.id_ref_pessoas = matricula.ref_pessoa AND 
							     d.id_ref_periodos = '$periodo' AND 
								 d.d_ref_disciplina_ofer = $diario_id AND 
								 d.ref_diario_avaliacao = '$prova')
            WHERE
				(matricula.ref_disciplina_ofer = $diario_id) AND 
				(matricula.dt_cancelamento is null) AND 
				(matricula.ref_motivo_matricula = 0)";

// AND d.rel_diario_formulas_grupo = '$grupo'

$sql12 .= ') AS T2 ON (T2.id = T1.id) INNER JOIN (';


$sql12 .= "SELECT DISTINCT
               pessoas.id AS ref_pessoa, d.nota AS notaextra
            FROM
               matricula INNER JOIN
               pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN
               diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                 d.id_ref_pessoas = matricula.ref_pessoa AND 
								d.id_ref_periodos = '$periodo' AND 
								d.d_ref_disciplina_ofer = $diario_id AND 
								d.ref_diario_avaliacao = '7')
            WHERE
				(matricula.ref_disciplina_ofer = $diario_id) AND 
				(matricula.dt_cancelamento is null) AND 
				(matricula.ref_motivo_matricula = 0)";


// d.rel_diario_formulas_grupo = '$grupo'

$sql12 .= ') AS T3 ON (T3.ref_pessoa = T2.id) ORDER BY lower(to_ascii(nome));';

//die('<pre>'.$sql12.'</pre>');

$sql1 = "SELECT DISTINCT
  m.ordem_chamada,
  p.nome,
  p.id,
  p.ra_cnec
FROM
  matricula m
  INNER JOIN pessoas p ON (m.ref_pessoa = p.id)
WHERE
  (m.ref_periodo = '$periodo') AND
  (m.ref_disciplina_ofer = '$diario_id') AND
  (m.dt_cancelamento isnull) AND
  (m.ref_motivo_matricula = 0)
ORDER BY
  m.ordem_chamada;";

// (matricula.ref_disciplina = '$getdisciplina') AND

$alunos = $conn->get_all($sql12);


if($prova != 7)
{
	/* PROCESSO DE NOTA DISTRIBUIDA */

	$sqlNotaDistribuida = "
		SELECT nota_distribuida 
		FROM diario_formulas 
		WHERE 
		grupo ILIKE '%-$diario_id' AND 
		prova = '$prova'";

	$nota_distribuida = number::numeric2decimal_br($conn->get_one($sqlNotaDistribuida),1);
}

?>
<html>
<head>
<title><?=$IEnome?></title>
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">
</head>

<body>
<table cellspacing="0" cellpadding="0">
  <tr>
    <td>
    	<div align="left">
        <h3>
		   Lan&ccedil;amento / Altera&ccedil;&atilde;o da Nota
        <?php
		if($prova == 7) { 
			echo '<font color="blue"> Nota Extra</font> <font color="red" size="2">(Utilize apenas quando houver reavalia&ccedil;&atilde;o/recupera&ccedil;&atilde;o)</font>.' ;		  
		}
		else{	
			echo '<font color="blue"> P'. $prova .'</font>.'; 
		}
		echo '</h3>';
?>
</div></td>
  </tr>
</table>
<form name="form1" method="post" action="<?=$BASE_URL .'app/web_diario/professor/notas/grava_notas.php'?>">

	<input type="hidden" name="codprova" id="codprova" value="<?=$prova?>">
	<input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">
	<input type="hidden" name="operacao" id="operacao" value="<?=$operacao?>">

<?=papeleta_header($diario_id)?>

<br />

<?php
	if($prova != 7) :
?>
	<p><strong>Nota distribu&iacute;da:</strong>
	<input name="valor_avaliacao" type="text" id="valor_avaliacao" size="8" value="<?=$nota_distribuida?>">
	</p>
<?php endif; ?>
<br />
<table cellspacing="0" cellpadding="0" class="papeleta">
  <tr bgcolor="#666666">

  <td align="center"><font color="#FFFFFF"><strong>Ordem</strong></font></td>
  <td align="center"><font color="#FFFFFF"><strong>Nota</strong></font></td>
      <td><font color="#FFFFFF"><b>&nbsp;Matr&iacute;cula</b></font></td>
  <td><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
 </tr>
 <?php

   $st = '';
   $ordem = 1;
   foreach($alunos as $aluno) :
   
      $notaprova = $aluno['notabanco'];
      $nota_parcial = $aluno['notaparcial'];

      if($prova == 7 && $nota_parcial > 59.999) {

			continue;
	  }

      if($notaprova < 0 )
      {
      		$notaprova = '';
      }
      else {
      		$notaprova = number::numeric2decimal_br($notaprova,1);
      }

      if($st == '#F3F3F3')
      {
         $st = '#E3E3E3';
      }
      else
      {
         $st ='#F3F3F3';
      }
?>
      <tr bgcolor="<?=$st?>"> <td align="center"><?=$ordem?></td>
		<td align="center">
	   <input name="notas[<?=$aluno['ref_pessoa']?>]" id="notas[<?=$aluno['ref_pessoa']?>]" type="text" onBlur="MM_validateForm('textfield','','RinRange0:10');return document.MM_returnValue" value="<?=$notaprova?>" size="4" maxlength="4">
	  <input type="hidden" name="matricula[]" value="<?=$aluno['ref_pessoa']?>"></td>
            <td><?=$aluno['ref_pessoa']?></td>
            <td><?=$aluno['nome']?></td>
            </tr>

<?php   
		$ordem++; 
		endforeach; 
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
  <input type="submit" name="Submit" value="Gravar notas">
&nbsp;&nbsp;ou&nbsp;
<a href="#" onclick="javascript:window.close();">cancelar</a>
</form>
<br />
<br />
</script>
</body>
</html>
<?php } ?>
