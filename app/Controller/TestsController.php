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
class TestsController extends AppController {

    var $uses = array('Short');
   

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('shorturl', 'convert', 'getShortenedURLFromID','index'));
    }

    public function index() {
        //echo $this->GoogleApi->send('google.com');
       // die;
        $a =  date('m/d/Y H:i:s', strtotime('-1 hour'));
        $date = new DateTime($a, new DateTimeZone('Asia/Kolkata'));
        echo date("m/d/Y H:i:s", $date->format('U'));
        
        //$this->Session->setFlash('Your new client record has been created. Waiting for allocation at the moment.', 'success');
         
        
                    //$this->redirect(array('controller' => 'messages', 'action' => 'index', 'leads', 'my-clients'));
         
    }

    public function shorturl() {
        
        $id = 's/'.$this->params['id'];          
        $this->Short->unbindModel(array('hasMany' => array('Comment')));
        $post = $this->Short->findBySlug($id);
        $long_url = $post['Short']['url'];
        //header('HTTP/1.1 301 Moved Permanently');
        //header('Location: ' .  $long_url);
        //$this->redirect(array('controller' => 'resources', 'action' => 'index'), 301);
        $this->redirect(array($long_url, 301));
 
    }

    public function convert() {
        
        $sort_url = '';
        $search_condition = array();
        
        

        if ($this->request->is('post') || $this->request->is('put')) {
         
            $url_to_shorten = stripslashes(trim($this->request->data['Short']['longurl']));
           
            if (!empty($url_to_shorten) && preg_match('|^https?://|', $url_to_shorten)) {               

                // check if the client IP is allowed to shorten
                if ($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP) {
                    die('You are not allowed to shorten URLs with this service.');
                }

                // check if the URL is valid
                if (CHECK_URL) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url_to_shorten);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $response = curl_exec($ch);
                    $response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($response_status == '404') {
                        die('Not a valid URL');
                    }
                }

                // check if the URL has already been shortened
                $already_shortened = $this->Short->findByUrl($url_to_shorten);
                
                if (!empty($already_shortened)) {
                    // URL has already been shortened
                    $shortened_url = $this->getShortenedURLFromID($already_shortened['Short']['id']);
                    //$shortened_url = $this->getShortenedURLFromID(1);
                } else {
                    // URL not in database, insert
                    $this->request->data['Short']['url'] = $url_to_shorten;
                    $this->request->data['Short']['domain'] = $_SERVER['HTTP_HOST'];
                    $this->Short->create();
                    if ($this->Short->save($this->request->data)) {
                        $insert_id = $this->Short->getLastInsertId();
                        $shortened_url = $this->getShortenedURLFromID($insert_id);
                        $this->Short->updateAll(array('Short.slug' => "'s/" .$shortened_url . "'"), array('Short.id' => $insert_id));
                    }
                }
                $sort_url= BASE_HREF .'s/'. $shortened_url;
                $this->Session->setFlash("Done! You've created your shorten url- ".$sort_url, 'success');
            }
            else{
                $this->Session->setFlash('Unable to shorten that link. It is not a valid url.', 'failure');
            }
        }
        
        $this->paginate['order'] = array('Short.created' => 'desc');
        $this->set('Shorts', $this->paginate("Short", $search_condition));
        
        
        $this->set(compact('sort_url'));
    }

    public function getShortenedURLFromID($integer, $base = ALLOWED_CHARS) {
        $length = strlen($base);
        $base = str_split($base);
        $out = '';
        while ($integer > $length - 1) {
            $out = $base[fmod($integer, $length)] . $out;
            $integer = floor($integer / $length);
        }
        return $base[$integer] . $out;
    }
    
}