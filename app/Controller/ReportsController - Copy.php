<?php

/**
 * Reports controller.
 *
 * This file will render views from views/DownloadTables/
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
 * DownloadTables controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ReportsController extends AppController {

    public $uses = array('TravelHotelLookup', 'TravelCity','TravelCountry','TravelLookupContinent', 'TravelLookupValueContractStatus', 'TravelChain',
        'TravelSuburb', 'TravelArea', 'TravelBrand','Province','TravelHotelRoomSupplier','TravelCitySupplier','Mappinge','TravelSupplier','LogCall');

    public function index() {
        
    }

    public function reports() {


        $TravelCities = array();
        $country_id = '220';

        if ($this->request->is('post') || $this->request->is('put')) {
            $country_id = $this->data['Report']['country_id'];
        }




        $this->TravelCity->bindModel(array(
            'hasMany' => array(
                'TravelHotelRoomSupplier' => array(
                    'className' => 'TravelHotelRoomSupplier',
                    'foreignKey' => 'hotel_city_id',
                    'fields' => 'TravelHotelRoomSupplier.id',
                    'conditions' => array('TravelHotelRoomSupplier.hotel_country_id' => $country_id)  // 1 for client table of  lookup_value_activity_levels
                ),
                'TravelCitySuppliers' => array(
                    'className' => 'TravelCitySuppliers',
                    'foreignKey' => 'city_id',
                    'fields' => 'TravelCitySuppliers.id',
                    'conditions' => array('TravelCitySuppliers.city_country_id' => $country_id)  // 1 for client table of  lookup_value_activity_levels
                ),
                'TravelHotelLookup' => array(
                    'className' => 'TravelHotelLookup',
                    'foreignKey' => 'city_id',
                    'fields' => 'TravelHotelLookup.id',
                    'conditions' => array('TravelHotelLookup.country_id' => $country_id)  // 1 for client table of  lookup_value_activity_levelsv
                ),
                'TravelSuburb' => array(
                    'className' => 'TravelSuburb',
                    'foreignKey' => 'city_id',
                    'fields' => 'TravelSuburb.id',
                    'conditions' => array('TravelSuburb.country_id' => $country_id)  // 1 for client table of  lookup_value_activity_levelsv
                ),
                'TravelArea' => array(
                    'className' => 'TravelArea',
                    'foreignKey' => 'city_id',
                    'fields' => 'TravelArea.id',
                    'conditions' => array('TravelArea.country_id' => $country_id)  // 1 for client table of  lookup_value_activity_levelsv
                ),
            ),
        ));



        $TravelCities = $this->TravelCity->find('all', array(
            'conditions' => array('TravelCity.country_id' => $country_id),
            'fields' => array('id', 'city_name','country_name','city_code','province_name'),
            'order' => 'city_name ASC'
        ));
        
       // pr($TravelCities);
        
        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'order' => 'country_name ASC'));

        $this->set(compact('TravelCities','TravelCountries','country_id'));
    }

    function delete($id = null) {

        if ($this->TravelCity->delete($id)) {
            $this->Session->setFlash('City has been deleted.', 'success');
            $this->redirect(array('action' => 'reports'));
        } else {
            $this->Session->setFlash('Unable to delete City.', 'failure');
            $this->redirect(array('action' => 'reports'));
        }
    }
    
    public function mismatch_hotel() {
        
        $TravelCities = array();
        $country_id = '220';

        if ($this->request->is('post') || $this->request->is('put')) {
            $country_id = $this->data['Report']['country_id'];
        }
        
         
                
        
        $this->TravelHotelLookup->unbindModel(
                array('hasMany' => array('TravelHotelRoomSupplier'))
            );
        

        //SELECT `city_name`,count(`id`) FROM `travel_hotel_lookups` WHERE `country_id`='220' and `city_id` not in(select id from travel_cities where country_id='220') group by `city_id` 
//$TravelHotelLookups = $this->TravelHotelLookup->query("SELECT `city_name`,count(`id`) FROM `travel_hotel_lookups` WHERE `country_id`='220' and `city_id` not in(select id from travel_cities where country_id='220') group by `city_id` ");
  
$TravelHotelLookups = $this->TravelHotelLookup->find
                    (
                    'all', array
                (
                'fields' => array('TravelHotelLookup.city_name','TravelHotelLookup.city_id','TravelHotelLookup.city_code','TravelCity.city_name','TravelCity.country_name','TravelCity.continent_name','TravelHotelLookup.country_name','COUNT(TravelHotelLookup.city_id) AS cnt'),
                'joins' => array(
                array(
                    'table' => 'travel_cities',
                    'alias' => 'TravelCity',
                    'conditions' => array(
                        'TravelCity.id = TravelHotelLookup.city_id'
                    )
                )
            ),
                        
             'conditions' => array
                    (
                    'TravelHotelLookup.city_id NOT IN (SELECT id FROM travel_cities WHERE country_id = "'.$country_id.'")',
                    'TravelHotelLookup.country_id' => $country_id
                ),
                'group' => 'TravelHotelLookup.city_id',        
                'order' => 'TravelHotelLookup.city_name ASC'
                    )
            ); 
       
//pr($TravelHotelLookups);
        //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);       
        //debug($log);
       //pr($TravelHotelLookups);
       //die;
           /*     
       $TravelCities = $this->TravelCity->find('all', array(
            'conditions' => array('TravelCity.country_id' => $country_id),
            'fields' => array('id', 'city_name','country_name','city_code')
        ));
            * 
            */
        
        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'order' => 'country_name ASC'));

        $this->set(compact('TravelCountries','country_id','TravelHotelLookups'));
    }
    
    public function hotel_summary($city_id = null) {
        

       // $city_id = $this->Auth->user('city_id');
        $user_id = $this->Auth->user('id');
        $search_condition = array();
        $hotel_name = '';
        $continent_id = '';
        $country_id = '';
       // $city_id = '';
        $suburb_id = '';
        $area_id = '';
        $chain_id = '';
        $brand_id = '';
        $status = '';
        $wtb_status = '';
        $active = '';
        $province_id = '';
        $TravelCities = array();
        $TravelCountries = array();
        $TravelSuburbs = array();
        $TravelAreas = array();
        $TravelChains = array();
        $TravelBrands = array();
        $Provinces = array();


        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->request);
            //die;
            if (!empty($this->data['TravelHotelLookup']['hotel_name'])) {
                $hotel_name = $this->data['TravelHotelLookup']['hotel_name'];
                array_push($search_condition, array('OR' => array('TravelHotelLookup.id' . ' LIKE' => $hotel_name,'TravelHotelLookup.hotel_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.hotel_code' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.country_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.city_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.area_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%")));
            }
            if (!empty($this->data['TravelHotelLookup']['continent_id'])) {
                $continent_id = $this->data['TravelHotelLookup']['continent_id'];
                array_push($search_condition, array('TravelHotelLookup.continent_id' => $continent_id));
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id, country_name', 'conditions' => array('TravelCountry.continent_id' => $continent_id,
                        'TravelCountry.country_status' => '1',
                        'TravelCountry.wtb_status' => '1',
                        'TravelCountry.active' => 'TRUE'), 'order' => 'country_name ASC'));
            }

            if (!empty($this->data['TravelHotelLookup']['country_id'])) {
                $country_id = $this->data['TravelHotelLookup']['country_id'];
                $province_id = $this->data['TravelHotelLookup']['province_id'];
                array_push($search_condition, array('TravelHotelLookup.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id, city_name', 'conditions' => array('TravelCity.province_id' => $province_id,
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE',), 'order' => 'city_name ASC'));
                
                
            }
            if (!empty($this->data['TravelHotelLookup']['province_id'])) {
                
                array_push($search_condition, array('TravelHotelLookup.province_id' => $province_id));
                $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $country_id,
                    'Province.continent_id' => $continent_id,
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE',
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));
            }
            if (!empty($this->data['TravelHotelLookup']['city_id'])) {
                $city_id = $this->data['TravelHotelLookup']['city_id'];
                array_push($search_condition, array('TravelHotelLookup.city_id' => $city_id));
                $TravelSuburbs = $this->TravelSuburb->find('list', array(
                    'conditions' => array(
                        'TravelSuburb.country_id' => $country_id,
                        'TravelSuburb.city_id' => $city_id,
                        'TravelSuburb.status' => '1',
                        'TravelSuburb.wtb_status' => '1',
                        'TravelSuburb.active' => 'TRUE'
                    ),
                    'fields' => 'TravelSuburb.id, TravelSuburb.name',
                    'order' => 'TravelSuburb.name ASC'
                ));
            }
            if (!empty($this->data['TravelHotelLookup']['suburb_id'])) {
                $suburb_id = $this->data['TravelHotelLookup']['suburb_id'];
                array_push($search_condition, array('TravelHotelLookup.suburb_id' => $suburb_id));
                $TravelAreas = $this->TravelArea->find('list', array(
                    'conditions' => array(
                        'TravelArea.suburb_id' => $suburb_id,
                        'TravelArea.area_status' => '1',
                        'TravelArea.wtb_status' => '1',
                        'TravelArea.area_active' => 'TRUE'
                    ),
                    'fields' => 'TravelArea.id, TravelArea.area_name',
                    'order' => 'TravelArea.area_name ASC'
                ));
            }


            if (!empty($this->data['TravelHotelLookup']['area_id'])) {
                $area_id = $this->data['TravelHotelLookup']['area_id'];
                array_push($search_condition, array('TravelHotelLookup.area_id' => $area_id));
            }
            if (!empty($this->data['TravelHotelLookup']['chain_id'])) {
                $chain_id = $this->data['TravelHotelLookup']['chain_id'];
                array_push($search_condition, array('TravelHotelLookup.chain_id' => $chain_id));
                $TravelBrands = $this->TravelBrand->find('list', array(
                    'conditions' => array(
                        'TravelBrand.brand_chain_id' => $chain_id,
                        'TravelBrand.brand_status' => '1',
                        'TravelBrand.wtb_status' => '1',
                        'TravelBrand.brand_active' => 'TRUE'
                    ),
                    'fields' => 'TravelBrand.id, TravelBrand.brand_name',
                    'order' => 'TravelBrand.brand_name ASC'
                ));
                $TravelBrands = array('1' => 'No Brand') + $TravelBrands;
            }
            if (!empty($this->data['TravelHotelLookup']['brand_id'])) {
                $brand_id = $this->data['TravelHotelLookup']['brand_id'];
                array_push($search_condition, array('TravelHotelLookup.brand_id' => $brand_id));
            }
            if (!empty($this->data['TravelHotelLookup']['status'])) {
                $status = $this->data['TravelHotelLookup']['status'];
                array_push($search_condition, array('TravelHotelLookup.status' => $status));
            }
            if (!empty($this->data['TravelHotelLookup']['wtb_status'])) {
                $wtb_status = $this->data['TravelHotelLookup']['wtb_status'];
                array_push($search_condition, array('TravelHotelLookup.wtb_status' => $wtb_status));
            }
            if (!empty($this->data['TravelHotelLookup']['active'])) {
                $active = $this->data['TravelHotelLookup']['active'];
                array_push($search_condition, array('TravelHotelLookup.active' => $active));
            }
        } elseif ($this->request->is('get')) {

            if (!empty($this->request->params['named']['hotel_name'])) {
                $hotel_name = $this->request->params['named']['hotel_name'];
                array_push($search_condition, array('OR' => array('TravelHotelLookup.hotel_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.hotel_code' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.country_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.city_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%", 'TravelHotelLookup.area_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_name))) . "%")));
            }

            if (!empty($this->request->params['named']['continent_id'])) {
                $continent_id = $this->request->params['named']['continent_id'];
                array_push($search_condition, array('TravelHotelLookup.continent_id' => $continent_id));
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id, country_name', 'conditions' => array('TravelCountry.continent_id' => $continent_id,
                        'TravelCountry.country_status' => '1',
                        'TravelCountry.wtb_status' => '1',
                        'TravelCountry.active' => 'TRUE'), 'order' => 'country_name ASC'));
            }

            if (!empty($this->request->params['named']['country_id'])) {
                $country_id = $this->request->params['named']['country_id'];
                $province_id = $this->request->params['named']['province_id'];
                array_push($search_condition, array('TravelHotelLookup.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id, city_name', 'conditions' => array('TravelCity.province_id' => $province_id,
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE',), 'order' => 'city_name ASC'));
            }
            if (!empty($this->request->params['named']['province_id'])) {
                
                array_push($search_condition, array('TravelHotelLookup.province_id' => $province_id));
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

            if (!empty($this->request->params['named']['city_id'])) {
                $city_id = $this->request->params['named']['city_id'];
                array_push($search_condition, array('TravelHotelLookup.city_id' => $city_id));
                $TravelSuburbs = $this->TravelSuburb->find('list', array(
                    'conditions' => array(
                        'TravelSuburb.country_id' => $country_id,
                        'TravelSuburb.city_id' => $city_id,
                        'TravelSuburb.status' => '1',
                        'TravelSuburb.wtb_status' => '1',
                        'TravelSuburb.active' => 'TRUE'
                    ),
                    'fields' => 'TravelSuburb.id, TravelSuburb.name',
                    'order' => 'TravelSuburb.name ASC'
                ));
            }

            if (!empty($this->request->params['named']['suburb_id'])) {
                $suburb_id = $this->request->params['named']['suburb_id'];
                array_push($search_condition, array('TravelHotelLookup.suburb_id' => $suburb_id));
                $TravelAreas = $this->TravelArea->find('list', array(
                    'conditions' => array(
                        'TravelArea.suburb_id' => $suburb_id,
                        'TravelArea.area_status' => '1',
                        'TravelArea.wtb_status' => '1',
                        'TravelArea.area_active' => 'TRUE'
                    ),
                    'fields' => 'TravelArea.id, TravelArea.area_name',
                    'order' => 'TravelArea.area_name ASC'
                ));
            }
            if (!empty($this->request->params['named']['area_id'])) {
                $area_id = $this->request->params['named']['area_id'];
                array_push($search_condition, array('TravelHotelLookup.area_id' => $area_id));
            }
            if (!empty($this->request->params['named']['chain_id'])) {
                $chain_id = $this->request->params['named']['chain_id'];
                array_push($search_condition, array('TravelHotelLookup.chain_id' => $chain_id));
                $TravelBrands = $this->TravelBrand->find('list', array(
                    'conditions' => array(
                        'TravelBrand.brand_chain_id' => $chain_id,
                        'TravelBrand.brand_status' => '1',
                        'TravelBrand.wtb_status' => '1',
                        'TravelBrand.brand_active' => 'TRUE'
                    ),
                    'fields' => 'TravelBrand.id, TravelBrand.brand_name',
                    'order' => 'TravelBrand.brand_name ASC'
                ));
                $TravelBrands = array('1' => 'No Brand') + $TravelBrands;
            }
            if (!empty($this->request->params['named']['brand_id'])) {
                $brand_id = $this->request->params['named']['brand_id'];
                array_push($search_condition, array('TravelHotelLookup.brand_id' => $brand_id));
            }
            if (!empty($this->request->params['named']['status'])) {
                $status = $this->request->params['named']['status'];
                array_push($search_condition, array('TravelHotelLookup.status' => $status));
            }
            if (!empty($this->request->params['named']['wtb_status'])) {
                $wtb_status = $this->request->params['named']['wtb_status'];
                array_push($search_condition, array('TravelHotelLookup.wtb_status' => $wtb_status));
            }
            if (!empty($this->request->params['named']['active'])) {
                $active = $this->request->params['named']['active'];
                array_push($search_condition, array('TravelHotelLookup.active' => $active));
            }
        }


        if($city_id)
          array_push($search_condition, array('TravelHotelLookup.city_id' => $city_id)); 


/*
        if (count($this->params['pass'])) {


            $aaray = explode(':', $this->params['pass'][0]);
            $field = $aaray[0];
            $value = $aaray[1];
            array_push($search_condition, array('TravelHotelLookup.' . $field . ' LIKE' => '%' . $value . '%')); // when builder is approve/pending                 
        }
        
          elseif(count($this->params['named'])){
          foreach($this->params['named'] as $key=>$val){
          array_push($search_condition, array('TravelHotelLookup.' .$key.' LIKE' => '%'.$val.'%')); // when builder is approve/pending
          }
          }
         * 
         */
        array_push($search_condition, array('TravelHotelLookup.country_id' => '220'));

        $this->paginate['order'] = array('TravelHotelLookup.city_code' => 'asc');
        $this->set('TravelHotelLookups', $this->paginate("TravelHotelLookup", $search_condition));

        //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);       
        //debug($log);
        //die;



        if (!isset($this->passedArgs['hotel_name']) && empty($this->passedArgs['hotel_name'])) {
            $this->passedArgs['hotel_name'] = (isset($this->data['TravelHotelLookup']['hotel_name'])) ? $this->data['TravelHotelLookup']['hotel_name'] : '';
        }
        if (!isset($this->passedArgs['continent_id']) && empty($this->passedArgs['continent_id'])) {
            $this->passedArgs['continent_id'] = (isset($this->data['TravelHotelLookup']['continent_id'])) ? $this->data['TravelHotelLookup']['continent_id'] : '';
        }
        if (!isset($this->passedArgs['country_id']) && empty($this->passedArgs['country_id'])) {
            $this->passedArgs['country_id'] = (isset($this->data['TravelHotelLookup']['country_id'])) ? $this->data['TravelHotelLookup']['country_id'] : '';
        }
        if (!isset($this->passedArgs['province_id']) && empty($this->passedArgs['province_id'])) {
            $this->passedArgs['province_id'] = (isset($this->data['TravelHotelLookup']['province_id'])) ? $this->data['TravelHotelLookup']['province_id'] : '';
        }
        if (!isset($this->passedArgs['city_id']) && empty($this->passedArgs['city_id'])) {
            $this->passedArgs['city_id'] = (isset($this->data['TravelHotelLookup']['city_id'])) ? $this->data['TravelHotelLookup']['city_id'] : '';
        }
        if (!isset($this->passedArgs['suburb_id']) && empty($this->passedArgs['suburb_id'])) {
            $this->passedArgs['suburb_id'] = (isset($this->data['TravelHotelLookup']['suburb_id'])) ? $this->data['TravelHotelLookup']['suburb_id'] : '';
        }
        if (!isset($this->passedArgs['area_id']) && empty($this->passedArgs['area_id'])) {
            $this->passedArgs['area_id'] = (isset($this->data['TravelHotelLookup']['area_id'])) ? $this->data['TravelHotelLookup']['area_id'] : '';
        }
        if (!isset($this->passedArgs['chain_id']) && empty($this->passedArgs['chain_id'])) {
            $this->passedArgs['chain_id'] = (isset($this->data['TravelHotelLookup']['chain_id'])) ? $this->data['TravelHotelLookup']['chain_id'] : '';
        }
        if (!isset($this->passedArgs['brand_id']) && empty($this->passedArgs['brand_id'])) {
            $this->passedArgs['brand_id'] = (isset($this->data['TravelHotelLookup']['brand_id'])) ? $this->data['TravelHotelLookup']['brand_id'] : '';
        }
        if (!isset($this->passedArgs['status']) && empty($this->passedArgs['status'])) {
            $this->passedArgs['status'] = (isset($this->data['TravelHotelLookup']['status'])) ? $this->data['TravelHotelLookup']['status'] : '';
        }
        if (!isset($this->passedArgs['wtb_status']) && empty($this->passedArgs['wtb_status'])) {
            $this->passedArgs['wtb_status'] = (isset($this->data['TravelHotelLookup']['wtb_status'])) ? $this->data['TravelHotelLookup']['wtb_status'] : '';
        }
        if (!isset($this->passedArgs['active']) && empty($this->passedArgs['active'])) {
            $this->passedArgs['active'] = (isset($this->data['TravelHotelLookup']['active'])) ? $this->data['TravelHotelLookup']['active'] : '';
        }



        if (!isset($this->data) && empty($this->data)) {
            $this->data['TravelHotelLookup']['hotel_name'] = $this->passedArgs['hotel_name'];
            $this->data['TravelHotelLookup']['continent_id'] = $this->passedArgs['continent_id'];
            $this->data['TravelHotelLookup']['country_id'] = $this->passedArgs['country_id'];
            $this->data['TravelHotelLookup']['province_id'] = $this->passedArgs['province_id'];
            $this->data['TravelHotelLookup']['city_id'] = $this->passedArgs['city_id'];
            $this->data['TravelHotelLookup']['suburb_id'] = $this->passedArgs['suburb_id'];
            $this->data['TravelHotelLookup']['area_id'] = $this->passedArgs['area_id'];
            $this->data['TravelHotelLookup']['chain_id'] = $this->passedArgs['chain_id'];
            $this->data['TravelHotelLookup']['brand_id'] = $this->passedArgs['brand_id'];
            $this->data['TravelHotelLookup']['status'] = $this->passedArgs['status'];
            $this->data['TravelHotelLookup']['wtb_status'] = $this->passedArgs['wtb_status'];
            $this->data['TravelHotelLookup']['active'] = $this->passedArgs['active'];
        }
        
        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $TravelLookupValueContractStatuses = $this->TravelLookupValueContractStatus->find('list', array('fields' => 'id, value', 'order' => 'value ASC'));
        $TravelChains = $this->TravelChain->find('list', array('fields' => 'id,chain_name', 'conditions' => array('chain_status' => 1, 'wtb_status' => 1, 'chain_active' => 'TRUE', array('NOT' => array('id' => 1))), 'order' => 'chain_name ASC'));
        $TravelChains = array('1' => 'No Chain') + $TravelChains;

        $this->set(compact('hotel_name', 'continent_id', 'country_id', 'city_id', 'suburb_id', 'area_id', 'TravelChains', 'status', 'active', 'chain_id', 'brand_id', 'wtb_status', 'TravelCountries', 'TravelCities', 'TravelSuburbs', 'TravelAreas', 'TravelChains', 'TravelBrands', 'TravelLookupValueContractStatuses', 'TravelLookupContinents', 'Provinces','province_id'));
    }
    
    public function hotel_delete() {
        
        $flag = 0;
        foreach ($this->data['TravelHotelLookup']['check'] as $val) {
            if ($this->TravelHotelLookup->delete($val)) {
                $flag = 1;
            }
        }
        $flag ? $this->Session->setFlash('Hotel record has been successfully deleted.', 'success') : $this->Session->setFlash('Unable to delete Hotel.', 'failure');
                       
        return $this->redirect(array('controller' => 'reports', 'action' => 'hotel_summary'));
    }
    
    public function city_delete() {
        
        $flag = 0;
        foreach ($this->data['TravelCity']['check'] as $val) {
            if ($this->TravelCity->delete($val)) {
                $flag = 1;
            }
        }
        $flag ? $this->Session->setFlash('City record has been successfully deleted.', 'success') : $this->Session->setFlash('Unable to delete City.', 'failure');
                       
        return $this->redirect(array('controller' => 'reports', 'action' => 'reports'));
    }
    
    public function hotel_mapping_delete() {
        
        $flag = 0;
        foreach ($this->data['TravelHotelRoomSupplier']['check'] as $val) {
            if ($this->TravelHotelRoomSupplier->delete($val)) {
                $this->Mappinge->deleteAll(array('Mappinge.hotel_supplier_id' => $val), false);                
                $flag = 1;
            }
        }
        $flag ? $this->Session->setFlash('Hotel mapping record has been successfully deleted.', 'success') : $this->Session->setFlash('Unable to delete City.', 'failure');
                       
        return $this->redirect(array('controller' => 'reports', 'action' => 'hotel_mapping_list'));
    }
    public function city_mapping_delete() {
        
        $flag = 0;
        foreach ($this->data['TravelCitySupplier']['check'] as $val) {
            if ($this->TravelCitySupplier->delete($val)) {
                $this->Mappinge->deleteAll(array('Mappinge.city_supplier_id' => $val), false);                
                $flag = 1;
            }
        }
        $flag ? $this->Session->setFlash('City mapping record has been successfully deleted.', 'success') : $this->Session->setFlash('Unable to delete City.', 'failure');
                       
        return $this->redirect(array('controller' => 'reports', 'action' => 'city_mapping_list'));
    }
    
    public function suburb_delete() {
        
        $flag = 0;
        foreach ($this->data['TravelSuburb']['check'] as $val) {
            if ($this->TravelSuburb->delete($val)) {
                $flag = 1;
            }
        }
        $flag ? $this->Session->setFlash('Suburb record has been successfully deleted.', 'success') : $this->Session->setFlash('Unable to delete suburb.', 'failure');
                       
        return $this->redirect(array('controller' => 'reports', 'action' => 'suburb_list'));
    }
    
     public function area_delete() {
        
        $flag = 0;
        foreach ($this->data['TravelArea']['check'] as $val) {
            if ($this->TravelArea->delete($val)) {
                $flag = 1;
            }
        }
        $flag ? $this->Session->setFlash('Area record has been successfully deleted.', 'success') : $this->Session->setFlash('Unable to delete area.', 'failure');
                       
        return $this->redirect(array('controller' => 'reports', 'action' => 'area_list'));
    }
    
    public function hotel_mapping_list($city_id = null) {
        
        $dummy_status = $this->Auth->user('dummy_status');
        $role_id = $this->Session->read("role_id");
        $search_condition = array();
       

        $search = '';
        $supplier_code = '';
        $country_wtb_code = '';
        $city_wtb_code = '';
        $hotel_wtb_code = '';
        $status = '';
        $active = '';
        $exclude = '';
        $mapping_type = '';
        $wtb_status = '';
        $province_id = '';
        $TravelCities = array();
        $TravelHotelLookups = array();
        $Provinces = array();
        

        if ($this->request->is('post') || $this->request->is('put')) {
            
            if (!empty($this->data['TravelHotelRoomSupplier']['search'])) {
                $search = $this->data['TravelHotelRoomSupplier']['search'];
                array_push($search_condition, array( 'TravelHotelRoomSupplier.city_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%"));
                
            }
            if (!empty($this->data['TravelHotelRoomSupplier']['active'])) {
                $active = $this->data['TravelHotelRoomSupplier']['active'];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.active' => $active, 'TravelHotelRoomSupplier.active' => $active, 'TravelHotelRoomSupplier.active' => $active)));
                
            }
            if (!empty($this->data['TravelHotelRoomSupplier']['wtb_status'])) {
                $wtb_status = $this->data['TravelHotelRoomSupplier']['wtb_status'];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.wtb_status' => $wtb_status, 'TravelHotelRoomSupplier.wtb_status' => $wtb_status, 'TravelHotelRoomSupplier.wtb_status' => $wtb_status)));
                
            }

            if (!empty($this->data['TravelHotelRoomSupplier']['supplier_code'])) {
                $supplier_code = $this->data['TravelHotelRoomSupplier']['supplier_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.supplier_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($supplier_code))) . "%"));
            }
 
            if (!empty($this->data['TravelHotelRoomSupplier']['country_wtb_code'])) {
                $country_wtb_code = $this->data['TravelHotelRoomSupplier']['country_wtb_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.country_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($country_wtb_code))) . "%"));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'city_code, city_name', 'conditions' => array('TravelCity.country_code LIKE ' => '%' . trim($this->data['TravelHotelRoomSupplier']['country_wtb_code']) . '%',
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE'), 'order' => 'city_name ASC'));
                
               // $TravelCities = $this->TravelCity->find('list', array('fields' => 'city_code, city_name', 'conditions' => array('country_code LIKE ' => '%' . trim($this->data['TravelHotelRoomSupplier']['country_wtb_code']) . '%', 'city_status' => '0'), 'order' => 'city_name ASC'));
            }
            if (!empty($this->data['TravelHotelRoomSupplier']['city_wtb_code'])) {
                $city_wtb_code = $this->data['TravelHotelRoomSupplier']['city_wtb_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.city_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($city_wtb_code))) . "%"));
                $TravelHotelLookups = $this->TravelHotelLookup->find('list', array('fields' => 'hotel_code, hotel_name', 'conditions' => array('city_code LIKE' => '%' . trim($this->data['TravelHotelRoomSupplier']['city_wtb_code']) . '%', 'active' => 'TRUE'), 'order' => 'hotel_name ASC'));
            }
            if (!empty($this->data['TravelHotelRoomSupplier']['hotel_wtb_code'])) {
                $hotel_wtb_code= $this->data['TravelHotelRoomSupplier']['hotel_wtb_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.hotel_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_wtb_code))) . "%"));
            }
            if (!empty($this->data['TravelHotelRoomSupplier']['province_id'])) {
                $province_id = $this->data['TravelHotelRoomSupplier']['province_id'];
                array_push($search_condition, array('OR' => array( 'TravelHotelRoomSupplier.province_id' =>$province_id, 'TravelHotelRoomSupplier.province_id' => $province_id)));                               
            }

            if (!empty($this->data['TravelHotelRoomSupplier']['status'])) {
                $status = $this->data['TravelHotelRoomSupplier']['status'];
                array_push($search_condition, array('TravelHotelRoomSupplier.status' => $status));
            }
            if (!empty($this->data['TravelHotelRoomSupplier']['exclude'])) {
                $exclude = $this->data['TravelHotelRoomSupplier']['exclude'];
                array_push($search_condition, array('TravelHotelRoomSupplier.exclude' => $exclude));
            }
        } elseif ($this->request->is('get')) {

            if (!empty($this->request->params['named']['search '])) {
                $search = $this->request->params['named']['search '];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.country_name LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%", 'TravelHotelRoomSupplier.city_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%", 'TravelHotelRoomSupplier.hotel_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%")));
            }
            if (!empty($this->request->params['named']['active '])) {
                $active = $this->request->params['named']['active '];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.active' => $active, 'TravelHotelRoomSupplier.active' => $active, 'TravelHotelRoomSupplier.active' => $active)));
            }
            if (!empty($this->request->params['named']['province_id '])) {
                $province_id = $this->request->params['named']['province_id '];
                array_push($search_condition, array('OR' => array( 'TravelHotelRoomSupplier.province_id' =>$province_id, 'TravelHotelRoomSupplier.province_id' => $province_id)));                               
            }
            if (!empty($this->request->params['named']['wtb_status '])) {
                $wtb_status = $this->request->params['named']['wtb_status '];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.wtb_status' => $wtb_status, 'TravelHotelRoomSupplier.wtb_status' => $wtb_status, 'TravelHotelRoomSupplier.wtb_status' => $wtb_status)));
            }

            if (!empty($this->request->params['named']['supplier_code'])) {
                $supplier_code = $this->request->params['named']['supplier_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.supplier_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($supplier_code))) . "%"));
            }
            if (!empty($this->request->params['named']['mapping_type'])) {
                $mapping_type = $this->request->params['named']['mapping_type'];
                array_push($search_condition, array('TravelHotelRoomSupplier.mapping_type' => $mapping_type));
            }
            if (!empty($this->request->params['named']['country_wtb_code'])) {
                $country_wtb_code = $this->request->params['named']['country_wtb_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.country_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($country_wtb_code))) . "%"));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'city_code, city_name', 'conditions' => array('TravelCity.country_code LIKE ' => '%' . trim($country_wtb_code) . '%',
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE'), 'order' => 'city_name ASC'));
            }
            if (!empty($this->request->params['named']['city_wtb_code'])) {
                $city_wtb_code = $this->request->params['named']['city_wtb_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.city_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($city_wtb_code))) . "%"));
                $TravelHotelLookups = $this->TravelHotelLookup->find('list', array('fields' => 'hotel_code, hotel_name', 'conditions' => array('city_code LIKE' => '%' . trim($this->request->params['named']['city_wtb_code']) . '%', 'active' => 'TRUE'), 'order' => 'hotel_name ASC'));
            }
            if (!empty($this->request->params['named']['hotel_wtb_code'])) {
                $hotel_wtb_code = $this->request->params['named']['hotel_wtb_code'];
                array_push($search_condition, array('TravelHotelRoomSupplier.hotel_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($hotel_wtb_code))) . "%"));
            }
            if (!empty($this->request->params['named']['status'])) {
                $status = $this->request->params['named']['status'];
                array_push($search_condition, array('TravelHotelRoomSupplier.status' => $status));
            }
            if (!empty($this->request->params['named']['exclude'])) {
                $exclude = $this->request->params['named']['exclude'];
                array_push($search_condition, array('TravelHotelRoomSupplier.exclude' => $exclude));
            }
        }
        
        if($city_id)
          array_push($search_condition, array('TravelHotelRoomSupplier.hotel_city_id' => $city_id));

        $this->paginate['order'] = array('TravelHotelRoomSupplier.hotel_city_id' => 'asc');
        $this->set('TravelHotelRoomSuppliers', $this->paginate("TravelHotelRoomSupplier", $search_condition));
        
        $TravelSuppliers = $this->TravelSupplier->find('list', array('fields' => 'supplier_code, supplier_name', 'conditions' => array('active' => 'TRUE'), 'order' => 'supplier_name ASC'));
        $this->set(compact('TravelSuppliers'));
        
        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'country_code, country_name', 'conditions' => array(
                        'TravelCountry.country_status' => '1',
                        'TravelCountry.wtb_status' => '1',
                        'TravelCountry.active' => 'TRUE'), 'order' => 'country_name ASC'));

        //$TravelCountries = $this->TravelCountry->find('list', array('fields' => 'country_code, country_name', 'conditions' => array('country_status' => '1'), 'order' => 'country_name ASC'));
        $this->set(compact('TravelCountries'));


        $this->set(compact('TravelCities'));


        $this->set(compact('TravelHotelLookups'));

        $TravelActionItemTypes = $this->TravelActionItemType->find('list', array('fields' => 'id, value', 'order' => 'value ASC'));
        $this->set(compact('TravelActionItemTypes'));

        $hotel_all_count = $this->TravelHotelLookup->find('count');
        $this->set(compact('hotel_all_count'));

        $country_all_count = $this->TravelCountry->find('count');
        $this->set(compact('country_all_count'));

        $city_all_count = $this->TravelCity->find('count');
        $this->set(compact('city_all_count'));


        if (!isset($this->passedArgs['search']) && empty($this->passedArgs['search'])) {
            $this->passedArgs['search'] = (isset($this->data['TravelHotelRoomSupplier']['search'])) ? $this->data['TravelHotelRoomSupplier']['search'] : '';
        }
        if (!isset($this->passedArgs['active']) && empty($this->passedArgs['active'])) {
            $this->passedArgs['active'] = (isset($this->data['TravelHotelRoomSupplier']['active'])) ? $this->data['TravelHotelRoomSupplier']['active'] : '';
        }
        if (!isset($this->passedArgs['wtb_status']) && empty($this->passedArgs['wtb_status'])) {
            $this->passedArgs['wtb_status'] = (isset($this->data['TravelHotelRoomSupplier']['wtb_status'])) ? $this->data['TravelHotelRoomSupplier']['wtb_status'] : '';
        }
        if (!isset($this->passedArgs['supplier_code']) && empty($this->passedArgs['supplier_code'])) {
            $this->passedArgs['supplier_code'] = (isset($this->data['TravelHotelRoomSupplier']['supplier_code'])) ? $this->data['TravelHotelRoomSupplier']['supplier_code'] : '';
        }
        if (!isset($this->passedArgs['mapping_type']) && empty($this->passedArgs['mapping_type'])) {
            $this->passedArgs['mapping_type'] = (isset($this->data['TravelHotelRoomSupplier']['mapping_type'])) ? $this->data['TravelHotelRoomSupplier']['mapping_type'] : '';
        }
        if (!isset($this->passedArgs['country_wtb_code']) && empty($this->passedArgs['country_wtb_code'])) {
            $this->passedArgs['country_wtb_code'] = (isset($this->data['TravelHotelRoomSupplier']['country_wtb_code'])) ? $this->data['TravelHotelRoomSupplier']['country_wtb_code'] : '';
        }
        if (!isset($this->passedArgs['city_wtb_code']) && empty($this->passedArgs['city_wtb_code'])) {
            $this->passedArgs['city_wtb_code'] = (isset($this->data['TravelHotelRoomSupplier']['city_wtb_code'])) ? $this->data['TravelHotelRoomSupplier']['city_wtb_code'] : '';
        }
        if (!isset($this->passedArgs['hotel_wtb_code']) && empty($this->passedArgs['hotel_wtb_code'])) {
            $this->passedArgs['hotel_wtb_code'] = (isset($this->data['TravelHotelRoomSupplier']['hotel_wtb_code'])) ? $this->data['TravelHotelRoomSupplier']['hotel_wtb_code'] : '';
        }
        if (!isset($this->passedArgs['status']) && empty($this->passedArgs['status'])) {
            $this->passedArgs['status'] = (isset($this->data['TravelHotelRoomSupplier']['status'])) ? $this->data['TravelHotelRoomSupplier']['status'] : '';
        }
        if (!isset($this->passedArgs['exclude']) && empty($this->passedArgs['exclude'])) {
            $this->passedArgs['exclude'] = (isset($this->data['TravelHotelRoomSupplier']['exclude'])) ? $this->data['TravelHotelRoomSupplier']['exclude'] : '';
        }
        if (!isset($this->passedArgs['province_id']) && empty($this->passedArgs['province_id'])) {
            $this->passedArgs['province_id'] = (isset($this->data['TravelHotelRoomSupplier']['province_id'])) ? $this->data['TravelHotelRoomSupplier']['province_id'] : '';
        }



        if (!isset($this->data) && empty($this->data)) {
            $this->data['TravelHotelRoomSupplier']['search'] = $this->passedArgs['search'];
            $this->data['TravelHotelRoomSupplier']['active'] = $this->passedArgs['active'];
            $this->data['TravelHotelRoomSupplier']['wtb_status'] = $this->passedArgs['wtb_status'];
            $this->data['TravelHotelRoomSupplier']['supplier_code'] = $this->passedArgs['supplier_code'];
            $this->data['TravelHotelRoomSupplier']['mapping_type'] = $this->passedArgs['mapping_type'];
            $this->data['TravelHotelRoomSupplier']['country_wtb_code'] = $this->passedArgs['country_wtb_code'];
            $this->data['TravelHotelRoomSupplier']['city_wtb_code'] = $this->passedArgs['city_wtb_code'];
            $this->data['TravelHotelRoomSupplier']['hotel_wtb_code'] = $this->passedArgs['hotel_wtb_code'];
            $this->data['TravelHotelRoomSupplier']['status'] = $this->passedArgs['status'];
            $this->data['TravelHotelRoomSupplier']['exclude'] = $this->passedArgs['exclude'];
            $this->data['TravelHotelRoomSupplier']['province_id'] = $this->passedArgs['province_id'];
        }
        
        
        $TravelMappingTypes = $this->TravelMappingType->find('list', array('fields' => 'id, value', 'order' => 'value ASC'));

        $this->set(compact('search','supplier_code','country_wtb_code','wtb_status','city_wtb_code','active','hotel_wtb_code','status','exclude','TravelMappingTypes','mapping_type','province_id','Provinces'));
    }
    
    public function city_mapping_list($city_id = null) {
        
         $dummy_status = $this->Auth->user('dummy_status');
        $role_id = $this->Session->read("role_id");
        $search_condition = array();
       

        $search = '';
        $supplier_code = '';
        $country_wtb_code = '';
        $city_wtb_code = '';
        $hotel_wtb_code = '';
        $status = '';
        $active = '';
        $exclude = '';
        $mapping_type = '';
        $wtb_status = '';
        $province_id = '';
        $TravelCities = array();
        $TravelHotelLookups = array();
        $Provinces = array();
        

        if ($this->request->is('post') || $this->request->is('put')) {
            
            if (!empty($this->data['TravelCitySupplier']['search'])) {
                $search = $this->data['TravelCitySupplier']['search'];
                array_push($search_condition, array( 'TravelCitySupplier.city_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%"));
                
            }
            if (!empty($this->data['TravelCitySupplier']['active'])) {
                $active = $this->data['TravelCitySupplier']['active'];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.active' => $active, 'TravelCitySupplier.active' => $active, 'TravelHotelRoomSupplier.active' => $active)));
                
            }
            if (!empty($this->data['TravelCitySupplier']['wtb_status'])) {
                $wtb_status = $this->data['TravelCitySupplier']['wtb_status'];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.wtb_status' => $wtb_status, 'TravelCitySupplier.wtb_status' => $wtb_status, 'TravelHotelRoomSupplier.wtb_status' => $wtb_status)));
                
            }

            if (!empty($this->data['TravelCitySupplier']['supplier_code'])) {
                $supplier_code = $this->data['TravelCitySupplier']['supplier_code'];
                array_push($search_condition, array('TravelCitySupplier.supplier_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($supplier_code))) . "%"));
            }
 
            if (!empty($this->data['TravelCitySupplier']['country_wtb_code'])) {
                $country_wtb_code = $this->data['TravelCitySupplier']['country_wtb_code'];
                array_push($search_condition, array('TravelCitySupplier.country_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($country_wtb_code))) . "%"));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'city_code, city_name', 'conditions' => array('TravelCity.country_code LIKE ' => '%' . trim($this->data['TravelCitySupplier']['country_wtb_code']) . '%',
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE'), 'order' => 'city_name ASC'));
                
               // $TravelCities = $this->TravelCity->find('list', array('fields' => 'city_code, city_name', 'conditions' => array('country_code LIKE ' => '%' . trim($this->data['TravelCitySupplier']['country_wtb_code']) . '%', 'city_status' => '0'), 'order' => 'city_name ASC'));
            }
            if (!empty($this->data['TravelCitySupplier']['city_wtb_code'])) {
                $city_wtb_code = $this->data['TravelCitySupplier']['city_wtb_code'];
                array_push($search_condition, array('TravelCitySupplier.city_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($city_wtb_code))) . "%"));
                $TravelHotelLookups = $this->TravelHotelLookup->find('list', array('fields' => 'hotel_code, hotel_name', 'conditions' => array('city_code LIKE' => '%' . trim($this->data['TravelCitySupplier']['city_wtb_code']) . '%', 'active' => 'TRUE'), 'order' => 'hotel_name ASC'));
            }
        
            if (!empty($this->data['TravelCitySupplier']['province_id'])) {
                $province_id = $this->data['TravelCitySupplier']['province_id'];
                array_push($search_condition, array('OR' => array( 'TravelCitySupplier.province_id' =>$province_id, 'TravelHotelRoomSupplier.province_id' => $province_id)));                               
            }

            if (!empty($this->data['TravelCitySupplier']['status'])) {
                $status = $this->data['TravelCitySupplier']['status'];
                array_push($search_condition, array('TravelCitySupplier.status' => $status));
            }
            if (!empty($this->data['TravelCitySupplier']['exclude'])) {
                $exclude = $this->data['TravelCitySupplier']['exclude'];
                array_push($search_condition, array('TravelCitySupplier.exclude' => $exclude));
            }
        } elseif ($this->request->is('get')) {

            if (!empty($this->request->params['named']['search '])) {
                $search = $this->request->params['named']['search '];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.country_name LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%", 'TravelCitySupplier.city_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%", 'TravelHotelRoomSupplier.hotel_name' . ' LIKE' => "%" . mysql_escape_string(trim(strip_tags($search))) . "%")));
            }
            if (!empty($this->request->params['named']['active '])) {
                $active = $this->request->params['named']['active '];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.active' => $active, 'TravelCitySupplier.active' => $active, 'TravelHotelRoomSupplier.active' => $active)));
            }
            if (!empty($this->request->params['named']['province_id '])) {
                $province_id = $this->request->params['named']['province_id '];
                array_push($search_condition, array('OR' => array( 'TravelCitySupplier.province_id' =>$province_id, 'TravelHotelRoomSupplier.province_id' => $province_id)));                               
            }
            if (!empty($this->request->params['named']['wtb_status '])) {
                $wtb_status = $this->request->params['named']['wtb_status '];
                array_push($search_condition, array('OR' => array('TravelCountrySupplier.wtb_status' => $wtb_status, 'TravelCitySupplier.wtb_status' => $wtb_status, 'TravelHotelRoomSupplier.wtb_status' => $wtb_status)));
            }

            if (!empty($this->request->params['named']['supplier_code'])) {
                $supplier_code = $this->request->params['named']['supplier_code'];
                array_push($search_condition, array('TravelCitySupplier.supplier_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($supplier_code))) . "%"));
            }

            if (!empty($this->request->params['named']['country_wtb_code'])) {
                $country_wtb_code = $this->request->params['named']['country_wtb_code'];
                array_push($search_condition, array('TravelCitySupplier.country_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($country_wtb_code))) . "%"));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'city_code, city_name', 'conditions' => array('TravelCity.country_code LIKE ' => '%' . trim($country_wtb_code) . '%',
                        'TravelCity.city_status' => '1',
                        'TravelCity.wtb_status' => '1',
                        'TravelCity.active' => 'TRUE'), 'order' => 'city_name ASC'));
            }
            if (!empty($this->request->params['named']['city_wtb_code'])) {
                $city_wtb_code = $this->request->params['named']['city_wtb_code'];
                array_push($search_condition, array('TravelCitySupplier.city_wtb_code LIKE' => "%" . mysql_escape_string(trim(strip_tags($city_wtb_code))) . "%"));
                $TravelHotelLookups = $this->TravelHotelLookup->find('list', array('fields' => 'hotel_code, hotel_name', 'conditions' => array('city_code LIKE' => '%' . trim($this->request->params['named']['city_wtb_code']) . '%', 'active' => 'TRUE'), 'order' => 'hotel_name ASC'));
            }
  
            if (!empty($this->request->params['named']['status'])) {
                $status = $this->request->params['named']['status'];
                array_push($search_condition, array('TravelCitySupplier.status' => $status));
            }
            if (!empty($this->request->params['named']['exclude'])) {
                $exclude = $this->request->params['named']['exclude'];
                array_push($search_condition, array('TravelCitySupplier.exclude' => $exclude));
            }
        }
        
        if($city_id)
          array_push($search_condition, array('TravelCitySupplier.city_id' => $city_id));

        $this->paginate['order'] = array('TravelCitySupplier.city_id' => 'asc');
        $this->set('TravelCitySuppliers', $this->paginate("TravelCitySupplier", $search_condition));
        
        
        $TravelSuppliers = $this->TravelSupplier->find('list', array('fields' => 'supplier_code, supplier_name', 'conditions' => array('active' => 'TRUE'), 'order' => 'supplier_name ASC'));
        $this->set(compact('TravelSuppliers'));
        
        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'country_code, country_name', 'conditions' => array(
                        'TravelCountry.country_status' => '1',
                        'TravelCountry.wtb_status' => '1',
                        'TravelCountry.active' => 'TRUE'), 'order' => 'country_name ASC'));

        //$TravelCountries = $this->TravelCountry->find('list', array('fields' => 'country_code, country_name', 'conditions' => array('country_status' => '1'), 'order' => 'country_name ASC'));
        $this->set(compact('TravelCountries'));


        $this->set(compact('TravelCities'));



        if (!isset($this->passedArgs['search']) && empty($this->passedArgs['search'])) {
            $this->passedArgs['search'] = (isset($this->data['TravelCitySupplier']['search'])) ? $this->data['TravelCitySupplier']['search'] : '';
        }
        if (!isset($this->passedArgs['active']) && empty($this->passedArgs['active'])) {
            $this->passedArgs['active'] = (isset($this->data['TravelCitySupplier']['active'])) ? $this->data['TravelCitySupplier']['active'] : '';
        }
        if (!isset($this->passedArgs['wtb_status']) && empty($this->passedArgs['wtb_status'])) {
            $this->passedArgs['wtb_status'] = (isset($this->data['TravelCitySupplier']['wtb_status'])) ? $this->data['TravelCitySupplier']['wtb_status'] : '';
        }
        if (!isset($this->passedArgs['supplier_code']) && empty($this->passedArgs['supplier_code'])) {
            $this->passedArgs['supplier_code'] = (isset($this->data['TravelCitySupplier']['supplier_code'])) ? $this->data['TravelCitySupplier']['supplier_code'] : '';
        }
        if (!isset($this->passedArgs['country_wtb_code']) && empty($this->passedArgs['country_wtb_code'])) {
            $this->passedArgs['country_wtb_code'] = (isset($this->data['TravelCitySupplier']['country_wtb_code'])) ? $this->data['TravelCitySupplier']['country_wtb_code'] : '';
        }
        if (!isset($this->passedArgs['city_wtb_code']) && empty($this->passedArgs['city_wtb_code'])) {
            $this->passedArgs['city_wtb_code'] = (isset($this->data['TravelCitySupplier']['city_wtb_code'])) ? $this->data['TravelCitySupplier']['city_wtb_code'] : '';
        }      
        if (!isset($this->passedArgs['status']) && empty($this->passedArgs['status'])) {
            $this->passedArgs['status'] = (isset($this->data['TravelCitySupplier']['status'])) ? $this->data['TravelCitySupplier']['status'] : '';
        }
        if (!isset($this->passedArgs['exclude']) && empty($this->passedArgs['exclude'])) {
            $this->passedArgs['exclude'] = (isset($this->data['TravelCitySupplier']['exclude'])) ? $this->data['TravelCitySupplier']['exclude'] : '';
        }
        if (!isset($this->passedArgs['province_id']) && empty($this->passedArgs['province_id'])) {
            $this->passedArgs['province_id'] = (isset($this->data['TravelCitySupplier']['province_id'])) ? $this->data['TravelCitySupplier']['province_id'] : '';
        }



        if (!isset($this->data) && empty($this->data)) {
            $this->data['TravelCitySupplier']['search'] = $this->passedArgs['search'];
            $this->data['TravelCitySupplier']['active'] = $this->passedArgs['active'];
            $this->data['TravelCitySupplier']['wtb_status'] = $this->passedArgs['wtb_status'];       
            $this->data['TravelCitySupplier']['country_wtb_code'] = $this->passedArgs['country_wtb_code'];
            $this->data['TravelCitySupplier']['city_wtb_code'] = $this->passedArgs['city_wtb_code'];
            $this->data['TravelCitySupplier']['status'] = $this->passedArgs['status'];
            $this->data['TravelCitySupplier']['exclude'] = $this->passedArgs['exclude'];
            $this->data['TravelCitySupplier']['province_id'] = $this->passedArgs['province_id'];
        }

        $this->set(compact('search','supplier_code','country_wtb_code','wtb_status','city_wtb_code','active','hotel_wtb_code','status','exclude','TravelMappingTypes','mapping_type','province_id','Provinces'));

    }
    
    public function suburb_list($post_city_id = null) {

        $dummy_status = $this->Auth->user('dummy_status');
        $condition_dummy_status = array('dummy_status' => $dummy_status);
        $search_condition = array();
        $TravelCities = array();
        $TravelCountries = ARRAY();
        $name = '';
        $city_id = '';
        $country_id = '';
        $continent_id = '';
        $top_neighborhood = '';
        $active = '';
        $status = '';

        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->data['TravelSuburb']['name'])) {
                $name = $this->data['TravelSuburb']['name'];
                array_push($search_condition, array("TravelSuburb.name LIKE '%$name%'"));
            }
            if (!empty($this->data['TravelSuburb']['continent_id'])) {
                $continent_id = $this->data['TravelSuburb']['continent_id'];
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('continent_id' => $continent_id, 'country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));
                array_push($search_condition, array('TravelSuburb.continent_id' => $continent_id));
            }

            if (!empty($this->data['TravelSuburb']['country_id'])) {
                $country_id = $this->data['TravelSuburb']['country_id'];
                array_push($search_condition, array('TravelSuburb.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id,city_name', 'conditions' => array('country_id' => $country_id, 'city_status' => 1, 'active' => 'TRUE'), 'order' => 'city_name ASC'));
            }

            if (!empty($this->data['TravelSuburb']['city_id'])) {
                $city_id = $this->data['TravelSuburb']['city_id'];
                array_push($search_condition, array('TravelSuburb.city_id' => $city_id));
            }
            if (!empty($this->data['TravelSuburb']['top_neighborhood'])) {
                $top_neighborhood = $this->data['TravelSuburb']['top_neighborhood'];
                array_push($search_condition, array('TravelSuburb.top_neighborhood' => $top_neighborhood));
            }
            if (!empty($this->data['TravelSuburb']['active'])) {
                $active = $this->data['TravelSuburb']['active'];
                array_push($search_condition, array('TravelSuburb.active' => $active));
            }
            if (!empty($this->data['TravelSuburb']['status'])) {
                $status = $this->data['TravelSuburb']['status'];
                array_push($search_condition, array('TravelSuburb.status' => $status));
            }
        } elseif ($this->request->is('get')) {

            if (!empty($this->request->params['named']['name'])) {
                $name = $this->request->params['named']['name'];
                array_push($search_condition, array("TravelSuburb.name LIKE '%$name%'"));
            }
            if (!empty($this->request->params['named']['continent_id'])) {
                $continent_id = $this->request->params['named']['continent_id'];
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('continent_id' => $continent_id, 'country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));
                array_push($search_condition, array('TravelSuburb.continent_id' => $continent_id));
            }

            if (!empty($this->request->params['named']['country_id'])) {
                $country_id = $this->request->params['named']['country_id'];
                array_push($search_condition, array('TravelSuburb.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id,city_name', 'conditions' => array('country_id' => $country_id, 'city_status' => 0), 'order' => 'city_name ASC'));
            }

            if (!empty($this->request->params['named']['city_id'])) {
                $city_id = $this->request->params['named']['city_id'];
                array_push($search_condition, array('TravelSuburb.city_id' => $city_id));
            }
            if (!empty($this->request->params['TravelSuburb']['top_neighborhood'])) {
                $top_neighborhood = $this->request->params['TravelSuburb']['top_neighborhood'];
                array_push($search_condition, array('TravelSuburb.top_neighborhood' => $top_neighborhood));
            }
            if (!empty($this->request->params['TravelSuburb']['active'])) {
                $active = $this->request->params['TravelSuburb']['active'];
                array_push($search_condition, array('TravelSuburb.active' => $active));
            }
            if (!empty($this->request->params['TravelSuburb']['status'])) {
                $status = $this->request->params['TravelSuburb']['status'];
                array_push($search_condition, array('TravelSuburb.status' => $status));
            }
        }

        if($post_city_id)
          array_push($search_condition, array('TravelSuburb.city_id' => $post_city_id));

        $this->paginate['order'] = array('TravelSuburb.name' => 'asc');
        $this->set('TravelSuburbs', $this->paginate("TravelSuburb", $search_condition));


        if (!isset($this->passedArgs['name']) && empty($this->passedArgs['name'])) {
            $this->passedArgs['name'] = (isset($this->data['TravelSuburb']['name'])) ? $this->data['TravelSuburb']['name'] : '';
        }
        if (!isset($this->passedArgs['continent_id']) && empty($this->passedArgs['continent_id'])) {
            $this->passedArgs['continent_id'] = (isset($this->data['TravelSuburb']['continent_id'])) ? $this->data['TravelSuburb']['continent_id'] : '';
        }
        if (!isset($this->passedArgs['country_id']) && empty($this->passedArgs['country_id'])) {
            $this->passedArgs['country_id'] = (isset($this->data['TravelSuburb']['country_id'])) ? $this->data['TravelSuburb']['country_id'] : '';
        }
        if (!isset($this->passedArgs['city_id']) && empty($this->passedArgs['city_id'])) {
            $this->passedArgs['city_id'] = (isset($this->data['TravelSuburb']['city_id'])) ? $this->data['TravelSuburb']['city_id'] : '';
        }
        if (!isset($this->passedArgs['top_neighborhood']) && empty($this->passedArgs['top_neighborhood'])) {
            $this->passedArgs['top_neighborhood'] = (isset($this->data['TravelSuburb']['top_neighborhood'])) ? $this->data['TravelSuburb']['top_neighborhood'] : '';
        }
        if (!isset($this->passedArgs['active']) && empty($this->passedArgs['active'])) {
            $this->passedArgs['active'] = (isset($this->data['TravelSuburb']['active'])) ? $this->data['TravelSuburb']['active'] : '';
        }
        if (!isset($this->passedArgs['status']) && empty($this->passedArgs['status'])) {
            $this->passedArgs['status'] = (isset($this->data['TravelSuburb']['status'])) ? $this->data['TravelSuburb']['status'] : '';
        }
        if (!isset($this->data) && empty($this->data)) {

            $this->data['TravelSuburb']['name'] = $this->passedArgs['name'];
            $this->data['TravelSuburb']['continent_id'] = $this->passedArgs['continent_id'];
            $this->data['TravelSuburb']['country_id'] = $this->passedArgs['country_id'];
            $this->data['TravelSuburb']['active'] = $this->passedArgs['active'];
            $this->data['TravelSuburb']['city_id'] = $this->passedArgs['city_id'];
            $this->data['TravelSuburb']['top_neighborhood'] = $this->passedArgs['top_neighborhood'];
            $this->data['TravelSuburb']['status'] = $this->passedArgs['status'];
        }


        $this->set(compact('TravelCountries'));

        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $this->set(compact('TravelLookupContinents'));

        $this->set(compact('TravelCities'));

        $this->set(compact('city_id'));
        $this->set(compact('continent_id'));
        $this->set(compact('country_id'));
        $this->set(compact('name'));
        $this->set(compact('active'));
        $this->set(compact('top_neighborhood'));
        $this->set(compact('status'));
    }
    
    public function area_list($city_id = null) {


        $search_condition = array();
        $TravelCities = array();
        $TravelSuburbs = array();
        $TravelCountries = array();
        $area_name = '';
        $continent_id = '';
        //$city_id = '';
        $country_id = '';
        $suburb_id = '';

        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->data['TravelArea']['area_name'])) {
                $area_name = $this->data['TravelArea']['area_name'];
                array_push($search_condition, array("TravelArea.area_name LIKE '%$area_name%'"));
            }
            if (!empty($this->data['TravelArea']['continent_id'])) {
                $continent_id = $this->data['TravelArea']['continent_id'];
                array_push($search_condition, array('TravelArea.continent_id' => $continent_id));
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('continent_id' => $continent_id, 'country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));
            }

            if (!empty($this->data['TravelArea']['country_id'])) {
                $country_id = $this->data['TravelArea']['country_id'];
                array_push($search_condition, array('TravelArea.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id,city_name', 'conditions' => array('country_id' => $country_id, 'city_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'city_name ASC'));
            }

            if (!empty($this->data['TravelArea']['city_id'])) {
                $city_id = $this->data['TravelArea']['city_id'];
                array_push($search_condition, array('TravelArea.city_id' => $city_id));
                $TravelSuburbs = $this->TravelSuburb->find('list', array('fields' => 'id,name', 'conditions' => array('status' => 1, 'country_id' => $country_id, 'city_id' => $city_id), 'order' => 'name ASC'));
            }
            if (!empty($this->data['TravelArea']['suburb_id'])) {
                $suburb_id = $this->data['TravelArea']['suburb_id'];
                array_push($search_condition, array('TravelArea.suburb_id' => $suburb_id));
            }
        } elseif ($this->request->is('get')) {

            if (!empty($this->request->params['named']['area_name'])) {
                $area_name = $this->request->params['named']['area_name'];
                array_push($search_condition, array("TravelArea.area_name LIKE '%$area_name%'"));
            }

            if (!empty($this->request->params['TravelArea']['continent_id'])) {
                $continent_id = $this->request->params['TravelArea']['continent_id'];
                array_push($search_condition, array('TravelArea.continent_id' => $continent_id));
                $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('continent_id' => $continent_id, 'country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));
            }

            if (!empty($this->request->params['named']['country_id'])) {
                $country_id = $this->request->params['named']['country_id'];
                array_push($search_condition, array('TravelArea.country_id' => $country_id));
                $TravelCities = $this->TravelCity->find('list', array('fields' => 'id,city_name', 'conditions' => array('country_id' => $country_id, 'city_status' => 0), 'order' => 'city_name ASC'));
            }

            if (!empty($this->request->params['named']['city_id'])) {
                $city_id = $this->request->params['named']['city_id'];
                array_push($search_condition, array('TravelArea.city_id' => $city_id));
                $TravelSuburbs = $this->TravelSuburb->find('list', array('fields' => 'id,name', 'conditions' => array('status' => 1, 'country_id' => $country_id, 'city_id' => $city_id), 'order' => 'name ASC'));
            }
            if (!empty($this->request->params['named']['suburb_id'])) {
                $suburb_id = $this->request->params['named']['suburb_id'];
                array_push($search_condition, array('TravelArea.suburb_id' => $suburb_id));
            }
        }
        
        if($city_id)
          array_push($search_condition, array('TravelArea.city_id' => $city_id));


        $this->paginate['order'] = array('TravelArea.name' => 'asc');
        $this->set('TravelAreas', $this->paginate("TravelArea", $search_condition));





        if (!isset($this->passedArgs['area_name']) && empty($this->passedArgs['area_name'])) {
            $this->passedArgs['area_name'] = (isset($this->data['TravelArea']['area_name'])) ? $this->data['TravelArea']['area_name'] : '';
        }
        if (!isset($this->passedArgs['continent_id']) && empty($this->passedArgs['continent_id'])) {
            $this->passedArgs['continent_id'] = (isset($this->data['TravelArea']['continent_id'])) ? $this->data['TravelArea']['continent_id'] : '';
        }
        if (!isset($this->passedArgs['country_id']) && empty($this->passedArgs['country_id'])) {
            $this->passedArgs['country_id'] = (isset($this->data['TravelArea']['country_id'])) ? $this->data['TravelArea']['country_id'] : '';
        }
        if (!isset($this->passedArgs['city_id']) && empty($this->passedArgs['city_id'])) {
            $this->passedArgs['city_id'] = (isset($this->data['TravelArea']['city_id'])) ? $this->data['TravelArea']['city_id'] : '';
        }
        if (!isset($this->passedArgs['suburb_id']) && empty($this->passedArgs['suburb_id'])) {
            $this->passedArgs['suburb_id'] = (isset($this->data['TravelArea']['suburb_id'])) ? $this->data['TravelArea']['suburb_id'] : '';
        }
        if (!isset($this->data) && empty($this->data)) {

            $this->data['TravelArea']['area_name'] = $this->passedArgs['area_name'];
            $this->data['TravelArea']['continent_id'] = $this->passedArgs['continent_id'];
            $this->data['TravelArea']['country_id'] = $this->passedArgs['country_id'];
            $this->data['TravelArea']['city_id'] = $this->passedArgs['city_id'];
            $this->data['TravelArea']['suburb_id'] = $this->passedArgs['suburb_id'];
        }


        $this->set(compact('TravelCountries'));
        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $this->set(compact('TravelLookupContinents'));
        $this->set(compact('TravelSuburbs'));
        $this->set(compact('TravelCities'));

        $this->set(compact('city_id'));
        $this->set(compact('continent_id'));
        $this->set(compact('country_id'));
        $this->set(compact('area_name'));
        $this->set(compact('suburb_id'));
    }
    
    function city_mapping_edit($id = null) {
        
        //$this->layout = '';
        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $log_call_screen = '';
        $xml_msg = '';
        $xml_error = 'FALSE';

        if (!$id) {
            throw new NotFoundException(__('Invalid City Supplier'));
        }

        $TravelCitySuppliers = $this->TravelCitySupplier->findById($id);


        if (!$TravelCitySuppliers) {
            throw new NotFoundException(__('Invalid City Supplier'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            
            $country_code = $TravelCitySuppliers['TravelCountrySupplier']['pf_country_code'];
            $SupplierCode = $TravelCitySuppliers['TravelCountrySupplier']['supplier_code'];
            $SupplierCountryCode = $TravelCitySuppliers['TravelCountrySupplier']['supplier_country_code'];
            $CountryName = $TravelCitySuppliers['TravelCountrySupplier']['country_name'];
            $CountryId = $TravelCitySuppliers['TravelCountrySupplier']['country_id'];
            $CountryContinentId = $TravelCitySuppliers['TravelCountrySupplier']['country_continent_id'];
            $CountryContinentName = $TravelCitySuppliers['TravelCountrySupplier']['country_continent_name'];
            $CountryMappingName = $TravelCitySuppliers['TravelCountrySupplier']['country_mapping_name'];
            $CountrySupplierStatus = $TravelCitySuppliers['TravelCountrySupplier']['country_suppliner_status'];
            $Active = strtolower($TravelCitySuppliers['TravelCountrySupplier']['active']);
            $Excluded = strtolower($TravelCitySuppliers['TravelCountrySupplier']['excluded']);
            $ApprovedBy = $TravelCitySuppliers['TravelCountrySupplier']['approved_by'];
            $CreatedBy = $TravelCitySuppliers['TravelCountrySupplier']['created_by'];
            $app_date = explode(' ', $TravelCitySuppliers['TravelCountrySupplier']['approved_date']);
            $ApprovedDate = $app_date[0] . 'T' . $app_date[1];
            $date = explode(' ', $TravelCitySuppliers['TravelCountrySupplier']['created']);
            $created = $date[0] . 'T' . $date[1];
            $is_update = $TravelCitySuppliers['TravelCountrySupplier']['is_update'];
                    if ($is_update == 'Y')
                        $country_actiontype = 'Update';
                    else
                        $country_actiontype = 'AddNew';
                    
            $WtbStatus = $TravelCitySuppliers['TravelCountrySupplier']['wtb_status'];
                    if ($WtbStatus)
                        $WtbStatus = 'true';
                    else
                        $WtbStatus = 'false'; 


            $this->TravelCitySupplier->id = $id;
            if ($this->TravelCitySupplier->save($this->request->data['TravelCitySupplier'])) {
                
                
                $content_xml_str = '<soap:Body>
                                        <ProcessXML xmlns="http://www.travel.domain/">
                                            <RequestInfo>
                                                <ResourceDataRequest>
                                                    <RequestAuditInfo>
                                                        <RequestType>PXML_WData_CityMapping</RequestType>
                                                        <RequestTime>' . $CreatedDate . '</RequestTime>
                                                        <RequestResource>Silkrouters</RequestResource>
                                                    </RequestAuditInfo>
                                                    <RequestParameters>                        
                                                        <ResourceData>
                                                            <ResourceDetailsData srno="1" actiontype="' . $city_actiontype . '">
                                                                <Id>' . $Id . '</Id>
                                                                <CityCode><![CDATA['.$city_code.']]></CityCode>
                                                                <CityName><![CDATA[' . $CityName . ']]></CityName>
                                                                <CityId>' . $CityId . '</CityId>                                
                                                                <SupplierCode><![CDATA[' . $SupplierCode . ']]></SupplierCode>
                                                                <SupplierCityCode><![CDATA[' . $SupplierCityCode . ']]></SupplierCityCode>
                                                                <PFCityCode><![CDATA[' . $city_code . ']]></PFCityCode>
                                                                <CityMappingName><![CDATA[' . $CityMappingName . ']]></CityMappingName>
                                                                <CityCountryCode><![CDATA[' . $country_code . ']]></CityCountryCode>
                                                                <CityCountryId>' . $CityCountryId . '</CityCountryId>
                                                                <CityCountryName><![CDATA[' . $CityCountryName . ']]></CityCountryName>
                                                                <CityContinentId>' . $CityContinentId . '</CityContinentId>
                                                                <CityContinentName><![CDATA[' . $CityContinentName . ']]></CityContinentName>
                                                                <ProvinceId>' . $ProvinceId . '</ProvinceId>
                                                                <ProvinceName><![CDATA[' . $ProvinceName . ']]></ProvinceName>
                                                                <CitySupplierStatus>' . $CitySupplierStatus . '</CitySupplierStatus>
                                                                <SupplierCountryCode><![CDATA[' . $SupplierCountryCode . ']]></SupplierCountryCode>
                                                                <WtbStatus>false</WtbStatus>
                                                                <Active>' . $Active . '</Active>
                                                                <Excluded>' . $Excluded . '</Excluded>                             
                                                                <ApprovedBy>' . $ApprovedBy . '</ApprovedBy>
                                                                <ApprovedDate>' . $ApprovedDate . '</ApprovedDate>
                                                                <CreatedBy>' . $CreatedBy . '</CreatedBy>
                                                                <CreatedDate>' . $created . '</CreatedDate> 
                                                            </ResourceDetailsData>              
                                                    </ResourceData>
                                                    </RequestParameters>
                                                </ResourceDataRequest>
                                            </RequestInfo>
                                        </ProcessXML>
                                    </soap:Body>';

                $log_call_screen = 'Edit - City Mapping';
                $RESOURCEDATA = 'RESOURCEDATA_CITYMAPPING';
                
                $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');

            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);
//Get response from here
                $xml_arr = $this->xml2array($order_return);

                if ($xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0] == '201') {
                    $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0];
                    $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['UPDATEINFO']['STATUS'][0];
                    $xml_msg = "Foreign record has been successfully created [Code:$log_call_status_code]";
                    $this->$table->updateAll(array('wtb_status' => "'1'",'is_update' => "'Y'"), array('id' => $id));
                    
                } else {

                    $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['ERRORINFO']['ERROR'][0];
                    $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0]; // RESPONSEID
                    $xml_msg = "There was a problem with foreign record creation [Code:$log_call_status_code]";
                    $this->$table->updateAll(array('wtb_status' => "'2'"), array('id' => $id));
                    $xml_error = 'TRUE';
                }
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }


            $this->request->data['LogCall']['log_call_nature'] = 'Production';
            $this->request->data['LogCall']['log_call_type'] = 'Outbound';
            $this->request->data['LogCall']['log_call_parms'] = trim($xml_string);
            $this->request->data['LogCall']['log_call_status_code'] = $log_call_status_code;
            $this->request->data['LogCall']['log_call_status_message'] = $log_call_status_message;
            $this->request->data['LogCall']['log_call_screen'] = $log_call_screen;
            $this->request->data['LogCall']['log_call_counterparty'] = 'WTBNETWORKS';
            $this->request->data['LogCall']['log_call_by'] = $user_id;
            $this->LogCall->save($this->request->data['LogCall']);
            $LogId = $this->LogCall->getLastInsertId();
            $a =  date('m/d/Y H:i:s', strtotime('-1 hour'));
            $date = new DateTime($a, new DateTimeZone('Asia/Calcutta'));
            $message = $xml_msg;
            if($xml_error == 'TRUE'){
                $Email = new CakeEmail();

                $Email->viewVars(array(
                    'request_xml' => trim($xml_string),
                    'respon_message' => $log_call_status_message,
                    'respon_code' => $log_call_status_code,
                ));

                $to = 'biswajit@wtbglobal.com';
                $cc = 'infra@sumanus.com';
               
                $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Log Id [' . $LogId . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();
            }
            $this->Session->setFlash($message, 'success');
          

                //$this->Session->setFlash('Your changes have been submitted. Waiting for approval at the moment...', 'success');
            }
            else
                $this->Session->setFlash('Unable to add Action item.', 'failure');

        }


        $TravelSuppliers = $this->TravelSupplier->find('all', array('fields' => 'supplier_code, supplier_name', 'conditions' => array('active' => 'TRUE'), 'order' => 'supplier_name ASC'));
        $TravelSuppliers = Set::combine($TravelSuppliers, '{n}.TravelSupplier.supplier_code', array('%s - %s', '{n}.TravelSupplier.supplier_code', '{n}.TravelSupplier.supplier_name'));
        $this->set(compact('TravelSuppliers'));

        $TravelCountries = $this->TravelCountry->find('all', array('fields' => 'country_code, country_name', 'conditions' => array('country_code' => $TravelCitySuppliers['TravelCitySupplier']['city_country_code']), 'order' => 'country_name ASC'));
        $TravelCountries = Set::combine($TravelCountries, '{n}.TravelCountry.country_code', array('%s - %s', '{n}.TravelCountry.country_code', '{n}.TravelCountry.country_name'));
        $this->set(compact('TravelCountries'));
        
        $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $TravelCitySuppliers['TravelCitySupplier']['city_country_id'],
                    'Province.continent_id' => $TravelCitySuppliers['TravelCitySupplier']['city_continent_id'],
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE',
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));

        $TravelCities = $this->TravelCity->find('all', array('fields' => 'city_code, city_name', 'conditions' => array('city_code' => $TravelCitySuppliers['TravelCitySupplier']['pf_city_code']), 'order' => 'city_name ASC'));
        $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.city_code', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));
        $this->set(compact('TravelCities','Provinces'));


        $this->request->data = $TravelCitySuppliers;
    }
    
    function hotel_mapping_edit($id = null) {

        $user_id = $this->Auth->user('id');
        $role_id = $this->Session->read("role_id");
        $dummy_status = $this->Auth->user('dummy_status');
        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $log_call_screen = '';
        $xml_msg = '';
        $xml_error = 'FALSE';

        if (!$id) {
            throw new NotFoundException(__('Invalid Hotel Supplier'));
        }

        $TravelHotelRoomSuppliers = $this->TravelHotelRoomSupplier->findById($id);


        if (!$TravelHotelRoomSuppliers) {
            throw new NotFoundException(__('Invalid Hotel Supplier'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            
            $Id = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['id'];
            $country_code = trim($TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_country_code']);
            $hotel_code = trim($TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_code']);
            $city_code = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_city_code'];
            $SupplierCode = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['supplier_code'];
            $Active = strtolower($TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['active']);
            $Excluded = strtolower($TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['excluded']);
            $SupplierCountryCode = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['supplier_item_code4'];
            $SupplierCityCode = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['supplier_item_code3'];
            $SupplierHotelCode = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['supplier_item_code1'];
            $HotelName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_name'];
            $CityId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_city_id'];
            $CityName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_city_name'];
            $SuburbId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_suburb_id'];
            $SuburbName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_suburb_name'];
            $AreaId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_area_id'];
            $AreaName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_area_name'];
            $BrandId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_brand_id'];
            $BrandName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_brand_name'];
            $ChainId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_chain_id'];
            $ChainName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_chain_name'];
            $CountryId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_country_id'];
            $CountryName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_country_name'];
            $ContinentId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_continent_id'];
            $ContinentName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_continent_name'];
            $ApprovedBy = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['approved_by'];
            $CreatedBy = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['created_by'];           
            $app_date = explode(' ', $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['approved_date']);        
            $ApprovedDate = $app_date[0] . 'T' . $app_date[1];           
            $ProvinceId = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['province_id'];
            $ProvinceName = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['province_name'];
            $date = explode(' ', $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['created']);
            $created = $date[0] . 'T' . $date[1];
            $is_update = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['is_update'];
                    if ($is_update == 'Y')
                        $hotel_actiontype = 'Update';
                    else
                        $hotel_actiontype = 'AddNew';
                    
            $WtbStatus = $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['wtb_status'];
                    if ($WtbStatus)
                        $WtbStatus = 'true';
                    else
                        $WtbStatus = 'false';

            $this->TravelHotelRoomSupplier->id = $id;
            if ($this->TravelHotelRoomSupplier->save($this->request->data['TravelHotelRoomSupplier'])) {
                
                $content_xml_str = '<soap:Body>
                                        <ProcessXML xmlns="http://www.travel.domain/">
                                            <RequestInfo>
                                                <ResourceDataRequest>
                                                    <RequestAuditInfo>
                                                        <RequestType>PXML_WData_HotelMapping</RequestType>
                                                        <RequestTime>' . $CreatedDate . '</RequestTime>
                                                        <RequestResource>Silkrouters</RequestResource>
                                                    </RequestAuditInfo>
                                                    <RequestParameters>                        
                                                        <ResourceData>
                                                            <ResourceDetailsData srno="1" actiontype="' . $hotel_actiontype . '">
                                                                <Id>'.$Id.'</Id>
                                                                <HotelCode><![CDATA['.$hotel_code.']]></HotelCode>
                                                                <HotelName><![CDATA['.$HotelName.']]></HotelName>
                                                                <SupplierCode><![CDATA['.$SupplierCode.']]></SupplierCode>
                                                                <WtbStatus>false</WtbStatus>
                                                                <Active>'.$Active.'</Active>
                                                                <Excluded>'.$Excluded.'</Excluded>
                                                                <ContinentId>'.$ContinentId.'</ContinentId>
                                                                <ContinentCode>NA</ContinentCode>
                                                                <ContinentName><![CDATA['.$ContinentName.']]></ContinentName>                              
                                                                <CountryId>'.$CountryId.'</CountryId>
                                                                <CountryCode><![CDATA['.$country_code.']]></CountryCode>
                                                                <CountryName><![CDATA['.$CountryName.']]></CountryName>
                                                                <ProvinceId>' . $ProvinceId . '</ProvinceId>
                                                                <ProvinceName><![CDATA[' . $ProvinceName . ']]></ProvinceName>
                                                                <CityId>'.$CityId.'</CityId>
                                                                <CityCode><![CDATA['.$city_code.']]></CityCode>
                                                                <CityName><![CDATA['.$CityName.']]></CityName>
                                                                <SuburbId>'.$SuburbId.'</SuburbId>
                                                                <SuburbCode>NA</SuburbCode>
                                                                <SuburbName><![CDATA['.$SuburbName.']]></SuburbName>
                                                                <AreaId>'.$AreaId.'</AreaId>
                                                                <AreaName><![CDATA['.$AreaName.']]></AreaName>
                                                                <BrandId>'.$BrandId.'</BrandId>
                                                                <BrandName><![CDATA['.$BrandName.']]></BrandName>
                                                                <ChainId>'.$ChainId.'</ChainId>
                                                                <ChainName><![CDATA['.$ChainName.']]></ChainName>    
                                                                <SupplierCountryCode><![CDATA['.$SupplierCountryCode.']]></SupplierCountryCode>
                                                                <SupplierCityCode><![CDATA['.$SupplierCityCode.']]></SupplierCityCode>
                                                                <SupplierHotelCode>'.$SupplierHotelCode.'</SupplierHotelCode>                              
                                                                <SupplierHotelRoomCode></SupplierHotelRoomCode>
                                                                <SupplierItemCode5></SupplierItemCode5>
                                                                <SupplierItemCode6></SupplierItemCode6>                              
                                                                <SupplierSuburbCode>NA</SupplierSuburbCode>
                                                                <SupplierAreaCode>NA</SupplierAreaCode>                              
                                                                <ApprovedBy>'.$ApprovedBy.'</ApprovedBy>
                                                                <ApprovedDate>'.$ApprovedDate.'</ApprovedDate>
                                                                <CreatedBy>' . $CreatedBy . '</CreatedBy>
                                                                <CreatedDate>' . $created . '</CreatedDate> 
                                                              </ResourceDetailsData>              
                                                    </ResourceData>
                                                    </RequestParameters>
                                                </ResourceDataRequest>
                                            </RequestInfo>
                                        </ProcessXML>
                                    </soap:Body>';
                
                $log_call_screen = 'Edit - Hotel Mapping';
                $RESOURCEDATA = 'RESOURCEDATA_HOTELMAPPING';
                
                $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');

            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);
//Get response from here
                $xml_arr = $this->xml2array($order_return);

                if ($xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0] == '201') {
                    $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0];
                    $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['UPDATEINFO']['STATUS'][0];
                    $xml_msg = "Foreign record has been successfully created [Code:$log_call_status_code]";
                    $this->$table->updateAll(array('wtb_status' => "'1'",'is_update' => "'Y'"), array('id' => $id));
                    
                } else {

                    $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['ERRORINFO']['ERROR'][0];
                    $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0]; // RESPONSEID
                    $xml_msg = "There was a problem with foreign record creation [Code:$log_call_status_code]";
                    $this->$table->updateAll(array('wtb_status' => "'2'"), array('id' => $id));
                    $xml_error = 'TRUE';
                }
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }


            $this->request->data['LogCall']['log_call_nature'] = 'Production';
            $this->request->data['LogCall']['log_call_type'] = 'Outbound';
            $this->request->data['LogCall']['log_call_parms'] = trim($xml_string);
            $this->request->data['LogCall']['log_call_status_code'] = $log_call_status_code;
            $this->request->data['LogCall']['log_call_status_message'] = $log_call_status_message;
            $this->request->data['LogCall']['log_call_screen'] = $log_call_screen;
            $this->request->data['LogCall']['log_call_counterparty'] = 'WTBNETWORKS';
            $this->request->data['LogCall']['log_call_by'] = $user_id;
            $this->LogCall->save($this->request->data['LogCall']);
            $LogId = $this->LogCall->getLastInsertId();
            $a =  date('m/d/Y H:i:s', strtotime('-1 hour'));
            $date = new DateTime($a, new DateTimeZone('Asia/Calcutta'));
            $message = $xml_msg;
            if($xml_error == 'TRUE'){
                $Email = new CakeEmail();

                $Email->viewVars(array(
                    'request_xml' => trim($xml_string),
                    'respon_message' => $log_call_status_message,
                    'respon_code' => $log_call_status_code,
                ));

                $to = 'biswajit@wtbglobal.com';
                $cc = 'infra@sumanus.com';
               
                $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Log Id [' . $LogId . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();
            }
            $this->Session->setFlash($message, 'success');
               
                
            }
            else
                $this->Session->setFlash('Unable to add Action item.', 'failure');

           
        }


        $TravelSuppliers = $this->TravelSupplier->find('all', array('fields' => 'supplier_code, supplier_name', 'conditions' => array('active' => 'TRUE'), 'order' => 'supplier_name ASC'));
        $TravelSuppliers = Set::combine($TravelSuppliers, '{n}.TravelSupplier.supplier_code', array('%s - %s', '{n}.TravelSupplier.supplier_code', '{n}.TravelSupplier.supplier_name'));
     

        $TravelCountries = $this->TravelCountry->find('all', array('fields' => 'country_code, country_name', 'conditions' => array('country_code' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_country_code']), 'order' => 'country_name ASC'));
        $TravelCountries = Set::combine($TravelCountries, '{n}.TravelCountry.country_code', array('%s - %s', '{n}.TravelCountry.country_code', '{n}.TravelCountry.country_name'));


        $TravelCities = $this->TravelCity->find('all', array('fields' => 'city_code, city_name', 'conditions' => array('city_code' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_city_code']), 'order' => 'city_name ASC'));
        $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.city_code', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));
    
        $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_country_id'],
                    'Province.continent_id' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_continent_id'],
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE',
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));

        $TravelHotelLookups = $this->TravelHotelLookup->find('list', array('fields' => 'hotel_code, hotel_name', 'conditions' => array('hotel_code' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_code']), 'order' => 'hotel_name ASC'));
        
        
        $TravelAreas = $this->TravelArea->find('list', array(
                'conditions' => array(
                    'TravelArea.id' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_area_id'],                   
                ),
                'fields' => 'TravelArea.id, TravelArea.area_name',
                'order' => 'TravelArea.area_name ASC'
            ));
       
        
        $TravelSuburbs = $this->TravelSuburb->find('list', array(
                'conditions' => array(
                    'TravelSuburb.id' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_suburb_id'],
                ),
                'fields' => 'TravelSuburb.id, TravelSuburb.name',
                'order' => 'TravelSuburb.name ASC'
            ));
        
        $TravelChains = $this->TravelChain->find('list', array(
                'conditions' => array(
                    'TravelChain.id' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_chain_id'],
                  
                ),
                'fields' => 'TravelChain.id, TravelChain.chain_name',
                'order' => 'TravelChain.chain_name ASC'
            ));
        
        $TravelBrands = $this->TravelBrand->find('list', array(
                'conditions' => array(
                    'TravelBrand.id' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_brand_id'],
                ),
                'fields' => 'TravelBrand.id, TravelBrand.brand_name',
                'order' => 'TravelBrand.brand_name ASC'
            ));
        
        $HotelUrl = $this->TravelHotelLookup->find('first',array('conditions' => array('hotel_code' => $TravelHotelRoomSuppliers['TravelHotelRoomSupplier']['hotel_code']),'fields' => array('url_hotel','address')));
    
        $this->set(compact('TravelHotelLookups','Provinces','TravelCities','TravelCountries','TravelSuppliers','TravelAreas','TravelSuburbs','TravelChains','TravelBrands','HotelUrl'));

        $this->request->data = $TravelHotelRoomSuppliers;
    }
    
    public function suburb_edit($id = null, $mode = 1) {

        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');
        $dummy_status = $this->Auth->user('dummy_status');
        $this->set(compact('mode'));  
        $xml_error = 'FALSE';
 
        if (!$id) {
            throw new NotFoundException(__('Invalid Suburb'));
        }

        $TravelSuburbs = $this->TravelSuburb->findById($id);

        if (!$TravelSuburbs) {
            throw new NotFoundException(__('Invalid suburb'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            //    $this->TravelSuburb->set($this->data);
            if ($this->TravelSuburb->validates() == true) {

                $this->TravelSuburb->id = $id;
                if ($this->TravelSuburb->save($this->request->data)) {
                    $SuburbId = $id;
                    $SuburbName = $this->data['TravelSuburb']['name'];
                    $CityId = $this->data['TravelSuburb']['city_id'];
                    $CityName = $this->data['TravelSuburb']['city_name'];
                    $CountryId = $this->data['TravelSuburb']['country_id'];
                    $CountryName = $this->data['TravelSuburb']['country_name'];
                    $ContinentId = $this->data['TravelSuburb']['continent_id'];
                    $ContinentName = $this->data['TravelSuburb']['continent_name'];
                    $SuburbDescription = $this->data['TravelSuburb']['description'];
                    $Active = strtolower($TravelSuburbs['TravelSuburb']['active']);
                    $TopNeighborhood = strtolower($this->data['TravelSuburb']['top_neighborhood']);
                    $SuburbStatus = $TravelSuburbs['TravelSuburb']['status'];
                    if ($SuburbStatus)
                        $SuburbStatus = 'true';
                    else
                        $SuburbStatus = 'false';

                    $WtbStatus = $TravelSuburbs['TravelSuburb']['wtb_status'];
                    if ($WtbStatus)
                        $WtbStatus = 'true';
                    else
                        $WtbStatus = 'false';
                    $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');

                    $content_xml_str = '<soap:Body>
                                        <ProcessXML xmlns="http://www.travel.domain/">
                                            <RequestInfo>
                                                <ResourceDataRequest>
                                                    <RequestAuditInfo>
                                                        <RequestType>PXML_WData_Suburb</RequestType>
                                                        <RequestTime>' . $CreatedDate . '</RequestTime>
                                                        <RequestResource>Silkrouters</RequestResource>
                                                    </RequestAuditInfo>
                                                    <RequestParameters>                        
                                                        <ResourceData>
                                                            <ResourceDetailsData srno="1" actiontype="Update">
                                                                <SuburbId>' . $SuburbId . '</SuburbId>
                                                                <SuburbCode>NA</SuburbCode>
                                                                <SuburbName>' . $SuburbName . '</SuburbName>
                                                                <CityId>' . $CityId . '</CityId>
                                                                <CityCode>NA</CityCode>
                                                                <CityName>' . $CityName . '</CityName>
                                                                <CountryId>' . $CountryId . '</CountryId>
                                                                <CountryCode>NA</CountryCode>
                                                                <CountryName>' . $CountryName . '</CountryName>
                                                                <ContinentId>' . $ContinentId . '</ContinentId>
                                                                <ContinentCode>NA</ContinentCode>
                                                                <ContinentName>' . $ContinentName . '</ContinentName>
                                                                <SuburbDescription>' . $SuburbDescription . '</SuburbDescription>
                                                                <SuburbKeyword>NA</SuburbKeyword>
                                                                <Active>' . $Active . '</Active>
                                                                <TopNeighborhood>' . $TopNeighborhood . '</TopNeighborhood>
                                                                <SuburbStatus>' . $SuburbStatus . '</SuburbStatus>
                                                                <WtbStatus>' . $WtbStatus . '</WtbStatus>
                                                                <ApprovedBy>0</ApprovedBy>
                                                                <ApprovedDate>1111-01-01T00:00:00</ApprovedDate>
                                                                <CreatedBy>' . $user_id . '</CreatedBy>
                                                                <CreatedDate>' . $CreatedDate . '</CreatedDate>                                 

                                                            </ResourceDetailsData>                        
                                                    </ResourceData>
                                                    </RequestParameters>
                                                </ResourceDataRequest>
                                            </RequestInfo>
                                        </ProcessXML>
                                    </soap:Body>';


                    $log_call_screen = 'Suburb - Edit';

                    $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
                    $client = new SoapClient(null, array(
                        'location' => $location_URL,
                        'uri' => '',
                        'trace' => 1,
                    ));

                    try {
                        $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);

                        $xml_arr = $this->xml2array($order_return);

                        if ($xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_SUBURB']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0] == '201') {
                            $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_SUBURB']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0];
                            $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_SUBURB']['RESPONSEAUDITINFO']['UPDATEINFO']['STATUS'][0];
                            $xml_msg = "Foreign record has been successfully updated [Code:$log_call_status_code]";
                            $this->TravelSuburb->updateAll(array('TravelSuburb.wtb_status' => "'1'"), array('TravelSuburb.id' => $SuburbId));
                        } else {

                            $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_SUBURB']['RESPONSEAUDITINFO']['ERRORINFO']['ERROR'][0];
                            $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_SUBURB']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0]; // RESPONSEID
                            $xml_msg = "There was a problem with foreign record updated [Code:$log_call_status_code]";
                            $this->TravelSuburb->updateAll(array('TravelSuburb.wtb_status' => "'2'"), array('TravelSuburb.id' => $SuburbId));
                            $xml_error = 'TRUE';
                        }
                    } catch (SoapFault $exception) {
                        var_dump(get_class($exception));
                        var_dump($exception);
                    }


                    $this->request->data['LogCall']['log_call_nature'] = 'Production';
                    $this->request->data['LogCall']['log_call_type'] = 'Outbound';
                    $this->request->data['LogCall']['log_call_parms'] = trim($xml_string);
                    $this->request->data['LogCall']['log_call_status_code'] = $log_call_status_code;
                    $this->request->data['LogCall']['log_call_status_message'] = $log_call_status_message;
                    $this->request->data['LogCall']['log_call_screen'] = $log_call_screen;
                    $this->request->data['LogCall']['log_call_counterparty'] = 'WTBNETWORKS';
                    $this->request->data['LogCall']['log_call_by'] = $user_id;
                    $this->LogCall->save($this->request->data['LogCall']);
                    $message = 'Local record has been successfully updated.<br />' . $xml_msg;
                    $a = date('m/d/Y H:i:s', strtotime('-1 hour'));
                    $date = new DateTime($a, new DateTimeZone('Asia/Calcutta'));
                    if ($xml_error == 'TRUE') {
                        $Email = new CakeEmail();

                        $Email->viewVars(array(
                            'request_xml' => trim($xml_string),
                            'respon_message' => $log_call_status_message,
                            'respon_code' => $log_call_status_code,
                        ));

                        $to = 'biswajit@wtbglobal.com';
                        $cc = 'infra@sumanus.com';

                        $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();
                    }
                    $this->Session->setFlash($message, 'success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to update Suburb.', 'failure');
                }
            }
        }


        $TravelCities = $this->TravelCity->find('list', array('fields' => 'id,city_name', 'conditions' => array('country_id' => $TravelSuburbs['TravelSuburb']['country_id'], 'city_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'city_name ASC'));
        //  $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.id', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));
        $this->set(compact('TravelCities'));

        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('continent_id' => $TravelSuburbs['TravelSuburb']['continent_id'], 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));
        // $TravelCountries = Set::combine($TravelCountries, '{n}.TravelCountry.id', array('%s - %s', '{n}.TravelCountry.country_code', '{n}.TravelCountry.country_name'));
        $this->set(compact('TravelCountries'));

        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $this->set(compact('TravelLookupContinents'));
        
        $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $TravelSuburbs['TravelSuburb']['country_id'],
                    'Province.continent_id' => $TravelSuburbs['TravelSuburb']['continent_id'],
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE',
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));

        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $this->set(compact('TravelLookupContinents','Provinces'));

        $this->request->data = $TravelSuburbs;
    }
    
    public function area_edit($id = null, $mode = 1) {

        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');
        $dummy_status = $this->Auth->user('dummy_status');
        $xml_error = 'FALSE';
        $this->set(compact('mode'));

        if (!$id) {
            throw new NotFoundException(__('Invalid Suburb'));
        }

        $TravelAreas = $this->TravelArea->findById($id);

        if (!$TravelAreas) {
            throw new NotFoundException(__('Invalid suburb'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->TravelArea->set($this->data);
            if ($this->TravelArea->validates() == true) {

                $this->TravelArea->id = $id;
                if ($this->TravelArea->save($this->request->data)) {
                    $AreaId = $id;
                    $AreaName = $this->data['TravelArea']['area_name'];
                    $SuburbId = $this->data['TravelArea']['suburb_id'];
                    $SuburbName = $this->data['TravelArea']['suburb_name'];
                    $CityId = $this->data['TravelArea']['city_id'];
                    $CityName = $this->data['TravelArea']['city_name'];
                    $CountryId = $this->data['TravelArea']['country_id'];
                    $CountryName = $this->data['TravelArea']['country_name'];
                    $ContinentId = $this->data['TravelArea']['continent_id'];
                    $ContinentName = $this->data['TravelArea']['continent_name'];
                    $AreaDescription = $this->data['TravelArea']['area_description'];
                    $TopArea = strtolower($this->data['TravelArea']['top_area']);
                    $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');
                    $Active = 'true';



                    $content_xml_str = '<soap:Body>
                                        <ProcessXML xmlns="http://www.travel.domain/">
                                            <RequestInfo>
                                                <ResourceDataRequest>
                                                    <RequestAuditInfo>
                                                        <RequestType>PXML_WData_Area</RequestType>
                                                        <RequestTime>' . $CreatedDate . '</RequestTime>
                                                        <RequestResource>Silkrouters</RequestResource>
                                                    </RequestAuditInfo>
                                                    <RequestParameters>                        
                                                        <ResourceData>
                                                             <ResourceDetailsData srno="1" actiontype="Update">
                                                                <AreaId>' . $AreaId . '</AreaId>
                                                                <AreaCode>NA</AreaCode>
                                                                <AreaName>' . $AreaName . '</AreaName>
                                                                <SuburbId>' . $SuburbId . '</SuburbId>
                                                                <SuburbCode>NA</SuburbCode>
                                                                <SuburbName>' . $SuburbName . '</SuburbName>
                                                                <CityId>' . $CityId . '</CityId>
                                                                <CityCode>NA</CityCode>
                                                                <CityName>' . $CityName . '</CityName>
                                                                <CountryId>' . $CountryId . '</CountryId>
                                                                <CountryCode>NA</CountryCode>
                                                                <CountryName>' . $CountryName . '</CountryName>
                                                                <ContinentId>' . $ContinentId . '</ContinentId>
                                                                <ContinentCode>NA</ContinentCode>
                                                                <ContinentName>' . $ContinentName . '</ContinentName>
                                                                <ProvinceId>0</ProvinceId>
                                                                <ProvinceName>NA</ProvinceName>
                                                                <AreaMap></AreaMap>
                                                                <AreaMapSS></AreaMapSS>
                                                                <AreaComment></AreaComment>
                                                                <AreaDescription></AreaDescription>
                                                                <AreaActive>' . $Active . '</AreaActive>
                                                                <AreaDomainName></AreaDomainName>
                                                                <TopArea>' . $TopArea . '</TopArea>
                                                                <AreaStatus>true</AreaStatus>
                                                                <WtbStatus>false</WtbStatus>
                                                                <ActiveMap>true</ActiveMap>
                                                                <Isupdated>1</Isupdated>
                                                                <PFTActive>1</PFTActive>
                                                                <AreaNameURL></AreaNameURL>
                                                                <TopDestination>1</TopDestination>
                                                                <ApprovedBy>0</ApprovedBy>
                                                                <ApprovedDate>1111-01-01T00:00:00</ApprovedDate>
                                                                <CreatedBy>' . $user_id . '</CreatedBy>
                                                                <CreatedDate>' . $CreatedDate . '</CreatedDate>
                                                            </ResourceDetailsData>

                         
                                                    </ResourceData>
                                                    </RequestParameters>
                                                </ResourceDataRequest>
                                            </RequestInfo>
                                        </ProcessXML>
                                    </soap:Body>';


                    $log_call_screen = 'Area - Edit';

                    $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
                    $client = new SoapClient(null, array(
                        'location' => $location_URL,
                        'uri' => '',
                        'trace' => 1,
                    ));

                    try {
                        $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);

                        $xml_arr = $this->xml2array($order_return);
                        //echo htmlentities($xml_string);
                        // pr($xml_arr);
                        //  die;


                        if ($xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_AREA']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0] == '201') {
                            $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_AREA']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0];
                            $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_AREA']['RESPONSEAUDITINFO']['UPDATEINFO']['STATUS'][0];
                            $xml_msg = "Foreign record has been successfully updated [Code:$log_call_status_code]";
                            $this->TravelArea->updateAll(array('TravelArea.wtb_status' => "'1'"), array('TravelArea.id' => $AreaId));
                        } else {

                            $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_AREA']['RESPONSEAUDITINFO']['ERRORINFO']['ERROR'][0];
                            $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_AREA']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0]; // RESPONSEID
                            $xml_msg = "There was a problem with foreign record updation [Code:$log_call_status_code]";
                            $this->TravelArea->updateAll(array('TravelArea.wtb_status' => "'2'"), array('TravelArea.id' => $AreaId));
                            $xml_error = 'TRUE';
                        }
                    } catch (SoapFault $exception) {
                        var_dump(get_class($exception));
                        var_dump($exception);
                    }


                    $this->request->data['LogCall']['log_call_nature'] = 'Production';
                    $this->request->data['LogCall']['log_call_type'] = 'Outbound';
                    $this->request->data['LogCall']['log_call_parms'] = trim($xml_string);
                    $this->request->data['LogCall']['log_call_status_code'] = $log_call_status_code;
                    $this->request->data['LogCall']['log_call_status_message'] = $log_call_status_message;
                    $this->request->data['LogCall']['log_call_screen'] = $log_call_screen;
                    $this->request->data['LogCall']['log_call_counterparty'] = 'WTBNETWORKS';
                    $this->request->data['LogCall']['log_call_by'] = $user_id;
                    $this->LogCall->save($this->request->data['LogCall']);
                    $message = 'Local record has been successfully updated.<br />' . $xml_msg;
                    $this->Session->setFlash($message, 'success');
                    $a = date('m/d/Y H:i:s', strtotime('-1 hour'));
                    $date = new DateTime($a, new DateTimeZone('Asia/Calcutta'));
                    if ($xml_error == 'TRUE') {
                        $Email = new CakeEmail();

                        $Email->viewVars(array(
                            'request_xml' => trim($xml_string),
                            'respon_message' => $log_call_status_message,
                            'respon_code' => $log_call_status_code,
                        ));

                        $to = 'biswajit@wtbglobal.com';
                        $cc = 'infra@sumanus.com';

                        $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();
                    }
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('Unable to update Area.', 'failure');
                }
            }
        }


        $TravelCities = $this->TravelCity->find('list', array('fields' => 'id,city_name', 'conditions' => array('country_id' => $TravelAreas['TravelArea']['country_id'], 'city_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'city_name ASC'));
        //  $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.id', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));
        $this->set(compact('TravelCities'));

        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('continent_id' => $TravelAreas['TravelArea']['continent_id'], 'country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));
        $this->set(compact('TravelCountries'));
        
        $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $TravelAreas['TravelArea']['country_id'],
                    'Province.continent_id' => $TravelAreas['TravelArea']['continent_id'],
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE',
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));

        $TravelSuburbs = $this->TravelSuburb->find('list', array('fields' => 'id,name', 'conditions' => array('status' => 1, 'country_id' => $TravelAreas['TravelArea']['country_id'], 'city_id' => $TravelAreas['TravelArea']['city_id']), 'order' => 'name ASC'));
        $this->set(compact('TravelSuburbs'));

        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $this->set(compact('TravelLookupContinents','Provinces'));
        
        

        $this->request->data = $TravelAreas;
    }

}