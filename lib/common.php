<?php

/**
 * Chamada do arquivo de configuracao do SAGU
 */
require_once(dirname(__FILE__) . '/config.php');

/**
 * Classe de abstracao de dados do SAGU
 */
class Query {

    var $conn;     // the connection id
    var $sql;      // the SQL command string
    var $result;   // the SQL command result set
    var $row;      // the current row index

    function Open() {

        LogSQL($this->sql);

        $this->result = pg_exec($this->conn->id,$this->sql);
        $this->row    = -1;

        return $this->result != null;
    }

    function Close(){

        if ( $this->result != null ){
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

    function GetAllValues()
    {
        return pg_fetch_all($this->result);
    }

    function SetConnection($c)
    {
        $this->conn = $c;
    }
}

/**
 * Classe de conexao do SAGU
 */
class Connection  {


    var $id;         // the connection identifier
    var $traceback;  // a list of transaction errors
    var $level;      // a counter for the transaction level


    // opens a connection to the specified data source
    function Open($no_SaguAssert=false)
    {
        global $LoginUID,$LoginPWD,$LoginDB,$LoginHost;

        $SessionAuth = $_COOKIE['SessionAuth'];

        if ( ! empty($SessionAuth) ){
            list ( $LoginUID, $LoginPWD ) = split(":",$SessionAuth,2);

        }
        // LogSQL("*** SessionAuth=$SessionAuth ***");

        $arg = "host=$LoginHost dbname=$LoginDB port=5432 user=$LoginUID password=$LoginPWD";

        // $this->id = @pg_pConnect($arg);
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

        $sql = str_replace("''",'NULL',$sql);

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

}

/**
 * Use esta fun��o para pr�-visualizar um comando sql
 */
function LogSQL($sql,$force=false)
{
    global $SQL_Debug, $SQL_LogFile, $REMOTE_ADDR, $LoginUID;

    if ( ! $SQL_Debug )
    return;

    // junta multiplas linhas em uma so
    $sql = ereg_replace("\n+ *"," ",$sql);
    $sql = ereg_replace(" +"," ",$sql);

    // elimina espa�os iniciais e no final da instru��o SQL
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

}

// -----------------------------------------------------------
// Purpose: The exit function is used in order to provide a
//          consistent manner of error handling. This function
//          does not return from execution.
// -----------------------------------------------------------
function FatalExit($msg="",$info="",$href=""){

    global $ErrorURL;

    if ( $msg == "" )
    $msg = "Erro inesperado ou acesso proibido";

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
    echo("      <div align=\"center\"><img src=\"../images/univates.gif\" width=\"104\" height=\"94\" align=\"middle\"></div>");
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
function SuccessPage($titulo,$goto="history.go(-1)",$info="",$button="")
{
    global $SuccessURL, $exito_titulo, $exito_goto, $exito_info, $exito_button;

    $exito_titulo = $titulo;
    $exito_goto   = $goto;
    $exito_info   = $info;
    $exito_button = $button;

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
    FatalExit("Erro inesperado ou acesso proibido!",$msg);
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
        $msg = "Valor informado para o campo <b><i>$name</b></i> � inv�lido.";

        if ( $hint != "" )
        $msg .= "<br><br><b>Restri��o:</b> " . $hint;

        FatalExit("Erro de Digita��o!",$msg);
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
            $msg = "Campo obrigat�rio [<b><i>$name</i></b>] n�o informado!";

            FatalExit("Erro de Digita��o!",$msg,$href);
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
// Purpose: Obter ID de uma seq�encia
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

    SaguAssert(!$SaguAssert || $success,$msg ? $msg : "Nao foi possivel obter um c�digo de '$seq'<br><br>$err!");

    return $id;
}

// -----------------------------------------------------------
// Purpose: Checks if the current session has the SessionAuth
//          cookie defined, if it is not defined, the default 
//          login page is called.
// -----------------------------------------------------------
function CheckLogin(){

    global $SessionAuth,$LoginURL;

    if ( empty($SessionAuth) )
    {
        Header("Location: $LoginURL");

        exit;
    }
}

// -----------------------------------------------------------
// userid : allowed | denied : url1,url2,
// -----------------------------------------------------------
function CheckAccess($user,$path){

    global $LoginACL;

    // LogSQL("*** CheckAccess($user,$path) ***");

    $file = @fopen($LoginACL,"r");

    if ( $file )
    {
        $ok = false;

        $done = false;

        while ( $ln = fgets($file,4096) )
        {
            // ignore comment or empty lines
            if ( ereg("^ *#|^ *$",$ln) )
            continue;

            // userid: url,url,...
            list ( $uid, $action, $url_list ) = split(":",$ln,3);

            $uid      = trim($uid);
            $action   = strtoupper(trim($action));
            $url_list = trim($url_list);

            // LogSQL("*** ACL $uid, $action, $url_list ***");

            // if ( $user == "pablo" )
            //   LogSQL("*** ACL $uid, $action, $url, $path ***");

            if ( $uid == $user || $uid == "*" )
            {
                $a = split(",",$url_list);

                for ( $i=0; $i < count($a); $i++ )
                {
                    $ok = false;

                    $s = trim($a[$i]);

                    if ( $action == "ALLOW" )
                    {
                        $ok = $path == "*" || ereg("^$s",$path);

                        if ( $ok )
                        {
                            $done = true;
                            break;
                        }
                    }

                    else if ( $action == "DENY" )
                    {
                        $ok = $path != "*" && ! ereg("^$s",$path);

                        // LogSQL("*** ACL DENY: ereg('^$s',$path) -> $ok ***");

                        if ( ! $ok )
                        {
                            $done = true;
                            break;
                        }
                    }

                    else
                    ASSERT(1,"ERROR: Invalid ACCESS CONTROL option!");

                    // if ( $user == "pablo" )
                    //   LogSQL("*** ACL $uid, $action, $s, $path  = $ok ***");
                }
            }

            if ( $done )
            break;
        }

        fclose($file);

        if ( ! $ok )
        {
            LogSQL("*** ACL ACCESS DENIED (uid=$user,path=$path) ***");

            SaguAssert($ok,"ACCESS DENIED");
        }
    }
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




function GetEmpresa($id,$SaguAssert)
{
    $sql = "select id,razao_social from configuracao_empresa where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
    $obj = $query->GetValue(2);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
    SaguAssert(!empty($obj),"Empresa [$id] nao definido!");

    return $obj;
}



// ------------------------------------------------------------------
// [PABLO:] Para testes use banco de dados teste para usu�rio teste
// ------------------------------------------------------------------
if ( ! empty($SessionAuth) )
{
    // buscar authentica��o do cookie
    list ( $UID, $PWD ) = split(":",$SessionAuth,2);

    // caso usu�rio � teste vai para o bd teste
    if ($UID == "teste")
    $LoginDB = "teste";
}



// Faz a verificacao de login

$no_login_check = $_COOKIE['no_login_check'];

if ( empty($no_login_check) || !$no_login_check )
{
    CheckLogin();
    // buscar authentica��o do cookie
    list ( $LoginUID, $LoginPWD ) = split(":",$SessionAuth,2);
}

?>
