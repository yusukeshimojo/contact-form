<?php

class ContactFormApplication extends Application {
    protected $login_action = array();

    public function getRootDir() {
        return dirname(__FILE__);
    }

    protected function registerRoutes() {
        return array(
            '/'
                => array('controller' => 'form', 'action' => 'index'),
            '/form/index'
                => array('controller' => 'form', 'action' => 'index'),
            '/form/confirm'
                => array('controller' => 'form', 'action' => 'confirm'),
            '/form/complete'
                => array('controller' => 'form', 'action' => 'complete'),
        );
    }

    protected function configure() {
        $this->db_manager->connect('master', array(
            'dsn'      => 'mysql:dbname=contact_form;host=localhost;charset=utf8',
            'user'     => 'root',
            'password' => '',
        ));
    }
}
