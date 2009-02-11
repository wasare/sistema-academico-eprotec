<script language="PHP">
//----------------------------------------------------------------------------
// Formata o nome das pessoas. Primeira letra em maiúscula 
// Paulo Roberto Mallmann - 23/08/2001
//----------------------------------------------------------------------------
Function FormataNome($nome)
{
  $tamanho = strlen($nome);

  $nome = strtolower(strtr($nome, "ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜİ","àáâãäåçèéêëìíîïñòóôõöøùúûüı"));
  
  for($y = 0; $y < $tamanho; $y++)
  {
    $caract = substr($nome, $y, 1);
    if ((((substr($nome, $y - 1, 1)) == ' ') || ($y == 0)))
            $obj .= strtoupper(substr(strtr($nome, "àáâãäåçèéêëìíîïñòóôõöøùúûüı","ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜİ"), $y, 1));
    else
            $obj .= $caract;
  }
  
return $obj;
}
</script>
