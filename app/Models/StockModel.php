<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\GeneralModel;
use \Hermawan\DataTables\DataTable;


class StockModel extends Model
{
    public function get_item_stock_data($get,$post){
        
        $dt_search = array(
            "id",
            "name",
            "hsn", 
        ); 

        $dt_col = array(
            "id",
            "name",
            "hsn",
        );
    
        $filter = $get['filter_data'];
        $tablename = "item";
        $where = '';
        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }
        $where .= " and is_delete=0 and type != 'Grey'";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
        
        foreach ($rResult['table'] as $row) {
            
            if(empty($post)){
                $sale =SaleItemSTock($row['id']);
                $purchase =PurchaseItemSTock($row['id']);
                $sale_pur =sale_purchase_itm_total();
            }else{
                $sale =SaleItemSTock($row['id'],@$post['from'],@$post['to']);
                $purchase =PurchaseItemSTock($row['id'],@$post['from'],@$post['to']);
                $sale_pur =sale_purchase_itm_total(@$post['from'],@$post['to']);
            }

            if($purchase['itm']['total_qty'] != 0 ){
                $diff_total =   number_format(($purchase['itm']['total_rate'] / $purchase['itm']['total_qty']) * ($purchase['itm']['total_qty'] - $sale['itm']['total_qty']),2);    
            }else{
                $diff_total =   number_format(1 * ($purchase['itm']['total_qty'] - $sale['itm']['total_qty']),2);    
            }
            
            $DataRow = array();
            
            $DataRow[] = $row['id'];
            $DataRow[] = $row['name'].' ('.$row['hsn'].')';
            $DataRow[] = $purchase['itm']['total_qty'];
            $DataRow[] = number_format($purchase['itm']['total_rate'],2);
            $DataRow[] = $sale['itm']['total_qty'];
            $DataRow[] = number_format($sale['itm']['total_rate'],2);
            $DataRow[] = $purchase['itm']['total_qty'] - $sale['itm']['total_qty'];
            $DataRow[] = $diff_total; 
           
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }

    public function get_Gray_voucher_byitem($id,$post){

        $grayvoucher = "'Gray Challan' as voucher_type,";
        $millvoucher = "'Mill Issue' as voucher_type,";
        $grayReturnvoucher = "'Gray Return' as voucher_type,";
        $millReturnvoucher = "'Mill Return' as voucher_type,";

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select($grayvoucher.'g.id,g.challan_date as date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(gi.pcs) as pcs,SUM(gi.meter) as meter');
        $builder->join('grayChallan_item gi','gi.pid ='.$id);
        $builder->join('grey_challan g','g.id = gi.voucher_id');
        $builder->join('account ac','ac.id =g.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(gi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(gi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(gi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('gi.purchase_type','Gray');
        $builder->where('g.is_delete',0);
        $builder->where('g.is_cancle',0);
        $builder->groupBy('g.id');
        $query = $builder->get();
        $gray = $query->getResultArray();

        $builder = $db->table('item i');
        $builder->select($millvoucher.'m.id,m.challan_date as date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(mi.pcs) as pcs,SUM(mi.meter) as meter');
        $builder->join('mill_item mi','mi.pid ='.$id);
        $builder->join('mill_challan m','m.id =mi.voucher_id');
        $builder->join('account ac','ac.id =m.mill_ac');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(mi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(mi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(mi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('m.is_delete',0);
        $builder->where('m.is_cancle',0);
        $builder->groupby('m.id');
        $query = $builder->get();
        $mill = $query->getResultArray();


        $builder = $db->table('item i');
        $builder->select($grayReturnvoucher.'rg.id,rg.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rgi.ret_taka) as pcs,SUM(rgi.ret_meter) as meter');
        $builder->join('retGrayFinish_item rgi','rgi.pid ='.$id);
        $builder->join('retGrayFinish rg','rg.id =rgi.voucher_id');
        $builder->join('account ac','ac.id = rg.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rgi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rgi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rgi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('rgi.purchase_type','Gray');
        $builder->where('rg.is_delete',0);
        $builder->where('rg.is_cancle',0);
        $builder->groupby('rg.id');
        $query = $builder->get();
        $gray_return = $query->getResultArray();

        
        $builder = $db->table('item i');
        $builder->select($millReturnvoucher.'rm.id,rm.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rmi.ret_taka) as pcs,SUM(rmi.ret_meter) as meter');
        $builder->join('return_mill_item rmi','rmi.pid ='.$id);
        $builder->join('return_mill rm','rm.id =rmi.voucher_id');
        $builder->join('account ac','ac.id = rm.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rmi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rmi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rmi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('rm.is_delete',0);
        $builder->where('rm.is_cancle',0);
        $builder->groupby('rm.id');
        $query = $builder->get();
        $mill_return = $query->getResultArray();

        $result['stock'] = array_merge($gray,$mill,$gray_return,$mill_return);
        
        $gmodel = new GeneralModel;
        $itm = $gmodel->get_data_table('item',array('id'=>$id),'*');
        
        $result['opening_taka'] = (float)$itm['opening_taka'];
        $result['opening_meter'] = (float)$itm['opening_meter'];
        return $result;
    }
    
    public function get_Mill_voucher_byitem($id,$post){

        $millvoucher = "'Mill Issue' as voucher_type,";
        $millreceivedvoucher = "'Mill Received' as voucher_type,";
        $millReturnvoucher = "'Mill Return' as voucher_type,";
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select($millvoucher.'m.id,m.challan_date as date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(mi.pcs) as pcs,SUM(mi.meter) as meter');
        $builder->join('mill_item mi','mi.pid ='.$id);
        $builder->join('mill_challan m','m.id = mi.voucher_id');
        $builder->join('account ac','ac.id =m.mill_ac');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(mi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(mi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(mi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('m.is_delete',0);
        $builder->where('m.is_cancle',0);
        $builder->groupBy('m.id');
        $query = $builder->get();
        $mill_issue = $query->getResultArray();
        
        $builder = $db->table('item i');
        $builder->select($millreceivedvoucher.'mr.id,mr.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(mri.rec_pcs) as pcs,SUM(mri.rec_meter) as meter');
        $builder->join('millRec_item mri','mri.pid ='.$id);
        $builder->join('millRec mr','mr.id =mri.voucher_id');
        $builder->join('account ac','ac.id =mr.mill_ac');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(mri.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(mri.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(mri.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('mr.is_delete',0);
        $builder->where('mr.is_cancle',0);
        $builder->groupby('mr.id');
        $query = $builder->get();
        $mill_received = $query->getResultArray();

        
        $builder = $db->table('item i');
        $builder->select($millReturnvoucher.'rm.id,rm.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rmi.ret_taka) as pcs,SUM(rmi.ret_meter) as meter');
        $builder->join('return_mill_item rmi','rmi.pid ='.$id);
        $builder->join('return_mill rm','rm.id =rmi.voucher_id');
        $builder->join('account ac','ac.id = rm.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rmi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rmi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rmi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('rm.is_delete',0);
        $builder->where('rm.is_cancle',0);
        $builder->groupby('rm.id');
        $query = $builder->get();
        $mill_return = $query->getResultArray();

        $result = array_merge($mill_issue,$mill_received,$mill_return);

        return $result;
    }

    public function get_Finish_voucher_byitem($id,$post){

        $grayvoucher = "'Finish Challan' as voucher_type,";
        $jobissuevoucher = "'Job Issue' as voucher_type,";
        $millreceivedvoucher = "'Mill Received' as voucher_type,";
        $finishReturnvoucher = "'Finish Return' as voucher_type,";

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select($grayvoucher.'g.id,g.challan_date as date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(gi.pcs) as pcs,SUM(gi.meter) as meter');
        $builder->join('grayChallan_item gi','gi.pid ='.$id);
        $builder->join('grey_challan g','g.id = gi.voucher_id');
        $builder->join('account ac','ac.id =g.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(gi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(gi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(gi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('gi.purchase_type','Finish');
        $builder->where('g.is_delete',0);
        $builder->where('g.is_cancle',0);
        $builder->groupBy('g.id');
        $query = $builder->get();
        $finish = $query->getResultArray();


        $builder = $db->table('item i');
        $builder->select($millreceivedvoucher.'mr.id,mr.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(mri.rec_pcs) as pcs,SUM(mri.rec_meter) as meter');
        $builder->join('millRec_item mri','mri.screen ='.$id);
        $builder->join('millRec mr','mr.id =mri.voucher_id');
        $builder->join('account ac','ac.id =mr.mill_ac');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(mri.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(mri.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(mri.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('mr.is_delete',0);
        $builder->where('mr.is_cancle',0);
        $builder->groupby('mr.id');
        $query = $builder->get();
        $mill_received = $query->getResultArray();


        $builder = $db->table('item i');
        $builder->select($jobissuevoucher.'j.id,j.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(ji.unit) as pcs,SUM(ji.meter) as meter');
        $builder->join('sendJob_Item ji','ji.pid ='.$id);
        $builder->join('sendJobwork j','j.id =ji.voucher_id');
        $builder->join('account ac','ac.id = j.account');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(ji.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(ji.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(ji.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('j.is_delete',0);
        $builder->where('j.is_cancle',0);
        $builder->groupby('j.id');
        $query = $builder->get();
        $job_issue = $query->getResultArray();


        $builder = $db->table('item i');
        $builder->select($finishReturnvoucher.'rg.id,rg.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rgi.ret_taka) as pcs,SUM(rgi.ret_meter) as meter');
        $builder->join('retGrayFinish_item rgi','rgi.pid ='.$id);
        $builder->join('retGrayFinish rg','rg.id =rgi.voucher_id');
        $builder->join('account ac','ac.id = rg.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rgi.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rgi.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rgi.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('rgi.purchase_type','Finish');
        $builder->where('rg.is_delete',0);
        $builder->where('rg.is_cancle',0);
        $builder->groupby('rg.id');
        $query = $builder->get();
        $finish_return = $query->getResultArray();


        $result = array_merge($finish,$mill_received,$job_issue,$finish_return);
       
        return $result;
    }

    public function get_Job_voucher_byitem($id,$post){

        $jobissuevoucher = "'Job Issue' as voucher_type,";
        $jobreceivedvoucher = "'Job Received' as voucher_type,";
        $jobreturnvoucher = "'Job Return' as voucher_type,";

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select($jobissuevoucher.'j.id,j.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(ji.pcs) as pcs,SUM(ji.meter) as meter');
        $builder->join('sendJob_Item ji','ji.pid ='.$id);
        $builder->join('sendJobwork j','j.id =ji.voucher_id');
        $builder->join('account ac','ac.id = j.account');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(ji.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(ji.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(ji.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('j.is_delete',0);
        $builder->where('j.is_cancle',0);
        $builder->groupby('j.id');
        $query = $builder->get();
        $job_issue = $query->getResultArray();


        $builder = $db->table('item i');
        $builder->select($jobreceivedvoucher.'jr.id,jr.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rji.rec_pcs) as pcs,SUM(rji.rec_mtr) as meter');
        $builder->join('recJob_Item rji','rji.pid ='.$id);
        $builder->join('recJobwork jr','jr.id =rji.voucher_id');
        $builder->join('account ac','ac.id = jr.account');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rji.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rji.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rji.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('jr.is_delete',0);
        $builder->where('jr.is_cancle',0);
        $builder->groupby('jr.id');
        $query = $builder->get();
        $job_received = $query->getResultArray();


        $builder = $db->table('item i');
        $builder->select($jobreturnvoucher.'jrt.id,jrt.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rtji.ret_taka) as pcs,SUM(rtji.ret_meter) as meter');
        $builder->join('return_jobwork_item rtji','rtji.pid ='.$id);
        $builder->join('return_jobwork jrt','jrt.id =rtji.voucher_id');
        $builder->join('account ac','ac.id = jrt.party_name');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rtji.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rtji.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rtji.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('jrt.is_delete',0);
        $builder->where('jrt.is_cancle',0);
        $builder->groupby('jrt.id');
        $query = $builder->get();
        $job_return= $query->getResultArray();


        $result = array_merge($job_issue,$job_received,$job_return);

        // echo '<pre>';print_r($job_issue);
        // echo '<pre>';print_r($job_received);
        // echo '<pre>';print_r($job_return);exit;
       
        return $result;
    }

    public function get_JobRec_voucher_byitem($id,$post){

        $jobreceivedvoucher = "'Job Received' as voucher_type,";

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select($jobreceivedvoucher.'jr.id,jr.date,ac.name as account_name,i.id as item_id,i.name as item_name,i.hsn,SUM(rji.rec_pcs) as pcs,SUM(rji.rec_mtr) as meter');
        $builder->join('recJob_Item rji','rji.screen ='.$id);
        $builder->join('recJobwork jr','jr.id =rji.voucher_id');
        $builder->join('account ac','ac.id = jr.account');
        if(isset($post['from_date']) && !empty($post['from_date'])){
            $builder->where('DATE(rji.created_at) >=', db_date($post['from_date']));
        }
        if(isset($post['from_date']) && !empty($post['from_date']) && isset($post['to_date']) && !empty($post['to_date']))
        {
            $builder->where('DATE(rji.created_at) >=', db_date($post['from_date']));
            $builder->where('DATE(rji.created_at) <=', db_date($post['to_date']));
        }
        $builder->where('i.id',$id);
        $builder->where('jr.is_delete',0);
        $builder->where('jr.is_cancle',0);
        $builder->groupby('jr.id');
        $query = $builder->get();
        $job_received = $query->getResultArray();
   
        return $job_received;
    }

    public function get_Gray_ItemStock_data($get,$post){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,i.opening_meter,i.opening_taka,gci.pcs as gray_pcs,gci.meter as gray_mtr,rti.ret_taka,rti.ret_meter,mi.send_pcs as send_pcs,mi.send_meter as send_mtr,rmi.ret_milltaka,rmi.ret_millmeter,gc.warehouse as gray_warehouse,rm.warehouse as ret_mill_warehouse');

        $join1 = "(SELECT voucher_id,pid, SUM(pcs) as pcs,SUM(meter) as meter,created_at FROM grayChallan_item where is_delete = 0 and  purchase_type = 'Gray' GROUP BY pid) as gci";
        $join2 = "(SELECT voucher_id,pid, SUM(pcs) as send_pcs ,SUM(meter) as send_meter,created_at FROM mill_item where is_delete = 0 GROUP BY pid) as mi";
        $join3 = "(SELECT voucher_id,pid, SUM(ret_taka) as ret_taka ,SUM(ret_meter) as ret_meter,created_at FROM retGrayFinish_item where is_delete = 0 GROUP BY pid) as rti";
        $join4 = "(SELECT voucher_id,pid, SUM(ret_taka) as ret_milltaka ,SUM(ret_meter) as ret_millmeter,created_at FROM return_mill_item where is_delete = 0 GROUP BY pid) as rmi";

        $builder->join($join1,'gci.pid = i.id','left');
        $builder->join($join2,'mi.pid = i.id','left');
        $builder->join($join3,'rti.pid = i.id','left');
        $builder->join($join4,'rmi.pid = i.id','left');
        $builder->join('grey_challan gc','gc.id = gci.voucher_id','left');
        $builder->join('return_mill rm','rm.id = rmi.voucher_id','left');
        $builder->where('gci.pid is not null' ); 
        $builder->where(array('i.is_delete' => 0)); 
        
        $filter = $get['filter_data'];
        $datatable =  DataTable::of($builder,$post)
            ->setSearchableColumns(['i.name','i.hsn']);
           
            if(@$post['warehouse'] != '' && @$post['warehouse'] != 'undefined'){

                $datatable->edit('gray_pcs', function($row){
                    
                    if($row->ret_mill_warehouse == $row->gray_warehouse){
                        $stock_pcs = (float)$row->gray_pcs - (float)$row->ret_taka - (float)$row->send_pcs + (float)$row->ret_milltaka + (float)$row->opening_taka;
                    }else{
                        $stock_pcs = (float)$row->gray_pcs - (float)$row->ret_taka - (float)$row->send_pcs  + (float)$row->opening_taka;
                    }
                    return $stock_pcs;
                }); 

                $datatable->edit('gray_mtr', function($row){
                        if($row->ret_mill_warehouse == $row->gray_warehouse){
                            $stock_meter = (float)$row->gray_mtr - (float)$row->ret_meter - (float)$row->send_mtr + (float)$row->ret_millmeter + (float)$row->opening_meter;
                        }else{
                            $stock_meter = (float)$row->gray_mtr - (float)$row->ret_meter - (float)$row->send_mtr +  (float)$row->opening_meter;
                        }
                    return $stock_meter;
                });
            }else{

                $datatable->edit('gray_pcs', function($row){
                    $stock_pcs = (float)$row->gray_pcs - (float)$row->ret_taka - (float)$row->send_pcs + (float)$row->ret_milltaka + (float)$row->opening_taka;
                    return $stock_pcs;
                }); 
                $datatable->edit('gray_mtr', function($row){
                    $stock_meter = (float)$row->gray_mtr - (float)$row->ret_meter - (float)$row->send_mtr + (float)$row->ret_millmeter + (float)$row->opening_meter;
                    return $stock_meter;
                });
            }
            
            

            $datatable->edit('name', function($row){
                $name = '<a href = "'.url('Stock/gray_voucher_detail/').$row->id.'">'.$row->name.'</a>';
                return $name;
            })
            
            ->filter(function ($builder, $request) {
                if ($request->from_date != '' && $request->from_date != 'undefined'){
                    $builder->where('DATE(gci.created_at) >=', db_date($request->from_date));
                    $builder->where('DATE(mi.created_at) >=', db_date($request->from_date));
                    $builder->where('DATE(rti.created_at) >=', db_date($request->from_date));
                    $builder->where('DATE(rmi.created_at) >=', db_date($request->from_date));
                }
                   
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined'){
                    $builder->where('DATE(gci.created_at) <=', db_date($request->to_date));
                    $builder->where('DATE(mi.created_at) <=', db_date($request->to_date));
                    $builder->where('DATE(rti.created_at) <=', db_date($request->to_date));
                    $builder->where('DATE(rmi.created_at) <=', db_date($request->to_date));
                }
                if(@$request->warehouse != '' && @$request->warehouse != 'undefined'){
                    $builder->where('gc.warehouse', $request->warehouse);
                    $builder->orWhere('rm.warehouse', $request->warehouse);
                }
            })
            
            ->hide('ret_taka')
            ->hide('ret_meter')
            ->hide('send_pcs')
            ->hide('send_mtr')
            ->hide('opening_meter')
            ->hide('opening_taka')
            ->hide('ret_milltaka')
            ->hide('ret_millmeter')
            ->hide('gray_warehouse')
            ->hide('ret_mill_warehouse');

            $res = $datatable->toJson();
            
            return $res;
    }

    public function get_Mill_ItemStock_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,mi.pcs as mill_pcs,mi.meter as mill_mtr,rti.ret_taka,rti.ret_meter,rci.rec_pcs,rci.rec_meter,rci.rec_cut');

        $join1 = "(SELECT pid, SUM(pcs) as pcs,SUM(meter) as meter,created_at FROM mill_item where is_delete = 0 GROUP BY pid) as mi";
        $join2 = "(SELECT pid, SUM(rec_pcs) as rec_pcs ,SUM(rec_meter) as rec_meter,SUM(cut) as rec_cut FROM millRec_item where is_delete = 0 GROUP BY pid) as rci";
        $join3 = "(SELECT pid, SUM(ret_taka) as ret_taka ,SUM(ret_meter) as ret_meter FROM return_mill_item where is_delete = 0 GROUP BY pid) as rti";

        $builder->join($join1,'mi.pid = i.id','left');
        $builder->join($join2,'rci.pid = i.id','left');
        $builder->join($join3,'rti.pid = i.id','left');
        $builder->where('mi.pid is not null' ); 
        $builder->where(array('i.is_delete' => 0)); 
       
        // $query = $builder->get();
        // $res = $query->getResultArray();
        // echo $db->getLastQuery();exit;
        $filter = $get['filter_data'];
        

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn'])
            ->edit('mill_pcs', function($row){
                $stock_pcs = (float)$row->mill_pcs - (float)$row->ret_taka - (float)$row->rec_pcs;
                return $stock_pcs;
            })

            ->edit('mill_mtr', function($row){
                $stock_meter = (float)$row->mill_mtr - (float)$row->ret_meter - (float)$row->rec_meter - (float)$row->rec_cut;
                return $stock_meter;
            })

            ->edit('name', function($row){
                $name = '<a href = "'.url('Stock/mill_voucher_detail/').$row->id.'">'.$row->name.'</a>';
                return $name;
            })
            
            
            ->filter(function ($builder, $request) {
                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(mi.created_at) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(mi.created_at) <=', db_date($request->to_date));
            })
            
            ->hide('ret_taka')
            ->hide('ret_meter')
            ->hide('rec_pcs')
            ->hide('rec_meter')
            ->hide('rec_cut');

            $res = $datatable->toJson();
            // echo $db->getLastQuery();exit;   
            return $res;
    }

    public function get_Finish_ItemStock_data($get,$post){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,rci.rec_pcs,rci.rec_meter,gci.pcs as pur_pcs,gci.meter as pur_mtr,ji.send_taka,ji.send_meter,rgci.retPur_taka,rgci.retPur_meter,gci.gray_date,rci.rec_date,m.warehouse as mill_rec_warehouse,gc.warehouse as gray_warehouse');

        $join1 = "(SELECT voucher_id,pid, SUM(pcs) as pcs,SUM(meter) as meter,created_at as gray_date FROM grayChallan_item where is_delete = 0 and purchase_type ='Finish' GROUP BY pid) as gci";
        $join2 = "(SELECT voucher_id,screen, SUM(rec_pcs) as rec_pcs ,SUM(rec_meter) as rec_meter,created_at as rec_date FROM millRec_item where is_delete = 0 GROUP BY pid) as rci";
        $join3 = "(SELECT voucher_id,pid, SUM(unit) as send_taka ,SUM(meter) as send_meter FROM sendJob_Item where is_delete = 0 GROUP BY pid) as ji";
        $join4 = "(SELECT voucher_id,pid, SUM(ret_taka) as retPur_taka ,SUM(ret_meter) as retPur_meter FROM retGrayFinish_item where is_delete = 0 and purchase_type ='Finish' GROUP BY pid) as rgci";

        $builder->join($join1,'gci.pid = i.id','left');
        $builder->join($join2,'rci.screen = i.id','left');
        $builder->join($join3,'ji.pid = i.id','left');
        $builder->join($join4,'rgci.pid = i.id','left');
        $builder->join('grey_challan gc','gc.id = gci.voucher_id','left');
        $builder->join('millRec m','m.id = rci.voucher_id','left');

      
        $builder->where(array('i.is_delete' => 0)); 
        $builder->where('rci.screen is not null' ); 
        $builder->orWhere('gci.pid is not null' ); 
        // $query = $builder->get();
        // $result = $query->getResultArray();
        // echo '<pre>';print_r($result);exit;
        // echo $db->getLAstQuery();exit;

        $filter = $get['filter_data'];
        
        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn']);

            $datatable->postData = $post;

            if(@$post['warehouse'] != '' && @$post['warehouse'] != 'undefined'){
                $datatable->edit('rec_pcs', function($row){
                    if($row->mill_rec_warehouse == $row->gray_warehouse){
                        $stock_pcs = ((float)$row->rec_pcs -  (float)$row->send_taka) + ((float)$row->pur_pcs - (float)$row->retPur_taka);
                    }else if($_POST['warehouse'] == $row->mill_rec_warehouse ){
                        $stock_pcs = ((float)$row->rec_pcs -  (float)$row->send_taka)  - (float)$row->retPur_taka;
                    }else if($_POST['warehouse'] == $row->gray_warehouse){
                        $stock_pcs =  ((float)$row->pur_pcs -  (float)$row->send_taka -  (float)$row->retPur_taka);
                    }else{}

                    return $stock_pcs;
                });
    
                $datatable->edit('rec_meter', function($row){

                    if($row->mill_rec_warehouse == $row->gray_warehouse){
                        $stock_meter = (float)$row->rec_meter + (float)$row->pur_mtr - (float)$row->send_meter - (float)$row->retPur_meter;
                    }else if($_POST['warehouse'] == $row->mill_rec_warehouse ){
                        $stock_meter = (float)$row->rec_meter  - (float)$row->send_meter - (float)$row->retPur_meter;

                    }else if($_POST['warehouse'] == $row->gray_warehouse){
                        $stock_meter =  (float)$row->pur_mtr - (float)$row->send_meter - (float)$row->retPur_meter;

                    }else{}
                    return $stock_meter;
                });

            }else{
                $datatable->edit('rec_pcs', function($row){
                    $stock_pcs = ((float)$row->rec_pcs -  (float)$row->send_taka) + ((float)$row->pur_pcs - (float)$row->retPur_taka)  ;
                    return $stock_pcs;
                });
    
                $datatable->edit('rec_meter', function($row){
                    $stock_meter = (float)$row->rec_meter + (float)$row->pur_mtr - (float)$row->send_meter - (float)$row->retPur_meter;
                    return $stock_meter;
                });                
            }
            $datatable->edit('name', function($row){
                $name = '<a href = "'.url('Stock/finish_voucher_detail/').$row->id.'">'.$row->name.'</a>';
                return $name;
            })
            
            ->filter(function ($builder, $request) {
                if ($request->from_date != '' && $request->from_date != 'undefined'){
                    $builder->where('DATE(rci.rec_date) >=', db_date($request->from_date));
                    $builder->where('DATE(gci.gray_date) >=', db_date($request->from_date));
                }
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined'){
                    $builder->where('DATE(rci.rec_date) <=', db_date($request->to_date));
                    $builder->where('DATE(gci.gray_date) <=', db_date($request->to_date));
                }

                if(@$request->warehouse != '' && @$request->warehouse != 'undefined'){
                    $builder->where('m.warehouse', $request->warehouse);
                    $builder->orWhere('gc.warehouse', $request->warehouse);
                }
            })
            
            ->hide('ret_taka')
            ->hide('ret_meter')
            ->hide('pur_pcs')
            ->hide('pur_mtr')
            ->hide('retPur_taka')
            ->hide('retPur_meter')
            ->hide('gray_date')
            ->hide('gray_warehouse')
            ->hide('mill_rec_warehouse')
            ->hide('rec_date');

            $res = $datatable->toJson();
            return $res;
    }

    public function get_Job_ItemStock_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,ji.pcs,ji.meter,ji.sortage,rci.rec_pcs,rci.rec_meter,rji.ret_pcs,rji.ret_meter');

        $join1 = "(SELECT pid, SUM(pcs) as pcs,SUM(meter) as meter,SUM(sortage) as sortage,created_at FROM sendJob_Item where is_delete = 0 GROUP BY pid) as ji";
        $join2 = "(SELECT pid, SUM(rec_pcs) as rec_pcs ,SUM(rec_mtr) as rec_meter FROM recJob_Item where is_delete = 0 GROUP BY pid) as rci";
        $join3 = "(SELECT pid, SUM(ret_taka) as ret_pcs ,SUM(ret_meter) as ret_meter FROM return_jobwork_item where is_delete = 0 GROUP BY pid) as rji";

        $builder->join($join1,'ji.pid = i.id','left');
        $builder->join($join2,'rci.pid = i.id','left');
        $builder->join($join3,'rji.pid = i.id','left');

        $builder->where('ji.pid is not null' );
        $builder->where(array('i.is_delete' => 0));
        // $query = $builder->get();
        // $result = $query->getResultArray();
        // echo $db->getLAstQuery();exit;
        
        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn'])
            ->edit('pcs', function($row){
                $stock_pcs = (float)$row->pcs - (float)$row->rec_pcs - (float)$row->ret_pcs;
                return $stock_pcs;
            })

            ->edit('meter', function($row){
                $stock_meter = (float)$row->meter - (float)$row->rec_meter - (float)$row->ret_meter;
                return $stock_meter;
            })

            ->edit('name', function($row){
                $name = '<a href = "'.url('Stock/job_voucher_detail/').$row->id.'">'.$row->name.'</a>';
                return $name;
            })
            
            ->filter(function ($builder, $request) {
                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(ji.created_at) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(ji.created_at) <=', db_date($request->to_date));
            })
            
            ->hide('rec_pcs')
            ->hide('rci_meter')
            ->hide('ret_pcs')
            ->hide('ret_meter');

            $res = $datatable->toJson();
            
            return $res;
    }

    public function get_RecJob_ItemStock_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,rci.rec_pcs,rci.rec_meter,rc.warehouse');

        $join = "(SELECT voucher_id,screen,pid, SUM(rec_pcs) as rec_pcs ,SUM(rec_mtr) as rec_meter,created_at FROM recJob_Item where is_delete = 0 GROUP BY screen) as rci";

        $builder->join($join,'rci.screen = i.id','left');
        $builder->join('recJobwork rc','rc.id = rci.voucher_id','left');
        
        $builder->where('rci.screen is not null' );
        $builder->where(array('i.is_delete' => 0));
       
        
        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn'])
            ->edit('name', function($row){
                $name = '<a href = "'.url('Stock/jobRec_voucher_detail/').$row->id.'">'.$row->name.'</a>';
                return $name;
            })
            
            ->filter(function ($builder, $request) {
                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(rci.created_at) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(rci.created_at) <=', db_date($request->to_date));

                if(@$request->warehouse != '' && @$request->warehouse != 'undefined') {
                    $builder->where('rc.warehouse', $request->warehouse);
                }
            })
            ->hide('warehouse');
            
            $res = $datatable->toJson();
            // echo $db->getLastQuery();exit;
            return $res;
    }

    public function get_avg_price_byitem($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('id,name,type');
        $builder->where('is_delete',0);
        $builder->where('item_mode','milling');
        $query = $builder->get();
        $result = $query->getResultArray();
        
        $arr = array();
        
        //**************** Pagination Not Work ****************//  


        // $results_per_page = 10; 
        // $page_first_result = ($page-1) * $results_per_page; 

        // $number_of_result =0;
        // foreach($result as $row){
        //     if($row['type'] == 'Grey'){
        //         $builder=$db->table('grayChallan_item');
        //         $builder->select('pid as item_id,SUM(amount) as total_taxable,SUM(meter) as total_meter,count(id) as devide_count,SUM(price) as total_price');
        //         $builder->where('purchase_type','Gray');
        //         $builder->where('pid',$row['id']);
        //         $builder->where('is_delete',0);
        //         $builder->groupBy('pid');
        //         $count_result = $builder->countAllResults();
        //         $number_of_result +=$count_result;

        //     }else if($row['type'] == 'Finish'){
        //         $builder=$db->table('millRec_item');
        //         $builder->select('screen as item_id,SUM(amount) as total_taxable,SUM(rec_meter) as total_meter,count(id) as devide_count,SUM(price) as total_price,SUM(price)/count(id) as avg_price');
        //         $builder->where('screen',$row['id']);
        //         $builder->where('is_delete',0);
        //         $builder->groupBy('screen');
        //         $count_result = $builder->countAllResults();
        //         $number_of_result +=$count_result;
        //     }
        //     else if($row['type'] == 'Jobwork'){
        //         $builder=$db->table('recJob_Item');
        //         $builder->select('screen as item_id,SUM(subtotal) as total_taxable,SUM(rec_mtr) as total_meter,count(id) as devide_count,SUM(price) as total_price,SUM(price)/count(id) as avg_price');
        //         $builder->where('screen',$row['id']);
        //         $builder->where('is_delete',0);
        //         $builder->groupBy('screen');
        //         $count_result = $builder->countAllResults();
        //         $number_of_result +=$count_result;
        //     }else{}
        // }

        // print_r($number_of_result);exit;

        foreach($result as $row){
            if($row['type'] == 'Grey'){
                $builder=$db->table('grayChallan_item');
                $builder->select('pid as item_id,SUM(amount) as total_taxable,SUM(meter) as total_meter,count(id) as devide_count,SUM(price) as total_price,SUM(price)/count(id) as avg_price');
                $builder->where('purchase_type','Gray');
                $builder->where('pid',$row['id']);
                $builder->where('is_delete',0);
                $builder->groupBy('pid');
                $query = $builder->get();
                $res = $query->getRowArray();
                if(!empty($res)){
                    $res['type'] = 'Grey';
                    $res['item_name'] = $row['name'];
                    $arr[] =$res;
                }
            }
            else if($row['type'] == 'Finish'){

                $builder=$db->table('millRec_item');
                $builder->select('pid,screen as item_id,SUM(amount) as total_taxable,SUM(rec_meter) as total_meter,count(id) as devide_count,SUM(price) as total_price,SUM(price)/count(id) as avg_price');
                $builder->where('screen',$row['id']);
                $builder->where('is_delete',0);
                $builder->groupBy('screen');
                $query = $builder->get();
                $res = $query->getRowArray();

                

                if(!empty($res)){
                    $builder1=$db->table('grayChallan_item');
                    $builder1->select('SUM(amount) as total_taxable');
                    $builder1->where('purchase_type','Gray');
                    $builder1->where('pid',$res['pid']);
                    $builder1->where('is_delete',0);
                    $builder1->groupBy('pid');
                    $query1 = $builder1->get();
                    $res1 = $query1->getRowArray();

                    $gray_total = $res1['total_taxable'];
                    $mill_total = $res['total_taxable'];
                    $grand_total = (float)$res['total_taxable']  + (float)$res1['total_taxable'];

                    $avg_price = $grand_total / $res['total_meter'];
                    
                    $res['avg_price2'] = $avg_price;
                    $res['type'] = 'Finish';
                    $res['item_name'] = $row['name'];
                    $arr[] =$res;
                }
            }else if($row['type'] == 'Jobwork'){
                $builder=$db->table('recJob_Item');
                $builder->select('screen as item_id,SUM(subtotal) as total_taxable,SUM(rec_mtr) as total_meter,count(id) as devide_count,SUM(price) as total_price,SUM(price)/count(id) as avg_price');
                $builder->where('screen',$row['id']);
                $builder->where('is_delete',0);
                $builder->groupBy('screen');
                $query = $builder->get();
                $res = $query->getRowArray();
                if(!empty($res)){
                    $res['type'] = 'Jobwork';
                    $res['item_name'] = $row['name'];
                    $arr[] =$res;
                }
            }else{}
        }
        // echo '<pre>';print_r($arr);exit;
        return $arr;
    }
}

?>