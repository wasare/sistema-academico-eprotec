<?php

/* DEBUG
print_r($_POST);
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
*/

//INCLUSAO DE BIBLIOTECAS
require_once("../../../configs/configuracao.php");
require_once("../../../lib/adodb/tohtml.inc.php");


//BOTAO CONFIRMA
//Param: lista id de matriculas, conexao
function bt_confirma($listaMatriculas, $Con)
{
	$sql = "DELETE FROM matricula WHERE id IN(" . $listaMatriculas . ");";
	
	//echo $sql;die;
	
	$RsApagarMatricula = $Con->Execute($sql);
	
	if (!$RsApagarMatricula){
		print $Con->ErrorMsg();
		die();
	}else{
		print "<div align='center'>";
		print "<h2><font color='red'><strong>Matrículas excluídas com sucesso!</strong></font></h2>";
		print "<a href='filtro.php'>Excluir outra matricula</a>";
		print "</div>";
		die;
	}
	
	//header("location: filtro.php");
}


//BOTAO CANCELA
function bt_cancel()
{
	header("location: filtro.php");
}


// EXIBE PESSOA
//Param: id pessoa, conexao
function exibeDadosAluno($aluno, $Con)
{
	
	$sqlAluno = "SELECT id, nome FROM pessoas WHERE id = $aluno;";
	
	$RsAluno = $Con->Execute($sqlAluno);
	
	//Se Result1 falhar
	if (!$RsAluno){
		print $Con->ErrorMsg();
		die();
	}
	
	$exibeAluno = $RsAluno->fields[0] . " - " . $RsAluno->fields[1] ;
	
	return $exibeAluno;
}


//MONTA LISTA DE MATRICULAS
//Param: vetor matriculas
function montaListaMatriculas($vetMatriculas)
{
	$matriculas = '';
	
	foreach($vetMatriculas as $i)
	{
		if($matriculas == '')
		{
			$matriculas .= "$i";
		}else
		{
			$matriculas .= ", " . "$i";
		}
	}
	
	return $matriculas;
}


// EXIBE MATRICULADAS 
// Param: lista de matriculas, conexao
function exibeMatriculadas($matriculas, $Con)
{

	//echo $matriculas;die;
	
	$sqlDiarios = "
	SELECT
	m.id, m.ref_disciplina_ofer, d.id, d.descricao_disciplina, m.ref_curso, c.descricao
	FROM
	matricula m, cursos c, disciplinas_ofer o, disciplinas d
	WHERE
	m.id IN($matriculas) AND
	m.ref_curso = c.id AND
	o.id = m.ref_disciplina_ofer AND
	o.ref_disciplina = d.id
	ORDER BY c.descricao;";
	
	$RsDiarios = $Con->Execute($sqlDiarios);	
	
	if (!$RsDiarios){
		print $Con->ErrorMsg();
		die();
	}
	

	while(!$RsDiarios->EOF) {
	 
		if($cor == "#E1E1FF")
		{
			$cor = "#FFFFFF";
		} else
		{
			$cor = "#E1E1FF";
		}
	
		$exibe_diarios .= "<tr bgcolor=\"$cor\">";
		$exibe_diarios .= "<td>" . $RsDiarios->fields[1] . "</td>";
		$exibe_diarios .= "<td>" . $RsDiarios->fields[2] . " - " . $RsDiarios->fields[3] . "</td>";
		$exibe_diarios .= "<td>" . $RsDiarios->fields[4] . " - " . $RsDiarios->fields[5] . "</td>";
		$exibe_diarios .= "</tr>";	

		$RsDiarios->MoveNext();

	}
	
	return $exibe_diarios;
	
}//fim exibeMatriculadas


//Criando a classe de conexão
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

// VERIFICA SE HOUVE ENVIO DESTE FORMULARIO
if($_POST["lista_matriculas"])
{
	/** ACOES DOS BOTOES DO FORMULARIO  **/
	if($_POST["opcao"] == "confirm")
	{
		bt_confirma($_POST["lista_matriculas"],$Conexao);
	}
	elseif($_POST["opcao"] == "cancel")			
	{
		bt_cancel();
	}
	
}
else
{
	//POST lista_cursos.php
	$id_mat = $_POST["id_matricula"];//vetor matriculas
	$cod_aluno = $_POST["cod_aluno"];
	$periodo = $_POST["periodo"];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SA</title>
<link href="../../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
  <h1>Excluir Matr&iacute;cula</h1>
  <div class="box_geral"> <strong>Aluno: </strong><?php echo exibeDadosAluno($cod_aluno, $Conexao); ?><br />
    <strong>Per&iacute;odo: </strong>
    <?=$periodo?>
  </div>
  <p>
  <font color="red" class="msg_erro">
     <strong>Tem certeza que deseja excluir a(s) matr&iacute;cula(s) na(s) disciplina(s) listada(s)?</strong>
  </font>
  </p>
  <table border="0" cellpadding="0" cellspacing="2">
    <tr>
      <td height="32" align="center" bgcolor="#CCCCFF">Diário</td>
      <td height="32" align="center" bgcolor="#CCCCFF">Disciplina</td>
      <td height="32" align="center" bgcolor="#CCCCFF">Curso</td>
    </tr>
    <?php echo exibeMatriculadas(montaListaMatriculas($id_mat), $Conexao); ?>
  </table>
  <br /><br />
  <form id="form1" name="form1" method="post" action="">
    <input name="lista_matriculas" type="hidden" value="<?php echo montaListaMatriculas($id_mat);?>" />
    <input name="opcao" type="hidden" value="" />
    <input type="submit" name="button2" id="button2" value="Cancelar" onclick="document.form1.opcao.value = 'cancel'" />
    <input type="submit" name="button" id="button" value="Confirmar" onclick="document.form1.opcao.value = 'confirm'" />
  </form>
</div>
</body>
</html>
