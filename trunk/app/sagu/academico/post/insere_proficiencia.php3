<? require("../../../../lib/common.php"); ?>
<html>
<head>

<script language="PHP">

CheckFormParameters(array("ref_pessoa",
                          "ref_contrato",
                          "ref_curso",
                          "ref_campus",
                          "ref_periodo"));

SaguAssert(count($ref_disciplina)>0,"Selecione pelo menos uma proficiência!");

$conn = new Connection;

$conn->Open();

$conn->Begin();

for ( $i=0; $i<count($ref_disciplina); $i++ )
{
    if (!empty(${'opcao_' . $i}))
    {
        $sql = " insert into matricula (" .
               "    ref_contrato," .
               "    ref_pessoa," .
               "    ref_campus," .
               "    ref_curso," .
               "    ref_periodo," .
               "    ref_disciplina," .
               "    conceito," .
               "    fl_exibe_displ_hist, " .
               "    dt_matricula," .
               "    hora_matricula)" .
               " values (" .
               "    '$ref_contrato'," .
               "    '$ref_pessoa'," .
               "    '$ref_campus'," .
               "    '$ref_curso'," .
               "    '$ref_periodo'," .
               "    '$ref_disciplina[$i]'," .
               "    'A'," .
               "    'N'," .
               "    date(now())," .
               "    now())";

        echo("<!--\n$sql\n-->\n");

        $ok = $conn->Execute($sql);
        
        if ( !$ok )
            break;
    }
}

$conn->Finish();

$conn->Close();

SaguAssert($ok,"Inclusão das Proficiências falhou!!!");

$url = "../consultas_diversas.phtml" .
       "?periodo=" . urlencode($ref_periodo) .
       "&pessoa="   . urlencode($ref_pessoa);

SuccessPage("Proficiência(s) inserida(s) com Sucesso",$url);
</script>
</head>

<body bgcolor="#FFFFFF">

</body>
</html>
