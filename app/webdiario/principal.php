<?php

session_start();

$_SESSION['nivel'] = 0;
$_SESSION['login'] = 'login';

include("webdiario.conf.php");


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
              	self.location.href = "erro.php"
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


    /*
				$sql2 = "SELECT id_nome, login, nivel from diario_usuarios where login = '$user';";

                $res = consulta_sql($sql2);

				if(!is_string($res))
				{
					session_start();

    				while($linha = pg_fetch_array($res))
    				{
						$_SESSION['nivel'] = $linha['nivel'];
        		        $_SESSION['login'] = $linha['login'];
		                $_SESSION['id'] = $linha['id_nome'];
    				}

					//print_r($P);die;

					$_SESSION['lst_periodo'] = $P["$speriodo"];

					//print_r($_SESSION); die;

				}
				else
				{
    				echo $res;
    				exit;
				}
	*/
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

/*
					if ($_SESSION['nivel'] == 3) {

						// VERIFICA SE EXISTE ALGUMA COORDENACAO ATUALMENTE
						$sql1 = 'SELECT DISTINCT ref_professor
									FROM coordenadores
                        WHERE ref_professor = \''.$_SESSION['id'].'\';';

						$qry1 = consulta_sql($sql1);

						if(is_string($qry1)) {
							echo $qry1;
							$ret = false;
							exit;
						}
						else {
							if(pg_numrows($qry1) == 0) {

								    print '<script language=javascript>
               window.alert("Atualmente você não possui nenhuma coordenação de curso!");                javascript:window.history.back(1);
               </script>';
    							die;
							}
							else {


								print '<html>
                    <head>
                    <title>Diario Net</title>
                    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                    </head>
                    <frameset rows="15,*" cols="135,*" frameborder="NO" border="0" framespacing="0">                    <frame name="top" scrolling="NO" noresize src="top.php" frameborder="NO" >
                    <frame name="logo" scrolling="NO" noresize src="logo.php" frameborder="NO" >
                    <frame name="menu" scrolling="AUTO" src="coordenacao/menu_coordenacao.php" frameborder="NO" >
                    <frame name="principal" src="prin.php" scrolling="AUTO" frameborder="NO">
                    </frameset>
                    <noframes>
                    <body bgcolor="#FFFFFF" text="#000000">
                    </body>
                    </noframes>
                    </html>';
							}
						}
					}
					else {

*/
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
