<?php
 echo $this->Html->css(array('/bootstrap/css/bootstrap.min','popup',
									
									'font-awesome/css/font-awesome.min',
									
									'/js/lib/datepicker/css/datepicker',
									'/js/lib/timepicker/css/bootstrap-timepicker.min'
									
									
									)
							);
echo $this->Html->script(array('jquery.min','lib/chained/jquery.chained.remote.min','lib/jquery.inputmask/jquery.inputmask.bundle.min','lib/parsley/parsley.min','pages/ebro_form_validate','lib/datepicker/js/bootstrap-datepicker','lib/timepicker/js/bootstrap-timepicker.min','pages/ebro_form_extended'));
		/* End */
		//pr($this->data);
?>

<!----------------------------start add project block------------------------------>

<div class="pop-outer">
     <div class="pop-up-hdng">Hotel Count</div>


    <?php
    //echo $this->Form->create('Remark', array('enctype' => 'multipart/form-data'));
	echo $this->Form->create('Reimbursement', array('method' => 'post','id' => 'parsley_reg','novalidate' => true,
													'inputDefaults' => array(
																	'label' => false,
																	'div' => false,
																	'class' => 'form-control',
																),
													array('controller' => 'reimbursements','action' => 'edit')	
						));
  
  
   
    ?>
 
<div class="col-sm-12">
    <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="1000">
        <thead>
            <tr>
            <th data-hide="phone" data-group="group1">Continent</th> 
            <th data-hide="phone" data-group="group1">Province</th> 
            <th data-hide="phone" data-group="group1">Country</th>                        
            <th data-hide="phone" data-group="group1">City</th> 
            </tr>
        </thead>
        <tbody>
            <tr>
                
            </tr>
        </tbody>
    </table>
	</div>
            
        

    <?php echo $this->Form->end();
    ?>
</div>	

		
<!----------------------------end add project block------------------------------>
