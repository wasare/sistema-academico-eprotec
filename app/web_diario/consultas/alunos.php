<?php
require_once('../../setup.php');

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

$conn = new connection_factory($param_conn);


$sql1 = "SELECT DISTINCT
      b.nome
      FROM
      diario_usuarios a, pessoas b
      WHERE
      a.login = '". $uid ."'
      AND
      a.id_nome = b.id;";

$nome_completo = $conn->adodb->getOne($sql1);

if($nome_completo === FALSE || !is_string($nome_completo))
{
    die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}


$st = '#F3F3F3';

$btnOK = false;

?>
<html>
<head>
<title><?=$IEnome?> - consulta alunos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">
  
</head>

<body>

<p class="title">Pesquisa de Alunos</p>
  <table width="450">
    <tr>
      <td width="400" bgcolor="#cccccc" colspan="3"><b>&nbsp;Busca de Aluno</b></td>
    </tr>
    <form action="alunos.php" method="post" name="busca">
      <tr>
        <td width="50" valign="botton">Matr&iacute;cula</td>
        <td width="300" valign="botton">Nome</td>
        <td width="50">&nbsp;</td>
      </tr>
      <tr>
        <td width="50" valign="middle">
        <input name="ra"  type="text" maxlenght="8" size="8" value="<?echo $ra; ?>" />
        </td>
        <td width="300" valign="middle">
        <input name="nome"type="text" maxlenght="50" size="50" value="<? echo $nome; ?>" />
        </td>
        <td width="50">
        <input name="btnOK" type="submit" value=" OK ">
        </td>
      </tr>
    </form>
  </table><br>

<?php

if (isset($_POST['btnOK']) && ( trim($_POST['btnOK']) == 'OK')) {
	
	$id = $_SESSION['id'];

	$ra = trim(@$_POST['ra']);
	
	$nome = trim(@$_POST['nome']);

  	$sql1 = 'SELECT  DISTINCT 
      a.nome, a.id, b.ref_curso, d.abreviatura, c.id as contrato
   FROM
      pessoas a, matricula b, contratos c, cursos d
   WHERE
         a.id IN (
                  SELECT 
                     DISTINCT
                        a.ref_pessoa
                     FROM 
                        matricula a ';

  
   	$coordenador = 0;

	//Se é coordenador
	 if($_SESSION['cursosc'] != '' && isset($_SESSION['cursosc'])) {
	 	
			//
			$coordenador = 1;
	 }
	
	//Se é professor (professor = 1)
     if($_SESSION['nivel'] == 1 && $coordenador != 1) {
	 
            $sql1 .=  '  WHERE ref_periodo IN ('.$_SESSION['lst_periodo'].') ';
     }

    
	//Se é secretaria
	//Mostra tudo
	
	
	$sql1 .= ' ORDER BY a.ref_pessoa
         )  AND
      a.id = b.ref_pessoa AND
      c.ref_pessoa = a.id AND
      c.id = b.ref_contrato AND
      b.ref_curso = d.id AND
      c.ref_curso = d.id ';
   

	if(isset($ra) && is_numeric($ra) && $ra != "") {

        $sql1 .= " AND a.ra_cnec = '$ra' ";
        $btnOK = true;
    }

   if(isset($nome) && ($nome != "") && strlen($nome) != 2) {

        $sql1 .= " AND lower(to_ascii(a.nome)) ";
        $sql1 .= " SIMILAR TO lower(to_ascii('$nome%')) ";

        $btnOK = true;
   }

   $sql1 .= " ORDER BY a.nome LIMIT 20 OFFSET -1;"; 

   if(!isset($_SESSION['lst_periodo']) && $_SESSION['lst_periodo'] == "" && strlen($_SESSION['lst_periodo']) < 4) {

        $btnOK = false;
   }
   //echo $sql1; die;



if($btnOK) {
	
	$qry1 = consulta_sql($sql1);

	if(is_string($qry1)) {
		
		echo $qry1;
		exit;
		
	} 
	else {

		if (pg_numrows($qry1) > 0) {

		   echo '<h4><font color="red">Se não obteve o resultado esperado seja mais espec&iacute;fico!</font></h4>';

		   echo '<table  width="80%" cellspacing="0" cellpadding="0" class="papeleta">
		    <tr bgcolor="#CCCCCC">
      			<td width="12%"><b>Matr&iacute;cula</b></td>
      			<td width="45%"><b>Nome</b></td>
				<td width="35%"><b>Curso</b></td>
      			<td width="10%"><b>Ações</b></td>
    		</tr>';


            while($row3 = pg_fetch_array($qry1)) {

		      	if ($st == '#F3F3F3') { $st = '#E3E3E3'; } else { $st ='#F3F3F3'; }

		      	$q3nome = $row3['nome'];
      	    	$q3id = str_pad($row3['id'], 5, "0", STR_PAD_LEFT);
			    $q3curso = $row3['abreviatura'];
				$q3cs  = $row3['ref_curso'];
				$q3contrato = $row3['contrato'];

      			echo '<tr bgcolor="'.$st.'">';
				echo ' <td align="center">'.$q3id.'</td>';
				echo ' <td>'.$q3nome.'</td>';
                echo ' <td align="center">'.$q3curso.'</td>';
				echo ' <td align="center"> <a href="lista_ficha_academica.php?aluno='.$q3id.'&nome='.$q3nome.'&curso='.$q3curso.'&cs='.$q3cs.'&contrato='. $q3contrato .'" target="_blank"> <img src="../img/edit.gif" border="0" title="Ficha Acadêmica"></a>&nbsp; <a href="cadastro_alunos.php?aluno='.$q3id.'">    <img src="../img/compose.gif" border="0" title="Ver Cadastro"></a>&nbsp;</td>';
				echo '</tr>';
    		}

            echo '</table>';
            echo '<br /> <br />';
 

    	} else {
  
			echo '<script language="javascript">window.alert("Não foi encontrado nenhum aluno!"); javascript:window.history.back(1); </script>';

            unset($_POST['nomes']);
        	unset($_POST['ras']);
        	unset($_POST['btnOK']);

        	$_POST = array();

      		die;
		}

    	unset($_POST['nomes']);
    	unset($_POST['ras']);
        unset($_POST['btnOK']);
   
		$_POST = array();
        
	}	
}
else {
			
	echo '<script language="javascript">window.alert("Algum erro impediu que sua consulta fosse realizada!"); javascript:window.history.back(1); </script>';

    unset($_POST['nomes']);
    unset($_POST['ras']);
    unset($_POST['btnOK']);

    $_POST = array();

    die;

}

}

//Lista a variavel de sessao
//print_r($_SESSION['cursosc']);
?>
      
</body>
</html>
