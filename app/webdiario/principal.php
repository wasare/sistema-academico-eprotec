<?php

session_start();

$_SESSION['nivel'] = 0;
$_SESSION['login'] = 'login';

require_once("webdiario.conf.php");

setcookie ("us", "0", time( )-9999);
setcookie ("login", "0", time( )-9999);

$_SESSION = array();

session_destroy();

$user = $_POST['user'];
$senha = md5($_POST['senha']);
$speriodo = trim($_POST['speriodo']);

//$sql_query = "SELECT id_nome, login, senha, nivel from diario_usuarios where login = '$user' and senha = '$senha'";

$result = diario_sql($user, $senha,$speriodo);

$coordena = '';


if ($result['nivel'] == 1) {
	$coordena = coordena_sql($result['id_nome']);
}


if($result['diario'] == 'semdiario' && $coordena == 0) { // || !is_numeric($speriodo) ) {

	print '<script language=javascript>
               window.alert("Você não possui nenhum diário registrado ou \n autorização para acessar este período!");
               javascript:window.history.back(1);
               </script>';
    die;
}

if($result['diario'] == 'invalido') {


    $ip = $_SERVER["REMOTE_ADDR"];
    $pagina = $_SERVER["PHP_SELF"];
    $status = "LOGIN RECUSADO";
	$usuario = trim($user);
	$sql_store = htmlspecialchars("$usuario");
	$Data = date("Y-m-d");
	$Hora = date("H:i:s");
    $sqllog = "insert into diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) values('$sql_store','$Data','$Hora','$ip','$pagina','$status','$senha')";
    $query1 =  pg_exec($dbconnect, $sqllog);

	print '<html>
                <body>
                <SCRIPT LANGUAGE="JavaScript">
              	self.location.href = "'. $erro .'"
             	</script>
                </body>
                </html>';
       		exit;

} else {

    setcookie ("us", "0", time( )-9999);
    setcookie ("login", "0", time( )-9999);
    setcookie ("us", $_POST['user'], 0);
    setcookie('login', $_POST['user'], 0);

    $ip = $_SERVER["REMOTE_ADDR"];
    $pagina = $_SERVER["PHP_SELF"];
    $status = "LOGIN ACEITO";
	$usuario = trim($user);
	$sql_store = htmlspecialchars("$usuario");
	$Data = date("Y-m-d");
	$Hora = date("H:i:s");
    $sqllog = "insert into diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) values('$sql_store','$Data','$Hora','$ip','$pagina','$status','Senha Valida')";

	$query1 =  pg_exec($dbconnect, $sqllog);

	session_start();

    $_SESSION['nivel'] = $result['nivel'];
    $_SESSION['login'] = $result['login'];
    $_SESSION['id'] = $result['id_nome'];

    $_SESSION['lst_periodo'] = $P["$speriodo"];

	if ($coordena == 1) { 
	
		$_SESSION['cursosc'] = coordena_sql($result['id_nome'],1); 
	}

                if ($_SESSION['nivel'] == 2) {

			   print '<html>
					<head>
					<title>Diario Net</title>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
					</head>
					<frameset cols="135,*" frameborder="NO" border="0" framespacing="0">
 					<frame name="menu" scrolling="NO" noresize src="secretaria/menu_secretaria.php" frameborder="NO" >
					<frame name="principal" src="prin.php" scrolling="AUTO" frameborder="NO">
					</frameset>
					<noframes>
					<body bgcolor="#FFFFFF" text="#000000">
					</body>
					</noframes>
					</html>';
				}
                else {

				print '<html>
   					<head>
   					<title>Diario Net</title>
  					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 					</head>
 					<frameset cols="135,*" frameborder="NO" border="0" framespacing="0">
   					<frame name="menu" scrolling="AUTO" src="menu.php" frameborder="NO" >
		  			<frame name="principal" src="prin.php" scrolling="AUTO" frameborder="NO">
					</frameset>
					<noframes>
					<body bgcolor="#FFFFFF" text="#000000">
					</body>
					</noframes>
					</html>';

			}
 //           }
 }
?>
