<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("ref_periodo",
                          "ref_pessoa",
                          "ref_contrato",
                          "ref_curso",
                          "ref_campus",
                          "fl_limite_cr",
                          "fl_limite_turno"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " select 1 " .
       " from autorizacao " .
       " where ref_periodo = '$ref_periodo' and " .
       "       ref_contrato = '$ref_contrato' and " .
       "       ref_pessoa = '$ref_pessoa' and " .
       "       ref_curso = '$ref_curso' and " .
       "       ref_campus = '$ref_campus';";

$query = $conn->CreateQuery($sql);

if ($query->MoveNext())
{
    $sql = " update autorizacao set " .
           "    fl_limite_cr = '$fl_limite_cr', " .
           "    fl_limite_turno = '$fl_limite_turno' " .
           " where ref_periodo = '$ref_periodo' and " .
           "       ref_contrato = '$ref_contrato' and " .
           "       ref_pessoa = '$ref_pessoa' and " .
           "       ref_curso = '$ref_curso' and " .
           "       ref_campus = '$ref_campus'";
}
else
{
    $sql = " INSERT INTO autorizacao ( " .
           "    ref_periodo, " .
           "    ref_contrato, " .
           "    ref_pessoa, " .
           "    ref_curso, " .
           "    ref_campus, " .
           "    fl_limite_cr, " .
           "    fl_limite_turno " .
           " ) VALUES ( " .
           "   '$ref_periodo', " .
           "   '$ref_contrato', " .
           "   '$ref_pessoa', " .
           "   '$ref_curso', " .
           "   '$ref_campus', " .
           "   '$fl_limite_cr', " .
           "   '$fl_limite_turno') ";

}

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Autorização de Matrícula para Aluno",
            "location='../autorizacao_matricula.phtml'",
            "Autorização efetuada com sucesso.");
</script>
