<?php namespace App\Controllers;

use App\Models\GeneralModel;
use App\Models\PurchaseModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Purchase extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new PurchaseModel();
        $this->gmodel = new GeneralModel();

    }
    public function debit()
    {
        $data['title'] = "Debit";
        return view('transaction/debit', $data);
    }

    public function add_debit($id = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('company'));
        }

        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $msg = $this->model->insert_edit_debit($post);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
            $data = $this->model->get_master_data('debit', $id);
        }

        $data['id'] = $id;
        $data['title'] = "Add Debit";

        return view('purchase/create_debit_note', $data);
    }

    public function Getdata($method = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }
        $uid = session('uid');
        if ($method == 'debit') {
            $get = $this->request->getGet();
            $get['uid'] = $uid;
            $this->model->get_debit_data($get);
        }
        if ($method == 'purchasechallan') {
            $get = $this->request->getGet();
            $this->model->get_purchasechallan_data($get);
        }
        if ($method == 'purchaseinvoice') {
            $get = $this->request->getGet();
            $this->model->get_purchaseinvoice_data($get);
        }
        if ($method == 'purchasereturn') {
            $get = $this->request->getGet();
            $this->model->get_purchasereturn_data($get);
        }
        if ($method == 'general_purchase') {
            $get = $this->request->getGet();
            $this->model->get_general_purchase_data($get);
        }
        if ($method == 'search_purchase_invoice') {
            $post = $this->request->getPost();
            $result = $this->model->get_purchaseinvoice_databyid($post);
            return $this->response->setJSON($result);
        }
        if ($method == 'search_purchase_general') {
            $post = $this->request->getPost();
            $result = $this->model->get_purchasegeneral_databyid($post);
            return $this->response->setJSON($result);
        }

        if ($method == 'get_purchase_challan') {
            $post = $this->request->getPost();
            $data = $this->model->search_purchase_challan_data($post);
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

    public function purchasechallan()
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }
        $data['title'] = "purchase Challan";
        return view('purchase/purchasechallan', $data);
    }

    public function add_purchasechallan($id = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }

        $data = array();
        $post = $this->request->getPost();

        if (!empty($post)) {
            $msg = $this->model->insert_edit_purchasechallan($post);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
            $data = $this->model->get_purchase_challan($id);
        }
        // echo '<pre>';print_r($data);exit;
        $tax_id = $this->gmodel->get_data_table('gl_group', array('name' => 'Duties and taxes'), 'id');
        $tax = $this->gmodel->get_array_table('account', array('gl_group' => $tax_id['id']), 'name');
        $getId = $this->gmodel->get_purchase_id('purchase_challan');

        $data['tax'] = $tax;
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;
        $data['title'] = "Add PurchaseChallan";
        return view('purchase/create_purchasechallan', $data);
    }

    public function purchaseinvoice()
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }

        $data['title'] = "purchase Invoice";
        return view('purchase/purchaseinvoice', $data);
    }

    public function add_purchaseinvoice($id = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_purchaseinvoice($post);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
            $data = $this->model->get_purchase_invoice($id);
        }
        // echo '<pre>';print_r($data);exit;
        $tax_id = $this->gmodel->get_data_table('gl_group', array('name' => 'Duties and taxes'), 'id');
        $tax = $this->gmodel->get_array_table('account', array('gl_group' => $tax_id['id']), 'name');
        $getId = $this->gmodel->get_saleInv_id('purchase_invoice');

        $data['tax'] = $tax;
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;

        $data['title'] = "Add PurchaseInvoice";
        return view('purchase/create_purchaseinvoice', $data);
    }

    public function purchasereturn()
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }

        $data['title'] = "purchase Return";
        return view('purchase/purchasereturn', $data);
    }

    public function add_purchasereturn($id = '')
    {

        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {
            $msg = $this->model->insert_edit_purchasereturn($post);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
            $data = $this->model->get_purchase_return($id);
        }
        $tax_id = $this->gmodel->get_data_table('gl_group', array('name' => 'Duties and taxes'), 'id');
        $tax = $this->gmodel->get_array_table('account', array('gl_group' => $tax_id['id']), 'name');
        $getId = $this->gmodel->get_lastId('purchase_return');

        $data['tax'] = $tax;
        $data['id'] = $id;
        $data['current_id'] = $getId + 1;

        $data['title'] = "Purchase Return";
        return view('purchase/create_purchasereturn', $data);
    }

    public function general_purchase()
    {
        if (!session('uid')) {
            return redirect()->to(url('Auth'));
        }

        $data['title'] = "General Purchase";
        return view('purchase/general_purchase', $data);
    }

    public function add_general_pur($type, $id = '')
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        if (!empty($post)) {

            $msg = $this->model->insert_edit_general_pur($post);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
            $data = $this->model->get_gnlPur_byid($id);
        }
        // echo '<pre>';print_r($data);exit;

        $tax_id = $this->gmodel->get_data_table('gl_group', array('name' => 'Duties and taxes'), 'id');
        $tax = $this->gmodel->get_array_table('account', array('gl_group' => $tax_id['id']), 'name');
        $data['tax'] = $tax;

        // $getId = $this->gmodel->get_lastId('purchase_general');
        $getId = $this->gmodel->get_general_id($type, 'purchase_general');

        $data['current_id'] = $getId + 1;

        $data['id'] = $id;
        $data['title'] = "Add General";
        $data['type'] = $type;

        return view('purchase/create_gnrl_purchase', $data);

    }

    public function purchase_challan_detail($id)
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if ($id != '') {
            $data = $this->model->get_purchase_challan($id);
        }
        // echo '<pre>';print_r($data);exit;
        $data['title'] = "Challan Detail";
        return view('purchase/purchase_challan_detail', $data);
    }

    public function purchase_return_detail($id)
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if ($id != '') {
            $data = $this->model->get_purchase_return($id);
        }
        // echo '<pre>';print_r($data);exit;
        $data['title'] = "Return Detail";
        return view('purchase/purchase_return_detail', $data);
    }

    public function purchase_invoice_detail($id)
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if ($id != '') {
            $data = $this->model->get_purchase_invoice($id);
        }
        //echo '<pre>';print_r($data);exit;
        $data['title'] = "Invoice Detail";
        return view('purchase/purchase_invoice_detail', $data);
    }

    public function purchase_general_detail($id)
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if ($id != '') {
            $data = $this->model->get_gnlPur_byid($id);
        }

        $data['title'] = "General Detail";
        return view('purchase/purchase_general_detail', $data);
    }

    public function pdf_challan($id)
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if ($id != '') {

            $data = $this->model->get_purchase_challan($id);

            $data['account'] = $this->gmodel->get_data_table('account', array('id' => $data['purchasechallan']['account']), '*');
            $data['transport'] = $this->gmodel->get_data_table('transport', array('id' => $data['purchasechallan']['transport']), '*');

            $data['delivery'] = $this->gmodel->get_data_table('account', array('id' => @$data['purchasechallan']['delivery_code']), '*');

            $data['billing_state'] = $this->gmodel->get_data_table('states', array('id' => @$data['account']['state']), '*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries', array('id' => @$data['account']['country']), '*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities', array('id' => @$data['account']['city']), '*');

            $data['ship_state'] = $this->gmodel->get_data_table('states', array('name' => @$data['purchasechallan']['ship_state']), '*');

            $data['bank_detail'] = $this->gmodel->get_data_table('bank', array('id' => @$data['account']['bank']), '*');
            $data['billterm'] = $this->gmodel->get_bill_term();

        }

        ini_set('memory_limit', '-1');
        $html = view('pdf/purchase_challan', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('fontHeightRatio', 1);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A3', 'portrait');
        $dompdf->render();

        $dompdf->stream('challan.pdf', array("Attachment" => 0));
        return $this->response->setHeader('Content-Disposition', 'inline; filename="invoice.pdf"')
            ->setContentType('application/pdf');
    }

    public function pdf_invoice($id){
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if($id != ''){
            $data = $this->model->get_purchase_invoice($id);  
            //print_r($data);exit;
            $data['challan_detail'] = $this->gmodel->get_data_table('purchase_challan',array('id'=>$data['purchaseinvoice']['challan_no']),'*');
            $data['account'] = $this->gmodel->get_data_table('account',array('id'=>$data['purchaseinvoice']['account']),'*');
            $data['transport'] = $this->gmodel->get_data_table('transport',array('id'=>$data['purchaseinvoice']['transport']),'*');

            $data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['purchaseinvoice']['delivery_code']),'*');
    
            $data['billing_state'] = $this->gmodel->get_data_table('states',array('id'=>@$data['account']['state']),'*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['country']),'*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['city']),'*');

            $data['ship_state'] = $this->gmodel->get_data_table('states',array('name'=>@$data['purchaseinvoice']['ship_state']),'*');
            // $data['ship_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['ship_country']),'*');
            // $data['ship_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['ship_city']),'*');
            
            $data['bank_detail'] = $this->gmodel->get_data_table('bank',array('id'=>@$data['account']['bank']),'*');
            $data['billterm'] = $this->gmodel->get_bill_term();
       
        }

       // echo '<pre>';print_r($data);exit;
        //ini_set('memory_limit', '-1');
        $html =  view('pdf/purchase_invoice',$data);
        //return view('pdf/invoice_detail', $data);
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('fontHeightRatio', 1);
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

    public function pdf_return($id)
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if ($id != '') {
            $data = $this->model->get_purchase_return($id);
            $data['account'] = $this->gmodel->get_data_table('account', array('id' => $data['p_return']['account']), '*');
            //$data['challan_detail'] = $this->gmodel->get_data_table('purchase_challan',array('id'=>@$data['purchaseinvoice']['challan_no']),'*');
            $data['bank_detail'] = $this->gmodel->get_data_table('bank', array('id' => @$data['account']['bank']), '*');
            $data['billterm'] = $this->gmodel->get_bill_term();

        }

        //echo '<pre>';print_r($data);exit;
        //ini_set('memory_limit', '-1');
        $html = view('pdf/pur_return_detail', $data);
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
        return $this->response->setHeader('Content-Disposition', 'inline; filename="invoice.pdf"')
            ->setContentType('application/pdf');
        // }else{
        // $dompdf->stream('challan.pdf', array("Attachment" => 1));
        // }
    }

    public function pdf_general($id)
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        if ($id != '') {
            $data = $this->model->get_gnlPur_byid($id);
            //print_r($data);exit;
            $data['account'] = $this->gmodel->get_data_table('account', array('id' => $data['general']['party_account']), '*');
            //$data['invoice_detail'] = $this->gmodel->get_data_table('purchase_invoice',array('id'=>$data['general']['invoice']),'*');
            //$data['challan_detail'] = $this->gmodel->get_data_table('purchase_challan',array('id'=>$data['invoice_detail']['challan_no']),'*');

            $data['account'] = $this->gmodel->get_data_table('account', array('id' => $data['general']['party_account']), '*');
            //$data['transport'] = $this->gmodel->get_data_table('transport',array('id'=>$data['general']['transport']),'*');

            //$data['delivery'] = $this->gmodel->get_data_table('account',array('id'=>@$data['general']['delivery_code']),'*');

            $data['billing_state'] = $this->gmodel->get_data_table('states', array('id' => @$data['account']['state']), '*');
            $data['billing_country'] = $this->gmodel->get_data_table('countries', array('id' => @$data['account']['country']), '*');
            $data['billing_city'] = $this->gmodel->get_data_table('cities', array('id' => @$data['account']['city']), '*');

            $data['ship_state'] = $this->gmodel->get_data_table('states', array('name' => @$data['general']['ship_state']), '*');
            // $data['ship_country'] = $this->gmodel->get_data_table('countries',array('id'=>@$data['account']['ship_country']),'*');
            // $data['ship_city'] = $this->gmodel->get_data_table('cities',array('id'=>@$data['account']['ship_city']),'*');

            $data['bank_detail'] = $this->gmodel->get_data_table('bank', array('id' => @$data['account']['bank']), '*');
            $data['billterm'] = $this->gmodel->get_bill_term();

        }

        //  echo '<pre>';print_r($data);exit;
        //ini_set('memory_limit', '-1');
        $html = view('pdf/general_purchase', $data);
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
        return $this->response->setHeader('Content-Disposition', 'inline; filename="invoice.pdf"')
            ->setContentType('application/pdf');
        // }else{
        // $dompdf->stream('challan.pdf', array("Attachment" => 1));
        // }
    }

    public function debit_note()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $data['title'] = "Debit Note";
        return view('purchase/debit_note', $data);
    }

}
