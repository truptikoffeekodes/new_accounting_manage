<?php 

namespace App\Controllers;
use App\Models\BankModel;
use App\Models\GeneralModel;
use DateTime;
class Bank extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new BankModel();
        $this->gmodel = new GeneralModel();
        
    }
    
    public function update_recons()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $post = $this->request->getPost();
        
        $bank = $this->gmodel->get_data_table('bank_tras',array('id' =>$post['pk']),'*');

        if($bank['payment_type'] == 'contra'){
            
            if($bank['cash_type'] == 'Fund Transfer'){
                $msg = $this->gmodel->update_data_table('contra_trans',array('id' =>$post['name']),array('recons_date' => $post['value']));
            }else{

                $msg = $this->gmodel->update_data_table('contra_trans',array('parent_id' =>$post['pk']),array('recons_date' => $post['value']));
            }
        }else{
            $msg = $this->gmodel->update_data_table('bank_tras',array('id' =>$post['pk']),array('recons_date' => $post['value']));
        }
        
        $msg['month'] = $post['value'];
        return $this->response->setJson($msg);
    }   
    
    public function unlink_reconsilation($from,$to,$ac){
        if(!session('uid')){
            return redirect()->to(url('auth'));
        }
        
        $res = $this->model->unlink_bank_reconsilation($from,$to,$ac);

        $data['title'] = 'Bank Reconciliation';
        $session = session();
        $session->setFlashdata('start_date', $from);
        $session->setFlashdata('end_date', $to);
        $session->setFlashdata('account_id', $ac);
        
        return redirect()->to(url('bank/reconciliation'));
    }


    public function Statement($from,$to,$ac){
        if(!session('uid')){
            return redirect()->to(url('auth'));
        }
        
        $reconsilation = $this->gmodel->get_bank_reconsilation($ac,$to);

        $account_id = @$ac;
        $start_date= @$from;
        $end_date = @$to;
            
        $data = get_reconsilation_data($account_id,$start_date,$end_date);
        $opening = $this->gmodel->get_data_table('account',array('id'=>$account_id),'opening_bal,opening_type');

        if(!empty($opening)){
            if($opening['opening_type'] == 'Credit'){
                $reconsilation -= @$opening['opening_bal'] ? $opening['opening_bal'] : 0;
            }
            if($opening['opening_type'] == 'Debit'){
                $reconsilation =  (@$reconsilation ? $reconsilation : 0 ) + (@$opening['opening_bal'] ? $opening['opening_bal'] : 0) ;
            }
        }
        
        $data['title'] = 'Bank Reconciliation';
        $data['opening_bal'] = $reconsilation;
        
        return view('bank/bank_statement',$data);
    }

    public function single_unlink(){
        if(!session('uid')){
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        
        if(!empty($post)){
            $msg = $this->model->unlink_single_reconsilation($post);
            return $this->response->setJSON($msg);
        }
    }

    public function reconciliation(){

        if(!session('uid')){
            return redirect()->to(url('auth'));
        }

        $data = array();
        $post = $this->request->getPost();
        
        $reconsilation = 0;

        if(!empty($post))
        {
            $account_id = @$post['account'];
            $start_date= @$post['from'];
            $end_date = @$post['to'];
            $reconsilation = $this->gmodel->get_bank_reconsilation($account_id,@$post['to']);
            
            $data = get_reconsilation_data($account_id,$start_date,$end_date);
            
            $opening = $this->gmodel->get_data_table('account',array('id'=>$account_id),'opening_bal,opening_type');
            
        }else{

            $session = session();
            if(!empty($session->getFlashdata())){

                $reconsilation = $this->gmodel->get_bank_reconsilation($session->getFlashdata('account_id'),$session->getFlashdata('end_date'));
                $data = get_reconsilation_data($session->getFlashdata('account_id'),$session->getFlashdata('start_date'),$session->getFlashdata('end_date'));
                $opening = $this->gmodel->get_data_table('account',array('id'=>$session->getFlashdata('account_id')),'opening_bal,opening_type');
                
            }else{
                $data['bank'] = array();
            }
        }

        if(!empty($opening)){
            if($opening['opening_type'] == 'Credit'){
                $reconsilation -= @$opening['opening_bal'] ? $opening['opening_bal'] : 0;
            }
            if($opening['opening_type'] == 'Debit'){
             
                $reconsilation =  $reconsilation + (@$opening['opening_bal'] ? $opening['opening_bal'] : 0)  ;
                             
            }
        }
        
        $data['title'] = 'Bank Reconciliation';
        $data['opening_bal'] = $reconsilation;
        
        return view('bank/bank_reconciliation',$data);
    }

    public function unreconsilation(){
        
        if(!session('uid')){
            return redirect()->to(url('auth'));
        }
        
        $data = array();
        $post = $this->request->getPost();
        
        $reconsilation = 0;
        $prev_opening = 0;

        $month = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'Auguest', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
        
        if(!empty($post))
        {
            $account_id = @$post['account'];   
            $start_date= @$post['from'];
            $end_date = @$post['to'];
            
            $reconsilation = $this->gmodel->get_bank_reconsilation($account_id,$end_date);

            $date = new DateTime($end_date);
            $date->modify("last day of previous month");
            $prev_month =  $date->format("Y-m-d");

            $prev_opening = $this->gmodel->get_bank_reconsilation($account_id,$prev_month);
            $bank = $this->gmodel->get_data_table("account",array('id'=>$account_id),'opening_bal,opening_type');
            $opening_bal = @$bank['opening_bal'] != '' ? $bank['opening_bal'] : 0;  
            $opening_type = @$bank['opening_type'];
            if($opening_type == 'Debit'){
                $prev_opening = $prev_opening +$opening_bal; 
            }else{
                $prev_opening = $prev_opening -$opening_bal; 

            }
            
            
            $data = get_unreconsilation_data($account_id,$start_date,$end_date);
            $opening = $this->gmodel->get_data_table('account',array('id'=>$account_id),'opening_bal,opening_type');

        }else{
            $session = session();
            $reconsilation = $this->gmodel->get_bank_reconsilation($session->getFlashdata('account_id'),$session->getFlashdata('end_date'));

            $date = new DateTime($session->getFlashdata('end_date'));
            $date->modify("last day of previous month");
            $prev_month =  $date->format("Y-m-d");
            
            $prev_opening = $this->gmodel->get_bank_reconsilation($session->getFlashdata('account_id'),$prev_month);
            $bank = $this->gmodel->get_data_table("account",array('id'=>$session->getFlashdata('account_id')),'opening_bal,opening_type');
            $opening_bal = @$bank['opening_bal'] != '' ? $bank['opening_bal'] : 0;  
            $opening_type = @$bank['opening_type'];
            if($opening_type == 'debit'){
                $prev_opening = $prev_opening +$opening_bal; 
            }else{
                $prev_opening = $prev_opening -$opening_bal; 

            }

            if(!empty($session->getFlashdata())){
                $data = get_reconsilation_data($session->getFlashdata('account_id'),$session->getFlashdata('start_date'),$session->getFlashdata('end_date'));
                $opening = $this->gmodel->get_data_table('account',array('id'=>$session->getFlashdata('account_id')),'opening_bal,opening_type');
                
            }else{
                $data['bank'] = array();
            }
        }

        if(!empty($opening)){
            if($opening['opening_type'] == 'Credit'){
                $reconsilation -= @$opening['opening_bal'] ? $opening['opening_bal'] : 0;
            }
            if($opening['opening_type'] == 'Debit'){
             
                $reconsilation =  (float)$reconsilation + (float)@$opening['opening_bal'] ;
                             
            }
        }
        
        $data['title'] = 'Unlink Bank Reconciliation';
        $data['opening_bal'] = number_format($reconsilation,2);
        $data['prev_opening'] = $prev_opening;
        $data['month'] = $month;

        return view('bank/unlink_reconciliation',$data);
    }
    
    public function bank_transaction()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title']="Bank Book";
        return view('bank/bank_transaction',$data);
    }
    
    public function contra_transaction(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data['title']="Contra Transaction";
        return view('bank/contra_transaction',$data);
    }
    
    public function jv_particular()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data['title']="JV Particular";
        return view('bank/jv_particular',$data);
    }
    
    public function cash_transaction()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data['title']="cash Transaction";
        return view('bank/cash_transaction',$data);
    }
    
    public function add_banktrans($id = ''){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if (!empty($post)) {
            $msg = $this->model->insert_edit_banktrans($post);
            return $this->response->setJSON($msg);
        }

        if ($id != '') {
                $data['banktrans']= $this->model->get_banktrans_data_byid($id);
        }
        $data['id'] = $id;

        $data['title']="Bank Transaction";
        return view('bank/create_bankTrans',$data);
    }

    public function add_jvparticular($id = ''){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $data = array();
        $post = $this->request->getPost();

        if(!empty($post)) {
            $msg = $this->model->insert_edit_jvparticular($post);
            return $this->response->setJSON($msg);
        }
        
        if($id != '') {
            $data['jvparticular']= $this->model->get_jvparticular_data_byid($id);
        }

        $getId = $this->gmodel->get_lastId('jv_main');

        $data['id'] = $id;
        $data['current_id'] = $getId + 1;

        $data['title']="JV Paricular";
        return view('bank/create_jvparticular',$data);
    }

    public function add_cashtrans($id = ''){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
     
        if ($id != '') {
            $data['cashtrans']= $this->model->get_banktrans_data_byid($id);
            
        }
        $cash = $this->gmodel->get_data_table('account',array('name'=>'cash'),'id,name');
        $data['cash_account']['id'] = @$cash['id'];
        $data['cash_account']['name'] = @$cash['name'];

        $data['id'] = $id;
        $data['title']="cash Transaction";
        // echo '<pre>';print_r($data);exit;
        return view('bank/create_cashTrans',$data);
    }

    public function add_contratrans($id = ''){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $data = array();
        $post = $this->request->getPost();
        
        if (!empty($post)) {
            $msg = $this->model->insert_edit_banktrans($post);
            return $this->response->setJSON($msg);
        }
     
       if ($id != '') {
            $data['contratrans']= $this->model->get_contratrans_data_byid($id);
       }
    //    echo '<pre>';print_r($data);exit;
        $data['id'] = $id;
        $data['title']="Contra Transaction";
        return view('bank/create_contraTrans',$data);
    }

    public function add_checkrange($id = ''){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data = array();
        $post = $this->request->getPost();
        $get = $this->request->getGet();

        if (!empty($post)) {    
            $msg = $this->model->insert_edit_checkrange($post);
            return $this->response->setJSON($msg);
        }
        
        if ($id != '') {
            $data['checkrange']= $this->model->get_checkrange_data($id);
        }
        
        $bank = $this->gmodel->get_data_table('account',array('id'=>@$get['bank_id']),'name');
        
        $data['checkrange']['bank_id']=@$get['bank_id'];
        $data['checkrange']['bank_name']=@$bank['name'];
        
        $data['id'] = $id;
        $data['title']="Check range";

        return view('bank/create_checkrange',$data);
        
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
        
        if ($method == 'banktrans') {
            $get = $this->request->getGet();
            $post = $this->request->getPost();
            $this->model->get_banktrans_data($get,$post);
        }
        
        if ($method == 'cashtrans') {
            $get = $this->request->getGet();
            $this->model->get_cashtrans_data($get);
        }
        
        if ($method == 'contratrans') {
            $get = $this->request->getGet();
            $this->model->get_contratrans_data($get);
        }
        
        if ($method == 'jvparticular') {
            $get = $this->request->getGet();
            $this->model->get_jvparticular_data($get);
        }
        
        if ($method == 'search_invoice') {
            $post = $this->request->getPost();
            $result = $this->model->get_invoice_databyid($post);
            return $this->response->setJSON($result);
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
}

?>