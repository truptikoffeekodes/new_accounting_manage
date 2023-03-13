<?php

namespace App\Models;

use App\Models\GeneralModel;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GstModel extends Model
{
    public function insert_edit_gstr2_JSON($file)
    {
        $msg = array();

        if ($file->isValid() && !$file->hasMoved()) {
            $original_path = '/gstr2_json/' . date('Ymd') . '/';

            if (!file_exists(getcwd() . $original_path)) {
                mkdir(getcwd() . $original_path, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move(getcwd() . $original_path, $newName);

            $path = $original_path . $newName;
            // $json =  $pdata['gstr2_json'];
            $file = url($path);

            $str = file_get_contents($file);
            $arr = json_decode($str, true);

            $pdata['cess'] = 0;
            $pdata['cgst'] = 0;
            $pdata['sgst'] = 0;
            $pdata['igst'] = 0;
            $pdata['taxable'] = 0;
            $pdata['total'] = 0;
            $pdata['iamt'] = 0;

            $b2b_vcount = count($arr['b2b']);
            $pdata['b2b_count'] = $b2b_vcount;

            foreach ($arr['b2b'] as $row) {
                foreach ($row['inv'] as $inv) {
                    $pdata['cess'] += @$inv['itms'][0]['itm_det']['csamt'];
                    $pdata['cgst'] += @$inv['itms'][0]['itm_det']['camt'];
                    $pdata['sgst'] += @$inv['itms'][0]['itm_det']['samt'];
                    $pdata['iamt'] += @$inv['itms'][0]['itm_det']['iamt'];
                    $pdata['taxable'] += @$inv['itms'][0]['itm_det']['txval'];
                    $pdata['total'] += @$inv['val'];
                    $date = @$inv['idt'];
                }
            }

            $cdn_vcount = count($arr['cdn']);
            $cdata['cdn_count'] = $cdn_vcount;
            $cdata['cess'] = 0;
            $cdata['cgst'] = 0;
            $cdata['sgst'] = 0;
            $cdata['iamt'] = 0;
            $cdata['taxable'] = 0;
            $cdata['total'] = 0;
            foreach ($arr['cdn'] as $row) {
                foreach ($row['nt'] as $inv) {
                    $cdata['cess'] += @$inv['itms'][0]['itm_det']['csamt'];
                    $cdata['cgst'] += @$inv['itms'][0]['itm_det']['camt'];
                    $cdata['sgst'] += @$inv['itms'][0]['itm_det']['samt'];
                    $cdata['iamt'] += @$inv['itms'][0]['itm_det']['iamt'];
                    $cdata['taxable'] += @$inv['itms'][0]['itm_det']['txval'];
                    $cdata['total'] += @$inv['val'];
                }
            }
            if (!empty($date)) {
                $date = date('Y-m-d', strtotime($date));

                $first_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", first day of this month");
                $first_date = date("Y-m-d", $first_date_find);

                $last_date_find = strtotime(date("Y-m-d", strtotime($date)) . ", last day of this month");
                $last_date = date("Y-m-d", $last_date_find);
            }
            $db = $this->db;
            $db->setDatabase(session('DataSource'));
            $builder = $db->table('b2b_json');
            $builder->select('*');
            $builder->where('from_date', $first_date);
            $builder->where('to_date', $last_date);
            $query = $builder->get();
            $result_array = $query->getResultArray();

            $data = array();
            $db = $this->db;
            $builder = $db->table(' b2b_json');
            if (!empty($pdata)) {
                $data['b2b'] = json_encode($pdata);
            }
            if (!empty($cdata)) {
                $data['cdn'] = json_encode($cdata);
            }

            $data['from_date'] = @$first_date;
            $data['to_date'] = @$last_date;

            if (!empty($result_array)) {
                $data['update_at'] = date('Y-m-d H:i:s');
                $data['update_by'] = session('uid');

                $builder->where(array("from_date" => $first_date));
                $builder->where(array("to_date" => $last_date));
                $result = $builder->update($data);

                $builder = $db->table('b2b_json');
                $builder->select('*');
                $builder->where('from_date', $first_date);
                $builder->where('to_date', $last_date);
                $query = $builder->get();
                $result_arr = $query->getRowArray();

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!", 'data' => $result_arr, 'start_date' => $first_date, 'end_date' => $last_date);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail ..!!!", 'data' => $result_arr);
                }
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = session('uid');

                $result = $builder->insert($data);
                $id = $db->insertID();

                $builder = $db->table('b2b_json');
                $builder->select('*');
                $builder->where('id', $id);
                $query = $builder->get();
                $result_arr = $query->getRowArray();
                $msg = array();

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!", 'data' => $result_arr, 'start_date' => $first_date, 'end_date' => $last_date);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail ..!!!", 'data' => $result_arr);
                }
            }

        }

        return $msg;

    }

    public function gstr3_xls_export_data($post)
    {
        $data = get_gstr3_detail(db_date($post['from']), db_date($post['to']));
       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getStyle('A4:H4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'GSTR-3 Report');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', db_date(@$post['from']));
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', db_date(@$post['to']));
       

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SI NO.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Particular');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Taxable Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Integrated Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'Central Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'State Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'Cess Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'Tax Amount');
       
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . 5, 3.1);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . 5, 'Outward Supplies and inward supplies liable to Reverse charge');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . 5, @$data['outward']['taxable_amount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . 5, @$data['outward']['igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . 5, @$data['outward']['cgst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . 5, @$data['outward']['sgst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . 5, @$data['outward']['cess']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . 5, @$data['outward']['cess'] +@$data['outward']['sgst'] + @$data['outward']['cgst'] + @$data['outward']['igst']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . 6, 3.2);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . 6, 'Of the Supplies Shown in 3.1(a) above,detail of inter-state Supplied maid to unregister persons,composition taxable  person and UIN holders');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . 6, @$data['gst_type_wise']['unregister']['taxable_amount'] +  @$data['gst_type_wise']['composition']['taxable_amount']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . 6, @$data['gst_type_wise']['unregister']['igst'] +  @$data['gst_type_wise']['composition']['igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . 6, @$data['gst_type_wise']['unregister']['cgst'] +  @$data['gst_type_wise']['composition']['cgst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . 6, @$data['gst_type_wise']['unregister']['sgst'] +  @$data['gst_type_wise']['composition']['sgst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . 6, @$data['gst_type_wise']['unregister']['cess'] +  @$data['gst_type_wise']['composition']['cess']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . 6, @$data['gst_type_wise']['unregister']['cess'] + @$data['gst_type_wise']['unregister']['sgst'] + @$data['gst_type_wise']['unregister']['cgst'] + @$data['gst_type_wise']['unregister']['igst'] +  @$data['gst_type_wise']['composition']['cess'] + @$data['gst_type_wise']['composition']['igst'] + @$data['gst_type_wise']['composition']['cgst'] +@$data['gst_type_wise']['composition']['sgst']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . 7, 4);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . 7, 'Eligible ITC');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . 7,'');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . 7,@$data['eligable_itc']['igst'] + @$data['import_good']['tot_gst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . 7, @$data['eligable_itc']['cgst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . 7, @$data['eligable_itc']['Sgst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . 7, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . 7, '');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . 8, 5);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . 8, 'Value of exempt,nil rated and non-GST inward supplies');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . 8, @$data['nill']['exempt'] + @$data['nill']['non_gst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . 8, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . 8, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . 8, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . 8, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . 8, '');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . 9, 5.1);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . 9, 'Interest and Late fee Payable');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . 9, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . 9, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . 9, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . 9, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . 9, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . 9, '');
    
        $spreadsheet->getActiveSheet()->setTitle('GSTR-3');
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }

    public function gstr1_export_data($post)
    {

        $data = get_gstr1_detail(db_date($post['from']), db_date($post['to']));

        $b2b = $data['b2b']['data'];
        
        
        $b2cs = $data['b2cSmall']['data'];
        $cdnr = @$data['cr_drReg']['data'];
        $cdnur = $data['cr_drUnReg']['data'];
        $hsn = $data['hsn']['data'];

        $final = array();
        $final['gstin'] = session('gst');
        $date = date_create(session('financial_from'));

        $final['fp'] = date_format($date, 'mY');
        $final['gt'] = 0.00;
        $final['cur_gt'] = 0.00;

        $gst_wise = array();
        $cdnur_gst_wise = array();
        $cdnr_gst_wise = array();

        $gmodel = new GeneralModel();

        //------------B2B (b2b)---------//

        foreach ($b2b as $row) {
            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');
            $row['state_code'] = $state['state_code'];
            $gst_wise[$row['gst']][] = $row;
        }

        foreach ($gst_wise as $gst_arr) {
            $inv = array();

            foreach ($gst_arr as $row) {

                $itm = array();

                $taxes = json_decode($row['taxes']);

                $arr['inum'] = @$row['custom_inv_no'] ? @$row['custom_inv_no'] : '';
                $arr['idt'] = user_date(@$row['invoice_date']);
                $arr['val'] = (float) @$row['net_amount'];
                $arr['pos'] = @$row['state_code'];
                $arr['rchrg'] = "N";

                $itm_det['txval'] = @$row['taxable'];
                $itm_det['rt'] = 18;

                if (in_array('igst', $taxes)) {
                    $itm_det['iamt'] = (float) @$row['tot_igst'];
                } else {
                    $itm_det['camt'] = (float) @$row['tot_cgst'];
                    $itm_det['samt'] = (float) @$row['tot_sgst'];
                }
                $itm_det['csamt'] = (float) @$row['cess'];

                $itm['num'] = 1;
                $itm['itmdtl'] = $itm_det;

                $arr['itms'] = array($itm);

                $arr['inv_typ'] = 'R';

                $inv[] = $arr;
                $b2b_arr['ctin'] = $row['gst'];
                $b2b_arr['inv'] = $inv;
            }
            $final['b2b'][] = $b2b_arr;

        }
        //echo '<pre>';print_r($b2b);exit;
        //------------B2C Small (b2cs)---------//

        foreach ($b2cs as $row) {
            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');

            $taxes = json_decode($row['taxes']);
            if (@$state['state_code'] == session('state_code')) {
                $supply_type = "INTER";
            } else {
                $supply_type = "INTERA";
            }
            $b2c_data['rt'] = '';
            $b2c_data['sply_ty'] = $supply_type;
            $b2c_data['pos'] = @$state['state_code'];
            $b2c_data['typ'] = "OE";
            $b2c_data['txval'] = (float) $row['taxable'];

            if (in_array('igst', $taxes)) {
                $b2c_data['iamt'] = (float) @$row['tot_igst'];
            } else {
                $b2c_data['camt'] = (float) @$row['tot_cgst'];
                $b2c_data['samt'] = (float) @$row['tot_sgst'];
            }
            $b2c_data['csamt'] = @$row['cess'] ? (float) @$row['cess'] : 0.00;

            $final['b2cs'][] = $b2c_data;
        }


        //------------ CREDIT NOTE REGISTER (CDNR)---------//

        foreach ($cdnr as $row) {
            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');
            $row['state_code'] = $state['state_code'];
            $cdnr_gst_wise[$row['gst']][] = $row;
        }

        foreach ($cdnr_gst_wise as $cdnr_gst_arr) {

            $cdnr_nt = array();

            foreach ($cdnr_gst_arr as $row) {

                $itm = array();

                $taxes = json_decode($row['taxes']);

                $cdnr_arr['ntty'] = "C";
                $cdnr_arr['nt_num'] = @$row['return_no'];
                $cdnr_arr['nt_dt'] = user_date(@$row['return_date']);
                $cdnr_arr['p_gst'] = @$row['state_code'];
                $cdnr_arr['rchrg'] = "N";
                $cdnr_arr['inv_typ'] = "R";
                $cdnr_arr['val'] = (float) @$row['net_amount'];

                $cdnr_itm_det['txval'] = (float) @$row['taxable'];
                $cdnr_itm_det['rt'] = 18;

                if (in_array('igst', $taxes)) {
                    $cdnr_itm_det['iamt'] = (float) @$row['tot_igst'];
                } else {
                    $cdnr_itm_det['camt'] = (float) @$row['tot_cgst'];
                    $cdnr_itm_det['samt'] = (float) @$row['tot_sgst'];
                }
                $cdnr_itm_det['csamt'] = (float) @$row['cess'];

                $cdnr_itm['num'] = 1;
                $cdnr_itm['itm_det'] = $cdnr_itm_det;

                $cdnr_arr['itms'] = array($cdnr_itm);

                $cdnr_arr['inv_typ'] = 'R';

                $cdnr_nt[] = $cdnr_arr;
                $cdnr_final['ctin'] = $row['gst'];
                $cdnr_final['nt'] = $cdnr_nt;

            }
            $final['cdnr'][] = $cdnr_final;

        }

        //------------ CREDIT NOTE UNREGISTER (CDNUR)---------//

        foreach ($cdnur as $row) {
            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');
            $row['state_code'] = @$state['state_code'];
            $cdnur_gst_wise[$row['gst']][] = $row;
        }

        foreach ($cdnur_gst_wise as $cdnur_gst_arr) {

            $cdnur_nt = array();

            foreach ($cdnur_gst_arr as $row) {

                $itm = array();

                $taxes = json_decode($row['taxes']);

                $cdnur_arr['typ'] = "B2CL";
                $cdnur_arr['ntty'] = "C";
                $cdnur_arr['nt_num'] = @$row['return_no'];
                $cdnur_arr['nt_dt'] = user_date(@$row['return_date']);
                $cdnur_arr['pos'] = @$row['state_code'];

                $cdnur_itm_det['txval'] = @$row['taxable'];
                $cdnur_itm_det['rt'] = 18;

                if (in_array('igst', $taxes)) {
                    $cdnur_itm_det['iamt'] = @$row['tot_igst'];
                } else {
                    $cdnur_itm_det['camt'] = @$row['tot_cgst'];
                    $cdnur_itm_det['samt'] = @$row['tot_sgst'];
                }
                $cdnur_itm_det['csamt'] = @$row['cess'];

                $cdnur_itm['num'] = 1;
                $cdnur_itm['itmdtl'] = $cdnr_itm_det;

                $cdnur_arr['itms'] = array($cdnr_itm);

                $cdnur_arr['inv_typ'] = 'R';

                $cdnur_nt[] = $cdnr_arr;
                $cdnur_final['ctin'] = $row['gst'];
                $cdnur_final['nt'] = $cdnr_nt;
            }

            $final['cdnur'][] = $cdnur_final;

        }

        //------------ HSN ---------//

        $hsn_arr = array();
        $hsn_da = array();

        foreach ($hsn as $row) {
            $taxes = json_decode($row['taxes']);
            $total = 0;
            $total = (float) $row['qty'] * (float) $row['rate'];
            $gst_amt = (float) $total * ((float) $row['igst'] / 100);

            $row['igst_amt'] = 0;
            $row['sgst_amt'] = 0;
            $row['cgst_amt'] = 0;

            if (in_array('igst', $taxes)) {
                $row['igst_amt'] = (float) $gst_amt;
            } else {
                $row['sgst_amt'] = (float) $gst_amt / 2;
                $row['cgst_amt'] = (float) $gst_amt / 2;
            }

            $hsn_arr[$row['hsn']][] = $row;

        }

        foreach ($hsn_arr as $hsn_rw) {

            $i = 1;

            foreach ($hsn_rw as $row) {
                $hsn_data['num'] = $i;
                $hsn_data['hsn_sc'] = $row['hsn'];
                $hsn_data['desc'] = $row['item_name'];
                $hsn_data['uqc'] = $row['uom'];
                $hsn_data['qty'] = (@$hsn_data['qty'] ? (float) $hsn_data['qty'] : 0) + (float) $row['qty'];
                $hsn_data['val'] = (float) number_format((@$hsn_data['val'] ? (float) $hsn_data['val'] : 0) + ((float) $row['rate'] * (float) $row['qty']) + (float) $row['igst_amt'] + (float) $row['cgst_amt'] + (float) $row['sgst_amt'], 2);
                $hsn_data['txval'] = (float) number_format((@$hsn_data['val'] ? (float) $hsn_data['val'] : 0) + ((float) $row['rate'] * (float) $row['qty']), 2);
                $hsn_data['iamt'] = (float) number_format((@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) + (float) $row['igst_amt'], 2);
                $hsn_data['samt'] = (float) number_format((@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) + (float) $row['sgst_amt'], 2);
                $hsn_data['camt'] = (float) number_format((@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) + (float) $row['cgst_amt'], 2);

            }
            $hsn_da['data'][] = $hsn_data;
        }
        $final['hsn'] = $hsn_da;

        return $final_json = json_encode($final);
    }


    public function gstr1_xls_export_data($post)
    {
        $data = get_gstr1_detail(db_date($post['from']), db_date($post['to']));

        $nill = get_nill_detail(db_date($post['from']),db_date($post['to']));
       
        // $inter_unreg = $nill['inter_unreg']['data'];
        // $inter_reg = $nill['inter_reg']['data'];
        // $intera_reg = $nill['intera_reg']['data'];
        // $intera_unreg = $nill['intera_unreg']['data'];

        // $inter_unreg_nill['taxable'] = 0;
        // $inter_unreg_nill['net_amt'] = 0;

        // $inter_unreg_exempt['taxable'] = 0;
        // $inter_unreg_exempt['net_amt'] = 0;

        // $inter_unreg_na['taxable'] = 0;
        // $inter_unreg_na['net_amt'] = 0;

        // $inter_reg_nill['taxable'] = 0;
        // $inter_reg_nill['net_amt'] = 0;

        // $inter_reg_exempt['taxable'] = 0;
        // $inter_reg_exempt['net_amt'] = 0;
        
        // $inter_reg_na['taxable'] = 0;
        // $inter_reg_na['net_amt'] = 0;

        // $intera_reg_nill['taxable'] = 0;
        // $intera_reg_nill['net_amt'] = 0;

        // $intera_reg_exempt['taxable'] = 0;
        // $intera_reg_exempt['net_amt'] = 0;
        
        // $intera_reg_na['taxable'] = 0;
        // $intera_reg_na['net_amt'] = 0;

        // $intera_unreg_nill['taxable'] = 0;
        // $intera_unreg_nill['net_amt'] = 0;

        // $intera_unreg_exempt['taxable'] = 0;
        // $intera_unreg_exempt['net_amt'] = 0;
        
        // $intera_unreg_na['taxable'] = 0;
        // $intera_unreg_na['net_amt'] = 0;

        // foreach($inter_unreg as $row){
        //     if($row['inv_taxability'] == 'Exempt'){

        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $inter_unreg_exempt['taxable'] -= $row['taxable'];
        //             $inter_unreg_exempt['net_amt'] -= $row['net_amount'];   
        //         }else{
        //             $inter_unreg_exempt['taxable'] += $row['taxable'];
        //             $inter_unreg_exempt['net_amt'] += $row['net_amount'];    
        //         }
                
        //     }else if($row['inv_taxability'] == 'Nill'){

        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $inter_unreg_exempt['taxable'] -= $row['taxable'];
        //             $inter_unreg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $inter_unreg_exempt['taxable'] += $row['taxable'];
        //             $inter_unreg_exempt['net_amt'] += $row['net_amount'];
        //         }
                
        //     }else{

        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){

        //             $inter_unreg_na['taxable'] -= $row['taxable'];
        //             $inter_unreg_na['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $inter_unreg_na['taxable'] += $row['taxable'];
        //             $inter_unreg_na['net_amt'] += $row['net_amount'];
        //         }
        //     }
        // }

        // foreach($inter_reg as $row){
        //     if($row['inv_taxability'] == 'Exempt'){

        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $inter_reg_exempt['taxable'] -= $row['taxable'];
        //             $inter_reg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $inter_reg_exempt['taxable'] += $row['taxable'];
        //             $inter_reg_exempt['net_amt'] += $row['net_amount'];
        //         }
               
        //     }else if($row['inv_taxability'] == 'Nill'){
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $inter_reg_exempt['taxable'] -= $row['taxable'];
        //             $inter_reg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $inter_reg_exempt['taxable'] += $row['taxable'];
        //             $inter_reg_exempt['net_amt'] += $row['net_amount'];
        //         }
        //     }else{
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $inter_reg_na['taxable'] -= $row['taxable'];
        //             $inter_reg_na['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $inter_reg_na['taxable'] += $row['taxable'];
        //             $inter_reg_na['net_amt'] += $row['net_amount'];
        //         }
        //     }
        // }

        // foreach($intera_reg as $row){
        //     if($row['inv_taxability'] == 'Exempt'){
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $intera_reg_exempt['taxable'] -= $row['taxable'];
        //             $intera_reg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $intera_reg_exempt['taxable'] += $row['taxable'];
        //             $intera_reg_exempt['net_amt'] += $row['net_amount'];
        //         }
                
        //     }else if($row['inv_taxability'] == 'Nill'){
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $intera_reg_exempt['taxable'] -= $row['taxable'];
        //             $intera_reg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $intera_reg_exempt['taxable'] += $row['taxable'];
        //             $intera_reg_exempt['net_amt'] += $row['net_amount'];
        //         }
                

        //     }else{
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $intera_reg_na['taxable'] -= $row['taxable'];
        //             $intera_reg_na['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $intera_reg_na['taxable'] += $row['taxable'];
        //             $intera_reg_na['net_amt'] += $row['net_amount'];
        //         }
              
        //     }
        // }

        // foreach($intera_unreg as $row){
        //     if($row['inv_taxability'] == 'Exempt'){
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $intera_unreg_exempt['taxable'] -= $row['taxable'];
        //             $intera_unreg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $intera_unreg_exempt['taxable'] += $row['taxable'];
        //             $intera_unreg_exempt['net_amt'] += $row['net_amount'];
        //         }
                
        //     }else if($row['inv_taxability'] == 'Nill'){
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $intera_unreg_exempt['taxable'] -= $row['taxable'];
        //             $intera_unreg_exempt['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $intera_unreg_exempt['taxable'] += $row['taxable'];
        //             $intera_unreg_exempt['net_amt'] += $row['net_amount'];
        //         }
        //     }else{
        //         if(isset($row['return_no']) || @$row['v_type'] == 'return'){
        //             $intera_unreg_na['taxable'] -= $row['taxable'];
        //             $intera_unreg_na['net_amt'] -= $row['net_amount'];
        //         }else{
        //             $intera_unreg_na['taxable'] += $row['taxable'];
        //             $intera_unreg_na['net_amt'] += $row['net_amount'];
        //         }
        //     }
        // }


        $b2b = $data['b2b']['data'];
        //echo '<pre>';Print_r($b2b);exit;
        
        $b2c_large = $data['b2cLarge']['data'];
        $b2c_small = $data['b2cSmall']['data'];

        $cdnr = $data['cr_drReg']['data'];
        $final_cdnur = $data['cr_drUnReg']['data'];
        // echo '<pre>';print_r($final_cdnur);exit;
        $hsn = $data['hsn']['data'];

        $db = $this->db;

        $builder = $db->table('sales_invoice');
        $builder->select('id,custom_inv_no');
        $builder->where('is_delete',0);
        $builder->where('is_cancle',0);
        $builder->where(array('DATE(invoice_date)  >= ' => db_date($data['start_date'])));
        $builder->where(array('DATE(invoice_date)  <= ' => db_date($data['end_date'])));
        $query = $builder->get();
        $res = $query->getResultArray();

        $builder = $db->table('sales_ACinvoice');
        $builder->select('id,supp_inv as custom_inv_no');
        $builder->where('is_delete',0);
        $builder->where('is_cancle',0);
        $builder->where('v_type','general');
        $builder->where(array('DATE(invoice_date)  >= ' => db_date($data['start_date'])));
        $builder->where(array('DATE(invoice_date)  <= ' => db_date($data['end_date'])));
        $query = $builder->get();
        $gnrl_res = $query->getResultArray();

        $sales_invoice = array_merge($res,$gnrl_res);
        
        $builder = $db->table('sales_return');
        $builder->select('id,return_no');
        $builder->where('is_delete',0);
        $builder->where('is_cancle',0);
        $builder->where(array('DATE(return_date)  >= ' => db_date($data['start_date'])));
        $builder->where(array('DATE(return_date)  <= ' => db_date($data['end_date'])));
        $query = $builder->get();
        $res1 = $query->getResultArray();

        $builder = $db->table('sales_ACinvoice');
        $builder->select('id,supp_inv as custom_inv_no');
        $builder->where('is_delete',0);
        $builder->where('is_cancle',0);
        $builder->where('v_type','return');
        $builder->where(array('DATE(invoice_date)  >= ' => db_date($data['start_date'])));
        $builder->where(array('DATE(invoice_date)  <= ' => db_date($data['end_date'])));
        $query = $builder->get();
        $gnrl_res1 = $query->getResultArray();

        $sales_return = array_merge($res1,$gnrl_res1);
        $gmodel = new GeneralModel();
        $credit_sale_count = count($sales_return);
        $cancle_ret = $gmodel->get_data_table('sales_return',array('is_cancle'=>1 , 'DATE(return_date)  >= '=> db_date($data['start_date']) ,'DATE(return_date)  <= ' => db_date($data['end_date'])),'COUNT(id) as ret_count');
        $cancle_gnrlret = $gmodel->get_data_table('sales_ACinvoice',array('v_type'=>'return','is_cancle'=>1 , 'DATE(invoice_date)  >= '=> db_date($data['start_date']) ,'DATE(invoice_date)  <= ' => db_date($data['end_date'])),'COUNT(id) as gnrlret_count');

        $credit_sale_from = @$sales_return[0]['return_no'];
        $credit_sale_to = @$sales_return[$credit_sale_count-1]['return_no'];

        $outward_sale_count = count($sales_invoice);
        $cancle_sale = $gmodel->get_data_table('sales_invoice',array('is_cancle'=>1 , 'DATE(invoice_date)  >= '=> db_date($data['start_date']) ,'DATE(invoice_date)  <= ' => db_date($data['end_date'])),'COUNT(id) as sale_count');
        $cancle_gnrlsale = $gmodel->get_data_table('sales_ACinvoice',array('v_type'=>'general','is_cancle'=>1 , 'DATE(invoice_date)  >= '=> db_date($data['start_date']) ,'DATE(invoice_date)  <= ' => db_date($data['end_date'])),'COUNT(id) as gnrlsale_count');


        $outward_sale_from = $sales_invoice[0]['custom_inv_no'];
        $outward_sale_to = $sales_invoice[$outward_sale_count-1]['custom_inv_no'];

        $gmodel = new GeneralModel;

        $hsn_arr = array();
        $hsn_da = array();

        $final_b2b = array();
        $final_cdnr = array();
        
        foreach($b2b as $row){

            if(isset($row['v_type'])){

                $builder = $db->table('sales_ACparticu');
                $builder->select('taxability,SUM(amount) as total ,igst');
                $builder->where('is_delete',0);
                $builder->where('parent_id',$row['id']);
                $builder->groupBy('igst');
                $query = $builder->get();
                $result = $query->getResultArray();              

            }else{

                $builder = $db->table('sales_item');
                $builder->select('taxability,SUM(rate*qty) as total,igst,AVG(item_disc) as disc');
                $builder->where('is_delete',0);
                $builder->where('type','invoice');
                $builder->where('parent_id',$row['id']);
                $builder->groupBy('igst');
                $query = $builder->get();
                $result = $query->getResultArray();
                
            }
            
            foreach($result as $row2){
                // if($row2['taxability'] != 'Exempt' && $row2['taxability'] != 'Nill'){
                    if(isset($row2['disc']) && $row2['disc'] > 0 ){
                        $disc_amt = ($row2['total'] * $row2['disc'])/100;
                        $total = $row2['total'] - $disc_amt; 
                        $row['taxable'] = $total;

                    }else{
                        $row['taxable'] = $row2['total'];
                    }
                    $row['igst'] = $row2['igst'];
                    $final_b2b[] = $row;
                // }
            }
        } 
        
        foreach($cdnr as $row){

            $db = $this->db;

            if(isset($row['v_type'])){
                $builder = $db->table('sales_ACparticu');
                $builder->select('taxability,SUM(amount) as total ,igst');
                $builder->where('is_delete',0);
                $builder->where('parent_id',$row['id']);
                $builder->groupBy('igst');
                $query = $builder->get();
                $result = $query->getResultArray();
            }else{
                $builder = $db->table('sales_item');
                $builder->select('taxability,SUM(rate*qty) as total ,igst');
                $builder->where('is_delete',0);
                $builder->where('type','return');
                $builder->where('parent_id',$row['id']);
                $builder->groupBy('igst');
                $query = $builder->get();
                $result = $query->getResultArray();
            }
            
            foreach($result as $row2){
            //   if($row2['taxability'] != 'Exempt' && $row2['taxability'] != 'Nill'){  
                $row['taxable'] = $row2['total'];
                $row['igst'] = $row2['igst'];

                $final_cdnr[] = $row;
            //   }
            }



        } 
        
      
        // foreach($cdnur as $row){

        //     $db = $this->db;
        //     if($row['acc_state'] != session('state')){
        //         if(isset($row['v_type'])){

        //             $builder = $db->table('sales_ACparticu');
        //             $builder->select('taxability,SUM(amount) as total ,igst');
        //             $builder->where('is_delete',0);
        //             $builder->where('parent_id',$row['id']);
        //             $builder->groupBy('igst');
        //             $query = $builder->get();
        //             $result = $query->getResultArray();              

        //         }else{

        //             $builder = $db->table('sales_item');
        //             $builder->select('taxability,SUM(rate*qty) as total ,igst');
        //             $builder->where('is_delete',0);
        //             $builder->where('type','return');
        //             $builder->where('parent_id',$row['id']);
        //             $builder->groupBy('igst');
        //             $query = $builder->get();
        //             $result = $query->getResultArray();

        //         }
                
        //         foreach($result as $row2){
        //             if($row2['taxability'] != 'Exempt' && $row2['taxability'] != 'Nill'){  

        //                 $row['taxable'] = $row2['total'];
        //                 $row['igst'] = $row2['igst'];
        //                 $final_cdnur[] = $row;
        //             }
        //         }
        //     }else{

        //         if(isset($row['v_type'])){

        //             $builder = $db->table('sales_ACparticu');
        //             $builder->select('taxability,SUM(amount) as total ,igst');
        //             $builder->where('is_delete',0);
        //             $builder->where('parent_id',$row['id']);
        //             $builder->groupBy('igst');
        //             $query = $builder->get();
        //             $result = $query->getResultArray();              

        //         }else{

        //             $builder = $db->table('sales_item');
        //             $builder->select('taxability,SUM(rate*qty) as total ,igst');
        //             $builder->where('is_delete',0);
        //             $builder->where('type','return');
        //             $builder->where('parent_id',$row['id']);
        //             $builder->groupBy('igst');
        //             $query = $builder->get();
        //             $result = $query->getResultArray();
        //         }

        //         //---- get cdnur company state total taxable gst wise  ---//

        //         foreach($result as $row2){
        //             if($row2['taxability'] != 'Exempt' && $row2['taxability'] != 'Nill'){

        //                 $row['taxable'] = $row2['total'];
        //                 $row['igst'] = $row2['igst'];
        //                 $comp_state_cdnur[$row2['igst']]['state'] = $row['acc_state'];
        //                 $comp_state_cdnur[$row2['igst']]['gst'] = $row2['igst'];
        //                 $comp_state_cdnur[$row2['igst']]['taxable'] = (@$comp_state_cdnur[$row2['igst']]['taxable'] ? $comp_state_cdnur[$row2['igst']]['taxable'] : 0) + $row2['total'];
        //             }
        //         }
        //     }
        // }

        // $new_b2b_small = array();
        // $abc = 0;
        // foreach ($b2c_small as $row) {
        
        //     if (isset($row['v_type'])) {
        //         $sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'taxability,igst , amount as total');
        //     } else {
        //         $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'taxability,igst,(rate*qty) as total');
        //     }
        //     $invtotal = 0;

        //     foreach($sale as $row1){
        //         if($row1['taxability'] != 'Exempt' && $row1['taxability'] != 'Nill'){
        //             $invtotal = 0;
        //             $new_b2b_small[$row['acc_state']][$row1['igst']]['acc_state'] = $row['acc_state'];
        //             $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] : 0) + $row1['total'];
        //             $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] : 0) + $row['cess'];
        //             $new_b2b_small[$row['acc_state']][$row1['igst']]['gst'] = $row1['igst'];
        //         }
        //     }
        // }


        // $b2c_small = $data['b2cSmall']['data'];
        $cdnur_state = $data['cr_drUnReg_state']['data'];
        
        $db = $this->db;
        $gmodel = new GeneralModel;
        $comp_state_cdnur = array();


        foreach($cdnur_state as $row){

            $db = $this->db;

            if($row['gst'] == '' ||  empty($row['gst'])){

                // if($row['acc_state'] == session('state')){
                    if(isset($row['v_type'])){

                        $builder = $db->table('sales_ACparticu');
                        $builder->select('taxability,SUM(amount) as total ,igst');
                        $builder->where('is_delete',0);
                        $builder->where('parent_id',$row['id']);
                        $builder->groupBy('igst');
                        $query = $builder->get();
                        $result = $query->getResultArray();              

                    }else{

                        $builder = $db->table('sales_item');
                        $builder->select('taxability,SUM(rate*qty) as total ,igst');
                        $builder->where('is_delete',0);
                        $builder->where('type','return');
                        $builder->where('parent_id',$row['id']);
                        $builder->groupBy('igst');
                        $query = $builder->get();
                        $result = $query->getResultArray();
                    }

                    //---- get cdnur company state total taxable gst wise  ---//

                    foreach($result as $row2){
                        if($row2['taxability'] != 'Nill' && $row2['taxability'] != 'Exempt' ){

                            $row['taxable'] = $row2['total'];
                            $row['igst'] = $row2['igst'];
                            
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['state'] = $row['acc_state'];
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['gst'] = $row2['igst'];
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] : 0) + $row2['total'];
                        }
                    }
                // }
            }
        } 

        $new_b2b_small = array();

        foreach ($b2c_small as $row) {
        
            if (isset($row['v_type'])) {
                $sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'taxability,igst , amount as total');
            } else {
                $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'taxability,igst,(rate*qty) as total');
            }

            $invtotal = 0;
            foreach($sale as $row1){
                if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt' ){
                    
                    $invtotal = 0;
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['id'] = $row['id'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['acc_state'] = $row['acc_state'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] : 0) + $row1['total'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] : 0) + $row['cess'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['gst'] = $row1['igst'];
                }
            }
        }

        //---- cdnur company state taxable minus from b2c small same state data ---//

        // foreach($comp_state_cdnur as $row){
        //     $new_b2b_small[$row['state']][$row['gst']]['taxable'] = $new_b2b_small[$row['state']][$row['gst']]['taxable'] - $row['taxable'];
        // }
       
        foreach($comp_state_cdnur as $state => $value){
            foreach($value as $gst => $row2){
                $new_b2b_small[$state][$gst]['acc_state'] = @$new_b2b_small[$state][$gst]['acc_state'] ?  @$new_b2b_small[$state][$gst]['acc_state'] :  $state ;
                $new_b2b_small[$state][$gst]['taxable'] = (@$new_b2b_small[$state][$gst]['taxable'] ? (float)$new_b2b_small[$state][$gst]['taxable'] : 0) - (@$row2['taxable'] ? (float)$row2['taxable'] : 0);
                $new_b2b_small[$state][$gst]['gst'] = @$new_b2b_small[$state][$gst]['gst'] ? (float)$new_b2b_small[$state][$gst]['gst'] :   @$row2['gst'] ;
            }
        }

        //---- cdnur company state taxable minus from b2c small same state data ---//

        // foreach($comp_state_cdnur as $row){
        //     $new_b2b_small[$row['state']][$row['gst']]['taxable'] = $new_b2b_small[$row['state']][$row['gst']]['taxable'] - $row['taxable'];
        // }


        // foreach ($hsn as $row) {
        //     if($row['vch_type'] == 'sale_return' || $row['vch_type'] == 'sale_invoice'){
        //         $uom = $gmodel->get_data_table('uom',array('code'=>$row['uom']),'*');
        //     }else{
        //         $uom = array();
        //     }
        //     $taxes = json_decode($row['taxes']);
        //     $total = 0;
        //     $disc_amt=0;
        //     if($row['vch_type'] == 'sale_return' || $row['vch_type'] == 'sale_invoice'){
        //         $total = (float) $row['qty'] * (float) $row['rate'];
        //         if($row['item_disc'] > 0){
        //             $disc_amt = (float)$total * (float)$row['item_disc'] /100;
        //             $total -= $disc_amt;
        //         }
        //     }else{
        //         $total =  (float)$row['rate'];
        //     }

        //     $gst_amt = (float) $total * ((float) $row['igst'] / 100);

        //     $row['igst_amt'] = 0;
        //     $row['sgst_amt'] = 0;
        //     $row['cgst_amt'] = 0;

        //     if (in_array('igst', $taxes)) {
        //         $row['igst_amt'] = (float) $gst_amt;
        //     } else {
        //         $row['sgst_amt'] = (float) $gst_amt / 2;
        //         $row['cgst_amt'] = (float) $gst_amt / 2;
        //     }
            
        //     $row['total_amt'] = (float) $total + (float) $gst_amt;
        //     $row['total_taxable'] = $total;
            
        //     $row['uom_name'] = @$uom['name'];
            
        //     $hsn_arr[$row['hsn']][$row['igst']][] = $row;
            
        // }
      
        
        // foreach ($hsn_arr as $hsn_rw) {

        //     $hsn_data['txval'] =0;
        //     $hsn_data['iamt'] = 0;
        //     $hsn_data['samt'] = 0;
        //     $hsn_data['camt'] = 0;
        //     $hsn_data['val'] = 0;
        //     $hsn_data['qty'] = 0;

        //     foreach ($hsn_rw as $row1) {    
        //         $i = 1;

        //         foreach ($row1 as $row){
                    
        //             $hsn_data['num'] = $i;
        //             $hsn_data['hsn_sc'] = $row['hsn'];
        //             $hsn_data['rate'] = $row['igst'];
        //             $hsn_data['desc'] = $row['item_name'];
        //             $hsn_data['uqc'] = isset($row['uom']) ? @$row['uom'].'-'.@$row['uom_name'] : '';
        //             if($row['vch_type'] == 'sale_invoice'){
        //                 $hsn_data['qty'] = (@$hsn_data['qty'] ? (float) $hsn_data['qty'] : 0) + (float) $row['qty'];
        //             }else if($row['vch_type'] == 'sale_return'){
        //                 $hsn_data['qty'] = (@$hsn_data['qty'] ? (float) $hsn_data['qty'] : 0) - (float) $row['qty'];
        //             }else{
        //                 $hsn_data['qty'] = '';
        //             }
        //             if($row['vch_type'] == 'sale_invoice' || $row['vch_type'] == 'general'){
        //                 $hsn_data['val'] = (@$hsn_data['val'] ? $hsn_data['val'] : 0) + $row['total_amt'];
        //                 $hsn_data['txval'] = (float) (@$hsn_data['txval'] ? (float) $hsn_data['txval'] : 0) + (float) $row['total_taxable'];
        //                 $hsn_data['iamt'] = (float) (@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) + (float) $row['igst_amt'];
        //                 $hsn_data['samt'] = (float) (@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) + (float) $row['sgst_amt'];
        //                 $hsn_data['camt'] = (float) (@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) + (float) $row['cgst_amt'];
        //             }else{
        //                 $hsn_data['val'] = (@$hsn_data['val'] ? $hsn_data['val'] : 0) - $row['total_amt'];
        //                 $hsn_data['txval'] = (float) (@$hsn_data['txval'] ? (float) $hsn_data['txval'] : 0) - (float) $row['total_taxable'];
        //                 $hsn_data['iamt'] = (float) (@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) - (float) $row['igst_amt'];
        //                 $hsn_data['samt'] = (float) (@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) - (float) $row['sgst_amt'];
        //                 $hsn_data['camt'] = (float) (@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) - (float) $row['cgst_amt'];
        //             }
                    
        //         }
        //         $hsn_da['data'][] = $hsn_data;
        //     }
        // }

        foreach ($hsn as $row) {
            
            $taxes = json_decode($row['taxes']);
            $total = 0;
            if($row['vch_type'] != 'general'){
                $total = (float) $row['qty'] * (float) $row['rate'];
                if($row['item_disc'] > 0 ){
                    $disc_amt = (float)$total * (float)$row['item_disc'] /100;
                    $total -= $disc_amt;
                }
                $uom = $gmodel->get_data_table('uom',array('code'=>@$row['uom']),'*');
            }else{
                $total = (float)$row['rate'];
            }

            $gst_amt = (float) $total * ((float) $row['igst'] / 100);

            $row['igst_amt'] = 0;
            $row['sgst_amt'] = 0;
            $row['cgst_amt'] = 0;

            if (in_array('igst', $taxes)) {
                $row['igst_amt'] = (float) $gst_amt;
            } else {
                $row['sgst_amt'] = (float) $gst_amt / 2;
                $row['cgst_amt'] = (float) $gst_amt / 2;
            }
            
            $row['total_amt'] = (float) $total + (float) $gst_amt;
            $row['total_taxable'] = $total;
            $row['uom_name'] = @$uom['name'];
            
            $hsn_arr[$row['hsn']][$row['igst']][] = $row;
            
        }
        
        foreach ($hsn_arr as $hsn_rw) {

            foreach ($hsn_rw as $row1) {    
                $i = 1;
                
                $hsn_data = array();
                $hsn_data['txval'] =0;
                $hsn_data['iamt'] = 0;
                $hsn_data['samt'] = 0;
                $hsn_data['camt'] = 0;
                $hsn_data['val'] = 0;
                $hsn_data['qty'] = 0;
                
                foreach ($row1 as $row){

                    $hsn_data['num'] = $i;
                    $hsn_data['hsn_sc'] = $row['hsn'];
                    $hsn_data['rate'] = $row['igst'];
                    $hsn_data['desc'] = $row['item_name'];
                    $hsn_data['uqc'] = @$row['uom'].'-'.@$row['uom_name'];

                    if($row['type'] == 'general' || $row['type'] == 'invoice' ){

                        $hsn_data['qty'] = (@$hsn_data['qty'] ? (float)@$hsn_data['qty'] : 0) + (float)@$row['qty'];
                        $hsn_data['val'] = (@$hsn_data['val'] ? $hsn_data['val'] : 0) + $row['total_amt'];
                        $hsn_data['txval'] = (float)(@$hsn_data['txval'] ? (float) $hsn_data['txval'] : 0) + (float) $row['total_taxable'];
                        $hsn_data['iamt'] = (float)(@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) + (float) $row['igst_amt'];
                        $hsn_data['samt'] = (float)(@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) + (float) $row['sgst_amt'];
                        $hsn_data['camt'] = (float)(@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) + (float) $row['cgst_amt'];

                    }else{

                        $hsn_data['qty'] = (@$hsn_data['qty'] ? (float) $hsn_data['qty'] : 0) - (float) $row['qty'];
                        $hsn_data['val'] = (@$hsn_data['val'] ? $hsn_data['val'] : 0) - $row['total_amt'];
                        $hsn_data['txval'] = (float)(@$hsn_data['txval'] ? (float) $hsn_data['txval'] : 0) - (float) $row['total_taxable'];
                        $hsn_data['iamt'] = (float)(@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) - (float) $row['igst_amt'];
                        $hsn_data['samt'] = (float)(@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) - (float) $row['sgst_amt'];
                        $hsn_data['camt'] = (float)(@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) - (float) $row['cgst_amt'];
                    }
                    
                }
                $hsn_da['data'][] = $hsn_data;
            }
        }
       
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

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Summary For B2B(4)');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'GSTIN/UIN of Recipient');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Receiver Name');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Invoice Number');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'Invoice date');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'Invoice Value');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'Reverse Charge');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'Invoice Type');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J4', 'E-Commerce GSTIN');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K4', 'Rate');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('L4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('M4', 'Cess Amount');

        $i = 5;
         
        foreach ($final_b2b as $row) {

            if($row['discount'] > 0  && $row['discount'] != '' ){
                $taxable = $row['total_amount'];
            }else{
                $taxable = $row['taxable'];
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $row['gst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, @$row['custom_inv_no'] ? @$row['custom_inv_no'] : $row['supp_inv'] );

            $dt = date_create(@$row['invoice_date']);
            $i_date = date_format($dt, 'd-M-y');
            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $i_date);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format($row['net_amount'], 2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, @$state['state_code'] . '-' . @$state['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, 'N');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $row['gst_type']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, '');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $row['igst']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $taxable);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $row['cess']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('b2b');

        $spreadsheet->createSheet();


        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:I2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:I4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(1)->setCellValue('A1', 'Summary For B2CL(5)');

        $spreadsheet->setActiveSheetIndex(1)->setCellValue('A4', 'Invoice Number');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('B4', 'Invoice date');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('C4', 'Invoice Value');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('D4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('E4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('F4', 'Rate');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('G4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('H4', 'Cess Amount');
        $spreadsheet->setActiveSheetIndex(1)->setCellValue('I4', 'E-Commerce GSTIN');

        $i = 5;

        foreach ($b2c_large as $row) {

            $spreadsheet->setActiveSheetIndex(1)->setCellValue('A' . $i, @$row['custom_inv_no']);

            $dt = date_create(@$row['invoice_date']);
            $i_date = date_format($dt, 'd-M-y');
            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');

            if (isset($row['v_type'])) {
                $sale = $gmodel->get_data_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'igst');
            } else {
                $sale = $gmodel->get_data_table('sales_item', array('parent_id' => $row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'igst');
            }

            $spreadsheet->setActiveSheetIndex(1)->setCellValue('B' . $i, $i_date);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('C' . $i, $row['net_amount']);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('D' . $i, @$state['state_code'] . '-' . @$state['name']);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('E' . $i, '');
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('F' . $i, $sale['igst']);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('G' . $i, $row['taxable']);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('H' . $i, $row['cess']);
            $spreadsheet->setActiveSheetIndex(1)->setCellValue('I' . $i, '');

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('b2cl');

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:G2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:G4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');


        $spreadsheet->setActiveSheetIndex(2)->setCellValue('A1', 'Summary For B2CS(7)');

        $spreadsheet->setActiveSheetIndex(2)->setCellValue('A4', 'Type');
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('B4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('C4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('D4', 'Rate');
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('E4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('F4', 'Cess Amount');
        $spreadsheet->setActiveSheetIndex(2)->setCellValue('G4', 'E-Commerce GSTIN');

        $i = 5;

        foreach ($new_b2b_small as $row1) {
            foreach($row1 as $row){
                $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');

                $spreadsheet->setActiveSheetIndex(2)->setCellValue('A' . $i, 'OE');
                $spreadsheet->setActiveSheetIndex(2)->setCellValue('B' . $i, @$state['state_code'] . '-' . @$state['name']);
                $spreadsheet->setActiveSheetIndex(2)->setCellValue('C' . $i, '');
                $spreadsheet->setActiveSheetIndex(2)->setCellValue('D' . $i, @$row['gst']);
                $spreadsheet->setActiveSheetIndex(2)->setCellValue('E' . $i, @$row['taxable']);
                $spreadsheet->setActiveSheetIndex(2)->setCellValue('F' . $i, @$row['cess']);
                $spreadsheet->setActiveSheetIndex(2)->setCellValue('G' . $i, '');

                $i++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle('b2cs');

        // ------------- Summary For CDNR(9B) ------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:M2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:M4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');


        $spreadsheet->setActiveSheetIndex(3)->setCellValue('A1', 'Summary For CDNR(9B)');

        $spreadsheet->setActiveSheetIndex(3)->setCellValue('A4', 'GSTIN/UIN of Recipient');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('B4', 'Receiver Name');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('C4', 'Note Number');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('D4', 'Note Date');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('E4', 'Note Type');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('F4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('G4', 'Reverse Charge');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('H4', 'Note Supply Type');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('I4', 'Note Value');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('J4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('K4', 'Rate');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('L4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(3)->setCellValue('M4', 'Cess Amount');

        $i = 5;

        foreach ($final_cdnr as $row) {

            if(isset($row['return_date'])){
                $dt = date_create(@$row['return_date']);
            }else{
                $dt = date_create(@$row['invoice_date']);
            }

            $r_date = date_format($dt, 'd-M-y');

            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');

            $spreadsheet->setActiveSheetIndex(3)->setCellValue('A' . $i, $row['gst']);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('B' . $i, $row['name']);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('C' . $i, @$row['supp_inv'] ? $row['supp_inv'] : $row['supp_inv'] );
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('D' . $i, $r_date);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('E' . $i, 'C');
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('F' . $i, @$state['state_code'] . '-' . @$state['name']);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('G' . $i, 'N');
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('H' . $i, 'Regular');
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('I' . $i, $row['net_amount']);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('J' . $i, '');
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('K' . $i, $row['igst']);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('L' . $i, $row['taxable']);
            $spreadsheet->setActiveSheetIndex(3)->setCellValue('M' . $i, $row['cess']);

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('cdnr');

        // ------------- Summary For CDNUR(9B) ------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:J4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');


        $spreadsheet->setActiveSheetIndex(4)->setCellValue('A1', 'Summary For CDNUR(9B)');

        $spreadsheet->setActiveSheetIndex(4)->setCellValue('A4', 'UR Type');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('B4', 'Note Number');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('C4', 'Note Date');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('D4', 'Note Type');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('E4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('F4', 'Note Value');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('G4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('H4', 'Rate');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('I4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(4)->setCellValue('J4', 'Cess Amount');

        $i = 5;

        foreach ($final_cdnur as $row) {

            if(isset($row['return_date'])){
                $dt = date_create(@$row['return_date']);
            }else{
                $dt = date_create(@$row['invoice_date']);
            }

            $r_date = date_format($dt, 'd-M-y');

            $state = $gmodel->get_data_table('states', array('id' => $row['acc_state']), '*');

            $spreadsheet->setActiveSheetIndex(4)->setCellValue('A' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('B' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('C' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('D' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('E' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('F' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('G' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('H' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('I' . $i, '');
            $spreadsheet->setActiveSheetIndex(4)->setCellValue('J' . $i, '');

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('cdnur');

        // ------------- End Summary For CDNUR(9B) ------------- //

        // ------------- Summary For EXP(6) ------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:J4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');


        $spreadsheet->setActiveSheetIndex(5)->setCellValue('A1', 'Summary For EXP(6)');

        $spreadsheet->setActiveSheetIndex(5)->setCellValue('A4', 'Export Type');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('B4', 'Invoice Number');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('C4', 'Invoice date');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('D4', 'Invoice Value');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('E4', 'Port Code');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('F4', 'Shipping Bill Number');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('G4', 'Shipping Bill Date');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('H4', 'Rate');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('I4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(5)->setCellValue('J4', 'Cess Amount');

        $spreadsheet->getActiveSheet()->setTitle('exp');

        // ------------- End Summary For EXP(6) ------------- //

        // ------------- Summary For Summary For Advance Received (11B) ------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:E2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:E4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(6)->setCellValue('A1', 'Summary For Advance Received (11B)');

        $spreadsheet->setActiveSheetIndex(6)->setCellValue('A4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(6)->setCellValue('B4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(6)->setCellValue('C4', 'Rate');
        $spreadsheet->setActiveSheetIndex(6)->setCellValue('D4', 'Gross Advance Received');
        $spreadsheet->setActiveSheetIndex(6)->setCellValue('E4', 'Cess Amount');

        $spreadsheet->getActiveSheet()->setTitle('at');

        // ------------- End Summary For Advance Received (11B) ------------- //

        // ------------- Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:E2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:E4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');

        $spreadsheet->setActiveSheetIndex(7)->setCellValue('A1', 'Summary For Advance Adjusted (11B)');

        $spreadsheet->setActiveSheetIndex(7)->setCellValue('A4', 'Place Of Supply');
        $spreadsheet->setActiveSheetIndex(7)->setCellValue('B4', 'Applicable % of Tax Rate');
        $spreadsheet->setActiveSheetIndex(7)->setCellValue('C4', 'Rate');
        $spreadsheet->setActiveSheetIndex(7)->setCellValue('D4', 'Gross Advance Received');
        $spreadsheet->setActiveSheetIndex(7)->setCellValue('E4', 'Cess Amount');

        $spreadsheet->getActiveSheet()->setTitle('atadj');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        // ------------- Summary For Nil rated, exempted and non GST outward supplies (8)------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:D2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:D4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('A1', 'Summary For Nil rated, exempted and non GST outward supplies (8)');

        $spreadsheet->setActiveSheetIndex(8)->setCellValue('A4', 'Description');
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('A5', 'Inter-State supplies to registered persons');
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('A6', 'Intra-State supplies to registered persons');
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('A7', 'Inter-State supplies to unregistered persons');
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('A8', 'Intra-State supplies to unregistered persons');

        $spreadsheet->setActiveSheetIndex(8)->setCellValue('B4', 'Nil Rated Supplies');
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('C4', 'Exempted (other than nil rated/non GST supply )');
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('D4', 'Non-GST supplies');
      

        $spreadsheet->setActiveSheetIndex(8)->setCellValue('B5' , @$inter_reg_nill['net_amt']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('C5' , $nill['inter_reg']['net_amount']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('D5' , @$inter_reg_na['net_amt']);

        $spreadsheet->setActiveSheetIndex(8)->setCellValue('B6' , @$intera_reg_nill['net_amt']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('C6' , $nill['intera_reg']['net_amount']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('D6' , @$intera_reg_na['net_amt']);

        $spreadsheet->setActiveSheetIndex(8)->setCellValue('B7' , @$inter_unreg_nill['net_amt']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('C7' , $nill['inter_unreg']['net_amount']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('D7' , @$inter_unreg_na['net_amt']);

        $spreadsheet->setActiveSheetIndex(8)->setCellValue('B8' , @$intera_unreg_nill['net_amt']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('C8' , $nill['intera_unreg']['net_amount']);
        $spreadsheet->setActiveSheetIndex(8)->setCellValue('D8' , @$intera_unreg_na['net_amt']);
        

        $spreadsheet->getActiveSheet()->setTitle('exemp');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        // ------------- Summary For CDNUR(9B) ------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:J2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:J4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        

        $spreadsheet->setActiveSheetIndex(9)->setCellValue('A1', 'Summary For HSN(12)');

        $spreadsheet->setActiveSheetIndex(9)->setCellValue('A4', 'HSN');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('B4', 'Description');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('C4', 'UQC');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('D4', 'Total Quantity');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('E4', 'Total Value');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('F4', 'Rate');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('G4', 'Taxable Value');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('H4', 'Integrated Tax Amount');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('I4', 'Central Tax Amount');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('J4', 'State/UT Tax Amount');
        $spreadsheet->setActiveSheetIndex(9)->setCellValue('K4', 'Cess Amount');

        $i = 5;
        
        foreach ($hsn_da['data'] as $row) {

            $spreadsheet->setActiveSheetIndex(9)->setCellValue('A' . $i, $row['hsn_sc']);
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('B' . $i, @$row['desc']);
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('C' . $i, @$row['uqc']);
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('D' . $i, @$row['qty']);
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('E' . $i, number_format(@$row['val'], 2, '.', ''));
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('F' . $i, @$row['rate']);
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('G' . $i, number_format(@$row['txval'], 2, '.', ''));
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('H' . $i, number_format(@$row['iamt'], 2, '.', ''));
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('I' . $i, number_format(@$row['camt'], 2, '.', ''));
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('J' . $i, number_format(@$row['samt'], 2, '.', ''));
            $spreadsheet->setActiveSheetIndex(9)->setCellValue('K' . $i, '');

            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle('hsn');

        // ------------- End Summary For CDNUR(9B) ------------- //

        // ------------- Summary For Nil rated, exempted and non GST outward supplies (8)------------- //

        $spreadsheet->createSheet();

        $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A2:E2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('2F75B5');

        $spreadsheet->getActiveSheet()->getStyle('A4:E4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        

        $spreadsheet->setActiveSheetIndex(10)->setCellValue('A1', 'Summary of documents issued during the tax period (13)');

        $spreadsheet->setActiveSheetIndex(10)->setCellValue('D2', 'Total Number');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('E2', 'Total Cancelled');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('A4', 'Nature of Document');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('B4', 'Sr. No. From');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('C4', 'Sr. No. To');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('D4', 'Total Number');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('E4', 'Cancelled');

        $spreadsheet->setActiveSheetIndex(10)->setCellValue('A5', 'Invoices for outward supply');
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('A6', 'Credit Note');

        $spreadsheet->setActiveSheetIndex(10)->setCellValue('B5', $outward_sale_from);
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('C5', $outward_sale_to);
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('D5', $outward_sale_count + $cancle_sale['sale_count'] + $cancle_gnrlsale['gnrlsale_count']);
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('E5', $cancle_sale['sale_count'] + $cancle_gnrlsale['gnrlsale_count']);

        $spreadsheet->setActiveSheetIndex(10)->setCellValue('B6', $credit_sale_from);
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('C6', $credit_sale_to);
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('D6', $credit_sale_count + $cancle_ret['ret_count'] + $cancle_gnrlret['gnrlret_count']);
        $spreadsheet->setActiveSheetIndex(10)->setCellValue('E6', $cancle_ret['ret_count'] + $cancle_gnrlret['gnrlret_count']);


        $spreadsheet->getActiveSheet()->setTitle('docs');

        // ------------- End Summary For Advance Adjusted (11B) ------------- //

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }

    public function get_hsn_summary($start_date,$end_date)
    {
        $data = get_gstr1_detail(db_date($start_date), db_date($end_date));
        $hsn = $data['hsn']['data'];
        
        $gmodel = new GeneralModel();
        $grand = 0 ;

        foreach ($hsn as $row) {
            if($row['vch_type'] != 'general'){
                $uom = $gmodel->get_data_table('uom',array('code'=>$row['uom']),'*');
                $total = 0;
                $total = (float) $row['qty'] * (float) $row['rate'];
                if($row['item_disc'] > 0 ){
                    $disc_amt = (float)$total * (float)$row['item_disc'] /100;
                    $total -= $disc_amt;
                }    
            }else{
                $total = (float)$row['rate'];
            }
 
            $taxes = json_decode($row['taxes']);
            
            $gst_amt = (float) $total * ((float) $row['igst'] / 100);

            $row['igst_amt'] = 0;
            $row['sgst_amt'] = 0;
            $row['cgst_amt'] = 0;

            if (in_array('igst', $taxes)) {
                $row['igst_amt'] = (float) $gst_amt;
            } else {
                $row['sgst_amt'] = (float) $gst_amt / 2;
                $row['cgst_amt'] = (float) $gst_amt / 2;
            }
            
            $row['total_amt'] = (float) $total + (float) $gst_amt;
            $row['total_taxable'] = $total;
            $row['uom_name'] = $uom['name'];
            
            $hsn_arr[$row['hsn']][$row['igst']][] = $row;
            
            $grand +=$total;
        }
        
        foreach ($hsn_arr as $hsn_rw) {

            foreach ($hsn_rw as $row1) {    
                $i = 1;
                
                $hsn_data = array();
                $hsn_data['txval'] =0;
                $hsn_data['iamt'] = 0;
                $hsn_data['samt'] = 0;
                $hsn_data['camt'] = 0;
                $hsn_data['val'] = 0;
                $hsn_data['qty'] = 0;
                
                foreach ($row1 as $row){

                    $hsn_data['num'] = $i;
                    $hsn_data['hsn_sc'] = $row['hsn'];
                    $hsn_data['rate'] = $row['igst'];
                    $hsn_data['desc'] = $row['item_name'];
                    $hsn_data['uqc'] = @$row['uom'].'-'.@$row['uom_name'];

                    if($row['type'] == 'general' || $row['type'] == 'invoice' ){

                        $hsn_data['qty'] = (@$hsn_data['qty'] ? (float)@$hsn_data['qty'] : 0) + (float) @$row['qty'];
                        $hsn_data['val'] = (@$hsn_data['val'] ? $hsn_data['val'] : 0) + $row['total_amt'];
                        $hsn_data['txval'] = (float)(@$hsn_data['txval'] ? (float) $hsn_data['txval'] : 0) + (float) $row['total_taxable'];
                        $hsn_data['iamt'] = (float)(@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) + (float) $row['igst_amt'];
                        $hsn_data['samt'] = (float)(@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) + (float) $row['sgst_amt'];
                        $hsn_data['camt'] = (float)(@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) + (float) $row['cgst_amt'];

                    }else{

                        $hsn_data['qty'] = (@$hsn_data['qty'] ? (float) $hsn_data['qty'] : 0) - (float) $row['qty'];
                        $hsn_data['val'] = (@$hsn_data['val'] ? $hsn_data['val'] : 0) - $row['total_amt'];
                        $hsn_data['txval'] = (float)(@$hsn_data['txval'] ? (float) $hsn_data['txval'] : 0) - (float) $row['total_taxable'];
                        $hsn_data['iamt'] = (float)(@$hsn_data['iamt'] ? (float) $hsn_data['iamt'] : 0) - (float) $row['igst_amt'];
                        $hsn_data['samt'] = (float)(@$hsn_data['samt'] ? (float) $hsn_data['samt'] : 0) - (float) $row['sgst_amt'];
                        $hsn_data['camt'] = (float)(@$hsn_data['camt'] ? (float) $hsn_data['camt'] : 0) - (float) $row['cgst_amt'];
                    }
                    
                }
                $hsn_da['data'][] = $hsn_data;
            }
        }

        $hsn_sum['data'] = $hsn_da['data'];
        $hsn_sum['start_date'] = $start_date;
        $hsn_sum['end_date'] = $end_date;
        
        return  $hsn_sum;

    }

    public function get_hsn_detail($start_date,$end_date,$hsn,$gst)
    {
        $data = get_gstr1_detail(db_date($start_date), db_date($end_date));
        $hsn_list = $data['hsn']['data'];

        $gmodel = new GeneralModel();

        foreach ($hsn_list as $row) {

            if($row['hsn'] == $hsn && (float)$row['igst'] == (float)$gst)
            {
                $uom = $gmodel->get_data_table('uom',array('code'=>$row['uom']),'*');
                if(isset($row['v_type']))
                {
                    $invoice = $gmodel->get_data_table('sales_ACinvoice',array('id'=>$row['parent_id']),'id,custom_inv_no,party_account');
                    $account = $gmodel->get_data_table('account',array('id'=>$invoice['party_account']),'name');  
                }
                else
                {
                    if($row['type'] == 'return' && !isset($row['v_type'])){
                        $invoice = $gmodel->get_data_table('sales_return',array('id'=>$row['parent_id']),'id,supp_inv as custom_inv_no,account');
                    }else{
                        $invoice = $gmodel->get_data_table('sales_invoice',array('id'=>$row['parent_id']),'id,custom_inv_no,account');
                    }
                    $account = $gmodel->get_data_table('account',array('id'=>$invoice['account']),'name');
                }

                $invoice['account_name'] = $account['name'];
                $row['invoice_detail'] = $invoice;
                $taxes = json_decode($row['taxes']);
                $total = 0;
                $total = (float) $row['qty'] * (float) $row['rate'];
                $gst_amt = (float) $total * ((float) $row['igst'] / 100);

                $row['igst_amt'] = 0;
                $row['sgst_amt'] = 0;
                $row['cgst_amt'] = 0;

                if (in_array('igst', $taxes)) {
                    $row['igst_amt'] = (float) $gst_amt;
                } else {
                    $row['sgst_amt'] = (float) $gst_amt / 2;
                    $row['cgst_amt'] = (float) $gst_amt / 2;
                }
                
                $row['total_amt'] = (float) $total + (float) $gst_amt;
                $row['total_taxable'] = $total;
                $row['uom_name'] = $uom['name'];
                
                $hsn_arr[] = $row;
               
            }
         
        }

       
       return $hsn_arr;
        
    }

    public function get_state_wise_data($from,$to)
    {
        $data = get_gstr1_detail(db_date($from), db_date($to));
       
        $b2c_small = $data['b2cSmall']['data'];
        $cdnur = $data['cr_drUnReg_state']['data'];
        
        $db = $this->db;
        $gmodel = new GeneralModel;
        $comp_state_cdnur = array();


        foreach($cdnur as $row){

            $db = $this->db;

            if($row['gst'] == '' ||  empty($row['gst'])){

                // if($row['acc_state'] == session('state')){
                    if(isset($row['v_type'])){

                        $builder = $db->table('sales_ACparticu');
                        $builder->select('taxability,SUM(amount) as total ,igst,parent_id');
                        $builder->where('is_delete',0);
                        $builder->where('parent_id',$row['id']);
                        $builder->groupBy('igst');
                        $query = $builder->get();
                        $result = $query->getResultArray();              

                    }else{

                        $builder = $db->table('sales_item');
                        $builder->select('taxability,SUM(rate*qty) as total ,igst,parent_id');
                        $builder->where('is_delete',0);
                        $builder->where('type','return');
                        $builder->where('parent_id',$row['id']);
                        $builder->groupBy('igst');
                        $query = $builder->get();
                        $result = $query->getResultArray();
                    }

                    //---- get cdnur company state total taxable gst wise  ---//

                    foreach($result as $row2){
                        if($row2['taxability'] != 'Nill' && $row2['taxability'] != 'Exempt' ){

                            $row['taxable'] = $row2['total'];
                            $row['igst'] = $row2['igst'];
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['voucher'] = $row2['parent_id'];
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['state'] = $row['acc_state'];
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['gst'] = $row2['igst'];
                            $comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] : 0) + $row2['total'];
                        }
                    }
                // }
            }
        } 

        $new_b2b_small = array();

        foreach ($b2c_small as $row) {
        
            if (isset($row['v_type'])) {
                $sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'taxability,igst , amount as total');
            } else {
                $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'taxability,igst,(rate*qty) as total');
            }

            $invtotal = 0;
            foreach($sale as $row1){
                if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt' ){
                    $invtotal = 0;
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['id'] = $row['id'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['acc_state'] = $row['acc_state'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] : 0) + $row1['total'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] : 0) + $row['cess'];
                    $new_b2b_small[$row['acc_state']][$row1['igst']]['gst'] = $row1['igst'];
                }
            }
        }

        //---- cdnur company state taxable minus from b2c small same state data ---//
        
        // foreach($comp_state_cdnur as $row){
        //     $new_b2b_small[$row['state']][$row['gst']]['taxable'] = $new_b2b_small[$row['state']][$row['gst']]['taxable'] - $row['taxable'];
        // }

        foreach($comp_state_cdnur as $state => $value){
            foreach($value as $gst => $row2){

                $new_b2b_small[$state][$gst]['acc_state'] = @$new_b2b_small[$state][$gst]['acc_state'] ?  @$new_b2b_small[$state][$gst]['acc_state'] :  $state ;
                $new_b2b_small[$state][$gst]['taxable'] = (@$new_b2b_small[$state][$gst]['taxable'] ? (float)$new_b2b_small[$state][$gst]['taxable'] : 0) - (@$row2['taxable'] ? (float)$row2['taxable'] : 0);
                $new_b2b_small[$state][$gst]['gst'] = @$new_b2b_small[$state][$gst]['gst'] ? (float)$new_b2b_small[$state][$gst]['gst'] :   @$row2['gst'] ;
                // $new_b2b_small[$state][$gst]['tot_cgst'] = (@$new_b2b_small[$state][$gst]['tot_cgst'] ? (float)$new_b2b_small[$state][$gst]['tot_cgst'] : 0)  - (@$row2['tot_cgst'] ? (float)$row2['tot_cgst'] : 0);
                // $new_b2b_small[$state][$gst]['tot_sgst'] = (@$new_b2b_small[$state][$gst]['tot_sgst'] ? (float)$new_b2b_small[$state][$gst]['tot_sgst'] : 0) - (@$row2['tot_cgst'] ? (float)$row2['tot_sgst'] : 0);
            }
        }

        return $new_b2b_small;

    }

    public function get_state_wise_voucher($post)
    {
        $gmodel = new GeneralModel;

        $state= $gmodel->get_data_table('states',array('id'=>$post['state_code']),'state_code,name');
        if($post['from'] == '') {
            if (date('m') <= '03') {
                $year = date('Y') - 1;
                $post['from'] = $year . '-04-01';
            } else {
                $year = date('Y');
                $post['from'] = $year . '-04-01';
            }
        }
        
        if($post['to'] == '') {
    
            if (date('m') <= '03') {
                $year = date('Y');
            } else {
                $year = date('Y') + 1;
            }
            $post['to'] = $year . '-03-31';
        }
        $db = \Config\Database::connect();
        if (session('DataSource')) {
            $db->setDatabase(session('DataSource'));
        }
    
        $data = get_gstr1_detail(db_date($post['from']), db_date($post['to']));
       
        $b2c_small = $data['b2cSmall']['data'];
        $cdnur = $data['cr_drUnReg_state']['data'];
        
        $db = $this->db;
      
        $comp_state_cdnur = array();
        $new_cdnur = array();

        foreach($cdnur as $row){

            $db = $this->db;
            if($row['acc_state'] == $post['state_code']){

                if(isset($row['v_type'])){
                    $result = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'igst , amount as total');
                }else{
                    $result = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'return'), 'igst,(rate*qty) as total');
                }

                $invtaxable = 0;
                $tot_igst = 0;
                $tot_cgst = 0;
                $tot_sgst = 0;

                foreach($result as $row2){
                   if($post['rate'] == $row2['igst'])
                   {
                        if($row2['igst'] == $post['rate']){

                            $invtaxable +=  (float)$row2['total'];
                            $tot_igst +=  (float)$row2['total'] * (float)$row2['igst'] / 100;
                            $tot_cgst += (float)$tot_igst / 2;
                            $tot_sgst += (float)$tot_igst / 2;
    
                            $row['taxable'] = $invtaxable;
                            $row['tot_igst'] = $tot_igst;
                            $row['tot_cgst'] = $tot_cgst;
                            $row['tot_sgst'] = $tot_sgst;
    
                            if(isset($row['v_type'])){
                                $new_cdnur['gnrl_sale'][$row['id']] = $row;
                            }else{
                                $new_cdnur['sale'][$row['id']] = $row;
                            }
                        }
                   }
                }
            }
        } 

        $cdnur_merge = array_merge(@$new_cdnur['gnrl_sale'] ? $new_cdnur['gnrl_sale'] : array() ,@$new_cdnur['sale'] ? $new_cdnur['sale'] : array());     
        $new_b2b_small = array();

        foreach ($b2c_small as $row) {
            if($row['acc_state'] == $post['state_code'])
            {
               
                if (isset($row['v_type'])) {
                    $sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'igst , amount as total');
                } else {
                    $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'igst,(rate*qty) as total');
                }
                
                $invtaxable = 0;
                $tot_igst = 0;
                $tot_cgst = 0;
                $tot_sgst = 0;
                $invoice_total = 0;
    
                foreach($sale as $row1){

                    if($row1['igst'] == $post['rate']){
                       
                        $invtaxable +=  (float)$row1['total'];
                        $tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                        $tot_cgst += ((float)$row1['total'] * (float)$row1['igst'] / 100) / 2;
                        $tot_sgst += ((float)$row1['total'] * (float)$row1['igst'] / 100) / 2;

                        $row['taxable'] = $invtaxable;
                        $row['tot_igst'] = $tot_igst;
                        $row['tot_cgst'] = $tot_cgst;
                        $row['tot_sgst'] = $tot_sgst;

                        if(isset($row['v_type'])){
                            $new_b2b_small['gnrl_sale'][$row['id']] = $row;
                        }else{
                            $new_b2b_small['sale'][$row['id']] = $row;
                        }
                    }
                
                }
            }
        }

        $b2c_merge = array_merge(@$new_b2b_small['gnrl_sale'] ? $new_b2b_small['gnrl_sale'] : array() ,@$new_b2b_small['sale'] ? $new_b2b_small['sale'] : array());

        $result['state'] = $state;
        $result['new_b2c_small'] = $b2c_merge;
        $result['new_cdnur'] = $cdnur_merge;
        
        return $result;

    }
    
    public function b2b_sales_inv_vouchers_xls_export_data($post)
    {
        //print_r($post);exit;
        $data = get_b2b_b2c_detail($post['from'],$post['to']);
        if($post['type'] == 'sales')
        {
            $sale = $data['sale']['data'];
        }
        else
        {
            $sale = $data['gnrl_sale']['data'];
        }
        
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // $from = $post['from'];
        // $to = $post['to'];
       //echo '<pre>';print_r($data);exit;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A4:I4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        if($post['type'] == 'sales')
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'B2B Sales Invoice Register Report');
        }
        else
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'B2B General Sales Invoice Register Report');
        }
       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SR ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'ACCOUNTS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INTEGRATED TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'CENTRAL TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'STATE TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'CESS AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'INVOICE AMOUNT');

        $i = 5;
        $closing = 0;
        foreach ($sale as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''.number_format(@$row['taxable'],2));
            $taxes = json_decode($row['taxes']);

            if(in_array('igst',$taxes)){
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i,'');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_igst'],2));  
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_cgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_sgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, '');
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));

            $i++;
        }
        if($post['type'] == 'sales')
        {
            $spreadsheet->getActiveSheet()->setTitle('B2B Sales Invoice');
        }
        else
        {
            $spreadsheet->getActiveSheet()->setTitle('B2B General Sales Invoice');
        }
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function b2c_large_xls_export_data($post)
    {
        //print_r($post);exit;
        $data = get_b2b_b2c_detail($post['from'],$post['to']);
        if($post['type'] == 'sales')
        {
            $sale = $data['sales_b2c_large']['data'];
        }
        else
        {
            $sale = $data['gnrl_sale_b2c_large']['data'];
        }
        
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // $from = $post['from'];
        // $to = $post['to'];
       //echo '<pre>';print_r($data);exit;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A4:I4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        if($post['type'] == 'sales')
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'B2C Sales Large Register Report');
        }
        else
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'B2C General Sales Large Register Report');
        }
       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SR ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'ACCOUNTS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INTEGRATED TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'CENTRAL TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'STATE TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'CESS AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'INVOICE AMOUNT');

        $i = 5;
        $closing = 0;
        foreach ($sale as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''.number_format(@$row['taxable'],2));
            $taxes = json_decode($row['taxes']);

            if(in_array('igst',$taxes)){
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i,'');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_igst'],2));  
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_cgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_sgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, '');
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));

            $i++;
        }
        if($post['type'] == 'sales')
        {
            $spreadsheet->getActiveSheet()->setTitle('B2C Sales Large');
        }
        else
        {
            $spreadsheet->getActiveSheet()->setTitle('B2C General Sales Large');
        }
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function b2c_small_xls_export_data($post)
    {
        //print_r($post);exit;
        $data = get_b2b_b2c_detail($post['from'],$post['to']);
        //echo '<pre>';print_r($data);exit;
        if($post['type'] == 'sales')
        {
            $sale = $data['sale_b2c_small']['data'];
        }
        else
        {
            $sale = $data['gnrl_sale_b2c_small']['data'];
        }
        
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // $from = $post['from'];
        // $to = $post['to'];
    

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A4:I4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        if($post['type'] == 'sales')
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'B2C Sales Small Register Report');
        }
        else
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'B2C General Sales Small Register Report');
        }
       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SR ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'ACCOUNTS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INTEGRATED TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'CENTRAL TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'STATE TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'CESS AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'INVOICE AMOUNT');

        $i = 5;
        $closing = 0;
        foreach ($sale as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''.number_format(@$row['taxable'],2));
            $taxes = json_decode($row['taxes']);

            if(in_array('igst',$taxes)){
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i,'');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_igst'],2));  
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_cgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_sgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, '');
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));

            $i++;
        }
        if($post['type'] == 'sales')
        {
            $spreadsheet->getActiveSheet()->setTitle('B2C Sales Small');
        }
        else
        {
            $spreadsheet->getActiveSheet()->setTitle('B2C General Sales Small');
        }
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function cr_dr_invoice_xls_export_data($post)
    {
        //print_r($post);exit;
        $data = get_cr_dr_detail($post['from'],$post['to']);
       
        if($post['type'] == 'sales')
        {
            $sale = $data['sale_return_Reg']['data'];
        }
        else
        {
            $sale = $data['ac_return_Reg']['data'];
        }
        //echo '<pre>';print_r($sale);exit;
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // $from = $post['from'];
        // $to = $post['to'];
    

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A4:I4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        if($post['type'] == 'sales')
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Credit/Debit Register Report');
        }
        else
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Credit/Debit General Register Report');
        }
       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SR ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'ACCOUNTS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INTEGRATED TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'CENTRAL TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'STATE TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'CESS AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'INVOICE AMOUNT');

        $i = 5;
        $closing = 0;
        foreach ($sale as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''.number_format(@$row['taxable'],2));
            $taxes = json_decode($row['taxes']);

            if(in_array('igst',$taxes)){
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i,'');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_igst'],2));  
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_cgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_sgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, '');
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));

            $i++;
        }
        if($post['type'] == 'sales')
        {
            $spreadsheet->getActiveSheet()->setTitle('Cr Dr Reg Invoice');
        }
        else
        {
            $spreadsheet->getActiveSheet()->setTitle('General Cr Dr Reg Invoice');
        }
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function cr_dr_invoice_unreg_xls_export_data($post)
    {
        //print_r($post);exit;
        $data = get_cr_dr_detail($post['from'],$post['to']);
        //echo '<pre>';print_r($data);exit;
        if($post['type'] == 'sales')
        {
            $sale = $data['sale_return_UnReg']['data'];
        }
        else
        {
            $sale = $data['ac_return_UnReg']['data'];
        }
        
        $gmodel = new GeneralModel();

        $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // $from = $post['from'];
        // $to = $post['to'];
    

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A4:I4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
        if($post['type'] == 'sales')
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Credit/Debit UnRegister Report');
        }
        else
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Credit/Debit General UnRegister Report');
        }
       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SR ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'ACCOUNTS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INTEGRATED TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E4', 'CENTRAL TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F4', 'STATE TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G4', 'CESS AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H4', 'TAX AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I4', 'INVOICE AMOUNT');

        $i = 5;
        $closing = 0;
        foreach ($sale as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, isset($row['return_no']) || @$row['v_type']=='return' ? '-' : ''.number_format(@$row['taxable'],2));
            $taxes = json_decode($row['taxes']);

            if(in_array('igst',$taxes)){
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i,'');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_igst'],2));  
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_cgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_sgst'],2));
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, '');
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));

            $i++;
        }
        if($post['type'] == 'sales')
        {
            $spreadsheet->getActiveSheet()->setTitle('Cr Dr UnReg Invoice');
        }
        else
        {
            $spreadsheet->getActiveSheet()->setTitle('General Cr Dr UnReg Invoice');
        }
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function gstr1_nill_xls_export_data($post)
    {
        //print_r($post);exit;
        $data = get_nill_detail($post['from'],$post['to']);
        //echo '<pre>';print_r($data);exit;
        if($post['type'] == 'inter_reg')
        {
            $sale = $data['inter_reg']['data'];
        }elseif($post['type'] == 'intera_reg'){
            $sale = $data['intera_reg']['data'];
        }elseif($post['type'] == 'inter_unreg'){
            $sale = $data['inter_unreg']['data'];
        }else{
            $sale = $data['intera_unreg']['data'];
        }

        
        $gmodel = new GeneralModel();

       // $acc = $gmodel->get_data_table('account', array('id' => @$post['ac_id']), 'id,name');
        // $from = $post['from'];
        // $to = $post['to'];
    

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A4:D4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
       
        if($post['type'] == 'inter_reg')
        {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Nil Rated Invoices Inter Register Report');

        }elseif($post['type'] == 'intera_reg'){
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Nil Rated Invoices Intera Register Report');

        }elseif($post['type'] == 'inter_unreg'){
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Nil Rated Invoices Inter UnRegister Report');

        }else{
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Nil Rated Invoices Intera UnRegister Report');

        }

       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'SR ID');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'PARTICULAR');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'TAXABLE AMOUNT');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D4', 'INVOICE AMOUNT');

        $i = 5;
        $closing = 0;
        foreach ($sale as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no'] ? $row['invoice_no'] : $row['return_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, isset($row['return_no']) || @$row['v_type'] == 'return' ? '-' :''.number_format(@$row['taxable'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, isset($row['return_no']) || @$row['v_type'] == 'return' ? '-' :''.number_format(@$row['net_amount'],2));
            
            $i++;
        }
       
            $spreadsheet->getActiveSheet()->setTitle('Nil Rated Invoices');
       
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    } 
    public function gstr2_b2b_invoices_excel_export_data($post)
    {
        $data = get_gstr2_b2b_b2c_detail(db_date($post['from']),db_date($post['to']));
        //echo '<pre>';print_r($data);exit;
        if($post['type'] == 'purchase')
        {
            $result = $data['purchase_b2b']['data'];
        }else{
            $result = $data['gnrl_purchase_b2b']['data'];
        }
      // echo '<pre>';print_r($result);exit;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A5:I5')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
       
       
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'GSTR-2 B2B Invoice detail Report');

        

       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', @$hsn);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'SI NO.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'Accounts');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'Taxable Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'Integrated Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'Central Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'State Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'Cess Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'Invoice Amount');
      

        $i = 6;
        $closing = 0;

      
        foreach ($result as $row) {
        
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i,  number_format(@$row['taxable'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_cgst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_sgst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));
           
            $i++;
        }
       
            $spreadsheet->getActiveSheet()->setTitle('GSTR-2 B2B Invoices');
       
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    public function gstr2_dr_cr_invoices_excel_export_data($post)
    {
        $result = array();
        $data = get_gstr2_cr_dr_detail(db_date($post['from']),db_date($post['to']));
        //echo '<pre>';print_r($data);exit;
        if($post['type'] == 'purchase')
        {
            $result = $data['purchase_ret_cr_dr_reg'];
        }else{
            $result = $data['gnrl_ret_cr_dr_reg'];
        }
      // echo '<pre>';print_r($result);exit;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getActiveSheet()->getStyle('A5:I5')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('F8CBAD');
       
       
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'GSTR-2 DR-CR Invoice detail Report');

        

       // $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$acc['name']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', @$post['from']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', @$post['to']);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A3', @$hsn);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A5', 'SI NO.');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B5', 'Accounts');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C5', 'Taxable Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D5', 'Integrated Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E5', 'Central Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('F5', 'State Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G5', 'Cess Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H5', 'Tax Amount');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I5', 'Invoice Amount');
      

        $i = 6;
        $closing = 0;

      if(!empty($result['data']))
      {
        foreach ($result['data'] as $row) {
            if($post['type'] == 'purchase')
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['return_no']);
            }
            else
            {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, @$row['invoice_no']);
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, @$row['name']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i,  number_format(@$row['taxable'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format(@$row['tot_cgst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, number_format(@$row['tot_sgst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format(@$row['tot_cess'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format(@$row['tot_igst'],2));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, number_format(@$row['net_amount'],2));
           
            $i++;
        }
      }
            $spreadsheet->getActiveSheet()->setTitle('GSTR-2 DR-CR Invoices');
       
        $spreadsheet->createSheet();

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
  
}
