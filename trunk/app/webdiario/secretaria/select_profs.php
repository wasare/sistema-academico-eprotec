<?php

    include_once('verifica_acesso.php');

    include_once('../conf/webdiario.conf.php');

    if($_SESSION['periodo'] === "" OR !isset($_SESSION['periodo']))
    {
        echo '<script language="javascript">
                window.alert("ERRO! Primeiro selecione um periodo!");
        </script>';
        //sleep(2);
        echo '<meta http-equiv="refresh" content="0;url=select_periodos.php">';
        exit;
    }

    $msg = '';
	
    if ($_SESSION['select_prof'] == "" OR !isset($_SESSION['select_prof']))
    {
        $msg = 'N&atilde;o Selecionado!';
    }
    else
    {
        $qryProf = 'SELECT DISTINCT id, nome FROM pessoas WHERE id = '. $_SESSION['select_prof'].';';

    $qry1 = consulta_sql($qryProf);

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
                $msg = @$linha['nome'] .' ('.@$linha['id'].')';
            }
         }

      }
	}

	$qryP = 'SELECT DISTINCT d.id, d.descricao FROM periodos d WHERE d.id = \''. $_SESSION['periodo'].'\';';

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
                    $dsc_periodo = @$linha['descricao'];
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
            <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Sele&ccedil;&atilde;o de Professor(a)</strong></font></div></td>
            </tr></table>
			 
				<h4><font color="orange"><b>Per&iacute;odo Corrente: </b></font><font color="blue"><?php echo $dsc_periodo; ?></font></h4>
		  
		      <h4><font color="orange"><b>Professor(a) Corrente: </b></font><font color="blue"><?php echo $msg; ?></font></h4>
	

				<p><b>Selecione o professor:</b><br /> <br />

<?php	
//	getPeriodosSecretaria();

	$sqlprofs = 'SELECT 
					id, nome 
				 FROM 
					pessoas 
				WHERE 
					id IN ( 
							SELECT 
								DISTINCT 
									d1.ref_professor 
								FROM 
									disciplinas_ofer_prof d1, disciplinas_ofer d2 
								WHERE 
									d1.ref_disciplina_ofer = d2.id AND 
									d1.ref_professor IS NOT NULL AND 
									d2.ref_periodo = \''.$_SESSION['periodo'].'\'
							) 
				ORDER BY  to_ascii(nome);';

	//echo $sqlprofs; die;

    $qry1 = consulta_sql($sqlprofs);

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
                $nomeprof = @$linha['nome'];
                $codprof = @$linha['id'];
    		
				//echo '<option value="'.$codprof.'">'.$codprof.' - '.$nomeprof.'</option>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<a href="?prof='.$codprof.'">'.$codprof.' - '.$nomeprof.'</a><br />';

             }
				//	echo '</select></p></form>';            
          }

      }



	if($_GET['prof'] != "" AND isset($_GET['prof']))
	{
		$_SESSION['select_prof'] = $_GET['prof'];

		//print_r($_SESSION);
		
		unset($_GET['prof']);
	
//		echo '<script language="javascript">   window.alert("Professor(a) selecionado(a)!");</script>';
		//sleep(2);
		echo '<meta http-equiv="refresh" content="0;url=diarios_secretaria.php">';
//		header("Location: select_periodos.php");
		//exit;
		// javascript:window.history.back(1);
						 
	}


?>

</body>
</html>
