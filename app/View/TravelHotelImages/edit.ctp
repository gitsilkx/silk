<?php
$this->Html->addCrumb('Edit Hotel', 'javascript:void(0);', array('class' => 'breadcrumblast'));

?>

<div class="col-sm-12" id="mycl-det">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Edit Information</h4>
        </div>
        <div class="panel-body">
            <fieldset>
                <legend><span>Edit Hotel</span></legend>
            </fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo $this->Form->create('TravelHotelLookup', array('enctype' => 'multipart/form-data', 'method' => 'post', 'id' => 'wizard_a', 'novalidate' => true,
                        'inputDefaults' => array(
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                        )
                    ));
                    echo $this->Form->hidden('hotel_img1', array('value' => $this->data['TravelHotelLookup']['hotel_img1']));
                    echo $this->Form->hidden('hotel_img2', array('value' => $this->data['TravelHotelLookup']['hotel_img2']));
                    echo $this->Form->hidden('hotel_img3', array('value' => $this->data['TravelHotelLookup']['hotel_img3']));
                    echo $this->Form->hidden('hotel_img4', array('value' => $this->data['TravelHotelLookup']['hotel_img4']));
                    echo $this->Form->hidden('hotel_img5', array('value' => $this->data['TravelHotelLookup']['hotel_img5']));
                    echo $this->Form->hidden('hotel_img6', array('value' => $this->data['TravelHotelLookup']['hotel_img6']));

                    //echo $this->Form->hidden('continent_name');
                    //echo $this->Form->hidden('continent_code');
                    //echo $this->Form->hidden('country_code');
                    //echo $this->Form->hidden('country_name');
                    echo $this->Form->hidden('city_name');
                    echo $this->Form->hidden('suburb_name');
                    echo $this->Form->hidden('area_name');
                    echo $this->Form->hidden('chain_name');
                    echo $this->Form->hidden('brand_name');
                    echo $this->Form->hidden('city_code');
                    //echo $this->Form->hidden('province_name');
                    ?>

                    <h4>Primary Information</h4>
                    <fieldset class="nopdng">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <h4>Hotel Basic Information</h4>
                                    <div class="form-group">
                                        <label for="input_name" class="req">Hotel Name</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('hotel_name', array('data-required' => 'true'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Continent</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('continent_id', array('options' => $TravelLookupContinents, 'empty' => '--Select--', 'data-required' => 'true','disabled'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Province</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('province_id', array('options' => $Provinces, 'empty' => '--Select--', 'data-required' => 'true','disabled'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Suburb</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('suburb_id', array('options' => $TravelSuburbs, 'empty' => '--Select--', 'data-required' => 'true'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Chain</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('chain_id', array('options' => $TravelChains, 'empty' => '--Select--', 'data-required' => 'true'));
                                            ?></div>
                                    </div>                                                                                                                            
                                

                                </div>




                                <div class="col-sm-6">
                                    <h4>&nbsp;</h4>
                                    <div class="form-group">
                                        <label for="input_name" class="req">Hotel Code</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('hotel_code', array('readonly' => true));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Country</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('country_id', array('options' => $TravelCountries, 'empty' => '--Select--', 'data-required' => 'true','disabled'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">City</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('city_id', array('options' => $TravelCities, 'empty' => '--Select--', 'data-required' => 'true'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Area</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('area_id', array('options' => $TravelAreas, 'empty' => '--Select--', 'data-required' => 'true'));
                                            ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_input_name" class="req">Brand</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">
                                            <?php
                                            echo $this->Form->input('brand_id', array('options' => $TravelBrands, 'empty' => '--Select--', 'data-required' => 'true'));
                                            ?></div>
                                    </div>
                                   
                                    <div class="form-group int-sm">
                                        <label>Location</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10">  <?php
                                            echo $this->Form->input('gps_prm_1', array('class' => 'form-control decimal','placeholder' => 'GPS Parameter 1','style' => 'width:47%'));
                                            echo $this->Form->input('gps_prm_2', array('class' => 'form-control decimal','placeholder' => 'GPS Parameter 2','style' => 'width:47%'));
                                            ?></div>
                                    </div>                                 
                                                                                                

                                </div>
                                <br class="spacer" />
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10 editable txtbox">
                                            <?php
                                            echo $this->Form->input('address', array('type' => 'textarea'));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <span class="colon">:</span>
                                        <div class="col-sm-10 editable txtbox">
                                            <?php
                                            echo $this->Form->input('hotel_comment', array('type' => 'textarea','style' => 'width:100%;height:100px'));
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <div style="clear: both;"></div>
                                <h4>Hotel Pictures</h4>       


                                <div class="row" id="fr-select">
                                    <div class="col-sm-6 uploadfile">
                                        <div class="form-group">
                                            <div class="col-sm-10 editable txtbox">
                                                <label>Picture1</label>
                                                <span class="colon">:</span>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new img-thumbnail" style="width: 178px; height: 120px;">
                                                        <?php
                                                        if ($this->data['TravelHotelLookup']['hotel_img1']) {
                                                            $imagePath = $this->webroot . 'uploads/hotels';
                                                            $image1 = $imagePath . '/' . $this->data['TravelHotelLookup']['hotel_img1'];
                                                        } else {
                                                            $image1 = $this->webroot . "img/no_img_180.png";
                                                        }
                                                        ?>
                                                        <img src="<?php echo $image1; ?>" height="200" width="170" />

                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 178px; height: 120px"></div>
                                                    <div>
                                                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
                                                            <input type="file" name="data[TravelHotelLookup][image1]" data-require="true" />

                                                        </span>
                                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 editable txtbox">
                                                <label>Picture3</label>
                                                <span class="colon">:</span>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new img-thumbnail" style="width: 178px; height: 120px;">
                                                        <?php
                                                        if ($this->data['TravelHotelLookup']['hotel_img3']) {
                                                            $imagePath = $this->webroot . 'uploads/hotels';
                                                            $image3 = $imagePath . '/' . $this->data['TravelHotelLookup']['hotel_img3'];
                                                        } else {
                                                            $image3 = $this->webroot . "img/no_img_180.png";
                                                        }
                                                        ?>
                                                        <img src="<?php echo $image3; ?>" height="200" width="170" />

                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 178px; height: 120px"></div>
                                                    <div>
                                                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
                                                            <input type="file" name="data[TravelHotelLookup][image3]" />

                                                        </span>
                                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 editable txtbox">
                                                <label>Picture5</label>
                                                <span class="colon">:</span>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new img-thumbnail" style="width: 178px; height: 120px;">
                                                        <?php
                                                        if ($this->data['TravelHotelLookup']['hotel_img5']) {
                                                            $imagePath = $this->webroot . 'uploads/hotels';
                                                            $image5 = $imagePath . '/' . $this->data['TravelHotelLookup']['hotel_img5'];
                                                        } else {
                                                            $image5 = $this->webroot . "img/no_img_180.png";
                                                        }
                                                        ?>
                                                        <img src="<?php echo $image5; ?>" height="200" width="170" />

                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 178px; height: 120px"></div>
                                                    <div>
                                                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
                                                            <input type="file" name="data[TravelHotelLookup][image5]" />

                                                        </span>
                                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        


                                    </div>

                                    <div class="col-sm-6 uploadfile">
                                        <div class="form-group">
                                            <div class="col-sm-10 editable txtbox">
                                                <label>Picture2</label>
                                                <span class="colon">:</span>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new img-thumbnail" style="width: 178px; height: 120px;">

<?php
if ($this->data['TravelHotelLookup']['hotel_img2']) {
    $imagePath = $this->webroot . 'uploads/hotels';
    $image2 = $imagePath . '/' . $this->data['TravelHotelLookup']['hotel_img2'];
} else {
    $image2 = $this->webroot . "img/no_img_180.png";
}
?>
                                                        <img src="<?php echo $image2; ?>" height="200" width="170" /></div>
                                                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 178px; height: 120px">


                                                    </div>
                                                    <div>
                                                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
                                                            <input type="file" name="data[TravelHotelLookup][image2]" />

                                                        </span>
                                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 editable txtbox">
                                                <label>Picture4</label>
                                                <span class="colon">:</span>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new img-thumbnail" style="width: 178px; height: 120px;">
                                                        <?php
                                                        if ($this->data['TravelHotelLookup']['hotel_img4']) {
                                                            $imagePath = $this->webroot . 'uploads/hotels';
                                                            $image4 = $imagePath . '/' . $this->data['TravelHotelLookup']['hotel_img4'];
                                                        } else {
                                                            $image4 = $this->webroot . "img/no_img_180.png";
                                                        }
                                                        ?>
                                                        <img src="<?php echo $image4; ?>" height="200" width="170" />

                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 178px; height: 120px"></div>
                                                    <div>
                                                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
                                                            <input type="file" name="data[TravelHotelLookup][image4]" />

                                                        </span>
                                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 editable txtbox">
                                                <label>Picture6</label>
                                                <span class="colon">:</span>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="fileupload-new img-thumbnail" style="width: 178px; height: 120px;">
                                                        <?php
                                                        if ($this->data['TravelHotelLookup']['hotel_img6']) {
                                                            $imagePath = $this->webroot . 'uploads/hotels';
                                                            $image6 = $imagePath . '/' . $this->data['TravelHotelLookup']['hotel_img6'];
                                                        } else {
                                                            $image6 = $this->webroot . "img/no_img_180.png";
                                                        }
                                                        ?>
                                                        <img src="<?php echo $image6; ?>" height="200" width="170" />

                                                    </div>
                                                    <div class="fileupload-preview fileupload-exists img-thumbnail" style="width: 178px; height: 120px"></div>
                                                    <div>
                                                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
                                                            <input type="file" name="data[TravelHotelLookup][image6]" />

                                                        </span>
                                                        <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                     

                                    </div>
                                   
                                   

                                </div>

                                </div>
                            </div>
                        </div>
                    </fieldset>

                    
<?php echo $this->Form->end(); ?>
                </div>
            </div>

        </div>
    </div>
</div>


<?php
$data = $this->Js->get('#wizard_a')->serializeForm(array('isForm' => true, 'inline' => true));

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

$this->Js->get('#TravelHotelLookupSuburbId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_travel_area_by_suburb_id/TravelHotelLookup/suburb_id'
                ), array(
            'update' => '#TravelHotelLookupAreaId',
            'async' => true,
            'before' => 'loading("TravelHotelLookupAreaId")',
            'complete' => 'loaded("TravelHotelLookupAreaId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $data
        ))
);

$this->Js->get('#TravelHotelLookupChainId')->event('change', $this->Js->request(array(
            'controller' => 'all_functions',
            'action' => 'get_travel_brand_by_chain_id/TravelHotelLookup/chain_id'
                ), array(
            'update' => '#TravelHotelLookupBrandId',
            'async' => true,
            'before' => 'loading("TravelHotelLookupBrandId")',
            'complete' => 'loaded("TravelHotelLookupBrandId")',
            'method' => 'post',
            'dataExpression' => true,
            'data' => $data
        ))
);

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
?>
<script>
    $(document).ready(function() {
        $('#TravelHotelLookupContinentId').change(function() {
            $('#TravelHotelLookupContinentName').val($('#TravelHotelLookupContinentId option:selected').text());
        });
        
        $('#TravelHotelLookupCountryId').change(function() {
            $('#TravelHotelLookupCountryName').val($('#TravelHotelLookupCountryId option:selected').text());
        });
          
        $('#TravelHotelLookupSuburbId').change(function() {
            $('#TravelHotelLookupSuburbName').val($('#TravelHotelLookupSuburbId option:selected').text());
        });
        
        $('#TravelHotelLookupAreaId').change(function() {
            $('#TravelHotelLookupAreaName').val($('#TravelHotelLookupAreaId option:selected').text());
        });
        
        $('#TravelHotelLookupChainId').change(function() {
            $('#TravelHotelLookupChainName').val($('#TravelHotelLookupChainId option:selected').text());
        });
        
        $('#TravelHotelLookupBrandId').change(function() {
            $('#TravelHotelLookupBrandName').val($('#TravelHotelLookupBrandId option:selected').text());
        });
        
        $('#TravelHotelLookupProvinceId').change(function() {
            $('#TravelHotelLookupProvinceName').val($('#TravelHotelLookupProvinceId option:selected').text());
        });
        
        $('.decimal').change(function(event) {        
        this.value = parseFloat(this.value).toFixed(7);
       
    });
    
    $('#TravelHotelLookupCityId').change(function() {
            var str = $('#TravelHotelLookupCityId option:selected').text();
            var res = str.split("-");          
            $('#TravelHotelLookupCityCode').val(res[0]);
            $('#TravelHotelLookupCityName').val(res[1]);
        });
        
        $('#TravelHotelLookupCountryId').change(function() {
            var str = $('#TravelHotelLookupCountryId option:selected').text();
            var res = str.split("-");          
            $('#TravelHotelLookupCountryCode').val(res[0]);
            $('#TravelHotelLookupCountryName').val(res[1]);
        });
        
        $('#TravelHotelLookupContinentId').change(function() {
            var str = $('#TravelHotelLookupContinentId option:selected').text();
            var res = str.split("-");          
            $('#TravelHotelLookupContinentCode').val(res[0]);
            $('#TravelHotelLookupContinentName').val(res[1]);
        });
    
    $('#TravelHotelLookupNoRoom').change(function(event) {
        //alert('asd');
        this.value = parseFloat(this.value).toFixed(0);
        //  this.value = this.value.replace (/(\.\d\d)\d+|([\d.]*)[^\d.]/, '$1$2');
    })
    });

</script>    

