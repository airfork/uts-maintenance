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
            'busNumber' => $bus,
            'location' => $location,
            'description' => $this->sanitize($description),
            'submittedBy' => $this->sanitize($name)
        );
        $this->db->set($data);
        return $this->db->insert('issues');
    }

    public function reset() {
        $this->db->set('ignored', true);
        return $this->db->update('issues');
    }

    public function get_issues($area) {
        $this->db->order_by('busNumber', 'ASC');
        $query = $this->db->get_where('issues', array('location' => $area, 'ignored' => false));
        return $query->result_array();
    }

    private function sanitize($data) {
        return htmlspecialchars(trim(stripslashes($data)));
    }
}