<?php

namespace App\Models;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TradingModel extends Model
{

    public function get_closing_stock($start_date ='', $end_date= ''){
        
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
            // $sale_pur = sale_purchase_itm_total($start_date,$end_date);
            if($purchase['itm']['total_qty'] != 0 ){
                $diff_total[] =   (@$purchase['itm']['total_rate'] / @$purchase['itm']['total_qty']) * (@$purchase['itm']['total_qty'] - @$sale['itm']['total_qty']);    
            }else{
                $diff_total[] =   1 * (@$purchase['itm']['total_qty'] - @$sale['itm']['total_qty']);    
            }
        }
        $final_total  =  array_sum($diff_total);
        if(!isset($final_total) || empty($final_total)){
            $final_total = 0;
        }
        return $final_total;
        
    }

    public function get_closing_bal($start_date ='', $end_date= ''){
        
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

    public function purchaseItem_voucher_wise_data($get){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);

            $builder = $db->table('purchase_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';


            $builder = $db->table('purchase_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        // echo $db->getLastQuery();exit;
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        return $purchase;     
    }
    
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
            $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
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
            $builder->select('p.invoice_no as voucher_id,p.id,p.taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();

            // $builder = $db->table('sales_item pi');
            // $builder->select('p.id,(pi.rate*pi.qty) as taxable,pi.type,ac.name as party_name,p.invoice_date  as date');
            // $builder->join('sales_invoice p', 'p.id =pi.parent_id');
            // $builder->join('account ac', 'ac.id =p.account');
            // $builder->where('pi.type','invoice' );
            // $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            // $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            // $builder->groupBy('p.id');
            // $query = $builder->get();
            // $sales['sales'] = $query->getResultArray();

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        //echo '<pre>';print_r($sales);exit;
        return $sales;     
    }

    public function purchaseGray_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('gray_item gi');
            $builder->select('g.id,SUM(amount) as taxable,ac.name as party_name,g.inv_date as date');
            $builder->join('grey g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Gray');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(g.inv_date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('gray_item gi');
            $builder->select('g.id,SUM(amount) as taxable,ac.name as party_name,g.inv_date as date');
            $builder->join('grey g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Gray');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(g.inv_date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        // echo '<pre>';print_r($purchase);exit;
        return $purchase;     
    }

    public function salesGray_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('saleMillInvoice_Item sgi');
            $builder->select('sg.id as id,(SUM(sgi.price) * SUM(sgi.meter)) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date as date');
            $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "gray")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('saleMillInvoice_Item sgi');
            $builder->select('sg.id as id,(SUM(sgi.price) * SUM(sgi.meter)) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date as date');
            $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "gray")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

         //echo '<pre>';print_r($sales);exit;
        return $sales;     
    }

    public function purchaseReturnItem_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('purchase_return p');
            $builder->select('p.return_no,p.id,SUM(p.taxable) as taxable,ac.name as party_name,p.return_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_return p');
            $builder->select('p.return_no,p.id,SUM(p.taxable) as taxable,ac.name as party_name,p.return_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        // echo '<pre>';print_r($purchase);exit;
        return $purchase;     
    }

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
            $builder->select('p.return_no,p.id,SUM(taxable) as taxable,ac.name as party_name,p.return_date as date,,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_return p');
            $builder->select('p.return_no,p.id,SUM(net_amount) as taxable,ac.name as party_name,p.return_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where('p.is_delete',0);
            $builder->where('p.is_cancle',0);
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        // echo '<pre>';print_r($purchase);exit;
        return $sales;     
    }
    
    public function purchaseReturnGray_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('retGrayFinish_item gi');
            $builder->select('g.id,SUM(subtotal) as taxable,ac.name as party_name,g.date as date');
            $builder->join('retGrayFinish g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Gray');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.date)  >= ' => $start_date));
            $builder->where(array('DATE(g.date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('retGrayFinish_item gi');
            $builder->select('g.id,SUM(subtotal) as taxable,ac.name as party_name,g.date as date');
            $builder->join('retGrayFinish g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Gray');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.date)  >= ' => $start_date));
            $builder->where(array('DATE(g.date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        // echo '<pre>';print_r($purchase);exit;
        return $purchase;     
    }

    public function salesReturnGray_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('saleMillReturn_Item sgi');
            $builder->select('sg.id as id,SUM(sg.total_amount) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date as date');
            $builder->join('saleMillReturn sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "gray")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('saleMillReturn_Item sgi');
            $builder->select('sg.id as id,SUM(sg.total_amount) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date as date');
            $builder->join('saleMillReturn sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "gray")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        // echo '<pre>';print_r($purchase);exit;
        return $sales;     
    }

    public function purchaseReturnFinish_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('retGrayFinish_item gi');
            $builder->select('g.id,SUM(subtotal) as taxable,ac.name as party_name,g.date as date');
            $builder->join('retGrayFinish g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Finish');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.date)  >= ' => $start_date));
            $builder->where(array('DATE(g.date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('retGrayFinish_item gi');
            $builder->select('g.id,SUM(subtotal) as taxable,ac.name as party_name,g.date as date');
            $builder->join('retGrayFinish g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Finish');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.date)  >= ' => $start_date));
            $builder->where(array('DATE(g.date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        return $purchase;     
    }

    public function salesReturnFinish_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('saleMillReturn_Item sgi');
            $builder->select('sg.id as id,SUM(sg.total_amount) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date as date');
            $builder->join('saleMillReturn sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Finish" OR sgi.item_type = "finish")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('salemillreturn_item sgi');
            $builder->select('sg.id as id,SUM(sg.total_amount) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date as date');
            $builder->join('salemillreturn sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Finish" OR sgi.item_type = "finish")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();;

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        return $sales;     
    }

    public function purchaseFinish_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('gray_item gi');
            $builder->select('g.id,SUM(amount) as taxable,ac.name as party_name,g.inv_date as date');
            $builder->join('grey g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Finish');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(g.inv_date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('gray_item gi');
            $builder->select('g.id,SUM(amount) as taxable,ac.name as party_name,g.inv_date as date');
            $builder->join('grey g', 'g.id = gi.voucher_id');
            $builder->join('account ac', 'ac.id =g.party_name');
            $builder->where('gi.purchase_type','Finish');
            $builder->where(array('g.is_delete' => '0'));
            $builder->where(array('DATE(g.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(g.inv_date)  <= ' => $end_date));
            $builder->groupBy('g.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        return $purchase;     
    }

    public function salesFinish_voucher_wise_data($get){
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $purchase = array();

        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
             
            $builder = $db->table('saleMillInvoice_Item sgi');
            $builder->select('sg.id as id,(SUM(sgi.price) * SUM(sgi.meter)) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date');
            $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Finish" OR sgi.item_type = "finish")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();


        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('saleMillInvoice_Item sgi');
            $builder->select('sg.id as id,(SUM(sgi.price) * SUM(sgi.meter)) as taxable,ac.name as party_name,MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter,sg.date');
            $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
            $builder->join('account ac', 'ac.id =sg.account');
            $builder->where('(sgi.item_type="Finish" OR sgi.item_type = "finish")');
            $builder->where(array('sg.is_delete' => '0'));
            $builder->where(array('DATE(sg.date)  >= ' => $start_date));
            $builder->where(array('DATE(sg.date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();

        }else{
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        return $sales;     
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
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
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
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
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
        // echo '<pre>';print_r($pg_income);exit;
        $result['sales'] = array();
        $total = 0;
        if(!empty($pg_income['sales'])){
            foreach ($pg_income['sales'] as $row) {
       
                $after_disc=0;
                
                if($row['disc_type'] == 'Fixed'){
                    $row['pg_amount'] = (float)$row['pg_amount'] -  (float)$row['discount'];
                    $after_disc =  $row['pg_amount'];
                }else{
                    $row['pg_amount'] = ((float)$row['pg_amount'] * ((float)$row['discount'] / 100));
                    $after_disc =  $row['pg_amount'];
                }
                
                // if($row['amtx_type'] == 'Fixed'){
                //     $row['pg_amount'] = (float)$after_disc - (float)$row['amtx']; 
                // }else{
                //     $row['pg_amount'] = (float)$after_disc - ((float)$after_disc * ((float)$row['amtx'] / 100));
                // }
                
                if($row['amty_type'] == 'Fixed'){
                    $row['pg_amount'] = (float)$row['pg_amount'] + (float)$row['amty']; 
                }else{
                    $row['pg_amount'] = (float)$row['pg_amount'] + ((float)$after_disc * ((float)$row['amty'] / 100));
                }
        
                // $total += $row['pg_amount'];
                $row['taxable'] = $row['pg_amount'];
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
            $pg_expence['purchase'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_particu pp');
            $builder->select('ac.name as party_name,pg.doc_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
            $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
            $builder->where('pg.party_account',$get['id']);
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

        $bank_income['date']['from'] = $start_date;
        $bank_income['date']['to'] = $end_date;
        $bank_income['ac_id'] = $get['id'];

        $gmodel = new GeneralModel();
        $account  = $gmodel->get_data_table("account",array('id'=>$get['id']),'name');
        $bank_income['ac_name'] = @$account['name'];

        return $bank_income;     
    }
      // update trupti 26-12-2022 duties and taxes add taxes account
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.net_amount as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_igst as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_cgst as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_sgst as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.net_amount as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_igst as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_cgst as amount');
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
            $builder->select('pi.id,ac.id as account_id,ac.name as party_name,pi.return_date as date,pi.tot_sgst as amount');
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

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        $purchase['ac_id'] = $get['id'];
        //echo '<pre>';print_r($purchase);exit;
        return $purchase;
    }
    // update trupti 26-12-2022 duties and taxes add taxes account
    public function purchase_voucher_wise_data($get)
    {
        // print_r($get);exit;
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

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        $purchase['ac_id'] = $get['id'];

        return $purchase;
    }

    
    public function gray_finish_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('grey gi');
            $builder->select('gi.id,ac.id as account_id,ac.name as party_name,gi.inv_date as date,gi.total_amount as taxable,gi.purchase_type as mode');
            $builder->join('account ac', 'ac.id =gi.party_name');
            $builder->where(array('gi.party_name' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('gi.is_delete' => '0'));
            $builder->where(array('DATE(gi.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(gi.inv_date)  <= ' => $end_date));
            $builder->groupBy('gi.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();     

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('grey gi');
            $builder->select('gi.id,ac.id as account_id,ac.name as party_name,gi.inv_date as date,gi.total_amount as taxable,gi.purchase_type as mode');
            $builder->join('account ac', 'ac.id =gi.party_name');
            $builder->where(array('gi.party_name' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('gi.is_delete' => '0'));
            $builder->where(array('DATE(gi.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(gi.inv_date)  <= ' => $end_date));
            $builder->groupBy('gi.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();      

        }else{
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        $purchase['ac_id'] = $get['id'];

        return $purchase;     
    }
    
    public function gray_finish_ret_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('retGrayFinish gi');
            $builder->select('gi.id,ac.id as account_id,ac.name as party_name,gi.date as date,gi.total_amount as taxable,gi.purchase_type as mode');
            $builder->join('account ac', 'ac.id =gi.party_name');
            $builder->where(array('gi.party_name' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('gi.is_delete' => '0'));
            $builder->where(array('DATE(gi.date)  >= ' => $start_date));
            $builder->where(array('DATE(gi.date)  <= ' => $end_date));
            $builder->groupBy('gi.id');
            $query = $builder->get();
            $purchase['purchase_ret'] = $query->getResultArray();     
            
        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('retGrayFinish gi');
            $builder->select('gi.id,ac.id as account_id,ac.name as party_name,gi.date as date,gi.total_amount as taxable,gi.purchase_type as mode');
            $builder->join('account ac', 'ac.id =gi.party_name');
            $builder->where(array('gi.party_name' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('gi.is_delete' => '0'));
            $builder->where(array('DATE(gi.date)  >= ' => $start_date));
            $builder->where(array('DATE(gi.date)  <= ' => $end_date));
            $builder->groupBy('gi.id');
            $query = $builder->get();
            $purchase['purchase_ret'] = $query->getResultArray();      

        }else{
            $purchase['purchase_ret'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;
        $purchase['ac_id'] = $get['id'];

        return $purchase;     
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

        $jv_income['date']['from'] = $start_date;
        $jv_income['date']['to'] = $end_date;
        $jv_income['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $jv_income;     
    }

    public function purchase_bank_cash_voucher_wise_data($get){

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
            $bank_expence['purchase'] = $query->getResultArray();     


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
            $bank_expence['purchase'] = $query->getResultArray();      

        }else{
            $bank_expence['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $bank_expence['date']['from'] = $start_date;
        $bank_expence['date']['to'] = $end_date;
        $bank_expence['ac_id'] = $get['id'];
        // echo '<pre>';print_r($bank_income);exit;
        return $bank_expence;     
    }

    public function purchase_jv_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('jv_particular jv');
            $builder->select('jv.jv_id as id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.id');
            $query = $builder->get();
            $jv_expence['purchase'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.jv_id as id,jv.date,ac.id as account_id,jv.amount as taxable, ac.name as party_name,jv.dr_cr');
            $builder->join('account ac', 'ac.id =jv.particular');
            $builder->where(array('jv.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('jv.is_delete' => '0'));
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            $builder->groupBy('jv.jv_id');
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
        
        // echo '<pre>';print_r($bank_income);exit;
        return $jv_expence;     
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
            $builder->select('bt.id,ac.id as account_id,ac.name as party_name,bt.receipt_date as date,bt.amount as total,bt.mode,bt.payment_type');
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
            $builder->where(array('bt.particular' => $get['id']));
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
            $builder->select('si.id,si.invoice_date,ac.id as account_id,si.net_amount as taxable, ac.name as party_name');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('si.is_cancle' => '0'));
            $builder->where(array('DATE(si.sales_invoice)  >= ' => $start_date));
            $builder->where(array('DATE(si.sales_invoice)  <= ' => $end_date));
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

    public function currentassets_millsales_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('saleMillInvoice si');
            $builder->select('si.id,si.date,ac.id as account_id,si.total_amount as taxable, ac.name as party_name,si.item_type as mode');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $mill_sales['currentassets_millsales'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('saleMillInvoice si');
            $builder->select('si.id,si.date,ac.id as account_id,si.total_amount as taxable, ac.name as party_name,si.item_type as mode');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $mill_sales['currentassets_millsales'] = $query->getResultArray();
        }else{

            $mill_sales['currentassets_millsales'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $mill_sales['date']['from'] = $start_date;
        $mill_sales['date']['to'] = $end_date;
        $mill_sales['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $mill_sales;     
    }

    public function currentassets_millsalesreturn_voucher_wise($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
            
            $builder = $db->table('saleMillReturn si');
            $builder->select('si.id,si.date,ac.id as account_id,si.total_amount as taxable, ac.name as party_name,,si.item_type as mode');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $mill_sales_return['currentassets_millsalesreturn'] = $query->getResultArray();

        }else if(!empty(@$get['from'])){

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('saleMillReturn si');
            $builder->select('si.id,si.date,ac.id as account_id,si.total_amount as taxable, ac.name as party_name,si.item_type as mode');
            $builder->join('account ac', 'ac.id =si.account');
            $builder->where(array('si.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('si.is_delete' => '0'));
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
            $builder->groupBy('si.id');
            $query = $builder->get();
            $mill_sales_return['currentassets_millsalesreturn'] = $query->getResultArray();
        }else{

            $mill_sales_return['currentassets_millsalesreturn'] = array();
            $start_date = '';
            $end_date = '';
        }   

        $mill_sales_return['date']['from'] = $start_date;
        $mill_sales_return['date']['to'] = $end_date;
        $mill_sales_return['ac_id'] = $get['id'];
        
        // echo '<pre>';print_r($bank_income);exit;
        return $mill_sales_return;     
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

            $builder = $db->table('contra_trans ct');
            $builder->select('ct.id,ac.id as account_id,ac.name as party_name,ct.receipt_date as date,ct.amount as taxable,ct.narration');
            $builder->join('account ac', 'ac.id =ct.particular');
            $builder->where(array('ct.particular' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
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

            $builder = $db->table('contra_trans ct');
            $builder->select('ct.id,ac.id as account_id,ac.name as party_name,ct.receipt_date as date,ct.amount as taxable,ct.narration');
            $builder->join('account ac', 'ac.id =ct.account');
            $builder->where(array('ct.account' => $get['id']));
            $builder->where(array('ac.is_delete' => '0'));
            $builder->where(array('ct.is_delete' => '0'));
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
    // update trupti 03-12-2022
    public function trading_xls_export_data($post)
    {
        $gmodel = new GeneralModel;
        $exp = array();
        $gl_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Expenses'),'id,name');
        $gl_inc_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Income'),'id,name');
        $init_total = 0;
        $sale_pur = sale_purchase_vouhcer($post['from'],$post['to']); 
      
        $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$post['from'],$post['to']);
        $exp[$gl_id['id']]['name'] = $gl_id['name'];
        $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$post['from'],$post['to']);
        
        $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$post['from'],$post['to']);
        $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
        $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$post['from'],$post['to']);
        
        $init_total = 0;

        $closing_stock = $this->get_closing_stock($post['from'],$post['to']);
        $closing_bal = $this->get_closing_bal($post['from'],$post['to']);
        $Opening_bal = Opening_bal('Opening Stock');

        if(session('is_stock') == 1 ){
            $closing_stock = @$closing_bal ? @$closing_bal : @$Opening_bal;
        }else{
            $closing_stock  = $closing_bal;
        }

        $all_purchase = @$sale_pur['pur_total_rate'];
        $all_purchase_return = @$sale_pur['Purret_total_rate'];
        
        $all_sale = @$sale_pur['sale_total_rate'];
        $all_sale_return = @$sale_pur['Saleret_total_rate'];

        $income_total = (float)$all_sale - (float)$all_sale_return + @$closing_stock + @$sale_pur['inc_total'];
        $expens_total = @$sale_pur['opening_bal'] + (float)$all_purchase  - (float)$all_purchase_return + @$sale_pur['exp_total'];
        
        if(($expens_total -  $income_total) < 0 ){
            $gross_profit = ($expens_total -  $income_total) * -1;
            
            $expens_total +=$gross_profit;
        }else{
            $gross_loss = $expens_total -  $income_total;
            $income_total +=$gross_loss; 
        }
        if((@$all_sale - @$all_sale_return)  != 0){
            $per_base = 100 / (@$all_sale - @$all_sale_return);
        }else{
            $per_base = 100/1;
        }

       //echo '<pre>';Print_r($sale_pur);exit;
       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->getBorders()
                    ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A5:F5')->getBorders()
                    ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C4:C10')->getBorders()
                    ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C8')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C8')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Trading Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', user_date(@$post['from']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', user_date(@$post['to']));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', '');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'as at '.user_date($post['to']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'as at '.user_date($post['to']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', '');

        //$i = 5;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6' , 'Opening Stock');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B6' , '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C6' ,number_format(@$sale_pur['opening_bal'],2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D6' , 'Sales Accounts');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E6' ,'');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F6' ,number_format($all_sale - $all_sale_return,2));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7' , 'Stock In Hand');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B7' , number_format(@$sale_pur['opening_bal'],2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C7' ,'');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D7' , 'Sales Accounts');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F7' , number_format($all_sale,2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F7' ,'');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A8' , 'Purchase Accounts');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B8' , '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C8' ,$all_purchase - $all_purchase_return);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D8' , 'Sales Return');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E8' ,'-'.number_format($all_sale_return,2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F8' ,'');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A9' , 'Purchase Account');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B9' , number_format($all_purchase,2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C9' ,'');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D9' , 'Closing Stock');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E9' ,'');
        if(session('is_stock') == 1 ) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F9' ,@$sale_pur['opening_bal'] + $closing_stock);
        }
        else
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F9' ,@$sale_pur['opening_bal']);
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A10' , 'Purchase Return');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B10' , '-'.number_format($all_purchase_return,2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C10' ,'');
      

        $total = 0;
        $i =11;
        if(isset($sale_pur['exp']))
        {
            
            foreach($sale_pur['exp'] as $key => $value) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , @$value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,number_format(@$sale_pur['exp_total'],2));
                $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
               
                if(!empty($value['account'])) {
                    foreach(@$value['account'] as $ac_key => $ac_value){
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , @$ac_key );
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , number_format($ac_value['total'],2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,'');
                        $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $i++;
                    }
                }
                $i++;
            }
        }
        if(!empty($value['sub_categories'])) {
            foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                $total = 0;
                $arr[$sub_key] = $sub_value;
                $total = subGrp_total($arr,0);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , @$sub_value['name'] );
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , number_format($total,2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,'');
                $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $i++;
            
            }
        }
        if(!empty($gross_profit)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , 'Gross Profit');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,number_format($gross_profit,2));
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
      
        }

        $total = 0;
        $j = 10;
        if(isset($sale_pur['inc']))
        {
            
            foreach($sale_pur['inc'] as $key => $value) { 
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , @$value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j ,'');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j , number_format(@$sale_pur['inc_total'],2));
                if(!empty($value['account'])) {
                    foreach(@$value['account'] as $ac_key => $ac_value){
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , @$ac_key);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j ,number_format($ac_value['total'],2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j , '');
                        $j++;
                    }
                }
                $j++;

            }
        }
        if(!empty($value['sub_categories'])) {
            foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                $total = 0;
                $arr[$sub_key] = $sub_value;
                $total = subGrp_total($arr,0);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , @$sub_value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j ,number_format($total,2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j , '');
                $j++;
            }
        }
        if(!empty($gross_loss)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , 'Gross Loss');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j ,'');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j ,number_format(@$gross_loss,2));
        }
        $spreadsheet->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getBorders()
                    ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , 'Total');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,number_format($expens_total,2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i , 'Total');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i ,'');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i ,number_format($income_total,2));
   
        $spreadsheet->getActiveSheet()->setTitle('Trading report');
        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }
    public function profit_loss_xls_export_data($post)
    {
        $gmodel = new GeneralModel;
        $gl_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Expenses','is_delete'=>0),'id,name');
        $gl_inc_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Income','is_delete'=>0),'id,name');
        $pl_exp_id = $gmodel->get_data_table('gl_group',array('name'=>'P & L Expenses','is_delete'=>0),'id,name');
        $pl_inc_id = $gmodel->get_data_table('gl_group',array('name'=>'P & L Incomes','is_delete'=>0),'id,name');
        $init_total =0;
         
        $trading = sale_purchase_itm_total($post['from'],$post['to']); 
            
        $exp[$gl_id['id']] = trading_expense_data($gl_id['id'],$post['from'],$post['to']);
        $exp[$gl_id['id']]['name'] = $gl_id['name'];
        $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'],$post['from'],$post['to']);
        
        $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'],$post['from'],$post['to']);
        $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
        $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'],$post['from'],$post['to']);
    
        $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id'],$post['from'],$post['to']);
        $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
        $exp_pl[$pl_exp_id['id']]['sub_categories']  = get_PL_expense_sub_grp_data($pl_exp_id['id'],$post['from'],$post['to']);

        $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id'],$post['from'],$post['to']);
        $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
        $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id'],$post['from'],$post['to']);
        
        $pl  = pl_tot_data($post['from'],$post['to']);
        $closing_stock = $this->get_closing_stock($post['from'],$post['to']);
        $closing_bal = $this->get_closing_bal($post['from'],$post['to']);
        $Opening_bal = Opening_bal('Opening Stock',$post['from'],$post['to']);

        //$trading = $sale_pur;
        //$data['pl'] = $pl ;
        
        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);

        $exp_pl_total = subGrp_total($exp_pl,$init_total);
        $inc_pl_total = subGrp_total($inc_pl,$init_total);

        $pl['exp'] = @$exp_pl;
        $pl['inc'] = @$inc_pl;

        $trading['exp_total'] = @$exp_total;
        $trading['inc_total'] = @$inc_total;
        
        $pl['exp_total'] = @$exp_pl_total;
        $pl['inc_total'] = @$inc_pl_total;
        
        $trading['opening_bal'] = $Opening_bal;
        $trading['closing_bal'] = @$closing_stock; 
        $trading['closing'] = @$closing_bal;

        if(session('is_stock') == 1 ){
            $closing_stock = @$trading['closing'] ? @$trading['closing'] : @$trading['opening_bal'];
        }else{
            $closing_stock  = @$trading['closing_bal'] ? @$trading['closing_bal'] : @$trading['opening_bal'] ;
        }

        $all_purchase = $trading['pur_total_rate'];
        $all_purchase_return = $trading['Purret_total_rate'];
        
        $all_sale = $trading['sale_total_rate'];
        $all_sale_return = $trading['Saleret_total_rate'];

        $income_total = (float)$all_sale - (float)$all_sale_return + $closing_stock +$trading['inc_total'];
        $expens_total = $trading['opening_bal'] + (float)$all_purchase  - (float)$all_purchase_return + $trading['exp_total'];


        if(($expens_total -  $income_total) < 0 ){
            $gross_profit = ($expens_total -  $income_total) * -1;
        }else{
            $gross_loss = $expens_total -  $income_total;
        }
        
        if((@$gross_loss + $pl['exp_total'])   >  ($pl['inc_total'] + @$gross_profit)){
            $net_loss = (@$gross_loss + $pl['exp_total']) - ($pl['inc_total'] + @$gross_profit);
        }else{
            $net_profit =($pl['inc_total'] + @$gross_profit)  - (@$gross_loss + $pl['exp_total']);
        }

        $pl_expens_total = $pl['exp_total'] + @$net_profit + @$gross_loss;
        $pl_income_total = $pl['inc_total'] + @$gross_profit + @$net_loss;
        

       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->getBorders()
                    ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A5:F5')->getBorders()
                    ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C4:C10')->getBorders()
                    ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C8')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('C8')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F6')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F6')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Profit/Loss Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', user_date(@$post['from']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', user_date(@$post['to']));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', '');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'as at'.user_date($post['to']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'as at'.user_date($post['to']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', '');
       // echo '<pre>';Print_r($gross_loss);exit;
        
        if(!empty($gross_loss)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6' , 'Gross Loss');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B6' , '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C6' ,number_format($gross_loss,2));
        }
        $total = 0;
        $i = 7;
        //echo '<pre>';Print_r($exp_pl);exit;
        
        if(!empty($pl['exp']))
        {
            foreach($pl['exp'] as $key => $value) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , @$value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,number_format(@$pl['exp_total'],2));

                if(!empty($value['account'])) {
                    foreach(@$value['account'] as $ac_key => $ac_value){
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , @$ac_key);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , number_format(@$ac_value['total'] ,2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,'');
                        $i++;
                    }
                }
                if(!empty($value['sub_categories'])) {
                    foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                        $total = 0;
                        $arr[$sub_key] = $sub_value;
                        $total = subGrp_total($arr,0);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , @$sub_value['name']);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , number_format($total,2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,'');
                        $i++;
                        unset($arr);
                    }
                }
                $i++;
            }
        }
        
        if(isset($net_profit) && !empty($net_profit) ) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , 'Net Profit');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,number_format($net_profit,2));
            //$i+1;
        }
        if(!empty($gross_profit)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D6' , 'Gross Profit');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E6' , '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F6' ,number_format($gross_profit,2));
        }
        $j = 7;
        $total_profit = 0;
        if(!empty($pl['inc']))
        {
            foreach($pl['inc'] as $key => $value) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , @$value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j , '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j ,number_format(@$pl['inc_total'],2));
                if(!empty($value['account'])) {
                    foreach(@$value['account'] as $ac_key => $ac_value){
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , @$ac_key);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j , number_format($ac_value['total'],2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j ,'');
                        $j++;
                    }   
                }     
                if(!empty($value['sub_categories'])) {
                    foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                        $total_profit = 0;
                        $arr[$sub_key] = $sub_value;
                        $total_profit = subGrp_total($arr,0);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , @$sub_value['name']);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j , number_format($total_profit,2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j ,'');
                        $j++;
                        unset($arr);
                    }
                }
                $j++;
            }
        }
        if(isset($net_loss) && !empty($net_loss) ) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j , 'Net Loss');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j , '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j ,number_format($net_loss,2));
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$i , 'Total');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$i , '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$i ,number_format($pl_expens_total,2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i , 'Total');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$i ,'');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$i ,number_format($pl_income_total,2));
        $spreadsheet->getActiveSheet()->setTitle('Trading report');
        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }
   //START update trupti 26-12-2022 duties and taxes add taxes account
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

        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;
        $sales['ac_id'] = $get['id'];

        return $sales;     
    }
    //update trupti 26-12-2022
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
        if(!empty($sales['sales'])){
            foreach ($sales['sales'] as $row) {
       
               // $total += $row['pg_amount'];
                if($row['pg_type'] == 'general'){
                    $total += (float)$row['pg_amount'];
                }else{
                    $total -= (float)$row['pg_amount'];
                } 
                $row['taxable'] = $total;
                $result['sales'][] = $row; 
            }
        }

        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }

// END update trupti 26-12-2022 duties and taxes add taxes account



}

?>