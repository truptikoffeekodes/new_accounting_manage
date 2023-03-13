<?php 
namespace App\Controllers;
use App\Models\MillingReportModel;
use App\Models\MasterModel;
use App\Models\GeneralModel;


class MillingReport extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new MillingReportModel();
        $this->gmodel = new GeneralModel();
        
    }

    public function gray_issue_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['gray'] = $this->model->get_gray_issue_report($post);
        
        $data['title'] = "Gray Issue Report";
        return view('MillingReport/grayIssue_report', $data); 
    }

    public function finish_issue_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['gray'] = $this->model->get_finish_issue_report($post);
        
        $data['title'] = "Finish Issue Report";
        return view('MillingReport/finishIssue_report', $data); 
    }

    public function gray_return_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['gray'] = $this->model->get_gray_return_report($post);
        // echo '<pre>';print_r($data);exit;

        
        $data['title'] = "Gray Return Report";
        return view('MillingReport/grayReturn_report', $data); 
    }

    public function finish_return_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['gray'] = $this->model->get_finish_return_report($post);
        // echo '<pre>';print_r($data);exit;

        
        $data['title'] = "Finish Return Report";
        return view('MillingReport/finishReturn_report', $data); 
    }

    public function mill_issue_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['mill'] = $this->model->get_mill_issue_report($post);
        // echo '<pre>';print_r($data);exit;

        
        $data['title'] = "Mill Issue Report";
        return view('MillingReport/millIssue_report', $data); 
    }

    public function mill_received_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['mill'] = $this->model->get_mill_received_report($post);
        // echo '<pre>';print_r($data);exit;

        
        $data['title'] = "Mill Received Report";
        return view('MillingReport/millReceived_report', $data); 
    }

    public function mill_return_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['mill'] = $this->model->get_mill_return_report($post);
        
        $data['title'] = "Mill Return Report";
        return view('MillingReport/millReturn_report', $data); 
    }

    public function job_issue_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['job'] = $this->model->get_job_issue_report($post);
        
        $data['title'] = "Job Issue Report";
        return view('MillingReport/jobIssue_report', $data); 
    }

    public function job_return_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['job'] = $this->model->get_job_return_report($post);
        
        $data['title'] = "Job Return Report";
        return view('MillingReport/jobReturn_report', $data); 
    }

    public function job_received_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['job'] = $this->model->get_job_received_report($post);
        
        $data['title'] = "Job Received Report";
        return view('MillingReport/jobReceived_report', $data); 
    }

    public function mill_rec_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Mill Received Report";
        return view('MillingReport/millRec_report', $data); 
    }

    public function Gray_item_wise()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Gray Item Wise Report";
        return view('MillingReport/Gray_ItemWise', $data);
    }

    public function sendMill_item_wise()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Mill Issue Item Wise Report";
        return view('MillingReport/sendMill_item_wise', $data);
    }

    public function recMill_item_wise()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Mill Received Item Wise Report";
        return view('MillingReport/recMill_item_wise', $data);
    }

    public function sendJob_item_wise()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Send JOB Item Wise Report";
        return view('MillingReport/sendJob_item_wise', $data);
    }

    public function Gray_Invoice_wise()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Gray Invoice Report";
        return view('MillingReport/Gray_InvoiceWise', $data);
    }

    public function Mill_report()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Mill Issue Voucher Wise Report";
        return view('MillingReport/mill_report', $data);
    }

    public function RecMill_report()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Mill Received Voucher Wise Report";
        return view('MillingReport/RecMill_report', $data);
    }

    public function sendJob_report()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Jobwork Issue Voucher Wise Report";
        return view('MillingReport/sendJob_report', $data);
    }

    public function RecJob_report()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        
        $data['title'] = "Jobwork Received Voucher Wise Report";
        return view('MillingReport/RecJob_report', $data);
    }

  

    //*********** ITEM  WISE REPORT ***********//
    
    public function gray_issue_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['gray'] = $this->model->get_gray_issue_ItemWise_report($post);
        
        $data['title'] = "Gray Issue Item Wise Report";
        return view('MillingReport/grayIssue_ItemWise_report', $data); 
    }

    public function finish_issue_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['gray'] = $this->model->get_finish_issue_ItemWise_report($post);
        
        $data['title'] = "Finish Issue Item Wise Report";
        return view('MillingReport/finishIssue_ItemWise_report', $data); 
    }
    
    public function gray_return_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['gray'] = $this->model->get_gray_return_ItemWise_report($post);
        
        $data['title'] = "Gray Return Item Wise Report";
        return view('MillingReport/grayReturn_ItemWise_report', $data); 
    }

    public function finish_return_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        $data['gray'] = $this->model->get_finish_return_ItemWise_report($post);
        
        $data['title'] = "Finish Return Item Wise Report";
        return view('MillingReport/finishReturn_ItemWise_report', $data); 
    }
    
    public function mill_issue_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['mill'] = $this->model->get_mill_issue_ItemWise_report($post);
        
        $data['title'] = "Mill Issue Item Wise Report";
        return view('MillingReport/MillIssue_ItemWise_report', $data); 
    }

    public function mill_return_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['mill'] = $this->model->get_mill_return_ItemWise_report($post);
        
        $data['title'] = "Mill Return Item Wise Report";
        return view('MillingReport/MillReturn_ItemWise_report', $data); 
    }
    
    public function mill_received_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['mill'] = $this->model->get_mill_received_ItemWise_report($post);
        
        $data['title'] = "Mill Received Item Wise Report";
        return view('MillingReport/MillReceived_ItemWise_report', $data); 
    }

    public function job_issue_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['job'] = $this->model->get_job_issue_ItemWise_report($post);
        
        $data['title'] = "Job Issue Item Wise Report";
        return view('MillingReport/jobIssue_ItemWise_report', $data); 
    }

    public function job_return_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['job'] = $this->model->get_job_return_ItemWise_report($post);
        
        $data['title'] = "Job Return Item Wise Report";
        return view('MillingReport/jobReturn_ItemWise_report', $data); 
    }

    public function job_received_ItemWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['job'] = $this->model->get_job_received_ItemWise_report($post);
        
        $data['title'] = "Job Received Item Wise Report";
        return view('MillingReport/jobReceived_ItemWise_report', $data); 
    }


    //*********** BROKER WISE REPORT ***********//

    public function gray_issue_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['gray'] = $this->model->get_gray_issue_BrokerWise_report($post);
        
        $data['title'] = "Gray Issue Broker Wise Report";
        return view('MillingReport/grayIssue_BrokerWise_report', $data); 
    }

    public function finish_issue_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        // echo '<pre>';print_r($post);exit;
        $data['finish'] = $this->model->get_finish_issue_BrokerWise_report($post);
        
        $data['title'] = "Finish Issue Broker Wise Report";
        return view('MillingReport/finishIssue_BrokerWise_report', $data); 
    }

    public function gray_return_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();

        $data['gray'] = $this->model->get_gray_return_BrokerWise_report($post);
        
        $data['title'] = "Gray Return Broker Wise Report";
        return view('MillingReport/grayReturn_BrokerWise_report', $data); 
    }

    public function finish_return_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();

        $data['gray'] = $this->model->get_finish_return_BrokerWise_report($post);
        
        $data['title'] = "Finish Return Broker Wise Report";
        return view('MillingReport/finishReturn_BrokerWise_report', $data); 
    }

    public function mill_issue_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['mill'] = $this->model->get_mill_issue_BrokerWise_report($post);
        
        $data['title'] = "Mill Issue Broker Wise Report";
        return view('MillingReport/millIssue_BrokerWise_report', $data); 
    }


    public function mill_return_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['mill'] = $this->model->get_mill_return_BrokerWise_report($post);
        
        $data['title'] = "Mill Return Broker Wise Report";
        return view('MillingReport/millReturn_BrokerWise_report', $data); 
    }

    public function mill_received_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['mill'] = $this->model->get_mill_received_BrokerWise_report($post);
        
        $data['title'] = "Mill Received Broker Wise Report";
        return view('MillingReport/millReceived_BrokerWise_report', $data); 
    }
    
    public function job_issue_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['job'] = $this->model->get_job_issue_BrokerWise_report($post);
        
        $data['title'] = "Job Issue Broker Wise Report";
        return view('MillingReport/jobIssue_BrokerWise_report', $data); 
    }

    public function job_return_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['job'] = $this->model->get_job_return_BrokerWise_report($post);
        
        $data['title'] = "Job Return Broker Wise Report";
        return view('MillingReport/jobReturn_BrokerWise_report', $data); 
    }

    public function job_received_BrokerWise_report(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $data['job'] = $this->model->get_job_received_BrokerWise_report($post);
        
        $data['title'] = "Job Received Broker Wise Report";
        return view('MillingReport/jobReceived_BrokerWise_report', $data); 
    }

    public function Getdata($method = '') {
        
        if (!session('cid')) {
            return redirect()->to(url('Company'));
        }
        
        $cid = session('cid');

        if ($method == 'mill_rec_report') {
            $get = $this->request->getGet();
            $this->model->get_MillRec_report_data($get);
        }
        
        if ($method == 'Gray_ItemWise') {
            $get = $this->request->getGet();  
            return $this->model->get_Gray_ItemWise_data($get);
        }
        
        if ($method == 'sendMill_ItemWise') {
            $get = $this->request->getGet();  
            return $this->model->get_sendMill_ItemWise_data($get);
        }

        if ($method == 'sendJob_ItemWise') {
            $get = $this->request->getGet();  
            return $this->model->get_sendJob_ItemWise_data($get);
        }

        if ($method == 'recMill_ItemWise') {
            $get = $this->request->getGet();  
            return $this->model->get_recMill_ItemWise_data($get);
        }
        
        if ($method == 'Gray_InvoiceWise') {
            $get = $this->request->getGet();  
            return $this->model->get_Gray_InvoiceWise_data($get);
        }

        if ($method == 'mill_report') {
            $get = $this->request->getGet();  
            return $this->model->get_mill_report_data($get);
        }
        if ($method == 'sendJob_report') {
            $get = $this->request->getGet();  
            return $this->model->get_sendJob_report_data($get);
        } 
        if ($method == 'RecMill_report') {
            $get = $this->request->getGet();  
            return $this->model->get_RecMill_report_data($get);
        } 
        if ($method == 'JobMill_report') {
            $get = $this->request->getGet();  
            return $this->model->get_JobMill_report_data($get);
        } 
    }

}

?>