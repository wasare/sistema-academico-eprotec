<?php

require_once('../conf/conn_diario.php');


function Gera_Senha(){
	$numero_grande=mt_rand(9999,99999);

	if($numero_grande<0)
		$numero_grande*=(-1);
	
	$num_insc=$numero_grande;

	return $num_insc;
}


///converte o e-mail digitado para minusculas
$mailconsulta = strtolower($_POST['mailconsulta']);

if ($mailconsulta =='') {
               print '<script language=javascript>
               window.alert("N&atilde;o Foi digitado o e-mail !");
               javascript:window.history.back(1);
               </script>';
               break;
} else {


	$sql = "select
         a.login,
         a.senha,
         b.id,
         a.nome_completo,
         b.email
         from diario_usuarios a, pessoas b
         where
         a.id_nome = b.id AND
         b.email = '$mailconsulta';"; //  echo $sql; die;



    $rs = pg_query($dbconnect, $sql);

    $numrows = pg_numrows($rs);
//    echo $sql; die;
    if($numrows == 1 ) {

            $con1 = pg_exec($dbconnect, $sql);

      		while ($linha = pg_fetch_array($con1)) {
                   $endereco = $linha["email"];
                   $nomec = $linha["nome_completo"];
                   $user = $linha["login"];
                   $pass = $linha["senha"];
                   $id = $linha["id"];
      		}

			$endereco = strtolower($endereco);

			$num_rand = Gera_Senha();
	
			$passwnew1 = md5($num_rand);

			$sql1 = "UPDATE diario_usuarios SET senha = '$passwnew1' WHERE id_nome = $id;";
	
			$query1 =  pg_exec($dbconnect, $sql1);

			$msg_assunto = "Usuario e Senha Web Diario - CEFET-BAMBUI";

			$msg_corpo = "Caro usuario " . $nomec . " ,a seu pedido sua senha do Web Diario foi enviada, segue abaixo seu usuario e sua nova senha provisoria:\n\n";
        	$msg_corpo .= "Usuario: " . $user . "\n";
			$msg_corpo .= "Senha: " . $num_rand . "\n\n";
			$msg_corpo .= "Para sua seguranca altere esta senha ao acessar o sistema!"."\n\n";
			$msg_corpo .= "Atenciosamente, " . "\nWeb Diario CEFET-BAMBUI\n\n\n";
			$msg_corpo .= "OBS: Esta eh uma mensagem automatica e nao deve ser respondida, qualquer duvida envie e-mail para wanderson@cefetbambui.edu.br\n\n\n";
			$msg_corpo .= "MENSAGEM PROPOSITALMENTE SEM ACENTOS.";

			echo '<html>
					<head>
						<title>CONFIRMACAO</title>
						<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
					</head>

					<body>';
	
		        	// EMAIL COM A NOVA SESSAO	
				if ( @mail($mailconsulta, $msg_assunto, $msg_corpo, "FROM: diario@cefetbambui.edu.br") ) {

					echo 	'<p>Sr.(a) <strong>'.$nomec.'</strong> a sua senha foi enviada para o e-mail <strong>' . $mailconsulta . '</strong></p>
					<p>&nbsp;</p>
					<p><a href="../index.php">P&aacute;gina Inicial</a></p>';
				}
				else  {
					echo '	<p>Ocorreu um erro ao enviar a nova senha!</p>
						<p>&nbsp;</p>
						<p><a href="../index.php">P&aacute;gina Inicial</a></p>';
				}

					echo '</body>
					</html>';
	
	} else {
               print '<script language=javascript>
               window.alert("O e-mail não foi encontrado em nossa base de dados !");
               javascript:window.history.back(1);
               </script>';
               break;
	}

}

?>
