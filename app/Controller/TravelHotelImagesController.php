<?php

/**
 * TravelHotelLookups controller.
 *
 * This file will render views from views/TravelHotelLookups/
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
 * Builder controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TravelHotelImagesController extends AppController {

    public $uses = array('TravelHotelLookup', 'TravelHotelRoomSupplier', 'TravelCountry', 'TravelLookupContinent', 'TravelLookupValueContractStatus', 'TravelCity', 'TravelChain',
        'TravelSuburb', 'TravelArea', 'TravelBrand', 'TravelActionItem', 'TravelRemark', 'LogCall','User','Province','ProvincePermission', 'DeleteTravelHotelLookup', 'DeleteLogTable',
        'TravelLookupRateType','TravelLookupPropertyType','TravelCitySupplier');
    public $components = array('Sms', 'Image');
    public $uploadDir;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->uploadDir = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . '/uploads/hotels';
        $this->Width = '200';
        $this->Height = '200';
    }

    public function index() {
        

        $city_id = $this->Auth->user('city_id');
        $user_id = $this->Auth->user('id');
        $search_condition = array();
        $hotel_name = '';
        $continent_id = '';
        $country_id = '';
        $city_id = '';
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
        $proArr = array();
	$conProvince = array();
        
            if($this->checkProvince())
            $proArr = $this->checkProvince();
            //next($proArr);
            
            
  
			
			if($this->hotelProvince()){
            array_push($search_condition, array('TravelHotelLookup.province_id' => $this->hotelProvince())); 
			$conProvince = array('TravelHotelLookup.province_id' => $this->hotelProvince());
			}



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
                    'Province.id' => $proArr
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

        



            
//  pr($this->params);

        if (count($this->params['pass'])) {

            $aaray = explode(':', $this->params['pass'][0]);
            $field = $aaray[0];
            $value = $aaray[1];
            array_push($search_condition, array('TravelHotelLookup.' . $field . ' LIKE' => '%' . $value . '%')); // when builder is approve/pending                 
        }
        /*
          elseif(count($this->params['named'])){
          foreach($this->params['named'] as $key=>$val){
          array_push($search_condition, array('TravelHotelLookup.' .$key.' LIKE' => '%'.$val.'%')); // when builder is approve/pending
          }
          }
         * 
         */
        //array_push($search_condition, array('TravelHotelLookup.country_id' => '220'));

        $this->paginate['order'] = array('TravelHotelLookup.city_code' => 'asc');
        $this->set('TravelHotelLookups', $this->paginate("TravelHotelLookup", $search_condition));

        //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);       
        //debug($log);
        //die;

        $hotel_count = $this->TravelHotelLookup->find('count',array('conditions' => $conProvince));
        $this->set(compact('hotel_count'));

        $active_count = $this->TravelHotelLookup->find('count', array('conditions' => array('active' => '1')+$conProvince));
        $this->set(compact('active_count'));

        $midd_east_count = $this->TravelHotelLookup->find('count', array('conditions' => array('continent_id LIKE' => '%ME%')+$conProvince));
        $this->set(compact('midd_east_count'));

        $direct_count = $this->TravelHotelLookup->find('count', array('conditions' => array('contract_status' => '2')+$conProvince));
        $this->set(compact('direct_count'));

        $europe_count = $this->TravelHotelLookup->find('count', array('conditions' => array('continent_id LIKE' => '%EU%')+$conProvince));
        $this->set(compact('europe_count'));

        $asia_count = $this->TravelHotelLookup->find('count', array('conditions' => array('continent_id LIKE' => '%AS%')+$conProvince));
        $apac_count = $asia_count + $europe_count;
        $this->set(compact('apac_count'));

        $mapped_count = $this->TravelHotelRoomSupplier->find('count', array(
            'joins' => array(
                array(
                    'table' => 'travel_hotel_lookups',
                    'alias' => 'TravelHotelLookup',
                    'conditions' => array(
                        'TravelHotelLookup.hotel_code = TravelHotelRoomSupplier.hotel_code'
                    )
                )
            )
        ));

        $four_star_count = $this->TravelHotelLookup->find('count', array('conditions' => array('star LIKE' => '%4%')+$conProvince));
        $five_star_count = $this->TravelHotelLookup->find('count', array('conditions' => array('star LIKE' => '%5%')+$conProvince));
        $four_five_star = $four_star_count + $five_star_count;
        $three_star_count = $this->TravelHotelLookup->find('count', array('conditions' => array('star LIKE' => '%3%')+$conProvince));
        $below_three_star_count = $this->TravelHotelLookup->find('count', array('conditions' => array('star >' => '3')+$conProvince));
        $thailand_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%TH%')+$conProvince));
        $bangkok_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%BKK%')+$conProvince));
        $pattaya_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%PYX%')+$conProvince));
        $phuket_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%HKT%')+$conProvince));
        $india_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%IN%')+$conProvince));
        $uae_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%AE%')+$conProvince));
        $dubai_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%DUA%')+$conProvince));
        $sharjah_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%SHH%')+$conProvince));
        $abu_dhabi_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%AUH%')+$conProvince));
        $melbourne_count = $this->TravelHotelLookup->find('count', array('conditions' => array('city_code LIKE' => '%9AJ%')+$conProvince));
        $new_zealand_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%NZ%')+$conProvince));
        $malaysia_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%MY%')+$conProvince));
        $singapore_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%SG%')+$conProvince));
        $maldives_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%MV%')+$conProvince));
        $srilanka_count = $this->TravelHotelLookup->find('count', array('conditions' => array('country_code LIKE' => '%LK%')+$conProvince));
        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $TravelLookupValueContractStatuses = $this->TravelLookupValueContractStatus->find('list', array('fields' => 'id, value', 'order' => 'value ASC'));
        $TravelChains = $this->TravelChain->find('list', array('fields' => 'id,chain_name', 'conditions' => array('chain_status' => 1, 'wtb_status' => 1, 'chain_active' => 'TRUE', array('NOT' => array('id' => 1))), 'order' => 'chain_name ASC'));
        $TravelChains = array('1' => 'No Chain') + $TravelChains;
         


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

        $this->set(compact('hotel_name', 'continent_id', 'country_id', 'city_id', 'suburb_id', 'area_id', 'TravelChains', 'status', 'active', 'chain_id', 'brand_id', 'wtb_status', 'TravelCountries', 'TravelCities', 'TravelSuburbs', 'TravelAreas', 'TravelChains', 'TravelBrands', 'TravelLookupValueContractStatuses', 'TravelLookupContinents', 'mapped_count', 'srilanka_count', 'maldives_count', 'singapore_count', 'malaysia_count', 'new_zealand_count', 'melbourne_count', 'abu_dhabi_count', 'sharjah_count', 'dubai_count', 'uae_count', 'india_count', 'phuket_count', 'pattaya_count', 'bangkok_count', 'thailand_count', 'below_three_star_count', 'three_star_count', 'four_five_star','Provinces','province_id'));
    }

    

    public function edit($id) {

        $user_id = $this->Auth->user('id');
        $role_id = $this->Session->read("role_id");
        $dummy_status = $this->Auth->user('dummy_status');
        $actio_itme_id = '';
        $flag = 0;
        // connect and login to FTP server
        $ftp_server = "50.87.144.15";
        $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, 'imageius@prop-genie.com', '_$g6_ZLuH&p@');
        
        

        $TravelCountries = array();
        $TravelCities = array();
        $TravelSuburbs = array();
        $TravelAreas = array();
        $TravelBrands = array();
        $Provinces=array();
        $ConArry = array();

        $arr = explode('_', $id);
        $id = $arr[0];
        
        if (!$id) {
            throw new NotFoundException(__('Invalid Hotel'));
        }

        $TravelHotelLookups = $this->TravelHotelLookup->findById($id);
        
        if (!$TravelHotelLookups) {
            throw new NotFoundException(__('Invalid Hotel'));
        }
        
        
       
        //echo $next_action_by;

        

        if ($this->request->is('post') || $this->request->is('put')) {



            $image1 = '';
            $image2 = '';
            $image3 = '';
            $image4 = '';


            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image1']['tmp_name'])) {               
              
                $image1 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img1'], $this->request->data['TravelHotelLookup']['image1'], $this->uploadDir, 'image1');
                $this->request->data['TravelHotelLookup']['full_img1'] = $image1;
                $this->request->data['TravelHotelLookup']['thumb_img1'] = $image1;
                $this->Image->thumbnail($this->uploadDir.'/'.$image1,'thumbs',$this->Width,$this->Height);
                 
                $file_thum= $this->uploadDir.'/thumbs/'.$image1;
                $dstfile_thum='uploads/hotels/thumbs/'.$image1;
                
                $file= $this->uploadDir.'/'.$image1;
                $dstfile='uploads/hotels/'.$image1;

                if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII))
                  {
                    ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                  //echo "Successfully uploaded $file.";
                  }
                else
                  {
                  echo "Error uploading $file.";
                  }

                // close connection
                ftp_close($ftp_conn);
                $this->Image->delete($image1,$this->uploadDir);
                $this->Image->delete($image1,$this->uploadDir.'/thumbs/');
 
            } else {
                unset($this->request->data['TravelHotelLookup']['image1']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image2']['tmp_name'])) {
                $image2 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['hotel_img2'], $this->request->data['TravelHotelLookup']['image2'], $this->uploadDir, 'image2');
                $this->request->data['TravelHotelLookup']['hotel_img2'] = $image2;
            } else {
                unset($this->request->data['TravelHotelLookup']['image2']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image3']['tmp_name'])) {
                $image2 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['hotel_img3'], $this->request->data['TravelHotelLookup']['image3'], $this->uploadDir, 'image3');
                $this->request->data['TravelHotelLookup']['hotel_img3'] = $image2;
            } else {
                unset($this->request->data['TravelHotelLookup']['image3']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image4']['tmp_name'])) {
                $image2 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['hotel_img4'], $this->request->data['TravelHotelLookup']['image4'], $this->uploadDir, 'image4');
                $this->request->data['TravelHotelLookup']['hotel_img4'] = $image2;
            } else {
                unset($this->request->data['TravelHotelLookup']['image4']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image5']['tmp_name'])) {
                $image2 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['hotel_img5'], $this->request->data['TravelHotelLookup']['image5'], $this->uploadDir, 'image5');
                $this->request->data['TravelHotelLookup']['hotel_img5'] = $image2;
            } else {
                unset($this->request->data['TravelHotelLookup']['image5']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image6']['tmp_name'])) {
                $image2 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['hotel_img6'], $this->request->data['TravelHotelLookup']['image6'], $this->uploadDir, 'image6');
                $this->request->data['TravelHotelLookup']['hotel_img6'] = $image2;
            } else {
                unset($this->request->data['TravelHotelLookup']['image6']);
            }

      

            //$this->request->data['TravelHotelLookup']['active'] = 'FALSE';
            //$this->request->data['TravelHotelLookup']['created_by'] = $user_id;
            //$this->request->data['TravelHotelLookup']['status'] = '4';




            $this->TravelHotelLookup->id = $id;
            if ($this->TravelHotelLookup->save($this->request->data['TravelHotelLookup'])) {
               
                $this->Session->setFlash('Your changes have been submitted. Waiting for approval at the moment...', 'success');
            }

          
            // $this->redirect(array('controller' => 'messages','action' => 'index','properties','my-properties'));
        }


        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'continent_name ASC'));
        $this->set(compact('TravelLookupContinents'));

        $TravelChains = $this->TravelChain->find('list', array('fields' => 'id,chain_name', 'conditions' => array('chain_status' => 1, 'wtb_status' => 1, 'chain_active' => 'TRUE', array('NOT' => array('id' => 1))), 'order' => 'chain_name ASC'));
        $TravelChains = array('1' => 'No Chain') + $TravelChains;
        $this->set(compact('TravelChains'));

        if ($TravelHotelLookups['TravelHotelLookup']['continent_id']) {
            $TravelCountries = $this->TravelCountry->find('list', array(
                'conditions' => array(
                    'TravelCountry.continent_id' => $TravelHotelLookups['TravelHotelLookup']['continent_id'],
                    'TravelCountry.country_status' => '1',
                    'TravelCountry.wtb_status' => '1',
                    'TravelCountry.active' => 'TRUE'
                ),
                'fields' => 'TravelCountry.id, TravelCountry.country_name',
                'order' => 'TravelCountry.country_name ASC'
            ));
        }
        $this->set(compact('TravelCountries'));

        if ($TravelHotelLookups['TravelHotelLookup']['country_id']) {
            $TravelCities = $this->TravelCity->find('all', array(
                'conditions' => array(
                    'TravelCity.country_id' => $TravelHotelLookups['TravelHotelLookup']['country_id'],
                    'TravelCity.continent_id' => $TravelHotelLookups['TravelHotelLookup']['continent_id'],
                    'TravelCity.city_status' => '1',
                    'TravelCity.wtb_status' => '1',
                    'TravelCity.active' => 'TRUE',
                    'TravelCity.province_id' => $TravelHotelLookups['TravelHotelLookup']['province_id'],
                ),
                'fields' => array('TravelCity.id', 'TravelCity.city_name', 'TravelCity.city_code'),
                'order' => 'TravelCity.city_name ASC'
            ));
            $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.id', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));
        
            
            $Provinces = $this->Province->find('list', array(
                'conditions' => array(
                    'Province.country_id' => $TravelHotelLookups['TravelHotelLookup']['country_id'],
                    'Province.continent_id' => $TravelHotelLookups['TravelHotelLookup']['continent_id'],
                    'Province.status' => '1',
                    'Province.wtb_status' => '1',
                    'Province.active' => 'TRUE'
                    //'Province.id' => $proArr
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));
            
        }

        $this->set(compact('TravelCities'));

        if ($TravelHotelLookups['TravelHotelLookup']['city_id']) {
            $TravelSuburbs = $this->TravelSuburb->find('list', array(
                'conditions' => array(
                    'TravelSuburb.country_id' => $TravelHotelLookups['TravelHotelLookup']['country_id'],
                    'TravelSuburb.city_id' => $TravelHotelLookups['TravelHotelLookup']['city_id'],
                    'TravelSuburb.status' => '1',
                    'TravelSuburb.wtb_status' => '1',
                    'TravelSuburb.active' => 'TRUE'
                ),
                'fields' => 'TravelSuburb.id, TravelSuburb.name',
                'order' => 'TravelSuburb.name ASC'
            ));
        }

        $this->set(compact('TravelSuburbs'));

        if ($TravelHotelLookups['TravelHotelLookup']['suburb_id']) {
            $TravelAreas = $this->TravelArea->find('list', array(
                'conditions' => array(
                    'TravelArea.suburb_id' => $TravelHotelLookups['TravelHotelLookup']['suburb_id'],
                    'TravelArea.area_status' => '1',
                    'TravelArea.wtb_status' => '1',
                    'TravelArea.area_active' => 'TRUE'
                ),
                'fields' => 'TravelArea.id, TravelArea.area_name',
                'order' => 'TravelArea.area_name ASC'
            ));
        }

        $this->set(compact('TravelAreas'));

        if ($TravelHotelLookups['TravelHotelLookup']['chain_id'] > 1) {
            $TravelBrands = $this->TravelBrand->find('list', array(
                'conditions' => array(
                    'TravelBrand.brand_chain_id' => $TravelHotelLookups['TravelHotelLookup']['chain_id'],
                    'TravelBrand.brand_status' => '1',
                    'TravelBrand.wtb_status' => '1',
                    'TravelBrand.brand_active' => 'TRUE'
                ),
                'fields' => 'TravelBrand.id, TravelBrand.brand_name',
                'order' => 'TravelBrand.brand_name ASC'
            ));
        }
        $TravelBrands = array('1' => 'No Brand') + $TravelBrands;

        $TravelLookupPropertyTypes = $this->TravelLookupPropertyType->find('list', array('fields' => 'id,value','order' => 'value ASC'));
        $TravelLookupRateTypes = $this->TravelLookupRateType->find('list', array('fields' => 'id,value','order' => 'value ASC'));
        $TravelHotelRoomSuppliers = $this->TravelHotelRoomSupplier->find('all', array('conditions' => array('TravelHotelRoomSupplier.hotel_id' => $id)));
        $this->set(compact('TravelBrands','actio_itme_id', 'TravelHotelRoomSuppliers','Provinces','TravelLookupPropertyTypes','TravelLookupRateTypes'));
        

        $this->request->data = $TravelHotelLookups;
    }



}

