<?php

//use addbook
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

