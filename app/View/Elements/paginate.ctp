<?php $this->Paginator->options(array('url' => $this->passedArgs));

$modelArr = array_keys($this->params['paging']);
 $model = $modelArr[0];
 $pos = strpos($this->params['action'], '/');
 if ($pos === false) {
     
     $action = $this->params['action'];     
 }
 else {
     
     $arr = explode('/', $this->params['action']);
     $action = $arr[1];
 }

 echo $url = $this->params['controller'].'/a'.$action.'/';
//$model = Inflector::classify($this->params['controller']);

if (count($this->passedArgs)) {

           foreach ($this->passedArgs as $key => $value) {
               if($value){
                    $url .= $key.':'.$value;
                    $url .= '/';
               }
            }                
        }
echo $url;
die;

 ?>
 	

<table align="center" class="paginate_class" width="100%" >  
        <tr>
            <!--<td align="left" width="15%">
                <?php
                echo $this->Paginator->counter(array('format' => 'Total Records: {:count} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
                ?>

            </td>-->
            <td align="center" width="75%">
            	<ul class="pagination pagination-sm">
                <?php
                // Shows the next and previous links
				 echo $this->Paginator->first('«', array('tag' => 'li','currentClass' => 'footable-page-arrow disabled','class' => 'footable-page-arrow'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
               // echo $this->Paginator->first('« First', null, null, array('class' => 'footable-page-arrow disabled','tag' => 'li'));
			   echo $this->Paginator->prev('‹', array('tag' => 'li','currentClass' => 'footable-page-arrow disabled','class' => 'footable-page-arrow'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
               // echo $this->Paginator->prev('« Previous', null, null, array('class' => 'footable-page-arrow disabled' ,'tag' => 'li'));
                echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a','class' => 'footable-page' , 'currentClass' => 'footable-page active','tag' => 'li','first' => 1));
				
				//echo $this->Paginator->numbers(array('modulus' => 3, 'separator' => '&nbsp;|&nbsp;','class' => 'footable-page', 'tag' => 'li'));
				 echo $this->Paginator->next('›', array('tag' => 'li','currentClass' => 'footable-page-arrow disabled','class' => 'footable-page-arrow'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
             //   echo $this->Paginator->next('Next »', null, null, array('class' => 'footable-page-arrow disabled','tag' => 'li'));
			 echo $this->Paginator->last('»', array('tag' => 'li','currentClass' => 'footable-page-arrow disabled','class' => 'footable-page-arrow'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
               // echo $this->Paginator->last('»', null, null, array('class' => 'footable-page-arrow disabled','tag' => 'li'));
                ?>
                </ul>
            </td>
            <td width="10%"> <?php
					
                                $options = array( 10 => '10', 20 => '20',30 => '30', 50 => '50', 100 => '100' , 500 => '500' );
                                echo $this->Form->create(array('url' => $url,'type'=>'get'));
                                if(isset($this->params['url']['page']))
                                   echo $this->Form->hidden('page',array('value' => $this->params['url']['page']));
                                echo $this->Form->select('limit', $options, array(
                                'value'=>$this->params['paging'][$model]['limit'], 
                                'empty' => FALSE, 
                                'onChange'=>'this.form.submit();', 
                                'name'=>'limit'
                                )
                                );
                                echo $this->Form->end();
								
								?>
             </td>
        </tr>
   </table>