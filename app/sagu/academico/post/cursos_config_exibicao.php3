<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>Exibi��o dos Cursos</title>
</head>
<body>
<?


    SaguAssert(count($ref_curso)>0,"Campos curso n�o informado");
    
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

    SaguAssert($ok,"N�o foi poss�vel fazer as altera��es!!!");
    SuccessPage("Configura��o de Exibi��o de Cursos",
                "location='../lista_cursos.php3'",
                "Configura��o de Exibi��o de Cursos efetuada com sucesso.");
?>
</body>
</html>
