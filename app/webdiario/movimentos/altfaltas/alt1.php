<?php
   include_once('../../webdiario.conf.php');

   $getdisciplina = $_GET['disc'];
   $getofer = $_GET['ofer'];
   $getperiodo = $_SESSION['periodo'];
   $id = $_SESSION['id'];
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">

<script language=javascript>
function jsConfirm(dia)
{
   if (! dia == "")
   {
    if (! confirm('Você realmente deseja apagar \n a chamada do dia ' + dia + '?' + '\nTodas as faltas lançadas nesta data, \n caso existam, serão excluídas!'))
      {
         //window.history.back(2); 
         //self.location = ();
         return false; 
      } 
      else 
      {
         document.getElementById('envia').submit();
         return true;
      }
   }
   else return false;
}

</script>

</head>

<body bgcolor="#FFFFFF" text="#000000">

<table width="471" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Exclus&atilde;o de Chamada</strong></font></div></td>
  </tr>
  <tr> 
    <td> <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><img src="../../img/atencao.gif" width="49" height="11">: 
        Este processo exclui a chamada do dia selecionado, portanto o professor dever&aacute; refazer a chamada.</strong></font></p>
      <div align="center"></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
 echo getHeaderDisc($_GET['ofer']);
  

 $sql4 = "SELECT DISTINCT
                 id,
                 id_prof,
                 periodo,
                 curso,
                 disciplina,
                 dia,
                 ref_disciplina_ofer as idof
                 FROM
                 diario_seq_faltas
                 WHERE
                 periodo = '$getperiodo' AND
                 ref_disciplina_ofer = '$getofer' ORDER BY dia DESC; ";

				 
		$qry4 = consulta_sql($sql4);

		if(is_string($qry4))
		{
			echo $qry4;
			exit;
		}

   echo '<form name="envia" id="envia" enctype="multipart/form-data" action="excluichamada.php" method="post">';
 
   echo '<input type="hidden" name="id" id="id" value="' .$_SESSION['id'].'">';
   echo '<input type="hidden" name="periodo" id="periodo" value="' . $getperiodo.'">';
   echo '<input type="hidden" name="disc" id="disc" value="' .$getdisciplina.'">';
   echo '<input type="hidden" name="ofer" id="ofer" value="' . $getofer.'">';

   echo '<p>Selecione a data da chamada a excluir:</p>
      <p><select name="senddata" id="senddata" class="select" onchange="jsConfirm(this.value);">
		<option value="">--- data de chamada ---</option>';
		
	while($row4 = pg_fetch_array($qry4))
    {
       $nc = $row4['dia'];
       $idnc = $row4['dia'];
       echo '<option value="'.$idnc.'">'.$nc.'</option>';
    }
    
	echo '</select></form>';

?>
</form>
</body>
</head>
</html>
