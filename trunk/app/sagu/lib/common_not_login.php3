<?
set_time_limit(120);

require_once("../../../lib/config.php");

/*
if ( !$SAGU_HOST )
  $SAGU_HOST = "sistemas.cefetbambui.edu.br/sistema_academico/";
*/

// inicialização padrão
$LoginDB     = "sagu";
$LoginUID    = "";
$LoginPWD    = "";
$LoginHost   = "dados.cefetbambui.edu.br";
$LoginURL    = "https://$SAGU_HOST/";
$LoginACL    = "../html/users.acl"; 
$ErrorURL    = "../fatalerror.phtml";
$SuccessURL  = "../modelos/modelo_exito.phtml";


// onde colocar variaveis de ambiente
$SAGU_PATH   = dirname(__FILE) . '/..';

$SQL_Debug   = 1;                            // 1 para gravar os comandos SQL no arquivo $SLQLogFile, 0 para não fazer
$SQL_LogFile = "$SAGU_PATH/logs/sql.log";    // nome do arquivo para gravar os comandos SQL
 
class Query
{
  var $conn;     // the connection id
  var $sql;      // the SQL command string
  var $result;   // the SQL command result set
  var $row;      // the current row index
  
  function Open()
  {   
    LogSQL($this->sql);    
     
    $this->result = pg_exec($this->conn->id,$this->sql); 
    $this->row    = -1;
    
    return $this->result != null;
  }
  
  function Close()
  {
    if ( $this->result != null )
    {
      pg_freeresult($this->result);
    
      $this->result = null;
    }
  }
  
  function MovePrev()
  {
    if ( $this->row >= 0 )
    {
      $this->row--;
      
      return true;
    }
    
    return false;
  }
  
  function MoveNext()
  {
    if ( $this->row + 1 < $this->GetRowCount() )
    {
      $this->row++;
      
      return true;
    }
    
    return false;
  }
  
  function GetRowCount()
  {
    return pg_numrows($this->result);
  }
  
  function GetColumnCount()
  {
    return pg_numfields($this->result);
  }
  
  function GetColumnName($col)
  {
    return pg_fieldname($this->result,$col-1);
  }

  function GetValue($col)
  {
    return pg_result($this->result,$this->row,$col-1);
  }

  function GetRowValues()
  {
    return pg_fetch_row($this->result,$this->row);
  }

  function SetConnection($c)
  {
    $this->conn = $c;
  }
};

/**
 *
 */
class Connection 
{
  var $id;         // the connection identifier
  var $traceback;  // a list of transaction errors
  var $level;      // a counter for the transaction level
  
  // opens a connection to the specified data source
  function Open($no_SaguAssert=false) 
  { 
    global $LoginUID,$LoginPWD,$LoginDB,$LoginHOST;

    $arg = "host=$LoginHOST dbname=$LoginDB port=5432 user=$LoginUID password=$LoginPWD";
    
    $this->id = @pg_Connect($arg);

    $this->level = 0;
    
    if ( empty($no_SaguAssert) || !$no_SaguAssert )
    {
      $err = @$this->GetError();

      SaguAssert($this->id,"Connection : Open(\"user=$LoginUID\") : Connection refused!<br><br>$err");
    } 

    return empty($this->id) ? 0 : $this->id;
 }

  // closes a previously opened connection
  function Close()
  {
    if ( $this->id )
    {
      SaguAssert($this->level==0,"Transactions not finished!");
        
      pg_close($this->id);

      $this->id = 0;
    }
  }
  
  function Begin()
  {
    $this->Execute("begin transaction");
    
    $this->level++;
  }

  function Finish()
  {
    SaguAssert($this->level>0,"Transaction level underrun!");
    
    $success = $this->GetErrorCount() == 0;
    
    if ( $success )
      $this->Execute("commit");
    else
      $this->Execute("rollback");

    $this->level--;
    
    return $success;
  }

  function GetError()
  {
    return pg_errormessage($this->id);
  }

  function GetErrorCount()
  {
    return empty($this->traceback) ? 0 : count($this->traceback);
  }

  function CheckError()
  {
    if ( empty($this->traceback) )
      return;

    $n = count($this->traceback);

    if ( $n > 0 )
    {
      $msg = "";

      for ( $i=0; $i<$n; $i++ )
        $msg .= $this->traceback[$i] . "<br>";

      FatalExit("Transaction Error",$msg);
    }
  }

  function Execute($sql)
  {
    LogSQL($sql);
     
    $rs = pg_exec($this->id,$sql);

    $success = false;

    if ( $rs )
    {
      $success = true;
      pg_freeresult($rs);
    }

    else
      $this->traceback[] = $this->GetError();

    return $success;
  }

  function CreateQuery($sql="")
  { 
    SaguAssert($this->id,"Connection: CreateQuery: Connection ID");
    
    $q = new Query;
    
    $q->conn   = $this;
    $q->sql    = $sql;
    $q->result = 0;
    $q->row    = -1;

    if ( $sql != "" )
      $q->Open();
    
    return $q;
  }
};

// -----------------------------------------------------------
// Use esta função para pré-visualizar um comando sql
// ----------------------------------------------------------
function LogSQL($sql,$force=false)
{
  global $SQL_Debug, $SQL_LogFile, $REMOTE_ADDR, $LoginUID;

  if ( ! $SQL_Debug )
    return;

  // junta multiplas linhas em uma so
  $sql = ereg_replace("\n+ *"," ",$sql);
  $sql = ereg_replace(" +"," ",$sql);

  // elimina espaços iniciais e no final da instrução SQL
  $sql = ereg_replace("^ +| +$","",$sql);

  // traduz aspas " em ""
  $sql = ereg_replace("\"","\"\"",$sql);

  // data e horas no formato "dd/mes/aaaa:hh:mm:ss"
  $dts = date("Y/m/d:H:i:s");

  $cmd = "^\*\*\*|" .                                            // prefixo para comandos quaisquer
         "^ *INSERT|^ *DELETE|^ *UPDATE|^ *ALTER|^ *CREATE|" .   // comandos perigosos SQL
         "^ *BEGIN|^ *COMMIT|^ *ROLLBACK|^ *GRANT|^ *REVOKE";

  $ip  = sprintf("%15s",$REMOTE_ADDR);
  $uid = sprintf("%-10s",$LoginUID);

  if ( $force || eregi($cmd,$sql) )
    error_log("$ip - $uid - [$dts] \"$sql\"\n",3,$SQL_LogFile);

  // may uncomment following lines for testing or debugging
  // else
  //   error_log(">>> $REMOTE_ADDR - $LoginUID - [$dts] \"$sql\"\n",3,$SQL_LogFile);
}

// -----------------------------------------------------------
// Purpose: The exit function is used in order to provide a
//          consistent manner of error handling. This function
//          does not return from execution.
// -----------------------------------------------------------
function FatalExit($msg="",$info="",$href="")
{ global $ErrorURL;

  if ( $msg == "" )
    $msg = "Erro inesperado";

  if ( $info == "" )
    $info = "Causa desconhecida";

  if ( $href == "" )
    $href = "javascript:history.go(-1)";

  if ( $ErrorURL )
  {
    include($ErrorURL);
    die;
  }
 
  echo("<html>");
  echo("<head>");
  echo("<title>Untitled Document</title>");
  echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">");
  echo("</head>");
  echo("");
  echo("<body bgcolor=\"#FFFFFF\">");
  echo("<table width=\"80%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" height=\"90%\">");
  echo("  <tr> ");
  echo("    <td width=\"33%\"> ");
  echo("      <div align=\"center\"><img src=\"../images/logo_ies.gif\" width=\"104\" height=\"94\" align=\"middle\"></div>");
  echo("    </td>");
  echo("    <td width=\"67%\">");
  echo("      <div align=\"center\"><b><font color=\"#000000\" size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\">Aten&ccedil;&atilde;o</font></b></div>");
  echo("    </td>");
  echo("  </tr>");
  echo("  <tr> ");
  echo("    <td colspan=\"2\"> ");
  echo("      <div align=\"center\">");
  echo("        <p><b><font size=\"5\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FF0000\">$msg");
  echo("</font></b><br><br><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\">Causa: $info</font></p>");
  echo("        <p>&nbsp;</p>");
  echo("      </div>");
  echo("    </td>");
  echo("  </tr>");
  echo("  <tr> ");
  echo("    <td colspan=\"2\"> ");
  echo("      <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><a href=\"$href\"><b>Voltar</b></a></font></div>");
  echo("    </td>");
  echo("  </tr>");
  echo("</table>");
  echo("</body>");
  echo("</html>");

  die();
}

// -----------------------------------------------------------
// Purpose: Calls page with information about successful 
// completion
// -----------------------------------------------------------
function SuccessPage($titulo,$goto="history.go(-1)",$info="")
{
  global $SuccessURL, $exito_titulo, $exito_info, $exito_goto;

  $exito_titulo = $titulo;
  $exito_info   = $info;
  $exito_goto   = $goto;

  //  strpos($exito_goto,"location=") != 0 
  if ( substr($exito_goto,0,8) != "history." && substr($exito_goto,0,9) != "location=" )
    $exito_goto = "location='" . $exito_goto . "'";

  LogSQL("\$exito_goto = $exito_goto");

  include($SuccessURL);
}

// -----------------------------------------------------------
// Purpose: Aborts program execution if a condition fails.
// -----------------------------------------------------------
function SaguAssert($cond,$msg="")
{
  if ( $cond == false )
    FatalExit("Erro Inesperado!",$msg);
}

// -----------------------------------------------------------
// Purpose: Checks a list of required input fields. The 
//          argument passed, is expected to be an associative
//          array, whose key is the field name and the value
//          contains the input field's value.
//
//          When 
//          consistent manner of error handling. This function
//          does not return from execution.
// -----------------------------------------------------------
function CheckInputFields($fields,$stop=true,$rname=null)
{
 reset($fields);

 $n = count($fields);

 for ($i=0; $i<$n; $i++ )
 {
   list($key,$val) = each($fields);

   $val = trim($val);

   if ( $val == "" )
   {
     if ( $stop )
       FatalExit("Input Error","Missing value for field (" . ($i + 1) . ") <i>" . $key . "</i>");

     else
     {
       if ( $rname != null )
         $rname = $key;

       return false;
     }
   }
 }
}

// -----------------------------------------------------------
// Purpose: Checks a field value for valid content. This
//          function is mainly for convenience in order to
//          generate a standardized message for an invalid
//          field input.
//
//          When $cond is false, the function generates an
//          error message and does not return from execution.
// -----------------------------------------------------------
function CheckInputValue($name,$cond,$hint="")
{
  if ( !$cond )
  {
    $msg = "Valor informado para o campo <b><i>$name</b></i> é inválido.";

    if ( $hint != "" )
      $msg .= "<br><br><b>Restrição:</b> " . $hint;

    FatalExit("Erro de Digitação!",$msg);
  }
}

// -----------------------------------------------------------
// Purpose: Checks a field value for valid content. This
//          function is mainly for convenience in order to
//          generate a standardized message for an invalid
//          field input.
//
//          When $cond is false, the function generates an
//          error message and does not return from execution.
// -----------------------------------------------------------
function CheckFormParameters($list,$href="")
{
  $n = count($list);

  for ( $i=0; $i<$n; $i++ )
  {
    $name  = $list[$i];

    if ( !$name )
      continue;

    $value = $GLOBALS[$name];

    // if ( empty($value) ) 
    // Com PHP4 o empty causa problema com os campos '0' - Beto - 09/10/2001

    if ($value == '')
    {
      $msg = "Campo obrigatório [<b><i>$name</i></b>] não informado!";

      FatalExit("Erro de Digitação!",$msg,$href);
    }
  }
}

// -----------------------------------------------------------
// Purpose: Checks if a specified keyword matches the list
//          of valid values. If not FatalExit will be called
//          with an appropriate error message.
// -----------------------------------------------------------
function CheckKeyword($name,$kword,$values)
{
  if ( empty($kword) || $kword == "" )
    FatalExit("Parameter Error","Required keyword <b>$name</b> is not specified!");

  else
  {
    $n = count($values);

    for ( $i=0; $i<$n; $i++ )
    {
      if ( $kword == $values[$i] )
        return;
    }

    $msg = "Keyword [<b>$name</b>] contains the unupported value [<b>$kword</b>]!<br><br>" . 
           "Supported values are: [";

    for ( $i=0; $i<$n; $i++ )
    {
      if ( $i > 0 )
        $msg .= ", ";

      $msg .= "<i>" . $values[$i] . "</i>";
    }

    $msg .= "].";

    FatalExit("Parameter Error",$msg);
  }
}

// -----------------------------------------------------------
// Purpose: Prints a debugging message as preformatted text
// -----------------------------------------------------------
function debug($msg)
{
  echo("<pre>$msg</pre>");
}


// -----------------------------------------------------------
// Purpose: Retorna a data do dia no formato D/M/AAAA
// -----------------------------------------------------------
function Today()
{
  $dt = getdate();

  return sprintf("%0.2d/%0.2d/%0.4d",$dt["mday"],$dt["mon"],$dt["year"]);
}


function Today_usa()
{
  $dt = getdate();
  
  return sprintf("%0.4d/%0.2d/%0.2d",$dt["year"], $dt["mon"],$dt["mday"]);
  //return sprintf("%0.4d/%0.2d/%0.2d",$dt["mon"], $dt["mday"],$dt["year"]);
}

// -----------------------------------------------------------
// Purpose: Converte a data de formato D/M/AAAA para AAAA/M/D
// -----------------------------------------------------------
function DMA_To_AMD($dt)
{
  list ( $d, $m, $a ) = split("/",$dt,3);

  return sprintf("%0.4d/%0.2d/%0.2d",$a,$m,$d);
}

// -----------------------------------------------------------
// Purpose: Converte a data de formato M/D/A para D/M/A 
// -----------------------------------------------------------
function MDA_To_DMA($dt)
{
  list ( $d, $m, $a ) = split("-",$dt,3);

  return sprintf("%0.2d/%0.2d/%0.2d",$m,$d,$a);
}

// -----------------------------------------------------------
// Purpose: Obter ID de uma seqüencia
// -----------------------------------------------------------
function GetIdentity($seq,$SaguAssert=true,$msg="")
{
  $conn = new Connection;

  $conn->Open();
    
  $sql = "select nextval('$seq')";
  
  $query = @$conn->CreateQuery($sql);
  
  $success = false;
  
  if ( @$query->MoveNext() )
  {
    $id = $query->GetValue(1);
    
    $success = true;
  }

  $err = $conn->GetError();
  
  $query->Close();

  SaguAssert(!$SaguAssert || $success,$msg ? $msg : "Nao foi possivel obter um código de '$seq'<br><br>$err!");

  return $id;
}

//
// converte real para inteiro
//
function real_to_int($valor)
{
   $valor_string = "$valor";
   $valor_novo = "";
   $n = 0;
   while (($n<strlen($valor_string)) && ($valor_string[$n] != "."))
   {
      $valor_novo = "$valor_novo$valor_string[$n]";
      $n ++;
   }
   return($valor_novo);
}
?>
