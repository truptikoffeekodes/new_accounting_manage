<?php 

namespace App\Controllers;
use App\Models\GeneralModel;
use App\Models\SalesApendColumnModel;

class SalesApendColumn extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new SalesApendColumnModel();
        $this->gmodel = new GeneralModel();
        
    }
    // item taxability
    public function item_taxability()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }
           $msg = $this->model->update_item_taxability();
           return $this->response->setJSON($msg);
    }
    // inv_taxability
    public function sales_challan_taxability()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_challan_taxability();
           return $this->response->setJSON($msg);
    }
    public function sales_invoice_taxability()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_invoice_taxability();
           return $this->response->setJSON($msg);
    }
    public function sales_return_taxability()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_return_taxability();
           return $this->response->setJSON($msg);
    }
    public function account_taxability()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

      
           $msg = $this->model->update_account_taxability();
           return $this->response->setJSON($msg);
      

    }
    public function sales_acinvoice_taxability()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_acinvoice_taxability();
           return $this->response->setJSON($msg);
    }
    // modified igst_acc, cgst_acc, sgst_acc, glgroup
    public function sales_challan_gst_acc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_challan_gst_acc();
           return $this->response->setJSON($msg);
    }
    public function sales_invoice_gst_acc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_invoice_gst_acc();
           return $this->response->setJSON($msg);
    }
    public function sales_return_gst_acc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_return_gst_acc();
           return $this->response->setJSON($msg);
    }
    public function sales_general_gst_acc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_general_gst_acc();
           return $this->response->setJSON($msg);
    }
    // item discount
    public function sales_general_item_disc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->new_update_sales_general_item_disc();
           return $this->response->setJSON($msg);
    }
    public function sales_challan_item_disc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->new_update_sales_challan_item_disc();
           return $this->response->setJSON($msg);
    }
    public function sales_invoice_item_disc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->new_update_sales_invoice_item_disc();
           return $this->response->setJSON($msg);
    }
    public function sales_return_item_disc()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->new_update_sales_return_item_disc();
           return $this->response->setJSON($msg);
    }
   
    /// newwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
    // sales gl_group update
    public function update_glgroup_sales_challan()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_glgroup_sales_challan();
           return $this->response->setJSON($msg);
    }
    public function update_glgroup_sales_invoice()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_glgroup_sales_invoice();
           return $this->response->setJSON($msg);
    }
    public function update_glgroup_sales_return()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_glgroup_sales_return();
           return $this->response->setJSON($msg);
    }
    public function update_glgroup_sales_general()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_glgroup_sales_general();
           return $this->response->setJSON($msg);
    }
   // update gst number 
    public function sales_acinvoice_gst()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_acinvoice_gst();
           return $this->response->setJSON($msg);
    }
    //sub_total
    public function sales_sub_total()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_subtotal();
           return $this->response->setJSON($msg);
    }
    public function sales_general_sub_total()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_general_subtotal();
           return $this->response->setJSON($msg);
    }
    // hsn 
    public function sales_item_hsn()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sales_item_hsn();
           return $this->response->setJSON($msg);
    }
    public function update_sgst_amt()
    {
        // if (!session('uid')) {
        //     return redirect()->to(url('auth'));
        // }

           $msg = $this->model->update_sgst_amt();
           return $this->response->setJSON($msg);
    }
    public function update_divide_discount_sales_invoice()
    {
        $msg = $this->model->update_divide_discount_sales_invoice();
        return $this->response->setJSON($msg);
    }
    // public function update_net_amount_sales_invoice()
    // {
    //     $msg = $this->model->update_net_amount_sales_invoice();
    //     return $this->response->setJSON($msg);
    // }

    public function update_divide_discount_sales_challan()
    {
        $msg = $this->model->update_divide_discount_sales_challan();
        return $this->response->setJSON($msg);
    }
    public function update_net_amount_sales_challan()
    {
        $msg = $this->model->update_net_amount_sales_challan();
        return $this->response->setJSON($msg);
    }

    public function update_divide_discount_sales_return()
    {
        $msg = $this->model->update_divide_discount_sales_return();
        return $this->response->setJSON($msg);
    }
    public function update_net_amount_sales_return()
    {
        $msg = $this->model->update_net_amount_sales_return();
        return $this->response->setJSON($msg);
    }

   


   
      
   

   


}

?>