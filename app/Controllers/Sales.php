<?php 
namespace App\Controllers;
use App\Models\GeneralModel;
use App\Models\SalesModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Sales extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new SalesModel();
        $this->gmodel = new GeneralModel();
        
    }

    public function test(){

        getenv('database.default.database');
    }

    public function add_challan($id = '')
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $msg=$this->model->insert_edit_challan($post);
            return $this->response->setJSON($msg);
        }

        if($id != '') {
            $data = $this->model->get_sales_challan($id);
        }

        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');

        $getId = $this->gmodel->get_voucher_id('sales_challan');
        
        $data['tax'] = $tax; 
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Sales Challan";
        
        return view('Sales/challan',$data);
    }

    public function challan_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->model->get_sales_challan($id);   
        }
        //echo '<pre>';Print_r($data);exit;
        
        $data['title']="Challan Detail";
        
        return view('Sales/challan_detail', $data);
    }

    // public function pdf_challan($id){
    //     if (!session('uid')) {
    //         return redirect()->to(url('auth'));
    //     }

    //     if($id != ''){
    //         $data = $this->gmodel->get_sales_challan($id);   
    //     }
        
    //     ini_set('memory_limit', '-1');
    //     return $html =  view('pdf/challan_detail',$data);

    //     $options = new Options();
    //     $options->set('isRemoteEnabled', true);
    //     $options->set('fontHeightRatio', 1);
    //     $dompdf = new Dompdf($options);
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A3', 'portrait');
    //     $dompdf->render();  

    //     //if($post['type'] == 'print'){
    //         $dompdf->stream('challan.pdf', array("Attachment" => 0));
    //         return $this->response->setHeader('Content-Disposition','inline; filename="invoice.pdf"')
    //                             ->setContentType('application/pdf');
    //     // }else{
    //     //    $dompdf->stream();
    //     // }
    // }

    public function pdf_challan($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){

            $data = $this->model->get_sales_challan($id);   
            // $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['challan']['account']),'*');
            // $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['challan']['delivery_code']),'*');
            // $data['company'] = $this->gmodel->get_data_table('account',array('id'=>@$data['challan']['delivery_code']),'*');
            
            // $data['delivery_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['delivery']['state']),'*');
            // $data['challan_detail'] = $this->gmodel->get_data_table('sales_challan',array('id'=>@$data['challan']['challan_no']),'*');
            // $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            // $data['billterm'] = $this->gmodel->get_bill_term();

            $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['challan']['account']),'*');
            $data['transport'] = $this->gmodel->get_data_table('transport',array('id'=>$data['challan']['transport']),'*');

            $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['challan']['delivery_code']),'*');
    
            $data['billing_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['account']['state']),'*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['country']),'*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['city']),'*');

            $data['ship_state'] = $this->gmodel->get_data_table('states',array('name'=>@$data['challan']['ship_state']),'*');
            // $data['ship_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['ship_country']),'*');
            // $data['ship_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['ship_city']),'*');
            
            $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            $data['billterm'] = $this->gmodel->get_bill_term();
        
        }
        
        $html =  view('pdf/sales_challan',$data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('fontHeightRatio', 1.1);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait');
        $dompdf->render();  

        //if($post['type'] == 'print'){
            $dompdf->stream('challan.pdf', array("Attachment" => 0));
            return $this->response->setHeader('Content-Disposition','inline; filename="invoice.pdf"')
                                ->setContentType('application/pdf');
        // }else{
            // $dompdf->stream('challan.pdf', array("Attachment" => 1));
        // }

        
    }

    public function pdf_invoice($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            
            $data = $this->model->get_sales_invoice($id);   

            $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['salesinvoice']['account']),'*');
            $data['transport'] = $this->gmodel->get_data_table('transport',array('id'=>$data['salesinvoice']['transport']),'*');

            $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['salesinvoice']['delivery_code']),'*');
    
            $data['billing_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['account']['state']),'*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['country']),'*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['city']),'*');

            $data['ship_state'] = $this->gmodel->get_data_table('states',array('name'=>@$data['salesinvoice']['ship_state']),'*');
            // $data['ship_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['ship_country']),'*');
            // $data['ship_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['ship_city']),'*');
            
            $data['company'] = $this->gmodel->get_data_table('account',array('id'=>@$data['salesinvoice']['delivery_code']),'*');
            $data['challan_detail'] = $this->gmodel->get_data_table('sales_challan',array('id'=>@$data['salesinvoice']['challan_no']),'*');
            $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            $data['billterm'] = $this->gmodel->get_bill_term();
        }

        $html =  view('pdf/sales_invoice',$data);
        // return view('pdf/sales_invoice', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('fontHeightRatio', 1.3);
        $options->set('isPhpEnabled',TRUE);
        // $options->set('debugCss', TRUE);
        $options->set('isHtml5ParserEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait');
        $dompdf->render();  

        //if($post['type'] == 'print'){
            $dompdf->stream('invoice.pdf', array("Attachment" => 0));
            return $this->response->setHeader('Content-Disposition','inline; filename="invoice.pdf"')
                                ->setContentType('application/pdf');
        // }else{
            // $dompdf->stream('challan.pdf', array("Attachment" => 1));
        // }
    }

    public function pdf_return($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
       
        if($id != ''){
            $data = $this->gmodel->get_sales_return($id);   
            // $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['s_return']['account']),'*');
            // $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['s_return']['delivery_code']),'*');
            // $data['delivery_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['delivery']['state']),'*');
            // $data['company'] = $this->gmodel->get_data_table('account',array('id'=>@$data['s_return']['delivery_code']),'*');
            // $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            // $data['billterm'] = $this->gmodel->get_bill_term();
            $data['sales_invoice'] = $this->gmodel->get_data_table('sales_invoice',array('id'=>@$data['s_return']['invoice']),'*');
            $data['challan_detail'] = $this->gmodel->get_data_table('sales_challan',array('id'=>@$data['sales_invoice']['challan_no']),'*');
            
            $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['s_return']['account']),'*');
            $data['transport'] = $this->gmodel->get_data_table('transport',array('id'=>$data['s_return']['transport']),'*');

            $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['s_return']['delivery_code']),'*');
    
            $data['billing_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['account']['state']),'*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['country']),'*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['city']),'*');

            $data['ship_state'] = $this->gmodel->get_data_table('states',array('name'=>@$data['s_return']['ship_state']),'*');
           
            $data['company'] = $this->gmodel->get_data_table('account',array('id'=>@$data['s_return']['delivery_code']),'*');
            //$data['challan_detail'] = $this->gmodel->get_data_table('sales_challan',array('id'=>@$data['s_return']['challan_no']),'*');
            $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            $data['billterm'] = $this->gmodel->get_bill_term();
       
        }
    
        $html =  view('pdf/sales_return',$data);
        
        //return view('pdf/invoice_detail', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('fontHeightRatio', 1);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait');
        $dompdf->render();  

        //if($post['type'] == 'print'){
            $dompdf->stream('return.pdf', array("Attachment" => 0));
            return $this->response->setHeader('Content-Disposition','inline; filename="invoice.pdf"')
                                ->setContentType('application/pdf');
        // }else{
            // $dompdf->stream('challan.pdf', array("Attachment" => 1));
        // }
    }




    public function return_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->gmodel->get_sales_return($id);   
        }
        // echo '<pre>';print_r($data);exit; 
        $data['title']="Return Detail";
        return view('Sales/sales_return_detail', $data);
    }
    
    public function invoice_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->model->get_sales_invoice($id);   
        }
        //echo '<pre>';print_r($data);exit; 
        $data['title']="Invoice Detail";
        return view('Sales/sales_invoice_detail', $data);
    }

    public function general_detail($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->model->get_ACinvoice_byid($id);   
        }
        // echo '<pre>';print_r($data);exit; 
        $data['title']="General Detail";
        return view('Sales/sales_general_detail', $data);
    }

    public function add_salesinvoice($id=''){

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $msg = $this->model->insert_edit_salesinvoice($post);
            return $this->response->setJSON($msg);
        }
        
        if ($id != '') {
             $data = $this->model->get_sales_invoice($id);
        }
        $data['voucher_list'] = get_voucher_list('1');

        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id'],),'name');
        
        $data['tax'] = $tax; 

        $getId = $this->gmodel->get_saleInv_id('sales_invoice');
        $data['current_id'] = $getId + 1;
      
        if(session('DataSource')=='ACE20227T93')
        {
            $c_data['type'] = 'invoice';
            $c_data['date'] = date('Y-m-d');
            $cutom_inv_no = $this->gmodel->get_max_customInvno($c_data);
        }
        elseif(session('DataSource')=='KLA2022ZFDH')
        {
            $cutom_inv_no = $this->gmodel->ecom_get_max_customInvno();
        }
        else
        {

        }

        $data['custom_inv_no'] = @$cutom_inv_no;
        $data['id'] = $id;
        $data['title'] = "Add SalesInvoice";

        return view('Sales/create_salesinvoice', $data);
    }    


    public function add_salesreturn($id = ''){
        if (!session('cid')) {
             return redirect()->to(url('company'));
        }
        ini_set('max_input_vars',3000);

        $data = array();
        $post = $this->request->getPost();
        
        if(!empty($post)) {
            $msg = $this->model->insert_edit_salesreturn($post);
            return $this->response->setJSON($msg);
        }
        if($id != '') {
            $data = $this->gmodel->get_sales_return($id);
        }
        //echo '<pre>';Print_r($data);exit;
        
        $data['voucher_list'] = get_voucher_list('3');

        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' => $tax_id['id']),'name');
        
        $data['tax'] = $tax;
        $getId = $this->gmodel->get_return_id('sales_return');
        $data['current_id'] = $getId + 1;
       
        if(session('DataSource')=='ACE20227T93')
        { 
            $c_data['type'] = 'return';
            $c_data['date'] = date('Y-m-d');
            $supp_inv_no = $this->gmodel->get_max_customInvno($c_data);
        }
        elseif(session('DataSource')=='KLA2022ZFDH')
        {
            $supp_inv_no = $this->gmodel->ecom_ret_get_max_customInvno();
        }
        else
        {

        }
        
        //print_r($plateform_data);exit;
        $data['supp_inv_no'] = @$supp_inv_no;
        $data['id'] = $id;
        $data['title']="Sales Return";
        
        return view('Sales/create_salesreturn', $data);
    }

    public function add_ACinvoice($type,$id = '')
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_ACinvoice($post);
            return $this->response->setJSON($msg);
        }

        if($id != '') {
            $data= $this->model->get_ACinvoice_byid($id);
        }

        $tax_id = $this->gmodel->get_data_table('gl_group',array('name' => 'Duties and taxes'),'id');
        $tax = $this->gmodel->get_array_table('account',array('gl_group' =>$tax_id['id']),'name');
          
        $data['tax'] = $tax; 
        $getId = $this->gmodel->get_general_id($type,'sales_ACinvoice');
        $data['current_id'] = $getId + 1;
        
        $data['id'] = $id;
        $data['type'] = $type;
        
        $data['title']="General Sales";
        return view('Sales/create_ac_invoice', $data);
    }

    public function pdf_general($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
       
        if($id != ''){
            $data = $this->model->get_ACinvoice_byid($id);   
            
            $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['invoice']['party_account']),'*');

            $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['invoice']['delivery_code']),'*');
    
            $data['billing_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['account']['state']),'*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['country']),'*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['city']),'*');

            $data['ship_state'] = $this->gmodel->get_data_table('states',array('name'=>@$data['invoice']['ship_state']),'*');
           
            $data['company'] = $this->gmodel->get_data_table('account',array('id'=>@$data['invoice']['delivery_code']),'*');
            // $data['challan_detail'] = $this->gmodel->get_data_table('sales_challan',array('id'=>@$data['s_return']['challan_no']),'*');
            $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            $data['billterm'] = $this->gmodel->get_bill_term();
        
        }

        // echo '<pre>';print_r($data);exit;
        // ini_set('memory_limit', '-1');
        $html =  view('pdf/general_sales',$data);
        //return view('pdf/invoice_detail', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('fontHeightRatio', 1);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait');
        $dompdf->render();  

        //if($post['type'] == 'print'){
            $dompdf->stream('general.pdf', array("Attachment" => 0));
            return $this->response->setHeader('Content-Disposition','inline; filename="invoice.pdf"')
                                ->setContentType('application/pdf');
        // }else{
            // $dompdf->stream('challan.pdf', array("Attachment" => 1));
        // }
    }

    public function ac_invoice(){
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }
        
        $data['title']="General Sales";
        return view('Sales/ac_invoice',$data);
    }

    public function challan()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Sales Challan";
        
        return view('Sales/challan_view',$data);
    }
    public function salesinvoice()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $data['title']="Sales Invoice";
        return view('Sales/salesinvoice', $data);
    }
   
    public function salesreturn()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $data['title']="Sales Return";
        return view('Sales/salesreturn',$data);
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

    public function Getdata($method = '') {
        
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }

        $cid = session('cid');
        
        if ($method == 'challan') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_challan_detail($get);
        }   
       
        if ($method == 'get_challan') {
            $post = $this->request->getPost();
            $data = $this->model->search_challan_data($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'salesinvoice') {
            $get = $this->request->getGet();
            $get['cid']=$cid;
            $this->model->get_salesinvoice_data($get);
        }

        if ($method == 'salesreturn') {
            $get = $this->request->getGet();
            $this->model->get_salesreturn_data($get);
        }

        if($method == 'ac_invoice') {
            $get = $this->request->getGet();
            $this->model->get_ac_invoice($get);
        }
        
        if ($method == 'Item') {
            $post= $this->request->getPost();
            $data = $this->model->search_item_data(@$post['searchTerm']);
            return $this->response->setJSON($data);
        }

        if ($method == 'bank_cashAdvance') {
            $post= $this->request->getPost();
            $data = $this->model->get_BankCashAdvance($post);
            return $this->response->setJSON($data);
        }

        if ($method == 'search_sales_invoice') {
            $post = $this->request->getPost();
            // print_r($post);exit;
            $result = $this->model->get_Saleinvoice_databyid($post);
            return $this->response->setJSON($result);
        }
        if ($method == 'search_sale_general') {
            $post = $this->request->getPost();
            // print_r($post);exit;
            $result = $this->model->get_Salegeneral_databyid($post);
            return $this->response->setJSON($result);
        }
        if ($method == 'get_max_customInvno') {
            $post = $this->request->getPost();
            $result = $this->gmodel->get_max_customInvno($post);
            return $this->response->setJSON(array("st" => "success", "invoice" => $result));
        }
        if ($method == 'ecom_get_max_customInvno') {
            $post = $this->request->getPost();
            $result = $this->gmodel->new_ecom_get_max_customInvno($post);
            return $this->response->setJSON(array("st" => "success", "invoice" => $result));
        }
        if ($method == 'ecom_ret_get_max_customInvno') {
            $post = $this->request->getPost();
            $result = $this->gmodel->new_ret_ecom_get_max_customInvno($post);
            return $this->response->setJSON(array("st" => "success", "invoice" => $result));
        }
        
    }
    public function item_taxability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
       }
      
           $msg = $this->model->update_item_taxability();
           return $this->response->setJSON($msg);
      

    }
    public function sales_challan_taxability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
           $msg = $this->model->update_sales_challan_taxability();
           return $this->response->setJSON($msg);
    }
    public function sales_invoice_taxability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
           $msg = $this->model->update_sales_invoice_taxability();
           return $this->response->setJSON($msg);
    }
    public function sales_return_taxability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
           $msg = $this->model->update_sales_return_taxability();
           return $this->response->setJSON($msg);
    }
    public function account_taxability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
       }
      
           $msg = $this->model->update_account_taxability();
           return $this->response->setJSON($msg);
      

    }
    public function sales_acinvoice_gst()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
           $msg = $this->model->update_acinvoice_gst();
           return $this->response->setJSON($msg);
    }
    public function sales_acinvoice_taxability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
           $msg = $this->model->update_acinvoice_taxability();
           return $this->response->setJSON($msg);
    }
}
?>