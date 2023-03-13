<?php 

namespace App\Controllers;
use App\Models\GeneralModel;
use App\Models\PurchaseApendColumnModel;

class PurchaseApendColumn extends BaseController{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new PurchaseApendColumnModel();
        $this->gmodel = new GeneralModel();
        
    }
     // item taxability
     public function item_taxability()
     {
         if (!session('uid')) {
             return redirect()->to(url('auth'));
         }
            $msg = $this->model->update_item_taxability();
            return $this->response->setJSON($msg);
     }
  
    // inv_taxability
    public function purchase_challan_taxability()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_challan_taxability();
           return $this->response->setJSON($msg);
    }
    public function purchase_invoice_taxability()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_invoice_taxability();
           return $this->response->setJSON($msg);
    }
    public function purchase_return_taxability()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_return_taxability();
           return $this->response->setJSON($msg);
    }
    public function account_taxability()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

      
           $msg = $this->model->update_account_taxability();
           return $this->response->setJSON($msg);
      

    }
    public function purchase_acinvoice_taxability()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_acinvoice_taxability();
           return $this->response->setJSON($msg);
    }
    // modified igst_acc, cgst_acc, sgst_acc, glgroup
    public function purchase_challan_gst_acc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_challan_gst_acc();
           return $this->response->setJSON($msg);
    }
    public function purchase_invoice_gst_acc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_invoice_gst_acc();
           return $this->response->setJSON($msg);
    }
    public function purchase_return_gst_acc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_return_gst_acc();
           return $this->response->setJSON($msg);
    }
    public function purchase_general_gst_acc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_general_gst_acc();
           return $this->response->setJSON($msg);
    }
    // item discount
    public function purchase_general_item_disc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->new_update_purchase_general_item_disc();
           return $this->response->setJSON($msg);
    }
    public function purchase_challan_item_disc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->new_update_purchase_challan_item_disc();
           return $this->response->setJSON($msg);
    }
    public function purchase_invoice_item_disc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->new_update_purchase_invoice_item_disc();
           return $this->response->setJSON($msg);
    }
    public function purchase_return_item_disc()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->new_update_purchase_return_item_disc();
           return $this->response->setJSON($msg);
    }
   
    /// newwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
    // purchase gl_group update
    public function update_glgroup_purchase_challan()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_glgroup_purchase_challan();
           return $this->response->setJSON($msg);
    }
    public function update_glgroup_purchase_invoice()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_glgroup_purchase_invoice();
           return $this->response->setJSON($msg);
    }
    public function update_glgroup_purchase_return()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_glgroup_purchase_return();
           return $this->response->setJSON($msg);
    }
    public function update_glgroup_purchase_general()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_glgroup_purchase_general();
           return $this->response->setJSON($msg);
    }
   // update gst number 
    public function purchase_acinvoice_gst()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_acinvoice_gst();
           return $this->response->setJSON($msg);
    }
    //sub_total
    public function purchase_sub_total()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_subtotal();
           return $this->response->setJSON($msg);
    }
    public function purchase_general_sub_total()
    {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }

           $msg = $this->model->update_purchase_general_subtotal();
           return $this->response->setJSON($msg);
    }

    
   
      
   

   


}

?>