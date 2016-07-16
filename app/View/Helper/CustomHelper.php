<?php
/**
 * Custom Helper
 *
 * For custom theme specific methods.
 *
 * If your theme requires custom methods,
 * copy this file to /app/views/themed/your_theme_alias/helpers/custom.php and modify.
 *
 * You can then use this helper from your theme's views using $custom variable.
 *
 * @category Helper
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
 App::uses('Helper', 'View');
 
class CustomHelper extends Helper {

/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */
    public $helpers = array();
    
    public function GetUserStatus($user_id = null)
    {
        $user_status = ClassRegistry::init('User')->find('first', array('conditions'=>array('User.id'=>$user_id),'fields'=>array('User.status')));
  	if($user_status['User']['status'] == 't')
		{ 
			echo "Active"; 
		}else{
			echo "Deactive";
		};
    }
    
     public function alreadyExists($data, $table, $field){
        return ClassRegistry::init($table)->find('count', array('fields' => $table.'.id','conditions' => array($table.'.'.$field => $data)));
    }
    
    public function getSupplierName($supplier_id){
        $DataArray = ClassRegistry::init('TravelSupplier')->find('first', array('fields' => array('supplier_name'), 'conditions' => array('TravelSupplier.id' => $supplier_id)));
        return $DataArray['TravelSupplier']['supplier_name'];
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
    
    public function getSupplierCountryNameByCode($country_code){
        $DataArray = ClassRegistry::init('SupplierCountry')->find('first', array('fields' => array('name'), 'conditions' => array('SupplierCountry.code' => $country_code)));
        return $DataArray['SupplierCountry']['name'];
    }
    
    public function getSupplierCityNameByCode($city_code){
        $DataArray = ClassRegistry::init('SupplierCity')->find('first', array('fields' => array('name'), 'conditions' => array('SupplierCity.code' => $city_code)));
        return $DataArray['SupplierCity']['name'];
    }
    
    public function Hello(){
        return 'Hello';
    }
    
    public function Username($user_id) {
        $user = ClassRegistry::init('User')->find('first', array('fields' => array('fname', 'mname', 'lname'), 'conditions' => array('User.id' => $user_id)));
        return $user['User']['fname'] . ' ' . $user['User']['mname'] . ' ' . $user['User']['lname'];
    }
    
    public function getCountryName($country_id) {
        $DataArray = ClassRegistry::init('TravelCountry')->find('first', array('fields' => array('country_name'), 'conditions' => array('TravelCountry.id' => $country_id)));
        return $DataArray['TravelCountry']['country_name'];
    }
    
    public function getProvinceName($province_id) {
        $DataArray = ClassRegistry::init('Province')->find('first', array('fields' => array('name'), 'conditions' => array('Province.id' => $province_id)));
        return $DataArray['Province']['name'];
    }
    
   
    
    
   public function ConvertGMTToLocalTimezone($gmttime, $timezoneRequired) {
        $system_timezone = date_default_timezone_get();

        date_default_timezone_set("GMT");
        $gmt = date("Y-m-d h:i:s A");

        $local_timezone = $timezoneRequired;
        date_default_timezone_set($local_timezone);
        $local = date("Y-m-d h:i:s A");

        date_default_timezone_set($system_timezone);
        //$diff = (strtotime($local) - strtotime($gmt));
        $diff = 41400;
        $date = new DateTime($gmttime);
        $date->modify("+$diff seconds");
        $timestamp = $date->format("m-d-Y H:i:s");
        return $timestamp;
    }
    
    public function after_last($pattern, $inthat)
    {
        if (!is_bool($this->strrevpos($inthat, $pattern)))
        return substr($inthat, $this->strrevpos($inthat, $pattern)+strlen($pattern));
    }
    public function strrevpos($instr, $needle)
    {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    } 
    
    public function getMissmatchHotelCount($country_id,$city_id){
        ClassRegistry::init('TravelHotelLookup')->find('all', array('fields' => array('id'),'conditions' => array('TravelHotelLookup.country_id' => $country_id)));
        return $DataArray['TravelHotelLookup'];
    }
    
    public function getHotelUnallocatedCnt($country_id,$city_id){
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),'conditions' => array('TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id' => '0')));
        //return $DataArray['TravelHotelLookup'];
    }
    
    public function getHotePendingCnt($country_id,$city_id){
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),'conditions' => array('OR'=> array('chain_id' => '0','brand_id' => '0','suburb_id' => '0'),'TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id !=' => '0')));
        //return $DataArray['TravelHotelLookup'];
    }
    public function getHoteSubmittedCnt($country_id,$city_id){
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),'conditions' => array('TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id !=' => '0',
             'TravelHotelLookup.suburb_id !=' => '0','TravelHotelLookup.area_id !=' => '0','TravelHotelLookup.chain_id !=' => '0','TravelHotelLookup.brand_id !=' => '0','TravelHotelLookup.status' => '4')));
     
    }
    
    public function getHoteApprovedCnt($country_id,$city_id){
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),'conditions' => array('OR' => array('TravelHotelLookup.status' => array('2','8')),'TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id !=' => '0',
             'TravelHotelLookup.suburb_id !=' => '0','TravelHotelLookup.area_id !=' => '0','TravelHotelLookup.chain_id !=' => '0','TravelHotelLookup.brand_id !=' => '0')));
     
    }
    
    public function getHoteTotalCnt($country_id,$city_id){
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),'conditions' => array('TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id)));
     
    }
    
    public function getMappingPendingCnt($country_id,$city_id,$supplier_id){
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),
            'joins' => array(
                    array(
                        'table' => 'travel_hotel_room_suppliers',
                        'alias' => 'TravelHotelRoomSupplier',
                        'type'  => 'LEFT',
                        'foreignKey'    => false,
                        'conditions'    => array('TravelHotelLookup.id = TravelHotelRoomSupplier.hotel_id','TravelHotelRoomSupplier.hotel_supplier_status NOT' => array('1','2','7')),
                        ),
                )                   
            ,'conditions' => array('OR' => array('TravelHotelLookup.status' => array('2','8')),'TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id !=' => '0',
             'TravelHotelLookup.suburb_id !=' => '0','TravelHotelLookup.area_id !=' => '0','TravelHotelLookup.chain_id !=' => '0','TravelHotelLookup.brand_id !=' => '0')));
     
    }
    
    public function getMappingSubmitCnt($country_id,$city_id,$supplier_id){
        
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),
            'joins' => array(
                    array(
                        'table' => 'travel_hotel_room_suppliers',
                        'alias' => 'TravelHotelRoomSupplier',
                        'type'  => 'INNER',
                        'foreignKey'    => false,
                        'conditions'    => array('TravelHotelLookup.id = TravelHotelRoomSupplier.hotel_id','TravelHotelRoomSupplier.hotel_supplier_status' => '1'),
                        ),
                )                   
            ,'conditions' => array('OR' => array('TravelHotelLookup.status' => array('2','8')),'TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id !=' => '0',
             'TravelHotelLookup.suburb_id !=' => '0','TravelHotelLookup.area_id !=' => '0','TravelHotelLookup.chain_id !=' => '0','TravelHotelLookup.brand_id !=' => '0')));
     
     
    }
    
    public function getMappingApproveCnt($country_id,$city_id,$supplier_id){
        
        return ClassRegistry::init('TravelHotelLookup')->find('count', array('fields' => array('id'),
            'joins' => array(
                    array(
                        'table' => 'travel_hotel_room_suppliers',
                        'alias' => 'TravelHotelRoomSupplier',
                        'type'  => 'INNER',
                        'foreignKey'    => false,
                        'conditions'    => array('TravelHotelLookup.id = TravelHotelRoomSupplier.hotel_id','TravelHotelRoomSupplier.hotel_supplier_status' => array('2','7')),
                        ),
                )                   
            ,'conditions' => array('OR' => array('TravelHotelLookup.status' => array('2','8')),'TravelHotelLookup.country_id' => $country_id,'TravelHotelLookup.city_id' => $city_id,'TravelHotelLookup.province_id !=' => '0',
             'TravelHotelLookup.suburb_id !=' => '0','TravelHotelLookup.area_id !=' => '0','TravelHotelLookup.chain_id !=' => '0','TravelHotelLookup.brand_id !=' => '0')));
     
     
    }
  
}
