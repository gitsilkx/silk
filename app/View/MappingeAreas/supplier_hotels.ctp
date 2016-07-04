<?php
$this->Html->addCrumb('My Supplier Hotels', 'javascript:void(0);', array('class' => 'breadcrumblast'));
//echo $this->element('FetchAreas/top_menu');
?>


<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                    echo $this->Paginator->counter(array('format' => '{:count}'));
                    ?></span> My Fetch Area</h4>
          
           
        </div>
        <div class="panel panel-default">
            <div class="panel_controls">

                <?php
                echo $this->Form->create('TravelHotelLookup', array('class' => 'quick_search', 'id' => 'parsley_reg', 'novalidate' => true, 'inputDefaults' => array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control',
                )));
                ?> 
                <div class="row spe-row">
                    <div class="col-sm-4 col-xs-8">
                        <?php echo $this->Form->input('hotel_name', array('value' => $hotel_name, 'placeholder' => 'Please type hotel name', 'error' => array('class' => 'formerror'))); ?>
                    </div>                 
                </div>
                <div class="row">
                    
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Supplier:</label>
                        <?php echo $this->Form->input('supplier_id', array('options' => $TravelSuppliers, 'empty' => '--Select--', 'value' => $supplier_id, 'data-required' => 'true')); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Continent:</label>
                        <?php echo $this->Form->input('continent_id', array('options' => $TravelLookupContinents, 'empty' => '--Select--', 'value' => $continent_id, 'data-required' => 'true')); ?>
                    </div>                    
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Country:</label>
                        <?php echo $this->Form->input('country_id', array('options' => $TravelCountries, 'empty' => '--Select--', 'value' => $country_id, 'data-required' => 'true')); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Province:</label>
                        <?php echo $this->Form->input('province_id', array('options' => $Provinces, 'empty' => '--Select--', 'value' => $province_id, 'data-required' => 'true')); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">City:</label>
                        <?php echo $this->Form->input('city_id', array('options' => $TravelCities, 'empty' => '--Select--', 'value' => $city_id, 'data-required' => 'true')); ?>
                    </div>
                    




                    <div class="col-sm-3 col-xs-6">
                        <label>&nbsp;</label>
                        <?php
                        echo $this->Form->submit('Filter', array('div' => false, 'class' => 'btn btn-default btn-sm"'));
// echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-default btn-sm"'));
                        ?>

                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <?php if($display == 'TRUE'){?>
            <table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="500">
                <thead>
                  
                    <tr>
                        <th data-toggle="true" data-sort-ignore="true" width="3%" data-group="group1"><?php echo $this->Paginator->sort('id', 'Hotel Id');
                echo ($sort == 'id') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-sort-ignore="true" width="15%" data-group="group1"><?php echo $this->Paginator->sort('hotel_name', 'Hotel Name');
                echo ($sort == 'hotel_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-group="group1" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('hotel_code', 'Sup. Hotel Code');
                echo ($sort == 'hotel_code') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>                    
                       
                        <th data-hide="phone" data-group="group9" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('country_name', 'Sup. Country');
                echo ($sort == 'country_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('country_code', 'Sup. Country Code');
                echo ($sort == 'country_code') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        
                        <th data-hide="phone" data-group="group9" width="8%" data-sort-ignore="true"><?php echo $this->Paginator->sort('city_name', 'Sup. City');
                echo ($sort == 'city_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                 <th data-hide="phone" data-group="group9" width="8%" data-sort-ignore="true"><?php echo $this->Paginator->sort('city_code', 'Sup. City Code');
                echo ($sort == 'city_code') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        


                        <th data-hide="phone" data-group="group10" width="5%" data-sort-ignore="true">Status</th>
                        <th data-hide="phone" data-group="group10" width="5%" data-sort-ignore="true">No. Of Mapping</th>
                   
                        <th data-group="group8" data-hide="phone" data-sort-ignore="true" width="7%">Action</th> 

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
	//pr($SupplierHotels);
                    $secondary_city = '';
                    $city_code = '';

                    if (isset($SupplierHotels) && count($SupplierHotels) > 0):
                        foreach ($SupplierHotels as $SupplierHotel):
                            $id = $SupplierHotel['SupplierHotel']['id'];
                    
                    if($city_code <> $SupplierHotel['SupplierHotel']['city_code'])
                           echo '<h4> Supplier City : '.$SupplierHotel['SupplierHotel']['city_code'].' - '.$SupplierHotel['SupplierHotel']['city_name'].'</h4>';
                           $city_code = $SupplierHotel['SupplierHotel']['city_code'];
                            ?>
                
                            <tr>
                                <td class="tablebody"><?php echo $id; ?></td>
                                <td class="tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_name']; ?></td>               
                                <td class="tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_code']; ?></td>                                                               
                               
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['country_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['country_code']; ?></td>
                              
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['city_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['city_code']; ?></td>
                                
                                <td class="sub-tablebody"><?php echo $SupplierHotel['TravelSupplierStatus']['value']; ?></td>
                                <td class="tablebody"><?php echo count($SupplierHotel['TravelHotelRoomSupplier']);?></td>

                                <td valign="middle" align="center">

                                    <?php
                                    if($this->Session->read('role_id') <> '64' || $this->Session->read('role_id') <> '61') {
                                    if($SupplierHotel['SupplierHotel']['status'] == '1' || $SupplierHotel['SupplierHotel']['status'] == '5')
                                        echo $this->Html->link('<span class="icon-list"></span>', array('controller' => 'admin', 'action' => 'hotel_mapping/' . $id), array('class' => 'act-ico','target' => '_blank', 'escape' => false));
                                    }
                                    ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>

                        <?php
                        echo $this->element('paginate');
                    else:
                        echo '<tr><td colspan="43" class="norecords">No Records Found</td></tr>';

                    endif;
                    ?>
                </tbody>
            </table>  
            <?PHP }?>
            


        </div>
    </div>
</div>

<?php

/*
 * Get sates by country code
 */
$data = $this->Js->get('#parsley_reg')->serializeForm(array('isForm' => true, 'inline' => true));

$this->Js->get('#TravelHotelLookupContinentId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_travel_country_by_continent_id/TravelHotelLookup/continent_id'
                ), array(
            'update' => '#TravelHotelLookupCountryId',
            'async' => true,
            'before' => 'loading("TravelHotelLookupCountryId")',
            'complete' => 'loaded("TravelHotelLookupCountryId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $this->Js->serializeForm(array(
                'isForm' => true,
                'inline' => true
            ))
        ))
);
/*
 * Get sates by country code
 */
$this->Js->get('#TravelHotelLookupCountryId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_province_by_continent_country/TravelHotelLookup/continent_id/country_id'
                ), array(
            'update' => '#TravelHotelLookupProvinceId',
            'async' => true,
            'before' => 'loading("TravelHotelLookupProvinceId")',
            'complete' => 'loaded("TravelHotelLookupProvinceId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $data
        ))
);

$this->Js->get('#TravelHotelLookupProvinceId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_travel_city_by_province/TravelHotelLookup/province_id'
                ), array(
            'update' => '#TravelHotelLookupCityId',
            'async' => true,
            'before' => 'loading("TravelHotelLookupCityId")',
            'complete' => 'loaded("TravelHotelLookupCityId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $data
        ))
);

$this->Js->get('#TravelHotelLookupCityId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_travel_suburb_by_country_id_and_city_id/TravelHotelLookup/country_id/city_id'
                ), array(
            'update' => '#TravelHotelLookupSuburbId',
            'async' => true,
            'before' => 'loading("TravelHotelLookupSuburbId")',
            'complete' => 'loaded("TravelHotelLookupSuburbId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $data
        ))
);

$this->Js->get('#TravelHotelLookupCityId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_supplier_city_code/TravelHotelLookup'
                ), array(
            'update' => '#TravelHotelLookupSupplierCityCode',
            'async' => true,
            'before' => 'loading("TravelHotelLookupSupplierCityCode")',
            'complete' => 'loaded("TravelHotelLookupSupplierCityCode")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $data
        ))
);
?>