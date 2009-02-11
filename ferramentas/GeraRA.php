<?php


for ( $i = 4117 ; $i <= 4332 ; $i++ ) {

	$qry .= "UPDATE pessoas SET ";
  $qry .= " ra_cnec = $i WHERE id = $i ;";

  $qry .= " INSERT INTO documentos (ref_pessoa) VALUES ($i); ";

/*
  $pw = str_pad($i, 5, "0", STR_PAD_LEFT);
    
  $qry .= 'INSERT INTO "AcessoAluno"("AlunoID", "cvSenha") ';
  $qry .= ' VALUES('.$i.', md5(\''.$pw.'\'));';
  $qry .= "<br /> <br />";
*/

/*  $qry .= "INSERT INTO documentos (ref_pessoa) VALUES ( ";
  $qry .= " $i) ;<br />";
*/
}

echo "<br />$qry<br />";



?>