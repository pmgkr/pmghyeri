<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function authenticate($email) {
        return $this->db->get_where('book_login', ['email' => $email])->row();
    }

}
?>