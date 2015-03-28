<?php
include_once '../config.php';
$_SESSION['TOPMENU'] = 'adminlog';
$database = ( isset($_GET['database']) && !empty($_GET['database']) ) ? $_GET['database'] : '';
$filename = $_SESSION['TOPMENU'];
$title = 'Admin Log(s)';
$main_file = "get{$filename}.php";
$show_layout = isset($_GET['hide_layout']) ? false : true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	
  <head>	 		
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	 		
    <title>			
      <?php echo $title; ?>		
    </title>	 		
    <?php include_once '../incHeaderScript.php'; ?>  
    <script type='text/javascript' src='<?php echo $filename ?>.js'></script>
    <?php if($show_layout==false) { ?>
    <script type="text/javascript">
    GlobalSearch.disabled(true);
    </script>    
    <?php } ?>
  </head>	
  <body>
    <?php if ($show_layout): ?>
      <?php include_once CMSROOTPATH . '/topmenu.php'; ?>		
    <?php endif ?>
    <div class="content">			
      <div class="title">				
        <?php print $title; ?>			
      </div>   			
      <table width="100%" border="0" cellspacing="0" cellpadding="0">     				
        <tr>       					
          <td valign="top" class="leftPanel">						
            <!-- make this td conditional -->         						
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">           							
              <tr>             								
                <td width="10" class="boxtopLeft"></td>             								
                <td class="boxtopMiddle"></td>             								
                <td class="boxtopRight"></td>           							
              </tr>           							
              <tr>             								
                <td width="10" class="boxMiddleLeft"></td>             								
                <td class="boxMiddleMiddle">	            									
                  <div class="leftlinks">										
                    <?php include_once CMSROOTPATH . '/leftmenu.php'; ?>									
                  </div>                         </td>             								
                <td width="10" class="boxMiddleRight"></td>           							
              </tr>           							
              <tr>             								
                <td class="boxbottomLeft"></td>             								
                <td class="boxbottomMiddle"></td>             								
                <td class="boxbottomRight"></td>           							
              </tr>         						
            </table></td>       					
          <td valign="top" class="rightPanel">       						
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="box">	   							
              <tr>		 								
                <td width="10" class="boxtopLeft"></td>		 								
                <td class="boxtopMiddle"></td>		 								
                <td class="boxtopRight"></td>	   							
              </tr>	   							
              <tr>		 								
                <td width="10" class="boxMiddleLeft"></td>		 								
                <td class="boxMiddleMiddle">		 									
                  <div id="tabs" class="padding12">			 										
                    <span id="backtomodule" style="margin-right:5px;">										
                    </span>		 									
                  </div>         									
                  <div id="displaycontent" class="padding12">			 										
                    <div class="sorting"></div>	
					<?php require_once CMSROOTPATH . '/adminlog/search_form.php'; ?>
                    <!--content display starts here --> 		
					
                    <div id="mainContainer">		
					<?php include_once $main_file; ?> 			 										
                    </div>	 		 									
                  </div>		 			 									
                  <!-- content display ends here -->  		 			</td>			 								
                <td width="10" class="boxMiddleRight">&nbsp;</td>		   							
              </tr>		   							
              <tr>			 								
                <td class="boxbottomLeft"></td>			 								
                <td class="boxbottomMiddle"></td>			 								
                <td class="boxbottomRight"></td>		   							
              </tr>		 						
            </table>      </td>     				
        </tr>   			
      </table>		
    </div>	
    <?php if ($show_layout): ?>
      <?php include_once(CMSROOTPATH . "/incFooter.php"); ?>	
    <?php endif ?>
  </body>
  <script type="text/javascript">
$(document).ready(function() {
	 $("#searchById").val('Search by Id');
	 $('input[type=text]').focus(function() {
	  if($(this).val() == $(this).attr('defaultValue')) {
		 $(this).val('');
	  }
   })
   .blur(function() {
	  if($(this).val().length == 0) {
		 $(this).val($(this).attr('defaultValue'));
	  }
   });
})
</script>
</html>