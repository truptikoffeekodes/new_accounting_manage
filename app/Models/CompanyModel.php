<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\GeneralModel;

class CompanyModel extends Model
{
    
    public function get_companygrp_data($get){
        $dt_search = $dt_col = array(
           "id",
           "code",
           "name",
           "sname",
           "pname",
           "address",
           "city",
           "state",
           "country",
           "pin",
           "phone",
           "fax",
           "email",
           "weburl",
           "slogan",
           "notes",
           "status",
           "savelogo",
           "created_at",
           "created_by",
           "update_at",
           "update_by",
        );
    
        $filter = $get['filter_data'];
        $tablename = "companygrp";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
    
        foreach ($rResult['table'] as $row) {
           // print_r($row);exit;
            $DataRow = array();
            $btnedit = '<a data-toggle="modal" data-target="#fm_model"  href="' . url('Company/create_companygrp/') . $row['id'] . '" data-title="Edit Company Group : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btndelete = '<a data-toggle="modal" target="_blank"   title="Company Group Name: ' . $row['code'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $btn = $btnedit . $btndelete;
    
            $DataRow[] = $row['id'];
            $DataRow[] = $row['code'];         
            $DataRow[] = $row['name'];
            $DataRow[] = $row['email'];
            $DataRow[] = $row['phone'];
            $DataRow[] = $row['notes'];
            $DataRow[] = $row['status'];
    
            $DataRow[] = $btn;
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function insert_edit_companygrp($post,$file){
     
        $db = $this->db;
        $builder = $db->table('companygrp');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
   
        $msg = array();
        $pdata = array(
            'code' => $post['code'],
            'name' => $post['name'],
            'sname' => $post['short'],
            'pname' => $post['print'],
            'address' => $post['add'],
            'city' => $post['city'],
            'state' => $post['state'],
            'country' => $post['Country'],
            'pin' => $post['pin'],
            'phone' => $post['phone'],
            'fax' => $post['fax'],
            'email' => $post['email'],
            'weburl' => $post['URL'],
            'slogan' => $post['slogan'],
            'notes' => $post['note'],
            'status' => $post['status'],
            'savelogo' => $post['slogo'],
        );

        
    
        if (!empty($result_array)) {
         
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = '1';
            if (empty($msg)) {
             
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
 
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
         
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = '2';
         
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
        return $msg;
    }
 
    public function insert_edit_company($post,$file,$logo){
        
        $db = $this->db;
        $builder = $db->table('company');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        $msg = array();
        
        if(!empty($post['financial_year_form']) && $post['financial_year_form'] != '00-00-0000' ){
            $fn_date = date_create($post['financial_year_form']);
            $financial_year_form = date_format($fn_date,'Y-m-d');
        }else{
            $financial_year_form = '';
        }
        
        if(!empty($post['financial_year_to'])  && $post['financial_year_to'] != '00-00-0000'){
            $fn_to_date = date_create($post['financial_year_to']);
            $financial_year_to = date_format($fn_to_date,'Y-m-d');
        }else{
            $financial_year_to = '';
        }
        
        if(!empty($post['local_tax_date']) && $post['local_tax_date'] != '00-00-0000' ){
            $fn_local_tax_date = date_create($post['local_tax_date']);
            $local_tax_date = date_format($fn_local_tax_date,'Y-m-d');
        }else{
            $local_tax_date = '';
        }
        

        if(!empty($post['central_tax_date']) && $post['central_tax_date'] != '00-00-0000'){
            $fn_central_tax_date = date_create($post['central_tax_date']);
            $central_tax_date = date_format($fn_central_tax_date,'Y-m-d');
        }else{
            $central_tax_date = '';
        }

        if(!empty($post['cst_tin_date']) && $post['cst_tin_date'] != '00-00-0000'){
            $fn_cst_tin_date = date_create($post['cst_tin_date']);
            $cst_tin_date = date_format($fn_cst_tin_date,'Y-m-d');
        }else{
            $cst_tin_date = '';
        }
        if(!empty($post['gst_date']) && $post['gst_date'] != '00-00-0000'){
            $fn_gst_date = date_create($post['gst_date']);
            $gst_date = date_format($fn_gst_date,'Y-m-d');
        }else{
            $gst_date = '';
        }
        
        $pdata = array(
            'code' => $post['code'],
            'company_group' => @$post['group'],
            'name' => strtoupper(@$post['company_name']),
            'financial_form' => (@$financial_year_form) ? @$financial_year_form : '',
            'financial_to' => @$financial_year_to,
            'localtax_no' => (@$post['local_tax']) ? @$post['local_tax'] : '',
            'localtax_date' => (@$local_tax_date) ? @$local_tax_date : '',
            'centraltax_no' => (@$post['central_tax']) ? @$post['central_tax'] : '',
            'centraltax_date' => (@$central_tax_date) ? @$central_tax_date : '',
            'cin' => (@$post['cin']) ? @$post['cin'] : '',
            'cst_no' => (@$post['cst_tin'] ? @$post['cst_tin'] : ''),
            'cst_date' => (@$cst_tin_date) ? @$cst_tin_date : '',
            'gst_no' => (@$post['GST']) ? @$post['GST'] : '',
            'gst_date' => (@$gst_date) ? @$gst_date : '',
            'incomtax_pan' => (@$post['income_tax_pan']) ? @$post['income_tax_pan'] : '',
            'ward_no' => (@$post['ward_no']) ? @$post['ward_no'] : '',
            'buisness_code' => (@$post['buisness_code']) ? @$post['buisness_code'] : '',
            'form_company' => (@$post['company_form']) ? @$post['company_form'] : '',
            'business_type' => (@$post['business_type']) ? @$post['business_type'] : '',
            'contact_person' => (@$post['contact_person']) ? ucfirst(@$post['contact_person']) : '',
            'alternate_contact' => (@$post['altername_contact']) ? @$post['altername_contact'] : '',
            'address' => (@$post['address']) ? @$post['address'] : '',
            'country' => (@$post['country']) ? @$post['country'] : '',
            'state' => (@$post['state']) ? @$post['state'] : '',
            'city' => (@$post['city']) ? @$post['city'] : '',
            'pin' => (@$post['pin']) ? @$post['pin'] : '',
            'whatsap'=> (@$post['whatsap']) ? @$post['whatsap'] : '',
            'email'=> (@$post['email']) ? @$post['email'] : '',
            'reg_certi'=> (@$post['reg_cert']) ? @$post['reg_cert'] : '',
            'enrol_certi'=> (@$post['enrol_certi']) ? @$post['enrol_certi'] : '',
            'impo_expo'=> (@$post['impo_expo']) ? @$post['impo_expo'] : '',
            'bank_ac_name'=> (@$post['bank_holder']) ? @$post['bank_holder'] : '',
            'bank_ac_no'=> (@$post['bank_ac']) ? @$post['bank_ac'] : '',
            'bank_name'=> (@$post['bank_name']) ? @$post['bank_name'] : '',
            'ifsc'=> (@$post['ifsc']) ? strtoupper(@$post['ifsc']) : '',
        );
        
        if(isset($file)){
            
            if ($file->isValid() && !$file->hasMoved()) {
                $original_path = '/Signature_img/' . date('Ymd') . '/';

                if (!file_exists(getcwd() . $original_path)) {
                    mkdir(getcwd() . $original_path, 0777, true);
                }
                $newName = $file->getRandomName();
                $file->move(getcwd() . $original_path, $newName);
                $pdata['sign_capture'] = $original_path . $newName;
            }
        }
        
        if(isset($logo)){
            if($logo->isValid() && !$logo->hasMoved()) {
                $original_path = '/LOGO/' . date('Ymd') . '/';

                if (!file_exists(getcwd() . $original_path)) {
                    mkdir(getcwd() . $original_path, 0777, true);
                }
                $newName = $file->getRandomName();
                $logo->move(getcwd() . $original_path, $newName);
                $pdata['logo'] = $original_path . $newName;
            }
        }

        if (!empty($result_array)) {
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                // $builder = $db->table('company');

                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $DataSource = $post['code'];
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');
            $pdata['DataSource'] = $DataSource;

            $db->query('CREATE DATABASE IF NOT EXISTS '.$DataSource.';');
    
            $lines = file(getcwd().'/sql/koffeekoded.sql'); 
            
            $temp_line = '';
            foreach ($lines as $line)
            {
                if (substr($line, 0, 2) == '--'  || substr($line, 0, 1) == '#')
                continue;
                $temp_line .= $line;    
                if (substr(trim($line), -1, 1) == ';')
                {
                    $this->db->setDatabase($DataSource);
                    try{
                        $this->db->query($temp_line);
                    }catch(\Exception $e){
                        $qry = "DROP DATABASE $DataSource";
                        $this->db->query($qry);
                        $msg = array('st' => 'fail', 'msg' => "Database Creation Issue..Contact Administrator..!!");
                        return $msg; 
                        
                    }        
                    $temp_line = '';
                    // echo '<pre>';print_r($temp_line);
                }
            }  
           //exit;   
            if (empty($msg)) {
                
                $this->db->setDatabase('manifest_erp');
                $result = $builder->Insert($pdata);
                
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        }
        return $msg;
    }
    
    public function  get_gst($id){
        $db = $this->db;
        $builder = $db->table('company');
        $builder->select('*');
        $builder->where(array("id" => $id));
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRowArray();
        $gmodel= new GeneralModel();
        
        $getCountry = $gmodel->get_data_table('countries',array('id'=>@$result['country']),'name');
        $getState = $gmodel->get_data_table('states',array('id'=>@$result['state']),'name');
        $getCity = $gmodel->get_data_table('cities',array('id'=>@$result['city']),'name');

        $result['country_name'] = @$getCountry['name'];
        $result['state_name'] = @$getState['name'];
        $result['city_name'] = @$getCity['name'];

        return $result;
    }

    public function insert_edit_company_gst($post){

        $db = $this->db;
        $builder = $db->table('company');
        $builder->select('*');
        $builder->where(array("id" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        
        if(!empty($post['eway_date'])){
            $fn_date = date_create($post['eway_date']);
            $date = date_format($fn_date,'Y-m-d',);
        }
        $pdata = array(
            'gst_address' =>@$post['gst_address'],
            'city' => @$post['city'] ? $post['city'] : '',
            'state' => @$post['state'] ? $post['state'] : '',
            'country' => @$post['country'] ? $post['country'] : '',
            'gst_type' => @$post['gst_type'] ? $post['gst_type'] : '',
            'gst_period' => @$post['gst_period'] ? $post['gst_period'] :'' ,
            'advance_rec' => @$post['advance_rec'] ? $post['advance_rec'] : '' ,
            'rev_charge' => @$post['rev_charge'] ? $post['rev_charge'] : '',
            'eway' => @$post['eway'] ? $post['eway'] : '',
            'eway_date' => @$date ? @$date : '',
            'threshold' =>@$post['threshold'] ? $post['threshold'] : '',
            'intra_state' =>@$post['intra_state'] ? $post['intra_state'] : '',
            'intra_threshold' =>@$post['intra_threshold'] ? $post['intra_threshold'] : '',
        );

        if (!empty($result_array)) {
            $pdata['update_at'] = date('Y-m-d H:i:s');
            $pdata['update_by'] = session('uid');
            if (empty($msg)) {
                $builder->where(array("id" => $post['id']));
                $result = $builder->Update($pdata);
                //$builder = $db->table('company');
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details updated Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            }
        } else {
            $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
        }
        
        return $msg;
    }

    public function get_company_data($get){
        
        $dt_search = $dt_col = array(
           "id",
           "code",
           "name",
           "company_group",
           "name",
           "financial_form",
           "financial_to",
           "phone",
           "email",  
           "created_at",  
        );
    
        $filter = $get['filter_data'];
        $tablename = "company";
        $where = '';
        
        // if($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        
        $where .= " and is_delete=0";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
    
        foreach ($rResult['table'] as $row) { 
            
            $DataRow = array();
            $btnedit = '<a  href="' . url('Company/CreateCompany/') . $row['id'] . '" data-title="Edit Company : ' . $row['name'] . '" class="btn btn-link pd-10"><i class="far fa-edit"></i></a> ';
            $btnview = '<a  href="' . url('company/company_view/') . $row['id'] . '"  class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $add_gst = '<a  data-toggle ="modal" data-target="#fm_model" href="' . url('Company/add_gst/') . $row['id'] . '" data-title="Add Gst Detail : ' . $row['name'] . '" class="btn btn-link pd-10">Add GST</a> ';
            $btndelete = '<a data-toggle ="modal" target="_blank"   title="Company Name: ' . $row['code'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $login = '<a href="' . url('Company/opencompany/') . $row['id'] . '"   tabindex="-1" class="btn btn-link pd-10">Open Company</a> ';
            $btn = $btnedit . $btndelete . $btnview;
    
            if(!empty($row['financial_form']) || $row['financial_form'] != '0000-00-00'){
                $from_dt = date_create($row['financial_form']);
                $from_date = date_format($from_dt, 'Y');
            }else{
                $from_date = '';
            }

            if(!empty($row['financial_to']) || $row['financial_to'] != '0000-00-00'){
                $to_dt = date_create($row['financial_to']);
                $to_date = date_format($to_dt, 'Y');
            }else{
                $to_date = '';
            }

            $DataRow[] = $row['id'];
            $DataRow[] ='<a  href="' . url('company/company_view/') . $row['id']. '" >'. $row['name'] . '</a>';
            $DataRow[] =user_date($row['created_at']);
            $DataRow[] = $row['code'];
            $DataRow[] = $from_date .'-'. $to_date;
            
            $DataRow[] = $login;
            $DataRow[] = $add_gst;
            $DataRow[] = $btn;
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function company_login($id) {
        
        $db = $this->db;
        //$db->setDatabase(DataSource);
        $builder = $db->table('company');
        $builder->select('*');
        $builder->where(array("id" => $id, 'is_delete' => '0'));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRow();
        $msg = array();

        $gmodel = new GeneralModel();
        if (!empty($result_array)) {
            
            $city = $gmodel->get_data_table('cities',array('id'=>$result_array->city),'name');
            $country = $gmodel->get_data_table('countries',array('id'=>$result_array->country),'name');
            $state = $gmodel->get_data_table('states',array('id'=>$result_array->state),'name,state_code');

            $companydata = [
                'cid' => $result_array->id,
                'is_stock' => $result_array->is_stock,
                'code' => $result_array->code,
                'DataSource' => $result_array->DataSource,
                'company_group' => $result_array->company_group,
                'name' => $result_array->name,
                'email' => $result_array->email,
                'financial_form' => $result_array->financial_form,
                'financial_to' => $result_array->financial_to,
                'state' => @$result_array->state,
                'state_name' => @$state['name'],
                'state_code' => @$state['state_code'],
                'city' => @$city['name'],
                'pin' => $result_array->pin,
                'country' => @$country['name'],
                'address' => $result_array->address,
                'gst' => $result_array->gst_no,
                'whatsapp' => $result_array->whatsap,
                'contact' => $result_array->alternate_phone,
                'incomtax_pan' => $result_array->incomtax_pan,
                'bank_ac_name' => $result_array->bank_ac_name,
                'bank_ac_no' => $result_array->bank_ac_no,
                'bank_name' => $result_array->bank_name,
                'ifsc' => $result_array->ifsc,
            ];
            $session = session();
            $session->set($companydata);
            
            $msg = array("st" => "success", "msg" => "Session Set Successfully!!!");
        } else {
            $msg = array("st" => "failed", "msg" => "Something Went Wrong!!!");
        }
        return $msg;
    }

    public function get_master_data($method, $id) {
        $gmodel=new GeneralModel;
        if ($method == 'companygrp') {
            $result['companygrp'] = $gmodel->get_data_table('companygrp', array('id' => $id));
        }
        return $result;
    }

    public function UpdateData($post) {
        $result = array();
        $db = $this->db;
        if ($post['type'] == 'Remove') {
            if ($post['method'] == 'company') {
                $gnmodel = new GeneralModel();
                $company = $gnmodel->get_data_table('company',array('id'=>$post['pk']),'code');
                if(!empty($company)){
                    $qry = "CREATE DATABASE IF NOT EXISTS " .$company['code'];
                    $db->query($qry); 
                                  
                    $drp = "DROP DATABASE " .$company['code'];
                    $res = $db->query($drp);
                    
                    if($res){
                        $result = $gnmodel->update_data_table('company', array('id' => $post['pk']), array('is_delete' => '1'));
                    }
                }else{
                    $result = $gnmodel->update_data_table('company', array('id' => $post['pk']), array('is_delete' => '1'));
                }

            }
            if ($post['method'] == 'companygrp') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('companygrp', array('id' => $post['pk']), array('is_delete' => '1'));
            }
        }
        return $result;
    }

    public function get_company_byid($id){
        $db = $this->db;
        $builder = $db->table('company');
        $builder->select('*');
        $builder->where(array("id" => $id));
        $builder->limit(1);
        $result = $builder->get();
        $result_row = $result->getRowArray();
        
        $gmodel = new GeneralModel();
        $getcountry = $gmodel->get_data_table('countries',array('id'=>$result_row['country']),'name');
        $getcities = $gmodel->get_data_table('cities',array('id'=>$result_row['city']),'name');
        $getstate = $gmodel->get_data_table('states',array('id'=>$result_row['state']),'name');

        $result_row['country_name'] = @$getcountry['name'];
        $result_row['state_name'] = @$getstate['name'];
        $result_row['city_name'] = @$getcities['name'];
                
        return $result_row;
    }

    public function search_companygrp_data($term) {
        //    echo 'jenith';exit;
        $db = $this->db;
        $builder = $db->table('companygrp');
        $builder->select('id,name');
        $builder->where(array('is_delete' => '0'));
        $builder->like('name',$term);
        $builder->limit(5);
        $query = $builder->get();
        $getdata = $query->getResultArray();
        
        $result = array();
        foreach($getdata as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        // print_r($result);exit;
        return $result;
    }

    public function getCountry($post){
            
        $db = $this->db;
        
        
        $builder=$db->table('countries');
        $builder->select('*');
        $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        $result = $builder->get();
        $result_array = $result->getResultArray();

        $result = array();
        foreach($result_array as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }

    public function getStates($post) {
        $db = $this->db;
       
        $builder=$db->table('states');
        $builder->select('*');
        $builder->like('name',(@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        $builder->where('country_id', $post['country']);
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $result = array();
        foreach($result_array as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }

    public function getCities($post) {
        $db = $this->db; 
        $builder=$db->table('cities');
        $builder->select('*');
        $builder->like('name', (@$post['searchTerm']) ? @$post['searchTerm'] : 'A');
        if(isset($post['state'])){
            $builder->where('state_id', $post['state']);
        }
        $result = $builder->get();
        $result_array = $result->getResultArray();
        $result = array();
        foreach($result_array as $row){
            $result[] = array("text" => $row['name'],"id" => $row['id']);
        }
        return $result;
    }

    public function update_company_id($post,$field){
        
        $db = $this->db;
        $builder = $db->table('company');
        $builder->set($field,$post['id']);
        $builder->where(array("id" => session('cid')));
        $result = $builder->update();
         
        if($result){
            $session = session();
            $session->set('is_stock',$post['id']);
            $msg = array("st" => "success", "msg" => "Data Update Successfully.. !!!");
        } else {
            $msg = array("st" => "failed", "msg" => "Something Went Wrong .. !!!");
        }
        return $msg;

    }

    public function get_company_detail($id){
        
        $db = $this->db;  
        $builder=$db->table('company');
        $builder->select('*');
        $builder->where('is_delete',0);
        $builder->where('id',$id);
        $query = $builder->get();
        $result = $query->getRowArray();
        
        return $result;
    }
}
?>