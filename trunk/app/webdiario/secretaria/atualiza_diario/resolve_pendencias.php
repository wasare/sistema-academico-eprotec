<?php
// http://sagu.cefetbambui.edu.br/diario/movimentos/lancanotas/lanca1.php
include_once('../conf/webdiario.conf.php');


$getofer = $_GET['ofer'];
$getdisciplina = $_GET['disc'];

if(isset($_GET['getperiodo']) && $_GET['getperiodo'] != '') {

    $getperiodo = $_GET['getperiodo'];
}
else {
	$getperiodo = $_SESSION['periodo'];
}

if(isset($_GET['refprof']) && is_numeric($_GET['refprof'])) {

    $id = $_GET['refprof'];
}
else {
    $id = $_SESSION['id'];
}


//print_r($_SESSION);

//echo '<br />';

//print_r($_GET);

//die;

//$grupo = $_GET['grupo'];

//$getcurso = $_GET['curso'];

//$getdisciplina = $_GET['getdisciplina'];
//$getofer = $_GET['getofer'];


$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);

$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);

//echo '<br />'.$grupo.'<br />'; 


//echo $grupo_novo.'<br />';

//die;

$flag_pendencia = 0;

$qryDiario = 'BEGIN;';

$msg = 'Encontradas e resolvidas as seguintes pendências:\n\n';

$getcurso = getCurso($getperiodo,$getdisciplina,$getofer);

$CursoTipo = getCursoTipo($getofer);



// VERIFICA PENDENCIAS RELACIONADAS AO LANCAMENTO DE NOTAS
$sql1 = "SELECT
         grupo
         FROM diario_formulas
         WHERE
         grupo ILIKE '$grupo_novo';";

//echo $sql1; die;

$qryFormula = consulta_sql($sql1);

if(is_string($qry))
{
    echo $qry;
    exit;
}

$numformula = pg_numrows($qryFormula);

if($numformula == 6) {

	$qryNotas = 'SELECT 
		m.ref_pessoa, id_ref_pessoas 
	    FROM 
	    	matricula m 
	    LEFT JOIN ( 
		    	SELECT DISTINCT 
				d.id_ref_pessoas 
			FROM 
				diario_notas d 
			WHERE 
				d.d_ref_disciplina_ofer = ' . $getofer . '
		      ) tmp 
		ON ( m.ref_pessoa = id_ref_pessoas ) 
	    WHERE 
	    	m.ref_disciplina_ofer = ' . $getofer . ' AND 
		id_ref_pessoas IS NULL 
	    ORDER BY 
	    	    id_ref_pessoas;';

    //echo $qryNotas; die;
	$qry = consulta_sql($qryNotas);

	if(is_string($qry))
	{
		echo $qry;
		exit;
	}
	
	$NumReg = pg_numrows($qry);

	$NumNotas = 6;

	if ($NumReg > 0) 
	{
		$getcurso = getCurso($getperiodo,$getdisciplina,$getofer);
		
		while($registro = pg_fetch_array($qry))
		{
			$ref_pessoa = $registro['ref_pessoa'];
		
			for($i = 1 ; $i <= $NumNotas; $i++)
			{
				$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
				$qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
				$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
				$qryDiario .= ' rel_diario_formulas_grupo)';
				$qryDiario .= " VALUES($ref_pessoa,'$i','0','0',$ref_pessoa,'$getperiodo',$getcurso,";
				$qryDiario .= " $getofer,'$grupo');"; 
			}

			$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
			$qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
			$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
			$qryDiario .= ' rel_diario_formulas_grupo)';
			$qryDiario .= " VALUES($ref_pessoa,'7','-1','0',$ref_pessoa,'$getperiodo',$getcurso,";
			$qryDiario .= " $getofer,'$grupo');";
		}


		
		$flag_pendencia = 1;
        $msg .= $NumReg . ' alunos com problemas no lançamento de notas\n';

   }
}
// ^ VERIFICA PENDENCIAS RELACIONADAS AO LANCAMENTO DE NOTAS ^


// VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE FALTAS
$sqlDiarioFaltas = "
SELECT * FROM 
(
SELECT 
   DISTINCT 
registro_id, CASE
                 WHEN num_faltas IS NULL THEN '0'
                 ELSE num_faltas
                 END AS num_faltas, CASE
                                        WHEN faltas_diario IS NULL THEN '0'
                                        ELSE faltas_diario
                                        END AS faltas_diario
FROM
(
SELECT 
    DISTINCT 
      CAST(a.ref_pessoa AS INTEGER) AS registro_id, a.num_faltas
    FROM 
      matricula a
    WHERE 
      a.ref_periodo = '$getperiodo' AND
      a.ref_disciplina_ofer = '$getofer'
) AS T1
FULL OUTER JOIN
(
SELECT 
      CAST(a.ra_cnec AS INTEGER) AS registro_id, count(a.ra_cnec) AS faltas_diario 
    FROM 
      diario_chamadas a 
    WHERE 
      (a.ref_periodo = '$getperiodo') AND
      (a.ref_disciplina_ofer = '$getofer') AND
       a.ra_cnec IN (
          SELECT DISTINCT a.ref_pessoa FROM matricula a WHERE a.ref_periodo = '$getperiodo' AND a.ref_disciplina_ofer = '$getofer'

    ) 
    GROUP BY ra_cnec
) AS T4

USING (registro_id)

) AS TB

WHERE 
(num_faltas <> faltas_diario);";

$qryFaltas = consulta_sql($sqlDiarioFaltas);


//echo $sqlDiarioFaltas.'<br /><br />'; die;

if(is_string($qryFaltas))
{
    echo $qryFaltas;
    exit;
}

$numFalta = pg_numrows($qryFaltas);


if ($numFalta != 0) {
      
      while($registro = pg_fetch_array($qryFaltas))
      {
         $ref_pessoa = $registro['registro_id'];
         $faltas = $registro['faltas_diario'];
         $num_faltas = $registro['num_faltas'];

         $qryDiario .= ' UPDATE matricula SET num_faltas = '. $faltas;
         $qryDiario .= ' WHERE ref_pessoa = '. $ref_pessoa .' AND';
         $qryDiario .= " ref_periodo = '$getperiodo' AND ";
         $qryDiario .= ' ref_disciplina_ofer = '. $getofer .';';
      }

      $flag_pendencia = 1;
      $msg .= $numFalta . ' alunos com problemas no somatório de faltas\n';

}
// ^ VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE FALTAS ^


// VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE NOTAS

$sqlNotas = "
SELECT 
    DISTINCT 
        registro_id, nota_diario, nota_extra, nota_final
FROM
(
SELECT 
  DISTINCT 
    CAST(b.id AS INTEGER) AS registro_id, CAST(SUM(c.nota) AS NUMERIC) AS nota_diario 
  FROM 
    matricula a, pessoas b, diario_notas c 
  WHERE 
    a.ref_periodo = '$getperiodo' AND 
    a.ref_disciplina_ofer = '$getofer' AND 
    b.ra_cnec = c.ra_cnec AND 
    c.d_ref_disciplina_ofer = '$getofer' AND 
    a.ref_pessoa = b.id AND 
    b.ra_cnec IN (
          SELECT DISTINCT a.ref_pessoa FROM matricula a WHERE a.ref_periodo = '$getperiodo' AND a.ref_disciplina_ofer = '$getofer'

    )  AND 
    ref_diario_avaliacao < 7
  GROUP BY b.id 
) AS T1

INNER JOIN 

(
SELECT 
  DISTINCT 
    CAST(b.id AS INTEGER) AS registro_id, CAST(c.nota AS NUMERIC) AS nota_extra
  FROM 
    matricula a, pessoas b, diario_notas c 
  WHERE 
    a.ref_periodo = '$getperiodo' AND 
    a.ref_disciplina_ofer = '$getofer' AND 
    b.ra_cnec = c.ra_cnec AND 
    c.d_ref_disciplina_ofer = '$getofer' AND 
    a.ref_pessoa = b.id AND 
    b.ra_cnec IN (
          SELECT DISTINCT a.ref_pessoa FROM matricula a WHERE a.ref_periodo = '$getperiodo' AND a.ref_disciplina_ofer = '$getofer'

    )  AND 
    ref_diario_avaliacao = 7
) AS T2

USING (registro_id)
INNER JOIN

(
SELECT 
    DISTINCT 
      CAST(a.ref_pessoa AS INTEGER) AS registro_id, CAST(a.nota_final AS NUMERIC) 
    FROM 
      matricula a
    WHERE 
      a.ref_periodo = '$getperiodo' AND 
      a.ref_disciplina_ofer = '$getofer' 
) AS T3

USING (registro_id)

WHERE
   nota_diario <> nota_final;";


//echo $sqlNotas; die;
$qryNotas = consulta_sql($sqlNotas);

if(is_string($qryNotas))
{
    echo $qryFaltas;
    exit;
}


//echo $sqlNotas; die;

$numNotas = pg_numrows($qryNotas);


if ($numNotas != 0) {

      $numNotas = 0;
      
      while($registro = pg_fetch_array($qryNotas))
      {
		 //print_r($registro);
		 
         $ref_pessoa = $registro['registro_id'];
         $nota_diario = $registro['nota_diario'];
         $nota_final = $registro['nota_final'];
         $nota_extra = $registro['nota_extra'];

         
         if($nota_extra == -1 && $nota_diario != $nota_final) {
            // NOTA EXTRA NÃO LANCADA E SOMATORIO ERRADO
            $qryDiario .= ' UPDATE matricula SET nota_final = '. $nota_diario;
            $qryDiario .= ' WHERE ref_pessoa = '. $ref_pessoa .' AND';
            $qryDiario .= " ref_periodo = '$getperiodo' AND ";
            $qryDiario .= ' ref_disciplina_ofer = '. $getofer .';';

            $numNotas++;
         }
         else {
            // NOTA EXTRA LANCADA
            if($nota_diario < 60 || $nota_final < 60) {

               // CALCULA NOTA FINAL E VERIFICA NOTA EXTRA SOMENTE COM NOTA < 60
               // NOTA < 60 RATIFICA O LANCAMENTO DA NOTA EXTRA
               
               /*if($CursoTipo == 1 || $CursoTipo == 7 || $CursoTipo == 8 || $CursoTipo == 9) {

                  $NotaFinal = $nota_extra;
               }*/
               
               if( $CursoTipo == 2 || $CursoTipo == 4 || $CursoTipo == 5 || $CursoTipo == 6 || $CursoTipo == 10 ) {

                    $NotaFinal = (($nota_diario + $nota_extra) / 2);
               }
               else
	       {
                  $NotaFinal = $nota_extra;
               }

 
               if($NotaFinal != $nota_final) {

                  // NOTA EXTRA LANCADA E SOMATORIO ERRADO
                  $qryDiario .= ' UPDATE matricula SET nota_final = '. $NotaFinal;
                  $qryDiario .= ' WHERE ref_pessoa = '. $ref_pessoa .' AND';
                  $qryDiario .= " ref_periodo = '$getperiodo' AND ";
                  $qryDiario .= ' ref_disciplina_ofer = '. $getofer .';';

                  $numNotas++;
               }
            }

         }
      }

      if($numNotas > 0) {
      
         $flag_pendencia = 1;
         $msg .= $numNotas . ' alunos com problemas no somatório de notas\n';
      }

}

if($flag_pendencia == 1) {

   $qryDiario .= "COMMIT;";

  // echo $qryDiario; die;

   $res = consulta_sql($qryDiario);

     
   if(is_string($res)) {

      
    //  echo $res;
	  $_GET = array();
								
      print '<script language=javascript>  window.alert("OCORREU ALGUM ERRO QUE IMPEDIU A ATUALIZAÇÃO DOS DADOS!!\n Código do diário: '. $getofer . '"); javascript:window.history.back(1);
      </script>';
      exit;
   }
   else {
	     $_GET = array();
		 
         print '<script language=javascript>  window.alert("'. $msg . '"); javascript:window.history.back(1);
                  </script>';
         exit;
   }


}
else {

	$_GET = array();

	print '<script language=javascript>
            window.alert("Nenhuma pendência!");
        javascript:window.history.back(1);
        </script>';
        exit;
}


?>
