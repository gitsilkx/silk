<?php/** * MassOperations controller. * * This file will render views from views/MassOperations/ * * PHP 5 * * CakePHP(tm) : Rapid Development Framework (http://cakephp.org) * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org) * * Licensed under The MIT License * For full copyright and license information, please see the LICENSE.txt * Redistributions of files must retain the above copyright notice. * * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org) * @link          http://cakephp.org CakePHP(tm) Project * @package       app.Controller * @since         CakePHP(tm) v 0.2.9 * @license       http://www.opensource.org/licenses/mit-license.php MIT License */App::uses('AppController', 'Controller');App::uses('Xml', 'Utility');App::uses('CakeEmail', 'Network/Email');/** * MassOperations controller * * * @package       app.Controller * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html */class MassOperationsController extends AppController {    public $uses = array('Province', 'TravelCity', 'TravelCountry', 'TravelLookupContinent', 'TravelHotelLookup', 'TravelSuburb', 'TravelArea',        'TravelHotelLookup','LogCall','User');    public function index() {            }    public function insert_operations() {        if ($this->request->is('post')) {            $table = $this->data['InsertTable']['table'];            if (!empty($this->data['InsertTable']['file']['name'])) {                $file = $this->data['InsertTable']['file'];                $file_location = WWW_ROOT . 'uploads/MassOperations/' . $file['name'];                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/MassOperations/' . $file['name']);                                                /*                 * Testing                 */                 // open the file             $handle = fopen($file_location, "r");             // read the 1st row as headings             $header = fgetcsv($handle);                          // read each data row in the file            $i = 0;             while (($row = fgetcsv($handle)) !== FALSE) {                     $i++;                     $data = array();                     // for each header field                     foreach ($header as $k=>$head) {                             // get the data field from Model.field                             if (strpos($head,'.')!==false) {                                     $h = explode('.',$head);                 #die(debug($h));                                     $data[$h[0]][$h[1]]=isset($row[$k]) ? $row[$k] : '';                            }                             // get the data field from field                            else {                                     $data[$table][$head]=isset($row[$k]) ? $row[$k]: '';                            }                                                }                                          pr($row);                     $this->$table->create();                     //$this->$table->save($data);             }             // close the file             fclose($handle);                echo $i;                die;                                $array = $this->import($file['name'], $table);                                $this->Session->setFlash($array['messages'][0], 'success');                if ($table == 'Province')                    $this->redirect(array('controller' => 'provinces', 'action' => 'index'));            }        }    }    public function update_operations() {        $TravelCities = array();        $TravelLookupContinents = array();        $Provinces = array();        $conitinent_name = '';        $province_name = '';        $continent_code = '';        $proceed = 'display:block';        $update = 'display:none';        $generate = 'display:none';        $common = 'display:none';        $list_city = 'display:none';        $update_query = '';        $disabled = '';        $selected = array();        $city_active = array();        $hotel_active = array();        $area_active = array();        $all_active = array();        $query = '';        $city = '';        if ($this->request->is('post')) {                        if (!empty($this->data['InsertTable']['active'])) {                $active = $this->data['InsertTable']['active'];                $city_active = array('TravelCity.active' => "'" .$active. "'");                $hotel_active = array('TravelHotelLookup.active' => "'" .$active. "'");                $area_active = array('TravelArea.area_active' => "'" .$active. "'");                $all_active = array('active' => "'" .$active. "'");                $query = ',active = '.$active;              }            $table = $this->request->data['InsertTable']['table'];            $country_id = $this->request->data['InsertTable']['country_id'];            $continent_id = $this->request->data['InsertTable']['continent_id'];            $province_id = $this->request->data['InsertTable']['province_id'];            $update_city_id = $this->request->data['InsertTable']['update_city_id'];            $continent_id = $this->request->data['InsertTable']['continent_id'];            $update_country_id = $this->request->data['InsertTable']['update_country_id'];            $update_country_code = $this->request->data['InsertTable']['update_country_code'];            $update_country_name = $this->request->data['InsertTable']['update_country_name'];            $city_id = $this->request->data['InsertTable']['city_id'];            $city_code = $this->request->data['InsertTable']['city_code'];            $city_name = $this->request->data['InsertTable']['city_name'];            $continent_name = $this->request->data['InsertTable']['continent_name'];            $continent_code = $this->request->data['InsertTable']['continent_code'];            $province_name = $this->request->data['InsertTable']['province_name'];            if ($table == 'TravelCity') {                $proceed = 'display:none';                $update = 'display:block';                $city = 'display:none';                                $generate = 'display:block';                $common = 'display:none';                $list_city = 'display:block';            } else {                $proceed = 'display:none';                $update = 'display:block';                $city = 'display:block';                $generate = 'display:block';                $common = 'display:block';                $list_city = 'display:none';            }            if (isset($this->request->data['proceed']) == 'Proceed') {                            } elseif (isset($this->request->data['update']) == 'Update') {                if ($table == 'TravelCity') {                                        if (count($this->data['InsertTable']['city_id']) > 0) {                        foreach ($this->data['InsertTable']['city_id'] as $val) {                            $condition[] = $val;                            $save = array(                                'TravelCity.province_id' => $province_id,                                'TravelCity.province_name' => "'" . $province_name . "'",                                'TravelCity.continent_id' => $continent_id,                                'TravelCity.continent_name' => "'" . $continent_name . "'",                                'country_id' => "'" . $update_country_id . "'",                                'country_code' => "'" . $update_country_code . "'",                                'country_name' => "'" . $update_country_name . "'"                            );                        }                        $saveAll = $save + $city_active;                        $this->TravelCity->updateAll($saveAll, array('TravelCity.id' => $condition));                                                //$log = $this->TravelCity->getDataSource()->getLog(false, false);                               //debug($log);                        //die;                    }                } elseif ($table == 'TravelHotelLookup') {                                        $this->$table->updateAll(array('continent_id' => $continent_id, 'continent_name' => "'" . $continent_name . "'", 'continent_code' => "'" . $continent_code . "'", 'province_id' => $province_id, 'province_name' => "'" . $province_name . "'", 'country_id' => "'" . $update_country_id . "'", 'country_code' => "'" . $update_country_code . "'", 'country_name' => "'" . $update_country_name . "'", 'city_id' => "'" . $city_id . "'", 'city_name' => "'" . $city_name . "'", 'city_code' => "'" . $city_code . "'") + $hotel_active, array($table . '.country_id' => $country_id, $table . '.city_id' => $update_city_id));                }                elseif($table == 'TravelArea'){                    $this->$table->updateAll(array('continent_id' => $continent_id, 'continent_name' => "'" . $continent_name . "'", 'province_id' => $province_id, 'province_name' => "'" . $province_name . "'", 'country_id' => "'" . $update_country_id . "'", 'country_name' => "'" . $update_country_name . "'", 'city_id' => "'" . $city_id . "'", 'city_name' => "'" . $city_name . "'") + $area_active, array($table . '.country_id' => $country_id, $table . '.city_id' => $update_city_id));                }                else {                    $this->$table->updateAll(array('continent_id' => $continent_id, 'continent_name' => "'" . $continent_name . "'", 'province_id' => $province_id, 'province_name' => "'" . $province_name . "'", 'country_id' => "'" . $update_country_id . "'", 'country_name' => "'" . $update_country_name . "'", 'city_id' => "'" . $city_id . "'", 'city_name' => "'" . $city_name . "'") + $all_active, array($table . '.country_id' => $country_id, $table . '.city_id' => $update_city_id));                }                $this->Session->setFlash('Update successfully.', 'success');            } elseif (isset($this->request->data['generate']) == 'Generate') {                if ($table == 'TravelCity') {                    if (count($this->data['InsertTable']['city_id']) > 0) {                        foreach ($this->data['InsertTable']['city_id'] as $val) {                            $selected[] = $val;                            $update_query .= 'UPDATE TABLE ' . $table . ' SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',country_id = ' . $update_country_id .',country_code = ' . $update_country_code .',country_name = ' . $update_country_name .',province_id = ' . $province_id . ',province_name = ' . $province_name .$query. ' WHERE  city_id = ' . $val . '<br>';                        }                    }                } elseif ($table == 'TravelHotelLookup') {                    $update_query = 'UPDATE TABLE ' . $table . ' SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',continent_code = ' . $continent_code . ',province_id = ' . $province_id . ',province_name = ' . $province_name .',country_id = ' . $update_country_id .',country_code = ' . $update_country_code .',country_name = ' . $update_country_name .',city_id = ' . $city_id .',city_name = ' . $city_name .',city_code = ' . $city_code .$query. ' WHERE country_id = ' . $country_id . ' AND city_id = ' . $update_city_id;                } else {                    $update_query = 'UPDATE TABLE ' . $table . ' SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',province_id = ' . $province_id . ',province_name = ' . $province_name . ',country_id = ' . $update_country_id .',country_name = ' . $update_country_name .',city_id = ' . $city_id .',city_name = ' . $city_name .' WHERE country_id = ' . $country_id  .$query. ' AND city_id = ' . $update_city_id;                }                //$disabled = 'disabled';                $this->Session->setFlash($update_query, 'success');                }            $TravelCities = $this->TravelCity->find('list', array('fields' => array('id', 'city_name'), 'conditions' => array('country_id' => $country_id), 'order' => 'city_name ASC'));            $UpdateTravelCities = $this->TravelCity->find('list', array('fields' => array('id', 'city_name'), 'conditions' => array('country_id' => $update_country_id), 'order' => 'city_name ASC'));            $TravelLookupContinents = $this->TravelLookupContinent->find('all', array('fields' => array('TravelLookupContinent.id', 'TravelLookupContinent.continent_name', 'TravelLookupContinent.continent_code'), 'order' => 'TravelLookupContinent.continent_name ASC'));            $TravelLookupContinents = Set::combine($TravelLookupContinents, '{n}.TravelLookupContinent.id', array('%s - %s', '{n}.TravelLookupContinent.continent_code', '{n}.TravelLookupContinent.continent_name'));            //$TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'order' => 'continent_name ASC'));            $Provinces = $this->Province->find('list', array('fields' => array('id', 'name')));            //pr($Provinces);        }        $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id,country_name', 'conditions' => array('country_status' => 1, 'wtb_status' => 1, 'active' => 'TRUE'), 'order' => 'country_name ASC'));        $this->set(compact('TravelCountries','city','UpdateTravelCities', 'TravelCities', 'generate', 'proceed', 'update', 'TravelLookupContinents', 'Provinces', 'common', 'list_city', 'selected'));    }    public function import($filename, $table) {        $i = 0;        $error = null;        $filename = WWW_ROOT . 'uploads/MassOperations/' . $filename;        $handle = fopen($filename, "r");        $header = fgetcsv($handle);        $message = '';        $return = array(            //'messages' => array(),            'errors' => array(),        );        while (($row = fgetcsv($handle)) !== FALSE) {            $i++;            $data = array();            foreach ($header as $k => $head) {                if (strpos($head, '.') !== false) {                    $h = explode('.', $head);                    $data[$h[0]][$h[1]] = (isset($row[$k])) ? $row[$k] : '';                } else {                    $data[$table][$head] = (isset($row[$k])) ? $row[$k] : '';                }            }            $id = isset($row[0]) ? $row[0] : 0;            if ($id <> 'NULL') {                $DataArray = $this->$table->find('all', array('conditions' => array($table . '.id' => $id)));                if (!empty($DataArray)) {                    $apiConfig = (isset($DataArray[0][$table]) && is_array($DataArray[0][$table])) ? ($DataArray[0][$table]) : array();                                        $data[$table] = array_merge($apiConfig, $data[$table]);                    $message = 'updated.';                } else {                    $this->$table->id = $id;                }            } else {                $this->$table->create();                $message = 'saved.';            }            //pr($data);            //die;            if ($this->$table->saveAll($data)) {                //$return['messages'][] = __(sprintf('%d rows has been '.$message, $i), true);            } else {                //$return['errors'][] = __(sprintf("Listing Skip Row %d failed to save.", $i), true);            }        }        $return['messages'][] = __(sprintf('%d rows has been ' . $message, $i), true);        fclose($handle);        return $return;    }    public function hotel() {                $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';        $action_URL = 'http://www.travel.domain/ProcessXML';        $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');        $user_id = $this->Auth->user('id');        $search_condition = array();        $TravelLookupContinents = array();        $TravelCountries = array();        $Provinces = array();        $TravelCities = array();        $check_condition = array();        $selected = array();        $active_array = array();        $conditions = '';        $update_con = array();        $update = false;        $local_udate = false;        $active = '';        $aa = '';        $where_continent_id = '';        $where_province_id = '';        $where_city_id = '';        $where_country_id = '';        $sqn_generate_btt = true;        if (!empty($this->request->params['named']['continent_id'])) {            $where_continent_id = $this->request->params['named']['continent_id'];            $conditions .= 'TravelHotelLookup.continent_id = '.$where_continent_id;                        //array_push($update_condition, array('TravelHotelLookup.continent_id'  => $this->request->params['named']['continent_id']));        }                                if (!empty($this->request->params['named']['province_id'])) {            $where_province_id = $this->request->params['named']['province_id'];             $conditions .= ' AND TravelHotelLookup.province_id = '.$where_province_id;                         //$province_id = $this->request->params['named']['province_id'];        }                if (!empty($this->request->params['named']['city_id'])) {            $where_city_id = $this->request->params['named']['city_id'];            $conditions .= ' TravelHotelLookup.city_id = '.$where_city_id;                        //$country_id = $this->request->params['named']['country_id'];        }                if (!empty($this->request->params['named']['country_id'])) {            $where_country_id = $this->request->params['named']['country_id'];            $conditions .= ' AND TravelHotelLookup.country_id = '.$where_country_id;                        //$country_id = $this->request->params['named']['country_id'];        }                        if (count($this->params['pass'])) {           foreach ($this->params['pass'] as $key => $value) {                array_push($search_condition, array('TravelHotelLookup.' . $key => $value));                           }                        } elseif (count($this->params['named'])) {            foreach ($this->params['named'] as $key => $value) {                array_push($search_condition, array('TravelHotelLookup.' . $key => $value));                           }        }                if ($this->request->is('post')) {            $sqn_generate_btt = false;            if (!empty($this->data['MassOperation']['active'])) {                $active = $this->data['MassOperation']['active'];                $active_array = array('TravelHotelLookup.active' => "'".$active."'");            }            $continent_id = $this->request->data['MassOperation']['continent_id'];            $province_id = $this->request->data['MassOperation']['province_id'];                        $continent_name = $this->request->data['MassOperation']['continent_name'];            $continent_code = $this->request->data['MassOperation']['continent_code'];            $province_name = $this->request->data['MassOperation']['province_name'];            $country_code = $this->request->data['MassOperation']['country_code'];            $country_id = $this->request->data['MassOperation']['country_id'];            $country_name = $this->request->data['MassOperation']['country_name'];            $city_id = $this->request->data['MassOperation']['city_id'];            $city_name = $this->request->data['MassOperation']['city_name'];            $city_code = $this->request->data['MassOperation']['city_code'];            $sequence_no = $this->request->data['MassOperation']['sequence_no'];                        if(isset($this->data['MassOperation']['check'])){                foreach ($this->data['MassOperation']['check'] as $val) {                                        $selected[] = $val;                }                array_push($update_con, array('NOT' => array('TravelHotelLookup.id' => $selected)));                $aa = implode(',', $selected);                                $conditions .= ' AND TravelHotelLookup.id NOT IN ('.$aa.')';            }            //pr($check_condition);            array_push($update_con, $search_condition);            /*             $update_con = $search_condition + $check_condition;            if(!empty($check_condition))                $update_con = $search_condition + $check_condition;            else                $update_con = $search_condition;            */            //pr($update_con);            //die;           if (isset($this->request->data['update']) == 'Update') {                $count = $this->TravelHotelLookup->find('count', array('conditions' => array($conditions)));                    //$this->TravelHotelLookup->updateAll(array('TravelHotelLookup.continent_id' => $continent_id, 'TravelHotelLookup.continent_name' => "'" . $continent_name . "'", 'TravelHotelLookup.continent_code' => "'" . $continent_code . "'", 'TravelHotelLookup.country_id' => "'" . $country_id . "'", 'TravelHotelLookup.country_code' => "'" . $country_code . "'", 'TravelHotelLookup.country_name' => "'" . $country_name . "'", 'TravelHotelLookup.city_id' => "'" . $city_id . "'", 'TravelHotelLookup.city_code' => "'" . $city_code . "'", 'TravelHotelLookup.city_name' => "'" . $city_name . "'", 'TravelHotelLookup.province_id' => $province_id, 'TravelHotelLookup.province_name' => "'" . $province_name . "'") + $active_array , $update_con);                    //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);                           //debug($log);                    //die;                    /*                     * Start XML call                     */                if ($active == 'TRUE')                        $Active = '1';                    else                        $Active = '0';                     $content_xml_str = '<soap:Body>                                            <ProcessXML xmlns="http://www.travel.domain/">                                                <RequestInfo>                                                    <ResourceDataRequest>                                                        <RequestAuditInfo>                                                            <RequestType>PXML_WData_LookupUpdateList</RequestType>                                                            <RequestTime>' . $CreatedDate . '</RequestTime>                                                            <RequestResource>Silkrouters</RequestResource>                                                        </RequestAuditInfo>                                                        <RequestParameters>                                                            <ResourceData>                                                                <ResourceDetailsData srno="'.$sequence_no.'" lookuptype="Hotel">                                                                    <SelectedCountIn>'.$count.'</SelectedCountIn>                                                                    <ContinentId>' . $continent_id . '</ContinentId>                                                                    <ContinentCode>' . $continent_code . '</ContinentCode>                                                                    <ContinentName>' . $continent_name . '</ContinentName>                                                                    <CountryId>' . $country_id . '</CountryId>                                                                    <CountryCode>' . $country_code . '</CountryCode>                                                                    <CountryName>' . $country_name . '</CountryName>                                                                    <ProvinceId>' . $province_id . '</ProvinceId>                                                                    <ProvinceName>' . $province_name . '</ProvinceName>                                                                    <CityId>' . $city_id . '</CityId>                                                                    <CityCode>' . $city_code . '</CityCode>                                                                    <CityName>' . $city_name . '</CityName>                                                                    <SuburbId></SuburbId>                                                                    <SuburbName></SuburbName>                                                                    <AreaId></AreaId>                                                                    <AreaName></AreaName>                                                                                                   <IsAdminApproved></IsAdminApproved>                                                                    <Active>'.$Active.'</Active>                                                                    <Exclude></Exclude>                                                                    <PFActive></PFActive>                                                                    <SSActive></SSActive>                                                                    <Status></Status>                                                                    <WhereContinentId>'.$where_continent_id.'</WhereContinentId>                                                                                                    <WhereCountryId>'.$where_country_id.'</WhereCountryId>                                                                    <WhereProvinceId>'.$where_province_id.'</WhereProvinceId>                                                                    <WhereCityId>'.$where_city_id.'</WhereCityId>                                                                    <WhereSuburbId></WhereSuburbId>                                                                    <WhereAreaId></WhereAreaId>                                                                    <WhereExcludeIdList>'.$aa.'</WhereExcludeIdList>                                                                </ResourceDetailsData>                                                             </ResourceData>                                                                                                                                       </RequestParameters>                                                    </ResourceDataRequest>                                                </RequestInfo>                                            </ProcessXML>                                        </soap:Body>';                    $log_call_screen = 'Hotel - Update (Mass Operation)';                    $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');                    $client = new SoapClient(null, array(                        'location' => $location_URL,                        'uri' => '',                        'trace' => 1,                    ));                    try {                        $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);                        $xml_arr = Xml::toArray(Xml::build($order_return));                                               //$xml_arr = $this->xml2array($order_return);                        echo htmlentities($xml_string);                         //pr($xml_arr);                          die;                        if ($xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['ResponseInfo']['ResponseId'] == '201') {                            $log_call_status_code = $xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['ResponseInfo']['ResponseId'];                            $log_call_status_message = $xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['UpdateInfo']['Status'];                            $inbound = $xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['UpdateInfo']['SelectedCountIn'];                            $outbound = $xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['UpdateInfo']['SelectedCountOut'];                            $xml_msg = "Foreign record has been successfully updated [Code:$log_call_status_code][Inbound:$inbound][Outbound:$outbound]";                            $local_udate = true;                            $this->Session->setFlash($xml_msg, 'success');                        } else {                            $log_call_status_message = $xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['UpdateInfo']['TraceInfo0']['TraceInfo']['ErrorMessage'];                            $log_call_status_code = $xml_arr['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['ResourceData_LookupUpdateList']['ResponseAuditInfo']['ResponseInfo']['ResponseId'];                            $xml_msg = "There was a problem with foreign record updation [Code:$log_call_status_code]";                            //$this->TravelArea->updateAll(array('TravelArea.wtb_status' => "'2'"), array('TravelArea.id' => $AreaId));                            $xml_error = 'TRUE';                            $this->Session->setFlash($xml_msg, 'failure');                        }                    } catch (SoapFault $exception) {                        var_dump(get_class($exception));                        var_dump($exception);                    }                    $this->request->data['LogCall']['log_call_nature'] = 'Production';                    $this->request->data['LogCall']['log_call_type'] = 'Outbound';                    $this->request->data['LogCall']['log_call_parms'] = trim($xml_string);                    $this->request->data['LogCall']['log_call_status_code'] = $log_call_status_code;                    $this->request->data['LogCall']['log_call_status_message'] = $log_call_status_message;                    $this->request->data['LogCall']['log_call_screen'] = $log_call_screen;                    $this->request->data['LogCall']['log_call_counterparty'] = 'WTBNETWORKS';                    $this->request->data['LogCall']['log_call_by'] = $user_id;                    $this->LogCall->save($this->request->data['LogCall']);                   // $message = 'Local record has been successfully updated.<br />' . $xml_msg;                   // $this->Session->setFlash($message, 'success');                    $a = date('m/d/Y H:i:s', strtotime('-1 hour'));                    $date = new DateTime($a, new DateTimeZone('Asia/Calcutta'));                    if ($xml_error == 'TRUE') {                        $Email = new CakeEmail();                        $Email->viewVars(array(                            'request_xml' => trim($xml_string),                            'respon_message' => $log_call_status_message,                            'respon_code' => $log_call_status_code,                        ));                        $to = 'biswajit@wtbglobal.com';                        $cc = 'infra@sumanus.com';                        $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();                    }                                                                                                                                                                /*                     * End XML call                     */                                                }            elseif (isset($this->request->data['local_update']) == 'local_update') {                if($this->TravelHotelLookup->updateAll(array('TravelHotelLookup.continent_id' => $continent_id, 'TravelHotelLookup.continent_name' => "'" . $continent_name . "'", 'TravelHotelLookup.continent_code' => "'" . $continent_code . "'", 'TravelHotelLookup.country_id' => "'" . $country_id . "'", 'TravelHotelLookup.country_code' => "'" . $country_code . "'", 'TravelHotelLookup.country_name' => "'" . $country_name . "'", 'TravelHotelLookup.city_id' => "'" . $city_id . "'", 'TravelHotelLookup.city_code' => "'" . $city_code . "'", 'TravelHotelLookup.city_name' => "'" . $city_name . "'", 'TravelHotelLookup.sequence_no' => $sequence_no, 'TravelHotelLookup.province_id' => $province_id, 'TravelHotelLookup.province_name' => "'" . $province_name . "'") + $active_array , $update_con))                      $this->Session->setFlash('Local record has been successfully updated.', 'success');                  else                    $this->Session->setFlash('Unable to updated local records.', 'failure');              }            elseif (isset($this->request->data['generate']) == 'Generate') {                                 $update = true;                if (!empty($this->data['MassOperation']['active']))                     $update_query = 'UPDATE TABLE Hotel SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',continent_code = ' . $continent_code . ',country_id = ' . $country_id . ',country_code = ' . $country_code . ',country_name = ' . $country_name . ',city_id = ' . $city_id . ',city_code = ' . $city_code . ',city_name = ' . $city_name . ',province_id = ' . $province_id . ',province_name = ' . $province_name . ',active = ' . $active .' WHERE '.$conditions;                else                    $update_query = 'UPDATE TABLE Hotel SET continent_id = ' . $continent_id . ',continent_name = ' . $continent_name . ',continent_code = ' . $continent_code . ',country_id = ' . $country_id . ',country_code = ' . $country_code . ',country_name = ' . $country_name . ',city_id = ' . $city_id . ',city_code = ' . $city_code . ',city_name = ' . $city_name . ',province_id = ' . $province_id . ',province_name = ' . $province_name . ' WHERE '.$conditions;                $count = $this->TravelHotelLookup->find('count', array('conditions' => array($conditions)));                    $update_query .="<br><br>Number of Records that will be impacted by this Operation = ".$count;//pr($count);                //$disabled = 'disabled';                $this->Session->setFlash($update_query, 'success');            }            $TravelCities = $this->TravelCity->find('all', array(                'conditions' => array(                    'TravelCity.province_id' => $province_id,                    'TravelCity.city_status' => '1',                    'TravelCity.wtb_status' => '1',                    'TravelCity.active' => 'TRUE'                ),                'fields' => array('TravelCity.id', 'TravelCity.city_code', 'TravelCity.city_name'),                'order' => 'TravelCity.city_code ASC'            ));                        $TravelCities = Set::combine($TravelCities, '{n}.TravelCity.id', array('%s - %s', '{n}.TravelCity.city_code', '{n}.TravelCity.city_name'));            //$TravelCities = $this->TravelCity->find('list', array('fields' => array('id', 'city_name'), 'conditions' => array('country_id' => $country_id), 'order' => 'city_name ASC'));            $TravelLookupContinents = $this->TravelLookupContinent->find('all', array('fields' => array('TravelLookupContinent.id', 'TravelLookupContinent.continent_name', 'TravelLookupContinent.continent_code'), 'order' => 'TravelLookupContinent.continent_name ASC'));            $TravelLookupContinents = Set::combine($TravelLookupContinents, '{n}.TravelLookupContinent.id', array('%s - %s', '{n}.TravelLookupContinent.continent_code', '{n}.TravelLookupContinent.continent_name'));            $TravelCountries = $this->TravelCountry->find('all', array(                'conditions' => array(                    'TravelCountry.continent_id' => $continent_id,                    'TravelCountry.country_status' => '1',                    'TravelCountry.wtb_status' => '1',                    'TravelCountry.active' => 'TRUE'                ),                'fields' => array('TravelCountry.id', 'TravelCountry.country_name', 'TravelCountry.country_code'),                'order' => 'TravelCountry.country_name ASC'            ));            $TravelCountries = Set::combine($TravelCountries, '{n}.TravelCountry.id', array('%s - %s', '{n}.TravelCountry.country_code', '{n}.TravelCountry.country_name'));            $Provinces = $this->Province->find('list', array('fields' => array('id', 'name'), 'conditions' => array('country_id' => $country_id)));            //pr($Provinces);        }                        $this->paginate['order'] = array('TravelHotelLookup.city_code' => 'asc');        $this->set('TravelHotelLookups', $this->paginate("TravelHotelLookup", $search_condition));                                $TravelLookupContinents = $this->TravelLookupContinent->find('all', array('fields' => array('TravelLookupContinent.id', 'TravelLookupContinent.continent_name', 'TravelLookupContinent.continent_code'), 'order' => 'TravelLookupContinent.continent_name ASC'));        $TravelLookupContinents = Set::combine($TravelLookupContinents, '{n}.TravelLookupContinent.id', array('%s - %s', '{n}.TravelLookupContinent.continent_code', '{n}.TravelLookupContinent.continent_name'));              $this->set(compact('TravelLookupContinents','TravelCountries','Provinces','TravelCities','selected','update','local_udate','sqn_generate_btt'));                //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);               //debug($log);        //die;    }}