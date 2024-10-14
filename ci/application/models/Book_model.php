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


}