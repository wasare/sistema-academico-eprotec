<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>Exibição dos Cursos</title>
</head>
<body>
<?


    SaguAssert(count($ref_curso)>0,"Campos curso não informado");
    
    $conn = new Connection;
    $conn->Open();
    $conn->Begin();

    $max=count($ref_curso);

    for ($i=0; $i<$max; $i++)
    {
        $sql = " update cursos SET " .
               "     sequencia = '$sequencia[$i]' " .
               " where id = '$ref_curso[$i]'";

        $ok = $conn->Execute($sql);
    
        if (!$ok)
            break;
    }

    $conn->Finish();
    $conn->Close();

    SaguAssert($ok,"Não foi possível fazer as alterações!!!");
    SuccessPage("Configuração de Exibição de Cursos",
                "location='../lista_cursos.php3'",
                "Configuração de Exibição de Cursos efetuada com sucesso.");
?>
</body>
</html>
