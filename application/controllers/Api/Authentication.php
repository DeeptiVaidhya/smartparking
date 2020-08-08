<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Authentication extends REST_Controller {

    public function __construct() 
    { 
    	 
        parent::__construct();

        // Load the user model
        $this->load->library('session');
        $this->load->model('Api/LoginApiModel');
        
    }
    
    public function login_old_post() 
    {
        
        $email = $this->post('email');
        $password = $this->post('password');
        $phone_number = $this->post('phone_number');
        $login_type = $this->post('login_type');
        $firebase_token = $this->post('firebase_token');
        
        if((!empty($email)||!empty($phone_number)) && !empty($login_type))
        {
            if(!empty($phone_number))
            {
             $con = array('phone_number' => $phone_number,'password' => $password);
            }
            else 
            {
             $con = array('email' => $email,'password' => $password);
            }
             
             $user = $this->LoginApiModel->getRows($con,$login_type,$firebase_token);
             unset($user['data']->password);
             // unset($user['data']->username);
            if(!empty($user['data']))
            {
                     $this->response([
                    'status' => TRUE,
                    'message' => $user['message'],
                    'loggged_in_as'=>$user['logged_as'],
                    'data' => $user['data']
                ], REST_Controller::HTTP_OK);

            }
            else
            {
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response([
                    'status' => TRUE,
                    'message' => $user['message'],
                    
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }

        else
        {
            // Set the response and exit
            $this->response([
            'status' => FALSE,
            'message' => "Provide email and password.",

            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


     public function login_post() 
    {  
        $email = $this->post('email');
        $password = $this->post('password');
        $phone_number = $this->post('phone_number');
        $login_type = $this->post('user_type');
        
        if(!empty($phone_number) && !empty($login_type))
        {
            if(!empty($phone_number))
            {
             $con = array('phone_number' => $phone_number);
            }
            
             $user = $this->LoginApiModel->getRows($con,$login_type);
             unset($user['data']->password);
             // unset($user['data']->username);
            if(!empty($user['data']))
            {
                     $this->response([
                    'status' => TRUE,
                    'message' => $user['message'],
                    'loggged_in_as'=>$user['logged_as'],
                    'data' => $user['data']
                ], REST_Controller::HTTP_OK);

            }

            else
            {
                // Set the response and exit
                //BAD_REQUEST (400) being the HTTP response code
                $this->response([
                    'status' => TRUE,
                    'message' => $user['message'],
                    
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => "Provide email and password.",
               
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public function generateOtp_post(){
        $phone_number = $this->post('phone');
        $forget_type  = $this->post('user_type');
        $user = $this->LoginApiModel->checkPhoneNumber($forget_type,$phone_number);
        if(!$user)
        {
            $this->response([
            'status' => FALSE,
            'message' => 'Phone number not Exist.',
           ], REST_Controller::HTTP_OK);    
        }
        else 
        {
            // $this->session->set_userdata('mob',$phone_number);
            $otp = rand(1000, 9999);
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, "http://trans.smsfresh.co/api/sendmsg.php?user=Live7update&pass=123456&sender=LIVUPD&phone=".$phone_number."&text=".$otp."&priority=ndnd&stype=normal"); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $output = curl_exec($ch); 
            curl_close($ch); 
            $array_data = array('otp'=>$otp,"Phone"=>$phone_number,"type"=>$forget_type);
            $updateOtp = $this->LoginApiModel->UpdateOtp($array_data);


            if ($updateOtp) {
                $data = array("Phone"=>$phone_number);
                $checkOtp = $this->LoginApiModel->checkNewOtp($data,$forget_type);


                $this->response([
                'status' => TRUE,
                'message' => 'Your Otp is Valid for 60 seconds',
                'user_id' => $checkOtp->id,
                'otp' =>$checkOtp->otp,
                'phone_number'=>$checkOtp->phone_number,
               ], REST_Controller::HTTP_OK); 
            }else{
                 $this->response([
                'status' => FALSE,
                'message' => 'Failed',
                
               ], REST_Controller::HTTP_OK); 
            }
            
        }
       
    }

    public function otpVarification_post(){
        
        $otp1 = $this->post('otp');
        $phone_number = $this->post('mobile');
        $forget_type  = $this->post('user_type');
        $fcm_token   = $this->post('fcm_token');

        if($forget_type == 2){
            $table = 'Doctors';
          }
          else {
            $table = 'Patients';
          }
        
        $query = $this->db->query("SELECT id, username, dob, gender, address, image, phone_number,status,Firebase_token,otp FROM $table where phone_number= '".$phone_number."' ");

        $row = $query->row();

        $session = $this->session->userdata('session_'.$otp1);
        if(!empty($row->otp))
        {
            
            if($forget_type==1){
                $query = $this->db->query("SELECT id, username, dob, gender, address, image, phone_number,status,Firebase_token,otp FROM Patients where phone_number= $phone_number ");
                $row = $query->row();

                $whereData  = array('id' =>$row->id , );
                $updateData  = array('Firebase_token' =>$fcm_token , );
                $update_token = $this->LoginApiModel->update('Patients',$updateData,$whereData);

                if ($update_token) {

                    $query1 = $this->db->query("SELECT id, username, dob, gender, address, image, phone_number,status,Firebase_token,otp FROM Patients where phone_number='".$phone_number."'");
                    $row1 = $query1->row();

                    $this->response([
                        'status' => TRUE,
                        'data'=>$row,
                        'message' => 'Correct Otp',
                    ], REST_Controller::HTTP_OK); 
                    $this->session->unset_userdata('session_'.$phone_number);
                    
                }else{
                     $this->response([
                        'status' => FALSE,
                        'message' => 'Fcm token not update ',
                    ], REST_Controller::HTTP_BAD_REQUEST); 
                }
               
            }else if($forget_type==2){

               $query = $this->db->query("SELECT `id`, `username`, `phone_number`,`otp`,`Vehicle_Type`,`vehicle_number`,image FROM `Doctors` WHERE `phone_number` = $phone_number ");
                $row = $query->row(); 

                $whereData  = array('id' =>$row->id , );
                $updateData  = array('Firebase_token' =>$fcm_token , );
                $update_token = $this->LoginApiModel->update('Doctors',$updateData,$whereData);

                if ($update_token) {

                    $query11 = $this->db->query("SELECT id, username, phone_number,otp,Vehicle_Type,vehicle_number,Firebase_token,image FROM Doctors where phone_number='".$phone_number."'");
                    $row11 = $query11->row(); 

                    $this->response([
                        'status' => TRUE,
                        'data'=>$row11,
                        'message' => 'Correct Otp',
                    ], REST_Controller::HTTP_OK); 
                    $this->session->unset_userdata('session_'.$phone_number);
                }else{

                    $this->response([
                        'status' => FALSE,
                        'message' => 'Fcm token not update ',
                    ], REST_Controller::HTTP_BAD_REQUEST); 

                }

                
            }else{
              $row = array();
              $this->response([
                'status' => TRUE,
                'data'=>$row,
                'message' => 'Correct Otp',
                ], REST_Controller::HTTP_OK); 
                $this->session->unset_userdata('session_'.$phone_number);
            }
            
        }
        else 
        {
            $this->response([
            'status' => FALSE,
            'message' => 'Otp Not Found',
            ], REST_Controller::HTTP_BAD_REQUEST); 
        }
    }



    /******** API FOR  Check user otp *********/ 
    public function verifyBookingOtp_post() {
        $booking_id = $this->post('booking_id');
        $driver_id = $this->post('driver_id');
        $mobile  = $this->post('mobile');
        $otp  = $this->post('otp'); 

        $WhereID  = array('id'=> $booking_id,'driver_id'=>$driver_id,'is_status'=>1);
        $checkBookingData= $this->LoginApiModel->singleRowdata($WhereID,'booking');

        if ($checkBookingData) {
            $whereDataType  = array('booking_id'=> $booking_id, 'reciver_number' =>$mobile, 'otp'=>$otp);
            $checkBooking = $this->LoginApiModel->singleRowdata($whereDataType,'booking_drop_location');

            if($checkBooking){
                $data = array(
                   'is_booking_verify' => '1',
                   'otp' => '0'
                );
                $show_status = $this->LoginApiModel->update('booking_drop_location',$data,$whereDataType);
                if ($show_status) {

                    $where =  array(
                       'reciver_number' => $checkBooking->reciver_number,
                       'booking_id' => $booking_id
                    );

                    $newResult= $this->LoginApiModel->singleRowdata($where,'booking_drop_location');
                    if(!empty($newResult))
                    {

                        $this->response([
                            'status'  => TRUE,
                            'message' => 'Otp Verify',
                            'data'    => $newResult
                        ], REST_Controller::HTTP_OK);

                    }

                    else
                    {
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Failed to Verify',
                            
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                }else{
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Failed to Verify',
                        
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }else{
                 $this->response([
                        'status' => FALSE,
                        'message' => 'Incorrect Otp',
                        
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
             $this->response([
            'status' => FALSE,
            'message' => 'Please Accept Booking First',
           ], REST_Controller::HTTP_OK);   
        }

        
        
    }

    public function changePassword_post()
    {
        $phone_number = $this->post('phone_number');
        $user_type    = $this->post('user_type');
        $new_password = $this->post('password');

        if($this->LoginApiModel->changePassword($phone_number,$new_password,$user_type))
        {
            $this->response([
            'status' => TRUE,
            'message' => 'Password Changed',
            ], REST_Controller::HTTP_OK); 
        }
        else 
        {
            $this->response([
            'status' => FALSE,
            'message' => 'Unknown Phone Number',
            ], REST_Controller::HTTP_OK);
        }

    }

   
  
    public function User_registration_post() 
    {      
                                                 
        $username = strip_tags($this->post('username'));
        $email = strip_tags($this->post('email'));
        $password = strip_tags($this->post('password'));
        $dob = strip_tags($this->post('dob'));
        $gender = strip_tags($this->post('gender'));
        $address = strip_tags($this->post('address'));
        $phone_number = strip_tags($this->post('phone_number'));
        $fcm_token = strip_tags($this->post('fcm_token'));



        
        $full_name = explode(' ',$username);
        $first_name='';
        $last_name='';
        empty($full_name[0])?$first_name='':$first_name=$full_name[0];
        empty($full_name[1])?$last_name='':$last_name=$full_name[1];

        // $status = strip_tags($this->post('status'));
        if(!empty($username) && !empty($phone_number))
        {

            $userData = array(
            'username' => $username,
            //'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'dob' =>$dob,
            'gender'=>$gender,
            'address' =>$address,
            'phone_number'=>$phone_number,
            'Firebase_token'=>$fcm_token,
            
            );  

          
           /* if($this->LoginApiModel->checkEmail($userData['email'],1))
            {
                $this->response([
                'status' => FALSE,
                'message' => 'Email is already exist ',
                ], REST_Controller::HTTP_OK);
            }
            else*/ if($this->LoginApiModel->checkContact($userData['phone_number'],1))
            {
                $this->response([
                'status' => FALSE,
                'message' => 'phone number is already exist ',
                ], REST_Controller::HTTP_OK); 
            }

            else 
            {
                $insert = $this->LoginApiModel->insert_patient($userData);
                if(!$insert['status'])
                {
                    $this->response([
                    'status' => TRUE,
                    'message' => 'User added successfully ',
                    ], REST_Controller::HTTP_OK);
                }
                else
                {
                    $this->response([
                    'status' => FALSE,
                    'message' => 'Unable to add',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }  

        }
        else 
        {
        $this->response([
        'status' => FALSE,
        'message' => 'Provide proper information ',
        ], REST_Controller::HTTP_OK);
        }       
    }

                
  
    public function driver_registration_post(){
        $username = strip_tags($this->post('username'));
        $email = strip_tags($this->post('email'));
        $password = strip_tags($this->post('password'));
        $dob = strip_tags($this->post('dob'));
        $gender = strip_tags($this->post('gender'));
        $Vehicle_Type = strip_tags($this->post('Vehicle_Type'));
        $address = strip_tags($this->post('address'));
        $phone_number = strip_tags($this->post('phone_number'));
        $fcm_token = strip_tags($this->post('fcm_token'));
        $user_type = strip_tags($this->post('user_type'));
        $vehicle_number = strip_tags($this->post('vehicle_number'));
        $full_name = explode(' ',$username);
        $first_name='';
        $last_name='';
        empty($full_name[0])?$first_name='':$first_name=$full_name[0];
        empty($full_name[1])?$last_name='':$last_name=$full_name[1];

        if(!empty($username) && !empty($phone_number))
        {

            date_default_timezone_set("Asia/Calcutta");
        $datetime = date('Y-m-d H:i:s');
        
            $userData = array(
                'username'=>$username,
              //  'first_name' => ($first_name)?$first_name:"",
               // 'last_name' => ($last_name)?$last_name:"",
                'email' => ($email)?$email:"",
                'password' => ($password)?$password:"",
                'dob' =>($dob)?$dob:"",
                'gender'=>($gender)?$gender:"",
                'address' =>($address)?$address:"",
                'phone_number'=>$phone_number,
                'Firebase_token' =>$fcm_token,
                'user_type' => $user_type,
                'Vehicle_Type'=>$Vehicle_Type,
                'vehicle_number' => $vehicle_number,
                'joning_date' => $datetime

            );  

            if($this->LoginApiModel->checkContact($userData['phone_number'],2))
            {
                $this->response([
                'status' => FALSE,
                'message' => 'phone number is already exist ',
                ], REST_Controller::HTTP_OK); 
            }

            else 
            {
                $insert = $this->LoginApiModel->insert_doctor($userData);
                if(!$insert['status'])
                {
                    $this->response([
                    'status' => TRUE,
                    'message' => 'User added successfully ',
                    ], REST_Controller::HTTP_OK);
                }
                else
                {
                    $this->response([
                    'status' => FALSE,
                    'message' => 'Unable to add',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }  

        }
        else 
        {
        $this->response([
        'status' => FALSE,
        'message' => 'Provide proper information ',
        ], REST_Controller::HTTP_OK);
        }

    }  




    public function editProfile_post(){
        $user_type = strip_tags($this->post('user_type'));
        $profile_id = strip_tags($this->post('profile_id'));
        $username = strip_tags($this->post('username'));
        $address = strip_tags($this->post('address'));
        $gender = strip_tags($this->post('gender')); 
        $phone_number = strip_tags($this->post('phone_number'));
        $email = strip_tags($this->post('email'));
        $dob = strip_tags($this->post('dob'));
    if(!empty($_FILES['attachment_image']['name'])){
        $config['upload_path'] = './uploads/user_profiles/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $_FILES['attachment_image']['name'];
        
        //Load upload library and initialize configuration
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
        
        if($this->upload->do_upload('attachment_image')){
            $uploadData = $this->upload->data();
            $attachment_image = $uploadData['file_name'];
            $data['attachment_image'] = $attachment_image;
        }else{
            $attachment_image = '';
        }
    }
    else{
        $attachment_image = '';
    }
   
  // requires php5
  /*define('UPLOAD_DIR', './uploads/activity_photos/');
  $img = $_POST['photo'];
  $img = str_replace('data:image/png;base64,', '', $img);
  $img = str_replace(' ', '+', $img);
  $data = base64_decode($img);
  $file = UPLOAD_DIR . uniqid() . '.png';
  $success = file_put_contents($file, $data);
   $success ? $file : 'Unable to save the file.';*/



    if(!empty($profile_id) )

    {


    $profile_data = array('id'=>$profile_id,'username'=>$username,'address'=>$address,'gender'=>$gender,'phone_number'=>$phone_number,'email'=>$email,'dob'=>$dob,'image'=>$attachment_image);

    

      $activity = $this->LoginApiModel->editProfile($user_type,$profile_id,$profile_data);



      if($activity)

      {

        $this->response([

          'status' => TRUE,

          'message' => 'Profile updated successfully',

          'all_activities'=>$profile_data

          ], REST_Controller::HTTP_OK);

      }



      else 

      {

        $this->response([

          'status' => FALSE,

          'message' => 'Something went wrong',

          ], REST_Controller::HTTP_OK);

      }

      }

       else 

        {

        $this->response([

        'status' => FALSE,

        'message' => 'Provide proper information ',

        ], REST_Controller::HTTP_OK);

        }     

    }


 /*Booking Api*/
    public function addBooking_post(){
       	  $id = $this->db->insert_id();
       	  $pick_location = $this->post('pick_location');
       	  $drop_location = $this->post('drop_location');
       	  $vehicle_type = $this->post('vehicle_type');
       	  $pick_time = $this->post('pick_time');
       	  $pick_date = $this->post('pick_date');
       	  $stuf_name = $this->post('stuf_name');
       	  //$floor = $this->post('floor');
       	  $packaging_required = $this->post('packaging_required');
       	  $amount = $this->post('amount');
       	  $payment_type = $this->post('payment_type');
       	  $distance = $this->post('distance');
       	  $receiver_name = $this->post('receiver_name');
       	  $receiver_mobilenumber = $this->post('receiver_mobilenumber');
          $lift_facility = $this->post('lift_facility');
          $landmark = $this->post('landmark');
          $description  = $this->post('description');
          $pick_date_time = $this->post('pick_date_time');
          $user_id = $this->post('user_id');

          $pick_up_letter = $this->post('pick_up_letter');

          $pick_letter_time = $this->post('pick_letter_time');

          $booking_date =  date('Y-m-d');
          //$date_time =  date('Y-m-d H:i:s');
          $booking_time =  date('H:i:s');


       	  $data = array(
            'id'=>$id,
            'pick_location'=>$pick_location,
            'drop_location'=>$drop_location,
            'vehicle_type'=>$vehicle_type,
            'pick_time'=>$pick_time,
            'pick_date'=>$pick_date,
            'stuf_name'=>$stuf_name,
            'packaging_required'=>$packaging_required,
            'amount'=>$amount,
            'payment_type'=>$payment_type,
            'distance'=>$distance,
            'receiver_name'=>$receiver_name,
            'receiver_mobilenumber'=>$receiver_mobilenumber,
            'lift_facility'=>$lift_facility,
            'landmark'=>$receiver_mobilenumber,
            'description'=>$description,
            'pick_date_time'=>$pick_date_time,
            'user_id'=>$user_id,'booking_date'=>$booking_date,
            'booking_time' => $booking_time,
            'pick_up_letter' => $pick_up_letter,
            'date_time'=>$pick_letter_time
        );

       	  /*if(!empty($_FILES['attachment_image']['name'])){
                $config['upload_path'] = './uploads/user_profiles/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['attachment_image']['name'];
                
                //Load upload library and initialize configuration
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload('attachment_image')){
                    $uploadData = $this->upload->data();
                    $attachment_image = $uploadData['file_name'];
                    $data['attachment_image'] = $attachment_image;
                }else{
                    $attachment_image = '';
                }
          }
          else{
                $attachment_image = '';
          }*/



       	  $id = $this->LoginApiModel->user_booking($data);

       	  $booking_data = array('id'=>$id,'pick_location'=>$pick_location,'drop_location'=>$drop_location,'vehicle_type'=>$vehicle_type,'pick_time'=>$pick_time,'pick_date'=>$pick_date,'stuf_name'=>$stuf_name,'packaging_required'=>$packaging_required,'amount'=>$amount,'payment_type'=>$payment_type,'distance'=>$distance,'receiver_name'=>$receiver_name,'receiver_mobilenumber'=>$receiver_mobilenumber,'lift_facility'=>$lift_facility,'landmark'=>$receiver_mobilenumber,'description'=>$description,'pick_date_time'=>$pick_date_time,'user_id'=>$user_id,'date_time');

       	  if($id){
                 $booking_id = $this->db->insert_id();

                 $drop_loca = json_decode($drop_location);
                 
                 foreach ($drop_loca as $key => $value) {
                    $otp = rand(1000, 9999);
                    $drop_location  = array(
                        'booking_id' => $booking_id,
                        'address'    => $value->address,
                        'latitude'    => $value->latitude,
                        'longitude' => $value->longitude,
                        'reciver_name' => $value->reciver_name,
                        'reciver_number' => $value->reciver_number,
                        'otp' => $otp 
                    );

                    $insert_data =  $this->LoginApiModel->insert($drop_location,'booking_drop_location');
                    if($insert_data) {

                        $newmobile = $value->reciver_number;


                        $sendOtp = $this->twillomessageSend($otp, $newmobile); 


                        //$sendOtp = $this->messageSend($otp, $newmobile); 
                    }

                 }

                $pick_loca = json_decode($pick_location);
                foreach ($pick_loca as $key => $value) {
                   $latitude   = $value->latitude;
                   $longitude  =  $value->longitude;
                }
                $getDoctors = $this->LoginApiModel->selectnearbyDriver($latitude,$longitude);


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
                        $currentData =  new DateTime(date("Y-m-d H:i:s"));
                        //echo '<br/>'.$date_time; 
                        $interval = $currentData->diff(new DateTime($pick_letter_time));
                        //echo $interval->format('%i');die;
                        if($interval->format('%i') == 30){
                            //die("hjhjh");
                            $this->push_notification_android($device_token, $message, $title, $type ='');
                        
                         $this->firebase->insertBookingMessage(
                            array(
                               'paitent_id'=>$patient_id,
                               'doctor_id'=>$doctor_id,
                               'booking_id'=>$booking_id,
                               'vehicle_type'=>$vehicle_type,
                               'message'=>'You have received a New Booking Request',
                               'title'=>'New Booking',
                               'date_time' => $datetime 
                            )
                        );
                        }
                        if($pick_up_letter ==0){
                            $this->push_notification_android($device_token, $message, $title, $type ='');
                        
                            $this->firebase->insertBookingMessage(
                            array(
                               'paitent_id'=>$patient_id,
                               'doctor_id'=>$doctor_id,
                               'booking_id'=>$booking_id,
                               'vehicle_type'=>$vehicle_type,
                               'message'=>'You have received a New Booking Request',
                               'title'=>'New Booking',
                               'date_time' => $datetime 
                            )
                        );
                        }
                       
                    }
                }
                
	       	  $this->response([

	          'status' => TRUE,

	          'message' => 'Booking successfully',

	          'all_activities'=>$booking_data,
              'sendOtp' => $sendOtp

	          ], REST_Controller::HTTP_OK);
       	  }
       	  else{
       	  	$this->response([

	        'status' => FALSE,

	        'message' => 'Something went wrong',

	        ], REST_Controller::HTTP_OK);
       	  }

    }

    


    public function getBookingamount_post(){
       	  $pick_location = $this->post('pick_location');
       	  $drop_location = $this->post('drop_location');
       	  $vehicle_type = $this->post('vehicle_type');
       	  $pick_time = $this->post('pick_time');
       	  $pick_date = $this->post('pick_date');
       	  $stuf_name = $this->post('stuf_name');

       	  $packaging_required = $this->post('packaging_required');
       	  $amount = $this->post('amount');
       	  $payment_type = $this->post('payment_type');
       	  $distance = $this->post('distance');
       	  $receiver_name = $this->post('receiver_name');
       	  $receiver_mobilenumber = $this->post('receiver_mobilenumber');
          $lift_facility = $this->post('lift_facility');
          $landmark = $this->post('landmark');
          $description  = $this->post('description');
          $pick_date_time = $this->post('pick_date_time');
          $user_id = $this->post('user_id');

       	  $data = array('pick_location'=>$pick_location,'drop_location'=>$drop_location,'vehicle_type'=>$vehicle_type,'pick_time'=>$pick_time,'pick_date'=>$pick_date,'stuf_name'=>$stuf_name,'packaging_required'=>$packaging_required,'amount'=>$amount,'payment_type'=>$payment_type,'distance'=>$distance,'receiver_name'=>$receiver_name,'receiver_mobilenumber'=>$receiver_mobilenumber,'lift_facility'=>$lift_facility,'landmark'=>$receiver_mobilenumber,'description'=>$description,'pick_date_time'=>$pick_date_time,'user_id'=>$user_id);


       	  if($data){
	       	  $this->response([

	          'status' => TRUE,

	          'message' => 'Booking Amount',

	          'all_activities'=>$data

	          ], REST_Controller::HTTP_OK);
       	  }
       	  else{
       	  	$this->response([

	        'status' => FALSE,

	        'message' => 'Something went wrong',

	        ], REST_Controller::HTTP_OK);
       	  }

    }

    public function bookingAmount_post(){

          $vehicle_type = $this->post('vehicle_type');
          $distance     = $this->post('distance');
          $promocode_amount    = $this->post('promocode_amount');
         
          $whereType  = array('id' => $vehicle_type);

          $getVehicle  = $this->LoginApiModel->singleRowdata($whereType,'vehicle_category');

          if($getVehicle){

              $price_per_distance = $getVehicle->price_per_distance;
              $dis_calculate_amt  = floatval($price_per_distance) * floatval($distance);

             $comisionAmt = $getVehicle->commission;

             $couponAmt = $promocode_amount;

             $total = floatval($dis_calculate_amt) + floatval($comisionAmt) - floatval($couponAmt);

             $checkAmt  = array(

                'price_per_distance' => $dis_calculate_amt,
                'commission_amount'  => $comisionAmt,
                'coupon_amount'      => $couponAmt,
                'total'              => $total

            );

              $this->response([

              'status' => TRUE,

              'message' => 'Booking Amount',

              'calculat_amount'=>$checkAmt

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Something went wrong',

            ], REST_Controller::HTTP_OK);
          }

    }

    public function editBooking_post(){
       	  $id = $this->post('id');
       	  $pick_location = $this->post('pick_location');
       	  $drop_location = $this->post('drop_location');
       	  $vehicle_type = $this->post('vehicle_type');
       	  $pick_time = $this->post('pick_time');
       	  $pick_date = $this->post('pick_date');
       	  $stuf_name = $this->post('stuf_name');
       	  $floor = $this->post('floor');
       	  $packaging_required = $this->post('packaging_required');
       	  $amount = $this->post('amount');
       	  $payment_type = $this->post('payment_type');
       	  $distance = $this->post('distance');
       	  $receiver_name = $this->post('receiver_name');
       	  $receiver_mobilenumber = $this->post('receiver_mobilenumber');


       	  $data = array('id'=>$id,'pick_location'=>$pick_location,'drop_location'=>$drop_location,'vehicle_type'=>$vehicle_type,'pick_time'=>$pick_time,'pick_date'=>$pick_date,'stuf_name'=>$stuf_name,'floor'=>$floor,'packaging_required'=>$packaging_required,'amount'=>$amount,'payment_type'=>$payment_type,'distance'=>$distance,'receiver_name'=>$receiver_name,'receiver_mobilenumber'=>$receiver_mobilenumber);

       	  if(!empty($_FILES['attachment_image']['name'])){
                $config['upload_path'] = './uploads/user_profiles/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['attachment_image']['name'];
                
                //Load upload library and initialize configuration
                $this->load->library('upload',$config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload('attachment_image')){
                    $uploadData = $this->upload->data();
                    $attachment_image = $uploadData['file_name'];
                    $data['attachment_image'] = $attachment_image;
                }else{
                    $attachment_image = '';
                }
          }
          else{
                $attachment_image = '';
          }


          $data = array('id'=>$id,'pick_location'=>$pick_location,'drop_location'=>$drop_location,'vehicle_type'=>$vehicle_type,'pick_time'=>$pick_time,'pick_date'=>$pick_date,'stuf_name'=>$stuf_name,'floor'=>$floor,'packaging_required'=>$packaging_required,'amount'=>$amount,'payment_type'=>$payment_type,'distance'=>$distance,'attachment_image'=>$attachment_image,'receiver_name'=>$receiver_name,'receiver_mobilenumber'=>$receiver_mobilenumber);

       	  $id = $this->LoginApiModel->editBooking($data,$id);

       	  if($id){
	       	  $this->response([

	          'status' => TRUE,

	          'message' => 'Booking updated successfully',


	          ], REST_Controller::HTTP_OK);
       	  }
       	  else{
       	  	$this->response([

	        'status' => FALSE,

	        'message' => 'Something went wrong',

	        ], REST_Controller::HTTP_OK);
       	  }

    }

    public function getBooking_get(){
          
          $booking_data = $this->LoginApiModel->getBooking();

          if($booking_data){
              $this->response([

              'status' => TRUE,

              'message' => 'Booking List',

              'all_activities'=>$booking_data

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Unable to find the list of booking',

            ], REST_Controller::HTTP_OK);
          }

    }

    public function getProfile_post(){

          $user_type = strip_tags($this->post('user_type'));

          $profile_id = strip_tags($this->post('profile_id'));
          $user_data = $this->LoginApiModel->getprofile($profile_id,$user_type);

          if($user_data){
              $this->response([

              'status' => TRUE,

              'message' => 'User List',

              'all_activities'=>$user_data

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Unable to find the list of users',

            ], REST_Controller::HTTP_OK);
          }

    }
    
    
    
    public function privacypolicy_get(){

          $this->db->select("*");
        $this->db->from('privacy_policy');
       $data = $this->db->get()->row();

          if($data){
              $this->response([

              'status' => TRUE,

              'message' => 'Privacy Policy.',

              'data'=>$data

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Privacy Policy.',

            ], REST_Controller::HTTP_OK);
          }

    }



    public function getvehiclebycatid_post(){

          $cat_id = strip_tags($this->post('cat_id'));


          $user_data = $this->LoginApiModel->getvehiclebycatid($cat_id);

          if($user_data){
              $this->response([

              'status' => TRUE,

              'message' => 'Vehicle List',

              'all_vehicle'=>$user_data

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Unable to find the list of users',

            ], REST_Controller::HTTP_OK);
          }

    }


    public function getBookingbyuserid_post(){
          $user_id = $this->post('user_id');
          $booking_data = $this->LoginApiModel->getBookingByUserid($user_id);
          if($booking_data){
          foreach ($booking_data as $key) {
            
            $whereId  = array('booking_id' =>$key['id']);

            $checkBookingLocation  = $this->LoginApiModel->selectdata('booking_drop_location',$whereId);
           
           $date = $key['date_time'];

	       $post_date = date('Y-m-d', strtotime($date));          

	       $time= $this->get_time($post_date);

            //$BookingLocation = array('drop_location'=>$checkBookingLocation);

          	$data[] = array(
    		'id'            => $key['id'],
    		'pick_location' => $key['pick_location'],
    		'drop_location' => json_encode($checkBookingLocation),
    		'pick_time'     => $key['pick_time'],
    		'pick_date'     => $key['pick_date'],
    		'phonenum'      => $key['phonenum'],
    		'stuf_name'     => $key['stuf_name'],
    		'packaging_required' => $key['packaging_required'],
    		'amount'           => $key['amount'],
    		'payment_type'     => $key['payment_type'],
    		'distance'         => $key['distance'],
    		'attachment_image' => $key['attachment_image'],
    		'receiver_name'    => $key['receiver_name'],
    		'receiver_mobilenumber' => $key['receiver_mobilenumber'],
    		'lift_facility' => $key['lift_facility'],
    		'landmark' => $key['landmark'],
    		'description' => $key['description'],
    		'pick_date_time' => $key['pick_date_time'],
    		'user_id' => $key['user_id'],
    		'driver_id' => $key['driver_id'],
    		'driver_location' => $key['driver_location'],
    		'latitude' => $key['latitude'],
    		'longnitue' => $key['longnitue'],
    		'status' => $key['status'],
    		
    		'is_status' => $key['is_status'],
    		'customer_name'=> $key['customer_name'],
    		'username'=> $key['username'],
    		'phone_number'=> $key['phone_number'],
    		'vehicle_type'=> $key['vehicle_type'],
    		'image'=> $key['image'],
    		'time' => $time,
            'is_booking_verify' => $key['is_booking_verify'],
    		'date_time' => $key['date_time'],

    		);
          }


              $this->response([

              'status' => TRUE,

              'message' => 'Booking List',

              'all_activities'=>$data

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Unable to find the list of booking',

            ], REST_Controller::HTTP_OK);
          }

    }



    public function deleteBooking_post(){

          $id = $this->post('id');
          
          $data = $this->LoginApiModel->deleteBooking($id);

          if(!empty($data)){
              $this->response([

              'status' => TRUE,

              'message' => 'Delete booking successfully',

              'data'=>$data

              ], REST_Controller::HTTP_OK);
          }
          else{
            $this->response([

            'status' => FALSE,

            'message' => 'Unable to find booking',

            ], REST_Controller::HTTP_OK);
          }

    }
    /*Booking Api*/


    /*Notification API*/
    function push_notification_android($device_id,$message,$message_info='', $type =''){
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAAGQ3N_Jk:APA91bEnY-euls8dXy2Tpb4Ed2lcd-RFoZsMhikgEoArO5Fnj2SrLag3qtOyi1t4Ef47xMCEdAEwZ1VV2uze9y-K-GD7uvkW1G2fGFqkrZd5VtP-_x7HzTwcR90RgCie1WxmXaLLMlt9';

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

        //print_r($result);die;
        return json_decode($result);
    }
    
    
    function push_notificationUser_android($device_id,$message,$message_info='', $type =''){
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAAyVLav9A:APA91bFv0ZEXr51q1fUPlWwi7p_oHaZWc9wX8arKQBg7aHzgkmPjlZLxCPalen3ybWI0ZYasPJclWWSy0p_z4qaNsBSEuDyT-6K5uigfG6YV3Mu0g0zdOu15Aw9_8aysnqWjkJgllG0W';

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

        //print_r($result);die;
        return json_decode($result);
    }
   public function getBookingByVehicle_post()
  {
     
    $vehicle_type = strip_tags($this->post('vehicle_type'));
    $driver_id = strip_tags($this->post('driver_id'));

    $whereId  = array('id' => $driver_id);
    $driverData = $this->LoginApiModel->singleRowdata($whereId,'Doctors');

    if ($driverData) {

       $joning_date =$driverData->joning_date;
    }
    
    $booking_data = $this->LoginApiModel->getBookingByVehicleType($vehicle_type);

    foreach ($booking_data as $key ) {

    	$whereData  = array('id' => $key['driver_id']);
        $driverdata = $this->LoginApiModel->singleRowdata($whereData,'Doctors');

        if ($driverdata) {
        	$vehicle_number  = $driverdata->vehicle_number;
        }else{
        	$vehicle_number = '';
        }

        $date = $key['date_time'];
        $post_date = date('Y-m-d', strtotime($date));          

        $time= $this->get_time($post_date);

    	$data[] = array(
    		'id'            => $key['id'],
    		'pick_location' => $key['pick_location'],
    		'drop_location' => $key['drop_location'],
    		'pick_time'     => $key['pick_time'],
    		'pick_date'     => $key['pick_date'],
    		'phonenum'      => $key['phonenum'],
    		'stuf_name'     => $key['stuf_name'],
    		'packaging_required' => $key['packaging_required'],
    		'amount'           => $key['amount'],
    		'payment_type'     => $key['payment_type'],
    		'distance'         => $key['distance'],
    		'attachment_image' => $key['attachment_image'],
    		'receiver_name'    => $key['receiver_name'],
    		'receiver_mobilenumber' => $key['receiver_mobilenumber'],
    		'lift_facility' => $key['lift_facility'],
    		'landmark' => $key['landmark'],
    		'description' => $key['description'],
    		'pick_date_time' => $key['pick_date_time'],
    		'user_id' => $key['user_id'],
    		'driver_id' => $key['driver_id'],
    		'driver_location' => $key['driver_location'],
    		'latitude' => $key['latitude'],
    		'longnitue' => $key['longnitue'],
    		'status' => $key['status'],
    		'date_time' => $key['date_time'],
    		'is_status' => $key['is_status'],
    		'customer_name'=> $key['customer_name'],
    		'username'=> $key['username'],
    		'phone_number'=> $key['phone_number'],
    		'vehicle_type'=> $key['vehicle_type'],
    		'image'=> $key['image'],
    		'vehicle_number' => $vehicle_number,
    		'time' => $time

    	);
    }

    if(!empty($data))
    {
     
      $this->response([
          'status' => TRUE,
          'message'=>'Booking list',
          'data'    =>$data 
        ], REST_Controller::HTTP_OK);
        
     

    }else{
       $this->response([
            'status' => FALSE,
            'message' =>'data not found',
            'data'    =>[]
        ], REST_Controller::HTTP_OK);
     
    }
  }


    public function driverBookingDetailsByid_post(){
      
      $driver_id   = strip_tags($this->post('driver_id'));
      $results    = $this->LoginApiModel->selectDriverBooking($driver_id);
      if ($results) {

	      	foreach ($results as $key ) {

	        $date = $key['date_time'];
	        $post_date = date('Y-m-d', strtotime($date));          

	        $time= $this->get_time($post_date);

            $whereId  = array('booking_id' =>$key['id']);

            $checkBookingLocation  = $this->LoginApiModel->selectdata('booking_drop_location',$whereId);




	    	$data[] = array(
	    		'id'            => $key['id'],
	    		'pick_location' => $key['pick_location'],
	    		'drop_location' => json_encode($checkBookingLocation),
	    		'pick_time'     => $key['pick_time'],
	    		'pick_date'     => $key['pick_date'],
	    		'phonenum'      => $key['phonenum'],
	    		'stuf_name'     => $key['stuf_name'],
	    		'packaging_required' => $key['packaging_required'],
	    		'amount'           => $key['amount'],
	    		'payment_type'     => $key['payment_type'],
	    		'distance'         => $key['distance'],
	    		'attachment_image' => $key['attachment_image'],
	    		'receiver_name'    => $key['receiver_name'],
	    		'receiver_mobilenumber' => $key['receiver_mobilenumber'],
	    		'lift_facility' => $key['lift_facility'],
	    		'landmark' => $key['landmark'],
	    		'description' => $key['description'],
	    		'pick_date_time' => $key['pick_date_time'],
	    		'user_id' => $key['user_id'],
	    		'driver_id' => $key['driver_id'],
	    		'driver_location' => $key['driver_location'],
	    		'latitude' => $key['latitude'],
	    		'longnitue' => $key['longnitue'],
	    		'status' => $key['status'],
	    		'date_time' => $key['date_time'],
	    		'is_status' => $key['is_status'],
	    		'customer_name'=> $key['customer_name'],
	    		'username'=> $key['username'],
	    		'phone_number'=> $key['phone_number'],
	    		'vehicle_type'=> $key['vehicle_type'],
	    		'image'=> $key['image'],
	    		'time' => $time

	    	);
	    	}


           $this->response([
                'status' => TRUE,
                'message'=>'Booking Details',
                'data'   =>$data 
            ], REST_Controller::HTTP_OK);
      }else{
           $this->response([
              'status' => FALSE,
              'message' =>' Data not found',
           
          ], REST_Controller::HTTP_OK);
      }   
      
  }



  public function bookingAcceptReject_post()
   {
     $driver_id    = strip_tags($this->post('driver_id'));
     $booking_id   = strip_tags($this->post('booking_id'));
     $status       = strip_tags($this->post('status'));

     
    if($status == 1)
    {
        $whereData  = array('id' => $booking_id);
        $data = $this->LoginApiModel->singleRowdata($whereData,'booking');
        if($data)
        {

        	$whereData  = array('driver_id' => $driver_id,'is_status'=>1);
            $checkDriverRide = $this->LoginApiModel->singleRowdata($whereData,'booking');

            if ($checkDriverRide) {
            	$this->response([
                   'status' => FALSE,
                   'message' =>'Unable To Accepted Because you are ongoing ride',	               
	            ], REST_Controller::HTTP_OK);
            	
            }else{
            	$whereId = array('id' => $booking_id);
	            $updateStatus = array(
	                'driver_id' => $driver_id,
	                'is_status' => 1
	            );           
	            if ($this->LoginApiModel->update('booking',$updateStatus,$whereId)) {

	                $checkBooking  = $this->db->query('SELECT * FROM booking WHERE id ="'.$booking_id.'" '  )->row();

	                if ($checkBooking) {
	                  $user_id = $checkBooking->user_id;
	                }
                    
	                $whereID = array('id'=> $driver_id);
	                $updateStaus  = array('available_staus' => 2);
	                $this->LoginApiModel->update('Doctors',$updateStaus,$whereID);

	                $title = "Booking Accepted";
	                $notification = "Your Booking is Accepted";
	                date_default_timezone_set("Asia/Calcutta");
	                $datetime = date('Y-m-d H:i:s');
	                $Firebase_token = $this->db->query('SELECT * FROM Patients WHERE id ="'.$checkBooking->user_id.'" '  )->row();
                    
                    $device_token = $Firebase_token->Firebase_token;
                    $this->push_notificationUser_android($device_token, $notification, $title, $type ='');
	                $data      = array(
	                	"appointment_id" => $booking_id,
	                	"doctor_id" => $driver_id,
	                	"title" =>$title,
	                	"notification"=>$notification,
	                	"date_time"=>$datetime,
	                	"user_id" => $user_id,
	                	"status"=>1
	                );
	                $results    = $this->LoginApiModel->insert($data,'Notification');

	              $this->response([
	                'status' => TRUE,
	                  'message' =>'Booking Accepted Successfully'
	              ], REST_Controller::HTTP_OK);
	            }else{
	              $this->response([
	                  'status' => FALSE,
	                  'message' =>'Unable To Accepted',
	               
	              ], REST_Controller::HTTP_OK);
	            }   	
            }
                    
        }
        else 
        {
          $this->response([
            'status' => TRUE,
            'message' => 'Data Not found'
          ], REST_Controller::HTTP_OK);
        }
     
    }

    else if ($status == 2)
    {
        $whereData  = array('id' => $booking_id);

        $data = $this->LoginApiModel->singleRowdata($whereData,'booking');
          if($data)
            {
              $where = array('booking_id' => $booking_id);
              $updateData  = array('is_status' => 1,);
              if ($this->LoginApiModel->update('booking_notificaion',$updateData,$where)) {
                  
                $checkBooking  = $this->db->query('SELECT * FROM booking WHERE id ="'.$booking_id.'" '  )->row();

                if ($checkBooking) {
                  $user_id = $checkBooking->user_id;
                }

                $whereBid = array('id' => $booking_id);
                $driver_id    = strip_tags($this->post('driver_id'));
                $updateBooking  = array('is_status' => 2,'driver_id'=> $driver_id);
                //print_r($updateBooking);die;
                $this->LoginApiModel->update('booking',$updateBooking,$whereBid);

                $title = "Booking Rejected";
                $notification = "Your Booking is Rejected";
                date_default_timezone_set("Asia/Calcutta");
                $Firebase_token = $this->db->query('SELECT * FROM Patients WHERE id ="'.$checkBooking->user_id.'" '  )->row();
                $device_token = $Firebase_token->Firebase_token;
                $this->push_notificationUser_android($device_token, $notification, $title, $type ='');
                $datetime = date('Y-m-d H:i:s');
                $data      = array("appointment_id" => $booking_id,"doctor_id" => $driver_id,"title" =>$title,"notification"=>$notification,"date_time"=>$datetime,"status"=>2);
                $results    = $this->LoginApiModel->insert($data,'Notification');
                $this->response([
                    'status' => TRUE,
                    'message' =>'Booking Rejected Successfully'
                ], REST_Controller::HTTP_OK);
              }else{
                $this->response([
                    'status' => FALSE,
                    'message' =>'Unable To Rejected',
                 
                ], REST_Controller::HTTP_OK);
              }           
            }
          else 
          {
            $this->response([
              'status' => FALSE,
              'message' => 'Data Not found'
            ], REST_Controller::HTTP_OK);
          }
     }
     else if ($status == 3)
    {
        $whereData  = array('id' => $booking_id);

        $data = $this->LoginApiModel->singleRowdata($whereData,'booking');
          if($data)
            {
              $where = array('booking_id' => $booking_id);
              $updateData  = array('is_status' => 1,);
              if ($this->LoginApiModel->update('booking_notificaion',$updateData,$where)) {
                
                $checkBooking  = $this->db->query('SELECT * FROM booking WHERE id ="'.$booking_id.'" '  )->row();

	                if ($checkBooking) {
	                  $user_id = $checkBooking->user_id;
	                }
                $whereBid = array('id' => $booking_id);
                $driver_id    = strip_tags($this->post('driver_id'));
                $updateBooking  = array('is_status' => 3,'driver_id'=> $driver_id);
                //print_r($updateBooking);die;
                $this->LoginApiModel->update('booking',$updateBooking,$whereBid);

                $whereID = array('id'=> $driver_id);
                $updateStaus  = array('available_staus' => 0);
                $this->LoginApiModel->update('Doctors',$updateStaus,$whereID);



                $title = "Ride End";
                $notification = "Your Ride is End";
                date_default_timezone_set("Asia/Calcutta");
                $Firebase_token = $this->db->query('SELECT * FROM Patients WHERE id ="'.$checkBooking->user_id.'" '  )->row();
                $device_token = $Firebase_token->Firebase_token;
                $this->push_notificationUser_android($device_token, $notification, $title, $type ='');
                $datetime = date('Y-m-d H:i:s');
                $data      = array("appointment_id" => $booking_id,"doctor_id" => $driver_id,"title" =>$title,"notification"=>$notification,"date_time"=>$datetime,"status"=>3);
                $results    = $this->LoginApiModel->insert($data,'Notification');
                $this->response([
                    'status' => TRUE,
                    'message' =>'Ride End Successfully'
                ], REST_Controller::HTTP_OK);
              }else{
                $this->response([
                    'status' => FALSE,
                    'message' =>'Unable To End',
                 
                ], REST_Controller::HTTP_OK);
              }           
            }
          else 
          {
            $this->response([
              'status' => FALSE,
              'message' => 'Data Not found'
            ], REST_Controller::HTTP_OK);
          }
     }


     else
     {
      $this->response([
            'status' => FALSE,
            'message' =>'Unable To Update',
         
        ], REST_Controller::HTTP_OK);
     }

   }
   
   
   public function getBookingRoute_post()
  {
     
    $booking_id    = strip_tags($this->post('booking_id'));
    $whereData     = array('id' => $booking_id);
    $data          = $this->LoginApiModel->singleRowdata($whereData,'booking');

    $picup_location = json_decode($data->pick_location);

    $drop_location = json_decode($data->drop_location);

    if(!empty($data))
    {
      $pic_lat   =  $picup_location->latitude;
      $pic_long  =  $picup_location->latitude;
      $drop_lat  =  $drop_location->latitude;
      $drop_long =  $drop_location->latitude;

      $GetRoute = $this->GetRoute($pic_lat,$pic_long,$drop_lat,$drop_long);

      if ($GetRoute ) {
        $this->response([
          'status' => TRUE,
          'message'=>'Booking list',
          'data' =>$GetRoute 
        ], REST_Controller::HTTP_OK);
      }
     

    }else{
       $this->response([
            'status' => FALSE,
            'message' =>'data not found',
         
        ], REST_Controller::HTTP_OK);
     
    }
  }

   public function GetRoute($pic_lat,$pic_long,$drop_lat,$drop_long){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      // CURLOPT_URL => "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=40.6655101,-73.89188969999998&destinations=40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.6905615%2C-73.9976592%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626%7C40.659569%2C-73.933783%7C40.729029%2C-73.851524%7C40.6860072%2C-73.6334271%7C40.598566%2C-73.7527626&key=AIzaSyD8JEKiRk3PdB97Bp2AVyv_Npdieu2X7JI",

       CURLOPT_URL => "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$pic_lat.",".$pic_long."&destinations=".$drop_lat.",".$drop_long."&key=AIzaSyD8JEKiRk3PdB97Bp2AVyv_Npdieu2X7JI",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "postman-token: c8fb937a-222e-3181-570a-06b8787892d2"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $res = json_decode($response);
      return $res;
    }


  }
  
  
   public function getBookingRouteById_post()
  {
     
    $booking_id    = strip_tags($this->post('booking_id'));
    $whereData     = array('id' => $booking_id);
    $data          = $this->LoginApiModel->singleRowdata($whereData,'booking');
    

    if(!empty($data))
    {
       $picup_location = json_decode($data->pick_location);

       $drop_location = json_decode($data->drop_location);

       $date = $data->date_time;

       $post_date = date('Y-m-d', strtotime($date));          

       $time= $this->get_time($post_date);

       $data_res = array('picup_location' => $picup_location,'drop_location' => $drop_location,'time'=> $time,'date'=>$date);

      $this->response([
          'status' => TRUE,
          'message'=>'Booking list',
          'data' =>$data_res 
        ], REST_Controller::HTTP_OK);
        
     

    }else{
       $this->response([
            'status' => FALSE,
            'message' =>'data not found',
         
        ], REST_Controller::HTTP_OK);
     
    }
  }
  
  
 public function driverLocation_post()
  {
     
    $booking_id          = strip_tags($this->post('booking_id'));
    $driver_id           = strip_tags($this->post('driver_id'));   
    $driver_location     = strip_tags($this->post('driver_location'));
    $location_name       = strip_tags($this->post('location_name'));

    $latitude            = strip_tags($this->post('latitude'));
    $longnitue           = strip_tags($this->post('longnitue'));
    $location_info       = strip_tags($this->post('location_info'));

    $whereData   = array('id' => $booking_id,'driver_id'  => $driver_id);

    $data   = $this->LoginApiModel->singleRowdata($whereData,'booking');
    
    if(!empty($data))
    {

        $whereDriver   = array('id'  => $driver_id);
        $Doctorsdata   = $this->LoginApiModel->singleRowdata($whereDriver,'Doctors');

        if ($Doctorsdata) {
            $vehicle_type = $Doctorsdata->Vehicle_Type;

            if ($vehicle_type == 1) {
                $icon = 'https://alphawizz.com/SmartParking/assets/img/bike.png';
            }else if ($vehicle_type == 1) {
               $icon = 'https://alphawizz.com/SmartParking/assets/img/pickup.png';
            }else{
                $icon = 'https://alphawizz.com/SmartParking/assets/img/mark.png';
            }
        }else{
            $vehicle_type = '';
        }

        $whereId      = array('driver_id'  => $driver_id);
        $check_driver = $this->LoginApiModel->singleRowdata($whereId,'locations');

        if ($check_driver) {
            $data_res = array(
                'driver_id'     => $driver_id,
                'latitude'      => $latitude,
                'longitude'     => $longnitue,
                'location_name' => $location_name,
                'location_info' => $location_info,
                'vehicle_type'  => $vehicle_type,
                'vehicle_icon'  => $icon
            );
            $update  = $this->LoginApiModel->update('locations',$data_res,$whereId);
        }else{

            $whereDriver   = array('id'  => $driver_id);
            $Doctorsdata   = $this->LoginApiModel->singleRowdata($whereDriver,'Doctors');

            if ($Doctorsdata) {
                $vehicle_type = $Doctorsdata->Vehicle_Type;

                if ($vehicle_type == 1) {
                    $icon = 'https://alphawizz.com/SmartParking/assets/img/bike.png';
                }else if ($vehicle_type == 1) {
                   $icon = 'https://alphawizz.com/SmartParking/assets/img/pickup.png';
                }else{
                    $icon = 'https://alphawizz.com/SmartParking/assets/img/mark.png';
                }
            }else{
                $vehicle_type = '';
            }


            $insert_data = array(
                'driver_id'     => $driver_id,
                'latitude'      => $latitude,
                'longitude'     => $longnitue,
                'location_name' => $location_name,
                'location_info' => $location_info,                
                'vehicle_type'  => $vehicle_type,
                'vehicle_icon'  => $icon
            );
            $insertLocation = $this->LoginApiModel->insert($insert_data,'locations');
        }


    $driver_loca = json_encode($driver_location);
    $data_res = array('driver_location' => $driver_loca);
    $update   = $this->LoginApiModel->update('booking',$data_res,$whereData);

      if ($update) {

        
          $this->response([
              'status' => TRUE,
              'message'=>'Location Update Successfully',
              //'data'   =>$data_res 
          ], REST_Controller::HTTP_OK);
      }else
      {
         $this->response([
            'status' => FALSE,
            'message' =>'Location Did not Update',
         
        ], REST_Controller::HTTP_OK);

      }     

    }else{

        $this->response([
            'status' => FALSE,
            'message' =>'You have not accepted any request.',
         
        ], REST_Controller::HTTP_OK);
     
    }
  }

  public function driverLocationUpdate_post()
  {
     
    
    $driver_id           = strip_tags($this->post('driver_id'));  
    $latitude            = strip_tags($this->post('latitude'));
    $longnitue           = strip_tags($this->post('longnitue'));

    $whereDriver   = array('id'  => $driver_id);
    $driver_data   = $this->LoginApiModel->singleRowdata($whereDriver,'Doctors');
    
    if(!empty($driver_data))
    {
        $data_res = array(
            'latitude'      => $latitude,
            'longnitue'     => $longnitue
        );
        $update  = $this->LoginApiModel->update('Doctors',$data_res,$whereDriver);
        if ($update) {
	        $this->response([
	              'status' => TRUE,
	              'message'=>'Location Update Successfully',
	         ], REST_Controller::HTTP_OK);
		     }else
	    {
	         $this->response([
	            'status' => FALSE,
	            'message' =>'Location Did not Update',
	         
	        ], REST_Controller::HTTP_OK);

	    } 

    }else{

        $this->response([
            'status' => FALSE,
            'message' =>'Driver Data Incorrect.',
         
        ], REST_Controller::HTTP_OK);
     
    }
  }


   public function driverNotificationList_post(){

    $vehicle_type    = strip_tags($this->post('vehicle_type'));
    $driver_id       = strip_tags($this->post('driver_id'));
    $where           = array("doctor_id" => $driver_id,'read_status' => 1,'vehicle_type' => $vehicle_type);
    $results         = $this->LoginApiModel->selectnotification('booking_notificaion',$where);
    if ($results) {
        foreach ($results as $key) {
            $post_date = $key['date_time'];
            $time_ago =strtotime($post_date);
            $time_elapsed = $this->timeAgo($time_ago);

            $where  = array('id' => $key['booking_id']);

            $checkBooking  = $this->LoginApiModel->singleRowdata($where,'booking');

            $wherePaitent  = array('id' => $key['paitent_id']);

            $p_data  = $this->LoginApiModel->singleRowdata($wherePaitent,'Patients');

            $wheredoct = array('id' => $key['doctor_id']);

            $Doctors_data  = $this->LoginApiModel->singleRowdata($wheredoct,'Doctors');

            $date = $key['date_time'];
            $post_date = date('Y-m-d', strtotime($date));          

            $time= $this->get_time($post_date);

			if(!empty($checkBooking->id)){
            $whereId  = array('booking_id' =>$checkBooking->id);

            $checkBookingLocation  = $this->LoginApiModel->selectdata('booking_drop_location',$whereId);

			
			//print_r($checkBookingLocation);
			$booking = array(
		        "pick_location"    =>$checkBooking->pick_location,
		        "drop_location"    => json_encode($checkBookingLocation),
		        "vehicle_type"       => $checkBooking->vehicle_type,
		        "pick_time"     => $checkBooking->pick_time,
		        "pick_date"     => $checkBooking->pick_date,
		        "phonenum"     => $checkBooking->phonenum,
		        "stuf_name"     => $checkBooking->stuf_name,
		        "packaging_required"     => $checkBooking->packaging_required,
		        "amount"     => $checkBooking->amount,
		        "payment_type"     => $checkBooking->payment_type,
		        "distance"     => $checkBooking->distance,
		        "attachment_image"     => $checkBooking->attachment_image,
		        "lift_facility"     => $checkBooking->lift_facility,
		        "landmark"     => $checkBooking->landmark,
		        "description"     => $checkBooking->description,
		        "pick_date_time"     => $checkBooking->pick_date_time,
		        "user_id"     => $checkBooking->user_id,
		        "driver_id"     => $checkBooking->driver_id,
		        "driver_location"     => $checkBooking->driver_location,
		        "booking_date"     => $checkBooking->booking_date,
		        "booking_time"     => $checkBooking->booking_time,
		        "pick_up_letter"     => $checkBooking->pick_up_letter,
		        "date_time"     => $checkBooking->date_time,
		        "is_status"     => $checkBooking->is_status,
		        "customer_name"     => $checkBooking->customer_name,


		);
			}

            $data[] = array(
				"notification_id"  => $key['notification_id'],
				"user_id"          => $key['paitent_id'],
				'patients_data'    => $p_data,
				"vehicle_type"     => $vehicle_type,
				"driver_id"        => $key['doctor_id'],
				'driver_data'      => $Doctors_data,
				"booking_id"       => $key['booking_id'],
				'booking_data'     => $booking,
				"title"            => $key['title'],
				"message"          => $key['message'],
				"date_time"        => $time_elapsed,
				"read_status"      => $key['read_status'],
				"time" => $time
            );
        }  
         $this->response([
              'status' => TRUE,
              'message'=>'Nottification List',
              'data'   =>$data 
          ], REST_Controller::HTTP_OK);
    }else{
         $this->response([
            'status' => FALSE,
            'message' =>' Data not found',
         
        ], REST_Controller::HTTP_OK);
    }
  }


  public function driverNotificationCount_post(){
     $driver_id     = $this->post('driver_id');
     $vehicle_type     = $this->post('vehicle_type');
     $where      = array("doctor_id" => $driver_id,'read_status' => 0,'vehicle_type'=>$vehicle_type);
     $results    = $this->LoginApiModel->record_count('booking_notificaion',$where);
      if ($results) {
           $this->response([
                'status' => TRUE,
                'message'=>'Nottification Count',
                'data'   =>$results 
            ], REST_Controller::HTTP_OK);
      }else{
           $this->response([
              'status' => TRUE,
              'message'=>'Nottification Count',
              'data' =>'0',
           
          ], REST_Controller::HTTP_OK);
      }   
      
  }

public function readUnreadNotification_post(){  

    $driver_id           = strip_tags($this->post('driver_id')); 
    //$notification_id     = strip_tags($this->post('notification_id'));

    $where = array('doctor_id' => $driver_id,'read_status' => 0);
    $data   = $this->LoginApiModel->singleRowdata($where,'booking_notificaion');
    if ($data) {
      $data_update  = array('read_status' => 1);
      $results      = $this->LoginApiModel->update('booking_notificaion',$data_update,$where);
      if ($results) {         
          $this->response([
                'status' => TRUE,
                'message'=>'Nottification Read',
          ], REST_Controller::HTTP_OK);
      }else{
           $this->response([
              'status' => FALSE,
              'message' =>' Nottification not Read',
           
          ], REST_Controller::HTTP_OK);
      }   

    }else{
      $this->response([
            'status' => FALSE,
            'message' =>' Data not found',
         
        ], REST_Controller::HTTP_OK);

    }
  }
  
   



public function patientNotificationList_post(){

    //$booking_id    = strip_tags($this->post('booking_id'));
    $patient     = strip_tags($this->post('patient_id'));
    $where      = array("user_id" => $patient,'read_status' => 1);
    $results    = $this->LoginApiModel->selectnotification('Notification',$where);
    if ($results) {
        foreach ($results as $key) {
            $checkBooking  = $this->db->query('SELECT * FROM booking WHERE id ="'.$key['appointment_id'].'" '  )->row();
            if(!empty($checkBooking->id)){
            $whereId  = array('booking_id' =>$checkBooking->id);

            $checkBookingLocation  = $this->LoginApiModel->selectdata('booking_drop_location',$whereId);
            $booking_data = array(
                 "booking_id"    =>$checkBooking->id,
                    "pick_location"    =>$checkBooking->pick_location,
                    "drop_location"    => json_encode($checkBookingLocation),
                    "vehicle_type"       => $checkBooking->vehicle_type,
                    "pick_time"     => $checkBooking->pick_time,
                    "pick_date"     => $checkBooking->pick_date,
                    "phonenum"     => $checkBooking->phonenum,
                    "stuf_name"     => $checkBooking->stuf_name,
                    "packaging_required"     => $checkBooking->packaging_required,
                    "amount"     => $checkBooking->amount,
                    "payment_type"     => $checkBooking->payment_type,
                    "distance"     => $checkBooking->distance,
                    "attachment_image"     => $checkBooking->attachment_image,
                    "lift_facility"     => $checkBooking->lift_facility,
                    "landmark"     => $checkBooking->landmark,
                    "description"     => $checkBooking->description,
                    "pick_date_time"     => $checkBooking->pick_date_time,
                    "user_id"     => $checkBooking->user_id,
                    "driver_id"     => $checkBooking->driver_id,
                    "driver_location"     => $checkBooking->driver_location,
                    "booking_date"     => $checkBooking->booking_date,
                    "booking_time"     => $checkBooking->booking_time,
                    "pick_up_letter"     => $checkBooking->pick_up_letter,
                    "date_time"     => $checkBooking->date_time,
                    "is_status"     => $checkBooking->is_status,
                    "customer_name"     => $checkBooking->customer_name,


            );
            }

            $checkuser  = $this->db->query('SELECT * FROM Patients WHERE id ="'.$key['user_id'].'" '  )->row();

            $wherePaitent  = array('id' => $key['doctor_id']);

            $doctor_data  = $this->LoginApiModel->singleRowdata($wherePaitent,'Doctors');

            if ($doctor_data) {
            	$vehicle_number = $doctor_data->vehicle_number;
            	$driver_name =  $doctor_data->username;
            	$image =  $doctor_data->image;
            	$first_name =  $doctor_data->first_name;
            	$phone_number = $doctor_data->phone_number;

            }else{
            	$vehicle_number = '';
            }

            $date = $key['date_time'];
            $post_date = date('Y-m-d', strtotime($date));		   

            $time= $this->get_time($post_date);

            $data[] = array(
            	
            	"time" => $time,
                "notification_id"  => $key['notification_id'],
                "user_id"       => $key['user_id'],
                "title"         => $key['title'],
                "message"       => $key['notification'],
                "date_time"     => $key['date_time'],
                "booking_data"  => ($booking_data)?$booking_data:"",
                "user_data"     => $checkuser,
                "vehicle_number"   => $vehicle_number,
                "driver_image"    => $image,
                "driver_name" => $first_name,
                "driver_username" => $driver_name,
                "driver_number" => $phone_number,
                "read_status"   => $key['read_status'],
            );
        }  
         $this->response([
              'status' => TRUE,
              'message'=>'Nottification List',
              'data'   =>$data 
          ], REST_Controller::HTTP_OK);
    }else{
         $this->response([
            'status' => FALSE,
            'message' =>' Data not found',
         
        ], REST_Controller::HTTP_OK);
    }   
   
}

public function get_time($post_date) {

	$curr_date = date('Y-m-d');

    if($curr_date == $post_date){
    	  //return $arrayName = array('curr_date' => $curr_date, 'post_date'=> $post_date,'status'=>'now');
	       return 'Now';
	}

	if($curr_date > $post_date){

		// return $arrayName = array('curr_date' => $curr_date, 'post_date'=> $post_date,'status'=>'Later');

	       return  'Later';
	}

	if($curr_date < $post_date){

		//echo $curr_date;

		$date = "2020-06-02";
		$date1 = str_replace('-', '/', $date);
		$tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));

		// $stop_date = date('Y-m-d', strtotime('+1 day', $curr_date));

		// return $tomorrow;

		if ($tomorrow < $post_date) {

			return 'Later';

		 //return $arrayName = array('curr_date' => $curr_date, 'post_date'=> $post_date,'status'=>'Later');
		}else
		{
			return 'Tomorrow';
		//return $arrayName = array('curr_date' => $curr_date, 'post_date'=> $post_date,'status'=>'Tomorrow');
		}
	    //return 'Tomorrow';
	}



	// $datediff = $post_date - $curr_date;
 // 	$difference = floor($datediff/(60*60*24));

 // 	if($difference > 0)
	//  {
	//     return 'Tomorrow';
	//  }



    // $current = strtotime(date('Y-m-d H:i'));

    // $date_diff = floatval($date) - floatval($current);
    // $difference = round($date_diff/(60*60*24));

    // if($difference >= 0) {
    //         return 'Now';
    // } else if($difference == -1) {
    //         return 'Later';
    // } else if($difference == -2 || $difference == -3  || $difference == -4 || $difference == -5) {
    //         return 'Tomorrow'; 
    // } else {
    //         return 'Tomorrow';
    // }

}




public function patientNotificationCount_post(){

     $user_id     = $this->post('user_id');
     $where      = array("user_id" => $user_id,'read_status' => 0);
     $results    = $this->LoginApiModel->record_count('Notification',$where);
    if ($results) {
       
         $this->response([
              'status' => TRUE,
              'message'=>'Nottification Count',
              'data'   =>$results 
          ], REST_Controller::HTTP_OK);
    }else{
         $this->response([
            'status' => FALSE,
            'message' =>' Data not found',
         
        ], REST_Controller::HTTP_OK);
    }   
   
}

public function paitentreadUnreadNotification_post(){  

    $patient_id           = strip_tags($this->post('patient_id')); 
   // $notification_id     = strip_tags($this->post('notification_id'));

    $where = array('user_id'=>$patient_id,'read_status' => 0);
    $data   = $this->LoginApiModel->singleRowdata($where,'Notification');
    if ($data) {
      $data_update  = array('read_status' => 1);
      $results      = $this->LoginApiModel->update('Notification',$data_update,$where);
      if ($results) {         
          $this->response([
                'status' => TRUE,
                'message'=>'Nottification Read',
          ], REST_Controller::HTTP_OK);
      }else{
           $this->response([
              'status' => FALSE,
              'message' =>' Nottification not Read',
           
          ], REST_Controller::HTTP_OK);
      }   

    }else{
      $this->response([
            'status' => FALSE,
            'message' =>' Data not found',
         
        ], REST_Controller::HTTP_OK);

    }
  }

  public function getDriverRoot_post()
  {
     
    $driver_id    = strip_tags($this->post('driver_id'));
    $booking_id    = strip_tags($this->post('booking_id'));
    $whereData     = array('id' => $booking_id,'driver_id' =>$driver_id );
    $data  = $this->LoginApiModel->singleRowdata($whereData,'booking');   

    if(!empty($data))
    {
      $driver_location = json_decode($data->driver_location);

      $data_res = array('driver_location' => $driver_location);

      $this->response([
          'status' => TRUE,
          'message'=>'Driver location',
          'data' =>$data_res 
        ], REST_Controller::HTTP_OK);
        
     

    }else{
       $this->response([
            'status' => FALSE,
            'message' =>'data not found',
         
        ], REST_Controller::HTTP_OK);
     
    }
  }


public function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

public function applyPromoCode_post() {
    $coupon_code    = strip_tags($this->post('coupon_code'));
    $amount         = strip_tags($this->post('amount'));
    $where          = array('coupon_code' => $coupon_code);

    $results = $this->LoginApiModel->singleRowdata($where,'coupon');
    if ($results) {
        $currentDate = date('Y-m-d');
        if ($results->start_date <= $currentDate && $results ->end_date>= $currentDate) {
            if ($results->discount_type == 'Flat') {
                $flat = $results->discount;
                $totalAmount = $amount;
                $new_amount = $amount - $flat;
            }
            elseif($results ->discount_type == 'Percent') {
                $percentage = $results ->discount;
                $totalAmount = $amount;
                $percent = $percentage / 100 * $totalAmount;
                $new_amount = $totalAmount - $percent;
            }else {

            }
            $coupon_array = array(
                'old_amount' => $amount,
                'new_amount' => $new_amount,
                'discount' => $results->discount.
                ' '.$results ->discount_type,
                'coupon_id' => $results->coupon_id
            );

            $this->response([
                'status'  => TRUE,
                'message' => 'Coupon Applied Successfully',
                'data'    => $coupon_array 
            ], REST_Controller::HTTP_OK);
           
        }else{

            $this->response([
                'status' => FALSE,
                'message' =>'Coupon Has Been Expired',             
            ], REST_Controller::HTTP_OK);
          
        }
    }else{
          $this->response([
            'status' => FALSE,
            'message' =>'Invalid Promo Code',
         
        ], REST_Controller::HTTP_OK);
    }
}

    public function cronPickUpletter_get()
    {
        date_default_timezone_set("Asia/Calcutta");
        $currentDate = date("F j Y H:i");
        $where  = array('pick_up_letter' => 1,
        //,'pick_date_time' => $currentDate 
        );
        $results = $this->LoginApiModel->selectdata('booking',$where);
        if ($results) {

            $getDoctors = [];
            foreach ($results as $key ) {
                $pick_date_time = $key['pick_date_time'];
                $newTime = date("F j Y H:i",strtotime("-30 minutes", strtotime($pick_date_time)));
                if($currentDate == $newTime){

                     $getDoctors = $this->LoginApiModel->select('Doctors');                    

                    $getDoctors = $this->LoginApiModel->select('Doctors');

                    if ($getDoctors) {

                        foreach($getDoctors as $key1) {
                            $doctor_data[] = array('id' => $key1['id']);

                            $title = 'Notification';
                            $message = 'You Have Received New Request';
                            $device_token = $key1['Firebase_token'];

                            $doctor_id = $key1['id'];
                            $user_id = $key['user_id'];

                            $booking_id = $key['id'];

                            $vehicle_type = $key['vehicle_type'];

                            date_default_timezone_set("Asia/Calcutta");
                            $datetime = date('Y-m-d H:i:s');

                            $this->push_notification_android($device_token, $message, $title, $type ='');

                             $this->firebase->insertBookingMessage(
                                array(
                                   'paitent_id'=>$user_id,
                                   'doctor_id'=>$doctor_id,
                                   'booking_id'=>$booking_id,
                                   'vehicle_type'=>$vehicle_type,
                                   'message'=>'You have received a New Booking Request',
                                   'title'=>'New Booking',
                                   'date_time' => $datetime 
                                )
                            );
                           
                        }
                    }               

                }
            }

            $this->response([
                'status' => TRUE,
                'msg' => 'success',
                'data' =>$getDoctors,             
            ], 
            REST_Controller::HTTP_OK);

        }else{

            $this->response([
                'status' => FALSE,
                
            ], 
            REST_Controller::HTTP_OK);

        }
    }

public function driverAvailableStatus_post(){

    $driver_id    = $this->post('driver_id');
    $status       = $this->post('status');
    $where       = array("id" => $driver_id);
    if ($status == 'online') {
    	$driver_status = '1';
    }else{
    	$driver_status = '2';
    }
    $data        = array('online_status' => $driver_status);
    $results    = $this->LoginApiModel->update('Doctors',$data,$where);
    if ($results) {
    	$check_driver = $this->LoginApiModel->singleRowdata($where,'Doctors');
       	 $status = $check_driver->online_status;

       	if ($status == 1) {
		    $driver_sta = 'online';
		}else{
		    $driver_sta = 'offline';
		}


         $this->response([
              'status' => TRUE,
              'message'=>'success',
              'data'   =>$driver_sta 
          ], REST_Controller::HTTP_OK);
    }else{
         $this->response([
            'status' => FALSE,
            'message' =>' Data not found',
         
        ], REST_Controller::HTTP_OK);
    }   
   
}

public function generateBookingOtp_post(){
        $booking_id = $this->post('booking_id');
        $driver_id  = $this->post('driver_id');
        $mobile  = $this->post('mobile');

        // $whereType  = array('id'=> $booking_id,'driver_id'=>$driver_id);
        // $checkBooking = $this->LoginApiModel->singleRowdata($whereType,'booking');
        // if(!$checkBooking)
        // {
        //     $this->response([
        //     'status' => FALSE,
        //     'message' => 'Please Accept Booking First',
        //    ], REST_Controller::HTTP_OK);    
        // }
        // else 
        // {
            $whereDataType  = array( 'reciver_number' =>$mobile);
            // $checkBooking = $this->LoginApiModel->singleRowdata($whereDataType,'booking_drop_location1');
            // // print_r($checkBooking);die;
            // if ($checkBooking) {
                 $otp = rand(1000, 9999);
                $newmobile = '7987735353';//$checkBooking->reciver_number;
                $sendOtp = $this->twillomessageSend($otp, $newmobile); 

                $array_data = array('otp'=>$otp);
                $updateOtp = $this->LoginApiModel->update('booking_drop_location',$array_data,$whereDataType);

                if ($updateOtp) {
                    $data = array("reciver_number"=>$newmobile);
                    $checkOtp = $this->LoginApiModel->singleRowdata($whereDataType,'booking_drop_location');


                    $this->response([
                    'status' => TRUE,
                    // 'message' => 'Your Otp is Valid for 60 seconds',
                    
                    // 'otp' =>$checkOtp->otp,
                    // 'phone_number'=>$checkOtp->reciver_number,
                    "sendOtp" =>$sendOtp
                   ], REST_Controller::HTTP_OK); 
                }else{
                     $this->response([
                    'status' => FALSE,
                    'message' => 'Failed',
                    
                   ], REST_Controller::HTTP_OK); 
                }
            // }else{
            //     $this->response([
            //         'status' => FALSE,
            //         'message' => 'Mobile Number Incorrect',
                    
            //        ], REST_Controller::HTTP_OK); 
            // }
            
            
        //}
       
    }


 public function twillomessageSend($otp,$mobile){
            $id = "AC99d9afe060cc8bac4aa6e32422179b5c";
            $token = "1c47a266dfd11ad2e8c766c5d43491db";
            $url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
            $from = "+12563776344";
            $to = $mobile; // twilio trial verified number
            //$body = "Hello";

             $body   = "Your OTP is ".$otp." Please enter the same to use your account. Keep this OTP to yourself for account safety.";


            $data = array (
                'From' => $from,
                'To' => $to,
                'Body' => $body,
            );
            $post = http_build_query($data);
            $x = curl_init($url);
            curl_setopt($x, CURLOPT_POST, true);
            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
            $y = curl_exec($x);
            $err = curl_error($x);
            curl_close($x);
            if ($err) {
              return false;//echo "cURL Error #:" . $err;
            } else {
              return true; 
            }
    }


    public function messageSend($otp,$mobile){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "http://trans.smsfresh.co/api/sendmsg.php?user=Live7update&pass=123456&sender=LIVUPD&phone=".$mobile."&text=".$otp."&priority=ndnd&stype=normal"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch);        

        $err = curl_error($ch);

        curl_close($ch);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return true; 
        }
        // if ($err) {
        //     return false;
        // } else {
        //     return true; 
        // }
    }


}
