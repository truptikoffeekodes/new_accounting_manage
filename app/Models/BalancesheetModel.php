<?php

namespace App\Models;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\TradingModel;
use App\Models\GeneralModel;

class BalancesheetModel extends Model
{
    public function __construct() {
        parent::__construct();
        $this->tmodel = new TradingModel();
        $this->gmodel = new GeneralModel();
       
    }
    public function purchase_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =pi.account');
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_invoice = $query->getResultArray();

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id'] . '');
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_igst = $query->getResultArray();

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_cgst = $query->getResultArray();

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_sgst = $query->getResultArray();
            $purchase['purchase'] =  array_merge($purchase_invoice, $purchase_igst, $purchase_cgst, $purchase_sgst);

        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =pi.account');
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_invoice = $query->getResultArray();

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id'] . '');
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_igst = $query->getResultArray();

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_cgst = $query->getResultArray();

            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_sgst = $query->getResultArray();
            $purchase['purchase'] =  array_merge($purchase_invoice, $purchase_igst, $purchase_cgst, $purchase_sgst);

        } else {
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }
        $total_taxable = 0;
        foreach($purchase['purchase'] as $row)
        {
            $total_taxable += $row['taxable'];
        }
        $purchase['total_taxable'] = $total_taxable;
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        $purchase['ac_id'] = $get['id'];

        return $purchase;
    }
    public function purchase_ret_voucher_wise_data($get)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return = $query->getResultArray();

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return_igst = $query->getResultArray();
            //print_r($purchase_return_igst);exit;

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return_cgst = $query->getResultArray();

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return_sgst = $query->getResultArray();

            $purchase['purchase_ret'] =  array_merge($purchase_return, $purchase_return_igst, $purchase_return_sgst, $purchase_return_cgst);
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return = $query->getResultArray();

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return_igst = $query->getResultArray();

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return_cgst = $query->getResultArray();

            $builder = $db->table('purchase_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id =' . $get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $purchase_return_sgst = $query->getResultArray();

            $purchase['purchase_ret'] =  array_merge($purchase_return, $purchase_return_igst, $purchase_return_sgst, $purchase_return_cgst);
        } else {
            $purchase['purchase_ret'] = array();
            $start_date = '';
            $end_date = '';
        }
        $total_taxable = 0;
        foreach($purchase['purchase_ret'] as $row)
        {
            $total_taxable += $row['taxable'];
        }
        $purchase['total_taxable'] = $total_taxable;
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        $purchase['ac_id'] = $get['id'];
        //echo '<pre>';print_r($purchase);exit;
        return $purchase;
    }
    public function generalPurchase_liabi_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where('pg.party_account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_invoice = $query->getResultArray();

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.igst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_igst = $query->getResultArray();

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = '.$get['id']);
            $builder->where('pg.cgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_cgst = $query->getResultArray();

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = '.$get['id']);
            $builder->where('pg.sgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_sgst = $query->getResultArray();

            $pg_expence['purchase'] =  array_merge($purchase_invoice,$purchase_igst,$purchase_cgst,$purchase_sgst);


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pg.party_account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_invoice = $query->getResultArray();

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.igst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_igst = $query->getResultArray();

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = '.$get['id']);
            $builder->where('pg.cgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_cgst = $query->getResultArray();

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = '.$get['id']);
            $builder->where('pg.sgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $purchase_sgst = $query->getResultArray();

            $pg_expence['purchase'] =  array_merge($purchase_invoice,$purchase_igst,$purchase_cgst,$purchase_sgst);

        }else{
            $pg_expence['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result['purchase'] = array();
       // echo '<pre>';Print_r($);exit;
        
        $total = 0;
        $credit = 0;
        $debit = 0;
        if(!empty($pg_expence['purchase'])){
            foreach ($pg_expence['purchase'] as $row) {
       
               
                if($row['pg_type'] == 'general'){
                    ///$total += (float)$row['pg_amount'];
                    $credit += (float)$row['pg_amount'];
                }else{
                   // $total -= (float)$row['pg_amount'];
                    $debit  += (float)$row['pg_amount'];
                } 
                //$row['taxable'] = $total;
               // $result['purchase'][] = $row; 
              
            }
        }
        $result['purchase'] = $pg_expence['purchase'];
        //$result['total_taxable'] = $total;
        $result['credit'] = $credit;
        $result['debit'] = $debit;
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function sales_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =pi.account');
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_invoice = $query->getResultArray();   
            
            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_igst = $query->getResultArray(); 

            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_cgst = $query->getResultArray(); 

            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_sgst = $query->getResultArray();

            $sales['sales'] =  array_merge($sales_invoice,$sales_igst,$sales_cgst,$sales_sgst);


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =pi.account');
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_invoice = $query->getResultArray();   
            
            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_igst = $query->getResultArray(); 

            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_cgst = $query->getResultArray(); 

            $builder = $db->table('sales_invoice pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.invoice_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('pi.is_cancle' => '0'));
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_sgst = $query->getResultArray();

            $sales['sales'] =  array_merge($sales_invoice,$sales_igst,$sales_cgst,$sales_sgst);

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }  
        $total_taxable = 0;
        foreach($sales['sales'] as $row) 
        {
           $total_taxable += $row['taxable']; 
        }
        $sales['total_taxable'] = $total_taxable;
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;
        $sales['ac_id'] = $get['id'];

        return $sales;     
    }
    public function sales_ret_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =pi.account');
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return = $query->getResultArray();

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return_igst = $query->getResultArray();

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return_cgst = $query->getResultArray();

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return_sgst = $query->getResultArray();

            $sales['sales_ret'] =  array_merge($sales_return,$sales_return_igst,$sales_return_cgst,$sales_return_sgst);



        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.net_amount as taxable');
            $builder->join('account ac', 'ac.id =pi.account');
            $builder->where(array('pi.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return = $query->getResultArray();

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_igst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.igst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return_igst = $query->getResultArray();

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_cgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.cgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return_cgst = $query->getResultArray();

            $builder = $db->table('sales_return pi');
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_sgst as taxable');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where(array('pi.sgst_acc' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pi.is_delete' => '0'));
            $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
            $builder->groupBy('pi.id');
            $query = $builder->get();
            $sales_return_sgst = $query->getResultArray();

            $sales['sales_ret'] =  array_merge($sales_return,$sales_return_igst,$sales_return_cgst,$sales_return_sgst);
 

        }else{
            $sales['sales_ret'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $total_taxable = 0;
        foreach($sales['sales_ret'] as $row) 
        {
           $total_taxable += $row['taxable']; 
        }
        $sales['total_taxable'] = $total_taxable;
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;
        $sales['ac_id'] = $get['id'];

        return $sales;     
    }
    public function generalSales_liabi_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where('pg.party_account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_invoice = $query->getResultArray();

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.igst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_igst = $query->getResultArray();
            //print_r($sales_igst);exit;

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.cgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_cgst = $query->getResultArray();

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.sgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_sgst = $query->getResultArray();

            $sales['sales'] =  array_merge($sales_invoice,$sales_igst,$sales_cgst,$sales_sgst);


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pg.party_account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_invoice = $query->getResultArray();

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.igst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_igst = $query->getResultArray();

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.cgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_cgst = $query->getResultArray();

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id ='.$get['id']);
            $builder->where('pg.sgst_acc',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_sgst = $query->getResultArray();

            $sales['sales'] =  array_merge($sales_invoice,$sales_igst,$sales_cgst,$sales_sgst);

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result['sales'] = array();
        $total = 0;
        $credit = 0;
        $debit = 0;
        if(!empty($sales['sales'])){
            foreach ($sales['sales'] as $row) {
       
               
                if($row['pg_type'] == 'general'){
                    ///$total += (float)$row['pg_amount'];
                    $credit += (float)$row['pg_amount'];
                }else{
                   // $total -= (float)$row['pg_amount'];
                    $debit  += (float)$row['pg_amount'];
                } 
                //$row['taxable'] = $total;
               // $result['purchase'][] = $row; 
              
            }
        }
        $result['sales'] = $sales['sales'];
        //$result['total_taxable'] = $total;
        $result['credit'] = $credit;
        $result['debit'] = $debit;
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function currentassets_bankcash_voucher_Perwise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income['currentassets_banktrans'] = $query->getResultArray();     
            

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';
            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income['currentassets_banktrans'] = $query->getResultArray();  

        }else{
            $bank_income['currentassets_banktrans'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $bank_income['date']['from'] = $start_date;
        $bank_income['date']['to'] = $end_date;
        $bank_income['ac_id'] = $get['id'];

        // echo '<pre>';print_r($bank_income);exit;

        return $bank_income;     
    }
    public function currentassets_jv_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('jv_particular jv');
            $builder->select('jm.id,jv.jv_id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->join('jv_main jm', 'jm.id =jv.jv_id');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jm.is_delete' => '0'));
            $builder->where(array('jv   .is_delete' => '0'));
            $builder->where(array('DATE(jm.date)  >= ' => $start_date));
            $builder->where(array('DATE(jm.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_income['currentassets_jv'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('jv_particular jv');
            $builder->select('jm.id,jv.jv_id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->join('jv_main jm', 'jm.id =jv.jv_id');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jm.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jm.date)  >= ' => $start_date));
            $builder->where(array('DATE(jm.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_income['currentassets_jv'] = $query->getResultArray();

        }else{

            $jv_income['currentassets_jv'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $jv_income['date']['from'] = $start_date;
        $jv_income['date']['to'] = $end_date;
        $jv_income['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $jv_income;     
    }
    public function currentassets_bankcash_voucher_Acwise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.account');
            $builder->where(array('bt.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.payment_type !=' => 'contra'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income['currentassets_banktrans'] = $query->getResultArray();     


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as total,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('bt.payment_type !=' => 'contra'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income['currentassets_banktrans'] = $query->getResultArray();      

        }else{
            $bank_income['currentassets_banktrans'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $bank_income['date']['from'] = $start_date;
        $bank_income['date']['to'] = $end_date;
        $bank_income['ac_id'] = $get['id'];
        // echo '<pre>';print_r($bank_income);exit;
        return $bank_income;     
    }
    public function currentassets_salesinvoice_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('sales_invoice si');
            $builder->select('si.id,si.invoice_date as date,ac.id as account_id,si.net_amount as taxable, ac.name as party_name');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('si.is_cancle' => '0'));
            $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $sales_invoice['currentassets_salesinvoice'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_invoice si');
            $builder->select('si.id,si.invoice_date as date,ac.id as account_id,si.net_amount as taxable, ac.name as party_name');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('si.is_cancle' => '0'));
            $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $sales_invoice['currentassets_salesinvoice'] = $query->getResultArray();

        }else{

            $sales_invoice['currentassets_salesinvoice'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $sales_invoice['date']['from'] = $start_date;
        $sales_invoice['date']['to'] = $end_date;
        $sales_invoice['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $sales_invoice;     
    }
    public function currentassets_salesreturn_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('sales_return si');
            $builder->select('si.id,si.return_date as date,ac.id as account_id,si.net_amount as taxable, ac.name as party_name');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('DATE(si.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(si.return_date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $sales_return['currentassets_salesreturn'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_return si');
            $builder->select('si.id,si.return_date as date,ac.id as account_id,si.net_amount as taxable, ac.name as party_name');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('DATE(si.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(si.return_date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $sales_return['currentassets_salesreturn'] = $query->getResultArray();

        }else{

            $sales_return['currentassets_salesreturn'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $sales_return['date']['from'] = $start_date;
        $sales_return['date']['to'] = $end_date;
        $sales_return['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $sales_return;     
    }
    public function currentassets_gnrl_sale_voucher_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('sales_ACinvoice pg');
            $builder->select('pg.id,pg.invoice_date as date,ac.id as account_id,ac.name as party_name,pg.net_amount as taxable');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.v_type' => "general"));
            $builder->where(array('pg.party_account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_invoice['currentassets_salesinvoice'] = $query->getResultArray();
        

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';
            
            $builder = $db->table('sales_ACinvoice pg');
            $builder->select('pg.id,pg.invoice_date as date,ac.id as account_id,ac.name as party_name,pg.net_amount as taxable');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.v_type' => "general"));
            $builder->where(array('pg.party_account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_invoice['currentassets_salesinvoice'] = $query->getResultArray();
            

        }else{

            $sales_invoice['currentassets_salesinvoice'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $sales_invoice['date']['from'] = $start_date;
        $sales_invoice['date']['to'] = $end_date;
        $sales_invoice['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $sales_invoice;     
    }
    public function currentassets_gnrl_sale_rtn_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('sales_ACinvoice pg');
            $builder->select('pg.id,pg.invoice_date as date,ac.id as account_id,ac.name as party_name,pg.net_amount as taxable');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.v_type' => "return"));
            $builder->where(array('pg.party_account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_invoice['currentassets_salesreturn'] = $query->getResultArray();
        

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';
            
            $builder = $db->table('sales_ACinvoice pg');
            $builder->select('pg.id,pg.invoice_date as date,ac.id as account_id,ac.name as party_name,pg.net_amount as taxable');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.v_type' => "return"));
            $builder->where(array('pg.party_account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $sales_invoice['currentassets_salesreturn'] = $query->getResultArray();
            

        }else{

            $sales_invoice['currentassets_salesreturn'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $sales_invoice['date']['from'] = $start_date;
        $sales_invoice['date']['to'] = $end_date;
        $sales_invoice['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $sales_invoice;     
    }
    public function currentassets_contra_voucher_Perwise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras ct');
            $builder->select('ct.id,ac.id as account_id,ac.name as party_name,ct.receipt_date as date,ct.amount as taxable,ct.narration');
            $builder->join('account ac', 'ac.id =ct.particular','left');
            $builder->where(array('ct.particular' => $get['id']));
            $builder->where(array('ct.payment_type' => 'contra'));
            $builder->where(array('ct.is_delete' => '0'));
            $builder->where(array('DATE(ct.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(ct.receipt_date)  <= ' => $end_date));
            $builder->groupBy('ct.id');
            $query = $builder->get();
            $contra_trans['currentassets_contratrans'] = $query->getResultArray(); 
            
        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras ct');
            $builder->select('ct.id,ac.id as account_id,ac.name as party_name,ct.receipt_date as date,ct.amount as taxable,ct.narration');
            $builder->join('account ac', 'ac.id =ct.particular','left');
            $builder->where(array('ct.particular' => $get['id']));
            $builder->where(array('ct.payment_type' => 'contra'));
            $builder->where(array('ct.is_delete' => '0'));
            $builder->where(array('DATE(ct.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(ct.receipt_date)  <= ' => $end_date));
            $builder->groupBy('ct.id');
            $query = $builder->get();
            $contra_trans['currentassets_contratrans'] = $query->getResultArray(); 


        }else{
            $contra_trans['currentassets_contratrans'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $contra_trans['date']['from'] = $start_date;
        $contra_trans['date']['to'] = $end_date;
        $contra_trans['ac_id'] = $get['id'];
         
        return $contra_trans;     
    }
    public function currentassets_contra_voucher_Acwise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras ct');
            $builder->select('ct.id,ac.id as account_id,ac.name as party_name,ct.receipt_date as date,ct.amount as taxable,ct.narration');
            $builder->join('account ac', 'ac.id =ct.account','left');
            $builder->where(array('ct.account' => $get['id']));
            //$builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('ct.is_delete' => '0'));
            $builder->where(array('ct.payment_type' => 'contra'));
            $builder->where(array('DATE(ct.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(ct.receipt_date)  <= ' => $end_date));
            $builder->groupBy('ct.id');
            $query = $builder->get();
            $contra_trans['currentassets_ac_contratrans'] = $query->getResultArray(); 
            //echo $db->getLastQuery();exit;    


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras ct');
            $builder->select('ct.id,ac.id as account_id,ac.name as party_name,ct.receipt_date as date,ct.amount as taxable,ct.narration');
            $builder->join('account ac', 'ac.id =ct.account','left');
            $builder->where(array('ct.account' => $get['id']));
            //$builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('ct.is_delete' => '0'));
            $builder->where(array('ct.payment_type' => 'contra'));
            $builder->where(array('DATE(ct.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(ct.receipt_date)  <= ' => $end_date));
            $builder->groupBy('ct.id');
            $query = $builder->get();
            $contra_trans['currentassets_ac_contratrans'] = $query->getResultArray(); 

        }else{
            $contra_trans['currentassets_ac_contratrans'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $contra_trans['date']['from'] = $start_date;
        $contra_trans['date']['to'] = $end_date;
        $contra_trans['ac_id'] = $get['id'];
         //echo '<pre>';print_r($contra_trans);exit;
        return $contra_trans;     
    }
    public function fixedassets_bankcash_voucher_Perwise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income['fixedassets_banktrans'] = $query->getResultArray();     


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as total,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income['fixedassets_banktrans'] = $query->getResultArray();      

        }else{
            $bank_income['fixedassets_banktrans'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $bank_income['date']['from'] = $start_date;
        $bank_income['date']['to'] = $end_date;
        $bank_income['ac_id'] = $get['id'];
        // echo '<pre>';print_r($bank_income);exit;
        return $bank_income;     
    }
    public function fixedassets_jv_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_income['fixedassets_jv'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_income['fixedassets_jv'] = $query->getResultArray();

        }else{

            $jv_income['fixedassets_jv'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $jv_income['date']['from'] = $start_date;
        $jv_income['date']['to'] = $end_date;
        $jv_income['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $jv_income;     
    }
    public function generalSales_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.sub_total,pp.added_amt');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            // $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            // $builder->groupBy('pp.id');
            $query = $builder->get();
            $pg_income['sales'] = $query->getResultArray();
            // echo $db->getLastQuery();exit;


        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.sub_total,pp.added_amt');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            // $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
            // $builder->groupBy('pp.id');
            $query = $builder->get();
            $pg_income['sales'] = $query->getResultArray();
            // echo $db->getLastQuery();exit;
            // echo '<pre>';print_r($pg_income);exit;
        }else{
            $pg_income['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
     //echo '<pre>';print_r($pg_income);exit;
        $result['sales'] = array();
        $total = 0;
        if(!empty($pg_income['sales'])){
            foreach ($pg_income['sales'] as $row) {
       
                $total = $row['sub_total'] + $row['added_amt'];
                $row['taxable'] =  $total;
                $result['sales'][] = $row; 
            }
        }

        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function generalPurchase_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('purchase_particu pp');
            $builder->select('acc.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->join('account acc', 'acc.id = pg.party_account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');


            $builder->groupBy('pg.id');
            $query = $builder->get();
            $pg_expence['purchase'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0'));
            $builder->where(array('pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->groupBy('pg.id');
            $query = $builder->get();
            $pg_expence['purchase'] = $query->getResultArray();

        }else{
            $pg_expence['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result['purchase'] = array();
        $total = 0;
        if(!empty($pg_expence['purchase'])){
            foreach ($pg_expence['purchase'] as $row) {
       
                $after_disc=0;
                
                if($row['disc_type'] == 'Fixed'){
                    $row['pg_amount'] = (float)$row['pg_amount'] -  (float)$row['discount'];
                    $after_disc =  $row['pg_amount'];
                }else{
                    $row['pg_amount'] = ((float)$row['pg_amount'] * ((float)$row['discount'] / 100));
                    $after_disc =  $row['pg_amount'];
                }
                
                if($row['amty_type'] == 'Fixed'){
                    $row['pg_amount'] = (float)$row['pg_amount'] + (float)$row['amty']; 
                }else{
                    $row['pg_amount'] = (float)$row['pg_amount'] + ((float)$after_disc * ((float)$row['amty'] / 100));
                }
        
               // $total += $row['pg_amount'];
                if($row['pg_type'] == 'general'){
                    $total += (float)$row['pg_amount'];
                }else{
                    $total -= (float)$row['pg_amount'];
                } 
                $row['taxable'] = $total;
                $result['purchase'][] = $row; 
            }
        }

        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function balancesheet_xls_export_data($post)
    {
        $gmodel = new GeneralModel;

        $gl_capital = $gmodel->get_data_table('gl_group', array('name' => 'Capital'), 'id,name');
        $gl_loan = $gmodel->get_data_table('gl_group', array('name' => 'Loans'), 'id,name');
        $gl_lib = $gmodel->get_data_table('gl_group', array('name' => 'Current Liabilities'), 'id,name');
        $gl_fixedassets = $gmodel->get_data_table('gl_group', array('name' => 'Fixed Assets'), 'id,name');
        $gl_currentassets = $gmodel->get_data_table('gl_group', array('name' => 'Current Assets'), 'id,name');
        $gl_otherassets = $gmodel->get_data_table('gl_group', array('name' => 'Other Assets'), 'id,name');

        $balancesheet  = balancesheet_detail($post['from'], $post['to']);
        $pl  = pl_tot_data($post['from'], $post['to']);

        $Opening_bal = Opening_bal('Opening Stock');
        $manualy_closing_bal =$this->tmodel->get_manualy_stock($post['from'],$post['to']);
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


        $sundry_debtors = (@$sale_purchase['sale_total_rate'] - @$sale_purchase['Saleret_total_rate'] + @$currentassets['Sundry Debtors']['total']);
        $sundry_creditor = (@$sale_purchase['pur_total_rate'] - @$sale_purchase['Purret_total_rate'] + @$current_lib['Sundry Creditors']['total']);
        // echo '<pre>';print_r($currentassets);exit;

        $init_total = 0;

        $capital_total = subGrp_total($capital, $init_total);
        $loan_total = subGrp_total($loan, $init_total);
        $current_lib_total = subGrp_total($current_lib, $init_total);
        $fixedassets_total = subGrp_total($fixedassets, $init_total);
        $currentassets_total = subGrp_total($currentassets, $init_total);
        $otherassets_total = subGrp_total($otherassets, $init_total);

        $bl['capital'] = $capital;
        $bl['capital_total'] = $capital_total;

        $bl['loan'] = $loan;
        $bl['loan_total'] = $loan_total;

        $bl['current_lib'] = $current_lib;
        $bl['current_lib_total'] = $current_lib_total;

        $bl['fixedassets'] = $fixedassets;
        $bl['fixedassets_total'] = $fixedassets_total;

        $bl['currentassets'] = $currentassets;
        $bl['currentassets_total'] = $currentassets_total;

        $bl['otherassets'] = $otherassets;
        $bl['otherassets_total'] = $otherassets_total;

        // $data['pl'] = $pl;
        $bl_sheet = $balancesheet;
        $trading['sundry_debtors'] = $sundry_debtors;
        $trading['sundry_creditor'] = $sundry_creditor;
        $closing_bal = @$closing_data['closing_bal']; 
        $closing_stock = @$closing_data['closing_stock'];
        $manualy_closing_bal = @$manualy_closing_bal;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A2:C2');
        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getFont()->setSize(20);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', session('address'));
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A3:F3');
        $spreadsheet->getActiveSheet()->getStyle('A3:F3')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $date_from = date_create($post['from']);
        $new_date_from = date_format($date_from, "d-M-y");
        $date_to = date_create($post['to']);
        $new_date_to = date_format($date_to, "d-M-y");

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'Balancesheet Report');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A5:C5');
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(20);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6', $new_date_from);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B6', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C6', $new_date_to);
        $spreadsheet->getActiveSheet()->getStyle('A6:F6')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B7', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C7', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D7', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E7', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F7', '');
        $spreadsheet->getActiveSheet()->getStyle('A7:F7')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A7:F7')->getFont()->setSize(15);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A8', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B8', ' at ' . $new_date_from);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C8', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D8', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E8', ' at ' . $new_date_to);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F8', '');
        $spreadsheet->getActiveSheet()->getStyle('A8:F8')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->getActiveSheet()->getStyle('C4:C8')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
       
        if(session('is_stock') == 1 ){
            $closing_bal = @$manualy_closing_bal;
        }else{
            $closing_bal  = @$closing_bal;
        }
        $total = 0;
        $i = 9;
        foreach ($bl['capital'] as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$value['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$bl['capital_total']);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
            $i++;
            if (!empty($value['account'])) {
                foreach (@$value['account'] as $ac_key => $ac_value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$ac_key);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($ac_value['total'], 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                        ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                    $i++;
                }
            }
            if (!empty($value['sub_categories'])) {
                foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                    $total = 0;
                    $arr[$sub_key] = $sub_value;
                    $total = subGrp_total($arr, 0);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$sub_value['name']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($total, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                        ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                    unset($arr);
                    $i++;
                }
            }
        }

        $total = 0;
        foreach ($bl['loan'] as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$value['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$bl['capital_total']);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
            $i++;
            if (!empty($value['account'])) {
                foreach (@$value['account'] as $ac_key => $ac_value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$ac_key);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($ac_value['total'], 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                        ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                    $i++;
                }
            }
            if (!empty($value['sub_categories'])) {
                foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                    $total = 0;
                    $arr[$sub_key] = $sub_value;
                    $total = subGrp_total($arr, 0);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$sub_value['name']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($total, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                        ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                    $i++;
                    unset($arr);
                }
            }
        }
        $total = 0;
        foreach ($bl['current_lib'] as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$value['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format(@$bl['current_lib_total'], 2));
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
            $i++;
            if (!empty($value['account'])) {
                foreach (@$value['account'] as $ac_key => $ac_value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$ac_key);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($ac_value['total'], 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                    $i++;
                }
            }
            if (!empty($value['sub_categories'])) {
                foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                    $total = 0;
                    $arr[$sub_key] = $sub_value;
                    $total = subGrp_total($arr, 0);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$sub_value['name']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($total, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                    $i++;
                    unset($arr);
                }
            }
        }
        $j = 9;
        $total = 0;
        foreach ($bl['fixedassets'] as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$value['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format(@$bl['fixedassets_total'], 2));
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->getFormatCode();
            $j++;
            if (!empty($value['account'])) {
                foreach (@$value['account'] as $ac_key => $ac_value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$ac_key);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($ac_value['total'], 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                    $j++;
                }
            }
            if (!empty($value['sub_categories'])) {
                foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                    $total = 0;
                    $arr[$sub_key] = $sub_value;
                    $total = subGrp_total($arr, 0);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$sub_value['name']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($total, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                    $j++;
                    unset($arr);
                }
            }
        }
        $total = 0;
        foreach ($bl['currentassets'] as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$value['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format(@$bl['currentassets_total'], 2));
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->getFormatCode();
            $j++;
            if (!empty($value['account'])) {
                foreach (@$value['account'] as $ac_key => $ac_value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$ac_key);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($ac_value['total'], 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                    $j++;
                }
            }
            if (!empty($value['sub_categories'])) {
                foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                    $total = 0;
                    $arr[$sub_key] = $sub_value;
                    $total = subGrp_total($arr, 0);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$sub_value['name']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($total, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                    $j++;
                    unset($arr);
                }
            }
        }
        $total = 0;
        foreach ($bl['otherassets'] as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$value['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format(@$bl['otherassets_total'], 2));
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->getFormatCode();
            $j++;
            if (!empty($value['account'])) {
                foreach (@$value['account'] as $ac_key => $ac_value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$ac_key);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($ac_value['total'], 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                    $j++;
                }
            }
            if (!empty($value['sub_categories'])) {
                foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                    $total = 0;
                    $arr[$sub_key] = $sub_value;
                    $total = subGrp_total($arr, 0);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$sub_value['name']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($total, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                    $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                    $j++;
                    unset($arr);
                }
            }
        }



        $spreadsheet->getActiveSheet()->setTitle('Balancesheet report');
        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    //use of get profit loss
    function pl_tot_data_bl_new($start_date = '', $end_date = '')
    {
        $init_total =0;
    
        $gl_id = $this->gmodel->get_data_table('gl_group',array('name'=>'Trading Expenses','is_delete'=>0),'id,name');
        $gl_inc_id = $this->gmodel->get_data_table('gl_group',array('name'=>'Trading Income','is_delete'=>0),'id,name');
        $pl_exp_id = $this->gmodel->get_data_table('gl_group',array('name'=>'P & L Expenses','is_delete'=>0),'id,name');
        $pl_inc_id = $this->gmodel->get_data_table('gl_group',array('name'=>'P & L Incomes','is_delete'=>0),'id,name');
        $gl_opening_id = $this->gmodel->get_data_table('gl_group',array('name'=>'Opening Stock'),'id,name');
       
        if(!empty($start_date)){

            //$Opening_bal = Opening_bal('Opening Stock');
             
            $manualy_closing_bal = $this->tmodel->get_manualy_stock($start_date,$end_date);
            $closing_data = $this->tmodel->get_closing_detail($start_date,$end_date);

            $sale_pur = sale_purchase_vouhcer($start_date,$end_date); 

            $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$start_date,$end_date);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$start_date,$end_date);
            
            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$start_date,$end_date);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$start_date,$end_date);
            
            $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id'],$start_date,$end_date);
            $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
            $exp_pl[$pl_exp_id['id']]['sub_categories']  = get_PL_expense_sub_grp_data($pl_exp_id['id'],$start_date,$end_date);

            
            $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id'],$start_date,$end_date);
            $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
            $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id'],$start_date,$end_date);
          
        }
        else
        {
            //$Opening_bal = Opening_bal('Opening Stock');
            $manualy_closing_bal = $this->tmodel->get_manualy_stock();
            $closing_data = $this->tmodel->get_closing_detail();
            $sale_pur = sale_purchase_vouhcer($start_date,$end_date); 
            $exp[$gl_id['id']] = trading_expense_data($gl_id['id']);
            $exp[$gl_id['id']]['name'] = $gl_id['name'];
            $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id']);
    
            $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id']);
            $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
            $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id']);

            $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id']);
            $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
            $exp_pl[$pl_exp_id['id']]['sub_categories'] = get_PL_expense_sub_grp_data($pl_exp_id['id']);
            
            
            $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id']);
            $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
            $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id']);
        }
       
       // echo '<pre>';Print_r($sale_pur);exit;
        $all_purchase = $sale_pur['pur_total_rate'] ;
        $all_purchase_return = $sale_pur['Purret_total_rate'] ;
        
        $all_sale = $sale_pur['sale_total_rate'] ;
        $all_sale_return = $sale_pur['saleret_total_rate'] ;

        $opening_stock[$gl_opening_id['id']] = opening_stock_data($gl_opening_id['id']);
        $opening_stock[$gl_opening_id['id']]['name'] = $gl_opening_id['name'];
        $opening_stock[$gl_opening_id['id']]['sub_categories'] = get_opening_stock_sub_grp_data($gl_opening_id['id']);
             

        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);
        $exp_pl_total = subGrp_total($exp_pl,$init_total);
        $inc_pl_total = subGrp_total($inc_pl,$init_total);
        $opening_total = subGrp_total($opening_stock,$init_total);

        if(session('is_stock') == 1 ){
            $closing_stock = @$manualy_closing_bal;
        }else{
            $closing_stock  = @$closing_data['closing_bal'] + $opening_total;
        }

        $income_total = (float)$all_sale - (float)$all_sale_return + $closing_stock + $inc_total;
        $expens_total = $opening_total + (float)$all_purchase  - (float)$all_purchase_return + $exp_total;

        if(($expens_total -  $income_total) < 0 ){
            $gross_profit = ($expens_total -  $income_total) * -1;
        }else{
            $gross_loss = $expens_total -  $income_total;
        }
        $net_loss = 0;
        $net_profit = 0;
        if((@$gross_loss + $exp_pl_total)   >  ($inc_pl_total + @$gross_profit)){
            $net_loss = (@$gross_loss + $exp_pl_total) - ($inc_pl_total + @$gross_profit);
        }else{
            $net_profit =($inc_pl_total + @$gross_profit)  - (@$gross_loss + $exp_pl_total);
        }
       
        $data['net_loss'] = $net_loss;
        $data['net_profit'] = $net_profit;

        //echo '<pre>';print_r($data);exit;
        return $data;
    }
    public function otherassets_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income = $query->getResultArray();     
            

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';
            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_income = $query->getResultArray();  

        }else{
            $bank_income = array();
            $start_date = '';
            $end_date = '';
        }   

        $bank_income['date']['from'] = $start_date;
        $bank_income['date']['to'] = $end_date;
        $bank_income['ac_id'] = $get['id'];

        // echo '<pre>';print_r($bank_income);exit;

        return $bank_income;     
    }
    
   
}
?>
