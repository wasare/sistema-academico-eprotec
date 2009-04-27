<?php


//INICIALIZA A SESSÃO
@session_start();


require_once("../../../lib/common.php");
require_once("../../../lib/properties.php");


define('PDF_TMP_DIR', dirname(__FILE__) .'/boletins/pdf/');

require_once(dirname(__FILE__) .'/../../../lib/fpdf153/fpdf.php');
require_once(dirname(__FILE__) .'/../../../lib/fpdi/fpdi.php');
require_once(dirname(__FILE__) .'/../../../lib/fpdf153/pdf.ext.php');




function remove_files($dir)
{
  if(is_dir($dir))
  {
    if($handle = opendir($dir))
    {
      while(($file = readdir($handle)) !== false)
      {
        if($file != "." && $file != ".." && $file != "Thumbs.db"/*pesky windows, images..*/)
        {
          //echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";
          //$aPDFs[] = $file;
          @unlink($dir.'/'.$file);
        }
      }
      closedir($handle);
      return $aPDFs;
    }
  }
}


remove_files(PDF_TMP_DIR);


class  Boletim extends PDF {


   	//var $NPeriodo;
   	var $NCurso;
  	// var $NSigla;
	
	
	function Header() {
	
	
    	// IMAGEM COM O LOGO
    	$this->Image(dirname(__FILE__) .'/../../../images/if_minas_campus_bambui-logo.png',170,16,25);
    
	    // SELECIONA FONT ARIAL BOLD 10
    	$this->SetFont('Arial','',13);
    
	    // PREPARA TITULO DO CABECALHO
    	$this->Cell(0,5,'MEC - SETEC',0,1,'L');
    
	    $this->Cell(0,5,'INSTITUTO FEDERAL MINAS GERAIS - CAMPUS BAMBUÍ',0,1,'L');
    
    	$this->Cell(0,5,'GERÊNCIA DE REGISTROS ESCOLARES',0,1,'L');
    
    
	    // Quebra de linha
    	$this->Ln();
    
	    // SELECIONA FONT ARIAL  12
    	$this->SetFont('Arial','B',11);
	    $this->Cell(0,5,'APROVEITAMENTO MODULAR',0,1,'C');
    
    	// Quebra de linha
	    $this->Ln(5);

	}


	function Footer() {

    	//Vai para 1.0 cm da parte inferior
	    $this->SetY(-15);
    	//Seleciona a fonte Arial itï¿½lico 8
	    $this->SetFont('Arial','I',8);
	}

	function GeraBoletins($Dados,$FileName,$THeader,$Curso,$Dir,$con)	{
	

      //print_r($Dados);die;
    	$this->NPeriodo = $Periodo;
	    $this->NCurso = $Curso;
    
    	// LARGURA DAS COLUNAS
	    $w = array(96,16,20,28,16);    
            
    	$numRows = count($Dados); 
		// print_r($Dados);die;

		// NUMERO DE LINHAS POR PAGINA^M
		$linhas = 35;
				
		$Pages = ceil($numRows / $linhas);
		
		$registro = 0;

    	for ( $j = 0; $j < $numRows ; ++$j ) {    

	       // INICIA UMA NOVA PAGINA

			if($registro != $Dados[$j][1]) {

				if ($j != 0 ) {
        	    	// Closure line
		    	  	$this->Cell(array_sum($w),0,'','T');
            	}
		      
         		$registro = $Dados[$j][1];

	        	// INICIA UMA NOVA PAGINA
		    	$this->AddPage();
        

	    	 	$this->SetFont('Arial','B',10);
     			$Texto = "Nome: ";
	     		$this->Write(5,$Texto);

     			$this->SetFont('Arial','',12);
    	 		$Texto = $Dados[$j][0];
     			$this->Write(5,$Texto);

	     		$this->SetFont('Arial','B',10);
    	 		$Texto = "               Matrícula: ";
     			$this->Write(5,$Texto);

           		$this->SetFont('Arial','',11);
	           	$Texto = str_pad($Dados[$j][1], 5, "0", STR_PAD_LEFT);
    	       	$this->Write(5,$Texto);


	    	    // QUEBRA DE LINHA
           		$this->Ln(5);

	           $this->SetFont('Arial','B',10);
    	       $Texto = "Curso: ";
        	   $this->Write(5,$Texto);

         	  	$this->SetFont('Arial','',11);
           		$Texto = " $Curso";
	           	$this->Write(5,$Texto);

				// QUEBRA DE LINHA
        	    $this->Ln(5);
			
			   $this->SetFont('Arial','B',10);
    	       $Texto = "Período: ";
        	   $this->Write(5,$Texto);


           $this->SetFont('Arial','',11);
           $Texto = str_pad($Dados[$j][7], 5, "0", STR_PAD_LEFT);
           $this->Write(5,$Texto);


           	  $this->Ln(5);

              $this->SetFont('Arial','B',10);
              $Texto = "Data de Emissão: ";
              $this->Write(5,$Texto);

            	$this->SetFont('Arial','',10);
            	$this->Write(5,date('d/m/Y H:i s').'s');
	      // QUEBRA DE LINHA
          // $this->Ln(5);

           // QUEBRA DE LINHA
            $this->Ln();
            $this->Ln();

			$this->SetFont('Arial','B',8);
			 
           // ADICIONA O CABECHALHO DA TABELA
           for( $i=0 ; $i < count($THeader) ; ++$i )
           {
                $this->Cell($w[$i],6,$THeader[$i],1,0,'C');
           }

           $this->SetFont('Arial','', 9);

           // QUEBRA DE LINHA
           $this->Ln();
       }

        	//$this->AddPage(); 


            $c_distribuida_sql = 'SELECT COUNT(*) FROM diario_formulas ';
            $c_distribuida_sql .= " WHERE grupo ILIKE '%-". $Dados[$j][9] . "';";

            $qry = $con->CreateQuery($c_distribuida_sql);
            $c_distribuida = $qry->GetRowValues();
            $qry->Close();
            //print_r($c_distribuida);
            
			$n_distribuida_sql = 'SELECT sum(nota_distribuida) as nota_distribuida FROM diario_formulas ';
		    $n_distribuida_sql .= " WHERE grupo ILIKE '%-". $Dados[$j][9] . "';";
           
			$qry = $con->CreateQuery($n_distribuida_sql);
			$nota_distribuida = $qry->GetRowValues();
			$qry->Close(); 


			if ( $c_distribuida[0] == 6 ) {

			    $nota_final = str_replace('.', ',', $Dados[$j][3]);
			}
			else {
				$nota_distribuida[0] = '-';
			    $nota_final = '-';
			}
     
			
        	$this->Cell($w[0],5.7,'  ' . $Dados[$j][9] . ' - ' . $Dados[$j][2],'LR');
        
        	$this->Cell($w[1],5.7,'      '.$Dados[$j][4],'LR');
        
        	$this->Cell($w[2],5.7,'       '.$nota_final,'LR');
			$this->Cell($w[3],5.7,'            ' . $nota_distribuida[0],'LR');
        
        	$this->Cell($w[4],5.7,'     '.$Dados[$j][6],'LR');
        
        	//$this->Cell($w[4],5.7,'     '.$Dados[$j][Carga],'LR');
        
        	//$this->Cell($w[5],5.7,'    '.$Dados[$j][Resultado],'LR');
        
        	// QUEBRA DE LINHA                   
        	$this->Ln();
         
           
   			if ($j == ($numRows - 1)) {
                // Closure line
                $this->Cell(array_sum($w),0,'','T');
            }    
        
      }
      
    $this->Output($Dir.$FileName, F);
}


}  // FIM CLASSE BOLETIM

class concat_pdf extends FPDI {

    var $files = array();

    function setFiles($files) {
        $this->files = $files;
    }

    function concat() {
        foreach($this->files AS $file) {
            $pagecount = $this->setSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                 $tplidx = $this->ImportPage($i);
                 $s = $this->getTemplatesize($tplidx);
                 $this->AddPage($s['h'] > $s['w'] ? 'P' : 'L');
                 $this->useTemplate($tplidx);
            }
        }
    }

}

$periodo = $_GET['periodo'];
$curso = $_GET['curso'];
$aluno_id = $_GET['id'];

$qryCurso = " SELECT abreviatura FROM cursos WHERE id = ".$curso.";";

$Registro = $Mat;

// RECUPERA DESCRICAO DO CURSO
$conn = new Connection;
$conn->Open();

$query = $conn->CreateQuery($qryCurso);
$NCurso = $query->GetRowValues();
$query->Close();



// RECUPERA ALUNO(S)
if ( !IsSet($aluno_id)  && !is_numeric($aluno_id) ) {

     $qryAlunos = "SELECT 
                     DISTINCT
                        A.ref_pessoa
                     FROM 
                        matricula A
                     WHERE
                        ref_periodo = '$periodo' AND
                        ref_curso = $curso
                     ORDER BY A.ref_pessoa;";

    $query = $conn->CreateQuery($qryAlunos);	
    $aAlunos = array();

    while ( $query->MoveNext() )
    {
       // list ( $campus, $id, $desc, $id_contrato, $semestre, $turma, $ref_periodo_turma ) = $query->GetRowValues();
       $aAlunos = array_merge($aAlunos, $query->GetRowValues());
	} // while

	$query->Close();
	
}
else {
	    $aAlunos[] = $aluno_id;
}


$qryBoletim = '
SELECT 
    p.nome, m.ref_pessoa as ra_cnec, d.descricao_disciplina, m.nota_final, d.carga_horaria, m.ref_curso, m.num_faltas, s.descricao as periodo, m.ref_periodo, m.ref_disciplina_ofer as oferecida, m.ordem_chamada
    FROM
        matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
    WHERE 
        m.ref_pessoa = p.id AND 
	o.ref_periodo = \'%s\' AND        
	p.ra_cnec = %s AND 
        m.ref_curso = %s AND         
        m.ref_disciplina_ofer = o.id AND 
        d.id = o.ref_disciplina AND
        s.id = o.ref_periodo
    ORDER BY 3;';

//echo sprintf($qryBoletim1,$periodo,$periodo,$aAlunos[0],$curso,$aAlunos[0]);

//echo sprintf($qryBoletim,$periodo,$aAlunos[0],$curso); die;


foreach ($aAlunos as $aluno) {

	$query = $conn->CreateQuery(sprintf($qryBoletim,$periodo,$aluno,$curso));
    $Boletim = array();

    while ( $query->MoveNext() )
    {
       // list ( $campus, $id, $desc, $id_contrato, $semestre, $turma, $ref_periodo_turma ) = $query->GetRowValues();
	   $Boletim[] = $query->GetRowValues();
    } // while

    $query->Close();

    // GERA PDF DA LISTA DE PRESENCA DOS CANDIDATOS^M
    $bo_pdf = new Boletim('P','mm','A4');
              
    $bo_pdf->SetFont('Arial','B',13);
    $bo_pdf->SetMargins(18, 15 , 15);
    $bo_pdf->AliasNbPages();
             
    // PREPARA O CABELHO  DA TABELA
    $TableHeader = array('Componente Modular', 'CH', 'Nota', 'Nota Distribuída', 'Faltas');
               
    $NArquivo = "Boletim_".$curso."_".$aluno.".pdf"; 
          
    // EXECUTA A GERACAO DO RELATORIO     
    $bo_pdf->GeraBoletins($Boletim,$NArquivo,$TableHeader,$NCurso[0],PDF_TMP_DIR,$conn);

}

function list_files($dir)
{
  if(is_dir($dir))
  {
    if($handle = opendir($dir))
    {
      while(($file = readdir($handle)) !== false)
      {
        if($file != "." && $file != ".." && $file != "Thumbs.db"/*pesky windows, images..*/)
        {
          //echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";
		  $aPDFs[] = $dir.$file;
        }
      }
      closedir($handle);
	  return $aPDFs;
    }
  }
}


$pdf =& new concat_pdf();
$pdf->setFiles(list_files(PDF_TMP_DIR));
$pdf->concat();

$nome_arquivo = "Boletim_".$curso."_".$periodo.".pdf";

$pdf->Output("boletins/$nome_arquivo", 'F');

header("Location: boletins/$nome_arquivo");

?>  
