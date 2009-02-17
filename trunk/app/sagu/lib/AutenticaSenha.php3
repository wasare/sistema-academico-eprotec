<script language="PHP">

require_once("../../../lib/config.php");

Function AutenticaSenha($usuario, $senha)
{
    global $BASE_DIR . 'app/sagu';

    $fd = fopen ($BASE_DIR ."app/sagu/file.txt", "r");
    $x = 0;
 
    while (!feof ($fd)) 
    {
       $linha[$x] = fgets($fd, 4096);

       list($user[$x], 
            $passwd[$x]) = split(":", $linha[$x], 2);

       if ($user[$x] == $usuario)
       {
            $password = trim($passwd[$x]);
       }
       $x++;
    }
    
    fclose ($fd);

    if (md5($senha) != $password)
    {
        SaguAssert(0, "SENHA DO ADMINISTRADOR ACADÊMICO INCORRETA!!!");
    }
}

</script>
