<?php
?>
<div class="row">
    <div class="col-md-4 active">
        <div class="info-box  bg-info  text-white" id="initial-tour">
            <div class="info-icon bg-info-dark">
                <span aria-hidden="true" class="icon icon-layers"></span>
            </div>
            <div class="info-details">
                <?php
                echo $this->Html->link('<h4>Insert Operations<span class="pull-right"></span></h4>', '/mass_operations/insert_operations', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Insert Operations', 'escape' => false));
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 active">
        <div class="info-box  bg-info  text-white" id="initial-tour">
            <div class="info-icon bg-info-dark">
                <span aria-hidden="true" class="icon icon-layers"></span>
            </div>
            <div class="info-details">
                <?php
                echo $this->Html->link('<h4>Update Operations<span class="pull-right"></span></h4>', '/mass_operations/update_operations', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Update Operations', 'escape' => false));
                ?>
            </div>
        </div>
    </div>          
</div>
