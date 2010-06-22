<?php
include_once ('../../webdiario.conf.php');

$var = explode(":",$_GET[getdisciplina]);
$getdisciplina = $var[0];
$getofer = $var[1];

// VARS

$sql1 ="SELECT
      a.id,
      a.aula,
      b.nome,
      b.ra_cnec
      FROM
      diario_chamadas a, pessoas b
      WHERE
      a.data_chamada = '$senddata' AND
      a.ref_professor = '$idprofessor' AND
      a.ref_periodo = '$sendperiodo' AND
      a.ref_curso = '$sendcurso' AND
      a.ref_disciplina = $senddisciplina AND
      a.ra_cnec = b.ra_cnec AND
	  a.abono = 'N'
      ORDER BY b.nome, a.aula";
$query1 = pg_exec($dbconnect, $sql1);
   
$sql9 ="SELECT
               a.descricao as cdesc,
               b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
               c.descricao as perdesc
               FROM
               cursos a,
               disciplinas b,
               periodos c,
               disciplinas_ofer d
               WHERE
               a.id = '$sendcurso' AND
               b.id = '$senddisciplina' AND
               d.id = '$getofer' AND
               c.id = '$sendperiodo'";
$query9 = pg_exec($dbconnect, $sql9);

while($linha9 = pg_fetch_array($query9)) {
               $exibecurso = $linha9["cdesc"];
               $exibedisc  = $linha9["descricao_extenso"];
               $exibeper   = $linha9["perdesc"];
                }
pg_close($dbconnect);
?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<?php print('<form name="form1" method="post" action="concluiabono.php?id=' . $idprofessor. '&periodo=' . $sendperiodo. '&curso=' . $sendcurso. '&disciplina=' . $senddisciplina. '&data=' . $senddata.'">');?>
<table width="92%" border="0">
  <tr> 
    <td width="31%"><div align="center">Curso :</div></td>
    <td width="31%">
<div align="center">Disciplina :</div></td>
    <td width="31%">
<div align="center">Periodo :</div></td>
  </tr>
  <tr> 
    <td><div align="center"><strong><?php print ($exibecurso); ?></strong></div></td>
    <td><div align="center"><strong><?php print ($exibedisc); ?></strong></div></td>
    <td><div align="center"><strong><?php print ($exibeper); ?></strong></div></td>
  </tr>
</table>
<p></p> </p>
<table width="92%" border="0">
  <tr bgcolor="#666666">
                                          
    <td width="9%" align="center"><font color="#FFFFFF"><strong>Abonar</strong></font></td>
    <td width="9%" align="center"><font color="#FFFFFF"><strong>Aula</strong></font></td>
                                          <td width="10%"><font color="#FFFFFF"><b>&nbsp;Matr&iacute;cula</b></font></td>
                                          <td width="76%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
                                      </tr>
                       <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["ra_cnec"];
                                   $result3 = $linha1["aula"];
                                   $result4 = $linha1["id"];
						           $result = $linha1["nome"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print (' <tr bgcolor="'.$st.'">
                                                       <td align="center">
                                                           <input type="checkbox" class="checkbox" name="nomes[]" value="'.$result4.'">
                                                       </td>
                                                       <td align="center">
                                                           '.$result3.'
                                                       </td>
                                                       <td>
                                                           '.$result2.'
                                                       </td>
                                                       <td>
                                                           '.$result.'
                                                       </td>
                                                   </tr>');
					   }
                    	?>
                        </table><br>
<input type="submit" name="Submit" value="Abonar Falta --&gt;">
</form>
</body>
</html>