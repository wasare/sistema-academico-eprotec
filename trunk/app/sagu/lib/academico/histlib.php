<?
class Informacoes
{
  var $carga_horaria;
  var $creditos;
  var $grau;
  var $ref_periodo;
  var $aproveit;
  var $substit;
};

function RetornaAproveit($ref_contrato, $ref_disciplina, $conn)
{

  $sql = " select carga_horaria_aprov, " .
      	 "   	  creditos_aprov, " .
    	 "  	  nota_final, " .
    	 "        ref_periodo, " .
         "        obs_aproveitamento, " .
    	 "        ref_disciplina_subst, " .
    	 "        descricao_disciplina(ref_disciplina_subst), " .
         "        fl_exibe_displ_hist, " .
    	 "        conceito, " .
    	 "        fl_liberado, " .
         "        'true' " .
    	 " from matricula " .
    	 " where ref_contrato = $ref_contrato and " .
    	 "       ref_disciplina = $ref_disciplina and " .
    	 "       ( (dt_cancelamento is null and nota_final >= get_media_final(ref_periodo)) or " .
    	 "        (dt_cancelamento is null and nota_final = 0) ) ; ";//and " .
    	 //"       fl_liberado <> '2' and " .
    	 //"       fl_liberado <> '1' ; ";
    	 
   //echo $sql;
   //exit;

// O padrão de sem nota é zero ao inves de null por isso nao apareciam nos historicos as disciplinas do periodo atual

  $query = $conn->CreateQuery($sql);

  SaguAssert($query,"Não foi possível consultar as notas/créditos da disciplina $ref_disciplina");

  if ( @$query->MoveNext() )
  {
    $obj = $query->GetRowValues();
  }
  else
    $obj = '';

  $query->Close();

  return $obj;
  
  //print_r($obj);
}


// Imprimir o texto ate o limite de caracteres
Function ImprimeTexto($myfile_ps, $descrtexto, $tam, $col, $col1, $lin, $dif, $fonte, $tamanho)
{
  $descrtexto = ereg_replace("-","\-", "$descrtexto");
  while (strlen($descrtexto)>0) 
  {
    if (strlen($descrtexto) > $tam) 
    {
      
      $textoimprimir = substr($descrtexto, 0, $tam);
      $textoimprimir = strrev($textoimprimir);
      $textoimprimir = strstr($textoimprimir, " ");
      $textoimprimir = strrev($textoimprimir);
      $tam_result    = strlen($textoimprimir);
      $textoimprimir = substr($descrtexto, 0, $tam_result);

      PS_show_xy_font($myfile_ps, $textoimprimir, $col, $lin, $fonte, $tamanho);
      $descrtexto = substr($descrtexto, $tam_result, strlen($descrtexto));
      $col = $col1;
      $lin = $lin - $dif;
    }
    else {
      PS_show_xy_font($myfile_ps, $descrtexto, $col, $lin, $fonte, $tamanho);
      $descrtexto = '';
    }
  }
  return($lin);
}

// Imprimir o texto Justificado
Function ImprimeTexto2($myfile_ps, $descrtexto, $tam, $col, $col1, $lin, $dif, $fonte, $tamanho)
{
  $descrtexto = ereg_replace("-","\-", "$descrtexto");
  
  $paragrafo = 0;
  
  if ($col != $col1)
  {
     $paragrafo = round((($col - $col1)/7.2), 0); // Aproximadamente 7.2 pixels cada caracter...
  
  }
  while (strlen($descrtexto)>0) 
  {
    if (strlen($descrtexto) > $tam) 
    {
      if ($paragrafo != 0)
      {
        $tamx = $tam - $paragrafo;
        $textoimprimir = substr($descrtexto, 0, $tamx);
        $paragrafo = 0;
      }
      else
      {
        $tamx = $tam;
        $textoimprimir = substr($descrtexto, 0, $tamx);
      }
      
      $textoimprimir = strrev($textoimprimir);
      $textoimprimir = strstr($textoimprimir, " ");
      $textoimprimir = strrev($textoimprimir);
      $tam_result    = strlen($textoimprimir);
      $textoimprimir = substr($descrtexto, 0, $tam_result);

      $textoimprimir = trim($textoimprimir);

      $textoimprimir = str_replace(' ','  ',"$textoimprimir");

      if (strlen($textoimprimir) < $tamx)
      {
        $textoimprimir = str_replace('  ','   ',"$textoimprimir");
      }
      
      while (strlen($textoimprimir) > $tamx)
      {
          $textoimprimir = substr($textoimprimir, 0, strpos($textoimprimir, "  ")) . 
                           substr($textoimprimir, strpos($textoimprimir, "  ") + 1, strlen($textoimprimir));
      }
      
      PS_show_xy_font($myfile_ps, $textoimprimir, $col, $lin, $fonte, $tamanho);
      $descrtexto = substr($descrtexto, $tam_result, strlen($descrtexto));
      $col = $col1;
      $lin = $lin - $dif;
    }
    else {
      PS_show_xy_font($myfile_ps, $descrtexto, $col, $lin, $fonte, $tamanho);
      $descrtexto = '';
    }
  }
  return($lin);
}

function RetornaAproveit1($ref_pessoa, $ref_disciplina)
{
  $conn = new Connection;
  $conn->Open();

  $sql = " select carga_horaria_aprov, " .
      	 "  	  creditos_aprov, " .
    	 "  	  nota_final, " .
    	 "  	  ref_periodo, " .
    	 "        obs_aproveitamento, " .
    	 "        ref_disciplina_subst, " .
    	 "        fl_exibe_displ_hist, " .
    	 "        conceito " .
    	 " from matricula " .
    	 " where ref_pessoa = $ref_pessoa and " .
    	 "       ref_disciplina = $ref_disciplina and " .
    	 "       (dt_cancelamento is null or nota_final >= get_media_final(ref_periodo))";
    
  $query = $conn->CreateQuery($sql);

  SaguAssert($query,"Não foi possível consultar as notas/creditos da disciplina $ref_disciplina");

  if ( @$query->MoveNext() )
  {
    $obj = $query->GetRowValues();
  }
  else
    $obj = '';

  $query->Close();
  $conn->Close();

  return $obj;
}



Function VerificaValores($dt_nascimento, $rg_orgao, $rg_numero, $rg_data)
{
  if (empty($dt_nascimento)) {
    echo('===> ATENÇÃO:  DATA DE NASCIMENTO do aluno não está Cadastrada. <br>');
  }
  if (empty($rg_numero)) echo('===> ATENÇÃO:  Nº RG do aluno não está Cadastrado. <br>');
  if (empty($rg_orgao)) echo('===> ATENÇÃO:  ORGÃO do RG do aluno não está Cadastrado. <br>');
  if (empty($rg_data)) echo('===> ATENÇÃO:  DATA RG do aluno não está Cadastrada. <br>');
}


Function NomeDisciplina($ref_disciplina)
{
  $conn = new Connection;
  $conn->Open();

  $sql = "select descricao_extenso from disciplinas where id = $ref_disciplina";

  $query = $conn->CreateQuery($sql);

  SaguAssert($query,"Não foi possível encontrar o nome da disciplina optativa $ref_disciplina");

  if ( @$query->MoveNext() )
  {
    list ($obj) = $query->GetRowValues();
  }
  else
    $obj = '';

  $query->Close();
  $conn->Close();

  return $obj;

}

function is_in_array($needle,$haystack, $substit) {
  $needleFound = false;
  $returnValue = ''; //false;
  reset($haystack);
  while ((list($key,$value) = each($haystack)) && !$needleFound) {
    if ($needle == $value) {
      $needleFound = true;

      $conn = new Connection;
      $conn->Open();
      $sql = "select descricao_extenso from disciplinas where id = $substit";
      $query = $conn->CreateQuery($sql);
      SaguAssert($query,"Não foi possível encontrar o nome da disciplina optativa $ref_disciplina");
      if ($query->MoveNext())
        list($returnValue) = $query->GetRowValues();

      $query->Close();
      $conn->Close();

      //$returnValue = $key;
    }
  }
return $returnValue;

}

function is_in_array1($needle,$haystack)
{
  $needleFound = false;
  $returnValue = '0';
  reset($haystack);
  while ((list($key,$value) = each($haystack)) && !$needleFound) {
    if ($needle == $value) {
      $needleFound = true;
      $returnValue = '1'; 
    }
  }
  return $returnValue;
}

// -------------------------------------------
// Purpose: Retorna data no formato DD/MM/AAAA
// -------------------------------------------
function DDMMAAAA($data) {
  if($data) {
    $NovaData = substr($data, 3, 2) . '/' . substr($data, 0, 2) . '/' . substr($data, 6, 4);
    return $NovaData;
  }
  else
    return $data;
}

function DIAEXTENSO($data) {
  if($data) {
    $Dia = substr($data, 0, 2);
    $Mes = substr($data, 3, 2);
    $Ano = substr($data, 6, 4);
 
    switch($Dia) {
      case "01":
        $Extenso = "PRIMEIRO ";
        break;
      case "02":
        $Extenso = "DOIS ";
        break;
       case "03":
        $Extenso = "TRÊS ";
        break;
      case "04":
        $Extenso = "QUATRO ";
        break;
      case "05":
        $Extenso = "CINCO ";
        break;
      case "06":
        $Extenso = "SEIS ";
        break;
      case "07":
        $Extenso = "SETE ";
        break;
      case "08":
        $Extenso = "OITO ";
        break;
      case "09":
        $Extenso = "NOVE ";
        break;
      case "10":
        $Extenso = "DEZ ";
        break;
      case "11":
        $Extenso = "ONZE ";
        break;
      case "12":
        $Extenso = "DOZE ";
        break;
      case "13":
        $Extenso = "TREZE ";
        break;
      case "14":
        $Extenso = "QUATORZE ";
        break;
      case "15":
        $Extenso = "QUINZE ";
        break;
      case "16":
        $Extenso = "DEZESSEIS ";
        break;
      case "17":
        $Extenso = "DEZESSETE ";
        break;
      case "18":
        $Extenso = "DEZOITO ";
        break;
      case "19":
        $Extenso = "DEZENOVE ";
        break;
      case "20":
        $Extenso = "VINTE ";
        break;
      case "21":
        $Extenso = "VINTE E UM ";
        break;
      case "22":
        $Extenso = "VINTE E DOIS ";
        break;
      case "23":
        $Extenso = "VINTE E TRÊS ";
        break;
      case "24":
        $Extenso = "VINTE E QUATRO ";
        break;
      case "25":
        $Extenso = "VINTE E CINCO ";
        break;
      case "26":
        $Extenso = "VINTE E SEIS ";
        break;
      case "27":
        $Extenso = "VINTE E SETE ";
        break;
      case "28":
        $Extenso = "VINTE E OITO ";
        break;
      case "29":
        $Extenso = "VINTE E NOVE ";
        break;
      case "30":
        $Extenso = "TRINTA ";
        break;
      case "31":
        $Extenso = "TRINTA E UM ";
        break;
      default:
        $Extenso = "DIA INVÁLIDO. INFORME DD/MM/AAAA ";
        break;
    }  

    switch($Mes) {
      case "01":
        $Extenso = $Extenso . "DE JANEIRO DE ";
        break;
      case "02":
        $Extenso = $Extenso . "DE FEVEREIRO DE ";
        break;
      case "03":
        $Extenso = $Extenso . "DE MARÇO DE ";
        break;
      case "04":
        $Extenso = $Extenso . "DE ABRIL DE ";
        break;
      case "05":
        $Extenso = $Extenso . "DE MAIO DE ";
        break;
      case "06":
        $Extenso = $Extenso . "DE JUNHO DE ";
        break;
      case "07":
        $Extenso = $Extenso . "DE JULHO DE ";
        break;
      case "08":
        $Extenso = $Extenso . "DE AGOSTO DE ";
        break;
      case "09":
        $Extenso = $Extenso . "DE SETEMBRO DE ";
        break;
      case "10":
        $Extenso = $Extenso . "DE OUTUBRO DE ";
        break;
      case "11":
        $Extenso = $Extenso . "DE NOVEMBRO DE ";
        break;
      case "12":
        $Extenso = $Extenso . "DE DEZEMBRO DE ";
        break;
      default:
        $Extenso = $Extenso . "MÊS INVÁLIDO. INFORME DD/MM/AAAA ";
        break;
    }

    switch($Ano) {
      case "1998":
        $Extenso = $Extenso . "UM MIL NOVECENTOS E NOVENTA E OITO";
        break;
      case "1999":
        $Extenso = $Extenso . "UM MIL NOVECENTOS E NOVENTA E NOVE";
        break;
      case "2000":
        $Extenso = $Extenso . "DOIS MIL";
        break;
      case "2001":
        $Extenso = $Extenso . "DOIS MIL E UM";
        break;
      case "2002":
        $Extenso = $Extenso . "DOIS MIL E DOIS";
        break;
      case "2003":
        $Extenso = $Extenso . "DOIS MIL E TRÊS";
        break;
      case "2004":
        $Extenso = $Extenso . "DOIS MIL E QUATRO";
        break;
	case "2005":
        $Extenso = $Extenso . "DOIS MIL E CINCO";
        break;
	case "2006":
        $Extenso = $Extenso . "DOIS MIL E SEIS";
        break;
	case "2007":
        $Extenso = $Extenso . "DOIS MIL E SETE";
        break;
	case "2008":
        $Extenso = $Extenso . "DOIS MIL E OITO";
        break;
      default:
        $Extenso = $Extenso . "ANO INVÁLIDO OU NÃO SUPORTADO. INFORME DD/MM/AAAA ";
        break;
    }
    return $Extenso;
  }
  else
    return $data;
}
 
?>
<HTML>
<BODY></BODY>
</HTML>
