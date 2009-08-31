<?php
require_once('../webdiario.conf.php');

$ras = $_GET['aluno'];

if(isset($ras) && is_numeric($ras) && $ras != "") {
    $btnOK = true;
}

//Seleciona o Período
$sql1 = "SELECT
             p.nome,
		 	 rua,
			 complemento,
			 bairro,
			 p.cep,
    	     fone_particular,
			 fone_profissional,
			 fone_celular,
			 fone_recado,
			 email,
    		 dt_nascimento,
			 c.nome AS cidade,
             c.ref_estado AS uf
    		 FROM
			 pessoas p, cidade c
             WHERE p.id = $ras AND c.id = ref_cidade;";
             
//$query1 = pg_exec($dbconnect, $sql1);

$qry1 = consulta_sql($sql1);

         while($linha1 = pg_fetch_array($qry1)) {
               $exibenome = $linha1['nome'];
               $exiberua  = $linha1['rua'];

			   $exibecomplemento   = '';
			   if(!preg_match('/'. $linha1['complemento'] .'/i', $exiberua) )
					$exibecomplemento   = ', '. $linha1['complemento'];

               $exibebairro = $linha1['bairro'];
               $exibecep   = $linha1['cep'];
               $exibefoneparticular   = $linha1['fone_particular'];
               $exibeprofissional   = $linha1['fone_profissional'];
               $exibefonecelular   = $linha1['fone_celular'];
               $exibefonerecado   = $linha1['fone_recado'];
               $exibeemail   = $linha1['email'];
               $exibedatanasc = br_date($linha1['dt_nascimento']);
			   $cidade = $linha1['cidade'];
			   $estado = $linha1['uf'];
       }
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
</head>

<body>
<table width="631" border="0">

 <th rowspan="10">
                     <img title="<?php echo($exibenome); ?>" src="foto.php?p=<?php echo($ras); ?>" alt="<?php echo($exibenome);?>" border="1" width="120" />
					                           </th>
  <tr>
  
							  
    <td><font color="#0000FF">Nome do Aluno (a) : <strong><font color="#000000"><?php print $exibenome; ?></font></strong></font></td>
  </tr>
  <tr> 
    <td><font color="#0000FF">Endere&ccedil;o : <strong><font color="#000000"><?php print $exiberua . $exibecomplemento; ?></font></strong></font></td>
    <td><font color="#0000FF">Bairro : <strong><font color="#000000"><?php print $exibebairro; ?></font></strong></font></td>
    </tr>
    <tr>
	<td><font color="#0000FF">Cidade / UF : <strong><font color="#000000"><?php print $cidade; ?>&nbsp;-&nbsp;<?php print $estado; ?></font></strong></font></td>
    <td><font color="#0000FF">Cep : <strong><font color="#000000"><?php print $exibecep; ?></font></strong></font></td>
  </tr>
  <tr> 
    <td><font color="#0000FF">Fone Particular : <strong><font color="#000000"><?php print $exibefoneparticular; ?></font></strong></font></td>
    <td><font color="#0000FF">Fone Profissional : <strong><font color="#000000"><?php print $exibefoneprofissional; ?></font></strong></font></td>

  </tr>
  <tr> 
    <td><font color="#0000FF">Fone Celular : <strong><font color="#000000"><?php print $exibefonecelular; ?></font></strong></font></td>
    <td><font color="#0000FF">Fone Recado : <strong><font color="#000000"><?php print $exibefonerecado; ?></font></strong></font></td>
  </tr>
  <tr> 
    <td height="21"><font color="#0000FF">E-Mail : <strong><font color="#000000"><?php print $exibeemail; ?></font></strong></font></td>
    <td><font color="#0000FF">Data de Nacimento : <strong><font color="#000000"><?php print $exibedatanasc; ?></font></strong></font></td>
	</tr>
	<tr>
  </tr>

</table>
</body>
</html>
