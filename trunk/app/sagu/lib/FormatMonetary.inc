<?
// FormatMonetary comes from Agata Report
// used in Diario Auxiliar and Razao Auxiliar
// by Pablo Dall'Oglio

function FormatMonetary($number, $precision, $thousep, $decsep)
{
  $zeros = '000000000000';
  $brancos = '          ';
  $brancos .= $brancos;
  $size = 14;

  if (strstr($number, '.'))
  {
    $a = explode('.', $number);
  }
  else if (strstr($number, ','))
  {
    $a = explode(',', $number);
  }
  else
  {
    $a[0] = $number;
  }
  $part1 = $a[0];
  $part2 = trim(substr($a[1],0,$precision));
   
  //if (!$part2)
  //  $part2 = substr($zeros, 0, $precision);

  // Correçao feita por Beto - 20/04/2004
  // A função não estava formatando corretamento números com 
  // uma única casa decimal após a vírgula.
  if (strlen($part2) < $precision)
  {
    $part2 = $part2 . substr($zeros, 0, ($precision - strlen($part2)));
  }
  
  $tmp = strrev($part1);

  for ($n=0; $n<strlen($tmp); $n++)
  {
    if ($i==3)
    {
      $resultpart1 .= $thousep;
      $i = 0;
    }
    $i ++;
    $resultpart1 .= substr($tmp,$n,1);
  }
  $part1 = strrev($resultpart1);
  $result = substr($brancos, 0, $size - strlen($part1)) . $part1 . $decsep . $part2;
  return $result;
}
?>
