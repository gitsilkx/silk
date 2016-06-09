<?php

/**

 * Messages controller.

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

 * Roles controller

 *

 *

 * @package       app.Controller

 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html

 */
class MessagesController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'error', 'list_message');
    }

    public function index($controller, $action) {


        $this->set('controller', $controller);

        $this->set('action', $action);
    }

    public function error() {
        
    }

    public function list_message() {
        
    }

}
?>

