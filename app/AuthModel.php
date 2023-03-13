<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Authenticator;

class AuthModel extends Model{

    public function google($data){

        $ga = new Authenticator();
        $secure_code = GCODE;
        $time_slice = floor(strtotime(CDATE) / 30);
        $otp = $ga->getCode($secure_code, $time_slice);

        if ($otp == $data['code']) {
            //sendsms($data['umobile'],smsbody('otp',$otp));
            $result = array('st' => 'success', 'msg' => 'success_google');
        } else {
            $result = array('st' => 'fail', 'msg' => 'request_create_fail');
        }

        return $result;
    }

    public function login($post) {
        $result_array = array();
        $db = $this->db;

        // if($post['username'] == 'Admin')
        // {
            $builder = $db->table('admin_master');
            $builder->select('*');
            $builder->where(array("userid" => $post['username'], 'isdelete' => '0'));
            $builder->limit(1);
            $result = $builder->get();
            $result_array = $result->getRow();

            if(empty($result_array))
            {
                $builder = $db->table('users');
                $builder->select('*');
                $builder->where(array("user_name" => $post['username'], 'is_delete' => '0'));
                $builder->limit(1);
                $result = $builder->get();
                $result_array = $result->getRow();
            }
        
        $msg = array();
        if (!empty($result_array)) {
            if (md5($post['password']) == $result_array->password) {
                if (!($result_array->isblock)) {
                    if (($result_array->isactive)) {
                        $newdata = [
                            'uid' => $result_array->id,
                            'username' => isset($result_array->username)?$result_array->username:$result_array->name,
                            'adid' => isset($result_array->adid)?$result_array->adid:'',
                            'utype' =>  $result_array->utype,
                            'gcode' =>  isset($result_array->gcode)?$result_array->gcode:'',
                            'tuid' => $result_array->id,
                            'logged_in' => TRUE
                        ];
                        $session = session();
                        $session->set($newdata);

                        $msg = array("st" => "success", "msg" => "Login Successfully!!!");
                    } else {
                        $msg = array("st" => "failed", "msg" => "Account UnActive!!!");
                    }
                } else {    
                    $msg = array("st" => "failed", "msg" => "Account Blocked!!!");
                }
            } else {
                $msg = array("st" => "failed", "msg" => "Username or Password are Wrong!!!");
            }
        } else {
            $msg = array("st" => "failed", "msg" => "Username or Password are Wrong!!!");
        }
        return $msg;
    }

    public function CreateAuth(){

        $g = new Authenticator();

        echo $secret = $g->createSecret();
        $qrCodeUrl = $g->getQRCodeGoogleUrl('Textile', 'SNNGNHTW33U44SBI');
        return '<img src="'.$qrCodeUrl.'" />';
    }
    
}