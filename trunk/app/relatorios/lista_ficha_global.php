<?php 

require("../../lib/common.php");
require("../../lib/config.php");

require("../sagu/lib/InvData.php3");
require("../sagu/lib/GetPessoaNome.php3"); 
require("../sagu/lib/StatusDisciplina.php3"); 

require("../../lib/properties.php"); 


$periodo_id = $_POST['periodo'];
$pessoa_id =  $_POST['pessoa'];

$properties->Set('periodo',$periodo_id);
$properties->Set('pessoa',$pessoa_id);
$properties->Save();

CheckFormParameters(array ("pessoa"));
$nome_aluno = GetPessoaNome($pessoa_id, true);


$filtro_periodo1 = '';
$filtro_periodo2 = '';

if(!empty($periodo_id)) {

  $filtro_periodo1 = " A.ref_periodo = '$periodo_id' "; 
  $filtro_periodo2 = " A.ref_periodo <> '$periodo_id' AND B.ref_periodo <> '$periodo_id' AND ";

}


?>
<html>
<head>
<link href="../css/busca.css" rel="stylesheet" type="text/css">
<script language="JavaScript">

function regime_especial(ender){
  window.open(ender,'Consulta','resizable=yes, toolbar=no,width=600,height=300,scrollbars=yes');
}

function consulta_generica(ender){
  window.open(ender,'Consulta','resizable=yes, toolbar=no,width=600,height=300,scrollbars=yes');
}

function AproveitaDisciplina(ref_pessoa,ref_disciplina,ref_curso,ref_matricula,nota_final,conceito_final){
 url = '/academico/aproveitamento_int.phtml?ref_pessoa=' + ref_pessoa +
       '&ref_disciplina_subst=' + ref_disciplina +
       '&ref_curso_subst=' + ref_curso +
       '&ref_matricula=' + ref_matricula +
       '&nota_final=' + nota_final +
       '&conceito_final=' + conceito_final;

  if (ref_disciplina!='')
  {
    if (confirm("Deseja Aproveitar Disciplina ?"))
    {
        location = url;
    }
    else
    {
      alert("Aproveitamento Cancelado.");
    }
  }
  else
  {
    location = url;
  }
}
</script>
<?php

function Mostra_Matricula() {

	global $pessoa_id, $filtro_periodo1;

	$conn = new Connection;
	$conn->Open();

	$sql = " SELECT " .
	" 		  A.ref_disciplina, " .
	"        descricao_disciplina(B.ref_disciplina)," .
	"        A.nota_final, " .
	"        get_dia_semana_abrv(dia_disciplina_ofer_todos(B.id)), " .
	"        num_sala_disciplina_ofer_todos(B.id), " .
	"        B.ref_disciplina,  " .
	"        A.dt_cancelamento is null, " .
	"        A.ref_campus, " .
	"	      A.fl_liberado, " .
	"	      C.num_creditos, " .
	"        turno_disciplina_ofer_todos(B.id), " .
	"        A.conceito, " .
	"        A.ref_periodo, " .
	"        professor_disciplina_ofer(B.id), " .
	"        B.id, " .
	"        A.fl_internet, " .
	"        A.status_disciplina, " .
	"        trim(A.obs_aproveitamento), " .
	"        A.ref_curso," .
    "        A.ref_motivo_matricula" .
	" FROM matricula A, disciplinas_ofer B, disciplinas C" .
	" WHERE A.ref_disciplina_ofer = B.id AND " .
	"       A.ref_disciplina = C.id AND " .
	"       A.ref_pessoa = '$pessoa_id' AND " .
    $filtro_periodo1 .
	" ORDER BY dia_disciplina_ofer_todos(B.id) ";

	$query = $conn->CreateQuery($sql);

	$n = $query->GetColumnCount();

	echo ("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabela_relatorio\">");

	$i = 1;

	// cores fundo
	$bg0 = "#000000";
	$bg1 = "#EEEEFF";
	$bg2 = "#FFFFEE";

	while ($query->MoveNext()) {
		list ($id, $desc_disciplina, $nota_final, $dia_semana, $num_sala, $ref_disciplina, $dt_cancelamento, $ref_campus, $fl_liberado, $num_creditos, $turno, $conceito, $ref_periodo, $professor, $ref_disciplina_ofer, $fl_internet, $status_disciplina, $obs_aproveitamento, $ref_curso, $ref_motivo_matricula) = $query->GetRowValues();

		$status = '&nbsp;';

		$status = StatusDisciplina($dt_cancelamento, $nota_final, $fl_liberado, $conceito, $ref_periodo, $conn);

		if (($conceito != 'Desis') && ($conceito != 'Disp.') && ($conceito != '')) {
			$nota_final = $conceito;
		} else {
			$nota_final = sprintf("%.2f", $nota_final);
		}

		$num_creditos = sprintf("%.2f", $num_creditos);

		if ($i == 1) {

			echo ("<tr>\n");
			echo ("<th width=\"8%\">Cód.</td>");
			echo ("<th width=\"40%\">Disciplina/Professor</td>");
			//echo ("<th width=\"5%\" align=\"center\">Tur</td>");
			echo ("<th width=\"8%\" align=\"center\">Nota</td>");
			//echo ("<th width=\"9%\" align=\"center\">Dia</td>");
			echo ("<th width=\"9%\" align=\"center\">Situa&ccedil;&atilde;o</td>");
			//echo ("<th width=\"7%\" align=\"center\">Créd.</td>");
			//echo ("<th width=\"8%\" align=\"center\">Sala</td>");
			echo ("<th width=\"3%\" align=\"center\">S</td>");
			echo ("<th width=\"3%\" align=\"center\">M</td>");
			echo ("</tr>");
		}

		if ($fl_internet == 't') {
			$fl_internet = "<img src=\"../sagu/images/internet.gif\" alt=\"Matrícula feita pela Internet\" title=\"Matrícula feita pela Internet\">";
		} else {
			$fl_internet = "<img src=\"../sagu/images/normal.gif\" alt=\"Matrícula feita na sede\" title=\"Matrícula feita na sede\">";
		}

		if ($status_disciplina == 't') {
			$status_disciplina = "<img src=\"../sagu/images/autorizado.gif\" alt=\"Matrícula com desbloqueio\" title=\"Matrícula com desbloqueio\">";
		} else {
			$status_disciplina = "<img src=\"../sagu/images/liberado.gif\" alt=\"Matrícula sem desbloqueio\" title=\"Matrícula sem desbloqueio\">";
		}

		if ($dt_cancelamento == 't') {
			$cor = 'blue';
			$title = 'Matrícula Efetivada';
			$cancelada = "<font color=$cor alt=\"$title\" title=\"$title\"><b>&nbsp;&nbsp;&nbsp;</b></font>";
		} else {
			$cor = 'red';
			$title = 'Matrícula Cancelada';
			$cancelada = "<font color=$cor alt=\"$title\" title=\"$title\"><b>[C]</b></font>";
		}

		if ($i % 2) {
			$fg = $fg1;
			$bg = $bg1;
		} else {
			$fg = $fg2;
			$bg = $bg2;
		}

		echo ("<tr bgcolor=\"$bg\">\n");
		echo ("<td width=\"8%\">$ref_disciplina</td>");
		echo ("<td width=\"36%\">");

        // APROVEITAMENTO DE ESTUDOS 2
        // CERTIFICACAO DE EXPERIENCIAS 3
        // EDUCACAO FISICA 4
        switch ($ref_motivo_matricula) {
            case 0:
                echo '<img src="../../images/check_nossa.gif" alt="Cursada na institui&ccedil;&atilde;o" title="Cursada na institui&ccedil;&atilde;o" />';
                break;
            case 2:
				echo '<img src="../../images/check_aproveitamento_estudos.gif" alt="Aproveitamento de Estudos" title="Aproveitamento de Estudos" />';
                break;
            case 3:
			    echo '<img src="../../images/check_certificacao_experiencia.gif" alt="Certificação de experi&ecirc;ncia" title="Certificação de experi&ecirc;ncia" />';
                break;
            case 4:
                echo '<img src="../../images/check_educacao_fisica.gif" alt="Dispensado de Educa&ccedil;&atilde;o f&iacute;sica" />';
                break;
        }

/*
		if ($obs_aproveitamento != '') {
			echo ("<img src=\"../../sagu/images/checkapr.gif\" alt=\"Disciplina Aproveitada\" title=\"Disciplina Aproveitada\">");
		} else {
			$sql = "select ref_campus from contratos where ref_pessoa = $pessoa and ref_curso = $ref_curso";
			$query22 = $conn->CreateQuery($sql);
			$query22->MoveNext();
			$ref_campus_aux = $query22->GetValue(1);

			if ($ref_campus != $ref_campus_aux) {
				echo ("<img src=\"../../sagu/images/checkoff.gif\" alt=\"Fora da sede\" title=\"Fora da sede\">");
			} else {
				echo ("<img src=\"../../sagu/images/checkon.gif\" alt=\"Na sede\" title=\"Na sede\">");
			}
		}
*/
		if (!is_null($professor)) {
			$professor = "(<i>$professor</i>)";
		}

		echo ("$cancelada $desc_disciplina $professor</td>");
		//echo ("<td width=\"5%\" align=\"center\">$turno</td>");
		echo ("<td width=\"8%\" align=\"center\">$nota_final</td>");
		//echo ("<td width=\"9%\" align=\"center\">$dia_semana&nbsp;</td>");
		echo ("<td width=\"9%\" align=\"center\">$status</td>");
		//echo ("<td width=\"7%\" align=\"center\">$num_creditos&nbsp;</td>");
		//echo ("<td width=\"8%\" align=\"center\">$num_sala&nbsp;</td>");
		echo ("<td width=\"3%\" align=\"center\">$status_disciplina</td>");
		echo ("<td width=\"3%\" align=\"center\">$fl_internet</td>");
		echo ("</tr>");

		$i++;

	}

	echo "</table>";
	$query->Close();
	$conn->Close();
}

function Mostra_Matricula_Geral() {

	global $pessoa_id, $filtro_periodo2;

	$conn = new Connection;
	$conn->Open();

	$sql = " SELECT " .
	"		  A.ref_disciplina, " .
	"        get_curso_abrv(A.ref_curso), " .
	"        descricao_disciplina(B.ref_disciplina), " .
	"        A.nota_final,  " .
	"        get_dia_semana_abrv(dia_disciplina_ofer_todos(B.id)), " .
	"        num_sala_disciplina_ofer_todos(B.id), " .
	"        B.ref_disciplina,  " .
	"        A.dt_cancelamento is null, " .
	"        A.ref_campus, " .
	"        D.ref_campus, " .
	"        A.fl_liberado, " .
	"        C.num_creditos, " .
	"        A.conceito, " .
	"        A.ref_periodo, " .
	"        professor_disciplina_ofer(B.id), A.ref_curso, " .
	"        A.status_disciplina, " .
	"        A.fl_internet, " .
	"        trim(A.obs_aproveitamento), " .
    "        A.ref_motivo_matricula " .
	" FROM matricula A, disciplinas_ofer B, disciplinas C, contratos D " .
	" WHERE B.id = A.ref_disciplina_ofer AND " .
	"       C.id = A.ref_disciplina AND " .
	"       A.ref_contrato = D.id AND " .
	$filtro_periodo2 .
	"       A.ref_pessoa = '$pessoa_id' " .
	" ORDER BY A.ref_periodo, dia_disciplina_ofer_todos(B.id) ";

	$query = $conn->CreateQuery($sql);

	$n = $query->GetColumnCount();

	echo ("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabela_relatorio\">");

	$i = 1;

	// cores fundo
	$bg0 = "#000000";
	$bg1 = "#EEEEFF";
	$bg2 = "#FFFFEE";

	while ($query->MoveNext()) {
		list ($id, $curso_desc, $desc_disciplina, $nota_final, $dia_semana, $num_sala, $ref_disciplina, $dt_cancelamento, $ref_campus, $ref_campus_contrato, $fl_liberado, $num_creditos, $conceito, $ref_periodo, $professor, $ref_curso, $status_disciplina, $fl_internet, $obs_aproveitamento, $ref_motivo_matricula) = $query->GetRowValues();

		$curso_desc = "$ref_curso/$ref_campus_contrato - $curso_desc";

		$status = '&nbsp;';

		$status = StatusDisciplina($dt_cancelamento, $nota_final, $fl_liberado, $conceito, $ref_periodo, $conn);

		if (($conceito != 'Desis') && ($conceito != 'Disp.') && ($conceito != '')) {
			$nota_final = $conceito;
		} else {
			$nota_final = sprintf("%.2f", $nota_final);

			if (($nota_final >= 5) && ($nota_final <= 10)) {
				if ($medias[$curso_desc]->soma == NULL) {
					$medias[$curso_desc]->soma = 0;
					$medias[$curso_desc]->count = 0;
				}
				$medias[$curso_desc]->soma += $nota_final;
				$medias[$curso_desc]->count++;
			}
		}

		$num_creditos = sprintf("%.2f", $num_creditos);

		if ($i == 1) {

			echo "<tr>\n";
			echo "<th width=\"7%\">Cód.</td>";
			echo "<th width=\"5%\">Per&iacute;odo</td>";
			echo "<th width=\"40%\">Disciplina/Professor</td>";
			echo "<th width=\"9%\" align=\"center\">Nota</td>";
			//echo "<th width=\"9%\" align=\"center\">Dia</td>";
			echo "<th width=\"9%\" align=\"center\">Situa&ccedil;&atilde;o</td>";
			//echo "<th width=\"7%\" align=\"center\">Créd.</td>";
			//echo "<th width=\"8%\" align=\"center\">Sala</td>";
			echo "<th width=\"3%\" align=\"center\">S</td>";
			echo "<th width=\"3%\" align=\"center\">M</td>";
			echo "</tr>";
		}

		if ($fl_internet == 't') {
			$fl_internet = "<img src=\"../sagu/images/internet.gif\" alt=\"Matrícula feita pela Internet\" title=\"Matrícula feita pela Internet\">";
		} else {
			$fl_internet = "<img src=\"../sagu/images/normal.gif\" alt=\"Matrícula feita na sede\" title=\"Matrícula feita na sede\">";
		}

		if ($status_disciplina == 't') {
			$status_disciplina = "<img src=\"../sagu/images/autorizado.gif\" alt=\"Matrícula com desbloqueio\" title=\"Matrícula com desbloqueio\">";
		} else {
			$status_disciplina = "<img src=\"../sagu/images/liberado.gif\" alt=\"Matrícula sem desbloqueio\" title=\"Matrícula sem desbloqueio\">";
		}

		if ($dt_cancelamento == 't') {
			$cor = 'blue';
			$title = 'Matrícula Efetivada';
			$cancelada = "<font color=$cor alt=\"$title\" title=\"$title\"><b>&nbsp;&nbsp;&nbsp;</b></font>";
		} else {
			$cor = 'red';
			$title = 'Matrícula Cancelada';
			$cancelada = "<font color=$cor alt=\"$title\" title=\"$title\"><b>[C]</b></font>";
		}

		if ($i % 2) {
			$bg = $bg1;
		} else {
			$bg = $bg2;
		}

		echo ("<tr bgcolor=\"$bg\">\n");
		echo ("<td width=\"7%\">$ref_disciplina</td>");
		echo ("<td width=\"5%\">$ref_periodo</td>");
		echo ("<td width=\"36%\">");

        // APROVEITAMENTO DE ESTUDOS 2
        // CERTIFICACAO DE EXPERIENCIAS 3
        // EDUCACAO FISICA 4
        switch ($ref_motivo_matricula) {
            case 0:
                echo '<img src="../../images/check_nossa.gif" alt="Cursada na institui&ccedil;&atilde;o" title="Cursada na institui&ccedil;&atilde;o" />';
                break;
            case 2:
                echo '<img src="../../images/check_aproveitamento_estudos.gif" alt="Aproveitamento de Estudos" title="Aproveitamento de Estudos" />';
                break;
            case 3:
                echo '<img src="../../images/check_certificacao_experiencia.gif" alt="Certificação de experi&ecirc;ncia" title="Certificação de experi&ecirc;ncia" />';
                break;
            case 4:
                echo '<img src="../../images/check_educacao_fisica.gif" alt="Dispensado de Educa&ccedil;&atilde;o f&iacute;sica" />';
                break;
        }

/*
		if ($obs_aproveitamento != '') {
			echo ("<img src=\"../../sagu/images/checkapr.gif\" alt=\"Disciplina Aproveitada\" title=\"Disciplina Aproveitada\">");
		} else {
			$sql = "select ref_campus from contratos where ref_pessoa = $pessoa and ref_curso = $ref_curso";
			$query22 = $conn->CreateQuery($sql);
			$query22->MoveNext();
			$ref_campus_aux = $query22->GetValue(1);

			if ($ref_campus != $ref_campus_aux) {
				echo ("<img src=\"../../sagu/images/checkoff.gif\" alt=\"Fora da sede\" title=\"Fora da sede\">");
			} else {
				echo ("<img src=\"../../sagu/images/checkon.gif\" alt=\"Na sede\" title=\"Na sede\">");
			}
		}
*/
		if (!is_null($professor)) {
			$professor = "(<i>$professor</i>)";
		}

		echo ("$cancelada $desc_disciplina $professor</td>");
		echo ("<td width=\"9%\" align=\"center\">$nota_final</td>");
		//echo ("<td width=\"9%\" align=\"center\">$dia_semana&nbsp;</td>");
		echo ("<td width=\"9%\" align=\"center\">$status</td>");
		//echo ("<td width=\"7%\" align=\"center\">$num_creditos</td>");
		//echo ("<td width=\"8%\" align=\"center\">$num_sala&nbsp;</td>");
		echo ("<td width=\"3%\" align=\"center\">$status_disciplina</td>");
		echo ("<td width=\"3%\" align=\"center\">$fl_internet</td>");
		echo ("</tr>");

		$i++;

	}

	// CÁLCULO E IMPRESSÃO DAS MÉDIAS NOS DIFERENTES CURSOS DA PESSOA
	echo ("<tr>");
	echo ("   <td colspan=\"11\">&nbsp;</td>");
	echo ("</tr>");
	if ($medias) {
		foreach ($medias as $curso => $key) {
			echo ("<tr bgcolor=\"$bg1\">");
			echo ("  <td colspan=\"4\" align=\"left\">MÉDIA EM $curso</td>");
			echo ("  <td align=\"center\">" . round($medias[$curso]->soma / $medias[$curso]->count, 2) . "&nbsp;</td>");
			echo ("  <td colspan=\"6\">&nbsp;</td>");
			echo ("</tr>");
		}
	}

	echo ("</table>");
	$query->Close();
	$conn->Close();
}

function Mostra_Matricula_Outros() {
	global $pessoa_id, $periodo_id;

	$conn = new Connection;

	$conn->Open();

	$sql = " select A.id, " .
	"        descricao_disciplina(A.ref_disciplina), " .
	"        A.nota_final,  " .
	"        A.ref_disciplina, " .
	"        B.num_creditos,  " .
	"        A.status_disciplina, " .
	"        A.fl_internet " .
	" from matricula A, disciplinas B " .
	" where A.ref_disciplina_ofer is null and " .
	"       A.ref_pessoa = '$pessoa_id' and " .
	"       A.ref_disciplina = B.id " .
	" order by A.ref_periodo;";

	$query = $conn->CreateQuery($sql);

	echo ("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabela_relatorio\"");

	$i = 1;

	// cores fundo
	$bg0 = "#000000";
	$bg1 = "#EEEEFF";
	$bg2 = "#FFFFEE";

	while ($query->MoveNext()) {
		list ($id, $desc_disciplina, $nota_final, $ref_disciplina, $num_creditos, $status_disciplina, $fl_internet) = $query->GetRowValues();

		$nota_final = sprintf("%.2f", $nota_final);
		$num_creditos = sprintf("%.2f", $num_creditos);

		if ($i == 1) {

			echo ("<tr>\n");
			echo ("<th width=\"5%\">&nbsp;</td>");
			echo ("<th width=\"10%\">Cód.</b></font></td>");
			echo ("<th width=\"34%\">Disciplina</b></font></td>");
			//echo ("<th width=\"10%\">Créd</td>");
			echo ("<th width=\"10%\">Nota</td>");
			echo ("<th width=\"3%\">S</td>");
			echo ("<th width=\"3%\">M</td>");
			echo ("</tr>");
		}

		$href = "<a href=\"matricula_altera.phtml?id=$id\"><img src=\"../sagu/images/select.gif\" alt='Ver Matrícula' title='Ver Matrícula' align='absmiddle' border=0></a>";

		if ($fl_internet == 't') {
			$fl_internet = "<img src=\"../sagu/images/internet.gif\" alt=\"Matrícula feita pela Internet\" title=\"Matrícula feita pela Internet\">";
		} else {
			$fl_internet = "<img src=\"../sagu/images/normal.gif\" alt=\"Matrícula feita na sede\" title=\"Matrícula feita na sede\">";
		}

		if ($status_disciplina == 't') {
			$status_disciplina = "<img src=\"../sagu/images/autorizado.gif\" alt=\"Matrícula com desbloqueio\" title=\"Matrícula com desbloqueio\">";
		} else {
			$status_disciplina = "<img src=\"../sagu/images/liberado.gif\" alt=\"Matrícula sem desbloqueio\" title=\"Matrícula sem desbloqueio\">";
		}

		if ($i % 2) {
			$bg = $bg1;
		} else {
			$bg = $bg2;
		}

		echo ("<tr bgcolor=\"$bg\">\n");
		echo ("<td width=\"5%\">$href</td>");
		echo ("<td width=\"10%\">$ref_disciplina</td>");
		echo ("<td width=\"34%\">$desc_disciplina</td>");
		//echo ("<td width=\"10%\">$num_creditos</td>");
		echo ("<td width=\"10%\">$nota_final</td>");
		echo ("<td width=\"3%\" align=\"center\">$status_disciplina</td>");
		echo ("<td width=\"3%\" align=\"center\">$fl_internet</td>");
		echo ("</tr>");

		$i++;

	}

	echo "</table>";
	$query->Close();
	$conn->Close();
}

function Mostra_Contratos() {

	global $pessoa_id, $periodo_id;

	$conn = new Connection;

	$conn->Open();

	$sql = " select ref_curso, " .
	"        ref_campus, " .
	"        count(*) " .
	" from contratos " .
	" where ref_pessoa = '$pessoa_id' " .
	" group by ref_curso, " .
	"          ref_campus";

	$query = $conn->CreateQuery($sql);

	while ($query->MoveNext()) {
		list ($ref_curso, $ref_campus, $num_contratos) = $query->GetRowValues();

		if ($num_contratos > '1') {
			$ref_curso_aux = $ref_curso;
		}

	}
	$query->Close();

	$sql = " select id, " .
	"        curso_desc(ref_curso), " .
	"  	  dt_ativacao," .
	"        dt_desativacao, " .
	"  	  ref_pessoa, " .
	"  	  ref_curso, " .
	"        ref_campus " .
	" from contratos" .
	" where ref_pessoa = '$pessoa_id'" .
	" order by dt_ativacao";

	$query = $conn->CreateQuery($sql);

	echo ("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabela_relatorio\"");

	$i = 1;

	// cores fundo
	$bg0 = "#000000";
	$bg1 = "#EEEEFF";
	$bg2 = "#FFFFEE";

	while ($query->MoveNext()) {
		list ($ref_contrato, $curso, $dt_ativacao, $dt_desativacao, $ref_pessoa, $ref_curso, $ref_campus) = $query->GetRowValues();

		$dt_ativacao = InvData($dt_ativacao);
		$dt_desativacao = InvData($dt_desativacao);

		if ($i == 1) {

			echo ("<tr>\n");
			//echo ("<th align=left width=\"5%\">&nbsp;</td>");
			echo ("<th align=left width=\"5%\">Código</td>");
			echo ("<th width=\"50%\">Curso</td>");
			echo ("<th width=\"17%\">Ativacao</td>");
			echo ("<th width=\"23%\">Desativacao</td>");
			echo ("</tr>");
		}

		//$href = "<a href=\"/academico/alterar_contrato.phtml?id=$ref_contrato\"><img src=\"../../sagu/images/select.gif\" alt='Ver Contrato' title='Ver Contrato' align='absmiddle' border=0></a>";

		$url = "post/troca_contrato.php3?ref_pessoa=$ref_pessoa%26ref_contrato=$ref_contrato%26ref_curso=$ref_curso%26ref_campus=$ref_campus";

		$href1 = "<a href=\"/academico/pede_senha.phtml?url=$url\"><img src=\"../sagu/images/obs.gif\" alt='Converte Disciplinas deste Contrato para Outro. Casos de Trancamento por Débito Financeiro' title='Converte Disciplinas deste Contrato para Outro. Casos de Trancamento por Débito Financeiro' align='absmiddle' border=0></a>";

		if ($i % 2) {
			$bg = $bg1;
		} else {
			$bg = $bg2;
		}

		echo ("<tr bgcolor=\"$bg\">\n");
		//echo ("<td width=\"5%\">$href</td>");
		echo ("<td width=\"5%\">$ref_curso</td>");
		echo ("<td width=\"50%\">$curso</td>");
		echo ("<td width=\"17%\">$dt_ativacao</td>");
		if (($ref_curso == $ref_curso_aux) && ($dt_desativacao != '')) {
			echo ("<td width=\"23%\">&nbsp;$dt_desativacao $href1</td>");
		} else {
			echo ("<td width=\"23%\">&nbsp;$dt_desativacao</td>");
		}
		echo ("  </tr>");

		$i++;

	}

	echo "</table>";
	$query->Close();
	$conn->Close();
}
?>
<script language="JavaScript">
function _select(param1,param2,param3)
{
 if (param1 == 1)
 window.opener.setResult(param1,param2,param3);
}
</script>
<link href="../../Styles/style.css" rel="stylesheet" type="text/css">
</head>
<body marginwidth="20" marginheight="20">
<center>
<div style="width: 760px;" align="center">
  <div align="center" style="text-align:center; font-size:12px;"> <img src="../../images/armasbra.jpg" width="57" height="60"><br />
    MEC-SETEC<br />
    CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
    SETOR DE REGISTROS ESCOLARES <br />
    <br />
    <br />
  </div>
  <h2>Ficha Global do Aluno</h2>
  Aluno: <?php echo $pessoa . '  -  ' . $nome_aluno;?>
  <form method="post" action="" name="myform">
    <?php 
		if(!empty($periodo_id))
			echo "<h3>Disciplinas Matriculadas -  $periodo_id</h3>";
    ?>

    <?php Mostra_Matricula(); ?>
    <h3>Disciplinas Cursadas</h3>
    <?php Mostra_Matricula_Geral(); ?>
    <h3>Outras</h3>
    <?php Mostra_Matricula_Outros(); ?>
    <br />
    <h3>Contratos</h3>
    <?php Mostra_Contratos(); ?>
    <br />
<div align="left">
    <h4>Legenda</h4>

    <img src="../../images/check_nossa.gif" alt="Cursada na institui&ccedil;&atilde;o" title="Cursada na institui&ccedil;&atilde;o" /> Disciplina Cursada na Institui&ccedil;&atilde;o<br />
    <img src="../../images/check_aproveitamento_estudos.gif" alt="Aproveitamento de Estudos" title="Aproveitamento de Estudos" /> Aproveitamento de Estudos <br />
    <img src="../../images/check_certificacao_experiencia.gif" alt="Certifica&ccedil;&atilde;o de experi&ecirc;ncia" title="Certificação de experi&ecirc;ncia" /> Certifica&ccedil;&atilde;o Experi&ecirc;ncia <br />
     <img src="../../images/check_educacao_fisica.gif" alt="Dispensado de Educa&ccedil;&atilde;o f&iacute;sica" /> Dispensado de Educa&ccedil;&atilde;o f&iacute;sica<br />
</div>
    <div align="center">
      <input type="button" name="Button" value="Voltar" onClick="javascript:history.go(-1)">
    </div>
  </form>
</div>
</center>
</body>
</html>
