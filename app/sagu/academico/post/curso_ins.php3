<? require("../../../../lib/common.php"); ?>
<? require("../../lib/VerificaChaveUnica.php3"); ?>
<html>
<head>
<title>UNIVATES</title>
<script language="PHP">

  CheckFormParameters(array("id",
                            "descricao",
                            "abreviatura",
                            "turno",
                            "agrupo_curso",
                            "ref_tipo_curso"));

SaguAssert(VerificaChaveUnica("cursos", "id", "$id"), "J� existente Curso com esse c�digo!");

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into cursos (id," .
       "                     descricao," . 
       "                     abreviatura," . 
       "                     sigla," . 
       "                     total_creditos," .
       "                     total_carga_horaria," .
       "                     total_semestres," .
       "                     grau_academico," .
       "                     exigencias," .
       "                     agrupo_curso," .
       "                     ref_area," .
       "                     reconhecimento, " .
       "                     autorizacao, " .
       "                     turno, " .
       "                     ref_tipo_curso, " .
       "                     historico) " .
       " values ('$id'," .
       "         '$descricao'," . 
       "         '$abreviatura',".
       "         '$sigla',".
       "         '$total_creditos',".
       "         '$total_carga_horaria',".
       "         '$total_semestres',".
       "         '$grau_academico',".
       "         '$exigencias',".
       "         '$agrupo_curso',".
       "         '$ref_area',".
       "         '$reconhecimento',".
       "         '$autorizacao',".
       "         '$turno',".
       "         '$ref_tipo_curso', " .
       "         '$cabecalho_historico')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel inserir o curso!!!");

SuccessPage("Inclus�o de Cursos",
            "location='../curso_ins.phtml'",
            "Curso inclu�do com sucesso!!!",
            "location='../consulta_cursos.phtml'");
</script>
</head>
<body>
</body>
</html>
