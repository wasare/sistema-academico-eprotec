<?php
	class clsConfig
	{
		
		private $app_raiz;
		private $app_biblioteca;
		private $app_controle;
		private $app_js;
		private $app_modelo;
		private $app_visao;
		private $app_imagens;
		private $app_estilos;
		
		//configuracoes bd
		private $m_host;
		private $m_usuario;
		private $m_senha;
		private $m_esquema;
		private $m_driver;
	
		function clsConfig()
		{
			$this->app_raiz = "https://dev.cefetbambui.edu.br/desenvolvimento/prato/";
			$this->app_biblioteca = "prato/biblioteca/";
			$this->app_controle = "prato/controle/";
			$this->app_js = "prato/js/";
			$this->app_modelo = "prato/modelo/";
			$this->app_visao = "prato/visao/";
			$this->app_imagens = "prato/visao/imagens/";
			$this->app_estilos = "prato/visao/estilo/";
			
			//dados do banco
			$this->m_driver = 'postgres';
			$this->m_esquema = 'sagu';
			$this->m_host = '192.168.0.234';
			$this->m_senha = 'paort';
			$this->m_usuario = 'usrprato';
		}
		
		public function GetHost()
		{
			return $this->m_host;
		}
		
		public function GetUsuario()
		{
			return $this->m_usuario;
		}
		
		public function GetSenha()
		{
			return $this->m_senha;
		}
		
		public function GetEsquema()
		{
			return $this->m_esquema;
		}
		
		public function GetDriver()
		{
			return $this->m_driver;
		}
		
		public function GetPaginaConfirmacao()
		{
			return "frmConfirmacao.php";
		}
		
		public function GetPaginaPrincipal()
		{
			return "frmInicial.php";
		}
		
		public function GetEmailAdmin()
		{
			return "ciniro@gmail.com";
		}
		
		public function GetImagemSemFoto()
		{
			return "semfoto.jpg";
		}
		
		public function ConfirmaOperacao($volta, $mensagem)
		{
			header('location:'. $this->GetPaginaConfirmacao() . '?pagina='. $volta . '&mensagem='.$mensagem);
		}
		
		public function Logout($redireciona)
		{
			ob_start();
			session_start();
			
			//DESTRÓI AS SESSOES
			$_SESSION['codigo'] = array();
			unset($_SESSION['codigo']);
			
			//REDIRECIONA PARA A TELA DE LOGIN
			if ($redireciona==true)
			{
				Header('Location:' . $this->GetPaginaPrincipal());
			}	
		}
	}
?>
