<?php
App::uses('AppModel', 'Model');
	
class Common extends AppModel {

   var $useTable = false;

   public function getContinentCode($continent_id){
        $DataArray = ClassRegistry::init('TravelLookupContinent')->find('first', array('fields' => array('continent_code'), 'conditions' => array('TravelLookupContinent.id' => $continent_id)));
        return $DataArray['TravelLookupContinent']['continent_code'];
    }
    
    public function getContinentName($continent_id){
        $DataArray = ClassRegistry::init('TravelLookupContinent')->find('first', array('fields' => array('continent_name'), 'conditions' => array('TravelLookupContinent.id' => $continent_id)));
        return $DataArray['TravelLookupContinent']['continent_name'];
    }
    
   public function getCountryCode($country_id){
        $DataArray = ClassRegistry::init('TravelCountry')->find('first', array('fields' => array('country_code'), 'conditions' => array('TravelCountry.id' => $country_id)));
        return $DataArray['TravelCountry']['country_code'];
    }
    
    public function getCountryName($country_id){
        $DataArray = ClassRegistry::init('TravelCountry')->find('first', array('fields' => array('country_name'), 'conditions' => array('TravelCountry.id' => $country_id)));
        return $DataArray['TravelCountry']['country_name'];
    }
    
    public function getProvinceName($province_id){
        $DataArray = ClassRegistry::init('Province')->find('first', array('fields' => array('name'), 'conditions' => array('Province.id' => $province_id)));
        return $DataArray['Province']['name'];
    }
    
    public function getCityName($city_id){
        $DataArray = ClassRegistry::init('TravelCity')->find('first', array('fields' => array('city_name'), 'conditions' => array('TravelCity.id' => $city_id)));
        return $DataArray['TravelCity']['city_name'];
    }
    
    public function getSuburbName($suburb_id){
        $DataArray = ClassRegistry::init('TravelSuburb')->find('first', array('fields' => array('name'), 'conditions' => array('TravelSuburb.id' => $suburb_id)));
        return $DataArray['TravelSuburb']['name'];
    }
    
    public function getSupplierCountryCode($country_id){
        $DataArray = ClassRegistry::init('SupplierCountry')->find('first', array('fields' => array('code'), 'conditions' => array('SupplierCountry.id' => $country_id)));
        return $DataArray['SupplierCountry']['code'];
    }
    
    public function getSupplierCityCode($city_id){
        $DataArray = ClassRegistry::init('SupplierCity')->find('first', array('fields' => array('code'), 'conditions' => array('SupplierCity.id' => $city_id)));
        return $DataArray['SupplierCity']['code'];
    }
    
    public function getSupplierCode($supplier_id){
        $DataArray = ClassRegistry::init('TravelSupplier')->find('first', array('fields' => array('supplier_code'), 'conditions' => array('TravelSupplier.id' => $supplier_id)));
        return $DataArray['TravelSupplier']['supplier_code'];
    }
    
    public function getSupplierCountryName($country_id){
        $DataArray = ClassRegistry::init('SupplierCountry')->find('first', array('fields' => array('name'), 'conditions' => array('SupplierCountry.id' => $country_id)));
        return $DataArray['SupplierCountry']['name'];
    }
    
    public function getSupplierCityName($city_id){
        $DataArray = ClassRegistry::init('SupplierCity')->find('first', array('fields' => array('name'), 'conditions' => array('SupplierCity.id' => $city_id)));
        return $DataArray['SupplierCity']['name'];
    }
    
    public function getSupplierName($supplier_id){
        $DataArray = ClassRegistry::init('TravelSupplier')->find('first', array('fields' => array('supplier_name'), 'conditions' => array('TravelSupplier.id' => $supplier_id)));
        return $DataArray['TravelSupplier']['supplier_name'];
    }
}
?>