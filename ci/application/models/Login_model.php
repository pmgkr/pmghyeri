<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // 사용자 인증
    public function authenticate($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('book_login');
    
        if ($query->num_rows() > 0) {
            return $query->row(); // 사용자 정보 반환
        } else {
            return false; // 사용자 없음
        }
    }

    // 사용자 등록 (비밀번호 해시 포함)
    public function register_user($email, $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT); // 비밀번호 해시
        $data = [
            'email' => $email,
            'password' => $password_hash
        ];
        return $this->db->insert('book_login', $data);
    }

}
?>