<?php 
namespace App\Controllers;
use App\Models\ItemsModel;
use App\Models\MasterModel;
use App\Models\GeneralModel;


class Items extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new ItemsModel();
        $this->gmodel = new GeneralModel();
    }

    public function index()
    {
        if (!session('uid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title']="Itmes";
        return view('Item/item', $data);
    }
    // public function insert_itemgroup()
    // {
    //      $post=$this->request->getPost();
    //     // print_r($post);exit;
    //      if(!empty($post))
    //      {
    //         $msg=$this->model->insert_edit_itemgrp($post);
    //      }
    //      return $this->response->setJSON($msg);
    // }
   
    public function item_grp()
    {   
        $data['title']="Itmes Group";
        return view('master/item_grp', $data);
    }
    
    public function CreateItem($id = ''){
        
        if (!session('uid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        if(!empty($post))
        {
            // echo '<pre>';print_r($post);
            $msg=$this->model->insert_edit_item($post);
            return $this->response->setJSON($msg);
        }
         
        if ($id != '') {
            $data['item'] = $this->model->get_item_data_byid($id);
        }
        $data['id'] = $id;
        $data['title']="Add Items";
        
        // echo '<pre>';print_r($data);exit;
        return view('Item/createitem',$data);
    }
    public function Getdata($method = '') {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        if ($method == 'item') {
            $get = $this->request->getGet();
            $this->model->get_item_data($get);
            //return $this->response->setJSON($data);
        }
    }
    public function Action($method = '') {
        $result = array();
       // print_r($method);exit;
        if ($method == 'Update') {
            $post = $this->request->getPost();
            $result = $this->model->UpdateData($post);
        }
        return $this->response->setJSON($result);
    }   
}
?>