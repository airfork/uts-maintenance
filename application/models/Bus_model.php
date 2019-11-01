<?php
/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/16/19
 * Time: 3:38 PM
 */

class Bus_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function get_buses($slug = false) {
        if ($slug == false) {
	    $this->db->order_by('id', 'ASC');
            $query = $this->db->get_where('buses', array('completed' => false));
            return $query->result_array();
        }
        $query = $this->db->get_where('buses', array('id' => $slug));
        return $query->row_array();
    }

    public function get_completed_buses() {
	$this->db->order_by('completedby', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get_where('buses', array('completed' => true));
        return $query->result_array();
    }

    public function update($bus = NULL, $name = NULL) {
        $this->db->set('completed', TRUE);
        $this->db->set('completedby', $name);
        $this->db->where('id', $bus);
        return $this->db->update('buses');
    }

    public function reset() {
        $this->db->set('completed', false);
        $this->db->set('completedby', '');
        return $this->db->update('buses');
    }

    public function add($bus = NULL): bool {
        if (empty($bus)) {
            return false;
        }
        $this->db->set('id', $bus);
        return $this->db->insert('buses');
    }

    public function delete($bus = NULL): bool {
        if (empty($bus)) {
            return false;
        }
        $this->db->where('id', $bus);
        return $this->db->delete('buses');
    }
}
