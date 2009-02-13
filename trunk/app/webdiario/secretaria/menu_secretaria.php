<?php

include_once('../conf/webdiario.conf.php');

require_once($BASE_DIR.'conf/verifica_acesso.php');

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
                d.id = o.ref_disciplina;";

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
<link rel="stylesheet" href="../css/geral.css" type="text/css">

</head>
<body bgcolor="#BCCDE9" text="#CCCCCC" link="#CCCCCC" vlink="#CCCCCC" alink="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<table width="114" border="0">
  <tr>
     <td colspan="2"><div align="center"><font size="1"><a href="../prin.php?y=2003" target="principal"><font color="#000033"><a href="../prin.php" target="principal"><?php echo $_SESSION['login'];  ?></a></font></font></div> <hr /></td>
  </tr> 
 
<?php

 if($_SESSION['nivel'] == 2)
 {
   echo '<tr>                                                                                                 <td colspan="2" height="0"><div align="center"><b><font color="#000033" size="1">SECRETARIA</font></b></div></td>                                                                             </tr>'; 
  }
?>
  <tr>
    <td><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td><a href="select_periodos.php" target="principal"><font color="#000099" size="1">Per&iacute;odo </font></a></td>
  </tr>
  <tr>
    <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="select_profs.php" target="principal"><font color="#000099" size="1">Professor(a)</font></a></td>
  </tr>
  <tr>
      <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
	      <td width="106"><a href="diarios_secretaria.php" target="principal"><font color="#000099" size="1">Di&aacute;rios</font></a>
     </td>
  </tr>

<tr>
   <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
   <td width="106"><a href="consultas/andamento_periodo.php" target="principal"><font color="#000099" size="1">Resumo no Per&iacute;odo</font></a></td>
</tr>


<tr>
    <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="movimentos/atualiza_diarios.php" target="principal"><font color="#000099" size="1">Atualizar Di&aacute;rios</font></a></td>
</tr>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>


  <tr> 
    <td colspan="2"> <div align="center"><b><font color="#000033" size="1">OP&Ccedil;&Otilde;ES</font></b></div></td>
  </tr>

   <tr> 
    <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="../consultas/alunos.php" target="principal"><font color="#000099" size="1">Consultar Aluno</font></a></td>
  </tr>
  <tr> 
    <td><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td><a href="../movimentos/senhas/senha_1.php" target="principal"><font color="#000099" size="1">Trocar Senha </font></a></td>
  </tr>
  <tr> 
    <td width="5"><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td width="106"><a href="../consultas/log_acesso.php" target="principal"><font color="#000099" size="1">Log de Acesso</font></a></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2"><b><font color="#000033" size="1">PROGRAMAS</font></b></td>
  </tr>
  <tr> 
    <td><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td><b><a href="../../gs851w32.exe" target="principal"><font color="#000099" size="1">1 
      - GhostScript</font></a></b></td>
  </tr>
  <tr> 
    <td><img src="../img/menu_seta.gif" width="5" height="5"></td>
    <td><b><a href="../../gsv48w32.exe" target="principal"><font color="#000099" size="1">2 
      - GhostView</font></a></b></td>
  </tr>
  <tr> 
    <td width="5">&nbsp;</td>
    <td width="106">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2"> <div align="center"><font size="1"><a href="../logout.php" target="_parent"><font color="#000099"><strong>Sair</strong></font></a></font></div></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
 </body>
</html>

<?php }
?>
