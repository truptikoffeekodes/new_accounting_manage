<?php

namespace App\Models;
use CodeIgniter\Model;

class GeneralModel extends Model
{   
    public function get_data_table($table = '', $where = array(), $select = '') {
         
        $db = $this->db;
        if(session('DataSource')){
            $db->setDatabase(session('DataSource')); 
        }
        $builder = $db->table($table);
        if ($select == '')
            $select = '*';
        $query = $builder->select($select)->where($where)->get();
        
        $getdata = $query->getResultArray();
        
        if (!empty($getdata)) {
            $result = $getdata[0];
        } else {
            $result = array();
        }
        return $result;
    }

    public function company_data_table($table = '', $where = array(), $select = '') {
         
        $db = $this->db;
        $db->setDatabase('manifest_erp'); 
        
        $builder = $db->table($table);
        if ($select == '')
            $select = '*';
        $query = $builder->select($select)->where($where)->get();
        
        $getdata = $query->getResultArray();
           
        if (!empty($getdata)) {
            $result = $getdata[0];
        } else {
            $result = array();
        }

        return $result;
    }

    public function get_array_table($table = '', $where = array(), $select = '') {
         
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        if ($select == '')
            $select = '*';
        $query = $builder->select($select)->where($where)->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        if (!empty($getdata)) {
            $result = $getdata;
        } 
        return $result;
    }

    public function get_api_array_table($database,$table = '', $where = array(), $select = '') {
         
        $db = $this->db;
        $db->setDatabase($database); 
        $builder = $db->table($table);
        if ($select == '')
            $select = '*';
        $query = $builder->select($select)->where($where)->get();
        $getdata = $query->getResultArray();
        // echo $db->getLastQuery();exit;

        $result = array();
        if (!empty($getdata)) {
            $result = $getdata;
        } 
        return $result;
    }
    
    public function get_lastId($table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(id) as last_id';
        $query = $builder->select($select)->get();
        //echo $db->getLastQuery($query);exit;
        $getdata = $query->getRow();

        if (!empty($getdata)) {
            $result = $getdata->last_id;
        } else {
            $result = '';
        }
        
        return $result;
    }

    public function get_return_id($table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(return_no) as last_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->last_id;
        } else {
            $result = '';
        }
        
        return $result;
    }

    public function get_api_voucher_return_id($database,$table = '')
    {
        $db = $this->db;
        $db->setDatabase($database); 
        $builder = $db->table($table);
        $select = 'MAX(return_no) as last_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->last_id;
        } else {
            $result = '';
        }
        
        return $result;
    }
    
    public function get_purchase_id($table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(challan_no) as last_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->last_id;
        } else {
            $result = '';
        }
        
        return $result;
    }
    public function get_voucher_id($table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(challan_no) as max_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->max_id;
        } else {
            $result = '';
        }
        
        return $result;
    }
   

    public function get_saleInv_id($table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(invoice_no) as max_id';
        $builder->select($select);
        if($table == 'sales_ACinvoice'){
            $builder->where('v_type','general');
        }
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->max_id;
        } else {
            $result = '';
        }
        
        return $result;
    }

    public function get_general_id($type,$table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(invoice_no) as max_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $builder->where('v_type',$type);
        $query = $builder->get();
        $getdata = $query->getRow();

        if (!empty($getdata)) {
            $result = $getdata->max_id;
        } else {
            $result = '';
        }    
        
        return $result;
    }

    public function get_api_data_table($database,$table = '', $where = array(), $select = '') {
         
        $db = $this->db;
        $db->setDatabase($database); 
        $builder = $db->table($table);
        if ($select == '')
            $select = '*';
        $query = $builder->select($select)->where($where)->get();
        // echo $db->getLastQuery($query);exit;
        $getdata = $query->getResultArray();
        
        if (!empty($getdata)) {
            $result = $getdata[0];
        } else {
            $result = array();
        }
        return $result;
    }

    public function get_api_voucher_id($database,$table = '')
    {
        $db = $this->db;
        $db->setDatabase($database); 
        $builder = $db->table($table);
        $select = 'MAX(challan_no) as max_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->max_id;
        } else {
            $result = '';
        }
        
        return $result;
    }

    public function get_api_voucher_invoice_id($database,$table = '')
    {
        $db = $this->db;
        $db->setDatabase($database); 
        $builder = $db->table($table);
        $select = 'MAX(invoice_no) as max_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();
        if (!empty($getdata)) {
            $result = $getdata->max_id;
        } else {
            $result = '';
        }
        
        return $result;
    }

    public function get_srlastId($table = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table($table);
        $select = 'MAX(sr_no) as last_id';
        $builder->select($select);
        $builder->where('is_delete','0');
        $query = $builder->get();
        $getdata = $query->getRow();

        if (!empty($getdata)) {
            $result = $getdata->last_id;
        } else {
            $result = '';
        }

        return $result;
    }

    public function get_bank_reconsilation($id='',$end_date){

        $start_date = session('financial_form');
        if ($end_date == '') {
            if (date('m') < '03') {
                $year = date('Y');
            } else {
                $year = date('Y') + 1;
            }
            $end_date = $year . '-03-31';
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('bank_tras');
        $builder->select('*');
        $builder->where('is_delete',0);
        $builder->where('account',$id);
        $builder->where('recons_date !=', '');
        $builder->where('payment_type', 'bank');
        $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
        $result = $builder->get();
        $result_array = $result->getResultArray();

        $builder=$db->table('bank_tras bt');
        $builder->select('ct.id as ct_id,bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,ct.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
        $builder->join('contra_trans ct','ct.parent_id = bt.id');
        $builder->join('account ac','ac.id = ct.account');
        $builder->where(array('bt.payment_type' => 'contra','bt.is_delete' => 0));
        $builder->where('ct.recons_date !=' , '');
        $builder->where('ct.account',$id);
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
        $builder->orderBy('bt.receipt_date','ASC');
        $query=$builder->get();
        $result_array1 = $query->getResultArray();
    
        $result = array_merge($result_array,$result_array1);
        
        $total = 0;
        
        foreach($result as $row){
            if($row['mode'] == 'Payment'){
                $total -= $row['amount'];
            }
            if($row['mode'] == 'Receipt'){
                $total += $row['amount'];
            }
        }

        // echo '<pre>';print_r($total);exit;
        return $total;
    }
  
    public function search_salevouchertype_data($post){

        $gmodel = new GeneralModel();
        $sales = $gmodel->get_data_table('gl_group',array('name'=>'Sale'),'id');

        $sales_gl = gl_list([$sales['id']]);
        $sales_gl[]=$sales['id'];

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$sales_gl);
        if(@$post['searchTerm']){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state']);
        }
        return $result;
    }

    public function search_saleReturnVoucher_data($post){

        $gmodel = new GeneralModel();
        $sales = $gmodel->get_data_table('gl_group',array('name'=>'Sales Return'),'id');

        $sales_gl = gl_list([$sales['id']]);
        $sales_gl[]=$sales['id'];

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$sales_gl);
        if(@$post['searchTerm']){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state']);
        }
        return $result;
    }

    public function search_purchasevouchertype_data($post){

        $gmodel = new GeneralModel();
        $sales = $gmodel->get_data_table('gl_group',array('name'=>'Purchases'),'id');
        $sales_gl = gl_list([@$sales['id']]);
        $sales_gl[]=$sales['id'];
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$sales_gl);
        if(@$post['searchTerm']){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();

        //echo $db->getLastQuery();exit;


        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state']);
        }
        return $result;
    }

    public function search_purchaseRetvoucher_data($post){

        $gmodel = new GeneralModel();
        $sales = $gmodel->get_data_table('gl_group',array('name'=>'Purchase Return'),'id');

        $sales_gl = gl_list([$sales['id']]);
        $sales_gl[]=$sales['id'];

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$sales_gl);
        if(@$post['searchTerm']){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();

        //echo $db->getLastQuery();exit;


        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state']);
        }
        return $result;
    }

    // update trupti 24-11-2022
    public function get_sales_return($id) {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('sales_return sr'); 
        $builder->select('sr.*,ac.name as account_name');
        $builder->join('account ac','ac.id = sr.account');
        $builder->where(array('sr.id'=>$id));
        $query = $builder->get();
        $return = $query->getResultArray();
                
        $getdata['s_return'] = $return[0];
        
        $gmodel=new GeneralModel();
        foreach($return as $row){
            
            $getbroker = $gmodel->get_data_table('account',array('id'=>$row['broker']),'name');
            $getdelivery = $gmodel->get_data_table('account',array('id'=>$row['delivery_code']),'name');
            $getinvoice = $gmodel->get_data_table('sales_invoice',array('id'=>$row['invoice']),'id,invoice_date,net_amount');   
            $gettransport = $gmodel->get_data_table('transport',array('id'=>$row['transport']),'code');
            $getcity = $gmodel->get_data_table('cities',array('id'=>$row['city']),'name');
            $getvehicle = $gmodel->get_data_table('vehicle',array('id'=>$row['vehicle_no']),'name');
            $getvoucher = $gmodel->get_data_table('account',array('id'=>$row['voucher_type']),'name');
            $getround = $gmodel->get_data_table('account',array('id'=>$row['round']),'name');
            $igst_acc = $gmodel->get_data_table('account',array('id'=>@$row['igst_acc']),'name');
            $sgst_acc = $gmodel->get_data_table('account',array('id'=>@$row['sgst_acc']),'name');
            $cgst_acc = $gmodel->get_data_table('account',array('id'=>@$row['cgst_acc']),'name');

            $getdata['s_return']['broker_name']=@$getbroker['name'];
            $getdata['s_return']['delivery_name']=@$getdelivery['name'];
            $getdata['s_return']['transport_name']=@$gettransport['code'];
            $getdata['s_return']['city_name']=@$getcity['name'];
            $getdata['s_return']['vehicle_name']=@$getvehicle['name'];
            $getdata['s_return']['voucher_name']=@$getvoucher['name'];
            $getdata['s_return']['round_name']=@$getround['name'];
            $getdata['s_return']['igst_acc_name']=@$igst_acc['name'];
            $getdata['s_return']['sgst_acc_name']=@$sgst_acc['name'];
            $getdata['s_return']['cgst_acc_name']=@$cgst_acc['name'];

            if(!empty($getinvoice)){
                $getdata['s_return']['invoice_name']='('.@$getinvoice['id'].') -'.@$row['account_name'].' - '.@$getinvoice['invoice_date'].'- ₹'.@$getinvoice['net_amount'];
            }else{
                $getdata['s_return']['invoice_name']='';
            }
        }
        
        $item_builder =$db->table('sales_item st');
        $item_builder->select('st.*,st.uom as uom');
        //$item_builder->join('item i','i.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id,'st.type' => 'return','st.expence_type'=>'','st.is_delete' => 0 ));
        $query= $item_builder->get();
        $getdata1 = $query->getResultArray();
        //echo '<pre>';print_r($getdata1);exit;
        foreach($getdata1 as $row){
            if($row['is_expence'] == 0)
            {
                $getitem = $gmodel->get_data_table('item',array('id'=>$row['item_id']),'id,type,name,sku,purchase_cost,hsn,code,uom as item_uom');
                $uom =  explode(',',$getitem['item_uom']);
                foreach($uom as $row1){
                    $getuom = $gmodel->get_data_table('uom',array('id'=>$row1),'code');
                    $uom_arr[] =$getuom['code']; 
                }
                
                $coma_uom = implode(',',$uom_arr);
                $row['item_uom'] =$coma_uom; 
                $row['id'] =$row['item_id']; 
                $row['type'] =$getitem['type'];  
               // $row['mode'] =$getitem['mode'];  
                $row['name'] =$getitem['name']; 
                $row['sku'] =$getitem['sku']; 
                $row['purchase_cost'] =$getitem['purchase_cost']; 
                $row['hsn'] =$getitem['hsn'];
               
            }
            else
            {
                $getaccount = $gmodel->get_data_table('account',array('id'=>$row['item_id']),'id,name,code');
                $row['id'] =$row['item_id'];
                $row['name'] =$getaccount['name'];
                $row['code'] =$getaccount['code']; 
                $row['hsn'] ='';
                
            }
            $getdata['item'][] = $row;
        }
        $item_builder = $db->table('sales_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'return','st.expence_type'=>'rounding_invoices','st.is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getrounding = $query->getRowArray();
        //echo '<pre>';Print_r($getrounding);exit;
        

        $getdata['s_return']['round_acc'] = @$getrounding['item_id'];
        $getdata['s_return']['round_acc_name'] = @$getrounding['acc_name'];

        $item_builder = $db->table('sales_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'return','st.expence_type'=>'discount','st.is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdiscount = $query->getRowArray();

        $getdata['s_return']['discount_acc'] = @$getdiscount['item_id'];
        $getdata['s_return']['discount_acc_name'] = @$getdiscount['acc_name'];
        return $getdata;
    }

    public function update_data_table($table = '', $where = array(), $upadte_data = array()) {

        $db = $this->db;
        if(session('DataSource')){
            $db->setDatabase(session('DataSource')); 
        }
        $builder = $db->table($table);
        $builder->where($where);
        $result = $builder->update($upadte_data);
        // echo $db->getLastQuery();exit;        
        $return = array();
        if ($result) {
            $return = array('st' => 'success', 'txt' => 'success');
        } else {
            $return = array('st' => 'fail', 'txt' => 'update fail');
        }
        //print_r($return);exit;
        return $return;
    }

    public function update_api_data_table($database,$table = '', $where = array(), $upadte_data = array()) {
        $db = $this->db;
        $db->setDatabase($database); 
        
        $builder = $db->table($table);
        $builder->where($where);
        $result = $builder->update($upadte_data);
        $return = array();
        if ($result) {
            $return = array('st' => 'success', 'txt' => 'success');
        } else {
            $return = array('st' => 'fail', 'txt' => 'update fail');
        }
        //print_r($return);exit;
        return $return;
    }

    public function get_bill_term(){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('billterm');
        $builder->select('*');
        $builder->limit(3);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        return $result_array;

    }
    public function get_max_customInvno($post = '')
    {
        //print_r($post);exit;
        $time = strtotime($post['date']);
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder_pt_voucher = $db->table('platform_voucher');
        $select = 'MAX(voucher) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0', 'platform_id' => 1, 'type' => $post['type'],'form_type'=>'Normal'));
        $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();
        //print_r($getdata);exit;
        if (!empty($getdata)) {
            $max_voucher = $getdata->max_id;
            $plateform_data  = $this->get_data_table('platform_voucher', array('voucher' => $max_voucher), 'custom_inv_no');
           if (!empty($plateform_data)) {
                $string = $plateform_data['custom_inv_no'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int)filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
              
        } else {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
        }
        if($post['type'] == 'invoice')
        {
            $result = 'AI/' . $month . $year . '/' . $s_number;
        }
        else
        {
            $result = 'CN/AI/' . $month . $year . '/' . $s_number;
        }
        
        return $result;
    }
    
    public function ecom_get_max_customInvno()
    {
        $gmodel = new GeneralModel();
        $time = strtotime(date('Y-m-d'));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder_pt_voucher = $db->table('sales_invoice');
        $select = 'MAX(id) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0'));
        $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();
        $custom_date = $month . $year;

        if (!empty($getdata->max_id)) {
            $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $getdata->max_id), 'custom_inv_no');
            if (!empty($invoice_data['custom_inv_no'])) {
                $string = $invoice_data['custom_inv_no'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
           
           
        }
        else
        {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            
        }
        $result = 'KE' . '/' . $custom_date . '/B' . $s_number;
       
        
        return $result;
    }
    public function new_ecom_get_max_customInvno($post)
    {
        //print_r($post);exit;
        $gmodel = new GeneralModel();
        $time = strtotime($post['date']);
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $custom_date = $month . $year;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        if(empty($post['ac_id']))
        {
            $builder_pt_voucher = $db->table('sales_invoice');
            $select = 'MAX(id) as max_id';
            $builder_pt_voucher->select($select);
            $builder_pt_voucher->where(array('is_delete' => '0'));
            $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
            $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
            $query = $builder_pt_voucher->get();
            $getdata = $query->getRow();
            $custom_date = $month . $year;

            if (!empty($getdata->max_id)) {
                $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $getdata->max_id), 'custom_inv_no');
                if (!empty($invoice_data['custom_inv_no'])) {
                    $string = $invoice_data['custom_inv_no'];
                    $outputArr = preg_split("/\//", $string);
                    $count = count($outputArr);
                    $last_array = $outputArr[$count - 1];
                    $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                    $new_num = $int_var + 1;
                    $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
                } else {
                    $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                }
            
            
            }
            else
            {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                
            }
            $result = 'KE' . '/' . $custom_date . '/' . $s_number;
        }
        else
        {
            if(!empty($post['gst']))
            {
                $builder_pt_voucher = $db->table('sales_invoice');
                $select = 'MAX(id) as max_id';
                $builder_pt_voucher->select($select);
                $builder_pt_voucher->where(array('is_delete' => '0'));
                $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
                $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
                $builder_pt_voucher->where('gst !=','');
                $query = $builder_pt_voucher->get();
                $getdata = $query->getRow();
                if (!empty($getdata->max_id)) {
                    $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $getdata->max_id), 'custom_inv_no');
                    if (!empty($invoice_data['custom_inv_no'])) {
                        $string = $invoice_data['custom_inv_no'];
                        $outputArr = preg_split("/\//", $string);
                        $count = count($outputArr);
                        $last_array = $outputArr[$count - 1];
                        $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                        $new_num = $int_var + 1;
                        $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
                    } else {
                        $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                    }
                }   
                else
                {
                    $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                }   
                $result = 'KE' . '/' . $custom_date . '/B' . $s_number;      

            }
            else
            {
                $builder_pt_voucher = $db->table('sales_invoice');
                $select = 'MAX(id) as max_id';
                $builder_pt_voucher->select($select);
                $builder_pt_voucher->where(array('is_delete' => '0'));
                $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
                $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
                $builder_pt_voucher->where('gst =','');
                $query = $builder_pt_voucher->get();
                $getdata = $query->getRow();
                if (!empty($getdata->max_id)) {
                    $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $getdata->max_id), 'custom_inv_no');
                    if (!empty($invoice_data['custom_inv_no'])) {
                        $string = $invoice_data['custom_inv_no'];
                        $outputArr = preg_split("/\//", $string);
                        $count = count($outputArr);
                        $last_array = $outputArr[$count - 1];
                        $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                        $new_num = $int_var + 1;
                        $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
                    } else {
                        $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                    }
                }   
                else
                {
                    $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                }   
                $result = 'KE' . '/' . $custom_date . '/C' . $s_number;  
            }
        }
        
        return $result;
    }
    public function ecom_ret_get_max_customInvno()
    {
        $gmodel = new GeneralModel();
        $time = strtotime(date('Y-m-d'));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder_pt_voucher = $db->table('sales_return');
        $select = 'MAX(id) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0'));
        $builder_pt_voucher->where(array('DATE(return_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(return_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();
        $custom_date = $month . $year;

        if (!empty($getdata->max_id)) {
            $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $getdata->max_id), 'supp_inv');
            if (!empty($invoice_data['supp_inv'])) {
                $string = $invoice_data['supp_inv'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
           
           
        }
        else
        {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            
        }
        $result = 'C/KE' . '/' . $custom_date . '/B' . $s_number;
       
        
        return $result;
    }
    public function new_ret_ecom_get_max_customInvno($post)
    {
        //print_r($post);exit;
        $gmodel = new GeneralModel();
        $time = strtotime($post['date']);
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $custom_date = $month . $year;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        if(empty($post['ac_id']))
        {
            $builder_pt_voucher = $db->table('sales_return');
            $select = 'MAX(id) as max_id';
            $builder_pt_voucher->select($select);
            $builder_pt_voucher->where(array('is_delete' => '0'));
            $builder_pt_voucher->where(array('DATE(return_date)  >= ' => $start_date));
            $builder_pt_voucher->where(array('DATE(return_date)  <= ' => $end_date));
            $query = $builder_pt_voucher->get();
            $getdata = $query->getRow();
            $custom_date = $month . $year;

            if (!empty($getdata->max_id)) {
                $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $getdata->max_id), 'supp_inv');
                if (!empty($invoice_data['supp_inv'])) {
                    $string = $invoice_data['supp_inv'];
                    $outputArr = preg_split("/\//", $string);
                    $count = count($outputArr);
                    $last_array = $outputArr[$count - 1];
                    $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                    $new_num = $int_var + 1;
                    $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
                } else {
                    $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                }
            
            
            }
            else
            {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                
            }
            $result = 'C/KE' . '/' . $custom_date . '/' . $s_number;
        }
        else
        {
            if(!empty($post['gst']))
            {
                $builder_pt_voucher = $db->table('sales_return');
                $select = 'MAX(id) as max_id';
                $builder_pt_voucher->select($select);
                $builder_pt_voucher->where(array('is_delete' => '0'));
                $builder_pt_voucher->where(array('DATE(return_date)  >= ' => $start_date));
                $builder_pt_voucher->where(array('DATE(return_date)  <= ' => $end_date));
                $builder_pt_voucher->where('gst !=','');
                $query = $builder_pt_voucher->get();
                $getdata = $query->getRow();
                if (!empty($getdata->max_id)) {
                    $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $getdata->max_id), 'supp_inv');
                    if (!empty($invoice_data['supp_inv'])) {
                        $string = $invoice_data['supp_inv'];
                        $outputArr = preg_split("/\//", $string);
                        $count = count($outputArr);
                        $last_array = $outputArr[$count - 1];
                        $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                        $new_num = $int_var + 1;
                        $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
                    } else {
                        $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                    }
                }   
                else
                {
                    $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                }   
                $result = 'C/KE' . '/' . $custom_date . '/B' . $s_number;      

            }
            else
            {
                $builder_pt_voucher = $db->table('sales_return');
                $select = 'MAX(id) as max_id';
                $builder_pt_voucher->select($select);
                $builder_pt_voucher->where(array('is_delete' => '0'));
                $builder_pt_voucher->where(array('DATE(return_date)  >= ' => $start_date));
                $builder_pt_voucher->where(array('DATE(return_date)  <= ' => $end_date));
                $builder_pt_voucher->where('gst =','');
                $query = $builder_pt_voucher->get();
                $getdata = $query->getRow();
                if (!empty($getdata->max_id)) {
                    $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $getdata->max_id), 'supp_inv');
                    if (!empty($invoice_data['supp_inv'])) {
                        $string = $invoice_data['supp_inv'];
                        $outputArr = preg_split("/\//", $string);
                        $count = count($outputArr);
                        $last_array = $outputArr[$count - 1];
                        $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                        $new_num = $int_var + 1;
                        $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
                    } else {
                        $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                    }
                }   
                else
                {
                    $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
                }   
                $result = 'C/KE' . '/' . $custom_date . '/C' . $s_number;  
            }
        }
        
        return $result;
    }
  
}