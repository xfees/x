<?php

class Paginate{

  public $offset=0;
  public $recperpage=MAX_FORMS_PER_PAGE;
  public $pg=''; public $action='';
  public $dispfirstpage=1;
  public $displastpage=10;
  public $paginginfo=0;
  public $module='';
  public $filename='';
  public $searchtype='';
  public $searchData='';
  public $sql='';
  public $db=NULL;
  public $res=NULL;
  public $totalrecords=0;
  public $data=array();

  function __construct($module,$search_type='first',$searchData,$disp_first_page=1,$disp_last_page=10,$pg='', $action='', $records_per_page){

    $this->filename = $module;
    $this->searchtype = $search_type;
    $this->searchData = $searchData;
    $this->recperpage = $records_per_page;
    $this->pg = $pg;
    $this->action = $action;
    $this->dispfirstpage = $disp_first_page;
    $this->displastpage = $disp_last_page;
    $this->paginginfo = $this->pagingcalc($this->pg, $this->dispfirstpage,$this->displastpage, $this->recperpage);
    $this->offset = $this->paginginfo[0];
    $this->displastpage = $this->paginginfo[1];
    $this->dispfirstpage = $this->paginginfo[2];
    $this->pg = $this->paginginfo[3];
  }

  function render($sql_count){
    $this->totalrecords=$sql_count;
    $html=$this->pagination();
    return $html;
  }


  function pagingcalc($pg='', $dispfirstpage='', $displastpage='', $recperpage=''){
    $pg==''?$this->pg=1:$this->pg=$pg;
    $dispfirstpage==''?$this->dispfirstpage=1:$this->dispfirstpage=$dispfirstpage;
    $displastpage==''?$this->displastpage=10:$this->displastpage=$displastpage;
    $recperpage==''?$this->recperpage=20:$this->recperpage=$recperpage;

    if($this->pg == 'prev') {
      $this->displastpage = $this->dispfirstpage - 1;
      $this->dispfirstpage = $this->displastpage - 9;
      $this->pg = $this->dispfirstpage;
    } else if($this->pg == 'next') {
      $this->dispfirstpage = $this->displastpage + 1;
      $this->displastpage = $this->dispfirstpage + 9 ;
      $this->pg = $this->dispfirstpage;
    }
    $this->offset = ($this->pg-1) * $this->recperpage;
    return (array($this->offset, $this->displastpage, $this->dispfirstpage, $this->pg));
  }


  function pagination()
  {

    $tp = ceil($this->totalrecords / $this->recperpage);
    if($tp < $this->displastpage ){
      $this->displastpage = $tp;
    }
    $totalpages=$tp;

    $html  = "";
    $html .= '<div id="pagingCont">
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="250">&nbsp;&nbsp;
	<select name="max_form_per_page" id="max_form_per_page" class="inputControl1" onchange=\'searchdata("'.$this->filename.'","'.$this->searchtype.'","'.$this->searchData.'","","","",this.value,"'.$this->action.'")\'>';
    $i = 20;
    while( $i <= 100){
      if($this->recperpage == $i){
        $html .='<option value="'.$i.'" selected="selected">'.$i.'</option>';
      }else{
        $html .='<option value="'.$i.'">'.$i.'</option>';
      }
      $i = $i+20;
    }
    $html .='</select>';
    $html .='&nbsp;&nbsp;<span class="h1td"><b>TotalRecords</b>:&nbsp;'.number_format($this->totalrecords).'&nbsp;</span></td>';
    if($this->totalrecords>0){
      if($this->dispfirstpage > 1)
      {
        $html .='<td width="10"><a href="javascript:void(0);" onclick=\'searchdata("'.$this->filename.'","'.$this->searchtype.'","'.$this->searchData.'","prev", "'.$this->displastpage.'","'.$this->dispfirstpage.'","'.$this->recperpage.'","'.$this->action.'")\' class="btnPrevious">&nbsp;</a></td>';
      }else{
        $html .='<td width="10"><a href="javascript:void(0);" class="btnPrevious disabled">&nbsp;</a></td>';
      }
      $html .='<td class="pageMiddle">&nbsp;&nbsp;';
      for($i=$this->dispfirstpage; $i<=$this->displastpage; $i++) {
        if($this->pg == $i) {
          $html .='<span class="h1td"><b>'.$i.'</b></span>&nbsp;&nbsp;';
        }
        else {
          $html .='<a href="javascript:void(0);" class="LinkBL1" onclick=\'searchdata("'.$this->filename.'","'.$this->searchtype.'","'.$this->searchData.'","'.$i.'","'.$this->displastpage.'","'.$this->dispfirstpage.'","'.$this->recperpage.'","'.$this->action.'")\'>'.$i.'</a>&nbsp;&nbsp;';
        }
      }
      $html .='</td>';
      if($this->displastpage<$totalpages) {
        $html .='<td><a href="javascript:void(0);" onclick=\'searchdata("'.$this->filename.'","'.$this->searchtype.'","'.$this->searchData.'","next","'.$this->displastpage .'","'.$this->dispfirstpage.'","'.$this->recperpage.'","'.$this->action.'")\' class="btnNext">&nbsp;</a></td>';
      }else{
        $html .='<td><a href="javascript:void(0);" class="btnNext disabled">&nbsp;</a></td>';
      }
    }//end of if
    $html .='</tr></table></div>';
    return($html);
  }
}
?>