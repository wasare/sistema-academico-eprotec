<?php
require_once("../../../lib/common.php");
require_once("../../../configs/configuracao.php");
require_once("../../../core/reports/header.php");
require_once("../../../core/reports/carimbo.php");
require_once("../../../core/date.php");

$conn = new connection_factory($param_conn);

//Assinatura
$carimbo = new carimbo($param_conn);
//Cabecalho
$header = new header($param_conn);

$curso_id = $_POST['codigo_curso'];
$data_inicio = date::convert_date($_POST['data_inicio']);
$data_fim = date::convert_date($_POST['data_fim']);


$sql = "
SELECT 
  to_char(c.dt_formatura,'DD/MM/YYYY'), 
  p.nome, 
  p.cod_cpf_cgc, 
  to_char(p.dt_nascimento,'DD/MM/YYYY'),
  p.sexo, 
  p.fone_particular, 
  p.fone_celular, 
  p.email, 
  s.descricao,
   
  p.rua || 
  CASE WHEN 
    p.complemento IS NULL THEN ' ' 
    ELSE ', ' || p.complemento 
  END,
  p.bairro,
  a.nome || ' - ' || a.ref_estado,
  P.cep
FROM 
  contratos c, pessoas p, aux_cidades a, cursos s

WHERE "; 

if(!empty($curso_id)){
	$sql .= " c.ref_curso = $curso_id AND ";
}

$sql .= " 
  c.dt_formatura is not null AND 
  c.dt_formatura > '$data_inicio' AND 
  c.dt_formatura < '$data_fim' AND 
  
  p.id = c.ref_pessoa AND
  s.id = c.ref_curso AND
  a.id = p.ref_cidade

ORDER BY s.descricao,p.nome;";


$RsEgressos = $conn->Execute($sql);

$total = $RsEgressos->RecordCount();

if($total < 1){
    echo "<script>alert('Nenhum registro foi retornado!'); window.close();</script>";
}

?>

<html>
<head>
<title>SA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
	<div style="width: 760px;">
       
    	<div align="center" style="text-align:center; font-size:12px;">
        	<?php echo $header->get_empresa($PATH_IMAGES); ?>
            <br /><br />
        </div>    
        <div align="center">
            <h2>RELAT&Oacute;RIO DE EGRESSOS</h2>
            <p>
		<strong>Total de egressos: </strong> <?php echo $total; ?> 
		<strong>Intervalo de data:</strong> 
			de <?php echo $_POST['data_inicio'];?> 
			&agrave; <?php echo $_POST['data_fim'];?>
	    </p>
	    <table class="tabela_relatorio" cellspacing="0" border="1" cellpadding="0">
		<tr>
		    <td><strong>Curso</strong></td>
		    <td><strong>Nome completo</strong></td>
		    <td><strong>Data cola&ccedil;&atilde;o</strong></td>
    		<td><strong>CPF</strong></td>
		    <td><strong>Data nascimento</strong></td>
		    <td><strong>Sexo</strong></td>
		    <td><strong>Telefone fixo</strong></td>
		    <td><strong>Telefone celular</strong></td>
		    <td><strong>E-mail</strong></td>
		    <td><strong>Endere&ccedil;o</strong></td>
		    <td><strong>Bairro</strong></td>
		    <td><strong>Cidade / UF</strong></td>
            <td><strong>CEP</strong></td>
		</tr>
		<?php 
		    while(!$RsEgressos->EOF){
		        echo '<tr>';
		        echo '<td>'.$RsEgressos->fields[8].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[1].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[0].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[2].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[3].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[4].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[5].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[6].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[7].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[9].'&nbsp;</td>';
		        echo '<td>'.$RsEgressos->fields[10].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[11].'&nbsp;</td>';
	            echo '<td>'.$RsEgressos->fields[12].'&nbsp;</td>';
				echo '</tr>';
				
				$RsEgressos->MoveNext();
		    }
		?>
	    </table>
            <p>&nbsp;</p>
            <div class="carimbo_box">
            	_______________________________<br>
				<span class="carimbo_nome">
		    		<?php echo $carimbo->get_nome($_POST['carimbo']);?>
				</span><br />
				<span class="carimbo_funcao">
		    		<?php echo $carimbo->get_funcao($_POST['carimbo']);?>
				</span>
	    	</div>
	    <br>
	</div>
    </div>
</body>
</html>
