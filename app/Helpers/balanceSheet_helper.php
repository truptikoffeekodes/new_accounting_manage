<?php

use App\Models\GeneralModel;

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
        $ac_capital[$key]['total'] = @$ac_capital[$key]['total'] + (@$ac_capital[$key]['rec_total'] - @$ac_capital[$key]['pay_total']);
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

        $capital[$row['gl_name']]['total'] = @$capital[$row['gl_name']]['cr_total'] - @$capital[$row['gl_name']]['dr_total'];
    }

    foreach ($ac_capital as $key => $value) {
        $ac_capital[$key]['total'] = @$ac_capital[$key]['total'] + (@$ac_capital[$key]['cr_total'] - @$ac_capital[$key]['dr_total']);
    }

    foreach ($capital as $key => $value) {
        $capital[$key]['total'] = (@$value['receipt_total'] - @$value['payment_total']) + (@$value['cr_total'] - @$value['dr_total']);
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
        $loan[$key]['total'] = (@$value['receipt_total'] - @$value['payment_total']) + (@$value['cr_total'] - @$value['dr_total']);
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
        $current_lib[$key]['total'] = (@$value['receipt_total'] - @$value['payment_total']) + (@$value['cr_total'] - @$value['dr_total']);
    }

    $current_lib['Sundry Creditors']['total'] = (($sale_purchase['pur_total_rate']) - ($sale_purchase['Purret_total_rate'])) + (@$current_lib['Sundry Creditors']['total']);

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
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['Sinv_total'] - @$ac_fixed_asset[$key]['Sret_total']);
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
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['Pinv_total'] - @$ac_fixed_asset[$key]['Pret_total']) + @$ac_fixed_asset[$key]['total'];
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
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['rec_total'] - @$ac_fixed_asset[$key]['pay_total']) + @$ac_fixed_asset[$key]['total'];
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
        $ac_fixed_asset[$key]['total'] = (@$ac_fixed_asset[$key]['dr_total'] - @$ac_fixed_asset[$key]['cr_total']) + (@$ac_fixed_asset[$key]['total']);
    }
    foreach ($fixed_asset as $key => $value) {
        $fixed_asset[$key]['total'] = (@$value['receipt_total'] - @$value['payment_total']) + (@$value['dr_total'] - @$value['cr_total']) + (@$value['Sinvoice_total'] - @$value['Sreturn_total']) + (@$value['Pinvoice_total'] - @$value['Preturn_total']);
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
        $current_asset[$key]['total'] = @$value['payment_total'] - @$value['receipt_total'] + @$value['dr_total'] - @$value['cr_total'];
    }

    $current_asset['Sundry Debtors']['total'] = ((@$sale_purchase['sale_total_rate']) - (@$sale_purchase['Saleret_total_rate']) + (@$current_asset['Sundry Debtors']['total']));

    // Fixed Assets Final Array //
    $data = array();
    $data = array_merge($data, $fixed_asset);
    // $bank_parti_fixAset = array_merge($bank_parti_fixAset,$jv_fixAset);
    $data['Fixed Assets']['data'] = $ac_fixed_asset;
    // End Fixed Assets Final Array //

    // Capital Final Array /
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
        'total' => - (@$duties_taxes['sale_net'] + @$duties_taxes['purchase_net']),
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
function pl_tot_data_bl($start_date = '', $end_date = '')
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
        $tot_pl_expense[$key]['total'] = @$value['general'] - @$value['return'] + @$value['jv_total'] + @$value['sale_brokrage'] + @$value['pur_brokrage'];
        $total_arr[] = @$value['general'] - @$value['return'] + @$value['jv_total'] + @$value['sale_brokrage'] + @$value['pur_brokrage'];
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
        $tot_pl_income[$key]['total'] = @$value['general'] - @$value['return'] + @$value['jv_total'];
        $total_ex_arr[] = @$value['general'] - @$value['return'] + @$value['jv_total'];
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

//capital data
function capital_data($id, $start_date = '', $end_date = '')
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
        if ($row['opening_type'] == 'Debit') {
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        } else {
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        }
        $tot_pg_income[$row['account_name']]['opening_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_pg_income[$row['account_name']]['type'] = 'capital';
    }

    $bank_income = array();
    //     echo '<pre>';Print_r($start_date);
    // echo '<pre>';Print_r($end_date);exit;

    

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
        if ($row['mode'] == 'Receipt') {
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        } else {
            $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }

        $tot_pg_income[$row['account_name']]['bt_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
         //use of gl_group summary
         $tot_pg_income[$row['account_name']]['type'] = 'capital';
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
        //use of gl_group summary
        $tot_pg_income[$row['account_name']]['type'] = 'capital';
    }

    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {
        $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
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
function get_capital_sub_grp_data($parent_id, $start_date = '', $end_date = '')
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

        if ($start_date != ''  && $end_date != '') {
            $category = capital_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_capital_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = capital_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_capital_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}
//loan data
function loans_data($id, $start_date = '', $end_date = '')
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
        if ($row['opening_type'] == 'Debit') {
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        } else {
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        }
        $tot_pg_income[$row['account_name']]['opening_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
         //use of gl_group summary
         $tot_pg_income[$row['account_name']]['type'] = 'loan';
    }


    $bank_income = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('bank_tras bt', 'bt.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0', 'bt.is_delete' => '0'));
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
        //use of gl_group summary
        $tot_pg_income[$row['account_name']]['type'] = 'loan';
    }
    // echo '<pre>';print_r($bank_income);
    $jv_income = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('jv_particular jv', 'jv.particular = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0', 'jv.is_delete' => '0'));
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
        //use of gl_group summary
        $tot_pg_income[$row['account_name']]['type'] = 'loan';
    }
    // echo '<pre>';Print_r($tot_pg_income);exit;





    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {

        $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['opening_total'];
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
function get_loans_sub_grp_data($parent_id, $start_date = '', $end_date = '')
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

        if ($start_date != ''  && $end_date != '') {
            $category = loans_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_loans_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = loans_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_loans_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}
//current liabilities data
// function Currlib_data($id, $start_date = '', $end_date = '')
// {

//     if ($start_date == '') {
//         if (date('m') < '03') {
//             $year = date('Y') - 1;
//             $start_date = $year . '-04-01';
//         } else {
//             $year = date('Y');
//             $start_date = $year . '-04-01';
//         }
//     }

//     if ($end_date == '') {

//         if (date('m') < '03') {
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

//     $tot_pg_income = array();

//     $account = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $query = $builder->get();
//     $account = $query->getResultArray();

//     foreach ($account as $row) {
//         if ($row['opening_type'] == 'Debit') {
//             $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
//         } else {
//             $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
//         }
//         $tot_pg_income[$row['account_name']]['opening_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         //use of gl_group summary
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }


//     $pg_expense = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('purchase_general pg', 'pg.party_account = ac.id');
//     $builder->join('purchase_particu pp', 'pp.parent_id = pg.id');
//     $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('pg.is_delete' => '0'));
//     $builder->where(array('pg.is_cancle' => '0'));
//     $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
//     $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
//     $builder->groupBy('pg.id');
//     $query = $builder->get();
//     $pg_expense = $query->getResultArray();


//     foreach ($pg_expense as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type']] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $bank_income = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
//     $builder->join('account ac', 'gl.id = ac.gl_group');
//     $builder->join('bank_tras bt', 'bt.particular = ac.id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('bt.is_delete' => '0'));
//     $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
//     $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
//     $query = $builder->get();
//     $bank_income = $query->getResultArray();

//     foreach ($bank_income as $row) {

//         if ($row['mode'] == 'Receipt') {
//             $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
//         } else {
//             $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
//         }

//         $tot_pg_income[$row['account_name']]['bt_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $jv_income = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('jv_particular jv', 'jv.particular = ac.id');
//     $builder->join('jv_main jm', 'jm.id = jv.jv_id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('jv.is_delete' => '0'));
//     $builder->where(array('jm.is_delete' => '0'));
//     $builder->where(array('DATE(jv.date)  >= ' => $start_date));
//     $builder->where(array('DATE(jv.date)  <= ' => $end_date));
//     $query = $builder->get();
//     $jv_income = $query->getResultArray();

//     foreach ($jv_income as $row) {

//         if ($row['dr_cr'] == 'cr') {
//             $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
//         } else {
//             $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
//         }

//         $tot_pg_income[$row['account_name']]['jv_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $purchase = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('purchase_invoice pi', 'pi.account = ac.id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('pi.is_delete' => '0'));
//     $builder->where(array('pi.is_cancle' => '0'));
//     $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
//     $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
//     $query = $builder->get();
//     $purchase = $query->getResultArray();

//     foreach ($purchase as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total']) ? $tot_pg_income[$row['account_name']]['purchase_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $purchase_return = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('purchase_return pi', 'pi.account = ac.id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
//     $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
//     $query = $builder->get();
//     $purchase_return = $query->getResultArray();

//     foreach ($purchase_return as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_ret_total']) ? $tot_pg_income[$row['account_name']]['purchase_ret_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_ret_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     // update trupti 26-12-2022 duties and taxes add taxes account
//     $data = gst_gl_group_data($id, $start_date, $end_date);

//     $pg_expense_igst = $data['pg_expense_igst'];
//     $pg_expense_cgst = $data['pg_expense_cgst'];
//     $pg_expense_sgst = $data['pg_expense_sgst'];

//     foreach ($pg_expense_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst'] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($pg_expense_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst'] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($pg_expense_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst'] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $sg_expense_igst = $data['sg_expense_igst'];
//     $sg_expense_cgst = $data['sg_expense_cgst'];
//     $sg_expense_sgst = $data['sg_expense_sgst'];
//     foreach ($sg_expense_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['sg_amount_igst'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sg_expense_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['sg_amount_cgst'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_cgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sg_expense_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['sg_amount_sgst'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $purchase_igst = $data['purchase_igst'];
//     $purchase_cgst = $data['purchase_cgst'];
//     $purchase_sgst = $data['purchase_sgst'];
//     foreach ($purchase_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_igst']) ? $tot_pg_income[$row['account_name']]['purchase_total_igst'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total_igst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_cgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_cgst'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total_cgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_sgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_sgst'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total_sgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $sales_igst = $data['sales_igst'];
//     $sales_cgst = $data['sales_cgst'];
//     $sales_sgst = $data['sales_sgst'];
//     foreach ($sales_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_igst_total'] : 0) + $row['sales_igst_total'];
//         $tot_pg_income[$row['account_name']]['sales_igst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_cgst_total'] : 0) + $row['sales_cgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_cgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_sgst_total'] : 0) + $row['sales_sgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_sgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $purchase_return_igst = $data['purchase_return_igst'];
//     $purchase_return_cgst = $data['purchase_return_cgst'];
//     $purchase_return_sgst = $data['purchase_return_sgst'];

//     foreach ($purchase_return_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_igst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_return_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_return_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $sales_return_igst = $data['sales_return_igst'];
//     $sales_return_cgst = $data['sales_return_cgst'];
//     $sales_return_sgst = $data['sales_return_sgst'];
//     foreach ($sales_return_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_return_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_igst_total'] : 0) + $row['sales_return_igst_total'];
//         $tot_pg_income[$row['account_name']]['sales_return_igst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_return_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] : 0) + $row['sales_return_cgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_return_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] : 0) + $row['sales_return_sgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//           //use of gl_group summary
//           $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }


//     $total_arr = array();

//     foreach ($tot_pg_income as $key => $value) {

//         $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total']  + @$value['general'] - @$value['return']
//             + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
//             - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
//             + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
//             - @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
//             + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
//             - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
//             + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
//             - @$value['purchase_return_igst_total'] - @$value['purchase_return_sgst_total'] - @$value['purchase_return_cgst_total'] + @$value['opening_total'];
//         $total_arr[] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total'] +  @$value['general'] - @$value['return']
//             + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
//             - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
//             + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
//             - @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
//             + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
//             - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
//             + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
//             - @$value['purchase_return_igst_total'] - @$value['purchase_return_sgst_total'] - @$value['purchase_return_cgst_total'] + @$value['opening_total'];
//     }

//     if (!empty($total_arr)) {
//         $trading_income_total = array_sum($total_arr);
//     } else {
//         $trading_income_total = 0;
//     }

//     $arr['account'] = $tot_pg_income;
//     $arr['total'] = $trading_income_total;

//     return $arr;
// }
// function Currlib_data($id, $start_date = '', $end_date = '')
// {

//     if ($start_date == '') {
//         if (date('m') < '03') {
//             $year = date('Y') - 1;
//             $start_date = $year . '-04-01';
//         } else {
//             $year = date('Y');
//             $start_date = $year . '-04-01';
//         }
//     }

//     if ($end_date == '') {

//         if (date('m') < '03') {
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

//     $tot_pg_income = array();

//     $account = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $query = $builder->get();
//     $account = $query->getResultArray();

//     foreach ($account as $row) {
//         if ($row['opening_type'] == 'Debit') {
//             $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
//         } else {
//             $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
//         }
//         $tot_pg_income[$row['account_name']]['opening_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }


//     $pg_expense = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('purchase_general pg', 'pg.party_account = ac.id');
//     $builder->join('purchase_particu pp', 'pp.parent_id = pg.id');
//     $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('pg.is_delete' => '0'));
//     $builder->where(array('pg.is_cancle' => '0'));
//     $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
//     $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
//     $builder->groupBy('pg.id');
//     $query = $builder->get();
//     $pg_expense = $query->getResultArray();


//     foreach ($pg_expense as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']]) ? $tot_pg_income[$row['account_name']][$row['pg_type']] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type']] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $bank_income = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.parent,gl.id as gl_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
//     $builder->join('account ac', 'gl.id = ac.gl_group');
//     $builder->join('bank_tras bt', 'bt.particular = ac.id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('bt.is_delete' => '0'));
//     $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
//     $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
//     $query = $builder->get();
//     $bank_income = $query->getResultArray();

//     foreach ($bank_income as $row) {

//         if ($row['mode'] == 'Receipt') {
//             $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
//         } else {
//             $total = ((@$tot_pg_income[$row['account_name']]['bt_total']) ? $tot_pg_income[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
//         }

//         $tot_pg_income[$row['account_name']]['bt_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $jv_income = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,jv.amount as total, ac.name as account_name,jv.dr_cr');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('jv_particular jv', 'jv.particular = ac.id');
//     $builder->join('jv_main jm', 'jm.id = jv.jv_id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('jv.is_delete' => '0'));
//     $builder->where(array('jm.is_delete' => '0'));
//     $builder->where(array('DATE(jv.date)  >= ' => $start_date));
//     $builder->where(array('DATE(jv.date)  <= ' => $end_date));
//     $query = $builder->get();
//     $jv_income = $query->getResultArray();

//     foreach ($jv_income as $row) {

//         if ($row['dr_cr'] == 'cr') {
//             $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
//         } else {
//             $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) - $row['total'];
//         }

//         $tot_pg_income[$row['account_name']]['jv_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $purchase = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('purchase_invoice pi', 'pi.account = ac.id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('pi.is_delete' => '0'));
//     $builder->where(array('pi.is_cancle' => '0'));
//     $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
//     $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
//     $query = $builder->get();
//     $purchase = $query->getResultArray();

//     foreach ($purchase as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total']) ? $tot_pg_income[$row['account_name']]['purchase_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $purchase_return = array();

//     $builder = $db->table('gl_group gl');
//     $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('purchase_return pi', 'pi.account = ac.id');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
//     $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
//     $query = $builder->get();
//     $purchase_return = $query->getResultArray();

//     foreach ($purchase_return as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_ret_total']) ? $tot_pg_income[$row['account_name']]['purchase_ret_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_ret_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

    
//     $builder = $db->table('gl_group gl');
//     $builder->select('gl.id as gl_id,gl.name,gl.parent,sg.v_type as sg_type,sg.party_account as sg_acc,ac.name as account_name,ac.id as account_id,sg.net_amount as sg_amount,sg.disc_type,sg.discount,sg.amty,sg.amty_type');
//     $builder->join('account ac', 'gl.id =ac.gl_group');
//     $builder->join('sales_ACinvoice sg', 'sg.party_account = ac.id');
//     $builder->join('sales_ACparticu sp', 'sp.parent_id = sg.id');
//     $builder->where('(sg.v_type="general" OR sg.v_type = "return")');
//     $builder->where(array('gl.id' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('sg.is_delete' => '0'));
//     $builder->where(array('sg.is_cancle' => '0'));
//     $builder->where(array('DATE(sg.invoice_date)  >= ' => $start_date));
//     $builder->where(array('DATE(sg.invoice_date)  <= ' => $end_date));
//     $builder->groupBy('sg.id');
//     $query = $builder->get();
//     $sale_general = $query->getResultArray();

//     foreach ($sale_general as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_'.$row['sg_type']]) ? $tot_pg_income[$row['account_name']]['sales_'.$row['sg_type']] : 0) + $row['sg_amount'];
//         $tot_pg_income[$row['account_name']]['sales_'.$row['sg_type']] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }


//     // update trupti 26-12-2022 duties and taxes add taxes account
//     $data = gst_gl_group_data($id, $start_date, $end_date);

//     $pg_expense_igst = $data['pg_expense_igst'];
//     $pg_expense_cgst = $data['pg_expense_cgst'];
//     $pg_expense_sgst = $data['pg_expense_sgst'];

//     foreach ($pg_expense_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst'] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities'; 
//     }
//     foreach ($pg_expense_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst'] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($pg_expense_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst'] : 0) + $row['pg_amount'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $sg_expense_igst = $data['sg_expense_igst'];
//     $sg_expense_cgst = $data['sg_expense_cgst'];
//     $sg_expense_sgst = $data['sg_expense_sgst'];


//     foreach ($sg_expense_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst'] : 0) + $row['sg_amount_igst'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sg_expense_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']. 'sales_cgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type']. 'sales_cgst'] : 0) + $row['sg_amount_cgst'];

//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_cgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
     
//     }
//     foreach ($sg_expense_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst'] : 0) + $row['sg_amount_sgst'];
//         $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }


//     $purchase_igst = $data['purchase_igst'];
//     $purchase_cgst = $data['purchase_cgst'];
//     $purchase_sgst = $data['purchase_sgst'];

//     foreach ($purchase_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_igst']) ? $tot_pg_income[$row['account_name']]['purchase_total_igst'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total_igst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_cgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_cgst'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total_cgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_sgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_sgst'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_total_sgst'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $sales_igst = $data['sales_igst'];
//     $sales_cgst = $data['sales_cgst'];
//     $sales_sgst = $data['sales_sgst'];
//     foreach ($sales_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_igst_total'] : 0) + $row['sales_igst_total'];
//         $tot_pg_income[$row['account_name']]['sales_igst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_cgst_total'] : 0) + $row['sales_cgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_cgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_sgst_total'] : 0) + $row['sales_sgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_sgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }

//     $purchase_return_igst = $data['purchase_return_igst'];
//     $purchase_return_cgst = $data['purchase_return_cgst'];
//     $purchase_return_sgst = $data['purchase_return_sgst'];

//     foreach ($purchase_return_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_igst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_return_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($purchase_return_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] : 0) + $row['total'];
//         $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     $sales_return_igst = $data['sales_return_igst'];
//     $sales_return_cgst = $data['sales_return_cgst'];
//     $sales_return_sgst = $data['sales_return_sgst'];
//     foreach ($sales_return_igst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_return_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_igst_total'] : 0) + $row['sales_return_igst_total'];
//         $tot_pg_income[$row['account_name']]['sales_return_igst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_return_cgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] : 0) + $row['sales_return_cgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }
//     foreach ($sales_return_sgst as $row) {

//         $total = ((@$tot_pg_income[$row['account_name']]['sales_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] : 0) + $row['sales_return_sgst_total'];
//         $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] = $total;
//         $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
//         $tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
//     }


//     $total_arr = array();

//     foreach ($tot_pg_income as $key => $value) {

//         $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total']  
//             + @$value['general'] - @$value['return'] - @$value['sales_general'] + @$value['sales_return'] 
//             + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
//             - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
//             + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
//             - @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
//             + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
//             - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
//             + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
//             - @$value['purchase_return_igst_total'] - @$value['purchase_return_sgst_total'] - @$value['purchase_return_cgst_total']+ @$value['opening_total'];

//         $total_arr[] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total'] 
//             +  @$value['general'] - @$value['return'] - @$value['sales_general'] + @$value['sales_return'] 
//             + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
//             - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
//             + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
//             - @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
//             + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
//             - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
//             + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
//             - @$value['purchase_return_igst_total'] - @$value['purchase_return_sgst_total'] - @$value['purchase_return_cgst_total'] + @$value['opening_total'];
//     }

//     if (!empty($total_arr)) {
//         $trading_income_total = array_sum($total_arr);
//     } else {
//         $trading_income_total = 0;
//     }

//     $arr['account'] = $tot_pg_income;
//     $arr['total'] = $trading_income_total;

//     return $arr;
// }
function Currlib_data($id, $start_date = '', $end_date = '')
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
        if ($row['opening_type'] == 'Debit') {
            $total = ((@$tot_pg_income[$row['account_name']]['opening_total']) ? $tot_pg_income[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        } else {
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
    //$builder->join('purchase_particu pp', 'pp.parent_id = pg.id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
   // $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense = $query->getResultArray();


    foreach ($pg_expense as $row) {

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

        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_pg_income[$row['account_name']]['jv_total']) ? $tot_pg_income[$row['account_name']]['jv_total'] : 0) + $row['total'];
        } else {
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

    foreach ($purchase as $row) {

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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();

    foreach ($purchase_return as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_ret_total']) ? $tot_pg_income[$row['account_name']]['purchase_ret_total'] : 0) + $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_ret_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,sg.v_type as sg_type,sg.party_account as sg_acc,ac.name as account_name,ac.id as account_id,sg.net_amount as sg_amount,sg.disc_type,sg.discount,sg.amty,sg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice sg', 'sg.party_account = ac.id');
    //$builder->join('sales_ACparticu sp', 'sp.parent_id = sg.id');
    $builder->where('(sg.v_type="general" OR sg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sg.is_delete' => '0'));
    $builder->where(array('sg.is_cancle' => '0'));
    $builder->where(array('DATE(sg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sg.invoice_date)  <= ' => $end_date));
    $builder->groupBy('sg.id');
    $query = $builder->get();
    $sale_general = $query->getResultArray();

    foreach ($sale_general as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_'.$row['sg_type']]) ? $tot_pg_income[$row['account_name']]['sales_'.$row['sg_type']] : 0) + $row['sg_amount'];
        $tot_pg_income[$row['account_name']]['sales_'.$row['sg_type']] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
        //$tot_pg_income[$row['account_name']]['type'] = 'current liabilities';
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

    foreach ($sales as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_total']) ? $tot_pg_income[$row['account_name']]['sales_total'] : 0) + $row['total'];
        $tot_pg_income[$row['account_name']]['sales_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    $sales_return = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pi', 'pi.account = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    foreach ($sales_return as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_ret_total']) ? $tot_pg_income[$row['account_name']]['sales_ret_total'] : 0) + $row['total'];
        $tot_pg_income[$row['account_name']]['sales_ret_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    // update trupti 26-12-2022 duties and taxes add taxes account
    $data = gst_gl_group_data($id, $start_date, $end_date);

    $pg_expense_igst = $data['pg_expense_igst'];
    $pg_expense_cgst = $data['pg_expense_cgst'];
    $pg_expense_sgst = $data['pg_expense_sgst'];

    foreach ($pg_expense_igst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst'] : 0) - $row['pg_amount'];
        $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_igst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($pg_expense_cgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst'] : 0) - $row['pg_amount'];
        $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_cgst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($pg_expense_sgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst'] : 0) - $row['pg_amount'];
        $tot_pg_income[$row['account_name']][$row['pg_type'] . 'purchase_sgst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $sg_expense_igst = $data['sg_expense_igst'];
    $sg_expense_cgst = $data['sg_expense_cgst'];
    $sg_expense_sgst = $data['sg_expense_sgst'];


    foreach ($sg_expense_igst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst'] : 0) + $row['sg_amount_igst'];
        $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_igst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($sg_expense_cgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type']. 'sales_cgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type']. 'sales_cgst'] : 0) + $row['sg_amount_cgst'];

        $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_cgst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
     
    }
   
    foreach ($sg_expense_sgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst']) ? $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst'] : 0) + $row['sg_amount_sgst'];
        $tot_pg_income[$row['account_name']][$row['pg_type'] . 'sales_sgst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }


    $purchase_igst = $data['purchase_igst'];
    $purchase_cgst = $data['purchase_cgst'];
    $purchase_sgst = $data['purchase_sgst'];

    foreach ($purchase_igst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_igst']) ? $tot_pg_income[$row['account_name']]['purchase_total_igst'] : 0) - $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_total_igst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($purchase_cgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_cgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_cgst'] : 0) - $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_total_cgst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($purchase_sgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_total_sgst']) ? $tot_pg_income[$row['account_name']]['purchase_total_sgst'] : 0) - $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_total_sgst'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    $sales_igst = $data['sales_igst'];
    $sales_cgst = $data['sales_cgst'];
    $sales_sgst = $data['sales_sgst'];
    foreach ($sales_igst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_igst_total'] : 0) + $row['sales_igst_total'];
        $tot_pg_income[$row['account_name']]['sales_igst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($sales_cgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_cgst_total'] : 0) + $row['sales_cgst_total'];
        $tot_pg_income[$row['account_name']]['sales_cgst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($sales_sgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_sgst_total'] : 0) + $row['sales_sgst_total'];
        $tot_pg_income[$row['account_name']]['sales_sgst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }

    $purchase_return_igst = $data['purchase_return_igst'];
    $purchase_return_cgst = $data['purchase_return_cgst'];
    $purchase_return_sgst = $data['purchase_return_sgst'];

    foreach ($purchase_return_igst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_igst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] : 0) + $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_return_igst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($purchase_return_cgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] : 0) + $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_return_cgst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($purchase_return_sgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['purchase_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] : 0) + $row['total'];
        $tot_pg_income[$row['account_name']]['purchase_return_sgst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    $sales_return_igst = $data['sales_return_igst'];
    $sales_return_cgst = $data['sales_return_cgst'];
    $sales_return_sgst = $data['sales_return_sgst'];
    foreach ($sales_return_igst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_return_igst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_igst_total'] : 0) - $row['sales_return_igst_total'];
        $tot_pg_income[$row['account_name']]['sales_return_igst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($sales_return_cgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_return_cgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] : 0) - $row['sales_return_cgst_total'];
        $tot_pg_income[$row['account_name']]['sales_return_cgst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }
    foreach ($sales_return_sgst as $row) {

        $total = ((@$tot_pg_income[$row['account_name']]['sales_return_sgst_total']) ? $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] : 0) - $row['sales_return_sgst_total'];
        $tot_pg_income[$row['account_name']]['sales_return_sgst_total'] = $total;
        $tot_pg_income[$row['account_name']]['account_id'] = $row['account_id'];
    }


    $total_arr = array();

    foreach ($tot_pg_income as $key => $value) {

        $tot_pg_income[$key]['total'] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total'] - @$value['sales_total'] + @$value['sales_ret_total'] 
            + @$value['general'] - @$value['return'] - @$value['sales_general'] + @$value['sales_return']
            + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
            - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
            + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
            + @$value['sales_return_igst_total'] - @$value['sales_return_sgst_total'] - @$value['sales_return_cgst_total']
            + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
            - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
            + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
            + @$value['purchase_return_igst_total'] + @$value['purchase_return_sgst_total'] + @$value['purchase_return_cgst_total']+ @$value['opening_total'];

        $total_arr[] = @$value['jv_total'] + @$value['bt_total']  + @$value['purchase_total'] - @$value['purchase_ret_total'] - @$value['sales_total'] + @$value['sales_ret_total'] 
            +  @$value['general'] - @$value['return'] - @$value['sales_general'] + @$value['sales_return']
            + @$value['generalsales_igst'] + @$value['generalsales_cgst'] + @$value['generalsales_sgst']
            - @$value['returnsales_igst'] - @$value['returnsales_cgst'] - @$value['returnsales_sgst']
            + @$value['sales_igst_total'] + @$value['sales_sgst_total'] + @$value['sales_cgst_total']
            + @$value['sales_return_igst_total'] + @$value['sales_return_sgst_total'] + @$value['sales_return_cgst_total']
            + @$value['generalpurchase_igst'] + @$value['generalpurchase_sgst'] + @$value['generalpurchase_cgst']
            - @$value['returnpurchase_igst'] - @$value['returnpurchase_sgst'] - @$value['returnpurchase_cgst']
            + @$value['purchase_total_igst'] + @$value['purchase_total_sgst'] + @$value['purchase_total_cgst']
            + @$value['purchase_return_igst_total'] + @$value['purchase_return_sgst_total'] + @$value['purchase_return_cgst_total'] + @$value['opening_total'];
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
function get_Currlib_sub_grp_data($parent_id, $start_date = '', $end_date = '')
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

        if ($start_date != ''  && $end_date != '') {
            $category = Currlib_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_Currlib_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = Currlib_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_Currlib_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}
//fixed assets data
function Fixed_Assets_data($id, $start_date = '', $end_date = '')
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
        if ($row['opening_type'] == 'Debit') {
            $total = ((@$tot_fixedassets[$row['account_name']]['opening_total']) ? $tot_fixedassets[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        } else {
            $total = ((@$tot_fixedassets[$row['account_name']]['opening_total']) ? $tot_fixedassets[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        }
        $tot_fixedassets[$row['account_name']]['opening_total'] = $total;
        $tot_fixedassets[$row['account_name']]['account_id'] = $row['account_id'];
          //use of gl_group summary
          $tot_pg_income[$row['account_name']]['type'] = 'fixed assets';
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
        if ($row['mode'] == 'Payment') {
            $total = ((@$tot_fixedassets[$row['account_name']]['bt_total']) ? $tot_fixedassets[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        } else {
            $total = ((@$tot_fixedassets[$row['account_name']]['bt_total']) ? $tot_fixedassets[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }
        $tot_fixedassets[$row['account_name']]['bt_total'] = $total;
        $tot_fixedassets[$row['account_name']]['account_id'] = $row['account_id'];
         //use of gl_group summary
         $tot_fixedassets[$row['account_name']]['type'] = 'fixed assets';
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

    foreach ($jv_FixedAssets as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_fixedassets[$row['account_name']]['jv_total']) ? $tot_fixedassets[$row['account_name']]['jv_total'] : 0) - $row['total'];
        } else {
            $total = ((@$tot_fixedassets[$row['account_name']]['jv_total']) ? $tot_fixedassets[$row['account_name']]['jv_total'] : 0) + $row['total'];
        }
        $tot_fixedassets[$row['account_name']]['bt_total'] = $total;
        $tot_fixedassets[$row['account_name']]['account_id'] = $row['account_id'];
         //use of gl_group summary
         $tot_fixedassets[$row['account_name']]['type'] = 'fixed assets';
        
    }
    
    $sales_FixedAssets = array();
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,sa.type as type,sa.sub_total,sa.added_amt,gl.name as gl_name, ac.name as account_name');
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

    foreach ($sales_FixedAssets as $row) {
        $total1 = $row['sub_total'] + $row['added_amt'];
        if ($row['type'] == 'general') {
            $gen_total = ((@$tot_fixedassets[$row['account_name']]['general']) ? $tot_fixedassets[$row['account_name']]['general'] : 0) + $total1;
        } else {
            $ret_total = ((@$tot_fixedassets[$row['account_name']]['return']) ? $tot_fixedassets[$row['account_name']]['return'] : 0) - $total1;
        }
        $total =  @$gen_total - @$ret_total;
        $tot_fixedassets[$row['account_name']]['sale_total'] = $total;
        $tot_fixedassets[$row['account_name']]['account_id'] = @$row['account_id'];
           //use of gl_group summary
           $tot_fixedassets[$row['account_name']]['type'] = 'fixed assets';
    }
   
    $purchase_FixedAssets = array();
    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,pg.type as type,pg.sub_total,pg.added_amt,gl.name as gl_name, ac.name as account_name');
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
    //echo '<pre>';Print_r($purchase_FixedAssets);exit;
    //echo $db->getLastQuery();exit;

   
    foreach ($purchase_FixedAssets as $row) {
        $total =0;
        $total1 = $row['sub_total'] + $row['added_amt'];
        if ($row['type'] == 'general') {

            $total += $total1;
        } else {
            $total -= $total1;
        }
        //$total = $gen_total - (@$ret_total ? @$ret_total : 0);
        $tot_fixedassets[$row['account_name']]['purchase_total'] = $total;
        $tot_fixedassets[$row['account_name']]['account_id'] = @$row['account_id'];
           //use of gl_group summary
           $tot_fixedassets[$row['account_name']]['type'] = 'fixed assets';
    }
    //echo '<pre>';Print_r($tot_fixedassets);exit;
    $total_arr = array();
   
    
    foreach ($tot_fixedassets as $key => $value) {
        $tot_fixedassets[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'] + @$value['opening_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'] + @$value['opening_total'];
    }

    if (!empty($total_arr)) {
        $fixed_assets_total = array_sum($total_arr);
    } else {
        $fixed_assets_total = 0;
    }

    $arr['account'] = $tot_fixedassets;
    $arr['total'] = $fixed_assets_total;

    return $arr;
}
function get_FixedAssets_sub_grp_data($parent_id, $start_date = '', $end_date = '')
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

        if ($start_date != ''  && $end_date != '') {
            $category = Fixed_Assets_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_FixedAssets_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = Fixed_Assets_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_FixedAssets_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}
//current assets data
function Current_Assets_data($id, $start_date = '', $end_date = '')
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
    $tot_currentassets = array();

    $account = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent, ac.name as account_name,opening_bal as opening_total,opening_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $query = $builder->get();
    $account = $query->getResultArray();

    foreach ($account as $row) {
        if ($row['opening_type'] == 'Debit') {
            $total = ((@$tot_currentassets[$row['account_name']]['opening_total']) ? $tot_currentassets[$row['account_name']]['opening_total'] : 0) + (float)$row['opening_total'];
        } else {
            $total = ((@$tot_currentassets[$row['account_name']]['opening_total']) ? $tot_currentassets[$row['account_name']]['opening_total'] : 0) - (float)$row['opening_total'];
        }
        $tot_currentassets[$row['account_name']]['opening_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
           //use of gl_group summary
           $tot_currentassets[$row['account_name']]['type'] = 'current assets';
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,sg.v_type as sg_type,sg.party_account as sg_acc,ac.name as account_name,ac.id as account_id,sg.net_amount as sg_amount,sg.disc_type,sg.discount,sg.amty,sg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice sg', 'sg.party_account = ac.id');
   // $builder->join('sales_ACparticu sp', 'sp.parent_id = sg.id');
    $builder->where('(sg.v_type="general" OR sg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('sg.is_delete' => '0'));
    $builder->where(array('sg.is_cancle' => '0'));
    $builder->where(array('DATE(sg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sg.invoice_date)  <= ' => $end_date));
    //$builder->groupBy('sg.id');
    $query = $builder->get();
    $sale_gnrl_current_asset = $query->getResultArray();

    foreach ($sale_gnrl_current_asset as $row) {

        $total = ((@$tot_currentassets[$row['account_name']][$row['sg_type']]) ? $tot_currentassets[$row['account_name']][$row['sg_type']] : 0) + $row['sg_amount'];
        $tot_currentassets[$row['account_name']][$row['sg_type']] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
    }

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.party_account = ac.id');
    //$builder->join('purchase_particu pp', 'pp.parent_id = pg.id');
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
    $query = $builder->get();
    $purchase_gnrl_current_asset = $query->getResultArray();

    foreach ($purchase_gnrl_current_asset as $row) {

        $total = ((@$tot_currentassets[$row['account_name']]['purchase'.$row['pg_type']]) ? $tot_currentassets[$row['account_name']]['purchase'.$row['pg_type']] : 0) + $row['pg_amount'];
        $tot_currentassets[$row['account_name']]['purchase'.$row['pg_type']] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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


    $total = 0;
    foreach ($purchase as $row) {


        $total = ((@$tot_currentassets[$row['account_name']]['purchase_total']) ? $tot_currentassets[$row['account_name']]['purchase_total'] : 0) + $row['total'];
        $tot_currentassets[$row['account_name']]['purchase_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
    }


    $sales_return = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pi', 'pi.account = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    foreach ($sales_return as $row) {


        $total = ((@$tot_currentassets[$row['account_name']]['sales_ret_total']) ? $tot_currentassets[$row['account_name']]['sales_ret_total'] : 0) + $row['total'];
        $tot_currentassets[$row['account_name']]['sales_ret_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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

        if ($row['mode'] == 'Payment') {
            $total = ((@$tot_currentassets[$row['account_name']]['bt_total']) ? (float)$tot_currentassets[$row['account_name']]['bt_total'] : 0) + $row['total'];
        } else {
            $total = ((@$tot_currentassets[$row['account_name']]['bt_total']) ? (float)$tot_currentassets[$row['account_name']]['bt_total'] : 0) - $row['total'];
        }

        $tot_currentassets[$row['account_name']]['bt_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = @$row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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

    foreach ($jv_CurrentAssets as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_currentassets[$row['account_name']]['jv_total']) ? $tot_currentassets[$row['account_name']]['jv_total'] : 0) - $row['total'];
        } else {
            $total = ((@$tot_currentassets[$row['account_name']]['jv_total']) ? $tot_currentassets[$row['account_name']]['jv_total'] : 0) + $row['total'];
        }
        $tot_currentassets[$row['account_name']]['jv_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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

        if ($row['mode'] == 'Payment') {
            $total = ((@$tot_currentassets[$row['account_name']]['ac_total']) ? $tot_currentassets[$row['account_name']]['ac_total'] : 0) - $row['total'];
        } else {
            $total = ((@$tot_currentassets[$row['account_name']]['ac_total']) ? $tot_currentassets[$row['account_name']]['ac_total'] : 0) + $row['total'];
        }

        $tot_currentassets[$row['account_name']]['ac_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = @$row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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
    foreach ($sales as $row) {


        $total = ((@$tot_currentassets[$row['account_name']]['sales_total']) ? $tot_currentassets[$row['account_name']]['sales_total'] : 0) + $row['total'];
        $tot_currentassets[$row['account_name']]['sales_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
    }


    $sales_return = array();

    $builder = $db->table('gl_group gl');
    $builder->select('ac.id as account_id,gl.name as gl_name,gl.id as gl_id,gl.parent,pi.net_amount as total, ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_return pi', 'pi.account = ac.id');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    foreach ($sales_return as $row) {


        $total = ((@$tot_currentassets[$row['account_name']]['sales_ret_total']) ? $tot_currentassets[$row['account_name']]['sales_ret_total'] : 0) + $row['total'];
        $tot_currentassets[$row['account_name']]['sales_ret_total'] = $total;
        $tot_currentassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
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
        //use of gl_group summary
        $tot_currentassets[$row['account_name']]['type'] = 'current assets';
    }

    $total_arr = array();

    foreach ($tot_currentassets as $key => $value) {
        $tot_currentassets[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['ac_total'] + @$value['sales_total'] - @$value['sales_ret_total'] + @$value['contra_ac_total'] + @$value['contra_total'] + @$value['general'] - @$value['return'] - @$value['purchase_general'] + @$value['purchase_return'] + @$value['opening_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['ac_total'] + @$value['sales_total'] - @$value['sales_ret_total'] + @$value['contra_ac_total'] + @$value['contra_total'] + @$value['general'] - @$value['return'] - @$value['purchase_general'] + @$value['purchase_return'] + @$value['opening_total'];
    }

    if (!empty($total_arr)) {
        $current_assets_total = array_sum($total_arr);
    } else {
        $current_assets_total = 0;
    }

    $arr['account'] = $tot_currentassets;
    $arr['total'] = $current_assets_total;
   // echo '<pre>';Print_r($arr);exit;
    

    return $arr;
}
function get_CurrentAssets_sub_grp_data($parent_id, $start_date = '', $end_date = '')
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

        if ($start_date != ''  && $end_date != '') {
            $category = Current_Assets_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_CurrentAssets_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = Current_Assets_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_CurrentAssets_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return $categories;
}
//other assets data
function Other_Assets_data($id, $start_date = '', $end_date = '')
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
    $bank_OtherAssets = array();
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

        if ($row['mode'] == 'Payment') {
            $total = ((@$tot_otherassets[$row['account_name']]['bt_total']) ? $tot_otherassets[$row['account_name']]['bt_total'] : 0) + $row['bt_total'];
        } else {
            $total = ((@$tot_otherassets[$row['account_name']]['bt_total']) ? $tot_otherassets[$row['account_name']]['bt_total'] : 0) - $row['bt_total'];
        }
        $tot_otherassets[$row['account_name']]['bt_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_otherassets[$row['account_name']]['type'] = 'other assets';
    }

    $jv_OtherAssets = array();
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

    foreach ($jv_OtherAssets as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total = ((@$tot_otherassets[$row['account_name']]['jv_total']) ? $tot_otherassets[$row['account_name']]['jv_total'] : 0) + $row['total'];
        } else {
            $total = ((@$tot_otherassets[$row['account_name']]['jv_total']) ? $tot_otherassets[$row['account_name']]['jv_total'] : 0) - $row['total'];
        }
        $tot_otherassets[$row['account_name']]['jv_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = $row['account_id'];
        //use of gl_group summary
        $tot_otherassets[$row['account_name']]['type'] = 'other assets';
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

    foreach ($sales_OtherAssets as $row) {
        if ($row['type'] == 'general') {
            $gen_total = ((@$tot_otherassets[$row['account_name']]['general']) ? $tot_otherassets[$row['account_name']]['general'] : 0) + $row['total'];
        } else {
            $ret_total = ((@$tot_otherassets[$row['account_name']]['return']) ? $tot_otherassets[$row['account_name']]['return'] : 0) - $row['total'];
        }
        $total =  @$gen_total - @$ret_total;
        $tot_otherassets[$row['account_name']]['sale_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = @$row['account_id'];
        //use of gl_group summary
        $tot_otherassets[$row['account_name']]['type'] = 'other assets';
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

    foreach ($purchase_OtherAssets as $row) {
        if ($row['type'] == 'general') {
            $gen_total = ((@$tot_otherassets[$row['account_name']]['general']) ? $tot_otherassets[$row['account_name']]['general'] : 0) + $row['total'];
        } else {
            $ret_total = ((@$tot_otherassets[$row['account_name']]['return']) ? $tot_otherassets[$row['account_name']]['return'] : 0) - $row['total'];
        }
        $total = $gen_total - $ret_total;
        $tot_otherassets[$row['account_name']]['purchase_total'] = $total;
        $tot_otherassets[$row['account_name']]['account_id'] = @$row['account_id'];
        //use of gl_group summary
        $tot_otherassets[$row['account_name']]['type'] = 'other assets';
    }

    $total_arr = array();

    foreach ($tot_otherassets as $key => $value) {
        $tot_otherassets[$key]['total'] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'];
        $total_arr[] = @$value['jv_total'] + @$value['bt_total'] + @$value['sale_total'] + @$value['purchase_total'];
    }

    if (!empty($total_arr)) {
        $other_assets_total = array_sum($total_arr);
    } else {
        $other_assets_total = 0;
    }

    $arr['account'] = $tot_otherassets;
    $arr['total'] = $other_assets_total;

    return $arr;
}
function get_OtherAssets_sub_grp_data($parent_id, $start_date = '', $end_date = '')
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

        if ($start_date != ''  && $end_date != '') {
            $category = Other_Assets_data($mainCategory->id, $start_date, $end_date);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_OtherAssets_sub_grp_data($mainCategory->id, $start_date, $end_date);
        } else {
            $category = Other_Assets_data($mainCategory->id);
            $category['name'] = $mainCategory->name;
            $category['sub_categories'] = get_OtherAssets_sub_grp_data($mainCategory->id);
        }

        $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}
function gst_gl_group_data($id, $start_date, $end_date)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    // purchase general
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_igst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.igst_acc = ac.id', 'left');
    //$builder->join('purchase_particu pp', 'pp.parent_id = pg.id', "left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_cgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.cgst_acc = ac.id', 'left');
    //$builder->join('purchase_particu pp', 'pp.parent_id = pg.id', "left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_sgst as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('purchase_general pg', 'pg.sgst_acc = ac.id', 'left');
    //$builder->join('purchase_particu pp', 'pp.parent_id = pg.id', "left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expense_sgst = $query->getResultArray();

    //sales general
    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_igst as sg_amount_igst,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.igst_acc = ac.id', 'left');
    //$builder->join('sales_ACparticu pp', 'pp.parent_id = pg.id', "left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
    $query = $builder->get();
    $sg_expense_igst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_cgst as sg_amount_cgst,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.cgst_acc = ac.id', 'left');
    //$builder->join('sales_ACparticu pp', 'pp.parent_id = pg.id', "left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
    $query = $builder->get();
    $sg_expense_cgst = $query->getResultArray();

    $builder = $db->table('gl_group gl');
    $builder->select('gl.id as gl_id,gl.name,gl.parent,pg.v_type as pg_type,pg.party_account as pg_acc,ac.name as account_name,ac.id as account_id,pg.tot_sgst as sg_amount_sgst,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'gl.id =ac.gl_group');
    $builder->join('sales_ACinvoice pg', 'pg.sgst_acc = ac.id', 'left');
    //$builder->join('sales_ACparticu pp', 'pp.parent_id = pg.id', "left");
    $builder->where('(pg.v_type="general" OR pg.v_type = "return")');
    $builder->where(array('gl.id' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
    //$builder->groupBy('pg.id');
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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
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
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
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
//****************************start capital account*********************************//
function get_capital_account_wise($start_date, $end_date, $id)
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

    $capital = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account', array('id' => $id), 'opening_bal,opening_type');

    $capital['opening']['total'] = 0;

    if ($acc['opening_type'] == 'Debit') {
        $capital['opening']['total'] -= (float)@$acc['opening_bal'];
    } else {
        $capital['opening']['total'] += (float)@$acc['opening_bal'];
    }

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_expence = $query->getResultArray();

    $capital['bank_trans']['total'] = 0;

    $total = 0;
    foreach ($bank_expence as $row) {
        if ($row['mode'] == 'Payment') {
            $total -= $row['bt_total'];
        } else {
            $total += $row['bt_total'];
        }
    }
    $capital['bank_trans']['total'] = $total;
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

    $capital['jv_parti']['total'] = 0;
    $total = 0;

    foreach ($jv_expence as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total += $row['total'];
        } else {
            $total -= $row['total'];
        }
    }
    $capital['jv_parti']['total'] += $total;


    $capital['from'] = $start_date;
    $capital['to'] = $end_date;
    $capital['id'] = $id;
    return $capital;
}
function get_loan_account_wise($start_date, $end_date, $id)
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

    $loan = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account', array('id' => $id), 'opening_bal,opening_type');

    $loan['opening']['total'] = 0;

    if ($acc['opening_type'] == 'Debit') {
        $loan['opening']['total'] -= (float)@$acc['opening_bal'];
    } else {
        $loan['opening']['total'] += (float)@$acc['opening_bal'];
    }

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0', 'bt.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_expence = $query->getResultArray();

    $loan['bank_trans']['total'] = 0;

    $total = 0;
    foreach ($bank_expence as $row) {

        if ($row['mode'] == 'Payment') {
            $total -= $row['bt_total'];
        } else {
            $total += $row['bt_total'];
        }
    }
    $loan['bank_trans']['total'] = $total;

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


    $loan['jv_parti']['total'] = 0;
    $total = 0;

    foreach ($jv_expence as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total += $row['total'];
        } else {
            $total -= $row['total'];
        }
    }
    $loan['jv_parti']['total'] += $total;


    $loan['from'] = $start_date;
    $loan['to'] = $end_date;
    $loan['id'] = $id;

    return $loan;
}
function get_current_lib_account_wise($start_date, $end_date, $id)
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

    $current_lib = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account', array('id' => $id), 'opening_bal,opening_type');

    $current_lib['opening']['total'] = 0;

    if ($acc['opening_type'] == 'Debit') {
        $current_lib['opening']['total'] -= (float)@$acc['opening_bal'];
    } else {
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
    $builder->join('account ac', 'ac.id =' . $id);
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

        if ($row['mode'] == 'Payment') {
            $total -= $row['bt_total'];
        } else {
            $total += $row['bt_total'];
        }
    }
    $current_lib['bank_trans']['total'] = $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->join('jv_main jm', 'jm.id = jv.jv_id');
    $builder->where('jv.particular', $id);
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
        if ($row['dr_cr'] == 'cr') {
            $total += $row['total'];
        } else {
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

    foreach ($purchase as $row) {
      
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

    foreach ($purchase_return as $row) {

        $total += $row['total'];
    }
    @$current_lib['purchase_return']['total'] -= $total;

    $builder = $db->table('sales_ACinvoice pg');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,ac.name as account_name,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pg.party_account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_income = $query->getResultArray();
    foreach ($pg_income as $row) {

        $total = (((float) @$current_lib['general_sales'][$row['pg_type']]) ? (float) $current_lib['general_sales'][$row['pg_type']] : 0) + (float) $row['pg_amount'];

        $current_lib['general_sales'][$row['pg_type']] = $total;

        $current_lib['general_sales']['total'] = (float)@$current_lib['general_sales']['return'] - (float)@$current_lib['general_sales']['general'];
    }


    // update trupti 26-12-2022 duties and taxes add taxes account
    $gst_data = gst_account_data($id, $start_date, $end_date);
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

    $current_lib['from'] = $start_date;
    $current_lib['to'] = $end_date;
    $current_lib['id'] = $id;



    return $current_lib;
}
function gst_account_data($id, $start_date, $end_date)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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
        if ($row['pg_type'] == 'general') {
            $total += (float) $row['pg_amount'];
        } else {
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
        if ($row['pg_type'] == 'general') {
            $total += (float) $row['pg_amount'];
        } else {
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
        if ($row['pg_type'] == 'general') {
            $total += (float) $row['pg_amount'];
        } else {
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

    foreach ($sales_igst as $row) {

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

    foreach ($sales_cgst as $row) {

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

    foreach ($sales_sgst as $row) {

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

    foreach ($sales_return_igst as $row) {

        $total += $row['total'];
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

    foreach ($sales_return_cgst as $row) {

        $total += $row['total'];
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

    foreach ($sales_return_sgst as $row) {

        $total += $row['total'];
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
        if ($row['pg_type'] == 'general') {
            $total += (float) $row['pg_amount'];
        } else {
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
        if ($row['pg_type'] == 'general') {
            $total += (float) $row['pg_amount'];
        } else {
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
        if ($row['pg_type'] == 'general') {
            $total += (float) $row['pg_amount'];
        } else {
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

    foreach ($purchase_igst as $row) {

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

    foreach ($purchase_cgst as $row) {

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

    foreach ($purchase_sgst as $row) {

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

    foreach ($purchase_return_igst as $row) {

        $total -= $row['total'];
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

    foreach ($purchase_return_cgst as $row) {

        $total -= $row['total'];
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

    foreach ($purchase_return_sgst as $row) {

        $total -= $row['total'];
    }
    @$current_lib['purchase_return_sgst']['total'] = $total;
    return $current_lib;
}
function get_purchase_monthly($start_date, $end_date, $id)
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
function get_purchase_ret_monthly($start_date, $end_date, $id)
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

    $builder = $db->table('purchase_return pi');
    $builder->select('MONTH(pi.return_date) as month,YEAR(pi.return_date) as year,ac.id as account_id,ac.name as account_name,pi.net_amount as pi_total');
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
function get_sales_monthly($start_date, $end_date, $id)
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
function get_sales_ret_monthly($start_date, $end_date, $id)
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

        $tot_expence['generalSales'][$row['month']]['total'] = (float)@$tot_expence['generalSales'][$row['month']]['return'] - (float) @$tot_expence['generalSales'][$row['month']]['general'];
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
function get_currentassets_account_wise($start_date, $end_date, $id)
{

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

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $tot_currentassets = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account', array('id' => $id), 'opening_bal,opening_type');

    $tot_currentassets['opening']['total'] = 0;

    if ($acc['opening_type'] == 'Debit') {
        $tot_currentassets['opening']['total'] += (float)@$acc['opening_bal'];
    } else {
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

        if ($row['pg_type'] == 'general') {
            $genral_total += $row['pg_amount'];
        } else {
            $return_total += $row['pg_amount'];
        }
    }

    @$tot_currentassets['general_sales']['total'] += $genral_total;
    @$tot_currentassets['general_sales_return']['total'] += $return_total;


    $builder = $db->table('purchase_general pg');
    $builder->select('pg.id as voucher_id,pg.v_type as pg_type,ac.name as account_name,pg.net_amount as pg_amount,pg.disc_type,pg.discount,pg.amty,pg.amty_type');
    $builder->join('account ac', 'ac.id = ' . $id);
    $builder->where(array('pg.party_account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('pg.is_cancle' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $builder->groupBy('pg.id');
    $query = $builder->get();
    $pg_expence = $query->getResultArray();


    $total = 0;
    foreach ($pg_expence as $row) {

        if ($row['pg_type'] == 'general') {
            $total -= $row['pg_amount'];
        } else {
            $total += $row['pg_amount'];
        }
    }

    @$tot_currentassets['general_purchase']['total'] += $total;
   

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
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
        if ($row['mode'] == 'Payment') {
            $total += $row['bt_total'];
        } else {
            $total -= $row['bt_total'];
        }
    }

    $tot_currentassets['per_bank_trans']['total'] += $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->join('jv_main jm', 'jm.id = jv.jv_id');
    $builder->where('jv.particular', $id);
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
        if ($row['dr_cr'] == 'cr') {
            $total -= $row['total'];
        } else {
            $total += $row['total'];
        }
    }

    $tot_currentassets['jv_currentassets']['total'] += $total;

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
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
        if ($row['mode'] == 'Payment') {
            $tot_currentassets['ac_bank_trans']['total'] -= $row['bt_total'];
        } else {
            $tot_currentassets['ac_bank_trans']['total'] += $row['bt_total'];
        }
    }

    $total = 0;

    $builder = $db->table('sales_invoice pi');
    $builder->select('ac.id as account_id,pi.net_amount as total,ac.name as account_name,pi.amty,pi.amty_type,pi.discount,pi.disc_type');
    $builder->join('account ac', 'ac.id = pi.account', 'left');
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
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('pi.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pi.is_delete' => '0'));
    $builder->where(array('pi.is_cancle' => '0'));
    $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sales_return = $query->getResultArray();

    foreach ($sales_return as $row) {
        $total += $row['total'];
    }

    @$tot_currentassets['sales_return']['total'] -= $total;
    //@$tot_currentassets['sales_return']['total'] -= $total;
    $total = 0;

    $builder = $db->table('bank_tras ct');
    $builder->select('ac.id as account_id,ac.name as account_name,ct.amount as bt_total,ct.narration');
    $builder->join('account ac', 'ac.id =' . $id);
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
    $builder->join('account ac', 'ac.id =' . $id);
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

    $tot_currentassets['from'] = $start_date;
    $tot_currentassets['to'] = $end_date;
    $tot_currentassets['id'] = $id;

    return $tot_currentassets;
}
function get_currentassets_banktrans_monthly_PerWise($start_date, $end_date, $id)
{
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
            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) -  $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

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
function get_currentassets_jv_monthly($start_date, $end_date, $id)
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
        if ($row['dr_cr'] == 'cr') {
            $cr_tot = ((@$arr[$row['month']][$row['dr_cr']]) ? @$arr[$row['month']][$row['dr_cr']] : 0) +  $row['total'];
            $arr[$row['month']][$row['dr_cr']] = $cr_tot;

            @$arr[$row['month']]['total'] -= $row['total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

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
function get_currentassets_banktrans_monthly_AcWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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

        if ($row['mode'] == 'Payment') {

            $pay_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $pay_total;

            @$arr[$row['month']]['total'] -= $row['bt_total'];
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

            $rec_total = (@$arr[$row['month']][$row['mode']] ? $arr[$row['month']][$row['mode']] : 0) + $row['bt_total'];
            $arr[$row['month']][$row['mode']] = $rec_total;

            if ($row['mode'] == 'Receipt') {
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
function get_currentassets_salesinvoice_monthly_AcWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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
function get_currentassets_salesreturn_monthly_AcWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder = $db->table('sales_return si');
    $builder->select('MONTH(si.return_date) as month,YEAR(si.return_date) as year,ac.id as account_id,ac.name as account_name,SUM(si.net_amount) as total');
    $builder->join('account ac', 'ac.id =si.account');
    $builder->where(array('si.account' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('si.is_delete' => '0', 'si.is_cancle' => '0'));
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

    return $result;
}
function get_currentassets_gnrl_sales_monthly_AcWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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
function get_currentassets_gnrl_salesreturn_monthly_AcWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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
function get_currentassets_contra_monthly_PerWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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
function get_currentassets_contra_monthly_AcWise($start_date, $end_date, $id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
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

function get_fixedassets_account_wise($start_date, $end_date, $id)
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
    $tot_fixedassets = array();

    $gmodel  = new GeneralModel();
    $acc = $gmodel->get_data_table('account', array('id' => $id), 'opening_bal,opening_type');

    $tot_fixedassets['opening']['total'] = 0;

    if ($acc['opening_type'] == 'Debit') {
        $tot_fixedassets['opening']['total'] += (float)@$acc['opening_bal'];
    } else {
        $tot_fixedassets['opening']['total'] -= (float)@$acc['opening_bal'];
    }

    $builder = $db->table('bank_tras bt');
    $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
    $builder->join('account ac', 'ac.id =' . $id);
    $builder->where(array('bt.particular' => $id));
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $bank_FixedAssets = $query->getResultArray();

    $tot_fixedassets['per_bank_trans']['total'] = 0;

    $total = 0;

    foreach ($bank_FixedAssets as $row) {

        if ($row['mode'] == 'Payment') {
            $total += $row['bt_total'];
        } else {
            $total -= $row['bt_total'];
        }
    }
    $tot_fixedassets['per_bank_trans']['total'] += $total;

    $builder = $db->table('jv_particular jv');
    $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
    $builder->join('account ac', 'ac.id = jv.particular');
    $builder->where('jv.particular', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('jv.is_delete' => '0'));
    $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $jv_FixedAssets = $query->getResultArray();
    $tot_fixedassets['jv_fixedassets']['total'] = 0;
    $total = 0;

    foreach ($jv_FixedAssets as $row) {
        if ($row['dr_cr'] == 'cr') {
            $total -= $row['total'];
        } else {
            $total += $row['total'];
        }
    }
    $tot_fixedassets['jv_fixedassets']['total'] += $total;

    $builder = $db->table('purchase_particu pp');
    $builder->select('ac.id as account_id,pp.sub_total,pp.added_amt,ac.name as account_name,pp.type');
    $builder->join('purchase_general pg', 'pp.parent_id = pg.id');
    $builder->join('account ac', 'ac.id = pp.account');
    $builder->where('pp.account', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $expence_FixedAssets = $query->getResultArray();
    //echo '<pre>';Print_r($expence_FixedAssets);exit;
    
    $tot_fixedassets['expence_fixedassets']['total'] = 0;
    $total = 0;

    foreach ($expence_FixedAssets as $row) {
        $total1 = $row['sub_total'] + $row['added_amt'];
        if ($row['type'] == 'general') {
            $total += $total1;
        } else {
            $total -= $total1;
        }
    }
    $tot_fixedassets['expence_fixedassets']['total'] = $total;


    $builder = $db->table('sales_ACparticu pp');
    $builder->select('ac.id as account_id,pp.sub_total,pp.added_amt,ac.name as account_name,pp.type');
    $builder->join('sales_ACinvoice pg', 'pp.parent_id = pg.id');
    $builder->join('account ac', 'ac.id = pp.account');
    $builder->where('pp.account', $id);
    $builder->where(array('ac.is_delete' => '0'));
    $builder->where(array('pg.is_delete' => '0'));
    $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $income_FixedAssets = $query->getResultArray();
    $tot_fixedassets['income_fixedassets']['total'] = 0;
    $total = 0;

    foreach ($income_FixedAssets as $row) {
        $total1 = $row['sub_total'] + $row['added_amt'];
        if ($row['type'] == 'general') {
            $total += $total1;
        } else {
            $total -= $total1;
        }
    }
    $tot_fixedassets['income_fixedassets']['total'] = $total;

    $tot_fixedassets['from'] = $start_date;
    $tot_fixedassets['to'] = $end_date;
    $tot_fixedassets['id'] = $id;

    return $tot_fixedassets;
}
function get_fixedassets_banktrans_monthly_PerWise($start_date, $end_date, $id)
{
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
            $total = (@$arr[$row['month']]['total'] ? $arr[$row['month']]['total'] : 0) -  $row['bt_total'];
            $arr[$row['month']]['total'] = $total;
            $arr[$row['month']]['month'] = $row['month'];
            $arr[$row['month']]['year'] = $row['year'];
            $arr[$row['month']]['account_id'] = $row['account_id'];
            $arr[$row['month']]['account_name'] = $row['account_name'];
        } else {

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
function get_sub_sub_glgroup($parent_id)
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
        //$category = array();

            $category = gl_list_new($mainCategory->id);
            //$category['name'] = $mainCategory->name;
            $sub_category = get_sub_sub_glgroup($mainCategory->id);
            if(!empty($sub_category))
            {
                foreach($sub_category as $key=>$value)
                {
                    $category[$key] = $value;
                    //if(!empty($value as ))
                }
            }
            //$new_Array = array_merge($category,$sub_category);
            // echo '<pre>id';Print_r($mainCategory->id);
            // echo '<pre>main';Print_r($category);
            // echo '<pre>sub';Print_r($sub_category);
            
        

       $categories[$mainCategory->id] = $category;
    }
    return  $categories;
}
// function new_get_sub_sub_glgroup($parent_id)
// {
//     $categories = array();

//     $db = \Config\Database::connect();

//     if (session('DataSource')) {
//         $db->setDatabase(session('DataSource'));
//     }

//     $builder = $db->table('gl_group');
//     $builder->select('id,name,parent');
//     $builder->where('is_delete', 0);
//     $query = $builder->get();
//     $builder->orderBy('id', 'desc');
//     $result = $query->getResult();
//     //echo '<pre>';Print_r($result);exit;

    
//     foreach ($result as $mainCategory) {
//         //$category = array();

//             $category = gl_list_new($mainCategory->id);
//             //$category['name'] = $mainCategory->name;
//             $sub_category = get_sub_sub_glgroup($mainCategory->id);
//             if(!empty($sub_category))
//             {
//                 foreach($sub_category as $key=>$value)
//                 {
//                     $category[$key] = $value;
//                     //if(!empty($value as ))
//                 }
//             }
//             //$new_Array = array_merge($category,$sub_category);
//             // echo '<pre>id';Print_r($mainCategory->id);
//             // echo '<pre>main';Print_r($category);
//             // echo '<pre>sub';Print_r($sub_category);
            
        

//        $categories[$mainCategory->id] = $category;
//     }
//     return  $categories;
// }
// function new_new_get_sub_sub_glgroup($id)
// {
    
//     $categories = array();

//     $db = \Config\Database::connect();

//     if (session('DataSource')) {
//         $db->setDatabase(session('DataSource'));
//     }

//     $builder = $db->table('gl_group');
//     $builder->select('id,name,parent');
//     $builder->where('is_delete', 0);
//     $builder->orderBy('id', 'desc');
//     $builder->where('id', $id);
//     $query = $builder->get();
//     $result = $query->getResultArray();
//     $data = new_parent_get_sub_sub_glgroup($result[0]['parent']);
//     if($result[0]['parent'] != 0)
//     {
//         $data[$id]  = new_new_get_sub_sub_glgroup($result[0]['id']);
//     }
//     return $data;
//     //echo '<pre>';Print_r($result);exit;
    
//     // $data = array();
//     // foreach($result as $row)
//     // {
//     //     //$data[$row['id']] = new_parent_get_sub_sub_glgroup($row['parent']);
//     // }
//     //echo '<pre>';Print_r($data);exit;
    
// }
function new_parent_get_sub_sub_glgroup($pid)
{
    $categories = array();

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    // $builder = $db->table('gl_group');
    // $builder->select('id,name,parent');
    // $builder->where('id', $pid);
    // $builder->where('is_delete', 0);
    // $query = $builder->get();
    // $result = $query->getRowArray();
    // echo '<pre>';Print_r($result);
    
  

    $main = get_parent_gl_group_old($pid);
    $parent_id = floatval($main['parent']);
    // echo '<pre>';Print_r($main);
    //$parent_data = get_parent_gl_group($pid);
    //$n=1;
    $result = array();
        while($parent_id > 0){  
            // $new_array[] = $pid; 
            //echo "test";
           $get_pid = get_parent_gl_group_old($parent_id);
           //print_r($get_pid);
            // $result = new_parent_get_sub_sub_glgroup($pid);
            $result[] = $get_pid;
            if(!empty($get_pid)){
                $parent_id = floatval($get_pid['parent']);
             }
           else {
            $parent_id = 0;
           }    
        }

            // echo '<pre>in';Print_r($get_pid);
        //$n++;      
   //echo '<pre>';Print_r($result);exit;


    

    // foreach ($result as $mainCategory) {
    //     //$category = array();

    //         //$category = gl_list_new($mainCategory->id);
    //         //$category['name'] = $mainCategory->name;
    //         $sub_category = new_parent_get_sub_sub_glgroup($mainCategory->id);
    //         // if(!empty($sub_category))
    //         // {
    //         //     foreach($sub_category as $key=>$value)
    //         //     {
    //         //         $category[$key] = $value;
    //         //         //if(!empty($value as ))
    //         //     }
    //         // }
    //         //$new_Array = array_merge($category,$sub_category);
    //         echo '<pre>id';Print_r($mainCategory->id);
    //         //echo '<pre>main';Print_r($category);
    //         echo '<pre>sub';Print_r($sub_category);
            
        

    //    $categories[$mainCategory->id] = $sub_category;
    // }
    return  $result;
}
function get_parent_gl_group_old($id)
{
    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder = $db->table('gl_group');
    $builder->select('id,name,parent');
    $builder->where('id', $id);
    $builder->where('is_delete', 0);
    $query = $builder->get();
    $result = $query->getRowArray();
    // $parent_id = $result->parent;
    return $result;
}
// function get_otherassets_account_wise($start_date, $end_date, $id)
// {

//     if ($start_date == '') {
//         if (date('m') < '03') {
//             $year = date('Y') - 1;
//             $start_date = $year . '-04-01';
//         } else {
//             $year = date('Y');
//             $start_date = $year . '-04-01';
//         }
//     }

//     if ($end_date == '') {

//         if (date('m') < '03') {
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
//     $tot_otherassets = array();

//     $gmodel  = new GeneralModel();
//     $acc = $gmodel->get_data_table('account', array('id' => $id), 'opening_bal,opening_type');

//     $tot_otherassets['opening']['total'] = 0;

//     if ($acc['opening_type'] == 'Debit') {
//         $tot_otherassets['opening']['total'] += (float)@$acc['opening_bal'];
//     } else {
//         $tot_otherassets['opening']['total'] -= (float)@$acc['opening_bal'];
//     }

//     $builder = $db->table('bank_tras bt');
//     $builder->select('ac.id as account_id,ac.name as account_name,bt.amount as bt_total,bt.mode');
//     $builder->join('account ac', 'ac.id =' . $id);
//     $builder->where(array('bt.particular' => $id));
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
//     $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
//     $query = $builder->get();
//     $bank_otherAssets = $query->getResultArray();

//     $tot_otherassets['per_bank_trans']['total'] = 0;

//     $total = 0;

//     foreach ($bank_otherAssets as $row) {

//         if ($row['mode'] == 'Payment') {
//             $total += $row['bt_total'];
//         } else {
//             $total -= $row['bt_total'];
//         }
//     }
//     $tot_otherassets['per_bank_trans']['total'] += $total;

//     $builder = $db->table('jv_particular jv');
//     $builder->select('ac.id as account_id,jv.amount as total,ac.name as account_name,jv.dr_cr');
//     $builder->join('account ac', 'ac.id = jv.particular');
//     $builder->where('jv.particular', $id);
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('jv.is_delete' => '0'));
//     $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
//     $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
//     $query = $builder->get();
//     $jv_otherAssets = $query->getResultArray();
//     $tot_otherassets['jv_otherassets']['total'] = 0;
//     $total = 0;

//     foreach ($jv_otherAssets as $row) {
//         if ($row['dr_cr'] == 'cr') {
//             $total -= $row['total'];
//         } else {
//             $total += $row['total'];
//         }
//     }
//     $tot_otherassets['jv_otherassets']['total'] += $total;

//     $builder = $db->table('purchase_particu pp');
//     $builder->select('ac.id as account_id,pp.sub_total,pp.added_amt,ac.name as account_name,pp.type');
//     $builder->join('purchase_general pg', 'pp.parent_id = pg.id');
//     $builder->join('account ac', 'ac.id = pp.account');
//     $builder->where('pp.account', $id);
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('pg.is_delete' => '0'));
//     $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
//     $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
//     $query = $builder->get();
//     $expence_otherAssets = $query->getResultArray();
//     //echo '<pre>';Print_r($expence_FixedAssets);exit;
    
//     $tot_otherassets['expence_otherassets']['total'] = 0;
//     $total = 0;

//     foreach ($expence_otherAssets as $row) {
//         $total1 = $row['sub_total'] + $row['added_amt'];
//         if ($row['type'] == 'general') {
//             $total += $total1;
//         } else {
//             $total -= $total1;
//         }
//     }
//     $tot_otherassets['expence_otherassets']['total'] = $total;


//     $builder = $db->table('sales_ACparticu pp');
//     $builder->select('ac.id as account_id,pp.sub_total,pp.added_amt,ac.name as account_name,pp.type');
//     $builder->join('sales_ACinvoice pg', 'pp.parent_id = pg.id');
//     $builder->join('account ac', 'ac.id = pp.account');
//     $builder->where('pp.account', $id);
//     $builder->where(array('ac.is_delete' => '0'));
//     $builder->where(array('pg.is_delete' => '0'));
//     $builder->where(array('DATE(pg.invoice_date)  >= ' => db_date($start_date)));
//     $builder->where(array('DATE(pg.invoice_date)  <= ' => db_date($end_date)));
//     $query = $builder->get();
//     $income_otherAssets = $query->getResultArray();
//     $tot_otherassets['income_otherassets']['total'] = 0;
//     $total = 0;

//     foreach ($income_otherAssets as $row) {
//         $total1 = $row['sub_total'] + $row['added_amt'];
//         if ($row['type'] == 'general') {
//             $total += $total1;
//         } else {
//             $total -= $total1;
//         }
//     }
//     $tot_otherassets['income_othersssets']['total'] = $total;

//     $tot_otherassets['from'] = $start_date;
//     $tot_otherassets['to'] = $end_date;
//     $tot_otherassets['id'] = $id;

//     return $tot_otherassets;
// }
function get_otherassets_account_wise($start_date, $end_date, $id)
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
    $builder->where(array('bt.particular' => $id,'bt.payment_type !=' => 'contra'));
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

    $builder = $db->table('purchase_general pg');
    $builder->select('MONTH(pg.doc_date) as month,YEAR(pg.doc_date) as year,pg.v_type as pg_type,pg.net_amount as pg_amount,ac.id as account_id,ac.name as account_name');
    $builder->join('purchase_particu pp', 'pg.id = pp.parent_id');
    $builder->join('account ac', 'ac.id =pg.party_account');
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

    foreach ($pg_expence as $row) {
        if ($row['pg_type'] == 'general') {
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

    $result = array();
    $result['other_assets'] = $arr;
    $result['from'] = $start_date;
    $result['to'] = $end_date;
    //echo '<pre>';print_r($result);exit;
    return $result;
}