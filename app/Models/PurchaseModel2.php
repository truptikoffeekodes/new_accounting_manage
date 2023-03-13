<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    public function insert_edit_debit($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('debit_note');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $msg = array();

        $pdata = array(
            'document' => $post['document'],
            'date' => $post['date'],
            'status' => $post['status'],
            'class' => $post['class'],
            'particlur' => $post['particulrs'],
            'account' => $post['account'],
            'notes' => $post['notes'],
        );

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {

                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $builder = $db->table('debit_note');

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
                //print_r($result);exit;
                $id = $db->insertID();
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function get_debit_data($get)
    {
        $dt_search = $dt_col = array(
            "id",
            "document",
            "date",
            "status",
            "class",
            "particlur",
            "account",
            "notes",
            "created_at",
            "created_by",
            "update_at",
            "update_by",
        );

        $filter = $get['filter_data'];
        $tablename = "debit_note";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $btnedit = '<a   href="' . url('Purchase/add_debit/') . $row['id'] . '"   data-title="Edit Debit : ' . $row['document'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Debit Note: ' . $row['document'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btn = $btnedit . $btndelete;

            $DataRow[] = $row['id'];
            $DataRow[] = $row['document'];
            $DataRow[] = $row['date'];
            $DataRow[] = $row['status'];
            $DataRow[] = $row['class'];
            $DataRow[] = $row['particlur'];
            $DataRow[] = $row['account'];
            $DataRow[] = $row['notes'];

            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_purchasechallan_data($get)
    {
        $dt_search = array(
            "pc.id",
            "pc.challan_date",
            "pc.challan_no",
            "pc.custom_challan_no",
            "pc.net_amount",
            "(select name from account ac where pc.account = ac.id)",

        );
        $dt_col = array(
            "pc.id",
            "pc.challan_date",
            "pc.challan_no",
            "pc.custom_challan_no",
            "pc.account",
            "(select name from account ac where pc.account = ac.id) as account_name",
            "pc.broker",
            "pc.net_amount",
            "pc.is_cancle",
            "pc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "purchase_challan pc";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $gmodel = new GeneralModel();

        foreach ($rResult['table'] as $row) {

            $DataRow = array();

            $statusarray = array("1" => "Cancled", "0" => "Cancle");
            $btn_cancle = '<a data-toggle="modal" target="_blank"   title="Cancle Challan: ' . $row['challan_no'] . '"  onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-times-circle"></i></a> ';

            $btnedit = '<a   href="' . url('purchase/add_purchasechallan/') . $row['id'] . '"   data-title="Edit PurchaseChallan: ' . $row['account'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('purchase/purchase_challan_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Challan No: ' . $row['challan_no'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $getMax = $gmodel->get_data_table('purchase_challan', array('is_delete' => 0), 'MAX(challan_no) as max_challan');

            $btnpdf = '<a href="' . url('Purchase/pdf_challan/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';


            if ($row['is_cancle'] == 1 || $row['is_delete'] == 1) {
                $btn =  $btnview . $btnpdf;
            } else {
                $btn =  $btnedit . $btnview . $btnpdf;
            }

            if ($getMax['max_challan'] == $row['challan_no']) {
                if ($row['is_cancle'] != 1) {
                    $btn .= $btndelete;
                }
            } else {
                if ($row['is_cancle'] == 0) {
                    $btn .= $btn_cancle;
                } else {
                }
            }

            if (!empty($row['gst'])) {
                $gst = '<br>(' . $row['gst'] . ')';
            } else {
                $gst = '';
            }

            $DataRow[] = $row['challan_no'];
            $DataRow[] = $row['custom_challan_no'];
            $DataRow[] = user_date($row['challan_date']);
            $DataRow[] = $row['account_name'] . $gst;
            $DataRow[] = number_format($row['net_amount'], 2);
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;
            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    // update trupti 24-11-2022
    public function insert_edit_purchasechallan($post)
    {

        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array('id' => $post['id']));
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
        $item_disc = $post['item_disc'];
        $discount = $post['discount'];
        $amty = $post['amty'];
        $cess = $post['cess'];
        $total = 0.0;
        $item_count = 0;
        $expence_count = 0;
        for ($i = 0; $i < count($pid); $i++) {

            if ($post['expence'][$i] == 0) {
                $item_count += 1;
            } else {
                $expence_count += 1;
            }
        }
        if ($expence_count > 0) {
            if ($item_count == 0) {
                $msg = array('st' => 'Fail', 'msg' => "Please select Item!!!");
                return $msg;
            }
        }

        if ($item_count == 0) {
            $msg = array('st' => 'Fail', 'msg' => "Please select Item!!!");
            return $msg;
        }

        for ($i = 0; $i < count($pid); $i++) {
            $disc_amt = 0;
            if ($post['expence'][$i] == 0) {
                $disc_amt = 0;
                if ($item_disc[$i] != 0) {
                    $sub = $post['qty'][$i] * $post['price'][$i];
                    $disc_amt = $sub * $item_disc[$i] / 100;
                }
                $final_sub = $post['qty'][$i] * $post['price'][$i] - @$disc_amt;
            } else {
                $final_sub = $post['price'][$i];
            }
            $total += $final_sub;
        }
        // discount calculation modification update 16-01-2023
        //$total = 0;
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {

                if ($post['discount'] > 0) {
                    $total = 0;
                    $item_total_amt = 0;
                    for ($i = 0; $i < count($pid); $i++) {
                        // $devide_disc = $post['discount'] / count($pid);
                        // if ($post['expence'][$i] == 0) {
                        //     if ($item_disc[$i] != 0) {
                        //         $sub = $post['qty'][$i] * $post['price'][$i];
                        //         $disc_amt = $sub * $item_disc[$i] / 100;
                        //     }
                        //     $final_sub = $post['qty'][$i] * $post['price'][$i] - $disc_amt;
                        // } else {
                        //     $final_sub = $post['price'][$i];
                        // }
                        // $total += $final_sub - $devide_disc;
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_total_amt += $sub;
                        }
                    }
                    $post['discount'] = $item_total_amt * $post['discount'] / 100;
                    for ($i = 0; $i < count($pid); $i++) {
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_per = ($sub * 100) / $item_total_amt;
                            $item_disc_amt = ($item_per / 100) * $post['discount'];
                            $final_sub = $sub - $item_disc_amt;
                        } else {
                            $final_sub = $post['price'][$i];
                        }


                        $total += $final_sub;
                    }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            }
            if ($post['discount'] > 0) {
                $total = 0;
                for ($i = 0; $i < count($pid); $i++) {

                    //$total = 0; 
                    $item_total_amt = 0;
                    for ($i = 0; $i < count($pid); $i++) {

                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_total_amt += $sub;
                        }
                    }

                    for ($i = 0; $i < count($pid); $i++) {
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_per = ($sub * 100) / $item_total_amt;
                            $item_disc_amt = ($item_per / 100) * $post['discount'];
                            $final_sub = $sub - $item_disc_amt;
                            // echo '</br>';
                            // echo '<pre>sub:';Print_r($sub);
                            // echo '<pre>item_per:';Print_r($item_per);
                            // echo '<pre>disc_amt:';Print_r($item_disc_amt);  
                        } else {
                            $final_sub = $post['price'][$i];
                        }
                        //echo '<pre>';Print_r($final_sub);

                        $total += $final_sub;
                    }
                }
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

        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            } else {
                $post['cess'] = $total * $post['cess'] / 100;
            }
        } else {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            }
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total * $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }

        $date = db_date($post['challan_date']);
        $lr_date = db_date($post['lr_date']);
        $netamount = $total  + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'];
        if (isset($post['taxes'])) {
            if (!empty($post['taxes'])) {
                if (in_array('igst', $post['taxes'])) {
                    $igst_acc = $post['igst_acc'];
                    $cgst_acc = "";
                    $sgst_acc = "";
                } else {
                    $igst_acc = "";
                    $cgst_acc = $post['cgst_acc'];
                    $sgst_acc = $post['sgst_acc'];
                }
            } else {
                $igst_acc = "";
                $cgst_acc = "";
                $sgst_acc = "";
            }
        } else {
            $igst_acc = "";
            $cgst_acc = "";
            $sgst_acc = "";
        }
        //echo '<pre>';Print_r($post);exit;

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $post['gl_group'],
            'challan_no' => $post['challan_no'],
            'custom_challan_no' => @$post['custom_challan_no'] ? $post['custom_challan_no'] : '',
            'challan_date' => $date,
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst_no'],
            'sup_chl_no' => $post['sup_chl_no'],
            'broker' => @$post['broker'],
            'other' => @$post['other'],
            'lr_no' => $post['lr_no'],
            'lr_date' => $lr_date,
            'city' => @$post['city'],
            'transport' => @$post['transport'] ? $post['transport'] : '',
            'taxes' => json_encode(@$post['taxes']),
            'transport_mode' => $post['transport_mode'],
            'vehicle' => @$post['vehicle'] ? $post['vehicle'] : '',
            'supply_inv' => $post['supply_inv'],
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'total_amount' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'cess_type' => $post['cess_type'],
            'cess' => $cess,
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'amty' => $amty,
            'amty_type' => $post['amty_type'],
            'net_amount' => round($netamount),
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'igst_acc' => @$igst_acc,
            'cgst_acc' =>  @$cgst_acc,
            'sgst_acc' =>  @$sgst_acc,
        );
        if ($post['gst_no'] != '') {
            if (in_array('Taxable', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $post['taxability']) && !in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }
        if (!empty($result_array)) {
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'challan', "is_delete" => 0, "is_expence" => 0))->get();
                $getItem = $item_result->getRow();

                $account_builder = $db->table('purchase_item');
                $account_result = $account_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'challan', "is_delete" => 0, "is_expence" => 1))->get();
                $getAccount = $account_result->getRow();

                $new_item = array();
                $new_itempid = array();
                $new_account = array();
                $new_accountpid = array();
                for ($i = 0; $i < count($post['pid']); $i++) {

                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;

                        $total = $post['qty'][$i] * $post['price'][$i];
                        if ($post['discount'] > 0) {
                            $sub_total = $total - @$post['divide_disc_amt'][$i];
                        } else {
                            $sub_total = $total -  @$post['item_discount_hidden'][$i];
                        }
                        $item['pid'] = $post['pid'][$i];
                        $item['hsn'] = $post['hsn'][$i];
                        $item['qty'] = $post['qty'][$i];
                        $item['uom'] = $post['uom'][$i];
                        $item['rate'] = $post['price'][$i];
                        $item['igst'] = $post['igst'][$i];
                        $item['cgst'] = $post['cgst'][$i];
                        $item['sgst'] = $post['sgst'][$i];
                        $item['igst_amt'] = $post['igst_amt'][$i];
                        $item['cgst_amt'] = $post['cgst_amt'][$i];
                        $item['sgst_amt'] = $post['sgst_amt'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                        //update discount column 17-01-2023
                        $item['total'] = $total;
                        $item['item_disc'] = $post['item_disc'][$i];
                        $item['discount'] = $post['item_discount_hidden'][$i];
                        $item['divide_disc_item_per'] = @$post['item_per'][$i];
                        $item['divide_disc_item_amt'] = @$post['divide_disc_amt'][$i];
                        $item['sub_total'] = $sub_total;
                        // end
                        $item['added_amt'] = $post['item_added_amt_hidden'][$i];
                        $item['remark'] = $post['remark'][$i];
                        $new_item[] = $item;
                        $new_itempid[] = $post['pid'][$i];
                    } else {
                        $item['pid'] = $post['pid'][$i];
                        $item['hsn'] = "";
                        $item['qty'] = 0;
                        $item['uom'] = '';
                        $item['rate'] = $post['price'][$i];
                        $item['igst'] = $post['igst'][$i];
                        $item['cgst'] = $post['cgst'][$i];
                        $item['sgst'] = $post['sgst'][$i];
                        $item['igst_amt'] = $post['igst_amt'][$i];
                        $item['cgst_amt'] = $post['cgst_amt'][$i];
                        $item['sgst_amt'] = $post['sgst_amt'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                        //update discount column 17-01-2023
                        $item['total'] = $post['price'][$i];
                        $item['item_disc'] = 0;
                        $item['discount'] = 0;
                        $item['divide_disc_item_per'] = 0;
                        $item['divide_disc_item_amt'] = 0;
                        $item['sub_total'] = $post['price'][$i];
                        //end
                        $item['added_amt'] = $post['item_added_amt_hidden'][$i];
                        $item['remark'] = $post['remark'][$i];
                        $new_account[] = $item;
                        $new_accountpid[] = $post['pid'][$i];
                    }
                }
                //echo '<pre>';Print_r($new_item);exit;

                $getitem = explode(',', $getItem->item_id);
                $getAccount = explode(',', $getAccount->item_id);

                $delete_itemid = array_diff($getitem, $new_itempid);
                $delete_account = array_diff($getAccount, $new_accountpid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'challan'));
                        $item_builder->update($del_data);
                    }
                }
                if (!empty($delete_account)) {
                    foreach ($delete_account as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $account_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'challan'));
                        $account_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($new_item); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $new_item[$i]['pid'], "parent_id" => $post['id'], "type" => 'challan', 'is_delete' => 0, 'is_expence' => 0))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'hsn' => $new_item[$i]['hsn'],
                            'type' => 'challan',
                            'uom' => $new_item[$i]['uom'],
                            'rate' => $new_item[$i]['rate'],
                            'qty' => $new_item[$i]['qty'],
                            'igst' => $new_item[$i]['igst'],
                            'cgst' => $new_item[$i]['cgst'],
                            'sgst' => $new_item[$i]['sgst'],
                            'igst_amt' => $new_item[$i]['igst_amt'],
                            'cgst_amt' => $new_item[$i]['cgst_amt'],
                            'sgst_amt' => $new_item[$i]['sgst_amt'],
                            'taxability' => $new_item[$i]['taxability'],
                            //update discount column 17-01-2023
                            'total' =>$new_item[$i]['total'],
                            'item_disc' => $new_item[$i]['item_disc'],
                            'discount' => $new_item[$i]['discount'],
                            'divide_disc_item_per' => $new_item[$i]['divide_disc_item_per'],
                            'divide_disc_item_amt' => $new_item[$i]['divide_disc_item_amt'],
                            'sub_total' => $new_item[$i]['sub_total'],
                            // end
                            'added_amt' => $new_item[$i]['added_amt'],
                            'remark' => $new_item[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $item_builder->where(array('item_id' => $getItem->item_id, 'parent_id' => $post['id'], "type" => 'challan', 'is_delete' => 0, 'is_expence' => 0));
                        $res = $item_builder->update($item_data);
                    } else {

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'hsn' => $new_item[$i]['hsn'],
                            'type' => 'challan',
                            'uom' => $new_item[$i]['uom'],
                            'rate' => $new_item[$i]['rate'],
                            'qty' => $new_item[$i]['qty'],
                            'igst' => $new_item[$i]['igst'],
                            'cgst' => $new_item[$i]['cgst'],
                            'sgst' => $new_item[$i]['sgst'],
                            'igst_amt' => $new_item[$i]['igst_amt'],
                            'cgst_amt' => $new_item[$i]['cgst_amt'],
                            'sgst_amt' => $new_item[$i]['sgst_amt'],
                            'taxability' => $new_item[$i]['taxability'],
                            //update discount column 17-01-2023
                            'total' => $new_item[$i]['total'],
                            'item_disc' => $new_item[$i]['item_disc'],
                            'discount' => $new_item[$i]['discount'],
                            'divide_disc_item_per' => $new_item[$i]['divide_disc_item_per'],
                            'divide_disc_item_amt' => $new_item[$i]['divide_disc_item_amt'],
                            'sub_total' => $new_item[$i]['sub_total'],
                            // end
                            'added_amt' => $new_item[$i]['added_amt'],
                            'remark' => $new_item[$i]['remark'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                    }
                }

                for ($i = 0; $i < count($new_account); $i++) {
                    $account_result = $account_builder->select('*')->where(array("item_id" => $new_account[$i]['pid'], "parent_id" => $post['id'], 'type' => 'challan', 'is_delete' => 0, 'is_expence' => 1))->get();
                    $getAccount = $account_result->getRow();
                    if (!empty($getAccount)) {
                        $acc_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            'hsn' => '',
                            'type' => 'challan',
                            'uom' => '',
                            'rate' => $new_account[$i]['rate'],
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt' => $new_account[$i]['igst_amt'],
                            'cgst_amt' => $new_account[$i]['cgst_amt'],
                            'sgst_amt' => $new_account[$i]['sgst_amt'],
                            'taxability' => $new_account[$i]['taxability'],
                            //update discount column 17-01-2023
                            'total' => $new_account[$i]['total'],
                            'item_disc' => $new_account[$i]['item_disc'],
                            'discount' => $new_account[$i]['discount'],
                            'divide_disc_item_per' => $new_account[$i]['divide_disc_item_per'],
                            'divide_disc_item_amt' => $new_account[$i]['divide_disc_item_amt'],
                            'sub_total' => $new_account[$i]['sub_total'],
                            // end
                            'added_amt' => $new_account[$i]['added_amt'],
                            'remark' => $new_account[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getAccount->item_id, 'parent_id' => $post['id'], 'type' => 'challan', 'is_delete' => 0, 'is_expence' => 1));
                        $res = $account_builder->update($acc_data);
                    } else {

                        $acc_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            'hsn' => '',
                            'type' => 'challan',
                            'uom' => '',
                            'rate' => $new_account[$i]['rate'],
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt' => $new_account[$i]['igst_amt'],
                            'cgst_amt' => $new_account[$i]['cgst_amt'],
                            'sgst_amt' => $new_account[$i]['sgst_amt'],
                            'taxability' => $new_account[$i]['taxability'],
                            //update discount column 17-01-2023
                            'total' => $new_account[$i]['total'],
                            'item_disc' => $new_account[$i]['item_disc'],
                            'discount' => $new_account[$i]['discount'],
                            'divide_disc_item_per' => $new_account[$i]['divide_disc_item_per'],
                            'divide_disc_item_amt' => $new_account[$i]['divide_disc_item_amt'],
                            'sub_total' => $new_account[$i]['sub_total'],
                            // end
                            'added_amt' => $new_account[$i]['added_amt'],
                            'remark' => $new_account[$i]['remark'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $account_builder->insert($acc_data);
                    }
                }
                $builder = $db->table('purchase_challan');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    //return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {

           // echo '<pre>';Print_r($post);exit;

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {
                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;

                        $total = $post['qty'][$i] * $post['price'][$i];
                        if ($post['discount'] > 0) {
                            $sub_total = $total - @$post['divide_disc_amt'][$i];
                        } else {
                            $sub_total = $total -  @$post['item_discount_hidden'][$i];
                        }
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'is_expence' => 0,
                            'item_id' => $post['pid'][$i],
                            'hsn' => $post['hsn'][$i],
                            'type' => 'challan',
                            'uom' => $post['uom'][$i],
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            //update discount column 17-01-2023
                            'total' => $total,
                            'item_disc' => $post['item_disc'][$i],
                            'discount' => $post['item_discount_hidden'][$i],
                            'divide_disc_item_per' => $post['item_per'][$i],
                            'divide_disc_item_amt' => $post['divide_disc_amt'][$i],
                            'sub_total' => $sub_total,
                            // end
                            'added_amt' => $post['item_added_amt_hidden'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    } else {
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'is_expence' => 1,
                            'item_id' => $post['pid'][$i],
                            'hsn' => '',
                            'type' => 'challan',
                            'uom' => '',
                            'rate' => $post['price'][$i],
                            'qty' => 0,
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            //update discount column 17-01-2023
                            'total' => $post['price'][$i],
                            'item_disc' => 0,
                            'discount' => 0,
                            'divide_disc_item_per' => 0,
                            'divide_disc_item_amt' => 0,
                            'sub_total' => $post['price'][$i],
                            // end
                            'added_amt' => $post['item_added_amt_hidden'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    }
                }
                //echo '<pre>';Print_r($itemdata);exit;
                

                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);

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
    // update trupti 24-11-2022
    public function insert_edit_purchasereturn($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        //echo '<pre>';print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        // print_r($post);exit;
        $msg = array();

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];
        $item_disc = $post['item_disc'];
        $discount = $post['discount'];
        $cess = $post['cess'];
       
        $total = 0.0;
        $item_count = 0;
        $expence_count = 0;
        for ($i = 0; $i < count($pid); $i++) {

            if ($post['expence'][$i] == 0) {
                $item_count += 1;
            } else {
                $expence_count += 1;
            }
        }
        if ($expence_count > 0) {
            if ($item_count == 0) {
                $msg = array('st' => 'Fail', 'msg' => "Please select Item!!!");
                return $msg;
            }
        }


        for ($i = 0; $i < count($pid); $i++) {
            $disc_amt = 0;
            if ($post['expence'][$i] == 0) {
                $disc_amt = 0;
                if ($item_disc[$i] != 0) {
                    $sub = $post['qty'][$i] * $post['price'][$i];
                    $disc_amt = $sub * $item_disc[$i] / 100;
                }
                $final_sub = $post['qty'][$i] * $post['price'][$i] - @$disc_amt;
            } else {
                $final_sub = $post['price'][$i];
            }
            $total += $final_sub;
        }
        // discount calculation modification update 16-01-2023
        //$total = 0;
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {

                if ($post['discount'] > 0) {
                    $total = 0;
                    $item_total_amt = 0;
                    for ($i = 0; $i < count($pid); $i++) {
                      
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_total_amt += $sub;
                        }
                    }
                    $post['discount'] = $item_total_amt * $post['discount'] / 100;
                    for ($i = 0; $i < count($pid); $i++) {
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_per = ($sub * 100) / $item_total_amt;
                            $item_disc_amt = ($item_per / 100) * $post['discount'];
                            $final_sub = $sub - $item_disc_amt;
                        } else {
                            $final_sub = $post['price'][$i];
                        }


                        $total += $final_sub;
                    }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            }
            if ($post['discount'] > 0) {
                $total = 0;
                for ($i = 0; $i < count($pid); $i++) {

                    //$total = 0; 
                    $item_total_amt = 0;
                    for ($i = 0; $i < count($pid); $i++) {

                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_total_amt += $sub;
                        }
                    }

                    for ($i = 0; $i < count($pid); $i++) {
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_per = ($sub * 100) / $item_total_amt;
                            $item_disc_amt = ($item_per / 100) * $post['discount'];
                            $final_sub = $sub - $item_disc_amt;
                          
                        } else {
                            $final_sub = $post['price'][$i];
                        }
                        
                        $total += $final_sub;
                    }
                }
            }
        }


        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            } else {
                $post['cess'] = $total * $post['cess'] / 100;
            }
        } else {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            }
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total * $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }
        $date = db_date($post['return_date']);
        $due_date = db_date($post['due_date']);
        $lr_date = db_date($post['lr_date']);
        $netamount = $total  + $post['cess'] + $tds_amt + $post['tot_igst'];
        if (isset($post['taxes'])) {
            if (!empty($post['taxes'])) {
                if (in_array('igst', $post['taxes'])) {
                    $igst_acc = $post['igst_acc'];
                    $cgst_acc = "";
                    $sgst_acc = "";
                } else {
                    $igst_acc = "";
                    $cgst_acc = $post['cgst_acc'];
                    $sgst_acc = $post['sgst_acc'];
                }
            } else {
                $igst_acc = "";
                $cgst_acc = "";
                $sgst_acc = "";
            }
        } else {
            $igst_acc = "";
            $cgst_acc = "";
            $sgst_acc = "";
        }

        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $post['gl_group'],
            'return_no' => $post['return_no'],
            'return_date' => $date,
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst_no'],
            'broker' => @$post['broker'],
            'other' => $post['other'],
            'invoice' => @$post['invoice'],
            'lr_no' => $post['lr_no'],
            'lr_date' => $lr_date,
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'city' => @$post['city'],
            'transport' => @$post['transport'],
            'transport_mode' => $post['transport_mode'],
            'vehicle' => @$post['vehicle'],
            'due_days' => $post['due_days'],
            'due_date' => $due_date,
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'taxes' => json_encode(@$post['taxes']),
            'cess_type' => $post['cess_type'],
            'cess' => @$cess,
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'total_amount' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'net_amount' => round($netamount),
            'brokerage_type' => @$post['brokerage_type'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        if ($post['gst_no'] != '') {
            if (in_array('Taxable', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $post['taxability']) && !in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }
        //echo '<pre>';Print_r($post);exit;
        $gnmodel = new GeneralModel();
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                //print_r($post['pid']);exit;
                //    echo $db->getLastQuery();exit;
                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return','expence_type'=>'', "is_delete" => 0, "is_expence" => 0))->get();
                $getItem = $item_result->getRow();

                $account_builder = $db->table('purchase_item');
                $account_result = $account_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return','expence_type'=>'', "is_delete" => 0, "is_expence" => 1))->get();
                $getAccount = $account_result->getRow();

                $account_builder = $db->table('purchase_item');
                $discount_ac_result = $account_builder->select('item_id')->where(array("parent_id" => $post['id'], "type" => 'return', "is_delete" => 0,'expence_type'=>'discount', "is_expence" => 1))->get();
                $getDiscount = $discount_ac_result->getRow();

                $account_builder = $db->table('purchase_item');
                $round_ac_result = $account_builder->select('item_id')->where(array("parent_id" => $post['id'], "type" => 'return', "is_delete" => 0,'expence_type'=>'rounding_invoices', "is_expence" => 1))->get();
                $getRound = $round_ac_result->getRow();
                if(!empty($getDiscount))
                {
                    if(isset($post['discount_acc']) AND !empty($post['discount_amount_new']))
                    {
                        $disc_data = array(
                            'item_id' => $post['discount_acc'],
                            'rate' => $post['discount_amount_new'],
                            'total' => $post['discount_amount_new'],
                            'sub_total' => $post['discount_amount_new'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getDiscount->item_id, 'parent_id' => $post['id'], 'type' => 'return','expence_type'=>'discount'));
                        $account_builder->update($disc_data);
                    }
                    else
                    {
                        $result_up = $gnmodel->update_data_table('purchase_item', array('item_id' => $getDiscount->item_id, 'parent_id' => $post['id'], 'type' => 'return','expence_type'=>'discount'), array('is_delete' => '1'));
                    }
                }
                else
                {
                    if(isset($post['discount_acc']) AND !empty($post['discount_amount_new']))
                    {
                        $discount_itemdata[] = array(
                            'parent_id' => $post['id'],
                            'expence_type'=>'discount',
                            'is_expence' => 1,
                            'item_id' => $post['discount_acc'],
                            'hsn' => '',
                            'type' => 'return',
                            'uom' => '',
                            'rate' => $post['discount_amount_new'],
                            'qty' => 0,
                            'igst' => '',
                            'cgst' => '',
                            'sgst' => '',
                            'igst_amt' => '',
                            'cgst_amt' => '',
                            'sgst_amt' => '',
                            'taxability' => '',
                            //update discount column 17-01-2023
                            'total' => $post['discount_amount_new'],
                            'item_disc' => 0,
                            'discount' => 0,
                            'divide_disc_item_per' => 0,
                            'divide_disc_item_amt' => 0,
                            'sub_total' => $post['discount_amount_new'],
                            // end
                            'added_amt' => '',
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $item_builder = $db->table('purchase_item');
                        $result3 = $item_builder->insertBatch($discount_itemdata);
                    }
                }

                if(!empty($getRound))
                {
                    if(isset($post['round']) AND $post['round_diff'] != 0)
                    {
                        $round_data = array(
                            'item_id' => $post['round'],
                            'rate' => $post['round_diff'],
                            'total' => $post['round_diff'],
                            'sub_total' => $post['round_diff'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getRound->item_id, 'parent_id' => $post['id'], 'type' => 'return','expence_type'=>'rounding_invoices'));
                        $account_builder->update($round_data);
                    }
                    else
                    {
                        $result_up = $gnmodel->update_data_table('purchase_item', array('item_id' => $getRound->item_id, 'parent_id' => $post['id'], 'type' => 'return','expence_type'=>'rounding_invoices'), array('is_delete' => '1'));
             
                    }
                }
                else
                {
                    if(isset($post['round']) AND $post['round_diff'] != 0)
                    {
                        $round_itemdata[] = array(
                            'parent_id' => $post['id'],
                            'expence_type'=>'rounding_invoices',
                            'is_expence' => 1,
                            'item_id' => $post['round'],
                            'hsn' => '',
                            'type' => 'return',
                            'uom' => '',
                            'rate' => $post['round_diff'],
                            'qty' => 0,
                            'igst' => '',
                            'cgst' => '',
                            'sgst' => '',
                            'igst_amt' => '',
                            'cgst_amt' => '',
                            'sgst_amt' => '',
                            'taxability' => '',
                            //update discount column 17-01-2023
                            'total' => $post['round_diff'],
                            'item_disc' => 0,
                            'discount' => 0,
                            'divide_disc_item_per' => 0,
                            'divide_disc_item_amt' => 0,
                            'sub_total' => $post['round_diff'],
                            // end
                            'added_amt' =>'',
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $item_builder = $db->table('purchase_item');
                        $result2 = $item_builder->insertBatch($round_itemdata);
                    }
                }

                $new_item = array();
                $new_itempid = array();
                $new_account = array();
                $new_accountpid = array();
                // print_r($getItem);exit;
                for ($i = 0; $i < count($post['pid']); $i++) {

                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;

                        $total = $post['qty'][$i] * $post['price'][$i];
                        if ($post['discount'] > 0) {
                            $sub_total = $total - @$post['divide_disc_amt'][$i];
                        } else {
                            $sub_total = $total -  @$post['item_discount_hidden'][$i];
                        }
                        $item['pid'] = $post['pid'][$i];
                        $item['qty'] = $post['qty'][$i];
                        $item['hsn'] = @$post['hsn'][$i];
                        $item['uom'] = $post['uom'][$i];
                        $item['rate'] = $post['price'][$i];
                        $item['igst'] = $post['igst'][$i];
                        $item['cgst'] = $post['cgst'][$i];
                        $item['sgst'] = $post['sgst'][$i];
                        $item['igst_amt'] = $post['igst_amt'][$i];
                        $item['cgst_amt'] = $post['cgst_amt'][$i];
                        $item['sgst_amt'] = $post['sgst_amt'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                          //update discount column 17-01-2023
                          $item['total'] = $total;
                          $item['item_disc'] = $post['item_disc'][$i];
                          $item['discount'] = $post['item_discount_hidden'][$i];
                          $item['divide_disc_item_per'] = @$post['item_per'][$i];
                          $item['divide_disc_item_amt'] = @$post['divide_disc_amt'][$i];
                          $item['sub_total'] = $sub_total;
                          // end
                          $item['added_amt'] = $post['item_added_amt_hidden'][$i];
                          $item['remark'] = $post['remark'][$i];
                        $new_item[] = $item;
                        $new_itempid[] = $post['pid'][$i];
                    } else {
                        $item['pid'] = $post['pid'][$i];
                        $item['qty'] = $post['qty'][$i];
                        $item['hsn'] = @$post['hsn'][$i];
                        $item['uom'] = $post['uom'][$i];
                        $item['rate'] = $post['price'][$i];
                        $item['igst'] = $post['igst'][$i];
                        $item['cgst'] = $post['cgst'][$i];
                        $item['sgst'] = $post['sgst'][$i];
                        $item['igst_amt'] = $post['igst_amt'][$i];
                        $item['cgst_amt'] = $post['cgst_amt'][$i];
                        $item['sgst_amt'] = $post['sgst_amt'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                         //update discount column 17-01-2023
                         $item['total'] = $post['price'][$i];
                         $item['item_disc'] = 0;
                         $item['discount'] = 0;
                         $item['divide_disc_item_per'] = 0;
                         $item['divide_disc_item_amt'] = 0;
                         $item['sub_total'] = $post['price'][$i];
                         //end
                         $item['added_amt'] = $post['item_added_amt_hidden'][$i];
                         $item['remark'] = $post['remark'][$i];
                        $new_account[] = $item;
                        $new_accountpid[] = $post['pid'][$i];
                    }
                }


                $getitem = explode(',', $getItem->item_id);
                $getAccount = explode(',', $getAccount->item_id);

                $delete_itemid = array_diff($getitem, $new_itempid);
                $delete_account = array_diff($getAccount, $new_accountpid);
                //print_r($new_item);exit;

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'return'));
                        $item_builder->update($del_data);
                    }
                }
                if (!empty($delete_account)) {
                    foreach ($delete_account as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $account_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'return'));
                        $account_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($new_item); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $new_item[$i]['pid'], "parent_id" => $post['id'], 'type' => 'return'))->get();
                    $getItem = $item_result->getRow();
                    // print_r($getItem->qty);
                    // print_r($post['qty'][$i]);exit;

                    if (!empty($getItem)) {
                        // $qty = $post['qty'][$i] - is_int($getItem->qty);

                        $item_data = array(
                            // 'parent_id'=> $post['id'],
                            // 'is_expence' =>0,
                            // 'item_id'=> $new_item[$i]['pid'],
                            // 'type'=> 'challan',
                            'hsn' => @$new_item[$i]['hsn'],
                            'uom' => $new_item[$i]['uom'],
                            'rate' => $new_item[$i]['rate'],
                            'qty' => $new_item[$i]['qty'],
                            'igst' => $new_item[$i]['igst'],
                            'cgst' => $new_item[$i]['cgst'],
                            'sgst' => $new_item[$i]['sgst'],
                            'igst_amt' => $new_item[$i]['igst_amt'],
                            'cgst_amt' => $new_item[$i]['cgst_amt'],
                            'sgst_amt' => $new_item[$i]['sgst_amt'],
                             //update discount column 17-01-2023
                             'total' =>$new_item[$i]['total'],
                             'item_disc' => $new_item[$i]['item_disc'],
                             'discount' => $new_item[$i]['discount'],
                             'divide_disc_item_per' => $new_item[$i]['divide_disc_item_per'],
                             'divide_disc_item_amt' => $new_item[$i]['divide_disc_item_amt'],
                             'sub_total' => $new_item[$i]['sub_total'],
                             // end
                             'added_amt' => $new_item[$i]['added_amt'],
                             'remark' => $new_item[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $item_builder->where(array('item_id' =>   $getItem->item_id, 'parent_id' => $post['id'], 'type' => 'return'));
                        $res = $item_builder->update($item_data);
                    } else {

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'type' => 'return',
                            'hsn' => @$new_item[$i]['hsn'],
                            'uom' => $new_item[$i]['uom'],
                            'rate' => $new_item[$i]['rate'],
                            'qty' => $new_item[$i]['qty'],
                            'igst' => $new_item[$i]['igst'],
                            'cgst' => $new_item[$i]['cgst'],
                            'sgst' => $new_item[$i]['sgst'],
                            'igst_amt' => $new_item[$i]['igst_amt'],
                            'cgst_amt' => $new_item[$i]['cgst_amt'],
                            'sgst_amt' => $new_item[$i]['sgst_amt'],
                            'taxability' => $new_item[$i]['taxability'],
                             //update discount column 17-01-2023
                             'total' => $new_item[$i]['total'],
                             'item_disc' => $new_item[$i]['item_disc'],
                             'discount' => $new_item[$i]['discount'],
                             'divide_disc_item_per' => $new_item[$i]['divide_disc_item_per'],
                             'divide_disc_item_amt' => $new_item[$i]['divide_disc_item_amt'],
                             'sub_total' => $new_item[$i]['sub_total'],
                             // end
                             'added_amt' => $new_item[$i]['added_amt'],
                             'remark' => $new_item[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                    }
                }
                for ($i = 0; $i < count($new_account); $i++) {
                    $account_result = $account_builder->select('*')->where(array("item_id" => $new_account[$i]['pid'], "parent_id" => $post['id'], 'type' => 'return'))->get();
                    $getAccount = $account_result->getRow();
                    if (!empty($getAccount)) {

                        $item_data = array(
                            // 'parent_id'=> $post['id'],
                            // 'is_expence' =>1,
                            // 'item_id'=> $new_account[$i]['pid'],
                            // 'type'=> 'challan',
                            "hsn" => '',
                            'uom' => '',
                            'rate' => $new_account[$i]['rate'],
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt' => $new_account[$i]['igst_amt'],
                            'cgst_amt' => $new_account[$i]['cgst_amt'],
                            'sgst_amt' => $new_account[$i]['sgst_amt'],
                            'taxability' => $new_account[$i]['taxability'],
                            //update discount column 17-01-2023
                            'total' => $new_account[$i]['total'],
                            'item_disc' => $new_account[$i]['item_disc'],
                            'discount' => $new_account[$i]['discount'],
                            'divide_disc_item_per' => $new_account[$i]['divide_disc_item_per'],
                            'divide_disc_item_amt' => $new_account[$i]['divide_disc_item_amt'],
                            'sub_total' => $new_account[$i]['sub_total'],
                            // end
                            'added_amt' => $new_account[$i]['added_amt'],
                            'remark' => $new_account[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getAccount->item_id, 'parent_id' => $post['id']));
                        $res = $account_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            "hsn" => '',
                            'type' => 'return',
                            'uom' => '',
                            'rate' => $new_account[$i]['rate'],
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt' => $new_account[$i]['igst_amt'],
                            'cgst_amt' => $new_account[$i]['cgst_amt'],
                            'sgst_amt' => $new_account[$i]['sgst_amt'],
                            'taxability' => $new_account[$i]['taxability'],
                            //update discount column 17-01-2023
                            'total' => $new_account[$i]['total'],
                            'item_disc' => $new_account[$i]['item_disc'],
                            'discount' => $new_account[$i]['discount'],
                            'divide_disc_item_per' => $new_account[$i]['divide_disc_item_per'],
                            'divide_disc_item_amt' => $new_account[$i]['divide_disc_item_amt'],
                            'sub_total' => $new_account[$i]['sub_total'],
                            // end
                            'added_amt' => $new_account[$i]['added_amt'],
                            'remark' => $new_account[$i]['remark'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $account_builder->insert($item_data);
                    }
                }
                $builder = $db->table('purchase_return');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    //return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {

                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;

                        $total = $post['qty'][$i] * $post['price'][$i];
                        if ($post['discount'] > 0) {
                            $sub_total = $total - @$post['divide_disc_amt'][$i];
                        } else {
                            $sub_total = $total -  @$post['item_discount_hidden'][$i];
                        }
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'is_expence' => 0,
                            'item_id' => $post['pid'][$i],
                             'hsn' => @$post['hsn'][$i] ? $post['hsn'][$i]  : '',
                            'type' => 'return',
                            'uom' => $post['uom'][$i],
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                              //update discount column 17-01-2023
                              'total' => $total,
                              'item_disc' => $post['item_disc'][$i],
                              'discount' => $post['item_discount_hidden'][$i],
                              'divide_disc_item_per' => $post['item_per'][$i],
                              'divide_disc_item_amt' => $post['divide_disc_amt'][$i],
                              'sub_total' => $sub_total,
                              // end
                              'added_amt' => $post['item_added_amt_hidden'][$i],
                              'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    } else {
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'is_expence' => 1,
                            'item_id' => $post['pid'][$i],
                            'hsn' => @$post['hsn'][$i] ? $post['hsn'][$i]  : '',
                            'type' => 'return',
                            'uom' => '',
                            'rate' => $post['price'][$i],
                            'qty' => 0,
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                             //update discount column 17-01-2023
                             'total' => $post['price'][$i],
                             'item_disc' => 0,
                             'discount' => 0,
                             'divide_disc_item_per' => 0,
                             'divide_disc_item_amt' => 0,
                             'sub_total' => $post['price'][$i],
                             // end
                             'added_amt' => $post['item_added_amt_hidden'][$i],
                             'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    }
                }
                //echo '<pre>';print_r($itemdata);exit;
                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);
                if(isset($post['round']) AND $post['round_diff'] != 0)
                {
                    $round_itemdata[] = array(
                        'parent_id' => $id,
                        'expence_type'=>'rounding_invoices',
                        'is_expence' => 1,
                        'item_id' => $post['round'],
                        'hsn' => '',
                        'type' => 'return',
                        'uom' => '',
                        'rate' => $post['round_diff'],
                        'qty' => 0,
                        'igst' => '',
                        'cgst' => '',
                        'sgst' => '',
                        'igst_amt' => '',
                        'cgst_amt' => '',
                        'sgst_amt' => '',
                        'taxability' => '',
                        //update discount column 17-01-2023
                        'total' => $post['round_diff'],
                        'item_disc' => 0,
                        'discount' => 0,
                        'divide_disc_item_per' => 0,
                        'divide_disc_item_amt' => 0,
                        'sub_total' => $post['round_diff'],
                        // end
                        'added_amt' =>'',
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('purchase_item');
                    $result2 = $item_builder->insertBatch($round_itemdata);
                }
                if(isset($post['discount_acc']) AND !empty($post['discount_amount_new']))
                {
                    $discount_itemdata[] = array(
                        'parent_id' => $id,
                        'expence_type'=>'discount',
                        'is_expence' => 1,
                        'item_id' => $post['discount_acc'],
                        'hsn' => '',
                        'type' => 'return',
                        'uom' => '',
                        'rate' => $post['discount_amount_new'],
                        'qty' => 0,
                        'igst' => '',
                        'cgst' => '',
                        'sgst' => '',
                        'igst_amt' => '',
                        'cgst_amt' => '',
                        'sgst_amt' => '',
                        'taxability' => '',
                        //update discount column 17-01-2023
                        'total' => $post['discount_amount_new'],
                        'item_disc' => 0,
                        'discount' => 0,
                        'divide_disc_item_per' => 0,
                        'divide_disc_item_amt' => 0,
                        'sub_total' => $post['discount_amount_new'],
                        // end
                        'added_amt' => '',
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('purchase_item');
                    $result3 = $item_builder->insertBatch($discount_itemdata);
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


    public function get_purchasereturn_data($get)
    {
        $dt_search = array(
            "pr.id",
            "pr.return_no",
            "pr.return_date",
            "pr.net_amount",
            "pr.other",
            "(select name from account ac where pr.account = ac.id)",

        );
        $dt_col = array(
            "pr.id",
            "pr.return_no",
            "pr.return_date",
            "pr.account",
            "(select name from account ac where pr.account = ac.id) as account_name",
            "pr.net_amount",
            "pr.other",
            "(select name from account br where pr.broker = br.id) as broker_name",
            "pr.is_delete",
            "pr.is_cancle",
        );

        $filter = $get['filter_data'];
        $tablename = "purchase_return pr";
        $where = '';

        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $gmodel = new GeneralModel();
        foreach ($rResult['table'] as $row) {

            $DataRow = array();
            $btnview = '<a href="' . url('purchase/purchase_return_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btnedit = '<a   href="' . url('purchase/add_purchasereturn/') . $row['id'] . '" data-title="Edit Purchase Return : ' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Purchase return: ' . $row['account'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btn_cancle = '<a data-toggle="modal" target="_blank"   title="Cancle Return Invoice: ' . $row['id'] . '"  onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-times-circle"></i></a> ';
            $getMax = $gmodel->get_data_table('purchase_return', array('is_delete' => 0), 'MAX(return_no) as max_no');
            $btnpdf = '<a href="' . url('Purchase/pdf_return/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';
           
            if ($row['is_cancle'] == 1 || $row['is_delete'] == 1) {
                $btn =  $btnview . $btnpdf;
            } else {
                $btn =  $btnedit . $btnview . $btnpdf;
            }

            if ($getMax['max_no'] == $row['return_no']) {
                $btn .= $btndelete;
            } else {
                if ($row['is_cancle'] == 0) {
                    $btn .= $btn_cancle;
                }
            }

            if (!empty($row['gst'])) {
                $gst = '<br>(' . $row['gst'] . ')';
            } else {
                $gst = '';
            }

            $DataRow[] = $row['return_no'];
            $DataRow[] = user_date($row['return_date']);
            $DataRow[] = $row['account_name'] . $gst;
            $DataRow[] = $row['net_amount'];
            $DataRow[] = $row['other'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    // update trupti 24-11-2022
    public function get_gnlPur_byid($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_general pg');
        $builder->select('pg.*,ac.name as party_name');
        $builder->join('account ac', 'ac.id = pg.party_account');
        $builder->where(array('pg.id' => $id));
        $query = $builder->get();
        $general = $query->getResultArray();

        $getdata['general'] = $general[0];
        $gmodel = new GeneralModel();
        foreach ($general as $row) {

            $total_return = $gmodel->get_data_table('purchase_general', array('return_purchase' => $row['return_purchase'], 'v_type' => 'return'), 'SUM(net_amount) as total');
            $getreturn = $gmodel->get_data_table('purchase_general', array('id' => $row['return_purchase'], 'v_type' => 'general'), 'id,net_amount,doc_date');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $getround = $gmodel->get_data_table('account', array('id' => $row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            $getdata['general']['voucher_name'] = @$getvoucher['name'];
            $getdata['general']['round_name'] = @$getround['name'];
            $getdata['general']['return_pur_name'] = '(' . @$getreturn['id'] . ') - ' . @$getreturn['doc_date'] . '-' . @$row['party_name'] . '- ₹' . (@$getreturn['net_amount'] + @$row['net_amount'] - @$total_return['total']);
            $getdata['general']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['general']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['general']['cgst_acc_name'] = @$cgst_acc['name'];
        }
        $item_builder = $db->table('purchase_particu sp');
        $item_builder->select('sp.*,ac.name as account_name,ac.code as code,ac.hsn');
        $item_builder->join('account ac', 'ac.id = sp.account');
        $item_builder->where(array('sp.parent_id' => $id,  'sp.is_delete' => 0));
        $query = $item_builder->get();
        $getdata['acc'] = $query->getResultArray();
        // echo '<pre>';print_r($getdata);exit;
        return $getdata;
    }

    public function get_general_purchase_data($get)
    {

        $dt_search = array(

            "pg.invoice_no",
            "pg.party_account",
            "pg.v_type",
            "pg.supp_inv",
            "pg.status",
            "pg.net_amount",
        );
        $dt_col = array(
            "pg.invoice_no",
            "pg.id",
            "pg.party_account",
            "pg.v_type",
            "pg.voucher_type",
            "(select name from account ac where pg.party_account = ac.id) as party_account_name",
            "pg.supp_inv",
            "pg.net_amount",
            "pg.is_cancle",
            "pg.is_delete",
            "pg.status",
        );

        $filter = $get['filter_data'];
        $tablename = "purchase_general pg";
        $where = '';

        if ($filter != '' && $filter != 'undefined') {
            $where .= ' and v_type ="' . $filter . '"';
        }
        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $statusarray = array("1" => "Activate", "0" => "Deactivate");
        $gmodel = new GeneralModel();
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title=" ' . $row['party_account_name'] . '" onclick="editable_os(this)"  data-val="' . $row['is_cancle'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="" title="' . $statusarray[$row['is_cancle']] . '"><i class="far fa-times-circle"></a>';

            $btnview = '<a href="' . url('purchase/purchase_general_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btnedit = '<a href="' . url('purchase/add_general_pur/') . $row['v_type'] . '/' . $row['id'] . '"  class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Ac Invoice Id: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $status = '<a target="_blank"   title="Item Invoice Id: ' . $row['id'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';

            $getMax = $gmodel->get_data_table('purchase_general', array('is_delete' => 0, 'v_type' => $filter), 'MAX(invoice_no) as max_no');
            $btnpdf = '<a href="' . url('Purchase/pdf_general/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';


            if ($row['is_cancle'] == 1 || $row['is_delete'] == 1) {
                $btn =  $btnview . $btnpdf;
            } else {
                $btn =  $btnedit . $btnview . $btnpdf;
            }

            if ($getMax['max_no'] == $row['invoice_no']) {
                if ($row['is_cancle'] != 1) {
                    $btn .= $btndelete;
                }
            } else {
                if ($row['is_cancle'] == 0) {
                    $btn .= $btn_cancle;
                }
            }

            $DataRow[] = $row['invoice_no'];
            $DataRow[] = $row['v_type'];
            $DataRow[] = $row['party_account_name'];
            $DataRow[] = $row['supp_inv'];
            $DataRow[] = $row['net_amount'];
            $DataRow[] = ($row['is_cancle'] == 1) ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    // update trupti 24-11-2022
    public function insert_edit_general_pur($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array('id' => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        $msg = array();

        $pid = $post['pid'];
        $price = $post['price'];
        $igst = $post['igst'];
        $cgst = $post['cgst'];
        $sgst = $post['sgst'];
        $discount = $post['discount'];
        $amty = $post['amty'];
        $total = 0.0;
        // echo '<pre>';print_r($post);exit;
        for ($i = 0; $i < count($price); $i++) {
            $sub = $post['price'][$i];
            $total += $post['price'][$i];
        }

        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {
                $post['discount'] = $total * $post['discount'] / 100;
                if ($post['discount'] > 0) {
                    $total = 0;
                    for ($i = 0; $i < count($pid); $i++) {
                        $disc_amt = 0;
                        $devide_disc = $post['discount'] / count($pid);

                        $sub =  $post['price'][$i];
                        $total +=  $post['price'][$i] - $devide_disc;
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

                    $sub = $post['price'][$i];
                    $total += $post['price'][$i] - $devide_disc;
                }
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

        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            } else {
                $post['cess'] = $total * $post['cess'] / 100;
            }
        } else {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            }
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total * $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }

        $netamount = $total + $post['amty'] + $post['tot_igst'];

        if (in_array('tds', $post['taxes'])) {
            $netamount += $tds_amt;
        }
        if (in_array('cess', $post['taxes'])) {
            $netamount += $post['cess'];
        }
        // $netamount=$total-$post['amtx'] + $post['amty'] + $post['tot_igst'];
        $date = db_date($post['doc_date']);
        if (isset($post['taxes'])) {
            if (!empty($post['taxes'])) {
                if (in_array('igst', $post['taxes'])) {
                    $igst_acc = $post['igst_acc'];
                    $cgst_acc = "";
                    $sgst_acc = "";
                } else {
                    $igst_acc = "";
                    $cgst_acc = $post['cgst_acc'];
                    $sgst_acc = $post['sgst_acc'];
                }
            } else {
                $igst_acc = "";
                $cgst_acc = "";
                $sgst_acc = "";
            }
        } else {
            $igst_acc = "";
            $cgst_acc = "";
            $sgst_acc = "";
        }
        $pdata = array(
            'invoice_no' => $post['invoice_no'],
            'gl_group' => $post['gl_group'],
            'doc_date' => $date,
            'voucher_type' => @$post['voucher_type'],
            'party_account' => @$post['party_account'],
            'gst_no' => @$post['gst_no'],
            'v_type' => @$post['v_type'],
            'return_purchase' => @$post['invoice'],
            'other' => @$post['other'],
            'tds_per' => @$post['tds_per'],
            'tds_amt' => $post['tds_amt'],
            'tds_limit' => @$post['tds_limit'],
            'acc_state' => @$post['acc_state'],
            'taxes' => json_encode($post['taxes']),
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'cess' => @$post['cess'],
            'cess_type' => @$post['cess_type'],
            'amty' => $amty,
            'amty_type' => $post['amty_type'],
            'supp_inv' => $post['supp_inv'],
            'supp_inv_date' => @$post['supp_inv_date']  ? db_date($post['supp_inv_date']) : '',
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'total_amount' => $total,
            'net_amount' => $netamount + ($post['round_diff'] ? $post['round_diff']  : 0),
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' => @$cgst_acc,
            'sgst_acc' => @$sgst_acc,
        );
        if ($post['gst_no'] != '') {
            if (in_array('Taxable', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $post['taxability']) && !in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }
        //echo '<pre>';Print_r($post);exit;

        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $account_builder = $db->table('purchase_particu');
                $account_result = $account_builder->select('GROUP_CONCAT(account) as account')->where(array("parent_id" => $post['id'], "type" => $post['v_type']))->get();
                $getAccount = $account_result->getRow();

                $getpid = explode(',', $getAccount->account);
                $delete_itemid = array_diff($getpid, $pid);

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $account_builder->where(array('account' => $del_id, 'parent_id' => $post['id'], 'type' => $post['v_type']));
                        $account_builder->update($del_data);
                    }
                }
                for ($i = 0; $i < count($pid); $i++) {
                    $account_result = $account_builder->select('*')->where(array("account" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getAccount = $account_result->getRow();
                    if ($post['discount'] > 0) {
                        $sub_total = $post['subtotal'][$i] - $post['item_discount_hidden'][$i];
                    } else {
                        $sub_total = $post['subtotal'][$i];
                    }
                    if (!empty($getAccount)) {
                        $account_data = array(

                            'amount' => $post['price'][$i],
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'remark' => $post['remark'][$i],
                            'sub_total' => $sub_total,
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            'discount' => $post['item_discount_hidden'][$i],
                            'added_amt' => $post['item_added_amt_hidden'][$i],
                            'is_delete' => 0,
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('account' => $pid[$i], 'parent_id' => $post['id']));
                        $res = $account_builder->update($account_data);
                    } else {
                        $account_data = array(
                            'parent_id' => $post['id'],
                            'account' => $post['pid'][$i],
                            'type' => @$post['v_type'],
                            'amount' => $post['price'][$i],
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'remark' => $post['remark'][$i],
                            'sub_total' => $sub_total,
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            'discount' => $post['item_discount_hidden'][$i],
                            'added_amt' => $post['item_added_amt_hidden'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $account_builder->insert($account_data);
                    }
                    $account_builder->where(array('parent_id' => $post['id'], 'account' => $post['pid'][$i], "type" => 'challan'));
                    $result1 = $account_builder->update($account_data);
                }

                $builder = $db->table('purchase_particu');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    //return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {
                    $sub_total = 0;
                    if ($post['discount'] > 0) {
                        $sub_total = $post['subtotal'][$i] - $post['item_discount_hidden'][$i];
                    } else {
                        $sub_total = $post['subtotal'][$i];
                    }
                    $accountdata[] = array(
                        'parent_id' => $id,
                        'account' => $post['pid'][$i],
                        'type' => @$post['v_type'],
                        'amount' => $post['price'][$i],
                        'igst' => $post['igst'][$i],
                        'cgst' => $post['cgst'][$i],
                        'sgst' => $post['sgst'][$i],
                        'remark' => $post['remark'][$i],
                        'sub_total' => $sub_total,
                        'igst_amt' => $post['igst_amt'][$i],
                        'cgst_amt' => $post['cgst_amt'][$i],
                        'sgst_amt' => $post['sgst_amt'][$i],
                        'taxability' => $post['taxability'][$i],
                        'discount' => $post['item_discount_hidden'][$i],
                        'added_amt' => $post['item_added_amt_hidden'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                }
                $account_builder = $db->table('purchase_particu');
                $result1 = $account_builder->insertBatch($accountdata);

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

    // update trupti 24-11-2022
    public function insert_edit_purchaseinvoice($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice');
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
        // $item_brokrage = $post['item_brokrage'];
        $item_disc = $post['item_disc'];
        $discount = $post['discount'];
        $cess = $post['cess'];
        $total = 0.0;
        $item_count = 0;
        $expence_count = 0;
        for ($i = 0; $i < count($pid); $i++) {

            if ($post['expence'][$i] == 0) {
                $item_count += 1;
            } else {
                $expence_count += 1;
            }
        }
        if ($expence_count > 0) {
            if ($item_count == 0) {
                $msg = array('st' => 'Fail', 'msg' => "Please select Item!!!");
                return $msg;
            }
        }

        if ($item_count == 0) {
            $msg = array('st' => 'Fail', 'msg' => "Please select Item!!!");
            return $msg;
        }

        for ($i = 0; $i < count($pid); $i++) {
            $disc_amt = 0;
            if ($post['expence'][$i] == 0) {
                $disc_amt = 0;
                if ($item_disc[$i] != 0) {
                    $sub = $post['qty'][$i] * $post['price'][$i];
                    $disc_amt = $sub * $item_disc[$i] / 100;
                }
                $final_sub = $post['qty'][$i] * $post['price'][$i] - @$disc_amt;
            } else {
                $final_sub = $post['price'][$i];
            }
            $total += $final_sub;
        }
        // discount calculation modification update 16-01-2023
        //$total = 0;
        if ($post['disc_type'] == '%') {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            } else {

                if ($post['discount'] > 0) {
                    $total = 0;
                    $item_total_amt = 0;
                    for ($i = 0; $i < count($pid); $i++) {
                    
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_total_amt += $sub;
                        }
                    }
                    $post['discount'] = $item_total_amt * $post['discount'] / 100;
                    for ($i = 0; $i < count($pid); $i++) {
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_per = ($sub * 100) / $item_total_amt;
                            $item_disc_amt = ($item_per / 100) * $post['discount'];
                            $final_sub = $sub - $item_disc_amt;
                        } else {
                            $final_sub = $post['price'][$i];
                        }


                        $total += $final_sub;
                    }
                }
            }
        } else {
            if ($post['discount'] == '') {
                $post['discount'] = 0;
            }
            if ($post['discount'] > 0) {
                $total = 0;
                for ($i = 0; $i < count($pid); $i++) {

                    //$total = 0; 
                    $item_total_amt = 0;
                    for ($i = 0; $i < count($pid); $i++) {

                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_total_amt += $sub;
                        }
                    }

                    for ($i = 0; $i < count($pid); $i++) {
                        if ($post['expence'][$i] == 0) {
                            $sub = $post['qty'][$i] * $post['price'][$i];
                            $item_per = ($sub * 100) / $item_total_amt;
                            $item_disc_amt = ($item_per / 100) * $post['discount'];
                            $final_sub = $sub - $item_disc_amt;
                       
                        } else {
                            $final_sub = $post['price'][$i];
                        }
                        //echo '<pre>';Print_r($final_sub);

                        $total += $final_sub;
                    }
                }
            }
        }
       
        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            } else {
                $post['cess'] = $total * $post['cess'] / 100;
            }
        } else {
            if ($post['cess'] == '') {
                $post['cess'] = 0;
            }
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total * $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }

        $lr_date = db_date($post['lr_date']);
        $invoice_date = db_date($post['invoice_date']);
        $due_date = db_date($post['due_date']);

        $netamount = $total + $post['cess'] + $tds_amt + $post['tot_igst'];
        //$netamount = $total + $post['cess'] + $post['amty'] + $tds_amt + $post['tot_igst'] + $post['round_diff'];


        if (isset($post['taxes'])) {
            if (!empty($post['taxes'])) {
                if (in_array('igst', $post['taxes'])) {
                    $igst_acc = $post['igst_acc'];
                    $cgst_acc = "";
                    $sgst_acc = "";
                } else {
                    $igst_acc = "";
                    $cgst_acc = $post['cgst_acc'];
                    $sgst_acc = $post['sgst_acc'];
                }
            } else {
                $igst_acc = "";
                $cgst_acc = "";
                $sgst_acc = "";
            }
        } else {
            $igst_acc = "";
            $cgst_acc = "";
            $sgst_acc = "";
        }
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $post['gl_group'],
            'invoice_date' => $invoice_date,
            'challan_no' => @$post['challan'] ?  $post['challan'] : '',
            'invoice_no' => $post['invoice_no'],
            'custom_inv_no' => @$post['custom_inv_no'] ? $post['custom_inv_no'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst_no' => $post['gst_no'],
            'sup_chl_no' => $post['party_bill'],
            'supply_inv' => $post['supply_inv'],
            'broker' => @$post['broker'],
            'brokerage_type' => $post['brokerage_type'],
            'other' => $post['other'],
            'lr_no' => $post['lr_no'],
            'lr_date' => $lr_date,
            'city' => @$post['city'],
            'transport' => @$post['transport'],
            'transport_mode' => $post['transport_mode'],
            'vehicle' => @$post['vehicle'],
            'due_days' => $post['due_days'],
            'due_date' => $due_date,
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'taxes' => json_encode(@$post['taxes']),
            'cess_type' => $post['cess_type'],
            'cess' => @$cess,
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'total_amount' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'net_amount' => round($netamount),
            'brokerage_type' => @$post['brokerage_type'],
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'igst_acc' => @$igst_acc,
            'cgst_acc' =>  @$cgst_acc,
            'sgst_acc' =>  @$sgst_acc,
        );
        if ($post['gst_no'] != '') {
            if (in_array('Taxable', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else if (!in_array('Taxable', $post['taxability']) && in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        } else {
            if (in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Exempt';
            } else if (in_array('Taxable', $post['taxability']) && !in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Taxable';
            } else if (in_array('Taxable', $post['taxability']) && in_array('Nill', $post['taxability']) && !in_array('Exempt', $post['taxability'])) {

                $pdata['inv_taxability'] = 'Nill';
            } else {
                $pdata['inv_taxability'] = 'N/A';
            }
        }
        $gnmodel = new GeneralModel();
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                $item_builder = $db->table('purchase_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0,'expence_type'=>'',  "is_expence" => 0))->get();
                $getItem = $item_result->getRow();

                $account_builder = $db->table('purchase_item');
                $account_result = $account_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0,'expence_type'=>'', "is_expence" => 1))->get();
                $getAccount = $account_result->getRow();

                $account_builder = $db->table('purchase_item');
                $discount_ac_result = $account_builder->select('item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0,'expence_type'=>'discount', "is_expence" => 1))->get();
                $getDiscount = $discount_ac_result->getRow();

                $account_builder = $db->table('purchase_item');
                $round_ac_result = $account_builder->select('item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0,'expence_type'=>'rounding_invoices', "is_expence" => 1))->get();
                $getRound = $round_ac_result->getRow();
                if(!empty($getDiscount))
                {
                    if(isset($post['discount_acc']) AND !empty($post['discount_amount_new']))
                    {
                        $disc_data = array(
                            'item_id' => $post['discount_acc'],
                            'rate' => $post['discount_amount_new'],
                            'total' => $post['discount_amount_new'],
                            'sub_total' => $post['discount_amount_new'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getDiscount->item_id, 'parent_id' => $post['id'], 'type' => 'invoice','expence_type'=>'discount'));
                        $account_builder->update($disc_data);
                    }
                    else
                    {
                        $result_up = $gnmodel->update_data_table('purchase_item', array('item_id' => $getDiscount->item_id, 'parent_id' => $post['id'], 'type' => 'invoice','expence_type'=>'discount'), array('is_delete' => '1'));
                    }
                }
                else
                {
                    if(isset($post['discount_acc']) AND !empty($post['discount_amount_new']))
                    {
                        $discount_itemdata[] = array(
                            'parent_id' => $post['id'],
                            'expence_type'=>'discount',
                            'is_expence' => 1,
                            'item_id' => $post['discount_acc'],
                            'hsn' => '',
                            'type' => 'invoice',
                            'uom' => '',
                            'rate' => $post['discount_amount_new'],
                            'qty' => 0,
                            'igst' => '',
                            'cgst' => '',
                            'sgst' => '',
                            'igst_amt' => '',
                            'cgst_amt' => '',
                            'sgst_amt' => '',
                            'taxability' => '',
                            //update discount column 17-01-2023
                            'total' => $post['discount_amount_new'],
                            'item_disc' => 0,
                            'discount' => 0,
                            'divide_disc_item_per' => 0,
                            'divide_disc_item_amt' => 0,
                            'sub_total' => $post['discount_amount_new'],
                            // end
                            'added_amt' => '',
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $item_builder = $db->table('purchase_item');
                        $result3 = $item_builder->insertBatch($discount_itemdata);
                    }
                }

                if(!empty($getRound))
                {
                    if(isset($post['round']) AND $post['round_diff'] != 0)
                    {
                        $round_data = array(
                            'item_id' => $post['round'],
                            'rate' => $post['round_diff'],
                            'total' => $post['round_diff'],
                            'sub_total' => $post['round_diff'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getRound->item_id, 'parent_id' => $post['id'], 'type' => 'invoice','expence_type'=>'rounding_invoices'));
                        $account_builder->update($round_data);
                    }
                    else
                    {
                        $result_up = $gnmodel->update_data_table('purchase_item', array('item_id' => $getRound->item_id, 'parent_id' => $post['id'], 'type' => 'invoice','expence_type'=>'rounding_invoices'), array('is_delete' => '1'));
             
                    }

                }
                else
                {
                    if(isset($post['round']) AND $post['round_diff'] != 0)
                    {
                        $round_itemdata[] = array(
                            'parent_id' => $post['id'],
                            'expence_type'=>'rounding_invoices',
                            'is_expence' => 1,
                            'item_id' => $post['round'],
                            'hsn' => '',
                            'type' => 'invoice',
                            'uom' => '',
                            'rate' => $post['round_diff'],
                            'qty' => 0,
                            'igst' => '',
                            'cgst' => '',
                            'sgst' => '',
                            'igst_amt' => '',
                            'cgst_amt' => '',
                            'sgst_amt' => '',
                            'taxability' => '',
                            //update discount column 17-01-2023
                            'total' => $post['round_diff'],
                            'item_disc' => 0,
                            'discount' => 0,
                            'divide_disc_item_per' => 0,
                            'divide_disc_item_amt' => 0,
                            'sub_total' => $post['round_diff'],
                            // end
                            'added_amt' =>'',
                            'remark' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $item_builder = $db->table('purchase_item');
                        $result2 = $item_builder->insertBatch($round_itemdata);
                    }
                }
                $new_item = array();
                $new_itempid = array();
                $new_account = array();
                $new_accountpid = array();
                //print_r($getItem);exit;
                for ($i = 0; $i < count($post['pid']); $i++) {

                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;

                        $total = $post['qty'][$i] * $post['price'][$i];
                        if ($post['discount'] > 0) {
                            $sub_total = $total - @$post['divide_disc_amt'][$i];
                        } else {
                            $sub_total = $total -  @$post['item_discount_hidden'][$i];
                        }
                        $item['pid'] = $post['pid'][$i];
                        $item['qty'] = $post['qty'][$i];
                        $item['hsn'] = @$post['hsn'][$i] ? $post['hsn'][$i]  : '';
                        $item['uom'] = $post['uom'][$i];
                        $item['rate'] = $post['price'][$i];
                        $item['igst'] = $post['igst'][$i];
                        $item['cgst'] = $post['cgst'][$i];
                        $item['sgst'] = $post['sgst'][$i];
                        $item['igst_amt'] = $post['igst_amt'][$i];
                        $item['cgst_amt'] = $post['cgst_amt'][$i];
                        $item['sgst_amt'] = $post['sgst_amt'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                        //update discount column 17-01-2023
                        $item['total'] = $total;
                        $item['item_disc'] = $post['item_disc'][$i];
                        $item['discount'] = $post['item_discount_hidden'][$i];
                        $item['divide_disc_item_per'] = @$post['item_per'][$i];
                        $item['divide_disc_item_amt'] = @$post['divide_disc_amt'][$i];
                        $item['sub_total'] = $sub_total;
                        // end
                        $item['added_amt'] = $post['item_added_amt_hidden'][$i];
                        $item['remark'] = $post['remark'][$i];
                        $new_item[] = $item;
                        $new_itempid[] = $post['pid'][$i];
                    } else {
                        $item['pid'] = $post['pid'][$i];
                        $item['qty'] = $post['qty'][$i];
                        $item['hsn'] = @$post['hsn'][$i] ? $post['hsn'][$i]  : '';
                        $item['uom'] = $post['uom'][$i];
                        $item['rate'] = $post['price'][$i];
                        $item['igst'] = $post['igst'][$i];
                        $item['cgst'] = $post['cgst'][$i];
                        $item['sgst'] = $post['sgst'][$i];
                        $item['igst_amt'] = $post['igst_amt'][$i];
                        $item['cgst_amt'] = $post['cgst_amt'][$i];
                        $item['sgst_amt'] = $post['sgst_amt'][$i];
                        $item['taxability'] = $post['taxability'][$i];
                        //update discount column 17-01-2023
                        $item['total'] = $post['price'][$i];
                        $item['item_disc'] = 0;
                        $item['discount'] = 0;
                        $item['divide_disc_item_per'] = 0;
                        $item['divide_disc_item_amt'] = 0;
                        $item['sub_total'] = $post['price'][$i];
                        //end
                        $item['added_amt'] = $post['item_added_amt_hidden'][$i];
                        $item['remark'] = $post['remark'][$i];
                        $new_account[] = $item;
                        $new_accountpid[] = $post['pid'][$i];
                    }
                }


                $getitem = explode(',', $getItem->item_id);
                $getAccount = explode(',', $getAccount->item_id);

                $delete_itemid = array_diff($getitem, $new_itempid);
                $delete_account = array_diff($getAccount, $new_accountpid);
                //print_r($delete_itemid);exit;

                if (!empty($delete_itemid)) {
                    foreach ($delete_itemid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $item_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $item_builder->update($del_data);
                    }
                }
                if (!empty($delete_account)) {
                    foreach ($delete_account as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $account_builder->where(array('item_id' => $del_id, 'parent_id' => $post['id'], 'type' => 'invoice'));
                        $account_builder->update($del_data);
                    }
                }
                for ($i = 0; $i < count($new_item); $i++) {
                    $item_result = $item_builder->select('*')->where(array("item_id" => $new_item[$i]['pid'], "parent_id" => $post['id'], "type" => 'invoice', 'is_delete' => 0, 'is_expence' => 0))->get();
                    $getItem = $item_result->getRow();

                    if (!empty($getItem)) {
                        //    $qty = $post['qty'][$i] - $getItem->qty;

                        $item_data = array(
                            // 'parent_id'=> $post['id'],
                            'is_expence' => 0,
                            // 'item_id'=> $new_item[$i]['pid'],
                            // 'type'=> 'challan',
                            'hsn' => @$post['hsn'][$i] ? $post['hsn'][$i]  : '',
                            'uom' => $new_item[$i]['uom'],
                            'rate' => $new_item[$i]['rate'],
                            'qty' => $new_item[$i]['qty'],
                            'igst' => $new_item[$i]['igst'],
                            'cgst' => $new_item[$i]['cgst'],
                            'sgst' => $new_item[$i]['sgst'],
                            'igst_amt' => $new_item[$i]['igst_amt'],
                            'cgst_amt' => $new_item[$i]['cgst_amt'],
                            'sgst_amt' => $new_item[$i]['sgst_amt'],
                            'taxability' => $new_item[$i]['taxability'],
                             //update discount column 17-01-2023
                             'total' =>$new_item[$i]['total'],
                             'item_disc' => $new_item[$i]['item_disc'],
                             'discount' => $new_item[$i]['discount'],
                             'divide_disc_item_per' => $new_item[$i]['divide_disc_item_per'],
                             'divide_disc_item_amt' => $new_item[$i]['divide_disc_item_amt'],
                             'sub_total' => $new_item[$i]['sub_total'],
                             // end
                             'added_amt' => $new_item[$i]['added_amt'],
                             'remark' => $new_item[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $item_builder->where(array('item_id' => $getItem->item_id, 'parent_id' => $post['id'], "type" => 'invoice', 'is_delete' => 0, 'is_expence' => 0));
                        $res = $item_builder->update($item_data);
                    } else {

                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'type' => 'invoice',
                            'hsn' => @$post['hsn'][$i] ? $post['hsn'][$i]  : '',
                            //'item_disc' => $new_item[$i]['item_disc'],
                            'uom' => $new_item[$i]['uom'],
                            'rate' => $new_item[$i]['rate'],
                            'qty' => $new_item[$i]['qty'],
                            'igst' => $new_item[$i]['igst'],
                            'cgst' => $new_item[$i]['cgst'],
                            'sgst' => $new_item[$i]['sgst'],
                            'igst_amt' => $new_item[$i]['igst_amt'],
                            'cgst_amt' => $new_item[$i]['cgst_amt'],
                            'sgst_amt' => $new_item[$i]['sgst_amt'],
                            'taxability' => $new_item[$i]['taxability'],
                              //update discount column 17-01-2023
                              'total' => $new_item[$i]['total'],
                              'item_disc' => $new_item[$i]['item_disc'],
                              'discount' => $new_item[$i]['discount'],
                              'divide_disc_item_per' => $new_item[$i]['divide_disc_item_per'],
                              'divide_disc_item_amt' => $new_item[$i]['divide_disc_item_amt'],
                              'sub_total' => $new_item[$i]['sub_total'],
                              // end
                              'added_amt' => $new_item[$i]['added_amt'],
                              'remark' => $new_item[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                    }
                }
                for ($i = 0; $i < count($new_account); $i++) {
                    $account_result = $account_builder->select('*')->where(array("item_id" => $new_account[$i]['pid'], "parent_id" => $post['id'], 'type' => 'invoice', 'is_delete' => 0, 'is_expence' => 1))->get();
                    $getAccount = $account_result->getRow();
                    if (!empty($getAccount)) {

                        $acc_data = array(
                            // 'parent_id'=> $post['id'],
                            'is_expence' => 1,
                            // 'item_id'=> $new_account[$i]['pid'],
                            // 'type'=> 'challan',
                            'item_disc' => '',
                            'uom' => '',
                            'rate' => $new_account[$i]['rate'],
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt' => $new_account[$i]['igst_amt'],
                            'cgst_amt' => $new_account[$i]['cgst_amt'],
                            'sgst_amt' => $new_account[$i]['sgst_amt'],
                            'taxability' => $new_account[$i]['taxability'],
                             //update discount column 17-01-2023
                             'total' => $new_account[$i]['total'],
                             'item_disc' => $new_account[$i]['item_disc'],
                             'discount' => $new_account[$i]['discount'],
                             'divide_disc_item_per' => $new_account[$i]['divide_disc_item_per'],
                             'divide_disc_item_amt' => $new_account[$i]['divide_disc_item_amt'],
                             'sub_total' => $new_account[$i]['sub_total'],
                             // end
                             'added_amt' => $new_account[$i]['added_amt'],
                             'remark' => $new_account[$i]['remark'],
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getAccount->item_id, 'parent_id' => $post['id'], 'type' => 'invoice', 'is_delete' => 0, 'is_expence' => 1));
                        $res = $account_builder->update($acc_data);
                    } else {
                        $acc_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            'type' => 'invoice',
                            'uom' => '',
                            'rate' => $new_account[$i]['rate'],
                            'item_disc' => '',
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt' => $new_account[$i]['igst_amt'],
                            'cgst_amt' => $new_account[$i]['cgst_amt'],
                            'sgst_amt' => $new_account[$i]['sgst_amt'],
                            'taxability' => $new_account[$i]['taxability'],
                             //update discount column 17-01-2023
                             'total' => $new_account[$i]['total'],
                             'item_disc' => $new_account[$i]['item_disc'],
                             'discount' => $new_account[$i]['discount'],
                             'divide_disc_item_per' => $new_account[$i]['divide_disc_item_per'],
                             'divide_disc_item_amt' => $new_account[$i]['divide_disc_item_amt'],
                             'sub_total' => $new_account[$i]['sub_total'],
                             // end
                             'added_amt' => $new_account[$i]['added_amt'],
                             'remark' => $new_account[$i]['remark'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $account_builder->insert($acc_data);
                    }
                }
                $builder = $db->table('purchase_invoice');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                    //return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            //echo '<pre>';Print_r($post);exit;

            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');

            if (empty($msg)) {
                $result = $builder->Insert($pdata);
                // print_r($result);
                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {

                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;
                        if ($post['discount'] > 0) {
                            $sub_total = $post['subtotal'][$i] - $post['item_discount_hidden'][$i];
                        } else {
                            $sub_total = $post['subtotal'][$i];
                        }
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'is_expence' => 0,
                            'item_id' => $post['pid'][$i],
                            'hsn' => @$post['hsn'][$i] ? $post['hsn'][$i]  : '',
                            'type' => 'invoice',
                            'uom' => $post['uom'][$i],
                            'rate' => $post['price'][$i],
                            'qty' => $post['qty'][$i],
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            //update discount column 17-01-2023
                            'total' => $total,
                            'item_disc' => $post['item_disc'][$i],
                            'discount' => $post['item_discount_hidden'][$i],
                            'divide_disc_item_per' => $post['item_per'][$i],
                            'divide_disc_item_amt' => $post['divide_disc_amt'][$i],
                            'sub_total' => $sub_total,
                            // end
                            'added_amt' => $post['item_added_amt_hidden'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    } else {
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'is_expence' => 1,
                            'item_id' => $post['pid'][$i],
                            'hsn' => @$post['hsn'][$i] ? $post['hsn'][$i]  : '',
                            'type' => 'invoice',
                            'uom' => '',
                            'rate' => $post['price'][$i],
                            'qty' => 0,
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            //update discount column 17-01-2023
                            'total' => $post['price'][$i],
                            'item_disc' => 0,
                            'discount' => 0,
                            'divide_disc_item_per' => 0,
                            'divide_disc_item_amt' => 0,
                            'sub_total' => $post['price'][$i],
                            // end
                            'added_amt' => $post['item_added_amt_hidden'][$i],
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    }
                }
                $item_builder = $db->table('purchase_item');
                $result1 = $item_builder->insertBatch($itemdata);
                if(isset($post['round']) AND $post['round_diff'] != 0)
                {
                    $round_itemdata[] = array(
                        'parent_id' => $id,
                        'expence_type'=>'rounding_invoices',
                        'is_expence' => 1,
                        'item_id' => $post['round'],
                        'hsn' => '',
                        'type' => 'invoice',
                        'uom' => '',
                        'rate' => $post['round_diff'],
                        'qty' => 0,
                        'igst' => '',
                        'cgst' => '',
                        'sgst' => '',
                        'igst_amt' => '',
                        'cgst_amt' => '',
                        'sgst_amt' => '',
                        'taxability' => '',
                        //update discount column 17-01-2023
                        'total' => $post['round_diff'],
                        'item_disc' => 0,
                        'discount' => 0,
                        'divide_disc_item_per' => 0,
                        'divide_disc_item_amt' => 0,
                        'sub_total' => $post['round_diff'],
                        // end
                        'added_amt' =>'',
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('purchase_item');
                    $result2 = $item_builder->insertBatch($round_itemdata);
                }
                
                if(isset($post['discount_acc']) AND !empty($post['discount_amount_new']))
                {
                    $discount_itemdata[] = array(
                        'parent_id' => $id,
                        'expence_type'=>'discount',
                        'is_expence' => 1,
                        'item_id' => $post['discount_acc'],
                        'hsn' => '',
                        'type' => 'invoice',
                        'uom' => '',
                        'rate' => $post['discount_amount_new'],
                        'qty' => 0,
                        'igst' => '',
                        'cgst' => '',
                        'sgst' => '',
                        'igst_amt' => '',
                        'cgst_amt' => '',
                        'sgst_amt' => '',
                        'taxability' => '',
                        //update discount column 17-01-2023
                        'total' => $post['discount_amount_new'],
                        'item_disc' => 0,
                        'discount' => 0,
                        'divide_disc_item_per' => 0,
                        'divide_disc_item_amt' => 0,
                        'sub_total' => $post['discount_amount_new'],
                        // end
                        'added_amt' => '',
                        'remark' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                    $item_builder = $db->table('purchase_item');
                    $result3 = $item_builder->insertBatch($discount_itemdata);
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

    public function get_purchaseinvoice_data($get)
    {
        $dt_search = array(
            "pi.id",
            "pi.invoice_no",
            "pi.invoice_date",
            "pi.supply_inv",
            "(select name from account ac where pi.account = ac.id)",
            "pi.custom_inv_no",
            "pi.other",
            "pi.net_amount"

        );
        $dt_col = array(
            "pi.id",
            "pi.invoice_no",
            "pi.supply_inv",
            "pi.invoice_date",
            "pi.account",
            "pi.other",
            "pi.net_amount",
            "(select name from account ac where pi.account = ac.id) as account_name",
            "(select name from account br where pi.broker = br.id) as broker_name",
            "pi.is_cancle",
            "pi.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "purchase_invoice pi";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $gmodel = new GeneralModel();
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btnedit = '<a   href="' . url('purchase/add_purchaseinvoice/') . $row['id'] . '"   data-title="Edit PurchaseInvoice: ' . $row['account'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('purchase/purchase_invoice_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Debit Name: ' . $row['account'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btn_cancle = '<a data-toggle="modal" target="_blank"   title="Cancle Invoice: ' . $row['id'] . '"  onclick="editable_os(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-times-circle"></i></a> ';
            $btnpdf = '<a href="' . url('Purchase/pdf_invoice/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';


            $getMax = $gmodel->get_data_table('purchase_invoice', array('is_delete' => 0), 'MAX(invoice_no) as max_no');


            if ($row['is_cancle'] == 1 || $row['is_delete'] == 1) {
                $btn =  $btnview . $btnpdf;
            } else {
                $btn =  $btnedit . $btnview . $btnpdf;
            }

            if ($getMax['max_no'] == $row['invoice_no']) {
                $btn .= $btndelete;
            } else {
                if ($row['is_cancle'] == 0) {
                    $btn .= $btn_cancle;
                }
            }

            if (!empty($row['gst'])) {
                $gst = '<br>(' . $row['gst'] . ')';
            } else {
                $gst = '';
            }

            $DataRow[] = $row['invoice_no'];
            $DataRow[] = $row['supply_inv'];
            $DataRow[] = user_date($row['invoice_date']);
            $DataRow[] = $row['account_name'] . $gst;
            $DataRow[] = number_format($row['net_amount'], 2);
            $DataRow[] = $row['other'];
            $DataRow[] = ($row['is_cancle'] == 1)  ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    // update trupti 24-11-2022
    public function get_purchase_invoice($id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice');
        $query = $builder->select('*')->where(array('id' => $id))->get();
        $invoice = $query->getResultArray();
        $getdata['purchaseinvoice'] = $invoice[0];
        // echo '<pre>';print_r($getdata);exit;
        $gmodel = new GeneralModel();
        foreach ($invoice as $row) {

            $getchallan = $gmodel->get_data_table('purchase_challan', array('id' => $row['challan_no']), '*');
            $getchallan_ac = $gmodel->get_data_table('account', array('id' => @$getchallan['account']), 'name');

            $challan_no = @$getchallan['challan_no'] . '(' . @$getchallan_ac['name'] . ')/ ' . user_date(@$getchallan['challan_date']);


            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name,brokrage');
            $getac = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'code');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $getround = $gmodel->get_data_table('account', array('id' => $row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');


            $getdata['purchaseinvoice']['broker_name'] = @$getbroker['name'];
            $getdata['purchaseinvoice']['account_name'] = @$getac['name'];
            $getdata['purchaseinvoice']['fix_brokrage'] = @$getbroker['brokrage'];
            $getdata['purchaseinvoice']['voucher_name'] = @$getvoucher['name'];
            $getdata['purchaseinvoice']['round_name'] = @$getround['name'];

            $getdata['purchaseinvoice']['transport_name'] = @$gettransport['code'];
            $getdata['purchaseinvoice']['city_name'] = @$getcity['name'];
            $getdata['purchaseinvoice']['vehicle_name'] = @$getvehicle['name'];
            $getdata['purchaseinvoice']['challan_name'] = @$challan_no;
            $getdata['purchaseinvoice']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['purchaseinvoice']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['purchaseinvoice']['cgst_acc_name'] = @$cgst_acc['name'];
        }

        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,st.uom as uom');
        //$item_builder->join('item i','i.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'invoice','st.expence_type'=>'', 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdata1 = $query->getResultArray();
        //echo '<pre>';print_r($getdata1);exit;
        foreach ($getdata1 as $row) {
            if ($row['is_expence'] == 0) {
                $getitem = $gmodel->get_data_table('item', array('id' => $row['item_id']), 'id,type,name,sku,purchase_cost,hsn,code,uom as item_uom');
                $uom =  explode(',', $getitem['item_uom']);
                foreach ($uom as $row1) {
                    $getuom = $gmodel->get_data_table('uom', array('id' => $row1), 'code');
                    $uom_arr[] = $getuom['code'];
                }

                $coma_uom = implode(',', $uom_arr);
                $row['item_uom'] = $coma_uom;
                $row['id'] = $row['item_id'];
                $row['type'] = $getitem['type'];
                // $row['mode'] =$getitem['mode'];  
                $row['name'] = $getitem['name'];
                $row['sku'] = $getitem['sku'];
                $row['purchase_cost'] = $getitem['purchase_cost'];
                $row['hsn'] = $getitem['hsn'];
            } else {
                $getaccount = $gmodel->get_data_table('account', array('id' => $row['item_id']), 'id,name,code');
                $row['id'] = $row['item_id'];
                $row['name'] = $getaccount['name'];
                $row['code'] = $getaccount['code'];
            }
            $getdata['item'][] = $row;
            //$uom_arr = array();
        }
        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'invoice','st.expence_type'=>'rounding_invoices','is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getrounding = $query->getRowArray();

        $getdata['purchaseinvoice']['round_acc'] = @$getrounding['item_id'];
        $getdata['purchaseinvoice']['round_acc_name'] = @$getrounding['acc_name'];

        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'invoice','st.expence_type'=>'discount','is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdiscount = $query->getRowArray();

        $getdata['purchaseinvoice']['discount_acc'] = @$getdiscount['item_id'];
        $getdata['purchaseinvoice']['discount_acc_name'] = @$getdiscount['acc_name'];

        return $getdata;
    }

    // update trupti 24-11-2022
    public function search_purchase_challan_data($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_challan pc');
        $builder->select('pc.*,ac.name as account_name,ac.default_due_days');
        $builder->join('account ac', 'ac.id = pc.account');
        if (!empty($post['searchTerm'])) {
            $builder->like('pc.challan_no', @$post['searchTerm']);
        }
        $builder->where("pc.is_delete", 0);
        $builder->where("pc.is_cancle", 0);
        $query = $builder->get();
        $challan = $query->getResultArray();


        $gmodel = new GeneralModel();
        foreach ($challan as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name,brokrage');

            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'code');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            $row['broker_name'] = @$getbroker['name'];
            $row['fix_brokrage'] = @$getbroker['brokrage'];

            $row['transport_name'] = @$gettransport['code'];
            $row['city_name'] = @$getcity['name'];
            $row['vehicle_name'] = @$getvehicle['name'];

            $row['lr_date'] = user_date($row['lr_date']);
            $row['supply_date'] = user_date($row['supply_date']);
            $row['igst_acc_name'] = @$igst_acc['name'];
            $row['sgst_acc_name'] = @$sgst_acc['name'];
            $row['cgst_acc_name'] = @$cgst_acc['name'];


            $item_builder = $db->table('purchase_item');
            $item_builder->select('*');
            $item_builder->where(array('parent_id' => $row['id'], 'type' => 'challan', 'is_delete' => 0));
            $query = $item_builder->get();
            $getdata1 = $query->getResultArray();
            //echo '<pre>';print_r($row);
            $item = array();
            $total_challan_qty = 0;
            foreach ($getdata1 as $row2) {

                if ($row2['is_expence'] == 0) {
                    $total_challan_qty += $row2['qty'];
                    $getitem = $gmodel->get_data_table('item', array('id' => $row2['item_id']), 'id,type,name,sku,purchase_cost,hsn,code,uom as item_uom');
                    $uom =  explode(',', $getitem['item_uom']);
                    foreach ($uom as $row3) {
                        $getuom = $gmodel->get_data_table('uom', array('id' => $row3), 'code');
                        $uom_arr[] = $getuom['code'];
                    }

                    $coma_uom = implode(',', $uom_arr);
                    $row2['id'] = $row2['item_id'];
                    $row2['item_uom'] = $coma_uom;
                    $row2['type'] = $getitem['type'];
                    // $row['mode'] =$getitem['mode'];  
                    $row2['name'] = $getitem['name'];
                    $row2['sku'] = $getitem['sku'];
                    $row2['purchase_cost'] = $getitem['purchase_cost'];
                    // $row2['hsn'] = $row2['hsn'];
                } else {
                    $getaccount = $gmodel->get_data_table('account', array('id' => $row2['item_id']), 'id,name,code');
                    $row2['id'] = $row2['item_id'];
                    $row2['name'] = $getaccount['name'];
                    $row2['code'] = $getaccount['code'];
                }
                $item[] = $row2;
                //$uom_arr = array();
            }
            $builder = $db->table('purchase_invoice si');
            $builder->select('si.*,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where('si.is_delete', '0');
            $builder->where('si.challan_no', $row['id']);
            $query = $builder->get();
            $invoices = $query->getResultArray();
            $total_qty = 0;
            foreach ($invoices as $row1) {
                $item_builder = $db->table('purchase_item st');
                $item_builder->select('SUM(qty) as qty');
                $item_builder->where(array('st.parent_id' => $row1['id'], 'st.type' => 'invoice', 'st.is_delete' => 0));
                $query = $item_builder->get();
                $item2 = $query->getRowArray();
                $total_qty += $item2['qty'];
            }
            $text = $row['challan_no'] . ' (' . $row['account_name'] . ') / ' . user_date($row['challan_date']);
            if ($total_qty < $total_challan_qty) {
                $result[] = array("text" => $text, "id" => $row['id'], 'purchasechallan' => $row, 'item' => $item);
            }
            unset($item);
        }
        //exit;
        // echo '<pre>';print_r($result);exit;
        return $result;
    }

    // update trupti 24-11-2022
    public function get_purchase_challan($id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_challan pc');
        $builder->select('pc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pc.account');
        $builder->where(array('pc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();

        $getdata['purchasechallan'] = $challan[0];

        $gmodel = new GeneralModel();
        foreach ($challan as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            //$getclass = $gmodel->get_data_table('class',array('id'=>$row['class']),'name');
            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle']), 'name');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $getround = $gmodel->get_data_table('account', array('id' => $row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');


            $getdata['purchasechallan']['broker_name'] = @$getbroker['name'];
            //$getdata['purchasechallan']['class_name']=@$getclass['name'];
            $getdata['purchasechallan']['transport_name'] = @$gettransport['name'];
            $getdata['purchasechallan']['vehicle_name'] = @$getvehicle['name'];
            $getdata['purchasechallan']['city_name'] = @$getcity['name'];
            $getdata['purchasechallan']['voucher_name'] = @$getvoucher['name'];
            $getdata['purchasechallan']['round_name'] = @$getround['name'];
            $getdata['purchasechallan']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['purchasechallan']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['purchasechallan']['cgst_acc_name'] = @$cgst_acc['name'];
        }

        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,st.uom as uom');
        //$item_builder->join('item i','i.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'challan', 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdata1 = $query->getResultArray();
        //echo '<pre>';print_r($getdata1);exit;
        foreach ($getdata1 as $row) {
            if ($row['is_expence'] == 0) {
                $getitem = $gmodel->get_data_table('item', array('id' => $row['item_id']), 'id,type,name,sku,purchase_cost,hsn,code,uom as item_uom');
                $uom =  explode(',', $getitem['item_uom']);
                foreach ($uom as $row1) {
                    $getuom = $gmodel->get_data_table('uom', array('id' => $row1), 'code');
                    $uom_arr[] = $getuom['code'];
                }

                $coma_uom = implode(',', $uom_arr);
                $row['item_uom'] = $coma_uom;
                $row['id'] = $row['item_id'];
                $row['type'] = $getitem['type'];
                // $row['mode'] =$getitem['mode'];  
                $row['name'] = $getitem['name'];
                $row['sku'] = $getitem['sku'];
                $row['purchase_cost'] = $getitem['purchase_cost'];
                $row['hsn'] = $getitem['hsn'];
            } else {
                $getaccount = $gmodel->get_data_table('account', array('id' => $row['item_id']), 'id,name,code');
                $row['id'] = $row['item_id'];
                $row['name'] = $getaccount['name'];
                $row['code'] = $getaccount['code'];
            }
            $getdata['item'][] = $row;
            //$uom_arr = array();
        }
        //echo '<pre>';print_r($getdata);exit;  
        return $getdata;
    }

    public function get_master_data($method, $id)
    {

        $gnmodel = new GeneralModel();
        if ($method == 'debit') {
            $result['debit'] = $gnmodel->get_data_table('debit_note', array('id' => $id));
        }
        if ($method == 'purchasechallan') {
            $result['purchasechallan'] = $gnmodel->get_data_table('purchase_challan', array('id' => $id));
        }
        if ($method == 'purchaseinvoice') {
            $result['purchaseinvoice'] = $gnmodel->get_data_table('purchase_invoice', array('id' => $id));
        }
        if ($method == 'purchasereturn') {
            $result['p_return'] = $gnmodel->get_data_table('purchase_return', array('id' => $id));
        }
        return $result;
    }
    // update trupti 24-11-2022
    public function get_purchaseinvoice_databyid($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice si');
        $builder->select('si.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.account' => $post['id']));
        if (@$post['searchTerm'] != '') {
            $builder->where(array('si.id' => @$post['searchTerm']));
        }
        $builder->orderBy('si.id', 'desc');
        $builder->limit(5);
        $query = $builder->get();
        $sale_invoice = $query->getResultArray();

        $gmodel = new GeneralModel();
        $result = array();
        foreach ($sale_invoice as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name,brokrage');

            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle']), 'name');

            $row['broker_name'] = @$getbroker['name'];
            $row['fix_brokrage'] = @$getbroker['brokrage'];
            $row['transport_name'] = @$gettransport['name'];
            $row['city_name'] = @$getcity['name'];
            $row['vehicle_name'] = @$getvehicle['name'];

            $row['lr_date'] = user_date($row['lr_date']);
            $row['supply_date'] = user_date($row['supply_date']);


            $item_builder = $db->table('purchase_item st');
            $item_builder->select('st.*,i.id,i.name,i.code,i.uom as item_uom ,st.uom as uom');
            $item_builder->join('item i', 'i.id = st.item_id');
            $item_builder->where(array('st.parent_id' => $row['id'], 'st.type' => 'invoice', 'st.is_delete' => 0));
            $query = $item_builder->get();
            $item1 = $query->getResultArray();
            $total_challan_qty = 0;

            foreach ($item1 as $row2) {
                $total_challan_qty += $row2['qty'];
                $uom =  explode(',', $row2['item_uom']);
                foreach ($uom as $row1) {
                    $getuom = $gmodel->get_data_table('uom', array('id' => $row1), 'code');
                    $uom_arr[] = $getuom['code'];
                }

                $coma_uom = implode(',', $uom_arr);
                $row2['item_uom'] = $coma_uom;
                $item[] = $row2;
                $uom_arr = array();
            }

            $builder = $db->table('purchase_invoice si');
            $builder->select('si.*,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where('si.is_delete', '0');
            $builder->where('si.challan_no', $row['id']);
            $query = $builder->get();
            $invoices = $query->getResultArray();
            $total_qty = 0;

            foreach ($invoices as $row1) {
                $item_builder = $db->table('purchase_item st');
                $item_builder->select('SUM(qty) as qty');
                $item_builder->where(array('st.parent_id' => $row1['id'], 'st.type' => 'return', 'st.is_delete' => 0));
                $query = $item_builder->get();
                $item2 = $query->getRowArray();
                $total_qty += $item2['qty'];
            }
            $text = $row['invoice_no'] . ' (' . $row['account_name'] . ') / ' . user_date($row['invoice_date']);

            if ($total_qty < $total_challan_qty) {
                $result[] = array("text" => $text, "id" => $row['id'], 'return' => $row, 'item' => $item);
            }
            unset($item);
        }


        // foreach ($sale_invoice as $row) {

        //     $text = '(' . $row['invoice_no'] . ') -' . $row['invoice_date'] . '-' . $row['account_name'] . ' - ₹' . $row['net_amount'];
        //     $data[] = array(
        //         'id' => $row['id'],
        //         'text' => $text,
        //         'table' => 'sales_invoice',
        //     );
        // }

        return $result;
    }
    // update trupti 24-11-2022
    public function get_purchase_return($id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_return');
        $query = $builder->select('*')->where(array('id' => $id))->get();
        $return = $query->getResultArray();
        $getdata['p_return'] = $return[0];

        $gmodel = new GeneralModel();
        foreach ($return as $row) {

            // $getbroker = $gmodel->get_data_table('account',array('id'=>$row['broker']),'name,brokrage');
            $getac = $gmodel->get_data_table('account', array('id' => $row['account']), 'name');
            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');

            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle']), 'name');
            $getinvoice = $gmodel->get_data_table('purchase_invoice', array('id' => $row['invoice']), 'id,invoice_date,net_amount');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $getround = $gmodel->get_data_table('account', array('id' => $row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            $getdata['p_return']['broker_name'] = @$getbroker['name'];
            $getdata['p_return']['account_name'] = @$getac['name'];
            // $getdata['p_return']['fix_brokrage']=@$getbroker['brokrage'];
            // $getdata['p_return']['broker_ledger_name']=@$getbroker_led['name'];

            $getdata['p_return']['transport_name'] = @$gettransport['name'];
            $getdata['p_return']['city_name'] = @$getcity['name'];
            $getdata['p_return']['vehicle_name'] = @$getvehicle['name'];
            $getdata['p_return']['voucher_name'] = @$getvoucher['name'];
            $getdata['p_return']['round_name'] = @$getround['name'];
            $getdata['p_return']['broker_name'] = @$getbroker['name'];
            $getdata['p_return']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['p_return']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['p_return']['cgst_acc_name'] = @$cgst_acc['name'];

            if (!empty($getinvoice)) {
                $getdata['p_return']['invoice_name'] = '(' . @$getinvoice['id'] . ') -' . @$getac['name'] . ' / ' . user_date(@$getinvoice['invoice_date']) . '/ ₹' . @$getinvoice['net_amount'];
            } else {
                $getdata['p_return']['invoice_name'] = '';
            }
        }
        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,st.uom as uom');
        //$item_builder->join('item i','i.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'return','expence_type'=>'', 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdata1 = $query->getResultArray();
        //echo '<pre>';print_r($getdata1);exit;
        foreach ($getdata1 as $row) {
            if ($row['is_expence'] == 0) {
                $getitem = $gmodel->get_data_table('item', array('id' => $row['item_id']), 'id,type,name,sku,purchase_cost,hsn,code,uom as item_uom');
                $uom =  explode(',', $getitem['item_uom']);
                foreach ($uom as $row1) {
                    $getuom = $gmodel->get_data_table('uom', array('id' => $row1), 'code');
                    $uom_arr[] = $getuom['code'];
                }

                $coma_uom = implode(',', $uom_arr);
                $row['item_uom'] = $coma_uom;
                $row['id'] = $row['item_id'];
                $row['type'] = $getitem['type'];
                // $row['mode'] =$getitem['mode'];  
                $row['name'] = $getitem['name'];
                $row['sku'] = $getitem['sku'];
                $row['purchase_cost'] = $getitem['purchase_cost'];
                $row['hsn'] = $getitem['hsn'];
            } else {
                $getaccount = $gmodel->get_data_table('account', array('id' => $row['item_id']), 'id,name,code');
                $row['id'] = $row['item_id'];
                $row['name'] = $getaccount['name'];
                $row['code'] = $getaccount['code'];
            }
            $getdata['item'][] = $row;
            //$uom_arr = array();
        }
        // echo '<pre>';print_r($getdata);exit;
        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'return','st.expence_type'=>'rounding_invoices','is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getrounding = $query->getRowArray();

        $getdata['p_return']['round_acc'] = @$getrounding['item_id'];
        $getdata['p_return']['round_acc_name'] = @$getrounding['acc_name'];

        $item_builder = $db->table('purchase_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'return','st.expence_type'=>'discount','is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdiscount = $query->getRowArray();

        $getdata['p_return']['discount_acc'] = @$getdiscount['item_id'];
        $getdata['p_return']['discount_acc_name'] = @$getdiscount['acc_name'];
        return $getdata;
    }



    public function get_purchasegeneral_databyid($post)
    {
        // print_r($post);exit;
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_general pg');
        $builder->select('pg.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = pg.party_account');
        $builder->where(array('pg.party_account' => $post['id']));
        $builder->where(array('pg.v_type' => 'general'));
        if (@$post['searchTerm'] != '') {
            $builder->where(array('pg.id' => @$post['searchTerm']));
        }
        $builder->orderBy('pg.id', 'desc');
        $builder->limit(5);
        $query = $builder->get();
        $purchase_general = $query->getResultArray();

        $gmodel = new GeneralModel();
        $data = array();
        foreach ($purchase_general as $row) {
            // $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'sales_invoice','is_delete' => '0' );
            $total_return = $gmodel->get_data_table('purchase_general', array('return_purchase' => $row['id'], 'v_type' => 'return'), 'SUM(net_amount) as total');


            // echo $db->getLastQuery();
            $dt = date_create($row['doc_date']);
            $date = date_format($dt, 'd-m-Y');
            $text = '(' . $row['id'] . ') -' . $date . '-' . $row['account_name'] . ' - ₹' . ($row['net_amount'] - $total_return['total']);
            $data[] = array(
                'id' => $row['id'],
                'text' => $text,
                'table' => 'sales_ACinvoice',
            );
        }

        return $data;
    }

    public function UpdateData($post)
    {
        $result = array();

        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'debit') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('debit_note', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'purchasechallan') {
                $gnmodel = new GeneralModel();

                $purchase_invoice = $gnmodel->get_array_table('purchase_invoice', array('challan_no' => $post['pk']), 'is_cancle,is_delete');

                foreach ($purchase_invoice as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_delete = 0;
                    }
                }

                if (isset($is_delete) && $is_delete == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Delete Purchase Invoice..!');
                } else {
                    $result = $gnmodel->update_data_table('purchase_challan', array('id' => $post['pk']), array('is_delete' => '1'));
                }
            }
            if ($post['method'] == 'purchaseinvoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('purchase_invoice', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'purchasereturn') {
                $gnmodel = new GeneralModel();
                //print_r($post);exit;
                $result = $gnmodel->update_data_table('purchase_return', array('id' => $post['pk']), array('is_delete' => '1'));
            }
            if ($post['method'] == 'general_purchase') {
                $gnmodel = new GeneralModel();
                //print_r($post);exit;
                $result = $gnmodel->update_data_table('purchase_general', array('id' => $post['pk']), array('is_delete' => '1'));
            }
        }
        if ($post['type'] == 'Cancle') {

            if ($post['method'] == 'purchasechallan') {

                $gnmodel = new GeneralModel();
                $purchase_invoice = $gnmodel->get_array_table('purchase_invoice', array('challan_no' => $post['pk']), 'is_cancle,is_delete');

                foreach ($purchase_invoice as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_cancle = 0;
                    }
                }

                if (isset($is_cancle) && $is_cancle == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Cancle Invoice');
                } else {
                    $result = $gnmodel->update_data_table('purchase_challan', array('id' => $post['pk']), array('is_cancle' => 1));
                }
            }
            if ($post['method'] == 'purchaseinvoice') {
                $gnmodel = new GeneralModel();
                $purchase_return = $gnmodel->get_array_table('purchase_return', array('invoice' => $post['pk']), 'is_cancle,is_delete');

                foreach ($purchase_return as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_cancle = 0;
                    }
                }
                if (isset($is_cancle) && $is_cancle == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Cancle Invoice');
                } else {
                    $result = $gnmodel->update_data_table('purchase_invoice', array('id' => $post['pk']), array('is_cancle' => 1));
                }
            }
            if ($post['method'] == 'purchasereturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('purchase_return', array('id' => $post['pk']), array('is_cancle' => 1));
            }

            if ($post['method'] == 'general_purchase') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('purchase_general', array('id' => $post['pk']), array('is_cancle' => 1));
            }
        }
        return $result;
    }
}
