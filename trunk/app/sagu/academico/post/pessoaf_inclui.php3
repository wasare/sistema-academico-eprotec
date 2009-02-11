<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Cadastrar Pessoa</title>
<script language="PHP">
CheckFormParameters(array(
                              "nome",
                              "rua",
//                            "complemento",
//                            "bairro",
                              "cep",
                              "ref_cidade",
//                            "fone_particular",
//                            "fone_profissional",
//                            "fone_celular",
//                            "fone_recado",
//                            "email",
//                            "email_alt",
//                            "estado_civil",
                              "dt_cadastro",
                              "dt_nascimento",
                              "sexo",
                              "deficiencia",
//                            "rg_numero",
//                            "rg_orgao",
//                            "rg_cidade",
//                            "rg_data",
//                            "ref_filiacao",
//                            "ref_naturalidade",
//                            "ref_nacionalidade",
//                            "cod_cpf_cgc",
//                            "titulo_eleitor",
//                            "placa_carro",
                              "fl_dados_pessoais",

//                            "rg_num",
//                            "cpf",
//                            "titulo_eleitord",
//                            "quitacao_eleitoral",
//                            "hist_original",
//                            "hist_escolar",
//                            "doc_militar",
//                            "foto",
//                            "atestado_medico",
//                            "diploma_autenticado",
//                            "solteiro_emancipado",

//                            "ano_2g",
//                            "ref_escola_2g",
//                            "cidade_2g",
//                            "ref_curso_2g",
//                            "cod_passivo",
//                            "obs",
                              "")); // Marca fim da Lista -> é opcional

$dt_cadastro    = InvData($dt_cadastro);
$rg_data        = InvData($rg_data);
$dt_nascimento  = InvData($dt_nascimento);
$cep            = trim(str_replace('-','',$cep));
$cod_cpf_cgc    = str_replace('/','',$cod_cpf_cgc);
$cod_cpf_cgc    = str_replace('-','',$cod_cpf_cgc);
$cod_cpf_cgc    = trim(str_replace('.','',$cod_cpf_cgc));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = "select nextval('seq_pessoas')";

$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id_pessoa = $query->GetValue(1);
  
  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero da pessoa! <br> $sql");

$tipo_pessoa= 'f';

$sql = " insert into pessoas (id," .
       "                      nome," .
       "                      rua," .
       "                      complemento," .
       "                      bairro," .
       "                      cep," .
       "                      ref_cidade," .
       "                      fone_particular," .
       "                      fone_profissional," .
       "                      fone_celular," .
       "                      fone_recado," .
       "                      email," .
       "                      email_alt," .
       "                      estado_civil," .
       "                      dt_cadastro," .
       "                      tipo_pessoa," .
       "                      dt_nascimento," .
       "                      sexo," .
       "                      deficiencia," .
       "                      deficiencia_desc," .
       "                      rg_numero," .
       "                      rg_orgao," .
       "                      rg_cidade," .
       "                      rg_data," .
       "                      fl_cartao, " .
       "                      fl_dados_pessoais, " .
       "                      ref_filiacao," .
       "                      ref_naturalidade," .
       "                      ref_nacionalidade," .
       "                      cod_cpf_cgc," .
       "                      titulo_eleitor," .
       "                      placa_carro," .
       "                      ano_2g," .
       "                      escola_2g," .
       "                      cidade_2g," .
       "                      ref_curso_2g," .
       "                      cod_passivo," . 
       "                      cod_externo," . 
       "                      obs)" .
       "       values (" .
       "                      '$id_pessoa'," .
       "                      '$nome'," .
       "                      '$rua'," .
       "                      '$complemento'," .
       "                      '$bairro'," .
       "                      '$cep'," .
       "                      '$ref_cidade'," .
       "                      '$fone_particular'," .
       "                      '$fone_profissional'," .
       "                      '$fone_celular'," .
       "                      '$fone_recado'," .
       "                      '$email'," .
       "                      '$email_alt'," .
       "                      '$estado_civil'," .
       "                      '$dt_cadastro'," .
       "                      '$tipo_pessoa',";
       if ( $dt_nascimento == '')
         { $sql = $sql . "  null,"; }
       else
         { $sql = $sql . "  '$dt_nascimento',";  }
       
       $sql = $sql . "        '$sexo'," .
       "                      '$deficiencia',";

       if ( $deficiencia_desc == '' )
         { $sql .= " null,"; }
       else
         { $sql .= " '$deficiencia_desc',"; }

       $sql .= "              '$rg_numero'," .
       "                      '$rg_orgao'," .
       "                      '$rg_cidade',";
       if ( $rg_data=='')
         { $sql = $sql . "  null,"; }
       else
         { $sql = $sql . "  '$rg_data',";  }

       if ( ($fl_cartao=='t') || ($fl_cartao=='Sim') )
         { $sql = $sql . "  't',"; }
       else
         { $sql = $sql . "  'f',";  }

       if ( ($fl_dados_pessoais=='t') || ($fl_dados_pessoais=='Sim') )
         { $sql = $sql . "  't',"; }
       else
         { $sql = $sql . "  'f',";  }
       
       $sql = $sql . "        '$ref_filiacao'," .
       "                      '$ref_naturalidade'," .
       "                      '$ref_nacionalidade'," .
       "                      '$cod_cpf_cgc'," .
       "                      '$titulo_eleitor'," .
       "                      '$placa_carro'," .
       "                      '$ano_2g'," .
       "                      '$ref_escola_2g'," .
       "                      '$cidade_2g'," .
       "                      '$ref_curso_2g'," .
       "                      '$cod_passivo'," .
       "                      '$cod_externo'," .
       "                      '$obs')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Não foi possível inserir o registro!<br>$sql");

$sql = " insert into documentos (ref_pessoa," .
       "                         rg_num," .
       "                         cpf," .
       "                         titulo_eleitor," .
       "                         quitacao_eleitoral," .
       "                         hist_original," .
       "                         hist_escolar," .
       "                         doc_militar," .
       "                         foto," . 
       "                         atestado_medico," .
       "                         diploma_autenticado," .
       "                         solteiro_emancipado)" .
       " values ('$id_pessoa',"; 
       if (( $rg_num=='f') || ($rg_num=='')) { $sql = $sql . "  'f',"; }
       if ( $rg_num=='t') { $sql = $sql . "  't',";  }

       if (( $cpf=='f') || ($cpf=='')) { $sql = $sql . "  'f',"; }
       if ( $cpf=='t') { $sql = $sql . "  't',";  }

       if (( $titulo_eleitord=='f') || ($titulo_eleitord=='')) { $sql = $sql . "  'f',"; }
       if ( $titulo_eleitord=='t') { $sql = $sql . "  't',";  }

       if (( $quitacao_eleitoral=='f') || ($quitacao_eleitoral=='')) { $sql = $sql . "  'f',"; }
       if ( $quitacao_eleitoral=='t') { $sql = $sql . "  't',";  }
       
       if (( $hist_original=='f') || ($hist_original=='')) { $sql = $sql . "  'f',"; }
       if ( $hist_original=='t') { $sql = $sql . "  't',";  }
       
       if (( $hist_escolar=='f') || ($hist_escolar=='')) { $sql = $sql . "  'f',"; }
       if ( $hist_escolar=='t') { $sql = $sql . "  't',";  }

       if (( $doc_militar=='f') || ($doc_militar=='')) { $sql = $sql . "  'f',"; }
       if ( $doc_militar=='t') { $sql = $sql . "  't',";  }
       
       if (( $foto=='f') || ($foto=='')) { $sql = $sql . "  'f',"; }
       if ( $foto=='t') { $sql = $sql . "  't',";  }
       
       if (( $atestado_medico=='f') || ($atestado_medico=='')) { $sql = $sql . "  'f',"; }
       if ( $atestado_medico=='t') { $sql = $sql . "  't',";  }

       if (( $diploma_autenticado=='f') || ($diploma_autenticado=='')) { $sql = $sql . "  'f',"; }
       if ( $diploma_autenticado=='t') { $sql = $sql . "  't',";  }

       if (( $solteiro_emancipado=='f') || ($solteiro_emancipado=='')) { $sql = $sql . "  'f');"; }
       if ( $solteiro_emancipado=='t') { $sql = $sql . "  't');";  }

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!<br>$sql");

SuccessPage("Inclusão de Alunos",
            "location='../pessoaf_inclui.phtml'",
            "O código do Aluno é $id_pessoa",
            "location='../consulta_inclui_pessoa.phtml'");

</script>
</head>
<body>
</body>
</html>
