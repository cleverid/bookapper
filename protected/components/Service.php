<?php
class Service{
	public static function preView($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}
?>
