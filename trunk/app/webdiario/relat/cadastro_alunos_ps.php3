<? require("/var/www/sagu/html/conf/common.php3"); ?>
<? require("/var/www/sagu/html/conf/GetPessoaNome.php3"); ?>
<? require("/var/www/sagu/html/conf/GetField.php3"); ?>
<? require("/var/www/sagu/html/relat/pslib.php"); ?>
<? require("/var/www/sagu/html/conf/academico/histlib.php"); ?>
<? require("/var/www/sagu/html/conf/InvData.php3"); ?>
<html>
<head>
<title>Cadastro Geral</title>
</head>
<body  bgcolor="#FFFFFF">
<script Language="Javascript">
var NOVAWIN = window.open("/aguarde.html", "NOVAWIN", "status=no,toolbar=no,location=no,menu=no,scrollbars=no,width=260,height=105,left=280,top=235");
</script>

<?php

   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\">Cadastro de Alunos<BR> </font>");

   flush();
   $nr_pag = 1;
   $data = date("d\/m\/y");
   //======================== DECLARA NOME DO ARQUIVO PS DESTINO
   $nome_arq_ps = "ps/cadastro_geral_alunos.ps";
   $myfile_ps = fopen($nome_arq_ps,"w");

   //========================= ABRE ARQUIVO PS DESTINO
   PS_open($myfile_ps, "SAGU", $nome_arq_ps, 'Landscape');

   //========================= AJUSTA O USO DE ACENTOS
   PS_set_acent($myfile_ps);

   //========================= INICIA A PRIMEIRA PAGINA
   PS_begin_page($myfile_ps, $nr_pag);
    
   //========================= Rotate (Para usar a p�gina em LANDSCAPE)
   PS_rotate($myfile_ps, 90);
  
   PS_show_xy_font($myfile_ps, 'C�digos dos Cursos', 45, -30, 'Arial-Bold', 15);
   PS_show_xy_font($myfile_ps, "$data", 225, -30, 'Arial-Bold', 10);

   $sql = " SELECT id, " .
          "        descricao " .
          " FROM cursos " .
          " ORDER BY descricao; " ;

   //========================= CONECTA AO BD
   
   $conn = new Connection;
   $conn->Open();
   $query = $conn->CreateQuery($sql);
   
   SaguAssert($query,"N�o foi poss�vel executar a consulta SQL!");
    
   $lin = -60;
   $controle = 1;
   $contador = 1;
   
   while( $query->MoveNext() )
   {

    list($cod_curso,
         $descricao) = $query->GetRowValues();
	 
    if ($controle == 1) 
    {
       PS_show_xy_font($myfile_ps, 'C�digo', 50, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 45, -45, 90, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Descricao', 95, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 90, -45, 805, -65, 0.3);
       $lin = $lin - 15;
       $controle = $controle + 1;
    }
    if ($controle != 1)
    {
       PS_show_xy_font($myfile_ps, "$cod_curso", 55, $lin, 'Arial', 8);
       PS_show_xy_font($myfile_ps, "$descricao", 95, $lin, 'Arial', 8);
    }
     
    $contador = $contador + 1;
    $lin = $lin - 15;
    
    if ($contador == 35) 
    {
      PS_end_page($myfile_ps);
      $nr_pag = $nr_pag + 1;
      PS_begin_page($myfile_ps, $nr_pag);
      PS_rotate($myfile_ps, 90);
      $lin = -60;
      $controle = 1;
      $contador = 1;
    }
 
   }
   
   PS_end_page($myfile_ps);
   $nr_pag = $nr_pag + 1;
   PS_begin_page($myfile_ps, $nr_pag);
   PS_rotate($myfile_ps, 90);
   
   @$query->Close(); 

   PS_show_xy_font($myfile_ps, 'Cadastro de Alunos', 45, -30, 'Arial-Bold', 15);
   PS_show_xy_font($myfile_ps, "$data", 225, -30, 'Arial-Bold', 10);

   $sql = " SELECT A.id, " .
          "        A.nome, " .
          "        A.rua, " .
          "        A.complemento, " .
          "        A.bairro, " .
          "        get_cidade(A.ref_cidade), " .
          "        A.fone_particular, " .
          "        A.fone_profissional, " .
          "        A.fone_celular, " .
          "        A.fone_recado, " .
          "        B.dt_desativacao, " .
          "        B.ref_curso, " .
          "        B.ref_campus " .
          " FROM pessoas A, contratos B " .
          " WHERE A.id = B.ref_pessoa and " .
	  "       A.tipo_pessoa = 'f' " .
          " ORDER BY A.nome; " ;

   //========================= CONECTA AO BD
   
   $query = $conn->CreateQuery($sql);
   
   SaguAssert($query,"N�o foi poss�vel executar a consulta SQL!");
    
   $lin = -60;
   $controle = 1;
   $contador = 1;

   while( $query->MoveNext() )
   {

    list($id,
         $nome,
         $rua,
         $complemento,
         $bairro,
         $cidade,
         $fone_particular,
         $fone_profissional,
         $fone_celular,
         $fone_recado,
         $dt_desativacao,
         $ref_curso,
         $ref_campus) = $query->GetRowValues();
	 
    $nome = substr($nome, 0, 30);
    
    if (strpos($cidade, "(")) {
       $cidade = substr($cidade, 0, strpos($cidade, "("));
       $cidade = substr($cidade, 0, 18);
    }
    else  {
       $cidade = substr($cidade, 0, 18);
    }
     
    if (strpos($rua, "(")) {
       $rua = substr($rua, 0, strpos($rua, "("));
       $rua = substr($rua, 0, 32);
    }
    else  {
       $rua = substr($rua, 0, 32);
    }
    
    if (empty($complemento)) { $complemento = ' ';}
    if (empty($fone_particular)) { $fone_particular = 'NI';}
    if (empty($fone_profissional)) { $fone_profissional = 'NI';}
    if (empty($fone_celular)) { $fone_celular = 'NI';}
    if (empty($fone_recado)) { $fone_recado = 'NI';}
   
    if ($controle == 1) 
    {
       PS_show_xy_font($myfile_ps, 'C�digo', 55, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 45, -45, 95, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Nome', 100, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 95, -45, 255, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Endere�o', 260, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 255, -45, 445, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Cidade', 450, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 445, -45, 545, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Fones ( Part, Prof, Cel, Rec )', 550, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 545, -45, 735, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Curso', 740, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 735, -45, 775, -65, 0.3);
       PS_show_xy_font($myfile_ps, 'Cam', 778, $lin, 'Arial-Bold', 10);
       PS_rect($myfile_ps, 775, -45, 805, -65, 0.3);
       $lin = $lin - 15;
       $controle = $controle + 1;
    }
    if ($controle != 1)
    {
       if ($dt_desativacao == '')
       {
       PS_show_xy_font($myfile_ps, '#', 45, $lin, 'Arial', 8);
       }
       else
       {
       PS_show_xy_font($myfile_ps, ' ', 45, $lin, 'Arial', 8);
       }
       PS_show_xy_font($myfile_ps, "$id", 55, $lin, 'Arial', 8);
       PS_show_xy_font($myfile_ps, "$nome", 95, $lin, 'Arial', 8);
       PS_show_xy_font($myfile_ps, "$rua $complemento", 260, $lin, 'Arial', 8);
       PS_show_xy_font($myfile_ps, "$cidade", 450, $lin, 'Arial', 8);
       
       $fones = $fone_particular . " / " . $fone_profissional . " / " . $fone_celular . " / " . $fone_recado;
       
       $fones = substr($fones, 0, 48);
       
       PS_show_xy_font($myfile_ps, "$fones", 550, $lin, 'Arial', 8);
       PS_show_xy_font($myfile_ps, "$ref_curso", 748, $lin, 'Arial', 8);
       PS_show_xy_font($myfile_ps, "$ref_campus", 788, $lin, 'Arial', 8);
    }
   $contador = $contador + 1;
   $lin = $lin - 15;
   if ($contador == 34) 
   {
    PS_end_page($myfile_ps);
    $nr_pag = $nr_pag + 1;
    PS_begin_page($myfile_ps, $nr_pag);
    PS_rotate($myfile_ps, 90);
    $lin = -60;
    $controle = 1;
    $contador = 1;
   }
}
//========================= FECHA A P�GINA 
    PS_end_page($myfile_ps);

    //========================= FECHA O ARQUIVO PS DESTINO
    PS_close($myfile_ps);

    //========================= CANCELA CONEX�O
    @$query->Close();
    @$conn->Close();

?>

 <script Language="Javascript">
   NOVAWIN.close();
   location="<? echo($nome_arq_ps) ?>";
 </script>

 <form name="myform" action="" >
   <p align="center">
     <input type="button" name="botao" value="&lt;&lt; Retornar" onclick="history.go(-1)">
     <input type="button" name="botao2" value="Imprimir Novamente" onclick="location='<?php echo($nome_arq_ps)  ?>'" />
   </p>

</body>

</html>
