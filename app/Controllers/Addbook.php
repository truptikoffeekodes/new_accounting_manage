<?php

namespace App\Controllers;

use App\Models\AddbookModel;
use App\Models\GeneralModel;
use App\Models\MasterModel;

class Addbook extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface$request, \CodeIgniter\HTTP\ResponseInterface$response, \Psr\Log\LoggerInterface$logger)
    {

        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new AddbookModel();
        $this->gmodel = new GeneralModel();
        $this->mmodel = new MasterModel();

    }
    // sales register
    public function Sales_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Sales_register($post);
            
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_register($post);
        }
        $data['sales'] = salesItem_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }        
        $data['type'] = "sales";
        $data['title'] = "Sales Data";
        $type = "sales";

        
        return view('addbook/sales_register', $data);
    }
    public function salesItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        if(!isset($get['page']))
        {
            $get['page']=1;
        }
        //echo '<pre>';Print_r($get);exit;
        
        $data = $this->model->salesItem_voucher_wise_data($get);        

        $data['title'] = "Sales Report Voucher Wise";
        return view('addbook/salesItem_voucher',$data);
    }
    //sales gst register
    public function Sales_gst_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Sales_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "Sales GST Data";
   
        return view('addbook/sales_gst_register', $data);
    }
    public function Sales_gst_register2()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Sales_gst_register2($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "Sales GST Data";
   
        return view('addbook/sales_gst_register2', $data);
    }
    // general sales register
    public function Gnrl_sales_register()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Sales_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Sales_register($post);
        }
        
        $data['sales'] = GnrlsalesItem_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }
        $data['type'] = "General Sales";
        $data['title'] = "General Sales Data";
        

        return view('addbook/gnrl_sales_register', $data);
    }
    public function gnrl_sales_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->gnrl_sales_voucher_wise_data($get);        

        $data['title'] = "General Sales Voucher Wise";
        return view('trading/gnrl_sales_voucher',$data);
    } 
    //purchase register
    public function Purchase_register()
    {
        $data = array();
        $post = $this->request->getPost();
    

        if (!empty($post)) {
            $data = $this->model->get_Purchase_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            
            $data = $this->model->get_Purchase_register($post);
        }

        $data['purchase'] = purchaseItem_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        $data['type'] = "Purchase";
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }

        $data['title'] = "Purchase Data";
        $type = "Purchase";
       
        return view('addbook/purchase_register', $data);

    }
    public function purchaseItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseItem_voucher_wise_data($get);        

        $data['title'] = "Purchase Report Voucher Wise";
        return view('addbook/purchaseItem_voucher',$data);
    }
    // purchase gst register
    public function Purchase_gst_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Purchase_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Purchase_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "Purchase GST Data";
   
        return view('addbook/purchase_gst_register', $data);
    }
    public function Purchase_gst_register2()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Purchase_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Purchase_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "Purchase GST Data";
   
        return view('addbook/purchase_gst_register2', $data);
    }
    //general purchase register
    public function Gnrl_purchase_register()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Purchase_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Purchase_register($post);
        }
        
        $data['purchase'] = Gnrlpurchase_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }
        
        $data['type'] = "General Purchase";
        $data['title'] = "General Purchase Data";

        return view('addbook/gnrl_purchase_register', $data);
    }
    public function gnrl_purchase_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->gnrl_purchase_voucher_wise_data($get);        

        $data['title'] = "General Purchase Voucher Wise";
        
        return view('trading/gnrl_purchase_voucher',$data);
    }
    //credit note
    public function Sales_return_register()
    {
        $data = array();
        $post = $this->request->getPost();


        if (!empty($post)) {
            $data = $this->model->get_Sales_return_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_return_register($post);
        }
        
        $data['sales'] = salesReturnItem_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }

        $data['type'] = "Sales Return";
        $data['title'] = "Sales Return Data";
        $type = "sales";

        return view('addbook/creditnote', $data);
    }
    public function salesReturnItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->salesReturnItem_voucher_wise_data($get);        

        $data['title'] = "Sales Return Report Voucher Wise";
        return view('addbook/salesReturnItem_voucher',$data);
    }
    public function View_filter($type = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $data = get_filter_view($type);
        $post = $this->request->getGet();

        if (!empty($post)) {

            $type = @$post['type'];
            $mode = @$post['mode'];
            $account_id = @$post['account_id'];
            $start_date = @$post['from'];
            $end_date = @$post['to'];
            
            $data = get_filter_view($type, $mode, $account_id, $start_date, $end_date);
        }

        if (date('m') <= '03') {

            $apryear = date('Y') - 1;
            $mayyear = date('Y') - 1;
            $junyear = date('Y') - 1;
            $julyyear = date('Y') - 1;
            $ogstyear = date('Y') - 1;
            $sepyear = date('Y') - 1;
            $octyear = date('Y') - 1;
            $novyear = date('Y') - 1;
            $decyear = date('Y') - 1;
            $janyear = date('Y');
            $febyear = date('Y');
            $marchyear = date('Y');
            //$start_date = $year.'-04-01';
        } else {
            $apryear = date('Y');
            $mayyear = date('Y');
            $junyear = date('Y');
            $julyyear = date('Y');
            $ogstyear = date('Y');
            $sepyear = date('Y');
            $octyear = date('Y');
            $novyear = date('Y');
            $decyear = date('Y');
            $janyear = date('Y') + 1;
            $febyear = date('Y') + 1;
            $marchyear = date('Y') + 1;
        }

        if (!empty($type)) {
            $data['jan'] = getmonth_total($type, $janyear . '-01-01', $janyear . '-01-31');
            $data['feb'] = getmonth_total($type, $febyear . '-02-01', $febyear . '-02-28');
            $data['march'] = getmonth_total($type, $marchyear . '-03-01', $marchyear . '-03-31');
            $data['apr'] = getmonth_total($type, $apryear . '-04-01', $apryear . '-04-30');
            $data['may'] = getmonth_total($type, $mayyear . '-05-01', $mayyear . '-05-31');
            $data['jun'] = getmonth_total($type, $junyear . '-06-01', $junyear . '-06-30');
            $data['july'] = getmonth_total($type, $julyyear . '-07-01', $julyyear . '-07-31');
            $data['ogst'] = getmonth_total($type, $ogstyear . '-08-01', $ogstyear . '-08-31');
            $data['sep'] = getmonth_total($type, $sepyear . '-09-01', $sepyear . '-09-30');
            $data['oct'] = getmonth_total($type, $octyear . '-10-01', $octyear . '-10-31');
            $data['nov'] = getmonth_total($type, $novyear . '-11-01', $novyear . '-11-30');
            $data['dec'] = getmonth_total($type, $decyear . '-12-01', $decyear . '-12-31');
            
        }

        if ($type == 'sales') {
            $data['type'] = "sales";
            $data['title'] = "Sales Data";
        } else if ($type == 'purchase') {
            $data['type'] = "purchase";
            $data['title'] = "Purchase Data";
        } else if ($type == 'creditnote') {
            $data['type'] = "creditnote";
            $data['title'] = "Credit Note Data";
        } else if ($type == 'debitnote') {
            $data['type'] = "debitnote";
            $data['title'] = "Debit Note Data";
        } else if ($type == 'payment') {
            $data['type'] = "payment";
            $data['title'] = "Payment Data";
        } else if ($type == 'receipt') {
            $data['type'] = "receipt";
            $data['title'] = "Receipts Data";
        } else if ($type == 'cash') {
            $data['type'] = "cash";
            $data['title'] = "Cash Data";
        } else if ($type == 'bank') {
            $data['type'] = "bank";
            $data['title'] = "Bank Data";
        } else if ($type == 'journal') {
            $data['type'] = "journal";
            $data['title'] = "Journal Data";
        } else if ($type == 'ledger') {
            $data['account'] = $this->model->get_account_data();
            $data['type'] = "ledger";
            $data['title'] = "Ledger Data";
        }

        return view('addbook/account_book', $data);
    }
    public function view_bill()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $post = $this->request->getPost();
        
        if (!empty($post)) {
            $data = $this->model->get_transaction_view($post);
        }

        $data['title'] = "Show Bill";

        return view('addbook/view_bill', $data);
    }
    public function add_view()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();

        $data['title'] = "Show Bill";
        //print_r($data);exit;
        return view('reporting/view_bill', $data);
    }
    public function Groupsummary()
    {

        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            //print_r($post);exit;
            $id = @$post['glgroup_id'];
            $data['glgroup'] = glgroup_totalamount($id);
            //echo '<pre>';print_r($data);exit;
        } else {
            $data['glgroup'] = glgroup_totalamount();

        }
        $data['gl_group'] = $this->model->get_glgroup_data();
        $data['title'] = "Group Summary Data";

        return view('addbook/groupsummary', $data);
    }
    public function Outstanding($type = '')
    {
        $post = $this->request->getPost();

        $from = session('financial_form'); 
        $to = session('financial_to'); 

        if (!empty($post)) {
            $type = @$post['type'];
            $data['glgroup'] = Outstanding($type,$from,$to);
        }

        $data = Outstanding($type,$from,$to);

        if ($type == 'receivable') {
            $data['type'] = "receivable";
        } else {
            $data['type'] = "payable";
        }

        $data['title'] = "Outstanding Report";
        return view('addbook/outstanding_report', $data);
    }
    public function Ledger_outstanding()
    {
        $data = array();
        $post = $this->request->getPost();
        $get = $this->request->getGet();

        if(!empty($post['start_date']))
        {
            $from = @$post['start_date'];
            $to = @$post['end_date'];
            $account_id = @$post['account_id'];

            $data = Ledger_outstanding($account_id,$from,$to);
        }else if(!empty($get['from'])){
            $from = @$get['from'];
            $to = @$get['to'];
            $account_id = @$get['account_id'];

            $data = Ledger_outstanding($account_id,$from,$to);
        }else{
            $from = session('financial_form'); 
            $to = session('financial_to'); 
            $account_id = @$post['account_id'];

            $data = Ledger_outstanding($account_id,$from,$to);          
        }

        $data['account_id'] = $account_id;


        $data['from'] = $from;
        $data['to'] = $to;

        $data['account'] = $this->model->get_account_data();
        $data['title'] = "Outstanding Report";
        $data['type'] = "Ledger";

        return view('addbook/ledger_outstanding', $data);
    }
    public function ledgeroutstanding_xls_export(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 

        $post = $this->request->getGet();
       
        if(!empty($post)){
            $data = $this->model->ledgeroutstanding_xls_export_data($post);
        }else{       
            $post['from'] = session('financial_form'); 
            $post['to'] = session('financial_to'); 

            $data = $this->model->ledgeroutstanding_xls_export_data($post);   
        }
        

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function Ledger_outstanding_report()
    {
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $data['account'] = get_allLedger_OutStanding($post);
        }else{       
            $company_from = session('financial_form');
            $company_to = session('financial_to');   

            $post['from'] = $company_from; 
            $post['to'] = $company_to; 
            $data['account'] = get_allLedger_OutStanding($post);
        }
        
        $data['title'] = "Outstanding Report";
        $data['type'] = "Ledger";
        $data['from'] = $post['from'];
        $data['to'] = $post['to'];

        return view('addbook/ledger_outstanding_report', $data);
    }
    public function ledgeroutstanding_report_xls_export(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 

        $post = $this->request->getGet();
       
        if(!empty($post)){
            $data = $this->model->ledgeroutstanding_report_xls_export_data($post);
        }else{       
            $post['from'] = session('financial_form'); 
            $post['to'] = session('financial_to'); 

            $data = $this->model->ledgeroutstanding_report_xls_export_data($post);   
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function Gnrl_purchase_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Purchase_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Purchase_register_xls($post);
        }
        
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }
    public function Gnrl_purchase_gst_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_gnrl_purchase_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_gnrl_purchase_gst_register($post);
        }
        $data['date'] =$post;
        $data['title'] = "General Purchase GST Data";
   
        return view('addbook/gnrl_purchase_gst_register', $data);
    }
    public function Gnrl_purchase_gst_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Purchase_gst_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Purchase_gst_register_xls($post);
        }
        
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }
    public function Gnrl_purchase_rtn_register()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_purchase_rtn_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_purchase_rtn_register($post);
        }
        
        $data['purchase'] = Gnrlpurchase_rtn_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }
        $data['type'] = "General Purchase";
        $data['title'] = "General Purchase Data";
        
        return view('addbook/gnrl_purchase_rtn_register', $data);
    }
    public function Gnrl_purchase_rtn_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_purchase_rtn_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_purchase_rtn_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }   
    public function gnrl_purchase_rtn_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->gnrl_purchase_rtn_voucher_wise_data($get);        

        $data['title'] = "General Return Purchase Voucher Wise";
        return view('trading/gnrl_purchase_rtn_voucher',$data);
    }
    public function Gnrl_sales_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();
        //print_r($post);exit;
        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Sales_register_xls($post);
            
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Sales_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }
    public function Gnrl_sales_rtn_register()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Sales_rtn_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Sales_rtn_register($post);
        }
        
        $data['sales'] = GnrlsalesRtnItem_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }
        $data['type'] = "General Sales Return";
        $data['title'] = "General Sales Return Data";
        

        return view('addbook/gnrl_sales_rtn_register', $data);
    }
    public function Gnrl_sales_rtn_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_sales_rtn_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_sales_rtn_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }
    public function gnrl_sales_rtn_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->gnrl_sales_rtn_voucher_wise_data($get);        

        $data['title'] = "General Sales Return Voucher Wise";
        return view('trading/gnrl_sales_rtn_voucher',$data);
    }
    public function Gnrl_sales_gst_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_sales_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_sales_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "General Sales GST Data";
   
        return view('addbook/gnrl_sales_gst_register', $data);
    }
    public function Gnrl_sales_gst_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Gnrl_Sales_gst_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Gnrl_Sales_gst_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }
    public function Sales_register_xls()
    {
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Sales_register_xls($post);
           
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_register_xls($post);
        }
        
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
    }
    public function Sales_gst_register_xls()
    {
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Sales_gst_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_gst_register_xls($post);
        }
        
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
    }
    public function Sales_gst_register2_xls()
    {
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Sales_gst_register2_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_gst_register2_xls($post);
        }
        
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
    }
    public function Sales_return_register_xls()
    {
        $data = array();
        $post = $this->request->getGet();


        if (!empty($post)) {
            $data = $this->model->get_Sales_return_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Sales_return_register_xls($post);
        }
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
    public function Creditnote_gst_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Creditnote_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Creditnote_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "Creditnote GST Register Data";
   
        return view('addbook/credit_note_gst_register', $data);
    }
    public function Creditnote_gst_register_xls()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $data = array();
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_Creditnote_gst_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Creditnote_gst_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          
    }
    public function Debitnote_gst_register()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_Debitnote_gst_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Debitnote_gst_register($post);
        }
        
        $data['date'] =$post;
        $data['title'] = "Debitnote GST Register Data";
   
        return view('addbook/debit_note_gst_register', $data);
    }
    public function Purchase_register_xls()
    {
        $data = array();
        $post = $this->request->getGet();
    

        if (!empty($post)) {
            $data = $this->model->get_Purchase_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            
            $data = $this->model->get_Purchase_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
    public function Purchase_gst_register_xls()
    {
        $data = array();
        $post = $this->request->getGet();
    

        if (!empty($post)) {
            $data = $this->model->get_Purchase_gst_register_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            
            $data = $this->model->get_Purchase_gst_register_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    }
    public function Purchase_gst_register2_xls()
    {
        $data = array();
        $post = $this->request->getGet();
    

        if (!empty($post)) {
            $data = $this->model->get_Purchase_gst_register2_xls($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            
            $data = $this->model->get_Purchase_gst_register2_xls($post);
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    
    }
    public function Purchase_return_register()
    {
        $data = array();
        $post = $this->request->getPost();
    
        if (!empty($post)) {
            $data = $this->model->get_Purchase_return_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            
            $data = $this->model->get_Purchase_return_register($post);
        }

        $data['purchase'] = purchaseReturnItem_monthly_data(session('financial_form'),session('financial_to'));
        $data['date'] =$post;
        if(!empty($post['ac_id']))
        {
            $data['account_id'] = $post['ac_id'];
        }
        
        $data['title'] = "Debit Note Register";
        
        return view('addbook/debitnote', $data);

    }
    public function Purchase_return_register_xls()
    {
        $data = array();
        $post = $this->request->getGet();


        if (!empty($post)) {
            $data = $this->model->get_Purchase_return_register_xls($post);
           
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_Purchase_return_register_xls($post);
        }
        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
    public function purchaseReturnItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseReturnItem_voucher_wise_data($get);        

        $data['title'] = "Purchase Return Report Voucher Wise";
        return view('trading/purchaseReturnItem_voucher',$data);
    }
    public function payment()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_payment_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_payment_register($post);
        }

        $data['type'] = "Payment";
        $data['title'] = "Payment Data";
        $type = "payment";
        $data['date'] =$post;

        $data['payment'] = payment_monthly_data(session('financial_form'),session('financial_to'),@$post['mode']);
        return view('addbook/payment', $data);
    }
    public function Payment_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->payment_voucher_wise_data($get);        

        $data['title'] = "Payment Voucher";
        return view('addbook/payment_voucher_data',$data);
    }
    public function contra()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_contra_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_contra_register($post);
        }

        $data['type'] = "Contra";
        $data['title'] = "Contra Data";
        $type = "Contra";
        $data['date'] =$post;

        $data['contra'] = contra_monthly_data(session('financial_form'),session('financial_to'),@$post['mode']);
        return view('addbook/contra', $data);
    }
    public function Contra_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->contra_voucher_wise_data($get);        

        $data['title'] = "Contra Voucher";
        return view('addbook/contra_voucher_data',$data);
    }
    public function Receipt_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->receipt_voucher_wise_data($get);        

        $data['title'] = "Receipt Voucher";
        return view('addbook/receipt_voucher_data',$data);
    }
    public function receipt()
    {
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $data = $this->model->get_receipt_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data = $this->model->get_receipt_register($post);
        }


        $data['type'] = "receipt";
        $data['title'] = "Receipt Data";
        $type = "Receipt";
        $data['date'] =$post;
        $data['receipt'] = receipt_monthly_data(session('financial_form'),session('financial_to'),@$post['mode']);

        return view('addbook/receipt', $data);

    }
    public function cash()
    {
        $data = array();
        $post = $this->request->getPost();
        //print_r($post);exit;
        if (date('m') <= '03') {
            // echo "gre";exit;
            $apryear = date('Y') - 1;
            $mayyear = date('Y') - 1;
            $junyear = date('Y') - 1;
            $julyyear = date('Y') - 1;
            $ogstyear = date('Y') - 1;
            $sepyear = date('Y') - 1;
            $octyear = date('Y') - 1;
            $novyear = date('Y') - 1;
            $decyear = date('Y') - 1;
            $janyear = date('Y');
            $febyear = date('Y');
            $marchyear = date('Y');
            //$start_date = $year.'-04-01';
        } else {
            $apryear = date('Y');
            $mayyear = date('Y');
            $junyear = date('Y');
            $julyyear = date('Y');
            $ogstyear = date('Y');
            $sepyear = date('Y');
            $octyear = date('Y');
            $novyear = date('Y');
            $decyear = date('Y');
            $janyear = date('Y') + 1;
            $febyear = date('Y') + 1;
            $marchyear = date('Y') + 1;
            //$start_date = $year.'-04-01';
        }

        if (!empty($post)) {
            $data = $this->model->get_cash_register($post);
        } else {
            $data = $this->model->get_cash_register();
        }

        $data['type'] = "cash";
        $data['title'] = "Cash Data";
        $type = "cash";
        $data['jan'] = getmonth_total($type, $janyear . '-01-01', $janyear . '-01-31');
        $data['feb'] = getmonth_total($type, $febyear . '-02-01', $febyear . '-02-28');
        $data['march'] = getmonth_total($type, $marchyear . '-03-01', $marchyear . '-03-31');
        $data['apr'] = getmonth_total($type, $apryear . '-04-01', $apryear . '-04-30');
        $data['may'] = getmonth_total($type, $mayyear . '-05-01', $mayyear . '-05-31');
        $data['jun'] = getmonth_total($type, $junyear . '-06-01', $junyear . '-06-30');
        $data['july'] = getmonth_total($type, $julyyear . '-07-01', $julyyear . '-07-31');
        $data['ogst'] = getmonth_total($type, $ogstyear . '-08-01', $ogstyear . '-08-31');
        $data['sep'] = getmonth_total($type, $sepyear . '-09-01', $sepyear . '-09-30');
        $data['oct'] = getmonth_total($type, $octyear . '-10-01', $octyear . '-10-31');
        $data['nov'] = getmonth_total($type, $novyear . '-11-01', $novyear . '-11-30');
        $data['dec'] = getmonth_total($type, $decyear . '-12-01', $decyear . '-12-31');
        
        return view('addbook/cash', $data);

    }
    public function Bank()
    {
        $data = array();
        $post = $this->request->getPost();

        if (date('m') <= '03') {

            $apryear = date('Y') - 1;
            $mayyear = date('Y') - 1;
            $junyear = date('Y') - 1;
            $julyyear = date('Y') - 1;
            $ogstyear = date('Y') - 1;
            $sepyear = date('Y') - 1;
            $octyear = date('Y') - 1;
            $novyear = date('Y') - 1;
            $decyear = date('Y') - 1;
            $janyear = date('Y');
            $febyear = date('Y');
            $marchyear = date('Y');
            //$start_date = $year.'-04-01';
        } else {
            $apryear = date('Y');
            $mayyear = date('Y');
            $junyear = date('Y');
            $julyyear = date('Y');
            $ogstyear = date('Y');
            $sepyear = date('Y');
            $octyear = date('Y');
            $novyear = date('Y');
            $decyear = date('Y');
            $janyear = date('Y') + 1;
            $febyear = date('Y') + 1;
            $marchyear = date('Y') + 1;
            //$start_date = $year.'-04-01';
        }

        if (!empty($post)) {
            $data = $this->model->get_bank_register($post);
        } else {
            $data = $this->model->get_bank_register();
        }
        $data['type'] = "bank";
        $data['title'] = "Bank Data";
        $type = "bank";
        $data['jan'] = getmonth_total($type, $janyear . '-01-01', $janyear . '-01-31');
        $data['feb'] = getmonth_total($type, $febyear . '-02-01', $febyear . '-02-28');
        $data['march'] = getmonth_total($type, $marchyear . '-03-01', $marchyear . '-03-31');
        $data['apr'] = getmonth_total($type, $apryear . '-04-01', $apryear . '-04-30');
        $data['may'] = getmonth_total($type, $mayyear . '-05-01', $mayyear . '-05-31');
        $data['jun'] = getmonth_total($type, $junyear . '-06-01', $junyear . '-06-30');
        $data['july'] = getmonth_total($type, $julyyear . '-07-01', $julyyear . '-07-31');
        $data['ogst'] = getmonth_total($type, $ogstyear . '-08-01', $ogstyear . '-08-31');
        $data['sep'] = getmonth_total($type, $sepyear . '-09-01', $sepyear . '-09-30');
        $data['oct'] = getmonth_total($type, $octyear . '-10-01', $octyear . '-10-31');
        $data['nov'] = getmonth_total($type, $novyear . '-11-01', $novyear . '-11-30');
        $data['dec'] = getmonth_total($type, $decyear . '-12-01', $decyear . '-12-31');
        //$data['month']=$data;

        //echo '<pre>';print_r($data);exit;
        return view('addbook/bank', $data);

    }
    public function journal()
    {
        $data = array();
        $post = $this->request->getPost();
        //print_r($post);exit;
        if (date('m') <= '03') {
            // echo "gre";exit;
            $apryear = date('Y') - 1;
            $mayyear = date('Y') - 1;
            $junyear = date('Y') - 1;
            $julyyear = date('Y') - 1;
            $ogstyear = date('Y') - 1;
            $sepyear = date('Y') - 1;
            $octyear = date('Y') - 1;
            $novyear = date('Y') - 1;
            $decyear = date('Y') - 1;
            $janyear = date('Y');
            $febyear = date('Y');
            $marchyear = date('Y');
            //$start_date = $year.'-04-01';
        } else {
            $apryear = date('Y');
            $mayyear = date('Y');
            $junyear = date('Y');
            $julyyear = date('Y');
            $ogstyear = date('Y');
            $sepyear = date('Y');
            $octyear = date('Y');
            $novyear = date('Y');
            $decyear = date('Y');
            $janyear = date('Y') + 1;
            $febyear = date('Y') + 1;
            $marchyear = date('Y') + 1;
            //$start_date = $year.'-04-01';
        }

        if (!empty($post)) {
            $data = $this->model->get_journal_register($post);
        } else {
            $data = $this->model->get_journal_register();
        }

        $data['type'] = "journal";
        $data['title'] = "Journal Register Data";
        $type = "journal";
        $data['jan'] = getmonth_total($type, $janyear . '-01-01', $janyear . '-01-31');
        $data['feb'] = getmonth_total($type, $febyear . '-02-01', $febyear . '-02-28');
        $data['march'] = getmonth_total($type, $marchyear . '-03-01', $marchyear . '-03-31');
        $data['apr'] = getmonth_total($type, $apryear . '-04-01', $apryear . '-04-30');
        $data['may'] = getmonth_total($type, $mayyear . '-05-01', $mayyear . '-05-31');
        $data['jun'] = getmonth_total($type, $junyear . '-06-01', $junyear . '-06-30');
        $data['july'] = getmonth_total($type, $julyyear . '-07-01', $julyyear . '-07-31');
        $data['ogst'] = getmonth_total($type, $ogstyear . '-08-01', $ogstyear . '-08-31');
        $data['sep'] = getmonth_total($type, $sepyear . '-09-01', $sepyear . '-09-30');
        $data['oct'] = getmonth_total($type, $octyear . '-10-01', $octyear . '-10-31');
        $data['nov'] = getmonth_total($type, $novyear . '-11-01', $novyear . '-11-30');
        $data['dec'] = getmonth_total($type, $decyear . '-12-01', $decyear . '-12-31');
        
        return view('addbook/journal', $data);
    }
    public function ledger()
    {
        $data = array();
        $post = $this->request->getPost();
     
        if (!empty($post)) {
            $data['invoice_data'] = $this->model->get_ledger_register($post);
            $data['old_data'] = $this->model->get_old_ledger_register($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data['invoice_data'] = $this->model->get_ledger_register($post);
           // $data['old_data'] = $this->model->get_old_ledger_register($post);
        }
        $data['start_date'] =  @$data['invoice_data']['start_date'];
        $data['end_date'] =  @$data['invoice_data']['end_date'];
        $data['account_id'] =  @$data['invoice_data']['account_id'];  
        $data['type'] = "Ledger";
        $data['title'] = "Ledger Register Data";
        //echo '<pre>';print_r($data);exit;

        return view('addbook/ledger', $data);

    }
    public function ledger_xls_export(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 

        $post = $this->request->getGet();

        if(!empty($post)){
            $data = $this->model->ledger_xls_export_data($post);
        }else{       
            $post['from'] = session('financial_form'); 
            $post['to'] = session('financial_to'); 

            $data = $this->model->ledger_xls_export_data($post);   
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function ledger_outstanding_list_xls_export()
    {
       
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->ledger_outstanding_list_xls_export_data($post);
        }else{       
            $data = $this->model->ledger_outstanding_list_xls_export_data($post);   
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function get_ledger_register()
    {
        $data = array();
        $post = $this->request->getGet();
     
        if (!empty($post['from']) && !empty($post['to'])) {
            $data['invoice_data'] = $this->model->get_ledger_register($post);
            $data['old_data'] = $this->model->get_old_ledger_register($post);
        } else {
          
            $data['invoice_data'] = $this->model->get_ledger_register($post);
            $data['old_data'] = $this->model->get_old_ledger_register($post);
        }
            
        $data['type'] = "Ledger";
        $data['title'] = "Ledger Register Data";
       // echo '<pre>';print_r($data);exit;

        return view('addbook/ledger_invoices', $data);

    }
    public function Ledger_outstanding_list()
    {
      
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $data = $this->model->get_ledger_outstanding_list_new($post);
        }else{  
            $data = $this->model->get_ledger_outstanding_list_new($post);
        }
        $data['party'] = @$post['party'];
        $data['account_id'] = @$post['account_id'];
        $data['front_date'] = !empty(@$post['date'])?$post['date']:date('Y-m-d');
        if(!empty($post['account_id']))
        {
            $acc = $this->gmodel->get_data_table('account', array('id' => @$post['account_id']), 'id,name');
            $data['account_name'] = $acc['name'];
        }
        
        $data['type'] = "Ledger";
        $data['title']="Ledger Outstating List";
        return view('addbook/ledger_list', $data);
    }
    // closing balance calculation 
    public function update_gl_group_summary_table()
    {
        $data = $this->model->update_gl_group_summary_table();
    }
    public function gl_group_summary_query()
    {
       
        $data = $this->model->get_gl_group_summary_query_data();
        echo '<pre>';Print_r($data);exit;
        

    }
    public function closing_bal_report()
    {
     
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post['from']) && !empty($post['to'])) {
            $data['gl_summary'] = $this->model->get_closing_bal_report_data($post);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data['gl_summary'] = $this->model->get_closing_bal_report_data($post);
        }
        $data['start_date'] = @$post['from']; 
        $data['end_date'] = @$post['to']; 
        
       // echo '<pre>';Print_r($data);exit;
        
        $data['title'] = "GL Group Summary";
        return view('addbook/gl_summary_report', $data);

    }
    public function closing_bal_account_report()
    {
     
        // $data = array();
        $post = $this->request->getGet();
        $gmodel = new GeneralModel;
         $data['gl_account_summary'] = $this->model->get_closing_bal_account_report_data($post);
       
         $data['start_date'] = @$post['from']; 
         $data['end_date'] = @$post['to']; 
         $data['account_id'] = @$post['account_id']; 
         $data['type'] = @$post['type']; 
         $ac_name = $gmodel->get_data_table('account', array('id' => @$post['account_id']), 'name');
         $data['account_name'] = @$ac_name['name'];
        $data['title'] = "GL Account Summary";
        return view('addbook/gl_account_summary_report', $data);

    }
    public function Getdata($method = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }
        $cid = session('cid');
        if ($method == 'account') {
            $get = $this->request->getGet();
            $get['cid'] = $cid;
            $this->model->get_account_data($get);
        }
        if ($method == 'banktrans') {
            $get = $this->request->getGet();
            $this->model->get_banktrans_data($get);
        }

        if ($method == 'search_bill') {
            $post = $this->request->getPost();
            $result = $this->model->get_billno_databyid($post);
            return $this->response->setJSON($result);
        }
           
        if($method == 'search_account') {
            $post = $this->request->getPost();
            //print_r($post);exit;
            if($post['party_name'] == 'Sundry Debtors')
            {
                $data= $this->mmodel->search_sun_debtor(@$post);
            }
            else
            {
                $data= $this->mmodel->search_sun_credit(@$post);
            }
            return $this->response->setJSON($data);
        }

    }
    public function Action($method = '')
    {
        $result = array();
        if ($method == 'Update') {
            $post = $this->request->getPost();
            $result = $this->model->UpdateData($post);
        }
        return $this->response->setJSON($result);
    }
}