<?php

use phpDocumentor\Reflection\Types\Boolean;

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
        $this->load->model('contacts_model');
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->library('email');
        if (getenv('PRODUCTION')) {
            $this->production = true;
        }
    }

    public function index() {
        $data['buses'] = $this->bus_model->get_buses();
        $data['signedIn'] = $this->validate();
        $this->email_issues();
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
        if (!$this->bus_model->update($bus, $this->sanitize($name))) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
		$this->issue_sheet($bus);
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
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
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
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
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
        if (!$this->issues_model->delete($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Failed to delete issues related to bus.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        if (!$this->bus_model->delete($bus)) {
            header('Content-Type: application/json');
            echo json_encode(array('valid' => false, 'error' => 'Failed to delete bus from database.' ,'csrf_token' => $this->security->get_csrf_hash()));
            return;
        }
        header('Content-Type: application/json');
        echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
    }

    public function completed() {
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
        $data['buses'] = $this->bus_model->get_completed_buses();
        $this->load->view('buses/completed', $data);
    }

    public function issue_list($bus=null) {
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
		$busInfo = $this->bus_model->get_buses($bus);
		if (!$busInfo['completed']) {
			$this->redirectHome();
			return;
		}
		$data['busNumber'] = $bus;
		$data['issues'] = $this->issues_model->current_issues($bus);
		$this->load->view('buses/issueList', $data);
	}

	public function resolve_issue($issue) {
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
    	$found = $this->issues_model->get_issue($issue);
    	if (empty($found)) {
			header('Content-Type: application/json');
			echo json_encode(array('valid' => false, 'error' => 'Invalid issue, please refresh and try again.'));
			return;
		}
    	$this->issues_model->resolve($issue);

		header('Content-Type: application/json');
		echo json_encode(array('valid' => true));
	}

	private function issue_sheet($busNumber) : bool {
    	$issues = $this->issues_model->current_issues($busNumber);
    	if (empty($issues)) {
    		return false;
		}
		$writer = new XLSXWriter();
		$evenStyle = array('wrap-text'=>true, 'font'=>'Arial','font-size'=>11, 'fill'=>'#ccc', 'border'=>'left,right', 'border-style' => 'thin', 'halign' => 'center');
		$oddStyle = array('wrap-text'=>true, 'font'=>'Arial','font-size'=>11, 'border'=>'left,right', 'border-style' => 'thin', 'halign' => 'center');

		$count = 0;
		// Write sheet header to format columns
		$writer->writeSheetHeader('Bus '.$busNumber, array('Location' => 'string', 'Details' => 'string', 'Date Reported' => 'MM/DD/YYYY'), $col_options = ['widths'=>[40,50,19], 'halign' => 'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin', 'font-style' => 'bold']);
		foreach ($issues as $issue) {
			$style = ($count++ % 2 == 0 ? $evenStyle : $oddStyle);
			$location = ($issue['location'] == 'Destination Signs & Emergency Button' ? 'Dest. Signs' : $issue['location']);
			$writer->writeSheetRow('Bus '.$busNumber, array($location, $issue['description'], $issue['createdat']), $style);
		}
		// Save excel file
		$writer->writeToFile('spreadsheets/'.$busNumber.'.xlsx');
		return true;
	}

	private function email_issues() {
		$this->load->config('email');
		$emails = $this->contacts_model->get_contacts(false);
		$this->email->from($this->config->item('smtp_user'), "Tunji Afolabi-Brown");
		$this->email->to($this->email_arr_to_string($emails));
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');

		if ($this->email->send()) {
			echo 'Your Email has successfully been sent.';
		} else {
			show_error($this->email->print_debugger());
		}
	}

	private function email_arr_to_string($emails) {
    	$output = "";
    	foreach ($emails as $address) {
    		$output .= $address['email'].',';
		}
    	return trim($output, ',');
	}

    private function validate(): bool {
        if (empty($_SESSION['id'])) {
            return false;
        }
        return true;
    }

    private function redirectHome() {
		if ($this->production) {
			redirect($this->webURL, 'refresh');
			return;
		}
		redirect('/', 'refresh');
	}

    private function sanitize($data) {
        return htmlspecialchars(trim(stripslashes($data)));
    }
}
