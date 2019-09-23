<?php

class Contacts_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function create($name, $email) {
		$data = array(
			'name' => $name,
			'email' => $email,
		);
		$this->db->set($data);
		return $this->db->insert('contacts');
	}

	public function get_contacts($names = true) {
		if ($names) {
			$this->db->order_by('name', 'ASC');
		} else {
			$this->db->select('email');
		}
		$query = $this->db->get('contacts');
		return $query->result_array();
	}

	public function delete($id) {
		if (empty($id)) {
			return false;
		}
		$this->db->where('id', $id);
		return $this->db->delete('contacts');
	}
}
