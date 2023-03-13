<?php

namespace App\Controllers;

use App\Models\ItemsModel;
use App\Models\AccountModel;
use App\Models\SalesModel;
use App\Models\PurchaseModel;
use App\Models\GeneralModel;
use App\Models\MasterModel;
use App\Models\ApiModel;

class Api extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){

        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");

        $this->model = new ApiModel();
        $this->imodel = new ItemsModel();
        $this->amodel = new AccountModel();
        $this->smodel = new SalesModel();
        $this->pmodel = new PurchaseModel();
        $this->gmodel = new GeneralModel();
        $this->mmodel = new MasterModel();
    }

    public function add_distributer_party(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
            
                if(!empty($post['gst'])){
                    $post['gst_type'] = "Regular";
                }else{
                    $post['gst_type'] = "Unregister";
                }

                $post['country'] = "101";
                $post['ship_country'] = "101";
                $post['taxability'] = "N/A";

                if(!empty($post['state'])){
                    $state = $this->gmodel->get_data_table('states',array("name"=>$post['state']),'id');
                    $post['state'] = @$state['id'];
                }
                if(!empty($post['city'])){
                    $ship_city = $this->gmodel->get_data_table('cities',array("name"=>$post['city']),'id');
                    $post['city'] = @$city['id'];
                }


                if(!empty($post['ship_state'])){
                    $ship_state = $this->gmodel->get_data_table('states',array("name"=>$post['ship_state']),'id');
                    $post['ship_state'] = @$ship_state['id'];
                }
                if(!empty($post['ship_city'])){
                    $ship_city = $this->gmodel->get_data_table('cities',array("name"=>$post['ship_city']),'id');
                    $post['ship_city'] = @$ship_city['id'];
                }

                $response = $this->amodel->insert_edit_account($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function add_bank(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
            
                if(!empty($post['gst'])){
                    $post['gst_type'] = "Regular";
                }else{
                    $post['gst_type'] = "Unregister";
                }

                $post['country'] = "101";
                $post['ship_country'] = "101";
                $post['taxability'] = "N/A";

                if(!empty($post['state'])){
                    $state = $this->gmodel->get_data_table('states',array("name"=>$post['state']),'id');
                    $post['state'] = @$state['id'];
                }
                if(!empty($post['city'])){
                    $ship_city = $this->gmodel->get_data_table('cities',array("name"=>$post['city']),'id');
                    $post['city'] = @$city['id'];
                }


                if(!empty($post['ship_state'])){
                    $ship_state = $this->gmodel->get_data_table('states',array("name"=>$post['ship_state']),'id');
                    $post['ship_state'] = @$ship_state['id'];
                }
                if(!empty($post['ship_city'])){
                    $ship_city = $this->gmodel->get_data_table('cities',array("name"=>$post['ship_city']),'id');
                    $post['ship_city'] = @$ship_city['id'];
                }

                $response = $this->amodel->insert_edit_account($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function add_transporter(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['country'] = '101';

                if(!empty($post['state'])){
                    $state = $this->gmodel->get_api_data_table($post['database'],'states',array("name"=>$post['state']),'id');
                    $post['state'] = @$state['id'];
                }

                if(!empty($post['city'])){
                    $ship_city = $this->gmodel->get_api_data_table($post['database'],'cities',array("name"=>$post['city']),'id');
                    $post['city'] = @$ship_city['id'];
                }

                $response = $this->model->insert_edit_transport($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function add_multi_distributer_party(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $multi_post =json_decode(json_encode($post_json), true);;

        $fail_name = array();
        $success_ids = array();

        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($multi_post)){
                foreach($multi_post as $post){

                    if(!empty($post['gst'])){
                        $post['gst_type'] = "Regular";
                    }else{
                        $post['gst_type'] = "Unregister";
                    }

                    $post['country'] = "101";
                    $post['taxability'] = "N/A";

                    if(!empty($post['state'])){
                        $state = $this->gmodel->get_data_table('states',array("name"=>$post['state']),'id');
                        $post['state'] = $state['id'];
                    }
                    if(!empty($post['city'])){
                        $city = $this->gmodel->get_data_table('cities',array("name"=>$post['city']),'id');
                        $post['city'] = $city['id'];
                    }
                    $resp = $this->amodel->insert_edit_account($post);

                    if($resp['st'] == "fail"){
                        $fail_name[] = $post['name'] . " => ".$resp['msg'] ;
                    }else{
                        $success_ids[] = @$resp['id'];
                    }
                }
                $response = array('st' => 'success', 'msg' => 'You Api Call Successfully' ,"success_ids"=>$success_ids,"fail_names" =>$fail_name);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','success_id' =>array(),'fail_names'=>array());
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','success_id' =>array(),'fail_names'=>array());
        }

        return $this->response->setJSON($response);
    }
    public function add_item_grp(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['status'] = 1;
                $response = $this->mmodel->insert_edit_itemgrp($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function add_item(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post = json_decode(json_encode($post_json), true);
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['item_type'] = "Inventory";
                $post['item_mode'] = "general";
                $post['uom'][] = "28";
                $post['default_cut'] = 0;
                $post['non_gst'] = "no";
                $post['taxability'] = "Taxable";
                $response = $this->imodel->insert_edit_item($post);

            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }

  // colorsoul api
    public function Ace_add_sales_invoice(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "51";
                $post['round'] = "6";
                $response = $this->model->ace_insert_edit_sales_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Ace_pos_add_sales_invoice(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        // print_r($post);exit;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "51";
                $post['round'] = "6";
                $response = $this->model->ace_pos_insert_edit_sales_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function Ace_add_sales_return(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
      
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "52";
                $post['round'] = "6";
                $response = $this->model->ace_insert_edit_sales_return($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function Ace_add_purchase_invoice(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "53";
                $post['round'] = "6";
                $response = $this->model->ace_insert_edit_purchase_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        // print_r($response);exit;

        return $this->response->setJSON($response);
    }
    // ecom api
    public function Ecom_mtr_add_sales_invoice(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "51";
                $post['round'] = "6";
                $response = $this->model->ecom_mtr_insert_edit_sale_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Ecom_mtr_add_sales_return(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "52";
                $post['round'] = "6";
                $response = $this->model->ecom_mtr_insert_edit_sale_return($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Ecom_mtr_add_purchase_invoice(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "53";
                $post['round'] = "6";
                $response = $this->model->ecom_mtr_insert_edit_purchase_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Ecom_mtr_add_custom_jv(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                
                $response = $this->model->ecom_mtr_insert_edit_custom_jv($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        // print_r($response);exit;

        return $this->response->setJSON($response);
    }

    // klamp ace api
    public function Klamp_ace_add_sales_invoice(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "51";
                $post['round'] = "6";
                $response = $this->model->klamp_ace_insert_edit_sale_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Klamp_ace_add_sales_return(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "52";
                $post['round'] = "6";
                $response = $this->model->klamp_ace_insert_edit_sale_return($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Klamp_ace_add_purchase_invoice(){
        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if(@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "53";
                $post['round'] = "6";
                $response = $this->model->klamp_ace_insert_edit_purchase_invoice($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        
        return $this->response->setJSON($response);
    }
    public function Klamp_ace_add_custom_jv(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                
                $response = $this->model->klamp_ace_insert_edit_custom_jv($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }
        // print_r($response);exit;

        return $this->response->setJSON($response);
    }

   //start not usable
    public function add_sale_challan(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "51";
                $post['round'] = "6";
                $response = $this->model->insert_edit_sale_challan($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    public function add_purchase_challan(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        // print_r($post);exit;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $post['voucher_type'] = "53";
                $post['round'] = "6";
                $response = $this->model->insert_edit_purchase_challan($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }
    //
   
   
    //end not usable
   
    public function update(){

        $header = $this->request->getHeader('Authorization');
        $post_json = $this->request->getJSON();
        $post =json_decode(json_encode($post_json), true);;
        
        if (@$header->getValue() == '4ccda7514adc0f13595a585205fb9761') {
            if(!empty($post)){
                $response = $this->model->updatedata($post);
            }else{
                $response = array('st' => 'fail', 'msg' => 'Data is Empty','id' =>'');
            }
        } else {
            $response = array('st' => 'fail', 'msg' => 'You are not Authorization','id'=>'');
        }

        return $this->response->setJSON($response);
    }


}

?>