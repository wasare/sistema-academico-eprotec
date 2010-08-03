<?php

require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/date.php');

$conn = new connection_factory($param_conn);

$flag = (isset($_POST['ok'])) ? (int) $_POST['flag'] : (int) $_GET['flag'];
$data_chamada = $_GET['data_chamada'];
$diario_id = isset($_GET['diario_id']) ? (int) $_GET['diario_id'] : (int) $_POST['diario_id'];


if ((!isset($_POST['ok']) && $diario_id == 0) || $flag == 0)
    exit('<script language="javascript" type="text/javascript">window.alert("ERRO! Dados invalidos!");window.close();</script>');

if (is_finalizado($diario_id))
    exit('<script language="javascript" type="text/javascript">window.alert("Diario fechado para alteracoes!");window.close();</script>');

//  VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR
if ($_SESSION['sa_modulo'] == 'web_diario_login') {
  if(!acessa_diario($diario_id,$sa_ref_pessoa)) {

    exit('<script language="javascript" type="text/javascript">
            alert(\'Voc� n�o tem direito de acesso a estas informa��es!\');
            window.close();</script>');
  }
}
// ^ VERIFICA O DIREITO DE ACESSO AO DIARIO COMO PROFESSOR OU COORDENADOR ^ //


if(isset($_POST['ok']) && $_POST['ok'] == 'OK1') {
    
	$sql1 = 'UPDATE diario_seq_faltas SET conteudo = \''.$_POST['texto'].'\' WHERE id = '.$_POST['flag'].';';
   
	$q = $conn->Execute($sql1);

	echo '<script type="text/javascript">  window.alert("Conteudo de aula alterado com sucesso! ");';
	if(isset($_SESSION['web_diario_do']))
		echo 'self.location.href = "'. $BASE_URL .'app/web_diario/requisita.php?do='. $_SESSION['web_diario_do'] .'&id='.$_POST['diario_id'];
	else
		echo 'self.location.href = "'. $BASE_URL .'app/relatorios/web_diario/conteudo_aula.php?diario_id='.$_POST['diario_id'];
	
	echo '"</script>';
}
else
{
	$sql1 = "SELECT 
		conteudo
               FROM
               diario_seq_faltas
               WHERE
               id = $flag;";
			   
	$conteudo = $conn->get_one($sql1);
}

?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title><?=$IEnome?> - Altera&ccedil;&atilde;o de conte&uacute;do de aula</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">
</head>
<body>

<br />
<div align="left" class="titulo1">
        Altera&ccedil;&atilde;o de conte&uacute;do de aula
</div>
<br />
<?=papeleta_header($diario_id)?>
<br />

<form name="conteudo_aula" id="conteudo_aula" method="post" action="altera_conteudo_aula.php">
<table cellspacing="0" cellpadding="0" class="papeleta">

<input type="hidden" name="flag" value="<?=$flag?>" />
<input type="hidden" name="ok" value="OK1" />

<input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">

  <tr>
    <td colspan="3"><strong>Data chamada: <?=$data_chamada?></strong></td>
  </tr>
  <tr>
    <td colspan="3">
        <div align="center">
          <textarea name="texto" cols="80" rows="10"><?=$conteudo?></textarea>
        </div>
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
        <div align="center">
          <input type="submit" name="atualizar" id="atualizar" value="Atualizar">
		  &nbsp;&nbsp;&nbsp;
          <a href="#" onclick="javascript:history.back();">Cancelar</a>
        </div>
      </td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
