<?php

App::uses('AppController', 'Controller');
class TravelLookUpsController extends AppController{
    var $uses = array('TravelCity','TravelCountry','TravelCitySupplier','TravelCountrySupplier','TravelCurrencyByCountryForSupplier',
                        'TravelCurrencyForSupplier','TravelHotelRoomSupplier','TravelHotelLookup','TravelHotelRoomlookup','TravelNationalityForSupplier',
                        'TravelSupplier'
                      );

    
    public function index(){

         $TravelCities = $this->TravelCity->find('all',array('order' => 'city_name asc'));         
         $this->set('TravelCities', $this->paginate("TravelCity"));
       
       
         $TravelCountries = $this->TravelCountry->find('all',array('order' => 'country_name asc'));
         $this->set(compact('TravelCountries'));
         
         $TravelCitySuppliers = $this->TravelCitySupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelCitySuppliers'));
         
         $TravelCitySuppliers = $this->TravelCitySupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelCitySuppliers'));
         
         $TravelCountrySuppliers = $this->TravelCountrySupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelCountrySuppliers'));
         
         $TravelCurrencyByCountryForSuppliers = $this->TravelCurrencyByCountryForSupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelCurrencyByCountryForSuppliers'));
         
         $TravelCurrencyForSuppliers = $this->TravelCurrencyForSupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelCurrencyForSuppliers'));
         
         $TravelHotelRoomSuppliers = $this->TravelHotelRoomSupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelHotelRoomSuppliers'));
                
        // $TravelHotelRoomlookups = $this->TravelHotelRoomlookup->find('all',array('order' => 'id asc'));
       //  $this->set(compact('TravelHotelRoomlookups'));
         
         $TravelNationalityForSuppliers = $this->TravelNationalityForSupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelNationalityForSuppliers'));
         
         $TravelSuppliers = $this->TravelSupplier->find('all',array('order' => 'id asc'));
         $this->set(compact('TravelSuppliers'));
          
    }
    
  
    
}
?>