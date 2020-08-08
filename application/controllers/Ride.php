<?php

class Ride extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model');

         $this->load->model('Api/LoginApiModel');
        if(empty($this->session->userdata('admin'))){
            redirect('AdminLogin');            
          }
    }

    public function index(){
        //$data['Appointments'] = $this->AppointmentModel->getAllAppointment();
        $where = array('pick_up_letter' => 0);
        $data['booking'] = $this->Model->selectdata('booking',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('ride/all_ride',$data);
        $this->load->view('common/footer');   
    }

     public function pickup_letter(){
        $where = array('pick_up_letter' => 1);
        $data['booking'] = $this->Model->selectdata('booking',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('ride/pick_letter',$data);
        $this->load->view('common/footer');   
    }

    public function add_ride(){
        
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->model('Model');
        $data['vehicles'] = $this->Model->select('vehicle_category');
        //print_r($data);die();
        $this->load->view('ride/add_ride',$data);        
        $this->load->view('common/footer');  
    }
    public function save_booking(){        
        $this->load->view('common/header');
        $this->load->view('common/sidebar');        
         if(!empty($this->input->post())){
         $user_id = $this->input->post('user_id');
         $phonenum = $this->input->post('phonenum');
         $pickloc = $this->input->post('pickloc');
         $droploc = $this->input->post('droploc');
         $date = $this->input->post('date');
         $time = $this->input->post('time');
         $vehicle_type = $this->input->post('vehicle_type');

         $stuf_name = $this->input->post('stuf_name'); 
         $description = $this->input->post('description');
         

         $pick_location[] = array("address"=>$pickloc,"latitude"=>"22.9331491","longitude"=>"75.8826237");

         $pic = json_encode($pick_location);

         $drop_location[] = array("address"=>$droploc,"latitude"=>"22.9331491","longitude"=>"75.8826237");

         $drop = json_encode($drop_location);

         $data=array(
            'user_id'=>$user_id,
            'phonenum'=>$phonenum,
            'pick_location'=>$pic,
            'drop_location'=>$drop,
            'pick_date'=>$date,
            'pick_time'=>$time,
            'stuf_name' => $stuf_name,
            'description' => $description,
            'vehicle_type'=>1,
            'booking_date' => date('Y-m-d'),
            'booking_time' => date('h:i:s'),
            'date_time' => date('Y-m-d h:i')
         );
        
          $this->load->model('AppointmentModel');
          

          if($this->AppointmentModel->InserCustomer($data)){

            $booking_id = $this->db->insert_id();

                 $drop_loca = json_decode($drop);
                 foreach ($drop_loca as $key => $value) {
                    $drop_location  = array(
                        'booking_id' => $booking_id,
                        'address'    => $value->address,
                        'latitude'    => $value->latitude,
                        'longitude' => $value->longitude,
                        // 'reciver_name' => $value->reciver_name,
                        // 'reciver_number' => $value->reciver_number,

                    );

                     $insert_data =  $this->LoginApiModel->insert($drop_location,'booking_drop_location');
                 }

                $pick_loca = json_decode($pic);

                //print_r($pick_loca);
                foreach ($pick_loca as $key => $value) {
                   $latitude   = $value->latitude;
                   $longitude  =  $value->longitude;
                 }
                //$whereStatus  = array('online_status' => '1');
                $getDoctors = $this->LoginApiModel->selectnearbyDriver($latitude,$longitude);
               // print_r($getDoctors);die;
                if ($getDoctors) {

                    foreach($getDoctors as $key) {

                        $doctor_data[] = array('id' => $key['id']);

                        $title = 'Notification';
                        $message = 'You Have Received New Request';
                        $device_token = $key['Firebase_token'];

                        $doctor_id = $key['id'];
                        $patient_id = $user_id;

                        date_default_timezone_set("Asia/Calcutta");
                        $datetime = date('Y-m-d H:i:s');

                        $this->push_notification_android($device_token, $message, $title, $type ='');

                         $this->firebase->insertBookingMessage(
                            array(
                               'paitent_id'=>$patient_id,
                               'doctor_id'=>$doctor_id,
                               'booking_id'=>$booking_id,
                                'vehicle_type'=>1,
                               'message'=>'You have received a New Booking Request',
                               'title'=>'New Booking',
                               'date_time' => $datetime 
                            )
                        );
                       
                    }
                }


              $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissable" style="color: #000;background-color: #89e6d4;border-color: #52dfc3;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> Success ! </strong> Ride Add successfully</div>');
                 redirect('Ride');
          }else{
               $this->session->set_flashdata('error', '<div class="alert alert-warning alert-dismissable" style="color: #000;background-color: #89e6d4;border-color: #52dfc3;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error ! </strong> Ride failed  to Add</div>');

               redirect('Ride/add_ride');
          }

     }
     $this->load->view('common/footer');
    }


    /*Notification API*/
    function push_notification_android($device_id,$message,$message_info='', $type =''){
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAAVNgEe24:APA91bHTul1yu0VI-Jb2nTjrOPW3P_yVzMdZ9p0S7Si380hCvEgqr3OENci3_ni4Mg-Doeba8bx_vBzmLNdO-lrC10s6xtxlxlaR1BugSZaEPb1K81sx2aUxP31SaSP2Q0-5B_p4zx49';

        // $fields = array (
        // 'registration_ids' => array (
        //     $device_id
        // ),
        // 'data' => array (
        //     "message" => $message
        // )
        // );

        $fields = array (
            'registration_ids' => array (
                    $device_id
            ),
            'data' => array (
                    "message" => $message,
                    'message_info' => $message_info,
            ),                
            'priority' => 'high',
            'notification' => array(
                        'title' => "Notification",
                        'body' => $message,                            
            ),
        );
        $fields = json_encode ( $fields );

        //header includes Content type and api key
        $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        //echo "<pre>";print_r(json_decode($result));die;
        if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result);
    }


    public function edit_ride(){
        if(!empty($this->input->post())){
         $booking_id = $this->input->post('booking_id');
         
         $dept_data = $this->input->post();
         unset($dept_data['booking_id']);

         $customername = $this->input->post('customername');
         $phonenum = $this->input->post('phonenum');
         $pickloc = $this->input->post('pickloc');
         $droploc = $this->input->post('droploc');
         $date = $this->input->post('date');
         $time = $this->input->post('time');
         $vehicletype = $this->input->post('vehicletype');

         $pick_location[] = array("address"=>$pickloc,"latitude"=>"","longitude"=>"");

         $pic = json_encode($pick_location);

         $drop_location[] = array("address"=>$droploc,"latitude"=>"","longitude"=>"");

         $drop = json_encode($drop_location);

         $dept_data=array(
            'user_id'=>$customername,
            'phonenum'=>$phonenum,
            'pick_location'=>$pic,
            'drop_location'=>$drop,
            'pick_date'=>$date,
            'pick_time'=>$time,
            'vehicle_type'=>$vehicletype
         );
        
          $this->load->model('AppointmentModel');
          

          if($this->AppointmentModel->updateAppointment($dept_data,$booking_id)){
              $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissable" style="color: #000;background-color: #89e6d4;border-color: #52dfc3;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong> Success ! </strong> Ride Add successfully</div>');
                 redirect('Ride');
          }else{
               $this->session->set_flashdata('error', '<div class="alert alert-warning alert-dismissable" style="color: #000;background-color: #89e6d4;border-color: #52dfc3;">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Error ! </strong> Ride failed  to Add</div>');

               redirect('Ride/add_ride');
          }



         // if($this->AppointmentModel->updateAppointment($dept_data,$dept_id)){
         //  redirect('Ride');
         // }
        }
        else { 
            $booking_id = $this->uri->segment(3);
            $where = array('id'=> $booking_id);        

            $data['booking'] = $this->AppointmentModel->singleRowdata($where,'booking');
            $data['vehicles'] = $this->Model->select('vehicle_category');
            $id = $this->uri->segment(3);
            $data['App'] = $this->AppointmentModel->getAppointmentById($id);
            
            
            $this->load->view('common/header');
            $this->load->view('common/sidebar');
            $this->load->view('ride/edit_ride',$data);
            $this->load->view('common/footer');  
    }
    }


    public function Status(){
        $appointment_id = $this->uri->segment(3);
        $status_id = $this->uri->segment(4);

        $this->db->where('appointment_id',$appointment_id);
        $this->db->update('Appointment',array('status'=>$status_id));
        
        redirect('Ride');

    }

    public function delete_appointment(){
        $id = $this->uri->segment(3);
        $data['Doctor_data'] = $this->AppointmentModel->deleteAppointmentById($id);
        redirect('Ride');
    }


    public function route_map($id){
        $doctor_id = $this->uri->segment(3);
        $where  = array('id' => $id, );
        $data['booking'] = $this->Model->singleRowdata($where,'booking');

        $users = $this->DoctorModel->getLocationList($id);

        $markers = [];
        $infowindow = [];
 
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
        $this->load->view('ride/route_map',$data);
        //$this->load->view('common/footer');  
    }



     public function ongoingRides() {

        $where  = array('is_status' => 1); 
        $data['booking'] = $this->Model->selectdata('booking',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('ride/ongoing_ride',$data);
        $this->load->view('common/footer'); 
    }

     public function cancelledRides() {

        $where  = array('is_status' => 2); 
        $data['booking'] = $this->Model->selectdata('booking',$where);
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('ride/cancel_ride',$data);
        $this->load->view('common/footer'); 
    }




    

}