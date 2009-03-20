<?php

    include_once('verifica_acesso.php');

    include_once('../webdiario.conf.php');

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
	
    if ($_SESSION['select_curso'] == "" OR !isset($_SESSION['select_curso']))
    {
        $msg = 'N&atilde;o Selecionado!';
    }
    else
    {
        $qryCurso = 'SELECT DISTINCT id, descricao AS nome FROM cursos WHERE id = '. $_SESSION['select_curso'].';';

    $qry1 = consulta_sql($qryCurso);

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
                $msg = @$linha['id'] .' - '.@$linha['nome'].')';
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
            <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Sele&ccedil;&atilde;o de Curso</strong></font></div></td>
            </tr></table>
			 
				<h4><font color="orange"><b>Per&iacute;odo Corrente: </b></font><font color="blue"><?php echo $dsc_periodo; ?></font></h4>
		  
		      <h4><font color="orange"><b>Curso Corrente: </b></font><font color="blue"><?php echo $msg; ?></font></h4>
	

				<p><b>Selecione o curso:</b><br /> <br />

<?php	
//	getPeriodosSecretaria();
	$qryCursos = " SELECT DISTINCT
    a.ref_curso || ' - ' || c.descricao AS curso, a.ref_curso, ref_tipo_curso
      FROM
          disciplinas_ofer a, disciplinas_ofer_prof b, cursos c
            WHERE
                a.ref_periodo = '".$_SESSION['periodo']."' AND
				a.ref_curso IN (".$_SESSION['cursosc'].") AND
                    a.id = b.ref_disciplina_ofer AND
                        c.id = a.ref_curso AND
                            ref_professor IS NOT NULL
            ORDER BY ref_tipo_curso;";


    $qry1 = consulta_sql($qryCursos);

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
                $nome_curso = @$linha['curso'];
                $curso_id = @$linha['ref_curso'];

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<a href="?curso_s='.$curso_id.'">'.$nome_curso.'</a><br />';

             }
          }

      }





	if($_GET['curso_s'] != "" AND isset($_GET['curso_s']))
	{
		$_SESSION['select_curso'] = $_GET['curso_s'];

		//print_r($_SESSION);
		
		unset($_GET['curso_s']);
	
//		echo '<script language="javascript">   window.alert("Professor(a) selecionado(a)!");</script>';
		//sleep(2);
		echo '<meta http-equiv="refresh" content="0;url=diarios_coordenacao.php">';
//		header("Location: select_periodos.php");
		//exit;
		// javascript:window.history.back(1);
						 
	}


?>

</body>
</html>
