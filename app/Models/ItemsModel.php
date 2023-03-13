<?php

namespace App\Models;
use CodeIgniter\Model;

class ItemsModel extends Model
{
    public function insert_edit_item($post)
    {
     
     $db = $this->db;
     if(isset($post['database'])){
        $db->setDatabase($post['database']);
    }else{
        $db->setDatabase(session('DataSource')); 
    }
     $builder = $db->table('item');
     $builder->select('*');
     $builder->where(array("id" => $post['id']));
     $builder->limit(1);
     $result = $builder->get();
     $result_array = $result->getRow();
     
     if($post['uom'] != ''){
        $uom = implode(',',@$post['uom']);
     }
     $gmodel = new GeneralModel();

     $msg = array();

     if($post['name'] == ''){
        $msg = array('st' => 'fail', 'msg' => "Please Enter Name..!",'id'=>'');
        return $msg;
     }
     //echo '<pre>';print_r($post);exit;
    $pdata = array(
        'code' => @$post['code'] ? $post['code'] : '' ,
        'type' => $post['item_type'],
        'item_mode' =>$post['item_mode'],
        'item_grp' => @$post['item_grp'],
        'name' => ucwords($post['name']),
        'sku' => $post['sku'],
        'status' => 1,
        'default_cut' => $post['default_cut'],
        'uom' => $uom,
        'purchase_cost' => @$post['purchase_cost'] ? $post['purchase_cost']:'',
        'purchase_min_qty' => @$post['purchase_min_qty'] ? $post['purchase_min_qty']:'',
        'purchase_max_qty' => @$post['purchase_min_qty'] ? $post['purchase_min_qty']:'',
        'sales_price' => @$post['sales_price'] ? $post['sales_price']:'',
        'brokrage' => @$post['brokrage'] ? $post['brokrage']:'',
        'sale_min_qty' => @$post['sale_min_qty'] ? $post['sale_min_qty']:'',
        'sale_max_qty' => @$post['sale_max_qty'] ? $post['sale_max_qty']:'',
        'opening_stock' => @$post['opening_stock'] ? $post['opening_stock']:'',
        'opening_rate' => @$post['opening_rate'] ?$post['opening_rate']:'',
        'opening_total' => @$post['opening_total'] ? $post['opening_total']:'',
        'opening_uom' => @$post['opening_uom'] ? $post['opening_uom']:'',
        'hsn' => @$post['hsn'] ? trim($post['hsn']):'',
        'taxability' => @$post['taxability'] ? $post['taxability']:'',
        'rev_charge' => @$post['rev_charge'] ? $post['rev_charge']:'',
        'ineligible' => @$post['ineligible'] ? $post['ineligible']:'',
        'non_gst' => @$post['non_gst'] ? $post['non_gst']:'',
        'igst' => @$post['igst'] ? $post['igst']:'',
        'cgst' => @$post['cgst'] ? $post['cgst']:'',
        'sgst' => @$post['sgst'] ? $post['sgst']:'',
    );
   // echo '<pre>';print_r($pdata);exit;
     if (!empty($result_array)) {   
         
        $res = $gmodel->get_data_table('item',array('name'=>$post['name'],'id!='=>$post['id'],'is_delete'=>0),'*');
        if(!empty($res)){
            $msg = array('st' => 'fail', 'msg' => "Item With Same Name Was Already Exist..!");
            return $msg;
        }

        

         $pdata['update_at'] = date('Y-m-d H:i:s');
         $pdata['update_by'] = session('uid');
         if (empty($msg)) { 
            //  echo '<pre>';print_r($post);exit;
             
             $builder->where(array("id" => $post['id']));
             $result = $builder->Update($pdata);
             
            //  echo $db->getLastQuery();exit;
             $builder = $db->table('item');
 
             if ($result) {
                 $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
             } else {
                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
             }
        }
     }
     
     else {

        $res = $gmodel->get_data_table('item',array('name'=>$post['name'],'is_delete'=>0),'*');
        if(!empty($res)){
            $msg = array('st' => 'fail', 'msg' => "Item With Same Name Was Already Exist..!");
            return $msg;
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
             $uom = $post['uom'][0];
             $uom_data = $gmodel->get_data_table('uom',array('id'=>$uom),'code');
             $pdata['uom_name'] = $uom_data['code'];
            
             if ($result) {
                 $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!",'id'=>"$id",'data'=>$pdata);
             } else {
                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
             }
         }
     }
     return $msg;
    }
    
//    public function insert_edit_itemgrp($post)
//    {
    
//     $db = $this->db;
//     $db->setDatabase(session('DataSource'));
//     $builder = $db->table('item_group');
//     $builder->select('*');
//     $builder->where(array("id" => $post['id']));
//     $builder->limit(1);
//     $result = $builder->get();
//     $result_array = $result->getRow();
    
//     $msg = array();
    
//     $pdata = array(
//         'code' => @$post['code'] ? $post['code'] : '' ,
//         'name' => $post['name'],
//         'parent' => $post['parentgrp'],
//         'status' => $post['status'],
//     );
    
//     if (!empty($result_array)) {

//         $pdata['update_at'] = date('Y-m-d H:i:s');
//         $pdata['update_by'] = session('uid');
//         if (empty($msg)) {
//             $builder->where(array("id" => $post['id']));
//             $result = $builder->Update($pdata);
            
//             $builder = $db->table('item_group');

//             if ($result) {
//                 $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
//             } else {
//                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
//             }
//         }
//     }
    
//      else {
//         $pdata['created_at'] = date('Y-m-d H:i:s');
//         $pdata['created_by'] = session('uid');
       
//         if (empty($msg)) {
//             $result = $builder->Insert($pdata);
//             $id = $db->insertID();
//             if ($result) {
//                 $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
//             } else {
//                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
//             }
//         }
//     }
//     return $msg;
//    }
   public function get_item_data($get){

    $dt_search= array(
        
        "i.hsn",
        "i.type",
        "i.name",
        "i.sku",

    );
    $dt_col = array(
        "i.id",
        "i.hsn",
        "i.type",
        "i.item_grp",
        "(select name from item_group ig where ig.id = i.item_grp ) as itm_grp_name",
        "i.name",
        "i.sku",
        "i.status"
    );

    $filter = $get['filter_data'];
    $tablename = "item i";
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
        
        $btnedit = '<a  href="' . url('Items/Createitem/') . $row['id'] . '"   class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
        $btndelete = '<a data-toggle="modal" target="_blank"   title="Item Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
        $status = '<a target="_blank"   title="Item Name: ' . $row['name'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';
        $btn = $btnedit . $btndelete;

        $DataRow[] = $row['id'];
        $DataRow[] = $row['name'];
        $DataRow[] = $row['hsn'];
        $DataRow[] = $row['itm_grp_name'];
        $DataRow[] = $row['type'];
        $DataRow[] = $row['sku'];
        $DataRow[] = $status;
        $DataRow[] = $btn;

        $encode[] = $DataRow;
    }

    $json = json_encode($encode);
    echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    exit;
    }
   
     public function UpdateData($post) {
        $result = array();
        $gnmodel = new GeneralModel();
        if ($post['type'] == 'Status') {
            if ($post['method'] == 'item') {
                $result = $gnmodel->update_data_table('item', array('id' => $post['pk']), array('status' => $post['val']));
            }
        }
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'item') {
                $result = $gnmodel->update_data_table('item', array('id' => $post['pk']), array('is_delete' => '1'));
            }
        }
        //print_r($result);exit;
        return $result;
    }

    public function get_item_data_byid($id)
    {
        $db=$this->db;
        $db->setDatabase(session('DataSource'));
        $builder=$db->table('item i');
        $builder->select('i.*,ig.name as item_grp_name');
        $builder->join('item_group ig','ig.id = i.item_grp','left');
        $builder->where(array('i.id' => $id));
        $query = $builder->get();
        $result = $query->getResultArray();
        
        $uom = explode(',',$result[0]['uom']);
        
        foreach($uom as $key => $value){
            $gmodel = new GeneralModel();
            $res= $gmodel->get_data_table('uom',array('id'=>$value),'code');
            $uom_name[$key] = $res['code'];
        }

        if($result[0]['opening_uom'] != ''){
            $opening_uom= $gmodel->get_data_table('uom',array('id'=>$result[0]['opening_uom']),'code,name');
            $result[0]['opening_uom_name'] = @$opening_uom['code'];
        }

        $result[0]['uom_name'] = implode(',',$uom_name);
        return $result[0];
    }
}
?>