<? 
function PS_begin_page($file, $page, $pagecount)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_begin_page - Missing parameter: 1 (file name) <br>");
  }
  if (empty($page))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_begin_page - Missing parameter: 2 (page number) <br>");
  }
  if (empty($pagecount))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_begin_page - Missing parameter: 3 (pagecount) <br>");
  }
  if (intval($pagecount) == 0)
  {
    echo("<br><b>PSLib Warning:</b> Function PS_begin_page - Incorrect value: parameter 3 (pagecount) <br>");
  }

  fwrite($file, "%%Page: " . $page . " " . $pagecount . "\n");
}


function PS_close($file)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_close - Missing parameter: 1 (file name) <br>");
  }

  fclose($file);
}


function PS_end_page($file)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_end_page: Missing parameter: 1 (file name) <br>");
  }

  fwrite($file, "showpage \n");
}


function PS_line($file, $xcoord_from, $ycoord_from, $xcoord_to, $ycoord_to, $linewidth)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_line - Missing parameter: 1 (file name) <br>");
  }
  if (empty($xcoord_from))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_line - Missing parameter: 2 (xcoord_from) <br>");
  }
  if (empty($ycoord_from))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_line - Missing parameter: 3 (ycoord_from) <br>");
  }
  if (empty($xcoord_to))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_line - Missing parameter: 4 (xcoord_to) <br>");
  }
  if (empty($ycoord_to))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_line - Missing parameter: 5 (ycoord_to) <br>");
  }
  if (empty($linewidth))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_line - Missing parameter: 6 (linewidth, must be >= 1) <br>");
  }

  fwrite($file, $linewidth . " setlinewidth  \n");
  fwrite($file, $xcoord_from . " " . $ycoord_from  . " moveto \n");
  fwrite($file, $xcoord_to . " " . $ycoord_to  . " lineto \n");
  fwrite($file, "stroke \n");
}


function PS_moveto($file, $xcoord, $ycoord)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto - Missing parameter: 1 (file name) <br>");
  }
  if (empty($xcoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto - Missing parameter: 2 (xcoord) <br>");
  }
  if (empty($ycoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto - Missing parameter: 3 (ycoord) <br>");
  }

  fwrite($file, $xcoord . " " . $ycoord . " moveto \n");
}


function PS_moveto_font($file, $xcoord, $ycoord, $font_name, $font_size)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto_font - Missing parameter: 1 (file name) <br>");
  }
  if (empty($xcoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto_font - Missing parameter: 2 (xcoord) <br>");
  }
  if (empty($ycoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto_font - Missing parameter: 3 (ycoord) <br>");
  }
  if (empty($font_name))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto_font - Missing parameter: 4 (font_name) <br>");
  }
  if (empty($font_size))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto_font - Missing parameter: 5 (font_size) <br>");
  }
  if (intval($font_size) == 0)
  {
    echo("<br><b>PSLib Warning:</b> Function PS_moveto_font: Incorrect value: parameter 5 (font_size) <br>");
  }

  fwrite($file, $xcoord . " " . $ycoord . " moveto \n");
  fwrite($file, "/" . $font_name . " findfont " . $font_size . " scalefont setfont \n");
}


function PS_open($file, $author, $title, $pagecount)
{
  if ($file=='help')
  {
    echo("<br><b>PSLib HELP:</b> Function PS_open(param1, param2, param3, param4) <br>");
    echo("param1 = ps file name to create <br>");
    echo("param2 = creator/author name <br>");
    echo("param3 = file title <br>");
    echo("param4 = total of pages <br><br>");
    return("");
  }
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_open - Missing parameter: 1 (file name) <br>");
  }
  if (empty($author))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_open - Missing parameter: 2 (author) <br>");
  }
  if (empty($title))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_open - Missing parameter: 3 (title) <br>");
  }
  if (empty($pagecount))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_open - Missing parameter: 4 (pagecount) <br>");
  }

  fwrite($file, "%!PS-Adobe-3.0 \n");
  fwrite($file, "%%%Creator:" . $author . "\n");
  fwrite($file, "%%Title: " . $title . "\n");
  fwrite($file, "%%Pages:" . $pagecount . "\n");
}


function PS_open_ps($file, $ps_file)
{
  if ($file=='help')
  {
    echo("<br><b>PSLib HELP:</b> Function PS_open_ps(param1, param2) <br>");
    echo("param1 = ps file name to write to <br>");
    echo("param2 = source ps file (remember to exclude any file information like title, author,... in the top of the file)<br><br>");
    return("");
  }
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_open_ps - Missing parameter: 1 (file name) <br>");
  }
  if (empty($ps_file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_open_ps - Missing parameter: 2 (source ps file name) <br>");
  }

  $temp_ = fopen($ps_file,"r");
  while(!feof($temp_))
  {
    $line_ = fgets($temp_, 500);
    $cont_ = $cont_ . $line_;
  }
  fclose($temp_);
  fwrite($file, $cont_);
}


function PS_rect($file, $xcoord_from, $ycoord_from, $xcoord_to, $ycoord_to, $linewidth)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Missing parameter: 1 (file name) <br>");
  }
  if (empty($xcoord_from))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Missing parameter: 2 (xcoord_from) <br>");
  }
  if (empty($ycoord_from))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Missing parameter: 3 (ycoord_from) <br>");
  }
  if (empty($xcoord_to))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Missing parameter: 4 (xcoord_to) <br>");
  }
  if (empty($ycoord_to))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Missing parameter: 5 (ycoord_to) <br>");
  }
  if (empty($linewidth))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Missing parameter: 6 (linewidth, must be >= 1) <br>");
  }
  if (intval($linewidth) == 0)
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect - Incorrect value: parameter 6 (linewidth, must be >= 1) <br>");
  }

  fwrite($file, $linewidth . " setlinewidth  \n");
  fwrite($file, "newpath \n");
  fwrite($file, $xcoord_from . " " . $ycoord_from  . " moveto \n");
  fwrite($file, $xcoord_to . " " . $ycoord_from  . " lineto \n");
  fwrite($file, $xcoord_to . " " . $ycoord_to  . " lineto \n");
  fwrite($file, $xcoord_from . " " . $ycoord_to  . " lineto \n");
  fwrite($file, "closepath \n");
  fwrite($file, "stroke \n");
}


function PS_rect_fill($file, $xcoord_from, $ycoord_from, $xcoord_to, $ycoord_to, $linewidth, $darkness)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter: 1 (file name) <br>");
  }
  if (empty($xcoord_from))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter: 2 (xcoord_from) <br>");
  }
  if (empty($ycoord_from))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter: 3 (ycoord_from) <br>");
  }
  if (empty($xcoord_to))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter: 4 (xcoord_to) <br>");
  }
  if (empty($ycoord_to))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter: 5 (ycoord_to) <br>");
  }
  if (empty($linewidth))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter: 6 (linewidth, must be >= 1) <br>");
  }
  if (empty($darkness))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Missing parameter:  (darkness) <br>");
  }
  if (intval($linewidth) == 0)
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rect_fill - Incorrect value: parameter 6 (linewidth, must be >= 1) <br>");
  }

  fwrite($file, "newpath \n");
  fwrite($file, $linewidth . " setlinewidth  \n");
  fwrite($file, $xcoord_from . " " . $ycoord_from  . " moveto \n");
  fwrite($file, $xcoord_to . " " . $ycoord_from  . " lineto \n");
  fwrite($file, $xcoord_to . " " . $ycoord_to  . " lineto \n");
  fwrite($file, $xcoord_from . " " . $ycoord_to  . " lineto \n");
  fwrite($file, "closepath \n");
  fwrite($file, "gsave \n");
  fwrite($file, $darkness . " setgray  \n");
  fwrite($file, "fill \n");
  fwrite($file, "grestore \n");
  fwrite($file, "stroke \n");
}


function PS_rotate($file, $degrees)
{
  if ($file=='help')
  {
    echo("<br><b>PSLib HELP:</b> Function PS_rotate(param1, param2) <br>");
    echo("param1 = ps file name to write to <br>");
    echo("param2 = degrees to rotate <br>");
    echo("=> if param2 = 0  or  param2 = 360 -> back to normal <br><br>");
    return("");
  }

 if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rotate - Missing parameter: 1 (file name) <br>");
  } 
  if (empty($degrees))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_rotate - Missing parameter: 2 (degrees) <br>");
  }
  if (($degrees == '0') or ($degrees == '360'))
  {
    fwrite($file, "grestore \n");
  }
  else
  {
    fwrite($file, "gsave \n");
    fwrite($file, $degrees . " rotate \n");
  }
}


function PS_set_font($file, $font_name, $font_size)
{
  if ($file=='help')
  {
    echo("<br><b>PSLib HELP:</b> Function PS_set_font(param1, param2, param3) <br>");
    echo("param1 = ps file name to write to <br>");
    echo("param2 = font name <br>");
    echo("param3 = font size <br><br>");
    return("");
  }
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_set_font - Missing parameter: 1 (file name) <br>");
  }
  if (empty($font_name))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_set_font - Missing parameter: 2 (font name) <br>");
  }
  if (empty($font_size))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_set_font - Missing parameter: 3 (font size) <br>");
  }
  if (intval($font_size) == 0)
  {
    echo("<br><b>PSLib Warning:</b> Function PS_set_font - Incorrect value: parameter 3 (font_size) <br>");
  }

  fwrite($file, "/" . $font_name . " findfont " . $font_size . " scalefont setfont \n");
}


function PS_show($file, $text)
{
  if ($file=='help')
  {
    echo("<br><b>PSLib HELP:</b> Function PS_show(param1, param2) <br>");
    echo("param1 = ps file name to write to <br>");
    echo("param2 = text to show <br><br>");
    return("");
  }
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show - Missing parameter: 1 (file name) <br>");
  }
  if (empty($text))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show - Missing parameter: 2 (text) <br>");
  }
 
  fwrite($file, "(" . $text  . ") show \n");
}


function PS_show_eval($file, $text)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_eval - Missing parameter: 1 (file name) <br>");
  }
  if (empty($text))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_eval - Missing parameter: 2 (text) <br>");
  }

  eval ("\$text = \"$text\";");
  fwrite($file, "(" . $text  . ") show \n");
}


function PS_show_xy($file, $text, $xcoord, $ycoord)
{
  if ($file=='help')
  {
    echo("<br><b>PSLib HELP:</b> Function PS_show_xy(param1, param2, param3, param4) <br>");
    echo("param1 = ps file name to write to <br>");
    echo("param2 = text to show <br>");
    echo("param3 = X coordenate <br>");
    echo("param4 = Y coordenate <br><br>");
    return("");
  }
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy - Missing parameter: 1 (file name) <br>");
  }
  if (!isset($text))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy - Missing parameter: 2 (text) <br>");
  }
  if (empty($xcoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy - Missing parameter: 3 (xcoord) <br>");
  }
  if (empty($ycoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy - Missing parameter: 4 (ycoord) <br>");
  }

  fwrite($file, $xcoord . " " . $ycoord . " moveto \n");
  fwrite($file, "(" . $text  . ") show \n");
}


function PS_show_xy_font($file, $text, $xcoord, $ycoord, $font_name, $font_size)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Missing parameter: 1 (file name) <br>");
  }
  if (empty($text))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Missing parameter: 2 (text) <br>");
  }
  if (empty($xcoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Missing parameter: 3 (xcoord) <br>");
  }
  if (empty($ycoord))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Missing parameter: 4 (ycoord) <br>");
  }
  if (empty($font_name))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Missing parameter: 5 (font_name) <br>");
  }
  if (empty($font_size))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Missing parameter: 6 (font_size) <br>");
  }
  if (intval($font_size) == 0)
  {
    echo("<br><b>PSLib Warning:</b> Function PS_show_xy_font - Incorrect value: parameter 6 (font_size) <br>");
  }

  fwrite($file, $xcoord . " " . $ycoord . " moveto \n");
  fwrite($file, "/" . $font_name . " findfont " . $font_size . " scalefont setfont \n");
  fwrite($file, "(" . $text  . ") show \n");
}

function PS_set_acent($file)
{
  if (empty($file))
  {
    echo("<br><b>PSLib Warning:</b> Function PS_set_acent - Missing parameter: 1 (file name) <br>");
  }

  if (file_exists('acentos.ps'))
  {
    $file_acentos = fopen('acentos.ps',"r");
    while(!feof($file_acentos))
    {
      $line = fgets($file_acentos, 500);
      $acentos = $acentos . $line;
    }
    fclose($file_acentos);
    fwrite($file, $acentos . "\n");
  }
  else
  {
    echo("<br><b>PSLib ERROR:</b> Function PS_set_acent - Required file: <b>acentos.ps</b> not found <br>");
  }

}


?>
<HTML>
<BODY></BODY>
</HTML>