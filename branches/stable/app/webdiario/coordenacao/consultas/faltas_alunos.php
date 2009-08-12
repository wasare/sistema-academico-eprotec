<?php
/*
====================================
DESENVOLVIDO SOBRE LEIS DA GNU/GPL
====================================

E-CNEC : ti@cneccapviari.br

CNEC CAPVIARI - www.cneccapivari.br
Rua Barão do Rio Branco, 347, Centro - Capivari/SP
Tel.: (19)3492-1869
*/
$st = '#F3F3F3';
include_once('../../webdiario.conf.php');


//https://sagu.cefetbambui.edu.br/diario2/secretaria/consultas/faltas_alunos.php?ras=1306&nome=Jovelino%20Jos%E9%20%20Alves%20da%20Silva

$nomes = $_GET['nomes'];
$ras = $_GET['ras'];

if(!IsSet($_SESSION['login'])) 
{
   header("location:$erro");
   exit;
} 
else 
{

/* ////////////////////////////////////////////////////////////////////



  // MONTA OS CAMPOS DE DISCIPLINAS E NOTAS
     $sql2= "SELECT
             d.id,
             d.descricao_disciplina as descricao,
             m.num_faltas as faltas,
             m.nota_exame as nota_exame,
             m.nota as nota
             FROM
             matricula m, disciplinas d
             WHERE
             m.ref_pessoa = '$idpessoa' AND
             m.ref_periodo = '$classe' AND
             d.id = m.ref_disciplina";
      $query2 = pg_exec($dbconnect, $sql2);

           // Calcula linha para Moldura e exibição
           $registros = pg_NumRows($query2);
           $fimmateria = 554;
           for($fim=1; $fim<$registros; $fim++) {
            $fimmateria = $fimmateria - 11;      }




           // imprime os caras registros
             $linhamaterias = 559;
	         while($row2 = pg_fetch_array($query2))
	        	{
	        	$id = $row2["id"];
        		$nomemateria = $row2["descricao"];
        		$notamateria = $row2["nota"];
        		$notaexame = $row2["nota_exame"];
        		$faltasmateria = $row2["faltas"];
        		if ($notamateria==''){
        		$notamateria='****';   }
        		if ($faltasmateria==0){
        		$faltasmateria='-';   }

        		// CALCULA A HORA AULA PREVISTA
                $sql3 = "SELECT
                id,
                carga_horaria
                FROM disciplinas
                WHERE
                id = '$id'";
                $query3 = pg_exec($dbconnect, $sql3);
                while($row3 = pg_fetch_array($query3)) {
        		$cargahoraria = $row3["carga_horaria"];
        		}

        		// CALCULA HORA AULA DADA
        		   $sqlflag="select
                   flag
                   from diario_seq_faltas
                   where periodo='$classe' and disciplina='$id'";
                   $queryflag=pg_exec($dbconnect, $sqlflag);

                   while ($rowflag=pg_fetch_array($queryflag)) {
                   $flags=$rowflag["flag"];
                   if ($flags == "") {
                   $result=$flags;
                   } elseif ($flags != "") {
                   $result=$result+$flags;
                   }
                   }



//////////////////////////////////////////////////////////////////////// */

//Seleciona o nome dos alunos curso e descrição
/////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");
$sql1 = "SELECT
             d.id,
             d.descricao_disciplina as descricao,
             d.carga_horaria,
             m.ref_periodo,
             m.num_faltas as faltas,
             m.nota_final as nota_final,
             m.nota as nota,
             m.ref_disciplina_ofer as oferecida
             FROM
             matricula m, disciplinas d, pessoas p
             WHERE
             m.ref_pessoa = p.id AND p.ra_cnec = '$ras' AND
             m.dt_matricula >= '2004-01-01' AND
             d.id = m.ref_disciplina order by 2";
             
$query1 = pg_exec($dbconnect, $sql1);



?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<body>
<br>
<?php if (pg_numrows($query1) > 0) { ?>
      <font color="#000000" size="2"><b>Matr&iacute;cula: </b><?php echo($ras);?><b> Nome: </b><?php echo($nome);?></font><br><br>
      <table width="80%">
             <tr bgcolor="#666666">
                  <td width="40%"><div align="center"><font color="#FFFFFF"><b>Disciplina</b></font></div></td>
                  <td width="20%"><div align="center"><font color="#FFFFFF"><b>Média</b></font></div></td>
                  <td width="20%"><div align="center"><font color="#FFFFFF"><b>Faltas</b></font></div></td>
				  <td width="20%"><div align="center"><font color="#FFFFFF"><b>% 
        Faltas</b></font></div></td>
				  <td width="20%"><div align="center"><font color="#FFFFFF"><b>Aulas 
        Dadas</b></font></div></td>
  				  <td width="20%"><div align="center"><font color="#FFFFFF"><b>Aulas
        Previstas</b></font></div></td>

              </tr>
         <?php
           while($row2 = pg_fetch_array($query1))
           {
               $did = $row2["id"];
               $nomemateria = $row2["descricao"];
               $notafinal = $row2["nota_final"];
               $faltasmateria = $row2["faltas"];
               $classe = $row2["ref_periodo"];
               $aulaprev = $row2["carga_horaria"];
               $oferecida = $row2["oferecida"];
               
               if ($faltasmateria == 0)
               {
        		$faltasmateria='-';   
               }
/*
                 $sqlflag = "SELECT
                                 flag
                              FROM diario_seq_faltas
                   where periodo = '$classe' and disciplina='$id'";
                   $queryflag=pg_exec($dbconnect, $sqlflag);

                   while ($rowflag=pg_fetch_array($queryflag)) {
                   $flags=$rowflag["flag"];
                   if ($flags == "") {
                   $result=$flags;
                   } elseif ($flags != "") {
                   $result=$result+$flags;
                   }
                   }
*/                  
 $sqlflag ="
            SELECT 
                  SUM(CAST(flag AS INTEGER)) AS carga
               FROM 
                  diario_seq_faltas 
               WHERE 
                  periodo = '$classe' AND 
                  disciplina = '$did' AND 
                  ref_disciplina_ofer = $oferecida; ";
                  
       //echo '<br />'.$sqlflag; 
      // and ref_disciplina_ofer = '$getofer'
       $queryflag = pg_exec($dbconnect, $sqlflag);
       
       $rowflag = pg_fetch_array($queryflag);
       
       $result = $rowflag[carga];

                   if ($result <> ""){
                        $perfaltas = ($faltasmateria * 100) / $result;
                        $pfaltas = substr($perfaltas,0,5);
                        $stfaltas=$pfaltas . ' %';
                        }
                   else {$pfaltas = '-'; $stfaltas=$pfaltas;}
                   if ($pfaltas > 25) { $fcolor="#FF0000";} else {$fcolor="#000000";}
             print ("<tr bgcolor=\"$st\">
                          <td><font color=$fcolor>$nomemateria</td></font>
                          <td align=center><font color=$fcolor>$notafinal</td></font>
                          <td align=center><font color=$fcolor>$faltasmateria</td></font>
                          <td align=center><font color=$fcolor>$stfaltas</td></font>
                          <td align=center><font color=$fcolor>$result</td></font>
                          <td align=center><font color=$fcolor>$aulaprev</td></font>
                     </tr>");
                     unset($result);
      if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
            	}
                 ?></table>
</body>
</html><? } else {
	print("<font color=\"#FF0000\" size=\"2\"><b>Esse aluno não possui notas e faltas</b></font>"); }} ?>
