<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from coordenadores where ref_campus = '$ref_campus' and ref_curso = '$ref_curso'";

$ok = $conn->Execute($sql);

$conn->Close();

saguassert($ok,"N�o foi poss�vel de excluir o coordenador!");

SuccessPage("Coordenador exclu�do do curso com sucesso",
            "location='../coordenadores.phtml'");
</script>