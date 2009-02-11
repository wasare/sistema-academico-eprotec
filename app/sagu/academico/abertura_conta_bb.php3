<?
require("../../../lib/common.php");
require("../lib/Tira_Acento.php3");
require("../lib/Limpa_CPF.php3");
require("../lib/RetornoBanco.php3");
require("../lib/GetField.php3"); 
?>

<?

$brancos="                                                  " .  //  50
         "                                                  " .  // 100
         "                                                  " .  // 150
         "                                                  " .  // 200
         "                                                  " .  // 250
         "                                                  " .  // 300
         "                                                  " .  // 350
         "                                                  " ;  // 400
 
$zeros = "00000000000000000000000000000000000000000000000000" ; // 50

function header_($fp, $seq_remessa)
{
	global $brancos, $zeros;

	$linha = '0000000';					// Identificação do Header
	$linha.= date(dmY);					// Data remessa
	$linha.= 'MCIF460';					// Nome do Arquivo
	$linha.= ' ';						// Branco
	$linha.= '         ';				// Código MCI da Empresa
	$linha.= '     ';					// Número do Processo
	$linha.= $seq_remessa;				// Sequencial Remessa
	$linha.= '02';						// Versão do Layout
	$linha.= '0139';					// Profixo da Agência
	$linha.= '2';						// Digito Verificador
	$linha.= '           ';				// Conta Corrente da Empresa	
	$linha.= ' ';						// Dígito Verificador da Conta Corrente
	$linha.= ' ';						// Indicador de Envio do Kit do Cadastramento
	$linha.= substr($brancos, 0, 88);	// Brancos

	$linha = Tira_Acento($linha);

	fputs($fp, "$linha\n");
}

function detalhe01($fp, $seq, $cpf, $dt_nascimento, $nome, $ref_pessoa)
{
	global $brancos, $zeros;

	$seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
	list($mes, $dia, $ano) = split("-", $dt_nascimento, 3);

	if(strlen($dia)==1) 
		$dia = (string) '0' . $dia;
	if(strlen($mes)==1) 
		$dia = (string) '0' . $mes;

	$linha = $seq;												// Sequencial de registro
	$linha.= '01';												// tipo de detalhe
	$linha.= '1';												// tipo de pessoa
	$linha.= '1';												// tipo de cpf
	$linha.= substr($zeros, 0, 14 - strlen($cpf)) . $cpf;		// cpf
	$linha.= $dia . $mes . $ano;								// Data de Nascimento 
	$linha.= $nome . substr($brancos, 0, 60 - strlen($nome) );	// Nome
	$linha.= substr($brancos, 0, 25);							// Nome personalizado
	$linha.= ' ';												// Branco
	$linha.= $ref_pessoa . substr($brancos, 0, 17 - strlen($ref_pessoa) ); // Campo livre
	$linha.= '0139';											// Prefixo da Agência
	$linha.= '2';												// Dígito Verificador
	$linha.= '  ';												// Grupo Setex
	$linha.= ' ';												// Dígito Verificador Setex
	$linha.= substr($brancos, 0, 8);							// Brancos

	$linha = Tira_Acento($linha);
	fputs($fp, "$linha\n");
}

function detalhe02($fp, $seq, $sexo, $cidade, $rg, $orgao)
{
	global $brancos, $zeros;

	$seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
	$linha = $seq;												// Sequencial de registro
	$linha.= '02';												// tipo de detalhe
	$linha.= strtoupper(substr($sexo, 0, 1));					// sexo
	$linha.= '01';												// nacionalidade
	$linha.= substr($cidade, 0, 25) . substr($brancos, 0, 25-strlen($cidade));	// Naturalidade
	$linha.= '20';												// tipo de documento
	$linha.= $rg . substr($brancos, 0, 20-strlen($rg));			// rg
	$linha.= $orgao . substr($brancos, 0, 15-strlen($orgao));	// orgao emissor
	$linha.= substr($zeros, 0, 8);								// data de emissão
	$linha.= '01';												// estado civil
	$linha.= '00';												// capacidade civil
	$linha.= '000';												// formação
	$linha.= '004';												// grau de instrução
	$linha.= '010';												// natureza da ocupação
	$linha.= '179';												// ocupação
	$linha.= substr($zeros, 0, 15);								// rendimento
	$linha.= substr($zeros, 0, 6);								// mes e ano rendimento
	$linha.= substr($brancos, 0, 33);							// Brancos

	$linha = Tira_Acento($linha);
	fputs($fp, "$linha\n");
}


function detalhe03($fp, $seq, $nome_mae, $nome_pai )
{
    global $brancos, $zeros;
 
    $seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
    $linha = $seq;                                              // Sequencial de registro
	$linha.= '03';												// tipo de detalhe
	$linha.= $nome_mae . substr($brancos, 0, 60-strlen($nome_mae));  // Nome da mae
	$linha.= $nome_pai . substr($brancos, 0, 60-strlen($nome_pai));  // Nome do pai
	$linha.= substr($brancos, 0, 23);							// Brancos

	$linha = Tira_Acento($linha);
	fputs($fp, "$linha\n");
}

function detalhe05($fp, $seq)
{
    global $brancos, $zeros;
 
    $seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
    $linha = $seq;                                              // Sequencial de registro
	$linha.= '05';												// tipo de detalhe
	$linha.= '1';												// Contrato de Trabalho
	$linha.= '2';												// Tipo de Empregador
	$linha.= '04008342000109';									// CGC Empregador
	$linha.= substr($zeros, 0, 6);								// Inicio Emprego
	$linha.= 'FUNDACAO VALE DO TAQUARI DE EDUCACAO DESENVOLVIMENTO SOCIAL ';  // Empregador
	$linha.= 'ESTAGIARIO' . substr($brancos, 0, 50);			// Cargo
	$linha.= substr($brancos, 0, 1);							// Brancos

	$linha = Tira_Acento($linha);
	fputs($fp, "$linha\n");
}

function detalhe06($fp, $seq, $rua, $bairro, $cep, $fone)
{
    global $brancos, $zeros;
 
    $seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
    $linha = $seq;                                              // Sequencial de registro
    $linha.= '06';                                              // tipo de detalhe
	$linha.= $rua . substr($brancos, 0, 60-strlen($rua));		// Logradouro
	$linha.= $bairro . substr($brancos, 0, 30-strlen($bairro));	// Bairro

		$cep = trim(substr(Tira_Acento($cep), 0, 8));
	$linha.= $cep;												// Cep
		if(strlen($cep)<8)
			$linha.= substr($zeros, 0, 8-strlen($cep));			// Cep

	$linha.= '51  ';											// DDD

		$fone =  trim(substr(Tira_Acento($fone), 0, 9));
	$linha.= $fone . substr($brancos, 0, 9-strlen($fone));		// Fone
	$linha.= substr($zeros, 0, 9);								// CP
	$linha.= substr($zeros, 0, 2);								// Situacao do Imóvel
	$linha.= substr($zeros, 0, 6);								// Inicio da Residência

    $linha.= substr($brancos, 0, 15);                            // Brancos
 
	$linha = Tira_Acento($linha);
    fputs($fp, "$linha\n");
}

function detalhe07($fp, $seq)
{
    global $brancos, $zeros;
 
    $seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
    $linha = $seq;                                              // Sequencial de registro
    $linha.= '07';                                              // tipo de detalhe
	$linha.= 'AVELINO TALINI' . substr($brancos, 0, 46);		// Logradouro
	$linha.= 'UNIVERSITARIO' . substr($brancos, 0, 17);			// Bairro
	$linha.= '95900000';										// cep
	$linha.= '51  ';											// DDD
	$linha.= '3714 7000';				 						// Fone
	$linha.= substr($brancos, 0, 20);							// ramal
	$linha.= '155      ';										// CP

	$linha.= substr($brancos, 0, 3);                            // Brancos
 
	$linha = Tira_Acento($linha);
    fputs($fp, "$linha\n");

}


function trailer($fp, $seq, $count)
{
    global $brancos, $zeros; 

    $linha = '9999999'; 										// Sequencial de registro
	$linha.= substr($zeros, 0, 5-strlen($seq)) . $seq;			// Total de registros
	$linha.= substr($zeros, 0, 9-strlen($count)) . $count;		// Total de registros
	$linha.= substr($brancos, 0, 129);							// Brancos

	$linha = Tira_Acento($linha);
	fputs($fp, "$linha\n");
}

?>




<?

	$nome_arquivo   = "../arquivos/MCIF460.txt";
 
	$fp = fopen($nome_arquivo, w); //nome do arquivo deve ser montado

	$seq = 0;

	$conn = new Connection;
	$conn->open();
 
    $sql = " select id, byte_bb from sequencial_banco";
 
    $query = $conn->CreateQuery($sql);
 
    if( $query->MoveNext() )
    {
        $seq_remessa = $query->GetValue(2);
		$seq_remessa++;
		$seq_remessa = (string) substr($zeros, 0, 5-strlen($seq_remessa) ) . $seq_remessa;
    }

	$sql = " update sequencial_banco set byte_bb = $seq_remessa";

	$ok = $conn->Execute($sql);

	header_($fp, $seq_remessa);

	$sql = " select A.id, A.nome, A.cod_cpf_cgc, A.dt_nascimento, " . 
		   "        A.rg_numero, A.rg_orgao, get_cidade(A.ref_cidade), " .
           "        B.pai_nome, B.mae_nome, A.rua, A.bairro, A.cep, ".
           "        A.fone_particular  " .
           " from pessoas A, filiacao B where A.ref_filiacao = B.id " .
           "  and A.conta_bb = 't'";

    $query = $conn->CreateQuery($sql);

    $count = 1;

	while($query->MoveNext())
	{
		list($ref_pessoa, $nome, $cpf, $dt_nascimento, $rg, $orgao, $cidade, $nome_pai, $nome_mae,
             $rua, $bairro, $cep, $fone) = $query->GetRowValues();

		$seq++;
	
	 	$cpf = Limpa_CPF($cpf);

		if($sexo==0)
			$sexo = 'M';
		else
			$sexo = 'F';
 
 		detalhe01($fp, $seq, $cpf, $dt_nascimento, $nome, $ref_pessoa);
		$count++;
		detalhe02($fp, $seq, $sexo, $cidade, $rg, $orgao);
		$count++;
		detalhe03($fp, $seq, $nome_mae, $nome_pai );
		$count++;
		detalhe05($fp, $seq);
		$count++;
		detalhe06($fp, $seq, $rua, $bairro, $cep, $fone);
		$count++;
		detalhe07($fp, $seq);
		$count++;
	}

	$count++;
	trailer($fp, $seq, $count);

?>
<HTML>
<HEAD>
</HEAD>
<BODY bgcolor="#FFFFFF"><a href=<? echo($nome_arquivo)  ?>><? echo($nome_arquivo)  ?></a>
</BODY>
</HTML>
