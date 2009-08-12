<?php

class gtiMail
{
    private $parts;
    
    /*
     * Método construtor
     */
    function __construct()
    {
        $this->parts = array();
        $this->boundary = md5(time());
    }
    
    /*
     * Adiciona HTML
     */
    function AdicionarHtml($body)
    {
        $body = stripslashes($body);
        $msg  = "--{$this->mime_boundary}n";
        $msg .= "Content-Type: text/html; charset=ISO-8859-1nn";
        $msg .= $body;
        
        $this->parts[] = $msg;
    }
    
    /*
     * Adiciona Texto
     */
    function AdicionarTexto($body)
    {
        $body = stripslashes($body);
        $msg  = "--{$this->mime_boundary}n";
        $msg .= "Content-Type: text/plain; charset=ISO-8859-1nn";
        $msg .= $body;
        
        $this->parts[] = $msg;
    }
    
    /*
     * Adiciona Imagem
     */
    function AdicionarPng($arquivo, $download)
    {
        $fd=fopen($arquivo, 'rb');
        $contents=fread($fd, filesize($arquivo));
        $contents=chunk_split(base64_encode($contents),68,"n");
        fclose($fd);
        
        $msg  = "--{$this->mime_boundary}n";
        $msg .= "Content-Type: image/png; name={$download}n";
        $msg .= "Content-Transfer-Encoding: base64n";
        $msg .= "Content-Disposition: attachment; filename={$download}nn";
        $msg .= "{$contents}";
        
        $this->parts[] = $msg;
    }
    
    /*
     * Envia Email
     */
    function Enviar($de, $para, $assunto)
    {
        $headers  = "From: {$de}n";
        $headers .= 'Content-Type: multipart/mixed; boundary="'.$this->mime_boundary.'n';
        $headers .= 'X-Mailer: PHP-' . phpversion() . 'n';
        $headers .= 'Mime-Version: 1.0nn';
        
        $msg = implode("n", $this->parts);
        
        mail($para, $assunto, $msg, $headers);
    }
}

?>
