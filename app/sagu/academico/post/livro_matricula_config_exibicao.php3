<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Exibição dos Cursos no Livro de Matrícula</title>
</head>
<body>
<?

   CheckFormParameters( array("ref_periodo",
                              "submit") );

   $conn = new Connection;
   $conn->Open();
   $conn->Begin();

   $max=count($ref_curso);

   for ( $i=0; $i<$max; $i++ )
   {
      $sql = " update cursos SET " .
             "     sequencia = '$seq[$i]' " .
             " where id = '$ref_curso[$i]'";

      $ok = $conn->Execute($sql);
      $err= $conn->GetError();
   }

   $conn->Finish();
   $conn->Close();

   SaguAssert($ok,"Não foi possível fazer as alterações!!!");
   SuccessPage("Configuração de Exibição de Cursos",
               "location='../livro_matricula_periodo_selecionado.php3?ref_periodo=$ref_periodo'",
               "Configuração de Exibição de Cursos efetuada com sucesso.");
?>
</body>
</html>
