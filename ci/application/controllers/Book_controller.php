<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Book_controller extends CI_Controller {

        public function __construct() {
            parent::__construct();
    
            // URL 헬퍼 로드
            $this->load->helper('url');
        }

        // controller : 모델에서 연결된 디비를 뷰에 어떻게 컨트롤하여 보여줄지
        public function index()
        {
            //모델 파일과 연결
            $this->load->model('Book_model'); 

            //한 페이지에 보여줄 항목 수
            $limit = 20;

            // URL 세그먼트로부터 현재 페이지 번호 가져오기
            $page = $this->uri->segment(2);
            $page = isset($page) && is_numeric($page) ? (int)$page : 1;  // 기본값: 1 페이지

            //offset 계산
            $offset = ($page - 1) * $limit;

            //데이터 가져오기
            $data['books'] = $this->Book_model->get_books($limit, $offset);

            //전체 데이터 수 가져오기
            $total_books = $this->Book_model->get_books_count();

            //총 페이지 수 계산
            $data['total_pages'] = ceil($total_books / $limit); //전체 데이터 수 나누기 한페이지 보여줄 데이터수

            // 현재 페이지 넘버 설정
            $data['current_page'] = $page;
            
            //뷰 파일 로드
            $this->load->view('pages/book_list', $data);
        }

        public function rental_status()
        {
            $this->load->view('pages/rental_status');
        }
    }
?>