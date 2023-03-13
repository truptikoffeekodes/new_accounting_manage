<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\GeneralModel;

class Auth extends BaseController {

    public function index() {

        if (session('uid')) {
            return redirect()->to(url(''));
        }
        $msg = array('st' => '', 'msg' => '');
        $post = $this->request->getPost();
        if (!empty($post) && isset($post['username']) && isset($post['password'])) {
            $authmodel = new AuthModel();
            $msg = $authmodel->login($post);
        }
        $data['msg'] = $msg;
        if ($msg['st'] == 'success')
           // return redirect()->to(url('auth/google'));
		    return redirect()->to(url(''));
        else
            return view('auth/login', $data);
    }

    public function CreateAuth(){
        
        $authmodel = new AuthModel();
        echo $authmodel->CreateAuth();
    }

    public function Google() {

        $msg = array('st' => '', 'msg' => '');
        $post = $this->request->getPost();
	
        if (!empty($post)  && isset($post['code'])) {
            $authmodel = new AuthModel();
            $msg = $authmodel->Google($post);
			if($msg['st']=='success'){
	
                helper('cookie');           
                $cookie_name = "gcode";
                $cookie_value = md5(GCODE);
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
			}
        }
	
        $data['msg'] = $msg;
        if ($msg['st'] == 'success')
            return redirect()->to(url(''));
        else
            return view('auth/google_auth', $data);
		
    }
    
    public function logout() {
        $session = session();
        $data = [
            'uid',
            'username',
            'utype',
            'logged_in',
			
        ];
        $session->remove($data);
        $cdata = [
            'cid',
            'name',
            'DataSource',
            'company_group',
            'code',
            'email',
        ];
        $session->remove($cdata);
       
        return redirect()->to(url(''));
    }
    
    public function auth_qr()
    {
        $auth=new AuthModel();
        echo $auth->CreateAuth();
    }
}