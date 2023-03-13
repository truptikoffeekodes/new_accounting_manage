<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\GeneralModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TestingModel extends Model
{
    public function get_hsn_core_data($type,$start_date='',$end_date=''){

        if($start_date == '') {
            if (date('m') <= '03') {
                $year = date('Y') - 1;
                $start_date = $year . '-04-01';
            } else {
                $year = date('Y');
                $start_date = $year . '-04-01';
            }
        }
        
        if($end_date == '') {
    
            if (date('m') <= '03') {
                $year = date('Y');
            } else {
                $year = date('Y') + 1;
            }
            $end_date = $year . '-03-31';
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        if($type == "sales_invoice")
        {
            //print_r("jkdfhke");exit;
            $vch_type = "'sale_invoice' as vch_type" ;
           
            $builder =$db->table('sales_item si');
            //$builder->select('si.*,i.hsn,s.taxes,s.disc_type,s.discount,'.$vch_type);
            $builder->select('si.parent_id,s.invoice_date as date,s.taxes,si.taxability,si.type,si.item_id,si.uom,si.qty,si.rate,si.igst,si.cgst,si.sgst,si.igst_amt,si.cgst_amt,si.sgst_amt,si.item_disc, i.name,i.hsn, s.taxes,s.gst,s.custom_inv_no as cinv_no,ac.name as account_name,s.disc_type,s.discount,si.sub_total as taxable,'.$vch_type);
            $builder->join('item i','i.id = si.item_id');
            $builder->join('sales_invoice s','s.id = si.parent_id');
            $builder->join('account ac','ac.id = s.account');
            $builder->where(array('si.type' => 'invoice'));
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('s.is_delete' => 0));
            $builder->where(array('s.is_cancle' => 0));
            $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $invoice_item = $query->getResultArray();
            $data['sales'] = $invoice_item;
            $title = "Sales Invoice Report";
        }
        elseif($type == "sales_return")
        {    
            $vch_type_ret = "'sale_return' as vch_type" ;
            $builder =$db->table('sales_item si');
            $builder->select('si.parent_id,s.return_date as date,s.taxes,si.taxability,si.type,si.item_id,si.uom,si.qty,si.rate,si.igst,si.cgst,si.sgst,si.igst_amt,si.cgst_amt,si.sgst_amt,si.item_disc, i.name,i.hsn, s.taxes,s.gst,s.supp_inv as cinv_no,ac.name as account_name,s.disc_type, s.discount,si.sub_total as taxable,'.$vch_type_ret);
            $builder->join('item i','i.id = si.item_id');
            $builder->join('sales_return s','s.id = si.parent_id');
            $builder->join('account ac','ac.id = s.account');
            $builder->where(array('si.type' => 'return'));
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('s.is_delete' => 0));
            $builder->where(array('s.is_cancle' => 0));
            $builder->where(array('DATE(s.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(s.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $return_item = $query->getResultArray();
            $data['sales'] = $return_item;
            $title = "Sales Return Report";
        }
        elseif($type == "sales_general")
        {    
            $vch_type_ret = "'sale_general' as vch_type" ;
            $builder =$db->table('sales_ACparticu si');
            $builder->select('si.parent_id,s.invoice_date as date,s.taxes,si.taxability,si.type,si.account as item_id,si.amount as rate,si.igst,si.cgst,si.sgst,si.igst_amt,si.cgst_amt,si.sgst_amt, i.name,s.taxes,s.gst,s.supp_inv as cinv_no,ac.name as account_name,s.disc_type, s.discount,si.sub_total as taxable,'.$vch_type_ret);
            $builder->join('account i','i.id = si.account');
            $builder->join('sales_ACinvoice s','s.id = si.parent_id');
            $builder->join('account ac','ac.id = s.party_account');
            $builder->where(array('si.type' => 'general'));
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('s.is_delete' => 0));
            $builder->where(array('s.is_cancle' => 0));
            $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $general_item = $query->getResultArray();
            $data['sales'] = $general_item;
            $title = "Sales General Report";
        }
        else
        {
            $vch_type_ret = "'sale_generalReturn' as vch_type" ;
            $builder =$db->table('sales_ACparticu si');
            $builder->select('si.parent_id,s.invoice_date as date,s.taxes,si.taxability,si.type,si.account as item_id,si.amount as rate,si.igst,si.cgst,si.sgst,si.igst_amt,si.cgst_amt,si.sgst_amt, i.name,s.taxes,s.gst,s.supp_inv as cinv_no,ac.name as account_name,s.disc_type, s.discount,si.sub_total as taxable,'.$vch_type_ret);
            $builder->join('account i','i.id = si.account');
            $builder->join('sales_ACinvoice s','s.id = si.parent_id');
            $builder->join('account ac','ac.id = s.party_account');
            $builder->where(array('si.type' => 'return'));
            $builder->where(array('si.is_delete' => 0));
            $builder->where(array('s.is_delete' => 0));
            $builder->where(array('s.is_cancle' => 0));
            $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $generalreturn_item = $query->getResultArray();
            $data['sales'] = $generalreturn_item;
            $title = "Sales General Return Report";
        }

        $data['type'] = $title;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        //$data = array_merge($invoice_item,$return_item);
        //echo '<pre>';Print_r($data);exit;
        
        return $data;
    }    
    
    public function test_xls_export_data($post)
    {
        //echo '<pre>';Print_r($post);exit;
        

        $data = $this->get_hsn_core_data($post['type'],db_date($post['from']), db_date($post['to']));


         //echo "<pre>";print_r($data);exit;
       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A2:C2');
        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:C2')->getFont()->setSize(20);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', session('address'));
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A3:F3');
        $spreadsheet->getActiveSheet()->getStyle('A3:F3')->getBorders()
        ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $date_from=date_create($data['start_date']);
        $new_date_from = date_format($date_from,"d-M-y");
        $date_to=date_create($data['end_date']);
        $new_date_to = date_format($date_to,"d-M-y");

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', @$data['type']);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A5:C5');
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(20);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6', $new_date_from);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B6', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C6', $new_date_to);
        $spreadsheet->getActiveSheet()->getStyle('A6:F6')->getBorders()
        ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


        $spreadsheet->getActiveSheet()->getStyle('A7:Y7')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');

        // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Summary');
       

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', 'SI No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B7', 'Date');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C7', 'Voucher Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D7', 'Custome Inv No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E7', 'Account Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F7', 'Taxability');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G7', 'Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H7', 'Item ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I7', 'Item Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J7', 'Uom');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K7', 'QTY');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L7', 'Rate');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M7', 'Igst');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('N7', 'Cgst');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('O7', 'Sgst');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('P7', 'Igst Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q7', 'cgst Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('R7', 'sgst Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('S7', 'Item Discount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('T7', 'HSN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('U7', 'Taxes');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('V7', 'Gst No');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('W7', 'Discount Type'); 
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('X7', 'Discount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('Y7', 'Taxable Amount');
      

        $i = 8;
        // echo '<pre>';print_r($final_b2b);exit;
        foreach ($data['sales'] as $row) {
            $taxes = json_decode($row['taxes']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['parent_id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['date']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['vch_type'] );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, @$row['cinv_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, @$row['account_name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$row['taxability']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, @$row['type']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, @$row['item_id']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, @$row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, @$row['uom']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, @$row['qty']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, @$row['rate']);
           
            if(in_array('igst', $taxes))
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, @$row['igst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, '0.00');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, '0.00');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, @$row['igst_amt']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q' . $i, '0.00');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('R' . $i, '0.00');
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, '0.00');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, @$row['cgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, @$row['sgst']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, '0.00');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q' . $i, @$row['cgst_amt']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('R' . $i, @$row['sgst_amt']);
            }
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('S' . $i, @$row['item_disc']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('T' . $i, @$row['hsn']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('U' . $i, @$row['taxes']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('V' . $i, @$row['gst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('W' . $i, @$row['disc_type']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('X' . $i, @$row['discount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Y' . $i, @$row['taxable']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('core_hsn_data');
        //$objPHPExcel->getActiveSheet()->setTitle("Title");

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }
    //update trupti 13-12-2022
    public function plateform_list($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('platform');
        $builder->select('*');
        $builder->where(array('is_delete' => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
       
        foreach($result_array as $row)
        {
            $builder = $db->table('platform_voucher pv');
            $builder->select('SUM(si.net_amount) as total');
            $builder->join('sales_invoice si', 'si.id = pv.voucher');
            $builder->where(array('pv.is_delete' => 0,'pv.is_cancle' => 0,'pv.platform_id'=>$row['id']));
            $builder->where(array('si.is_delete' => 0,'si.is_cancle' => 0));
            $builder->where(array('pv.type' => 'invoice'));
            if(!empty($post['from']))
            {
                $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($post['from'])));
                $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($post['to'])));
            }
            $result = $builder->get();
            $data = $result->getRowArray();

            $builder = $db->table('platform_voucher pv');
            $builder->select('SUM(si.net_amount) as total');
            $builder->join('sales_return si', 'si.id = pv.voucher');
            $builder->where(array('pv.is_delete' => 0,'pv.is_cancle' => 0,'pv.platform_id'=>$row['id']));
            $builder->where(array('si.is_delete' => 0,'si.is_cancle' => 0));
            $builder->where(array('pv.type' => 'return'));
            if(!empty($post['from']))
            {
                $builder->where(array('DATE(si.return_date)  >= ' => db_date($post['from'])));
                $builder->where(array('DATE(si.return_date)  <= ' => db_date($post['to'])));
            }
            $result = $builder->get();
            $return_data = $result->getRowArray();
            if(!empty($post['type']))
            {
                if($post['type'] == "sales")
                {
                    $row['total'] = (float)$data['total'];
                }
                else
                {
                    $row['total'] =  (float)$return_data['total'];
                }
            }
            else
            {
                $row['total'] = (float)$data['total'] - (float)$return_data['total'];
            }
           
            $plateform_data[] = $row;
        }
        return $plateform_data;
    }
    public function Plateformwise_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $sales_type = "'sales' as type";

        $builder = $db->table('platform_voucher pv');
        $builder->select($sales_type.',pv.voucher,si.id,si.invoice_date as date,si.invoice_no,si.custom_inv_no,si.account,ac.name as account_name,si.net_amount,si.taxable,si.tot_igst,si.gst');
        $builder->join('sales_invoice si', 'si.id = pv.voucher');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('pv.is_delete' => 0,'pv.is_cancle' => 0,'pv.platform_id'=>@$post['id']));
        $builder->where(array('si.is_delete' => 0,'si.is_cancle' => 0,'si.gst!='=>''));
        $builder->where(array('pv.type' => 'invoice'));
        if(!empty($post['from']))
        {
                $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($post['from'])));
                $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($post['to'])));
        }
        $result = $builder->get();
        $result_b2b = $result->getResultArray();

        $sales_return = "'sales_return' as type";


        $builder = $db->table('platform_voucher pv');
        $builder->select($sales_type.',pv.voucher,si.id,si.return_date as date,si.return_no as invoice_no,si.supp_inv as custom_inv_no,si.account,ac.name as account_name,si.net_amount,si.taxable,si.tot_igst,si.gst');
        $builder->join('sales_return si', 'si.id = pv.voucher');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('pv.is_delete' => 0,'pv.is_cancle' => 0,'pv.platform_id'=>@$post['id']));
        $builder->where(array('si.is_delete' => 0,'si.is_cancle' => 0,'si.gst!='=>''));
        $builder->where(array('pv.type' => 'return'));
        if(!empty($post['from']))
        {
            $builder->where(array('DATE(si.return_date)  >= ' => db_date($post['from'])));
            $builder->where(array('DATE(si.return_date)  <= ' => db_date($post['to'])));
        }
        $result = $builder->get();
        $return_b2b = $result->getResultArray();
        if(!empty($post['type']))
        {
            if($post['type'] == "sales")
            {
                $data['invoice_list']['b2b'] = $result_b2b;
            }
            else
            {
                $data['invoice_list']['b2b'] = $return_b2b;
            }
        }
        else
        {
            $data['invoice_list']['b2b'] = array_merge($result_b2b,$return_b2b);
        }
       
        $builder = $db->table('platform_voucher pv');
        $builder->select($sales_type.',pv.voucher,si.id,si.invoice_date as date,si.invoice_no,si.custom_inv_no,si.account,ac.name as account_name,si.net_amount,si.taxable,si.tot_igst,si.gst');
        $builder->join('sales_invoice si', 'si.id = pv.voucher');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('pv.is_delete' => 0,'pv.is_cancle' => 0,'pv.platform_id'=>$post['id']));
        $builder->where(array('si.is_delete' => 0,'si.is_cancle' => 0,'si.gst='=>''));
        $builder->where(array('pv.type' => 'invoice'));
        if(!empty($post['from']))
        {
                $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($post['from'])));
                $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($post['to'])));
        }
        $result = $builder->get();
        $result_b2c = $result->getResultArray();

        $builder = $db->table('platform_voucher pv');
        $builder->select($sales_return.',pv.voucher,si.id,si.return_date as date,si.return_no as invoice_no,si.supp_inv as custom_inv_no,si.account,ac.name as account_name,si.net_amount,si.taxable,si.tot_igst,si.gst');
        $builder->join('sales_return si', 'si.id = pv.voucher');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('pv.is_delete' => 0,'pv.is_cancle' => 0,'pv.platform_id'=>$post['id']));
        $builder->where(array('si.is_delete' => 0,'si.is_cancle' => 0,'si.gst='=>''));
        $builder->where(array('pv.type' => 'return'));
        if(!empty($post['from']))
        {
            $builder->where(array('DATE(si.return_date)  >= ' => db_date($post['from'])));
            $builder->where(array('DATE(si.return_date)  <= ' => db_date($post['to'])));
        }
        $result = $builder->get();
        $return_b2c = $result->getResultArray();

        if(!empty($post['type']))
        {
            if($post['type'] == "sales")
            {
                $data['invoice_list']['b2c'] = $result_b2c;
            }
            else
            {
                $data['invoice_list']['b2c'] = $return_b2c;
            }
        }
        else
        {
            $data['invoice_list']['b2c'] = array_merge($result_b2c,$return_b2c);
        }
        
        $data['id'] = $post['id'];
        $data['from'] = $post['from'];
        $data['to'] = $post['to'];
        $data['type'] = $post['type'];
        return $data;
    }
    public function update_inv_type()
    {
        $gnmodel = new GeneralModel();
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('platform_voucher');
        $builder->select('*');
        $builder->where(array("type=" => ''));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        //echo '<pre>';Print_r($result_array);exit;
        foreach($result_array as $row)
        {
            $builder = $db->table('sales_invoice');
            $builder->select('*');
            $builder->where(array('id' => $row['voucher'],'custom_inv_no'=> $row['custom_inv_no'],'is_delete'=>0,''));
            $result = $builder->get();
            $result_array1 = $result->getRowArray();
            if(!empty($result_array1))
            {
                    $result1 = $gnmodel->update_data_table('platform_voucher', array('id'=>$row['id']), array("type" => 'invoice'));
            }
            else
            {

            }
        
            // else
            // {
            //     $builder = $db->table('sales_return');
            //     $builder->select('*');
            //     $builder->where(array('id' => $row['voucher'],'supp_inv'=> $row['custom_inv_no']));
            //     $result = $builder->get();
            //     $result_array2 = $result->getRowArray();
            //     if(!empty($result_array2))
            //     {
                    
            //     }
            // }

        }
        return true;
        
    }
    // tds report data
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
                if (!in_array($row1['particular'], $account_id)) {
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
                        if ($rowjv_item['dr_cr'] == 'dr') {
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
            $spreadsheet->getActiveSheet()->getStyle($i.'10')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($i.'10')->getBorders()
            ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle($i.'10')->getBorders()
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
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $l++;

            }
            $spreadsheet->getActiveSheet()->getStyle('B'.$k)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('E'.$k)->getFont()->setBold(true);
            $k++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $k, 'Total');
        $spreadsheet->getActiveSheet()->getStyle('A'.$k)->getFont()->setBold(true); 
        $spreadsheet->getActiveSheet()->getStyle('A'.$k.':E'.$k)->getBorders()
        ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$k.':E'.$k)->getBorders()
        ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A'.$k.':E'.$k)->getBorders()
        ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          
      
            $l = 4;
           
            for ($i = 'E'; $i < 'Z'; $i++) {

                $spreadsheet->setActiveSheetIndex(0)->setCellValue($i . $k, @$data['total'][$l]);
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getBorders()
                ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getBorders()
                ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle($i.$k)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
                
                $l++;

            }
           
        
        $spreadsheet->getActiveSheet()->setTitle('Tds Report');

        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
?>