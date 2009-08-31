function CarregaToolTip(nome)
  {
      obj = document.getElementById("txtInfo")
      obj.value = "Clique sobre uma das op��es acima para acessar as �reas configur�veis.";
      
      switch (nome)
      {
        case "bt_usuarios":       
            obj.value = "Altera��es, inclus�es e \n dele��es de usu�rios que usam o sistema PRATO.";;
        break;
        case "bt_configuracoes":
            obj.value = "Op��es de configura��o do sistema PRATO.";;
        break;
        case "bt_grupos":
            obj.value = "Defina grupos e descontos personalizados.";;
        break;
        case "bt_relatorios":
            obj.value = "Dados na forma de relat�ris sint�ticos. Acesse e exporte dados do sistema PRATO.";;
        break;
        case "bt_operadores":
            obj.value = "Crie usu�rios para operar o sistema PRATO.";;
        break;
        case "bt_refeicao":
            obj.value = "Cadastro, altera��o e dele��o de refei��es dispostas aos usu�rios.";;
        break;
		case "bt_marmitex":
            obj.value = "�rea para lan�amento de vendas por marmitex.";;
        break;
		case "bt_minhasconsultas":
            obj.value = "Seus relat�rios e consultas prontas personalizados.";;
        break;
      }
  }