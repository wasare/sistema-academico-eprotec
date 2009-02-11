<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("ref_periodo",
                          "ref_pessoa",
                          "ref_curso",
                          "ref_campus",
                          "ref_disciplina"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " select 1 " .
       " from disciplinas_todos_alunos " .
       " where ref_pessoa = '$ref_pessoa' and " .
       "       ref_curso = '$ref_curso' and " .
       "       ref_campus = '$ref_campus' and " .
       "       ref_disciplina = '$ref_disciplina';";

$query = $conn->CreateQuery($sql);

if ($query->MoveNext())
{
    $sql = " update disciplinas_todos_alunos set " .
           "    fl_autorizado = 't', " .
           "    ref_disciplina_subst = $ref_disciplina_subst " .
           " where ref_pessoa = '$ref_pessoa' and " .
           "       ref_curso = '$ref_curso' and " .
           "       ref_campus = '$ref_campus' and " .
           "       ref_disciplina = '$ref_disciplina';";
}
else
{
    $sql = " INSERT INTO disciplinas_todos_alunos ( " .
           "    ref_pessoa, " .
           "    ref_curso, " .
           "    ref_campus, " .
           "    ref_disciplina, " .
           "    fl_autorizado, " .
           "    ref_disciplina_subst " .
           " ) VALUES ( " .
           "   '$ref_pessoa', " .
           "   '$ref_curso', " .
           "   '$ref_campus', " .
           "   '$ref_disciplina', " .
           "   't', " .
           "   $ref_disciplina_subst) ";

}

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Liberação de disciplina para Aluno",
            "location='../desbloqueia_disciplina.phtml'",
            "Disciplina liberada com sucesso.");
</script>
