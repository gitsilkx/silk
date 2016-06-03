<?php/** * MassOperations controller. * * This file will render views from views/MassOperations/ * * PHP 5 * * CakePHP(tm) : Rapid Development Framework (http://cakephp.org) * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org) * * Licensed under The MIT License * For full copyright and license information, please see the LICENSE.txt * Redistributions of files must retain the above copyright notice. * * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org) * @link          http://cakephp.org CakePHP(tm) Project * @package       app.Controller * @since         CakePHP(tm) v 0.2.9 * @license       http://www.opensource.org/licenses/mit-license.php MIT License */App::uses('AppController', 'Controller');/** * MassOperations controller * * * @package       app.Controller * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html */class MassOperationsController extends AppController {    public $uses = array('Province', 'TravelCity', 'TravelCountry', 'TravelLookupContinent', 'TravelHotelLookup', 'TravelSuburb', 'TravelArea',        'TravelHotelLookup');    public function index() {            }    public function insert_operations() {        if ($this->request->is('post')) {            $table = $this->data['InsertTable']['table'];            if (!empty($this->data['InsertTable']['file']['name'])) {                $file = $this->data['InsertTable']['file'];                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/MassOperations/' . $file['name']);                $array = $this->import($file['name'], $table);                $this->Session->setFlash($array['messages'][0], 'success');                if ($table == 'Province')                    $this->redirect(array('controller' => 'provinces', 'action' => 'index'));            }        }    }    public function update_operations() {        $TravelCities = array();        $TravelLookupContinents = array();        $Provinces = array();        $conitinent_name = '';        $province_name = '';        $continent_code = '';        $proceed = 'display:block';        $update = 'display:none';        $generate = 'display:none';        $common = 'display:none';        $list_city = 'display:none';        $update_query = '';        $disabled = '';        $selected = array();        $city_active = array();        $hotel_active = array();        $area_active = array();        $all_active = array();        $query = '';        $city = '';        if ($this->request->is('post')) {                        if (!empty($this->data['InsertTable']['active'])) {                $active = $this->data['InsertTable']['active'];                $city_active = array('TravelCity.active' => "'" .$active. "'");                $hotel_active = array('TravelHotelLookup.active' => "'" .$active. "'");                $area_active = array('TravelArea.area_active' => "'" .$active. "'");                $all_active = array('active' => "'" .$active. "'");                $query = ',active = '.$active;              }            $table = $this->request->data['InsertTable']['table'];            $country_id = $this->request->data['InsertTable']['country_id'];            $continent_id = $this->request->data['InsertTable']['continent_id'];            $province_id = $this->request->data['InsertTable']['province_id'];            $update_city_id = $this->request->data['InsertTable']['update_city_id'];            $continent_id = $this->request->data['InsertTable']['continent_id'];            $update_country_id = $this->request->data['InsertTable']['update_country_id'];            $update_country_code = $this->request->data['InsertTable']['update_country_code'];            $update_country_name = $this->request->data['InsertTable']['update_country_name'];            $city_id = $this->request->data['InsertTable']['city_id'];            $city_code = $this->request->data['InsertTable']['city_code'];            $city_name = $this->request->data['InsertTable']['city_name'];            $continent_name = $this->request->data['InsertTable']['continent_name'];            $continent_code = $this->request->data['InsertTable']['continent_code'];            $province_name = $this->request->data['InsertTable']['province_name'];            if ($table == 'TravelCity') {                $proceed = 'display:none';                $update = 'display:block';                $city = 'display:none';                                $generate = 'display:block';                $common = 'display:none';                $list_city = 'display:block';            } else {                $proceed = 'display:none';                $update = 'display:block';                $city = 'display:block';                $generate = 'display:block';                $common = 'display:block';                $list_city = 'display:none';            }            if (isset($this->request->data['proceed']) == 'Proceed') {                            } elseif (isset($this->request->data['update']) == 'Update') {                if ($table == 'TravelCity') {                                        if (count($this->data['InsertTable']['city_id']) > 0) {                        foreach ($this->data['InsertTable']['city_id'] as $val) {                            $condition[] = $val;                            $save = array(                                'TravelCity.province_id' => $province_id,                                'TravelCity.province_name' => "'" . $province_name . "'",                                'TravelCity.continent_id' => $continent_id,                                'TravelCity.continent_name' => "'" . $continent_name . "'",                                'country_id' => "'" . $update_country_id . "'",                                'country_code' => "'" . $update_country_code . "'",                                'country_name' => "'" . $update_country_name . "'"                            );                        }                        $saveAll = $save + $city_active;                        $this->TravelCity->updateAll($saveAll, array('TravelCity.id' => $condition));                                                //$log = $this->TravelCity->getDataSource()->getLog(false, false);                               //debug($log);                        //die;                    }                } elseif ($table == 'TravelHotelLookup') {                                        $this->$table->updateAll(array('continent_id' => $continent_id, 'continent_name' => "'" . $continent_name . "'", 'continent_code' => "'" . $continent_code . "'", 'province_id' => $province_id, 'province_name' => "'" . $province_name . "'", 'country_id' => "'" . $update_country_id . "'", 'country_code' => "'" . $update_country_code . "'", 'country_name' => "'" . $update_country_name . "'", 'city_id' => "'" . $city_id . "'", 'city_name' => "'" . $city_name . "'", 'city_code' => "'" . $city_code . "'") + $hotel_active, array($table . '.country_id' => $country_id, $table . '.city_id' => $update_city_id));                }                elseif($table == 'TravelArea'){                    $this->$table->updateAll(array('continent_id' => $continent_id, 'continent_name' => "'" . $continent_name . "'", 'province_id' => $province_id, 'province_name' => "'" . $province_name . "'", 'country_id' => "'" . $update_country_id . "'", 'country_name' => "'" . $update_country_name . "'", 'city_id' => "'" . $city_id . "'", 'city_name' => "'" . $city_name . "'") + $area_active, array($table . '.country_id' => $country_id, $table . '.city_id' => $update_city_id));                }                else {                    $this->$table->updateAll(array('continent_id' => $continent_id, 'continent_name' => "'" . $continent_name . "'", 'province_id' => $province_id, 'province_name' => "'" . $province_name . "'", 'country_id' => "'" . $update_country_id . "'", 'country_name' => "'" . $update_country_name . "'", 'city_id' => "'" . $city_id . "'", 'city_name' => "'" . $city_name . "'") + $all_active, array($table . '.country_id' => $country_id, $table . '.city_id' => $update_city_id));                }                $this->Session->setFlash('Update successfully.', 'success');            } elseif (isset($this->request->data['generate']) == 'Generate') {                if ($table == 'TravelCity') {                    if (count($this->data['InsertTable']['city_id']) > 0) {                        foreach ($this->data['InsertTable']['city_id'] as $val) {                            $selected[] = $val;                            $update_query .= 'UPDATE TABLE ' . $table . ' SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',country_id = ' . $update_country_id .',country_code = ' . $update_country_code .',country_name = ' . $update_country_name .',province_id = ' . $province_id . ',province_name = ' . $province_name .$query. ' WHERE  city_id = ' . $val . '<br>';                        }                    }                } elseif ($table == 'TravelHotelLookup') {                    $update_query = 'UPDATE TABLE ' . $table . ' SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',continent_code = ' . $continent_code . ',province_id = ' . $province_id . ',province_name = ' . $province_name .',country_id = ' . $update_country_id .',country_code = ' . $update_country_code .',country_name = ' . $update_country_name .',city_id = ' . $city_id .',city_name = ' . $city_name .',city_code = ' . $city_code .$query. ' WHERE country_id = ' . $country_id . ' AND city_id = ' . $update_city_id;                } else {                    $update_query = 'UPDATE TABLE ' . $table . ' SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',province_id = ' . $province_id . ',province_name = ' . $province_name . ',country_id = ' . $update_country_id .',country_name = ' . $update_country_name .',city_id = ' . $city_id .',city_name = ' . $city_name .' WHERE country_id = ' . $country_id  .$query. ' AND city_id = ' . $update_city_id;                }                //$disabled = 'disabled';                $this->Session->setFlash($update_query, 'success');                }            $TravelCities = $this->TravelCity->find('list', array('fields' => array('id', 'city_name'), 'conditions' => array('country_id' => $country_id), 'order' => 'city_name ASC'));            $UpdateTravelCities = $this->TravelCity->find('list', array('fields' => array('id', 'city_name'), 'conditions' => array('country_id' => $update_country_id), 'order' => 'city_name ASC'));            $TravelLookupContinents = $this->TravelLookupContinent->find('all', array('fields' => array('TravelLookupContinent.id', 'TravelLookupContinent.continent_name', 'TravelLookupContinent.continent_code'), 'order' => 'TravelLookupContinent.continent_name ASC'));            $TravelLookupContinents = Set::combine($TravelLookupContinents, '{n}.TravelLookupContinent.id', array('%s - %s', '{n}.TravelLookupContinent.continent_code', '{n}.TravelLookupContinent.continent_name'));            //$TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'order' => 'continent_name ASC'));            $Provinces = $this->Province->find('list', array('fields' => array('id', 'name')));            //pr($Provinces);        }        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));        $this->set(compact('TravelCountries','city','UpdateTravelCities', 'TravelCities', 'generate', 'proceed', 'update', 'TravelLookupContinents', 'Provinces', 'common', 'list_city', 'selected'));    }    public function import($filename, $table) {        $i = 0;        $error = null;        $filename = WWW_ROOT . 'uploads/MassOperations/' . $filename;        $handle = fopen($filename, "r");        $header = fgetcsv($handle);        $message = '';        $return = array(            //'messages' => array(),            'errors' => array(),        );        while (($row = fgetcsv($handle)) !== FALSE) {            $i++;            $data = array();            foreach ($header as $k => $head) {                if (strpos($head, '.') !== false) {                    $h = explode('.', $head);                    $data[$h[0]][$h[1]] = (isset($row[$k])) ? $row[$k] : '';                } else {                    $data[$table][$head] = (isset($row[$k])) ? $row[$k] : '';                }            }            $id = isset($row[0]) ? $row[0] : 0;            if ($id <> 'NULL') {                $DataArray = $this->$table->find('all', array('conditions' => array($table . '.id' => $id)));                if (!empty($DataArray)) {                    $apiConfig = (isset($DataArray[0][$table]) && is_array($DataArray[0][$table])) ? ($DataArray[0][$table]) : array();                                        $data[$table] = array_merge($apiConfig, $data[$table]);                    $message = 'updated.';                } else {                    $this->$table->id = $id;                }            } else {                $this->$table->create();                $message = 'saved.';            }            //pr($data);            //die;            if ($this->$table->saveAll($data)) {                //$return['messages'][] = __(sprintf('%d rows has been '.$message, $i), true);            } else {                //$return['errors'][] = __(sprintf("Listing Skip Row %d failed to save.", $i), true);            }        }        $return['messages'][] = __(sprintf('%d rows has been ' . $message, $i), true);        fclose($handle);        return $return;    }    public function hotel() {                $search_condition = array();        $TravelLookupContinents = array();        $TravelCountries = array();        $Provinces = array();        $TravelCities = array();        $check_condition = array();        $selected = array();        $active_array = array();        $conditions = '';        $update_con = array();        if (!empty($this->request->params['named']['continent_id'])) {            $conditions .= 'TravelHotelLookup.continent_id = '.$this->request->params['named']['continent_id'];            //array_push($update_condition, array('TravelHotelLookup.continent_id'  => $this->request->params['named']['continent_id']));        }                                if (!empty($this->request->params['named']['province_id'])) {             $conditions .= ' AND TravelHotelLookup.province_id = '.$this->request->params['named']['province_id'];            //$province_id = $this->request->params['named']['province_id'];        }                if (!empty($this->request->params['named']['city_id'])) {            $conditions .= ' TravelHotelLookup.city_id = '.$this->request->params['named']['city_id'];            //$country_id = $this->request->params['named']['country_id'];        }                if (!empty($this->request->params['named']['country_id'])) {            $conditions .= ' AND TravelHotelLookup.country_id = '.$this->request->params['named']['country_id'];            //$country_id = $this->request->params['named']['country_id'];        }                        if (count($this->params['pass'])) {           foreach ($this->params['pass'] as $key => $value) {                array_push($search_condition, array('TravelHotelLookup.' . $key => $value));                           }                        } elseif (count($this->params['named'])) {            foreach ($this->params['named'] as $key => $value) {                array_push($search_condition, array('TravelHotelLookup.' . $key => $value));                           }        }                if ($this->request->is('post')) {                        if (!empty($this->data['MassOperation']['active'])) {                $active = $this->data['MassOperation']['active'];                $active_array = array('TravelHotelLookup.active' => "'".$active."'");            }            $continent_id = $this->request->data['MassOperation']['continent_id'];            $province_id = $this->request->data['MassOperation']['province_id'];                        $continent_name = $this->request->data['MassOperation']['continent_name'];            $continent_code = $this->request->data['MassOperation']['continent_code'];            $province_name = $this->request->data['MassOperation']['province_name'];            $country_code = $this->request->data['MassOperation']['country_code'];            $country_id = $this->request->data['MassOperation']['country_id'];            $country_name = $this->request->data['MassOperation']['country_name'];            $city_id = $this->request->data['MassOperation']['city_id'];            $city_name = $this->request->data['MassOperation']['city_name'];            $city_code = $this->request->data['MassOperation']['city_code'];                        if(isset($this->data['MassOperation']['check'])){                foreach ($this->data['MassOperation']['check'] as $val) {                                        $selected[] = $val;                }                array_push($update_con, array('NOT' => array('TravelHotelLookup.id' => $selected)));                $aa = implode(',', $selected);                                $conditions .= ' AND TravelHotelLookup.id NOT IN ('.$aa.')';            }            //pr($check_condition);            array_push($update_con, $search_condition);            /*             $update_con = $search_condition + $check_condition;            if(!empty($check_condition))                $update_con = $search_condition + $check_condition;            else                $update_con = $search_condition;            */            //pr($update_con);            //die;           if (isset($this->request->data['update']) == 'Update') {                                    $this->TravelHotelLookup->updateAll(array('TravelHotelLookup.continent_id' => $continent_id, 'TravelHotelLookup.continent_name' => "'" . $continent_name . "'", 'TravelHotelLookup.continent_code' => "'" . $continent_code . "'", 'TravelHotelLookup.country_id' => "'" . $country_id . "'", 'TravelHotelLookup.country_code' => "'" . $country_code . "'", 'TravelHotelLookup.country_name' => "'" . $country_name . "'", 'TravelHotelLookup.city_id' => "'" . $city_id . "'", 'TravelHotelLookup.city_code' => "'" . $city_code . "'", 'TravelHotelLookup.city_name' => "'" . $city_name . "'", 'TravelHotelLookup.province_id' => $province_id, 'TravelHotelLookup.province_name' => "'" . $province_name . "'") + $active_array , $update_con);                    //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);                           //debug($log);                    //die;                                    $this->Session->setFlash('Update successfully.', 'success');            } elseif (isset($this->request->data['generate']) == 'Generate') {                                 if (!empty($this->data['MassOperation']['active']))                     $update_query = 'UPDATE TABLE Hotel SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',continent_code = ' . $continent_code . ',country_id = ' . $country_id . ',country_code = ' . $country_code . ',country_name = ' . $country_name . ',city_id = ' . $city_id . ',city_code = ' . $city_code . ',city_name = ' . $city_name . ',province_id = ' . $province_id . ',province_name = ' . $province_name . ',active = ' . $active .' WHERE '.$conditions;                else                    $update_query = 'UPDATE TABLE Hotel SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',continent_code = ' . $continent_code . ',country_id = ' . $country_id . ',country_code = ' . $country_code . ',country_name = ' . $country_name . ',city_id = ' . $city_id . ',city_code = ' . $city_code . ',city_name = ' . $city_name . ',province_id = ' . $province_id . ',province_name = ' . $province_name . ' WHERE '.$conditions;                $count = $this->TravelHotelLookup->find('count', array('conditions' => array($conditions)));                    $update_query .="<br><br>Number of Records that will be impacted by this Operation = ".$count;//pr($count);                //$disabled = 'disabled';                $this->Session->setFlash($update_query, 'success');            }            $TravelCities = $this->TravelCity->find('all', array(                'conditions' => array(                    'TravelCity.province_id' => $province_id,                    'TravelCity.city_status' => '1',                    'TravelCity.wtb_status' => '1',                    'TravelCity.active' => 'TRUE'                ),                'fields' => array('TravelCity.id', 'TravelCity.city_code', 'TravelCity.city_name'),                'order' => 'TravelCity.city_code ASC'            ));                        $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.id', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));            //$TravelCities = $this->TravelCity->find('list', array('fields' => array('id', 'city_name'), 'conditions' => array('country_id' => $country_id), 'order' => 'city_name ASC'));            $TravelLookupContinents = $this->TravelLookupContinent->find('all', array('fields' => array('TravelLookupContinent.id', 'TravelLookupContinent.continent_name', 'TravelLookupContinent.continent_code'), 'order' => 'TravelLookupContinent.continent_name ASC'));            $TravelLookupContinents = Set::combine($TravelLookupContinents, '{n}.TravelLookupContinent.id', array('%s - %s', '{n}.TravelLookupContinent.continent_code', '{n}.TravelLookupContinent.continent_name'));            $TravelCountries = $this->TravelCountry->find('all', array(                'conditions' => array(                    'TravelCountry.continent_id' => $continent_id,                    'TravelCountry.country_status' => '1',                    'TravelCountry.wtb_status' => '1',                    'TravelCountry.active' => 'TRUE'                ),                'fields' => array('TravelCountry.id', 'TravelCountry.country_name', 'TravelCountry.country_code'),                'order' => 'TravelCountry.country_name ASC'            ));            $TravelCountries = Set::combine($TravelCountries, '{n}.TravelCountry.id', array('%s - %s', '{n}.TravelCountry.country_code', '{n}.TravelCountry.country_name'));            $Provinces = $this->Province->find('list', array('fields' => array('id', 'name'), 'conditions' => array('country_id' => $country_id)));            //pr($Provinces);        }                        $this->paginate['order'] = array('TravelHotelLookup.city_code' => 'asc');        $this->set('TravelHotelLookups', $this->paginate("TravelHotelLookup", $search_condition));                                $TravelLookupContinents = $this->TravelLookupContinent->find('all', array('fields' => array('TravelLookupContinent.id', 'TravelLookupContinent.continent_name', 'TravelLookupContinent.continent_code'), 'order' => 'TravelLookupContinent.continent_name ASC'));        $TravelLookupContinents = Set::combine($TravelLookupContinents, '{n}.TravelLookupContinent.id', array('%s - %s', '{n}.TravelLookupContinent.continent_code', '{n}.TravelLookupContinent.continent_name'));              $this->set(compact('TravelLookupContinents','TravelCountries','Provinces','TravelCities','selected'));                //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);               //debug($log);        //die;    }}