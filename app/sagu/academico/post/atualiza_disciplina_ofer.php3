<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array("id"));

$conn = new Connection;

$conn->Open();

$turno = substr($turno, 0, 1);

$sql = " update disciplinas_ofer set " .
       "    id = '$id'," .
       "    ref_campus = '$ref_campus'," .
       "    ref_curso = '$ref_curso'," .
       "    ref_periodo = '$ref_periodo'," .
       "    ref_disciplina = '$ref_disciplina'," .
       "    num_alunos = '$num_alunos'," .
       "    fixar_num_sala = '$fixar_num_sala'," .
       "    is_cancelada = '$is_cancelada'," .
       "    turma = '$turma'," .
       "    ref_periodo_turma = '$ref_periodo_turma'," .
       "    conteudo = '$conteudo'" .
       "  where id = '$id'";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possível de atualizar o registro!");

$conn->Close();

SuccessPage("Registro Atualizado com sucesso","location='../disciplina_ofer.phtml'");

</script>
</head>
<body bgcolor="#FFFFFF">
</body>
</html>
