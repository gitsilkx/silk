<?php

/**
 * Permissions controller.
 *
 * This file will render views from views/roles/
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
 * Permissions controller
 *
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PermissionSetsController extends AppController {

    var $uses = array('PermissionSet', 'Role', 'ControllerSet');
    public $components = array('ControllerList');
	function beforeFilter(){     
             parent::beforeFilter();    
    $this->Auth->allow('index','set_perm','del_perm');
}
	

    public function index() {
		//echo 'asd';
		//die;
      	$this->insert_controllers_actions_into_permission_table();
        $roles = $this->Role->find('all', array('order' => 'id', 'contain' => false, 'recursive' => -1));
        $this->set('roles', $roles);
        $action = $this->ControllerSet->find('all', array('order' => 'id', 'contain' => false, 'recursive' => -1));
        $this->set('actions', $action);
        $permission = $this->PermissionSet->find('all');
        $this->set('permissions', $permission);
    }

    public function set_perm() {
        $this->layout = 'ajax';
        $data = array();
        $id = $this->data['id'];
        $arr = explode('_', $id);
        $controller_id = $arr[0];
        $role_id = $arr[1];

        $data = array('controller_id' => $controller_id, 'role_id' => $role_id);
        if ($this->PermissionSet->save($data)) {
            $name = $this->PermissionSet->getLastInsertId();
        } 
        $this->set('div_id', $id);
        $this->set('name', $name);
    }

    public function del_perm() {

        $this->layout = 'ajax';
        $data = array();
        $id = $this->data['id'];
        $permission_id = $this->data['permission_id'];
        $arr = explode('_', $id);
        $controller_id = $arr[0];
        $role_id = $arr[1];
        $div_id = $controller_id . '_' . $role_id;

        $this->PermissionSet->delete($permission_id);

        $this->set('div_id', $id);
    }
    
    private function insert_controllers_actions_into_permission_table () {
        

        //pr($this->ControllerList->getList(array('AllFunctionsController', 'AdminsController')));//exit;
        //pr($this->ControllerList->getActions('AdminsController'));//exit;
        
        $permission_table = $this->ControllerSet->find('list', array('fields' => array('id', 'action_name', 'controller_name')));
      //  pr($permission_table);
       // die();
        $controllersToExclude = array('PagesController');
        // $controllers = $this->ControllerList->get();
        $controllers = $this->ControllerList->getControllerList();
        //$controler_db = array_diff($controllers, $permission_table);
      //  pr($controllers);
      //  die;
        $save = array();
		
        foreach ($controllers as $controller => $actions) {
		if(count($actions) && !empty($actions)){
            foreach ($actions as $action) {
                if (!isset($permission_table[$controller]) || !in_array($action, $permission_table[$controller])) {
                 
                    $save[] = array('ControllerSet' => array(
                        'slug' => $controller.'_'.$action,
                        'title' => $controller.' '.$action,
                        'controller_name' => $controller,
                        'action_name' => $action     
                    ));
                }                    
            }
        }
		}
        //pr($save);  
        if (!empty($save))
            $this->ControllerSet->saveMany($save);
        
    }

}

?>
