<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    
class Book_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function Get_books()
    {
        $result = $this->db->query('SELECT book_name, publisher FROM book_list')->result();
        $this->db->close();
    }


}