<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<script language="PHP">
  CheckFormParameters(array("ref_curso",
                            "ref_campus",
                            "ref_disciplina",
                            "ref_disciplina_equivalente"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into cursos_disciplinas_compl (" .
       "        ref_curso," .
       "        ref_campus," .
       "        ref_disciplina," .
       "        ref_disciplina_equivalente " .
       " ) values ( " .
       "        '$ref_curso', " .
       "        '$ref_campus', " .
       "        '$ref_disciplina', " .
       "        '$ref_disciplina_equivalente'" .
       " )";  

$ok = $conn->Execute($sql);

if ( !$ok )
{
    $conn->Finish();
    $conn->Close();
    SaguAssert($ok,"Nao foi possivel inserir a disciplina equivalente!");
}

$sql = " insert into cursos_disciplinas (" .
       "    ref_curso, " .
       "    ref_campus, " .
       "    ref_disciplina, " .
       "    semestre_curso, " .
       "    curriculo_mco, " .
       "    equivalencia_disciplina, " .
       "    cursa_outra_disciplina, " .
       "    esconde_historico, " .
       "    dt_inicio_curriculo, " .
       "    dt_final_curriculo, " .
       "    curso_substituido, " .
       "    disciplina_substituida, " .
       "    pre_requisito_hora, " .
       "    exibe_historico, " .
       "    fl_soma_curriculo, " .
       "    ref_area)" .
       " select ref_curso, " .
       "        ref_campus, " .
       "        '$ref_disciplina_equivalente', " .
       "        semestre_curso, " .
       "        curriculo_mco, " .
       "        equivalencia_disciplina, " .
       "        '1', " .
       "        esconde_historico, " .
       "        dt_inicio_curriculo, " .
       "        dt_final_curriculo, " .
       "        curso_substituido, " .
       "        disciplina_substituida, " .
       "        pre_requisito_hora, " .
       "        exibe_historico, " .
       "        'f', " .
       "        ref_area " .
       " from cursos_disciplinas " .
       " where ref_curso = '$ref_curso' and " .
       "       ref_campus = '$ref_campus' and " .
       "       ref_disciplina = '$ref_disciplina';";

$ok = $conn->Execute($sql);

if ( !$ok )
{
    $conn->Finish();
    $conn->Close();
    SaguAssert($ok,"Nao foi possivel inserir a disciplina equivalente no curso disciplina!");
}

$sql = " UPDATE cursos_disciplinas SET " .
       "    cursa_outra_disciplina = '1' " .
       " where ref_curso = '$ref_curso' and " .
       "       ref_campus = '$ref_campus' and " .
       "       ref_disciplina = '$ref_disciplina';";

$ok = $conn->Execute($sql);

if ( !$ok )
{
    $conn->Finish();
    $conn->Close();
    SaguAssert($ok,"Nao foi possivel atualizar a disciplina do currículo!");
}

$conn->Finish();

$conn->Close();

SuccessPage("Inclusão de Disciplinas que podem ser cursadas em lugar de outra",
            "location='../consulta_inclui_cursos_disciplinas.phtml?ref_disciplina=$ref_disciplina&ref_curso=$ref_curso&ref_campus=$ref_campus'",
            "Disciplina incluída com sucesso!!!");

</script>
</head>
<body>
</body>
</html>
