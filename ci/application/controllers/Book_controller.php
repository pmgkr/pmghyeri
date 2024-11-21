<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Book_controller extends CI_Controller {

        public function __construct() {
            parent::__construct();
            // URL 헬퍼 로드
            $this->load->helper('url');
            //모델 파일과 연결
            $this->load->model('Book_model'); 
        }

        // controller : 모델에서 연결된 디비를 뷰에 어떻게 컨트롤하여 보여줄지
        public function index()
        {
            $segment = $this->uri->segment(2, 'list');
            switch($segment){
                case 'list':
                    $this->list();
                    break;
                case 'loan':
                    $this->loanA();
                    break;
                default:
                    show_404();
            }
        }
        public function list() {
            //검색어 받아오기
            $search_query = $this->input->get('search');
            //한 페이지에 보여줄 항목 수
            $limit = 20;
            // URL 세그먼트로부터 현재 페이지 번호 가져오기
            $page = $this->uri->segment(2);
            $page = isset($page) && is_numeric($page) ? (int)$page : 1;  // 기본값: 1 페이지
            //offset 계산
            $offset = ($page - 1) * $limit;

            //검색어에 따라 결과 가져오기
            if (!empty($search_query)) {
                $data['books'] = $this->Book_model->search_books($search_query, $limit, $offset);
                $total_books = $this->Book_model->get_search_books_count($search_query);
            } else {
                $data['books'] = $this->Book_model->get_books($limit, $offset);
                $total_books = $this->Book_model->get_books_count();
            }
            //echo $this->db->last_query(); //마지막 쿼리 확인
            

            //총 페이지 수 계산
            $data['total_pages'] = ceil($total_books / $limit); //전체 데이터 수 나누기 한페이지 보여줄 데이터수
            // 현재 페이지 넘버 설정
            $data['search_query'] = $search_query; // 뷰에 검색어 전달
            $data['current_page'] = $page;
            
            //뷰 파일 로드

            $this->load->view('pages/book_list', $data);
        }
        
        //대출처리
        public function loanA() {
            $book_seq = $this->input->post('book_seq');
            $user_id = 'hyericha@pmgasia.com';

            // 로그 기록 (CodeIgniter 로그 파일에 기록됨)
            log_message('debug', "Received book_seq: " . $book_seq);

            $book = $this->Book_model->get_book_by_seq($book_seq); // `seq`로 도서 검색
            $book_name = $book->book_name; // 책 이름 저장
            $book_author = $book->author; // 작가 저장
            $book_publisher = $book->publisher; // 출판사 저장
        
            if ($book && $this->Book_model->is_available_for_loan($book_seq)) { 
                // 대출 기록을 추가하고 상태를 업데이트
                if ($this->Book_model->create_loan($book_seq ,$book_name, $book_author, $book_publisher, $user_id)) {
                    $response = ['message' => '책이 성공적으로 대출되었습니다.'];
                } else {
                    $response = ['message' => '대출 처리 중 문제가 발생했습니다.'];
                }
            } else {
                $response = ['message' => '책을 대출할 수 없습니다.'];
            }
        
            echo json_encode($response); // JSON 응답 반환
        }
        

        //status====================================================
        public function status()
        {
            $search_query = $this->input->get('search');
            $limit = 20;
            $page = $this->uri->segment(2);
            $page = isset($page) && is_numeric($page) ? (int)$page : 1;  // 기본값: 1 페이지
            $offset = ($page - 1) * $limit;


            if (!empty($search_query)) {
                $data['loans'] = $this->Book_model->search_loans($search_query, $limit, $offset);
                $total_loans = $this->Book_model->get_search_loans_count($search_query);
            } else {
                $data['loans'] = $this->Book_model->get_loans($limit, $offset);
                $total_loans = $this->Book_model->get_loans_count();
            }
            //echo $this->db->last_query(); //마지막 쿼리 확인
            

            //총 페이지 수 계산
            $data['total_pages'] = ceil($total_loans / $limit); //전체 데이터 수 나누기 한페이지 보여줄 데이터수
            // 현재 페이지 넘버 설정
            $data['search_query'] = $search_query; // 뷰에 검색어 전달
            $data['current_page'] = $page;
            

            //뷰 파일 로드

            $this->load->view('pages/status_list', $data);
        }

        //반납처리
        public function return()
        {
            $book_seq = $this->input->post('book_seq');
            $user_id = 'hyericha@pmgasia.com';

            if (!$book_seq) {
                echo json_encode(['status' => 'error', 'message' => '도서 정보가 전달되지 않았습니다.']);
                return;
            }

            $updateStatus = $this->Book_model->returnBook($book_seq);
            if ($updateStatus) {
                echo json_encode(['status' => 'success', 'message' => '반납이 완료되었습니다.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => '반납 중 문제가 발생했습니다.']);
            }

            
        }
        
    }
?>