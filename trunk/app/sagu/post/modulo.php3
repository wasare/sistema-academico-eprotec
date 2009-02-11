<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<script language=PHP>
CheckFormParameters(array("nome_modulo"));

$id_modulo = GetIdentity('seq_sagu_modulos');

$conn = new Connection;
$conn->Open();

$sql = "insert into sagu_modulos (" .
       "                               id,".
       "                               nome_modulo)" . 
       "       values (" .
       "                               '$id_modulo',".
       "                               '$nome_modulo')";

$ok = $conn->Execute($sql);  

$conn->Close();
SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Módulo",
            "location='/tools.phtml'",
            "O id do módulo é <b>$id_modulo</b>.");

</script>
</HEAD>
<BODY>
</BODY>
</HTML>
