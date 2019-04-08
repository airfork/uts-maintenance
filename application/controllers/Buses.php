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

    // Add issues to db
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
        // Make sure bus exists and has not already been completed
        $data['bus'] = $this->bus_model->get_buses($this->sanitize($bus));
        if(empty($data['bus']) || $data['bus']['completed']) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Make global because scoping is weird in PHP
        global $valid;
        global $name;
        $valid = false;
        // Iterate over items in POST
        foreach ($_POST as $loc => $issue) {
            // Replace any underscores put in with spaces
            $loc = str_replace('_', ' ', htmlspecialchars_decode($loc));
            // Check for name field, needs to be first
            if ($loc === 'name') {
                $valid = true;
                $name = $issue;

                // Check that location exits and that the issue is not whitespace
            } else if(in_array(htmlspecialchars_decode($loc), $locations) && trim($issue) !== '') {
                // If name is not set, exit
                if (!$valid) {
                    header('Content-Type: application/json');
                    echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
                    return;
                }
                // Create issue in db
                $rows = $this->issues_model->create($bus, $issue, htmlspecialchars_decode($loc), $name);
                // Sanity check, if no rows updated, exit
                if ($rows === 0) {
                    header('Content-Type: application/json');
                    echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
                    return;
                }
            }
        }
        // If name not set (there are no issues so loop not entered), exit
        if (!$valid) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Mark bus as done
        if (!$this->bus_model->update($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Success
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
    }

    // Resets buses to being uncompleted
    public function reset() {
        // Make sure user is signed in
       if (!$this->validate()) {
           echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash(), 'not_signed_in' => true));
           return;
       }
       // Reset buses, respond if error
       if (!$this->bus_model->reset()) {
           header('Content-Type: application/json');
           echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
           return;
       }
        // Ignore all issues in db, respond if error
       if (!$this->issues_model->reset()) {
           header('Content-Type: application/json');
           echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
           return;
       }
       // Respond on success
       header('Content-Type: application/json');
       echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));

    }

    // add bus to db
    public function add() {
        // Sanitize data
        $bus = $this->sanitize($_POST['bus']);
        // Make sure input is a number
        if (empty($bus) || !is_numeric($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Bus number missing or invalid.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Convert to number
        $bus = intval($bus);
        // Make sure bus does not already exist
        if (!empty($this->bus_model->get_buses($bus))) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Bus already exists.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Make sure bus number is in range
        if ($bus < 0 || $bus > 100000) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Invalid number range.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Add bus, return error message if this fails for some reason
        if (!$this->bus_model->add($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'There was an error of some sort, please try again.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Regular exit, everything went well
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
    }

    public function delete() {
        // Sanitize input
        $bus = $this->sanitize($_POST['bus']);
        // Make sure input is a number
        if (empty($bus) || !is_numeric($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Bus number missing or invalid.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        // Convert to number
        $bus = intval($bus);
        // Make sure bus exists
        if (empty($this->bus_model->get_buses($bus))) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Bus does not exist.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->bus_model->delete($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'There was an error of some sort, please try again.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->issues_model->delete($bus)) {
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