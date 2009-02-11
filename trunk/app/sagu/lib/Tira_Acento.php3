<?
/**
* Função que recebe uma string e compara caracter a caracter com um cadeia 
* de caracteres acentuados e substitui pelo equivalente de uma cadeia de 
* caracteres sem acento
***/

Function Tira_Acento($string)
{

   set_time_limit(240);

   $acentos = 'áéíóúÁÉÍÓÚàÀÂâÊêôÔüÜïÏöÖñÑãÃõÕçÇªºäÄ\'';
   $letras  = 'AEIOUAEIOUAAAAEEOOUUIIOONNAAOOCCAOAA ';
//   $letras  = 'aeiouAEIOUaAAaEeoOuUiIoOnNaAoOcCaoaA ';



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
