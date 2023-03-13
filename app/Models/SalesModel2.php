<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{

    public function get_ac_invoice($get)
    {

        $dt_search = array(
            "si.id",
            "(select name from account ac where si.party_account = ac.id)",
            "si.invoice_date",
            "si.v_type",
            "si.supp_inv",
            "si.status",
            "si.net_amount",
        );

        $dt_col = array(
            "si.invoice_no",
            "si.id",
            "si.v_type",
            "si.invoice_date",
            "si.party_account",
            "(select name from account ac where si.party_account = ac.id) as party_account_name",
            "si.supp_inv",
            "si.net_amount",
            "si.status",
            "si.is_cancle",
            "si.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "sales_ACinvoice si";
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

            $btnedit = '<a href="' . url('sales/add_ACinvoice/') . $row['v_type'] . '/' . $row['id'] . '"  class="btn btn-link pd-6"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('sales/general_detail/') . $row['id'] . '"    class="btn btn-link pd-6"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Ac Invoice Id: ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6"><i class="far fa-trash-alt"></i></a> ';
            $status = '<a target="_blank"   title="Item Invoice Id: ' . $row['id'] . '" onclick="editable_os(this)"  data-val="' . $row['status'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['status']] . '</a>';

            $getMax = $gmodel->get_data_table('sales_ACinvoice', array('is_delete' => 0, 'v_type' => $filter), 'MAX(invoice_no) as max_no');
            $btnpdf = '<a href="' . url('sales/pdf_general/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';


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
            $DataRow[] = user_date($row['invoice_date']);
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
    public function get_ACinvoice_byid($id)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_ACinvoice si');
        $builder->select('si.*,ac.name as party_name');
        $builder->join('account ac', 'ac.id = si.party_account');
        $builder->where(array('si.id' => $id));
        $query = $builder->get();
        $invoice = $query->getResultArray();

        $getdata['invoice'] = $invoice[0];
        $gmodel = new GeneralModel();
        foreach ($invoice as $row) {
            $total_return = $gmodel->get_data_table('sales_ACinvoice', array('return_sale' => $row['return_sale'], 'v_type' => 'return'), 'SUM(net_amount) as total');
            $getreturn = $gmodel->get_data_table('sales_ACinvoice', array('id' => $row['return_sale'], 'v_type' => 'general'), 'id,net_amount,invoice_date');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $getround = $gmodel->get_data_table('account', array('id' => $row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            $getdata['invoice']['return_sale_name'] = '(' . @$getreturn['id'] . ') - ' . @$getreturn['invoice_date'] . '-' . $row['party_name'] . '- ₹' . (@$getreturn['net_amount'] + @$row['net_amount'] - @$total_return['total']);
            $getdata['invoice']['voucher_name'] = @$getvoucher['name'];
            $getdata['invoice']['round_name'] = @$getround['name'];
            $getdata['invoice']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['invoice']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['invoice']['cgst_acc_name'] = @$cgst_acc['name'];
        }

        $item_builder = $db->table('sales_ACparticu sp');
        $item_builder->select('sp.*,ac.name as account_name,ac.code as code,,ac.hsn');
        $item_builder->join('account ac', 'ac.id = sp.account');
        $item_builder->where(array('sp.parent_id' => $id, 'sp.is_delete' => 0));
        $query = $item_builder->get();
        $getdata['acc'] = $query->getResultArray();
        // echo '<pre>';print_r($getdata['acc']);exit;
        return $getdata;
    }
    // update trupti 24-11-2022
    public function insert_edit_challan($post)
    {
        //print_r($post);exit;
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
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
            if ($post['amty'] == '')
                $post['amty'] = 0;
            else
                $post['amty'] = $total *  $post['amty'] / 100;
        } else {
            if ($post['amty'] == '')
                $post['amty'] = 0;
        }

        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '')
                $post['cess'] = 0;
            else
                $post['cess'] = $total *  $post['cess'] / 100;
        } else {
            if ($post['cess'] == '')
                $post['cess'] = 0;
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total *  $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }

        $dt = date_create($post['challan_date']);
        $date = date_format($dt, 'Y-m-d');

        if (isset($post['lr_date'])) {
            $lr_dt = date_create($post['lr_date']);
            $lr_date = date_format($lr_dt, 'Y-m-d');
        } else {
            $lr_date = '';
        }

        $netamount = $total + $post['cess'] + $post['amty'] +   $tds_amt + $post['tot_igst'];
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
            'challan_date' => $date,
            'challan_no' => $post['challan_no'],
            'custom_challan_no' => @$post['custom_challan_no'] ? $post['custom_challan_no'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'delivery_code' => @$post['delivery_code'],
            'other' => @$post['other'],
            'lr_no' => $post['lrno'],
            'lr_date' => $lr_date,
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'transport' => @$post['transport'],
            'city' => @$post['city'],
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'total_amount' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amty' => $amty,
            'amty_type' => $post['amty_type'],
            'cess_type' => $post['cess_type'],
            'cess' => $cess,
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'net_amount' => round($netamount),
            'transport_mode' => $post['trasport_mode'],
            'vehicle_modeno' => @$post['vhicle_modeno'],
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
        if ($post['gst'] != '') {
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

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'challan', "is_delete" => 0, "is_expence" => 0))->get();
                $getItem = $item_result->getRow();

                $account_builder = $db->table('sales_item');
                $account_result = $account_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'challan', "is_delete" => 0, "is_expence" => 1))->get();
                $getAccount = $account_result->getRow();

                $new_item = array();
                $new_itempid = array();
                $new_account = array();
                $new_accountpid = array();
                //update item column 17-01-2023
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
                        $item['hsn'] = $post['hsn'][$i];
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
                            'type' => 'challan',
                            'uom' => '',
                            'hsn' => '',
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
                            'type' => 'challan',
                            'uom' => '',
                            'hsn' => '',
                            'rate' => $new_account[$i]['rate'],
                            'qty' => '',
                            'igst' => $new_account[$i]['igst'],
                            'cgst' => $new_account[$i]['cgst'],
                            'sgst' => $new_account[$i]['sgst'],
                            'igst_amt'   => $new_account[$i]['igst_amt'],
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
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $account_builder->insert($acc_data);
                    }
                }
                $builder = $db->table('sales_challan');
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
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

                $id = $db->insertID();

                for ($i = 0; $i < count($pid); $i++) {
                    $gmodel = new GeneralModel();

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
                $item_builder = $db->table('sales_item');
                $result1 = $item_builder->insertBatch($itemdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }
    // update trupti 24-11-2022
    public function insert_edit_ACinvoice($post)
    {
        //print_r($post);exit;
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_ACinvoice');
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
                        $total += $post['price'][$i] - $devide_disc;
                    }
                }
            }
        } else {
            if ($post['discount'] == '')
                $post['discount'] == 0;
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
            if ($post['amty'] == '')
                $post['amty'] = 0;
            else
                $post['amty'] = $total *  $post['amty'] / 100;
        } else {
            if ($post['amty'] == '')
                $post['amty'] = 0;
        }

        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '')
                $post['cess'] = 0;
            else
                $post['cess'] = $total *  $post['cess'] / 100;
        } else {
            if ($post['cess'] == '')
                $post['cess'] = 0;
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total *  $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }

        $netamount = $total + $post['amty']  +  $post['tot_igst'];

        if (in_array('tds', $post['taxes'])) {
            $netamount +=   $tds_amt;
        }
        if (in_array('cess', $post['taxes'])) {
            $netamount +=   $post['cess'];
        }
        //echo '<pre>';Print_r($post);exit;

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
            'invoice_date' => db_date($post['invoice_date']),
            'invoice_no' => $post['invoice_no'],
            'party_account' => @$post['party_account'],
            'gst' => @$post['gst'],
            'v_type' => @$post['v_type'],
            'return_sale' => @$post['invoice'],
            'other' => @$post['other'],
            'tds_per' => @$post['tds_per'],
            'tds_amt' => $post['tds_amt'],
            'tds_limit' => @$post['tds_limit'],
            'acc_state' => @$post['acc_state'],
            'taxes' => json_encode($post['taxes']),
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'amty' => $amty,
            'amty_type' => $post['amty_type'],
            'cess' => @$post['cess'],
            'cess_type' => @$post['cess_type'],
            'supp_inv' => $post['supp_inv'],
            'supp_inv_date' => @$post['supp_inv_date'] ? db_date(@$post['supp_inv_date']) : '',
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'total_amount' => $total,
            'net_amount' => $netamount + (@$post['round_diff'] ? $post['round_diff'] : 0),
            'round' => @$post['round'],
            'round_diff' => @$post['round_diff'],
            'taxable' => @$post['taxable'],
            'bank_name' => @$post['bank_name'] ? $post['bank_name'] : '',
            'bank_ac' => @$post['bank_ac'] ? $post['bank_ac'] : '',
            'bank_ifsc' => @$post['bank_ifsc'] ? $post['bank_ifsc'] : '',
            'bank_holder' => @$post['bank_holder'] ? $post['bank_holder'] : '',
            'igst_acc' => @$igst_acc,
            'cgst_acc' =>  @$cgst_acc,
            'sgst_acc' =>  @$sgst_acc,
        );
        //echo '<pre>';Print_r($pdata);exit;

        if ($post['gst'] != '') {
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
        // echo '<pre>';print_r($pdata);exit;
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);

                $account_builder = $db->table('sales_ACparticu');
                $account_result = $account_builder->select('GROUP_CONCAT(account) as account')->where(array("parent_id" => $post['id'], "type" => $post['v_type']))->get();
                $getAccount = $account_result->getRow();

                $getpid = explode(',', $getAccount->account);
                $delete_accountid = array_diff($getpid, $pid);

                if (!empty($delete_accountid)) {
                    foreach ($delete_accountid as $key => $del_id) {
                        $del_data = array('is_delete' => '1');
                        $account_builder->where(array('account' => $del_id, 'parent_id' => $post['id'], 'type' => $post['v_type']));
                        $account_builder->update($del_data);
                    }
                }

                for ($i = 0; $i < count($pid); $i++) {
                    $account_result = $account_builder->select('*')->where(array("account" => $pid[$i], "parent_id" => $post['id']))->get();
                    $getAccount = $account_result->getRow();

                    $sub_total = 0;
                    if ($post['discount'] > 0) {
                        $sub_total = $post['subtotal'][$i] - $post['item_discount_hidden'][$i];
                    } else {
                        $sub_total = $post['subtotal'][$i];
                    }
                    //echo '<pre>';Print_r($sub_total);exit;

                    if (!empty($getAccount)) {
                        $account_data = array(

                            'amount' => $post['price'][$i],
                            'igst' => $post['igst'][$i],
                            'cgst' => $post['cgst'][$i],
                            'sgst' => $post['sgst'][$i],
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            'sub_total' => $sub_total,
                            'remark' => $post['remark'][$i],
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
                            'igst_amt' => $post['igst_amt'][$i],
                            'cgst_amt' => $post['cgst_amt'][$i],
                            'sgst_amt' => $post['sgst_amt'][$i],
                            'taxability' => $post['taxability'][$i],
                            'sub_total' => $sub_total,
                            'remark' => $post['remark'][$i],
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

                $builder = $db->table('sales_ACparticu');

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
                    $sub_total = 0;
                    if ($post['discount'] > 0) {
                        $sub_total = $post['subtotal'][$i] - $post['item_discount_hidden'][$i];
                    } else {
                        $sub_total = $post['subtotal'][$i];
                    }
                    //echo '<pre>';Print_r($sub_total);

                    $accountdata[] = array(
                        'parent_id' => $id,
                        'account' => $post['pid'][$i],
                        'type' => @$post['v_type'],
                        'amount' => $post['price'][$i],
                        'igst' => $post['igst'][$i],
                        'cgst' => $post['cgst'][$i],
                        'sgst' => $post['sgst'][$i],
                        'igst_amt' => $post['igst_amt'][$i],
                        'cgst_amt' => $post['cgst_amt'][$i],
                        'sgst_amt' => $post['sgst_amt'][$i],
                        'taxability' => $post['taxability'][$i],
                        'sub_total' => $sub_total,
                        'remark' => $post['remark'][$i],
                        'discount' => $post['item_discount_hidden'][$i],
                        'added_amt' => $post['item_added_amt_hidden'][$i],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session('uid'),
                    );
                }
                // exit;
                $account_builder = $db->table('sales_ACparticu');
                $result1 = $account_builder->insertBatch($accountdata);

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }
    // update trupti 24-11-2022
    public function search_item_data($term)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('*');
        if ($term != '') {
            $where = "(`code` LIKE '%" . $term . "%' OR  `name` LIKE '%" . $term . "%') AND `is_delete` = '0'";
        } else {
            $where = "`is_delete` = '0'";
        }
        $builder->where($where);
        $builder->limit(10);
        $query = $builder->get();
        $getdata = $query->getResultArray();

        foreach ($getdata as $row) {

            $item_uom = explode(',', $row['uom']);
            $option = '';
            $gmodel = new GeneralModel();

            foreach ($item_uom as $uom) {
                $uom_name  = $gmodel->get_data_table('uom', array('id' => $uom), 'code');
                $option .= '<option value="' . $uom_name['code'] . '">' . $uom_name['code'] . '</option>';
            }

            $price_data = array(
                "id" => $row['id'],
                "hsn" => $row['hsn'],
                "taxability" => $row['taxability'],
                'sales_price' => $row['sales_price'],
                'purchase_price' => $row['purchase_cost'],
                'igst' => $row['igst'],
                'cgst' => $row['cgst'],
                'sgst' => $row['sgst'],
                'brokrage' => $row['brokrage'],

            );

            $result[] = array(
                "text" => $row['name'],
                "id" => $row['id'],
                "price" => $price_data,
                "uom" => $option,
                'is_expence' => 0,
            );
        }

        return $result;
    }

    public function get_BankCashAdvance($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('bank_tras');
        $builder->select('*');
        $builder->where(array("nature_pay" => 2));
        $builder->orWhere(array("nature_rec" => 2));
        $builder->where(array("particular" => $post['account']));
        $builder->limit(10);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        $result = array();
        foreach ($getdata as $row) {
            $gmodel = new GeneralModel();
            $ac_name = $gmodel->get_data_table('account', array('id' => $row['particular']), 'name');
            $result[] = array(
                "text" => $row['id'] . ' ( ' . $ac_name['name'] . ' )' . ' - ₹ ' . $row['amount'],
                "id" => $row['id']
            );
        }
        return $result;
    }

    public function get_challan_detail($get)
    {

        $dt_search = array(
            "sc.challan_date",
            "sc.challan_no",
            "sc.custom_challan_no",
            "(select name from account ac where ac.id = sc.account)",
            "sc.net_amount"
        );

        $dt_col = array(
            "sc.id",
            "sc.challan_no",
            "sc.custom_challan_no",
            "sc.challan_date",
            "sc.net_amount",
            "(select name from account ac where ac.id = sc.account) as account_name",
            "(select name from account ac where ac.id = sc.broker) as broker_name",
            "sc.account",
            "sc.broker",
            "sc.delivery_code",
            "sc.lr_no",
            "sc.lr_date",
            "sc.weight",
            "sc.freight",
            "sc.transport",
            "sc.gst",
            "sc.is_cancle",
            "sc.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "sales_challan sc";
        $where = '';
        $where .= " and is_delete=0";
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        $gmodel = new GeneralModel();

        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title="Cancle Challan" onclick="editable_os(this)"  data-val="' . $row['is_cancle'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '"><i class="far fa-times-circle"></i></a>';

            $btnedit = '<a href="' . url('Sales/add_challan/') . $row['id'] . '" data-target="#fm_model"  data-title="Edit Group : " class="btn btn-link pd-6"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('Sales/challan_detail/') . $row['id'] . '"    class="btn btn-link pd-6"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Delete Voucher"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6"><i class="far fa-trash-alt"></i></a> ';
            $btnpdf = '<a href="' . url('sales/pdf_challan/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';

            $getMax = $gmodel->get_data_table('sales_challan', array('is_delete' => 0), 'MAX(challan_no) as max_no');


            if ($row['is_cancle'] == 1 || $row['is_delete'] == 1) {
                $btn =  $btnview . $btnpdf;
            } else {
                $btn =  $btnedit . $btnview . $btnpdf;
            }

            if ($getMax['max_no'] == $row['challan_no']) {
                $btn .= $btndelete;
            } else {
                if ($row['is_cancle'] == 0) {
                    $btn .= $btn_cancle;
                }
            }

            $date = user_date($row['challan_date']);
            if (!empty($row['gst'])) {
                $gst = '<br>(' . $row['gst'] . ')';
            } else {
                $gst = '';
            }
            $DataRow[] = $row['challan_no'];
            $DataRow[] = $row['custom_challan_no'];
            $DataRow[] = $date;
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
    public function search_challan_data($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_challan sc');
        $builder->select('sc.*,ac.name as account_name,ac.default_due_days');
        $builder->join('account ac', 'ac.id = sc.account');
        if (!empty(@$post['searchTerm'])) {
            $builder->like('sc.challan_no', @$post['searchTerm']);
        }
        $builder->where('sc.is_delete', '0');
        $builder->where('sc.is_cancle', '0');
        $query = $builder->get();
        $challan = $query->getResultArray();

        // $getdata['challan'] = $challan[0];

        $gmodel = new GeneralModel();

        foreach ($challan as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name,brokrage');
            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');
            // $getclass = $gmodel->get_data_table('class',array('id'=>$row['class']),'code');

            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'code');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle_modeno']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            if (empty($getbroker)) {
                $getbroker['name'] = '';
            }
            if ($row['lr_date'] == '0000-00-00') {
                $row['lr_date'] = '';
            } else {
                $dt = date_create($row['lr_date']);
                $row['lr_date'] = date_format($dt, 'd-m-Y');
            }
            if (empty($gettransport)) {
                $gettransport['code'] = '';
            }
            if (empty($getcity)) {
                $getcity['name'] = '';
            }
            if (empty($getvehicle)) {
                $getvehicle['name'] = '';
            }
            $row['broker_name'] = @$getbroker['name'];
            $row['fix_brokrage'] = @$getbroker['brokrage'];
            $row['delivery_name'] = @$getdelivery['name'];

            $row['transport_name'] = @$gettransport['code'];
            $row['city_name'] = @$getcity['name'];
            $row['vehicle_name'] = @$getvehicle['name'];
            $row['igst_acc_name'] = @$igst_acc['name'];
            $row['sgst_acc_name'] = @$sgst_acc['name'];
            $row['cgst_acc_name'] = @$cgst_acc['name'];

            // $item_builder =$db->table('sales_item st');
            // $item_builder->select('st.*,i.id,i.type,i.item_mode,i.name,i.sku,i.purchase_cost,i.hsn,i.code,i.uom as item_uom ,st.uom as uom');
            // $item_builder->join('item i','i.id = st.item_id');
            // $item_builder->where(array('st.parent_id' => $row['id'],'st.type' => 'challan' , 'st.is_delete' => 0 ));
            // $query= $item_builder->get();
            // $item1 = $query->getResultArray();

            $total_challan_qty = 0;
            // foreach($item1 as $row2){
            //     $total_challan_qty += $row2['qty'];
            //     $uom =  explode(',',$row2['item_uom']);
            //     foreach($uom as $row1){
            //         $getuom = $gmodel->get_data_table('uom',array('id'=>$row1),'code');
            //         $uom_arr[] =$getuom['code']; 
            //     }

            //     $coma_uom = implode(',',$uom_arr);
            //     $row2['item_uom'] =$coma_uom; 
            //     $item[] = $row2;
            //     $uom_arr = array();
            // } 
            $item_builder = $db->table('sales_item st');
            $item_builder->select('st.*,st.uom as uom');
            //$item_builder->join('item i','i.id = st.item_id');
            $item_builder->where(array('st.parent_id' => $row['id'], 'st.type' => 'challan', 'st.is_delete' => 0));
            $query = $item_builder->get();
            $getdata1 = $query->getResultArray();
            // echo '<pre>';print_r($getdata1);exit;
            $item = array();
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
                    $row2['hsn'] = $getitem['hsn'];
                } else {
                    $getaccount = $gmodel->get_data_table('account', array('id' => $row2['item_id']), 'id,name,code');
                    $row2['id'] = $row2['item_id'];
                    $row2['name'] = $getaccount['name'];
                    $row2['code'] = $getaccount['code'];
                }
                $item[] = $row2;
                //$uom_arr = array();
            }
            // print_r($item);exit;
            $builder = $db->table('sales_invoice si');
            $builder->select('si.*,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where('si.is_delete', '0');
            $builder->where('si.challan_no', $row['id']);
            $query = $builder->get();
            $invoice = $query->getResultArray();

            $total_qty = 0;
            foreach ($invoice as $row1) {
                $item_builder = $db->table('sales_item st');
                $item_builder->select('SUM(qty) as qty');
                $item_builder->join('item i', 'i.id = st.item_id');
                $item_builder->where(array('st.parent_id' => $row1['id'], 'st.type' => 'invoice', 'st.is_delete' => 0));
                $query = $item_builder->get();
                $item2 = $query->getRowArray();

                $total_qty += $item2['qty'];
            }
            //print_r($total_qty);

            $dt = date_create($row['challan_date']);
            $date = date_format($dt, 'd-m-Y');

            $text = $row['challan_no'] . ' (' . $row['account_name'] . ') /' . $date;
            if ($total_qty < $total_challan_qty) {
                $result[] = array("text" => $text, "id" => $row['id'], 'challan' => $row, 'item' => $item);
            } else {
                $result = array();
            }
            unset($item);
        }
        // echo '<pre>';print_r($result);exit;
        return $result;
    }

    // update trupti 24-11-2022
    public function get_sales_challan($id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_challan sc');
        $builder->select('sc.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sc.account');
        $builder->where(array('sc.id' => $id));
        $query = $builder->get();
        $challan = $query->getResultArray();

        $getdata['challan'] = $challan[0];

        $gmodel = new GeneralModel();

        foreach ($challan as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');

            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'code');
            $getcity = $gmodel->get_data_table('cities', array('id' => $row['city']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vehicle_modeno']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            $getround = $gmodel->get_data_table('account', array('id' => @$row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            $getdata['challan']['broker_name'] = @$getbroker['name'];
            $getdata['challan']['delivery_name'] = @$getdelivery['name'];
            $getdata['challan']['transport_name'] = @$gettransport['code'];
            $getdata['challan']['city_name'] = @$getcity['name'];
            $getdata['challan']['vehicle_name'] = @$getvehicle['name'];
            $getdata['challan']['voucher_name'] = @$getvoucher['name'];
            $getdata['challan']['round_name'] = @$getround['name'];
            $getdata['challan']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['challan']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['challan']['cgst_acc_name'] = @$cgst_acc['name'];
        }

        $item_builder = $db->table('sales_item st');
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
                $row['hsn'] = '';
            }
            $getdata['item'][] = $row;
            //$uom_arr = array();
        }
        //echo '<pre>';print_r($getdata);exit;  
        return $getdata;
    }
    // update trupti 24-11-2022
    public function insert_edit_salesinvoice($post)
    {
        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Item");
            return $msg;
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("custom_inv_no" => $post['custom_inv_no'], "is_delete" => 0, "is_cancle" => 0));
        $builder->limit(1);
        $result1 = $builder->get();
        $result_array1 = $result1->getRow();
        $msg = array();

        if (!empty($result_array1)) {
            if ($result_array1->id != $post['id']) {
                $msg = array('st' => 'fail', 'msg' => "Custom Invoice Number Already Exist!!!");
                return $msg;
            }
        }
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
        $final_sub = 0;
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
        // if ($post['amty_type'] == '%') {
        //     if ($post['amty'] == '')
        //         $post['amty'] = 0;
        //     else
        //         $post['amty'] = $total *  $post['amty'] / 100;
        // } else {
        //     if ($post['amty'] == '')
        //         $post['amty'] = 0;
        //     else
        //         $post['amty'] = $post['amty'];
        // }

        if ($post['cess_type'] == '%') {
            if ($post['cess'] == '')
                $post['cess'] = 0;
            else
                $post['cess'] = $total *  $post['cess'] / 100;
        } else {
            if ($post['cess'] == '')
                $post['cess'] = 0;
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total *  $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }
        $netamount = $total + $post['cess'] + $tds_amt + $post['tot_igst'];
      
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
            'invoice_no' => $post['invoice_no'],
            'custom_inv_no' => @$post['custom_inv_no'] ? $post['custom_inv_no'] : '',
            'invoice_date' => db_date($post['invoice_date']),
            'challan_no' => @$post['challan'] ? $post['challan'] : '',
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => $post['gst'],
            'broker' => @$post['broker'],
            'other' => $post['other'],
            'lr_no' => $post['lrno'],
            'lr_date' => $post['lr_date'],
            'delivery_code' => @$post['delivery_code'],
            'transport' => @$post['transport'],
            'transport_mode' => @$post['trasport_mode'],
            'vhicle_no' => @$post['vehicle'],
            'total_amount' => $total,
            'taxes' => json_encode(@$post['taxes']),
            'tot_igst' => $post['tot_igst'],
            'tot_cgst' => $post['tot_cgst'],
            'tot_sgst' => $post['tot_sgst'],
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            // 'amty' => $amty,
            // 'amty_type' => $post['amty_type'],
            'cess_type' => $post['cess_type'],
            'cess' => $cess,
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'brokrage_type' => @$post['brokerage_type'],
            'net_amount' => round($netamount),
            'due_days' => $post['due_day'],
            'due_date' => $post['due_date'],
            'stat_adj' => isset($post['stat_adj']) ? $post['stat_adj'] : 0,
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
        if ($post['gst'] != '') {
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
      
        if (isset($post['stat_adj']) && $post['stat_adj'] == 1) {
            $pdata['ref_type'] = $post['ref_type'];
            $pdata['voucher_amt'] = $post['voucher_amt'];
            if ($post['ref_type'] == 'Advance') {
                $pdata['voucher'] = $post['voucher'];
            }
        }
        $gnmodel = new GeneralModel();
        //echo '<pre>';Print_r($post);exit;
        
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                if(session('DataSource')=='ACE20223HUY')
                {
                    $result_jv = $gnmodel->update_data_table('jv_management', array('invoice_no' => $post['id'],'type' => "invoice"), array('is_update' => '1'));
                }
                
                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0, "is_expence" => 0))->get();
                $getItem = $item_result->getRow(); 

                $account_builder = $db->table('sales_item');
                $account_result = $account_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0,"expence_type"=>'',"is_expence" => 1))->get();
                $getAccount = $account_result->getRow();

                $account_builder = $db->table('sales_item');
                $discount_ac_result = $account_builder->select('item_id')->where(array("parent_id" => $post['id'], "type" => 'invoice', "is_delete" => 0,'expence_type'=>'discount', "is_expence" => 1))->get();
                $getDiscount = $discount_ac_result->getRow();

                $account_builder = $db->table('sales_item');
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
                        $result_up = $gnmodel->update_data_table('sales_item', array('item_id' => $getDiscount->item_id, 'parent_id' => $post['id'], 'type' => 'invoice','expence_type'=>'discount'), array('is_delete' => '1'));
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
                        $item_builder = $db->table('sales_item');
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
                        $result_up = $gnmodel->update_data_table('sales_item', array('item_id' => $getRound->item_id, 'parent_id' => $post['id'], 'type' => 'invoice','expence_type'=>'rounding_invoices'), array('is_delete' => '1'));
             
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
                        $item_builder = $db->table('sales_item');
                        $result2 = $item_builder->insertBatch($round_itemdata);
                    }
                }
                $new_item = array();
                $new_itempid = array();
                $new_account = array();
                $new_accountpid = array();
                
                for ($i = 0; $i < count($post['pid']); $i++) {

                    if ($post['expence'][$i] == 0) {
                        $sub_total = 0;
                        if($post['divide_disc_amt'][$i] == '')
                        {
                            $post['divide_disc_amt'][$i] = 0.00;
                        }
                       
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
                        $item['remark'] = $post['remark'][$i];
                        $new_item[] = $item;
                        $new_itempid[] = $post['pid'][$i];
                    } else {
                        $item['pid'] = $post['pid'][$i];
                        $item['hsn'] = $post['hsn'][$i];
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
                        //update discount column 17-01-2023
                        $item['total'] = $post['price'][$i];
                        $item['item_disc'] = 0;
                        $item['discount'] = 0;
                        $item['divide_disc_item_per'] = 0;
                        $item['divide_disc_item_amt'] = 0;
                        $item['sub_total'] = $post['price'][$i];
                        //end
                        $item['remark'] = $post['remark'][$i];
                        $new_account[] = $item;
                        $new_accountpid[] = $post['pid'][$i];
                    }
                }
                $getitem = explode(',', $getItem->item_id);
                $getAccount = explode(',', $getAccount->item_id);

                $delete_itemid = array_diff($getitem, $new_itempid);
                $delete_account = array_diff($getAccount, $new_accountpid);

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

                        $qty = $new_item[$i]['qty'] - $getItem->qty;
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'hsn' => $new_item[$i]['hsn'],
                            'type' => 'invoice',
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
                            'hsn' => $new_item[$i]['hsn'],
                            'type' => 'invoice',
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
                            'remark' => $new_item[$i]['remark'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $item_builder->insert($item_data);
                    }
                }

                for ($i = 0; $i < count($new_account); $i++) {
                    $account_result = $account_builder->select('*')->where(array("item_id" => $new_account[$i]['pid'], "parent_id" => $post['id'], 'type' => 'invoice', 'is_delete' => 0, 'is_expence' => 1))->get();
                    $getAccount = $account_result->getRow();
                    //print_r($getAccount);
                    if (!empty($getAccount)) {
                        //echo 'get';

                        $acc_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            'hsn' => '',
                            'type' => 'invoice',
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
                            'hsn' => '',
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
                            'remark' => $new_account[$i]['remark'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                        $res = $account_builder->insert($acc_data);
                    }
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
                            'expence_type'=>'',
                            'is_expence' => 0,
                            'item_id' => $post['pid'][$i],
                            'hsn' => $post['hsn'][$i],
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
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    } else {
                        $itemdata[] = array(
                            'parent_id' => $id,
                            'expence_type'=>'',
                            'is_expence' => 1,
                            'item_id' => $post['pid'][$i],
                            'hsn' => '',
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
                            'remark' => $post['remark'][$i],
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => session('uid'),
                        );
                    }
                }
                $item_builder = $db->table('sales_item');
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
                    $item_builder = $db->table('sales_item');
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
                    $item_builder = $db->table('sales_item');
                    $result3 = $item_builder->insertBatch($discount_itemdata);
                } 

                if(session('DataSource')=='ACE20223HUY')
                {
                    $platform_data = array(
                        'voucher' => $id,
                        'type' => "invoice",
                        'platform_id' => 1,
                        'custom_inv_no' => $post['custom_inv_no'],
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

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                    // return view('master/account_view');
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }

        return $msg;
    }


    public function get_salesinvoice_data($get)
    {
        $dt_search = array(
            "si.id",
            "si.invoice_date",
            "si.custom_inv_no",
            "(select name from account a where a.id = si.account)",
            "si.net_amount",
            "si.other",
        );

        $dt_col = array(
            "si.id",
            "si.invoice_date",
            "si.invoice_no",
            "si.custom_inv_no",
            "(select name from account a where a.id = si.account) as account_name",
            "si.account",
            "(select name from account a where a.id = si.broker) as broker_name",
            "si.net_amount",
            "si.other",
            "si.is_cancle",
            "si.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "sales_invoice si";
        $where = '';

        $where .= " and is_delete=0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();

        $gmodel = new GeneralModel();
        foreach ($rResult['table'] as $row) {
            $DataRow = array();

            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $btn_cancle = '<a target="_blank" title=" ' . $row['account_name'] . '" onclick="editable_os(this)"  data-val="' . $row['is_cancle'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '"><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a href="' . url('sales/add_salesinvoice/') . $row['id'] . '" class="btn btn-link pd-6"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('sales/invoice_detail/') . $row['id'] . '"    class="btn btn-link pd-6"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="challan : ' . $row['id'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6"><i class="far fa-trash-alt"></i></a> ';
            $btnpdf = '<a href="' . url('sales/pdf_invoice/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';

            $getMax = $gmodel->get_data_table('sales_invoice', array('is_delete' => 0), 'MAX(invoice_no) as max_no');


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

            $date = user_date($row['invoice_date']);
            $DataRow[] = $row['id'];
            $DataRow[] = $row['invoice_no'];
            $DataRow[] = $row['custom_inv_no'];
            $DataRow[] = $date;
            $DataRow[] = $row['account_name'];
            $DataRow[] = number_format($row['net_amount'], 2);
            $DataRow[] = $row['other'];
            $DataRow[] = ($row['is_cancle'] == 1) ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    // update trupti 24-11-2022
    public function get_sales_invoice($id)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_invoice si');
        $builder->select('si.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.id' => $id));
        $query = $builder->get();
        $invoice = $query->getResultArray();

        $getdata['salesinvoice'] = $invoice[0];
        $gmodel = new GeneralModel();
        foreach ($invoice as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name');
            $getchallan = $gmodel->get_data_table('sales_challan', array('id' => $row['challan_no']), '*');
            if (!empty($getchallan)) {
                $getchallan_ac = $gmodel->get_data_table('account', array('id' => @$getchallan['account']), 'name');
                $challan_no = $getchallan['challan_no'] . '(' . $getchallan_ac['name'] . ')/ ' . user_date($getchallan['challan_date']);
            } else {
                $getchallan_ac = '';
                $challan_no = '';
            }

            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');

            $gettransport = $gmodel->get_data_table('transport', array('id' => $row['transport']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => $row['vhicle_no']), 'name');
            // $getdiscount = $gmodel->get_data_table('account', array('id' => $row['discount']), 'name');
            $getvoucher = $gmodel->get_data_table('account', array('id' => $row['voucher_type']), 'name');
            // $getround = $gmodel->get_data_table('account', array('id' => $row['round']), 'name');
            $igst_acc = $gmodel->get_data_table('account', array('id' => @$row['igst_acc']), 'name');
            $sgst_acc = $gmodel->get_data_table('account', array('id' => @$row['sgst_acc']), 'name');
            $cgst_acc = $gmodel->get_data_table('account', array('id' => @$row['cgst_acc']), 'name');

            $getdata['salesinvoice']['voucher_name'] = @$getvoucher['name'];
            $getdata['salesinvoice']['broker_name'] = @$getbroker['name'];
            $getdata['salesinvoice']['delivery_name'] = @$getdelivery['name'];
            $getdata['salesinvoice']['transport_name'] = @$gettransport['name'];
            $getdata['salesinvoice']['vehicle_name'] = @$getvehicle['name'];
            // $getdata['salesinvoice']['broker_ledger_name'] = @$getbroker_ledger['name'];
            // $getdata['salesinvoice']['round_name'] = @$getround['name'];
            // $getdata['salesinvoice']['discount_name'] = @$getdiscount['name'];
            $getdata['salesinvoice']['challan_name'] = @$challan_no;
            $getdata['salesinvoice']['igst_acc_name'] = @$igst_acc['name'];
            $getdata['salesinvoice']['sgst_acc_name'] = @$sgst_acc['name'];
            $getdata['salesinvoice']['cgst_acc_name'] = @$cgst_acc['name'];
        }

        $item_builder = $db->table('sales_item st');
        $item_builder->select('st.*,st.uom as uom');
        //$item_builder->join('item i','i.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'invoice','st.expence_type'=>'', 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdata1 = $query->getResultArray();
        //echo '<pre>';print_r($getdata1);exit;
        foreach ($getdata1 as $row) {
            if ($row['is_expence'] == 0) {
                $getitem = $gmodel->get_data_table('item', array('id' => $row['item_id']), 'id,type,name,sku,purchase_cost,hsn,code,uom as item_uom');
                // print_r($row['item_id']);
                // print_r($getitem);exit;
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
                $row['hsn'] = '';
            }
            $getdata['item'][] = $row;
            //$uom_arr = array();
        }

        $item_builder = $db->table('sales_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'invoice','st.expence_type'=>'rounding_invoices','is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getrounding = $query->getRowArray();

        $getdata['salesinvoice']['round_acc'] = @$getrounding['item_id'];
        $getdata['salesinvoice']['round_acc_name'] = @$getrounding['acc_name'];

        $item_builder = $db->table('sales_item st');
        $item_builder->select('st.*,ac.name as acc_name');
        $item_builder->join('account ac','ac.id = st.item_id');
        $item_builder->where(array('st.parent_id' => $id, 'st.type' => 'invoice','st.expence_type'=>'discount','is_expence'=>1, 'st.is_delete' => 0));
        $query = $item_builder->get();
        $getdiscount = $query->getRowArray();

        $getdata['salesinvoice']['discount_acc'] = @$getdiscount['item_id'];
        $getdata['salesinvoice']['discount_acc_name'] = @$getdiscount['acc_name'];

        return $getdata;
    }
    // update trupti 24-11-2022
    public function insert_edit_salesreturn($post)
    {

        if (!@$post['pid']) {
            $msg = array('st' => 'fail', 'msg' => "Please Select any Product");
            return $msg;
        }
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_return');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();

        $builder = $db->table('sales_return');
        $builder->select('*');
        $builder->where(array("supp_inv" => $post['supp_inv'], "is_delete" => 0, "is_cancle" => 0));
        $builder->limit(1);
        $result1 = $builder->get();
        $result_array1 = $result1->getRow();
        $msg = array();

        if (!empty($result_array1)) {
            if ($result_array1->id != $post['id']) {
                $msg = array('st' => 'fail', 'msg' => "Supply Invoice Number Already Exist!!!");
                return $msg;
            }
        }

        $pid = $post['pid'];
        $qty = $post['qty'];
        $price = $post['price'];
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
            if ($post['cess'] == '')
                $post['cess'] = 0;
            else
                $post['cess'] = $total *  $post['cess'] / 100;
        } else {
            if ($post['cess'] == '')
                $post['cess'] = 0;
        }

        if (!empty($post['tds_per'])) {
            $tds_amt = $total *  $post['tds_per'] / 100;
        } else {
            $tds_amt = 0;
        }

        $netamount = $total + $post['cess'] + $tds_amt + $post['tot_igst'];

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

        $msg = array();
        $pdata = array(
            'voucher_type' => $post['voucher_type'],
            'gl_group' => $post['gl_group'],
            'return_no' => $post['return_no'],
            'supp_inv' => $post['supp_inv'],
            'return_date' => db_date($post['return_date']),
            'account' => $post['account'],
            'tds_limit' => $post['tds_limit'],
            'acc_state' => $post['acc_state'],
            'gst' => @$post['gst'],
            'broker' => @$post['broker'],
            'other' => @$post['other'],
            'invoice' => !empty($post['invoice']) ? $post['invoice'] : '',
            'total' => $total,
            'discount' => $discount,
            'disc_type' => $post['disc_type'],
            'cess' => $cess,
            'cess_type' => $post['cess_type'],
            'tds_amt' => $post['tds_amt'],
            'tds_per' => $post['tds_per'],
            'net_amount' => round($netamount),
            'delivery_code' => @$post['delivery_code'],
            'taxes' => json_encode(@$post['taxes']),
            'net_amount' => round($netamount),
            'lr_no' => $post['lrno'],
            'lr_date' => $post['lr_date'],
            'weight' => $post['weight'],
            'freight' => $post['freight'],
            'transport' => @$post['transport'],
            'city' => @$post['city'],
            'transport_mode' => @$post['trasport_mode'],
            'vehicle_no' => @$post['vhicle_modeno'],
            'tot_igst' => $post['tot_igst'],
            'tot_sgst' => $post['tot_sgst'],
            'tot_cgst' => $post['tot_cgst'],
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
        if ($post['gst'] != '') {
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
        //print_r($post);exit;
        $gnmodel = new GeneralModel();
        if (!empty($result_array)) {

            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                
                if(session('DataSource')=='ACE20223HUY')
                {
                    $result_jv = $gnmodel->update_data_table('jv_management', array('invoice_no' => $post['id'],'type' => "return"), array('is_update' => '1'));
                }

                $item_builder = $db->table('sales_item');
                $item_result = $item_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return', 'expence_type'=>'',"is_delete" => 0, "is_expence" => 0))->get();
                $getItem = $item_result->getRow();

                $account_builder = $db->table('sales_item');
                $account_result = $account_builder->select('GROUP_CONCAT(item_id) as item_id')->where(array("parent_id" => $post['id'], "type" => 'return','expence_type'=>'', "is_delete" => 0, "is_expence" => 1))->get();
                $getAccount = $account_result->getRow();

                $account_builder = $db->table('sales_item');
                $discount_ac_result = $account_builder->select('item_id')->where(array("parent_id" => $post['id'], "type" => 'return', "is_delete" => 0,'expence_type'=>'discount', "is_expence" => 1))->get();
                $getDiscount = $discount_ac_result->getRow();

                $account_builder = $db->table('sales_item');
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
                        $result_up = $gnmodel->update_data_table('sales_item', array('item_id' => $getDiscount->item_id, 'parent_id' => $post['id'], 'type' => 'return','expence_type'=>'discount'), array('is_delete' => '1'));
            
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
                        $item_builder = $db->table('sales_item');
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
                        $result_up = $gnmodel->update_data_table('sales_item', array('item_id' => $getRound->item_id, 'parent_id' => $post['id'], 'type' => 'return','expence_type'=>'rounding_invoices'), array('is_delete' => '1'));
             
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
                        $item_builder = $db->table('sales_item');
                        $result2 = $item_builder->insertBatch($round_itemdata);
                    }
                }

                $new_item = array();
                $new_itempid = array();
                $new_account = array();
                $new_accountpid = array();
                //print_r($post['expence']);exit;
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
                        $item['hsn'] = $post['hsn'][$i];
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

                //print_r($new_accountpid);exit;

                $getitem = explode(',', $getItem->item_id);
                $getAccount = explode(',', $getAccount->item_id);

                $delete_itemid = array_diff($getitem, $new_itempid);
                $delete_account = array_diff($getAccount, $new_accountpid);

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
                    $item_result = $item_builder->select('*')->where(array("item_id" => $new_item[$i]['pid'], "parent_id" => $post['id'], "type" => 'return', 'is_delete' => 0, 'is_expence' => 0))->get();
                    $getItem = $item_result->getRow();
                    if (!empty($getItem)) {
                        $qty = $new_item[$i]['qty'] - $getItem->qty;
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'hsn' => $new_item[$i]['hsn'],
                            'type' => 'return',
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
                        $item_builder->where(array('item_id' => $getItem->item_id, 'parent_id' => $post['id'], "type" => 'return', 'is_delete' => 0, 'is_expence' => 0));
                        $res = $item_builder->update($item_data);
                    } else {
                        $item_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 0,
                            'item_id' => $new_item[$i]['pid'],
                            'hsn' => $new_item[$i]['hsn'],
                            'type' => 'return',
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
                //exit;
                //echo '<pre>';print_r($new_account);exit;
                for ($i = 0; $i < count($new_account); $i++) {
                    $account_result = $account_builder->select('*')->where(array("item_id" => $new_account[$i]['pid'], "parent_id" => $post['id'], 'type' => 'return', 'is_delete' => 0, 'is_expence' => 1))->get();
                    $getAccount = $account_result->getRow();
                    //print_r($getAccount);
                    if (!empty($getAccount)) {
                        //echo 'get';

                        $acc_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            'hsn' => '',
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
                            'update_at' => date('Y-m-d H:i:s'),
                            'update_by' => session('uid'),
                        );
                        $account_builder->where(array('item_id' => $getAccount->item_id, 'parent_id' => $post['id'], 'type' => 'return', 'is_delete' => 0, 'is_expence' => 1));
                        $res = $account_builder->update($acc_data);
                    } else {
                        $acc_data = array(
                            'parent_id' => $post['id'],
                            'is_expence' => 1,
                            'item_id' => $new_account[$i]['pid'],
                            'hsn' => '',
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
                        $res = $account_builder->insert($acc_data);
                    }
                }
                // exit;
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
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
                            'hsn' => '',
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
                //print_r($itemdata);exit;
                $item_builder = $db->table('sales_item');
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
                    $item_builder = $db->table('sales_item');
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
                    $item_builder = $db->table('sales_item');
                    $result3 = $item_builder->insertBatch($discount_itemdata);
                }

                if(session('DataSource')=='ACE20223HUY')
                {
                    $builder = $db->table('sales_return');
                    $platform_data = array(
                        'voucher' => $id,
                        'type' => "return",
                        'platform_id' => 1,
                        'custom_inv_no' => $post['supp_inv'],
                        'invoice_date' => db_date(@$post['return_date']),
                        'database_name' => @$post['database'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => 0,
                    );
                    $platform_builder = $db->table('platform_voucher');
                    $sstatus = $platform_builder->Insert($platform_data);

                    $jv_data = array(
                        'invoice_no' => $id,
                        'type' => "return",
                        'platform_id' => 1,
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

                if ($result &&  $result1) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }

    public function get_salesreturn_data($get)
    {

        $dt_search = array(
            "sr.id",
            "sr.return_no",
            "sr.return_date",
            "sr.supp_inv",
            "(select name from account a where a.id = sr.account)",
            "sr.net_amount",
            "sr.other",
        );

        $dt_col = array(
            "sr.id",
            "sr.return_no",
            "sr.supp_inv",
            "sr.return_date",
            "(select name from account a where a.id = sr.account) as account_name",
            "sr.account",
            "(select name from account a where a.id = sr.broker) as broker_name",
            "sr.broker",
            "sr.net_amount",
            "sr.other",
            "sr.is_cancle",
            "sr.is_delete",
        );

        $filter = $get['filter_data'];
        $tablename = "sales_return sr";
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
            $statusarray = array("1" => "Cancled", "0" => "Cancle");

            $DataRow = array();

            $btn_cancle = '<a target="_blank" title=" ' . $row['account_name'] . '" onclick="editable_os(this)"  data-val="' . $row['is_cancle'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-6" title="' . $statusarray[$row['is_cancle']] . '"><i class="far fa-times-circle"></i></a>';
            $btnedit = '<a   href="' . url('sales/add_salesreturn/') . $row['id'] . '" data-title="Edit Sales Return : ' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btnview = '<a href="' . url('sales/return_detail/') . $row['id'] . '"    class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a  target="_blank"   title="Sales Return: ' . $row['account_name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btnpdf = '<a href="' . url('sales/pdf_return/') . $row['id'] . '" class="btn btn-link pd-6"><i class="fas fa-print"></i></a> ';

            $getMax = $gmodel->get_data_table('sales_return', array('is_delete' => 0), 'MAX(return_no) as max_no');

            if ($row['is_cancle'] == 1 || $row['is_delete'] == 1) {
                $btn =  $btnview . $btnpdf;
            } else {
                $btn =  $btnedit . $btnview . $btnpdf;
            }

            if ($getMax['max_no'] == $row['return_no']) {
                if ($row['is_cancle'] != 1) {
                    $btn .= $btndelete;
                }
            } else {
                if ($row['is_cancle'] == 0) {
                    $btn .= $btn_cancle;
                }
            }

            $date = user_date($row['return_date']);
            $DataRow[] = $row['id'];
            $DataRow[] = $row['supp_inv'];

            $DataRow[] = $date;
            $DataRow[] = $row['account_name'];
            $DataRow[] = number_format($row['net_amount'], 2);
            $DataRow[] = $row['other'];
            $DataRow[] = ($row['is_cancle'] == 1) ? '<p class="tx-danger">Cancled</p>' : '<p class="tx-success">Approved</p>';
            $DataRow[] = $btn;

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function UpdateData($post)
    {
        $result = array();
        // echo '<pre>';print_r($post);exit;
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'challan') {
                $gnmodel = new GeneralModel();
                $sales_invoice = $gnmodel->get_array_table('sales_invoice', array('challan_no' => $post['pk']), 'is_delete,is_cancle');

                foreach ($sales_invoice as $row) {
                    if (@$row['is_delete'] == 0 && @$row['is_cancle'] == '0') {
                        $is_delete = 0;
                    }
                }
                if (isset($is_delete) && $is_delete == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Delete Invoice');
                } else {
                    $result = $gnmodel->update_data_table('sales_challan', array('id' => $post['pk']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
                }
            }

            if ($post['method'] == 'c_note') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('credit_note', array('id' => $post['pk']), array('is_delete' => '1'));
            }

            if ($post['method'] == 'salesinvoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_invoice', array('id' => $post['pk']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
                $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $post['pk'],'type' => "invoice"), array('is_delete' => '1','is_update' => '1'));
            }

            if ($post['method'] == 'salesreturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_return', array('id' => $post['pk']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
                $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $post['pk'],'type' => "return"), array('is_delete' => '1','is_update' => '1'));
        
            }

            if ($post['method'] == 'ac_challan') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_ACchallan', array('id' => $post['pk']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
            }

            if ($post['method'] == 'ac_invoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_ACinvoice', array('id' => $post['pk']), array('is_delete' => '1', 'update_at' => date('Y-m-d H:i:s'), 'update_by' => session('uid')));
            }
        }

        // if ($post['type'] == 'Status') {

        //     if ($post['method'] == 'item_invoice') {
        //         $gnmodel = new GeneralModel();
        //         $result = $gnmodel->update_data_table('sale_iteminvoice', array('id' => $post['pk']), array('status' => $post['val']));
        //     }
        //     if ($post['method'] == 'item_challan') {
        //         $gnmodel = new GeneralModel();
        //         $result = $gnmodel->update_data_table('sales_itemchallan', array('id' => $post['pk']), array('status' => $post['val']));
        //     }

        // }

        if ($post['type'] == 'Cancle') {
            if ($post['method'] == 'challan') {
                $gnmodel = new GeneralModel();
                $sales_invoice = $gnmodel->get_array_table('sales_invoice', array('challan_no' => $post['pk']), 'is_cancle,is_delete');

                foreach ($sales_invoice as $row) {
                    if (@$row['is_cancle'] == 0 && @$row['is_delete'] == 0) {
                        $is_cancle = 0;
                    }
                }

                if (isset($is_cancle) && $is_cancle == 0) {
                    $result = array('st' => 'fail', 'msg' => 'Please First Cancle Invoice');
                } else {
                    $result = $gnmodel->update_data_table('sales_challan', array('id' => $post['pk']), array('is_cancle' => 1));
                }
            }

            if ($post['method'] == 'salesinvoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_invoice', array('id' => $post['pk']), array('is_cancle' => 1));
                $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $post['pk'],'type' => "invoice"), array('is_cancle' => '1','is_update' => '1'));
            }

            if ($post['method'] == 'salesreturn') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_return', array('id' => $post['pk']), array('is_cancle' => 1));
                $result1 = $gnmodel->update_data_table('jv_management', array('invoice_no' => $post['pk'],'type' => "return"), array('is_cancle' => '1','is_update' => '1'));
            }


            if ($post['method'] == 'ac_invoice') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('sales_ACinvoice', array('id' => $post['pk']), array('is_cancle' => 1));
            }
        }
        return $result;
    }

    public function get_master_data($method, $id)
    {

        $gnmodel = new GeneralModel;


        if ($method == 'salesinvoice') {
            $result['salesinvoice'] = $gnmodel->get_data_table('sales_invoice', array('id' => $id));
        }
        if ($method == 'salesreturn') {
            $result['s_return'] = $gnmodel->get_data_table('sales_return', array('id' => $id));
        }

        return $result;
    }
    // update trupti 24-11-2022
    public function get_Saleinvoice_databyid($post)
    {

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_invoice si');
        $builder->select('si.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = si.account');
        $builder->where(array('si.account' => $post['id']));

        if (@$post['searchTerm'] != '') {
            $builder->where(array('si.invoice_no' => @$post['searchTerm']));
        }

        $builder->orderBy('si.id', 'desc');
        $query = $builder->get();
        $sale_invoice = $query->getResultArray();

        $gmodel = new GeneralModel();


        foreach ($sale_invoice as $row) {

            $getbroker = $gmodel->get_data_table('account', array('id' => $row['broker']), 'name,brokrage');
            $getdelivery = $gmodel->get_data_table('account', array('id' => $row['delivery_code']), 'name');

            $gettransport = $gmodel->get_data_table('transport', array('id' => @$row['transport']), 'name');
            $getvehicle = $gmodel->get_data_table('vehicle', array('id' => @$row['vehicle_modeno']), 'name');

            if (empty($getbroker)) {
                $getbroker['name'] = '';
            }
            if ($row['lr_date'] == '0000-00-00') {
                $row['lr_date'] = '';
            } else {
                $dt = date_create($row['lr_date']);
                $row['lr_date'] = date_format($dt, 'd-m-Y');
            }
            if (empty($gettransport)) {
                $gettransport['code'] = '';
            }
            if (empty($getcity)) {
                $getcity['name'] = '';
            }
            if (empty($getvehicle)) {
                $getvehicle['name'] = '';
            }
            $row['broker_name'] = @$getbroker['name'];
            $row['fix_brokrage'] = @$getbroker['brokrage'];
            $row['delivery_name'] = @$getdelivery['name'];

            $row['transport_name'] = @$gettransport['name'];
            $row['city_name'] = @$getcity['name'];
            $row['vehicle_name'] = @$getvehicle['name'];

            $item_builder = $db->table('sales_item st');
            $item_builder->select('st.*,i.id,i.type,i.item_mode,i.name,i.sku,i.purchase_cost,i.hsn,i.code,i.uom as item_uom ,st.uom as uom');
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

            $builder = $db->table('sales_return si');
            $builder->select('si.*,ac.name as account_name');
            $builder->join('account ac', 'ac.id = si.account');
            $builder->where('si.is_delete', '0');
            $builder->where('si.invoice', $row['id']);
            $query = $builder->get();
            $return = $query->getResultArray();

            $total_qty = 0;

            foreach ($return as $row1) {
                $item_builder = $db->table('sales_item st');
                $item_builder->select('SUM(qty) as qty');
                $item_builder->join('item i', 'i.id = st.item_id');
                $item_builder->where(array('st.parent_id' => $row1['id'], 'st.type' => 'return', 'st.is_delete' => 0));
                $query = $item_builder->get();
                $item2 = $query->getRowArray();

                $total_qty += $item2['qty'];
            }

            $dt = date_create($row['invoice_date']);
            $date = date_format($dt, 'd-m-Y');

            $text = $row['invoice_no'] . ' (' . $row['account_name'] . ') /' . $date;
            // print_r($total_qty);
            // print_r($total_challan_qty);exit;
            if ($total_qty < $total_challan_qty) {
                $result[] = array("text" => $text, "id" => $row['id'], 'return' => $row, 'item' => $item);
                // print_r($result);exit;
            } else {

                $result[] = array();
            }

            unset($item);
        }


        // foreach($sale_invoice as $row){

        //     $text = '('.$row['invoice_no'] .') -'.user_date($row['invoice_date']).'-'.$row['account_name'] .' - ₹'.$row['net_amount'];
        //     $data[] = array(
        //         'id'=>$row['id'],
        //         'text'=>$text,
        //         'table'=>'sales_invoice'
        //     );

        // }

        return $result;
    }

    public function get_Salegeneral_databyid($post)
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_ACinvoice sa');
        $builder->select('sa.*,ac.name as account_name');
        $builder->join('account ac', 'ac.id = sa.party_account');
        $builder->where('sa.party_account', $post['id']);
        $builder->where(array('sa.party_account' => $post['id']));
        $builder->where(array('sa.v_type' => 'general'));
        if (@$post['searchTerm'] != '') {
            $builder->where(array('sa.id' => @$post['searchTerm']));
        }
        $builder->orderBy('sa.id', 'desc');
        $builder->limit(5);
        $query = $builder->get();
        $sale_general = $query->getResultArray();

        $gmodel = new GeneralModel();

        $data = array();

        foreach ($sale_general as $row) {
            // $whr = array('invoice' =>$row['id'] , 'invoice_tb'=>'sales_invoice','is_delete' => '0' );
            $total_return = $gmodel->get_data_table('sales_ACinvoice', array('return_sale' => $row['id'], 'v_type' => 'return'), 'SUM(net_amount) as total');

            $dt = date_create($row['invoice_date']);
            $date = date_format($dt, 'd/m/Y');

            $text = '(' . $row['invoice_no'] . ') - ' . $date . '-' . $row['account_name'] . ' - ₹' . ($row['net_amount'] - $total_return['total']);
            $data[] = array(
                'id' => $row['id'],
                'text' => $text,
                'table' => 'sales_ACinvoice'
            );
        }
        return $data;
    }
    public function update_item_taxability()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_item');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $item_igst_amt = 0.00;
            $item_cgst_amt = 0.00;
            $item_sgst_amt = 0.00;
            if ($row['item_id'] != 0) {
                $item_data = $gmodel->get_data_table('item', array('id' => $row['item_id']), 'id,taxability');

                $item_taxability = $item_data['taxability'];
                if ($row['item_disc'] > 0) {
                    $sub = $row['qty'] * $row['rate'];
                    $disc_amt = $sub * $row['item_disc'] / 100;

                    $final_sub = $sub - $disc_amt;

                    $item_igst_amt = $final_sub * $row['igst'] / 100;
                    $item_cgst_amt = $item_igst_amt / 2;
                    $item_sgst_amt = $item_igst_amt / 2;
                } else {
                    $sub = $row['qty'] * $row['rate'];
                    $item_igst_amt = $sub * $row['igst'] / 100;
                    $item_cgst_amt = $item_igst_amt / 2;
                    $item_sgst_amt = $item_igst_amt / 2;
                }
                $result = $gmodel->update_data_table('sales_item', array('id' => $row['id']), array('igst_amt' => $item_igst_amt, 'cgst_amt' => $item_cgst_amt, 'sgst_amt' => $item_sgst_amt, 'taxability' => $item_taxability));
            }
        }
        return $result;

        //exit;
    }
    public function update_sales_challan_taxability()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_challan');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('sales_item');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => "challan"));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = '';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = '';
                }
            }
            $result = $gmodel->update_data_table('sales_challan', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_sales_invoice_taxability()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_invoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('sales_item');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => "invoice"));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = '';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = '';
                }
            }
            $result = $gmodel->update_data_table('sales_invoice', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_sales_return_taxability()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_return');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('sales_item');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => "return"));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = '';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = '';
                }
            }
            $result = $gmodel->update_data_table('sales_return', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_account_taxability()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_ACparticu');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $item_igst_amt = 0.00;
            $item_cgst_amt = 0.00;
            $item_sgst_amt = 0.00;
            // if($row['item_id'] != 0)
            // {
            $account_data = $gmodel->get_data_table('account', array('id' => $row['account']), 'id,taxability');

            $account_taxability = $account_data['taxability'];


            $sub = $row['amount'];
            $item_igst_amt = $sub * $row['igst'] / 100;
            $item_cgst_amt = $item_igst_amt / 2;
            $item_sgst_amt = $item_igst_amt / 2;

            $result = $gmodel->update_data_table('sales_ACparticu', array('id' => $row['id']), array('igst_amt' => $item_igst_amt, 'cgst_amt' => $item_cgst_amt, 'sgst_amt' => $item_sgst_amt, 'taxability' => $account_taxability));
            // }
        }
        return $result;

        //exit;
    }
    public function update_acinvoice_gst()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_ACinvoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('account');
            $builder->select('gst');
            $builder->where(array("is_delete" => 0, 'id' => $row['party_account']));
            $result1 = $builder->get();
            $result_array1 = $result1->getRowArray();
            //echo '<pre>';print_r($result_array1);
            $gst = $result_array1['gst'];
            $result = $gmodel->update_data_table('sales_ACinvoice', array('id' => $row['id']), array('gst' => $gst));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_acinvoice_taxability()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('sales_ACinvoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('sales_ACparticu');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id']));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = '';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = '';
                }
            }
            $result = $gmodel->update_data_table('sales_ACinvoice', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        //exit;
        return $result;
        // exit;

    }
}
