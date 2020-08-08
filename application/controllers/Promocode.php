<?php

class Promocode extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(empty($this->session->userdata('admin'))){
            redirect('AdminLogin');
          }
          $this->load->model('Promocode/PromocodeModel');
    }

    public function index(){
        $data['promocode'] = $this->PromocodeModel->getAllPromocode();
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        $this->load->view('promocode/promocode_list',$data);
        $this->load->view('common/footer');   
    }

    public function addPromo(){
        $this->load->view('common/header');
        $this->load->view('common/sidebar'); 
        $this->load->view('promocode/add_promocode');        
        $this->load->view('common/footer');  
    }
    public function savePromo(){
        $this->load->view('common/header');
        $this->load->view('common/sidebar');        
         if(!empty($this->input->post())){
         $coupon_name = $this->input->post('coupon_name');
         $coupon_code = $this->input->post('coupon_code');
         $description = $this->input->post('description');
         $discount = $this->input->post('discount');
         $discount_type = $this->input->post('discount_type');
         $coupon_applied_for = $this->input->post('coupon_applied_for');
         $start_date = $this->input->post('start_date');
         $end_date = $this->input->post('end_date');
        // $created_at = $this->input->post('created_at');

         $data=array(
            'coupon_name'=>$coupon_name,
            'coupon_code'=>$coupon_code,
            'description'=>$description,
            'discount'=>$discount,
            'discount_type'=>$discount_type,
            'coupon_applied_for'=>$coupon_applied_for,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
           // 'created_at'=>$created_at
         );
        
          
        $this->PromocodeModel->InserCoupon($data);

        redirect('Promocode/index');
     }
     $this->load->view('common/footer');
          
    }
    public function editPromo($id){
        $this->load->view('common/header');
        $this->load->view('common/sidebar');
        //$promo_id = $this->input->post('coupon_id');
        // print_r($promo_id);die(); 
        $data['promodetail']=$this->PromocodeModel->editCoupon($id);
          $this->load->view('promocode/edit_promocode',$data);          
        //print_r($data);die();
         if(!empty($this->input->post())){
         $coupon_name = $this->input->post('coupon_name');
         $coupon_code = $this->input->post('coupon_code');
         $description = $this->input->post('description');
         $discount = $this->input->post('discount');
         $discount_type = $this->input->post('discount_type');
         $coupon_applied_for = $this->input->post('coupon_applied_for');
         $start_date = $this->input->post('start_date');
         $end_date = $this->input->post('end_date');
         $created_at = $this->input->post('created_at');

         $coupon=array(            
            'coupon_name'=>$coupon_name,
            'coupon_code'=>$coupon_code,
            'description'=>$description,
            'discount'=>$discount,
            'discount_type'=>$discount_type,
            'coupon_applied_for'=>$coupon_applied_for,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'created_at'=>$created_at
         );
         //print_r($id);die();       
        $this->PromocodeModel->UpdateCoupon($id,$coupon);
         redirect('Promocode/index');
        }  

        $this->load->view('common/footer');  
    
}
public function deletePromo($id){      
        $this->PromocodeModel->deletePromoById($id);
        redirect('Promocode/index');
    }

}