<?php 
//Define all functions here
	function __autoload($className){
		if(file_exists(CMSROOTPATH.'/classes/class.'.$className.'.php')){
			include_once(CMSROOTPATH.'/classes/class.'.$className.'.php');	
		}
	}
	function getModulesList($selected_id = NULL){
		$strSelect = '';
		$db =  Database::Instance() ;
		$sql = "SELECT id,name,displayname FROM cms_modules where STATUS=1 order by name";
		$db->query($sql);
		if($db->getRowCount() > 0){
			$result_module = $db->getResultSet();
			foreach($result_module as $k=>$v){
                                $selected = NULL;
                                if ( $selected_id !=NULL && $selected_id == $v['id'] )
                                {
                                  $selected = ' selected="selected" ';
                                }
				$strSelect .= '<option ' . $selected . ' value="'.$v['id'].'">'.ucfirst($v['displayname']).'</option>';
			}		
		}	
		return $strSelect;
	}// eof getModuleList	
	
	function getModulesListByname($selected_id = NULL){
		$strSelect = '';
		$db =  Database::Instance() ;
		$sql = "SELECT id,name,displayname FROM cms_modules where STATUS=1 order by name";
		$db->query($sql);
		if($db->getRowCount() > 0){
			$result_module = $db->getResultSet();
			foreach($result_module as $k=>$v){
                                $selected = NULL;
                                if ( $selected_id !=NULL && $selected_id == $v['id'] )
                                {
                                  $selected = ' selected="selected" ';
                                }
				$strSelect .= '<option ' . $selected . ' value="'.$v['id'].'">'.ucfirst($v['name']).'</option>';
			}		
		}	
		return $strSelect;
	}// eof getModuleList	

	function getContentType($click=true){
		$strSelect = ''; $i = 1;
		$db = Database::Instance() ;
		$sql = "SELECT * FROM contype";
		$db->query($sql);
		if($db->getRowCount() > 0){
			$result_module = $db->getResultSet();
			foreach($result_module as $k=>$v){
				$strSelect .= '<input class="contype" type="radio" name="contype" id="contype'.$i.'" value="'.$v['id'].'"';
				if($click) $strSelect .= 'onClick="resetForm(this.value)"';
				$strSelect .= '/>&nbsp;'.$v['name'].'&nbsp;&nbsp;&nbsp;';
				$i++;
			}		
		}	
		return $strSelect;
	}// eof getContentType	
	
	function populateContentType($contype = ''){
		$db = Database::Instance() ;
		$sql = "SELECT * FROM contype";
		$db->query($sql);
		if($db->getRowCount() > 0){
			$result_module = $db->getResultSet();
			foreach($result_module as $k=>$v){
				$strSelect .= '<option value="'.$v['id'].'"';
				if($contype != '' && $v['id'] == $contype) $strSelect .= ' selected="selected"';
				$strSelect .= '>'.$v['name'].'</option>';
			}		
		}	
		return $strSelect;
	}// eof getContentType		
	
	function chkrightsmsg ($actionmsg){
		switch($actionmsg){
			case 'a';
				$msg="This is a Read-Only Account. No action will be performed for this Account.";
				break;
			case 'm':
				$msg="This is a Read-Only Account. No action will be performed for this Account.";
				break;
			case 'd':
				$msg="This is a Read-Only Account. No action will be performed for this Account.";
				break;
		}
		return $msg;
	} //eof chkrightsmsg

	function ressuccessmsg($actionmsg)
	{
		switch($actionmsg)
		{
			case 'a';
				$msg="Record Added Successfully";
				break;
			case 'm':
				$msg="Record Modified Successfully";
				break;
			case 'd':
				$msg="Record Deleted Successfully";
				break;
			case 'r':
				$msg="Record Restored Successfully";
				break;
		}
		return $msg;
	} //eof ressuccessmsg

	function resfailedmsg($actionmsg){
		switch($actionmsg){
			case 'a';
				$msg="Record Not Inserted.";
				break;
			case 'm':
				$msg="Record Not Modified.";
				break;
			case 'd':
				$msg="Record Not Deleted.";
				break;
			case 'r':
				$msg="Record Not Restored Properly.";
				break;
		}
		return $msg;
	} // eof resfailedmsg
	
	/* Function to take sentences on word boundary's Starts */
	function str_stop($string, $max_length){
	   if (strlen($string) > $max_length){
		   $string = substr($string, 0, $max_length);
		   $pos = strrpos($string, " ");
		   if($pos === false) {
				   return substr($string, 0, $max_length)."...";
			   }
		   return substr($string, 0, $pos)."...";
	   }else{
		   return $string;
	   }
	}
	/* Function to take sentences on word boundary's Ends */

	/* Function to take sentences on character limits Starts */
	function str_substr($string, $max_length){
		$string=trim($string);
	   	if (strlen($string) > $max_length){
		   $newstring= substr($string, 0, $max_length);
		}else{
		   $newstring=$string;
		}
		if(strlen($string)>strlen($newstring)){
			$newstring.='...';
		}
		return $newstring;
	}
	/* Function to take sentences on character limits Ends */
	
	//Function to remove special characters from text STARTS
	function specialchars($text){
		$newtext = trim($text);
		$newtext = strip_tags($newtext);
		$newtext=htmlspecialchars(str_replace('\\', '', $newtext),ENT_QUOTES);
		$newtext=eregi_replace('&amp;','&',$newtext);
		//$newtext=nl2br($newtext);
		return $newtext;
	}
	//Function to remove special characters from text ENDS


	// REDIRECT FUNCTION 
	function client_redirect($url){
		echo "<script language=\"\">";
		echo "window.location.href = \"".$url."\"";
		echo "</script>";
	}

	function arr2json($arr) {
			$str = "{ ";
			$temp = array();
			while ( list( $key, $val ) = each($arr) ){	//echo "<p>".$key."=".$val;
				$val=decodespecialchars($val);
				$encval = encodejson($val);
				$encval=$val;
				$s = "\"$key\" : { \"value\" : \"$encval\"}";
				array_push($temp, $s);
			}
		
			$str .= implode(',',$temp);
			$str .= "}";
			//echo $str;		
			return $str;
	}
	
	function geteditdatetime($dbdate){
		$tmStamp = strtotime($dbdate);
		$dtSystemDate=date("d-m-Y H:i:s",$tmStamp);
		return $dtSystemDate;
	}
	
	function getdisplaydatetime($dbdate){
		$tmStamp = strtotime($dbdate);
		$dtSystemDate=date("M d, Y - h:i A",$tmStamp);
		$dtSystemDate=str_replace("-", "at", $dtSystemDate);
		return $dtSystemDate;
	}
	function getcalendardatetime($dbdate){
		$temppublishdate = explode(" ", $dbdate) ;
		$temppublishdate_datepart = explode("-", $temppublishdate[0]);	
		$publishdate = $temppublishdate_datepart[2] . '-' . $temppublishdate_datepart[1] . '-'. $temppublishdate_datepart[0] . ' ' . $temppublishdate[1] ;
		return $publishdate;
	}
	function getdisplaydate($dbdate){
		$tmStamp = strtotime($dbdate);
		$dtSystemDate=date("M d, Y ",$tmStamp);
		return $dtSystemDate;
	}

	function getdbdate($dbdate){
		$tmStamp = strtotime($dbdate);
		$dtSystemDate=date("Y-m-d H:i:s",$tmStamp);
		return $dtSystemDate;
	}

		//Function to convert date into YYYY-mm-dd hh:mm:ss format
	function dbdate($date){
		$date=explode("-",$date);
		$year=explode(" ",$date[2]);
		$date1=$year[0]."-";
		if($date[1]<10 && (substr($date[1],0,1)!=0))
			$date1.="0".$date[1]."-";
		else
			$date1.=$date[1]."-";
		if($date[0]<10 && (substr($date[0],0,1)!=0))
			$date1.="0".$date[0]." ".$year[1];
		else
		$date1.=$date[0]." ".$year[1];
		return $date1;
	}
	//date without time
	function dbdatenotime($date){
		$date=explode("-",$date);
		$year=explode(" ",$date[2]);
		$date1=$year[0]."-";
		if($date[1]<10 && (substr($date[1],0,1)!=0))
			$date1.="0".$date[1]."-";
		else
			$date1.=$date[1]."-";
		if($date[0]<10 && (substr($date[0],0,1)!=0))
			$date1.="0".$date[0]." ".$year[1];
		else
		$date1.=$date[0];
		return $date1;
	}
//Function to get IP address of user
	function getIP(){
		if(isset($_SERVER["HTTP_TRUE_CLIENT_IP"]))
			$IP = $_SERVER["HTTP_TRUE_CLIENT_IP"];
		elseif(isset($_SERVER["HTTP_NS_REMOTE_ADDR"]))
			$IP = $_SERVER["HTTP_NS_REMOTE_ADDR"];
		else
			$IP = $_SERVER["REMOTE_ADDR"];
		return($IP);	
	}	
	
//Function to convert all special chars Starts
function replacespecialchars($str){
	$str=trim($str);
	$str=ereg_replace("&quot;","",$str);
	$str=ereg_replace("&#039;","",$str);
	$str=ereg_replace("&","&amp;",$str);
	$str=ereg_replace("\;","&#59;",$str);
	$str=ereg_replace("-","",$str);
	$str=ereg_replace("#","",$str);
	$str=ereg_replace("\?","",$str);
	$str=ereg_replace('"',"",$str);
	$str=ereg_replace("'","",$str);
	$str=ereg_replace(",","",$str);
	$str=ereg_replace("!","",$str);
	$str=ereg_replace("'","",$str);
	$str=ereg_replace("&","",$str);
	$str=ereg_replace("\/","",$str);
	$str=ereg_replace("\.","",$str);
	$str=ereg_replace(":","",$str);
	$str=ereg_replace(",","",$str);
	$str=ereg_replace(";","",$str);
	$str=ereg_replace("\(","",$str);
	$str=ereg_replace("\)","",$str);
	$str=ereg_replace("!","",$str);
	$str=ereg_replace("\>","",$str);
	$str=ereg_replace("\%","",$str);
	$str=ereg_replace("  "," ",$str);
	$str=ereg_replace(" "," ",$str);
	$str=ereg_replace("�","&#8218;",$str);
	$str=ereg_replace("�","&#402;",$str);
	$str=ereg_replace("�","&#8222;",$str);
	$str=ereg_replace("�","&#8230;",$str);
	$str=ereg_replace("�","&#8224;",$str);
	$str=ereg_replace("�","&#8225;",$str);
	$str=ereg_replace("�","&#710;",$str);
	$str=ereg_replace("�","&#8240;",$str);
	$str=ereg_replace("�","&#352;",$str);
	$str=ereg_replace("�","&#8249;",$str);
	$str=ereg_replace("�","&#338;",$str);
	$str=ereg_replace("�","&#8216;",$str);
	$str=ereg_replace("�","&#8217;",$str);
	$str=ereg_replace("�","&#8220;",$str);
	$str=ereg_replace("�","&#8221;",$str);
	$str=ereg_replace("�","&#8226;",$str);
	$str=ereg_replace("�","&#8211;",$str);
	$str=ereg_replace("�","&#8212;",$str);
	$str=ereg_replace("�","&#732;",$str);
	$str=ereg_replace("�","&#8482;",$str);
	$str=ereg_replace("�","&#353;",$str);
	$str=ereg_replace("�","&#8250;",$str);
	$str=ereg_replace("�","&#339;",$str);
	$str=ereg_replace("�","&#376;",$str);
	$str=strtolower($str);
	return $str;
}
//Function to convert all special chars Ends
//Function to convert HTML entities
function decodespecialchars($str){
	$str = ereg_replace("\n","\\n",$str);
	$str = ereg_replace("\r","\\r",$str);
	$str=ereg_replace("&#039;","'",$str);
	$str=ereg_replace("&amp;","&",$str);
	$str=ereg_replace("&#59;","\;",$str);
	$str=ereg_replace("&#35;","#",$str);
	$str=ereg_replace("&#34;",'"',$str);
	$str=ereg_replace("&#39;","'",$str);
	$str=ereg_replace("&#58;",":",$str);
	$str=ereg_replace("&#47;","\/",$str);
	$str=ereg_replace("&#33;","!",$str);
	$str=ereg_replace("&#63;","\?",$str);
	//special character
	$str=ereg_replace("&#8218;","�",$str);
	$str=ereg_replace("&#402;","�",$str);
	$str=ereg_replace("&#8222;","�",$str);
	$str=ereg_replace("&#8230;","�",$str);
	$str=ereg_replace("&#8224;","�",$str);
	$str=ereg_replace("&#8225;","�",$str);
	$str=ereg_replace("&#710;","�",$str);
	$str=ereg_replace("&#8240;","�",$str);
	$str=ereg_replace("&#352;","�",$str);
	$str=ereg_replace("&#8249;","�",$str);
	$str=ereg_replace("&#338;","�",$str);
	$str=ereg_replace("&#8216;","�",$str);
	$str=ereg_replace("&#8217;","�",$str);
	$str=ereg_replace("&#8220;","�",$str);
	$str=ereg_replace("&#8221;","�",$str);
	$str=ereg_replace("&#8226;","�",$str);
	$str=ereg_replace("&#8211;","�",$str);
	$str=ereg_replace("&#8212;","�",$str);
	$str=ereg_replace("&#732;","�",$str);
	$str=ereg_replace("&#8482;","�",$str);
	$str=ereg_replace("&#353;","�",$str);
	$str=ereg_replace("&#8250;","�",$str);
	$str=ereg_replace("&#339;","�",$str);
	$str=ereg_replace("&#376;","�",$str);
	$str=htmlspecialchars_decode($str);
	return $str;
}

//This function is used to get the headline of the content for Tracklog function Starts
	function getHeadline($id,$columns,$tablename,$idcolumn){
		$headline="";
		if($idcolumn!=""){
			$db =  Database::Instance();
			$dataArr = $db->getDataFromTable(array($idcolumn=>$id),$tablename,$columns);
			$totalData = count($dataArr);
			if($totalData){
				$headline=$dataArr[0][$columns];
			}
		}
		return($headline);
	}
//This function is used to get the headline of the content for Tracklog function Ends 

function replacespecialcharsurl($str){
		$str=trim($str);
		$str=ereg_replace("&#039;","",$str);
		$str=ereg_replace("\/","",$str);
		$str=ereg_replace("-","",$str);
		$str=ereg_replace('"',"",$str);
		$str=ereg_replace("'","",$str);
		$str=ereg_replace("!","",$str);
		$str=ereg_replace("#","",$str);
		$str=ereg_replace("\?","",$str);
		$str=ereg_replace(",","",$str);
		$str=ereg_replace("'","",$str);
		$str=ereg_replace("&","",$str);
		$str=ereg_replace("\.","",$str);
		$str=ereg_replace(":","",$str);
		$str=ereg_replace("\(","",$str);
		$str=ereg_replace("\)","",$str);
		$str=ereg_replace("!","",$str);
		$str=ereg_replace("\%","",$str);
		$str=ereg_replace("\>","",$str);
		$str=ereg_replace("  "," ",$str);
		$str=ereg_replace(" ","-",$str);

		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("�","",$str);
		$str=ereg_replace("â€™","",$str);
		$str=ereg_replace("’","",$str);
		$str=strtolower($str);
		return $str;
}

#######function used to display alternate classes##########
function alternate_row($tdrow=0,$tdClass1,$tdClass2)
{
	if(($tdrow%2)==0)
	{
		$tdClass=$tdClass1;
	}
	else
	{
		$tdClass=$tdClass2;
	}
	return $tdClass;
}

// this function refresh the front-end content cache
function process_content_cache($content_ids, $action = 'r')
{
  $cache_api_url = FRONTEND_MEMCACHE_API . "?ids=$content_ids&a=$action";  
  return file_get_contents($cache_api_url);
}

 function getPreviewLink($conType,$id, $is_aggregate = false){
    $return=FRONTEND_SITE_URL.'/showdetail.php?id='.$id.'&flag=p';       
	return($return);
 }
 
 function strhex($string){
   $hexstr = unpack('H*', $string);
   return array_shift($hexstr);
 }
 
 function hexstr($hexstr){
   $hexstr = str_replace(' ', '', $hexstr);
   $retstr = pack('H*', $hexstr);
   return $retstr;
 }
 
 function encryptdata($str){
   return base64_encode(strrev(trim(strhex($str))));
 }
 
 function decryptdata($str){
   return hexstr(strrev(base64_decode($str)));
 }

 function getthumbnail($imagename,$size){ 
 	if($imagename != '' && $size != '' ){
		$imgval=explode('.',$imagename);
		$resizethumbval=$imgval[0].'_'.$size.'.'.$imgval[1];
		return $resizethumbval;
	}else{
		return $imagename;
	}
 }

 function callCurlURL($url, $params = array()){

	 try{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT,1);

		if(isset($params['request_type']) && $params['request_type'] == 'post'){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post_fields']);
		}

			$content = curl_exec($ch);
			curl_close($ch);
			return $content;
		
		} catch (Exception $e) {
			   // echo 'Caught exception: ',  $e->getMessage(), "\n";
			}	
	}

	function launchBackgroundProcess($call){
		pclose(popen($call . " &", "r"));
		return true;

	}
		
?>