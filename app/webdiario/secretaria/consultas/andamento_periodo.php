<?php

    include_once('../verifica_acesso.php');

    include_once('../../conf/webdiario.conf.php');

	
	$msg = '';
?>

<html>
<head>
<a name="topo">
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../../css/forms.css" type="text/css">

<script src="../js/event-listener.js" type="text/javascript"></script>
<script src="../js/enter-as-tab.js" type="text/javascript"></script>

</head>
<body onLoad="javascript:document.form1.reset()">

<table width="90%" height="73" border="0">
              <tr>
            <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Resumo de Notas e Faltas no Per&iacute;odo</strong></font></div></td>
            </tr>
</table>

<p><b>Selecione o per&iacute;odo:</b><br /> <br />

<?php	

	$sqlperiodos = "SELECT DISTINCT d.id, d.descricao FROM periodos d WHERE dt_inicial > '01/02/2005' AND id IN ( SELECT DISTINCT ref_periodo FROM matricula ) ORDER BY d.id DESC;";

	//echo $sqlperiodos;

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
          }

      }



	if($_GET['p'] != "" AND isset($_GET['p']))
	{

		
		$_SESSION['select_periodo'] = $_GET['p'];

		//print_r($_SESSION);
		
		//header("Location: diarios.php?periodo=". $_SESSION['periodo']);		

		unset($_GET['p']);
	
//		echo '<script language="javascript">   window.alert("Período selecionado!"); </script>';
		//sleep(2);
		echo '<meta http-equiv="refresh" content="0;url=andamento_curso.php">';
//		header("Location: select_periodos.php");
		//exit;
		// javascript:window.history.back(1);
						 
	}


?>

</body>
</html>
