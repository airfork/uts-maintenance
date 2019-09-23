<?php

class Contacts extends CI_Controller {

	private $production = false;
	private $webURL = 'https://inspection-list.herokuapp.com';

	public function __construct() {
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->library('session');
		$this->load->model('contacts_model');
		if (getenv('PRODUCTION')) {
			$this->production = true;
		}
	}

	public function index() {
		$data['contacts'] = $this->contacts_model->get_contacts();
		$this->load->view('contacts/contacts', $data);
	}

	public function create() {
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
		$name = $_POST['name'];
		$email = $_POST['email'];
		if (empty($name) || empty($email)) {
			header('Content-Type: application/json');
			echo json_encode(array('valid' => false, 'error' => 'Name or email field is missing' ,'csrf_token' => $this->security->get_csrf_hash()));
			return;
		}
		if (!$this->contacts_model->create($name, $email)) {
			header('Content-Type: application/json');
			echo json_encode(array('valid' => false, 'error' => 'There was an error of some sort, please try again.' ,'csrf_token' => $this->security->get_csrf_hash()));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('valid' => true, 'csrf_token' => $this->security->get_csrf_hash()));
	}

	public function delete($id) {
		if (!$this->validate()) {
			$this->redirectHome();
			return;
		}
		if (empty($id)) {
			header('Content-Type: application/json');
			echo json_encode(array('valid' => false, 'error' => 'There was an error of some sort, please try again.'));
			return;
		}
		if (!$this->contacts_model->delete($id)) {
			header('Content-Type: application/json');
			echo json_encode(array('valid' => false, 'error' => 'There was an error of some sort, please try again.'));
			return;
		}
		header('Content-Type: application/json');
		echo json_encode(array('valid' => true));
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
}
