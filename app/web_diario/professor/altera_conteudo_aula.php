<?php

require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/date.php');
require_once($BASE_DIR .'app/matricula/atualiza_diario_matricula.php');

$conn = new connection_factory($param_conn);

$flag = $_GET['flag'];
$data_chamada = $_GET['data_chamada'];

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

if(!is_numeric($flag) || !is_numeric($diario_id) AND !isset($_POST['ok']))
{
    exit('<script language="javascript">
                window.alert("ERRO! Conteudo de aula invalido!");
                window.close();
    </script>');
}


if(isset($_POST['ok']) AND $_POST['ok'] == 'OK1')
{
    
	$sql1 = 'UPDATE diario_seq_faltas SET conteudo = \''.$_POST['texto'].'\' WHERE id = '.$_POST['flag'].';';
   
	$q = $conn->Execute($sql1);
    
    if($q === FALSE)
    {
		envia_erro($sql1 ."\n". $conn->ErrorMsg());
        exit;
    }

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
			   
	$conteudo = $conn->adodb->getOne($sql1);

	if($conteudo === FALSE)
	{
		envia_erro(__FILE__ . "\n" . $sql1 ."\n". $conn->ErrorMsg());
		exit;
	}
}

?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN\">
<tml>
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
          <textarea name="texto" cols="100" rows="15"><?=$conteudo?></textarea>
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
