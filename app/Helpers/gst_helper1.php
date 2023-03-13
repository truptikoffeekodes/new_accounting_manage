<?php
use App\Models\GeneralModel;

function get_gstr2_detail($start_date = '', $end_date = ''){
    
    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {
        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder =$db->table('purchase_invoice pi');
    $builder->select('pi.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pi.account');
    $builder->where(array('pi.is_delete ' =>0));
    $builder->where(array('pi.is_cancle ' =>0));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();

    $builder =$db->table('purchase_general pg');
    $builder->select('pg.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pg.party_account');
    $builder->where(array('v_type' => 'general'));
    $builder->where(array('pg.is_delete' => 0));
    $builder->where(array('pg.is_cancle' => 0));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchaseGeneral = $query->getResultArray();
    
    $purchase = array_merge($purchase_invoice,$purchaseGeneral);
    
    $b2b =array();
    $b2cSmall = array();

    for($i=0;$i<count($purchase);$i++){
        if(@$purchase[$i]['gst'] == '' || empty($purchase[$i]['gst']) ){
            if($purchase[$i]['total_amount'] < 250000){  
                $b2cSmall['data'][] = $purchase[$i];
            }
        }else{
            if($purchase[$i]['total_amount'] < 250000){  
                $b2cSmall['data'][] = $purchase[$i];
            }
            $b2b['data'][] = $purchase[$i];
        }
    }
    $b2b['sgst'] =0;
    $b2b['cgst'] =0;
    $b2b['igst'] =0;
    $b2b['cess'] =0;
    $b2b['taxable_amount'] =0;

    $b2cSmall['sgst'] = 0;
    $b2cSmall['cgst'] = 0;
    $b2cSmall['igst'] = 0;
    $b2cSmall['cess'] = 0;
    $b2cSmall['taxable_amount'] = 0;

    if(!empty($b2b['data'])){
        foreach($b2b['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $b2b['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $b2b['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $b2b['cess'] += $row['cess'];
                }else{
                    $b2b['igst'] += $row['tot_igst'];
                }   
            }
            $b2b['taxable_amount'] += $row['total_amount'];
        }
    }

    if(!empty($b2cSmall['data'])){
        foreach($b2cSmall['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $b2cSmall['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $b2cSmall['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $b2cSmall['cess'] += $row['cess'];
                }else{
                    $b2cSmall['igst'] += $row['tot_igst'];
                }
            }

            $b2cSmall['taxable_amount'] += $row['total_amount'];
            $b2cSmall['voucher_count'] = count($b2cSmall['data']);
        }
    }
    
    $builder =$db->table('purchase_return pr');
    $builder->select('pr.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pr.account');
    $builder->where(array('pr.is_delete' => 0));
    $builder->where(array('pr.is_cancle' => 0));
    $builder->where(array('DATE(pr.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pr.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    
    $builder =$db->table('purchase_general pg');
    $builder->select('pg.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pg.party_account');
    $builder->where(array('v_type' => 'return'));
    $builder->where(array('pg.is_delete' => 0));
    $builder->where(array('pg.is_cancle' => 0));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $general_return = $query->getResultArray();

    
    $cr_dr = array_merge($purchase_return,$general_return);

    $cr_drReg=array();
    $cr_dr_UnReg=array();

    $cr_drReg['cgst'] =0;
    $cr_drReg['sgst'] =0;
    $cr_drReg['igst'] =0;
    $cr_drReg['cess'] =0;
    $cr_drReg['taxable_amount'] =0;

    $cr_dr_UnReg['cgst']=0;
    $cr_dr_UnReg['sgst']=0;
    $cr_dr_UnReg['igst']=0;
    $cr_dr_UnReg['cess']=0;
    $cr_dr_UnReg['taxable_amount']=0;

    for($i=0;$i<count($cr_dr);$i++){
        if(@$cr_dr[$i]['gst'] == '' || empty($cr_dr[$i]['gst']) ){
            $cr_dr_UnReg['data'][] = $cr_dr[$i];
        }else{
            $cr_drReg['data'][] = $cr_dr[$i];
        }
    }
    
    if(!empty($cr_drReg['data'])){
        foreach($cr_drReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){

                if($tax == 'cgst'){                    
                    $cr_drReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $cr_drReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $cr_drReg['cess'] += $row['cess'];
                }else{
                    $cr_drReg['igst'] += $row['tot_igst'];
                }
            }
            $cr_drReg['taxable_amount'] += $row['total_amount'];
        }
    }

    if(!empty($cr_dr_UnReg['data'])){
        foreach($cr_dr_UnReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $cr_dr_UnReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $cr_dr_UnReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $cr_dr_UnReg['cess'] += $row['cess'];
                }else{
                    $cr_dr_UnReg['igst'] += $row['tot_igst'];
                }
            }
            $cr_dr_UnReg['taxable_amount'] += $row['total_amount'];
        }
    }

    $purchase = (@$b2b['data'] ? count(@$b2b['data']) : 0) ;
    $purchase_return = (@$cr_drReg['data'] ? count(@$cr_drReg['data']) : 0) ;
    
    $from =date_create($start_date) ;                                         
    $to = date_create($end_date);
    $gstr2 = array(
        'b2b' =>    $b2b,
        'b2cSmall' => $b2cSmall,
        'cr_drReg' => $cr_drReg,
        'cr_drUnReg' => $cr_dr_UnReg,
        'purchase' => $purchase,
        'purchase_return' => $purchase_return,
        'start_date' => date_format($from,"Y-m-d"),
        'end_date' => date_format($to,"Y-m-d")
    );
    return $gstr2;
    
}

function get_state_data($id)
{
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }    
    $builder =$db->table('states');
    $builder->select('*');
    $builder->where(array('id'=>$id));
    $query = $builder->get();
    $ac_return = $query->getRowArray();
    return $ac_return;
}

function get_gstr2_b2b_b2c_detail($start_date = '', $end_date = ''){
    
    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder =$db->table('purchase_invoice pi');
    $builder->select('pi.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pi.account');
    $builder->where(array('pi.is_delete ' =>0));
    $builder->where(array('pi.is_cancle ' =>0));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_invoice = $query->getResultArray();

    $builder =$db->table('purchase_general pg');
    $builder->select('pg.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pg.party_account');
    $builder->where(array('v_type' => 'general'));
    $builder->where(array('pg.is_delete' => 0));
    $builder->where(array('pg.is_cancle' => 0));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchaseGeneral = $query->getResultArray();
    
    $purchase_b2b =array();
    $purchase_b2cSmall = array();
    $gnrl_purchase_b2b =array();
    $gnrl_purchase_b2cSmall = array();

    $purchase_b2b['data']=array();
    $purchase_b2cSmall['data'] = array();
    $gnrl_purchase_b2b['data'] = array();
    $gnrl_purchase_b2cSmall['data'] = array();

    for($i=0;$i<count($purchase_invoice);$i++){
        if(@$purchase_invoice[$i]['gst'] == '' || empty($purchase_invoice[$i]['gst']) ){
            if($purchase[$i]['total_amount'] < 250000){  
                $purchase_b2cSmall['data'][] = $purchase_invoice[$i];
            }
        }else{
            if($purchase_invoice[$i]['total_amount'] < 250000){  
                $purchase_b2cSmall['data'][] = $purchase_invoice[$i];
            }
            $purchase_b2b['data'][] = $purchase_invoice[$i];
        }
    }

    for($i=0;$i<count($purchaseGeneral);$i++){
        if(@$purchaseGeneral[$i]['gst'] == '' || empty($purchaseGeneral[$i]['gst']) ){
            if($purchaseGeneral[$i]['total_amount'] < 250000){  
                $gnrl_purchase_b2cSmall['data'][] = $purchaseGeneral[$i];
            }
        }else{
            if($purchaseGeneral[$i]['total_amount'] < 250000){  
                $gnrl_purchase_b2cSmall['data'][] = $purchaseGeneral[$i];
            }
            $gnrl_purchase_b2b['data'][] = $purchaseGeneral[$i];
        }
    }
    // echo '<pre>';print_r($gnrl_purchase_b2b);exit;
    $purchase_b2b['sgst'] =0;
    $purchase_b2b['cgst'] =0;
    $purchase_b2b['igst'] =0;
    $purchase_b2b['cess'] =0;
    $purchase_b2b['taxable_amount'] =0;
    $purchase_b2b['net_amount'] =0;
    $purchase_b2b['count'] =count(@$purchase_b2b['data']);

    $purchase_b2cSmall['sgst'] = 0;
    $purchase_b2cSmall['cgst'] = 0;
    $purchase_b2cSmall['igst'] = 0;
    $purchase_b2cSmall['cess'] = 0;
    $purchase_b2cSmall['taxable_amount'] = 0;
    $purchase_b2cSmall['net_amount'] = 0;
    $purchase_b2cSmall['count'] = count($purchase_b2cSmall['data']);

    if(!empty($purchase_b2b['data'])){
        foreach($purchase_b2b['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $purchase_b2b['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $purchase_b2b['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $purchase_b2b['cess'] += $row['cess'];
                }else{
                    $purchase_b2b['igst'] += $row['tot_igst'];
                }   
            }
            $purchase_b2b['taxable_amount'] += $row['total_amount'];
            $purchase_b2b['net_amount'] += $row['net_amount'];
        }
    }

    if(!empty($purchase_b2cSmall['data'])){
        foreach($purchase_b2cSmall['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $purchase_b2cSmall['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $purchase_b2cSmall['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $purchase_b2cSmall['cess'] += $row['cess'];
                }else{
                    $purchase_b2cSmall['igst'] += $row['tot_igst'];
                }
            }
            $purchase_b2cSmall['taxable_amount'] += $row['total_amount'];
            $purchase_b2cSmall['net_amount'] += $row['net_amount'];
        }
    }

    $gnrl_purchase_b2b['sgst'] =0;
    $gnrl_purchase_b2b['cgst'] =0;
    $gnrl_purchase_b2b['igst'] =0;
    $gnrl_purchase_b2b['cess'] =0;
    $gnrl_purchase_b2b['taxable_amount'] =0;
    $gnrl_purchase_b2b['net_amount'] =0;
    $gnrl_purchase_b2b['count'] =count($gnrl_purchase_b2b['data']);

    $gnrl_purchase_b2cSmall['sgst'] = 0;
    $gnrl_purchase_b2cSmall['cgst'] = 0;
    $gnrl_purchase_b2cSmall['igst'] = 0;
    $gnrl_purchase_b2cSmall['cess'] = 0;
    $gnrl_purchase_b2cSmall['taxable_amount'] = 0;
    $gnrl_purchase_b2cSmall['net_amount'] = 0;
    $gnrl_purchase_b2cSmall['count'] = count($gnrl_purchase_b2cSmall['data']);

    if(!empty($gnrl_purchase_b2b['data'])){
        foreach($gnrl_purchase_b2b['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $gnrl_purchase_b2b['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_purchase_b2b['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_purchase_b2b['cess'] += $row['cess'];
                }else{
                    $gnrl_purchase_b2b['igst'] += $row['tot_igst'];
                }   
            }
            $gnrl_purchase_b2b['taxable_amount'] += $row['total_amount'];
            $gnrl_purchase_b2b['net_amount'] += $row['net_amount'];
        }
    }

    if(!empty($gnrl_purchase_b2cSmall['data'])){
        foreach($gnrl_purchase_b2cSmall['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $gnrl_purchase_b2cSmall['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_purchase_b2cSmall['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_purchase_b2cSmall['cess'] += $row['cess'];
                }else{
                    $gnrl_purchase_b2cSmall['igst'] += $row['tot_igst'];
                }
            }

            $gnrl_purchase_b2cSmall['taxable_amount'] += $row['total_amount'];
            $gnrl_purchase_b2cSmall['net_amount'] += $row['net_amount'];
        }
    }
    
   

    // $purchase = (@$b2b['data'] ? count(@$b2b['data']) : 0) ;
    // $purchase_return = (@$cr_drReg['data'] ? count(@$cr_drReg['data']) : 0) ;
    
    $from =date_create($start_date) ;                                         
    $to = date_create($end_date);
    $gstr2 = array(
        'purchase_b2b' =>    $purchase_b2b,
        'purchase_b2cSmall' => $purchase_b2cSmall,
        'gnrl_purchase_b2b' =>    $gnrl_purchase_b2b,
        'gnrl_purchase_b2cSmall' => $gnrl_purchase_b2cSmall,
        'start_date' => date_format($from,"d-m-Y"),
        'end_date' => date_format($to,"d-m-Y")
    );
    // echo '<pre>';print_r($gstr2);exit;
    return $gstr2;
    
}

function get_gstr2_cr_dr_detail($start_date = '', $end_date = ''){
        
    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    //print_r($start_date);exit;
    $builder =$db->table('purchase_return pr');
    $builder->select('pr.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pr.account');
    $builder->where(array('pr.is_delete' => 0));
    $builder->where(array('pr.is_cancle' => 0));
    $builder->where(array('DATE(pr.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(pr.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase_return = $query->getResultArray();
    
    $builder =$db->table('purchase_general pg');
    $builder->select('pg.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pg.party_account');
    $builder->where(array('v_type' => 'return'));
    $builder->where(array('pg.is_delete' => 0));
    $builder->where(array('pg.is_cancle' => 0));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $general_return = $query->getResultArray();

    
    // $cr_dr = array_merge($purchase_return,$general_return);

    $purchase_ret_cr_drReg=array();
    $gnrl_ret_cr_drsReg=array();

    $purchase_ret_cr_dr_UnReg=array();
    $gnrl_ret_cr_dr_UnReg=array();

    $purchase_ret_cr_drReg['cgst'] =0;
    $purchase_ret_cr_drReg['sgst'] =0;
    $purchase_ret_cr_drReg['igst'] =0;
    $purchase_ret_cr_drReg['cess'] =0;
    $purchase_ret_cr_drReg['taxable_amount'] =0;
    $purchase_ret_cr_drReg['net_amount'] =0;

    $purchase_ret_cr_dr_UnReg['cgst']=0;
    $purchase_ret_cr_dr_UnReg['sgst']=0;
    $purchase_ret_cr_dr_UnReg['igst']=0;
    $purchase_ret_cr_dr_UnReg['cess']=0;
    $purchase_ret_cr_dr_UnReg['taxable_amount']=0;
    $purchase_ret_cr_dr_UnReg['net_amount']=0;

    for($i=0;$i<count($purchase_return);$i++){
        if(@$purchase_return[$i]['gst'] == '' || empty($purchase_return[$i]['gst']) ){
            $purchase_ret_cr_dr_UnReg['data'][] = $purchase_return[$i];
        }else{
            $purchase_ret_cr_drReg['data'][] = $purchase_return[$i];
        }
    }
    
    if(!empty($purchase_ret_cr_drReg['data'])){
        foreach($purchase_ret_cr_drReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){

                if($tax == 'cgst'){                    
                    $purchase_ret_cr_drReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $purchase_ret_cr_drReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $purchase_ret_cr_drReg['cess'] += $row['cess'];
                }else{
                    $purchase_ret_cr_drReg['igst'] += $row['tot_igst'];
                }
            }
            $purchase_ret_cr_drReg['taxable_amount'] += $row['total_amount'];
            $purchase_ret_cr_drReg['net_amount'] += $row['net_amount'];
        }
    }

    if(!empty($purchase_ret_cr_dr_UnReg['data'])){
        foreach($purchase_ret_cr_dr_UnReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $purchase_ret_cr_dr_UnReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $purchase_ret_cr_dr_UnReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $purchase_ret_cr_dr_UnReg['cess'] += $row['cess'];
                }else{
                    $purchase_ret_cr_dr_UnReg['igst'] += $row['tot_igst'];
                }
            }
            $purchase_ret_cr_dr_UnReg['taxable_amount'] += $row['total_amount'];
            $purchase_ret_cr_dr_UnReg['net_amount'] += $row['net_amount'];
        }
    }

    $gnrl_ret_cr_drReg['cgst'] =0;
    $gnrl_ret_cr_drReg['sgst'] =0;
    $gnrl_ret_cr_drReg['igst'] =0;
    $gnrl_ret_cr_drReg['cess'] =0;
    $gnrl_ret_cr_drReg['taxable_amount'] =0;
    $gnrl_ret_cr_drReg['net_amount'] =0;

    $gnrl_ret_cr_dr_UnReg['cgst']=0;
    $gnrl_ret_cr_dr_UnReg['sgst']=0;
    $gnrl_ret_cr_dr_UnReg['igst']=0;
    $gnrl_ret_cr_dr_UnReg['cess']=0;
    $gnrl_ret_cr_dr_UnReg['taxable_amount']=0;
    $gnrl_ret_cr_dr_UnReg['net_amount']=0;


    for($i=0;$i<count($general_return);$i++){
        if(@$general_return[$i]['gst'] == '' || empty($general_return[$i]['gst']) ){
            $gnrl_ret_cr_dr_UnReg['data'][] = $general_return[$i];
        }else{
            $gnrl_ret_cr_drReg['data'][] = $general_return[$i];
        }
    }
    
    if(!empty($gnrl_ret_cr_drReg['data'])){
        foreach($gnrl_ret_cr_drReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){

                if($tax == 'cgst'){                    
                    $gnrl_ret_cr_drReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_ret_cr_drReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_ret_cr_drReg['cess'] += $row['cess'];
                }else{
                    $gnrl_ret_cr_drReg['igst'] += $row['tot_igst'];
                }
            }
            $gnrl_ret_cr_drReg['taxable_amount'] += $row['total_amount'];
            $gnrl_ret_cr_drReg['net_amount'] += $row['net_amount'];
        }
    }

    if(!empty($gnrl_ret_cr_dr_UnReg['data'])){
        foreach($gnrl_ret_cr_dr_UnReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $gnrl_ret_cr_dr_UnReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_ret_cr_dr_UnReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_ret_cr_dr_UnReg['cess'] += $row['cess'];
                }else{
                    $gnrl_ret_cr_dr_UnReg['igst'] += $row['tot_igst'];
                }
            }
            $gnrl_ret_cr_dr_UnReg['taxable_amount'] += $row['total_amount'];
            $gnrl_ret_cr_dr_UnReg['net_amount'] += $row['net_amount'];
        }
    }


    $from =date_create($start_date) ;                                         
    $to = date_create($end_date);

    $gstr2 = array(
        'gnrl_ret_cr_dr_unreg' =>    $gnrl_ret_cr_dr_UnReg,
        'gnrl_ret_cr_dr_reg' =>    $gnrl_ret_cr_drReg,
        'purchase_ret_cr_dr_unreg' =>    $purchase_ret_cr_dr_UnReg,
        'purchase_ret_cr_dr_reg' =>    $purchase_ret_cr_drReg,
        'start_date' => date_format($from,"d-m-Y"),
        'end_date' => date_format($to,"d-m-Y")
    );

    return $gstr2;
}

function get_gstr3_detail($start_date = '', $end_date = ''){

    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $outward = get_gstr1_detail($start_date,$end_date);
    $inward = get_gstr2_detail($start_date,$end_date);
    
    $b2b_inward = @$inward['b2b']['data'];
    $b2cSmall_inward = @$inward['b2cSmall']['data'];

    // $inward_b2c_b2b = array_merge($b2b_inward,$b2cSmall_inward);
    
    $eligible_itc_nill = get_eligible_itc_nill($b2b_inward);

    // echo '<pre>';print_r($eligible_itc_nill);exit;
    $import_good = import_goods_data($start_date,$end_date);

    $eligible_itc_nill['nill']['exempt'] += $import_good['nontaxable'];
    $gst_type_wise =array();
    
    $gst_type_wise['unregister']['igst']=0;
    $gst_type_wise['unregister']['taxable_amount']=0;
    $gst_type_wise['unregister']['sgst'] = 0;
    $gst_type_wise['unregister']['cgst'] = 0;
    $gst_type_wise['unregister']['cess'] = 0;

    $gst_type_wise['composition']['igst']=0;
    $gst_type_wise['composition']['taxable_amount']=0;
    $gst_type_wise['composition']['sgst'] = 0;
    $gst_type_wise['composition']['cgst'] = 0;
    $gst_type_wise['composition']['cess'] = 0;

    if(!empty($outward['b2b']['data'])){
        foreach($outward['b2b']['data'] as $row){
            if($row['gst_type'] == "Unregister"){

                $taxes = json_decode($row['taxes']);
                
                foreach($taxes as $tax){
                    if($tax == 'cgst'){
                        $gst_type_wise['unregister']['cgst'] += $row['tot_cgst'];
                    }else if($tax == 'sgst'){
                        $gst_type_wise['unregister']['sgst'] += $row['tot_sgst'];
                    }else if($tax == 'cess'){
                        $gst_type_wise['unregister']['cess'] += $row['cess'];
                    }else{
                        $gst_type_wise['unregister']['igst'] += $row['tot_igst'];
                    }
                }
                $gst_type_wise['unregister']['taxable_amount'] += $row['total_amount'];
            }

            if($row['gst_type'] == "Composition"){
                
                $taxes = json_decode($row['taxes']);
                foreach($taxes as $tax){
                    if($tax == 'cgst'){
                        $gst_type_wise['composition']['cgst'] += $row['tot_cgst'];
                    }else if($tax == 'sgst'){
                        $gst_type_wise['composition']['sgst'] += $row['tot_sgst'];
                    }else if($tax == 'cess'){
                        $gst_type_wise['composition']['cess'] += $row['cess'];
                    }else{
                        $gst_type_wise['composition']['igst'] += $row['tot_igst'];
                    }
                }
                $gst_type_wise['composition']['taxable_amount'] += $row['total_amount'];
            }
        }
    }
   

    $supply['unregister']['igst']=0;
    $supply['unregister']['taxable_amount']=0;
    $supply['unregister']['sgst'] = 0;
    $supply['unregister']['cgst'] = 0;
    $supply['unregister']['cess'] = 0;

    $supply['composition']['igst']=0;
    $supply['composition']['taxable_amount']=0;
    $supply['composition']['sgst'] = 0;
    $supply['composition']['cgst'] = 0;
    $supply['composition']['cess'] = 0;

    foreach($outward['b2cSmall']['data'] as $row ){

        if($row['acc_state'] != session('state')){

            if($row['gst_type'] == 'Composition'){
                $taxes = json_decode($row['taxes']);
                
                foreach($taxes as $tax){
                    if($tax == 'cgst'){
                        $supply['composition']['cgst'] += $row['tot_cgst'];
                    }else if($tax == 'sgst'){
                        $supply['composition']['sgst'] += $row['tot_sgst'];
                    }else if($tax == 'cess'){
                        $supply['composition']['cess'] += $row['cess'];
                    }else{
                        $supply['composition']['igst'] += $row['tot_igst'];
                    }
                }
                $supply['composition']['taxable_amount'] += $row['total_amount'];
            }

            if($row['gst_type'] == '' || $row['gst_type'] == 'Unregister' ){
                $taxes = json_decode($row['taxes']);
                
                foreach($taxes as $tax){
                    if($tax == 'cgst'){
                        $supply['unregister']['cgst'] += $row['tot_cgst'];
                    }else if($tax == 'sgst'){
                        $supply['unregister']['sgst'] += $row['tot_sgst'];
                    }else if($tax == 'cess'){
                        $supply['unregister']['cess'] += $row['cess'];
                    }else{
                        $supply['unregister']['igst'] += $row['tot_igst'];
                    }
                }
                $supply['unregister']['taxable_amount'] += $row['total_amount'];
            }
        }
    }

    $inward_supply['nill']['taxable_amount'] = 0;
    
    // foreach($b2cSmall_inward as $row){
    //     if($row['taxability'] == 'Exempt' || $row['taxability'] == 'Nill'){
    //         $inward_supply['nill']['taxable_amount'] += $row['total_amount'];
    //     }
    // }
    
    $eligable_itc['sgst'] = 0;
    $eligable_itc['cgst'] = 0;
    $eligable_itc['igst'] = 0;
    $eligable_itc['cess'] = 0;

    // if(!empty($inward['b2csmall']['data'])){
    //     $eligable_itc =$inward['b2b'];
    // }
    
    $from =date_create($start_date) ;                                         
    $to = date_create($end_date);

    $gstr3=array(
        'gst_type_wise' => $gst_type_wise,
        'outward' => $outward['b2b'],
        'nill' => $eligible_itc_nill['nill'],
        'supply' => $supply,
        'inward_supply' => $inward_supply,
        'import_good' => $import_good,
        'eligable_itc' =>$eligible_itc_nill['eligible'],
        'eligable_data' =>$eligible_itc_nill['eligible']['new_data'],
        'start_date' => date_format($from,"d-m-Y"),
        'end_date' => date_format($to,"d-m-Y") 
    );

    
   return $gstr3;
    
}

function import_goods_data($start_date = '', $end_date = ''){
    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }

    $db = \Config\Database::connect();

    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    
    $builder =$db->table('purchase_invoice pi');
    $builder->select('pi.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = pi.account');
    $builder->where(array('pi.is_delete ' =>0));
    $builder->where(array('pi.is_cancle ' =>0));
    $builder->where(array('pi.is_import ' =>1));
    $builder->where(array('DATE(pi.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $purchase_invoice['data'] = $query->getResultArray();

    $tot_gst  = 0;
    $tot_nontaxable =0;
    foreach($purchase_invoice['data'] as $row){
        if($row['is_import'] == 1){
            $tot_gst += $row['import_gst'];
            $tot_nontaxable += $row['import_gst'];
        }
    }
    $purchase_invoice['tot_gst'] = $tot_gst;
    $purchase_invoice['nontaxable'] = $tot_nontaxable;
    // echo '<pre>';print_r($end_date);
    // echo '<pre>';print_r($purchase_invoice);exit;
    return $purchase_invoice;
}

function get_gstr1_detail($start_date = '', $end_date = ''){

    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {
        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $builder =$db->table('sales_invoice si');
    $builder->select('si.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = si.account');
    $builder->where(array('si.is_delete' => 0));
    $builder->where(array('si.is_cancle' => 0));
    $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();
    
    $builder =$db->table('sales_ACinvoice sa');
    $builder->select('sa.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = sa.party_account');
    $builder->where(array('v_type' => 'general'));
    $builder->where(array('sa.is_delete' => 0));
    $builder->where(array('sa.is_cancle' => 0));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $salesAcinvoice = $query->getResultArray();

    $sales = array_merge($salesAcinvoice,$sales_invoice);
    
    $b2c =array();
    $b2b =array();
    $b2cSmall = array();
    $b2cLarge = array();
    $nill = array();

    $b2b['data'] = array();
    $b2cSmall['data'] = array();
    $b2cLarge['data'] = array();
    $nill['data'] = array();
    

    $gmodel = new GeneralModel;
   
    foreach($sales as $row){
        
        if($row['gst'] == '' || empty($row['gst'])) {  // B2C Condition 

            if (isset($row['v_type'])) {
                $sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0),'taxability,igst, amount as total');
            } else {
                $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'taxability,igst,(rate*qty) as total');
            }
    
            $nill_invtaxable = 0;
            $nill_tot_igst = 0;
            $nill_tot_cgst = 0;
            $nill_tot_sgst = 0;
            $nill_invoice_amt = 0;
    
            $invtaxable = 0;
            $tot_igst = 0;
            $tot_cgst = 0;
            $tot_sgst = 0;
            $invoice_amt =0;
    
            $arr1 = $arr2 = $row;
    
            $i = 0;
            $j = 0;

            foreach($sale as $row1){

                if($row1['taxability'] == 'Nill' || $row1['taxability'] == 'Exempt'){  // In B2c Invoice include exempt and nill rated item  
                  
                    $nill_invtaxable +=  (float)$row1['total'];
                    $nill_tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                    $nill_tot_cgst += ((float)$row1['total'] * (float)$row1['igst'] / 100)/ 2;
                    $nill_tot_sgst += ((float)$row1['total'] * (float)$row1['igst'] / 100) / 2;
                    $nill_invoice_amt = $nill_invtaxable + $nill_tot_igst;
    
                    $arr1['taxable'] = $nill_invtaxable; 
                    $arr1['tot_igst'] = $nill_tot_igst; 
                    $arr1['tot_sgst'] = $nill_tot_sgst; 
                    $arr1['tot_cgst'] = $nill_tot_cgst; 
                    $arr1['net_amount'] = $nill_invoice_amt; 

                    $i++;
    
                }else{
                   
                    $invtaxable +=  (float)$row1['total'];
                    $tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                    $tot_cgst += ((float)$row1['total'] * (float)$row1['igst'] / 100) / 2;
                    $tot_sgst += ((float)$row1['total'] * (float)$row1['igst'] / 100) / 2;
                    
                    $invoice_amt = $invtaxable + $tot_igst;  
    
                    $arr2['taxable'] = $invtaxable; 
                    $arr2['tot_igst'] = $tot_igst; 
                    $arr2['tot_sgst'] = $tot_cgst; 
                    $arr2['tot_cgst'] = $tot_sgst; 
                    $arr2['tot_cgst'] = $invoice_amt;   
                    $j++;
                }
            }
            
            if($i != 0){
                $nill['data'][] = $arr1;
            }

            if($j != 0){
                if($arr2['taxable'] < 250000){
                    $b2cSmall['data'][] = $arr2;
                }else{
                    $b2cLarge['data'][] = $arr2;
                }
            }
        }else{
            if($row['inv_taxability'] == 'Nill' || $row['inv_taxability'] == 'Exempt'){
                $nill['data'][] = $row;
            }else{
                $b2b['data'][] = $row;
            }
        }
    }

    $b2b['sgst'] =0;
    $b2b['cgst'] =0;
    $b2b['igst'] =0;
    $b2b['cess'] =0;
    $b2b['taxable_amount'] =0;

    $nill['sgst'] =0;
    $nill['cgst'] =0;
    $nill['igst'] =0;
    $nill['cess'] =0;
    $nill['taxable_amount'] =0;

    $b2cSmall['sgst'] = 0;
    $b2cSmall['cgst'] = 0;
    $b2cSmall['igst'] = 0;
    $b2cSmall['cess'] = 0;
    $b2cSmall['taxable_amount'] = 0;

    $b2cLarge['sgst'] =0;
    $b2cLarge['cgst'] =0;
    $b2cLarge['igst'] =0;
    $b2cLarge['cess'] =0;
    $b2cLarge['taxable_amount'] =0;

    if(!empty($b2b['data'])){
        foreach($b2b['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $b2b['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $b2b['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $b2b['cess'] += (float)$row['cess'];
                }else{
                    $b2b['igst'] += (float)$row['tot_igst'];
                }
            }
            $b2b['taxable_amount'] += (float)$row['taxable'];
        }
    }
    
    if(!empty($b2cLarge['data'])){
        foreach($b2cLarge['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $b2cLarge['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $b2cLarge['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $b2cLarge['cess'] += $row['cess'];
                }else{
                    $b2cLarge['igst'] += $row['tot_igst'];
                }
            }
            $b2cLarge['taxable_amount'] += $row['taxable'];
        }
    }

    if(!empty($b2cSmall['data'])){
        foreach($b2cSmall['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $b2cSmall['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $b2cSmall['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $b2cSmall['cess'] += $row['cess'];
                }else{
                    $b2cSmall['igst'] += $row['tot_igst'];
                }
            }
            $b2cSmall['taxable_amount'] += $row['taxable'];
        }
    }
   
    $builder =$db->table('sales_return sr');
    $builder->select('sr.*,ac.gst,ac.name');
    $builder->join('account ac','ac.id = sr.account');
    $builder->where(array('sr.is_delete' => 0));
    $builder->where(array('sr.is_cancle' => 0));
    $builder->where(array('DATE(sr.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(sr.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $sale_return = $query->getResultArray();

    $builder =$db->table('sales_ACinvoice sa');
    $builder->select('sa.*,ac.gst,ac.name,sa.total_amount as total');
    $builder->join('account ac','ac.id = sa.party_account');
    $builder->where(array('v_type' => 'return'));
    $builder->where(array('sa.is_delete' => 0));
    $builder->where(array('sa.is_cancle' => 0));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $ac_return = $query->getResultArray();
    
    $cr_dr = array_merge($sale_return,$ac_return);

    $cr_dr_UnReg=array();
    $cr_dr_UnReg_state=array();
    $cr_drReg=array();

    $cr_dr_UnReg['data'] = array();
    $cr_dr_UnReg['cgst']=0;
    $cr_dr_UnReg['sgst']=0;
    $cr_dr_UnReg['igst']=0;
    $cr_dr_UnReg['cess']=0;
    $cr_dr_UnReg['taxable_amount']=0;
    
    $cr_dr_UnReg_state['data'] = array();
    $cr_dr_UnReg_state['cgst']=0;
    $cr_dr_UnReg_state['sgst']=0;
    $cr_dr_UnReg_state['igst']=0;
    $cr_dr_UnReg_state['cess']=0;
    $cr_dr_UnReg_state['taxable_amount']=0;

    $cr_drReg['data'] = array();
    $cr_drReg['cgst'] =0;
    $cr_drReg['sgst'] =0;
    $cr_drReg['igst'] =0;
    $cr_drReg['cess'] =0;
    $cr_drReg['taxable_amount'] =0;

    foreach($cr_dr as $row){

        if($row['gst'] == '' || empty($row['gst'])){

            if (isset($row['v_type'])) {
                $sale_ret = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0),'taxability,igst, amount as total');
            } else {
                $sale_ret = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'return'), 'taxability,igst,(rate*qty) as total');
            }

            $nill_crdr_invtaxable = 0;
            $nill_crdr_tot_igst = 0;
            $nill_crdr_tot_cgst = 0;
            $nill_crdr_tot_sgst = 0;
            $nill_crdr_invoice_amt = 0;
    
            $crdr_invtaxable = 0;
            $crdr_tot_igst = 0;
            $crdr_tot_cgst = 0;
            $crdr_tot_sgst = 0;
            $crdr_invoice_amt =0;
            
            $crdr_invtaxable_state = 0;
            $crdr_tot_igst_state = 0;
            $crdr_tot_cgst_state = 0;
            $crdr_tot_sgst_state = 0;
            $crdr_invoice_amt_state =0;
    
            $arr3 = $with_state = $without_state = $row;

            $i=0;            
            $j=0;            
            $k=0;  

            foreach($sale_ret as $row1){
                if($row1['taxability'] == 'Nill' || $row1['taxability'] == 'Exempt'){
                    
                    $nill_crdr_invtaxable +=  (float)$row1['total'];
                    $nill_crdr_tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                    $nill_crdr_tot_cgst += (float)$nill_crdr_tot_igst / 2;
                    $nill_crdr_tot_sgst += (float)$nill_crdr_tot_igst / 2;
                    $nill_crdr_invoice_amt = $nill_crdr_invtaxable + $nill_crdr_tot_igst;
    
                    $arr3['taxable'] = $nill_crdr_invtaxable; 
                    $arr3['tot_igst'] = $nill_crdr_tot_igst; 
                    $arr3['tot_sgst'] = $nill_crdr_tot_sgst; 
                    $arr3['tot_cgst'] = $nill_crdr_tot_cgst; 
                    $arr3['net_amount'] = $nill_crdr_invoice_amt; 
                    $i++;
                    
                }else{
    
                    if($row['acc_state'] != session('state')){
                        $crdr_invtaxable +=  (float)$row1['total'];
                        $crdr_tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                        $crdr_tot_cgst += (float)$crdr_tot_igst / 2;
                        $crdr_tot_sgst += (float)$crdr_tot_igst / 2;
                        
                        $crdr_invoice_amt = $crdr_invtaxable + $crdr_tot_igst;  
        
                        $without_state['taxable'] = $crdr_invtaxable; 
                        $without_state['tot_igst'] = $crdr_tot_igst; 
                        $without_state['tot_sgst'] = $crdr_tot_cgst; 
                        $without_state['tot_cgst'] = $crdr_tot_sgst; 
                        $without_state['tot_cgst'] = $crdr_invoice_amt;   
                        $k++;  
                    }

                    $crdr_invtaxable_state +=  (float)$row1['total'];
                    $crdr_tot_igst_state +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                    $crdr_tot_cgst_state += (float)$crdr_tot_igst_state / 2;
                    $crdr_tot_sgst_state += (float)$crdr_tot_igst_state / 2;
                    
                    $crdr_invoice_amt_state = $crdr_invtaxable_state + $crdr_tot_igst_state;  
    
                    $with_state['taxable'] = $crdr_invtaxable_state; 
                    $with_state['tot_igst'] = $crdr_tot_igst_state; 
                    $with_state['tot_sgst'] = $crdr_tot_cgst_state; 
                    $with_state['tot_cgst'] = $crdr_tot_sgst_state; 
                    $with_state['tot_cgst'] = $crdr_invoice_amt_state;   
                    $j++;
                }
            }
            if($i != 0){
                $nill['data'][] = $arr3;
            }
            if($j != 0){
                $cr_dr_UnReg_state['data'][] = $with_state;
            }
            if($k != 0){
                $cr_dr_UnReg['data'][] = $without_state;
            }


        }else{

            if($row['inv_taxability'] == 'Nill' || $row['inv_taxability'] == 'Exempt'){
                $nill['data'][] = $row;
            }else{
                $cr_drReg['data'][] = $row;
            }
        }        
    }
    
    if(!empty($nill['data'])){
        foreach($nill['data'] as $row){
            if(isset($row['return_no']) || @$row['v_type'] == 'return'){
                $nill['taxable_amount'] -=$row['taxable'];    
            }else{
                $nill['taxable_amount'] += $row['taxable'];
            }
        }
    }

    if(!empty($cr_dr_UnReg_state['data'])){
        foreach($cr_dr_UnReg_state['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $cr_dr_UnReg_state['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $cr_dr_UnReg_state['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $cr_dr_UnReg_state['cess'] += $row['cess'];
                }else{
                    $cr_dr_UnReg_state['igst'] += $row['tot_igst'];
                }
            }
            $cr_dr_UnReg_state['taxable_amount'] += $row['taxable'];
        }
    }

    if(!empty($cr_dr_UnReg['data'])){
        foreach($cr_dr_UnReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $cr_dr_UnReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $cr_dr_UnReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $cr_dr_UnReg['cess'] += $row['cess'];
                }else{
                    $cr_dr_UnReg['igst'] += $row['tot_igst'];
                }
            }
            $cr_dr_UnReg['taxable_amount'] += $row['taxable'];
        }
    }

    if(!empty($cr_drReg['data'])){
        foreach($cr_drReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){                    
                    $cr_drReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $cr_drReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $cr_drReg['cess'] += $row['cess'];
                }else{
                    $cr_drReg['igst'] += $row['tot_igst'];
                }
            }
            $cr_drReg['taxable_amount'] += $row['taxable'];
        }
    }

    $builder =$db->table('bank_tras bt');
    $builder->select('bt.*,ac.gst,ac.name');
    $builder->join('account ac','ac.id = bt.particular');
    $builder->where('bt.is_delete',0);
    $builder->where(array('DATE(bt.receipt_date)  >= ' => $start_date));
    $builder->where(array('DATE(bt.receipt_date)  <= ' => $end_date));
    $query = $builder->get();
    $bank = $query->getResultArray();

    $builder =$db->table('jv_particular jv');
    $builder->select('jv.*,ac.gst,ac.name');
    $builder->join('account ac','ac.id = jv.particular');
    $builder->join('jv_main jm','jm.id = jv.jv_id');
    $builder->where('jm.is_delete',0);
    $builder->where(array('DATE(jv.date)  >= ' => $start_date));
    $builder->where(array('DATE(jv.date)  <= ' => $end_date));
    $query = $builder->get();
    $jv = $query->getResultArray();

    $relevant['data'] = array_merge($bank,$jv);
    
    $relevant_gst = array();

    // for($i=0;$i<count($relevant);$i++){
    //     if(@$relevant[$i]['gst'] == '' || empty($relevant[$i]['gst']) ){
    //         $relevant_non['data'][] = $relevant[$i];
    //     }else{
    //         $relevant_gst['data'][] = $relevant[$i];
    //     }
    // }

    $builder =$db->table('purchase_invoice pi');
    $builder->select('pi.*,ac.gst,ac.name');
    $builder->join('account ac','ac.id = pi.account');
    $builder->where('pi.is_delete',0);
    $builder->where('pi.is_cancle',0);
    $builder->where(array('DATE(pi.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(pi.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $purchase = $query->getResultArray();

    $builder =$db->table('purchase_general pg');
    $builder->select('pg.*,ac.gst,ac.name');
    $builder->join('account ac','ac.id = pg.party_account');
    $builder->where(array('pg.v_type' => 'general'));
    $builder->where(array('pg.is_delete' => 0));
    $builder->where(array('pg.is_cancle' => 0));
    $builder->where(array('DATE(pg.doc_date)  >= ' => $start_date));
    $builder->where(array('DATE(pg.doc_date)  <= ' => $end_date));
    $query = $builder->get();
    $pg = $query->getResultArray();

    $relev = array_merge($purchase,$pg);
    $relevant_non['data'] = array();

    for($i=0;$i<count($relev);$i++){
        if(@$relev[$i]['gst'] == '' || empty(@$relev[$i]['gst']) ){
            $relevant_non['data'][] = $relev[$i];
        }else{
            $relevant_gst['data'][] = $relev[$i];
        }
    }

    $vch_type = "'sale_invoice' as vch_type" ;
    
    $builder =$db->table('sales_item si');
    $builder->select('si.*,i.hsn,s.taxes,s.disc_type,s.discount,'.$vch_type);
    $builder->join('item i','i.id = si.item_id');
    $builder->join('sales_invoice s','s.id = si.parent_id');
    $builder->where(array('si.type' => 'invoice'));
    $builder->where(array('si.is_delete' => 0));
    $builder->where(array('s.is_delete' => 0));
    $builder->where(array('s.is_cancle' => 0));
    $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $invoice_item = $query->getResultArray();

    
    $vch_type = "'sale_return' as vch_type" ;
    $builder =$db->table('sales_item si');
    $builder->select('si.*,i.hsn,s.taxes,s.disc_type,s.discount,'.$vch_type);
    $builder->join('item i','i.id = si.item_id');
    $builder->join('sales_return s','s.id = si.parent_id');
    $builder->where(array('si.type' => 'return'));
    $builder->where(array('si.is_delete' => 0));
    $builder->where(array('s.is_delete' => 0));
    $builder->where(array('s.is_cancle' => 0));
    $builder->where(array('DATE(s.return_date)  >= ' => $start_date));
    $builder->where(array('DATE(s.return_date)  <= ' => $end_date));
    $query = $builder->get();
    $return_item = $query->getResultArray();

    $sale_item = array_merge($invoice_item,$return_item);

    $hsn = array();
    $non_hsn = array();
    $hsn['data'] = array();
    $non_hsn['data'] = array();
    
    $gmodel = new App\Models\GeneralModel();

    for($i=0;$i<count($sale_item);$i++){
        $itm = $gmodel->get_data_table('item',array('id'=>$sale_item[$i]['item_id']),'name');
        $sale_item[$i]['item_name'] = $itm['name'];

        if(@$sale_item[$i]['hsn'] == '' || empty(@$sale_item[$i]['hsn']) ){
            $non_hsn['data'][] = $sale_item[$i];
        }else{
            $hsn['data'][] = $sale_item[$i];
        }
    }

    $builder =$db->table('sales_ACparticu sa');
    $builder->select('sa.*,ac.hsn,s.taxes,ac.name as item_name,sa.amount as rate,sa.type as vch_type');
    $builder->join('account ac','ac.id = sa.account');
    $builder->join('sales_ACinvoice s','s.id = sa.parent_id');
    $builder->where(array('sa.is_delete' => 0));
    $builder->where(array('s.is_delete' => 0));
    $builder->where(array('s.is_cancle' => 0));
    $builder->where(array('DATE(s.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(s.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $gnrl_sale_item = $query->getResultArray();


    for($i=0;$i<count($gnrl_sale_item);$i++){
        if(@$gnrl_sale_item[$i]['hsn'] == '' || empty(@$gnrl_sale_item[$i]['hsn']) ){
            $non_hsn['data'][] = $gnrl_sale_item[$i];
        }else{
            $hsn['data'][] = $gnrl_sale_item[$i];
        }
    }

    $builder =$db->table('jv_particular jv');
    $builder->select('bt.taxable');
    $builder->join('bank_tras bt','bt.id = jv.bank_tras','LEFT');
    $builder->where(array('adjust' => '2'));
    $builder->where(array('dr_cr' => 'cr'));
    $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
    $builder->groupBy('jv.bank_tras');
    $query = $builder->get();
    $taxable = $query->getResultArray();

    $advance_libility['total_taxable'] = 0;
    foreach ($taxable as $row) {
        $advance_libility['total_taxable'] += $row['taxable'];
    }

    $builder =$db->table('jv_particular jv');
    $builder->select('jv.*,ac.name');
    $builder->join('account ac','ac.id = jv.particular');
    $builder->where(array('adjust' => '2'));
    $builder->where(array('dr_cr' => 'cr'));
    $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $jv = $query->getResultArray();

    $advance_tax_libility = array();
    
    foreach ($jv as $row) {
        $total = (@$advance_libility[$row['name']]) ? ($advance_libility[$row['name']] + $row['amount']) : 0 + $row['amount'];
        $advance_libility[$row['name']] = $total;
        $advance_libility['total_voucher'] = count($jv);
    }

    $builder =$db->table('jv_particular jv');
    $builder->select('bt.taxable');
    $builder->join('bank_tras bt','bt.id = jv.bank_tras','LEFT');
    $builder->where(array('adjust' => '7'));
    $builder->where(array('dr_cr' => 'dr'));
    $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
    $builder->groupBy('jv.bank_tras');
    $query = $builder->get();
    $taxable = $query->getResultArray();

    $advance_adjustment['total_taxable'] = 0;

    foreach ($taxable as $row) {
        $advance_adjustment['total_taxable'] += $row['taxable'];
    }

    $builder =$db->table('jv_particular jv');
    $builder->select('jv.*,ac.name');
    $builder->join('account ac','ac.id = jv.particular');
    $builder->where(array('adjust' => '7'));
    $builder->where(array('dr_cr' => 'dr'));
    $builder->where(array('DATE(jv.created_at)  >= ' => $start_date));
    $builder->where(array('DATE(jv.created_at)  <= ' => $end_date));
    $query = $builder->get();
    $adjust_jv = $query->getResultArray();
    
    $advance_adjustment = array();
        
    foreach ($adjust_jv as $row) {
        
        $total = (@$advance_adjustment[$row['name']]) ? ($advance_adjustment[$row['name']] + $row['amount']) : 0 + $row['amount'];
        $advance_adjustment[$row['name']] = $total;
        $advance_adjustment['total_voucher'] = count($adjust_jv);
    }

    $sale = (@$b2b['data'] ? count(@$b2b['data']) : 0) + (@$b2cSmall['data'] ? count(@$b2cSmall['data']) :0 ) + (@$b2cLarge['data'] ? count(@$b2cLarge['data']) : 0);
    $sale_return = (@$cr_drReg['data'] ? count(@$cr_drReg['data']) : 0)  + (@$cr_dr_UnReg['data'] ? count(@$cr_dr_UnReg['data']) : 0);
    
    $state_wise_b2c = get_state_wise_b2c($b2cSmall,$cr_dr_UnReg_state);

    $from =date_create($start_date);                                         
    $to = date_create($end_date);


    $gstr1 = array(
        'b2b' =>    $b2b,
        'b2cSmall' => $b2cSmall,
        'state_wise_b2c' => $state_wise_b2c,
        'b2cLarge' => $b2cLarge,
        'cr_drReg' => $cr_drReg,
        'cr_drUnReg_state' => $cr_dr_UnReg_state,
        'cr_drUnReg' => $cr_dr_UnReg,
        'sales' => $sale,
        'sale_return' => $sale_return,
        'relevant_non' => $relevant,
        'relevant_gst' => $relevant_gst,
        'hsn' => @$hsn,
        'non_hsn' => $non_hsn,
        'advance_tax' => $advance_libility,
        'advance_adjust' => $advance_adjustment,
        'nill'=>$nill,
        'start_date' => user_date($start_date),
        'end_date' => user_date($end_date)
    );

    return $gstr1;
}

function get_state_wise_b2c($b2c,$cr_dr_UnReg_state){

    $b2c_small = $b2c['data'];
    $cdnur = $cr_dr_UnReg_state['data'];
    // $cdnur_not_state = $cr_dr_UnReg['data'];
    // $cdnur = $cdnur_state;
    // echo '<pre>';print_r($cdnur_state);exit;

    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }
    $gmodel = new GeneralModel;
    $comp_state_cdnur = array();
    $cdnr_total['tot_cgst'] =0;
    $cdnr_total['tot_sgst'] =0;
    $cdnr_total['tot_igst'] =0;
    $cdnr_total['taxable'] =0;
    $cdnr_total['net_amount'] = 0;

    foreach($cdnur as $row){

        if($row['gst'] == '' ||  empty($row['gst'])){

            // if($row['acc_state'] == session('state')){
                if(isset($row['v_type'])){

                    $builder = $db->table('sales_ACparticu');
                    $builder->select('taxability,SUM(amount) as total ,igst');
                    $builder->where('is_delete',0);
                    $builder->where('parent_id',$row['id']);
                    $builder->groupBy('igst');
                    $query = $builder->get();
                    $result = $query->getResultArray();              

                }else{

                    $builder = $db->table('sales_item');
                    $builder->select('taxability,SUM(rate*qty) as total ,igst');
                    $builder->where('is_delete',0);
                    $builder->where('type','return');
                    $builder->where('parent_id',$row['id']);
                    $builder->groupBy('igst');
                    $query = $builder->get();
                    $result = $query->getResultArray();
                }

                //---- get cdnur company state total taxable gst wise  ---//

                foreach($result as $row2){
                    if($row2['taxability'] != 'Nill' && $row2['taxability'] != 'Exempt' ){

                        $row['taxable'] = $row2['total'];
                        $row['igst'] = $row2['igst'];

                        $taxes = json_decode($row['taxes']);
                        $gst = $row2['total'] *  (float)$row2['igst'] /100;
                       
                        foreach($taxes as $tax){
                            if($tax == 'cgst'){
                                $cdnr_total['tot_cgst'] +=$gst/2;
                                $comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_cgst'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_cgst'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_cgst'] : 0) + $gst/2;
                            }else if($tax == 'sgst'){
                                $cdnr_total['tot_sgst'] +=$gst/2;
                                $comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_sgst'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_sgst'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_sgst'] : 0) + $gst/2;
                            }else if($tax == 'igst'){
                                $cdnr_total['tot_igst'] +=$gst;
                                $comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_igst'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_igst'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['tot_igst'] : 0) + $gst;
                            }else{
                                $cess = 0;
                            }
                        }
                        $cdnr_total['taxable'] += $row2['total']; 
                        $cdnr_total['net_amount'] += $row2['total'] + $gst;
                         
                        $comp_state_cdnur[$row['acc_state']][$row2['igst']]['net_amount'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['net_amount'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['net_amount'] : 0) + $gst + $row2['total'];

                        $comp_state_cdnur[$row['acc_state']][$row2['igst']]['state'] = $row['acc_state'];
                        $comp_state_cdnur[$row['acc_state']][$row2['igst']]['gst'] = $row2['igst'];
                        $comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] = (@$comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] ? $comp_state_cdnur[$row['acc_state']][$row2['igst']]['taxable'] : 0) + $row2['total'];
                    }
                }
            // }
        }
    } 

    $new_b2b_small = array();
    $new_b2b_small['tot_cgst'] =0;
    $new_b2b_small['tot_sgst'] =0;
    $new_b2b_small['tot_igst'] =0;
    $new_b2b_small['taxable'] =0;
    $new_b2b_small['net_amount'] = 0;
   
    foreach ($b2c_small as $row) {
    
        if (isset($row['v_type'])) {
            $sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0), 'taxability,igst , amount as total');
        } else {
            $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), 'taxability,igst,(rate*qty) as total');
        }

        $invtotal = 0;
            
        foreach($sale as $row1){
            if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt' ){
                
                $invtotal = 0;
                
                $taxes = json_decode($row['taxes']);
                $gst = $row1['total'] *  (float)$row1['igst'] /100;

                foreach($taxes as $tax){
                    $cgst =0;
                    $sgst =0;
                    if($tax == 'cgst'){
                        $cgst = (float)$gst / 2;
                        $new_b2b_small['tot_cgst'] = (float)$new_b2b_small['tot_cgst'] +  (float)$cgst;
                        $new_b2b_small[$row['acc_state']][$row1['igst']]['tot_cgst'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['tot_cgst'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['tot_cgst'] : 0) + $gst/2;
                    }else if($tax == 'sgst'){
                        $sgst = (float)$gst / 2;
                        $new_b2b_small['tot_sgst'] += $sgst;
                        $new_b2b_small[$row['acc_state']][$row1['igst']]['tot_sgst'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['tot_sgst'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['tot_sgst'] : 0) + $gst/2;
                    }else if($tax == 'igst'){
                        $new_b2b_small['tot_igst'] += $gst;
                        $new_b2b_small[$row['acc_state']][$row1['igst']]['tot_igst'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['tot_igst'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['tot_igst'] : 0) + $gst;
                    }else{
                        $cess = 0;
                    }
                }
                $new_b2b_small['taxable']  += $row1['total'];
                $new_b2b_small['net_amount']  += $row1['total'] + $gst;

                $new_b2b_small[$row['acc_state']][$row1['igst']]['net_amount'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['net_amount'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['net_amount'] : 0) + $row1['total'] + $gst;

                $new_b2b_small[$row['acc_state']][$row1['igst']]['id'] = $row['id'];
                $new_b2b_small[$row['acc_state']][$row1['igst']]['acc_state'] = $row['acc_state'];
                $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['taxable'] : 0) + $row1['total'];
                $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] = (@$new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] ? $new_b2b_small[$row['acc_state']][$row1['igst']]['cess'] : 0) + $row['cess'];
                $new_b2b_small[$row['acc_state']][$row1['igst']]['gst'] = $row1['igst'];

                
            }
        }
    }
  
    //---- cdnur company state taxable minus from b2c small same state data ---//
    // echo '<pre>';print_r($cdnr_total);

    foreach($comp_state_cdnur as $state => $value){
        
        foreach($value as $gst => $row2){
        
            $new_b2b_small[$state][$gst]['taxable'] = (@$new_b2b_small[$state][$gst]['taxable'] ? (float)$new_b2b_small[$state][$gst]['taxable'] : 0) - (@$row2['taxable'] ? (float)$row2['taxable'] : 0);
            $new_b2b_small[$state][$gst]['tot_igst'] = (@$new_b2b_small[$state][$gst]['tot_igst'] ? (float)$new_b2b_small[$state][$gst]['tot_igst'] : 0)  - (@$row2['tot_igst'] ? (float)$row2['tot_igst'] : 0);
            $new_b2b_small[$state][$gst]['tot_cgst'] = (@$new_b2b_small[$state][$gst]['tot_cgst'] ? (float)$new_b2b_small[$state][$gst]['tot_cgst'] : 0)  - (@$row2['tot_cgst'] ? (float)$row2['tot_cgst'] : 0);
            $new_b2b_small[$state][$gst]['tot_sgst'] = (@$new_b2b_small[$state][$gst]['tot_sgst'] ? (float)$new_b2b_small[$state][$gst]['tot_sgst'] : 0) - (@$row2['tot_cgst'] ? (float)$row2['tot_sgst'] : 0);
            $new_b2b_small[$state][$gst]['net_amount'] = (@$new_b2b_small[$state][$gst]['net_amount'] ? (float)$new_b2b_small[$state][$gst]['net_amount'] : 0)  - (@$row2['net_amount'] ? (float)$row2['net_amount'] : 0);
        }
    }

    $new_b2b_small['taxable'] = $new_b2b_small['taxable'] - $cdnr_total['taxable'];
    $new_b2b_small['tot_igst'] = $new_b2b_small['tot_igst'] - $cdnr_total['tot_igst'];
    $new_b2b_small['tot_sgst'] = $new_b2b_small['tot_sgst'] - $cdnr_total['tot_sgst'];
    $new_b2b_small['tot_cgst'] = $new_b2b_small['tot_cgst'] - $cdnr_total['tot_cgst'];
    $new_b2b_small['net_amount'] = $new_b2b_small['net_amount'] - $cdnr_total['net_amount'];

    // echo '<pre>';print_r($new_b2b_small);exit;

    return $new_b2b_small;
}

// function check_taxability($data){

//     $db = \Config\Database::connect();
//     if (session('DataSource')) {
//         $db->setDatabase(session('DataSource'));
//     }

//     $result_data = array();
//     $type1= array();
//     $type2= array();
//     $type3= array();
  

//     foreach($data as $row){
//         if(isset($row['v_type'])){  

//            $type1[] = $row['id'];
//         }else{
         
//             if(isset($row['return_no'])){
//                 $type2[] = $row['id'];
//             }else{
//                 $type3[] = $row['id'];
//             }
            
//         }
//     }

//     if(count($type1) > 0){
//         $builder =$db->table('sales_ACparticu si');
//         $builder->select('a.taxability');
//         $builder->join('account a','a.id = si.account');
//         $builder->whereIn('si.parent_id',$type1);   
//         $builder->where('si.is_delete',0);   
//         $builder->where('a.is_delete',0);
//         $query = $builder->get();
//         $result1 = $query->getResultArray();
//     }

//     if(count($type2) > 0){
//         $builder =$db->table('sales_item si');
//         $builder->select('si.*,i.taxability');
//         $builder->join('item i','i.id = si.item_id');
//         $builder->whereIn('si.parent_id',$type2);  
//         $builder->where('si.type','return');
//         $builder->where('si.is_delete',0);   
//         $builder->where('i.is_delete',0);
//         $query = $builder->get();
//         $result2 = $query->getResultArray();
//     }
//     if(count($type3) > 0){
//         $builder =$db->table('sales_item si');
//         $builder->select('si.*,i.taxability');
//         $builder->join('item i','i.id = si.item_id');
//         $builder->whereIn('si.parent_id',$type3);
//         $builder->where('si.type','invoice');
//         $builder->where('si.is_delete',0);   
//         $builder->where('i.is_delete',0);
//         $query = $builder->get();
//         $result3 = $query->getResultArray();
//     }

//     foreach($data as $row){
//         if(isset($row['v_type'])){
//             $find = search($result1,'parent_id',$row['id']);
//         }else{
         
//             if(isset($row['return_no'])){
//                 $find = search($result2,'parent_id',$row['id']);
//             }else{
//                 $find = search($result3,'parent_id',$row['id']);
//             }
            
//         }

//         $temp = array();
//             foreach($find as $row1){
                
//                 if($row1['taxability'] == 'Nill' && !in_array('Nill',$temp)){
//                    $temp[] = "Nill";
//                 }else if($row1['taxability'] == 'Exempt' && !in_array('Exempt',$temp)){
//                     // $is_exempt = 1;
//                     $temp[] = "Exempt";

//                 }else if($row1['taxability'] == 'Taxable' && !in_array('Taxable',$temp)){
//                     // $is_taxable = 1;
//                     $temp[] = "Taxable";
//                 }else{
//                     // $temp[] = 'N/A'; nai ave aaa to taxability su avse jyare kai na hoy tyare ..?
//                 }              
//             }

//             if(count($temp) == 0){
//                 $row['taxability'] = "N/A";
//             } else if(count($temp) == 1){
//                 if(in_array('Nill',$temp)){
//                     $row['taxability'] = 'Nill';
//                 }else if(in_array('Taxable',$temp)){
//                     $row['taxability'] = 'Taxable';
//                 }else{
//                     $row['taxability'] = 'Exempt';
//                 } 

//             } else if(count($temp) == 2){

//                 if(in_array('Nill',$temp) && in_array('Exempt',$temp)){
//                     $row['taxability'] = 'Exempt';
//                 }else if(in_array('Nill',$temp) && in_array('Taxable',$temp)){
//                     $row['taxability'] = 'Nill';
//                 }else if(in_array('Exempt',$temp) && in_array('Taxable',$temp)){
//                     $row['taxability'] = 'Exempt';
//                 } else{}

//             }else{
//                 $row['taxability'] = 'Exempt';
//             }

//         $result_data[] = $row;
  
//     }
   
//     return $result_data;
// }

function check_taxability($data){
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $result_data = array();

    foreach($data as $row){
        if(isset($row['v_type'])){  

            $builder =$db->table('sales_ACparticu si');
            $builder->select('a.taxability');
            $builder->join('account a','a.id = si.account');
            $builder->where('si.parent_id',$row['id']);   
            $builder->where('si.is_delete',0);   
            $builder->where('a.is_delete',0);
            $query = $builder->get();
            $result = $query->getResultArray();

            $is_taxable = 0;
            $is_nill = 0;
            $is_exempt = 0;
            $is_na = 0;

            foreach($result as $row1){
                if($row1['taxability'] == 'Nill'){
                    $is_nill=1;
                }else if($row1['taxability'] == 'Exempt'){
                    $is_exempt = 1;
                }else if($row1['taxability'] == 'Taxable'){
                    $is_taxable = 1;
                }else{
                    $is_na = 1;                    
                }
            }

            if($is_taxable == 1){
                $row['taxability'] = 'Taxable';
            }else if($is_nill == 1){
                $row['taxability'] = 'Nill';
            }else if($is_na == 1){
                $row['taxability'] = 'N/A';
            }else{
                $row['taxability'] = 'Exempt';
            }    

        }else{
           
        
            $builder =$db->table('sales_item si');
            $builder->select('si.*,i.taxability');
            $builder->join('item i','i.id = si.item_id');
            $builder->where('si.parent_id',$row['id']);  
            if(isset($row['return_no'])){
                $builder->where('si.type','return');
            }else{
                $builder->where('si.type','invoice');
            }
            $builder->where('si.is_delete',0);   
            $builder->where('i.is_delete',0);
            $query = $builder->get();
            $result = $query->getResultArray();

           
            $is_taxable = 0;
            $is_nill = 0;
            $is_exempt = 0;
            $is_na = 0;

            foreach($result as $row1){
                if($row1['taxability'] == 'Nill'){
                    $is_nill=1;
                }else if($row1['taxability'] == 'Exempt'){
                    $is_exempt = 1;
                }else if($row1['taxability'] == 'Taxable'){
                    $is_taxable = 1;
                }else{
                    $is_na = 1;
                }
            }

            if($is_taxable == 1){
                $row['taxability'] = 'Taxable';
            }else if($is_nill == 1){
                $row['taxability'] = 'Nill';
            }else if($is_na == 1){
                $row['taxability'] = 'N/A';
            }else{
                $row['taxability'] = 'Exempt';
            }    
        }

        $result_data[] = $row;
    }

    return $result_data;
}

function get_eligible_itc_nill($data){
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $gmodel = new App\Models\GeneralModel();

    $result_data = array();
    $non_gst =0;
    $exempt =0;
    $taxable =0;
    $igst = 0;
    $cgst = 0;
    $sgst = 0;
    $general_purchase_array=array();
    $purchase_invoice_array=array();

    $new_data=array();
    //$purchase_invoice_array=array();
    if(!empty($data))
    {
        foreach($data as $row){
            $exempt1 =0;
            $taxable1 =0;
            $igst1 = 0;
            $cgst1 = 0;
            $sgst1 = 0;

            if(isset($row['v_type'])){  

                $builder =$db->table('purchase_particu si');
                $builder->select('a.taxability,si.*');
                $builder->join('account a','a.id = si.account');
                $builder->where('si.parent_id',$row['id']);   
                $builder->where('si.is_delete',0);   
                $builder->where('a.is_delete',0);
                $query = $builder->get();
                $result = $query->getResultArray();

                $purchase_gen = array();

                foreach($result as $row1){
                
                    if($row1['taxability'] == 'N/A' || $row1['taxability'] == 'Nill' || $row1['taxability'] == 'Exempt' || $row1['taxability'] == ''){
                        $exempt += $row1['amount'];       
                    }else{
                        $taxes = $gmodel->get_data_table('purchase_general',array('id'=>$row1['parent_id']),'taxes');
                        $arr_taxes = json_decode($taxes['taxes']);

                        $gst = ($row1['amount']) * ($row1['igst']/100);
                        $taxable += $row1['amount'];
                        $taxable1 += $row1['amount'];

                        if(in_array('igst',$arr_taxes)){
                            $igst += $gst;
                            $igst1 += $gst;
                        }else{
                            $cgst += $gst/2;
                            $sgst += $gst/2;
                            $cgst1 += $gst/2;
                            $sgst1 += $gst/2;
                        }
                    }
                }

            }else{
                if($row['is_import'] == 0){
                    $builder =$db->table('purchase_item si');
                    $builder->select('si.*,i.taxability,i.non_gst');
                    $builder->join('item i','i.id = si.item_id');
                    $builder->where('si.parent_id',$row['id']);  
                    if(isset($row['return_no'])){
                        $builder->where('si.type','return');
                    }else{
                        $builder->where('si.type','invoice');
                    }
                    $builder->where('si.is_delete',0);   
                    $builder->where('i.is_delete',0);
                    $query = $builder->get();
                    $result = $query->getResultArray();

                    foreach($result as $row2){
                        if($row2['non_gst'] == 'yes'){
                            $non_gst += $row2['qty'] * $row2['rate'];
                        }else if($row2['taxability'] == 'N/A' || $row2['taxability'] == 'Nill' || $row2['taxability'] == 'Exempt' || $row2['taxability'] == ''){
                            $exempt += $row2['qty'] * $row2['rate'];
                            $exempt1 += $row2['qty'] * $row2['rate'];
                        }else{
                            $taxes = $gmodel->get_data_table('purchase_invoice',array('id'=>$row2['parent_id']),'taxes');
                            $arr_taxes = json_decode($taxes['taxes']);

                            $gst = ($row2['qty'] * $row2['rate']) * ($row2['igst']/100);
                            $taxable += $row2['qty'] * $row2['rate'];
                            $taxable1 += $row2['qty'] * $row2['rate'];

                            if(in_array('igst',$arr_taxes)){
                                $igst += $gst;
                                $igst1 += $gst;
                            }else{
                                $cgst += $gst/2;
                                $sgst += $gst/2;
                                $cgst1 += $gst/2;
                                $sgst1 += $gst/2;
                            }                       
                        }
                        
                    }
                }   
            } 
                $row['exempt_total']= $exempt1;
                $row['taxable_total']= $taxable1;
                $row['igst_total']= $igst1;
                $row['cgst_total']= $cgst1;
                $row['sgst_total']= $sgst1; 
            $new_data[] = $row;     
        }
    }
    
    $eligible['taxable'] = $taxable;
    $eligible['cgst'] = $cgst;
    $eligible['sgst'] = $sgst;
    $eligible['igst'] = $igst;
    $eligible['new_data'] = $new_data;

    $nill['non_gst'] = $non_gst; 
    $nill['exempt'] = $exempt;

    $result_data['eligible'] = $eligible;
    $result_data['nill'] = $nill;
    $result_data['data'] = $data;

    return $result_data;
}

function get_nill_detail($start_date = '', $end_date = ''){
    
    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $data = get_gstr1_detail(db_date($start_date),db_date($end_date));
    $nill =  $data['nill'];

     //echo '<pre>';print_r($nill);exit;
    // foreach($nill['data'] as $rw){
    //     if($rw['gst'] != '' &&  !empty($rw['gst'])){
    //         echo '<pre>';print_r($rw);
    //     }
    // }exit;
    
    // exit;
    // echo '<pre>';print_r($nill);exit;

    // $sale ="'sales' as type";
    // $gnrl_sale ="'gnrl_sales' as type";

    // $builder =$db->table('sales_invoice si');
    // $builder->select('si.*,ac.gst,ac.name,ac.gst_type,'.$sale);
    // $builder->join('account ac','ac.id = si.account');
    // $builder->where(array('si.is_delete' => 0));
    // $builder->where(array('si.is_cancle' => 0));
    // $builder->where(array('DATE(si.invoice_date)  >= ' => db_date($start_date)));
    // $builder->where(array('DATE(si.invoice_date)  <= ' => db_date($end_date)));
    // $query = $builder->get();
    // $sales_invoice = $query->getResultArray();

    // $builder =$db->table('sales_ACinvoice sa');
    // $builder->select('sa.*,ac.gst,ac.name,ac.gst_type,'.$gnrl_sale);
    // $builder->join('account ac','ac.id = sa.party_account');
    // $builder->where(array('v_type' => 'general'));
    // $builder->where(array('sa.is_delete' => 0));
    // $builder->where(array('sa.is_cancle' => 0));
    // $builder->where(array('DATE(sa.invoice_date)  >= ' => db_date($start_date)));
    // $builder->where(array('DATE(sa.invoice_date)  <= ' => db_date($end_date)));
    // $query = $builder->get();
    // $salesAcinvoice = $query->getResultArray();

    // $sales = array_merge($salesAcinvoice,$sales_invoice);

    // //$sales = check_taxability($sales); 

    // $b2c =array();
    // $b2b =array();
    // $b2cSmall = array();
    // $b2cLarge = array();
    // $nill = array();

    // $b2b['data'] = array();
    // $b2cSmall['data'] = array();
    // $b2cLarge['data'] = array();
    // $nill['data'] = array();


    // for($i=0;$i<count($sales);$i++){
    //     if($sales[$i]['inv_taxability'] == 'Nill' || $sales[$i]['inv_taxability'] == 'Exempt'){
    //         $nill['data'][] = $sales[$i];
    //     }
    // }


    // $builder =$db->table('sales_return si');
    // $builder->select('si.*,ac.gst,ac.name,ac.gst_type,'.$sale);
    // $builder->join('account ac','ac.id = si.account');
    // $builder->where(array('si.is_delete' => 0));
    // $builder->where(array('si.is_cancle' => 0));
    // $builder->where(array('DATE(si.return_date)  >= ' => db_date($start_date)));
    // $builder->where(array('DATE(si.return_date)  <= ' => db_date($end_date)));
    // $query = $builder->get();
    // $sales_return = $query->getResultArray();

    // $builder =$db->table('sales_ACinvoice sa');
    // $builder->select('sa.*,ac.gst,ac.name,ac.gst_type,'.$gnrl_sale);
    // $builder->join('account ac','ac.id = sa.party_account');
    // $builder->where(array('v_type' => 'return'));
    // $builder->where(array('sa.is_delete' => 0));
    // $builder->where(array('sa.is_cancle' => 0));
    // $builder->where(array('DATE(sa.invoice_date)  >= ' => db_date($start_date)));
    // $builder->where(array('DATE(sa.invoice_date)  <= ' => db_date($end_date)));
    // $query = $builder->get();
    // $salesAcinvoice_return = $query->getResultArray();


    // $sales_return = array_merge($salesAcinvoice_return,$sales_return);
    // //$sales_return = check_taxability($sales_return); 

    // for($i=0;$i<count($sales_return);$i++){
    //     if($sales_return[$i]['inv_taxability'] == 'Nill' || $sales_return[$i]['inv_taxability'] == 'Exempt'){
    //         $nill['data'][] = $sales_return[$i];
    //     }
    // }


    $inter_unreg=array();
    $intera_unreg=array();
    $intera_reg=array();
    $inter_reg=array();

    $inter_unreg['data'] = array();
    $intera_unreg['data'] = array();
    $intera_reg['data'] = array();
    $inter_reg['data'] = array();

  

    for($i=0;$i<count($nill['data']);$i++){

        if(isset($nill['data'][$i]['v_type'])){
            $nill['data'][$i]['type'] = 'gnrl_sales';
        }else{
            $nill['data'][$i]['type'] = 'sales';
        }


        if(@$nill['data'][$i]['gst'] == '' || empty($nill['data'][$i]['gst'])){
            if($nill['data'][$i]['acc_state'] == session('state')) {
                $intera_unreg['data'][] = $nill['data'][$i];          
            }else{
                $inter_unreg['data'][] = $nill['data'][$i];          
            }
        }else{
            if($nill['data'][$i]['acc_state'] == session('state')) {
                $intera_reg['data'][] = $nill['data'][$i];          
            }else{
                $inter_reg['data'][] = $nill['data'][$i];          
            }
        }
    }

    $intera_reg['count'] = count($intera_reg['data']);
    $intera_reg['taxable_amount'] =0;
    $intera_reg['net_amount'] =0;

    if(!empty($intera_reg['data'])){
        foreach($intera_reg['data'] as $row){
            if(isset($row['return_no']) || @$row['v_type'] == 'return'){
                $intera_reg['taxable_amount'] -= $row['taxable'];
                $intera_reg['net_amount'] -= $row['net_amount'];
            }else{
                $intera_reg['taxable_amount'] += $row['taxable'];
                $intera_reg['net_amount'] += $row['net_amount'];
            }
        }
    }

    $inter_reg['count'] = count($inter_reg['data']);
    $inter_reg['taxable_amount'] =0;
    $inter_reg['net_amount'] =0;

    if(!empty($inter_reg['data'])){
        foreach($inter_reg['data'] as $row){
            if(isset($row['return_no']) || @$row['v_type'] == 'return'){
                $inter_reg['taxable_amount'] -= $row['taxable'];
                $inter_reg['net_amount'] -= $row['net_amount'];
            }else{
                $inter_reg['taxable_amount'] += $row['taxable'];
                $inter_reg['net_amount'] += $row['net_amount'];
            }
        }
    }

    $inter_unreg['count'] = count($inter_unreg['data']);
    $inter_unreg['taxable_amount'] =0;
    $inter_unreg['net_amount'] =0;

    if(!empty($inter_unreg['data'])){
        foreach($inter_unreg['data'] as $row){
            if(isset($row['return_no']) || @$row['v_type'] == 'return'){
                $inter_unreg['taxable_amount'] -= $row['taxable'];
                $inter_unreg['net_amount'] -= $row['net_amount'];
            }else{
                $inter_unreg['taxable_amount'] += $row['taxable'];
                $inter_unreg['net_amount'] += $row['net_amount'];
            }
        }
    }

    $intera_unreg['count'] = count($intera_unreg['data']);
    $intera_unreg['taxable_amount']=0;
    $intera_unreg['net_amount']=0;

    if(!empty($intera_unreg['data'])){
        foreach($intera_unreg['data'] as $row){
            if(isset($row['return_no']) || @$row['v_type'] == 'return'){
                $intera_unreg['taxable_amount'] -= $row['taxable'];
                $intera_unreg['net_amount'] -= $row['net_amount'];
            }else{
                $intera_unreg['taxable_amount'] += $row['taxable'];
                $intera_unreg['net_amount'] += $row['net_amount'];
            }
        }
    }

    $nill_data = array(
        'inter_unreg' =>  $inter_unreg,
        'inter_reg' => $inter_reg,
        'intera_unreg' => $intera_unreg,
        'intera_reg' => $intera_reg,
        'start_date' => user_date($start_date),
        'end_date' => user_date($end_date)
    );

    // echo '<pre>';print_r($nill_data);exit;
    return $nill_data;

}

function get_b2b_b2c_detail($start_date = '', $end_date = ''){

    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder =$db->table('sales_invoice si');
    $builder->select('si.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = si.account');
    $builder->where(array('si.is_delete' => 0));
    $builder->where(array('si.is_cancle' => 0));
    $builder->where(array('DATE(si.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(si.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $sales_invoice = $query->getResultArray();

    $builder =$db->table('sales_ACinvoice sa');
    $builder->select('sa.*,ac.gst,ac.name,ac.gst_type');
    $builder->join('account ac','ac.id = sa.party_account');
    $builder->where(array('v_type' => 'general'));
    $builder->where(array('sa.is_delete' => 0));
    $builder->where(array('sa.is_cancle' => 0));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => $start_date));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => $end_date));
    $query = $builder->get();
    $salesAcinvoice = $query->getResultArray();

    $sales['data'] = array();
    $sales_b2cSmall['data'] = array();
    $sales_b2cLarge['data'] = array();
    $gnrl_sales_b2cSmall['data'] = array();
    $gnrl_sales_b2cLarge['data'] = array();
    $sales_nill['data'] = array();

    $gmodel = new App\Models\GeneralModel();


    // for($i=0;$i<count($sales_invoice);$i++){
    //     $state = $gmodel->get_data_table('states',array('id'=>$sales_invoice[$i]['acc_state']),'name');
        
    //     $sales_invoice[$i]['state_name'] = @$state['name'];

    //     if(@$sales_invoice[$i]['inv_taxability'] == 'Nill' || @$sales_invoice[$i]['inv_taxability'] == 'Exempt' ){
    //         $sales_nill['data'][] = $sales_invoice[$i];
    //     }elseif(@$sales_invoice[$i]['gst'] == '' || empty($sales_invoice[$i]['gst'])){
    //         if($sales_invoice[$i]['taxable'] < 250000){
    //             $sales_b2cSmall['data'][] = $sales_invoice[$i];
    //         }else{
    //             $sales_b2cLarge['data'][] = $sales_invoice[$i];
    //         }
    //     }else{
    //         $sales['data'][] = $sales_invoice[$i];
    //     }
    // }

    foreach($sales_invoice as $row){

        $sale = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'invoice'), '*,taxability,igst,rate,qty,item_disc as disc');        
        $state = $gmodel->get_data_table('states',array('id'=>$row['acc_state']),'name');
        $row['state_name'] = @$state['name'];
    
        if($row['gst'] == '' || empty($row['gst'])){

            $invtaxable = 0;
            $tot_igst = 0;
            $tot_cgst = 0;
            $tot_sgst = 0;
            $invoice_amt =0;
    
            $taxable_item_arr = $row;
            $i=0;
            foreach($sale as $row1){

                if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt'){
                    
                    $total = $row1['qty'] * $row1['rate'];
                    $invtaxable +=  $total;
                    $tot_igst +=  (float)$total * (float)$row1['igst'] / 100;
                    $tot_cgst += (float)$tot_igst / 2;
                    $tot_sgst += (float)$tot_igst / 2;
                    
                    $invoice_amt = $invtaxable + $tot_igst;  
    
                    $taxable_item_arr['taxable'] = $invtaxable; 
                    $taxable_item_arr['tot_igst'] = $tot_igst; 
                    $taxable_item_arr['tot_sgst'] = $tot_cgst; 
                    $taxable_item_arr['tot_cgst'] = $tot_sgst; 
                    $taxable_item_arr['tot_cgst'] = $invoice_amt;   
                    $i++;
                }
            }
            if($i > 0){
                if($taxable_item_arr['taxable'] < 250000){
                    $sales_b2cSmall['data'][] = $taxable_item_arr;
                }else{
                    $sales_b2cLarge['data'][] = $taxable_item_arr;
                }
            }
        }else{
            if($row['inv_taxability'] != 'Nill' && $row['inv_taxability'] != 'Exempt'){ 
                
                $disc =0;
                $item_taxable =0;
                
                foreach($sale as $row2){
                        $total1 = 0;
                    // if($row2['taxability'] != 'Exempt' && $row2['taxability'] != 'Nill'){
                        if(isset($row2['disc']) && $row2['disc'] > 0 ){
                            $total = $row2['qty'] * $row2['rate'];
                            $disc_amt = ($total * (float)$row2['disc'])/100;
                            $total1 = $total - $disc_amt; 
                            $item_taxable += $total1;
                        }else{
                            $total = $row2['qty'] * $row2['rate'];
                            $item_taxable +=  $total;
                        }
                        $row['igst'] = $row2['igst'];     
                    // }
                }
                
                if($row['discount'] > 0  && $row['discount'] != '' ){
                        $row['taxable'] = $row['total_amount'];
                }else{
                        $row['taxable'] = $item_taxable;
                }

                $sales['data'][] = $row;
            }
        }        
    }

    $gnrl_sale['data'] = array();
    $gnrl_sale_nill['data'] = array();

    // for($i=0;$i<count($salesAcinvoice);$i++){
    //     $state = $gmodel->get_data_table('states',array($salesAcinvoice[$i]['acc_state']),'name');
    //     $salesAcinvoice[$i]['state_name'] = @$state['name'];

    //     if(@$salesAcinvoice[$i]['inv_taxability'] == 'Nill' || @$sales_invoice[$i]['inv_taxability'] == 'Exempt'){
    //         $gnrl_sale_nill['data'][] = $salesAcinvoice[$i];
    //     }elseif(@$salesAcinvoice[$i]['gst'] == '' || empty($salesAcinvoice[$i]['gst'])){
    //         if($salesAcinvoice[$i]['taxable'] < 250000){
    //             $gnrl_sales_b2cSmall['data'][] = $salesAcinvoice[$i];
    //         }else{
    //             $gnrl_sales_b2cLarge['data'][] = $salesAcinvoice[$i];
    //         }
    //     }else{
    //         $gnrl_sale['data'][] = $salesAcinvoice[$i];
    //     }
    // }

    foreach($salesAcinvoice as $row){

        $gnrl_sale = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0),'taxability,igst, amount as total');

        $state = $gmodel->get_data_table('states',array('id'=>$row['acc_state']),'name');
        $row['state_name'] = @$state['name'];

        
        if($row['gst'] == '' || empty($row['gst'])){
            $gnrl_invtaxable = 0;
            $gnrl_tot_igst = 0;
            $gnrl_tot_cgst = 0;
            $gnrl_tot_sgst = 0;
            $gnrl_invoice_amt =0;
    
            $taxable_ac_item_arr = $row;
            $i=0;
            foreach($sale as $row1){
                if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt'){
                    
                    $gnrl_invtaxable +=  (float)@$row1['total'];
                    $gnrl_tot_igst +=  (float)@$row1['total'] * (float)@$row1['igst'] / 100;
                    $gnrl_tot_cgst += (float)@$gnrl_tot_igst / 2;
                    $gnrl_tot_sgst += (float)@$gnrl_tot_igst / 2;
                    
                    $invoice_amt = $invtaxable + $tot_igst;  
    
                    $taxable_ac_item_arr['taxable'] = $invtaxable; 
                    $taxable_ac_item_arr['tot_igst'] = $tot_igst; 
                    $taxable_ac_item_arr['tot_sgst'] = $tot_cgst; 
                    $taxable_ac_item_arr['tot_cgst'] = $tot_sgst; 
                    $taxable_ac_item_arr['tot_cgst'] = $invoice_amt;
                    $i++;   
                }
            }
            if($i > 0){
                if($taxable_ac_item_arr['taxable'] < 250000){
                    $gnrl_sales_b2cSmall['data'][] = $taxable_ac_item_arr;
                }else{
                    $gnrl_sales_b2cLarge['data'][] = $taxable_ac_item_arr;
                }
            }
        }else{
            if($row['inv_taxability'] != 'Nill' && $row['inv_taxability'] != 'Exempt'){
                $gnrl_sale['data'][] = $row;
            }
        }        
    }

    $gnrl_sale['igst'] = 0;
    $gnrl_sale['cgst'] = 0;
    $gnrl_sale['sgst'] = 0;
    $gnrl_sale['cess'] = 0;
    $gnrl_sale['count'] = count(@$gnrl_sale['data']);
    $gnrl_sale['taxable_amount'] = 0;
    $gnrl_sale['net_amount'] = 0;

    $taxes = array();

    if(!empty($gnrl_sale['data'])){
        foreach($gnrl_sale['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $gnrl_sale['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_sale['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_sale['cess'] += (float)$row['cess'];
                }else{
                    $gnrl_sale['igst'] += (float)$row['tot_igst'];
                }
            }
            $gnrl_sale['taxable_amount'] += (float)$row['taxable'];
            $gnrl_sale['net_amount'] += (float)$row['net_amount'];
        }
    }


    $gnrl_sales_b2cSmall['igst'] = 0;
    $gnrl_sales_b2cSmall['cgst'] = 0;
    $gnrl_sales_b2cSmall['sgst'] = 0;
    $gnrl_sales_b2cSmall['cess'] = 0;
    $gnrl_sales_b2cSmall['count'] = count(@$gnrl_sales_b2cSmall['data']);
    $gnrl_sales_b2cSmall['taxable_amount'] = 0;
    $gnrl_sales_b2cSmall['net_amount'] = 0;

    $taxes = array();

    if(!empty($gnrl_sales_b2cSmall['data'])){
        foreach($gnrl_sales_b2cSmall['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $gnrl_sales_b2cSmall['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_sales_b2cSmall['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_sales_b2cSmall['cess'] += (float)$row['cess'];
                }else{
                    $gnrl_sales_b2cSmall['igst'] += (float)$row['tot_igst'];
                }
            }
            $gnrl_sales_b2cSmall['taxable_amount'] += (float)$row['taxable'];
            $gnrl_sales_b2cSmall['net_amount'] += (float)$row['net_amount'];
        }
    }


    $gnrl_sales_b2cLarge['igst'] = 0;
    $gnrl_sales_b2cLarge['cgst'] = 0;
    $gnrl_sales_b2cLarge['sgst'] = 0;
    $gnrl_sales_b2cLarge['cess'] = 0;
    $gnrl_sales_b2cLarge['count'] = count(@$gnrl_sales_b2cLarge['data']);
    $gnrl_sales_b2cLarge['taxable_amount'] = 0;
    $gnrl_sales_b2cLarge['net_amount'] = 0;

    $taxes = array();

    if(!empty($gnrl_sales_b2cLarge['data'])){
        foreach($gnrl_sales_b2cLarge['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $gnrl_sales_b2cLarge['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $gnrl_sales_b2cLarge['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $gnrl_sales_b2cLarge['cess'] += (float)$row['cess'];
                }else{
                    $gnrl_sales_b2cLarge['igst'] += (float)$row['tot_igst'];
                }
            }
            $gnrl_sales_b2cLarge['taxable_amount'] += (float)$row['taxable'];
            $gnrl_sales_b2cLarge['net_amount'] += (float)$row['net_amount'];
        }
    }

    $sales['igst'] = 0;
    $sales['cgst'] = 0;
    $sales['sgst'] = 0;
    $sales['cess'] = 0;
    $sales['count'] = count(@$sales['data']);
    $sales['taxable_amount'] = 0;
    $sales['net_amount'] = 0;

    $taxes = array();
    
    if(!empty($sales['data'])){
        foreach($sales['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $sales['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $sales['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $sales['cess'] += (float)$row['cess'];
                }else{
                    $sales['igst'] += (float)$row['tot_igst'];
                }
            }
            $sales['taxable_amount'] += (float)$row['taxable'];
            $sales['net_amount'] += (float)$row['net_amount'];
        }
    }

    $sales_b2cLarge['igst'] = 0;
    $sales_b2cLarge['cgst'] = 0;
    $sales_b2cLarge['sgst'] = 0;
    $sales_b2cLarge['cess'] = 0;
    $sales_b2cLarge['count'] = count(@$sales_b2cLarge['data']);
    $sales_b2cLarge['taxable_amount'] = 0;
    $sales_b2cLarge['net_amount'] = 0;

    $taxes = array();
    
    if(!empty($sales_b2cLarge['data'])){
        foreach($sales_b2cLarge['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $sales_b2cLarge['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $sales_b2cLarge['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $sales_b2cLarge['cess'] += (float)$row['cess'];
                }else{
                    $sales_b2cLarge['igst'] += (float)$row['tot_igst'];
                }
            }
            $sales_b2cLarge['taxable_amount'] += (float)$row['taxable'];
            $sales_b2cLarge['net_amount'] += (float)$row['net_amount'];
        }
    }

    $sales_b2cSmall['igst'] = 0;
    $sales_b2cSmall['cgst'] = 0;
    $sales_b2cSmall['sgst'] = 0;
    $sales_b2cSmall['cess'] = 0;
    $sales_b2cSmall['count'] = count(@$sales_b2cSmall['data']);
    $sales_b2cSmall['taxable_amount'] = 0;
    $sales_b2cSmall['net_amount'] = 0;

    $taxes = array();
    
    if(!empty($sales_b2cSmall['data'])){
        foreach($sales_b2cSmall['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $sales_b2cSmall['cgst'] += (float)$row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $sales_b2cSmall['sgst'] += (float)$row['tot_sgst'];
                }else if($tax == 'cess'){
                    $sales_b2cSmall['cess'] += (float)$row['cess'];
                }else{
                    $sales_b2cSmall['igst'] += (float)$row['tot_igst'];
                }
            }
            $sales_b2cSmall['taxable_amount'] += (float)$row['taxable'];
            $sales_b2cSmall['net_amount'] += (float)$row['net_amount'];
        }
    }

    $data['sale'] = $sales;
    $data['gnrl_sale'] = $gnrl_sale;

    $data['sale_b2c_small'] = $sales_b2cSmall;
    $data['gnrl_sale_b2c_small'] = $gnrl_sales_b2cSmall;
    
    $data['sales_b2c_large'] = $sales_b2cLarge;
    $data['gnrl_sale_b2c_large'] = $gnrl_sales_b2cLarge;
    
    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;

    return $data;  
}

function get_cr_dr_detail($start_date = '', $end_date = ''){

    if($start_date == '') {
        if (date('m') <= '03') {
            $year = date('Y') - 1;
            $start_date = $year . '-04-01';
        } else {
            $year = date('Y');
            $start_date = $year . '-04-01';
        }
    }
    
    if($end_date == '') {

        if (date('m') <= '03') {
            $year = date('Y');
        } else {
            $year = date('Y') + 1;
        }
        $end_date = $year . '-03-31';
    }
    
    $db = \Config\Database::connect();
    if (session('DataSource')) {
        $db->setDatabase(session('DataSource'));
    }

    $builder =$db->table('sales_return sr');
    $builder->select('sr.*,ac.gst,ac.name,sr.return_no as invoice_no,sr.acc_state as state');
    $builder->join('account ac','ac.id = sr.account');
    $builder->where(array('sr.is_delete' => 0));
    $builder->where(array('sr.is_cancle' => 0));
    $builder->where(array('DATE(sr.return_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(sr.return_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $sale_return = $query->getResultArray();

    $builder =$db->table('sales_ACinvoice sa');
    $builder->select('sa.*,ac.gst,ac.name,sa.total_amount as total,sa.acc_state as state');
    $builder->join('account ac','ac.id = sa.party_account');
    $builder->where(array('v_type' => 'return'));
    $builder->where(array('sa.is_delete' => 0));
    $builder->where(array('sa.is_cancle' => 0));
    $builder->where(array('DATE(sa.invoice_date)  >= ' => db_date($start_date)));
    $builder->where(array('DATE(sa.invoice_date)  <= ' => db_date($end_date)));
    $query = $builder->get();
    $ac_return = $query->getResultArray();


    //-------- SALE RETURN ---------//

    $sale_return_UnReg=array();
    $sale_return_Reg=array();

    $sale_return_UnReg['data'] = array();
    $sale_return_UnReg['cgst']=0;
    $sale_return_UnReg['sgst']=0;
    $sale_return_UnReg['igst']=0;
    $sale_return_UnReg['cess']=0;
    $sale_return_UnReg['taxable_amount']=0;
    $sale_return_UnReg['net_amount'] = 0;

    $sale_return_Reg['data'] = array();
    $sale_return_Reg['cgst'] =0;
    $sale_return_Reg['sgst'] =0;
    $sale_return_Reg['igst'] =0;
    $sale_return_Reg['cess'] =0;
    $sale_return_Reg['taxable_amount'] = 0;
    $sale_return_Reg['net_amount'] = 0;

    $gmodel = new App\Models\GeneralModel();

    // for($i=0;$i<count($sale_return);$i++) {
    //     $state = $gmodel->get_data_table('states',array('id'=>$sale_return[$i]['state']),'name');
    //     $sale_return[$i]['state_name'] = @$state['name'];

    //     if($sale_return[$i]['inv_taxability'] != 'Nill' AND $sale_return[$i]['inv_taxability'] != 'Exempt'){
    //         if(@$sale_return[$i]['gst'] == '' || empty($sale_return[$i]['gst']) ){
    //             $sale_return_UnReg['data'][] = $sale_return[$i];
    //         }else{
    //             $sale_return_Reg['data'][] = $sale_return[$i];
    //         }
    //     }
    // }

    foreach($sale_return as $row){

        $sale_ret = $gmodel->get_array_table('sales_item', array('parent_id' =>$row['id'], 'is_delete' => 0, 'type' => 'return'), 'taxability,igst,(rate*qty) as total');
        
        $state = $gmodel->get_data_table('states',array('id'=>$row['state']),'name');
        $row['state_name'] = @$state['name'];
        
        if($row['gst'] == '' || empty($row['gst'])){

            $crdr_invtaxable = 0;
            $crdr_tot_igst = 0;
            $crdr_tot_cgst = 0;
            $crdr_tot_sgst = 0;
            $crdr_invoice_amt =0;
    
            $taxable_item_arr = $row;
            $i=0;
            foreach($sale_ret as $row1){
                if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt'){

                    if($row['acc_state'] != session('state')){
                    
                        $crdr_invtaxable +=  (float)$row1['total'];
                        $crdr_tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                        $crdr_tot_cgst += (float)$crdr_tot_igst / 2;
                        $crdr_tot_sgst += (float)$crdr_tot_igst / 2;
                        
                        $crdr_invoice_amt = $crdr_invtaxable + $crdr_tot_igst;  
        
                        $taxable_item_arr['taxable'] = $crdr_invtaxable; 
                        $taxable_item_arr['tot_igst'] = $crdr_tot_igst; 
                        $taxable_item_arr['tot_sgst'] = $crdr_tot_cgst; 
                        $taxable_item_arr['tot_cgst'] = $crdr_tot_sgst; 
                        $taxable_item_arr['tot_cgst'] = $crdr_invoice_amt;   
                        $i++;
                    }
                }
            }
            if($i >0){
                $sale_return_UnReg['data'][] = $taxable_item_arr;
            }
        }else{
            if($row['inv_taxability'] != 'Nill' && $row['inv_taxability'] != 'Exempt'){
                $sale_return_Reg['data'][] = $row;
            }
        }        
    }

    $sale_return_Reg['count'] =count($sale_return_Reg['data']);
    $sale_return_UnReg['count'] = count($sale_return_UnReg['data']);

    if(!empty($sale_return_UnReg['data'])){
        foreach($sale_return_UnReg['data'] as $row){
            
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $sale_return_UnReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $sale_return_UnReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $sale_return_UnReg['cess'] += $row['cess'];
                }else{
                    $sale_return_UnReg['igst'] += $row['tot_igst'];
                }
            }
            $sale_return_UnReg['taxable_amount'] += $row['taxable'];
            $sale_return_UnReg['net_amount'] += $row['net_amount'];

        }
    }

    if(!empty($sale_return_Reg['data'])){
        foreach($sale_return_Reg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){

                if($tax == 'cgst'){                    
                    $sale_return_Reg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $sale_return_Reg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $sale_return_Reg['cess'] += $row['cess'];
                }else{
                    $sale_return_Reg['igst'] += $row['tot_igst'];
                }
            }
            $sale_return_Reg['taxable_amount'] += $row['taxable'];
            $sale_return_Reg['net_amount'] += $row['net_amount'];
        }
    }

    //--------- END SALE RETURN------------//



    //--------- START General SALE RETURN------------//

    $ac_return_UnReg=array();
    $ac_return_Reg=array();

    $ac_return_UnReg['data'] = array();
    $ac_return_UnReg['cgst']=0;
    $ac_return_UnReg['sgst']=0;
    $ac_return_UnReg['igst']=0;
    $ac_return_UnReg['cess']=0;
    $ac_return_UnReg['net_amount']=0;
    $ac_return_UnReg['taxable_amount']=0;

    $ac_return_Reg['data'] = array();
    $ac_return_Reg['cgst'] =0;
    $ac_return_Reg['sgst'] =0;
    $ac_return_Reg['igst'] =0;
    $ac_return_Reg['cess'] =0;
    $ac_return_Reg['net_amount']=0;
    $ac_return_Reg['taxable_amount'] =0;


    // for($i=0;$i<count($ac_return);$i++){

    //     $state = $gmodel->get_data_table('states',array('id'=>$ac_return[$i]['state']),'name');
    //     $ac_return[$i]['state_name'] = @$state['name'];

    //     if(@$ac_return[$i]['inv_taxability'] != 'Nill' AND @$ac_return[$i]['inv_taxability'] != 'Exempt'){
        
    //         if(@$ac_return[$i]['gst'] == '' || empty($ac_return[$i]['gst']) ){
    //             $ac_return_UnReg['data'][] = $ac_return[$i];
    //         }else{
    //             $ac_return_Reg['data'][] = $ac_return[$i];
    //         }
    //     }
    // }

    foreach($ac_return as $row){

        $gnrl_sale_ret = $gmodel->get_array_table('sales_ACparticu', array('parent_id' => $row['id'], 'is_delete' => 0),'taxability,igst, amount as total');
        
        $state = $gmodel->get_data_table('states',array('id'=>$row['state']),'name');
        $row['state_name'] = @$state['name'];


        if($row['gst'] == '' || empty($row['gst'])){

            $crdr_invtaxable = 0;
            $crdr_tot_igst = 0;
            $crdr_tot_cgst = 0;
            $crdr_tot_sgst = 0;
            $crdr_invoice_amt =0;

            $taxable_gnrl_item_arr = $row;
            $j=0;
            foreach($gnrl_sale_ret as $row1){
                if($row1['taxability'] != 'Nill' && $row1['taxability'] != 'Exempt'){
                    if($row['acc_state'] != session('state')){
                        $crdr_invtaxable +=  (float)$row1['total'];
                        $crdr_tot_igst +=  (float)$row1['total'] * (float)$row1['igst'] / 100;
                        $crdr_tot_cgst += (float)$crdr_tot_igst / 2;
                        $crdr_tot_sgst += (float)$crdr_tot_igst / 2;
                        
                        $crdr_invoice_amt = $crdr_invtaxable + $crdr_tot_igst;  

                        $taxable_gnrl_item_arr['taxable'] = $crdr_invtaxable; 
                        $taxable_gnrl_item_arr['tot_igst'] = $crdr_tot_igst; 
                        $taxable_gnrl_item_arr['tot_sgst'] = $crdr_tot_cgst; 
                        $taxable_gnrl_item_arr['tot_cgst'] = $crdr_tot_sgst; 
                        $taxable_gnrl_item_arr['tot_cgst'] = $crdr_invoice_amt;   
                        $j++;
                    }
                }
            }
            if($j > 0){
                $ac_return_UnReg['data'][] = $taxable_gnrl_item_arr;
            }
        }else{
            if($row['inv_taxability'] != 'Nill' && $row['inv_taxability'] != 'Exempt'){
                $ac_return_Reg['data'][] = $row;
            }
        }        
    }

    $ac_return_UnReg['count'] = count($ac_return_UnReg['data']);
    $ac_return_Reg['count'] = count($ac_return_Reg['data']);

    if(!empty($ac_return_UnReg['data'])){
        foreach($ac_return_UnReg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){
                if($tax == 'cgst'){
                    $ac_return_UnReg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $ac_return_UnReg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $ac_return_UnReg['cess'] += $row['cess'];
                }else{
                    $ac_return_UnReg['igst'] += $row['tot_igst'];
                }
            }
            $ac_return_UnReg['taxable_amount'] += $row['taxable'];
            $ac_return_UnReg['net_amount'] += $row['net_amount'];
        }
    }

    if(!empty($ac_return_Reg['data'])){
        foreach($ac_return_Reg['data'] as $row){
            $taxes = json_decode($row['taxes']);
            foreach($taxes as $tax){

                if($tax == 'cgst'){                    
                    $ac_return_Reg['cgst'] += $row['tot_cgst'];
                }else if($tax == 'sgst'){
                    $ac_return_Reg['sgst'] += $row['tot_sgst'];
                }else if($tax == 'cess'){
                    $ac_return_Reg['cess'] += $row['cess'];
                }else{
                    $ac_return_Reg['igst'] += $row['tot_igst'];
                }
            }
            $ac_return_Reg['taxable_amount'] += $row['taxable'];
            $ac_return_Reg['net_amount'] += $row['net_amount'];
        }
    }

    //--------- END General SALE RETURN------------//

    $gstr1 = array(
        'ac_return_Reg' => $ac_return_Reg,
        'ac_return_UnReg' => $ac_return_UnReg,
        'sale_return_UnReg' =>$sale_return_UnReg,
        'sale_return_Reg' =>$sale_return_Reg,
        'start_date' => user_date($start_date),
        'end_date' => user_date($end_date) 
    );
   
    return $gstr1;
}


?>