<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Login_controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->library('session');
    }

    // 로그인 페이지
    public function login() {
        $this->load->view('pages/login'); // 로그인 뷰 로드
    }

    // 로그인 처리
    public function login_process() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
    
        // 사용자 인증 (이메일 기준으로 조회)
        $user = $this->Login_model->authenticate($email);

        // 디버깅을 위한 로그
        log_message('debug', 'User Data: ' . print_r($user, true)); // 사용자 정보 확인
        log_message('debug', 'Entered Password: ' . $password);  // 입력한 비밀번호 확인
        //log_message('debug', 'Stored Password: ' . $user->password);  // 저장된 암호화된 비밀번호 확인
    
        if (!$user) {
            // 이메일이 존재하지 않는 경우
            $this->session->set_flashdata('error', 'Email not found.');
            $this->load->view('pages/login');
        } elseif (!password_verify($password, $user->password)) {
            // 비밀번호가 틀린 경우
            $this->session->set_flashdata('error', 'Incorrect password.');
            $this->load->view('pages/login');
        } else {
            // 로그인 성공시 세션에 사용자 이름과 이메일 저장
            $this->session->set_userdata([
                'user_email' => $user->email,
                'user_name' => $user->username // 데이터베이스의 사용자 이름
                ]);
            redirect('/book');
        }
    
        // 실패 시 로그인 페이지로 리다이렉트
        $this->load->view('pages/login');
    }
    

    //비밀번호 해시
    public function hash_existing_passwords() {
        // book_login 테이블에서 모든 사용자 가져오기
        $this->db->select('email, password');
        $users = $this->db->get('book_login')->result();
    
        foreach ($users as $user) {
            // 기존 평문 비밀번호 해시 변환
            if (!password_get_info($user->password)['algo']) { // 이미 해시된 경우 스킵
                $hashed_password = password_hash($user->password, PASSWORD_DEFAULT);
    
                // 변환된 해시를 DB에 업데이트
                $this->db->where('email', $user->email);
                $this->db->update('book_login', ['password' => $hashed_password]);
    
                // 로그에 기록 (디버깅용)
                log_message('debug', 'Email: ' . $user->email . ' | Hashed Password: ' . $hashed_password);
            }
        }
    
        echo "비밀번호 해시 변환이 완료되었습니다!";
    }
    
        

    // 로그아웃
        public function logout() {
        // 세션 해제
        $this->session->unset_userdata('user_email'); 
        $this->session->sess_destroy(); // 세션 완전히 파괴
        redirect('/login');
    }
}


?>
