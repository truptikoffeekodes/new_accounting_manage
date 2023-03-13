<?php 
namespace App\Controllers;
use App\Models\GeneralModel;
use App\Models\TradingModel;

class Trading extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new TradingModel();
        $this->gmodel = new GeneralModel();
        helper('trading');    
    }
    public function dashboard(){
      
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post= $this->request->getPost();
        $gmodel = new GeneralModel;
        $exp = array();
        $gl_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Expenses'),'id,name');
        $gl_inc_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Income'),'id,name');
        $gl_opening_id = $gmodel->get_data_table('gl_group',array('name'=>'Opening Stock'),'id,name');
        $init_total = 0;

        $company_from = session('financial_form');
        $company_to = session('financial_to');   
        
        if(!empty($post)){

            $from =date_create($post['from']) ;                                         
            $to = date_create($post['to']);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            $sale_pur = sale_purchase_vouhcer($post['from'],$post['to']); 
              
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$post['from'],$post['to']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$post['from'],$post['to']);
            
            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$post['from'],$post['to']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$post['from'],$post['to']);
            
            $init_total = 0;

            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->model->get_manualy_stock($post['from'],$post['to']);
            $closing_data = $this->model->get_closing_detail($post['from'],$post['to']);
         
        }else if($company_from != 0000-00-00 && $company_to != 0000-00-00){

            $from =date_create($company_from) ;                                         
            $to = date_create($company_to);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            $sale_pur = sale_purchase_vouhcer($post['from'],$post['to']);
           
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$post['from'],$post['to']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$post['from'],$post['to']);
            
            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$post['from'],$post['to']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$post['from'],$post['to']);
            
            $init_total = 0;

            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->model->get_manualy_stock($post['from'],$post['to']);
            $closing_data = $this->model->get_closing_detail($post['from'],$post['to']);

        }else{
            $sale_pur = sale_purchase_vouhcer();
           
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id']);

            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id']);
            
            $Opening_bal = Opening_bal('Opening Stock');

            $manualy_closing_bal = $this->model->get_manualy_stock();
            $closing_data = $this->model->get_closing_detail();
          
        }
        $opening_stock[$gl_opening_id['id']] = opening_stock_data($gl_opening_id['id']);
        $opening_stock[$gl_opening_id['id']]['name'] = $gl_opening_id['name'];
        $opening_stock[$gl_opening_id['id']]['sub_categories'] = get_opening_stock_sub_grp_data($gl_opening_id['id']);
        
        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);
        $opening_total = subGrp_total($opening_stock,$init_total);
        
        $data['trading'] = $sale_pur;
        
       
        //change calculation closing_bal update 21-01-2023
        $data['trading']['opening_bal'] = $Opening_bal;
        $data['trading']['closing_bal'] = @$closing_data['closing_bal']; 
        $data['trading']['closing_stock'] = @$closing_data['closing_stock'];
        $data['trading']['manualy_closing_bal'] = @$manualy_closing_bal;
        
        $data['trading']['exp'] = @$exp;
        $data['trading']['inc'] = @$inc;
        $data['trading']['opening_stock'] = @$opening_stock;

        $data['trading']['exp_total'] = @$exp_total;
        $data['trading']['inc_total'] = @$inc_total;
        $data['trading']['opening_bal_total'] = @$opening_total;
        //update trupti 03-12-2022
        $data['start_date'] = $post['from']?$post['from']:$company_from;
        $data['end_date'] = $post['to']?$post['to']:$company_to;

        $data['title'] =  "Trading Dashboard";
        return view('trading/trading/dashboard',$data);
    }
    ///////////////////////********************start Account ***********//////////////////////
    // *************start sales account*******************//
    public function sales_voucher(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get= $this->request->getGet();        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = sale_purchase_vouhcer($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = sale_purchase_vouhcer($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Sales Voucher";
        return view('trading/trading/sales_voucher',$data);
    }
    public function salesItem_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = salesItem_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = salesItem_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Monthy Item Sales";
        
        // echo '<pre>';print_r($data);exit;
        return view('trading/trading/salesItem_monthly',$data);

    }
    public function salesItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        // if(!isset($get['page']))
        // {
        //     $get['page']=1;
        // }
        $data = $this->model->salesItem_voucher_wise_data($get);        

        $data['title'] = "Sales Report Voucher Wise";
        return view('trading/trading/salesItem_voucher',$data);
    }
    // *************start sales return account*******************//
    public function salesReturn_voucher(){
        //helper('trading');
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get= $this->request->getGet();        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = sale_purchase_vouhcer($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = sale_purchase_vouhcer($post['from'],$post['to']);
            $data['date'] =$post;
        }
       
        $data['title'] = "Sales Return Voucher";
        return view('trading/trading/salesReturn_voucher',$data);
    }
    public function salesReturnItem_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();    
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = salesReturnItem_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = salesReturnItem_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Monthy Item Sales Return";
         //echo '<pre>';print_r($data);exit;
        return view('trading/trading/salesReturnItem_monthly',$data);

    }
    public function salesReturnItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $get = $this->request->getGet();
       
        $data = $this->model->salesReturnItem_voucher_wise_data($get);        

        $data['title'] = "Sales Return Report Voucher Wise";
        return view('trading/trading/salesReturnItem_voucher',$data);
    }
    // *************start purchase account*******************//
    public function purchase_voucher(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get= $this->request->getGet();        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = sale_purchase_vouhcer($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = sale_purchase_vouhcer($post['from'],$post['to']);
            $data['date'] =$post;
        }
        // echo '<pre>';print_r($data);exit;

        $data['title'] = "Purchase Voucher";
        return view('trading/trading/purchase_voucher',$data);
    }
    public function purchaseItem_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = purchaseItem_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = purchaseItem_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Monthy Item Purchase";
        return view('trading/trading/purchaseItem_monthly',$data);

    }
    public function purchaseItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        }
        $data = $this->model->purchaseItem_voucher_wise_data($get); 
      
               

        $data['title'] = "Purchase Report Voucher Wise";
        return view('trading/trading/purchaseItem_voucher',$data);
    }
    // *************start purchase return account*******************//
    public function purchaseReturn_voucher(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get= $this->request->getGet();        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = sale_purchase_vouhcer($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = sale_purchase_vouhcer($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Purchase Return Voucher";
        return view('trading/trading/purchaseReturn_voucher',$data);
    }
    public function purchaseReturnItem_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();    
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = purchaseReturnItem_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = purchaseReturnItem_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Monthy Item Purchase Return";
        // echo '<pre>';print_r($data);exit;
        return view('trading/trading/purchaseReturnItem_monthly',$data);

    }
    public function purchaseReturnItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        }
        $data = $this->model->purchaseReturnItem_voucher_wise_data($get);        
        //echo '<pre>';Print_r($data);exit;
        
        $data['title'] = "Purchase Return Report Voucher Wise";
        return view('trading/trading/purchaseReturnItem_voucher',$data);
    }
    ///////////////////////********************end Account ***********//////////////////////
    ///////////////////////*****************start trading income and expence ***********//////////////////////
    public function get_income_account_data(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        // print_r($get);exit;
        $data= get_trading_income_account_wise(@$get['from'],@$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['ac_name'] =$acc['name'];
        $data['type'] =$get['type'];

        if($get['type'] == "pl"){
            $data['title'] = "P & L Income Voucher";
        }else{
            $data['title'] = "Trading Income Voucher";
        }
       // echo '<pre>';Print_r($data);exit;
        
        return view('trading/income/income_acc_voucher',$data);
    }
    public function get_expence_account_data(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data= get_trading_expence_account_wise($get['from'],$get['to'],$get['id']);
    
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
       
        $data['type'] =$get['type'];
        if($get['type'] == "pl"){
            $data['title'] = "P & L Expence Voucher";
        }else{
            $data['title'] = "Trading Expence Voucher";
        }
        
        $data['ac_name'] =$acc['name'];
        return view('trading/expence/expence_acc_voucher',$data);
        
    }
    public function generalSales_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_generalSales_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Sales Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/income/general_sales_monthlyAcc',$data);
    }
    public function generalSales_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        }
        $data = $this->model->generalSales_voucher_wise_data($get);        
        $data['title'] = "General Sales Voucher Wise";
        $data['type']=@$get['type'];
        return view('trading/income/generalSales_voucher',$data);
    }
    public function generalPurchase_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_generalPurchase_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Purchase Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        //echo '<pre>';   print_r($data);exit;
        return view('trading/expence/general_purchase_monthlyAcc',$data);
    }
    public function generalPurchase_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        }
        $data = $this->model->generalPurchase_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "General Purchase Voucher Wise";
        
        //echo '<pre>';print_r($data);exit;
        return view('trading/expence/generalPurchase_voucher',$data);
    }

    // ********************** pl and balancesheet used this function*****************//
    public function bank_cash_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_bank_cash_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Bank Cash Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/income/bank_cash_monthlyAcc',$data);
    }
    public function bank_cash_voucher_wise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
       
        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        } 
        $data = $this->model->bank_cash_voucher_wise_data($get);        
       
        $data['title'] = "Bank Cash Voucher Wise";      
        
        return view('trading/income/bank_cash_voucher',$data);
    }
    public function jv_monthly_AcWise(){

        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $data = get_jv_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Journal Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        
        return view('trading/income/jv_monthlyAcc',$data);
    }
    public function jv_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        }
        $data = $this->model->jv_voucher_wise_data($get);        
       
        $data['title'] = "Jounral Voucher Wise";
        $data['type'] = @$get['type'];        
        return view('trading/income/jv_voucher',$data);
    }
    //**********************pl and balancesheet used this function********** */
    public function purchase_bank_cash_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_purchase_bank_cash_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Bank Cash Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        //echo '<pre>';print_r($data);exit;
        return view('trading/expence/purchase_bank_cash_monthlyAcc',$data);
    }
    public function purchase_bankcash_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        } 
        $data = $this->model->purchase_bank_cash_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "Bank Cash Voucher Wise";  
        //echo '<pre>';print_r($data);exit;      
        return view('trading/expence/purchase_bank_cash_voucher',$data);
    }
    public function purchase_jv_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_purchase_jv_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Journal Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        //echo '<pre>';print_r($data);exit;
        return view('trading/expence/purchase_jv_monthlyAcc',$data);
    }
    public function purchase_jv_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        } 
        $data = $this->model->purchase_jv_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "Jounral Voucher Wise";        
        return view('trading/expence/purchase_jv_voucher',$data);
    }
    // closing stock 
    public function oc_closing(){
        if(!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title'] = "Closing Data" ;
        return view('trading/closing', $data);

    }
    public function add_closing($id=''){
        if(!session('cid')) {
            return redirect()->to(url('company'));
        }
       $data = array();
       $post = $this->request->getPost();
       
       if(!empty($post))
       {
           $msg=$this->model->insert_edit_closing($post);
           return $this->response->setJSON($msg); 
       }
       if ($id != '') {
           $data['closing'] = $this->model->get_OCstock_data($id);
       }
       $data['title'] = "Add Closing" ;
       return view('trading/add_closing', $data);

    }
    public function Getdata($method = '',$type='') {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        if ($method == 'closing') {
            $get = $this->request->getGet();
            $this->model->get_closing_data($get);
        }
    }
    public function Trading_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->trading_xls_export_data($post);
        }else{       
            $post['from'] = session('financial_form'); 
            $post['to'] = session('financial_to'); 
            $data = $this->model->trading_xls_export_data($post);   
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function sales_item_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->sales_item_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function sales_return_item_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->sales_return_item_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function purchase_item_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->purchase_item_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function purchase_return_item_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->purchase_return_item_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function get_income_sub_grp(){
        
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
      
        $inc[$get['id']] = trading_income_data($get['id'],$get['from'],$get['to']);
        
        $inc[$get['id']]['name'] = $get['name'];
        if($get['type'] == 'pl'){
            $data['title'] =  "P & L Income Sub Group";
            $inc[$get['id']]['sub_categories'] = get_PL_income_sub_grp_data($get['id'],$get['from'],$get['to']);
        }else{
            $data['title'] =  "Trading Income Sub Group";
            $inc[$get['id']]['sub_categories'] = get_income_sub_grp_data($get['id'],$get['from'],$get['to']);
        }
        
        $init_total = 0;
        $inc_total = subGrp_total($inc,$init_total);

        $data['trading']['inc'] = @$inc;

        $data['trading']['inc_total'] = @$inc_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];
        $data['ac_id'] = $get['id'];
        $data['ac_name'] = $get['name'];
        $data['type'] = $get['type'];
        
        return view('trading/income/sub_group_detail',$data);

    }
    public function get_expence_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $inc[$get['id']] = pl_expense_data($get['id'],$get['from'],$get['to']);
        $inc[$get['id']]['name'] = $get['name'];
        
        if($get['type'] == 'pl'){
            $data['title'] =  "P & L Income Sub Group";
            $inc[$get['id']]['sub_categories'] = get_PL_expense_sub_grp_data($get['id'],$get['from'],$get['to']);
        }else{
            $data['title'] =  "Trading Income Sub Group";
            $inc[$get['id']]['sub_categories'] = get_expense_sub_grp_data($get['id'],$get['from'],$get['to']);
        }
        $init_total = 0;
        $inc_total = subGrp_total($inc,$init_total);

        $data['trading']['inc'] = @$inc;

        $data['trading']['inc_total'] = @$inc_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];
        $data['ac_id'] = $get['id'];
        $data['ac_name'] = $get['name'];
        $data['type'] = $get['type'];

        //$data['title'] =  "Trading Expence Sub Group";
        
        return view('trading/expence/sub_group_detail',$data);

    }
    public function get_opening_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $opening_stock[$get['id']] = opening_stock_data($get['id'],$get['from'],$get['to']);
        $opening_stock[$get['id']]['name'] = $get['name'];
        
       
            $data['title'] =  "Trading Opening Stock";
            $opening_stock[$get['id']]['sub_categories'] = get_opening_stock_sub_grp_data($get['id'],$get['from'],$get['to']);
    
        $init_total = 0;
        $opening_total = subGrp_total($opening_stock,$init_total);

        $data['trading']['opening_stock'] = @$opening_stock;

        $data['trading']['opening_total'] = @$opening_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];
        $data['ac_id'] = $get['id'];
        $data['ac_name'] = $get['name'];
        //$data['type'] = $get['type'];

        //$data['title'] =  "Trading Expence Sub Group";
        
        return view('trading/trading/sub_group_opening_detail',$data);

    }

   

    
    


}