<?php

namespace App\Models;

use App\Models\GeneralModel;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\TradingModel;

class AddbookModel extends Model
{

    // public function get_outstanding_data(){

    // }

    public function ledgeroutstanding_report_xls_export_data($post)
    {
        //print_R($post);exit;

        $data = get_legderoutstanding_data($post);
        //    echo '<pre>';print_r($data);exit;
        // $sales_invoice = array();
        // $sales_acinvoice = array();
        // $purchase_invoice = array();
        // $purchase_general = array();

        // $sales_invoice = isset($data['sales_invoice'])?$data['sales_invoice']:array();
        // $sales_acinvoice = isset($data['sales_ACinvoice'])?$data['sales_ACinvoice']:array();
        // $purchase_invoice = isset($data['purchase_invoice'])?$data['purchase_invoice']:array();
        // $purchase_general = isset($data['purchase_general'])?$data['purchase_general']:array();

        // $new_data = array_merge($sales_invoice,$sales_acinvoice,$purchase_invoice,$purchase_general);

        //echo '<pre>';print_r($new_data);exit;
        // if(!empty($data['sales_invoice']))
        // {
        //     $sales_invoice = @$data['sales_invoice'];
        // }
        // else
        // {
        //     $sales_invoice = array();
        // }
        // if(!empty($data['sales_ACinvoice']))
        // {
        //     $sales_acinvoice = @$data['sales_ACinvoice'];

        //     $new_data = array_merge($sales_invoice,$sales_acinvoice);
        // }
        // else
        // {
        //     $new_data = $sales_invoice;
        // }

        //echo '<pre>';print_r($data);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:M2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:M4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Outstanding Report');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'Account Id');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Account Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Receivable');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Payable');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'Outstanding Amount');

        $i = 5;

        foreach ($data as $row) {

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $row['receivable_amount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $row['payble_amount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $row['outstanding']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('ledger_outstanding_report');

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function ledgeroutstanding_xls_export_data($post)
    {

        $data = Ledger_outstanding($post['account_id'], db_date($post['from']), db_date($post['to']));
        // echo '<pre>'; print_r($data);exit;

        $sales_invoice = isset($data['sales_invoice']) ? $data['sales_invoice'] : array();
        $sales_acinvoice = isset($data['sales_ACinvoice']) ? $data['sales_ACinvoice'] : array();
        $purchase_invoice = isset($data['purchase_invoice']) ? $data['purchase_invoice'] : array();
        $purchase_general = isset($data['purchase_general']) ? $data['purchase_general'] : array();

        $new_data = array_merge($sales_invoice, $sales_acinvoice, $purchase_invoice, $purchase_general);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Outstanding Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$data['accountname']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', user_date(@$post['from']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C3', user_date(@$post['to']));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'Invoice No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Account Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Receivable');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Payable');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'Outstanding Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'Intrest Rate');

        $i = 5;

        foreach ($new_data as $row) {
            if (isset($row['inv_id'])) {
                $inv_id = $row['inv_id'];
                $account = $row['account_name'];
                $net_amount = $row['net_amount'];
                $amount = $row['amount'];
                $panding_amount = $row['panding_amount'];
                $intrest_rate = $row['intrest_rate'];
            } elseif (isset($row['ginv_id'])) {
                $inv_id = $row['ginv_id'];
                $account = $row['gaccount_name'];
                $net_amount = $row['gnet_amount'];
                $amount = $row['gamount'];
                $panding_amount = $row['gpanding_amount'];
                $intrest_rate = $row['gintrest_rate'];
            } elseif (isset($row['pinv_id'])) {
                $inv_id = $row['pinv_id'];
                $account = $row['paccount_name'];
                $net_amount = $row['pnet_amount'];
                $amount = $row['pamount'];
                $panding_amount = $row['ppanding_amount'];
                $intrest_rate = $row['pintrest_rate'];
            } elseif (isset($row['pginv_id'])) {

                $inv_id = $row['pginv_id'];
                $account = $row['pgaccount_name'];
                $net_amount = $row['pgnet_amount'];
                $amount = $row['pgamount'];
                $panding_amount = $row['pgpanding_amount'];
                $intrest_rate = $row['pgintrest_rate'];
            } else {
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$inv_id);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$account_name);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$net_amount);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$amount);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$panding_amount);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$intrest_rate);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('ledger_outstanding');

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }



    public function get_transaction_view($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $getdata = array();

        if ($post['type'] == "sales") {
            if (empty($post['transaction_type'])) {
                $builder = $db->table('sales_invoice si');
                $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = si.account');
                $query = $builder->get();
                $getdata['sales_invoice'] = $query->getResultArray();
                $sinvoice_total = 0;
                foreach ($getdata['sales_invoice'] as $row) {
                    $sinvoice_total = $sinvoice_total + $row["net_amount"];
                }

                $getdata['total']['salesinvoice_total'] = $sinvoice_total;
                // echo '<pre>';print_r($getdata);exit;
                // $getdata['sales_invoice']['type']="sales_invoice";

                $builder = $db->table('sales_return sr');
                $builder->select('sr.id,sr.account,sr.return_date as date,sr.total,sr.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = sr.account');
                $query1 = $builder->get();
                $getdata['sales_return'] = $query1->getResultArray();
                $sreturn_total = 0;
                foreach ($getdata['sales_return'] as $row) {
                    $sreturn_total = $sreturn_total + $row["net_amount"];
                }

                $getdata['total']['salesreturn_total'] = $sreturn_total;
                //$getdata['sales_return']['type']="sales_return";

                $builder = $db->table('sales_ACinvoice sac');
                $builder->select('sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name');
                //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = sac.party_account');
                //$builder->where(array('sac.id' => @$post['bill_no']));
                //$builder->orWhere(array('sac.return_sale' => @$post['bill_no']));
                $query2 = $builder->get();
                $getdata['sales_general'] = $query2->getResultArray();
                $ginvoive_total = 0;
                $greturn_total = 0;
                foreach ($getdata['sales_general'] as $row) {
                    if ($row['v_type'] == 'general') {
                        $ginvoive_total = $ginvoive_total + $row["net_amount"];
                    } else {
                        $greturn_total = $greturn_total + $row["net_amount"];
                    }
                }

                $getdata['total']['sgeneralinvoive_total'] = $ginvoive_total;
                $getdata['total']['sgeneralreturn_total'] = $greturn_total;
                //if()

                //echo '<pre>';print_r($getdata);exit;
            } else if ($post['transaction_type'] == 'item_wise') {
                $builder = $db->table('sales_invoice si');
                $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = si.account');
                if (!empty(@$post['bill_no'])) {
                    $builder->where(array('si.id' => @$post['bill_no']));
                }
                $query3 = $builder->get();
                $getdata['sales_invoice'] = $query3->getResultArray();
                $sinvoice_total = 0;
                foreach ($getdata['sales_invoice'] as $row) {
                    $sinvoice_total = $sinvoice_total + $row["net_amount"];
                }

                $getdata['total']['salesinvoice_total'] = $sinvoice_total;
                //$getdata['sales_invoice']['type']="sales_invoice";

                $builder = $db->table('sales_return sr');
                $builder->select('sr.id,sr.account,sr.return_date as date,sr.total,sr.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = sr.account');
                if (!empty(@$post['bill_no'])) {
                    $builder->where(array('sr.id' => @$post['bill_no']));
                }
                $query4 = $builder->get();
                $getdata['sales_return'] = $query4->getResultArray();
                $sreturn_total = 0;
                foreach ($getdata['sales_return'] as $row) {
                    $sreturn_total = $sreturn_total + $row["net_amount"];
                }

                $getdata['total']['salesreturn_total'] = $sreturn_total;
                //$getdata['sales_return']['type']="sales_return";
            } else {
                $builder = $db->table('sales_ACinvoice sac');
                $builder->select('sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name');
                //$builder->select('sr.id,sr.account,sr.return_date,sr.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = sac.party_account');
                if (!empty(@$post['bill_no'])) {
                    $builder->where(array('sac.id' => @$post['bill_no']));
                    $builder->orWhere(array('sac.return_sale' => @$post['bill_no']));
                }

                $query5 = $builder->get();
                $getdata['sales_general'] = $query5->getResultArray();
                $gsinvoive_total = 0;
                $gsreturn_total = 0;
                foreach ($getdata['sales_general'] as $row) {
                    if ($row['v_type'] == 'general') {
                        $gsinvoive_total = $gsinvoive_total + $row["net_amount"];
                    } else {
                        $gsreturn_total = $gsreturn_total + $row["net_amount"];
                    }
                }

                $getdata['total']['sgeneralinvoive_total'] = $gsinvoive_total;
                $getdata['total']['sgeneralreturn_total'] = $gsreturn_total;
            }
        } else {
            if (empty($post['transaction_type'])) {
                $builder = $db->table('purchase_invoice pi');
                //$builder->select('id,account,invoice_date,net_amount');
                $builder->select('pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = pi.account');
                // $builder->where(array('pi.id' => @$post['bill_no']));
                $query6 = $builder->get();
                $getdata['purchase_invoice'] = $query6->getResultArray();
                $pinvoice_total = 0;
                foreach ($getdata['purchase_invoice'] as $row) {
                    $pinvoice_total = $pinvoice_total + $row["net_amount"];
                }

                $getdata['total']['purchaseinvoice_total'] = $pinvoice_total;
                //$getdata['purchase_invoice']['type']="purchase_invoice";

                $builder = $db->table('purchase_return pr');
                //$builder->select('account,return_date,net_amount');
                $builder->select('pr.id,pr.account,pr.return_date as date,pr.total_amount,pr.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = pr.account');
                //$builder->where(array('pr.id' => @$post['bill_no']));
                $query7 = $builder->get();
                $getdata['purchase_return'] = $query7->getResultArray();
                $preturn_total = 0;
                foreach ($getdata['purchase_return'] as $row) {
                    $preturn_total = $preturn_total + $row["net_amount"];
                }

                $getdata['total']['purchasreturn_total'] = $preturn_total;
                //$getdata['purchase_return']['type']="purchase_return";

                $builder = $db->table('purchase_general pg');
                // $builder->select('id,party_account,v_type,doc_date,net_amount');
                $builder->select('pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = pg.party_account');
                //$builder->where(array('pg.id' => @$post['bill_no']));
                //$builder->orWhere(array('pg.return_purchase' => @$post['bill_no']));
                $query8 = $builder->get();
                $getdata['purchase_general'] = $query8->getResultArray();
                //$getdata['purchase_general']['type']="purchase_general";
                $gpinvoive_total = 0;
                $gpreturn_total = 0;
                foreach ($getdata['purchase_general'] as $row) {
                    if ($row['v_type'] == 'general') {
                        $gpinvoive_total = $gpinvoive_total + $row["net_amount"];
                    } else {
                        $gpreturn_total = $gpreturn_total + $row["net_amount"];
                    }
                }

                $getdata['total']['pgeneralinvoive_total'] = $gpinvoive_total;
                $getdata['total']['pgeneralreturn_total'] = $gpreturn_total;
            } else if ($post['transaction_type'] == 'item_wise') {

                $builder = $db->table('purchase_invoice pi');
                //$builder->select('id,account,invoice_date,net_amount');
                $builder->select('pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = pi.account');
                if (!empty(@$post['bill_no'])) {
                    $builder->where(array('pi.id' => @$post['bill_no']));
                }
                $query9 = $builder->get();
                $getdata['purchase_invoice'] = $query9->getResultArray();
                $pinvoice_total = 0;
                foreach ($getdata['purchase_invoice'] as $row) {
                    $pinvoice_total = $pinvoice_total + $row["net_amount"];
                }

                $getdata['total']['purchaseinvoice_total'] = $pinvoice_total;
                //$getdata['purchase_invoice']['type']="purchase_invoice";
                // print_r($getdata);exit;
                $builder = $db->table('purchase_return pr');
                //$builder->select('account,return_date,net_amount');
                $builder->select('pr.id,pr.account,pr.return_date as date,pr.total_amount,pr.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = pr.account');
                if (!empty(@$post['bill_no'])) {
                    $builder->where(array('pr.id' => @$post['bill_no']));
                }
                $query10 = $builder->get();
                $getdata['purchase_return'] = $query10->getResultArray();
                $preturn_total = 0;
                foreach ($getdata['purchase_return'] as $row) {
                    $preturn_total = $preturn_total + $row["net_amount"];
                }

                $getdata['total']['purchasreturn_total'] = $preturn_total;
                //$getdata['purchase_return']['type']="purchase_return";

            } else {
                $builder = $db->table('purchase_general pg');
                // $builder->select('id,party_account,v_type,doc_date,net_amount');
                $builder->select('pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name');
                $builder->join('account ac', 'ac.id = pg.party_account');
                if (!empty(@$post['bill_no'])) {
                    $builder->where(array('pg.id' => @$post['bill_no']));
                    $builder->orWhere(array('pg.return_purchase' => @$post['bill_no']));
                }
                $query11 = $builder->get();
                $getdata['purchase_general'] = $query11->getResultArray();
                $gpinvoive_total = 0;
                $gpreturn_total = 0;
                foreach ($getdata['purchase_general'] as $row) {
                    if ($row['v_type'] == 'general') {
                        $gpinvoive_total = $gpinvoive_total + $row["net_amount"];
                    } else {
                        $gpreturn_total = $gpreturn_total + $row["net_amount"];
                    }
                }

                $getdata['total']['pgeneralinvoive_total'] = $gpinvoive_total;
                $getdata['total']['pgeneralreturn_total'] = $gpreturn_total;
                //$getdata['purchase_general']['type']="purchase_general";
            }
        }

        return $getdata;
    }

    public function get_billno_databyid($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        if ($post['trans_type'] == 'sales' && $post['particular'] == 'item_wise') {
            $builder = $db->table('sales_invoice si');
            $builder->select('si.*,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            //  $builder->where(array('si.account' => $post['id']));
            if (@$post['searchTerm'] != '') {
                $builder->like(array('id' => @$post['searchTerm']));
            }
            $builder->orderBy('si.id', 'desc');
            $builder->limit(5);
            $query = $builder->get();
            $sales_invoice = $query->getResultArray();

            foreach ($sales_invoice as $row) {

                // echo $db->getLastQuery();
                $text = '(' . $row['id'] . ') -' . $row['invoice_date'] . '-' . $row['account_name'] . ' - Sale Invoice';
                $data[] = array(
                    'id' => $row['id'],
                    'text' => $text,
                );
            }
            // echo '<pre>';print_r($data);exit;

            return $data;
        }
        if ($post['trans_type'] == 'sales' && $post['particular'] == 'general') {
            $builder = $db->table('sales_ACinvoice sa');
            $builder->select('sa.*,ac.name as party_name');
            $builder->join('account ac', 'ac.id = sa.party_account');
            //   $builder->where(array('sa.party_account' => $post['id']));
            if (@$post['searchTerm'] != '') {
                $builder->like(array('id' => @$post['searchTerm']));
            }
            $builder->orderBy('sa.id', 'desc');
            $builder->limit(5);
            $query = $builder->get();
            $sales_Acinvoice = $query->getResultArray();

            foreach ($sales_Acinvoice as $row) {

                // echo $db->getLastQuery();
                $text = '(' . $row['id'] . ') - ' . $row['invoice_date'] . '-' . $row['party_name'] . ' - General Sale';
                $data[] = array(
                    'id' => $row['id'],
                    'text' => $text,
                );
            }
            // echo '<pre>';print_r($data);exit;

            return $data;
        }
        if ($post['trans_type'] == 'purchase' && $post['particular'] == 'item_wise') {
            $builder = $db->table('purchase_invoice pi');
            $builder->select('pi.*,ac.name as account_name');
            $builder->join('account ac', 'ac.id = pi.account');
            //  $builder->where(array('pi.account' => $post['id']));
            //  $builder->where(array('si.account' => $post['id']));
            if (@$post['searchTerm'] != '') {
                $builder->like(array('id' => @$post['searchTerm']));
            }
            $builder->orderBy('pi.id', 'desc');
            $builder->limit(5);
            $query = $builder->get();
            $purchase_invoice = $query->getResultArray();

            foreach ($purchase_invoice as $row) {

                $text = '(' . $row['id'] . ') -' . $row['invoice_date'] . '-' . $row['account_name'] . ' - Purchase  Invoice';
                $data[] = array(
                    'id' => $row['id'],
                    'text' => $text,
                );
            }

            return $data;
        }
        if ($post['trans_type'] == 'purchase' && $post['particular'] == 'general') {
            $builder = $db->table('purchase_general pg');
            $builder->select('pg.*,ac.name as party_name');
            $builder->join('account ac', 'ac.id = pg.party_account');
            if (@$post['searchTerm'] != '') {
                $builder->like(array('id' => @$post['searchTerm']));
            }
            $builder->orderBy('pg.id', 'desc');
            $builder->limit(5);
            $query = $builder->get();
            $purchase_general = $query->getResultArray();

            foreach ($purchase_general as $row) {
                $text = '(' . $row['id'] . ') - ' . $row['doc_date'] . '-' . $row['party_name'] . ' - General Purchase';
                $data[] = array(
                    'id' => $row['id'],
                    'text' => $text,
                );
            }
            return $data;
        }
    }

    public function get_glgroup_data()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('gl_group');
        $builder->select('id,name');
        $builder->where(array('is_delete' => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();

        return $result_array;
    }

    public function get_Gnrl_Purchase_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('purchase_general si');
        $builder->select('si.id,si.tot_igst,si.tot_cgst,si.tot_sgst,si.taxes,si.supp_inv,si.party_account,si.doc_date as date,si.total_amount,si.net_amount,ac.name as account_name,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.party_account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.party_account');
        $builder->join('account acc', 'acc.id = si.voucher_type', 'left');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('si.v_type' => 'general'));
        $builder->where(array('DATE(si.doc_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.doc_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $getdata['purchase_invoice'] = $query->getResultArray();

        $sinvoice_total = 0;
        foreach ($getdata['purchase_invoice'] as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];
        }

        $getdata['total']['purchaseinvoice_total'] = $sinvoice_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function get_Gnrl_purchase_rtn_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('purchase_general si');
        $builder->select('si.id,si.tot_igst,si.tot_cgst,si.tot_sgst,si.taxes,si.supp_inv,si.party_account,si.doc_date as date,si.total_amount,si.taxable,si.net_amount,ac.name as account_name,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.party_account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.party_account');
        $builder->join('account acc', 'acc.id = si.voucher_type', 'left');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('si.v_type' => 'return'));
        $builder->where(array('DATE(si.doc_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.doc_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $getdata['purchase_invoice'] = $query->getResultArray();

        $sinvoice_total = 0;
        foreach ($getdata['purchase_invoice'] as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];
        }

        $getdata['total']['purchaseinvoice_total'] = $sinvoice_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function gnrl_purchase_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('purchase_general p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.doc_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'general');
            $builder->where(array('DATE(p.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.doc_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_general p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.doc_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'general');
            $builder->where(array('DATE(p.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.doc_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else {
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        return $purchase;
    }

    public function gnrl_purchase_rtn_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('purchase_general p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.doc_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'return');
            $builder->where(array('DATE(p.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.doc_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_general p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.doc_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'return');
            $builder->where(array('DATE(p.doc_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.doc_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else {
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        return $purchase;
    }

    public function get_Gnrl_Sales_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_ACinvoice si');
        $builder->select('si.id,si.party_account,si.invoice_date as date,si.total_amount,si.taxable,si.net_amount,ac.name as account_name,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.party_account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.party_account');
        $builder->join('account acc', 'acc.id = si.voucher_type', 'left');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('si.v_type' => 'general'));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $getdata['sales_invoice'] = $query->getResultArray();

        $sinvoice_total = 0;
        foreach ($getdata['sales_invoice'] as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];
        }

        $getdata['total']['salesinvoice_total'] = $sinvoice_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_Gnrl_Sales_rtn_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_ACinvoice si');
        $builder->select('si.id,si.party_account,si.invoice_date as date,si.total_amount,si.taxable,si.net_amount,ac.name as account_name,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.party_account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.party_account');
        $builder->join('account acc', 'acc.id = si.voucher_type', 'left');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('si.v_type' => 'return'));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $getdata['sales_invoice'] = $query->getResultArray();

        $sinvoice_total = 0;
        foreach ($getdata['sales_invoice'] as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];
        }

        $getdata['total']['salesinvoice_total'] = $sinvoice_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function gnrl_sales_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('sales_ACinvoice p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'general');
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_ACinvoice p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'general');
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else {
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        return $sales;
    }

    public function gnrl_sales_rtn_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('sales_ACinvoice p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'return');
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_ACinvoice p');
            $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.party_account');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where('p.v_type', 'return');
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else {
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        return $sales;
    }

    public function get_Sales_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_invoice si');
        $builder->select('si.id,si.account,si.invoice_date as date,si.total_amount,si.taxable,si.net_amount,ac.name as account_name,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.account');
        $builder->join('account acc', 'acc.id = si.voucher_type');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $getdata['sales_invoice'] = $query->getResultArray();

        $sinvoice_total = 0;
        foreach ($getdata['sales_invoice'] as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];
        }

        $getdata['total']['salesinvoice_total'] = $sinvoice_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function get_Sales_register_xls($post)
    {

        $data = $this->get_Sales_register($post);

        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Sales Register');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', user_date($post['from']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C2', user_date($post['to']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', @$acc['name']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'VOUCHER TYPE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'DEBIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'CREDIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['sales_invoice'] as $row) {
            $closing += (float) $row['net_amount'];

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['voucher_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Sales Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Sales_gst_register_xls($post)
    {

        $data = $this->get_Sales_gst_register($post);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:O4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Sales GST Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'SGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'CGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'IGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O4', 'IGST AMOUNT');
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', 'TOTAL GST');

        $i = 5;
        $closing = 0;
        $sale = $data['sale'];
        // echo '<pre>';print_r($sale);exit;
        //for($i=0;$i<count($sale);$i++) {
        foreach ($sale as $row) {
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($l = 0; $l < count($row['item']); $l++) {
                $total += $row['item'][$l]['qty'] * $row['item'][$l]['rate'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            $total_gst = 0;

            for ($k = 0; $k < count($row['item']); $k++) {
                $sub = $row['item'][$k]['qty'] * $row['item'][$k]['rate'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$k]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $total_gst += ($final_sub * ($row['item'][$k]['igst'] / 100));
            }
            for ($j = 0; $j < count($row['item']); $j++) {
                //if($row['id'] )
                //echo '<pre>';print_r($row['item'][$j]['igst']);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['invoice_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$total_gst, 2));
                $sub = $row['item'][$j]['qty'] * $row['item'][$j]['rate'];

                if ($row['discount'] > 0) {

                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$j]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }
                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                //print_r($itm_igst);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $row['item'][$j]['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $row['item'][$j]['qty']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $final_sub);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['item'][$j]['sgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$itm_sgst, 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['item'][$j]['cgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$itm_cgst, 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$row['item'][$j]['igst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, number_format(@$itm_igst, 2));

                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle('Sales Gst Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Sales_gst_register2_xls($post)
    {

        $data = $this->get_Sales_gst_register2($post);
        //echo '<pre>';print_r($data);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:O4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Sales GST Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'SGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'CGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'IGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O4', 'IGST AMOUNT');

        $i = 5;
        $closing = 0;
        $sale = $data['sale'];

        foreach ($sale as $row) {
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($l = 0; $l < count($row['item']); $l++) {
                $total += $row['item'][$l]['qty'] * $row['item'][$l]['rate'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            $total_gst = 0;

            for ($k = 0; $k < count($row['item']); $k++) {
                $sub = $row['item'][$k]['qty'] * $row['item'][$k]['rate'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$k]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $total_gst += ($final_sub * ($row['item'][$k]['igst'] / 100));
            }
            $new_array = array();
            for ($j = 0; $j < count($row['item']); $j++) {
                $sub = $row['item'][$j]['qty'] * $row['item'][$j]['rate'];

                if ($row['discount'] > 0) {

                    $discount_amt = $sub * $disc_avg_per;
                    $finaj_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$j]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                $new_array[$row['item'][$j]['hsn']]['hsn'] = $row['item'][$j]['hsn'];
                $new_array[$row['item'][$j]['hsn']]['qty'] = (isset($new_array[$row['item'][$j]['hsn']]['qty']) ? $new_array[$row['item'][$j]['hsn']]['qty'] : 0) + $row['item'][$j]['qty'];
                $new_array[$row['item'][$j]['hsn']]['total'] = (isset($new_array[$row['item'][$j]['hsn']]['total']) ? $new_array[$row['item'][$j]['hsn']]['total'] : 0) + $final_sub;
                $new_array[$row['item'][$j]['hsn']]['igst'] = isset($row['item'][$j]['igst']) ? @$row['item'][$j]['igst'] : 0;
                $new_array[$row['item'][$j]['hsn']]['sgst'] = isset($row['item'][$j]['sgst']) ? @$row['item'][$j]['sgst'] : 0;
                $new_array[$row['item'][$j]['hsn']]['cgst'] = isset($row['item'][$j]['cgst']) ? @$row['item'][$j]['cgst'] : 0;

                if (in_array("igst", $tax_arr)) {
                    $new_array[$row['item'][$j]['hsn']]['igst_total'] = (isset($new_array[$row['item'][$j]['hsn']]['igst_total']) ? $new_array[$row['item'][$j]['hsn']]['igst_total'] : 0) + $itm_igst;
                } else {
                    $new_array[$row['item'][$j]['hsn']]['cgst_total'] = (isset($new_array[$row['item'][$j]['hsn']]['cgst_total']) ? $new_array[$row['item'][$j]['hsn']]['cgst_total'] : 0) + $itm_cgst;
                    $new_array[$row['item'][$j]['hsn']]['sgst_total'] = (isset($new_array[$row['item'][$j]['hsn']]['sgst_total']) ? $new_array[$row['item'][$j]['hsn']]['sgst_total'] : 0) + $itm_sgst;
                }
            }

            foreach ($new_array as $item) {

                //if($row['id'] )
                //echo '<pre>';print_r($row['item'][$j]['igst']);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['invoice_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$total_gst, 2));

                //print_r($itm_igst);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$item['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$item['qty']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$item['total']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$item['sgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$item['sgst_total'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$item['cgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$item['cgst_total'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$item['igst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, number_format(@$item['igst_total'], 2));

                $i++;
            }
        }
        //echo '<pre>';print_r($spreadsheet);exit;
        $spreadsheet->getActiveSheet()->setTitle('Sales Gst Register2');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Gnrl_Sales_register_xls($post)
    {
        // print_r($post);exit;

        $data = $this->get_Gnrl_Sales_register($post);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // print_r($acc);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'General Sales Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'VOUCHER TYPE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'DEBIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'CREDIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['sales_invoice'] as $row) {
            $closing += (float) $row['net_amount'];

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['voucher_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Sales Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    // public function get_Purchase_register_xls($post)
    // {

    //     $data = $this->get_Purchase_register($post);
    //     $gmodel = new GeneralModel();
    //     $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');

    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setARGB('F8CBAD');

    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Purchase Register Report');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);

    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'INVOICE NO');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'NAME');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'VOUCHER TYPE');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'TAXABLE');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'IGST');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'CGST');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'SGST');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('J5', 'TOTAL TAX');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('K5', 'INVOICE VALUE');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('L5', 'CLOSING');
    //     $spreadsheet->setActiveSheetIndex(0)->setCellValue('M5', 'Import Gst');

    //     $i = 6;
    //     $closing = 0;
    //     foreach ($data['purchase_invoice'] as $row) {
    //         $closing += (float) $row['net_amount'];
    //         $igst = 0;
    //         $cgst = 0;
    //         $sgst = 0;
    //         $taxes = json_decode($row['taxes']);

    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['supply_inv']);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['account_name']);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['voucher_name']);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$row['total_amount']);
    //         if(in_array('igst',$taxes)){
    //             $igst = $row['tot_igst'];
    //         }else{
    //             $cgst = $row['tot_igst']/2;
    //             $sgst = $row['tot_igst']/2;
    //         }
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$igst);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$cgst);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$sgst);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['tot_igst']);
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$row['net_amount'], 2));
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, number_format($closing, 2));
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$row['import_gst'], 2));

    //         $i++;
    //     }

    //     $spreadsheet->getActiveSheet()->setTitle('Purchase Register');

    //     $spreadsheet->createSheet();

    //     $spreadsheet->setActiveSheetIndex(0);
    //     $writer = new Xlsx($spreadsheet);
    //     $writer->save('php://output');
    // }
    // update code by jenith 17-01-2023
    public function get_Purchase_register_xls($post)
    {

        $data = $this->get_Purchase_register($post);
        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Purchase Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'INVOICE NO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'NARRATION');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'VOUCHER TYPE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'TAXABLE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'IGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'CGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J5', 'SGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K5', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L5', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M5', 'CLOSING');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N5', 'Import Gst');

        $i = 6;
        $closing = 0;
        foreach ($data['purchase_invoice'] as $row) {
            $closing += (float) $row['net_amount'];
            $igst = 0;
            $cgst = 0;
            $sgst = 0;
            $taxes = json_decode($row['taxes']);

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['supply_inv']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['other']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$row['voucher_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$row['total_amount']);
            if (in_array('igst', $taxes)) {
                $igst = $row['tot_igst'];
            } else {
                $cgst = $row['tot_igst'] / 2;
                $sgst = $row['tot_igst'] / 2;
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$igst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$cgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$sgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, @$row['tot_igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format($closing, 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, number_format(@$row['import_gst'], 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Purchase Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }


    public function get_Purchase_gst_register_xls($post)
    {

        $data = $this->get_Purchase_gst_register($post);
        //echo '<pre>';print_r($data);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:O4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Purchase GST Register Report');
        //$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'SGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'CGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'IGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O4', 'IGST AMOUNT');
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', 'TOTAL GST');

        $i = 5;
        $closing = 0;
        $purchase = $data['purchase'];

        // echo '<pre>';print_r($sale);exit;
        //for($i=0;$i<count($sale);$i++) {
        foreach ($purchase as $row) {
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($l = 0; $l < count($row['item']); $l++) {
                $total += $row['item'][$l]['qty'] * $row['item'][$l]['rate'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            $total_gst = 0;

            for ($k = 0; $k < count($row['item']); $k++) {
                $sub = $row['item'][$k]['qty'] * $row['item'][$k]['rate'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$k]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $total_gst += ($final_sub * ($row['item'][$k]['igst'] / 100));
            }
            for ($j = 0; $j < count($row['item']); $j++) {
                //if($row['id'] )
                //echo '<pre>';print_r($row['item'][$j]['igst']);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['invoice_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$total_gst, 2));
                $sub = $row['item'][$j]['qty'] * $row['item'][$j]['rate'];

                if ($row['discount'] > 0) {

                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$j]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }
                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                //print_r($itm_igst);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $row['item'][$j]['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $row['item'][$j]['qty']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $final_sub);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['item'][$j]['sgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$itm_sgst, 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['item'][$j]['cgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$itm_cgst, 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$row['item'][$j]['igst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, number_format(@$itm_igst, 2));

                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle('Sales Gst Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Purchase_gst_register2_xls($post)
    {

        $data = $this->get_Purchase_gst_register($post);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:O4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Purchase GST Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'SGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'CGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'IGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O4', 'IGST AMOUNT');

        $i = 5;
        $closing = 0;
        $purchase = $data['purchase'];

        foreach ($purchase as $row) {
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($l = 0; $l < count($row['item']); $l++) {
                $total += $row['item'][$l]['qty'] * $row['item'][$l]['rate'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            $total_gst = 0;

            for ($k = 0; $k < count($row['item']); $k++) {
                $sub = $row['item'][$k]['qty'] * $row['item'][$k]['rate'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$k]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $total_gst += ($final_sub * ($row['item'][$k]['igst'] / 100));
            }
            $new_array = array();
            for ($j = 0; $j < count($row['item']); $j++) {
                $sub = $row['item'][$j]['qty'] * $row['item'][$j]['rate'];

                if ($row['discount'] > 0) {

                    $discount_amt = $sub * $disc_avg_per;
                    $finaj_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$j]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                $new_array[$row['item'][$j]['hsn']]['hsn'] = $row['item'][$j]['hsn'];
                $new_array[$row['item'][$j]['hsn']]['qty'] = (isset($new_array[$row['item'][$j]['hsn']]['qty']) ? $new_array[$row['item'][$j]['hsn']]['qty'] : 0) + $row['item'][$j]['qty'];
                $new_array[$row['item'][$j]['hsn']]['total'] = (isset($new_array[$row['item'][$j]['hsn']]['total']) ? $new_array[$row['item'][$j]['hsn']]['total'] : 0) + $final_sub;
                $new_array[$row['item'][$j]['hsn']]['igst'] = isset($row['item'][$j]['igst']) ? @$row['item'][$j]['igst'] : 0;
                $new_array[$row['item'][$j]['hsn']]['sgst'] = isset($row['item'][$j]['sgst']) ? @$row['item'][$j]['sgst'] : 0;
                $new_array[$row['item'][$j]['hsn']]['cgst'] = isset($row['item'][$j]['cgst']) ? @$row['item'][$j]['cgst'] : 0;

                if (in_array("igst", $tax_arr)) {
                    $new_array[$row['item'][$j]['hsn']]['igst_total'] = (isset($new_array[$row['item'][$j]['hsn']]['igst_total']) ? $new_array[$row['item'][$j]['hsn']]['igst_total'] : 0) + $itm_igst;
                } else {
                    $new_array[$row['item'][$j]['hsn']]['cgst_total'] = (isset($new_array[$row['item'][$j]['hsn']]['cgst_total']) ? $new_array[$row['item'][$j]['hsn']]['cgst_total'] : 0) + $itm_cgst;
                    $new_array[$row['item'][$j]['hsn']]['sgst_total'] = (isset($new_array[$row['item'][$j]['hsn']]['sgst_total']) ? $new_array[$row['item'][$j]['hsn']]['sgst_total'] : 0) + $itm_sgst;
                }
            }

            foreach ($new_array as $item) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['invoice_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$total_gst, 2));

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$item['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$item['qty']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$item['total']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$item['sgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$item['sgst_total'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$item['cgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$item['cgst_total'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$item['igst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, number_format(@$item['igst_total'], 2));

                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle('Sales Gst Register2');
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Gnrl_Purchase_register_xls($post)
    {

        $data = $this->get_Gnrl_Purchase_register($post);
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'General Purchase Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'INVOICE NO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'TAXABLE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'IGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'SGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J5', 'INVOICE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['purchase_invoice'] as $row) {
            $closing += (float) $row['net_amount'];
            $igst = 0;
            $cgst = 0;
            $sgst = 0;
            $taxes = json_decode($row['taxes']);

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['supp_inv']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['total_amount']);
            if (in_array('igst', $taxes)) {
                $igst = $row['tot_igst'];
            } else {
                $cgst = $row['tot_igst'] / 2;
                $sgst = $row['tot_igst'] / 2;
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$igst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$cgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$sgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$row['tot_igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('General Purchase Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Gnrl_Purchase_gst_register_xls($post)
    {

        $data = $this->get_gnrl_purchase_gst_register($post);
        //echo '<pre>';print_r($data);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:N4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'General Purchase GST Register Report');
        //$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'SGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'CGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'IGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'IGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'TOTAL GST');

        $i = 5;
        $closing = 0;
        foreach ($data['purchase'] as $row) {

            //$closing += (float) $row['net_amount'];
            $k = 0;
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($j = 0; $j < count($row['item']); $j++) {
                $total += $row['item'][$j]['amount'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            for ($j = 0; $j < count($row['item']); $j++) {
                $sub = $row['item'][$j]['amount'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;
                } else {
                    $final_sub = $sub;
                    $add_amt = $final_sub * $add_amt_per;
                }
                $final_sub += $add_amt;

                // if($k == 0)
                // {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['doc_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));

                $sub = $row['item'][$j]['amount'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;
                } else {
                    $final_sub = $sub;
                    $add_amt = $final_sub * $add_amt_per;
                }
                $final_sub += $add_amt;

                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                //$k++;

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$row['item'][$j]['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$final_sub, 2));

                if (in_array("igst", $tax_arr)) {

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['item'][$j]['igst']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$itm_igst, 2));
                } else {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$row['item'][$j]['sgst']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$itm_sgst, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['item'][$j]['cgst']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$itm_igst, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, '');
                }
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, number_format(@$row['tot_igst'], 2));
                $i++;
            }
        }

        //}

        $spreadsheet->getActiveSheet()->setTitle('Sales Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Sales_return_register_xls($post)
    {

        $data = $this->get_Sales_return_register($post);
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Credit Note Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'VOUCHER TYPE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'DEBIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'CREDIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['sales_return'] as $row) {
            $closing += (float) $row['net_amount'];

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['voucher_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Credit Note');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    // public function salesItem_voucher_wise_data($get)
    // {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $purchase = array();
    //     $results_per_page = 15;
    //     $page = $get['page'];
    //     $page_first_result = ($page - 1) * $results_per_page;
    //     $new_limit = ($page - 1) * 15;

    //     $gmodel = new GeneralModel();
    //     if (!empty($get['year'])) {

    //         $start = strtotime("{$get['year']}-{$get['month']}-01");
    //         $end = strtotime('-1 second', strtotime('+1 month', $start));

    //         $start_date = date('Y-m-d', $start);
    //         $end_date = date('Y-m-d', $end);

    //         $builder = $db->table('sales_invoice p');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->join('account acc', 'acc.id =p.voucher_type','left');
    //         $builder->where('p.is_delete', 0);
    //         $builder->where('p.is_cancle', 0);
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $number_of_result = $builder->countAllResults();

    //         $number_of_page = ceil($number_of_result / $results_per_page);
    //         $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->join('account acc', 'acc.id =p.voucher_type','left');
    //         $builder->where('p.is_delete', 0);
    //         $builder->where('p.is_cancle', 0);
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $builder->limit($results_per_page, $page_first_result);
    //         $query = $builder->get();
    //         $sales['sales'] = $query->getResultArray();

    //         $query = "SELECT SUM(net_amount) as total_taxable  FROM (SELECT p.net_amount
    //         FROM `sales_invoice` `p`
    //         WHERE `p`.`is_delete` = 0
    //         AND `p`.`is_cancle` = 0
    //         AND DATE(p.invoice_date) >= '".$start_date."'
    //         AND DATE(p.invoice_date) <= '".$end_date."'
    //          LIMIT ".$new_limit.") as t";
    //         $total_amount = $db->query($query)->getRowArray();

    //     } else if (!empty(@$get['from'])) {

    //         $start_date = @$get['from'] ? db_date($get['from']) : '';
    //         $end_date = @$get['to'] ? db_date($get['to']) : '';

    //         $builder = $db->table('sales_invoice p');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->join('account acc', 'acc.id =p.voucher_type');
    //         $builder->where('p.is_delete', 0);
    //         $builder->where('p.is_cancle', 0);
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $number_of_result = $builder->countAllResults();
    //         $number_of_page = ceil($number_of_result / $results_per_page);
    //         $builder->select('p.invoice_no as voucher_id,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
    //         $builder->join('account ac', 'ac.id =p.account');
    //         $builder->join('account acc', 'acc.id =p.voucher_type');
    //         $builder->where('p.is_delete', 0);
    //         $builder->where('p.is_cancle', 0);
    //         $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
    //         $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
    //         $builder->limit($results_per_page, $page_first_result);
    //         $query = $builder->get();
    //         $sales['sales'] = $query->getResultArray();
    //         $query = "SELECT SUM(net_amount) as total_taxable  FROM (SELECT p.net_amount
    //         FROM `sales_invoice` `p`
    //         WHERE `p`.`is_delete` = 0
    //         AND `p`.`is_cancle` = 0
    //         AND DATE(p.invoice_date) >= '".$start_date."'
    //         AND DATE(p.invoice_date) <= '".$end_date."'
    //         LIMIT ".$new_limit.") as t";
    //      $total_amount = $db->query($query)->getRowArray();

    //     } else {
    //         $sales['sales'] = array();
    //         $start_date = '';
    //         $end_date = '';
    //     }

    //     $sales['page'] = $page;
    //     $sales['number_of_page'] = $number_of_page;
    //     $sales['month'] = $get['month'];
    //     $sales['year'] = $get['year'];
    //     $sales['date']['from'] = $start_date;
    //     $sales['date']['to'] = $end_date;
    //     if($page == 1)
    //     {
    //         $sales['opening_balance'] =0;
    //     }
    //     else
    //     {
    //         $sales['opening_balance'] = $total_amount['total_taxable'];
    //     }
    //     echo '<pre>';Print_r($sales);exit;

    //     return $sales;
    // }
    public function salesItem_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('sales_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.custom_inv_no,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.custom_inv_no,p.id,p.net_amount as taxable,ac.name as party_name,p.invoice_date  as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account', 'left');
            $builder->join('account acc', 'acc.id =p.voucher_type', 'left');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else {
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }
        $builder = $db->table('sales_invoice p');
        $builder->select('SUM(p.net_amount) as sales_total');
        $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
        $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales['total'] = $query->getRowArray();
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        return $sales;
    }



    public function salesReturnItem_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('sales_return p');
            $builder->select('p.return_no,p.id,SUM(net_amount) as taxable,ac.name as party_name,p.return_date as date,,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->where(array('p.is_cancle' => 0));
            $builder->where(array('p.is_delete' => 0));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_return p');
            $builder->select('p.return_no,p.id,SUM(net_amount) as taxable,ac.name as party_name,p.return_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->where(array('p.is_cancle' => 0));
            $builder->where(array('p.is_delete' => 0));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $sales['sales'] = $query->getResultArray();
        } else {
            $sales['sales'] = array();
            $start_date = '';
            $end_date = '';
        }
        $builder = $db->table('sales_return p');
        $builder->select('SUM(p.net_amount) as sales_total');
        $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
        $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
        $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
        $query = $builder->get();
        $sales['total'] = $query->getRowArray();
        $sales['date']['from'] = $start_date;
        $sales['date']['to'] = $end_date;

        // echo '<pre>';print_r($purchase);exit;
        return $sales;
    }

    public function get_Sales_return_register($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_return si');
        $builder->select('si.id,si.account,si.return_date as date,si.taxable,si.net_amount,ac.name as account_name,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.account');
        $builder->join('account acc', 'acc.id = si.voucher_type');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('DATE(si.return_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.return_date)  <= ' => db_date($end_date)));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('si.is_delete' => 0));
        $query = $builder->get();
        $getdata['sales_return'] = $query->getResultArray();
        $sreturn_total = 0;

        foreach ($getdata['sales_return'] as $row) {
            $sreturn_total = $sreturn_total + $row["net_amount"];
        }

        $getdata['total']['salesreturn_total'] = $sreturn_total;
        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_Purchase_register($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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

        $getdata = array();
        $builder = $db->table('purchase_invoice pi');
        $builder->select('pi.import_gst,pi.taxes,pi.tot_igst,pi.tot_cgst,pi.tot_sgst,pi.id,pi.invoice_no,pi.supply_inv,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name,,acc.name as voucher_name');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('pi.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = pi.account');
        $builder->join('account acc', 'acc.id = pi.voucher_type');
        $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
        $builder->where(array('pi.is_cancle' => 0));
        $builder->where(array('pi.is_delete' => 0));
        $query6 = $builder->get();
        $getdata['purchase_invoice'] = $query6->getResultArray();

        $pinvoice_total = 0;
        foreach ($getdata['purchase_invoice'] as $row) {
            $pinvoice_total = $pinvoice_total + $row["net_amount"];
        }

        $getdata['total']['purchaseinvoice_total'] = $pinvoice_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function purchaseItem_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);


            $builder = $db->table('purchase_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.supply_inv,p.id,p.net_amount taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_invoice p');
            $builder->select('p.invoice_no as voucher_id,p.supply_inv,p.id,p.net_amount taxable,ac.name as party_name,p.invoice_date  as date');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->where('p.is_delete', 0);
            $builder->where('p.is_cancle', 0);
            $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else {
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }
        $builder = $db->table('purchase_invoice p');
        $builder->select('SUM(p.net_amount) as purchase_total');
        $builder->where(array('p.is_delete' => 0, 'p.is_cancle' => 0));
        $builder->where(array('DATE(p.invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(p.invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase['total'] = $query->getRowArray();

        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        //echo '<pre>';print_r($sales);exit;
        return $purchase;
    }

    public function get_Purchase_return_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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

        $getdata = array();
        $builder = $db->table('purchase_return pi');
        $builder->select('pi.id,pi.other,pi.tot_igst,pi.tot_cgst,pi.tot_sgst,pi.taxes,pi.account,pi.return_date as date,pi.total_amount,pi.net_amount,ac.name as account_name,,acc.name as voucher_name');

        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('pi.account' => $post['ac_id']));
        }

        $builder->join('account ac', 'ac.id = pi.account');
        $builder->join('account acc', 'acc.id = pi.voucher_type');
        $builder->where(array('pi.is_delete' => 0));
        $builder->where(array('pi.is_cancle' => 0));
        $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
        $query6 = $builder->get();
        $getdata['purchase_return'] = $query6->getResultArray();

        $preturn_total = 0;

        foreach ($getdata['purchase_return'] as $row) {
            $preturn_total = $preturn_total + $row["net_amount"];
        }

        $getdata['total']['purchasereturn_total'] = $preturn_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function get_Purchase_return_register_xls($post)
    {

        $data = $this->get_Purchase_return_register($post);
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');

        //print_r($post);exit;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Debit Note Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'Narration');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'TAXABLE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'IGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'SGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J5', 'INVOICE VALUE ');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['purchase_return'] as $row) {
            $closing += (float) $row['net_amount'];
            $igst = 0;
            $cgst = 0;
            $sgst = 0;
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                $igst = $row['tot_igst'];
            } else {
                $cgst = $row['tot_igst'] / 2;
                $sgst = $row['tot_igst'] / 2;
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['other']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['total_amount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$igst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$cgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$sgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$row['tot_igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Credit Note');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Gnrl_purchase_rtn_register_xls($post)
    {

        $data = $this->get_Gnrl_purchase_rtn_register($post);
        //print_r($post);exit;
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'General Purchase Return Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'INVOICE NO');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'TAXABLE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'IGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'SGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J5', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['purchase_invoice'] as $row) {
            $closing += (float) $row['net_amount'];

            $igst = 0;
            $cgst = 0;
            $sgst = 0;
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                $igst = $row['tot_igst'];
            } else {
                $cgst = $row['tot_igst'] / 2;
                $sgst = $row['tot_igst'] / 2;
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['supp_inv']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['total_amount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$igst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$cgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$sgst);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$row['tot_igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Credit Note');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Gnrl_sales_rtn_register_xls($post)
    {

        $data = $this->get_Gnrl_sales_rtn_register($post);
        //print_r($data);exit;
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'General Sales Return Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', $data['end_date']);


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'VOUCHER TYPE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'DEBIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'CREDIT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'CLOSING');

        $i = 6;
        $closing = 0;
        foreach ($data['sales_invoice'] as $row) {
            $closing += (float) $row['net_amount'];

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date($row['date']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['voucher_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format($closing, 2));

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('Credit Note');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }


    public function purchaseReturnItem_voucher_wise_data($get)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $purchase = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('purchase_return p');
            $builder->select('p.return_no,p.id,SUM(p.net_amount) as taxable,ac.name as party_name,p.return_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->where(array('p.is_delete' => 0));
            $builder->where(array('p.is_cancle' => 0));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();

            $getdata = array();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_return p');
            $builder->select('p.return_no,p.id,SUM(p.net_amount) as taxable,ac.name as party_name,p.return_date as date,acc.name as voucher_name');
            $builder->join('account ac', 'ac.id =p.account');
            $builder->join('account acc', 'acc.id =p.voucher_type');
            $builder->where(array('DATE(p.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(p.return_date)  <= ' => $end_date));
            $builder->where(array('p.is_delete' => 0));
            $builder->where(array('p.is_cancle' => 0));
            $builder->groupBy('p.id');
            $query = $builder->get();
            $purchase['purchase'] = $query->getResultArray();
        } else {
            $purchase['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }
        $purchase['date']['from'] = $start_date;
        $purchase['date']['to'] = $end_date;

        return $purchase;
    }

    public function payment_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $getdata = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.particular');
            $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

            if (!empty($mode)) {
                $builder->where(array('bt.payment_type' => $mode));
            } else {
                $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
                $builder->where($whr);
            }

            $query = $builder->get();
            $getdata['payment_vch'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.particular');
            $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

            if (!empty($mode)) {
                $builder->where(array('bt.payment_type' => $mode));
            } else {
                $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
                $builder->where($whr);
            }

            $query = $builder->get();
            $getdata['payment_vch'] = $query->getResultArray();
        } else {
            $getdata['payment_vch'] = array();
            $start_date = '';
            $end_date = '';
        }

        $getdata['date']['from'] = $start_date;
        $getdata['date']['to'] = $end_date;

        return $getdata;
    }

    public function receipt_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $getdata = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.particular');
            $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

            if (!empty($mode)) {
                $builder->where(array('bt.payment_type' => $mode));
            } else {
                $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
                $builder->where($whr);
            }

            $query = $builder->get();
            $getdata['receipt_vch'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.particular');
            $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

            if (!empty($mode)) {
                $builder->where(array('bt.payment_type' => $mode));
            } else {
                $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
                $builder->where($whr);
            }

            $query = $builder->get();
            $getdata['receipt_vch'] = $query->getResultArray();
        } else {
            $getdata['receipt_vch'] = array();
            $start_date = '';
            $end_date = '';
        }

        $getdata['date']['from'] = $start_date;
        $getdata['date']['to'] = $end_date;

        return $getdata;
    }

    public function contra_voucher_wise_data($get)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $getdata = array();

        if (!empty($get['year'])) {

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));

            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', $end);

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.particular');
            $builder->where(array('bt.is_delete' => 0));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
            $builder->where(array('bt.payment_type' => 'contra'));
            $query = $builder->get();

            $getdata['contra_vch'] = $query->getResultArray();
        } else if (!empty(@$get['from'])) {

            $start_date = @$get['from'] ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.particular');
            $builder->where(array('bt.is_delete' => 0));
            $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
            $builder->where(array('bt.payment_type' => 'contra'));
            $query = $builder->get();

            $getdata['contra_vch'] = $query->getResultArray();
        } else {
            $getdata['contra_vch'] = array();
            $start_date = '';
            $end_date = '';
        }

        $getdata['date']['from'] = $start_date;
        $getdata['date']['to'] = $end_date;

        return $getdata;
    }

    public function get_creditnote($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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
        // print_r($start_date);
        // print_r($end_date);exit;
        $getdata = array();
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
        //print_r($getdata);exit;

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

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        //print_r($getdata);exit;
        return $getdata;
    }

    public function get_debitnote($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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
        // print_r($start_date);
        // print_r($end_date);exit;
        $getdata = array();
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

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        //print_r($getdata);exit;
        return $getdata;
    }

    public function get_payment_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $mode = @$post['mode'];

        $getdata = array();
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.particular');
        $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

        if (!empty($mode)) {
            $builder->where(array('bt.payment_type' => $mode));
        } else {
            $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
            $builder->where($whr);
        }

        $query = $builder->get();
        $getdata['payment_vch'] = $query->getResultArray();

        $payment_total = 0;
        foreach ($getdata['payment_vch'] as $row) {
            $payment_total = $payment_total + $row["amount"];
        }

        $getdata['total']['payment_total'] = $payment_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function get_receipt_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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
        $mode = @$post['mode'];

        $getdata = array();

        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.particular');
        $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

        if (!empty($mode)) {
            $builder->where(array('bt.payment_type' => $mode));
        } else {
            $whr = "(bt.payment_type = 'bank' or bt.payment_type = 'cash')";
            $builder->where($whr);
        }

        $query = $builder->get();
        $getdata['receipt_vch'] = $query->getResultArray();

        $receipt_total = 0;
        foreach ($getdata['receipt_vch'] as $row) {
            $receipt_total = $receipt_total + $row["amount"];
        }

        $getdata['total']['receipt_total'] = $receipt_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function get_contra_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $mode = @$post['mode'];

        $getdata = array();
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.mode,bt.payment_type,bt.cash_type,bt.receipt_date as date,bt.amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.particular');
        $builder->where(array('bt.is_delete' => 0));
        $builder->where(array('bt.payment_type' => 'contra'));
        // $builder->where(array('bt.cash_type !=' => 'Fund Transfer'));
        $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $result1 = $query->getResultArray();

        $getdata['contra_vch'] = $result1;

        $contra_total = 0;

        foreach ($getdata['contra_vch'] as $row) {
            $contra_total = $contra_total + $row["amount"];
        }

        $getdata['total']['contra_total'] = $contra_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        return $getdata;
    }

    public function get_bank_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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
        $mode = @$post['mode'];

        $getdata = array();
        $builder = $db->table('bank_tras bt');
        $builder->select('bt.id,bt.account,bt.check_no,bt.check_date,bt.payment_type,bt.mode,bt.receipt_date as date,bt.amount,bt.recons_date,ac.name as account_name');
        $builder->join('account ac', 'ac.id = bt.particular');
        $builder->where(array('bt.payment_type' => 'bank', 'bt.is_delete' => 0));

        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
            $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
        }

        if (!empty($mode) && $mode != 'Reconsilation') {
            $builder->where(array('bt.mode' => $mode));
        }
        $builder->where(array('bt.account' => @$post['account']));
        $query5 = $builder->get();
        $getdata['bank'] = $query5->getResultArray();

        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['account']), 'id,name');
        // print_r($post);exit;

        if (isset($post['account'])) {
            $opening = $gmodel->get_data_table('account', array('id' => @$post['account']), 'opening_bal,opening_type');
        } else {
            $opening = array();
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
        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        $getdata['opening_bal'] = $opening;

        $getdata['acc_name'] = @$acc['name'] ? $acc['name'] : '';
        $getdata['acc_id'] = @$acc['id'] ? $acc['id'] : '';

        return $getdata;
    }

    public function get_cash_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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
        $mode = @$post['mode'];
        // print_r($start_date);
        // print_r($end_date);exit;
        $getdata = array();
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

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        //print_r($getdata);exit;
        return $getdata;
    }

    public function get_journal_register($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
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
        $mode = @$post['mode'];
        // print_r($start_date);
        // print_r($end_date);exit;
        $getdata = array();
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
        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        //print_r($getdata);exit;
        return $getdata;
    }

    public function get_account_data()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('id,name');
        $builder->where(array('is_delete' => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();

        return $result_array;
    }

    public function get_ledger_register($post)
    {
        //print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

        $account_id = @$post['account_id'];
        $gmodel = new GeneralModel();
        $account_name = $gmodel->get_data_table('account', array('id' => $account_id), 'name');



        // if ($start_date == '') {
        //     if (date('m') <= '03') {
        //         $year = date('Y') - 1;
        //         $start_date = $year . '-04-01';
        //     } else {
        //         $year = date('Y');
        //         $start_date = $year . '-04-01';
        //     }
        // }
        // if ($end_date == '') {

        //     if (date('m') <= '03') {
        //         $year = date('Y');
        //     } else {
        //         $year = date('Y') + 1;
        //     }
        //     $end_date = $year . '-03-31';
        // }
        // print_r($start_date);
        // print_r($end_date);exit;

        $getdata = array();

        if (!empty($account_id)) {

            $builder = $db->table('sales_invoice si');
            $builder->select('"sale_invoice" as type,si.id,si.custom_inv_no as vch_no,si.account,si.invoice_date as date,si.total_amount,si.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where(array('si.is_delete' => 0, 'si.is_cancle' => 0, 'si.account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $sales_invoice = $query->getResultArray();
            //echo $db->getLastQuery();exit;

            $sinvoice_total = 0;
            foreach ($sales_invoice as $row) {
                $sinvoice_total = $sinvoice_total + $row["net_amount"];
            }

            $ledger['total']['salesinvoice_total'] = $sinvoice_total;

            $builder = $db->table('sales_ACinvoice sac');
            $builder->select('"sale_general" as type,sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name,sac.supp_inv as vch_no');
            $builder->join('account ac', 'ac.id = sac.party_account');
            $builder->where(array('sac.is_delete' => 0, 'sac.is_cancle' => 0, 'sac.v_type' => 'general', 'sac.party_account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(sac.invoice_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(sac.invoice_date)  <= ' => db_date($end_date)));
            }
            $query2 = $builder->get();
            $salesinvoice_general = $query2->getResultArray();
            $sginvoive_total = 0;


            foreach ($salesinvoice_general as $row) {
                $sginvoive_total = $sginvoive_total + $row["net_amount"];
            }

            $ledger['total']['salesinvoice_general_total'] = $sginvoive_total;

            $builder = $db->table('purchase_invoice pi');
            $builder->select('"purchase_invoice" as type,pi.id,pi.account,pi.invoice_date as date,pi.total_amount,pi.net_amount,ac.name as account_name,pi.supply_inv as vch_no');
            $builder->join('account ac', 'ac.id = pi.account');
            $builder->where(array('pi.is_delete' => 0, 'pi.is_cancle' => 0, 'pi.account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
            }
            $query6 = $builder->get();
            $purchase_invoice = $query6->getResultArray();

            $pinvoice_total = 0;

            foreach ($purchase_invoice as $row) {
                $pinvoice_total = $pinvoice_total + $row["net_amount"];
            }

            $ledger['total']['purchaseinvoice_total'] = $pinvoice_total;

            $builder = $db->table('purchase_general pg');
            $builder->select('"purchase_general" as type,pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name,pg.supp_inv as vch_no');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.is_delete' => 0, 'pg.is_cancle' => 0, 'pg.v_type' => 'general', 'pg.party_account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
            }
            $query8 = $builder->get();
            $purchaseinvoice_general = $query8->getResultArray();


            $gpinvoive_total = 0;

            foreach ($purchaseinvoice_general as $row) {
                $gpinvoive_total = $gpinvoive_total + $row["net_amount"];
            }

            $ledger['total']['purchaseinvoive_general_total'] = $gpinvoive_total;

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode as type,bt.payment_type,bt.receipt_date as date,bt.amount as net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.mode' => 'Payment', 'bt.is_delete' => 0, 'bt.particular' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $payment = $query->getResultArray();
            // echo $db->getLastQuery();
            // echo '<pre>';print_r($payment);exit;

            $payment_total = 0;
            foreach ($payment as $row) {
                $payment_total = $payment_total + $row["net_amount"];
            }

            $ledger['total']['payment_total'] = $payment_total;

            $builder = $db->table('bank_tras bt');
            $builder->select('bt.id,bt.account,bt.mode as type,bt.payment_type,bt.receipt_date as date,bt.amount as net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = bt.account');
            $builder->where(array('bt.mode' => 'Receipt', 'bt.is_delete' => 0, 'bt.particular' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(bt.receipt_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(bt.receipt_date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $receipt = $query->getResultArray();


            $receipt_total = 0;

            foreach ($receipt as $row) {
                $receipt_total = $receipt_total + $row["net_amount"];
            }

            $ledger['total']['receipt_total'] = $receipt_total;

            $builder = $db->table('purchase_return pr');
            $builder->select('"purchase_return" as type,pr.id,pr.account,pr.return_date as date,pr.total_amount,pr.net_amount,ac.name as account_name');
            $builder->join('account ac', 'ac.id = pr.account');
            $builder->where(array('pr.is_delete' => 0, 'pr.account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(pr.return_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(pr.return_date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $purchase_return = $query->getResultArray();


            $preturn_total = 0;

            foreach ($purchase_return as $row) {
                $preturn_total = $preturn_total + $row["net_amount"];
            }

            $ledger['total']['purchasreturn_total'] = $preturn_total;

            $builder = $db->table('purchase_general pg');
            $builder->select('"purchase_general_return" as type,pg.id,pg.party_account,pg.v_type,pg.doc_date as date,pg.total_amount,pg.net_amount,ac.name as account_name,pg.supp_inv as vch_no');
            $builder->join('account ac', 'ac.id = pg.party_account');
            $builder->where(array('pg.v_type' => 'return', 'pg.is_delete' => 0, 'pg.is_cancle' => 0, 'pg.party_account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(pg.doc_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(pg.doc_date)  <= ' => db_date($end_date)));
            }
            $query8 = $builder->get();
            $purchasegeneral_return = $query8->getResultArray();


            $gpreturn_total = 0;

            foreach ($purchasegeneral_return as $row) {
                $gpreturn_total = $gpreturn_total + $row["net_amount"];
            }

            $ledger['total']['purchasereturn_general_total'] = $gpreturn_total;

            $builder = $db->table('sales_return sr');
            $builder->select('"sale_return" as type,sr.id,sr.account,sr.return_date as date,sr.total,sr.net_amount,ac.name as account_name,sr.supp_inv as vch_no');
            $builder->join('account ac', 'ac.id = sr.account');
            $builder->where(array('sr.is_delete' => 0, 'sr.is_cancle' => 0, 'sr.account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(sr.return_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(sr.return_date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $sales_return = $query->getResultArray();


            $sreturn_total = 0;

            foreach ($sales_return as $row) {
                $sreturn_total = $sreturn_total + $row["net_amount"];
            }

            $ledger['total']['salesreturn_total'] = $sreturn_total;

            $builder = $db->table('sales_ACinvoice sac');
            $builder->select('"sale_general_return" as type,sac.id,sac.party_account,sac.v_type,sac.invoice_date as date,sac.total_amount,sac.net_amount,ac.name as account_name,sac.supp_inv as vch_no');
            $builder->join('account ac', 'ac.id = sac.party_account');
            $builder->where(array('sac.v_type' => 'return', 'sac.is_cancle' => 0, 'sac.is_delete' => 0, 'sac.party_account' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(sac.invoice_date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(sac.invoice_date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $salesgeneral_return = $query->getResultArray();

            $gsreturn_total = 0;

            foreach ($salesgeneral_return as $row) {
                $gsreturn_total = $gsreturn_total + $row["net_amount"];
            }

            $ledger['total']['salesreturn_general_total'] = $gsreturn_total;

            $builder = $db->table('jv_particular jv');
            $builder->select('jv.id,jv.jv_id,jv.particular,jv.dr_cr as type,jv.date as date,jv.amount as net_amount,ac.name as account_name,jv.jv_id');
            $builder->join('account ac', 'ac.id = jv.particular');
            $builder->where(array('jv.is_delete' => 0, 'jv.particular' => $account_id));
            if (!empty($start_date)) {
                $builder->where(array('DATE(jv.date)  >= ' => db_date($start_date)));
            }
            if (!empty($end_date)) {
                $builder->where(array('DATE(jv.date)  <= ' => db_date($end_date)));
            }
            $query = $builder->get();
            $journal = $query->getResultArray();

            $journalcredit_total = 0;
            $journaldebit_total = 0;
            $journal_new = array();
            foreach ($journal as $row) {

                if ($row['type'] == 'cr') {
                    $dr = $gmodel->get_data_table('jv_particular', array('jv_id' => $row['jv_id'], 'dr_cr' => "dr"), 'particular');

                    $ac = $gmodel->get_data_table('account', array('id' => $dr['particular']), 'name');

                    $row['account_name'] = @$ac['name'];

                    $journalcredit_total = $journalcredit_total + $row["net_amount"];
                } else {
                    $journaldebit_total = $journaldebit_total + $row["net_amount"];
                }

                $journal_new[] = $row;
            }


            $ledger['total']['journalcredit_total'] = $journalcredit_total;
            $ledger['total']['journaldebit_total'] = $journaldebit_total;

            $getdata['ledger'] = $ledger;

            $merge_arr = array_merge($sales_invoice, $salesinvoice_general, $purchase_invoice, $purchaseinvoice_general, $payment, $receipt, $purchase_return, $purchasegeneral_return, $sales_return, $salesgeneral_return, $journal_new);
            usort($merge_arr, 'date_compare');

            $getdata['data'] = $merge_arr;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        $getdata['ac_name'] = @$account_name['name'];
        $getdata['account_id'] = @$post['account_id'];

        return $getdata;
    }

    public function get_old_ledger_register($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

        $account_id = @$post['account_id'];
        $gmodel = new GeneralModel();
        $account_name = $gmodel->get_data_table('account', array('id' => $account_id), 'name,opening_type,opening_bal');

        $ledger = array();
        $salesinvoice_total = 0;
        $salesinvoice_general_total = 0;
        $purchaseinvoice_total = 0;
        $purchaseinvoive_general_total = 0;
        $payment_total = 0;
        $receipt_total = 0;
        $purchasreturn_total = 0;
        $purchasereturn_general_total = 0;
        $salesreturn_total = 0;
        $salesreturn_general_total = 0;
        $journal = 0;
        $journalcredit_total = 0;
        $journaldebit_total = 0;

        if (!empty($account_id)) {

            $builder = $db->table('sales_invoice');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('is_delete' => 0, 'is_cancle' => 0, 'account' => $account_id));
            $builder->where(array('DATE(invoice_date)  < ' => db_date($start_date)));
            $query = $builder->get();
            $sales_invoice = $query->getRowArray();
            // echo $db->getLastQuery();

            $salesinvoice_total = $sales_invoice["total_netamount"];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('is_delete' => 0, 'is_cancle' => 0, 'v_type' => 'general', 'party_account' => $account_id));
            $builder->where(array('DATE(invoice_date)  < ' => db_date($start_date)));
            $query2 = $builder->get();
            $salesinvoice_general = $query2->getRowArray();
            $salesinvoice_general_total = $salesinvoice_general["total_netamount"];

            $builder = $db->table('purchase_invoice');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('is_delete' => 0, 'is_cancle' => 0, 'account' => $account_id));
            $builder->where(array('DATE(invoice_date)  < ' => db_date($start_date)));
            $query6 = $builder->get();
            $purchase_invoice = $query6->getRowArray();
            $purchaseinvoice_total = $purchase_invoice["total_netamount"];

            $builder = $db->table('purchase_general pg');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('is_delete' => 0, 'is_cancle' => 0, 'v_type' => 'general', 'party_account' => $account_id));
            $builder->where(array('DATE(doc_date)  < ' => db_date($start_date)));
            $query8 = $builder->get();
            $purchaseinvoice_general = $query8->getRowArray();
            $purchaseinvoive_general_total = $purchaseinvoice_general['total_netamount'];

            $builder = $db->table('bank_tras');
            $builder->select('SUM(amount) as total_netamount');
            $builder->where(array('mode' => 'Payment', 'is_delete' => 0, 'particular' => $account_id));
            $builder->where(array('DATE(receipt_date) < ' => db_date($start_date)));
            $query = $builder->get();
            $payment = $query->getRowArray();
            $payment_total = $payment['total_netamount'];

            $builder = $db->table('bank_tras');
            $builder->select('SUM(amount) as total_netamount');
            $builder->where(array('mode' => 'Receipt', 'is_delete' => 0, 'particular' => $account_id));
            $builder->where(array('DATE(receipt_date)  < ' => db_date($start_date)));
            $query = $builder->get();
            $receipt = $query->getRowArray();
            $receipt_total = $receipt['total_netamount'];

            $builder = $db->table('purchase_return');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('is_delete' => 0, 'account' => $account_id));
            $builder->where(array('DATE(return_date) < ' => db_date($start_date)));
            $query = $builder->get();
            $purchase_return = $query->getRowArray();
            $purchasreturn_total = $purchase_return['total_netamount'];

            $builder = $db->table('purchase_general');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('v_type' => 'return', 'is_delete' => 0, 'is_cancle' => 0, 'party_account' => $account_id));
            $builder->where(array('DATE(doc_date)  < ' => db_date($start_date)));
            $query8 = $builder->get();
            $purchasegeneral_return = $query8->getRowArray();
            $purchasereturn_general_total = $purchasegeneral_return['total_netamount'];

            $builder = $db->table('sales_return');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('is_delete' => 0, 'is_cancle' => 0, 'account' => $account_id));
            $builder->where(array('DATE(return_date)  < ' => db_date($start_date)));
            $query = $builder->get();
            $sales_return = $query->getRowArray();
            $salesreturn_total = $sales_return['total_netamount'];

            $builder = $db->table('sales_ACinvoice');
            $builder->select('SUM(net_amount) as total_netamount');
            $builder->where(array('v_type' => 'return', 'is_cancle' => 0, 'is_delete' => 0, 'party_account' => $account_id));
            $builder->where(array('DATE(invoice_date)  < ' => db_date($start_date)));
            $query = $builder->get();
            $salesgeneral_return = $query->getRowArray();
            $salesreturn_general_total = $salesgeneral_return['total_netamount'];

            $builder = $db->table('jv_particular');
            $builder->select('dr_cr,amount as net_amount');
            $builder->where(array('is_delete' => 0, 'particular' => $account_id));
            $builder->where(array('DATE(date) < ' => db_date($start_date)));
            $query = $builder->get();
            $journal = $query->getResultArray();


            $journal_new = array();
            foreach ($journal as $row) {
                if ($row['dr_cr'] == 'cr') {
                    $journalcredit_total = $journalcredit_total + $row["net_amount"];
                } else {
                    $journaldebit_total = $journaldebit_total + $row["net_amount"];
                }
            }

            $journalcredit_total = $journalcredit_total;
            $journaldebit_total = $journaldebit_total;
        }
        $debit_account = 0;
        $credit_account = 0;
        if ($account_name['opening_type'] != '') {
            if ($account_name['opening_bal'] != '') {
                if ($account_name['opening_type'] == 'Credit') {
                    $credit_account = $account_name['opening_bal'];
                } else {
                    $debit_account = $account_name['opening_bal'];
                }
            }
        }
        // echo '<pre>';print_r($credit_account);
        // echo '<pre>$salesreturn_total';print_r($salesreturn_total);
        // echo '<pre>$salesreturn_general_total';print_r($salesreturn_general_total);
        // echo '<pre>$purchaseinvoice_total';print_r($purchaseinvoice_total);
        // echo '<pre>$purchaseinvoive_general_total';print_r($purchaseinvoive_general_total);
        // echo '<pre>$receipt_total';print_r($receipt_total);
        // echo '<pre>$journalcredit_total';print_r($journalcredit_total);exit;
        $credit = (float)$credit_account + (float)$salesreturn_total + (float)$salesreturn_general_total + (float)$purchaseinvoice_total + (float)$purchaseinvoive_general_total + (float)$receipt_total + (float)$journalcredit_total;
        $debit = (float)$debit_account + (float)$salesinvoice_total + (float)$salesinvoice_general_total + (float)$purchasreturn_total + (float)$purchasereturn_general_total + (float)$payment_total + (float)$journaldebit_total;
        $opening_bal = $debit - $credit;
        // echo '<pre>';print_r($opening_bal);exit;

        return $opening_bal;
    }

    public function get_sale_gray_finish($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
        $mode = @$post['mode'];
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
        $builder = $db->table('salemillinvoice si');
        $builder->select('si.id,si.item_type,si.account,si.date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('item_type' => $mode));
        }

        $query = $builder->get();
        $getdata['sales_grayfinish'] = $query->getResultArray();

        $sales_grayfinish_total = 0;
        foreach ($getdata['sales_grayfinish'] as $row) {
            $sales_grayfinish_total = $sales_grayfinish_total + $row["net_amount"];
        }

        $getdata['total']['sales_grayfinish_total'] = $sales_grayfinish_total;

        $builder = $db->table('salemillreturn si');
        $builder->select('si.id,si.item_type,si.account,si.date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('item_type' => $mode));
        }
        $query1 = $builder->get();
        $getdata['sales_grayfinish_return'] = $query1->getResultArray();
        $sales_grayfinishreturn_total = 0;
        foreach ($getdata['sales_grayfinish_return'] as $row) {
            $sales_grayfinishreturn_total = $sales_grayfinishreturn_total + $row["net_amount"];
        }
        $getdata['total']['sales_grayfinishreturn_total'] = $sales_grayfinishreturn_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        //print_r($getdata);exit;
        return $getdata;
    }

    public function get_purchase_gray_finish($post = '')
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];
        $mode = @$post['mode'];
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
        // print_r($start_date);
        // print_r($end_date);
        $getdata = array();
        $builder = $db->table('grey si');
        $builder->select('si.id,si.purchase_type,si.party_name,si.inv_date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.party_name');
        $builder->where(array('si.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(si.inv_date)  >= ' => $start_date));
            $builder->where(array('DATE(si.inv_date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('purchase_type' => $mode));
        }

        $query = $builder->get();
        $getdata['purchase_grayfinish'] = $query->getResultArray();

        $purchase_grayfinish_total = 0;
        foreach ($getdata['purchase_grayfinish'] as $row) {
            $purchase_grayfinish_total = $purchase_grayfinish_total + $row["net_amount"];
        }

        $getdata['total']['purchase_grayfinish_total'] = $purchase_grayfinish_total;

        $builder = $db->table('retgrayfinish si');
        $builder->select('si.id,si.purchase_type,si.party_name,si.date as date,si.total_amount,si.net_amount,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.party_name');
        $builder->where(array('si.is_delete' => 0));
        if (!empty($start_date) and !empty($end_date)) {
            $builder->where(array('DATE(si.date)  >= ' => $start_date));
            $builder->where(array('DATE(si.date)  <= ' => $end_date));
        }
        if (!empty($mode)) {
            $builder->where(array('purchase_type' => $mode));
        }

        $query = $builder->get();
        $getdata['purchase_grayfinish_return'] = $query->getResultArray();
        $purchase_grayfinishreturn_total = 0;
        foreach ($getdata['purchase_grayfinish_return'] as $row) {
            $purchase_grayfinishreturn_total = $purchase_grayfinishreturn_total + $row["net_amount"];
        }
        $getdata['total']['purchase_grayfinishreturn_total'] = $purchase_grayfinishreturn_total;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_Sales_gst_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_invoice si');
        $builder->select('si.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $sales_invoice = $query->getResultArray();

        $sinvoice_total = 0;

        foreach ($sales_invoice as $row) {

            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('sales_item si');
            $builder->select('si.*,i.hsn');
            $builder->join('item i', 'i.id = si.item_id');
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('si.type' => 'invoice'));
            $builder->where(array('si.parent_id' => $row['id']));
            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['sale'][] = $row;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        // echo '<pre>';print_r($getdata);exit;

        return $getdata;
    }

    public function get_Sales_gst_register2($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_invoice si');
        $builder->select('si.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $sales_invoice = $query->getResultArray();

        $sinvoice_total = 0;

        foreach ($sales_invoice as $row) {

            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('sales_item si');
            $builder->select('si.*,i.hsn');
            $builder->join('item i', 'i.id = si.item_id');
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('si.type' => 'invoice'));
            $builder->where(array('si.parent_id' => $row['id']));

            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['sale'][] = $row;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;

        // echo '<pre>';print_r($getdata);exit;

        return $getdata;
    }

    public function get_Gnrl_sales_gst_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_ACinvoice si');
        $builder->select('si.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.party_account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.party_account');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('si.v_type' => 'general'));
        $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $sales_invoice = $query->getResultArray();

        $sinvoice_total = 0;
        foreach ($sales_invoice as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('sales_ACparticu si');
            $builder->select('si.*,ac.hsn');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('si.type' => 'general'));
            $builder->where(array('si.parent_id' => $row['id']));
            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['sale'][] = $row;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_Gnrl_Sales_gst_register_xls($post)
    {

        $data = $this->get_Gnrl_sales_gst_register($post);
        //echo '<pre>';print_r($data);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:N4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'General Sales GST Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'SGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'CGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'IGST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'IGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'TOTAL GST');

        $i = 5;
        $closing = 0;
        foreach ($data['sale'] as $row) {

            //$closing += (float) $row['net_amount'];
            $k = 0;
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($j = 0; $j < count($row['item']); $j++) {
                $total += $row['item'][$j]['amount'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            for ($j = 0; $j < count($row['item']); $j++) {
                $sub = $row['item'][$j]['amount'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;
                } else {
                    $final_sub = $sub;
                    $add_amt = $final_sub * $add_amt_per;
                }
                $final_sub += $add_amt;

                // if($k == 0)
                // {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['invoice_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));

                $sub = $row['item'][$j]['amount'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;
                } else {
                    $final_sub = $sub;
                    $add_amt = $final_sub * $add_amt_per;
                }
                $final_sub += $add_amt;

                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                //$k++;

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$row['item'][$j]['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$final_sub, 2));

                if (in_array("igst", $tax_arr)) {

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['item'][$j]['igst']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$itm_igst, 2));
                } else {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$row['item'][$j]['sgst']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$itm_sgst, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['item'][$j]['cgst']);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$itm_igst, 2));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, '');
                }
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, number_format(@$row['tot_igst'], 2));
                $i++;
            }
        }

        //}

        $spreadsheet->getActiveSheet()->setTitle('Sales Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Creditnote_gst_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('sales_return si');
        $builder->select('si.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.is_delete' => 0));
        $builder->where(array('si.is_cancle' => 0));
        $builder->where(array('DATE(si.return_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(si.return_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $sales_invoice = $query->getResultArray();

        $sinvoice_total = 0;

        foreach ($sales_invoice as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('sales_item si');
            $builder->select('si.*,i.hsn');
            $builder->join('item i', 'i.id = si.item_id');
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('si.type' => 'return'));
            $builder->where(array('si.parent_id' => $row['id']));
            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['sale'][] = $row;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_Creditnote_gst_register_xls($post)
    {

        $data = $this->get_Creditnote_gst_register($post);
        //cho '<pre>';print_r($data);exit;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:O4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Credit Note GST Register Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', $data['start_date']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $data['end_date']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'NAME');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'GST');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE DATE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'INVOICE VALUE');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'TOTAL TAX');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'SGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'SGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'CGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'CGST AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'IGST %');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O4', 'IGST AMOUNT');
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('P1', 'TOTAL GST');

        $i = 5;
        $closing = 0;
        $sale = $data['sale'];
        //echo '<pre>';print_r($sale);exit;
        //for($i=0;$i<count($sale);$i++) {
        foreach ($sale as $row) {
            $total = 0;
            $tax_arr = json_decode($row['taxes']);

            for ($l = 0; $l < count($row['item']); $l++) {
                $total += $row['item'][$l]['qty'] * $row['item'][$l]['rate'];
            }

            if ($row['discount'] > 0) {
                if ($row['disc_type'] == '%') {
                    $discount_amount = ($total * ($row['discount'] / 100));
                    $disc_avg_per = $discount_amount / $total;
                } else {
                    $disc_avg_per = $row['discount'] / $total;
                }
            } else {
                $disc_avg_per = 0;
            }

            if ($row['amty'] > 0) {
                if ($row['amty_type'] == '%') {
                    $amty_amount = ($total * ($row['amty'] / 100));
                    $add_amt_per = $amty_amount / $total;
                } else {
                    $add_amt_per = $row['amty'] / $total;
                }
            } else {
                $add_amt_per = 0;
            }

            $total_gst = 0;

            for ($k = 0; $k < count($row['item']); $k++) {
                $sub = $row['item'][$k]['qty'] * $row['item'][$k]['rate'];

                if ($row['discount'] > 0) {
                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$k]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }

                $total_gst += ($final_sub * ($row['item'][$k]['igst'] / 100));
            }
            for ($j = 0; $j < count($row['item']); $j++) {
                //if($row['id'] )
                //echo '<pre>';print_r($row['item'][$j]['igst']);exit;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, user_date($row['return_date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['net_amount'], 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$total_gst, 2));
                $sub = $row['item'][$j]['qty'] * $row['item'][$j]['rate'];

                if ($row['discount'] > 0) {

                    $discount_amt = $sub * $disc_avg_per;
                    $final_sub = $sub - $discount_amt;
                    $add_amt = $sub * $add_amt_per;

                    $final_sub += $add_amt;
                } else {
                    $disc_amt = $sub * $row['item'][$j]['item_disc'] / 100;
                    $final_sub = $sub - $disc_amt;
                    $add_amt = $final_sub * $add_amt_per;

                    $final_sub += $add_amt;
                }
                $itm_igst = $final_sub * ($row['item'][$j]['igst'] / 100);
                $itm_cgst = $itm_igst / 2;
                $itm_sgst = $itm_igst / 2;

                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $row['item'][$j]['hsn']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $row['item'][$j]['qty']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $final_sub);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['item'][$j]['sgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, number_format(@$itm_sgst, 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['item'][$j]['cgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, number_format(@$itm_cgst, 2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$row['item'][$j]['igst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, number_format(@$itm_igst, 2));

                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle('Sales Gst Register');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_Purchase_gst_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();

        $builder = $db->table('purchase_invoice pi');
        $builder->select('pi.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('pi.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = pi.account');
        $builder->where(array('pi.is_delete' => 0));
        $builder->where(array('pi.is_cancle' => 0));
        $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $purchase_invoice = $query->getResultArray();

        $sinvoice_total = 0;

        foreach ($purchase_invoice as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('purchase_item pi');
            $builder->select('pi.*,i.hsn');
            $builder->join('item i', 'i.id = pi.item_id');
            $builder->where(array('pi.is_delete' => 0));
            $builder->where(array('pi.type' => 'invoice'));
            $builder->where(array('pi.parent_id' => $row['id']));
            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['purchase'][] = $row;
        }
        //echo '<pre>';Print_r($getdata);exit;

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_gnrl_purchase_gst_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();

        $builder = $db->table('purchase_general pi');
        $builder->select('pi.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('pi.party_account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = pi.party_account');
        $builder->where(array('pi.is_delete' => 0));
        $builder->where(array('pi.is_cancle' => 0));
        $builder->where(array('pi.v_type' => 'general'));
        $builder->where(array('DATE(pi.doc_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.doc_date)  <= ' => db_date($end_date)));

        $query = $builder->get();
        $purchase_invoice = $query->getResultArray();

        $sinvoice_total = 0;

        foreach ($purchase_invoice as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('purchase_particu pi');
            $builder->select('pi.*,ac.hsn');
            $builder->join('account ac', 'ac.id = pi.account');
            $builder->where(array('pi.is_delete' => 0));
            $builder->where(array('pi.type' => 'general'));
            $builder->where(array('pi.parent_id' => $row['id']));
            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['purchase'][] = $row;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function get_Debitnote_gst_register($post = '')
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $start_date = @$post['from'];
        $end_date = @$post['to'];

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

        $getdata = array();
        $builder = $db->table('purchase_return pi');
        $builder->select('pi.*,ac.name as account_name,ac.gst');
        if (isset($post['ac_id']) && $post['ac_id'] != '') {
            $builder->where(array('si.account' => $post['ac_id']));
        }
        $builder->join('account ac', 'ac.id = pi.account');
        $builder->where(array('pi.is_delete' => 0));
        $builder->where(array('pi.is_cancle' => 0));
        $builder->where(array('DATE(pi.return_date)  >= ' => db_date($start_date)));
        $builder->where(array('DATE(pi.return_date)  <= ' => db_date($end_date)));
        $query = $builder->get();
        $purchase_invoice = $query->getResultArray();

        $sinvoice_total = 0;

        foreach ($purchase_invoice as $row) {
            $sinvoice_total = $sinvoice_total + $row["net_amount"];

            $builder = $db->table('purchase_item si');
            $builder->select('si.*,i.hsn');
            $builder->join('item i', 'i.id = si.item_id');
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('si.type' => 'return'));
            $builder->where(array('si.parent_id' => $row['id']));
            $query = $builder->get();
            $items = $query->getResultArray();

            $row['item'] = $items;

            $getdata['purchase'][] = $row;
        }

        $getdata['start_date'] = $start_date;
        $getdata['end_date'] = $end_date;
        return $getdata;
    }

    public function ledger_xls_export_data($post)
    {
        $new_data = array();
        if (!empty($post['account_id'])) {
            $data = $this->get_ledger_register($post);
            $new_data = $data['data'];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:F4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Ledger Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$data['ac_name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', user_date(@$post['from']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B3', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C3', user_date(@$post['to']));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'Id');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Date');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Voucher No.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'Voucher Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'Credit');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'Debit');

        $i = 5;
        $credit = 0;
        $debit = 0;
        if (!empty($new_data)) {
            foreach ($new_data as $row) {


                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, user_date(@$row['date']));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['account_name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['vch_no']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['type']);

                if ($row['type'] == 'sale_return' || $row['type'] == 'sale_general_return' ||  $row['type'] == 'purchase_invoice' || $row['type'] == 'purchase_general' || $row['type'] == 'Receipt' || $row['type'] == 'cr') {
                    //$credit += (float)$row['net_amount'];

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @number_format(@$row['net_amount'], 2, '.', ""));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, '');
                } else {
                    //$debit += (float)$row['net_amount'];
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, '');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @number_format(@$row['net_amount'], 2, '.', ""));
                }
                $i++;
            }
        }
        $spreadsheet->getActiveSheet()->setTitle('ledger_report');

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function get_ledger_outstanding_list_new($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        //print_r($post);exit;

        $data = array();
        $data1 = array();
        if (!empty($post['date'])) {
            $today = $post['date'];
        } else {
            $today = date('Y-m-d');
        }
        $thirty = date('Y-m-d', strtotime('-30 days', strtotime($today))) . PHP_EOL;
        $sixty = date('Y-m-d', strtotime('-60 days', strtotime($today))) . PHP_EOL;
        $ninety = date('Y-m-d', strtotime('-90 days', strtotime($today))) . PHP_EOL;
        if (!empty($post['account_id'])) {
            //$page = 1;

            $data['id'] = $post['account_id'];
            $gmodel = new GeneralModel();
            $acc = $gmodel->get_data_table('account', array('id' => @$post['account_id']), 'id,name,opening_type,opening_bal');
            $data['name'] = $acc['name'];
            // $data['thirty'] = get_ledger_outstanding_list($thirty,$today,$post['account_id']);
            // $data['sixty'] = get_ledger_outstanding_list($sixty,$thirty,$post['account_id']);
            // $data['ninety'] = get_ledger_outstanding_list($ninety,$sixty,$post['account_id']);
            // $data['ninety_above'] = get_ledger_outstanding_list('',$ninety,$post['account_id']);
            $thirty_data = get_ledger_outstanding_list($thirty, $today, $post['account_id']);
            $sixty_data = get_ledger_outstanding_list($sixty, $thirty, $post['account_id']);
            $ninety_data = get_ledger_outstanding_list($ninety, $sixty, $post['account_id']);
            $ninety_above_data = get_ledger_outstanding_list('', $ninety, $post['account_id']);
            $debit_account = 0;
            $credit_account = 0;
            $sixty_credit = 0;
            $sixty_debit = 0;
            $ninety_credit = 0;
            $ninety_debit = 0;
            $thirty_credit = 0;
            $thirty_debit = 0;
            if ($acc['opening_type'] != '') {
                if ($acc['opening_bal'] != '') {
                    if ($acc['opening_type'] == 'Credit') {

                        $credit_account = $acc['opening_bal'];
                    } else {

                        $debit_account = $acc['opening_bal'];
                    }
                }
            }
            $ninety_above_credit = $ninety_above_data['credit'] + $credit_account;
            $ninety_above_debit = $ninety_above_data['debit'] + $debit_account;
            $data['ninety_above'] = $ninety_above_debit - $ninety_above_credit;
            //print_r($ninety_data['credit']);exit;
            if ($data['ninety_above'] < 0) {
                //print_r($ninety_data['credit']);
                $ninety_debit = $ninety_data['debit'];
                $ninety_credit = $ninety_data['credit'] + $data['ninety_above'];
            } else {
                $ninety_credit = $ninety_data['credit'];
                $ninety_debit = $ninety_data['debit'] + $data['ninety_above'];
            }
            //exit;
            $data['ninety'] = $ninety_debit - $ninety_credit;

            if ($data['ninety'] < 0) {
                $sixty_debit = $sixty_data['debit'];
                $sixty_credit = $sixty_data['credit'] + $data['ninety'];
            } else {
                $sixty_credit = $sixty_data['credit'];
                $sixty_debit = $sixty_data['debit'] + $data['ninety'];
            }
            $data['sixty'] = $sixty_debit - $sixty_credit;

            if ($data['sixty'] < 0) {
                $thirty_debit = $thirty_data['debit'];
                $thirty_credit = $thirty_data['credit'] + $data['sixty'];
            } else {
                $thirty_credit = $thirty_data['credit'];
                $thirty_debit = $thirty_data['debit'] + $data['sixty'];
            }
            $data['thirty'] = $thirty_debit - $thirty_credit;


            $data1[] = $data;
        } else if (!empty($post['party']) and empty($post['account_id'])) {
            if ($post['party'] == 'Sundry Creditors') {
                $builder = $db->table('gl_group');
                $builder->select('id');
                $builder->where(array('name' => 'Sundry Creditors'));
                $query = $builder->get();
                $gl_group = $query->getRowArray();

                $glgroup = gl_list([$gl_group['id']]);
                $glgroup[] = $gl_group['id'];



                $builder = $db->table('account acc');
                $builder->select('acc.id,acc.name,acc.intrest_rate,acc.opening_type,acc.opening_bal');
                $builder->join('gl_group gl', 'gl.id = acc.gl_group');
                $builder->where(array('acc.is_delete' => 0));
                $builder->whereIn('gl.id', $glgroup);
                $query = $builder->get();
                $account = $query->getResultArray();


                foreach ($account as $row) {

                    $data['id'] = $row['id'];
                    $data['name'] = $row['name'];
                    $thirty_data = get_ledger_outstanding_list($thirty, $today, $row['id']);
                    $sixty_data = get_ledger_outstanding_list($sixty, $thirty, $row['id']);
                    $ninety_data = get_ledger_outstanding_list($ninety, $sixty, $row['id']);
                    $ninety_above_data = get_ledger_outstanding_list('', $ninety, $row['id']);
                    $debit_account = 0;
                    $credit_account = 0;

                    if ($row['opening_type'] != '') {
                        if ($row['opening_bal'] != '') {
                            if ($row['opening_type'] == 'Credit') {

                                $credit_account = $row['opening_bal'];
                            } else {

                                $debit_account = $row['opening_bal'];
                            }
                        }
                    }
                    $ninety_above_credit = $ninety_above_data['credit'] + $credit_account;
                    $ninety_above_debit = $ninety_above_data['debit'] + $debit_account;
                    $data['ninety_above'] = $ninety_above_debit - $ninety_above_credit;
                    //print_r($ninety_data['credit']);exit;
                    if ($data['ninety_above'] < 0) {
                        //print_r($ninety_data['credit']);
                        $ninety_debit = $ninety_data['debit'];
                        $ninety_credit = $ninety_data['credit'] + $data['ninety_above'];
                    } else {
                        $ninety_credit = $ninety_data['credit'];
                        $ninety_debit = $ninety_data['debit'] + $data['ninety_above'];
                    }
                    //exit;
                    $data['ninety'] = $ninety_debit - $ninety_credit;

                    if ($data['ninety'] < 0) {
                        $sixty_debit = $sixty_data['debit'];
                        $sixty_credit = $sixty_data['credit'] + $data['ninety'];
                    } else {
                        $sixty_credit = $sixty_data['credit'];
                        $sixty_debit = $sixty_data['debit'] + $data['ninety'];
                    }
                    $data['sixty'] = $sixty_debit - $sixty_credit;

                    if ($data['sixty'] < 0) {
                        $thirty_debit = $thirty_data['debit'];
                        $thirty_credit = $thirty_data['credit'] + $data['sixty'];
                    } else {
                        $thirty_credit = $thirty_data['credit'];
                        $thirty_debit = $thirty_data['debit'] + $data['sixty'];
                    }
                    $data['thirty'] = $thirty_debit - $thirty_credit;
                    $data1[] = $data;
                }
            } else {
                $builder = $db->table('gl_group');
                $builder->select('id');
                $builder->where(array('name' => 'Sundry Debtors'));
                $query = $builder->get();
                $gl_group = $query->getRowArray();

                $glgroup = gl_list([$gl_group['id']]);
                $glgroup[] = $gl_group['id'];


                $builder = $db->table('account acc');
                $builder->select('acc.id,acc.name,acc.intrest_rate,acc.opening_type,acc.opening_bal');
                $builder->join('gl_group gl', 'gl.id = acc.gl_group');
                $builder->where(array('acc.is_delete' => 0));
                $builder->whereIn('gl.id', $glgroup);
                $query = $builder->get();
                $account = $query->getResultArray();

                $account_total = 0;
                // $total_closing = ($page-1) * 15; 
                foreach ($account as $row) {

                    $data['id'] = $row['id'];
                    $data['name'] = $row['name'];
                    $thirty_data = get_ledger_outstanding_list($thirty, $today, $row['id']);
                    $sixty_data = get_ledger_outstanding_list($sixty, $thirty, $row['id']);
                    $ninety_data = get_ledger_outstanding_list($ninety, $sixty, $row['id']);
                    $ninety_above_data = get_ledger_outstanding_list('', $ninety, $row['id']);
                    $debit_account = 0;
                    $credit_account = 0;

                    if ($row['opening_type'] != '') {
                        if ($row['opening_bal'] != '') {
                            if ($row['opening_type'] == 'Credit') {

                                $credit_account = $row['opening_bal'];
                            } else {

                                $debit_account = $row['opening_bal'];
                            }
                        }
                    }
                    $ninety_above_credit = $ninety_above_data['credit'] + $credit_account;
                    $ninety_above_debit = $ninety_above_data['debit'] + $debit_account;
                    $data['ninety_above'] = $ninety_above_debit - $ninety_above_credit;
                    //print_r($ninety_data['credit']);exit;
                    if ($data['ninety_above'] < 0) {
                        //print_r($ninety_data['credit']);
                        $ninety_debit = $ninety_data['debit'];
                        $ninety_credit = $ninety_data['credit'] + $data['ninety_above'];
                    } else {
                        $ninety_credit = $ninety_data['credit'];
                        $ninety_debit = $ninety_data['debit'] + $data['ninety_above'];
                    }
                    //exit;
                    $data['ninety'] = $ninety_debit - $ninety_credit;

                    if ($data['ninety'] < 0) {
                        $sixty_debit = $sixty_data['debit'];
                        $sixty_credit = $sixty_data['credit'] + $data['ninety'];
                    } else {
                        $sixty_credit = $sixty_data['credit'];
                        $sixty_debit = $sixty_data['debit'] + $data['ninety'];
                    }
                    $data['sixty'] = $sixty_debit - $sixty_credit;

                    if ($data['sixty'] < 0) {
                        $thirty_debit = $thirty_data['debit'];
                        $thirty_credit = $thirty_data['credit'] + $data['sixty'];
                    } else {
                        $thirty_credit = $thirty_data['credit'];
                        $thirty_debit = $thirty_data['debit'] + $data['sixty'];
                    }
                    $data['thirty'] = $thirty_debit - $thirty_credit;
                    $data1[] = $data;
                }
            }
        } else {

            $builder = $db->table('gl_group');
            $builder->select('id');
            $builder->where(array('name' => 'Sundry Creditors'));
            $query = $builder->get();
            $gl_group = $query->getRowArray();

            $builder = $db->table('gl_group');
            $builder->select('id');
            $builder->where(array('name' => 'Sundry Debtors'));
            $query = $builder->get();
            $gl_group1 = $query->getRowArray();

            $glgroup = gl_list([$gl_group['id']]);
            $glgroup[] = $gl_group['id'];
            //print_r($glgroup);exit;

            $glgroup1 = gl_list([$gl_group1['id']]);
            $glgroup1[] = $gl_group1['id'];
            $gl_grp = array_merge($glgroup, $glgroup1);



            $builder = $db->table('account acc');
            $builder->select('acc.id,acc.name,acc.intrest_rate,acc.opening_type,acc.opening_bal');
            $builder->join('gl_group gl', 'gl.id = acc.gl_group');
            $builder->where(array('acc.is_delete' => 0));
            $builder->whereIn('gl.id', $gl_grp);
            $query = $builder->get();
            $account = $query->getResultArray();


            foreach ($account as $row) {
                $data['id'] = $row['id'];
                $data['name'] = $row['name'];
                $thirty_data = get_ledger_outstanding_list($thirty, $today, $row['id']);
                $sixty_data = get_ledger_outstanding_list($sixty, $thirty, $row['id']);
                $ninety_data = get_ledger_outstanding_list($ninety, $sixty, $row['id']);
                $ninety_above_data = get_ledger_outstanding_list('', $ninety, $row['id']);
                $debit_account = 0;
                $credit_account = 0;

                if ($row['opening_type'] != '') {
                    if ($row['opening_bal'] != '') {
                        if ($row['opening_type'] == 'Credit') {

                            $credit_account = $row['opening_bal'];
                        } else {

                            $debit_account = $row['opening_bal'];
                        }
                    }
                }
                $ninety_above_credit = $ninety_above_data['credit'] + $credit_account;
                $ninety_above_debit = $ninety_above_data['debit'] + $debit_account;
                $data['ninety_above'] = $ninety_above_debit - $ninety_above_credit;
                //print_r($ninety_data['credit']);exit;
                if ($data['ninety_above'] < 0) {
                    //print_r($ninety_data['credit']);
                    $ninety_debit = $ninety_data['debit'];
                    $ninety_credit = $ninety_data['credit'] + $data['ninety_above'];
                } else {
                    $ninety_credit = $ninety_data['credit'];
                    $ninety_debit = $ninety_data['debit'] + $data['ninety_above'];
                }
                //exit;
                $data['ninety'] = $ninety_debit - $ninety_credit;

                if ($data['ninety'] < 0) {
                    $sixty_debit = $sixty_data['debit'];
                    $sixty_credit = $sixty_data['credit'] + $data['ninety'];
                } else {
                    $sixty_credit = $sixty_data['credit'];
                    $sixty_debit = $sixty_data['debit'] + $data['ninety'];
                }
                $data['sixty'] = $sixty_debit - $sixty_credit;

                if ($data['sixty'] < 0) {
                    $thirty_debit = $thirty_data['debit'];
                    $thirty_credit = $thirty_data['credit'] + $data['sixty'];
                } else {
                    $thirty_credit = $thirty_data['credit'];
                    $thirty_debit = $thirty_data['debit'] + $data['sixty'];
                }
                $data['thirty'] = $thirty_debit - $thirty_credit;
                $data1[] = $data;
            }
        }
        //exit;
        usort($data1, function ($a, $b) {
            return $b['ninety_above'] <=> $a['ninety_above'];
        });

        $new_data['data'] =  $data1;
        $new_data['today'] = $today;
        $new_data['thirty'] = $thirty;
        $new_data['sixty'] = $sixty;
        $new_data['ninety'] = $ninety;
        return $new_data;
    }
    public function ledger_outstanding_list_xls_export_data($post)
    {
        //print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $data = array();
        $data1 = array();
        if (!empty($post['date'])) {
            $today = $post['date'];
        } else {
            $today = date('Y-m-d');
        }
        $thirty = date('Y-m-d', strtotime('-30 days', strtotime($today))) . PHP_EOL;
        $sixty = date('Y-m-d', strtotime('-60 days', strtotime($today))) . PHP_EOL;
        $ninety = date('Y-m-d', strtotime('-90 days', strtotime($today))) . PHP_EOL;
        if (!empty($post['account_id'])) {
            //$page = 1;
            //print_r("fjfkm");exit;
            $data['id'] = $post['account_id'];
            $gmodel = new GeneralModel();
            $acc = $gmodel->get_data_table('account', array('id' => @$post['account_id']), 'id,name');
            $data['name'] = $acc['name'];
            $data['thirty'] = get_ledger_outstanding_list($thirty, $today, $post['account_id']);
            $data['sixty'] = get_ledger_outstanding_list($sixty, $thirty, $post['account_id']);
            $data['ninety'] = get_ledger_outstanding_list($ninety, $sixty, $post['account_id']);
            $data['ninety_above'] = get_ledger_outstanding_list('', $ninety, $post['account_id']);
            $data1[] = $data;
        } elseif (!empty($post['party']) and empty($post['account_id'])) {

            if ($post['party'] == 'Sundry Creditors') {
                //print_r("party_crediter");exit;
                $builder = $db->table('gl_group');
                $builder->select('id');
                $builder->where(array('name' => 'Sundry Creditors'));
                $query = $builder->get();
                $gl_group = $query->getRowArray();

                $glgroup = gl_list([$gl_group['id']]);
                $glgroup[] = $gl_group['id'];

                $builder = $db->table('account acc');
                $builder->select('acc.id,acc.name,acc.intrest_rate');
                $builder->join('gl_group gl', 'gl.id = acc.gl_group');
                $builder->where(array('acc.is_delete' => 0));
                $builder->whereIn('gl.id', $glgroup);
                $query = $builder->get();
                $account = $query->getResultArray();


                foreach ($account as $row) {

                    $data['id'] = $row['id'];
                    $data['name'] = $row['name'];
                    $data['thirty'] = get_ledger_outstanding_list($thirty, $today, $row['id']);
                    $data['sixty'] = get_ledger_outstanding_list($sixty, $thirty, $row['id']);
                    $data['ninety'] = get_ledger_outstanding_list($ninety, $sixty, $row['id']);
                    $data['ninety_above'] = get_ledger_outstanding_list('', $ninety, $row['id']);
                    $data1[] = $data;
                }
            } else {
                //print_r("party_debiter");exit;
                $builder = $db->table('gl_group');
                $builder->select('id');
                $builder->where(array('name' => 'Sundry Debtors'));
                $query = $builder->get();
                $gl_group = $query->getRowArray();

                $glgroup = gl_list([$gl_group['id']]);
                $glgroup[] = $gl_group['id'];


                $builder = $db->table('account acc');
                $builder->select('acc.id,acc.name,acc.intrest_rate');
                $builder->join('gl_group gl', 'gl.id = acc.gl_group');
                $builder->where(array('acc.is_delete' => 0));
                $builder->whereIn('gl.id', $glgroup);
                $query = $builder->get();
                $account = $query->getResultArray();

                foreach ($account as $row) {

                    $data['id'] = $row['id'];
                    $data['name'] = $row['name'];
                    $data['thirty'] = get_ledger_outstanding_list($thirty, $today, $row['id']);
                    $data['sixty'] = get_ledger_outstanding_list($sixty, $thirty, $row['id']);
                    $data['ninety'] = get_ledger_outstanding_list($ninety, $sixty, $row['id']);
                    $data['ninety_above'] = get_ledger_outstanding_list('', $ninety, $row['id']);
                    $data1[] = $data;
                }
            }
        } else {
            //print_r("all");exit;
            $builder = $db->table('gl_group');
            $builder->select('id');
            $builder->where(array('name' => 'Sundry Creditors'));
            $query = $builder->get();
            $gl_group = $query->getRowArray();

            $builder = $db->table('gl_group');
            $builder->select('id');
            $builder->where(array('name' => 'Sundry Debtors'));
            $query = $builder->get();
            $gl_group1 = $query->getRowArray();

            $glgroup = gl_list([$gl_group['id']]);
            $glgroup[] = $gl_group['id'];
            //print_r($glgroup);exit;

            $glgroup1 = gl_list([$gl_group1['id']]);
            $glgroup1[] = $gl_group1['id'];
            $gl_grp = array_merge($glgroup, $glgroup1);


            $builder = $db->table('account acc');
            $builder->select('acc.id,acc.name,acc.intrest_rate');
            $builder->join('gl_group gl', 'gl.id = acc.gl_group');
            $builder->where(array('acc.is_delete' => 0));
            $builder->whereIn('gl.id', $gl_grp);
            $query = $builder->get();
            $account = $query->getResultArray();

            foreach ($account as $row) {

                $data['id'] = $row['id'];
                $data['name'] = $row['name'];
                $data['thirty'] = get_ledger_outstanding_list($thirty, $today, $row['id']);
                $data['sixty'] = get_ledger_outstanding_list($sixty, $thirty, $row['id']);
                $data['ninety'] = get_ledger_outstanding_list($ninety, $sixty, $row['id']);
                $data['ninety_above'] = get_ledger_outstanding_list('', $ninety, $row['id']);
                $data1[] = $data;
            }
        }
        usort($data1, function ($a, $b) {
            return $b['ninety_above'] <=> $a['ninety_above'];
        });


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A5:F5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Ledget Outstanding List Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'Party:' . @$post['party']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', 'Account:' . @$post['account_id']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'start date:' . user_date(@$post['date']));

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'Ledger Id');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'Ledger Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', '90 Days Above');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', '60 to 90 Days');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', '30 to 60 Days');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', '0 to 30 Days');

        $i = 6;

        foreach ($data1 as $row) {


            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$ninety_above);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$ninety);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$sixty);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$thirty);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('ledger_outstanding_list');

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function update_gl_group_summary_table()
    {
       
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('gl_group');
        $builder->select('*');
        $query = $builder->get();
        $result = $query->getResultArray();
        foreach ($result as $row) {
          
           $builder_gl_summary = $db->table('gl_group_summary');
           $pdata = array(
                'gl_name' => $row['name'],
                'parent' => $row['parent'],
                'all_sub_glgroup' => '',
                'closing' => 0.00,
                'created_at' => $row['created_at'],
                'created_by' => $row['created_by'],
                'update_by' => $row['update_by'],
                'update_at' => $row['update_at'],
                'is_delete' => $row['is_delete'],
           );
           $result_gl = $builder_gl_summary->Insert($pdata);
               
        }
        if(isset($result_gl))
        {
            $msg = array("st"=>"succsess","msg"=>"succsess");
        }
        else
        {
            $msg = array("st"=>"fail","msg"=>"fail");
        }
        return  $msg;
    }
    public function get_gl_group_summary_query_data()
    {
       
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('gl_group_summary');
        $builder->select('*');
        $builder->where('is_delete', 0);
        //$builder->where('id', 44);
        $builder->orderBy('id', 'desc');
        $query = $builder->get();
        $result = $query->getResultArray();
       // echo '<pre>';Print_r($result);exit;
        
        $gnmodel = new GeneralModel();
        foreach ($result as $row) {
            
            $data = gl_group_summary_array($row['id']); 
            foreach($data as $gl_data)
            {
                $new_sub = array();
                $new_array = array();
                $old_gl = array();
                $new_gl = array();
                $get_data = $gnmodel->get_data_table('gl_group_summary', array('id' => $gl_data['id']),'all_sub_glgroup');
               
                if(!empty($get_data['all_sub_glgroup']))
                {
                    
                    $old_gl = explode(",",$get_data['all_sub_glgroup']);
                    $new_gl[] = $row['id'];
                    $new_array= array_merge($old_gl,$new_gl);
                   $new_sub = implode(',',$new_array);
                   $result_gl = $gnmodel->update_data_table('gl_group_summary', array('id' => $gl_data['id']), array('all_sub_glgroup' => $new_sub));
      
                    
                }    
                else
                {
                     $result_gl = $gnmodel->update_data_table('gl_group_summary', array('id' => $gl_data['id']), array('all_sub_glgroup' => $row['id']));
                }
                //update_gl
                //$result_up = $gnmodel->update_data_table('gl_group_summary', array('id' => $gl_data['id']), array('update_gl' => 1));

            }
           
        }
      
        if(isset($result_gl))
        {
            $msg = array("st"=>"succsess","msg"=>"succsess");

        }
        else
        {
            $msg = array("st"=>"fail","msg"=>"fail");
        }
        return  $msg;
    }
    public function get_closing_bal_report_data($post)
    {

        $start_date = $post['from'];
        $end_date = $post['to'];

        $gmodel = new GeneralModel;
        $gl_capital = $gmodel->get_data_table('gl_group_summary', array('gl_name' => 'Capital'), 'id,gl_name,all_sub_glgroup');
        $gl_loan = $gmodel->get_data_table('gl_group_summary', array('gl_name' => 'Loans'), 'id,gl_name,all_sub_glgroup');
        $gl_lib = $gmodel->get_data_table('gl_group_summary', array('gl_name' => 'Current Liabilities'), 'id,gl_name,,all_sub_glgroup');
        $gl_fixedassets = $gmodel->get_data_table('gl_group_summary', array('gl_name' => 'Fixed Assets'), 'id,gl_name,,all_sub_glgroup');
        $gl_currentassets = $gmodel->get_data_table('gl_group_summary', array('gl_name' => 'Current Assets'), 'id,gl_name,,all_sub_glgroup');
        $gl_otherassets = $gmodel->get_data_table('gl_group_summary', array('gl_name' => 'Other Assets'), 'id,gl_name,,all_sub_glgroup');
      
           $capital_account_list = array();
            if(!empty($gl_capital['all_sub_glgroup']))
            {
                
                $capital_data_main_gl = capital_data($gl_capital['id'],$start_date,$end_date);
                $main_gl_account_data = $capital_data_main_gl['account'];

                $list_sub_gl = $gl_capital['all_sub_glgroup'];
                $array_sub_gl = explode(",",$list_sub_gl);
               
                foreach($array_sub_gl as $array_sub_glrow)
                { 
                    $capital_data_gl = capital_data($array_sub_glrow,$start_date,$end_date);
                    $sub_gl_account_data = $capital_data_gl['account'];
                    foreach($sub_gl_account_data as $key => $row)
                    {
                        $sub_gl_account[$key] = $row;
                    }
                }
                $capital_account_list = array_merge($main_gl_account_data,$sub_gl_account);
            }
            else
            {
                $capital_data = capital_data($gl_capital['id'],$start_date,$end_date);       
                $capital_account_list = $capital_data['account'];
               
            }
            //secho '<pre>';Print_r($capital_account_list);exit;
            
            $loan_account_list = array();
            if(!empty($gl_loan['all_sub_glgroup']))
            {
                $loan_data_main_gl = loans_data($gl_loan['id'],$start_date,$end_date);
                $main_gl_account_data = $loan_data_main_gl['account'];

                $list_sub_gl = $gl_loan['all_sub_glgroup'];
                $array_sub_gl = explode(",",$list_sub_gl);
                $sub_gl_account = array();
                foreach($array_sub_gl as $array_sub_glrow)
                { 
                    $loan_data_gl = capital_data($array_sub_glrow,$start_date,$end_date);
                    $sub_gl_account_data = $loan_data_gl['account'];
                    foreach($sub_gl_account_data as $key => $row)
                    {
                        $sub_gl_account[$key] = $row;
                    }
                }
                $loan_account_list = array_merge($main_gl_account_data,$sub_gl_account);
            }
            else
            {
                $loan_data = loans_data($gl_loan['id'],$start_date,$end_date);
                $loan_account_list = $loan_data['account'];
               
            }
            $liability_account_list = array();
            if(!empty($gl_lib['all_sub_glgroup']))
            {
                
                $liability_data_main_gl = Currlib_data($gl_lib['id'],$start_date,$end_date);
                
                $main_gl_account_data = $liability_data_main_gl['account'];

                $list_sub_gl = $gl_lib['all_sub_glgroup'];
                $array_sub_gl = explode(",",$list_sub_gl);
                $sub_gl_account = array();
                foreach($array_sub_gl as $array_sub_glrow)
                { 
                    $liability_data_gl = Currlib_data($array_sub_glrow,$start_date,$end_date);
                   
                    $sub_gl_account_data = $liability_data_gl['account'];
                    foreach($sub_gl_account_data as $key => $row)
                    {
                        $sub_gl_account[$key] = $row;
                    }
                }
               // echo '<pre>';Print_r($sub_gl_account);exit; 
                $liability_account_list = array_merge($main_gl_account_data,$sub_gl_account);
               
            }
            else
            {
                $liability_data = Currlib_data($gl_capital['id'],$start_date,$end_date);       
                $liability_account_list = $liability_data['account'];
               
            }
           
            
            $current_assets_account_list = array();
            if(!empty($gl_currentassets['all_sub_glgroup']))
            {
                
                $current_assets_data_main_gl = Current_Assets_data($gl_currentassets['id'],$start_date,$end_date);
                $main_gl_account_data = $current_assets_data_main_gl['account'];

                $list_sub_gl = $gl_currentassets['all_sub_glgroup'];
                $array_sub_gl = explode(",",$list_sub_gl);
                $sub_gl_account = array();
                foreach($array_sub_gl as $array_sub_glrow)
                { 
                    $current_assets_data_gl = Current_Assets_data($array_sub_glrow,$start_date,$end_date);
                    $sub_gl_account_data = $current_assets_data_gl['account'];
                    foreach($sub_gl_account_data as $key => $row)
                    {
                        $sub_gl_account[$key] = $row;
                    }
                }
                $current_assets_account_list = array_merge($main_gl_account_data,$sub_gl_account);
            }
            else
            {
                $current_assets_data = Current_Assets_data($gl_currentassets['id'],$start_date,$end_date);
                $current_assets_account_list = $current_assets_data['account'];
               
            }
            //echo '<pre>';Print_r($current_assets_account_list);exit;
            
            $fixed_assets_account_list = array();
            
            
            if(!empty($gl_fixedassets['all_sub_glgroup']))
            {
               
                $fixed_assets_data_main_gl = Fixed_Assets_data($gl_fixedassets['id'],$start_date,$end_date);
                $main_gl_account_data = $fixed_assets_data_main_gl['account'];
               
                $list_sub_gl = $gl_fixedassets['all_sub_glgroup'];
                $array_sub_gl = explode(",",$list_sub_gl);
                $sub_gl_account = array();
                foreach($array_sub_gl as $array_sub_glrow)
                { 
                    $fixed_assets_data_gl = Fixed_Assets_data($array_sub_glrow,$start_date,$end_date);
                    //echo '<pre>';Print_r($fixed_assets_data_gl);
                    $sub_gl_account_data = $fixed_assets_data_gl['account'];
                    foreach($sub_gl_account_data as $key => $row)
                    {
                        $sub_gl_account[$key] = $row;
                    }
                }
                //exit;
                $fixed_assets_account_list = array_merge($main_gl_account_data,$sub_gl_account);
            }
            else
            {
                $fixed_assets_data = Fixed_Assets_data($gl_fixedassets['id'],$start_date,$end_date);
                $fixed_assets_account_list = $fixed_assets_data['account'];
               
            }
            //echo '<pre>';Print_r($fixed_assets_account_list);exit;
            $other_assets_account_list = array();
            if(!empty($gl_otherassets['all_sub_glgroup']))
            {
               
                $other_assets_data_main_gl = Other_Assets_data($gl_otherassets['id'],$start_date,$end_date);
                $main_gl_account_data = $other_assets_data_main_gl['account'];

                $list_sub_gl = $gl_otherassets['all_sub_glgroup'];
                $array_sub_gl = explode(",",$list_sub_gl);
                $sub_gl_account = array();
                foreach($array_sub_gl as $array_sub_glrow)
                { 
                    $other_assets_data_gl = Other_Assets_data($array_sub_glrow,$start_date,$end_date);
                    $sub_gl_account_data = $other_assets_data_gl['account'];
                    $sub_gl_account = array();
                    foreach($sub_gl_account_data as $key => $row)
                    {
                        $sub_gl_account[$key] = $row;
                    }
                }
                $other_assets_account_list = array_merge($main_gl_account_data,$sub_gl_account);
            }
            else
            {
                $other_assets_data = Other_Assets_data($gl_otherassets['id'],$start_date,$end_date);
                $other_assets_account_list = $other_assets_data['account']; 
            }
            $last_array = array_merge($capital_account_list,$loan_account_list,$liability_account_list,$current_assets_account_list,$fixed_assets_account_list,$other_assets_account_list);
            return $last_array;   
    }
    public function get_closing_bal_account_report_data($post)
    {
        $gmodel  = new GeneralModel();
        $tmodel  = new TradingModel();
        $bmodel  = new BalancesheetModel();
        $acc = $gmodel->get_data_table('account', array('id' => $post['account_id']), 'opening_bal,opening_type');
        $data = array();
        $opening_bal = 0;
        if($post['type'] == 'capital' OR $post['type'] == 'loan' OR $post['type'] == 'current liabilities')
        {
            if ($acc['opening_type'] == 'Debit') {
                $opening_bal -= (float)@$acc['opening_bal'];
            } else {
                $opening_bal += (float)@$acc['opening_bal'];
            }
        }
        else
        {
            if ($acc['opening_type'] == 'Debit') {
                $opening_bal = (float)@$acc['opening_bal'];
            } else {
                $opening_bal -= (float)@$acc['opening_bal'];
            }
        }
        $send['id'] = $post['account_id'];
        $send['from'] = $post['from'];
        $send['to'] = $post['to'];
        if($post['type'] == 'capital' OR $post['type'] == 'loan')
        {
            $bank_data = $tmodel->bank_cash_voucher_wise_data($send);
            $jv_data= $tmodel->jv_voucher_wise_data($send);
          
            $data['bank'] = $bank_data['sales'];
            $data['jv'] = $jv_data['sales'];
        }
        elseif($post['type'] == 'current liabilities')
        {
            $purchase_invoice = $bmodel->purchase_voucher_wise_data($send);
            $purchase_return = $bmodel->purchase_ret_voucher_wise_data($send);
            $purchase_general = $bmodel->generalPurchase_liabi_voucher_wise_data($send);
            $sales_invoice = $bmodel->sales_voucher_wise_data($send);
            $sales_return = $bmodel->sales_ret_voucher_wise_data($send);
            $sales_general = $bmodel->generalSales_liabi_voucher_wise_data($send);
            $bank_data = $tmodel->bank_cash_voucher_wise_data($send);
            $jv_data= $tmodel->jv_voucher_wise_data($send);
          
            $data['purchase_invoice'] = $purchase_invoice['purchase'];
            $data['purchase_return'] = $purchase_return['purchase_ret'];
            $data['purchase_general'] = $purchase_general['purchase'];
            $data['sales_invoice'] = $sales_invoice['sales'];
            $data['sales_return'] = $sales_return['sales_ret'];
            $data['sales_general'] = $sales_general['sales'];
            $data['bank'] = $bank_data['sales'];
            $data['jv'] = $jv_data['sales'];
         
        }
        elseif($post['type'] == 'fixed assets' OR $post['type'] == 'other assets')
        {
            $sales_general = $bmodel->generalSales_voucher_wise_data($send);
            $purchase_general = $bmodel->generalPurchase_voucher_wise_data($send);
            $bank_data = $bmodel->fixedassets_bankcash_voucher_Perwise($send);
            $jv_data = $bmodel->fixedassets_jv_voucher_wise($send);

            $data['sales_general'] = $sales_general['sales'];
            $data['purchase_general'] = $purchase_general['purchase'];
            $data['bank'] = $bank_data['fixedassets_banktrans'];
            $data['jv'] = $jv_data['fixedassets_jv'];
           // echo '<pre>';Print_r($data);exit;
            
        }
        elseif($post['type'] == 'current assets')
        {
            $sales_invoice = $bmodel->currentassets_salesinvoice_voucher_wise($send);
            $sales_return = $bmodel->currentassets_salesreturn_voucher_wise($send);
            $sales_general = $bmodel->currentassets_gnrl_sale_voucher_data($send);
            $sales_general_return = $bmodel->currentassets_gnrl_sale_rtn_voucher_wise($send);
            $bank_per_data = $bmodel->currentassets_bankcash_voucher_Perwise($send);
            $bank_acc_data = $bmodel->currentassets_bankcash_voucher_Acwise($send);
            $contra_per_data = $bmodel->currentassets_contra_voucher_Perwise($send);
            $contra_acc_data = $bmodel->currentassets_contra_voucher_Acwise($send);
            $jv_data= $bmodel->currentassets_jv_voucher_wise($send); 
            //echo '<pre>';Print_r($bank_per_data);exit;
            

            $data['sales_invoice'] = $sales_invoice['currentassets_salesinvoice'];
            $data['sales_return'] = $sales_return['currentassets_salesreturn'];
            $data['curr_sales_general'] = $sales_general['currentassets_salesinvoice'];
            $data['sales_general_return'] = $sales_general_return['currentassets_salesreturn'];
            $data['bank_per'] = $bank_per_data['currentassets_banktrans'];
            $data['bank_acc'] = $bank_acc_data['currentassets_banktrans'];
            $data['contra_per'] = $contra_per_data['currentassets_contratrans'];
            $data['contra_acc'] = $contra_acc_data['currentassets_ac_contratrans'];
            $data['curr_jv'] = $jv_data['currentassets_jv'];
        }
        else
        {
            
        }
        $data['start_date'] = $post['from'];
        $data['end_date'] = $post['to'];
        $data['type'] = $post['type'];
        $data['account_id'] = $post['account_id'];
        $data['opening_bal'] = @$opening_bal;
        $data['opening_type'] = $acc['opening_type'];
        //echo '<pre>';Print_r($data);exit;
        
        
        return $data;
        //echo '<pre>';Print_r($data);exit;
        
        
    }

}
