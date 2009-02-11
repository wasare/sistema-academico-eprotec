<?
  function CalcValorAtual($saldo, $dt_vencimento, $desconto_mes, $multa_pos, $multa_mes, $dias_multa, &$dias)
  {
    $vcto_dt  = $dt_vencimento;
    list($vcto_ano,$vcto_mes,$vcto_dia)=split("-",$vcto_dt,3);
    $vcto_dias = $vcto_dia + ($vcto_mes*30) + ($vcto_ano*365);

    $now_dt  = getdate();
    $now_dia = $now_dt["mday"];
    $now_mes = $now_dt["mon"];
    $now_ano = $now_dt["year"];
    $now_dias = $now_dia + ($now_mes*30) + ($now_ano*365);

    $dias = $now_dias - $vcto_dias;

    $finan_tx_antecipado = $desconto_mes;
    $finan_tx_postergado = $multa_pos;
    $finan_tx_por_dia    = $multa_mes/30;
    $finan_tx_num_dias   = $dias_multa;

    if ($dias <=0)
    {
      // taxa com desconto
      $vlr_atual = $saldo * ((100-$finan_tx_antecipado)/100);
    }
    else
    if ($dias <= $finan_tx_num_dias)
    {
      // taxa normal
      $vlr_atual = $saldo;
    }
    else
    if ($dias > $finan_tx_num_dias )
    {
      // taxa com acréscimo
      $vlr_atual =  $saldo +
      (
        ($saldo * (($finan_tx_postergado)/100))+
        ( $saldo *
          (
            ($finan_tx_por_dia * ($dias-$finan_tx_num_dias)) /100
          )
        )
      );
    }

    $vlr_atual = $vlr_atual*100;
    $vlr_atual = round($vlr_atual);
    settype($vlr_atual, "integer");
    $vlr_atual = $vlr_atual/100;

    if ($saldo==0)
    {
      $vlr_atual=0;
    }

    return $vlr_atual;
  }
?>
