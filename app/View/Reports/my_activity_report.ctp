<?php $this->Html->addCrumb('My Summary', 'javascript:void(0);', array('class' => 'breadcrumblast')); 

                echo $this->Form->create('Report', array('controller' => 'reports', 'action' => 'my_activity_report','class' => 'quick_search', 'id' => 'parsley_reg', 'novalidate' => true, 'inputDefaults' => array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control',
                )));
                ?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"> My Summary</h4>
            
        </div>
        <div class="panel panel-default">
            <div class="panel_controls hideform">
                 
          
                <div class="row">
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Summary Type:</label>
                        <?php echo $this->Form->input('summary_type', array('options' => $summary, 'empty' => '--Select--', 'data-required' => 'true','disabled' => '2')); ?>
                    </div>
					<div class="col-sm-3 col-xs-6">
                        <label for="un_member">Choose Person:</label>
                        <?php echo $this->Form->input('user_id', array('options' => $persons, 'empty' => $Select)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Choose Date:</label>
                        <?php echo $this->Form->input('choose_date', array('options' => $ChooseDate, 'empty' => '--Select--', 'data-required' => 'true')); ?>
                    </div> 
					<div class="col-sm-3 col-xs-6">
                        <label for="un_member">Supplier:</label>
                        <?php echo $this->Form->input('supplier_id', array('options' => $TravelSuppliers, 'empty' => '--Select--', 'data-required' => 'true')); ?>
                    </div>  					
                    <div class="col-sm-3 col-xs-6">
                       <label>&nbsp;</label>
                        <?php
                        echo $this->Form->submit('View Report', array('div' => false,'label' => false,'class' => 'success btn','style' => 'width: 50%;margin-top: 0px;'));
                        ?>
                    </div>
                </div>
                
            </div>
            <br />
            <?php if($display == 'TRUE'){?>
            <table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="500">
                <thead>
                   <tr class="footable-group-row">
                        <th data-group="group3" colspan="5" class="nodis">Information</th>
                        <th data-group="group1" colspan="5">Wtp Hotel Edit</th> 
                    </tr>
                    <tr>           
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Sl. </th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Person</th>						
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Country</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Province</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">City</th>    
                        
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1" >Hotel Edited</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Mapping Submitted</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1" >Hotel Approved</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1" >Mapping Approved</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1" >Image Uploaded</th>                
						
                                         
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
					//echo '<pre>';
                 /// print_r($TravelCities);
                //  die;
				//echo $data_choose_date;
                    $supplier_id = $this->data['Report']['supplier_id'];
                    if (isset($TravelCities) && count($TravelCities) > 0):
					
					$hotelEditedCnt = 0;
					$MappingSubmittedCnt = 0;
				
					
                        foreach ($TravelCities as $TravelCity):
                            $id = $TravelCity['TravelCity']['id'];              
                            $country_id = $TravelCity[0]['country_id'];
							
							
							$hotelEditedCnt += $hotelEditedCnt_1 = $this->Custom->getHoteByDateCnt($country_id,$id,$data_choose_date,'Hotel Edited');
							$MappingSubmittedCnt += $MappingSubmittedCnt_1 = $this->Custom->getHoteByDateCnt($country_id,$id,$data_choose_date,'Mapping Submitted');
							
                            ?>
                            <tr>                              
								<td><?php echo $i; ?></td>
                                <td><?php echo $this->Custom->Username($TravelCity[0]['user_id']); ?></td>								
                                <td><?php echo $this->Custom->getCountryName($country_id); ?></td>
                                <td><?php echo $this->Custom->getProvinceName($TravelCity[0]['province_id']); ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_name']; ?></td> 
                                
                                <td class="background_yellow"><?php echo $hotelEditedCnt_1; ?></td>
                                <td class="background_yellow"><?php echo $MappingSubmittedCnt_1; ?></td>
                                <td class="background_yellow">0</td>                               
                                <td class="background_yellow">0</td>
								<td class="background_yellow">0</td>
                            </tr>
                        <?php 
                        $i++;
                        endforeach;
						?>
							<tr>     
								<th colspan="5">Total </th>  
                                
                                <th><?php echo $hotelEditedCnt; ?></th>
                                <th><?php echo $MappingSubmittedCnt; ?></th>
                                <th><?php echo '0'; ?></th>
                                <th><?php echo '0'; ?></th>
                                <th><?php echo '0'; ?></th>                                                                
                            </tr>

<?php						
                    else:
                        echo '<tr><td colspan="5" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
     
            
            <?php }?>
            
        </div>
    </div>
</div>

<?php echo $this->Form->end(); 

$this->Js->get('#ReportSummaryType')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_user_list_by_summary_type'
                ), array(
            'update' => '#ReportUserId',
            'async' => true,
            'before' => 'loading("ReportUserId")',
            'complete' => 'loaded("ReportUserId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
?>