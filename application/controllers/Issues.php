<?php
/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/24/19
 * Time: 3:18 AM
 */

class Issues extends CI_Controller {
    private $production = false;
    private $webURL = 'https://inspection-list.herokuapp.com';

    public function __construct() {
        parent::__construct();
        $this->load->model('issues_model');
        $this->load->helper('url_helper');
        $this->load->helper('download');
        $this->load->helper('file');
        $this->load->library('session');
        if (getenv('PRODUCTION')) {
            $this->production = true;
        }
    }

    private function validate() {
        if (empty($_SESSION['id'])) {
            return false;
        }
        return true;
    }
}