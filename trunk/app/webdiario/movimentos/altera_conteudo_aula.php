<?php

include ('../conf/webdiario.conf.php');


$flag = $_GET['flag'];


if(isset($_POST['ok']) AND $_POST['ok'] == 'OK1')
{
    
  
    $vars = "id=".@$_POST['idp']."&getperiodo=".@$_POST['periodo']."&disc=".@$_POST['disc']."&ofer=".@$_POST['ofer'];

    $sql1 = 'UPDATE diario_seq_faltas SET conteudo = \''.$_POST['texto'].'\' WHERE id = '.$_POST['flag'].';';
   
     $q = consulta_sql($sql1);
    
     if(is_string($q))
     {
		echo $q;
        exit;
     }

   print ('<script type="text/javascript"> 
   window.alert("Conteudo ide aula alterado com sucesso !! ");
   self.location.href = "../diarios.php?periodo='.$_POST['periodo'].'"</script>');
    //window.open("conteudoaula.php?'.$vars.'");</script>');
    //self.location.href = "conteudoaula.php?'.$vars.'"</script>');
	
}

//$vars = "id=".$id."&getperiodo=". $getperiodo."&disc=".@$getdisciplina."&ofer=".@$getofer;

$sql1 ="SELECT id,
               conteudo
               FROM
               diario_seq_faltas
               WHERE
               id = '$flag';";
			   
$qry1 = consulta_sql($sql1);

if(is_string($qry1))
{
	echo $qry1;
    exit;
}

while($linha1 = pg_fetch_array($qry1)) 
{

	$result = $linha1['conteudo'];
	
print('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN\">
<tml>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>

<form name="teste" method="post" action="altera_conteudo_aula.php">

<table width="92%" height="220" border="0">

<input type="hidden" name="flag" value="'.$flag.'" />
<input type="hidden" name="ok" value="OK1" />

<input type="hidden" name="idp" id="id" value="' .$_GET['id'].'">
<input type="hidden" name="periodo" id="periodo" value="' . $_GET['getperiodo'].'">
<input type="hidden" name="disc" id="disc" value="' .$_GET['disc'].'">
<input type="hidden" name="ofer" id="ofer" value="' . $_GET['ofer'].'">
	 

  <tr>
    <td colspan="3"><div align="center"><font color="#FF0000" size="5" face="Verdana, Arial, Helvetica, sans-serif"><strong>Altera&ccedil;&atilde;o de Conte&uacute;do de Aula</strong></font></div></td>
  </tr>
  <tr>
    <td colspan="3">Conte&uacute;do:</td>
  </tr>
  <tr>
    <td colspan="3">
        <div align="center">
          <textarea name="texto" cols="100" rows="15">'.$result.'</textarea>
        </div>
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
        <div align="center">
          <input type="submit" value="Atualizar">
        </div>
      </td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>');

}
?>
