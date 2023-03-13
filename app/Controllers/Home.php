<?php namespace App\Controllers;

class Home extends BaseController{

	public function index(){
		//echo '<pre>';Print_r(session('cid'));exit;
		

		if(session('cid')) { 
			$data['title'] = "Dashboard";
			return view('home/index');
		} else {
			$data['title'] = "Company";
			return view('company/index',$data);
		} 
	}
}

