<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model{

  public function singleRow($where_data,$table){  
    $this->db->select('*');
    $this->db->where($where_data);
    $query = $this->db->get($table);
    return $query->num_rows();
  }

  public function singleRowdata($where_data,$table){  
    $this->db->where($where_data);
    $query = $this->db->get($table);
    return $query->row();
  }

  public function select($table,$order=""){    
     $this->db->select('*');
     $this->db->order_by($order);
     $this->db->from($table);
     $query = $this->db->get();
     return $query->result_array();
  }

  public function selectdata($table,$wheredata,$order=""){ 
     $this->db->select('*');
     $this->db->order_by($order);
     $this->db->from($table);
     $this->db->where($wheredata);
     $query = $this->db->get();
     return $query->result_array();
  }

  public function update($table,$data,$where_data){
    $this->db->where($where_data);
    $insertData=$this->db->update($table,$data);
    if($insertData){
      return TRUE;
    }else{
      return FALSE;
    }
  }

  public function count($table){
    $this->db->select('*');
    $this->db->from($table);
    return $this->db->count_all_results();
  }

  public function countWhere($table,$where){
    $this->db->select('*');
    $this->db->from($table);
    $this->db->where($where);
    return $this->db->count_all_results();
  }

  public function record_count($table,$data){
    if(!empty($data))
    {
      $this->db->where($data);
    }
    $this->db->from($table);
    return $this->db->count_all_results();
  }

  public function insert($data,$table){      
      $this->db->set($data);
      $insertData = $this->db->insert($table);
      if($insertData){
        return $this->db->insert_id();;
      }else{
        return FALSE;
      }
  }

  public function selectAllById($tbl,$wheredata="")
  {
     $this ->db-> select('*');
     $this ->db-> from($tbl);
     $this ->db-> where($wheredata);
     $query = $this ->db-> get();
     return $query->result_array();
  }

    public function delete($wheredata,$tbl){
        $query = $this->db->where($wheredata);
        $query = $this->db->delete($tbl);
        return $query;
  }
}