<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    
class Book_model extends CI_Model {

    //model : db와 연결, 쿼리를 날려 데려옴
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    

    public function get_books($limit, $offset) { 
        //limit : 한 페이지에 표시할 데이터 수, offset : 현재 페이지에 따라 얼마나 건너 뛸지 결정
        $this->db->order_by('seq', 'DESC');
        $query = $this->db->get('book_list',  $limit, $offset );
        return $query->result();
    }

    // 전체 책의 개수 구하기
    public function get_books_count() 
    {
        return $this->db->count_all('book_list');  // 'books' 테이블의 전체 행 개수
    }




    //검색하기
    public function search_books($search_query, $limit, $offset) {
        if (!empty($search_query)) {
            $this->db->like('book_name', $search_query);
            $this->db->or_like('author', $search_query);
            $this->db->or_like('publisher', $search_query);
        }
        $this->db->order_by('seq', 'DESC');
        $query = $this->db->get('book_list',  $limit, $offset );

        return $query->result();
    }

    public function get_search_books_count($search_query){
        if (!empty($search_query)) {
            $this->db->like('book_name', $search_query);
            $this->db->or_like('author', $search_query);
            $this->db->or_like('publisher', $search_query);
        }
        
        return $this->db->count_all_results('book_list'); // book_list 테이블에서 조건에 맞는 개수를 반환
    }
    



    //대출 처리


    public function get_book_by_seq($seq) {
        return $this->db->get_where('book_list', ['seq' => $seq])->row();
    }
    
    // 대출 가능한지 확인
    public function is_available_for_loan($book_seq) {
        $this->db->where('seq', $book_seq);
        $this->db->where('status', 'borrowed');
        $query = $this->db->get('book_loans');
        return $query->num_rows() == 0; // 대출된 기록이 없으면 가능
    }
    
    // 대출 기록 생성 및 책 상태 업데이트
    public function create_loan($book_seq, $book_name, $book_author, $book_status, $user_id) {
        $data = [
            'seq' => $book_seq,
            'book_name' => $book_name,
            'author' => $book_author,
            'status' => $book_status,
            'user_id' => $user_id,
            'loan_date' => date('Y-m-d H:i:s'),
            'return_date' => date('Y-m-d', strtotime('+7 days')), // 7일 후 due_date
            'status' => 'borrowed',
        ];
        $this->db->insert('book_loans', $data);
    
        // 도서 상태를 'borrowed'로 업데이트
        $this->db->where('seq', $book_seq);
        $this->db->update('book_list', ['status' => 'borrowed']);
        
        return $this->db->affected_rows() > 0;
    }


    //status===============================================
    
    public function get_loans($limit, $offset) { 
        //limit : 한 페이지에 표시할 데이터 수, offset : 현재 페이지에 따라 얼마나 건너 뛸지 결정
        $this->db->order_by('loan_date', 'DESC');
        $query = $this->db->get('book_loans',  $limit, $offset );
        return $query->result();
    }
    public function get_loans_count() 
    {
        return $this->db->count_all('book_loans');  // 'loans' 테이블의 전체 행 개수
    }

    //검색
    public function search_loans($search_query, $limit, $offset) {
        if (!empty($search_query)) {
            $this->db->like('book_name', $search_query);
            $this->db->or_like('author', $search_query);
            $this->db->or_like('publisher', $search_query);
        }
        $this->db->order_by('seq');
        $query = $this->db->get('book_loans',  $limit, $offset );

        return $query->result();
    }

    public function get_search_loans_count($search_query){
        if (!empty($search_query)) {
            $this->db->like('book_name', $search_query);
            $this->db->or_like('author', $search_query);
            $this->db->or_like('publisher', $search_query);
        }
        
        return $this->db->count_all_results('book_loans'); // book_list 테이블에서 조건에 맞는 개수를 반환
    }


    //반납하기
    public function returnBook($book_seq)
    {
        // 현재 시간 가져오기
        $returnTime = date('Y-m-d H:i');
        // book_loans 상태 업데이트
        $this->db->where('seq', $book_seq);
        $this->db->update('book_loans', ['status' => 'return', 'return_time' => $returnTime]);

        if ($this->db->affected_rows() > 0) {
            // book_list 상태 업데이트
            $this->db->where('seq', $book_seq);
            $this->db->update('book_list', ['status' => 'available']);
        }

        return $this->db->affected_rows() > 0; // 업데이트 성공 여부 반환

    }

}