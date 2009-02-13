<?php


    include_once('../verifica_acesso.php');

    include_once('../../conf/webdiario.conf.php');

    if($_SESSION['select_periodo'] === "" OR !isset($_SESSION['select_periodo']))
    {
        echo '<script language="javascript">
                window.alert("ERRO! Primeiro selecione um periodo!");
        </script>';
        //sleep(2);
        echo '<meta http-equiv="refresh" content="0;url=andamento_periodo.php">';
        exit;
    }

    $msg = '';
	
    if ($_SESSION['select_curso'] == "" OR !isset($_SESSION['select_curso']))
    {
        $msg = 'N&atilde;o Selecionado!';
    }
    else
    {
		
	   // print_r($_SESSION);

       $qryCurso = " SELECT DISTINCT
    a.ref_curso || ' - ' || c.descricao AS curso, ref_tipo_curso
      FROM
          disciplinas_ofer a, disciplinas_ofer_prof b, cursos c
            WHERE
                a.ref_periodo = '".$_SESSION['select_periodo']."' AND
                    a.id = b.ref_disciplina_ofer AND
                        c.id = a.ref_curso AND
                         a.ref_curso = ".$_SESSION['select_curso']." AND
                            ref_professor IS NOT NULL
            ORDER BY ref_tipo_curso;";

    $qry1 = consulta_sql($qryCurso);

      if(is_string($qry1))
      {
        echo $qry1;
        exit;
      }
      else
      {
          if(pg_numrows($qry1) != 0)
          {
            while($linha = pg_fetch_array($qry1))
            {
                $msg = @$linha['curso'];
            }
         }

      }
	}

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
<script src="<?php echo $BASE_URL.'lib/js/jslib.js';?>" type="text/javascript"></script>
</head>
<body>

<table width="90%" height="73" border="0">
              <tr>
            <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Resumo de Notas e Faltas no Per&iacute;odo</strong></font></div></td>
            </tr></table>
			 
				<h4><font color="orange"><b>Per&iacute;odo: </b></font><font color="blue"><?php echo $dsc_periodo; ?></font></h4>
		  
		      <h4><font color="orange"><b>Curso: </b></font><font color="blue"><?php echo $msg; ?></font></h4>

				<?php

					if(strlen($msg) > 0 && isset($_SESSION['select_curso'])) {

						$sqlTurmas = "SELECT DISTINCT 
										turma 
									FROM 
										disciplinas_ofer 
									WHERE 
										ref_periodo = '".$_SESSION['select_periodo']."' AND 
										ref_curso = ".$_SESSION['select_curso'].";"; 

                        $qryT = consulta_sql($sqlTurmas);

                        if(is_string($qryT))
      					{
        					echo $qryT;
        					exit;
      					}
      					else
      					{
          					if(pg_numrows($qryT) > 1)
          					{
								
								echo '<h5>Resumo por turma: &nbsp;&nbsp;&nbsp; ';
            					while($linha = pg_fetch_array($qryT))
            					{
                					$t = @$linha['turma'];

									//echo '<a href="'.$BASE_URL.'consultas/andamento_consulta.php?t='.$t.'">'.$t.'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
									echo '<a href="#" onclick="javascript:openWin(\'Resumo de Notas e Faltas\',\''.$BASE_URL.'consultas/andamento_consulta.php?t='.$t.'\');">'.$t.'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            					}
         					}
                            else {

			
								  echo '<a href="#" onclick="javascript:openWin(\'Resumo de Notas e Faltas\',\''.$BASE_URL.'consultas/andamento_consulta.php?t=0\');">Ver Resumo da turma</a>';					
							}
							echo '<br />';
      					}

					}
				?>
	
				<p><b>Selecione o curso:</b><br /> <br />

<?php

	
     $qryCursos = " SELECT DISTINCT
    a.ref_curso || ' - ' || c.descricao AS curso, a.ref_curso, ref_tipo_curso
      FROM
          disciplinas_ofer a, disciplinas_ofer_prof b, cursos c
            WHERE
                a.ref_periodo = '".$_SESSION['select_periodo']."' AND
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
    		
				//echo '<option value="'.$codprof.'">'.$codprof.' - '.$nomeprof.'</option>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                echo '<a href="?curso='.$curso_id.'">'.$nome_curso.'</a><br />';

             }
          }

      }



	if($_GET['curso'] != "" AND isset($_GET['curso']))
	{
		$_SESSION['select_curso'] = $_GET['curso'];

		//print_r($_SESSION);
		
		//header("Location: diarios.php?periodo=". $_SESSION['periodo']);		

		unset($_GET['curso']);
	
	//	echo '<script language="javascript">   window.alert("Curso selecionado!"); </script>';
		//sleep(2);
		echo '<meta http-equiv="refresh" content="0;url=andamento_curso.php">';
//		header("Location: select_periodos.php");
		//exit;
		// javascript:window.history.back(1);
						 
	}


?>

</body>
</html>
