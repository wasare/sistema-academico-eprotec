<?php
include ('../../webdiario.conf.php');
// CONECT NO BANCO
////////////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

      $sql1 = "SELECT DISTINCT
      a.login,
      a.id_nome,
      a.senha
      FROM
      diario_usuarios a, pessoas b
      WHERE
      a.login = '$us'
      AND
      a.id_nome = b.id";
      $query1 = pg_exec($dbconnect, $sql1);
      while($linha1 = pg_fetch_array($query1)) 	{
        		$idpessoas = $linha1["id_nome"];
        		$senhaoriginal = $linha1["senha"];
                }
                
      if ( md5($passwdoriginal) != $senhaoriginal) {
      print '<script language=javascript>
						 window.alert("Sua senha atual não confere !");
						javascript:window.history.back(1);
						</script>';
                        exit;          }
      if (($passwnew1 != $passwnew2) or ($passwnew1 == '')) {
      print '<script language=javascript>
						 window.alert("As novas senhas não coicidem !");
						javascript:window.history.back(1);
						</script>';
                        exit;         } else {
      if ($us != 'NONAME') 
      {

	$passwnew1 = md5($_POST['passwnew1']);

      	$sql1 = "UPDATE diario_usuarios SET senha = '$passwnew1' WHERE id_nome = '$idpessoas'";
	$query1 =  pg_exec($dbconnect, $sql1);

      print ('<script language=javascript>
   window.alert("Troca de senha para o usuário '.$us.' realizada com sucesso ! ");
   self.location.href = "../../prin.php?y=2003"
   </script>');
   } else {
      print ("Tá querendo né ??");
      }
    }
   pg_close($dbconnect);
?>

