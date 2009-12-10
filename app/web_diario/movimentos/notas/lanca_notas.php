<?php

require_once('../../app/setup.php');
require_once($BASE_DIR .'core/web_diario.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['id'];
$operacao = $_GET['do'];

/*
TODO: verifica o direito de acesso do usu�rio ao di�rio informado
*/

if(!is_inicializado($diario_id)) 
{
    if (ini_diario($diario_id))
        echo '<script type="text/javascript">  window.alert("Diario iniciado com sucesso!"); </script>';
    else
    {
        // FIXME: informar ao administrador/desenvolvedor quando ocorrer erro
        echo '<script language=javascript>  window.alert("Falha ao inicializar o diario!"); </script>';
        exit;   
    }
} 
else 
{

	$curso = get_curso($diario_id);
	$disciplina = get_disciplina($diario_id);

	$sa_ref_periodo = $_SESSION['web_diario_periodo_id'];

	$grupo_novo = ("%-" . $sa_ref_periodo . "-%-" . $diario_id);

	// ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO
	$qryNotas = 'SELECT
        m.ref_pessoa, id_ref_pessoas
        FROM
            matricula m
        LEFT OUTER JOIN (
                SELECT DISTINCT
                d.id_ref_pessoas
            FROM
                diario_notas d
            WHERE
                d.d_ref_disciplina_ofer = ' . $diario_id . '
              ) tmp
        ON ( m.ref_pessoa = id_ref_pessoas )
        WHERE
            m.ref_disciplina_ofer = ' . $diario_id . ' AND
            id_ref_pessoas IS NULL AND
			(m.dt_cancelamento is null) AND
			(m.ref_motivo_matricula = 0)

        ORDER BY
                id_ref_pessoas;';

	$alunos = $conn->get_all($qryNotas);

	require_once($BASE_DIR .'app/matricula/atualiza_diario_matricula.php');

    if(count($alunos != 0))
	{
		foreach($alunos as $registro)
		{
			$ref_aluno = $registro['ref_pessoa'];
			atualiza_matricula("$ref_aluno","$diario_id");
		}
	}

// ^ ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO ^//

}

?>

<html>
<head>
<title><?=$IEnome?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">
</head>

<body>
<div align="left" class="titulo">
  <h3>Lan&ccedil;amento / Altera&ccedil;&atilde;o de Notas</h3>
</div>
<br />
<?=papeleta_header($diario_id)?>
<br />
<br />
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Voc&ecirc; tem a seguinte f&oacute;rmula cadastrada :</font>

<?php

$sql1 = "SELECT DISTINCT
                formula
                FROM
                diario_formulas
                WHERE
                grupo ILIKE '$grupo_novo';";
                
$formula = $conn->get_one($sql1);

$sql2 = "SELECT
                id,
                prova,
                descricao
                FROM
                diario_formulas
                WHERE
                grupo ILIKE '$grupo_novo'
				ORDER BY descricao;";

$provas = $conn->get_all($sql2);

if (!empty($formula)) 
{ 
     echo '<table cellspacing="0" cellpadding="0" class="papeleta"><tr bgcolor="#CCCCCC"> <td><b>Descri��o</b></td></tr>';
	 echo '<tr bgcolor="#F3F3F3"><td style="font-size: 1.2em">'. $formula .'</td></tr></table>';
     // $st = '#E3E3E3';
} 
else 
{
    echo '<strong><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">N&atilde;o existe f&oacute;rmula ou provas cadastradas !</font></strong>';
}

?>
<br />
    <form name="envia_nota" id="envia_nota" method="post" action="<?=$BASE_URL .'app/web_diario/movimentos/notas/informa_notas.php'?>">
      <div align="left">Lan&ccedil;amento referente � :

			<input type="hidden" name="diario_id" id="diario_id" value="<?=$diario_id?>">
			<input type="hidden" name="operacao" id="operacao" value="<?=$operacao?>">
 	
		<select id="getprova" name="getprova" class="select">
<?php
         foreach($provas as $p)
         {
            $qid = $p['prova'];
            $qdesc = $p['descricao'];
            echo '<option value="'. $p['prova'] .'">'. $p['descricao'] .'</option>';
         }
?>
		 <option value="7">Nota Extra</option>
         </select>


&nbsp;&nbsp;<input type="submit" name="Submit" value="Lan&ccedil;ar notas">
&nbsp;&nbsp;ou&nbsp;
<a href="#" onclick="javascript:window.close();">cancelar</a>
        </div>
    </form>
<h3>INSTRU&Ccedil;&Otilde;ES</h3>
<font color="#330099">* Professor, conforme descrito pela f&oacute;rmula acima voc&ecirc; ter&aacute; no m&aacute;ximo seis (6) notas para lan&ccedil;ar.<br />
* Estas notas representam as avalia&ccedil;&otilde;es aplicadas durante o per&iacute;odo (Provas, Trabalhos, Relat�rios, Monitorias, etc).<br /> * As notas (de uma a seis) ser&atilde;o somadas e o total final n&atilde;o poder&aacute; exceder a 100 pontos!
<br />
* Para nota de <font color="red">recupera&ccedil;&atilde;o/reavalia&ccedil;&atilde;o</font> utilize a op&ccedil;&atilde;o "Nota Extra" na lista de "Lan&ccedil;amento referente &agrave;".
<br />
* <font color="red">IMPORTANTE:</font> Uma vez lan&ccedil;ada a "Nota Extra" as notas de 1 a 6 n&atilde;o poder&atilde;o ser alteradas!
</font>

</body>
</html>