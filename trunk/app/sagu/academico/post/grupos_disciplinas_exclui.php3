<? require("../../../../lib/common.php"); ?>

<HTML><HEAD><script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from grupos_disciplinas where id='$id';"; 

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../consulta_inclui_grupos_disciplinas.phtml'");
</script>
</HEAD>
<BODY></BODY>
</HTML>
