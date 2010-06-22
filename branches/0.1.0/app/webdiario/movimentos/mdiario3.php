<?php
if ($nomes != "")
 {
   reset ($nomes);
   while ($array_cell = each($nomes)) {
	$valor = $array_cell['value'];
	print ($valor.'<br>') ;
	print ($semestre);
	print ($curso);
	print ($disciplina);
	print ($nomeprofessor);
//	$banco = "insert into teste value(NULL,'$valor')";
		}
  }
?>