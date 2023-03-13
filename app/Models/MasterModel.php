<?php

namespace App\Models;
use CodeIgniter\Model;

class MasterModel extends Model
{
    

   public function insert_edit_itemgrp($post)
   {
    
    $db = $this->db;
    if(isset($post['database'])){
        $db->setDatabase($post['database']);
    }else{
        $db->setDatabase(session('DataSource')); 
    }
    $builder = $db->table('item_group');
    $builder->select('*');
    $builder->where(array("id" => $post['id']));
    $builder->limit(1);
    $result = $builder->get();
    $result_array = $result->getRow();
    
    $msg = array();
    
    $pdata = array(
        'code' => @$post['code'] ? @$post['code'] : '' ,
        'name' => $post['name'],
        'status' => $post['status'],
    );
    
    if (!empty($result_array)) {

        $pdata['update_at'] = date('Y-m-d H:i:s');
        if(session('uid')){
            $pdata['update_by'] = session('uid');
        }else{
            $pdata['update_by'] = 0;
        }
        if (empty($msg)) {
            $builder->where(array("id" => $post['id']));
            $result = $builder->Update($pdata);
            
            $builder = $db->table('item_group');

            if ($result) {
                $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!","id"=>@$post['id']);
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }
        }
    }
    
     else {
       // print_r($post);exit;
        $gmodel = new GeneralModel();  
            $getitemgrp = $gmodel->get_data_table('item_group',array('name' => strtoupper($post['name'])));
            if(!empty($getitemgrp)){
                $msg = array('st' => 'fail', 'msg' => "Enter item group  already Added");   
            }
        
        $pdata['created_at'] = date('Y-m-d H:i:s');
        
        if(session('uid')){
            $pdata['created_by'] = session('uid');
        }else{
            $pdata['created_by'] = 0;
        }
       
        if (empty($msg)) {
            $result = $builder->Insert($pdata);
            $id = $db->insertID();
            if ($result) {
                $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!","id"=>$id);
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail","id"=>"");
            }
        }
    }

    return $msg;
   }

   public function insert_edit_tds($post)
   {
    //print_r($post);exit;
     $db = $this->db;
     $db->setDatabase(session('DataSource')); 
     $builder = $db->table('tds_rate');
    $builder->select('*');
    $builder->where(array("id" => $post['id']));
    $builder->limit(1);
    $result = $builder->get();
    $result_array = $result->getRow();
    
    $msg = array(); 
    $pdata = array(
       'section' => $post['section'],
       'pay_nature' => $post['pay_nature'],
       'the_sold' => $post['the_sold'],
       'indv' => $post['indv'],
       'others' => $post['others'],
    );
    //print_r($pdata);exit;
    if (!empty($result_array)) {
        
        $pdata['update_at'] = date('Y-m-d H:i:s');
        $pdata['update_by'] = '1';
        if (empty($msg)) {
            
            $builder->where(array("id" => $post['id']));
            $result = $builder->Update($pdata);
            
            $builder = $db->table('tds_rate');
   
            if ($result) {
                $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }
        }
    }
    
     else {
        
        $pdata['created_at'] = date('Y-m-d H:i:s');
        $pdata['created_by'] = '2';
        
        if (empty($msg)) {
            
            $result = $builder->Insert($pdata);
           //print_r($result);exit;
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

   public function insert_edit_glgrp($post)
   {
    //print_r($post);exit;
    $db = $this->db;
    $db->setDatabase(session('DataSource')); 
    $builder = $db->table('gl_group');
    $builder->select('*');
    $builder->where(array("id" => $post['id']));
    $builder->limit(1);
    $result = $builder->get();
    $result_array = $result->getRow();
    
    $msg = array();
  
    $pdata = array(
        'code' => $post['code'],
        'name' => $post['name'],
        'parent' => @$post['parent_grp'],
        'status' => 1,
    );

    $pdata1 = array(
        'gl_name' => $post['name'],
        'parent' => @$post['parent_grp'],
    );
    $gmodel = new GeneralModel();
    $res = $gmodel->get_data_table('gl_group',array('name'=>$post['name']),'*');
    
    if(empty($result_array)){
        if(!empty($res)){
            $msg = array('st' => 'fail', 'msg' => "General Ledger With This Name Was Alredy Exist..!!");
            return $msg;
        }
    }
    if (!empty($result_array)) {
        $res = $gmodel->get_data_table('gl_group',array('name'=>$post['name'],'id !='=>$post['id']),'*');
        if(!empty($res)){
            $msg = array('st' => 'fail', 'msg' => "General Ledger With This Name Was Alredy Exist..!!");
            return $msg;
        }

        $pdata['update_at'] = date('Y-m-d H:i:s');
        $pdata['update_by'] = session('uid');
        if (empty($msg)) {
            $builder->where(array("id" => $post['id']));
            $result = $builder->Update($pdata);
            
            $builder = $db->table('gl_group');

            if ($result) {
                $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }
        }
    }
    else 
    {
        
        $pdata['created_at'] = date('Y-m-d H:i:s');
        $pdata['created_by'] = session('uid');
        $pdata1['created_at'] = date('Y-m-d H:i:s');
        $pdata1['created_by'] = session('uid');
       
        if (empty($msg)) {
           $result = $builder->Insert($pdata);
           $id = $db->insertID();

           $builder_gl = $db->table('gl_group_summary');
           $result_gl = $builder_gl->Insert($pdata1);
           $summ_id = $db->insertID();

           $get_parent_data = $gmodel->get_data_table('gl_group_summary', array('id' => $post['parent_grp']),'all_sub_glgroup');
               
           if(!empty($get_data['all_sub_glgroup']))
           {
               
               $old_gl = explode(",",$get_data['all_sub_glgroup']);
               $new_gl[] = $summ_id;
               $new_array= array_merge($old_gl,$new_gl);
              $new_sub = implode(',',$new_array);
              $result_gl = $gmodel->update_data_table('gl_group_summary', array('id' => $post['parent_grp']), array('all_sub_glgroup' => $new_sub));
 
               
           }    
           else
           {
                $result_gl = $gmodel->update_data_table('gl_group_summary', array('id' => $post['parent_grp']), array('all_sub_glgroup' => $id));
           }
           $gl_data = gl_group_summary_array(@$post['parent_grp']);
           foreach($gl_data as $gl_data_row)
            {
                $new_sub = array();
                $new_array = array();
                $old_gl = array();
                $new_gl = array();
                $get_data = $gmodel->get_data_table('gl_group_summary', array('id' => $gl_data_row['id']),'all_sub_glgroup');
               
                if(!empty($get_data['all_sub_glgroup']))
                {
                    
                    $old_gl = explode(",",$get_data['all_sub_glgroup']);
                    $new_gl[] = $summ_id;
                    $new_array= array_merge($old_gl,$new_gl);
                   $new_sub = implode(',',$new_array);
                   $result_gl = $gmodel->update_data_table('gl_group_summary', array('id' => $gl_data_row['id']), array('all_sub_glgroup' => $new_sub));
      
                    
                }    
                else
                {
                     $result_gl = $gmodel->update_data_table('gl_group_summary', array('id' => $gl_data_row['id']), array('all_sub_glgroup' => $row['id']));
                }
              
            }
            //echo '<pre>';Print_r($gl_data);exit;
            
            if ($result) {
                $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }
        }
    }
    return $msg;
   }

   public function get_billterm_data($get){
    
    $dt_search =array(
        "name",
        "code",
        "billterm",
    );  
    $dt_col = array(
        "id",
        "name",
        "code",
        "billterm",
        "status",
    );

    $filter = $get['filter_data'];
    $tablename = "billterm";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0";

    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array(); 
    $statusarray = array("1" => "Activate", "0" => "Deactivate");

    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        $btnedit = '<a data-toggle="modal"  href="' . url('Master/add_billterm/') . $row['id'] . '"data-target="#fm_model"   data-title="Edit Transport : ' . $row['billterm'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Transport Name: ' . $row['billterm'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $status= '<a  tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
        $btn = $btnedit . $btndelete;

        $DataRow[] = $row['id'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['code'];
        $DataRow[] = $row['billterm'];
        $DataRow[] = $status;
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}

   public function get_godown_data($get){
    $dt_search = $dt_col = array(
        "id",
        "description",
        "name",
    );

    $filter = $get['filter_data'];
    $tablename = "godown";
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
        $btnedit = '<a data-toggle="modal"  href="' . url('Master/add_godown/') . $row['id'] . '"data-target="#fm_model"   data-title="Edit Transport : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Godown Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $btn = $btnedit . $btndelete;

        $DataRow[] = $row['id'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['description'];
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}


public function insert_edit_supervisor($post)
    {
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('supervisor');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        $pdata = array(
            'name' => $post['name'],
            'code' => $post['code'],
            'notes' => $post['notes'],
            'updatedate' => date('Y-m-d'),
            'status' => 1,
        );
        
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('supervisor');

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
        return $msg;
    }

    public function get_supervisor_data($get){
        $dt_search = $dt_col = array(
            "id",
            "name",
            "code",
            "notes",
            "updatedate",
            "status",
        );
        
    $filter = $get['filter_data'];
    $tablename = "supervisor";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0";
    
    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];
    
    $encode = array();
    $statusarray = array("1" => "Activate", "0" => "Deactivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        
    
        $btnedit = '<a data-toggle="modal" href="' . url('master/add_supervisor/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Supervisor Code: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        
        $status = '<a target="_blank"   title="Class Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
        $btn = $btnedit . $btndelete;
    
       
        $DataRow[] = $row['id'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['code'];
        $DataRow[] = $row['notes'];
        $DataRow[] = $row['updatedate'];
        $DataRow[] = $status;
        $DataRow[] = $btn;
        $encode[] = $DataRow;
    }
    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;    
}

    
public function insert_edit_uom($post)
    {
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('uom');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        $pdata = array(
            'code' => $post['code'],
            'name' => $post['name'],
            'decimal_digit' => $post['decimal'],
            'status' => 1,
            'is_static' => 1,
        );
        $gmodel = new GeneralModel;


        if (!empty($result_array)) {
            $res = $gmodel->get_data_table('uom',array('name'=>$post['name'],'id!='=>$post['id']),'*');
            $res1 = $gmodel->get_data_table('uom',array('code'=>$post['code'],'id!='=>$post['id']),'*');
            if(!empty($res) OR !empty($res1) ){
                $msg = array('st' => 'fail', 'msg' => "UOM With Same Name OR Shortname Was Already Exist..!");
                return $msg;
            }
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['updated_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('uom');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        
        else {
            $res = $gmodel->get_data_table('uom',array('name'=>$post['name']),'*');
            $res1 = $gmodel->get_data_table('uom',array('code'=>$post['code']),'*');
            if(!empty($res) OR !empty($res1) ){
                $msg = array('st' => 'fail', 'msg' => "UOM With Same Name OR Shortname Was Already Exist..!");
                return $msg;
            }
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
        return $msg;
    }

    public function insert_edit_bank($post)
    {
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('bank');
        $builder->select('*');
        $builder->where(array("id" => @$post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        
        $pdata = array(
            'name' => ucwords(@$post['name']),
            'ifsc' => @$post['ifsc'] ? @$post['ifsc'] : '',
            'branch_name' => @$post['branch_name'] ? @$post['branch_name'] : '',
            'ac_no' => @$post['ac_no'] ? @$post['ac_no'] : '',
            'status' => 1,
        );
        
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('bank');

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
        return $msg;
    }

    public function insert_edit_screenseries($post)
    {
        //print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('screenseries');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        $pdata = array(
            'code' => $post['code'],
            'name' => $post['name'],
            'notes' => $post['notes'],
            'status' => $post['status'],
        );
        
        if(!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('screenseries');

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
        return $msg;
    }

    public function search_sale_delivery($post)
    {

        $gmodel = new GeneralModel();
        $sun_deb = $gmodel->get_data_table('gl_group',array('name'=>'Sundry Debtors'),'id');
 
        $sundry_debtor = gl_list([$sun_deb['id']]);
        $sundry_debtor[]=$sun_deb['id'];

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.*');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0'));
        $builder->whereIn('gl.id',$sundry_debtor);

        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }

        $query = $builder->get();
        $getdata = $query->getResultArray();
      
        $result = array();
        
        foreach($getdata as $row){

            $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
            $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
            $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');

            $row['country_name'] = @$contry['name']; 
            $row['state_name'] = @$state['name']; 
            $row['city_name'] = @$city['name']; 
            $result[] = array("text" => $row['name'],"id" => $row['id'],"data" =>$row);
        }

        return $result;
    }
    // update trupti 24-11-2022
    public function search_pur_delivery($post)
    {
        //print_r("jhgfer");exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0'));
        $builder->where('gl.name','Sundry Creditors');
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        // echo $db->getLastQuery().'<br>';
        // print_r($getdata);exit;
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }

        return $result;
    }

    public function search_bank_data($post)
    {   
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account ac');
        $builder->select('ac.id,ac.name,ac.code');
        $builder->join('gl_group gl','gl.id = ac.gl_group');
        $builder->where(array('ac.is_delete'=> '0'  ));
        $builder->where('gl.name','Banks');
        if(!empty($post['searchTerm'])){
            $builder->like('ac.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');        
        }
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
            
        $result = array();
        $gmodel = new GeneralModel();
        foreach($getdata as $row){
            $check_no =  $gmodel->get_data_table('check_range',array('bank_id'=>$row['id'] , 'chk_finish' => '0'),'used');
            $result[] = array("code" => $row['code'],"id" => $row['id'],"text" => $row['name'] , "check"=>@$check_no['used']);
        }
       // print_r($result);exit;
        return $result;
    }

    public function search_master_bank_data($post)
    {   
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('bank');
        $builder->select('*');
        $builder->where(array('is_delete'=> '0'));
        if(!empty($post['searchTerm'])){
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');        
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
            
        $result = array();
        $gmodel = new GeneralModel();
        foreach($getdata as $row){
            $result[] = array("id" => $row['id'],"text" => $row['name']);
        }
        return $result;
    }

    
    public function search_finish_item_data($post) {
        //    echo 'jenith';exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('item');
        $builder->select('id,name');
        $builder->where(array('is_delete' => '0'));
        $builder->where(array('item_mode' => 'milling'));
        $builder->where(array('type' => 'Finish'));
        
        // $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        // $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        // echo '<pre>'; print_r($getdata);exit;
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }   
        return $result;
    }

    public function search_finishuom_data($post) {
        //    echo 'jenith';exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('uom');
        $builder->select('id,code');
        $builder->where(array('is_delete' => '0'));
        
        // $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        // $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        // echo '<pre>'; print_r($getdata);exit;
        foreach($getdata as $row){
            $result[] = array("text" => $row['code'],"id" => $row['id']);
        }   
        return $result;
    }

    public function search_transport_data($post) {
        //    echo 'jenith';exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('transport');
        $builder->select('id,code,name');
        $builder->where(array('is_delete' => '0'));
        
        if(isset($post['searchTerm'])){
            $builder->like('code',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            $builder->orLike('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }

    public function search_companygrp_data($term) {
        //echo 'jenith';exit;
        $db = $this->db;
        $builder = $db->table('companygrp');
        $builder->select('id,name');
        $builder->where(array('is_delete' => '0'));
        if(!emptu($term)){
            $builder->like('name',$term);
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        foreach($getdata as $row){
            $result[] = array("value" => $row['name'],"data" => $row['id']);
        }
        return $result;
    }

    public function search_uom_data($post) {
        //echo 'jenith';exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('uom');
        $builder->select('id,code,name');
        $builder->where(array('is_delete' => '0'));
        if(isset($post['searchTerm']) && $post['searchTerm'] != ''){
            $builder->like('code',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        // echo $db->getLastQuery();exit;
        
        $result = array();
        
        foreach($getdata as $row){
            $result[] = array("text" => $row['code'] .'('.$row['name'].')' ,"id" => $row['id']);
        }
        
        return $result;
    }

 

    public function search_particularitem_data($post)
    {
        // $where= "'gl.name != Sundry Creditors"
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ,'acc.status' => '1' ));
        $builder->where('gl.name !=','Sundry Creditors');
        $builder->orWhere('gl.name !=','Sundry Debtors');
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }

    public function search_banktrans_account_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0'  ));
        $builder->where('gl.name','Banks');
        $builder->orWhere('gl.name','Cash on Hand');
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();

        foreach($getdata as $row){
            
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('check_range');
            $builder->select('used');
            $builder->where('chk_finish',0);
            $builder->where('bank_id',$row['id']);
            $query = $builder->get();
            $res = $query->getRowArray(); 

            $result[] = array("text" => $row['name'],"id" => $row['id'],'check'=>@$res['used']);
        }

        return $result;
    }

    public function search_bank_account_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0'));
        $builder->where('gl.name','Banks');
        
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }

    public function search_bank_paticular_data($post)
    {
        $sale_pur = sale_purchase_itm_total();
        $pl_data  = pl_tot_data_bl();
        
        $trading = $sale_pur;
        $pl = $pl_data ;
        $gmodel = new GeneralModel();
        
        $trading['opening_bal'] = Opening_bal('Opening Stock');
        $gl_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Expenses'),'id,name');
        $gl_inc_id = $gmodel->get_data_table('gl_group',array('name'=>'Trading Income'),'id,name');
        
        $exp[$gl_id['id']] = trading_expense_data($gl_id['id']);
        $exp[$gl_id['id']]['name'] = $gl_id['name'];
        $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id']);

        $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id']);
        $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
        $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id']);

        // $exp = trading_expense_data($gl_id['id']);
        // $inc = trading_income_data($gl_inc_id['id']);
        
        $init_total = 0;

        $exp_total = subGrp_total($exp,$init_total);
        $inc_total = subGrp_total($inc,$init_total);
        
        $all_purchase = $trading['pur_total_rate'];
        $all_purchase_return = $trading['Purret_total_rate'];
                                            
        $all_sale = $trading['sale_total_rate'];
        $all_sale_return = $trading['Saleret_total_rate'];


        $income_total = ($all_sale - $all_sale_return) +  ($trading['opening_bal'] + ($all_purchase - $all_purchase_return) - ($all_sale - $all_sale_return)) +$inc_total;
        $expens_total = $trading['opening_bal'] + ($all_purchase -$all_purchase_return) + $exp_total;
        
        $net_profit = 0;
        $net_loss = 0;
        $gross_profit = ($expens_total -  $income_total) * -1;
        
        if(($expens_total -  $income_total) < 0 ){
            $net_profit = $gross_profit  + $pl['pl_income'] - $pl['pl_expense'];
        }else{
            $gross_loss = $expens_total -  $income_total;
            $net_loss = $gross_profit  + $pl['pl_expense'];
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst,acc.state');
        $builder->where(array('acc.is_delete' => '0' ));
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();

        if(@$net_profit != '0'){
            $profit_str = "Net Profit ( ".@$net_profit." )";
            $result[] = array("text" => $profit_str,"id" => 'net_profit');
        }
        if(@$net_loss != '0'){
            $loss_str = "Net Loss ( ".@$net_loss." )";
            $result[] = array("text" => $loss_str,"id" => 'net_loss');
        }

        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"state" =>@$row['state']);
        }
        return $result;
    }
    
    // public function search_account_data($post)
    // {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource')); 
    //     $builder = $db->table('account acc');
    //     $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
    //     $builder->join('gl_group gl','gl.id = acc.gl_group');
    //     $builder->where(array('acc.is_delete' => '0'  ));
    //     $builder->where('(gl.name = "Sundry Creditors" OR  gl.name = "Sundry Debtors" )');
    //     // $builder->where('','Sundry Creditors');
    //     // $builder->orWhere('gl.name','Sundry Debtors');
    //     if(@$post['searchTerm']){
    //         $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
    //     }
    //     $builder->limit(5);
    //     $query = $builder->get();
    //     $getdata = $query->getResultArray();
        
    //     //echo $db->getLastQuery();exit;

    //     $result = array();
    //     foreach($getdata as $row){
    //         $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state']);
    //     }
    //     return $result;
    // } 

    public function search_account_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        // $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
        // $builder->join('gl_group gl','gl.id = acc.gl_group');
        // $builder->where(array('acc.is_delete' => '0'  ));
        $builder->select('acc.*');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        // $builder->where('(gl.name = "Sundry Creditors" OR  gl.name = "Sundry Debtors" )');
        // $builder->where('','Sundry Creditors');
        // $builder->orWhere('gl.name','Sundry Debtors');
        if(@$post['searchTerm']){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        //echo $db->getLastQuery();exit;

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
        }
        return $result;
    } 
    
    public function search_accountSundry_cred_debt_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $gl_ids = gl_list([13,19]);
        $gl_ids[]=13;
        $gl_ids[]=19;
        
        $builder = $db->table('account');
        $builder->select('name,id,gst,tds_rate,tds_limit,state,gst_add');
        $builder->where(array('is_delete' => '0'  ));        
        $builder->whereIn('gl_group',$gl_ids);        
        if(isset($post['searchTerm'])){
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        // $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"address"=>$row['gst_add']);
        }
        return $result;
    }

    public function search_trans_bank_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $gl_ids = gl_list([22]);
        $gl_ids[]=22;
        
        $builder = $db->table('account');
        $builder->select('*');
        $builder->where(array('is_delete' => '0'));        
        $builder->whereIn('gl_group',$gl_ids);        
        if(isset($post['searchTerm'])){
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : '');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"data"=>$row);
        }
        return $result;
    }

    public function search_account_mill_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $gl_ids = gl_list([35]);
        $gl_ids[]=35;
        $builder = $db->table('account');
        $builder->select('name,id,gst,tds_rate,tds_limit,state');
        $builder->where(array('is_delete' => '0'  ));        
        $builder->whereIn('gl_group',$gl_ids);  
        if(isset($post['searchTerm'])){      
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"acc_state"=>$row['state']);
        }
        return $result;
    }

    public function search_advance_liability($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('bank_tras bt');
        $builder->select('acc.name as account_name,bt.*');
        $builder->join('account acc','acc.id =bt.particular');
        $builder->where(array('bt.particular' => $post['id']));
        $builder->where(array('bt.is_delete' => '0'));
        $builder->where(array('bt.stat_adj' => '1'));
        $builder->where(array('bt.nature_rec' => '2'));
        $builder->where(array('bt.nature_rec' => '2'));
        
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        // echo '<pre>';print_r($getdata);exit;
        //echo $db->getLastQuery();exit;
 
        $result = array();
        foreach($getdata as $row){
            $gmodel = new GeneralModel();
            $str = $row['id'] .' - '.$row['account_name'].' ('.$row['gst_amt'].')'; 
            $result[] = array("text" => $str,"id" => $row['id']);
        }
        return $result;
    }
    
    public function search_gst_parti($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst_type,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ,'acc.status' => '1' ));
        $builder->where('(gl.name = "Sundry Creditors" OR  gl.name = "Sundry Debtors" )');
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        //echo $db->getLastQuery();exit;
 
        $result = array();
        foreach($getdata as $row){
            $gmodel = new GeneralModel();
            $state = $gmodel->get_data_table('states', array("id"=>$row['state']),'name');

            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"gst_type"=>$row['gst_type'],"state"=>@$state['name']);
        }
        return $result;
    }
    

    public function search_broker_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.brokrage');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0'));
        $builder->where('gl.name','Broker');
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"brokrage" => $row['brokrage']);
        }
        return $result;
    }
    
    public function search_warehouse_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('warehouse');
        $builder->select('name,id,area');
        $builder->where(array('is_delete' => '0'));
        if(isset($post['searchTerm'])){
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        
        foreach($getdata as $row){
            $txt = $row['name'].' ('.$row['area'].')';
            $result[] = array("text" => $txt,"id" => $row['id']);
        }
        return $result;
    }


    // public function search_acc_particular_data($term) {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('account');
    //     $builder->select('*');
    //     $where = "(`code` LIKE '%".$term."%' OR  `name` LIKE '%".$term."%') AND `is_delete` = '0'";
        
    //     $builder->where($where);           
    //     $builder->limit(10);
    //     $query = $builder->get();
    //     $getdata = $query->getResultArray();
    //   // update trupti 24-11-2022
    //     foreach($getdata as $row){     

    //         $paticular_data = array(

    //             "id" => $row['id'],
    //             'igst' => $row['igst'],
    //             'cgst' => $row['cgst'],
    //             'sgst' => $row['sgst'],
    //             'taxability' => $row['taxability'],
            
    //         );
    //         $result[] = array(
    //             "text" => $row['name'] .' ('. $row['hsn'] .')',
    //             "id" => $row['id'],
    //             "paticular" => $paticular_data,
    //             "is_expence" => 1,
    //         );
    //     }
    //     return $result;
    // }
    public function search_acc_particular_data($post)
    {
       
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $gmodel = new GeneralModel();
        $in = $gmodel->get_data_table('gl_group',array('name'=>'Incomes'),'id');
        $ex = $gmodel->get_data_table('gl_group',array('name'=>'Expenses'),'id');
        $gl_ids = gl_list([$in['id'],$ex['id']]);
        $gl_ids[]=$in['id'];
        $gl_ids[]=$ex['id'];
        
        $builder = $db->table('account');
        $builder->select('*');
        if (isset($post['searchTerm'])) {
            $where = "(`code` LIKE '%" . $post['searchTerm'] . "%' OR  `name` LIKE '%" . $post['searchTerm'] . "%') AND `is_delete` = '0' AND `tax_type` = ''";
        } else {
            $where = "`is_delete` = '0' AND `tax_type` = ''";
        }
        $builder->where($where);
        $builder->whereIn('gl_group',$gl_ids);  
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        foreach($getdata as $row){
                $paticular_data = array(

                                "id" => $row['id'],
                                'igst' => $row['igst'],
                                'cgst' => $row['cgst'],
                                'sgst' => $row['sgst'],
                                'taxability' => $row['taxability'],
                            
                            );
                            $result[] = array(
                                "text" => $row['name'],
                                "id" => $row['id'],
                                "paticular" => $paticular_data,
                                "is_expence" => 1,
                            );
        }
        return $result;
    }

    public function search_broker_ledger($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        
        $gmodel=new GeneralModel();
        $expense_id =$gmodel->get_data_table('gl_group',array('name' =>'Expenses'),'id');
        $parent_exp = $gmodel->get_data_table('gl_group',array('parent' =>$expense_id['id']),'group_concat(id) as id' );
        $id = $expense_id['id'].','.$parent_exp['id'];

        $builder = $db->table('account');
        $builder->select('*');
         
        $builder->where(array('is_delete' => '0' ,'status' => 1)); 
        $builder->whereIn('gl_group' ,explode(',',$id)); 
        if(isset($post['searchTerm'])){
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        // echo $db->getLastQuery();exit;
        $result = array();
        
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        
        return $result;
    }

    public function search_party_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->where('gl.name','Sundry Creditors');
        $builder->orWhere('gl.name','Sundry Debtors');
        if(@$post['searchTerm'] != ''){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        // echo $db->getLAstQuery();exit;
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst']);
        }
        return $result;
    }

    public function search_parent_glgrp_data($post) {
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('gl_group');
        $builder->select('id,name,parent');
        $builder->where(array('is_delete' => '0','is_view' => '0'));
        if(isset($post['searchTerm'])){
            $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $gmodel =new GeneralModel();
        $income_id =$gmodel->get_data_table('gl_group',array('name'=> 'Incomes'),'id');
        $expence_id =$gmodel->get_data_table('gl_group',array('name'=> 'Expenses'),'id');
        $tradingincome_id =$gmodel->get_data_table('gl_group',array('name'=> 'Trading Expenses'),'id');
        $tradingexpence_id =$gmodel->get_data_table('gl_group',array('name'=> 'Trading Expenses'),'id');
        $result = array();
        
        foreach($getdata as $row){
            $parent = 0;
            $main_id = '';
            if($row['id'] == 16 || $row['id'] == 27 || $row['id'] == 29 || $row['id'] == 30 || $row['id'] == 31){
                $main_id = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 16 || $res['id'] == 27 || $res['id'] == 29 || $res['id'] == 30 || $res['id'] == 31){
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
            if($row['id'] == 21 || $row['id'] == 24 || $row['id'] == 28 || $row['id'] == 17){
                $tx_bn_hide = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 21 || $res['id'] == 24 || $res['id'] == 28 || $res['id'] == 17){
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
            if($row['id'] == 21 || $row['id'] == 30 || $row['id'] == 29 || $row['id'] == 31){
                $new_hide = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 21 || $res['id'] == 30 || $res['id'] == 29 || $res['id'] == 31){
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
            if($row['id'] == 22 ){
                $bank = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 22){
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
            if($row['id'] == 21 ){
                $cash = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 21){
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

            if($row['id'] == 4 || $row['id'] == 2){
                $opening_balCr = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 4 || $res['id'] == 2){
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

            if($row['id'] == 1 || $row['id'] == 3){
                $opening_balDr = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 1 || $res['id'] == 3){
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

            if($row['id'] == 13 || $row['id'] == 19){
                $creditor_debtor = $row['id'];
            }else{
                if($row['parent'] != 0){
                    $x = 5;
                    $parent = $row['parent'];
                    for($i = 0;$i<$x;$i++){
                        $res = $gmodel->get_data_table('gl_group',array('id'=> $parent),'id,parent');
                        if($res['id'] == 13 || $res['id'] == 19){
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

            $result[] = array("text" => $row['name'],"opening_balDr" => $opening_balDr, "opening_balCr" => $opening_balCr,"id" => $row['id'],"parent_id" =>$row['parent'],"income_id" =>$income_id['id'], "expense_id" => $expence_id['id'], "main_id"=>$main_id, "tx_bn_hide"=>$tx_bn_hide,'bank_id'=>$bank,'cash_id'=>$cash,'new_hide' => $new_hide,'creditor_debtor'=> $creditor_debtor);
        }
        //print_r($result);exit;
        return $result;
    }

    public function search_itemgrp_data($term)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('item_group');
        $builder->select('id,name,code');
        $where = "(`code` LIKE '%".$term."%' OR  `name` LIKE '%".$term."%') AND `is_delete` = '0'";
        $builder->where($where);
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
                
        $result = array();
            foreach($getdata as $row){
                $result[] = array("text" => $row['name'] . ' ('.$row['code'].')',"id" => $row['id']);
            }
        //print_r($result);exit;
            return $result;
        
    }

    

    

    public function parent_itemgrp_data($term)
    {
      //  print_r($term);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('item_group');
        $builder->select('id,name,code');
        $where = "(`code` LIKE '%".$term."%' OR  `name` LIKE '%".$term."%') AND `is_delete` = '0'";
        $builder->where($where);
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
                
        $result = array();
            foreach($getdata as $row){
                $result[] = array("value" => $row['name'],"data" => $row['id']);
            }
      //  print_r($result);exit;
            return $result;
        
    }    

    public function get_itemgrp_data($get){
        $dt_search = $dt_col = array(
            "id",
            "code",
            "name",
            "status",
        );
        
        $filter = $get['filter_data'];
        $tablename = "item_group";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $statusarray = array("1" => "Activate", "0" => "Deacivate");
        foreach ($rResult['table'] as $row) {
            
            $DataRow = array();

            $btnedit = '<a data-toggle="modal" href="' . url('master/add_itemgrp/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Item Group Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $status= '<a target ="_blank" title="Item Group Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
            
            $btn = $btnedit . $btndelete;
            $DataRow[] = $row['id'];
            $DataRow[] = $row['code'];
            $DataRow[] = $row['name'];
            $DataRow[] = $status;
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
}


public function get_broker_data($get){
    $dt_search = array(
        "name",
        "code",
        "city",
        "state",
        "country",
        "pin",
        "mobile",
    );
    
    $dt_col = array(
        "id",
        "name",
        "code",
        "mobile",
        "e_mail",
        "status",
    );
    
    $filter = $get['filter_data'];
    $tablename = "broker";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0";

    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array();
    $statusarray = array("1" => "Activate", "0" => "Deactivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        

        $btnedit = '<a  data-toggle="modal" data-target="#fm_model" href="'. url('master/add_broker/') . $row['id'] . '"   data-title="Edit Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Broker Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $status= '<a  onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" tabindex="-1">'.$statusarray[$row['status']].'</a>';
        $btn = $btnedit . $btndelete;

    
        $DataRow[] = $row['id'];
        $DataRow[] = $row['code'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['mobile'];
        $DataRow[] = $status;
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}

public function get_hsn_data($get){
    $dt_search= array(
        "hc.id",
        "hc.hsn",
        "hc.description",
        "hc.rate",
        "hc.related_code",
    );
    $dt_col = array(
        "hc.id",
        "hc.hsn",
        "hc.description",
        "hc.rate",
        "hc.related_code",
        "(select hsn from hsn_code ac where ac.id = hc.related_code) as related_hsn",
    );

    $filter = $get['filter_data'];
    $tablename = "hsn_code hc";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0";

    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array();
   // $statusarray = array("1" => "Activate", "0" => "Deactivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        

        $btnedit = '<a data-toggle="modal" href="' . url('master/add_hsn/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : ' . $row['hsn'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="HSN Code: ' . $row['hsn'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
    //    $status = '<a target="_blank"   title="Gl Group Name: '.$row['name'].'" onclick="editable_os(this)"  data-val="'.$row['status'].'"  data-pk="'.$row['id'].'" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
        $btn = $btnedit . $btndelete;

        $DataRow[] = $row['id'];
        $DataRow[] = $row['hsn'];
        $DataRow[] = $row['description'];
        $DataRow[] = $row['rate'];
        $DataRow[] = $row['related_hsn'];
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}

public function get_tds_data($get){
    $dt_search = $dt_col = array(
        "id",
        "section",
        "pay_nature",
        "the_sold",
        "indv",
        "others",
    );

    $filter = $get['filter_data'];
    $tablename = "tds_rate";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0";

    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array(); 
    //$statusarray = array("1" => "Activate", "0" => "Deactivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        $btnedit = '<a data-toggle="modal"  href="' . url('Master/add_tds/') . $row['id'] . '"data-target="#fm_model"   data-title="Edit Transport : ' . $row['section'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="TDS Rate: ' . $row['section'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        // $status= '<a title="_blank"onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
     
        $btn = $btnedit . $btndelete;

        $DataRow[] = $row['id'];
        $DataRow[] = $row['section'];
        $DataRow[] = $row['pay_nature'];
        $DataRow[] = $row['the_sold'];
        $DataRow[] = $row['indv'];
        $DataRow[] = $row['others'];
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}

public function get_bank_data($get){
    $dt_search = $dt_col = array(
        "id",
        "name",
        "ifsc",
        "branch_name",
        "ac_no",
        "status",
    );
    
    $filter = $get['filter_data'];
    $tablename = "bank";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0";

    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array();
    $statusarray = array("1" => "Activate", "0" => "Deacivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
    

    $btnedit = '<a data-toggle="modal" href="' . url('master/add_bank/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Bank : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
    $btndelete = '<a data-toggle="modal" target="_blank"   title="Bank Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
    $status= '<a target="_blank"   title="Bank Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
    $btn = $btnedit . $btndelete;

   
    $DataRow[] = $row['id'];
    $DataRow[] = $row['name'];
    $DataRow[] = $row['ac_no'];
    $DataRow[] = $row['ifsc'];
    $DataRow[] = $row['branch_name'];
    $DataRow[] = $status;
    $DataRow[] = $btn;

    $encode[] = $DataRow;
    }
    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;

}


public function get_glgrp_data($get){
    $dt_search = array(
        "gl.id",
        "gl.code",
        "gl.name",
        "(select name from gl_group glg where glg.id = gl.parent)",
    );
    
    $dt_col = array(
        "gl.id",
        "gl.code",
        "gl.name",
        "gl.parent",
        "gl.status",
        "gl.is_static",
        "(select name from gl_group glg where glg.id = gl.parent) as gl_parent",
    );

    $filter = $get['filter_data'];
    $tablename = "gl_group gl";
    $where = '';
    // if ($filter != '' && $filter != 'undefined') {
    //     $where .= ' and UserType ="' . $filter . '"';
    // }
    $where .= " and is_delete=0 and is_view=0";
    // $dt_order = array('gl.id' => 'asc');
    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array();
    $statusarray = array("1" => "Activate", "0" => "Deacivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        
        $btnedit = '<a data-toggle="modal" href="' . url('master/add_glgrp/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Group Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $status = '<a target="_blank"   title="Gl Group Name: '.$row['name'].'" onclick="editable_os(this)"  data-val="'.$row['status'].'"  data-pk="'.$row['id'].'" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
        
        if($row['is_static'] == 0){
            $btn = $btnedit . $btndelete;
        }else{
            $btn = '';
        }

        $DataRow[] = $row['id'];
        $DataRow[] = $row['code'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['gl_parent'];
        $DataRow[] = $status;
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}

public function get_uom_data($get){
    $dt_search = $dt_col = array(
        "id",        
        "code",
        "name",
        "decimal_digit",
        "status",
        "is_static"
    );

    $filter = $get['filter_data'];
    $tablename = "uom";
    $where = '';
    
    $where .= " and is_delete=0";

    $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    $sEcho = $rResult['draw'];

    $encode = array(); 
    $statusarray = array("1" => "Activate", "0" => "Deactivate");
    foreach ($rResult['table'] as $row) {
        $DataRow = array();
        
        $btnedit = '<a  data-toggle="modal" href="' . url('Master/Createuom/') . $row['id'] . '"   data-title="Edit OUM : ' . $row['name'] . '" class="btn btn-link pd-10" data-target="#fm_model" ><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="UOM : ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $status= '<a  onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" tabindex="-1">'.$statusarray[$row['status']].'</a>';

        $btn = $btnedit;
        if($row['is_static'] != 0){
            $btn = $btnedit . $btndelete;
        }else{
            $btn = $btnedit;
        }

        $DataRow[] = $row['id'];
        $DataRow[] = $row['code'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['decimal_digit'];
        $DataRow[] = $status;
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}  

public function get_warehouse_data($get){
    $dt_search = $dt_col = array(
        "id",
        "name",
        "code",
        "address",
        "city",
        "pin",
        "state",
        "country",
        "area",
        "phone",
        "whatsapp",
        "mobile",
        "email",
        
    );
    
    $filter = $get['filter_data'];
    $tablename = "warehouse";
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
        

        $btnedit = '<a  href="' . url('master/add_warehouse/') . $row['id'] . '"   data-title="Edit Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Warehouse Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $btn = $btnedit . $btndelete;

    
        $DataRow[] = $row['id'];
        $DataRow[] = $row['code'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['phone'];
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
}

    public function get_master_data($method, $id) 
    {
        
        $gnmodel = new GeneralModel();
        if ($method == 'supervisor') {
            $result['supervisor'] = $gnmodel->get_data_table('supervisor', array('id' => $id));  
        }
        if ($method == 'itemgrp') {
            $result['itemgrp'] = $gnmodel->get_data_table('item_group', array('id' => $id));
        }
       
        if ($method == 'glgrp') {
         
            $result['glgrp'] = $gnmodel->get_data_table('gl_group', array('id' => $id));
            $parent_id = $result['glgrp']['parent'];
            $parent = $gnmodel->get_data_table('gl_group', array('id' => $parent_id),'name');
            
            if(isset($parent['name']))
                $result['glgrp']['parent_name'] = $parent['name'];      
            else
                $result['glgrp']['parent_name'] = '';
        }

        if ($method == 'hsn') {

            $result['hsn'] = $gnmodel->get_data_table('hsn_code', array('id' => $id));
            $parent_id = $result['hsn']['related_code'];
            $parent = $gnmodel->get_data_table('hsn_code', array('id' => $parent_id),'hsn');
            
            if(isset($parent['hsn']))
                $result['hsn']['related_name'] = $parent['hsn'];      
            else
                $result['hsn']['related_name'] = '';
        }

        if ($method == 'item') {
            $result['item'] = $gnmodel->get_data_table('item', array('id' => $id));
        }
        if ($method == 'uom') {
            $result['uom'] = $gnmodel->get_data_table('uom', array('id' => $id));
        }
        if ($method == 'class') {
            $result['class'] = $gnmodel->get_data_table('class', array('id' => $id));
        }
        if ($method == 'billterm') {
            $result['billterm'] = $gnmodel->get_data_table('billterm', array('id' => $id));
        }
        if ($method == 'transport') {
            $result['Transport'] = $gnmodel->get_data_table('transport', array('id' => $id));
            if(!empty($result['Transport'])){
                
               $country =  $gnmodel->get_data_table('countries',array('id'=>$result['Transport']['country']),'name');
               $state =  $gnmodel->get_data_table('states',array('id'=>$result['Transport']['state']),'name');
               $city =  $gnmodel->get_data_table('cities',array('id'=>$result['Transport']['city']),'name');

               $result['Transport']['country_name'] = @$country['name'];
               $result['Transport']['city_name'] = @$city['name'];
               $result['Transport']['state_name'] = @$state['name'];
            } 
        }
        if ($method == 'vehicle') {
            $result['vehicle'] = $gnmodel->get_data_table('vehicle', array('id' => $id));  
        }
        if ($method == 'cashrece') {
            $result['cashrece'] = $gnmodel->get_data_table('cash_receipt', array('id' => $id));  
        }
        if ($method == 'cashpayment') {
            $result['cashpayment'] = $gnmodel->get_data_table('cash_payment', array('id' => $id));  
        }
        if ($method == 'bank') {
            $result['bank'] = $gnmodel->get_data_table('bank', array('id' => $id));  
        }
        if ($method == 'warehouse') {
            $result['warehouse'] = $gnmodel->get_data_table('warehouse', array('id' => $id));
            $city = $gnmodel->get_data_table('cities', array('id' => $result['warehouse']['city']),'name');
            $country = $gnmodel->get_data_table('countries', array('id' => $result['warehouse']['country']),'name');
            $state = $gnmodel->get_data_table('states', array('id' => $result['warehouse']['state']),'name');
            $result['warehouse']['country_name'] =$country['name']; 
            $result['warehouse']['state_name'] =$state['name']; 
            $result['warehouse']['city_name'] =$city['name'];
        }
        if ($method == 'screenseries') {
            $result['screenseries'] = $gnmodel->get_data_table('screenseries', array('id' => $id));  
        }
        if ($method == 'broker') {
            $result['broker'] = $gnmodel->get_data_table('broker', array('id' => $id));  
            
            $city = $gnmodel->get_data_table('cities', array('id' => $result['broker']['city']),'name');
            $country = $gnmodel->get_data_table('countries', array('id' => $result['broker']['country']),'name');
            $state = $gnmodel->get_data_table('states', array('id' => $result['broker']['state']),'name');
            $result['broker']['country_name'] =$country['name']; 
            $result['broker']['state_name'] =$state['name']; 
            $result['broker']['city_name'] =$city['name']; 
        }
        if ($method == 'tds') {
            $result['tds'] = $gnmodel->get_data_table('tds_rate', array('id' => $id));
        }
        return $result;
    }

    public function UpdateData($post) {
        $result = array();
        $db = $this->db;
       if ($post['type'] == 'Status') {
            if ($post['method'] == 'bank') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('bank', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'supervisor') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('supervisor', array('id' => $post['pk']), array('status' => $post['val'],'updatedate' => date('Y-m-d')));
            }
            if ($post['method'] == 'screenseries') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('screenseries', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'billterm') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('billterm', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'glgrp') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('gl_group', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'hsn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('hsn_code', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'warehouse') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('warehouse', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'vehicle') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('vehicle', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'transport') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('transport', array('id' => $post['pk']), array('status' => $post['val']));
            }
            if ($post['method'] == 'broker') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('broker', array('id' => $post['pk']), array('status' => $post['val']));
            }
       }
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'itemgrp') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('item_group', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'billterm') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('billterm', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'supervisor') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('supervisor', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'glgrp') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('gl_group', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'hsn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('hsn_code', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'uom') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('uom', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'godown') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('godown', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'transport') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('transport', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'cashrece') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('cash_receipt', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'cashpayment') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('cash_payment', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'vehicle') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('vehicle', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'bank') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('bank', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'screenseries') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('screenseries', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'warehouse') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('warehouse', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'broker') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('broker', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'tds') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('tds_rate', array('id' => $post['pk']), array('is_delete' => '1'));
            }
        }
        return $result;
    }

    public function insert_edit_billterm($post)
    {
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('billterm');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        $pdata = array(
            'name' => $post['name'],
            'code' => $post['code'],
            'billterm' => $post['billterm'],
            'status' => 1,
        );
        
        if(!empty($result_array)){

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('billterm');

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
    public function insert_edit_hsn($post)
    {
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('hsn_code');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        $pdata = array(
            'hsn' => $post['hsn_code'],
            'description' => $post['description'],
            'rate' => $post['rate'],
            'related_code' => !empty($post['related_code'])?$post['related_code']:'',
            //'status' => 1,
        );
        
        if(!empty($result_array)){

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('billterm');

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


    public function insert_edit_warehouse($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('warehouse');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $gmodel = new GeneralModel;

        $msg = array();
        $pdata = array(
            'name' => $post['name'],
            'code' => $post['code'],
            'address' => $post['address'] ? $post['address'] : '',
            'city' => $post['city'] ? $post['city'] : '',
            'pin' => $post['pin'] ? $post['pin'] : '',
            'state' => $post['state'] ? $post['state'] : '' ,
            'country' => $post['country'] ? $post['country'] : '',
            'area' => $post['area'] ? $post['area'] : '',
            'phone' => $post['phone'] ? $post['phone'] :'',
            'whatsapp' => $post['whatsapp'] ? $post['whatsapp'] : '',
            'mobile' => $post['mobile'] ? $post['mobile'] : '',
            'email' => @$post['e_mail'] ? $post['e_mail'] : '',
        );
        
        if (!empty($result_array)) {
            $res = $gmodel->get_data_table('warehouse',array('name'=>$post['name'],'id!='=>$post['id']),'*');
            if(!empty($res)){
                $msg = array('st' => 'fail', 'msg' => "Warehouse With Same Name Was Already Exist..!");
                return $msg;
            }

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('warehouse');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        
        else {
            $res = $gmodel->get_data_table('warehouse',array('name'=>$post['name']),'*');
            if(!empty($res)){
                $msg = array('st' => 'fail', 'msg' => "Warehouse With Same Name Was Already Exist..!");
                return $msg;
            }
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
        return $msg;
    }

    public function search_vehicle_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('vehicle');
        $builder->select('id,name,code');
      
        if(isset($post['searchTerm'])){
            $builder->like('code',@$post['searchTerm']);
            $builder->orLike('name',@$post['searchTerm']);
        }
        $builder->where('is_delete',0);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'] .' ('.$row['code'].')',"id" => $row['id']);
        }
        return $result;
    }

    public function search_tds_data($post){
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('tds_rate');
        $builder->select('*');
        $builder->where(array('is_delete' => '0'));
        if(isset($post['searchTerm'])){
            $builder->like('pay_nature',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        foreach($getdata as $row){
            $text = '('.$row['section'].') - '.$row['pay_nature']; 
            $result[] = array("text" => $text,"id" => $row['id'] ,'threshold' =>$row['the_sold'],'indi'=>$row['indv'],'other'=>$row['others'] );
        }
        return $result;
    }
    // update trupti 24-11-2022
    public function search_sun_credit($post)
    {
        $gmodel = new GeneralModel();
        $sun_cred = $gmodel->get_data_table('gl_group',array('name'=>'Sundry Creditors'),'id');

        $sundry_creditor = gl_list([$sun_cred['id']]);
        $sundry_creditor[]=$sun_cred['id'];
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.gl_group,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state,acc.default_due_days');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$sundry_creditor);
        if(!empty($post['searchTerm'])){
            $builder->like('acc.name',@$post['searchTerm']);
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
       //print_r(session('DataSource'));exit;
        //echo '<pre>';Print_r($getdata);exit;
        
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
        }
        return $result;
    }


    public function get_round_off_data($post)
    {
        $gmodel = new GeneralModel();
        $round_off = $gmodel->get_data_table('gl_group',array('name'=>'Round off'),'id');
 
        $round = gl_list([$round_off['id']]);
        $round[]=$round_off['id'];
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state,acc.default_due_days');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$round);
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        //echo $db->getLastQuery();exit;

        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }
    
    public function search_sun_debtor($post)
    {
        $gmodel = new GeneralModel();
        $sun_deb = $gmodel->get_data_table('gl_group',array('name'=>'Sundry Debtors'),'id');
 
        $sundry_debtor = gl_list([$sun_deb['id']]);
        $sundry_debtor[]=$sun_deb['id'];
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('account acc');
        $builder->select('acc.*');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => '0' ));
        $builder->whereIn('gl.id',$sundry_debtor);
        if(isset($post['searchTerm'])){
            $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        

        $result = array();
        foreach($getdata as $row){
            $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
            $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
            $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');

            $row['country_name'] = @$contry['name']; 
            $row['state_name'] = @$state['name']; 
            $row['city_name'] = @$city['name']; 
            
            $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
        }
        return $result;
    }

    public function search_related_hsn_data($post) {
        
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('hsn_code');
        $builder->select('id,hsn');
        $builder->where(array('is_delete' => '0'));
        
        if(isset($post['searchTerm'])){
            $builder->like('hsn',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }

        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['hsn'],"id" => $row['id']);
        }

        return $result;
    }

    public function insert_edit_vehicle($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('vehicle');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $msg = array();
        //print_r($pdata);exit;
        $pdata = array(
            'code' => $post['code'],
            'name' => $post['name'],
            'status' => 1,
            'note' => $post['note']
        );
        
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('vehicle');

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
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!",'id'=>"$id",'data'=>$pdata);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

public function get_vehicle_data($get){
 $dt_search = $dt_col = array(
     "id",
     "code",
     "name",
     "status",
     "note",
     "created_at",
     "created_by",
     "update_at",
     "update_by",
 );

 $filter = $get['filter_data'];
 $tablename = "vehicle";
 $where = '';
 // if ($filter != '' && $filter != 'undefined') {
 //     $where .= ' and UserType ="' . $filter . '"';
 // }
 $where .= " and is_delete=0";

 $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
 $sEcho = $rResult['draw'];

 $encode = array(); 
 $statusarray = array("1" => "Activate", "0" => "Deactivate");
 foreach ($rResult['table'] as $row) {
     $DataRow = array();
     $btnedit = '<a data-toggle="modal" href="' . url('Master/add_vehicle/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Vehicle : ' . $row['code'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
     $btndelete = '<a data-toggle="modal" target="_blank"   title="Style Name: ' . $row['code'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
     $status = '<a target="_blank"   title="Class Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
     $btn = $btnedit . $btndelete;

     $DataRow[] = $row['id'];
     $DataRow[] = $row['code'];
     $DataRow[] = $row['name'];
     $DataRow[] = $row['note'];
     $DataRow[] = $status;
     $DataRow[] = $btn;

     $encode[] = $DataRow;
 }

 $json = json_encode($encode);
 echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
 exit;
}

public function insert_edit_cashrece($post)
{
  //print_r($post);exit;
  $db = $this->db;
  $db->setDatabase(session('DataSource'));
  $builder = $db->table('cash_receipt');
 $builder->select('*');
 $builder->where(array("id" => $post['id']));
 $builder->limit(1);
 $result = $builder->get();
 $result_array = $result->getRow();
//print_r($post);exit;
 $msg = array();
 //print_r($pdata);exit;
 $pdata = array(
     'date' => $post['date'],
     'account' => $post['account_id'],
     'class' => $post['class_id'],
     'receby_sub' => $post['receby_sub_id'],
     'particulrs' => $post['particulrs'],
     'amount' => $post['amount'],
 );
 //print_r($pdata);exit;
 if (!empty($result_array)) {
     $pdata['update_at'] = date('Y-m-d H:i:s');
     $pdata['update_by'] = session('uid');
     if (empty($msg)) {
         
         $builder->where(array("id" => $post['id']));
         $result = $builder->Update($pdata);
         
         $builder = $db->table('cash_receipt');

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
        //print_r($result);exit;
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


public function insert_edit_cashpayment($post)
{
  $db = $this->db;
  $db->setDatabase(session('DataSource'));
  $builder = $db->table('cash_payment');
  $builder->select('*');
  $builder->where(array("id" => $post['id']));
  $builder->limit(1);
  $result = $builder->get();
  $result_array = $result->getRow();
    
    $msg = array();
    
    $pdata = array(
        'date' => $post['date'],
        'account' => $post['account'],
        'class' => $post['class'],
        'paid_to_sub' => $post['paid_to'],
        'particulrs' => $post['particulars'],
        'amount' => $post['amount'],    
    );
 
    if (!empty($result_array)) {
  
     $pdata['update_at'] = date('Y-m-d H:i:s');
     $pdata['update_by'] = session('uid');
     if (empty($msg)) {
         
         $builder->where(array("id" => $post['id']));
         $result = $builder->Update($pdata);
         
         $builder = $db->table('cash_payment');

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
 return $msg;
}



    public function insert_edit_transport($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('transport');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
      
        $msg = array();
        $pdata = array(
            'code' => $post['code'],
            'name' => $post['name'],
            'contact' => $post['contact'],
            'address' => $post['address'],
            'pincode' => $post['pin'],
            'country' => !empty($post['country'])?$post['country']:'',
            'city' => !empty($post['city'])?$post['city']:'',
            'state' =>  !empty($post['state'])?$post['state']:'',
            'tran_id' => @$post['tranid'],
            'status' => 1,            
        );
        $gmodel = new GeneralModel;
        //print_r($result_array);exit;
        if (!empty($result_array)) {
            $res = $gmodel->get_data_table('transport',array('name'=>$post['name'],'id!='=>$post['id']),'*');
            if(!empty($res)){
                $msg = array('st' => 'fail', 'msg' => "Transport With Same Name Was Already Exist..!");
                return $msg;
            }
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('transport');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        else
        {
            $res = $gmodel->get_data_table('transport',array('name'=>$post['name']),'*');
            if(!empty($res)){
                $msg = array('st' => 'fail', 'msg' => "Transport With Same Name Was Already Exist..!");
                return $msg;
            }

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');
            
            if (empty($msg)) {
                
                $result = $builder->Insert($pdata);
            //print_r($result);exit;
                $id = $db->insertID();
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!",'id'=>"$id",'data'=>$pdata);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function get_transport_data($get){
        $dt_search = $dt_col = array(
            "id",
            "code",
            "name",
            "tran_id",
            "status	",
            "created_at",
            "created_by",
            "update_at",
            "update_by",
        );

        $filter = $get['filter_data'];
        $tablename = "transport";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";
        $statusarray = array("1" => "Activate", "0" => "Deactivate");
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array(); 

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            
            $btnedit = '<a data-target="#fm_model" data-toggle="modal"  data-title="Edit Transport" href="' . url('Master/add_transport/') . $row['id'] . '"data-target="#fm_model"   data-title="Edit Transport : ' . $row['code'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Transport Name: ' . $row['code'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $status = '<a target="_blank"   title="Transport Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
            $btn = $btnedit . $btndelete;
            
            $DataRow[] = $row['id'];
            
            $DataRow[] = $row['code'];
            $DataRow[] = $row['name'];
            $DataRow[] = $row['tran_id'];
            $DataRow[] = $status;
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function insert_edit_godown($post){
         //print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('class');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
         
        $msg = array(); 
        $pdata = array(
            'name' => $post['name'],
            'description' => $post['description'],    
        );
         
        if (!empty($result_array)) {
             
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if(empty($msg)) { 
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $builder = $db->table('class');
        
                if($result){
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                }else{
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }else{
             
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');
             
            if (empty($msg)) {     
                $result = $builder->Insert($pdata);
                //print_r($result);exit;
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
    
        public function getCountry($post){
            
            $db = $this->db;
            $db->setDatabase(session('DataSource'));
            
            $builder=$db->table('countries');
            $builder->select('*');
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $result = $builder->get();
            $result_array = $result->getResultArray();
    
            $result = array();
            foreach($result_array as $row){
                $result[] = array("text" => $row['name'],"id" => $row['id']);
            }
            return $result;
        }
    
        public function getStates($post) {
            $db = $this->db;
            $db->setDatabase(session('DataSource'));
            $builder=$db->table('states');
            $builder->select('*');
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $builder->where('country_id', $post['country']);
            $result = $builder->get();
            $result_array = $result->getResultArray();
            $result = array();
            foreach($result_array as $row){
                $result[] = array("text" => $row['name'],"id" => $row['id']);
            }
            return $result;
        }
    
        public function getCities($post) {
            $db = $this->db;
            $db->setDatabase(session('DataSource'));
            
            $builder=$db->table('cities');
            $builder->select('*');
            if(isset($post['searchTerm'])){
                $builder->like('name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            if(isset($post['state'])){
                $builder->where('state_id', $post['state']);
            }
            $builder->limit(10);
            $result = $builder->get();
            $result_array = $result->getResultArray();
            $result = array();
            foreach($result_array as $row){
                $result[] = array("text" => $row['name'],"id" => $row['id']);
            }
            return $result;
        }
        


        public function insert_edit_broker($post)
        {
            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            
            $builder = $db->table('broker');
            $builder->select('*');
            $builder->where(array("id" => $post['id']));
            $builder->limit(1);
            $result = $builder->get();
            $result_array = $result->getRow();
            
            $msg = array();
            $pdata = array(
                'name' => $post['name'],
                'code' => $post['code'],
                'address' => $post['address'],
                'pin' => $post['pin'],
                'city' => $post['city'],
                'state' => $post['state'],
                'country' => $post['country'],
                'mobile' => $post['mobile'],
                'e_mail' => $post['e_mail'],
                'brokerage' => $post['brokerage'],
                'status' => 1,
            );
            
            if (!empty($result_array)) {
    
                $pdata['updated_at'] = date('Y-m-d H:i:s');
                $pdata['updated_by'] = session('uid');
                if (empty($msg)) {
                    $builder->where(array("id" => $post['id']));
                    $result = $builder->Update($pdata);
                    
                    $builder = $db->table('broker');
    
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
            return $msg;
        }
        // update trupti 24-11-2022
        public function search_igst_account_data($post)
        {
            //print_r($post);exit;
             $gmodel = new GeneralModel();
            // $igst = $gmodel->get_data_table('gl_group',array('name'=>'Igst'),'id');
     
            // $igst_account = gl_list([$igst['id']]);
            // $igst_account[]=$igst['id'];
            
            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('account');
            $builder->select('*');
            $builder->where(array('is_delete' => '0' ));
            $builder->where(array('tax_type'=>'gst','taxation'=>'igst'));
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $query = $builder->get();
            $getdata = $query->getResultArray();
            
    
            $result = array();
            foreach($getdata as $row){
                $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
                $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
                $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');
    
                $row['country_name'] = @$contry['name']; 
                $row['state_name'] = @$state['name']; 
                $row['city_name'] = @$city['name']; 
                
                $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
            }
            return $result;
        }
        public function search_cgst_account_data($post)
        {
            $gmodel = new GeneralModel();
            
            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('account');
            $builder->select('*');
            $builder->where(array('is_delete' => '0' ));
            $builder->where(array('tax_type'=>'gst','taxation'=>'cgst'));
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $query = $builder->get();
            $getdata = $query->getResultArray();
            
    
            $result = array();
            foreach($getdata as $row){
                $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
                $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
                $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');
    
                $row['country_name'] = @$contry['name']; 
                $row['state_name'] = @$state['name']; 
                $row['city_name'] = @$city['name']; 
                
                $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
            }
            return $result;
        }
        public function search_sgst_account_data($post)
        {
            $gmodel = new GeneralModel();
            // $sgst = $gmodel->get_data_table('gl_group',array('name'=>'Sgst'),'id');
     
            // $sgst_account = gl_list([$sgst['id']]);
            // $sgst_account[]=$sgst['id'];
            
            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('account');
            $builder->select('*');
            $builder->where(array('is_delete' => '0' ));
            $builder->where(array('tax_type'=>'gst','taxation'=>'sgst'));
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $query = $builder->get();
            $getdata = $query->getResultArray();
            
    
            $result = array();
            foreach($getdata as $row){
                $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
                $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
                $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');
    
                $row['country_name'] = @$contry['name']; 
                $row['state_name'] = @$state['name']; 
                $row['city_name'] = @$city['name']; 
                
                $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
            }
            return $result;
        }
        public function search_discount_account_data($post)
        {
            $gmodel = new GeneralModel();
            
            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('account');
            $builder->select('*');
            $builder->where(array('is_delete' => '0' ));
            $builder->where(array('tax_type'=>'discount'));
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $query = $builder->get();
            $getdata = $query->getResultArray();
            
    
            $result = array();
            foreach($getdata as $row){
                $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
                $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
                $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');
    
                $row['country_name'] = @$contry['name']; 
                $row['state_name'] = @$state['name']; 
                $row['city_name'] = @$city['name']; 
                
                $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
            }
            return $result;
        }
        public function search_round_account_data($post)
        {
            $gmodel = new GeneralModel();
            
            $db = $this->db;
            $db->setDatabase(session('DataSource')); 
            $builder = $db->table('account');
            $builder->select('*');
            $builder->where(array('is_delete' => '0' ));
            $builder->where(array('tax_type'=>'rounding_invoices'));
            if(isset($post['searchTerm'])){
                $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
            }
            $query = $builder->get();
            $getdata = $query->getResultArray();
            
    
            $result = array();
            foreach($getdata as $row){
                $city = $gmodel->get_data_table('cities',array('id'=>$row['ship_city']),'name');
                $state = $gmodel->get_data_table('states',array('id'=>$row['ship_state']),'name');
                $contry = $gmodel->get_data_table('countries',array('id'=>$row['ship_country']),'name');
    
                $row['country_name'] = @$contry['name']; 
                $row['state_name'] = @$state['name']; 
                $row['city_name'] = @$city['name']; 
                
                $result[] = array("text" => $row['name'],"id" => $row['id'],"gsttin"=>$row['gst'],"tds"=>$row['tds_rate'],"tds_limit"=>$row['tds_limit'],"state"=>$row['state'],"due_day"=>$row['default_due_days'],"data"=>$row);
            }
            return $result;
        }
    }
    
?>