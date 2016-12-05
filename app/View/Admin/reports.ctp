<?php $this->Html->addCrumb('My Reports', 'javascript:void(0);', array('class' => 'breadcrumblast'));
 ?> 
 <?php if ($this->Session->read('role_id') == '65' || $this->Session->read('role_id') == '62' || $this->Session->read('role_id') == '61' || $this->Session->read('role_id') == '28' || $this->Session->read('role_id') == '68') { 
 ?>
<div class="row">
            <div class="col-md-4 active">
                <div class="info-box  bg-info  text-white" id="initial-tour">
                    <div class="info-icon bg-info-dark">
                        <span aria-hidden="true" class="icon icon-layers"></span>
                    </div>
                    <div class="info-details">
        <?php
        echo $this->Html->link('<h4>Summary Report<span class="pull-right"></span></h4><h6>By User, Role</h6>', '/reports/summary_report', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Summary Report', 'escape' => false));       
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
        echo $this->Html->link('<h4>Activity Report<span class="pull-right"></span></h4><h6>By User, Role</h6>', '/reports/my_activity_report', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Buzznet', 'escape' => false));       
        ?>
                    </div>
                </div>
            </div>
</div>
 <?php } else {
 ?>
<div class="row">
            <div class="col-md-4 active">
                <div class="info-box  bg-info  text-white" id="initial-tour">
                    <div class="info-icon bg-info-dark">
                        <span aria-hidden="true" class="icon icon-layers"></span>
                    </div>
                    <div class="info-details">
        <?php
        echo $this->Html->link('<h4>Summary Report<span class="pull-right"></span></h4><h6>By Content</h6>', '/reports/summary_reports', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Buzznet', 'escape' => false));       
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
        echo $this->Html->link('<h4>Province Summary Report<span class="pull-right"></span></h4><h6>By Country</h6>', '/reports/province_reports', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Buzznet', 'escape' => false));       
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
        echo $this->Html->link('<h4>Hotel Summary Report (Mismatched)<span class="pull-right"></span></h4><h6>By Country</h6>', '/reports/mismatch_hotel', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Tumblr', 'escape' => false));        
        ?>
                    </div>
                </div>
            </div>
             
         </div>
<div class="row">
<div class="col-md-4 active">

                <div class="info-box  bg-info  text-white" id="initial-tour">
                    <div class="info-icon bg-info-dark">
                        <span aria-hidden="true" class="icon icon-layers"></span>
                    </div>
                    <div class="info-details">
        <?php
        echo $this->Html->link('<h4>City Summary Report<span class="pull-right"></span></h4><h6>By Country</h6>', '/reports/city_reports', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'City Reports', 'escape' => false));        
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
        echo $this->Html->link('<h4>My Report</h4>', '/reports/job_report', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Job Report', 'escape' => false));        
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
        echo $this->Html->link('<h4>Hotel Summary Report (Mismatched)<span class="pull-right"></span></h4><h6>By Country Reverse</h6>', '/reports/mismatch_country', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'My Tumblr', 'escape' => false));        
        ?>
                    </div>
                </div>
            </div>
         </div>
<div class="row">
 <div class="col-md-4 active">

                <div class="info-box  bg-info  text-white" id="initial-tour">
                    <div class="info-icon bg-info-dark">
                        <span aria-hidden="true" class="icon icon-layers"></span>
                    </div>
                    <div class="info-details">
        <?php
        echo $this->Html->link('<h4>Duplicate Hotel Report<span class="pull-right"></span></h4><h6>By Continent, Country, Province, City</h6>', '/reports/duplicate_hotel_report', array('data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => 'Duplicate Hotel Report', 'escape' => false));        
        ?>
                    </div>
                </div>
            </div>
         </div>
<?php 
}
 ?>		 
