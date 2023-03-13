<?php

namespace App\Models;

use CodeIgniter\Model;
use \Hermawan\DataTables\DataTable;

class MillingReportModel extends Model
{
    public function get_Gray_ItemWise_data($get){ 

        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,gi.taka,gi.meter,gi.cut,rti.ret_taka,rti.ret_meter,rti.ret_cut,mi.send_taka,mi.send_meter');

        $join1 = "(SELECT pid, SUM(pcs) as taka,SUM(meter) as meter,SUM(cut) as cut FROM gray_item  where is_delete = 0 and purchase_type ='Gray' GROUP BY pid) as gi";
        $join2 = "(SELECT pid, SUM(ret_taka) as ret_taka ,SUM(ret_meter) as ret_meter,SUM(cut) as ret_cut FROM retGrayFinish_item where is_delete = 0 and purchase_type ='Gray' GROUP BY pid ) as rti";
        $join3 = "(SELECT pid, SUM(pcs) as send_taka ,SUM(meter) as send_meter FROM mill_item where is_delete = 0 GROUP BY pid ) as mi";

        $builder->join($join1,'gi.pid = i.id','left');
        $builder->join($join2,'rti.pid = i.id','left');
        $builder->join($join3,'mi.pid = i.id','left');
        $builder->where('gi.pid is not null');
        $builder->where(array('i.is_delete' => 0)); 

        $filter = $get['filter_data'];
        

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn']);
            // ->edit('taka', function($row){
            //     $name = '<a href = "'.url('MillingReport/gray_puchase_taka/').$row->id.'">'.$row->taka.'</a>';
            //     return $name;
            // });
            $res = $datatable->toJson();
            // echo $db->getLastQuery();
            return $res;
    }

    public function get_MillRec_report_data($get)
    {

        $dt_search = array(
            "mi.voucher_id",
            "ac.name as mill_name",
            "dl.name as party_name",
            "mr.date as voucher_date",
            "i.name as finish_item",
            "mi.screen",
            "mi.rec_pcs",
            "mi.rec_meter",
            "mi.taka_tp",
            "mi.millRecTb_ids",
        );

        $dt_col = array(
            "mi.voucher_id",
            "mi.id",
            "ac.name as mill_name",
            "dl.name as party_name",
            "mr.date as voucher_date",
            "i.name as finish_item",
            "mi.screen",
            "mi.rec_pcs",
            "mi.rec_meter",
            "mi.taka_tp",
            "mi.millRecTb_ids",
        );

        $filter = $get['filter_data'];
        $tablename = "millRec_item mi";
        $tablename .= ' left join millRec mr on mr.id = mi.voucher_id ';
        $tablename .= ' left join account ac on ac.id = mr.mill_ac';
        $tablename .= ' left join account dl on dl.id = mr.delivery_code';
        $tablename .= ' left join item i on i.id = mi.screen';
        $where = '';

        // if ($filter != '' && $filter != 'undefined') {
        //     $where .= ' and UserType ="' . $filter . '"';
        // }

        // $where .= " and is_delete = 0";

        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];

        $encode = array();
        foreach ($rResult['table'] as $row) {
            $DataRow = array();
            $send_jobwork = '<a   href = "' . url('milling/send_jobwork/') . $row['voucher_id'] . '/' . $row['id'] . '" class="btn btn-link pd-10"><i class="far fa-paper-plane"></i></a> ';

            $btn = $send_jobwork;

            $DataRow[] = $row['voucher_id'];
            $DataRow[] = $row['party_name'];
            $DataRow[] = $row['mill_name'];
            $DataRow[] = $row['finish_item'];
            $DataRow[] = $row['rec_pcs'];
            $DataRow[] = $row['rec_meter'];

            $encode[] = $DataRow;
        }

        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }  

    public function get_sendMill_ItemWise_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,mi.pcs,mi.meter,rti.ret_taka,rti.ret_meter,rci.rec_pcs,rci.rec_meter,rci.rec_cut');

        $join1 = "(SELECT pid, SUM(pcs) as pcs,SUM(meter) as meter FROM mill_item  where is_delete = 0 GROUP BY pid) as mi";
        $join2 = "(SELECT pid, SUM(rec_pcs) as rec_pcs ,SUM(rec_meter) as rec_meter,SUM(cut) as rec_cut FROM millRec_item where is_delete = 0 GROUP BY pid ) as rci";
        $join3 = "(SELECT pid, SUM(ret_taka) as ret_taka ,SUM(ret_meter) as ret_meter FROM return_mill_item where is_delete = 0 GROUP BY pid ) as rti";

        $builder->join($join1,'mi.pid = i.id','left');
        $builder->join($join2,'rci.pid = i.id','left');
        $builder->join($join3,'rti.pid = i.id','left');
        $builder->where('mi.pid is not null' ); 
        $builder->where(array('i.is_delete' => 0)); 
       
        $filter = $get['filter_data'];
        

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn']);
            
            $res = $datatable->toJson();
            // echo $db->getLastQuery();
            return $res;
    }

    public function get_recMill_ItemWise_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,rci.rec_pcs,rci.rec_meter,rci.rec_cut');

        $join1 = "(SELECT screen, SUM(rec_pcs) as rec_pcs ,SUM(rec_meter) as rec_meter,SUM(cut) as rec_cut FROM millRec_item where is_delete = 0 GROUP BY pid ) as rci";

        $builder->join($join1,'rci.screen = i.id','left');
        $builder->where('rci.screen is not null'); 
        $builder->where(array('i.is_delete' => 0)); 
       
        $filter = $get['filter_data'];
        

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn']);
            
            $res = $datatable->toJson();
            // echo $db->getLastQuery();
            return $res;
    }

    public function get_sendJob_ItemWise_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i');
        $builder->select('i.id,i.name,i.hsn,ji.pcs,ji.meter,ji.cut,rti.ret_taka,rti.ret_meter,rci.rec_pcs,rci.rec_meter');

        $join1 = "(SELECT pid, SUM(pcs) as pcs,SUM(meter) as meter,SUM(sortage) as cut FROM sendJob_Item  where is_delete = 0 GROUP BY pid) as ji";
        $join2 = "(SELECT pid, SUM(rec_pcs) as rec_pcs ,SUM(rec_mtr) as rec_meter FROM recJob_Item where is_delete = 0 GROUP BY pid ) as rci";
        $join3 = "(SELECT pid, SUM(ret_taka) as ret_taka ,SUM(ret_meter) as ret_meter FROM return_jobwork_item where is_delete = 0 GROUP BY pid ) as rti";

        $builder->join($join1,'ji.pid = i.id','left');
        $builder->join($join2,'rci.pid = i.id','left');
        $builder->join($join3,'rti.pid = i.id','left');
        $builder->where('ji.pid is not null' ); 
        $builder->where(array('i.is_delete' => 0)); 
       

        $filter = $get['filter_data'];
        

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn']);
            
            $res = $datatable->toJson();
            return $res;
    }

    public function get_Gray_InvoiceWise_data($get){ 
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i')
                      ->select('g.id,g.inv_no,g.inv_date,ac.name as account_name,i.name,i.hsn,SUM(gi.pcs) as taka,SUM(gi.meter) as meter,SUM(gi.cut) as cut ,SUM(rgi.ret_taka) as return_taka,SUM(rgi.ret_meter) as return_meter,,SUM(rgi.cut) as ret_cut')
                      ->join('gray_item gi','gi.pid = i.id','left')
                      ->join('grey g','g.id = gi.voucher_id','left')
                      ->join('account ac','ac.id = g.party_name','left')
                      ->join('retGrayFinish rg','rg.weaver_invoice = gi.voucher_id','left')
                      ->join('retGrayFinish_item rgi','rgi.voucher_id = rg.id','left')
                      ->where(array('i.is_delete' => 0))
                      ->where(array('gi.is_delete' => 0))
                    //   ->where(array('rgi.pid' => 'i.id'))
                      ->where(array('gi.purchase_type' => 'Gray'))
                      ->where(array('i.item_mode' => "milling"))
                      ->where(array('i.type' => "Grey"))
                      ->groupBy('g.id');
                    // $query = $builder->get();
                    // $result = $query->getResultArray();

        // echo '<pre>';print_r($result);exit;
        // echo $db->getLastQuery();exit;
        $filter = $get['filter_data'];
        //  if ($filter != '' && $filter != 'undefined') {
        //     $builder->where('account', $get['filter_data']);
        //  }

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn','ac.name','g.inv_date','g.inv_no'])
            ->edit('inv_date', function($row){
                $user_date = user_date($row->inv_date);
                return $user_date;
            })
            
            ->edit('inv_no', function($row){
                $inv_no = '<a href = "'.url('Milling/Add_grey/').$row->id.'">'.$row->inv_no.'</a>';
                return $inv_no;
            })

            ->edit('taka', function($row){
                $taka = $row->taka.'('.$row->meter.')';
                return $taka;
            })

            ->edit('return_taka', function($row){
                $taka = $row->return_taka.'('.$row->return_meter.')';
                return $taka;
            })

            ->add('available_taka', function($row){
                $available_taka = (float)$row->taka - (float)$row->return_taka;
                return $available_taka;
            },'last')

            ->add('available_meter', function($row){
                $available_meter = (float)$row->meter - (float)$row->return_meter;
                return $available_meter;
            },'last')
            
            ->filter(function ($builder, $request) {

                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(g.inv_date) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(g.inv_date) <=', db_date($request->to_date));

            })->hide('id');
            
            $res = $datatable->toJson();
            return $res;
    }

    public function get_mill_report_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i')
                      ->select('m.id,gc.id as gray_ChallanID,gc.challan_no,g.id as grayinvID,g.inv_no,acc.name as weaver_ac,m.challan_date,ac.name as account_name,i.name,i.hsn,SUM(mi.pcs) as taka,SUM(mi.meter) as meter,SUM(rmi.ret_taka) as return_taka,SUM(rmi.ret_meter) as return_meter')
                      ->join('mill_item mi','mi.pid = i.id','left')
                      ->join('mill_challan m','m.id = mi.voucher_id','left')
                      ->join('grey_challan gc','gc.id = m.challan_no','left')
                      ->join('grey g','g.challan_no = m.challan_no','left')
                      ->join('account ac','ac.id = m.mill_ac','left')
                      ->join('account acc','acc.id = gc.party_name','left')
                      ->join('return_mill rm','rm.mill_challan = mi.voucher_id','left')
                      ->join('return_mill_item rmi','rmi.voucher_id = rm.id','left')
                      ->where(array('i.is_delete' => 0))
                      ->where(array('m.is_delete' => 0))
                      ->where(array('rm.is_delete' => 0))
                      ->where(array('rmi.is_delete' => 0))
                      ->groupBy('m.id');
                   
        $filter = $get['filter_data'];
       

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn','ac.name','m.challan_date','gc.challan_no','m.id','g.inv_no'])
            ->edit('inv_date', function($row){
                $user_date = user_date($row->inv_date);
                return $user_date;
            })
           
            ->edit('id', function($row){
                $id = '<a href="'.url('Milling/Add_millSend/'.$row->id).'">'.$row->id.'</a>';
                return $id;
            })

            ->edit('challan_no', function($row){
                $id = '<a href="'.url('Milling/Add_millSend/'.$row->id).'">'.$row->challan_no.'</a>';
                return $id;
            })

            ->edit('challan_no', function($row){
                $grey_challan = '<a href="'.url('Milling/Add_grey_challan/'.$row->gray_ChallanID).'">'.$row->challan_no.'</a>';
                return $grey_challan;
            })

            ->edit('inv_no', function($row){
                $grey_challan = '<a href="'.url('Milling/Add_grey/'.$row->grayinvID).'">'.$row->inv_no.'</a>';
                return $grey_challan;
            })
            
            ->add('total_taka', function($row){
                $tot_taka = (isset($row->taka) ? (float)$row->taka : 0)  -  (isset($row->return_taka) ? (float)$row->return_taka : 0) ;
                return $tot_taka;
            }, 'last')
            ->add('total_meter', function($row){
                $tot_meter = (isset($row->meter) ? (float)$row->meter : 0)  -  (isset($row->return_meter) ? (float)$row->return_meter : 0) ;
                return $tot_meter;
            }, 'last')

            ->filter(function ($builder, $request) {

                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(m.challan_date) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(m.challan_date) <=', db_date($request->to_date));
            })->hide('gray_ChallanID')->hide('grayinvID');
            
            $res = $datatable->toJson();
            return $res;
    }

    public function get_sendJob_report_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i')
                      ->select('j.id,j.date,ac.name as account_name,i.name,i.hsn,SUM(ji.pcs) as pcs,SUM(ji.meter) as meter,SUM(ji.sortage) as cut,SUM(rji.ret_taka) as return_pcs,SUM(rji.ret_meter) as return_meter')
                      ->join('sendJob_Item ji','ji.pid = i.id','left')
                      ->join('sendJobwork j','j.id = ji.voucher_id','left')
                      ->join('account ac','ac.id = j.account','left')
                      ->join('return_jobwork rj','rj.job_challan = ji.voucher_id','left')
                      ->join('return_jobwork_item rji','rji.voucher_id = rj.id','left')
                      ->where(array('i.is_delete' => 0))
                      ->where(array('j.is_delete' => 0))
                      ->groupBy('j.id');
                   
        $filter = $get['filter_data'];
       

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn','ac.name','j.date','j.id'])
            ->edit('date', function($row){
                $user_date = user_date($row->date);
                return $user_date;
            })

            ->edit('id', function($row){
                $id = '<a href="'.url('Milling/Add_jobwork/'.$row->id).'">'.$row->id.'</a>';
                return $id;
            })

            ->add('total_taka', function($row){
                $tot_taka = (isset($row->pcs) ? (float)$row->pcs : 0)  -  (isset($row->return_pcs) ? (float)$row->return_pcs : 0) ;
                return $tot_taka;
            }, 'last')
            ->add('total_meter', function($row){
                $tot_meter = (isset($row->meter) ? (float)$row->meter : 0)  -  (isset($row->return_meter) ? (float)$row->return_meter : 0) ;
                return $tot_meter;
            }, 'last')

            
            ->filter(function ($builder, $request) {

                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(j.date) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(j.date) <=', db_date($request->to_date));
            });
            
            $res = $datatable->toJson();
            return $res;
    }

    public function get_RecMill_report_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i')
                      ->select('mr.id,mr.challan_no,gc.id as gray_ChallanID,gc.challan_no as grey_challan,g.id as grayinvID,g.inv_no,acc.name as weaver_ac,mr.date,ac.name as account_name,i.name,is.name as screen,SUM(mri.rec_pcs) as taka,SUM(mri.rec_meter) as meter,SUM(cut) as rec_cut')
                      ->join('millRec_item mri','mri.pid = i.id','left')
                      ->join('item is','is.id = mri.screen','left')
                      ->join('millRec mr','mr.id = mri.voucher_id','left')
                      ->join('mill_challan mc','mc.id = mr.challan_no','left')
                      ->join('grey_challan gc','gc.id = mc.challan_no','left')
                      ->join('grey g','g.challan_no = gc.id','left')
                      ->join('account ac','ac.id = mr.mill_ac','left')
                      ->join('account acc','acc.id = gc.party_name','left')
                      ->where(array('i.is_delete' => 0))
                      ->where(array('mr.is_delete' => 0))
                      ->groupBy('mr.id');
                    // $query = $builder->get();
                    // $result = $query->getResultArray();

        // echo '<pre>';print_r($result);exit;
        // echo $db->getLastQuery();exit;
        $filter = $get['filter_data'];
       

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn','ac.name','mr.id','mr.challan_no','gc.challan_no','g.inv_no','is.name','i.name'])
            ->edit('date', function($row){
                $user_date = user_date($row->date);
                return $user_date;
            })
            ->edit('name', function($row){
                $item_name = $row->name.'<br><b>('.$row->screen.')</b>';
                return $item_name;
            })

            ->edit('id', function($row){
                $id = '<a href="'.url('Milling/add_rec_mill/'.$row->id).'">'.$row->id.'</a>';
                return $id;
            })

            ->edit('challan_no', function($row){
                $challan_no = '<a href="'.url('Milling/Add_millSend/'.$row->challan_no).'">'.$row->challan_no.'</a>';
                return $challan_no;
            })

            ->edit('grey_challan', function($row){
                $grey_challan = '<a href="'.url('Milling/Add_grey_challan/'.$row->gray_ChallanID).'">'.$row->grey_challan.'</a>';
                return $grey_challan;
            })

            ->edit('inv_no', function($row){
                $grey_challan = '<a href="'.url('Milling/Add_grey/'.$row->grayinvID).'">'.$row->inv_no.'</a>';
                return $grey_challan;
            })

            ->filter(function ($builder, $request) {

                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(mr.date) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(mr.date) <=', db_date($request->to_date));
            })->hide('screen')->hide('gray_ChallanID')->hide('grayinvID');
            
            $res = $datatable->toJson();
            return $res;
    }

    public function get_JobMill_report_data($get){ 

        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item i')
                      ->select('jr.id,jr.challan_no,jr.date,ac.name as account_name,i.name,is.name as screen,SUM(jri.rec_pcs) as pcs,SUM(jri.rec_mtr) as meter')
                      ->join('recJob_Item jri','jri.pid = i.id','left')
                      ->join('item is','is.id = jri.screen','left')
                      ->join('recJobwork jr','jr.id = jri.voucher_id','left')
                      ->join('account ac','ac.id = jr.account','left')
                      ->where(array('i.is_delete' => 0))
                      ->where(array('jr.is_delete' => 0))
                      ->groupBy('jr.id');
                    // $query = $builder->get();
                    // $result = $query->getResultArray();

        // echo '<pre>';print_r($result);exit;
        // echo $db->getLastQuery();exit;
        $filter = $get['filter_data'];
       

        $datatable =  DataTable::of($builder)
            ->setSearchableColumns(['i.name','i.hsn','ac.name','jr.id','jr.challan_no','is.name','i.name'])
            ->edit('date', function($row){
                $user_date = user_date($row->date);
                return $user_date;
            })
            ->edit('id', function($row){
                $id = '<a href="'.url('Milling/Add_rec_jobwork/'.$row->id).'">'.$row->id.'</a>';
                return $id;
            })
            ->edit('challan_no', function($row){
                $challan_no = '<a href="'.url('Milling/Add_jobwork/'.$row->challan_no).'">'.$row->challan_no.'</a>';
                return $challan_no;
            })
            ->edit('name', function($row){
                $item_name = $row->name.'<br><b>('.$row->screen.')</b>';
                return $item_name;
            })

            ->filter(function ($builder, $request) {

                if ($request->from_date != '' && $request->from_date != 'undefined')
                    $builder->where('DATE(mr.date) >=', db_date($request->from_date));
                    
                if ($request->from_date != '' && $request->from_date != 'undefined' && $request->to_date != '' && $request->to_date != 'undefined')
                    $builder->where('DATE(mr.date) <=', db_date($request->to_date));
            })->hide('screen');
            
            $res = $datatable->toJson();
            return $res;
    }

    public function get_gray_issue_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();

        foreach($acc as $row){
            $builder = $db->table('grey g');
            $builder->select('g.*,gi.*,w.name as warehouse_name,i.name,i.hsn,g.id as gray_id');
            $builder->join('gray_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('g.purchase_type','Gray');
            $builder->where('g.is_delete','0');
            $builder->where('g.party_name',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){    
                $builder->where('g.party_name',$post['account']);
            }
            
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){   
                $builder->where('g.inv_date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.inv_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){    
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){    
                $builder->where('g.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();

            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_finish_issue_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();

        foreach($acc as $row){
            $builder = $db->table('grey g');
            $builder->select('g.*,gi.*,w.name as warehouse_name,i.name,i.hsn,g.id as gray_id');
            $builder->join('gray_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('g.purchase_type','Finish');
            $builder->where('g.is_delete','0');
            $builder->where('g.party_name',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){    
                $builder->where('g.party_name',$post['account']);
            }
            
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){   
                $builder->where('g.inv_date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.inv_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){    
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){    
                $builder->where('g.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();

            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_gray_return_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            $builder = $db->table('retGrayFinish g');
            $builder->select('g.*,gi.*,i.name,i.hsn,g.id as gray_id,w.name as warehouse_name');
            $builder->join('retGrayFinish_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('g.purchase_type','Gray');
            $builder->where('g.is_delete','0');
            $builder->where('g.party_name',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('g.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('g.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('g.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_finish_return_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            $builder = $db->table('retGrayFinish g');
            $builder->select('g.*,gi.*,i.name,i.hsn,g.id as gray_id,w.name as warehouse_name');
            $builder->join('retGrayFinish_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('g.purchase_type','Finish');
            $builder->where('g.is_delete','0');
            $builder->where('g.party_name',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('g.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('g.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('g.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_issue_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            $builder = $db->table('mill_challan m');
            $builder->select('m.*,mi.*,i.name,i.hsn,m.id as mill_id,w.name as warehouse_name');
            $builder->join('mill_item mi','mi.voucher_id = m.id');
            $builder->join('item i','i.id = mi.pid');
            $builder->join('warehouse w','w.id = m.warehouse');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('m.mill_ac',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.mill_ac',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('m.challan_date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.challan_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('mi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            // foreach($res as $row1){
            //     $tax = json_decode($row1['taxes']);
            //     if(in_array('sgst',$tax)){
            //         $row1['tax_type'] = 'non_igst';
            //     }else{
            //         $row1['tax_type'] = 'igst';
            //     }
            //     $final_item[] =$row1; 
            // }   
            
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_received_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
        $gmodel = new GeneralModel;

        foreach($acc as $row){
            $builder = $db->table('millRec m');
            $builder->select('m.*,mi.*,i.name,i.hsn,m.id as mill_id,it.name as screen_name,w.name as warehouse_name');
            $builder->join('millRec_item mi','mi.voucher_id = m.id');
            $builder->join('item i','i.id = mi.pid');
            $builder->join('item it','it.id = mi.screen');
            $builder->join('warehouse w','w.id = m.warehouse','left');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('m.mill_ac',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.mill_ac',$post['account']);
            }

            if(isset($post['from_date']) && db_date($post['from_date']) != ''){    
                $builder->where('m.date >=',db_date($post['from_date']));
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $item_type = $gmodel->get_data_table('item',array('id'=>$post['item']),'type');
                if($item_type['type'] == 'Finish'){
                    $builder->Where('mi.screen',$post['item']);
                }else{
                    $builder->where('mi.pid',$post['item']);
                }
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }

                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_return_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            
            $builder = $db->table('return_mill m');
            $builder->select('m.*,mi.*,i.name,i.hsn,m.id as mill_id,,w.name as warehouse_name');
            $builder->join('return_mill_item mi','mi.voucher_id = m.id');
            $builder->join('item i','i.id = mi.pid');
            $builder->join('warehouse w','w.id = m.warehouse','left');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('m.party_name',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.party_name',$post['account']);
            }

            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('m.date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('mi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_issue_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            
            $builder = $db->table('sendJobwork j');
            $builder->select('j.*,ji.*,i.name,i.hsn,j.id as job_id,w.name as warehouse_name');
            $builder->join('sendJob_Item ji','ji.voucher_id = j.id');
            $builder->join('item i','i.id = ji.pid');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('j.account',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.account',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('ji.pid',$post['item']);
            }

            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_return_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            
            $builder = $db->table('return_jobwork j');
            $builder->select('j.*,ji.*,i.name,i.hsn,j.id as job_id,w.name as warehouse_name');
            $builder->join('return_jobwork_item ji','ji.voucher_id = j.id');
            $builder->join('item i','i.id = ji.pid');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('j.party_name',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('ji.screen',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_received_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
        $gmodel = new GeneralModel;
        foreach($acc as $row){
            $builder = $db->table('recJobwork j');
            $builder->select('j.*,ji.*,i.name,i.hsn,j.id as job_id,it.name as screen_name,w.name as warehouse_name');
            $builder->join('recJob_Item ji','ji.voucher_id = j.id');
            $builder->join('item i','i.id = ji.pid');
            $builder->join('item it','it.id = ji.screen');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('j.account',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.account',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }
        
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }

            if(isset($post['item']) && $post['item'] != ''){
                $item_type = $gmodel->get_data_table('item',array('id'=>$post['item']),'type');
                if($item_type['type'] == 'Jobwork'){
                    $builder->Where('ji.screen',$post['item']);
                }else{
                    $builder->where('ji.pid',$post['item']);
                }
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }

                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    //********** ITEM WISE REPORT **********//

    public function get_gray_issue_ItemWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();

        foreach($item as $row){
            $builder = $db->table('gray_item gi');
            $builder->select('g.*,gi.*,w.name as warehouse_name,g.id as gray_id,ac.name as party_name');
            $builder->join('grey g','g.id = gi.voucher_id');
            $builder->join('account ac','ac.id = g.party_name');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('gi.purchase_type','Gray');
            $builder->where('g.is_delete','0');
            $builder->where('gi.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){    
                $builder->where('g.party_name',$post['account']);
            }
            
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){   
                $builder->where('g.inv_date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.inv_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){    
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){    
                $builder->where('g.warehouse',$post['warehouse']);
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_finish_issue_ItemWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();

        foreach($item as $row){
            $builder = $db->table('gray_item gi');
            $builder->select('g.*,gi.*,w.name as warehouse_name,g.id as gray_id,ac.name as party_name');
            $builder->join('grey g','g.id = gi.voucher_id');
            $builder->join('account ac','ac.id = g.party_name');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('gi.purchase_type','Finish');
            $builder->where('g.is_delete','0');
            $builder->where('gi.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){    
                $builder->where('g.party_name',$post['account']);
            }
            
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){   
                $builder->where('g.inv_date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.inv_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){    
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){    
                $builder->where('g.warehouse',$post['warehouse']);
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_gray_return_ItemWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();

        foreach($item as $row){
            $builder = $db->table('retGrayFinish_item gi');
            $builder->select('g.*,gi.*,ac.name as account_name,g.id as gray_id,w.name as warehouse_name');
            $builder->join('retGrayFinish g','g.id = gi.voucher_id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->join('account ac','ac.id = g.party_name','left');
            $builder->where('g.purchase_type','Gray');
            $builder->where('g.is_delete','0');
            $builder->where('gi.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('g.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('g.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('g.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_finish_return_ItemWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();

        foreach($item as $row){
            $builder = $db->table('retGrayFinish_item gi');
            $builder->select('g.*,gi.*,ac.name as account_name,g.id as gray_id,w.name as warehouse_name');
            $builder->join('retGrayFinish g','g.id = gi.voucher_id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->join('account ac','ac.id = g.party_name','left');
            $builder->where('g.purchase_type','Finish');
            $builder->where('g.is_delete','0');
            $builder->where('gi.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('g.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('g.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('g.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_issue_ItemWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();
       
        foreach($item as $row){
            $builder = $db->table('mill_item mi');
            $builder->select('m.*,mi.*,ac.name as party_name,m.id as mill_id,w.name as warehouse_name');
            $builder->join('mill_challan m','m.id = mi.voucher_id');
            $builder->join('account ac','ac.id = m.mill_ac');
            $builder->join('warehouse w','w.id = m.warehouse');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('mi.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.mill_ac',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('m.challan_date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.challan_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('mi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            // foreach($res as $row1){
            //     $tax = json_decode($row1['taxes']);
            //     if(in_array('sgst',$tax)){
            //         $row1['tax_type'] = 'non_igst';
            //     }else{
            //         $row1['tax_type'] = 'igst';
            //     }
            //     $final_item[] =$row1; 
            // }   
            
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }
    
    public function get_mill_return_ItemWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();
       
        foreach($item as $row){
            
            $builder = $db->table('return_mill_item mi');
            $builder->select('m.*,mi.*,ac.name as party_name,m.id as mill_id,,w.name as warehouse_name');
            $builder->join('return_mill m','m.id = mi.voucher_id');
            $builder->join('account ac','ac.id = m.party_name');
            $builder->join('warehouse w','w.id = m.warehouse','left');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('mi.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.party_name',$post['account']);
            }

            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('m.date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('mi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_received_ItemWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();
       
        $gmodel = new GeneralModel;

        foreach($item as $row){
            $builder = $db->table('millRec_item mi');
            $builder->select('m.*,mi.*,ac.name as party_name,m.id as mill_id,w.name as warehouse_name');
            $builder->join('millRec m','m.id = mi.voucher_id');
            $builder->join('account ac','ac.id = m.mill_ac');
            $builder->join('warehouse w','w.id = m.warehouse','left');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('mi.screen',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.mill_ac',$post['account']);
            }

            if(isset($post['from_date']) && db_date($post['from_date']) != ''){    
                $builder->where('m.date >=',db_date($post['from_date']));
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->Where('mi.screen',$post['item']);
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }

                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_issue_ItemWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();

        foreach($item as $row){
            
            $builder = $db->table('sendJob_Item ji');
            $builder->select('j.*,ji.*,ac.name as party_name,j.id as job_id,w.name as warehouse_name');
            $builder->join('sendJobwork j','ji.voucher_id = j.id');
            $builder->join('account ac','ac.id = j.account');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('ji.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.account',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('ji.pid',$post['item']);
            }

            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_return_ItemWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();

       
        foreach($item as $row){
            
            $builder = $db->table('return_jobwork_item ji');
            $builder->select('j.*,ji.*,ac.name as party_name,j.id as job_id,w.name as warehouse_name');
            $builder->join('return_jobwork j','j.id = ji.voucher_id');
            $builder->join('account ac','ac.id = j.party_name');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('ji.pid',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('ji.screen',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_received_ItemWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('item');
        $builder->select('name,id,hsn');
        $query = $builder->get();
        $item = $query->getResultArray();
        $arr=array();
        
        $gmodel = new GeneralModel;

        foreach($item as $row){
            $builder = $db->table('recJob_Item ji');
            $builder->select('j.*,ji.*,ac.name as party_name,j.id as job_id,w.name as warehouse_name');
            $builder->join('recJobwork j','ji.voucher_id = j.id');
            $builder->join('account ac','ac.id = j.account');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('ji.screen',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.account',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }
        
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }

            if(isset($post['item']) && $post['item'] != ''){
                $item_type = $gmodel->get_data_table('item',array('id'=>$post['item']),'type');
                if($item_type['type'] == 'Jobwork'){
                    $builder->Where('ji.screen',$post['item']);
                }else{
                    $builder->where('ji.pid',$post['item']);
                }
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }

                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['hsn']=$row['hsn'];
                $data['id']=$row['id'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    //******* BROKER WISE REPORT *******//

    public function get_gray_issue_BrokerWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();

        foreach($acc as $row){
            $builder = $db->table('grey g');
            $builder->select('g.*,gi.*,w.name as warehouse_name,i.name,i.hsn,g.id as gray_id,ac.name as party_name');
            $builder->join('gray_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('account ac','ac.id = g.party_name');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('g.purchase_type','Gray');
            $builder->where('g.is_delete','0');
            $builder->where('g.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){    
                $builder->where('g.party_name',$post['account']);
            }
            
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){   
                $builder->where('g.inv_date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.inv_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){    
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){    
                $builder->where('g.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){    
                $builder->where('g.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();

            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_finish_issue_BrokerWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();

        foreach($acc as $row){
            $builder = $db->table('grey g');
            $builder->select('g.*,gi.*,w.name as warehouse_name,i.name,i.hsn,g.id as gray_id,ac.name as party_name');
            $builder->join('gray_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('account ac','ac.id = g.party_name');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->where('g.purchase_type','Finish');
            $builder->where('g.is_delete','0');
            $builder->where('g.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){    
                $builder->where('g.party_name',$post['account']);
            }
            
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){   
                $builder->where('g.inv_date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.inv_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){    
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){    
                $builder->where('g.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){    
                $builder->where('g.broker',$post['broker']);
            }

            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_gray_return_BrokerWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            $builder = $db->table('retGrayFinish g');
            $builder->select('g.*,gi.*,i.name,i.hsn,g.id as gray_id,w.name as warehouse_name,ac.name as party_name');
            $builder->join('retGrayFinish_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->join('account ac','ac.id = g.party_name');
            $builder->where('g.purchase_type','Gray');
            $builder->where('g.is_delete','0');
            $builder->where('g.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('g.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('g.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('g.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('g.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_finish_return_BrokerWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            $builder = $db->table('retGrayFinish g');
            $builder->select('g.*,gi.*,i.name,i.hsn,g.id as gray_id,w.name as warehouse_name,ac.name as party_name');
            $builder->join('retGrayFinish_item gi','gi.voucher_id = g.id');
            $builder->join('item i','i.id = gi.pid');
            $builder->join('warehouse w','w.id = g.warehouse','left');
            $builder->join('account ac','ac.id = g.party_name');
            $builder->where('g.purchase_type','Finish');
            $builder->where('g.is_delete','0');
            $builder->where('g.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('g.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('g.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('g.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('gi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('g.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('g.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }
                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_issue_BrokerWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            $builder = $db->table('mill_challan m');
            $builder->select('m.*,mi.*,i.name,i.hsn,m.id as mill_id,w.name as warehouse_name,ac.name as mill_name');
            $builder->join('mill_item mi','mi.voucher_id = m.id');
            $builder->join('item i','i.id = mi.pid');
            $builder->join('warehouse w','w.id = m.warehouse');
            $builder->join('account ac','ac.id = m.mill_ac');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('m.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.mill_ac',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('m.challan_date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.challan_date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('mi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('m.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
            // foreach($res as $row1){
            //     $tax = json_decode($row1['taxes']);
            //     if(in_array('sgst',$tax)){
            //         $row1['tax_type'] = 'non_igst';
            //     }else{
            //         $row1['tax_type'] = 'igst';
            //     }
            //     $final_item[] =$row1; 
            // }   
            
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_received_BrokerWise_report($post){
        // echo '<pre>';print_r($post);exit;
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
        $gmodel = new GeneralModel;

        foreach($acc as $row){
            $builder = $db->table('millRec m');
            $builder->select('m.*,mi.*,i.name,i.hsn,m.id as mill_id,it.name as screen_name,w.name as warehouse_name,ac.name as mill_name');
            $builder->join('millRec_item mi','mi.voucher_id = m.id');
            $builder->join('item i','i.id = mi.pid');
            $builder->join('item it','it.id = mi.screen');
            $builder->join('warehouse w','w.id = m.warehouse','left');
            $builder->join('account  ac','ac.id = m.mill_ac');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('m.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.mill_ac',$post['account']);
            }

            if(isset($post['from_date']) && db_date($post['from_date']) != ''){    
                $builder->where('m.date >=',db_date($post['from_date']));
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }
            
            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('m.broker',$post['broker']);
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $item_type = $gmodel->get_data_table('item',array('id'=>$post['item']),'type');
                if($item_type['type'] == 'Finish'){
                    $builder->Where('mi.screen',$post['item']);
                }else{
                    $builder->where('mi.pid',$post['item']);
                }
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }

                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_mill_return_BrokerWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            
            $builder = $db->table('return_mill m');
            $builder->select('m.*,mi.*,i.name,i.hsn,m.id as mill_id,,w.name as warehouse_name,ac.name as mill_name');
            $builder->join('return_mill_item mi','mi.voucher_id = m.id');
            $builder->join('item i','i.id = mi.pid');
            $builder->join('warehouse w','w.id = m.warehouse','left');
            $builder->join('account ac','ac.id = m.party_name');
            $builder->where('m.is_delete','0');
            $builder->where('m.is_cancle','0');
            $builder->where('m.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('m.party_name',$post['account']);
            }

            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('m.date >=',db_date($post['from_date']));
            }

            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('m.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('mi.pid',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('m.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('m.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_issue_BrokerWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            
            $builder = $db->table('sendJobwork j');
            $builder->select('j.*,ji.*,i.name,i.hsn,j.id as job_id,w.name as warehouse_name,ac.name as party_name');
            $builder->join('sendJob_Item ji','ji.voucher_id = j.id');
            $builder->join('item i','i.id = ji.pid');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->join('account ac','ac.id = j.account');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('j.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.account',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('ji.pid',$post['item']);
            }

            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }
            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('j.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_return_BrokerWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
       
        foreach($acc as $row){
            
            $builder = $db->table('return_jobwork j');
            $builder->select('j.*,ji.*,i.name,i.hsn,j.id as job_id,w.name as warehouse_name,ac.name as party_name');
            $builder->join('return_jobwork_item ji','ji.voucher_id = j.id');
            $builder->join('item i','i.id = ji.pid');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->join('account ac','ac.id = j.party_name');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('j.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.party_name',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }

            if(isset($post['item']) && $post['item'] != ''){
                $builder->where('ji.screen',$post['item']);
            }
            
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('j.broker',$post['broker']);
            }


            $query = $builder->get();
            $res = $query->getResultArray();
            
         
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$res;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

    public function get_job_received_BrokerWise_report($post){
        
        $db = $this->db;
        $db->setDatabase(session('DataSource'));
        $builder = $db->table('account');
        $builder->select('name,id,gst_add as address,gst');
        $query = $builder->get();
        $acc = $query->getResultArray();
        $arr=array();
        
        $gmodel = new GeneralModel;

        foreach($acc as $row){
            $builder = $db->table('recJobwork j');
            $builder->select('j.*,ji.*,i.name,i.hsn,j.id as job_id,it.name as screen_name,w.name as warehouse_name,ac.name as party_name');
            $builder->join('recJob_Item ji','ji.voucher_id = j.id');
            $builder->join('item i','i.id = ji.pid');
            $builder->join('item it','it.id = ji.screen');
            $builder->join('warehouse w','w.id = j.warehouse','left');
            $builder->join('account ac','ac.id = j.account');
            $builder->where('j.is_delete','0');
            $builder->where('j.is_cancle','0');
            $builder->where('j.broker',$row['id']);
            
            if(isset($post['account']) && $post['account'] != ''){
                $builder->where('j.account',$post['account']);
            }
            if(isset($post['from_date']) && db_date($post['from_date']) != ''){
                
                $builder->where('j.date >=',db_date($post['from_date']));
            }
            if(isset($post['to_date']) && isset($post['from_date']) && db_date($post['from_date']) != '' && db_date($post['to_date']) != ''){
                $builder->where('j.date <=',db_date($post['to_date']));
            }
        
            if(isset($post['warehouse']) && $post['warehouse'] != ''){
                $builder->where('j.warehouse',$post['warehouse']);
            }

            if(isset($post['broker']) && $post['broker'] != ''){
                $builder->where('j.broker',$post['broker']);
            }

            if(isset($post['item']) && $post['item'] != ''){
                $item_type = $gmodel->get_data_table('item',array('id'=>$post['item']),'type');
                if($item_type['type'] == 'Jobwork'){
                    $builder->Where('ji.screen',$post['item']);
                }else{
                    $builder->where('ji.pid',$post['item']);
                }
            }

            $query = $builder->get();
            $res = $query->getResultArray();

            foreach($res as $row1){
                $tax = json_decode($row1['taxes']);
                
                if(in_array('sgst',$tax)){
                    $row1['tax_type'] = 'non_igst';
                }else{
                    $row1['tax_type'] = 'igst';
                }

                $final_item[] =$row1; 
            }   
           
            if(!empty($res)){
                $data['name']=$row['name'];
                $data['gst']=$row['gst'];
                $data['address']=$row['address'];
                $data['data']=$final_item;

                $arr[] = $data;
            }
            unset($final_item);

        }
        // echo '<pre>';print_r($arr);exit;

        return $arr;
    }

}
?>