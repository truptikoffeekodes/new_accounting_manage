<?php namespace App\Controllers;
use App\Models\UserModel;
use App\Models\GeneralModel;

class User extends BaseController{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger){
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: * ");
        $this->model = new UserModel();
        $this->gmodel = new GeneralModel();
        
    }
	public function index(){

        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        
        $data['title']="User";
        return view('user/user_list',$data); 
    }
    public function create_user($id= '')
    {
       if (!session('uid')) {
           return redirect()->to(url('auth'));
       }
       $data = array();
       $post = $this->request->getPost();
       
       if (!empty($post)){
           $msg = $this->model->insert_edit_user($post);
           return $this->response->setJSON($msg);
       }
       if($id != '') {
           $data['user'] = $this->model->get_user_byid($id);   
       }
       //print_r($data);exit;
       $data['title']="Add User";
       
       return view('user/create_user',$data);
    }
    public function Getdata($method = '') {
        if (!session('uid')) {
            return redirect()->to(url('auth'));
        }
        if ($method == 'user') {
            $get = $this->request->getGet();
            $this->model->get_users_data($get);
        }
       
    }
    public function Action($method = '') {
        $result = array();
        if ($method == 'Update') {
            $post = $this->request->getPost();
            $result = $this->model->UpdateData($post);
        }
        return $this->response->setJSON($result);
    }  

   
      
}
?>