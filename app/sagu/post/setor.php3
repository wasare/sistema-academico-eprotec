<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<script language=PHP>
CheckFormParameters(array("nome_setor"));

$id_setor=GetIdentity('seq_sagu_setores');

$conn = new Connection;
$conn->Open();

$sql = "insert into sagu_setores (" .
       "                               id,".
       "                               nome_setor," .
       "                               email)" . 
       "       values (" .
       "                               '$id_setor',".
       "                               '$nome_setor'," .
       "                               '$email')";

$ok = $conn->Execute($sql);  

$conn->Close();
SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Setor",
            "location='/tools.phtml'",
            "O id do setor é <b>$id_setor</b>.");

</script>
</HEAD>
<BODY>
</BODY>
</HTML>
