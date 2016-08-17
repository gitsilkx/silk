<?php

App::uses('AppModel', 'Model');

class TravelHotelImage extends AppModel {

    public $name = 'TravelHotelImage';

    public $validate = array(
        'image1' => array(
            'type' => array(
                'rule'    => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
                'message' => 'Please supply a valid image.'
            ),
            )    
        );

}

?>