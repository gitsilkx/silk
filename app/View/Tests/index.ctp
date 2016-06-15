<?php
$this->Html->addCrumb('My Tests', 'javascript:void(0);', array('class' => 'breadcrumblast'));
echo $this->Form->create('Test', array('enctype' => 'multipart/form-data', 'method' => 'post',
    'id' => 'parsley_reg',
    'novalidate' => true,
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'class' => 'form-control',
    )
));
//echo $this->Html->link('<span class="icon-list"></span>', array('controller' => 'messages', 'action' => 'index/leads/my-clients'), array('class' => 'act-ico', 'escape' => false));
?>

<div class="col-sm-12" id="mycl-det">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Spinner: Output Single Content Versions From Nested Spintax</h4>
        </div>
        <div class="panel-body">
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="reg_input_name" style="margin-left: 14px">Enter Spintax</label>
                        <span class="colon">:</span>
                        <div class="col-sm-10">
                            <?php
                            echo $this->Form->input('text', array('type' => 'textarea', 'style' => 'width:122%;height:100px'));
                            ?></div>
                    </div>
                    <div class="form-group">
                        <label for="reg_input_name" style="margin-left: 14px">Output Spintax</label>
                        <span class="colon">:</span>
                        <div class="col-sm-10">
                            <?php
                            echo $this->Form->input('task_url_description2', array('type' => 'textarea', 'style' => 'width:122%;height:100px','value' => $output_txt));
                            ?></div>
                    </div>


                </div>



                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-1">
                            <?php
                            echo $this->Form->submit('Action', array('class' => 'btn btn-success sticky_success'));
                            ?>
                        </div>
                        <div class="col-sm-1">
                            <?php echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-danger sticky_important')); ?>
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



