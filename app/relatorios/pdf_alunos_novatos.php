<?php

  //ARQUIVO DE CONFIGURACAO E CLASSE ADODB
  header ("Cache-Control: no-cache");
  require("../../lib/common.php");
  require("../../lib/config.php");
  require("../../configuracao.php");
  require("../../lib/adodb/adodb.inc.php");
  require("../../lib/adodb/tohtml.inc.php");
  //require("../../lib/adodb/adodb-pager.inc.php");


  ini_set('display_errors', 0);

  //RECEBENDO OS DADOS DO FORMULARIO --
  $periodo = $_POST["periodo1"];
  $aluno = $_POST["aluno"];
  $curso = $_POST["codigo_curso"];
  $resp_nome = $_POST["resp_nome"];
  $resp_cargo = $_POST["resp_cargo"];
  $turma = $_POST["turma"];





  //MONTANDO A SQL PARA A CONSULTA --
  
  $sql = " SELECT DISTINCT p.id as \"Código\""; 
  
  if (isset($_POST["nome"])) $sql .= ', p.nome as "Nome" '; 

  if (isset($_POST["turma2"])) { 
  
	  $sql .= ', c.turma as "Turma" '; 
	  $condicao_turma = "AND c.ref_pessoa = p.id";
	 // $tabela_contrato = ", public.contratos t";
  }
  
  if ($turma != '') { 
  	
	$condicao_turma .= " AND c.turma = '$turma' ";
	//$tabela_contrato = ", public.contratos t ";
        $info_turma = '&nbsp;&nbsp;-&nbsp;&nbsp;<strong>Turma: </strong> '. $turma;
  }


  //Dados de Filiacao
  if (isset($_POST["pai"])) { 
  
	  $sql .= ', f.pai_nome as "Pai"'; 
	  //$condicao_filiacao = " p.ref_filiacao = f.id AND ";
	  
	  $tabela_filiacao = "LEFT OUTER JOIN filiacao f ON(p.ref_filiacao = f.id)";
	  //$tabela_filiacao = ", public.filiacao f ";
  }
  	
  if (isset($_POST["mae"])) {
  
	  $sql .= ', f.mae_nome as "Mae" '; 
	  //$condicao_filiacao = " p.ref_filiacao = f.id AND ";
	  
	  $tabela_filiacao = "LEFT OUTER JOIN filiacao f ON(p.ref_filiacao = f.id)";
	  //$tabela_filiacao = ", public.filiacao f ";
  }
		
  if (isset($_POST["endereco"])) {
  	$sql .= ', p.rua || \' \' || p.complemento as "Endereço"'; 
  }
  
  if (isset($_POST["bairro"])) {
  	$sql .= ', p.bairro as "Bairro"'; 
  }

  //Dados de Cidade
  if (isset($_POST["cidade"])) {
	  
	  $sql .= ', m.nome || \'-\' || m.ref_estado as "Cidade"'; 
	  $condicao_municipio = " p.ref_cidade = m.id AND ";
	  $tabela_municipio = ", public.aux_cidades m";
  
  }

  if (isset($_POST["cep"])) {
  	$sql .= ', p.cep as "CEP"'; 
  }

  if (isset($_POST["telefone"])) {
	
	  $sql .= ', p.fone_particular as "Tel. Part."
		, p.fone_profissional as "Tel. Prof."
		, p.fone_celular as "Tel. Cel."
		, p.fone_recado as "Tel. Rec."
	  '; 
  
  }
	
  if (isset($_POST["rg"])) $sql .= ', p.rg_numero as "RG"'; 
	
  if (isset($_POST["cpf"])) $sql .= ', p.cod_cpf_cgc as "CPF"'; 
	
  if (isset($_POST["sexo"])) $sql .= ', p.sexo as "Sexo"'; 	
	
  if (isset($_POST["data_nascimento"])) $sql .= ', to_char(p.dt_nascimento, \'DD/MM/YYYY\') as "Data de Nascimento"'; 

  $sql .= " 
  
  FROM 
  pessoas p $tabela_filiacao, contratos c $tabela_municipio 
  WHERE 
  c.ref_periodo_turma = '$periodo' AND ";
	
  if ($curso != '') $sql .= " c.ref_curso = '$curso' AND"; 
  
  //$sql .= $condicao_filiacao; 
  $sql .= $condicao_municipio;
		
  if ($aluno != '')  $sql .= " p.id = '$aluno' AND ";
	
  $sql .= " 
  c.ref_pessoa = p.id  
  $condicao_turma 
  
  ORDER BY 2";
 
  $sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii("Nome"));';
 
  // echo $sql;
  //die();




  
  //Criando a classe de conexão ADODB
  $Conexao = NewADOConnection("postgres");

  //Setando como conexão persistente
  $Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

  
  $RsCurso = $Conexao->Execute("SELECT descricao ||' (' || id || ') ' as \"Curso\" FROM cursos WHERE id = $curso;");
  $info = "<h4>" . $RsCurso->fields[0] . "</h4>";
  
  //Exibindo a descricao do periodo
  $RsPeriodo = $Conexao->Execute("SELECT descricao FROM periodos WHERE id = '$periodo';");
  $DescricaoPeriodo = $RsPeriodo->fields[0];
  
  
  //EXECUTANDO SQL DA CONSULTA PRINCIPAL
  $Result1 = $Conexao->Execute($sql);
    
  //numero de ocorrencias
  $num_result = $Result1->RecordCount();
  
  
  
  //Informacoes de cabecalho
  $info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Período: </strong> <span>$DescricaoPeriodo</span> $info_turma <br><br>";
  
  //Dados de rodape com assinatura
  $rodape = '<span style="font-size: 12px;">' . $resp_nome . "</span><br>";
  $rodape .= '<span style="font-size: 9px;"><strong>' . $resp_cargo . "</strong></span><br>";


 //html2pdf
  ob_start();

?>
<link href="../../Styles/style.css" rel="stylesheet" type="text/css">
<page backtop="10mm" backbottom="10mm" >
<page_header></page_header>
<page_footer>
<table style="width: 700px;">
  <tr>
    <td style="text-align: left; width: 50%">&nbsp;</td>
    <td style="text-align: right; width: 50%">página [[page_cu]]/[[page_nb]]</td>
  </tr>
</table>
</page_footer>
<span style="text-align:center; font-size:12px;">
	<img src="../../images/armasbra.jpg" width="57" height="60"><br />
	MEC-SETEC<br />
	CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
    SETOR DE REGISTROS ESCOLARES
    <br /><br /><br />
</span>
<h2 style="font-size:16px;">LISTA DE ALUNOS NOVATOS</h2>
<?php
  //Insere os dados de Cabecalho
  echo $info;  
  
  //Gera a Tabela do Relatorio
  rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
?>
<br>
<br>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">__________________________________________</td>
  </tr>
  <tr>
    <td align="center"><?php echo $rodape; ?></td>
  </tr>
</table>
</page>
<?php

  	$content = ob_get_clean();
  	require_once('../../lib/html2pdf/html2pdf.class.php');
  	$pdf = new HTML2PDF('P','A4','en');
  	$pdf->WriteHTML($content, isset($_GET['vuehtml']));
  	$pdf->Output(); 

?>
