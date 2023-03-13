<?php

namespace App\Models;

use CodeIgniter\Model;

class MillingModel extends Model
{

    public function insert_edit_grey($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        $discount = $post['discount'];
        $amtx = $post['amtx'];
        $amty = $post['amty'];

        $total = 0.0;
        $sub_total = 0.0;

        for ($i = 0; $i < count($post['pid']); $i++) {
            $total += $post['subtotal'][$i];
        }
        $sub_total = $total;
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                if ($post['discount'] > 0) {
                    $post['discount'] = $total * $post['discount'] / 100;
                    for ($i = 0; $i < count($pid); $i++) {
                        $disc_amt = 0;
                        $devide_disc = $post['discount'] / count($pid);
                        $total = $total - $devide_disc;
                    }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] == 0;
            }

            if ($post['discount'] > 0) {
                $total = 0;
                $devide_disc = $post['discount'] / count($pid);
                for ($i = 0; $i < count($pid); $i++) {
                    $disc_amt = 0;
                    $total = $total - $devide_disc;
                }
            }
        }

        if ($post['amtx_type'] == '%') {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            } else {
                $post['amtx'] = $total * $post['amtx'] / 100;
            }

        } else {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            }

        }

        if ($post['amty_type'] == '%') {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            } else {
                $post['amty'] = $total * $post['amty'] / 100;
            }

        } else {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            }

        }

        $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst'];
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'sr_no' => $post['srno'],
            'challan_no' => $post['challan'],
            'inv_no' => $post['inv_no'],
            'inv_date' => db_date($post['inv_date']),
            'purchase_type' => $post['purchase_type'],
            'transport_mode' => $post['trasport_mode'],
            'party_name' => @$post['account'],
            'delivery_ac' => @$post['delivery_ac'],
            'delivery_code' => @$post['delivery_code'],
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? $post['broker'] : '',
            'transport' => @$post['transport'] ? $post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? $post['warehouse'] :'',
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'acc_state' => $post['acc_state'],
            'total_amount' => $sub_total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amtx' => $amtx,
            'amtx_type' => $post['amtx_type'],
            'amty' => $amty,
            'cess_type' => $post['cess_type'],
            'cess' => $post['cess'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'net_amount' => $netamount,
        );

        if (!empty($result_array)) {
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('gray_item');
                $item_result = $item_builder->select('GROUP_CONCAT(pid) as pid')->where(array("voucher_id" => $post['id']))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->pid);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('pid' => $del_id, 'voucher_id' => $post['id']));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        $item_data = array(
                            'voucher_id' => $post['id'],
                            'pid' => $post['pid'][$i],
                            'type' => $post['type'][$i],
                            'purchase_type' => $post['purchase_type'],
                            'igst' => $post['igst'][$i],
                            'price' => $post['price'][$i],
                            'pcs' => $post['taka'][$i],
                            'cut' => $post['cut'][$i],
                            'meter' => $post['meter'][$i],
                            'amount' => $post['subtotal'][$i],
                            'remark' => $post['remark'][$i],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );

                        $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'voucher_id' => $post['id'],
                            'pid' => $post['pid'][$i],
                            'type' => $post['type'][$i],
                            'purchase_type' => $post['purchase_type'],
                            'igst' => $post['igst'][$i],
                            'price' => $post['price'][$i],
                            'pcs' => $post['taka'][$i],
                            'cut' => $post['cut'][$i],
                            'meter' => $post['meter'][$i],
                            'amount' => $post['subtotal'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                        $item_id = $db->insertID();
                    }
                    $item_builder->where(array('voucher_id' => $post['id'], 'pid' => $post['pid'][$i]));
                    $result1 = $item_builder->update($item_data);
                }

                $builder = $db->table('grey');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'pid' => $post['pid'][$i],
                        'type' => $post['type'][$i],
                        'purchase_type' => $post['purchase_type'],
                        'igst' => $post['igst'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'cut' => $post['cut'][$i],
                        'meter' => $post['meter'][$i],
                        'amount' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('gray_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    // $takaTb_id = explode(',',$post['takaTb_id'][$i]);

                    // for($j=0;$j<count($takaTb_id);$j++){
                    //     $taka_builer = $db->table('grey_taka');
                    //     $taka_builer->where('id',$takaTb_id[$j]);
                    //     $taka_builer->update(array('voucher_id'=>$id,'MillItem_id'=>$item_id));
                    // }
                }
                if ($result && $result1) {
                    $gmodel = new GeneralModel();
                    $gmodel->update_data_table('grey_challan', array('id' => $post['challan']), array('is_invoiced' => 1));

                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }

        return $msg;
    }

    public function insert_edit_MillSaleInvoice($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillInvoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        $discount = $post['discount'];
        $amtx = $post['amtx'];
        $amty = $post['amty'];

        $total = 0.0;

        for ($i = 0; $i < count($post['pid']); $i++) {
            $total += $post['subtotal'][$i];
        }
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                if ($post['discount'] > 0) {
                    $post['discount'] = $total * $post['discount'] / 100;
                    $total = $total -$post['discount'];
                    // for ($i = 0; $i < count($pid); $i++) {
                    //     $disc_amt = 0;
                    //     $devide_disc = $post['discount'] / count($pid);
                    //     $total = $total - $devide_disc;
                    // }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] == 0;
            }

            if ($post['discount'] > 0) {
                $total = $total -$post['discount'];
                
                // $total = 0;
                // $devide_disc = $post['discount'] / count($pid);
                // for ($i = 0; $i < count($pid); $i++) {
                //     $disc_amt = 0;
                //     $total = $total - $devide_disc;
                // }
            }
        }

        if ($post['amtx_type'] == '%') {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            } else {
                $post['amtx'] = $total * $post['amtx'] / 100;
            }

        } else {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            }

        }

        if ($post['amty_type'] == '%') {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            } else {
                $post['amty'] = $total * $post['amty'] / 100;
            }

        } else {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            }

        }

        $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst'];
        // echo '<pre>';print_r($post);exit;
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'challan' => $post['challan'],
            'sr_no' => $post['srno'],
            'date' => db_date($post['invoice_date']),
            'transport_mode' => $post['trasport_mode'],
            'account' => @$post['account'],
            'gst' => @$post['gst'],
            'item_type' => @$post['item_type'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'vehicle' => @$post['vehicle'] ? @$post['vehicle'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'other' => @$post['other'] ? @$post['other'] : '',
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'acc_state' => $post['acc_state'],
            'total_amount' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amtx' => $amtx,
            'amtx_type' => $post['amtx_type'],
            'amty' => $amty,
            'cess_type' => $post['cess_type'],
            'cess' => $post['cess'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'net_amount' => $netamount,
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('saleMillInvoice_Item');
                $item_result = $item_builder->select('GROUP_CONCAT(pid) as pid')->where(array("voucher_id" => $post['id']))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->pid);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('pid' => $del_id, 'voucher_id' => $post['id']));
                        $item_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        
                        $item_data = array(
                            'voucher_id' => $post['id'],
                            'pid' => $post['pid'][$i],
                            'item_type' => @$post['item_type'],
                            'type' => $post['type'][$i],
                            'taka' => $post['taka'][$i],
                            'meter' => $post['meter'][$i],
                            'gst' => $post['gst'][$i],
                            'price' => $post['price'][$i],
                            'subtotal' => $post['subtotal'][$i],
                            'remark' => $post['remark'][$i],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );


                        $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'voucher_id' => $post['id'],
                            'pid' => $post['pid'][$i],
                            'item_type' => @$post['item_type'],
                            'type' => $post['type'][$i],
                            'taka' => $post['taka'][$i],
                            'meter' => $post['meter'][$i],
                            'gst' => $post['gst'][$i],
                            'price' => $post['price'][$i],
                            'subtotal' => $post['subtotal'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                        $item_id = $db->insertID();
                    }
                    $item_builder->where(array('voucher_id' => $post['id'], 'pid' => $post['pid'][$i]));
                    $result1 = $item_builder->update($item_data);
                }

                $builder = $db->table('saleMillInvoice');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'pid' => $post['pid'][$i],
                        'item_type' => @$post['item_type'],
                        'type' => $post['type'][$i],
                        'taka' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'gst' => $post['gst'][$i],
                        'price' => $post['price'][$i],
                        'subtotal' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('saleMillInvoice_Item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                   
                }
                if ($result && $result1) {
                    $gmodel = new GeneralModel();
                    $gmodel->update_data_table('saleMillChallan', array('id' => $post['challan']), array('is_invoiced' => 1));

                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }

        return $msg;
    }

    public function insert_edit_millSend($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('mill_challan');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];

        $total = 0.0;

        $pdata = array(
            'challan_no' => $post['challan'],
            'sr_no' => $post['srno'],
            'challan_date' => db_date($post['challan_date']),
            'transport_mode' => $post['trasport_mode'],
            'mill_ac' => @$post['account'],
            'acc_state' => @$post['acc_state'],
            'tds_per' => @$post['tds_per'],
            'tds_limit' => @$post['tds_limit'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('mill_item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();
                    // print_r($getItem);exit;

                    $item_data = array(
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'remark' => $post['remark'][$i],
                    );
                    $delmill_ids = explode(',', $post['need_toDelete'][$i]);
                    $mill_takaTb_ids = explode(',', $post['mill_takaTb_ids'][$i]);
                    $greyTakaTb_ids = explode(',', $post['greyTakaTb_ids'][$i]);
                    if (!empty($post['need_toDelete'][$i])) {
                        foreach ($delmill_ids as $mill_id) {
                            $greytaka = $gmodel->get_data_table('millChallan_taka', array('id' => $mill_id), 'greyTaka_Id');
                            $gmodel->update_data_table('greyChallan_taka', array('id' => @$greytaka['greyTaka_Id']), array('is_send_mill' => 0));
                            $gmodel->update_data_table('millChallan_taka', array('id' => $mill_id), array('is_delete' => 1));
                        }
                    }

                    $mill_item = $gmodel->get_data_table('mill_item', array('voucher_id' => $post['id'], 'pid' => $pid[$i]), 'id');

                    foreach ($mill_takaTb_ids as $millId) {
                        $gmodel->update_data_table('millChallan_taka', array('id' => $millId), array('voucher_id' => $post['id'], 'mill_item_id' => $mill_item['id']));
                    }

                    foreach ($greyTakaTb_ids as $greyid) {
                        $gmodel->update_data_table('greyChallan_taka', array('id' => $greyid), array('is_send_mill' => 1));
                    }

                    $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                    $res = $item_builder->update($item_data);
                }

                $builder = $db->table('grey');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'all_greyTakaTb_ids' => $post['all_greyTakaTb_ids'][$i],
                        'pid' => $post['pid'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'price' => $post['price'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('mill_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $mill_takaTb_ids = explode(',', $post['mill_takaTb_ids'][$i]);
                    $greyTakatbID = explode(',', $post['greyTakaTb_ids'][$i]);

                    for ($j = 0; $j < count($mill_takaTb_ids); $j++) {
                        $taka_builer = $db->table('millChallan_taka');
                        $taka_builer->where('id', $mill_takaTb_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'mill_item_id' => $item_id));
                    }

                    for ($k = 0; $k < count($greyTakatbID); $k++) {
                        $taka_builer = $db->table('greyChallan_taka');
                        $taka_builer->where('id', $greyTakatbID[$k]);
                        $taka_builer->update(array('is_send_mill' => 1));
                    }

                }
                if ($result && $result1) {
                    $gmodel = new GeneralModel();
                    $gmodel->update_data_table('grey_challan', array('id' => $post['challan']), array('is_invoiced' => 1));

                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }

        return $msg;
    }

    public function insert_edit_retGrayFinish($post)
    {
        if(!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product..!!");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('retGrayFinish');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        $discount = $post['discount'];
        $amtx = $post['amtx'];
        $amty = $post['amty'];

        $total = 0.0;

        for ($i = 0; $i < count($post['pid']); $i++) {
            $total += $post['subtotal'][$i];
        }
        $total_amt = $total;
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                if ($post['discount'] > 0) {
                    $post['discount'] = $total * $post['discount'] / 100;
                    for ($i = 0; $i < count($pid); $i++) {
                        $disc_amt = 0;
                        $devide_disc = $post['discount'] / count($pid);
                        $total = $total - $devide_disc;
                    }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] == 0;
            }

            if ($post['discount'] > 0) {
                $total = 0;
                $devide_disc = $post['discount'] / count($pid);
                for ($i = 0; $i < count($pid); $i++) {
                    $disc_amt = 0;
                    $total = $total - $devide_disc;
                }
            }
        }
        
        if ($post['amtx_type'] == '%') {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            } else {
                $post['amtx'] = $total * $post['amtx'] / 100;
            }
        } else {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            }

        }

        if ($post['amty_type'] == '%') {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            } else {
                $post['amty'] = $total * $post['amty'] / 100;
            }

        } else {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            }

        }

        $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst'];

        $pdata = array(
            'sr_no' => $post['srno'],
            'voucher_type' => $post['voucher_type'],
            'weaver_invoice' => $post['weaver_invoice'],
            'challan_no' => $post['challan_no'],
            'credit_note' => $post['credit_note'],
            'date' => db_date($post['date']),
            'purchase_type' => $post['purchase_type'],
            'transport_mode' => $post['trasport_mode'],
            'party_name' => @$post['account'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'acc_state' => $post['acc_state'],
            'total_amount' => $total_amt,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amtx' => $amtx,
            'amtx_type' => $post['amtx_type'],
            'amty' => $amty,
            'cess_type' => $post['cess_type'],
            'cess' => $post['cess'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'net_amount' => $netamount,
        );

        // get challan no from invoice to insert in returnTaka table // 
        $gmodel =new GeneralModel();
        $gray = $gmodel->get_data_table('grey',array('id'=>$post['weaver_invoice']),'challan_no');
        // print_r($gray['challan_no']);exit;
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('retGrayFinish_item');
                $gmodel = new GeneralModel();

                for ($i = 0; $i < count($pid); $i++) {

                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    $item_data = array(
                        'purchase_type' => $post['purchase_type'],
                        'type' => $post['type'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'gst' => $post['igst'][$i],
                        'cut' => $post['cut'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'subtotal' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                    );

                    $delmill_ids = explode(',', $post['need_toDelete'][$i]);
                    $ret_takaTb_ids = explode(',', $post['ret_takaTb_ids'][$i]);
                    $greyTakaTb_ids = explode(',', $post['greyTakaTb_ids'][$i]);
                    if (!empty($post['need_toDelete'][$i])) {
                        foreach ($delmill_ids as $ret_id) {
                            $gmodel->update_data_table('retGrayFinish_taka', array('id' => $ret_id), array('is_delete' => 1));
                            $gray_challan  = $gmodel->get_data_table('retGrayFinish_taka', array('id' => $ret_id), '*');
                            $gmodel->update_data_table('greyChallan_taka', array('voucher_id' => $gray['challan_no'],'tr_id_item'=>$gray_challan['tr_id_item'],'taka_no'=>$gray_challan['taka_no']), array('is_return' => 0));
                        }
                    }
                    
                    $ret_item = $gmodel->get_data_table('retGrayFinish_item', array('voucher_id' => $post['id'], 'pid' => $pid[$i]), 'id');

                    foreach ($ret_takaTb_ids as $retId) {
                        $gmodel->update_data_table('retGrayFinish_taka', array('id' => $retId), array('voucher_id' => $post['id'], 'item_id' => $ret_item['id'] ,'purchase_invoice_id' =>$post['weaver_invoice'],'purchase_challan_id' =>$gray['challan_no']));
                    }

                    // Till now not put is_return  = 1  if we want then uncomment
                    
                    foreach ($greyTakaTb_ids as $greyid) {
                        $gmodel->update_data_table('greyChallan_taka', array('id' => $greyid), array('is_return' => 1));
                    }

                    $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                    $res = $item_builder->update($item_data);
                }

                $builder = $db->table('retGrayFinish');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'pid' => $post['pid'][$i],
                        'purchase_type' => $post['purchase_type'],
                        'type' => $post['type'][$i],
                        'type' => $post['type'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'gst' => $post['igst'][$i],
                        'cut' => $post['cut'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'subtotal' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                  
                    $item_builder = $db->table('retGrayFinish_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $ret_takaTb_ids = explode(',', $post['ret_takaTb_ids'][$i]);
                    $greyTakatbID = explode(',', $post['greyTakaTb_ids'][$i]);

                    for ($j = 0; $j < count($ret_takaTb_ids); $j++) {
                        $taka_builer = $db->table('retGrayFinish_taka');
                        $taka_builer->where('id', $ret_takaTb_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'item_id' => $item_id , 'purchase_type' =>$post['purchase_type'] ,'purchase_invoice_id' =>$post['weaver_invoice'],'purchase_challan_id' =>$gray['challan_no']));
                    }

                    for($k = 0; $k < count($greyTakatbID); $k++) {
                        $taka_builer = $db->table('greyChallan_taka');
                        $taka_builer->where('id', $greyTakatbID[$k]);
                        $taka_builer->update(array('is_return' => 1));
                    }

                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }
    
    public function insert_edit_MillSaleReturn($post)
    {
        if(!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillReturn');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        $discount = $post['discount'];
        $amtx = $post['amtx'];
        $amty = $post['amty'];

        $total = 0.0;

        for ($i = 0; $i < count($post['pid']); $i++) {
            $total += $post['subtotal'][$i];
        }
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                if ($post['discount'] > 0) {
                    $post['discount'] = $total * $post['discount'] / 100;
                    $total = $total -$post['discount'];
                    // for ($i = 0; $i < count($pid); $i++) {
                    //     $disc_amt = 0;
                    //     $devide_disc = $post['discount'] / count($pid);
                    //     $total = $total - $devide_disc;
                    // }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] == 0;
            }

            if ($post['discount'] > 0) {
                $total = $total -$post['discount'];
                
                // $total = 0;
                // $devide_disc = $post['discount'] / count($pid);
                // for ($i = 0; $i < count($pid); $i++) {
                //     $disc_amt = 0;
                //     $total = $total - $devide_disc;
                // }
            }
        }

        if ($post['amtx_type'] == '%') {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            } else {
                $post['amtx'] = $total * $post['amtx'] / 100;
            }

        } else {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            }

        }

        if ($post['amty_type'] == '%') {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            } else {
                $post['amty'] = $total * $post['amty'] / 100;
            }

        } else {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            }

        }

        $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst'];
        
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'invoice_no' => $post['invoice_no'],
            'sr_no' => $post['srno'],
            'date' => db_date($post['date']),
            'transport_mode' => $post['trasport_mode'],
            'account' => @$post['account'],
            'gst' => @$post['gst'],
            'item_type' => @$post['item_type'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'vehicle' => @$post['vehicle'] ? @$post['vehicle'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'other' => @$post['other'] ? @$post['other'] : '',
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'acc_state' => $post['acc_state'],
            'total_amount' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amtx' => $amtx,
            'amtx_type' => $post['amtx_type'],
            'amty' => $amty,
            'cess_type' => $post['cess_type'],
            'cess' => $post['cess'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'net_amount' => $netamount,
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('saleMillReturn_Item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();
                    
                    $item_data = array(
                        'voucher_id' => $post['id'],
                        'pid' => $post['pid'][$i],
                        'item_type' => @$post['item_type'],
                        'saleTakatbID' => $post['ret_takaTb_ids'][$i],
                        'type' => $post['type'][$i],
                        'taka' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'gst' => $post['gst'][$i],
                        'price' => $post['price'][$i],
                        'subtotal' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                        'update_at' => date('Y-m-d H:i:s'),
                        'update_by' => session('uid'),
                    );
                    $delmill_ids = explode(',', $post['need_toDelete'][$i]);
                    $ret_takaTb_ids = explode(',', $post['ret_takaTb_ids'][$i]);
                    $greyTakaTb_ids = explode(',', $post['saleTakaTb_ids'][$i]);
                    if (!empty($post['need_toDelete'][$i])) {
                        foreach ($delmill_ids as $ret_id) {
                            $gmodel->update_data_table('saleMillReturn_taka', array('id' => $ret_id), array('is_delete' => 1));
                            $ret_TBtaka = $gmodel->get_data_table('saleMillReturn_taka', array('id' => $ret_id),'*');

                            $gmodel->update_data_table('saleMillChallan_taka', array('taka_no' => $ret_TBtaka['taka_no']), array('is_return' => 0   ));
                        }
                    }

                    $ret_item = $gmodel->get_data_table('saleMillReturn_Item', array('voucher_id' => $post['id'], 'pid' => $pid[$i]), 'id');

                    foreach ($ret_takaTb_ids as $retId) {
                        $gmodel->update_data_table('saleMillReturn_taka', array('id' => $retId), array('voucher_id' => $post['id'], 'sale_item_id' => $ret_item['id'] , 'item_type' => @$post['item_type']));
                    }
                    
                    // Till now not  put is_return  = 1  if we want then uncomment
                    
                    // foreach ($greyTakaTb_ids as $greyid) {
                    //     $gmodel->update_data_table('greyChallan_taka', array('id' => $greyid), array('is_return' => 1));
                    // }

                    $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                    $res = $item_builder->update($item_data);
                }

                $builder = $db->table('saleMillReturn');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'pid' => $post['pid'][$i],
                        'saleTakatbID' => $post['ret_takaTb_ids'][$i],
                        'item_type' => @$post['item_type'],
                        'type' => $post['type'][$i],
                        'taka' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'gst' => $post['gst'][$i],
                        'price' => $post['price'][$i],
                        'subtotal' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('saleMillReturn_Item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $ret_takaTb_ids = explode(',', $post['ret_takaTb_ids'][$i]);
                    $saleTakatbID = explode(',', $post['saleTakaTb_ids'][$i]);

                    for ($j = 0; $j < count($ret_takaTb_ids); $j++) {
                        $taka_builer = $db->table('saleMillReturn_taka');
                        $taka_builer->where('id', $ret_takaTb_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'sale_item_id' => $item_id ,'item_type'=>@$post['item_type']));
                    }

                    for($k = 0; $k < count($saleTakatbID); $k++) {
                        $taka_builer = $db->table('saleMillChallan_taka');
                        $taka_builer->where('id', $saleTakatbID[$k]);
                        $taka_builer->update(array('is_return' => 1));
                    }
                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function insert_edit_returnMill($post)
    {
        if(!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('return_mill');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        
        $pdata = array(
            'sr_no' => $post['srno'],
            'mill_challan' => $post['mill_challan'],
            'date' => db_date($post['date']),
            'transport_mode' => $post['trasport_mode'],
            'weaver_challan' => $post['weaver_challan'],
            'party_name' => @$post['account'],
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'acc_state' => $post['acc_state'],            
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('return_mill_item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();
                    

                    $item_data = array(
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'remark' => $post['remark'][$i],
                    );
                    $delmill_ids = explode(',', $post['need_toDelete'][$i]);
                    $ret_takaTb_ids = explode(',', $post['ret_takaTb_ids'][$i]);
                    $millTakaTb_ids = explode(',', $post['millTakaTb_ids'][$i]);
                    if (!empty($post['need_toDelete'][$i])) {
                        foreach ($delmill_ids as $ret_id) {
                            $milltaka = $gmodel->get_data_table('return_mill_taka', array('id' => $ret_id), 'millTaka_Id');
                            $gmodel->update_data_table('millChallan_taka', array('id' => @$milltaka['millTaka_Id']), array('is_return' => 0));
                            $gmodel->update_data_table('return_mill_taka', array('id' => $ret_id), array('is_delete' => 1));
                        }
                    }

                    $ret_item = $gmodel->get_data_table('return_mill_item', array('voucher_id' => $post['id'], 'pid' => $pid[$i]), 'id');

                    foreach ($ret_takaTb_ids as $retId) {
                        $gmodel->update_data_table('return_mill_taka', array('id' => $retId), array('voucher_id' => $post['id'], 'item_id' => $ret_item['id']));
                    }

                    // Till now not  put is_return  = 1  if we want then uncomment
                    
                    foreach ($millTakaTb_ids as $millid) {
                        $gmodel->update_data_table('millChallan_taka', array('id' => $millid), array('is_return' => 1));
                    }

                    $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                    $res = $item_builder->update($item_data);
                }

                $builder = $db->table('return_mill');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'pid' => $post['pid'][$i],
                        'type' => $post['type'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'price' => $post['price'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('return_mill_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $ret_takaTb_ids = explode(',', $post['ret_takaTb_ids'][$i]);
                    $millTakatbID = explode(',', $post['millTakaTb_ids'][$i]);

                    for ($j = 0; $j < count($ret_takaTb_ids); $j++) {
                        $taka_builer = $db->table('return_mill_taka');
                        $taka_builer->where('id', $ret_takaTb_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'item_id' => $item_id ));
                    }

                    for($k = 0; $k < count($millTakatbID); $k++) {
                        $taka_builer = $db->table('millChallan_taka');
                        $taka_builer->where('id', $millTakatbID[$k]);
                        $taka_builer->update(array('is_return' => 1));
                    }

                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function insert_edit_returnJobwork($post)
    {
        if(!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('return_jobwork');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        
        $pdata = array(
            'sr_no' => $post['srno'],
            'job_challan' => $post['job_challan'],
            'date' => db_date($post['date']),
            'transport_mode' => $post['trasport_mode'],
            'party_name' => @$post['account'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'acc_state' => $post['acc_state'],            
        );

        if (!empty($result_array)) {
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('return_jobwork_item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();
                    // print_r($getItem);exit;

                    $item_data = array(
                        'screen' => $post['screen'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'cut' => $post['cut'][$i],
                        'unit' => $post['unit'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'remark' => $post['remark'][$i],
                    );
                  
                    $ret_item = $gmodel->get_data_table('return_jobwork_item', array('voucher_id' => $post['id'], 'pid' => $pid[$i]), 'id');

                    $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                    $res = $item_builder->update($item_data);
                }

                $builder = $db->table('return_jobwork');
                
                if($result) {
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'pid' => $post['pid'][$i],
                        'type' => $post['type'][$i],
                        'screen' => $post['screen'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'cut' => $post['cut'][$i],
                        'unit' => $post['unit'][$i],
                        'ret_taka' => $post['ret_taka'][$i],
                        'ret_meter' => $post['ret_meter'][$i],
                        'price' => $post['price'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('return_jobwork_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();

                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function insert_edit_jobwork($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sendJobwork');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];

        $total = 0.0;

        $pdata = array(
            'sr_no' => $post['srno'],
            'date' => db_date($post['date']),
            'transport_mode' => $post['trasport_mode'],
            'account' => @$post['account'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            $item_builder = $db->table('sendJob_Item');
            $item_result = $item_builder->select('GROUP_CONCAT(pid) as item_id')->where(array("voucher_id" => $post['id']))->get();
            $getItm = $item_result->getRow();

            $getpid = explode(',', $getItm->item_id);
            $delete_itemid = array_diff($getpid, $pid);
            //$itemdata=0;
            $gmodel = new GeneralModel();
            if (!empty($delete_itemid)) {
                foreach ($delete_itemid as $key => $del_id) {
                    $item = $gmodel->get_data_table('sendJob_Item', array('pid' => $del_id, 'voucher_id' => $post['id']), 'sedJob_TakaId,id');
                    $sendJob_taka = explode(',', $item['sedJob_TakaId']);

                    foreach ($sendJob_taka as $senJobTakaTb_id) {
                        //get taka no so we can updatae millRec_taka table
                        $getTakaNo = $gmodel->get_data_table('sendJob_taka', array('id' => $senJobTakaTb_id), 'taka_no,type');

                        // Remove if rec taka qty is 0 form this voucher
                        $gmodel->update_data_table('sendJob_taka', array('id' => $senJobTakaTb_id), array('voucher_id' => 0, 'job_item_id' => 0));

                        if ($getTakaNo['type'] == "Mill Received") {
                            // set is_sendJob = 0  in millRec_taka table
                            $gmodel->update_data_table('millRec_taka', array('screen' => $del_id, 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sendJob' => 0));
                        }
                        if ($getTakaNo['type'] == "Finish Purchase") {

                            $gmodel->update_data_table('greyChallan_taka', array('tr_id_item' => $del_id, 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sendJob' => 0));
                        }

                    }

                    $del_data = array('is_delete' => '1');
                    $item_builder->where(array('id' => $item['id']));
                    $item_builder->update($del_data);
                }
            }

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('sendJob_Item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {

                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();
                    if (!empty($getItem)) {
                        $item = array(
                            'voucher_id' => $post['id'],
                            'sedJob_TakaId' => $post['sendJob_ids'][$i],
                            'pid' => $post['pid'][$i],
                            'type' => $post['type'][$i],
                            'unit' => $post['total_taka'][$i],
                            'meter' => $post['total_qty'][$i],
                            'cut' => $post['cut'][$i],
                            'pcs' => $post['pcs'][$i],
                            'sortage' => $post['sortage'][$i],
                            'price' => $post['price'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );

                        $oldIds = explode(',', $getItem->sedJob_TakaId);
                        $newIds = explode(',', $post['sendJob_ids'][$i]);
                       
                        // Update new Ids of millRec_taka
                        $update_arr_dif = array_diff($newIds, $oldIds);
                      
                        if (!empty($update_arr_dif)) {
                            foreach ($update_arr_dif as $sendJobTable_id) {
                                // get taka no so we can update millRec_taka table
                                $getTakaNo = $gmodel->get_data_table('sendJob_taka', array('id' => $sendJobTable_id), 'taka_no,type');

                                // update voucher Id and sendJob_taka id
                                $gmodel->update_data_table('sendJob_taka', array('id' => $sendJobTable_id), array('voucher_id' => $post['id'], 'job_item_id' => $getItem->id));

                                if ($getTakaNo['type'] == "Mill Received") {
                                    // set is_sendJob = 1  in millRec_taka table
                                    $gmodel->update_data_table('millRec_taka', array('screen' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sendJob' => 1));
                                }
                                if($getTakaNo['type'] == "Finish Purchase"){
                                    $gmodel->update_data_table('greyChallan_taka', array('tr_id_item' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sendJob' => 1));
                                }

                            }
                        }

                        $remove_arr_dif = array_diff($oldIds, $newIds);
                    
                        if (!empty($remove_arr_dif)) {
                            foreach ($remove_arr_dif as $senJobTakaTb_id) {
                                //get taka no so we can updatae millRec_taka table
                                $getTkNo = $gmodel->get_data_table('sendJob_taka', array('id' => $senJobTakaTb_id), 'taka_no,type');

                                // Remove if rec taka qty is 0 form this voucher
                                $gmodel->update_data_table('sendJob_taka', array('id' => $senJobTakaTb_id), array('voucher_id' => 0, 'job_item_id' => 0));

                                if ($getTkNo['type'] == "Mill Received") {
                                    // set is_sendJob = 0  in millRec_taka table
                                    $gmodel->update_data_table('millRec_taka', array('screen' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTkNo['taka_no']), array('is_sendJob' => 0));
                                }

                                if ($getTkNo['type'] == "Finish Purchase") {
                                    // set is_sendJob = 0  in greyChallan_taka table
                                    $gmodel->update_data_table('greyChallan_taka', array('tr_id_item' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTkNo['taka_no']), array('is_sendJob' => 0));
                                }

                            }
                        }
                       
                        $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                        $res = $item_builder->update($item);
                    } else {
                        $item = array(
                            'voucher_id' => $post['id'],
                            'sedJob_TakaId' => $post['sendJob_ids'][$i],
                            'pid' => $post['pid'][$i],
                            'type' => $post['type'][$i],
                            'unit' => $post['total_taka'][$i],
                            'meter' => $post['total_qty'][$i],
                            'cut' => $post['cut'][$i],
                            'pcs' => $post['pcs'][$i],
                            'sortage' => $post['sortage'][$i],
                            'price' => $post['price'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );

                        $item_builder = $db->table('sendJob_Item');
                        $result1 = $item_builder->insert($item);

                        $item_id = $db->insertID();
                        $sendJob_ids = explode(',', $post['sendJob_ids'][$i]);
                        // $greyTakatbID = explode(',',$post['greyTakaTb_ids'][$i]);

                        for ($j = 0; $j < count($sendJob_ids); $j++) {
                            $taka_builer = $db->table('sendJob_taka');
                            $taka_builer->where('id', $sendJob_ids[$j]);
                            $taka_builer->update(array('voucher_id' => $post['id'], 'job_item_id' => $item_id));
                        }

                        for ($j = 0; $j < count($sendJob_ids); $j++) {
                            $taka_builer = $db->table('sendJob_taka');
                            $taka_builer->select('*');
                            $taka_builer->where('id', $sendJob_ids[$j]);
                            $query = $taka_builer->get();
                            $res = $query->getRowArray();

                            if ($res['type'] == 'Mill Received') {
                                $builder = $db->table('millRec_taka');
                                $builder->where(array('screen' => $res['tr_id_item']));
                                $builder->where(array('voucher_id !=' => 0));
                                $builder->where(array('millRec_item !=' => 0));
                                $builder->where(array('is_delete' => 0));
                                $builder->where(array('taka_no' => $res['taka_no']));
                                $builder->update(array('is_sendJob' => 1));
                            }
                            if ($res['type'] == 'Finish Purchase') {
                                $builder = $db->table('greyChallan_taka');
                                $builder->where(array('tr_id_item' => $res['tr_id_item']));
                                $builder->where(array('voucher_id !=' => 0));
                                $builder->where(array('is_delete' => 0));
                                $builder->where(array('taka_no' => $res['taka_no']));
                                $builder->update(array('is_sendJob' => 1));
                            }
                        }
                    }
                }

                $builder = $db->table('sendJobwork');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'sedJob_TakaId' => $post['sendJob_ids'][$i],
                        'pid' => $post['pid'][$i],
                        'type' => $post['type'][$i],
                        'unit' => $post['total_taka'][$i],
                        'meter' => $post['total_qty'][$i],
                        'cut' => $post['cut'][$i],
                        'pcs' => $post['pcs'][$i],
                        'sortage' => $post['sortage'][$i],
                        'price' => $post['price'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('sendJob_Item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $sendJob_ids = explode(',', $post['sendJob_ids'][$i]);
                    // $greyTakatbID = explode(',',$post['greyTakaTb_ids'][$i]);

                    for ($j = 0; $j < count($sendJob_ids); $j++) {
                        $taka_builer = $db->table('sendJob_taka');
                        $taka_builer->where('id', $sendJob_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'job_item_id' => $item_id));
                    }

                    for ($j = 0; $j < count($sendJob_ids); $j++) {

                        $taka_builer = $db->table('sendJob_taka');
                        $taka_builer->select('*');
                        $taka_builer->where('id', $sendJob_ids[$j]);
                        $query = $taka_builer->get();
                        $res = $query->getRowArray();

                        if ($res['type'] == 'Mill Received') {
                            $builder = $db->table('millRec_taka');
                            $builder->where(array('screen' => $res['tr_id_item']));
                            $builder->where(array('voucher_id !=' => 0));
                            $builder->where(array('millRec_item !=' => 0));
                            $builder->where(array('is_delete' => 0));
                            $builder->where(array('taka_no' => $res['taka_no']));
                            $builder->update(array('is_sendJob' => 1));
                        }
                        if ($res['type'] == 'Finish Purchase') {
                            $builder = $db->table('greyChallan_taka');
                            $builder->where(array('tr_id_item' => $res['tr_id_item']));
                            $builder->where(array('voucher_id !=' => 0));
                            $builder->where(array('is_delete' => 0));
                            $builder->where(array('taka_no' => $res['taka_no']));
                            $builder->update(array('is_sendJob' => 1));
                        }
                    }
                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");

                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }

        return $msg;
    }

    public function insert_edit_MillSaleChallan($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillChallan');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];

        $total = 0.0;

        $pdata = array(
            'sr_no' => $post['srno'],
            'date' => db_date($post['challan_date']),
            'transport_mode' => $post['trasport_mode'],
            'account' => @$post['account'],
            'gst' => @$post['gst'],
            'item_type' => @$post['item_type'],
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'vehicle' => @$post['vehicle'] ? @$post['vehicle'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'other' => @$post['other'] ? @$post['other'] : '',
        );
        // echo '<pre>';print_r($pdata);exit;
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            $item_builder = $db->table('saleMillChallan_Item');
            $item_result = $item_builder->select('GROUP_CONCAT(pid) as item_id')->where(array("voucher_id" => $post['id']))->get();
            $getItm = $item_result->getRow();

            $getpid = explode(',', $getItm->item_id);
            $delete_itemid = array_diff($getpid, $pid);
            //$itemdata=0;
            $gmodel = new GeneralModel();
            if (!empty($delete_itemid)) {
                foreach ($delete_itemid as $key => $del_id) {
                    $item = $gmodel->get_data_table('saleMillChallan_Item', array('pid' => $del_id, 'voucher_id' => $post['id']), 'sale_TakaId,id');
                    $sendJob_taka = explode(',', $item['sale_TakaId']);

                    foreach ($sendJob_taka as $senJobTakaTb_id) {
                        //get taka no so we can updatae millRec_taka table
                        $getTakaNo = $gmodel->get_data_table('saleMillChallan_taka', array('id' => $senJobTakaTb_id), 'taka_no,type');

                        // Remove if rec taka qty is 0 form this voucher
                        $gmodel->update_data_table('saleMillChallan_taka', array('id' => $senJobTakaTb_id), array('voucher_id' => 0, 'sale_item_id' => 0));

                        if ($getTakaNo['type'] == "Mill Received") {
                            // set is_sendJob = 0  in millRec_taka table
                            $gmodel->update_data_table('millRec_taka', array('screen' => $del_id, 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sale' => 0));
                        }else{
                            $gmodel->update_data_table('greyChallan_taka', array('tr_id_item' => $del_id, 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sale' => 0));
                        }

                    }

                    $del_data = array('is_delete' => '1');
                    $item_builder->where(array('id' => $item['id']));
                    $item_builder->update($del_data);
                }
            }

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('saleMillChallan_Item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {

                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();
                    if (!empty($getItem)) {
                        $item = array(
                            'voucher_id' => $post['id'],
                            'sale_TakaId' => $post['saleMillTaka_ids'][$i],
                            'pid' => $post['pid'][$i],
                            'item_type' => @$post['item_type'],
                            'type' => $post['type'][$i],
                            'taka' => $post['total_taka'][$i],
                            'meter' => $post['total_qty'][$i],
                            'gst' => $post['gst'][$i],
                            'price' => $post['price'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );

                        $oldIds = explode(',', $getItem->sale_TakaId);
                        $newIds = explode(',', $post['saleMillTaka_ids'][$i]);
                       
                        // Update new Ids of millRec_taka
                        $update_arr_dif = array_diff($newIds, $oldIds);
                      
                        if (!empty($update_arr_dif)) {
                            foreach ($update_arr_dif as $sendJobTable_id) {
                                //get taka no so we can updatae millRec_taka table
                                $getTakaNo = $gmodel->get_data_table('saleMillChallan_taka', array('id' => $sendJobTable_id), 'taka_no,type');
                                // update voucher Id and sendJob_taka id
                                $gmodel->update_data_table('saleMillChallan_taka', array('id' => $sendJobTable_id), array('voucher_id' => $post['id'], 'sale_item_id' => $getItem->id , 'item_type' =>@$post['item_type']));

                                if ($getTakaNo['type'] == "Mill Received") {
                                    // set is_sendJob = 1  in millRec_taka table
                                    $gmodel->update_data_table('millRec_taka', array('screen' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sale' => 1));
                                }else{
                                    $gmodel->update_data_table('greyChallan_taka', array('tr_id_item' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTakaNo['taka_no']), array('is_sale' => 1));
                                }
                            }
                        }

                        $remove_arr_dif = array_diff($oldIds, $newIds);
                    
                        if (!empty($remove_arr_dif)) {
                            foreach ($remove_arr_dif as $senJobTakaTb_id) {
                                //get taka no so we can updatae millRec_taka table
                                $getTkNo = $gmodel->get_data_table('saleMillChallan_taka', array('id' => $senJobTakaTb_id), 'taka_no,type');

                                // Remove if rec taka qty is 0 form this voucher
                                $gmodel->update_data_table('saleMillChallan_taka', array('id' => $senJobTakaTb_id), array('voucher_id' => 0, 'sale_item_id' => 0));

                                if ($getTkNo['type'] == "Mill Received") {
                                    // set is_sale = 0  in millRec_taka table
                                    $gmodel->update_data_table('millRec_taka', array('screen' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTkNo['taka_no']), array('is_sale' => 0));
                                }else{
                                    $gmodel->update_data_table('greyChallan_taka', array('tr_id_item' => $post['pid'][$i], 'voucher_id !=' => 0, 'is_delete' => 0, 'taka_no' => $getTkNo['taka_no']), array('is_sale' => 0));
                                }

                            }
                        }
                       
                        $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                        $res = $item_builder->update($item);
                    } else {
                        $item = array(
                            'voucher_id' => $post['id'],
                            'sale_TakaId' => $post['saleMillTaka_ids'][$i],
                            'pid' => $post['pid'][$i],
                            'item_type' => @$post['item_type'],
                            'type' => $post['type'][$i],
                            'taka' => $post['total_taka'][$i],
                            'meter' => $post['total_qty'][$i],
                            'gst' => $post['gst'][$i],
                            'price' => $post['price'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );

                        $item_builder = $db->table('saleMillChallan_Item');
                        $result1 = $item_builder->insert($item);

                        $item_id = $db->insertID();
                        $sendJob_ids = explode(',', $post['saleMillTaka_ids'][$i]);
                        // $greyTakatbID = explode(',',$post['greyTakaTb_ids'][$i]);

                        for ($j = 0; $j < count($sendJob_ids); $j++) {
                            $taka_builer = $db->table('saleMillChallan_taka');
                            $taka_builer->where('id', $sendJob_ids[$j]);
                            $taka_builer->update(array('voucher_id' => $post['id'], 'sale_item_id' => $item_id ,'item_type' =>$post['item_type'] ));
                        }

                        for ($j = 0; $j < count($sendJob_ids); $j++) {
                            $taka_builer = $db->table('saleMillChallan_taka');
                            $taka_builer->select('*');
                            $taka_builer->where('id', $sendJob_ids[$j]);
                            $query = $taka_builer->get();
                            $res = $query->getRowArray();

                            if ($res['type'] == 'Mill Received') {
                                $builder = $db->table('millRec_taka');
                                $builder->where(array('screen' => $res['tr_id_item']));
                                $builder->where(array('voucher_id !=' => 0));
                                $builder->where(array('millRec_item !=' => 0));
                                $builder->where(array('is_delete' => 0));
                                $builder->where(array('taka_no' => $res['taka_no']));
                                $builder->update(array('is_sale' => 1));
                            }
                        }
                    }
                }

                $builder = $db->table('saleMillChallan');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'sale_TakaId' => $post['saleMillTaka_ids'][$i],
                        'pid' => $post['pid'][$i],
                        'item_type' => $post['item_type'],
                        'type' => $post['type'][$i],
                        'taka' => $post['total_taka'][$i],
                        'meter' => $post['total_qty'][$i],
                        'gst' => $post['gst'][$i],
                        'price' => $post['price'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('saleMillChallan_Item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $sendJob_ids = explode(',', $post['saleMillTaka_ids'][$i]);
                    // $greyTakatbID = explode(',',$post['greyTakaTb_ids'][$i]);

                    for ($j = 0; $j < count($sendJob_ids); $j++) {
                        $taka_builer = $db->table('saleMillChallan_taka');
                        $taka_builer->where('id', $sendJob_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'sale_item_id' => $item_id , 'item_type'=>$post['item_type']));
                    }

                    for ($j = 0; $j < count($sendJob_ids); $j++) {
                        $taka_builer = $db->table('saleMillChallan_taka');
                        $taka_builer->select('*');
                        $taka_builer->where('id', $sendJob_ids[$j]);
                        $query = $taka_builer->get();
                        $res = $query->getRowArray();

                        if ($res['type'] == 'Mill Received') {
                            $builder = $db->table('millRec_taka');
                            $builder->where(array('screen' => $res['tr_id_item']));
                            $builder->where(array('voucher_id !=' => 0));
                            $builder->where(array('millRec_item !=' => 0));
                            $builder->where(array('is_delete' => 0));
                            $builder->where(array('taka_no' => $res['taka_no']));
                            $builder->update(array('is_sale' => 1));
                        }else{
                            $builder = $db->table('greyChallan_taka');
                            $builder->where(array('tr_id_item' => $res['tr_id_item']));
                            $builder->where(array('voucher_id !=' => 0));
                            $builder->where(array('MillItem_id !=' => 0));
                            $builder->where(array('is_delete' => 0));
                            $builder->where(array('taka_no' => $res['taka_no']));
                            $builder->update(array('is_sale' => 1));
                        }
                    }
                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");

                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }

        return $msg;
    }

    public function insert_edit_millRec($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millRec');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];
        $discount = $post['discount'];
        $amtx = $post['amtx'];
        $amty = $post['amty'];

        $total = 0.0;

        for ($i = 0; $i < count($post['pid']); $i++) {
            $total += $post['subtotal'][$i];
        }
        $total_amt = $total;
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                if ($post['discount'] > 0) {
                    $post['discount'] = $total * $post['discount'] / 100;
                    for ($i = 0; $i < count($pid); $i++) {
                        $disc_amt = 0;
                        $devide_disc = $post['discount'] / count($pid);
                        $total = $total - $devide_disc;
                    }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] == 0;
            }

            if ($post['discount'] > 0) {
                $total = 0;
                $devide_disc = $post['discount'] / count($pid);
                for ($i = 0; $i < count($pid); $i++) {
                    $disc_amt = 0;
                    $total = $total - $devide_disc;
                }
            }
        }

        if ($post['amtx_type'] == '%') {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            } else {
                $post['amtx'] = $total * $post['amtx'] / 100;
            }

        } else {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            }

        }

        if ($post['amty_type'] == '%') {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            } else {
                $post['amty'] = $total * $post['amty'] / 100;
            }

        } else {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            }

        }

        $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst'];

        $pdata = array(
            'sr_no' => $post['srno'],
            'challan_no' => $post['mill_challan'],
            'date' => db_date($post['date']),
            'transport_mode' => $post['trasport_mode'],
            'mill_ac' => @$post['account'],
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'lot_no' => @$post['lot_no'] ? @$post['lot_no'] : '',
            'is_lot_complete' => @$post['is_LotComplete'] ? @$post['is_LotComplete'] : '',
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'acc_state' => $post['acc_state'],
            'total_amount' => $total_amt,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amtx' => $amtx,
            'amtx_type' => $post['amtx_type'],
            'amty' => $amty,
            'cess_type' => $post['cess_type'],
            'cess' => $post['cess'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'net_amount' => $netamount,
        );
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {

                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('millRec_item');
                $gmodel = new GeneralModel();
                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    $item = array(
                        'millRecTb_ids' => $post['MillRecTB_id'][$i],
                        'type' => $post['type'][$i],
                        'screen' => $post['screen'][$i],
                        'gst' => $post['igst'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_pcs' => $post['return_taka'][$i],
                        'ret_meter' => $post['return_meter'][$i],
                        'rec_pcs' => $post['rec_taka'][$i],
                        'rec_meter' => $post['rec_meter'][$i],
                        'amount' => $post['subtotal'][$i],
                        'cut' => $post['cut'][$i],
                        'taka_tp' => $post['taka_tp'][$i],
                        'update_at' => date('Y-m-d H:i:s'),
                        'update_by' => session('uid'),
                    );

                    $Rec_Millids = explode(',', $getItem->millRecTb_ids);
                    $NewRec_Millids = explode(',', $post['MillRecTB_id'][$i]);

                    // Update new Ids of millRec_taka
                    $update_arr_dif = array_diff($NewRec_Millids, $Rec_Millids);

                    if (!empty($update_arr_dif)) {
                        foreach ($update_arr_dif as $MilRecTable_id) {
                            // get Millchallan table ID
                            $millChallanTaka = $gmodel->get_data_table('millRec_taka', array('id' => $MilRecTable_id), 'millTaka_Id');

                            // update voucher Id and MillItemtable id
                            $gmodel->update_data_table('millRec_taka', array('id' => $MilRecTable_id), array('voucher_id' => $post['id'], 'millRec_item' => $getItem->id, 'screen' => $post['screen'][$i]));

                            // set is_rec_mill = 1  in millchallantaka table
                            $gmodel->update_data_table('millChallan_taka', array('id' => $millChallanTaka['millTaka_Id']), array('is_rec_mill' => 1));
                        }
                    }
                    // update edit_qty=>received_qty
                    foreach ($NewRec_Millids as $row) {
                        // get value which we want to update at received_qty
                        $edit_qty = $gmodel->get_data_table('millRec_taka', array('id' => $row), 'edit_qty,received_qty');

                        // update edit_qty=>received_qty
                        $gmodel->update_data_table('millRec_taka', array('id' => $row), array('received_qty' => $edit_qty['edit_qty']));

                    }

                    $remove_arr_dif = array_diff($Rec_Millids, $NewRec_Millids);

                    if (!empty($remove_arr_dif)) {
                        foreach ($remove_arr_dif as $MilRecTable_id) {
                            // Remove if rec taka qty is 0 form this voucher
                            $gmodel->update_data_table('millRec_taka', array('id' => $MilRecTable_id), array('voucher_id' => 0, 'millRec_item' => 0, 'received_qty' => '', 'cut' => '', 'screen' => ''));

                            // Get millchallan ID to set is_send 0 in MillChallanTaka Table
                            $millChallanTaka = $gmodel->get_data_table('millRec_taka', array('id' => $MilRecTable_id), 'millTaka_Id');

                            // set is_rec_mill = 0  in millchallantaka table
                            $gmodel->update_data_table('millChallan_taka', array('id' => $millChallanTaka['millTaka_Id']), array('is_rec_mill' => 0));
                        }
                    }

                    if (!empty($post['need_toDelete'][$i])) {
                        foreach ($delmill_ids as $mill_id) {
                            $greytaka = $gmodel->get_data_table('millChallan_taka', array('id' => $mill_id), 'greyTaka_Id');
                            $gmodel->update_data_table('greyChallan_taka', array('id' => @$greytaka['greyTaka_Id']), array('is_send_mill' => 0));
                            $gmodel->update_data_table('millChallan_taka', array('id' => $mill_id), array('is_delete' => 1));
                        }
                    }

                    // $mill_item = $gmodel->get_data_table('mill_item',array('voucher_id'=>$post['id'],'pid'=>$pid[$i]),'id');

                    // foreach($mill_takaTb_ids as $millId){
                    //     $gmodel->update_data_table('millChallan_taka',array('id'=>$millId),array('voucher_id'=>$post['id'],'mill_item_id'=>$mill_item['id']));
                    // }

                    // foreach($greyTakaTb_ids as $greyid){
                    //     $gmodel->update_data_table('greyChallan_taka',array('id'=>$greyid),array('is_send_mill'=>1));
                    // }

                    $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                    $res = $item_builder->update($item);
                }

                $builder = $db->table('grey');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'millRecTb_ids' => $post['MillRecTB_id'][$i],
                        'pid' => $post['pid'][$i],
                        'type' => $post['type'][$i],
                        'screen' => $post['screen'][$i],
                        'gst' => $post['igst'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'meter' => $post['meter'][$i],
                        'ret_pcs' => $post['return_taka'][$i],
                        'ret_meter' => $post['return_meter'][$i],
                        'rec_pcs' => $post['rec_taka'][$i],
                        'rec_meter' => $post['rec_meter'][$i],
                        'amount' => $post['subtotal'][$i],
                        'cut' => $post['rec_cut'][$i],
                        'taka_tp' => $post['taka_tp'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );

                    $item_builder = $db->table('millRec_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $millRec_takaTb_ids = explode(',', $post['MillRecTB_id'][$i]);

                    $MillTakaTB_id = explode(',', $post['MillTakaTB_id'][$i]);

                    for($j = 0; $j < count($millRec_takaTb_ids); $j++) {
                        $taka_builer = $db->table('millRec_taka');
                        $taka_builer->where('id', $millRec_takaTb_ids[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'millRec_item' => $item_id, 'screen' => $post['screen'][$i]));
                    }
                    for ($k = 0; $k < count($MillTakaTB_id); $k++) {
                        $mill_builder = $db->table('millChallan_taka');
                        $mill_builder->where('id', $MillTakaTB_id[$k]);
                        $mill_builder->update(array('is_rec_mill' => 1));
                    }
                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");

                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function insert_edit_greyChallan($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey_challan');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $pid = $post['pid'];
        $price = $post['price'];

        $total = 0.0;

        for ($i = 0; $i < count($post['pid']); $i++) {
            $total += $post['subtotal'][$i];
        }
        // echo '<pre>';print_r($post);exit;
        $pdata = array(
            'sr_no' => $post['srno'],
            'voucher_type' => @$post['voucher_type'],
            'challan_no' => $post['challan_id'],
            'challan_date' => db_date($post['challan_date']),
            'purchase_type' => $post['purchase_type'],
            'transport_mode' => @$post['trasport_mode'] ? $post['trasport_mode'] : '',
            'party_name' => @$post['account'],
            'delivery_ac' => @$post['delivery_ac'] ? $post['delivery_ac'] : '',
            'delivery_code' => @$post['delivery_code'] ? $post['delivery_code'] : '',
            'warehouse' => @$post['warehouse'] ? $post['warehouse'] : '',
            'broker' => @$post['broker'] ? $post['broker'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'acc_state' => $post['acc_state'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'total_amount' => $total,
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));

                $result = $builder->Update($pdata);

                $item_builder = $db->table('grayChallan_item');
                $item_result = $item_builder->select('GROUP_CONCAT(pid) as pid')->where(array("voucher_id" => $post['id']))->get();
                $getItem = $item_result->getRow();

                $getpid = explode(',', $getItem->pid);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('pid' => $del_id, 'voucher_id' => $post['id']));
                        $item_builder->update($del_data);

                        $taka_builder = $db->table('greyChallan_taka');
                        $taka_builder->where(array('tr_id_item' => $del_id, 'voucher_id' => $post['id']));
                        $taka_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        //$qty = $post['qty'][$i] - $getItem->qty;
                        $item_data = array(
                            'voucher_id' => $post['id'],
                            'takaTB_ids' => $post['takaTb_id'][$i],
                            'pid' => $post['pid'][$i],
                            'purchase_type' => $post['purchase_type'],
                            'type' => $post['type'][$i],
                            'igst' => $post['igst'][$i],
                            'price' => $post['price'][$i],
                            'pcs' => $post['taka'][$i],
                            'cut' => $post['cut'][$i],
                            'meter' => $post['meter'][$i],
                            'amount' => $post['subtotal'][$i],
                            'extra' => $post['extra'][$i],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );

                        $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'voucher_id' => $post['id'],
                            'takaTB_ids' => $post['takaTb_id'][$i],
                            'pid' => $post['pid'][$i],
                            'purchase_type' => $post['purchase_type'],
                            'type' => $post['type'][$i],
                            'igst' => $post['igst'][$i],
                            'price' => $post['price'][$i],
                            'pcs' => $post['taka'][$i],
                            'cut' => $post['cut'][$i],
                            'meter' => $post['meter'][$i],
                            'amount' => $post['subtotal'][$i],
                            'extra' => $post['extra'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                        $item_id = $db->insertID();
                        $takaTb_id = explode(',', $post['takaTb_id'][$i]);

                        for ($j = 0; $j < count($takaTb_id); $j++) {
                            $taka_builer = $db->table('greyChallan_taka');
                            $taka_builer->where('id', $takaTb_id[$j]);
                            $taka_builer->update(array('voucher_id' => $post['id'], 'MillItem_id' => $item_id));
                        }
                    }
                   
                    $item_builder->where(array('voucher_id' => $post['id'], 'pid' => $post['pid'][$i]));
                    $result1 = $item_builder->update($item_data);
                }

                $builder = $db->table('grey_challan');
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

                for ($i = 0; $i < count($pid); $i++) {
                    $item = array(
                        'voucher_id' => $id,
                        'takaTB_ids' => $post['takaTb_id'][$i],
                        'pid' => $post['pid'][$i],
                        'type' => $post['type'][$i],
                        'purchase_type' => $post['purchase_type'],
                        'igst' => $post['igst'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['taka'][$i],
                        'cut' => $post['cut'][$i],
                        'meter' => $post['meter'][$i],
                        'amount' => $post['subtotal'][$i],
                        'extra' => $post['extra'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('grayChallan_item');
                    $result1 = $item_builder->insert($item);

                    $item_id = $db->insertID();
                    $takaTb_id = explode(',', $post['takaTb_id'][$i]);

                    for ($j = 0; $j < count($takaTb_id); $j++) {
                        $taka_builer = $db->table('greyChallan_taka');
                        $taka_builer->where('id', $takaTb_id[$j]);
                        $taka_builer->update(array('voucher_id' => $id, 'MillItem_id' => $item_id , 'purchase_type' => $post['purchase_type']));
                    }
                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    // public function insert_edit_taka($post){
    //     // print_r($post['taka_qty']);exit;
    //     foreach ($post['taka_qty'] as $key => $value) {
    //         if (empty($value)) {
    //            unset($post['taka_qty'][$key]);
    //         }
    //     }
    //     if (empty($post['taka_qty'])) {
    //         $msg = array('st' => 'fail', 'msg' => "Quintity Must Be Required");
    //         return $msg;
    //     }

    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder=$db->table('grey_taka');
    //     $builder->select('*');
    //     $builder->where('taka_no',@$post['taka_no'][$i]);
    //     $query = $builder->get();
    //     $result= $query->getResultArray();

    //     for($i = 0;$i<count($post['taka_no']);$i++){
    //         $pdata = array(
    //             'tr_id_item'=>$post['tr_id'],
    //             'taka_no'=>$post['taka_no'][$i],
    //             'weaver_taka'=>$post['weaver_taka'][$i],
    //             'quantity'=>$post['taka_qty'][$i],
    //             'accumulate'=>$post['accumulate'][$i],
    //             'cut'=>$post['taka_cut'][$i]
    //         );

    //         if(!empty($post['taka_id'][$i])){

    //             $pdata['update_by']=session('uid');
    //             $pdata['update_at']=date('Y-m-d H:i:s');
    //             $builder->where('id',@$post['taka_id'][$i]);
    //             $result1 = $builder->update($pdata);
    //             $taka_id = $post['taka_id'];

    //         }else{

    //             $pdata['created_by']=session('uid');
    //             $pdata['created_at']=date('Y-m-d H:i:s');
    //             $result1 = $builder->insert($pdata);
    //             $taka_id[] = $db->insertID();
    //         }
    //     }

    //     if($result1){
    //         $msg = array('st'=>'success' , 'msg'=>'Taka Updated Successfully..!' , 'takaTB_id'=>$taka_id);
    //     }else{
    //         $msg = array('st'=>'success' , 'msg'=>'Something Went Wrong..!');
    //     }
    //     return $msg;
    // }

    public function insert_edit_Challantaka($post)
    {
        // print_r($post['taka_qty']);exit;
        foreach ($post['taka_qty'] as $key => $value) {
            if (empty($value)) {
                unset($post['taka_qty'][$key]);
            }
        }
        if (empty($post['taka_qty'])) {
            $msg = array('st' => 'fail', 'msg' => "Quintity Must Be Required");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('greyChallan_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['taka_no'][$i]);
        $query = $builder->get();
        $result = $query->getResultArray();

        for ($i = 0; $i < count($post['taka_no']); $i++) {
            $pdata = array(
                'tr_id_item' => $post['tr_id'],
                'taka_no' => $post['taka_no'][$i],
                'weaver_taka' => $post['weaver_taka'][$i],
                'quantity' => $post['taka_qty'][$i],
                'accumulate' => $post['accumulate'][$i],
                'cut' => $post['taka_cut'][$i],
            );

            if (!empty($post['taka_id'][$i])) {

                $pdata['update_by'] = session('uid');
                $pdata['update_at'] = date('Y-m-d H:i:s');
                $builder->where('id', @$post['taka_id'][$i]);
                $result1 = $builder->update($pdata);
                $taka_id = $post['taka_id'];

            } else {

                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $result1 = $builder->insert($pdata);
                $taka_id[] = $db->insertID();
            }
        }

        if ($result1) {
            $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'takaTB_id' => $taka_id);
        } else {
            $msg = array('st' => 'success', 'msg' => 'Something Went Wrong..!');
        }
        return $msg;
    }

    public function insert_edit_Milltaka($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millChallan_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['taka_no'][$i]);
        $query = $builder->get();
        $result = $query->getResultArray();

        $gmodel = new GeneralModel();

        if (!isset($post['check'])) {
            $post['check'] = array();
        }

        $del_taka_id = array_diff($post['taka_no'], $post['check']);

        foreach ($del_taka_id as $row1) {
            $res = $gmodel->get_data_table('millChallan_taka', array('taka_no' => $row1, 'is_delete' => 0), '*');
            if (!empty($res)) {
                $need_del_ids[] = $res['id'];
            }
        }

        for ($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('millChallan_taka', array('taka_no' => $post['check'][$i], 'is_delete' => 0, 'voucher_id !=' => 0, 'mill_item_id !=' => 0), '*');
            if (empty($res)) {

                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'greyTaka_Id' => $post['taka_id'][$post['check'][$i]],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['taka_qty'][$post['check'][$i]],
                );

                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $result1 = $builder->insert($pdata);

                $taka_id[] = $db->insertID();
                $grey_takaID[] = $post['taka_id'][$post['check'][$i]];
            }
        }

        // if($result1){
        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'need_toDelete' => @$need_del_ids, 'takaTB_id' => @$taka_id, 'greyTakaID' => @$grey_takaID);
        // }else{
        //     $msg = array('st'=>'success' , 'msg'=>'Something Went Wrong..!');
        // }
        return $msg;
    }

    public function insert_edit_RetGrayFinish_taka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('retGrayFinish_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['taka_no'][$i]);
        $query = $builder->get();
        $result = $query->getResultArray();

        $gmodel = new GeneralModel();

        if (!isset($post['check'])) {
            $post['check'] = array();
        }
        $del_taka_id = array_diff($post['taka_no'], $post['check']);

        foreach($del_taka_id as $row1) {
            $res = $gmodel->get_data_table('retGrayFinish_taka', array('taka_no' => $row1, 'is_delete' => 0), '*');
            if(!empty($res)){
                $need_del_ids[] = $res['id'];
            }
        }
        
        for($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('retGrayFinish_taka', array('taka_no' => $post['check'][$i], 'is_delete' => 0, 'voucher_id !=' => 0, 'item_id !=' => 0), '*');
            if (empty($res)) {
                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'greyTaka_Id' => $post['taka_id'][$post['check'][$i]],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['taka_qty'][$post['check'][$i]],
                );
                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                
                $result1 = $builder->insert($pdata);
                $taka_id[] = $db->insertID();
                $grey_takaID[] = $post['taka_id'][$post['check'][$i]];
            }
        }
        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'need_toDelete' => @$need_del_ids, 'takaTB_id' => @$taka_id, 'greyTakaID' => @$grey_takaID);
        return $msg;
    }
    
    public function insert_edit_MillSale_taka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillReturn_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['taka_no'][$i]);
        $query = $builder->get();
        $result = $query->getResultArray();

        $gmodel = new GeneralModel();

        if (!isset($post['check'])) {
            $post['check'] = array();
        }
        $del_taka_id = array_diff($post['taka_no'], $post['check']);

        foreach($del_taka_id as $row1) {
            $res = $gmodel->get_data_table('saleMillReturn_taka', array('taka_no' => $row1, 'is_delete' => 0), '*');
            if(!empty($res)){
                $need_del_ids[] = $res['id'];
            }
        }
        
        for($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('saleMillReturn_taka', array('taka_no' => $post['check'][$i], 'is_delete' => 0, 'voucher_id !=' => 0, 'sale_item_id !=' => 0), '*');
            if (empty($res)) {
                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'saleTaka_Id' => $post['taka_id'][$post['check'][$i]],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['taka_qty'][$post['check'][$i]],
                );
                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                
                $result1 = $builder->insert($pdata);
                $taka_id[] = $db->insertID();
                $sale_takaID[] = $post['taka_id'][$post['check'][$i]];
            }
        }
        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'need_toDelete' => @$need_del_ids, 'takaTB_id' => @$taka_id, 'saleTakaID' => @$sale_takaID);
        return $msg;
    }

    public function insert_edit_Mill_ReturnTaka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('return_mill_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['taka_no'][$i]);
        $query = $builder->get();
        $result = $query->getResultArray();

        $gmodel = new GeneralModel();

        if (!isset($post['check'])) {
            $post['check'] = array();
        }

        $del_taka_id = array_diff($post['taka_no'], $post['check']);

        foreach($del_taka_id as $row1) {
            $res = $gmodel->get_data_table('return_mill_taka', array('taka_no' => $row1, 'is_delete' => 0), '*');
            if(!empty($res)){
                $need_del_ids[] = $res['id'];
            }
        }
        
        for($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('return_mill_taka', array('taka_no' => $post['check'][$i], 'is_delete' => 0, 'voucher_id !=' => 0, 'item_id !=' => 0), '*');
            if (empty($res)) {
                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'millTaka_Id' => $post['taka_id'][$post['check'][$i]],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['taka_qty'][$post['check'][$i]],
                );
                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                
                $result1 = $builder->insert($pdata);
                $taka_id[] = $db->insertID();
                $grey_takaID[] = $post['taka_id'][$post['check'][$i]];
            }
        }
        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'need_toDelete' => @$need_del_ids, 'takaTB_id' => @$taka_id, 'greyTakaID' => @$grey_takaID);
        return $msg;
    }

    public function insert_edit_MillRectaka($post)
    {

        if (empty($post['rec_qty'])) {
            $msg = array('st' => 'fail', 'msg' => "Quintity Must Be Required");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        for($i = 0; $i < count($post['taka_no']); $i++){

            $builder = $db->table('millRec_taka');
            $builder->select('*');
            $builder->where('taka_no', @$post['taka_no'][$i]);
            $builder->where('voucher_id !=', 0);
            $builder->where('is_delete', 0);
            $query = $builder->get();
            $result = $query->getResultArray();
      
            $pdata = array(
                'mill_item_id' => $post['mill_item_id'][$i],
                'tr_id_item' => $post['tr_id'],
                'millTaka_Id' => $post['taka_id'][$i],
                'taka_no' => $post['taka_no'][$i],
                'weaver_taka' => $post['weaver_taka'][$i],
                'quantity' => $post['quantity'][$i],
                // 'received_qty'=>$post['rec_qty'][$i],
                'cut' => $post['taka_cut'][$i],
            );

            if (!empty($result)) {
                $pdata['edit_qty'] = $post['rec_qty'][$i];
            } else {
                $pdata['received_qty'] = $post['rec_qty'][$i];
                $pdata['edit_qty'] = $post['rec_qty'][$i];
            }

            if (!empty($result)) {

                $pdata['update_by'] = session('uid');
                $pdata['update_at'] = date('Y-m-d H:i:s');
                $builder->where('id', @$post['millRecTakaID'][$i]);
                $result1 = $builder->update($pdata);

                if ($post['rec_qty'][$i] != 0 || $post['rec_qty'][$i] != '') {
                    $taka_id[] = $post['millRecTakaID'][$i];
                    
                    $milltaka_id[] = $post['taka_id'][$i];
                }
                
            } else {

                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                if ($post['rec_qty'][$i] != '') {
                    $result1 = $builder->insert($pdata);
                    $taka_id[] = $db->insertID();
                    $milltaka_id[] = $post['taka_id'][$i];
                }
            }
        }

        if ($result1) {
            $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'MillRecTB_id' => $taka_id, 'milltaka_id' => $milltaka_id);
        } else {
            $msg = array('st' => 'success', 'msg' => 'Something Went Wrong..!');
        }
        return $msg;
    }

    public function insert_edit_SendJobTaka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sendJob_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['']);
        $query = $builder->get();
        $result = $query->getResultArray();

        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('sendJob_taka', array('taka_no' => $post['check'][$i], 'type' => $post['type'][$post['check'][$i]], 'is_delete' => 0, 'voucher_id !=' => 0), '*');
            if (empty($res)) {
                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['qty'][$post['check'][$i]],
                    'type' => $post['type'][$post['check'][$i]],
                );

                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $result1 = $builder->insert($pdata);

                $sendJob_ids[] = $db->insertID();
            } else {
                $oldSenJob_ids[] = $res['id'];
            }

        }
        $final_ids = array();
        if(empty($sendJob_ids)){
            $sendJob_ids = array();
        }
        if (!empty($oldSenJob_ids)) {
            $final_ids = array_merge($sendJob_ids, $oldSenJob_ids);
        } else {
            $final_ids = $sendJob_ids;
        }

        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'sendJob_ids' => @$final_ids);
        return $msg;
    }

    public function insert_edit_SaleTaka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillChallan_taka');
        // $builder->select('*');
        // $builder->where('taka_no', @$post['']);
        // $query = $builder->get();
        // $result = $query->getResultArray();

        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('saleMillChallan_taka', array('taka_no' => $post['check'][$i], 'type' => $post['type'][$post['check'][$i]], 'is_delete' => 0, 'voucher_id !=' => 0), '*');
            if (empty($res)) {
                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['qty'][$post['check'][$i]],
                    'type' => $post['type'][$post['check'][$i]],
                );

                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                $result1 = $builder->insert($pdata);

                $saleMillTaka_ids[] = $db->insertID();
            } else {
                $oldSaleMillTaka_ids[] = $res['id'];
            }

        }
        $final_ids = array();
        if(empty($saleMillTaka_ids)){
            $saleMillTaka_ids = array();
        }
        if (!empty($oldSaleMillTaka_ids)) {
            $final_ids = array_merge($saleMillTaka_ids, $oldSaleMillTaka_ids);
        } else {
            $final_ids = $saleMillTaka_ids;
        }

        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'saleMillTaka_ids' => @$final_ids);
        return $msg;
    }

    public function insert_edit_RecJobTaka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('recJob_taka');
        $builder->select('*');
        $builder->where('taka_no', @$post['']);
        $query = $builder->get();
        $result = $query->getResultArray();
        $gmodel = new GeneralModel();

        for ($i = 0; $i < count($post['check']); $i++) {
            $res = $gmodel->get_data_table('recJob_taka', array('taka_no' => $post['check'][$i], 'type' => $post['type'][$post['check'][$i]], 'is_delete' => 0, 'voucher_id !=' => 0), '*');
            if (empty($res)){
                $pdata = array(
                    'tr_id_item' => $post['tr_id'],
                    'sendJobTaka_ID' => $post['sendJobTaka_ID'][$post['check'][$i]],
                    'taka_no' => $post['taka_no'][$post['check'][$i]],
                    'weaver_taka' => $post['weaver_taka'][$post['check'][$i]],
                    'quantity' => $post['qty'][$post['check'][$i]],
                    'type' => $post['type'][$post['check'][$i]],
                );
                $pdata['created_by'] = session('uid');
                $pdata['created_at'] = date('Y-m-d H:i:s');
                // echo '<pre>';print_r($pdata);exit;
                $result1 = $builder->insert($pdata);

                $recJob_ids[] = $db->insertID();
            } else {
                $oldSenJob_ids[] = $res['id'];
            }
        }
        $final_ids = array();
        if(empty($recJob_ids)){
            $recJob_ids = array();
        }
        if (!empty($oldSenJob_ids)) {
            $final_ids = array_merge($recJob_ids, $oldSenJob_ids);
        } else {
            $final_ids = $recJob_ids;
        }

        $msg = array('st' => 'success', 'msg' => 'Taka Updated Successfully..!', 'recJob_ids' => @$final_ids);
        return $msg;
    }

    public function search_item_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('*');
        if(isset($post['type']) && $post['type'] != ''){
            $builder->where('type',$post['type']);
        }
        $builder->where('is_delete',0);
        if(!empty($post['searchTerm'])){
            $builder->like('name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        foreach ($getdata as $row) {

            $item_uom = explode(',', $row['uom']);
            $option = '';
            $gmodel = new GeneralModel();
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $option .= '<option value="' . $uom . '">' . $uom_name['code'] . '</option>';
            }

            $price_data = array(
                "id" => $row['id'],
                'sales_price' => $row['sales_price'],
                'purchase_cost' => $row['purchase_cost'],
                'igst' => $row['igst'],
                'cgst' => $row['cgst'],
                'sgst' => $row['sgst'],
                'brokrage' => $row['brokrage'],
            );

            $result[] = array(
                "text" => $row['name'],
                "hsn" => $row['hsn'],
                "id" => $row['id'],
                "price" => $price_data,
                "uom" => $option,
            );
        }
        
        return $result;
    }

    // public function get_millRec_data($post)
    // {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('millRec_item mi');
    //     $builder->select('mi.id as millRecItem_id,mi.voucher_id,mi.millRecTb_ids,mi.rec_meter,mi.rec_pcs,mi.taka_tp,ac.name as mill_name,dl.name as party_name');
    //     $builder->join('millRec mr', 'mr.id =' . $post['voucher_id']);
    //     $builder->join('account ac', 'ac.id =mr.mill_ac');
    //     $builder->join('account dl', 'dl.id =mr.delivery_code');
    //     $builder->where('mi.id', $post['millRecItem_id']);
    //     $query = $builder->get();
    //     $res['MillRec'] = $query->getRowArray();
    //     $rectaka_ids = explode(',', $res['MillRec']['millRecTb_ids']);

    //     $gmodel = new GeneralModel;

    //     foreach ($rectaka_ids as $id) {

    //         $rectaka = $gmodel->get_data_table('millRec_taka', array('id' => $id), 'received_qty,taka_no');
    //         $received_qty = $rectaka['received_qty'];
    //         $taka_tp = explode('+', $received_qty);
    //         $total_qty = 0;

    //         foreach ($taka_tp as $row) {
    //             $total_qty += $row;
    //         }

    //         $res['option'][] = '<option value="' . $id . '">TAKA:' . $rectaka['taka_no'] . ' QTY: ' . $total_qty . '</option>';
    //     }
    //     return $res;
    // }

    public function search_finish_item($term)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('*');
        if (!empty($term)) {
            $where = "(`code` LIKE '%" . $term . "%' OR  `name` LIKE '%" . $term . "%') AND `is_delete` = '0' AND `type` = 'Finish'";
        }else{
            $where = "`is_delete` = '0' AND `type` = 'Finish'";
        }
        $builder->where($where);
        $builder->limit(10);
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        $gmodel = new GeneralModel();

        foreach ($getdata as $row) {
            $item_uom = explode(',', $row['uom']);
            $option = '';

            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $option .= '<option value="' . $uom . '">' . $uom_name['code'] . '</option>';
            }

            $builder = $db->table('millRec_taka');
            $builder->select('SUM(received_qty) as total_rec_qty , COUNT(screen) as unit_count');
            $builder->where(array('screen' => $row['id']));
            $builder->where('voucher_id !=', 0);
            $builder->where('millRec_item !=', 0);
            $builder->where('is_delete', 0);
            $query = $builder->get();
            $res1 = $query->getRow();

            $builder = $db->table('greyChallan_taka');
            $builder->select('SUM(quantity) as total_rec_qty , COUNT(taka_no) as unit_count');
            $builder->where(array('tr_id_item' => $row['id']));
            $builder->where('voucher_id !=', 0);
            $builder->where('is_sale', 0);
            $builder->where('is_return', 0);
            $builder->where('is_delete', 0);
            $query = $builder->get();
            $res2 = $query->getRow();

            $total_mtr = (float)$res1->total_rec_qty + (float)$res2->total_rec_qty;
            $total_taka = (float)$res1->unit_count + (float)$res2->unit_count;


            $result[] = array(
                "text" => $row['name'] . ' ~ ' . $total_taka . '(' . (($total_mtr != '') ? $total_mtr : 0) . ')',
                "hsn" => $row['hsn'],
                "stock" => $total_taka . '(' . (($total_mtr != '') ? $total_mtr : 0) . ')',
                "id" => $row['id'],
                "default_cut" => $row['default_cut'],
                "uom" => $option,
            );
        }
        return $result;
    }

    public function search_GrayFinish_sale_Item($term)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('*');
        if(!empty($term['searchTerm'])) {
            $where = "(`code` LIKE '%" . $term['searchTerm'] . "%' OR  `name` LIKE '%" . $term['searchTerm'] . "%') AND `is_delete` = '0' ";
        }else{
            $where = " `is_delete` = '0' ";
        }
        if($term['type'] != ''){
            $where .= ' and `type` = "'.$term['type'].'"';
        }
       
        $builder->where($where);
        $builder->limit(10);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        // echo $db->getLastQuery();exit;

        
        //print_r($getdata);exit;
        
        $result = array();
        $gmodel = new GeneralModel();

        foreach($getdata as $row) {

            $item_uom = explode(',', $row['uom']);
            $option = '';
            
            foreach($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $option .= '<option value="' . $uom . '">' . $uom_name['code'] . '</option>';
            }
            
            $builder = $db->table('millRec_taka');
            $db->setDatabase(session('DataSource'));
            $builder->select('SUM(received_qty) as total_rec_qty , COUNT(screen) as unit_count');
            $builder->where(array('screen' => $row['id']));
            $builder->where('voucher_id !=', 0);
            $builder->where('millRec_item !=', 0);
            $builder->where('is_delete', 0);
            $query = $builder->get();
            $res = $query->getRowArray();
            
            //... Output Need To Add  Finish Purcahse ...//

            $builder = $db->table('greyChallan_taka');
            $db->setDatabase(session('DataSource'));
            $builder->select('SUM(quantity) as total_rec_qty , COUNT(tr_id_item) as unit_count');
            $builder->where(array('tr_id_item' => $row['id']));
            $builder->where('voucher_id !=', 0);
            $builder->where('MillItem_id !=', 0);
            // $builder->where('purchase_type', 'Finish');
            $builder->where('is_delete', 0);
            $query = $builder->get();
            $finish_purchase = $query->getRowArray();
            
            $taka = $res['unit_count'] + $finish_purchase['unit_count'];
            $meter = $res['total_rec_qty'] + $finish_purchase['total_rec_qty'];
            
            $result[] = array(
                "text" => $row['name'] . ' ~ ' . $taka . '(' . (($meter != '') ? $meter : 0) . ')',
                "hsn" => $row['hsn'],
                "gst" => $row['igst'],
                "stock" => $taka . '(' . (($taka != '') ? $taka : 0) . ')',
                "id" => $row['id'],
                "uom" => $option,
            );
        }
        return $result;
    }

    public function search_finishJob_item($term)
    {
        // echo '<pre>';print_r($term);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('*');
        if(!empty($term) || $term != '' && isset($term)){
            echo 'jenith';exit;
            $where = "(`code` LIKE '%" . $term . "%' OR  `name` LIKE '%" . $term . "%') AND `is_delete` = '0' AND `type` = 'Jobwork'";
            $builder->where($where);
        }else{
          
            $builder->where(array('type'=>'Jobwork'));
        }
        $builder->limit(10);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();

        foreach ($getdata as $row) {
            $item_uom = explode(',', $row['uom']);
            $option = '';
            foreach ($item_uom as $uom) {
                $option .= '<option value="' . $uom . '">' . $uom . '</option>';
            }
            $price_data = array(
                "id" => $row['id'],
                'sales_price' => $row['sales_price'],
                'igst' => $row['igst'],
                'cgst' => $row['cgst'],
                'sgst' => $row['sgst'],
                'brokrage' => $row['brokrage'],
            );

            $result[] = array(
                "text" => $row['name'] . ' (' . $row['code'] . ')',
                "id" => $row['id'],
                "price" => $price_data,
                "uom" => $option,
            );
        }
        return $result;
    }

    public function search_jobwork_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sendJobwork j');
        $builder->select('j.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = j.account');
        
        if(!empty(@$post['searchTerm']) || @$post['searchTerm'] != ''){
            $builder->like('j.id',@$post['searchTerm']);
            $builder->orLike('ac.name',@$post['searchTerm']);
        }
        
        $builder->limit(10);
        $query = $builder->get();
        $getdata = $query->getResultArray();

        $result = array();
        $gmodel = new GeneralModel();

        foreach($getdata as $row){
            
            // $getaccount = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
            $gettransport = $gmodel->get_data_table('account', array('id' => $row['transport']), 'name');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name');

            // $row['account_name'] = @$getaccount['name'];
            $row['delivery_name'] = @$getdelivery['name'];
            $row['transport_name'] = @$gettransport['name'];
            $row['broker_name'] = @$getbroker['name'];
            $row['warehouse_name'] = @$getwarehouse['name'];


            $builder = $db->table('sendJob_Item ji');
            $builder->select('ji.*,i.uom as uom,i.name,i.code,i.id as item_id,i.hsn,i.igst');
            $builder->join('item i', 'i.id = ji.pid');
            $builder->where(array('voucher_id' => $row['id']));
            $query = $builder->get();
            $item = $query->getResultArray();
            
            $total_mtr = 0;
            $total_pcs = 0;
            // Return calculation START //
            $job_ret = $gmodel->get_array_table('return_jobwork',array('job_challan' => $row['id']),'id');
            
            
            if(!empty($job_ret)){

                $retitem = $db->table('return_jobwork_item');
                foreach($job_ret as $row_job_ret ){
                    $retitem->select('SUM(ret_taka) as return_pcs,SUM(ret_meter) as return_meter,pid');
                    $retitem->where(array('voucher_id' => $row_job_ret['id'], 'is_delete' => 0));
                    $retitem->groupBy('pid');
                    $query = $retitem->get();
                    $job_ret = $query->getRowArray();

                    $job_retitem[] =$job_ret;
                }
            }
            // Return calculation END //


            foreach ($item as $row1) {

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                    $option .= '<option value="' . $uom . '"  >' . $uom_name['code'] . '</option>';
                }

                $builder = $db->table('recJob_Item');
                $builder->select('pending');
                $builder->where('send_challan_no',$row['id']);
                $builder->where('pid',$row1['pid']);
                $builder->where('is_delete',0);
                $builder->orderBy('id', 'DESC');
                $query = $builder->get();
                $pending = $query   ->getRowArray();
                
                // $pending = $gmodel->get_data_table('recJob_Item',array(''=>,'pid'=>$row1['pid']),'pending');
                
                if(!empty($job_retitem)){
                    $row1['return_meter'] = 0;
                    $row1['return_pcs'] = 0;
                    foreach($job_retitem as $row_ret){
                        if($row_ret['pid'] == $row1['pid']){
                            $row1['return_meter'] += (float)$row_ret['return_meter'];
                            $row1['return_pcs'] += (float)$row_ret['return_pcs'];
                        }
                    }
                }else{
                    $row1['return_meter'] =0;
                    $row1['return_pcs'] =0;
                }

                if(!empty($pending['pending']) && $pending['pending'] != ''){
                    
                    $row1['pending'] = $pending['pending'];
                    $pcs_mtr = explode('-',$pending['pending']);
                    
                    $row1['remaining_pcs'] = (float)$pcs_mtr[0] - (float)$row1['return_pcs'];
                    $row1['remaining_mtr'] = (float)$pcs_mtr[1] - (float)$row1['return_meter'];

                }else{  
                    
                    $row1['remaining_pcs'] = (float)$row1['pcs'] - (float)$row1['return_pcs'];
                    $row1['remaining_mtr'] = (float)$row1['meter'] - (float)$row1['return_meter'];
                }
                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;

                $total_pcs +=$row1['unit'];
                $total_mtr += $row1['meter'];
            }
            $text = $row['id'] . ' (' . $row['account_name'] . ') /'.user_date($row['date']).'~'.$total_pcs.'('.$total_mtr.')';
            $result[] = array("text" => $text, "id" => $row['id'], 'job' => $row, 'item' => $item_arr);
            unset($item_arr);
        }
        // echo '<pre>';print_r($result);exit;
        return $result;
    }

    public function search_challan_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey_challan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        if (isset($post['challan_id'])) {
            $builder->where(array('sc.id' => $post['challan_id']));
        } else {
            if(isset($post['searchTerm'])){
                $sear_name =  (@$post['searchTerm']) ? @$post['searchTerm'] : 'A';
                $where = '(sc.challan_no LIKE "%'.$sear_name.'%"  OR ac.name LIKE "%'.$sear_name.'%")';
                $builder->where($where);
            }
        }
        if (isset($post['type'])) {
            if ($post['type'] != 'mill') {
                $builder->where(array('sc.is_invoiced' => 0));
            }
        }else{
            $builder->where(array('sc.is_invoiced' => 0));
        }
        $builder->where(array('sc.is_delete' => 0));        
        $builder->where(array('sc.is_cancle' => 0));        
        $query = $builder->get();
        $challan = $query->getResultArray();
        // echo $db->getLastQuery();exit;
        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            
            $row['delivery_ac_name'] = @$getdelivery_ac ['name'];
            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('grayChallan_item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.id as id,st.type as GiType');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();
            $total_mtr = 0;
            $total_pcs = 0;
            foreach ($item as $row1) {
                $total_mtr += ($row1['meter'] - $row1['cut']);
                $total_pcs += $row1['pcs'];

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                    $select = ($uom == $row1['GiType']) ? 'selected' : '';
                    $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;
                $send_meter = 0;
                $send_taka = 0;
                // When Send To Mill Minus Meter Which was Sended //

                if (isset($post['type'])) {
                    if ($post['type'] == 'mill') {
                        $ids = explode(',', $row1['takaTB_ids']);
                        foreach ($ids as $id) {
                            $taka = $gmodel->get_data_table('greyChallan_taka', array('id' => $id), 'quantity,is_send_mill,is_sale,is_return');
                            if(!empty($taka)){
                                if ($taka['is_send_mill'] == 1 || $taka['is_sale'] == 1 || $taka['is_return'] == 1) {
                                    $row1['meter'] -= $taka['quantity'];
                                    $row1['pcs'] -= 1;
                                    $send_meter += $taka['quantity'];
                                    $send_taka += 1;
                                    
                                }
                            }
                        }
                    }
                }
                if (isset($post['challan_id'])) {
                    $row1['all_greyTakaTb_ids'] = $row1['takaTB_ids'];
                    $row1['remark'] = '';
                    $row1['from_challan'] = 'from_challan';
                }
                $item_arr[] = $row1;

            }
            // with pcs/mtr
            // $text = $row['challan_no'] . ' (' . $row['account_name'] . ') /' . user_date($row['challan_date']) . '~ P/Q : ' . ((float)$total_pcs - (float)$send_taka). ' / ' . ((float)$total_mtr - (float)$send_meter);
            
            // without pcs/mtr
            $text = $row['challan_no'] . ' (' . $row['account_name'] . ') /' . user_date($row['challan_date']) ;

            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
            unset($item_arr);
        }

        return $result;
    }

    public function search_gray_finish_challan_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillChallan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');
        if (isset($post['challan_id'])) {
            $builder->where(array('sc.id' => $post['challan_id']));
        } else {
            $builder->like('sc.id', (@$post['searchTerm']) ? @$post['searchTerm'] : '1');
            $builder->orLike('ac.name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }

        $query = $builder->get();
        $challan = $query->getResultArray();
         
        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('saleMillChallan_Item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.id as id,st.type as GiType');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();
            
            $total_mtr = 0;
            $total_pcs = 0;

            foreach ($item as $row1) {
                $total_mtr += $row1['meter'];
                $total_pcs += $row1['taka'];

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                    $select = ($uom == $row1['GiType']) ? 'selected' : '';
                    $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;

                // When Send To Mill Minus Meter Which was Sended //

                // if (isset($post['type'])) {
                //     if ($post['type'] == 'mill') {
                //         $ids = explode(',', $row1['takaTB_ids']);
                //         foreach ($ids as $id) {
                //             $taka = $gmodel->get_data_table('greyChallan_taka', array('id' => $id), 'quantity,is_send_mill');
                //             if ($taka['is_send_mill'] == 1) {
                //                 $row1['meter'] -= $taka['quantity'];
                //                 $row1['pcs'] -= 1;
                //             }

                //         }
                //     }
                // }
                // if (isset($post['challan_id'])) {
                //     $row1['all_greyTakaTb_ids'] = $row1['takaTB_ids'];
                //     $row1['remark'] = '';
                //     $row1['from_challan'] = 'from_challan';
                // }
                $item_arr[] = $row1;

            }
            $text = $row['id'] . ' (' . $row['account_name'] . ') /' . user_date($row['date']) . '~ P/Q : ' . $total_pcs . ' / ' . $total_mtr;

            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
            unset($item_arr);
        }

        return $result;
    }

    public function search_invoice_data($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey sc');
        $builder->select('sc.*,ac.name as account_name,gc.challan_no as buyer_grayChallan');
        $builder->join('account ac', 'ac.id = sc.party_name');
        $builder->join('grey_challan gc', 'gc.id = sc.challan_no');
        $builder->where('sc.is_delete',0);
        $builder->where('sc.is_cancle',0);
        if(!empty($post['searchTerm'])){ 
            $sear_name =  (@$post['searchTerm']) ? @$post['searchTerm'] : 'A';
            $where = '(sc.inv_no LIKE "%'.$sear_name.'%"  OR ac.name LIKE "%'.$sear_name.'%")';
            $builder->where($where);
        }
        $query = $builder->get();
        $challan = $query->getResultArray();

        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $row['delivery_ac_name'] = @$getdelivery_ac['name'];
            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('gray_item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.id as id,st.type as GiType');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();
            
            $total_mtr = 0;
            $total_pcs = 0;
            foreach ($item as $row1) {
                $total_mtr += ($row1['meter'] - $row1['cut']);
                $total_pcs += $row1['pcs'];

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                    $select = ($uom == $row1['GiType']) ? 'selected' : '';
                    $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;

            }
     
            $text = $row['inv_no'] . ' (' . $row['account_name'] . ') /' . user_date($row['inv_date']) . '~ P/Q : ' . $total_pcs . ' / ' . $total_mtr;

            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
            unset($item_arr);
        }

        return $result;
    }


    public function search_MillSaleInvoice_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillInvoice sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');
        if(!empty($post['searchTerm'])){ 
            $builder->like('sc.id', (@$post['searchTerm']) ? @$post['searchTerm'] : '1');
            $builder->orLike('ac.name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }

        $query = $builder->get();
        $challan = $query->getResultArray();

        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('saleMillInvoice_Item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.id as id,st.type as GiType');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();
            
            $total_mtr = 0;
            $total_pcs = 0;
            foreach ($item as $row1) {
                $total_mtr += $row1['meter'];
                $total_pcs += $row1['taka'];

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                    $select = ($uom == $row1['GiType']) ? 'selected' : '';
                    $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;

            }
            $text = $row['id'] . ' (' . $row['account_name'] . ') /' . user_date($row['date']) . '~ P/Q : ' . $total_pcs . ' / ' . $total_mtr;

            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
            unset($item_arr);
        }

        return $result;
    }

    public function search_MillChallan_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('mill_challan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.mill_ac');
        if (!empty($post['searchTerm'])) {
            $builder->like('sc.sr_no', (@$post['searchTerm']) ? @$post['searchTerm'] : '1');
            $builder->orLike('ac.name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $builder->where('sc.is_delete',0);
        $builder->where('sc.is_cancle',0);
        $query = $builder->get();
        $challan = $query->getResultArray();

        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $row['delivery_name'] = @$getdelivery['name'];
            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $mill_ret = $gmodel->get_data_table('return_mill',array('mill_challan' => $row['id']),'id');
            
            if(!empty($mill_ret['id'])){

                $retitem = $db->table('return_mill_item');
                $retitem->select('SUM(ret_meter) as return_meter,SUM(ret_taka) as return_pcs,pid');
                $retitem->where(array('voucher_id' => $mill_ret['id'], 'is_delete' => 0));
                $retitem->groupBy('pid');
                $query = $retitem->get();
                $mill_retitem = $query->getResultArray();
            }

            $item_builder = $db->table('mill_item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();

            $total_pcs = 0;
            $total_mtr = 0;
            
            $item_arr = array();

            foreach ($item as $row1) {
                
                if(!empty($mill_retitem)){
                    foreach($mill_retitem as $row_ret){
                        if($row_ret['pid'] == $row1['pid']){
                            $row1['return_meter'] = $row_ret['return_meter'];
                            $row1['return_pcs'] = $row_ret['return_pcs'];
                        }
                    }
                }else{
                    $row1['return_meter'] =0;
                    $row1['return_pcs'] =0;
                }


                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                    $option .= '<option value="' . $uom . '"  >' . $uom_name['code'] . '</option>';
                }

                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;

                $total_pcs += $row1['pcs'];
                $total_mtr += $row1['meter'];
            }
            // echo '<pre>';print_r($item_arr);exit;

            $text = $row['sr_no'] . ' (' . $row['account_name'] . ') /' . user_date($row['challan_date']) . '~ P/Q : ' . $total_pcs . ' / ' . $total_mtr;
            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
        }
        // print_r($result);exit;
        return $result;
    }

    public function search_MillChallanForReturn_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('mill_challan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.mill_ac');

        if (!empty($post['searchTerm'])) {
            $builder->like('sc.id', (@$post['searchTerm']) ? @$post['searchTerm'] : '1');
            $builder->orLike('ac.name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }

        $builder->where('sc.is_delete',0);
        $builder->where('sc.is_cancle',0);
        $query = $builder->get();
        $challan = $query->getResultArray();

        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $row['delivery_name'] = @$getdelivery['name'];
            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('mill_item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();

            $total_pcs = 0;
            $total_mtr = 0;
            $item_arr = array();

            foreach ($item as $row1) {

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                    $option .= '<option value="' . $uom . '"  >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;

                $total_pcs += $row1['pcs'];
                $total_mtr += $row1['meter'];
            }

            $text = $row['id'] . ' (' . $row['account_name'] . ') /' . user_date($row['challan_date']) . '~ P/Q : ' . $total_pcs . ' / ' . $total_mtr;
            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
        }
        // print_r($result);exit;
        return $result;
    }

    public function search_JobChallanForReturn_data($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sendJobwork sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');

        if(!empty($post['searchTerm'])) {
            $builder->like('sc.id', (@$post['searchTerm']) ? @$post['searchTerm'] : '1');
            $builder->orLike('ac.name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $builder->where('sc.is_delete',0);
        $builder->where('sc.is_cancle',0);
        $query = $builder->get();
        $challan = $query->getResultArray();
         
        $gmodel = new GeneralModel();
        $result = array();

        foreach ($challan as $row) {

            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $row['delivery_name'] = @$getdelivery['name'];
            $row['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . @$getwarehouse['area'] . ')') : '';
            $row['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $row['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('sendJob_Item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();

            $total_pcs = 0;
            $total_mtr = 0;
            $item_arr = array();

            foreach ($item as $row1) {

                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                    $option .= '<option value="' . $uom . '"  >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;

                $total_pcs += $row1['pcs'];
                $total_mtr += $row1['meter'];
            }

            // $text = $row['id'] . ' (' . $row['account_name'] . ') /' . user_date($row['date']) . '~ P/Q : ' . $total_pcs . ' / ' . $total_mtr;
            $text = $row['id'] . ' (' . $row['account_name'] . ') /' . user_date($row['date']);

            $result[]= array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
        }
        return $result;
    }

    public function search_challan_mill($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey_challan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        if (!empty($post['searchTerm'])) {
            $builder->like('sc.challan_no', (@$post['searchTerm']) ? @$post['searchTerm'] : '1');
            $builder->orLike('ac.name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        }
        $query = $builder->get();
        $challan = $query->getResultArray();

        $gmodel = new GeneralModel();

        foreach ($challan as $row) {
            

            $row['lr_date'] = user_date($row['lr_date']);

            $item_builder = $db->table('grayChallan_item st');
            $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.id as id,st.type as GiType');
            $item_builder->join('item i', 'i.id = st.pid');
            $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item = $query->getResultArray();

            foreach ($item as $row1) {
                $item_uom = explode(',', $row1['uom']);
                $option = '';
                $gmodel = new GeneralModel();
                foreach ($item_uom as $uom) {
                    $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                    $select = ($uom == $row1['GiType']) ? 'selected' : '';
                    $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
                }
                $row1['uom_opt'] = $option;
                $item_arr[] = $row1;
            }
            $text = $row['challan_no'] . ' (' . $row['account_name'] . ') /' . $row['challan_date'];

            $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item_arr);
        }
        return $result;
    }

    public function search_challan_item($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $item_builder = $db->table('grayChallan_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.id as id,st.type as GiType');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $post['voucher_id'], 'st.is_delete' => 0));

        $query = $item_builder->get();
        $getdata['item'] = $query->getResultArray();

        foreach ($getdata['item'] as $row1) {
            $item_uom = explode(',', $row1['uom']);
            $option = '';
            $gmodel = new GeneralModel();
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                $select = ($uom == $row1['GiType']) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
            }
            $row1['uom_opt'] = $option;
            $item_arr[] = $row1;
            $taka_id_arr = explode(',', $row1['takaTB_ids']);
            $getdata['taka'] = array();
            for ($i = 0; $i < count($taka_id_arr); $i++) {

                $builder = $db->table('greyChallan_taka');
                $builder->select('*');
                $builder->where(array('id' => $taka_id_arr[$i]));
                $query1 = $builder->get();
                $getdata['taka'][$i] = $query1->getRowArray();
            }
            $text = $row1['name'];
            $result[] = array("text" => $text, "id" => $row1['id'], 'taka' => $getdata['taka']);
        }
        return $result;
    }

    // public function search_finish_mill($post)
    // {
    //     // print_r($post['searchTerm']);exit;
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('finish_mill fn');
    //     $builder->select('fn.*,ac.name as account_name');
    //     $builder->join('account ac', 'ac.id = fn.party_name');
    //     $builder->like('ac.name', @$post['searchTerm']);
    //     $builder->orLike('fn.id', @$post['searchTerm']);
    //     $query = $builder->get();
    //     $finish = $query->getResultArray();
    //     $gmodel = new GeneralModel();

    //     foreach ($finish as $row) {
    //         $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
    //         // $getmillac = $gmodel->get_data_table('account', array('id' => $row['mill_ac']), 'name');

    //         $row['delivery_name'] = @$getdelivery['name'];
    //         // $row['mill_ac_name'] = @$getmillac['name'];

    //         $item_builder = $db->table('milling_item st');
    //         $item_builder->select('st.*,i.*,st.id as Mitem_id, st.type as mitype');
    //         $item_builder->join('item i', 'i.id = st.screen');
    //         $item_builder->where(array('st.parent_id' => $row['id'], 'st.is_delete' => 0));
    //         $query = $item_builder->get();
    //         $item = $query->getResultArray();
    //         // echo '<pre>';print_r($item);
    //         $text = $row['id'] . ' (' . $row['account_name'] . ') /' . $row['date'];

    //         $result[] = array("text" => $text, "id" => $row['id'], 'job' => $row, 'item' => $item);
    //     }
    //     return $result;
    // }

    public function get_grey_challan_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.purchase_type",
            "sc.challan_date",
            "ac.name"
        );
        
        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.challan_date",
            "sc.purchase_type",
            "sc.party_name",
            "ac.name as account_name",
            "sc.delivery_code",
            "i.name as item_name",
            "SUM(gi.meter) as total_meter",
            "SUM(gi.pcs) as total_taka",
            "gi.price",
            "gi.amount",
            "sc.is_cancle",
            "sc.is_delete",
            "sc.is_invoiced",
        );

        $filter = $get['filter_data'];
        $tablename = "grey_challan sc join account ac on ac.id = sc.party_name ";
        $tablename .= " join grayChallan_item gi on gi.voucher_id = sc.id" ;
        $tablename .= " join item i on i.id = gi.pid" ;
        $where = ' and gi.is_delete =0';
        $where = ' and sc.is_delete =0';
        $where .= ' GROUP BY sc.id';
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/Add_grey_challan/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Grey Challan : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('Milling/Gray_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';

            // $send_to_mill = '<a href="' . url('Milling/send_to_mill/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-paper-plane"></i></i></a> ';
            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }

            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            // $btn .= $send_to_mill;
            $DataRow[] = '<a href="'.url('Milling/Add_grey_challan/'.$row['id']).'">'.$row['sr_no'].'</a>';
            $DataRow[] = $row['challan_no'];
            $DataRow[] = user_date($row['challan_date']);
            $DataRow[] = $row['purchase_type'];
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['total_taka'];
            $DataRow[] = $row['total_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = $row['amount'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_mill_challan_data($get)
    {
        $dt_search = array(
            "sc.sr_no",
            "sc.challan_no",
            "sc.challan_date",
            "ac.name",
            "gc.challan_no",
            "g.inv_no",
            "i.name"
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.challan_date",
            "sc.mill_ac",
            "i.name as item_name",
            "ac.name as mill_name",
            "SUM(mi.meter) as total_meter",
            "SUM(mi.pcs) as total_taka",
            "mi.price",
            "gc.challan_no as buyer_challan",
            "g.inv_no as buyer_invoice",
            "sc.delivery_code",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "mill_challan sc join account ac on ac.id = sc.mill_ac ";
        $tablename .= " join mill_item mi on mi.voucher_id = sc.id ";
        $tablename .= " join item i on i.id = mi.pid ";
        $tablename .= " join grey_challan gc on gc.id = sc.challan_no ";
        $tablename .= " join grey g on g.challan_no = gc.id ";
        $where = ' and sc.is_delete = 0';
        $where .= ' GROUP BY sc.id';

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {

            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            //$btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['is_cancle'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btn_cancle = '<a data-toggle="modal" target="_blank"   title="Cancle Challan: ' . $row['id'] . '"  onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-times-circle"></i></a> ';
            $btnedit = '<a href="' . url('Milling/Add_millSend/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Grey Challan : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';


            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }
            $DataRow[] = $row['sr_no'];
            $DataRow[] = $row['buyer_challan'];
            $DataRow[] = $row['buyer_invoice'];
            $DataRow[] = user_date($row['challan_date']);
            $DataRow[] = $row['mill_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['total_taka'];
            $DataRow[] = $row['total_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_millSale_invoice_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.date",
            "ac.name",
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.date",
            "sc.account",
            "ac.name as account_name",
            "sc.delivery_code",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "saleMillInvoice sc join account ac on ac.id = sc.account";
        // $tablename .= 'left join saleMillInvoice_Item si on si.voucher_id = sc.id ';

        $where = ' and sc.is_delete =0';
        // $where .= " and is_delete=0";
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {

            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");
            $gmodel = new GeneralModel();
            $getData = $gmodel->get_data_table('saleMillInvoice_Item',array('voucher_id'=>$row['id']),'SUM(meter) total_send');

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/add_Mill_SaleInvoice/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Grey Challan : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';

            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }

            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['sr_no'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = $getData['total_send'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    
    public function get_millSale_return_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.date",
            "ac.name",
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.date",
            "sc.account",
            "ac.name as account_name",
            "sc.delivery_code",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "saleMillReturn sc join account ac on ac.id = sc.account";

        $where = ' and sc.is_delete=0';
        
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {

            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");
            $gmodel = new GeneralModel();
            $getData = $gmodel->get_data_table('saleMillReturn_Item',array('voucher_id'=>$row['id']),'SUM(meter) total_ret');

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/add_Mill_SaleReturn/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Return  : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';


            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }

            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['sr_no'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = @$getData['total_ret'] ? $getData['total_ret'] : 0;
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_mill_rec_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.challan_date",
            "ac.name",
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.date",
            "gc.challan_no as buyer_challan",
            "g.inv_no as buyer_invoice",
            "sc.mill_ac",
            "ac.name as mill_name",
            "sc.delivery_code",
            "SUM(mi.rec_meter) as rec_meter",
            "SUM(mi.rec_pcs) as rec_taka",
            "mi.price",
            "sc.total_amount",
            "sc.lr_no",
            "i.name as item_name",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "millRec sc join account ac on ac.id = sc.mill_ac ";
        $tablename .= " left join millRec_item mi on mi.voucher_id= sc.id";
        $tablename .= " left join item i on i.id = mi.screen ";
        $tablename .= " left join mill_challan mc on mc.id = sc.challan_no ";
        $tablename .= " left join grey_challan gc on gc.id = mc.challan_no ";
        $tablename .= " left join grey g on g.challan_no = gc.id ";
        // $tablename .= " GROUP BY(sc.id)";
        // $where = ' and GROUP BY(sc.id)';
        $where = ' and sc.is_delete=0';
        $where .= ' GROUP BY sc.id';

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        foreach ($rResult['table'] as $row) {

            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/add_rec_mill/').$row['id'].'" data-target="#fm_model"  data-title="Edit Grey Challan : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            
            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }

            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['sr_no'];
            $DataRow[] = '<a href="'.url('/Milling/Add_millSend/').$row['challan_no'].'">'.$row['challan_no'].'</a>';
            $DataRow[] = $row['buyer_challan'];
            $DataRow[] = $row['buyer_invoice'];
            $DataRow[] = user_date($row['date']); 
            $DataRow[] = $row['mill_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['rec_taka'];
            $DataRow[] = $row['rec_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = $row['total_amount'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_grey_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.purchase_type",
            "sc.net_amount",
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "(select challan_no from grey_challan gc where gc.id = sc.challan_no) as challan_tbNo",
            "sc.inv_no",
            "sc.purchase_type",
            "sc.inv_date",
            "sc.party_name",
            "(select name from account ac where ac.id = sc.party_name) as account_name",
            "sc.delivery_code",
            "i.name as item_name",
            "SUM(gi.meter) as total_meter",
            "SUM(gi.pcs) as total_taka",
            "gi.price",
            "gi.amount as taxable_amt",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.net_amount",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "grey sc";
        $tablename .= " join gray_item gi on gi.voucher_id = sc.id" ;
        $tablename .= " join item i on i.id = gi.pid" ;
        $tablename .= " join grey_challan gc on gc.id = sc.challan_no";
        $where = " and sc.is_delete=0";
        $where .= ' GROUP BY sc.id';
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");
            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';            

            $btnedit = '<a href="' . url('Milling/Add_grey/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('Milling/Gray_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Group Name: "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            

            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  $btnview;
            }else{
                $btn =  $btnedit . $btnview;
            }
            
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }
            

            $DataRow[] = $row['sr_no'];
            $DataRow[] = $row['challan_tbNo'];
            $DataRow[] = $row['inv_no'];
            $DataRow[] = user_date($row['inv_date']);
            $DataRow[] = $row['purchase_type'];
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['total_taka'];
            $DataRow[] = $row['total_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = $row['taxable_amt'];
            $DataRow[] = $row['net_amount'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_retGrayFinish_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.date",
            "sc.purchase_type",
            "ac.name"
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.challan_no",
            "sc.purchase_type",
            "sc.date",
            "g.inv_no",
            "sc.party_name",
            "sc.purchase_type",
            "ac.name as account_name",
            "sc.delivery_code",
            "i.name as item_name",
            "SUM(ri.ret_taka) as total_taka",
            "SUM(ri.ret_meter) as total_meter",
            "ri.price",
            "ri.subtotal",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "retGrayFinish sc join account ac on ac.id = sc.party_name  join grey g on g.id = sc.weaver_invoice";
        $tablename .= " join retGrayFinish_item ri on ri.voucher_id = sc.id";
        $tablename .= " join item i on i.id = ri.pid";
        $where = " and sc.is_delete=0";
        $where .= ' and ri.is_delete =0';
        $where .= ' and sc.is_delete =0';
        $where .= ' GROUP BY sc.id';
        
        // $where .= " and is_delete=0";
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/Add_retGrayFinish/') . $row['id'] . '"   class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('Milling/Gray_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';

            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }
            

            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }
            
            $DataRow[] = $row['sr_no'];
            $DataRow[] = $row['inv_no'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['purchase_type'];
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['total_taka'];
            $DataRow[] = $row['total_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = $row['subtotal'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_return_mill_data($get)
    {
        $dt_search = array(
            "sc.id",
            "sc.sr_no",
            "sc.mill_challan",
            "sc.date",
            "ac.name",
            "gc.challan_no",
            "g.inv_no",
            "i.name"
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.mill_challan",
            "sc.date",
            "sc.party_name",
            "gc.challan_no as buyer_challan",
            "g.inv_no as buyer_invoice",
            "i.name as item_name",
            "ac.name as account_name",
            "SUM(rmi.ret_meter) as ret_meter",
            "SUM(rmi.ret_taka) as ret_taka",
            "rmi.price",
            "sc.delivery_code",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "return_mill sc join account ac on ac.id = sc.party_name ";
        $tablename .= " join return_mill_item rmi on rmi.voucher_id= sc.id";
        $tablename .= " join item i on i.id = rmi.pid ";
        $tablename .= " join mill_challan mc on mc.id = sc.mill_challan ";
        $tablename .= " join grey_challan gc on gc.id = mc.challan_no ";
        $tablename .= " join grey g on g.challan_no = gc.id ";
        $where = ' and sc.is_delete=0';
        $where .= ' GROUP BY sc.id';
        // $where .= " ";
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-times-circle"></i></a> ';
            $btnedit = '<a href="' . url('Milling/Add_returnMill/') . $row['id'] . '"   class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('Milling/Gray_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';


            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }
            
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }
            
            $DataRow[] = $row['sr_no'];
            $DataRow[] = $row['mill_challan'];
            $DataRow[] = $row['buyer_challan'];
            $DataRow[] = $row['buyer_invoice'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['ret_taka'];
            $DataRow[] = $row['ret_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_return_jobwork_data($get)
    {
        $dt_search = array(
            "ac.name",
            "ji.ret_taka",
            "ji.ret_meter",
            "i.name"
        );

        $dt_col = array(
            "sc.id",
            "sc.sr_no",
            "sc.job_challan",
            "sc.date",
            "sc.party_name",
            "ac.name as account_name",
            "sc.delivery_code",
            "i.name as item_name",
            "SUM(ji.ret_taka) as total_pcs",
            "SUM(ji.ret_meter) as total_meter",
            "ji.price",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "return_jobwork sc join account ac on ac.id = sc.party_name ";
        $tablename .= " join return_jobwork_item ji on ji.voucher_id = sc.id ";
        $tablename .= " join item i on i.id = ji.screen ";
        $where = ' and sc.is_delete=0';
        $where .= ' GROUP BY sc.id';
        // $where .= " and is_delete=0";
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/Add_return_jobwork/') . $row['id'] . '"   class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('Milling/Gray_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title=": "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            
            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }
           
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['sr_no'];
            $DataRow[] = $row['job_challan'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['total_pcs'];
            $DataRow[] = $row['total_meter'];
            $DataRow[] = $row['price'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_greyinvoice_data($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();

        $getdata['challan'] = $challan[0];

        $gmodel = new GeneralModel();
        foreach ($challan as $row) {
            $getchallan = $gmodel->get_data_table('grey_challan', array('id' => $row['challan_no']), '*');
            $challan_ac = $gmodel->get_data_table('account', array('id' => $getchallan['party_name']), 'name');

            $getaccount = $gmodel->get_data_table('account', array('id' => $row['party_name']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $warehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $broker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $voucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['challan_name'] = $getchallan['challan_no'] . '( ' . $challan_ac['name'] . ' ) /' . $getchallan['challan_date'];
            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['delivery_ac_name'] = @$getdelivery_ac['name'];
            $getdata['challan']['warehouse_name'] = @$warehouse['name'] ;
            $getdata['challan']['broker_name'] = @$broker['name'];
            $getdata['challan']['transport_name'] = @$gettransport['name'];
            $getdata['challan']['voucher_name'] = @$voucher['name'];
        }

        $item_builder = $db->table('gray_item st');
        $item_builder->select('st.*,i.uom,i.hsn,i.name,i.code,st.type as GiType');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();

        foreach ($items as $row) {
            $item_uom = explode(',', $row['uom']);
            $option = '';
            $gmodel = new GeneralModel();
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');

                $select = ($uom == $row['GiType']) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
            }
            $row['uom_opt'] = $option;
            $getdata['items'][] = $row;
        }

        return $getdata;
    }

    public function get_greychallan_data($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grey_challan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();

        $getdata['challan'] = $challan[0];

        $gmodel = new GeneralModel();

        foreach ($challan as $row) {

            $getaccount = $gmodel->get_data_table('account', array('id' => $row['party_name']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['delivery_ac_name'] = @$getdelivery_ac['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
            $getdata['challan']['voucher_name'] = @$getvoucher['name'] ? @$getvoucher['name'] : '';
        }
        
        $item_builder = $db->table('grayChallan_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,st.type as GiType');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdata['items'] = $query->getResultArray();

        foreach ($getdata['items'] as $row) {
            $item_uom = explode(',', $row['uom']);
            $option = '';
            $gmodel = new GeneralModel();
            foreach($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($uom == $row['GiType']) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' >' . $uom_name['code'] . '</option>';
            }
            $row['uom_opt'] = $option;
            $getdata['item'][] = $row;
        }
        // echo '<pre>';print_r($getdata);exit;
        return $getdata;
    }

    public function get_mill_challan_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('mill_challan sc');
        $builder->select('sc.*,ac.name as account_name,gc.challan_date as gray_challan_dt,gc.party_name as grey_acID');
        $builder->join('account ac', 'ac.id = sc.mill_ac');
        $builder->join('grey_challan gc', 'gc.id = sc.challan_no');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        $getdata['challan'] = $challan[0];
        $gmodel = new GeneralModel();
        foreach ($challan as $row) {
            $getaccount = $gmodel->get_data_table('account', array('id' => $row['mill_ac']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['millAc_name'] = @$getaccount['name'];
            $getdata['challan']['delivery_ac_name'] = @$getdelivery_ac['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

        }

        $item_builder = $db->table('mill_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdata['item'] = $query->getResultArray();

        // for getting challan_name and total QTY And PCS
        $total_qty = 0;
        $total_pcs = 0;
        foreach ($getdata['item'] as $itm) {
            $greyId_arr = explode(',', $itm['all_greyTakaTb_ids']);
            $total_Itemqty = 0;
            for ($j = 0; $j < count($greyId_arr); $j++) {

                $builder = $db->table('greyChallan_taka');
                $builder->select('quantity,cut');
                $builder->where('id', $greyId_arr[$j]);
                $query = $builder->get();
                $res = $query->getRowArray();

                $total_Itemqty += ($res['quantity'] - $res['cut']);
            }

            $total_qty += $total_Itemqty;
            $total_pcs += count($greyId_arr);
        }

        $grey_ac = $gmodel->get_data_table('account', array('id' => $getdata['challan']['grey_acID']), 'name');
        $weaver_challan = $gmodel->get_data_table('grey_challan', array('id' => $getdata['challan']['challan_no']), 'challan_no');

        $getdata['challan']['challan_name'] = $weaver_challan['challan_no'] . '(' . $grey_ac['name']
        . ') /' . user_date($getdata['challan']['gray_challan_dt']) . '~ P/Q :' . $total_pcs . '/' . $total_qty;

        return $getdata;
    }

    public function get_retGrayFinish_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('retGrayFinish sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];
        $gmodel = new GeneralModel();
        
        foreach ($challan as $row) {
            $getaccount = $gmodel->get_data_table('account', array('id' => $row['party_name']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['delivery_ac_name'] = @$getdelivery_ac['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
            $getdata['challan']['voucher_name'] = @$getvoucher['name'] ? @$getvoucher['name'] : '';
        }
        
        $item_builder = $db->table('retGrayFinish_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,st.gst as igst');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();

        // for getting challan_name and total QTY And PCS
        foreach ($items as $itm) {
            
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            $getdata['item'][] = $itm;
        }
        // echo '<pre>';print_r($getdata);exit;

        $account = $gmodel->get_data_table('grey', array('id' => $getdata['challan']['weaver_invoice']), 'inv_no,party_name,inv_date');

        $total = $gmodel->get_data_table('greyChallan_taka', array('voucher_id' => $getdata['challan']['challan_no'],'is_delete'=>0), 'COUNT(id) as total_taka,SUM(quantity) as total_meter');

        $account_name = $gmodel->get_data_table('account', array('id' => $account['party_name']), 'name');

        $getdata['challan']['weaver_invoice_name'] = $account['inv_no'] . '(' . $account_name['name']. ') /' . user_date($account['inv_date']) . '~ P/Q :' . $total['total_taka'] . '/' . $total['total_meter'];

        return $getdata;
    }

    public function get_MillSaleChallan_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillChallan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];
        
        $gmodel = new GeneralModel();
        
        foreach ($challan as $row) {
            $getaccount = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
        }
        
        $item_builder = $db->table('saleMillChallan_Item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();

        foreach ($items as $itm) {
            
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            $getdata['item'][] = $itm;
        }
        // echo '<pre>';print_r($getdata);exit;

        // $account = $gmodel->get_data_table('grey', array('id' => $getdata['challan']['weaver_invoice'], 'challan_no'=>$getdata['challan']['challan_no']), 'inv_no,party_name,inv_date');

        // $total = $gmodel->get_data_table('greyChallan_taka', array('voucher_id' => $getdata['challan']['challan_no'],'is_delete'=>0), 'COUNT(id) as total_taka,SUM(quantity) as total_meter');

        // $account_name = $gmodel->get_data_table('account', array('id' => $account['party_name']), 'name');

        // $getdata['challan']['weaver_invoice_name'] = $account['inv_no'] . '(' . $account_name['name']. ') /' . user_date($account['inv_date']) . '~ P/Q :' . $total['total_taka'] . '/' . $total['total_meter'];
        
        return $getdata;
    }

    public function get_MillSaleInvoice_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillInvoice sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];
        
        $gmodel = new GeneralModel();
        
        foreach ($challan as $row) {
            $getaccount = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
            $getdata['challan']['voucher_name'] = @$getvoucher['name'] ? @$getvoucher['name'] : '';
        }
        
        $item_builder = $db->table('saleMillInvoice_Item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();

        foreach ($items as $itm) {
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            $getdata['item'][] = $itm;
        }
        // echo '<pre>';print_r($getdata);exit;

        $account = $gmodel->get_data_table('saleMillChallan', array('id' => $getdata['challan']['challan']), 'id,account,date');

        $total = $gmodel->get_data_table('saleMillChallan_taka', array('voucher_id' => $getdata['challan']['challan'],'is_delete'=>0), 'COUNT(id) as total_taka,SUM(quantity) as total_meter');

        $account_name = $gmodel->get_data_table('account', array('id' => $account['account']), 'name');

        $getdata['challan']['challan_name'] = $account['id'] . '(' . $account_name['name']. ') /' . user_date($account['date']) . '~ P/Q :' . $total['total_taka'] . '/' . $total['total_meter'];
        // echo '<pre>';print_r($getdata);exit;
        return $getdata;
    }
    
    public function get_MillSaleReturn_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillReturn sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];
        
        $gmodel = new GeneralModel();
        
        foreach ($challan as $row) {

            $getaccount = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
            $getdata['challan']['vehicle_name'] = @$getvehicle['name'] ? @$getvehicle['name'] : '';
            $getdata['challan']['voucher_name'] = @$getvoucher['name'] ? @$getvoucher['name'] : '';
        }
        
        $item_builder = $db->table('saleMillReturn_Item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();

        foreach ($items as $itm) {
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            $getdata['item'][] = $itm;
        }
        // echo '<pre>';print_r($getdata);exit;

        $account = $gmodel->get_data_table('saleMillInvoice', array('id' => $getdata['challan']['invoice_no']), 'id,account,date,challan');

        $total = $gmodel->get_data_table('saleMillReturn_Item', array('voucher_id' => $getdata['challan']['invoice_no'],'is_delete'=>0), 'SUM(taka) as total_taka,SUM(meter) as total_meter');

        $account_name = $gmodel->get_data_table('account', array('id' => $account['account']), 'name');

        $getdata['challan']['invoice_name'] = $account['id'] . '(' . $account_name['name']. ') /' . user_date($account['date']) . '~ P/Q :' . $total['total_taka'] . '/' . $total['total_meter'];
        $getdata['challan']['challan'] = $account['challan'];
        // echo '<pre>';print_r($getdata);exit;
        return $getdata;
    }

    public function get_returnmill_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('return_mill sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];
        
        $gmodel = new GeneralModel();
        
        foreach ($challan as $row) {
            $getaccount = $gmodel->get_data_table('account', array('id' => $row['party_name']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
        }
        
        $item_builder = $db->table('return_mill_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $id, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();

        // echo '<pre>';print_r($items);exit;
        
        // for getting challan_name and total QTY And PCS
        foreach ($items as $itm) {
            
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            $getdata['item'][] = $itm;
        }

        $account = $gmodel->get_data_table('mill_challan', array('id' => $getdata['challan']['mill_challan']), 'id,mill_ac,challan_date');

        $total = $gmodel->get_data_table('millChallan_taka', array('voucher_id' => $getdata['challan']['mill_challan'],'is_delete'=>0), 'COUNT(id) as total_taka,SUM(quantity) as total_meter');

        $account_name = $gmodel->get_data_table('account', array('id' => $account['mill_ac']), 'name');

        $getdata['challan']['mill_challan_name'] = $account['id'] . '(' . $account_name['name']. ') /' . user_date($account['challan_date']) . '~ P/Q :' . $total['total_taka'] . '/' . $total['total_meter'];
        //  echo '<pre>';print_r($getdata);exit;
        
        return $getdata;
    }
    
    public function get_returnJobwork_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('return_jobwork sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.party_name');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];
        
        $gmodel = new GeneralModel();
        
        foreach ($challan as $row) {
            $getaccount = $gmodel->get_data_table('account', array('id' => $row['party_name']), 'name');
            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['account_name'] = @$getaccount['name'];
            $getdata['challan']['delivery_ac_name'] = @$getdelivery['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';
        }
        
        $item_builder = $db->table('return_jobwork_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,i.igst');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();
        
        // for getting challan_name and total QTY And PCS
        foreach ($items as $itm) {
            
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            $screen = $gmodel->get_data_table('item', array('id' => $itm['screen']), 'name');
            $itm['screen_name'] = $screen['name'];
            $getdata['item'][] = $itm;
        }
        // echo '<pre>';print_r($getdata);exit;

        $account = $gmodel->get_data_table('sendJobwork', array('id' => $getdata['challan']['job_challan']), 'id,account,date');

        $total = $gmodel->get_data_table('sendJob_Item', array('voucher_id' => $getdata['challan']['job_challan'],'is_delete'=>0), 'SUM(pcs) as total_taka,SUM(meter) as total_meter');

        $account_name = $gmodel->get_data_table('account', array('id' => $account['account']), 'name');

        $getdata['challan']['job_challan_name'] = $account['id'] . '(' . $account_name['name']. ') /' . user_date($account['date']) . '~ P/Q :' . $total['total_taka'] . '/' . $total['total_meter'];
        // echo '<pre>';print_r($getdata);exit;
        return $getdata;

    }

    public function get_mill_rec_byID($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millRec sc');
        $builder->select('sc.*,ac.name as account_name,mc.challan_date as mill_challan_dt,mc.mill_ac as mill_acID');
        $builder->join('account ac', 'ac.id = sc.mill_ac');
        $builder->join('mill_challan mc', 'mc.id = sc.challan_no');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['challan'] = $challan[0];

        $gmodel = new GeneralModel();
        foreach ($challan as $row) {

            $getaccount = $gmodel->get_data_table('account', array('id' => $row['mill_ac']), 'name');
            $getdelivery_ac = $gmodel->get_data_table('account', array('id' => $row['delivery_ac']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name,area');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');

            $getdata['challan']['millAc_name'] = @$getaccount['name'];
            $getdata['challan']['delivery_ac_name'] = @$getdelivery_ac['name'];
            $getdata['challan']['broker_name'] = @$getbroker['name'] ? @$getbroker['name'] : '';
            $getdata['challan']['warehouse_name'] = @$getwarehouse['name'] ? (@$getwarehouse['name'] . ' (' . $getwarehouse['area'] . ')') : '';
            $getdata['challan']['transport_name'] = @$gettransport['name'] ? @$gettransport['name'] : '';

        }
        

        $item_builder = $db->table('millRec_item st');
        $item_builder->select('st.*,i.uom,i.name,i.code,i.hsn,it.name as screen_name');
        $item_builder->join('item i', 'i.id = st.pid');
        $item_builder->join('item it', 'it.id = st.screen');
        $item_builder->where(array('st.voucher_id' => $row['id'], 'st.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();
        // for getting challan_name and total QTY And PCS
        // echo '<pre>';print_r($items);exit;

        $total_qty = 0;
        $total_pcs = 0;
        foreach ($items as $itm) {
            $millRecId_arr = explode(',', $itm['millRecTb_ids']);
            $total_Itemqty = 0;
            $item_uom = explode(',', $itm['uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;
            
            for ($j = 0; $j < count($millRecId_arr); $j++) {

                $builder = $db->table('millRec_taka');
                $builder->select('quantity,cut');
                $builder->where('id', $millRecId_arr[$j]);
                $query = $builder->get();
                $res = $query->getRowArray();
                
                $total_Itemqty += ($res['quantity'] - $res['cut']);
            }
            $total_qty += $total_Itemqty;
            $total_pcs += count($millRecId_arr);
            $getdata['item'][] = $itm;
        }
        $mill_ac = $gmodel->get_data_table('account', array('id' => $getdata['challan']['mill_acID']), 'name');

        // total_taka value are not with return_taka value minus so we remove P/Q  //

        // $getdata['challan']['challan_name'] = $getdata['challan']['challan_no'] . '(' . $mill_ac['name']
        // . ') /' . user_date($getdata['challan']['mill_challan_dt']) . '~ P/Q :' . $total_pcs . '/' . $total_qty;

        $getdata['challan']['challan_name'] = $getdata['challan']['challan_no'] . '(' . $mill_ac['name']
        . ') /' . user_date($getdata['challan']['mill_challan_dt']) ;

        // echo '<pre>';print_r($getdata);exit;

        return $getdata;
    }

    // public function get_grayTaka_data($ids)
    // {
    //     $id = explode(',', $ids);
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('grey_taka');

    //     for ($i = 0; $i < count($id); $i++) {
    //         $builder->select('*');
    //         $builder->where('id', $id[$i]);
    //         $query = $builder->get();
    //         $result[] = $query->getRowArray();
    //     }
    //     return $result;
    // }

    public function get_grayChallanTaka($tr_id, $voucher_id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('greyChallan_taka');
        $builder->select('*');
        $builder->where('voucher_id', $voucher_id);
        $builder->where('tr_id_item', $tr_id);
        $builder->where('is_delete', 0);
        $query = $builder->get();
        $res = $query->getResultArray();

        return $res;
    }

    public function get_grayChallanTaka_data($greychallan_id, $grey_item, $voucher_id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('grayChallan_item');
        $builder->select('takaTB_ids');
        $builder->where('voucher_id', $greychallan_id);
        $builder->where('pid', $grey_item);
        $query = $builder->get();
        $res = $query->getRowArray();

        $ids = explode(',', $res['takaTB_ids']);
    
        $gmodel = new GeneralModel();

        if ($voucher_id != '') {
            for ($i = 0; $i < count($ids); $i++) {
                $fin_id = $gmodel->get_data_table('millChallan_taka', array('voucher_id' => $voucher_id, 'greyTaka_Id' => $ids[$i]), 'greyTaka_Id');
                if(isset($fin_id['greyTaka_Id'])) {
                    $id[] = $fin_id['greyTaka_Id'];
                }
            }
        }

        $result = array();

        $builder = $db->table('greyChallan_taka');
        
        if ($voucher_id == '') {
            $id = $ids;
        }
        
        for ($i = 0; $i < count($id); $i++) {
            $builder->select('*');
            $builder->where('id', $id[$i]);
            if($voucher_id == '' ){
                $builder->where('is_send_mill', 0);
                $builder->where('is_return', 0);
                $builder->where('is_sale', 0);
            }else{
                $builder->where('is_return', 0);
                $builder->where('is_sale', 0);
            }
            $query = $builder->get();
            $resu = $query->getRowArray();
            if(!empty($resu)) {
                $result[] = $resu;
            }
        }

        // echo '<pre>';print_r($result);exit;
        return $result;
    }

    public function get_grayChallanTaka_return($voucher_id,$tr_id,$id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('greyChallan_taka');
        $builder->select('*');
        $builder->where('voucher_id',$voucher_id);
        $builder->where('tr_id_item',$tr_id);
        $builder->where('is_send_mill',0);
        $builder->where('is_sale',0);
        $query = $builder->get();
        $result = $query->getResultArray();
        
        
        $final_Taka = array();
        $total_taka = 0;
        $total_meter = 0;
        
        foreach($result as $row){

            $builder= $db->table('retGrayFinish_taka');
            $builder->select('*');
            $builder->where('taka_no',$row['taka_no']);            
            if($id != ''){
                $builder->where('voucher_id',$id);
            }else{
                $builder->where('voucher_id !=',0);
            }
            $builder->where('item_id !=',0);
            $builder->where('is_delete',0);
            $query = $builder->get();
            $result = $query->getRowArray();
            
            

            if($id != ''){
                if(!empty($result)){
                    $total_taka +=1; 
                    $total_meter +=$row['quantity']; 
                    $row['is_return'] = 1;
                }else{
                    $row['is_return'] = 0;
                }
                $final_Taka['taka'][] = $row;
            }else{
                if(empty($result)){
                    $final_Taka['taka'][] = $row;
                }
            }
            
        } 
        $final_Taka['total_taka'] =$total_taka; 
        $final_Taka['total_meter'] =$total_meter; 
       
        // echo '<pre>';print_r($final_Taka);exit;

        return $final_Taka;
    }
    
    public function get_MillSaleChallanTaka_return($voucher_id,$tr_id,$id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('saleMillChallan_taka');
        $builder->select('*');
        $builder->where('voucher_id',$voucher_id);
        $builder->where('tr_id_item',$tr_id);
        // $builder->where('is_return',0);
        $builder->where('is_delete',0);
        $query = $builder->get();
        $result = $query->getResultArray();

        // echo '<pre>';print_r($result);exit;
        $final_Taka = array();
        $total_taka = 0;
        $total_meter = 0;

        foreach($result as $row){

            $builder= $db->table('saleMillReturn_taka');
            $builder->select('*');
            $builder->where('taka_no',$row['taka_no']);            
            if($id != ''){
                $builder->where('voucher_id',$id);
            }else{
                $builder->where('voucher_id !=',0);
            }
            $builder->where('sale_item_id !=',0);
            $builder->where('is_delete',0);
            $query = $builder->get();
            $result1 = $query->getRowArray();
            
            if($id != ''){
                if(!empty($result1)){
                    $total_taka +=1; 
                    $total_meter +=$row['quantity']; 
                    $row['is_return'] = 1;
                }else{
                    $row['is_return'] = 0;
                }
                $final_Taka['taka'][] = $row;
            }else{
               
                if(empty($result1)){  
                    $final_Taka['taka'][] = $row;
                }
            }

        }
        $final_Taka['total_taka'] =$total_taka; 
        $final_Taka['total_meter'] =$total_meter; 
        // echo '<pre>';print_r($final_Taka);exit;
        if(empty($final_Taka['taka'])){
            $final_Taka['taka'] = array();
        }
        return $final_Taka;
    }

    public function get_MillChallanTaka_return($voucher_id,$tr_id,$id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millChallan_taka');
        $builder->select('*');
        $builder->where('voucher_id',$voucher_id);
        $builder->where('tr_id_item',$tr_id);
        $builder->where('is_rec_mill',0);
        if($id == '' ){
            $builder->where('is_return',0);
        }
        $builder->where('is_delete',0);
        $query = $builder->get();
        $result = $query->getResultArray();
        

        $final_Taka = array();
        $total_taka = 0;
        $total_meter = 0;

        foreach($result as $row){

            $builder= $db->table('return_mill_taka');
            $builder->select('*');
            $builder->where('taka_no',$row['taka_no']);            
            if($id != ''){
                $builder->where('voucher_id',$id);
            }else{
                $builder->where('voucher_id !=',0);
            }   
            $builder->where('item_id !=',0);
            $builder->where('is_delete',0);
            $query = $builder->get();
            $result = $query->getRowArray();
            
            if($id != ''){
                if(!empty($result)){
                    $total_taka +=1; 
                    $total_meter +=$row['quantity']; 
                    $row['is_return'] = 1;
                }else{
                    $row['is_return'] = 0;
                }
                $final_Taka['taka'][] = $row;
            }else{
                if(empty($result)){
                    $final_Taka['taka'][] = $row;
                }
            }
        } 
        $final_Taka['total_taka'] =$total_taka; 
        $final_Taka['total_meter'] =$total_meter; 
        if(!isset($final_Taka['taka'])){
            $final_Taka['taka'] = array();
        }
        return $final_Taka;
    }

    public function get_millChallanTaka_data($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millChallan_taka');
        $builder->select('*');
        $builder->where('voucher_id', $post['voucher_id']);
        $builder->where('tr_id_item', $post['item_id']);
        $builder->where('is_delete', 0);
        $builder->where('is_return', 0);
        $builder->where('is_rec_mill', 0);
        $query = $builder->get();
        $res = $query->getResultArray();
        
        $gmodel =new GeneralModel();
        $get_millRec  = $gmodel->get_array_table('millRec',array('challan_no'=>$post['voucher_id'],'is_delete'=>0,'is_cancle'=>0),'id');
        // echo '<pre>';print_r($get_millRec);exit;
    
        if($post['id'] == '') {
            if(!empty($get_millRec)){
                foreach($res as $row){
                    if($row['is_rec_mill'] == 0 ){
                        $pending_taka[] =$row;
                    }
                }
            }
            if(isset($pending_taka) && !empty($pending_taka)){
                $ress = $pending_taka;
            }else{
                $ress= $res;
            }
        }

        if ($post['id'] != '') {
            
            for ($i = 0; $i < count($res); $i++) {
                $rectaka = $gmodel->get_data_table('millRec_taka', array('voucher_id !='=>0,'millTaka_Id' => $res[$i]['id'], 'taka_no' => $res[$i]['taka_no'],'is_delete'=>0), 'voucher_id,cut,received_qty,id as millRecTakaID');
                
                if(!empty($rectaka)){
                    $res[$i]['cut'] = $rectaka['cut'];
                    $res[$i]['received_qty'] = $rectaka['received_qty'];
                    $res[$i]['millRecTakaID'] = $rectaka['millRecTakaID'];
                    if($rectaka['voucher_id'] != $post['id']){
                        $res[$i]['disabled'] = 1;
                    }else{
                        $res[$i]['disabled'] = 0;
                        $taka[] = $res[$i];
                    }
                }else{
                    $res[$i]['cut'] = '';
                    $res[$i]['received_qty'] = '';
                    $res[$i]['disabled'] = 0;
                    $taka[] = $res[$i];
                }
            }
        }else{
            $taka = $ress;
        }
        
        return $taka;   
        
    }

    public function get_FinishTaka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millRec_taka');
        $select = "'Mill Received' as type";
        $builder->select($select.',weaver_taka,taka_no,received_qty,is_sendJob,is_delete');
        $builder->where(array('screen' => $post['item_id']));
        $builder->where('voucher_id !=', 0);
        $builder->where('millRec_item !=', 0);
        $builder->where('is_delete', 0);
        $builder->where('is_sendJob', 0);
        $query = $builder->get();
        $mill = $query->getResultArray();

        $select = "'Finish Purchase' as type";
        $builder = $db->table('greyChallan_taka');
        $builder->select($select.',weaver_taka,taka_no,quantity as received_qty,is_sendJob,is_delete');
        $builder->where(array('tr_id_item' => $post['item_id']));
        $builder->where('voucher_id !=', 0);
        $builder->where('MillItem_id !=', 0);
        $builder->where('is_delete', 0);
        $builder->where('is_sale', 0);
        $builder->where('is_sendJob', 0);
        $builder->where('is_return', 0);
        $query = $builder->get();
        $purchase = $query->getResultArray();
        $millRec = array_merge($mill,$purchase);

        $gmodel = new GeneralModel();
        
        if (!empty(@$post['job_itemID'])) {
            $item = $gmodel->get_data_table('sendJob_Item', array('id' => $post['job_itemID']), 'sedJob_TakaId');
            $ids = explode(',', $item['sedJob_TakaId']);

            foreach ($ids as $row) {
                $builder = $db->table('sendJob_taka');
                $builder->select('id,taka_no,type');
                $builder->where('id', $row);
                $builder->where('is_delete', 0);
                $query = $builder->get();
                $res[] = $query->getRowArray();
            }

            foreach ($res as $row1) {

                if ($row1['type'] == 'Mill Received') {
                    $select = "'Mill Received' as type";
                    $builder = $db->table('millRec_taka');
                    $builder->select($select.',weaver_taka,taka_no,received_qty,is_sendJob,is_delete');
                    $builder->where(array('screen' => $post['item_id']));
                    $builder->where('millRec_item !=', 0);
                }else{
                    $select = "'Finish Purchase' as type";
                    $builder = $db->table('greyChallan_taka');
                    $builder->select($select.',weaver_taka,taka_no,quantity as received_qty,is_sendJob,is_delete');
                    $builder->where(array('tr_id_item' => $post['item_id']));
                    $builder->where('MillItem_id !=', 0);
                }              
                $builder->where('voucher_id !=', 0);
                $builder->where('is_delete', 0);
                $builder->where('taka_no', $row1['taka_no']);
                $query = $builder->get();
                $millre = $query->getRowArray();

                $millre['sendJobTaka_ID'] = $row1['id'];
                $selected_millRec[] = $millre;
            }
        }
        if (!empty(@$post['job_itemID'])) {
            $millrectaka = array_merge($selected_millRec, $millRec);
        } else {
            $millrectaka = $millRec;
        }
        $total_qty = 0;
        $total_taka = 0;
        $result =array();
        foreach ($millrectaka as $row) {

            if ($row['is_sendJob'] == 1) {
                $total_qty += $row['received_qty'];
                $total_taka += 1;
            }
            $result['total_taka'] = $total_taka;
            $result['total_qty'] = $total_qty;
            $result['taka'][] = $row;
        }
        return $result;

    }

    public function get_SaleTaka($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('millRec_taka');
        $builder->select('*');
        $builder->where(array('screen' => $post['item_id']));
        $builder->where('voucher_id !=', 0);
        $builder->where('millRec_item !=', 0);
        $builder->where('is_delete', 0);
        $builder->where('is_sendJob', 0);
        $builder->where('is_sale', 0);
        $query = $builder->get();
        $res1 = $query->getResultArray();

        $builder = $db->table('greyChallan_taka');
        $builder->select('*,quantity as received_qty');
        $builder->where(array('tr_id_item' => $post['item_id']));
        $builder->where('voucher_id !=', 0);
        $builder->where('MillItem_id !=', 0);
        $builder->where('is_sendJob', 0);
        $builder->where('is_delete', 0);
        $builder->where('is_sale', 0);
        $builder->where('is_return', 0);
        $query = $builder->get();
        $res2 = $query->getResultArray();

        $millRec = array_merge($res1,$res2);
        $gmodel = new GeneralModel();
        
        if (!empty(@$post['job_itemID'])) {

            $item = $gmodel->get_data_table('saleMillChallan_Item', array('id' => $post['job_itemID']), 'sale_TakaId');
            $ids = explode(',', $item['sale_TakaId']);

            foreach ($ids as $row) {
                $builder = $db->table('saleMillChallan_taka');
                $builder->select('id,taka_no,type');
                $builder->where('id', $row);
                $builder->where('is_delete', 0);
                $query = $builder->get();
                $res[] = $query->getRowArray();
            }

            foreach ($res as $row1) {
                if ($row1['type'] == 'Mill Received') {
                    $builder = $db->table('millRec_taka');
                }else{
                    $builder = $db->table('greyChallan_taka');
                }

                if($row1['type'] != 'Mill Received'){
                    $builder->select('*,quantity as received_qty');
                }else{
                    $builder->select('*');
                }

                $builder->where('voucher_id !=', 0);
                $builder->where('is_delete', 0);
                if($row1['type'] != 'Mill Received'){
                    $builder->where(array('tr_id_item' => $post['item_id']));
                    // $builder->where('purchase_type','Finish');
                    $builder->where('MillItem_id !=', 0);
                }else{
                    $builder->where('millRec_item !=', 0);
                    $builder->where(array('screen' => $post['item_id']));
                }
                $builder->where('taka_no', $row1['taka_no']);
                $query = $builder->get();
                $millre = $query->getRowArray();
                $selected_millRec[] = $millre;

                $millre['sendJobTaka_ID'] = $row1['id'];
            }

        }

        if (!empty(@$post['job_itemID'])) {
            $millrectaka = array_merge($selected_millRec, $millRec);
        } else {           
            $millrectaka = $millRec;
        }
        
        $total_qty = 0;
        $total_taka = 0;
        if(!empty($millrectaka)){
            foreach ($millrectaka as $row) {
                if(isset($row['purchase_type'])){
                    if($row['purchase_type'] =='Finish'){
                        $row['type'] = "Finish Purchase";
                    }else{
                        $row['type'] = "Gray Purchase";
                    }
                }else{
                    $row['type'] = "Mill Received";
                }
                if ($row['is_sale'] == 1) {
                    $total_qty += $row['received_qty'];
                    $total_taka += 1;
                }
                $result['total_taka'] = $total_taka;
                $result['total_qty'] = $total_qty;
                
                $result['taka'][] = $row;
            }
        }else{
            $result = array();
        }

        return $result;

    }

    // public function get_grayitem_data($id)
    // {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('milling_item');
    //     $builder->select('*');
    //     $builder->where(array('id' => $id));
    //     $query = $builder->get();
    //     $item = $query->getRowArray();
    //     return $item;
    // }

    // public function get_jobitem_data($id)
    // {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('job_item');
    //     $builder->select('*');
    //     $builder->where(array('id' => $id));
    //     $query = $builder->get();
    //     $item = $query->getRowArray();
    //     return $item;
    // }

    // public function get_greychallan_list($id)
    // {
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('grey');
    //     $builder->select('*');
    //     //$builder->join('account ac','ac.id = sc.party_name');
    //     $builder->where(array('challan_no' => $id));
    //     $query = $builder->get();
    //     $itemlist = $query->getResultArray();
    //     //print_r($itemlist);exit;
    //     $getdata['itemlist'] = $itemlist[0];
    //     //$getdata['challanitem_type'] = $itemlist[0]['challanitem_type'];
    //     return $getdata;
    //     //print_r($getdata);exit;

    // }

    // public function insert_edit_finish($post)
    // {

    //     if (!@$post['pid']) {
    //         $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
    //         return $msg;
    //     }
    //     // echo '<pre>';print_r($post);exit;
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('finish_mill');
    //     $builder->select('*');
    //     $builder->where(array("id" => $post['id']));
    //     $builder->limit(1);
    //     $result = $builder->get();
    //     $result_array = $result->getRow();
    //     // $item=(explode(",",$post['add_item']));

    //     $pid = $post['pid'];
    //     $price = $post['price'];
    //     $discount = 0;
    //     $cess = 0;
    //     $amtx = $post['amtx'];
    //     $amty = $post['amty'];
    //     $total = 0.0;
    //     $nettotal = 0;
    //     for ($i = 0; $i < count($price); $i++) {
    //         $total += $post['subtotal'][$i];
    //         $nettotal += $post['subtotal'][$i];
    //     }

    //     if ($post['disc_type'] == '%') {
    //         if ($post['discount'] == '') {
    //             $post['discount'] = 0;
    //         } else {
    //             if ($post['discount'] > 0) {
    //                 $discount = $total * $post['discount'] / 100;
    //                 $total = $total - $discount;
    //             }
    //         }
    //     } else {
    //         if ($post['discount'] == '') {
    //             $post['discount'] == 0;
    //         }

    //         if ($post['discount'] > 0) {
    //             $total = $total - $post['discount'];
    //         }
    //     }

    //     if ($post['amtx_type'] == '%') {
    //         if ($post['amtx'] == '') {
    //             $post['amtx'] = 0;
    //         } else {
    //             $post['amtx'] = $total * $post['amtx'] / 100;
    //         }

    //     } else {
    //         if ($post['amtx'] == '') {
    //             $post['amtx'] = 0;
    //         }

    //     }

    //     if ($post['cess_type'] == '%') {
    //         if ($post['cess'] == '') {
    //             $post['cess'] = 0;
    //         } else {
    //             $cess = $total * $post['cess'] / 100;
    //         }

    //     } else {
    //         if ($post['cess'] == '') {
    //             $cess = 0;
    //         } else {
    //             $cess = $post['cess'];
    //         }

    //     }

    //     if ($post['amty_type'] == '%') {
    //         if ($post['amty'] == '') {
    //             $post['amty'] = 0;
    //         } else {
    //             $post['amty'] = $total * $post['amty'] / 100;
    //         }

    //     } else {
    //         if ($post['amty'] == '') {
    //             $post['amty'] = 0;
    //         }

    //     }

    //     $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst']+@$post['tds_amt'] + $cess;

    //     $pdata = array(
    //         'daybook_id' => $post['daybook'],
    //         'gray_no' => @$post['gray_challan'],
    //         'date' => $post['date'],
    //         // 'challan_no' => $post['challan_no'],
    //         // 'challan_date' => $post['challan_date'],
    //         // 'inv_no' => $post['inv_no'],
    //         // 'inv_date' => $post['inv_date'],
    //         'transport_mode' => $post['trasport_mode'],
    //         'party_name' => @$post['account'] ? @$post['account'] : '',
    //         'delivery_code' => @$post['delivery_code'] ? @$post['delivery_code'] : '',
    //         // 'challanitem_type' => $post['challanitem_type'],
    //         // 'add_item' => $post['add_item'],
    //         // 'item_return' => $post['return'],
    //         // 'diffrence' => $post['diffrence'],
    //         'lr_no' => $post['lrno'],
    //         'lr_date' => $post['lr_date'],
    //         'weight' => $post['weight'],
    //         'freight' => $post['freight'],
    //         'taxes' => json_encode(@$post['taxes']),
    //         'tot_igst' => $post['tot_igst'],
    //         'tot_cgst' => $post['tot_cgst'],
    //         'tot_sgst' => $post['tot_sgst'],
    //         'total_amount' => $nettotal,
    //         'discount' => $post['discount'],
    //         'disc_type' => $post['disc_type'],
    //         'amtx' => $amtx,
    //         'amtx_type' => $post['amtx_type'],
    //         'amty' => $amty,
    //         'cess_type' => $post['cess_type'],
    //         'cess' => $post['cess'],
    //         'tds_amt' => $post['tds_amt'],
    //         'tds_per' => $post['tds_per'],
    //         'tds_limit' => $post['tds_limit'],
    //         'acc_state' => $post['acc_state'],
    //         'net_amount' => $netamount,

    //     );
    //     // echo '<pre>';print_r($pdata);exit;
    //     if (!empty($result_array)) {

    //         $pdata['update_at'] = date('Y-m-d H:i:s');
    //         $pdata['update_by'] = session('uid');
    //         if (empty($msg)) {
    //             $builder->where(array("id" => $post['id']));
    //             $result = $builder->Update($pdata);

    //             $item_builder = $db->table('milling_item');
    //             $item_result = $item_builder->select('GROUP_CONCAT(pid) as pid')->where(array("finish_id" => $post['id']))->get();
    //             $getItem = $item_result->getRow();

    //             $getpid = explode(',', $getItem->pid);
    //             $delete_itemid = array_diff($getpid, $pid);

    //             if (!empty($delete_itemid)) {
    //                 foreach ($delete_itemid as $key => $del_id) {
    //                     $del_data = array('is_delete' => '1');
    //                     $item_builder->where(array('pid' => $del_id, 'parent_id' => $post['id'], 'type' => 'finish'));
    //                     $item_builder->update($del_data);
    //                 }
    //             }
    //             for ($i = 0; $i < count($pid); $i++) {

    //                 $accountdata[] = array(
    //                     'id' => @$post['millItem_id'][$i],
    //                     'finish_id' => $post['id'],
    //                     'pid' => $post['pid'][$i],
    //                     'main_type' => 'finish',
    //                     'type' => $post['type'][$i],
    //                     'igst' => $post['igst'][$i],
    //                     'pcs' => $post['pcs'][$i],
    //                     'cut' => $post['cut'][$i],
    //                     'mtr' => $post['mtr'][$i],
    //                     'meter' => $post['meter'][$i],
    //                     'send_mill' => $post['tot_mill'][$i],
    //                     'screen' => $post['screen'][$i],
    //                     'finish_price' => $post['price'][$i],
    //                     'finish_pcs' => $post['finish_pcs'][$i],
    //                     'finish_cut' => $post['tot_finishcut'][$i],
    //                     'finish_mtr' => $post['rec_mtr'][$i],
    //                     'finish_amount' => $post['subtotal'][$i],
    //                     'tot_rec' => $post['tot_recMill'][$i],
    //                     'tot_finish_cut' => $post['tot_finish_cut'][$i],
    //                     'update_at' => date('Y-m-d H:i:s'),
    //                     'update_by' => session('uid'),
    //                 );
    //             }

    //             $result = $item_builder->updateBatch($accountdata, 'id');

    //             // for($i=0;$i<count($pid);$i++)
    //             // {
    //             //     // $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i],"parent_id" => $post['id']))->get();
    //             //     // $getItem = $item_result->getRow();

    //             //     if(!empty($getItem)){
    //             //         $item_data=array(
    //             //             'parent_id'=> $post['id'],
    //             //             'pid'=> $post['pid'][$i],
    //             //             'main_type' => 'finish',
    //             //             'type'=> $post['type'][$i],
    //             //             'igst'=> $post['igst'][$i],
    //             //             'price'=> $post['price'][$i],
    //             //             'pcs'=> $post['pcs'][$i],
    //             //             'cut'=> $post['tot_finishcut'][$i],
    //             //             'mtr'=> $post['rec_mtr'][$i],
    //             //             'amount'=> $post['subtotal'][$i],
    //             //             'tot_grey'=> $post['tot_recMill'][$i],
    //             //             'tot_cut'=> $post['tot_finish_cut'][$i],
    //             //             'is_delete' => 0,
    //             //             'update_at'=> date('Y-m-d H:i:s'),
    //             //             'update_by'=> session('uid'),
    //             //         );
    //             //         $item_builder->where(array('pid'=>$pid[$i],'parent_id'=>$post['id']));
    //             //         $res = $item_builder->update($item_data);
    //             //     }else{
    //             //         $item_data = array(
    //             //             'parent_id'=> $post['id'],
    //             //             'pid'=> $post['pid'][$i],
    //             //             'main_type' => 'finish',
    //             //             'type'=> $post['type'][$i],
    //             //             'igst'=> $post['igst'][$i],
    //             //             'price'=> $post['price'][$i],
    //             //             'pcs'=> $post['pcs'][$i],
    //             //             'cut'=> $post['tot_finishcut'][$i],
    //             //             'mtr'=> $post['rec_mtr'][$i],
    //             //             'tot_grey'=> $post['tot_recMill'][$i],
    //             //             'tot_cut'=> $post['tot_finish_cut'][$i],
    //             //             'amount'=> $post['subtotal'][$i],
    //             //             'created_at'=> date('Y-m-d H:i:s'),
    //             //             'created_by'=> session('uid'),
    //             //         );
    //             //         $res = $item_builder->insert($item_data);
    //             //     }
    //             //     $item_builder->where(array('parent_id' => $post['id'] , 'pid'=> $post['pid'][$i], "type" => 'finish'));
    //             //     $result1=$item_builder->update($item_data);

    //             // }
    //             $builder = $db->table('finish');

    //             if ($result) {
    //                 $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
    //                 //return view('master/account_view');
    //             } else {
    //                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated  fail");
    //             }
    //         }
    //     } else {

    //         $pdata['created_at'] = date('Y-m-d H:i:s');
    //         $pdata['created_by'] = session('uid');
    //         // print_r($pdata);exit;
    //         if (empty($msg)) {
    //             //$builder = $db->table('grey');
    //             $result = $builder->Insert($pdata);
    //             //print_r($result);exit;
    //             $id = $db->insertID();
    //             //print_r($accountdata);exit;
    //             $account_builder = $db->table('milling_item');
    //             for ($i = 0; $i < count($pid); $i++) {
    //                 $builder = $db->table('item');
    //                 $builder->select('stock_rate');
    //                 //$builder->join('account ac','ac.id = sc.party_name');
    //                 $builder->where(array('id' => $post['screen'][$i]));
    //                 $query1 = $builder->get();
    //                 $itemdata = $query1->getRow();

    //                 $stock_rate = $itemdata->stock_rate ? $itemdata->stock_rate : 0;
    //                 $receive_mtr = $post['rec_mtr'][$i];
    //                 $new_stock = $stock_rate + $receive_mtr;

    //                 $gnmodel = new GeneralModel();
    //                 $result2 = $gnmodel->update_data_table('item', array('id' => $post['screen'][$i]), array('stock_rate' => $new_stock));

    //                 $accountdata[] = array(
    //                     'id' => @$post['millItem_id'][$i],
    //                     'finish_id' => $id,
    //                     'pid' => $post['pid'][$i],
    //                     'main_type' => 'finish',
    //                     'type' => $post['type'][$i],
    //                     'igst' => $post['igst'][$i],
    //                     'pcs' => $post['pcs'][$i],
    //                     'cut' => $post['cut'][$i],
    //                     'mtr' => $post['mtr'][$i],
    //                     'meter' => $post['meter'][$i],
    //                     'send_mill' => $post['tot_mill'][$i],
    //                     'finish_price' => $post['price'][$i],
    //                     'finish_pcs' => $post['finish_pcs'][$i],
    //                     'finish_cut' => $post['tot_finishcut'][$i],
    //                     'finish_mtr' => $post['rec_mtr'][$i],
    //                     'finish_amount' => $post['subtotal'][$i],
    //                     'tot_rec' => $post['tot_recMill'][$i],
    //                     'tot_finish_cut' => $post['tot_finish_cut'][$i],
    //                     'update_at' => date('Y-m-d H:i:s'),
    //                     'update_by' => session('uid'),
    //                 );
    //             }
    //             if (!empty($post['millItem_id'])) {
    //                 $result1 = $account_builder->updateBatch($accountdata, 'id');
    //             } else {
    //                 $result1 = $account_builder->insertBatch($accountdata);
    //             }

    //             if ($result && $result1) {
    //                 $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
    //                 // return view('master/account_view');
    //             } else {
    //                 $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
    //             }
    //         }
    //     }
    //     return $msg;
    // }

    public function insert_finish_screen($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));

        $builder = $db->table('item');

        $uom = implode(',', $post['uom']);
        $cgst = $post['igst'] / 2;
        $sgst = $post['igst'] / 2;
        $msg = array();
        $pdata = array(
            'code' => $post['code'],
            'type' => $post['item_type'],
            'item_mode' => $post['item_mode'],
            'item_grp' => @$post['item_grp'],
            'name' => $post['name'],
            'sku' => $post['sku'],
            'status' => 1,
            'uom' => $uom,
            'sales_price' => @$post['sales_price'],
            'hsn' => $post['hsn'],
            'igst' => $post['igst'],
            'cgst' => $cgst,
            'sgst' => $sgst,
        );

        $pdata['created_at'] = date('Y-m-d H:i:s');
        $pdata['created_by'] = session('uid');

        if (empty($msg)) {

            $result = $builder->Insert($pdata);
            $id = $db->insertID();
            if ($result) {
                $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
            } else {
                $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
            }
        }
        return $msg;
    }

    // public function get_finish_data($get)
    // {
    //     $dt_search = array(
    //         "sc.id",
    //         "sc.sr_no",
    //         "sc.net_amount",
    //         "(select name from account ac where ac.id = sc.party_name) as account_name",
    //     );

    //     $dt_col = array(
    //         "sc.id",
    //         "sc.gray_no",
    //         "sc.date",
    //         "daybook_id",
    //         "(select name from daybook d where d.id = sc.daybook_id) as daybook_name",
    //         "(select type from daybook d where d.id = sc.daybook_id) as daybook_type",
    //         "sc.party_name",
    //         "(select name from account ac where ac.id = sc.party_name) as account_name",
    //         "sc.delivery_code",
    //         "(select name from account ac where ac.id = sc.delivery_code) as delivery_name",
    //         "sc.lr_no",
    //         "sc.lr_date",
    //         "sc.weight",
    //         "sc.freight",
    //         "sc.net_amount",
    //         //"sc.gst",
    //     );

    //     $filter = $get['filter_data'];
    //     $tablename = "finish_mill sc";
    //     $where = '';
    //     $where .= " and is_delete=0";
    //     $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    //     $sEcho = $rResult['draw'];

    //     $encode = array();

    //     foreach ($rResult['table'] as $row) {
    //         $DataRow = array();

    //         $btnedit = '<a href="' . url('Milling/Add_finish/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
    //         $btnview = '<a href="' . url('Milling/finish_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
    //         $btndelete = '<a data-toggle="modal" target="_blank"   title="Group Name: "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
    //         $btn = $btnedit . $btndelete . $btnview;

    //         $DataRow[] = $row['id'];
    //         $DataRow[] = $row['date'];
    //         $DataRow[] = $row['daybook_name'] . '<br>(' . $row['daybook_type'] . ')';
    //         $DataRow[] = $row['account_name'];
    //         $DataRow[] = $row['delivery_name'];
    //         $DataRow[] = $row['net_amount'];
    //         $DataRow[] = $btn;
    //         $encode[] = $DataRow;
    //     }

    //     $json = json_encode($encode);
    //     echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    //     exit;
    // }

    // public function get_finishinvoice_data($id)
    // {
    //     // print_r($id);exit;
    //     $db = $this->db;
    //     $db->setDatabase(session('DataSource'));
    //     $builder = $db->table('finish_mill sc');
    //     $builder->select('sc.*,ac.name as account_name');
    //     $builder->join('account ac', 'ac.id = sc.party_name');
    //     $builder->where(array('sc.id' => $id));
    //     $query = $builder->get();
    //     $challan = $query->getResultArray();
    //     //   echo '<pre>';print_r($challan);exit;
    //     $getdata['finish'] = $challan[0];

    //     $gmodel = new GeneralModel();
    //     foreach ($challan as $row) {
    //         $getaccount = $gmodel->get_data_table('account', array('id' => $row['party_name']), 'name');
    //         $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
    //         $getdaybook = $gmodel->get_data_table('daybook', array('id' => $row['daybook_id']), 'name,type');

    //         $getchallan = $gmodel->get_data_table('grey', array('id' => $row['gray_no']), 'id,party_name,challan_date');
    //         $getchallanAC = $gmodel->get_data_table('account', array('id' => $getchallan['party_name']), 'name');

    //         $challan_detail = $getchallan['id'] . ' (' . $getchallanAC['name'] . ')/' . $getchallan['challan_date'];

    //         $getdata['finish']['account_name'] = @$getaccount['name'];
    //         $getdata['finish']['delivery_name'] = @$getdelivery['name'];
    //         $getdata['finish']['daybook_name'] = @$getdaybook['name'];
    //         $getdata['finish']['daybook_type'] = @$getdaybook['type'];
    //         $getdata['finish']['challan_detail'] = @$challan_detail;

    //     }

    //     $item_builder = $db->table('milling_item st');
    //     $item_builder->select('st.*,i.*,st.id as millitem_id,st.type as mitype');
    //     $item_builder->join('item i', 'i.id = st.pid');
    //     $item_builder->where(array('st.finish_id' => $row['id'], 'st.is_delete' => 0));
    //     $query = $item_builder->get();
    //     $getdata['item'] = $query->getResultArray();

    //     for ($i = 0; $i < count($getdata['item']); $i++) {
    //         $getscreen = $gmodel->get_data_table('item', array('id' => $getdata['item'][$i]['screen']), 'name');
    //         $getdata['item'][$i]['screen_name'] = @$getscreen['name'];
    //     }

    //     return $getdata;
    // }

    public function get_finishinvoice_list($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('finish');
        $builder->select('*');
        //$builder->join('account ac','ac.id = sc.party_name');
        $builder->where(array('id' => $id));
        $query = $builder->get();
        $itemlist = $query->getResultArray();
        //print_r($itemlist);exit;
        $getdata['returnlist'] = $itemlist[0];
        //$getdata['challanitem_type'] = $itemlist[0]['challanitem_type'];
        return $getdata;
        //print_r($getdata);exit;

    }

    public function insert_edit_recJob($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        $filtered = array_filter($post['recJOB_pcs']);
        if (empty($filtered)) {
            $msg = array('st' => 'fail', 'msg' => "Please Enter Received PCS");
            return $msg;
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('recJobwork');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        $pid = $post['pid'];
        $price = $post['price'];
        $discount = 0;
        $cess = 0;
        $amtx = $post['amtx'];
        $amty = $post['amty'];
        $total = 0.0;
        $nettotal = 0;
        
        for ($i = 0; $i < count($price); $i++) {
            $total += $post['subtotal'][$i];
            $nettotal += $post['subtotal'][$i];
        }

        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                if ($post['discount'] > 0) {
                    $discount = $total * $post['discount'] / 100;
                    $total = $total - $discount;
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] == 0;
            }

            if ($post['discount'] > 0) {
                $total = $total - $post['discount'];
            }
        }

        if ($post['amtx_type'] == '%') {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            } else {
                $post['amtx'] = $total * $post['amtx'] / 100;
            }

        } else {
            if ($post['amtx'] == '') {
                $post['amtx'] = 0;
            }
        }

        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            } else {
                $cess = $total * $post['cess'] / 100;
            }
        } else {
            if ($post['cess'] == '') {
                $cess = 0;
            } else {
                $cess = $post['cess'];
            }
        }

        if ($post['amty_type'] == '%') {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            } else {
                $post['amty'] = $total * $post['amty'] / 100;
            }
        } else {
            if ($post['amty'] == '') {
                $post['amty'] = 0;
            }
        }

        $netamount = $total - $post['amtx'] + $post['amty'] + $post['tot_igst']+@$post['tds_amt'] + $cess;
        
        $pdata = array(
            'sr_no' => @$post['srno'],
            'challan_no' => @$post['job_challan'],
            'date' => db_date($post['date']),
            'transport_mode' => $post['trasport_mode'],
            'account' => @$post['account'] ? @$post['account'] : '',
            'delivery' => @$post['delivery_add'] ? @$post['delivery_add'] : '',
            'delivery_ac' => @$post['delivery_ac'] ? @$post['delivery_ac'] : '',
            'broker' => @$post['broker'] ? @$post['broker'] : '',
            'transport' => @$post['transport'] ? @$post['transport'] : '',
            'warehouse' => @$post['warehouse'] ? @$post['warehouse'] : '',
            'lr_no' => $post['lrno'],
            'lr_date' => db_date($post['lr_date']),
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'total_amount' => $nettotal,
            'discount' => $post['discount'],
            'disc_type' => $post['disc_type'],
            'amtx' => $amtx,
            'amtx_type' => $post['amtx_type'],
            'amty' => $amty,
            'amty_type' => $post['amty_type'],
            'cess_type' => $post['cess_type'],
            'cess' => $post['cess'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'net_amount' => $netamount,
        );

        
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');

            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $item_builder = $db->table('recJob_Item');
                // $item_result = $item_builder->select('GROUP_CONCAT(pid) as pid')->where(array("voucher_id" => $post['id']))->get();
                // $getItem = $item_result->getRow();

                // $getpid = explode(',', $getItem->pid);
                // $delete_itemid = array_diff($getpid, $pid);

                // if (!empty($delete_itemid)) {
                //     foreach ($delete_itemid as $key => $del_id) {
                //         $del_data = array('is_delete' => '1');
                //         $item_builder->where(array('pid' => $del_id, 'voucher_id' => $post['id']));
                //         $item_builder->update($del_data);
                //     }
                // }
                for ($i = 0; $i < count($pid); $i++) {
                    $item_result = $item_builder->select('*')->where(array("pid" => $pid[$i], "voucher_id" => $post['id']))->get();
                    $getItem = $item_result->getRow();

                        $item_data= array(
                            'type' => $post['type'][$i],
                            'pid' => $post['pid'][$i],
                            'screen' => @$post['screen'][$i] ? @$post['screen'][$i] : $getItem->screen,
                            'gst' => @$post['gst'][$i] ? $post['gst'][$i] : $getItem->gst,
                            'price' => @$post['price'][$i] ? $post['price'][$i] : $getItem->price,
                            'pcs' => @$post['sendJOB_pcs'][$i] ? $post['sendJOB_pcs'][$i] : $getItem->pcs,
                            'meter' => @$post['sendJOB_mtr'][$i] ? $post['sendJOB_mtr'][$i] : $getItem->meter ,
                            'unit' => @$post['sendJOB_unit'][$i] ? @$post['sendJOB_unit'][$i] : $getItem->unit,
                            'cut' => @$post['sendJOB_cut'][$i] ? $post['sendJOB_cut'][$i] : $getItem->cut,
                            'return_pcs' => @$post['return_pcs'][$i] ? $post['return_pcs'][$i] : $getItem->return_pcs,
                            'return_meter' => @$post['return_meter'][$i] ? $post['return_meter'][$i] : $getItem->return_meter,
                            'rec_pcs' => @$post['recJOB_pcs'][$i] ? $post['recJOB_pcs'][$i] : $getItem->rec_pcs,
                            'rec_mtr' => @$post['recJOB_mtr'][$i] ? $post['recJOB_mtr'][$i] : $getItem->rec_mtr,
                            'pending' => @$post['pending'][$i]  ?  $post['pending'][$i] : $getItem->pending,
                            'subtotal' => @$post['subtotal'][$i] ? $post['subtotal'][$i] : $getItem->subtotal,
                            'remark' => @$post['remark'][$i] ? $post['remark'][$i] : $getItem->remark,
                            'update_at' =>  date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );

                        $item_builder->where(array('pid' => $pid[$i], 'voucher_id' => $post['id']));
                        $res = $item_builder->update($item_data);
                    

                }
                $builder = $db->table('recJobwork');

                if ($result && $res) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    //return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }else{

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');
            
            if (empty($msg)) {

                $result = $builder->Insert($pdata);
                
                $id = $db->insertID();
                $job_builder = $db->table('recJob_Item');
                
                for ($i = 0; $i < count($post['pid']); $i++) {
                   
                    // $job_item = $gnmodel->get_data_table('recJob_Item', array('id' => $post['item_id'][$i]), '*');
                    // $rec_pcs = ($job_item['rec_pcs'] ? $job_item['rec_pcs'] : 0) + $post['recJOB_pcs'][$i];
                    // $rec_mtr = ($job_item['rec_mtr'] ? $job_item['rec_mtr'] : 0) + $post['recJOB_mtr'][$i];

                    // $builder = $db->table('item');
                    // $builder->select('stock_rate');
                    // //$builder->join('account ac','ac.id = sc.party_name');
                    // $builder->where(array('id' => $post['screen'][$i]));
                    // $query1 = $builder->get();
                    // $itemdata = $query1->getRow();

                    // $stock_rate = $itemdata->stock_rate ? $itemdata->stock_rate : 0;
                    // $receive_mtr = $post['recJOB_mtr'][$i];
                    // $new_stock = $stock_rate + $receive_mtr;

                    // $result2 = $gnmodel->update_data_table('item', array('id' => $post['screen'][$i]), array('stock_rate' => $new_stock));

                    $jobdata= array(
                        'voucher_id' => $id,
                        'send_challan_no' => @$post['job_challan'],
                        'type' => $post['type'][$i],
                        'pid' => $post['pid'][$i],
                        'screen' => @$post['screen'][$i] ? @$post['screen'][$i] : '',
                        'gst' => $post['gst'][$i],
                        'price' => $post['price'][$i],
                        'pcs' => $post['sendJOB_pcs'][$i],
                        'meter' => $post['sendJOB_mtr'][$i],
                        'unit' => $post['sendJOB_unit'][$i],
                        'cut' => $post['sendJOB_cut'][$i],
                        'return_pcs' => $post['return_pcs'][$i],
                        'return_meter' => $post['return_meter'][$i],
                        'remaining_pcs' => $post['remaining_pcs'][$i],
                        'remaining_mtr' => $post['remaining_mtr'][$i],
                        'rec_pcs' => $post['recJOB_pcs'][$i],
                        'rec_mtr' => $post['recJOB_mtr'][$i],
                        'pending' => $post['pending'][$i],
                        'subtotal' => $post['subtotal'][$i],
                        'remark' => $post['remark'][$i],
                        'created_at' =>  date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $result1 = $job_builder->insert($jobdata);
                }
                if ($result && $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully..!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail..!!");
                }
            }
        }
        return $msg;
    }

    public function get_jobwork_data($get)
    {
        $dt_search = array(
            
            "j.sr_no",
            "j.date",
            "j.delivery_code",
        );

        $dt_col = array(
            "j.id",
            "j.sr_no",
            "j.date",
            "a.name as account_name",
            "dl.name as delivery_name",
            "i.name as item_name",
            "SUM(si.meter) as total_send",
            "SUM(si.unit) as total_taka",
            "SUM(si.pcs) as total_pcs",
            "j.account",
            "j.transport_mode",
            "j.delivery_code",
            "j.is_cancle",
            "j.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "sendJobwork j ";
        $tablename .= 'left join account a on a.id = j.account ';
        $tablename .= 'left join account dl on dl.id = j.delivery_ac ';
        $tablename .= 'left join sendJob_Item si on si.voucher_id = j.id ';
        $tablename .= 'left join item i on i.id = si.pid ';

        $where = ' and j.is_delete=0';
        $where .= ' group by j.id';

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $statusarray = array("1" => "Activate", "0" => "Deactivate");

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            
            $btn_cancle = '<a data-toggle="modal" target="_blank"   title="Cancle Challan: "  onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/Add_jobwork/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('Milling/jobwork_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Group Name: "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a>';
            
            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }

            // $btn .= ($row['is_delete'] == 1) ? '' : $btndelete;
            // $btn .= ($row['is_delete'] == 1) ? '' : $btn_cancle;
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['sr_no'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['account_name'];
            $DataRow[] = $row['item_name'];
            $DataRow[] = $row['total_taka'];
            $DataRow[] = $row['total_send'];
            $DataRow[] = $row['total_pcs'];
            $DataRow[] = ($row['is_delete'] == 1) ? '<p class="tx-danger">Deleted</p>' : (($row['is_cancle'] == 1 && $row['is_delete'] != 1) ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>');
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_mill_SaleChallan_data($get)
    {
        // print_r("bdchjd");exit;
        $dt_search = array(
            "sm.id",
            "sm.sr_no",
            "sm.date",
            "sm.delivery_code",
            "a.name as account_name",
        );

        $dt_col = array(
            "sm.id",
            "sm.sr_no",
            "sm.date",
            "a.name as account_name",
            "sm.account",
            "sm.transport_mode",
            "sm.delivery_code",
            "sm.is_cancle",
            "sm.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "saleMillChallan sm ";
        $tablename .= 'join account a on a.id = sm.account ';

        $where = ' and sm.is_delete = 0';

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $statusarray = array("1" => "Activate", "0" => "Deactivate");

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            
            $gmodel = new GeneralModel();
            $getData = $gmodel->get_data_table('saleMillChallan_Item',array('voucher_id'=>$row['id']),'SUM(meter) total_send');

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[@$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/add_Mill_SaleChallan/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Group Name: "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a>';
            
            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['sr_no'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = @$row['account_name'];          
            $DataRow[] = @$getData['total_send'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_RecJobwork_data($get)
    {
        $dt_search = array(
            "j.id",
            "j.sr_no",
            "j.date",
            "j.delivery",
            "a.name as account_name",
            "i.item_name"
        );
        
        $dt_col = array(
            "j.id",
            "j.sr_no",
            "j.date",
            "a.name as account_name",
            "i.name as item_name",
            "SUM(ji.rec_mtr) as total_mtr",
            "SUM(ji.rec_pcs) as total_pcs",
            "ji.price",
            "ji.subtotal",
            "j.account",
            "j.transport_mode",
            "j.delivery",
            "j.is_cancle",
            "j.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "recJobwork j ";
        $tablename .= 'left join account a on a.id = j.account ';
        $tablename .= 'left join recJob_Item ji on ji.voucher_id = j.id ';
        $tablename .= 'left join item i on i.id = ji.screen ';
        $where = ' and j.is_delete=0';
        $where .= ' group by j.id';
      
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $statusarray = array("1" => "Activate", "0" => "Deactivate");

        foreach ($rResult['table'] as $row) {
            $DataRow = array();

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '" ><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('Milling/Add_rec_jobwork/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : " class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            // $btnview = '<a href="' . url('Milling/Finishjobwork_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Name: "  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a>';
            
            if($row['is_cancle'] ==1 || $row['is_delete'] == 1){
                $btn =  '';
            }else{
                $btn =  $btnedit;
            }
           
            if($rResult['total'] == $row['sr_no']){
                $btn .= $btndelete;
            }else{
                if($row['is_cancle'] == 0){
                    $btn .= $btn_cancle;
                }
            }
            $DataRow[] = $row['sr_no'];
            $DataRow[] = user_date($row['date']);
            $DataRow[] = $row['account_name'];  
            $DataRow[] = $row['item_name'];  
            $DataRow[] = $row['total_mtr'];
            $DataRow[] = $row['total_pcs'];
            $DataRow[] = $row['price'];
            $DataRow[] = $row['subtotal'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_jobworkdata($id)
    {
        // print_r($id);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sendJobwork j');
        $builder->select('j.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = j.account');
        $builder->where(array('j.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();
        
        $getdata['job'] = $challan[0];
        $gmodel = new GeneralModel();

        foreach ($challan as $row) {

            $getaccount = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getwarehouse = $gmodel->get_data_table('warehouse', array('id' => $row['warehouse']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'code');

            $getdata['job']['broker_name'] = @$getbroker['name'];
            $getdata['job']['account_name'] = @$getaccount['name'];
            $getdata['job']['delivery_name'] = @$getdelivery['name'];
            $getdata['job']['transport_name'] = @$gettransport['code'];
            $getdata['job']['warehouse_name'] = @$getwarehouse['name'];

        }

        $item_builder = $db->table('sendJob_Item ji');
        $item_builder->select('ji.*,i.name,i.hsn,i.uom as item_uom ,ji.type as uom');
        $item_builder->join('item i', 'i.id = ji.pid');
        $item_builder->where(array('ji.voucher_id' => $row['id'], 'ji.is_delete' => 0));
        $query = $item_builder->get();
        $items = $query->getResultArray();
        foreach ($items as $itm) {

            $item_uom = explode(',', $itm['item_uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['uom'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
            $itm['uom_opt'] = $option;

            $builder = $db->table('millRec_taka');
            $builder->select('SUM(received_qty) as total_rec_qty , COUNT(screen) as unit_count');
            $builder->where(array('screen' => $itm['pid']));
            $builder->where('voucher_id !=', 0);
            $builder->where('millRec_item !=', 0);
            $builder->where('is_delete', 0);
            $builder->where('is_sendJob', 0);
            $query = $builder->get();
            $res = $query->getRowArray();

            $itm['stock'] = $res['unit_count'] . '(' . (($res['total_rec_qty'] != '') ? $res['total_rec_qty'] : 0) . ')';
            $getdata['item'][] = $itm;
        }
        // echo '<pre>';print_r($getdata);exit;
        return $getdata;

    }

    public function get_Recjobworkdata($id)
    {
        // print_r($id);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('recJobwork j');
        $builder->select('j.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = j.account');
        $builder->where(array('j.id' => $id));
        $query = $builder->get();
        $rec_job = $query->getResultArray();
        $getdata['job'] = @$rec_job[0];

        $gmodel = new GeneralModel();
        
        foreach ($rec_job as $row) {

            $getaccount = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $gettransport = $gmodel->get_data_table('transport',array('id'=>$row['transport']),'name');
            $getwarehouse = $gmodel->get_data_table('warehouse',array('id'=>$row['warehouse']),'name');
            
            
            $getchallandetail = $gmodel->get_data_table('sendJobwork', array('id' => $row['challan_no']), 'id,account,date');
            $getchallanac = $gmodel->get_data_table('account', array('id' => @$getchallandetail['account']), 'name');
            $getPcsMtr = $gmodel->get_array_table('sendJob_Item', array('voucher_id' =>  @$getchallandetail['id'],'is_delete' => 0), 'SUM(meter) as sendMtr, SUM(unit) as sendUnit');
           
            $getchallan = $row['challan_no'].' ('.$getchallanac['name'].')'. user_date($getchallandetail['date']).' ~ '.$getPcsMtr[0]['sendUnit'].'('.$getPcsMtr[0]['sendMtr'].')';

            $getdata['job']['broker_name']=@$getbroker['name'];
            $getdata['job']['account_name'] = @$getaccount['name'];
            $getdata['job']['broker_name'] = @$getbroker['name'];
            $getdata['job']['warehouse_name'] = @$getwarehouse['name'];
            $getdata['job']['transport_name'] = @$gettransport['name'];
           
            $getdata['job']['challan_name'] = @$getchallan;
        }

        $item_builder = $db->table('recJob_Item ji');
        $item_builder->select('ji.*,i.type as item_type , i.id as item_id ,i.uom as item_uom, i.name,i.hsn');
        $item_builder->join('item i', 'i.id = ji.pid');
        $item_builder->where(array('ji.voucher_id' => $row['id']));
        $item_builder->where(array('ji.is_delete' => 0));
        $query = $item_builder->get();
        $item = $query->getResultArray();
        
     
        foreach ($item as $itm) {
            $screen = $gmodel->get_data_table('item', array('id' => @$itm['screen'], 'name,code'));
            $item_uom = explode(',', $itm['item_uom']);
            $gmodel = new GeneralModel();
            $option = '';
            foreach ($item_uom as $uom) {
                $uom_name = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $select = ($itm['type'] == $uom) ? 'selected' : '';
                $option .= '<option value="' . $uom . '" ' . $select . ' \>' . $uom_name['code'] . '</option>';
            }
           
            $itm['uom_opt'] = $option;
            if(!empty($screen)){
                $itm['screen_name'] = $screen['name'].'('.$screen['code'].')';
            }else{
                $itm['screen_name'] = '';
            }
            $getdata['item'][] = $itm;
        }
        return $getdata;
    }

    public function get_jobwork_list($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('jobwork');
        $builder->select('*');
        //$builder->join('account ac','ac.id = sc.party_name');
        $builder->where(array('challan_no' => $id));
        $query = $builder->get();
        $itemlist = $query->getResultArray();
        //print_r($itemlist);exit;
        $getdata['itemlist'] = $itemlist[0];
        //$getdata['challanitem_type'] = $itemlist[0]['challanitem_type'];
        return $getdata;
        //print_r($getdata);exit;

    }

    public function UpdateData($post)
    {
        $result = array();
        if ($post['type'] == 'Remove') {

            if ($post['method'] == 'grey') {
                $gnmodel = new GeneralModel();
                $gray_challan = $gnmodel->get_array_table('grey',array('challan_no'=>$post['pk']),'is_cancle,is_delete');            
                
                foreach($gray_challan as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Invoice');                    
                }else{
                    $result = $gnmodel->update_data_table('grey', array('id' => $post['pk']), array('is_delete' => '1'));
                }
            }

            if ($post['method'] == 'grey_challan') {

                $gnmodel = new GeneralModel();
                $gray_challan = $gnmodel->get_array_table('grey',array('challan_no'=>$post['pk']),'is_cancle,is_delete');            
                
                foreach($gray_challan as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Invoice');                    
                }else{
                    $result = $gnmodel->update_data_table('grey_challan', array('id' => $post['pk']), array('is_delete' => '1'));

                }

            }

            if ($post['method'] == 'retGrayFinish') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('retGrayFinish', array('id' => $post['pk']), array('is_delete' => 1));
            } 

            if ($post['method'] == 'grey_invoice') {
                $gnmodel = new GeneralModel();
                $purchase_return = $gnmodel->get_array_table('retGrayFinish',array('challan_no'=>$post['pk']),'is_cancle,is_delete');

                foreach($purchase_return as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Return Invoice');                    
                }else{
                    $result = $gnmodel->update_data_table('grey', array('id' => $post['pk']), array('is_delete' => 1));
                    $gnmodel->update_data_table('gray_item', array('voucher_id' => $post['pk']), array('is_delete' => 1));
                    $challan = $gnmodel->get_data_table('grey',array('id' => $post['pk']),'challan_no');
                    $gnmodel->update_data_table('grey_challan', array('id' => $challan['challan_no']), array('is_invoiced' =>0));
                    $purchase_return = $gnmodel->get_array_table('retGrayFinish',array('challan_no'=>$post['pk']),'is_cancle,is_delete');
                }
            
            }

            if ($post['method'] == 'mill_challan'){
                $gnmodel = new GeneralModel();
             
                $result = $gnmodel->update_data_table('mill_challan', array('id' => $post['pk']), array('is_delete' => 1));
                $itm = $gnmodel->get_array_table('mill_item',array('voucher_id'=>$post['pk']),'id,pid');
                $gray_taka_ids = array();

                foreach($itm as $row){
                    $mill_taka = $gnmodel->get_array_table('millChallan_taka',array('voucher_id'=>$post['pk'],'mill_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),'greyTaka_Id');
                    
                    $gray_taka_ids = array_merge($gray_taka_ids,$mill_taka);
                    
                    $gnmodel->update_data_table('millChallan_taka',array('voucher_id'=>$post['pk'],'mill_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>1));
                    $gnmodel->update_data_table('mill_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                }
                foreach($gray_taka_ids as $row1){
                    $gnmodel->update_data_table('greyChallan_taka',array('id'=>$row1['greyTaka_Id']),array('is_send_mill'=>0));
                }
            }

            if ($post['method'] == 'finish'){
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('finish', array('id' => $post['pk']), array('is_delete' => '1'));
            }

            if ($post['method'] == 'mill_rec'){
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('millRec', array('id' => $post['pk']), array('is_delete' => 1));
                $itm = $gnmodel->get_array_table('millRec_item',array('voucher_id'=>$post['pk']),'id,pid');
                $mill_taka_ids = array();
                foreach($itm as $row){
                    $mill_taka = $gnmodel->get_array_table('millRec_taka',array('voucher_id'=>$post['pk'],'millRec_item'=>$row['id'],'tr_id_item'=>$row['pid']),'millTaka_Id');
                    $mill_taka_ids = array_merge($mill_taka_ids,$mill_taka);

                    $gnmodel->update_data_table('millRec_taka',array('voucher_id'=>$post['pk'],'millRec_item'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>1));
                    $gnmodel->update_data_table('millRec_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                }
                foreach($mill_taka_ids as $row1){
                    $gnmodel->update_data_table('millChallan_taka',array('id'=>$row1['millTaka_Id']),array('is_rec_mill'=>0));
                }
            }

            if($post['method'] == 'jobwork_data') {
                
                $gnmodel = new GeneralModel();
                
                $result = $gnmodel->update_data_table('sendJobwork', array('id' => $post['pk']), array('is_delete' => $post['val']));
                $itm = $gnmodel->get_array_table('sendJob_Item',array('voucher_id'=>$post['pk']),'id,pid');
                
                $mill_taka_ids = array();
                foreach($itm as $row){
                    $mill_taka = $gnmodel->get_array_table('sendJob_taka',array('voucher_id'=>$post['pk'],'job_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),'taka_no');
                    $mill_taka_ids = array_merge($mill_taka_ids,$mill_taka);
                    
                    $gnmodel->update_data_table('sendJob_taka',array('voucher_id'=>$post['pk'],'job_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>1));
                    $gnmodel->update_data_table('sendJob_Item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                      
                }
                foreach($mill_taka_ids as $row1){
                    $gnmodel->update_data_table('millRec_taka',array('taka_no'=>$row1['taka_no']),array('is_sendJob'=>0));
                }
            }

            if($post['method'] == 'rec_jobwork') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('recJobwork', array('id' => $post['pk']), array('is_delete' => 1));
                $itm = $gnmodel->get_array_table('recJob_Item',array('voucher_id'=>$post['pk']),'id,pid');
                foreach($itm as $row){
                    $gnmodel->update_data_table('recJob_Item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                }
            }

            if($post['method'] == 'return_jobwork') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('return_jobwork', array('id' => $post['pk']), array('is_delete' => 1));
                $itm = $gnmodel->get_array_table('return_jobwork_item',array('voucher_id'=>$post['pk']),'id,pid');
                foreach($itm as $row){
                    $gnmodel->update_data_table('return_jobwork_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                }
            }

            if($post['method'] == 'return_mill') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('return_mill', array('id' => $post['pk']), array('is_delete' => $post['val']));
                $itm = $gnmodel->update_data_table('return_mill_item',array('voucher_id'=>$post['pk']),array('is_delete'=>1));
                $itm_data = $gnmodel->get_array_table('return_mill_item',array('voucher_id'=>$post['pk']),'id,pid');
                foreach($itm_data as $row){
                    if($post['val'] == 1){
                        $gnmodel->update_data_table('return_mill_taka',array('voucher_id'=>$post['pk'],'item_id'=>$row['id']),array('is_delete'=>1));
                    }else{
                        $gnmodel->update_data_table('return_mill_taka',array('voucher_id'=>$post['pk'],'item_id'=>$row['id']),array('is_delete'=>0));

                    }
                }
            }
            
            if ($post['method'] == 'mill_SaleInvoice') {
                $gnmodel = new GeneralModel();
                $sale_millinvoice = $gnmodel->get_array_table('saleMillReturn',array('invoice_no'=>$post['pk']),'is_cancle,is_delete');            
                
                foreach($sale_millinvoice as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Return Invoice');                    
                }else{
              
                    $result = $gnmodel->update_data_table('saleMillInvoice', array('id' => $post['pk']), array('is_delete' => 1));
                }
            }

            if ($post['method'] == 'mill_SaleReturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('saleMillReturn', array('id' => $post['pk']), array('is_delete' => 1));
            }
        }

        if ($post['type'] == 'Status') {
            
            if ($post['method'] == 'grey_challan') {
                $gnmodel = new GeneralModel();

                $gray_challan = $gnmodel->get_array_table('grey',array('challan_no'=>$post['pk']),'is_cancle,is_delete');            
                // print_r($sales_invoice);exit;
                foreach($gray_challan as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Invoice');                    
                }else{
                    $result = $gnmodel->update_data_table('grey_challan', array('id' => $post['pk']), array('is_cancle' => 1));
                }
            }
            
            if ($post['method'] == 'grey_invoice') {

                $gnmodel = new GeneralModel();
                $purchase_return = $gnmodel->get_array_table('retGrayFinish',array('challan_no'=>$post['pk']),'is_cancle,is_delete');

                foreach($purchase_return as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Return Invoice');                    
                }else{
                    $result = $gnmodel->update_data_table('grey', array('id' => $post['pk']), array('is_cancle' => 1));
                    $gnmodel->update_data_table('gray_item', array('voucher_id' => $post['pk']), array('is_delete' => 1));
                    $challan = $gnmodel->get_data_table('grey',array('id' => $post['pk']),'challan_no');
                    $gnmodel->update_data_table('grey_challan', array('id' => $challan['challan_no']), array('is_invoiced' =>0));
                    $purchase_return = $gnmodel->get_array_table('retGrayFinish',array('challan_no'=>$post['pk']),'is_cancle,is_delete');
                }
            }   

            if ($post['method'] == 'retGrayFinish') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('retGrayFinish', array('id' => $post['pk']), array('is_cancle' => 1));
            }  

            if ($post['method'] == 'mill_challan') {
                $gnmodel = new GeneralModel();

                $result = $gnmodel->update_data_table('mill_challan', array('id' => $post['pk']), array('is_cancle' => $post['val']));
                $itm = $gnmodel->get_array_table('mill_item',array('voucher_id'=>$post['pk']),'id,pid');
                $gray_taka_ids = array();
                foreach($itm as $row){
                    $mill_taka = $gnmodel->get_array_table('millChallan_taka',array('voucher_id'=>$post['pk'],'mill_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),'greyTaka_Id');
                    
                    $gray_taka_ids = array_merge($gray_taka_ids,$mill_taka);
                    if($post['val'] == 1){
                        $gnmodel->update_data_table('millChallan_taka',array('voucher_id'=>$post['pk'],'mill_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>1));
                        $gnmodel->update_data_table('mill_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                    }else{
                        $gnmodel->update_data_table('millChallan_taka',array('voucher_id'=>$post['pk'],'mill_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>0));
                        $gnmodel->update_data_table('mill_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>0));
                    }
                }
                foreach($gray_taka_ids as $row1){
                    $gnmodel->update_data_table('greyChallan_taka',array('id'=>$row1['greyTaka_Id']),array('is_send_mill'=>($post['val'] == 1) ? '0' : '1'));
                } 
            }

            if ($post['method'] == 'mill_SaleInvoice') {
                $gnmodel = new GeneralModel();
               
                $sale_millinvoice = $gnmodel->get_array_table('saleMillReturn',array('invoice_no'=>$post['pk']),'is_cancle,is_delete');            
                
                foreach($sale_millinvoice as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Return Invoice');                    
                }else{
                    $result = $gnmodel->update_data_table('saleMillInvoice', array('id' => $post['pk']), array('is_cancle' => 1));
                }
            } 

            if ($post['method'] == 'mill_SaleReturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('saleMillReturn', array('id' => $post['pk']), array('is_cancle' => 1));
            }   

            if ($post['method'] == 'mill_rec') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('millRec', array('id' => $post['pk']), array('is_cancle' => $post['val']));
                $itm = $gnmodel->get_array_table('millRec_item',array('voucher_id'=>$post['pk']),'id,pid');
                $mill_taka_ids = array();
                foreach($itm as $row){
                    $mill_taka = $gnmodel->get_array_table('millRec_taka',array('voucher_id'=>$post['pk'],'millRec_item'=>$row['id'],'tr_id_item'=>$row['pid']),'millTaka_Id');
                    $mill_taka_ids = array_merge($mill_taka_ids,$mill_taka);
                    
                    if($post['val'] == 1){
                        $gnmodel->update_data_table('millRec_taka',array('voucher_id'=>$post['pk'],'millRec_item'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>1));
                        $gnmodel->update_data_table('millRec_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                    }else{
                        $gnmodel->update_data_table('millRec_taka',array('voucher_id'=>$post['pk'],'millRec_item'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>0));
                        $gnmodel->update_data_table('millRec_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>0));
                    }
                }
                foreach($mill_taka_ids as $row1){
                    $gnmodel->update_data_table('millChallan_taka',array('id'=>$row1['millTaka_Id']),array('is_rec_mill'=>($post['val'] == 1) ? '0' : '1'));
                } 
            }

            if($post['method'] == 'jobwork_data') {
                $gnmodel = new GeneralModel();
                $jobwork_challan = $gnmodel->get_array_table('recJobwork',array('challan_no'=>$post['pk']),'is_cancle,is_delete');            
                // print_r($sales_invoice);exit;
                foreach($jobwork_challan as $row){
                    if(@$row['is_cancle'] == 0 && @$row['is_delete'] == 0){
                        $is_cancle = 0;
                    }
                }
                
                if(isset($is_cancle) && $is_cancle == 0){
                    $result = array('st'=>'fail' ,'msg'=>'Please First Cancle Received Invoice');                    
                }else{
                
                    $result = $gnmodel->update_data_table('sendJobwork', array('id' => $post['pk']), array('is_cancle' => 1));
                    $itm = $gnmodel->get_array_table('sendJob_Item',array('voucher_id'=>$post['pk']),'id,pid');
                    
                    $mill_taka_ids = array();
                    foreach($itm as $row){
                        $mill_taka = $gnmodel->get_array_table('sendJob_taka',array('voucher_id'=>$post['pk'],'job_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),'taka_no');
                        $mill_taka_ids = array_merge($mill_taka_ids,$mill_taka);
                        
                        if($post['val'] == 1){
                            $gnmodel->update_data_table('sendJob_taka',array('voucher_id'=>$post['pk'],'job_item_id'=>$row['id'],'tr_id_item'=>$row['pid']),array('is_delete'=>1));
                            $gnmodel->update_data_table('sendJob_Item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                        }else{
                            $gnmodel->update_data_table('sendJob_taka',array('voucher_id'=>$post['pk'],'job_item_id'=>$row['id'],'tr_id_item' => $row['pid']),array('is_delete'=>0));
                            $gnmodel->update_data_table('sendJob_Item',array('id'=>$row['id'] ,'voucher_id'=>$post['pk']),array('is_delete'=>0));
                        }  
                    }
                    foreach($mill_taka_ids as $row1){
                        $gnmodel->update_data_table('millRec_taka',array('taka_no'=>$row1['taka_no']),array('is_sendJob'=>($post['val'] == 1) ? '0' : '1'));
                    } 
                }
            }
           
            if($post['method'] == 'rec_jobwork') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('recJobwork', array('id' => $post['pk']), array('is_cancle' => $post['val']));
                $itm = $gnmodel->get_array_table('recJob_Item',array('voucher_id'=>$post['pk']),'id,pid,send_challan_no');
                foreach($itm as $row){
                    if($post['val'] == 1){
                        $gnmodel->update_data_table('recJob_Item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>1));
                    }else{
                        $gnmodel->update_data_table('recJob_Item',array('id'=>$row['id'] ,'voucher_id'=>$post['pk']),array('is_delete'=>0));
                    }
                }
            }
           
            if($post['method'] == 'return_jobwork') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('return_jobwork', array('id' => $post['pk']), array('is_cancle' =>1));
                $itm = $gnmodel->get_array_table('return_jobwork_item',array('voucher_id'=>$post['pk']),'id,pid');
                foreach($itm as $row){
                    $gnmodel->update_data_table('return_jobwork_item',array('id'=>$row['id'],'voucher_id'=>$post['pk']),array('is_delete'=>$post['val']));
                }
            }

            if($post['method'] == 'return_mill') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('return_mill', array('id' => $post['pk']), array('is_cancle' => $post['val']));
                $itm = $gnmodel->update_data_table('return_mill_item',array('voucher_id'=>$post['pk']),array('is_delete'=>1));
                $itm_data = $gnmodel->get_array_table('return_mill_item',array('voucher_id'=>$post['pk']),'id,pid');
                foreach($itm_data as $row){
                    if($post['val'] == 1){
                        $gnmodel->update_data_table('return_mill_taka',array('voucher_id'=>$post['pk'],'item_id'=>$row['id']),array('is_delete'=>1));
                    }else{
                        $gnmodel->update_data_table('return_mill_taka',array('voucher_id'=>$post['pk'],'item_id'=>$row['id']),array('is_delete'=>0));

                    }
                }
            }
            
        }

        return $result;
    }

    // public function get_mill_stock_data($get, $post)
    // {

    //     $dt_search = array(
    //         "id",
    //         "name",
    //         "hsn",
    //     );

    //     $dt_col = array(
    //         "id",
    //         "name",
    //         "hsn",
    //         "type",
    //     );

    //     $filter = $get['filter_data'];
    //     $tablename = "item";
    //     $where = '';
    //     // if ($filter != '' && $filter != 'undefined') {
    //     //     $where .= ' and UserType ="' . $filter . '"';
    //     // }
    //     $where .= " and is_delete=0";
    //     $where .= " and item_mode ='milling' ";
    //     $where .= " and type !='Jobwork' ";

    //     $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    //     $sEcho = $rResult['draw'];

    //     $encode = array();

    //     foreach ($rResult['table'] as $row) {

    //         if (empty($post)) {
    //             $sale = milling_SaleItemSTock($row['id'], $row['type']);
    //         } else {
    //             $sale = milling_SaleItemSTock($row['id'], $row['type'], @$post['from'], @$post['to']);
    //         }

    //         $DataRow = array();

    //         $DataRow[] = $row['id'];
    //         $DataRow[] = $row['name'] . ' (' . $row['hsn'] . ')';
    //         $DataRow[] = $sale['mill']['gray_pcs'];
    //         $DataRow[] = $sale['mill']['gray_purcahse'];
    //         $DataRow[] = $sale['mill']['gray_cut'];

    //         $DataRow[] = $sale['mill']['send_pcs'];
    //         $DataRow[] = $sale['mill']['send_mill'];
    //         $DataRow[] = $sale['mill']['mill_cut'];

    //         $DataRow[] = $sale['mill']['gray_pcs'] - $sale['mill']['send_pcs'];
    //         $DataRow[] = $sale['mill']['gray_purcahse'] - $sale['mill']['send_mill'];
    //         $DataRow[] = $sale['mill']['gray_cut'] - $sale['mill']['mill_cut'];

    //         // $DataRow[] = $sale['mill']['finish_pcs'];
    //         // $DataRow[] = $sale['mill']['finish_meter'];
    //         // $DataRow[] = $sale['mill']['finish_cut'];

    //         $encode[] = $DataRow;

    //     }

    //     $json = json_encode($encode);
    //     echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    //     exit;
    // }

    // public function get_jobwork_stock_data($get, $post)
    // {
    //     $dt_search = array(
    //         "id",
    //         "name",
    //         "hsn",
    //     );

    //     $dt_col = array(
    //         "id",
    //         "name",
    //         "hsn",
    //         "type",
    //     );

    //     $filter = $get['filter_data'];
    //     $tablename = "item";
    //     $where = '';

    //     $where .= " and is_delete=0";
    //     $where .= " and item_mode ='milling' ";
    //     $where .= " and type !='Grey' ";

    //     $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
    //     $sEcho = $rResult['draw'];

    //     $encode = array();

    //     foreach ($rResult['table'] as $row) {

    //         if (empty($post)) {
    //             $sale = jobwork_ItemSTock($row['id'], $row['type']);
    //         } else {
    //             $sale = jobwork_ItemSTock($row['id'], $row['type'], @$post['from'], @$post['to']);
    //         }

    //         $DataRow = array();

    //         $DataRow[] = $row['id'];
    //         $DataRow[] = $row['name'] . ' (' . $row['hsn'] . ')';

    //         $DataRow[] = $sale['job']['rec_pcs'];
    //         $DataRow[] = $sale['job']['rec_mtr'];

    //         $DataRow[] = $sale['job']['send_pcs'];
    //         $DataRow[] = $sale['job']['send_mtr'];

    //         $DataRow[] = $sale['job']['pending_pcs'];
    //         $DataRow[] = $sale['job']['pending_mtr'];

    //         $encode[] = $DataRow;

    //     }

    //     $json = json_encode($encode);
    //     echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
    //     exit;
    // }

    public function validate_taka($post)
    {
        for ($i = 0; $i < count($post['taka_qty']); $i++) {
            if ($post['taka_qty'][$i] < $post['taka_cut'][$i]) {
                $msg = array('st' => 'fail', 'msg' => 'Quantity was must be More than Cut..!');
                return $msg;
            } else {
                $msg = array('st' => 'Success', 'msg' => 'Sucess');
            }
        }
        return $msg;
    }

}