<?php
  /**
  * FIXME: Inserir a regra de negocio em um arquivo aparte e incluir no arquivo que lista e no que gera PDF
  * 
  */

  require("../../../configs/configuracao.php");
  require("../../../lib/adodb/tohtml.inc.php");
 
  $conn = new connection_factory($param_conn);

  //Selecionando o campo periodo
  if($_POST["periodo1"] != ''){  
	  $periodo = $_POST["periodo1"];
  }
  else{
	  $periodo = $_POST["periodo"];
  }
  
  $curso 		= $_POST["codigo_curso"];
  $aluno 		= $_POST["aluno"];
  $situacao 	= $_POST["aprovacao"]; //1 = aprovado, 2 = reprovado, 3 = aprovado e reprovado
  $turma 		= $_POST["turma"]; 
  
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
 	 
  if ($turma != '') $sql .= " t.turma = '$turma' AND ";
  
  if ($aluno != '') $sql .= "m.ref_pessoa = '$aluno' AND ";

  $sql .= "p.id = m.ref_pessoa AND m.ref_disciplina_ofer = o.id AND o.ref_disciplina = d.id	";

  if ($situacao == '1') $sql .= " AND (m.nota_final >= 60 and m.num_faltas < (d.carga_horaria/100)*25) ";
  
  if ($situacao == '2') $sql .= " AND (m.nota_final < 60 or m.num_faltas > (d.carga_horaria/100)*25) ";

  $sql .= " ORDER BY 1, 2";


  $sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii("Nome (Cód)"));';	
  
  $RsCurso = $conn->Execute("SELECT descricao ||' (' || id || ') ' as \"Curso\" FROM cursos WHERE id = $curso;");
  $info = "<h4>".$RsCurso->fields[0]."</h4>";	

  $RsPeriodo = $conn->Execute("SELECT descricao FROM periodos WHERE id = '$periodo';");
  $DescricaoPeriodo = $RsPeriodo->fields[0];
  
  $Result1 = $conn->Execute($sql);
    
  $num_result = $Result1->RecordCount();
  
  
  $info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Período: </strong> <span>$DescricaoPeriodo</span> <br><br>";

?>
<html>
<head>
<title>Lista de Alunos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
	<div style="width: 760px;">
		<?php 
			/**
			 * FIXME: Inserir cabecalho e carimbo
		 	*/
	
			//header
		?>
		<h2>RELAT&Oacute;RIO DE SITUA&Ccedil;&Atilde;O DE APROVA&Ccedil;&Atilde;O DE ALUNO(S)</h2>
		<?php
			echo $info;
  			rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
		?>
		<br><br>
		<?php //carimbo ?>
	</div>
</body>
</html>
