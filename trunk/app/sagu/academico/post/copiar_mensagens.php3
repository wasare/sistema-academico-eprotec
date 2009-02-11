<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array(
                            "periodo_de",
                            "periodo_para"
                            ));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into mensagens (" .
       "	    ref_periodo, " .
       "	    texto, " .
       "        fonte, " .
       "        tamanho, " .
       "        sequencia, " .
       "        ref_status, " .
       "        fl_ouvinte) " .
       " (select '$periodo_para', " .
       "         texto, " .
       "         fonte, " .
       "         tamanho, " .
       "         sequencia, " .
       "         ref_status, " .
       "         fl_ouvinte " .
       " from mensagens where ref_periodo = '$periodo_de')";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$err= $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!<BR><BR>$err");

SuccessPage("Registros copiados com sucesso!!!",
            "location='../consulta_inclui_mensagem.phtml'",
            "Cópia feita do período <b>$periodo_de</b> para o <b>$periodo_para</b>.");
</script>
</HEAD>
<BODY></BODY>
</HTML>
