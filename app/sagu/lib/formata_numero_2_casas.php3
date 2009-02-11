<?
Function formata_numero_2_casas($numero)
{
    $numero = (int)($numero*100);
    $numero = round($numero);
    $numero = $numero / 100;
    $numero = sprintf("%0.2f", $numero);

    $t_casas = strlen($numero);

    $new_numero = substr($numero, $t_casas-2, 2);
    $new_numero = ',' . $new_numero;

    $aux_val = 4;

    $i = 0;
    for($aux_val; $aux_val<=$t_casas; $aux_val++)
    {
        $i++;
        if(($i == 3) || ($aux_val == $t_casas))
        {
           $new_numero = substr($numero, $t_casas-$aux_val, $i) . $new_numero;
           if($aux_val != $t_casas)
           {
               $new_numero = '.' . $new_numero;    
           }
           $i = 0;
        }
    }

    return $new_numero;
}

?>