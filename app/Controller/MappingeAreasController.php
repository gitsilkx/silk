<?php

/**
 * MappingArea controller.
 *
 * This file will render views from views/agents/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('CakeEmail', 'Network/Email');
/**
 * Email sender
 */
App::uses('AppController', 'Controller');

/**
 * Agent controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class MappingeAreasController extends AppController {

    public $uses = array('TravelHotelLookup','TravelCountry','TravelCity','Province','SupplierHotel','TravelSupplier',
        'TravelLookupContinent','TravelHotelRoomSupplier');

    public function supplier_hotels() {

        $search_condition = array();
        $supplier_id = '';
        $proArr = array();
        $SupplierHotels = array();
        $TravelCountries = array();
        $TravelCities = array();
        $Provinces = array();
        $display = 'FALSE';
        $supplier_id = '';
        $continent_id = '';
        $country_id = '';
        $province_id = '';
        $city_id = '';
        
        if($this->checkProvince())
            $proArr = $this->checkProvince();
        
        if ($this->request->is('post') || $this->request->is('put')){
            $display = 'TRUE';
            if (!empty($this->data['TravelHotelLookup']['supplier_id'])) {
                $supplier_id = $this->data['TravelHotelLookup']['supplier_id'];
               
            }
           if (!empty($this->data['TravelHotelLookup']['continent_id'])) {
                $continent_id = $this->data['TravelHotelLookup']['continent_id'];
                //array_push($search_condition, array('TravelHotelLookup.continent_id' => $continent_id));
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id, country_name', 'conditions' => array('TravelCountry.continent_id' => $continent_id,
                        'TravelCountry.country_status' => '1',
                        'TravelCountry.wtb_status' => '1',
                        'TravelCountry.active' => 'TRUE'), 'order' => 'country_name ASC'));
            }

            if (!empty($this->data['TravelHotelLookup']['country_id'])) {
                $country_id = $this->data['TravelHotelLookup']['country_id'];
                $province_id = $this->data['TravelHotelLookup']['province_id'];
                //array_push($search_condition, array('TravelHotelLookup.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id, city_name', 'conditions' => array('TravelCity.province_id' => $province_id,
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE',), 'order' => 'city_name ASC'));
                
                
            }
            if (!empty($this->data['TravelHotelLookup']['province_id'])) {
                
                //array_push($search_condition, array('TravelHotelLookup.province_id' => $province_id));
                $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $country_id,
                    'Province.continent_id' => $continent_id,
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE',
                    'Province.id' => $proArr
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));
            }
            if (!empty($this->data['TravelHotelLookup']['city_id'])) {
                $city_id = $this->data['TravelHotelLookup']['city_id'];
                //array_push($search_condition, array('TravelHotelLookup.city_id' => $city_id));               
            }
            
            $TravelHotelRoomSuppliers = $this->TravelHotelRoomSupplier->find('list',ARRAY('fields' => 'supplier_item_code3,supplier_item_code3','conditions' => 
             array('supplier_id' => $supplier_id,'hotel_continent_id' => $continent_id,'hotel_country_id' => $country_id,'province_id' => $province_id,'hotel_city_id' => $city_id)));
            pr($TravelHotelRoomSuppliers);
            
            $this->paginate['order'] = array('SupplierHotel.id' => 'asc');
            $this->set('SupplierHotels', $this->paginate("SupplierHotel", $search_condition));
        }
       
         
        
        
        $TravelSuppliers = $this->TravelSupplier->find('list', array('fields' => 'id,supplier_code', 'order' => 'supplier_code ASC'));
        
        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'order' => 'continent_name ASC'));
       
        
        $this->set(compact('TravelCities','Provinces','TravelCountries','display','TravelLookupContinents'
                ,'TravelSuppliers','supplier_id','continent_id','country_id','province_id','city_id'));
        
    }

    

}

