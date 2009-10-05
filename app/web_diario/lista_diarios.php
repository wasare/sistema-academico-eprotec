<?php

require_once('../../app/setup.php');

if(empty($_SESSION['web_diario_periodo_id']))
{
        echo '<script language="javascript">
                window.alert("ERRO! Primeiro informe um período!");
                window.close();
        </script>';
        exit;
}

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

$conn = new connection_factory($param_conn);

//ini_set("display_errors",1);

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


$qryPeriodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $_SESSION['web_diario_periodo_id'].'\';';
$periodo = $conn->adodb->getRow($qryPeriodo);
if($periodo === FALSE)
{
    die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}


$sql3 = 'SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso,
                o.id as idof,
				o.fl_digitada,
                o.fl_concluida
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                f.ref_professor = ( SELECT ref_pessoa FROM usuario WHERE nome = \''. $uid .'\') AND
                o.id = f.ref_disciplina_ofer AND
                o.ref_periodo = \''.$_SESSION['web_diario_periodo_id'].'\' AND
                o.is_cancelada = \'0\' AND
                d.id = o.ref_disciplina;';  

	$diarios = $conn->adodb->getAll($sql3);

   if(count($diarios) == 0)
   {
        echo '<script language="javascript">
                window.alert("Nenhum diário encontrado para o filtro selecionado!");
        </script>';
        exit;
   }

?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>

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
<div align="left">
<br />
  
<strong>
			<font size="4" face="Verdana, Arial, Helvetica, sans-serif">
				Per&iacute;odo: 
				<font color="red" size="4" face="Verdana, Arial, Helvetica, sans-serif"><?=$periodo['descricao']?></font>
			</font>
</strong>
&nbsp;&nbsp;
<span><a href="#" title="alterar o per&iacute;odo" alt="alterar o per&iacute;odo">alterar</a></span>
<br /> <br /> <br />

<h3>Marque o di&aacute;rio desejado e selecione uma op&ccedil;&atilde;o:</h3>
<br />
<form id="change_acao" name="change_acao" method="get" action="lista_diarios.php">
<input type="hidden" name="id" id="id" value="<?=$_SESSION['id']?>" />
<input type="hidden" name="vars" id="vars" value="<?=$vars_b?>" />

<table cellspacing="0" cellpadding="0" class="papeleta">
    <tr bgcolor="#cccccc">
	    <td> &nbsp; &nbsp; </td>
        <td align="center"><b>Di&aacute;rio</b></td>
        <td align="center"><b>Descri&ccedil;&atilde;o</b></td>
		<td align="center"><b>Situa&ccedil;&atilde;o</b></td>
    </tr>
<?php

	
	
$i = 0;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';
 
// $curso = $_GET["getcurso"];
foreach($diarios as $row3)
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

?>
</table>

<br /><br />
<p>

   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<input type="button" id="notas" name="notas" value="Notas" onclick="enviar(1);"/> &nbsp; &nbsp; &nbsp;

<input type="button" id="chamada" name="chamada" value="Chamada" onclick="enviar(0);"/> &nbsp; &nbsp; &nbsp;
<input type="button" id="papeleta" name="papeleta" value="Papeleta" onclick="enviar(5);" /> &nbsp; &nbsp; &nbsp;
<input type="button" id="conteudo" name="conteudo" value="Conte&uacute;do de Aula" onclick="enviar(6);" /> &nbsp; &nbsp;  &nbsp; &nbsp;
<select name="acao" id="acao" class="select" onchange="document.change_acao.submit();">
  	<option>---  outras   op&ccedil;&otilde;es     ---</option>
	<option value="10">Marcar/Desmarcar como Conclu&iacute;do</option>
	<option value="7">Papeleta Completa</option>
	<option value="9">Relat&oacute;rio de Faltas Completo</option>
	<option value="3">Alterar Faltas nas Chamadas</option>
    <option value="4">Excluir Chamada</option>
	<option value="2">Imprimir Caderno de Chamada</option>
<!--<option value="1">Lan&ccedil;ar ou Alterar Notas</option>
<option value="5">Papeleta</option>
<option value="6">Relat&oacute;rio com Conte&uacute;do de Aulas</option>-->
</select>
	</p></form>
<br /><br />
<br /><br />
</form>
</body>
</head>
</html>
