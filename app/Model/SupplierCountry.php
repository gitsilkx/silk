<?php

App::uses('AppModel', 'Model');

class SupplierCountry extends AppModel {

    var $name = 'SupplierCountry';

    public $belongsTo = array(
        'TravelSupplierStatus' => array(
            'className' => 'TravelSupplierStatus',
            'foreignKey' => 'status',
        ),
    );

}

?>