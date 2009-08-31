function CarregaToolTip(nome)
  {
      obj = document.getElementById("txtInfo")
      obj.value = "Clique sobre uma das opções acima para acessar as áreas configuráveis.";
      
      switch (nome)
      {
        case "bt_usuarios":       
            obj.value = "Alterações, inclusões e \n deleções de usuários que usam o sistema PRATO.";;
        break;
        case "bt_configuracoes":
            obj.value = "Opções de configuração do sistema PRATO.";;
        break;
        case "bt_grupos":
            obj.value = "Defina grupos e descontos personalizados.";;
        break;
        case "bt_relatorios":
            obj.value = "Dados na forma de relatóris sintéticos. Acesse e exporte dados do sistema PRATO.";;
        break;
        case "bt_operadores":
            obj.value = "Crie usuários para operar o sistema PRATO.";;
        break;
        case "bt_refeicao":
            obj.value = "Cadastro, alteração e deleção de refeições dispostas aos usuários.";;
        break;
		case "bt_marmitex":
            obj.value = "Área para lançamento de vendas por marmitex.";;
        break;
		case "bt_minhasconsultas":
            obj.value = "Seus relatórios e consultas prontas personalizados.";;
        break;
      }
  }