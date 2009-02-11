<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

  CheckFormParameters(array( "id",
                             "ref_disciplina",
                             "ref_disciplina_equivalente",
                             "ref_curso"));

  $conn = new Connection;
  $conn->Open();
  $conn->Begin();

  $sql = " select descricao_disciplina('$ref_disciplina'), " .
         "        descricao_disciplina('$ref_disciplina_equivalente'), " .
         "        curso_desc('$ref_curso'); ";
         
  $query = $conn->CreateQuery($sql);
  
  $query->MoveNext();
  
  list ( $descricao1,
         $descricao2,
         $curso) = $query->GetRowValues();

  $query->Close();

  $sql = " update disciplinas_equivalentes set" .
         "    ref_disciplina = '$ref_disciplina'," .
         "    ref_disciplina_equivalente = '$ref_disciplina_equivalente', " .
         "    ref_curso = '$ref_curso' " .
         " where id = '$id' ";

  $ok = $conn->Execute($sql);

  SaguAssert($ok,"Nao foi possivel inserir o registro!");

  $conn->Finish();
  $conn->Close();

  SuccessPage("Equivalência de de Disciplina alterada com sucesso", 
              "location='../consulta_disciplinas_equivalentes.phtml'",
              "Disciplina: $descricao1 ($ref_disciplina)<br>Disciplina Equivalente: $descricao2 ($ref_disciplina_equivalente)<br>Curso: $curso ($ref_curso)");

</script>
</head>
<body>
</body>
</html>
