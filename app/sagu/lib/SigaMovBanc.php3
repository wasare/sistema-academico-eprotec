<!-- Arquivo para integração do Contas a Receber do SAGU    -->
<!-- com o contas a Receber do MicroSiga    (não utilizado) -->
<!-- Autor Pablo Dall'Oglio                                 -->

<?

// -----------------------------------------------------------
// Purpose: Write to Siga's file 
// -----------------------------------------------------------
function WriteToSiga($id, $ref_titulo, $ref_historico, $info_historico, $dt_lancto, $valor_lancto, $aux_letra, $id_ret_banco, $pre_linha)
{

  $brancos =    "                                                  "; //  50
  $zeros   =   "00000000000000000000000000000000000000000000000000" .
    "00000000000000000000000000000000000000000000000000"; //100
  $aTIPO_DOC['D'] = 'DC';
  $aTIPO_DOC['J'] = 'JR';
  $aTIPO_DOC['M'] = 'MT';
  $aTIPO_DOC['E'] = 'ES';
  $aTIPO_DOC['N'] = 'VL';

  $nome_arq = "/usr/local/sagu/html/financeiro/siga_movimenta.txt";

  $sql1 = "select operacao,descricao,tipo from historicos where id = '$ref_historico'";
  $sql2 = "select b.cod_banco, b.prefixo_agencia, b.digito_agencia, b.num_ccorrente, " .
          "       b.digito_ccorrente, a.ref_pessoa, pessoa_nome(a.ref_pessoa) " .
          "from titulos_cr a, locais_pgto b where a.id='$ref_titulo' and a.ref_local=b.id";

  $conn = new Connection;
  $conn->Open();

  $query1 = $conn->CreateQuery($sql1);
  $query2 = $conn->CreateQuery($sql2);

  if ( @$query1->MoveNext() )
  { list($operacao, $descricao, $tipo) = $query1->GetRowValues(); }

  if ( @$query2->MoveNext() )
  { list($cod_banco, $prefixo_agencia, $digito_agencia, $num_ccorrente,
         $digito_ccorrente, $ref_pessoa, $pessoa_nome) = $query2->GetRowValues(); }

  $tipo_doc= $aTIPO_DOC[$tipo];

  $query1->Close();
  $query2->Close();
  $conn->Close();

  // processa pré-linha para Descontos, Juros ou Multas -->Chamada Recursiva apresentou problemas
  if (($tipo_doc == 'DC' || $tipo_doc == 'JR' || $tipo_doc == 'MT') && (!$prelinha))
  {
  WriteToSiga2($id, $ref_titulo, $ref_historico, $info_historico, $dt_lancto, $valor_lancto, $aux_letra, $id_ret_banco, true);
  }

  $pessoa_nome .= $brancos;
  $descricao   .= $brancos;

  if ($tipo_doc == 'ES')
  { $motivo_baixa = 'DEV'; } // Devolução - Estorno
  else
  { $motivo_baixa = 'NOR'; } // Baixa Normal


  if ($cod_banco=='0')  // em carteira
  {
    $cod_banco = 'CX1';
    $agencia   = 'CX1  ';
    $conta     = 'CX1       ';
  }
  else  // concatena codigos bancarios
  {
    $agencia = $prefixo_agencia . $digito_agencia;
    $conta = $num_ccorrente . $digito_ccorrente;
  }

  if ($operacao == 'C')
  { $recpag = 'R'; }
  else
  { $recpag = 'P'; }

  // Se é estorno, a operação é Pagamento (P), senão, é Recebimento (R).
  // Separar Juros, multa, o Valor no final.
  $linha_mens  = DMA_To_AMD_Siga($dt_lancto);      // data do movimento
  $linha_mens .= "VL";            // Tipo do Título
  $linha_mens .= "M1";            // Numerário

  if ($pre_linha)
  { $linha_mens .= Formata_Numero(0,15,2); }      // Valor do Movimento
  else
  { $linha_mens .= Formata_Numero($valor_lancto,15,2); }  // Valor do Movimento

  $linha_mens .= "0000000001";          // Natureza
  $linha_mens .= $cod_banco;          // Banco
  $linha_mens .= $agencia;          // Agência
  $linha_mens .= $conta;          // Conta Banco
  $linha_mens .= $recpag;          // Rec/Pag
  $linha_mens .= substr($pessoa_nome,0,30);      // Beneficiario
  $linha_mens .= substr($descricao,0,40);      // Historico
  $linha_mens .= $tipo_doc;          // Tipo de Documento
  $linha_mens .= Formata_Numero($valor_lancto,15,2);    // Valor Moeda 2
  $linha_mens .= "  ";            // Ident. LA
  $linha_mens .= "   ";            // Prefixo
  $linha_mens .= "1" . substr($ref_titulo,5,6);      // Titulo
  $linha_mens .= "1";            // Parcela
  $linha_mens .= substr($zeros,0,6-strlen($ref_pessoa)) . $ref_pessoa;    // Cliente/Fornecedor
  $linha_mens .= "01";            // Loja
  $linha_mens .= DMA_To_AMD_Siga($dt_lancto);      // Data Digitacao
  $linha_mens .= $motivo_baixa;          // Motivo da Baixa
  $linha_mens .= "01";            // Sequencia
  $linha_mens .= DMA_To_AMD_Siga($dt_lancto);      // Data Disponibilidade

  if ($tipo_doc == 'JR')
  { $linha_mens .= Formata_Numero($valor_lancto,15,2);}    // Valor dos Juros
  else
  { $linha_mens .= Formata_Numero(0,15,2);}

  if ($tipo_doc == 'MT')
  { $linha_mens .= Formata_Numero($valor_lancto,15,2);}    // Valor da Multa
  else
  { $linha_mens .= Formata_Numero(0,15,2);}

  $linha_mens .= Formata_Numero(0,15,2);      // Valor Correção

  if ($tipo_doc == 'DC')
  { $linha_mens .= Formata_Numero($valor_lancto,15,2);}    // Valor desconto
  else
  { $linha_mens .= Formata_Numero(0,15,2);}

  $myfile = fopen($nome_arq, "a");
  if (!$myfile)
  {
     echo("Não foi possível criar o arquivo. Verifique!");
     exit;
  }

  $linha_mens .= chr(13) . chr(10);        // retorno de carro
  fputs($myfile, "$linha_mens");

  fclose($myfile);
}


// -----------------------------------------------------------
// Purpose: Converte a data de formato D/M/AAAA para AAAAMMDD
// -----------------------------------------------------------
function DMA_To_AMD_Siga($dt)
{
  $m = substr($dt,0,2);
  $d = substr($dt,3,2);
  $a = substr($dt,6,4);
  return $a . $m . $d;
}

// -----------------------------------------------------------
// Purpose: Formatar numero com 0's a esquerda, sem ponto dec
// -----------------------------------------------------------
function Formata_Numero($numero,$len,$dec)
{
  $retorno = sprintf("%0" . $len . "." . $dec . "f", $numero);
  return ereg_replace( "\.", "", "$retorno" );
}

function WriteToSiga2($id, $ref_titulo, $ref_historico, $info_historico, $dt_lancto, $valor_lancto, $aux_letra, $id_ret_banco, $pre_linha)
{

  $brancos =    "                                                  "; //  50
  $zeros   =   "00000000000000000000000000000000000000000000000000" .
    "00000000000000000000000000000000000000000000000000"; //100
  $aTIPO_DOC['D'] = 'DC';
  $aTIPO_DOC['J'] = 'JR';
  $aTIPO_DOC['M'] = 'MT';
  $aTIPO_DOC['E'] = 'ES';
  $aTIPO_DOC['N'] = 'VL';

  $nome_arq = "/usr/local/sagu/html/financeiro/siga_movimenta.txt";

  $sql1 = "select operacao,descricao,tipo from historicos where id = '$ref_historico'";
  $sql2 = "select b.cod_banco, b.prefixo_agencia, b.digito_agencia, b.num_ccorrente, " .
          "       b.digito_ccorrente, a.ref_pessoa, pessoa_nome(a.ref_pessoa) " .
          "from titulos_cr a, locais_pgto b where a.id='$ref_titulo' and a.ref_local=b.id";

  $conn = new Connection;
  $conn->Open();

  $query1 = $conn->CreateQuery($sql1);
  $query2 = $conn->CreateQuery($sql2);

  if ( @$query1->MoveNext() )
  { list($operacao, $descricao, $tipo) = $query1->GetRowValues(); }

  if ( @$query2->MoveNext() )
  { list($cod_banco, $prefixo_agencia, $digito_agencia, $num_ccorrente,
         $digito_ccorrente, $ref_pessoa, $pessoa_nome) = $query2->GetRowValues(); }

  $tipo_doc= $aTIPO_DOC[$tipo];

  $query1->Close();
  $query2->Close();
  $conn->Close();

  // processa pré-linha para Descontos, Juros ou Multas -->Chamada Recursiva
  $pessoa_nome .= $brancos;
  $descricao   .= $brancos;

  if ($tipo_doc == 'ES')
  { $motivo_baixa = 'DEV'; } // Devolução - Estorno
  else
  { $motivo_baixa = 'NOR'; } // Baixa Normal


  if ($cod_banco=='0')  // em carteira
  {
    $cod_banco = 'CX1';
    $agencia   = 'CX1  ';
    $conta     = 'CX1       ';
  }
  else  // concatena codigos bancarios
  {
    $agencia = $prefixo_agencia . $digito_agencia;
    $conta = $num_ccorrente . $digito_ccorrente;
  }

  if ($operacao == 'C')
  { $recpag = 'R'; }
  else
  { $recpag = 'P'; }

  // Se é estorno, a operação é Pagamento (P), senão, é Recebimento (R).
  // Separar Juros, multa, o Valor no final.
  $linha_mens  = DMA_To_AMD_Siga($dt_lancto);      // data do movimento
  $linha_mens .= "VL";            // Tipo do Título
  $linha_mens .= "M1";            // Numerário

  if ($pre_linha)
  { $linha_mens .= Formata_Numero(0,15,2); }      // Valor do Movimento
  else
  { $linha_mens .= Formata_Numero($valor_lancto,15,2); }  // Valor do Movimento

  $linha_mens .= "0000000001";          // Natureza
  $linha_mens .= $cod_banco;          // Banco
  $linha_mens .= $agencia;          // Agência
  $linha_mens .= $conta;          // Conta Banco
  $linha_mens .= $recpag;          // Rec/Pag
  $linha_mens .= substr($pessoa_nome,0,30);      // Beneficiario
  $linha_mens .= substr($descricao,0,40);      // Historico
  $linha_mens .= $tipo_doc;          // Tipo de Documento
  $linha_mens .= Formata_Numero($valor_lancto,15,2);    // Valor Moeda 2
  $linha_mens .= "  ";            // Ident. LA
  $linha_mens .= "   ";            // Prefixo
  $linha_mens .= "1" . substr($ref_titulo,5,6);      // Titulo
  $linha_mens .= "1";            // Parcela
  $linha_mens .= substr($zeros,0,6-strlen($ref_pessoa)) . $ref_pessoa;    // Cliente/Fornecedor
  $linha_mens .= "01";            // Loja
  $linha_mens .= DMA_To_AMD_Siga($dt_lancto);      // Data Digitacao
  $linha_mens .= $motivo_baixa;          // Motivo da Baixa
  $linha_mens .= "01";            // Sequencia
  $linha_mens .= DMA_To_AMD_Siga($dt_lancto);      // Data Disponibilidade

  if ($tipo_doc == 'JR')
  { $linha_mens .= Formata_Numero($valor_lancto,15,2);}    // Valor dos Juros
  else
  { $linha_mens .= Formata_Numero(0,15,2);}

  if ($tipo_doc == 'MT')
  { $linha_mens .= Formata_Numero($valor_lancto,15,2);}    // Valor da Multa
  else
  { $linha_mens .= Formata_Numero(0,15,2);}

  $linha_mens .= Formata_Numero(0,15,2);      // Valor Correção

  if ($tipo_doc == 'DC')
  { $linha_mens .= Formata_Numero($valor_lancto,15,2);}    // Valor desconto
  else
  { $linha_mens .= Formata_Numero(0,15,2);}

  $myfile = fopen($nome_arq, "a");
  if (!$myfile)
  {
     echo("Não foi possível criar o arquivo. Verifique!");
     exit;
  }

  $linha_mens .= chr(13) . chr(10);        // retorno de carro
  fputs($myfile, "$linha_mens");

  fclose($myfile);
}


?>
