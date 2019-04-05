<?php
/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/23/19
 * Time: 2:54 AM
 */

class User extends CI_Controller {
    private $production = false;
    private $webURL = 'https://inspection-list.herokuapp.com';

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
        if (getenv('PRODUCTION')) {
            $this->production = true;
        }
    }

    public function index() {
        $this->signed_in();
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user/login', $data);
    }

    public function login() {
        $this->signed_in();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'username', 'required', array('required' => 'You have not provided a %s'));
        $this->form_validation->set_rules('password', 'password', 'required|callback_check_user');
        if ($this->form_validation->run() === FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            echo $this->production;
//            $this->load->view('user/login', $data);
        } else {
            if ($this->production) {
                redirect($this->webURL.'/dashboard', 'refresh');
                return;
            }
            redirect('/dashboard', 'refresh');
        }
    }

    public function register() {
        $this->validate();
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user/register', $data);
    }

    public function create() {
        $this->signed_in();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('encryption');
        $this->form_validation->set_rules(
            'username', 'username',
            'required|min_length[5]|max_length[12]|is_unique[users.username]',
            array(
                'required'      => 'You have not provided a %s.',
                'is_unique'     => 'This %s already exists.'
            )
        );
        $this->form_validation->set_rules('password', 'password', 'required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'password confirmation', 'required|matches[password]');
        if ($this->form_validation->run() === FALSE) {
            $data['csrf'] = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('user/register', $data);
        } else {
            $_SESSION['id'] = $this->encryption->encrypt($this->user_model->create());
            if ($this->production) {
                redirect($this->webURL, 'refresh');
                return;
            }
            redirect('/', 'refresh');
        }
    }

    public function check_user() {
        $this->signed_in();
        $username = $this->sanitize($this->input->post('username'));
        $password = $this->input->post('password');
        if(!$this->user_model->check_user($username, $password)) {
            $this->form_validation->set_message('check_user', 'Username or password is incorrect, please try again.');
            return FALSE;
        }
        return TRUE;
    }

    public function logout() {
        session_unset();
        session_destroy();
        if ($this->production) {
            redirect($this->webURL, 'refresh');
            return;
        }
        redirect('/', 'refresh');
    }

    public function dashboard() {
        $this->validate();
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('user/dashboard', $data);
    }

    private function sanitize($data) {
        return htmlspecialchars(trim(stripslashes($data)));
    }

    private function validate() {
        if (empty($_SESSION['id'])) {
            if ($this->production) {
                redirect($this->webURL.'/login', 'refresh');
                return;
            }
            redirect('/login', 'refresh');
        }
    }

    private function signed_in() {
        if (!empty($_SESSION['id'])) {
            if ($this->production) {
                redirect($this->webURL, 'refresh');
                return;
            }
            redirect('/', 'refresh');
        }
    }
}