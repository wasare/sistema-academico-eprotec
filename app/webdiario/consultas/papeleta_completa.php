<?php
require_once('../webdiario.conf.php');


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

if(isset($_SESSION['select_prof']) && is_numeric($_SESSION['select_prof']) ) {

	    $id = $_SESSION['select_prof'];
}
		

$ref_prof = $id;

//  INICIALIZA O DIARIO CASO NECESSARIO
if(!is_inicializado($getperiodo,$getofer)) 
{
    if (!inicializaDiario($getdisciplina,$getofer,$getperiodo,$id))
    {
        // FIXME: informar ao administrador/desenvolvedor quando ocorrer erro
        echo '<script type="text/javascript">  window.alert("Falha ao inicializar o diário!!!!!!!"); </script>';
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
                d.d_ref_disciplina_ofer = ' . $getofer . '
              ) tmp
        ON ( m.ref_pessoa = id_ref_pessoas )
        WHERE
            m.ref_disciplina_ofer = ' . $getofer . ' AND
            id_ref_pessoas IS NULL AND
			(m.dt_cancelamento is null) AND
			(m.ref_motivo_matricula = 0)

        ORDER BY
                id_ref_pessoas;';

$qry = consulta_sql($qryNotas);

if(is_string($qry))
{
	echo $qry;
    exit;
}

//-- Conectando com o PostgreSQL
// FIXME: migrar para conexao ADODB
if(($conn = pg_Pconnect("host=$host user=$user password=$password dbname=$database")) == false)
{
   $error_msg = "Não foi possível estabeler conexão com o Banco: " . $dbname;
}

require_once('../../matricula/atualiza_diario_matricula.php');

while($registro = pg_fetch_array($qry))
{
    $ref_pessoa = $registro['ref_pessoa'];
    atualiza_matricula("$ref_pessoa","$getofer");
}

// ^ ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO ^//


$sql3 = "SELECT 
         b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas 
         FROM matricula a, pessoas b 
         WHERE		 	
			(a.dt_cancelamento is null) AND
            a.ref_periodo = '$getperiodo' AND 
            a.ref_disciplina_ofer = '$getofer' AND
            a.ref_pessoa = b.id AND 
            a.ref_motivo_matricula = 0
         ORDER BY lower(to_ascii(nome));" ;


$sql3 = 'SELECT 
			b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, c.ref_diario_avaliacao, c.nota, a.num_faltas 
		FROM 
			matricula a, pessoas b, diario_notas c 
		WHERE	 
			(a.dt_cancelamento is null) AND 
			a.ref_periodo = \''. $getperiodo .'\' AND 
			a.ref_disciplina_ofer = '. $getofer .' AND 
			a.ref_pessoa = b.id AND 
			b.ra_cnec = c.ra_cnec AND 
			c.d_ref_disciplina_ofer = a.ref_disciplina_ofer AND 
			a.ref_motivo_matricula = 0 
		ORDER BY 
			lower(to_ascii(nome)), ref_diario_avaliacao;';

//echo $sql3; die; // a.ref_contrato IN(SELECT id FROM contratos WHERE dt_desativacao is null) AND

//a.ref_disciplina = '$getdisciplina' AND

         
$sql4 = "SELECT 
         a.descricao_extenso, a.carga_horaria, b.nome 
         FROM disciplinas a, pessoas b
         WHERE a.id = '$getdisciplina' ";
         
if($ref_prof != 0)
{
       $sql4 .=  "AND b.id = $ref_prof;";
}

else
{
      $sql4 .=  ";";
}

         
$qry3 = consulta_sql($sql3);

if(is_string($qry3))
{
	echo $qry3;
	exit;
}

//echo $sql4;die;

$qry4 = consulta_sql($sql4);

if(is_string($qry4))
{
   echo $qry4;
   exit;
}


$sql5 = " SELECT fl_digitada, fl_concluida
            FROM
                disciplinas_ofer
            WHERE
               id = '$getofer';";


//echo $sql5; die;
$qry5 = consulta_sql($sql5);

if(is_string($qry5))
{
    echo $qry5;
    exit;
}
else {

	$flag = pg_fetch_array($qry5,0);
    $fl_digitada = $flag[0];
    $fl_concluida = $flag[1];
}

// APROVEITAMENTO DE ESTUDOS 2
// CERTIFICACAO DE EXPERIENCIAS 3
// EDUCACAO FISICA 4
$msg_dispensa = '';

$sql_dispensas = "SELECT COUNT(*) 
                    FROM 
                        matricula a, pessoas b
                    WHERE 
            
                    (a.dt_cancelamento is null) AND            
                    a.ref_disciplina_ofer = '$getofer' AND
                    a.ref_pessoa = b.id AND 
                    a.ref_motivo_matricula IN (2,3,4) ;" ;

$qry_dispensas = consulta_sql($sql_dispensas);

if(is_string($qry_dispensas))
{
    echo $qry_dispensas;
    exit;
}
else {

	$dispensas = pg_fetch_row($qry_dispensas);
    $dispensas = $dispensas[0];
    if ($dispensas > 0 )
        $msg_dispensa .= '<font size="-1" color="brown"><strong>*</strong> ' . $dispensas . ' aluno(s) dispensado(s) neste di&aacute;rio. </font>';
}



?>

<html>
<head>
<title>papeletas</title>
</head>

<link rel="stylesheet" href="../css/forms.css" type="text/css">
<font size="2">

<?php

while($row4 = pg_fetch_array($qry4)) 
{
	$dis = $row4['descricao_extenso'];
	$prof = $row4['nome'];
	$cargap = $row4['carga_horaria'];
	break;
	print("Disciplina: <b>$dis ($ofcod)</b><br>");
	print("Professor(a): <b>$prof</b><br><br>");
}


echo getHeaderDisc($getofer);

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


$FaltaMax = $cargap * 0.25;
//echo $FaltaMax;

?>
</font>
<table width="80%" cellspacing="0" cellpadding="0" class="papeleta">	
	<tr bgcolor="#cccccc">
		<td width="5%"><b>N&ordm;</b></td>
		<td width="10%"><b>Matr&iacute;cula</b></td>
		<td width="40%"><b>Nome</b></td>
		<td align="center"><b>N1</b></td>
		<td align="center"><b>N2</b></td>
		<td align="center"><b>N3</b></td>
		<td align="center"><b>N4</b></td>
		<td align="center"><b>N5</b></td>
		<td align="center"><b>N6</b></td>
		<?php
		   if(eregi("06", $getperiodo) || eregi("07", $getperiodo) || eregi("08", $getperiodo) || eregi("09", $getperiodo) ) { 
				echo '<td align="center"><b>N. Extra</b></td>';
			}
		?>
		<td align="center"><b>Total</b></td>
		<td align="center"><b>Faltas</b></td>
	</tr>
<?php


$sqlflag ="SELECT
                  SUM(CAST(flag AS INTEGER)) AS carga
               FROM
                  diario_seq_faltas
               WHERE
                  periodo = '$getperiodo' AND
                  ref_disciplina_ofer = $getofer; ";

//echo $sqlflag;die;

//disciplina = $getdisciplina AND

$qryflag = consulta_sql($sqlflag);
     
if(is_string($qryflag))
{
	echo $qryflag;
	exit;
}

$rowflag = pg_fetch_array($qryflag);

$result = $rowflag['carga'];

if( $result < 1 )
{
    $result = 0;
}

$i = 0;
$No = 1;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';

while($row3 = pg_fetch_array($qry3))
{
    if ($row3['ref_diario_avaliacao'] == 1)
	{
		$nome_f = $row3["nome"];
		$racnec = $row3["ra_cnec"];
		$racnec = str_pad($racnec, 5, "0", STR_PAD_LEFT) ;
		$num = $row3["ordem_chamada"];
   
		if ($row3["num_faltas"] > 0)
		{
			$falta = $row3["num_faltas"];
		}
		else
		{
			$falta = '0';
		}

		if($falta > $FaltaMax) 
		{
			$falta = "<font color=\"red\"><b>$falta</b></font>";
		}
	
		if($row3['nota_final'] != 0) 
		{    
			$nota = getNumeric2Real($row3["nota_final"]); 
		}
		else 
		{ 
			$nota = $row3['nota_final'];
		}

		if ($nota < 60) 
		{
			$nota = "<font color=\"red\"><b>$nota</b></font>";
		}
   
   	//<td width=\"10%\">$num</td>\n
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
		print (" <td width=\"10%\" align=\"center\">$racnec</td>\n <td width=\"40%\">$nome_f</td>\n "); 

		$total_nota_webdiario = 0;
	}
		
	$N = $row3['nota'];
    
    if($N < 0)
    {
      $N = '-';
    }
   
    if($N > 0) 
	{ 
       $N = getNumeric2Real($N); 
    }
    //somatorio nota web diario^M
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
	
	print("Aulas dadas: <b>$result</b>&nbsp;&nbsp;&nbsp;");
	print("Aulas previstas na estrututa curricular: <b>$cargap</b><br>");
	print("<center>ASSINATURA(S):</center>");

    echo '<br />';

    $i = 0;

    if (!empty($msg_dispensa)) {
	
		$sql_dispensas = "SELECT 
         b.nome, b.id AS ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas, a.ref_motivo_matricula 
         FROM matricula a, pessoas b
         WHERE 
            
            (a.dt_cancelamento is null) AND            
            a.ref_disciplina_ofer = '$getofer' AND
            a.ref_pessoa = b.id AND 
            a.ref_motivo_matricula IN (2,3,4)            
         	ORDER BY lower(to_ascii(nome));" ;	
	   
		$qry_dispensa = consulta_sql($sql_dispensas);

		if(is_string($qry_dispensa))
		{
    		echo $qry_dispensa;
    		exit;
		}
		
?>
		<h4> Alunos Dispensados </h4>
		<table width="80%" cellspacing="0" cellpadding="0" class="papeleta">
        <tr bgcolor="#cccccc">
			<td width="10%" align="center"><b>Matr&iacute;cula</b></td>
			<td width="60%" ><b>Nome</b></td>
			<td width="10%" align="center"><b>Nota</b></td>
			<td width="20%" align="center"><b>Motivo</b></td>
		</tr>

<?php
	while($row3=pg_fetch_array($qry_dispensa))
	{
		$nome_f = $row3['nome'];
		$racnec = $row3['ra_cnec'];
		$racnec = str_pad($racnec, 5, "0", STR_PAD_LEFT) ;
		$num = $row3['ordem_chamada'];
        $motivo_matricula = $row3['ref_motivo_matricula'];

		if($row3['nota_final'] != 0) {
			$nota = getNumeric2Real($row3['nota_final']);
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
		print("<td align=\"center\" width=\"10%\">$racnec</td>\n <td width=\"60%\">$nome_f</td>\n ");
		print ("<td width=\"10%\" align=\"center\">$nota</td>\n ");
		print ("<td width=\"20%\" align=\"center\">$motivo_matricula</td>\n ");
		print("</tr>\n ");

		$i++;
	}
} // end if - somente exibe se houver dispensa

?>


</table>
<br /><br />
<input type="button" value="Imprimir" onClick="window.print()">
</body>
</html>
