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
			$this->app_raiz = "conta_academica/";
			$this->app_biblioteca = "conta_academica/biblioteca/";
			$this->app_controle = "conta_academica/controle/";
			$this->app_js = "conta_academica/js/";
			$this->app_modelo = "conta_academica/modelo/";
			$this->app_visao = "conta_academica/visao/";
			$this->app_imagens = "conta_academica/visao/imagens/";
			$this->app_estilos = "conta_academica/visao/estilo/";
			
			//dados do banco
			$this->m_driver = 'postgres';
			$this->m_esquema = 'sagu';
			$this->m_host = 'dados.cefetbambui.edu.br';
			$this->m_senha = '@1srv27';
			$this->m_usuario = 'aluno';

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
			return "webmaster@cefetbambui.edu.br";
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
