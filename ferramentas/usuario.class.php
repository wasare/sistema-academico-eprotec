<?php

class Usuario {

	var $nome_completo;
	var $email;
  	var $nome;
  	var $senha;

  	function getNomeCompleto() {
   		return $this->nome_completo;
  	}
  	
	function getEmail() {
   		return $this->email;
  	}
	
	function getNome() {
   		return $this->nome;
  	}
	
	function getSenha() {
   		return $this->senha;
  	}

  	function Usuario($nome,$senha) {
   		$this->nome  = $nome;
   		$this->senha = md5($senha); //criptografa a senha
  	}

  	function autentica() {
   		//Aqui estara o metodo de acesso ao banco.
   		$nome  = $this->nome;
   		$senha = $this->senha;
   		$query = "SELECT nome, senha FROM usuarios WHERE nome=$nome AND senha=$senha";
   		$resultados = mysql_query($query) or die(mysql_error());
   
   		if (mysql_num_rows($resultados)>0) {
    		$this->geraSessao($this);
    		return true;
   		}
   		else{
    		return false;
   		}
  	}//fim autentica

	function geraSessao($usuario) {
   		session_start();
   		$_SESSION['usuario'] = $usuario;
  	}//fim geraSessao()
}
?>
<?php
 include("usuario.php");
 $nome = $_POST["nome"];
 $senha = $_POST["senha"];
 $usuario = new Usuario($nome,$senha);
 if ($usuario->autentica()) {
  header("location:principal.php");
 }
 else {
  header("location:login.php");
 }
?>
