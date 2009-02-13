<?php

	
include_once('conf/webdiario.conf.php');


// CONECTA BD

//ini_set("display_errors",1);

//print_r($_GET);
//print_r($_SESSION);


unset($_SESSION['conteudo']);
unset($_SESSION['flag_falta']);


//$_SESSION['periodo'] = $_GET['periodo'];

$diario = @explode("|", $_GET['diario']);


if(isset($_GET['id']) AND ( !is_numeric($diario['0']) OR !is_numeric($diario['1'])) )
{
	
     echo '<script language="javascript">
	 		window.alert("ERRO! Primeiro selecione um diário!"); javascript:window.history.back(1);
	 </script>';

	 //header("Location: diarios.php?periodo=". $_SESSION['periodo']);
	 
      exit;

}
else
{

	if($diario['2'] === '1' && in_array($_GET['acao'], $Movimento) ) {
 
     echo '<script language="javascript">
            window.alert("ERRO! Este diário está fechado e não pode ser alterado!"); javascript:window.history.back(1);
     </script>';

     //header("Location: diarios.php?periodo=". $_SESSION['periodo']);

      exit;
    }


	$vars = "id=".$_SESSION['id']."&getperiodo=". $_SESSION['periodo']."&disc=".@$diario['0']."&ofer=".@$diario['1'];


	$vars_b = "id=".$_SESSION['id']."&getperiodo=". $_SESSION['periodo'];
	
//	https://sagu.cefetbambui.edu.br/webdiario/2007N/movimentos/lancanotas/resolve_pendencias.php?grupo=3660-0701-307008-1896&id=3660&curso=307&disc=307008&ofer=1896&getperiodo=0701
	
	if (isset($_GET['id']) AND $_GET['acao'] === "0")
	{
		header("Location: movimentos/chamada/chamadas.php?$vars");		
	}

	////movimentos/lancanotas/lanca3.php?id=2545&getcurso=101&getdisciplina=101015:1317&getperiodo=0602

    if (isset($_GET['id']) AND $_GET['acao'] === "1")
    {
        header("Location: movimentos/lancanotas/lancanotas1.php?$vars");
    }


   
	if (isset($_GET['id']) AND $_GET['acao'] === "2")
    {
        header("Location: relat/caderno_chamada_ps.php?$vars");
    }				

	//movimentos/chamadas.php?id=2516&getperiodo=0602&getdisciplina=504009%3A1178

    //movimentos/excluifalta/excluifalta_step_1.php?id=2545&sendperiodo=0602&senddisciplina=101015%3A1317

	if (isset($_GET['id']) AND $_GET['acao'] === "3")
	{
	  header("Location: movimentos/faltas/faltas.php?$vars");
	}//excluifaltas.php?$vars");

    // movimentos/altfaltas/alt1.php?id=2545&sendperiodo=0602&senddisciplina=101015%3A1317						
    
	if (isset($_GET['id']) AND $_GET['acao'] === "4")
	{
	  header("Location: movimentos/altfaltas/alt1.php?$vars");

	}

	//movimentos/papeletas.php?id=2545&us=wanderson&getperiodo=0602&getdisciplina=101015%3A1317%3A0

	if (isset($_GET['id']) AND $_GET['acao'] === "5")
	{
			      header("Location: consultas/papeleta.php?$vars");
    }


//	consultas/conteudoaula_2.php?id=2545&getperiodo=0602&getdisciplina=101015%3A1317
    if (isset($_GET['id']) AND $_GET['acao'] === "6")
    {
                  header("Location: consultas/conteudo_aula.php?$vars");
    }


    if (isset($_GET['id']) AND $_GET['acao'] === "7")
    {
                  header("Location: consultas/papeleta_completa.php?$vars");
    }

    if (isset($_GET['id']) AND $_GET['acao'] === "8")
    {
		header("Location: movimentos/resolve_pendencias.php?$vars");
    }
   
    if (isset($_GET['id']) AND $_GET['acao'] === "9")
    {
        header("Location: consultas/faltas_completo.php?$vars");
    }

	if (isset($_GET['id']) AND $_GET['acao'] === "10")
    {
		
		
        echo '<script language="javascript"> 
		
	      	function jsConcluido(id)
			{
   				if (! id == "") {
    				if (! confirm(\'Você deseja marcar/desmarcar como concluído o diário \' + id + \'?\' + \'\n Como concluído o diário poderá ser "Fechado" pela coordenação ficando\n bloqueado para alterações!\'))      
					{
                        javascript:window.history.back(1);                     
         				return false;
      				} 
					else {
         				self.location = "movimentos/marca_concluido.php?ofer=" + id;
         				return true;
      				}
   				}
   				else {
					javascript:window.history.back(1);
					return false;
				}
			}
					
			jsConcluido('.$diario['1'].');</script>';
		exit;

    }


	
}




if($_GET['periodo'] != "")
{
 
   $getperiodo = $_GET['periodo'];
   
   $_SESSION['periodo'] = $getperiodo;
   
   $sql3 = 'SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso,
                o.id as idof,
				o.fl_digitada,
                o.fl_concluida
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                f.ref_professor = \''.$_SESSION['id'].'\' AND
                o.id = f.ref_disciplina_ofer AND
                o.ref_periodo = \''.$_SESSION['periodo'].'\' AND
                o.is_cancelada = 0 AND
                d.id = o.ref_disciplina;';
 
			   //echo $sql3; die;	
/*
echo $sql3;
exit;
*/
   
//   $con = diario_open_db();	 $dbconnect
   $query3 = consulta_sql($sql3);
}

?>

<html>
<head>
<title>CEFET-BAMBU&Iacute;</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!--<link rel="stylesheet" href="css/forms.css" type="text/css">
<link rel="stylesheet" href="css/gerals.css" type="text/css">-->

<style type="text/css" title="Standard" media="screen">
		@import "css/forms.css";
		@import "css/gerals.css";
</style>
  
<script language="javascript" type="text/javascript">

function setOpcao() {

    document.getElementById("acao").options[0].selected;
}

function enviar(id) {

	var undefined;
    var lst = -1;
    var i;
    var lista = document.change_acao.diario;

    if(lista.length == undefined) {

        if(lista.checked)
            lst = 0;
    }
    else {

         for (i = 0 ; i < lista.length; i++) {
            if( lista[i].checked ) {
                lst = i;
                i = -1;
                break;
            }
        }

    }


	if (lst == -1) {

            alert("ERRO! Primeiro selecione um diário!");
            return false;
    }
    else {

        if(lst == 0 && i != -1) {
            diarios = lista.value;
        }
        else {
            diarios = lista[lst].value;
        }

        diario  = diarios.split("|");

        var disc = diario[0];
        var ofer = diario[1];
		var encerrado = diario[2];

		if (encerrado == 1 && (id == 0 || id ==1)) {

			alert("ERRO! Este diário está finalizado e não pode ser alterado!");
			return false;

		}

    }
	   
 
	var vars = document.getElementById('vars').value + "&disc=" + disc + "&ofer=" + ofer;

//	alert(vars); return false;
	
	if(id == 0)
	    self.location = "movimentos/chamada/chamadas.php?" + vars;
	
	if(id == 1)
		self.location = "movimentos/lancanotas/lancanotas1.php?" + vars;
		
	if(id == 5)
		self.location = "consultas/papeleta.php?" + vars;
		
	if(id == 6)
		self.location = "consultas/conteudo_aula.php?" + vars;
}
		

</script>

</head>

<body bgcolor="#FFFFFF" text="#000000" >
<center>
<div align="left"><br>
<?php
    
   print('
  <table width="471" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
  <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="red">Per&iacute;odo: '.getNomePeriodo($getperiodo).'</font></strong></font></div></td>
  </tr>
</table>

  ');

echo '<p><h3>Marque o di&aacute;rio desejado e selecione a op&ccedil;&atilde;o:</h3></p>';

echo '<form id="change_acao" name="change_acao" method="get" action="diarios.php">';

echo '<input type="hidden" name="id" id="id" value="' . $_SESSION['id'] . '" />';

echo '<input type="hidden" name="vars" id="vars" value="' . $vars_b . '" />';
											

?>   



<table width="80%" cellspacing="0" cellpadding="0" class="papeleta">
    <tr bgcolor="#cccccc">
	    <td width="3%"> &nbsp;</td>
        <td width="15%" align="center"><b>C&oacute;d. Di&aacute;rio</b></td>
        <td width="70%" align="center"><b>Descri&ccedil;&atilde;o</b></td>
		<td width="12%" align="center"><b>Situa&ccedil;&atilde;o</b></td>
    </tr>
<?php

	
	
$i = 0;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';
 
// $curso = $_GET["getcurso"];
while($row3 = pg_fetch_array($query3))
{
	$nc = $row3["descricao_extenso"];
    $idnc = $row3["id"];
    $idof = $row3["idof"];
	$fl_digitada = $row3['fl_digitada'];
	$fl_concluida = $row3['fl_concluida'];

    $fl_encerrado = 0;
	
	if($fl_digitada == 'f' && $fl_concluida == 'f') {  
		$fl_situacao = '<font color="green"><b>Aberto</b></font>';  
	} 
	else { 
		if($fl_concluida == 't') {
        	$fl_situacao = '<font color="blue"><b>Conclu&iacute;do</b></font>';
    	}

		if($fl_digitada == 't') {
            $fl_situacao = '<font color="red"><b>Finalizado</b></font>';
            $fl_encerrado = 1;
        }        
	}


//    print "<option value=$idnc:$idof>($idof) - $nc</option>";
//	echo '<a href="diarios.php?periodo='.$codiperiodo.'" target="principal"><font color="#FFFFCC" size="1">'.($idof) - $nc.'</a><br />';
      // print "<input type=\"hidden\" name=\"getofer\" value=$idof />";
    if ( ($i % 2) == 0)
   	{
      $rcolor = $r1;
    }
   	else
   	{
      $rcolor = $r2;
   	}

   	echo '<tr bgcolor="'.$rcolor.'">';
    echo '<td width="3%" align="center"><input  type="radio" name="diario" id="diario" value="'.$idnc.'|'.$idof.'|'.$fl_encerrado.'" /></td>';

   	echo ' <td width="16%" align="center">'.$idof.'</td> <td>'.$nc.'</td> ';		

	echo ' <td width="6%" align="center">'.$fl_situacao.'</td> ';
	echo '</tr> ';

   	$i++;
}

echo '</table> <br />';

   echo '<p>';

   echo '&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<input type="button" id="notas" name="notas" value="Notas" onclick="enviar(1);"/> &nbsp; &nbsp; &nbsp;';

    echo '<input type="button" id="chamada" name="chamada" value="Chamada" onclick="enviar(0);"/> &nbsp; &nbsp; &nbsp;';

    echo '<input type="button" id="papeleta" name="papeleta" value="Papeleta" onclick="enviar(5);" /> &nbsp; &nbsp; &nbsp;';

    echo '<input type="button" id="conteudo" name="conteudo" value="Conte&uacute;do de Aula" onclick="enviar(6);" /> &nbsp; &nbsp;  &nbsp; &nbsp;';
 
    echo '<select name="acao" id="acao" class="select" onchange="document.change_acao.submit();">
	   	<option>---  outras   op&ccedil;&otilde;es     ---</option>';
	echo '<option value="10">Marcar/Desmarcar como Conclu&iacute;do</option>';
	echo '<option value="7">Papeleta Completa</option>';
	echo '<option value="9">Relat&oacute;rio de Faltas Completo</option>';
	echo '<option value="3">Alterar Faltas nas Chamadas</option>';
    echo '<option value="4">Excluir Chamada</option>';
	echo '<option value="2">Imprimir Caderno de Chamada</option>';
//	echo '<option value="1">Lan&ccedil;ar ou Alterar Notas</option>';
//	echo '<option value="5">Papeleta</option>';
//	echo '<option value="6">Relat&oacute;rio com Conte&uacute;do de Aulas</option>';
	echo '<option value="8">Resolver pend&ecirc;ncias e problemas</option>';
/*	echo '<option value="'.$codiperiodo.'">Fazer Chamada</option>';
	echo '<option value="'.$codiperiodo.'">Fazer Chamada</option>';*/
    echo "</select>";
	echo "</p></form>";


?>
</form>
</body>
</head>
</html>
