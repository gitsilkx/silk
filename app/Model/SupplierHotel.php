<?php
	App::uses('AppModel', 'Model');
	
	class SupplierHotel extends AppModel {
		var $name = 'SupplierHotel';
		
		public $validate = array(
		'hotel_name' => array(											
			'notempty' => array(									
				'rule' => array('notempty'),								
				'message' => 'Please enter hotel name',
				
			),

			'isUnique' => array (
				'rule' => 'isUnique',
				'message' => 'This hotel already exists.'
				
			),

		)
	);
			
		
	}
?>