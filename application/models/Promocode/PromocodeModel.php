<?php

class PromocodeModel extends CI_Model {
    public function getAllPromocode(){
    	$this->db->select('*');
    	$this->db->from('coupon');
    	$data = $this->db->get()->result_array();        
        return $data;
    }
     public function InserCoupon($data){
    	$this->db->insert('coupon',$data);    	      
        return true;
    }
    public function editCoupon($id){
    	$this->db->select('*');
    	$this->db->from('coupon');
    	$this->db->where('coupon_id',$id);  
    	$data = $this->db->get()->result_array();
    	//print_r($data);die();
        return $data;
    }
    public function UpdateCoupon($id,$data){
    	$this->db->set($data);
    	//$this->db->from('coupon');
    	$this->db->where('coupon_id',$id); 
    	 $this->db->update('coupon');
    	//$data = $this->db->get()->result_array();
    	
        return true;
    }
    public function deletePromoById($id){
    	$this->db->where('coupon_id',$id); 
    	$this->db->delete('coupon');    	

        return true;
    
    }
}