<?php

/**
 * Test controller.
 *
 * This file will render views from views/cities/
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

/**
 * City controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class SupportTicketsController extends AppController {

    var $uses = array('SupportTicket','LookupDepartment','LookupQuestion','LookupScreen','TravelHotelLookup','LookupTicketUrgency',
        'TravelLookupContinent','TravelCountry','Province','TravelCity','TravelSuburb','LookupTicketSolution','TravelArea','LookupResponseIssue',
        'TravelChain','LookupTicketStatus','User');
    public $components = array('Image');
   
    public function beforeFilter() {
        parent::beforeFilter();
        $this->uploadDir = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . '/uploads/SpportTicket';
    }

    public function index() {
        
        $user_id = $this->Auth->user('id');
        $search_condition = array();
        $conArray = array();
        
        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->request);
            //die;
            if (!empty($this->data['SupportTicket']['about'])) {
                $about = $this->data['SupportTicket']['about'];
                array_push($search_condition, array('SupportTicket.about' . ' LIKE' => $about));
            }
            
            if (!empty($this->data['SupportTicket']['created_by'])) {
                $created_by = $this->data['SupportTicket']['created_by'];
                array_push($search_condition, array('SupportTicket.created_by' => $created_by));
            }
            
            if (!empty($this->data['SupportTicket']['screen'])) {
                $screen = $this->data['SupportTicket']['screen'];
                array_push($search_condition, array('SupportTicket.screen' => $screen));
            }
            
            if (!empty($this->data['SupportTicket']['answer'])) {
                $answer = $this->data['SupportTicket']['answer'];
                array_push($search_condition, array('SupportTicket.answer' => $answer));
            }
            
            if (!empty($this->data['SupportTicket']['urgency'])) {
                $urgency = $this->data['SupportTicket']['urgency'];
                array_push($search_condition, array('SupportTicket.urgency' => $urgency));
            }
            
            if (!empty($this->data['SupportTicket']['status'])) {
                $status = $this->data['SupportTicket']['status'];
                array_push($search_condition, array('SupportTicket.status' => $status));
            }
            
            
            
        }
        
        array_push($search_condition, array('OR' => array('SupportTicket.created_by' => $user_id,'SupportTicket.next_action_by' => $user_id,'SupportTicket.approved_by' => $user_id,'SupportTicket.last_action_by' => $user_id),'SupportTicket.active' => 'TRUE'));
        $this->paginate['order'] = array('SupportTicket.created' => 'desc');
        $this->set('SupportTickets', $this->paginate("SupportTicket", $search_condition));
        
        $LookupScreen = $this->LookupScreen->find('list', array('fields' => 'id, value', 'order' => 'value ASC'));
        $LookupQuestion = $this->LookupQuestion->find('list', array('fields' => 'LookupQuestion.id, LookupQuestion.question','conditions' => array('LookupQuestion.parent_id' => null), 'order' => 'LookupQuestion.id ASC'));
        //$CommonQuestion = $this->LookupQuestion->find('list', array('fields' => 'LookupQuestion.id, LookupQuestion.question','conditions' => array('LookupQuestion.id' => array('7','8')), 'order' => 'LookupQuestion.id ASC'));
        $LookupTicketUrgency = $this->LookupTicketUrgency->find('list', array('fields' => 'LookupTicketUrgency.id, LookupTicketUrgency.value', 'order' => 'LookupTicketUrgency.value ASC'));
        $LookupTicketStatus = $this->LookupTicketStatus->find('list', array('fields' => 'LookupTicketStatus.id, LookupTicketStatus.value', 'order' => 'LookupTicketStatus.value ASC'));
        
        if($user_id == '169'){ // overseer apac
            $conArray = array('OR' =>  ARRAY('User.travel_role_infra_mapping != ""','User.t_sales_role_id != ""'));
        }
        else{
            $conArray = array('User.id' => $user_id);
        }
        
        $users = $this->User->find('all', array('fields' => array('User.id', 'User.fname', 'User.lname'),
            'conditions' => $conArray, 'order' => 'User.fname asc'));
        $users = Set::combine($users, '{n}.User.id', array('%s %s', '{n}.User.fname', '{n}.User.lname'));
        
        $this->set(compact('LookupScreen','LookupQuestion','LookupTicketUrgency','users','LookupTicketStatus'));
         
    }
    
    public function add($screen = null,$action_id = null) {
        $this->layout = '';
        $continent_id = '';
        $country_id = '';
        $province_id = '';
        $city_id = '';
        $suburb_id = '';
        $chain_id = '';
        $brand_id = '';
        $hotel_id = '';
        $user_id = $this->Auth->user('id');
        $upload_picture = '';
        
        $LookupQuestion = array();
        $TravelHotelLookup = array();
        if($screen == '1'){ // Hotel Edit
           $TravelHotelLookup = $this->TravelHotelLookup->find('all',array('fields' => array('TravelHotelLookup.id','TravelHotelLookup.hotel_name','TravelHotelLookup.hotel_code'),'conditions' => array('TravelHotelLookup.id' => $action_id)));
           
           $TravelHotelLookup = Set::combine($TravelHotelLookup, '{n}.TravelHotelLookup.id', array('%s | %s | %s', '{n}.TravelHotelLookup.hotel_name','{n}.TravelHotelLookup.hotel_code', '{n}.TravelHotelLookup.id'));
           $AllHotel = $this->TravelHotelLookup->find('first',array('conditions' => array('TravelHotelLookup.id' => $action_id)));
 
           $continent_id = $AllHotel['TravelHotelLookup']['continent_id'];
           $country_id = $AllHotel['TravelHotelLookup']['country_id'];
           $province_id = $AllHotel['TravelHotelLookup']['province_id'];
           $city_id = $AllHotel['TravelHotelLookup']['city_id'];
           $suburb_id = $AllHotel['TravelHotelLookup']['suburb_id'];
           $chain_id = $AllHotel['TravelHotelLookup']['chain_id'];
           $brand_id = $AllHotel['TravelHotelLookup']['brand_id'];
           $hotel_id = $AllHotel['TravelHotelLookup']['id'];
        }
        
        if ($this->request->is('post')) {
            //pr($this->data);
            //$question_id = $this->data['SupportTicket']['question_id'];
            $answer = $this->data['SupportTicket']['answer'];
            $about = $this->data['SupportTicket']['about'];
            $question_id1 = $this->data['SupportTicket']['question_id1'];
            $answer1 = $this->data['SupportTicket']['answer1'];
            $question_id2 = $this->data['SupportTicket']['question_id2'];
            $answer2 = $this->data['SupportTicket']['answer2'];
            
            $this->request->data['SupportTicket']['status'] = '1'; // 1 = open
            $this->request->data['SupportTicket']['opend_by'] = 'SENDER';
            $this->request->data['SupportTicket']['active'] = 'TRUE';
            $this->request->data['SupportTicket']['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->request->data['SupportTicket']['question_id'] = 'What is the issue?';
            //$this->request->data['SupportTicket']['answer'] = $this->SupportTicket->getAnswerByAnswerId($answer);
            
            $department_id = $this->SupportTicket->getDepartmentByQuestionId($answer);
            $this->request->data['SupportTicket']['next_action_by'] = $this->SupportTicket->getNextActionByDepartmentId($department_id);
            $this->request->data['SupportTicket']['department_id'] = $department_id;
            $this->request->data['SupportTicket']['type'] = '1'; // Internal
            $this->request->data['SupportTicket']['created_by'] = $user_id;
            $this->request->data['SupportTicket']['last_action_by'] = $user_id;
            $this->request->data['SupportTicket']['screen'] = $screen;
            $this->request->data['SupportTicket']['response_issue_id'] = $answer;
            
            
            if (is_uploaded_file($this->request->data['SupportTicket']['file']['tmp_name'])) {
                $upload_picture = $this->Image->upload(null, $this->request->data['SupportTicket']['file'], $this->uploadDir, 'ticket');
                $this->request->data['SupportTicket']['file'] = $upload_picture;
            } else {
                unset($this->request->data['SupportTicket']['file']);
            }
            /*
            if (!empty($this->data['SupportTicket']['file']['name'])) {
                $file = $this->data['SupportTicket']['file'];
                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/SpportTicket/' . $file['name']);  
                $this->request->data['SupportTicket']['file'] = $file['name'];
            }
             * 
             */
            
            if($screen == '1'){ // Hotel Edit
                $this->request->data['SupportTicket']['about'] = $this->SupportTicket->getHotelNameByHotelId($about);
            }
            if($answer == '1' || $answer == '2'){
                $this->request->data['SupportTicket']['answer2'] = $this->SupportTicket->getCountryNameByCountryId($answer2);
                $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getContinentNameByContinentId($answer1);
                
            }
            elseif($answer == '3'){ // Province Incorrect
                $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getCountryNameByCountryId($answer1);
                $this->request->data['SupportTicket']['answer2'] = $this->SupportTicket->getProvinceByProvinceId($answer2);
            }
            elseif($answer == '4'){ // City Incorrect               
                $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getProvinceByProvinceId($answer1);
                 $this->request->data['SupportTicket']['answer2'] = $this->SupportTicket->getCityByCityId($answer2);
            }
            elseif($answer == '5'){ // Suburb Incorrect               
                
                 $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getCityByCityId($answer1);
            }
            elseif($answer == '6'){ // Area Incorrect               
                
                 $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getSuburbBySuburbId($answer1);
            }
            elseif($answer == '27'){ // Duplicate Hotel               
                
                 $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getHotelNameByHotelId($answer1);
            }
            elseif($answer == '30'){ // Brand Missing               
                
                 $this->request->data['SupportTicket']['answer1'] = $this->SupportTicket->getChainId($answer1);
            }
            
            
           
            if ($this->SupportTicket->save($this->request->data)) {
                $this->Session->setFlash('Your ticket has been successfully created.', 'success');
            }
            
             echo '<script>
				 			var objP=parent.document.getElementsByClassName("mfp-bg");
							var objC=parent.document.getElementsByClassName("mfp-wrap");
							objP[0].style.display="none";
							objC[0].style.display="none";
							parent.location.reload(true);</script>';
            
        }
        
        //pr($TravelHotelLookup);
        
        $LookupScreen = $this->LookupScreen->find('list', array('fields' => 'id, value','conditions' => array('id' => $screen), 'order' => 'value ASC'));
        $LookupQuestion = $this->LookupQuestion->find('list', array('fields' => 'LookupQuestion.id, LookupQuestion.question','conditions' => array('LookupQuestion.screen' => $screen,'LookupQuestion.parent_id' => null), 'order' => 'LookupQuestion.id ASC'));
        $CommonQuestion = $this->LookupQuestion->find('list', array('fields' => 'LookupQuestion.id, LookupQuestion.question','conditions' => array('LookupQuestion.id' => array('7','8')), 'order' => 'LookupQuestion.id ASC'));
        $LookupTicketUrgency = $this->LookupTicketUrgency->find('list', array('fields' => 'LookupTicketUrgency.id, LookupTicketUrgency.value', 'order' => 'LookupTicketUrgency.value ASC'));
        $LookupQuestion =  $LookupQuestion + $CommonQuestion;    
       $this->set(compact('LookupDepartment','LookupQuestion','LookupScreen','TravelHotelLookup','LookupTicketUrgency','continent_id','country_id','province_id','city_id','suburb_id','chain_id','brand_id','hotel_id'));
         
    }
    
    public function view($id = null) {
        //$this->layout = '';
        if (!$id) {
            throw new NotFoundException(__('Invalid Ticket'));
        }

        $SupportTickets = $this->SupportTicket->findById($id);


        if (!$SupportTickets) {
            throw new NotFoundException(__('Invalid Ticket'));
        }
        $this->request->data = $SupportTickets;
    }
    
   public function response($id = null){
       
       $this->layout ='';
       $DataArray = array();
        $country_con = array();
        $province_con = array();
        $city_con = array();
        $suburb_con = array();
        $area_con = array();
        $chain_con = array();
        $user_id = $this->Auth->user('id');
        
       if (!$id) {
            throw new NotFoundException(__('Invalid Ticket'));
        }

        $SupportTickets = $this->SupportTicket->findById($id);
        $answer = $SupportTickets['SupportTicket']['answer'];


        if (!$SupportTickets) {
            throw new NotFoundException(__('Invalid Ticket'));
        }
        
 
        if($answer == '3'){ //province
            $country_name = $SupportTickets['SupportTicket']['answer1'];
            $country_con = array('country_name' => $country_name);
        }
        elseif($answer == '4'){
            $name = $SupportTickets['SupportTicket']['answer1'];
            $province_con = array('name' => $name);
        }
        elseif($answer == '5'){
            $city_name = $SupportTickets['SupportTicket']['answer1'];
            $city_con = array('city_name' => $city_name);
        }
        elseif($answer == '6'){
            $suburb_name = $SupportTickets['SupportTicket']['answer1'];
            $suburb_con = array('name' => $suburb_name);
            $area_con = array('suburb_name' => $suburb_name);
        }
        elseif($answer == '30'){
            $chain_name = $SupportTickets['SupportTicket']['answer1'];
            $chain_con = array('chain_name' => $chain_name);
            
        }

   
        
        if ($answer) {
           $DataArray = $this->LookupQuestion->find('all', array('fields' => 'LookupQuestion.id, LookupQuestion.question','conditions' => array('LookupQuestion.parent_id' => $answer), 'order' => 'LookupQuestion.id ASC'));
      
        }
  
        
        if ($this->request->is('post') || $this->request->is('put')) {
            
            $upload_picture = '';
            $res = '';
            
            if (is_uploaded_file($this->request->data['SupportTicket']['response_file']['tmp_name'])) {
                $upload_picture = $this->Image->upload(null, $this->request->data['SupportTicket']['response_file'], $this->uploadDir, 'response');
                $this->request->data['SupportTicket']['response_file'] = $upload_picture;
            } else {
                unset($this->request->data['SupportTicket']['response_file']);
            }
            
            if (!empty($this->data['SupportTicket']['continent'])) {
                $continent = $this->data['SupportTicket']['continent'];
                $res .= $this->SupportTicket->getContinentNameByContinentId($continent).' -> ';
            }
            if (!empty($this->data['SupportTicket']['country'])) {
                $country = $this->data['SupportTicket']['country'];
                $res .= $this->SupportTicket->getCountryNameByCountryId($country).' -> ';
            }
            if (!empty($this->data['SupportTicket']['province'])) {
                $province = $this->data['SupportTicket']['province'];
                $res .= $this->SupportTicket->getProvinceByProvinceId($province).' -> ';
            }
            if (!empty($this->data['SupportTicket']['city'])) {
                $city = $this->data['SupportTicket']['city'];
                $res .= $this->SupportTicket->getCityByCityId($city).' -> ';
            }     
            
            if (!empty($this->data['SupportTicket']['suburb'])) {
                $suburb = $this->data['SupportTicket']['suburb'];
                $res .= $this->SupportTicket->getSuburbBySuburbId($suburb).' -> ';
            }
            
            if (!empty($this->data['SupportTicket']['area'])) {
                $area = $this->data['SupportTicket']['area'];
                $res .= $this->SupportTicket->getAreaByAreaId($area);
            }
            
            $response1 = $this->data['SupportTicket']['response1'];
            $response2 = $this->data['SupportTicket']['response2'];
            

            if($answer == '7'){ // Technical Issue    
                
               $res = $this->SupportTicket->getAnswerByAnswerId($response1);
            }
            elseif($answer == '29' || $answer == '30'){
                if (!empty($this->data['SupportTicket']['chain'])) {
                    $chain = $this->data['SupportTicket']['chain'];
                    $res = $this->SupportTicket->getChainId($chain).' -> ';
                }
                if (!empty($this->data['SupportTicket']['brand'])) {
                    $brand = $this->data['SupportTicket']['brand'];
                    $res .= $this->SupportTicket->getBrandId($brand);
                }
            }
            
            
            
            
            $this->request->data['SupportTicket']['response1'] = $res;
            $this->request->data['SupportTicket']['opend_by'] = 'RECEIVER';
            $this->request->data['SupportTicket']['response_ip'] = $_SERVER['REMOTE_ADDR'];
            $this->request->data['SupportTicket']['status'] = '2'; // 2 = RESOLVED
            $this->request->data['SupportTicket']['last_action_by'] = $user_id;
            $this->request->data['SupportTicket']['next_action_by'] = $SupportTickets['SupportTicket']['created_by'];
            $this->request->data['SupportTicket']['approved_by'] = $user_id;
            
            //pr($this->request->data);
            //die;
            $this->SupportTicket->id = $id;
                if ($this->SupportTicket->save($this->request->data)) {
                    $this->Session->setFlash('Your response has been successfully submitted.', 'success');
                }
                
             echo '<script>
				 			var objP=parent.document.getElementsByClassName("mfp-bg");
							var objC=parent.document.getElementsByClassName("mfp-wrap");
							objP[0].style.display="none";
							objC[0].style.display="none";
							parent.location.reload(true);</script>';   
        }
        
        $TravelLookupContinent = $this->TravelLookupContinent->find('list', array('fields' => 'id,continent_name', 'conditions' => array('continent_status' => 1, 'wtb_status' => 1), 'order' => 'continent_name ASC'));
      $TravelCountries = $this->TravelCountry->find('list', array('fields' => 'id, country_name',
          'conditions' => array(
                        $country_con), 'order' => 'country_name ASC'));
      
      $Provinces = $this->Province->find('list', array(
                'conditions' => array(                   
                    
                    $province_con,
                ),
                'fields' => array('Province.id', 'Province.name'),
                'order' => 'Province.name ASC'
            ));
      
      $TravelCities = $this->TravelCity->find('list', array(
                'conditions' => array(
             
                    
                    $city_con
                ),
                'fields' => array('TravelCity.id', 'TravelCity.city_name'),
                'order' => 'TravelCity.city_name ASC'
            ));
      
      $TravelSuburbs = $this->TravelSuburb->find('list', array(
                'conditions' => array(
                                   
                    
                    $suburb_con
                ),
                'fields' => 'TravelSuburb.id, TravelSuburb.name',
                'order' => 'TravelSuburb.name ASC'
            ));
      
   $TravelAreas = $this->TravelArea->find('list', array(
                'conditions' => array(                    
                    
                    $area_con
                ),
                'fields' => 'TravelArea.id, TravelArea.area_name',
                'order' => 'TravelArea.area_name ASC'
            ));   
   
   $TravelChains = $this->TravelChain->find('list', array(
                'conditions' => array(
                                   
                    
                    $chain_con
                ),
                'fields' => 'TravelChain.id, TravelChain.chain_name',
                'order' => 'TravelChain.chain_name ASC'
            ));
   
   
      
      $TechnicalIssue = $this->LookupQuestion->find('list', array(
                'conditions' => array('LookupQuestion.parent_id' => 21),
                'fields' => 'LookupQuestion.question, LookupQuestion.question',
                'order' => 'LookupQuestion.id ASC'
            ));
      
      $solution = $this->LookupTicketSolution->find('list', array(
                //'conditions' => array('LookupTicketSolution.question_id' => array($SupportTickets['SupportTicket']['answer'],0)),
                'fields' => 'LookupTicketSolution.id, LookupTicketSolution.value',
                'order' => 'LookupTicketSolution.value ASC'
            ));
      
      $LookupResponseIssues = $this->LookupResponseIssue->find('list', array(
                'conditions' => array(                    
                    //'LookupResponseIssue.question_id' => array(0,$answer),                                    
                ),
                'fields' => 'LookupResponseIssue.id, LookupResponseIssue.value',
                'order' => 'LookupResponseIssue.value ASC'
            ));
      
      
        
        $this->set(compact('DataArray','TravelChains','solution','TravelAreas','LookupResponseIssues','TravelLookupContinent','TravelCountries','Provinces','TravelCities','TravelSuburbs','TechnicalIssue'));
        
        
        $this->request->data = $SupportTickets;
   } 
   public function close($id = null){
       
       $user_id = $this->Auth->user('id');
       if (!$id) {
            throw new NotFoundException(__('Invalid Ticket'));
        }
        
        $this->request->data['SupportTicket']['status'] = '3'; // 3 = CLOSED
        $this->request->data['SupportTicket']['next_action_by'] = NULL; 
        $this->request->data['SupportTicket']['last_action_by'] = $user_id;
        $this->SupportTicket->id = $id;
                if ($this->SupportTicket->save($this->request->data)) {
                    $this->Session->setFlash('Your response has been successfully submitted.', 'success');
                    $this->redirect(array('action' => 'index'));
                }
                else {
                $this->Session->setFlash('Unable to close ticket.', 'failure');
            }
   }
}