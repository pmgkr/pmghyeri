<?php
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

        // 사용자 인증
        $user = $this->Login_model->authenticate($email);

         // 디버깅을 위한 로그
        log_message('debug', 'User Data: ' . print_r($user, true)); // 사용자 정보 확인
        log_message('debug', 'Entered Password: ' . $password);  // 입력한 비밀번호 확인
        log_message('debug', 'Stored Password: ' . $user->password);  // 저장된 암호화된 비밀번호 확인

        if ($user && strcmp($password, $user->password)) {
            // 세션 설정
            $this->session->set_userdata('user_id', $user->id);
            redirect('/book'); // 로그인 후 book_list 페이지로 리다이렉트
        } else {
            $this->session->set_flashdata('error', 'Invalid email or password'); // 오류 메시지 설정
            redirect('/login');
        }
    }

    // 로그아웃
    public function logout() {
        $this->session->unset_userdata('user_id');
        redirect('/login');
    }
}
?>
