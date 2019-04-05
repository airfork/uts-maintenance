<?php

/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/16/19
 * Time: 3:11 PM
 */
class Buses extends CI_Controller {

    private $production = false;
    private $webURL = 'https://inspection-list.herokuapp.com';

    public function __construct() {
        parent::__construct();
        $this->load->model('bus_model');
        $this->load->model('issues_model');
        $this->load->helper('url_helper');
        $this->load->library('session');
        if (getenv('PRODUCTION')) {
            $this->production = true;
        }
    }

    public function index() {
        $data['buses'] = $this->bus_model->get_buses();
        $this->load->view('buses/index', $data);
    }

    public function view($bus = NULL) {
        $data['bus'] = $this->bus_model->get_buses($bus);
        if ($data['bus']['completed']) {
            if ($this->production) {
                redirect($this->webURL, 'refresh');
                return;
            }
            redirect('/', 'refresh');
            return;
        }
        if(empty($data['bus'])) {
            show_404();
        }
        $this->load->view('buses/view', $data);
    }

    public function issue($bus = NULL) {
        $locations = array(
            'Destination Signs & Emergency Button',
            'Zonar',
            'Stop Request',
            'Radio & PA',
            'Passenger Seats',
            'Emergency Equipment',
            'ADA',
            'Emergency Exits',
            'Auxiliary Fan',
            'Heat/AC',
            'Driver\'s Seat',
            'Mirrors',
            'Defroster',
            'Interior Lighting',
            'Windshield Wipers',
            'Glass Breakage',
            'Bike Racks',
            'Other',
        );
        $data['bus'] = $this->bus_model->get_buses($this->sanitize($bus));
        if(empty($data['bus']) || $data['bus']['completed']) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        global $valid;
        global $name;
        $valid = false;
        foreach ($_POST as $loc => $issue) {
            $loc = str_replace('_', ' ', htmlspecialchars_decode($loc));
            if ($loc === 'name') {
                $valid = true;
                $name = $issue;
            } else if(in_array(htmlspecialchars_decode($loc), $locations) && trim($issue) !== '') {
                if (!$valid) {
                    header('Content-Type: application/json');
                    echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
                    return;
                }
                $rows = $this->issues_model->create($bus, $issue, htmlspecialchars_decode($loc), $name);
                if ($rows === 0) {
                    header('Content-Type: application/json');
                    echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
                    return;
                }
            }
        }
        if (!$valid) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->bus_model->update($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
    }

    public function reset() {
       if (!$this->validate()) {
           echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash(), 'not_signed_in' => true));
           return;
       }
       if (!$this->bus_model->reset()) {
           header('Content-Type: application/json');
           echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
           return;
       }

       if (!$this->issues_model->reset()) {
           header('Content-Type: application/json');
           echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
           return;
       }
       header('Content-Type: application/json');
       echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));

    }

    public function add() {
        $bus = $this->sanitize($_POST['bus']);
        if (empty($bus) || !is_numeric($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Bus number missing or invalid.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $bus = intval($bus);
        if (!empty($this->bus_model->get_buses($bus))) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Bus already exists.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if ($bus < 0 || $bus > 100000) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Invalid number range.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->bus_model->add($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'There was an error of some sort, please try again.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
    }

    private function validate() {
        if (empty($_SESSION['id'])) {
            return false;
        }
        return true;
    }

    private function sanitize($data) {
        return htmlspecialchars(trim(stripslashes($data)));
    }
}