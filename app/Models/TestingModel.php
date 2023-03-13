<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\GeneralModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TestingModel extends Model
{
    public function get_hsn_core_data($type, $start_date = '', $end_date = '')
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
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        if ($type == "sales_invoice") {
            //print_r("jkdfhke");exit;
            $vch_type = "'sale_invoice' as vch_type";

            $builder = $db->table('sales_item si');
            //$builder->select('si.*,i.hsn,s.taxes,s.disc_type,s.discount,'.$vch_type);
            $builder->select('si.parent_id,s.invoice_date as date,si.taxability,si.type,si.item_id,si.uom,si.qty,si.rate,si.igst,si.igst_amt,si.cgst_amt,si.sgst_amt,si.item_disc, i.name,i.hsn, s.taxes,s.gst,s.custom_inv_no as cinv_no,ac.name as account_name,s.disc_type, s.discount,' . $vch_type);
            $builder->join('item i', 'i.id = si.item_id');
            $builder->join('sales_invoice s', 's.id = si.parent_id');
            $builder->join('account ac', 'ac.id = s.account');
            $builder->where(array('si.type' => 'invoice'));
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('s.is_delete' => 0));
            $builder->where(array('s.is_cancle' => 0));
            $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $invoice_item = $query->getResultArray();
            $data['sales'] = $invoice_item;
        } else {
            $vch_type_ret = "'sale_return' as vch_type";
            $builder = $db->table('sales_item si');
            $builder->select('si.parent_id,s.return_date as date,si.taxability,si.type,si.item_id,si.uom,si.qty,si.rate,si.igst,si.igst_amt,si.cgst_amt,si.sgst_amt,si.item_disc, i.name,i.hsn, s.taxes,s.gst,s.supp_inv as cinv_no,ac.name as account_name,s.disc_type, s.discount,' . $vch_type_ret);
            $builder->join('item i', 'i.id = si.item_id');
            $builder->join('sales_return s', 's.id = si.parent_id');
            $builder->join('account ac', 'ac.id = s.account');
            $builder->where(array('si.type' => 'return'));
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('s.is_delete' => 0));
            $builder->where(array('s.is_cancle' => 0));
            $builder->where(array('DATE(s.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(s.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $return_item = $query->getResultArray();
            $data['sales'] = $return_item;
        }

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        //$data = array_merge($invoice_item,$return_item);
        //echo '<pre>';Print_r($data);exit;

        return $data;
    }

    public function test_xls_export_data($post)
    {

        $data = $this->get_hsn_core_data($post['type'], db_date($post['from']), db_date($post['to']));

        //echo "<pre>";print_r($data);exit;

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

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Summary');


        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SI No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Date');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Voucher Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Custome Inv No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'Account Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'Taxability');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'Item ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'Item Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'Uom');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'Rate');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'Igst');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N4', 'Igst Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O4', 'cgst Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('P4', 'sgst Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q4', 'Item Discount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('R4', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('S4', 'Taxes');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('T4', 'Gst No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('U4', 'Discount Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('V4', 'Discount');


        $i = 5;
        // echo '<pre>';print_r($final_b2b);exit;
        foreach ($data['sales'] as $row) {

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['parent_id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['date']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['vch_type']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['cinv_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$row['taxability']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$row['type']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$row['item_id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['uom']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, @$row['qty']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['rate']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, @$row['igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$row['igst_amt']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, @$row['cgst_amt']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, @$row['sgst_amt']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q' . $i, @$row['item_disc']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('R' . $i, @$row['hsn']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('S' . $i, @$row['taxes']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('T' . $i, @$row['gst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('U' . $i, @$row['disc_type']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('V' . $i, @$row['discount']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('core_hsn_data');
        //$objPHPExcel->getActiveSheet()->setTitle("Title");

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function insert_edit_shortcut_key($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('shortcut_key');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        $builder = $db->table('shortcut_key');
        $builder->select('*');
        $builder->where(array("key_char" => $post['key_char']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array1 = $result->getRow();

        $builder = $db->table('shortcut_key');
        $builder->select('*');
        $builder->where(array("voucher_type" => $post['voucher_type']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array2 = $result->getRow();

        if (!empty($result_array1)) {
            if ($result_array1->id != $post['id']) {
                $msg = array('st' => 'fail', 'msg' => "Key Already Used!!!");
                return $msg;
            }
        }
        if (!empty($result_array2)) {
            if ($result_array2->id != $post['id']) {
                $msg = array('st' => 'fail', 'msg' => "Key Already Set!!!");
                return $msg;
            }
        }

        $pdata = array(
            'key_char' => $post['key_char'],
            'key_code' => $post['key_code'],
            'voucher_type' => $post['voucher_type'],
        );
        if (!empty($result_array)) {

            $pdata['updated_at'] = date('Y-m-d H:i:s');
            $pdata['updated_by'] = session('uid');

            if (empty($msg)) {
                $builder = $db->table('shortcut_key');
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                $id = $db->insertID();
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details added fail");
                }
            }
        }

        return $msg;
    }
    public function get_shortcutkey_data($get)
    {
        $dt_search = $dt_col = array(
            "bt.id",
            "bt.key_char",
            "bt.key_code",
            "bt.voucher_type",

        );

        $filter = $get['filter_data'];
        $tablename = "shortcut_key bt";
        $where = '';

        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        //$statusarray = array("1" => "Activate", "0" => "Deactivate");

        foreach ($rResult['table'] as $row) {
            $DataRow = array();

            $btnedit = '<a   href="' . url('Bank/add_banktrans/') . $row['id'] . '"  data-title="Edit Receipt: "' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Receipt No: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            //  $status= '<a  tabindex="-1" onclick="editable_os(this)"  data-val="'.$row['id'].'"  data-pk="'.$row['id'].'" >'.$statusarray[$row['status']].'</a>';
            $btn = $btnedit . $btndelete;

            $DataRow[] = $row['id'];
            $DataRow[] = $row['key_char'];
            $DataRow[] = $row['key_code'];
            $DataRow[] = $row['voucher_type'];
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    // jv management grouping by party updated by 19-01-2023
    public function jv_invoice_list($post)
    {

        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        $builder = $db->table('jv_management jm');
        $builder->select('jm.party_account,ac.name as party_account_name');
        $builder->join('account ac', 'ac.id = jm.party_account');
        $builder->join('platform_voucher pv', 'pv.voucher = jm.invoice_no','left');
        $builder->join('platform p', 'p.id = pv.platform_id','left');
        if (!empty($post['month'])) {
            $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
        }
        if (!empty($post['platform_id'])) {
             $builder->where(array('pv.platform_id' => $post['platform_id']));
        }
        $builder->where(array("jm.type" => 'invoice'));
        $builder->groupBy('jm.party_account');
        $result = $builder->get();
        $party_list = $result->getResultArray();
       // echo $db->getLastQuery();exit;
    
        $invoice_list = array();
        foreach ($party_list as $row) {
            $builder = $db->table('jv_management jm');
            $builder->select('jm.*');
            $builder->join('platform_voucher pv', 'pv.voucher = jm.invoice_no');
            if (!empty($post['month'])) {
                $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
            }
            if (!empty($post['platform_id'])) {
                $builder->where(array('pv.platform_id' => $post['platform_id']));
            }
            $builder->where(array("jm.type" => 'invoice', "jm.party_account" => $row['party_account']));
            $result = $builder->get();
            $jv_invoice_list = $result->getResultArray();
            $jv_invoice_list_new = array();
            foreach($jv_invoice_list as $jv_inv_row)
            {
                if($jv_inv_row['is_update'] == 0 AND ($jv_inv_row['is_delete'] == 1 OR $jv_inv_row['is_cancle'] == 1))
                {
                    
                }
                else
                {
                    $jv_invoice_list_new[] = $jv_inv_row;
                } 
            }
           

            
            $total =0;
            $jv_pass = array();
            $is_upadte = array();
            $is_delete = array();
            $is_cancel = array();
            foreach($jv_invoice_list_new as $row1)
            {
                $jv_pass[] = $row1['jv_pass'];
                $total += $row1['amount'];
                $is_upadte[] = $row1['is_update'];
                $is_delete[] = $row1['is_delete'];
                $is_cancel[] = $row1['is_cancle']; 

            }
            $get_arrayunique = array_unique($jv_pass);
            $count_arrayunique = count($get_arrayunique);

            $is_updateunique = array_unique($is_upadte);
            $count_is_updateunique = count($is_updateunique);
            
            $is_cancelunique = array_unique($is_cancel);
            $count_is_cancelunique = count($is_cancelunique);

            $is_deleteunique = array_unique($is_delete);
            $count_is_deleteunique = count($is_deleteunique);
            // echo '<br>';
            // echo '<pre>';Print_r('$account'.$row['party_account']);
            // echo '<pre>';Print_r('$count_arrayunique'.$count_arrayunique);
            // echo '<pre>';Print_r('$get_arrayunique[0]'.$get_arrayunique[0]);
            

            if($count_arrayunique == 1 AND $get_arrayunique[0] == 0)
            {
               $status = '<span style="color: blue;"> New </span>';
            }
            // elseif($count_arrayunique == 1 AND $get_arrayunique[0] == 1 AND $count_is_updateunique == 1
            //  AND $is_updateunique[0] == 0 AND $count_is_cancelunique == 1 AND $is_cancelunique[0] == 0
            //  AND $count_is_deleteunique == 1 AND $is_deleteunique[0] == 0)
            elseif($count_arrayunique == 1 AND $get_arrayunique[0] == 1 AND $count_is_updateunique == 1 AND $is_updateunique[0] == 0)
            
            {
                $status = '<span style="color: green;"> Updated </span>';
            }
            else
            {
                $status = '<span style="color: orange;"> Remaining </span>';
            }
            //echo '<pre>';Print_r($jv_pass);exit;
            
            //$check_status = array_unique($jv_pass);
            //echo '<pre>';Print_r($check_status);
            
            $row['total'] = $total;
            $row['status'] = $status;
            $invoice_list[] = $row;
        }
         //exit;
        //echo '<pre>';Print_r($invoice_list);exit;
        
        $data['invoice_list'] = $invoice_list;


        $return_list = array();
        $builder = $db->table('jv_management jm');
        $builder->select('jm.party_account,ac.name as party_account_name');
        $builder->join('account ac', 'ac.id = jm.party_account');
        $builder->join('platform_voucher pv', 'pv.voucher = jm.invoice_no','left');
        $builder->join('platform p', 'p.id = pv.platform_id','left');
        if (!empty($post['month'])) {
            $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
        }
        if (!empty($post['platform_id'])) {
             $builder->where(array('pv.platform_id' => $post['platform_id']));
        }
        $builder->where(array("jm.type" => 'return'));
        $builder->groupBy('jm.party_account');
        $result = $builder->get();
        $party_list = $result->getResultArray();

        foreach ($party_list as $row) {
            $builder = $db->table('jv_management jm');
            $builder->select('jm.*');
            $builder->join('platform_voucher pv', 'pv.voucher = jm.invoice_no');
            if (!empty($post['month'])) {
                $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
            }
            if (!empty($post['platform_id'])) {
                $builder->where(array('pv.platform_id' => $post['platform_id']));
            }

            $builder->where(array("jm.type" => 'return', "jm.party_account" => $row['party_account']));
            $result = $builder->get();
            $jv_return_list = $result->getResultArray();
        
            
            $jv_return_list_new = array();
            foreach($jv_return_list as $jv_ret_row)
            {
                if($jv_ret_row['is_update'] == 0 AND ($jv_ret_row['is_delete'] == 1 OR $jv_ret_row['is_cancle'] == 1))
                {
                   
                }
                else
                {
                    $jv_return_list_new[] = $jv_ret_row;
                }
            }
            $total =0;
            $jv_pass_ret = array();
            $is_upadte_ret = array();
            $is_delete_ret = array();
            $is_cancel_ret = array();
            //echo '<pre>';Print_r($jv_return_list_new);
             foreach($jv_return_list_new as $row1)
             {
                 $jv_pass_ret[] = $row1['jv_pass'];
                 $total += $row1['amount'];
                 $is_upadte_ret[] = $row1['is_update'];
                 $is_delete_ret[] = $row1['is_delete'];
                 $is_cancel_ret[] = $row1['is_cancle']; 
 
             }
             //echo '<pre>';Print_r($is_upadte);
             
             $get_arrayunique = array_unique($jv_pass_ret);
             $count_arrayunique = count($get_arrayunique);
 
             $is_updateunique = array_unique($is_upadte_ret);
             $count_is_updateunique = count($is_updateunique);
             
             $is_cancelunique = array_unique($is_cancel_ret);
             $count_is_cancelunique = count($is_cancelunique);
 
             $is_deleteunique = array_unique($is_delete_ret);
             $count_is_deleteunique = count($is_deleteunique);
            //   echo '<br>';
            // echo '<pre>';Print_r('$account'.$row['party_account']);
            // echo '<pre>';Print_r('$count_arrayunique'.$is_updateunique);
           // echo '<pre>';Print_r('$get_arrayunique[0]'.$is_updateunique[0]);
 
             if($count_arrayunique == 1 AND $get_arrayunique[0] == 0)
             {
                $status = '<span style="color: blue;"> New </span>';
             }
            //  elseif($count_arrayunique == 1 AND $get_arrayunique[0] == 1 AND $count_is_updateunique == 1
            //   AND $is_updateunique[0] == 0 AND $count_is_cancelunique == 1 AND $is_cancelunique[0] == 0
            //   AND $count_is_deleteunique == 1 AND $is_deleteunique[0] == 0)
            elseif($count_arrayunique == 1 AND $get_arrayunique[0] == 1 AND $count_is_updateunique == 1 AND $is_updateunique[0] == 0)
            //   AND $is_updateunique[0] == 0 
             {
                 $status = '<span style="color: green;"> Updated </span>';
             }
            
             else
             {
                 $status = '<span style="color: orange;"> Remaining </span>';
             }
           
             
             $row['total'] = $total;
             $row['status'] = $status;
             
            $return_list[] = $row;
        }
        //exit;
        $data['return_list'] = $return_list;

        $data['month'] = $post['month'];
        $data['year'] = $post['year'];
        $data['platform_id'] = $post['platform_id'];
        return $data;
    }
    public function add_jv_invoice($post)
    {
        //secho '<pre>';Print_r($post);exit;

        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        // $builder = $db->table('jv_management jm');
        // $builder->select('jm.invoice_no');
        // $builder->join('platform_voucher pv', 'pv.voucher = jm.invoice_no','left');
        // if (!empty($post['month'])) {
        //     $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
        //     $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
        // }
        // if (!empty($post['platform_id'])) {
        //      $builder->where(array('pv.platform_id' => $post['platform_id']));
        // }
        // $builder->where(array("jm.type" => 'invoice'));
        // $builder->groupBy('jm.party_account');
        // $result = $builder->get();
        // $invoice_list = $result->getResultArray();
        // foreach($invoice_list as $invoice)
        // {
            
        //     $invoice_array[] = $invoice['invoice_no'];
        // }
        // $narration = implode(',',$invoice_array);
        //echo '<pre>';Print_r($narration);exit;
        


        foreach ($post['invoice'] as $account) {
            $builder = $db->table('jv_management jm');
            $builder->select('jm.*');
            if (!empty($post['month'])) {
                $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
            }
            if (!empty($post['platform_id'])) {
                $builder->where(array('platform_id' => $post['platform_id']));
            }
            $builder->where(array("jm.type" => 'invoice', "jm.party_account" => $account));
            $result = $builder->get();
            $invoice_list = $result->getResultArray();
            $jv_array = array();
            $party_total = 0;
            foreach ($invoice_list as $row) {
                if ($row['jv_pass'] == 1) {
                    $jv_array[] = 1;
                } else {
                    $jv_array[] = 0;
                }
                $party_total += $row['amount'];
                $invoice_array[] = $row['invoice_no'];
            }
            $narration = implode(',',$invoice_array);
           // echo '<pre>';Print_r($narration);exit;
            
            $gnmodel = new GeneralModel();
            if (in_array("1", $jv_array)) {

                foreach ($invoice_list as $row) {
                    $jv_data = $gnmodel->get_data_table('jv_particular', array('jv_id' => $row['jv_id'], 'dr_cr' => 'dr'), 'particular');
                    $post['credit_party_account'] = $jv_data['particular'];
                }
                $jv_id = $invoice_list[0]['jv_id'];
                $total_amt = 0;
                $pdata['narration'] = $narration;
                $pdata['update_at'] = date('Y-m-d H:i:s');
                $pdata['update_by'] = session('uid');;

                $builder_main = $db->table('jv_main');
                $builder_main->where(array("id" => $jv_id));
                $result = $builder_main->Update($pdata);
                foreach ($invoice_list as $row) {
                    if ($row['jv_pass'] == 1) {
                        if ($row['is_update'] == 1 && $row['is_cancle'] != 1 && $row['is_delete'] != 1) {
                            $invoice = $gnmodel->get_data_table('sales_invoice', array('id' => $row['invoice_no']), 'net_amount');
                            $total_amt += $invoice['net_amount'];
                            $update_at = date('Y-m-d H:i:s');
                            $update_by = session('uid');
                            $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $row['invoice_no'], 'type' => "invoice"), array('amount' => $invoice['net_amount'], 'is_update' => 0, 'updated_at' => $update_at, 'updated_by' => $update_by));
                        } elseif ($row['is_update'] == 1 AND ($row['is_cancle'] == 1 || $row['is_delete'] == 1)) {
                            $update_at = date('Y-m-d H:i:s');
                            $update_by = session('uid');
                            $total_amt += 0;
                            $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $row['invoice_no'], 'type' => "invoice"), array('is_update' => 0, 'updated_at' => $update_at, 'updated_by' => $update_by));
                
                        } 
                        elseif ($row['is_update'] == 0 AND ($row['is_cancle'] == 1 || $row['is_delete'] == 1)) {
                        
                            $total_amt += 0;
                         }
                       
                        else {
                            $total_amt += $row['amount'];
                        }
                    } else {
                        $created_at = date('Y-m-d H:i:s');
                        $created_by = session('uid');
                        $result_jv = $gnmodel->update_data_table('jv_management', array('id' => $row['id']), array('jv_pass' => '1', 'jv_id' => $jv_id, 'created_at' => $created_at, 'created_by' => $created_by));

                        $total_amt += $row['amount'];
                    }
                }
                $update_jv_parti1 = $gnmodel->update_data_table('jv_particular', array("jv_id" => $jv_id, 'dr_cr' => "dr", 'particular' => @$post['credit_party_account']), array('amount' => $total_amt, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
                $update_jv_parti = $gnmodel->update_data_table('jv_particular', array("jv_id" => $jv_id, 'dr_cr' => "cr", 'particular' => $account), array('amount' => $total_amt, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
                $log_data = array(
                    'jv_id' => $jv_id,
                    'invoice_type' => "invoice",
                    'log_date' => date('Y-m-d H:i:s'),
                    'log_type' => "update",
                    'account' =>  $account,
                    'platform_id' => $post['platform_id'],
                    'month_year' => $post['month'] . '-' . $post['year'],
                    'amount' => @$total_amt,
                );
                $data = $this->add_jv_invoice_log($log_data);
                if ($result and $update_jv_parti) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            } else {
                if (empty($post['credit_party_account'])) {
                    $msg = array('st' => 'fail', 'msg' => "Please select Particular");
                    return $msg;
                }
                $pdata = array(
                    'date' => date('Y-m-d'),
                    'narration' => $narration,
                );
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $pdata['created_by'] = session('uid');
                $builder_main = $db->table('jv_main');
                $result = $builder_main->Insert($pdata);

                $id = $db->insertID();

                $data1 = array(
                    'jv_id' => $id,
                    'date' => date('Y-m-d'),
                    'dr_cr' => "dr",
                    'particular' => @$post['credit_party_account'],
                    'method' => '',
                    'amount' => @$party_total,
                    'other' => '',
                    'stat_adj' => '',
                    'invoice' => '',
                    'invoice_tb' => '',
                );
                $data1['created_at'] = date('Y-m-d H:i:s');
                $data1['created_by'] = session('uid');
                $builder_parti = $db->table('jv_particular');
                $result_parti_cr = $builder_parti->Insert($data1);

                $data = array(
                    'jv_id' => $id,
                    'date' => date('Y-m-d'),
                    'dr_cr' => "cr",
                    'particular' => @$account,
                    'method' => 'on_account',
                    'amount' => @$party_total,
                    'other' => '',
                    'stat_adj' => '',
                    'invoice' => '',
                    'invoice_tb' => '',
                );
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = session('uid');
                //echo '<pre>';Print_r($data);exit;

                $builder_parti = $db->table('jv_particular');
                $result_parti_dr = $builder_parti->Insert($data);




                // echo $db->getLastQuery();exit;


                $log_data = array(
                    'jv_id' => $id,
                    'invoice_type' => "invoice",
                    'log_date' => date('Y-m-d H:i:s'),
                    'log_type' => "insert",
                    'account' =>  @$account,
                    'platform_id' => $post['platform_id'],
                    'month_year' => $post['month'] . '-' . $post['year'],
                    'amount' => @$party_total,
                );
                $data_log = $this->add_jv_invoice_log($log_data);


                foreach ($invoice_list as $row2) {
                    $created_at = date('Y-m-d H:i:s');
                    $created_by = session('uid');
                    $result_jv = $gnmodel->update_data_table('jv_management', array('id' => $row2['id']), array('jv_pass' => '1', 'jv_id' => $id, 'created_at' => $created_at, 'created_by' => $created_by));
                }
                if ($result and $result_parti_dr and $result_parti_cr) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Added fail");
                }
            }
        }
        return $msg;
        //exit;
    }
    public function add_jv_return($post)
    {
        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        foreach ($post['return'] as $account) {
            $builder = $db->table('jv_management jm');
            $builder->select('jm.*');
            if (!empty($post['month'])) {
                $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
                $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
            }
            if (!empty($post['platform_id'])) {
                $builder->where(array('platform_id' => $post['platform_id']));
            }
            $builder->where(array("jm.type" => 'return', "jm.party_account" => $account));
            $result = $builder->get();
            $return_list = $result->getResultArray();
            //echo '<pre>';Print_r($invoice_list);
            $jv_array = array();
            $party_total = 0;
            foreach ($return_list as $row) {
                if ($row['jv_pass'] == 1) {
                    $jv_array[] = 1;
                } else {
                    $jv_array[] = 0;
                }
                $party_total += $row['amount'];
                $invoice_array[] = $row['invoice_no'];
            }
            $narration = implode(',',$invoice_array);
            $gnmodel = new GeneralModel();
            if (in_array("1", $jv_array)) {

                foreach ($return_list as $row) {
                    $jv_data = $gnmodel->get_data_table('jv_particular', array('jv_id' => $row['jv_id'], 'dr_cr' => 'cr'), 'particular');
                    $post['debit_party_account'] = $jv_data['particular'];
                }
                $jv_id = $return_list[0]['jv_id'];
                $total_amt = 0;
                $pdata['narration'] = $narration;
                $pdata['update_at'] = date('Y-m-d H:i:s');
                $pdata['update_by'] = session('uid');

                $builder_main = $db->table('jv_main');
                $builder_main->where(array("id" => $jv_id));
                $result = $builder_main->Update($pdata);
                foreach ($return_list as $row) {
                    if ($row['jv_pass'] == 1) {
                        if ($row['is_update'] == 1 && $row['is_cancle'] != 1 && $row['is_delete'] != 1) {
                            $invoice = $gnmodel->get_data_table('sales_return', array('id' => $row['invoice_no']), 'net_amount');
                            $total_amt += $invoice['net_amount'];
                            $updated_at = date('Y-m-d H:i:s');
                            $updated_by = session('uid');
                            $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $row['invoice_no'], 'type' => "return"), array('amount' => $invoice['net_amount'], 'is_update' => 0, 'updated_at' => $updated_at, 'updated_by' => $updated_by));
                        }elseif ($row['is_update'] == 1 AND ($row['is_cancle'] == 1 || $row['is_delete'] == 1)) {
                            $update_at = date('Y-m-d H:i:s');
                            $update_by = session('uid');
                            $total_amt += 0;
                            $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $row['invoice_no'], 'type' => "return"), array('is_update' => 0, 'updated_at' => $update_at, 'updated_by' => $update_by));
                
                        } 
                        elseif ($row['is_update'] == 0 AND ($row['is_cancle'] == 1 || $row['is_delete'] == 1)) {
                        
                            $total_amt += 0;
                         }
                       
                        else {
                            $total_amt += $row['amount'];
                        }
                       
                        
                    } else {
                        $created_at = date('Y-m-d H:i:s');
                        $created_by = session('uid');
                        $result_jv = $gnmodel->update_data_table('jv_management', array('id' => $row['id']), array('jv_pass' => '1', 'jv_id' => $jv_id, 'created_at' => $created_at, 'created_by' => $created_by));
                        $total_amt += $row['amount'];
                    }
                }
                $update_jv_parti1 = $gnmodel->update_data_table('jv_particular', array("jv_id" => $jv_id, 'dr_cr' => "cr", 'particular' => @$post['debit_party_account']), array('amount' => $total_amt, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
                $update_jv_parti = $gnmodel->update_data_table('jv_particular', array("jv_id" => $jv_id, 'dr_cr' => "dr", 'particular' => $account), array('amount' => $total_amt, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));

                $log_data = array(
                    'jv_id' => $jv_id,
                    'invoice_type' => "return",
                    'log_date' => date('Y-m-d H:i:s'),
                    'log_type' => "update",
                    'account' =>  $account,
                    'platform_id' => $post['platform_id'],
                    'month_year' => $post['month'] . '-' . $post['year'],
                    'amount' => @$total_amt,
                );
                $data = $this->add_jv_invoice_log($log_data);
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            } else {
                if (empty($post['debit_party_account'])) {
                    $msg = array('st' => 'fail', 'msg' => "Please select Particular");
                    return $msg;
                }
                $pdata = array(
                    'date' => date('Y-m-d'),
                    'narration' => $narration,
                );
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $pdata['created_by'] = session('uid');
                $builder_main = $db->table('jv_main');
                $result = $builder_main->Insert($pdata);
                $id = $db->insertID();

                $data1 = array(
                    'jv_id' => $id,
                    'date' => date('Y-m-d'),
                    'dr_cr' => "cr",
                    'particular' => @$post['debit_party_account'],
                    'method' => '',
                    'amount' => @$party_total,
                    'other' => '',
                    'stat_adj' => '',
                    'invoice' => '',
                    'invoice_tb' => '',
                );
                $data1['created_at'] = date('Y-m-d H:i:s');
                $data1['created_by'] = session('uid');
                $builder_parti = $db->table('jv_particular');
                $result_parti_cr = $builder_parti->Insert($data1);


                $data = array(
                    'jv_id' => $id,
                    'date' => date('Y-m-d'),
                    'dr_cr' => "dr",
                    'particular' => @$account,
                    'method' => 'on_account',
                    'amount' => @$party_total,
                    'other' => '',
                    'stat_adj' => '',
                    'invoice' => '',
                    'invoice_tb' => '',
                );
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = session('uid');
                //echo '<pre>';Print_r($data);exit;

                $builder_parti = $db->table('jv_particular');
                $result_parti_dr = $builder_parti->Insert($data);


                $log_data = array(
                    'jv_id' => $id,
                    'invoice_type' => "return",
                    'log_date' => date('Y-m-d H:i:s'),
                    'log_type' => "insert",
                    'account' =>  @$account,
                    'platform_id' => $post['platform_id'],
                    'month_year' => $post['month'] . '-' . $post['year'],
                    'amount' => @$party_total,
                );
                $data_log = $this->add_jv_invoice_log($log_data);

                foreach ($return_list as $row2) {
                    $created_at = date('Y-m-d H:i:s');
                    $created_by = session('uid');
                    $result_jv = $gnmodel->update_data_table('jv_management', array('id' => $row2['id']), array('jv_pass' => '1', 'jv_id' => $id, 'created_at' => $created_at, 'created_by' => $created_by));
                }
                if ($result and $result_parti_dr and $result_parti_cr) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Added fail");
                }
            }
        }
        return $msg;
    }
    public function add_jv_invoice_log($log_data)
    {
        //echo '<pre>';Print_r($log_data);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('jv_management_log');
        $log_data['created_at'] = date('Y-m-d H:i:s');
        $log_data['created_by'] = session('uid');
        $result = $builder->Insert($log_data);
        if ($result) {
            $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
        } else {
            $msg = array('st' => 'fail', 'msg' => "Your Details Added fail");
        }
        return $msg;
    }
    public function Jv_management_log($get)
    {
        //echo '<pre>';Print_r("vkjfhvkj");exit;

        $dt_search = array(
            "gl.id",
            "gl.jv_id",
            "(select name from account ac where gl.account = ac.id)",
            "gl.log_date",
            "gl.invoice_type",
            "gl.log_type",
            "gl.amount",
            "gl.platform_id",
            "(select name from platform p where gl.platform_id = p.id)",
        );
        $dt_col = array(
            "gl.id",
            "gl.jv_id",
            "gl.account",
            "(select name from account ac where gl.account = ac.id) as account_name",
            "gl.log_date",
            "gl.invoice_type",
            "gl.log_type",
            "gl.amount",
            "gl.platform_id",
            "(select name from platform p where gl.platform_id = p.id) as plateform_name",
        );

        $filter = $get['filter_data'];
        $tablename = "jv_management_log gl";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        // $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        foreach ($rResult['table'] as $row) {

            $btnview = '<a href="' . url('Testing/party_invoice_list/') . $row['jv_id'] . '"    class="btn btn-link pd-6"><i class="far fa-eye"></i></a> ';

            $DataRow = array();
            $DataRow[] = $row['id'];
            $DataRow[] = $row['plateform_name'];
            $DataRow[] = $row['jv_id'];
            $DataRow[] = '<a href="' . url('Bank/add_jvparticular') . '/' . $row['jv_id'] . '" >' . $row['account_name'] . '</a> ';
            $DataRow[] = $row['invoice_type'];
            $DataRow[] = user_date($row['log_date']);
            $DataRow[] = $row['log_type'];
            $DataRow[] = $row['amount'];
            $DataRow[] = $btnview;

            $encode[] = $DataRow;
        }
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    public function get_partyinvoice_data($get)
    {
        //echo '<pre>';Print_r("vkjfhvkj");exit;

        $dt_search = array(
            "gl.id",
            "gl.jv_id",
            "(select name from account ac where gl.party_account = ac.id)",
            "gl.invoice_no",
            "gl.type",
            "gl.invoice_date",
            "gl.amount",
            "gl.jv_pass",
        );
        $dt_col = array(
            "gl.id",
            "gl.jv_id",
            "(select name from account ac where gl.party_account = ac.id) as account_name",
            "gl.invoice_no",
            "gl.type",
            "gl.invoice_date",
            "gl.amount",
            "gl.jv_pass",
            "gl.is_update",
            "gl.is_delete",
            "gl.is_cancle",

        );

        $filter = $get['filter_data'];
        $tablename = "jv_management gl";
        $where = '';
        if ($filter != '' && $filter != 'undefined') {
            $where .= ' and jv_id ="' . $filter . '"';
        }
        $where .= " and gl.is_delete = 0 and gl.is_cancle = 0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            
                $DataRow[] = $row['id'];
                $DataRow[] = $row['jv_id'];
                $DataRow[] = $row['account_name'];
                $DataRow[] = $row['invoice_no'];
                $DataRow[] = $row['type'];
                $DataRow[] = user_date($row['invoice_date']);
                $DataRow[] = $row['amount'];
                $encode[] = $DataRow;
                //$DataRow[] = '';
            

           
        }
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    // public function tds_report_data($post)
    // {
    //     $start = strtotime("{$post['year']}-{$post['month']}-01");
    //     $end = strtotime('-1 second', strtotime('+1 month', $start));
    //     $start_date = date('Y-m-d', $start);
    //     $end_date = date('Y-m-d', $end);
    //     $party_id = $post['account_id'];

    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('purchase_invoice');
    //     $builder->select('*');
    //     $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
    //     $builder->where(array('account' => $party_id));
    //     $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
    //     $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
    //     $query = $builder->get();
    //     $purchase_invoice = $query->getResultArray();

    //     $account_id = array();
    //     $purchase_item_data = array();
    //     foreach ($purchase_invoice as $row) {
    //         // if (!in_array($row['account'], $account_id)) {
    //         //     $account_id[] = $row['account'];
    //         // }
    //         if (!in_array($row['round'], $account_id)) {
    //             $account_id[] = $row['round'];
    //         }
    //         $taxes = json_decode($row['taxes']);
    //         if (in_array('igst', $taxes)) {
    //             if (!in_array($row['igst_acc'], $account_id)) {
    //                 $account_id[] = $row['igst_acc'];
    //             }
    //         } else {
    //             if (!in_array($row['cgst_acc'], $account_id)) {
    //                 $account_id[] = $row['cgst_acc'];
    //             }
    //             if (!in_array($row['sgst_acc'], $account_id)) {
    //                 $account_id[] = $row['sgst_acc'];
    //             }
    //         }
    //         $builder = $db->table('purchase_item');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'parent_id' => $row['id'], 'type' => 'invoice', 'is_expence' => 1));
    //         $query = $builder->get();
    //         $purchase_item_data = $query->getResultArray();
    //         if (!empty($purchase_item_data)) {
    //             foreach ($purchase_item_data as $row1) {
    //                 if (!in_array($row1['item_id'], $account_id)) {
    //                     $account_id[] = $row1['item_id'];
    //                 }
    //             }
    //         }
    //     }
    //     $builder = $db->table('purchase_return');
    //     $builder->select('*');
    //     $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
    //     $builder->where(array('account' => $party_id));
    //     $builder->where(array('DATE(return_date)  >= ' => $start_date));
    //     $builder->where(array('DATE(return_date)  <= ' => $end_date));
    //     $query = $builder->get();
    //     $purchase_return = $query->getResultArray();

    //     foreach ($purchase_return as $row) {
    //         // if (!in_array($row['account'], $account_id)) {
    //         //     $account_id[] = $row['account'];
    //         // }
    //         if (!in_array($row['round'], $account_id)) {
    //             $account_id[] = $row['round'];
    //         }
    //         $taxes = json_decode($row['taxes']);
    //         if (in_array('igst', $taxes)) {
    //             if (!in_array($row['igst_acc'], $account_id)) {
    //                 $account_id[] = $row['igst_acc'];
    //             }
    //         } else {
    //             if (!in_array($row['cgst_acc'], $account_id)) {
    //                 $account_id[] = $row['cgst_acc'];
    //             }
    //             if (!in_array($row['sgst_acc'], $account_id)) {
    //                 $account_id[] = $row['sgst_acc'];
    //             }
    //         }
    //         $builder = $db->table('purchase_item');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'parent_id' => $row['id'], 'type' => 'return', 'is_expence' => 1));
    //         $query = $builder->get();
    //         $purchase_ret_item_data = $query->getResultArray();
    //         if (!empty($purchase_ret_item_data)) {
    //             foreach ($purchase_ret_item_data as $row1) {
    //                 if (!in_array($row1['item_id'], $account_id)) {
    //                     $account_id[] = $row1['item_id'];
    //                 }
    //             }
    //         }
    //     }

    //     $builder = $db->table('purchase_general');
    //     $builder->select('*');
    //     $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
    //     $builder->where(array('party_account' => $party_id));
    //     $builder->where(array('DATE(doc_date)  >= ' => $start_date));
    //     $builder->where(array('DATE(doc_date)  <= ' => $end_date));
    //     $query = $builder->get();
    //     $purchase_general = $query->getResultArray();

    //     foreach ($purchase_general as $row) {
    //         // if (!in_array($row['account'], $account_id)) {
    //         //     $account_id[] = $row['account'];
    //         // }
    //         if (!in_array($row['round'], $account_id)) {
    //             $account_id[] = $row['round'];
    //         }
    //         $taxes = json_decode($row['taxes']);
    //         if (in_array('igst', $taxes)) {
    //             if (!in_array($row['igst_acc'], $account_id)) {
    //                 $account_id[] = $row['igst_acc'];
    //             }
    //         } else {
    //             if (!in_array($row['cgst_acc'], $account_id)) {
    //                 $account_id[] = $row['cgst_acc'];
    //             }
    //             if (!in_array($row['sgst_acc'], $account_id)) {
    //                 $account_id[] = $row['sgst_acc'];
    //             }
    //         }
    //         $builder = $db->table('purchase_particu');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'parent_id' => $row['id']));
    //         $query = $builder->get();
    //         $purchase_gen_item_data = $query->getResultArray();
    //         if (!empty($purchase_gen_item_data)) {
    //             foreach ($purchase_gen_item_data as $row1) {
    //                 if (!in_array($row1['account'], $account_id)) {
    //                     $account_id[] = $row1['account'];
    //                 }
    //             }
    //         }
    //     }

    //     $builder = $db->table('bank_tras');
    //     $builder->select('*');
    //     $builder->where(array('is_delete' => 0));
    //     $builder->where(array('particular' => $party_id));
    //     $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
    //     $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
    //     $query = $builder->get();
    //     $bank_trans = $query->getResultArray();
    //     foreach ($bank_trans as $row) {
    //         if (!in_array($row['account'], $account_id)) {
    //             $account_id[] = $row['account'];
    //         }
    //     }

    //     $builder = $db->table('jv_particular');
    //     $builder->select('*');
    //     $builder->where(array('is_delete' => 0));
    //     $builder->where(array('particular' => $party_id));
    //     $builder->where(array('DATE(date)  >= ' => $start_date));
    //     $builder->where(array('DATE(date)  <= ' => $end_date));
    //     $query = $builder->get();
    //     $jv_data = $query->getResultArray();
    //     //echo '<pre>';Print_r($jv_data);exit;
    //     //echo '<pre>';Print_r($account_id);exit;
    //     foreach ($jv_data as $row) {
    //         $builder = $db->table('jv_particular');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'jv_id' => $row['jv_id'], 'particular!=' => $row['particular']));
    //         $query = $builder->get();
    //         $jv_data_list = $query->getResultArray();
    //         //echo '<pre>';Print_r($jv_data_list);
    //         foreach ($jv_data_list as $row1) {
    //             if (!in_array($row['particular'], $account_id)) {
    //                 $account_id[] = $row1['particular'];
    //             }
    //         }
    //     }
    //     // exit;

    //     $gmodel = new GeneralModel();
    //     $header = array();
    //     $header1 = array();
    //     $header[0] = 'Date';
    //     $header[1] = 'Particulars';
    //     $header[2] = 'Voucher Type';
    //     $header[3] = 'Narration';
    //     $header[4] = 'Gross Total';
    //     $i = 5;
    //     foreach ($account_id as $row) {
    //         $header[$i] = $row;
    //         $i++;
    //     }
    //     $i = 5;
    //     $header1[0] = 'Date';
    //     $header1[1] = 'Particulars';
    //     $header1[2] = 'Voucher Type';
    //     $header1[3] = 'Narration';
    //     $header1[4] = 'Gross Total';
    //     $account_name = array();
    //     foreach ($account_id as $row) {
    //         $account  = $gmodel->get_data_table("account", array('id' => $row), 'name');
    //         $header1[$i] = $account['name'];
    //         $account_name[] = $account['name'];
    //         $i++;
    //     }
    //     $invoice_list = array();
    //     $total = array();
    //     $total[0] = '';
    //     $total[1] = '';
    //     $total[2] = '';
    //     $total[3] = '';

    //     foreach ($purchase_invoice as $row3) {

    //         $data = array();
    //         $account_name  = $gmodel->get_data_table("account", array('id' => $row3['account']), 'name');
    //         $data[0] = user_date($row3['invoice_date']);
    //         $data[1] = '<a href="' . url('purchase/add_purchaseinvoice/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
    //         $data[2] = 'Purchase Invoice';
    //         $data[3] = $row3['supply_inv'];
    //         $data[4] = number_format($row3['net_amount'], 2, ".", "");


    //         if (isset($total[4])) {
    //             $total[4] = @$total[4]  + (float) @$row3['net_amount'];
    //         } else {
    //             $total[4] = $row3['net_amount'];
    //         }

    //         $purchase_item_data_new = array();

    //         $builder = $db->table('purchase_item');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id'], 'type' => 'invoice', 'is_expence' => 1));
    //         $query = $builder->get();
    //         $purchase_item_data_new = $query->getResultArray();

    //         foreach ($header as $key => $value) {
    //             $taxes = json_decode($row3['taxes']);
    //             if ($value == $row3['round']) {
    //                 $data[$key] = number_format($row3['round_diff'], 2, ".", "");
    //                 if (isset($total[$key])) {
    //                     $total[$key] += @$row3['round_diff'];
    //                 } else {
    //                     $total[$key] = $row3['round_diff'];
    //                 }
    //             }
    //             if (in_array('igst', $taxes)) {
    //                 if ($value == $row3['igst_acc']) {
    //                     $data[$key] =  number_format($row3['tot_igst'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] += $row3['tot_igst'];
    //                     } else {
    //                         $total[$key] = $row3['tot_igst'];
    //                     }
    //                 }
    //             }
    //             if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

    //                 if ($value == $row3['cgst_acc']) {
    //                     $data[$key] =  number_format($row3['tot_cgst'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] += $row3['tot_cgst'];
    //                     } else {
    //                         $total[$key] = $row3['tot_cgst'];
    //                     }
    //                 }
    //                 if ($value == $row3['sgst_acc']) {
    //                     $data[$key] =  number_format($row3['tot_sgst'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] += $row3['tot_sgst'];
    //                     } else {
    //                         $total[$key] = $row3['tot_sgst'];
    //                     }
    //                 }
    //             }
    //             foreach ($purchase_item_data_new as $rowpurchase_item) {
    //                 if ($value == $rowpurchase_item['item_id']) {
    //                     $data[$key] = number_format($rowpurchase_item['rate'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] += $rowpurchase_item['rate'];
    //                     } else {
    //                         $total[$key] = $rowpurchase_item['rate'];
    //                     }
    //                 }
    //             }
    //         }
    //         $invoice_list[] = $data;
    //     }
    //     foreach ($purchase_return as $row3) {
    //         $data = array();
    //         $account_name  = $gmodel->get_data_table("account", array('id' => $row3['account']), 'name');
    //         $data[0] = user_date($row3['return_date']);
    //         $data[1] = '<a href="' . url('purchase/add_purchasereturn/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
    //         $data[2] = 'Purchase Return';
    //         $data[3] = $row3['other'];
    //         $data[4] =  '-' . number_format($row3['net_amount'], 2, ".", "");
    //         if (isset($total[4])) {
    //             $total[4] -= $row3['net_amount'];
    //         } else {
    //             $total[4] = -$row3['net_amount'];
    //         }

    //         $builder = $db->table('purchase_item');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id'], 'type' => 'return', 'is_expence' => 1));
    //         $query = $builder->get();
    //         $purchase_item_data_new = $query->getResultArray();

    //         foreach ($header as $key => $value) {
    //             $taxes = json_decode($row3['taxes']);
    //             if ($value == $row3['round']) {
    //                 $data[$key] =  number_format($row3['round_diff'], 2, ".", "");
    //                 if (isset($total[$key])) {
    //                     $total[$key] -= $row3['round_diff'];
    //                 } else {
    //                     $total[$key] = -$row3['round_diff'];
    //                 }
    //             }
    //             if (in_array('igst', $taxes)) {
    //                 if ($value == $row3['igst_acc']) {
    //                     $data[$key] = '-' . number_format($row3['tot_igst'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] -= $row3['tot_igst'];
    //                     } else {
    //                         $total[$key] = -$row3['tot_igst'];
    //                     }
    //                 }
    //             }
    //             if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

    //                 if ($value == $row3['cgst_acc']) {
    //                     $data[$key] = '-' . number_format($row3['tot_cgst'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] -= $row3['tot_cgst'];
    //                     } else {
    //                         $total[$key] = -$row3['tot_cgst'];
    //                     }
    //                 }
    //                 if ($value == $row3['sgst_acc']) {
    //                     $data[$key] = '-' . number_format($row3['tot_sgst'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] -= $row3['tot_sgst'];
    //                     } else {
    //                         $total[$key] = -$row3['tot_sgst'];
    //                     }
    //                 }
    //             }
    //             foreach ($purchase_item_data_new as $rowpurchase_item) {
    //                 if ($value == $rowpurchase_item['item_id']) {
    //                     $data[$key] = '-' . number_format($rowpurchase_item['rate'], 2, ".", "");
    //                     if (isset($total[$key])) {
    //                         $total[$key] -= $rowpurchase_item['rate'];
    //                     } else {
    //                         $total[$key] = -$rowpurchase_item['rate'];
    //                     }
    //                 }
    //             }
    //         }
    //         $invoice_list[] = $data;
    //     }
    //     foreach ($purchase_general as $row3) {
    //         $data = array();
    //         $account_name  = $gmodel->get_data_table("account", array('id' => $row3['party_account']), 'name');
    //         $data[0] = user_date($row3['doc_date']);
    //         //$data[1] = $account_name['name'];

    //         if ($row3['v_type'] == 'general') {
    //             $data[1] = '<a href="' . url('purchase/add_general_pur/general/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
    //             $data[2] = 'Purchase General';
    //             $data[3] = $row3['supp_inv'];
    //             $data[4] = number_format($row3['net_amount'], 2, ".", "");
    //         } else {
    //             $data[1] = '<a href="' . url('purchase/add_general_pur/return/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
    //             $data[2] = 'Purchase General Return';
    //             $data[3] = $row3['supp_inv'];
    //             $data[4] = '-' . number_format($row3['net_amount'], 2, ".", "");
    //         }

    //         if (isset($total[4])) {
    //             if ($row3['v_type'] == 'general') {
    //                 $total[4] += $row3['net_amount'];
    //             } else {
    //                 $total[4] -= $row3['net_amount'];
    //             }
    //         } else {
    //             if ($row3['v_type'] == 'general') {
    //                 $total[4] = $row3['net_amount'];
    //             } else {
    //                 $total[4] = -$row3['net_amount'];
    //             }
    //         }

    //         $builder = $db->table('purchase_particu');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id']));
    //         $query = $builder->get();
    //         $purchase_item_data_new = $query->getResultArray();

    //         foreach ($header as $key => $value) {
    //             $taxes = json_decode($row3['taxes']);
    //             if ($value == $row3['round']) {
    //                 if ($row3['v_type'] == 'general') {
    //                     $data[$key] = number_format($row3['round_diff'], 2, ".", "");
    //                 } else {
    //                     $data[$key] = number_format($row3['round_diff'], 2, ".", "");
    //                 }

    //                 if (isset($total[$key])) {
    //                     if ($row3['v_type'] == 'general') {
    //                         $total[$key] += $row3['round_diff'];
    //                     } else {
    //                         $total[$key] -= $row3['round_diff'];
    //                     }
    //                 } else {
    //                     if ($row3['v_type'] == 'general') {
    //                         $total[$key] = $row3['round_diff'];
    //                     } else {
    //                         $total[$key] = -$row3['round_diff'];
    //                     }
    //                 }
    //             }
    //             if (in_array('igst', $taxes)) {
    //                 if ($value == $row3['igst_acc']) {
    //                     if ($row3['v_type'] == 'general') {
    //                         $data[$key] = number_format($row3['tot_igst'], 2, ".", "");
    //                     } else {
    //                         $data[$key] = '-' . number_format($row3['tot_igst'], 2, ".", "");
    //                     }

    //                     if (isset($total[$key])) {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] += $row3['tot_igst'];
    //                         } else {
    //                             $total[$key] -= $row3['tot_igst'];
    //                         }
    //                     } else {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] = $row3['tot_igst'];
    //                         } else {
    //                             $total[$key] = -$row3['tot_igst'];
    //                         }
    //                     }
    //                 }
    //             }
    //             if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

    //                 if ($value == $row3['cgst_acc']) {
    //                     if ($row3['v_type'] == 'general') {
    //                         $data[$key] = number_format($row3['tot_cgst'], 2, ".", "");
    //                     } else {
    //                         $data[$key] = '-' . number_format($row3['tot_cgst'], 2, ".", "");
    //                     }

    //                     if (isset($total[$key])) {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] += $row3['tot_cgst'];
    //                         } else {
    //                             $total[$key] -= $row3['tot_cgst'];
    //                         }
    //                     } else {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] = $row3['tot_cgst'];
    //                         } else {
    //                             $total[$key] = -$row3['tot_cgst'];
    //                         }
    //                     }
    //                 }
    //                 if ($value == $row3['sgst_acc']) {
    //                     if ($row3['v_type'] == 'general') {
    //                         $data[$key] = number_format($row3['tot_sgst'], 2, ".", "");
    //                     } else {
    //                         $data[$key] = '-' . number_format($row3['tot_sgst'], 2, ".", "");
    //                     }
    //                     if (isset($total[$key])) {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] += $row3['tot_sgst'];
    //                         } else {
    //                             $total[$key] -= $row3['tot_sgst'];
    //                         }
    //                     } else {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] = $row3['tot_sgst'];
    //                         } else {
    //                             $total[$key] = -$row3['tot_sgst'];
    //                         }
    //                     }
    //                 }
    //             }
    //             foreach ($purchase_item_data_new as $rowpurchase_item) {
    //                 if ($value == $rowpurchase_item['account']) {
    //                     if ($row3['v_type'] == 'general') {
    //                         $data[$key] = number_format($rowpurchase_item['amount'], 2, ".", "");
    //                     } else {
    //                         $data[$key] = '-' . number_format($rowpurchase_item['amount'], 2, ".", "");
    //                     }

    //                     if (isset($total[$key])) {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] += $rowpurchase_item['amount'];
    //                         } else {
    //                             $total[$key] -= $rowpurchase_item['amount'];
    //                         }
    //                     } else {
    //                         if ($row3['v_type'] == 'general') {
    //                             $total[$key] = $rowpurchase_item['amount'];
    //                         } else {
    //                             $total[$key] = -$rowpurchase_item['amount'];
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         $invoice_list[] = $data;
    //     }
    //     //echo '<pre>';Print_r($bank_trans);exit;

    //     foreach ($bank_trans as $row3) {
    //         $data = array();
    //         $account_name  = $gmodel->get_data_table("account", array('id' => $row3['particular']), 'name');
    //         $data[0] = user_date($row3['receipt_date']);
    //         if ($row3['payment_type'] == 'bank') {
    //             $data[1] = '<a href="' . url('Bank/add_banktrans/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
    //         } else {
    //             $data[1] = '<a href="' . url('Bank/add_cashtrans/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
    //         }
    //         $data[2] = $row3['mode'] . ' ' . $row3['payment_type'] . ' Transaction';
    //         $data[3] = '';
    //         //$data[3] = $row3['narration'];
    //         if ($row3['mode'] == 'Payment') {
    //             $data[4] = '-' . number_format($row3['amount'], 2, ".", "");
    //         } else {
    //             $data[4] = number_format($row3['amount'], 2, ".", "");
    //         }

    //         if (isset($total[4])) {
    //             if ($row3['mode'] == 'Payment') {
    //                 $total[4] -= $row3['amount'];
    //             } else {
    //                 $total[4] += $row3['amount'];
    //             }
    //         } else {
    //             if ($row3['mode'] == 'Payment') {
    //                 $total[4] = -$row3['amount'];
    //             } else {
    //                 $total[4] = $row3['amount'];
    //             }
    //         }
    //         foreach ($header as $key => $value) {
    //             // echo '<pre>val';Print_r($value);
    //             // echo '<pre>acc';Print_r($row3['account']);

    //             if ($value == $row3['account']) {

    //                 if ($row3['mode'] == 'Payment') {
    //                     $data[$key] =  '-' . number_format($row3['amount'], 2, ".", "");
    //                 } else {
    //                     $data[$key] = number_format($row3['amount'], 2, ".", "");
    //                 }
    //                 if (isset($total[$key])) {
    //                     if ($row3['mode'] == 'Payment') {
    //                         $total[$key] -= $row3['amount'];
    //                     } else {
    //                         $total[$key] += $row3['amount'];
    //                     }
    //                 } else {
    //                     if ($row3['mode'] == 'Payment') {
    //                         $total[$key] = -$row3['amount'];
    //                     } else {
    //                         $total[$key] = $row3['amount'];
    //                     }
    //                 }
    //             }
    //         }
    //         // exit;
    //         $invoice_list[] = $data;
    //     }
    //     //echo '<pre>';Print_r($header);exit;
    //     //echo '<pre>';Print_r($invoice_list);exit;
    //     //echo '<pre>';Print_r($header);exit;

    //     foreach ($jv_data as $row3) {
    //         $data = array();
    //         $account_name  = $gmodel->get_data_table("account", array('id' => $row3['particular']), 'name');
    //         $data[0] = user_date($row3['date']);
    //         $data[1] = '<a href="' . url('Bank/add_jvparticular/') . $row3['jv_id'] . '">' . $account_name['name'] . '</a>';
    //         $data[2] = 'JV Voucher';
    //         // $data[2] = 'JV Voucher('.$row3['dr_cr'].')';
    //         $data[3] = '';
    //         //$data[3] = $row3['jv_id'];
    //         if ($row3['dr_cr'] == 'dr') {
    //             $data[4] = '-' . number_format($row3['amount'], 2, ".", "");
    //         } else {
    //             $data[4] = number_format($row3['amount'], 2, ".", "");
    //         }
    //         if (isset($total[4])) {
    //             if ($row3['dr_cr'] == 'dr') {
    //                 $total[4] -= $row3['amount'];
    //             } else {
    //                 $total[4] += $row3['amount'];
    //             }
    //         } else {
    //             if ($row3['dr_cr'] == 'dr') {
    //                 $total[4] = -$row3['amount'];
    //             } else {
    //                 $total[4] = $row3['amount'];
    //             }
    //         }

    //         $builder = $db->table('jv_particular');
    //         $builder->select('*');
    //         $builder->where(array('is_delete' => 0, 'jv_id' => $row3['jv_id'], 'particular!=' => $row3['particular']));
    //         $query = $builder->get();
    //         $jv_particular_data_new = $query->getResultArray();
    //         foreach ($header as $key => $value) {
    //             foreach ($jv_particular_data_new as $rowjv_item) {
    //                 if ($value == $rowjv_item['particular']) {
    //                     if ($row3['dr_cr'] == 'dr') {
    //                         $data[$key] = '-' . number_format($rowjv_item['amount'], 2, ".", "");
    //                     } else {
    //                         $data[$key] = number_format($rowjv_item['amount'], 2, ".", "");
    //                     }
    //                     if (isset($total[$key])) {
    //                         if ($row3['dr_cr'] == 'dr') {
    //                             $total[$key] -= $rowjv_item['amount'];
    //                         } else {
    //                             $total[$key] += $rowjv_item['amount'];
    //                         }
    //                     } else {
    //                         if ($row3['dr_cr'] == 'dr') {
    //                             $total[$key] = -$rowjv_item['amount'];
    //                         } else {
    //                             $total[$key] = $rowjv_item['amount'];
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         $invoice_list[] = $data;
    //     }
    //     $result['header'] = $header;
    //     $result['header_account_name'] = $header1;
    //     $result['invoice_list'] = $invoice_list;
    //     $result['total'] = $total;
    //     //exit;
    //     //echo '<pre>';Print_r($invoice_list);exit;

    //     return $result;
    // }
    public function tds_report_data($post)
    {
        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $party_id = $post['account_id'];

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
        $builder->where(array('account' => $party_id));
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_invoice = $query->getResultArray();

        $account_id = array();
        $purchase_item_data = array();
        foreach ($purchase_invoice as $row  ) {
            // if (!in_array($row['account'], $account_id)) {
            //     $account_id[] = $row['account'];
            // }
            if (!in_array($row['round'], $account_id)) {
                $account_id[] = $row['round'];
            }
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                if (!in_array($row['igst_acc'], $account_id)) {
                    $account_id[] = $row['igst_acc'];
                }
            } else {
                if (!in_array($row['cgst_acc'], $account_id)) {
                    $account_id[] = $row['cgst_acc'];
                }
                if (!in_array($row['sgst_acc'], $account_id)) {
                    $account_id[] = $row['sgst_acc'];
                }
            }
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row['id'], 'type' => 'invoice', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_item_data = $query->getResultArray();
            if (!empty($purchase_item_data)) {
                foreach ($purchase_item_data as $row1) {
                    if (!in_array($row1['item_id'], $account_id)) {
                        $account_id[] = $row1['item_id'];
                    }
                }
            }
        }
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
        $builder->where(array('account' => $party_id));
        $builder->where(array('DATE(return_date)  >= ' => $start_date));
        $builder->where(array('DATE(return_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_return = $query->getResultArray();

        foreach ($purchase_return as $row) {
            // if (!in_array($row['account'], $account_id)) {
            //     $account_id[] = $row['account'];
            // }
            if (!in_array($row['round'], $account_id)) {
                $account_id[] = $row['round'];
            }
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                if (!in_array($row['igst_acc'], $account_id)) {
                    $account_id[] = $row['igst_acc'];
                }
            } else {
                if (!in_array($row['cgst_acc'], $account_id)) {
                    $account_id[] = $row['cgst_acc'];
                }
                if (!in_array($row['sgst_acc'], $account_id)) {
                    $account_id[] = $row['sgst_acc'];
                }
            }
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row['id'], 'type' => 'return', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_ret_item_data = $query->getResultArray();
            if (!empty($purchase_ret_item_data)) {
                foreach ($purchase_ret_item_data as $row1) {
                    if (!in_array($row1['item_id'], $account_id)) {
                        $account_id[] = $row1['item_id'];
                    }
                }
            }
        }

        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
        $builder->where(array('party_account' => $party_id));
        $builder->where(array('DATE(doc_date)  >= ' => $start_date));
        $builder->where(array('DATE(doc_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_general = $query->getResultArray();

        foreach ($purchase_general as $row) {
            // if (!in_array($row['account'], $account_id)) {
            //     $account_id[] = $row['account'];
            // }
            if (!in_array($row['round'], $account_id)) {
                $account_id[] = $row['round'];
            }
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                if (!in_array($row['igst_acc'], $account_id)) {
                    $account_id[] = $row['igst_acc'];
                }
            } else {
                if (!in_array($row['cgst_acc'], $account_id)) {
                    $account_id[] = $row['cgst_acc'];
                }
                if (!in_array($row['sgst_acc'], $account_id)) {
                    $account_id[] = $row['sgst_acc'];
                }
            }
            $builder = $db->table('purchase_particu');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row['id']));
            $query = $builder->get();
            $purchase_gen_item_data = $query->getResultArray();
            if (!empty($purchase_gen_item_data)) {
                foreach ($purchase_gen_item_data as $row1) {
                    if (!in_array($row1['account'], $account_id)) {
                        $account_id[] = $row1['account'];
                    }
                }
            }
        }

        $builder = $db->table('bank_tras');
        $builder->select('*');
        $builder->where(array('is_delete' => 0));
        $builder->where(array('particular' => $party_id));
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $bank_trans = $query->getResultArray();
        foreach ($bank_trans as $row) {
            if (!in_array($row['account'], $account_id)) {
                $account_id[] = $row['account'];
            }
        }

        $builder = $db->table('jv_particular');
        $builder->select('*');
        $builder->where(array('is_delete' => 0));
        $builder->where(array('particular' => $party_id));
        $builder->where(array('DATE(date)  >= ' => $start_date));
        $builder->where(array('DATE(date)  <= ' => $end_date));
        $query = $builder->get();
        $jv_data = $query->getResultArray();
        foreach ($jv_data as $row) {
            $builder = $db->table('jv_particular');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'jv_id' => $row['jv_id'], 'particular!=' => $row['particular']));
            $query = $builder->get();
            $jv_data_list = $query->getResultArray();
            foreach ($jv_data_list as $row1) {
                if (!in_array($row1['particular'], $account_id)) {
                    $account_id[] = $row1['particular'];
                }
            }
        }

        $gmodel = new GeneralModel();
        $header = array();
        $header1 = array();
        $header[0] = 'Date';
        $header[1] = 'Particulars';
        $header[2] = 'Voucher Type';
        $header[3] = 'Narration';
        $header[4] = 'Gross Total';
        $i = 5;
        foreach ($account_id as $row) {
            $header[$i] = $row;
            $i++;
        }
        $i = 5;
        $header1[0] = 'Date';
        $header1[1] = 'Particulars';
        $header1[2] = 'Voucher Type';
        $header1[3] = 'Narration';
        $header1[4] = 'Gross Total';
        $account_name = array();
        foreach ($account_id as $row) {
            $account  = $gmodel->get_data_table("account", array('id' => $row), 'name');
            $header1[$i] = $account['name'];
            $account_name[] = $account['name'];
            $i++;
        }
        $invoice_list = array();
        $total = array();
        $total[0] = '';
        $total[1] = '';
        $total[2] = '';
        $total[3] = '';

        foreach ($purchase_invoice as $row3) {

            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['account']), 'name');
            $data[0] = user_date($row3['invoice_date']);
            $data[1] = '<a href="' . url('purchase/add_purchaseinvoice/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
            $data[2] = 'Purchase Invoice';
            $data[3] = $row3['supply_inv'];
            $data[4] = number_format($row3['net_amount'], 2, ".", "");


            if (isset($total[4])) {
                $total[4] = @$total[4]  + (float) @$row3['net_amount'];
            } else {
                $total[4] = $row3['net_amount'];
            }

            $purchase_item_data_new = array();

            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id'], 'type' => 'invoice', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_item_data_new = $query->getResultArray();

            foreach ($header as $key => $value) {
                $taxes = json_decode($row3['taxes']);
                if ($value == $row3['round']) {
                    $data[$key] = number_format($row3['round_diff'], 2, ".", "");
                    if (isset($total[$key])) {
                        $total[$key] += @$row3['round_diff'];
                    } else {
                        $total[$key] = $row3['round_diff'];
                    }
                }
                if (in_array('igst', $taxes)) {
                    if ($value == $row3['igst_acc']) {
                        $data[$key] =  number_format($row3['tot_igst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $row3['tot_igst'];
                        } else {
                            $total[$key] = $row3['tot_igst'];
                        }
                    }
                }
                if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

                    if ($value == $row3['cgst_acc']) {
                        $data[$key] =  number_format($row3['tot_cgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $row3['tot_cgst'];
                        } else {
                            $total[$key] = $row3['tot_cgst'];
                        }
                    }
                    if ($value == $row3['sgst_acc']) {
                        $data[$key] =  number_format($row3['tot_sgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $row3['tot_sgst'];
                        } else {
                            $total[$key] = $row3['tot_sgst'];
                        }
                    }
                }
                foreach ($purchase_item_data_new as $rowpurchase_item) {
                    if ($value == $rowpurchase_item['item_id']) {
                        $data[$key] = number_format($rowpurchase_item['rate'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $rowpurchase_item['rate'];
                        } else {
                            $total[$key] = $rowpurchase_item['rate'];
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        foreach ($purchase_return as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['account']), 'name');
            $data[0] = user_date($row3['return_date']);
            $data[1] = '<a href="' . url('purchase/add_purchasereturn/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
            $data[2] = 'Purchase Return';
            $data[3] = $row3['other'];
            $data[4] =  '-' . number_format($row3['net_amount'], 2, ".", "");
            if (isset($total[4])) {
                $total[4] -= $row3['net_amount'];
            } else {
                $total[4] = -$row3['net_amount'];
            }

            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id'], 'type' => 'return', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_item_data_new = $query->getResultArray();

            foreach ($header as $key => $value) {
                $taxes = json_decode($row3['taxes']);
                if ($value == $row3['round']) {
                    $data[$key] =  number_format($row3['round_diff'], 2, ".", "");
                    if (isset($total[$key])) {
                        $total[$key] -= $row3['round_diff'];
                    } else {
                        $total[$key] = -$row3['round_diff'];
                    }
                }
                if (in_array('igst', $taxes)) {
                    if ($value == $row3['igst_acc']) {
                        $data[$key] = '-' . number_format($row3['tot_igst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $row3['tot_igst'];
                        } else {
                            $total[$key] = -$row3['tot_igst'];
                        }
                    }
                }
                if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

                    if ($value == $row3['cgst_acc']) {
                        $data[$key] = '-' . number_format($row3['tot_cgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $row3['tot_cgst'];
                        } else {
                            $total[$key] = -$row3['tot_cgst'];
                        }
                    }
                    if ($value == $row3['sgst_acc']) {
                        $data[$key] = '-' . number_format($row3['tot_sgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $row3['tot_sgst'];
                        } else {
                            $total[$key] = -$row3['tot_sgst'];
                        }
                    }
                }
                foreach ($purchase_item_data_new as $rowpurchase_item) {
                    if ($value == $rowpurchase_item['item_id']) {
                        $data[$key] = '-' . number_format($rowpurchase_item['rate'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $rowpurchase_item['rate'];
                        } else {
                            $total[$key] = -$rowpurchase_item['rate'];
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        foreach ($purchase_general as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['party_account']), 'name');
            $data[0] = user_date($row3['doc_date']);
            //$data[1] = $account_name['name'];

            if ($row3['v_type'] == 'general') {
                $data[1] = '<a href="' . url('purchase/add_general_pur/general/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
                $data[2] = 'Purchase General';
                $data[3] = $row3['supp_inv'];
                $data[4] = number_format($row3['net_amount'], 2, ".", "");
            } else {
                $data[1] = '<a href="' . url('purchase/add_general_pur/return/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
                $data[2] = 'Purchase General Return';
                $data[3] = $row3['supp_inv'];
                $data[4] = '-' . number_format($row3['net_amount'], 2, ".", "");
            }

            if (isset($total[4])) {
                if ($row3['v_type'] == 'general') {
                    $total[4] += $row3['net_amount'];
                } else {
                    $total[4] -= $row3['net_amount'];
                }
            } else {
                if ($row3['v_type'] == 'general') {
                    $total[4] = $row3['net_amount'];
                } else {
                    $total[4] = -$row3['net_amount'];
                }
            }

            $builder = $db->table('purchase_particu');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id']));
            $query = $builder->get();
            $purchase_item_data_new = $query->getResultArray();

            foreach ($header as $key => $value) {
                $taxes = json_decode($row3['taxes']);
                if ($value == $row3['round']) {
                    if ($row3['v_type'] == 'general') {
                        $data[$key] = number_format($row3['round_diff'], 2, ".", "");
                    } else {
                        $data[$key] = number_format($row3['round_diff'], 2, ".", "");
                    }

                    if (isset($total[$key])) {
                        if ($row3['v_type'] == 'general') {
                            $total[$key] += $row3['round_diff'];
                        } else {
                            $total[$key] -= $row3['round_diff'];
                        }
                    } else {
                        if ($row3['v_type'] == 'general') {
                            $total[$key] = $row3['round_diff'];
                        } else {
                            $total[$key] = -$row3['round_diff'];
                        }
                    }
                }
                if (in_array('igst', $taxes)) {
                    if ($value == $row3['igst_acc']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($row3['tot_igst'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($row3['tot_igst'], 2, ".", "");
                        }

                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $row3['tot_igst'];
                            } else {
                                $total[$key] -= $row3['tot_igst'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $row3['tot_igst'];
                            } else {
                                $total[$key] = -$row3['tot_igst'];
                            }
                        }
                    }
                }
                if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

                    if ($value == $row3['cgst_acc']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($row3['tot_cgst'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($row3['tot_cgst'], 2, ".", "");
                        }

                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $row3['tot_cgst'];
                            } else {
                                $total[$key] -= $row3['tot_cgst'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $row3['tot_cgst'];
                            } else {
                                $total[$key] = -$row3['tot_cgst'];
                            }
                        }
                    }
                    if ($value == $row3['sgst_acc']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($row3['tot_sgst'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($row3['tot_sgst'], 2, ".", "");
                        }
                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $row3['tot_sgst'];
                            } else {
                                $total[$key] -= $row3['tot_sgst'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $row3['tot_sgst'];
                            } else {
                                $total[$key] = -$row3['tot_sgst'];
                            }
                        }
                    }
                }
                foreach ($purchase_item_data_new as $rowpurchase_item) {
                    if ($value == $rowpurchase_item['account']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($rowpurchase_item['amount'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($rowpurchase_item['amount'], 2, ".", "");
                        }

                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $rowpurchase_item['amount'];
                            } else {
                                $total[$key] -= $rowpurchase_item['amount'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $rowpurchase_item['amount'];
                            } else {
                                $total[$key] = -$rowpurchase_item['amount'];
                            }
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        //echo '<pre>';Print_r($bank_trans);exit;

        foreach ($bank_trans as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['particular']), 'name');
            $data[0] = user_date($row3['receipt_date']);
            if ($row3['payment_type'] == 'bank') {
                $data[1] = '<a href="' . url('Bank/add_banktrans/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
            } else {
                $data[1] = '<a href="' . url('Bank/add_cashtrans/') . $row3['id'] . '">' . $account_name['name'] . '</a>';
            }
            $data[2] = $row3['mode'] . ' ' . $row3['payment_type'] . ' Transaction';
            $data[3] = '';
            //$data[3] = $row3['narration'];
            if ($row3['mode'] == 'Payment') {
                $data[4] = '-' . number_format($row3['amount'], 2, ".", "");
            } else {
                $data[4] = number_format($row3['amount'], 2, ".", "");
            }

            if (isset($total[4])) {
                if ($row3['mode'] == 'Payment') {
                    $total[4] -= $row3['amount'];
                } else {
                    $total[4] += $row3['amount'];
                }
            } else {
                if ($row3['mode'] == 'Payment') {
                    $total[4] = -$row3['amount'];
                } else {
                    $total[4] = $row3['amount'];
                }
            }
            foreach ($header as $key => $value) {
                // echo '<pre>val';Print_r($value);
                // echo '<pre>acc';Print_r($row3['account']);

                if ($value == $row3['account']) {

                    if ($row3['mode'] == 'Payment') {
                        $data[$key] =  '-' . number_format($row3['amount'], 2, ".", "");
                    } else {
                        $data[$key] = number_format($row3['amount'], 2, ".", "");
                    }
                    if (isset($total[$key])) {
                        if ($row3['mode'] == 'Payment') {
                            $total[$key] -= $row3['amount'];
                        } else {
                            $total[$key] += $row3['amount'];
                        }
                    } else {
                        if ($row3['mode'] == 'Payment') {
                            $total[$key] = -$row3['amount'];
                        } else {
                            $total[$key] = $row3['amount'];
                        }
                    }
                }
            }
            // exit;
            $invoice_list[] = $data;
        }
        //echo '<pre>';Print_r($header);exit;
        //echo '<pre>';Print_r($invoice_list);exit;
        //echo '<pre>';Print_r($header);exit;

        foreach ($jv_data as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['particular']), 'name');
            $data[0] = user_date($row3['date']);
            $data[1] = '<a href="' . url('Bank/add_jvparticular/') . $row3['jv_id'] . '">' . $account_name['name'] . '</a>';
            $data[2] = 'JV Voucher';
            // $data[2] = 'JV Voucher('.$row3['dr_cr'].')';
            $data[3] = '';
            //$data[3] = $row3['jv_id'];
            if ($row3['dr_cr'] == 'dr') {
                $data[4] = '-' . number_format($row3['amount'], 2, ".", "");
            } else {
                $data[4] = number_format($row3['amount'], 2, ".", "");
            }
            if (isset($total[4])) {
                if ($row3['dr_cr'] == 'dr') {
                    $total[4] -= $row3['amount'];
                } else {
                    $total[4] += $row3['amount'];
                }
            } else {
                if ($row3['dr_cr'] == 'dr') {
                    $total[4] = -$row3['amount'];
                } else {
                    $total[4] = $row3['amount'];
                }
            }

            $builder = $db->table('jv_particular');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'jv_id' => $row3['jv_id'], 'particular!=' => $row3['particular']));
            $query = $builder->get();
            $jv_particular_data_new = $query->getResultArray();
            

            foreach ($header as $key => $value) {
                foreach ($jv_particular_data_new as $rowjv_item) {

                    if ($value == $rowjv_item['particular']) {
                        if ($rowjv_item['dr_cr'] == 'dr') {
                            $data[$key] = '-' . number_format($rowjv_item['amount'], 2, ".", "");
                        } else {
                            $data[$key] = number_format($rowjv_item['amount'], 2, ".", "");
                        }
                        if (isset($total[$key])) {
                            if ($rowjv_item['dr_cr'] == 'dr') {
                                $total[$key] -= $rowjv_item['amount'];
                            } else {
                                $total[$key] += $rowjv_item['amount'];
                            }
                        } else {
                            if ($rowjv_item['dr_cr'] == 'dr') {
                                $total[$key] = -$rowjv_item['amount'];
                            } else {
                                $total[$key] = $rowjv_item['amount'];
                            }
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        $result['header'] = $header;
        $result['header_account_name'] = $header1;
        $result['invoice_list'] = $invoice_list;
        $result['total'] = $total;
        //exit;
        //echo '<pre>';Print_r($invoice_list);exit;

        return $result;
    }
    public function tds_report_data_excel($post)
    {
        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $party_id = $post['account_id'];

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
        $builder->where(array('account' => $party_id));
        $builder->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_invoice = $query->getResultArray();

        $account_id = array();
        $purchase_item_data = array();
        foreach ($purchase_invoice as $row) {
            // if (!in_array($row['account'], $account_id)) {
            //     $account_id[] = $row['account'];
            // }
            if (!in_array($row['round'], $account_id)) {
                $account_id[] = $row['round'];
            }
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                if (!in_array($row['igst_acc'], $account_id)) {
                    $account_id[] = $row['igst_acc'];
                }
            } else {
                if (!in_array($row['cgst_acc'], $account_id)) {
                    $account_id[] = $row['cgst_acc'];
                }
                if (!in_array($row['sgst_acc'], $account_id)) {
                    $account_id[] = $row['sgst_acc'];
                }
            }
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row['id'], 'type' => 'invoice', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_item_data = $query->getResultArray();
            if (!empty($purchase_item_data)) {
                foreach ($purchase_item_data as $row1) {
                    if (!in_array($row1['item_id'], $account_id)) {
                        $account_id[] = $row1['item_id'];
                    }
                }
            }
        }
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
        $builder->where(array('account' => $party_id));
        $builder->where(array('DATE(return_date)  >= ' => $start_date));
        $builder->where(array('DATE(return_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_return = $query->getResultArray();

        foreach ($purchase_return as $row) {
            // if (!in_array($row['account'], $account_id)) {
            //     $account_id[] = $row['account'];
            // }
            if (!in_array($row['round'], $account_id)) {
                $account_id[] = $row['round'];
            }
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                if (!in_array($row['igst_acc'], $account_id)) {
                    $account_id[] = $row['igst_acc'];
                }
            } else {
                if (!in_array($row['cgst_acc'], $account_id)) {
                    $account_id[] = $row['cgst_acc'];
                }
                if (!in_array($row['sgst_acc'], $account_id)) {
                    $account_id[] = $row['sgst_acc'];
                }
            }
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row['id'], 'type' => 'return', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_ret_item_data = $query->getResultArray();
            if (!empty($purchase_ret_item_data)) {
                foreach ($purchase_ret_item_data as $row1) {
                    if (!in_array($row1['item_id'], $account_id)) {
                        $account_id[] = $row1['item_id'];
                    }
                }
            }
        }

        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array('is_delete' => 0, 'is_cancle' => 0));
        $builder->where(array('party_account' => $party_id));
        $builder->where(array('DATE(doc_date)  >= ' => $start_date));
        $builder->where(array('DATE(doc_date)  <= ' => $end_date));
        $query = $builder->get();
        $purchase_general = $query->getResultArray();

        foreach ($purchase_general as $row) {
            // if (!in_array($row['account'], $account_id)) {
            //     $account_id[] = $row['account'];
            // }
            if (!in_array($row['round'], $account_id)) {
                $account_id[] = $row['round'];
            }
            $taxes = json_decode($row['taxes']);
            if (in_array('igst', $taxes)) {
                if (!in_array($row['igst_acc'], $account_id)) {
                    $account_id[] = $row['igst_acc'];
                }
            } else {
                if (!in_array($row['cgst_acc'], $account_id)) {
                    $account_id[] = $row['cgst_acc'];
                }
                if (!in_array($row['sgst_acc'], $account_id)) {
                    $account_id[] = $row['sgst_acc'];
                }
            }
            $builder = $db->table('purchase_particu');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row['id']));
            $query = $builder->get();
            $purchase_gen_item_data = $query->getResultArray();
            if (!empty($purchase_gen_item_data)) {
                foreach ($purchase_gen_item_data as $row1) {
                    if (!in_array($row1['account'], $account_id)) {
                        $account_id[] = $row1['account'];
                    }
                }
            }
        }

        $builder = $db->table('bank_tras');
        $builder->select('*');
        $builder->where(array('is_delete' => 0));
        $builder->where(array('particular' => $party_id));
        $builder->where(array('DATE(receipt_date)  >= ' => $start_date));
        $builder->where(array('DATE(receipt_date)  <= ' => $end_date));
        $query = $builder->get();
        $bank_trans = $query->getResultArray();
        foreach ($bank_trans as $row) {
            if (!in_array($row['account'], $account_id)) {
                $account_id[] = $row['account'];
            }
        }

        $builder = $db->table('jv_particular');
        $builder->select('*');
        $builder->where(array('is_delete' => 0));
        $builder->where(array('particular' => $party_id));
        $builder->where(array('DATE(date)  >= ' => $start_date));
        $builder->where(array('DATE(date)  <= ' => $end_date));
        $query = $builder->get();
        $jv_data = $query->getResultArray();
        //echo '<pre>';Print_r($jv_data);exit;
        //echo '<pre>';Print_r($account_id);exit;
        foreach ($jv_data as $row) {
            $builder = $db->table('jv_particular');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'jv_id' => $row['jv_id'], 'particular!=' => $row['particular']));
            $query = $builder->get();
            $jv_data_list = $query->getResultArray();
            //echo '<pre>';Print_r($jv_data_list);
            foreach ($jv_data_list as $row1) {
                if (!in_array($row['particular'], $account_id)) {
                    $account_id[] = $row1['particular'];
                }
            }
        }
        // exit;

        $gmodel = new GeneralModel();
        $header = array();
        $header1 = array();
        $header[0] = 'Date';
        $header[1] = 'Particulars';
        $header[2] = 'Voucher Type';
        $header[3] = 'Narration';
        $header[4] = 'Gross Total';
        $i = 5;
        foreach ($account_id as $row) {
            $header[$i] = $row;
            $i++;
        }
        $i = 5;
        $header1[0] = 'Date';
        $header1[1] = 'Particulars';
        $header1[2] = 'Voucher Type';
        $header1[3] = 'Narration';
        $header1[4] = 'Gross Total';
        $account_name = array();
        foreach ($account_id as $row) {
            $account  = $gmodel->get_data_table("account", array('id' => $row), 'name');
            $header1[$i] = $account['name'];
            $account_name[] = $account['name'];
            $i++;
        }
        $invoice_list = array();
        $total = array();
        $total[0] = '';
        $total[1] = '';
        $total[2] = '';
        $total[3] = '';

        foreach ($purchase_invoice as $row3) {

            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['account']), 'name');
            $data[0] = user_date($row3['invoice_date']);
            $data[1] = $account_name['name'];
            $data[2] = 'Purchase Invoice';
            $data[3] = $row3['supply_inv'];
            $data[4] = number_format($row3['net_amount'], 2, ".", "");


            if (isset($total[4])) {
                $total[4] = @$total[4]  + (float) @$row3['net_amount'];
            } else {
                $total[4] = $row3['net_amount'];
            }

            $purchase_item_data_new = array();

            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id'], 'type' => 'invoice', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_item_data_new = $query->getResultArray();

            foreach ($header as $key => $value) {
                $taxes = json_decode($row3['taxes']);
                if ($value == $row3['round']) {
                    $data[$key] = number_format($row3['round_diff'], 2, ".", "");
                    if (isset($total[$key])) {
                        $total[$key] += @$row3['round_diff'];
                    } else {
                        $total[$key] = $row3['round_diff'];
                    }
                }
                if (in_array('igst', $taxes)) {
                    if ($value == $row3['igst_acc']) {
                        $data[$key] =  number_format($row3['tot_igst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $row3['tot_igst'];
                        } else {
                            $total[$key] = $row3['tot_igst'];
                        }
                    }
                }
                if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

                    if ($value == $row3['cgst_acc']) {
                        $data[$key] =  number_format($row3['tot_cgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $row3['tot_cgst'];
                        } else {
                            $total[$key] = $row3['tot_cgst'];
                        }
                    }
                    if ($value == $row3['sgst_acc']) {
                        $data[$key] =  number_format($row3['tot_sgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $row3['tot_sgst'];
                        } else {
                            $total[$key] = $row3['tot_sgst'];
                        }
                    }
                }
                foreach ($purchase_item_data_new as $rowpurchase_item) {
                    if ($value == $rowpurchase_item['item_id']) {
                        $data[$key] = number_format($rowpurchase_item['rate'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] += $rowpurchase_item['rate'];
                        } else {
                            $total[$key] = $rowpurchase_item['rate'];
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        foreach ($purchase_return as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['account']), 'name');
            $data[0] = user_date($row3['return_date']);
            $data[1] = $account_name['name'];
            $data[2] = 'Purchase Return';
            $data[3] = $row3['other'];
            $data[4] =  '-' . number_format($row3['net_amount'], 2, ".", "");
            if (isset($total[4])) {
                $total[4] -= $row3['net_amount'];
            } else {
                $total[4] = -$row3['net_amount'];
            }

            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id'], 'type' => 'return', 'is_expence' => 1));
            $query = $builder->get();
            $purchase_item_data_new = $query->getResultArray();

            foreach ($header as $key => $value) {
                $taxes = json_decode($row3['taxes']);
                if ($value == $row3['round']) {
                    $data[$key] =  number_format($row3['round_diff'], 2, ".", "");
                    if (isset($total[$key])) {
                        $total[$key] -= $row3['round_diff'];
                    } else {
                        $total[$key] = -$row3['round_diff'];
                    }
                }
                if (in_array('igst', $taxes)) {
                    if ($value == $row3['igst_acc']) {
                        $data[$key] = '-' . number_format($row3['tot_igst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $row3['tot_igst'];
                        } else {
                            $total[$key] = -$row3['tot_igst'];
                        }
                    }
                }
                if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

                    if ($value == $row3['cgst_acc']) {
                        $data[$key] = '-' . number_format($row3['tot_cgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $row3['tot_cgst'];
                        } else {
                            $total[$key] = -$row3['tot_cgst'];
                        }
                    }
                    if ($value == $row3['sgst_acc']) {
                        $data[$key] = '-' . number_format($row3['tot_sgst'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $row3['tot_sgst'];
                        } else {
                            $total[$key] = -$row3['tot_sgst'];
                        }
                    }
                }
                foreach ($purchase_item_data_new as $rowpurchase_item) {
                    if ($value == $rowpurchase_item['item_id']) {
                        $data[$key] = '-' . number_format($rowpurchase_item['rate'], 2, ".", "");
                        if (isset($total[$key])) {
                            $total[$key] -= $rowpurchase_item['rate'];
                        } else {
                            $total[$key] = -$rowpurchase_item['rate'];
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        foreach ($purchase_general as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['party_account']), 'name');
            $data[0] = user_date($row3['doc_date']);
            $data[1] = $account_name['name'];

            if ($row3['v_type'] == 'general') {
                $data[2] = 'Purchase General';
                $data[3] = $row3['supp_inv'];
                $data[4] = number_format($row3['net_amount'], 2, ".", "");
            } else {
                $data[2] = 'Purchase General Return';
                $data[3] = $row3['supp_inv'];
                $data[4] = '-' . number_format($row3['net_amount'], 2, ".", "");
            }

            if (isset($total[4])) {
                if ($row3['v_type'] == 'general') {
                    $total[4] += $row3['net_amount'];
                } else {
                    $total[4] -= $row3['net_amount'];
                }
            } else {
                if ($row3['v_type'] == 'general') {
                    $total[4] = $row3['net_amount'];
                } else {
                    $total[4] = -$row3['net_amount'];
                }
            }

            $builder = $db->table('purchase_particu');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'parent_id' => $row3['id']));
            $query = $builder->get();
            $purchase_item_data_new = $query->getResultArray();

            foreach ($header as $key => $value) {
                $taxes = json_decode($row3['taxes']);
                if ($value == $row3['round']) {
                    if ($row3['v_type'] == 'general') {
                        $data[$key] = number_format($row3['round_diff'], 2, ".", "");
                    } else {
                        $data[$key] = number_format($row3['round_diff'], 2, ".", "");
                    }

                    if (isset($total[$key])) {
                        if ($row3['v_type'] == 'general') {
                            $total[$key] += $row3['round_diff'];
                        } else {
                            $total[$key] -= $row3['round_diff'];
                        }
                    } else {
                        if ($row3['v_type'] == 'general') {
                            $total[$key] = $row3['round_diff'];
                        } else {
                            $total[$key] = -$row3['round_diff'];
                        }
                    }
                }
                if (in_array('igst', $taxes)) {
                    if ($value == $row3['igst_acc']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($row3['tot_igst'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($row3['tot_igst'], 2, ".", "");
                        }

                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $row3['tot_igst'];
                            } else {
                                $total[$key] -= $row3['tot_igst'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $row3['tot_igst'];
                            } else {
                                $total[$key] = -$row3['tot_igst'];
                            }
                        }
                    }
                }
                if (in_array('cgst', $taxes) && in_array('sgst', $taxes)) {

                    if ($value == $row3['cgst_acc']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($row3['tot_cgst'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($row3['tot_cgst'], 2, ".", "");
                        }

                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $row3['tot_cgst'];
                            } else {
                                $total[$key] -= $row3['tot_cgst'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $row3['tot_cgst'];
                            } else {
                                $total[$key] = -$row3['tot_cgst'];
                            }
                        }
                    }
                    if ($value == $row3['sgst_acc']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($row3['tot_sgst'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($row3['tot_sgst'], 2, ".", "");
                        }
                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $row3['tot_sgst'];
                            } else {
                                $total[$key] -= $row3['tot_sgst'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $row3['tot_sgst'];
                            } else {
                                $total[$key] = -$row3['tot_sgst'];
                            }
                        }
                    }
                }
                foreach ($purchase_item_data_new as $rowpurchase_item) {
                    if ($value == $rowpurchase_item['account']) {
                        if ($row3['v_type'] == 'general') {
                            $data[$key] = number_format($rowpurchase_item['amount'], 2, ".", "");
                        } else {
                            $data[$key] = '-' . number_format($rowpurchase_item['amount'], 2, ".", "");
                        }

                        if (isset($total[$key])) {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] += $rowpurchase_item['amount'];
                            } else {
                                $total[$key] -= $rowpurchase_item['amount'];
                            }
                        } else {
                            if ($row3['v_type'] == 'general') {
                                $total[$key] = $rowpurchase_item['amount'];
                            } else {
                                $total[$key] = -$rowpurchase_item['amount'];
                            }
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        //echo '<pre>';Print_r($bank_trans);exit;

        foreach ($bank_trans as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['particular']), 'name');
            $data[0] = user_date($row3['receipt_date']);
            $data[1] = $account_name['name'];
            $data[2] = $row3['mode'] . ' ' . $row3['payment_type'] . ' Transaction';
            $data[3] = '';
            //$data[3] = $row3['narration'];
            if ($row3['mode'] == 'Payment') {
                $data[4] = '-' . number_format($row3['amount'], 2, ".", "");
            } else {
                $data[4] = number_format($row3['amount'], 2, ".", "");
            }

            if (isset($total[4])) {
                if ($row3['mode'] == 'Payment') {
                    $total[4] -= $row3['amount'];
                } else {
                    $total[4] += $row3['amount'];
                }
            } else {
                if ($row3['mode'] == 'Payment') {
                    $total[4] = -$row3['amount'];
                } else {
                    $total[4] = $row3['amount'];
                }
            }
            foreach ($header as $key => $value) {
                // echo '<pre>val';Print_r($value);
                // echo '<pre>acc';Print_r($row3['account']);

                if ($value == $row3['account']) {

                    if ($row3['mode'] == 'Payment') {
                        $data[$key] =  '-' . number_format($row3['amount'], 2, ".", "");
                    } else {
                        $data[$key] = number_format($row3['amount'], 2, ".", "");
                    }
                    if (isset($total[$key])) {
                        if ($row3['mode'] == 'Payment') {
                            $total[$key] -= $row3['amount'];
                        } else {
                            $total[$key] += $row3['amount'];
                        }
                    } else {
                        if ($row3['mode'] == 'Payment') {
                            $total[$key] = -$row3['amount'];
                        } else {
                            $total[$key] = $row3['amount'];
                        }
                    }
                }
            }
            // exit;
            $invoice_list[] = $data;
        }
        //echo '<pre>';Print_r($header);exit;
        //echo '<pre>';Print_r($invoice_list);exit;
        //echo '<pre>';Print_r($header);exit;

        foreach ($jv_data as $row3) {
            $data = array();
            $account_name  = $gmodel->get_data_table("account", array('id' => $row3['particular']), 'name');
            $data[0] = user_date($row3['date']);
            $data[1] = $account_name['name'];
            $data[2] = 'JV Voucher';
            // $data[2] = 'JV Voucher('.$row3['dr_cr'].')';
            $data[3] = '';
            //$data[3] = $row3['jv_id'];
            if ($row3['dr_cr'] == 'dr') {
                $data[4] = '-' . number_format($row3['amount'], 2, ".", "");
            } else {
                $data[4] = number_format($row3['amount'], 2, ".", "");
            }
            if (isset($total[4])) {
                if ($row3['dr_cr'] == 'dr') {
                    $total[4] -= $row3['amount'];
                } else {
                    $total[4] += $row3['amount'];
                }
            } else {
                if ($row3['dr_cr'] == 'dr') {
                    $total[4] = -$row3['amount'];
                } else {
                    $total[4] = $row3['amount'];
                }
            }

            $builder = $db->table('jv_particular');
            $builder->select('*');
            $builder->where(array('is_delete' => 0, 'jv_id' => $row3['jv_id'], 'particular!=' => $row3['particular']));
            $query = $builder->get();
            $jv_particular_data_new = $query->getResultArray();
            foreach ($header as $key => $value) {
                foreach ($jv_particular_data_new as $rowjv_item) {
                    if ($value == $rowjv_item['particular']) {
                        if ($row3['dr_cr'] == 'dr') {
                            $data[$key] = '-' . number_format($rowjv_item['amount'], 2, ".", "");
                        } else {
                            $data[$key] = number_format($rowjv_item['amount'], 2, ".", "");
                        }
                        if (isset($total[$key])) {
                            if ($row3['dr_cr'] == 'dr') {
                                $total[$key] -= $rowjv_item['amount'];
                            } else {
                                $total[$key] += $rowjv_item['amount'];
                            }
                        } else {
                            if ($row3['dr_cr'] == 'dr') {
                                $total[$key] = -$rowjv_item['amount'];
                            } else {
                                $total[$key] = $rowjv_item['amount'];
                            }
                        }
                    }
                }
            }
            $invoice_list[] = $data;
        }
        $result['header'] = $header;
        $result['header_account_name'] = $header1;
        $result['invoice_list'] = $invoice_list;
        $result['total'] = $total;
        //exit;
        //echo '<pre>';Print_r($invoice_list);exit;

        return $result;
    }
    public function tds_report_excel_data($post)
    {
        $data = $this->tds_report_data_excel($post);
        //echo '<pre>';Print_r($data);exit;
        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        $party_id = $post['account_id'];

        $gmodel = new GeneralModel();
        $acc = $gmodel->get_data_table('account', array('id' => @$party_id), 'id,name,address');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A2:C2');
        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getFont()->setSize(20);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', session('address'));
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A3:H3');
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A5:C5');
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(20);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6', @$acc['address']);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A6:H6');
        $spreadsheet->getActiveSheet()->getStyle('A6:H6')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A8', user_date($start_date));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B8', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C8', user_date($end_date));
        $spreadsheet->getActiveSheet()->getStyle('A8:C8')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A8:C8')->getFont()->setSize(12);


        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'Tds Report');
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6', user_date($start_date));
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('B6', 'to');
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('C6', user_date($end_date));
        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', @$acc['name']);
        // $spreadsheet->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
        // $spreadsheet->getActiveSheet()->getStyle('A7')->getFont()->setSize(12);
        $count = count($data['header_account_name']);
        // echo '<pre>';Print_r($count);exit;

        $j = 0;
        for ($i = 'A'; $i < 'Z'; $i++) {
            $spreadsheet->getActiveSheet()->getStyle($i . '10')->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($i . '10')->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($i . '10')->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $spreadsheet->setActiveSheetIndex(0)->setCellValue($i . '10', @$data['header_account_name'][$j]);
            $j++;
        }
        $spreadsheet->getActiveSheet()->getRowDimension('10')->setRowHeight(30);


        $k = 11;
        $closing = 0;

        foreach ($data['invoice_list'] as $row) {
            $l = 0;

            for ($i = 'A'; $i < 'Z'; $i++) {

                $spreadsheet->setActiveSheetIndex(0)->setCellValue($i . $k, @$row[$l]);
                $spreadsheet->getActiveSheet()->getStyle($i . $k)->getBorders()
                    ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle($i . $k)->getBorders()
                    ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle($i . $k)->getBorders()
                    ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $l++;
            }
            $spreadsheet->getActiveSheet()->getStyle('B' . $k)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('E' . $k)->getFont()->setBold(true);
            $k++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $k, 'Total');
        $spreadsheet->getActiveSheet()->getStyle('A' . $k)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $k . ':E' . $k)->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A' . $k . ':E' . $k)->getBorders()
            ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A' . $k . ':E' . $k)->getBorders()
            ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


        $l = 4;

        for ($i = 'E'; $i < 'Z'; $i++) {

            $spreadsheet->setActiveSheetIndex(0)->setCellValue($i . $k, @$data['total'][$l]);
            $spreadsheet->getActiveSheet()->getStyle($i . $k)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle($i . $k)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($i . $k)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($i . $k)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


            $l++;
        }


        $spreadsheet->getActiveSheet()->setTitle('Tds Report');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function get_plateform_data()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('platform');
        $builder->select("*");
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }

        return $result;
    }
    public function update_jv_management()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('platform_voucher');
        $builder->select("*");
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        $gmodel = new GeneralModel();
        foreach($getdata as $row){
            
                if($row['type'] == 'invoice')
                {
                    $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $row['voucher']), 'account,gst,net_amount');
                }
                else
                {
                    $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $row['voucher']), 'account,gst,net_amount');
                }
                if(!empty($invoice_data))
                {
               
                    $builder_jv = $db->table('jv_management');
                    $data = array(
                        'jv_id' => '',
                        'platform_id' => $row['platform_id'],
                        'invoice_no' => $row['voucher'],
                        'invoice_date' => $row['invoice_date'],
                        'party_account' => $invoice_data['account'],
                        'gst' => $invoice_data['gst'],
                        'type' => $row['type'],
                        'amount' => $invoice_data['net_amount'],
                        'created_at'=> $row['created_at'],
                        'created_by'=> $row['created_by'],

                    );
                    $result = $builder_jv->Insert($data);
                }
        }
       // exit;

        return $result;
    }
    public function get_acinvoice_list($post)
    {
       // echo '<pre>';Print_r($post);exit;
        
        $start = strtotime("{$post['year']}-{$post['month']}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));
        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        $builder = $db->table('jv_management jm');
        $builder->select('jm.*,ac.name as ac_name,si.acc_state,s.name as state_name');
        $builder->join('platform_voucher pv', 'pv.voucher = jm.invoice_no');
        $builder->join('account ac', 'ac.id = jm.party_account');
        if($post['type'] == 'invoice')
        {
            $builder->join('sales_invoice si', 'si.id = jm.invoice_no');
        }
        else
        {
            $builder->join('sales_return si', 'si.id = jm.invoice_no');
        }
        $builder->join('states s', 's.id = si.acc_state');
        
        if (!empty($post['month'])) {
            $builder->where(array('DATE(jm.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(jm.invoice_date)  <= ' => $end_date));
        }
        if (!empty($post['plateform_id'])) {
            $builder->where(array('pv.platform_id' => $post['plateform_id']));
        }
        $builder->where(array("jm.type" => $post['type'], "jm.party_account" => $post['ac_id']));
        $result = $builder->get();
        $invoice_list = $result->getResultArray();

        return $invoice_list;
        //echo '<pre>';Print_r($invoice_list);exit;
        
    }
}
