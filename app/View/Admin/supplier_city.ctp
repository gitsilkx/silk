<?php
$this->Html->addCrumb('My Supplier Cities', 'javascript:void(0);', array('class' => 'breadcrumblast'));
echo $this->element('FetchAreas/top_menu');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                    echo $this->Paginator->counter(array('format' => '{:count}'));
                    ?></span> My  Supplier Cities</h4>
          <span class="badge badge-circle add-client nomrgn"><i class="icon-plus"></i> <?php echo $this->Html->link('Add Supplier City', '/admin/add_city') ?></span>
            <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
        </div>
        <div class="panel panel-default">
            <table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="500">
                <thead>             
                    <tr>
                        <th data-toggle="true" data-sort-ignore="true" width="3%" data-group="group1"><?php echo $this->Paginator->sort('id', 'Id');
                echo ($sort == 'id') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-sort-ignore="true" width="10%" data-group="group1"><?php echo $this->Paginator->sort('name', 'Suppler City Name');
                echo ($sort == 'name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-group="group1" width="3%" data-sort-ignore="true"><?php echo $this->Paginator->sort('hotel_code', 'Supplier City Code');
                echo ($sort == 'code') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>                    
                        <th data-hide="phone" data-group="group1" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('brand_name', 'Supplier Country Code');
                echo ($sort == 'country_code') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                     <th data-hide="phone" data-sort-ignore="true">Action</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
	//pr($SupplierCities);
       // die;
                    $secondary_city = '';

                    if (isset($SupplierCities) && count($SupplierCities) > 0):
                        foreach ($SupplierCities as $SupplierCity):
                            $id = $SupplierCity['SupplierCity']['id'];

                     
                            ?>
                            <tr>
                                <td class="tablebody"><?php echo $id; ?></td>
                                <td class="tablebody"><?php echo $SupplierCity['SupplierCity']['name']; ?></td>               
                                <td class="tablebody"><?php echo $SupplierCity['SupplierCity']['code']; ?></td>                                                               
                                <td class="tablebody"><?php echo $SupplierCity['SupplierCity']['country_code']; ?></td>                                                   
                                <td width="10%" valign="middle" align="center"><?php
                                echo $this->Html->link('<span class="icon-list"></span>', array('controller' => 'admin', 'action' => 'city_mapping/' . $id), array('class' => 'act-ico','target' => '_blank', 'escape' => false));
                                ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        echo $this->element('paginate');
                    else:
                        echo '<tr><td colspan="4" class="norecords">No Records Found</td></tr>';

                    endif;
                    ?>
                </tbody>
            </table>     
            <span class="badge badge-circle add-client"><i class="icon-plus"></i> <?php echo $this->Html->link('Add Supplier City', '/admin/add_city') ?></span>


        </div>
    </div>
</div>
