<?php
use App\Models\GeneralModel;

//********** Start Balance Sheet Sub Group LOOPING ***********//

function get_capital_sub_grp_data($parent_id,$start_date = '', $end_date = ''){
    $categories = array();

    $db = \Config\Database::connect();
    
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $builder->where('is_delete', 0);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();
        
        if($start_date != ''  && $end_date != ''){
            $category = capital_data($mainCategory->id,$start_date,$end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_capital_sub_grp_data($mainCategory->id,$start_date,$end_date);
        
        }else{
            $category = capital_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_capital_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}

function get_loans_sub_grp_data($parent_id,$start_date = '', $end_date = ''){
    $categories = array();

    $db = \Config\Database::connect();
    
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $builder->where('is_delete', 0);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();
        
        if($start_date != ''  && $end_date != ''){
            $category = loans_data($mainCategory->id,$start_date,$end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_loans_sub_grp_data($mainCategory->id,$start_date,$end_date);
        }else{
            $category = loans_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_loans_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}

function get_Currlib_sub_grp_data($parent_id,$start_date = '', $end_date = ''){
    $categories = array();

    $db = \Config\Database::connect();
    
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $builder->where('is_delete', 0);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();
        
        if($start_date != ''  && $end_date != ''){
            $category = Currlib_data($mainCategory->id,$start_date,$end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_Currlib_sub_grp_data($mainCategory->id,$start_date,$end_date);
        }else{
            $category = Currlib_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_Currlib_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}

function get_FixedAssets_sub_grp_data($parent_id,$start_date = '', $end_date = ''){
    $categories = array();

    $db = \Config\Database::connect();
    
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $builder->where('is_delete',0);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();
        
        if($start_date != ''  && $end_date != ''){
            $category = Fixed_Assets_data($mainCategory->id,$start_date,$end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_FixedAssets_sub_grp_data($mainCategory->id,$start_date,$end_date);
        
        }else{
            $category = Fixed_Assets_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_FixedAssets_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}


function get_OtherAssets_sub_grp_data($parent_id,$start_date = '', $end_date = ''){
    $categories = array();

    $db = \Config\Database::connect();
    
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $builder->where('is_delete', 0);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();
        
        if($start_date != ''  && $end_date != ''){
            $category = Other_Assets_data($mainCategory->id,$start_date,$end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_OtherAssets_sub_grp_data($mainCategory->id,$start_date,$end_date);        
        }else{
            $category = Other_Assets_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_OtherAssets_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}

function get_CurrentAssets_sub_grp_data($parent_id,$start_date = '', $end_date = ''){
    $categories = array();

    $db = \Config\Database::connect();
    
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $builder->where('is_delete', 0);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();
        
        if($start_date != ''  && $end_date != ''){
            $category = Current_Assets_data($mainCategory->id,$start_date,$end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_CurrentAssets_sub_grp_data($mainCategory->id,$start_date,$end_date);
        
        }else{
            $category = Current_Assets_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_CurrentAssets_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return $categories;
}



//********** End Balance Sheet Sub Group LOOPING ***********//


//********** Start Balance Sheet DATA ***********//

function capital_data($id,$start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $tot_pg_income = array();
    $account = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $query = $builder->get();
    $account = $query->getResultArray();

    foreach ($account as $row) {
        if($row['opening_type'] == 'Debit'){
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        }
        $tot_pg_income[$row['account_name']]['opening_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    
    $bank_income = array();
            
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,bt.payment_type,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_income = $query->getResultArray();

    foreach ($bank_income as $row) {
        if($row['mode'] == 'Receipt'){
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }

        $tot_pg_income[$row['account_name']]['bt_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $jv_income = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_income = $query->getResultArray();
   
    foreach ($jv_income as $row) {

        if($row['dr_cr'] == 'cr'){
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }
        
        $tot_pg_income[$row['account_name']]['jv_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {
        $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
    }
    
    if(!empty($total_arr)) {
        $trading_income_total = array_sum($total_arr);
    }else {
        $trading_income_total = 0;
    }

    $arr['account'] = $tot_pg_income;
    $arr['total'] = $trading_income_total;

    return $arr;
}

function loans_data($id,$start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $tot_pg_income = array();


    $account = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $query = $builder->get();
    $account = $query->getResultArray();

    foreach ($account as $row) {
        if($row['opening_type'] == 'Debit'){
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        }
        $tot_pg_income[$row['account_name']]['opening_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }


    $bank_income = array();
            
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0','bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_income = $query->getResultArray();

    foreach ($bank_income as $row) {
        
        if($row['mode'] == 'Receipt'){
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }
        $tot_pg_income[$row['account_name']]['bt_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    // echo '<pre>';print_r($bank_income);
    $jv_income = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('jv_particular jv', 'jv.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0','jv.is_delete' => '0'));
        $builder->where(array('DATE(jv.date)  >= ' => $start_date));
        $builder->where(array('DATE(jv.date)  <= ' => $end_date));
        $query = $builder->get();
        $jv_income = $query->getResultArray();
   
    foreach ($jv_income as $row) {
        if($row['dr_cr'] == 'cr'){
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }
        $tot_pg_income[$row['account_name']]['jv_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }




    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {

        $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
        $total_arr[] = @$value['jv_total']+@$value['bt_total'] + @$value['opening_total'];
    }
    
    if(!empty($total_arr)) {
        $trading_income_total = array_sum($total_arr);
    }else {
        $trading_income_total = 0;
    }

    $arr['account'] = $tot_pg_income;
    $arr['total'] = $trading_income_total;

    return $arr;
}
//update trupti 26-12-2022
function Currlib_data($id,$start_date = '', $end_date = ''){

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $tot_pg_income = array();

    $account = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $query = $builder->get();
    $account = $query->getResultArray();

    foreach ($account as $row) {
        if($row['opening_type'] == 'Debit'){
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        }
        $tot_pg_income[$row['account_name']]['opening_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }


    $pg_expense = array();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.party_account = ac.id');
    $builder->join('purchase_particu pp', 'pp.parent_id = pg.id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense = $query->getResultArray();


    foreach($pg_expense as $row) {
        
        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']][$row['pg_type']] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];

    }

    $bank_income = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id = ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_income = $query->getResultArray();

    foreach($bank_income as $row) {

        if($row['mode'] == 'Receipt'){
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }

        $tot_pg_income[$row['account_name']]['bt_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $jv_income = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->join('jv_main jm', 'jm.id = jv.jv_id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('jm.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_income = $query->getResultArray();

    foreach ($jv_income as $row) {

        if($row['dr_cr'] == 'cr'){
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
        }else{
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }

        $tot_pg_income[$row['account_name']]['jv_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

        $purchase = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('purchase_invoice pi', 'pi.account = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pi.is_delete' => '0'));
        $builder->where(array('pi.is_cancle' => '0'));
        $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase = $query->getResultArray();

        foreach($purchase as $row){
           
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_total']) ? $tot_pg_income[$row['account_name']]['purchase_total'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        $purchase_return = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('purchase_return pi', 'pi.account = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
        $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_return = $query->getResultArray();

        foreach($purchase_return as $row){
           
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_ret_total']) ? $tot_pg_income[$row['account_name']]['purchase_ret_total'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_ret_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
    
        // update trupti 26-12-2022 duties and taxes add taxes account
        $data = gst_gl_group_data($id,$start_date,$end_date);
            
        $pg_expense_igst = $data['pg_expense_igst'];
        $pg_expense_cgst = $data['pg_expense_cgst'];
        $pg_expense_sgst = $data['pg_expense_sgst'];

        foreach($pg_expense_igst as $row) {
        
            $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_igst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_igst'] : 0) + $row['pg_amount'];
            $tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_igst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    
        }
        foreach($pg_expense_cgst as $row) {
        
            $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_cgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_cgst'] : 0) + $row['pg_amount'];
            $tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_cgst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    
        }
        foreach($pg_expense_sgst as $row) {
        
            $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_sgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_sgst'] : 0) + $row['pg_amount'];
            $tot_pg_income[$row['account_name']][$row['pg_type'].'purchase_sgst']= $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    
        }
        $sg_expense_igst = $data['sg_expense_igst'];
        $sg_expense_cgst = $data['sg_expense_cgst'];
        $sg_expense_sgst = $data['sg_expense_sgst'];
        foreach($sg_expense_igst as $row) {
        
            $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['sg_amount_igst'];
            $tot_pg_income[$row['account_name']][$row['pg_type'].'sales_igst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    
        }
        foreach($sg_expense_cgst as $row) {
        
            $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['sg_amount_cgst'];
            $tot_pg_income[$row['account_name']][$row['pg_type'].'sales_cgst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    
        }
        foreach($sg_expense_sgst as $row) {
        
            $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['sg_amount_sgst'];
            $tot_pg_income[$row['account_name']][$row['pg_type'].'sales_sgst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    
        }
        $purchase_igst = $data['purchase_igst'];
        $purchase_cgst = $data['purchase_cgst'];
        $purchase_sgst = $data['purchase_sgst'];
        foreach($purchase_igst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_igst']) ? $tot_pg_income[$row['account_name']]['purchase_total_igst'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_total_igst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($purchase_cgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_cgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_cgst'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_total_cgst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($purchase_sgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_sgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_sgst'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_total_sgst'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        $sales_igst = $data['sales_igst'];
        $sales_cgst = $data['sales_cgst'];
        $sales_sgst = $data['sales_sgst'];
        foreach($sales_igst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['sales_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_igst_total'] : 0) + $row['sales_igst_total'];
            $tot_pg_income[$row['account_name']]['sales_igst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($sales_cgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['sales_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_cgst_total'] : 0) + $row['sales_cgst_total'];
            $tot_pg_income[$row['account_name']]['sales_cgst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($sales_sgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['sales_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_sgst_total'] : 0) + $row['sales_sgst_total'];
            $tot_pg_income[$row['account_name']]['sales_sgst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
    
        $purchase_return_igst = $data['purchase_return_igst'];
        $purchase_return_cgst = $data['purchase_return_cgst'];
        $purchase_return_sgst = $data['purchase_return_sgst'];

        foreach($purchase_return_igst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_igst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($purchase_return_cgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($purchase_return_sgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] : 0) + $row['total'];
            $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        $sales_return_igst = $data['sales_return_igst'];
        $sales_return_cgst = $data['sales_return_cgst'];
        $sales_return_sgst = $data['sales_return_sgst'];
        foreach($sales_return_igst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['sales_return_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_igst_total'] : 0) + $row['sales_return_igst_total'];
            $tot_pg_income[$row['account_name']]['sales_return_igst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($sales_return_cgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['sales_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] : 0) + $row['sales_return_cgst_total'];
            $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        foreach($sales_return_sgst as $row){
        
            $total = ((@$tot_pg_income[$row['account_name']]['sales_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] : 0) + $row['sales_return_sgst_total'];
            $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] = $total;
            $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        }
        
        
    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {

        $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total']  + @$value['general']-@$value['return'] 
        + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
        - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
        + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
        - @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
        + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
        - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
        + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
        - @$value['purchase_return_igst_total'] - @$value['purchase_return_sgst_total'] - @$value['purchase_return_cgst_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total'] +  @$value['general']-@$value['return']
        + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
        - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
        + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
        - @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
        + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
        - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
        + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
        - @$value['purchase_return_igst_total'] - @$value['purchase_return_sgst_total'] - @$value['purchase_return_cgst_total']
        ;
    }
    
    if(!empty($total_arr)) {
        $trading_income_total = array_sum($total_arr);
    }else {
        $trading_income_total = 0;
    }

    $arr['account'] = $tot_pg_income;
    $arr['total'] = $trading_income_total;

    return $arr;
}


function Fixed_Assets_data($id,$start_date = '', $end_date = ''){
    
        if ($start_date == '') {
            if (date('m') < '03') {
                $year = date('Y') - 1;
                $start_date = $year . '-04-01';
            } else {
                $year = date('Y');
                $start_date = $year . '-04-01';
            }
        }
        if ($end_date == '') {

            if (date('m') < '03') {
                $year = date('Y');
            } else {
                $year = date('Y') + 1;
            }
            $end_date = $year . '-03-31';
        }
        
        $db = \Config\Database::connect();
        
        if(session('DataSource')) {
            $db->setDatabase(session('DataSource'));
        }

        $tot_fixedassets = array();

        $account = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $query = $builder->get();
        $account = $query->getResultArray();

        foreach ($account as $row) {
            if($row['opening_type'] == 'Debit'){
                $total = ((@$tot_fixedassets[$row['account_name']]['opening_total']) ? $tot_fixedassets[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
            }else{
                $total = ((@$tot_fixedassets[$row['account_name']]['opening_total']) ? $tot_fixedassets[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
            }
            $tot_fixedassets[$row['account_name']]['opening_total'] = $total;
            $tot_fixedassets[$row['account_name']]['account_id'] = $row['account_id'];
        }

        $bank_FixedAssets = array();     
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('bank_tras bt', 'bt.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('bt.is_delete' => '0'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $bank_FixedAssets = $query->getResultArray();

      

        foreach ($bank_FixedAssets as $row) {    
            if($row['mode'] == 'Payment'){
                $total = ((@$tot_fixedassets[$row['account_name']]['bt_total']) ? $tot_fixedassets[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
            }else{
                $total = ((@$tot_fixedassets[$row['account_name']]['bt_total']) ? $tot_fixedassets[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
            }
            $tot_fixedassets[$row['account_name']]['bt_total'] = $total;
            $tot_fixedassets[$row['account_name']]['account_id'] = $row['account_id'];
        }

        $jv_FixedAssets = array();
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('jv_particular jv', 'jv.particular = ac.id');
        $builder->join('jv_main jm', 'jm.id = jv.jv_id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('jm.is_delete' => '0'));
        $builder->where(array('DATE(jm.date)  >= ' => $start_date));
        $builder->where(array('DATE(jm.date)  <= ' => $end_date));
        $query = $builder->get();
        $jv_FixedAssets = $query->getResultArray();
       
        foreach($jv_FixedAssets as $row) {
            if($row['dr_cr'] == 'cr'){
                $total = ((@$tot_fixedassets[$row['account_name']]['jv_total']) ? $tot_fixedassets[$row['account_name']]['jv_total'] : 0) - $row['total'];
            }else{
                $total = ((@$tot_fixedassets[$row['account_name']]['jv_total']) ? $tot_fixedassets[$row['account_name']]['jv_total'] : 0) + $row['total'];
            }
            $tot_fixedassets[$row['account_name']]['jv_total'] = $total;
            $tot_fixedassets[$row['account_name']]['account_id'] = $row['account_id'];
        }
        $sales_FixedAssets = array();
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,sa.type as type,sa.amount as total,gl.name as gl_name, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('sales_ACparticu sa', 'sa.account = ac.id');
        $builder->join('sales_ACinvoice si', 'si.id = sa.parent_id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('sa.is_delete' => '0'));
        $builder->where(array('si.is_delete' => '0'));
        $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales_FixedAssets = $query->getResultArray();
       
        foreach($sales_FixedAssets as $row) {
            if($row['type'] == 'general'){
                $gen_total = ((@$tot_fixedassets[$row['account_name']]['general']) ? $tot_fixedassets[$row['account_name']]['general'] : 0) + $row['total'];
            }else{
                $ret_total = ((@$tot_fixedassets[$row['account_name']]['return']) ? $tot_fixedassets[$row['account_name']]['return'] : 0) - $row['total'];
            }
            $total =  @$gen_total - @$ret_total;
            $tot_fixedassets[$row['account_name']]['sale_total'] = $total;
            $tot_fixedassets[$row['account_name']]['account_id'] = @$row['account_id'];
        }
        $purchase_FixedAssets = array();
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,pg.type as type,pg.amount as total,gl.name as gl_name, ac.name as account_name');
        $builder->join('account ac', 'gl.id = ac.gl_group');
        $builder->join('purchase_particu pg', 'pg.account = ac.id');
        $builder->join('purchase_general pn', 'pn.id = pg.parent_id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pg.is_delete' => '0'));
        $builder->where(array('DATE(pn.doc_date)  >= ' => $start_date));
        $builder->where(array('DATE(pn.doc_date)  <= ' => $end_date));
        // $builder->where(array('DATE(pn.doc_date)  >= ' => db_date($start_date)));
        // $builder->where(array('DATE(pn.doc_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $purchase_FixedAssets = $query->getResultArray();
        //echo '<pre>';Print_r();exit;
        //echo $db->getLastQuery();exit;
        
      
        foreach($purchase_FixedAssets as $row) {
            if($row['type'] == 'general'){
                $gen_total = ((@$tot_fixedassets[$row['account_name']]['general']) ? $tot_fixedassets[$row['account_name']]['general'] : 0) + $row['total'];
            }else{
                $ret_total = ((@$tot_fixedassets[$row['account_name']]['return']) ? $tot_fixedassets[$row['account_name']]['return'] : 0) - $row['total'];
            }
            $total = $gen_total - (@$ret_total ? @$ret_total : 0);
            $tot_fixedassets[$row['account_name']]['purchase_total'] = $total;
            $tot_fixedassets[$row['account_name']]['account_id'] = @$row['account_id'];
        }

        $total_arr = array();

        foreach ($tot_fixedassets as $key => $value) {
            $tot_fixedassets[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'] + @$value['opening_total'];
            $total_arr[] = @$value['jv_total']+@$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'] + @$value['opening_total'];
        }
        
        if(!empty($total_arr)) {
            $fixed_assets_total = array_sum($total_arr);
        }else {
            $fixed_assets_total = 0;
        }
    
        $arr['account'] = $tot_fixedassets;
        $arr['total'] = $fixed_assets_total;

        return $arr;
}

function Current_Assets_data($id,$start_date = '', $end_date = '')
{
        if ($start_date == '') {
            if (date('m') < '03') {
                $year = date('Y') - 1;
                $start_date = $year . '-04-01';
            } else {
                $year = date('Y');
                $start_date = $year . '-04-01';
            }
        }

        if ($end_date == '') {

            if (date('m') < '03') {
                $year = date('Y');
            } else {
                $year = date('Y') + 1;
            }
            $end_date = $year . '-03-31';
        }
        
        $db = \Config\Database::connect();
        
        if(session('DataSource')) {
            $db->setDatabase(session('DataSource'));
        }
        $tot_currentassets = array();

        $account = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $query = $builder->get();
        $account = $query->getResultArray();

        foreach($account as $row) {
            if($row['opening_type'] == 'Debit'){
                $total = ((@$tot_currentassets[$row['account_name']]['opening_total']) ? $tot_currentassets[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
            }else{
                $total = ((@$tot_currentassets[$row['account_name']]['opening_total']) ? $tot_currentassets[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
            }
            $tot_currentassets[$row['account_name']]['opening_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        }

        $builder = $db->table('gl_group gl');
        $builder->select('gl.id as gl_id,gl.name,gl.parent,sg.v_type as sg_type,sg.party_account as sg_acc,ac.name as account_name,ac.id as account_id,sg.net_amount as sg_amount,sg.disc_type,sg.discount,sg.amty,sg.amty_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('sales_ACinvoice sg', 'sg.party_account = ac.id');
        $builder->join('sales_ACparticu sp', 'sp.parent_id = sg.id');
        $builder->where('(sg.v_type="general" OR sg.v_type = "return")');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('sg.is_delete' => '0'));
        $builder->where(array('sg.is_cancle' => '0'));
        $builder->where(array('DATE(sg.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(sg.invoice_date)  <= ' => $end_date));
        $builder->groupBy('sg.id');
        $query = $builder->get();
        $sale_gnrl_current_asset = $query->getResultArray();

        // echo $db->getLastQuery();
        // echo '<pre>';print_r($id);
        // echo '<pre>';print_r($start_date);
        // echo '<pre>';print_r($end_date);
        // echo '<pre>';print_r($sale_gnrl_current_asset);
    
    
        foreach($sale_gnrl_current_asset as $row) {
            
            $total = ((@$tot_currentassets[$row['account_name']][$row['sg_type']]) ? $tot_currentassets[$row['account_name']][$row['sg_type']] : 0) + $row['sg_amount'];
            $tot_currentassets[$row['account_name']][$row['sg_type']] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
            
        }

        $bank_CurrentAssets = array();     
        $builder = $db->table('gl_group gl');
        $builder->select('gl.id as gl_id,ac.id as account_id,gl.name as gl_name,bt.amount as total,bt.mode as mode,bt.payment_type as pay_type, ac.name as account_name');
        $builder->join('account ac', 'gl.id = ac.gl_group');
        $builder->join('bank_tras bt', 'bt.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('bt.payment_type !=' => 'contra'));
        $builder->where(array('bt.is_delete' => '0'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $bank_CurrentAssets = $query->getResultArray();

        foreach ($bank_CurrentAssets as $row) {
            
            if($row['mode'] == 'Payment'){
                $total = ((@$tot_currentassets[$row['account_name']]['bt_total']) ? (float)$tot_currentassets[$row['account_name']]['bt_total'] : 0) + $row['total'];
            }else{
                $total = ((@$tot_currentassets[$row['account_name']]['bt_total']) ? (float)$tot_currentassets[$row['account_name']]['bt_total'] : 0) - $row['total'];
            }

            $tot_currentassets[$row['account_name']]['bt_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = @$row['account_id'];
        }
        

        $jv_CurrentAssets = array();
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('jv_particular jv', 'jv.particular = ac.id');
        $builder->join('jv_main jm', 'jm.id = jv.jv_id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('jm.is_delete' => '0'));
        $builder->where(array('jv.is_delete' => '0'));
        $builder->where(array('DATE(jm.date)  >= ' => $start_date));
        $builder->where(array('DATE(jm.date)  <= ' => $end_date));
        $query = $builder->get();
        $jv_CurrentAssets = $query->getResultArray();

        foreach($jv_CurrentAssets as $row) {
            if($row['dr_cr'] == 'cr'){
                $total = ((@$tot_currentassets[$row['account_name']]['jv_total']) ? $tot_currentassets[$row['account_name']]['jv_total'] : 0) - $row['total'];
            }else{
                $total = ((@$tot_currentassets[$row['account_name']]['jv_total']) ? $tot_currentassets[$row['account_name']]['jv_total'] : 0) + $row['total'];
            }
            $tot_currentassets[$row['account_name']]['jv_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        }

        $ac_CurrentAssets = array();
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,bt.amount as total,bt.mode as mode,bt.payment_type as pay_type, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('bank_tras bt', 'bt.account = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('bt.is_delete' => '0'));
        $builder->where(array('bt.payment_type !=' => 'contra'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $ac_CurrentAssets = $query->getResultArray();
        
        foreach ($ac_CurrentAssets as $row) {
            
            if($row['mode'] == 'Payment'){
                $total = ((@$tot_currentassets[$row['account_name']]['ac_total']) ? $tot_currentassets[$row['account_name']]['ac_total'] : 0) - $row['total'];
            }else{
                $total = ((@$tot_currentassets[$row['account_name']]['ac_total']) ? $tot_currentassets[$row['account_name']]['ac_total'] : 0) + $row['total'];             
            }

            $tot_currentassets[$row['account_name']]['ac_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = @$row['account_id'];
        }

        $sales = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('sales_invoice pi', 'pi.account = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pi.is_delete' => '0'));
        $builder->where(array('pi.is_cancle' => '0'));
        $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales = $query->getResultArray();


        $total = 0;
        foreach($sales as $row){
            // $after_disc=0;
            
            
            // if($row['disc_type'] != ''){
            //     if($row['disc_type'] == 'Fixed'){
            //         $row['total'] = (float)$row['total'] -  (float)$row['discount'];
            //         $after_disc =  $row['total'];
            //     }else{
            //         $row['total'] = ((float)$row['total'] * ((float)$row['discount'] / 100));
            //         $after_disc =  $row['total'];
            //     }
            // }else{
            //     $after_disc = $row['total'];
            // }
            
            // if($row['amty_type'] != ''){
           
            //     if($row['amty_type'] == 'Fixed'){
            //         $row['total'] = (float)$row['total'] + (float)$row['amty']; 
            //     }else{
            //         $row['total'] = (float)$row['total'] + ((float)@$after_disc * ((float)$row['amty'] / 100));
            //     }
            // }

            $total = ((@$tot_currentassets[$row['account_name']]['sales_total']) ? $tot_currentassets[$row['account_name']]['sales_total'] : 0) + $row['total'];
            $tot_currentassets[$row['account_name']]['sales_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        }


        $sales_return = array();

        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('sales_return pi', 'pi.account = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
        $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales_return = $query->getResultArray();

        foreach($sales_return as $row){
          

            $total = ((@$tot_currentassets[$row['account_name']]['sales_ret_total']) ? $tot_currentassets[$row['account_name']]['sales_ret_total'] : 0) + $row['total'];
            $tot_currentassets[$row['account_name']]['sales_ret_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        }

       
        $contra_CurrentAssets = array();     
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,ct.amount as total,ac.name as account_name');
        $builder->join('account ac', 'gl.id = ac.gl_group');
        $builder->join('bank_tras ct', 'ct.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('ct.is_delete' => '0'));
        $builder->where(array('ct.payment_type' => 'contra'));
        $builder->where(array('DATE(ct.receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(ct.receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $contra_CurrentAssets = $query->getResultArray();
       
        foreach ($contra_CurrentAssets as $row) {
            $total = ((@$tot_currentassets[$row['account_name']]['contra_total']) ? $tot_currentassets[$row['account_name']]['contra_total'] : 0) - $row['total'];   
            
            $tot_currentassets[$row['account_name']]['contra_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = @$row['account_id'];
        }


        $contra_ac_CurrentAssets = array();     
        $builder = $db->table('gl_group gl');
        $builder->select('ac.id as account_id,gl.name as gl_name,ct.amount as total,ac.name as account_name');
        $builder->join('account ac', 'gl.id = ac.gl_group');
        $builder->join('bank_tras ct', 'ct.account = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('ct.is_delete' => '0'));
        $builder->where(array('ct.payment_type' => 'contra'));
        $builder->where(array('DATE(ct.receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(ct.receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $contra_ac_CurrentAssets = $query->getResultArray();
    
        foreach ($contra_ac_CurrentAssets as $row) {
            
            $total = ((@$tot_currentassets[$row['account_name']]['contra_ac_total']) ? $tot_currentassets[$row['account_name']]['contra_ac_total'] : 0) + $row['total'];
           
            $tot_currentassets[$row['account_name']]['contra_ac_total'] = $total;
            $tot_currentassets[$row['account_name']]['account_id'] = @$row['account_id'];
        }
        
        $total_arr = array();

        foreach ($tot_currentassets as $key => $value) {
            $tot_currentassets[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['ac_total'] + @$value['sales_total'] - @$value['sales_ret_total'] + @$value['mill_sales_total'] - @$value['mill_sales_ret_total'] + @$value['contra_ac_total'] + @$value['contra_total'] + @$value['general'] - @$value['return'] + @$value['opening_total'];
            $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['ac_total'] + @$value['sales_total'] - @$value['sales_ret_total'] + @$value['mill_sales_total'] - @$value['mill_sales_ret_total'] + @$value['contra_ac_total'] + @$value['contra_total'] + @$value['general'] - @$value['return'] + @$value['opening_total'];
        }
        
        if(!empty($total_arr)) {
            $current_assets_total = array_sum($total_arr);
        }else {
            $current_assets_total = 0;
        }
        
        $arr['account'] = $tot_currentassets;
        $arr['total'] = $current_assets_total;
       
        
        return $arr;
}

function Other_Assets_data($id,$start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $bank_FixedAssets = array();     
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.payment_type !=' => 'contra'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_OtherAssets = $query->getResultArray();
    
    $tot_otherassets = array();
    foreach ($bank_OtherAssets as $row) {
        
        if($row['mode'] == 'Payment'){
            $total = ((@$tot_otherassets[$row['account_name']]['bt_total']) ? $tot_otherassets[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        }else{
            $total = ((@$tot_otherassets[$row['account_name']]['bt_total']) ? $tot_otherassets[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }
        $tot_otherassets[$row['account_name']]['bt_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $jv_FixedAssets = array();
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->join('jv_main jm', 'jm.id = jv.jv_id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jm.is_delete' => '0'));
    $builder->where(array('DATE(jm.date)  >= ' => $start_date));
    $builder->where(array('DATE(jm.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_OtherAssets = $query->getResultArray();
   
    foreach($jv_OtherAssets as $row) {
        if($row['dr_cr'] == 'cr'){
            $total = ((@$tot_otherassets[$row['account_name']]['jv_total']) ? $tot_otherassets[$row['account_name']]['jv_total'] : 0) + $row['total'];
        }else{
            $total = ((@$tot_otherassets[$row['account_name']]['jv_total']) ? $tot_otherassets[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }
        $tot_otherassets[$row['account_name']]['jv_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = $row['account_id'];
    }
    
    $sales_OtherAssets = array();
    
    $builder = $db->table('gl_group gl');
    $builder->select('sa.type as type,sa.amount as total,gl.name as gl_name, ac.name as account_name');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu sa', 'sa.account = ac.id');
    $builder->join('sales_ACinvoice si', 'si.id = sa.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sa.is_delete' => '0'));
    $builder->where(array('si.is_delete' => '0'));
    $builder->where(array('DATE(sa.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(sa.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $sales_OtherAssets = $query->getResultArray();
   
    foreach($sales_OtherAssets as $row) {
        if($row['type'] == 'general'){
            $gen_total = ((@$tot_otherassets[$row['account_name']]['general']) ? $tot_otherassets[$row['account_name']]['general'] : 0) + $row['total'];
        }else{
            $ret_total = ((@$tot_otherassets[$row['account_name']]['return']) ? $tot_otherassets[$row['account_name']]['return'] : 0) - $row['total'];
        }
        $total =  @$gen_total - @$ret_total;
        $tot_otherassets[$row['account_name']]['sale_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = @$row['account_id'];
    }
    
    $purchase_OtherAssets = array();
    
    $builder = $db->table('gl_group gl');
    $builder->select('pg.type as type,pg.amount as total,gl.name as gl_name, ac.name as account_name');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_particu pg', 'pg.account = ac.id');
    $builder->join('purchase_general pn', 'pn.id = pg.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(pg.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_OtherAssets = $query->getResultArray();
  
    foreach($purchase_OtherAssets as $row) {
        if($row['type'] == 'general'){
            $gen_total = ((@$tot_otherassets[$row['account_name']]['general']) ? $tot_otherassets[$row['account_name']]['general'] : 0) + $row['total'];
        }else{
            $ret_total = ((@$tot_otherassets[$row['account_name']]['return']) ? $tot_otherassets[$row['account_name']]['return'] : 0) - $row['total'];
        }
        $total = $gen_total - $ret_total;
        $tot_otherassets[$row['account_name']]['purchase_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = @$row['account_id'];
    }

    $total_arr = array();

    foreach ($tot_otherassets as $key => $value) {
        $tot_otherassets[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'];
    }
    
    if(!empty($total_arr)){
        $other_assets_total = array_sum($total_arr);
    }else {
        $other_assets_total = 0;
    }

    $arr['account'] = $tot_otherassets;
    $arr['total'] = $other_assets_total;
    
    return $arr;
}

//********** END Balance Sheet DATA ***********//
// update trupti 26-12-2022 duties and taxes add taxes account
function get_generalPurchase_monthly($start_date, $end_date, $id)
{

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('purchase_general pg');
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('purchase_particu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.party_account' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    $arr = array();
    $tot_expence = array();
    foreach ($pg_expence as $row) {

      
        $total = ((@$tot_expence['generalPurchase'][$row['month']][$row['pg_type']]) ? $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalPurchase'][$row['month']]['total'] = (float)@$tot_expence['generalPurchase'][$row['month']]['general'] - (float) @$tot_expence['generalPurchase'][$row['month']]['return'];
        $tot_expence['generalPurchase'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalPurchase'][$row['month']]['month'] = $row['month'];

    }

    $builder = $db->table('purchase_general pg');
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('purchase_particu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.igst_acc' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence_igst = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    //$arr = array();
    //$pg_expence_igst = array();
    foreach ($pg_expence_igst as $row) {

      
        $total = ((@$tot_expence['generalPurchase'][$row['month']][$row['pg_type']]) ? $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalPurchase'][$row['month']]['total'] = (float)@$tot_expence['generalPurchase'][$row['month']]['general'] - (float) @$tot_expence['generalPurchase'][$row['month']]['return'];
        $tot_expence['generalPurchase'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalPurchase'][$row['month']]['month'] = $row['month'];

    }

    $builder = $db->table('purchase_general pg');
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('purchase_particu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.cgst_acc' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence_cgst = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    //$arr = array();
    //$pg_expence_cgst = array();
    foreach ($pg_expence_cgst as $row) {

      
        $total = ((@$tot_expence['generalPurchase'][$row['month']][$row['pg_type']]) ? $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalPurchase'][$row['month']]['total'] = (float)@$tot_expence['generalPurchase'][$row['month']]['general'] - (float) @$tot_expence['generalPurchase'][$row['month']]['return'];
        $tot_expence['generalPurchase'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalPurchase'][$row['month']]['month'] = $row['month'];

    }

    $builder = $db->table('purchase_general pg');
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('purchase_particu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.sgst_acc' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence_sgst = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    //$arr = array();
    //$pg_expence_sgst = array();
    foreach ($pg_expence_sgst as $row) {

      
        $total = ((@$tot_expence['generalPurchase'][$row['month']][$row['pg_type']]) ? $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalPurchase'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalPurchase'][$row['month']]['total'] = (float)@$tot_expence['generalPurchase'][$row['month']]['general'] - (float) @$tot_expence['generalPurchase'][$row['month']]['return'];
        $tot_expence['generalPurchase'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalPurchase'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = $tot_expence;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    return $result;
}


function get_capital_account_wise($start_date,$end_date,$id){

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $capital = array();
         
        $gmodel  = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array('id'=>$id),'opening_bal,opening_type');

        $capital['opening']['total']=0;

        if($acc['opening_type'] == 'Debit'){
            $capital['opening']['total'] -= (float)@$acc['opening_bal'];
        }else{
            $capital['opening']['total'] += (float)@$acc['opening_bal'];
        }
    
        $builder = $db->table('bank_tras bt');
        $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
        $builder->join('account ac', 'ac.id ='.$id);
        $builder->where(array('bt.particular' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $bank_expence = $query->getResultArray();
        
        $capital['bank_trans']['total'] = 0;
        
        $total = 0;
        foreach ($bank_expence as $row) {
            
            if($row['mode'] == 'Payment'){
                $total -= $row['bt_total'];
            }else{
                $total +=$row['bt_total'];
            }
            
        }
        $capital['bank_trans']['total'] = $total;

        $builder = $db->table('jv_particular jv');
        $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
        $builder->join('account ac', 'ac.id = jv.particular');
        $builder->where('jv.particular',$id);
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('jv.is_delete' => '0'));
        $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $jv_expence = $query->getResultArray();
        

        $capital['jv_parti']['total'] = 0;
    $total = 0;
    
    foreach ($jv_expence as $row) {
        if($row['dr_cr'] == 'cr'){
            $total += $row['total'];
        }else{
            $total -= $row['total'];
        }
        
    }
    $capital['jv_parti']['total'] += $total;


    $capital['from'] =$start_date; 
    $capital['to'] =$end_date;
    $capital['id'] =$id;
    return $capital;
}

function get_loan_account_wise($start_date,$end_date,$id){

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $loan = array();

    $gmodel  = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array('id'=>$id),'opening_bal,opening_type');

        $loan['opening']['total']=0;

        if($acc['opening_type'] == 'Debit'){
            $loan['opening']['total'] -= (float)@$acc['opening_bal'];
        }else{
            $loan['opening']['total'] += (float)@$acc['opening_bal'];
        }
            
        $builder = $db->table('bank_tras bt');
        $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
        $builder->join('account ac', 'ac.id ='.$id);
        $builder->where(array('bt.particular' => $id));
        $builder->where(array('ac.is_delete' => '0','bt.is_delete' => '0'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $bank_expence = $query->getResultArray();
        
        $loan['bank_trans']['total'] = 0;
        
        $total = 0;
        foreach ($bank_expence as $row) {
            
            if($row['mode'] == 'Payment'){
                $total -= $row['bt_total'];
            }else{
                $total +=$row['bt_total'];
            }
            
        }
        $loan['bank_trans']['total'] = $total;

        $builder = $db->table('jv_particular jv');
        $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
        $builder->join('account ac', 'ac.id = jv.particular');
        $builder->where('jv.particular',$id);
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('jv.is_delete' => '0'));
        $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $jv_expence = $query->getResultArray();
        

        $loan['jv_parti']['total'] = 0;
    $total = 0;
    
    foreach ($jv_expence as $row) {
        if($row['dr_cr'] == 'cr'){
            $total += $row['total'];
        }else{
            $total -= $row['total'];
        }
        
    }
    $loan['jv_parti']['total'] += $total;


    $loan['from'] =$start_date; 
    $loan['to'] =$end_date;
    $loan['id'] =$id;

    return $loan;
}

function get_current_lib_account_wise($start_date,$end_date,$id){

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $current_lib = array();

    $gmodel  = new GeneralModel();
        $acc = $gmodel->get_data_table('account',array('id'=>$id),'opening_bal,opening_type');

        $current_lib['opening']['total']=0;

        if($acc['opening_type'] == 'Debit'){
            $current_lib['opening']['total'] -= (float)@$acc['opening_bal'];
        }else{
            $current_lib['opening']['total'] += (float)@$acc['opening_bal'];
        }

    $builder = $db->table('purchase_particu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.party_account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence = $query->getResultArray();
  

    $total = 0;

    foreach ($pg_expence as $row) {

        $total = (((float) @$current_lib['general_purchase'][$row['pg_type']]) ? (float) $current_lib['general_purchase'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        
        $current_lib['general_purchase'][$row['pg_type']] = $total;
        
        $current_lib['general_purchase']['total'] = (float)@$current_lib['general_purchase']['general'] - (float)@$current_lib['general_purchase']['return'];
    }

    

        
        $builder = $db->table('bank_tras bt');
        $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
        $builder->join('account ac', 'ac.id ='.$id);
        $builder->where(array('bt.particular' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('bt.is_delete' => '0'));
        $builder->where(array('bt.payment_type !=' => 'contra'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $bank_expence = $query->getResultArray();
        
        $current_lib['bank_trans']['total'] = 0;
        
        $total = 0;
        foreach ($bank_expence as $row) {
            
            if($row['mode'] == 'Payment'){
                $total -= $row['bt_total'];
            }else{
                $total +=$row['bt_total'];
            }
            
        }
        $current_lib['bank_trans']['total'] = $total;

        $builder = $db->table('jv_particular jv');
        $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
        $builder->join('account ac', 'ac.id = jv.particular');
        $builder->join('jv_main jm', 'jm.id = jv.jv_id');
        $builder->where('jv.particular',$id);
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('jv.is_delete' => '0'));
        $builder->where(array('jm.is_delete' => '0'));
        $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $jv_expence = $query->getResultArray();
        

        $current_lib['jv_parti']['total'] = 0;
    $total = 0;
    
    foreach ($jv_expence as $row) {
        if($row['dr_cr'] == 'cr'){
            $total += $row['total'];
        }else{
            $total -= $row['total'];
        }
        
    }
    $current_lib['jv_parti']['total'] += $total;


    $purchase = array();

    $builder = $db->table('purchase_invoice pi');
    $builder->select('ac.id as account_id,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.account =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase = $query->getResultArray();
    
    $total = 0;

    foreach($purchase as $row){
        // $after_disc=0;
    
        // if($row['disc_type'] == '%'){
        //     $row['total'] = ((float)$row['total'] * ((float)$row['discount'] / 100));
        //     $after_disc =  $row['total'];
        // }else{
        //     $row['total'] = (float)$row['total'] -  (float)$row['discount'];
        //     $after_disc =  $row['total'];
        // }
      
        // if($row['amty_type'] == '%'){
        //     $row['total'] = (float)$row['total'] + ((float)$after_disc * ((float)$row['amty'] / 100));
        // }else{
        //     $row['total'] = (float)$row['total'] + (float)$row['amty']; 
        // }

        $total += $row['total'];
      
    }
    @$current_lib['purchase']['total'] += $total;

    $purchase_return = array();

    $builder = $db->table('purchase_return pi');
    $builder->select('ac.id as account_id,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.account =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();

    
    $total = 0;
    
    foreach($purchase_return as $row){
        // $after_disc=0;
    
        // if($row['disc_type'] == '%'){
        //     $row['total'] = ((float)$row['total'] * ((float)$row['discount'] / 100));
        //     $after_disc =  $row['total'];
        // }else{
        //     $row['total'] = (float)$row['total'] -  (float)$row['discount'];
        //     $after_disc =  $row['total'];            
        // }
        
        // if($row['amty_type'] == '%'){
        //     $row['total'] = (float)$row['total'] + ((float)$after_disc * ((float)$row['amty'] / 100));
        // }else{
        //     $row['total'] = (float)$row['total'] + (float)$row['amty']; 
        // }

        $total +=$row['total'];
        
    }
    @$current_lib['purchase_return']['total'] -= $total;

   // update trupti 26-12-2022 duties and taxes add taxes account
   $gst_data =gst_account_data($id,$start_date,$end_date);
   //echo '<pre>';Print_r($gst_data);exit;
   @$current_lib['sales_general_igst']['total'] = $gst_data['sales_general_igst']['total'];
   @$current_lib['sales_general_sgst']['total'] = $gst_data['sales_general_sgst']['total'];
   @$current_lib['sales_general_sgst']['total'] = $gst_data['sales_general_sgst']['total'];

   @$current_lib['sales_igst']['total'] = $gst_data['sales_igst']['total'];
   @$current_lib['sales_cgst']['total'] = $gst_data['sales_cgst']['total'];
   @$current_lib['sales_sgst']['total'] = $gst_data['sales_sgst']['total'];

   @$current_lib['sales_return_igst']['total'] = $gst_data['sales_return_igst']['total'];
   @$current_lib['sales_return_cgst']['total'] = $gst_data['sales_return_cgst']['total'];
   @$current_lib['sales_return_sgst']['total'] = $gst_data['sales_return_sgst']['total'];
   
   @$current_lib['purchase_general_igst']['total'] = $gst_data['purchase_general_igst']['total'];
   @$current_lib['purchase_general_cgst']['total'] = $gst_data['purchase_general_cgst']['total'];
   @$current_lib['purchase_general_sgst']['total'] = $gst_data['purchase_general_sgst']['total'];

   @$current_lib['purchase_igst']['total'] = $gst_data['purchase_igst']['total'];
   @$current_lib['purchase_cgst']['total'] = $gst_data['purchase_cgst']['total'];
   @$current_lib['purchase_sgst']['total'] = $gst_data['purchase_sgst']['total'];

   @$current_lib['purchase_return_igst']['total'] = $gst_data['purchase_return_igst']['total'];
   @$current_lib['purchase_return_cgst']['total'] = $gst_data['purchase_return_cgst']['total'];
   @$current_lib['purchase_return_sgst']['total'] = $gst_data['purchase_return_sgst']['total'];

    $current_lib['from'] =$start_date; 
    $current_lib['to'] =$end_date;
    $current_lib['id'] =$id;



    return $current_lib;
}

function get_fixedassets_account_wise($start_date,$end_date,$id){

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $tot_fixedassets = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account',array('id'=>$id),'opening_bal,opening_type');

    $tot_fixedassets['opening']['total']=0;

    if($acc['opening_type'] == 'Debit'){
        $tot_fixedassets['opening']['total'] += (float)@$acc['opening_bal'];
    }else{
        $tot_fixedassets['opening']['total'] -= (float)@$acc['opening_bal'];
    }

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_FixedAssets = $query->getResultArray();
    
    $tot_fixedassets['per_bank_trans']['total'] = 0;
    
    $total = 0;

    foreach ($bank_FixedAssets as $row) {
        
        if($row['mode'] == 'Payment'){
            $total += $row['bt_total'];
        }else{
            $total -=$row['bt_total'];
        }
        
    }
    $tot_fixedassets['per_bank_trans']['total'] += $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->where('jv.particular',$id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_FixedAssets = $query->getResultArray();
    $tot_fixedassets['jv_fixedassets']['total'] = 0;
    $total = 0;
    
    foreach ($jv_FixedAssets as $row) {
        if($row['dr_cr'] == 'cr'){
            $total -= $row['total'];
        }else{
            $total += $row['total'];
        }
        
    }
    $tot_fixedassets['jv_fixedassets']['total'] += $total;

    $builder = $db->table('purchase_particu pp');
    $builder->select('ac.id as account_id,pp.amount as total,ac.name as account_name,pp.type');
    $builder->join('purchase_general pg', 'pp.parent_id = pg.id');
    $builder->join('account ac', 'ac.id = pp.account');
    $builder->where('pp.account',$id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $expence_FixedAssets = $query->getResultArray();
    $tot_fixedassets['expence_fixedassets']['total'] = 0;
    $total = 0;
    
    foreach ($expence_FixedAssets as $row) {
        if($row['type'] == 'general'){
            $total += $row['total'];
        }else{
            $total -= $row['total'];
        }
        
    }
    $tot_fixedassets['expence_fixedassets']['total'] += $total;

    
    $builder = $db->table('sales_ACparticu pp');
    $builder->select('ac.id as account_id,pp.amount as total,ac.name as account_name,pp.type');
    $builder->join('sales_ACinvoice pg', 'pp.parent_id = pg.id');
    $builder->join('account ac', 'ac.id = pp.account');
    $builder->where('pp.account',$id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $income_FixedAssets = $query->getResultArray();
    $tot_fixedassets['income_fixedassets']['total'] = 0;
    $total = 0;
    
    foreach ($income_FixedAssets as $row) {
        if($row['type'] == 'general'){
            $total += $row['total'];
        }else{
            $total -= $row['total'];
        }
        
    }
    $tot_fixedassets['income_fixedassets']['total'] += $total;



    $tot_fixedassets['from'] =$start_date; 
    $tot_fixedassets['to'] =$end_date;
    $tot_fixedassets['id'] =$id;

    return $tot_fixedassets;
}

function get_currentassets_account_wise($start_date,$end_date,$id){

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
        } else {
            $year = date('Y');
        }
        $start_date = $year . '-04-01';
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $tot_currentassets = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account',array('id'=>$id),'opening_bal,opening_type');

    $tot_currentassets['opening']['total']=0;

    if($acc['opening_type'] == 'Debit'){
        $tot_currentassets['opening']['total'] += (float)@$acc['opening_bal'];
    }else{
        $tot_currentassets['opening']['total'] -= (float)@$acc['opening_bal'];
    }

    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,ac.name as account_name,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'ac.id = ' . $id);
    // $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.party_account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_income = $query->getResultArray();


    $genral_total = 0;
    $return_total = 0;
    foreach ($pg_income as $row) {

        if($row['pg_type'] == 'general'){
            $genral_total += $row['pg_amount'];
        }else{
            $return_total += $row['pg_amount'];
        }
    }

    @$tot_currentassets['general_sales']['total'] += $genral_total;
    @$tot_currentassets['general_sales_return']['total'] += $return_total;
    
    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('bt.payment_type !=' => 'contra'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => 0));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_CurrentAssets = $query->getResultArray();
    
    $tot_currentassets['per_bank_trans']['total'] = 0;
    
    $total = 0;

    foreach ($bank_CurrentAssets as $row) {
        if($row['mode'] == 'Payment'){
            $total += $row['bt_total'];
        }else{
            $total -=$row['bt_total'];
        }    
    }

    $tot_currentassets['per_bank_trans']['total'] += $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->join('jv_main jm', 'jm.id = jv.jv_id');
    $builder->where('jv.particular',$id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jm.is_delete' => 0));
    $builder->where(array('jv.is_delete' => 0));
    $builder->where(array('DATE(jm.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jm.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_CurrentAssets = $query->getResultArray();
    $tot_currentassets['jv_currentassets']['total'] = 0;
    $total = 0;
    
    foreach ($jv_CurrentAssets as $row) {
        if($row['dr_cr'] == 'cr'){
            $total -= $row['total'];
        }else{
            $total += $row['total'];
        }
    }

    $tot_currentassets['jv_currentassets']['total'] += $total;

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('bt.account' => $id));
    $builder->where(array('bt.payment_type !=' => 'contra'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => 0));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $ac_bank_CurrentAssets = $query->getResultArray();

    
    $tot_currentassets['ac_bank_trans']['total'] = 0;
    $total = 0;
    
    foreach ($ac_bank_CurrentAssets as $row) {   
        if($row['mode'] == 'Payment'){
            $tot_currentassets['ac_bank_trans']['total'] -= $row['bt_total'];
        }else{
            $tot_currentassets['ac_bank_trans']['total'] +=$row['bt_total'];
        }
    }   

    $total = 0;

    $builder = $db->table('sales_invoice pi');
    $builder->select('ac.id as account_id,pi.net_amount as total,ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'ac.id = pi.account','left');
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => 0));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray(); 

    foreach ($sales_invoice as $row) {
        $total += $row['total'];   
    }

    @$tot_currentassets['sales_invoice']['total'] += $total;

    // $tot_currentassets['sales_return']['total'] = 0;
    $total = 0;

    $builder = $db->table('sales_return pi');
    $builder->select('ac.id as account_id,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => 0));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    foreach ($sales_return as $row) {
        $after_disc=0;
        if($row['disc_type'] == '%'){
            $row['total'] = ((float)$row['total'] * ((float)$row['discount'] / 100));
            $after_disc =  $row['total'];
        }else{
            $row['total'] = (float)$row['total'] -  (float)$row['discount'];
            $after_disc =  $row['total'];            
        }
        
        if($row['amty_type'] == 'Fixed'){
            $row['total'] = (float)$row['total'] + ((float)$after_disc * ((float)$row['amty'] / 100));
        }else{
            $row['total'] = (float)$row['total'] + (float)$row['amty'];
        }

        $total += $row['total'];
    }

    @$tot_currentassets['sales_return']['total'] -= $total;

    $total = 0;

    $builder = $db->table('saleMillInvoice gi');
    $builder->select('ac.id as account_id,gi.total_amount as total, ac.name as account_name,gi.amty,gi.amty_type,gi.discount,gi.disc_type');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('gi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(gi.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(gi.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $mill_sales = $query->getResultArray();

    foreach ($mill_sales as $row) {
        $total += $row['total'];
    }

    @$tot_currentassets['mill_sales']['total'] += $total;

    $total = 0;

        $builder = $db->table('saleMillReturn pi');
        $builder->select('ac.id as account_id,pi.total_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
        $builder->join('account ac', 'ac.id ='.$id);
        $builder->where(array('pi.account' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pi.is_delete' => '0'));
        $builder->where(array('DATE(pi.date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $mill_sales_return = $query->getResultArray();

        foreach ($mill_sales_return as $row) {
            $total += $row['total'];
        }

        @$tot_currentassets['mill_sales_return']['total'] -= $total;

        $total = 0;
        
        $builder = $db->table('bank_tras ct');
        $builder->select('ac.id as account_id,ac.name as account_name,ct.amount as bt_total,ct.narration');
        $builder->join('account ac', 'ac.id ='.$id);
        $builder->where(array('ct.particular' => $id));
        $builder->where(array('ct.payment_type' => 'contra'));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('ct.is_delete' => '0'));
        $builder->where(array('DATE(ct.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(ct.receipt_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $contra_CurrentAssets = $query->getResultArray();
        
        foreach ($contra_CurrentAssets as $row) {
                $total -= $row['bt_total'];
        }
        
        @$tot_currentassets['per_contra_trans']['total'] += $total;
    
        $total = 0;
        
        $builder = $db->table('bank_tras ct');
        $builder->select('ac.id as account_id,ac.name as account_name,ct.amount as bt_total,ct.narration');
        $builder->join('account ac', 'ac.id ='.$id);
        $builder->where(array('ct.account' => $id));
        $builder->where(array('ct.payment_type' => 'contra'));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('ct.is_delete' => '0'));
        $builder->where(array('DATE(ct.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(ct.receipt_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $ac_contra_CurrentAssets = $query->getResultArray();
        
        foreach ($ac_contra_CurrentAssets as $row) {
                $total += $row['bt_total'];
        }

        @$tot_currentassets['ac_contra_trans']['total'] += $total;

    $tot_currentassets['from'] =$start_date; 
    $tot_currentassets['to'] =$end_date;
    $tot_currentassets['id'] =$id;

    return $tot_currentassets;
}

function get_currentassets_banktrans_monthly_PerWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =bt.particular');
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_income = $query->getResultArray();
    
    $total = 0;
    $arr = array();
    // echo '<pre><br> detail :';print_r($bank_income);

    foreach ($bank_income as $row) {
        
        if($row['mode'] == 'Receipt'){
            
            $rec_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $rec_total;
            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) -  $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
            
        }else{

            $pay_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $pay_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) +  $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        }
    }
    // echo '<pre>';print_r($arr);exit;
    $result = array();
    $result['bankcash'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}   

function get_currentassets_jv_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('jv_particular jv');
    $builder->select('jm.id,MONTH(jv.date) as month,YEAR(jv.date) as year,ac.id as account_id,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id =jv.particular');
    $builder->join('jv_main jm', 'jm.id =jv.jv_id');
    $builder->where(array('jv.particular' => $id));
    $builder->where(array('jm.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jm.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jm.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_income = $query->getResultArray();

    $total = 0;   
    $arr = array();
    
    foreach ($jv_income as $row) {
        if($row['dr_cr'] == 'cr'){
            $cr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) +  $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $cr_tot;

            @$arr[$row['month']]['total'] -= $row['total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }else{

            $dr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) +  $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $dr_tot;

            @$arr[$row['month']]['total'] += $row['total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }
    }

    $result = array();
    $result['jv'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}

function get_currentassets_salesinvoice_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $builder = $db->table('sales_invoice si');
    $builder->select('MONTH(si.invoice_date) as month,YEAR(si.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(si.net_amount) as total');
    $builder->join('account ac', 'ac.id =si.account');
    $builder->where(array('si.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('si.is_delete' => '0'));
    $builder->where(array('si.is_cancle' => '0'));
    $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(si.invoice_date)');
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();

    $arr = array();

    foreach ($sales_invoice as $row) { 
            @$arr[$row['month']] = $row;
    }

    $result = array();
    $result['salesinvoice'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    return $result;
}

function get_currentassets_salesreturn_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('sales_return si');
    $builder->select('MONTH(si.return_date) as month,YEAR(si.return_date) as year,ac.id as account_id,ac.name as account_name,SUM(si.net_amount) as bt_total');
    $builder->join('account ac', 'ac.id =si.account');
    $builder->where(array('si.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('si.is_delete' => '0'));
    $builder->where(array('DATE(si.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(si.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(si.return_date)');
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    $arr = array();

    foreach ($sales_return as $row) {
     
            @$arr[$row['month']] = $row;
            
    }
    $result = array();
    $result['salesreturn'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}


function get_currentassets_gnrl_sales_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pg.net_amount) as total');
    $builder->join('account ac', 'ac.id = pg.party_account');
    $builder->where(array('pg.v_type' => "general"));
    $builder->where(array('pg.party_account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pg.invoice_date)');
    $query = $builder->get();
    $sales_general = $query->getResultArray();


    $arr = array();

    foreach ($sales_general as $row) { 
            @$arr[$row['month']] = $row;
    }

    $result = array();
    $result['salesinvoice'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    
    return $result;
}

function get_currentassets_gnrl_salesreturn_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pg.net_amount) as total');
    $builder->join('account ac', 'ac.id = pg.party_account');
    $builder->where(array('pg.v_type' => "return"));
    $builder->where(array('pg.party_account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pg.invoice_date)');
    $query = $builder->get();
    $sales_general = $query->getResultArray();


    $arr = array();

    foreach ($sales_general as $row) { 
            @$arr[$row['month']] = $row;
    }

    $result = array();
    $result['salesreturn'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    
    return $result;
}

function get_currentassets_millsales_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('saleMillInvoice si');
    $builder->select('MONTH(si.date) as month,YEAR(si.date) as year,ac.id as account_id,ac.name as account_name,si.total_amount as bt_total');
    $builder->join('account ac', 'ac.id =si.account');
    $builder->where(array('si.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('si.is_delete' => '0'));
    $builder->where(array('DATE(si.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(si.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $mill_sales = $query->getResultArray();

    $arr = array();

    foreach ($mill_sales as $row) {
     
            @$arr[$row['month']]['total'] += $row['bt_total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
    }
    $result = array();
    $result['millsales'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}

function get_currentassets_millsalesreturn_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('saleMillReturn si');
    $builder->select('MONTH(si.date) as month,YEAR(si.date) as year,ac.id as account_id,ac.name as account_name,si.total_amount as bt_total');
    $builder->join('account ac', 'ac.id =si.account');
    $builder->where(array('si.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('si.is_delete' => '0'));
    $builder->where(array('DATE(si.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(si.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $mill_sales_return = $query->getResultArray();

    $arr = array();

    foreach ($mill_sales_return as $row) {
     
            @$arr[$row['month']]['total'] += $row['bt_total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
    }
    $result = array();
    $result['millsalesreturn'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}

function get_currentassets_banktrans_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =bt.account');
    $builder->where(array('bt.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('bt.payment_type !=' => 'contra'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_income = $query->getResultArray();
    
    $total = 0;
    $arr = array();

    foreach ($bank_income as $row) {
        
        if($row['mode'] == 'Payment'){
            
            $pay_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $pay_total;

            @$arr[$row['month']]['total'] -= $row['bt_total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
            
        }else{

            $rec_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $rec_total;

            if($row['mode'] == 'Receipt'){
                @$arr[$row['month']]['total'] += $row['bt_total'];
            }

            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }
    }

    $result = array();
    $result['bankcash'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    return $result;
}


function get_currentassets_contra_monthly_PerWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('bank_tras ct');
    $builder->select('MONTH(ct.receipt_date) as month,YEAR(ct.receipt_date) as year,ac.id as account_id,ac.name as account_name,ct.amount as bt_total');
    $builder->join('account ac', 'ac.id =ct.particular');
    $builder->where(array('ct.particular' => $id));
    $builder->where(array('ct.payment_type' => 'contra'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('ct.is_delete' => '0'));
    $builder->where(array('DATE(ct.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(ct.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $contra_income = $query->getResultArray();
    
    $total = 0;
    $arr = array();

    foreach ($contra_income as $row) {
            @$arr[$row['month']]['total'] -= $row['bt_total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name']; 
    }

    $result = array();
    $result['per_contra'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    return $result;
}

function get_currentassets_contra_monthly_AcWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('bank_tras ct');
    $builder->select('MONTH(ct.receipt_date) as month,YEAR(ct.receipt_date) as year,ac.id as account_id,ac.name as account_name,ct.amount as bt_total,ct.narration');
    $builder->join('account ac', 'ac.id =ct.account');
    $builder->where(array('ct.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('ct.is_delete' => '0'));
    $builder->where(array('ct.payment_type' => 'contra'));
    $builder->where(array('DATE(ct.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(ct.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $contra_income = $query->getResultArray();
    
    $total = 0;
    $arr = array();

    foreach ($contra_income as $row) {
       
            @$arr[$row['month']]['total'] += $row['bt_total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
            
      
    }
    $result = array();
    $result['ac_contra'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}

function get_fixedassets_banktrans_monthly_PerWise($start_date,$end_date,$id)
{
    $db = \Config\Database::connect();

    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =bt.particular');
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_income = $query->getResultArray();
    
    $total = 0;
    $arr = array();
    // echo '<pre><br> detail :';print_r($bank_income);

    foreach ($bank_income as $row) {
        
        if($row['mode'] == 'Receipt'){
            
            $rec_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $rec_total;
            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) -  $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
            
        }else{

            $pay_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $pay_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) +  $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        }
    }
    // echo '<pre>';print_r($arr);exit;
    $result = array();
    $result['bankcash'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}

function get_fixedassets_jv_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('jv_particular jv');
    $builder->select('MONTH(jv.date) as month,YEAR(jv.date) as year,ac.id as account_id,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id =jv.particular');
    $builder->where(array('jv.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_income = $query->getResultArray();
    
    $total = 0;   
    $arr = array();
    
    foreach ($jv_income as $row) {
        if($row['dr_cr'] == 'cr'){
            $cr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) +  $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $cr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }else{

            $dr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) +  $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $dr_tot;


            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }
    }

    $result = array();
    $result['jv'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;
    return $result;
}

function get_purchase_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('purchase_invoice pi');
    $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.net_amount) as total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.invoice_date)');
    $query = $builder->get();
    $purchase = $query->getResultArray();
    $total = 0;
    $arr = array();

    foreach ($purchase as $row) {

        $arr[$row['month']] = $row;
        // $arr[$row['month']]['total'] = $total;
    }
    // echo '<pre>';print_r($arr);exit;
     // update trupti 26-12-2022 duties and taxes add taxes account
     $builder = $db->table('purchase_invoice pi');
     $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_igst) as total');
     $builder->join('account ac', 'ac.id =pi.igst_acc');
     $builder->where(array('pi.igst_acc' => $id));
     $builder->where(array('ac.is_delete' => '0'));
     $builder->where(array('pi.is_delete' => '0'));
     $builder->where(array('pi.is_cancle' => '0'));
     $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
     $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
     $builder->groupBy('MONTH(pi.invoice_date)');
     $query = $builder->get();
     $purchase_igst = $query->getResultArray();
     $total = 0;
     foreach ($purchase_igst as $row) {
         $arr[$row['month']] = $row;
     }
    
     $builder = $db->table('purchase_invoice pi');
     $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_cgst) as total');
     $builder->join('account ac', 'ac.id =pi.cgst_acc');
     $builder->where(array('pi.cgst_acc' => $id));
     $builder->where(array('ac.is_delete' => '0'));
     $builder->where(array('pi.is_delete' => '0'));
     $builder->where(array('pi.is_cancle' => '0'));
     $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
     $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
     $builder->groupBy('MONTH(pi.invoice_date)');
     $query = $builder->get();
     $purchase_cgst = $query->getResultArray();
     $total = 0;
     
     foreach ($purchase_cgst as $row) {  
         $arr[$row['month']] = $row;
     }
     $builder = $db->table('purchase_invoice pi');
     $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_sgst) as total');
     $builder->join('account ac', 'ac.id =pi.sgst_acc');
     $builder->where(array('pi.sgst_acc' => $id));
     $builder->where(array('ac.is_delete' => '0'));
     $builder->where(array('pi.is_delete' => '0'));
     $builder->where(array('pi.is_cancle' => '0'));
     $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
     $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
     $builder->groupBy('MONTH(pi.invoice_date)');
     $query = $builder->get();
     $purchase_sgst = $query->getResultArray();
     $total = 0;
     
     foreach ($purchase_sgst as $row) {
         $arr[$row['month']] = $row;
     }
    $result = array();
    $result['purchase'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    
    return $result;
}
// update trupti 26-12-2022 duties and taxes add taxes account
function get_purchase_ret_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('purchase_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,pi.total_amount as pi_total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase = $query->getResultArray();
    
    $total = 0;
    $arr = array();

    foreach ($purchase as $row) {
        $total += $row['pi_total'];
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['total'] = $total;
    }
    $builder = $db->table('purchase_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,pi.tot_igst as pi_total');
    $builder->join('account ac', 'ac.id =pi.igst_acc');
    $builder->where(array('pi.igst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_igst = $query->getResultArray();
    //echo '<pre>';print_r($purchase_igst);exit;
    
    $total = 0;
    // $arr = array();

    foreach ($purchase_igst as $row) {
        $total += $row['pi_total'];
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['total'] = $total;
    }
    //echo '<pre>';print_r($arr);exit;
    $builder = $db->table('purchase_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,pi.tot_cgst as pi_total');
    $builder->join('account ac', 'ac.id =pi.cgst_acc');
    $builder->where(array('pi.cgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_cgst = $query->getResultArray();
    
    $total = 0;
    //$arr = array();

    foreach ($purchase_cgst as $row) {
        $total += $row['pi_total'];
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['total'] = $total;
    }
    $builder = $db->table('purchase_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,pi.tot_sgst as pi_total');
    $builder->join('account ac', 'ac.id =pi.sgst_acc');
    $builder->where(array('pi.sgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_sgst = $query->getResultArray();
    
    $total = 0;
    //$arr = array();

    foreach ($purchase_sgst as $row) {
        $total += $row['pi_total'];
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['total'] = $total;
    }

    $result = array();
    $result['purchase_ret'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    
    return $result;
}

function get_gray_finish_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('grey gi');
    $builder->select('MONTH(gi.inv_date) as month,YEAR(gi.inv_date) as year,ac.id as account_id,ac.name as account_name,gi.total_amount as gi_total,gi.purchase_type as mode');
    $builder->join('account ac', 'ac.id =gi.party_name');
    $builder->where(array('gi.party_name' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('gi.is_delete' => '0'));
    $builder->where(array('DATE(gi.inv_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(gi.inv_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase = $query->getResultArray();
    
    $total = 0;
    $arr = array();

    foreach($purchase as $row) {
        $total += $row['gi_total'];
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['total'] = $total;
    }

    $result = array();
    $result['purchase'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    
    return $result;
}

function get_gray_finish_ret_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('retGrayFinish gi');
    $builder->select('MONTH(gi.date) as month,YEAR(gi.date) as year,ac.id as account_id,ac.name as account_name,gi.total_amount as gi_total,gi.purchase_type as mode');
    $builder->join('account ac', 'ac.id =gi.party_name');
    $builder->where(array('gi.party_name' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('gi.is_delete' => '0'));
    $builder->where(array('DATE(gi.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(gi.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase = $query->getResultArray();
    
    $total = 0;
    $arr = array();

    foreach($purchase as $row) {
        $total += $row['gi_total'];
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['total'] = $total;
    }

    $result = array();
    $result['purchase_ret'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    
    return $result;
}
function gst_gl_group_data($id,$start_date,$end_date)
{
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    // purchase general
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.igst_acc = ac.id','left');
    $builder->join('purchase_particu pp', 'pp.parent_id = pg.id',"left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.cgst_acc = ac.id','left');
    $builder->join('purchase_particu pp', 'pp.parent_id = pg.id',"left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.sgst_acc = ac.id','left');
    $builder->join('purchase_particu pp', 'pp.parent_id = pg.id',"left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense_sgst = $query->getResultArray();
    
    //sales general
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_igst as sg_amount_igst,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.igst_acc = ac.id','left');
    $builder->join('sales_ACparticu pp', 'pp.parent_id = pg.id',"left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sg_expense_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_cgst as sg_amount_cgst,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.cgst_acc = ac.id','left');
    $builder->join('sales_ACparticu pp', 'pp.parent_id = pg.id',"left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sg_expense_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_sgst as sg_amount_sgst,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.sgst_acc = ac.id','left');
    $builder->join('sales_ACparticu pp', 'pp.parent_id = pg.id',"left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sg_expense_sgst = $query->getResultArray();

    // purchase invoice
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_igst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_invoice pi', 'pi.igst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_cgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_invoice pi', 'pi.cgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_sgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_invoice pi', 'pi.sgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_sgst = $query->getResultArray();

    //purchase return
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_igst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_return pi', 'pi.igst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_cgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_return pi', 'pi.cgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_sgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_return pi', 'pi.sgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return_sgst = $query->getResultArray();

    //sales invoice
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_igst as sales_igst_total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_invoice pi', 'pi.igst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_cgst as sales_cgst_total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_invoice pi', 'pi.cgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_sgst as sales_sgst_total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_invoice pi', 'pi.sgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_sgst = $query->getResultArray();

    // sales return
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_igst as sales_return_igst_total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pi', 'pi.igst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_cgst as sales_return_cgst_total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pi', 'pi.cgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.tot_sgst as sales_return_sgst_total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pi', 'pi.sgst_acc = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return_sgst = $query->getResultArray();

    $data['pg_expense_igst'] = $pg_expense_igst;
    $data['pg_expense_cgst'] = $pg_expense_cgst;
    $data['pg_expense_sgst'] = $pg_expense_sgst;

    $data['sg_expense_igst'] = $sg_expense_igst;
    $data['sg_expense_cgst'] = $sg_expense_cgst;
    $data['sg_expense_sgst'] = $sg_expense_sgst;

    $data['purchase_igst'] = $purchase_igst;
    $data['purchase_cgst'] = $purchase_cgst;
    $data['purchase_sgst'] = $purchase_sgst;

    $data['purchase_return_igst'] = $purchase_return_igst;
    $data['purchase_return_cgst'] = $purchase_return_cgst;
    $data['purchase_return_sgst'] = $purchase_return_sgst;

    $data['sales_igst'] = $sales_igst;
    $data['sales_cgst'] = $sales_cgst;
    $data['sales_sgst'] = $sales_sgst;

    $data['sales_return_igst'] = $sales_return_igst;
    $data['sales_return_cgst'] = $sales_return_cgst;
    $data['sales_return_sgst'] = $sales_return_sgst;
   
    return $data;
}

function gst_account_data($id,$start_date,$end_date)
{
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('sales_ACparticu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.igst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sales_general_igst = $query->getResultArray();
    $total = 0;

    foreach ($sales_general_igst as $row) {

        //$total = (((float) @$current_lib['purchase_general_igst'][$row['pg_type']]) ? (float) $current_lib['purchase_general_igst'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        if($row['pg_type'] == 'general')
        {
            $total += (float) $row['pg_amount'];
        }
        else
        {
            $total -= (float) $row['pg_amount'];
        }
        // echo '<pre>';print_r($total);
        //$purchase_general[$row['pg_type']] = $total;
        
    }
    $current_lib['sales_general_igst']['total'] = $total;

    $builder = $db->table('sales_ACparticu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.sgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sales_general_sgst = $query->getResultArray();


    $total = 0;

    foreach ($sales_general_sgst as $row) {

        //$total = (((float) @$current_lib['purchase_general_igst'][$row['pg_type']]) ? (float) $current_lib['purchase_general_igst'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        if($row['pg_type'] == 'general')
        {
            $total += (float) $row['pg_amount'];
        }
        else
        {
            $total -= (float) $row['pg_amount'];
        }
        // echo '<pre>';print_r($total);
        //$purchase_general[$row['pg_type']] = $total;
        
    }
    $current_lib['sales_general_sgst']['total'] = $total;

    $builder = $db->table('sales_ACparticu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.cgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sales_general_cgst = $query->getResultArray();


    $total = 0;

    foreach ($sales_general_cgst as $row) {

        //$total = (((float) @$current_lib['purchase_general_igst'][$row['pg_type']]) ? (float) $current_lib['purchase_general_igst'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        if($row['pg_type'] == 'general')
        {
            $total += (float) $row['pg_amount'];
        }
        else
        {
            $total -= (float) $row['pg_amount'];
        }
        // echo '<pre>';print_r($total);
        //$purchase_general[$row['pg_type']] = $total;
        
    }
    $current_lib['sales_general_cgst']['total'] = $total;

    // sales invoice
    $sales_igst = array();

    $builder = $db->table('sales_invoice pi');
    $builder->select('ac.id as account_id,pi.tot_igst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.igst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_igst = $query->getResultArray();
    
    $total = 0;

    foreach($sales_igst as $row){
        
        $total += $row['total'];
    
    }
    @$current_lib['sales_igst']['total'] = $total;

    $sales_cgst = array();

    $builder = $db->table('sales_invoice pi');
    $builder->select('ac.id as account_id,pi.tot_cgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.cgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_cgst = $query->getResultArray();
    
    $total = 0;

    foreach($sales_cgst as $row){
        
        $total += $row['total'];
    
    }
    @$current_lib['sales_cgst']['total'] = $total;

    $sales_sgst = array();

    $builder = $db->table('sales_invoice pi');
    $builder->select('ac.id as account_id,pi.tot_sgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.sgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_sgst = $query->getResultArray();
    
    $total = 0;

    foreach($sales_sgst as $row){
    
        $total += $row['total'];
    
    }
    @$current_lib['sales_sgst']['total'] += $total;
    // sales return
    $sales_return_igst = array();

    $builder = $db->table('sales_return pi');
    $builder->select('ac.id as account_id,pi.tot_igst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.igst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return_igst = $query->getResultArray();

    
    $total = 0;
    
    foreach($sales_return_igst as $row){
        
        $total +=$row['total'];
        
    }
    @$current_lib['sales_return_igst']['total'] -= $total;

    $sales_return_cgst = array();

    $builder = $db->table('sales_return pi');
    $builder->select('ac.id as account_id,pi.tot_cgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.cgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return_cgst = $query->getResultArray();

    
    $total = 0;
    
    foreach($sales_return_cgst as $row){
        
        $total +=$row['total'];
        
    }
    @$current_lib['sales_return_cgst']['total'] -= $total;

    
    $sales_return_sgst = array();

    $builder = $db->table('sales_return pi');
    $builder->select('ac.id as account_id,pi.tot_sgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.sgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return_sgst = $query->getResultArray();

    
    $total = 0;
    
    foreach($sales_return_sgst as $row){
        
        $total +=$row['total'];
        
    }
    @$current_lib['sales_return_sgst']['total'] -= $total;

    //purchase general igst cgst sgst
    $purchase_general_igst = array();
    $builder = $db->table('purchase_particu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.igst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $purchase_general_igst = $query->getResultArray();
    //echo '<pre>';print_r($purchase_general_igst);exit;

    $total = 0;

    foreach ($purchase_general_igst as $row) {

        //$total = (((float) @$current_lib['purchase_general_igst'][$row['pg_type']]) ? (float) $current_lib['purchase_general_igst'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        if($row['pg_type'] == 'general')
        {
            $total += (float) $row['pg_amount'];
        }
        else
        {
            $total -= (float) $row['pg_amount'];
        }
        // echo '<pre>';print_r($total);
        //$purchase_general[$row['pg_type']] = $total;
        
    }
    $current_lib['purchase_general_igst']['total'] = $total;

    // echo '<pre>';print_r($current_lib);exit;

    $purchase_general_cgst = array();
    $builder = $db->table('purchase_particu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.cgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $purchase_general_cgst = $query->getResultArray();


    $total = 0;

    foreach ($purchase_general_cgst as $row) {

        //$total = (((float) @$current_lib['purchase_general_igst'][$row['pg_type']]) ? (float) $current_lib['purchase_general_igst'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        if($row['pg_type'] == 'general')
        {
            $total += (float) $row['pg_amount'];
        }
        else
        {
            $total -= (float) $row['pg_amount'];
        }
        // echo '<pre>';print_r($total);
        //$purchase_general[$row['pg_type']] = $total;
        
    }
    $current_lib['purchase_general_cgst']['total'] = $total;

    $purchase_general_sgst = array();
    $builder = $db->table('purchase_particu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.sgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $purchase_general_sgst = $query->getResultArray();


    $total = 0;

    foreach ($purchase_general_sgst as $row) {

        //$total = (((float) @$current_lib['purchase_general_igst'][$row['pg_type']]) ? (float) $current_lib['purchase_general_igst'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        if($row['pg_type'] == 'general')
        {
            $total += (float) $row['pg_amount'];
        }
        else
        {
            $total -= (float) $row['pg_amount'];
        }
        // echo '<pre>';print_r($total);
        //$purchase_general[$row['pg_type']] = $total;
        
    }
    $current_lib['purchase_general_sgst']['total'] = $total;
    // purchase invoice

    $purchase_igst = array();

    $builder = $db->table('purchase_invoice pi');
    $builder->select('ac.id as account_id,pi.tot_igst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.igst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_igst = $query->getResultArray();
    //echo $db->getLastQuery();exit;

    
    
    $total = 0;

    foreach($purchase_igst as $row){
        
        $total += $row['total'];
    
    }
    @$current_lib['purchase_igst']['total'] = $total;

    $purchase_cgst = array();

    $builder = $db->table('purchase_invoice pi');
    $builder->select('ac.id as account_id,pi.tot_cgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.cgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_cgst = $query->getResultArray();
    
    $total = 0;

    foreach($purchase_cgst as $row){
    
        $total += $row['total'];
    
    }
    @$current_lib['purchase_cgst']['total'] = $total;

    $purchase_sgst = array();

    $builder = $db->table('purchase_invoice pi');
    $builder->select('ac.id as account_id,pi.tot_sgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.sgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_sgst = $query->getResultArray();
    
    $total = 0;

    foreach($purchase_sgst as $row){
        
        $total += $row['total'];
    
    }
    @$current_lib['purchase_sgst']['total'] = $total;

    //purchase return
    $purchase_return_igst = array();

    $builder = $db->table('purchase_return pi');
    $builder->select('ac.id as account_id,pi.tot_igst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.igst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return_igst = $query->getResultArray();

    
    $total = 0;
    
    foreach($purchase_return_igst as $row){
        
        $total -=$row['total'];
        
    }
    @$current_lib['purchase_return_igst']['total'] = $total;

    $purchase_return_cgst = array();

    $builder = $db->table('purchase_return pi');
    $builder->select('ac.id as account_id,pi.tot_cgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.cgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return_cgst = $query->getResultArray();

    
    $total = 0;
    
    foreach($purchase_return_cgst as $row){
        
        $total -=$row['total'];
        
    }
    @$current_lib['purchase_return_cgst']['total'] = $total;

    $purchase_return_sgst = array();

    $builder = $db->table('purchase_return pi');
    $builder->select('ac.id as account_id,pi.tot_sgst as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'pi.sgst_acc =ac.id');
    $builder->where(array('ac.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return_sgst = $query->getResultArray();

    
    $total = 0;
    
    foreach($purchase_return_sgst as $row){
    
        $total -=$row['total'];
        
    }
    @$current_lib['purchase_return_sgst']['total'] = $total;
    return $current_lib;
}
// START update trupti 26-12-2022 duties and taxes add taxes account
function get_sales_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('sales_invoice pi');
    $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.net_amount) as total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.invoice_date)');
    $query = $builder->get();
    $sales = $query->getResultArray();
    $total = 0;
    $arr = array();

    foreach ($sales as $row) {


        // $total += $row['pi_total'];
        
        $arr[$row['month']] = $row;
        // $arr[$row['month']]['total'] = $total;
    }
 
    $builder = $db->table('sales_invoice pi');
    $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_igst) as total');
    $builder->join('account ac', 'ac.id =pi.igst_acc');
    $builder->where(array('pi.igst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.invoice_date)');
    $query = $builder->get();
    $sales_igst = $query->getResultArray();
    $total = 0;
    //$arr = array();
    //echo '<pre>';print_r($purchase_igst);exit;

    foreach ($sales_igst as $row) {


        // $total += $row['pi_total'];
        
        $arr[$row['month']] = $row;
        // $arr[$row['month']]['total'] = $total;
    }
   // echo '<pre>';print_r($arr);exit;
    $builder = $db->table('sales_invoice pi');
    $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_cgst) as total');
    $builder->join('account ac', 'ac.id =pi.cgst_acc');
    $builder->where(array('pi.cgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.invoice_date)');
    $query = $builder->get();
    $sales_cgst = $query->getResultArray();
    $total = 0;
    //$arr = array();111

    foreach ($sales_cgst as $row) {


        // $total += $row['pi_total'];
        
        $arr[$row['month']] = $row;
        // $arr[$row['month']]['total'] = $total;
    }
    $builder = $db->table('sales_invoice pi');
    $builder->select('MONTH(pi.invoice_date) as month,YEAR(pi.invoice_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_sgst) as total');
    $builder->join('account ac', 'ac.id =pi.sgst_acc');
    $builder->where(array('pi.sgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.invoice_date)');
    $query = $builder->get();
    $sales_sgst = $query->getResultArray();
    $total = 0;
    //$arr = array();

    foreach ($sales_sgst as $row) {


        // $total += $row['pi_total'];
        
        $arr[$row['month']] = $row;
        // $arr[$row['month']]['total'] = $total;
    }
    //print_r($arr);exit;
    $result = array();
    $result['sales'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    
    return $result;
}
//update trupti 26-12-2022
function get_sales_ret_monthly($start_date,$end_date,$id){
    
    if($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {
        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    
    if(session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('sales_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.net_amount) as total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.return_date)');
    $query = $builder->get();
    $sales_return = $query->getResultArray();
    $total = 0;
    $arr = array();

    foreach ($sales_return as $row) {
        $arr[$row['month']] = $row;
    }
 
    $builder = $db->table('sales_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_igst) as total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.igst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.return_date)');
    $query = $builder->get();
    $sales_return_igst = $query->getResultArray();
    $total = 0;
    //$arr = array();

    foreach ($sales_return_igst as $row) {
        $arr[$row['month']] = $row;
    }

    $builder = $db->table('sales_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_cgst) as total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.cgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.return_date)');
    $query = $builder->get();
    $sales_return_cgst = $query->getResultArray();
    $total = 0;
    //$arr = array();

    foreach ($sales_return_cgst as $row) {
        $arr[$row['month']] = $row;
    }

    $builder = $db->table('sales_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,SUM(pi.tot_sgst) as total');
    $builder->join('account ac', 'ac.id =pi.account');
    $builder->where(array('pi.sgst_acc' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(pi.return_date)');
    $query = $builder->get();
    $sales_return_sgst = $query->getResultArray();
    $total = 0;
    //$arr = array();

    foreach ($sales_return_sgst as $row) {
        $arr[$row['month']] = $row;
    }
    //print_r($arr);exit;
    $result = array();
    $result['sales_return'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;

    
    return $result;
}
//update trupti 26-12-2022
function get_generalSales_monthly($start_date, $end_date, $id)
{

    if ($start_date == '') {
        if (date('m') < '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }

    if ($end_date == '') {

        if (date('m') < '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.party_account' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    $arr = array();
    $tot_expence = array();
    foreach ($pg_expence as $row) {

      
        $total = ((@$tot_expence['generalSales'][$row['month']][$row['pg_type']]) ? $tot_expence['generalSales'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalSales'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalSales'][$row['month']]['total'] = (float)@$tot_expence['generalSales'][$row['month']]['general'] - (float) @$tot_expence['generalSales'][$row['month']]['return'];
        $tot_expence['generalSales'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalSales'][$row['month']]['month'] = $row['month'];

    }

    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.igst_acc' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence_igst = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    $arr = array();
    //$tot_expence_igst = array();
    foreach ($pg_expence_igst as $row) {

      
        $total = ((@$tot_expence['generalSales'][$row['month']][$row['pg_type']]) ? $tot_expence['generalSales'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalSales'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalSales'][$row['month']]['total'] = (float)@$tot_expence['generalSales'][$row['month']]['general'] - (float) @$tot_expence['generalSales'][$row['month']]['return'];
        $tot_expence['generalSales'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalSales'][$row['month']]['month'] = $row['month'];

    }


    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.cgst_acc' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence_cgst = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    $arr = array();
    //$tot_expence_cgst = array();
    foreach ($pg_expence_cgst as $row) {

      
        $total = ((@$tot_expence['generalSales'][$row['month']][$row['pg_type']]) ? $tot_expence['generalSales'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalSales'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalSales'][$row['month']]['total'] = (float)@$tot_expence['generalSales'][$row['month']]['general'] - (float) @$tot_expence['generalSales'][$row['month']]['return'];
        $tot_expence['generalSales'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalSales'][$row['month']]['month'] = $row['month'];

    }


    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.sgst_acc' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence_sgst = $query->getResultArray();
    // echo '<pre>';print_r($pg_expence);exit;
    $arr = array();
    //$tot_expence_cgst = array();
    foreach ($pg_expence_sgst as $row) {

      
        $total = ((@$tot_expence['generalSales'][$row['month']][$row['pg_type']]) ? $tot_expence['generalSales'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_expence['generalSales'][$row['month']][$row['pg_type']] = $total;

        $tot_expence['generalSales'][$row['month']]['total'] = (float)@$tot_expence['generalSales'][$row['month']]['general'] - (float) @$tot_expence['generalSales'][$row['month']]['return'];
        $tot_expence['generalSales'][$row['month']]['year'] = $row['year'];
        $tot_expence['generalSales'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = $tot_expence;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
   // echo '<pre>';print_r($tot_expence);exit;

    return $result;
}
//END update trupti 26-12-2022 duties and taxes add taxes account

