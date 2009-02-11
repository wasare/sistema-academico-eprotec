<!-- Funções para integração contábil                       -->
<!-- Autor Pablo Dall'Oglio, Ultima modificação: 08/02/2001 -->

<?

// -----------------------------------------------------------
// Purpose: Write to Siga's Contábil file
// -----------------------------------------------------------
define("QUEBRA", chr(13) . chr(10));
function WriteToSigaFile($handle, $cod_lcto, $tipo_lcto, $conta_debito, $conta_credito, $historico, $valor_lancto, $ccusto_debito, $ccusto_credito, $lote, $documento, $linha, $separator = QUEBRA, $item_deb = '', $item_cred = '')
{

  $brancos =   "                                                  "; //  50
  $brancos .= $brancos;
  $brancos .= $brancos . $brancos . $brancos . $brancos . $brancos;
  $zeros   =   "00000000000000000000000000000000000000000000000000" .
               "00000000000000000000000000000000000000000000000000"; //100

  $linha_mens  = $cod_lcto;
  $linha_mens .= trim($tipo_lcto);
  $linha_mens .= $conta_debito . substr($brancos,0,20-strlen($conta_debito));
  $linha_mens .= $conta_credito . substr($brancos,0,20-strlen($conta_credito));
  if (strlen($historico)>40)
  {
    $historico = substr($historico,0,39);
  }
  $linha_mens .= strtoupper($historico) . substr($brancos,0,40-strlen($historico));
  $linha_mens .= Formata_Numero($valor_lancto,15,2);
  $linha_mens .= $ccusto_debito . substr($brancos,0,9-strlen($ccusto_debito));
  $linha_mens .= $ccusto_credito . substr($brancos,0,9-strlen($ccusto_credito));
  $linha_mens .= '  ' . substr($zeros,0,9-strlen($item_deb))  . $item_deb;
  $linha_mens .= ' ' . substr($zeros,0,9-strlen($item_cred)) . $item_cred;
  $linha_mens .= substr($brancos,0,510-strlen($linha_mens));
  $linha_mens .= $separator;  // retorno de carro

  fputs($handle, "$linha_mens");

}


// -----------------------------------------------------------
// Purpose: Formatar sufixo contábil de um curso
// -----------------------------------------------------------
function formata_sufixo($sufixo)
{
   return substr($sufixo,0,2) . "." . substr($sufixo,2,3) . "." . substr($sufixo,5,3);
}


// -----------------------------------------------------------
// Purpose: Formatar numero com 0's a esquerda, sem ponto dec
// -----------------------------------------------------------
function Formata_Numero($numero,$len,$dec)
{
  $numero = str_replace(',','.',$numero);
  $retorno = sprintf("%0" . $len . "." . $dec . "f", $numero);
  return ereg_replace( "\.", "", "$retorno" );
}

function FormataConta($conta)
{
    $tmp1 = substr($conta,0,3);
    $tmp2 = substr($conta,3,2);
    $tmp3 = substr($conta,5,2);
    $tmp4 = substr($conta,7,3);
    $tmp5 = substr($conta,10,3);

    return "{$tmp1}.{$tmp2}.{$tmp3}.{$tmp4}.{$tmp5}";
}


?>
