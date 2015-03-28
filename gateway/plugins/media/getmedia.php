<?php

include '../../config.php';

// upload new image
if (empty($_POST['action'])) {
  $module = $_POST['modulename'];
  $filename = $_POST['filename'];
  
  $useType = $_POST['useType'];
  if($useType == 'thumbnail') {
    $size = 25;
  } else {
    $size = 60;
  }

  $server_temp = SITE_MEDIA_TEMP_PATH . DIRECTORY_SEPARATOR;
  $server_final = SITE_MEDIA_PATH . DIRECTORY_SEPARATOR;

  $error = "";
  $imagename = "";

  $fileElementName = $filename;
  $xfile_type = $_FILES[$fileElementName]["type"];
  
  //print_r($_FILES['size']);
  //die;

  if (!empty($_FILES[$fileElementName]['error'])) {
    switch ($_FILES[$fileElementName]['error']) {
      case '1':
        $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        break;
      case '2':
        $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        break;
      case '3':
        $error = 'The uploaded file was only partially uploaded';
        break;
      case '4':
        $error = 'No file was uploaded.';
        break;

      case '6':
        $error = 'Missing a temporary folder';
        break;
      case '7':
        $error = 'Failed to write file to disk';
        break;
      case '8':
        $error = 'File upload stopped by extension';
        break;
      case '999':
      default:
        $error = 'No error code avaiable';
    }
  } elseif (empty($_FILES[$filename]['tmp_name']) || $_FILES[$filename]['tmp_name'] == 'none') {
    $error = 'No file was uploaded!!..';
  //} elseif (!empty($_FILES[$filename]['size']) && $_FILES[$filename]['size'] > $size*1024) {
    //$error = 'Image size is large. please upload image smaller them '.$size.'KB';
  } else if (ereg("^image/", $xfile_type) != true) {
    $error = 'Image File Error. Please upload a .jpg or .gif';
  } else {
    //Upload the image & resize it to its requires sizes
    $imagename = mpUploadImage($_FILES[$filename], $module);
    //for security reason, we force to remove all uploaded file
    @unlink($_FILES[$filename]);
  }
  echo "{";
  echo "error: '" . $error . "',\n";
  echo "msg: '" . $imagename . "'\n";
  echo "}";
  die;
}

$action = $_POST['action'];
$html = '';

switch ($action) {
  case 'search':
    //print_r($_POST);
    //die;
    $keyword = $_POST['keyword'];
    $type = $_POST['type'];                        // This value will be posted automatically if type (&type=multiple) is passed initially, so dont worry for this

    $object_aggregated_media = new AggregatedMedia();
    $current_page_number = $_POST['page'];
    $params = array();                                // parameters needed by solr engine
    $params['keywords'] = $keyword;

    $params['limit'] = 20;
    if ($current_page_number == 1) {
      $params['start'] = 0;
    } else {
      $params['start'] = $params['limit'] * ($current_page_number - 1) + 1;
    }

    if (isset($_POST['fromDate']) && !empty($_POST['toDate'])) {

      $fD = explode('-', $_POST['fromDate']);
      $eD = explode('-', $_POST['toDate']);

      $params['date'] = $fD[2] . '-' . $fD[1] . '-' . $fD[0] . ' 00:00:00';
      $params['end_date'] = $eD[2] . '-' . $eD[1] . '-' . $eD[0] . ' 23:59:59';
    }

    if ($_POST['sortBy'] == 1) {
      $params['sort'] = array('insertdate' => 'desc');
    }

    $params['media_type'] = 1;
    
    $result = $object_aggregated_media->getMedia($params);         // getMedia() returns data as per parameters given        
    
    $result = json_decode($result);
    
    $records = $result->response->docs; // solr document result    
    $total_number_of_records = $result->response->numFound;

    foreach ($records as $key => $record) {      
      $dataset = (array) $record;      
      $media[$key]['id'] = md5($dataset['media_content']);
      $url = $dataset['media_content'];
      if ($dataset['is_editorial'] == 1) {
        $url = SITE_MEDIA_URL . '/' . $url;
      }

      $date = explode('T', $dataset['insertdate']);
      $date = explode('-', $date[0]);
      $media[$key]['url'] = $url;
      $media[$key]['alt'] = $dataset['image_alt'];
      $media[$key]['credits'] = $dataset['credits'];
      $media[$key]['caption'] = $dataset['caption'];
      $media[$key]['title'] = $dataset['title'];
      $media[$key]['is_editorial'] = $dataset['is_editorial'];
      $media[$key]['is_stored'] = $dataset['is_stored'];
      $media[$key]['content_id'] = $dataset['content_id'];
      $media[$key]['author'] = $dataset['author_name'];
      $media[$key]['keywords'] = implode(',', $dataset['keywords']);

      $media[$key]['date'] = $date[2] . '-' . $date[1] . '-' . $date[0];

      if (empty($media[$key]['alt'])) {
        $media[$key]['alt'] = $media[$key]['title'];
      }

      if (empty($media[$key]['alt'])) {
        $media[$key]['alt'] = $media[$key]['caption'];
      }
    }
    
    $html .= '<div id="searchArea">';
    $html .= '<div id="filters" class="filtersForm" style="float:left; padding:5px; height:430px; width: 19%;">';
    $html .= '<div><strong>Refine Results</strong></div>';

    $html .= '<div>';
    $html .= '<div><lebal>Sort By</lebal></div>';
    if ($_POST['sortBy'] == 1) {
      $checked1 = 'checked';
    } else {
      $checked1 = '';
    }
    if ($_POST['sortBy'] == 2) {
      $checked2 = 'checked';
    } else {
      $checked2 = '';
    }
    $html .= '<div><input type="radio" name="sortBy" id="sortBy1" value="1" ' . $checked1 . '>Date&nbsp;<input type="radio" name="sortBy" id="sortBy2" value="2" ' . $checked2 . '>Relevance</div>';
    $html .= '</div>';

    $html .= '<div>';
    $html .= '<div><lebal>Date Filter</lebal></div>';
    $html .= '<div>';
    $html .= '<input type="text" name="fromDate" id="fromDate" class="calendar2 inputWizard2 search-date" style="height:25px" value="' . $_POST['fromDate'] . '" size="5" />&nbsp;-&nbsp;<input type="text" name="toDate" id="toDate" class="calendar2 inputWizard2 search-date"  value="' . $_POST['toDate'] . '" size="5"  style="height:25px" />';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '<div>';
    $html .= '<input type="button" value="Filter" onclick="mpSearchImages(1, 1)"/>';
    $html .= '</div>';

    $html .= '</div>';
    $html .= '<div id="result" style="float:left;  width: 80%">';
    
    $paging = pagination($total_number_of_records, $params['limit'], $current_page_number);
    
    $html .= '<div style="padding: 10px;">' . $paging . '</div>';

    foreach ($media as $m) {
      $id = $m['id'];
      $html .= '<div style="float:left; padding: 5px;" id="id_' . $id . '">';

      // image
      $html .= '<div>';

      $url = $m['url'];
      $html .= img_tag($url, $m['alt']);
      $html .= '</div>';

      // actions
      $html .= '<div>';
      foreach ($m as $k => $v) {        
        $html .= '<textarea style="display:none" name="' . $id . '_' . $k . '" id="' . $id . '_' . $k . '" >' . $v . '</textarea>';
      }

      $html .= '<a href="#" onclick="mpOpenAddImage(\'' . $id . '\')"><img src="' . IMAGEPATH . '/media-add.png" alt="Add"/></a>&nbsp;&nbsp;';      
      $html .= '</div>';

      $html .= '</div>';
    }
    $html .= '<div style="clear:both" />';
    $html .= '</div>';
    $html .= '</div>';
    echo $html;
    break;


  //----------  crop image  
  case 'crop':

    $image_name = $_POST['url'];

    if ($_POST['is_editorial'] == 0 && empty($_POST['is_stored'])) {
      // download and save image      
      $r = downloadImageFromURL($image_name);
      $name = $r[2];
      $tmpPath = $r[1];
      $is_downloaded = $r[0];
      if ($is_downloaded && is_file($tmpPath)) {
        $can_crop = true;
      } else {
        $can_crop = false;
        $msg = "Unable to download image";
      }
    } else {
      // copy image from original location to tmp      

      if ($_POST['is_editorial'] == 1) {
        // find image name
        $arr = explode('/', $image_name);

        $tName = $arr[count($arr) - 1];
        $sPath = str_replace(SITEPATH, ROOTPATH, $image_name);

        $extension = strrchr($image_name, '.');
        $extension = strtolower($extension);

        // remove the time part
        $arr2 = explode('_', $tName);
        if (count($arr2) > 1) {
          unset($arr2[count($arr2) - 1]);
        }

        $name = implode('_', $arr2) . '_' . time() . $extension;
        $tmpPath = ROOTPATH . '/temp/' . $name;

        copy($sPath, $tmpPath);
      } else {
        $name = str_replace(SITEPATH . '/temp/', '', $image_name);
        $tmpPath = ROOTPATH . '/temp/' . $name;
      }

      if (is_file($tmpPath)) {
        $can_crop = true;
      } else {
        $can_crop = false;
        $msg = "Image not found"; // will be rare. possibly permission problem
      }
    }

    $arrJson = array();

    if ($can_crop) {

      $extension = strrchr($tmpPath, '.');
      $extension = strtolower($extension);

      $croppedName = substr($name, 0, strpos($name, '.')) . '_' . time() . $extension;
      $croppedPath = ROOTPATH . '/temp/' . $croppedName;
      if (is_file($croppedPath)) {
        unlink($croppedPath);
      }

      $jpeg_quality = 100;
      $dst_r = imagecreatetruecolor($_POST['w'], $_POST['h']);
      $is_cropped = 0;
      switch ($extension) {
        case '.jpg':
        case '.jpeg':
          $img_r = imagecreatefromjpeg($tmpPath);
          imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x1'], $_POST['y1'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
          $is_cropped = imagejpeg($dst_r, $croppedPath, $jpeg_quality);
          break;
        case '.png':
          $img_r = imagecreatefrompng($tmpPath);
          imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x1'], $_POST['y1'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
          $is_cropped = imagepng($dst_r, $croppedPath);
          break;
        case '.gif':
          $img_r = imagecreatefromgif($tmpPath);
          imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x1'], $_POST['y1'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
          $is_cropped = imagegif($dst_r, $croppedPath, $jpeg_quality);
          break;
      }

      imagedestroy($dst_r);

      if ($is_cropped) {

        $tmpURL = SITEPATH . '/temp/' . $name;
        $croppedURL = SITEPATH . '/temp/' . $croppedName;

        $arrJson['status'] = "success";
        $arrJson['iTPath'] = $tmpURL;
        $arrJson['iCPath'] = $croppedURL;
      } else {
        $arrJson['status'] = "fail";
        $arrJson['msg'] = "Unable to crop the image";
      }
    } else {
      $arrJson['status'] = "fail";
      $arrJson['msg'] = $msg;
    }
    echo json_encode($arrJson);
    die;
    break;

  case 'use':
    if ($_POST['is_editorial'] == 0 && empty($_POST['is_stored'])) {
      // download and save image    	
      $r = downloadImageFromURL($_POST['url']);
      $name = $r[2];
      $tmpPath = $r[1];
      $is_downloaded = $r[0];
      if ($is_downloaded && is_file($tmpPath)) {
        $can_use = true;
      } else {
        $can_use = false;
        $msg = "Unable to download image";
      }
    } else {
      if ($_POST['is_editorial'] == 1) {
        // find image name
        $arr = explode('/', $_POST['url']);

        $tName = $arr[count($arr) - 1];
        $sPath = str_replace(SITEPATH, ROOTPATH, $_POST['url']);

        $extension = strrchr($_POST['url'], '.');
        $extension = strtolower($extension);

        // remove the time part
        $arr2 = explode('_', $tName);
        if (count($arr2) > 1) {
          unset($arr2[count($arr2) - 1]);
        }

        $name = implode('_', $arr2) . '_' . time() . $extension;
        $tmpPath = ROOTPATH . '/temp/' . $name;

        copy($sPath, $tmpPath);
      } else {
        // already downloaded or existing image
        $name = str_replace(SITEPATH . '/temp/', '', $_POST['url']);
        $tmpPath = ROOTPATH . '/temp/' . $name;
      }

      if (is_file($tmpPath)) {
        $can_use = true;
      } else {
        $can_use = false;
        $msg = "Image not found"; // will be rare. possibly permission problem
      }
    }

    $arrJson = array();
    if ($can_use) {
      $images = mpUseImage($name, $tmpPath, $_POST['module'], $_POST['type'], $_POST['contentType']); //, $_POST['contentType'], $_POST['section']);
      
      //print_r($images);
      //die;
      
      $html = '';

      if ($_POST['type'] == 'editor') {

        //-- add to solr
        $_POST['path'] = $images[0];
        mpAddToSolr($_POST, '');
        
        // SITE_MEDIA_URL . '/' . $images[0]
        if($_POST['contentType'] == NEWS || $_POST['contentType'] == COLUMN) {
          $widthval = $sizearrayPlugin[$_POST['module']][$_POST['type']]['width'][0];
          $heigthval = $sizearrayPlugin[$_POST['module']][$_POST['type']]['height'][0];          
        } else {      
          $widthval = 540;
          $heigthval = 540;          
        }        
        
        $sizeval = $widthval . 'x' . $heigthval;
        $img = SITE_MEDIA_URL .'/'.getthumbnail($images[0],$sizeval);

        $sz = $images['sizes']['new_filename_'.$widthval.'x'.$heigthval];

        //$html .= '<p class="articlefigure">';
        $html .= '<p>';
        if(!empty ($_POST['pImgCaption'])) {
          $html .= '<span class="picCaption" style="width: '.$sz[0].'px; display: inline; margin-top: '.($sz[1] - 22).'px;"><span style="width: '.($sz[0] - 10).'px; height: 16px">'.$_POST['pImgCaption'].'</span></span>';
        }        
        $html .= '<img src="'.$img.'" width="'.$sz[0].'" height="'.$sz[1].'" title="' . $_POST['pImgAlt'] . '" alt="' . $_POST['pImgAlt'] . '" id="ed-img"/>';        
        $html .= '</p>';        
        
      } else if ($_POST['type'] == 'gallary') {    
        
        $w = $sizearrayPlugin[$_POST['module']][$_POST['type']]['width'][0];
        $h = $sizearrayPlugin[$_POST['module']][$_POST['type']]['height'][0];
        $sz = $images['sizes']['new_filename_'.$w.'x'.$h];
        
        $html .= "<li style='float:left;padding: 10px; margin-left: 0px; list-style: none;' id='strip_" . $_POST['cnt'] . "'>";

        $imgPath = SITE_MEDIA_URL . '/' . $images[0];
        $_POST['url'] = $imgPath;
        $_POST['origonal_url'] = $imgPath;
        if ($_POST['is_editorial'] == 2) {
          $_POST['is_editorial'] = 1;
        }

        $html .= "<div>";
        $widthval = min($sizearrayPlugin[$_POST['module']][$_POST['type']]['width']);
        $heigthval = min($sizearrayPlugin[$_POST['module']][$_POST['type']]['height']);
        $sizeval = $widthval . 'x' . $heigthval;
        $tURL = SITE_MEDIA_URL . '/' . getthumbnail($images[0], $sizeval);

        $html .= "<img src='" . $tURL . "' height='50px' width='50px'/>";
        $html .= "</div>";
        $html .= "<div style='padding-top: 5px'  class='mp-delete'>";
        $html .= "<a href='#' onclick='mpRemoveImageFromStrip(\"" . $_POST['cnt'] . "\")'><img src=\"" . IMAGEPATH . "/media-remove.gif\" alt=\"Remove\" title=\"Remove\"/></a>";
        $html .= "<a href='#' onclick='mpEditFromStrip(\"" . $_POST['cnt'] . "\")'><img src=\"" . IMAGEPATH . "/media-edit.png\" alt=\"Edit\" title=\"Edit\"/></a>";
        $html .= "</div>";

        $html .= "<div style='clear:both; display: none'>";
        $html .= "<input type='text' id='" . $_POST['control'] . "_" . $_POST['cnt'] . "' name='" . $_POST['control'] . "[" . $_POST['cnt'] . "][path]' value='" . $images[0] . "'/>";
        foreach ($_POST as $k => $v) {
          if ($k != 'control' && $k != 'cnt') {
            $html .= "<textarea id='" . $k . "_" . $_POST['cnt'] . "' name='" . $_POST['control'] . "[" . $_POST['cnt'] . "][$k]' >" . $v . "</textarea>";
          }
        }
        
        $html .= "<textarea id='pWidth_" . $_POST['cnt'] . "' name='" . $_POST['control'] . "[" . $_POST['cnt'] . "][pWidth]' >" . $sz[0] . "</textarea>";
        $html .= "<textarea id='pHeight_" . $_POST['cnt'] . "' name='" . $_POST['control'] . "[" . $_POST['cnt'] . "][pHeight]' >" . $sz[1] . "</textarea>";
        //$images
        $html .= "</div>";

        $html .= "</li>";
      } else if ($_POST['module'] == 'blocks' && $_POST['type'] == 'thumbnail') {
        //-- save image to block        
        $_POST['path'] = $images[0];
        mpAddToSolr($_POST, '');
        $db = Database::Instance();
        $values['thumbnail'] = $images[0];
        $values['thumbnail_alt'] = $_POST['pImgAlt'];
        $whereArr['id'] = $_POST['rid'];
        $html = $db->updateDataIntoTable($values, $whereArr, 'pagemanagement', false);
      } else {
        
        $w = $sizearrayPlugin[$_POST['module']][$_POST['type']]['width'][0];
        $h = $sizearrayPlugin[$_POST['module']][$_POST['type']]['height'][0];
        $sz = $images['sizes']['new_filename_'.$w.'x'.$h];

        $imgPath = SITE_MEDIA_URL . '/' . $images[0];
        $_POST['url'] = $imgPath;
        $_POST['origonal_url'] = $imgPath;
        if ($_POST['is_editorial'] == 2) {
          $_POST['is_editorial'] = 1;
        }

        if ($_POST['type'] == 'thumbnail') {
          $widthval = min($sizearrayPlugin[$_POST['module']][$_POST['type']]['width']);
          $heigthval = min($sizearrayPlugin[$_POST['module']][$_POST['type']]['height']);
          
          $sizeval = $widthval . 'x' . $heigthval;
          $flag = 1;
          $displayImage = SITE_MEDIA_URL . '/' . getthumbnail($images[0], $sizeval);
        } else {
          $sizeval = '';
          $flag = 0;
          $displayImage = SITE_MEDIA_URL . '/' . $images[0];
        }

        $html .= "<div style='float:left'>";
        if ($flag) {
          $html .= "<img src='" . $displayImage . "'/>";
        } else {
          $html .= "<img src='" . $displayImage . "' width='100px' height='100px'/>";
        }
        $html .= "</div>";
        $html .= "<div style='float:left; margin-left: 10px'>";
        $html .= "<a href='#' onclick='mpRemoveImage(\"" . $_POST['module'] . "\",\"" . $_POST['control'] . "\",\"" . $_POST['type'] . "\")'>X</a>";
        $html .= "</div>";
        $html .= "<div style='clear:both; display: none'>";
        $html .= "<input type='text' id='" . $_POST['control'] . "' name='" . $_POST['control'] . "[path]' value='" . $images[0] . "'/>";
        foreach ($_POST as $k => $v) {
          if ($k != 'control') {
            $html .= "<textarea id='" . $_POST['control'] . "_$k' name='" . $_POST['control'] . "[$k]' >" . $v . "</textarea>";
          }
        }
        
        $html .= "<textarea id='".$_POST['control']."_pWidth' name='" . $_POST['control'] . "[pWidth]' >" . $sz[0] . "</textarea>";
        $html .= "<textarea id='".$_POST['control']."_pHeight' name='" . $_POST['control'] . "[pHeight]' >" . $sz[1] . "</textarea>";
        
        $html .= "</div>";
      }

      $arrJson['status'] = "success";
      $arrJson['html'] = $html;
    } else {
      $arrJson['status'] = "fail";
      $arrJson['msg'] = $msg;
    }
    echo json_encode($arrJson);

    //
    die;
    break;
}

function img_tag($src, $alt) {
  $img = '<img  style="border:1px grey solid;" onmouseout="this.style.border=\'1px grey solid\';" onmouseover="this.style.border=\'1px magenta solid\';"';
  $img .= ' src="' . $src . '"';
  $img .= ' title="' . $alt . '"';
  $img .= ' height="100px" width="100px"/>';
  return $img;
}

function pagination($total, $per_page = 10, $page = 1, $url = '?') {
  
  $adjacents = "2";

  $page = ($page == 0 ? 1 : $page);
  $start = ($page - 1) * $per_page;

  $prev = $page - 1;
  $next = $page + 1;
  $lastpage = ceil($total / $per_page);
  $lpm1 = $lastpage - 1;    

  $pagination = "";
  if ($lastpage > 1) {  
    $pagination .= "<span style='background:#eee;padding:5px 10px 5px; 10px;'>Page $page of $lastpage</span>";
    if ($lastpage < 7 + ($adjacents * 2)) {
      for ($counter = 1; $counter <= $lastpage; $counter++) {
        if ($counter == $page) {          
          $pagination.= '<a style="background:#ccc;padding:5px 10px 5px; 10px;" href="javascript: void(0);" ">' . $counter . '</a>';
        } else {          
          $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $counter . '\');">' . $counter . '</a>';
        }
      }
    }
    elseif ($lastpage > 5 + ($adjacents * 2)) {
      if ($page < 1 + ($adjacents * 2)) {
        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
          if ($counter == $page)
            $pagination.= '<a style="background:#ccc;padding:5px 10px 5px; 10px;" href="javascript: void(0);" ">' . $counter . '</a>';
          else
            $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $counter . '\');">' . $counter . '</a>';
        }
        $pagination.= "<span style='background:#eee;padding:5px 10px 5px; 10px;'>...</span>";
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $lpm1 . '\');">' . $lpm1 . '</a>';
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $lastpage . '\');">' . $lastpage . '</a>';        
      }
      elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'1\');">1</a>';
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'2\');">2</a>';        
        $pagination.= "<span style='background:#eee;padding:5px 10px 5px; 10px;'>...</span>";
        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
          if ($counter == $page) {
            $pagination.= '<a style="background:#ccc;padding:5px 10px 5px; 10px;" href="javascript: void(0);" ">' . $counter . '</a>';
          } else {
            $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $counter . '\');">' . $counter . '</a>';
          }
        }
        $pagination.= "<span style='background:#eee;padding:5px 10px 5px; 10px;'>..</span>";
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $lpm1 . '\');">' . $lpm1 . '</a>';
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $lastpage . '\');">' . $lastpage . '</a>';        
      }
      else {
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'1\');">1</a>';
        $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'2\');">2</a>';        
        $pagination.= "<span style='background:#eee;padding:5px 10px 5px; 10px;'>..</span>";
        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
          if ($counter == $page) {
            $pagination.= '<a style="background:#ccc;padding:5px 10px 5px; 10px;" href="javascript: void(0);" ">' . $counter . '</a>';                      
          } else {
            $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $counter . '\');">' . $counter . '</a>';            
          }
        }
      }
    }

    if ($page < $counter - 1) {
      $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $next . '\');">Next</a>';
      $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" onclick="mpSearchImages(1, \'' . $lastpage . '\');">Last</a>';      
    } else {
      $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" ">Next</a>';
      $pagination.= '<a style="background:#eee;padding:5px 10px 5px; 10px;" href="javascript: void(0);" ">Last</a>';      
    }    
  }


  return $pagination;
}

?>