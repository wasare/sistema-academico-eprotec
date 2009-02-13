<?php
/*
    package::i.tools
    
    php-downloader  v1.0    -   www.ipunkt.biz
    
    (c) 2002 - www.ipunkt.biz (rok)
*/

//  INFO    //
$info  = 'php-downloader v1.0 - www.ipunkt.biz'."\n";
$info .= '===================================='."\n";
$info .= $_SERVER['HTTP_REFERER']."\n";
//  USER-INFO   //
// Whether the os php is running on is windows or not
if (!defined('IS_WINDOWS')) {
    if (defined('PHP_OS') && eregi('win', PHP_OS)) {
        define('IS_WINDOWS', 1);
    } else {
        define('IS_WINDOWS', 0);
    }
}

// Determines platform (OS), browser and version of the user
// Based on a phpBuilder article:
//   see http://www.phpbuilder.net/columns/tim20000821.php
if (!defined('USR_OS')) {
    // loic1 - 2001/25/11: use the new globals arrays defined with php 4.1+
    if ( !empty($_SERVER['HTTP_USER_AGENT']) )
    {
        $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    }
    elseif ( !empty($HTTP_SERVER_VARS['HTTP_USER_AGENT']) )
    {
        $HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
    }
    elseif ( !isset($HTTP_USER_AGENT) )
    {
        $HTTP_USER_AGENT = '';
    }

    // 1. Platform
    if ( strstr($HTTP_USER_AGENT, 'Win') )
    {
        define('USR_OS', 'Win');
    }
    elseif ( strstr($HTTP_USER_AGENT, 'Mac') )
    {
        define('USR_OS', 'Mac');
    }
    elseif ( strstr($HTTP_USER_AGENT, 'Linux') )
    {
        define('USR_OS', 'Linux');
    }
    elseif ( strstr($HTTP_USER_AGENT, 'Unix') )
    {
        define('USR_OS', 'Unix');
    }
    elseif ( strstr($HTTP_USER_AGENT, 'OS/2') )
    {
        define('USR_OS', 'OS/2');
    }
    else
    {
        define('USR_OS', 'Other');
    }

    // 2. browser and version
    if ( ereg('Opera(/| )([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version) )
    {
        define('USR_BROWSER_VER', $log_version[2]);
        define('USR_BROWSER_AGENT', 'OPERA');
    }
    elseif ( ereg('MSIE ([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version) )
    {
        define('USR_BROWSER_VER', $log_version[1]);
        define('USR_BROWSER_AGENT', 'IE');
    }
    elseif ( ereg('OmniWeb/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version) )
    {
        define('USR_BROWSER_VER', $log_version[1]);
        define('USR_BROWSER_AGENT', 'OMNIWEB');
    }
    elseif ( ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version) )
    {
        define('USR_BROWSER_VER', $log_version[1]);
        define('USR_BROWSER_AGENT', 'MOZILLA');
    }
    elseif ( ereg('Konqueror/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version) )
    {
        define('USR_BROWSER_VER', $log_version[1]);
        define('USR_BROWSER_AGENT', 'KONQUEROR');
    }
    else
    {
        define('USR_BROWSER_VER', 0);
        define('USR_BROWSER_AGENT', 'OTHER');
    }
}



if ( isset($_REQUEST['file']) )
{
    //  defines filename, extension and also mime types
    if ( file_exists($_REQUEST['file']) )
    {
        $pi = pathinfo($_REQUEST['file']);
        $path = $pi['dirname'].'/';
        $filename = $pi['basename'];
        
        $info .= $filename.' ('.filesize($path.$filename).' bytes)';
        $size = filesize($path.$filename);
        
        if ( $_REQUEST['method'] && ($_REQUEST['method'] == 'gzip' || $_REQUEST['method'] == 'tar') )
        {
            $ext = '.tar.gz';
            $mime_type = 'application/x-gzip';
        }
        elseif ( $_REQUEST['method'] && $_REQUEST['method'] == 'zip')
        {
            $ext = '.zip';
            $mime_type = 'application/x-zip';
        }
        else
        {
            $ext       = '';
            // loic1: 'application/octet-stream' is the registered IANA type but
            //        MSIE and Opera seems to prefer 'application/octetstream'
            $mime_type = (USR_BROWSER_AGENT == 'IE' || USR_BROWSER_AGENT == 'OPERA')
                ? 'application/octetstream'
                : 'application/octet-stream';
        }
    
        // Send headers
        header('Content-Type: '.$mime_type);
        // lem9 & loic1: IE need specific headers
        if (USR_BROWSER_AGENT == 'IE')
        {
            header('Content-Disposition: inline; filename="'.$filename.$ext.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: attachment; filename="'.$filename.$ext.'"');
            header('Expires: 0');
            header('Pragma: no-cache');
        }
    
        // get dump_buffer
        $fp = fopen($path.$filename, 'rb');
        $dump_buffer = fread($fp, filesize($path.$filename));
        fclose($fp);
    
        // displays the dump...
        // 1. as a gzipped file
        if ( isset($_REQUEST['method']) && $_REQUEST['method'] == 'zip') {
            if ( @function_exists('gzcompress') )
            {
                require_once('class.zipfile.php');
                $zipfile = new zipfile();
                $zipfile->addFile($dump_buffer, $filename);
                $zipfile->addFile($info, 'info.txt');
                echo $zipfile->file();
            }
        }
        // 2. as a gzipped file
        elseif ( isset($_REQUEST['method']) && ($_REQUEST['method'] == 'gzip' || $_REQUEST['method'] == 'tar') )
        {
            if ( @function_exists('gzencode') )
            {
                require_once('class.tar.php');
                $tar = new tar();
                $tar->addFile($path.$filename);
                //$tar->addFile();
                echo $tar->toTarOutput($filename.$ext, true);
            }
        }
        // 3. on screen or as a text file
        else {
            echo $dump_buffer;
        }
        
        // 4. count downloads
        $fc = @fopen('php_downloader.counter', 'a');
        $method = ( !isset($_REQUEST['method']) ) ? 'plain' : $_REQUEST['method'];
        @fwrite($fc, date('r').';'.$_REQUEST['file'].';'.$method.';'.$_SERVER["REMOTE_ADDR"]."\n");
        @fclose($fc);
    }
    else
    {
        echo "No such file or directory";
    }

}
else
    header('location: licitadownload.php');
exit;
?>