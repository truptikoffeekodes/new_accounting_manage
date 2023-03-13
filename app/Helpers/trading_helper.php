<?php 
//**************************dashbord************************ */
function sale_purchase_vouhcer($start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {
        if (date('m') <= '03') {
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
    $result = array();
    $builder = $db->table('purchase_invoice pi');
    $builder->select('pi.id,((SUM(pp.total)) - (SUM(pp.discount))) as total');
    $builder->join('purchase_item pp', 'pp.parent_id =pi.id');
    $builder->where(array('pp.is_delete' => 0,'pp.type'=>'invoice','pp.is_expence'=>0));
    $builder->where(array('pi.is_cancle' => 0,'pi.is_delete' => 0));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $builder->groupBy("pp.parent_id");
    $query = $builder->get();
    $purcahse_invoice = $query->getResultArray();
    $purcahse['pur_taxable'] = 0;
    foreach($purcahse_invoice as $row)
    {
        $purcahse['pur_taxable'] += $row['total']; 
    }

    $builder = $db->table('purchase_return pi');
    $builder->select('pi.id,((SUM(pp.total)) - (SUM(pp.discount))) as total');
    $builder->join('purchase_item pp', 'pp.parent_id =pi.id');
    $builder->where(array('pp.is_delete' => 0,'pp.type'=>'return','pp.is_expence'=>0));
    $builder->where(array('pi.is_cancle' => 0,'pi.is_delete' => 0));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $builder->groupBy("pp.parent_id");
    $query = $builder->get();
    $purcahse_return = $query->getResultArray();
    $pur_ret['purRet_taxable'] = 0;
    foreach($purcahse_return as $row)
    {
        $pur_ret['purRet_taxable'] += $row['total']; 
    }

    $builder = $db->table('sales_invoice pi');
    $builder->select('pi.id,((SUM(pp.total)) - (SUM(pp.discount))) as total');
    $builder->join('sales_item pp', 'pp.parent_id =pi.id');
    $builder->where(array('pp.is_delete' => 0,'pp.type'=>'invoice','pp.is_expence'=>0));
    $builder->where(array('pi.is_cancle' => 0,'pi.is_delete' => 0));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $builder->groupBy("pp.parent_id");
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();
    $sale['sale_taxable'] = 0;
    foreach($sales_invoice as $row)
    {
        $sale['sale_taxable']  += $row['total']; 
    }

    $builder = $db->table('sales_return pi');
    $builder->select('pi.id,((SUM(pp.total)) - (SUM(pp.discount))) as total');
    $builder->join('sales_item pp', 'pp.parent_id =pi.id');
    $builder->where(array('pp.is_delete' => 0,'pp.type'=>'return','pp.is_expence'=>0));
    $builder->where(array('pi.is_cancle' => 0,'pi.is_delete' => 0));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $builder->groupBy("pp.parent_id");
    $query = $builder->get();
    $sales_return = $query->getResultArray();
    $sale_ret['saleRet_taxable'] = 0;
    foreach($sales_return as $row)
    {
        $sale_ret['saleRet_taxable']  += $row['total']; 
    }
   
   
    $result = array(
        'pur_total_rate' => @$purcahse['pur_taxable'] ? $purcahse['pur_taxable'] : 0,
        'Purret_total_rate' => @$pur_ret['purRet_taxable'] ? $pur_ret['purRet_taxable'] : 0,
        'sale_total_rate' => @$sale['sale_taxable'] ? $sale['sale_taxable'] : 0,
        'saleret_total_rate' => @$sale_ret['saleRet_taxable'] ? $sale_ret['saleRet_taxable'] : 0,
        'from' => $start_date,
        'to' => $end_date,
    );

    return $result;
}
// update sub total and added amt  trupti 12-12-2022
function trading_expense_data($id, $start_date = '', $end_date = '')
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
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,ac.id as account_id,(pp.amount - pp.discount) as pg_amount');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_particu pp', 'pp.account = ac.id');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $pg_expense = $query->getResultArray();
    $total = 0;
    $tot_pg_expens = array();
    foreach ($pg_expense as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = ((@$tot_pg_expens[$row['account_name']][$row['pg_type']]) ? $tot_pg_expens[$row['account_name']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_pg_expens[$row['account_name']][$row['pg_type']] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['account_id'];
    }
  
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id ,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_expens = $query->getResultArray();
   
   $total = 0;
    foreach ($bank_expens as $row) {

        if ($row['mode'] == 'Receipt') {
            $total = ((@$tot_pg_expens[$row['account_name']]['bt_total']) ? $tot_pg_expens[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        } else {
            $total = ((@$tot_pg_expens[$row['account_name']]['bt_total']) ? $tot_pg_expens[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        }
        $tot_pg_expens[$row['account_name']]['bt_total'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['account_id'];
    }
   
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,jv.amount as jv_total,jv.dr_cr');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_expens = $query->getResultArray();

    $total = 0;
    foreach ($jv_expens as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_pg_expens[$row['account_name']]['jv_total']) ? $tot_pg_expens[$row['account_name']]['jv_total'] : 0) - $row['jv_total'];
        } else {
            $total = ((@$tot_pg_expens[$row['account_name']]['jv_total']) ? $tot_pg_expens[$row['account_name']]['jv_total'] : 0) + $row['jv_total'];
        }
        $tot_pg_expens[$row['account_name']]['jv_total'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['account_id'];

    }

    $builder = $db->table('gl_group gl');
    $builder->select('sa.v_type as sa_type,sp.account as sp_acc,ac.id as account_id,ac.name as account_name,(sp.amount - sp.discount) as sa_amount');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu sp', 'sp.account = ac.id');
    $builder->join('sales_ACinvoice sa', 'sa.id = sp.parent_id');
    $builder->where('gl.id', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sp.is_delete' => '0'));
    $builder->where(array('sa.is_cancle' => '0','sa.is_delete' => '0'));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $pl_income = $query->getResultArray();
    
    $total = 0;
    foreach ($pl_income as $row) {
        $row['sa_amount'] = (float) $row['sa_amount'];
        $total = ((@$tot_pg_expens[$row['account_name']]['sales_'.$row['sa_type']]) ? $tot_pg_expens[$row['account_name']]['sales_'.$row['sa_type']] : 0) + $row['sa_amount'];
        $tot_pg_expens[$row['account_name']]['sales_'.$row['sa_type']] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['account_id'];

    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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
    $total = 0;
   
    foreach ($sales_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_expens[$row['account_name']]['sales_invoice_exp'] ? $tot_pg_expens[$row['account_name']]['sales_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pg_expens[$row['account_name']]['sales_invoice_exp'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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
    $total = 0;
    foreach ($sales_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_expens[$row['account_name']]['sales_return_exp'] ? $tot_pg_expens[$row['account_name']]['sales_return_exp'] : 0) + $row['pg_amount'];
        $tot_pg_expens[$row['account_name']]['sales_return_exp'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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
   
    $total =0;
    foreach ($purchase_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_expens[$row['account_name']]['purchase_invoice_exp'] ? $tot_pg_expens[$row['account_name']]['purchase_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pg_expens[$row['account_name']]['purchase_invoice_exp'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['pp_acc'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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

    $total =0;
    foreach ($purchase_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_expens[$row['account_name']]['purchase_return_exp'] ? $tot_pg_expens[$row['account_name']]['purchase_return_exp'] : 0) + $row['pg_amount'];
        $tot_pg_expens[$row['account_name']]['purchase_return_exp'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
    $total_ex_arr = array();
    
    foreach ($tot_pg_expens as $key => $value) {
        $tot_pg_expens[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'] - @$value['sales_general'] + @$value['sales_return']
                                      - @$value['sales_invoice_exp'] + @$value['sales_return_exp'] + @$value['purchase_invoice_exp'] - @$value['purchase_return_exp'];
        $total_ex_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'] - @$value['sales_general'] + @$value['sales_return']
                        - @$value['sales_invoice_exp'] + @$value['sales_return_exp'] + @$value['purchase_invoice_exp'] - @$value['purchase_return_exp'];
       
    }
   
    if (!empty($total_ex_arr)) {
        $trading_expense_total = array_sum($total_ex_arr);
    } else {
        $trading_expense_total = 0;
    }

    $arr['account'] = $tot_pg_expens;
    $arr['total'] = $trading_expense_total;

    return $arr;
}
//update sub total and added amt  trupti 12-12-2022
function trading_income_data($id, $start_date = '', $end_date = '')
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

    $pg_income = array();
    
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,ac.id as account_id,(pp.amount - pp.discount) as pg_amount');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu pp', 'pp.account = ac.id');
    $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0','pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    $total = 0;
    $tot_pg_income = array();
    foreach ($pg_income as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']][$row['pg_type']] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $bank_income = array();
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_income = $query->getResultArray();
    $total = 0;
    foreach ($bank_income as $row) {

        if ($row['mode'] == 'Receipt') {
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        } else {
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
    $total = 0;
    foreach ($jv_income as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
        } else {
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }
        $tot_pg_income[$row['account_name']]['jv_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,ac.id as account_id,(pp.amount - pp.discount) as pg_amount');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_particu pp', 'pp.account = ac.id');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $pg_expense = $query->getResultArray();
    $total = 0;
    foreach ($pg_expense as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = ((@$tot_pg_income[$row['account_name']]['purchase_'.$row['pg_type']]) ? $tot_pg_income[$row['account_name']]['purchase_'.$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']]['purchase_'.$row['pg_type']] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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
    $total = 0;
    foreach ($sales_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_income[$row['account_name']]['sales_invoice_exp'] ? $tot_pg_income[$row['account_name']]['sales_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']]['sales_invoice_exp'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
   
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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

    $total = 0;
    foreach ($sales_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_income[$row['account_name']]['sales_return_exp'] ? $tot_pg_income[$row['account_name']]['sales_return_exp'] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']]['sales_return_exp'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }
 
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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
   
    $total =0;
    foreach ($purchase_invoice as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_income[$row['account_name']]['purchase_invoice_exp'] ? $tot_pg_income[$row['account_name']]['purchase_invoice_exp'] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']]['purchase_invoice_exp'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pp.item_id as pp_acc,ac.name as account_name,(pp.total - pp.discount) as pg_amount');
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
    $total =0;
    foreach ($purchase_return as $row) {
        $row['pg_amount'] = (float) $row['pg_amount'];
        $total = (@$tot_pg_income[$row['account_name']]['purchase_return_exp'] ? $tot_pg_income[$row['account_name']]['purchase_return_exp'] : 0) + $row['pg_amount'];
        $tot_pg_income[$row['account_name']]['purchase_return_exp'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['pp_acc'];
    }

    $total_arr = array();
    foreach ($tot_pg_income as $key => $value) {
        $tot_pg_income[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'] - @$value['purchase_general'] + @$value['purchase_return']
                                        + @$value['sales_invoice_exp'] - @$value['sales_return_exp'] - @$value['purchase_invoice_exp'] + @$value['purchase_return_exp'];
        $total_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'] - @$value['purchase_general'] + @$value['purchase_return']
                                        + @$value['sales_invoice_exp'] - @$value['sales_return_exp'] - @$value['purchase_invoice_exp'] + @$value['purchase_return_exp'];
      
    }

    if (!empty($total_arr)) {
        $trading_income_total = array_sum($total_arr);
    } else {
        $trading_income_total = 0;
    }

    $arr['account'] = $tot_pg_income;
    $arr['total'] = $trading_income_total;

    return $arr;

}
function opening_stock_data($id, $start_date = '', $end_date = '')
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
    $builder->select('ac.id as account_id, ac.name as account_name,opening_bal');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $query = $builder->get();
    $account_list = $query->getResultArray();
    //echo '<pre>';Print_r($pg_expense);exit;
    
    $account_opening = array();
    $total = 0;
    foreach ($account_list as $row) {
       // $row['amount'] = (float) $row['opening_bal'];
        $total += $row['opening_bal'];
        $account_opening[$row['account_name']]['opening_stock'] = $row['opening_bal'];
        $account_opening[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $arr['account'] = $account_opening;
    $arr['total'] = $total;
    //echo '<pre>';Print_r($arr);exit;
    
    return $arr;
}
//******* Trading Income & Expense Sub Group LOOPING *******//
function get_expense_sub_grp_data($parent_id, $start_date = '', $end_date = '')
{

    $categories = array();

    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();

        if ($start_date != '' && $end_date != '') {
            $category = trading_expense_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_expense_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = trading_expense_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_expense_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }

    return $categories;
}

function get_income_sub_grp_data($parent_id, $start_date = '', $end_date = '')
{
    $categories = array();

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();

        if ($start_date != '' && $end_date != '') {
            $category = trading_income_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_income_sub_grp_data($mainCategory->id, $start_date, $end_date);

        } else {
            $category = trading_income_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_income_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return $categories;
}
function get_opening_stock_sub_grp_data($parent_id, $start_date = '', $end_date = '')
{
    $categories = array();

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('parent', $parent_id);
    $query = $builder->get();
    $result = $query->getResult();

    foreach ($result as $mainCategory) {
        $category = array();

        if ($start_date != '' && $end_date != '') {
            $category = opening_stock_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_opening_stock_sub_grp_data($mainCategory->id, $start_date, $end_date);

        } else {
            $category = opening_stock_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_opening_stock_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return $categories;
}
function SaleItemSTock($id, $start_date = '', $end_date = '')
{
    if ($start_date == '') {

        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {

        if (date('m') <= '03') {
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

    $builder = $db->table('sales_item si');
    $builder->select('si.id,si.rate,si.qty,si.type');
    $builder->join('sales_invoice s', 's.id = si.parent_id', 'left');
    $builder->where('(si.type="invoice" OR si.type="return")');
    $builder->where(array('s.is_delete' => 0));
    $builder->where(array('si.item_id' => $id));
    $builder->where(array('DATE(s.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(s.created_at)  <= ' => $end_date));

    $query = $builder->get();
    $result = $query->getResultArray();

    $total_rate = 0;
    $total_qty = 0;

    $ret_total_rate = 0;
    $ret_total_qty = 0;

    foreach ($result as $row) {
        if ($row['type'] == 'invoice') {
            // print_r($row['id']);
            $total_rate += $row['rate'] * $row['qty'];
            $total_qty += $row['qty'];
        } else {
            $ret_total_rate += $row['rate'] * $row['qty'];
            $ret_total_qty += $row['qty'];
        }
    }

    $re['itm'] = array(
        'result' => $result,
        'total_rate' => $total_rate - $ret_total_rate,
        'total_qty' => $total_qty - $ret_total_qty,
    );

    return $re;
}

function PurchaseItemSTock($id, $start_date = '', $end_date = '')
{

    if ($start_date == '') {

        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {

        if (date('m') <= '03') {
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
    $builder = $db->table('purchase_item pi');
    $builder->select('pi.rate,pi.qty,pi.type');
    $builder->join('purchase_invoice p', 'p.id = pi.parent_id');
    $builder->where(array('pi.type' => 'invoice'));
    $builder->where(array('p.is_delete' => '0'));
    // $builder->orWhere(array('pi.type'=>'return'));
    $builder->where(array('pi.item_id' => $id));
    $builder->where(array('DATE(p.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(p.created_at)  <= ' => $end_date));
    // $builder->groupBy('pi.qty');
    $query = $builder->get();
    $result = $query->getResultArray();

    $total_rate = 0;
    $total_qty = 0;

    // $ret_total_rate = 0;
    // $ret_total_qty = 0;

    $builder1 = $db->table('item');
    $builder1->select('type,opening_total');
    $builder1->where(array('id' => $id));
    $query1 = $builder1->get();
    $result1 = $query1->getResultArray();

    foreach ($result as $row) {
        if ($row['type'] == 'invoice') {
            $total_rate += $row['rate'] * $row['qty'];
            $total_qty += $row['qty'];

        }
        //else{
        //     $ret_total_rate += $row['rate'] * $row['qty'];
        //     $ret_total_qty += $row['qty'];
        //}
    }

    foreach ($result1 as $row1) {
        if ($row1['type'] == 'Finish') {
            $total_qty += $row1['opening_total'] ? $row1['opening_total'] : 0;
        }
    }

    $re['itm'] = array(
        'total_rate' => $total_rate,
        'total_qty' => $total_qty,
    );
    return $re;
}
function Opening_bal($gl1, $start_date = '', $end_date = '')
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
    $db->setDatabase(session('DataSource'));

    $builder = $db->table('gl_group');
    $builder->select('id');
    $builder->where(array('name' => $gl1));
    $query = $builder->get();
    $gl_id = $query->getRowArray();

    $gl_ids = gl_list([$gl_id['id']]);
    $gl_ids[] = $gl_id['id'];

    $result = array();
    $opening_bal = 0;

    foreach ($gl_ids as $row) {
        $builder = $db->table('gl_group gl');
        $builder->select('SUM(ac.opening_bal) as total');
        $builder->join('account ac', 'gl.id = ac.gl_group');
        $builder->where(array('gl.id' => $row));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('ac.opening_type' => 'Debit'));
        // $builder->where(array('DATE(ac.created_at)  >= ' => $start_date));
        // $builder->where(array('DATE(ac.created_at)  <= ' => $end_date));
        $query = $builder->get();

        $get_result = $query->getRow();

        $opening_bal += (float)$get_result->total;

    }
    // print_r($opening_bal);exit;
    return $opening_bal;
}
function subGrp_total($data, $total)
{

    if (!empty($data)) {
        $arr = array();
        foreach ($data as $key => $value) {
            $total += $value['total'];
            $arr[$key] = $total;
            if (!empty($value['sub_categories'])) {
                $total = subGrp_total($value['sub_categories'], $total);
            }
        }
    }
    return $total;
}

// *************end dashbord*******************//
///////////////////////***************Monthly sales purchase account***********//////////////////////
function salesItem_monthly_data($start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {
        if (date('m') <= '03') {
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
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pp.total,pp.discount');
    $builder->join('sales_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.is_expence'=>0,'pp.type'=>'invoice','pp.is_delete'=>0));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
   
    $arr = array();
   
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'] - (float) $row['discount'];
        $total = ((@$arr[$row['month']]['total']) ? $arr[$row['month']]['total'] : 0) + $row['pg_amount'];
        $arr[$row['month']]['total'] = $total;
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['month'] = $row['month'];

    }
    return $arr;
}
function salesReturnItem_monthly_data($start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {
        if (date('m') <= '03') {
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
    $monthly_sales = array();
   
    $builder = $db->table('sales_return pg');
    $builder->select('MONTH(pg.return_date) as month,YEAR(pg.return_date) as year,pp.total,pp.discount');
    $builder->join('sales_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.is_expence'=>0,'pp.type'=>'return','pp.is_delete'=>0));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
  
    $arr = array();
   
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'] - (float) $row['discount'];
        $total = ((@$arr[$row['month']]['total']) ? $arr[$row['month']]['total'] : 0) + $row['pg_amount'];
        $arr[$row['month']]['total'] = $total;
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['month'] = $row['month'];

    }
    return $arr;
}
function purchaseItem_monthly_data($start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {
        if (date('m') <= '03') {
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
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pp.total,pp.discount');
    $builder->join('purchase_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.is_expence'=>0,'pp.type'=>'invoice','pp.is_delete'=>0));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    //echo '<pre>';Print_r($pg_income);exit;
    
  
    $arr = array();
   
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'] - (float) $row['discount'];
        $total = ((@$arr[$row['month']]['total']) ? $arr[$row['month']]['total'] : 0) + $row['pg_amount'];
        $arr[$row['month']]['total'] = $total;
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['month'] = $row['month'];

    }

    return $arr;
}
function purchaseReturnItem_monthly_data($start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {
        if (date('m') <= '03') {
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
    $builder->select('MONTH(pg.return_date) as month,YEAR(pg.return_date) as year,pp.total,pp.discount');
    $builder->join('purchase_item pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.is_expence'=>0,'pp.type'=>'return','pp.is_delete'=>0));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
  
    $arr = array();
   
    foreach ($pg_income as $row) {
        $after_disc = 0;
        $row['pg_amount'] = (float) $row['total'] - (float) $row['discount'];
        $total = ((@$arr[$row['month']]['total']) ? $arr[$row['month']]['total'] : 0) + $row['pg_amount'];
        $arr[$row['month']]['total'] = $total;
        $arr[$row['month']]['year'] = $row['year'];
        $arr[$row['month']]['month'] = $row['month'];

    }

    return $arr;
}
///////////////////////************end Monthly sales purchase account***********//////////////////////
function sale_purchase_itm_total($start_date = '', $end_date = '')
{
    if ($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    if ($end_date == '') {
        if (date('m') <= '03') {
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

    $builder = $db->table('purchase_item pi');
    $builder->select('pi.rate,pi.qty,pi.type');
    $builder->join('purchase_invoice p', 'p.id =pi.parent_id');
    $builder->where('(pi.type="invoice" OR pi.type = "return")');
    $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purcahse = $query->getResultArray();

    $builder = $db->table('sales_item si');
    $builder->select('si.rate,si.id,si.qty,si.type');
    $builder->join('sales_invoice s', 's.id = si.parent_id');
    $builder->where('(si.type="invoice" OR si.type = "return")');
    $builder->where(array('s.is_delete' => '0'));
    $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sale = $query->getResultArray();

   

    $pur_total_rate = 0;
    $pur_total_qty = 0;
    $sale_total_rate = 0;
    $sale_total_qty = 0;

    

    $Purret_total_qty = 0;
    $Purret_total_rate = 0;
    $Saleret_total_qty = 0;
    $Saleret_total_rate = 0;

    // $ret_total_rate = 0;
    // $ret_total_qty = 0;

    foreach ($purcahse as $row) {
        if ($row['type'] == 'invoice') {
            $pur_total_rate += $row['rate'] * $row['qty'];
            $pur_total_qty += $row['qty'];
        } else {
            $Purret_total_rate += $row['rate'] * $row['qty'];
            $Purret_total_qty += $row['qty'];
        }
    }

    foreach ($sale as $row) {
        if ($row['type'] == 'invoice') {
            $sale_total_rate += $row['rate'] * $row['qty'];
            $sale_total_qty += $row['qty'];
        } else {
            $Saleret_total_rate += $row['rate'] * $row['qty'];
            $Saleret_total_qty += $row['qty'];
        }
    }

  

    $re = array();
    $re = array(
        'sale_total_rate' => $sale_total_rate,
        'sale_total_qty' => $sale_total_qty,
        'pur_total_rate' => $pur_total_rate,
        'pur_total_qty' => $pur_total_qty,
        'Saleret_total_qty' => $Saleret_total_qty,
        'Saleret_total_rate' => $Saleret_total_rate,
        'Purret_total_rate' => $Purret_total_rate,
        'Purret_total_qty' => $Purret_total_qty,
      
        'from' => $start_date,
        'to' => $end_date,
    );
    // echo '<pre>';print_r($re);exit;
    return $re;
}
///////////////////////************start trading income expence ***********//////////////////////

function get_trading_income_account_wise($start_date, $end_date, $id)
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

    $builder = $db->table('sales_ACparticu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pp.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    $tot_income = array();
    $tot_income['general_sales']['total'] = 0;
    foreach ($pg_income as $row) {

        $row['pg_amount'] = (float)$row['pg_amount'];
       
        $total = (((float) @$tot_income['general_sales'][$row['pg_type']]) ? (float) $tot_income['general_sales'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        
        $tot_income['general_sales'][$row['pg_type']] = $total;
        
        $tot_income['general_sales']['total'] = (float)@$tot_income['general_sales']['general'] - (float)@$tot_income['general_sales']['return'];
    }
    
    $bank_income = array();

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_income = $query->getResultArray();

    $tot_income['bank_trans']['total'] = 0;

    $total = 0;
    foreach ($bank_income as $row) {

        if ($row['mode'] == 'Receipt') {
            $total += $row['bt_total'];
        } else {
            $total -= $row['bt_total'];
        }

    }
    $tot_income['bank_trans']['total'] += $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->where('jv.particular', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_income = $query->getResultArray();

    $tot_income['jv_parti']['total'] = 0;
    $total = 0;

    foreach ($jv_income as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total += $row['total'];
        } else {
            $total -= $row['total'];
        }

    }
    $tot_income['jv_parti']['total'] += $total;

    $builder = $db->table('purchase_particu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount,pp.sub_total,pp.added_amt,pg.is_delete,pp.is_delete as pp_delete');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pp.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_expence = $query->getResultArray();
    //echo '<pre>';Print_r($pg_expence);exit;
    

    $total = 0;
    $tot_income['general_purchase']['total'] =0;
    foreach ($pg_expence as $row) {
      
        

        $row['pg_amount'] = (float) $row['sub_total'] + (float) $row['added_amt'];
       
        $total = (((float) @$tot_income['general_purchase'][$row['pg_type']]) ? (float) $tot_income['general_purchase'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        
        $tot_income['general_purchase'][$row['pg_type']] = $total;
      
        
        $tot_income['general_purchase']['total'] = (float)@$tot_income['general_purchase']['return'] - (float)@$tot_income['general_purchase']['general'];
    }
   // exit;
    //echo '<pre>';Print_r($tot_income);exit;
    
    $builder = $db->table('sales_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total,pp.added_amt');
    $builder->join('sales_invoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'invoice'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();
    $total = 0;
    foreach ($sales_invoice as $row) {
        $row['pg_amount'] = $row['sub_total'] + $row['added_amt'];
        $total += $row['pg_amount'];
    }
    $tot_income['sales_invoice']['total'] = $total;

    $builder = $db->table('sales_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total,pp.added_amt');
    $builder->join('sales_return pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'return'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();
    $total = 0;
    foreach ($sales_return as $row) {
        $row['pg_amount'] = $row['sub_total'] + $row['added_amt'];
        $total += $row['pg_amount'];
    }
    $tot_income['sales_return']['total'] = $total;

    $builder = $db->table('purchase_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total,pp.added_amt');
    $builder->join('purchase_invoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'invoice'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();
    $total = 0;
    foreach ($purchase_invoice as $row) {
        $row['pg_amount'] = $row['sub_total'] + $row['added_amt'];
        $total += $row['pg_amount'];
    }
    $tot_income['purchase_invoice']['total'] = $total;

    $builder = $db->table('purchase_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.sub_total,pp.added_amt');
    $builder->join('purchase_return pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'return'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    $total = 0;
    foreach ($purchase_return as $row) {
        $row['pg_amount'] = $row['sub_total'] + $row['added_amt'];
        $total += $row['pg_amount'];
    }
    $tot_income['purchase_return']['total'] = $total;

    $tot_income['from'] = $start_date;
    $tot_income['to'] = $end_date;
    $tot_income['id'] = $id;
    // echo '<pre>';print_r($tot_income);exit;
    return $tot_income;
}
function get_trading_expence_account_wise($start_date, $end_date, $id)
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

    $builder = $db->table('purchase_particu pp');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount');
    $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pp.account' => $id,'pp.is_delete' => '0'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_expence = $query->getResultArray();
   
    $total = 0;
    $tot_expence = array();
    $tot_expence['general_purchase']['total'] =0;
    foreach ($pg_expence as $row) {

        $row['pg_amount'] = (float) $row['pg_amount'];
       
        $total = (((float) @$tot_expence['general_purchase'][$row['pg_type']]) ? (float) $tot_expence['general_purchase'][$row['pg_type']] : 0) + (float) $row['pg_amount'];
        
        $tot_expence['general_purchase'][$row['pg_type']] = $total;
        
        $tot_expence['general_purchase']['total'] = (float)@$tot_expence['general_purchase']['general'] - (float)@$tot_expence['general_purchase']['return'];
    }
    
    $bank_expence = array();

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_expence = $query->getResultArray();
   // echo '<pre>';Print_r($);exit;
    

    $tot_expence['bank_trans']['total'] = 0;

    $total = 0;
    foreach ($bank_expence as $row) {

        if ($row['mode'] == 'Payment') {
            $total += $row['bt_total'];
        } else {
            $total -= $row['bt_total'];
        }

    }
    $tot_expence['bank_trans']['total'] = $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->where('jv.particular', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_expence = $query->getResultArray();
    
    $tot_expence['jv_parti']['total'] = 0;
    $total = 0;

    foreach ($jv_expence as $row) {
        if ($row['dr_cr'] == 'dr') {
            $total += $row['total'];
        } else {
            $total -= $row['total'];
        }

    }
    $tot_expence['jv_parti']['total'] += $total;

    $builder = $db->table('sales_ACparticu sp');
    $builder->select('sa.id as voucher_id,sa.v_type as sg_type,sp.account as sp_acc,ac.name as account_name,sp.amount as sg_amount');
    $builder->join('sales_ACinvoice sa', 'sa.id = sp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where('(sa.v_type="general" OR sa.v_type = "return")');
    $builder->where(array('sp.account' => $id,'sp.is_delete' => '0'));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sa.is_delete' => '0','sa.is_cancle' => '0'));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sg_income = $query->getResultArray();

    $tot_expence['general_sales']['total']=0;
    
    foreach ($sg_income as $row) {
        $row['sg_amount'] = (float) $row['sg_amount'];
        $total = (((float) @$tot_expence['general_sales'][$row['sg_type']]) ? (float) $tot_expence['general_sales'][$row['sg_type']] : 0) + (float) $row['sg_amount'];
        $tot_expence['general_sales'][$row['sg_type']] = $total; 
        $tot_expence['general_sales']['total'] = (float)@$tot_expence['general_sales']['return'] - (float)@$tot_expence['general_sales']['general'];
    }

    $builder = $db->table('sales_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.total');
    $builder->join('sales_invoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'invoice'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();
    $total = 0;
    foreach ($sales_invoice as $row) {
        $row['pg_amount'] = $row['total'];
        $total += $row['pg_amount'];
    }
    $tot_expence['sales_invoice']['total'] = $total;
 
    $builder = $db->table('sales_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.total');
    $builder->join('sales_return pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'return'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();
    $total = 0;
    foreach ($sales_return as $row) {
        $row['pg_amount'] = $row['total'];
        $total += $row['pg_amount'];
    }
    $tot_expence['sales_return']['total'] = $total;

    $builder = $db->table('purchase_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.total');
    $builder->join('purchase_invoice pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'invoice'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();
    $total = 0;
    foreach ($purchase_invoice as $row) {
        $row['pg_amount'] = $row['total'];
        $total += $row['pg_amount'];
    }
    $tot_expence['purchase_invoice']['total'] = $total;

    $builder = $db->table('purchase_item pp');
    $builder->select('pg.id,pp.item_id as pp_acc,ac.name as account_name,pp.total');
    $builder->join('purchase_return pg', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pp.item_id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('pp.is_delete' => '0','pp.is_expence' => '1','pp.type' => 'return'));
    $builder->where(array('DATE(pg.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    $total = 0;
    foreach ($purchase_return as $row) {
        $row['pg_amount'] = $row['total'];
        $total += $row['pg_amount'];
    }
    $tot_expence['purchase_return']['total'] = $total;

    $tot_expence['from'] = $start_date;
    $tot_expence['to'] = $end_date;
    $tot_expence['id'] = $id;
    
    return $tot_expence;
}
function new_get_trading_expence_account_wise($start_date, $end_date, $id)
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

        if ($row['mode'] == 'Receipt') {

            $rec_total = (@$arr[$row['month']]['debit'] ? $arr[$row['month']]['debit'] : 0) + $row['bt_total'];
            $arr[$row['month']]['debit'] = $rec_total;
            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        } else {

            $pay_total = (@$arr[$row['month']]['credit'] ? $arr[$row['month']]['credit'] : 0) + $row['bt_total'];
            $arr[$row['month']]['credit'] = $pay_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        }
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
    // echo '<pre>';print_r($jv_income);exit;
    $total = 0;
    $arr = array();

    foreach ($jv_income as $row) {
        if ($row['dr_cr'] == 'cr') {
            $cr_tot = ((@$arr[$row['month']]['credit']) ? @$arr[$row['month']]['credit'] : 0) + $row['total'];
            $arr[$row['month']]['credit'] = $cr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

            $dr_tot = ((@$arr[$row['month']]['debit']) ? @$arr[$row['month']]['debit'] : 0) + $row['total'];
            $arr[$row['month']]['debit'] = $dr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }
    }
    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pg.net_amount as pg_amount,ac.id as account_id,ac.name as account_name');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id =pg.party_account');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pg.party_account' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $sg_expence = $query->getResultArray();

    foreach ($sg_expence as $row) {
        if ($row['pg_type'] == 'return') {
            $cr_tot = ((@$arr[$row['month']]['credit']) ? @$arr[$row['month']]['credit'] : 0) + $row['pg_amount'];
            $arr[$row['month']]['credit'] = $cr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['pg_amount'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

            $dr_tot = ((@$arr[$row['month']]['debit']) ? @$arr[$row['month']]['debit'] : 0) + $row['pg_amount'];
            $arr[$row['month']]['debit'] = $dr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['pg_amount'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        }
    }
}
function get_generalSales_monthly_AcWise($start_date, $end_date, $id)
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
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pp.amount as pg_amount,pp.sub_total,pp.added_amt');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.account' => $id));
    $builder->where(array('pg.is_delete' => '0','pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    $arr = array();
    // echo '<pre>';print_r($pg_income);
    $tot_income = array();
    foreach ($pg_income as $row) {

        $after_disc = 0;

       
            $row['pg_amount'] = (float) $row['sub_total'] + (float) $row['added_amt'];
      
        $total = ((@$tot_income['generalSale'][$row['month']][$row['pg_type']]) ? $tot_income['generalSale'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_income['generalSale'][$row['month']][$row['pg_type']] = $total;

        $tot_income['generalSale'][$row['month']]['total'] = (float)@$tot_income['generalSale'][$row['month']]['general'] - (float) @$tot_income['generalSale'][$row['month']]['return'];
        $tot_income['generalSale'][$row['month']]['year'] = $row['year'];
        $tot_income['generalSale'][$row['month']]['month'] = $row['month'];

    }

    $result = array();
    $result = @$tot_income;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    // echo '<pre>';print_r($result);exit;

    return $result;
}
function get_generalPurchase_monthly_AcWise($start_date, $end_date, $id)
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
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('pp.account' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));

    $query = $builder->get();
    $pg_expence = $query->getResultArray();
    //print_r()
    $arr = array();
    $tot_expence = array();
    foreach ($pg_expence as $row) {

        $after_disc = 0;

        $row['pg_amount'] = (float) $row['pg_amount'];
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
// ********************** income and pl used function ************************//
function get_bank_cash_monthly_AcWise($start_date, $end_date, $id)
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

        if ($row['mode'] == 'Receipt') {

            $rec_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $rec_total;
            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        } else {

            $pay_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $pay_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['bt_total'];
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
function get_jv_monthly_AcWise($start_date, $end_date, $id)
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
    // echo '<pre>';print_r($jv_income);exit;
    $total = 0;
    $arr = array();

    foreach ($jv_income as $row) {
        if ($row['dr_cr'] == 'cr') {
            $cr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) + $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $cr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

            $dr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) + $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $dr_tot;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['total'];
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
//******************* expence and pl used function****** */
function get_purchase_bank_cash_monthly_AcWise($start_date, $end_date, $id)
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

    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =bt.particular');
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    //$builder->groupBy('MONTH(bt.receipt_date)');
    $query = $builder->get();
    $bank_expence = $query->getResultArray();

    $total = 0;
    $arr = array();

    foreach ($bank_expence as $row) {

        if ($row['mode'] == 'Receipt') {
            $rec_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $rec_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        } else {
            $pay_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $pay_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) + $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
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
    //echo '<pre>';print_r($result);exit;
    return $result;
}
function get_purchase_jv_monthly_AcWise($start_date, $end_date, $id)
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

    $builder = $db->table('jv_particular jv');
    $builder->select('MONTH(jv.date) as month,YEAR(jv.date) as year,ac.id as account_id,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id =jv.particular');
    $builder->where(array('jv.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_expence = $query->getResultArray();

    $total = 0;
    $arr = array();

    foreach ($jv_expence as $row) {

        if ($row['dr_cr'] == 'cr') {

            $cr_total = (@$arr[$row['month']][$row['dr_cr']] ? $arr[$row['month']][$row['dr_cr']] : 0) + $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $cr_total;

            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) - $row['total'];
            $arr[$row['month']]['total'] = $total;

            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];

        } else {

            $dr_total = (@$arr[$row['month']][$row['dr_cr']] ? $arr[$row['month']][$row['dr_cr']] : 0) + $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $dr_total;

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


?>