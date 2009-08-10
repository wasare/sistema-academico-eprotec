<?php

  //ARQUIVO DE CONFIGURACAO E CLASSE ADODB
  header ("Cache-Control: no-cache");
  require("../../lib/common.php");
  require("../../configs/configuracao.php");
  require("../../lib/adodb/adodb.inc.php");
  require("../../lib/adodb/tohtml.inc.php");
  
  //Criando a classe de conexão ADODB
  $Conexao = NewADOConnection("postgres");

  //Setando como conexão persistente
  $Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

  
  if ( is_numeric($_POST['cidade']) ) {

	  $cidade = ' t.ref_campus = '. $_POST['cidade'] .' AND';
          //EXECUTANDO SQL COM ADODB
	  $RsCidade = $Conexao->Execute("SELECT cidade_campus FROM campus WHERE id = " . $_POST['cidade'] . ";");

	 // Se RsCidade falhar
	 if (!$RsCidade){
        	print $Conexao->ErrorMsg();
    		die();	
	 }
         $txt_cidade = "&nbsp;&nbsp;-&nbsp;&nbsp;<strong>Cidade: </strong>" . $RsCidade->fields[0];

  }
  else
	  $cidade = '';


  $sql = "SELECT DISTINCT 
	p.id as \"Código\", 
  	p.nome as \"Nome\" , 
	f.pai_nome as \"Pai\", 
	f.mae_nome as \"Mae\" , 
	p.rua || ' ' || p.complemento as \"Endereço\", 
	p.bairro as \"Bairro\", 
	m.nome || '-' || m.ref_estado as \"Cidade\", 
	p.cep as \"CEP\", 
	
	p.fone_particular as \"Tel. Part.\",
	p.fone_profissional as \"Tel. Prof.\",
	p.fone_celular as \"Tel. Cel.\",
	p.fone_recado as \"Tel. Rec.\", 
	
	p.rg_numero as \"RG\", 	
	p.cod_cpf_cgc as \"CPF\", 
	p.sexo as \"Sexo\", 
	to_char(p.dt_nascimento, 
	'DD/MM/YYYY') as \"Data de Nascimento\" 
	
	FROM 
	public.pessoas p, public.matricula c , public.contratos t , public.aux_cidades m , public.filiacao f 
	
	WHERE 
	c.ref_periodo = '" . $_POST["periodo"] . "' AND 
	p.ref_filiacao = f.id AND 
	p.ref_cidade = m.id AND 
	c.ref_pessoa = p.id AND 
	t.ref_curso = c.ref_curso AND $cidade
	t.ref_pessoa = p.id 
	ORDER BY 2";

  $sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii("Nome"));';	
  //echo $sql;
  //die;
  
  
  
  $Result1 = $Conexao->Execute($sql);
  
  
  //numero de ocorrencias
  $num_result = $Result1->RecordCount();
  
  
  //Informacoes de cabecalho
  $info = "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Período: </strong> <span>".$_POST['periodo']."</span> $txt_cidade <br><br>";
  
?>
<html>
<head>
<title>Lista de Alunos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" name="FrmImprimir" id="FrmImprimir" action="">
  <h2>RELAT&Oacute;RIO COM TODOS OS ALUNOS MATRICULADOS POR PER&Iacute;ODO</h2>
  <p>
    <?php 
  
  echo $info;
  
  //rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
  
  echo '<TABLE COLS=16 width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"><tr>
			<TH>Código</TH>
			<TH>Nome</TH>
			<TH>Pai</TH>
			<TH>Mae</TH>
			<TH>Endereço</TH>
			<TH>Bairro</TH>
			<TH>Cidade</TH>
			<TH>CEP</TH>
			<TH>Tel. Part.</TH>
			<TH>Tel. Prof.</TH>
			<TH>Tel. Cel.</TH>
			<TH>Tel. Rec.</TH>
			<TH>RG</TH>
			<TH>CPF</TH>
			<TH>Sexo</TH>
			<TH>Data de Nascimento</TH>
		</tr>';

  
  /*
  echo '<table width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0">
  <tr>
    <th>C&oacute;d.</th>
    <th>Nome </th>
    <th>Turma</th>
    <th>Pai</th>
    <th>M&atilde;e</th>
    <th>Endere&ccedil;o</th>
    <th>Bairro</th>
    <th>Cidade</th>
    <th>CEP</th>
    <th>Tel. Part.</th>
    <th>Tel. Prof.</th>
    <th>Tel. Cel.</th>
    <th>Tel. Rec.</th>
    <th>RG</th>
    <th>CPF</th>
    <th>Sexo</th>
    <th>Dt. Nascimento</th>
  </tr>';
  */
  while(!$Result1->EOF){
  
  	  echo "
	  <TR valign=top>
		<TD align=right>" . $Result1->fields[0] . "</TD>
		<TD>&nbsp;" . $Result1->fields[1] . "</TD>
		<TD>&nbsp;" . $Result1->fields[2] . "</TD>
		<TD>&nbsp;" . $Result1->fields[3] . "</TD>
		<TD>&nbsp;" . $Result1->fields[4] . "</TD>
		<TD>&nbsp;" . $Result1->fields[5] . "</TD>
		<TD>&nbsp;" . $Result1->fields[6] . "</TD>
		<TD>&nbsp;" . $Result1->fields[7] . "</TD>
		<TD>&nbsp;" . $Result1->fields[8] . "</TD>
		<TD>&nbsp;" . $Result1->fields[9] . "</TD>
		<TD>&nbsp;" . $Result1->fields[10] . "</TD>
		<TD>&nbsp;" . $Result1->fields[11] . "</TD>
		<TD>&nbsp;" . $Result1->fields[12] . "</TD>
		<TD>&nbsp;" . $Result1->fields[13] . "</TD>
		<TD>&nbsp;" . $Result1->fields[14] . "</TD>
		<TD>&nbsp;" . $Result1->fields[15] . "</TD>
	  </TR>";
  
  	$Result1->MoveNext();
  
  }
  
  echo "</table>";


?>
  </p>
</form>
</body>
</html>
