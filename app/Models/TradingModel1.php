<?php

namespace App\Models;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TradingModel extends Model
{
    public function get_closing_detail($start_date ='', $end_date= ''){
        
        if($start_date == ''){
            if(date('m') < '03'){
                $year = date('Y')-1;
                $start_date = $year.'-04-01';
            }else{
                $year = date('Y');
                $start_date = $year.'-04-01';
            }
        }

        if($end_date == '' ){
            if(date('m') < '03'){
                $year = date('Y');
            }else{
                $year = date('Y')+1;
            }
            $end_date =$year.'-03-31'; 
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('item'); 
        $builder->select('*');
        $builder->where(array('is_delete'=>0));
        $query = $builder->get();
        $result = $query->getResultArray();
        $diff_total = array();

        foreach($result as $row){
            
            $sale = SaleItemSTock($row['id'],$start_date,$end_date);
            $purchase = PurchaseItemSTock($row['id'],$start_date,$end_date);
            // // $sale_pur = sale_purchase_itm_total($start_date,$end_date);
            if($purchase['itm']['total_qty'] != 0 ){
                $diff_total[] =   (@$purchase['itm']['total_rate'] / @$purchase['itm']['total_qty']) * (@$purchase['itm']['total_qty'] - @$sale['itm']['total_qty']);  
                $diff_qty[]  = @$purchase['itm']['total_qty'] - @$sale['itm']['total_qty']; 
            }else{
                $diff_total[] =   1 * (@$purchase['itm']['total_rate'] - @$sale['itm']['total_rate']);   
                $diff_qty[]  = 0; 
            }
        }
        $final_total  =  array_sum($diff_total);
        $final_qty  =  array_sum($diff_qty);
        if(!isset($final_total) || empty($final_total)){
            $final_total = 0;
        }
        if(!isset($final_qty) || empty($final_qty)){
            $final_qty = 0;
        }
        $data['closing_bal'] = $final_total;
        $data['closing_stock'] = $final_qty;

        return $data;
        
    }
    public function get_manualy_stock($start_date ='', $end_date= ''){
        
        if($start_date == ''){
            if(date('m') < '03'){
                $year = date('Y')-1;
                $start_date = $year.'-04-01';
            }else{
                $year = date('Y');
                $start_date = $year.'-04-01';
            }
        }

        if($end_date == '' ){
            if(date('m') < '03'){
                $year = date('Y');
            }else{
                $year = date('Y')+1;
            }
            $end_date =$year.'-03-31'; 
        }
       
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('oc_stock'); 
        $builder->select('*');
        $builder->where('date <=',$end_date);
        $builder->where('date <',date('Y-m-d'));
        $builder->where(array('is_delete'=>0));
        $builder->orderBy('date','desc');
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRowArray();
        @$closing =  @$result['closing'];
        
        return @$closing;
        // echo '<pre>';print_r($result);exit;
        
    }
    // *************start sales purchase voucher account*******************//
    // public function salesItem_voucher_wise_data($get){
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource')); 
    //     $results_per_page = 15;
    //     $page = $get['page'];
    //     $page_first_result = ($page - 1) * $results_per_page;
    //     $new_limit = ($page - 1) * 15;

    //     if(!empty($get['year'])){

    //         $start = strtotime("{$get['year']}-{$get['month']}-01");
    //         $end = strtotime('-1 second', strtotime('+1 month', $start));
             
    //         $start_date = date('Y-m-d',$start);
    //         $end_date = date('Y-m-d',$end);
             
    //         $builder = $db->table('sales_invoice p');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $number_of_result = $builder->countAllResults();
    //         $number_of_page = ceil($number_of_result / $results_per_page);
    //         $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $builder->limit($results_per_page, $page_first_result);
    //         $query = $builder->get();
    //         $sales['sales'] = $query->getResultArray();

    //         $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
    //         FROM `sales_invoice` `p`
    //         WHERE `p`.`is_delete` = 0
    //         AND `p`.`is_cancle` = 0
    //         AND DATE(p.invoice_date) >= '".$start_date."'
    //         AND DATE(p.invoice_date) <= '".$end_date."'
    //          LIMIT ".$new_limit.") as t";
    //         $total_amount = $db->query($query)->getRowArray();


    //     }else if(!empty(@$get['from'])){

    //         $start_date = @$get['from']  ? db_date($get['from']) : '';
    //         $end_date = @$get['to'] ? db_date($get['to']) : '';

    //         $builder = $db->table('sales_invoice p');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $number_of_result = $builder->countAllResults();
    //         $number_of_page = ceil($number_of_result / $results_per_page);
    //         $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $builder->limit($results_per_page, $page_first_result);
    //         $query = $builder->get();
    //         $sales['sales'] = $query->getResultArray();

    //         $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
    //         FROM `sales_invoice` `p`
    //         WHERE `p`.`is_delete` = 0
    //         AND `p`.`is_cancle` = 0
    //         AND DATE(p.invoice_date) >= '".$start_date."'
    //         AND DATE(p.invoice_date) <= '".$end_date."'
    //          LIMIT ".$new_limit.") as t";
    //         $total_amount = $db->query($query)->getRowArray();

    //     }else{
    //         $sales['sales'] = array();
    //         $start_date = '';
    //         $end_date = '';
    //     }   
    //     $sales['page'] = $page;
    //     $sales['number_of_page'] = $number_of_page;
    //     $sales['month'] = @$get['month'];
    //     $sales['year'] = @$get['year'];
    //     $sales['date']['from'] = $start_date;
    //     $sales['date']['to'] = $end_date;
    //     if($page == 1)
    //     {
    //         $sales['opening_balance'] =0;
    //     }
    //     else
    //     {
    //         $sales['opening_balance'] = $total_amount['total_taxable'];
    //     }
    //     //echo '<pre>';Print_r($sales);exit;
        
    //     return $sales;     
    // }
    public function salesItem_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             

            $builder = $db->table('sales_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.custom_inv_no,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.custom_inv_no,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $builder = $db->table('sales_invoice p');
        $builder->select('SUM(p.taxable) as sales_total');
        $builder->where(array('p.is_delete'=>0,'p.is_cancle'=>0));
        $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales['total'] = $query->getRowArray();

        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        //echo '<pre>';print_r($sales);exit;
        return $sales;     
    }
    // public function salesReturnItem_voucher_wise_data($get){
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource')); 
    //     $results_per_page = 15;
    //     $page = $get['page'];
    //     $page_first_result = ($page - 1) * $results_per_page;
    //     $new_limit = ($page - 1) * 15;

    //     if(!empty($get['year'])){

    //         $start = strtotime("{$get['year']}-{$get['month']}-01");
    //         $end = strtotime('-1 second', strtotime('+1 month', $start));
             
    //         $start_date = date('Y-m-d',$start);
    //         $end_date = date('Y-m-d',$end);
             
    //         $builder = $db->table('sales_return p');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
    //         $number_of_result = $builder->countAllResults();
    //         $number_of_page = ceil($number_of_result / $results_per_page);
    //         $builder->select('p.return_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.return_date  as date');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
    //         $builder->limit($results_per_page, $page_first_result);
    //         $query = $builder->get();
    //         $sales['sales'] = $query->getResultArray();

    //         $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
    //         FROM `sales_return` `p`
    //         WHERE `p`.`is_delete` = 0
    //         AND `p`.`is_cancle` = 0
    //         AND DATE(p.return_date) >= '".$start_date."'
    //         AND DATE(p.return_date) <= '".$end_date."'
    //          LIMIT ".$new_limit.") as t";
    //         $total_amount = $db->query($query)->getRowArray();


    //     }else if(!empty(@$get['from'])){

    //         $start_date = @$get['from']  ? db_date($get['from']) : '';
    //         $end_date = @$get['to'] ? db_date($get['to']) : '';

    //         $builder = $db->table('sales_return p');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
    //         $number_of_result = $builder->countAllResults();
    //         $number_of_page = ceil($number_of_result / $results_per_page);
    //         $builder->select('p.return_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.return_date  as date');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
    //         $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
    //         $builder->limit($results_per_page, $page_first_result);
    //         $query = $builder->get();
    //         $sales['sales'] = $query->getResultArray();

    //         $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
    //         FROM `sales_return` `p`
    //         WHERE `p`.`is_delete` = 0
    //         AND `p`.`is_cancle` = 0
    //         AND DATE(p.return_date) >= '".$start_date."'
    //         AND DATE(p.return_date) <= '".$end_date."'
    //          LIMIT ".$new_limit.") as t";
    //         $total_amount = $db->query($query)->getRowArray();

    //     }else{
    //         $sales['sales'] = array();
    //         $start_date = '';
    //         $end_date = '';
    //     }   
    //     $sales['page'] = $page;
    //     $sales['number_of_page'] = $number_of_page;
    //     $sales['month'] = @$get['month'];
    //     $sales['year'] = @$get['year'];
    //     $sales['date']['from'] = $start_date;
    //     $sales['date']['to'] = $end_date;
    //     if($page == 1)
    //     {
    //         $sales['opening_balance'] =0;
    //     }
    //     else
    //     {
    //         $sales['opening_balance'] = $total_amount['total_taxable'];
    //     }
    //     return $sales;     
    // }
    public function salesReturnItem_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             

            $builder = $db->table('sales_return p');
            $builder->select('p.return_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.return_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_return p');
            $builder->select('p.return_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.return_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $builder = $db->table('sales_return p');
        $builder->select('SUM(p.taxable) as sales_total');
        $builder->where(array('p.is_delete'=>0,'p.is_cancle'=>0));
        $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
        $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales['total'] = $query->getRowArray();

        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        //echo '<pre>';print_r($sales);exit;
        return $sales;     
    }
    public function purchaseItem_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $results_per_page = 15;
        $page = $get['page'];
        $page_first_result = ($page - 1) * $results_per_page;
        $new_limit = ($page - 1) * 15;

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('purchase_invoice p');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

            $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
            FROM `purchase_invoice` `p`
            WHERE `p`.`is_delete` = 0
            AND `p`.`is_cancle` = 0
            AND DATE(p.invoice_date) >= '".$start_date."'
            AND DATE(p.invoice_date) <= '".$end_date."'
             LIMIT ".$new_limit.") as t";
            $total_amount = $db->query($query)->getRowArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_invoice p');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

            $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
            FROM `purchase_invoice` `p`
            WHERE `p`.`is_delete` = 0
            AND `p`.`is_cancle` = 0
            AND DATE(p.invoice_date) >= '".$start_date."'
            AND DATE(p.invoice_date) <= '".$end_date."'
             LIMIT ".$new_limit.") as t";
            $total_amount = $db->query($query)->getRowArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['page'] = @$page;
        $purchase['number_of_page'] = @$number_of_page;
        $purchase['month'] = @$get['month'];
        $purchase['year'] = @$get['year'];
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        if($page == 1)
        {
            $purchase['opening_balance'] =0;
        }
        else
        {
            $purchase['opening_balance'] = $total_amount['total_taxable'];
        }
        return $purchase;     
    }
    public function purchaseReturnItem_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $results_per_page = 15;
        $page = $get['page'];
        $page_first_result = ($page - 1) * $results_per_page;
        $new_limit = ($page - 1) * 15;

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('purchase_return p');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('p.return_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.return_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

            $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
            FROM `purchase_return` `p`
            WHERE `p`.`is_delete` = 0
            AND `p`.`is_cancle` = 0
            AND DATE(p.return_date) >= '".$start_date."'
            AND DATE(p.return_date) <= '".$end_date."'
             LIMIT ".$new_limit.") as t";
            $total_amount = $db->query($query)->getRowArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_return p');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('p.return_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.return_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

            $query = "SELECT SUM(taxable) as total_taxable  FROM (SELECT p.taxable
            FROM `purchase_return` `p`
            WHERE `p`.`is_delete` = 0
            AND `p`.`is_cancle` = 0
            AND DATE(p.return_date) >= '".$start_date."'
            AND DATE(p.return_date) <= '".$end_date."'
             LIMIT ".$new_limit.") as t";
            $total_amount = $db->query($query)->getRowArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['page'] = @$page;
        $purchase['number_of_page'] = @$number_of_page;
        $purchase['month'] = @$get['month'];
        $purchase['year'] = @$get['year'];
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        if($page == 1)
        {
            $purchase['opening_balance'] =0;
        }
        else
        {
            $purchase['opening_balance'] = $total_amount['total_taxable'];
        }
        return $purchase;     
    }
    // *************trading voucher data*******************//
    public function generalSales_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $results_per_page = 15;
        $page = $get['page'];
        $page_first_result = ($page - 1) * $results_per_page;
        $new_limit = ($page - 1) * 15;

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('sales_ACparticu pp');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0','pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pp.discount,pp.added_amt,pp.sub_total');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0','pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_income['sales'] = $query->getResultArray();
            // echo $db->getLastQuery();exit;


        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_ACparticu pp');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0','pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pp.discount,pp.added_amt,pp.sub_total');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0','pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_income['sales'] = $query->getResultArray();

        }else{
            $pg_income['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        // echo '<pre>';print_r($pg_income);exit;
        $result['sales'] = array();
        $total = 0;
        if(!empty($pg_income['sales'])){
            foreach ($pg_income['sales'] as $row) {
    
                $row['pg_amount'] = (float)$row['sub_total'] + (float)$row['added_amt']; 
                $row['taxable'] = $row['pg_amount'];
                $result['sales'][] = $row; 
            }
        }

        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];
        $result['page'] = $page;
        $result['number_of_page'] = @$number_of_page;
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function generalPurchase_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $results_per_page = 15;
        $page = $get['page'];
        $page_first_result = ($page - 1) * $results_per_page;
        $new_limit = ($page - 1) * 15;
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('purchase_particu pp');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->join('account acc', 'acc.id = pg.party_account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('acc.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pp.sub_total,pp.added_amt');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->join('account acc', 'acc.id = pg.party_account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $pg_expence['purchase'] = $query->getResultArray();
           

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_particu pp');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->join('account acc', 'acc.id = pg.party_account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('acc.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no ,pg.id as id ,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pp.sub_total,pp.added_amt');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->join('account acc', 'acc.id = pg.party_account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0'));
            $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->groupBy('pg.id');
            $builder->limit($results_per_page, $page_first_result);
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
    
                $row['pg_amount'] = (float)$row['sub_total'] + (float)$row['added_amt']; 
              
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
        $result['page'] = $page;
        $result['number_of_page'] = @$number_of_page;
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];
      

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    //************************income and pl used function ***************************//
    public function bank_cash_voucher_wise_data($get){

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
            $bank_income['sales'] = $query->getResultArray();     


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
            $bank_income['sales'] = $query->getResultArray();      

        }else{
            $bank_income['sales'] = array();
            $start_date = '';
            $end_date = '';
        } 
        $credit = 0;
        $debit = 0;
        foreach($bank_income['sales'] as $row)  
        {
            if($row['mode']=="Receipt"){ 
                $credit += $row['taxable'];
            }else{
                $debit += $row['taxable'];
            }
        }
        $bank_income['debit'] = $debit;
        $bank_income['credit'] = $credit;
        $bank_income['date']['from'] = $start_date;
        $bank_income['date']['to'] = $end_date;
        $bank_income['ac_id'] = $get['id'];

        $gmodel = new GeneralModel();
        $account  = $gmodel->get_data_table("account",array('id'=>$get['id']),'name');
        $bank_income['ac_name'] = @$account['name'];

        return $bank_income;     
    }
    public function jv_voucher_wise_data($get){

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
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('jm.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_income['sales'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('jv_particular jv');
            $builder->select('jm.id,jv.jv_id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->join('jv_main jm', 'jm.id =jv.jv_id');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('jm.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_income['sales'] = $query->getResultArray();

        }else{

            $jv_income['sales'] = array();
            $start_date = '';
            $end_date = '';
        }  
        $credit = 0; 
        $debit = 0; 
        foreach($jv_income['sales'] as $row)
        {
            if($row['dr_cr']=="cr"){ 
                $credit += $row['taxable'];
            }else{
                $debit += $row['taxable'];
            }
        }

        $jv_income['credit'] = $credit;
        $jv_income['debit'] = $debit;
        $jv_income['date']['from'] = $start_date;
        $jv_income['date']['to'] = $end_date;
        $jv_income['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $jv_income;     
    }
    public function purchase_bank_cash_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $results_per_page = 15;
        $page = $get['page'];
        $page_first_result = ($page - 1) * $results_per_page;
        $new_limit = ($page - 1) * 15;
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('bank_tras bt');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->limit($results_per_page, $page_first_result);
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_expence['purchase'] = $query->getResultArray();     


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras bt');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as taxable,bt.mode,bt.payment_type');
            $builder->join('account ac', 'ac.id =bt.particular');
            $builder->where(array('bt.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('bt.is_delete' => '0'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $builder->limit($results_per_page, $page_first_result);
            $builder->groupBy('bt.id');
            $query = $builder->get();
            $bank_expence['purchase'] = $query->getResultArray();      

        }else{
            $bank_expence['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $bank_expence['date']['from'] = $start_date;
        $bank_expence['date']['to'] = $end_date;
        $bank_expence['ac_id'] = $get['id'];
        $bank_expence['page'] = $page;
        $bank_expence['number_of_page'] = @$number_of_page;
        $bank_expence['month'] = @$get['month'];
        $bank_expence['year'] = @$get['year'];
        // echo '<pre>';print_r($bank_income);exit;
        return $bank_expence;     
    }
    public function purchase_jv_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $results_per_page = 15;
        $page = $get['page'];
        $page_first_result = ($page - 1) * $results_per_page;
        $new_limit = ($page - 1) * 15;
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('jv_particular jv');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('jv.jv_id as id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $jv_expence['purchase'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('jv_particular jv');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $number_of_result = $builder->countAllResults();
            $number_of_page = ceil($number_of_result / $results_per_page);
            $builder->select('jv.jv_id as id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $builder->limit($results_per_page, $page_first_result);
            $query = $builder->get();
            $jv_expence['purchase'] = $query->getResultArray();

        }else{

            $jv_expence['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $jv_expence['date']['from'] = $start_date;
        $jv_expence['date']['to'] = $end_date;
        $jv_expence['ac_id'] = $get['id'];
        $jv_expence['page'] = $page;
        $jv_expence['number_of_page'] = @$number_of_page;
        $jv_expence['month'] = @$get['month'];
        $jv_expence['year'] = @$get['year'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $jv_expence;     
    }
    public function get_closing_data($get){
        $dt_search = array(
            "id",
            "closing",
            "date",
            
        );
        
        $dt_col = array(
            "id",
            "closing",
            "date",
        );
        
        $filter = $get['filter_data'];
        $tablename = "oc_stock";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array();
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            
    
            $btnedit = '<a  data-toggle="modal" data-target="#fm_model" href="'. url('Trading/add_closing/') . $row['id'] . '"   data-title="Edit Closing Date: ' . $row['date'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Cloasing Date: ' . $row['date'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            
            $btn = $btnedit . $btndelete;
    
        
            $DataRow[] = $row['id'];
            $DataRow[] = $row['closing'];
            $DataRow[] = $row['date'];
            $DataRow[] = $btn;
    
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    public function insert_edit_closing($post){
        
        for($i=0;$i<count($post['closing']);$i++){ 
            $msg = array();

            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('oc_stock');
            $builder->select('*');
            $builder->where(array("id" => @$post['id'][$i]));
            $builder->limit(1);
            $result = $builder->get();
            $result_array = $result->getRow();
           
            $pdata = array(
                'date' => $post['date'][$i],
                'closing' => $post['closing'][$i],            
            );
            
            if (!empty($result_array)) {
                $pdata['update_at'] = date('Y-m-d H:i:s');
                $pdata['update_by'] = session('uid');
                if (empty($msg)) {
                    
                    $builder->where(array("id" => $post['id'][$i]));
                    $result = $builder->Update($pdata);
                    
                    $builder = $db->table('oc_stock');
        
                    if ($result) {
                        $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    } else {
                        $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                    }
                }
            }
            
            else {
                
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $pdata['created_by'] = session('uid');
                
                if (empty($msg)) {
                    
                    $result = $builder->Insert($pdata);
                    
                    $id = $db->insertID();
                    if ($result) {
                        $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    } else {
                        $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                    }
                }
            }
        }
        return $msg;
    }
    public function get_OCstock_data($id){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('oc_stock'); 
        $builder->select('*');
        $builder->where(array('id'=>$id));
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRowArray();

        return $result; 
    }
     //update trupti 01-12-2022
     public function trading_xls_export_data($post)
     {
         $gmodel = new GeneralModel;
         $exp = array();
         $gl_id = $gmodel->get_data_table('gl_group', array('name' => 'Trading Expenses'), 'id,name');
         $gl_inc_id = $gmodel->get_data_table('gl_group', array('name' => 'Trading Income'), 'id,name');
         $init_total = 0;
         $sale_pur = sale_purchase_vouhcer($post['from'], $post['to']);
 
         $exp[$gl_id['id']] = trading_expense_data($gl_id['id'], $post['from'], $post['to']);
         $exp[$gl_id['id']]['name'] = $gl_id['name'];
         $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'], $post['from'], $post['to']);
 
         $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'], $post['from'], $post['to']);
         $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
         $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'], $post['from'], $post['to']);
 
         $init_total = 0;
 
         $closing_stock = $this->get_closing_stock($post['from'], $post['to']);
         $closing_bal = $this->get_closing_bal($post['from'], $post['to']);
         $Opening_bal = Opening_bal('Opening Stock');
 
         if (session('is_stock') == 1) {
             $closing_stock = @$closing_bal ? @$closing_bal : @$Opening_bal;
         } else {
             $closing_stock  = $closing_bal;
         }
 
         $all_purchase = @$sale_pur['pur_total_rate'];
         $all_purchase_return = @$sale_pur['Purret_total_rate'];
 
         $all_sale = @$sale_pur['sale_total_rate'];
         $all_sale_return = @$sale_pur['Saleret_total_rate'];
 
         $income_total = (float)$all_sale - (float)$all_sale_return + @$closing_stock + @$sale_pur['inc_total'];
         $expens_total = @$sale_pur['opening_bal'] + (float)$all_purchase  - (float)$all_purchase_return + @$sale_pur['exp_total'];
 
         if (($expens_total -  $income_total) < 0) {
             $gross_profit = ($expens_total -  $income_total) * -1;
 
             $expens_total += $gross_profit;
         } else {
             $gross_loss = $expens_total -  $income_total;
             $income_total += $gross_loss;
         }
         if ((@$all_sale - @$all_sale_return)  != 0) {
             $per_base = 100 / (@$all_sale - @$all_sale_return);
         } else {
             $per_base = 100 / 1;
         }
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
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'Trading Report');
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
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A9', 'Opening Stock');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B9', '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C9', number_format(@$sale_pur['opening_bal'], 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D9', 'Sales Accounts');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E9', '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F9', number_format($all_sale - $all_sale_return, 2));
         $spreadsheet->getActiveSheet()->getStyle('A9:C9')->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle('C9')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('C9')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('A9:F9')->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle('A9:F9')->getFont()->setSize(12);
         $spreadsheet->getActiveSheet()->getStyle('C9')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('C9')->getNumberFormat()->getFormatCode();
         $spreadsheet->getActiveSheet()->getStyle('F9')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('F9')->getNumberFormat()->getFormatCode();
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A10', 'Stock In Hand');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B10', number_format(@$sale_pur['opening_bal'], 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C10', '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D10', 'Sales Accounts');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E10', number_format($all_sale, 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F10', '');
         $spreadsheet->getActiveSheet()->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('B10')->getNumberFormat()->getFormatCode();
         $spreadsheet->getActiveSheet()->getStyle('E10')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('E10')->getNumberFormat()->getFormatCode();
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A11', 'Purchase Accounts');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B11', '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C11', number_format($all_purchase - $all_purchase_return, 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D11', 'Sales Return');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E11', '-' . number_format($all_sale_return, 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F11', '');
         $spreadsheet->getActiveSheet()->getStyle('C11')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('C11')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('A11:C11')->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle('A11:C11')->getFont()->setSize(12);
         $spreadsheet->getActiveSheet()->getStyle('C11')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('C11')->getNumberFormat()->getFormatCode();
         $spreadsheet->getActiveSheet()->getStyle('E11')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('E11')->getNumberFormat()->getFormatCode();
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A12', 'Purchase Account');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B12', number_format($all_purchase, 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C12', '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D12', 'Closing Stock');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E12', '');
         if (session('is_stock') == 1) {
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('F12', number_format(@$sale_pur['opening_bal'] + $closing_stock, 2));
         } else {
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('F12', number_format(@$sale_pur['opening_bal'], 2));
         }
         // $spreadsheet->getActiveSheet()->getStyle('C12')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         // $spreadsheet->getActiveSheet()->getStyle('C12')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('D12:F12')->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle('D12:F12')->getFont()->setSize(12);
         $spreadsheet->getActiveSheet()->getStyle('B12')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('B12')->getNumberFormat()->getFormatCode();
         $spreadsheet->getActiveSheet()->getStyle('F12')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('F12')->getNumberFormat()->getFormatCode();
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A13', 'Purchase Return');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B13', '-' . number_format($all_purchase_return, 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C13', '');
 
         $spreadsheet->getActiveSheet()->getStyle('C7:C13')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('B13')->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('B13')->getNumberFormat()->getFormatCode();
 
         $i = 14;
         if (isset($sale_pur['exp'])) {
 
             foreach ($sale_pur['exp'] as $key => $value) {
                 $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$value['name']);
                 $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
                 $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format(@$sale_pur['exp_total'], 2));
                 $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
                         $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
                         $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                         $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                         $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                         $i++;
                     }
                 }
             }
         }
         if (!empty($gross_profit)) {
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, 'Gross Profit');
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format($gross_profit, 2));
             $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
             $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
             $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
             $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setBold(true);
             $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setSize(12);
             $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
             $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
         }
 
         $total = 0;
         $j = 13;
         if (isset($sale_pur['inc'])) {
 
             foreach ($sale_pur['inc'] as $key => $value) {
                 $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$value['name']);
                 $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
                 $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format(@$sale_pur['inc_total'], 2));
                 $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                 $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
                     }
                 }
             }
         }
         if (!empty($gross_loss)) {
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, 'Gross Loss');
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format(@$gross_loss, 2));
             $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
             $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
             $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setBold(true);
             $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setSize(12);
             $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
             $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->getFormatCode();
         }
 
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, 'Total');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format($expens_total, 2));
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, 'Total');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format($income_total, 2));
         $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getBorders()
             ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getBorders()
             ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
         $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getFont()->setBold(true);
         $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getFont()->setSize(15);
         $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
         $spreadsheet->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
         $spreadsheet->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->getFormatCode();
 
         $spreadsheet->getActiveSheet()->setTitle('Trading report');
         $spreadsheet->createSheet();
 
         $spreadsheet->getActiveSheet()->setTitle('docs');
 
         // ------------- End Summary For Advance Adjusted (11B) ------------- //
 
         $spreadsheet->setActiveSheetIndex(0);
         $writer = new Xlsx($spreadsheet);
         $writer->save('php://output');
     }

}
