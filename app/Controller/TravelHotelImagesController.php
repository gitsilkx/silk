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

        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
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
                $this->request->data['TravelHotelLookup']['full_img1'] = 'http://imageius.com/uploads/hotels/'.$image1;
                $this->request->data['TravelHotelLookup']['thumb_img1'] = 'http://imageius.com/uploads/hotels/thumbs/'.$image1;
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

      
             $HotelId = $id;
            $HotelCode = $this->data['TravelHotelLookup']['hotel_code'];
            $HotelName = $this->data['TravelHotelLookup']['hotel_name'];
            $AreaId = $this->data['TravelHotelLookup']['area_id'];
           // $AreaCode = $this->data['TravelHotelLookup']['area_code'];
            

            $AreaName = $this->data['TravelHotelLookup']['area_name'];
            
            $SuburbId = $this->data['TravelHotelLookup']['suburb_id'];
           
            $SuburbName = $this->data['TravelHotelLookup']['suburb_name'];

            $CityId = $this->data['TravelHotelLookup']['city_id'];
           
            $CityName = $this->data['TravelHotelLookup']['city_name'];
            $CityCode = $this->data['TravelHotelLookup']['city_code'];
            $CountryId = $this->data['TravelHotelLookup']['country_id'];
            $CountryName = $this->data['TravelHotelLookup']['country_name'];
            $CountryCode = $this->data['TravelHotelLookup']['country_code'];
            $ContinentId = $this->data['TravelHotelLookup']['continent_id'];
            $ContinentName = $this->data['TravelHotelLookup']['continent_name'];
            $ContinentCode = $this->data['TravelHotelLookup']['continent_code'];
            $BrandId = $this->data['TravelHotelLookup']['brand_id'];
            
            $BrandName = $this->data['TravelHotelLookup']['brand_name'];
            $ChainId = $this->data['TravelHotelLookup']['chain_id'];
            
            $ChainName = $this->data['TravelHotelLookup']['chain_name'];
            $HotelComment = $this->data['TravelHotelLookup']['hotel_comment'];
            $Star = $TravelHotelLookups['TravelHotelLookup']['star'];
            $Keyword = $TravelHotelLookups['TravelHotelLookup']['keyword'];
            $StandardRating = $TravelHotelLookups['TravelHotelLookup']['standard_rating'];
            $HotelRating = $TravelHotelLookups['TravelHotelLookup']['hotel_rating'];
            $FoodRating = $TravelHotelLookups['TravelHotelLookup']['food_rating'];
            $ServiceRating = $TravelHotelLookups['TravelHotelLookup']['service_rating'];
            $LocationRating = $TravelHotelLookups['TravelHotelLookup']['location_rating'];
            $ValueRating = $TravelHotelLookups['TravelHotelLookup']['value_rating'];
            $OverallRating = $TravelHotelLookups['TravelHotelLookup']['overall_rating'];
            $HotelImage1 = $this->data['TravelHotelLookup']['full_img1'];
            $HotelImage2 =$this->data['TravelHotelLookup']['full_img2'];
            $HotelImage3 = $this->data['TravelHotelLookup']['full_img3'];
            $HotelImage4 = $this->data['TravelHotelLookup']['full_img4'];
            $HotelImage5 = $this->data['TravelHotelLookup']['full_img5'];
            $HotelImage6 = $this->data['TravelHotelLookup']['full_img6'];
            $ThumbImage1 = $this->data['TravelHotelLookup']['thumb_img1'];
            $ThumbImage2 = $this->data['TravelHotelLookup']['thumb_img2'];
            $ThumbImage3 = $this->data['TravelHotelLookup']['thumb_img3'];
            $ThumbImage4 = $this->data['TravelHotelLookup']['thumb_img4'];
            $ThumbImage5 = $this->data['TravelHotelLookup']['thumb_img5'];
            $ThumbImage6 = $this->data['TravelHotelLookup']['thumb_img6'];
            $Logo = $TravelHotelLookups['TravelHotelLookup']['logo'];
            $Logo1 = $TravelHotelLookups['TravelHotelLookup']['logo1'];
            $BusinessCenter = $TravelHotelLookups['TravelHotelLookup']['business_center'];
            $MeetingFacilities = $TravelHotelLookups['TravelHotelLookup']['meeting_facilities'];
            $DiningFacilities = $TravelHotelLookups['TravelHotelLookup']['dining_facilities'];
            $BarLounge = $TravelHotelLookups['TravelHotelLookup']['bar_lounge'];
            $FitnessCenter = $TravelHotelLookups['TravelHotelLookup']['fitness_center'];
            $Pool = $TravelHotelLookups['TravelHotelLookup']['pool'];
            $Golf = $TravelHotelLookups['TravelHotelLookup']['golf'];
            $Tennis = $TravelHotelLookups['TravelHotelLookup']['tennis'];
            $Kids = $TravelHotelLookups['TravelHotelLookup']['kids'];
            $Handicap = $TravelHotelLookups['TravelHotelLookup']['handicap'];
            $URLHotel = $TravelHotelLookups['TravelHotelLookup']['url_hotel'];
            $Address = $this->data['TravelHotelLookup']['address'];
            $PostCode = $TravelHotelLookups['TravelHotelLookup']['post_code'];
            $NoRoom = $TravelHotelLookups['TravelHotelLookup']['no_room'];
            $Active = $this->data['TravelHotelLookup']['active'];
            if ($Active == 'TRUE')
                $Active = '1';
            else
                $Active = '0';
            $ReservationEmail = $TravelHotelLookups['TravelHotelLookup']['reservation_email'];
            $ReservationContact = $TravelHotelLookups['TravelHotelLookup']['reservation_contact'];
            $EmergencyContactName = $TravelHotelLookups['TravelHotelLookup']['emergency_contact_name'];
            $ReservationDeskNumber = $TravelHotelLookups['TravelHotelLookup']['reservation_desk_number'];
            $EmergencyContactNumber = $TravelHotelLookups['TravelHotelLookup']['emergency_contact_number'];
            $GPSPARAM1 = $TravelHotelLookups['TravelHotelLookup']['gps_prm_1'];
            $GPSPARAM2 = $TravelHotelLookups['TravelHotelLookup']['gps_prm_2'];
            $ProvinceId = $this->data['TravelHotelLookup']['province_id'];
            $ProvinceName = $this->data['TravelHotelLookup']['province_name'];
            $TopHotel = strtolower($TravelHotelLookups['TravelHotelLookup']['top_hotel']);
            $PropertyType = $TravelHotelLookups['TravelHotelLookup']['property_type'];
            $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');

            $is_update = $TravelHotelLookups['TravelHotelLookup']['is_updated'];
            if ($is_update == 'Y')
                $actiontype = 'Update';
            else
                $actiontype = 'AddNew';


            //$this->request->data['TravelHotelLookup']['active'] = 'FALSE';
            //$this->request->data['TravelHotelLookup']['created_by'] = $user_id;
            //$this->request->data['TravelHotelLookup']['status'] = '4';




            $this->TravelHotelLookup->id = $id;
            if ($this->TravelHotelLookup->save($this->request->data['TravelHotelLookup'])) {
               $content_xml_str = '<soap:Body>
                                        <ProcessXML xmlns="http://www.travel.domain/">
                                            <RequestInfo>
                                                <ResourceDataRequest>
                                                    <RequestAuditInfo>
                                                        <RequestType>PXML_WData_Hotel</RequestType>
                                                        <RequestTime>' . $CreatedDate . '</RequestTime>
                                                        <RequestResource>Silkrouters</RequestResource>
                                                    </RequestAuditInfo>
                                                    <RequestParameters>                        
                                                        <ResourceData>
                                                            <ResourceDetailsData srno="1" actiontype="' . $actiontype . '">
                                                                <HotelId>34985</HotelId>
                                <HotelCode><![CDATA[ISTASP]]></HotelCode>
                                <HotelName><![CDATA[Aspen Hotel Istanbul]]></HotelName>
                                <AreaId>5414</AreaId>
                                <AreaCode><![CDATA[ISTCEN  ]]></AreaCode>
                                <AreaName><![CDATA[Aksaray]]></AreaName>
                                <SuburbId>503</SuburbId>
                                <SuburbCode>NA</SuburbCode>
                                <SuburbName><![CDATA[Fatih ]]></SuburbName>
                                <CityId>7211</CityId>
                                <CityCode><![CDATA[IST]]></CityCode>
                                <CityName><![CDATA[ Istanbul]]></CityName>
                                <CountryId>225</CountryId>
                                <CountryCode><![CDATA[TR ]]></CountryCode>
                                <CountryName><![CDATA[ Turkey]]></CountryName>
                                <ContinentId>4</ContinentId>
                                <ContinentCode><![CDATA[EU ]]></ContinentCode>
                                <ContinentName><![CDATA[ Europe]]></ContinentName>
                                <ProvinceId>345</ProvinceId>
                                <ProvinceName>Istanbul </ProvinceName>
                                <BrandId>1</BrandId>
                                <BrandName><![CDATA[No Brand]]></BrandName>
                                <ChainId>1</ChainId>
                                <ChainName><![CDATA[No Chain]]></ChainName>
                                <HotelComment></HotelComment>
                                <Star>3</Star>
                                <Keyword><![CDATA[]]></Keyword>
                                <StandardRating />
                                <HotelRating>0</HotelRating>
                                <FoodRating />
                                <ServiceRating />
                                <LocationRating />
                                <ValueRating />
                                <OverallRating />

                                <HotelImage1Full />
                                <HotelImage2Full />
                                <HotelImage3Full />
                                <HotelImage4Full />
                                <HotelImage5Full />
                                <HotelImage6Full />

                                <HotelImage1Thumb />
                                <HotelImage2Thumb />
                                <HotelImage3Thumb />
                                <HotelImage4Thumb />
                                <HotelImage5Thumb />
                                <HotelImage6Thumb />

                                <IsImage>false</IsImage>
                                <IsPage>false</IsPage>

                                <Logo />
                                <Logo1 />
                                <BusinessCenter />
                                <MeetingFacilities />
                                <DiningFacilities />
                                <BarLounge />
                                <FitnessCenter />
                                <Pool />
                                <Golf />
                                <Tennis />
                                <Kids />
                                <Handicap />
                                <URLHotel><![CDATA[http://www.aspenhotelistanbul.com/]]></URLHotel>
                                <Address><![CDATA[Aksaray Avenue No.25, Laleli, Fatih, Istanbul, Turkey 34150]]></Address>
                                <PostCode>34150</PostCode>
                                <NoRoom>63</NoRoom>
                                <Active>1</Active>

                                <ReservationEmail><![CDATA[]]></ReservationEmail>
                                <ReservationContact><![CDATA[]]></ReservationContact>
                                <EmergencyContactName><![CDATA[]]></EmergencyContactName>
                                <ReservationDeskNumber><![CDATA[+90 212 518 5361]]></ReservationDeskNumber>
                                <EmergencyContactNumber><![CDATA[+90 212 518 5361]]></EmergencyContactNumber>
                                <GPSPARAM1>41.0082997</GPSPARAM1>
                                <GPSPARAM2>28.9562657</GPSPARAM2>
                                <TopHotel />
                                <PropertyType>1</PropertyType>
                                <ApprovedBy>0</ApprovedBy>
                                <ApprovedDate>1111-01-01T00:00:00</ApprovedDate>
                                <CreatedBy>181</CreatedBy>
                                <CreatedDate>2016-08-16T06:03:24</CreatedDate>
                                                            </ResourceDetailsData>
                         
                                                    </ResourceData>
                                                    </RequestParameters>
                                                </ResourceDataRequest>
                                            </RequestInfo>
                                        </ProcessXML>
                                    </soap:Body>';


            $log_call_screen = 'Edit - Hotel';

            $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);

                //$xml_arr = $this->xml2array($order_return);
                 //echo htmlentities($xml_string);
                 //pr($xml_arr);
                 //die;

                if ($xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_HOTEL']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0] == '201') {
                    $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_HOTEL']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0];
                    $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_HOTEL']['RESPONSEAUDITINFO']['UPDATEINFO']['STATUS'][0];
                    $xml_msg = "Foreign record has been successfully created [Code:$log_call_status_code]";
                    $this->TravelHotelLookup->updateAll(array('TravelHotelLookup.wtb_status' => "'1'", 'TravelHotelLookup.is_updated' => "'Y'"), array('TravelHotelLookup.id' => $HotelId));
                } else {

                    $log_call_status_message = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_HOTEL']['RESPONSEAUDITINFO']['ERRORINFO']['ERROR'][0];
                    $log_call_status_code = $xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT']['RESOURCEDATA_HOTEL']['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0]; // RESPONSEID
                    $xml_msg = "There was a problem with foreign record creation [Code:$log_call_status_code]";
                    $this->TravelHotelLookup->updateAll(array('TravelHotelLookup.wtb_status' => "'2'"), array('TravelHotelLookup.id' => $HotelId));
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
            $message = 'Local record has been successfully updated.<br />' . $xml_msg;
            $a =  date('m/d/Y H:i:s', strtotime('-1 hour'));
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

                $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Log Id [' . $LogId . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();
            }

                $this->Session->setFlash($message, 'success');
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

