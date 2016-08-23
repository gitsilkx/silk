<?php $this->Html->addCrumb('My Summary', 'javascript:void(0);', array('class' => 'breadcrumblast')); 

                echo $this->Form->create('Report', array('controller' => 'reports', 'action' => 'job_report','class' => 'quick_search', 'id' => 'parsley_reg', 'novalidate' => true, 'inputDefaults' => array(
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
                    </div>                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Choose Person:</label>
                        <?php echo $this->Form->input('user_id', array('options' => $persons, 'empty' => '--Select--', 'data-required' => 'true')); ?>
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
                        <th data-group="group1" colspan="5">Edit</th>                     
                        <th data-group="group2" colspan="4">Mapping</th>
                        <th data-group="group4" colspan="3"><?php echo $this->Custom->getSupplierCode($this->data['Report']['supplier_id']); ?></th>
                    </tr>
                    <tr>           
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Sl. No.</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Person</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Country</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">Province</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group3">City</th>    
                        
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Unallocated</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Pending</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Submitted</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Approved</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">Total</th>                
                        
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group2">Pending</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group2">Submitted</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group2">Approved</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group2">Total</th>                
                        
                        
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group4">Mapp Completed</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group4">Map Submitted</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group4">Total</th>
                                             
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    //pr($this->data);
                   // die;
                    $supplier_id = $this->data['Report']['supplier_id'];
                    if (isset($TravelCities) && count($TravelCities) > 0):
                        foreach ($TravelCities as $TravelCity):
                            $id = $TravelCity['TravelCity']['id'];              
                             $country_id = $TravelCity[0]['country_id'];
                            ?>
                            <tr>                              
                                <td><?php echo $i; ?></td>
                                <td><?php echo $this->Custom->Username($this->data['Report']['user_id']); ?></td>
                                <td><?php echo $this->Custom->getCountryName($country_id); ?></td>
                                <td><?php echo $this->Custom->getProvinceName($TravelCity[0]['province_id']); ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_name']; ?></td> 
                                
                                <td><?php echo $this->Custom->getHotelUnallocatedCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getHotePendingCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getHoteSubmittedCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getHoteApprovedCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getHoteTotalCnt($country_id,$id); ?></td>                               
                                
                                <td><?php echo $this->Custom->getMappingPendingCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getMappingSubmitCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getMappingApproveCnt($country_id,$id); ?></td>
                                <td><?php echo $this->Custom->getHoteApprovedCnt($country_id,$id);?></td>
                                
                                
                                <td><?php echo $this->Custom->getSupplierHotelCompeleteCnt($country_id,$id,$supplier_id); ?></td>
                                <td><?php echo $this->Custom->getSupplierHotelSubmitCnt($country_id,$id,$supplier_id); ?></td>
                                <td><?php echo $this->Custom->getSupplierHotelTotalCnt($country_id,$id,$supplier_id); ?></td>

                            </tr>
                        <?php 
                        $i++;
                        endforeach; 
                    else:
                        echo '<tr><td colspan="5" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
     
            
            <?php }?>
            <!--
            <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="1000" style="width:50%;float: left;">
                <thead>
                     <tr class="footable-group-row">
                        <th data-group="group1" colspan="7" class="nodis">Mapping</th>                     
                       
                    </tr>
                    <tr>           
                      
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Pending</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Submitted</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Approved</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">Total</th>                
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">Supplier Total</th>                      
                    </tr>
                </thead>
                <tbody>
                    
                            <tr>                              
                                <td><?php //echo $hotel_unallocated_cnt; ?></td>
                                <td><?php //echo $hotel_pending_cnt; ?></td>
                                <td><?php //echo $hotel_submitted_cnt; ?></td>
                                <td><?php //echo $hotel_approved_cnt; ?></td>
                                <td><?php //echo $hotel_total_cnt; ?></td>                               

                            </tr>
                        
                </tbody>
            </table>
                    -->
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>