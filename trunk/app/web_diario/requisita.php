<?php

require_once(dirname(__FILE__) .'/../setup.php');
require_once($BASE_DIR .'core/web_diario.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['id'];
$operacao = $_GET['do'];

$_SESSION['web_diario_do'] = $operacao;

if(!is_numeric($diario_id) && !is_numeric($operacao))
{
	echo '<script language="javascript">
                window.alert("ERRO! Dados invalidos!");
                window.close();
    </script>';
    exit; 
}

/*
TODO: verifica o direito de acesso do usuário ao diário informado
*/
$_GET['diario_id'] = $diario_id;
$_POST['diario_id'] = $diario_id;


$menu_superior = '<div class="nao_imprime">';

if(isset($_SESSION['web_diario_periodo_id']))
	$menu_superior .= '<a href="#" onclick="window.opener.reload_parent_pane(\'pane_diarios\');window.close();">Meus di&aacute;rios</a>&nbsp;|&nbsp;';

if(isset($_SESSION['web_diario_periodo_coordena_id']))
	$menu_superior .= '<a href="#" onclick="window.opener.reload_parent_pane(\'pane_coordenacao\');window.close();">Coordena&ccedil;&atilde;o</a>&nbsp;|&nbsp;';

$menu_superior .= '<a href="#" onclick="window.opener.location.href=\''. $BASE_URL .'\';window.close();">Sair</a>&nbsp;&nbsp;&nbsp;&nbsp;';
$menu_superior .= '<img src="'. $BASE_URL .'public/images/icons/bola_verde.gif" width="10" height="10" />&nbsp;' . $sa_usuario .'&nbsp;&nbsp;';

$menu_superior .= '<br /><br /></div>';

echo $menu_superior;

// OPERACOES COM ALTERACAO DE DADOS
if($operacao == 'notas') {
  if(!is_inicializado($diario_id)) {
    if (ini_diario($diario_id)) {
      echo '<script type="text/javascript">window.alert("Diario iniciado com sucesso!"); </script>';
    }
    else {
        // TODO: informar ao administrador/desenvolvedor quando ocorrer erro
        echo '<script language=javascript> window.alert("Falha ao inicializar o diario!"); window.close(); </script>';
        exit;
    }
  }
  require_once($BASE_DIR .'app/web_diario/professor/notas/lanca_notas.php');
  exit;
}

if($operacao == 'chamada') {
    require_once($BASE_DIR .'app/web_diario/professor/chamada/chamadas.php');
    exit;
}

if($operacao == 'altera_chamada') {
    require_once($BASE_DIR .'app/web_diario/professor/chamada/faltas.php');
    exit;
}


if($operacao == 'exclui_chamada') {
    require_once($BASE_DIR .'app/web_diario/professor/chamada/exclui_chamada.php');
    exit;
}

if($operacao == 'marca_diario') {
	echo papeleta_header($diario_id);
    require_once($BASE_DIR .'app/web_diario/professor/marca_concluido.php');
	echo '<br />';
	echo '<script language="javascript" type="text/javascript"> 
			alert(\'Diario marcado / desmarcado com sucesso!\');
			window.opener.location.reload();
			setTimeout("self.close()",450); </script>';
    exit;
}

if($operacao == 'troca_senha') {
    require_once($BASE_DIR .'app/usuarios/alterar_senha.php');
    exit;
}

if($operacao == 'lista_diarios_coordenacao') {
	unset($_GET['diario_id']);
    unset($_POST['diario_id']);
	$_GET['curso_id'] = $diario_id;
	$_GET['periodo_id'] = $_SESSION['web_diario_periodo_coordena_id']; 
    require_once($BASE_DIR .'app/web_diario/secretaria/lista_diarios_secretaria.php');
    exit;
}


// ^ OPERACOES COM ALTERACAO DE DADOS   ^ //



// RELATORIOS
if($operacao == 'papeleta') {
    require_once($BASE_DIR .'app/relatorios/web_diario/papeleta.php');
    exit;
}

if($operacao == 'papeleta_completa') {
    require_once($BASE_DIR .'app/relatorios/web_diario/papeleta_completa.php');
    exit;
}

if($operacao == 'faltas_completo') {
    require_once($BASE_DIR .'app/relatorios/web_diario/faltas_completo.php');
    exit;
}

if($operacao == 'caderno_chamada') {
    require_once($BASE_DIR .'app/relatorios/web_diario/caderno_chamada.php');
    exit;
}

if($operacao == 'conteudo_aula') {
    require_once($BASE_DIR .'app/relatorios/web_diario/conteudo_aula.php');
    exit;
}
// ^ RELATORIOS ^ //

echo '<script type="text/javascript">window.focus.();</script>';

exit;
/*

*/
$sql3 = "SELECT 
         b.nome, b.id AS ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas 
         FROM matricula a, pessoas b
         WHERE 
            (a.dt_cancelamento is null) AND
            a.ref_disciplina_ofer = $diario_id AND
            a.ref_pessoa = b.id AND 
            a.ref_motivo_matricula = 0
            
         ORDER BY lower(to_ascii(nome));" ;


$qry3 = $conn->get_all($sql3);

$matriculas = count($qry3);

$sql5 = " SELECT fl_digitada, fl_concluida
            FROM
                disciplinas_ofer
            WHERE
               id = $diario_id;";
		   
$qry5 = $conn->get_row($sql5);

$fl_digitada = $qry5['fl_digitada'];
$fl_concluida = $qry5['fl_concluida'];

// APROVEITAMENTO DE ESTUDOS 2
// CERTIFICACAO DE EXPERIENCIAS 3
// EDUCACAO FISICA 4
$msg_dispensa = '';

$sql_dispensas = "SELECT COUNT(*) 
         			FROM 
						matricula a, pessoas b
         			WHERE 
            
            		(a.dt_cancelamento is null) AND            
            		a.ref_disciplina_ofer = $diario_id AND
            		a.ref_pessoa = b.id AND 
            		a.ref_motivo_matricula IN (2,3,4) ;" ;

$dispensas = $conn->adodb->getOne($sql_dispensas);

if ($dispensas > 0 ) {
	if($dispensas == 1)
        $msg_dispensa .= '<font size="-1" color="brown"><strong>*</strong> ' . $dispensas . ' aluno dispensado, consulte a papeleta completa para exib&iacute;-lo. </font>';
	else
		$msg_dispensa .= '<font size="-1" color="brown"><strong>*</strong> ' . $dispensas . ' alunos dispensados, consulte a papeleta completa para exib&iacute;-los. </font>';
}


?>

<html>
<head>
<title><?=$IEnome?> - papeleta</title>

<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<style media="print">
<!--
.nao_imprime {display:none}

table.papeleta {
    font: 0.7em verdana, arial, tahoma, sans-serif;
    border: 0.0015em solid;
    border-collapse: collapse;
    border-spacing: 0px;
}

.papeleta td {
    font: 0.7em verdana, arial, tahoma, sans-serif;
    border: 0.0015em solid;
    padding: 2px;
    border-collapse: collapse;
    border-spacing: 1px;
}
-->
</style>

</head>

<body>
<font size="2">

<?php


echo papeleta_header($diario_id);


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

echo 'Situa&ccedil;&atilde;o: ' . $fl_situacao;

if( $fl_digitada == 'f') {

    echo '<br /><font color="red" size="-2"><strong>SEM VALOR COMO DOCUMENTO, PASS&Iacute;VEL DE ALTERA&Ccedil;&Otilde;ES</strong></font>';

}

?>
</font>
<table cellspacing="0" cellpadding="0" class="papeleta">
	<tr bgcolor="#cccccc">
		<td  align="center"><b>N&ordm;</b></td>
		<td  align="center"><b>Matr&iacute;cula</b></td>
		<td><b>Nome</b></td>
		<td align="center"><b>Nota</b></td>
		<td align="center"><b>Falta</b></td>
	</tr>

<?php

    
$sql_carga_horaria = "SELECT get_carga_horaria_realizada($diario_id), get_carga_horaria(get_disciplina_de_disciplina_of($diario_id));"; 

$carga_horaria = $conn->adodb->getRow($sql_carga_horaria);

$ch_prevista = $carga_horaria['get_carga_horaria'];
$ch_realizada = $carga_horaria['get_carga_horaria_realizada'];

$FaltaMax = $ch_realizada * 0.25;

$i = 0;
$N = 1;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';


foreach($qry3 as $row3) 
{
   $nome_f = $row3['nome'];
   $racnec = $row3['ra_cnec'];
   $racnec = str_pad($racnec, 5, "0", STR_PAD_LEFT) ;
   $num = $row3['ordem_chamada'];
   
   if ($row3['num_faltas'] > 0){
      $falta = $row3['num_faltas'];
   }
   else{
      $falta = '0';
   }

   if($falta > $FaltaMax) $falta = "<font color=\"red\"><b>$falta</b></font>";
   
    if($row3['nota_final'] != 0) { 
		$nota = number_format($row3['nota_final'],'1',',','.');
	}
	else { 
		$nota = $row3['nota_final'];
	}
 
	 
   if ($nota < 60) 
   {
      $nota = "<font color=\"red\"><b>$nota</b></font>";
   }
   
   if ( ($i % 2) == 0)
   {
      $rcolor = $r1;
   }
   else
   {
      $rcolor = $r2;
   }
   print("<tr bgcolor=\"$rcolor\">\n"); 
   print(" <td align=\"center\" >". $N++ ."</td>\n <td align=\"center\" >$racnec</td>\n <td>$nome_f</td>\n "); 
   print ("<td align=\"center\">$nota</td>\n ");
   print ("<td align=\"center\">$falta</td>\n ");
   print("</tr>\n ");
   
   $i++;
}

?>


</table>

<?=$msg_dispensa?>

<hr width="60%" size="1" align="left" color="#FFFFFF">

<?php
	
print("Aulas dadas: <b>$ch_realizada</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
print("Aulas previstas: <b>$ch_prevista</b> <br />");
print("<br />ASSINATURA(S):");


?>
<br /><br />
<div class="nao_imprime">
<input type="button" value="Imprimir" onClick="window.print()">
&nbsp;&nbsp;&nbsp;
<input type="button" name="fechar" id="fechar" value="Fechar" onclick="javascript:window.close();" />
</div>
<br /><br />
</body>
</html>
