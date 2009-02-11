<? require("../../../../lib/common.php"); ?>
<? require("../../lib/ProcessaMaterial.php3"); ?>
<html>
<head>
<title>Coleta próximo mês e número de parcelas</title>
<?
CheckFormParameters(array('ref_periodo','ref_contrato'));
?>
</head>
<body bgcolor="#FFFFFF">
<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
  <tr align="center" bgcolor="#0066CC"> 
    <td colspan="7" height="28"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Aguarde ....</b></font></td>
  </tr>
 
  <?
  $conn = new Connection;
  $conn->Open();
 
  $sql = " select ref_curso, " .
         "        ref_campus, " .
    	 "        ref_pessoa " .
	     " from contratos " .
    	 " where id = '$ref_contrato'";

  $query = @$conn->CreateQuery($sql);

  if ( @$query->MoveNext() )
  {
    list($ref_curso,
         $ref_campus,
     	 $ref_pessoa) = $query->GetRowValues();
  }
  else
  {
    SaguAssert(0,"Contrato não encontrado!!!");
  }

  $query->Close();
  $conn->Close();
  
  // Processa material de matrícula
  ProcessaMaterial($ref_periodo, $ref_curso, $ref_campus, $ref_pessoa);

  $conn = new Connection;
  $conn->Open();

  // Busca o nº de parcelas que foi feito a matrícula da pessoa.
  $sql = " select count(*) " .
  	     " from previsao_lcto " .
      	 " where ref_contrato='$ref_contrato' and " .
  	     "	 ref_periodo='$ref_periodo' " .
      	 " group by seq_titulo " .
	     " order by seq_titulo ";
  
  $query = $conn->CreateQuery($sql);
  
  if ($query->MoveNext())
  {                                    // Verifica se a primeira sequencia nao é zero (casos raros)
     $previsoes = $query->GetValue(1); // Casos que a pessoa nao tem nenhuma previsao_lcto
  }

  $parcelas = $query->GetRowCount();   // Pega o nº de parcelas
 
  $query->Close();

  // Busca o valor da sequencia atual de pagamento
  $sql = " select max(sequencia)+1 " .
      	 " from log_titulos " .
	     " where ref_periodo='$ref_periodo';";
 
  $query = $conn->CreateQuery($sql);
 
  if ($query->MoveNext())
  {
    $mes_sequencia = $query->GetValue(1);
  }
  
  if (!$mes_sequencia)
  {
     $mes_sequencia = 1;
  }
  $query->Close();
  
  if (($previsoes == 0) && ($parcelas == 1)) // Isso bloqueia a ação (Envia zero para o formulário)
  {                                          // Casos que a pessoa não tem nenhuma previsao_lcto
     $parcelas_a_dividir = 0;
     $mes_sequencia = 0;
  }
  else
  {
    if ($parcelas == 5)
    {
       $parcelas_a_dividir = ($parcelas - $mes_sequencia) + 2;  // Cinco Parcelas
    }
    elseif ($parcelas == 4)
    {
       $parcelas_a_dividir = ($parcelas - $mes_sequencia) + 3;  // Quatro Parcelas
    }
    elseif ($parcelas == 3)
    {
       $parcelas_a_dividir = ($parcelas - $mes_sequencia) + 4;  // Tres Parcelas
    }
    elseif ($parcelas == 2)
    {
       $parcelas_a_dividir = ($parcelas - $mes_sequencia) + 5; // Duas Parcelas
    }
    else
    {
       $parcelas_a_dividir = ($parcelas - $mes_sequencia) + 1; // Seis Parcelas
    }
    
    if ($parcelas_a_dividir <= 0)           // Isso bloqueia a ação (Envia zero para o formulário)
    {
       $parcelas_a_dividir = 0;
       $mes_sequencia = 0;
    }
  
  }
  ?>
  <script language="JavaScript">
        window.opener.SetResultParcelas(<? echo("'$mes_sequencia','$parcelas_a_dividir','$parcelas'") ?>);
        window.close();
  </script>
</table>
</body>
</html>
