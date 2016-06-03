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
    public function Hello(){
        return 'Hello';
    }
    
    public function Username($user_id) {
        $user = ClassRegistry::init('User')->find('first', array('fields' => array('fname', 'mname', 'lname'), 'conditions' => array('User.id' => $user_id)));
        return $user['User']['fname'] . ' ' . $user['User']['mname'] . ' ' . $user['User']['lname'];
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
  
  
}
