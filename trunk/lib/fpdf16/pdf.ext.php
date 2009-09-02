<?php

class PDF extends FPDF
{
//Load data
function LoadData($file)
{
    //Read file lines
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}

function LoadArray($A)
{
    //Read file lines
    //$lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}

//Simple table
function BasicTable($header,$data)
{
    //Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    //Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}


function Footer()
{
    //Vai para 1.0 cm da parte inferior
    $this->SetY(-15);
    //Seleciona a fonte Arial it�lico 8
    $this->SetFont('Arial','I',8);
    //Imprime o n�mero da p�gina corrente e o total de p�ginas
    $this->Cell(0,0,'P�gina '.$this->PageNo().'/{nb}',0,0,'C');
}


}

?>