<?php

include_once('../conf/webdiario.conf.php');
require_once($BASE_DIR.'conf/verifica_acesso.php');
	

if($_SESSION['select_periodo'] === "" OR !isset($_SESSION['select_periodo']))
{
        echo '<script language="javascript">
                window.alert("ERRO! Primeiro selecione um periodo!");
        </script>';
        //sleep(2);
        echo '<meta http-equiv="refresh" content="0;url=../select_periodos.php">';
        exit;
}
else {
$qryP = 'SELECT DISTINCT d.id, d.descricao FROM periodos d WHERE d.id = \''. $_SESSION['select_periodo'].'\';';

        $qry1 = consulta_sql($qryP);

        if(is_string($qry1))
        {
            echo $qry1;
            exit;
        }
        else
        {
            if(pg_numrows($qry1) == 1)
            {
                while($linha = pg_fetch_array($qry1))
                {
                    $dsc_periodo = @$linha['descricao'];
                }
            }
      }
}

if($_SESSION['select_curso'] === "" OR !isset($_SESSION['select_curso']))
{
        echo '<script language="javascript">
                window.alert("Primeiro selecione um curso!");
        </script>';
        //sleep(2);
        echo '<meta http-equiv="refresh" content="0;url=andamento_curso.php">';
        exit;
}
else {

	 $qryCurso = " SELECT DISTINCT
    a.ref_curso || ' - ' || c.descricao AS curso, ref_tipo_curso
      FROM
          disciplinas_ofer a, disciplinas_ofer_prof b, cursos c
            WHERE
                a.ref_periodo = '".$_SESSION['select_periodo']."' AND
                    a.id = b.ref_disciplina_ofer AND
                        c.id = a.ref_curso AND
                         a.ref_curso = ".$_SESSION['select_curso']." AND
                            ref_professor IS NOT NULL
            ORDER BY ref_tipo_curso;";

    $qry1 = consulta_sql($qryCurso);

      if(is_string($qry1))
      {
        echo $qry1;
        exit;
      }
      else
      {
          if(pg_numrows($qry1) == 1)
          {
            while($linha = pg_fetch_array($qry1))
            {
                $nome_curso = @$linha['curso'];
            }
         }
      }
}

if($_GET['t'] === "" OR !isset($_GET['t']))
{
        echo '<script language="javascript">
                window.alert("Primeiro selecione uma Turma!");
        </script>';
        //sleep(2);
        echo '<meta http-equiv="refresh" content="0;url=andamento_curso.php">';
        exit;
}
else {

    $Turma = '';

	if($_GET['t'] != 0) { $Turma = " o.turma = '".$_GET['t']."' AND"; }

}

$sql1 = " select 
			o.id AS diario, d.id, d.descricao_disciplina, d.descricao_extenso, d.carga_horaria, p.id || ' - ' || p.nome AS prof 
		from 
			disciplinas d, disciplinas_ofer o, disciplinas_ofer_prof dp, pessoas p 
		where 
			o.ref_periodo = '".$_SESSION['select_periodo']."' and 
			d.id = o.ref_disciplina AND
            dp.ref_disciplina_ofer = o.id AND
            dp.ref_professor IS NOT NULL AND
            p.id = dp.ref_professor AND ".$Turma."         
			o.ref_curso = ".$_SESSION['select_curso']."			
		order by d.descricao_disciplina ;";

//echo $sql1; die;

$query1 = consulta_sql($sql1);

if(is_string($query1))
{
   echo $query1;
   exit;
}
else {

	 $diarios = pg_numrows($query1);
	 
	 if($diarios == 0)
     {

		echo '<script language="javascript">
                window.alert("Nenhum di&aacute;rio encontrado!");
        </script>';
        //sleep(2);
        echo '<meta http-equiv="refresh" content="0;url=andamento_curso.php">';
        exit;

     }

}

$sql2 = " select  
					CASE
                        WHEN a.nota_final IS NULL THEN '0'
                        ELSE a.nota_final
                    END AS notas, 
					
					b.nome || ' (' || b.id || ')' as nome, 
					a.num_faltas, 
					c.descricao_disciplina as disc
					
		from 
			matricula a, pessoas b, disciplinas c 
		where 
			ref_periodo = '".$_SESSION['select_periodo']."' and 
			a.ref_pessoa = b.id and 
			a.ref_disciplina = c.id AND
			a.ref_disciplina_ofer IN (

				select
            o.id 
        from
            disciplinas d, disciplinas_ofer o, disciplinas_ofer_prof dp, pessoas p
        where
            o.ref_periodo = '".$_SESSION['select_periodo']."' and
            d.id = o.ref_disciplina AND
            dp.ref_disciplina_ofer = o.id AND
            dp.ref_professor IS NOT NULL AND
			a.nota_final IS NOT NULL AND
            p.id = dp.ref_professor AND ".$Turma."
            o.ref_curso = ".$_SESSION['select_curso']."
        order by d.descricao_disciplina

				) AND
			a.ref_curso = ".$_SESSION['select_curso']."
		ORDER BY nome, disc;";


//$query2 = pg_exec($dbconnect, $sql2);

//echo $sql2; die;

$query2 = consulta_sql($sql2);

if(is_string($query2))
{
   echo $query2;
   exit;
}
else {
	$matriculas = pg_numrows($query2);
}

//echo $matriculas % $diarios;

/*
echo pg_numrows($query1);
echo '<br />';
echo pg_numrows($query2);
echo '<br />';

echo $sql1.'<br /><br />'.$sql2;
*/
// $sqlnotas = "select round(sum(nota*peso)/sum(peso),1) as notas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(d_ref_disciplina) as disc from diario_notas where id_ref_periodos = '$getperiodo' group by nome, disc";

?>
<html>
<head>
<title>Resumo de Notas e Faltas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/geral.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
</head>

<body>


<table width="100%">
<td align="center">

<h2>Resumo de Notas e Faltas</h2>

<h4>Per&iacute;odo: <font color="blue"><?php echo $dsc_periodo; ?></font></h4>
<h4>Curso: <font color="blue"><?php echo $nome_curso; ?></font></h4>
	 
<?php
/*if($matriculas % $diarios != 0) {
	echo '<h3>Ocorreu algum problema na cria&ccedil;&atilde;o do relat&oacute;rio, avise ao respons&aacute;vel informando o per&iacute;odo/curso/turma com problemas</h3>';
    echo '</td> </tr> </table>';
	die;

}
else {
  echo '</td></tr></table>';
}
*/
// cores para as linhas da tabela
$cor1 = '#F3F6FB';
$cor2 = '#FFFFFF';

echo '</tr></table> ';
echo '<b>LEGENDA </b>
<br /><br />
<table width="100%" cellspacing="0" cellpadding="0" class="papeleta">
<tr bgcolor="#9b9b9b">
<td align="center"><b>C&oacute;d. Di&aacute;rio</b></td>
<td align="center"><b>Descri&ccedil;&atilde;o</b></td>
<td align="center"><b>Professor(a)</b></td>
<td align="center"><b>CH Prevista</b></td>
<td align="center"><b>CH Realizada</b></td>
</tr>
<tr>';

/*
$query1 = consulta_sql($sql1);

if(is_string($query1))
{
   echo $query1;
   exit;
}
*/

while($row1=pg_fetch_array($query1)) {

   if ($cor=="#FFFFFF") { 
   		$cor=$cor1; 
	}else {
		$cor=$cor2; 
	}

   $carga=$row1['carga_horaria'];
	//$descricaodis2=substr($row1["descricao_disciplina"],0,6);
   $descricaodis2 = $row1["diario"];
   $descricaodis = $row1["descricao_extenso"];
   $iddisciplina = $row1["id"];

   $prof = explode(" ", $row1['prof']);
   
   $dsc_prof = '('.$prof[0].') '.$prof[2];

   unset($prof);

   echo "<tr bgcolor=\"$cor\">";
   echo "<td align=center>$descricaodis2</td>";
   echo "<td align=left>$iddisciplina - $descricaodis</td>";
   echo "<td align=center>$dsc_prof</td>";
   echo "<td align=center>$carga</td>";

	//$sqlflag = "select flag from diario_seq_faltas where periodo = '".$_SESSION['periodo']."' and disciplina = '$iddisciplina';"

   $sqlCarga = "SELECT SUM(CAST(flag AS INTEGER)) AS carga  
   				FROM  diario_seq_faltas  
   				WHERE  ref_disciplina_ofer = ".$descricaodis2." ;";

   $qryCarga = consulta_sql($sqlCarga);

   if(is_string($qryCarga))
   {
      echo $qryCarga;
      exit;
   }
   else {
      while($row = pg_fetch_array($qryCarga)) {  
			$carga_realizada = @$row['carga'];  
	  }
   }

   if ( $carga_realizada == "") { 
   		$carga_realizada = 0;
	}

   echo "<td align=\"center\">$carga_realizada</td>";
   unset($carga_realizada);
   echo "</tr>";
}
?>

</tr></table>

<br /><br /><br />

<table width="100%" cellspacing="0" cellpadding="0" class="papeleta"> 
<tr bgcolor="#9b9b9b">
<td><b>Aluno</b></td>

<?php

$query1 = consulta_sql($sql1);

if(is_string($query1))
{
   echo $query1;
   exit;
}

$totd = 0;

while($row1 = pg_fetch_array($query1)) {
   $iddis = $row1["id"];
   //$descricaodis2=substr($row1["descricao_disciplina"],0,6);
   $descricaodis2 = $row1["diario"];
   
   $descricaodis = $row1["descricao_disciplina"];
   echo ('<td colspan="2"> <div align=center> <b>'.$descricaodis2.'</b></div></td>');
   $arraydis[] = $descricaodis;

}

echo ('</tr> <tr bgcolor="#CCCCCC">');
echo ('<td align="center">&nbsp;</td>');
$dnota = 'N';
$dfalta = 'F';


$query1 = consulta_sql($sql1);

if(is_string($query1))
{
   echo $query1;
   exit;
}

//Titulo da tabela com as faltas e as notas por codigo de diario
while($row1=pg_fetch_array($query1)) {
   echo ('<td align=center><b>&nbsp;&nbsp;&nbsp;'.$dnota.'&nbsp;&nbsp;&nbsp;</b></td>');
   echo ('<td align=center><b>&nbsp;&nbsp;'.$dfalta.'&nbsp;&nbsp;</b></td>');
}

$totd = count($arraydis);
$totm = $totd;

reset($arraydis);

$i = 0;
$cor = $cor2;

echo ("</tr> <tr bgcolor=$cor>");

$ultdis = $arraydis[$i];

$ultalu = "*";

$i = 100;

while($row1 = pg_fetch_array($query2)) {

   $nomealu = $row1["nome"];
   $nomedisc = $row1["disc"];
   $notas = $row1["notas"];
   $faltas = $row1["num_faltas"];
   
   //if($notas == '') { $notas = 'nulo';}
   //if($faltas == '') { $faltas = 'nulo';}

   if($notas > 0) {  $notas = getNumeric2Real($notas); }
   
   //$notas = getNumeric2Real($notas);
   
   if ($ultalu != $nomealu) 
   {
		if ($i < $totm) {
			while ($i < $totm) {
               echo("<td align=center>  </td>");
               $i = $i + 1;
            }
        }
		
        if ($cor=="#FFFFFF") { $cor=$cor1;} else { $cor=$cor2;}
		
        echo("</tr> <tr bgcolor=$cor>");
        echo ("<td width=\"250\">$nomealu</td>");
        $ultalu = $nomealu;
        $i = 0;
        reset($arraydis);
   }
	
  // antes
   while ($nomedisc != $arraydis[$i]) {
       echo("<td align=center>  </td>");
       $i = $i + 1;
   }
   
   if ($nomedisc == $arraydis[$i]) {
   	  echo ("<td align=center>$notas</td>");
	  echo ("<td align=center>$faltas</td>");
   } 
   else {
       echo("<td align=center>  </td>");
   }
   $i = $i + 1;

}
?>
</tr> 
</table> 

<p>
<input type="button" value="Imprimir" onClick="window.print()">
</p>

</body>
</html>