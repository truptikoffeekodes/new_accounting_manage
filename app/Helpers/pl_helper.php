<?php 
function pl_expense_data($id, $start_date = '', $end_date = '')
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

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id, pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount,pp.sub_total,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_particu pp', 'pp.account = ac.id');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => 0));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));

    $query = $builder->get();
    $result_array = $query->getResultArray();
    $tot_pl_expense = array();


    $total = 0;
    foreach ($result_array as $row) {

        $after_disc = 0;

        $row['pg_amount'] = (float) $row['sub_total'] + (float) $row['added_amt'];
       
        if (isset($tot_pl_expense[$row['account_name']][$row['pg_type']])) {
            $total = $tot_pl_expense[$row['account_name']][$row['pg_type']] + $row['pg_amount'];
            $tot_pl_expense[$row['account_name']][$row['pg_type']] = $total;
        } else {
            $tot_pl_expense[$row['account_name']][$row['pg_type']] = 0 + $row['pg_amount'];
        }
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('jv.amount as jv_total,jv.dr_cr, ac.name as account_name,ac.id as account_id');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_expens = $query->getResultArray();

    foreach ($jv_expens as $row) {

        if ($row['dr_cr'] == 'cr') {

            if (isset($tot_pl_expense[$row['account_name']]['jv_total'])) {
                $total = $tot_pl_expense[$row['account_name']]['jv_total'] - $row['jv_total'];
                $tot_pl_expense[$row['account_name']]['jv_total'] = $total;
            } else {
                $tot_pl_expense[$row['account_name']]['jv_total'] = 0 - $row['jv_total'];
            }

            // $total = (@$tot_pl_expense[$row['account_name']]['jv_total']) ? $tot_pl_expense[$row['account_name']]['jv_total'] : 0 - $row['jv_total'];
        } else {

            if (isset($tot_pl_expense[$row['account_name']]['jv_total'])) {
                $total = $tot_pl_expense[$row['account_name']]['jv_total'] + $row['jv_total'];
                $tot_pl_expense[$row['account_name']]['jv_total'] = $total;
            } else {
                $tot_pl_expense[$row['account_name']]['jv_total'] = 0 + $row['jv_total'];
            }

            // $total = (@$tot_pl_expense[$row['account_name']]['jv_total']) ? $tot_pl_expense[$row['account_name']]['jv_total'] : 0 + $row['jv_total'];
        }
        // $tot_pl_expense[$row['account_name']]['jv_total'] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['account_id'];
    }

    // echo '<pre>';    print_r($id);
    // print_r($tot_pl_expense);exit;

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_expense = $query->getResultArray();

    
    $total = 0;
    foreach ($bank_expense as $row) {
        if ($row['mode'] == 'Receipt') {

            if (isset($tot_pl_expense[$row['account_name']]['bt_total'])) {
                $total = $tot_pl_expense[$row['account_name']]['bt_total'] - $row['bt_total'];
                $tot_pl_expense[$row['account_name']]['bt_total'] = $total;
            } else {
                $tot_pl_expense[$row['account_name']]['bt_total'] = 0 - $row['bt_total'];
            }
            // $total = ((@$tot_pl_expense[$row['account_name']]['bt_total']) ? floatval($tot_pl_expense[$row['account_name']]['bt_total']) : 0) - floatval($row['bt_total']);
        } else {
            if (isset($tot_pl_expense[$row['account_name']]['bt_total'])) {
                $total = $tot_pl_expense[$row['account_name']]['bt_total'] + $row['bt_total'];
                $tot_pl_expense[$row['account_name']]['bt_total'] = $total;
            } else {

                $tot_pl_expense[$row['account_name']]['bt_total'] = 0 + $row['bt_total'];

            }

            // $total = ((@$tot_pl_expense[$row['account_name']]['bt_total']) ? floatval($tot_pl_expense[$row['account_name']]['bt_total']) : 0) + floatval($row['bt_total']);
        }
        // $tot_pl_expense[$row['account_name']]['bt_total'] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['account_id'];
        
    }
    $builder = $db->table('gl_group gl');
    $builder->select('sa.v_type as sa_type,sp.account as sp_acc,ac.id as account_id,ac.name as account_name,sp.amount as sa_amount,sp.sub_total,sp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu sp', 'sp.account = ac.id');
    $builder->join('sales_ACinvoice sa', 'sa.id = sp.parent_id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sa.is_delete' => '0'));
    $builder->where(array('sa.is_cancle' => '0'));
    $builder->where(array('sp.is_delete' => '0'));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $pl_income = $query->getResultArray();
    //echo '<pre>';Print_r($pl_income);exit;
    
    
 

    foreach ($pl_income as $row) {
        $row['sa_amount'] = (float) $row['sub_total'] + (float) $row['added_amt'];
       
        $total = ((@$tot_pl_expense[$row['account_name']]['sales_'.$row['sa_type']]) ? $tot_pl_expense[$row['account_name']]['sales_'.$row['sa_type']] : 0) + $row['sa_amount'];
        $tot_pl_expense[$row['account_name']]['sales_'.$row['sa_type']] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['account_id'];

    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_item pp', 'pp.item_id =ac.id');
    $builder->join('sales_invoice pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="invoice")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();
    //echo '<pre>';Print_r($sales_invoice);exit;
    
  
    
    //$tot_pg_expens = array();
    $total = 0;
   
    foreach ($sales_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_expense[$row['account_name']]['sales_invoice_exp'] ? $tot_pl_expense[$row['account_name']]['sales_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pl_expense[$row['account_name']]['sales_invoice_exp'] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_item pp', 'pp.item_id =ac.id');
    $builder->join('sales_return pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="return")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    
    //$tot_pg_expens = array();
    $total = 0;
    foreach ($sales_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_expense[$row['account_name']]['sales_return_exp'] ? $tot_pl_expense[$row['account_name']]['sales_return_exp'] : 0) + $row['pg_amount'];
        $tot_pl_expense[$row['account_name']]['sales_return_exp'] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    //echo '<pre>';Print_r($tot_pg_expens);exit;
    

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_item pp', 'pp.item_id =ac.id');
    $builder->join('purchase_invoice pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="invoice")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();
    //$tot_pg_expens = array();
    $total =0;
    foreach ($purchase_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_expense[$row['account_name']]['purchase_invoice_exp'] ? $tot_pl_expense[$row['account_name']]['purchase_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pl_expense[$row['account_name']]['purchase_invoice_exp'] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['pp_acc'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_item pp', 'pp.item_id =ac.id');
    $builder->join('purchase_return pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="return")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    //$tot_pg_expens = array();
    $total =0;
    foreach ($purchase_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_expense[$row['account_name']]['purchase_return_exp'] ? $tot_pl_expense[$row['account_name']]['purchase_return_exp'] : 0) + $row['pg_amount'];
        $tot_pl_expense[$row['account_name']]['purchase_return_exp'] = $total;
        $tot_pl_expense[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
    //echo '<pre>';Print_r($tot_pl_expense);exit;
    


    
    

    $total_arr = array();
    foreach ($tot_pl_expense as $key => $value) {
        $tot_pl_expense[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['sale_brokrage']+@$value['pur_brokrage']+@$value['bt_total'] - @$value['sales_general'] + @$value['sales_return']
                                        - @$value['sales_invoice_exp'] + @$value['sales_return_exp'] + @$value['purchase_invoice_exp'] - @$value['purchase_return_exp'];
        $total_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['sale_brokrage']+@$value['pur_brokrage']+@$value['bt_total'] - @$value['sales_general'] + @$value['sales_return']
                    - @$value['sales_invoice_exp'] + @$value['sales_return_exp'] + @$value['purchase_invoice_exp'] - @$value['purchase_return_exp'];
  
    }

    if (!empty($total_arr)) {
        $pl_expens_total = array_sum($total_arr);
    } else {
        $pl_expens_total = 0;
    }

    $arr['account'] = $tot_pl_expense;
    $arr['total'] = $pl_expens_total;
    
    return $arr;

}

function pl_income_data($id, $start_date = '', $end_date = '')
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

    $builder = $db->table('gl_group gl');
    $builder->select('sa.v_type as sa_type,sp.account as sp_acc,ac.id as account_id,ac.name as account_name,sp.amount,sp.sub_total as sa_amount,sp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu sp', 'sp.account = ac.id');
    $builder->join('sales_ACinvoice sa', 'sa.id = sp.parent_id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sa.is_delete' => '0'));
    $builder->where(array('sa.is_cancle' => '0'));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $pl_income = $query->getResultArray();
   
    
    
    $tot_pl_income = array();

    foreach ($pl_income as $row) {
        $row['sa_amount'] = (float) $row['sa_amount'] + (float) $row['added_amt'];
       
        $total = ((@$tot_pl_income[$row['account_name']][$row['sa_type']]) ? $tot_pl_income[$row['account_name']][$row['sa_type']] : 0) + $row['sa_amount'];
        $tot_pl_income[$row['account_name']][$row['sa_type']] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['account_id'];

    }
   // echo '<pre>';Print_r($tot_pl_income);exit;

    $builder = $db->table('gl_group gl');
    $builder->select('ac.name as account_name,ac.id as account_id,jv.dr_cr,jv.amount as jv_total');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_income = $query->getResultArray();
    $total = 0;
    foreach ($jv_income as $row) {

        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_pl_income[$row['account_name']]['jv_total']) ? $tot_pl_income[$row['account_name']]['jv_total'] : 0) + $row['jv_total'];
        } else {
            $total = ((@$tot_pl_income[$row['account_name']]['jv_total']) ? $tot_pl_income[$row['account_name']]['jv_total'] : 0) - $row['jv_total'];
        }
        $tot_pl_income[$row['account_name']]['jv_total'] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['account_id'];

    }

    $builder = $db->table('gl_group gl');
    $builder->select('bt.payment_type,ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_income = $query->getResultArray();

    $total = 0;
    foreach ($bank_income as $row) {
        if ($row['mode'] == 'Receipt') {
            $total = ((@$tot_pl_income[$row['account_name']]['bt_total']) ? floatval($tot_pl_income[$row['account_name']]['bt_total']) : 0) + floatval($row['bt_total']);
        } else {
            $total = ((@$tot_pl_income[$row['account_name']]['bt_total']) ? floatval($tot_pl_income[$row['account_name']]['bt_total']) : 0) - floatval($row['bt_total']);
        }
        $tot_pl_income[$row['account_name']]['bt_total'] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['account_id'];
    }


    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,ac.id as account_id,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_particu pp', 'pp.account = ac.id');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $pg_expense = $query->getResultArray();
    //echo '<pre>';Print_r($pg_expense);exit;
    
    //$tot_pg_expens = array();
    foreach ($pg_expense as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = ((@$tot_pl_income[$row['account_name']]['purchase_'.$row['pg_type']]) ? $tot_pl_income[$row['account_name']]['purchase_'.$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_pl_income[$row['account_name']]['purchase_'.$row['pg_type']] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_item pp', 'pp.item_id =ac.id');
    $builder->join('sales_invoice pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="invoice")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();
    //echo '<pre>';Print_r($sales_invoice);exit;
    
  
    
    //$tot_pg_expens = array();
    $total = 0;
   
    foreach ($sales_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_income[$row['account_name']]['sales_invoice_exp'] ? $tot_pl_income[$row['account_name']]['sales_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pl_income[$row['account_name']]['sales_invoice_exp'] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_item pp', 'pp.item_id =ac.id');
    $builder->join('sales_return pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="return")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    
    //$tot_pg_expens = array();
    $total = 0;
    foreach ($sales_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_income[$row['account_name']]['sales_return_exp'] ? $tot_pl_income[$row['account_name']]['sales_return_exp'] : 0) + $row['pg_amount'];
        $tot_pl_income[$row['account_name']]['sales_return_exp'] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    //echo '<pre>';Print_r($tot_pg_expens);exit;
    

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_item pp', 'pp.item_id =ac.id');
    $builder->join('purchase_invoice pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="invoice")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();
    //$tot_pg_expens = array();
    $total =0;
    foreach ($purchase_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_income[$row['account_name']]['purchase_invoice_exp'] ? $tot_pl_income[$row['account_name']]['purchase_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pl_income[$row['account_name']]['purchase_invoice_exp'] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total as pg_amount,pp.added_amt');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_item pp', 'pp.item_id =ac.id');
    $builder->join('purchase_return pg', 'pg.id = pp.parent_id');
    $builder->where(array('gl.id' => $id));
    $builder->where('(pp.type="return")');
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    //$tot_pg_expens = array();
    $total =0;
    foreach ($purchase_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['added_amt'];
        $total = (@$tot_pl_income[$row['account_name']]['purchase_return_exp'] ? $tot_pl_income[$row['account_name']]['purchase_return_exp'] : 0) + $row['pg_amount'];
        $tot_pl_income[$row['account_name']]['purchase_return_exp'] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }

    $total_ex_arr = array();

    foreach ($tot_pl_income as $key => $value) {
        $tot_pl_income[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'] - @$value['purchase_general'] + @$value['purchase_return']
        + @$value['sales_invoice_exp'] - @$value['sales_return_exp'] - @$value['purchase_invoice_exp'] + @$value['purchase_return_exp'];
        $total_ex_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'] - @$value['purchase_general'] + @$value['purchase_return']
        + @$value['sales_invoice_exp'] - @$value['sales_return_exp'] - @$value['purchase_invoice_exp'] + @$value['purchase_return_exp'];
    }


    if (!empty($total_ex_arr)) {
        $pl_income_total = array_sum($total_ex_arr);
    } else {
        $pl_income_total = 0;
    }
    $arr['account'] = $tot_pl_income;
    $arr['total'] = $pl_income_total;

    return $arr;

}
function get_PL_expense_sub_grp_data($parent_id, $start_date = '', $end_date = '')
{
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

    // echo '<pre>jenith';print_r($parent_id);
    // print_r($result);exit;

    foreach ($result as $mainCategory) {
        $category = array();

        if ($start_date != '' && $end_date != '') {
            $category = pl_expense_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_PL_expense_sub_grp_data($mainCategory->id, $start_date, $end_date);

        } else {
            $category = pl_expense_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_PL_expense_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return $categories;
}

function get_PL_income_sub_grp_data($parent_id, $start_date = '', $end_date = '')
{
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

        if ($start_date != '' && $end_date != '') {
            $category = pl_income_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_PL_income_sub_grp_data($mainCategory->id, $start_date, $end_date);

        } else {
            $category = pl_income_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_PL_income_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }

    return $categories;
}
function pl_tot_data($start_date = '', $end_date = '')
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

    // Purchase Expense Start //

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as accoutn_id, pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_particu pp', 'pp.account = ac.id');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where('(gl.name ="P & L Expenses" OR gl.name ="Other Expenses")');
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(pg.created_at)  <= ' => $end_date));

    $query = $builder->get();
    $result_array = $query->getResultArray();

    $tot_pl_expense = array();

    foreach ($result_array as $row) {
        $total = (@$tot_pl_expense[$row['account_name']][$row['pg_type']]) ? $tot_pl_expense[$row['account_name']][$row['pg_type']] : 0 + $row['pg_amount'];
        $tot_pl_expense[$row['account_name']][$row['pg_type']] = $total;
    }

    // Expense Broker Ledger Amount//

    // $builder = $db->table('gl_group gl');
    // $builder->select('ac.name as account_name,si.broker_led_amt as sa_brokrage, pi.broker_led_amt as pur_brokrage');
    // $builder->join('account ac','ac.gl_group = gl.id');
    // $builder->join('sales_invoice si', 'si.broker_ledger = ac.id');
    // $builder->join('purchase_invoice pi', 'pi.broker_ledger = ac.id');
    // $builder->where('(gl.name ="P & L Expenses" OR gl.name ="Other Expenses")');
    // $builder->where(array('si.is_delete' => '0'));
    // $builder->where(array('pi.is_delete' => '0'));
    // $builder->where(array('DATE(si.created_at)  >= ' => $start_date));
    // $builder->where(array('DATE(si.created_at)  <= ' => $end_date));
    // $builder->where(array('DATE(pi.created_at)  >= ' => $start_date));
    // $builder->where(array('DATE(pi.created_at)  <= ' => $end_date));
    // $query = $builder->get();
    // $brokrage = $query->getResultArray();

    // foreach ($brokrage as $row) {
    //     $sa_brok = (@$tot_pl_expense[$row['account_name']]['sale_brokrage']) ? $tot_pl_expense[$row['account_name']]['sale_brokrage'] : 0 + ($row['sa_brokrage'] * -1);
    //     $tot_pl_expense[$row['account_name']]['sale_brokrage'] = $sa_brok;

    //     $pu_brok = (@$tot_pl_expense[$row['account_name']]['pur_brokrage']) ? $tot_pl_expense[$row['account_name']]['pur_brokrage'] : 0 + ($row['pur_brokrage'] * -1);
    //     $tot_pl_expense[$row['account_name']]['pur_brokrage'] = $pu_brok;
    // }

    //End Brokerage Expense Ledger Amount//

    $builder = $db->table('gl_group gl');
    $builder->select('jv.amount as total, ac.name as account_name');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where('(gl.name ="P & L Expenses" OR gl.name ="Other Expenses")');
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $jv_expens = $query->getResultArray();

    foreach ($jv_expens as $row) {
        $total = (@$tot_pl_expense[$row['account_name']]['jv_total']) ? $tot_pl_expense[$row['account_name']]['jv_total'] : 0 + $row['total'];
        $tot_pl_expense[$row['account_name']]['jv_total'] = $total;
    }
    $total_arr = array();
    foreach ($tot_pl_expense as $key => $value) {
        $tot_pl_expense[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['sale_brokrage']+@$value['pur_brokrage'];
        $total_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['sale_brokrage']+@$value['pur_brokrage'];
    }

    if (!empty($total_arr)) {

        $pl_expens_total = array_sum($total_arr);
    } else {
        $pl_expens_total = 0;
    }

    // Trading Income Start //

    $builder = $db->table('gl_group gl');
    $builder->select('sa.v_type as sa_type,sp.account as sp_acc,ac.name as account_name,sp.amount as sa_amount');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu sp', 'sp.account = ac.id');
    $builder->join('sales_ACinvoice sa', 'sa.id = sp.parent_id');
    $builder->where('(sa.v_type="general" OR sa.v_type = "return")');
    $builder->where('(gl.name ="P & L Incomes" OR gl.name ="Other Incomes")');
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sa.is_delete' => '0'));
    $builder->where(array('DATE(sa.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(sa.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $pl_income = $query->getResultArray();

    $tot_pl_income = array();

    foreach ($pl_income as $row) {
        $total = (@$tot_pl_income[$row['account_name']][$row['sa_type']]) ? $tot_pl_income[$row['account_name']][$row['sa_type']] : 0 + $row['sa_amount'];
        $tot_pl_income[$row['account_name']][$row['sa_type']] = $total;
    }

    $builder = $db->table('gl_group gl');
    $builder->select('ac.name as account_name,jv.amount as jv_total');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where('(gl.name ="P & L Incomes" OR gl.name ="Other Incomes")');
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $jv_income = $query->getResultArray();

    foreach ($jv_income as $row) {
        $total = (@$tot_pg_expens[$row['account_name']]['jv_total']) ? $tot_pg_expens[$row['account_name']]['jv_total'] : 0 + $row['jv_total'];
        $tot_pl_income[$row['account_name']]['jv_total'] = $total;
    }

    $total_ex_arr = array();

    foreach ($tot_pl_income as $key => $value) {
        $tot_pl_income[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total'];
        $total_ex_arr[] = @$value['general']-@$value['return']+@$value['jv_total'];
    }

    // echo '<pre>';print_r($tot_pl_income);exit;

    if (!empty($total_ex_arr)) {
        $pl_income_total = array_sum($total_ex_arr);
    } else {
        $pl_income_total = 0;
    }

    $data = array(
        'income_ac' => $tot_pl_income,
        'enpense_ac' => $tot_pl_expense,
        'pl_income' => $pl_income_total,
        'pl_expense' => $pl_expens_total,
    );

    // echo '<pre>';print_r($data);exit;
    return $data;
}
function pl_get_generalSales_monthly_AcWise($start_date, $end_date, $id)
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
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pp.amount as pg_amount');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.account' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    $arr = array();
  
    $tot_income = array();
    foreach ($pg_income as $row) {

        $after_disc = 0;
        $row['pg_amount'] = (float) $row['pg_amount'];
      
        $total = ((@$tot_income['generalSale'][$row['month']][$row['pg_type']]) ? $tot_income['generalSale'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_income['generalSale'][$row['month']][$row['pg_type']] = $total;

        $tot_income['generalSale'][$row['month']]['total'] = (float)@$tot_income['generalSale'][$row['month']]['return'] - (float) @$tot_income['generalSale'][$row['month']]['general'];
        $tot_income['generalSale'][$row['month']]['year'] = $row['year'];
        $tot_income['generalSale'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
   
    return $result;
}
function pl_get_generalPurchase_monthly_AcWise($start_date, $end_date, $id)
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
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pp.amount as pg_amount');
    $builder->join('purchase_particu pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.account' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    $arr = array();
  
    $tot_income = array();
    foreach ($pg_income as $row) {

        $after_disc = 0;
        $row['pg_amount'] = (float) $row['pg_amount'];
      
        $total = ((@$tot_income['generalPurchase'][$row['month']][$row['pg_type']]) ? $tot_income['generalPurchase'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_income['generalPurchase'][$row['month']][$row['pg_type']] = $total;

        $tot_income['generalPurchase'][$row['month']]['total'] = (float)@$tot_income['generalPurchase'][$row['month']]['return'] - (float) @$tot_income['generalSale'][$row['month']]['general'];
        $tot_income['generalPurchase'][$row['month']]['year'] = $row['year'];
        $tot_income['generalPurchase'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
   
    return $result;
}
function get_pl_sales_invoice_monthly_data($start_date, $end_date, $id)
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
    $builder = $db->table('sales_invoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pp.total');
    $builder->join('sales_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.item_id' => $id,'pp.is_expence'=>1,'pp.type'=>'invoice','pg.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
   
    $arr = array();
    $tot_income = array();
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'];
        $total = ((@$tot_income['sales_invoice'][$row['month']]['total']) ? $tot_income['sales_invoice'][$row['month']]['total'] : 0) + $row['pg_amount'];
        $tot_income['sales_invoice'][$row['month']]['total'] = $total;
        $tot_income['sales_invoice'][$row['month']]['year'] = $row['year'];
        $tot_income['sales_invoice'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;

    return $result;
}
function get_pl_sales_return_monthly_data($start_date, $end_date, $id)
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
    $builder = $db->table('sales_return pg');
    //$builder->select('pp.*');
    $builder->select('MONTH(pg.return_date) as month,YEAR(pg.return_date) as year,pp.total');
    $builder->join('sales_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.item_id' => $id,'pp.is_expence'=>1,'pp.type'=>'return','pp.is_delete'=>0));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    //echo '<pre>';Print_r($pg_income);exit;
    
  
    $arr = array();
    $tot_income = array();
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'];
        $total = ((@$tot_income['sales_return'][$row['month']]['total']) ? $tot_income['sales_return'][$row['month']]['total'] : 0) + $row['pg_amount'];
        $tot_income['sales_return'][$row['month']]['total'] = $total;
        $tot_income['sales_return'][$row['month']]['year'] = $row['year'];
        $tot_income['sales_return'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
   
    return $result;
}
function get_pl_purchase_invoice_monthly_data($start_date, $end_date, $id)
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
    $builder = $db->table('purchase_invoice pg');
    //$builder->select('pp.*');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pp.total,');
    $builder->join('purchase_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.item_id' => $id,'pp.is_expence'=>1,'pp.type'=>'invoice','pp.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
   
    $arr = array();
    $tot_income = array();
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'];
        $total = ((@$tot_income['purchase_invoice'][$row['month']]['total']) ? $tot_income['purchase_invoice'][$row['month']]['total'] : 0) + $row['pg_amount'];
        $tot_income['purchase_invoice'][$row['month']]['total'] = $total;
        $tot_income['purchase_invoice'][$row['month']]['year'] = $row['year'];
        $tot_income['purchase_invoice'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
   
    return $result;
}
function get_pl_purchase_return_monthly_data($start_date, $end_date, $id)
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
    $builder = $db->table('purchase_return pg');
    //$builder->select('pp.*');
    $builder->select('MONTH(pg.return_date) as month,YEAR(pg.return_date) as year,pp.total');
    $builder->join('purchase_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.item_id' => $id,'pp.is_expence'=>1,'pp.type'=>'return','pp.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    
    $arr = array();
    $tot_income = array();
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'];
        $total = ((@$tot_income['purchase_return'][$row['month']]['total']) ? $tot_income['purchase_return'][$row['month']]['total'] : 0) + $row['pg_amount'];
        $tot_income['purchase_return'][$row['month']]['total'] = $total;
        $tot_income['purchase_return'][$row['month']]['year'] = $row['year'];
        $tot_income['purchase_return'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;

    return $result;
}
function Round_off_data($id,$start_date='',$end_date='')
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

    $builder = $db->table('gl_group gl');
    //$builder -> select('ac.*');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.round as pp_acc,ac.name as account_name,pg.round_diff');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_invoice pg', 'pg.round = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();

    $total = 0;
    foreach($sales_invoice as $row)
    {
        $total -= $row['round_diff'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.round as pp_acc,ac.name as account_name,pg.round_diff');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pg', 'pg.round = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();
    foreach($sales_return as $row)
    {
        $total += $row['round_diff'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.round as pp_acc,ac.name as account_name,pg.round_diff');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_invoice pg', 'pg.round = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();
    foreach($purchase_invoice as $row)
    {
        $total += $row['round_diff'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.round as pp_acc,ac.name as account_name,pg.round_diff');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_return pg', 'pg.round = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    foreach($purchase_return as $row)
    {
        $total -= $row['round_diff'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,pg.v_type,gl.name,gl.parent,pg.round as pp_acc,ac.name as account_name,pg.round_diff');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.round = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_general = $query->getResultArray();
    foreach($sales_general as $row)
    {
        if($row['v_type'] == 'general')
        {
            $total -= $row['round_diff'];
        }
        else
        {
            $total += $row['round_diff'];
        }
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,pg.v_type,gl.parent,pg.round as pp_acc,ac.name as account_name,pg.round_diff');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.round = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_general = $query->getResultArray();

    foreach($purchase_general as $row)
    {
        if($row['v_type'] == 'general')
        {
            $total += $row['round_diff'];
        }
        else
        {
            $total -= $row['round_diff'];
        }
    }
    return $total;


    
   
}
function get_round_off_voucher_data($start_date, $end_date, $id)
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

    $builder = $db->table('sales_invoice si');
    $builder->select('si.id,si.round as pp_acc,ac.name as account_name,si.round_diff');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('si.is_delete' => '0','si.is_cancle' => '0','si.round'=>$id));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();

    $sales_inv_total = 0;
    foreach($sales_invoice as $row)
    {
        $sales_inv_total += $row['round_diff'];
    }
    $result['sales_invoice']['total'] = $sales_inv_total;

    $builder = $db->table('sales_return si');
    $builder->select('si.id,si.round as pp_acc,ac.name as account_name,si.round_diff');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('si.is_delete' => '0','si.is_cancle' => '0','si.round'=>$id));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();
    $sales_ret_total = 0;
    foreach($sales_return as $row)
    {
        $sales_ret_total += $row['round_diff'];
    }
    $result['sales_return']['total'] = $sales_ret_total;

    $builder = $db->table('purchase_invoice si');
    $builder->select('si.id,si.round as pp_acc,ac.name as account_name,si.round_diff');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('si.is_delete' => '0','si.is_cancle' => '0','si.round'=>$id));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();

    $purchase_inv_total = 0;
    foreach($purchase_invoice as $row)
    {
        $purchase_inv_total += $row['round_diff'];
    }
    $result['purchase_invoice']['total'] = $purchase_inv_total;

    $builder = $db->table('purchase_return si');
    $builder->select('si.id,si.round as pp_acc,ac.name as account_name,si.round_diff');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('si.is_delete' => '0','si.is_cancle' => '0','si.round'=>$id));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    $purchase_ret_total = 0;
    foreach($purchase_return as $row)
    {
        $purchase_ret_total += $row['round_diff'];
    }
    $result['purchase_return']['total'] = $purchase_ret_total;

    $builder = $db->table('sales_ACinvoice si');
    $builder->select('si.id,si.round as pp_acc,ac.name as account_name,si.round_diff');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('si.is_delete' => '0','si.is_cancle' => '0','si.round'=>$id));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_general = $query->getResultArray();
    $sales_general_total = 0;
    foreach($sales_general as $row)
    {
        if($row['v_type'] == 'general')
        {
            $sales_general_total -= $row['round_diff'];
        }
        else
        {
            $sales_general_total += $row['round_diff'];
        }
    }
    $result['sales_general']['total'] = $sales_general_total;

    $builder = $db->table('purchase_general si');
    $builder->select('si.id,si.round as pp_acc,ac.name as account_name,si.round_diff');
    $builder->join('account ac', 'ac.id ='.$id);
    $builder->where(array('si.is_delete' => '0','si.is_cancle' => '0','si.round'=>$id));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_general = $query->getResultArray();
    $purchase_general_total = 0;
    foreach($purchase_general as $row)
    {
        if($row['v_type'] == 'general')
        {
            $purchase_general_total += $row['round_diff'];
        }
        else
        {
            $purchase_general_total -= $row['round_diff'];
        }
    }
    $result['purchase_general']['total'] = $purchase_general_total;
    return $result;
    
}


?>