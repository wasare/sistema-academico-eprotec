<?php

require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/date.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['diario_id'];

if(!is_numeric($diario_id))
    exit('<script language="javascript" type="text/javascript">window.alert("ERRO! Diario invalido!");window.close();</script>');

//  VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR
if(isset($_SESSION['sa_modulo']) && $_SESSION['sa_modulo'] == 'web_diario_login') {
  if(!acessa_diario($diario_id,$sa_ref_pessoa)) {

    exit('<script language="javascript" type="text/javascript">
            alert(\'Você não tem direito de acesso a estas informações!\');
            window.close();</script>');
  }
  // ^ VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR ^ //
}


$sql1 ="SELECT id,
               dia,
               conteudo,
	       flag
               FROM
               diario_seq_faltas
               WHERE
               ref_disciplina_ofer = $diario_id 
               ORDER BY dia desc;";


$conteudos = $conn->get_all($sql1);

if(count($conteudos) == 0) 
  exit('<script language="javascript" type="text/javascript">window.alert("Nenhuma conteudo registrado para este diario!");window.close(); </script>');


?>
<html>
<head>
<title><?=$IEnome?> - conte&uacute;do de aula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<body>

<div align="left" class="titulo1">
   Conte&uacute;do de Aula
</div>
<br /><br />
<?=papeleta_header($diario_id)?>

<br />

<div align="left">
  <font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
    <strong>*Para alterar o conte&uacute;do de aula clique na data da chamada!</strong>
  </font>
</div>



<table cellspacing="0" cellpadding="0" class="papeleta">
  <tr bgcolor="#666666"> 
    <td align="center">
    	<div align="center"><font color="#FFFFFF">&nbsp;</font><b><font color="#FFFFFF">DATA</font></b></div>
    </td>
    <td align="center"><font color="#FFFFFF"><b>AULAS</b></font></td>
    <td><font color="#FFFFFF"><b>CONTE&Uacute;DO DE AULA</b></font></td>
  </tr>
<?php 

$st = '';
	
foreach($conteudos as $linha1) :
  
	$data_chamada = $linha1["dia"];
	$conteudo = $linha1["conteudo"];
	$chamada_id = $linha1["id"];
	$aulas = $linha1["flag"];

	if ( $st == '#F3F3F3') $st = '#E3E3E3'; else  $st ='#F3F3F3'; 
?>

  <tr bgcolor="<?=$st?>">
    <td align="center">
      <a href="<?=$BASE_URL?>app/web_diario/professor/altera_conteudo_aula.php?flag=<?=$chamada_id?>&diario_id=<?=$diario_id?>&data_chamada=<?=date::convert_date($data_chamada)?>" title="clique para alterar" alt="clique para alterar"><?=date::convert_date($data_chamada)?></a>
    </td>
    <td align="center"><?=$aulas?></td>
	<td><?=$conteudo?></td>
  </tr>

<?php
   endforeach;
?>

</table>
<br><br>
<input type="button" value="Imprimir" onClick="window.print()">
&nbsp;&nbsp;
<a href="#" onclick="javascript:window.close();">Fechar</a>
</body>
</html>
