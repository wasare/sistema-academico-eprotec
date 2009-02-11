<? require("../../lib/vest.php3"); ?>
<? require("../../lib/vestibular/common.php3"); ?>

<html>
<head>
<title>Pessoa Cadastrada</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="PHP">

  CheckFormParameters(array(
                            "nome",
//                            "rua",
//                            "complemento",
//                            "bairro",
//                            "cep",
//                            "ref_cidade",
//                            "fone_particular",
//                            "fone_profissional",
//                            "fone_celular",
//                            "fone_recado",
//                            "email",
//                            "email_alt",
//                            "estado_civil",
//                            "dt_cadastro",
//                            "obs",
//                            "dt_nascimento",
//                            "sexo",
//                            "rg_numero",
//                            "rg_orgao",
//                            "rg_cidade",
//                            "rg_data",
//                            "ref_filiacao",
//                            "ref_naturalidade",
//                            "ref_nacionalidade",
                            "cod_cpf_cgc",
//                            "titulo_eleitor",
//                            "placa_carro",
//                            "ano_2g",
//                            "cidade_2g",
//                            "ref_curso_2g",
//                            "cod_passivo",
                              "")); // Marca fim da Lista -> é opcional

  $rua = "";
  $complemento = "";
  $bairro = "";
  $cep = "";
  $ref_cidade = "";
  $fone_particular = "";
  $fone_profissional = "";
  $fone_celular = "";
  $fone_recado = "";
  $email = "";
  $email_alt = "";
  $estado_civil = "";
  $dt_cadastro = date(d\/m\/y);
  $obs = "";
  $dt_nascimento = date(d\/m\/y);
  $sexo = "";
  $rg_numero = "";
  $rg_orgao = "";
  $rg_cidade = "";
  $rg_data = date(d\/m\/y);
  $ref_filiacao = "";
  $ref_naturalidade = "";
  $ref_nacionalidade = "";
  $titulo_eleitor = "";
  $placa_carro = "";
  $ano_2g = "";
  $cidade_2g = "";
  $ref_curso_2g = "";
  $cod_passivo = "";

  $conn = new Connection;

  $conn->Open();
    
  $sql = "select nextval('seq_pessoas')";
  
  $query = $conn->CreateQuery($sql);
  
  $success = false;
  
  if ( $query->MoveNext() )
  {
    $id_pessoa = $query->GetValue(1);
    
    $success = true;
  }
  
  $query->Close();

SaguAssert($success,"Nao foi possivel obter um numero da pessoa!");

$tipo_pessoa= 'f';

$sql = "insert into pessoas (" .
       "                               id," .
       "                               nome," .
       "                               rua," .
       "                               complemento," .
       "                               bairro," .
       "                               cep," .
       "                               ref_cidade," .
       "                               fone_particular," .
       "                               fone_profissional," .
       "                               fone_celular," .
       "                               fone_recado," .
       "                               email," .
       "                               email_alt," .
       "                               estado_civil," .
       "                               dt_cadastro," .
       "                               tipo_pessoa," .
       "                               obs," .
       "                               dt_nascimento," .
       "                               sexo," .
       "                               rg_numero," .
       "                               rg_orgao," .
       "                               rg_cidade," .
       "                               rg_data," .
       "                               ref_filiacao," .
       "                               ref_naturalidade," .
       "                               ref_nacionalidade," .
       "                               cod_cpf_cgc," .
       "                               titulo_eleitor," .
       "                               placa_carro," .
       "                               ano_2g," .
       "                               cidade_2g," .
       "                               ref_curso_2g," .
       "                               cod_passivo)" . 
       "       values (" .
       "                               $id_pessoa," .
       "                               '$nome'," .
       "                               '$rua'," .
       "                               '$complemento'," .
       "                               '$bairro'," .
       "                               '$cep'," .
       "                               '$ref_cidade'," .
       "                               '$fone_particular'," .
       "                               '$fone_profissional'," .
       "                               '$fone_celular'," .
       "                               '$fone_recado'," .
       "                               '$email'," .
       "                               '$email_alt'," .
       "                               '$estado_civil'," .
       "                               '$dt_cadastro'," .
       "                               '$tipo_pessoa'," .
       "                               '$obs'," .
       "                               '$dt_nascimento'," .
       "                               '$sexo'," .
       "                               '$rg_numero'," .
       "                               '$rg_orgao'," .
       "                               '$rg_cidade'," .
       "                               '$rg_data'," .
       "                               '$ref_filiacao'," .
       "                               '$ref_naturalidade'," .
       "                               '$ref_nacionalidade'," .
       "                               '$cod_cpf_cgc'," .
       "                               '$titulo_eleitor'," .
       "                               '$placa_carro'," .
       "                               '$ano_2g'," .
       "                               '$cidade_2g'," .
       "                               '$ref_curso_2g'," .
       "                               '$cod_passivo')";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$err= $conn->GetError();

$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!<BR><BR>$err");

</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<table width="500" border="0" cellspacing="0" cellpadding="0" height="40" align="center">
  <tr bgcolor="#000099"> 
    <td height="35"> 
      <div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><b><font color="#CCCCFF"> 
        Opera&ccedil;&atilde;o Conclu&iacute;da</font></b></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><b></b></font></div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<p align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Aluno 
  Cadastrado com sucesso:</b></font></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">C&oacute;digo 
  do Professor: </font><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"> 
  <script language="PHP">
echo("$id_pessoa");
</script>
  </font></b></p>
<p align="center">&nbsp;</p>
<form name="myform" action="../pessoaf_seguro_inclui.phtml" >
  <div align="center">
    <input type="button" name="Button" value="Incluir outro Aluno" onclick="location='../consulta_inclui_pessoa.phtml'">
    <input type="button" name="Button2" value="Sair" onClick="javascript:history.go(-1)">

  </div>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
