<?php
include("../../conf/webdiario.conf.php");

$user = $_POST['user'];
$senha = md5($_POST['senha']);

$result = diario_sql($user, $senha);
	 if ($result == 'invalido') {
         print '<html>
                <body>
                <SCRIPT LANGUAGE="JavaScript">
              	self.location.href = "../abono/erro.php"
             	</script>
                </body>
                </html>';
       		exit;
		} else {
				$sql2="select login, nivel from diario_usuarios where login = '$user'";
		  	   	$rows=consulta_sql($sql2);
				$res = $rows['nivel'];
                if ($res == '2') {
                setcookie('login', $user, 0);
                print'<html>
                <body>
                <SCRIPT LANGUAGE="JavaScript">
              	self.location.href = "../abono/abono_step_2.php"
             	</script>
                </body>
                </html>';
                } else {
			   print'<html>
                <body>
                <SCRIPT LANGUAGE="JavaScript">
              	self.location.href = "../abono/erro.php"
             	</script>
                </body>
                </html>';}
                }
?>
