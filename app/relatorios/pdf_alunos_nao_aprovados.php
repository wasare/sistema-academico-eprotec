<?php

  
  //ARQUIVO DE CONFIGURACAO E CLASSE ADODB
  header ("Cache-Control: no-cache");
  require("../../lib/common.php");
  require("../../lib/config.php");
  require("../../configuracao.php");
  require("../../lib/adodb/adodb.inc.php");
  require("../../lib/adodb/tohtml.inc.php");
  

  //Selecionando o campo periodo
  if($_POST["periodo1"] != ''){  
	  $periodo = $_POST["periodo1"];
  }
  else{
	  $periodo = $_POST["periodo"];
  }
  
  $curso = $_POST["codigo_curso"];
  $aluno = $_POST["aluno"];
  $situacao = $_POST["aprovacao"]; //1 = aprovado, 2 = reprovado, 3 = aprovado e reprovado
  $turma = $_POST["turma"];
  $resp_nome = $_POST["resp_nome"];
  $resp_cargo = $_POST["resp_cargo"];
  
  

  //MONTANDO A SQL PARA CONSULTA --
  $sql = " SELECT 
  t.turma as \"Turma\", 
  p.nome || ' (' || m.ref_pessoa || ') ' as \"Nome (Cód)\", 
  d.descricao_disciplina || ' (' || o.ref_disciplina || '/' || m.ref_disciplina_ofer || ') ' as \"Disciplina (Cód Disc/Diário) \",
  m.nota_final as \"Nota\", 
  m.num_faltas || ' (' || d.carga_horaria || ') ' as \"Falta (Carga Horaria)\"
	
  FROM matricula m, pessoas p, disciplinas_ofer o, disciplinas d, public.contratos t
  
  WHERE
  m.ref_periodo = '$periodo' AND 
  m.ref_curso = '$curso' AND 
  t.ref_curso = m.ref_curso AND 
  t.ref_pessoa = p.id AND ";
  
  if ($turma != '') $sql .= " t.turma = '$turma' AND ";
  
  if ($aluno != '') $sql .= "m.ref_pessoa = '$aluno' AND ";

  $sql .= "p.id = m.ref_pessoa AND m.ref_disciplina_ofer = o.id AND o.ref_disciplina = d.id	";

  if ($situacao == '1') $sql .= " AND (m.nota_final >= 60 and m.num_faltas < (d.carga_horaria/100)*25) ";
  
  if ($situacao == '2') $sql .= " AND (m.nota_final < 60 or m.num_faltas > (d.carga_horaria/100)*25) ";

  $sql .= " ORDER BY 1, 2";

  $sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii("Nome"));';
	
  //echo $sql;
  //die();


  
  //Criando a classe de conexão
  $Conexao = NewADOConnection("postgres");
  
  //Setando como conexão persistente
  $Conexao->PConnect("host=$host dbname=$database user=$user password=$password");
  
  
  //Exibindo a descricao do curso
  $RsCurso = $Conexao->Execute("SELECT descricao ||' (' || id || ') ' as \"Curso\" FROM cursos WHERE id = $curso;");
  $info = "<strong>" . $RsCurso->fields[0] . "</strong><br />";	

  //Exibindo a descricao do periodo
  $RsPeriodo = $Conexao->Execute("SELECT descricao FROM periodos WHERE id = '$periodo';");
  $DescricaoPeriodo = $RsPeriodo->fields[0];
  
  //EXECUTANDO SQL DA CONSULTA PRINCIPAL
  $Result1 = $Conexao->Execute($sql);
    
  //numero de ocorrencias
  $num_result = $Result1->RecordCount();
  
  
  //Informacoes de cabecalho
  $info .= "Data: " . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "Hora: " . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "Total de Registros: " . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "Período: $DescricaoPeriodo <br><br>";
  
  //Dados de rodape com assinatura
  $rodape = '<span style="font-size: 12px;">' . $resp_nome . "</span><br>";
  $rodape .= '<span style="font-size: 9px;"><strong>' . $resp_cargo . "</strong></span><br>";


 //html2pdf
  ob_start();  

?>
<link href="file://///192.168.0.17/desenvolvimento/Styles/style.css" rel="stylesheet" type="text/css">
<page backtop="10mm" backbottom="10mm" >
<page_header></page_header>
<page_footer>
<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
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
<h2 style="font-size:16px;">RELAT&Oacute;RIO DE SITUA&Ccedil;&Atilde;O DE APROVA&Ccedil;&Atilde;O DE ALUNO(S)</h2>
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
