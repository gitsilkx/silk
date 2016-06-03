<?php
$this->Html->addCrumb('Insert Operations', 'javascript:void(0);', array('class' => 'breadcrumblast'));
echo $this->Form->create('InsertTable', array('method' => 'post',
    'id' => 'parsley_reg',
    'enctype' => 'multipart/form-data',
    'name' => 'fom',
    'onSubmit' => 'return valiDate()',
    'novalidate' => true,
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class' => 'form-control',
    ),
));
?>
<div class="col-sm-12" id="mycl-det">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Insert Operations</h4>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="reg_input_name" class="req"  style="margin-left: 14px;">Select Table</label>
                            <span class="colon">:</span>
                            <div class="col-sm-8">
                                <?php
// 'TravelHotelLookup' => 'Hotel', 'TravelCountry' => 'Country', 'TravelCity' => 'City', 'TravelCountrySupplier' => 'Mapping Country', 'TravelCitySupplier' => 'Mapping City', 'TravelHotelRoomSupplier' => 'Mapping Hotel'
                                echo $this->Form->input('table', array('options' => array('Province' => 'Province', 'TravelSuburb' => 'Suburb','TravelArea' => 'Area'), 'empty' => '--Select--', 'data-required' => 'true'));
                                ?></div>
                        </div>  
                        <div class="form-group">
                             <label for="reg_input_name" class="req"  style="margin-left: 14px;">Select File</label>
                            <span class="colon" style="margin-right: 20px">:</span>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-group">
                                <div class="form-control">
                                    <i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span>
                                </div>
                                <div class="input-group-btn">
                                    <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileupload-new">Select file</span>
                                        <span class="fileupload-exists">Change</span>
                                        <input type="file" name="data[InsertTable][file]" />
                                    </span>
                                </div>
                               
                            </div>
                            <span id="fileName" style="margin-left: 196px;
color: red;"></span>
                            
                        </div> 
                            
                            </div>
                    </div>
                    <div class="col-sm-6" id="ajax_Html">
                       
                        
                    </div>

                </div>
                <div class="clear" style="clear: both;"></div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-1">
                            <?php
                            echo $this->Form->submit('Proceed', array('class' => 'btn btn-success sticky_success'));
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
    $('#InsertTableTable').change(function() {
        var table = $(this).val();
        if(table == 'Province'){
            $('#ajax_Html').html('<a href="<?php echo $this->webroot . 'uploads/MassOperations/smaple/insert/Province.csv';?>" download> <button type="button" class="btn btn-primary btn-lg btn-block" style="width:100%">Download Province Sample [Note- Do not change column name]</button></a>');
            $('#fileName').text('[File name should be - '+table+'.csv]');
        }
        else if(table == 'TravelSuburb'){
            $('#ajax_Html').html('<a href="<?php echo $this->webroot . 'uploads/MassOperations/smaple/insert/TravelSuburb.csv';?>" download> <button type="button" class="btn btn-primary btn-lg btn-block" style="width:100%">Download Suburb Sample [Note- Do not change column name]</button></a>');
            $('#fileName').text('[File name should be - '+table+'.csv]');
        }
        else if(table == 'TravelArea'){
            $('#ajax_Html').html('<a href="<?php echo $this->webroot . 'uploads/MassOperations/smaple/insert/TravelArea.csv';?>" download> <button type="button" class="btn btn-primary btn-lg btn-block" style="width:100%">Download Area Sample [Note- Do not change column name]</button></a>');
            $('#fileName').text('[File name should be - '+table+'.csv]');
        }
        else{
            $('#ajax_Html').html('');
            $('#fileName').text('');
        }
    })
    </script>