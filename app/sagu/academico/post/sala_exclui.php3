<? require("../../../../lib/common.php"); ?>

<?

   $conn = new Connection;
   $conn->Open();

   $sql = "delete from salas" .
          " where id='$id'";

   $ok = $conn->Execute($sql);

   $conn->Close();

   SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

   SuccessPage("Registro exclu�do com sucesso",
               "location='../cadastro_salas.phtml'",
               "A sala foi exclu�da com sucesso.");

?>
