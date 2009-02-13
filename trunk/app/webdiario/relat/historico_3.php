<?php
include ('../conf/webdiario.conf.php');
require("pslib.class");

function Desconverte($data){
$dt_convertida=explode("-",$data);
$dia=$dt_convertida[0];
$mes=$dt_convertida[1];
$ano=$dt_convertida[2];
$ret_var=$ano."/".$mes."/".$dia;
return $ret_var;
}


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








                  if ($nomes == '') {
                  print '<script language=javascript>
						 window.alert("Você deve selecionar pelo menos 1 aluno para impressão !");
						javascript:window.history.back(1);
						</script>';
		       		    exit;
		       		    }else {
print('<html>
<head>
<title>Historico escolar</title>
</head>
<body bgcolor="#FFFFFF">');
echo("<font face=\"Verdana, Arial, Helvetica, sans-serif\"> GERANDO ARQUIVO PS ...... <br></font>");

////VARIÁVEIS DO AMBIENTE

$numeropaginas=1;


//// PS DESTINATION FILE - ARQUIVO PS DESTINO
   $nome_arq_ps = "ps/historico_escolar.ps";

   $PS = new postscript( $nome_arq_ps, "DIÁRIO WEB", "Historico Escolar","Portrait");
   

//Monta o arquivo de acordo com o numero de RA
        reset ($nomes);
        while ($array_cell = each($nomes)) {
        $ra_cnec = $array_cell['value'];

                 //Seleciona o nome dos alunos curo e descrição
                 $dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");
                 $sql1 = "SELECT
                 a.id,
                 a.nome,
                 a.rg_numero,
                 a.dt_nascimento,
                 a.titulo_eleitor,
                 a.escola_2g
                 from pessoas a
                 where
                 ra_cnec = '$ra_cnec'";
                 ////////   where ra_cnec = '$ra_cnec'";
            $query1 = pg_exec($dbconnect, $sql1);
	         while($row1 = pg_fetch_array($query1))
	        	{
	        	$idpessoa = $row1["id"];
        		$nomealuno = $row1["nome"];
        		$rg_numero = $row1["rg_numero"];
        		$nascimento = $row1["dt_nascimento"];
        		$natural = '';
        		$titel = $row1["titulo_eleitor"];
        		$esc2g = $row1["escola_2g"];
                }
                


// Post Script

   $PS->begin_page($numeropaginas);

   $meol=188; // POSIÇÃO DO GERAL COM RELAÇÃO À MARGEM ESQUERDA

   $PS->show_xy_font("FACULDADE CENECISTA DE CAPIVARI",$meol+20, 783, "Arial-Bold", 18);
   $PS->show_xy_font("Mantenedora: CAMPANHA NACIONAL DE ESCOLAS DA COMUNIDADE",$meol+2, 768, "Arial-Bold", 11);
   $PS->show_xy_font("Rua Barão do Rio Branco , 374 - CEP:  13.360-000 - Capivari - SP",$meol+22, 755, "Arial-Bold", 11);
   $PS->show_xy_font("E-mail: cnec@cneccapivari.br - Portal: www.cneccapivari.br",$meol+35, 742, "Arial-Bold", 11);
   $PS->show_xy_font("Telefone: (19) 3492-8888  -  Fax: (19) 3492-8880",$meol+70, 729, "Arial-Bold", 11);
   $PS->show_xy_font("Histórico Escolar",240, 705, "Arial-Bold-Italic", 14);

   $PS->show_xy_font("Nome do Aluno : ".$nomealuno, 32, 690, "Arial", 10);
   $PS->show_xy_font("Naturalidade : ".$natural, 32, 678, "Arial", 10);
   $PS->show_xy_font("Nascido : ".Desconverte($nascimento), 240, 678, "Arial", 10);
   $PS->show_xy_font("Identidade RG : ".$rg_numero, 32, 665, "Arial", 10);
   $PS->show_xy_font("Título Eleitor : ".$titel, 332, 665, "Arial", 10);

   $PS->show_xy_font("ESCOLARIDADE DE 2º GRAU", 225, 650, "Arial", 11);
   $PS->show_xy_font("$esc2g", 32, 637, "Arial-Bold-Italic", 11);
   $PS->show_xy_font("VESTIBULAR", 267, 623, "Arial", 11);
   $PS->show_xy_font("$locvest", 30, 610, "Arial-Bold-Italic", 11);
   $PS->show_xy_font("PONTOS OBTIDOS: $ptvest", 32, 598, "Arial", 10);
   $PS->show_xy_font("DATA $dtvest", 239, 598, "Arial", 10);
   $PS->show_xy_font("RESULTADO : CLASSIFICADO", 415, 598, "Arial", 10);

   ////MONTA MOLDURA CABEÇALHO
   $PS->show_xy_font("DISCIPLINA", 155, 561, "Arial", 9);
   $PS->show_xy_font("ANO", 350, 561, "Arial", 9);
   $PS->show_xy_font("MÉDIA FINAL", 386, 561, "Arial", 9);
   $PS->show_xy_font("A/R", 456, 561, "Arial", 9);
   $PS->show_xy_font("H/A", 495, 561, "Arial", 9);
   $PS->show_xy_font("SEM", 530, 561, "Arial", 9);
   ///      ESQ  SUP  DIR  INF  LINHA
   $PS->rect(30, 571, 560, 558, 1);
   $PS->rect(484, 571, 522, 558, 1);
   $PS->rect(383, 571, 446, 558, 1);   //DIF 38
   $PS->rect(338, 571, 338, 558, 1);   //DIF 38
   

  // MONTA OS CAMPOS DE DISCIPLINAS E NOTAS
     $sql2= "SELECT
     a.ref_disciplina as iddisc,
     a.dt_matricula,
     a.nota_final,
     a.nota_exame,
     a.num_faltas,
     a.ref_periodo,
     b.descricao_extenso,
     c.descricao
     from matricula a, disciplinas b, cursos c
     Where b.id = a.ref_disciplina
     and c.id = a.ref_curso
     and a.ref_pessoa = '$idpessoa' order by 1";
      $query2 = pg_exec($dbconnect, $sql2);
           
     /*      // Calcula linha para Moldura e exibição
           $registros = pg_NumRows($query2);
           $fimmateria = 554;
           for($fim=1; $fim<$registros; $fim++) {
            $fimmateria = $fimmateria - 11;      }                         */
            

           // imprime os caras registros
             $sup = 558;
             $back = 0.9;
             $totalcarga=0;
             
	         while($row2 = pg_fetch_array($query2))
	        	{
	        	$dtmatricula = $row2["dt_matricula"];
        		$nota = $row2["nota_final"];
        		$nota_exame = $row2["nota_exame"];
        		$numfaltas = $row2["num_faltas"];
        		$descmateria = $row2["descricao_extenso"];
        		$cursodesc = $row2["descricao"];
        		$iddisc = $row2["iddisc"];
        		$ref_periodo = $row2["ref_periodo"];
        		
        		if ($nota==''){
        		$nota='****';   }
        		if ($numfaltas==0){
        		$numfaltas='-';   }
        		
        		// CALCULA A HORA AULA PREVISTA
                $sql3 = "SELECT
                id,
                carga_horaria
                FROM disciplinas
                WHERE
                id = '$iddisc'";
                $query3 = pg_exec($dbconnect, $sql3);
                while($row3 = pg_fetch_array($query3)) {
        		$cargahoraria = $row3["carga_horaria"];
        		}
        		
        		// CALCULA HORA AULA DADA
        		   $sqlflag="select
                   flag
                   from diario_seq_faltas
                   where periodo='$ref_periodo' and disciplina='$iddisc'";
                   $queryflag=pg_exec($dbconnect, $sqlflag);

                   while ($rowflag=pg_fetch_array($queryflag)) {
                   $flags=$rowflag["flag"];
                   if ($flags == "") {
                   $result=$flags;
                   } elseif ($flags != "") {
                   $result=$result+$flags;
                   }
                   }

                //Calcula Média
                if (($nota=='****') || ($nota > $nota_exame)){
                $mediafinal = $nota;                               }
                if ($nota_exame > $nota){
                $mediafinal = $nota_exame;                           }
                if (($nota_exame=='') || ($notaexame==0)){
        		$notaexame = ' -';   }
        		
                //veririfica situação
                $situacao = 'A';
                if ($mediafinal <7){
        		$situacao = 'R';   }
        		$faltasdisponivel = ($result/4);
                if ($faltasmateria > $faltasdisponivel){
        		$situacao = 'R';   }
        		
                $inf = $sup-13;

                if ($back == 1 )  {
                $back = 0.9;      } else {
                $back = 1;          }
                
                
                   trim($dtmatricula);
                   $newdatar=substr($dtmatricula, 0,4);
                   
                   trim($ref_periodo);
                   $newper=substr($ref_periodo, 0,1);
                   $newper=$newper."º";
                
                $PS->rect_fill(30, $sup, 560, $inf, 1, $back);      // DIF LINHA 13
                $PS->rect_fill(484, $sup, 522, $inf, 1, $back);
                $PS->rect_fill(383, $sup, 446, $inf, 1, $back);
                $PS->rect_fill(338, $sup, 338, $inf, 1, $back);

                $PS->show_xy_font("$descmateria", 35, $inf + 4, "Arial", 9);
                $PS->show_xy_font("$newdatar", 350, $inf + 4, "Arial", 9);
                $PS->show_xy_font("$mediafinal", 408, $inf + 4, "Arial", 9);
                $PS->show_xy_font("$situacao", 461, $inf + 4, "Arial", 9);
                $PS->show_xy_font("$result", 499, $inf + 4, "Arial", 9);
                $PS->show_xy_font("$newper", 537, $inf + 4, "Arial", 9);
                unset($result);
                $sup=$sup-13;
                $totalcarga=$totalcarga+$result;

                } // fim while das materias
                
                $PS->show_xy_font("Obs: * = Aproveitamento de Estudos", 30, $inf -10 , "Arial", 9);
                $PS->show_xy_font("Data de Conclusão do Curso: ".$dtconc, 320, $inf -10 , "Arial", 9);
                $PS->show_xy_font("Data de Colação de Grau : ".$dtcol, 30, $inf -20 , "Arial", 9);
                $PS->show_xy_font("Data de Expedição do Diploma : ".$dtexp, 320, $inf -20 , "Arial", 9);
                $PS->show_xy_font("Carga horária total do Curso : ".$chtotal." ,incluídas as horas de Estágio Supervisionado. ", 30, $inf -30 , "Arial", 9);
                $PS->show_xy_font("$obs", 30, $inf -40 , "Arial", 9);
                $PS->show_xy_font("Capivari, $dia de $mesdesc de $ano.", 30, $inf -60 , "Arial", 9);

//   $PS->rect_fill(300, 300, 600, 400, 2, "0.7");

      $PS->show_xy_font("Curso : $cursodesc", 30, 580, "Arial-Bold", 12);

//   $PS->line($xcoord_from=0, $ycoord_from=0, $xcoord_to=0, $ycoord_to=0, $linewidth=0);
     $PS->line(50, 100, 260, 100, "1");
     $PS->show_xy_font("Viviani Bregion", 107, 85, "Arial", 12);
     $PS->show_xy_font("Secretária - R.G. 21.499.739", 99, 75, "Arial", 8);
     
     $PS->line(360, 100, 570, 100, "1");
     $PS->show_xy_font("Luís Donisete Campaci", 400, 85, "Arial", 12);
     $PS->show_xy_font("Diretor - R.G.: 6.279.669 ", 422, 75, "Arial", 8);

 
 
   //   LOGOTIPO CNEC - PS Image => Detail: when inserting a ps image, must delete the information about the file (in the top of the file)
   $PS->open_ps('cneclogo.ps');


   $PS->end_page(); // Finaliza a página
   $numeropaginas++;

   } //fim do While
   

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
