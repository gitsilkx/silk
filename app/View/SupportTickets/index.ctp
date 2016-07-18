<?php $this->Html->addCrumb('Support Ticket', 'javascript:void(0);', array('class' => 'breadcrumblast')); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-heading">
            <h4 class="table-heading-title"><span class="badge badge-circle badge-success"> <?php
                    echo $this->Paginator->counter(array('format' => '{:count}'));
                    ?></span>Support Ticket</h4>
            <span class="badge badge-circle add-client nomrgn"><i class="icon-plus"></i> <?php echo $this->Html->link('Create Support Ticket', '#') ?></span>
            <span class="search_panel_icon"><i class="icon-plus" id="toggle_search_panel"></i></span>
        </div>
        <div class="panel panel-default">

            <div class="panel_controls hideform">

                <?php
                echo $this->Form->create('SupportTicket', array('controller' => 'travel_areas', 'action' => 'index', 'class' => 'quick_search', 'id' => 'SearchForm', 'novalidate' => true, 'inputDefaults' => array(
                        'label' => false,
                        'div' => false,
                        'class' => 'form-control',
                )));
                ?> 

                <div class="row spe-row">
                    <div class="col-sm-4 col-xs-8">

                        <?php echo $this->Form->input('about', array('placeholder' => 'Type raised about', 'error' => array('class' => 'formerror'))); ?>
                    </div>
                    <div class="col-sm-3 col-xs-4">
                        <?php
                        echo $this->Form->submit('Search', array('div' => false, 'class' => 'btn btn-default btn-sm"'));
                        ?>

                    </div>
                </div>
                    <div class="row" id="search_panel_controls">
                    
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Raised By:</label>
                        <?php echo $this->Form->input('created_by', array('options' => $users, 'empty' => '--Select--')); ?>
                    </div>

                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Raised From:</label>
                        <?php echo $this->Form->input('screen', array('options' => $LookupScreen, 'empty' => '--Select--')); ?>
                    </div>

                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Topic:</label>
                        <?php echo $this->Form->input('answer', array('options' => $LookupQuestion, 'empty' => '--Select--')); ?>
                    </div>

                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Urgency:</label>
                        <?php echo $this->Form->input('urgency', array('options' => $LookupTicketUrgency, 'empty' => '--Select--')); ?>
                    </div>
                        
                    <div class="col-sm-3 col-xs-6">
                        <label for="un_member">Status:</label>
                        <?php echo $this->Form->input('status', array('options' => $LookupTicketStatus, 'empty' => '--Select--')); ?>
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
            </div>
            <table id="resp_table" class="table toggle-square" data-filter="#table_search" data-page-size="500">
               <thead>
                <tr>
                    <th>Ticket #</th>
                    <th class="hidden-sm hidden-xs">Raised By</th>
                    <th class="hidden-sm hidden-xs">Raised On</th>
                    <th class="hidden-sm hidden-xs">Raised From</th> 
                    <th class="hidden-sm hidden-xs">Raised About</th> 
                    <th class="hidden-sm hidden-xs">Topic</th>
                    <th>Urgency</th>
                    <th>Last Action By</th>
                    <th>Next Action By</th>
                    <th>Approved By</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (isset($SupportTickets) && count($SupportTickets) > 0):
                        foreach ($SupportTickets as $SupportTicket):
                            $id = $SupportTicket['SupportTicket']['id'];   
                            $status = $SupportTicket['SupportTicket']['status'];
                            ?>
                            <tr>
                               <td><?php echo $id; ?></td>
                                <td><?php echo $this->Custom->Username($SupportTicket['SupportTicket']['created_by']); ?></td>
                                <td><?php echo $SupportTicket['SupportTicket']['created']; ?></td>
                                <td><?php echo $SupportTicket['LookupScreen']['value']; ?></td>
                                <td><?php echo $SupportTicket['SupportTicket']['about']; ?></td>

                                <td><?php echo $SupportTicket['Answer']['question']; ?></td>
                                <td><?php echo $SupportTicket['LookupTicketUrgency']['value']; ?></td>
                                <td><?php echo ($SupportTicket['SupportTicket']['last_action_by']) ? $this->Custom->Username($SupportTicket['SupportTicket']['last_action_by']) : ''; ?></td>                               
                                <td><?php echo ($SupportTicket['SupportTicket']['next_action_by']) ? $this->Custom->Username($SupportTicket['SupportTicket']['next_action_by']) : ''; ?></td>
                                <td><?php echo ($SupportTicket['SupportTicket']['approved_by']) ? $this->Custom->Username($SupportTicket['SupportTicket']['approved_by']) : ''; ?></td>
                                <td><?php echo $SupportTicket['LookupTicketStatus']['value']; ?></td>
                                <td width="10%" valign="middle" align="center">
                                    <?php
                                    
                                    
                                    if(($SupportTicket['SupportTicket']['last_action_by'] == $self_id || $SupportTicket['SupportTicket']['approved_by'] == $self_id) && ($SupportTicket['SupportTicket']['status'] == '1' || $SupportTicket['SupportTicket']['status'] == '2'  || $SupportTicket['SupportTicket']['status'] == '3')){
                                        echo $this->Html->link('<span class="icon-eye-open"></span>', '/support_tickets/view/'.$id,array('class' => 'add-btn','target' => '_blank','escape' => false));                                   
                                    }                                    
                                    else
                                        echo $this->Html->link('<span class="icon-pencil"></span>', '/support_tickets/view/'.$id,array('class' => 'add-btn','target' => '_blank','escape' => false));                                   
                           
                       
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
            <span class="badge badge-circle add-client"><i class="icon-plus"></i> <?php echo $this->Html->link('Create Support Ticket', '#') ?></span>

        </div>
    </div>
</div>
