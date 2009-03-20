<?php
include_once('../webdiario.conf.php');

// CONECT NO BANCO
///////////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

// VARS
$sql2 = "select cudesc, curso from curso";
$query2 = pg_exec($dbconnect, $sql2);

$sql1 = "select descricao, id from semestre";
$query1 = pg_exec($dbconnect, $sql1);

$sql3 = "select descdisciplinas, id  from disciplinas";
$query3 = pg_exec($dbconnect, $sql3);

$sql4 = "select id, nome from professores";
$query4 = pg_exec($dbconnect, $sql4);

$sql5 = "select id, escola from escola";
$query5 = pg_exec($dbconnect, $sql5);

//$sql5 = "select ra, nome from alunos where nome like '%$nomes%' and ra like '%$ras%'";
//$query5 = pg_exec($dbconnect, $sql5);
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<form name="form1" method="post" action="mdiario2.php">
  <table width="511" border="0" height="71">
    <tr> 
      <td height="126"> 
        <table width="100%" border="0" height="145">
          <tr bgcolor="#CCCCCC"> 
            <td colspan="5"> 
              <div align="center"><font size="2"><b class="menu">Montagem do Di&aacute;rio 
                de Classe</b></font></div>
            </td>
          </tr>
          <tr> 
            <td width="17%">Semestre:</td>
            <td colspan="4"> 
              <select name="semestre" class="select">
                <?php while($linha1 = pg_fetch_array($query1)) { 
						           $semestre = $linha1["descricao"]; 
        			   print "<option value='$semestre'>$semestre</option>";}
						?>
              </select>
            </td>
          </tr>
          <tr> 
            <td width="17%">Curso:</td>
            <td colspan="4"> 
              <select name="curso" class="select">
                <?php while($linha2 = pg_fetch_array($query2)) { 
						           $curso = $linha2["cudesc"]; 
        			   print "<option value='$curso'>$curso</option>";}
						?>
              </select>
            </td>
          </tr>
          <tr> 
            <td width="17%">Disciplina:</td>
            <td colspan="4"> 
              <select name="disciplina" class="select">
                <?php while($linha3 = pg_fetch_array($query3)) { 
						           $disciplina = $linha3["descdisciplinas"]; 
        			   print "<option value='$disciplina'>$disciplina</option>";}
						?>
              </select>
            </td>
          </tr>
          <tr>
            <td width="17%">Nome do Professor:</td>
            <td colspan="4">
              <select name="select" class="select">
                <?php while($linha4 = pg_fetch_array($query4)) { 
						           $nprof = $linha4["nome"]; 
        			   print "<option value='$nprof'>$nprof</option>";}
						?>
              </select>
            </td>
          </tr>
          <tr> 
            <td width="17%">Escola:</td>
            <td colspan="4"> 
              <select name="escola" class="select">
                <?php while($linha5 = pg_fetch_array($query5)) {
						           $escola = $linha5["escola"];
        			   print "<option value='$escola'>$escola</option>";}
   pg_close($dbconect);
                        ?>
              </select>
            </td>
          </tr>
          <tr> 
            <td width="17%" height="7">&nbsp;</td>
            <td width="21%" height="7">&nbsp;</td>
            <td width="23%" height="7"> 
              <input type="submit" name="Post" value="Pr&oacute;ximo">
            </td>
            <td width="19%" height="7">&nbsp;</td>
            <td width="20%" height="7">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
