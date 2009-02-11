<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>Unifica Cadastro de Pessoas</title>
<script language="PHP">
  CheckFormParameters(array("id_valido",
                            "id_invalido",
                            "nome_pessoa"));
</script>

<?
Function Unifica_Cadastro($id_valido, $id_invalido, $nome_pessoa)
{
   $conn = new Connection;
   $conn->Open();
   $conn->Begin();

   echo("<br><center><h3>Unificando o cadastro de $nome_pessoa</h3></center><br>");
 
   flush();
   
   /* 1 ========================= MATRICULAS =============================*/
   
   echo("==> Tabela de Matrículas........................");
   
   $sql = "update matricula set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 2 ========================== CONTRATOS =============================*/

   echo("==> Tabela de Contratos.........................");
   
   $sql = "update contratos set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 3 ======================== VEST_INSCRICOES =========================*/

   echo("==> Tabela de vest_inscricoes...................");
   
   $sql = "update vest_inscricoes set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 4 ======================== LIVRO_MATRICULA =========================*/

   echo("==> Tabela de livro_matricula...................");
   
   $sql = "update livro_matricula set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 5 ===================== DISCIPLINAS TODOS OS ALUNOS================*/

   echo("==> Tabela de disciplinas_todos_alunos..........");
   
   $sql = "update disciplinas_todos_alunos set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 6 ======================== PREVISAO_LCTO ===========================*/

   echo("==> Tabela de previsao_lcto.....................");
   
   $sql = "update previsao_lcto set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 7 =========================== TITULOS_CR ===========================*/

   echo("==> Tabela de titulos_cr........................");
   
   $sql = "update titulos_cr set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 8 ============================ SALARIOS ===========================*/

   echo("==> Tabela de salarios..........................");
   
   $sql = "update salarios set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 9 ============================== BOLSAS ===========================*/

   echo("==> Tabela de bolsas............................");
   
   $sql = "update bolsas set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 10 ======================== COBRANCA PESSSOA ======================*/

   echo("==> Tabela de cobranca_pessoa...................");
   
   $sql = "update cobranca_pessoa set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 11 ===================== DISCIPLINAS_OFER_PROF ====================*/

   echo("==> Tabela de disciplinas_ofer_prof.............");
   
   $sql = "update disciplinas_ofer_prof set ref_professor='$id_valido' where ref_professor='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 12 ===================== DISCIPLINAS_OFER_COMPL ===================*/

   echo("==> Tabela de disciplinas_ofer_compl............");
   
   $sql = "update disciplinas_ofer_compl set ref_professor_aux='$id_valido' where ref_professor_aux='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 13 ========================= PROFESSORES =========================*/

   echo("==> Tabela de professores............");
   
   $sql = "update professores set ref_professor='$id_valido' where ref_professor='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 14 ========================= COORDENADORES =======================*/

   echo("==> Tabela de coordenadores............");
   
   $sql = "update coordenadores set ref_professor='$id_valido' where ref_professor='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 15 ============================== LÍDERES  =======================*/

   echo("==> Tabela de líderes............");
   
   $sql = "update lideres set ref_pessoa='$id_valido' where ref_pessoa='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");
   
   flush();

   /* 16 =================== DELETA CADASTROS INVALIDOS =================*/

   echo("==> Deletando cadastro de pessoas duplicado.....");
   
   $sql = "delete from pessoas where id ='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");

   echo("==> Deletando cadastro de filiação duplicado....");
   
   $sql = "delete from filiacao where id ='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");

   echo("==> Deletando cadastro de documentos duplicado..");
   
   $sql = "delete from documentos where ref_pessoa ='$id_invalido'; ";
   
   $ok = $conn->Execute($sql);
   
   echo("<b>&nbsp;&nbsp;OK!!!</b><br>");

   echo("==> Deletando cadastro duplo....................");
   
   $sql = " delete from cadastro_duplo where " .
          " ((id_old = '$id_valido' and id_new ='$id_invalido') or " .
	      "  (id_old = '$id_invalido' and id_new ='$id_valido')) and " .
	      " tipo = 'pessoas'; ";
   
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
     Unifica_Cadastro($id_valido, $id_invalido, $nome_pessoa);
  ?> 
</form>
</body>
</html>
