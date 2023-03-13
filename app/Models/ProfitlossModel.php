<?php

namespace App\Models;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\TradingModel;

class ProfitlossModel extends Model
{
    public function __construct() {
        parent::__construct();
        $this->tmodel = new TradingModel();
       
    }
    public function get_closing_stock($start_date ='', $end_date= ''){
        
        if($start_date == ''){
            if(date('m') < '03'){
                $year = date('Y')-1;
                $start_date = $year.'-04-01';
            }else{
                $year = date('Y');
                $start_date = $year.'-04-01';
            }
        }

        if($end_date == '' ){
            if(date('m') < '03'){
                $year = date('Y');
            }else{
                $year = date('Y')+1;
            }
            $end_date =$year.'-03-31'; 
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('item'); 
        $builder->select('*');
        $builder->where(array('is_delete'=>0));
        $query = $builder->get();
        $result = $query->getResultArray();
        $diff_total = array();

        foreach($result as $row){
            
            $sale = SaleItemSTock($row['id'],$start_date,$end_date);
            $purchase = PurchaseItemSTock($row['id'],$start_date,$end_date);
            // $sale_pur = sale_purchase_itm_total($start_date,$end_date);
            if($purchase['itm']['total_qty'] != 0 ){
                $diff_total[] =   (@$purchase['itm']['total_rate'] / @$purchase['itm']['total_qty']) * (@$purchase['itm']['total_qty'] - @$sale['itm']['total_qty']);    
            }else{
                $diff_total[] =   1 * (@$purchase['itm']['total_qty'] - @$sale['itm']['total_qty']);    
            }
        }
        $final_total  =  array_sum($diff_total);
        if(!isset($final_total) || empty($final_total)){
            $final_total = 0;
        }
        return $final_total;
        
    }

    public function get_closing_bal($start_date ='', $end_date= ''){
        
        if($start_date == ''){
            if(date('m') < '03'){
                $year = date('Y')-1;
                $start_date = $year.'-04-01';
            }else{
                $year = date('Y');
                $start_date = $year.'-04-01';
            }
        }

        if($end_date == '' ){
            if(date('m') < '03'){
                $year = date('Y');
            }else{
                $year = date('Y')+1;
            }
            $end_date =$year.'-03-31'; 
        }
       
        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
        $builder = $db->table('oc_stock'); 
        $builder->select('*');
        $builder->where('date <=',$end_date);
        $builder->where('date <',date('Y-m-d'));
        $builder->where(array('is_delete'=>0));
        $builder->orderBy('date','desc');
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRowArray();
        @$closing =  @$result['closing'];
        
        return @$closing;
        // echo '<pre>';print_r($result);exit;
        
    }
    public function profit_loss_xls_export_data($post)
    {
        $gmodel = new GeneralModel;
        $gl_id = $gmodel->get_data_table('gl_group', array('name' => 'Trading Expenses', 'is_delete' => 0), 'id,name');
        $gl_inc_id = $gmodel->get_data_table('gl_group', array('name' => 'Trading Income', 'is_delete' => 0), 'id,name');
        $pl_exp_id = $gmodel->get_data_table('gl_group', array('name' => 'P & L Expenses', 'is_delete' => 0), 'id,name');
        $pl_inc_id = $gmodel->get_data_table('gl_group', array('name' => 'P & L Incomes', 'is_delete' => 0), 'id,name');
        $init_total = 0;

        $trading = sale_purchase_itm_total($post['from'], $post['to']);

        $exp[$gl_id['id']] = trading_expense_data($gl_id['id'], $post['from'], $post['to']);
        $exp[$gl_id['id']]['name'] = $gl_id['name'];
        $exp[$gl_id['id']]['sub_categories'] = get_expense_sub_grp_data($gl_id['id'], $post['from'], $post['to']);

        $inc[$gl_inc_id['id']] = trading_income_data($gl_inc_id['id'], $post['from'], $post['to']);
        $inc[$gl_inc_id['id']]['name'] = $gl_inc_id['name'];
        $inc[$gl_inc_id['id']]['sub_categories'] = get_income_sub_grp_data($gl_inc_id['id'], $post['from'], $post['to']);

        $exp_pl[$pl_exp_id['id']] = pl_expense_data($pl_exp_id['id'], $post['from'], $post['to']);
        $exp_pl[$pl_exp_id['id']]['name'] = $pl_exp_id['name'];
        $exp_pl[$pl_exp_id['id']]['sub_categories']  = get_PL_expense_sub_grp_data($pl_exp_id['id'], $post['from'], $post['to']);

        $inc_pl[$pl_inc_id['id']] = pl_income_data($pl_inc_id['id'], $post['from'], $post['to']);
        $inc_pl[$pl_inc_id['id']]['name'] = $pl_inc_id['name'];
        $inc_pl[$pl_inc_id['id']]['sub_categories'] = get_PL_income_sub_grp_data($pl_inc_id['id'], $post['from'], $post['to']);

        $pl  = pl_tot_data($post['from'], $post['to']);
        $Opening_bal = Opening_bal('Opening Stock');
        $manualy_closing_bal = $this->tmodel->get_manualy_stock($post['from'],$post['to']);
        $closing_data = $this->tmodel->get_closing_detail($post['from'],$post['to']);


        //$trading = $sale_pur;
        //$data['pl'] = $pl ;

        $exp_total = subGrp_total($exp, $init_total);
        $inc_total = subGrp_total($inc, $init_total);

        $exp_pl_total = subGrp_total($exp_pl, $init_total);
        $inc_pl_total = subGrp_total($inc_pl, $init_total);

        $pl['exp'] = @$exp_pl;
        $pl['inc'] = @$inc_pl;

        $trading['exp_total'] = @$exp_total;
        $trading['inc_total'] = @$inc_total;

        $pl['exp_total'] = @$exp_pl_total;
        $pl['inc_total'] = @$inc_pl_total;

        
        $closing_bal = @$closing_data['closing_bal']; 
        $closing_stock = @$closing_data['closing_stock'];
        $manualy_closing_bal = @$manualy_closing_bal;

        if(session('is_stock') == 1 ){
            $closing_bal = @$manualy_closing_bal;
        }else{
            $closing_bal  = @$closing_bal;
        }

        $all_purchase = $trading['pur_total_rate'];
        $all_purchase_return = $trading['Purret_total_rate'];

        $all_sale = $trading['sale_total_rate'];
        $all_sale_return = $trading['Saleret_total_rate'];

        $income_total = (float)$all_sale - (float)$all_sale_return + $closing_bal + $trading['inc_total'];
        $expens_total = @$Opening_bal + (float)$all_purchase  - (float)$all_purchase_return + $trading['exp_total'];


        if (($expens_total -  $income_total) < 0) {
            $gross_profit = ($expens_total -  $income_total) * -1;
        } else {
            $gross_loss = $expens_total -  $income_total;
        }

        if ((@$gross_loss + $pl['exp_total'])   >  ($pl['inc_total'] + @$gross_profit)) {
            $net_loss = (@$gross_loss + $pl['exp_total']) - ($pl['inc_total'] + @$gross_profit);
        } else {
            $net_profit = ($pl['inc_total'] + @$gross_profit)  - (@$gross_loss + $pl['exp_total']);
        }

        $pl_expens_total = $pl['exp_total'] + @$net_profit + @$gross_loss;
        $pl_income_total = $pl['inc_total'] + @$gross_profit + @$net_loss;

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

        $date_from = date_create($post['from']);
        $new_date_from = date_format($date_from, "d-M-y");
        $date_to = date_create($post['to']);
        $new_date_to = date_format($date_to, "d-M-y");

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'Profit-Loss Report');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A5:C5');
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(20);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A6', $new_date_from);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B6', 'to');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C6', $new_date_to);
        $spreadsheet->getActiveSheet()->getStyle('A6:F6')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A7', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B7', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C7', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D7', 'Particulars');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E7', session('name'));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F7', '');
        $spreadsheet->getActiveSheet()->getStyle('A7:F7')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A7:F7')->getFont()->setSize(15);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A8', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B8', ' at ' . $new_date_from);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C8', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D8', '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E8', ' at ' . $new_date_to);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F8', '');
        $spreadsheet->getActiveSheet()->getStyle('A8:F8')->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->getActiveSheet()->getStyle('C4:C8')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        if (!empty($gross_loss)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A9', 'Gross Loss');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B9', '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C9', number_format($gross_loss, 2));
            $spreadsheet->getActiveSheet()->getStyle('A9:C9')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A9:C9')->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('C9')->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('C9')->getNumberFormat()->getFormatCode();
        }
        $total = 0;
        $i = 10;
        if (!empty($pl['exp'])) {
            foreach ($pl['exp'] as $key => $value) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format(@$pl['exp_total'], 2));
                $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                    ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                    ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                    ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setSize(12);
                $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
                $i++;
                if (!empty($value['account'])) {
                    foreach (@$value['account'] as $ac_key => $ac_value) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$ac_key);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format(@$ac_value['total'], 2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                        $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                            ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                        $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                        $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                        $i++;
                    }
                }
                if (!empty($value['sub_categories'])) {
                    foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                        $total = 0;
                        $arr[$sub_key] = $sub_value;
                        $total = subGrp_total($arr, 0);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$sub_value['name']);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, number_format($total, 2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, '');
                        $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                            ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
                        $spreadsheet->getActiveSheet()->getStyle('B' . $i)->getNumberFormat()->getFormatCode();
                        $i++;
                        unset($arr);
                    }
                }
            }
        }
        if (isset($net_profit) && !empty($net_profit)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, 'Net Profit');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format($net_profit, 2));
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
        }
        if (!empty($gross_profit)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D9', 'Gross Profit');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E9', '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F9', number_format($gross_profit, 2));
            $spreadsheet->getActiveSheet()->getStyle('F9')->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('D9:F9')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('D9:F9')->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('F9')->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('F9')->getNumberFormat()->getFormatCode();
        }
        $j = 10;
        $total_profit = 0;
        if (!empty($pl['inc'])) {
            foreach ($pl['inc'] as $key => $value) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$value['name']);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format(@$pl['inc_total'], 2));
                $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                    ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                    ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setSize(12);
                $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->getFormatCode();
                $j++;
                if (!empty($value['account'])) {
                    foreach (@$value['account'] as $ac_key => $ac_value) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$ac_key);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($ac_value['total'], 2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                        $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                        $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                        $j++;
                    }
                }
                if (!empty($value['sub_categories'])) {
                    foreach (@$value['sub_categories'] as $sub_key => $sub_value) {
                        $total_profit = 0;
                        $arr[$sub_key] = $sub_value;
                        $total_profit = subGrp_total($arr, 0);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, @$sub_value['name']);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, number_format($total_profit, 2));
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, '');
                        $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
                        $spreadsheet->getActiveSheet()->getStyle('E' . $j)->getNumberFormat()->getFormatCode();
                        $j++;
                        unset($arr);
                    }
                }
            }
        }
        if (isset($net_loss) && !empty($net_loss)) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $j, 'Net Loss');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $j, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $j, number_format($net_loss, 2));
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getBorders()
                ->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('D' . $j . ':F' . $j)->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->setFormatCode('#,##0.00');
            $spreadsheet->getActiveSheet()->getStyle('F' . $j)->getNumberFormat()->getFormatCode();
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, 'Total');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, number_format($pl_expens_total, 2));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, 'Total');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format($pl_income_total, 2));
        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getBorders()
            ->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getBorders()
            ->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->getFont()->setSize(15);
        $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $spreadsheet->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->getFormatCode();
        $spreadsheet->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $spreadsheet->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->getFormatCode();

        $spreadsheet->getActiveSheet()->setTitle('Profit-Loss report');
        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function generalSales_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as taxable');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0','pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_income['sales'] = $query->getResultArray();
          
        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_ACparticu pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pg.v_type as pg_type,pp.account as pp_acc,pp.amount as taxable');
            $builder->join('sales_ACinvoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.account');
            $builder->where('pp.account',$get['id']);
            $builder->where(array('pp.is_delete' => '0','pg.is_delete' => '0','pg.is_cancle' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_income['sales'] = $query->getResultArray();

        }else{
            $pg_income['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
      
        $result = $pg_income;
       
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function salesinvoice_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('sales_item pp');
            $builder->select('ac.name as party_name,pg.custom_inv_no,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('sales_invoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'invoice'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['sales'] = $query->getResultArray();
          
        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_item pp');
            $builder->select('ac.name as party_name,pg.custom_inv_no,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('sales_invoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'invoice'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['sales'] = $query->getResultArray();

        }else{
            $pg_expence['sales'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result = $pg_expence;
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];

        // echo '<pre>';print_r($result);exit;
        return $result;     
    }
    public function salesreturn_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('sales_item pp');
            $builder->select('ac.name as party_name,pg.supp_inv,pg.return_date as date,pg.return_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('sales_return pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'return','pp.is_delete' => '0'));
            $builder->where(array('DATE(pg.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['sales_return'] = $query->getResultArray();
            
        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('sales_item pp');
            $builder->select('ac.name as party_name,pg.supp_inv,pg.return_date as date,pg.return_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('sales_return pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'return','pp.is_delete' => '0'));
            $builder->where(array('DATE(pg.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['sales_return'] = $query->getResultArray();

        }else{
            $pg_expence['sales_return'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result = $pg_expence;
    
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];

        return $result;     
    }
    public function purchaseinvoice_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('purchase_item pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('purchase_invoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'invoice','pp.is_delete' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['purchase'] = $query->getResultArray();
            // echo $db->getLastQuery();exit;


        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_item pp');
            $builder->select('ac.name as party_name,pg.invoice_date as date,pg.invoice_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('purchase_invoice pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'invoice','pp.is_delete' => '0'));
            $builder->where(array('DATE(pg.invoice_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.invoice_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['purchase'] = $query->getResultArray();

        }else{
            $pg_expence['purchase'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result = $pg_expence;
     
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];

        return $result;     
    }
    public function purchasereturn_voucher_wise_data($get){

        $db = $this->db;
        $db->setDatabase(session('DataSource')); 
       
        if(!empty($get['year'])){

            $start = strtotime("{$get['year']}-{$get['month']}-01");
            $end = strtotime('-1 second', strtotime('+1 month', $start));
             
            $start_date = date('Y-m-d',$start);
            $end_date = date('Y-m-d',$end);
           
            $builder = $db->table('purchase_item pp');
            $builder->select('ac.name as party_name,pg.return_date as date,pg.return_no as voucher_no,pg.id,pp.item_id as pp_acc,pp.total as taxable');
            $builder->join('purchase_return pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'return','pp.is_delete' => '0'));
            $builder->where(array('DATE(pg.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['purchase_return'] = $query->getResultArray();
            
        }else if(!empty(@$get['from'])){
            $start_date = @$get['from']  ? db_date($get['from']) : '';
            $end_date = @$get['to'] ? db_date($get['to']) : '';

            $builder = $db->table('purchase_item pp');
            $builder->select('ac.name as party_name,pg.return_date as date,pg.return_no as voucher_no,pg.id,pp.item_id as pp_acc,,pp.total as taxable');
            $builder->join('purchase_return pg', 'pg.id = pp.parent_id');
            $builder->join('account ac', 'ac.id = pp.item_id');
            $builder->where('pp.item_id',$get['id']);
            $builder->where(array('pp.is_delete' => '0','is_expence'=>1,'pg.is_delete' => '0','pg.is_cancle' => '0','pp.type'=>'return','pp.is_delete' => '0'));
            $builder->where(array('DATE(pg.return_date)  >= ' => $start_date));
            $builder->where(array('DATE(pg.return_date)  <= ' => $end_date));
            $query = $builder->get();
            $pg_expence['purchase_return'] = $query->getResultArray();
            
        }else{
            $pg_expence['purchase_return'] = array();
            $start_date = '';
            $end_date = '';
        }   
        $result = $pg_expence;
        $result['date']['from'] = $start_date;
        $result['date']['to'] = $end_date;
        $result['ac_id'] = $get['id'];
        $result['month'] = @$get['month'];
        $result['year'] = @$get['year'];

        return $result;     
    }
    
}
?>
