<?php 
namespace App\Controllers;
use App\Models\MillingModel;
use App\Models\MasterModel;
use App\Models\GeneralModel;


class Milling extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new MillingModel();
        $this->gmodel = new GeneralModel();
    }
    
    public function Grey_Challan()
    {
        if(!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Grey/Finish Challan";
        return view('Milling/gray_challan', $data);
    }
    
    public function mill_sale_challan()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title']="Gray/Finish Sale Challan";
        return view('Milling/mill_sale_challan', $data);
    }  
    
    public function mill_sale_invoice()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title'] = "Gray/Finish Sale Invoice";
        return view('Milling/mill_sale_invoice', $data);
    }
    
    public function mill_sale_return()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title']="Gray/Finish Sale Return";
        return view('Milling/mill_sale_return', $data);
    }

    public function mill_challan()
    {
        if(!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title']="Mill Issue Challan";
        return view('Milling/mill_challan', $data);
    }

    public function mill_rec()
    {
        if(!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title']="Mill Received";
        return view('Milling/mill_Rec', $data);
    }

    public function Grey_invoice()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Gray/Finish Invoice";
        return view('Milling/grey_invoice', $data);
    }
    // Return View //
    public function retGrayFinish()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Gray/Finish Return (Debit Note)";
        return view('Milling/retGrayFinish', $data);
    }

    public function return_mill()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title']="Mill Return";
        return view('Milling/return_mill', $data);
    }

    public function mill_return()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title']="Return Mill";
        return view('Milling/mill_return', $data);
    }
    
    public function return_jobwork()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Jobwork Return";
        return view('Milling/return_jobwork', $data);
    }

    public function send_to_mill($id){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post['challan_id'] =$id;
        $post['type'] = 'mill';
        if(!empty($id)){   
            $challan_data =$this->model->search_challan_data($post);
        }

        $data['challan']['challan_no'] = $challan_data[0]['id'];
        $data['challan']['challan_name'] = $challan_data[0]['text'];
        $data['item'] = $challan_data[0]['item'];

        $getId = $this->gmodel->get_lastId('mill_challan');
        $data['current_id'] = $getId + 1;
        
        $data['title'] = "Send To Mill";
        return view('Milling/add_mill',$data);

    }
    

    public function Add_grey($id='')
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_grey($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        
        if($id != '') {
            $data = $this->model->get_greyinvoice_data($id);
            
        }
        
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('grey');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Add Grey/Finish Invoice";
        
        return view('Milling/add_grey',$data);
    }

    // Sale Mill Insert Edit // 

    public function add_Mill_SaleChallan($id='')
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_MillSaleChallan($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        
        if($id != '') {
            $data = $this->model->get_MillSaleChallan_byID($id);
        }
        
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_lastId('saleMillChallan');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;   
        $data['title'] = "Add Gray/Finish Sale Challan";

        return view('Milling/add_MillSaleChallan',$data);
    }

    public function add_Mill_SaleInvoice($id='')
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_MillSaleInvoice($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        
        if($id != '') {
            $data = $this->model->get_MillSaleInvoice_byID($id);
        }
        
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('saleMillInvoice');
        
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;   
        $data['title'] = "Add Gray/Finish Invoice";

        return view('Milling/add_MillSaleInvoice',$data);
    }
    
    // Return Insert Edit //

    public function add_Mill_SaleReturn($id='')
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_MillSaleReturn($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        
        if($id != '') {
            $data = $this->model->get_MillSaleReturn_byID($id);
        }
        
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('saleMillReturn');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;   
        $data['title'] = "Add Gray/Finish Sale Return";

        return view('Milling/add_MillSaleReturn',$data);
    }

    public function Add_retGrayFinish($id='')
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_retGrayFinish($post);
            return $this->response->setJSON($msg);
        }

        $gmodel=new GeneralModel();
        if($id != '') {
            $data = $this->model->get_retGrayFinish_byID($id);
        }
       
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('retGrayFinish');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;   
        $data['title'] = "Add Gray/Finish Return";

        return view('Milling/add_purchase_return',$data);
    }

    public function Add_returnMill($id = '')
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_returnMill($post);
            return $this->response->setJSON($msg);
        }

        $gmodel=new GeneralModel();
        if($id != '') {
            $data = $this->model->get_returnmill_byID($id);

            // echo '<pre>';print_r($data);exit;
        }
        
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('return_mill');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Add Mill Return";

        return view('Milling/add_MillReturn',$data);
    }

    public function Add_return_jobwork($id = '')
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_returnJobwork($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        if($id != '') {
            $data = $this->model->get_returnJobwork_byID($id);
        }
        
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('return_jobwork');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Add Jobwork Return";

        return view('Milling/add_JobworkReturn',$data);
    }
    
    // Milling Insert Edit //
    
    public function Add_grey_challan($id='')
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){   
            $msg=$this->model->insert_edit_greyChallan($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        if ($id != '') {
            $data = $this->model->get_greychallan_data($id);
        }
        // echo '<pre>';print_r($data);exit;
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('grey_challan');

        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Add Grey/Finish Challan";
        
        return view('Milling/add_grey_challan',$data);
    }

    public function add_rec_mill($id =''){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        if(!empty($post)){
            $msg=$this->model->insert_edit_millRec($post);
            return $this->response->setJSON($msg);
        }
        
        if($id != '') {
            $data = $this->model->get_mill_rec_byID($id);    
        }

        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('millRec');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Add Mill Received";
        return view('Milling/add_MiilRec',$data);
    }
 
    public function add_millSend($id = ''){
        
        if (!session('cid')) {
            return redirect()->to(url('auth'));
        }
        
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $msg=$this->model->insert_edit_millSend($post);
            return $this->response->setJSON($msg);
        }
        $gmodel=new GeneralModel();
        
        if($id != '') {
            $data = $this->model->get_mill_challan_byID($id);    
        }
        
        $getId = $this->gmodel->get_srlastId('mill_challan');
        $data['current_id'] = $getId + 1;

        $data['title'] = "Add Mill Issue";
        return view('Milling/add_mill',$data);
    }

    public function Add_Challantaka($tr_id,$voucher_id=''){

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        
        if($voucher_id != ''){
            $data['grey_taka'] = $this->model->get_grayChallanTaka($tr_id,$voucher_id);
        }
        $data['title'] = "Add Taka";
        $data['tr_id'] = $tr_id;
        $last_taka= $this->gmodel->get_data_table('greyChallan_taka',array(),'MAX(taka_no) as last_taka'); 
       
        $data['last_taka'] = $last_taka['last_taka'] + 1;
        if($last_taka['last_taka'] == '' || $last_taka['last_taka'] == 0 || $last_taka['last_taka'] == 'null' ){    
            $data['last_taka'] = 1;
        }
        return view('Milling/add_ChallanTaka', $data);
    }

    public function Add_millingtaka($tr_id,$greychallan_id,$voucher_id = ''){
        
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $post = $this->request->getPost();
        
        if($tr_id != ''){
            $data['grey_taka'] = $this->model->get_grayChallanTaka_data($greychallan_id,$tr_id,$voucher_id);
        }

        // echo '<pre>';print_r($data['grey_taka']);exit;
        $data['title'] = "Add Taka";
        $data['tr_id'] = $tr_id;
        
        // $last_taka = $this->gmodel->get_data_table('greyChallan_taka',array(),'MAX(taka_no) as last_taka');
        // $data['last_taka'] = $last_taka['last_taka'];
        
        // if($last_taka['last_taka'] == '' || $last_taka['last_taka'] == 0 || $last_taka['last_taka'] == 'null' ){    
        //     $data['last_taka'] = 1;
        // }
        // echo '<pre>';print_r($data);exit;
        return view('Milling/add_millingTaka',$data);
    }
    
    public function Add_returntaka($voucher_id,$tr_id,$id=''){
        
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        if($voucher_id != ''){
            $getdata = $this->model->get_grayChallanTaka_return($voucher_id,$tr_id,$id);
            $data['grey_taka']  = @$getdata['taka'];
        }
        $data['total_taka'] = @$getdata['total_taka'];
        $data['total_meter'] = @$getdata['total_meter'];
        $data['title'] = "Add Taka";
        $data['tr_id'] = $tr_id;
        
        return view('Milling/add_PurchaseRetTaka',$data);
    }

    public function Add_MillSaleReturntaka($voucher_id,$tr_id,$id=''){
        
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        if($voucher_id != ''){
            $getdata = $this->model->get_MillSaleChallanTaka_return($voucher_id,$tr_id,$id);
            $data['sale_taka']  = $getdata['taka'];
        }
        $data['total_taka'] = @$getdata['total_taka'];
        $data['total_meter'] = @$getdata['total_meter'];
        $data['title'] = "Add Taka";
        $data['tr_id'] = $tr_id;
        // echo '<pre>';print_r($data);exit;
        return view('Milling/add_MillSaleRetTaka',$data);
    }

    public function Add_ReturnMillTaka($voucher_id,$tr_id,$id=''){
        
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        if($voucher_id != ''){
            $getdata = $this->model->get_MillChallanTaka_return($voucher_id,$tr_id,$id);
           
            $data['mill_taka']  = $getdata['taka'];
        }

        $data['total_taka'] = @$getdata['total_taka'];
        $data['total_meter'] = @$getdata['total_meter'];
        
        $data['title'] = "Add Taka";
        $data['tr_id'] = $tr_id;
        return view('Milling/add_MillReturnTaka',$data);
    }

    public function Add_MillRecTaka($voucher_id,$item_id,$id = ''){
        
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $post['voucher_id'] = $voucher_id;
        $post['item_id'] = $item_id;
        $post['id'] = $id;

        $data['mill_taka'] = $this->model->get_millChallanTaka_data($post);
        
        $data['tr_id'] = $item_id;
        $data['title'] = "Received Mill";
        
        return view('Milling/add_MillRecTaka', $data);
    }

    public function Add_SendJobTaka($item_id,$job_itemID = ''){

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $post['item_id'] = $item_id;
        $post['job_itemID'] = $job_itemID;
        
        $data = $this->model->get_FinishTaka($post);
        $data['tr_id'] = $item_id;
        
        return view('Milling/add_SendJobTaka', $data);
    }

    public function Add_SaleTaka($item_id,$job_itemID = ''){

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $post['item_id'] = $item_id;
        $post['job_itemID'] = $job_itemID;
        
        $data = $this->model->get_SaleTaka($post);
        $data['tr_id'] = $item_id;
        return view('Milling/add_MillSaleTaka', $data);
    }

    // public function Add_taka($tr_id,$ids=''){
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //     $post = $this->request->getPost();
        
    //     if($ids != ''){
    //         $data['grey_taka'] = $this->model->get_grayTaka_data($ids);
    //     }
    //     // echo '<pre>';print_r($data['grey_taka']);exit;
    //     $data['title'] = "Add Item";
    //     $data['tr_id'] = $tr_id;
        
    //     $last_taka= $this->gmodel->get_data_table('grey_taka',array(),'MAX(taka_no) as last_taka'); 
       
    //     $data['last_taka'] = $last_taka['last_taka'];
    //     if($last_taka['last_taka'] == '' || $last_taka['last_taka'] == 0 || $last_taka['last_taka'] == 'null' ){    
    //         $data['last_taka'] = 1;
    //     }
    //     return view('Milling/add_taka', $data);
    // }

    public function  insert_taka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if(!empty($post)){
            $validation = $this->model->validate_taka($post);
        
            if($validation['st'] == 'Success'){
        
                $msg=$this->model->insert_edit_taka($post);
                return $this->response->setJson($msg);
            }else{

                return $this->response->setJson($validation);
            }
        }
    }

    public function  insert_Challantaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if(!empty($post)){
            $validation = $this->model->validate_taka($post);
        
            if($validation['st'] == 'Success'){
                $msg=$this->model->insert_edit_Challantaka($post);
                return $this->response->setJson($msg);
            }else{

                return $this->response->setJson($validation);
            }
        }
    }

    public function  insert_Milltaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if(!empty($post)){
            $msg=$this->model->insert_edit_Milltaka($post);
            return $this->response->setJson($msg);
        }
    }

    public function  insert_GrayFinish_Rettaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
       
        if(!empty($post)){
            $msg=$this->model->insert_edit_RetGrayFinish_taka($post);
            return $this->response->setJson($msg);
        }
    }
    
    public function  insert_MillSale_Rettaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
       
        if(!empty($post)){
            $msg=$this->model->insert_edit_MillSale_taka($post);
            return $this->response->setJson($msg);
        }
    }

    public function  insert_Mill_ReturnTaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
       
        if(!empty($post)){
            $msg=$this->model->insert_edit_Mill_ReturnTaka($post);
            return $this->response->setJson($msg);
        }
    }

    public function  insert_SendJobTaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $msg=$this->model->insert_edit_SendJobTaka($post);
            return $this->response->setJson($msg);
        }
    }

    public function  insert_SaleTaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if(!empty($post)){
            $msg=$this->model->insert_edit_SaleTaka($post);
            return $this->response->setJson($msg);
        }
    }

    public function  insert_RecJobTaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        
        if(!empty($post)){
            $msg=$this->model->insert_edit_RecJobTaka($post);
            return $this->response->setJson($msg);
        }
    }

    
    public function insert_MillRectaka(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $msg=$this->model->insert_edit_MillRectaka($post);
            return $this->response->setJson($msg);
        }
    }

    // public function add_finish_item($tr_id,$id='')
    // {
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }

    //     $post = $this->request->getPost();
        
    //     if ($id != '') {
    //         $data['grayitem'] = $this->model->get_grayitem_data($id);
    //     }
    //     // echo '<pre>';print_r($data);exit;
    //     $data['title']="Add Item";
    //     $data['tr_id']=$tr_id;

    //     return view('Milling/add_finish_item', $data);
    // }

    // public function add_jobwork_item($tr_id,$id='')
    // {
        
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //     $post = $this->request->getPost();
        
    //     if($id != '') {
    //         $data['jobitem'] = $this->model->get_jobitem_data($id);
    //     }
    //     $data['title']="Add Item";
    //     $data['tr_id']=$tr_id;
    //     return view('Milling/add_jobwork_item',$data);
    // }

    // public function add_job_FinishItem($tr_id,$id='',$jid='')
    // {
    //     if(!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //     $post = $this->request->getPost();
    //     if ($id != '') {
    //         $data['jobitem'] = $this->model->get_jobitem_data($id);
    //     }
    //     // echo '<pre>';print_r($data);exit;
    //     $data['title']="Add Item";
    //     $data['tr_id']=$tr_id;
    //     $data['jid']=$jid;
    //     // echo '<pre>'; print_r($data);exit;
    //     return view('Milling/add_job_FinishItem',$data);
    // }

    public function Gray_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->model->get_greychallan_data($id);  
        }
         //echo '<pre>';print_r($data);exit; 
        $data['title']="Challan Detail";
        return view('Milling/grey_detail', $data);
    }
   
    // public function Finish()
    // {
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //     $data['title']="Finish";
    //     return view('Milling/finish', $data);
    // }

    // public function Add_finish($id='')
    // {
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //     $data = array();
    //     $post = $this->request->getPost();
       
    //     if(!empty($post)){
               
    //         $msg=$this->model->insert_edit_finish($post);
    //         return $this->response->setJSON($msg);
    //     }
    //     if ($id != '') {
    //         $data = $this->model->get_finishinvoice_data($id);
    //     }
    //     // echo '<pre>'; print_r($data);exit;
    //     $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
    //     $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
    //     $getId = $this->gmodel->get_lastId('finish_mill');
        
    //     $data['tax'] = $tax; 
    //     $data['id'] = $id;
    //     $data['current_id'] = $getId + 1;
    //     $data['title'] = "Add Finish";
        
    //     return view('Milling/add_finish',$data);
    // }
    
    public function add_finish_screen(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if(!empty($post)){
            $msg = $this->model->insert_finish_screen($post);
            return $this->response->setJSON($msg);
        }
        return view('Milling/create_finishScreen');
    }
    
    public function add_finishJob_screen(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        if(!empty($post)){
            $msg = $this->model->insert_finish_screen($post);
            return $this->response->setJSON($msg);
        }
        return view('Milling/create_finishJob_screen');
    }


    // public function Add_finishitem($id='')
    // {
        
    //     if (!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }
    //     if ($id != '') {
    //         $data = $this->model->get_greychallan_list($id);
    //     }
    //     //echo '<pre>'; print_r($data);
    //     $data['title']="Add Finish Item";
    //     return view('Milling/add_finishitem', $data);
    // }

    public function milling_stock()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Zoom Report";
        return view('Milling/milling_stock', $data);
    }

    // public function jobwork_stock()
    // {
    //     if(!session('cid')) {
    //         return redirect()->to(url('company'));
    //     }

    //     $data['title']="Jobwork Zoom Report";
    //     return view('Milling/jobwork_stock', $data);
    // }

    public function update_finishitem($id)
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        if ($id != '') {
            $data = $this->model->get_finishinvoice_list($id);
        }
        //echo '<pre>';print_r($data);exit;
        $data['title']="Add Finish Item";
        return view('Milling/add_finishitem', $data);
    }

    public function Finish_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if($id != ''){
            $data = $this->model->get_finishinvoice_data($id);  
        }
         
        $data['title']="Invoice Detail";
        return view('Milling/finish_detail', $data);
    }

    public function Jobwork()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Jobwork Issue";
        return view('Milling/jobwork', $data);
    }

    public function Jobwork_rec()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Jobwork Received";
        return view('Milling/rec_jobwork', $data);
    }

    public function Add_jobwork($id='')
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){       
            $msg=$this->model->insert_edit_jobwork($post);
            return $this->response->setJSON($msg);
        }
        if($id != ''){
            $data = $this->model->get_jobworkdata($id);
        }
        $gmodel =new GeneralModel();
        $getId = $this->gmodel->get_srlastId('sendJobwork');

        $data['id'] = $id;
        $data['title'] = "Jobwork Issue";
        $data['current_id'] = $getId + 1;
        
        return view('Milling/create_jobwork',$data);
    }

    public function Add_rec_jobwork($id='')
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data = array();
        $post = $this->request->getPost();

        if(!empty($post)){       
            $msg=$this->model->insert_edit_recJob($post);
            return $this->response->setJSON($msg);
        }
        if($id != '') {
            $data = $this->model->get_Recjobworkdata($id);
        }
        
        $gmodel = new GeneralModel();
        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
        $getId = $this->gmodel->get_srlastId('recJobwork');

        $data['title'] = "Jobwork Received";
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
 
        return view('Milling/create_rec_jobwork',$data);
    }

    public function Add_jobworkitem($id='')
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        if ($id != '') {
            $data = $this->model-> get_jobworkdata($id);
            
        }
        
        $data['title'] = "Add Jobwork Item";
        return view('Milling/add_jobworkitem', $data);
    }

    public function jobwork_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->model->get_jobworkdata($id);  
        }
        $data['title']="Challan Detail";
        return view('Milling/jobwork_detail', $data);
    }

    
    public function Getdata($method = '') {
        
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }
        $cid = session('cid');

        if ($method == 'Item') {
            $post= $this->request->getPost();
            $data= $this->model->search_item_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'finish_Item') {
            $post= $this->request->getPost();
            $data = $this->model->search_finish_item(@$post['searchTerm']);
            return $this->response->setJSON($data);
        }

        if ($method == 'GrayFinish_sale_Item') {
            $post= $this->request->getPost();
            $data = $this->model->search_GrayFinish_sale_Item(@$post);
            return $this->response->setJSON($data);
        }  

        if ($method == 'finishjob_item') {
            $post= $this->request->getPost();
            $data = $this->model->search_finishJob_item(@$post['searchTerm']);
            return $this->response->setJSON($data);
        }

        if ($method == 'get_challan') {
            $post = $this->request->getPost();
            $data = $this->model->search_challan_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'get_gray_finish_challan') {
            $post = $this->request->getPost();
            $data = $this->model->search_gray_finish_challan_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'get_invoice') {
            $post = $this->request->getPost();
            $data = $this->model->search_invoice_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'get_MillSaleInvoice_Return') {
            $post = $this->request->getPost();
            $data = $this->model->search_MillSaleInvoice_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'get_challan_mill') {
            $post = $this->request->getPost();
            $data = $this->model->search_challan_mill($post);
            return $this->response->setJSON($data);
        }
        
        if ($method == 'challan_item') {
            $post = $this->request->getPost();
            $data = $this->model->search_challan_item($post);
            return $this->response->setJSON($data);
        }
        
        if ($method == 'jobwork') {
            $post = $this->request->getPost();
            $data = $this->model->search_jobwork_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'grey_invoice') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_grey_data($get);
        }

        if ($method == 'grey_challan') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_grey_challan_data($get);
        } 

        if ($method == 'retGrayFinish') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_retGrayFinish_data($get);
        }
        
        if ($method == 'return_mill') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_return_mill_data($get);
        } 
        
        if ($method == 'return_jobwork') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_return_jobwork_data($get);
        } 

        if ($method == 'search_mill_challan') {
            $post = $this->request->getPost();
            $data = $this->model->search_MillChallan_data($post);
            return $this->response->setJSON($data);
        }

        if($method == 'search_MillChallanForReturn'){
            $post = $this->request->getPost();
            $data = $this->model->search_MillChallanForReturn_data($post);
            return $this->response->setJSON($data);
        }

        if($method == 'search_jobChallanForReturn'){
            
            $post = $this->request->getPost();
            $data = $this->model->search_JobChallanForReturn_data($post);
            // echo '<pre>';print_r($data);exit;
            return $this->response->setJSON($data);
        }

        if ($method == 'mill_challan') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_mill_challan_data($get);
        }

        
        if ($method == 'mill_SaleInvoice') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_millSale_invoice_data($get);
        }
        if ($method == 'mill_SaleReturn') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_millSale_return_data($get);
        }

        if ($method == 'mill_rec') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_mill_rec_data($get);
        }

        if ($method == 'jobwork_data') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_jobwork_data($get);
        }

         if ($method == 'mill_SaleChallan') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_mill_SaleChallan_data($get);
        }
        
        if ($method == 'rec_jobwork') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_RecJobwork_data($get);
        }

    }
  
    public function Action($method = '') {
        $result = array();
      
        if ($method == 'Update') {

            $post = $this->request->getPost();
            // print_r($post);exit;
            $result = $this->model->UpdateData($post);
        }
        return $this->response->setJSON($result);
    }
}
?>