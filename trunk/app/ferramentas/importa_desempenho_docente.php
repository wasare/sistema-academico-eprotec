<?php

require_once(dirname(__FILE__) .'/../setup.php');
require_once($BASE_DIR .'core/number.php');

// CONEXAO ABERTA PARA TRABALHAR COM TRANSACAO (NÃO PERSISTENTE)
$conn = new connection_factory($param_conn);


//session_start();



set_time_limit(0);



$csv = dirname(__FILE__).'/csv/A1-teste.csv';

$memory_limit = ini_get('memory_limit');


function _desempenho_docente_importa($memory_limit, $csv_file) {

    global $conn;

    $levantamento = '1001';

    $qry = '';

    $nao_sera_importado = '';

    if (!file_exists($csv_file)) {
        echo 'arquivo n&atilde;o encontrado: ' . $csv_file;
        return;
    }

    $csv_file = realpath($csv_file);

    // Automatically detect line endings.
     ini_set('auto_detect_line_endings', '1');
    $handle = @fopen($csv_file, 'r');  

    $count_lines = count(file($csv_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

    $count = 0;
  
    while (($line = fgetcsv($handle, 1000, '|')) !== FALSE) {

        $verifica_associacao = 0;    
        // performace tweaks
        //$memory_usage = (memory_get_usage()/(1024*1024));
        //if ($memory_usage >= $memory_limit / 3) continue;
    
        // don't process empty lines and first line
        //$line_filled = (count($line) == 1 && strlen($line[0]) == 0) ? FALSE : TRUE;

        //echo $line_filled;

        //if ($count == 0) continue else $count++;

        //print_r($line); if ($count > 2 ) die();
        set_time_limit(60);

        $professor = (int) trim($line[1]);
        $disciplina_ofer = (int) trim($line[0]);            

        echo $professor . ' - '. $disciplina_ofer . '<br />';
        if ((!is_numeric($professor) || $professor == 0) && (!is_numeric($disciplina_ofer) || $disciplina_ofer == 0)) continue;
        //if (!is_numeric($disciplina_ofer) || $disciplina_ofer = 0) continue;
        //echo $disciplina_ofer . ' - ';
     
        // verifica associação professor <-> disciplina_ofer
        $sql_verifica = "SELECT COUNT(*) FROM disciplinas_ofer_prof ";
        $sql_verifica .= " WHERE ref_disciplina_ofer = $disciplina_ofer AND ";
        $sql_verifica .= " ref_professor = $professor;";

        //echo $sql_verifica;
        $verifica_associacao = $conn->get_one($sql_verifica);

        //echo $verifica_associacao;

        if ($verifica_associacao == 1) {

            $qry .= "BEGIN;<br />";

            $nota_criterio_1 = number::decimal_br2numeric(trim($line[2]),2);
            $qry .= "INSERT INTO desempenho_docente_nota VALUES ($disciplina_ofer, $professor, 1, $levantamento, $nota_criterio_1);<br />";

            $nota_criterio_2 = number::decimal_br2numeric(trim($line[3]),2);
            $qry .= "INSERT INTO desempenho_docente_nota VALUES ($disciplina_ofer, $professor, 2, $levantamento, $nota_criterio_2);<br />";

            $nota_criterio_3 = number::decimal_br2numeric(trim($line[4]),2);
            $qry .= "INSERT INTO desempenho_docente_nota VALUES ($disciplina_ofer, $professor, 3, $levantamento, $nota_criterio_3);<br />";

            $nota_criterio_4 = number::decimal_br2numeric(trim($line[5]),2);
            $qry .= "INSERT INTO desempenho_docente_nota VALUES ($disciplina_ofer, $professor, 4, $levantamento, $nota_criterio_4);<br />";

            $nota_criterio_5 = number::decimal_br2numeric(trim($line[6]),2);
            $qry .= "INSERT INTO desempenho_docente_nota VALUES ($disciplina_ofer, $professor, 5, $levantamento, $nota_criterio_5);<br />";
            
            $nota_criterio_6 = number::decimal_br2numeric(trim($line[7]),2);
            $qry .= "INSERT INTO desempenho_docente_nota VALUES ($disciplina_ofer, $professor, 6, $levantamento, $nota_criterio_6);<br />";

            $qry .= "COMMIT; <br /><br />";
                
        }
        else {
            // não existe associação professor <-> disciplina_ofer
            $sql_info = "SELECT descricao_disciplina(get_disciplina_de_disciplina_of($disciplina_ofer)) || ' (' || $disciplina_ofer || ') - '";
            $sql_info .= " || pessoa_nome($professor) || ' (' || $professor || ')';"; 

            $nao_sera_importado .= $conn->get_one($sql_info) .'  ' . $sql_verifica  .'<br />';


        }

        $count++;
    
    } // endwhile;  
  
    @fclose($handle);

    if (strlen($nao_sera_importado) > 0) {

        echo '<h3>N&atilde;o ser&aacute; importado os registros abaixo, pois n&atilde;o existe associa&ccedil;&atilde;o entre o professor(a) e a disciplina informadas</h3>';
        echo $nao_sera_importado;
        
    }

    if (strlen($qry) > 0) {
    echo '<h3>Registro para importa&ccedil;&atilde;o</h3>';
        echo "<br />$qry<br />";
    }
    else {
        echo '<h3>Nenhum registro para importa&ccedil;&atilde;o</h3>';
    }

}
/*

    // CONSULTAS PARA RELATORIO

    SELECT DISTINCT ref_periodo FROM desempenho_docente_nota WHERE ref_professor = 245;

    SELECT ref_disciplina_ofer, descricao_disciplina(get_disciplina_de_disciplina_of(ref_disciplina_ofer)) , ref_criterio, nota_media FROM desempenho_docente_nota WHERE ref_professor = 245 ORDER by ref_disciplina_ofer, ref_criterio;

*/



_desempenho_docente_importa($memory_limit, $csv);



?>
