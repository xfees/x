<?php
/**************************************************************/
// Author: Marghoob Suleman
// File: class.Console.php
// Desc: Javascript console and misc
// Version: 1.0
/**************************************************************/	
	class Console  {
		
		function __construct() {
				//nothing		
		}
		
		private function filterMe($val) {
			//echo $val;
			if(is_array($val)) {
				$value = json_encode($val);
			} else {
				$value = preg_replace( '/[\\r\\n\\t]/', '', $val);
			}
  			return $value;
		}
		
		public static function debug($val='') {
			/*
			$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? TRUE : FALSE;
			if($isAjax==FALSE) {
				
			}
			*/
			echo "<script type='text/javascript'>";
			echo "if(typeof(console)=='undefined') {";
			echo "alert('".addslashes($val)."')";
			echo "} else {";
			echo "console.debug(";
			echo "'".addslashes(self::filterMe($val))."'";
			echo ")}";
			echo "</script>";
			
		}
		
		public static function log($val) {
			
			echo "<script type='text/javascript'>";
			echo "if(typeof(console)=='undefined') {";
			echo "alert('".addslashes(self::filterMe($val))."')";
			echo "} else {";
			echo "console.log(";
			echo "'".addslashes(self::filterMe($val))."'";
			echo ")}";
			echo "</script>";
		}
		public static function call($val='', $agruments) {
			echo "<script type='text/javascript'>";
			echo "if(typeof($val)=='function') {";
			echo "eval($val)('".addslashes(self::filterMe($agruments))."')";
			echo "}";
			echo "</script>";
		} 
			
		function __destruct() {
			//nothing
		}
	}