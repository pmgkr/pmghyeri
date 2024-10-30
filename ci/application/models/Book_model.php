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
    public function create_loan($book_seq ,$book_name, $user_id) {
        $data = [
            'seq' => $book_seq,
            'book_name' => $book_name,
            'user_id' => $user_id,
            'loan_date' => date('Y-m-d H:i:s'),
            'status' => 'borrowed',
        ];
        $this->db->insert('book_loans', $data);
    
        // 도서 상태를 'borrowed'로 업데이트
        $this->db->where('seq', $book_seq);
        $this->db->update('book_list', ['status' => 'borrowed']);
        
        return $this->db->affected_rows() > 0;
    }

}