<?php

class App_Util_Pagination {
	
    static function Paginate($pageActual,$total,$url,$params="") {
        $totalPages= intval((($total+9)/10));
        $begin=1;
        $end=$totalPages;
        $initialPoint=".. ";
        $endingPoint=" ..";
        if($totalPages>7){
            if($pageActual>4) $begin=$pageActual-3;
            $end=$begin+6;
            if($end>$totalPages) $end=$totalPages;
        }
        if($begin==1) $initialPoint="";
        if($end==$totalPages) $endingPoint="";
//        echo "<br>PA={$pageActual} T={$total} TP={$totalPaginas} I={$inicio} F={$fin}";
        if($totalPages==1) return "";
        $msg ='<br/>';
		$msg.='<div>';
        $msg.='P&aacute;ginas:';
        if(!empty($params)){
            $params.="/";
        }
        //primera pagina
        if ($pageActual>1){
          $msg.='<a href="'.$url.'/'.App_Util_SafeUrl::encryptString($params."page/".($pageActual-1),true).'">';
          $msg.='&lt;&lt;Anterior';
          $msg.='</a> |';
        }else{
          $msg.='<span class="disabled">&lt;&lt;Anterior</span> |';
        }
        $msg.=$initialPoint;
        //Numbered page links
        for($i=$begin;$i<=$end;$i++){
          if ($i != $pageActual){
            $msg.=' <a href="'.$url.'/'.App_Util_SafeUrl::encryptString($params."page/".($i),true).'">';
            $msg.=$i;
            $msg.='</a> |';
          }else{
            $msg.=' <strong>['.$i.']</strong> |';
      		}
          }
          $msg.=$endingPoint;
        //Next page link 
        if($pageActual<$totalPages){
          $msg.='<a href="'.$url.'/'.App_Util_SafeUrl::encryptString($params."page/".($pageActual+1),true).'">';
          $msg.='Siguiente &gt;&gt;';
          $msg.='</a>';
        }else{
          $msg.='<span class="disabled"> Siguiente &gt;&gt;</span>';
        }
        $msg.='</div>';
        return $msg;
/*
 * Drop down paginaton example
 */
/*
?>
<?php if ($this->pageCount): ?>
<select id="paginationControl" size="1">
<?php foreach ($this->pagesInRange as $page): ?>
  <?php $selected = ($page == $this->current) ? ' selected="selected"' : ''; ?>
  <option value="<?php
        echo $this->url(array('page' => $page));?>"<?php echo $selected ?>>
    <?php echo $page; ?>
  </option>
<?php endforeach; ?>
</select>
<?php endif; ?>
<script type="text/javascript"
     src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js">
</script>
<script type="text/javascript">
$('paginationControl').observe('change', function() {
    window.location = this.options[this.selectedIndex].value;
})
</script>
*/
        
	}
}//fin de la clase
