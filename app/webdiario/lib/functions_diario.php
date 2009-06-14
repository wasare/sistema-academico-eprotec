<?php
/*
====================================
 DESENVOLVIDO SOBRE LEIS DA GNU/GPL
====================================

E-CNEC : ti@cneccapviari.br

CNEC CAPVIARI - www.cneccapivari.br
Rua Barão do Rio Branco, 347, Centro - Capivari/SP
Tel.: (19)3492-1869

MODIFICADO E EXPANDIDO PELO CEFET-BAMBUÍ

CEFET-BAMBUI - www.cefetbambui.edu.br

*/


function diario_open_db() {
    global $dbconnect, $error_msg;
    global $host, $dbname, $dbuser, $dbpassword;

    if (($dbconnect = pg_Pconnect("host=$host user=$dbuser password=$dbpassword dbname=$dbname")) == false) {
              $error_msg="Não foi possível estabeler conexão com o Banco: " . $dbname;
              }
    return $dbconnect;
}

function diario_error_text() {
  global $error_msg;

  return $error_msg;
}

function diario_close_db() {
  global $dbconnect;
  if ($dbconnect == true) {
     Pg_Close($connectdb);
     }
 return;
}


function diario_sql($u, $p, $sp=0) {

   global $dbconnect, $error_msg, $P, $L, $Autorizado;

   $sql_query = "SELECT id_nome, login, nivel from diario_usuarios where login = '$u' and senha = '$p'";

   if (!$dbconnect) {
       if (!($dbconnect = diario_open_db())) {
             return null;
           }
   }

	if (( $result_sql = pg_exec($dbconnect, $sql_query)) == false) {
        $error_msg="Error ao executar a consulta: " . $sql_query;
        return false;
	}
	else {

        $numrows = pg_NumRows($result_sql);

        if ($numrows > 0) {

            $robject = pg_fetch_object($result_sql, 0);

			$campo['login'] = $robject->login;
			$campo['id_nome'] = $robject->id_nome;
			$campo['nivel'] = $robject->nivel;

			$autoriza = 0;

            if(in_array($sp, $Autorizado)) {

				$autoriza = 1;
			}
			else {

				if(in_array($campo['id_nome'],$L[$sp])) {

					$autoriza = 1;
				}
			}

			if($sp != 0 && $campo['nivel'] == 1 && $autoriza == 1) {

					$sql1 = 'SELECT DISTINCT
				      a.login,  a.id_nome, b.ref_professor, c.ref_periodo, d.descricao
      					FROM diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      					WHERE a.login = \''.$u.'\'
      					AND a.id_nome = b.ref_professor
      					AND b.ref_disciplina_ofer =  c.id
      					AND c.ref_periodo = d.id
      					AND c.ref_periodo IN ('.$P[$sp].');';

					$qry1 = consulta_sql($sql1);

					if(is_string($qry1)) {
						echo $qry1;
						$ret = false;
						exit;
					}
					else {
						if(pg_numrows($qry1) == 0) {

							$campo['diario'] = 'semdiario';
							return $campo;
						}
					}
			}
			else {
			    if($campo['nivel'] != 1) {
					return $campo;
				}
				else {
					$campo['diario'] = 'semdiario';
					return $campo;
				}
			}
        }
        else {
            $campo['diario'] = "invalido";
            return $campo;
        }
        return $campo;
    }
}

function consulta_sql($sql_query) {
   global $dbconnect, $error_msg;

   if (!$dbconnect) {
       if (!($dbconnect = diario_open_db())) {
             return null;
           }
    }

    // echo $sql_query; 

    if (( $result_sql = pg_exec($dbconnect, $sql_query)) == false) {
        $error_msg = "Error ao executar a consulta: " . $sql_query;
		$error_msg .= '<br /> <br />Entre em contato com o respons&aacute;vel: ';
		$error_msg .= '<a href="javascript:history.go(-1)">Voltar</a></b>';
        return $error_msg;
    } else {
        //$rows = pg_fetch_array($result_sql);
		//echo pg_result_error($result_sql);

        return $result_sql;
    }
}

function consulta_while($sql_query) {
   global $dbconnect, $error_msg;

   if (!$dbconnect) {
       if (!($dbconnect = diario_open_db())) {
             return null;
           }
    }

    if (( $result_sql = pg_exec($dbconnect, $sql_query)) == false) {
        $error_msg="Error ao executar a consulta: " . $sql_query;
        return false;
    } else {
      while ($rows = pg_fetch_array($result_sql)) {
        $sql_resultado = $rows;
        return $rows;
      }
    }
}


function diario_begin_transaction() {
   global $dbconnect;

     if (!$dbconnect) {
         if (!($dbconnect = diario_open_db())) {
            return null;
            }
        }
  return pg_exec($dbconnect, "begin;");
}

function diario_commit_transaction() {
   global $dbconnect;

     if (!$dbconnect) {
         if (!($dbconnect = diario_open_db())) {
            return null;
            }
        }
  return pg_exec($dbconnect, "commit;");
}


function diario_rollback() {
   global $dbconnect;

     if (!$dbconnect) {
         if (!($dbconnect = diario_open_db())) {
            return null;
            }
        }
  return pg_exec($dbconnect, "rollback;");
}

function br_date($date)
{
  $dia = Substr($date, 8, 2);
  $mes = Substr($date, 5, 2);
  $ano = Substr($date, 0, 4);
  $newdate = $dia . '/' . $mes . '/' . $ano;
  return $newdate;
}

function media($media)
{
   $f = strrchr($media, ".");
   if($f == "")
   {
      $newmedia = $media;
   }
   else
   {
      $string = $media;

      $separat = ".";

      $c = substr($string, 0, strlen($string)-strlen (strstr ($string,$separat)));

      if($media == "10.0")
      {
        $newmedia = $media;
      }
      else
      {
         if($f == ".0")
         {
            $newmedia = $c . ".0";
         }
         if($f == ".1")
         {
            $newmedia = $c . ".0";
         }
         if($f == ".2")
         {
            $newmedia = $c . ".0";
         }
         if($f == ".3")
         {
            $newmedia = $c . ".5";
         }
         if($f == ".4")
         {
            $newmedia = $c . ".5";
         }
         if($f == ".5")
         {
            $newmedia = $c . ".5";
         }
         if($f == ".6")
         {
            $newmedia = $c . ".5";
         }
         if($f == ".7")
         {
            $newmedia = $c . ".5";
         }
         if($f == ".8")
         {
            $newmedia = $c + 1 . ".0";
         }
         if ($f == ".9")
         {
            $newmedia = $c + 1 . ".0";
         }
      }
   }
    return $newmedia;
}

// function adiciona falta
//falta($periodo, $ra, $disciplina, $getofer, 1, 'SUB');
function falta($ref_periodo, $ra_cnec, $ref_disciplina, $ref_disciplina_ofer, $qtde, $par1, $qry='BEGIN;')
{
   global $dbconnect, $error_msg;
   global $host, $dbname, $dbuser, $dbpassword;

    // abrir tabela de matricula e selecionar as faltas
  /*
   $sqlaluno = "SELECT id FROM pessoas WHERE ra_cnec = '$ra_cnec';";

   echo $sqlaluno.'<br />';

   $queryaluno = pg_exec($dbconnect, $sqlaluno);

   while($row1 = pg_fetch_array($queryaluno))
   {
      $ref_pessoa = $row1["id"];
   }
   */

   $ref_pessoa = $ra_cnec;

   $sqlf = "SELECT num_faltas
            FROM
                  matricula
            WHERE
                  ref_periodo = '$ref_periodo' AND
                  ref_pessoa = '$ref_pessoa' AND
                  ref_disciplina = '$ref_disciplina' AND
                  ref_disciplina_ofer = $ref_disciplina_ofer;";



	 $sqlf = "SELECT
					count(ra_cnec) AS num_faltas
				FROM
					diario_chamadas a
				WHERE
					(a.ref_periodo = '$ref_periodo') AND
					(a.ref_disciplina_ofer = '$ref_disciplina_ofer') AND
					(ra_cnec = '$ref_pessoa');";


   $queryf = consulta_sql($sqlf);

   if(is_string($queryf))
   {
	   echo $queryf;
	   exit;
   }
   else
   {
		while($row1 = pg_fetch_array($queryf))
		{
			$totfaltas = $row1['num_faltas'];
		}
   }

   if ( $totfaltas > 0 )
   {
      $totfaltas = $totfaltas;
   }
   else
   {
      $totfaltas = 0;
   }


   if($par1 == "SOMA")
   {
      $totfaltas = $totfaltas + $qtde;
   }

   if($par1 == "SUB")
   {
      $totfaltas = $totfaltas - $qtde;
   }

	// $totfaltas = $totfaltas + $qtde;


  // echo '$totfaltas:'.$totfaltas.'<br />';
   //echo '$qtde:'.$qtde.'<br />';
   //die;

   $sqlfalta = $qry . "UPDATE
                  matricula
               SET
                  num_faltas = $totfaltas
               WHERE
                  ref_periodo = '$ref_periodo' AND
                  ref_pessoa = '$ref_pessoa' AND
                  ref_disciplina = '$ref_disciplina' AND
                  ref_disciplina_ofer = $ref_disciplina_ofer;";

   $sqlfalta .= 'COMMIT;';

   //echo $sqlfalta.'<br />';

   $qryfalta = consulta_sql($sqlfalta);

   if(is_string($qryfalta))
   {
	   echo $qryfalta;
	   exit;
   }
   else
   {
	   return $qryfalta;
   }

}

function getNumeric2Real($nNumeric)
{
     setlocale(LC_CTYPE,"pt_BR");

     $Real = explode('.',$nNumeric);

     $Inteiro = $Real[0];

     $CasaDecimal = substr(@$Real[1], 0, 2);

     if ( strlen($CasaDecimal) < 2 )
     {
        $CasaDecimal = str_pad($CasaDecimal, 1, "0", STR_PAD_RIGHT);
     }

     $InteiroComMilhar = number_format($Inteiro, 0, '.', '.');

     $Real = $InteiroComMilhar.','.$CasaDecimal;

     return $Real;
}

function getReal2Numeric($rValor)
{
     setlocale(LC_CTYPE,"pt_BR");

     $ValNumeric =  str_replace(",", "+", $rValor);
     $ValNumeric =  str_replace(".", "", $ValNumeric);
     $ValNumeric =  str_replace("+", ".", $ValNumeric);

     $Numeric = explode('.',$ValNumeric);

     $Inteiro = $Numeric[0];
     $Decimal = substr($Numeric[1], 0, 2);

     if(strlen($Decimal) < 2)
     {
        $Decimal = str_pad($Decimal, 1, "0", STR_PAD_RIGHT);
     }

     $ValNumeric = $Inteiro.'.'.$Decimal;

     return $ValNumeric;

}

function getDias($nData)
{
     setlocale(LC_CTYPE,"pt_BR");

     // DESMEMBRA DATA
     $nData = explode("/","$nData");
     $dia = $nData[0];
     $mes = $nData[1];
     $ano = $nData[2];

    $TimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $mes, $dia, $ano);
    $Dias = $TimeStamp / 86400;

    $Idade = floor($Dias / 365.25);

    return $Dias;

}

function getIniSem()
{
   $m = date("m");

   if($m > 07)
   {
      $m = '06';
   }
   else
   {
      $m = '01';
   }

   //echo $m;
   return $m;


}

function getPeriodos($user,$nivel=1)
{
   global $dbconnect, $error_msg, $speriodo;

   $m = getIniSem();

   $DataInicial = date("01/07/2007");
   $DataFinal = date("31/12/2007");

   //echo $_SESSION['lst_periodo']; die;

   $sql1 = 'SELECT DISTINCT
      a.login,
      a.id_nome,
      b.ref_professor,
      c.ref_periodo,
      d.descricao
      FROM diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE a.login = \''.$user.'\'
      AND a.id_nome = b.ref_professor
      AND b.ref_disciplina_ofer =  c.id
      AND c.ref_periodo = d.id
	  AND c.ref_periodo IN ('.$speriodo.');';

      $qry1 = consulta_sql($sql1);

	  if(is_string($qry1))
      {
		echo $qry1;
        $ret = false;
		exit;
      }
      else
      {
		  if(pg_numrows($qry1) > 0)
		  {
			while($linha = pg_fetch_array($qry1))
			{
				$nomeperiodo = @$linha['descricao'];
				$codiperiodo = @$linha['ref_periodo'];
				$id = @$linha['ref_professor'];

				echo '<tr><td width="5" height="6"><img src="img/menu_seta.gif" width="5" height="5"></td>';
				echo '<td width="106" height="6"><a href="periodo.php?periodo='.$codiperiodo.'" target="principal"><font color="#000099" size="1">'.$nomeperiodo.'</font></a></td></tr>';

				$ret = true;
			}
		  }
		  else
		  {
                echo '<tr><td width="5" height="6"><img src="img/menu_seta.gif" width="5" height="5"></td>';
                echo '<td width="106" height="6"><font color="#000099" size="1"><b>Indispon&iacute;vel.</b></font></td></tr>';
                $ret = false;
		  }

      }
}




function getNomePeriodo($id)
{
   global $dbconnect, $error_msg;

   $sql1 = "SELECT
                  descricao
               FROM
                  periodos
            WHERE id = '$id'";

      $query1 = pg_exec($dbconnect, $sql1);

      $row = pg_fetch_array($query1);

         $dsc_periodo = $row['descricao'];
         return $dsc_periodo;
}

function getHeaderDisc($ofer)
{
   global $dbconnect, $error_msg;

/*
   $sql9 = "SELECT DISTINCT
         a.id || ' - ' || a.descricao as cdesc,
         b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
         c.descricao as perdesc,
         d.ref_curso,
         f.nome
         FROM
          cursos a,
          disciplinas b,
          periodos c,
          disciplinas_ofer d,
		  disciplinas_ofer_prof e,
		  pessoas f
		WHERE
          d.ref_periodo = '$periodo' AND
		  e.ref_professor = f.id AND
		  d.id = e.ref_disciplina_ofer AND
          b.id = '$disc' AND
          c.id = '$periodo' AND
          d.id = '$ofer' AND
          d.ref_disciplina = '$disc' AND
          d.is_cancelada = 0 AND
          a.id = d.ref_curso";

*/
	$sql9 = "SELECT DISTINCT
          a.id || ' - ' || a.descricao as cdesc,
          b.id || ' - ' || b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
          c.descricao as perdesc,
          d.ref_curso,
          f.nome
         FROM
          cursos a LEFT OUTER JOIN disciplinas_ofer d ON (a.id = d.ref_curso) LEFT OUTER JOIN disciplinas b ON (d.ref_disciplina = b.id) LEFT OUTER JOIN periodos c ON (c.id = d.ref_periodo) LEFT OUTER JOIN disciplinas_ofer_prof e ON (e.ref_disciplina_ofer = d.id) LEFT OUTER JOIN pessoas f ON (e.ref_professor = f.id)
        WHERE
          d.id = $ofer AND
          d.is_cancelada = 0
        ORDER BY f.nome;";

    //echo $sql9;	exit;

    $qry9 = consulta_sql($sql9);

	if(is_string($qry9))
	{
		echo $qry9;
		exit;
	}
	else {
		 $profs = pg_numrows($qry9);
	}

    while ( $linha9 = pg_fetch_array($qry9) )
    {
        $Disc['curso'] = $linha9["cdesc"];
        $Disc['disc']  = $linha9["descricao_extenso"];
        $Disc['periodo']   = $linha9["perdesc"];
        $Disc['ref_curso']   = $linha9["ref_curso"];
		$Disc['prof'][]   = $linha9["nome"];
    }

    $HDisc .= '<input type="hidden" name="curso" id="curso" value="'.$Disc['ref_curso'].'" />';

	$HDisc = 'Curso: <b>'.$Disc['curso'].'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
	$HDisc .= 'Disciplina: <b>'.$Disc['disc'].'</b><br>';
	$HDisc .= 'Per&iacute;odo: <b>'.$Disc['periodo'].'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
	$HDisc .= 'Professor(a): ';

	for($p = 0 ; $p < $profs; $p++) {

		$HDisc .= '&nbsp;&nbsp;<b>'.$Disc['prof'][$p].'</b><br />';

		if(($profs - 1) > $p) {
			$HDisc .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
	}

		return $HDisc;
	}


	function getAnoPeriodo($periodo)
	{
	   global $dbconnect, $error_msg;

	   $qry1 = 'SELECT
					dt_inicial, dt_final
				FROM periodos WHERE id = \''.$periodo.'\';';

		//echo $qry1;

		$query1 = pg_exec($dbconnect, $qry1);


		while ( $linha = pg_fetch_array($query1) )
		{
			$DataPeriodo[] = date($linha['dt_inicial']);
			$DataPeriodo[]  = $linha['dt_final'];
		}

		return $DataPeriodo;
	}
/*
	function msgJaExiste()
	{
	  echo '<script language=javascript> window.alert("Já existe chamada realizada para esta data.");	javascript:window.history.back(1); </script>';
	  die;

	}
*/
	function getTime($t)
	{
	  if ($t == 0 ) return date("Y-m-d");
	  if ($t == 1 ) return date("H:i:s");
	}


	function getCurso($p,$d,$o) {


	// VAR CONSULTA
	$sql9 = "SELECT
			 a.descricao as cdesc,
			 b.descricao_extenso,
			 c.descricao as perdesc,
			 d.ref_curso
			 FROM
			  cursos a,
			  disciplinas b,
			  periodos c,
			  disciplinas_ofer d  where
			  d.ref_periodo = '$p' AND
			  b.id = '$d' AND
			  c.id = '$p' AND
			  d.id = '$o' AND
			  a.id = d.ref_curso;";

	//echo $sql9;
	//exit;

		$qry9 = consulta_sql($sql9);

		if(is_string($qry9)) {

			echo $qry9;
			exit;
		}

		while($linha9 = pg_fetch_array($qry9)) {
			$curso   = $linha9["ref_curso"];
		}

		return $curso;

	}


	function getCursoTipo($o) {


	// CONSULTA O NIVEL DO CURSO
		$sqlCursoTipo = 'SELECT
							ref_tipo_curso
						FROM
							cursos c, disciplinas_ofer d
						WHERE
							 c.id = ref_curso AND
						 d.id = '.$o.';';

		$qryCursoTipo = consulta_sql($sqlCursoTipo);

				   if(is_string($qryCursoTipo))
				   {
					  echo $qryCursoTipo;
					  exit;
				   }
				   else {

					  $CursoTipo = pg_fetch_array($qryCursoTipo);
					  $CursoTipo = $CursoTipo['ref_tipo_curso'];

					  return $CursoTipo;
				   }

	}

	function coordena_sql($u, $cursos=0) {

		$sql1 = 'SELECT DISTINCT ref_curso
					FROM coordenadores
					WHERE
					ref_professor = '.$u.';';


		$qry1 = consulta_sql($sql1);

		if(is_string($qry1))
		{
			echo $qry1;
			exit;
		}
		else {

			$num_cursos = pg_numrows($qry1);

			$coordena = 0;

			if($num_cursos > 0) {

			$coordena = 1;

        	$cursos_coordenados = '';

            $c_total = $num_cursos - 1;

            $arr = pg_fetch_all_columns($qry1, 0);

            for($c = 0 ; $c < $num_cursos; $c++) {

            	$cursos_coordenados .= @$arr[$c];

				if($c != $c_total) { $cursos_coordenados .= ','; }

            }
		}
   	}

	if ($cursos != 0) {
		return $cursos_coordenados;
	}
	else {
		return $coordena;
	}
}

function is_finalizado($o) {

  $sqlOfer = 'SELECT
                  fl_digitada
                     FROM
                        disciplinas_ofer d
                     WHERE
                        d.id = '.$o.';';

  $qryOfer = consulta_sql($sqlOfer);

  if(is_string($qryOfer))
  {
  	echo $qryOfer;
    exit;
  }
  else {
     $Ofer = pg_fetch_array($qryOfer);
     $Ofer = $Ofer['fl_digitada'];
		
	if ($Ofer == 't')
	{
    	return TRUE;
	}
	else{
		return FALSE;
	}
  }
}

function is_inicializado($periodo,$ofer) {

    $grupo = ("%-" . $periodo . "-%-" . $ofer);

	$sql1 = "SELECT
         grupo
         FROM diario_formulas
         WHERE
         grupo ILIKE '$grupo';";

	$qry1 = consulta_sql($sql1);
	
	if(is_string($qry1))
  	{
    	echo $qry1;
    	exit;
  	}
  	else 
	{
	
    	$num_reg = pg_numrows($qry1);
		if ($num_reg == 6)
    	{
        	return TRUE;
    	}
    	else
		{
        	return FALSE;
    	}
	}
}

function inicializaDiario($disc,$ofer,$periodo,$prof) {

    $grupo = ($prof . "-" . $periodo . "-" . $disc . "-" . $ofer);
    $grupo_novo = ("%-" . $periodo . "-%-" . $ofer);

    $curso = getCurso($periodo,$disc,$ofer);

    $ret = TRUE;

    // PASSO 1
	$numprovas = 6;

	// PASSO 2
    $formula = '';
	for ($cont = 1; $cont <= $numprovas; $cont++) 
	{
	    $prova[] = 'Nota '.$cont;

        if($cont == 1)
        {
            $formula .= 'P'.$cont;
        }
        else 
        {
            $formula .= '+P'.$cont;
        }
	}    

	// PASSO 3
	$sqldel = "BEGIN; DELETE FROM diario_formulas WHERE grupo ILIKE '$grupo_novo';";
	$sqldel .= "DELETE FROM diario_notas WHERE rel_diario_formulas_grupo ILIKE '$grupo_novo'; COMMIT;";

	$qrydel =  consulta_sql($sqldel);

	if(is_string($qrydel))
	{
		//echo $qrydel;
		//exit;
        $ret = FALSE;
	}

	reset($prova);

	$sql1 = 'BEGIN;';

	while (list($index,$value) = each($prova)) 
	{
		$descricao_prova = $prova[$index];
		$num_prova = ($index+1);
		$sql1 .= "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values('$prof','$periodo','$disc','$num_prova','$descricao_prova','$formula','$grupo');";
   
	}

	$sql1 .= 'COMMIT;';

	$qry1 = consulta_sql($sql1);

	if(is_string($qry1))
	{
		$ret = FALSE;
	}

    // PASSO 4 - PROCESSA CRIA REGISTROS DE ACORDO COM A FORMULA
    $qryNotas = 'SELECT
        m.ref_pessoa, id_ref_pessoas
        FROM
            matricula m
        LEFT JOIN (
                SELECT DISTINCT
                d.id_ref_pessoas
            FROM
                diario_notas d
            WHERE
                d.d_ref_disciplina_ofer = ' . $ofer . '
              ) tmp
        ON ( m.ref_pessoa = id_ref_pessoas )
        WHERE
            m.ref_disciplina_ofer = ' . $ofer . ' AND
            id_ref_pessoas IS NULL AND
			(m.dt_cancelamento is null) AND
			(m.ref_motivo_matricula = 0)
        ORDER BY
                id_ref_pessoas;';

	$qry = consulta_sql($qryNotas);

	if(is_string($qry))
	{
	    $ret = FALSE;
	}


    $qryDiario = "BEGIN;";

	  while($registro = pg_fetch_array($qry))
      {
			$ref_pessoa = $registro['ref_pessoa'];

			for($i = 1 ; $i <= $numprovas; $i++)
			{
				$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
	            $qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
		        $qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
			    $qryDiario .= ' rel_diario_formulas_grupo)';
				$qryDiario .= " VALUES($ref_pessoa,'$i','0','0',$ref_pessoa,'$periodo',$curso,";
	            $qryDiario .= " $ofer,'$grupo');";
			}

				$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
		        $qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
				$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
				$qryDiario .= ' rel_diario_formulas_grupo)';
				$qryDiario .= " VALUES($ref_pessoa,'7','-1','0',$ref_pessoa,'$periodo',$curso,";
				$qryDiario .= " $ofer,'$grupo');";
	 }


	 $qryDiario .= "COMMIT;";

     $res = consulta_sql($qryDiario);

     if(is_string($res))
     {
         $ret = FALSE;
     }

    return $ret;
}




if(!isset($_SESSION['login']) && !is_string($_SESSION['login']) && !isset($_SESSION['nivel']) && !is_integer($_SESSION['nivel']) ) {

   header("Location: $BASE_URL".'index.php');
   exit;

}
?>
