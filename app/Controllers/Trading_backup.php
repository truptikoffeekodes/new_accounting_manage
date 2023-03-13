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

            $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock');

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

            $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock');

        }else{
            $sale_pur = sale_purchase_vouhcer();

            $exp[$gl_id['id']] = trading_expense_data($gl_id['id']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id']);

            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id']);
            
            $closing_stock = $this->model->get_closing_stock();   
            $closing_bal = $this->model->get_closing_bal();
            $Opening_bal = Opening_bal('Opening Stock');
        }
        // exit;
        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);
        
        $data['trading'] = $sale_pur;
        
        
        $data['trading']['opening_bal'] = $Opening_bal;
        
        $data['trading']['closing_bal'] = @$closing_stock; 
        $data['trading']['closing'] = @$closing_bal; 
        
        $data['trading']['exp'] = @$exp;
        $data['trading']['inc'] = @$inc;

        $data['trading']['exp_total'] = @$exp_total;
        $data['trading']['inc_total'] = @$inc_total;
        //update trupti 03-12-2022
        $data['start_date'] = $post['from']?$post['from']:$company_from;
        $data['end_date'] = $post['to']?$post['to']:$company_to;

        $data['title'] =  "Trading Dashboard";
        
        return view('trading/dashboard',$data);
    }
    
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
        
        $init_total =0;

        if(!empty($post)){
            $from =date_create($post['from']) ;                                         
            $to = date_create($post['to']);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            //***** Start Trading Expense & Income  *****//
            
            $sale_pur = sale_purchase_itm_total($post['from'],$post['to']); 
            
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
            $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock',$post['from'],$post['to']);
        
        }else if($company_from != 0000-00-00 && $company_to != 0000-00-00){
            $from =date_create($company_from) ;                                         
            $to = date_create($company_to);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            //***** Start Trading Expense & Income  *****//
            
            $sale_pur = sale_purchase_itm_total($post['from'],$post['to']); 
            
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
            $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock',$post['from'],$post['to']);
        }
        else{

            //***** Start Trading Expense & Income Data *****//

            $sale_pur = sale_purchase_itm_total();    
            
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
            
            $closing_stock = $this->model->get_closing_stock(); 
            $closing_bal = $this->model->get_closing_bal();
            $Opening_bal = Opening_bal('Opening Stock');
        }
        
        $data['trading'] = $sale_pur;
        $data['pl'] = $pl ;

        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);

        $exp_pl_total = subGrp_total($exp_pl,$init_total);
        $inc_pl_total = subGrp_total($inc_pl,$init_total);

        $data['pl']['exp'] = @$exp_pl;
        $data['pl']['inc'] = @$inc_pl;

        $data['trading']['exp_total'] = @$exp_total;
        $data['trading']['inc_total'] = @$inc_total;
        
        $data['pl']['exp_total'] = @$exp_pl_total;
        $data['pl']['inc_total'] = @$inc_pl_total;
        
        $data['trading']['opening_bal'] = $Opening_bal;
        $data['trading']['closing_bal'] = @$closing_stock; 
        $data['trading']['closing'] = @$closing_bal;

        //update trupti 03-12-2022
        $data['start_date'] = $post['from']?$post['from']:$company_from;
        $data['end_date'] = $post['to']?$post['to']:$company_to;
        $data['title'] =  "P & L Dashboard";
        
        return view('trading/pl_dashboard',$data);
    }

    public function balacesheet(){

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();
        
        $gmodel = new GeneralModel;

        $gl_capital = $gmodel->get_data_table('gl_group',array('name'=>'Capital'),'id,name');
        $gl_loan = $gmodel->get_data_table('gl_group',array('name'=>'Loans'),'id,name');
        $gl_lib = $gmodel->get_data_table('gl_group',array('name'=>'Current Liabilities'),'id,name');
        $gl_fixedassets = $gmodel->get_data_table('gl_group',array('name'=>'Fixed Assets'),'id,name');
        $gl_currentassets = $gmodel->get_data_table('gl_group',array('name'=>'Current Assets'),'id,name');
        $gl_otherassets = $gmodel->get_data_table('gl_group',array('name'=>'Other Assets'),'id,name');

        $company_from = session('financial_form');
        $company_to = session('financial_to');   

        if(!empty($post)){

            $from =date_create($post['from']) ;                                         
            $to = date_create($post['to']);     
            
            $post['from'] = date_format($from,"Y-m-d");
            $post['to'] = date_format($to,"Y-m-d");

            $balancesheet  = balancesheet_detail($post['from'],$post['to']);
            $pl  = pl_tot_data($post['from'],$post['to']);
            
            $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock',$post['from'],$post['to']);

            $sale_purchase = sale_purchase_itm_total($post['from'],$post['to']); 

            $capital[$gl_capital['id']] = capital_data($gl_capital['id'],$post['from'],$post['to']);
            $capital[$gl_capital['id']]['name'] = $gl_capital['name'];
            $capital[$gl_capital['id']]['sub_categories'] = get_capital_sub_grp_data($gl_capital['id'],$post['from'],$post['to']);

            $loan[$gl_loan['id']] = loans_data($gl_loan['id'],$post['from'],$post['to']);
            $loan[$gl_loan['id']]['name'] = $gl_loan['name'];
            $loan[$gl_loan['id']]['sub_categories'] = get_loans_sub_grp_data($gl_loan['id'],$post['from'],$post['to']);

            $current_lib[$gl_lib['id']] = Currlib_data($gl_lib['id'],$post['from'],$post['to']);
            $current_lib[$gl_lib['id']]['name'] = $gl_lib['name'];
            $current_lib[$gl_lib['id']]['sub_categories'] = get_Currlib_sub_grp_data($gl_lib['id'],$post['from'],$post['to']);

            $fixedassets[$gl_fixedassets['id']] = Fixed_Assets_data($gl_fixedassets['id'],$post['from'],$post['to']);
            $fixedassets[$gl_fixedassets['id']]['name'] = $gl_fixedassets['name'];
            $fixedassets[$gl_fixedassets['id']]['sub_categories'] = get_FixedAssets_sub_grp_data($gl_fixedassets['id'],$post['from'],$post['to']);
            
            $currentassets[$gl_currentassets['id']] = Current_Assets_data($gl_currentassets['id'],$post['from'],$post['to']);
            $currentassets[$gl_currentassets['id']]['name'] = $gl_currentassets['name'];
            $currentassets[$gl_currentassets['id']]['sub_categories'] = get_CurrentAssets_sub_grp_data($gl_currentassets['id'],$post['from'],$post['to']);  

            $otherassets[$gl_otherassets['id']] = Other_Assets_data($gl_otherassets['id'],$post['from'],$post['to']);
            $otherassets[$gl_otherassets['id']]['name'] = $gl_otherassets['name'];
            $otherassets[$gl_otherassets['id']]['sub_categories'] = get_OtherAssets_sub_grp_data($gl_otherassets['id'],$post['from'],$post['to']);

            
        }else if($company_from != 0000-00-00 && $company_to != 0000-00-00){
            
            
            $post['from'] = db_date($company_from);
            $post['to'] = db_date($company_to);

            $balancesheet  = balancesheet_detail($post['from'],$post['to']);
            $pl  = pl_tot_data($post['from'],$post['to']);
            
            $closing_stock = $this->model->get_closing_stock($post['from'],$post['to']);
            $closing_bal = $this->model->get_closing_bal($post['from'],$post['to']);
            $Opening_bal = Opening_bal('Opening Stock',$post['from'],$post['to']);

            $sale_purchase = sale_purchase_itm_total($post['from'],$post['to']); 

            $capital[$gl_capital['id']] = capital_data($gl_capital['id'],$post['from'],$post['to']);
            $capital[$gl_capital['id']]['name'] = $gl_capital['name'];
            $capital[$gl_capital['id']]['sub_categories'] = get_capital_sub_grp_data($gl_capital['id'],$post['from'],$post['to']);

            $loan[$gl_loan['id']] = loans_data($gl_loan['id'],$post['from'],$post['to']);
            $loan[$gl_loan['id']]['name'] = $gl_loan['name'];
            $loan[$gl_loan['id']]['sub_categories'] = get_loans_sub_grp_data($gl_loan['id'],$post['from'],$post['to']);

            $current_lib[$gl_lib['id']] = Currlib_data($gl_lib['id'],$post['from'],$post['to']);
            $current_lib[$gl_lib['id']]['name'] = $gl_lib['name'];
            $current_lib[$gl_lib['id']]['sub_categories'] = get_Currlib_sub_grp_data($gl_lib['id'],$post['from'],$post['to']);

            $fixedassets[$gl_fixedassets['id']] = Fixed_Assets_data($gl_fixedassets['id'],$post['from'],$post['to']);
            $fixedassets[$gl_fixedassets['id']]['name'] = $gl_fixedassets['name'];
            $fixedassets[$gl_fixedassets['id']]['sub_categories'] = get_FixedAssets_sub_grp_data($gl_fixedassets['id'],$post['from'],$post['to']);
            
            $currentassets[$gl_currentassets['id']] = Current_Assets_data($gl_currentassets['id'],$post['from'],$post['to']);
            $currentassets[$gl_currentassets['id']]['name'] = $gl_currentassets['name'];
            $currentassets[$gl_currentassets['id']]['sub_categories'] = get_CurrentAssets_sub_grp_data($gl_currentassets['id'],$post['from'],$post['to']);  
            
           // echo '<pre>';print_r($currentassets);exit;


            $otherassets[$gl_otherassets['id']] = Other_Assets_data($gl_otherassets['id'],$post['from'],$post['to']);
            $otherassets[$gl_otherassets['id']]['name'] = $gl_otherassets['name'];
            $otherassets[$gl_otherassets['id']]['sub_categories'] = get_OtherAssets_sub_grp_data($gl_otherassets['id'],$post['from'],$post['to']);
        }else{

            $capital[$gl_capital['id']] = capital_data($gl_capital['id']);
            $capital[$gl_capital['id']]['name'] = $gl_capital['name'];
            $capital[$gl_capital['id']]['sub_categories'] = get_capital_sub_grp_data($gl_capital['id']);

            $loan[$gl_loan['id']] = loans_data($gl_loan['id']);
            $loan[$gl_loan['id']]['name'] = $gl_loan['name'];
            $loan[$gl_loan['id']]['sub_categories'] = get_loans_sub_grp_data($gl_loan['id']);
            // echo '<pre> helper ';print_r($loan);exit;
            $current_lib[$gl_lib['id']] = Currlib_data($gl_lib['id']);
            $current_lib[$gl_lib['id']]['name'] = $gl_lib['name'];
            $current_lib[$gl_lib['id']]['sub_categories'] = get_Currlib_sub_grp_data($gl_lib['id']);

            $fixedassets[$gl_fixedassets['id']] = Fixed_Assets_data($gl_fixedassets['id']);
            $fixedassets[$gl_fixedassets['id']]['name'] = $gl_fixedassets['name'];
            $fixedassets[$gl_fixedassets['id']]['sub_categories'] = get_FixedAssets_sub_grp_data($gl_fixedassets['id']);

            $currentassets[$gl_currentassets['id']] = Current_Assets_data($gl_currentassets['id']);
            $currentassets[$gl_currentassets['id']]['name'] = $gl_currentassets['name'];
            $currentassets[$gl_currentassets['id']]['sub_categories'] = get_CurrentAssets_sub_grp_data($gl_currentassets['id']);


            $otherassets[$gl_otherassets['id']] = Other_Assets_data($gl_otherassets['id']);
            $otherassets[$gl_otherassets['id']]['name'] = $gl_otherassets['name'];
            $otherassets[$gl_otherassets['id']]['sub_categories'] = get_OtherAssets_sub_grp_data($gl_otherassets['id']);

            $balancesheet  = balancesheet_detail();
            
            $sale_purchase = sale_purchase_itm_total();
            $pl = pl_tot_data();

            $closing_stock = $this->model->get_closing_stock();
            $closing_bal = $this->model->get_closing_bal();
            $Opening_bal = Opening_bal('Opening Stock');

        }
        
        $sundry_debtors = ((@$sale_purchase['sale_total_rate'] + @$sale_purchase['sale_Gray_total_rate'] + $sale_purchase['sale_Finish_total_rate']) - (@$sale_purchase['Saleret_total_rate'] + @$sale_purchase['Retsale_Gray_total_rate'] + @$sale_purchase['Retsale_Finish_total_rate'])) + (@$currentassets['Sundry Debtors']['total']);
        $sundry_creditor = ((@$sale_purchase['pur_total_rate'] + @$sale_purchase['purchase_Gray_total_rate'] + $sale_purchase['purchase_Finish_total_rate']))  - (@$sale_purchase['Purret_total_rate'] + @$sale_purchase['Retpurchase_Gray_total_rate'] + @$sale_purchase['Retpurchase_Finish_total_rate']) + (@$current_lib['Sundry Creditors']['total']);
        // echo '<pre>';print_r($currentassets);exit;

        $init_total = 0;
        
        $capital_total = subGrp_total($capital,$init_total);
        $loan_total = subGrp_total($loan,$init_total);
        $current_lib_total = subGrp_total($current_lib,$init_total);
        $fixedassets_total = subGrp_total($fixedassets,$init_total);
        $currentassets_total = subGrp_total($currentassets,$init_total);
        $otherassets_total = subGrp_total($otherassets,$init_total);

        $data['bl']['capital'] = $capital;
        $data['bl']['capital_total'] = $capital_total;

        $data['bl']['loan'] = $loan;
        $data['bl']['loan_total'] = $loan_total;

        $data['bl']['current_lib'] = $current_lib;
        $data['bl']['current_lib_total'] = $current_lib_total;

        $data['bl']['fixedassets'] = $fixedassets;
        $data['bl']['fixedassets_total'] = $fixedassets_total;

        $data['bl']['currentassets'] = $currentassets;
        $data['bl']['currentassets_total'] = $currentassets_total;

        $data['bl']['otherassets'] = $otherassets;
        $data['bl']['otherassets_total'] = $otherassets_total;

        $data['pl'] = $pl;
        $data['bl_sheet'] = $balancesheet;

        
        $data['trading']['sundry_debtors'] = $sundry_debtors;
        $data['trading']['sundry_creditor'] = $sundry_creditor;
        $data['trading']['opening_bal'] = $Opening_bal;
        $data['trading']['from'] = user_date($sale_purchase['from']);
        $data['trading']['to'] = user_date($sale_purchase['to']);
        $data['start_date'] = $post['from']?$post['from']:$company_from;
        $data['end_date'] = $post['to']?$post['to']:$company_to;
        $data['title'] =  "Balancesheet";
        $data['trading']['closing_bal'] = @$closing_stock;
        $data['trading']['closing'] = @$closing_bal;

        // echo '<pre>';print_r($data);exit;
        

        return view('trading/balancesheet',$data);
    }

    public function get_capital_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $capital[$get['id']] = capital_data($get['id'],db_date($get['from']),db_date($get['to']));
        $capital[$get['id']]['name'] = $get['name'];
        $capital[$get['id']]['sub_categories'] = get_capital_sub_grp_data($get['id'],db_date($get['from']),db_date($get['to']));

        $data['title'] =  "Capital Sub Group";
    
        $init_total = 0;
        $capital_total = subGrp_total($capital,$init_total);

        $data['bl']['capital'] = @$capital;

        $data['bl']['capital_total'] = @$capital_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];

        return view('trading/liability/capital_sub_group_detail',$data);

    }

    public function get_loan_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $loan[$get['id']] = loans_data($get['id'],db_date($get['from']),db_date($get['to']));
        $loan[$get['id']]['name'] = $get['name'];
        
        $loan[$get['id']]['sub_categories'] = get_loans_sub_grp_data($get['id'],db_date($get['from']),db_date($get['to']));

        $data['title'] =  "Loan Sub Group";
    
        $init_total = 0;
        $loan_total = subGrp_total($loan,$init_total);

        $data['bl']['loan'] = @$loan;

        $data['bl']['loan_total'] = @$loan_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];

        
        return view('trading/liability/loan_sub_group_detail',$data);

    }

    public function get_current_lib_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $current_lib[$get['id']] = Currlib_data($get['id'],db_date($get['from']),db_date($get['to']));
        $current_lib[$get['id']]['name'] = $get['name'];
        
        $current_lib[$get['id']]['sub_categories'] = get_Currlib_sub_grp_data($get['id'],db_date($get['from']),db_date($get['to']));

        $data['title'] =  "Current Liabilities Sub Group";
    
        $init_total = 0;
        $current_lib_total = subGrp_total($current_lib,$init_total);

        $data['bl']['current_lib'] = @$current_lib;

        $data['bl']['current_lib_total'] = @$current_lib_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];

        
        return view('trading/liability/current_lib_sub_group_detail',$data);

    }

    public function get_fixed_assets_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $fixed_assets[$get['id']] = Fixed_Assets_data($get['id'],db_date($get['from']),db_date($get['to']));
        $fixed_assets[$get['id']]['name'] = $get['name'];
        
        $fixed_assets[$get['id']]['sub_categories'] = get_FixedAssets_sub_grp_data($get['id'],db_date($get['from']),db_date($get['to']));

        $data['title'] =  "Fixed Assets Sub Group";
    
        $init_total = 0;
        $fixed_assets_total = subGrp_total($fixed_assets,$init_total);

        $data['bl']['fixed_assets'] = @$fixed_assets;

        $data['bl']['fixed_assets_total'] = @$fixed_assets_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];
       // echo '<pre>';print_r($data);exit;

        
        return view('trading/assets/fixed_assets_sub_group_detail',$data);

    }

    public function get_current_assets_sub_grp(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $current_assets[$get['id']] = Current_Assets_data($get['id'],db_date($get['from']),db_date($get['to']));
        $current_assets[$get['id']]['name'] = $get['name'];

        $current_assets[$get['id']]['sub_categories'] = get_CurrentAssets_sub_grp_data($get['id'],db_date($get['from']),db_date($get['to']));
        
        
        $data['title'] =  "Current Assets Sub Group";
    
        $init_total = 0;
        $current_assets_total = subGrp_total($current_assets,$init_total);

        $data['bl']['current_assets'] = @$current_assets;

        $data['bl']['current_assets_total'] = @$current_assets_total;
        
        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];
        
        return view('trading/assets/current_assets_sub_group_detail',$data);

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
        $data['type'] = $get['type'];

        //$data['title'] =  "Trading Expence Sub Group";
        
        return view('trading/expence/sub_group_detail',$data);

    }

    //***** Voucher List ****//

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
        return view('trading/purchase_voucher',$data);
    }

    public function purchaseReturn_voucher(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get= $this->request->getGet();        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = sale_purchase_itm_total($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = sale_purchase_itm_total($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Purchase Return Voucher";
        return view('trading/purchaseReturn_voucher',$data);
    }

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
        return view('trading/sales_voucher',$data);
    }

    public function salesReturn_voucher(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get= $this->request->getGet();        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = sale_purchase_itm_total($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = sale_purchase_itm_total($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Sales Return Voucher";
        return view('trading/salesReturn_voucher',$data);
    }

    //**** Monthly  *****//

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
        // echo '<pre>';print_r($data);exit;
        return view('trading/purchaseItem_monthly',$data);

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
        return view('trading/salesItem_monthly',$data);

    }

    public function purchaseGray_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = purchaseGray_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = purchaseGray_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Gray Issue Monthly";
        // echo '<pre>';print_r($data);exit;
        return view('trading/purchaseGray_monthly',$data);

    }

    public function salesGray_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = salesGray_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = salesGray_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Gray Sales Monthly";
        // echo '<pre>';print_r($data);exit;
        return view('trading/salesGray_monthly',$data);

    }

    public function purchaseFinish_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = purchaseFinish_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = purchaseFinish_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Finish Issue Monthly";
        // echo '<pre>';print_r($data);exit;
        return view('trading/purchaseFinish_monthly',$data);

    }

    public function salesFinish_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = salesFinish_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = salesFinish_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Finish Sales Monthly";
        // echo '<pre>';print_r($data);exit;
        return view('trading/salesFinish_monthly',$data);

    }

    //****** voucher wise  *******//

    public function purchaseItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseItem_voucher_wise_data($get);        

        $data['title'] = "Purchase Report Voucher Wise";
        return view('trading/purchaseItem_voucher',$data);
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
        $data = $this->model->salesItem_voucher_wise_data($get);        

        $data['title'] = "Sales Report Voucher Wise";
        return view('trading/salesItem_voucher',$data);
    }

    public function purchaseGray_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseGray_voucher_wise_data($get);        

        $data['title'] = "Gray Issue Voucher Wise";
        return view('trading/purchaseGray_voucher',$data);
    }

    public function salesGray_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->salesGray_voucher_wise_data($get);        

        $data['title'] = "Gray Sales Voucher Wise";
        return view('trading/salesGray_voucher',$data);
    }

    public function purchaseFinish_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseFinish_voucher_wise_data($get);        

        $data['title'] = "Finish Issue Voucher Wise";
        return view('trading/purchaseFinish_voucher',$data);
    }

    public function salesFinish_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->salesFinish_voucher_wise_data($get);        

        $data['title'] = "Finish Sales Voucher Wise";
        return view('trading/salesFinish_voucher',$data);
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
        return view('trading/purchaseReturnItem_monthly',$data);

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
        // echo '<pre>';print_r($data);exit;
        return view('trading/salesReturnItem_monthly',$data);

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

    public function salesReturnItem_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->salesReturnItem_voucher_wise_data($get);        

        $data['title'] = "Sales Return Report Voucher Wise";
        return view('trading/salesReturnItem_voucher',$data);
    }

    public function purchaseReturnGray_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['purchase'] = purchaseReturnGray_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = purchaseReturnGray_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Gray Return Monthly";
        return view('trading/purchaseReturnGray_monthly',$data);

    }

    public function salesReturnGray_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $post = $this->request->getPost();
        
        if(empty($post)){
            $data['sales'] = salesReturnGray_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = salesReturnGray_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Gray Sales Return Monthly";
        return view('trading/salesReturnGray_monthly',$data);

    }

    public function purchaseReturnFinish_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $post = $this->request->getPost();
        if(empty($post)){
            $data['purchase'] = purchaseReturnFinish_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['purchase'] = purchaseReturnFinish_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Finish Return Monthly";
        return view('trading/purchaseReturnFinish_monthly',$data);

    }
    
    public function salesReturnFinish_monthly(){
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $post = $this->request->getPost();
        if(empty($post)){
            $data['sales'] = salesReturnFinish_monthly_data($get['from'],$get['to']);
            $data['date'] =$get;
        }else{
            $data['sales'] = salesReturnFinish_monthly_data($post['from'],$post['to']);
            $data['date'] =$post;
        }

        $data['title'] = "Finish Return Monthly";
        return view('trading/salesReturnFinish_monthly',$data);

    }

    public function purchaseReturnGray_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseReturnGray_voucher_wise_data($get);        

        $data['title'] = "Gray Return Voucher Wise";
        return view('trading/purchaseReturnGray_voucher',$data);
    }

    public function salesReturnGray_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->salesReturnGray_voucher_wise_data($get);        

        $data['title'] = "Gray Return Voucher Wise";
        return view('trading/salesReturnGray_voucher',$data);
    }

    public function purchaseReturnFinish_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchaseReturnFinish_voucher_wise_data($get);        

        $data['title'] = "Finish Return Voucher Wise";
        return view('trading/purchaseReturnFinish_voucher',$data);
    }

    public function salesReturnFinish_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->salesReturnFinish_voucher_wise_data($get);        

        $data['title'] = "Finish Return Voucher Wise";
        return view('trading/salesReturnFinish_voucher',$data);
    }

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

    //************* BalanceSheet Detail *********//

    public function get_capital_account_data(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data= get_capital_account_wise($get['from'],$get['to'],$get['id']);
       
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
       
        $data['title'] = "Capital Voucher";
        
        //$data['title'] = "Trading Expence Voucher";
        $data['ac_name'] =$acc['name'] ;
        // echo '<pre>';print_r($data);exit;
        return view('trading/liability/capital_acc_voucher',$data);
        
    }
    
    public function get_loan_account_data(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data= get_loan_account_wise($get['from'],$get['to'],$get['id']);
       
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
       
        
        $data['title'] = "Loan Voucher";
        
        $data['ac_name'] =$acc['name'] ;
        return view('trading/liability/loan_acc_voucher',$data);
        
    }
    
    public function get_current_lib_account_data(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $get = $this->request->getGet();
        
        $data= get_current_lib_account_wise($get['from'],$get['to'],$get['id']);
    
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Current Liabilities Voucher";
        $data['ac_name'] =$acc['name'] ;
        //update trupti 26-12-2022
        $data['ac_id'] = $get['id'];

        
        return view('trading/liability/current_lib_acc_voucher',$data);
        
    }

    public function get_fixedassets_account_data(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data= get_fixedassets_account_wise($get['from'],$get['to'],$get['id']);
       
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
       
       
        $data['title'] = "Fixed Assets Voucher";
        $data['ac_name'] =$acc['name'] ;
        $data['from'] =$get['from'];
        $data['to'] =$get['to'];
        $data['id'] =$get['id'];
        
        return view('trading/assets/fixed_assets',$data);
        
    }

    public function fixedassets_bankcash_voucher_Perwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->fixedassets_bankcash_voucher_Perwise($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "Bank Cash Voucher Wise";  
        //echo '<pre>';print_r($data);exit;      
        return view('trading/assets/fixedassets_bankcash_voucher_Perwise',$data);
    }

    public function get_currentassets_account_data(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_account_wise($get['from'],$get['to'],$get['id']);
        
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
       
       
        $data['title'] = "Current Assets Voucher";
        $data['ac_name'] =$acc['name'] ;
        $data['from'] =$get['from'];
        $data['to'] =$get['to'];
        $data['id'] =$get['id'];
        return view('trading/assets/current_assets',$data);
        
    }

    public function currentassets_banktrans_monthly_PerWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_banktrans_monthly_PerWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Bank Cash Monthly Perticular Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/currentassets_bank_cash_monthlyPer',$data);
    }

    public function currentassets_jv_monthly(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_jv_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Journal Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/currentassets_jv_monthlyAcc',$data);
    }

    public function currentassets_banktrans_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_banktrans_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Bank Cash Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/currentassets_bank_cash_monthlyAc',$data);
    }

    public function currentassets_gnrl_sales_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_gnrl_sales_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Sales Invoice Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/current_aset_gnrl_sale_inv_monthly',$data);
    }

    public function currentassets_gnrl_salesreturn_monthly_AcWise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $get = $this->request->getGet();
        
        $data = get_currentassets_gnrl_salesreturn_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Sales Return Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/current_aset_gnrl_sale_rtn_monthly',$data);
    }

    public function currentassets_gnrl_sale_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_gnrl_sale_voucher_data($get);
        
        $data['title'] = "General Sales Invoice Voucher Wise"; 
               
        return view('trading/assets/current_aset_gnrl_sale_inv_voucher_wise',$data);
    }

    public function currentassets_gnrl_sale_rtn_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_gnrl_sale_rtn_voucher_wise($get);
        
        $data['title'] = "General Sales Return Voucher Wise"; 
               
        return view('trading/assets/current_aset_gnrl_sale_rtn_voucher_wise',$data);
    }

    public function currentassets_salesinvoice_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_salesinvoice_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Sales Invoice Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/current_asset_sale_inv_monthly',$data);
    }

    public function currentassets_salesinvoice_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_salesinvoice_voucher_wise($get);
        
        $data['title'] = "Sales Invoice Voucher Wise"; 
               
        return view('trading/assets/current_aset_sale_inv_voucher_wise',$data);
    }

    public function currentassets_salesreturn_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_salesreturn_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Sales Return Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/current_aset_sales_ret_monthly',$data);
    }

    public function currentassets_salesreturn_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_salesreturn_voucher_wise($get);
        //$data['ac_id'] =@$get['id'];
        $data['title'] = "Sales Return Voucher Wise"; 
         
        // /echo '<pre>';print_r($data);exit;      
        return view('trading/assets/current_aset_sale_ret_voucher_wise',$data);
    }

    public function currentassets_millsales_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_millsales_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Gray/Finish Sales Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/current_asset_mill_sale_monthly',$data);
    }

    public function currentassets_millsales_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_millsales_voucher_wise($get);
        //$data['ac_id'] =@$get['id'];
        $data['title'] = "Gray/Finish Sales Voucher Wise"; 
         
        // /echo '<pre>';print_r($data);exit;      
        return view('trading/assets/current_aset_mill_sale_voucher_wise',$data);
    }

    public function currentassets_millsalesreturn_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_millsalesreturn_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Gray/Finish Sales Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/current_aset_mill_sales_return_monthly',$data);
    }

    public function currentassets_millsalesreturn_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_millsalesreturn_voucher_wise($get);
        //$data['ac_id'] =@$get['id'];
        $data['title'] = "Gray/Finish Sales Return Voucher Wise"; 
         
        // /echo '<pre>';print_r($data);exit;      
        return view('trading/assets/current_aset_mill_sale_ret_voucher_wise',$data);
    }

    public function currentassets_bankcash_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_bankcash_voucher_Acwise($get);        
       
        $data['title'] = "Bank Cash Voucher Wise";  
              
        return view('trading/assets/currentassets_bankcash_voucher_Acwise',$data);
    }

    public function currentassets_bankcash_voucher_Perwise(){

        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_bankcash_voucher_Perwise($get);        

        $acc = $this->gmodel->get_data_table('account',array('id'=>$get['id']),'name');

        $data['type'] =@$get['type'];
        $data['title'] = "Bank Cash Voucher Wise";  
        $data['ac_name'] = @$acc['name'];  
            
        return view('trading/assets/currentassets_bankcash_voucher_Perwise',$data);
    }

    public function currentassets_jv_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_jv_voucher_wise($get);
        
        $acc = $this->gmodel->get_data_table('account',array('id'=>$get['id'],'name'));
        

        $data['title'] = "JV Voucher Wise";
        $data['ac_name'] = $acc['name'];

        return view('trading/assets/currentassets_jv_voucher_wise',$data);
    }

    public function fixedassets_banktrans_monthly_PerWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_fixedassets_banktrans_monthly_PerWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Bank Cash Monthly Perticular Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/fixedassets_bank_cash_monthlyPer',$data);
    }

    public function currentassets_contra_monthly_PerWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_contra_monthly_PerWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Contra Transaction Monthly Perticular Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/current_assets_contra_monthlyPer',$data);
    }

    public function currentassets_contra_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_currentassets_contra_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Contra Transaction Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/current_asset_contra_monthlyAc',$data);
    }

    public function currentassets_contra_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_contra_voucher_Acwise($get);        
       
        $data['title'] = "Contra Transaction Voucher Wise";  
              
        return view('trading/assets/current_asset_contra_voucher_Acwise',$data);
    }

    public function currentassets_contra_voucher_Perwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_contra_voucher_Perwise($get);        
       
        $data['title'] = "Contra Transaction Voucher Wise";  
              
        return view('trading/assets/current_asset_contra_voucher_Perwise',$data);
    }

    public function fixedassets_jv_monthly(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_fixedassets_jv_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Journal Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/fixedassets_jv_monthlyAcc',$data);
    }

    public function fixedassets_jv_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->fixedassets_jv_voucher_wise($get);
        //$data['ac_id'] =@$get['id'];
        $data['title'] = "JV Voucher Wise";  
        //echo '<pre>';print_r($data);exit;      
        return view('trading/assets/fixedassets_jv_voucher_wise',$data);
    }

    public function fixedassets_purchaseinvoice_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_generalPurchase_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Journal Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        //echo '<pre>';print_r($data);exit;
        return view('trading/assets/fixedassets_purchase_monthlyAcc',$data);
    }

    public function fixedassets_salesinvoice_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_generalSales_monthly_AcWise($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Journal Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
       
        return view('trading/assets/fixedassets_sales_monthlyAcc',$data);
    }

    public function fixedassets_Sales_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalSales_voucher_wise_data($get);        
        
        $data['title'] = "General Sales Voucher Wise";
       
        return view('trading/assets/generalSales_voucher',$data);
    }

    public function fixedassets_purchase_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalPurchase_voucher_wise_data($get);        
        
        $data['title'] = "General Purchase Voucher Wise";
       
        return view('trading/assets/generalPurchase_voucher',$data);
    }


    //***** Monthly Trading Income Account ******//

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

    public function purchase_monthly_AcWise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        
        $data = get_purchase_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Purchase Monthly";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/liability/purchase_monthly',$data);
    }

    public function purchase_ret_monthly(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $get = $this->request->getGet();
        
        $data = get_purchase_ret_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "Purchase Return Monthly";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/liability/purchase_ret_monthly',$data);
    }
    
    public function gray_finish_monthly(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $get = $this->request->getGet();
        
        $data = get_gray_finish_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Gray/Finish Purchase Monthly";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];

        return view('trading/liability/gray_finish_monthly',$data);
    }

    public function gray_finish_ret_monthly(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        
        $get = $this->request->getGet();
        
        $data = get_gray_finish_ret_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        
        $data['title'] = "Gray/Finish Purchase Return Monthly";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];

        return view('trading/liability/gray_finish_ret_monthly',$data);
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

    public function generalSales_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalSales_voucher_wise_data($get);        
        
        $data['title'] = "General Sales Voucher Wise";
        $data['type']=@$get['type'];
        return view('trading/income/generalSales_voucher',$data);
    }
    
    public function bank_cash_voucher_wise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->bank_cash_voucher_wise_data($get);        
       
        $data['title'] = "Bank Cash Voucher Wise";      
        
        return view('trading/income/bank_cash_voucher',$data);
    }

    public function purchase_ret_voucher_wise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchase_ret_voucher_wise_data($get); 
        // update trupti 26-12-2022 duties and taxes add taxes account
        $data['ac_id'] = $get['id'];       
       
        $data['title'] = "Purchase Return Voucher Wise";      
        
        return view('trading/liability/purchase_ret_voucher',$data);
    }

    public function purchase_voucher_wise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchase_voucher_wise_data($get);        
       
        $data['title'] = "Purchase Voucher Wise";      
        
        return view('trading/liability/purchase_voucher',$data);
    }
    
    public function gray_finish_voucher_wise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->gray_finish_voucher_wise_data($get);        
       
        $data['title'] = "Gray/Finish Purchase Voucher Wise";      
        
        return view('trading/liability/grayFinish_purchase_voucher',$data);
    }

    public function gray_finish_ret_voucher_wise(){
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->gray_finish_ret_voucher_wise_data($get);        
       
        $data['title'] = "Gray/Finish Return Purchase Voucher Wise";      
        
        return view('trading/liability/grayFinish_ret_purchase_voucher',$data);
    }
    
    public function jv_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->jv_voucher_wise_data($get);        
       
        $data['title'] = "Jounral Voucher Wise";
        $data['type'] = $get['type'];        
        return view('trading/income/jv_voucher',$data);
    }


    //***** Monthly Trading Expense Account ******//

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

    public function generalPurchase_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalPurchase_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "General Purchase Voucher Wise";
        
        //echo '<pre>';print_r($data);exit;
        return view('trading/expence/generalPurchase_voucher',$data);
    }

    public function purchase_jv_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchase_jv_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "Jounral Voucher Wise";        
        return view('trading/expence/purchase_jv_voucher',$data);
    }

    public function purchase_bankcash_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchase_bank_cash_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "Bank Cash Voucher Wise";  
        //echo '<pre>';print_r($data);exit;      
        return view('trading/expence/purchase_bank_cash_voucher',$data);
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

    public function generalPurchase_monthly(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();
        
        $data = get_generalPurchase_monthly($get['from'],$get['to'],$get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array("id"=>$get['id']),'name');
        $data['title'] = "General Purchase Monthly Account Wise";
        $data['ac_name'] =$acc['name'];
        $data['ac_id'] =@$get['id'];
        $data['type'] =@$get['type'];

        return view('trading/liability/general_purchase_monthly',$data);
    }

    public function generalPurchase_voucher_wise_liability(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalPurchase_liabi_voucher_wise_data($get);        
        $data['type'] =@$get['type'];
        $data['title'] = "General Purchase Voucher Wise";
        
        return view('trading/liability/generalPurchase_liab_voucher',$data);
    }
    //update trupti 01-12-2022
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
    public function Balancesheet_xls(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        } 
        $post = $this->request->getGet();
        if(!empty($post)){
            $data = $this->model->balancesheet_xls_export_data($post);
        }else{       
            $post['from'] = session('financial_form'); 
            $post['to'] = session('financial_to'); 
            $data = $this->model->balancesheet_xls_export_data($post);   
        }

        return $this->response->setHeader('Contente-Disposition','attachment;filename=abc.xlsx')
        ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       
    }
   // START update trupti 26-12-2022 duties and taxes add taxes account
     public function sales_monthly_AcWise()
     {
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
         $get = $this->request->getGet();
 
         $data = get_sales_monthly($get['from'], $get['to'], $get['id']);
         $gmodel = new GeneralModel();
         $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
         $data['title'] = "Sales Invoice Monthly Account Wise";
         $data['ac_name'] = $acc['name'];
         $data['ac_id'] = @$get['id'];
 
         //echo '<pre>';print_r($data);exit;
         return view('trading/liability/sales_monthly', $data);
     }
     public function sales_ret_monthly()
     {
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
         $get = $this->request->getGet();
 
         $data = get_sales_ret_monthly($get['from'], $get['to'], $get['id']);
         $gmodel = new GeneralModel();
         $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
         $data['title'] = "Sales Return Monthly Account Wise";
         $data['ac_name'] = $acc['name'];
         $data['ac_id'] = @$get['id'];
 
         //echo '<pre>';print_r($data);exit;
         return view('trading/liability/sales_ret_monthly', $data);
     }
      public function generalsales_monthly()
     {
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
         $get = $this->request->getGet();
 
         $data = get_generalSales_monthly($get['from'], $get['to'], $get['id']);
         // echo '<pre>';print_r($data);exit;
 
         $gmodel = new GeneralModel();
         $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
         $data['title'] = "General Purchase Monthly Account Wise";
         $data['ac_name'] = $acc['name'];
         $data['ac_id'] = @$get['id'];
         $data['type'] = @$get['type'];
 
         return view('trading/liability/general_sales_monthly', $data);
     }
    public function sales_voucher_wise()
     {
 
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
 
         $get = $this->request->getGet();
         $data = $this->model->sales_voucher_wise_data($get);
 
         $data['title'] = "Sales Voucher Wise";
 
         return view('trading/liability/sales_voucher', $data);
     }
    public function sales_ret_voucher_wise()
     {
 
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
 
         $get = $this->request->getGet();
         $data = $this->model->sales_ret_voucher_wise_data($get);
 
         $data['title'] = "Sales Return Voucher Wise";
 
         return view('trading/liability/sales_ret_voucher', $data);
     }
    public function generalSales_voucher_wise_liability()
     {
         if (!session('cid')) {
             return redirect()->to(url('company'));
         }
 
         $get = $this->request->getGet();
         $data = $this->model->generalSales_liabi_voucher_wise_data($get);
         $data['type'] = @$get['type'];
         $data['title'] = "GeneralSales Voucher Wise";
         //echo '<pre>';print_r($data);exit;
 
         return view('trading/liability/generalsales_liab_voucher', $data);
     }
     // end update trupti 26-12-2022 duties and taxes add taxes account


}