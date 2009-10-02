<?php 

class number {
	
	/**
	 * Converte do formato numeric para decimal brasileiro
	 * @nNumeric numeric
	 * @return decimal brasileiro
	 */
	function numeric2decimal_br($nNumeric)
	{
		setlocale(LC_CTYPE,"pt_BR");
		$Real = explode('.',$nNumeric);
		$Inteiro = $Real[0];

		$CasaDecimal = substr(@$Real[1], 0, 2);

		if ( strlen($CasaDecimal) < 2 )
			$CasaDecimal = str_pad($CasaDecimal, 1, "0", STR_PAD_RIGHT);
    
		$InteiroComMilhar = number_format($Inteiro, 0, '.', '.');
		$Real = $InteiroComMilhar.','.$CasaDecimal;

		return $Real;
	}
	
	/**
     * Converte do formato decimal brasileiro para numeric
     * @rValor decimal brasileiro
     * @return numeric
     */
	function decimal_br2numeric($rValor)
	{
		setlocale(LC_CTYPE,"pt_BR");

		$ValNumeric =  str_replace(",", "+", $rValor);
		$ValNumeric =  str_replace(".", "", $ValNumeric);
		$ValNumeric =  str_replace("+", ".", $ValNumeric);

		$Numeric = explode('.',$ValNumeric);

		$Inteiro = $Numeric[0];
		$Decimal = substr($Numeric[1], 0, 2);

		if(strlen($Decimal) < 2)
			$Decimal = str_pad($Decimal, 1, "0", STR_PAD_RIGHT);

		$ValNumeric = $Inteiro.'.'.$Decimal;

		return $ValNumeric;
	}

}
?>
