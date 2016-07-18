<?php
echo $this->Html->css(array('/bootstrap/css/bootstrap.min', 'popup',
    'font-awesome/css/font-awesome.min',
    '/js/lib/datepicker/css/datepicker',
    '/js/lib/timepicker/css/bootstrap-timepicker.min'
        )
);
echo $this->Html->script(array('jquery.min', 'lib/chained/jquery.chained.remote.min', 'lib/jquery.inputmask/jquery.inputmask.bundle.min', 'lib/parsley/parsley.min', 'pages/ebro_form_validate', 'lib/datepicker/js/bootstrap-datepicker', 'lib/timepicker/js/bootstrap-timepicker.min', 'pages/ebro_form_extended'));
/* End */
//pr($this->data);
?>

<!----------------------------start add project block------------------------------>

<div class="pop-outer">
    <div class="pop-up-hdng">Mapping Information</div>
    <div class="col-sm-12">
        <?php if (count($TravelHotelRoomSuppliers) > 0) { ?>
             <table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="500">
                <thead>
                    <tr class="footable-group-row">
                        <th data-group="group1" colspan="5" class="nodis">Information</th>
                        <th data-group="group2" colspan="3">WTB</th>
                        <th data-group="group3" colspan="3">SUPPLIER</th>
                        
                    </tr>
                    <tr>                        
                        <th data-toggle="phone"  data-group="group1">Supplier Code</th>                        
                        <th data-hide="phone"  data-group="group1">Mapping Status</th>                                                              
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Mapping Active?</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Mapping Excluded?</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Mapping Name</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group2">Hotel</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group2">Country</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group2">City</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group3">Hotel</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group3">Country</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group3">City</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    //pr($TravelCountrySuppliers);
                    if (isset($TravelHotelRoomSuppliers) && count($TravelHotelRoomSuppliers) > 0):
                        foreach ($TravelHotelRoomSuppliers as $TravelHotelRoomSupplier):
                            $id = $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['id'];
                            $status = $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['hotel_supplier_status'];
                            if ($status == '1')
                                $status_txt = 'Submitted For Approval';
                            elseif ($status == '2')
                                $status_txt = 'Approved';
                            elseif ($status == '3')
                                $status_txt = 'Returned';
                            elseif ($status == '4')
                                $status_txt = 'Change Submitted';
                            elseif ($status == '5')
                                $status_txt = 'Rejection';
                            elseif ($status == '6')
                                $status_txt = 'Request For Allocation';
                            else
                                $status_txt = 'Allocation';
                            ?>
                            <tr>

                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['supplier_code']; ?></td>
                                <td><?php echo $status_txt; ?></td>
                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['active']; ?></td>                                                          

                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['excluded']; ?></td>
                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['hotel_mapping_name']; ?></td>
                                <td><?php echo $this->Html->link($TravelHotelRoomSupplier['TravelHotelRoomSupplier']['hotel_code'], array('controller' => 'reports', 'action' => 'hotel_summary/id:'.$TravelHotelRoomSupplier['TravelHotelRoomSupplier']['hotel_id']), array('class' => 'act-ico', 'escape' => false,'target' => '_blank')); ?></td>
                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['hotel_country_code']; ?></td>
                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['hotel_city_code']; ?></td>
                                <td><?php echo $this->Html->link($TravelHotelRoomSupplier['TravelHotelRoomSupplier']['supplier_item_code1'], array('controller' => 'admin', 'action' => 'supplier_hotels/hotel_code:'.$TravelHotelRoomSupplier['TravelHotelRoomSupplier']['supplier_item_code1']), array('class' => 'act-ico', 'escape' => false,'target' => '_blank')); ?></td>
                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['supplier_item_code4']; ?></td>
                                <td><?php echo $TravelHotelRoomSupplier['TravelHotelRoomSupplier']['supplier_item_code3']; ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                    else:
                        echo '<tr><td colspan="7" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

</div>	

