<?php
require_once('../webdiario.conf.php');

// VARS

$sql1 ="select e.escola,
       e.id,
       ca.diasdasemana,
       ca.escola,
       ca.diasnumericos,
       ca.descricao from
         escola e, calendarioacademico ca
         where e.escola = '$escola'
         and e.id = ca.escola";
$query1 = pg_exec($dbconnect, $sql1);
   pg_close($dbconnect);
?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<?php print('<form name="form1" method="post" action="mdiario3.php?semestre=' . $semestre. '&curso=' . $curso. '&disciplina=' . $disciplina. '&nomeprofessor=' . $nomeprofessor. '">');?>
                     <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["diasnumericos"];
						           $result = $linha1["diasdasemana"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print ('<table width="100%" border="0"><td bgcolor="'.$st.'"><input type="checkbox" class="checkbox" name="nomes[]" value="'.$result2.'">'.$result2.' - '.$result.'</table></td>');
					   }
                    	?>
  <input type="submit" name="Submit" value="Pr&oacute;ximo">
</form>
</body>
</html>
