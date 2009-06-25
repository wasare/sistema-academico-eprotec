<?php

include_once('webdiario.conf.php');

if ( !IsSet($_SESSION['login']) ) 
{
   header("location:$erro");
   exit;
} 
else 
{ 

if(@$_GET["getperiodo"] != "")
{
   $sql3 = "SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso,
                o.id as idof
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                f.ref_professor = '$id' AND
                o.id = f.ref_disciplina_ofer AND
                o.ref_periodo = '$getperiodo' AND
                o.is_cancelada = 0 AND
                d.id = o.ref_disciplina";

/*
echo $sql3;
exit;
*/

   $query3 = pg_exec($dbconnect, $sql3);
}

?>
<html>
<head>
<title>Menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/geral.css" type="text/css">

</head>
<body bgcolor="#BCCDE9" text="#CCCCCC" link="#000099" vlink="#000099" alink="#000099" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<table width="114" border="0">

  <tr>
     <td colspan="2"><div align="center"><font size="1"><a href="../prin.php?y=2003" target="principal"><font color="#000033"><a href="prin.php" target="principal"><?php echo $_SESSION['login'];  ?></a></font></font></div> <hr /></td>
  </tr>

<?php

 if($_SESSION['nivel'] == 2)
 {
   echo '<tr>                                                                                                 
   	<td colspan="2" height="0"><div align="center"><b><font color="#000033" size="1">SECRETARIA</font></b></div></td>                                                                            
	</tr>'; 
  }
?>
  <tr>

  <tr>
     <td colspan="2" height="0"><div align="center"><b><font color="#000033" size="1">PER&Iacute;ODOS</font></b></div></td>
  </tr>
<?php
		
	getPeriodos($_SESSION['login'],$_SESSION['nivel']);
	
?>
  <tr>
      <td colspan="2">&nbsp;</td>
  </tr>

<?php

 //print_r($_SESSION);
 if($_SESSION['cursosc'] != '' && isset($_SESSION['cursosc'])) {
   echo '<tr>
   			<td colspan="2" height="0">
				<div align="center"><b><font color="#000033" size="1">COORDENA&Ccedil;&Atilde;O</font></b></div>
			</td>
		</tr>';
		
/*Variáveis de Sessao 
Array ( 
[nivel] => 1 
[login] => santiago 
[id] => 819 
[lst_periodo] => '07','07021','07022','0702' 
[cursosc] => 307 )
*/
  
?>
  <tr>
    <td><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td><a href="coordenacao/select_periodos.php" target="principal"><font color="#000099" size="1">Per&iacute;odo </font></a></td>
  </tr>
  <tr>
    <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="coordenacao/select_cursos.php" target="principal"><font color="#000099" size="1">Cursos</font></a></td>
  </tr>
  <tr>
      <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
          <td width="106"><a href="coordenacao/diarios_coordenacao.php" target="principal"><font color="#000099" size="1">Di&aacute;rios</font></a>
     </td>
  </tr>

 <tr>
      <td colspan="2">&nbsp;</td>
 </tr>
<?php
	
}

?>
	   
 <!-- <tr> 
    <td width="5"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="consultas/diario_de_classe/index_prof.php?us=$us" target="principal"><font color="#FFFFCC" size="1">Diï¿½rio 
      de Classe</font></a></td>
  </tr></tr>-->
  
  <!--  <tr> 
    <td width="5"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="movimentos/lancanotas/lanca1.php" target="principal"><font color="#FFFFCC" size="1">Lan&ccedil;ar 
      Nota</font></a></td>
  </tr>
  <tr> 
    <td width="5"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="movimentos/altera_%20notas/altera1.php" target="principal"><font color="#FFFFCC" size="1">Alterar 
      Nota</font></a></td>
  </tr>
-->
  <tr> 
    <td colspan="2"> <div align="center"><b><font color="#000033" size="1">OP&Ccedil;&Otilde;ES</font></b></div></td>
  </tr>
   <tr> 
    <td width="5"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="consultas/alunos.php" target="principal"><font color="#000099" size="1">Consultar Aluno</font></a></td>
  </tr>
<!--  <tr> 
    <td width="5" height="13"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="consultas/notas_faltas/consulta.php" target="principal"><font color="#FFFFCC" size="1">Todas 
      Disciplinas</font></a></td>
  </tr>
  <tr>
    <td height="13"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td><a href="consultas/notas_faltas/consultapordisciplina1.php" target="principal"><font color="#FFFFCC" size="1">Sele&ccedil;&atilde;o 
      de Disciplina</font></a></td>
  </tr>-->
  
 <!-- <tr> 
    <td><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td><a href="consultas/cargahoraria_1.php" target="principal"><font color="#FFFFCC" size="1">Carga 
      Hor&aacute;ria </font></a></td>
  </tr>-->
  <tr> 
    <td><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td><a href="movimentos/senhas/senha_1.php" target="principal"><font color="#000099" size="1">Trocar Senha </font></a></td>
  </tr>
  <tr> 
    <td width="5"><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="consultas/log_acesso.php" target="principal"><font color="#000099" size="1">Log de Acesso</font></a></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2"><b><font color="#000033" size="1">PROGRAMAS</font></b></td>
  </tr>
  <tr> 
    <td><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td><b><a href="docs/gs851w32.exe" target="principal"><font color="#000099" size="1">1 
      - GhostScript</font></a></b></td>
  </tr>
  <tr> 
    <td><img src="img/menu_seta.gif" width="5" height="5"></td>
    <td><b><a href="docs/gsv48w32.exe" target="principal"><font color="#000099" size="1">2 
      - GhostView</font></a></b></td>
  </tr>
  <tr> 
    <td width="5">&nbsp;</td>
    <td width="106">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2"> <div align="center"><font size="1"><a href="logout.php" target="_parent"><font color="#000099"><strong>Sair</strong></font></a></font></div></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
 </body>
</html>

<?php }
?>
