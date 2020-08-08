<?php

class Vehicle extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
         $this->load->model('Model');
        $this->load->helper(array('url','html','form'));
       
         if(!$this->login->isLoggedIn())
         {
            redirect('AdminLogin');
         }
    }


     public function index() {
        $data['vehicle'] = $this->Model->select('vehicle_category');

        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('vehicle/vehicle_list',$data);
        $this->load->view('common/footer');   
     
        
    }

     public function addVehicle()
    {
        if($this->session->userdata('admin')){


          if(!empty($this->input->post()))
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('pricing', 'Pricing', 'required');
            $this->form_validation->set_rules('commission', 'Commission', 'required');
            //$this->form_validation->set_rules('price_per_distance', 'Pri', 'required');
            

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">','</div>');
            if ($this->form_validation->run() == TRUE)
            {
               

            $name   =  $this->input->post('name');
            $pricing  =  $this->input->post('pricing');
            $commission  =  $this->input->post('commission');
            $price_per_distance  =  $this->input->post('price_per_distance');
            $status  =  $this->input->post('status');

            $data  = array('name' => $name, 'pricing' => $pricing, 'commission' => $commission, 'price_per_distance' => $price_per_distance,'status' => $status );
            if($this->Model->insert($data,'vehicle_category')){

                  $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissable" style="margin:0px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> Success ! </strong> Vehicle Added Successfully</div>');



                // $this->session->set_flashdata('success','Vehicle Added Successfully');
                redirect('Vehicle');
            }
      
            else {
                $this->session->set_flashdata('alert', '<div class="alert alert-success alert-dismissable" style="margin:0px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> Error ! </strong> Vehicle not added Successfully</div>');



                // $this->session->set_flashdata('success','Vehicle Added Successfully');
                redirect('Vehicle');
            }
           
        }

        else {
                $data['vehicle'] = $this->Model->select('vehicle_category');
                $this->load->view('common/header');
                $this->load->view('common/sidebar');
                $this->load->view('vehicle/add_vehicle',$data);
                $this->load->view('common/footer'); 
        }
    }
    else {
            $data['vehicle'] = $this->Model->select('vehicle_category');
                $this->load->view('common/header');
                $this->load->view('common/sidebar');
                $this->load->view('vehicle/add_vehicle',$data);
                $this->load->view('common/footer'); 
        }
  }
  }


    public function editVehicle($id){
        $where = array('id' => $id);
        $data['vehicle_data'] = $this->Model->singleRowdata($where,'vehicle_category');            
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('vehicle/edit_vehicle',$data);
        $this->load->view('common/footer');  
    }

    public function editVehicleDetails() {
        $wheredata = array(
          'id' => $this->input->post('vehicle_id')
        );
        

        $data = array(
            'name'           => $this->input->post('name'),
            'pricing'     => $this->input->post('pricing'),
            'commission'           => $this->input->post('commission'),
            'price_per_distance'           => $this->input->post('price_per_distance'),
            'status'           => $this->input->post('status'),
        );
            
        $updateResult = $this->Model->update('vehicle_category',  $data, $wheredata);
        if ($updateResult) {
          $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Record Updated Sucessfully!!</strong>.
          </div>');
          redirect('Vehicle');
        }else{
          $this->session->set_flashdata('alert', '<div class="alert alert-danger alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Error!!</strong>.
          </div>');
          redirect('Vehicle');
        }
    
           
   }




    public function Status(){
        $appointment_id = $this->uri->segment(3);
        $status_id = $this->uri->segment(4);

        $this->db->where('appointment_id',$appointment_id);
        $this->db->update('Appointment',array('status'=>$status_id));
        
        redirect('Vehicle');

    }

    public function deleteVehicle(){
        $id = $this->uri->segment(3);
        $where  = array('id' => $id , );
        $data['Doctor_data'] = $this->Model->delete($where,'vehicle_category');
        redirect('Vehicle');
    }




}