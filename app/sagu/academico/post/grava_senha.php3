<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>UNIVATES</title>
<script language="PHP">

 $fd = fopen ("../../file.txt", "r");
 $x = 0;

 while (!feof ($fd))
 {
   $linha[$x] = fgets($fd, 4096);

   list($user[$x],
        $passwd[$x]) = split(":", $linha[$x], 2);

   if ($user[$x] == $usuario)
   {
      $password = trim($passwd[$x]);
      $passwd[$x] = md5($password1);
   }
  $x++;
 }
 fclose ($fd);
      
 if ( $password != md5($senha_atual))
 {
   SaguAssert(0, "SENHA ATUAL INV�LIDA");
   return false;
 }
 if (!$senha_atual) {
   SaguAssert(0, "Voc� n�o digitou a Senha Atual.");
   return false;
 }
 if (!$password1 || !$password2) {
        SaguAssert(0, "Voc� deve digitar duas vezes a senha.");
        return false;
 }
 if ($password1 != $password2) {
        SaguAssert(0, "As duas senhas n�o s�o as mesmas.");
        return false;
 }

 $num = count($user);

 for ($x=0; $x<$num; $x++)
 {
    if (($user[$x] != '') && ($passwd[$x] != ''))
    {
      $row .= trim($user[$x]) . ":" . trim($passwd[$x]) . "\n";
    }
 }  
 
 $fd = fopen ("../../file.txt", "w");
 
 fwrite($fd, $row);

 fclose ($fd);

 SuccessPage("Senha do Administrador Acad�mico",
             "location='../senha_administrador.phtml'",
             "Senha do Administrador Acad�mico<br>alterada com sucesso!!!");


</script>
</head>
</body>
</html>
