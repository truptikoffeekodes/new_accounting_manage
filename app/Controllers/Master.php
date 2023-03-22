<?php 
namespace App\Controllers;
use App\Models\MasterModel;
use App\Models\GeneralModel;
use App\Models\AccountModel;
use App\Models\ItemsModel;

class Master extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new MasterModel();
        $this->gmodel = new GeneralModel();
        $this->acmodel = new AccountModel();
        $this->imodel = new ItemsModel();
    }

    public function transport()
    {
        if (!session('uid')){
            return redirect()->to(url('auth'));
        }
        
        $data['title']="Transport";
        return view('master/transport', $data);
    }

    public function add_account($type= '')
    {
        if(!session('uid')) {
           return redirect()->to(url('auth'));
        }

        $data = array();
        $post = $this->request->getPost();
        if(!empty($post)) {
           $msg = $this->acmodel->insert_edit_account($post);
           return $this->response->setJSON($msg);
        }
     
        $data['title']="Add Account";
        $data['type'] = $type;
        return view('master/create_account',$data);
    }
    

    public function add_account_inc_exp()
    {
        if(!session('uid')) {
           return redirect()->to(url('auth'));
        }
        
        $data = array();
        $post = $this->request->getPost();
        if(!empty($post)) {
           $msg = $this->acmodel->insert_edit_account($post);
           return $this->response->setJSON($msg);
        }
     
        $data['title']="Add Account";
        // $data['type'] = $type;
        
        return view('master/create_acc_inc_exp',$data);
    }
    
    public function vehicle()
    {
        if (!session('uid')){
            return redirect()->to(url('auth'));
        }
        $data['title']="vehicle";
        return view('master/vehicle', $data);
    }

    public function broker()
    {
        if (!session('uid')){
            return redirect()->to(url('auth'));
        }

        $data['title']="Broker";
        return view('master/broker',$data);
    }

    public function tds()
    {
        if (!session('uid')){
            return redirect()->to(url('auth'));
        }

        $data['title']="tds";
        return view('master/tds', $data);
    }

    public function hsn()
    {
        if (!session('uid')){
            return redirect()->to(url('auth'));
        }

        $data['title']="hsn";
        return view('master/hsn', $data);
    }

    public function add_broker($id= '')
    {
       if (!session('uid')){
           return redirect()->to(url('auth'));
       }
       $data = array();
       $post = $this->request->getPost();
       
       if (!empty($post)){
           $msg = $this->model->insert_edit_broker($post);
           return $this->response->setJSON($msg);
       }
       
       if ($id != '') {
          $data = $this->model->get_master_data('broker', $id);
       }

       $data['title']="Add Broker";
       return view('master/create_broker',$data);
    }
    
    public function insert_glgroup()
    {
         $post=$this->request->getPost();
         if(!empty($post))
         {
            $msg=$this->model->insert_edit_glgrp($post);
         }
         return $this->response->setJSON($msg);
    }
    
    public function billterm()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Bill Term";
        return view('master/billterm', $data);
    }

    public function add_billterm($id=''){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_billterm($post);
            return $this->response->setJSON($msg);
        }
        //print_r($id);exit;
        if ($id != '') {
            $data = $this->model->get_master_data('billterm', $id);
        }
        //echo '<pre>';print_r($data);exit;
        $data['id'] = $id;
        $data['title'] = "Add Bill Term" ;
        return view('master/create_billterm', $data);
    }

    public function add_tds($id=''){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_tds($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
            $data = $this->model->get_master_data('tds', $id);
        }
        //echo '<pre>';print_r($data);exit;
        $data['id'] = $id;
        $data['title'] = "Add TDS Rate" ;
        return view('master/tds_create', $data);
    }

    public function add_hsn($id=''){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_hsn($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
            $data = $this->model->get_master_data('hsn', $id);
        }
        //echo '<pre>';print_r($data);exit;
        $data['id'] = $id;
        $data['title'] = "Add HSN" ;
        return view('master/create_hsn', $data);
    }

    // public function insert_uom()
    // {   
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //      $post=$this->request->getPost();
    //      if(!empty($post))
    //      {
            
    //      }
         
    // }
    public function add_godown($id=''){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_godown($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
            $data = $this->model->get_master_data('godown', $id);
        }
        $data['id'] = $id;
        $data['title'] = "Add godown" ;
        return view('master/create_godown', $data);
    }

    public function add_itemgrp($id = ''){
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
        $data = array();
        $post=$this->request->getPost();
        if(!empty($post))
        {
            $msg=$this->model->insert_edit_itemgrp($post);
            return $this->response->setJSON($msg); 
        }
        if ($id != '') {
            $data = $this->model->get_master_data('itemgrp', $id);
        }
        $data['id'] = $id;
        $data['title'] = "Add Items Group" ;
        return view('master/createitemgrp', $data);
    }

    public function add_item($type = '')
    {
       if (!session('uid')) {
           return redirect()->to(url('auth'));
       }
       $data = array();
       $post = $this->request->getPost();
       if (!empty($post)) {
           $msg = $this->imodel->insert_edit_item($post);
           return $this->response->setJSON($msg);
       }
        $data['type']=$type;
        $data['title']="Add Item";
        return view('master/create_item',$data);
    }

    public function add_cashrece($id=''){
        
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
           // print_r($post);exit;
               $msg = $this->model->insert_edit_cashrece($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
         $data = $this->model->get_master_data('cashrece', $id);
         }
         
         $data['id'] = $id;
        $data['title'] = "Cash Receipt" ;
        return view('master/create_cashreceipt', $data);
    }
   
    public function add_bankreceipt($id='')
    {
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
           // print_r($post);exit;
               $msg = $this->model->insert_edit_bankreceipt($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
         $data = $this->model->get_master_data('bankreceipt', $id);
         }
         
         $data['id'] = $id;
        $data['title'] = "Bank Receipt" ;
        return view('master/create_bankreceipt', $data);
    }
    
    public function add_vehicle($id=''){
        
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_vehicle($post);
            return $this->response->setJSON($msg);
        }
      
        if($id != '') {
            $data = $this->model->get_master_data('vehicle', $id);
        }

        $data['id'] = $id;
        $data['title'] = "Add Vehicle" ;
        return view('master/create_vehicle', $data);
    }

    public function add_glgrp($id = ''){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
       
        $data = array();
       $post = $this->request->getPost();

       if (!empty($post)) {   
           $msg = $this->model->insert_edit_glgrp($post);
           return $this->response->setJSON($msg);
        }
       if ($id != '') {
           $data = $this->model->get_master_data('glgrp', $id);
       }
       $data['id'] = $id;
       $data['title'] = "Add General Ledger Group" ;
       return view('master/create_glgrp', $data);
   }
    public function Createsubitemgrp($id = ''){
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            // $validate = $this->model->validate_itemgrp_data($post);
            // if($validate['st'] == 'fail'){
            //     return $this->response->setJSON($validate);
            // }else{
            //     //$post['annexure'] = 'CUS';
            //     $msg = $this->model->insert_edit_itemgrp($post,$file);
            //     return $this->response->setJSON($msg);
            // }
        }
        if ($id != '') {
            // $data = $this->model->get_master_data('offers', $id);
        }
        $data['id'] = $id;
        $data['title'] = "Add Sub Group" ;
        return view('master/createitemsubgrp', $data);
    }
    
    public function add_transport($id=''){
        if (!session('uid')) {
             return redirect()->to(url('Auth'));
         }
         $data = array();
         $post = $this->request->getPost();
         if (!empty($post)) {
             $msg = $this->model->insert_edit_transport($post);
             return $this->response->setJSON($msg);
         }
        if ($id != '') {
            $data = $this->model->get_master_data('transport', $id);
        }
         $data['id'] = $id;
         $data['title'] = "Add Transport" ;
         return view('master/create_transport', $data);
     }
     public function bank()
     {
         if (!session('uid')){
             return redirect()->to(url('auth'));
         }
         $data['title']="bank";
         return view('master/bank', $data);
     }
     public function add_bank($id= '')
     {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if (!empty($post)){
            $msg = $this->model->insert_edit_bank($post);
            return $this->response->setJSON($msg);
        }
        if($id != '') {
            $data = $this->model->get_master_data('bank', $id);   
        }
        $data['title']="Add Bank";
        
        return view('master/create_bank',$data);
     }
     public function add_screenseries($id= '')
     {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_screenseries($post);
            return $this->response->setJSON($msg);
        }
        // print_r($id);exit;
       if ($id != '') {
           $data = $this->model->get_master_data('screenseries', $id);
        }
         $data['title']="Add Screen Series";
         return view('master/create_screenseries',$data);
     }


    public function Createuom($id = ''){
        if (!session('uid')) {
            return redirect()->to(url('Admin'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg=$this->model->insert_edit_uom($post);
            return $this->response->setJSON($msg);
        }
        if ($id != '') {
            $data = $this->model->get_master_data('uom', $id);
        }
        $data['id'] = $id;
        $data['title'] = "Add Unit of Measure" ;
        return view('master/createuom', $data);
    }
    public function godown(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title'] = "Godown";
        return view('master/godown',$data);
    }
    public function uom()
    {   
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Unit Of Measurement";
        return view('master/uom', $data);
    }

    
    public function supervisor(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title'] = "Supervisor";
        return view('master/supervisor',$data);
    }
    public function add_supervisor($id= '')
    {
       if (!session('uid')) {
           return redirect()->to(url('auth'));
       }
       $data = array();
       $post = $this->request->getPost();
       
       if (!empty($post)) {
        
           $msg = $this->model->insert_edit_supervisor($post);
           return $this->response->setJSON($msg);
       }
      if ($id != '') {
          $data = $this->model->get_master_data('supervisor', $id);   
      }   
       
        $data['title']="Add Bank";
        return view('master/create_supervisor',$data);
    }

    public function warehouse()
    {
        $data['title']="Warehouse";
        return view('master/warehouse',$data);
    }

    public function add_warehouse($id= '')
    {
       if (!session('uid')){
           return redirect()->to(url('auth'));
       }
       $data = array();
       $post = $this->request->getPost();
       
       if (!empty($post)){
           $msg = $this->model->insert_edit_warehouse($post);
           return $this->response->setJSON($msg);
       }
       if ($id != '') {
          $data = $this->model->get_master_data('warehouse', $id);
       }
       $data['title']="Add Warehouse";
       return view('master/create_warehouse',$data);
    }

    public function style()
    {
        $data['title']="Style";
        return view('master/style',$data);
    }
    public function add_style()
    {
        // print_r('jentih');exit;
        $data['title']="Add Style";
        return view('master/create_style',$data);
    }

    public function jv_paricular()
    {
        // print_r('jentih');exit;
        $data['title']="JV Particular";
        return view('master/jv_particular',$data);
    }
    public function add_jvparticular()
    {
        // print_r('jentih');exit;
        $data['title']="Add JV Particular";
        return view('master/create_jvparticular',$data);
    }
    public function glgrp(){
        $data['title']="GL Group";
		return view('master/gl_grp',$data);
    }
    
    public function Getdata($method = '',$type='') {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        
        if ($method == 'broker') {
            $get = $this->request->getGet();
            $this->model->get_broker_data($get);
        }
        
        if ($method == 'hsn') {
            $get = $this->request->getGet();
            $this->model->get_hsn_data($get);
        }   
        
        if ($method == 'tds') {
            $get = $this->request->getGet();
            $this->model->get_tds_data($get);
        }

        if ($method == 'warehouse') {
            $get = $this->request->getGet();
            $this->model->get_warehouse_data($get);
        }
        if ($method == 'itemgrp') {
            $get = $this->request->getGet();
            $this->model->get_itemgrp_data($get);
        }
        if ($method == 'round_off') {
            $get = $this->request->getGet();
            $data = $this->model->get_round_off_data($get);
            return $this->response->setJSON($data);

        }
        if ($method == 'glgrp') {
            $get = $this->request->getGet();
            $this->model->get_glgrp_data($get);
        }
        if ($method == 'uom') {
            $get = $this->request->getGet();
            $this->model->get_uom_data($get);
        }
        if ($method == 'godown') {
            $get = $this->request->getGet();
            $this->model->get_godown_data($get);
        }
        if ($method == 'supervisor') {
            $get = $this->request->getGet();
            $this->model->get_supervisor_data($get);
        }
        if ($method == 'bank') {
            $get = $this->request->getGet();
            $this->model->get_bank_data($get);
        }
        if ($method == 'companygrp') {
            $get = $this->request->getPost();
            $data['suggestions'] = $this->model->search_companygrp_data($get['query']);
            return $this->response->setJSON($data);
        }
        if ($method == 'parent_glgrp') {
            $post = $this->request->getPost();
            $data = $this->model->search_parent_glgrp_data($post);
            return $this->response->setJSON($data);
        }     
        if ($method == 'finish_item') {
            $post = $this->request->getPost();
            $data = $this->model->search_finish_item_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_uom') {
            $post = $this->request->getPost();
            $data = $this->model->search_finishuom_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_uom_data') {
            $post = $this->request->getPost();
            $data = $this->model->search_uom_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'related_hsn') {
            $post = $this->request->getPost();
            $data = $this->model->search_related_hsn_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_broker') {
            $post = $this->request->getPost();
            $data = $this->model->search_broker_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_warehouse') {
            $post = $this->request->getPost();
            $data = $this->model->search_warehouse_data($post);
            return $this->response->setJSON($data);
        } 
        if ($method == 'search_broker_ledger') {
            $post = $this->request->getPost();
            $data = $this->model->search_broker_ledger($post);
            // print_r($data);exit;
            return $this->response->setJSON($data);
        }
        if ($method == 'transport') {
            $get = $this->request->getGet();
            $this->model->get_transport_data($get);
        }
        if ($method == 'vehicle') {
            $get = $this->request->getGet();
            $this->model->get_vehicle_data($get);
        }
        if ($method == 'cashrece') {
            $get = $this->request->getGet();
            $this->model->get_cashrece_data($get);
        }
        if ($method == 'cashpayment') {
            $get = $this->request->getGet();
            $this->model->get_cashpayment_data($get);
        }
        if ($method == 'parent_itemgrp') {
            $post = $this->request->getPost();
            $data['suggestions']=$this->model->parent_itemgrp_data($post['query']);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_itemgrp') {
            $post = $this->request->getPost();
            $data = $this->model->search_itemgrp_data((@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            return $this->response->setJSON($data);
        }
        if ($method == 'search_tds') {
            $post = $this->request->getPost();
            $data = $this->model->search_tds_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_party') {
            $post = $this->request->getPost();
            $data = $this->model->search_party_data($post);
            return $this->response->setJSON($data);
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
        if ($method == 'search_bank') {
            $post = $this->request->getPost();
            //print_r($post);exit;
            $data=$this->model->search_bank_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_master_bank') {
            $post = $this->request->getPost();
            
            $data=$this->model->search_master_bank_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_class') {
             $post = $this->request->getPost();
             $data = $this->model->search_class_data($post);
             return $this->response->setJSON($data);
         }
        if($method == 'search_account') {
            $post = $this->request->getPost();
            $data = $this->model->search_account_data($post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_trans_bank') {
            $post = $this->request->getPost();
            $data = $this->model->search_trans_bank_data($post);
            return $this->response->setJSON($data);
        } 
        if($method == 'search_accountSundry_cred_debt') {
            $post = $this->request->getPost();
            $data = $this->model->search_accountSundry_cred_debt_data($post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_account_mill') {
            $post = $this->request->getPost();
            $data = $this->model->search_account_mill_data($post);
            return $this->response->setJSON($data);
        }  
        if($method == 'search_exp_acl') {
            $post = $this->request->getPost();
            $data = $this->model->search_search_exp_ac_data($post);
            return $this->response->setJSON($data);
        }
        if($method == 'gst_parti') {
            $post = $this->request->getPost();
            $data = $this->model->search_gst_parti($post);
            return $this->response->setJSON($data);
        }
        if($method == 'advance_liability') {
            $post = $this->request->getPost();
            $data = $this->model->search_advance_liability($post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_banktrans_account') {
            $post = $this->request->getPost();
            $data = $this->model->search_banktrans_account_data($post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_bank_account_data') {
            $post = $this->request->getPost();
            $data = $this->model->search_bank_account_data($post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_bank_particular') {
            $post = $this->request->getPost();
            $data = $this->model->search_bank_paticular_data($post);
            return $this->response->setJSON($data);
        } 
        if ($method == 'particular') {
            $post= $this->request->getPost(); 
            $data = $this->model->search_acc_particular_data(@$post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_sale_delivery') {
            $post = $this->request->getPost();
            $data = $this->model->search_sale_delivery($post);
            return $this->response->setJSON($data);
        }
        if($method == 'search_pur_delivery') {
            $post = $this->request->getPost();
            $data = $this->model->search_pur_delivery($post);    
            return $this->response->setJSON($data);
        } 
        if ($method == 'search_particular_item') {
            $post = $this->request->getPost(); 
            $data = $this->model->search_particularitem_data($post);
            return $this->response->setJSON($data);
        } 
        if ($method == 'search_transport') {
            $post = $this->request->getPost();
            $data = $this->model->search_transport_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_vehicle') {    
            $post = $this->request->getPost();
            $data= $this->model->search_vehicle_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_sun_credit') {    
            $post = $this->request->getPost();
            $data= $this->model->search_sun_credit(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_sun_debtor') {
            $post = $this->request->getPost();
            $data= $this->model->search_sun_debtor(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_stform') {
            //print_r("hjdgcjs");exit;
            $get = $this->request->getPost();
            $data['suggestions'] = $this->model->search_stform_data($get['query']);
            return $this->response->setJSON($data);
        }
        if ($method == 'billterm') {
            $get = $this->request->getGet();
            $this->model->get_billterm_data($get);
        }
        if ($method == 'search_purchasechallan') {
            $get = $this->request->getPost();
            $data['suggestions'] = $this->model->search_purchasechallan_data($get['query']);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_screenseries') {
            $get = $this->request->getPost();
            $data['suggestions'] = $this->model->search_screenseries_data($get['query']);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_salevouchertype') {
            $post = $this->request->getPost();
            $data = $this->gmodel->search_salevouchertype_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'search_saleReturnVoucher') {
            $post = $this->request->getPost();
            $data = $this->gmodel->search_saleReturnVoucher_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'search_purchasevouchertype') {
            $post = $this->request->getPost();
            $data = $this->gmodel->search_purchasevouchertype_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_purchaseRetvoucher') {
            $post = $this->request->getPost();
            $data = $this->gmodel->search_purchaseRetvoucher_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'search_purchaseReturnVoucher') {
            $post = $this->request->getPost();
            $data = $this->gmodel->search_purchaseReturnVoucher_data($post);
            return $this->response->setJSON($data);
        }
        //  update trupti 24-11-2022
        if ($method == 'search_igst_account') {
            $post = $this->request->getPost();
            $data= $this->model->search_igst_account_data(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_sgst_account') {
            $post = $this->request->getPost();
            $data= $this->model->search_sgst_account_data(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_cgst_account') {
            $post = $this->request->getPost();
            $data= $this->model->search_cgst_account_data(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_discount_account') {
            $post = $this->request->getPost();
            $data= $this->model->search_discount_account_data(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_round_account') {
            $post = $this->request->getPost();
            $data= $this->model->search_round_account_data(@$post);
            return $this->response->setJSON($data);
        }
        if ($method == 'voucher_type') {
            $post = $this->request->getPost();
            $data = $this->model->search_voucher_type_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_sale_ledger_type') {
            $post = $this->request->getPost();
            $data = $this->model->search_sale_ledger_type_data($post);
            return $this->response->setJSON($data);
        }
        if ($method == 'search_purchase_ledger_type') {
            $post = $this->request->getPost();
            $data = $this->model->search_purchase_ledger_type_data($post);
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
    public function editable_update(){
        $post = $this->request->getPost();
       // print_r($post);exit;
    }
}
?>