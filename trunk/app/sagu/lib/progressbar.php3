<?php

class ProgressBar
{

  var $title;
  var $url;
  var $min;
  var $max;
  var $pos;

  function ProgressBar($title,$url,$min=0,$max=100)
  {
    $this->title = $title;
    $this->url = $url;
    $this->min = $min;
    $this->max = $max;
    $this->pos = 0;
  }

  function Start()
  {
    echo "<center><br><table align=\"center\" bgcolor=\"#000099\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"350\">\n" .
         "<tr height=\"30\">\n<td class=\"pageText\" align=\"center\" colspan=\"2\" bgcolor=\"#000099\">\n" . 
	 "<font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#ccccff\"><b>{$this->title}</b></font></td>\n</tr>\n" .
         "<tr>\n<td align=\"center\"><table bgcolor=\"#ffffff\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n" .
	 "<tr><td align=\"center\"><br><p><b>Aguarde o final do processamento ...</b></p>\n" .
         "<img name=\"progbar\" src=\"../images/progbar0.png\">\n" .
         "<br>&nbsp;</td></tr></table></td></tr></table>\n";

    flush();
  }

  function SetProgress($progress)
  {
    $step = (int)( $this->max - $this->min ) / 10;

    $pos = (int)( $progress / $step );

    if ( $pos != $this->pos )
    {
      $this->pos = $pos;
      echo "<script language=\"JavaScript\">\n" .
           "  document.progbar.src=\"../images/progbar$pos.png\";\n" .
           "</script>\n";
      flush();
    }
  }

  function Finish($title='Processamento Concluído!',$info='O processamento foi concluído com êxito',$location='')
  {

    echo "<br><br>\n" .
    SuccessPage("$title",
            "$location",
            "$info");
    echo "<script language=\"JavaScript\">\n" .
         "  document.progbar.src=\"../images/progbar10.png\";\n" .
         "</script>\n";
    flush();
  }

}

?>