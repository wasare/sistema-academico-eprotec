<? require("../../../../lib/common.php"); ?>

<?

CheckFormParameters(array("id",
                          "ref_periodo",
                          "texto",
                          "fonte",
                          "tamanho",
                          "sequencia",
            			  "ref_status",
            			  "fl_ouvinte"));

$fonte = substr($fonte, 0, 1);

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update mensagens set " .
       "    id = '$id'," .
       "    ref_periodo = '$ref_periodo'," .
       "    texto = '$texto'," .
       "    fonte = '$fonte'," .
       "    tamanho = '$tamanho'," .
       "    sequencia = '$sequencia',";
       
       if ( ($ref_status=='G') || ($ref_status=='Graduacao') )
         { $sql = $sql . " ref_status = 'G',"; }
       if ( ($ref_status=='C') || ($ref_status=='Calouro') )
         { $sql = $sql . " ref_status = 'C',"; }
       if ( ($ref_status=='O') || ($ref_status=='Outros') )
         { $sql = $sql . " ref_status = 'O',"; }
       
       if ( ($fl_ouvinte=='t') || ($fl_ouvinte=='Sim') )
         { $sql = $sql . " fl_ouvinte = 't'"; }
       else
         { $sql = $sql . " fl_ouvinte = 'f'";  }

       $sql = $sql . " where id = '$id' and ref_periodo = '$ref_periodo'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Mensagens",
            "location='../consulta_inclui_mensagem.phtml'",
            "Mensagem alterada com sucesso.");
?>
