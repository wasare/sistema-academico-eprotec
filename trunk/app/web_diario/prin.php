<style type="text/css"><!--.calendarHeader {
	font-weight: bold;
	color: #CC0000;
	background-color: #FFFFCC;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-style: normal;
	border: none;
}.calendarToday {
	background-color: #FFFFFF;
	border: 1px outset;
}.calendar {
	background-color: #FFFFCC;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	border: 1px outset;
}--></style>
<?php
include("calendar.php");
// Construct a calendar to show the current month
// If no month/year set, use current month/year
 
$d = getdate(time());

if ($month == "")
{
    $month = $d["mon"];
}

if ($year == "")
{
    $year = $d["year"];
}
?>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Calend&aacute;rio 
  corrente. Use <font color="#0000FF">&gt;&gt;</font> para pr&oacute;ximo m&ecirc;s 
  ou <font color="#0000FF">&lt;&lt;</font> para o m&ecirc;s anterior </strong></font></p>
<?php
$cal = new Calendar;
echo $cal->getMonthView($month, $year);
?>
<!--<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="http://www.cneccapivari.br/calendario.htm" target="_blank">Visualizar 
Calend&aacute;rio Acad&ecirc;mico</a> </font></p>-->
<p align="center">
</p>
<?php
// <p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="img/logoNewCNEC.jpg" width="200" height="86"></font></p>
?>
