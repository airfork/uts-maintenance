<?php
/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/23/19
 * Time: 2:45 AM
 */

class User_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('encryption');
    }

    public function create() {
        $username = $this->sanitize($this->input->post('username'));
        $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $data = array(
            'username' => $username,
            'password' => $password,
        );
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function check_user($username, $password) {
        $query = $this->db->get_where('users', array('username' => $username))->row_array();
        if (empty($query)) {
            return false;
        }
        if (!password_verify($password, $query['password'])) {
            return false;
        }
        $_SESSION['id'] = $this->encryption->encrypt($query['id']);
        return true;
    }

    private function sanitize($data) {
        return htmlspecialchars(trim(stripslashes($data)));
    }
}