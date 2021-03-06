<?php
/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/17/19
 * Time: 10:31 PM
 */

class Issues_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function create($bus, $description, $location, $name) {
        $data = array(
            'busnumber' => $bus,
            'location' => $location,
            'description' => $this->sanitize($description),
            'submittedby' => $this->sanitize($name)
        );
        $this->db->set($data);
        return $this->db->insert('issues');
    }

    public function reset() {
        $this->db->set('ignored', true);
        return $this->db->update('issues');
    }

    public function get_issues($area) {
        $this->db->order_by('createdat', 'ASC');
        $this->db->order_by('busnumber', 'ASC');
        $query = $this->db->get_where('issues', array('location' => $area, 'ignored' => false));
        return $query->result_array();
    }

    public function get_issue($id = null) {
    	$query = $this->db->get_where('issues', array('id' => $id, 'ignored' => false, 'repaired_at' => null));
    	return $query->row_array();
	}

    public function delete($bus): bool {
        $this->db->where('busnumber', $bus);
        return $this->db->delete('issues');
    }

    public function current_issues($busNumber) {
    	$this->db->order_by('location', 'ASC');
    	$query = $this->db->get_where('issues', array('busnumber' => $busNumber, 'ignored' => false, 'repaired_at' => null));
    	return $query->result_array();
	}

	public function resolve($issue) {
    	$this->db->set('repaired_at', 'NOW()', false);
    	$this->db->where('id', $issue);
    	return $this->db->update('issues');
	}

    private function sanitize($data) {
        return htmlspecialchars(trim(stripslashes($data)));
    }
}
