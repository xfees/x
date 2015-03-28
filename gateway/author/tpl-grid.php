<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr class="removeheading">
      <td class="pL pTB" id="notification"></td>
    </tr>
    <tr>
      <td class="pL pTB">
        <?php        
        if (!empty($result_data)) {
          foreach ($result_data as $key => $val) {
            if ($val["status"] == 0) {
              $display = 'UnPublish';
              $title = 'Click to Publish';
              $cssPublished = 'unPublished';
              $cssSpanID = "blueBG";
            } else {
              $display = 'Publish';
              $title = 'Click to UnPublish';
              $cssPublished = 'published';
              $cssSpanID = "greenBG";
            }
            //Console::log($val);                    
            if ($flag == 'a' || isset($search)) {   //----------If action was add then add a new div 
              ?>
              <!--  start box -->
              <div class='contentBoxContent' id="singleCont<?php echo $val['id']; ?>">
                <div class='contentBox_in'>
                  <div class="contentBox_content">

                    <div class="galleryR1">
                      <div style="float:left">
                        <?php if ($val['thumbnail'] == '') { ?>
                          <img src="<?php echo IMAGEPATH; ?>/notFound_75x75.gif" alt="<?php echo $val['name']; ?>" height="60" width="60" style="margin-right:5px;"/>
                          <?php
                        } else {
                          $widthval = $sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][width][count($sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][width]) - 2];
                          $heigthval = $sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][height][count($sizearrayPlugin[$_SESSION['TOPMENU']]['thumbnail'][width]) - 2];
                          $sizeval = $widthval . 'x' . $heigthval;
                          $thumbnailval = getthumbnail($val['thumbnail'], $sizeval);
                          ?>
                          <img src="<?php echo SITEAUTHORPATH; ?>/<?php echo $thumbnailval; ?>" alt="<?php echo $val['name']; ?>" height="60" width="60" style="padding:5px;"/>
                          <?php
                        }
                        ?>
                      </div>
                      <div style="float:left">
                        <div class='titleText pb4' id="hdlnplaceholder<?php echo $val['id'] ?>"  title="Click to edit name"><?php echo $val["name"] ?></div>
                        <div class='byCate pb4'><a href="javascript:void(0)" onclick="searchAuthorByAuthorType('<?php echo $val['rights']; ?>')" title="Show all <?php echo $userDetails[$val['rights']]; ?>"><?php echo $userDetails[$val['rights']]; ?></a></div>
                      </div>
                    </div>
                    <div class="galleryR2">
                      <div class='byAuthor pb4' style="clear:both"><?php echo $val["email"]; ?></div>	
                      <div class='byAuthor pb4' style="clear:both">User Type :<?php
                  switch ($val['by_line']) {
                    case 1:
                      $by_line = 'By Line';
                      break;
                    case 2:
                      $by_line = 'CMS User';
                      break;
                    case 3:
                      $by_line = 'Both';
                      break;
                  }
                  echo $by_line;
                        ?></div>	
                      <div class='byAuthor pb4' style="clear:both">Last login : <?php echo getdisplaydatetime($val["lastvisit"]); ?><br>Story count :<?php if ($val["story_count"] != 0) { ?><a href="javascript:void(0);" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/author/stats.php?searchByAuthor=<?php echo $val['id'] ?>', 850, 550);" title="View Statistics"><?php echo number_format($val["story_count"]); ?></a><?php } else { echo 0; } ?></div>		

                    </div></div>
                  <div class="contentBox_footer">
                    <div class="actions floatR">
                      <?php if ($action == 'tc' || $displaypage == 'trashcan') { ?>
                        <a href="javascript:void(0);" onclick='callUnDelete(<?php echo $val['id']; ?>,"<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="restore" title="Restore"><b>Restore</b></a>
                      <?php } else { ?>
                        <a href="<?php echo $modulePath; ?>/form.php?id=<?php echo $val['id']; ?>&action=m" class="edit" title="Edit" >Edit</a>		
                        <a href="<?php echo $modulePath; ?>/storydetails.php?id=<?php echo $val['id']; ?>" title="View Story Details" class="details">Details</a>				 
                        <?php if ($val["email"] != '') { ?>
                          <a href="javascript:void(0);" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/author/authormail.php?email=<?php echo $val['email'] ?>', 500, 400);" title="Email <?php echo $val["name"]; ?>" class="email">Email</a>
                        <?php } ?>
                        <a href="javascript:void(0);" class="log" onclick="ModalBox.open('<?php print CMSSITEPATH ?>/adminlog/display.php?hide_layout=1&searchByAuthor=<?php echo $val['id'] ?>', 850, 550);" href="javascript:void(0);" title="View logs">Log</a>  <a href="javascript:;" onclick='callDelete("<?php echo $val['id']; ?>","<?php echo strtolower($_SESSION['TOPMENU']) ?>")' class="delete" title="Delete">Delete</a>
                        <?php } ?>
                    </div>
                    <span class='<?php if ($action != 'tc') { ?>hand<?php } ?> contentid <?php echo $cssSpanID ?>' <?php if ($action != 'tc') { ?> onclick="location.href='<?php echo $modulePath; ?>/form.php?id=<?php echo $val['id']; ?>&action=m';" <?php } ?>><?php echo $val['id']; ?></span>                    
                  </div>
                </div>
                <div class='contentBoxBtm'></div>
                <div class="popdown boxRound abs" style="display:none">
                  <span class="ui-icon ui-icon-circle-close icon-close" title="close" onclick="closeMorePopup(this)"></span>
                  <a href="javascript:void(0)" onclick="openModalBox(<?php echo $val['id']; ?>);" title="Tag Data">Tag Data</a>
                  &nbsp;|&nbsp; 							
                  <a href="#">Photo Gallery</a>&nbsp;|&nbsp; 	
                  <a href="javascript:void(0)" title="Delete" class="delete" onclick='callDelete("<?php echo $val['id']; ?>","<?php echo $_SESSION['TOPMENU'] ?>")'>Delete</a>
                </div>
              </div>
              <!--  end box -->
            <?php
            }
          }
          ?>
        </td>
      </tr>
  <?php
} else {
  ?>
      <tr>
        <td class="pL pTB" id="norecordsdiv">No Records</td>
      </tr>
  <?php
}//end of else	
?>
    <tr>
      <td class="pL pTB">
        <?php
        if (Common::l($_GET['gs_paging']) != "0") {          
          echo $paginate->render($total);
        }
        ?>
      </td>
    </tr>
  </tbody>
</table>
