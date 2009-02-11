<!-- Função que gera a string por extenso de um determinado -->
<!-- valor                                                  -->
<!-- Autor Pablo Dall'Oglio, Ultima modificação: 15/12/2000 -->

<?
function TRIO_EXTENSO($cVALOR)
{
  $aUNID   = array(""," UM "," DOIS "," TRES "," QUATRO "," CINCO "," SEIS "," SETE "," OITO "," NOVE ");
  $aDEZE   = array("","   "," VINTE E"," TRINTA E"," QUARENTA E"," CINQUENTA E"," SESSENTA E"," SETENTA E"," OITENTA E"," NOVENTA E ");
  $aCENT   = array("","CENTO E","DUZENTOS E","TREZENTOS E","QUATROCENTOS E","QUINHENTOS E","SEISCENTOS E","SETECENTOS E","OITOCENTOS E","NOVECENTOS E");
  $aEXC    = array(" DEZ "," ONZE "," DOZE "," TREZE "," QUATORZE "," QUINZE "," DESESSEIS "," DESESSETE "," DEZOITO "," DESENOVE ");
  $nPOS1   = substr($cVALOR,0,1);
  $nPOS2   = substr($cVALOR,1,1);
  $nPOS3   = substr($cVALOR,2,1);
  $cCENTE  = $aCENT[($nPOS1)];
  $cDEZE   = $aDEZE[($nPOS2)];
  $cUNID   = $aUNID[($nPOS3)];

  if (substr($cVALOR,0,3) == "100")
  { $cCENTE = "CEM "; }

  if (substr($cVALOR,1,1) == "1")
  {  $cDEZE = $aEXC[$nPOS3];
     $cUNID = "";
  }

  $cRESULT = $cCENTE . $cDEZE . $cUNID;
  $cRESULT = substr($cRESULT,0,strlen($cRESULT)-1);
  return $cRESULT;
}

function EXTENSO($cVALOR, $lMOEDA)
{
  //pict 999.999.999,99

  $zeros = "000.000.000,00";
  $cVALOR = number_format($cVALOR,2);
  $cVALOR = substr($zeros,0,strlen($zeros)-strlen($cVALOR)) . $cVALOR;


  if ($lMOEDA)
  {
    $cMOEDA_SINGULAR = " REAL";
    $cMOEDA_PLURAL   = " REAIS";
  }
  else
  {
    $cMOEDA_SINGULAR = "";
    $cMOEDA_PLURAL   = "";
  }

  //cVALOR  = transform( nVALOR, "@ZE 999,999,999.99");
  //$cRETURN = substr($cVALOR,0,3) . substr($cVALOR,4,3) . substr($cVALOR,8,3);

  $cMILHAO = TRIO_EXTENSO(substr($cVALOR,0,3)) . ( (substr($cVALOR,0,3)>1) ? ' MILHOES' : '' );
  $cMILHAR = TRIO_EXTENSO(substr($cVALOR,4,3)) . ( (substr($cVALOR,4,3)>0) ? ' MIL' : '' );
  $cUNIDAD = TRIO_EXTENSO(substr($cVALOR,8,3)) . ( ($nVALOR==1) ? $cMOEDA_SINGULAR : $cMOEDA_PLURAL);
  $cCENTAV = TRIO_EXTENSO("0" . substr($cVALOR,12,2)) . ((substr($cVALOR,12,2)>0) ? " CENTAVOS" : "");

  $cRETURN = $cMILHAO . ((strlen(trim($cMILHAO))<>0 && strlen(trim($cMILHAR))<>0) ? ", " : "") .
             $cMILHAR . ((strlen(trim($cMILHAR))<>0 && strlen(trim($cUNIDAD))<>0) ? ", " : "") .
             $cUNIDAD . ((strlen(trim($cUNIDAD))<>0 && strlen(trim($cCENTAV))<>0) ? ", " : "") .
             $cCENTAV;
  return $cRETURN;
}

?>
