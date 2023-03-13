<?php 

namespace App\Controllers;
use App\Models\AccountModel;
use App\Models\GeneralModel;

class Account extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
                
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new AccountModel();
        $this->gmodel = new GeneralModel();
        
    }
    public function index(){
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }
        $data['title']="Account";
		return view('account/account',$data);
    }
    public function getstates($country_name) {  
        $json = array();
        $json = $this->model->getStates($country_name);
        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
    public function getcities($state_name) {
        $json = array();
       // $this->model->setStateID($this->input->post('stateID'));
        $json = $this->model->getCities($state_name);
        header('Content-Type: application/json');
        echo json_encode($json);
    }
  
   
    public function Getdata($method = '') {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }
        $cid = session('cid');
        if ($method == 'account') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_account_data($get);
        }
        if ($method == 'voucher') {
            $get = $this->request->getGet();
            $this->model->get_voucher_data($get);
        }
        if ($method == 'parent_voucher') {
            $get = $this->request->getGet();
            $data = $this->model->get_parent_voucher_data($get);
            return $this->response->setJSON($data);
        }
    }
    
    public function add_account($id = ''){
        
        $data = array();
        $post = $this->request->getPost();
        if(!empty($post))
        {   
            $msg=$this->model->insert_edit_account($post);
            return $this->response->setJSON($msg);
        }
         if ($id != '') {
            $data['account_view'] = $this->model->get_account_data_byid($id);
            $gl_id = $data['account_view']['gl_group'];
            $data['hide_show'] = $this->model->get_gl_parent($gl_id);
         }
         $data['id'] = $id;
         $data['title'] = "Add Account Data" ;
         //echo '<pre>';Print_r($data);exit;
         
         return view('account/create_account',$data);
     }
     
     public function Action($method = '') {
        $result = array();
        if ($method == 'Update') {
            $post = $this->request->getPost();
            $result = $this->model->UpdateData($post);
        }
       
        return $this->response->setJSON($result);
    }

    public function glgrp(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data['title']="GL Group";
		return view('account/gl_grp',$data);
    }

    public function add_glgrp(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data['title']="GL Group";
		return view('account/create_glgrp',$data);
    }

    public function account_view($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if($id != ''){
            $data['account'] = $this->model->get_account_data_byid($id);
        }
        $data['title']="Account View";
        // echo '<pre>';print_r($data);exit;
        return view('account/detail',$data);
    }
    public function get_gl_parent(){
        $post  = $this->request->getPost();
        $gl_id =$post['gl_id'];
        if(!empty($post)){
            $data = $this->model->get_gl_parent($gl_id);
        } 
        return $this->response->setJSON($data);
    }
    public function voucher(){
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }
        $data['title']="Voucher List";
		return view('account/voucher_list',$data);
    }
    public function add_voucher($id=''){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_voucher($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
            $data = $this->model->get_master_data('voucher_type', $id);
        }
      
        $data['id'] = $id;
        $data['title'] = "Add Voucher Type" ;
        return view('account/create_vouchertype', $data);
    }

}

?>