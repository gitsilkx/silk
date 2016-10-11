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
App::uses('Xml', 'Utility');
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
        'TravelSuburb', 'TravelArea', 'TravelBrand', 'TravelActionItem', 'TravelRemark', 'LogCall', 'User', 'Province', 'ProvincePermission', 'DeleteTravelHotelLookup', 'DeleteLogTable',
        'TravelLookupRateType', 'TravelLookupPropertyType', 'TravelCitySupplier', 'TravelHotelImage');
    public $components = array('Sms', 'Image','RequestHandler');

    public $uploadDir;


    public function beforeFilter() {
        parent::beforeFilter();
        $this->uploadDir = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . '/uploads/hotels';
        $this->Auth->allow('index','test');
        $this->Width = '200';
        $this->Height = '200';
    }

    public function index() {

        
        $city_id = $this->Auth->user('city_id');
        $user_id = $this->Auth->user('id');
        
        $search_condition = array();
        $service_status = '';
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
        $TravelHotelLookups = array();


 
        //next($proArr);

        if(isset($_REQUEST['a'])){
            $service_status = "";
            $this->Session->setFlash('Image uploaded successfully.', 'success');
        } else {
            if ($this->ServiceCheck() == 'true'){
               $service_status = "All services running normally.";
            }   else {
               $service_status = "Some services are offline. Please try later.";
            }  
        }

            if($this->checkImageProvince())

            $proArr = $this->checkImageProvince();

        //if ($this->checkImageProvince()) {
           
            array_push($search_condition, array('TravelHotelLookup.province_id' => $this->checkImageProvince()));
            
        //}

        

        if ($this->request->is('post') || $this->request->is('put')) {
          
           

            if (!empty($this->data['TravelHotelLookup']['hotel_name'])) {

                $hotel_name = $this->data['TravelHotelLookup']['hotel_name'];

                array_push($search_condition, array("SupplierHotel.hotel_name LIKE '%$hotel_name%'"));

            }

            if (!empty($this->data['TravelHotelLookup']['continent_id'])) {

                $continent_id = $this->data['TravelHotelLookup']['continent_id'];

                array_push($search_condition, array('TravelHotelLookup.continent_id' => $continent_id));

                $TravelCountries = $this->TravelCountry->find('all', array('fields' => 'id, country_name,country_code', 'conditions' => array('TravelCountry.continent_id' => $continent_id,

                        'TravelCountry.country_status' => '1',

                        'TravelCountry.wtb_status' => '1',

                        'TravelCountry.active' => 'TRUE'), 'order' => 'country_code ASC'));

                $TravelCountries = Set::combine($TravelCountries, '{n}.TravelCountry.id', array('%s - %s', '{n}.TravelCountry.country_code', '{n}.TravelCountry.country_name'));

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

            }


            
            $TravelHotelLookups = $this->TravelHotelLookup->find('all', array(
          
            'joins' => array(
                array(
                    'table' => 'travel_hotel_room_suppliers',
                    'alias' => 'TravelHotelRoomSupplier',
                    'type' => 'INNER',
                    'conditions' => 'TravelHotelRoomSupplier.hotel_id = TravelHotelLookup.id'
                ),

            ),
            'conditions' => $search_condition,
            'order' => 'TravelHotelLookup.hotel_name',
            ));

         //$log = $this->TravelHotelLookup->getDataSource()->getLog(false, false);       
        //debug($log);
        //die;
            
            
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



        if (!isset($this->data) && empty($this->data)) {
          
            $this->data['TravelHotelLookup']['continent_id'] = $this->passedArgs['continent_id'];
            $this->data['TravelHotelLookup']['country_id'] = $this->passedArgs['country_id'];
            $this->data['TravelHotelLookup']['province_id'] = $this->passedArgs['province_id'];
            $this->data['TravelHotelLookup']['city_id'] = $this->passedArgs['city_id'];
        }

        $TravelLookupContinents = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'order' => 'continent_name ASC'));
        
             
        
        $this->set(compact('hotel_name', 'continent_id','TravelHotelLookups', 'country_id', 'city_id', 'suburb_id', 'area_id', 'TravelChains', 'status', 'active', 'chain_id', 'brand_id', 'wtb_status', 'TravelCountries', 'TravelCities', 'TravelSuburbs', 'TravelAreas', 'TravelChains', 'TravelBrands', 'TravelLookupValueContractStatuses', 'TravelLookupContinents', 'mapped_count', 'srilanka_count', 'maldives_count', 'singapore_count', 'malaysia_count', 'new_zealand_count', 'melbourne_count', 'abu_dhabi_count', 'sharjah_count', 'dubai_count', 'uae_count', 'india_count', 'phuket_count', 'pattaya_count', 'bangkok_count', 'thailand_count', 'below_three_star_count', 'three_star_count', 'four_five_star', 'Provinces', 'province_id','service_status','user_id'));
    }

    public function ServiceCheck() {

        $headding = '';
        $order_return = '';
        $log_call_screen = '';
        $xml_msg = '';
        $location_URL = 'http://dev.wtbnetworks.com/TravelXmlManagerv001/ProEngine.Asmx';
        $action_URL = 'http://www.travel.domain/ProcessXML';
        $CreatedDate = date('Y-m-d') . 'T' . date('h:i:s');
        
                        $content_xml_str = '<soap:Body>

                                        <ProcessXML xmlns="http://www.travel.domain/">

                                            <RequestInfo>

                                                <ResourceDataRequest>

                                                    <RequestAuditInfo>

                                                        <RequestType>PXML_RequestServiceInfo</RequestType>

                                                        <RequestTime>' . $CreatedDate . '</RequestTime>

                                                        <RequestResource>Silkrouters</RequestResource>

                                                    </RequestAuditInfo>

                                                    <RequestParameters>                        

                                                        <ResourceData>

                                                            <ResourceDetailsData actiontype="CheckRequestAll">

                                                                <Language>UK/US-ENGLISH</Language>

                                                            </ResourceDetailsData>              

                                                    </ResourceData>

                                                    </RequestParameters>

                                                </ResourceDataRequest>

                                            </RequestInfo>

                                        </ProcessXML>

                                    </soap:Body>';


        $RESOURCEDATA = 'RESOURCEDATA_REQUESTSERVICEINFO';
        $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
                $client = new SoapClient(null, array(
                    'location' => $location_URL,
                    'uri' => '',
                    'trace' => 1,
                ));

                try {
                    $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);
                    $xml_arr = $this->xml2array($order_return);
                    if ($xml_arr['SOAP:ENVELOPE']['SOAP:BODY']['PROCESSXMLRESPONSE']['PROCESSXMLRESULT'][$RESOURCEDATA]['RESPONSEAUDITINFO']['RESPONSEINFO']['RESPONSEID'][0] == '201') {
                        return 'true';
                    } else {
                        return 'false';
                    }
                } catch (SoapFault $exception) {
                    var_dump(get_class($exception));
                    var_dump($exception);
                }
                
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
        $Provinces = array();
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
            $image5 = '';
            $image6 = '';
            $error_msg = '';

            $HotelName = $this->data['TravelHotelLookup']['hotel_name'];
            $ImageName = trim($HotelName);
            $ImageName = str_replace(' ', '-', $ImageName);
            

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image1']['tmp_name'])) {
                $file_type = $this->request->data['TravelHotelLookup']['image1']['type'];
                $file_size = $this->request->data['TravelHotelLookup']['image1']['size'];
                if ($this->ImagefileCheck($file_type, $file_size) == 'true') {
                    $image1 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img1'], $this->request->data['TravelHotelLookup']['image1'], $this->uploadDir, $ImageName . '-1');
                    $this->request->data['TravelHotelLookup']['full_img1'] = 'http://imageius.com/uploads/hotels/' . $image1;
                    $this->request->data['TravelHotelLookup']['thumb_img1'] = 'http://imageius.com/uploads/hotels/thumbs/' . $image1;
                    $this->Image->thumbnail($this->uploadDir . '/' . $image1, 'thumbs', $this->Width, $this->Height);

                    $file_thum = $this->uploadDir . '/thumbs/' . $image1;
                    $dstfile_thum = 'uploads/hotels/thumbs/' . $image1;

                    $file = $this->uploadDir . '/' . $image1;
                    $dstfile = 'uploads/hotels/' . $image1;

                    if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII)) {
                        ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                        //echo "Successfully uploaded $file.";
                    }
                } else {
                    $error_msg .= 'Picture1, ';
                }

                // close connection
                //ftp_close($ftp_conn);
                $this->Image->delete($image1, $this->uploadDir);
                $this->Image->delete($image1, $this->uploadDir . '/thumbs/');
            } else {
                unset($this->request->data['TravelHotelLookup']['image1']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image2']['tmp_name'])) {
                $file_type = $this->request->data['TravelHotelLookup']['image2']['type'];
                $file_size = $this->request->data['TravelHotelLookup']['image2']['size'];
                if ($this->ImagefileCheck($file_type, $file_size) == 'true') {
                    $image2 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img2'], $this->request->data['TravelHotelLookup']['image2'], $this->uploadDir, $ImageName . '-2');
                    $this->request->data['TravelHotelLookup']['full_img2'] = 'http://imageius.com/uploads/hotels/' . $image2;
                    $this->request->data['TravelHotelLookup']['thumb_img2'] = 'http://imageius.com/uploads/hotels/thumbs/' . $image2;
                    $this->Image->thumbnail($this->uploadDir . '/' . $image2, 'thumbs', $this->Width, $this->Height);

                    $file_thum = $this->uploadDir . '/thumbs/' . $image2;
                    $dstfile_thum = 'uploads/hotels/thumbs/' . $image2;

                    $file = $this->uploadDir . '/' . $image2;
                    $dstfile = 'uploads/hotels/' . $image2;

                    if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII)) {
                        ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                        //echo "Successfully uploaded $file.";
                    }
                } else {
                    $error_msg .= 'Picture2, ';
                }

                // close connection
                //ftp_close($ftp_conn);
                $this->Image->delete($image2, $this->uploadDir);
                $this->Image->delete($image2, $this->uploadDir . '/thumbs/');
            } else {
                unset($this->request->data['TravelHotelLookup']['image2']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image3']['tmp_name'])) {
                $file_type = $this->request->data['TravelHotelLookup']['image3']['type'];
                $file_size = $this->request->data['TravelHotelLookup']['image3']['size'];
                if ($this->ImagefileCheck($file_type, $file_size) == 'true') {
                    $image3 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img3'], $this->request->data['TravelHotelLookup']['image3'], $this->uploadDir, $ImageName . '-3');
                    $this->request->data['TravelHotelLookup']['full_img3'] = 'http://imageius.com/uploads/hotels/' . $image3;
                    $this->request->data['TravelHotelLookup']['thumb_img3'] = 'http://imageius.com/uploads/hotels/thumbs/' . $image3;
                    $this->Image->thumbnail($this->uploadDir . '/' . $image3, 'thumbs', $this->Width, $this->Height);

                    $file_thum = $this->uploadDir . '/thumbs/' . $image3;
                    $dstfile_thum = 'uploads/hotels/thumbs/' . $image3;

                    $file = $this->uploadDir . '/' . $image3;
                    $dstfile = 'uploads/hotels/' . $image3;

                    if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII)) {
                        ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                        //echo "Successfully uploaded $file.";
                    }
                } else {
                    $error_msg .= 'Picture3, ';
                }

                // close connection
                //ftp_close($ftp_conn);
                $this->Image->delete($image3, $this->uploadDir);
                $this->Image->delete($image3, $this->uploadDir . '/thumbs/');
            } else {
                unset($this->request->data['TravelHotelLookup']['image3']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image4']['tmp_name'])) {

                $file_type = $this->request->data['TravelHotelLookup']['image4']['type'];
                $file_size = $this->request->data['TravelHotelLookup']['image4']['size'];
                if ($this->ImagefileCheck($file_type, $file_size) == 'true') {
                    $image4 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img4'], $this->request->data['TravelHotelLookup']['image4'], $this->uploadDir, $ImageName . '-4');
                    $this->request->data['TravelHotelLookup']['full_img4'] = 'http://imageius.com/uploads/hotels/' . $image4;
                    $this->request->data['TravelHotelLookup']['thumb_img4'] = 'http://imageius.com/uploads/hotels/thumbs/' . $image4;
                    $this->Image->thumbnail($this->uploadDir . '/' . $image4, 'thumbs', $this->Width, $this->Height);

                    $file_thum = $this->uploadDir . '/thumbs/' . $image4;
                    $dstfile_thum = 'uploads/hotels/thumbs/' . $image4;

                    $file = $this->uploadDir . '/' . $image4;
                    $dstfile = 'uploads/hotels/' . $image4;

                    if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII)) {
                        ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                        //echo "Successfully uploaded $file.";
                    }
                } else {
                    $error_msg .= 'Picture4, ';
                }

                // close connection
                //ftp_close($ftp_conn);
                $this->Image->delete($image4, $this->uploadDir);
                $this->Image->delete($image4, $this->uploadDir . '/thumbs/');
            } else {
                unset($this->request->data['TravelHotelLookup']['image4']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image5']['tmp_name'])) {

                $file_type = $this->request->data['TravelHotelLookup']['image5']['type'];
                $file_size = $this->request->data['TravelHotelLookup']['image5']['size'];
                if ($this->ImagefileCheck($file_type, $file_size) == 'true') {
                    $image5 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img5'], $this->request->data['TravelHotelLookup']['image5'], $this->uploadDir, $ImageName . '-5');
                    $this->request->data['TravelHotelLookup']['full_img5'] = 'http://imageius.com/uploads/hotels/' . $image5;
                    $this->request->data['TravelHotelLookup']['thumb_img5'] = 'http://imageius.com/uploads/hotels/thumbs/' . $image5;
                    $this->Image->thumbnail($this->uploadDir . '/' . $image5, 'thumbs', $this->Width, $this->Height);

                    $file_thum = $this->uploadDir . '/thumbs/' . $image5;
                    $dstfile_thum = 'uploads/hotels/thumbs/' . $image5;

                    $file = $this->uploadDir . '/' . $image5;
                    $dstfile = 'uploads/hotels/' . $image5;

                    if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII)) {
                        ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                        //echo "Successfully uploaded $file.";
                    }
                } else {
                    $error_msg .= 'Picture5, ';
                }

                // close connection
                //ftp_close($ftp_conn);
                $this->Image->delete($image5, $this->uploadDir);
                $this->Image->delete($image5, $this->uploadDir . '/thumbs/');
            } else {
                unset($this->request->data['TravelHotelLookup']['image5']);
            }

            if (is_uploaded_file($this->request->data['TravelHotelLookup']['image6']['tmp_name'])) {
                $file_type = $this->request->data['TravelHotelLookup']['image6']['type'];
                $file_size = $this->request->data['TravelHotelLookup']['image6']['size'];
                if ($this->ImagefileCheck($file_type, $file_size) == 'true') {
                    $image6 = $this->Image->upload($TravelHotelLookups['TravelHotelLookup']['full_img6'], $this->request->data['TravelHotelLookup']['image6'], $this->uploadDir, $ImageName . '-6');
                    $this->request->data['TravelHotelLookup']['full_img6'] = 'http://imageius.com/uploads/hotels/' . $image6;
                    $this->request->data['TravelHotelLookup']['thumb_img6'] = 'http://imageius.com/uploads/hotels/thumbs/' . $image6;
                    $this->Image->thumbnail($this->uploadDir . '/' . $image6, 'thumbs', $this->Width, $this->Height);

                    $file_thum = $this->uploadDir . '/thumbs/' . $image6;
                    $dstfile_thum = 'uploads/hotels/thumbs/' . $image6;

                    $file = $this->uploadDir . '/' . $image6;
                    $dstfile = 'uploads/hotels/' . $image6;

                    if (ftp_put($ftp_conn, $dstfile, $file, FTP_ASCII)) {
                        ftp_put($ftp_conn, $dstfile_thum, $file_thum, FTP_ASCII);
                        //echo "Successfully uploaded $file.";
                    }
                } else {
                    $error_msg .= 'Picture6';
                }

                // close connection
                //ftp_close($ftp_conn);
                $this->Image->delete($image6, $this->uploadDir);
                $this->Image->delete($image6, $this->uploadDir . '/thumbs/');
            } else {
                unset($this->request->data['TravelHotelLookup']['image6']);
            }
           
            if ($error_msg <> '') {
                $er_msg = 'Wrong image type/size (File size should be <= 3 mb) - '.$error_msg;
                $this->Session->setFlash($er_msg, 'failure');               
                $this->redirect(array('action' => 'edit/' . $id));
            }
             ftp_close($ftp_conn);
            $HotelId = $id;
            $HotelCode = $TravelHotelLookups['TravelHotelLookup']['hotel_code'];

            $AreaId = $this->data['TravelHotelLookup']['area_id'];
            // $AreaCode = $this->data['TravelHotelLookup']['area_code'];


            $AreaName = $this->data['TravelHotelLookup']['area_name'];

            $SuburbId = $this->data['TravelHotelLookup']['suburb_id'];

            $SuburbName = $this->data['TravelHotelLookup']['suburb_name'];

            $CityId = $this->data['TravelHotelLookup']['city_id'];

            $CityName = $this->data['TravelHotelLookup']['city_name'];
            $CityCode = $this->data['TravelHotelLookup']['city_code'];
            $CountryId = $TravelHotelLookups['TravelHotelLookup']['country_id'];
            $CountryName = $TravelHotelLookups['TravelHotelLookup']['country_name'];
            $CountryCode = $TravelHotelLookups['TravelHotelLookup']['country_code'];
            $ContinentId = $TravelHotelLookups['TravelHotelLookup']['continent_id'];
            $ContinentName = $TravelHotelLookups['TravelHotelLookup']['continent_name'];
            $ContinentCode = $TravelHotelLookups['TravelHotelLookup']['continent_code'];
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
            $HotelImage2 = $this->data['TravelHotelLookup']['full_img2'];
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
            $Active = $TravelHotelLookups['TravelHotelLookup']['active'];
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
            $ProvinceId = $TravelHotelLookups['TravelHotelLookup']['province_id'];
            $ProvinceName = $TravelHotelLookups['TravelHotelLookup']['province_name'];
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
                                                                <HotelId>' . $HotelId . '</HotelId>
                                <HotelCode><![CDATA[' . $HotelCode . ']]></HotelCode>
                                <HotelName><![CDATA[' . $HotelName . ']]></HotelName>
                                <AreaId>' . $AreaId . '</AreaId>
                                <AreaCode>NA</AreaCode>
                                <AreaName><![CDATA[' . $AreaName . ']]></AreaName>
                                <SuburbId>' . $SuburbId . '</SuburbId>
                                <SuburbCode>NA</SuburbCode>
                                <SuburbName><![CDATA[' . $SuburbName . ']]></SuburbName>
                                <CityId>' . $CityId . '</CityId>
                                <CityCode><![CDATA[' . $CityCode . ']]></CityCode>
                                <CityName><![CDATA[' . $CityName . ']]></CityName>
                                <CountryId>' . $CountryId . '</CountryId>
                                <CountryCode><![CDATA[' . $CountryCode . ']]></CountryCode>
                                <CountryName><![CDATA[' . $CountryName . ']]></CountryName>
                                <ContinentId>' . $ContinentId . '</ContinentId>
                                <ContinentCode><![CDATA[' . $ContinentCode . ']]></ContinentCode>
                                <ContinentName><![CDATA[' . $ContinentName . ']]></ContinentName>
                                <ProvinceId>' . $ProvinceId . '</ProvinceId>
                                <ProvinceName><![CDATA[' . $ProvinceName . ']]></ProvinceName>
                                <BrandId>' . $BrandId . '</BrandId>
                                <BrandName><![CDATA[' . $BrandName . ']]></BrandName>
                                <ChainId>' . $ChainId . '</ChainId>
                                <ChainName><![CDATA[' . $ChainName . ']]></ChainName>
                                <HotelComment><![CDATA[' . $HotelComment . ']]></HotelComment>
                                <Star>' . $Star . '</Star>
                                <Keyword><![CDATA[' . $Keyword . ']]></Keyword>
                                <StandardRating>' . $StandardRating . '</StandardRating>
                                <HotelRating>' . $StandardRating . '</HotelRating>                                
                                <FoodRating>' . $FoodRating . '</FoodRating>
                                <ServiceRating>' . $ServiceRating . '</ServiceRating>
                                <LocationRating>' . $LocationRating . '</LocationRating>
                                <ValueRating>' . $ValueRating . '</ValueRating>
                                <OverallRating>' . $OverallRating . '</OverallRating>

                                <HotelImage1Full><![CDATA[' . $HotelImage1 . ']]></HotelImage1Full>
                                <HotelImage2Full><![CDATA[' . $HotelImage2 . ']]></HotelImage2Full>
                                <HotelImage3Full><![CDATA[' . $HotelImage3 . ']]></HotelImage3Full>
                                <HotelImage4Full><![CDATA[' . $HotelImage4 . ']]></HotelImage4Full>
                                <HotelImage5Full><![CDATA[' . $HotelImage5 . ']]></HotelImage5Full>
                                <HotelImage6Full><![CDATA[' . $HotelImage6 . ']]></HotelImage6Full>

                                <HotelImage1Thumb><![CDATA[' . $ThumbImage1 . ']]></HotelImage1Thumb>
                                <HotelImage2Thumb><![CDATA[' . $ThumbImage2 . ']]></HotelImage2Thumb>
                                <HotelImage3Thumb><![CDATA[' . $ThumbImage3 . ']]></HotelImage3Thumb>
                                <HotelImage4Thumb><![CDATA[' . $ThumbImage4 . ']]></HotelImage4Thumb>
                                <HotelImage5Thumb><![CDATA[' . $ThumbImage5 . ']]></HotelImage5Thumb>
                                <HotelImage6Thumb><![CDATA[' . $ThumbImage6 . ']]></HotelImage6Thumb>

                                <IsImage>false</IsImage>
                                <IsPage>false</IsPage>

                                <Logo>' . $Logo . '</Logo>
                                                                <Logo1>' . $Logo1 . '</Logo1>
                                                                <BusinessCenter>' . $BusinessCenter . '</BusinessCenter>
                                                                <MeetingFacilities>' . $MeetingFacilities . '</MeetingFacilities>
                                                                <DiningFacilities>' . $DiningFacilities . '</DiningFacilities>
                                                                <BarLounge>' . $BarLounge . '</BarLounge>
                                                                <FitnessCenter>' . $FitnessCenter . '</FitnessCenter>
                                                                <Pool>' . $Pool . '</Pool>
                                                                <Golf>' . $Golf . '</Golf>
                                                                <Tennis>' . $Tennis . '</Tennis>
                                                                <Kids>' . $Kids . '</Kids>
                                                                <Handicap>' . $Handicap . '</Handicap>
                                                                <URLHotel><![CDATA[' . $URLHotel . ']]></URLHotel>
                                                                <Address><![CDATA[' . $Address . ']]></Address>
                                                                <PostCode>' . $PostCode . '</PostCode>
                                                                <NoRoom>' . $NoRoom . '</NoRoom>
                                                                <Active>' . $Active . '</Active>
                                                                <ReservationEmail><![CDATA[' . $ReservationEmail . ']]></ReservationEmail>
                                                                <ReservationContact><![CDATA[' . $ReservationContact . ']]></ReservationContact>
                                                                <EmergencyContactName><![CDATA[' . $EmergencyContactName . ']]></EmergencyContactName>
                                                                <ReservationDeskNumber><![CDATA[' . $ReservationDeskNumber . ']]></ReservationDeskNumber>
                                                                <EmergencyContactNumber><![CDATA[' . $EmergencyContactNumber . ']]></EmergencyContactNumber>
                                                                <GPSPARAM1>' . $GPSPARAM1 . '</GPSPARAM1>
                                                                <GPSPARAM2>' . $GPSPARAM2 . '</GPSPARAM2>
                                                                <TopHotel>' . $TopHotel . '</TopHotel> 
                                                                <PropertyType>' . $PropertyType . '</PropertyType>
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


                $log_call_screen = 'Edit - Hotel';

                $xml_string = Configure::read('travel_start_xml_str') . $content_xml_str . Configure::read('travel_end_xml_str');
                $client = new SoapClient(null, array(
                    'location' => $location_URL,
                    'uri' => '',
                    'trace' => 1,
                ));

                try {
                    $order_return = $client->__doRequest($xml_string, $location_URL, $action_URL, 1);
                    //$xml_arr = Xml::toArray(Xml::build($order_return));
                    $xml_arr = $this->xml2array($order_return);
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

                    $Email->template('XML/xml', 'default')->emailFormat('html')->to($to)->cc($cc)->from('admin@silkrouters.com')->subject('XML Error [' . $log_call_screen . '] Log Id [' . $LogId . '] Open By [' . $this->User->Username($user_id) . '] Date [' . date("m/d/Y H:i:s", $date->format('U')) . ']')->send();
                }

                $this->Session->setFlash($message, 'success');
            }



            $this->redirect(array('action' => 'index'));
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

        $TravelLookupPropertyTypes = $this->TravelLookupPropertyType->find('list', array('fields' => 'id,value', 'order' => 'value ASC'));
        $TravelLookupRateTypes = $this->TravelLookupRateType->find('list', array('fields' => 'id,value', 'order' => 'value ASC'));
        $TravelHotelRoomSuppliers = $this->TravelHotelRoomSupplier->find('all', array('conditions' => array('TravelHotelRoomSupplier.hotel_id' => $id)));
        $this->set(compact('TravelBrands', 'actio_itme_id', 'TravelHotelRoomSuppliers', 'Provinces', 'TravelLookupPropertyTypes', 'TravelLookupRateTypes'));


        $this->request->data = $TravelHotelLookups;
    }


    
    public function ImagefileCheck($file_type = null, $file_size = null) {
        $img_up_type = explode("/", $file_type);
        echo $img_up_type_firstpart = $img_up_type[0];
        if (($img_up_type_firstpart == "image" ) && ($file_size < 3000000)) {
            return 'true';
        } else {
            return 'false';
        }
    }
    
    public function test() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        Configure::write('debug', 2);
        $this->RequestHandler->respondAs('xml');
        App::import('Vendor', 'nusoap', array('file' => 'nusoap' . DS . 'lib' . DS . 'nusoap.php'));

        if (!isset($HTTP_RAW_POST_DATA))
            $HTTP_RAW_POST_DATA = file_get_contents('php://input');

        function hookTextBetweenTags($string, $tagname) {
            $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
            preg_match($pattern, $string, $matches);
            return $matches[1];
        }

        $server = new soap_server();
        $namespace = "http://silkrouters.com/travel_hotel_images/test";
        $endpoint = "http://silkrouters.com/travel_hotel_images/test";
        $server->configureWSDL("web-service", $namespace, $endpoint);
        $server->wsdl->schemaTargetNamespace = $namespace;

        
        $server->register("hello", array("username" => "xsd:string"), array("return" => "xsd:string"), "urn:web-service", "urn:web-service#hello", "rpc", "encoded", "Just say hello");
        $server->register("finish", array("msg" => "xsd:string"), array("return" => "xsd:string"), "urn:web-service", "urn:web-service#hello", "rpc", "encoded", "Just say hello");

        function hello($username) {
            //Can query database and any other complex operation
            mysql_query($username);
            return 'Hiiii-' . $username;
            
        }
        function finish($username) {
            //Can query database and any other complex operation
            mysql_query($username);
            return 'Hiiii-' . $username;
            
        }
        
        

       

        $server->service($HTTP_RAW_POST_DATA);
    }

}