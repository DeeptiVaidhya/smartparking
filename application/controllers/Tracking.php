<?php

class Tracking extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
         $this->load->model('Model');
       
         if(!$this->login->isLoggedIn())
         {
            redirect('AdminLogin');
         }
    }

    public function availableDriver() {

        $where  = array('available_staus' => 1); 
        $data['available_driver'] = $this->Model->selectdata('Doctors',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('tracking/available_driver',$data);
        $this->load->view('common/footer'); 
    }

     public function ongoingRides() {

        $where  = array('status' => 1); 
        $data['booking'] = $this->Model->selectdata('booking',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('tracking/ongoing_ride',$data);
        $this->load->view('common/footer'); 
    }

     public function cancelledRides() {

        $where  = array('status' => 2); 
        $data['booking'] = $this->Model->selectdata('booking',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('tracking/cancel_ride',$data);
        $this->load->view('common/footer'); 
    }
    


}