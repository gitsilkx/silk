<?php $this->Html->addCrumb('WTB Error', 'javascript:void(0);', array('class' => 'breadcrumblast')); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                    echo $this->Paginator->counter(array('format' => '{:count}'));
                    ?></span>WTB Error</h4>
           
            <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
        </div>
        <div class="panel panel-default">
<!--
            <div class="panel_controls hideform">

                <?php
                echo $this->Form->create('TravelWtbError', array('controller' => 'travel_suburbs', 'action' => 'index', 'class' => 'quick_search', 'id' => 'SearchForm', 'novalidate' => true, 'inputDefaults' => array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control',
                )));
                ?> 

                <div class="row spe-row">
                    <div class="col-sm-4 col-xs-8">

                        <?php echo $this->Form->input('name', array('value' => $name, 'placeholder' => 'Type suburb name', 'error' => array('class' => 'formerror'))); ?>
                    </div>
                    <div class="col-sm-3 col-xs-4">
                        <?php
                        echo $this->Form->submit('Suburb Search', array('div' => false, 'class' => 'btn btn-default btn-sm"'));
                        ?>

                    </div>
                </div>
                <div class="row" id="search_panel_controls">
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Continent:</label>
                        <?php echo $this->Form->input('continent_id', array('options' => $TravelLookupContinents, 'empty' => '--Select--', 'value' => $continent_id)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Country:</label>
                        <?php echo $this->Form->input('country_id', array('options' => $TravelCountries, 'empty' => '--Select--', 'value' => $country_id)); ?>
                    </div>

                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">City:</label>
                        <?php echo $this->Form->input('city_id', array('options' => $TravelCities, 'empty' => '--Select--', 'value' => $city_id)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Top Neighborhood:</label>
                        <?php echo $this->Form->input('top_neighborhood', array('options' => array('TRUE' => 'TRUE', 'FALSE' => 'FALSE'), 'empty' => '--Select--', 'value' => $top_neighborhood)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Active:</label>
                        <?php echo $this->Form->input('active', array('options' => array('TRUE' => 'TRUE', 'FALSE' => 'FALSE'), 'empty' => '--Select--', 'value' => $active)); ?>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Status:</label>
                        <?php echo $this->Form->input('status', array('options' => array('1' => 'Active', '2' => 'Inactive'), 'empty' => '--Select--', 'value' => $status)); ?>
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
            </div> -->
            <table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="500">
                <thead>
                    <tr class="footable-group-row">
                        <th data-group="group1" colspan="9" class="nodis">Suburb Information</th>
                        
                    </tr>
                    <tr>
                        <th data-toggle="phone" data-sort-ignore="true" data-group="group1"><?php echo $this->Paginator->sort('id', 'Error Id'); echo ($sort == 'id') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>" : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-sort-ignore="true" data-group="group1"><?php echo $this->Paginator->sort('error_topic', 'Error Topic'); echo ($sort == 'error_topic') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>" : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1"><?php echo $this->Paginator->sort('error_by', 'Error By'); echo ($sort == 'error_by') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>" : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1"><?php echo $this->Paginator->sort('error_time', 'Error Time'); echo ($sort == 'error_time') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>" : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1"><?php echo $this->Paginator->sort('fixed_by', 'Fixed By'); echo ($sort == 'fixed_by') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>" : " <i class='icon-sort'></i>"; ?></th>           

                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Fixed Time</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Log Id</th>
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Status</th> 
                        <th data-hide="phone" data-sort-ignore="true" data-group="group1">Error Entity/th> 
                          

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($TravelWtbErrors) && count($TravelWtbErrors) > 0):
                        foreach ($TravelWtbErrors as $TravelWtbError):
                            $id = $TravelWtbError['TravelWtbError']['id'];
                           
                            ?>
                            <tr>
                               <td><?php echo $id; ?></td>
                                <td><?php echo $TravelWtbError['TravelLookupErrorTopic']['value']; ?></td>
                                <td><?php echo $TravelWtbError['TravelWtbError']['error_by']; ?></td>
                                <td><?php echo $TravelWtbError['TravelWtbError']['error_time']; ?></td>
                                <td><?php echo $TravelWtbError['TravelWtbError']['fixed_by']; ?></td>

                                <td><?php echo $TravelWtbError['TravelWtbError']['fixed_time']; ?></td>
                                <td><?php echo $TravelWtbError['TravelWtbError']['log_id']; ?></td>
                                
                                <td><?php echo $TravelWtbError['TravelWtbError']['error_status']; ?></td>

                                <td width="10%" valign="middle" align="center">

                                    <?php
                                    echo $this->Html->link('<span class="icon-list"></span>', array('controller' => 'travel_suburbs', 'action' => 'retry/' . $id), array('class' => 'act-ico','data-toggle' => 'tooltip', 'data-placement' => 'left', 'title' => 'Re-try Operation', 'escape' => false));
                                       ?>
                                </td>


                            </tr>
                        <?php endforeach; ?>

                        <?php
                        echo $this->element('paginate');
                    else:
                        echo '<tr><td colspan="11" class="norecords">No Records Found</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
            

        </div>
    </div>
</div>

