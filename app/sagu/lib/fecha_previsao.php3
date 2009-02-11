<!-- Função que Fecha previsão de lançamento para um mês    -->
<!-- faz o saldo fechar em 0 com um lançamento do saldo     -->
<!-- em aberto                                              -->
<!-- Autor Pablo Dall'Oglio, Ultima modificação: 01/02/2001 -->

<script language="PHP">
function fecha_previsao($conn, $ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, $ref_historico, $ref_contrato, $seq_titulo, $valor, $dt_contabil)
{
  $sql_previsao = "insert into previsao_lcto" .
                  "  (" .
                  "     ref_pessoa,     ref_curso,    ref_campus,   ref_periodo,   ref_historico, " .
                  "     ref_contrato,   seq_titulo,   valor,        fl_prehist,    dt_contabil )" .
                  "  values" .
                  "  ( '$ref_pessoa',  '$ref_curso', '$ref_campus','$ref_periodo','$ref_historico',".
                  "    '$ref_contrato','$seq_titulo', $valor,       'f',           '$dt_contabil' )";

  $ok = $conn->Execute($sql_previsao);

  $sql_previsao = "update previsao_lcto set fl_lock='T' where ref_pessoa='$ref_pessoa' and " .
                          " ref_contrato='$ref_contrato' and seq_titulo='$seq_titulo' " .
                          " and ref_periodo='$ref_periodo'";
  $ok = $conn->Execute($sql_previsao);
}
</script>