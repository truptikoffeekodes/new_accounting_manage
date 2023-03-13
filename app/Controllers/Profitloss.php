<?php 
namespace App\Controllers;
use App\Models\GeneralModel;
use App\Models\ProfitlossModel;
use App\Models\TradingModel;

class Profitloss extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new ProfitlossModel();
        $this->gmodel = new GeneralModel();
        $this->tmodel = new TradingModel();
        helper('pl');    
    }
    public function pl_dashboard(){

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $company_from = session('financial_form');
        $company_to = session('financial_to');   
        $post= $this->request->getPost();

        $gl_id = $this->gmodel->get_data_table('gl_group',array('name'=>'Trading Expenses','is_delete'=>0),'id,name');
        $gl_inc_id = $this->gmodel->get_data_table('gl_group',array('name'=>'Trading Income','is_delete'=>0),'id,name');

        $pl_exp_id = $this->gmodel->get_data_table('gl_group',array('name'=>'P & L Expenses','is_delete'=>0),'id,name');
        
        $pl_inc_id = $this->gmodel->get_data_table('gl_group',array('name'=>'P & L Incomes','is_delete'=>0),'id,name');
        $gl_opening_id = $this->gmodel->get_data_table('gl_group',array('name'=>'Opening Stock'),'id,name');

       
        $init_total =0;

        if(!empty($post)){
            $from =date_create($post['from']) ;                                         
            $to = date_create($post['to']);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            //***** Start Trading Expense & Income  *****//
            
            $sale_pur = sale_purchase_vouhcer($post['from'],$post['to']); 
            
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$post['from'],$post['to']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$post['from'],$post['to']);
            
            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$post['from'],$post['to']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$post['from'],$post['to']);
            
            //***** End Trading Expense & Income  *****//

            //***** Start PL Expense & Income  *****//

            $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id'],$post['from'],$post['to']);
            $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
            $exp_pl[$pl_exp_id['id']]['sub_categories']  = get_PL_expense_sub_grp_data($pl_exp_id['id'],$post['from'],$post['to']);

            
            $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id'],$post['from'],$post['to']);
            $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
            $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id'],$post['from'],$post['to']);
            
            //***** End PL Expense & Income  *****//

            $pl  = pl_tot_data($post['from'],$post['to']);
            // $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            // $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock($post['from'],$post['to']);
            $closing_data = $this->tmodel->get_closing_detail($post['from'],$post['to']);

          
        
        }else if($company_from != 0000-00-00 && $company_to != 0000-00-00){
            $from =date_create($company_from) ;                                         
            $to = date_create($company_to);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            //***** Start Trading Expense & Income  *****//
            
            $sale_pur = sale_purchase_vouhcer($post['from'],$post['to']); 
            
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$post['from'],$post['to']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$post['from'],$post['to']);
            
            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$post['from'],$post['to']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$post['from'],$post['to']);
            
            //***** End Trading Expense & Income  *****//

            //***** Start PL Expense & Income  *****//

            $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id'],$post['from'],$post['to']);

            $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
            $exp_pl[$pl_exp_id['id']]['sub_categories']  = get_PL_expense_sub_grp_data($pl_exp_id['id'],$post['from'],$post['to']);

            $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id'],$post['from'],$post['to']);
            $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
            $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id'],$post['from'],$post['to']);
            
            
            //***** End PL Expense & Income  *****//

            $pl  = pl_tot_data($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock($post['from'],$post['to']);
            $closing_data = $this->tmodel->get_closing_detail($post['from'],$post['to']);

          
        }
        else{

            //***** Start Trading Expense & Income Data *****//

            $sale_pur = sale_purchase_vouhcer();    
            
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id']);

            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id']);
            
            //***** End Trading Expense & Income Data *****//
            

            //***** Start P & L Expense & Income Data *****//

            $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id']);
            $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
            $exp_pl[$pl_exp_id['id']]['sub_categories'] = get_PL_expense_sub_grp_data($pl_exp_id['id']);
            
            
            $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id']);
            $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
            $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id']);
            //***** End P & L Expense & Income  *****//

            $pl  = pl_tot_data();
            
            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock();
            $closing_data = $this->tmodel->get_closing_detail();

           
        }
        $opening_stock[$gl_opening_id['id']] = opening_stock_data($gl_opening_id['id']);
        $opening_stock[$gl_opening_id['id']]['name'] = $gl_opening_id['name'];
        $opening_stock[$gl_opening_id['id']]['sub_categories'] = get_opening_stock_sub_grp_data($gl_opening_id['id']);
            
        
        $data['trading'] = $sale_pur;
        $data['pl'] = $pl ;

        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);

        $exp_pl_total = subGrp_total($exp_pl,$init_total);
        $inc_pl_total = subGrp_total($inc_pl,$init_total);

        $opening_total = subGrp_total($opening_stock,$init_total);

        $data['pl']['exp'] = @$exp_pl;
        $data['pl']['inc'] = @$inc_pl;

        $data['trading']['exp_total'] = @$exp_total;
        $data['trading']['inc_total'] = @$inc_total;
        
        $data['pl']['exp_total'] = @$exp_pl_total;
        $data['pl']['inc_total'] = @$inc_pl_total;

      
        
        $data['trading']['opening_bal'] = $Opening_bal;
        $data['trading']['closing_bal'] = @$closing_data['closing_bal']; 
        $data['trading']['closing_stock'] = @$closing_data['closing_stock'];
        $data['trading']['manualy_closing_bal'] = @$manualy_closing_bal;
        $data['trading']['opening_bal_total'] = @$opening_total;
        //echo '<pre>';Print_r($data);exit;
        

        //update trupti 03-12-2022
        $data['start_date'] = $post['from']?$post['from']:$company_from;
        $data['end_date'] = $post['to']?$post['to']:$company_to;
        $data['title'] =  "P & L Dashboard";
        
        return view('trading/pl/pl_dashboard',$data);
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


    public function Profit_loss_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->profit_loss_xls_export_data($post);
        }else{       
            $post['from'] = session('financial_form'); 
            $post['to'] = session('financial_to'); 
            $data = $this->model->profit_loss_xls_export_data($post);   
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
    public function pl_generalSales_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = pl_get_generalSales_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Sales Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/expence/general_sales_monthlyAcc',$data);
    }
    public function generalSales_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        $data = $this->model->generalSales_voucher_wise_data($get);        
        $data['title'] = "General Sales Voucher Wise";
        $data['type']=@$get['type'];
        $data['id']=@$get['id'];
        return view('trading/expence/salesgeneralItem_voucher',$data);
    }
    public function pl_generalPurchase_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = pl_get_generalPurchase_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Sales Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/expence/general_purchase_monthlyAcc',$data);
    }
    public function generalPurchase_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        $data = $this->model->generalPurchase_voucher_wise_data($get);        
        $data['title'] = "General Sales Voucher Wise";
        $data['type']=@$get['type'];
        $data['id']=@$get['id'];
        return view('trading/income/purchasegeneralItem_voucher',$data);
    }
    public function pl_sales_invoice_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_pl_sales_invoice_monthly_data($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Sales Invoice Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        $data['from'] =@$get['from'];
        $data['to'] =@$get['to'];
      
        return view('trading/pl/sales_invoice_monthlyAcc',$data);
    }
    public function sales_invoices_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        $data = $this->model->salesinvoice_voucher_wise_data($get);        
        $data['title'] = "Sales Invoice Voucher Wise";
        $data['type']=@$get['type'];
        $data['id']=@$get['id'];
        return view('trading/pl/salesinvoiceItem_voucher',$data);
    }
    public function pl_sales_return_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_pl_sales_return_monthly_data($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Sales Return Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        $data['from'] =@$get['from'];
        $data['to'] =@$get['to'];
      
        return view('trading/pl/sales_return_monthlyAcc',$data);
    }
    public function sales_return_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        $data = $this->model->salesreturn_voucher_wise_data($get);        
        $data['title'] = "Sales Return Voucher Wise";
        $data['type']=@$get['type'];
        $data['id']=@$get['id'];
        return view('trading/pl/salesreturnItem_voucher',$data);
    }
    public function pl_purchase_invoice_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_pl_purchase_invoice_monthly_data($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Purchase Invoice Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        $data['from'] =@$get['from'];
        $data['to'] =@$get['to'];
      
        return view('trading/pl/purchase_invoice_monthlyAcc',$data);
    }
    public function purchase_invoice_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        $data = $this->model->purchaseinvoice_voucher_wise_data($get);        
        $data['title'] = "Purchase Invoice Voucher Wise";
        $data['type']=@$get['type'];
        $data['id']=@$get['id'];
        return view('trading/pl/purchaseinvoiceItem_voucher',$data);
    }
    public function pl_purchase_return_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_pl_purchase_return_monthly_data($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Purchase Return Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];
        $data['from'] =@$get['from'];
        $data['to'] =@$get['to'];
      
        return view('trading/pl/purchase_return_monthlyAcc',$data);
    }
    public function purchase_return_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        $data = $this->model->purchasereturn_voucher_wise_data($get);        
        $data['title'] = "Purchase Return Voucher Wise";
        $data['type']=@$get['type'];
        $data['id']=@$get['id'];
        return view('trading/pl/purchasereturnItem_voucher',$data);
    }
    public function get_round_off_data()
    {
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data= get_round_off_voucher_data($get['from'],$get['to'],$get['id']);
    
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Round Off Data";
        $data['ac_name'] =$acc['name'];
        return view('trading/expence/roundoff_voucher',$data);
    }
    
    


}
?>