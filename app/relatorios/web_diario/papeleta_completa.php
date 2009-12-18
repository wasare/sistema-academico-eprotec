<?php

require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'core/number.php');
require_once($BASE_DIR .'app/matricula/atualiza_diario_matricula.php');

$conn = new connection_factory($param_conn);

$diario_id = $_GET['diario_id'];

/*
TODO: verifica o direito de acesso do usuário ao diárioi, no caso de professor ou coordenador informado
*/


if(!is_numeric($diario_id))
{
    echo '<script language="javascript">
                window.alert("ERRO! Diario invalido!");
                window.close();
    </script>';
    exit;
}

//  INICIALIZA O DIARIO CASO NECESSARIO
if(!is_inicializado($diario_id)) 
{
    if(!ini_diario($diario_id))
    {
        echo '<script type="text/javascript">  window.alert("Falha ao inicializar o diário!!!!!!!"); </script>';
        envia_erro('Falha ao inicializar o diário '. $diario_id .'!!!!!!!');
        exit; 
    }
}
//^  INICIALIZA O DIARIO CASO NECESSARIO ^ //

// ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO
// SERÁ NECESSARIO PRINCIPALMENTE EM CASOS DE DISPENSA, ONDE O DIARIO É INICIALIZADO SOMENTE PARA O ALUNO DISPENSADO
$qryNotas = 'SELECT
        m.ref_pessoa, id_ref_pessoas
        FROM
            matricula m
        LEFT JOIN (
                SELECT DISTINCT
                d.id_ref_pessoas
            FROM
                diario_notas d
            WHERE
                d.d_ref_disciplina_ofer = ' . $diario_id . '
              ) tmp
        ON ( m.ref_pessoa = id_ref_pessoas )
        WHERE
            m.ref_disciplina_ofer = ' . $diario_id . ' AND
            id_ref_pessoas IS NULL AND
			(m.dt_cancelamento is null) AND
			(m.ref_motivo_matricula = 0)

        ORDER BY
                id_ref_pessoas;';

$qry = $conn->adodb->getAll($qryNotas);

foreach($qry as $registro)
{
    $ref_pessoa = $registro['ref_pessoa'];
    atualiza_matricula("$ref_pessoa","$diario_id");
}

// ^ ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO ^//

$sql3 = 'SELECT 
			b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, c.ref_diario_avaliacao, c.nota, a.num_faltas 
		FROM 
			matricula a, pessoas b, diario_notas c 
		WHERE	 
			(a.dt_cancelamento is null) AND 
			a.ref_disciplina_ofer = '. $diario_id .' AND 
			a.ref_pessoa = b.id AND 
			b.ra_cnec = c.ra_cnec AND 
			c.d_ref_disciplina_ofer = a.ref_disciplina_ofer AND 
			a.ref_motivo_matricula = 0 
		ORDER BY 
			lower(to_ascii(nome)), ref_diario_avaliacao;';


$matriculas = $conn->adodb->getAll($sql3);

if($matriculas === FALSE)
{
    envia_erro($sql3);
    exit;
}
else {
    if(count($matriculas) == 0) {
        echo '<script language="javascript">window.alert("Este diário ainda não foi iniciado pelo professor!"); javascript:window.close(); </script>';
      exit;
    }

}

$sql5 = " SELECT fl_digitada, fl_concluida
            FROM
                disciplinas_ofer
            WHERE
               id = $diario_id;";

$qry5 = $conn->adodb->getRow($sql5);

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
        $msg_dispensa .= '<font size="-1" color="brown"><strong>*</strong> ' . $dispensas . ' aluno dispensado neste di&aacute;rio. </font>';
    else
        $msg_dispensa .= '<font size="-1" color="brown"><strong>*</strong> ' . $dispensas . ' alunos dispensados neste di&aacute;rio. </font>';
}

?>

<html>
<head>
<title><?=$IEnome?> - papeleta completa</title>

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

echo 'Situação: ' . $fl_situacao;

if( $fl_digitada == 'f') {

	echo '<br /><font color="red" size="-2"><strong>SEM VALOR COMO DOCUMENTO, PASSÍVEL DE ALTERAÇÕES</strong></font>';

}

?>
</font>
<table cellspacing="0" cellpadding="0" class="papeleta">
	<tr bgcolor="#cccccc">
		<td><b>N&ordm;</b></td>
		<td><b>Matr&iacute;cula</b></td>
		<td><b>Nome</b></td>
		<td align="center"><b>N1</b></td>
		<td align="center"><b>N2</b></td>
		<td align="center"><b>N3</b></td>
		<td align="center"><b>N4</b></td>
		<td align="center"><b>N5</b></td>
		<td align="center"><b>N6</b></td>
		<td align="center"><b>N. Extra</b></td>
		<td align="center"><b>Total</b></td>
		<td align="center"><b>Faltas</b></td>
	</tr>
<?php


$sql_carga_horaria = "SELECT get_carga_horaria_realizada($diario_id), get_carga_horaria(get_disciplina_de_disciplina_of($diario_id));";

$carga_horaria = $conn->adodb->getRow($sql_carga_horaria);

$ch_prevista = $carga_horaria['get_carga_horaria'];
$ch_realizada = $carga_horaria['get_carga_horaria_realizada'];

$FaltaMax = $ch_realizada * 0.25;


$i = 0;
$No = 1;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';

foreach($matriculas as $row3)
{
    if ($row3['ref_diario_avaliacao'] == 1)
	{
		$nome_f = $row3["nome"];
		$racnec = $row3["ra_cnec"];
		$racnec = str_pad($racnec, 5, "0", STR_PAD_LEFT) ;
		$num = $row3["ordem_chamada"];
   
		if ($row3["num_faltas"] > 0)
			$falta = $row3["num_faltas"];
		else
			$falta = '0';
		
		if($falta > $FaltaMax) $falta = "<font color=\"red\"><b>$falta</b></font>";
		
		if($row3['nota_final'] != 0)
		{    
			$nota = number_format($row3['nota_final'],'1',',','.');
		}
		else 
		{ 
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
   	
		print  ("<tr bgcolor=\"$rcolor\">\n");
		print ("<td align=\"center\">".$No++."</td>\n ");
		print (" <td align=\"center\">$racnec</td>\n <td>$nome_f</td>\n "); 

		$total_nota_webdiario = 0;
	}
		
	$N = $row3['nota'];
    
    if($N < 0)
    {
      $N = '-';
    }
   
    if($N > 0) 
	{ 
		$N = number_format($N,'1',',','.');
    }
    //somatorio nota web diario
    $total_nota_webdiario += $N;

    print ("<td align=\"center\">$N</td>\n ");	
		
	if ($row3['ref_diario_avaliacao'] == 7) 
	{
		print ("<td align=\"center\">$nota</td>\n ");
   		print ("<td align=\"center\">$falta</td>\n ");
   
   		print ("</tr>\n ");
	}
   
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
	
    echo '<br /><br />';

    $i = 0;

	$sql_notas_distribuidas = 'SELECT nota_distribuida FROM diario_formulas WHERE grupo ilike \'%-'. $diario_id .'\' order by prova;';
	$notas_distribuidas = $conn->get_all($sql_notas_distribuidas);

?>
	<h4>Notas distribu&iacute;das</h4>
<table cellspacing="0" cellpadding="0" class="papeleta">
    <tr bgcolor="#cccccc">
        <td align="center"><b>N1</b></td>
        <td align="center"><b>N2</b></td>
        <td align="center"><b>N3</b></td>
        <td align="center"><b>N4</b></td>
        <td align="center"><b>N5</b></td>
        <td align="center"><b>N6</b></td>
		<td align="center"><b>Total</b></td>
    </tr>

    <tr bgcolor="#ffffff">
        <?php
			$total_distribuido = 0;
            foreach($notas_distribuidas as $nota)
			{
				$nota_d = number::numeric2decimal_br($nota['nota_distribuida'],'1');
				if($nota_d == 0 || empty($nota_d))
					$nota_d = '-';
				echo '<td align="center">'. $nota_d .'</td>';
				$total_distribuido += $nota['nota_distribuida'];
			}
			echo '<td align="center">'. number::numeric2decimal_br($total_distribuido,1) .'</td>';
        ?>
    </tr>
</table>
<font size="-1" color="brown"><strong>*</strong> as notas acima s&atilde;o informadas pelo professor.</font>
<br />

<?php

    if (!empty($msg_dispensa)) {
	
		$sql_dispensas = "SELECT 
         b.nome, b.id AS ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas, a.ref_motivo_matricula 
         FROM matricula a, pessoas b
         WHERE 
            
            (a.dt_cancelamento is null) AND         
            a.ref_disciplina_ofer = $diario_id AND
            a.ref_pessoa = b.id AND 
            a.ref_motivo_matricula IN (2,3,4)
         	ORDER BY lower(to_ascii(nome));" ;
	   
		$qry_dispensas = $conn->adodb->getAll($sql_dispensas);

?>

		<h4> Alunos dispensados </h4>
		<table cellspacing="0" cellpadding="0" class="papeleta">
        <tr bgcolor="#cccccc">
			<td align="center"><b>Matr&iacute;cula</b></td>
			<td><b>Nome</b></td>
			<td align="center"><b>Nota</b></td>
			<td align="center"><b>Motivo</b></td>
		</tr>

<?php
	

	foreach($qry_dispensas as $row3)
	{
		$nome_f = $row3['nome'];
		$racnec = $row3['ra_cnec'];
		$racnec = str_pad($racnec, 5, "0", STR_PAD_LEFT) ;
		$num = $row3['ordem_chamada'];
        $motivo_matricula = $row3['ref_motivo_matricula'];

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

        // APROVEITAMENTO DE ESTUDOS 2
		// CERTIFICACAO DE EXPERIENCIAS 3
		// EDUCACAO FISICA 4
        switch ($motivo_matricula) {
    		case 2:
        		$motivo_matricula = 'Aproveitamento de estudos';
        		break;
    		case 3:
        		$motivo_matricula = 'Certifica&ccedil;&atilde;o de experi&ecirc;ncia';
        		break;
    		case 4:
        		$motivo_matricula = 'Educa&ccedil;&atilde;o f&iacute;sica';
        		break;
		}

		//<td width=\"10%\">$num</td>\n
		if ( ($i % 2) == 0) { $rcolor = $r1; } else { $rcolor = $r2; }

		print("<tr bgcolor=\"$rcolor\">\n");
		print("<td align=\"center\">$racnec</td>\n <td>$nome_f</td>\n ");
		print ("<td align=\"center\">$nota</td>\n ");
		print ("<td align=\"center\">$motivo_matricula</td>\n ");
		print("</tr>\n ");

		$i++;
	}
} // end if - somente exibe se houver dispensa

?>


</table>
<br /><br />
<div class="nao_imprime">
<input type="button" value="Imprimir" onClick="window.print()">
&nbsp;&nbsp;
<a href="#" onclick="javascript:window.close();">Fechar</a>
</div>
<br /><br />
</body>
</html>
