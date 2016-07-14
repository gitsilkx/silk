<?php $this->Html->addCrumb('My Summary', 'javascript:void(0);', array('class' => 'breadcrumblast')); 

                echo $this->Form->create('Report', array('controller' => 'reports', 'action' => 'job_report','class' => 'quick_search', 'id' => 'SearchForm', 'novalidate' => true, 'inputDefaults' => array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control',
                )));
                ?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"> My Summary</h4>
            <span class="badge badge-circle add-client nomrgn"><i class="icon-plus"></i> <?php echo $this->Html->link('Add City', '/travel_cities/add') ?></span>
            <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
        </div>
        <div class="panel panel-default">
            <div class="panel_controls hideform">
                 
          
                <div class="row">
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Summary Type:</label>
                        <?php echo $this->Form->input('summary_type', array('options' => array('1' => 'Operation' , '2' => 'Application'), 'empty' => '--Select--')); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Choose Person:</label>
                        <?php echo $this->Form->input('user_id', array('options' => $persons, 'empty' => '--Select--')); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Supplier:</label>
                        <?php echo $this->Form->input('supplier_id', array('options' => array(), 'empty' => '--Select--')); ?>
                    </div>              
                    <div class="col-sm-3 col-xs-6">
                        <label>&nbsp;</label>
                        <?php
                        echo $this->Form->submit('Filter', array('div' => false, 'class' => 'btn btn-default btn-sm"'));
                        ?>
                    </div>
                </div>
                
            </div>
            <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="1000">
                <thead>
                   
                    <tr>           
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Sl. No.</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Person</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Country/th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Province</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">City</th>                
                                             
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    //pr($TravelCities);
                    //die;
                    if (isset($TravelCities) && count($TravelCities) > 0):
                        foreach ($TravelCities as $TravelCity):
                            $id = $TravelCity['TravelCity']['id'];              
                           
                            ?>
                            <tr>                              
                                <td><?php echo $i; ?></td>
                                <td><?php //echo $TravelCity['TravelCity']['city_name']; ?></td>
                                <td><?php echo $TravelCity['country_id']; ?></td>
                                <td><?php echo $TravelCity['province_id']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_name']; ?></td>                               

                            </tr>
                        <?php endforeach; 
                    else:
                        echo '<tr><td colspan="5" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
            
            <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="1000" style="display: none">
                <thead>
                     <tr class="footable-group-row">
                        <th data-group="group1" colspan="7" class="nodis">Edit</th>                     
                       
                    </tr>
                    <tr>           
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Unallocated</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Pending</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Submitted/th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Approved</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">Total</th>                
                                             
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    if (isset($TravelCities) && count($TravelCities) > 0):
                        foreach ($TravelCities as $TravelCity):
                            $id = $TravelCity['TravelCity']['id'];
                
                           
                            ?>
                            <tr>                              
                                <td><?php echo $id; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_name']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_code']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['continent_name']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['country_name']; ?></td>                               

                            </tr>
                        <?php endforeach; 
                    else:
                        echo '<tr><td colspan="5" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
            <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="1000" style="display: none">
                <thead>
                     <tr class="footable-group-row">
                        <th data-group="group1" colspan="7" class="nodis">Mapping</th>                     
                       
                    </tr>
                    <tr>           
                      
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Pending</th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Submitted/th>
                        <th data-toggle="phone"  data-sort-ignore="true" data-group="group1">Approved</th>
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">Total</th>                
                        <th data-hide="phone"  data-sort-ignore="true" data-group="group1">Supplier Total</th>                      
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    if (isset($TravelCities) && count($TravelCities) > 0):
                        foreach ($TravelCities as $TravelCity):
                            $id = $TravelCity['TravelCity']['id'];               
                           
                            ?>
                            <tr>                              
                                <td><?php echo $id; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_name']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['city_code']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['continent_name']; ?></td>
                                <td><?php echo $TravelCity['TravelCity']['country_name']; ?></td>                               

                            </tr>
                        <?php endforeach; 
                    else:
                        echo '<tr><td colspan="5" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>