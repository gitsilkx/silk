<?php
$this->Html->addCrumb('My Fetch Area', 'javascript:void(0);', array('class' => 'breadcrumblast'));
echo $this->element('FetchAreas/top_menu');
?>


<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                    echo $this->Paginator->counter(array('format' => '{:count}'));
                    ?></span> My Fetch Area</h4>
          
            <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
        </div>
        <div class="panel panel-default">
<!--
            <div class="panel_controls hideform">

                <?php
                echo $this->Form->create('Project', array('controller' => 'projects', 'action' => 'index', 'class' => 'quick_search', 'id' => 'SearchForm', 'novalidate' => true, 'inputDefaults' => array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control',
                )));
                echo $this->Form->hidden('model_name', array('id' => 'model_name', 'value' => 'Project'));
                ?> 
                <div class="row spe-row">
                    <div class="col-sm-4 col-xs-8">

                        <?php echo $this->Form->input('global_search', array('value' => $global_search, 'placeholder' => 'Type project name', 'error' => array('class' => 'formerror'))); ?>
                    </div>
                    <div class="col-sm-3 col-xs-4">
                        <?php
                        echo $this->Form->submit('Project Search', array('div' => false, 'class' => 'btn btn-default btn-sm"'));
                        ?>

                    </div>
                    <div class="col-sm-5 col-xs-5">
                        <label for="un_member">Phase:</label>
                        <?php echo $this->Form->input('phase_id', array('value' => $phase_id, 'options' => $phase, 'empty' => '--Select--', 'onchange' => 'this.form.submit()', 'error' => array('class' => 'formerror'))); ?>
                    </div>
                </div>
                <div class="row" id="search_panel_controls">

                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">City:</label>
                        <?php echo $this->Form->input('city_id', array('id' => 'city_id', 'type' => 'select', 'options' => $cities, 'empty' => '--Select--', 'value' => $city_id)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Suburb:</label>
                        <?php echo $this->Form->input('suburb_id', array('type' => 'select', 'options' => $suburbs, 'empty' => '--Select--', 'value' => $suburb_id)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Area:</label>
                        <?php echo $this->Form->input('area_id', array('options' => $areas, 'empty' => '--Select--', 'value' => $area_id)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Builder:</label>
                        <?php echo $this->Form->input('builder_id', array('div' => array('id' => 'builder_id'), 'options' => $builders, 'empty' => '--Select--', 'value' => $builder_id)); ?>
                    </div>

                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Residential:</label>
                        <?php echo $this->Form->input('proj_residential', array('options' => array('1' => 'Yes', '2' => 'No'), 'empty' => '--Select--', 'value' => $proj_residential)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">High End:</label>
                        <?php echo $this->Form->input('proj_highendresidential', array('options' => array('1' => 'Yes', '2' => 'No'), 'empty' => '--Select--', 'value' => $proj_highendresidential)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Commercial:</label>
                        <?php echo $this->Form->input('proj_commercial', array('options' => array('1' => 'Yes', '2' => 'No'), 'empty' => '--Select--', 'value' => $proj_commercial)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Secondary Builder:</label>
                        <?php echo $this->Form->input('secondary_builder_id', array('options' => $builders, 'empty' => '--Select--', 'value' => $secondary_builder_id)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Marketing Status:</label>
                        <?php echo $this->Form->input('proj_marketing_status', array('options' => $marketing_status, 'empty' => '--Select--', 'value' => $proj_marketing_status)) ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Marketing Partner:</label>
                        <?php echo $this->Form->input('proj_marketing_partner', array('options' => $marketing_partners, 'empty' => '--Select--', 'value' => $proj_marketing_partner)); ?>
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
            -->
            <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="100">
                <thead>
                    <tr>
                        <th data-toggle="true">Id</th>
                        <th data-hide="phone">Supplier</th>
                        <th data-hide="phone">Date</th>
                        <th data-hide="phone">Fetched Type</th>
                        <th data-hide="phone">Fetched By</th>
                        <th data-hide="phone">Continent</th>
                        <th data-hide="phone">Country</th>
                        <th data-hide="phone">City</th>
                        <th data-hide="phone">Total Volume</th>
                        <th data-hide="phone">Inserted Volume</th>
                        <th data-hide="phone">Status</th>
                          
                    </tr>
                </thead>
                <tbody>
<?php

if (isset($TravelFetchTables) && count($TravelFetchTables) > 0):
    foreach ($TravelFetchTables as $TravelFetchTable):
        $id = $TravelFetchTable['TravelFetchTable']['id'];

        $type = $TravelFetchTable['TravelFetchTable']['type_id'];
        if($type == '1')
            $type = 'Hotel';
        elseif($type == '2')
            $type = 'Country';
        elseif($type == '3')
            $type = 'City';
        ?>
                    <tr>
                        <td><?php echo $id;?></td>
                        <td><?php echo $this->Custom->getSupplierName($TravelFetchTable['TravelFetchTable']['supplier_id']); ?></td>
                        <td><?php echo $TravelFetchTable['TravelFetchTable']['created']; ?></td>
                        <td><?php echo $type; ?></td>
                        <td><?php echo $this->Custom->Username($TravelFetchTable['TravelFetchTable']['user_id']); ?></td>
                        <td><?php echo $TravelFetchTable['TravelFetchTable']['continent_id']; ?></td>
                        <td><?php echo $this->Custom->getSupplierCountryName($TravelFetchTable['TravelFetchTable']['country_id']); ?></td>
                        <td><?php echo $this->Custom->getSupplierCityName($TravelFetchTable['TravelFetchTable']['city_id']);?></td>
                        <td><?php echo $TravelFetchTable['TravelFetchTable']['total_volume']; ?></td>
                        <td><?php echo $TravelFetchTable['TravelFetchTable']['inserted_volume']; ?></td>
                        <td><?php echo $TravelFetchTable['TravelFetchTable']['status']; ?></td>
                    </tr>
        <?php endforeach; ?>

                        <?php
                        echo $this->element('paginate');
                    else:
                        echo '<tr><td colspan="25" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
           


        </div>
    </div>
</div>
