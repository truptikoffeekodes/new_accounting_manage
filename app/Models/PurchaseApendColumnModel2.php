<?php

namespace App\Models;

use App\Models\GeneralModel;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PurchaseApendColumnModel extends Model
{
    // item taxability
    public function update_item_taxability()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_item');
        $builder->select('*');
        $builder->where(array("is_delete" => 0,'is_update_taxability'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();

        $result1 = array();
        foreach ($result_array as $row) {
            $item_igst_amt = 0.00;
            $item_cgst_amt = 0.00;
            $item_sgst_amt = 0.00;
            if ($row['item_id'] != 0) {
                $item_data = $gmodel->get_data_table('item', array('id' => $row['item_id']), 'id,taxability');

                $item_taxability = @$item_data['taxability'];
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
                $result1 = $gmodel->update_data_table('purchase_item', array('id' => $row['id']), array('igst_amt' => $item_igst_amt, 'cgst_amt' => $item_cgst_amt, 'sgst_amt' => $item_sgst_amt, 'taxability' => $item_taxability));
                if ($result1) {
                    $result2 = $gmodel->update_data_table('purchase_item', array('id' => $row['id']), array('is_update_taxability' => 1));
                }
            }
        }
            if (isset($result2)) {
                $msg = array('st' => 'succsess', 'msg' => 'Updated');
            } else {
                $msg = array('st' => 'fail', 'msg' => 'out');
            }

        return $msg;

        //exit;
    }
    // inv_taxability
    public function update_purchase_challan_taxability()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('purchase_item');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => "challan"));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst_no'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = 'N/A';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = 'N/A';
                }
            }
            $result = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_purchase_invoice_taxability()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('purchase_item');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => "invoice"));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst_no'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = 'N/A';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = 'N/A';
                }
            }
            $result = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_purchase_return_taxability()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('purchase_item');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => "return"));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst_no'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = 'N/A';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = 'N/A';
                }
            }
            $result = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_account_taxability()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_particu');
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

            $result = $gmodel->update_data_table('purchase_particu', array('id' => $row['id']), array('igst_amt' => $item_igst_amt, 'cgst_amt' => $item_cgst_amt, 'sgst_amt' => $item_sgst_amt, 'taxability' => $account_taxability));
            // }
        }
        return $result;

        //exit;
    }
    public function update_acinvoice_gst()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_general');
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
            $result = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('gst_no' => $gst));


            //echo '<pre>';print_r($data);
        }
        return $result;
        // exit;

    }
    public function update_acinvoice_taxability()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $builder = $db->table('purchase_particu');
            $builder->select('taxability');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id']));
            $result1 = $builder->get();
            $result_array1 = $result1->getResultArray();
            //echo '<pre>';print_r($result_array1);
            foreach ($result_array1 as $row1) {
                $data[] = $row1['taxability'];
            }
            if ($row['gst_no'] != '') {
                if (in_array('Taxable', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (!in_array('Taxable', $data) && in_array('Nill', $data)) {

                    $inv_taxability = 'Nill';
                } else if (!in_array('Taxable', $data) && in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else {
                    $inv_taxability = 'N/A';
                }
            } else {
                if (in_array('Exempt', $data)) {

                    $inv_taxability = 'Exempt';
                } else if (in_array('Taxable', $data) && !in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Taxable';
                } else if (in_array('Taxable', $data) && in_array('Nill', $data) && !in_array('Exempt', $data)) {

                    $inv_taxability = 'Nill';
                } else {
                    $inv_taxability = 'N/A';
                }
            }
            $result = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('inv_taxability' => $inv_taxability));


            //echo '<pre>';print_r($data);
        }
        //exit;
        return $result;
        // exit;

    }
    // slaes gst acc
    public function update_purchase_challan_gst_acc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_gst' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();

        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $taxes_array = json_decode(@$row['taxes']);
            if (in_array("igst", $taxes_array)) {
                $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
                $result = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('igst_acc' => $igst_acc_id['id']));
            } else {
                $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
                $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
                $result = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('cgst_acc' => $cgst_acc_id['id']));
                $result1 = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('sgst_acc' => $sgst_acc_id['id']));
            }

            $gl_id = $gmodel->get_data_table('account', array('id' => $row['account']), 'gl_group');
            $result2 = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('gl_group' => $gl_id['gl_group']));

            if ($result and $result2) {
                $result3 = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('is_update_gst' => 1));
            }
        }
        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }

        return $msg;
        //exit;

    }
    public function update_purchase_invoice_gst_acc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_gst' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();

        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $taxes_array = json_decode(@$row['taxes']);
            if (in_array("igst", $taxes_array)) {
                $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
                $result = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('igst_acc' => $igst_acc_id['id']));
            } else {
                $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
                $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
                $result = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('cgst_acc' => $cgst_acc_id['id']));
                $result1 = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('sgst_acc' => $sgst_acc_id['id']));
            }

            $gl_id = $gmodel->get_data_table('account', array('id' => $row['account']), 'gl_group');
            $result2 = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('gl_group' => $gl_id['gl_group']));

            if ($result and $result2) {
                $result3 = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('is_update_gst' => 1));
            }
        }
        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function update_purchase_return_gst_acc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_gst' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();

        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $taxes_array = json_decode(@$row['taxes']);
            if (in_array("igst", $taxes_array)) {
                $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
                $result = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('igst_acc' => $igst_acc_id['id']));
            } else {
                $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
                $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
                $result = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('cgst_acc' => $cgst_acc_id['id']));
                $result1 = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('sgst_acc' => $sgst_acc_id['id']));
            }

            $gl_id = $gmodel->get_data_table('account', array('id' => $row['account']), 'gl_group');
            $result2 = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('gl_group' => $gl_id['gl_group']));

            if ($result and $result2) {
                $result3 = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('is_update_gst' => 1));
            }
        }
        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function update_purchase_general_gst_acc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_gst' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();

        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $data = array();
            $taxes_array = json_decode(@$row['taxes']);
            if (in_array("igst", $taxes_array)) {
                $igst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Igst'), 'id');
                $result = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('igst_acc' => $igst_acc_id['id']));
            } else {
                $cgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Cgst'), 'id');
                $sgst_acc_id = $gmodel->get_data_table('account', array('name' => 'Output Sgst'), 'id');
                $result = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('cgst_acc' => $cgst_acc_id['id']));
                $result1 = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('sgst_acc' => $sgst_acc_id['id']));
            }

            $gl_id = $gmodel->get_data_table('account', array('id' => $row['party_account']), 'gl_group');
            $result2 = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('gl_group' => $gl_id['gl_group']));

            if ($result and $result2) {
                $result3 = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('is_update_gst' => 1));
            }
        }
        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    // item disc
    public function update_purchase_challan_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $builder->limit(100);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => 'challan', 'is_update_column' => 0));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            //print_r($result_array1);exit;
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;
            if (!empty($result_array1)) {
                for ($i = 0; $i < count($result_array1); $i++) {
                    $disc_amt = 0;
                    if ($result_array1[$i]['is_expence'] == 0) {
                        // $disc_amt = 0;
                        // if ($result_array1[$i]['item_disc'] != 0) {
                        //     $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                        //     $disc_amt = $sub * $result_array1[$i]['item_disc'] / 100;
                        // }
                        $final_sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                    } else {
                        $final_sub = $result_array1[$i]['rate'];
                    }
                    $total += $final_sub;
                }
                if ($row['discount'] > 0) {
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                    }
                } else {
                    //if($)
                    for ($i = 0; $i < count($result_array1); $i++) {
                        $disc_amt = 0;
                        if ($result_array1[$i]['is_expence'] == 0) {
                            $disc_amt = 0;
                            if ($result_array1[$i]['item_disc'] != 0) {
                                $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                                $discount = $sub * $result_array1[$i]['item_disc'] / 100;
                                //$new_disc =0;
                                $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                            }
                        } else {
                            $discount = 0;
                            $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));

                            //$new_disc =0;
                        }
                        $new_disc += $discount;
                    }
                }
                //print_r($new_disc);exit;
                $discounted_total = $total - $new_disc;
                if ($row['amty_type'] == '%') {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $total_add_amt = $discounted_total *  $row['amty'] / 100;
                    $add_amt = $total_add_amt / $count;
                } else {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $add_amt = $row['amty'] / $count;
                }
                for ($i = 0; $i < count($result_array1); $i++) {

                    $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    if ($result and $result2) {
                        $result3 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('is_update_column' => 1));
                    }
                }
            }
        }
        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function update_purchase_invoice_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'id' => 2606));
        // $builder->limit(100);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        //echo '<pre>';print_r($result_array);exit;
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => 'invoice', 'is_update_column' => 0));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            //echo '<pre>';print_r($result_array1);exit;
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;
            $result3 = array();
            if (!empty($result_array1)) {
                for ($i = 0; $i < count($result_array1); $i++) {
                    $disc_amt = 0;
                    if ($result_array1[$i]['is_expence'] == 0) {
                        // $disc_amt = 0;
                        // if ($result_array1[$i]['item_disc'] != 0) {
                        //     $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                        //     $disc_amt = $sub * $result_array1[$i]['item_disc'] / 100;
                        // }
                        $final_sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                    } else {
                        $final_sub = $result_array1[$i]['rate'];
                    }
                    $total += $final_sub;
                }
                if ($row['discount'] > 0) {
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                    }
                } else {
                    //if($)
                    for ($i = 0; $i < count($result_array1); $i++) {
                        $disc_amt = 0;
                        if ($result_array1[$i]['is_expence'] == 0) {
                            $disc_amt = 0;
                            if ($result_array1[$i]['item_disc'] != 0) {
                                $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                                $discount = $sub * $result_array1[$i]['item_disc'] / 100;
                                //$new_disc =0;
                                $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                            }
                        } else {
                            $discount = 0;
                            $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));

                            //$new_disc =0;
                        }
                        $new_disc += $discount;
                    }
                }
                //print_r($new_disc);exit;
                $discounted_total = $total - $new_disc;
                if ($row['amty_type'] == '%') {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $total_add_amt = $discounted_total *  $row['amty'] / 100;
                    $add_amt = $total_add_amt / $count;
                } else {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $add_amt = $row['amty'] / $count;
                }

                for ($i = 0; $i < count($result_array1); $i++) {

                    $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    if ($result and $result2) {
                        $result3 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('is_update_column' => 1));
                    }
                }
            }
            // for ($i = 0; $i < count($result_array1); $i++) {


            //}

            //     echo '<pre>';print_r("id".$row['id']);
            //     echo '<pre>';print_r("total".$total);
            //     echo '<pre>';print_r("disc".$discount);
            // echo '<pre>';print_r("add".$add_amt);
        }

        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function update_purchase_return_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $builder->limit(100);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => 'return', 'is_update_column' => 0));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            //print_r($result_array1);exit;
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;
            if (!empty($result_array1)) {
                for ($i = 0; $i < count($result_array1); $i++) {
                    $disc_amt = 0;
                    if ($result_array1[$i]['is_expence'] == 0) {
                        // $disc_amt = 0;
                        // if ($result_array1[$i]['item_disc'] != 0) {
                        //     $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                        //     $disc_amt = $sub * $result_array1[$i]['item_disc'] / 100;
                        // }
                        $final_sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                    } else {
                        $final_sub = $result_array1[$i]['rate'];
                    }
                    $total += $final_sub;
                }
                if ($row['discount'] > 0) {
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                    }
                } else {
                    //if($)
                    for ($i = 0; $i < count($result_array1); $i++) {
                        $disc_amt = 0;
                        if ($result_array1[$i]['is_expence'] == 0) {
                            $disc_amt = 0;
                            if ($result_array1[$i]['item_disc'] != 0) {
                                $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                                $discount = $sub * $result_array1[$i]['item_disc'] / 100;
                                //$new_disc =0;
                                $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                            }
                        } else {
                            $discount = 0;
                            $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));

                            //$new_disc =0;
                        }
                        $new_disc += $discount;
                    }
                }
                //print_r($new_disc);exit;
                $discounted_total = $total - $new_disc;
                if ($row['amty_type'] == '%') {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $total_add_amt = $discounted_total *  $row['amty'] / 100;
                    $add_amt = $total_add_amt / $count;
                } else {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $add_amt = $row['amty'] / $count;
                }
                for ($i = 0; $i < count($result_array1); $i++) {

                    $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    if ($result and $result2) {
                        $result3 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('is_update_column' => 1));
                    }
                }
            }
            // for ($i = 0; $i < count($result_array1); $i++) {


            //}

            //     echo '<pre>';print_r("id".$row['id']);
            //     echo '<pre>';print_r("total".$total);
            //     echo '<pre>';print_r("disc".$discount);
            // echo '<pre>';print_r("add".$add_amt);
        }

        //exit;

        //print_r($result3);

        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function update_purchase_general_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array("is_delete" => 0));
        $builder->limit(100);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_particu');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id']));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            //print_r($result_array1);exit;
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;
            if (!empty($result_array1)) {
                for ($i = 0; $i < count($result_array1); $i++) {
                    $disc_amt = 0;

                    $final_sub = $result_array1[$i]['amount'];

                    $total += $final_sub;
                }
                if ($row['discount'] > 0) {
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result = $gmodel->update_data_table('purchase_particu', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                    }
                } else {
                    $discount = 0;
                    $new_disc = 0;
                }

                //print_r($new_disc);exit;
                $discounted_total = $total - $new_disc;
                if ($row['amty_type'] == '%') {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $total_add_amt = $discounted_total *  $row['amty'] / 100;
                    $add_amt = $total_add_amt / $count;
                } else {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $add_amt = $row['amty'] / $count;
                }
                for ($i = 0; $i < count($result_array1); $i++) {

                    $result2 = $gmodel->update_data_table('purchase_particu', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    if ($result and $result2) {
                        $result3 = $gmodel->update_data_table('purchase_particu', array('id' => $result_array1[$i]['id']), array('is_update_column' => 1));
                    }
                }
            }
            // for ($i = 0; $i < count($result_array1); $i++) {


            //}

            //     echo '<pre>';print_r("id".$row['id']);
            //     echo '<pre>';print_r("total".$total);
            //     echo '<pre>';print_r("disc".$discount);
            // echo '<pre>';print_r("add".$add_amt);
        }

        //exit;

        //print_r($result3);

        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    // discount and added_amt
    public function new_update_purchase_challan_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_discount' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => 'challan'));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;

            if (!empty($result_array1)) {
                if ($row['discount'] > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        if ($result_array1[$i]['is_expence'] == 0) {
                            $final_sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                        } else {
                            $final_sub = $result_array1[$i]['rate'];
                        }
                        $total += $final_sub;
                    }
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    $discounted_total = $total - $new_disc;
                    if ($row['amty_type'] == '%') {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $total_add_amt = $discounted_total *  $row['amty'] / 100;
                        $add_amt = $total_add_amt / $count;
                    } else {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $add_amt = $row['amty'] / $count;
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                        $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    }
                } else {
                    for ($i = 0; $i < $count; $i++) {

                        if ($result_array1[$i]['is_expence'] == 0) {
                            $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                            $discount = $sub * $result_array1[$i]['item_disc'] / 100;
                            $final_sub = $sub - $discount;
                            $result = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                        } else {
                            $final_sub = $result_array1[$i]['rate'];
                        }
                        $total += $final_sub;
                    }
                    $discounted_total = $total;
                    if ($row['amty_type'] == '%') {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $total_add_amt = $discounted_total *  $row['amty'] / 100;
                        $add_amt = $total_add_amt / $count;
                    } else {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $add_amt = $row['amty'] / $count;
                    }
                    for ($i = 0; $i < $count; $i++) {
                        $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    }
                }
            }
            if ($result and $result2) {
                $result3 = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('is_update_discount' => 1));
            }
        }
        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function new_update_purchase_invoice_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_discount' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => 'invoice'));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;


            $result1 = array();
            $result2 = array();
            if (!empty($result_array1)) {
                if ($row['discount'] > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        if ($result_array1[$i]['is_expence'] == 0) {
                            $final_sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                        } else {
                            $final_sub = $result_array1[$i]['rate'];
                        }
                        $total += $final_sub;
                    }
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    $discounted_total = $total - $new_disc;
                    if ($row['amty_type'] == '%') {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $total_add_amt = $discounted_total *  $row['amty'] / 100;
                        $add_amt = $total_add_amt / $count;
                    } else {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $add_amt = $row['amty'] / $count;
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result1 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                        $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    }
                } else {
                    for ($i = 0; $i < $count; $i++) {

                        if ($result_array1[$i]['is_expence'] == 0) {
                            $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                            $discount = $sub * $result_array1[$i]['item_disc'] / 100;
                            $final_sub = $sub - $discount;
                            $result1 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                        } else {
                            $final_sub = $result_array1[$i]['rate'];
                        }
                        $total += $final_sub;
                    }
                    $discounted_total = $total;
                    if ($row['amty_type'] == '%') {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $total_add_amt = $discounted_total *  $row['amty'] / 100;
                        $add_amt = $total_add_amt / $count;
                    } else {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $add_amt = $row['amty'] / $count;
                    }
                    for ($i = 0; $i < $count; $i++) {
                        $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    }
                }
            }
            if ($result1 and $result2) {
                $result3 = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('is_update_discount' => 1));
            }
        }

        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function new_update_purchase_return_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_discount' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id'], 'type' => 'return'));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;


            $result1 = array();
            $result2 = array();
            if (!empty($result_array1)) {
                if ($row['discount'] > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        if ($result_array1[$i]['is_expence'] == 0) {
                            $final_sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                        } else {
                            $final_sub = $result_array1[$i]['rate'];
                        }
                        $total += $final_sub;
                    }
                    if ($row['disc_type'] == '%') {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $total_discount_amt = $total * $row['discount'] / 100;
                            $new_disc = $total_discount_amt;
                            $discount = $total_discount_amt / $count;
                        }
                    } else {
                        if ($row['discount'] == '') {
                            $discount = 0;
                            $new_disc = 0;
                        } else {
                            $discount = $row['discount'] / $count;
                            $new_disc = $row['discount'];
                        }
                    }
                    $discounted_total = $total - $new_disc;
                    if ($row['amty_type'] == '%') {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $total_add_amt = $discounted_total *  $row['amty'] / 100;
                        $add_amt = $total_add_amt / $count;
                    } else {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $add_amt = $row['amty'] / $count;
                    }
                    for ($i = 0; $i < count($result_array1); $i++) {

                        $result1 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                        $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    }
                } else {
                    for ($i = 0; $i < $count; $i++) {

                        if ($result_array1[$i]['is_expence'] == 0) {
                            $sub = $result_array1[$i]['qty'] * $result_array1[$i]['rate'];
                            $discount = $sub * $result_array1[$i]['item_disc'] / 100;
                            $final_sub = $sub - $discount;
                            $result1 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                        } else {
                            $final_sub = $result_array1[$i]['rate'];
                        }
                        $total += $final_sub;
                    }
                    $discounted_total = $total;
                    if ($row['amty_type'] == '%') {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $total_add_amt = $discounted_total *  $row['amty'] / 100;
                        $add_amt = $total_add_amt / $count;
                    } else {
                        if ($row['amty'] == '')
                            $add_amt = 0;
                        else
                            $add_amt = $row['amty'] / $count;
                    }
                    for ($i = 0; $i < $count; $i++) {
                        $result2 = $gmodel->update_data_table('purchase_item', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                    }
                }
            }
            if ($result1 and $result2) {
                $result3 = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('is_update_discount' => 1));
            }
        }

        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    public function new_update_purchase_general_item_disc()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_discount' => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $discount = 0;
        $total = 0;
        $gmodel = new GeneralModel();


        foreach ($result_array as $row) {
            $builder = $db->table('purchase_particu');
            $builder->select('*');
            $builder->where(array("is_delete" => 0, 'parent_id' => $row['id']));
            $result = $builder->get();
            $result_array1 = $result->getResultArray();
            //print_r($result_array1);exit;
            $count = count($result_array1);
            $discount = 0;
            $new_disc = 0;
            $total = 0;
            $result1 = array();
            $result2 = array();
            if ($row['discount'] > 0) {
                for ($i = 0; $i < $count; $i++) {

                    $final_sub = $result_array1[$i]['amount'];
                    $total += $final_sub;
                }
                if ($row['disc_type'] == '%') {
                    if ($row['discount'] == '') {
                        $discount = 0;
                        $new_disc = 0;
                    } else {
                        $total_discount_amt = $total * $row['discount'] / 100;
                        $new_disc = $total_discount_amt;
                        $discount = $total_discount_amt / $count;
                    }
                } else {
                    if ($row['discount'] == '') {
                        $discount = 0;
                        $new_disc = 0;
                    } else {
                        $discount = $row['discount'] / $count;
                        $new_disc = $row['discount'];
                    }
                }
                $discounted_total = $total - $new_disc;
                if ($row['amty_type'] == '%') {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $total_add_amt = $discounted_total *  $row['amty'] / 100;
                    $add_amt = $total_add_amt / $count;
                } else {
                    if ($row['amty'] == '')
                        $add_amt = 0;
                    else
                        $add_amt = $row['amty'] / $count;
                }
                for ($i = 0; $i < count($result_array1); $i++) {

                    $result1 = $gmodel->update_data_table('purchase_particu', array('id' => $result_array1[$i]['id']), array('discount' => $discount));
                    $result2 = $gmodel->update_data_table('purchase_particu', array('id' => $result_array1[$i]['id']), array('added_amt' => $add_amt));
                }
                if ($result1 and $result2) {
                    $result3 = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('is_update_discount' => 1));
                }
            }
        }

        if (isset($result3)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
        //exit;

    }
    // newwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
    // sales gl_group update
    public function update_glgroup_purchase_challan()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, "is_update_glgroup" => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach ($result_array as $row) {
            $account_data = $gmodel->get_data_table('account', array('id' => $row['account']), 'gl_group');
            $result = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('gl_group' => $account_data['gl_group']));
            if (isset($result)) {
                $result2 = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('is_update_glgroup' => 1));
            }
        }
        if (isset($result2)) {

            $msg = array("st" => "succsess", "msg" => "succsess");
        } else {
            $msg = array("st" => "fail", "msg" => "out");
        }
        return $msg;

        //exit;
    }
    public function update_glgroup_purchase_invoice()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, "is_update_glgroup" => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach ($result_array as $row) {
            $account_data = $gmodel->get_data_table('account', array('id' => $row['account']), 'gl_group');
            $result = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('gl_group' => $account_data['gl_group']));
            if (isset($result)) {
                $result2 = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('is_update_glgroup' => 1));
            }
        }
        if (isset($result2)) {

            $msg = array("st" => "succsess", "msg" => "succsess");
        } else {
            $msg = array("st" => "fail", "msg" => "out");
        }
        return $msg;

        //exit;
    }
    public function update_glgroup_purchase_return()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, "is_update_glgroup" => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach ($result_array as $row) {
            $account_data = $gmodel->get_data_table('account', array('id' => $row['account']), 'gl_group');
            $result = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('gl_group' => $account_data['gl_group']));
            if (isset($result)) {
                $result2 = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('is_update_glgroup' => 1));
            }
        }
        if (isset($result2)) {

            $msg = array("st" => "succsess", "msg" => "succsess");
        } else {
            $msg = array("st" => "fail", "msg" => "out");
        }
        return $msg;

        //exit;
    }
    public function update_glgroup_purchase_general()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_general');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, "is_update_glgroup" => 0));
        $builder->limit(500);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach ($result_array as $row) {
            $account_data = $gmodel->get_data_table('account', array('id' => $row['party_account']), 'gl_group');
            $result = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('gl_group' => $account_data['gl_group']));
            if (isset($result)) {
                $result2 = $gmodel->update_data_table('purchase_general', array('id' => $row['id']), array('is_update_glgroup' => 1));
            }
        }
        if (isset($result2)) {

            $msg = array("st" => "succsess", "msg" => "succsess");
        } else {
            $msg = array("st" => "fail", "msg" => "out");
        }
        return $msg;

        //exit;
    }
    // update sub_total
    public function update_purchase_subtotal()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_item');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_subtotal' => 0));
        $builder->limit(3000);
        $result = $builder->get();
        $result_array1 = $result->getResultArray();
        $gmodel = new GeneralModel();
        $result1 = array();
        foreach ($result_array1 as $row) {
            if($row['is_expence'] == 0)
            {
                $total_amt = $row['qty'] * $row['rate'];
                $sub_total = $total_amt - $row['discount'];
            }
            else
            {
                $sub_total = $row['rate'] - $row['discount'];
            }
            $result1 = $gmodel->update_data_table('purchase_item', array('id' => $row['id']), array('sub_total' => $sub_total));
            if ($result1) {
                $result2 = $gmodel->update_data_table('purchase_item', array('id' => $row['id']), array('is_update_subtotal' => 1));
            }
        }
       

        if (isset($result2)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
    }
    public function update_purchase_general_subtotal()
    {
        $db = $this->db;
        // $db->setDatabase(session('DataSource'));
        $db->setDatabase('KLA2022ZFDH');
        $builder = $db->table('purchase_particu');
        $builder->select('*');
        $builder->where(array("is_delete" => 0, 'is_update_subtotal' => 0));
        $builder->limit(2000);
        $result = $builder->get();
        $result_array1 = $result->getResultArray();
        $gmodel = new GeneralModel();
        $result1 = array();
        foreach ($result_array1 as $row) {
            $sub_total = $row['amount'] - $row['discount'];
            $result1 = $gmodel->update_data_table('purchase_particu', array('id' => $row['id']), array('sub_total' => $sub_total));
            
            if ($result1) {
                $result2 = $gmodel->update_data_table('purchase_particu', array('id' => $row['id']), array('is_update_subtotal' => 1));
            }
        }
       

        if (isset($result2)) {
            $msg = array('st' => 'succsess', 'msg' => 'Updated');
        } else {
            $msg = array('st' => 'fail', 'msg' => 'out');
        }
        return $msg;
    }
    public function update_divide_discount_purchase_challan()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array('is_delete'=>0,'divide_disc_up'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach($result_array as $row)
        {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete'=>0,'parent_id'=>$row['id'],'type'=>'challan'));
            $result = $builder->get();
            $result_array_item = $result->getResultArray();
            $item_total = 0;
            foreach($result_array_item as $row1)
            {
                if($row1['is_expence'] == 0)
                {
                    $sub = $row1['qty'] * $row1['rate'];
                    $item_total += $sub;
                }
            }
            $total = 0;
            if($row['discount'] > 0)
            {
                if ($row['disc_type'] == '%') {    
                    $total_discount = $item_total * $row['discount'] / 100;
                }
                else
                {
                    $total_discount = $row['discount'];
                }
                //echo '<pre>';Print_r($total_discount);exit;
                
                
                foreach($result_array_item as $row1)
                {
                    if($row1['is_expence'] == 0)
                    {
                        $item_disc = 0;
                        $item_disc_amt = 0;
                        $sub = $row1['qty'] * $row1['rate'];
                        $divide_disc_per = ($sub * 100) / $item_total;  
                        $divide_disc_amt = ($divide_disc_per / 100) * $total_discount;
                        $final_sub = $sub - $divide_disc_amt;
                        $total += $final_sub;
                        // echo '<pre>nonexp';Print_r($sub);
                        // echo '<pre>nonexp';Print_r($divide_disc_per);
                        // echo '<pre>nonexp';Print_r($final_sub);
                         
                    }
                    else
                    {
                        $item_disc = 0;
                        $item_disc_amt = 0;
                        $divide_disc_per = 0;
                        $divide_disc_amt = 0.00;
                        $sub = $row1['rate'];
                        $final_sub = $row1['rate'];
                        $total += $final_sub; 
                      //  echo '<pre>exp';Print_r($divide_disc_amt);
                       // echo '<pre>exp';Print_r($final_sub);
                    }
                    //echo '<pre>';Print_r($total);exit;
                    $item_data = array(
                        'total' => $sub,
                        'item_disc'=>  $item_disc,
                        'discount'=> $item_disc_amt,
                        'divide_disc_item_per'=> $divide_disc_per,
                        'divide_disc_item_amt'=> $divide_disc_amt,
                        'sub_total'=> $final_sub,
                    );
                    $update_total = $gmodel->update_data_table('purchase_item', array('id' => $row1['id']), $item_data);
                 }
                // exit;
               
                
            }
            else
            {
                
                foreach($result_array_item as $row1)
                {
                    if($row1['is_expence'] == 0)
                    {
                        if($row1['item_disc'] > 0)
                        {
                            $sub = $row1['qty'] * $row1['rate']; 
                            $item_disc_amt = $sub * $row1['item_disc'] / 100;
                            $divide_disc_per = 0;  
                            $divide_disc_amt = 0.00;
                            $final_sub = $sub - $item_disc_amt;  
                            $total += $final_sub;  
                        }
                        else
                        {
                            $sub = $row1['qty'] * $row1['rate']; 
                            $item_disc_amt = 0.00;
                            $divide_disc_per = 0;  
                            $divide_disc_amt = 0.00;
                            $final_sub = $sub;   
                            $total += $final_sub;
                        }
                    }
                    else
                    {
                            $item_disc_amt = 0;
                            $divide_disc_per = 0;
                            $divide_disc_amt = 0.00;
                            $sub = $row1['rate'];
                            $final_sub = $row1['rate'];
                            $total += $final_sub;
                    }
                    $item_data = array(
                        'total' => $sub,
                        'discount'=> $item_disc_amt,
                        'divide_disc_item_per'=> $divide_disc_per,
                        'divide_disc_item_amt'=> $divide_disc_amt,
                        'sub_total'=> $final_sub,
                    );
                    $update_total = $gmodel->update_data_table('purchase_item', array('id' => $row1['id']), $item_data);
                    
                   
                }
            }
            $update_total_invoice = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('divide_disc_up'=>1));
                   
        }
        if(isset($update_total))
        {
            $msg = array("sucsess","updated data");
        }
        else
        {
            $msg = array("fail","out");   
        }
        return $msg;
        
    }
    public function update_net_amount_purchase_challan()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_challan');
        $builder->select('*');
        $builder->where(array('is_delete'=>0,'net_amt_up'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach($result_array as $row)
        {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete'=>0,'parent_id'=>$row['id'],'type'=>'challan'));
            $result = $builder->get();
            $result_array_item = $result->getResultArray();
            $item_total = 0;
            foreach($result_array_item as $row1)
            {
                $cal_total = $row1['sub_total'] + $row1['added_amt'];
                $item_total += $cal_total;
            }
            if ($row['cess_type'] == '%') {
                if ($row['cess'] == '')
                    $row['cess'] = 0;
                else
                    $row['cess'] = $item_total *  $row['cess'] / 100;
            } else {
                if ($row['cess'] == '')
                    $row['cess'] = 0;
            }
    
            if (!empty($row['tds_per'])) {
                $tds_amt = $item_total *  $row['tds_per'] / 100;
            } else {
                $tds_amt = 0;
            }
            $netamount = $item_total + $row['cess'] + $row['amty'] +   $tds_amt + $row['tot_igst'];
          
            $update_total_invoice = $gmodel->update_data_table('purchase_challan', array('id' => $row['id']), array('net_amt_up'=>1,'net_amount'=> $netamount));
      
        }
        if(isset($update_total_invoice))
        {
            $msg = array("sucsess","updated data");
        }
        else
        {
            $msg = array("fail","out");   
        }
        return $msg;
    }
    public function update_divide_discount_purchase_invoice()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array('is_delete'=>0,'divide_disc_up'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach($result_array as $row)
        {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete'=>0,'parent_id'=>$row['id'],'type'=>'invoice'));
            $result = $builder->get();
            $result_array_item = $result->getResultArray();
            $item_total = 0;
            foreach($result_array_item as $row1)
            {
                if($row1['is_expence'] == 0)
                {
                    $sub = $row1['qty'] * $row1['rate'];
                    $item_total += $sub;
                }
            }
            $total = 0;
            if($row['discount'] > 0)
            {
                if ($row['disc_type'] == '%') {    
                    $total_discount = $item_total * $row['discount'] / 100;
                }
                else
                {
                    $total_discount = $row['discount'];
                }
                //echo '<pre>';Print_r($total_discount);exit;
                
                
                foreach($result_array_item as $row1)
                {
                    if($row1['is_expence'] == 0)
                    {
                        $item_disc = 0;
                        $item_disc_amt = 0;
                        $sub = $row1['qty'] * $row1['rate'];
                        $divide_disc_per = ($sub * 100) / $item_total;  
                        $divide_disc_amt = ($divide_disc_per / 100) * $total_discount;
                        $final_sub = $sub - $divide_disc_amt;
                        $total += $final_sub;
                        // echo '<pre>nonexp';Print_r($sub);
                        // echo '<pre>nonexp';Print_r($divide_disc_per);
                        // echo '<pre>nonexp';Print_r($final_sub);
                         
                    }
                    else
                    {
                        $item_disc = 0;
                        $item_disc_amt = 0;
                        $divide_disc_per = 0;
                        $divide_disc_amt = 0.00;
                        $sub = $row1['rate'];
                        $final_sub = $row1['rate'];
                        $total += $final_sub; 
                      //  echo '<pre>exp';Print_r($divide_disc_amt);
                       // echo '<pre>exp';Print_r($final_sub);
                    }
                    //echo '<pre>';Print_r($total);exit;
                    $item_data = array(
                        'total' => $sub,
                        'item_disc'=>  $item_disc,
                        'discount'=> $item_disc_amt,
                        'divide_disc_item_per'=> $divide_disc_per,
                        'divide_disc_item_amt'=> $divide_disc_amt,
                        'sub_total'=> $final_sub,
                    );
                    $update_total = $gmodel->update_data_table('purchase_item', array('id' => $row1['id']), $item_data);
                 }
                // exit;
               
                
            }
            else
            {
                
                foreach($result_array_item as $row1)
                {
                    if($row1['is_expence'] == 0)
                    {
                        if($row1['item_disc'] > 0)
                        {
                            $sub = $row1['qty'] * $row1['rate']; 
                            $item_disc_amt = $sub * $row1['item_disc'] / 100;
                            $divide_disc_per = 0;  
                            $divide_disc_amt = 0.00;
                            $final_sub = $sub - $item_disc_amt;  
                            $total += $final_sub;  
                        }
                        else
                        {
                            $sub = $row1['qty'] * $row1['rate']; 
                            $item_disc_amt = 0.00;
                            $divide_disc_per = 0;  
                            $divide_disc_amt = 0.00;
                            $final_sub = $sub;   
                            $total += $final_sub;
                        }
                    }
                    else
                    {
                            $item_disc_amt = 0;
                            $divide_disc_per = 0;
                            $divide_disc_amt = 0.00;
                            $sub = $row1['rate'];
                            $final_sub = $row1['rate'];
                            $total += $final_sub;
                    }
                    $item_data = array(
                        'total' => $sub,
                        'discount'=> $item_disc_amt,
                        'divide_disc_item_per'=> $divide_disc_per,
                        'divide_disc_item_amt'=> $divide_disc_amt,
                        'sub_total'=> $final_sub,
                    );
                    $update_total = $gmodel->update_data_table('purchase_item', array('id' => $row1['id']), $item_data);
                    
                   
                }
            }
            $update_total_invoice = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('divide_disc_up'=>1));
                   
        }
        if(isset($update_total))
        {
            $msg = array("sucsess","updated data");
        }
        else
        {
            $msg = array("fail","out");   
        }
        return $msg;
        
    }
    public function update_net_amount_purchase_invoice()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_invoice');
        $builder->select('*');
        $builder->where(array('is_delete'=>0,'net_amt_up'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach($result_array as $row)
        {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete'=>0,'parent_id'=>$row['id'],'type'=>'invoice'));
            $result = $builder->get();
            $result_array_item = $result->getResultArray();
            $item_total = 0;
            foreach($result_array_item as $row1)
            {
                $cal_total = $row1['sub_total'] + $row1['added_amt'];
                $item_total += $cal_total;
            }
            if ($row['cess_type'] == '%') {
                if ($row['cess'] == '')
                    $row['cess'] = 0;
                else
                    $row['cess'] = $item_total *  $row['cess'] / 100;
            } else {
                if ($row['cess'] == '')
                    $row['cess'] = 0;
            }
    
            if (!empty($row['tds_per'])) {
                $tds_amt = $item_total *  $row['tds_per'] / 100;
            } else {
                $tds_amt = 0;
            }
            $netamount = $item_total + $row['cess'] + $row['amty'] +   $tds_amt + $row['tot_igst'];
          
            $update_total_invoice = $gmodel->update_data_table('purchase_invoice', array('id' => $row['id']), array('net_amt_up'=>1,'net_amount'=> $netamount));
      
        }
        if(isset($update_total_invoice))
        {
            $msg = array("sucsess","updated data");
        }
        else
        {
            $msg = array("fail","out");   
        }
        return $msg;
    }
    public function update_divide_discount_purchase_return()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array('is_delete'=>0,'divide_disc_up'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach($result_array as $row)
        {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete'=>0,'parent_id'=>$row['id'],'type'=>'return'));
            $result = $builder->get();
            $result_array_item = $result->getResultArray();
            $item_total = 0;
            foreach($result_array_item as $row1)
            {
                if($row1['is_expence'] == 0)
                {
                    $sub = $row1['qty'] * $row1['rate'];
                    $item_total += $sub;
                }
            }
            $total = 0;
            if($row['discount'] > 0)
            {
                if ($row['disc_type'] == '%') {    
                    $total_discount = $item_total * $row['discount'] / 100;
                }
                else
                {
                    $total_discount = $row['discount'];
                }
                //echo '<pre>';Print_r($total_discount);exit;
                
                
                foreach($result_array_item as $row1)
                {
                    if($row1['is_expence'] == 0)
                    {
                        $item_disc = 0;
                        $item_disc_amt = 0;
                        $sub = $row1['qty'] * $row1['rate'];
                        $divide_disc_per = ($sub * 100) / $item_total;  
                        $divide_disc_amt = ($divide_disc_per / 100) * $total_discount;
                        $final_sub = $sub - $divide_disc_amt;
                        $total += $final_sub;
                        // echo '<pre>nonexp';Print_r($sub);
                        // echo '<pre>nonexp';Print_r($divide_disc_per);
                        // echo '<pre>nonexp';Print_r($final_sub);
                         
                    }
                    else
                    {
                        $item_disc = 0;
                        $item_disc_amt = 0;
                        $divide_disc_per = 0;
                        $divide_disc_amt = 0.00;
                        $sub = $row1['rate'];
                        $final_sub = $row1['rate'];
                        $total += $final_sub; 
                      //  echo '<pre>exp';Print_r($divide_disc_amt);
                       // echo '<pre>exp';Print_r($final_sub);
                    }
                    //echo '<pre>';Print_r($total);exit;
                    $item_data = array(
                        'total' => $sub,
                        'item_disc'=>  $item_disc,
                        'discount'=> $item_disc_amt,
                        'divide_disc_item_per'=> $divide_disc_per,
                        'divide_disc_item_amt'=> $divide_disc_amt,
                        'sub_total'=> $final_sub,
                    );
                    $update_total = $gmodel->update_data_table('purchase_item', array('id' => $row1['id']), $item_data);
                 }
                // exit;
               
                
            }
            else
            {
                
                foreach($result_array_item as $row1)
                {
                    if($row1['is_expence'] == 0)
                    {
                        if($row1['item_disc'] > 0)
                        {
                            $sub = $row1['qty'] * $row1['rate']; 
                            $item_disc_amt = $sub * $row1['item_disc'] / 100;
                            $divide_disc_per = 0;  
                            $divide_disc_amt = 0.00;
                            $final_sub = $sub - $item_disc_amt;  
                            $total += $final_sub;  
                        }
                        else
                        {
                            $sub = $row1['qty'] * $row1['rate']; 
                            $item_disc_amt = 0.00;
                            $divide_disc_per = 0;  
                            $divide_disc_amt = 0.00;
                            $final_sub = $sub;   
                            $total += $final_sub;
                        }
                    }
                    else
                    {
                            $item_disc_amt = 0;
                            $divide_disc_per = 0;
                            $divide_disc_amt = 0.00;
                            $sub = $row1['rate'];
                            $final_sub = $row1['rate'];
                            $total += $final_sub;
                    }
                    $item_data = array(
                        'total' => $sub,
                        'discount'=> $item_disc_amt,
                        'divide_disc_item_per'=> $divide_disc_per,
                        'divide_disc_item_amt'=> $divide_disc_amt,
                        'sub_total'=> $final_sub,
                    );
                    $update_total = $gmodel->update_data_table('purchase_item', array('id' => $row1['id']), $item_data);
                    
                   
                }
            }
            $update_total_invoice = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('divide_disc_up'=>1));
                   
        }
        if(isset($update_total))
        {
            $msg = array("sucsess","updated data");
        }
        else
        {
            $msg = array("fail","out");   
        }
        return $msg;
        
    }
    public function update_net_amount_purchase_return()
    {
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('purchase_return');
        $builder->select('*');
        $builder->where(array('is_delete'=>0,'net_amt_up'=>0));
        $builder->limit(1000);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $gmodel = new GeneralModel();
        foreach($result_array as $row)
        {
            $builder = $db->table('purchase_item');
            $builder->select('*');
            $builder->where(array('is_delete'=>0,'parent_id'=>$row['id'],'type'=>'return'));
            $result = $builder->get();
            $result_array_item = $result->getResultArray();
            $item_total = 0;
            foreach($result_array_item as $row1)
            {
                $cal_total = $row1['sub_total'] + $row1['added_amt'];
                $item_total += $cal_total;
            }
            if ($row['cess_type'] == '%') {
                if ($row['cess'] == '')
                    $row['cess'] = 0;
                else
                    $row['cess'] = $item_total *  $row['cess'] / 100;
            } else {
                if ($row['cess'] == '')
                    $row['cess'] = 0;
            }
    
            if (!empty($row['tds_per'])) {
                $tds_amt = $item_total *  $row['tds_per'] / 100;
            } else {
                $tds_amt = 0;
            }
            $netamount = $item_total + $row['cess'] + $row['amty'] +   $tds_amt + $row['tot_igst'];
          
            $update_total_invoice = $gmodel->update_data_table('purchase_return', array('id' => $row['id']), array('net_amt_up'=>1,'net_amount'=> $netamount));
      
        }
        if(isset($update_total_invoice))
        {
            $msg = array("sucsess","updated data");
        }
        else
        {
            $msg = array("fail","out");   
        }
        return $msg;
    }
}