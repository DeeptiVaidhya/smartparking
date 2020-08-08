<?php

class Doctor extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
         //$this->load->model('Google_model', 'google');
        $this->load->helper(array('url','html','form'));
       
         if(!$this->login->isLoggedIn())
         {
            redirect('AdminLogin');
         }
    }

     public function index() {
        $location['doctors'] = $this->DoctorModel->getAllDocters();

        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('doctor/doctor_list',$location);
        //$this->load->view('common/footer');   
     
        
    }

    public function add_doctors()
    {   
        if(!empty($this->input->post()))
        {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('username', '', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('cpassword', 'Password Confirmation', 'required|matches[password]');
            $this->form_validation->set_rules('dob', 'Date Of birth', 'required');
            $this->form_validation->set_rules('joning_date', 'Joning Date', 'required');
            $this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('city', 'City', 'required');
            $this->form_validation->set_rules('state', 'State', 'required');
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
            $this->form_validation->set_rules('phone_number', 'Phone number', 'required');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">','</div>');
            if ($this->form_validation->run() == TRUE)
            {

                $config['upload_path'] = './uploads/doctor_profiles';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2000;
                $config['max_width'] = 4500;
                $config['max_height'] = 4500;
       


                $this->load->library('upload', $config);
        
                if (!$this->upload->do_upload('profile_image')) 
                {
                   $error = array('error' => $this->upload->display_errors());
                   $image = '';
            
           
                 } 
                 else 
                 {
                   $image = $this->upload->data()['file_name'];
                 }
                    $d = $this->input->post();
         
          $doctor_data = array('first_name'=>$d['firstname'],'last_name'=>$d['lastname'],'username'=>$d['username'],'email'=>$d['email'],'password'=>$d['password'],'dob'=>$d['dob'],'gender'=>$d['gender'],'address'=>$d['address'],'Country'=>$d['country'],'city'=>$d['city'],'state'=>$d['state'],'postal_code'=>$d['state'],'phone_number'=>$d['phone_number'],'image'=>$image,'status'=>$d['status'],'assign_id'=>$d['assign_id'],'joning_date'=>$d['joning_date'],'Vehicle_Type'=>$d['vehicle_type']


      );
        if(!$this->DoctorModel->isEmailAvailable($doctor_data['email'])){
        if($this->DoctorModel->AddDocter($doctor_data)){
            $this->session->set_flashdata('docter_add_result','Driver Added Successfully');
        }
       }
       else {
       
       }
       redirect('Doctor');
        
            }
            else {
              $data['department'] = $this->DoctorModel->departments();
                  $data['hospital'] = $this->DoctorModel->hospital_list();
                $this->load->view('common/header');
                $this->load->view('common/sidebar');
                $this->load->view('doctor/add_doctor',$data);
                $this->load->view('common/footer');  
            }
           
        }
        else {
         $data['department'] = $this->DoctorModel->departments();
         $data['hospital'] = $this->DoctorModel->hospital_list();
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('doctor/add_doctor',$data);
        $this->load->view('common/footer');  
    }
    }

    public function edit_doctors(){

        if(!empty($this->input->post())){
             $d = $this->input->post();
            
            $id = $d['id'];
              
             $config['upload_path'] = './uploads/doctor_profiles';
             $config['allowed_types'] = 'gif|jpg|png|jpeg';
             $config['max_size'] = 2000;
             $config['max_width'] = 4500;
             $config['max_height'] = 4500;
    

          

            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $image = '';
                
                
            } else {
                $image = $this->upload->data()['file_name'];
                $d['image'] = $image;
            }
            
         

            // $doctor_update_data = array('first_name'=>$d['first_name'],'last_name'=>$d['last_name'],'username'=>$d['username'],'email'=>$d['email'],'dob'=>$d['dob'],'address'=>$d['address'],'Country'=>$d['country'],'city'=>$d['city'],'state'=>$d['state'],'postal_code'=>$d['state'],'phone_number'=>$d['phone_number'],'image'=>$image,'biography'=>$d['biography'],'status'=>$d['status'],'profession'=>$d['pro']);
            if($this->DoctorModel->updateDocter($d,$id)){
              redirect('Doctor');
            }
           



        }
        else {
        $doctor_id = $this->uri->segment(3);
        $data['Doctor_data'] = $this->DoctorModel->getDocterById($doctor_id);
        $data['department'] = $this->DoctorModel->departments();
         $data['hospital'] = $this->DoctorModel->hospital_list();
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('doctor/edit_doctor',$data);
        $this->load->view('common/footer');  
        }
    }
    
    public function doctors_profile(){
        $doctor_id = $this->uri->segment(3);
        $data['Doctor_profile'] = $this->DoctorModel->getDocterById($doctor_id);

        $users = $this->DoctorModel->getLocationList($doctor_id);


        $markers = [];
        $infowindow = [];

        $location_name = "Indore";

        $location_info = "Indore";
 
        foreach($users as $value) {
          $markers[] = [
            $location_name, $value->latitude, $value->longnitue
          ];          
          $infowindow[] = [
           "<div class=info_content><h3>".$location_name."</h3><p>".$location_info."</p></div>"
          ];
        }
        $data['markers'] = json_encode($markers);
        $data['infowindow'] = json_encode($infowindow);


        
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('doctor/doctor_profile',$data);
        //$this->load->view('common/footer');  
    }

    public function get_doctors_profile(){

        $doctor_id = $_GET['doctor_id'];
        $Doctor_profile = $this->DoctorModel->getLocationList($doctor_id);

        //print_r($Doctor_profile);die;
        echo json_encode($Doctor_profile);
    }


    public function delete_doctors(){
        $id = $this->uri->segment(3);
        $data['Doctor_data'] = $this->DoctorModel->deleteDocterById($id);
        redirect('Doctor');
    }

    public function CheckEmail(){
        $email = $this->input->post('email');
        if($this->DoctorModel->isEmailAvailable($email)){
           echo TRUE;
        }
        else {
           echo FALSE;
        }
    }

    public function dashboard(){
        $data['dc'] =  $this->DashboardModel->getAllDoctorCount();
        $data['pc'] =  $this->DashboardModel->getAllPatientsCount();
        $data['apc'] = $this->DashboardModel->getAttendPatientCount();
        $data['ppc'] = $this->DashboardModel->getPendingPatientCount();
        $data['doc'] = $this->DashboardModel->getAllDoctor();
        $data['App'] = $this->DashboardModel->getLatestAppoinment();
        $data['pat'] = $this->DashboardModel->getPatients();

        $users = $this->DoctorModel->getAllLocationList();
        $markers = [];
        $infowindow = [];

        $location_name = "Indore";

        $location_info = "Indore";
 
        foreach($users as $value) {
          $markers[] = [
            $value->location_name, $value->latitude, $value->longitude
          ];          
         $infowindow[] = [
           "<div class=info_content><h3>".$value->location_name."</h3><p>".$value->location_info."</p></div>"
          ];
        }
        $data['markers'] = json_encode($markers);
        $data['infowindow'] = json_encode($infowindow);

        // echo '<pre>'; print_r($data);die;

        
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('doctor/dashboard',$data);
        //$this->load->view('common/footer');  
    }



}