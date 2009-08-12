<?php

require_once('../webdiario/webdiario.conf.php');


$matriculas = array(150199,150162,150185,150194,150196,150197,151415,151416,150200,150202,150203,150204,150205,147727,147994,147995,150208,148506,148508,147778,147779,147774,147775,147776,148560,148561,148562,150212,148494,148288,148305,148105,151417,147724,147725,147726,150050,150068,147728,147731,147729,147730,147732,155298,148294,148297,148301);


if($_POST['btnOK'] == 10 AND $_POST['nota1'] <= 100 AND in_array($_POST['registro_id'], $matriculas))
{

    // grava a nota
    $nota1 = str_replace(",",".",$_POST['nota1']);
    $sqlup = 'UPDATE matricula SET nota_final = '. $nota1 .' WHERE id = '. $_POST['registro_id'] .';';
    $qryup = consulta_sql($sqlup);

    if(is_string($qryup))
    {
        echo $qryup;
        exit;
    }
    //else
     // header('Location: lista_dispensas_nota_zero.php');
}


if (is_numeric($_GET['id']))
	$registro_id = $_GET['id'];

if (is_numeric($_POST['registro_id']))
	$registro_id = $_POST['registro_id'];

if(is_numeric($registro_id) AND in_array($registro_id, $matriculas))
{
  

	$sqlDispensas = 'SELECT id, ref_pessoa AS "Matricula", pessoa_nome(ref_pessoa) AS "Nome", ref_curso || \' - \' ||  get_curso_abrv(ref_curso) AS "Curso", descricao_disciplina(get_disciplina_de_disciplina_of(ref_disciplina_ofer)) AS "Disciplina", nota_final AS "Nota" FROM matricula where id = '. $registro_id .'  ORDER BY 2,1;';

	$qry1 = consulta_sql($sqlDispensas);

	if(is_string($qry1))
	{
		echo $qry1;
		exit;
	}

	echo 'Matricula &nbsp;/&nbsp; Nome &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/ Curso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/  Disciplina &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  / Nota&nbsp;&nbsp;&nbsp;  <br />  ';

	while($linha = pg_fetch_array($qry1))
	{
		$ref_pessoa = $linha['Matricula'];
		$nome = $linha['Nome'];
		$curso = $linha['Curso'];
		$disciplina = $linha['Disciplina'];
		$nota = $linha['Nota'];
		$registro_id = $linha['id'];
		echo "$ref_pessoa&nbsp;&nbsp;&nbsp;$nome&nbsp;&nbsp;&nbsp;$curso&nbsp;&nbsp;&nbsp;$disciplina&nbsp;&nbsp;&nbsp;$nota";
	}

    echo '<form name="form1" method="post" action="">';

   	echo '<input type="hidden" name="btnOK" id="btnOK" value="10" />';
    echo '<input type="hidden" name="registro_id" id="registro_id" value="'. $registro_id .'" />';

  	echo 'Nota da dispensa:&nbsp;<input type="text" name="nota1" id="nota1" size="6" value="" />';
   	echo '<input type="submit" name="enviar" id="enviar" value="Gravar -->" />';

	echo '</form>';
}


echo '<br /><a href="lista_dispensas_nota_zero.php">Voltar</a>';


?>
