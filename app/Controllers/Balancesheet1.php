<?php

namespace App\Controllers;

use App\Models\GeneralModel;
use App\Models\BalancesheetModel;
use App\Models\TradingModel;

class Balancesheet extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new BalancesheetModel();
        $this->gmodel = new GeneralModel();
        $this->tmodel = new TradingModel();
        // helper('BalanceSheet');    
    }
    public function index()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $post = $this->request->getPost();

        $gmodel = new GeneralModel;

        $gl_capital = $gmodel->get_data_table('gl_group', array('name' => 'Capital'), 'id,name');
        $gl_loan = $gmodel->get_data_table('gl_group', array('name' => 'Loans'), 'id,name');
        $gl_lib = $gmodel->get_data_table('gl_group', array('name' => 'Current Liabilities'), 'id,name');
        $gl_fixedassets = $gmodel->get_data_table('gl_group', array('name' => 'Fixed Assets'), 'id,name');
        $gl_currentassets = $gmodel->get_data_table('gl_group', array('name' => 'Current Assets'), 'id,name');
        $gl_otherassets = $gmodel->get_data_table('gl_group', array('name' => 'Other Assets'), 'id,name');

        $company_from = session('financial_form');
        $company_to = session('financial_to');
       
        
        if (!empty($post)) {

            $from = date_create($post['from']);
            $to = date_create($post['to']);

            $post['from'] = date_format($from, "Y-m-d");
            $post['to'] = date_format($to, "Y-m-d");

            $balancesheet  = balancesheet_detail($post['from'], $post['to']);
            $pl = pl_tot_data_bl($post['from'], $post['to']);

            // $closing_stock = $this->tmodel->get_closing_stock($post['from'], $post['to']);
            // $closing_bal = $this->tmodel->get_closing_bal($post['from'], $post['to']);
            // $Opening_bal = Opening_bal('Opening Stock', $post['from'], $post['to']);
            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock($post['from'],$post['to']);
            $closing_data = $this->tmodel->get_closing_detail($post['from'],$post['to']);

            $sale_purchase = sale_purchase_itm_total($post['from'], $post['to']);

            $capital[$gl_capital['id']] = capital_data($gl_capital['id'], $post['from'], $post['to']);
            $capital[$gl_capital['id']]['name'] = $gl_capital['name'];
            $capital[$gl_capital['id']]['sub_categories'] = get_capital_sub_grp_data($gl_capital['id'], $post['from'], $post['to']);

            $loan[$gl_loan['id']] = loans_data($gl_loan['id'], $post['from'], $post['to']);
            $loan[$gl_loan['id']]['name'] = $gl_loan['name'];
            $loan[$gl_loan['id']]['sub_categories'] = get_loans_sub_grp_data($gl_loan['id'], $post['from'], $post['to']);

            $current_lib[$gl_lib['id']] = Currlib_data($gl_lib['id'], $post['from'], $post['to']);
            $current_lib[$gl_lib['id']]['name'] = $gl_lib['name'];
            $current_lib[$gl_lib['id']]['sub_categories'] = get_Currlib_sub_grp_data($gl_lib['id'], $post['from'], $post['to']);

            $fixedassets[$gl_fixedassets['id']] = Fixed_Assets_data($gl_fixedassets['id'], $post['from'], $post['to']);
            $fixedassets[$gl_fixedassets['id']]['name'] = $gl_fixedassets['name'];
            $fixedassets[$gl_fixedassets['id']]['sub_categories'] = get_FixedAssets_sub_grp_data($gl_fixedassets['id'], $post['from'], $post['to']);
           
            
            $currentassets[$gl_currentassets['id']] = Current_Assets_data($gl_currentassets['id'], $post['from'], $post['to']);
            $currentassets[$gl_currentassets['id']]['name'] = $gl_currentassets['name'];
            $currentassets[$gl_currentassets['id']]['sub_categories'] = get_CurrentAssets_sub_grp_data($gl_currentassets['id'], $post['from'], $post['to']);

            $otherassets[$gl_otherassets['id']] = Other_Assets_data($gl_otherassets['id'], $post['from'], $post['to']);
            $otherassets[$gl_otherassets['id']]['name'] = $gl_otherassets['name'];
            $otherassets[$gl_otherassets['id']]['sub_categories'] = get_OtherAssets_sub_grp_data($gl_otherassets['id'], $post['from'], $post['to']);
        
        } else if ($company_from != 0000 - 00 - 00 && $company_to != 0000 - 00 - 00) {


            $post['from'] = db_date($company_from);
            $post['to'] = db_date($company_to);

            $balancesheet  = balancesheet_detail($post['from'], $post['to']);
            $pl  = pl_tot_data_bl($post['from'], $post['to']);

            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock($post['from'],$post['to']);
            $closing_data = $this->tmodel->get_closing_detail($post['from'],$post['to']);

            $sale_purchase = sale_purchase_itm_total($post['from'], $post['to']);

            $capital[$gl_capital['id']] = capital_data($gl_capital['id'], $post['from'], $post['to']);
            $capital[$gl_capital['id']]['name'] = $gl_capital['name'];
            $capital[$gl_capital['id']]['sub_categories'] = get_capital_sub_grp_data($gl_capital['id'], $post['from'], $post['to']);

            $loan[$gl_loan['id']] = loans_data($gl_loan['id'], $post['from'], $post['to']);
            $loan[$gl_loan['id']]['name'] = $gl_loan['name'];
            $loan[$gl_loan['id']]['sub_categories'] = get_loans_sub_grp_data($gl_loan['id'], $post['from'], $post['to']);

            $current_lib[$gl_lib['id']] = Currlib_data($gl_lib['id'], $post['from'], $post['to']);
            $current_lib[$gl_lib['id']]['name'] = $gl_lib['name'];
            $current_lib[$gl_lib['id']]['sub_categories'] = get_Currlib_sub_grp_data($gl_lib['id'], $post['from'], $post['to']);

            $fixedassets[$gl_fixedassets['id']] = Fixed_Assets_data($gl_fixedassets['id'], $post['from'], $post['to']);
            $fixedassets[$gl_fixedassets['id']]['name'] = $gl_fixedassets['name'];
            $fixedassets[$gl_fixedassets['id']]['sub_categories'] = get_FixedAssets_sub_grp_data($gl_fixedassets['id'], $post['from'], $post['to']);

            $currentassets[$gl_currentassets['id']] = Current_Assets_data($gl_currentassets['id'], $post['from'], $post['to']);
            $currentassets[$gl_currentassets['id']]['name'] = $gl_currentassets['name'];
            $currentassets[$gl_currentassets['id']]['sub_categories'] = get_CurrentAssets_sub_grp_data($gl_currentassets['id'], $post['from'], $post['to']);

            // echo '<pre>';print_r($currentassets);exit;


            $otherassets[$gl_otherassets['id']] = Other_Assets_data($gl_otherassets['id'], $post['from'], $post['to']);
            $otherassets[$gl_otherassets['id']]['name'] = $gl_otherassets['name'];
            $otherassets[$gl_otherassets['id']]['sub_categories'] = get_OtherAssets_sub_grp_data($gl_otherassets['id'], $post['from'], $post['to']);
        } else {

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
            $pl = pl_tot_data_bl();

            $Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock();
            $closing_data = $this->tmodel->get_closing_detail();
        }
      // echo '<pre>';Print_r($fixedassets);exit;
       $sundry_debtors = ((@$sale_purchase['sale_total_rate']) - (@$sale_purchase['Saleret_total_rate'] )) + (@$currentassets['Sundry Debtors']['total']);
       $sundry_creditor = ((@$sale_purchase['pur_total_rate'])  - (@$sale_purchase['Purret_total_rate'])) + (@$current_lib['Sundry Creditors']['total']);
      // echo '<pre>';print_r($currentassets);exit;

        $init_total = 0;

        $capital_total = subGrp_total($capital, $init_total);
        $loan_total = subGrp_total($loan, $init_total);
        $current_lib_total = subGrp_total($current_lib, $init_total);
        $fixedassets_total = subGrp_total($fixedassets, $init_total);
        $currentassets_total = subGrp_total($currentassets, $init_total);
        $otherassets_total = subGrp_total($otherassets, $init_total);
        //echo '<pre>';Print_r($fixedassets_total);exit;
        
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
        $data['start_date'] = $post['from'] ? $post['from'] : $company_from;
        $data['end_date'] = $post['to'] ? $post['to'] : $company_to;
        $data['title'] =  "Balancesheet";
        // $data['trading']['closing_bal'] = @$closing_stock;
        // $data['trading']['closing'] = @$closing_bal;

        $data['trading']['opening_bal'] = $Opening_bal;
        $data['trading']['closing_bal'] = @$closing_data['closing_bal']; 
        $data['trading']['closing_stock'] = @$closing_data['closing_stock'];
        $data['trading']['manualy_closing_bal'] = @$manualy_closing_bal;

        // echo '<pre>';print_r($data);exit;


        return view('trading/balancesheet/balancesheet_view', $data);
    }
    //*******************************capital account****************//
    public function get_capital_account_data()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();

        $data = get_capital_account_wise($get['from'], $get['to'], $get['id']);

        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');

        $data['title'] = "Capital Voucher";
        $data['ac_name'] = $acc['name'];
        //echo '<pre>';Print_r($data);exit;
        
        return view('trading/liability/capital_acc_voucher', $data);
    }
    public function get_loan_account_data()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();

        $data = get_loan_account_wise($get['from'], $get['to'], $get['id']);

        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');


        $data['title'] = "Loan Voucher";

        $data['ac_name'] = $acc['name'];
        return view('trading/liability/loan_acc_voucher', $data);
    }
    public function get_current_lib_sub_grp()
    {

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();

        $current_lib[$get['id']] = Currlib_data($get['id'], db_date($get['from']), db_date($get['to']));
        $current_lib[$get['id']]['name'] = $get['name'];

        $current_lib[$get['id']]['sub_categories'] = get_Currlib_sub_grp_data($get['id'], db_date($get['from']), db_date($get['to']));

        $data['title'] =  "Current Liabilities Sub Group";

        $init_total = 0;
        $current_lib_total = subGrp_total($current_lib, $init_total);

        $data['bl']['current_lib'] = @$current_lib;

        $data['bl']['current_lib_total'] = @$current_lib_total;

        $data['date']['from'] = $get['from'];
        $data['date']['to'] = $get['to'];


        return view('trading/liability/current_lib_sub_group_detail', $data);
    }
    public function get_current_lib_account_data()
    {

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();

        $data = get_current_lib_account_wise($get['from'], $get['to'], $get['id']);

        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
        $data['title'] = "Current Liabilities Voucher";
        $data['ac_name'] = $acc['name'];
        //update trupti 26-12-2022
        $data['ac_id'] = $get['id'];


        return view('trading/liability/current_lib_acc_voucher', $data);
    }
    public function purchase_monthly_AcWise()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();

        $data = get_purchase_monthly($get['from'], $get['to'], $get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
        $data['title'] = "Purchase Monthly";
        $data['ac_name'] = $acc['name'];
        $data['ac_id'] = @$get['id'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/liability/purchase_monthly', $data);
    }
    public function purchase_voucher_wise()
    {

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchase_voucher_wise_data($get);

        $data['title'] = "Purchase Voucher Wise";

        return view('trading/liability/purchase_voucher', $data);
    }
    public function purchase_ret_monthly()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();

        $data = get_purchase_ret_monthly($get['from'], $get['to'], $get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
        $data['title'] = "Purchase Return Monthly";
        $data['ac_name'] = $acc['name'];
        $data['ac_id'] = @$get['id'];
        // echo '<pre>';print_r($data);exit;
        return view('trading/liability/purchase_ret_monthly', $data);
    }
    public function purchase_ret_voucher_wise()
    {

        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->purchase_ret_voucher_wise_data($get);
        // update trupti 26-12-2022 duties and taxes add taxes account
        $data['ac_id'] = $get['id'];

        $data['title'] = "Purchase Return Voucher Wise";

        return view('trading/liability/purchase_ret_voucher', $data);
    }
    public function generalPurchase_monthly()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }
        $get = $this->request->getGet();

        $data = get_generalPurchase_monthly($get['from'], $get['to'], $get['id']);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array("id" => $get['id']), 'name');
        $data['title'] = "General Purchase Monthly Account Wise";
        $data['ac_name'] = $acc['name'];
        $data['ac_id'] = @$get['id'];
        $data['type'] = @$get['type'];

        return view('trading/liability/general_purchase_monthly', $data);
    }
    public function generalPurchase_voucher_wise_liability()
    {
        if (!session('cid')) {
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalPurchase_liabi_voucher_wise_data($get);
        $data['type'] = @$get['type'];
        $data['title'] = "General Purchase Voucher Wise";

        return view('trading/liability/generalPurchase_liab_voucher', $data);
    }
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
    public function get_current_assets_sub_grp(){
        
        
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        //echo '<pre>';Print_r($get);exit;
        
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
        $data['ac_id'] = $get['id'];
        $data['ac_name'] = $get['name'];
        
        return view('trading/assets/current_assets_sub_group_detail',$data);

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
    public function currentassets_bankcash_voucher_Perwise(){

        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        //echo '<pre>';Print_r($get);exit;
        
        $data = $this->model->currentassets_bankcash_voucher_Perwise($get);        

        $acc = $this->gmodel->get_data_table('account',array('id'=>$get['id']),'name');

        $data['type'] =@$get['type'];
        $data['title'] = "Bank Cash Voucher Wise";  
        $data['ac_name'] = @$acc['name'];  
            
        return view('trading/assets/currentassets_bankcash_voucher_Perwise',$data);
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
    public function currentassets_bankcash_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_bankcash_voucher_Acwise($get);        
       
        $data['title'] = "Bank Cash Voucher Wise";  
              
        return view('trading/assets/currentassets_bankcash_voucher_Acwise',$data);
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
    public function currentassets_gnrl_sale_voucher_Acwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_gnrl_sale_voucher_data($get);
        
        $data['title'] = "General Sales Invoice Voucher Wise"; 
               
        return view('trading/assets/current_aset_gnrl_sale_inv_voucher_wise',$data);
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
    public function currentassets_gnrl_sale_rtn_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_gnrl_sale_rtn_voucher_wise($get);
        
        $data['title'] = "General Sales Return Voucher Wise"; 
               
        return view('trading/assets/current_aset_gnrl_sale_rtn_voucher_wise',$data);
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
    public function currentassets_contra_voucher_Perwise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->currentassets_contra_voucher_Perwise($get);        
       
        $data['title'] = "Contra Transaction Voucher Wise";  
              
        return view('trading/assets/current_asset_contra_voucher_Perwise',$data);
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
    public function fixedassets_purchase_voucher_wise(){
        if(!session('cid')){
            return redirect()->to(url('company'));
        }

        $get = $this->request->getGet();
        $data = $this->model->generalPurchase_voucher_wise_data($get);        
        
        $data['title'] = "General Purchase Voucher Wise";
       
        return view('trading/assets/generalPurchase_voucher',$data);
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
    // sub group
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
        $data['ac_id'] = $get['id'];
        $data['ac_name'] = $get['name'];
        
        return view('trading/liability/loan_sub_group_detail',$data);

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
}

