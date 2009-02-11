<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>Unifica Cadastro de Instituições</title>
<script language="PHP">
CheckFormParameters(array("id_valido",
                          "id_invalido",
                          "nome_instituicao"));
</script>

<?
Function Unifica_Cadastro($id_valido, $id_invalido, $nome_instituicao)
{
   $conn = new Connection;
   $conn->Open();
   $conn->Begin();

   echo("<br><center><h3>Unificando o cadastro de $nome_instituicao</h3></center><br>");
 
   flush();

   /* 1 =========================== PESSOAS =============================*/
   
   echo("==> Tabela de Pessoas........................");
   
   $sql = "update pessoas set escola_2g='$id_valido' where escola_2g='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
 
   $sql = "update pessoas set escola_1g='$id_valido' where escola_1g='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
  
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();


   /* 2 ========================= MATRICULAS =============================*/
   
   echo("==> Tabela de Matrículas........................");
   
   $sql = "update matricula set ref_instituicao='$id_valido' where ref_instituicao='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 3 ========================= VESTIBULAR =============================*/
   
   echo("==> Tabela de Vestibular .......................");
   
   $sql = "update vestibular set instituicao='$id_valido' where instituicao='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();
   
   /* 4 =================== DELETA CADASTROS INVALIDOS =================*/

   echo("==> Deletando cadastro de instituição duplicado.....");
   
   $sql = "delete from instituicoes where id ='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");

   echo("==> Deletando cadastro duplo....................");
   
   $sql = " delete from cadastro_duplo where " .
          " ((id_old = '$id_valido' and id_new ='$id_invalido') or " .
	  "  (id_old = '$id_invalido' and id_new ='$id_valido')) and " .
	  " tipo = 'instituicoes'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");

   echo("<br><center><h3>Cadastro Unificado com sucesso!!!</h3></center><br>");
   
   $conn->Finish();
   $conn->Close();

}
?>
</head>

<body bgcolor="#FFFFFF">
<form method="post" action="">
  <?php 
     Unifica_Cadastro($id_valido, $id_invalido, $nome_instituicao);
  ?> 
</form>
</body>
</html>
