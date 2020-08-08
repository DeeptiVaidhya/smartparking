<?php

class Report extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
         $this->load->model('Model');
       
         if(!$this->login->isLoggedIn())
         {
            redirect('AdminLogin');
         }
    }

    public function per_day() {

        $currdate = date('Y-m-d');

        $data['booking'] = $this->db->query('SELECT * FROM booking as b WHERE b.date_time Like "%'.$currdate.'%"')->result_array();        

        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('report/perday_ride',$data);
        $this->load->view('common/footer'); 
    }

     public function per_month() {

        $currdate = date('Y-m-d');
        $week_last_day = date("Y-m-d", strtotime("- 30 day"));
         $data['booking'] = $this->db->query('SELECT * FROM booking as b WHERE b.date_time BETWEEN "'.$week_last_day.'" AND "'.$currdate.'" ' )->result_array();

        //$data['booking'] = $this->db->query('SELECT * FROM booking as b WHERE b.date_time Like "%'.$currdate.'%"')->result_array();       

        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('report/monthly_ride',$data);
        $this->load->view('common/footer'); 
    }



     public function dailyEarnings() {

        $currdate = date('Y-m-d');

        $data['booking'] = $this->db->query('SELECT * FROM booking as b WHERE b.date_time Like "%'.$currdate.'%"')->result_array();
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('report/daily_earning',$data);
        $this->load->view('common/footer'); 
    }

    public function driverNotification() {

       // $where  = array('available_staus' => 1); 
        $data['driver_noti'] = $this->Model->select('booking_notificaion');
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('report/driverNotification',$data);
        $this->load->view('common/footer'); 
    }


    public function userNotification() {

       // $where  = array('available_staus' => 1); 
        $data['driver_noti'] = $this->Model->select('Notification');
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('report/userNotification',$data);
        $this->load->view('common/footer'); 
    }
    


}