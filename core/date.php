<?php 

class date {
	
	function convert_date($data){
		return implode(!strstr($data, '/') ? "/" : "-", array_reverse(explode(!strstr($data, '/') ? "-" : "/", $data)));
	}
	
	function string_mes(){
		return;
	}

}
?>