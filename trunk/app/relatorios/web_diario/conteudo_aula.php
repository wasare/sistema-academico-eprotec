<?php

require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/date.php');
require_once($BASE_DIR .'app/matricula/atualiza_diario_matricula.php');


$conn = new connection_factory($param_conn);

$diario_id = $_GET['diario_id'];

if(!is_numeric($diario_id))
{
    echo '<script language="javascript">
                window.alert("ERRO! Diario invalido!");
                window.close();
    </script>';
    exit;
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


$conteudos = $conn->adodb->getAll($sql1);

if($conteudos === FALSE)
{
    envia_erro($sql1);
    exit;
}
else {
    if(count($conteudos) == 0) {

        echo '<script language="javascript">window.alert("Nenhuma conteudo registrado para este diario!"); javascript:window.close(); </script>';
      exit;
    }

}


?>
<html>
<head>
<title><?=$IEnome?> - conte&uacute;do de aula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<body>
<table cellspacing="0" cellpadding="0" border="0">
  <tr> 
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Conte&uacute;do de Aula</strong></font></div></td>
  </tr>
  <tr> 
  <td>
<?php

echo papeleta_header($diario_id);
						   
?>
  </td>
  </tr>
  <tr>
    <td><div align="left"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>*Para alterar o conte&uacute;do de aula clique na data da chamada!</strong></font></div></td>
  </tr>
</table>
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
	
foreach($conteudos as $linha1) 
{
	// $result2 = br_date($linha1["dia"]);
	$data_chamada = $linha1["dia"];
	$conteudo = $linha1["conteudo"];
	$chamada_id = $linha1["id"];
	$aulas = $linha1["flag"];
	if ( $st == '#F3F3F3') 
	{
		$st = '#E3E3E3';
	} 
	else 
	{	
		$st ='#F3F3F3';
	} 

	print ('<tr bgcolor="'.$st.'">
                <td align="center"><a href="'. $BASE_URL .'app/web_diario/professor/altera_conteudo_aula.php?flag='. $chamada_id .'&diario_id='. $diario_id .'&data_chamada='. date::convert_date($data_chamada) .'" title="clique para alterar" alt="clique para alterar">'. date::convert_date($data_chamada) .'</a></td>
				<td align="center">'.$aulas.'</td> 
				<td>'.$conteudo.'</td>
			</tr>');
	}
?>

</table>
<br><br>
<input type="button" value="Imprimir" onClick="window.print()">
&nbsp;&nbsp;&nbsp;
<input type="button" name="fechar" id="fechar" value="Fechar" onclick="javascript:window.close();" />
</body>
</html>
