<?php

require_once('../conf/conn_diario.php');

require("pslib.class");


                  if ($nomes == '') {
                  print '<script language=javascript>
						 window.alert("Você deve selecionar pelo menos 1 aluno para impressão !");
						javascript:window.history.back(1);
						</script>';
		       		    exit;
		       		    }else {
print('<html>
<head>
<title>Ficha Individual</title>
</head>
<body bgcolor="#FFFFFF">');
echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\"> GERANDO ARQUIVO PS ...... <br></font>");

////VARIÁVEIS DO AMBIENTE

$numeropaginas=1;


//// PS DESTINATION FILE - ARQUIVO PS DESTINO
   $nome_arq_ps = "ps/Ficha_Individual.ps";

   $PS = new postscript( $nome_arq_ps, "DIÁRIO WEB", "Ficha Individual","Portrait");
   

//Monta o arquivo de acordo com o numero de RA
        reset ($nomes);
        while ($array_cell = each($nomes)) {
        $ra_cnec = $array_cell['value'];

                 //Seleciona o nome dos alunos curo e descrição
                 $sql1 = "SELECT DISTINCT
                 	  a.nome,
                 	  a.ra_cnec,
                      a.id,
                 	  c.descricao,
                 	  b.ordem_chamada,
                 	  d.descricao as descperiodo
                      FROM
                      pessoas a, matricula b, cursos c, periodos d
                      WHERE
                      a.ra_cnec = '$ra_cnec' AND
                      b.ref_periodo = '$classe' AND
                      b.ref_pessoa = a.id AND
                      b.ref_curso = c.id AND
                      b.ref_periodo = d.id";
            $query1 = pg_exec($dbconnect, $sql1);
	         while($row1 = pg_fetch_array($query1))
	        	{
        		$nomealuno = $row1["nome"];
        		$desccurso = $row1["descricao"];
        		$descperiodo = $row1["descperiodo"];
        		$idpessoa = $row1["id"];
        		$ra = $row1["ra_cnec"];
        		$numerochamada = $row1["ordem_chamada"];
                }
                
///////////////////Função de data
$dia=gmDate("d");
$mes=gmdate("m");
$ano=gmdate("Y");
      switch ($mes) {
           case "01":
           $mesdesc = "Janeiro";
           break;
           case "02":
           $mesdesc = "Fevereiro";
           break;
           case "03":
           $mesdesc = "Março";
           break;
           case "04":
           $mesdesc = "Abril";
           break;
           case "05":
           $mesdesc = "Maio";
           break;
           case "06":
           $mesdesc = "Junho";
           break;
           case "07":
           $mesdesc = "Julho";
           break;
           case "08":
           $mesdesc = "Agosto";
           break;
           case "09":
           $mesdesc = "Setembro";
           break;
           case "10":
           $mesdesc = "Outubro";
           break;
           case "11":
           $mesdesc = "Novembro";
           break;
           case "12":
           $mesdesc = "Dezembro";
           break;
           }  ;

// Post Script

   $PS->begin_page($numeropaginas);

// MONTA O CABEÇALHO DA PÁGINA
////   $PS->rect(30, 800, 170, 725, 2); //representa o lugar do logo
////   $PS->show_xy_font("LOGO",63, 758, "Arial-Bold", 20);
   
   $meol=188; // POSIÇÃO DO GERAL COM RELAÇÃO À MARGEM ESQUERDA

   $PS->show_xy_font("FACULDADE CENECISTA DE CAPIVARI",$meol+20, 783, "Arial-Bold", 18);
   $PS->show_xy_font("Mantenedora: CAMPANHA NACIONAL DE ESCOLAS DA COMUNIDADE",$meol+2, 768, "Arial-Bold", 11);
   $PS->show_xy_font("Rua Barão do Rio Branco , 374 - CEP:  13.360-000 - Capivari - SP",$meol+22, 755, "Arial-Bold", 11);
   $PS->show_xy_font("E-mail: cnec@cneccapivari.br - Portal: www.cneccapivari.br",$meol+35, 742, "Arial-Bold", 11);
   $PS->show_xy_font("Telefone: (19) 3492-8888  -  Fax: (19) 3492-8880",$meol+70, 729, "Arial-Bold", 11);
   $PS->show_xy_font("Boletim de Desempenho Escolar",196, 670, "Arial-Bold-Italic", 14);
   
   
   $databin=$classe;
   trim($databin);
   $nchar=strlen($databin);
   $newvalor=substr($databin, $nchar-2,2);
   $PS->show_xy_font("Ano: 20".$newvalor,270, 650, "Arial-Italic", 13);

   // MONTA AS MOLDURAS
   $PS->rect(30, 618, 560, 602, 1);
   $PS->rect(30, 602, 560, 586, 1);
   $PS->rect(375, 618, 412, 602, 1);
   $PS->rect(30, 602, 375, 586, 1);
   $PS->rect(30, 586, 560, 570, 1);
   
   $PS->rect(470, 586, 515, 570, 1);
   $PS->rect(380, 586, 425, 570, 1);
   $PS->rect(290, 586, 335, 570, 1);
   $PS->rect(245, 586, 245, 570, 1);


   //MONTA AS LABELS DENTRO DAS MOLDURAS
   $PS->show_xy_font("Aluno:", 32, 605, "Arial-Bold", 10);
   $PS->show_xy_font("N°:", 378, 605, "Arial-Bold", 10);
   $PS->show_xy_font("Periodo:", 414, 605, "Arial-Bold", 10);
   $PS->show_xy_font("Curso de:", 32, 590, "Arial-Bold", 10);
   $PS->show_xy_font("Semestre:", 378, 590, "Arial-Bold", 10);
   $PS->show_xy_font("Disciplinas", 94, 575, "Arial", 8);
   $PS->show_xy_font("Notas", 258, 575, "Arial", 8);
   $PS->show_xy_font("N. Exame", 295, 575, "Arial", 8);
   $PS->show_xy_font("Média F.", 342, 575, "Arial", 8);
   $PS->show_xy_font("N° F.", 393, 575, "Arial", 8);
   $PS->show_xy_font("N° A.D.", 434, 575, "Arial", 8);
   $PS->show_xy_font("Carga /H.", 476, 575, "Arial", 8);
   $PS->show_xy_font("Situação", 522, 575, "Arial", 8);


      //MONTA OS DADOS DO ALUNO NO CABEÇALHO
   $PS->show_xy_font("$nomealuno - R.A $ra", 67, 605, "Arial-Bold", 10);
   $PS->show_xy_font("$numerochamada", 397, 605, "Arial-Bold", 10);
   $PS->show_xy_font("Noturno", 457, 605, "Arial-Bold", 10);
   $PS->show_xy_font("$desccurso", 81, 590, "Arial-Bold", 10);
   $PS->show_xy_font("$classe", 430, 590, "Arial-Bold", 10);

  // MONTA OS CAMPOS DE DISCIPLINAS E NOTAS
     $sql2= "SELECT
             d.id,
             d.descricao_disciplina as descricao,
             m.num_faltas as faltas,
             m.nota_exame as nota_exame,
             m.nota as nota123,
             m.nota_final as nota
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
            
   // MONTA MOLDURA DAS DISCIPLINAS
   $PS->rect_fill(30, 570, 560, $fimmateria, 1, "0.9"); //Moldura de Matérias diferenca de 12px
   $PS->show_xy_font("Capivari, $dia de $mesdesc de $ano.", 30, $fimmateria - 40, "Arial", 11);
           
           
           // imprime os caras registros
             $linhamaterias = 559;
	         while($row2 = pg_fetch_array($query2))
	        	{
	        	$id = $row2["id"];
        		$nomemateria = $row2["descricao"];
        		$notamateria = $row2["nota"];
        		$notamateria123 = $row2["nota123"];
        		$notaexame = $row2["nota_exame"];
        		$faltasmateria = $row2["faltas"];
            /*    if ($notamateria123 != '') {
                $notamateria = $notamateria123; }   */
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
                   if ($flags =='0') {
                       $flags=1;     }
                   if ($flags == "") {
                   $result=$flags;
                   } elseif ($flags != "") {
                   $result=$result+$flags;
                   }
                   }

                //Calcula Média
                if (($notamateria=='****') || ($notamateria >= $notaexame)){
                $mediafinal = $notamateria;                               }
                if ($notaexame >= $notamateria){
                $mediafinal = $notaexame;                           }
                if (($notaexame=='') || ($notaexame==0)){
        		$notaexame = ' -';   }
        		
                //veririfica situação
                $situacao = 'Aprovado';
                if ($mediafinal <7){
        		$situacao = 'Reprovado';   }
        		$faltasdisponivel = ($result/4);
                if ($faltasmateria > $faltasdisponivel){
        		$situacao = 'Reprovado';   }
        		
                $id = 1;

                //Imprime as disciplinas    DIRERENÇA DE -10 PX PARA CADA LINHA
                $PS->show_xy_font("", 32, $linhamaterias.$id, "Arial-Bold", 6);
                $PS->show_xy_font("$nomemateria", 32, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$notamateria", 264, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$notaexame", 307, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$mediafinal", 352, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$faltasmateria", 398, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$result Aulas", 435, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$cargahoraria Horas", 480, $linhamaterias, "Arial-Bold", 6);
                $PS->show_xy_font("$situacao", 524, $linhamaterias, "Arial-Bold", 6);
                $linhamaterias = $linhamaterias -10;
                unset($result);
                $id++;

                } // fim while das materias
   

//   $PS->rect_fill(300, 300, 600, 400, 2, "0.7");

//   $PS->line($xcoord_from=0, $ycoord_from=0, $xcoord_to=0, $ycoord_to=0, $linewidth=0);
     $PS->line(50, 100, 260, 100, "1");
     $PS->show_xy_font("Viviani Bregion", 112, 85, "Arial", 12);
     $PS->show_xy_font("Secretária - R.G. 21.499.739", 99, 75, "Arial", 8);
 //    $PS->line(335, 100, 545, 100, "1");
 
 
   //   LOGOTIPO CNEC - PS Image => Detail: when inserting a ps image, must delete the information about the file (in the top of the file)
   $PS->open_ps('cneclogo.ps');


   ////////$PS->end_page(); // Finaliza a página
   
   $numeropaginas++;

   } //fim do While
   

   $PS->end_page(); // Finaliza a página

   $PS->close();   // Fecha o arquivo ps

echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\"> ARQUIVO GERADO COM SUCESSO !! </font>");

print ('<script Language="Javascript">
    location="'.$nome_arq_ps.'";
</script>
    <br>
</body>
</html>');
                              } //Fim do Else
?>
