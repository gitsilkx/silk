<?php

App::uses('AppModel', 'Model');

class TravelHotelLookup extends AppModel {

    var $name = 'TravelHotelLookup';
    public $validate = array(
    );
    public $belongsTo = array(
        'ContractStatus' => array(
            'className' => 'TravelLookupValueContractStatus',
            'foreignKey' => 'contract_status',
        ), 
    );
    public $hasMany = array(
        'TravelHotelRoomSupplier' => array(
            'className' => 'TravelHotelRoomSupplier',
            'foreignKey' => 'hotel_id',            
            'conditions' => array('supplier_id' => $this->data[$this->name]['supplier_id'])  // 1 for client table of  lookup_value_activity_levels
        ),
        
        
    );
   

}

?>