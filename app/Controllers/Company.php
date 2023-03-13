<?php namespace App\Controllers;
use App\Models\CompanyModel;
use App\Models\GeneralModel;

class Company extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new CompanyModel();
        $this->gmodel = new GeneralModel();
        
    }
	public function index(){
        //echo '<pre>';Print_r(session('cid'));exit;
        
        
        $data['title']="Company";
        return view('company/index',$data); 
    }

    public function company_view($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        if(!empty($id)){
            $data['profile'] = $this->model->get_company_detail($id);
        }
        $data['title']="Company Detail";
        return view('company/detail', $data);
    }

    public function update_company(){
        $post = $this->request->getPost();
        if(!empty($post)){
            $msg = $this->model->update_company_id($post,'is_stock');
            return $this->response->setJSON($msg);   
        }
    }

    public function CreateCompany($id = ''){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data = array();
        
        $post = $this->request->getPost();
        $file = $this->request->getFile('sign_caption');
        $logo = $this->request->getFile('logo');
        
        if(!empty($post)){
            // print_r($post);exit;
            $msg = $this->model->insert_edit_company($post,$file,$logo);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
            $data['company'] = $this->model->get_company_byid($id); 
            // echo '<pre>';print_r($data);exit;
        }
        
        $data['title']="Company Group";
        return view('company/create_company', $data);
    }

    public function add_companygrp(){
        
         $post=$this->request->getPost();
         $file=$this->request->getFile('company_logo');
         
         if(!empty($post)){    
            $msg = $this->model->insert_edit_companygrp($post,$file);  
         }
         return $this->response->setJSON($msg);
         
    } 

    public function add_gst($id){

        $data['gst']=$this->model->get_gst($id);  

        $post = $this->request->getPost();
        if(!empty($post)){
            $msg = $this->model->insert_edit_company_gst($post);
            // print_r()
            return $this->response->setJSON($msg);
        }
        $data['title'] = "Add GST Detail";
        $data['id']=$id;
        //  echo '<pre>'; print_r($data);exit;
        return view('company/create_gst',$data);
    } 
    
    public function company_grp(){
        $data['title']="Company Group";
		return view('company/company_grp',$data);
    }

    public function create_companygrp($id=''){
        
        $data = array();
        $post = $this->request->getPost();
        //print_r($post);exit;
        // if (!empty($post)) {
        //     // $validate = $this->model->validate_itemgrp_data($post);
        //     // if($validate['st'] == 'fail'){
        //     //     return $this->response->setJSON($validate);
        //     // }else{
        //     //     //$post['annexure'] = 'CUS';
        //     //     $msg = $this->model->insert_edit_itemgrp($post,$file);
        //     //     return $this->response->setJSON($msg);
        //     // }
        // }
        if ($id != '') {
         $data = $this->model->get_master_data('companygrp', $id);
         }
         
         $data['id'] = $id;
        $data['title'] = "Add Company Group" ;
        return view('company/create_compaygrp', $data);
    
    }

    public function Getdata($method = '') {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if ($method == 'searchCompanyGroup') {
            $get = $this->request->getPost();
            $data = $this->model->search_companygrp_data($get['searchTerm']);
            return $this->response->setJSON($data);
        }
        if ($method == 'company') {
            $get = $this->request->getGet();
            $this->model->get_company_data($get);
        }
        if ($method == 'companygrp') {
            $get = $this->request->getGet();
            $this->model->get_companygrp_data($get);
        }
        if($method == 'search_country') {
            $post = $this->request->getPost();
            $data=$this->model->getCountry($post);
            return $this->response->setJSON($data);
        }

        if($method == 'search_state') {
            $post = $this->request->getPost();
            // print_r($post);exit;
            $data=$this->model->getStates($post);
            return $this->response->setJSON($data);
        }

        if($method == 'search_city') {
            $post = $this->request->getPost();
            $data=$this->model->getCities($post);
            return $this->response->setJSON($data);
        }
    }  


    public function Action($method = '') {
        $result = array();
        if ($method == 'Update') {

            $post = $this->request->getPost();
            $result = $this->model->UpdateData($post);
        }
        return $this->response->setJSON($result);
    }

    public function opencompany($id){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if($id !=''){
            $msg = $this->model->company_login($id);
        } 
        if(!session('uid')){
            return redirect()->to(url('company'));
        } else {
            return redirect()->to(url('Account'));
        } 
    }

    public function Close(){
        $session = session();
        $cdata = [
            'cid',
            'name',
            'DataSource',
            'company_group',
            'code',
            'name',
            'email',
        ];
        $session->remove($cdata);
        return redirect()->to(url(''));
    }
      
}
?>