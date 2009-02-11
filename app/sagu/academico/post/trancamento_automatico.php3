<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>Trancamento Automático</title>
<?
CheckFormParameters(array('ref_last_periodo','ref_motivo_desativacao','usuario','senha'));
?>
</script>
</head>
<?

  $conn = new Connection;
  
  $conn->Open();
   
  $sql = " UPDATE contratos " .
         " SET ref_motivo_desativacao = '$ref_motivo_desativacao', " .
         "     dt_desativacao = date(now()) " .
         " WHERE ref_last_periodo = '$ref_last_periodo' and " .
    	 "       dt_desativacao is null";

  $ok = $conn->Execute($sql); 

  SaguAssert($ok,"Nao foi possivel concluir o processamento!!!");
  
  SuccessPage("Processamento concluído com Sucesso","location='/academico/trancamento_automatico.phtml?usuario=$usuario&senha=$senha'","");

  ?>
<body></body>
</html>
