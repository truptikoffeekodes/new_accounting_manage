<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\GeneralModel;

class AccountModel extends Model
{
    
    public function insert_edit_account($post)
    {
       
       
        //echo '<pre>';Print_r($post);exit;
        
        $db = $this->db;

        // For Klamp ColorSoul database = $post['database'] //
        if(isset($post['database'])){
            $db->setDatabase($post['database']);
        }else{
            $db->setDatabase(session('DataSource')); 
        }

        $builder = $db->table('account');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        if(!empty($post['name'])){
            $name = $post['name'];
        }else{
            $name = $post['taxes_name'];
        }

        $gmodel = new GeneralModel();
        $res = $gmodel->get_data_table('account',array('name'=>$name,'is_delete'=>0),'*');
        
        if(empty($result_array)){
            if(!empty($res)){
                $msg = array('st' => 'fail', 'msg' => "Ledger With This Name Was Alredy Exist..!!");
                return $msg;
            }
        }
        if(!empty($post['gst']))
        {
            $newString = str_replace('-', '', $post['gst']);
        }
        else
        {
            $newString='';
        }
        

       if(!empty($post['tax_type']))
       {
            $tax_type = $post['tax_type'];
       }
       elseif(!empty($post['ledger_type']))
       {
            $tax_type = $post['ledger_type'];
       }
       else
       {
            $tax_type  = '';
       }
        $pdata = array(
            
            'code' => @$post['code'] ? $post['code'] : '',
            'name' => ucwords($name), 
            'tax_type' => $tax_type,
            'taxation' => isset($post['taxation']) ? $post['taxation'] : '',
            'owner' => @$post['own_name'] ? $post['own_name'] :'' , 
            'gl_group' => $post['glgrp'],
            'party_group' => @$post['party'],
            'print_name' => !empty($post['Pname'])?$post['Pname']:'',
            'email' =>  !empty($post['email'])?$post['email']:'', 
            'opening_bal' => @$post['opening_bal'] ? $post['opening_bal'] :'' ,  
            'opening_type' => @$post['opening_type'] ? $post['opening_type'] : '', 
            'brokrage' => !empty($post['brokrage'])?$post['brokrage']:'', 
            'address' => !empty($post['add'])?$post['add']:'',
            'gst_add' => @$post['gst_add'] ? $post['gst_add'] : '',
            'pin' => !empty($post['pin'])?$post['pin']:'',
            'city' => @$post['city'],
            'state' => @$post['state'], 
            'country' => @$post['country'],
            'ship_pin' => !empty($post['ship_pin']) ? $post['ship_pin']:'',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '', 
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',

            'mobile' => @$post['mob'] ? $post['mob'] : '',
            'whatspp' => @$post['whatspp'] ? $post['whatspp'] : '',
            'area' => @$post['area'] ? $post['area'] : '',
            'refrred' => @$post['refrred_id'] ? $post['refrred_id'] : '',
            'transport' => @$post['transport_id'] ? $post['transport_id'] : '',
            'tax_pan' => strtoupper(@$post['taxpan']),
            'gst' => strtoupper(@$newString),
            'ineligible' => @$post['ineligible'] ? $post['ineligible'] : '',
            'rev_charge' => @$post['rev_charge'] ? $post['rev_charge'] : '',
            'igst' => @$post['igst'] ? $post['igst'] : '',
            'cgst' => @$post['cgst'] ? $post['cgst'] : '',
            'sgst' => @$post['sgst'] ? $post['sgst'] : '',
            'cess' => @$post['cess'] ? $post['cess'] : '',
            'intrest_rate' => @$post['intrate'] ? $post['intrate'] : '' ,
            'trans_bank' => @$post['trans_bank'] ? $post['trans_bank'] : '',
            'trans_bank_ac' => @$post['trans_bank_ac'] ? $post['trans_bank_ac'] : '',
            'trans_bank_ifsc' => @$post['trans_bank_ifsc'] ? $post['trans_bank_ifsc'] : '',
            'trans_bank_holder' => @$post['trans_bank_holder'] ? $post['trans_bank_holder'] : '',
            'trans_bank_name' => @$post['trans_bank_name'] ? $post['trans_bank_name'] : '',
            'bank' => @$post['bank_id'] ? $post['bank_id'] : '',
            'default_due_days' => @$post['due'] ? $post['due'] : '',
            'bank_branch' => @$post['bankbranch'] ? $post['bankbranch'] : '', 
            'bank_ac_no' => @$post['bankac'] ?$post['bankac']:'',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'ac_type' => @$post['ac_type'] ? $post['ac_type'] : '' ,
            'bank_ifsc' => @$post['bankifsc'] ? $post['bankifsc'] : '',
            'taxability' => @$post['taxability'] ? $post['taxability'] : '',
            'hsn' => @$post['hsn'] ? $post['hsn'] : '',
            'alt_gst' => @$post['alt_gst'] ? $post['alt_gst']:'',
            'gst_type' => @$post['gst_type'] ? $post['gst_type'] : '',
            'set_as' => @$post['set_as'],
        );

        if(isset($post['check_tds'])){
            $pdata += array(
                'tds_limit' => $post['tds_limit'],
                'tds_check' => 1,
                'tds_rate' => $post['tds_rate'],
                'tds_cess' => $post['tds_cess'],
                'tds_hcess' => $post['tds_hcess'], 
                'tds' => $post['tds'],
                'tds_surcharge' => $post['tds_surch'],
            );
        }else{
            $pdata['tds_check'] = 0;
        }

        if (!empty($result_array)) {
            $res = $gmodel->get_data_table('account',array('name'=>$name,'id!='=>$post['id'] ,'is_delete'=>0),'*');
            if(!empty($res)){
                $msg = array('st' => 'fail', 'msg' => "Ledger With This Name Was Alredy Exist..!!");
                return $msg;
            }
            $pdata['update_at'] = date('Y-m-d H:i:s');
            if(session('uid')){
                $pdata['update_by'] = session('uid');
            }else{
                $pdata['update_by'] = 0;
            }
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('account');
    
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    //return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        else {
            
            $pdata['created_at'] = date('Y-m-d H:i:s');

            // For Klamp ColorSoul created_by =0 // 
            if(session('uid')){
                $pdata['created_by'] = session('uid');
            }else{
                $pdata['created_by'] = 0;
            }

            
            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                
                $id = $db->insertID();
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!","id"=>"$id","data"=>$pdata);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }
    
    public function get_account_data($get){
        
        $dt_search= array(
            "a.id",
            "a.name",
            "a.gl_group",
            "(select name from gl_group gl where a.gl_group = gl.id)",
            "a.mobile",
            "a.whatspp",
        );
        
        $dt_col = array(
            "a.id",
            "a.code",
            "a.name",
            "a.gl_group",
            "(select name from gl_group gl where a.gl_group = gl.id) as gl_grp",
            "a.party_group",
            "(select name from account ac where a.party_group = ac.id) as party_grp",
            "a.email", 
            "a.whatspp",
            "a.is_static",
           
        );
        $tablename = "account a";
        $where='';
        $where .= "and is_delete=0";
        
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
        
        $encode = array(); 
        // $statusarray = array("1" => "Activate", "0" => "Deacivate");
        
        foreach ($rResult['table'] as $row) {


            $DataRow = array();
            $btnedit = '<a href="' . url('account/add_account/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Account Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btnview = '<a  href="' . url('account/account_view/') . $row['id'] . '"  class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            // $status = '<a target="_blank"   title="Account Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
            
            if($row['is_static'] == 0){
                $btn = $btnedit . $btndelete . $btnview;
            }else{
                $btn = $btnview;
            }

            
            $DataRow[] = $row['id'];
            $DataRow[] = '<a href="'.url('account/account_view/').$row['id'].'">'.$row['name'].'</a>';
            $DataRow[] = $row['gl_grp'];
            $DataRow[] = $row['whatspp'];
            $DataRow[] = $btn;
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    
    
    public function get_account_data_byid($id)
    {
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('account');
        $builder->select('*');
        $builder->where(array('id' => $id));
        $query=$builder->get();
        
        $result=$query->getResultArray();
        $gmodel = new GeneralModel();
        $re = array();

        foreach ($result as $key => $value) {
            $getGL = $gmodel->get_data_table('gl_group',array('id' =>$value['gl_group'] ),'name');
            $getParty = $gmodel->get_data_table('account',array('id' =>$value['party_group'] ),'name');
            $getTransBank = $gmodel->get_data_table('account',array('id' =>@$value['trans_bank'] ),'name');
            
            $getReffered = $gmodel->get_data_table('account',array('id' =>$value['refrred'] ),'name');
            //$getScreen = $gmodel->get_data_table('screenseries',array('id' =>$value['screen_serice'] ),'name');
            $getBank = $gmodel->get_data_table('bank',array('id' =>$value['bank'] ),'name');
            $getcity = $gmodel->get_data_table('cities',array('id' =>$value['city'] ),'name');
            $getcountry = $gmodel->get_data_table('countries',array('id' =>$value['country'] ),'name');
            $getstate = $gmodel->get_data_table('states',array('id' =>$value['state'] ),'name');
            $getTransport = $gmodel->get_data_table('transport',array('id' =>$value['transport'] ),'code');
            $getTds = $gmodel->get_data_table('tds_rate',array('id' =>$value['tds'] ),'*');

            $getship_city = $gmodel->get_data_table('cities',array('id' =>$value['ship_city'] ),'name');
            $getship_country = $gmodel->get_data_table('countries',array('id' =>$value['ship_country'] ),'name');
            $getship_state = $gmodel->get_data_table('states',array('id' =>$value['ship_state'] ),'name');
            
            $value['party_grp'] = @$getParty['name'];
            $value['trans_bank_name'] = @$getTransBank['name'];
            $value['reffered_name'] = @$getReffered['name'];
            $value['bnk'] = @$getBank['name'];
            $value['trancode'] = @$getTransport['code'];
            $value['gl_grp'] = @$getGL['name'];
            $value['city_name'] = @$getcity['name'];
            $value['state_name'] = @$getstate['name'];
            $value['country_name'] = @$getcountry['name'];
            $value['ship_city_name'] = @$getship_city['name'];
            $value['ship_state_name'] = @$getship_state['name'];
            $value['ship_country_name'] = @$getship_country['name'];
            $value['tds_name'] = '('.@$getTds['section'].' ) -'.@$getTds['pay_nature'];

            $re[0] = $value;
        }
        return $re[0];
    }

    public function UpdateData($post) {
        $result = array();
        $db = $this->db;
        if ($post['type'] == 'Status') {
            if ($post['method'] == 'account') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('account', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'voucher') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('voucher_type', array('id' => $post['pk']), array('is_active' => $post['val']));
            }
           
            
        }
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'account') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('account', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'voucher') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('voucher_type', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            
         
        }   
        return $result;
    }
    
    public function get_master_data($method, $id){
        $gnmodel = new GeneralModel();
        if ($method == 'account_view') {
            $result['account_view'] = $gnmodel->get_data_table('account', array('id' => $id));
        }
        if ($method == 'voucher_type') {
            $result['voucher_type'] = $gnmodel->get_data_table('voucher_type', array('id' => $id));
            $parent =  $gnmodel->get_data_table('voucher_type', array('id' => $result['voucher_type']['parent_id']),'name');
            $result['voucher_type']['parent_name'] = $parent['name'];
        } 
       
        return $result;
    }

    public function get_gl_parent($id){
            $gmodel = new GeneralModel();
            $row = $gmodel->get_data_table('gl_group',array('id'=>$id),'*');
            //echo '<pre>';Print_r($id);exit;
            $fixed_accets_id =$gmodel->get_data_table('gl_group',array('name'=> 'Fixed Assets'),'id');
            $tradingexpence_id =$gmodel->get_data_table('gl_group',array('name'=> 'Trading Expenses'),'id');
            $tradingincome_id =$gmodel->get_data_table('gl_group',array('name'=> 'Trading Income'),'id');
            $plexpence_id =$gmodel->get_data_table('gl_group',array('name'=> 'PL Expenses'),'id');
            $plincome_id =$gmodel->get_data_table('gl_group',array('name'=> 'PL Incomes'),'id');
            $cashinhand_id =$gmodel->get_data_table('gl_group',array('name'=> 'Cash in Hand'),'id');
            $openingstock_id =$gmodel->get_data_table('gl_group',array('name'=> 'Opening Stock'),'id');
            $closingstock_id =$gmodel->get_data_table('gl_group',array('name'=> 'Closing Stock'),'id');
            $inveshment_id =$gmodel->get_data_table('gl_group',array('name'=> 'Investments'),'id');
            $bank_id =$gmodel->get_data_table('gl_group',array('name'=> 'Banks'),'id');
            $expence_id =$gmodel->get_data_table('gl_group',array('name'=> 'Expenses'),'id');
            $liabilities_id =$gmodel->get_data_table('gl_group',array('name'=> 'Liabilities'),'id');
            $income_id =$gmodel->get_data_table('gl_group',array('name'=> 'Incomes'),'id');
            $assets_id =$gmodel->get_data_table('gl_group',array('name'=> 'Assets'),'id');
            $sundrycreditors_id =$gmodel->get_data_table('gl_group',array('name'=> 'Sundry Creditors'),'id');
            $sundrydebtor_id =$gmodel->get_data_table('gl_group',array('name'=> 'Sundry Debtors'),'id');
                
            $parent = 0;
            $main_id = '';
            if($row['id'] == $fixed_accets_id || $row['id'] == $tradingexpence_id || $row['id'] == $tradingincome_id || $row['id'] == $plexpence_id || $row['id'] == $plincome_id){
                $main_id = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $fixed_accets_id || $res['id'] == $tradingexpence_id || $res['id'] == $tradingincome_id || $res['id'] == $plexpence_id || $res['id'] == $plincome_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                    }
                    $main_id = $res['id'];
                    $i = 0;
                }
            }
            $tx_bn_hide = '';
            if($row['id'] == $cashinhand_id || $row['id'] == $openingstock_id || $row['id'] == $closingstock_id || $row['id'] == $inveshment_id){
                $tx_bn_hide = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $cashinhand_id || $res['id'] == $openingstock_id || $res['id'] == $closingstock_id || $res['id'] == $inveshment_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $tx_bn_hide = $res['id'];
                }
            }
            $new_hide = '';
            if($row['id'] == $cashinhand_id || $row['id'] == $plexpence_id || $row['id'] == $tradingincome_id || $row['id'] == $plincome_id){
                $new_hide = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $cashinhand_id || $res['id'] == $plexpence_id || $res['id'] == $tradingincome_id || $res['id'] == $plincome_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $new_hide = $res['id'];
                }
            }

            $bank = '';
            if($row['id'] == $bank_id ){
                $bank = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $bank_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $bank = $res['id'];
                }
            }

            $cash = '';
            if($row['id'] == $cashinhand_id){
                $cash = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $cashinhand_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $cash = $res['id'];
                }
            }

            $opening_balCr = '';

            if($row['id'] == $expence_id || $row['id'] == $liabilities_id){
                $opening_balCr = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $expence_id || $res['id'] == $liabilities_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $opening_balCr = $res['id'];
                }
            }

            $opening_balDr = '';

            if($row['id'] == $assets_id || $row['id'] == $income_id){
                $opening_balDr = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $assets_id || $res['id'] == $income_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $opening_balDr = $res['id'];
                }
            }

            $creditor_debtor = '';
            //echo '<pre>';Print_r($row['id']);exit;
            if($row['id'] == $sundrycreditors_id || $row['id'] == $sundrydebtor_id){
               
                
                $creditor_debtor = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == $sundrycreditors_id || $res['id'] == $sundrydebtor_id){
                            $x = 0;
                        }else{
                            $x = $res['parent'];
                        }
                        $parent = $res['parent'];
                        $i = 0;
                    }
                    $creditor_debtor = $res['id'];
                }
            }
           
            $result = array("text" => $row['name'],"opening_balDr" => $opening_balDr, "opening_balCr" => $opening_balCr,"id" => $row['id'],"parent_id" =>$row['parent'],"income_id" =>$income_id['id'], "expense_id" => $expence_id['id'], "main_id"=>$main_id, "tx_bn_hide"=>$tx_bn_hide,'bank_id'=>$bank,'cash_id'=>$cash,'new_hide' => $new_hide,'creditor_debtor'=> $creditor_debtor);

            return $result;
    }

    public function get_voucher_data($get){
        
        $dt_search= array(
            "a.id",
            "a.name",
            "a.parent_id",
            "(select name from voucher_type vt where a.parent_id = vt.id)",
            "a.method_of_numbering",
            "a.is_active",
            "a.set_as"
        );
        
        $dt_col = array(
            "a.id",
            "a.name",
            "a.parent_id",
            "(select name from voucher_type vt where a.parent_id = vt.id) as parent_voucher_type",
            "a.method_of_numbering",
            "a.is_active",
            "a.set_as"
           
        );
        $tablename = "voucher_type a";
        $where='';
        $where .= "and is_delete=0 and set_as=0";
        
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
        
        $encode = array(); 
        $statusarray = array("1" => "Activate", "0" => "Deacivate");
        
        foreach ($rResult['table'] as $row) {


            $DataRow = array();
            $btnedit = '<a data-toggle="modal"  href="' . url('Account/add_voucher/') . $row['id'] . '"data-target="#fm_model"   data-title="Edit voucher name : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Voucher Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $status= '<a  tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['is_active']].'</a>';
            
           
               
            if($row['set_as'] == 0){
                $btn = $btnedit . $btndelete;
                $set_as = "Optional";
            }
            else
            {
                $btn = '';
                $set_as = "Default";
            }

            if($row['method_of_numbering'] == 'automatic')
            {
                $method = 'Automatic';
            }
            elseif($row['method_of_numbering'] == 'automatic_manual_override')
            {
                $method = 'Automatic(manual Override)';
            }
            elseif($row['method_of_numbering'] == 'manual')
            {
                $method = 'Manual';
            }
            else
            {
                $method = 'None';
            }

            
            $DataRow[] = $row['id'];
            $DataRow[] = $row['name'];
            $DataRow[] = $row['parent_voucher_type'];
            $DataRow[] = $method;
            $DataRow[] = $set_as;
            $DataRow[] = $status;
            $DataRow[] = $btn;
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    public function get_parent_voucher_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('voucher_type acc');
        $builder->select('acc.name,acc.id');
        $builder->where(array('acc.is_delete' => '0','acc.set_as' => '1'));
        if(@$post['searchTerm'] != ''){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        // echo $db->getLAstQuery();exit;
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }
    public function insert_edit_voucher($post)
    {
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('voucher_type');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('voucher_type');
        $builder->select('*');
        $builder->where(array("name" => $post['name']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array1 = $result->getRow();
        if(!empty($result_array1))
        {
            if(!empty($post['id']))
            {
                if($result_array1->id != $post['id'])
                {
                    $msg = array('st' => 'fail', 'msg' => "Voucher Name AAlredy Exist..!!");
                }
            }
            else
            {
                $msg = array('st' => 'fail', 'msg' => "Voucher Name AAlredy Exist..!!");
            }
        }
        
        $msg = array();
        $pdata = array(
            'name' => $post['name'],
            'parent_id' => @$post['parent_id'],
            'method_of_numbering' => $post['method_of_numbering'],
            'prevent_duplicate' => @$post['prevent_duplicate'] ? @$post['prevent_duplicate'] : '',
            'Prefix' => @$post['prefix'] ? @$post['prefix'] : '',
            'set_as' => $post['set_as'],
            'is_active' => 1,
        );
       // echo '<pre>';Print_r($pdata);exit;
        
        
        if(!empty($result_array)){

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('voucher_type');

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
                
                if($result){
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                }else{
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }
    

    
}