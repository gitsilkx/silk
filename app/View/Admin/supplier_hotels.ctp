<?php
$this->Html->addCrumb('My Supplier Hotels', 'javascript:void(0);', array('class' => 'breadcrumblast'));
echo $this->element('FetchAreas/top_menu');
?>


<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                    echo $this->Paginator->counter(array('format' => '{:count}'));
                    ?></span> My Fetch Area</h4>
          <span class="badge badge-circle add-client nomrgn"><i class="icon-plus"></i> <?php echo $this->Html->link('Add Supplier Hotel', '/admin/add_hotel') ?></span>
            <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
        </div>
        <div class="panel panel-default">

            <table border="0" cellpadding="0" cellspacing="0" id="resp_table" class="table toggle-square myclitb" data-filter="#table_search" data-page-size="500">
                <thead>
                    <tr class="footable-group-row">
                        <th data-group="group1" colspan="5" class="nodis">Hotel Information</th>
                        <th data-group="group9" colspan="6">Hotel Location</th>
                        <th data-group="group10" colspan="3">Hotel Status</th>
                        <th data-group="group2" colspan="8">Hotel Information</th>
                        <th data-group="group3" colspan="11">Hotel Facilities</th>
                        <th data-group="group4" colspan="1">Room Facilities</th>
                        <!--<th data-group="group5" colspan="6">Hotel Ratings</th>-->
                        <th data-group="group6" colspan="5">Hotel Contacts</th>
                        <th data-group="group7" colspan="2">Other Information</th>
                        <th data-group="group8" class="nodis">Hotel Action</th>
                    </tr>
                    <tr>
                        <th data-toggle="true" data-sort-ignore="true" width="3%" data-group="group1"><?php echo $this->Paginator->sort('id', 'Hotel Id');
                echo ($sort == 'id') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-sort-ignore="true" width="10%" data-group="group1"><?php echo $this->Paginator->sort('hotel_name', 'Hotel Name');
                echo ($sort == 'hotel_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-toggle="phone" data-group="group1" width="3%" data-sort-ignore="true"><?php echo $this->Paginator->sort('hotel_code', 'WTB Hotel Code');
                echo ($sort == 'hotel_code') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>                    
                        <th data-hide="phone" data-group="group1" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('brand_name', 'Brand');
                echo ($sort == 'brand_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group1" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('chain_name', 'Chain');
                echo ($sort == 'chain_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('continent_name', 'Continent');
                echo ($sort == 'continent_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('country_name', 'Country');
                echo ($sort == 'country_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="10%" data-sort-ignore="true"><?php echo $this->Paginator->sort('province_name', 'Province');
                echo ($sort == 'province_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="8%" data-sort-ignore="true"><?php echo $this->Paginator->sort('city_name', 'City');
                echo ($sort == 'city_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="8%" data-sort-ignore="true"><?php echo $this->Paginator->sort('suburb_name', 'Suburb');
                echo ($sort == 'suburb_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>
                        <th data-hide="phone" data-group="group9" width="5%" data-sort-ignore="true"><?php echo $this->Paginator->sort('area_name', 'Area');
                echo ($sort == 'area_name') ? ($direction == 'asc') ? " <i class='icon-caret-up'></i>" : " <i class='icon-caret-down'></i>"  : " <i class='icon-sort'></i>"; ?></th>


                        <th data-hide="phone" data-group="group10" width="5%" data-sort-ignore="true">Silkrouters</th>
                        <th data-hide="phone" data-group="group10" width="2%" data-sort-ignore="true">WTB</th>
                        <th data-hide="phone" data-group="group10" width="5%" data-sort-ignore="true">Active?</th>
                      

                        <th data-hide="all" data-group="group2" data-sort-ignore="true">Logo</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">Logo1</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">Hotel_img1</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">Hotel_img2</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">Address</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">Hotel_Comment</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">IsSendPromo</th>
                        <th data-hide="all" data-group="group2" data-sort-ignore="true">PromoText</th>

                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Business_Center</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Meeting_Facilities</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Dining_Facilities</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Bar_Lounge</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Fitness_Center</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Pool</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Golf</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Tennis</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Kids</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Handicap</th>
                        <th data-hide="all" data-group="group3" data-sort-ignore="true">Hotel_Facility</th>

                        <th data-hide="all" data-group="group4" data-sort-ignore="true">Room_Detail</th>
<!--
                        <th data-hide="all" data-group="group5" data-sort-ignore="true">HotelRating</th>
                        <th data-hide="all" data-group="group5" data-sort-ignore="true">FoodRating</th>
                        <th data-hide="all" data-group="group5" data-sort-ignore="true">ServiceRating</th>
                        <th data-hide="all" data-group="group5" data-sort-ignore="true">LocationRating</th>
                        <th data-hide="all" data-group="group5" data-sort-ignore="true">ValueRating</th>
                        <th data-hide="all" data-group="group5" data-sort-ignore="true">OverallRating</th>-->

                        <th data-hide="all" data-group="group6" data-sort-ignore="true">ReservationEmail</th>
                        <th data-hide="all" data-group="group6" data-sort-ignore="true">ReservationContact</th>
                        <th data-hide="all" data-group="group6" data-sort-ignore="true">EmergencyContactName</th>
                        <th data-hide="all" data-group="group6" data-sort-ignore="true">ReservationDeskNumber</th>
                        <th data-hide="all" data-group="group6" data-sort-ignore="true">EmergencyContactNumber</th>

                        <th data-hide="all" data-group="group7" data-sort-ignore="true">No_Room</th>
                        <th data-hide="all" data-group="group7" data-sort-ignore="true">IsOffline</th>
                        <th data-group="group8" data-hide="phone" data-sort-ignore="true" width="7%">Action</th> 

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
	//pr($SupplierHotels);
                    $secondary_city = '';

                    if (isset($SupplierHotels) && count($SupplierHotels) > 0):
                        foreach ($SupplierHotels as $SupplierHotel):
                            $id = $SupplierHotel['SupplierHotel']['id'];

                            if ($SupplierHotel['SupplierHotel']['wtb_status'] == '1')
                                $wtb_status = 'OK';
                            else
                                $wtb_status = 'ERROR';
                            ?>
                            <tr>
                                <td class="tablebody"><?php echo $id; ?></td>
                                <td class="tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_name']; ?></td>               
                                <td class="tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_code']; ?></td>                                                               
                                <td class="tablebody"><?php echo $SupplierHotel['SupplierHotel']['brand_name']; ?></td>
                                <td class="tablebody"><?php echo $SupplierHotel['SupplierHotel']['chain_name']; ?></td>

                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['continent_name']; ?></td>                                 
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['country_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['province_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['city_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['suburb_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['area_name']; ?></td>

                                <td class="sub-tablebody"><?php echo $SupplierHotel['TravelSupplierStatus']['value']; ?></td>
                                <td class="sub-tablebody"><?php echo $wtb_status; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['active']; ?></td>   
                                


                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['logo']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['logo1']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_img1']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_img2']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['address']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_comment']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['is_send_promo']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['promo_text']; ?></td>


                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['business_center']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['meeting_facilities']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['dining_facilities']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['bar_lounge']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['fitness_center']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['pool']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['golf']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['tennis']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['kids']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['handicap']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_facility']; ?></td>

                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['room_detail']; ?></td>

                                <!--
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['hotel_rating']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['food_rating']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['service_rating']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['location_rating']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['value_rating']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['overall_rating']; ?></td>-->

                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['reservation_email']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['reservation_contact']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['emergency_contact_name']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['reservation_desk_number']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['emergency_contact_number']; ?></td>

                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['no_room']; ?></td>
                                <td class="sub-tablebody"><?php echo $SupplierHotel['SupplierHotel']['is_offline']; ?></td>
                                <td valign="middle" align="center">

                                    <?php
                                    if($SupplierHotel['SupplierHotel']['status'] == '1' || $SupplierHotel['SupplierHotel']['status'] == '5')
                                        echo $this->Html->link('<span class="icon-list"></span>', array('controller' => 'admin', 'action' => 'hotel_mapping/' . $id), array('class' => 'act-ico','target' => '_blank', 'escape' => false));
                                    //elseif($SupplierHotel['SupplierHotel']['status'] == '4')
                                        //echo $this->Html->link('<span class="icon-list"></span>', array('controller' => 'admin', 'action' => 'hotel_add/' . $id), array('class' => 'act-ico','target' => '_blank', 'escape' => false));
                                    //echo $this->Html->link('<span class="icon-pencil"></span>', array('controller' => 'travel_hotel_lookups', 'action' => 'hotel_edit/' . $id,), array('class' => 'act-ico', 'escape' => false));
                                    //echo $this->Html->link('<span class="icon-remove"></span>', array('controller' => 'travel_hotel_lookups', 'action' => 'delete', $id), array('class' => 'act-ico', 'escape' => false), "Are you sure you wish to delete this hotel?");
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
            <span class="badge badge-circle add-client"><i class="icon-plus"></i> <?php echo $this->Html->link('Add Supplier Hotel', '/admin/add_hotel') ?></span>


        </div>
    </div>
</div>
