<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Book_controller extends CI_Controller {

        public function index()
        {
            $this->load->model('Book_model');
            $data['books'] = $this->Book_model->Get_books();
            $this->load->view('pages/book_list', $data);
        }
    }
?>