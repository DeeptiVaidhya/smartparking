<?php

class AdminDashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Model');
        $this->load->helper(array('url','html','form'));
        if(!$this->login->isLoggedIn()){
         redirect('AdminLogin');
        }
    }

    public function index(){

        if(empty($this->session->userdata('admin'))){
            redirect('AdminLogin');
          }
        $data['dc'] =  $this->DashboardModel->getAllDoctorCount();
        $data['pc'] =  $this->DashboardModel->getAllPatientsCount();
        $data['apc'] = $this->DashboardModel->getAttendPatientCount();
        $data['ppc'] = $this->DashboardModel->getPendingPatientCount();
        $data['doc'] = $this->DashboardModel->getAllDoctor();
        $data['App'] = $this->DashboardModel->getLatestAppoinment();
        $data['pat'] = $this->DashboardModel->getPatients();

        $users = $this->DoctorModel->getAllLocationList();
        $i = 0;

         for($i=0;$i<count($users);$i++){

            $driver_id = (int)$users[$i]->driver_id;
            $location_name = $users[$i]->location_name;
            $latitude = $users[$i]->latitude;
            $longitude = $users[$i]->longitude;

            $vehicle_icon = $users[$i]->vehicle_icon;


            if ($driver_id != 0) {
		        $where  = array('id' => $driver_id);
		        $driver_data = $this->Model->singleRowdata($where,'Doctors');
		        if ($driver_data) {
		        $name = $driver_data->username;
		        if (!empty($driver_data->vehicle_number)) {
		                $vehicle_number = $driver_data->vehicle_number;
		        }
		        }else{
		             $name ='User';
		             $vehicle_number ='';
		        }
		    }    

              $data['location'][] = $location_name.', '.$latitude.', '. $longitude.',' . $vehicle_number.',' . $name; 
                 //$data['location'][] = $location_name.', '.$latitude.', '. $longitude.',' . $vehicle_number.',' . $name.','.',' .($i+1); 
            }


	    // foreach($users as $value) {

	    // if ($value->driver_id != 0) {
	    //     $where  = array('id' => $value->driver_id);
	    //     $driver_data = $this->Model->singleRowdata($where,'Doctors');
	    //     if ($driver_data) {
	    //     $name = $driver_data->username;
	    //     if (!empty($driver_data->vehicle_number)) {
	    //             $vehicle_number = $driver_data->vehicle_number;
	    //     }
	    //     }else{
	    //          $name ='User';
	    //           $vehicle_number ='';
	    //     }
	    // }    

	    //$location[]= $row[0].', '.$row[2].', '.$row[1].','.($i+1);



	   //$data['location'][] = $value->location_name.', '.$value->latitude.', '. $value->longitude.',' . $vehicle_number.',' . $name; 


	  //$data['location'][]= array($value->location_name.', '.$value->latitude.', '. $value->longitude.',' . $vehicle_number);
              
//}

        // if ($users) {
        //     $markers = [];
        //     $infowindow = [];
     
        //     foreach($users as $value) { 
        //         if ($value->driver_id != 0) {
        //             $where  = array('id' => $value->driver_id);
        //             $driver_data = $this->Model->singleRowdata($where,'Doctors');
        //             if ($driver_data) {
        //             $name = $driver_data->username;
        //             if (!empty($driver_data->vehicle_number)) {
        //                     $vehicle_number = $driver_data->vehicle_number;
        //             }
        //             }else{
        //                  $name ='User';
        //                   $vehicle_number ='';
        //             }
        //         }
                

        //         $markers[] = [
        //             $value->location_name, $value->latitude, $value->longitude,$value->vehicle_icon
                   
        //         ];  

        //         $infowindow[] = [
        //            "<div class=info_content><h3>".$value->location_info."</h3><p>".$value->location_info."</p><p>Driver Name : ".$name."</p><p>Vehicle Number : ".$vehicle_number."</p></div>"
        //         ];
        //     }
        //     $data['markers'] = json_encode($markers);
        //     $data['infowindow'] = json_encode($infowindow);
        // }
        

        //echo '<pre>';print_r($data);die;

        
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('dashboard/dashboard',$data);
        // $this->load->view('common/footer');  
    }


    // public function index(){
    //     if(empty($this->session->userdata('admin'))){
    //         redirect('AdminLogin');
    //       }
    //     $data['dc'] =  $this->DashboardModel->getAllDoctorCount();
    //     $data['pc'] =  $this->DashboardModel->getAllPatientsCount();
    //     $data['apc'] = $this->DashboardModel->getAttendPatientCount();
    //     $data['ppc'] = $this->DashboardModel->getPendingPatientCount();
    //     $data['doc'] = $this->DashboardModel->getAllDoctor();
    //     $data['App'] = $this->DashboardModel->getLatestAppoinment();
    //     $data['pat'] = $this->DashboardModel->getPatients();

    //     // // $driver = $this->AdminLoginModel->getAllDriverLocation();

        
    //     // // $markers = [];
    //     // // $infowindow = [];
 
    //     // // foreach($driver as $value) {
    //     // //   $markers[] = [
    //     // //     $value->location_name, $value->latitude, $value->longitude
    //     // //   ];          
    //     // //   $infowindow[] = [
    //     // //    "<div class=info_content><h3>".$value->location_name."</h3><p>".$value->location_info."</p></div>"
    //     // //   ];
    //     // // }
    //     // // $location['markers'] = json_encode($markers);
    //     // // $location['infowindow'] = json_encode($infowindow);


    //     // $doctor_id = 2;
    //     // $data['Doctor_profile'] = $this->DoctorModel->getDocterById($doctor_id);

    //     // $users = $this->DoctorModel->getLocationList($doctor_id);
    //     // print_r($data['Doctor_profile']);

    //     // print_r($users);


    //     // $markers = [];
    //     // $infowindow = [];

    //     // $location_name = "Indore";

    //     // $location_info = "Indore";
 
    //     // foreach($users as $value) {
    //     //   $markers[] = [
    //     //     $location_name, $value->latitude, $value->longnitue
    //     //   ];          
    //     //   $infowindow[] = [
    //     //    "<div class=info_content><h3>".$location_name."</h3><p>".$location_info."</p></div>"
    //     //   ];
    //     // }
    //     // $data['markers'] = json_encode($markers);
    //     // $data['infowindow'] = json_encode($infowindow);  
        
    //     $this->load->view('common/header');
    //     $this->load->view('common/sidebar');
    //     $this->load->view('dashboard/dashboard_view',$data);
    //     // $this->load->view('common/footer');
    // }
    
    public function AdminProfile(){
       
        $id = $this->session->userdata('admin')[0]->id;
        $data['admin'] = $this->AdminLoginModel->getAdminProfile($id);
        
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('admin/admin_profile',$data);
        $this->load->view('common/footer');  
    }

    public function Setting(){
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('admin/admin_setting');
        $this->load->view('common/footer');
    }

    public function EditProfile(){
       if(!empty($this->input->post())){
          $admin = $this->input->post();
             $id = $admin['id'];
             $config['upload_path'] = './uploads/admin_profiles';
             $config['allowed_types'] = 'gif|jpg|png|jpeg';
             $config['max_size'] = 2000;
             $config['max_width'] = 4500;
             $config['max_height'] = 4500;

            $this->load->library('upload', $config);
            if(empty($admin['dob'])){
                unset($admin['dob']);
            }
            if (!$this->upload->do_upload('admin_image')) {
                $error = array('error' => $this->upload->display_errors());
                
            } else {
                $image = $this->upload->data()['file_name'];
                $admin['image'] = $image;
            }

            if($this->AdminLoginModel->updateAdmin($admin,$id)){
                 $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissable" style="margin:0px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> Success ! </strong> Profile Update successfully</div>');

                redirect('AdminDashboard/AdminProfile');
            }else{
                 $this->session->set_flashdata('error', '<div class="alert alert-warning alert-dismissable" style="margin:0px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> Success ! </strong> Profile failed to  Update</div>');

                redirect('AdminDashboard/AdminProfile');
            }
            
       }
       else {
        $id = $this->session->userdata('admin')[0]->id;
        $data['Admin_data'] = $this->AdminLoginModel->getAdminProfile($id);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('admin/admin_edit_profile',$data);
        $this->load->view('common/footer');
    }   
    }

    public function changePassword(){
        $this->form_validation->set_rules('old_password','Old Password','required');
        $this->form_validation->set_rules('new_password','New Password','required');
        $this->form_validation->set_rules('confirm_password','Confirm Password','required|matches[new_password]');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">','</div>');
        if($this->form_validation->run() == TRUE){
        	$old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $id = $this->session->userdata('admin')[0]->id;
          
        if($this->AdminLoginModel->checkOldPassword($old_password,$id)){
        	if($this->AdminLoginModel->updatePassword($new_password,$id)){
        		$this->session->set_flashdata('success','Password changed succesfully');
        		redirect('AdminDashboard/Setting');
         }
          }
        else {
        	$this->session->set_flashdata('error','Old password are incorrect');
        	redirect('AdminDashboard/Setting');
         }
       
        }
        else {
            $this->load->view('common/header');
            $this->load->view('common/sidebar');
            $this->load->view('admin/admin_setting');
            $this->load->view('common/footer');
        }

    }

    //  public function get_doctors_Location(){

    //     $users = $this->AdminLoginModel->getAllDriverLocation();

    //     $markers = [];
    //     $infowindow = [];

    //     $location_name = "Indore";

    //     $location_info = "Indore";
 
    //     foreach($users as $value) {
    //       $markers[] = [
    //         $location_name, $value['latitude'], $value['longnitue']
    //       ];          
    //       $infowindow[] = [
    //        "<div class=info_content><h3>".$location_name."</h3><p>".$location_info."</p></div>"
    //       ];
    //     }
    //     $data['markers'] = json_encode($markers);
    //     $data['infowindow'] = json_encode($infowindow);

    //     //print_r($Doctor_profile);die;
    //     echo json_encode($Doctor_profile);
    // }




    public function Approve(){
    $id = $this->uri->segment(3);
    $this->DashboardModel->approveAppointment($id);
    redirect('AdminDashboard');
    }
}