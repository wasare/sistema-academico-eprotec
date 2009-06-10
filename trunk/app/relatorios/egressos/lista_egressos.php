<?php

require("../../../lib/common.php");
require("../../../configuracao.php");
require("../../../lib/adodb/adodb.inc.php");
require("../header.php");


$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


$periodo = $_POST['periodo1'];
$curso_id = $_POST['codigo_curso'];
$resp_nome = $_POST['resp_nome'];
$resp_cargo = $_POST['resp_cargo'];


$sql = "

SELECT 
  
  to_char(c.dt_formatura,'YYYY') AS \"ANO DE CONCLUSÃO\", 
  p.nome AS \"NOME COMPLETO\", 
  p.cod_cpf_cgc AS \"CPF\", 
  to_char(p.dt_nascimento,'DD/MM/YYYY') AS \"DATA NASCIMENTO\",
  p.sexo AS \"SEXO\", 
  p.fone_particular AS \"TELEFONE FIXO\", 
  p.fone_celular AS \"TELEFONE CELULAR\", 
  p.email AS \"E-MAIL\", 
  s.descricao AS \"CURSO\",
   
  p.rua || '  ' || 
  CASE WHEN 
    p.complemento IS NULL THEN ' ' 
    ELSE p.complemento 
  END 
  || ' - ' || p.bairro || ' - ' || a.nome || '-' || a.ref_estado
  AS \"ENDEREÇO COMPLETO (Rua/Bairro/Cidade/UF)\"

FROM 
  contratos c, pessoas p, aux_cidades a, cursos s

WHERE
  c.ref_curso = $curso_id AND
  c.dt_formatura is not null AND
  c.ref_last_periodo = '$periodo'AND
  p.id = c.ref_pessoa AND
  s.id = c.ref_curso AND
  a.id = p.ref_cidade

ORDER BY 1;";


$RsEgressos = $Conexao->Execute($sql);

if (!$RsEgressos){
    print $Conexao->ErrorMsg();
    die();
}


$ano_conclusao = $RsEgressos->fields[0];
$curso = $RsEgressos->fields[8];

?>

<html>
    <head>
        <title>SA</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../../Styles/style.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
        <div style="width: 760px;">
            
            <div align="center" style="text-align:center; font-size:12px;">
                <?php echo hd_empresa($Conexao, '../../../images/armasbra.jpg'); ?>
                <br /><br />
            </div>
            
            <div align="center">
            <h2>RELAT&Oacute;RIO DE EGRESSOS</h2>
            <p>
				<strong>Curso:</strong> <?php echo $curso; ?> 
				<strong>Ano de conclus&atilde;o:</strong> <?php echo $ano_conclusao;?>
			</p>
			<table width="90%" class="tabela_relatorio" cellspacing="0" border="1" cellpadding="0">
			<tr>
				<td><strong>NOME COMPLETO</strong></td>
				<td><strong>CPF</strong></td>
				<td><strong>DATA NASCIMENTO</strong></td>
				<td><strong>SEXO</strong></td>
				<td><strong>TELEFONE FIXO</strong></td>
				<td><strong>TELEFONE CELULAR</strong></td>
				<td><strong>E-MAIL</strong></td>
				<td><strong>ENDEREÇO COMPLETO (Rua/Bairro/Cidade/UF)</strong></td>
			</tr>
			<?php 

			while(!$RsEgressos->EOF){
			
				echo '<tr>';
				echo '<td>'.$RsEgressos->fields[1].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[2].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[3].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[4].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[5].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[6].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[7].'&nbsp;</td>';
				echo '<td>'.$RsEgressos->fields[9].'&nbsp;</td>';
				echo '</tr>';
	
				$RsEgressos->MoveNext();
			}
			
			?>
			</table>
            <p>&nbsp;</p>
               <?php 
               
               //Dados de rodape com assinatura
			   	$rodape  = '__________________________________________<br>';
			    $rodape .= '<span style="font-size: 12px;">';
			   	$rodape .= $resp_nome . "</span><br>";
				$rodape .= '<span style="font-size: 9px;"><strong>'; 
				$rodape .= $resp_cargo . "</strong></span><br>";
				
				echo $rodape;
               
               ?>

            <br><br>
			</div>
		</div>
    </body>
</html>
