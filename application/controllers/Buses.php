<?php

/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/16/19
 * Time: 3:11 PM
 */
class Buses extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('bus_model');
        $this->load->model('issues_model');
        $this->load->helper('url_helper');
        $this->load->library('session');
    }

    public function index() {
        $data['buses'] = $this->bus_model->get_buses();
        $this->load->view('buses/index', $data);
    }

    public function view($bus = NULL) {
        $data['bus'] = $this->bus_model->get_buses($bus);
        if ($data['bus']['completed']) {
            echo '1';
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
        $data['bus'] = $this->bus_model->get_buses($bus);
        if(empty($data['bus']) || $data['bus']['completed']) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        $jData = json_decode(file_get_contents('php://input'), true);
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

       echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));

    }

    private function validate() {
        if (empty($_SESSION['id'])) {
            return false;
        }
        return true;
    }
}