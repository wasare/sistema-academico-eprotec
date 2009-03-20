<?php

    require_once('../webdiario.conf.php');

 	require_once($BASE_DIR_WEBDIARIO .'conf/verifica_acesso.php');
	
	$msg = '';
	
	if ($_SESSION['select_periodo'] == "" OR !isset($_SESSION['select_periodo']))
	{
		$msg = 'N&atilde;o Selecionado!';
	}
	else
	{
		$qryP = 'SELECT DISTINCT d.id, d.descricao FROM periodos d WHERE d.id = \''. $_SESSION['select_periodo'].'\';';
		
		$qry1 = consulta_sql($qryP);

		if(is_string($qry1))
		{
			echo $qry1;
			exit;
		}
		else
		{
			if(pg_numrows($qry1) == 1)
			{
				while($linha = pg_fetch_array($qry1))
				{
					$msg = @$linha['descricao'];
				}
			}
      }

	}

?>

<html>
<head>
<a name="topo">
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">

<script src="../js/event-listener.js" type="text/javascript"></script>
<script src="../js/enter-as-tab.js" type="text/javascript"></script>

</head>
<body onLoad="javascript:document.form1.reset()">

<table width="90%" height="73" border="0">
              <tr>
            <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Sele&ccedil;&atilde;o de Per&iacute;odo</strong></font></div></td>
            </tr></table>

			<h4><font color="orange"><b>Per&iacute;odo Corrente: </b></font><font color="blue"><?php echo $msg; ?></font></h4>
			

			<p><b>Selecione o per&iacute;odo:</b><br /> <br />

<?php	
	//$sqlperiodos = 'SELECT DISTINCT d.id, d.descricao FROM periodos d ORDER BY d.id DESC;';


	$sqlperiodos = 'SELECT DISTINCT 
						d.id, d.descricao 
					FROM 
					periodos d, disciplinas_ofer o 
					WHERE
						ref_periodo = d.id AND
						ref_curso IN ('.$_SESSION['cursosc'].')
					ORDER BY d.id DESC;';
	
	$qry1 = consulta_sql($sqlperiodos);


	
      if(is_string($qry1))
      {
        echo $qry1;
        exit;
      }
      else
      {
          if(pg_numrows($qry1) > 0)
          {
          	while($linha = pg_fetch_array($qry1))
            {
                $nomeperiodo = @$linha['descricao'];
                $codiperiodo = @$linha['id'];

    			//echo '<option value="'.$codiperiodo.'">'.$nomeperiodo.'</option>';

				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<a href="?p='.$codiperiodo.'">'.$nomeperiodo.'</a><br />';
             }
    			//echo '</select></p></form>';            
          }

      }



	if($_GET['p'] != "" AND isset($_GET['p']))
	{

		
		$_SESSION['select_periodo'] = $_GET['p'];
		$_SESSION['periodo'] = $_GET['p'];
		//print_r($_SESSION);
		
		unset($_GET['p']);
	
		// echo '<script language="javascript">  window.alert("Período selecionado!"); 	</script>';
		//sleep(2);
		echo '<meta http-equiv="refresh" content="0;url=select_cursos.php">';
		//header("Location: select_periodos.php");
		//exit;
		// javascript:window.history.back(1);
						 
	}


?>

</body>
</html>
