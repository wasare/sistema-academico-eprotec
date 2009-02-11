<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

  CheckFormParameters(array( "ref_curso",
                             "ref_disciplina",
                             "ref_disciplina_equivalente") );

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

  $id = GetIdentity("seq_disciplinas_equivalentes_id");

  $sql = "insert into disciplinas_equivalentes" .
         "  (" .
         "    id," .
         "    ref_disciplina," .
         "    ref_disciplina_equivalente," .
         "    ref_curso" .
         "  )" .
         "  values" .
         "  (" .
         "    '$id'," .
         "    '$ref_disciplina'," .
         "    '$ref_disciplina_equivalente'," .
         "    '$ref_curso'" .
         "  )";

  $ok = $conn->Execute($sql);

  SaguAssert($ok,"Nao foi possivel inserir o registro!");

  $conn->Finish();
  $conn->Close();

  SuccessPage("Equivalência de de Disciplina incluída com sucesso", 
              "location='../inclui_disciplinas_equivalentes.phtml'",
              "Disciplina: $descricao1 ($ref_disciplina)<br>Disciplina Equivalente: $descricao2 ($ref_disciplina_equivalente)<br>Curso: $curso ($ref_curso)",
              "location='../consulta_disciplinas_equivalentes.phtml'");

</script>
</head>
<body>
</body>
</html>
