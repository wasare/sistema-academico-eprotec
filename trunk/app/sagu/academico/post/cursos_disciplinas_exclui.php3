<? require("../../../../lib/common.php"); ?>

<HTML><HEAD><script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from cursos_disciplinas where ref_curso='$ref_curso' and ref_campus='$ref_campus' and ref_disciplina='$ref_disciplina';"; 

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
            "location='../consulta_inclui_cursos_disciplinas.phtml'");
</script>
</HEAD>
<BODY></BODY>
</HTML>
