<?php
echo $this->Html->css(array('/bootstrap/css/bootstrap.min', 'popup',
    'todc-bootstrap.min',
    'font-awesome/css/font-awesome.min',
    '/js/lib/datepicker/css/datepicker',
    '/js/lib/timepicker/css/bootstrap-timepicker.min'
        )
);
echo $this->Html->script(array('jquery.min', 'lib/chained/jquery.chained.remote.min', 'lib/jquery.inputmask/jquery.inputmask.bundle.min', 'lib/parsley/parsley.min', 'pages/ebro_form_validate', 'lib/datepicker/js/bootstrap-datepicker', 'lib/timepicker/js/bootstrap-timepicker.min', 'pages/ebro_form_extended'));

$other_return = 'display:none;';
if ($actionitems['Lead']['lead_source'] == 3) {
    $client_info = '<span class="old_client">Old Client</span>';
    $heading = 'Old Client Latest Status';
    $div_details = ' style="display:block"';
    $div_mins = ' style="display:none"';
    $other_return = 'display:block;';
} else {
    $client_info = '<span class="new_client">New Client</span>';
    $heading = 'Report First Call';
    $div_details = ' style="display:none"';
    $div_mins = ' style="display:block"';
    $other_return = 'display:block;';
}

//pr($actionitems);
/* End */
?>
<input type="hidden" id="hidden_site_baseurl" value="<?php echo $this->request->base . ((!is_null($this->params['language'])) ? '/' . $this->params['language'] : ''); ?>"  />

<!----------------------------start add project block------------------------------>
<div class="content">
    <div class="pop-up-hdng"><?php if ($user_type == 'Global') { ?>
            <span class="heading_text">Add Client Action | <?php echo $client['Lead']['lead_fname'] . ' ' . $client['Lead']['lead_lname']; ?> | Urgency:<?php echo $client['Urgency']['value']; ?> | Importance:<?php echo $client['Importance']['value']; ?></span><?php echo $client_info; ?>
<?php } else if ($user_type == 'Team') {
    ?>
            <span class="heading_text">Add Client Action | <?php echo $actionitems['Lead']['lead_fname'] . ' ' . $actionitems['Lead']['lead_lname']; ?> | Waiting for Acceptance</span>
<?php } else if ($user_type == 'Builder') { ?>
            <span class="heading_text">Add Builder Action | <?php echo $actionitems['ActionBuilder']['builder_name'] ?> | Waiting for Approval</span>
<?php } else if ($user_type == 'Execution') {
    ?>
            <span class="heading_text">Add Client Action | <?php echo $actionitems['Lead']['lead_fname'] . ' ' . $actionitems['Lead']['lead_lname']; ?> | Waiting for Activation</span><?php echo $client_info; ?>
        <?php
        } else {
            ?><span class="heading_text">Add Action | <?php echo $user_type; ?> | Waiting for Approval</span>
        <?php } ?>
    </div>


        <?php
        //echo $this->Form->create('Remark', array('enctype' => 'multipart/form-data'));
        echo $this->Form->create('ActionItem', array('method' => 'post', 'id' => 'parsley_reg', 'novalidate' => true, 'onsubmit' => 'return validation()',
            'inputDefaults' => array(
                'label' => false,
                'div' => false,
                'class' => 'form-control',
            )
        ));
        echo $this->Form->hidden('model_name', array('id' => 'model_name', 'value' => 'ActionItem'));
        echo $this->Form->hidden('action_item_level_id', array('value' => $actionitems['ActionItem']['action_item_level_id']));
        echo $this->Form->hidden('lead_id', array('value' => $actionitems['ActionItem']['lead_id'], 'type' => 'text'));
        echo $this->Form->hidden('builder_id', array('value' => $actionitems['ActionItem']['builder_id'], 'type' => 'text'));
        echo $this->Form->hidden('project_id', array('value' => $actionitems['ActionItem']['project_id'], 'type' => 'text'));
        echo $this->Form->hidden('action_item_source', array('value' => $actionitems['ActionItem']['action_item_source'], 'type' => 'text'));
        echo $this->Form->hidden('city_id', array('value' => $actionitems['Lead']['city_id'], 'type' => 'text'));
        $cur_date = date('d-m-Y');
        ?>
    <div class="col-sm-12 spacer">

        <div class="col-sm-6">


            <div class="form-group">
                <label class="bgr req">Choose Action Type</label>
                <span class="colon">:</span>
                <div class="col-sm-10"><?php
    echo $this->Form->input('type_id', array('id' => 'type_id', 'options' => $type, 'selected' => array('1', '2'), 'empty' => '--Select--', 'data-required' => 'true'));
    ?>

                </div>

            </div>





        </div>
        <div id="calling_div" class="fullwidth" style="display:none;">


            <h4><?php echo $heading ?></h4>
            <div class="col-sm-6 zindexm">
                <div class="form-group" >
                    <label class="bgr">Start Date</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="input-group date ebro_datepicker event_date_present_div" data-date-format="dd-mm-yyyy" data-date-autoclose="true">
<?php
echo $this->Form->input('Event.date1', array('id' => 'start_date', 'type' => 'text', 'data-date-format' => 'dd-mm-yyyy', 'data-date-autoclose' => 'true'));
?>
                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                        </div>


                    </div>
                </div>
                <div class="form-group">
                    <label class="bgr">Start Time</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="input-group bootstrap-timepicker">
                            <?php
                            echo $this->Form->input('Event.start_time', array('type' => 'text', 'id' => 'start_time'));
                            ?>
                            <span class="input-group-addon"><i class="icon-time"></i></span>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-sm-6 zindexm">
                <div class="form-group" >
                    <label class="bgr">End Date</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="input-group date ebro_datepicker event_date_present_div" data-date-format="dd-mm-yyyy" data-date-autoclose="true">
<?php
echo $this->Form->input('Event.date2', array('id' => 'end_date', 'type' => 'text', 'data-date-format' => 'dd-mm-yyyy', 'data-date-autoclose' => 'true'));
?>
                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                        </div>


                    </div>
                </div>
                <div class="form-group">
                    <label class="bgr">End Time</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="input-group bootstrap-timepicker">
                            <?php
                            echo $this->Form->input('Event.end_time', array('type' => 'text', 'id' => 'end_time'));
                            ?>
                            <span class="input-group-addon"><i class="icon-time"></i></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">


                <div class="form-group">
                    <label class="bgr">Quality</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                            echo $this->Form->input('Event.call_type_id', array('id' => 'type_id', 'options' => $call_quality, 'empty' => '--Select--'));
                            ?>

                    </div>

                </div>





            </div>
            <div class="col-sm-6">
                <div class="form-group" <?php echo $div_details ?>>
                    <label class="bgr">Details</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                            echo $this->Form->input('Event.event_type_desc', array('id' => 'type_id', 'options' => $activity_details, 'empty' => '--Select--'));
                            ?>

                    </div>

                </div>

                <div class="form-group" <?php echo $div_mins ?>>
                    <label class="bgr">Witnin 15 Minutes?</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10 checkbox-cont"><?php
                            $options = array('1' => 'Yes', '2' => 'No');
                            $attributes = array('legend' => false, 'escape' => false, 'hiddenField' => false);
                            echo $this->Form->radio('Event.call_duration', $options, $attributes);
                            ?>

                    </div>

                </div>





            </div>
            <div class="col-sm-12 fullrow">


                <div class="form-group">
                    <label class="bgr">Remark</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('Event.event_description', array('type' => 'textarea'));
                            ?>

                    </div>

                </div>





            </div>


            <h4>Client Details</h4>
            <div class="col-sm-6">


                <div class="form-group">
                    <label class="bgr">First Name</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_fname', array('value' => $actionitems['Lead']['lead_fname']));
                            ?>

                    </div>

                </div>
                <div class="form-group slt-sm">
                    <label class="bgr">Primary Mobile</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_primary_phone_country_code', array('options' => $codes, 'default' => '76', 'empty' => '--Select--', 'value' => $actionitems['Lead']['lead_primary_phone_country_code']));
                        echo $this->Form->input('lead_primaryphonenumber', array('class' => 'form-control sm rgt', 'value' => $actionitems['Lead']['lead_primaryphonenumber']));
                            ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Email</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_emailid', array('value' => $actionitems['Lead']['lead_emailid']));
                            ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Type</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_type', array('options' => $types, 'empty' => '--Select--', 'tabindex' => '12', 'value' => $actionitems['Lead']['lead_type']));
                            ?>

                    </div>

                </div>
                <div class="form-group slt-sm">
                    <label class="bgr">Budget</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_budget_unit', array('options' => $courrency, 'default' => '76', 'empty' => '--Select--', 'value' => $actionitems['Lead']['lead_budget_unit']));
                        echo $this->Form->input('lead_budget', array('class' => 'form-control sm rgt', 'value' => $actionitems['Lead']['lead_budget']));
                            ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Progress</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_progress', array('options' => $lead_progrss, 'empty' => '--Select--', 'tabindex' => '11', 'value' => $actionitems['Lead']['lead_progress'], 'disabled' => array('20')));
                            ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Importance</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_importance', array('options' => $importance, 'empty' => '--Select--', 'tabindex' => '10', 'value' => $actionitems['Lead']['lead_importance']));
                            ?>

                    </div>

                </div>





            </div>
            <div class="col-sm-6">


                <div class="form-group">
                    <label class="bgr">Last Name</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_lname', array('value' => $actionitems['Lead']['lead_lname']));
                        ?>

                    </div>

                </div>
                <div class="form-group slt-sm">
                    <label class="bgr">Secondary Mobile</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_secondary_phone_country_code', array('options' => $codes, 'default' => '76', 'empty' => '--Select--', 'value' => $actionitems['Lead']['lead_secondary_phone_country_code']));
                        echo $this->Form->input('lead_secondaryphonenumber', array('class' => 'form-control sm rgt', 'value' => $actionitems['Lead']['lead_secondaryphonenumber']));
                        ?>

                    </div>

                </div>

                <div class="form-group">
                    <label class="bgr">Location</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php echo $this->Form->input('lead_country', array('options' => $countires, 'empty' => 'Select', 'tabindex' => '2', 'value' => $actionitems['Lead']['lead_country'])); ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Segment </label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_segment', array('options' => $segment, 'empty' => '--Select--', 'tabindex' => '19', 'value' => $actionitems['Lead']['lead_segment']));
                        ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Special</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_special_client_status', array('options' => array('1' => 'Yes', '2' => 'No'), 'empty' => '--Select--', 'value' => $actionitems['Lead']['lead_special_client_status'], 'tabindex' => '14'));
                        ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Closure Probability</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_closureprobabilityinnext1Month', array('options' => $closureprobabilities, 'value' => $actionitems['Lead']['lead_closureprobabilityinnext1Month'], 'empty' => '--Select--', 'tabindex' => '20'));
                        ?>

                    </div>

                </div>
                <div class="form-group">
                    <label class="bgr">Urgency</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10"><?php
                        echo $this->Form->input('lead_urgency', array('options' => $urgencies, 'empty' => '--Select--', 'value' => $actionitems['Lead']['lead_urgency'], 'tabindex' => '9'));
                        ?>

                    </div>

                </div>



            </div>
            <h4>Client preferences </h4>
            <div class="col-sm-12 fullrow">
                <div class="form-group">
                    <label>Suburb</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="form-group xtrawidth">
                            <div class="col-sm-4 col-xs-12">

                        <?php
                        echo $this->Form->input('lead_suburb1', array('options' => $suburbs, 'value' => $actionitems['Lead']['lead_suburb1'], 'tabindex' => '22', 'class' => 'form-control city_bootbox_custom', 'empty' => '--Preference 1--', 'onchange' => 'filterPreferences(this.value,\'LeadLeadSuburb2\',\'LeadLeadSuburb3\')'));
                        ?>
                            </div>
                            <div class="col-sm-4 col-xs-12"><?php
                        echo $this->Form->input('lead_suburb2', array('options' => $suburbs, 'value' => $actionitems['Lead']['lead_suburb2'], 'tabindex' => '23', 'empty' => '--Preference 2--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadSuburb1\',\'LeadLeadSuburb3\')'));
                        ?>
                            </div>
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('lead_suburb3', array('options' => $suburbs, 'value' => $actionitems['Lead']['lead_suburb3'], 'tabindex' => '24', 'empty' => '--Preference 3--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadSuburb1\',\'LeadLeadSuburb2\')'));
?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 fullrow">
                <div class="form-group">
                    <label>Area</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="form-group xtrawidth">    
                            <div class="col-sm-4 col-xs-12">

                                <?php
                                echo $this->Form->input('lead_areapreference1', array('options' => $areas, 'value' => $actionitems['Lead']['lead_areapreference1'], 'tabindex' => '25', 'empty' => '--Preference 1--', 'class' => 'form-control city_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadAreapreference2\',\'LeadLeadAreapreference3\')'));
                                ?>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('lead_areapreference2', array('options' => $areas, 'value' => $actionitems['Lead']['lead_areapreference2'], 'tabindex' => '26', 'empty' => '--Preference 2--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadAreapreference1\',\'LeadLeadAreapreference3\')'));
                                ?></div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('lead_areapreference3', array('options' => $areas, 'value' => $actionitems['Lead']['lead_areapreference3'], 'tabindex' => '27', 'empty' => '--Preference 3--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadAreapreference1\',\'LeadLeadAreapreference2\')'));
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 fullrow">
                <div class="form-group">
                    <label>Builder</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="form-group xtrawidth">
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('builder_id1', array('options' => $builders, 'value' => $actionitems['Lead']['builder_id1'], 'tabindex' => '28', 'class' => 'form-control city_bootbox_custom', 'empty' => '--Preference 1--', 'onchange' => 'filterPreferences(this.value,\'LeadBuilderId2\',\'LeadBuilderId3\')'));
                                ?></div>
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('builder_id2', array('options' => $builders, 'value' => $actionitems['Lead']['builder_id2'], 'tabindex' => '29', 'empty' => '--Preference 2--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadBuilderId1\',\'LeadBuilderId3\')'));
?>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('builder_id3', array('options' => $builders, 'value' => $actionitems['Lead']['builder_id3'], 'tabindex' => '30', 'empty' => '--Preference 3--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadBuilderId1\',\'LeadBuilderId2\')'));
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 fullrow">
                <div class="form-group">
                    <label>Project</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="form-group xtrawidth">    
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('proj_id1', array('options' => $projects, 'tabindex' => '31', 'value' => $actionitems['Lead']['proj_id1'], 'empty' => '--Preference 1--', 'class' => 'form-control city_bootbox_custom',
    'onchange' => 'filterPreferences(this.value,\'LeadProjId2\',\'LeadProjId3\')'));
?></div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('proj_id2', array('options' => $projects, 'tabindex' => '32', 'value' => $actionitems['Lead']['proj_id2'], 'empty' => '--Preference 2--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadProjId1\',\'LeadProjId3\')'));
                                ?></div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('proj_id3', array('options' => $projects, 'tabindex' => '33', 'value' => $actionitems['Lead']['proj_id3'], 'empty' => '--Preference 3--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadProjId1\',\'LeadProjId2\')'));
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 fullrow">
                <div class="form-group">
                    <label>Unit</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="form-group xtrawidth">    
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('lead_unit_id_1', array('options' => $unit, 'tabindex' => '34', 'value' => $actionitems['Lead']['lead_unit_id_1'], 'empty' => '--Preference 1--', 'onchange' => 'filterPreferences(this.value,\'LeadLeadUnitId2\',\'LeadLeadUnitId3\')'));
?></div>
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('lead_unit_id_2', array('options' => $unit, 'tabindex' => '35', 'value' => $actionitems['Lead']['lead_unit_id_2'], 'empty' => '--Preference 2--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadUnitId1\',\'LeadLeadUnitId3\')'));
?></div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('lead_unit_id_3', array('options' => $unit, 'tabindex' => '36', 'value' => $actionitems['Lead']['lead_unit_id_3'], 'empty' => '--Preference 3--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadUnitId1\',\'LeadLeadUnitId2\')'));
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 fullrow">
                <div class="form-group">
                    <label>Project Type</label>
                    <span class="colon">:</span>
                    <div class="col-sm-10">
                        <div class="form-group xtrawidth">    
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('lead_typeofprojectpreference1', array('options' => $type_preference, 'value' => $actionitems['Lead']['lead_typeofprojectpreference1'], 'tabindex' => '37', 'empty' => '--Preference 1--', 'onchange' => 'filterPreferences(this.value,\'LeadLeadTypeofprojectpreference2\',\'LeadLeadTypeofprojectpreference3\')'));
?></div>
                            <div class="col-sm-4 col-xs-12">
<?php
echo $this->Form->input('lead_typeofprojectpreference2', array('options' => $type_preference, 'value' => $actionitems['Lead']['lead_typeofprojectpreference2'], 'tabindex' => '38', 'empty' => '--Preference 2--', 'class' => 'form-control pre_bootbox_custom', 'onchange' => 'filterPreferences(this.value,\'LeadLeadTypeofprojectpreference1\',\'LeadLeadTypeofprojectpreference3\')'));
?></div>
                            <div class="col-sm-4 col-xs-12">
                                <?php
                                echo $this->Form->input('lead_typeofprojectpreference3', array('options' => $type_preference, 'value' => $actionitems['Lead']['lead_typeofprojectpreference3'], 'tabindex' => '39', 'class' => 'form-control pre_bootbox_custom', 'empty' => '--Preference 3--', 'onchange' => 'filterPreferences(this.value,\'LeadLeadTypeofprojectpreference1\',\'LeadLeadTypeofprojectpreference2\')'));
                                ?></div>
                        </div>
                    </div>
                </div>
            </div>   

        </div>                                                    
        <div class="col-sm-6">
            <div class="form-group" id="div_line" style="display: none;">
                <label>Allocate To Channel</label>
                <span class="colon">:</span>
                <div class="col-sm-10">	<?php
                                echo $this->Form->input('allocated_channel_id', array('id' => 'allocated_channel_id', 'options' => $channels, 'empty' => '--Select--'));
                                ?></div>
            </div>


            <div class="form-group" id="rejection" style="display: none;">
                <label class="bgr">Reason for Rejection</label>
                <span class="colon">:</span>
                <div class="col-sm-10">	<?php echo $this->Form->input('lookup_rejection_id', array('id' => 'rejections_id', 'options' => $rejections, 'empty' => '--Select--')); ?>
                </div>
            </div>
            <div class="form-group" id="return" style="display: none;">
                <label>Reason for Return</label>
                <span class="colon">:</span>
                <div class="col-sm-10">	<?php
                                echo $this->Form->input('lookup_return_id', array('id' => 'return_id', 'options' => $returns, 'empty' => '--Select--'));
                                ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 spacer">
        <div id="ajax"></div>
    </div>
    <div class="col-sm-12 spacer">

        <div class="form-group smlabel" id="secondary_manager_id"  style="display: none;">
            <label>Secondary Manager</label>
            <span class="colon">:</span>
            <div class="col-sm-10">	<?php
                                echo $this->Form->input('secondary_manager_id', array('options' => $secondary_manager, 'empty' => '--Select--'));
                                ?></div>
        </div>
        <div class="form-group smlabel" id="sec_mang" style="display: none;">
            <label>Secondary Manager</label>
            <span class="colon">:</span>
            <div class="col-sm-10">&nbsp;</div>
        </div>
    </div>

    <div class="col-sm-12 spacer">

        <div class="col-sm-12 spacer">

            <div class="lf-space"><?php
                                echo $this->Form->input('other_return', array('div' => array('id' => 'other_return', 'style' => 'display:none;'), 'type' => 'textarea'));

                                echo $this->Form->input('other_rejection', array('div' => array('id' => 'other', 'style' => 'display:none;'), 'label' => false, 'type' => 'textarea'));
                                ?></div>
        </div>
    </div>

    <div class="row spacer">
        <div class="col-sm-12"><div class="col-sm-12"><?php echo $this->Form->submit('Add Action', array('class' => 'success btn', 'div' => false, 'id' => 'udate_unit')); ?><?php
                    echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'reset btn'));
                    ?></div></div>
    </div>



<?php echo $this->Form->end();
?>
</div>	

<script type="text/javascript">




    $(document).ready(function() {

        var FULL_BASE_URL = $('#hidden_site_baseurl').val();


        $('#allocated_channel_id').change(function() {

            var channel_id = $(this).val();
            var lead_id = $('#ActionItemLeadId').val();
            // alert(channel_id);
            var model = $('#model_name').val();
            var dataString = 'channel_id=' + channel_id + '&model=' + model + '&lead_id=' + lead_id;
            $('#ajax').addClass('loader');
            $('.success').attr('disabled', 'true');

            $.ajax({
                type: "POST",
                data: dataString,
                url: FULL_BASE_URL + '/all_functions/get_prmember_by_channel_id',
                beforeSend: function() {
                    $('#ajax').addClass('loader');
                    $('.success').attr('disabled', 'true');
                },
                success: function(return_data) {
                    $('#ajax').removeClass('loader');
                    $('.success').removeAttr('disabled', 'false');
                    $('#ajax').html(return_data);
                    $('#sec_mang').css('display', 'block');
                    $('#out_going_msg').css('display', 'block');
                }
            });

        });

        $('.pre_bootbox_custom').click(function(e) {

            var id = $(this).parent().prev('div').find('select').attr('id');
            var pref = $('#' + id).val();


            if (pref == '' || pref == null) {
                alert('Please select preference');
            }

        });

        $('#type_id').change(function() {
            var type = $(this).val();
            if (type == 9) {
                $('#div_line').css('display', 'none');
                $('#rejection').css('display', 'block');
                $('#remarks').val('');
                $('#pri_mang').css('display', 'none');
                $('#return').css('display', 'none');
                $('#sec_mang').css('display', 'none');
                $('#out_going_msg').css('display', 'none');
                $('#going_msg').css('display', 'none');
                $('#secondary_manager_id').css('display', 'none');
                $('#calling_div').css('display', 'none');

            }

            if (type == 4) {
                $('#div_line').css('display', 'block');
                $('#calling_div').css('display', 'none');
                $('#rejection').css('display', 'none');
                $('#return').css('display', 'none');
                $('#secondary_manager_id').css('display', 'none');

            }
            if (type == 8) {
                $('#return').css('display', 'block');
                $('#div_line').css('display', 'none');
                $('#rejection').css('display', 'none');
                $('#pri_mang').css('display', 'none');
                $('#sec_mang').css('display', 'none');
                $('#out_going_msg').css('display', 'none');
                $('#going_msg').css('display', 'none');
                $('#secondary_manager_id').css('display', 'none');
                $('#calling_div').css('display', 'none');
            }
            if (type == 2) {
                $('#return').css('display', 'none');
                $('#secondary_manager_id').css('display', 'none');
            }
            if (type == 6) {

                $('#return').css('display', 'none');
            }
            if (type == 3) {
                $('#secondary_manager_id').css('display', 'none');
                $('#return').css('display', 'none');
                $('#calling_div').css('display', 'block');
            }
            if (type == 5) {
                $('#div_line').css('display', 'block');
                $('#calling_div').css('display', 'none');
                $('#rejection').css('display', 'none');
                $('#return').css('display', 'none');
                $('#secondary_manager_id').css('display', 'none');


            }


        });

        $('#return_id').change(function() {
            var value = $(this).val();
            if (value == 10 || value == 1 || value == 2 || value == 3 || value == 4 || value == 5 || value == 6 || value == 7 || value == 8 || value == 9 || value == 29 || value == 30 || value == 31) {
                $('#other_return').css('display', 'block');
            }
            else {
                $('#other_return').css('display', 'none');
            }

        });

        $('#rejections_id').change(function() {
            var value = $(this).val();
            if (value == 10 || value == 1 || value == 2 || value == 3 || value == 4 || value == 5 || value == 6 || value == 7 || value == 8 || value == 9 || value == 11 || value == 12 || value == 13 || value == 14 || value == 15 || value == 16 || value == 17 || value == 18 || value == 19 || value == 20 || value == 21 || value == 22 || value == 23 || value == 24 || value == 25 || value == 26 || value == 27 || value == 28 || value == 29 || value == 30 || value == 31) {

                $('#other').css('display', 'block');
            }
            else {
                $('#other').css('display', 'none');
            }

        });

    });
    function validation() {
        var value = $('#rejections_id').val();
        var return_id = $('#return_id').val();
        var type_id = $('#type_id').val();


        if (value == 10 || type_id == 9)
        {
            if ($('#ActionItemOtherRejection').val() == '')
            {
                alert('Please type resion description');
                return false;
            }
        }


        if (return_id == 10 || return_id == 1 || return_id == 2 || return_id == 5 || return_id == 29 || return_id == 30 || type_id == 8) {
            if ($('#ActionItemOtherReturn').val() == '')
            {
                alert('Please type return description');
                return false;
            }
        }
    }


</script>		
<!----------------------------end add project block------------------------------>
