<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\GeneralModel;

class UserModel extends Model
{
 
    public function insert_edit_user($post){
        
        $db = $this->db;
        //print_r($db);exit;
        $builder = $db->table('users');
        $builder->select('*');
        $builder->where(array("id" => $post['id'],'is_delete'=>0, 'isblock'=>0));
        $builder->limit(1);
        $result = $builder->get();
        $result_array = $result->getRowArray();


        $builder = $db->table('users');
        $builder->select('*');
        $builder->where(array("user_name" => $post['user_name'],'is_delete'=>0, 'isblock'=>0));
        $builder->where(array("id !=" => $post['id']));
        $builder->limit(1);
        $result = $builder->get();
        $result_array1 = $result->getRowArray();
        //print_r($result_array1);exit;
       // $msg = array();

        if(!empty($result_array1))
        {
            $msg = array('st'=>'fail','msg'=>'User Name Already exist!!!');
            return $msg;
        }
    
        
        
        
        $pdata = array(
            'name' => $post['name'],
            'user_name' => $post['user_name'],
            'email' => @$post['email'],
            'contact' => @$post['contact'],
            
            //'utype' => 2,
            
        );
      
        if (!empty($result_array)) {
            if(!empty($post['password']))
            {
                if($post['password'] != $post['conform_password'])
                {
                    $msg = array('st'=>'fail','msg'=>'Password and Confirm Password not matched');
                    return $msg;
                }
                else
                {
                    $pdata['password'] = md5(@$post['password']);
                }
            }
            else
            {
                $pdata['password'] = $result_array['password'];
            }
            
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
            if($post['password'] != $post['conform_password'])
            {
                $msg = array('st'=>'fail','msg'=>'Password and Confirm Password not matched');
                return $msg;
            }
        
            $pdata['password'] = md5(@$post['password']);
            $pdata['created_at'] = date('Y-m-d H:i:s');
            $pdata['created_by'] = session('uid');
            //$this->db->setDatabase('manifest_erp');
            $result = $builder->Insert($pdata);
                
                if ($result) {
                    $msg = array('st' => 'success', 'msg' => "Your Details Added Successfully!!!");
                } else {
                    $msg = array('st' => 'fail', 'msg' => "Your Details Updated fail");
                }
            
        }
        return $msg;
    }
    public function get_users_data($get){
        
        $dt_search = $dt_col = array(
           "id",
           "name",
           "user_name",
           "email",
           "contact",
           "isblock",
           
        );
    
        $filter = $get['filter_data'];
        $tablename = "users";
        $where = '';
      
        $where .= " and is_delete=0";
    
        $rResult = getManagedData($tablename, $dt_col, $dt_search, $where);
        $sEcho = $rResult['draw'];
    
        $encode = array(); 
        $statusarray = array("1" => "Bloked", "0" => "Unblocked");
        foreach ($rResult['table'] as $row) { 
            
            $DataRow = array();
            $btnedit = ' <a data-toggle="modal" href="' . url('User/create_user/') . $row['id'] . '" data-target="#fm_model" data-title="Edit Company : ' . $row['name'] . '"  class="btn btn-link pd-10"><i class="far fa-edit"></i></a>';
            //$btnview = '<a  href="' . url('User/user_view/') . $row['id'] . '"  class="btn btn-link pd-10"><i class="far fa-eye"></i></a> ';
            $btndelete = '<a data-toggle ="modal" target="_blank"   title="User Name: ' . $row['name'] . '"  onclick="editable_remove(this)"  data-val="' . $row['id'] . '"  data-pk="' . $row['id'] . '" tabindex="-1" class="btn btn-link pd-10"><i class="far fa-trash-alt"></i></a> ';
            $status= '<a target="_blank"   title="User: ' . $row['id'] . '" onclick="editable_os(this)"  data-val="' . $row['isblock'] . '"  data-pk="' . $row['id'] . '" tabindex="-1"  >' . $statusarray[$row['isblock']] . '</a>';
           
            $btn = $btnedit . $btndelete;

            $DataRow[] = $row['id'];
            $DataRow[] =$row['user_name'];
            $DataRow[] =$row['name'];
            $DataRow[] =$row['email'];
            $DataRow[] = $row['contact'];
            $DataRow[] = $status;
            $DataRow[] = $btn;
            $encode[] = $DataRow;
        }
    
        $json = json_encode($encode);
        echo '{ "draw": ' . intval($sEcho) . ',"recordsTotal": ' . $rResult['total'] . ',"recordsFiltered": ' . $rResult['total'] . ',"data":' . $json . '}';
        exit;
    }
    public function UpdateData($post) {
        $result = array();
        $db = $this->db;
        if ($post['type'] == 'Block') {
            
            if ($post['method'] == 'user') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('users', array('id' => $post['pk']), array('isblock' => '1'));
            }
        }
        if ($post['type'] == 'Remove') {
            
            if ($post['method'] == 'user') {
                $gnmodel = new GeneralModel();
                $result = $gnmodel->update_data_table('users', array('id' => $post['pk']), array('is_delete' => '1'));
            }
        }
        return $result;
    }

    public function get_user_byid($id){
        $db = $this->db;
        $builder = $db->table('users');
        $builder->select('*');
        $builder->where(array("id" => $id));
        $builder->limit(1);
        $result = $builder->get();
        $result_row = $result->getRowArray();
        return $result_row;
        
       
    }

   
}
?>