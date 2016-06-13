<?php

/**
 * Builder controller.
 *
 * This file will render views from views/builders/
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
App::uses('AppController', 'Controller');

App::uses('Xml', 'Utility');

/**
 * Builder controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AdminController extends AppController {

    var $uses = array('SupplierCountry', 'TravelCountry', 'SupplierCity', 'SupplierHotel', 'TravelFetchTable', 'SupplierHotel', 'TravelSupplier', 'Common', 'SupplierHotel',
        'TravelActionItem', 'TravelRemark', 'TravelCountrySupplier', 'Mappinge', 'TravelCity', 'TravelCitySupplier','TravelHotelLookup','TravelHotelRoomSupplier','SupportTicket');

    function index() {
        
    }

    function reports() {
        
    }

    function data() {
        
    }

    function administration() {
        
    }

    public function fetch_hotels() {

        $search_condition = array();
        $this->paginate['order'] = array('TravelFetchTable.id' => 'asc');
        $this->set('TravelFetchTables', $this->paginate("TravelFetchTable", $search_condition));
        
        $SupplierCityCount = $this->SupplierCity->find('count');
        $SupplierCountryCount = $this->SupplierCountry->find('count');
        $SupplierHotelCount = $this->SupplierHotel->find('count');
        $this->set(compact('SupplierCityCount','SupplierCountryCount','SupplierHotelCount'));
    }

    public function add_hotel() {

        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');

        if ($this->request->is('post') || $this->request->is('put')) {

            $country_id = $this->data['SupplierHotel']['country_id'];
            $city_id = $this->data['SupplierHotel']['city_id'];
            $supplier_id = $this->data['SupplierHotel']['supplier_id'];

            $supplier_code = $this->Common->getSupplierCode($supplier_id);
            $supplier_name = $this->Common->getSupplierName($supplier_id);
            $country_code = $this->Common->getSupplierCountryCode($country_id);
            $country_name = $this->Common->getSupplierCountryName($country_id);
            $city_code = $this->Common->getSupplierCityCode($city_id);
            $city_name = $this->Common->getSupplierCityName($city_id);
            $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');

            $content_xml_str = '<soap:Body>
                                            <ProcessXML xmlns="http://www.travel.domain/">
                                                <RequestInfo>
                                                    <GetDirectSupplierStaticData>
                                                        <RequestAuditInfo>
                                                            <RequestType>PXML_DirectSupplier_GetStaticData_Prod</RequestType>
                                                            <RequestTime>' . $CreatedDate . '</RequestTime>
                                                            <RequestResource>Silkrouters</RequestResource>
                                                        </RequestAuditInfo>
                                                        <RequestParameters>
                                                            <SupplierDataType>Hotel</SupplierDataType>
                                                            <SupplierId>' . $supplier_id . '</SupplierId>
                                                            <CountryCode>' . $country_code . '</CountryCode>
                                                            <CityCode>' . $city_code . '</CityCode>
                                                            <HotelCode></HotelCode>
                                                        </RequestParameters>
                                                    </GetDirectSupplierStaticData>
                                                </RequestInfo>
                                            </ProcessXML>
                                        </soap:Body>';


            $log_call_screen = 'Supplier - Add';

            $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);
                //$xmlparser = xml_parser_create();
                //xml_parse_into_struct($xmlparser,$order_return,$values);
                //xml_parser_free($xmlparser);;
                //$xml_arr = xml_to_object($order_return);
                //$xml_arr = $this->Common->xml2array($order_return);
                //echo htmlentities($xml_string);
                // echo '<br>-------------------';
                // echo htmlentities($order_return);
                //pr($xml_arr);
                //$xmlObject = new Xml($xmlString);
                //$xmlObject = new Xml();
//$xmlArray = Xml::toArray($order_return);
                $xmlArray = Xml::toArray(Xml::build($order_return));
                //PR($xmlArray);
                // die;
                $ValArr = $xmlArray['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['SupplierData_Hotel']['ResponseAuditInfo']['root']['LocalHotelList']['item'];
                $total_volume = count($ValArr);


                if ($total_volume) {


                    $this->request->data['TravelFetchTable']['supplier_id'] = $supplier_id;
                    $this->request->data['TravelFetchTable']['user_id'] = $user_id;
                    $this->request->data['TravelFetchTable']['country_id'] = $country_id;
                    $this->request->data['TravelFetchTable']['city_id'] = $city_id;
                    $this->request->data['TravelFetchTable']['total_volume'] = $total_volume;
                    $this->request->data['TravelFetchTable']['status'] = 'Success';
                    $this->request->data['TravelFetchTable']['type_id'] = '1';

                    $this->TravelFetchTable->save($this->data['TravelFetchTable']);
                    $fetch_id = $this->TravelFetchTable->getLastInsertId();

                    if ($fetch_id) {
                        foreach ($ValArr as $value) {
                            //echo  $value['Code']['@'];
                            $save[] = array('SupplierHotel' => array(
                                    'city_id' => $city_id,
                                    'fetch_id' => $fetch_id,
                                    'city_code' => $city_code,
                                    'city_name' => $city_name,
                                    'country_id' => $country_id,
                                    'country_name' => $country_name,
                                    'country_code' => $country_code,
                                    'hotel_code' => $value['Id']['@'],
                                    'hotel_name' => $value['Name']['@'],
                                    'supplier_id' => $supplier_id,
                                    'supplier_code' => $supplier_code,
                                    'status' => '1',
                                    'supplier_name' => $supplier_name
                            ));
                        }



                        if ($this->SupplierHotel->validates() == true) {
                            $this->SupplierHotel->create();
                            if ($this->SupplierHotel->saveMany($save)) {

                                $this->Session->setFlash('Data inserted successfully', 'success');
                                $this->redirect(array('action' => 'fetch_hotels'));
                            }
                        }
                    }
                }
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }
        }
        $TravelSuppliers = $this->TravelSupplier->find('list', array('fields' => 'id,supplier_name', 'order' => 'supplier_name ASC'));
        $SupplierCountries = $this->SupplierCountry->find('list', array('fields' => 'id,name', 'order' => 'name ASC'));
        $this->set(compact('SupplierCountries', 'TravelSuppliers'));
    }

    public function add_country() {

        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');


        if ($this->request->is('post') || $this->request->is('put')) {

            $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');
            $supplier_id = $this->data['SupplierCountry']['supplier_id'];
            $supplier_code = $this->Common->getSupplierCode($supplier_id);
            $supplier_name = $this->Common->getSupplierName($supplier_id);

            $content_xml_str = '<soap:Body>
                                <ProcessXML xmlns="http://www.travel.domain/">
                                    <RequestInfo>
                                        <GetDirectSupplierStaticData>
                                            <RequestAuditInfo>
                                                <RequestType>PXML_DirectSupplier_GetStaticData_Prod</RequestType>
                                                <RequestTime>' . $CreatedDate . '</RequestTime>
                                                <RequestResource>Silkrouters</RequestResource>
                                            </RequestAuditInfo>
                                            <RequestParameters>
                                                <SupplierDataType>Country</SupplierDataType>
                                                <SupplierId>' . $supplier_id . '</SupplierId>
                                                <CountryCode></CountryCode>
                                                <CityCode></CityCode>
                                                <HotelCode></HotelCode>
                                            </RequestParameters>
                                        </GetDirectSupplierStaticData>
                                    </RequestInfo>
                                </ProcessXML>
                            </soap:Body>';


            $log_call_screen = 'Supplier Country - Add';

            $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);

                $xmlArray = Xml::toArray(Xml::build($order_return));

                $ValArr = $xmlArray['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['SupplierData_Country']['ResponseAuditInfo']['root']['a:item'];
                $total_volume = count($ValArr);
                //if($total_volume){

                $this->request->data['TravelFetchTable']['supplier_id'] = $supplier_id;
                $this->request->data['TravelFetchTable']['user_id'] = $user_id;
                $this->request->data['TravelFetchTable']['country_id'] = '';
                $this->request->data['TravelFetchTable']['city_id'] = '';
                $this->request->data['TravelFetchTable']['total_volume'] = $total_volume;
                $this->request->data['TravelFetchTable']['status'] = 'Success';
                $this->request->data['TravelFetchTable']['type_id'] = '2';

                $this->TravelFetchTable->save($this->data['TravelFetchTable']);
                $fetch_id = $this->TravelFetchTable->getLastInsertId();
                if ($fetch_id) {
                    foreach ($ValArr as $value) {
                        $save[] = array('SupplierCountry' => array(
                                'item' => $value['@item'],
                                'code' => $value['Code']['@'],
                                'name' => $value['Name']['@'],
                                'supplier_id' => $supplier_id,
                                'supplier_code' => $supplier_code,
                                'supplier_name' => $supplier_name,
                                'fetch_id' => $fetch_id,
                        ));
                    }
                    $this->SupplierCountry->create();
                    if ($this->SupplierCountry->saveMany($save)) {
                        $this->Session->setFlash('Data inserted successfully', 'success');
                        $this->redirect(array('action' => 'fetch_hotels'));
                    }
                }



                //}
                //}
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }
        }

        $TravelSuppliers = $this->TravelSupplier->find('list', array('fields' => 'id,supplier_name', 'order' => 'supplier_name ASC'));
        $this->set(compact('TravelSuppliers'));
    }

    public function supplier_country() {

        $search_condition = array();
        $this->paginate['order'] = array('SupplierCountry.id' => 'asc');
        $this->set('SupplierCountries', $this->paginate("SupplierCountry", $search_condition));      
        
        $SupplierCityCount = $this->SupplierCity->find('count');
        $SupplierCountryCount = $this->SupplierCountry->find('count');
        $SupplierHotelCount = $this->SupplierHotel->find('count');
        $this->set(compact('SupplierCityCount','SupplierCountryCount','SupplierHotelCount'));
    }

    public function add_city() {
        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');


        if ($this->request->is('post') || $this->request->is('put')) {

            $supplier_id = $this->data['SupplierCity']['supplier_id'];
            $country_id = $this->data['SupplierCity']['country_id'];
            $supplier_code = $this->Common->getSupplierCode($supplier_id);
            $supplier_name = $this->Common->getSupplierName($supplier_id);
            $country_code = $this->Common->getSupplierCountryCode($country_id);
            $country_name = $this->Common->getSupplierCountryName($country_id);
            $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');

            $content_xml_str = '<soap:Body>
                                    <ProcessXML xmlns="http://www.travel.domain/">
                                        <RequestInfo>
                                            <GetDirectSupplierStaticData>
                                                <RequestAuditInfo>
                                                    <RequestType>PXML_DirectSupplier_GetStaticData_Prod</RequestType>
                                                    <RequestTime>' . $CreatedDate . '</RequestTime>
                                                    <RequestResource>Silkrouters</RequestResource>
                                                </RequestAuditInfo>
                                                <RequestParameters>
                                                    <SupplierDataType>City</SupplierDataType>
                                                    <SupplierId>' . $supplier_id . '</SupplierId>
                                                    <CountryCode>' . $country_code . '</CountryCode>
                                                    <CityCode></CityCode>
                                                    <HotelCode></HotelCode>
                                                </RequestParameters>
                                            </GetDirectSupplierStaticData>
                                        </RequestInfo>
                                    </ProcessXML>
                                </soap:Body>';


            $log_call_screen = 'Supplier - Add';

            $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);

                $xmlArray = Xml::toArray(Xml::build($order_return));
                $ValArr = $xmlArray['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['SupplierData_City']['ResponseAuditInfo']['root']['CityInfo']['item'];
                $total_volume = count($ValArr);

                $this->request->data['TravelFetchTable']['supplier_id'] = $supplier_id;
                $this->request->data['TravelFetchTable']['user_id'] = $user_id;
                $this->request->data['TravelFetchTable']['country_id'] = $country_id;
                $this->request->data['TravelFetchTable']['city_id'] = '';
                $this->request->data['TravelFetchTable']['total_volume'] = $total_volume;
                $this->request->data['TravelFetchTable']['status'] = 'Success';
                $this->request->data['TravelFetchTable']['type_id'] = '3';
                $this->TravelFetchTable->save($this->data['TravelFetchTable']);
                $fetch_id = $this->TravelFetchTable->getLastInsertId();
                if ($fetch_id) {
                    foreach ($ValArr as $value) {

                        $save[] = array('SupplierCity' => array(
                                'fetch_id' => $fetch_id,
                                'country_code' => $country_code,
                                'country_id' => $country_id,
                                'country_name' => $country_name,
                                'code' => $value['CityCode']['@'],
                                'name' => $value['Name']['@'],
                                'supplier_id' => $supplier_id,
                                'supplier_code' => $supplier_code,
                                'supplier_name' => $supplier_name
                        ));
                    }

                    $this->SupplierCity->create();
                    if ($this->SupplierCity->saveMany($save)) {
                        $this->Session->setFlash('Data inserted successfully', 'success');
                        $this->redirect(array('action' => 'fetch_hotels'));
                    }
                }
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }
        }

        $TravelSuppliers = $this->TravelSupplier->find('list', array('fields' => 'id,supplier_name', 'order' => 'supplier_name ASC'));
        $SupplierCountries = $this->SupplierCountry->find('list', array('fields' => 'id,name', 'order' => 'name ASC'));
        $this->set(compact('SupplierCountries', 'TravelSuppliers'));
    }

    public function supplier_city() {

        $search_condition = array();
        $this->paginate['order'] = array('SupplierCity.id' => 'asc');
        $this->set('SupplierCities', $this->paginate("SupplierCity", $search_condition));
        
        $SupplierCityCount = $this->SupplierCity->find('count');
        $SupplierCountryCount = $this->SupplierCountry->find('count');
        $SupplierHotelCount = $this->SupplierHotel->find('count');
        $this->set(compact('SupplierCityCount','SupplierCountryCount','SupplierHotelCount'));
    }

    public function supplier_hotel_mappings() {

        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');

        if ($this->request->is('post') || $this->request->is('put')) {


            $content_xml_str = '<soap:Body>
                                            <ProcessXML xmlns="http://www.travel.domain/">
                                                <RequestInfo>
                                                    <GetDirectSupplierStaticData>
                                                        <RequestAuditInfo>
                                                            <RequestType>PXML_DirectSupplier_GetStaticData_Prod</RequestType>
                                                            <RequestTime>2016-07-20T15:59:45</RequestTime>
                                                            <RequestResource>CompanyCode</RequestResource>
                                                        </RequestAuditInfo>
                                                        <RequestParameters>
                                                            <SupplierDataType>Hotel</SupplierDataType>
                                                            <SupplierId>1</SupplierId>
                                                            <CountryCode>64</CountryCode>
                                                            <CityCode>108</CityCode>
                                                            <HotelCode></HotelCode>
                                                        </RequestParameters>
                                                    </GetDirectSupplierStaticData>
                                                </RequestInfo>
                                            </ProcessXML>
                                        </soap:Body>';


            $log_call_screen = 'Supplier - Add';

            $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);
                //$xmlparser = xml_parser_create();
                //xml_parse_into_struct($xmlparser,$order_return,$values);
                //xml_parser_free($xmlparser);;
                //$xml_arr = xml_to_object($order_return);
                //$xml_arr = $this->Common->xml2array($order_return);
                // echo htmlentities($xml_string);
                // echo '<br>-------------------';
                // echo htmlentities($order_return);
                //pr($xml_arr);
                //$xmlObject = new Xml($xmlString);
                //$xmlObject = new Xml();
//$xmlArray = Xml::toArray($order_return);
                $xmlArray = Xml::toArray(Xml::build($order_return));

                $ValArr = $xmlArray['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['SupplierData_Hotel']['ResponseAuditInfo']['root']['LocalHotelList']['item'];
                PR($ValArr);
                die;
                foreach ($ValArr as $value) {
                    //echo  $value['Code']['@'];
                    $save[] = array('SupplierHotel' => array(
                            'city_code' => '108',
                            'city_name' => 'BANGKOK',
                            'country_name' => 'THAILAND',
                            'country_code' => '64',
                            'id' => $value['Id']['@'],
                            'hotel_name' => $value['Name']['@'],
                    ));
                }
                $this->SupplierHotel->create();
                $this->SupplierHotel->saveMany($save);
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }
        }
    }

    public function supplier_hotels() {

        $search_condition = array();
        $this->paginate['order'] = array('SupplierHotel.id' => 'asc');
        $this->set('SupplierHotels', $this->paginate("SupplierHotel", $search_condition));
        
        $SupplierCityCount = $this->SupplierCity->find('count');
        $SupplierCountryCount = $this->SupplierCountry->find('count');
        $SupplierHotelCount = $this->SupplierHotel->find('count');
        $this->set(compact('SupplierCityCount','SupplierCountryCount','SupplierHotelCount'));
    }

    public function country_mapping($id = null) {

        $condition = '';

        if (!$id) {
            throw new NotFoundException(__('Invalid Country'));
        }

        $SupplierCountries = $this->SupplierCountry->findById($id);



        if (count($SupplierCountries)) {
            $country_name = $SupplierCountries['SupplierCountry']['name'];


            for ($indexOfFirstLetter = 0; $indexOfFirstLetter <= strlen($country_name); $indexOfFirstLetter++) {
                for ($indexOfLastLetter = $indexOfFirstLetter + 1; $indexOfLastLetter <= strlen($country_name); $indexOfLastLetter++) {
                    $arr[] = substr($country_name, $indexOfFirstLetter, 3);
                    $condition .= "(country_name LIKE '%" . $arr[$indexOfFirstLetter] . "%')";
                    if ($indexOfFirstLetter < strlen($country_name) - 1)
                        $condition .= 'OR';
                    $indexOfFirstLetter++;
                }
            }

            $TravelCountries = $this->TravelCountry->find
                    (
                    'all', array
                (
                'conditions' => array
                    (
                    $condition
                ),
                'order' => 'TravelCountry.country_name ASC',
                    )
            );

            $this->set(compact('TravelCountries'));
        }

        //$log = $this->TravelCountry->getDataSource()->getLog(false, false);       
        //debug($log);
        //die;

        $this->request->data = $SupplierCountries;
    }

    public function city_mapping($id = null) {

        $condition = array();
        $search_condition = array();

        if (!$id) {
            throw new NotFoundException(__('Invalid City'));
        }

        $SupplierCities = $this->SupplierCity->findById($id);



        if (count($SupplierCities)) {

            $city_name = $SupplierCities['SupplierCity']['name'];


            for ($indexOfFirstLetter = 0; $indexOfFirstLetter <= strlen($city_name); $indexOfFirstLetter++) {
                for ($indexOfLastLetter = $indexOfFirstLetter + 1; $indexOfLastLetter <= strlen($city_name); $indexOfLastLetter++) {
                    $new_arr[] = substr($city_name, $indexOfFirstLetter, 4);
                    //pr($new_arr);
                    //array_push($search_condition, ARRAY('OR'));
                    $condition[] = array("TravelCity.city_name LIKE '%$new_arr[$indexOfFirstLetter]%'");
                    //array_push($search_condition,  array("TravelCity.city_name LIKE '%$new_arr[$indexOfFirstLetter]%'"));
                    /*
                      $condition .= "(TravelCity.city_name LIKE '%" . $new_arr[$indexOfFirstLetter] . "%')";
                      if ($indexOfFirstLetter < strlen($city_name) - 1)
                      $condition .= 'OR';
                      $search_condition[] = $condition;
                     * 
                     */
                    $indexOfFirstLetter++;
                }
            }
            //pr($condition);
            array_push($search_condition, array('OR' => $condition));
            //pr($search_condition);
            // die;
            /*
              $TravelCities = $this->TravelCity->find
              (
              'all', array
              (

              'conditions' => array
              (
              $condition
              //'TravelCity.city_name like' => '%'.$city_name.'%'
              ),
              'order' => 'TravelCity.id ASC',
              )
              );
             * 
             */
            $this->paginate['order'] = array('TravelCity.city_name' => 'asc');
            $this->set('TravelCities', $this->paginate("TravelCity", $search_condition));
            // $this->set(compact('TravelCities'));
        }

        // pr($condition);
        //$log = $this->TravelCity->getDataSource()->getLog(false, false);       
        //debug($log);
        //die;

        $this->request->data = $SupplierCities;
    }

    public function add_country_mapping() {

        //$mapped = $this->data['mapped'];
        $dummy_status = $this->Auth->user('dummy_status');
        $role_id = $this->Session->read("role_id");
        $user_id = $this->Auth->user('id');

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->data['mapped'])) {
                $supplier_country_id = $this->data['Common']['supplier_country_id'];
                $country_id = $this->data['Common']['country_id'];
                //die;
                $SupplierCountries = $this->SupplierCountry->findById($supplier_country_id);
                $TravelCountries = $this->TravelCountry->findById($country_id);

                $this->set(compact('SupplierCountries', 'TravelCountries'));
            }



            if (isset($this->data['add'])) {
                $supplier_country_id = $this->data['SupplierCountry']['supplier_country_id'];
                $country_id = $this->data['SupplierCountry']['country_id'];
                //die;
                $SupplierCountries = $this->SupplierCountry->findById($supplier_country_id);
                $TravelCountries = $this->TravelCountry->findById($country_id);

                $next_action_by = '166';  //overseer 136 44 is sarika 152 - ojas
                $flag = 0;
                $search_condition = array();
                $condition = '';
                $success = '';

                $this->request->data['Mappinge']['supplier_code'] = $SupplierCountries['SupplierCountry']['supplier_code'];
                $this->request->data['Mappinge']['mapping_type'] = '1'; // supplier country
                $this->request->data['Mappinge']['country_wtb_code'] = $TravelCountries['TravelCountry']['country_code'];
                $this->request->data['Mappinge']['country_supplier_code'] = $SupplierCountries['SupplierCountry']['code'];

                $this->request->data['TravelCountrySupplier']['country_suppliner_status'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $this->request->data['TravelCountrySupplier']['excluded'] = 'FALSE'; // 2 for No of lookup_value_statuses
                $this->request->data['TravelCountrySupplier']['wtb_status'] = '1'; // 1 for True
                $this->request->data['TravelCountrySupplier']['active'] = 'FALSE'; // 2 for No of lookup_value_statuses
                $this->request->data['TravelCountrySupplier']['supplier_code'] = $SupplierCountries['SupplierCountry']['supplier_code'];
                $this->request->data['TravelCountrySupplier']['supplier_country_code'] = $SupplierCountries['SupplierCountry']['code'];
                $this->request->data['TravelCountrySupplier']['pf_country_code'] = $TravelCountries['TravelCountry']['country_code'];
                $this->request->data['TravelCountrySupplier']['country_supplier_id'] = $SupplierCountries['SupplierCountry']['id'];
                //$country_name_arr = $this->TravelCountry->findByCountryCode($this->data['Mapping']['pf_country_code'], array('fields' => 'country_name', 'id', 'continent_id', 'continent_name'));

                $this->request->data['TravelCountrySupplier']['country_name'] = $TravelCountries['TravelCountry']['country_name'];
                $this->request->data['TravelCountrySupplier']['country_id'] = $TravelCountries['TravelCountry']['id'];
                $this->request->data['TravelCountrySupplier']['country_continent_id'] = $TravelCountries['TravelCountry']['continent_id'];
                $this->request->data['TravelCountrySupplier']['country_continent_name'] = $TravelCountries['TravelCountry']['continent_name'];
                $this->request->data['TravelCountrySupplier']['country_mapping_name'] = strtoupper('[SUPP/COUNTRY] | ' . $SupplierCountries['SupplierCountry']['supplier_code'] . ' | ' . $TravelCountries['TravelCountry']['country_code'] . ' - ' . $TravelCountries['TravelCountry']['country_name']);
                $this->request->data['TravelCountrySupplier']['created_by'] = $user_id;

                $tr_remarks['TravelRemark']['remarks_level'] = '2'; // for Mapping Country from travel_action_remark_levels
                $tr_remarks['TravelRemark']['remarks'] = 'New Supplier Country Record Created';


                $tr_action_item['TravelActionItem']['level_id'] = '2'; // for agent travel_action_remark_levels
                $tr_action_item['TravelActionItem']['description'] = 'New Supplier Country Record Created - Submission For Approval';


                $this->TravelCountrySupplier->save($this->request->data['TravelCountrySupplier']);
                $country_supplier_id = $this->TravelCountrySupplier->getLastInsertId();
                if ($country_supplier_id) {

                    $this->request->data['Mappinge']['country_supplier_id'] = $country_supplier_id;
                    $tr_remarks['TravelRemark']['country_supplier_id'] = $country_supplier_id;
                    $tr_action_item['TravelActionItem']['country_supplier_id'] = $country_supplier_id;
                    $flag = 1;
                }

                $this->request->data['Mappinge']['created_by'] = $user_id;
                $this->request->data['Mappinge']['status'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $this->request->data['Mappinge']['exclude'] = '2'; // 2 for No of lookup_value_statuses
                $this->request->data['Mappinge']['dummy_status'] = $dummy_status;
                $this->Mappinge->save($this->request->data['Mappinge']);

                $tr_remarks['TravelRemark']['created_by'] = $user_id;
                $tr_remarks['TravelRemark']['remarks_time'] = date('g:i A');

                $tr_remarks['TravelRemark']['dummy_status'] = $dummy_status;
                $this->TravelRemark->save($tr_remarks);

                /*
                 * ********************** Action *********************
                 */

                $tr_action_item['TravelActionItem']['type_id'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $tr_action_item['TravelActionItem']['action_item_active'] = 'Yes';
                $tr_action_item['TravelActionItem']['action_item_source'] = $role_id;
                $tr_action_item['TravelActionItem']['created_by_id'] = $user_id;
                $tr_action_item['TravelActionItem']['created_by'] = $user_id;
                $tr_action_item['TravelActionItem']['dummy_status'] = $dummy_status;
                $tr_action_item['TravelActionItem']['next_action_by'] = $next_action_by;
                $tr_action_item['TravelActionItem']['parent_action_item_id'] = '';
                $this->TravelActionItem->save($tr_action_item);
                $ActionId = $this->TravelActionItem->getLastInsertId();
                $ActionUpdateArr['TravelActionItem']['parent_action_item_id'] = "'" . $ActionId . "'";
                $this->TravelActionItem->updateAll($ActionUpdateArr['TravelActionItem'], array('TravelActionItem.id' => $ActionId));

                $this->Session->setFlash('Your changes have been submitted. Waiting for approval at the moment...', 'success');
                $this->redirect(array('action' => 'supplier_country'));
            }
        }
    }

    public function add_city_mapping() {

        //$mapped = $this->data['mapped'];
        $dummy_status = $this->Auth->user('dummy_status');
        $role_id = $this->Session->read("role_id");
        $user_id = $this->Auth->user('id');

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->data['mapped'])) {
                $supplier_city_id = $this->data['Common']['supplier_city_id'];
                $city_id = $this->data['Common']['city_id'];
                //die;
                $SupplierCities = $this->SupplierCity->findById($supplier_city_id);
                $TravelCities = $this->TravelCity->findById($city_id);

                $this->set(compact('SupplierCities', 'TravelCities'));
            }



            if (isset($this->data['add'])) {
                $supplier_city_id = $this->data['SupplierCity']['supplier_city_id'];
                $city_id = $this->data['SupplierCity']['city_id'];

                $SupplierCities = $this->SupplierCity->findById($supplier_city_id);
                $TravelCities = $this->TravelCity->findById($city_id);

                $next_action_by = '166';  //overseer 136 44 is sarika 152 - ojas
                $flag = 0;
                $search_condition = array();
                $condition = '';
                $success = '';

                $this->request->data['Mappinge']['supplier_code'] = $SupplierCities['SupplierCity']['supplier_code'];
                $this->request->data['Mappinge']['mapping_type'] = '2'; // supplier country
                $this->request->data['Mappinge']['city_wtb_code'] = $TravelCities['TravelCity']['city_code'];
                $this->request->data['Mappinge']['city_supplier_code'] = $SupplierCities['SupplierCity']['code'];
                $this->request->data['Mappinge']['country_wtb_code'] = $TravelCities['TravelCity']['country_code'];

                $this->request->data['TravelCitySupplier']['city_supplier_status'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $this->request->data['TravelCitySupplier']['active'] = 'FALSE'; // 2 for No of lookup_value_statuses
                $this->request->data['TravelCitySupplier']['excluded'] = 'FALSE'; // 2 for No of lookup_value_statuses
                $this->request->data['TravelCitySupplier']['wtb_status'] = '1'; // 1 = true
                $this->request->data['TravelCitySupplier']['supplier_code'] = $SupplierCities['SupplierCity']['supplier_code'];
                $this->request->data['TravelCitySupplier']['supplier_city_code'] = $SupplierCities['SupplierCity']['code'];
                $this->request->data['TravelCitySupplier']['pf_city_code'] = $TravelCities['TravelCity']['city_code'];
                $this->request->data['TravelCitySupplier']['city_country_code'] = $TravelCities['TravelCity']['country_code'];
                $this->request->data['TravelCitySupplier']['province_id'] = $TravelCities['TravelCity']['province_id'];
                $this->request->data['TravelCitySupplier']['province_name'] = $TravelCities['TravelCity']['province_name'];

                $this->request->data['TravelCitySupplier']['city_name'] = $TravelCities['TravelCity']['city_name'];
                $this->request->data['TravelCitySupplier']['city_id'] = $TravelCities['TravelCity']['id'];
                $this->request->data['TravelCitySupplier']['city_mapping_name'] = strtoupper('[SUPP/CITY] | ' . $SupplierCities['SupplierCity']['supplier_code'] . ' | ' . $TravelCities['TravelCity']['country_code'] . ' | ' . $TravelCities['TravelCity']['city_code'] . ' - ' . $TravelCities['TravelCity']['city_name']);
                $this->request->data['TravelCitySupplier']['created_by'] = $user_id;

                $this->request->data['TravelCitySupplier']['city_supplier_id'] = $SupplierCities['SupplierCity']['id'];
                $this->request->data['TravelCitySupplier']['city_country_name'] = $TravelCities['TravelCity']['country_name'];
                $this->request->data['TravelCitySupplier']['city_country_id'] = $TravelCities['TravelCity']['country_id'];
                $this->request->data['TravelCitySupplier']['city_continent_id'] = $TravelCities['TravelCity']['continent_id'];
                $this->request->data['TravelCitySupplier']['city_continent_name'] = $TravelCities['TravelCity']['continent_name'];
                $this->request->data['TravelCitySupplier']['supplier_coutry_code'] = $TravelCities['TravelCity']['country_code'];
                $this->request->data['Mappinge']['country_supplier_code'] = $TravelCities['TravelCity']['country_code'];

                $tr_remarks['TravelRemark']['remarks_level'] = '3'; // for Mapping City from travel_action_remark_levels
                $tr_remarks['TravelRemark']['remarks'] = 'New Supplier City Record Created';


                $tr_action_item['TravelActionItem']['level_id'] = '3'; // for agent travel_action_remark_levels            
                $tr_action_item['TravelActionItem']['description'] = 'New Supplier City Record Created - Submission For Approval';


                $this->TravelCitySupplier->save($this->request->data['TravelCitySupplier']);
                $city_supplier_id = $this->TravelCitySupplier->getLastInsertId();
                if ($city_supplier_id) {
                    $this->request->data['Mappinge']['city_supplier_id'] = $city_supplier_id;
                    $tr_remarks['TravelRemark']['city_supplier_id'] = $city_supplier_id;
                    $tr_action_item['TravelActionItem']['city_supplier_id'] = $city_supplier_id;
                    $flag = 1;
                }

                $this->request->data['Mappinge']['created_by'] = $user_id;
                $this->request->data['Mappinge']['status'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $this->request->data['Mappinge']['exclude'] = '2'; // 2 for No of lookup_value_statuses
                $this->request->data['Mappinge']['dummy_status'] = $dummy_status;
                $this->Mappinge->save($this->request->data['Mappinge']);

                $tr_remarks['TravelRemark']['created_by'] = $user_id;
                $tr_remarks['TravelRemark']['remarks_time'] = date('g:i A');

                $tr_remarks['TravelRemark']['dummy_status'] = $dummy_status;
                $this->TravelRemark->save($tr_remarks);

                /*
                 * ********************** Action *********************
                 */

                $tr_action_item['TravelActionItem']['type_id'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $tr_action_item['TravelActionItem']['action_item_active'] = 'Yes';
                $tr_action_item['TravelActionItem']['action_item_source'] = $role_id;
                $tr_action_item['TravelActionItem']['created_by_id'] = $user_id;
                $tr_action_item['TravelActionItem']['created_by'] = $user_id;
                $tr_action_item['TravelActionItem']['dummy_status'] = $dummy_status;
                $tr_action_item['TravelActionItem']['next_action_by'] = $next_action_by;
                $tr_action_item['TravelActionItem']['parent_action_item_id'] = '';
                $this->TravelActionItem->save($tr_action_item);
                $ActionId = $this->TravelActionItem->getLastInsertId();
                $ActionUpdateArr['TravelActionItem']['parent_action_item_id'] = "'" . $ActionId . "'";
                $this->TravelActionItem->updateAll($ActionUpdateArr['TravelActionItem'], array('TravelActionItem.id' => $ActionId));

                $this->Session->setFlash('Your changes have been submitted. Waiting for approval at the moment...', 'success');
                $this->redirect(array('action' => 'supplier_city'));
            }
        }
    }

    public function hotel_mapping($id = null) {

        $condition = array();
        $search_condition = array();

        if (!$id) {
            throw new NotFoundException(__('Invalid Hotel'));
        }

        $SupplierHotels = $this->SupplierHotel->findById($id);
		


        if (count($SupplierHotels)) {

            $hotel_name = $SupplierHotels['SupplierHotel']['hotel_name'];


            for ($indexOfFirstLetter = 0; $indexOfFirstLetter <= strlen($hotel_name); $indexOfFirstLetter++) {
                for ($indexOfLastLetter = $indexOfFirstLetter + 1; $indexOfLastLetter <= strlen($hotel_name); $indexOfLastLetter++) {
                    $new_arr[] = substr($hotel_name, $indexOfFirstLetter, 4);
                    //pr($new_arr);
                    //array_push($search_condition, ARRAY('OR'));
                    $condition[] = array("TravelHotelLookup.hotel_name LIKE '%$new_arr[$indexOfFirstLetter]%'");
                    //array_push($search_condition,  array("TravelCity.city_name LIKE '%$new_arr[$indexOfFirstLetter]%'"));
                    /*
                      $condition .= "(TravelCity.city_name LIKE '%" . $new_arr[$indexOfFirstLetter] . "%')";
                      if ($indexOfFirstLetter < strlen($city_name) - 1)
                      $condition .= 'OR';
                      $search_condition[] = $condition;
                     * 
                     */
                    $indexOfFirstLetter++;
                }
            }
            //pr($condition);
            array_push($search_condition, array('OR' => $condition));
           // pr($search_condition);
            // die;
            /*
              $TravelCities = $this->TravelCity->find
              (
              'all', array
              (

              'conditions' => array
              (
              $condition
              //'TravelCity.city_name like' => '%'.$city_name.'%'
              ),
              'order' => 'TravelCity.id ASC',
              )
              );
             * 
             */
            $this->paginate['order'] = array('TravelHotelLookup.hotel_name' => 'asc');
            $this->set('TravelHotelLookups', $this->paginate("TravelHotelLookup", $search_condition));
            // $this->set(compact('TravelCities'));
        }

        // pr($condition);
       // $log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);       
        //debug($log);
        //die;

        $this->request->data = $SupplierHotels;
    }

    public function add_hotel_mapping() {

        //$mapped = $this->data['mapped'];
        $dummy_status = $this->Auth->user('dummy_status');
        $role_id = $this->Session->read("role_id");
        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $user_id = $this->Auth->user('id');
        $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');
        $address = '';
        
        $content_xml_str = '<soap:Body>
        <ProcessXML xmlns="http://www.travel.domain/">
            <RequestInfo>
                <GetDirectSupplierStaticData>
                    <RequestAuditInfo>
                        <RequestType>PXML_DirectSupplier_GetStaticData_Prod</RequestType>
                        <RequestTime>'.$CreatedDate.'</RequestTime>
                        <RequestResource>Silkrouters</RequestResource>
                    </RequestAuditInfo>
                    <RequestParameters>
                        <SupplierDataType>HotelDetail</SupplierDataType>
                        <SupplierId>2</SupplierId>
                        <CountryCode></CountryCode>
                        <CityCode></CityCode>
                        <HotelCode>9564</HotelCode>
                    </RequestParameters>
                </GetDirectSupplierStaticData>
            </RequestInfo>
        </ProcessXML>
    </soap:Body>';
        
        $log_call_screen = 'Supplier - Add';

            $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
            $client = new SoapClient(null, array(
                'location' => $location_URL,
                'uri' => '',
                'trace' => 1,
            ));

            try {
                $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);               
                $xmlArray = Xml::toArray(Xml::build($order_return));
                
                 $address = $xmlArray['Envelope']['soap:Body']['ProcessXMLResponse']['ProcessXMLResult']['SupplierData_HotelDetail']['ResponseAuditInfo']['root']['Address']['@'];
                
              
            } catch (SoapFault $exception) {
                var_dump(get_class($exception));
                var_dump($exception);
            }
            
            $this->set(compact('address'));

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->data['mapped'])) {
                $supplier_hotel_id = $this->data['Common']['supplier_hotel_id'];
                $hotel_id = $this->data['Common']['hotel_id'];
                //die;
                $SupplierHotels = $this->SupplierHotel->findById($supplier_hotel_id);
                $TravelHotelLookups = $this->TravelHotelLookup->findById($hotel_id);

                $this->set(compact('TravelHotelLookups', 'SupplierHotels'));
            }
            elseif (isset($this->data['add'])) {
                $supplier_hotel_id = $this->data['SupplierHotel']['supplier_hotel_id'];
                $hotel_id = $this->data['SupplierHotel']['hotel_id'];

                $SupplierHotels = $this->SupplierHotel->findById($supplier_hotel_id);
                $TravelHotelLookups = $this->TravelHotelLookup->findById($hotel_id);

                $next_action_by = '166';  //overseer 136 44 is sarika 152 - ojas
                $flag = 0;
                $search_condition = array();
                $condition = '';
                $success = '';

                $this->request->data['Mappinge']['supplier_code'] = $SupplierHotels['SupplierHotel']['supplier_code'];
                $this->request->data['Mappinge']['mapping_type'] = '3'; // supplier hotel
                $this->request->data['Mappinge']['hotel_wtb_code'] = $TravelHotelLookups['TravelHotelLookup']['hotel_code'];
                $this->request->data['Mappinge']['hotel_supplier_code'] = $SupplierHotels['SupplierHotel']['supplier_code'];
                $this->request->data['Mappinge']['city_wtb_code'] = $TravelHotelLookups['TravelHotelLookup']['city_code'];
                $this->request->data['Mappinge']['country_wtb_code'] = $TravelHotelLookups['TravelHotelLookup']['country_code'];

                $this->request->data['TravelHotelRoomSupplier']['hotel_supplier_status'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $this->request->data['TravelHotelRoomSupplier']['active'] = 'FALSE'; // 2 for No of lookup_value_statuses
                $this->request->data['TravelHotelRoomSupplier']['excluded'] = 'FALSE'; // 2 for No of lookup_value_statuses
                $this->request->data['TravelHotelRoomSupplier']['wtb_status'] = '1'; // 1 = true
                $this->request->data['TravelHotelRoomSupplier']['hotel_code'] = $TravelHotelLookups['TravelHotelLookup']['hotel_code'];
                $this->request->data['TravelHotelRoomSupplier']['supplier_code'] = $SupplierHotels['SupplierHotel']['supplier_code'];
                //$hotel_name_arr = $this->TravelHotelLookup->findByHotelCode($this->data['Mapping']['hotel_code'], array('fields' => 'hotel_name', 'id'));
                $this->request->data['TravelHotelRoomSupplier']['hotel_mapping_name'] = strtoupper('[SUPP/HOTEL] | ' . $SupplierHotels['SupplierHotel']['supplier_code'] . ' | ' . $TravelHotelLookups['TravelHotelLookup']['country_code'] . ' | ' . $TravelHotelLookups['TravelHotelLookup']['city_code'] . ' | ' . $TravelHotelLookups['TravelHotelLookup']['hotel_code'] . ' - ' . $TravelHotelLookups['TravelHotelLookup']['hotel_name']);
                $this->request->data['TravelHotelRoomSupplier']['hotel_name'] = $TravelHotelLookups['TravelHotelLookup']['hotel_name'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_id'] = $TravelHotelLookups['TravelHotelLookup']['id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_country_code'] = $TravelHotelLookups['TravelHotelLookup']['country_code'];
                $this->request->data['TravelHotelRoomSupplier']['supplier_item_code1'] = $TravelHotelLookups['TravelHotelLookup']['hotel_code'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_country_code'] = $TravelHotelLookups['TravelHotelLookup']['country_code'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_city_code'] = $TravelHotelLookups['TravelHotelLookup']['city_code'];
                //$TravelAreas = $this->TravelArea->find('first', array('fields' => array('area_name'), 'conditions' => array('id' => $this->data['Mapping']['hotel_area_id'])));
                $this->request->data['TravelHotelRoomSupplier']['hotel_area_id'] = $TravelHotelLookups['TravelHotelLookup']['area_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_area_name'] = $TravelHotelLookups['TravelHotelLookup']['area_name'];
                //$TravelBrands = $this->TravelBrand->find('first', array('fields' => array('TravelBrand.brand_name'), 'conditions' => array('TravelBrand.id' => $this->data['Mapping']['hotel_brand_id'])));
                $this->request->data['TravelHotelRoomSupplier']['hotel_brand_id'] = $TravelHotelLookups['TravelHotelLookup']['brand_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_brand_name'] = $TravelHotelLookups['TravelHotelLookup']['brand_name'];
                //$TravelSuburbs = $this->TravelSuburb->find('first', array('fields' => array('TravelSuburb.name'), 'conditions' => array('TravelSuburb.id' => $this->data['Mapping']['hotel_suburb_id'])));
                $this->request->data['TravelHotelRoomSupplier']['hotel_suburb_id'] = $TravelHotelLookups['TravelHotelLookup']['suburb_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_suburb_name'] = $TravelHotelLookups['TravelHotelLookup']['suburb_name'];
                //$TravelChains = $this->TravelChain->find('first', array('fields' => array('TravelChain.chain_name'), 'conditions' => array('TravelChain.id' => $this->data['Mapping']['hotel_chain_id'])));        
                $this->request->data['TravelHotelRoomSupplier']['hotel_chain_id'] = $TravelHotelLookups['TravelHotelLookup']['chain_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_chain_name'] = $TravelHotelLookups['TravelHotelLookup']['chain_name'];
                $this->request->data['TravelHotelRoomSupplier']['created_by'] = $user_id;
                $this->request->data['TravelHotelRoomSupplier']['province_id'] = $TravelHotelLookups['TravelHotelLookup']['province_id'];
                $this->request->data['TravelHotelRoomSupplier']['province_name'] = $TravelHotelLookups['TravelHotelLookup']['province_name'];

                //$supp_country_code = $this->TravelCountrySupplier->find('first', array('fields' => array('supplier_country_code', 'country_id', 'country_name', 'country_continent_id', 'country_continent_name'), 'conditions' => array('supplier_code' => $this->data['Mapping']['hotel_supplier_code'], 'pf_country_code' => $this->data['Mapping']['hotel_country_code'])));
                //$supp_country_code = $this->TravelCountrySupplier->find('first', array('fields' => array('supplier_country_code'), 'conditions' => array('supplier_code' => $this->data['Mapping']['hotel_supplier_code'], 'pf_country_code' => $this->data['Mapping']['hotel_country_code'])));
                $this->request->data['TravelHotelRoomSupplier']['supplier_item_code4'] = $TravelHotelLookups['TravelHotelLookup']['country_code'];
                $this->request->data['Mappinge']['country_supplier_code'] = $TravelHotelLookups['TravelHotelLookup']['country_code'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_country_id'] = $TravelHotelLookups['TravelHotelLookup']['country_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_country_name'] = $TravelHotelLookups['TravelHotelLookup']['country_name'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_continent_id'] = $TravelHotelLookups['TravelHotelLookup']['continent_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_continent_name'] = $TravelHotelLookups['TravelHotelLookup']['continent_name'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_supplier_id'] = $SupplierHotels['SupplierHotel']['id'];

                //$supp_city_code = $this->TravelCitySupplier->find('first', array('fields' => array('supplier_city_code', 'city_id', 'city_name'), 'conditions' => array('supplier_code' => $this->data['Mapping']['hotel_supplier_code'], 'pf_city_code' => $this->data['Mapping']['hotel_city_code'], 'city_country_code' => $this->data['Mapping']['hotel_country_code'])));
                $this->request->data['TravelHotelRoomSupplier']['supplier_item_code3'] = $TravelHotelLookups['TravelHotelLookup']['city_code'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_city_id'] = $TravelHotelLookups['TravelHotelLookup']['city_id'];
                $this->request->data['TravelHotelRoomSupplier']['hotel_city_name'] = $TravelHotelLookups['TravelHotelLookup']['city_name'];
                $this->request->data['Mappinge']['city_supplier_code'] = $TravelHotelLookups['TravelHotelLookup']['city_code'];

                $tr_remarks['TravelRemark']['remarks_level'] = '4'; // for Mapping City from travel_action_remark_levels
                $tr_remarks['TravelRemark']['remarks'] = 'New Supplier Hotel Record Created';

                $tr_action_item['TravelActionItem']['level_id'] = '4'; // for agent travel_action_remark_levels                 
                $tr_action_item['TravelActionItem']['description'] = 'New Supplier Hotel Record Created - Submission For Approval';

                /*
                  $permissionArray = $this->ProvincePermission->find('first',array('conditions' => array('continent_id' => $supp_country_code['TravelCountrySupplier']['country_continent_id'],'country_id' => $supp_country_code['TravelCountrySupplier']['country_id'],'province_id' => $this->data['Mapping']['hotel_province_id'],'user_id' => $user_id)));
                  if(isset($permissionArray['ProvincePermission']['approval_id']))
                  $next_action_by = $permissionArray['ProvincePermission']['approval_id'];
                  else
                 * 
                 */
                $next_action_by = '166'; //Infra Mapping
                $this->TravelHotelRoomSupplier->save($this->request->data['TravelHotelRoomSupplier']);
                //$this->TravelHotelLookup->updateAll(array('TravelHotelLookup.active' => "'FALSE'"), array('TravelHotelLookup.id' => $hotel_name_arr['TravelHotelLookup']['id']));
                $hotel_supplier_id = $this->TravelHotelRoomSupplier->getLastInsertId();
                if ($hotel_supplier_id) {
                    $this->request->data['Mappinge']['hotel_supplier_id'] = $hotel_supplier_id;
                    $tr_remarks['TravelRemark']['hotel_supplier_id'] = $hotel_supplier_id;
                    $tr_action_item['TravelActionItem']['hotel_supplier_id'] = $hotel_supplier_id;
                    $flag = 1;
                }

                $this->request->data['Mappinge']['created_by'] = $user_id;
                $this->request->data['Mappinge']['status'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $this->request->data['Mappinge']['exclude'] = '2'; // 2 for No of lookup_value_statuses
                $this->request->data['Mappinge']['dummy_status'] = $dummy_status;
                $this->Mappinge->save($this->request->data['Mappinge']);

                $tr_remarks['TravelRemark']['created_by'] = $user_id;
                $tr_remarks['TravelRemark']['remarks_time'] = date('g:i A');

                $tr_remarks['TravelRemark']['dummy_status'] = $dummy_status;
                $this->TravelRemark->save($tr_remarks);

                /*
                 * ********************** Action *********************
                 */

                $tr_action_item['TravelActionItem']['type_id'] = '1'; // 1 for Submission For Approval [None] of the travel_action_item_types
                $tr_action_item['TravelActionItem']['action_item_active'] = 'Yes';
                $tr_action_item['TravelActionItem']['action_item_source'] = $role_id;
                $tr_action_item['TravelActionItem']['created_by_id'] = $user_id;
                $tr_action_item['TravelActionItem']['created_by'] = $user_id;
                $tr_action_item['TravelActionItem']['dummy_status'] = $dummy_status;
                $tr_action_item['TravelActionItem']['next_action_by'] = $next_action_by;
                $tr_action_item['TravelActionItem']['parent_action_item_id'] = '';
                $this->TravelActionItem->save($tr_action_item);
                $ActionId = $this->TravelActionItem->getLastInsertId();
                $ActionUpdateArr['TravelActionItem']['parent_action_item_id'] = "'" . $ActionId . "'";
                $this->TravelActionItem->updateAll($ActionUpdateArr['TravelActionItem'], array('TravelActionItem.id' => $ActionId));

                $this->Session->setFlash('Your changes have been submitted. Waiting for approval at the moment...', 'success');
                $this->redirect(array('action' => 'supplier_hotels'));
            }
            elseif(isset($this->data['inserted'])){
                
               $screen = '4'; // fetch hotel table of  
               $supplier_hotel_id = $this->data['Common']['supplier_hotel_id'];
               echo $hotel_id = $this->data['Common']['hotel_id'];
               die;
               $hotel_code = $this->Common->getHotelCode($hotel_id);
               $hotel_name = $this->Common->getHotelName($hotel_id);
               echo $about = $hotel_name.' | '.$hotel_code.' | '.$hotel_id;
               die;
               $answer = '36'; // table of lookup_questions
               $this->request->data['SupportTicket']['status'] = '1'; // 1 = open
            $this->request->data['SupportTicket']['opend_by'] = 'SENDER';
            $this->request->data['SupportTicket']['active'] = 'TRUE';
            $this->request->data['SupportTicket']['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->request->data['SupportTicket']['question_id'] = 'What is the issue?';
            $this->request->data['SupportTicket']['about'] = $about;
            $this->request->data['SupportTicket']['answer'] = $answer;
            
            $department_id = $this->SupportTicket->getDepartmentByQuestionId($answer);
            $this->request->data['SupportTicket']['next_action_by'] = $this->SupportTicket->getNextActionByDepartmentId($department_id);
            $this->request->data['SupportTicket']['department_id'] = $department_id;
            $this->request->data['SupportTicket']['type'] = '1'; // Internal
            $this->request->data['SupportTicket']['created_by'] = $user_id;
            $this->request->data['SupportTicket']['last_action_by'] = $user_id;
            $this->request->data['SupportTicket']['screen'] = $screen;
            $this->request->data['SupportTicket']['response_issue_id'] = $answer;
                 if ($this->SupportTicket->save($this->request->data['SupportTicket'])) {
                     
                 }
            }
        }
    }

}
