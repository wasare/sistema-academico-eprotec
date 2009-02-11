<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("ref_campus",
                          "ref_cursos",
                          "ref_professor"));

$conn = new Connection;

$conn->Open();

$sql = " insert into coordenadores ( " .
       "        ref_professor," .
       "        ref_campus," .
       "        ref_curso)" . 
       " values ( " .
       "        '$ref_professor'," .
       "        '$ref_campus', " .
       "        '$ref_cursos')";


$ok = $conn->Execute($sql);

saguassert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

SuccessPage("Inclusão de Coordenadores",
            "location='../coordenadores.phtml'",
            "Coordenador incluído com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>