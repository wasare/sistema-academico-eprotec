<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<HEAD><script language="PHP">

CheckFormParameters(array("ref_matricula",
        		          "ref_contrato",
                          "ref_pessoa",
                          "ref_campus",
                          "ref_curso",
                          "ref_curso_subst",
                          "ref_periodo",
                          "ref_disciplina",
                          "ref_disciplina_subst",
                          "ref_disciplina_ofer"));

SaguAssert($ref_disciplina_subst && $ref_curso_subst,"Você não informou a disciplina substituída ou o curso substituído!!!");

$conn = new Connection;

$conn->Open();

// Solicitação da Neri para que as disciplinas com nomes iguais não 
// apareçam entre parênteses na impressão de documentos - Beto 14/07/2004
if ($ref_disciplina == $ref_disciplina_subst)
{ $ref_disciplina_subst = 0; }

if ($ref_curso == $ref_curso_subst)
{ $ref_curso_subst = 0; }

$sql = " update matricula set " .
       "    ref_contrato = '$ref_contrato'," .
       "    ref_pessoa = '$ref_pessoa'," .
       "    ref_campus = '$ref_campus'," .
       "    ref_curso = '$ref_curso'," .
       "    ref_curso_subst = '$ref_curso_subst'," .
       "    ref_periodo = '$ref_periodo'," .
       "    ref_disciplina = '$ref_disciplina'," .
       "    ref_disciplina_subst = '$ref_disciplina_subst'," .
       "    ref_disciplina_ofer = '$ref_disciplina_ofer'," .
       "    nota_final = '$nota_final'," .
       "    conceito = '$conceito_final'," .
       "    carga_horaria_aprov = '$carga_horaria_aprov', " .
       "    creditos_aprov = '$creditos_aprov', " .
       "    fl_exibe_displ_hist = 'S' " .
       " where id = '$ref_matricula'";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$location_vars = "?ref_contrato=$ref_contrato" .
                 "&ref_pessoa=$ref_pessoa" .
                 "&aluno_nome=$aluno_nome" .
                 "&curso=$ref_curso" .
                 "&ref_campus=$ref_campus";
                   
SuccessPage("Aproveitamento Interno",
            "location='/academico/aproveitamento_interno_disc.phtml$location_vars'");
</script>
</HEAD>
<BODY></BODY>
</html>
