<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title>Prepara Previs�es de Lan�amento</title>
<?
CheckFormParameters(array('id_contrato','periodo_id','ref_curso','ref_pessoa','dt_vencimento','dt_lcto','desconto', 'cod_titulo', 'boleto'));
?>
</script>
</head>
  <?

  $conn = new Connection;
  
  $conn->Open();
   
  $sql = " UPDATE previsao_lcto " .
         " SET seq_titulo = seq_titulo + 1 " .
         " WHERE ref_contrato = $id_contrato and " .
         "       ref_pessoa = $ref_pessoa and " .
    	 "       ref_periodo = '$periodo_id' ";


  $ok = $conn->Execute($sql); 

  $sql = " select min(seq_titulo) " .
         " from previsao_lcto " .
    	 " where ref_pessoa = $ref_pessoa and " .
    	 "       ref_contrato = '$id_contrato' and " .
    	 "       ref_periodo = '$periodo_id'";

  $query = $conn->CreateQuery($sql);
  
  if ($query->MoveNext())
  {  
     $seq_titulo = $query->GetValue(1); 
  }
  else
  { 
     SaguAssert(0, "Erro: N�o existe previs�es para esta pessoa com este contrato neste periodo!!!"); 
   }
 
   // Estes links s�o para impress�o de boleto do sicredi
   $link = "<a href=\"/financeiro/post/gera_titulo_previsao.php3" .
           "?ref_contrato=$id_contrato" .
    	   "&ref_periodo=$periodo_id" .
    	   "&ref_curso=$ref_curso" .
    	   "&ref_pessoa=$ref_pessoa" .
    	   "&seq_titulo=$seq_titulo" .
    	   "&dt_vencimento=$dt_vencimento" .
    	   "&dt_lcto=$dt_lcto" .
    	   "&desconto=$desconto" .
           "&cod_titulo=$cod_titulo" .
           "&boleto=$boleto\">Imprimir t�tulo<br>para Calouro</a>";

  SuccessPage("Previs�es de Lan�amento Alteradas","location='/academico/matricula_aluno.phtml'","$link");

  ?>

  <script language="JavaScript">
      alert("Previs�es do Aluno <?echo($ref_pessoa);?> foram ajustadas para o per�odo <?echo($periodo_id);?>.");
  </script>

<body></body>
</html>
