<?php
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
//********** End Trading Income & Expense Sub Group LOOPING ***********//

//******* Start P & L Income & Expense Sub Group LOOPING *******//

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

//********** End P & L Income & Expense Sub Group LOOPING ***********//

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

// function milling_SaleItemSTock($id,$type,$start_date = '', $end_date = ''){
//     if ($start_date == '') {

//         if (date('m') <= '03') {
//             $year = date('Y') - 1;
//             $start_date = $year . '-04-01';
//         } else {
//             $year = date('Y');
//             $start_date = $year . '-04-01';
//         }
//     }
//     if ($end_date == '') {

//         if (date('m') <= '03') {
//             $year = date('Y');
//         } else {
//             $year = date('Y') + 1;
//         }
//         $end_date = $year . '-03-31';
//     }

//     $db = \Config\Database::connect();
//     if (session('DataSource')) {
//         $db->setDatabase(session('DataSource'));
//     }
//     $builder = $db->table('milling_item mi');
//     $builder->select('mi.is_send,mi.send_pcs,mi.screen,mi.pcs,mi.finish_cut,mi.finish_id,mi.id,mi.mtr,mi.meter,mi.cut,mi.finish_pcs,mi.finish_mtr,mi.finish_price,mi.amount,mi.finish_amount');
//     $builder->join('finish_mill fm', 'fm.id = mi.finish_id','left');
//     $builder->where(array('mi.is_delete' => '0'));
//     if($type == 'Finish'){
//         $builder->where(array('mi.screen' => $id));
//     }else{
//         $builder->where(array('mi.pid' => $id));
//     }
//     $builder->where(array('DATE(mi.created_at)  >= ' => $start_date));
//     $builder->where(array('DATE(mi.created_at)  <= ' => $end_date));
//     $query = $builder->get();
//     $result = $query->getResultArray();

//     $gray_meter = 0;
//     $gray_pcs = 0;
//     $cut_meter = 0;
//     $send_meter = 0;
//     $finish_pcs = 0;
//     $finish_mtr = 0;
//     $finish_cut = 0;
//     $gray_cut = 0;
//     $send_pcs = 0;

//     foreach ($result as $row) {
//         if($type == 'Finish'){
//             $gray_pcs += @$row['finish_pcs'] ? $row['finish_pcs'] : 0;
//             $gray_meter += @$row['finish_mtr'] ? $row['finish_mtr'] : 0;
//             $gray_cut += @$row['finish_cut'] ? $row['finish_cut'] : 0;
//         }
//         else{
//             $gray_meter += $row['meter'];
//             $gray_pcs += $row['pcs'];
//             $gray_cut = 0;

//             $cut_meter  += $row['cut'];
//             $send_meter += $row['mtr'];
//             $send_pcs += intval(@$row['send_pcs']);
//         }

//     }

//     $re['mill'] = array(
//         'gray_purcahse' => $gray_meter,
//         'gray_pcs' => $gray_pcs,
//         'gray_cut' => $gray_cut,

//         'send_mill' => $send_meter,
//         'mill_cut' => $cut_meter,
//         'send_pcs' => $send_pcs,

//         // 'finish_meter' => $finish_mtr,
//         // 'finish_cut' => $finish_cut,
//         // 'finish_pcs' => $finish_pcs,
//     );

//     return $re;
// }

// function jobwork_ItemSTock($id,$type,$start_date = '', $end_date = ''){

//     // echo '<pre>';print_r('$re');exit;
//     if ($start_date == '') {
//         if (date('m') <= '03') {
//             $year = date('Y') - 1;
//             $start_date = $year . '-04-01';
//         } else {
//             $year = date('Y');
//             $start_date = $year . '-04-01';
//         }
//     }
//     if ($end_date == '') {

//         if (date('m') <= '03') {
//             $year = date('Y');
//         } else {
//             $year = date('Y') + 1;
//         }
//         $end_date = $year . '-03-31';
//     }

//     $db = \Config\Database::connect();
//     if (session('DataSource')) {
//         $db->setDatabase(session('DataSource'));
//     }
//     $builder = $db->table('job_item mi');
//     $builder->select('mi.send_pcs,mi.send_mtr,mi.rec_pcs,mi.rec_mtr,mi.screen,mi.id,mi.pid');

//     $builder->where(array('mi.is_delete' => '0'));
//     if($type == 'Jobwork'){
//         $builder->where(array('mi.screen' => $id));
//     }else{
//         $builder->where(array('mi.pid' => $id));
//     }
//     $builder->where(array('DATE(mi.created_at)  >= ' => $start_date));
//     $builder->where(array('DATE(mi.created_at)  <= ' => $end_date));
//     $query = $builder->get();
//     $result = $query->getResultArray();

//     $send_meter = 0;
//     $send_pcs = 0;
//     // $cut_meter = 0;
//     $rec_meter = 0;
//     $rec_pcs = 0;

//     $send_pcs = 0;
//     $pending_pcs = 0;
//     $pending_mtr = 0;

//     foreach ($result as $row) {
//         if($type != 'Jobwork'){
//             $send_pcs += @$row['send_pcs'] ? $row['send_pcs'] : 0;
//             $send_meter += @$row['send_mtr'] ? $row['send_mtr'] : 0;
//             $rec_pcs =  0;
//             $rec_meter =  0;
//             $pending_pcs =  @$row['tot_pending_pcs'] ? $row['tot_pending_pcs'] : 0;
//             $pending_mtr =  @$row['pending_mtr'] ? $row['pending_mtr'] : 0;
//         }
//        else{

//             $send_pcs = 0;
//             $send_meter = 0;
//             $rec_pcs += @$row['rec_pcs'] ? $row['rec_pcs'] : 0;
//             $rec_meter += @$row['rec_mtr'] ? $row['rec_mtr'] : 0;
//             $pending_pcs +=  @$row['tot_pending_pcs'] ? $row['tot_pending_pcs'] : 0;
//             $pending_mtr +=  @$row['pending_mtr'] ? $row['pending_mtr'] : 0;

//        }

//     }

//     $re['job'] = array(
//         'send_pcs' => $send_pcs,
//         'send_mtr' => $send_meter,
//         'rec_pcs' => $rec_pcs,
//         'rec_mtr' => $rec_meter,
//         'pending_pcs' => $pending_pcs,
//         'pending_mtr' => $pending_mtr,
//     );

//     return $re;
// }

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

function sale_purchase_vouhcer_t($start_date = '', $end_date = '')
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

    $builder = $db->table('purchase_invoice');
    $builder->select('SUM(taxable) as pur_taxable');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('is_cancle' => 0));
    $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purcahse = $query->getRowArray();

    // echo '<pre>';print_r($purcahse);exit;

    $builder = $db->table('purchase_return');
    $builder->select('SUM(taxable) as purRet_taxable');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('is_cancle' => 0));
    $builder->where(array('DATE(return_date)  >= ' => $start_date));
    $builder->where(array('DATE(return_date)  <= ' => $end_date));
    $query = $builder->get();
    $pur_ret = $query->getRowArray();

    $builder = $db->table('sales_invoice');
    $builder->select('SUM(taxable) as sale_taxable');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('is_cancle' => 0));
    $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sale = $query->getRowArray();

    // echo '<pre>';print_r($purcahse);exit;

    $builder = $db->table('sales_return');
    $builder->select('SUM(taxable) as saleRet_taxable');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('is_cancle' => 0));
    $builder->where(array('DATE(return_date)  >= ' => $start_date));
    $builder->where(array('DATE(return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sale_ret = $query->getRowArray();

    // need to change as per above query  //

    $builder = $db->table('gray_item gi');
    $builder->select('gi.price,gi.id,gi.meter,gi.purchase_type');
    $builder->join('grey g', 'g.id = gi.voucher_id');
    $builder->where('(gi.purchase_type="Gray" OR gi.purchase_type = "Finish")');
    $builder->where(array('g.is_delete' => '0'));
    $builder->where(array('DATE(g.inv_date)  >= ' => $start_date));
    $builder->where(array('DATE(g.inv_date)  <= ' => $end_date));
    $query = $builder->get();
    $gray_finish_purchase = $query->getResultArray();

    $builder = $db->table('saleMillInvoice_Item sgi');
    $builder->select('sgi.price,sgi.id,sgi.meter,sgi.item_type');
    $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
    $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "Finish")');
    $builder->where(array('sg.is_delete' => '0'));
    $builder->where(array('DATE(sg.date)  >= ' => $start_date));
    $builder->where(array('DATE(sg.date)  <= ' => $end_date));
    $query = $builder->get();
    $gray_finish_sale = $query->getResultArray();

    $builder = $db->table('retGrayFinish_item rgi');
    $builder->select('rgi.price,rgi.id,rgi.ret_meter,rgi.purchase_type');
    $builder->join('retGrayFinish rg', 'rg.id = rgi.voucher_id');
    $builder->where('(rgi.purchase_type="Gray" OR rgi.purchase_type = "Finish")');
    $builder->where(array('rg.is_delete' => '0'));
    $builder->where(array('DATE(rg.date)  >= ' => $start_date));
    $builder->where(array('DATE(rg.date)  <= ' => $end_date));
    $query = $builder->get();
    $Retgray_finish_purchase = $query->getResultArray();

    $builder = $db->table('saleMillReturn_Item rsgi');
    $builder->select('rsgi.price,rsgi.id,rsgi.ret_meter,rsgi.item_type');
    $builder->join('saleMillReturn rsg', 'rsg.id = rsgi.voucher_id');
    $builder->where('(rsgi.item_type="Gray" OR rsgi.item_type = "Finish")');
    $builder->where(array('rsg.is_delete' => '0'));
    $builder->where(array('DATE(rsg.date)  >= ' => $start_date));
    $builder->where(array('DATE(rsg.date)  <= ' => $end_date));
    $query = $builder->get();
    $Retgray_finish_sale = $query->getResultArray();

    $sale_Gray_total_rate = 0;
    $sale_Gray_total_qty = 0;
    $sale_Finish_total_rate = 0;
    $sale_Finish_total_qty = 0;

    $purchase_Gray_total_rate = 0;
    $purchase_Gray_total_qty = 0;
    $purchase_Finish_total_rate = 0;
    $purchase_Finish_total_qty = 0;

    $Retsale_Gray_total_rate = 0;
    $Retsale_Gray_total_qty = 0;
    $Retsale_Finish_total_rate = 0;
    $Retsale_Finish_total_qty = 0;

    $Retpurchase_Gray_total_rate = 0;
    $Retpurchase_Gray_total_qty = 0;
    $Retpurchase_Finish_total_rate = 0;
    $Retpurchase_Finish_total_qty = 0;

    $Purret_total_qty = 0;
    $Purret_total_rate = 0;
    $Saleret_total_qty = 0;
    $Saleret_total_rate = 0;

    foreach ($gray_finish_sale as $row) {
        if ($row['item_type'] == 'gray') {
            $sale_Gray_total_rate += $row['price'] * $row['meter'];
            $sale_Gray_total_qty += $row['meter'];
        } else {
            $sale_Finish_total_rate += $row['price'] * $row['meter'];
            $sale_Finish_total_qty += $row['meter'];
        }
    }

    foreach ($gray_finish_purchase as $row) {
        if ($row['purchase_type'] == 'Gray') {
            $purchase_Gray_total_rate += $row['price'] * $row['meter'];
            $purchase_Gray_total_qty += $row['meter'];
        } else {
            $purchase_Finish_total_rate += $row['price'] * $row['meter'];
            $purchase_Finish_total_qty += $row['meter'];
        }
    }

    foreach ($Retgray_finish_purchase as $row) {
        if ($row['purchase_type'] == 'Gray') {
            $Retpurchase_Gray_total_rate += $row['price'] * $row['ret_meter'];
            $Retpurchase_Gray_total_qty += $row['ret_meter'];
        } else {
            $Retpurchase_Finish_total_rate += $row['price'] * $row['ret_meter'];
            $Retpurchase_Finish_total_qty += $row['ret_meter'];
        }
    }

    foreach ($Retgray_finish_sale as $row) {
        if ($row['item_type'] == 'gray') {
            $Retsale_Gray_total_rate += $row['price'] * $row['ret_meter'];
            $Retsale_Gray_total_qty += $row['ret_meter'];
        } else {
            $Retsale_Finish_total_rate += $row['price'] * $row['ret_meter'];
            $Retsale_Finish_total_qty += $row['ret_meter'];
        }
    }

    $result = array(
        'pur_total_rate' => @$purcahse['pur_taxable'] ? $purcahse['pur_taxable'] : 0,
        'Purret_total_rate' => @$pur_ret['purRet_taxable'] ? $pur_ret['purRet_taxable'] : 0,
        'sale_total_rate' => @$sale['sale_taxable'] ? $sale['sale_taxable'] : 0,
        'Saleret_total_rate' => @$sale_ret['saleRet_taxable'] ? $sale_ret['saleRet_taxable'] : 0,
        'sale_Gray_total_rate' => $sale_Gray_total_rate,
        'sale_Gray_total_qty' => $sale_Gray_total_qty,
        'purchase_Gray_total_rate' => $purchase_Gray_total_rate,
        'purchase_Gray_total_qty' => $purchase_Gray_total_qty,
        'Retsale_Gray_total_rate' => $Retsale_Gray_total_rate,
        'Retsale_Gray_total_qty' => $Retsale_Gray_total_qty,
        'Retpurchase_Gray_total_rate' => $Retpurchase_Gray_total_rate,
        'Retpurchase_Gray_total_qty' => $Retpurchase_Gray_total_qty,
        'sale_Finish_total_rate' => $sale_Finish_total_rate,
        'sale_Finish_total_qty' => $sale_Finish_total_qty,
        'purchase_Finish_total_rate' => $purchase_Finish_total_rate,
        'purchase_Finish_total_qty' => $purchase_Finish_total_qty,
        'Retsale_Finish_total_rate' => $Retsale_Finish_total_rate,
        'Retsale_Finish_total_qty' => $Retsale_Finish_total_qty,
        'Retpurchase_Finish_total_rate' => $Retpurchase_Finish_total_rate,
        'Retpurchase_Finish_total_qty' => $Retpurchase_Finish_total_qty,
        'from' => $start_date,
        'to' => $end_date,
    );

    return $result;
}

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

    // Gray/Finish Sale AND Purchase //

    $builder = $db->table('gray_item gi');
    $builder->select('gi.price,gi.id,gi.meter,gi.purchase_type');
    $builder->join('grey g', 'g.id = gi.voucher_id');
    $builder->where('(gi.purchase_type="Gray" OR gi.purchase_type = "Finish")');
    $builder->where(array('g.is_delete' => '0'));
    $builder->where(array('DATE(g.inv_date)  >= ' => $start_date));
    $builder->where(array('DATE(g.inv_date)  <= ' => $end_date));
    $query = $builder->get();
    $gray_finish_purchase = $query->getResultArray();

    $builder = $db->table('saleMillInvoice_Item sgi');
    $builder->select('sgi.price,sgi.id,sgi.meter,sgi.item_type');
    $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
    $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "Finish")');
    $builder->where(array('sg.is_delete' => '0'));
    $builder->where(array('DATE(sg.date)  >= ' => $start_date));
    $builder->where(array('DATE(sg.date)  <= ' => $end_date));
    $query = $builder->get();
    $gray_finish_sale = $query->getResultArray();

    $builder = $db->table('retGrayFinish_item rgi');
    $builder->select('rgi.price,rgi.id,rgi.ret_meter,rgi.purchase_type');
    $builder->join('retGrayFinish rg', 'rg.id = rgi.voucher_id');
    $builder->where('(rgi.purchase_type="Gray" OR rgi.purchase_type = "Finish")');
    $builder->where(array('rg.is_delete' => '0'));
    $builder->where(array('DATE(rg.date)  >= ' => $start_date));
    $builder->where(array('DATE(rg.date)  <= ' => $end_date));
    $query = $builder->get();
    $Retgray_finish_purchase = $query->getResultArray();

    $builder = $db->table('saleMillReturn_Item rsgi');
    $builder->select('rsgi.price,rsgi.id,rsgi.ret_meter,rsgi.item_type');
    $builder->join('saleMillReturn rsg', 'rsg.id = rsgi.voucher_id');
    $builder->where('(rsgi.item_type="Gray" OR rsgi.item_type = "Finish")');
    $builder->where(array('rsg.is_delete' => '0'));
    $builder->where(array('DATE(rsg.date)  >= ' => $start_date));
    $builder->where(array('DATE(rsg.date)  <= ' => $end_date));
    $query = $builder->get();
    $Retgray_finish_sale = $query->getResultArray();

    $pur_total_rate = 0;
    $pur_total_qty = 0;
    $sale_total_rate = 0;
    $sale_total_qty = 0;

    $sale_Gray_total_rate = 0;
    $sale_Gray_total_qty = 0;
    $sale_Finish_total_rate = 0;
    $sale_Finish_total_qty = 0;

    $purchase_Gray_total_rate = 0;
    $purchase_Gray_total_qty = 0;
    $purchase_Finish_total_rate = 0;
    $purchase_Finish_total_qty = 0;

    $Retsale_Gray_total_rate = 0;
    $Retsale_Gray_total_qty = 0;
    $Retsale_Finish_total_rate = 0;
    $Retsale_Finish_total_qty = 0;

    $Retpurchase_Gray_total_rate = 0;
    $Retpurchase_Gray_total_qty = 0;
    $Retpurchase_Finish_total_rate = 0;
    $Retpurchase_Finish_total_qty = 0;

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

    foreach ($gray_finish_sale as $row) {
        if ($row['item_type'] == 'gray') {
            $sale_Gray_total_rate += $row['price'] * $row['meter'];
            $sale_Gray_total_qty += $row['meter'];
        } else {
            $sale_Finish_total_rate += $row['price'] * $row['meter'];
            $sale_Finish_total_qty += $row['meter'];
        }
    }

    foreach ($gray_finish_purchase as $row) {
        if ($row['purchase_type'] == 'Gray') {
            $purchase_Gray_total_rate += $row['price'] * $row['meter'];
            $purchase_Gray_total_qty += $row['meter'];
        } else {
            $purchase_Finish_total_rate += $row['price'] * $row['meter'];
            $purchase_Finish_total_qty += $row['meter'];
        }
    }

    foreach ($Retgray_finish_purchase as $row) {
        if ($row['purchase_type'] == 'Gray') {
            $Retpurchase_Gray_total_rate += $row['price'] * $row['ret_meter'];
            $Retpurchase_Gray_total_qty += $row['ret_meter'];
        } else {
            $Retpurchase_Finish_total_rate += $row['price'] * $row['ret_meter'];
            $Retpurchase_Finish_total_qty += $row['ret_meter'];
        }
    }

    foreach ($Retgray_finish_sale as $row) {
        if ($row['item_type'] == 'gray') {
            $Retsale_Gray_total_rate += $row['price'] * $row['ret_meter'];
            $Retsale_Gray_total_qty += $row['ret_meter'];
        } else {
            $Retsale_Finish_total_rate += $row['price'] * $row['ret_meter'];
            $Retsale_Finish_total_qty += $row['ret_meter'];
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
        'sale_Gray_total_rate' => $sale_Gray_total_rate,
        'sale_Gray_total_qty' => $sale_Gray_total_qty,
        'purchase_Gray_total_rate' => $purchase_Gray_total_rate,
        'purchase_Gray_total_qty' => $purchase_Gray_total_qty,
        'Retsale_Gray_total_rate' => $Retsale_Gray_total_rate,
        'Retsale_Gray_total_qty' => $Retsale_Gray_total_qty,
        'Retpurchase_Gray_total_rate' => $Retpurchase_Gray_total_rate,
        'Retpurchase_Gray_total_qty' => $Retpurchase_Gray_total_qty,
        'sale_Finish_total_rate' => $sale_Finish_total_rate,
        'sale_Finish_total_qty' => $sale_Finish_total_qty,
        'purchase_Finish_total_rate' => $purchase_Finish_total_rate,
        'purchase_Finish_total_qty' => $purchase_Finish_total_qty,
        'Retsale_Finish_total_rate' => $Retsale_Finish_total_rate,
        'Retsale_Finish_total_qty' => $Retsale_Finish_total_qty,
        'Retpurchase_Finish_total_rate' => $Retpurchase_Finish_total_rate,
        'Retpurchase_Finish_total_qty' => $Retpurchase_Finish_total_qty,
        'from' => $start_date,
        'to' => $end_date,
    );
    // echo '<pre>';print_r($re);exit;
    return $re;
}

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
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,ac.id as account_id,pp.total as pg_amount');
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

    foreach ($pg_expense as $row) {

        $after_disc = 0;

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }

        // if($row['amtx_type'] == 'Fixed'){
        //     $row['pg_amount'] = (float)$after_disc - (float)$row['amtx'];
        // }else{
        //     $row['pg_amount'] = (float)$after_disc - ((float)$after_disc * ((float)$row['amtx'] / 100));
        // }

        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

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
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank_expens = $query->getResultArray();

    foreach ($bank_expens as $row) {
        if ($row['mode'] == 'Receipt') {
            $total = (@$tot_pg_expens[$row['account_name']]['bt_total']) ? $tot_pg_expens[$row['account_name']]['bt_total'] : 0 - $row['bt_total'];
        } else {
            $total = (@$tot_pg_expens[$row['account_name']]['bt_total']) ? $tot_pg_expens[$row['account_name']]['bt_total'] : 0 + $row['bt_total'];
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
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv_expens = $query->getResultArray();

    $tot_pg_expens = array();

    foreach ($jv_expens as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = (@$tot_pg_expens[$row['account_name']]['jv_total']) ? $tot_pg_expens[$row['account_name']]['jv_total'] : 0 - $row['jv_total'];
        } else {
            $total = (@$tot_pg_expens[$row['account_name']]['jv_total']) ? $tot_pg_expens[$row['account_name']]['jv_total'] : 0 + $row['jv_total'];
        }
        $tot_pg_expens[$row['account_name']]['jv_total'] = $total;
        $tot_pg_expens[$row['account_name']]['account_id'] = $row['account_id'];

    }

    $total_ex_arr = array();

    foreach ($tot_pg_expens as $key => $value) {
        $tot_pg_expens[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
        $total_ex_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
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
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACparticu pp', 'pp.account = ac.id');
    $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    
    $tot_pg_income = array();

    foreach ($pg_income as $row) {

        $after_disc = 0;

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }

        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

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

    foreach ($jv_income as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
        } else {
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }
        $tot_pg_income[$row['account_name']]['jv_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {
        $tot_pg_income[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
        $total_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
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
    // echo '<pre>';print_r($pg_income);

    foreach ($pg_income as $row) {

        $after_disc = 0;

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }

       
        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

        $total = ((@$tot_income['general_sales'][$row['pg_type']]) ? $tot_income['general_sales'][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_income['general_sales'][$row['pg_type']] = $total;

        $tot_income['general_sales']['total'] = (float) $tot_income['general_sales']['general'] - (float) @$tot_income['general_sales']['return'];
    }
    $bank_income = array();

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
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
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type,pg.is_delete,pp.is_delete as pp_delete');
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

    $total = 0;

    foreach ($pg_expence as $row) {

        $after_disc = 0;

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }

        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

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

    $tot_expence['from'] = $start_date;
    $tot_expence['to'] = $end_date;
    $tot_expence['id'] = $id;
    // echo '<pre>';print_r($tot_expence);exit;
    return $tot_expence;
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
    $builder->select('MONTH(pg.invoice_date) as month,YEAR(pg.invoice_date) as year,pg.v_type as pg_type,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('sales_ACparticu pp', 'pg.id = pp.parent_id');
    $builder->where(array('pp.account' => $id));
    // $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));

    $query = $builder->get();
    $pg_income = $query->getResultArray();
    $arr = array();
    // echo '<pre>';print_r($pg_income);
    $tot_income = array();
    foreach ($pg_income as $row) {

        $after_disc = 0;

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }

        // if($row['amtx_type'] == 'Fixed'){
        //     $row['pg_amount'] = (float)$after_disc - (float)$row['amtx'];
        // }else{
        //     $row['pg_amount'] = (float)$after_disc - ((float)$after_disc * ((float)$row['amtx'] / 100));
        // }

        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

        $total = ((@$tot_income['generalSale'][$row['month']][$row['pg_type']]) ? $tot_income['generalSale'][$row['month']][$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_income['generalSale'][$row['month']][$row['pg_type']] = $total;

        $tot_income['generalSale'][$row['month']]['total'] = (float) $tot_income['generalSale'][$row['month']]['general'] - (float) @$tot_income['generalSale'][$row['month']]['return'];
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
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
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

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }


        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

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

function trading_tot_data($start_date = '', $end_date = '')
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

    // Trading Income Start //

    $builder = $db->table('gl_group');
    $builder->select('id');
    $builder->where('name', 'Trading Income');
    $query = $builder->get();
    $gl_inc_id = $query->getRowArray();

    $gl_ids = gl_list([$gl_inc_id['id']]);
    $gl_ids[] = $gl_inc_id['id'];

    $pg_income = array();
    foreach ($gl_ids as $id) {
        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,gl.id as gl_id,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('purchase_particu pp', 'pp.account = ac.id');
        $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
        $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pg.is_delete' => '0'));
        $builder->where(array('DATE(pg.created_at)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pg.created_at)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $res = $query->getResultArray();

        if (!empty($res)) {
            foreach ($res as $row) {
                $pg_income[] = $row;
            }
        }
    }

    $tot_pg_income = array();

    foreach ($pg_income as $row) {
        $total = (@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0 + $row['pg_amount'];
        $tot_pg_income[$row['account_name']][$row['pg_type']] = $total;
    }

    $bank_income = array();
    foreach ($gl_ids as $id) {

        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('bank_tras bt', 'bt.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(bt.created_at)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.created_at)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $res = $query->getResultArray();

        if (!empty($res)) {
            foreach ($res as $row) {
                $bank_income[] = $row;
            }
        }
    }

    foreach ($bank_income as $row) {
        $total = (@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0 + $row['bt_total'];
        $tot_pg_income[$row['account_name']]['bt_total'] = $total;
    }

    $jv_income = array();

    foreach ($gl_ids as $id) {

        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('jv_particular jv', 'jv.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(jv.created_at)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(jv.created_at)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $res = $query->getResultArray();

        if (!empty($res)) {
            foreach ($res as $row) {
                $jv_income[] = $row;
            }
        }
    }

    foreach ($jv_income as $row) {
        $total = (@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0 + $row['total'];
        $tot_pg_income[$row['account_name']]['jv_total'] = $total;
    }

    $total_arr = array();
    foreach ($tot_pg_income as $key => $value) {
        $tot_pg_income[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
        $total_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
    }

    if (!empty($total_arr)) {
        $trading_income_total = array_sum($total_arr);
    } else {
        $trading_income_total = 0;
    }

    // Trading Expense Start //

    $builder = $db->table('gl_group');
    $builder->select('id');
    $builder->where('name', 'Trading Expenses');
    $query = $builder->get();
    $gl_exp_id = $query->getRowArray();

    $gl_ids = gl_list([$gl_exp_id['id']]);
    $gl_ids[] = $gl_exp_id['id'];

    $pg_expense = array();

    foreach ($gl_ids as $id) {

        $builder = $db->table('gl_group gl');
        $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('purchase_particu pp', 'pp.account = ac.id');
        $builder->join('purchase_general pg', 'pg.id = pp.parent_id');
        $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pg.is_delete' => '0'));
        $builder->where(array('DATE(pg.created_at)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pg.created_at)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $res = $query->getResultArray();

        if (!empty($res)) {
            foreach ($res as $row) {
                $pg_expense[] = $row;
            }
        }
    }

    $tot_pg_expens = array();

    foreach ($pg_expense as $row) {
        $total = (@$tot_pg_expens[$row['account_name']][$row['pg_type']]) ? $tot_pg_expens[$row['account_name']][$row['pg_type']] : 0 + $row['pg_amount'];
        $tot_pg_expens[$row['account_name']][$row['pg_type']] = $total;
    }

    $bank_expens = array();

    foreach ($gl_ids as $id) {

        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('bank_tras bt', 'bt.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(bt.created_at)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.created_at)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $res = $query->getResultArray();

        if (!empty($res)) {
            foreach ($res as $row) {
                $bank_expens[] = $row;
            }
        }
    }

    foreach ($bank_expens as $row) {
        $total = (@$tot_pg_expens[$row['account_name']]['bt_total']) ? $tot_pg_expens[$row['account_name']]['bt_total'] : 0 + $row['bt_total'];
        $tot_pg_expens[$row['account_name']]['bt_total'] = $total;
    }
    $jv_expens = array();
    foreach ($gl_ids as $id) {

        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,jv.amount as jv_total');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('jv_particular jv', 'jv.particular = ac.id');
        $builder->where(array('gl.id' => $id));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(jv.created_at)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(jv.created_at)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $res = $query->getResultArray();

        if (!empty($res)) {
            foreach ($res as $row) {
                $jv_expens[] = $row;
            }
        }
    }
    foreach ($jv_expens as $row) {
        $total = (@$tot_pg_expens[$row['account_name']]['jv_total']) ? $tot_pg_expens[$row['account_name']]['jv_total'] : 0 + $row['jv_total'];
        $tot_pg_expens[$row['account_name']]['jv_total'] = $total;
    }

    $total_ex_arr = array();

    foreach ($tot_pg_expens as $key => $value) {
        $tot_pg_expens[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
        $total_ex_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
    }

    if (!empty($total_ex_arr)) {
        $trading_expense_total = array_sum($total_ex_arr);
    } else {
        $trading_expense_total = 0;
    }

    $data = array(
        'income_ac' => $tot_pg_income,
        'enpense_ac' => $tot_pg_expens,
        'trading_income' => $trading_income_total,
        'trading_expense' => $trading_expense_total,
        'from' => $start_date,
        'to' => $end_date,
    );
    // echo '<pre>';print_r($data);exit;
    return $data;
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
    $builder->select('ac.id as account_id, pg.v_type as pg_type,pp.account as pp_acc,ac.name as account_name,pp.amount as pg_amount,pg.disc_type,pg.discount,pg.amty_type,pg.amty');
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

        if ($row['disc_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] - (float) $row['discount'];
            $after_disc = $row['pg_amount'];
        } else {
            $row['pg_amount'] = ((float) $row['pg_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['pg_amount'];
        }

        if ($row['amty_type'] == 'Fixed') {
            $row['pg_amount'] = (float) $row['pg_amount'] + (float) $row['amty'];
        } else {
            $row['pg_amount'] = (float) $row['pg_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

        if (isset($tot_pl_expense[$row['account_name']][$row['pg_type']])) {
            $total = $tot_pl_expense[$row['account_name']][$row['pg_type']] + $row['pg_amount'];
            $tot_pl_expense[$row['account_name']][$row['pg_type']] = $total;
        } else {
            $tot_pl_expense[$row['account_name']][$row['pg_type']] = 0 + $row['pg_amount'];
        }

        // $total =  ($row['pg_amount'] + (float)$tot_pg_expens[$row['account_name']][$row['pg_type']]) : ( 0 + (float)$row['pg_amount']);
        // echo '<br>';print_r($total);
        // echo 'abc<br>';print_r(@$tot_pl_expense[$row['account_name']][$row['pg_type']]);
        // $tot_pl_expense[$row['account_name']][$row['pg_type']] = $total;
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


    $total_arr = array();
    foreach ($tot_pl_expense as $key => $value) {
        $tot_pl_expense[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['sale_brokrage']+@$value['pur_brokrage']+@$value['bt_total'];
        $total_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['sale_brokrage']+@$value['pur_brokrage']+@$value['bt_total'];
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
    $builder->select('sa.v_type as sa_type,sp.account as sp_acc,ac.id as account_id,ac.name as account_name,sp.amount as sa_amount,sa.disc_type,sa.discount,sa.amty_type,sa.amty');
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
        $after_disc = 0;

        if ($row['disc_type'] == 'Fixed') {
            $row['sa_amount'] = (float) $row['sa_amount'] - (float) $row['discount'];
            $after_disc = $row['sa_amount'];
        } else {
            $row['sa_amount'] = ((float) $row['sa_amount'] * ((float) $row['discount'] / 100));
            $after_disc = $row['sa_amount'];
        }

        // if($row['amtx_type'] == 'Fixed'){
        //     $row['sa_amount'] = (float)$after_disc - (float)$row['amtx'];
        // }else{
        //     $row['sa_amount'] = (float)$after_disc - ((float)$after_disc * ((float)$row['amtx'] / 100));
        // }

        if ($row['amty_type'] == 'Fixed') {
            $row['sa_amount'] = (float) $row['sa_amount'] + (float) $row['amty'];
        } else {
            $row['sa_amount'] = (float) $row['sa_amount'] + ((float) $after_disc * ((float) $row['amty'] / 100));
        }

        $total = ((@$tot_pl_income[$row['account_name']][$row['sa_type']]) ? $tot_pl_income[$row['account_name']][$row['sa_type']] : 0) + $row['sa_amount'];
        $tot_pl_income[$row['account_name']][$row['sa_type']] = $total;
        $tot_pl_income[$row['account_name']]['account_id'] = $row['account_id'];

    }

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

    $total_ex_arr = array();

    foreach ($tot_pl_income as $key => $value) {
        $tot_pl_income[$key]['total'] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
        $total_ex_arr[] = @$value['general']-@$value['return']+@$value['jv_total']+@$value['bt_total'];
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

function ledger($start_date = '', $end_date = '')
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

}

function balancesheet_detail($start_date = '', $end_date = '')
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

    $sale_purchase = sale_purchase_itm_total($start_date, $end_date);

    $duties_taxes = duties_taxes($start_date, $end_date);

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $jv_capital = jv_master($start_date, $end_date, $gl = "Capital");
    $bank_parti_capital = bank_parti_master($start_date, $end_date, $gl = "Capital");

    $jv_loan = jv_master($start_date, $end_date, $gl = "Loans");
    $bank_parti_loan = bank_parti_master($start_date, $end_date, $gl = "Loans");

    $bank_parti_currentLib = bank_parti_master($start_date, $end_date, $gl = "Current Liabilities");
    $jv_currentLib = jv_master($start_date, $end_date, $gl = "Current Liabilities");

    $ac_capital = array();
    $capital = array();

    foreach ($bank_parti_capital as $row) {
        if ($row['mode'] == 'Payment') {
            $total = ((@$capital[$row['gl_name']]['payment_total']) ? $capital[$row['gl_name']]['payment_total'] : 0) + $row['total'];
            $capital[$row['gl_name']]['payment_total'] = $total;

            $total = ((@$ac_capital[$row['account_name']]['pay_total']) ? $ac_capital[$row['account_name']]['pay_total'] : 0) + $row['total'];
            $ac_capital[$row['account_name']]['pay_total'] = $total;
        } else {
            $total = ((@$capital[$row['gl_name']]['receipt_total']) ? $capital[$row['gl_name']]['receipt_total'] : 0) + $row['total'];
            $capital[$row['gl_name']]['receipt_total'] = $total;

            $total = ((@$ac_capital[$row['account_name']]['rec_total']) ? $ac_capital[$row['account_name']]['rec_total'] : 0) + $row['total'];
            $ac_capital[$row['account_name']]['rec_total'] = $total;
        }
        // $ac_capital[$row['account_name']]['total'] = @$ac_capital[$row['account_name']]['rec_total'] - @$ac_capital[$row['account_name']]['pay_total'];
    }

    foreach ($ac_capital as $key => $value) {
        $ac_capital[$key]['total'] = @$ac_capital[$key]['total'] + (@$ac_capital[$key]['rec_total']-@$ac_capital[$key]['pay_total']);
    }

    foreach ($jv_capital as $row) {
        if ($row['jv_type'] == 'dr') {
            $total = ((@$capital[$row['gl_name']]['dr_total']) ? $capital[$row['gl_name']]['dr_total'] : 0) + $row['total'];
            $capital[$row['gl_name']]['dr_total'] = $total;

            $total = ((@$ac_capital[$row['account_name']]['dr_total']) ? $ac_capital[$row['account_name']]['dr_total'] : 0) + $row['total'];
            $ac_capital[$row['account_name']]['dr_total'] = $total;

        } else {
            $total = ((@$capital[$row['gl_name']]['cr_total']) ? $capital[$row['gl_name']]['cr_total'] : 0) + $row['total'];
            $capital[$row['gl_name']]['cr_total'] = $total;

            $total = ((@$ac_capital[$row['account_name']]['cr_total']) ? $ac_capital[$row['account_name']]['cr_total'] : 0) + $row['total'];
            $ac_capital[$row['account_name']]['cr_total'] = $total;
        }

        $capital[$row['gl_name']]['total'] = @$capital[$row['gl_name']]['cr_total']-@$capital[$row['gl_name']]['dr_total'];
    }

    foreach ($ac_capital as $key => $value) {
        $ac_capital[$key]['total'] = @$ac_capital[$key]['total'] + (@$ac_capital[$key]['cr_total']-@$ac_capital[$key]['dr_total']);
    }

    foreach ($capital as $key => $value) {
        $capital[$key]['total'] = (@$value['receipt_total']-@$value['payment_total']) + (@$value['cr_total']-@$value['dr_total']);
    }

    $loan = array();

    foreach ($bank_parti_loan as $row) {
        if ($row['mode'] == 'Payment') {
            $total = ((@$loan[$row['gl_name']]['payment_total']) ? $loan[$row['gl_name']]['payment_total'] : 0) + $row['total'];
            $loan[$row['gl_name']]['payment_total'] = $total;
        } else {
            $total = ((@$loan[$row['gl_name']]['receipt_total']) ? $loan[$row['gl_name']]['receipt_total'] : 0) + $row['total'];
            $loan[$row['gl_name']]['receipt_total'] = $total;
        }
    }

    foreach ($jv_loan as $row) {
        if ($row['jv_type'] == 'dr') {
            $total = ((@$loan[$row['gl_name']]['dr_total']) ? $loan[$row['gl_name']]['dr_total'] : 0) + $row['total'];
            $loan[$row['gl_name']]['dr_total'] = $total;
        } else {
            $total = ((@$loan[$row['gl_name']]['cr_total']) ? $loan[$row['gl_name']]['cr_total'] : 0) + $row['total'];
            $loan[$row['gl_name']]['cr_total'] = $total;
        }
    }

    foreach ($loan as $key => $value) {
        $loan[$key]['total'] = (@$value['receipt_total']-@$value['payment_total']) + (@$value['cr_total']-@$value['dr_total']);
    }

    $current_lib = array();
    foreach ($bank_parti_currentLib as $row) {
        if ($row['mode'] == 'Payment') {
            $total = ((@$current_lib[$row['gl_name']]['payment_total']) ? $current_lib[$row['gl_name']]['payment_total'] : 0) + $row['total'];
            $current_lib[$row['gl_name']]['payment_total'] = $total;
        } else {
            $total = ((@$current_lib[$row['gl_name']]['receipt_total']) ? $current_lib[$row['gl_name']]['receipt_total'] : 0) + $row['total'];
            $current_lib[$row['gl_name']]['receipt_total'] = $total;
        }
        // $current_lib[$row['gl_name']]['total'] = -@$current_lib[$row['gl_name']]['payment_total'] + @$current_lib[$row['gl_name']]['receipt_total'];
    }
    // $current_lib['Current Liabilities']['total']= @$current_lib['Current Liabilities']['payment_total']  - @$current_lib['Current Liabilities']['payment_total'];

    foreach ($jv_currentLib as $row) {
        if ($row['jv_type'] == 'dr') {
            $total = ((@$current_lib[$row['gl_name']]['dr_total']) ? $current_lib[$row['gl_name']]['dr_total'] : 0) + $row['total'];
            $current_lib[$row['gl_name']]['dr_total'] = $total;
        } else {
            $total = ((@$current_lib[$row['gl_name']]['cr_total']) ? $current_lib[$row['gl_name']]['cr_total'] : 0) + $row['total'];
            $current_lib[$row['gl_name']]['cr_total'] = $total;
        }
        // $current_lib[$row['gl_name']]['total'] = @$current_lib[$row['gl_name']]['cr_total'] - @$current_lib[$row['gl_name']]['dr_total'];
    }

    foreach ($current_lib as $key => $value) {
        $current_lib[$key]['total'] = (@$value['receipt_total']-@$value['payment_total']) + (@$value['cr_total']-@$value['dr_total']);
    }

    $current_lib['Sundry Creditors']['total'] = (($sale_purchase['pur_total_rate'] + $sale_purchase['purchase_Gray_total_rate'] + $sale_purchase['purchase_Finish_total_rate'])) - ($sale_purchase['Purret_total_rate'] + $sale_purchase['Retpurchase_Gray_total_rate'] + $sale_purchase['Retpurchase_Finish_total_rate']) + (@$current_lib['Sundry Creditors']['total']);

    $bank_parti_fixAset = bank_parti_master($start_date, $end_date, $gl = "Fixed Assets");
    $jv_fixAset = jv_master($start_date, $end_date, $gl = "Fixed Assets");
    $sg_fixAset = sale_General_master($start_date, $end_date, $gl = "Fixed Assets");
    $pg_fixAset = purchase_General_master($start_date, $end_date, $gl = "Fixed Assets");

    $fixed_asset = array();
    $ac_fixed_asset = array();

    foreach ($sg_fixAset as $row) {
        if ($row['type'] == 'return') {
            $total = ((@$fixed_asset[$row['gl_name']]['Sreturn_total']) ? $fixed_asset[$row['gl_name']]['Sreturn_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['Sreturn_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['Sret_total']) ? $ac_fixed_asset[$row['account_name']]['Sret_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['Sret_total'] = $total;
        } else {
            $total = ((@$fixed_asset[$row['gl_name']]['Sinvoice_total']) ? $fixed_asset[$row['gl_name']]['Sinvoice_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['Sinvoice_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['Sinv_total']) ? $ac_fixed_asset[$row['account_name']]['Sinv_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['Sinv_total'] = $total;
        }
        // $ac_fixed_asset[$row['account_name']]['total'] = @$ac_fixed_asset[$row['account_name']]['pay_total'] - @$ac_fixed_asset[$row['account_name']]['rec_total'];
    }

    foreach ($ac_fixed_asset as $key => $value) {
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['Sinv_total']-@$ac_fixed_asset[$key]['Sret_total']);
    }

    foreach ($pg_fixAset as $row) {
        if ($row['type'] == 'return') {
            $total = ((@$fixed_asset[$row['gl_name']]['Preturn_total']) ? $fixed_asset[$row['gl_name']]['Preturn_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['Preturn_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['Pret_total']) ? $ac_fixed_asset[$row['account_name']]['Pret_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['Pret_total'] = $total;
        } else {
            $total = ((@$fixed_asset[$row['gl_name']]['Pinvoice_total']) ? $fixed_asset[$row['gl_name']]['Pinvoice_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['Pinvoice_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['Pinv_total']) ? $ac_fixed_asset[$row['account_name']]['Pinv_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['Pinv_total'] = $total;
        }
        // $ac_fixed_asset[$row['account_name']]['total'] = @$ac_fixed_asset[$row['account_name']]['pay_total'] - @$ac_fixed_asset[$row['account_name']]['rec_total'];
    }

    foreach ($ac_fixed_asset as $key => $value) {
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['Pinv_total']-@$ac_fixed_asset[$key]['Pret_total'])+@$ac_fixed_asset[$key]['total'];
    }

    foreach ($bank_parti_fixAset as $row) {
        if ($row['mode'] == 'Payment') {
            $total = ((@$fixed_asset[$row['gl_name']]['payment_total']) ? $fixed_asset[$row['gl_name']]['payment_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['payment_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['pay_total']) ? $ac_fixed_asset[$row['account_name']]['pay_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['pay_total'] = $total;
        } else {
            $total = ((@$fixed_asset[$row['gl_name']]['receipt_total']) ? $fixed_asset[$row['gl_name']]['receipt_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['receipt_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['rec_total']) ? $ac_fixed_asset[$row['account_name']]['rec_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['rec_total'] = $total;
        }
        // $ac_fixed_asset[$row['account_name']]['total'] = @$ac_fixed_asset[$row['account_name']]['pay_total'] - @$ac_fixed_asset[$row['account_name']]['rec_total'];
    }

    // echo '<pre>';print_r($ac_fixed_asset);exit;
    foreach ($ac_fixed_asset as $key => $value) {
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['rec_total']-@$ac_fixed_asset[$key]['pay_total'])+@$ac_fixed_asset[$key]['total'];
    }

    foreach ($jv_fixAset as $row) {
        if ($row['jv_type'] == 'dr') {
            $total = ((@$fixed_asset[$row['gl_name']]['dr_total']) ? $fixed_asset[$row['gl_name']]['dr_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['dr_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['dr_total']) ? $ac_fixed_asset[$row['account_name']]['dr_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['dr_total'] = $total;
        } else {
            $total = ((@$fixed_asset[$row['gl_name']]['cr_total']) ? $fixed_asset[$row['gl_name']]['cr_total'] : 0) + $row['total'];
            $fixed_asset[$row['gl_name']]['cr_total'] = $total;

            $total = ((@$ac_fixed_asset[$row['account_name']]['cr_total']) ? $ac_fixed_asset[$row['account_name']]['cr_total'] : 0) + $row['total'];
            $ac_fixed_asset[$row['account_name']]['cr_total'] = $total;
        }
        // $ac_fixed_asset[$row['account_name']]['total'] = @$ac_fixed_asset[$row['account_name']]['total'] + @$ac_fixed_asset[$row['account_name']]['dr_total'] - @$ac_fixed_asset[$row['account_name']]['cr_total'];
    }
    // echo '<pre>';print_r($ac_fixed_asset);
    foreach ($ac_fixed_asset as $key => $value) {
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['dr_total']-@$ac_fixed_asset[$key]['cr_total']) + (@$ac_fixed_asset[$key]['total']);
    }
    foreach ($fixed_asset as $key => $value) {
        $fixed_asset[$key]['total'] = (@$value['receipt_total']-@$value['payment_total']) + (@$value['dr_total']-@$value['cr_total']) + (@$value['Sinvoice_total']-@$value['Sreturn_total']) + (@$value['Pinvoice_total']-@$value['Preturn_total']);
    }

    $bank_parti_CurAset = bank_parti_master($start_date, $end_date, $gl = "Current Assets");
    $bank_ac_CurAset = bank_ac_master($start_date, $end_date, $gl = "Current Assets");
    $jv_CurAset = jv_master($start_date, $end_date, $gl = "Current Assets");
    $current_asset = array();

    foreach ($bank_parti_CurAset as $row) {
        if ($row['mode'] == 'Payment') {
            $total = ((@$current_asset[$row['gl_name']]['payment_total']) ? $current_asset[$row['gl_name']]['payment_total'] : 0) + $row['total'];
            $current_asset[$row['gl_name']]['payment_total'] = $total;
        } else {
            $total = ((@$current_asset[$row['gl_name']]['receipt_total']) ? $current_asset[$row['gl_name']]['receipt_total'] : 0) + $row['total'];
            $current_asset[$row['gl_name']]['receipt_total'] = $total;
        }
    }

    foreach ($jv_CurAset as $row) {
        if ($row['jv_type'] == 'dr') {
            $total = ((@$current_asset[$row['gl_name']]['dr_total']) ? $current_asset[$row['gl_name']]['dr_total'] : 0) + $row['total'];
            $current_asset[$row['gl_name']]['dr_total'] = $total;
        } else {
            $total = ((@$current_asset[$row['gl_name']]['cr_total']) ? $current_asset[$row['gl_name']]['cr_total'] : 0) + $row['total'];
            $current_asset[$row['gl_name']]['cr_total'] = $total;
        }
    }

    foreach ($bank_ac_CurAset as $row) {
        if ($row['mode'] == 'Payment') {
            $total = ((@$current_asset[$row['gl_name']]['payment_total']) ? $current_asset[$row['gl_name']]['payment_total'] : 0) + $row['total'];
            $current_asset[$row['gl_name']]['payment_total'] = $total;
        } else {
            $total = ((@$current_asset[$row['gl_name']]['receipt_total']) ? $current_asset[$row['gl_name']]['receipt_total'] : 0) + $row['total'];
            $current_asset[$row['gl_name']]['receipt_total'] = $total;
        }
    }

    foreach ($current_asset as $key => $value) {
        $current_asset[$key]['total'] = @$value['payment_total']-@$value['receipt_total']+@$value['dr_total']-@$value['cr_total'];
    }

    $current_asset['Sundry Debtors']['total'] = ((@$sale_purchase['sale_total_rate'] + $sale_purchase['sale_Gray_total_rate'] + $sale_purchase['sale_Finish_total_rate']) - (@$sale_purchase['Saleret_total_rate'] + $sale_purchase['Retsale_Gray_total_rate'] + $sale_purchase['Retsale_Finish_total_rate'])) + (@$current_asset['Sundry Debtors']['total']);

    // Fixed Assets Final Array //
    $data = array();
    $data = array_merge($data, $fixed_asset);
    // $bank_parti_fixAset = array_merge($bank_parti_fixAset,$jv_fixAset);
    $data['Fixed Assets']['data'] = $ac_fixed_asset;
    // End Fixed Assets Final Array //

    // Capital Final Array //
    $data = array_merge($data, $capital);
    // $bank_parti_capital = array_merge($bank_parti_capital,$jv_capital);

    $data['Capital']['data'] = $ac_capital;

    // End Capital Final Array //

    // Current Asset Final Array //
    $total_ca = 0;
    foreach ($current_asset as $row) {
        $total_ca += $row['total'];
    }
    $data['Current Assets']['data'] = $current_asset;
    $data['Current Assets']['total'] = $total_ca;
    // End Current Asset Final Array //

    // Loan Final Array //
    $total_loan = 0;
    foreach ($loan as $row) {
        $total_loan += $row['total'];
    }
    $data['Loans']['data'] = $loan;
    $data['Loans']['total'] = $total_loan;
    // Loan Asset Final Array //

    // current_lib Final Array //
    $current_lib['Duties And Taxes'] = array(
        'total' => -(@$duties_taxes['sale_net']+@$duties_taxes['purchase_net']),
    );

    $total_cl = 0;
    foreach ($current_lib as $row) {
        $total_cl += $row['total'];
    }
    $data['Current Liabilities']['data'] = $current_lib;
    $data['Current Liabilities']['total'] = $total_cl;
    // Loan Asset Final Array //

    $data['from'] = $start_date;
    $data['to'] = $end_date;

    // echo '<pre>';print_r($data);exit;
    return $data;

}

function jv_master($start_date = '', $end_date = '', $gl1)
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

    $builder = $db->table('gl_group');
    $builder->select('id');
    $builder->where(array('parent' => $gl_id['id']));
    $query = $builder->get();
    $ids = $query->getResultArray();

    $perent[] = array('id' => $gl_id['id']);
    $ids = array_merge($ids, $perent);

    $result = array();
    foreach ($ids as $row) {

        $builder = $db->table('gl_group gl');
        $builder->select('jv.dr_cr as jv_type,jv.amount as total,gl.name as gl_name, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('jv_particular jv', 'jv.particular = ac.id');
        $builder->where(array('gl.id' => $row['id']));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('jv.is_delete' => '0'));
        $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
        $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
        $query = $builder->get();
        $get_result = $query->getResultArray();

        if (!empty($get_result)) {
            $result = array_merge($result, $get_result);
        }

    }

    return $result;
}

function bank_parti_master($start_date = '', $end_date = '', $gl1)
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

    $builder = $db->table('gl_group ');
    $builder->select('id');
    $builder->where(array('parent' => $gl_id['id']));
    $query = $builder->get();
    $ids = $query->getResultArray();

    $perent[] = array('id' => $gl_id['id']);
    $ids = array_merge($ids, $perent);

    $result = array();
    foreach ($ids as $row) {

        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,bt.amount as total,bt.mode as mode,bt.payment_type as pay_type, ac.name as account_name');
        $builder->join('account ac', 'gl.id = ac.gl_group');
        $builder->join('bank_tras bt', 'bt.particular = ac.id');
        $builder->where(array('gl.id' => $row['id']));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('bt.is_delete' => '0'));
        $builder->where(array('DATE(bt.created_at)  >= ' => $start_date));
        $builder->where(array('DATE(bt.created_at)  <= ' => $end_date));
        $query = $builder->get();

        $get_result = $query->getResultArray();

        if (!empty($get_result)) {
            $result = array_merge($result, $get_result);
        }

    }
    // echo $db->getLastQuery();
    // echo '<pre>';print_r($result);exit;
    return $result;
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

function bank_ac_master($start_date = '', $end_date = '', $gl1)
{
    // print_r($gl1);exit;
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

    $builder = $db->table('gl_group ');
    $builder->select('id');
    $builder->where(array('parent' => $gl_id['id']));
    $query = $builder->get();
    $ids = $query->getResultArray();

    $perent[] = array('id' => $gl_id['id']);
    $ids = array_merge($ids, $perent);

    $result = array();
    foreach ($ids as $row) {
        $builder = $db->table('gl_group gl');
        $builder->select('gl.name as gl_name,bt.amount as total,bt.mode as mode,bt.payment_type as pay_type, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('bank_tras bt', 'bt.account = ac.id');
        $builder->where(array('gl.id' => $row['id']));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('DATE(bt.created_at)  >= ' => $start_date));
        $builder->where(array('DATE(bt.created_at)  <= ' => $end_date));
        $query = $builder->get();
        $get_result = $query->getResultArray();

        if (!empty($get_result)) {
            $result = array_merge($result, $get_result);
        }

    }
    // echo '<pre>';print_r($ids);exit;
    return $result;
}

function duties_taxes($start_date = '', $end_date = '')
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

    $builder = $db->table('sales_invoice');
    $builder->select('SUM(tot_igst) as total_tax ');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $sales_invoice = $query->getRowArray();

    $builder = $db->table('sales_return');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return = $query->getRowArray();

    $builder = $db->table('sales_ACinvoice');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('v_type' => 'general'));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $sales_general = $query->getRowArray();

    $builder = $db->table('sales_ACinvoice');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('v_type' => 'return'));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $Retsales_general = $query->getRowArray();

    $builder = $db->table('purchase_invoice');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_invoice = $query->getRowArray();

    $builder = $db->table('purchase_return');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return = $query->getRowArray();

    $builder = $db->table('purchase_general');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('v_type' => 'general'));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_general = $query->getRowArray();

    $builder = $db->table('purchase_general');
    $builder->select('SUM(tot_igst) as total_tax');
    $builder->where(array('is_delete' => 0));
    $builder->where(array('v_type' => 'return'));
    $builder->where(array('DATE(created_at)  >= ' => $start_date));
    $builder->where(array('DATE(created_at)  <= ' => $end_date));
    $query = $builder->get();
    $Retpurchase_general = $query->getRowArray();

    $taxes = array(
        'sales_invoice' => $sales_invoice['total_tax'],
        'sales_return' => $sales_return['total_tax'],
        'sales_general' => $sales_general['total_tax'],
        'Retsales_general' => $Retsales_general['total_tax'],
        'purchase_invoice' => $purchase_invoice['total_tax'],
        'purchase_return' => $purchase_return['total_tax'],
        'purchase_general' => $purchase_general['total_tax'],
        'Retpurchase_general' => $Retpurchase_general['total_tax'],
        'sale_net' => ($sales_invoice['total_tax'] - $sales_return['total_tax']) + ($sales_general['total_tax'] - $Retsales_general['total_tax']),
        'purchase_net' => ($purchase_invoice['total_tax'] - $purchase_return['total_tax']) + ($purchase_general['total_tax'] - $Retpurchase_general['total_tax']),
    );
    $taxes['taxes'] = 0;
    foreach ($taxes as $key => $value) {
        if ($key != 'sales_return' && $key != 'purchase_return') {
            $taxes['taxes'] += $value;
        }
    }
    // echo '<pre>';print_r($taxes);exit;
    return $taxes;

}

function sale_General_master($start_date = '', $end_date = '', $gl1)
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

    $builder = $db->table('gl_group');
    $builder->select('id');
    $builder->where(array('parent' => $gl_id['id']));
    $query = $builder->get();
    $ids = $query->getResultArray();

    $perent[] = array('id' => $gl_id['id']);
    $ids = array_merge($ids, $perent);

    $result = array();
    foreach ($ids as $row) {

        $builder = $db->table('gl_group gl');
        $builder->select('sa.type as type,sa.amount as total,gl.name as gl_name, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('sales_ACparticu sa', 'sa.account = ac.id');
        $builder->join('sales_ACinvoice si', 'si.id = sa.parent_id');
        $builder->where(array('gl.id' => $row['id']));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('sa.is_delete' => '0'));
        $builder->where(array('si.is_delete' => '0'));
        $builder->where(array('DATE(sa.created_at)  >= ' => $start_date));
        $builder->where(array('DATE(sa.created_at)  <= ' => $end_date));
        $query = $builder->get();
        $get_result = $query->getResultArray();

        if (!empty($get_result)) {
            $result = array_merge($result, $get_result);
        }

    }

    return $result;
}

function purchase_General_master($start_date = '', $end_date = '', $gl1)
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

    $builder = $db->table('gl_group');
    $builder->select('id');
    $builder->where(array('parent' => $gl_id['id']));
    $query = $builder->get();
    $ids = $query->getResultArray();

    $perent[] = array('id' => $gl_id['id']);
    $ids = array_merge($ids, $perent);

    $result = array();
    foreach ($ids as $row) {

        $builder = $db->table('gl_group gl');
        $builder->select('pg.type as type,pg.amount as total,gl.name as gl_name, ac.name as account_name');
        $builder->join('account ac', 'gl.id =ac.gl_group');
        $builder->join('purchase_particu pg', 'pg.account = ac.id');
        $builder->join('purchase_general pn', 'pn.id = pg.id');
        $builder->where(array('gl.id' => $row['id']));
        $builder->where(array('ac.is_delete' => '0'));
        $builder->where(array('pg.is_delete' => '0'));
        $builder->where(array('DATE(pg.created_at)  >= ' => $start_date));
        $builder->where(array('DATE(pg.created_at)  <= ' => $end_date));
        $query = $builder->get();
        $get_result = $query->getResultArray();

        if (!empty($get_result)) {
            $result = array_merge($result, $get_result);
        }

    }
    // echo '<pre>'; print_r($get_result);exit;
    return $result;
}

// public function get_array_table($table = '', $where = array(), $select = '') {

//     $db = $this->db;
//     $db->setDatabase(session('DataSource'));
//     $builder = $db->table($table);
//     if ($select == '')
//         $select = '*';
//     $query = $builder->select($select)->where($where)->get();
//     // echo $db->getLastQuery($query);exit;
//     $getdata = $query->getResultArray();
//     $result = array();
//     if (!empty($getdata)) {
//         $result = $getdata;
//     }
//     return $result;
// }

function get_reconsilation_data($account, $start_date = '', $end_date = '')
{

    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

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

    $dt = new DateTime($start_date);
    if ($dt->format('m') <= '03') {
        $year = date('Y') - 1;
        $newdate = $year . '-04-01';
    } else {
        $year = $dt->format('Y');
        $newdate = $year . '-04-01';
    }

    $builder = $db->table('bank_tras bt');
    $builder->select('bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
    $builder->join('account ac', 'ac.id = bt.particular');
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($newdate)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $builder->where(array('bt.payment_type' => 'bank', 'bt.is_delete' => 0));
    $builder->where(array('bt.recons_date' => ''));
    $builder->where('bt.account', $account);
    $builder->orderBy('bt.receipt_date', 'ASC');
    $query = $builder->get();
    $getresult = $query->getResultArray();

    $builder = $db->table('bank_tras bt');
    $builder->select('ct.id as ct_id,bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,ct.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
    $builder->join('contra_trans ct', 'ct.parent_id = bt.id');
    $builder->join('account ac', 'ac.id = ct.account');
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($newdate)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $builder->where(array('bt.payment_type' => 'contra', 'bt.is_delete' => 0));
    $builder->where(array('ct.recons_date' => ''));
    $builder->where('ct.account', $account);
    $builder->orderBy('bt.receipt_date', 'ASC');
    $query = $builder->get();
    $getresult1 = $query->getResultArray();

    // echo $db->getLastQuery();exit;

    // $builder=$db->table('bank_tras bt');
    // $builder->select('bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,ct.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
    // $builder->join('account ac','ac.id = bt.particular');
    // $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($newdate)));
    // $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    // $builder->where(array('bt.payment_type' => 'contra','bt.is_delete' => 0));
    // $builder->where(array('bt.recons_date' => ''));
    // $builder->where('bt.particular',$account);
    // $builder->orderBy('bt.receipt_date','ASC');
    // $query=$builder->get();
    // $getresult2 = $query->getResultArray();

    // echo $db->getLastQuery();
    // echo '<pre>';print_r($getresult1);exit;

    $merge_array = array_merge($getresult, $getresult1);

    usort($merge_array, 'date_compare');

    $getdata['bank'] = $merge_array;

    $builder = $db->table('account');
    $builder->select('name');
    $builder->where('id', $account);
    $query = $builder->get();
    $res = $query->getRow();

    $getdata['account_name'] = @$res->name;
    $getdata['account_id'] = @$account;

    $bankcredit_total = 0;
    $bankdebit_total = 0;
    
    foreach ($getdata['bank'] as $row) {
        if ($row['mode'] == 'Receipt') {
            $bankdebit_total = $bankdebit_total + $row["amount"];
        } else {
            $bankcredit_total = $bankcredit_total + $row["amount"];
        }
    }

    $getdata['total']['bankdebit_total'] = $bankdebit_total;
    $getdata['total']['bankcredit_total'] = $bankcredit_total;

    $getdata['from'] = $start_date;
    $getdata['to'] = $end_date;

    return $getdata;
}

function get_unreconsilation_data($account, $start_date = '', $end_date = '')
{

    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

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

    $dt = new DateTime($start_date);

    if ($dt->format('m') <= '03') {
        $year = date('Y') - 1;
        $newdate = $year . '-04-01';
    } else {
        $year = $dt->format('Y');
        $newdate = $year . '-04-01';
    }

    $builder = $db->table('bank_tras bt');
    $builder->select('bt.id,bt.check_no,bt.cash_type,bt.check_date,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
    $builder->join('account ac', 'ac.id = bt.particular');
    $builder->where(array('bt.payment_type' => 'bank', 'bt.is_delete' => 0));
    $builder->where(array('bt.recons_date !=' => ''));
    $builder->where('bt.account', $account);
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $builder->orderBy('bt.receipt_date', 'ASC');
    $query = $builder->get();
    $getresult = $query->getResultArray();

    $builder = $db->table('bank_tras bt');
    $builder->select('ct.id as ct_id,bt.id,bt.cash_type,bt.check_no,bt.check_date,bt.account,bt.payment_type,ct.mode,bt.receipt_date as date,bt.amount,ct.recons_date,ac.name as account_name,acc.name as bank_account_name,pr.name as bank_particular_name');
    $builder->join('contra_trans ct', 'ct.parent_id = bt.id');
    $builder->join('account ac', 'ac.id = ct.account');
    $builder->join('account acc', 'acc.id = bt.account');
    $builder->join('account pr', 'pr.id = bt.particular');
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $builder->where(array('bt.payment_type' => 'contra', 'bt.is_delete' => 0));
    $builder->where(array('ct.recons_date !=' => ''));
    $builder->where('ct.account', $account);
    $builder->orderBy('bt.receipt_date', 'ASC');
    $query = $builder->get();
    $getresult1 = $query->getResultArray();
    

    $getresult2 = array();
    foreach($getresult1 as $row){
        if($row['cash_type'] == 'Fund Transfer'){

            if($row['account_name'] == $row['bank_account_name']){
                $row['account_name'] = $row['bank_particular_name'];
            }else{
                $row['account_name'] = $row['bank_account_name'];
            }
        }
        $getresult2[] = $row;
    }

    $merge_arr = array_merge($getresult, $getresult2);
    
    usort($merge_arr, 'date_compare');

    $getdata['bank'] = $merge_arr;

    $builder = $db->table('account');
    $builder->select('name');
    $builder->where('id', $account);
    $query = $builder->get();
    $res = $query->getRow();

    $getdata['account_name'] = @$res->name;
    $getdata['account_id'] = @$account;

    $bankcredit_total = 0;
    $bankdebit_total = 0;

    foreach ($getdata['bank'] as $row) {
        if ($row['mode'] == 'Receipt') {
            $bankdebit_total = $bankdebit_total + $row["amount"];
        } else {
            $bankcredit_total = $bankcredit_total + $row["amount"];
        }
    }

    $getdata['total']['bankdebit_total'] = $bankdebit_total;
    $getdata['total']['bankcredit_total'] = $bankcredit_total;

    $getdata['from'] = $start_date;
    $getdata['to'] = $end_date;

    return $getdata;
}

function get_filter_view($type = '', $mode = '', $account_id = '', $start_date = '', $end_date = '')
{

    // print_r($type);exit;
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
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
    if (!empty($start_date) && !empty($end_date)) {
        $start_date = date("Y-m-d", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($end_date));
    }
    //print_r($start_date);exit;
    $getdata = array();

    if ($type == "sales") {
        // echo "kjchkdf,d";exit;

        $builder = $db->table('sales_invoice si');
        $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
        }

        $query = $builder->get();
        $getdata['sales_invoice'] = $query->getResultArray();
        $sinvoice_total = 0;
        foreach ($getdata['sales_invoice'] as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];
        }

        $getdata['total']['salesinvoice_total'] = $sinvoice_total;

        $builder = $db->table('sales_ACinvoice sac');
        $builder->select('sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sac.party_account');
        $builder->where(array('sac.is_delete' => 0, 'sac.v_type' => 'general'));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(sac.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(sac.invoice_date)  <= ' => $end_date));
        }

        $query2 = $builder->get();
        $getdata['salesinvoice_general'] = $query2->getResultArray();
        $sginvoive_total = 0;
        foreach ($getdata['salesinvoice_general'] as $row) {
            $sginvoive_total = $sginvoive_total + $row["net_amount"];
        }

        $getdata['total']['salesinvoice_general_total'] = $sginvoive_total;
        // $ginvoive_total=0;
        // $greturn_total=0;
        // foreach($getdata['sales_general'] as $row)
        // {
        //     if($row['v_type']=='general')
        //     {
        //         $ginvoive_total= $ginvoive_total + $row["net_amount"];
        //     }
        //     else
        //     {
        //         $greturn_total= $greturn_total + $row["net_amount"];
        //     }
        // }

        // $getdata['total']['sgeneralinvoive_total']=$ginvoive_total;
        // $getdata['total']['sgeneralreturn_total']=$greturn_total;
        // echo '<pre>';print_r($getdata);exit;
    } else if ($type == 'purchase') {

        $builder = $db->table('purchase_invoice pi');
        //$builder->select('id,account,invoice_date,net_amount');
        $builder->select('pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pi.account');
        $builder->where(array('pi.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
        }

        $query6 = $builder->get();
        $getdata['purchase_invoice'] = $query6->getResultArray();
        $pinvoice_total = 0;
        foreach ($getdata['purchase_invoice'] as $row) {
            $pinvoice_total = $pinvoice_total + $row["net_amount"];
        }

        $getdata['total']['purchaseinvoice_total'] = $pinvoice_total;
        
        $builder = $db->table('purchase_general pg');
        // $builder->select('id,party_account,v_type,doc_date,net_amount');
        $builder->select('pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pg.party_account');
        $builder->where(array('pg.is_delete' => 0, 'pg.v_type' => 'general'));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
        }

        $query8 = $builder->get();
        $getdata['purchaseinvoice_general'] = $query8->getResultArray();
        //$getdata['purchase_general']['type']="purchase_general";
        $gpinvoive_total = 0;
        foreach ($getdata['purchaseinvoice_general'] as $row) {
            $gpinvoive_total = $gpinvoive_total + $row["net_amount"];
        }

        $getdata['total']['purchaseinvoive_general_total'] = $gpinvoive_total;
 
    } else if ($type == 'payment') {
        //print_r("jkvhkj");exit;
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
        //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.account');
        $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            //print_r("nbvnbvn");exit;
            $builder->where(array('bt.payment_type' => $mode));
        }

        $query5 = $builder->get();
        $getdata['payment'] = $query5->getResultArray();
        //$gsinvoive_total=0;
        $payment_total = 0;
        foreach ($getdata['payment'] as $row) {
            $payment_total = $payment_total + $row["amount"];
        }

        // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
        $getdata['total']['payment_total'] = $payment_total;
    } else if ($type == 'receipt') {
        //print_r("jkvhkj");exit;
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
        //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.account');
        $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('bt.payment_type' => $mode));
        }

        $query5 = $builder->get();
        $getdata['receipt'] = $query5->getResultArray();
        //$gsinvoive_total=0;
        $receipt_total = 0;
        foreach ($getdata['receipt'] as $row) {
            $receipt_total = $receipt_total + $row["amount"];
        }

        // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
        $getdata['total']['receipt_total'] = $receipt_total;
    } else if ($type == 'cash') {
        //print_r("jkvhkj");exit;
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,ac.name as account_name');
        //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.account');
        $builder->where(array('bt.payment_type' => 'cash', 'bt.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('bt.mode' => $mode));
        }

        $query5 = $builder->get();
        $getdata['cash'] = $query5->getResultArray();
        //$gsinvoive_total=0;
        $cashcredit_total = 0;
        $cashdebit_total = 0;
        foreach ($getdata['cash'] as $row) {
            if ($row['mode'] == 'Receipt') {
                $cashcredit_total = $cashcredit_total + $row["amount"];
            } else {
                $cashdebit_total = $cashdebit_total + $row["amount"];
            }
        }

        // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
        $getdata['total']['cashcredit_total'] = $cashcredit_total;
        $getdata['total']['cashdebit_total'] = $cashdebit_total;
    } else if ($type == 'bank') {
        // print_r("jkvhkj");exit;
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
        // $builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.account');
        $builder->where(array('bt.payment_type' => 'bank', 'bt.is_delete' => 0));

        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        }

        if (!empty($mode) && $mode != 'Reconsilation') {
            $builder->where(array('bt.mode' => $mode));
        }

        $query5 = $builder->get();
        $getdata['bank'] = $query5->getResultArray();
        // echo '<pre>';print_r($getdata);exit;
        if ($mode == 'Reconsilation') {

            $dt = new DateTime($start_date);
            $newdate = $dt->modify('-1 year')->format('Y-m-d');

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.payment_type' => 'bank', 'bt.is_delete' => 0));
            $builder->where(array('bt.recons_date' => '0000-00-00'));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $newdate));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            $que5 = $builder->get();
            $getresult = $que5->getResultArray();

            $getdata['bank'] = array_merge($getresult, $getdata['bank']);

        }

        $bankcredit_total = 0;
        $bankdebit_total = 0;

        foreach ($getdata['bank'] as $row) {
            if ($row['mode'] == 'Receipt') {
                $bankcredit_total = $bankcredit_total + $row["amount"];
            } else {
                $bankdebit_total = $bankdebit_total + $row["amount"];
            }
        }
        $getdata['total']['bankcredit_total'] = $bankcredit_total;
        $getdata['total']['bankdebit_total'] = $bankdebit_total;
    } else if ($type == 'debitnote') {
        $builder = $db->table('purchase_return pr');
        // //$builder->select('account,return_date,net_amount');
        $builder->select('pr.id,pr.account,pr.return_date as date,pr.total_amount,pr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pr.account');
        $builder->where(array('pr.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(pr.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pr.return_date)  <= ' => $end_date));
        }
        $query7 = $builder->get();
        $getdata['purchase_return'] = $query7->getResultArray();
        $preturn_total = 0;
        foreach ($getdata['purchase_return'] as $row) {
            $preturn_total = $preturn_total + $row["net_amount"];
        }
        $getdata['total']['purchasreturn_total'] = $preturn_total;
        $builder = $db->table('purchase_general pg');
        $builder->select('pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pg.party_account');
        $builder->where(array('pg.v_type' => 'return', 'pg.is_delete' => 0));

        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
        }

        $query8 = $builder->get();
        $getdata['purchasegeneral_return'] = $query8->getResultArray();
        $gpreturn_total = 0;
        foreach ($getdata['purchasegeneral_return'] as $row) {
            $gpreturn_total = $gpreturn_total + $row["net_amount"];
        }

        // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
        $getdata['total']['purchasereturn_general_total'] = $gpreturn_total;
    } else if ($type == 'creditnote') {

        $builder = $db->table('sales_return sr');
        $builder->select('sr.id,sr.account,sr.return_date as date,sr.total,sr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sr.account');
        $builder->where(array('sr.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(sr.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(sr.return_date)  <= ' => $end_date));
        }

        $query1 = $builder->get();
        $getdata['sales_return'] = $query1->getResultArray();
        $sreturn_total = 0;
        foreach ($getdata['sales_return'] as $row) {
            $sreturn_total = $sreturn_total + $row["net_amount"];
        }

        $getdata['total']['salesreturn_total'] = $sreturn_total;

        $builder = $db->table('sales_ACinvoice sac');
        $builder->select('sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name');
        //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sac.party_account');
        $builder->where(array('sac.v_type' => 'return', 'sac.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(sac.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(sac.invoice_date)  <= ' => $end_date));
        }

        $query5 = $builder->get();
        $getdata['salesgeneral_return'] = $query5->getResultArray();
        //$gsinvoive_total=0;
        $gsreturn_total = 0;
        foreach ($getdata['salesgeneral_return'] as $row) {
            $gsreturn_total = $gsreturn_total + $row["net_amount"];
        }

        // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
        $getdata['total']['salesreturn_general_total'] = $gsreturn_total;
    } else if ($type == 'journal') {

        $builder = $db->table('jv_particular jv');
        $builder->select('jv.id,jv.particular,jv.dr_cr,jv.date as date,jv.amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = jv.particular');
        $builder->where(array('jv.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(jv.date)  >= ' => $start_date));
            $builder->where(array('DATE(jv.date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('jv.dr_cr' => $mode));
        }

        $query1 = $builder->get();
        $getdata['journal'] = $query1->getResultArray();
        $journalcredit_total = 0;
        $journaldebit_total = 0;
        foreach ($getdata['journal'] as $row) {
            if ($row['dr_cr'] == 'cr') {
                $journalcredit_total = $journalcredit_total + $row["amount"];
            } else {
                $journaldebit_total = $journaldebit_total + $row["amount"];
            }
        }
        $getdata['total']['journalcredit_total'] = $journalcredit_total;
        $getdata['total']['journaldebit_total'] = $journaldebit_total;

    } else if ($type == 'ledger') {
        if (!empty($account_id)) {
            $builder = $db->table('sales_invoice si');
            $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where(array('si.is_delete' => 0, 'si.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
            }
            $query = $builder->get();
            $ledger['sales_invoice'] = $query->getResultArray();
            $sinvoice_total = 0;
            foreach ($ledger['sales_invoice'] as $row) {
                $sinvoice_total = $sinvoice_total + $row["net_amount"];
            }

            $ledger['total']['salesinvoice_total'] = $sinvoice_total;

            $builder = $db->table('sales_ACinvoice sac');
            $builder->select('sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name');
            //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = sac.party_account');
            $builder->where(array('sac.is_delete' => 0, 'sac.v_type' => 'general', 'sac.party_account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(sac.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(sac.invoice_date)  <= ' => $end_date));
            }
            $query2 = $builder->get();
            $ledger['salesinvoice_general'] = $query2->getResultArray();
            $sginvoive_total = 0;
            foreach ($ledger['salesinvoice_general'] as $row) {
                $sginvoive_total = $sginvoive_total + $row["net_amount"];
            }
            $ledger['total']['salesinvoice_general_total'] = $sginvoive_total;

            $builder = $db->table('purchase_invoice pi');
            //$builder->select('id,account,invoice_date,net_amount');
            $builder->select('pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = pi.account');
            $builder->where(array('pi.is_delete' => 0, 'pi.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
            }

            $query6 = $builder->get();
            $ledger['purchase_invoice'] = $query6->getResultArray();
            $pinvoice_total = 0;
            foreach ($ledger['purchase_invoice'] as $row) {
                $pinvoice_total = $pinvoice_total + $row["net_amount"];
            }

            $ledger['total']['purchaseinvoice_total'] = $pinvoice_total;

            $builder = $db->table('purchase_general pg');
            // $builder->select('id,party_account,v_type,doc_date,net_amount');
            $builder->select('pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.is_delete' => 0, 'pg.v_type' => 'general', 'pg.party_account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
                $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            }

            $query8 = $builder->get();
            $ledger['purchaseinvoice_general'] = $query8->getResultArray();
            //$getdata['purchase_general']['type']="purchase_general";
            $gpinvoive_total = 0;
            foreach ($ledger['purchaseinvoice_general'] as $row) {
                $gpinvoive_total = $gpinvoive_total + $row["net_amount"];
            }

            $ledger['total']['purchaseinvoive_general_total'] = $gpinvoive_total;

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0, 'bt.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
                $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            }
            if (!empty($mode)) {
                //print_r("nbvnbvn");exit;
                $builder->where(array('bt.payment_type' => $mode));
            }

            $query5 = $builder->get();
            $ledger['payment'] = $query5->getResultArray();
            //$gsinvoive_total=0;
            $payment_total = 0;
            foreach ($ledger['payment'] as $row) {
                $payment_total = $payment_total + $row["amount"];
            }

            // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
            $ledger['total']['payment_total'] = $payment_total;

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0, 'bt.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
                $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            }
            if (!empty($mode)) {
                $builder->where(array('bt.payment_type' => $mode));
            }

            $query5 = $builder->get();
            $ledger['receipt'] = $query5->getResultArray();
            //$gsinvoive_total=0;
            $receipt_total = 0;
            foreach ($ledger['receipt'] as $row) {
                $receipt_total = $receipt_total + $row["amount"];
            }

            // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
            $ledger['total']['receipt_total'] = $receipt_total;

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,ac.name as account_name');
            //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.payment_type' => 'cash', 'bt.is_delete' => 0, 'bt.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
                $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            }
            if (!empty($mode)) {
                $builder->where(array('bt.mode' => $mode));
            }

            $query5 = $builder->get();
            $ledger['cash'] = $query5->getResultArray();
            //$gsinvoive_total=0;
            $cashcredit_total = 0;
            $cashdebit_total = 0;
            foreach ($ledger['cash'] as $row) {
                if ($row['mode'] == 'Receipt') {
                    $cashcredit_total = $cashcredit_total + $row["amount"];
                } else {
                    $cashdebit_total = $cashdebit_total + $row["amount"];
                }
            }

            // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
            $ledger['total']['cashcredit_total'] = $cashcredit_total;
            $ledger['total']['cashdebit_total'] = $cashdebit_total;

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,ac.name as account_name');
            //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.payment_type' => 'bank', 'bt.is_delete' => 0, 'bt.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
                $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
            }
            if (!empty($mode)) {
                $builder->where(array('bt.mode' => $mode));
            }

            $query5 = $builder->get();
            $ledger['bank'] = $query5->getResultArray();
            //$gsinvoive_total=0;
            $bankcredit_total = 0;
            $bankdebit_total = 0;
            foreach ($ledger['bank'] as $row) {
                if ($row['mode'] == 'Receipt') {
                    $bankcredit_total = $bankcredit_total + $row["amount"];
                } else {
                    $bankdebit_total = $bankdebit_total + $row["amount"];
                }
            }

            $ledger['total']['bankcredit_total'] = $bankcredit_total;
            $ledger['total']['bankdebit_total'] = $bankdebit_total;

            $builder = $db->table('purchase_return pr');
            // //$builder->select('account,return_date,net_amount');
            $builder->select('pr.id,pr.account,pr.return_date as date,pr.total_amount,pr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = pr.account');
            $builder->where(array('pr.is_delete' => 0, 'pr.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(pr.return_date)  >= ' => $start_date));
                $builder->where(array('DATE(pr.return_date)  <= ' => $end_date));
            }

            $query7 = $builder->get();
            $ledger['purchase_return'] = $query7->getResultArray();
            $preturn_total = 0;
            foreach ($ledger['purchase_return'] as $row) {
                $preturn_total = $preturn_total + $row["net_amount"];
            }

            $ledger['total']['purchasreturn_total'] = $preturn_total;

            $builder = $db->table('purchase_general pg');
            $builder->select('pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.v_type' => 'return', 'pg.is_delete' => 0, 'pg.party_account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
                $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
            }

            $query8 = $builder->get();
            $ledger['purchasegeneral_return'] = $query8->getResultArray();
            $gpreturn_total = 0;
            foreach ($ledger['purchasegeneral_return'] as $row) {
                $gpreturn_total = $gpreturn_total + $row["net_amount"];
            }

            // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
            $ledger['total']['purchasereturn_general_total'] = $gpreturn_total;

            $builder = $db->table('sales_return sr');
            $builder->select('sr.id,sr.account,sr.return_date as date,sr.total,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = sr.account');
            $builder->where(array('sr.is_delete' => 0, 'sr.account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(sr.return_date)  >= ' => $start_date));
                $builder->where(array('DATE(sr.return_date)  <= ' => $end_date));
            }

            $query1 = $builder->get();
            $ledger['sales_return'] = $query1->getResultArray();
            $sreturn_total = 0;
            foreach ($ledger['sales_return'] as $row) {
                $sreturn_total = $sreturn_total + $row["net_amount"];
            }

            $ledger['total']['salesreturn_total'] = $sreturn_total;

            $builder = $db->table('sales_ACinvoice sac');
            $builder->select('sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name');
            //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = sac.party_account');
            $builder->where(array('sac.v_type' => 'return', 'sac.is_delete' => 0, 'sac.party_account' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(sac.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(sac.invoice_date)  <= ' => $end_date));
            }

            $query5 = $builder->get();
            $ledger['salesgeneral_return'] = $query5->getResultArray();
            //$gsinvoive_total=0;
            $gsreturn_total = 0;
            foreach ($ledger['salesgeneral_return'] as $row) {
                $gsreturn_total = $gsreturn_total + $row["net_amount"];
            }

            // $getdata['total']['sgeneralinvoive_total']=$gsinvoive_total;
            $ledger['total']['salesreturn_general_total'] = $gsreturn_total;

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,jv.date as date,jv.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.is_delete' => 0, 'jv.particular' => $account_id));
            if (!empty($start_date) and !empty($end_date)) {
                $builder->where(array('DATE(jv.date)  >= ' => $start_date));
                $builder->where(array('DATE(jv.date)  <= ' => $end_date));
            }
            if (!empty($mode)) {
                $builder->where(array('jv.dr_cr' => $mode));
            }

            $query1 = $builder->get();
            $ledger['journal'] = $query1->getResultArray();
            $journalcredit_total = 0;
            $journaldebit_total = 0;
            foreach ($ledger['journal'] as $row) {
                if ($row['dr_cr'] == 'cr') {
                    $journalcredit_total = $journalcredit_total + $row["amount"];
                } else {
                    $journaldebit_total = $journaldebit_total + $row["amount"];
                }
            }
            $ledger['total']['journalcredit_total'] = $journalcredit_total;
            $ledger['total']['journaldebit_total'] = $journaldebit_total;

            $getdata['ledger'] = $ledger;

            // /echo "no";exit;
        }
    }

    $getdata['start_date'] = $start_date;
    $getdata['end_date'] = $end_date;

    //echo '<pre>';print_r($getdata);exit;

    return $getdata;

}

function getmonth_total($type, $start_date, $end_date)
{

    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    // if(!empty($start_date) && !empty($end_date))
    // {
    // $start_date= date("Y-m-d", strtotime($start_date));
    // $end_date= date("Y-m-d", strtotime($end_date));
    // }
    // print_r($start_date);echo "<br>";
    // print_r($end_date);exit;
    $getdata = array();
    if ($type == "sales") {

        $builder = $db->table('sales_invoice');
        $builder->select('id,SUM(net_amount) as salesinvoice_monthtotal');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query = $builder->get();
        $result = $query->getResultArray();
        $getdata['total']['salesinvoice_monthtotal'] = $result[0];
        // echo '<pre>';print_r($result);exit;

        $builder = $db->table('sales_ACinvoice');
        $builder->select('id,SUM(net_amount) as salesgeneralinvoice_monthtotal,v_type');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'general', 'is_delete' => 0));

        $query1 = $builder->get();
        $result1 = $query1->getResultArray();
        $getdata['total']['salesgeneralinvoice_monthtotal'] = $result1[0];

        // $builder=$db->table('sales_acinvoice');
        // $builder->select('id,SUM(net_amount) as salesgeneralreturn_monthtotal,v_type');
        // $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        // $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        // $builder->where(array('v_type' => 'return'));
        // $query3=$builder->get();
        // $result3=$query3->getResultArray();
        // $getdata['total']['salesgeneralreturn_monthtotal']= $result3[0];

    } else if ($type == "purchase") {

        $builder = $db->table('purchase_invoice');
        $builder->select('id,SUM(net_amount) as purchaseinvoice_monthtotal');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query2 = $builder->get();
        $result2 = $query2->getResultArray();
        $getdata['total']['purchaseinvoice_monthtotal'] = $result2[0];

        $builder = $db->table('purchase_general');
        $builder->select('id,SUM(net_amount) as purchasegeneralinvoice_monthtotal,v_type');
        $builder->where(array('DATE(doc_date)  >= ' => $start_date));
        $builder->where(array('DATE(doc_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'general', 'is_delete' => 0));
        $query3 = $builder->get();
        $result3 = $query3->getResultArray();
        $getdata['total']['purchasegeneralinvoice_monthtotal'] = $result3[0];

        // $builder=$db->table('purchase_general');
        // $builder->select('id,SUM(net_amount) as purchasegeneralreturn_monthtotal,v_type');
        // $builder->where(array('DATE(doc_date)  >= ' => $start_date));
        // $builder->where(array('DATE(doc_date)  <= ' => $end_date));
        // $builder->where(array('v_type' => 'return'));
        // $query3=$builder->get();
        // $result3=$query3->getResultArray();
        // $getdata['total']['purchasegeneralreturn_monthtotal']= $result3[0];

    } else if ($type == "creditnote") {
        $builder = $db->table('sales_return');
        $builder->select('id,SUM(net_amount) as salesreturn_monthtotal');
        $builder->where(array('DATE(return_date)  >= ' => $start_date));
        $builder->where(array('DATE(return_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query4 = $builder->get();
        $result4 = $query4->getResultArray();
        $getdata['total']['salesreturn_monthtotal'] = $result4[0];

        $builder = $db->table('sales_ACinvoice');
        $builder->select('id,SUM(net_amount) as salesreturn_general_monthtotal,v_type');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'return', 'is_delete' => 0));
        $query5 = $builder->get();
        $result5 = $query5->getResultArray();
        $getdata['total']['salesreturn_general_monthtotal'] = $result5[0];
    } else if ($type == "debitnote") {
        $builder = $db->table('purchase_return');
        $builder->select('id,SUM(net_amount) as purchasereturn_monthtotal');
        $builder->where(array('DATE(return_date)  >= ' => $start_date));
        $builder->where(array('DATE(return_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query6 = $builder->get();
        $result6 = $query6->getResultArray();
        $getdata['total']['purchasereturn_monthtotal'] = $result6[0];

        $builder = $db->table('purchase_general pg');
        $builder->select('id,SUM(net_amount) as purchasereturn_general_monthtotal,v_type');
        $builder->where(array('DATE(doc_date)  >= ' => $start_date));
        $builder->where(array('DATE(doc_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'return', 'is_delete' => 0));
        $query7 = $builder->get();
        $result7 = $query7->getResultArray();
        $getdata['total']['purchasereturn_general_monthtotal'] = $result7[0];
    } else if ($type == 'payment') {
        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as payment_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('mode' => 'Payment', 'is_delete' => 0));
        $query8 = $builder->get();
        $result8 = $query8->getResultArray();
        $getdata['total']['payment_monthtotal'] = $result8[0];
    } else if ($type == 'receipt') {
        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as receipt_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('mode' => 'Receipt', 'is_delete' => 0));
        $query9 = $builder->get();
        $result9 = $query9->getResultArray();
        $getdata['total']['receipt_monthtotal'] = $result9[0];
    } else if ($type == 'cash') {
        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as cashcredit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'cash', 'mode' => 'Receipt', 'is_delete' => 0));
        $query10 = $builder->get();
        $result10 = $query10->getResultArray();
        $getdata['total']['cashcredit_monthtotal'] = $result10[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as cashdebit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'cash', 'mode' => 'Payment', 'is_delete' => 0));
        $query11 = $builder->get();
        $result11 = $query11->getResultArray();
        $getdata['total']['cashdebit_monthtotal'] = $result11[0];
    } else if ($type == 'bank') {
        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as bankcredit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'bank', 'mode' => 'Receipt', 'is_delete' => 0));
        $query12 = $builder->get();
        $result12 = $query12->getResultArray();
        $getdata['total']['bankcredit_monthtotal'] = $result12[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as bankdebit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'bank', 'mode' => 'Payment', 'is_delete' => 0));
        $query13 = $builder->get();
        $result13 = $query13->getResultArray();
        $getdata['total']['bankdebit_monthtotal'] = $result13[0];
    } else if ($type == 'journal') {
        $builder = $db->table('jv_particular');
        $builder->select('id,SUM(amount) as journalcredit_monthtotal');
        $builder->where(array('DATE(date)  >= ' => $start_date));
        $builder->where(array('DATE(date)  <= ' => $end_date));
        $builder->where(array('dr_cr' => 'cr', 'is_delete' => 0));
        $query14 = $builder->get();
        $result14 = $query14->getResultArray();
        $getdata['total']['journalcredit_monthtotal'] = $result14[0];

        $builder = $db->table('jv_particular');
        $builder->select('id,SUM(amount) as journaldebit_monthtotal');
        $builder->where(array('DATE(date)  >= ' => $start_date));
        $builder->where(array('DATE(date)  <= ' => $end_date));
        $builder->where(array('dr_cr' => 'dr', 'is_delete' => 0));
        $query15 = $builder->get();
        $result15 = $query15->getResultArray();
        $getdata['total']['journaldebit_monthtotal'] = $result15[0];
    } else if ($type == 'ledger') {
        $builder = $db->table('sales_invoice');
        $builder->select('id,SUM(net_amount) as salesinvoice_monthtotal');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query = $builder->get();
        $result = $query->getResultArray();
        $ledger['total']['salesinvoice_monthtotal'] = $result[0];
        // echo '<pre>';print_r($result);exit;

        $builder = $db->table('sales_ACinvoice');
        $builder->select('id,SUM(net_amount) as salesgeneralinvoice_monthtotal,v_type');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'general', 'is_delete' => 0));
        $query1 = $builder->get();
        $result1 = $query1->getResultArray();
        $ledger['total']['salesgeneralinvoice_monthtotal'] = $result1[0];

        $builder = $db->table('purchase_invoice');
        $builder->select('id,SUM(net_amount) as purchaseinvoice_monthtotal');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query2 = $builder->get();
        $result2 = $query2->getResultArray();
        $ledger['total']['purchaseinvoice_monthtotal'] = $result2[0];

        $builder = $db->table('purchase_general');
        $builder->select('id,SUM(net_amount) as purchasegeneralinvoice_monthtotal,v_type');
        $builder->where(array('DATE(doc_date)  >= ' => $start_date));
        $builder->where(array('DATE(doc_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'general', 'is_delete' => 0));
        $query3 = $builder->get();
        $result3 = $query3->getResultArray();
        $ledger['total']['purchasegeneralinvoice_monthtotal'] = $result3[0];

        $builder = $db->table('sales_return');
        $builder->select('id,SUM(net_amount) as salesreturn_monthtotal');
        $builder->where(array('DATE(return_date)  >= ' => $start_date));
        $builder->where(array('DATE(return_date)  <= ' => $end_date));
        $builder->where(array('is_delete' => 0));
        $query4 = $builder->get();
        $result4 = $query4->getResultArray();
        $ledger['total']['salesreturn_monthtotal'] = $result4[0];

        $builder = $db->table('sales_ACinvoice');
        $builder->select('id,SUM(net_amount) as creditnote_monthtotal,v_type');
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $builder->where(array('v_type' => 'return', 'is_delete' => 0));
        $query5 = $builder->get();
        $result5 = $query5->getResultArray();
        $ledger['total']['salesreturn_general_monthtotal'] = $result5[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as payment_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('mode' => 'Payment', 'is_delete' => 0));
        $query8 = $builder->get();
        $result8 = $query8->getResultArray();
        $ledger['total']['payment_monthtotal'] = $result8[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as receipt_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('mode' => 'Receipt', 'is_delete' => 0));
        $query9 = $builder->get();
        $result9 = $query9->getResultArray();
        $ledger['total']['receipt_monthtotal'] = $result9[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as cashcredit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'cash', 'mode' => 'Receipt', 'is_delete' => 0));
        $query10 = $builder->get();
        $result10 = $query10->getResultArray();
        $ledger['total']['cashcredit_monthtotal'] = $result10[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as cashdebit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'cash', 'mode' => 'Payment', 'is_delete' => 0));
        $query11 = $builder->get();
        $result11 = $query11->getResultArray();
        $ledger['total']['cashdebit_monthtotal'] = $result11[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as bankcredit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'bank', 'mode' => 'Receipt', 'is_delete' => 0));
        $query12 = $builder->get();
        $result12 = $query12->getResultArray();
        $ledger['total']['bankcredit_monthtotal'] = $result12[0];

        $builder = $db->table('bank_tras');
        $builder->select('id,SUM(amount) as bankdebit_monthtotal');
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $builder->where(array('payment_type' => 'bank', 'mode' => 'Payment', 'is_delete' => 0));
        $query13 = $builder->get();
        $result13 = $query13->getResultArray();
        $ledger['total']['bankdebit_monthtotal'] = $result13[0];

        $builder = $db->table('jv_particular');
        $builder->select('id,SUM(amount) as journalcredit_monthtotal');
        $builder->where(array('DATE(date)  >= ' => $start_date));
        $builder->where(array('DATE(date)  <= ' => $end_date));
        $builder->where(array('dr_cr' => 'cr', 'is_delete' => 0));
        $query14 = $builder->get();
        $result14 = $query14->getResultArray();
        $ledger['total']['journalcredit_monthtotal'] = $result14[0];

        $builder = $db->table('jv_particular');
        $builder->select('id,SUM(amount) as journaldebit_monthtotal');
        $builder->where(array('DATE(date)  >= ' => $start_date));
        $builder->where(array('DATE(date)  <= ' => $end_date));
        $builder->where(array('dr_cr' => 'dr', 'is_delete' => 0));
        $query15 = $builder->get();
        $result15 = $query15->getResultArray();
        $ledger['total']['journaldebit_monthtotal'] = $result15[0];

        $getdata['ledger'] = $ledger;
    }
    //  echo "<pre>";print_r($getdata);
    //exit;
    return $getdata;
}

function glgroup_totalamount($id = '')
{
    //print_r($id);exit;
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('gl_group');
    $builder->select('id,name,code');
    if (!empty($id)) {
        $builder->where(array('id' => $id));
    }
    $query = $builder->get();
    $gl_group = $query->getResultArray();

    for ($i = 0; $i < count($gl_group); $i++) {
        $builder = $db->table('account');
        $builder->select('id,name,code,gl_group');
        
        $builder->where(array('gl_group' => $gl_group[$i]['id']));
        $query1 = $builder->get();
        $account = $query1->getResultArray();
        
        $salesinvoice_total = 0;
        $salesinvoice_generaltotal = 0;
        $purchaseinvoice_total = 0;
        $purchaseinvoice_generaltotal = 0;
        $salesreturn_total = 0;
        $salesreturn_generaltotal = 0;
        $purchasereturn_total = 0;
        $purchasereturn_generaltotal = 0;
        $payment_total = 0;
        $receipt_total = 0;
        $cashcredit_total = 0;
        $cashdebit_total = 0;
        $bankcredit_total = 0;
        $bankdebit_total = 0;
        $journalcredit_total = 0;
        $journaldebit_total = 0;


        for ($j = 0; $j < count($account); $j++) {
            $builder = $db->table('sales_invoice');
            $builder->select('id,SUM(net_amount) as salesinvoice_total,account');
            
            $builder->where(array('is_cancle' => 0,'is_delete' => 0, 'account' => $account[$j]['id']));
            $query2 = $builder->get();
            $result = $query2->getResultArray();
            $salesinvoice_total = $salesinvoice_total + $result[0]['salesinvoice_total'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('id,SUM(net_amount) as salesinvoice_generaltotal,v_type');
            //$builder->where(array('DATE(invoice_date)  >= ' => $start_date));
            //$builder->where(array('DATE(invoice_date)  <= ' => $end_date));
            $builder->where(array('v_type' => 'general', 'is_delete' => 0,'is_cancle' => 0, 'party_account' => $account[$j]['id']));
            $query1 = $builder->get();
            $result1 = $query1->getResultArray();
            $salesinvoice_generaltotal = $salesinvoice_generaltotal + $result1[0]['salesinvoice_generaltotal'];

            $builder = $db->table('purchase_invoice');
            $builder->select('id,SUM(net_amount) as purchaseinvoice_total');
            //$builder->where(array('DATE(invoice_date)  >= ' => $start_date));
            //$builder->where(array('DATE(invoice_date)  <= ' => $end_date));
            $builder->where(array('is_cancle' => 0,'is_delete' => 0, 'account' => $account[$j]['id']));
            $query2 = $builder->get();
            $result2 = $query2->getResultArray();
            $purchaseinvoice_total = $purchaseinvoice_total + $result2[0]['purchaseinvoice_total'];

            $builder = $db->table('purchase_general');
            $builder->select('id,SUM(net_amount) as purchaseinvoice_generaltotal,v_type');
            //$builder->where(array('DATE(doc_date)  >= ' => $start_date));
            //$builder->where(array('DATE(doc_date)  <= ' => $end_date));
            $builder->where(array('v_type' => 'general', 'is_delete' => 0,'is_cancle' => 0, 'party_account' => $account[$j]['id']));
            $query3 = $builder->get();
            $result3 = $query3->getResultArray();
            $purchaseinvoice_generaltotal = $purchaseinvoice_generaltotal + $result3[0]['purchaseinvoice_generaltotal'];

            $builder = $db->table('sales_return');
            $builder->select('id,SUM(net_amount) as salesreturn_total');
            //$builder->where(array('DATE(return_date)  >= ' => $start_date));
            //$builder->where(array('DATE(return_date)  <= ' => $end_date));
            $builder->where(array('is_cancle' => 0,'is_delete' => 0, 'account' => $account[$j]['id']));
            $query4 = $builder->get();
            $result4 = $query4->getResultArray();
            $salesreturn_total = $salesreturn_total + $result4[0]['salesreturn_total'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('id,SUM(net_amount) as salesreturn_generaltotal,v_type');
            //$builder->where(array('DATE(invoice_date)  >= ' => $start_date));
            //$builder->where(array('DATE(invoice_date)  <= ' => $end_date));
            $builder->where(array('v_type' => 'return', 'is_delete' => 0,'is_cancle' => 0, 'party_account' => $account[$j]['id']));
            $query5 = $builder->get();
            $result5 = $query5->getResultArray();
            $salesreturn_generaltotal = $salesreturn_generaltotal + $result5[0]['salesreturn_generaltotal'];

            $builder = $db->table('purchase_return');
            $builder->select('id,SUM(net_amount) as purchasereturn_total');
            //$builder->where(array('DATE(return_date)  >= ' => $start_date));
            //$builder->where(array('DATE(return_date)  <= ' => $end_date));
            $builder->where(array('is_delete' => 0,'is_cancle' => 0, 'account' => $account[$j]['id']));
            $query6 = $builder->get();
            $result6 = $query6->getResultArray();
            $purchasereturn_total = $purchasereturn_total + $result6[0]['purchasereturn_total'];

            $builder = $db->table('purchase_general pg');
            $builder->select('id,SUM(net_amount) as purchasereturn_generaltotal,v_type');
            //$builder->where(array('DATE(doc_date)  >= ' => $start_date));
            //$builder->where(array('DATE(doc_date)  <= ' => $end_date));
            $builder->where(array('v_type' => 'return', 'is_delete' => 0,'is_cancle' => 0, 'party_account' => $account[$j]['id']));
            $query7 = $builder->get();
            $result7 = $query7->getResultArray();
            $purchasereturn_generaltotal = $purchasereturn_generaltotal + $result7[0]['purchasereturn_generaltotal'];

            $builder = $db->table('bank_tras');
            $builder->select('id,SUM(amount) as payment_total');
            //$builder->where(array('DATE(receipt_date)  >= ' => $start_date));
            //$builder->where(array('DATE(receipt_date)  <= ' => $end_date));
            $builder->where(array('mode' => 'Payment', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query8 = $builder->get();
            $result8 = $query8->getResultArray();
            $payment_total = $payment_total + $result8[0]['payment_total'];

            $builder = $db->table('bank_tras');
            $builder->select('id,SUM(amount) as receipt_total');
            //$builder->where(array('DATE(receipt_date)  >= ' => $start_date));
            //$builder->where(array('DATE(receipt_date)  <= ' => $end_date));
            $builder->where(array('mode' => 'Receipt', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query9 = $builder->get();
            $result9 = $query9->getResultArray();
            $receipt_total = $receipt_total + $result9[0]['receipt_total'];

            $builder = $db->table('bank_tras');
            $builder->select('id,SUM(amount) as cashcredit_total');
            //$builder->where(array('DATE(receipt_date)  >= ' => $start_date));
            //$builder->where(array('DATE(receipt_date)  <= ' => $end_date));
            $builder->where(array('payment_type' => 'cash', 'mode' => 'Receipt', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query10 = $builder->get();
            $result10 = $query10->getResultArray();
            $cashcredit_total = $cashcredit_total + $result10[0]['cashcredit_total'];

            $builder = $db->table('bank_tras');
            $builder->select('id,SUM(amount) as cashdebit_total');
            //$builder->where(array('DATE(receipt_date)  >= ' => $start_date));
            //$builder->where(array('DATE(receipt_date)  <= ' => $end_date));
            $builder->where(array('payment_type' => 'cash', 'mode' => 'Payment', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query11 = $builder->get();
            $result11 = $query11->getResultArray();
            $cashdebit_total = $cashdebit_total + $result11[0]['cashdebit_total'];

            $builder = $db->table('bank_tras');
            $builder->select('id,SUM(amount) as bankcredit_total');
            //$builder->where(array('DATE(receipt_date)  >= ' => $start_date));
            //$builder->where(array('DATE(receipt_date)  <= ' => $end_date));
            $builder->where(array('payment_type' => 'bank', 'mode' => 'Receipt', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query12 = $builder->get();
            $result12 = $query12->getResultArray();
            $bankcredit_total = $bankcredit_total + $result12[0]['bankcredit_total'];

            $builder = $db->table('bank_tras');
            $builder->select('id,SUM(amount) as bankdebit_total');
            //$builder->where(array('DATE(receipt_date)  >= ' => $start_date));
            //$builder->where(array('DATE(receipt_date)  <= ' => $end_date));
            $builder->where(array('payment_type' => 'bank', 'mode' => 'Payment', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query13 = $builder->get();
            $result13 = $query13->getResultArray();
            $bankdebit_total = $bankdebit_total + $result13[0]['bankdebit_total'];

            $builder = $db->table('jv_particular');
            $builder->select('id,SUM(amount) as journalcredit_total');
            //$builder->where(array('DATE(date)  >= ' => $start_date));
            //$builder->where(array('DATE(date)  <= ' => $end_date));
            $builder->where(array('dr_cr' => 'cr', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query14 = $builder->get();
            $result14 = $query14->getResultArray();
            $journalcredit_total = $journalcredit_total + $result14[0]['journalcredit_total'];

            $builder = $db->table('jv_particular');
            $builder->select('id,SUM(amount) as journaldebit_total');
            //$builder->where(array('DATE(date)  >= ' => $start_date));
            //$builder->where(array('DATE(date)  <= ' => $end_date));
            $builder->where(array('dr_cr' => 'dr', 'is_delete' => 0, 'particular' => $account[$j]['id']));
            $query15 = $builder->get();
            $result15 = $query15->getResultArray();
            $journaldebit_total = $journaldebit_total + $result15[0]['journaldebit_total'];

        }
        $getdata[$gl_group[$i]['name']]['salesinvoice_total'] = $salesinvoice_total;
        $getdata[$gl_group[$i]['name']]['salesinvoice_generaltotal'] = $salesinvoice_generaltotal;
        $getdata[$gl_group[$i]['name']]['purchaseinvoice_total'] = $purchaseinvoice_total;
        $getdata[$gl_group[$i]['name']]['purchaseinvoice_generaltotal'] = $purchaseinvoice_generaltotal;
        $getdata[$gl_group[$i]['name']]['salesreturn_total'] = $salesreturn_total;
        $getdata[$gl_group[$i]['name']]['salesreturn_generaltotal'] = $salesreturn_generaltotal;
        $getdata[$gl_group[$i]['name']]['purchasereturn_total'] = $purchasereturn_total;
        $getdata[$gl_group[$i]['name']]['purchasereturn_generaltotal'] = $purchasereturn_generaltotal;
        $getdata[$gl_group[$i]['name']]['payment_total'] = $payment_total;
        $getdata[$gl_group[$i]['name']]['receipt_total'] = $receipt_total;
        $getdata[$gl_group[$i]['name']]['cashcredit_total'] = $cashcredit_total;
        $getdata[$gl_group[$i]['name']]['cashdebit_total'] = $cashdebit_total;
        $getdata[$gl_group[$i]['name']]['bankcredit_total'] = $bankcredit_total;
        $getdata[$gl_group[$i]['name']]['bankdebit_total'] = $bankdebit_total;
        $getdata[$gl_group[$i]['name']]['journalcredit_total'] = $journalcredit_total;
        $getdata[$gl_group[$i]['name']]['journaldebit_total'] = $journaldebit_total;

    }
    return $getdata;
}

function Outstanding($type = '',$start_date='',$end_date='')
{
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $data = array();
    $getdata = array();

    if ($type == 'receivable') {

        $builder = $db->table('sales_invoice si');
        $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));
        $builder->where(array('si.is_delete' => 0));
        $query = $builder->get();
        $result = $query->getResultArray();
        // echo '<pre>';print_r($result);exit;
        
        for ($i = 0; $i < count($result); $i++) {

            $net_amount = $result[$i]['net_amount'];
         
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result[$i]['id'], 'bt.voucher_name' => 'Sale Invoice'));
            $query1 = $builder->get();
            $result1 = $query1->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'dr', 'jv.is_delete' => 0, 'jv.invoice' => $result[$i]['id'], 'jv.invoice_tb' => 'sales_invoice'));
            $query2 = $builder->get();
            $result2 = $query2->getRow();

            $bankamount_total = $result1->amount;
            $jvamount_total = $result2->jv_amount;
            
            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$i] = $net_amount - $complete_amount;

            $getdata[$i]['inv_id'] = $result[$i]['id'];
            $getdata[$i]['inv_tb'] = 'sales_invoice';
            $getdata[$i]['net_amount'] = $net_amount;
            $getdata[$i]['amount'] = $complete_amount;
            $getdata[$i]['panding_amount'] = $total_amount[$i];
            $data['sales_invoice'] = $getdata;
      
        }
       
        $builder = $db->table('sales_ACinvoice sg');
        $builder->select('sg.id,sg.party_account,sg.invoice_date as date,sg.total_amount,sg.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sg.party_account');
        $builder->where(array('DATE(sg.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(sg.invoice_date)  <= ' => db_date($end_date)));
        $builder->where(array('sg.is_delete' => 0));
        $query3 = $builder->get();
        $result3 = $query3->getResultArray();

        for ($j = 0; $j < count($result3); $j++) {

            $net_amount = $result3[$j]['net_amount'];
            
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result3[$j]['id'], 'bt.voucher_name' => 'General Sale'));
            $query4 = $builder->get();
            $result4 = $query4->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'dr', 'jv.is_delete' => 0, 'jv.invoice' => $result3[$j]['id'], 'jv.invoice_tb' => 'sales_ACinvoice'));
            $query5 = $builder->get();
            $result5 = $query5->getRow();

            $bankamount_total = $result4->amount;
            $jvamount_total = $result5->jv_amount;
          
            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$j] = $net_amount - $complete_amount;

            $ggetdata[$j]['ginv_id'] = $result3[$j]['id'];
            $ggetdata[$j]['ginv_tb'] = 'sales_ACinvoice';
            $ggetdata[$j]['gnet_amount'] = $net_amount;
            $ggetdata[$j]['gamount'] = $complete_amount;
            $ggetdata[$j]['gpanding_amount'] = $total_amount[$j];
            $data['sales_ACinvoice'] = $ggetdata;
           
        }

    } else {

        $builder = $db->table('purchase_invoice pi');
        $builder->select('pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pi.account');
        $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
        $builder->where(array('pi.is_delete' => 0));
        $query = $builder->get();
        $result = $query->getResultArray();

        for ($i = 0; $i < count($result); $i++) {
            $net_amount = $result[$i]['net_amount'];
         
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result[$i]['id'], 'bt.voucher_name' => 'Purchase Invoice'));
            $query1 = $builder->get();
            $result1 = $query1->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'cr', 'jv.is_delete' => 0, 'jv.invoice' => $result[$i]['id'], 'jv.invoice_tb' => 'purchase_invoice'));
            $query2 = $builder->get();
            $result2 = $query2->getRow();

            $bankamount_total = $result1->amount;
            $jvamount_total = $result2->jv_amount;

            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$i] = $net_amount - $complete_amount;

            $getdata[$i]['inv_id'] = $result[$i]['id'];
            $getdata[$i]['inv_tb'] = 'purchase_invoice';
            $getdata[$i]['net_amount'] = $net_amount;
            $getdata[$i]['amount'] = $complete_amount;
            $getdata[$i]['panding_amount'] = $total_amount[$i];
            $data['purchase_invoice'] = $getdata;

        }

        $builder = $db->table('purchase_general pg');
        $builder->select('pg.id,pg.party_account,pg.total_amount,pg.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pg.party_account');
        $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
        $builder->where(array('pg.is_delete' => 0));
        $query3 = $builder->get();
        $result3 = $query3->getResultArray();

        for ($j = 0; $j < count($result3); $j++) {

            $net_amount = $result3[$j]['net_amount'];
         
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result3[$j]['id'], 'bt.voucher_name' => 'General Purchase'));
            $query4 = $builder->get();
            $result4 = $query4->getRow();
            
            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'cr', 'jv.is_delete' => 0, 'jv.invoice' => $result3[$j]['id'], 'jv.invoice_tb' => 'purchase_general'));
            $query5 = $builder->get();
            $result5 = $query5->getRow();

            $bankamount_total = $result4->amount;
            $jvamount_total = $result5->jv_amount;

            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$j] = $net_amount - $complete_amount;

            $ggetdata[$j]['ginv_id'] = $result3[$j]['id'];
            $ggetdata[$j]['ginv_tb'] = 'purchase_general';
            $ggetdata[$j]['gnet_amount'] = $net_amount;
            $ggetdata[$j]['gamount'] = $complete_amount;
            $ggetdata[$j]['gpanding_amount'] = $total_amount[$j];
            $data['purchase_general'] = $ggetdata;
        }
    }
    // echo '<pre>';print_r($data);exit;
    return $data;
}

// function get_allLedger_OutStanding($post)
// {
//     $db = \Config\Database::connect();
//     if (session('DataSource')) {
//         $db->setDatabase(session('DataSource'));
//     }
    
//     $start_date = @$post['from'];
//     $end_date = @$post['to'];
    
//         $builder = $db->table('account');
//         $builder->select('id,name,intrest_rate');
//         $builder->where(array('is_delete' => 0));
//         $query = $builder->get();
//         $account = $query->getResultArray();

//         $data = array();
//         $data1 = array();

//         foreach($account as $row)
//         {
//             $data['id'] = $row['id'];
//             $data['name'] = $row['name'];


//             //---------Get Sales ITem Invoice Total----------//

//             $builder = $db->table('sales_invoice');
//             $builder->select('SUM(net_amount) as total');
//             $builder->where(array('is_delete' => 0, 'account' => $row['id'] , 'is_cancle'=>0));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
//             }
//             $query = $builder->get();
//             $result = $query->getRowArray();
//             $data['sales_invoice_total'] = $result['total'];


//             //---------General Sales Invoice Total----------//

//             $builder = $db->table('sales_ACinvoice');
//             $builder->select('SUM(net_amount) as total');
//             $builder->where(array('is_delete' => 0,'v_type'=>'general','party_account' => $row['id'], 'is_cancle'=>0));

//             if(!empty($post))
//             { 
//                 $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
//             }
//             $query1 = $builder->get();
//             $result1 = $query1->getRowArray();
//             $data['sales_acinvoice_general_total'] = $result1['total'];


//             //---------General Sales Return Invoice Total----------//

//             $builder = $db->table('sales_ACinvoice');
//             $builder->select('SUM(net_amount) as total');
//             $builder->where(array('is_delete' => 0,'v_type'=>'return','party_account' => $row['id'], 'is_cancle'=>0));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
//             }
//             $query2 = $builder->get();
//             $result2 = $query2->getRowArray();
//             $data['sales_acinvoice_return_total'] = $result2['total'];


//             //---------Purchase Invoice Item Total----------//

//             $builder = $db->table('purchase_invoice');
//             $builder->select('SUM(net_amount) as total');
//             $builder->where(array('is_delete' => 0, 'account' => $row['id'],'is_cancle'=>0));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
//             }
//             $query3 = $builder->get();
//             $result3 = $query3->getRowArray();
//             $data['purchase_invoice_total'] = $result3['total'];


//             //---------General Purchase Invoice Total----------//

//             $builder = $db->table('purchase_general');
//             $builder->select('SUM(net_amount) as total');
//             $builder->where(array('is_delete' => 0,'v_type'=>'general','party_account' => $row['id'],'is_cancle'=>0));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(doc_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(doc_date)  <= ' => db_date($end_date)));
//             }
//             $query4 = $builder->get();
//             $result4 = $query4->getRowArray();
//             $data['purchase_invoice_general_total'] = $result4['total'];


//             //---------General Purchase Return Invoice Total----------//

//             $builder = $db->table('purchase_general');
//             $builder->select('SUM(net_amount) as total');
//             $builder->where(array('is_delete' => 0,'v_type'=>'return','party_account' => $row['id'],'is_cancle'=>0));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(doc_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(doc_date)  <= ' => db_date($end_date)));
//             }
//             $query5 = $builder->get();
//             $result5 = $query5->getRowArray();
//             $data['purchase_invoice_return_total'] = $result5['total'];


//             //---------Bank Payment Total----------//

//             $builder = $db->table('bank_tras');
//             $builder->select('SUM(amount) as total');
//             $builder->where(array('mode' => 'Payment','is_delete' => 0,'account'=>$row['id']));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
//             }
//             $query6 = $builder->get();
//             $result6 = $query6->getRowArray();
//             $data['bank_trans_payment'] = $result6['total'];


//             //---------Bank Receipt Total----------//
            
//             $builder = $db->table('bank_tras');
//             $builder->select('SUM(amount) as total');
//             $builder->where(array('mode' => 'Receipt','is_delete' => 0,'account'=>$row['id']));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
//             }
//             $query7 = $builder->get();
//             $result7 = $query7->getRowArray();
//             $data['bank_trans_receipt'] = $result7['total'];


//             //---------JV Credit Total----------//

//             $builder = $db->table('jv_particular');
//             $builder->select('SUM(amount) as total');
//             $builder->where(array('dr_cr' => 'cr', 'is_delete' => 0,'particular'=>$row['id']));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(date)  <= ' => db_date($end_date)));
//             }
//             $query8 = $builder->get();
//             $result8 = $query8->getRowArray();
//             $data['jv_particular_cr'] = $result8['total'];


//             //---------JV Debit Total----------//

//             $builder = $db->table('jv_particular');
//             $builder->select('SUM(amount) as total');
//             $builder->where(array('dr_cr' => 'dr', 'is_delete' => 0,'particular'=>$row['id']));
//             if(!empty($post))
//             {
//                 $builder->where(array('DATE(date)  >= ' => db_date($start_date)));
//                 $builder->where(array('DATE(date)  <= ' => db_date($end_date)));
//             }
//             $query9 = $builder->get();
//             $result9 = $query8->getRowArray();
//             $data['jv_particular_dr'] = $result9['total'];

         
//             $data['receivable_amount'] = $data['sales_invoice_total'] + $data['sales_acinvoice_general_total'] + $data['purchase_invoice_return_total']
//                                 + $data['bank_trans_receipt'] + $data['jv_particular_cr'];
//             $data['payble_amount'] = $data['purchase_invoice_total'] + $data['purchase_invoice_general_total'] + $data['sales_acinvoice_return_total']
//                                 + $data['bank_trans_payment'] + $data['jv_particular_dr'];

//             $data['outstanding'] = $data['receivable_amount'] - $data['payble_amount'];

//             $data1[] = $data;
            
//         }

//        return $data1;
// }


function get_allLedger_OutStanding($post)
{

    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
        $start_date = $post['from'];
        $end_date = $post['to'];

        $builder = $db->table('gl_group');
        $builder->select('id');
        $builder->where(array('name' => 'Sundry Creditors'));
        $query = $builder->get();
        $gl_group = $query->getRowArray();
        
        $glgroup = gl_list([$gl_group['id']]);
        $glgroup[]=$gl_group['id'];
       
       $builder = $db->table('gl_group');
       $builder->select('id');
       $query = $builder->get();
       $gl_group1 = $query->getRowArray();

       $glgroup1 = gl_list([$gl_group1['id']]);
       $glgroup1[]=$gl_group1['id'];
       $gl_grp = array_merge($glgroup,$glgroup1);
       
   

    //    $db = $this->db;
    //    $db->setDatabase(session('DataSource')); 
    //    $builder = $db->table('account acc');
    //    $builder->select('acc.name,acc.id,acc.gst,acc.tds_rate,acc.tds_limit,acc.state');
    //    $builder->join('gl_group gl','gl.id = acc.gl_group');
    //    $builder->where(array('acc.is_delete' => '0' ));
    //    $builder->whereIn('gl.id',$sales_gl);
    //    if(@$post['searchTerm']){
    //        $builder->like('acc.name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
    //    }
    //    $query = $builder->get();
    //    $getdata = $query->getResultArray();

        $builder = $db->table('account acc');
        $builder->select('acc.id,acc.name,acc.intrest_rate');
        $builder->join('gl_group gl','gl.id = acc.gl_group');
        $builder->where(array('acc.is_delete' => 0));
        $builder->whereIn('gl.id',$gl_grp);
        $query = $builder->get();
        $account = $query->getResultArray();
       
        $data = array();
        $data1 = array();

        foreach($account as $row)
        {
            $data['id'] = $row['id'];
            $data['name'] = $row['name'];
            
            $builder = $db->table('sales_invoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0, 'account' => $row['id'] , 'is_cancle'=>0));
            $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query = $builder->get();
            $result = $query->getRowArray();

            $data['sales_invoice_total'] = $result['total'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'general','party_account' => $row['id'], 'is_cancle'=>0)); 
            $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query1 = $builder->get();
            $result1 = $query1->getRowArray();

            $data['sales_acinvoice_general_total'] = $result1['total'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'return','party_account' => $row['id'], 'is_cancle'=>0));
            $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query2 = $builder->get();
            $result2 = $query2->getRowArray();

            $data['sales_acinvoice_return_total'] = $result2['total'];

            $builder = $db->table('purchase_invoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0, 'account' => $row['id'],'is_cancle'=>0));
            $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query3 = $builder->get();
            $result3 = $query3->getRowArray();

            $data['purchase_invoice_total'] = $result3['total'];

            $builder = $db->table('purchase_general');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'general','party_account' => $row['id'],'is_cancle'=>0));
            $builder->where(array('DATE(doc_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(doc_date)  <= ' => db_date($end_date)));
            $query4 = $builder->get();
            $result4 = $query4->getRowArray();

            $data['purchase_invoice_general_total'] = $result4['total'];

            $builder = $db->table('purchase_general');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'return','party_account' => $row['id'],'is_cancle'=>0));
            $builder->where(array('DATE(doc_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(doc_date)  <= ' => db_date($end_date)));
            $query5 = $builder->get();
            $result5 = $query5->getRowArray();

            $data['purchase_invoice_return_total'] = $result5['total'];

            $builder = $db->table('bank_tras');
            $builder->select('SUM(amount) as total');
            $builder->where(array('mode' => 'Payment','is_delete' => 0,'account'=>$row['id']));
            $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
            $query6 = $builder->get();
            $result6 = $query6->getRowArray();

            $data['bank_trans_payment'] = $result6['total'];
            
            $builder = $db->table('bank_tras');
            $builder->select('SUM(amount) as total');
            $builder->where(array('mode' => 'Receipt','is_delete' => 0,'account'=>$row['id']));
            $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
            $query7 = $builder->get();
            $result7 = $query7->getRowArray();
            $data['bank_trans_receipt'] = $result7['total'];

            $builder = $db->table('jv_particular');
            $builder->select('SUM(amount) as total');
            $builder->where(array('dr_cr' => 'cr', 'is_delete' => 0,'particular'=>$row['id']));
            $builder->where(array('DATE(date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(date)  <= ' => db_date($end_date)));
            $query8 = $builder->get();
            $result8 = $query8->getRowArray();

            $data['jv_particular_cr'] = $result8['total'];

            $builder = $db->table('jv_particular');
            $builder->select('SUM(amount) as total');
            $builder->where(array('dr_cr' => 'dr', 'is_delete' => 0,'particular'=>$row['id']));
            $builder->where(array('DATE(date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(date)  <= ' => db_date($end_date)));
            $query9 = $builder->get();
            $result9 = $query8->getRowArray();
            $data['jv_particular_dr'] = $result9['total'];

         
            $data['receivable_amount'] = $data['sales_invoice_total'] + $data['sales_acinvoice_general_total'] + $data['purchase_invoice_return_total']
                                + $data['bank_trans_receipt'] + $data['jv_particular_cr'];
            $data['payble_amount'] = $data['purchase_invoice_total'] + $data['purchase_invoice_general_total'] + $data['sales_acinvoice_return_total']
                                + $data['bank_trans_payment'] + $data['jv_particular_dr'];

            $data['outstanding'] = $data['receivable_amount'] - $data['payble_amount'];

            $data1[] = $data;
            
        }
        // echo '<pre>';print_r($start_date);
        // echo '<pre>';print_r($data1);exit;

       return $data1;
       //exit;
      
  

}
function Ledger_outstanding($account_id,$start_date='',$end_date='')
{
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $data = array();
    if (!empty($account_id)) {

        $builder = $db->table('account');
        $builder->select('name,intrest_rate');
        $builder->where(array('is_delete' => 0, 'id' => $account_id));
        $query = $builder->get();
        $account = $query->getRow();

        $account_name = $account->name;
        $intrest_rate = $account->intrest_rate;
        $data['accountname'] = $account_name;

        $builder = $db->table('sales_invoice si');
        $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0, 'si.account' => $account_id));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $result = $query->getResultArray();
        // echo '<pre>';print_r($result);exit;

        for ($i = 0; $i < count($result); $i++) {
            $net_amount = @$result[$i]['net_amount'];
           
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result[$i]['id'], 'bt.voucher_name' => 'Sale Invoice'));
            $query1 = $builder->get();
            $result1 = $query1->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'dr', 'jv.is_delete' => 0, 'jv.invoice' => @$result[$i]['id'], 'jv.invoice_tb' => 'sales_invoice'));
            $query2 = $builder->get();
            $result2 = $query2->getRow();

            $bankamount_total = @$result1->amount;
            $jvamount_total = @$result2->jv_amount;
          
            $complete_amount = @$bankamount_total+@$jvamount_total;
            $total_amount[$i] = @$net_amount-@$complete_amount;

            $getdata[$i]['inv_id'] = $result[$i]['id'];
            $getdata[$i]['inv_date'] = $result[$i]['date'];
            $getdata[$i]['account_name'] = $account_name;
            $getdata[$i]['net_amount'] = $net_amount;
            $getdata[$i]['amount'] = $complete_amount;
            $getdata[$i]['panding_amount'] = $total_amount[$i];
            $getdata[$i]['intrest_rate'] = $intrest_rate;
            $data['sales_invoice'] = $getdata;
        }

        $builder = $db->table('sales_ACinvoice sg');
        $builder->select('sg.id,sg.party_account,sg.invoice_date as date,sg.total_amount,sg.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sg.party_account');
        $builder->where(array('sg.is_delete' => 0, 'sg.party_account' => $account_id));
        $builder->where(array('DATE(sg.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(sg.invoice_date)  <= ' => db_date($end_date)));
        $query3 = $builder->get();
        $result3 = $query3->getResultArray();

        for ($j = 0; $j < count($result3); $j++) {

            $net_amount = $result3[$j]['net_amount'];
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result3[$j]['id'], 'bt.voucher_name' => 'General Sale'));
            $query4 = $builder->get();
            $result4 = $query4->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'dr', 'jv.is_delete' => 0, 'jv.invoice' => $result3[$j]['id'], 'jv.invoice_tb' => 'sales_ACinvoice'));
            $query5 = $builder->get();
            $result5 = $query5->getRow();

            $bankamount_total = $result4->amount;
            $jvamount_total = $result5->jv_amount;
      
            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$j] = $net_amount - $complete_amount;

            $ggetdata[$j]['ginv_id'] = @$result3[$j]['id'];
            $ggetdata[$j]['ginv_date'] = @$result3[$i]['date'];
            $ggetdata[$j]['gaccount_name'] = @$account_name;
            $ggetdata[$j]['gnet_amount'] = @$net_amount;
            $ggetdata[$j]['gamount'] = @$complete_amount;
            $ggetdata[$j]['gpanding_amount'] = @$total_amount[$j];
            $ggetdata[$j]['gintrest_rate'] = @$intrest_rate;
            $data['sales_ACinvoice'] = @$ggetdata;
        }

        $builder = $db->table('purchase_invoice pi');
        $builder->select('pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pi.account');
        $builder->where(array('pi.is_delete' => 0, 'pi.account' => $account_id));
        $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
        $query6 = $builder->get();
        $result6 = $query6->getResultArray();

        for ($k = 0; $k < count($result6); $k++) {

            $net_amount = @$result6[$k]['net_amount'];

            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result6[$k]['id'], 'bt.voucher_name' => 'Purchase Invoice'));
            $query7 = $builder->get();
            $result7 = $query7->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'cr', 'jv.is_delete' => 0, 'jv.invoice' => @$result6[$k]['id'], 'jv.invoice_tb' => 'purchase_invoice'));
            $query8 = $builder->get();
            $result8 = $query8->getRow();

            $bankamount_total = $result7->amount;
            $jvamount_total = $result8->jv_amount;
            
            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$k] = $net_amount - $complete_amount;

            $pgetdata[$k]['pinv_id'] = $result6[$k]['id'];
            $pgetdata[$k]['pinv_date'] = $result6[$k]['date'];
            $pgetdata[$k]['paccount_name'] = $account_name;
            $pgetdata[$k]['pnet_amount'] = $net_amount;
            $pgetdata[$k]['pamount'] = $complete_amount;
            $pgetdata[$k]['ppanding_amount'] = $total_amount[$k];
            $pgetdata[$k]['pintrest_rate'] = $intrest_rate;
            $data['purchase_invoice'] = $pgetdata;

        }

        $builder = $db->table('purchase_general pg');
        $builder->select('pg.id,pg.party_account,pg.total_amount,pg.net_amount,ac.name as account_name,doc_date');
        $builder->join('account ac', 'ac.id = pg.party_account');
        $builder->where(array('pg.is_delete' => 0, 'pg.party_account' => $account_id));
        $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
        $query9 = $builder->get();
        $result9 = $query9->getResultArray();

        for ($l = 0; $l < count($result9); $l++) {

            $net_amount = $result9[$l]['net_amount'];
            $builder = $db->table('bank_cash_against bt');
            $builder->select('bt.id,bt.ac_id,bt.date as date,SUM(bt.vch_amt) as amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.ac_id','left');
            $builder->where(array('bt.is_delete' => 0, 'bt.vch_id' => $result9[$l]['id'], 'bt.voucher_name' => 'General Purchase'));
            $query10 = $builder->get();
            $result10 = $query10->getRow();

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.particular,jv.dr_cr,SUM(jv.amount) as jv_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.dr_cr' => 'cr', 'jv.is_delete' => 0, 'jv.invoice' => $result9[$l]['id'], 'jv.invoice_tb' => 'purchase_general'));
            $query11 = $builder->get();
            $result11 = $query11->getRow();

            $bankamount_total = $result10->amount;
            $jvamount_total = $result11->jv_amount;
           
            $complete_amount = $bankamount_total + $jvamount_total;
            $total_amount[$l] = $net_amount - $complete_amount;

        
            $pggetdata[$l]['pginv_id'] = $result9[$l]['id'];
            $pggetdata[$l]['pginv_date'] = $result9[$l]['doc_date'];
            $pggetdata[$l]['pgaccount_name'] = $account_name;
            $pggetdata[$l]['pgnet_amount'] = $net_amount;
            $pggetdata[$l]['pgamount'] = $complete_amount;
            $pggetdata[$l]['pgpanding_amount'] = $total_amount[$l];
            $pggetdata[$l]['pgintrest_rate'] = $intrest_rate;
            $data['purchase_general'] = $pggetdata;
        }

    }
    return $data;
    
}

function get_legderoutstanding_data($post)
{ 
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $start_date = @$post['start_date'];
    $end_date = @$post['end_date'];
    
        $builder = $db->table('account');
        $builder->select('id,name,intrest_rate');
        $builder->where(array('is_delete' => 0));
        $query = $builder->get();
        $account = $query->getResultArray();
        $data = array();
        $data1 = array();
        foreach($account as $row)
        {
            $data['id'] = $row['id'];
            $data['name'] = $row['name'];
            
            $builder = $db->table('sales_invoice');
            $builder->select('SUM(net_amount) as total');
            //$builder->join('account ac', 'ac.id = si.account');
            $builder->where(array('is_delete' => 0, 'account' => $row['id'] , 'is_cancle'=>0));
                $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query = $builder->get();
            $result = $query->getRowArray();
            $data['sales_invoice_total'] = $result['total'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'general','party_account' => $row['id'], 'is_cancle'=>0)); 
                $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query1 = $builder->get();
            $result1 = $query1->getRowArray();
            $data['sales_acinvoice_general_total'] = $result1['total'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'return','party_account' => $row['id'], 'is_cancle'=>0));
                $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query2 = $builder->get();
            $result2 = $query2->getRowArray();
            $data['sales_acinvoice_return_total'] = $result2['total'];

            $builder = $db->table('purchase_invoice');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0, 'account' => $row['id'],'is_cancle'=>0));
                $builder->where(array('DATE(invoice_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(invoice_date)  <= ' => db_date($end_date)));
            $query3 = $builder->get();
            $result3 = $query3->getRowArray();
            $data['purchase_invoice_total'] = $result3['total'];

            $builder = $db->table('purchase_general');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'general','party_account' => $row['id'],'is_cancle'=>0));
                $builder->where(array('DATE(doc_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(doc_date)  <= ' => db_date($end_date)));
            $query4 = $builder->get();
            $result4 = $query4->getRowArray();
            $data['purchase_invoice_general_total'] = $result4['total'];

            $builder = $db->table('purchase_general');
            $builder->select('SUM(net_amount) as total');
            $builder->where(array('is_delete' => 0,'v_type'=>'return','party_account' => $row['id'],'is_cancle'=>0));
                $builder->where(array('DATE(doc_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(doc_date)  <= ' => db_date($end_date)));
            $query5 = $builder->get();
            $result5 = $query5->getRowArray();
            $data['purchase_invoice_return_total'] = $result5['total'];

            $builder = $db->table('bank_tras');
            $builder->select('SUM(amount) as total');
            $builder->where(array('mode' => 'Payment','is_delete' => 0,'account'=>$row['id']));
                $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
            $query6 = $builder->get();
            $result6 = $query6->getRowArray();
            $data['bank_trans_payment'] = $result6['total'];
            
            $builder = $db->table('bank_tras');
            $builder->select('SUM(amount) as total');
            $builder->where(array('mode' => 'Receipt','is_delete' => 0,'account'=>$row['id']));
                $builder->where(array('DATE(receipt_date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(receipt_date)  <= ' => db_date($end_date)));
            $query7 = $builder->get();
            $result7 = $query7->getRowArray();
            $data['bank_trans_receipt'] = $result7['total'];

            $builder = $db->table('jv_particular');
            $builder->select('SUM(amount) as total');
            $builder->where(array('dr_cr' => 'cr', 'is_delete' => 0,'particular'=>$row['id']));
                $builder->where(array('DATE(date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(date)  <= ' => db_date($end_date)));
            $query8 = $builder->get();
            $result8 = $query8->getRowArray();
            $data['jv_particular_cr'] = $result8['total'];

            $builder = $db->table('jv_particular');
            $builder->select('SUM(amount) as total');
            $builder->where(array('dr_cr' => 'dr', 'is_delete' => 0,'particular'=>$row['id']));
                $builder->where(array('DATE(date)  >= ' => db_date($start_date)));
                $builder->where(array('DATE(date)  <= ' => db_date($end_date)));
            $query9 = $builder->get();
            $result9 = $query8->getRowArray();
            $data['jv_particular_dr'] = $result9['total'];

         
            $data['receivable_amount'] = $data['sales_invoice_total'] + $data['sales_acinvoice_general_total'] + $data['purchase_invoice_return_total']
                                + $data['bank_trans_receipt'] + $data['jv_particular_cr'];
            $data['payble_amount'] = $data['purchase_invoice_total'] + $data['purchase_invoice_general_total'] + $data['sales_acinvoice_return_total']
                                + $data['bank_trans_payment'] + $data['jv_particular_dr'];

            $data['outstanding'] = $data['receivable_amount'] - $data['payble_amount'];
            $data1[] = $data;
            
        }
       
       return $data1;
       
}

function csv_export($data = array(), $headlist = array(), $fileName)
{
    // USEFULL //
    // $header = array("name","last");
    // $data = array();
    // for(){
    //     $data[] =array(
    //         roha,
    //         vaja
    //     );
    // }
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
    header('Cache-Control: max-age=0');

    // Open the PHP file handle, php://output means direct output to the browser
    $fp = fopen('php://output', 'w');

    // Output Excel column name information
    foreach ($headlist as $key => $value) {
        //CSV Excel supports GBK encoding, must be converted, otherwise garbled
        $headlist[$key] = iconv('utf-8', 'gbk', $value);
    }

    // Write the data to the file handle through fputcsv
    fputcsv($fp, $headlist);

    //counter
    $num = 0;

    // Every $limit line, refresh the output buffer, not too big, not too small
    $limit = 100000;

    // Line out data, no waste of memory
    echo $count = count($data);
    for ($i = 0; $i < $count; $i++) {

        $num++;
        // Refresh the output buffer to prevent problems due to too much data
        if ($limit == $num) {
            ob_flush();
            flush();
            $num = 0;
        }

        $row = $data[$i];
        foreach ($row as $key => $value) {
            $aRow[] = iconv('utf-8', 'gbk', $value);
        }

        //fputcsv($fp, $aRow);

        //if($num == 2)
        print_r($row);exit;
    }
}

//****** Trading Sales/Purchase Deep Detail Function ******//
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

    $monthly_purchase = array();

    $builder = $db->table('purchase_invoice p');
    $builder->select('MONTH(p.invoice_date) as month,YEAR(p.invoice_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', 0);
    $builder->where('p.is_cancle', 0);
    $builder->where(array('DATE(p.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.invoice_date)');
    $result = $builder->get();
    $monthly_purchase = $result->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
}

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

    $monthly_sales = array();

    $builder = $db->table('sales_invoice p');
    $builder->select('MONTH(p.invoice_date) as month,YEAR(p.invoice_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete',0);
    $builder->where('p.is_cancle',0);
    $builder->where(array('DATE(p.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.invoice_date)');
    $result = $builder->get();
    $monthly_sales = $result->getResultArray();

    // echo '<pre>';print_r($monthly_sales);exit;
    $arra = array();
    foreach ($monthly_sales as $value) {

        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    // echo '<pre>';print_r($arra);exit;
    return $arra;
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

    $monthly_purchase = array();

    $builder = $db->table('purchase_return p');
    $builder->select('MONTH(p.return_date) as month,YEAR(p.return_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', '0');
    $builder->where(array('DATE(p.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.return_date)');
    $result = $builder->get();
    $monthly_purchase = $result->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
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

    $builder = $db->table('sales_return p');
    $builder->select('MONTH(p.return_date) as month,YEAR(p.return_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', '0');
    $builder->where(array('DATE(p.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.return_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.return_date)');
    $result = $builder->get();
    $monthly_sales = $result->getResultArray();

    $arra = array();
    foreach ($monthly_sales as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
}


function purchaseGray_monthly_data($start_date = '', $end_date = '')
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
    $monthly_purchase = array();

    $builder = $db->table('gray_item gi');
    $builder->select('MONTH(g.inv_date) as month,YEAR(g.inv_date) as year,SUM(amount) as total');
    $builder->join('grey g', 'g.id = gi.voucher_id');
    $builder->where('gi.purchase_type', 'Gray');
    $builder->where('g.is_delete', '0');
    $builder->groupBy('MONTH(g.inv_date)');
    $query = $builder->get();
    $monthly_purchase = $query->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
        );
    }

    return $arra;

    // $sums = array();
    // // foreach (array_keys($monthly_purchase + $monthly_gray_finish_purchase) as $key) {
    // //     // print_r(isset($monthly_purchase[$key]['total']) ? $monthly_purchase[$key]['total'] : 0);
    // //     // print_r(isset($monthly_gray_finish_purchase[$key]['total']) ? $monthly_gray_finish_purchase[$key]['total'] : 0);
    // //     // $sums[$key]['month'] = $$key]['month'];
    // //     $sums[$key]['total'] = (isset($monthly_purchase[$key]['total']) ? $monthly_purchase[$key]['total'] : 0) + (isset($monthly_gray_finish_purchase[$key]['total']) ? $monthly_gray_finish_purchase[$key]['total'] : 0);
    // // }
    // if(count($monthly_gray_finish_purchase) < count($monthly_purchase)){
    //     $max_array = $monthly_purchase;
    //     $bool =0;
    // }else{
    //     $max_array = $monthly_gray_finish_purchase;
    //     $bool = 1;
    // }

    // foreach($monthly_purchase as $row){
    //     // foreach($monthly_gray_finish_purchase as $row1){
    //     //     if($row['month'] == $row1['month']){
    //     //         $total = (float)$row['total'] + (float)$row1['total'];
    //     //         $test['month'] = $row['month'];
    //     //         $test['total'] = $total;
    //     //         $arr[]= $test;
    //     //     }else{
    //     //         $arr[]= $row1;
    //     //         $arr[]= $row;
    //     //     }
    //     // }
    // }
    // echo '<pre>';print_r($arr);exit;
}

function salesGray_monthly_data($start_date = '', $end_date = '')
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

    $builder = $db->table('saleMillInvoice_Item sgi');
    $builder->select('MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter');
    $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
    $builder->where('(sgi.item_type="Gray" OR sgi.item_type = "gray")');
    $builder->where(array('sg.is_delete' => '0'));
    $builder->where(array('DATE(sg.date)  >= ' => $start_date));
    $builder->where(array('DATE(sg.date)  <= ' => $end_date));
    $query = $builder->get();
    //$gray_finish_sale = $query->getResultArray();
    $monthly_sales = $query->getResultArray();

    $arra = array();
    foreach ($monthly_sales as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'] * $value['meter'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function purchaseFinish_monthly_data($start_date = '', $end_date = '')
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
    $monthly_purchase = array();

    $builder = $db->table('gray_item gi');
    $builder->select('MONTH(g.inv_date) as month,YEAR(g.inv_date) as year,SUM(amount) as total');
    $builder->join('grey g', 'g.id = gi.voucher_id');
    $builder->where('gi.purchase_type', 'Finish');
    $builder->where('g.is_delete', '0');
    $builder->groupBy('MONTH(g.inv_date)');
    $query = $builder->get();
    $monthly_purchase = $query->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function salesFinish_monthly_data($start_date = '', $end_date = '')
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

    $builder = $db->table('saleMillInvoice_Item sgi');
    $builder->select('MONTH(sg.date) as month,YEAR(sg.date) as year,SUM(sgi.price) as total,SUM(sgi.meter) as meter');
    $builder->join('saleMillInvoice sg', 'sg.id = sgi.voucher_id');
    $builder->where('(sgi.item_type="Finish" OR sgi.item_type = "finish")');
    $builder->where(array('sg.is_delete' => '0'));
    $builder->where(array('DATE(sg.date)  >= ' => $start_date));
    $builder->where(array('DATE(sg.date)  <= ' => $end_date));
    $query = $builder->get();
    //$gray_finish_sale = $query->getResultArray();
    $monthly_sales = $query->getResultArray();

    $arra = array();
    foreach ($monthly_sales as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'] * $value['meter'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function purchaseReturnGray_monthly_data($start_date = '', $end_date = '')
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
    $monthly_purchase = array();

    $builder = $db->table('retGrayFinish_item gi');
    $builder->select('MONTH(g.date) as month,YEAR(g.date) as year,SUM(subtotal) as total');
    $builder->join('retGrayFinish g', 'g.id = gi.voucher_id');
    $builder->where('gi.purchase_type', 'Gray');
    $builder->where('g.is_delete', '0');
    $builder->groupBy('MONTH(g.date)');
    $query = $builder->get();
    $monthly_purchase = $query->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function salesReturnGray_monthly_data($start_date = '', $end_date = '')
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

    $builder = $db->table('saleMillReturn_Item rsgi');
    //$builder->select('rsgi.price,rsgi.id,rsgi.ret_meter,rsgi.item_type');
    $builder->select('MONTH(rsg.date) as month,YEAR(rsg.date) as year,SUM(subtotal) as total');
    $builder->join('saleMillReturn rsg', 'rsg.id = rsgi.voucher_id');
    $builder->where('(rsgi.item_type="Gray" OR rsgi.item_type = "gray")');
    $builder->where(array('rsg.is_delete' => '0'));
    $builder->where(array('DATE(rsg.date)  >= ' => $start_date));
    $builder->where(array('DATE(rsg.date)  <= ' => $end_date));
    $query = $builder->get();
    $monthly_sales = $query->getResultArray();

    $arra = array();
    foreach ($monthly_sales as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function salesReturnFinish_monthly_data($start_date = '', $end_date = '')
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
    $monthly_purchase = array();

    $builder = $db->table('saleMillReturn_Item rsgi');
    //$builder->select('rsgi.price,rsgi.id,rsgi.ret_meter,rsgi.item_type');
    $builder->select('MONTH(rsg.date) as month,YEAR(rsg.date) as year,SUM(subtotal) as total');
    $builder->join('saleMillReturn rsg', 'rsg.id = rsgi.voucher_id');
    $builder->where('(rsgi.item_type="Finish" OR rsgi.item_type = "finish")');
    $builder->where(array('rsg.is_delete' => '0'));
    $builder->where(array('DATE(rsg.date)  >= ' => $start_date));
    $builder->where(array('DATE(rsg.date)  <= ' => $end_date));
    $query = $builder->get();
    $monthly_sales = $query->getResultArray();

    $arra = array();
    foreach ($monthly_sales as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function purchaseReturnFinish_monthly_data($start_date = '', $end_date = '')
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
    $monthly_purchase = array();

    $builder = $db->table('retGrayFinish_item gi');
    $builder->select('MONTH(g.date) as month,YEAR(g.date) as year,SUM(subtotal) as total');
    $builder->join('retGrayFinish g', 'g.id = gi.voucher_id');
    $builder->where('gi.purchase_type', 'Finish');
    $builder->where('g.is_delete', '0');
    $builder->groupBy('MONTH(g.date)');
    $query = $builder->get();
    $monthly_purchase = $query->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
        );
    }

    return $arra;

}

function inword($number)
{
    //$number = 190908100.25;
    
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
          
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] ." " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " ". $digits[$counter] . $plural . " " . $hundred;
        } else {
            $str[] = null;
        }

    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
    "." . $words[$point / 10] . " " .
    $words[$point = $point % 10] : '';
    
    if($result == ''){
        $result = "ZERO ";
    }
    if($points == ''){
        $points = "ZERO ";
    }
    $data = $result . "Rupees  " . $points . " Paise";
    return $data;

}

function payment_monthly_data($start_date = '', $end_date = '',$mode = '')
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

    $monthly_purchase = array();

    $getdata = array();
    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,SUM(bt.amount) as total,COUNT(id) as voucher_count');
    $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));

    if (!empty($mode)) {
        $builder->where(array('bt.payment_type' => $mode));
    }else{
        $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
        $builder->where($whr);
    }
    $builder->groupBy('MONTH(bt.receipt_date)');
    $query = $builder->get();
    $payment = $query->getResultArray();

    $arra = array();
    
    foreach ($payment as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count']            
        );
    }

    return $arra;
}

function receipt_monthly_data($start_date = '', $end_date = '',$mode = '')
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

    $monthly_purchase = array();

    $getdata = array();
    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,SUM(bt.amount) as total,COUNT(id) as voucher_count');
    $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));

    if (!empty($mode)) {
        $builder->where(array('bt.payment_type' => $mode));
    }else{
        $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
        $builder->where($whr);
    }
    $builder->groupBy('MONTH(bt.receipt_date)');
    $query = $builder->get();
    $receipt = $query->getResultArray();

    $arra = array();
    
    foreach ($receipt as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count']            
        );
    }

    return $arra;
}

function contra_monthly_data($start_date = '', $end_date = '',$mode = '')
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

    $monthly_purchase = array();

    $getdata = array();

    $getdata = array();
    $builder = $db->table('bank_tras bt');
    $builder->select('MONTH(bt.receipt_date) as month,YEAR(bt.receipt_date) as year,SUM(bt.amount) as total,COUNT(id) as voucher_count');
    $builder->where(array('bt.is_delete' => 0));
    $builder->where(array('bt.payment_type' => 'contra'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $builder->groupBy('MONTH(bt.receipt_date)');
    $query = $builder->get();
    $receipt = $query->getResultArray();

    $arra = array();
    
    foreach ($receipt as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count']            
        );
    }

    return $arra;
}

function Gnrlpurchase_monthly_data($start_date = '', $end_date = '')
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

    $monthly_purchase = array();

    $builder = $db->table('purchase_general p');
    $builder->select('MONTH(p.doc_date) as month,YEAR(p.doc_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', 0);
    $builder->where('p.is_cancle', 0);
    $builder->where('p.v_type', 'general');
    $builder->where(array('DATE(p.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.doc_date)');
    $result = $builder->get();
    $monthly_purchase = $result->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
}

function Gnrlpurchase_rtn_monthly_data($start_date = '', $end_date = '')
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

    $monthly_purchase = array();

    $builder = $db->table('purchase_general p');
    $builder->select('MONTH(p.doc_date) as month,YEAR(p.doc_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', 0);
    $builder->where('p.is_cancle', 0);
    $builder->where('p.v_type', 'return');
    $builder->where(array('DATE(p.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.doc_date)');
    $result = $builder->get();
    $monthly_purchase = $result->getResultArray();

    $arra = array();
    foreach ($monthly_purchase as $value) {
        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
}

function GnrlsalesItem_monthly_data($start_date = '', $end_date = '')
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

    $builder = $db->table('sales_ACinvoice p');
    $builder->select('MONTH(p.invoice_date) as month,YEAR(p.invoice_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', '0');
    $builder->where('p.is_cancle', '0');
    $builder->where('p.v_type', 'general');
    $builder->where(array('DATE(p.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.invoice_date)');
    $result = $builder->get();
    $monthly_sales = $result->getResultArray();

    $arra = array();

    foreach ($monthly_sales as $value) {

        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
}

function GnrlsalesRtnItem_monthly_data($start_date = '', $end_date = '')
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

    $builder = $db->table('sales_ACinvoice p');
    $builder->select('MONTH(p.invoice_date) as month,YEAR(p.invoice_date) as year,SUM(p.taxable) as total,SUM(p.net_amount) as total_net_amt,COUNT(id) as voucher_count');
    $builder->where('p.is_delete', '0');
    $builder->where('p.is_cancle', '0');
    $builder->where('p.v_type', 'return');
    $builder->where(array('DATE(p.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(p.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('MONTH(p.invoice_date)');
    $result = $builder->get();
    $monthly_sales = $result->getResultArray();

    $arra = array();

    foreach ($monthly_sales as $value) {

        $arra[$value['month']] = array(
            "total" => $value['total'],
            "year" => $value['year'],
            "voucher_count" => $value['voucher_count'],
            "total_net" => $value['total_net_amt'],
        );
    }

    return $arra;
}
