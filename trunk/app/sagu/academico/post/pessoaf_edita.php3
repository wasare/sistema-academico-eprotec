<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<script language="PHP">

$dt_cadastro   = InvData($dt_cadastro);
$rg_data	   = InvData($rg_data);
$dt_nascimento = InvData($dt_nascimento);
$cep           = trim(str_replace('-','',$cep));
$cod_cpf_cgc   = str_replace('/','',$cod_cpf_cgc);
$cod_cpf_cgc   = str_replace('-','',$cod_cpf_cgc);
$cod_cpf_cgc   = trim(str_replace('.','',$cod_cpf_cgc));

if ( ($quitacao_eleitoral=='t') || ($quitacao_eleitoral=='Sim') )
  $fl_quitacao_eleitoral = 1;
else
  $fl_quitacao_eleitoral = 0;
  
if (($estado_civil == 'Solteiro Emancipado') || ($estado_civil == 'E'))
  $estado_civil = 'E';
else
  $estado_civil = substr($estado_civil, 0, 1);

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "update pessoas set " .
       "    id = '$id'," .
       "    nome = '$nome'," .
       "    rua = '$rua'," .
       "    complemento = '$complemento'," .
       "    bairro = '$bairro'," .
       "    cep = '$cep'," .
       "    ref_cidade = '$ref_cidade'," .
       "    fone_particular = '$fone_particular'," .
       "    fone_profissional = '$fone_profissional'," .
       "    fone_celular = '$fone_celular'," .
       "    fone_recado  = '$fone_recado' ," .
       "    email        = '$email'       ," .
       "    email_alt    = '$email_alt'   ," .
       "    estado_civil = '$estado_civil'," .
       "    dt_cadastro = '$dt_cadastro',";
       if ( $dt_nascimento=='')
         { $sql = $sql . "dt_nascimento = null,"; }
         else
         { $sql = $sql . " dt_nascimento = '$dt_nascimento',";  }
        $sql = $sql . "  sexo = '$sexo'," .
       "    deficiencia = '$deficiencia'," .
       "    deficiencia_desc = '$deficiencia_desc'," .
       "    rg_numero = '$rg_numero'," .
       "    rg_orgao = '$rg_orgao'," .
       "    rg_cidade = '$rg_cidade',";
       if ( $rg_data=='')
         { $sql = $sql . " rg_data = null,"; }
         else
         { $sql = $sql . " rg_data = '$rg_data',";  }
        $sql = $sql . "  ref_filiacao = '$ref_filiacao'," .
       "    ref_naturalidade = '$ref_naturalidade'," .
       "    ref_nacionalidade = '$ref_nacionalidade'," .
       "    cod_cpf_cgc = '$cod_cpf_cgc'," .
       "    titulo_eleitor = '$titulo_eleitor'," .
       "    placa_carro = '$placa_carro',";
       if ( ($fl_cartao=='t') || ($fl_cartao=='Sim') )
         { $sql = $sql . " fl_cartao = 't',"; }
       else
         { $sql = $sql . " fl_cartao = 'f',";  }
       
       if ( ($fl_dados_pessoais=='t') || ($fl_dados_pessoais=='Sim') )
         { $sql = $sql . " fl_dados_pessoais = 't',"; }
       else
         { $sql = $sql . " fl_dados_pessoais = 'f',";  }
       $sql = $sql . "    ano_2g = '$ano_2g'," .
       "    escola_2g = '$ref_escola_2g', ".
       "    cidade_2g = '$cidade_2g'," .
       "    ref_curso_2g = '$ref_curso_2g'," .
       "    cod_passivo = '$cod_passivo', " .
       "    cod_externo = '$cod_externo', " .
       "    obs = '$obs'," .
       "    fl_quitacao_eleitoral = '$fl_quitacao_eleitoral' " .
       "  where id = '$id'";

echo "<!-- $sql -->";
$ok = $conn->Execute($sql);  

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

$sql = "update documentos set " .
       "    ref_pessoa = '$id',";
       if ( ($rg_num=='t') || ($rg_num=='Sim') )
         { $sql = $sql . " rg_num = 't',"; }
       else
         { $sql = $sql . " rg_num = 'f',";  }

       if ( ($cpf=='t') || ($cpf=='Sim') )
         { $sql = $sql . " cpf = 't',"; }
       else
         { $sql = $sql . " cpf = 'f',";  }

       if ( ($titulo_eleitord=='t') || ($titulo_eleitord=='Sim') )
         { $sql = $sql . " titulo_eleitor = 't',"; }
       else
         { $sql = $sql . " titulo_eleitor = 'f',";  }
       
       if ( ($quitacao_eleitoral=='t') || ($quitacao_eleitoral=='Sim') )
         { $sql = $sql . " quitacao_eleitoral = 't',"; }
       else
         { $sql = $sql . " quitacao_eleitoral = 'f',";  }
       
       if ( ($hist_original=='t') || ($hist_original=='Sim') )
         { $sql = $sql . " hist_original = 't',"; }
       else
         { $sql = $sql . " hist_original = 'f',";  }
       
       if ( ($hist_escolar=='t') || ($hist_escolar=='Sim') )
         { $sql = $sql . " hist_escolar = 't',"; }
       else
         { $sql = $sql . " hist_escolar = 'f',";  }

       if ( ($doc_militar=='t') || ($doc_militar=='Sim') )
         { $sql = $sql . " doc_militar = 't',"; }
       else
         { $sql = $sql . " doc_militar = 'f',";  }
       
       if ( ($foto=='t') || ($foto=='Sim') )
         { $sql = $sql . " foto = 't',"; }
       else
         { $sql = $sql . " foto = 'f',";  }
       
       if ( ($atestado_medico=='t') || ($atestado_medico=='Sim') )
         { $sql = $sql . " atestado_medico = 't',"; }
       else
         { $sql = $sql . " atestado_medico = 'f',";  }
       
       if ( ($diploma_autenticado=='t') || ($diploma_autenticado=='Sim') )
         { $sql = $sql . " diploma_autenticado = 't',"; }
       else
         { $sql = $sql . " diploma_autenticado = 'f',";  }
       
       if ( ($solteiro_emancipado=='t') || ($solteiro_emancipado=='Sim') )
         { $sql = $sql . " solteiro_emancipado = 't' "; }
       else
         { $sql = $sql . " solteiro_emancipado = 'f' ";  }
       
       $sql = $sql . " where ref_pessoa = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");
SuccessPage("Alteração de Alunos",
            "location='../consulta_inclui_pessoa.phtml'",
            "As informações de <b>$nome</b> foram atualizadas com sucesso.");
</script>
