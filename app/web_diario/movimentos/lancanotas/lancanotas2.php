<?php
require_once('../../webdiario.conf.php');


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];
$getcurso = $_GET['curso'];

$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);
$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);



// ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO
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
            id_ref_pessoas IS NULL AND
			(m.dt_cancelamento is null) AND
			(m.ref_motivo_matricula = 0)

        ORDER BY
                id_ref_pessoas;';

$qry = consulta_sql($qryNotas);

if(is_string($qry))
{
	echo $qry;
    exit;
}

//-- Conectando com o PostgreSQL
// FIXME: migrar para conexao ADODB
if(($conn = pg_Pconnect("host=$host user=$user password=$password dbname=$database")) == false)
{
   $error_msg = "Não foi possível estabeler conexão com o Banco: " . $database;
}

require_once('../../../matricula/atualiza_diario_matricula.php');

while($registro = pg_fetch_array($qry))
{
    $ref_pessoa = $registro['ref_pessoa'];
    atualiza_matricula("$ref_pessoa","$getofer");
}

// ^ ATUALIZA NOTAS E FALTAS CASO O DIARIO TENHA SIDO INICIALIZADO ^//

?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<style>
body{
	font-family:Geneva, Arial, Helvetica, sans-serif;
	font-size:12px;
}
</style>
<link href="../../style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div align="center" class="titulo">
  <p>Lan&ccedil;amento / Altera&ccedil;&atilde;o de Notas<br>
  </p>
</div>
</h3><font color="#330099">* Professor, conforme descrito pela f&oacute;rmula abaixo voc&ecirc; ter&aacute; no m&aacute;ximo seis (6) notas para lan&ccedil;ar.<br /> 
* Estas notas representam as avalia&ccedil;&otilde;es aplicadas durante o per&iacute;odo (Provas, Trabalhos, Relatórios, Monitorias, etc).<br /> * As notas (de uma a seis) ser&atilde;o somadas e o total final n&atilde;o poder&aacute; exceder a 100 pontos!
<br /> 
* Para nota de <font color="red">recupera&ccedil;&atilde;o/reavalia&ccedil;&atilde;o</font> utilize a op&ccedil;&atilde;o "Nota Extra" na lista de "Lan&ccedil;amento referente &agrave;". 
<br />
* <font color="red">IMPORTANTE:</font> Uma vez lan&ccedil;ada a "Nota Extra" as notas de 1 a 6 n&atilde;o poder&atilde;o ser alteradas!
</font>
<br>
<br />
<?php

  echo getHeaderDisc($getofer);

?>

<br>
<br>
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Voc&ecirc; tem a seguinte f&oacute;rmula cadastrada :</font>

<?php

$sql1 = "SELECT DISTINCT
                formula
                FROM
                diario_formulas
                WHERE
                grupo ILIKE '$grupo_novo';";
                
$qry1 = consulta_sql($sql1);

if(is_string($qry1))
{
  echo $qry1;
  exit;
}

$sql2 = "SELECT
                id,
                prova,
                descricao
                FROM
                diario_formulas
                WHERE
                grupo ILIKE '$grupo_novo'
				ORDER BY descricao;";
//echo $sql2;
//exit;
     
$qry2 = consulta_sql($sql2);

if(is_string($qry2))
{
  echo $qry2;
  exit;
}



if ((isset($id)) && (isset($getperiodo)) && (isset($getdisciplina))) 
{
  if (pg_numrows($qry1) > 0) 
  { 
     echo '<table width="400">
      <tr bgcolor="#CCCCCC">
      <td width="300"><b>Descrição</b></td>
      <!--<td width="20"><b>Ação</b></td>-->
    </tr>';
	$st = '';
	while ($row1 = pg_fetch_array($qry1)) 
	{
		if($st == '#F3F3F3') 
		{
			$st = '#E3E3E3';
		} 
		else 
		{
			$st ='#F3F3F3';
		}
		$qdesc = $row1['formula'];
		
		echo '<tr bgcolor="'.$st.'"><td>'.$qdesc.'</td></tr>';
		
    }
  } 
  else 
  {
    echo '<strong><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">N&atilde;o existe f&oacute;rmula ou provas cadastradas !</font></strong>';
   }
  //  pg_close($dbconnect);
}
else 
{
      print ("A consulta Falhou ! , Contate o suporte técnico !");
      print ($id);
      print ($getperiodo);
      print ($getdisciplina);
}
?>
<table width="92%" border="0">
    <td width="31%" height="23"> 
  <tr> 
    <form name="envia" id="envia" method="post" action="inputnotas.php">
      <td height="20" colspan="3"><div align="left">Lan&ccedil;amento referente à : 
<?php


   echo '<input type="hidden" name="id" id="id" value="' .$_SESSION['id'].'">';
   echo '<input type="hidden" name="periodo" id="periodo" value="' . $getperiodo.'">';
   echo '<input type="hidden" name="disc" id="disc" value="' .$getdisciplina.'">';
   echo '<input type="hidden" name="ofer" id="ofer" value="' . $getofer.'">';
   echo '<input type="hidden" name="curso" id="curso" value="' . $getcurso.'">';
 	
   	print("<select name=\"getprova\" class=\"select\">
  		<option value=\"A\" selected>--- selecione ---</option>");
          while ($row2 = pg_fetch_array($qry2)) 
         {
            $qid = $row2['prova'];
            $qdesc = $row2['descricao'];
            print "<option value=$qid>$qdesc</option>";
         }
		 print "<option value=7>Nota Extra</option>";

         print("</select>"); 
?>
          
&nbsp;&nbsp;<input type="submit" name="Submit" value="Lan&ccedil;ar notas -->">
        </div></td>
    </form>
</table>


</body>
</html>
