<?
/**
* Função que recebe uma string e compara caracter a caracter com um cadeia 
* de caracteres acentuados e substitui pelo equivalente de uma cadeia de 
* caracteres sem acento
***/

Function Limpa_CPF($string)
{

   set_time_limit(240);

   $acentos = '1234567890\-._ ';
   $letras  = '1234567890';

   $new_string = '';

   for($x=0; $x<strlen($string); $x++)
   {
      $let = substr($string, $x, 1);

      for($y=0; $y<strlen($acentos); $y++)
      {
         if($let==substr($acentos, $y, 1))
         {
            $let=substr($letras, $y, 1);
            break;
         }
      }

      $new_string = $new_string . $let;
   }

   return $new_string;

}

?>
