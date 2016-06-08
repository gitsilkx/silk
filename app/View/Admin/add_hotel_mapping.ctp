<?php
$this->Html->addCrumb('Add Hotel Mapping', 'javascript:void(0);', array('class' => 'breadcrumblast'));
echo $this->Form->create('SupplierHotel', array('method' => 'post',
    'id' => 'parsley_reg',
    'novalidate' => true,
    'onsubmit' => 'return Validate()',
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class' => 'form-control',
    ),
));
echo $this->Form->hidden('supplier_hotel_id',array('value' => $SupplierHotels['SupplierHotel']['id'],'type' => 'text'));
echo $this->Form->hidden('hotel_id',array('value' => $TravelHotelLookups['TravelHotelLookup']['id'],'type' => 'text'));
?>
<div class="col-sm-12" id="mycl-det">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Hotel Mapping</h4>
        </div>
        <div class="panel-body">
           
            <div class="row">
                
                <div class="col-sm-12"  style="background-color: rgb(211, 233, 237);overflow:hidden;">
                    <div class="col-sm-6">
                        <h4>Supplier Hotel : <?php echo strtoupper($SupplierHotels['SupplierHotel']['hotel_name']);?></h4>
                        <div class="form-group">
                            <label for="reg_input_name" class="bgr">Id</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $SupplierHotels['SupplierHotel']['id'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Continent</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $SupplierHotels['SupplierHotel']['continent_name'];
                                ?>
                                </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Hotel</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $SupplierHotels['SupplierHotel']['hotel_name'];
                                ?>
                                </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                       <h4>&nbsp;</h4>
                       <div class="form-group">
                            <label for="reg_input_name" class="req">Code</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo '<b>'.$SupplierHotels['SupplierHotel']['hotel_code'].'</b>';
                                ?></div>
                        </div>
                       <div class="form-group">
                            <label for="reg_input_name" class="req">Country</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $SupplierHotels['SupplierHotel']['country_name'];
                                ?></div>
                        </div>
                  
                       <div class="form-group">
                            <label for="reg_input_name" class="req">City</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $SupplierHotels['SupplierHotel']['city_name'];
                                ?>
                                </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-sm-12"  style="background-color: rgb(238, 221, 255);overflow:hidden;">
                    <div class="col-sm-6">
                        <h4>WTB Hotel : <?php echo strtoupper($TravelHotelLookups['TravelHotelLookup']['hotel_name']);?></h4>
                        <div class="form-group">
                            <label for="reg_input_name" class="bgr">Id</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo '<b>'.$TravelHotelLookups['TravelHotelLookup']['id'].'</b>';
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Continent</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['continent_name'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Province</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['province_name'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Suburb</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['suburb_name'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Chain</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['chain_name'];
                                ?></div>
                        </div>
                        
                    </div>
                    <div class="col-sm-6">
                        <h4>&nbsp;</h4>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Code</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['hotel_code'];
                                ?></div>
                        </div>
                       <div class="form-group">
                            <label for="reg_input_name" class="req">Country</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['country_name'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">City</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['city_name'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Area</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['area_name'];
                                ?></div>
                        </div>
                        <div class="form-group">
                            <label for="reg_input_name" class="req">Brand</label>
                            <span class="colon">:</span>
                            <div class="col-sm-10">
                                <?php
                                echo $TravelHotelLookups['TravelHotelLookup']['brand_name'];
                                ?></div>
                        </div>
                        
                    </div>
                </div>   
                <div class="clear" style="clear: both;"></div>
                <div class="col-sm-12">
                    <div class="row">  
                        
                        <div class="col-sm-2">
                            <?php
                            echo $this->Form->submit('Add', array('class' => 'btn btn-success sticky_success','name' => 'add','style' => 'width:100%;float:left'));                            
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Form->end();
?>
<script>
    function Validate(){
        return false;
    }
    </script>
