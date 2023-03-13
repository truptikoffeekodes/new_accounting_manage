<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\GeneralModel;

class ApiModel extends Model
{
    // colorsoul api
    public function ace_insert_edit_sales_invoice($post)
    { 
        $gmodel = new GeneralModel();

        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }

        if (!empty($post['account_name'])) {
            $acc = $gmodel->get_api_data_table($post['database'], 'account', array('name' => $post['account_name']), '*');
            $state = $gmodel->get_api_data_table($post['database'], 'states', array('name' => $post['state_name']), '*');
        }

        if (empty($acc)) {

            $sdata = array(
                'name' => ucwords($post['account_name']),
                'gl_group' => 19,
                'state' => @$state['id'],
                'country' => '101',
                'taxability' => 'Taxable',
                'gst_type' => 'Unregister',
            );

            $db = $this->db;
            $db->setDatabase($post['database']);
            $builder = $db->table('account');
            $builder->insert($sdata);
            $ac_id = $db->insertID();

            $post['account'] = $ac_id;
        } else {
            $post['account'] = $acc['id'];
        }

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("other" => $post['other'], "is_delete" => 0, "is_cancle" => 0));
        $builder->limit(1);
        $result1 = $builder->get();
        $result_array1 = $result1->getRow();

        if (!empty($result_array1)) {
            if ($result_array1->id == $post['id']) {
                $msg = array('st' => 'fail', 'msg' => "Invoice Number Already Exist!!!");
                return $msg;
            } else {
                $db->setDatabase($post['database']);
                $builder = $db->table('sales_item');
                $builder->select('*');
                $builder->where(array("parent_id" => $result_array1->id));
                $builder->where(array("is_delete" => 0));
                $builder->where(array("type" => "invoice"));
                $result = $builder->get();
                $result_item_array = $result->getResultArray();

                if (!empty($result_item_array)) {
                    foreach ($result_item_array as $row) {
                        $post['pid'][] = $row['item_id'];
                        $post['qty'][] = $row['qty'];
                        $post['price'][] = $row['rate'];
                        $post['igst'][] = $row['igst'];
                        $post['cgst'][] = $row['cgst'];
                        $post['sgst'][] = $row['sgst'];
                    }
                }
                $result_array = $result_array1;
                $post['id'] = $result_array1->id;
            }
        }

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;
        $taxability_array = array();
        for ($i = 0; $i < count($pid); $i++) {
            $sub_total = $post['qty'][$i] * $post['price'][$i];

            $igst += ($sub_total) * ($post['igst'][$i] / 100);
            $cgst += ($sub_total) * ($post['cgst'][$i] / 100);
            $sgst += ($sub_total) * ($post['sgst'][$i] / 100);

            $total += $sub_total;

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');

            $taxability_array[] = $item_data['taxability'];
        }
        
        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'sales_invoice');
            $post['invoice_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'sales_invoice', array('id' => $post['id'], 'invoice_no'));
            $post['invoice_no'] = $getchallan['invoice_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }
        $netamount = $total + (float) $igst;

        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
         $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        $time = strtotime(db_date($post['invoice_date']));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $builder_pt_voucher = $db->table('platform_voucher');
        $select = 'MAX(voucher) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0', 'platform_id' => $post['platform'], 'type' => 'invoice'));
        $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();

        $custom_date = $month . $year;
        if (!empty($post['gst_no'])) {
            $custom_gst_code = 'B';
        } else {
            $custom_gst_code = 'C';
        }
        if (!empty($getdata->max_id)) {
            $plateform_data = $gmodel->get_data_table('platform_voucher', array('voucher' => $getdata->max_id), 'custom_inv_no');
            if (!empty($plateform_data)) {
                $string = $plateform_data['custom_inv_no'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int)filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
        }

        if ($post['platform'] == 3) {
            $new_custome_inv_no = $post['platform_prefix'] . '/' . $custom_date . '/' . $custom_gst_code . $s_number;
        } else {
            $new_custome_inv_no = $post['platform_prefix'] . '/' . $custom_date . '/' . $s_number;
        }

        if (!empty($result_array)) {
            $new_custome_inv_no = @$result_array->custom_inv_no;
        }else{
            $new_custome_inv_no = $new_custome_inv_no;
        }

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $gl_id['gl_group'],
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'custom_inv_no' => $new_custome_inv_no,
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'delivery_code' => @$post['delivery_code'],
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vhicle_no' => @$post['vhicle_modeno'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$total,
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }
       
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder = $db->table('sales_invoice');
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $result_jv = $gmodel->update_data_table('jv_management', array('invoice_no' => $post['id'],'type' => "invoice"), array('is_update' => '1'));
       

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        $qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'hsn' => $item_hsn,
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $post['pid'][$i],
                            'hsn' => $item_hsn,
                            'type' => 'invoice',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $post['pid'][$i], "type" => 'invoice'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('sales_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {
                    $builder = $db->table('sales_invoice');
                    $result = $builder->Insert($pdata);
                    $id = $db->insertID();
                    // insert here platform, order id, type, database name in platform_voucher for ACE INTERNATIONAL
                    $platform_data = array(
                        'voucher' => $id,
                        'type' => "invoice",
                        'platform_id' => @$post['platform'],
                        'custom_inv_no' => @$new_custome_inv_no,
                        'invoice_date' => @$post['invoice_date'],
                        'database_name' => @$post['database'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );

                    $platform_builder = $db->table('platform_voucher');
                    $sstatus = $platform_builder->Insert($platform_data);

                    $jv_data = array(
                        'invoice_no' => $id,
                        'type' => "invoice",
                        'platform_id' => @$post['platform'],
                        'invoice_date' => db_date(@$post['invoice_date']),
                        'party_account' => $post['account'],
                        'gst' => $post['gst'],
                        'amount' => round($netamount),
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                    $jv_builder = $db->table('jv_management');
                    $jv_add = $jv_builder->Insert($jv_data);
                

                for ($i = 0; $i < count($pid); $i++) {
                    $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability,hsn');
                    $item_hsn = $item_data['hsn'];
                    $item_taxability = $item_data['taxability'];

                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }

                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $post['pid'][$i],
                        'hsn' => $item_hsn,
                        'type' => 'invoice',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'item_disc' => 0,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }

                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Insert fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        $msg['custom_invoiceNO'] = @$new_custome_inv_no;

        return $msg;
    }
    public function ace_pos_insert_edit_sales_invoice($post)
    {
        //echo '<pre>';Print_r($post);exit;
        
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;
        $taxability_array = array();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];
            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            $taxability_array[] = $item_data['taxability'];
        }

        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $gmodel = new GeneralModel();

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'sales_invoice');
            $post['invoice_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'sales_invoice', array('id' => $post['id'], 'invoice_no'));
            $post['invoice_no'] = $getchallan['invoice_no'];
        }
       // echo '<pre>';Print_r($post);exit;
        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');
            
            
            $post['tds_limit'] = @$getaccount['tds_limit'];
            $post['acc_state'] = @$getaccount['state'];
            $post['gst'] = @$getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;
        //echo '<pre>';Print_r($post['tot_igst']);exit;
        
        if ((float) $post['tot_igst'] > 0) {
            $netamount = $total + (float) $post['tot_igst'];
            $igst = (float) $post['tot_igst'];
            //$cgst = (float) $post['tot_igst'] / 2;
            $cgst = number_format($post['tot_igst'] / 2, 2, '.', '');
            $sgst = number_format($post['tot_igst'] / 2, 2, '.', '');
            //$sgst = (float) $post['tot_igst'] / 2;
        } else {
            $netamount = $total + (float) $post['tot_sgst'] + (float) $post['tot_cgst'];
            $igst = number_format(@$post['tot_sgst'], 2, '.', '') + number_format(@$post['tot_cgst'], 2, '.', '');
            $cgst = (float) @$post['tot_cgst'];
            $sgst = (float) @$post['tot_sgst'];
        }
       // echo '<pre>';Print_r($cgst);exit;
        
        // update trupti 28-11-2022
        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022

        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        $time = strtotime(db_date($post['invoice_date']));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $builder_pt_voucher = $db->table('platform_voucher');
        $select = 'MAX(voucher) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0', 'platform_id' => 1, 'type' => 'invoice','form_type'=>$post['type_foc_normal']));
        $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();
        //return $getdata_pt;

        if (!empty($getdata->max_id)) {
            $plateform_data = $gmodel->get_data_table('platform_voucher', array('voucher' => $getdata->max_id), 'custom_inv_no');
            if (!empty($plateform_data)) {
                $string = $plateform_data['custom_inv_no'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
        }


        if(!empty($result_array)){
            $new_custome_inv_no = $result_array-> custom_inv_no;
        }else{
            if($post['type_foc_normal'] == 'FOC')
            {
                $new_custome_inv_no = 'AI/FOC/' . $month . $year . '/' . $s_number;
            }
            else
            {
                $new_custome_inv_no = 'AI/' . $month . $year . '/' . $s_number;
            }
        }

        $netamount += @$post['round_diff'] ? $post['round_diff'] : 0;
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $gl_id['gl_group'],
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'custom_inv_no' => @$new_custome_inv_no,
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'delivery_code' => @$post['delivery_code'],
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
            'net_amount' => $netamount,
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vhicle_no' => @$post['vhicle_modeno'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $result_jv = $gmodel->update_data_table('jv_management', array('invoice_no' => $post['id'],'type' => "invoice"), array('is_update' => '1'));

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        // $qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_taxability = $item_data['taxability'];
                        $item_hsn = $item_data['hsn'];
                        $sub = $post['qty'][$i] * $post['price'][$i];

                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'hsn' => $item_hsn,
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );

                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability,hsn');
                        $item_taxability = $item_data['taxability'];
                        $item_hsn = $item_data['hsn'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $post['pid'][$i],
                            'hsn' => $item_hsn,
                            'type' => 'invoice',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $post['pid'][$i], "type" => 'invoice'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('sales_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $gmodel = new GeneralModel();

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {

                $result = $builder->Insert($pdata);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {
                    $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability,hsn');

                    $item_taxability = $item_data['taxability'];
                    $item_hsn = $item_data['hsn'];

                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }

                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $post['pid'][$i],
                        'hsn' => $item_hsn,
                        'type' => 'invoice',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'item_disc' => 0,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                
                }
                //echo '<pre>';Print_r($itemdata);exit;
                
                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
                $platform_data = array(
                    'voucher' => $id,
                    'type' => "invoice",
                    'platform_id' => 1,
                    'form_type'=> @$post['type_foc_normal'],
                    'custom_inv_no' => $new_custome_inv_no,
                    'invoice_date' => db_date(@$post['invoice_date']),
                    'database_name' => @$post['database'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 0,
                );
                $platform_builder = $db->table('platform_voucher');
                $sstatus = $platform_builder->Insert($platform_data);

                $jv_data = array(
                    'invoice_no' => $id,
                    'type' => "invoice",
                    'platform_id' => 1,
                    'invoice_date' => db_date(@$post['invoice_date']),
                    'party_account' => $post['account'],
                    'gst' => $post['gst'],
                    'amount' => round($netamount),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 0,
                );
                $jv_builder = $db->table('jv_management');
                $jv_add = $jv_builder->Insert($jv_data);
            }
        }

        $msg['id'] = @$id ? $id : $post['id'];
        $msg['custom_invoiceNO'] = @$new_custome_inv_no;

        return $msg;
    }
    public function ace_insert_edit_sales_return($post)
    {

        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('sales_return');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
       
        $msg = array();

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $taxability_array = array();
        $gmodel = new GeneralModel();

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;
        $taxability_array = array();

        for ($i = 0; $i < count($pid); $i++) {
            $sub_total = $post['qty'][$i] * $post['price'][$i];

            $igst += ($sub_total) * ($post['igst'][$i] / 100);
            $cgst += ($sub_total) * ($post['cgst'][$i] / 100);
            $sgst += ($sub_total) * ($post['sgst'][$i] / 100);

            $total += $sub_total;
            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            $taxability_array[] = $item_data['taxability'];
        }

        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $gmodel = new GeneralModel();

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_return_id($post['database'], 'sales_return');
            $post['return_no'] = $getId + 1;
        } else {
            $getinvoice = $gmodel->get_api_data_table($post['database'], 'sales_return', array('id' => $post['id'], 'return_no'));
            $post['return_no'] = $getinvoice['return_no'];
        }

        if (!empty($post['account_name'])) {

            if($post['type'] == 'salesFrm'){
                $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account_name']), 'tds_limit,state,gst,id');
            }else{
                $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('name' => $post['account_name']), 'tds_limit,state,gst,id');
            }

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
            $post['account'] = $getaccount['id'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        $netamount = $total + (float) $igst;
        // update trupti 28-11-2022
        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022
        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        $time = strtotime(db_date($post['return_date']));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);
        if ($post['platform_prefix'] == 'AI') {
            $plateform_id = 1;
        } else {
            $plateform_id = $post['platform'];
        }

        $builder_pt_voucher = $db->table('platform_voucher');
        $select = 'MAX(voucher) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0', 'platform_id' => $plateform_id, 'type' => 'return'));
        if($post['type'] == 'salesFrm'){
            $builder_pt_voucher->where(array('form_type'=> $post['type_foc_normal']));
        }
        $builder_pt_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();
        $custom_date = $month . $year;
        if (!empty($post['gst_no'])) {
            $custom_gst_code = 'B';
        } else {
            $custom_gst_code = 'C';
        }
        if (!empty($getdata->max_id)) {
            $plateform_data = $gmodel->get_data_table('platform_voucher', array('voucher' => $getdata->max_id), 'custom_inv_no');
            if (!empty($plateform_data)) {
                $string = $plateform_data['custom_inv_no'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int)filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
        }

        //echo '<pre>';Print_r($post['prefix']);exit;

        if ($post['platform'] == 3) {
            $new_supply_inv_no = $post['platform_prefix'] . '/' . $custom_date . '/' . $custom_gst_code . $s_number;
        } else {

            if($post['type'] == 'salesFrm'){
                if($post['type_foc_normal'] == 'FOC')
                {
                    $new_supply_inv_no = 'CN/AI/FOC/' . $month . $year . '/' . $s_number;
                }
                else
                {
                    $new_supply_inv_no = 'CN/AI/' . $month . $year . '/' . $s_number;
                }
               // $new_supply_inv_no = 'CN/AI/' . $custom_date . '/' . $s_number;
                //return $s_number;
            } else {
                $new_supply_inv_no = $post['platform_prefix'] . '/' . $custom_date . '/' . $s_number;
            }
        }
        
        if(!empty($result_array)) {
            $new_supply_inv_no = $result_array->supp_inv;
        }else{
            $new_supply_inv_no = $new_supply_inv_no;
        }

        $netamount += @$post['round_diff'] ? $post['round_diff'] : 0;
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $gl_id['gl_group'],
            'return_date' => db_date($post['return_date']),
            'return_no' => $post['return_no'],
            'supp_inv' => $new_supply_inv_no,
            'invoice' => @$post['invoice'] ? $post['invoice'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'delivery_code' => @$post['delivery_code'],
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
            'net_amount' => $netamount,
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder = $db->table('sales_return');
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $result_jv = $gmodel->update_data_table('jv_management', array('invoice_no' => $post['id'],'type' => "return"), array('is_update' => '1'));


                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'return'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id'], 'type' => "return"))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        // $qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }

                        $item_data = array(
                            'uom' => 'PCS',
                            'hsn' => $item_hsn,
                            'type' => 'return',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );

                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id'], "type" => 'return'));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $post['pid'][$i],
                            'hsn' => $item_hsn,
                            'type' => 'return',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $post['pid'][$i], "type" => 'return'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('sales_return');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $gmodel = new GeneralModel();

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {

                $result = $builder->Insert($pdata);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {
                    $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability,hsn');
                    $item_hsn = $item_data['hsn'];
                    $item_taxability = $item_data['taxability'];

                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }

                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $post['pid'][$i],
                        'hsn' => $item_hsn,
                        'type' => 'return',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'item_disc' => 0,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
                // insert here platform, order id, type, database name in platform_voucher for ACE INTERNATIONAL
                if($post['type'] == 'salesFrm'){
                    $form_type = $post['type_foc_normal'];
                }
                else
                {
                    $form_type = '';
                }
                $platform_data = array(
                    'voucher' => $id,
                    'type' => "return",
                    'platform_id' => !empty(@$post['platform']) ? @$post['platform'] : 1,
                    'form_type'=> @$form_type,
                    'custom_inv_no' => @$new_supply_inv_no,
                    'invoice_date' => @$post['return_date'],
                    'database_name' => @$post['database'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 0,
                );
                $platform_builder = $db->table('platform_voucher');
                $sstatus = $platform_builder->Insert($platform_data);

                $jv_data = array(
                    'invoice_no' => $id,
                    'type' => "return",
                    'platform_id' => !empty(@$post['platform']) ? @$post['platform'] : 1,
                    'invoice_date' => db_date(@$post['return_date']),
                    'party_account' => $post['account'],
                    'gst' => $post['gst'],
                    'amount' => round($netamount),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 0,
                );
                $jv_builder = $db->table('jv_management');
                $jv_add = $jv_builder->Insert($jv_data);
            }
        }

        $msg['id'] = @$id ? $id : $post['id'];
        $msg['custom_invoiceNO'] = @$new_supply_inv_no;

        return $msg;
    }
    public function ace_insert_edit_purchase_invoice($post)
    {

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }
        $gmodel = new GeneralModel();

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        if (!isset($post['pid'])) {
            foreach ($post['hsn'] as $row) {

                $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $row), '*');
                if (!isset($item_id['id'])) {
                    $msg = array('st' => 'failed', 'msg' => $row . ' This Hsn Item Not found in Accounting system..!');
                    return $msg;
                }
                $pid[] = $item_id['id'];
            }
        } else {
            $pid = $post['pid'];
        }
        // echo '<pre>';Print_r($pid);exit;

        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;

        if (isset($post['pid'])) {
            $count = count($post['pid']);
        } else {
            $count = count($post['hsn']);
        }

        // update trupti 28-11-2022
        $taxability_array = array();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            $taxability_array[] = $item_data['taxability'];
        }
        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'purchase_invoice');
            $post['invoice_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'purchase_invoice', array('id' => $post['id'], 'invoice_no'));
            $post['invoice_no'] = $getchallan['invoice_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if ($post['tot_igst'] > 0) {
            $netamount = $total + $post['tot_igst'];
            $igst = $post['tot_igst'];
            $cgst = (float)$post['tot_igst'] / 2;
            $sgst = (float)$post['tot_igst'] / 2;
        } else {
            $netamount = $total + (float)$post['tot_sgst'] + (float)$post['tot_cgst'];
            $igst = (float)@$post['tot_sgst'] + (float)@$post['tot_cgst'];
            $cgst = (float)@$post['tot_cgst'];
            $sgst = (float)@$post['tot_sgst'];
        }
        // update trupti 28-11-2022
        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022
        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' =>  $gl_id['gl_group'],
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'supply_inv' => $post['custom_inv_no'],
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',    
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vehicle' => @$post['vehicle'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'is_import' => isset($post['is_import']) ? $post['is_import'] : 0,
            'import_gst' => isset($post['import_gst']) ? $post['import_gst'] : 0,
            'import_taxable' => isset($post['import_taxable']) ? $post['import_taxable'] : 0,
            'import_nontaxable' => isset($post['import_nontaxable']) ? $post['import_nontaxable'] : 0,
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,

        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {


                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }


        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);


                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        // update trupti 28-11-2022
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        //$item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];
                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'hsn' => $post['hsn'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {

                        if (!isset($post['pid'][$i]) || empty($post['pid'][$i])) {
                            $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][$i]), '*');
                        }
                        // update trupti 28-11-2022
                        $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability');

                        $item_taxability = $item_data['taxability'];
                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => isset($post['pid'][$i]) ? $post['pid'][$i] : $item_id['id'],
                            'hsn' => $post['hsn'][$i],
                            'type' => 'invoice',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'item_disc' => 0,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => isset($post['pid'][$i]) ? $post['pid'][$i] : $item_id['id'], "type" => 'invoice'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('purchase_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();
                for ($i = 0; $i < count($pid); $i++) {
                    if (!isset($post['pid'][$i]) || empty($post['pid'][$i])) {
                        $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][$i]), '*');
                    }
                    $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability');

                    $item_taxability = $item_data['taxability'];
                    // update trupti 28-11-2022
                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }


                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => isset($post['pid'][$i]) ?  $post['pid'][$i] : $item_id['id'],
                        'hsn' => @$post['hsn'][$i] ?  $post['hsn'][$i] : '',
                        'type' => 'invoice',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'item_disc' => 0,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        return $msg;
    }
   
   // ecom api
    public function ecom_mtr_insert_edit_sale_return($post)
    {
        if (!@$post['hsn']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }
        $gmodel = new GeneralModel();
        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('sales_return');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $builder = $db->table('sales_return');
        $builder->select('*');
        $builder->where(array("other" => $post['other'], "is_delete" => 0, "is_cancle" => 0));
        $builder->limit(1);
        $result1 = $builder->get();
        $result_array1 = $result1->getRow();

        if (!empty($result_array1)) {
            if(!empty($post['id']))
            {
                if ($result_array1->id != $post['id']) {
                    $msg = array('st' => 'fail', 'msg' => "Credit note Number Already Exist!!!");
                    return $msg;
                }
            }
            else
            {
                $msg = array('st' => 'fail', 'msg' => "Credit note Number Already Exist!!!");
                return $msg;
            }
        }

        $msg = array();

        foreach ($post['hsn'] as $row) {

            $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $row), '*');
            if (!isset($item_id['id'])) {
                $msg = array('st' => 'failed', 'msg' => $row . ' This Hsn Item Not found in Accounting system..!');
                return $msg;
            }
            $pid[] = $item_id['id'];
        }
        //$pid=$post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;
        $taxability_array = array();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            // echo '<pre>';print_r($item_data);
            $taxability_array[] = $item_data['taxability'];
        }

        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $gmodel = new GeneralModel();

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_return_id($post['database'], 'sales_return');
            $post['return_no'] = $getId + 1;
        } else {
            $getinvoice = $gmodel->get_api_data_table($post['database'], 'sales_return', array('id' => $post['id'], 'return_no'));
            $post['return_no'] = $getinvoice['return_no'];
        }

        if (!empty($post['account_name'])) {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('name' => $post['account_name']), 'tds_limit,state,gst,id');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
            $post['account'] = $getaccount['id'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if ((float) $post['tot_igst'] > 0) {
            $netamount = $total + (float) $post['tot_igst'];
            $igst = (float) $post['tot_igst'];
            $cgst = (float) $post['tot_igst'] / 2;
            $sgst = (float) $post['tot_igst'] / 2;
        } else {
            $netamount = $total + (float) $post['tot_sgst'] + (float) $post['tot_cgst'];
            $igst = (float) @$post['tot_sgst'] + (float) @$post['tot_cgst'];
            $cgst = (float) @$post['tot_cgst'];
            $sgst = (float) @$post['tot_sgst'];
        }
        // update trupti 28-11-2022
        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022
        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        $time = strtotime(db_date($post['return_date']));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $builder_pt_voucher = $db->table('sales_return');
        $select = 'MAX(id) as max_id';
        $builder_pt_voucher->select($select);
        $builder_pt_voucher->where(array('is_delete' => '0'));
        $builder_pt_voucher->where(array('DATE(return_date)  >= ' => $start_date));
        $builder_pt_voucher->where(array('DATE(return_date)  <= ' => $end_date));
        $query = $builder_pt_voucher->get();
        $getdata = $query->getRow();
        $custom_date = $month . $year;
        if (empty($post['gst']) or $post['gst'] == null) {
            $custom_gst_code = 'C';
        } else {
            $custom_gst_code = 'B';
        }
        if (!empty($getdata->max_id)) {
            $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $getdata->max_id), 'supp_inv');
            if (!empty($invoice_data['supp_inv'])) {
                $string = $invoice_data['supp_inv'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
        }

        if (!empty($result_array)) {
            $new_supply_inv_no = @$result_array->supp_inv;
        } else {
            $new_supply_inv_no = 'AC/' . $custom_date . '/' . $custom_gst_code . $s_number;
        }

        $netamount += @$post['round_diff'] ? $post['round_diff'] : 0;
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $gl_id['gl_group'],
            'return_date' => db_date($post['return_date']),
            'return_no' => $post['return_no'],
            'supp_inv' => $new_supply_inv_no,
            'invoice' => @$post['invoice'] ? $post['invoice'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'delivery_code' => @$post['delivery_code'],
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
            'net_amount' => $netamount,
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'return'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id'], 'type' => "return"))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        // $qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'hsn' => $item_hsn,
                            'type' => 'return',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );

                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $pid[$i],
                            'hsn' => $item_hsn,
                            'type' => 'return',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $pid[$i], "type" => 'return'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('sales_return');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $gmodel = new GeneralModel();

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {

                $result = $builder->Insert($pdata);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {
                    $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                    $item_hsn = $item_data['hsn'];
                    $item_taxability = $item_data['taxability'];

                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }

                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $pid[$i],
                        'hsn' => $item_hsn,
                        'type' => 'return',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'item_disc' => 0,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        $msg['custom_invoiceNO'] = @$new_supply_inv_no;
        return $msg;
    }
    public function ecom_mtr_insert_edit_sale_invoice($post)
    {

        $gmodel = new GeneralModel();

        if (!@$post['hsn']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }

        if (!empty($post['account_name'])) {

            $acc = $gmodel->get_api_data_table($post['database'], 'account', array('name' => $post['account_name']), '*');
            // $state = $gmodel->get_api_data_table($post['database'],'states',array('name'=>$post['state_name']),'*');
            $db = $this->db;
            $builder = $db->table('states');
            $builder->select('id');
            $builder->where('name', $post['state_name']);
            $builder->orWhere('short_name', $post['state_name']);
            $query = $builder->get();
            $state = $query->getRowArray();

            if (empty($state)) {
                $msg = array('st' => 'fail', 'msg' => 'There Was No State name found In Accounting With this Name :' . $post['state_name']);
                return $msg;
            }
        }

        if (empty($acc)) {

            $sdata = array(
                'name' => ucwords($post['account_name']),
                'gl_group' => 19,
                'country' => '101',
                'taxability' => 'Taxable',
                'gst_type' => 'Regular',
                'gst' => @$post['gst'] ? $post['gst'] : '',
                'country' => 101,
                'state' => @$state['id'],
            );
            $db = $this->db;
            $db->setDatabase($post['database']);
            $builder = $db->table('account');
            $builder->insert($sdata);
            $ac_id = $db->insertID();

            $post['account'] = $ac_id;

        } else {
            $post['account'] = $acc['id'];
        }

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("other" => $post['other'], "is_delete" => 0, "is_cancle" => 0));
        $builder->limit(1);
        $result1 = $builder->get();
        $result_array1 = $result1->getRow();

        $new_item = array();

        if (!empty($result_array1)) {
            if ($result_array1->id == $post['id']) {
                $msg = array('st' => 'fail', 'msg' => "Invoice Number Already Exist!!!");
                return $msg;
            } else {

                $builder_item = $db->table('sales_item');
                $builder_item->select('*');
                $builder_item->where(array("parent_id" => $result_array1->id));
                $builder_item->where(array("is_delete" => 0));
                $result = $builder_item->get();
                $result_item_array = $result->getResultArray();

                foreach ($result_item_array as $row1) {
                    $new_pid[] = $row1['item_id'];
                }

                if ($post['hsn'][0] == '49011010' && $post['tot_igst'] > 0) {
                    $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0], 'taxability' => 'Taxable'), '*');
                } else {
                    $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0]), '*');
                }

                if (!isset($item_id['id'])) {
                    $msg = array('st' => 'failed', 'msg' => $post['hsn'][0] . 'Hsn Item Not found in Accounting system..!');
                    return $msg;
                }

                if (in_array($item_id['id'], $new_pid)) {
                    foreach ($result_item_array as $row) {
                        $hsn = $gmodel->get_api_data_table($post['database'], 'item', array('id' => $row['item_id']), 'hsn');

                        if ($item_id['id'] == $row['item_id']) {
                            if ((float) $post['igst'][0] != (float) $row['igst']) {
                                $msg = array('st' => 'fail', 'msg' => 'gst value is diffrent');
                                return $msg;
                            } else {
                                $total_qty = $row['qty'] + $post['qty'][0];
                                $total_price = ((float) $row['rate'] * (float) $row['qty']) + ((float) $post['price'][0] * (float) $post['qty'][0]);
                                $row['rate'] = (float) $total_price / (float) $total_qty;
                                $row['qty'] = $total_qty;
                            }
                        } else {
                            $row['qty'] = $row['qty'];
                            $row['rate'] = $row['rate'];
                        }
                        $new_item['pid'][] = $row['item_id'];
                        $new_item['hsn'][] = $hsn['hsn'];
                        $new_item['qty'][] = $row['qty'];
                        $new_item['price'][] = $row['rate'];
                        $new_item['igst'][] = $row['igst'];
                        $new_item['cgst'][] = $row['cgst'];
                        $new_item['sgst'][] = $row['sgst'];
                    }
                } else {
                    if ($post['hsn'][0] == '49011010' && $post['tot_igst'] > 0) {
                        $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0], 'taxability' => 'Taxable'), '*');
                    } else {
                        $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0]), '*');
                    }

                    if (!isset($item_id['id'])) {
                        $msg = array('st' => 'failed', 'msg' => $post['hsn'][0] . ' This Hsn Item Not found in Accounting system..!');
                        return $msg;
                    }

                    if (!empty($result_item_array)) {
                        foreach ($result_item_array as $row) {
                            $new_item['pid'][] = $row['item_id'];
                            $new_item['qty'][] = $row['qty'];
                            $new_item['price'][] = $row['rate'];
                            $new_item['igst'][] = $row['igst'];
                            $new_item['cgst'][] = $row['cgst'];
                            $new_item['sgst'][] = $row['sgst'];
                        }
                    }

                    $new_item['pid'][] = $item_id['id'];
                    $new_item['hsn'][] = $post['hsn'][0];
                    $new_item['qty'][] = $post['qty'][0];
                    $new_item['price'][] = $post['price'][0];
                    $new_item['igst'][] = $post['igst'][0];
                    $new_item['cgst'][] = $post['cgst'][0];
                    $new_item['sgst'][] = $post['sgst'][0];
                }
            }
            $result_array = $result_array1;
            $post['id'] = $result_array1->id;
        } else {
            $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0]), '*');

            if (!isset($item_id['id'])) {
                $msg = array('st' => 'failed', 'msg' => $post['hsn'][0] . ' This Hsn Item Not found in Accounting system..!');
                return $msg;
            }

            $new_item['pid'][] = $item_id['id'];
            $new_item['hsn'][] = $post['hsn'][0];
            $new_item['qty'][] = $post['qty'][0];
            $new_item['price'][] = $post['price'][0];
            $new_item['igst'][] = $post['igst'][0];
            $new_item['cgst'][] = $post['cgst'][0];
            $new_item['sgst'][] = $post['sgst'][0];
        }

        $pid = $new_item['pid'];
        $qty = $new_item['qty'];
        $price = $new_item['price'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;
        $taxability_array = array();

        for ($i = 0; $i < count($pid); $i++) {
            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            // echo '<pre>';print_r($item_data);
            $taxability_array[] = $item_data['taxability'];
            $sub_total = $new_item['qty'][$i] * $new_item['price'][$i];

            $igst += ($sub_total) * ($new_item['igst'][$i] / 100);
            $cgst += ($sub_total) * ($new_item['cgst'][$i] / 100);
            $sgst += ($sub_total) * ($new_item['sgst'][$i] / 100);

            $total += $sub_total;

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');

            $taxability_array[] = $item_data['taxability'];
        }
        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'sales_invoice');
            $post['invoice_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'sales_invoice', array('id' => $post['id'], 'invoice_no'));
            $post['invoice_no'] = $getchallan['invoice_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }
        $netamount = $total + (float) $igst;

        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022
        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        // Generate Custom invoice number and send it to response //

        $time = strtotime(db_date($post['invoice_date']));
        $month = date("m", $time);
        $year = date("y", $time);
        $year1 = date("Y", $time);

        $start = strtotime("{$year1}-{$month}-01");
        $end = strtotime('-1 second', strtotime('+1 month', $start));

        $start_date = date('Y-m-d', $start);
        $end_date = date('Y-m-d', $end);

        $builder_si_voucher = $db->table('sales_invoice');
        $select = 'MAX(id) as max_id';
        $builder_si_voucher->select($select);
        $builder_si_voucher->where(array('is_delete' => '0'));
        if (empty($post['gst']) or $post['gst'] == null) {
            $builder_si_voucher->where(array('gst==' => ''));
        }
        else
        {
            $builder_si_voucher->where(array('gst!=' => ''));
        }
        $builder_si_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
        $builder_si_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
        $query = $builder_si_voucher->get();
        $getdata = $query->getRow();

        $custom_date = $month . $year;

        if (empty($post['gst']) or $post['gst'] == null) {
            $custom_gst_code = 'C';
        } else {
            $custom_gst_code = 'B';
        }

        if (!empty($getdata->max_id)) {
            $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $getdata->max_id), 'custom_inv_no');
            if (!empty($invoice_data['custom_inv_no'])) {
                $string = $invoice_data['custom_inv_no'];
                $outputArr = preg_split("/\//", $string);
                $count = count($outputArr);
                $last_array = $outputArr[$count - 1];
                $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $int_var + 1;
                $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
            } else {
                $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
        }

        if (!empty($result_array)) {
            $new_custome_inv_no = @$result_array->custom_inv_no;
        } else {
            $new_custome_inv_no = 'A' . '/' . $custom_date . '/' . $custom_gst_code . $s_number;
        }

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $gl_id['gl_group'],
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'custom_inv_no' => $new_custome_inv_no,
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'delivery_code' => @$post['delivery_code'],
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vhicle_no' => @$post['vhicle_modeno'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
            'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
            'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
            'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
            'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder = $db->table('sales_invoice');
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        $qty = $new_item['qty'][$i] - $getItem->qty;
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $new_item['qty'][$i] * $new_item['price'][$i];
                        if (!empty($new_item['igst'][$i])) {
                            $item_igst_amt = $sub * $new_item['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'hsn' => $item_hsn,
                            'rate' => $new_item['price'][$i],
                            'qty' => $new_item['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $new_item['igst'][$i] ? $new_item['igst'][$i] : 0,
                            'cgst' => $new_item['cgst'][$i] ? $new_item['cgst'][$i] : 0,
                            'sgst' => $new_item['sgst'][$i] ? $new_item['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        $item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];

                        $sub = $new_item['qty'][$i] * $new_item['price'][$i];
                        if (!empty($new_item['igst'][$i])) {
                            $item_igst_amt = $sub * $new_item['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $pid[$i],
                            'hsn' => $item_hsn,
                            'type' => 'invoice',
                            'uom' => 'PCS',
                            'rate' => $new_item['price'][$i],
                            'qty' => $new_item['qty'][$i],
                            'igst' => $new_item['igst'][$i] ? $new_item['igst'][$i] : 0,
                            'cgst' => $new_item['cgst'][$i] ? $new_item['cgst'][$i] : 0,
                            'sgst' => $new_item['sgst'][$i] ? $new_item['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    // $item_builder->where(array('parent_id' => $post['id'] , 'item_id'=> $pid[$i], "type" => 'invoice'));
                    // $result1=$item_builder->update($item_data);

                }
                $builder = $db->table('sales_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {

              
                $builder = $db->table('sales_invoice');
                $result = $builder->Insert($pdata);
                $id = $db->insertID();
               

                for ($i = 0; $i < count($pid); $i++) {
                    $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                    $item_hsn = $item_data['hsn'];
                    $item_taxability = $item_data['taxability'];

                    $sub = $new_item['qty'][$i] * $new_item['price'][$i];
                    if (!empty($new_item['igst'][$i])) {
                        $item_igst_amt = $sub * $new_item['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }
                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $pid[$i],
                        'hsn' => $item_hsn,
                        'type' => 'invoice',
                        'uom' => 'PCS',
                        'rate' => $new_item['price'][$i],
                        'qty' => $new_item['qty'][$i],
                        'igst' => $new_item['igst'][$i] ? $new_item['igst'][$i] : 0,
                        'cgst' => @$new_item['cgst'][$i] ? $new_item['cgst'][$i] : 0,
                        'sgst' => @$new_item['sgst'][$i] ? $new_item['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'sub_total' => $sub,
                        'total' => $sub,
                        'item_disc' => 0,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Insert fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        $msg['custom_invoiceNO'] = @$new_custome_inv_no;

        return $msg;
    }
    public function ecom_mtr_insert_edit_purchase_invoice($post)
    {
       
        //echo '<pre>';Print_r($post);exit;
        
        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }
        $gmodel = new GeneralModel();

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();
        $pid = array();
        if (!isset($post['pid'])) {
            foreach ($post['hsn'] as $row) {

                $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $row), '*');
                
               // echo '<pre>';Print_r($item_id['id']);
                if (!isset($item_id['id'])) {
                    $msg = array('st' => 'failed', 'msg' => $row . ' This Hsn Item Not found in Accounting system..!');
                    return $msg;
                }
                $pid[] = $item_id['id'];
            }
            //exit;
        } else {
            $pid = $post['pid'];
        }
        //echo '<pre>';Print_r($pid);exit;

        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;

        if (isset($post['pid'])) {
            $count = count($post['pid']);
        } else {
            $count = count($post['hsn']);
        }

        // update trupti 28-11-2022
        $taxability_array = array();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            $taxability_array[] = $item_data['taxability'];
        }
        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'purchase_invoice');
            $post['invoice_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'purchase_invoice', array('id' => $post['id'], 'invoice_no'));
            $post['invoice_no'] = $getchallan['invoice_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if ($post['tot_igst'] > 0) {
            $netamount = $total + $post['tot_igst'];
            $igst = $post['tot_igst'];
            $cgst = (float)$post['tot_igst'] / 2;
            $sgst = (float)$post['tot_igst'] / 2;
        } else {
            $netamount = $total + (float)$post['tot_sgst'] + (float)$post['tot_cgst'];
            $igst = (float)@$post['tot_sgst'] + (float)@$post['tot_cgst'];
            $cgst = (float)@$post['tot_cgst'];
            $sgst = (float)@$post['tot_sgst'];
        }
        // update trupti 28-11-2022
        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022
        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' =>  $gl_id['gl_group'],
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'supply_inv' => $post['custom_inv_no'],
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',    
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vehicle' => @$post['vehicle'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'is_import' => isset($post['is_import']) ? $post['is_import'] : 0,
            'import_gst' => isset($post['import_gst']) ? $post['import_gst'] : 0,
            'import_taxable' => isset($post['import_taxable']) ? $post['import_taxable'] : 0,
            'import_nontaxable' => isset($post['import_nontaxable']) ? $post['import_nontaxable'] : 0,
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,

        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {


                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }


        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);


                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        // update trupti 28-11-2022
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        //$item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];
                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'hsn' => $post['hsn'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {

                        if (!isset($post['pid'][$i]) || empty($post['pid'][$i])) {
                            $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][$i]), '*');
                        }
                        // update trupti 28-11-2022
                        $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability');

                        $item_taxability = $item_data['taxability'];
                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => isset($post['pid'][$i]) ? $post['pid'][$i] : $item_id['id'],
                            'hsn' => $post['hsn'][$i],
                            'type' => 'invoice',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'item_disc' => 0,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => isset($post['pid'][$i]) ? $post['pid'][$i] : $item_id['id'], "type" => 'invoice'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('purchase_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();
                for ($i = 0; $i < count($pid); $i++) {
                    if (!isset($post['pid'][$i]) || empty($post['pid'][$i])) {
                        $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][$i]), '*');
                    }
                    $item_data = $gmodel->get_data_table('item', array('id' => $item_id['id']), 'id,taxability');

                    $item_taxability = $item_data['taxability'];
                    // update trupti 28-11-2022
                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }


                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => isset($post['pid'][$i]) ?  $post['pid'][$i] : $item_id['id'],
                        'hsn' => @$post['hsn'][$i] ?  $post['hsn'][$i] : '',
                        'type' => 'invoice',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'item_disc' => 0,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        return $msg;
    }
    public function ecom_mtr_insert_edit_custom_jv($post)
    {
        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('jv_main');
        $builder->select('*');
        $builder->where(array("id" => @$post['jv_id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pdata = array(
            'date' => db_date($post['date']),
            'narration' => $post['narration'],
        );
        $gmodel = new GeneralModel;

        foreach ($post['custom'] as $key => $value) {

            if ($key == 3) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 3%'), 'id');
            } else if ($key == 5) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 5%'), 'id');
            } else if ($key == 12) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 12%'), 'id');
            } else if ($key == 18) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 18%'), 'id');
            } else  if ($key == 28) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 28%'), 'id');
            } else {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Non Taxable'), 'id');
            }
            $post['dr_cr'][] = 'dr';
            $post['amount'][] = $value;
            if (isset($particular['id'])) {
                $post['particular'][] = $particular['id'];
            } else {
                $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this name: Custom Duty Charge @ ' . $key . '%');
                return $msg;
            }
        }

        foreach ($post['gst'] as $key => $value) {

            if ($key == 3) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 3%'), 'id');
            } else if ($key == 5) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 5%'), 'id');
            } else if ($key == 12) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 12%'), 'id');
            } else if ($key == 18) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 18%'), 'id');
            } else  if ($key == 28) {
                $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 28%'), 'id');
            } else {
            }

            $post['dr_cr'][] = 'dr';
            $post['amount'][] = $value;
            if (isset($particular['id'])) {
                $post['particular'][] = $particular['id'];
            } else {
                $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this name: Import IGST ' . $key . '%');
                return $msg;
            }
        }

        $cr_particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Duty Charge'), 'id');
        if (isset($cr_particular['id'])) {
            $post['dr_cr'][] = 'cr';
            $post['amount'][] = $post['cr_amt'];
            $post['particular'][] = $cr_particular['id'];
        } else {
            $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this name: Duty Charge');
            return $msg;
        }

        if (!empty($result_array)) {

            for ($i = 0; $i < count($post['dr_cr']); $i++) {
                // if (in_array($post['item_id'][$i], $old_item)) {
                $data = array(
                    'jv_id' => $post['jv_id'],
                    'date' => db_date($post['date']),
                    'dr_cr' => $post['dr_cr'][$i],
                    'particular' => $post['particular'][$i],
                    'method' => 'on_account',
                    'amount' => $post['amount'][$i],
                );
               
                $builder = $db->table('jv_particular');
                $builder->where(array("jv_id" => $post['jv_id'], "particular" => $post['particular'][$i]));
                $result = $builder->Update($data);
            }
           
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder = $db->table('jv_main');
                $builder->where(array("id" => $post['jv_id']));
                $result = $builder->Update($pdata);

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!", 'id' => $post['jv_id']);
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            $result = $builder->Insert($pdata);

            $id = $db->insertID();
            // $j = 0;
            // $k = 0;
            for ($i = 0; $i < count($post['dr_cr']); $i++) {
                $data = array(
                    'jv_id' => $id,
                    'date' => db_date($post['date']),
                    'dr_cr' => $post['dr_cr'][$i],
                    'particular' => $post['particular'][$i],
                    'method' => 'on_account',
                    'amount' => $post['amount'][$i],
                    'other' => @$post['other'][$i] ? $post['other'][$i] : '',
                    'stat_adj' => @$post['stat_adj'] ? $post['stat_adj'] : 0,
                );
             
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = 0;
                $builder = $db->table('jv_particular');
                $result1 = $builder->Insert($data);
            }

            if ($result and $result1) {
                $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!", 'id' => $id);
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }
        }
        return $msg;
    }

     // klamp ace api
     public function klamp_ace_insert_edit_sale_invoice($post)
     {
 
         $gmodel = new GeneralModel();
 
         if (!@$post['hsn']) {
             $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
             return $msg;
         }
 
         if ($post['database'] == '') {
             $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
             return $msg;
         }
 
         if (!empty($post['account_name'])) {
 
             $acc = $gmodel->get_api_data_table($post['database'], 'account', array('name' => $post['account_name']), '*');
             // $state = $gmodel->get_api_data_table($post['database'],'states',array('name'=>$post['state_name']),'*');
             $db = $this->db;
             $builder = $db->table('states');
             $builder->select('id');
             $builder->where('name', $post['state_name']);
             $builder->orWhere('short_name', $post['state_name']);
             $query = $builder->get();
             $state = $query->getRowArray();
 
             if (empty($state)) {
                 $msg = array('st' => 'fail', 'msg' => 'There Was No State name found In Accounting With this Name :' . $post['state_name']);
                 return $msg;
             }
         }
 
         if (empty($acc)) {
 
             $sdata = array(
                 'name' => ucwords($post['account_name']),
                 'gl_group' => 19,
                 'country' => '101',
                 'taxability' => 'Taxable',
                 'gst_type' => 'Regular',
                 'gst' => @$post['gst'] ? $post['gst'] : '',
                 'country' => 101,
                 'state' => @$state['id'],
             );
             $db = $this->db;
             $db->setDatabase($post['database']);
             $builder = $db->table('account');
             $builder->insert($sdata);
             $ac_id = $db->insertID();
 
             $post['account'] = $ac_id;
 
         } else {
             $post['account'] = $acc['id'];
         }
 
         $db = $this->db;
         $db->setDatabase($post['database']);
         $builder = $db->table('sales_invoice');
         $builder->select('*');
         $builder->where(array("id" => $post['id']));
         $builder->limit(1);
         $result = $builder->get();
         $result_array = $result->getRow();
 
         $msg = array();
 
         $builder = $db->table('sales_invoice');
         $builder->select('*');
         $builder->where(array("other" => $post['other'], "is_delete" => 0, "is_cancle" => 0));
         $builder->limit(1);
         $result1 = $builder->get();
         $result_array1 = $result1->getRow();
 
         $new_item = array();
 
         if (!empty($result_array1)) {
             if ($result_array1->id == $post['id']) {
                 $msg = array('st' => 'fail', 'msg' => "Invoice Number Already Exist!!!");
                 return $msg;
             } else {
 
                 $builder_item = $db->table('sales_item');
                 $builder_item->select('*');
                 $builder_item->where(array("parent_id" => $result_array1->id));
                 $builder_item->where(array("is_delete" => 0));
                 $result = $builder_item->get();
                 $result_item_array = $result->getResultArray();
 
                 foreach ($result_item_array as $row1) {
                     $new_pid[] = $row1['item_id'];
                 }
 
                 if ($post['hsn'][0] == '49011010' && $post['tot_igst'] > 0) {
                     $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0], 'taxability' => 'Taxable'), '*');
                 } else {
                     $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0]), '*');
                 }
 
                 if (!isset($item_id['id'])) {
                     $msg = array('st' => 'failed', 'msg' => $post['hsn'][0] . 'Hsn Item Not found in Accounting system..!');
                     return $msg;
                 }
 
                 if (in_array($item_id['id'], $new_pid)) {
                     foreach ($result_item_array as $row) {
                         $hsn = $gmodel->get_api_data_table($post['database'], 'item', array('id' => $row['item_id']), 'hsn');
 
                         if ($item_id['id'] == $row['item_id']) {
                             if ((float) $post['igst'][0] != (float) $row['igst']) {
                                 $msg = array('st' => 'fail', 'msg' => 'gst value is diffrent');
                                 return $msg;
                             } else {
                                 $total_qty = $row['qty'] + $post['qty'][0];
                                 $total_price = ((float) $row['rate'] * (float) $row['qty']) + ((float) $post['price'][0] * (float) $post['qty'][0]);
                                 $row['rate'] = (float) $total_price / (float) $total_qty;
                                 $row['qty'] = $total_qty;
                             }
                         } else {
                             $row['qty'] = $row['qty'];
                             $row['rate'] = $row['rate'];
                         }
                         $new_item['pid'][] = $row['item_id'];
                         $new_item['hsn'][] = $hsn['hsn'];
                         $new_item['qty'][] = $row['qty'];
                         $new_item['price'][] = $row['rate'];
                         $new_item['igst'][] = $row['igst'];
                         $new_item['cgst'][] = $row['cgst'];
                         $new_item['sgst'][] = $row['sgst'];
                     }
                 } else {
                     if ($post['hsn'][0] == '49011010' && $post['tot_igst'] > 0) {
                         $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0], 'taxability' => 'Taxable'), '*');
                     } else {
                         $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0]), '*');
                     }
 
                     if (!isset($item_id['id'])) {
                         $msg = array('st' => 'failed', 'msg' => $post['hsn'][0] . ' This Hsn Item Not found in Accounting system..!');
                         return $msg;
                     }
 
                     if (!empty($result_item_array)) {
                         foreach ($result_item_array as $row) {
                             $new_item['pid'][] = $row['item_id'];
                             $new_item['qty'][] = $row['qty'];
                             $new_item['price'][] = $row['rate'];
                             $new_item['igst'][] = $row['igst'];
                             $new_item['cgst'][] = $row['cgst'];
                             $new_item['sgst'][] = $row['sgst'];
                         }
                     }
 
                     $new_item['pid'][] = $item_id['id'];
                     $new_item['hsn'][] = $post['hsn'][0];
                     $new_item['qty'][] = $post['qty'][0];
                     $new_item['price'][] = $post['price'][0];
                     $new_item['igst'][] = $post['igst'][0];
                     $new_item['cgst'][] = $post['cgst'][0];
                     $new_item['sgst'][] = $post['sgst'][0];
                 }
             }
             $result_array = $result_array1;
             $post['id'] = $result_array1->id;
         } else {
             $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][0]), '*');
 
             if (!isset($item_id['id'])) {
                 $msg = array('st' => 'failed', 'msg' => $post['hsn'][0] . ' This Hsn Item Not found in Accounting system..!');
                 return $msg;
             }
 
             $new_item['pid'][] = $item_id['id'];
             $new_item['hsn'][] = $post['hsn'][0];
             $new_item['qty'][] = $post['qty'][0];
             $new_item['price'][] = $post['price'][0];
             $new_item['igst'][] = $post['igst'][0];
             $new_item['cgst'][] = $post['cgst'][0];
             $new_item['sgst'][] = $post['sgst'][0];
         }
 
         $pid = $new_item['pid'];
         $qty = $new_item['qty'];
         $price = $new_item['price'];
 
         $igst = 0;
         $cgst = 0;
         $sgst = 0;
 
         $discount = @$post['discount'] ? $post['discount'] : '0';
         $total = 0.0;
         $taxability_array = array();
 
         for ($i = 0; $i < count($pid); $i++) {
             $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
             // echo '<pre>';print_r($item_data);
             $taxability_array[] = $item_data['taxability'];
             $sub_total = $new_item['qty'][$i] * $new_item['price'][$i];
 
             $igst += ($sub_total) * ($new_item['igst'][$i] / 100);
             $cgst += ($sub_total) * ($new_item['cgst'][$i] / 100);
             $sgst += ($sub_total) * ($new_item['sgst'][$i] / 100);
 
             $total += $sub_total;
 
             $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
 
             $taxability_array[] = $item_data['taxability'];
         }
         $post['taxable'] = $total;
 
         if (isset($post['lr_date'])) {
             $lr_dt = date_create($post['lr_date']);
             $lr_date = date_format($lr_dt, 'Y-m-d');
         } else {
             $lr_date = '';
         }
 
         if (empty($post['id'])) {
             $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'sales_invoice');
             $post['invoice_no'] = $getId + 1;
         } else {
             $getchallan = $gmodel->get_api_data_table($post['database'], 'sales_invoice', array('id' => $post['id'], 'invoice_no'));
             $post['invoice_no'] = $getchallan['invoice_no'];
         }
 
         if ($post['account'] != '') {
             $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');
 
             $post['tds_limit'] = $getaccount['tds_limit'];
             $post['acc_state'] = $getaccount['state'];
             $post['gst'] = $getaccount['gst'];
         } else {
             $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
             return $msg;
         }
         $netamount = $total + (float) $igst;
 
         $taxes_array = @$post['taxes'];
         if (in_array("igst", $taxes_array)) {
             $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
             $igst_acc = $igst_acc_id['id'];
             $cgst_acc = '';
             $sgst_acc = '';
         } else {
             $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
             $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
             $igst_acc = '';
             $cgst_acc = $cgst_acc_id['id'];
             $sgst_acc = $sgst_acc_id['id'];
         }
         // update trupti 28-11-2022
         $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');
 
         // Generate Custom invoice number and send it to response //
 
         $time = strtotime(db_date($post['invoice_date']));
         $month = date("m", $time);
         $year = date("y", $time);
         $year1 = date("Y", $time);
 
         $start = strtotime("{$year1}-{$month}-01");
         $end = strtotime('-1 second', strtotime('+1 month', $start));
 
         $start_date = date('Y-m-d', $start);
         $end_date = date('Y-m-d', $end);
 
         $builder_si_voucher = $db->table('sales_invoice');
         $select = 'MAX(id) as max_id';
         $builder_si_voucher->select($select);
         $builder_si_voucher->where(array('is_delete' => '0'));
         if (empty($post['gst']) or $post['gst'] == null) {
             $builder_si_voucher->where(array('gst==' => ''));
         }
         else
         {
             $builder_si_voucher->where(array('gst!=' => ''));
         }
         $builder_si_voucher->where(array('DATE(invoice_date)  >= ' => $start_date));
         $builder_si_voucher->where(array('DATE(invoice_date)  <= ' => $end_date));
         $query = $builder_si_voucher->get();
         $getdata = $query->getRow();
 
         $custom_date = $month . $year;
 
         if (empty($post['gst']) or $post['gst'] == null) {
             $custom_gst_code = 'C';
         } else {
             $custom_gst_code = 'B';
         }
 
         if (!empty($getdata->max_id)) {
             $invoice_data = $gmodel->get_data_table('sales_invoice', array('id' => $getdata->max_id), 'custom_inv_no');
             if (!empty($invoice_data['custom_inv_no'])) {
                 $string = $invoice_data['custom_inv_no'];
                 $outputArr = preg_split("/\//", $string);
                 $count = count($outputArr);
                 $last_array = $outputArr[$count - 1];
                 $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                 $new_num = $int_var + 1;
                 $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
             } else {
                 $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
             }
         } else {
             $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
         }
 
         if (!empty($result_array)) {
             $new_custome_inv_no = @$result_array->custom_inv_no;
         } else {
             $new_custome_inv_no = 'A' . '/' . $custom_date . '/' . $custom_gst_code . $s_number;
         }
 
         $pdata = array(
             'voucher_type' => $post['voucher_type'],
             'gl_group' => $gl_id['gl_group'],
             'invoice_date' => db_date($post['invoice_date']),
             'invoice_no' => $post['invoice_no'],
             'custom_inv_no' => $new_custome_inv_no,
             'challan_no' => @$post['challan'] ? $post['challan'] : '',
             'account' => $post['account'],
             'tds_limit' => $post['tds_limit'],
             'acc_state' => $post['acc_state'],
             'gst' => $post['gst'],
             'broker' => @$post['broker'],
             'other' => @$post['other'] ? $post['other'] : '',
             'lr_no' => @$post['lrno'],
             'lr_date' => @$lr_date,
             'delivery_code' => @$post['delivery_code'],
             'transport' => @$post['transport'],
             'taxes' => json_encode(@$post['taxes']),
             'tot_igst' => @$igst,
             'tot_cgst' => @$cgst,
             'tot_sgst' => @$sgst,
             'total_amount' => $total,
             'discount' => @$discount ? $discount : '',
             'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
             'net_amount' => round($netamount),
             'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
             'vhicle_no' => @$post['vhicle_modeno'],
             'round' => @$post['round'],
             'round_diff' => @$post['round_diff'],
             'taxable' => @$post['taxable'],
             'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
             'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
             'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
             'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
             'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
             'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
             'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
             'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
             'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
             'igst_acc' => @$igst_acc,
             'cgst_acc' => @$cgst_acc,
             'sgst_acc' => @$sgst_acc,
         );
         // update trupti 28-11-2022
         if ($post['gst'] != '') {
             if (in_array('Taxable', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Taxable';
             } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Exempt';
             } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Nill';
             } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Exempt';
             } else {
                 $pdata['inv_taxability'] = 'N/A';
             }
         } else {
             if (in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Exempt';
             } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Taxable';
             } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Nill';
             } else {
                 $pdata['inv_taxability'] = 'N/A';
             }
         }
 
         if (!empty($result_array)) {
 
             $pdata['update_at'] = date('Y-m-d H:i:s');
             $pdata['update_by'] = 0;
 
             if (empty($msg)) {
                 $builder = $db->table('sales_invoice');
                 $builder->where(array("id" => $post['id']));
                 $result = $builder->Update($pdata);
 
                 $item_builder = $db->table('sales_item');
                 $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                 $getItem = $item_result->getRow();
 
                 $getpid = explode(',', $getItem->item_id);
                 $delete_itemid = array_diff($getpid, $pid);
 
                 if (!empty($delete_itemid)) {
                     foreach ($delete_itemid as $key => $del_id) {
                         $del_data = array('is_delete' => '1');
                         $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                         $item_builder->update($del_data);
                     }
                 }
 
                 for ($i = 0; $i < count($pid); $i++) {
                     $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                     $getItem = $item_result->getRow();
 
                     if (!empty($getItem)) {
                         $qty = $new_item['qty'][$i] - $getItem->qty;
                         $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                         $item_hsn = $item_data['hsn'];
                         $item_taxability = $item_data['taxability'];
 
                         $sub = $new_item['qty'][$i] * $new_item['price'][$i];
                         if (!empty($new_item['igst'][$i])) {
                             $item_igst_amt = $sub * $new_item['igst'][$i] / 100;
                             $item_cgst_amt = $item_igst_amt / 2;
                             $item_sgst_amt = $item_igst_amt / 2;
                         } else {
                             $item_igst_amt = 0;
                             $item_cgst_amt = 0;
                             $item_sgst_amt = 0;
                         }
                         $item_data = array(
                             'uom' => 'PCS',
                             'hsn' => $item_hsn,
                             'rate' => $new_item['price'][$i],
                             'qty' => $new_item['qty'][$i],
                             'item_disc' => 0,
                             'igst' => $new_item['igst'][$i] ? $new_item['igst'][$i] : 0,
                             'cgst' => $new_item['cgst'][$i] ? $new_item['cgst'][$i] : 0,
                             'sgst' => $new_item['sgst'][$i] ? $new_item['sgst'][$i] : 0,
                             'igst_amt' => $item_igst_amt,
                             'cgst_amt' => $item_cgst_amt,
                             'sgst_amt' => $item_sgst_amt,
                             'taxability' => $item_taxability,
                             'total' => $sub,
                             'sub_total' => $sub,
                             'remark' => '',
                             'is_delete' => 0,
                             'update_at' => date('Y-m-d H:i:s'),
                             'update_by' => 0,
                         );
                         $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                         $res = $item_builder->update($item_data);
                     } else {
                         $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                         $item_hsn = $item_data['hsn'];
                         $item_taxability = $item_data['taxability'];
 
                         $sub = $new_item['qty'][$i] * $new_item['price'][$i];
                         if (!empty($new_item['igst'][$i])) {
                             $item_igst_amt = $sub * $new_item['igst'][$i] / 100;
                             $item_cgst_amt = $item_igst_amt / 2;
                             $item_sgst_amt = $item_igst_amt / 2;
                         } else {
                             $item_igst_amt = 0;
                             $item_cgst_amt = 0;
                             $item_sgst_amt = 0;
                         }
                         $item_data = array(
                             'parent_id' => $post['id'],
                             'item_id' => $pid[$i],
                             'hsn' => $item_hsn,
                             'type' => 'invoice',
                             'uom' => 'PCS',
                             'rate' => $new_item['price'][$i],
                             'qty' => $new_item['qty'][$i],
                             'igst' => $new_item['igst'][$i] ? $new_item['igst'][$i] : 0,
                             'cgst' => $new_item['cgst'][$i] ? $new_item['cgst'][$i] : 0,
                             'sgst' => $new_item['sgst'][$i] ? $new_item['sgst'][$i] : 0,
                             'igst_amt' => $item_igst_amt,
                             'cgst_amt' => $item_cgst_amt,
                             'sgst_amt' => $item_sgst_amt,
                             'taxability' => $item_taxability,
                             'total' => $sub,
                             'sub_total' => $sub,
                             'item_disc' => 0,
                             'remark' => '',
                             'created_at' => date('Y-m-d H:i:s'),
                             'created_by' => 0,
                         );
                         $res = $item_builder->insert($item_data);
                     }
                     // $item_builder->where(array('parent_id' => $post['id'] , 'item_id'=> $pid[$i], "type" => 'invoice'));
                     // $result1=$item_builder->update($item_data);
 
                 }
                 $builder = $db->table('sales_invoice');
 
                 if ($result) {
                     $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                 } else {
                     $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                 }
             }
         } else {
 
             $pdata['created_at'] = date('Y-m-d H:i:s');
             $pdata['created_by'] = 0;
 
             if (empty($msg)) {
 
               
                 $builder = $db->table('sales_invoice');
                 $result = $builder->Insert($pdata);
                 $id = $db->insertID();
                
 
                 for ($i = 0; $i < count($pid); $i++) {
                     $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                     $item_hsn = $item_data['hsn'];
                     $item_taxability = $item_data['taxability'];
 
                     $sub = $new_item['qty'][$i] * $new_item['price'][$i];
                     if (!empty($new_item['igst'][$i])) {
                         $item_igst_amt = $sub * $new_item['igst'][$i] / 100;
                         $item_cgst_amt = $item_igst_amt / 2;
                         $item_sgst_amt = $item_igst_amt / 2;
                     } else {
                         $item_igst_amt = 0;
                         $item_cgst_amt = 0;
                         $item_sgst_amt = 0;
                     }
                     $itemdata[] = array(
                         'parent_id' => $id,
                         'item_id' => $pid[$i],
                         'hsn' => $item_hsn,
                         'type' => 'invoice',
                         'uom' => 'PCS',
                         'rate' => $new_item['price'][$i],
                         'qty' => $new_item['qty'][$i],
                         'igst' => $new_item['igst'][$i] ? $new_item['igst'][$i] : 0,
                         'cgst' => @$new_item['cgst'][$i] ? $new_item['cgst'][$i] : 0,
                         'sgst' => @$new_item['sgst'][$i] ? $new_item['sgst'][$i] : 0,
                         'igst_amt' => $item_igst_amt,
                         'cgst_amt' => $item_cgst_amt,
                         'sgst_amt' => $item_sgst_amt,
                         'taxability' => $item_taxability,
                         'sub_total' => $sub,
                         'total' => $sub,
                         'item_disc' => 0,
                         'remark' => '',
                         'created_at' => date('Y-m-d H:i:s'),
                         'created_by' => 0,
                     );
                 }
                 $item_builder = $db->table('sales_item');
                 $result1 = $item_builder->insertBatch($itemdata);
 
                 if ($result && $result1) {
                     $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                 } else {
                     $msg = array('st' => 'fail', 'msg' => "Your Details Insert fail");
                 }
             }
         }
         $msg['id'] = @$id ? $id : $post['id'];
         $msg['custom_invoiceNO'] = @$new_custome_inv_no;
 
         return $msg;
     }
     public function klamp_ace_insert_edit_sale_return($post)
     {
         if (!@$post['hsn']) {
             $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
             return $msg;
         }
 
         if ($post['database'] == '') {
             $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
             return $msg;
         }
         $gmodel = new GeneralModel();
         $db = $this->db;
         $db->setDatabase($post['database']);
         $builder = $db->table('sales_return');
         $builder->select('*');
         $builder->where(array("id" => $post['id']));
         $builder->limit(1);
         $result = $builder->get();
         $result_array = $result->getRow();
 
         $builder = $db->table('sales_return');
         $builder->select('*');
         $builder->where(array("other" => $post['other'], "is_delete" => 0, "is_cancle" => 0));
         $builder->limit(1);
         $result1 = $builder->get();
         $result_array1 = $result1->getRow();
 
         if (!empty($result_array1)) {
             if (!empty($post['id']) && $result_array1->id != $post['id']) {
                 $msg = array('st' => 'fail', 'msg' => "Credit note Number Already Exist!!!");
                 return $msg;
             }
         }
 
         $msg = array();
 
         foreach ($post['hsn'] as $row) {
 
             $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $row), '*');
             if (!isset($item_id['id'])) {
                 $msg = array('st' => 'failed', 'msg' => $row . ' This Hsn Item Not found in Accounting system..!');
                 return $msg;
             }
             $pid[] = $item_id['id'];
         }
         //$pid=$post['pid'];
         $qty = $post['qty'];
         $price = $post['price'];
         $igst = $post['igst'];
         $cgst = $post['cgst'];
         $sgst = $post['sgst'];
 
         $discount = @$post['discount'] ? $post['discount'] : '0';
         $total = 0.0;
         $taxability_array = array();
         $gmodel = new GeneralModel();
 
         for ($i = 0; $i < count($pid); $i++) {
             $total += $post['qty'][$i] * $post['price'][$i];
 
             $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
             // echo '<pre>';print_r($item_data);
             $taxability_array[] = $item_data['taxability'];
         }
 
         $post['taxable'] = $total;
 
         if (isset($post['lr_date'])) {
             $lr_dt = date_create($post['lr_date']);
             $lr_date = date_format($lr_dt, 'Y-m-d');
         } else {
             $lr_date = '';
         }
 
         $gmodel = new GeneralModel();
 
         if (empty($post['id'])) {
             $getId = $gmodel->get_api_voucher_return_id($post['database'], 'sales_return');
             $post['return_no'] = $getId + 1;
         } else {
             $getinvoice = $gmodel->get_api_data_table($post['database'], 'sales_return', array('id' => $post['id'], 'return_no'));
             $post['return_no'] = $getinvoice['return_no'];
         }
 
         if (!empty($post['account_name'])) {
             $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('name' => $post['account_name']), 'tds_limit,state,gst,id');
 
             $post['tds_limit'] = $getaccount['tds_limit'];
             $post['acc_state'] = $getaccount['state'];
             $post['gst'] = $getaccount['gst'];
             $post['account'] = $getaccount['id'];
         } else {
             $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
             return $msg;
         }
 
         // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];
 
         $igst = 0;
         $cgst = 0;
         $sgst = 0;
 
         if ((float) $post['tot_igst'] > 0) {
             $netamount = $total + (float) $post['tot_igst'];
             $igst = (float) $post['tot_igst'];
             $cgst = (float) $post['tot_igst'] / 2;
             $sgst = (float) $post['tot_igst'] / 2;
         } else {
             $netamount = $total + (float) $post['tot_sgst'] + (float) $post['tot_cgst'];
             $igst = (float) @$post['tot_sgst'] + (float) @$post['tot_cgst'];
             $cgst = (float) @$post['tot_cgst'];
             $sgst = (float) @$post['tot_sgst'];
         }
         // update trupti 28-11-2022
         $taxes_array = @$post['taxes'];
         if (in_array("igst", $taxes_array)) {
             $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
             $igst_acc = $igst_acc_id['id'];
             $cgst_acc = '';
             $sgst_acc = '';
         } else {
             $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
             $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
             $igst_acc = '';
             $cgst_acc = $cgst_acc_id['id'];
             $sgst_acc = $sgst_acc_id['id'];
         }
         // update trupti 28-11-2022
         $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');
 
         $time = strtotime(db_date($post['return_date']));
         $month = date("m", $time);
         $year = date("y", $time);
         $year1 = date("Y", $time);
 
         $start = strtotime("{$year1}-{$month}-01");
         $end = strtotime('-1 second', strtotime('+1 month', $start));
 
         $start_date = date('Y-m-d', $start);
         $end_date = date('Y-m-d', $end);
 
         $builder_pt_voucher = $db->table('sales_return');
         $select = 'MAX(id) as max_id';
         $builder_pt_voucher->select($select);
         $builder_pt_voucher->where(array('is_delete' => '0'));
         $builder_pt_voucher->where(array('DATE(return_date)  >= ' => $start_date));
         $builder_pt_voucher->where(array('DATE(return_date)  <= ' => $end_date));
         $query = $builder_pt_voucher->get();
         $getdata = $query->getRow();
         $custom_date = $month . $year;
         if (empty($post['gst']) or $post['gst'] == null) {
             $custom_gst_code = 'C';
         } else {
             $custom_gst_code = 'B';
         }
         if (!empty($getdata->max_id)) {
             $invoice_data = $gmodel->get_data_table('sales_return', array('id' => $getdata->max_id), 'supp_inv');
             if (!empty($invoice_data['supp_inv'])) {
                 $string = $invoice_data['supp_inv'];
                 $outputArr = preg_split("/\//", $string);
                 $count = count($outputArr);
                 $last_array = $outputArr[$count - 1];
                 $int_var = (int) filter_var($last_array, FILTER_SANITIZE_NUMBER_INT);
                 $new_num = $int_var + 1;
                 $s_number = str_pad($new_num, 4, "0", STR_PAD_LEFT);
             } else {
                 $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
             }
         } else {
             $s_number = str_pad(0001, 4, "0", STR_PAD_LEFT);
         }
 
         if (!empty($result_array)) {
             $new_supply_inv_no = @$result_array->supp_inv;
         } else {
             $new_supply_inv_no = 'AC/' . $custom_date . '/' . $custom_gst_code . $s_number;
         }
 
         $netamount += @$post['round_diff'] ? $post['round_diff'] : 0;
         $pdata = array(
             'voucher_type' => $post['voucher_type'],
             'gl_group' => $gl_id['gl_group'],
             'return_date' => db_date($post['return_date']),
             'return_no' => $post['return_no'],
             'supp_inv' => $new_supply_inv_no,
             'invoice' => @$post['invoice'] ? $post['invoice'] : '',
             'account' => $post['account'],
             'tds_limit' => $post['tds_limit'],
             'acc_state' => $post['acc_state'],
             'gst' => $post['gst'],
             'broker' => @$post['broker'],
             'other' => @$post['other'] ? $post['other'] : '',
             'lr_no' => @$post['lrno'],
             'lr_date' => @$lr_date,
             'delivery_code' => @$post['delivery_code'],
             'transport' => @$post['transport'],
             'taxes' => json_encode(@$post['taxes']),
             'tot_igst' => @$igst,
             'tot_cgst' => @$cgst,
             'tot_sgst' => @$sgst,
             'total' => $total,
             'discount' => @$discount ? $discount : '',
             'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
             'net_amount' => $netamount,
             'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
             'round' => @$post['round'],
             'round_diff' => @$post['round_diff'],
             'taxable' => @$post['taxable'],
             'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
             'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
             'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
             'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
             'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
             'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
             'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
             'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
             'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
             'ship_address' => @$post['ship_address'] ? $post['ship_address'] : '',
             'ship_state' => @$post['ship_state'] ? $post['ship_state'] : '',
             'ship_city' => @$post['ship_city'] ? $post['ship_city'] : '',
             'ship_country' => @$post['ship_country'] ? $post['ship_country'] : '',
             'ship_pin' => @$post['ship_pin'] ? $post['ship_pin'] : '',
             'igst_acc' => @$igst_acc,
             'cgst_acc' => @$cgst_acc,
             'sgst_acc' => @$sgst_acc,
         );
         // update trupti 28-11-2022
         if ($post['gst'] != '') {
             if (in_array('Taxable', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Taxable';
             } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Exempt';
             } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Nill';
             } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Exempt';
             } else {
                 $pdata['inv_taxability'] = 'N/A';
             }
         } else {
             if (in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Exempt';
             } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Taxable';
             } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {
 
                 $pdata['inv_taxability'] = 'Nill';
             } else {
                 $pdata['inv_taxability'] = 'N/A';
             }
         }
 
         if (!empty($result_array)) {
 
             $pdata['update_at'] = date('Y-m-d H:i:s');
             $pdata['update_by'] = 0;
 
             if (empty($msg)) {
                 $builder->where(array("id" => $post['id']));
                 $result = $builder->Update($pdata);
 
                 $item_builder = $db->table('sales_item');
                 $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return'))->get();
                 $getItem = $item_result->getRow();
 
                 $getpid = explode(',', $getItem->item_id);
                 $delete_itemid = array_diff($getpid, $pid);
 
                 if (!empty($delete_itemid)) {
                     foreach ($delete_itemid as $key => $del_id) {
                         $del_data = array('is_delete' => '1');
                         $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'return'));
                         $item_builder->update($del_data);
                     }
                 }
 
                 for ($i = 0; $i < count($pid); $i++) {
                     $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id'], 'type' => "return"))->get();
                     $getItem = $item_result->getRow();
 
                     if (!empty($getItem)) {
                         // $qty = $post['qty'][$i] - $getItem->qty;
                         $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                         $item_hsn = $item_data['hsn'];
                         $item_taxability = $item_data['taxability'];
 
                         $sub = $post['qty'][$i] * $post['price'][$i];
                         if (!empty($post['igst'][$i])) {
                             $item_igst_amt = $sub * $post['igst'][$i] / 100;
                             $item_cgst_amt = $item_igst_amt / 2;
                             $item_sgst_amt = $item_igst_amt / 2;
                         } else {
                             $item_igst_amt = 0;
                             $item_cgst_amt = 0;
                             $item_sgst_amt = 0;
                         }
                         $item_data = array(
                             'uom' => 'PCS',
                             'hsn' => $item_hsn,
                             'type' => 'return',
                             'rate' => $post['price'][$i],
                             'qty' => $post['qty'][$i],
                             'item_disc' => 0,
                             'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                             'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                             'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                             'igst_amt' => $item_igst_amt,
                             'cgst_amt' => $item_cgst_amt,
                             'sgst_amt' => $item_sgst_amt,
                             'taxability' => $item_taxability,
                             'total' => $sub,
                             'sub_total' => $sub,
                             'remark' => '',
                             'is_delete' => 0,
                             'update_at' => date('Y-m-d H:i:s'),
                             'update_by' => 0,
                         );
 
                         $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                         $res = $item_builder->update($item_data);
                     } else {
                         $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                         $item_hsn = $item_data['hsn'];
                         $item_taxability = $item_data['taxability'];
 
                         $sub = $post['qty'][$i] * $post['price'][$i];
                         if (!empty($post['igst'][$i])) {
                             $item_igst_amt = $sub * $post['igst'][$i] / 100;
                             $item_cgst_amt = $item_igst_amt / 2;
                             $item_sgst_amt = $item_igst_amt / 2;
                         } else {
                             $item_igst_amt = 0;
                             $item_cgst_amt = 0;
                             $item_sgst_amt = 0;
                         }
                         $item_data = array(
                             'parent_id' => $post['id'],
                             'item_id' => $pid[$i],
                             'hsn' => $item_hsn,
                             'type' => 'return',
                             'uom' => 'PCS',
                             'rate' => $post['price'][$i],
                             'qty' => $post['qty'][$i],
                             'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                             'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                             'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                             'igst_amt' => $item_igst_amt,
                             'cgst_amt' => $item_cgst_amt,
                             'sgst_amt' => $item_sgst_amt,
                             'taxability' => $item_taxability,
                             'total' => $sub,
                             'sub_total' => $sub,
                             'item_disc' => 0,
                             'remark' => '',
                             'created_at' => date('Y-m-d H:i:s'),
                             'created_by' => 0,
                         );
                         $res = $item_builder->insert($item_data);
                     }
                     $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $pid[$i], "type" => 'return'));
                     $result1 = $item_builder->update($item_data);
                 }
                 $builder = $db->table('sales_return');
 
                 if ($result) {
                     $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                 } else {
                     $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                 }
             }
         } else {
 
             $gmodel = new GeneralModel();
 
             $pdata['created_at'] = date('Y-m-d H:i:s');
             $pdata['created_by'] = 0;
 
             if (empty($msg)) {
 
                 $result = $builder->Insert($pdata);
                 $id = $db->insertID();
 
                 for ($i = 0; $i < count($pid); $i++) {
                     $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                     $item_hsn = $item_data['hsn'];
                     $item_taxability = $item_data['taxability'];
 
                     $sub = $post['qty'][$i] * $post['price'][$i];
                     if (!empty($post['igst'][$i])) {
                         $item_igst_amt = $sub * $post['igst'][$i] / 100;
                         $item_cgst_amt = $item_igst_amt / 2;
                         $item_sgst_amt = $item_igst_amt / 2;
                     } else {
                         $item_igst_amt = 0;
                         $item_cgst_amt = 0;
                         $item_sgst_amt = 0;
                     }
 
                     $itemdata[] = array(
                         'parent_id' => $id,
                         'item_id' => $pid[$i],
                         'hsn' => $item_hsn,
                         'type' => 'return',
                         'uom' => 'PCS',
                         'rate' => $post['price'][$i],
                         'qty' => $post['qty'][$i],
                         'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                         'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                         'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                         'igst_amt' => $item_igst_amt,
                         'cgst_amt' => $item_cgst_amt,
                         'sgst_amt' => $item_sgst_amt,
                         'taxability' => $item_taxability,
                         'total' => $sub,
                         'sub_total' => $sub,
                         'item_disc' => 0,
                         'remark' => '',
                         'created_at' => date('Y-m-d H:i:s'),
                         'created_by' => 0,
                     );
                 }
                 $item_builder = $db->table('sales_item');
                 $result1 = $item_builder->insertBatch($itemdata);
 
                 if ($result && $result1) {
                     $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                 } else {
                     $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                 }
             }
         }
         $msg['id'] = @$id ? $id : $post['id'];
         $msg['custom_invoiceNO'] = @$new_supply_inv_no;
         return $msg;
     }
     public function klamp_ace_insert_edit_purchase_invoice($post)
     {
       
        //echo '<pre>';Print_r($post);exit;
        
        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }
        $gmodel = new GeneralModel();

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();
        $pid = array();
        if (!isset($post['pid'])) {
            foreach ($post['hsn'] as $row) {

                $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $row), '*');
                
               // echo '<pre>';Print_r($item_id['id']);
                if (!isset($item_id['id'])) {
                    $msg = array('st' => 'failed', 'msg' => $row . ' This Hsn Item Not found in Accounting system..!');
                    return $msg;
                }
                $pid[] = $item_id['id'];
            }
            //exit;
        } else {
            $pid = $post['pid'];
        }
        //echo '<pre>';Print_r($pid);exit;

        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;

        if (isset($post['pid'])) {
            $count = count($post['pid']);
        } else {
            $count = count($post['hsn']);
        }

        // update trupti 28-11-2022
        $taxability_array = array();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            $taxability_array[] = $item_data['taxability'];
        }
        $post['taxable'] = $total;

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $gl_id = $gmodel->get_data_table('account', array('id' => $post['account']), 'gl_group');
        if(empty($gl_id))
        {
            $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this id: '.$post['account']);
            return $msg;
        }

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_invoice_id($post['database'], 'purchase_invoice');
            $post['invoice_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'purchase_invoice', array('id' => $post['id'], 'invoice_no'));
            $post['invoice_no'] = $getchallan['invoice_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if ($post['tot_igst'] > 0) {
            $netamount = $total + $post['tot_igst'];
            $igst = $post['tot_igst'];
            $cgst = (float)$post['tot_igst'] / 2;
            $sgst = (float)$post['tot_igst'] / 2;
        } else {
            $netamount = $total + (float)$post['tot_sgst'] + (float)$post['tot_cgst'];
            $igst = (float)@$post['tot_sgst'] + (float)@$post['tot_cgst'];
            $cgst = (float)@$post['tot_cgst'];
            $sgst = (float)@$post['tot_sgst'];
        }
        // update trupti 28-11-2022
        $taxes_array = @$post['taxes'];
        if (in_array("igst", $taxes_array)) {
            $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
            $igst_acc = $igst_acc_id['id'];
            $cgst_acc = '';
            $sgst_acc = '';
        } else {
            $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
            $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
            $igst_acc = '';
            $cgst_acc = $cgst_acc_id['id'];
            $sgst_acc = $sgst_acc_id['id'];
        }
        // update trupti 28-11-2022
        

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' =>  $gl_id['gl_group'],
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'supply_inv' => $post['custom_inv_no'],
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'transport' => @$post['transport'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',    
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vehicle' => @$post['vehicle'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'is_import' => isset($post['is_import']) ? $post['is_import'] : 0,
            'import_gst' => isset($post['import_gst']) ? $post['import_gst'] : 0,
            'import_taxable' => isset($post['import_taxable']) ? $post['import_taxable'] : 0,
            'import_nontaxable' => isset($post['import_nontaxable']) ? $post['import_nontaxable'] : 0,
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,

        );
        // update trupti 28-11-2022
        if ($post['gst'] != '') {
            if (in_array('Taxable', $taxability_array)) {


                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $taxability_array) && in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $taxability_array) && !in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $taxability_array) && in_array('Nill', $taxability_array) && !in_array('Exempt', $taxability_array)) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }


        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);


                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        // update trupti 28-11-2022
                        $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability,hsn');
                        //$item_hsn = $item_data['hsn'];
                        $item_taxability = $item_data['taxability'];
                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }
                        $item_data = array(
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'hsn' => $post['hsn'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {

                        if (!isset($post['pid'][$i]) || empty($post['pid'][$i])) {
                            $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][$i]), '*');
                        }
                        // update trupti 28-11-2022
                        $item_data = $gmodel->get_data_table('item', array('id' => $post['pid'][$i]), 'id,taxability');

                        $item_taxability = $item_data['taxability'];
                        $sub = $post['qty'][$i] * $post['price'][$i];
                        if (!empty($post['igst'][$i])) {
                            $item_igst_amt = $sub * $post['igst'][$i] / 100;
                            $item_cgst_amt = $item_igst_amt / 2;
                            $item_sgst_amt = $item_igst_amt / 2;
                        } else {
                            $item_igst_amt = 0;
                            $item_cgst_amt = 0;
                            $item_sgst_amt = 0;
                        }

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => isset($post['pid'][$i]) ? $post['pid'][$i] : $item_id['id'],
                            'hsn' => $post['hsn'][$i],
                            'type' => 'invoice',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'igst_amt' => $item_igst_amt,
                            'cgst_amt' => $item_cgst_amt,
                            'sgst_amt' => $item_sgst_amt,
                            'taxability' => $item_taxability,
                            'item_disc' => 0,
                            'total' => $sub,
                            'sub_total' => $sub,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => isset($post['pid'][$i]) ? $post['pid'][$i] : $item_id['id'], "type" => 'invoice'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('purchase_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();
                for ($i = 0; $i < count($pid); $i++) {
                    if (!isset($post['pid'][$i]) || empty($post['pid'][$i])) {
                        $item_id = $gmodel->get_api_data_table($post['database'], 'item', array('hsn' => $post['hsn'][$i]), '*');
                    }
                    $item_data = $gmodel->get_data_table('item', array('id' => $item_id['id']), 'id,taxability');

                    $item_taxability = $item_data['taxability'];
                    // update trupti 28-11-2022
                    $sub = $post['qty'][$i] * $post['price'][$i];
                    if (!empty($post['igst'][$i])) {
                        $item_igst_amt = $sub * $post['igst'][$i] / 100;
                        $item_cgst_amt = $item_igst_amt / 2;
                        $item_sgst_amt = $item_igst_amt / 2;
                    } else {
                        $item_igst_amt = 0;
                        $item_cgst_amt = 0;
                        $item_sgst_amt = 0;
                    }


                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => isset($post['pid'][$i]) ?  $post['pid'][$i] : $item_id['id'],
                        'hsn' => @$post['hsn'][$i] ?  $post['hsn'][$i] : '',
                        'type' => 'invoice',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'igst_amt' => $item_igst_amt,
                        'cgst_amt' => $item_cgst_amt,
                        'sgst_amt' => $item_sgst_amt,
                        'taxability' => $item_taxability,
                        'item_disc' => 0,
                        'total' => $sub,
                        'sub_total' => $sub,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        return $msg;
    }
     public function klamp_ace_insert_edit_custom_jv($post)
     {
         $db = $this->db;
         $db->setDatabase($post['database']);
         $builder = $db->table('jv_main');
         $builder->select('*');
         $builder->where(array("id" => @$post['jv_id']));
         $builder->limit(1);
         $result = $builder->get();
         $result_array = $result->getRow();
 
         $pdata = array(
             'date' => db_date($post['date']),
             'narration' => $post['narration'],
         );
         $gmodel = new GeneralModel;
 
         foreach ($post['custom'] as $key => $value) {
 
             if ($key == 3) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 3%'), 'id');
             } else if ($key == 5) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 5%'), 'id');
             } else if ($key == 12) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 12%'), 'id');
             } else if ($key == 18) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 18%'), 'id');
             } else  if ($key == 28) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Charge @ 28%'), 'id');
             } else {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Custom Duty Non Taxable'), 'id');
             }
             $post['dr_cr'][] = 'dr';
             $post['amount'][] = $value;
             if (isset($particular['id'])) {
                 $post['particular'][] = $particular['id'];
             } else {
                 $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this name: Custom Duty Charge @ ' . $key . '%');
                 return $msg;
             }
         }
 
         foreach ($post['gst'] as $key => $value) {
 
             if ($key == 3) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 3%'), 'id');
             } else if ($key == 5) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 5%'), 'id');
             } else if ($key == 12) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 12%'), 'id');
             } else if ($key == 18) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 18%'), 'id');
             } else  if ($key == 28) {
                 $particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Import IGST 28%'), 'id');
             } else {
             }
 
             $post['dr_cr'][] = 'dr';
             $post['amount'][] = $value;
             if (isset($particular['id'])) {
                 $post['particular'][] = $particular['id'];
             } else {
                 $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this name: Import IGST ' . $key . '%');
                 return $msg;
             }
         }
 
         $cr_particular = $gmodel->get_api_data_table($post['database'], 'account', array('name' => 'Duty Charge'), 'id');
         if (isset($cr_particular['id'])) {
             $post['dr_cr'][] = 'cr';
             $post['amount'][] = $post['cr_amt'];
             $post['particular'][] = $cr_particular['id'];
         } else {
             $msg = array('st' => 'fail', 'msg' => 'Ledger Account Not Found with this name: Duty Charge');
             return $msg;
         }
 
         if (!empty($result_array)) {
 
             for ($i = 0; $i < count($post['dr_cr']); $i++) {
                 // if (in_array($post['item_id'][$i], $old_item)) {
                 $data = array(
                     'jv_id' => $post['jv_id'],
                     'date' => db_date($post['date']),
                     'dr_cr' => $post['dr_cr'][$i],
                     'particular' => $post['particular'][$i],
                     'method' => 'on_account',
                     'amount' => $post['amount'][$i],
                 );
                
                 $builder = $db->table('jv_particular');
                 $builder->where(array("jv_id" => $post['jv_id'], "particular" => $post['particular'][$i]));
                 $result = $builder->Update($data);
             }
            
             $pdata['update_at'] = date('Y-m-d H:i:s');
             $pdata['update_by'] = session('uid');
             if (empty($msg)) {
                 $builder = $db->table('jv_main');
                 $builder->where(array("id" => $post['jv_id']));
                 $result = $builder->Update($pdata);
 
                 if ($result) {
                     $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!", 'id' => $post['jv_id']);
                 } else {
                     $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                 }
             }
         } else {
             $pdata['created_at'] = date('Y-m-d H:i:s');
             $pdata['created_by'] = 0;
 
             $result = $builder->Insert($pdata);
 
             $id = $db->insertID();
             // $j = 0;
             // $k = 0;
             for ($i = 0; $i < count($post['dr_cr']); $i++) {
                 $data = array(
                     'jv_id' => $id,
                     'date' => db_date($post['date']),
                     'dr_cr' => $post['dr_cr'][$i],
                     'particular' => $post['particular'][$i],
                     'method' => 'on_account',
                     'amount' => $post['amount'][$i],
                     'other' => @$post['other'][$i] ? $post['other'][$i] : '',
                     'stat_adj' => @$post['stat_adj'] ? $post['stat_adj'] : 0,
                 );
              
                 $data['created_at'] = date('Y-m-d H:i:s');
                 $data['created_by'] = 0;
                 $builder = $db->table('jv_particular');
                 $result1 = $builder->Insert($data);
             }
 
             if ($result and $result1) {
                 $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!", 'id' => $id);
             } else {
                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
             }
         }
         return $msg;
     }
    
   
    //start not usable
    public function insert_edit_sale_challan($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('sales_challan');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];
        }
        $post['taxable'] = $total;

        // if($post['disc_type'] == '%'){
        //     if($post['discount'] == ''){
        //         $post['discount'] = 0;
        //     } 
        //     else{
        //         $post['discount'] = $total * $post['discount']/100;
        //         if($post['discount'] > 0){
        //             $total = 0;
        //             for($i=0;$i<count($pid);$i++){
        //                 $disc_amt=0;
        //                 $devide_disc = $post['discount'] /count($pid);

        //                 if($item_disc[$i] != 0 ){
        //                     $sub = $post['qty'][$i] * $post['price'][$i];
        //                     $disc_amt = $sub * $item_disc[$i] / 100;
        //                 }

        //                 $total +=$post['qty'][$i] * $post['price'][$i] - $disc_amt - $devide_disc;
        //             }
        //         }
        //     }
        // } else {
        //     if($post['discount'] == ''){
        //         $post['discount'] = 0; 
        //     }
        //     if($post['discount'] > 0){
        //         $total = 0;
        //         for($i=0;$i<count($pid);$i++){
        //             $disc_amt=0;
        //             $devide_disc = $post['discount'] /count($pid);
        //             // echo 'devide_disc'. $devide_disc;exit;
        //             if($item_disc[$i] != 0 ){
        //                 $sub = $post['qty'][$i] * $post['price'][$i];
        //                 $disc_amt = $sub * $item_disc[$i] / 100;
        //             }

        //             $total +=$post['qty'][$i] * $post['price'][$i] - $disc_amt - $devide_disc;
        //         }
        //     }
        // }

        // if($post['amtx_type'] == '%'){
        //     if($post['amtx'] == '')
        //         $post['amtx'] = 0;
        //     else
        //         $post['amtx'] = $total *  $post['amtx']/100;
        // } else {
        //     if($post['amtx'] == '')
        //         $post['amtx'] = 0;
        // }

        // if($post['amty_type'] == '%'){
        //     if($post['amty'] == '')
        //         $post['amty'] = 0;
        //     else
        //         $post['amty'] = $total *  $post['amty']/100;
        // } else {
        //     if($post['amty'] == '')
        //         $post['amty'] = 0;
        // }

        // if($post['cess_type'] == '%'){
        //     if($post['cess'] == '')
        //         $post['cess'] = 0;
        //     else
        //         $post['cess'] = $total *  $post['cess']/100;
        // } else {
        //     if($post['cess'] == '')
        //         $post['cess'] = 0;
        // }

        // if(!empty($post['tds_per'])){
        //     $tds_amt =$total *  $post['tds_per']/100;
        // } else {
        //     $tds_amt = 0;
        // }

        $dt = date_create($post['challan_date']);
        $date = date_format($dt, 'Y-m-d');

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $gmodel = new GeneralModel();

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_id($post['database'], 'sales_challan');
            $post['challan_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'sales_challan', array('id' => $post['id'], 'challan_no'));
            $post['challan_no'] = $getchallan['challan_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');
            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        // $netamount = $total-$post['amtx'] + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if ($post['tot_igst'] > 0) {
            $netamount = $total + $post['tot_igst'];
            $igst = $post['tot_igst'];
            $cgst = (float)$post['tot_igst'] / 2;
            $sgst = (float)$post['tot_igst'] / 2;
        } else {
            $netamount = $total + $post['tot_sgst'] + $post['tot_cgst'];
            $igst = @$post['tot_sgst'] + @$post['tot_cgst'];
            $cgst = @$post['tot_cgst'];
            $sgst = @$post['tot_sgst'];
        }
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'challan_date' => $date,
            'challan_no' => $post['challan_no'],
            'custom_challan_no' => @$post['custom_challan_no'] ? $post['custom_challan_no'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'delivery_code' => @$post['delivery_code'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'weight' => @$post['weight'],
            'freight' => @$post['freight'],
            'transport' => @$post['transport'],
            'city' => @$post['city'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',
            // 'amtx' => $amtx,
            // 'amtx_type' => $post['amtx_type'],
            // 'amty' => $amty,
            // 'cess_type' => $post['cess_type'],        
            // 'cess' => $cess,        
            // 'tds_amt' => $post['tds_amt'],        
            // 'tds_per' => $post['tds_per'],        
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vehicle_modeno' => @$post['vhicle_modeno'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'challan'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'challan'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        $qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = array(
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $post['pid'][$i],
                            'type' => 'challan',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $post['pid'][$i], "type" => 'challan'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('sales_challan');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();
                for ($i = 0; $i < count($pid); $i++) {
                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $post['pid'][$i],
                        'type' => 'challan',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'item_disc' => 0,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        return $msg;
    }
    public function insert_edit_purchase_challan($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        if ($post['database'] == '') {
            $msg = array('st' => 'fail', 'msg' => 'Please Select Database..!');
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();


        $msg = array();


        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];

        $discount = @$post['discount'] ? $post['discount'] : '0';
        $total = 0.0;

        // update trupti 28-11-2022
        $taxability_array = array();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($pid); $i++) {
            $total += $post['qty'][$i] * $post['price'][$i];

            $item_data = $gmodel->get_data_table('item', array('id' => $pid[$i]), 'id,taxability');
            $taxability_array[] = $item_data['taxability'];
        }
        $post['taxable'] = $total;

        // if($post['disc_type'] == '%'){
        //     if($post['discount'] == ''){
        //         $post['discount'] = 0;
        //     } 
        //     else{
        //         $post['discount'] = $total * $post['discount']/100;
        //         if($post['discount'] > 0){
        //             $total = 0;
        //             for($i=0;$i<count($pid);$i++){
        //                 $disc_amt=0;
        //                 $devide_disc = $post['discount'] /count($pid);

        //                 if($item_disc[$i] != 0 ){
        //                     $sub = $post['qty'][$i] * $post['price'][$i];
        //                     $disc_amt = $sub * $item_disc[$i] / 100;
        //                 }

        //                 $total +=$post['qty'][$i] * $post['price'][$i] - $disc_amt - $devide_disc;
        //             }
        //         }
        //     }
        // } else {
        //     if($post['discount'] == ''){
        //         $post['discount'] = 0; 
        //     }
        //     if($post['discount'] > 0){
        //         $total = 0;
        //         for($i=0;$i<count($pid);$i++){
        //             $disc_amt=0;
        //             $devide_disc = $post['discount'] /count($pid);
        //             // echo 'devide_disc'. $devide_disc;exit;
        //             if($item_disc[$i] != 0 ){
        //                 $sub = $post['qty'][$i] * $post['price'][$i];
        //                 $disc_amt = $sub * $item_disc[$i] / 100;
        //             }

        //             $total +=$post['qty'][$i] * $post['price'][$i] - $disc_amt - $devide_disc;
        //         }
        //     }
        // }

        // if($post['amtx_type'] == '%'){
        //     if($post['amtx'] == '')
        //         $post['amtx'] = 0;
        //     else
        //         $post['amtx'] = $total *  $post['amtx']/100;
        // } else {
        //     if($post['amtx'] == '')
        //         $post['amtx'] = 0;
        // }

        // if($post['amty_type'] == '%'){
        //     if($post['amty'] == '')
        //         $post['amty'] = 0;
        //     else
        //         $post['amty'] = $total *  $post['amty']/100;
        // } else {
        //     if($post['amty'] == '')
        //         $post['amty'] = 0;
        // }

        // if($post['cess_type'] == '%'){
        //     if($post['cess'] == '')
        //         $post['cess'] = 0;
        //     else
        //         $post['cess'] = $total *  $post['cess']/100;
        // } else {
        //     if($post['cess'] == '')
        //         $post['cess'] = 0;
        // }

        // if(!empty($post['tds_per'])){
        //     $tds_amt =$total *  $post['tds_per']/100;
        // } else {
        //     $tds_amt = 0;
        // }

        $dt = date_create($post['challan_date']);
        $date = date_format($dt, 'Y-m-d');

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $gmodel = new GeneralModel();

        if (empty($post['id'])) {
            $getId = $gmodel->get_api_voucher_id($post['database'], 'purchase_challan');
            $post['challan_no'] = $getId + 1;
        } else {
            $getchallan = $gmodel->get_api_data_table($post['database'], 'purchase_challan', array('id' => $post['id'], 'challan_no'));
            $post['challan_no'] = $getchallan['challan_no'];
        }

        if ($post['account'] != '') {
            $getaccount = $gmodel->get_api_data_table($post['database'], 'account', array('id' => $post['account']), 'tds_limit,state,gst');

            $post['tds_limit'] = $getaccount['tds_limit'];
            $post['acc_state'] = $getaccount['state'];
            $post['gst'] = $getaccount['gst'];
        } else {
            $msg = array('st' => 'fail', 'msg' => "Please Select Distributer Or Party..!!");
            return $msg;
        }

        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if ((float)$post['tot_igst'] > 0) {
            $netamount = $total + $post['tot_igst'];
            $igst = (float)$post['tot_igst'];
            $cgst = (float)$post['tot_igst'] / 2;
            $sgst = (float)$post['tot_igst'] / 2;
        } else {
            $netamount = $total + $post['tot_sgst'] + $post['tot_cgst'];
            $igst = (float)@$post['tot_sgst'] + (float)@$post['tot_cgst'];
            $cgst = (float)@$post['tot_cgst'];
            $sgst = (float)@$post['tot_sgst'];
        }

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'challan_date' => $date,
            'challan_no' => $post['challan_no'],
            'custom_challan_no' => @$post['custom_challan_no'] ? $post['custom_challan_no'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst'],
            'sup_chl_no' => @$post['sup_chl_no'] ? $post['sup_chl_no'] : '',
            'supply_inv' => @$post['supply_inv'] ? $post['supply_inv'] : '',
            'broker' => @$post['broker'],
            'other' => @$post['other'] ? $post['other'] : '',
            'lr_no' => @$post['lrno'],
            'lr_date' => @$lr_date,
            'transport' => @$post['transport'],
            'city' => @$post['city'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => @$igst,
            'tot_cgst' => @$cgst,
            'tot_sgst' => @$sgst,
            'total_amount' => $total,
            'discount' => @$discount ? $discount : '',
            'disc_type' => @$post['disc_type'] ? $post['disc_type'] : '',       
            'net_amount' => round($netamount),
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : 'ROAD',
            'vehicle' => @$post['vehicle'] ? $post['vehicle'] : '',
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'challan'))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->item_id);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'challan'));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        $qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = array(
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'item_disc' => 0,
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'remark' => '',
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => 0,
                        );
                        $item_builder->where(array('item_id' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'item_id' => $post['pid'][$i],
                            'type' => 'challan',
                            'uom' => 'PCS',
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                            'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                            'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                            'item_disc' => 0,
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => 0,
                        );
                        $res = $item_builder->insert($item_data);
                    }
                    $item_builder->where(array('parent_id' => $post['id'], 'item_id' => $post['pid'][$i], "type" => 'challan'));
                    $result1 = $item_builder->update($item_data);
                }
                $builder = $db->table('purchase_challan');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();
                for ($i = 0; $i < count($pid); $i++) {
                    $itemdata[] = array(
                        'parent_id' => $id,
                        'item_id' => $post['pid'][$i],
                        'type' => 'challan',
                        'uom' => 'PCS',
                        'rate' => $post['price'][$i],
                        'qty' => $post['qty'][$i],
                        'igst' => $post['igst'][$i] ? $post['igst'][$i] : 0,
                        'cgst' => $post['cgst'][$i] ? $post['cgst'][$i] : 0,
                        'sgst' => $post['sgst'][$i] ? $post['sgst'][$i] : 0,
                        'item_disc' => 0,
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                }
                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = @$id ? $id : $post['id'];
        return $msg;
    }
    //end not usable
    public function insert_edit_transport($post)
    {

        $db = $this->db;
        $db->setDatabase($post['database']);
        $builder = $db->table('transport');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();
        $pdata = array(
            'code' => @$post['code'] ? $post['code'] : '',
            'name' => $post['name'],
            'contact' => @$post['contact'],
            'address' => @$post['address'],
            'pincode' => @$post['pincode'],
            'country' => !empty($post['country']) ? $post['country'] : '',
            'city' => !empty($post['city']) ? $post['city'] : '',
            'state' =>  !empty($post['state']) ? $post['state'] : '',
            'tran_id' => @$post['tran_id'] ? $post['tran_id'] : '',
            'status' => 1,
        );

        $gmodel = new GeneralModel;

        if (!empty($result_array)) {
            $res = $gmodel->get_api_data_table($post['database'], 'transport', array('name' => $post['name'], 'id!=' => $post['id']), '*');
            if (!empty($res)) {
                $msg = array('st' => 'fail', 'msg' => "Transport With Same Name Was Already Exist..!");
                return $msg;
            }
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = 0;
            if (empty($msg)) {

                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $id = $post['id'];

                $builder = $db->table('transport');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $res = $gmodel->get_api_data_table($post['database'], 'transport', array('name' => $post['name']), '*');
            if (!empty($res)) {
                $msg = array('st' => 'fail', 'msg' => "Transport With Same Name Was Already Exist..!");
                return $msg;
            }

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = 0;

            if (empty($msg)) {

                $result = $builder->Insert($pdata);

                $id = $db->insertID();
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        $msg['id'] = "$id";
        return $msg;
    }
    public function UpdateData($post)
    {
        $result = array();
        $db = $this->db;
        $gnmodel = new GeneralModel();

        if ($post['type'] == '' || $post['method'] == '' || $post['id'] == '' || $post['database'] == '') {
            $result = array('st' => 'fail', 'msg' => 'Please Send Proper Data => id,method,type,database');
            return $result;
        }
        if ($post['type'] == 'remove') {
            if ($post['method'] == 'account') {
                $result = $gnmodel->update_api_data_table($post['database'], 'account', array('id' => $post['id'], 'is_static' => '0'), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }
            if ($post['method'] == 'item') {
                $result = $gnmodel->update_api_data_table($post['database'], 'item', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }
            if ($post['method'] == 'itemgrp') {
                $result = $gnmodel->update_api_data_table($post['database'], 'item_group', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }

            if ($post['method'] == 'salechallan') {
                $gnmodel = new GeneralModel();
                $sales_invoice = $gnmodel->get_api_array_table($post['database'], 'sales_invoice', array('challan_no' => $post['id']), 'is_delete,is_cancle');

                foreach ($sales_invoice as $row) {
                    if (@$row['is_delete'] == 0 && @$row['is_cancle'] == '0') {
                        $is_delete = 0;
                    }
                }
                if (isset($is_delete) && $is_delete == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Delete Invoice');
                } else {
                    $result = $gnmodel->update_api_data_table($post['database'], 'sales_challan', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                }
            }

            if ($post['method'] == 'salesinvoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'sales_invoice', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                $result1 = $gnmodel->update_api_data_table($post['database'], 'platform_voucher', array('voucher' => $post['id'], 'type' => 'invoice'), array('is_delete' => '1','is_update'=>1));
            }

            if ($post['method'] == 'salesreturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'sales_return', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                $result1 = $gnmodel->update_api_data_table($post['database'], 'platform_voucher', array('voucher' => $post['id'], 'type' => 'return'), array('is_delete' => '1','is_update'=>1));
            }

            if ($post['method'] == 'purchasechallan') {
                $gnmodel = new GeneralModel();

                $purchase_invoice = $gnmodel->get_api_array_table($post['database'], 'purchase_invoice', array('challan_no' => $post['id']), 'is_cancle,is_delete');

                foreach ($purchase_invoice as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_delete = 0;
                    }
                }

                if (isset($is_delete) && $is_delete == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Delete Purchase Invoice..!');
                } else {
                    $result = $gnmodel->update_api_data_table($post['database'], 'purchase_challan', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                }
            }

            if ($post['method'] == 'purchaseinvoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'purchase_invoice', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }
            if ($post['method'] == 'purchasereturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'purchase_return', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }
        }

        if ($post['type'] == 'cancle') {
            if ($post['method'] == 'salechallan') {
                $gnmodel = new GeneralModel();
                $sales_invoice = $gnmodel->get_api_array_table($post['database'], 'sales_invoice', array('challan_no' => $post['id']), 'is_cancle,is_delete');

                foreach ($sales_invoice as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_cancle = 0;
                    }
                }

                if (isset($is_cancle) && $is_cancle == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Cancle Invoice');
                } else {
                    $result = $gnmodel->update_api_data_table($post['database'], 'sales_challan', array('id' => $post['id']), array('is_cancle' => 1, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                }
            }

            if ($post['method'] == 'salesinvoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'sales_invoice', array('id' => $post['id']), array('is_cancle' => 1, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                $result1 = $gnmodel->update_api_data_table($post['database'], 'platform_voucher', array('voucher' => $post['id'], 'type' => 'invoice'), array('is_cancle' => '1'));
            }

            if ($post['method'] == 'salesreturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'sales_return', array('id' => $post['id']), array('is_cancle' => 1, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                $result1 = $gnmodel->update_api_data_table($post['database'], 'platform_voucher', array('voucher' => $post['id'], 'type' => 'return'), array('is_cancle' => '1'));
            }
            if ($post['method'] == 'transport') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'transport', array('id' => $post['id']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }

            if ($post['method'] == 'purchasechallan') {

                $gnmodel = new GeneralModel();
                $purchase_invoice = $gnmodel->get_api_array_table($post['database'], 'purchase_invoice', array('challan_no' => $post['id']), 'is_cancle,is_delete');

                foreach ($purchase_invoice as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_cancle = 0;
                    }
                }

                if (isset($is_cancle) && $is_cancle == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Cancle Invoice');
                } else {
                    $result = $gnmodel->update_api_data_table($post['database'], 'purchase_challan', array('id' => $post['id']), array('is_cancle' => 1, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                }
            }

            if ($post['method'] == 'purchaseinvoice') {
                $gnmodel = new GeneralModel();
                $purchase_return = $gnmodel->get_api_array_table($post['database'], 'purchase_return', array('invoice' => $post['id']), 'is_cancle,is_delete');

                foreach ($purchase_return as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_cancle = 0;
                    }
                }
                if (isset($is_cancle) && $is_cancle == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Cancle Return');
                } else {
                    $result = $gnmodel->update_api_data_table($post['database'], 'purchase_invoice', array('id' => $post['id']), array('is_cancle' => 1, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
                }
            }
            if ($post['method'] == 'purchasereturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_api_data_table($post['database'], 'purchase_return', array('id' => $post['id']), array('is_cancle' => 1, 'update_at' => date('Y-m-d H:i:s'), 'update_by' => 0));
            }
        }

        $result['id'] = @$post['id'];
        return $result;
    }
   
}
?>
