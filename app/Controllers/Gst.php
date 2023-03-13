<?php

namespace App\Controllers;

use App\Models\GeneralModel;
use App\Models\GstModel;

class Gst extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new GstModel();
        $this->gmodel = new GeneralModel();

    }

    public function gstr3_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->gstr3_xls_export_data($post);
        } else {

            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->gstr3_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }

    public function gstr1()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getPost();
        if (!empty($post)) {
            $data['gstr1'] = get_gstr1_detail(db_date($post['from']), db_date($post['to']));
        }
        //echo '<pre>';Print_r($data);exit;
        

        $data['title'] = "GSTR -1";
        return view('gst/gstr1', $data);
    }

    public function gstr1_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->gstr1_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->gstr1_export_data($post);
        }
        $dt = date_create($post['from']);
        $fin_to = date_create(session('financial_to'));
        $fin_from = date_create(session('financial_to'));
        $name = 'GSTR-1_';
        $name .= session('gst') . '_';
        $name .= date_format($dt, 'M') . '_';
        $name .= date_format($fin_from, 'Y') . '-' . date_format($fin_to, 'y');
        $name .= '.json';

        return $this->response->download($name, $data);
    }

    public function b2c_small_state_vouchers()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data['state_data'] = $this->model->get_state_wise_voucher($post);
        }

        // echo '<pre>';print_r($data);exit;

        // else{

        //     $company_from = session('financial_form');
        //     $company_to = session('financial_to');
        //     $data['from'] = session('financial_form');
        //     $data['to'] = session('financial_to');
        //     $data['state_code'] = $post['state_code'];
        //     $data['rate'] = $post['rate'];

        //     $data['state_data'] = $this->model->get_state_wise_voucher(db_date($post['from']),db_date($post['to']),$post['state_code'],$post['rate']);

        // }

        $data['title'] = @$data['state_data']['state']['state_code'] . '-' . @$data['state_data']['state']['name'];
        $data['from'] = db_date($post['from']);
        $data['to'] = db_date($post['to']);
        $data['state_code'] = $post['state_code'];
        $data['rate'] = $post['rate'];

        return view('gst/gstr1_b2c_small_state_invoices', $data);

    }

    public function gstr1_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->gstr1_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->gstr1_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }

    public function cr_dr_reg_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_cr_dr_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_cr_dr_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "Credit/Debit Notes(Registered) -9B";

        return view('gst/cr_dr_reg_detail', $data);

    }

    public function Hsn_summary()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->get_hsn_summary(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');
            $data = $this->model->get_hsn_summary($company_from, $company_to);
        }
        // echo '<pre>';print_r($data);exit;

        $data['title'] = "HSN SUMMARY";
        return view('gst/hsn_summary', $data);
    }

    public function Hsn_detail($hsn = '', $gst = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data['hsn_detail'] = $this->model->get_hsn_detail(db_date($post['from']), db_date($post['to']), $hsn, $gst);
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data['hsn_detail'] = $this->model->get_hsn_detail($post['from'], $post['to'], $hsn, $gst);
        }

        $data['start_date'] = $post['from'];
        $data['end_date'] = $post['to'];

        $data['hsn'] = $hsn;
        $data['title'] = "HSN DETAIL";

        return view('gst/hsn_detail', $data);
    }

    public function cr_dr_unreg_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_cr_dr_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_cr_dr_detail(db_date($company_from), db_date($company_to));
        }
        $data['title'] = "Credit/Debit Notes(Unregistered) -9B";

        return view('gst/cr_dr_unreg_detail', $data);

    }

    public function cr_dr_reg_invoice()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_cr_dr_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_cr_dr_detail(db_date($company_from), db_date($company_to));
        }
        $data['type'] = $post['type'];
        $data['title'] = "Credit/Debit Notes(Registered) -9B";
        // echo '<pre>';   print_r($data);exit;
        return view('gst/cr_dr_reg_invoice', $data);

    }

    public function cr_dr_unreg_invoice()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_cr_dr_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_cr_dr_detail(db_date($company_from), db_date($company_to));
        }

        $data['type'] = $post['type'];
        $data['title'] = "Credit/Debit Notes(UnRegistered) -9B";

        return view('gst/cr_dr_unreg_invoice', $data);

    }

    public function b2b_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "B2B Invoices -4A,4B,4C,6B,6C";

        return view('gst/gstr1_b2b_detail', $data);

    }

    public function nill_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_nill_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_nill_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "Nil Rated Invoices -8A,8B,8C,8D";

        return view('gst/gstr1_nill_detail', $data);

    }

    public function nill_invoices()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_nill_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_nill_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "Nil Rated Invoices -8A,8B,8C,8D";
        $data['type'] = $post['type'];

        return view('gst/gstr1_nill_invoices', $data);

    }

    public function b2c_small_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
            $data['state_data'] = $this->model->get_state_wise_data(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
            $data['state_data'] = $this->model->get_state_wise_data(db_date($post['from']), db_date($post['to']));

        }

        $data['title'] = "B2C(Small) Invoices -7";

        return view('gst/gstr1_b2c_small_detail', $data);

    }

    public function b2c_large_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));

        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "B2C(Large) Invoices -5A,5B";

        return view('gst/gstr1_b2c_large_detail', $data);

    }

    public function b2b_sales_inv_vouchers()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();
        if (!empty($post)) {

            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }
        //echo '<pre>';Print_r($data);exit;

        $data['title'] = "B2B Sales Invoices";
        $data['type'] = "sales";
        return view('gst/gstr1_b2b_invoices', $data);
    }

    public function b2c_small_sales_inv_vouchers()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();
        if (!empty($post)) {
            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        // echo '<pre>';print_r($data);exit;
        $data['title'] = "B2C Sales Invoices";
        $data['type'] = "sales";
        return view('gst/gstr1_b2c_small_invoices', $data);
    }

    public function b2c_small_gnrl_sales_inv_vouchers()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();
        if (!empty($post)) {
            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "B2C Small General Sales Invoices";
        $data['type'] = "general";
        return view('gst/gstr1_b2c_small_invoices', $data);
    }

    public function b2c_large_sales_inv_vouchers()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();
        if (!empty($post)) {
            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "B2C Large Sales Invoices";
        $data['type'] = "sales";
        return view('gst/gstr1_b2c_large_invoices', $data);
    }

    public function b2c_large_gnrl_sales_inv_vouchers()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();
        if (!empty($post)) {
            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "B2C Large General Sales Invoices";
        $data['type'] = "general";
        return view('gst/gstr1_b2c_large_invoices', $data);
    }

    public function b2b_gnrl_sales_inv_vouchers()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();
        if (!empty($post)) {

            $data = get_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $data = get_b2b_b2c_detail(db_date($company_from), db_date($company_to));
        }

        $data['title'] = "B2B General Sales Invoices";
        $data['type'] = "General_Sales";
        return view('gst/gstr1_b2b_invoices', $data);
    }

    public function gstr2()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        $file = $this->request->getFile('json_file');
        if (!empty($file)) {
            $msg = $this->model->insert_edit_gstr2_JSON($file);

            if (@$msg['st'] == 'success') {

                $from = $msg['start_date'];
                $to = $msg['end_date'];
                $data['json'] = $msg['data'];
                $data['gstr2'] = get_gstr2_detail($from, $to);
                $data['title'] = "GSTR -2";

                return view('gst/gstr2_json', $data);

            } else {

            }
        }
        if (!empty($post)) {
            $from = date_create($post['from']);
            $to = date_create($post['to']);

            $post['from'] = date_format($from, "Y-m-d");
            $post['to'] = date_format($to, "Y-m-d");

            $data['gstr2'] = get_gstr2_detail($post['from'], $post['to']);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');
            $data['gstr2'] = get_gstr2_detail($company_from, $company_to);
        }

        $data['title'] = "GSTR -2";
        return view('gst/gstr2', $data);
    }

    public function gstr2_b2b_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = get_gstr2_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');
            $data = get_gstr2_b2b_b2c_detail($company_from, $company_to);
        }

        $data['title'] = "B2B Invoices - 3,4A";

        return view('gst/gstr2_b2b_detail', $data);
    }

    public function gstr2_cr_dr_detail()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = get_gstr2_cr_dr_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');
            $data = get_gstr2_cr_dr_detail($company_from, $company_to);
        }

        $data['title'] = "Credit /Debit Notes Regular - 6C";

        return view('gst/gstr2_cr_dr_detail', $data);
    }

    public function gstr2_b2b_invoices()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data = get_gstr2_b2b_b2c_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');
            $data = get_gstr2_b2b_b2c_detail($company_from, $company_to);
        }

        $data['title'] = "B2B Invoices - 3,4A";
        $data['type'] = $post['type'];

        return view('gst/gstr2_b2b_invoices', $data);
    }

    public function import_goods_invoice_wise()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();
        if (!empty($post)) {
            $result = import_goods_data($post['from'], $post['to']);
        }

        $result['date']['from'] = $post['from'];
        $result['date']['to'] = $post['to'];
        $result['title'] = "Import Goods Invoice Wise";
        // print_r($result);exit;
        return view('gst/import_good_invoice_wise', $result);

    }

    public function gstr2_cr_dr_invoices()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = get_gstr2_cr_dr_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');
            $data = get_gstr2_cr_dr_detail($company_from, $company_to);
        }

        $data['title'] = "B2B Invoices - 3,4A";
        $data['type'] = $post['type'];
        //echo '<pre>';print_r($data);exit;
        return view('gst/gstr2_cr_dr_invoices', $data);
    }

    public function b2binvoice()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getPost();
        echo '<pre>';
        print_r($post);exit;

        if (!empty($post)) {

            $from = date_create($post['from']);
            $to = date_create($post['to']);

            $post['from'] = date_format($from, "Y-m-d");
            $post['to'] = date_format($to, "Y-m-d");

            $data = $this->model->get_b2binvoice_data($post['from'], $post['to']);

        } else {
            $data = $this->model->get_b2binvoice_data();
        }

        $data['title'] = "B2B Invoice -3,4A";
        return view('gst/b2binvoice', $data);
    }

    public function gstr3()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getPost();
        if (!empty($post)) {

            $data['gstr3'] = get_gstr3_detail(db_date($post['from']), db_date($post['to']));
            // $data = get_gstr1_detail(db_date($post['from']), db_date($post['to']));
        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');

            $data['gstr3'] = get_gstr3_detail(db_date($post['from']), db_date($post['to']));
        }

        $data['from'] = $post['from'];
        $data['to'] = $post['to'];
        $data['title'] = "GSTR -3";

        // echo '<pre>';print_r($data);exit;

        return view('gst/gstr3', $data);
    }

    public function Gstr3_detail($type = '')
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        $post = $this->request->getGet();

        if (!empty($post)) {

            $data['gstr3_detail'] = get_gstr3_detail(db_date($post['from']), db_date($post['to']));

        } else {
            $post['from'] = session('financial_form');
            $post['to'] = session('financial_to');
            $data['gstr3_detail'] = get_gstr3_detail(db_date($post['from']), db_date($post['to']));

        }
        $data['start_date'] = $post['from'];
        $data['end_date'] = $post['to'];

        $data['type'] = $type;
        $data['title'] = "GSTR-3 DETAIL";

        return view('gst/gstr3_detail', $data);
    }

    public function xml()
    {

        $test_array = array(
            'bla' => 'blub',
            'foo' => 'bar',
            'another_array' => array(
                'stack' => 'overflow',
            ),
        );
        $xml = new \SimpleXMLElement('<root/>');
        array_walk_recursive($test_array, array($xml, 'addChild'));
        echo $xml->asXML();
    }
    public function b2b_sales_inv_vouchers_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->b2b_sales_inv_vouchers_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->b2b_sales_inv_vouchers_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function b2c_large_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->b2c_large_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->b2c_large_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function b2c_small_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->b2c_small_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->b2c_small_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function cr_dr_invoice_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->cr_dr_invoice_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->cr_dr_invoice_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function cr_dr_invoice_unreg_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->cr_dr_invoice_unreg_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->cr_dr_invoice_unreg_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function gstr1_nill_xls_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->gstr1_nill_xls_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->gstr1_nill_xls_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function gstr2_b2b_invoices_excel_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->gstr2_b2b_invoices_excel_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->gstr2_b2b_invoices_excel_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }
    public function gstr2_dr_cr_invoices_excel_export()
    {

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

        $post = $this->request->getGet();

        if (!empty($post)) {
            $data = $this->model->gstr2_dr_cr_invoices_excel_export_data($post);
        } else {
            $company_from = session('financial_form');
            $company_to = session('financial_to');

            $post['from'] = $company_from;
            $post['to'] = $company_to;

            $data = $this->model->gstr2_dr_cr_invoices_excel_export_data($post);
        }

        return $this->response->setHeader('Contente-Disposition', 'attachment;filename=abc.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    }

}
