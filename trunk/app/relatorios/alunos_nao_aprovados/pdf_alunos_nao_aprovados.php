<?php

  require("../../../configs/configuracao.php");
  require("../../../lib/adodb/tohtml.inc.php");
  
  $conn = new connection_factory($param_conn);

  if($_POST["periodo1"] != ''){  
	  $periodo = $_POST["periodo1"];
  }
  else{
	  $periodo = $_POST["periodo"];
  }
  
  $curso      = $_POST["codigo_curso"];
  $aluno      = $_POST["aluno"];
  $situacao   = $_POST["aprovacao"]; //1 = aprovado, 2 = reprovado, 3 = aprovado e reprovado
  $turma      = $_POST["turma"];

  $sql = "
  SELECT 
  	t.turma as \"Turma\", 
  	p.nome || ' (' || m.ref_pessoa || ') ' as \"Nome (Cód)\", 
  	d.descricao_disciplina || ' (' || o.ref_disciplina || '/' || m.ref_disciplina_ofer || ') ' as \"Disciplina (Cód Disc/Diário) \",
  	m.nota_final as \"Nota\", 
  	m.num_faltas || ' (' || d.carga_horaria || ') ' as \"Falta (Carga Horaria)\"
	
  FROM 
  	matricula m, pessoas p, disciplinas_ofer o, disciplinas d, public.contratos t
  
  WHERE
  	m.ref_periodo = '$periodo' AND 
  	m.ref_curso = '$curso' AND 
  	t.ref_curso = m.ref_curso AND 
  	t.ref_pessoa = p.id AND ";
  
  if ($turma != '') {
  	$sql .= " t.turma = '$turma' AND ";
  }
  
  if ($aluno != '') {
  	$sql .= "m.ref_pessoa = '$aluno' AND ";
  }

  $sql .= "p.id = m.ref_pessoa AND m.ref_disciplina_ofer = o.id AND o.ref_disciplina = d.id	";

  if ($situacao == '1') {
  	$sql .= " AND (m.nota_final >= 60 and m.num_faltas < (d.carga_horaria/100)*25) ";
  }
  
  if ($situacao == '2') {
  	$sql .= " AND (m.nota_final < 60 or m.num_faltas > (d.carga_horaria/100)*25) ";
  }

  $sql .= " ORDER BY 1, 2";
  
  $sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii("Nome"));';
	

  $RsCurso = $conn->Execute("SELECT descricao ||' (' || id || ') ' as \"Curso\" FROM cursos WHERE id = $curso;");
  $info = "<strong>" . $RsCurso->fields[0] . "</strong><br />";	

  $RsPeriodo = $conn->Execute("SELECT descricao FROM periodos WHERE id = '$periodo';");
  $DescricaoPeriodo = $RsPeriodo->fields[0];
  
  $Result1 = $conn->Execute($sql);
    
  $num_result = $Result1->RecordCount();
  
  $info .= "Data: " . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "Hora: " . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "Total de Registros: " . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "Período: $DescricaoPeriodo <br><br>";
  
 //html2pdf
  ob_start();  

?>
<link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
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
	<?php 
		/**
		 * FIXME: Inserir cabecalho e carimbo
		 */
	
		//header
	?>
	<h2 style="font-size:16px;">RELAT&Oacute;RIO DE SITUA&Ccedil;&Atilde;O DE APROVA&Ccedil;&Atilde;O DE ALUNO(S)</h2>
	<?php	
  		echo $info;  
  		rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
	?>
	<br>
	<br>
	<?php //carimbo ?>
</page>
<?php
  	$content = ob_get_clean();
  	require_once('../../lib/html2pdf/html2pdf.class.php');
  	$pdf = new HTML2PDF('P','A4','en');
  	$pdf->WriteHTML($content, isset($_GET['vuehtml']));
  	$pdf->Output(); 
?>