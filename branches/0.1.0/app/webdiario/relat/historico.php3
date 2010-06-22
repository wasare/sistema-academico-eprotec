<? require("pslib.class"); ?>
<html>
<head>
<title>Ficha Individual</title>
</head>

<body bgcolor="#FFFFFF">
<?

  echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\"> GERANDO ARQUIVO PS ...... <br></font>");

//// PS DESTINATION FILE - ARQUIVO PS DESTINO
   $nome_arq_ps = "ps/teste.ps";
   
   $PS = new postscript( $nome_arq_ps, "DIÁRIO WEB", "Ficha Individual","Portrait");

   $PS->begin_page(1);
   
   
   
   // MONTA O CABEÇALHO DA PÁGINA
 //  $PS->rect(30, 800, 170, 725, 2); //representa o lugar do logo
   $PS->show_xy_font("LOGO",63, 758, "Arial-Bold", 20);
   
   $meol=188; // POSIÇÃO DO GERAL COM RELAÇÃO À MARGEM ESQUERDA
   
   $PS->show_xy_font("Faculdade Cenecista de Capivari",$meol, 783, "Arial-Bold", 24);
   $PS->show_xy_font("Mantenedora: CAMPANHA NACIONAL DE ESCOLAS DA COMUNIDADE",$meol+2, 768, "Arial-Bold", 11);
   $PS->show_xy_font("Rua Barão do Rio Branco , 374 - CEP:  13.360-000 - Capivari - SP",$meol+22, 755, "Arial-Bold", 11);
   $PS->show_xy_font("E-mail: cnec@cneccapivari.br - Portal: www.cneccapivari.br",$meol+35, 742, "Arial-Bold", 11);
   $PS->show_xy_font("Telefone: (19) 3492-8888  -  Fax: (19) 3492-8880",$meol+70, 729, "Arial-Bold", 11);
   $PS->show_xy_font("Ficha Individual",237, 670, "Arial-Bold-Italic", 15);

   // MONTA AS MOLDURAS
   $PS->rect(30, 618, 560, 602, 1);
   $PS->rect(30, 602, 560, 586, 1);
   $PS->rect(30, 602, 375, 586, 1);
   $PS->rect(30, 586, 560, 570, 1);
   $PS->rect(440, 586, 500, 570, 1);
   $PS->rect(320, 586, 380, 570, 1);
   $PS->rect(200, 586, 260, 570, 1);
   $fimmateria = 554-11;   // 1 linha 554
   $PS->rect_fill(30, 570, 560, $fimmateria, 1, "0.9"); //Moldura de Matérias diferenca de 12px
   $PS->rect_fill(30, 570, 560, $fimmateria-11, 1, "0.9"); //Moldura de Matérias diferenca de 12px

   //MONTA AS LABELS DENTRO DAS MOLDURAS
   $PS->show_xy_font("Aluno:", 32, 605, "Arial-Bold", 10);
   $PS->show_xy_font("Curso de:", 32, 590, "Arial-Bold", 10);
   $PS->show_xy_font("Semestre:", 378, 590, "Arial-Bold", 10);
   $PS->show_xy_font("Disciplinas", 94, 575, "Arial", 8);
   $PS->show_xy_font("Notas", 220, 575, "Arial", 8);
   $PS->show_xy_font("Nota de Exame", 263, 575, "Arial", 8);
   $PS->show_xy_font("Média Final", 329, 575, "Arial", 8);
   $PS->show_xy_font("N° Faltas", 393, 575, "Arial", 8);
   $PS->show_xy_font("N° Aula Dada", 445, 575, "Arial", 8);
   $PS->show_xy_font("Carga Horária", 504, 575, "Arial", 8);
   
   
      //MONTA OS DADOS DO ALUNO NO CABEÇALHO
   $PS->show_xy_font("Rodrigo de Brito Volpini", 67, 605, "Arial-Bold", 10);
   $PS->show_xy_font("Sistemas de Informação", 81, 590, "Arial-Bold", 10);
   $PS->show_xy_font("1FANASA", 430, 590, "Arial-Bold", 10);

   //Imprime as disciplinas    DIRERENÇA DE 10 PX
   $linhamaterias = 559;// 1 linha é 559
   $PS->show_xy_font("DISISISISISISISISISISISIS", 32, $linhamaterias, "Arial-Bold", 6);
   $PS->show_xy_font("DISISISISISISISISISISISIS", 32, $linhamaterias-10, "Arial-Bold", 6);
   $PS->show_xy_font("DISISISISISISISISISISISIS", 32, $linhamaterias-20, "Arial-Bold", 6);

 
  /*  // MONTA OS BOX'S

   $PS->rect(565, 420, 30, 770, 2);
   $PS->rect(565, 420, 30, 770, 2);    */


//   $PS->rect_fill(300, 300, 600, 400, 2, "0.7");
   // LOGOTIPO CNEC - PS Image => Detail: when inserting a ps image, must delete the information about the file (in the top of the file)
   $PS->open_ps('logo.ps');
   

   $PS->end_page();
   $PS->close();
   
   echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\"> ARQUIVO GERADO COM SUCESSO !! </font>");
?> 
<script Language="Javascript">
    location="<? echo($nome_arq_ps) ?>";
</script>
    <br>
</body>
</html>
