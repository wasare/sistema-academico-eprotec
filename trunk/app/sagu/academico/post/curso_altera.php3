<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "descricao",
                          "abreviatura",
                          "agrupo_curso",
            			  "ref_tipo_curso",
			              "turno"));

$conn = new Connection;

$conn->Open();

$turno = substr($turno, 0, 1);

$sql = "update cursos set " .
       "    id = '$id'," .
       "    descricao = '$descricao'," .
       "    abreviatura = '$abreviatura'," .
       "    sigla = '$sigla'," .
       "    total_creditos = '$total_creditos'," .
       "    total_carga_horaria = '$total_carga_horaria'," .
       "    total_semestres = '$total_semestres'," .
       "    grau_academico = '$grau_academico'," .
       "    exigencias = '$exigencias'," .
       "    agrupo_curso = '$agrupo_curso'," .
       "    ref_area = '$ref_area'," .
       "    reconhecimento = '$reconhecimento'," .
       "    autorizacao = '$autorizacao'," .
       "    turno = '$turno'," .
       "    ref_tipo_curso = '$ref_tipo_curso'," .
       "    historico = '$historico'" .
       "  where id = '$id'";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Curso",
            "location='../consulta_cursos.phtml'",
            "Curso alterado com sucesso.");
</script>
