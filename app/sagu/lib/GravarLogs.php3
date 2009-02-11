<script language="PHP">
function GravarLogs($tabela, $campos, $chave, $valor_chave, $conn)
{
    global $REMOTE_ADDR;

    foreach($campos as $campo_tabela => $valor_campo)
    {
        $sql = " SELECT * FROM $tabela " .
               " WHERE $chave = '$valor_chave' and " .
               "       $campo_tabela = '$valor_campo'";
        
        @$query = $conn->CreateQuery($sql);

        $n = @$query->GetRowCount();

        if ($n==0)
        {
            $sql = " INSERT INTO cadastros_log (" .
                   "        tabela, " .
                   "        campo, " .
                   "        valor_anterior, " .
                   "        chave, " .
                   "        data, " .
                   "        hora, " .
                   "        ip) " .
                   " SELECT '$tabela', " .
                   "        '$campo_tabela', " .
                   "         $campo_tabela, " . 
                   "        '$valor_chave', " .
                   "         date(now()), " .
                   "         now(), " .
                   "         '$REMOTE_ADDR' " .
                   " FROM $tabela " .
                   " WHERE $chave = '$valor_chave' ";
                   
            $ok = $conn->Execute($sql);

            if (!$ok)
                SaguAssert(0, "Problema ao gravar log do campo <b>$campo_tabela</b> da tabela <b>$tabela</b>!!!");

            $update = " UPDATE $tabela SET " .
                      "    $campo_tabela = '$valor_campo' " .
                      " WHERE $chave = '$valor_chave'";
            
            $ok = $conn->Execute($update);
            
            if (!$ok)
                SaguAssert(0, "Problema ao alterar o campo <b>$campo_tabela</b> da tabela <b>$tabela</b>!!!");
        }
    }
}
</script>
