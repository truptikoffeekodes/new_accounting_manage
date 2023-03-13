<?php 

namespace App\Controllers;
use App\Models\StockModel;
use App\Models\GeneralModel;

class Stock extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new StockModel();
        $this->gmodel = new GeneralModel();
        
    }
  
    public function item_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title'] = "Inventory";
        return view('stock/item_stock',$data);
    }

    public function gray_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title'] = "Gray Item Stock";
        return view('stock/gray_stock',$data);
    }

    public function mill_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title'] = "Mill Item Stock";
        return view('stock/mill_stock',$data);
    }
    
    public function finish_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title'] = "Finish Item Stock";
        return view('stock/finish_stock',$data);
    }
    
    public function job_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title'] = "Jobwork Item Stock";
        return view('stock/job_stock',$data);
    }
    
    public function recjob_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title'] = "Jobwork Received Item Stock";
        return view('stock/recjob_stock',$data);
    }

    // Detail Voucher //

    public function gray_voucher_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        
        if($id != ''){
            $data = $this->model->get_Gray_voucher_byitem($id,$post);
            $data['item_id'] = $id;
        }
        $data['title'] = "Gray Item Stock Detail";
        return view('stock/gray_detail_stock',$data);
    }

    public function mill_voucher_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        
        if($id != ''){
            $data['stock'] = $this->model->get_Mill_voucher_byitem($id,$post);
            $data['item_id'] = $id;
        }
        $data['title'] = "Mill Item Stock Detail";
        return view('stock/mill_detail_stock',$data);
    }

    public function finish_voucher_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        
        if($id != ''){
            $data['stock'] = $this->model->get_Finish_voucher_byitem($id,$post);
            $data['item_id'] = $id;
        }
        $data['title'] = "Finish Item Stock Detail";
        return view('stock/finish_detail_stock',$data);
    }

    public function job_voucher_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        
        if($id != ''){
            $data['stock'] = $this->model->get_Job_voucher_byitem($id,$post);
            $data['item_id'] = $id;
        }
        $data['title'] = "Job Item Stock Detail";
        return view('stock/job_detail_stock',$data);
    }

    public function jobRec_voucher_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        
        if($id != ''){
            $data['stock'] = $this->model->get_JobRec_voucher_byitem($id,$post);
            $data['item_id'] = $id;
        }
        $data['title'] = "Job Item Stock Detail";
        return view('stock/jobRec_detail_stock',$data);
    }

    public function avg_price(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
    
        $data['stock'] = $this->model->get_avg_price_byitem($post);
        
        $data['title'] = "AVG Price of Item";
        return view('stock/avg_price',$data);
    }

    public function all_stock(){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data['title'] = "ALL Item Stock Detail";
        return view('stock/all_stock',$data);
    }
    
    public function Getdata($method = ''){
        
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        if ($method == 'item_stock') {
            $post = $this->request->getPost();
            //echo '<pre>';print_r($post);exit;
            $get = $this->request->getGet();
            $this->model->get_item_stock_data($get,$post);
        }

        if ($method == 'Gray_ItemStock') {
            $get = $this->request->getGet();
            $post = $this->request->getPost();
            // print_r($post['warehouse']);exit;
            return $this->model->get_Gray_ItemStock_data($get,$post);
        }
        if ($method == 'Mill_ItemStock') {
            $get = $this->request->getGet();  
            return $this->model->get_Mill_ItemStock_data($get);
        }
        if ($method == 'Finish_ItemStock') {
            $get = $this->request->getGet();  
            $post = $this->request->getPost();
            return $this->model->get_Finish_ItemStock_data($get,$post);
        }
        if ($method == 'Job_ItemStock') {
            $get = $this->request->getGet();  
            return $this->model->get_Job_ItemStock_data($get);
        }
        if ($method == 'recJob_ItemStock') {
            $get = $this->request->getGet();  
            return $this->model->get_RecJob_ItemStock_data($get);
        }
        
    }
}