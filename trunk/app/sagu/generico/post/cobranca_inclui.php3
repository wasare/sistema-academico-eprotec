<? require("../../../../lib/common.php"); ?>
<? require("../../lib/vestibular/common.php3"); ?> 
<script language="PHP">
CheckFormParameters(array(
                          "cobr_folha_debito",
                          "cobr_ref_folha_titular",
                          "cobr_bairro",
                          "cobr_cidade",
                          "cobr_rua",
                          "cobr_cep",
                          "cobr_complemento",
                          "cobr_banco",
                          "cobr_agencia",
                          "cobr_conta",
                          "cobr_identificador",
                          "cobr_dt_autorizacao"));
  $conn = new Connection;

  $conn->Open();
    
  $sql = "select nextval('seq_cobranca_pessoa')";
  
  $query = $conn->CreateQuery($sql);
  
  $success = false;
  
  if ( $query->MoveNext() )
  {
    $id_cobranca = $query->GetValue(1);
    
    $success = true;
  }
  
  $query->Close();

SaguAssert($success,"Nao foi possivel obter um numero de cobranca!");



$sql = "insert into cobranca_pessoa (" .
       "                               id," .
       "                               cobr_folha_debito," .
       "                               cobr_ref_folha_titular," .
       "                               cobr_bairro," .
       "                               cobr_cidade," .
       "                               cobr_rua," .
       "                               cobr_cep," .
       "                               cobr_complemento," .
       "                               cobr_banco," .
       "                               cobr_agencia," .
       "                               cobr_conta," .
       "                               cobr_identificador," .
       "                               cobr_dt_autorizacao)" . 
       "       values (" .
       "                               '$id_cobranca'," .
       "                               '$cobr_folha_debito'," .
       "                               '$cobr_ref_folha_titular'," .
       "                               '$cobr_bairro'," .
       "                               '$cobr_cidade'," .
       "                               '$cobr_rua'," .
       "                               '$cobr_cep'," .
       "                               '$cobr_complemento'," .
       "                               '$cobr_banco'," .
       "                               '$cobr_agencia'," .
       "                               '$cobr_conta'," .
       "                               '$cobr_identificador'," .
       "                               '$cobr_dt_autorizacao')";

// $query = $conn->CreateQuery();

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

SaguAssert($ok,"Nao foi possivel inserir o registro!");

// $query->Close();

$conn->Close();

list ( $cobr_ref_folha_titular_, $tmp1, $tmp2 ) = GetPessoaNome($cobr_ref_folha_titular,true);
</script>
<script language="JavaScript">
function Select_Cobranca(id,cobr_ref_folha_titular,cobr_ref_folha_titular2)
{
 window.opener.document.myform.ref_cobranca.value = id;
 window.opener.document.myform.cobr_ref_folha_titular.value = cobr_ref_folha_titular;
 window.opener.document.myform.cobr_nome_folha_titular.value = cobr_ref_folha_titular2;
 window.close();
}
</script>
<body bgcolor="#FFFFFF">
<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="26%"> 
      <div align="center"><img src="../../images/logo_ies.gif" width="104" height="94"></div>
    </td>
    <td width="74%"> 
      <div align="center"><b><font size="4" color="#0066CC" face="Verdana, Arial, Helvetica, sans-serif">Nova 
        Cobran&ccedil;a </font></b></div>
    </td>
  </tr>
</table>
<p align="center">&nbsp;</p>
<p align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><b>Dados 
  Inseridos com sucesso:</b></font></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">C&oacute;digo 
  de Cobran&ccedil;a: </font><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"> 
  <script language="PHP">
if ($cobr_folha_debito = '1')
   {
     echo("<a href=\"javascript:Select_Cobranca($id_cobranca,'$cobr_ref_folha_titular','$cobr_ref_folha_titular_')\"> $id_cobranca </a>");
   }
else
   {
     echo("<a href=\"javascript:Select_Cobranca($id_cobranca,\"\",\"\")\"> $id_cobranca </a>");
   }
</script>
  </font></b></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b></b></font></b></font></p>
