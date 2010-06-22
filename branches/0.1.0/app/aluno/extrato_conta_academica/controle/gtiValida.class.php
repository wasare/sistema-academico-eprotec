<?php

class gtiValidacao
{
	
	private $erro;
	private $mensagem;
	
	function gtiValidacao()
	{
		$this->erro = false;
		$this->mensagem = '<script type="text/javascript" language="javascript">alert("';
	}
	
	public function GetErro()
	{
		return $this->erro;
	}
	
	public function SetErro($value)
	{
		$this->erro = $value;
	}
	
	public function GetMensagem()
	{
		return $this->mensagem . '"); history.back();</script>';
	}
	
	public function SetMensagem($value)
	{
		$this->mensagem = $value;
	}
	
	public function AddMensagem($value)
	{
		$this->mensagem .= $value;
	}
	
	public function ValidaTelefone($telefone, $campo)
	{
		$p1 = $telefone[0];
		$ddd = $telefone[1] . $telefone[2];
		$p2 = $telefone[3];
		$num1 = $telefone[4].$telefone[5].$telefone[6].$telefone[7];
		$traco = $telefone[8];
		$num2 = $telefone[9].$telefone[10].$telefone[11].$telefone[12];
		
		if (!(($p1 == "(") and (is_numeric($ddd)==true) and ($p2 == ")") and (is_numeric($num1)==true) and ($traco == "-") and (is_numeric($num2))))
		{
			$this->erro = true;
			$this->mensagem .= '\n '.$campo.' Inválido!';
		}
	}
	
	public function ValidaCampoNumerico($conteudo, $campo)
	{
		if (!(is_numeric($conteudo)))
		{
			$this->erro = true;
			$this->mensagem .= '\n É necessário um número no campo '.$campo.'!';
		}
	}
	
	public function ValidaCampoNumericoInteiro($conteudo, $campo)
	{
		if (!(is_numeric($conteudo)))
		{
			$this->erro = true;
			$this->mensagem .= '\n É necessário um número no campo '.$campo.'!';
		}
	}
	
	public function ValidaCampoRequerido($conteudo, $campo)
	{
		if (trim($conteudo)=="")
		{
			$this->erro = true;
			$this->mensagem .= '\n O campo '.$campo.' é obrigatório!';
		}
	}
	
	public function ValidaComparacao($conteudo, $compara, $campo, $operador)
	{
		if (trim($operador)=="==")
		{
			if (trim($conteudo)==$compara)
			{
				$this->erro = true;
				$this->mensagem .= '\n O campo '.$campo.' não foi preenchido corretamente ou selecionado!';
			}
		}
		else
		{
			if (trim($conteudo)!=$compara)
			{
				$this->erro = true;
				$this->mensagem .= '\n O campo '.$campo.' não foi preenchido corretamente ou selecionado!';
			}
		}
	}
	
	public function ValidaSexo($conteudo)
	{
		if ($conteudo == "--selecione--")
		{
			$this->erro = true;
			$this->mensagem .= '\n Selecione alguma opção no campo sexo!';
		}
	}
	
	public function ValidaCEP($cep)
	{
		if (!((is_numeric($cep)) and (strlen($cep) == 8)))
		{
			$this->erro = true;
			$this->mensagem .= '\n CEP inválido!';
		}
	}
	
	public function ValidaCPF($cpf)
	{
		if (!((is_numeric($cpf)) and (strlen($cpf) == 11)))
		{
			$this->erro = true;
			$this->mensagem .= '\n CPF inválido!';
		}
	}
	
	public function ValidaData($sData)
	{

		setlocale(LC_CTYPE,"pt_BR");
	
		if((trim($sData) == "") OR (strlen($sData) != 10))
		{
			$this->erro = true;
			$this->mensagem .= '\n Data inválida!';			
		}
		else
		{
			if ($sData[2] == "/" )
			{
				$sData = str_replace('/','-',$sData);
			}
			
			list($d,$m,$a) = explode('-',$sData,3);			
			if(!checkdate($m,$d,$a))
			{
				$this->erro = true;
				$this->mensagem .= '\n Data inválida!';
			}
		}
	}
	
	public function ValidaComparacaoData($data1, $data2, $operacao)
	{

		$data1 = strtotime($data1); 
		$data2 = strtotime($data2); 
	
		switch ($operacao)
		{
			//SELEÇÕES DE GRID----------------------------------------------
			case '>':
				if (!($data1 > $data2))
				{
					$this->erro = true;
					$this->mensagem .= '\n A data inicial deve ser maior que a final!';	
				}
			break;
			case '<':
				if (!($data1 < $data2))
				{
					$this->erro = true;
					$this->mensagem .= '\n A data inicial deve ser menor que a final!';	
				}
			break;
			case '==':
				if (!($data1 == $data2))
				{
					$this->erro = true;
					$this->mensagem .= '\n A data inicial deve ser igual a final!';	
				}
			break;
			case '!=':
				if (!($data1 != $data2))
				{
					$this->erro = true;
					$this->mensagem .= '\n A data inicial deve ser diferente da final!';	
				}
			break;
		}	
		
	}

}



?>