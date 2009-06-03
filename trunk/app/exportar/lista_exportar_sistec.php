<?php

require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");

$periodo = $_POST['periodo1'];
$curso  = $_POST['codigo_curso'];

$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

$sql = "
SELECT DISTINCT
p.id as \"Código\",
p.nome as \"Nome\" ,
p.cod_cpf_cgc as \"CPF\"
FROM
pessoas p , matricula c
WHERE
c.ref_periodo = '$periodo' AND
c.ref_curso = '$curso' AND
c.ref_pessoa = p.id
ORDER BY 2;";

$Result1 = $Conexao->Execute($sql);

if (!$Result1){
    print $Conexao->ErrorMsg();
    die();
}

$resp_erro_cpf .= "
<table>
<tr>
    <td><strong>CPF</strong></td>
    <td><strong>C&oacute;digo</strong></td>
    <td><strong>Nome</strong></td>
</tr>";

while(!$Result1->EOF){
    
    if($Result1->fields[2] == "" || strlen($Result1->fields[2]) != 11){
        $resp_erro_cpf .= "<tr>";
        $resp_erro_cpf .= "<td>".$Result1->fields[2]."</td>";
        $resp_erro_cpf .= "<td>".$Result1->fields[0]."</td>";
        $resp_erro_cpf .= "<td>".$Result1->fields[1]."</td></tr>";
        $resp_erro_cpf .= "</tr>";
        
    }else{
        $resp_ok .= $Result1->fields[2].";";
    }

    /*
    if(strlen($Result1->fields[2]) == 10){
        echo $Result1->fields[0].", ";
    }
    */
    $Result1->MoveNext();
}
$resp_erro_cpf .= "</table>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SA</title>
<link href="../../Styles/formularios.css" rel="stylesheet"	type="text/css" />
</head>
<body>
<div align="center" style="height: 600px;">
<h1>Exportar alunos matriculados para o SISTEC</h1>
<div class="box_geral">
    Copie o texto do formul&aacute;rio abaixo e cole no respectivo campo do SISTEC.<br>
    Para facilitar dê um clique na caixa de texto do formul&aacute;rio e utilize os 
    atalhos de teclado "Ctrl + A" para selecionar o texto, "Ctrl + C" para copiar e 
    "Ctrl + V" para colar na caixa de texto do SISTEC.
    <textarea name="" cols="100" rows="5"><?=$resp_ok?></textarea>
    <h4>Alunos sem CPF cadastrado ou com n&uacute;mero de caracteres diferente de 11</h4>
    <?=$resp_erro_cpf?>
</div>
</div>
</body>
</html>