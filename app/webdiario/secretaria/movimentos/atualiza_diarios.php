<?php

    include_once('../verifica_acesso.php');

    include_once('../../conf/webdiario.conf.php');

    //print_r($_GET);

if(isset($_GET['btnOK']) && $_GET['btnOK'] == 'sim' ) {
	
    if (!is_numeric($_GET['oferecida']))
    {
       
		unset($_GET['oferecida']);
        unset($_GET['btnOK']);
        $_GET = array();
 
		print '<script language=javascript> window.alert("Código do diário não informado!"); </script>';
    }
    else
    {

       $qryDisc = " SELECT 
						DISTINCT 
							prof.ref_professor, o.ref_disciplina, o.ref_periodo 
						FROM 
							disciplinas_ofer o, disciplinas_ofer_prof prof
            WHERE
                 o.id = '".$_GET['oferecida']."' AND 
				 o.is_cancelada = 0 AND
				 o.id = prof.ref_disciplina_ofer ;";

    //echo $qryDisc; die;
    $qry1 = consulta_sql($qryDisc);

      if(is_string($qry1)) {

        echo $qry1;
        exit;
      }
      else {

		  if(pg_numrows($qry1) > 0) {

            while($linha = pg_fetch_array($qry1))
            {
                $d = @$linha['ref_disciplina'];
				$p = @$linha['ref_periodo'];
				$prof = @$linha['ref_professor'];
            }

		     $vars = "getperiodo=". $p ."&disc=". $d."&ofer=".@$_GET['oferecida']."&refprof=".$prof;

			 unset($_GET['oferecida']);
			 unset($_GET['btnOK']);
			 $_GET = array();
			
			 header("Location: ../../movimentos/resolve_pendencias.php?$vars");			 		
         }
         else {
				 print '<script language=javascript> window.alert("Diário não encontrado ou cancelado!"); </script>';
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
<link rel="stylesheet" href="<?php echo $CSS_DIR.'forms.css'; ?>" type="text/css">

<script src="../js/event-listener.js" type="text/javascript"></script>
<script src="../js/enter-as-tab.js" type="text/javascript"></script>

</head>
<body onLoad="javascript:document.form1.reset()">

<table width="90%" height="73" border="0">
              <tr>
            <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Atualiza&ccedil;&atilde;o de Di&aacute;rios para o SAGU</strong></font></div></td>
            </tr></table>
			 

                    <h2><font color="red" size="3">ATEN&Ccedil;&Atilde;O!</font> </h2>

 <h4>
Esta fun&ccedil;&atilde;o atualiza o di&aacute;rio informado, executando as mesmas rotinas da fun&ccedil;&atilde;o "Resolver Ped&ecirc;ncias", verifica e resolve problemas de:
<br />
<font color="#330099" size="3">
<br /> 
* Registro de notas, aluno que n&atilde;o consta na lista, pois foi inclu&iacute;do posteriormente;
<br />
* Somat&oacute;rio de faltas errado;
<br /> 
* Somat&oacute;rio de notas errado, considera inclusive a nota extra, caso tenha sido lan&ccedil;ada.
</font>
</h4>

<br />
<form id="form1" name="form1" method="get" action="">

C&oacute;digo do Di&aacute;rio: <input id="oferecida" name="oferecida" type="text" size="4" maxlength="6" />

<input type="hidden" id="btnOK" name="btnOK" value="sim" />
<input type="submit" id="envia" name="envia" value="Verifica e Atualiza -->" />

</form>

</body>
</html>
