<?php $this->Paginator->options(array('url' => $this->passedArgs));
//pr($this->params);
//pr($this->passedArgs);
$modelArr = array_keys($this->params['paging']);
 $model = $modelArr[0];
//$model = Inflector::classify($this->params['controller']);

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
                                echo $this->Form->create(array('url' => $this->passedArgs,'type'=>'get'));
                                
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