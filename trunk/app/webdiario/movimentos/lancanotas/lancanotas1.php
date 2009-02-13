<?php

include_once('../../conf/webdiario.conf.php');


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];


$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);


$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);


/*
// VAR CONSULTA
$sql9 = "SELECT
         a.descricao as cdesc,
         b.descricao_extenso,
         c.descricao as perdesc,
         d.ref_curso
         FROM
          cursos a,
          disciplinas b,
          periodos c,
          disciplinas_ofer d  where
          d.ref_periodo = '$getperiodo' AND
          b.id = '$getdisciplina' AND
          c.id = '$getperiodo' AND
          d.id = '$getofer' AND
          a.id = d.ref_curso;";
          
          
//echo $sql9;
//exit;

$qry9 = consulta_sql($sql9);

if(is_string($qry9))
{
	echo $qry9;
	exit;
}

while($linha9 = pg_fetch_array($qry9)) 
{
   $curso   = $linha9["ref_curso"];
}

*/
$getcurso = getCurso($getperiodo,$getdisciplina,$getofer);

$sql1 = "SELECT
         grupo
         FROM diario_formulas
         WHERE
         grupo ILIKE '$grupo_novo';";
                
$query1 = pg_exec($dbconnect, $sql1);

$numreg = pg_NumRows($query1);

//numprovas

/*
for ($cont=1; $cont <= $numprovas; $cont++) 
{
   print('<tr bgcolor="#E6E6E6"> 
      <td><div align="center"><strong><font color="#FF0000" size="2"><em>P'.$cont.'</em></font></strong></div></td>
      <td><input name="prova[]" type="text" value="Prova '.$cont.'" size="80" maxlength="80"></td>
    </tr>'); 
}
	
    print("<input type=\"hidden\" name=\"numprovas\" value=\"$numprovas\">");
    print("<input type=\"hidden\" name=\"id\" value=\"$id\">");
    print("<input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">");
    print("<input type=\"hidden\" name=\"getcurso\" value=\"$getcurso\">");
    print("<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">");

    prova[0]
    prova[1]
    prova[2]
    prova[3]

<input name="formula" type="text" size="70"> </td>
<?php
    print("<input type=\"hidden\" name=\"numprovas\" value=\"$numprovas\">");
    print("<input type=\"hidden\" name=\"id\" value=\"$id\">");
    print("<input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">");
    print("<input type=\"hidden\" name=\"getcurso\" value=\"$getcurso\">");
    print("<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">");
    print("<input type=\"hidden\" name=\"grupo\" value=\"$grupo\">");
?>



*/
if($numreg == 0) 
{

/*
   print ('<html> <body>
                    <SCRIPT LANGUAGE="JavaScript">
              	    self.location.href = "cformula_step_1.php?id=' . $id. '&getcurso=' . $getcurso. '&getdisciplina=' . $getdisciplina. '&getperiodo=' . $getperiodo. '"
             	    </script>
                    </body>
                    </html>');
*/

// PASSO 1
$numprovas = 6;

// PASSO 2
for ($cont=1; $cont <= $numprovas; $cont++) 
{
   $prova[] = 'Nota '.$cont;
}

// PASSO 3
$sqldel = "BEGIN; DELETE FROM diario_formulas WHERE grupo ILIKE '$grupo_novo';";
$sqldel .= "DELETE FROM diario_notas WHERE rel_diario_formulas_grupo ILIKE '$grupo_novo'; COMMIT;";

$qrydel =  consulta_sql($sqldel);

if(is_string($qrydel))
{
	echo $qrydel;
	exit;
}

reset($prova);

$sql1 = 'BEGIN;';

while (list($index,$value) = each($prova)) 
{
   $descricao_prova = $prova[$index];
   $num_prova=($index+1);
   $frm='P1';
   $sql1 .= "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values('$id','$getperiodo','$getdisciplina','$num_prova','$descricao_prova','$frm','$grupo');";
   
//   $query1 =  pg_exec($dbconnect, $sql1);
//print ("<BR>".$sql1."<BR>");

}

$sql1 .= 'COMMIT;';

$qry1 = consulta_sql($sql1);

if(is_string($qry1))
{
	echo $qry1;
	exit;
}


$formula = '';

for ($cont = 1; $cont <= $numprovas; $cont++) 
{
   if($cont == 1)
   {
      $formula .= 'P'.$cont;  
   }
   else 
   {
      $formula .= '+P'.$cont;
   }   
}


//$formula = 'P1+P2+P3+P4';

// PASSO 4 E FINAL
include_once('processa_formula.php');


} 
else 
{
  echo '<html> <body> <script type="text/javascript"> self.location.href = "lancanotas2.php?id=' . $id. '&disc=' . $getdisciplina . '&ofer=' . $getofer. '&getperiodo=' . $getperiodo. '&curso=' . $curso. '" </script> </body> </html>';
}

?>
