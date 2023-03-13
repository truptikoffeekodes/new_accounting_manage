<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\GeneralModel;

class BankModel extends Model
{   
    public function insert_edit_banktrans($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('bank_tras');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        $pdata = array();
        $msg = array();

        if(!is_numeric($post['amount'])){    
            $msg = array('st' => 'fail', 'msg' => "Amount Not Valid..!");
            return $msg;
        }
        
        $pdata = array(
            'payment_type' => $post['pay_type'],
            'mode' => $post['mode'],
            'receipt_date' => db_date($post['receipt_date']),
            'account' => !empty($post['account'])?$post['account']:'',
            'particular' => $post['particular'],
            'acc_state' => @$post['state'] ? $post['state'] :'' ,
            'amount' => $post['amount'],
            'adj_method' => @$post['adj_method'] ? $post['adj_method'] : '',
            'invoice' => @$post['invoice'],
            'invoice_tb' => @$post['invoice_table'],
            'narration' => $post['narration'],
            'stat_adj' =>@$post['stat_adj'] ? $post['stat_adj'] : 0,
        );

        if($post['pay_type'] == 'contra'){
            $pdata['cash_type'] = @$post['cash_type'] ? $post['cash_type'] : '';
        }
        
        if($post['mode'] == 'Receipt'){
            $pdata['nature_rec']=  @$post['nature_rec'] ? $post['nature_rec'] : '';
        }else{
            $pdata['nature_pay']=  @$post['nature_pay'] ? $post['nature_pay'] : '' ;
        }

        if(@$post['nature_rec'] == 2 ){
            $pdata['igst'] = @$post['igst'] ? @$post['igst'] : '';
            $pdata['cgst'] = @$post['cgst'] ? @$post['cgst'] : '';
            $pdata['sgst'] = @$post['sgst'] ? @$post['sgst'] : '';
            $pdata['item'] = @$post['item'] ? $post['item'] : '';
            $pdata['gst_amt'] = @$post['gst_amt'] ? $post['gst_amt'] : '';
            $pdata['taxable'] = @$post['taxable'] ? $post['taxable'] : '';
        }

        if(@$post['pay_type']  == 'bank' || @$post['pay_type']  == 'contra' ){
            $chk_date = @$post['chk_date'] ? db_date($post['chk_date']) : '';
            
            $pdata['check_no'] = @$post['checkno'] ? @$post['checkno'] : '' ;
            $pdata['check_date'] = @$chk_date;
        }

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if(empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                $builder = $db->table('bank_tras');


                $item_builder=$db->table('bank_cash_against');
                $item_result = $item_builder->select('GROUP_CONCAT(id) as ids')->where(array("parent_id" => $post['id']))->get();
                $getItem = $item_result->getRow();


                if(@$post['adj_method'] == "agains_reference"){
                    if(isset($post['vch_id']) && !empty($post['vch_id'])){

                        $getpid = explode(',', $getItem->ids);
                        $delete_itemid = array_diff($getpid,$post['against_id']);
                        
                        //$itemdata=0;
        
                        if(!empty($delete_itemid)){
                            foreach($delete_itemid as $key => $del_id){
                                $del_data = array('is_delete' => '1');
                                $item_builder->where(array('id' => $del_id , 'parent_id' => $post['id'] ));
                                $item_builder->update($del_data);
                            }       
                        }
                        
                        for($i=0;$i<count($post['vch_id']);$i++)
                        {
                            $item_result = $item_builder->select('*')->where(array("id" => $post['against_id'][$i],"parent_id" => $post['id']))->get();
                            $getItem = $item_result->getRow();

                            if(!empty($getItem)){
                                $item_data = array(
                                    'vch_id' => $post['vch_id'][$i],
                                    'payment_type' => $post['pay_type'],
                                    'date' => db_date($post['receipt_date']),
                                    'ac_id' => $post['ac_id'][$i],
                                    'ac_name' => $post['ac_name'][$i],
                                    'net_amt' => @$post['net_amt'][$i],
                                    'vch_amt' => $post['vch_amt'][$i],
                                    'total_paid' => $post['total_paid'][$i],
                                    'voucher_name' => @$post['voucher_name'][$i],
                                    'update_at' => date('Y-m-d H:i:s'),
                                    'update_by' => session('uid'),
                                );
                                $item_builder->where(array('id'=>$post['against_id'][$i],'parent_id'=>$post['id']));
                                $res = $item_builder->update($item_data);
                            }else{
                                $item_data = array(
                                    'parent_id' => $post['id'],
                                    'vch_id' => $post['vch_id'][$i],
                                    'payment_type' => $post['pay_type'],
                                    'date' => db_date($post['receipt_date']),
                                    'ac_id' => $post['ac_id'][$i],
                                    'ac_name' => $post['ac_name'][$i],
                                    'net_amt' => @$post['net_amt'][$i],
                                    'total_paid' => $post['total_paid'][$i],
                                    'vch_amt' => $post['vch_amt'][$i],
                                    'voucher_name' => @$post['voucher_name'][$i],
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'created_by' => session('uid'),
                                );
                                $res = $item_builder->insert($item_data);
                        }}
                    
                    }
                }else{
                    $del_data = array('is_delete' => '1');
                    $item_builder->where(array('parent_id' => $post['id'] ));
                    $item_builder->update($del_data);
                }

                if(@$post['pay_type'] == 'contra'){

                    $builder = $db->table('contra_trans');
                    $builder->select('*');
                    $builder->where('parent_id',$post['id']);
                    $builder->where('is_delete',0);
                    $query = $builder->get();
                    $res = $query->getResultArray();

                    $builder = $db->table('contra_trans');
    
                    $pdata = array(
                        'parent_id'=>$post['id'],
                        'mode' => 'Payment',
                        'receipt_date' => db_date($post['receipt_date']),  
                        'account' => $post['particular'], 
                        'amount' =>  $post['amount'],
                        'update_at' =>  date('Y-m-d H:i:s'),
                        'update_by' =>  session('uid')
                    );
    
                    $pdata1 = array(
                        'parent_id'=>$post['id'],
                        'mode' => 'Receipt',
                        'receipt_date' => db_date($post['receipt_date']),  
                        'account' => $post['account'],
                        'amount' =>  $post['amount'],
                        'update_at' =>  date('Y-m-d H:i:s'),
                        'update_by' =>  session('uid')
                    );
    
                    if(!empty($res)){
                        $builder->where(array("id" => $res[0]['id']));
                        $result = $builder->Update($pdata);
        
                        $builder->where(array("id" => $res[1]['id']));
                        $result = $builder->Update($pdata1);
                    }else{
                        $result = $builder->insert($pdata);
        
                        $result = $builder->insert($pdata1);
                    }

                }
                $builder = $db->table('bank_tras');

                if($result){
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

                if(isset($post['vch_id']) && !empty($post['vch_id'])){
                    for($i=0;$i<count($post['vch_id']);$i++)
                    {
                        $item_data[] = array(
                            'parent_id' => $id,
                            'vch_id' => $post['vch_id'][$i],
                            'payment_type' => $post['pay_type'],
                            'date' => db_date($post['receipt_date']),
                            'ac_id' => $post['ac_id'][$i],
                            'ac_name' => $post['ac_name'][$i],
                            'net_amt' => @$post['net_amt'][$i],
                            'total_paid' => $post['total_paid'][$i],
                            'vch_amt' => $post['vch_amt'][$i],
                            'voucher_name' => @$post['voucher_name'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    }
                    
                    $item_builder=$db->table('bank_cash_against');
                    $result1=$item_builder->insertBatch($item_data);
                }


                if(@$post['pay_type'] == 'contra'){

                    $builder = $db->table('contra_trans');

                    $pdata = array(
                        'parent_id' => $id, 
                        'mode' => 'Payment',
                        'receipt_date' => db_date($post['receipt_date']),  
                        'account' => $post['particular'], 
                        'amount' =>  $post['amount'],
                        'created_at' =>  date('Y-m-d H:i:s'),
                        'created_by' =>  session('uid')
                    );

                    $pdata1 = array(
                        'parent_id' => $id, 
                        'mode' => 'Receipt',
                        'receipt_date' => db_date($post['receipt_date']),  
                        'account' => $post['account'],
                        'amount' =>  $post['amount'],
                        'created_at' =>  date('Y-m-d H:i:s'),
                        'created_by' =>  session('uid')
                    );

                    $builder->insert($pdata);
                    $builder->insert($pdata1);
                }

                if(@$post['pay_type'] == 'bank'){
                    if(isset($post['checkno']) && @$post['checkno'] != ''){
                        $check= $post['checkno'] + 1;
                    }
                    if ($result) {
                        $gmodel= new GeneralModel();
                        if(isset($post['checkno']) && @$post['checkno'] != ''){
                            $res= $gmodel->get_data_table('check_range',array('bank_id'=>$post['account'] , 'chk_finish' =>'0'));
                            if(@$res['to_range'] == $post['checkno'] ){
                                $gmodel->update_data_table('check_range',array('bank_id'=>$post['account']),array('chk_finish' => 1 ));    
                            }else{
                                $gmodel->update_data_table('check_range',array('bank_id'=>$post['account']),array('used'=>$check));
                            }
                        }
                        $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    } else {
                        $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                    }
                }else{
                    if($result){
                        $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    }else{
                        $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                    }
                }
            }
        }

        return $msg;
    }

    public function insert_edit_contratrans($post)
    {
      
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('contra_trans');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        $pdata = array();
        $msg = array();
        
        $dt = date_create($post['receipt_date']);
        $date = date_format($dt,'Y-m-d');
        if(!empty($post['chk_date']))
        {
            $dt1 = date_create($post['chk_date']);
            $date1 = date_format($dt1,'Y-m-d');
        }
        else
        {
            $date1 = '0000-00-00';
        }

        $pdata = array(
            //'payment_type' => 'Contra',
            //'mode' => $post['mode'],
            'receipt_date' => $date,
            'account' => $post['account'],
            'checkno' => !empty($post['checkno']) ? $post['checkno'] : '',
            'chk_date' => $date1,
            'particular' => $post['particular'],
            'state' => !empty($post['state']) ? $post['state'] :'' ,
            'amount' => $post['amount'],
            'narration' => !empty($post['narration']) ? $post['narration'] : '',
          
        );

       

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            
            if(empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                $builder = $db->table('contra_trans');

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
            //print_r($pdata);exit;
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
        return $msg;
    }

    public function unlink_bank_reconsilation($from,$to,$ac){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 

        $builder = $db->table('bank_tras');
        $builder->set('recons_date', '');
        $builder->where('payment_type','bank');
        $builder->where('is_delete',0);
        $builder->where('account',$ac);
        $builder->where('DATE(receipt_date) >=',db_date($from));
        $builder->where('DATE(receipt_date) <=',db_date($to));
        $result = $builder->update();

        $whr = "(`account` = ".$ac." OR `particular` = ".$ac.")";

        $builder = $db->table('contra_trans');
        $builder->set('recons_date', '');
        $builder->where('is_delete',0);
        $builder->where('DATE(receipt_date) >=',db_date($from));
        $builder->where('DATE(receipt_date) <=',db_date($to));
        $builder->where('account',$ac);
        $result = $builder->update();

        // $builder=$db->table('bank_tras bt');
        // $builder->select('ct.id as ct_id,bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,ct.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
        // $builder->join('contra_trans ct','ct.parent_id = bt.id');
        // $builder->join('account ac','ac.id = ct.account');
        // $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($newdate)));
        // $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
        // $builder->where(array('bt.payment_type' => 'contra','bt.is_delete' => 0));
        // $builder->where(array('ct.recons_date' => ''));
        // $builder->where('ct.account',$account);
        // $builder->orderBy('bt.receipt_date','ASC');
        // $query=$builder->get();
        // $getresult1 = $query->getResultArray();

        
        
        if ($result) {
            $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
        } else {
            $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
        }
        return $msg;
    }

    public function unlink_single_reconsilation($post){
     
        $gmodel = new GeneralModel();

        $bank = $gmodel->get_data_table('bank_tras',array('id' =>$post['id']),'*');
        $data = array();
        // echo '<pre>';print_r($bank);exit;

        if($bank['payment_type'] == 'contra'){
            if($bank['cash_type'] == 'Fund Transfer'){
                $msg = $gmodel->update_data_table('contra_trans',array('id' =>$post['ct_id']),array('recons_date' => ''));
            }else{
                $msg = $gmodel->update_data_table('contra_trans',array('parent_id' =>$post['id']),array('recons_date' => ''));
            }
            $db = $this->db;  
            if(session('DatasSource')){
                $db->setDatabase(session('DataSource'));
            }
            $builder=$db->table('bank_tras bt');
            $builder->select('ct.id as ct_id,bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,ct.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
            $builder->join('contra_trans ct','ct.parent_id = bt.id');
            $builder->join('account ac','ac.id = ct.account');
            $builder->where(array('bt.payment_type' => 'contra','bt.is_delete' => 0));
            $builder->where(array('ct.recons_date' => ''));
            $builder->where('ct.id',$post['ct_id']);
            $query=$builder->get();
            $data = $query->getRowArray();

        }else{
            $msg = $gmodel->update_data_table('bank_tras',array('id' =>$post['id']),array('recons_date' => ''));
            $data = $gmodel->get_data_table('bank_tras',array('id'=>$post['id']),'*');

        }
        

        
        if ($msg) {
            $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully..!!!",'data'=>$data);
        } else {
            $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
        }
        return $msg;
    }

    public function insert_edit_checkrange($post)
    {
        
        if(!empty($post['bank'])){
            $gmodel= new GeneralModel();
            $chkrng_data = $gmodel->get_data_table('check_range',array('bank_id'=>$post['bank'],'chk_finish'=> 0));
            if(!empty($chkrng_data)){
                $msg = array('st' => 'fail', 'msg' => "CheckBook for This Bank was Already added");
                return $msg;
            }
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('check_range');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        $pdata = array(
            'bank_id' => $post['bank'],
            'from_range' => $post['from_range'],
            'to_range' => $post['to_range'],
            'total' => $post['total'],
            'used' => $post['from_range'],
        );
        
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            
            if(empty($msg)) {
                
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                $builder = $db->table('check_range');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!",'checkno'=>$post['from_range']);
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
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully...!!!",'checkno'=>$post['from_range']);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function insert_edit_jvparticular($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('jv_main');
        $builder->select('*');
        $builder->where(array("id" => @$post['jv_id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pdata = array(
            'date' => db_date($post['date']),
            'narration' => $post['narration'],
        );

        if (isset($post['stat_adj']) && $post['stat_adj'] == '1') {
            $pdata['stat_adj'] = $post['stat_adj'];
            $pdata['duty_tax'] = $post['duty_tax'];
            $pdata['adjust'] = $post['adjust'];
            $pdata['gst_detail'] = @$post['gst_detail'] ? $post['gst_detail'] : 0;
            $pdata['addi_detail'] = $post['addi_detail'];

            if (isset($post['gst_detail']) && $post['gst_detail'] == 1) {
                $pdata['gst_parti'] = $post['gst_parti'];
                $pdata['bank_tras'] = $post['bank_tras'];
                $pdata['supply'] = $post['supply'];
                $pdata['registration'] = $post['registration'];
                $pdata['party_type'] = $post['party_type'];
                $pdata['gst'] = $post['gst'];
            }
        }

        // echo '<pre>';print_r($post);exit;
        if (!empty($result_array)) {

            $builder = $db->table('jv_particular');
            $builder->select('*');
            $builder->where(array("jv_id" => @$post['jv_id']));

            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            $old_item = array();
            foreach ($result_array1 as $row) {
                $old_item[] = $row['id'];
            }
            $array_delete = array_diff($old_item, $post['item_id']);
            $array_insert = array_diff($post['item_id'], $old_item);

            $j = 0;
            $k = 0;
            for ($i = 0; $i < count($post['dr_cr']); $i++) {
                if (in_array($post['item_id'][$i], $old_item)) {
                    $data = array(
                        'jv_id' => $post['jv_id'],
                        'date' => db_date($post['date']),
                        'dr_cr' => $post['dr_cr'][$i],
                        'particular' => $post['particular'][$i],
                        'method' => $post['adj_method'][$i],
                        'amount' => $post['amount'][$i],
                        'other' => @$post['other'][$i] ? $post['other'][$i] : '',
                        'stat_adj' => @$post['stat_adj'] ? $post['stat_adj'] : 0,
                    );
                    if ($post['adj_method'][$i] == 'agains_reference') {
                        $data += array(
                            'invoice' => @$post['invoice'][$j],
                            'invoice_tb' => @$post['invoice_tb'][$j],
                        );
                        $j++;
                    }

                    if ($post['adj_method'][$i] == 'Advanced') {
                        $data += array(
                            'tax' => @$post['tax'][$k],
                            'advance_for' => @$post['advance_for'][$k],
                        );
                        $k++;
                    }
                    $builder = $db->table('jv_particular');
                    $builder->where(array("id" => $post['item_id'][$i]));
                    $result = $builder->Update($data);

                } else {
                    $data = array(
                        'jv_id' => $post['jv_id'],
                        'date' => db_date($post['date']),
                        'dr_cr' => $post['dr_cr'][$i],
                        'particular' => $post['particular'][$i],
                        'method' => $post['adj_method'][$i],
                        'amount' => $post['amount'][$i],
                        'other' => @$post['other'][$i] ? $post['other'][$i] : '',
                        'stat_adj' => @$post['stat_adj'] ? $post['stat_adj'] : 0,
                    );
                    if ($post['adj_method'][$i] == 'agains_reference') {
                        $data += array(
                            'invoice' => @$post['invoice'][$j],
                            'invoice_tb' => @$post['invoice_tb'][$j],
                        );
                        $j++;
                    }

                    if ($post['adj_method'][$i] == 'Advanced') {
                        $data += array(
                            'tax' => @$post['tax'][$k],
                            'advance_for' => @$post['advance_for'][$k],
                        );
                        $k++;
                    }
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['created_by'] = session('uid');
                    $builder = $db->table('jv_particular');
                    $result1 = $builder->Insert($data);
                    //}
                }
            }
            foreach ($array_delete as $row) {
                $data = array(
                    'is_delete' => 1,
                );
                $builder = $db->table('jv_particular');
                $builder->where(array("id" => $row));
                $result = $builder->Update($data);
            }

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder = $db->table('jv_main');
                $builder->where(array("id" => $post['jv_id']));
                $result = $builder->Update($pdata);

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');

            $result = $builder->Insert($pdata);

            $id = $db->insertID();
            $j = 0;
            $k = 0;
            for ($i = 0; $i < count($post['dr_cr']); $i++) {
                $data = array(
                    'jv_id' => $id,
                    'date' => db_date($post['date']),
                    'dr_cr' => $post['dr_cr'][$i],
                    'particular' => $post['particular'][$i],
                    'method' => $post['adj_method'][$i],
                    'amount' => $post['amount'][$i],
                    'other' => @$post['other'][$i] ? $post['other'][$i] : '',
                    'stat_adj' => @$post['stat_adj'] ? $post['stat_adj'] : 0,
                );
                if ($post['adj_method'][$i] == 'agains_reference') {
                    $data += array(
                        'invoice' => @$post['invoice'][$j],
                        'invoice_tb' => @$post['invoice_tb'][$j],
                    );
                    $j++;
                }

                if ($post['adj_method'][$i] == 'Advanced') {
                    $data += array(
                        'tax' => @$post['tax'][$k],
                        'advance_for' => @$post['advance_for'][$k],
                    );
                    $k++;
                }
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = session('uid');
                $builder = $db->table('jv_particular');
                $result1 = $builder->Insert($data);

            }

            if ($result and $result1) {
                $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }

        }
        return $msg;

    }
    
    public function get_banktrans_data($get,$post)
    {
        $dt_search = array(
            "bt.id",
            "bt.mode",
            "bt.receipt_date",
            "(select name from account ac where bt.particular = ac.id)",
            "(select name from account ac where bt.account = ac.id)",
            "bt.amount",
        );

        $dt_col = array(
            "bt.id",
            "bt.mode",
            "bt.receipt_date",
            "bt.payment_type",
            "bt.account",
            "(select name from account ac where bt.account = ac.id) as account_name",
            "bt.particular",
            "(select name from account ac where bt.particular = ac.id) as particular_name",
            "bt.amount",
            "bt.adj_method",
            "bt.narration",
        );

        $filter = $get['filter_data'];
        $tablename = "bank_tras bt";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";
        $where .= " and (payment_type='bank' or payment_type='contra') ";

        if(!empty(@$post['from'])){
            $where .= ' and DATE(bt.receipt_date)  >="'.  db_date($post['from']).'"';
        }

        if(!empty(@$post['to'])){
            $where .= ' and DATE(bt.receipt_date)  <="'. db_date($post['to']).'"';
        }

        if(!empty(@$post['account'])){
            $where .= " and bt.account  =". $post['account'];
        }

        if(!empty(@$post['parti'])){
            $where .= " and bt.particular  =". $post['parti'];
        }

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        //$statusarray = array("1" => "Activate", "0" => "Deactivate");

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            if($row['payment_type'] == 'contra'){
                $btnedit = '<a   href="' . url('Bank/add_contratrans/') . $row['id'] . '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            }else{
                $btnedit = '<a   href="' . url('Bank/add_banktrans/') . $row['id'] . '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            }
            // $btnedit = '<a   href="' . url('Bank/add_banktrans/') . $row['id'] . '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Receipt No: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            //  $status= '<a  tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
            $btn = $btnedit . $btndelete;

            $DataRow[] = $row['id'];
            $DataRow[] = ($row['payment_type'] == 'contra') ? 'Contra' : $row['mode'];
            $DataRow[] = user_date($row['receipt_date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['particular_name'];
            $DataRow[] = $row['amount'];
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_jvparticular_data($get){
        $dt_search =array(
            "jv.id",
            "jv.date"
          
        );  
        $dt_col = array(
            "jv.id",
            "jv.date"
        );
    
        $filter = $get['filter_data'];
        $tablename = "jv_main jv";
        $where = '';
        
        $where .= " and is_delete=0";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
        $gmodel = new GeneralModel();
        foreach ($rResult['table'] as $row) {

            $db = $this->db;
            $builder = $db->table('jv_particular');
            $builder->select('*');
            $builder->where(array("jv_id" => $row['id']));
            $result = $builder->get();
            $result_array = $result->getResultArray();
            $total_jv_amt = 0;
            foreach($result_array as $row1)
            {
                //$particulart_id = $row['id'];
                if($row1['dr_cr'] == 'cr')
                {
                    $total_jv_amt += $row1['amount'];
                }
            }
            //$particular = $gmodel->get_data_table('account',array('id'=>$particulart_id),'name');

            $DataRow = array();
            $btnedit = '<a href="' . url('Bank/add_jvparticular/').$row['id']. '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Receipt No: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btn = $btnedit . $btndelete;
    
            $DataRow[] = $row['id'];
            $DataRow[] = user_date($row['date']);
            //$DataRow[] = @$particular['name'];
            $DataRow[] =  $total_jv_amt;
            $DataRow[] = $btn;
    
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    // public function get_jvparticular_data($get){
    //     $dt_search =array(
    //         "jv.id",
            
    //      //   "jv.voucher_no",
    //         "jv.date",
    //         "jv.dr_cr",
    //         "jv.particular",
    //         "jv.method",
    //         "jv.amount",
    //         "jv.dr_cr",
    //     );  
    //     $dt_col = array(
    //         "jv.id",
    //         "jv.jv_id",
    //     //    "jv.voucher_no",
    //         "jv.date",
    //         "jv.dr_cr",
    //         "jv.particular",
    //         "(select name from account ac where jv.particular = ac.id) as particular_name",
    //         "jv.method",
    //         "jv.amount",
    //         "jv.dr_cr",
    //     );
    
    //     $filter = $get['filter_data'];
    //     $tablename = "jv_particular jv";
    //     $where = '';
    //     // if ($filter != '' && $filter != 'undefined') {
    //     //     $where .= ' and UserType ="' . $filter . '"';
    //     // }
    //     $where .= " and is_delete=0";
    
    //     $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    //     $sEcho = $rResult['draw'];
    
    //     $encode = array(); 
    //     //$statusarray = array("1" => "Activate", "0" => "Deactivate");
    
    //     foreach ($rResult['table'] as $row) {
    //         $DataRow = array();
    //         $btnedit = '<a   href="' . url('Bank/add_jvparticular/').$row['jv_id']. '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
    //      //   $btnview = '<a href="' . url('bank/banktrans_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
    //         $btndelete = '<a data-toggle="modal" target="_blank"   title="Receipt No: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
    //       //  $status= '<a  tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
    //         $btn = $btnedit . $btndelete;
    
    //         $DataRow[] = $row['id'];
    //         $DataRow[] = $row['date'];
    //         $DataRow[] = $row['particular_name'];
    //         $DataRow[] = $row['dr_cr'];
    //         $DataRow[] = $row['amount'];
    //         $DataRow[] = $btn;
    
    //         $encode[] = $DataRow;
    //     }
    
    //     $json = json_encode($encode);
    //     echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    //     exit;
    // }

    public function get_cashtrans_data($get){
        $dt_search =array(
            "ct.id",
            "ct.mode",
            "ct.receipt_date",
            //"ct.account",
            "ct.particular",
            "ct.amount",
            "ct.adj_method",
           
            "ct.narration",
        );  
        $dt_col = array(
            "ct.id",
            "ct.mode",
            "ct.receipt_date",
            //"ct.account",
            //"(select name from account ac where ct.account = ac.id) as account_name",
            "ct.particular",
            "(select name from account ac where ct.particular = ac.id) as particular_name",
            "ct.amount",
            "ct.adj_method",
            "ct.narration",
        );
    
        $filter = $get['filter_data'];
        $tablename = "bank_tras ct";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";
        $where .= " and  payment_type = 'cash' ";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
        //$statusarray = array("1" => "Activate", "0" => "Deactivate");
    
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $btnedit = '<a   href="' . url('Bank/add_cashtrans/').$row['id']. '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Receipt No: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
          // $status = '<a tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
            $btn = $btnedit . $btndelete;
    
            $DataRow[] = $row['id'];
            $DataRow[] = $row['mode'];
            $DataRow[] = $row['receipt_date'];
            //$DataRow[] = $row['account_name'];
            $DataRow[] = $row['particular_name'];
            $DataRow[] = $row['amount'];
            $DataRow[] = $btn;
    
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_contratrans_data($get){
        $dt_search =array(
            "ct.id",
            "ct.receipt_date",
            "ct.account",
            "ct.particular",
            "ct.amount",
            "ct.cash_type",
        );

        $dt_col = array(
            "ct.id",
            "ct.receipt_date",
            "ct.account",
            "(select name from account ac where ct.account = ac.id) as account_name",
            "ct.particular",
            "(select name from account ac where ct.particular = ac.id) as particular_name",
            "ct.amount",
            "ct.cash_type",
        );
    
        $filter = $get['filter_data'];
        $tablename = "bank_tras ct";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";
        $where .= " and  payment_type = 'contra' ";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
        //$statusarray = array("1" => "Activate", "0" => "Deactivate");
    
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $btnedit = '<a   href="' . url('Bank/add_contratrans/').$row['id']. '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Receipt No: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
          //  $status= '<a  tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
            $btn = $btnedit . $btndelete;
    
            $DataRow[] = $row['id'];
            //$DataRow[] = $row['mode'];
            $DataRow[] = user_date($row['receipt_date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['particular_name'];
            $DataRow[] = $row['amount'];
            $DataRow[] = '<b>'.$row['cash_type'].'</b>';
            $DataRow[] = $btn;
    
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    // public function get_bank_opening(){

    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource')); 

    //     $builder = $db->table('bank_tras');
    //     $builder->select('*');
    //     $builder->where('payment_type','bank');
    //     $builder->where('is_delete',0);
    //     $builder->where('recons_date !=', '');
    //     $result = $builder->get();
    //     $result_array = $result->getResultArray();
    //     // echo $db->getLastQuery();exit;
    //     $total = 0;
        
        
        
    //     foreach($result_array as $row){
    //         if($row['mode'] == 'Payment'){
    //             $total -= $row['amount'];
    //             // echo '<br>Payment'; print_r($row['amount']);
    //         }
    //         if($row['mode'] == 'Receipt'){
    //             $total += $row['amount'];
    //             // echo '<br>Receipt'; print_r($row['amount']);
    //         }
    //     }
    //     return $total;
    // }

    public function get_banktrans_data_byid($id)
    {
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('bank_tras');
        $builder->select('*');
        $builder->where(array('id' => $id));
        $query=$builder->get();
        
        $result=$query->getResultArray();
        $gmodel = new GeneralModel();
        $re = array();
        // echo '<pre>';print_r($result);exit;
        $gmodel= new GeneralModel();

        $bill_data = $gmodel->get_array_table('bank_cash_against',array('parent_id'=>$id,'is_delete'=>0),'*');

        
        foreach ($result as $key => $value) {
            $getAccount = $gmodel->get_data_table('account',array('id' =>$value['account'] ),'name');
            $getParty = $gmodel->get_data_table('account',array('id' =>$value['particular'] ),'name');
            $getItem = $gmodel->get_data_table('item',array('id' =>$value['item'] ),'name');
            // $getBank = $gmodel->get_data_table('account',array('id' =>$value['bank'] ),'name');
            
            if(!empty($value['invoice_tb'])){
                if($value['invoice_tb'] == 'sales_invoice')
                {
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'sales_invoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
                    $sale_return = $gmodel->get_data_table('sales_return',array('invoice'=>@$value['invoice']),'SUM(net_amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('sales_invoice si');
                    $builder->select('si.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = si.account');
                    $builder->where(array('si.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();
                    
                    
                    $getinvoice =  '('.@$value['invoice'].') - '.@$invocie['invoice_date'].'-'.@$invocie['invoice_name'] .' - ₹'.(@$invocie['net_amount'] - @$sale_return['total']).'/'.(@$invocie['net_amount'] - @$total_paid['total'] - @$sale_return['total']).' - Item Invocie' ;
                    // echo '<pre>';print_r($getinvoice);exit;
                }else if($value['invoice_tb'] == 'sales_ACinvoice'){
            
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'sales_ACinvoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
                    $sale_return = $gmodel->get_data_table('sales_ACinvoice',array('return_sale'=>@$value['invoice']),'SUM(net_amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('sales_ACinvoice si');
                    $builder->select('si.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = si.account');
                    $builder->where(array('si.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();

                    $getinvoice = '('.@$value['invoice'].') - '.@$invocie['invoice_date'].'-'.@$invocie['invoice_name'] .' - ₹'.(@$invocie['net_amount'] - @$sale_return['total']).'/'.(@$invocie['net_amount'] - @$total_paid['total'] - @$sale_return['total']).' -  General Sales';
                    
                }
                else if($value['invoice_tb'] == 'purchase_general'){
            
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'purchase_general','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
                    $purchase_return = $gmodel->get_data_table('purchase_general',array('return_purchase'=>@$value['invoice']),'SUM(net_amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('purchase_general pg');
                    $builder->select('pg.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = pg.party_account');
                    $builder->where(array('pg.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();

                    $getinvoice =  '('.@$value['invoice'].') - '.@$invocie['doc_date'].'-'.@$invocie['invoice_name'] .' - ₹'.(@$invocie['net_amount'] - @$purchase_return['total']).'/'.(@$invocie['net_amount'] - @$total_paid['total'] - @$purchase_return['total']).' -  General Purchase';
                    
                }else{
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'purchase_invoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
                    $purchase_return = $gmodel->get_data_table('purchase_return',array('invoice'=>@$value['invoice']),'SUM(net_amount) as total'); 

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('purchase_invoice pi');
                    $builder->select('pi.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = pi.account');
                    $builder->where(array('pi.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();
                    
                    $getinvoice =  '('.@$value['invoice'].') - '.@$invocie['invoice_date'].'-'.@$invocie['invoice_name'] .' - ₹'.(@$invocie['net_amount'] - @$purchase_return['total']).'/'.(@$invocie['net_amount'] - $total_paid['total'] - $purchase_return['total']).' -  Purchase Invocie';
                }
            }
        }
        $value['particular_name'] = @$getParty['name'];
        $value['account_name'] = @$getAccount['name'];
        // $value['bank_name'] = @$getBank['name'];
        $value['invoice_name'] = @$getinvoice;
        $value['item_name'] = @$getItem['name'];
        $value['bill'] = @$bill_data;
        $re[0] = $value;
        // echo '<pre>';print_r($re);exit;
        return $re[0];
    }

    public function get_jvparticular_data_byid($id)
    {
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('jv_main');
        $builder->select('*');
        $builder->where(array('id' => $id));
        $query1=$builder->get();
        $result1=$query1->getRowArray();

        $builder=$db->table('jv_particular');
        $builder->select('id as item_id,jv_id,dr_cr,particular,method,amount,invoice,invoice_tb,advance_for,tax');
        $builder->where(array('jv_id' => $id,'is_delete'=>0));
        $query=$builder->get();
        $result=$query->getResultArray();

        $gmodel = new GeneralModel();
        $re = array();

        foreach ($result as $key => $value) {
          
            $getParty = $gmodel->get_data_table('account',array('id' =>$value['particular'] ),'name');
            $getAdvance = $gmodel->get_data_table('account',array('id' =>$value['advance_for'] ),'name');
            $getgst_parti = $gmodel->get_data_table('account',array('id' =>$result1['gst_parti'] ),'name');

            if(!empty($value['invoice_tb'])){
                if($value['invoice_tb'] == 'sales_invoice')
                {
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'sales_invoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
                    
                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('sales_invoice si');
                    $builder->select('si.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = si.account');
                    $builder->where(array('si.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();
                    
                    
                    $getinvoice =  '('.$value['invoice'].') - '.$invocie['invoice_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' - Item Invocie' ;
                    // echo '<pre>';print_r($getinvoice);exit;
                }else if($value['invoice_tb'] == 'sales_ACinvoice'){
            
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'sales_ACinvoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('sales_ACinvoice si');
                    $builder->select('si.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = si.party_account');
                    $builder->where(array('si.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();
                    

                    $getinvoice = '('.@$value['invoice'].') - '.@$invocie['invoice_date'].'-'.@$invocie['invoice_name'] .' - ₹'.@$invocie['net_amount'].'/'.(@$invocie['net_amount'] - @$total_paid['total']).' -  General Sales';
                    
                }
                else if($value['invoice_tb'] == 'purchase_general'){
            
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'purchase_general','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('purchase_general pg');
                    $builder->select('pg.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = pg.party_account');
                    $builder->where(array('pg.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();

                    $getinvoice =  '('.$value['invoice'].') - '.$invocie['doc_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' -  General Purchase';
                    
                }else{
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'purchase_invoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('purchase_invoice pi');
                    $builder->select('pi.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = pi.account');
                    $builder->where(array('pi.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();
                    
                    $getinvoice =  '('.$value['invoice'].') - '.$invocie['invoice_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' -  Purchase Invocie';
                }
            } 
           
            $value['particular_name'] = @$getParty['name'];
            $value['advance_name'] = @$getAdvance['name'];
            $value['gst_parti_name'] = @$getgst_parti['name'];
            $value['invoice_name'] = @$getinvoice;
            
            $re[] = $value;
        }
        $data['detail'] = $result1;
        $data['item'] = $re;
        
        return $data;
    }

    

    public function get_cashtrans_data_byid($id)
    {
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('bank_tras');
        $builder->select('*');
        $builder->where(array('id' => $id));
        $query=$builder->get();
        
        $result=$query->getResultArray();
        $gmodel = new GeneralModel();
        $re = array();
        foreach ($result as $key => $value) {
            $getAccount = $gmodel->get_data_table('account',array('id' =>$value['account'] ),'name');
            $getParty = $gmodel->get_data_table('account',array('id' =>$value['particular'] ),'name');

            if(!empty($value['invoice_tb'])){
                if($value['invoice_tb'] == 'sales_invoice')
                {
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'sales_invoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('sales_invoice si');
                    $builder->select('si.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = si.account');
                    $builder->where(array('si.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();

                    $getinvoice =  '('.$value['invoice'].') - '.$invocie['invoice_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' - Item Invocie' ;
                    // echo '<pre>';print_r($getinvoice);exit;
                }else if($value['invoice_tb'] == 'sales_ACinvoice'){
            
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'sales_ACinvoice','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('sales_ACinvoice si');
                    $builder->select('si.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = si.account');
                    $builder->where(array('si.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();

                    $getinvoice = '('.$value['invoice'].') - '.$invocie['invoice_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' -  General Sales';
                    
                }
                else if($value['invoice_tb'] == 'purchase_general'){
            
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'purchase_general','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('purchase_general pg');
                    $builder->select('pg.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = pg.party_account');
                    $builder->where(array('pg.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();

                    $getinvoice =  '('.$value['invoice'].') - '.$invocie['doc_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' -  General Purchase';
                    
                }else{
                    $whr = array('invoice' =>@$value['invoice'] , 'invoice_tb'=>'purchase_general','is_delete' => '0' );
                    $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');

                    $db->setDatabase(session('DataSource'));
                    $builder=$db->table('purchase_invoice pi');
                    $builder->select('pi.*,ac.name as invoice_name');
                    $builder->join('account ac','ac.id = pi.account');
                    $builder->where(array('pi.id' => @$value['invoice']));
                    $builder->limit(1);
                    $query=$builder->get();
                    $invocie  = $result=$query->getRowarray();
                    
                    $getinvoice =  '('.$value['invoice'].') - '.$invocie['invoice_date'].'-'.$invocie['invoice_name'] .' - ₹'.$invocie['net_amount'].'/'.($invocie['net_amount'] - $total_paid['total']).' -  Purchase Invocie';
                }   
            }

            $value['particular_name'] = @$getParty['name'];
            $value['invoice_name'] = @$getinvoice;
            $value['account_name'] = @$getAccount['name'];
          
            $re[0] = $value;
        }
        // echo '<pre>';print_r($re);exit;
        return $re[0];
    }
    public function get_contratrans_data_byid($id)
    {
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('bank_tras');
        $builder->select('*');
        $builder->where(array('id' => $id));
        $query=$builder->get();
        $result=$query->getResultArray();

        $gmodel = new GeneralModel();
        $re = array();
        
        foreach ($result as $key => $value) {
            $getAccount = $gmodel->get_data_table('account',array('id' =>$value['account'] ),'name');
            $getParty = $gmodel->get_data_table('account',array('id' =>$value['particular'] ),'name');

            $value['particular_name'] = @$getParty['name'];
            $value['account_name'] = @$getAccount['name'];
          
            $re[0] = $value;
        }

        return $re[0];
    }

    public function get_invoice_databyid($post){
        
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('sales_invoice si');
        $builder->select('si.*,ac.name as party_name');
        $builder->join('account ac','ac.id = si.account');
        $builder->where(array('si.account' => $post['id']));
        if(@$post['searchTerm'] != ''){
            $builder->where(array('si.id' => @$post['searchTerm']));
        }
        $builder->orderBy('si.id','asc');
        $query=$builder->get();
        $sale_ITMinvoice=$query->getResultArray();


        $builder=$db->table('sales_ACinvoice sa');
        $builder->select('sa.*,ac.name as party_name');
        $builder->join('account ac','ac.id = sa.party_account');
        $builder->where(array('sa.party_account' => $post['id'])); 
        $builder->where(array('sa.v_type' => 'general')); 
        if(@$post['searchTerm'] != ''){
            $builder->where(array('sa.id' => @$post['searchTerm']));
        }
        $builder->orderBy('sa.id','asc');
        $builder->limit(5);
        $query=$builder->get();
        $sale_ACinvoice=$query->getResultArray();
        
        $builder=$db->table('purchase_invoice pi');
        $builder->select('pi.*,ac.name as party_name');
        $builder->join('account ac','ac.id = pi.account');
        $builder->where(array('pi.account' => $post['id']));
        if(@$post['searchTerm'] != ''){
            $builder->where(array('pi.id' => @$post['searchTerm']));
        }
        $builder->orderBy('pi.id','asc');
        $query=$builder->get();
        $purchase_invoice=$query->getResultArray();
          
        $builder=$db->table('purchase_general pg');
        $builder->select('pg.*,ac.name as party_name');
        $builder->join('account ac','ac.id = pg.party_account');
        $builder->where(array('pg.party_account' => $post['id']));
        $builder->where(array('pg.v_type' => 'general'));
        if(@$post['searchTerm'] != ''){
            $builder->where(array('pg.id' => @$post['searchTerm']));
        }
        $builder->orderBy('pg.id','asc');
        $query=$builder->get();
        $purchase_general=$query->getResultArray();


        // $builder=$db->table('grey g');
        // $builder->select('g.*,ac.name as party_name');
        // $builder->join('account ac','ac.id = g.party_name');
        // $builder->where(array('g.party_name' => $post['id']));
        // $builder->where(array('g.purchase_type' => 'Gray'));
        // if(@$post['searchTerm'] != ''){
        //     $builder->where(array('g.id' => @$post['searchTerm']));
        // }
        // $builder->orderBy('g.id','desc');
        // $query=$builder->get();
        // $gray_purchase = $query->getResultArray();


        // $builder=$db->table('grey g');
        // $builder->select('g.*,ac.name as party_name');
        // $builder->join('account ac','ac.id = g.party_name');
        // $builder->where(array('g.party_name' => $post['id']));
        // $builder->where(array('g.purchase_type' => 'Finish'));
        // if(@$post['searchTerm'] != ''){
        //     $builder->where(array('g.id' => @$post['searchTerm']));
        // }
        // $builder->orderBy('g.id','desc');
        
        // $query=$builder->get();
        // $finish_purchase = $query->getResultArray();

        // $builder=$db->table('saleMillInvoice sm');
        // $builder->select('sm.*,ac.name as party_name');
        // $builder->join('account ac','ac.id = sm.account');
        // $builder->where(array('sm.account' => $post['id']));
        // $builder->where(array('sm.item_type' => 'Gray'));
        // if(@$post['searchTerm'] != ''){
        //     $builder->where(array('sm.id' => @$post['searchTerm']));
        // }
        // $builder->orderBy('sm.id','desc');
        // $query=$builder->get();
        // $gray_sale = $query->getResultArray();

        // $builder=$db->table('saleMillInvoice sm');
        // $builder->select('sm.*,ac.name as party_name');
        // $builder->join('account ac','ac.id = sm.account');
        // $builder->where(array('sm.account' => $post['id']));
        // $builder->where(array('sm.item_type' => 'Finish'));
        // if(@$post['searchTerm'] != ''){
        //     $builder->where(array('sm.id' => @$post['searchTerm']));
        // }
        // $builder->orderBy('sm.id','desc');  
        // $query=$builder->get();
        // $finish_sale = $query->getResultArray();

        
        $itemcount=count($sale_ITMinvoice);
        $ac_count=count($sale_ACinvoice);
        $pur_count=count($purchase_invoice);
        $gn_count=count($purchase_general);
        
        $data =array();
        $gmodel= new GeneralModel();


        foreach($sale_ACinvoice as $row){
            
            $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'General Sale','is_delete' => '0' );
            $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
            $sale_return = $gmodel->get_data_table('sales_ACinvoice',array('return_sale'=>$row['id']),'SUM(net_amount) as total');
             
            $text = '('.$row['id'] .') -'.user_date($row['invoice_date']).'-'.$row['account_name'] .' - ₹'.($row['net_amount'] - @$sale_return['total']).'/'.($row['net_amount'] - $total_paid['total'] - @$sale_return['total'] ) .' - General Sale';
            $row['voucher_name'] = 'General Sale';
            $row['total_paid'] = @$total_paid['total'] ? $total_paid['total']  : 0;
            $data[] = array(
                'id'=>$row['id'],
                'text'=>$text,
                'table'=>'sales_ACinvoice',
                'data' => $row
            );
        }
       
        
        foreach($sale_ITMinvoice as $row){

            $whr = array('vch_id' =>$row['id'] , 'voucher_name' => 'Sale Invoice','is_delete' => '0');
            $total_paid = $gmodel->get_data_table('bank_cash_against',$whr,'SUM(vch_amt) as total');
            $sale_return = $gmodel->get_data_table('sales_return',array('invoice'=>$row['id']),'SUM(net_amount) as total');

            $custom_inv = !empty(@$row['custom_inv_no']) ? ' - '.$row['custom_inv_no'].'-' :  '';

            $text = '('.$row['id'] .')'.$custom_inv.user_date($row['invoice_date']).'-'.$row['party_name'].' - ₹'.($row['net_amount']).'/'.($row['net_amount'] - $total_paid['total'] ) .' - Sale Invoice';

            $row['voucher_name'] = 'Sale Invoice';
            $row['total_paid'] = @$total_paid['total'] ? $total_paid['total']  : 0;

            $data[] = array(
                'id'=>$row['id'],
                'text'=>$text,
                'table'=>'sales_invoice',
                'data' => $row
            ); 
        }

        foreach($purchase_invoice as $row){

            $whr = array('vch_id' =>$row['id'], 'voucher_name'=>'Purchase Invoice','is_delete' => '0');
            $total_paid = $gmodel->get_data_table('bank_cash_against',$whr,'SUM(vch_amt) as total');
            
            $purchase_return = $gmodel->get_data_table('purchase_return',array('invoice'=>$row['id']),'SUM(net_amount) as total');
            $custom_inv = !empty(@$row['custom_inv_no']) ? ' - '.$row['custom_inv_no'].'-' :  '';
            
            $text = '('.$row['invoice_no'] .')'.$custom_inv.user_date($row['invoice_date']).'-'.$row['party_name'] .' - ₹'.($row['net_amount'] ).'/'.($row['net_amount'] - $total_paid['total']) .' - Purchase Invoice';

            $row['voucher_name'] = 'Purchase Invoice';
            $row['total_paid'] = @$total_paid['total'] ? $total_paid['total']  : 0;

            $data[] = array(
                'id'=>$row['id'],
                'text'=>$text,
                'table'=>'purchase_invoice',
                'data' => $row
            );
        }
        
        foreach($purchase_general as $row){

            $whr = array('vch_id' =>$row['id'] , 'voucher_name'=>'General Purchase','is_delete' => '0');
            $total_paid = $gmodel->get_data_table('bank_cash_against',$whr,'SUM(vch_amt) as total');

            $purchase_return = $gmodel->get_data_table('purchase_general',array('return_purchase'=>$row['id']),'SUM(net_amount) as total');
            $text = '('.$row['invoice_no'] .') - '.user_date($row['doc_date']).'-'.$row['party_name'].' - ₹'.($row['net_amount']).'/'.($row['net_amount'] - $total_paid['total']) .' - General Purchase';
            
            $row['voucher_name'] = 'General Purchase';
            $row['total_paid'] = @$total_paid['total'] ? $total_paid['total']  : 0;

            $data[] = array(
                'id'=>$row['id'],
                'text'=>$text,
                'table'=>'purchase_general',
                'data' => $row

            ); 
        }

        // foreach($gray_purchase as $row){
        //     $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'Grey_Purchase','is_delete' => '0' );
        //     $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
        //     $purchase_return = $gmodel->get_data_table('retGrayFinish',array('weaver_invoice'=>$row['id'],'purchase_type'=>'Gray'),'SUM(net_amount) as total');
        //     // echo $db->getLastQuery();
        //     $text = '('.$row['id'] .') - '.user_date($row['inv_date']).'-'.$row['party_name'].' - ₹'.($row['net_amount'] - @$purchase_return['total']).'/'.($row['net_amount'] - $total_paid['total'] - @$purchase_return['total'] ) .' - Gray Issue';
        //     $row['voucher_name'] = 'Gray Issue';

        //     $data[] = array(
        //         'id'=>$row['id'],
        //         'text'=>$text,
        //         'table'=>'Grey_Purchase',
        //         'data' => $row

        //     ); 
        // }

        // foreach($finish_purchase as $row){
        //     $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'Finish_Purchase','is_delete' => '0' );
        //     $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
        //     $purchase_return = $gmodel->get_data_table('retGrayFinish',array('weaver_invoice'=>$row['id'],'purchase_type'=>'Finish'),'SUM(net_amount) as total');
        //     // echo $db->getLastQuery();
        //     $text = '('.$row['id'] .') - '.user_date($row['inv_date']).'-'.$row['party_name'].' - ₹'.($row['net_amount'] - @$purchase_return['total']).'/'.($row['net_amount'] - $total_paid['total'] - @$purchase_return['total'] ) .' - Finish Issue';
        //     $row['voucher_name'] = 'Finish Issue';

        //     $data[] = array(
        //         'id'=>$row['id'],
        //         'text'=>$text,
        //         'table'=>'Finish_Purchase',
        //         'data' => $row

        //     ); 
        // }

        // foreach($gray_sale as $row){
        //     $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'Grey_Sale','is_delete' => '0' );
        //     $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
        //     $purchase_return = $gmodel->get_data_table('saleMillReturn',array('invoice_no'=>$row['id'],'item_type'=>'Gray'),'SUM(net_amount) as total');
            
        //     $text = '('.$row['id'] .') - '.user_date($row['date']).'-'.$row['party_name'].' - ₹'.($row['net_amount'] - @$purchase_return['total']).'/'.($row['net_amount'] - $total_paid['total'] - @$purchase_return['total'] ) .' - Gray Sale';
        //     $row['voucher_name'] = 'Gray Sale';

        //     $data[] = array(
        //         'id'=>$row['id'],
        //         'text'=>$text,
        //         'table'=>'Grey_Sale',
        //         'data' => $row

        //     ); 
        // }

        // foreach($finish_sale as $row){
        //     $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'Finish_Sale','is_delete' => '0' );
        //     $total_paid = $gmodel->get_data_table('bank_tras',$whr,'SUM(amount) as total');
        //     $purchase_return = $gmodel->get_data_table('saleMillReturn',array('invoice_no'=>$row['id'],'item_type'=>'Finish'),'SUM(net_amount) as total');
            
        //     $text = '('.$row['id'] .') - '.user_date($row['date']).'-'.$row['party_name'].' - ₹'.($row['net_amount'] - @$purchase_return['total']).'/'.($row['net_amount'] - $total_paid['total'] - @$purchase_return['total'] ) .' - Finish Sale';
        //     $row['voucher_name'] = 'Finish Sale';

        //     $data[] = array(
        //         'id'=>$row['id'],
        //         'text'=>$text,
        //         'table'=>'Finish_Sale',
        //         'data' => $row

        //     ); 
        // }

        return $data; 
    }

    public function UpdateData($post) {
        $result = array();
        $db = $this->db;
      
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'banktrans') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('bank_tras', array('id' => $post['pk']), array('is_delete' => '1'));
                $result = $gnmodel->update_data_table('bank_cash_against', array('parent_id' => $post['pk']), array('is_delete' => '1'));

            }
        } 
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'cashtrans') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('bank_tras', array('id' => $post['pk']), array('is_delete' => '1'));
                $result = $gnmodel->update_data_table('bank_cash_against', array('parent_id' => $post['pk']), array('is_delete' => '1'));

            }
        }
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'jvparticular') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('jv_main', array('id' => $post['pk']), array('is_delete' => '1'));
                $result = $gnmodel->update_data_table('jv_particular', array('jv_id' => $post['pk']), array('is_delete' => '1'));
            }
        }  
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'contratrans') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('bank_tras', array('id' => $post['pk']), array('is_delete' => 1));
                $result = $gnmodel->update_data_table('contra_trans', array('parent_id' => $post['pk']), array('is_delete' => 1));

            }
        }     
        return $result;
    }

    // public function get_master_data($method, $id) {
    //     $gnmodel = new GeneralModel();
    //     if ($method == 'bankreceipt') {
    //         $result['bankreceipt'] = $gnmodel->get_data_table('bank_receipt', array('id' => $id));
    //     }
    //     return $result;
    // }  

    public function load_sales_excel($post){
        
        $inputfilename = $_FILES['excel'];
        $response = uploadMultiFiles('excel', 'reconsilation');
        
        if($response['is_success']){
        $inputfilename = getcwd().$response['fileName'];
        $exceldata = array();
        try {
            $inputfiletype = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputfilename);
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputfiletype);
            $objPHPExcel = $objReader->load($inputfilename);
        }catch(Exception $e) {
            die('Error loading file "' . pathinfo($inputfilename, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        echo '<pre>';print_r($objPHPExcel->getSheet(0));exit;
        // Loop through each row of the worksheet in turn
        $firstRow = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
        $data = array();
        $product_error = $category_error = $market_error = '';
        $val_check = validate_excel_format($firstRow,'BulkSale',0);
        if ($val_check['st'] == 'success' && $highestRow <= 301) {
        for ($row = 2; $row <= $highestRow; $row++) {
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
        if (!empty($rowData[0])) {
        $i = 0;
        foreach ($rowData[0] as $rowName) {
        $rowNames[strtolower($firstRow[0][$i])] = $rowName;
        $i++;
        }
        }
        $sku = explode('-',$rowNames['sku']);
        $new_sku = '';
        if(isset($sku[1])){
        $sku = substr($sku[1],-10);
        $add_chr = 10 - strlen($sku);
        for($i=0;$i<$add_chr;$i++){
        $new_sku .= '0';
        }
        $new_sku .= $sku;
        } else {
        $sku = substr($sku[0],-10);
        $add_chr = 10 - strlen($sku);
        for($i=0;$i<$add_chr;$i++){
        $new_sku .= '0';
        }
        $new_sku .= $sku;
        }
        
        $gmodel = new GeneralModel();
        $getProduct = $gmodel->get_data_table('product',array('sku' => $rowNames['sku'],'asin' => $new_sku,'account' => $rowNames['account']),'id,asin,sku_code,listed_price,pro_category,pro_wt_lbs');
        
        if(!empty($getProduct)){
        $getCategory = $gmodel->get_data_table('category',array('category_cd' => $getProduct['pro_category'],'market' => $rowNames['market']),'customs,tax,amazon_comm,freight');
        if(!empty($getCategory)){
        $getMarket = $gmodel->get_data_table('market',array('market' => $rowNames['market']),'currency_rate,currency_code');
        if(!empty($getMarket)){
        
        $listed_price = ($rowNames['buyer_price'] == '' && !is_numeric($rowNames['buyer_price'])) ? $getProduct['listed_price'] : $rowNames['buyer_price'];
        if($getMarket['currency_code'] == 'INR'){
        $buy_price = $listed_price;
        }else if($getMarket['currency_code'] == 'USD'){
        $getIndMarket = $gmodel->get_data_table('market',array('market' => 'IN'),'currency_rate');
        $buy_price = ($listed_price * $getIndMarket['currency_rate']);
        }else{
        $buy_price = ($listed_price * $getMarket['currency_rate']);
        }
        $amz_fees = $listed_price * ($getCategory['amazon_comm'] / 100);
        
        $gst = ($rowNames['tax'] == '' && !is_numeric($rowNames['tax'])) ? $getCategory['tax'] : $rowNames['tax'];
        $amz_fees = ($rowNames['amz_fees'] == '' && !is_numeric($rowNames['amz_fees'])) ? $amz_fees : $rowNames['amz_fees'];
        $customs = $getCategory['customs'];
        $weight = $getProduct['pro_wt_lbs'];
        $freight = $getCategory['freight'];
        //$gst = $buy_price * ($tax / 100);
        $earnings = (floatval($buy_price) + floatval($gst)) - floatval($amz_fees);
        //$earnings = floatval($buy_price) - floatval($amz_fees);
        
        $earnings_dollar = floatval($earnings) / floatval($getMarket['currency_rate']);
        $gst_dollar = floatval($gst) / floatval($getMarket['currency_rate']);
        
        $Wgt_Chg = (floatval($freight) * floatval($weight));
        $custom_chr = $earnings_dollar * ($customs / 100);
        $pur_price = $earnings_dollar - $gst_dollar - $custom_chr - $Wgt_Chg;
        $order_dt = date_create($rowNames['order_dt']);
        
        $where = array('asin' => $getProduct['asin'] .'','market' => $rowNames['market'],'account' => $rowNames['account']);
        $getStock = $gmodel->get_data_table('stock',$where);
        if(!empty($getStock)){
        if(intval($rowNames['qty']) <= $getStock['qty']){
        $stock = 'Yes';
        }else{
        $stock = 'No';
        }
        }else{
        $stock = 'No';
        }
        
        $data[] = array(
        "order_dt" => date_format($order_dt,"Y-m-d"),
        "sale_order_id" => $rowNames['sale_order_id'],
        "id" => $getProduct['id'],
        "weight" => $weight,
        "custom" => $customs,
        "gst" => number_format($gst, 2, '.', ''),
        "earnings" => number_format($earnings, 2, '.', ''),
        "Wgt_Chg" => number_format($Wgt_Chg, 2, '.', ''),
        "custom_chr" => number_format($custom_chr, 2, '.', ''),
        "pur_price" => number_format($pur_price, 2, '.', ''),
        "currency_rate" => $getMarket['currency_rate'],
        "freight" => $freight,
        "sku" => $getProduct['sku_code'] . $getProduct['asin'] .'',
        "buyer_price" => $buy_price,
        "tax" => number_format($gst, 2, '.', ''),
        "amz_fees" => $amz_fees,
        "qty" => $rowNames['qty'],
        "account" => $rowNames['account'],
        "market" => $rowNames['market'],
        "buyer_email" => $rowNames['buyer_email'],
        "buyer_name" => $rowNames['buyer_name'],
        "buyer_phone_number" => $rowNames['buyer_phone_number'],
        "recipient_name" => $rowNames['recipient_name'],
        "ship_address_1" => $rowNames['ship_address_1'],
        "ship_address_2" => $rowNames['ship_address_2'],
        "ship_address_3" => $rowNames['ship_address_3'],
        "ship_state" => $rowNames['ship_state'],
        "ship_postal_code" => $rowNames['ship_postal_code'],
        "ship_city" => @$rowNames['ship_city'],
        "ship_country" => $rowNames['ship_country'],
        "ship_phone_number" => $rowNames['ship_phone_number'],
        "stock" => $stock,
        );
        } else {
        $market_error .= $getProduct['sku_code'] .'-'. $getProduct['asin'] .',';
        }
        } else {
        $category_error .= $getProduct['sku_code'] .'-'. $getProduct['asin'] .',';
        }
        } else {
        $product_error .= $rowNames['sku'] .',';
        }
        }
        $msg = array(
        'sale_data' => $data,
        'market_error' => $market_error,
        'category_error' => $category_error,
        'product_error' => $product_error,
        );
        } else {
        $msg = array(
        'sale_data' => array(),
        'market_error' => array(),
        'category_error' => array(),
        'product_error' => 'Upload only Less then or equal to 300 rows of excel',
        );
        }
        } else {
        $msg = array(
        'st' => 'fail',
        'msg' => ''
        );
        }
        
        return $msg;
        }
    
}

?>