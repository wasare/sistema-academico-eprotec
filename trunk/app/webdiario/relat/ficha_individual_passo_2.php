<?php
/////include ('../conf/webdiario.conf.php');

/*
$dbuser='root';
$dbpassword='1wcvah12';
$dbname='sagu';


// CONECT NO BANCO
$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");
*/

/*
$dbuser='root';
$dbpassword='';
$dbname='sagu';

$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");
*/

require_once('../conf/conn_diario.php');


// VARS

$sql1 ="SELECT DISTINCT
               a.nome,
               a.id,
               a.ra_cnec,
               b.ordem_chamada
               FROM
               pessoas a,
               matricula b
               WHERE
               b.ref_periodo = '$getclasse' AND
               b.ref_pessoa = a.id
               ORDER BY b.ordem_chamada
               ";
$query1 = pg_exec($dbconnect, $sql1);

?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<?php print('<form name="form1" method="post" action="ficha_individual_passo_3.php?classe=' . $getclasse. '">');?>
</p>
<p><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Classe: 
  <?php print $getclasse; ?></strong></font></p>
<table width="92%" border="0">
  <tr bgcolor="#666666">
                                          
    <td width="9%" align="center"><font color="#FFFFFF"><strong>Selecione o aluno:</strong></font></td>
    <td width="6%" align="center"><font color="#FFFFFF"><strong>N°</strong></font></td>
                                          <td width="10%"><font color="#FFFFFF"><b>&nbsp;RA</b></font></td>
                                          <td width="76%"><font color="#FFFFFF"><b>&nbsp;Nome</b></font></td>
                                      </tr>
                       <?php while($linha1 = pg_fetch_array($query1)) {
                                   $result2 = $linha1["ra_cnec"];
                                   $result3 = $linha1["ordem_chamada"];
						           $result = $linha1["nome"];
								   if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';} 
        			   print (' <tr bgcolor="'.$st.'">
                                                       <td align="center">
                                                           <input type="checkbox" class="checkbox" name="nomes[]" value="'.$result2.'">
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

                        
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>

  
<input type="submit" name="Submit" value="Gerar Ficha Individual &gt;&gt;&gt;">
</form>
</body>
</html>
