<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Exibi��o dos Cursos no Livro de Matr�cula</title>
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

   SaguAssert($ok,"N�o foi poss�vel fazer as altera��es!!!");
   SuccessPage("Configura��o de Exibi��o de Cursos",
               "location='../livro_matricula_periodo_selecionado.php3?ref_periodo=$ref_periodo'",
               "Configura��o de Exibi��o de Cursos efetuada com sucesso.");
?>
</body>
</html>
