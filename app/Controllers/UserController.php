<?php namespace Ylc\AppBackendApi\Controllers;


class UserController extends Controller
{
    public function doLogin(){
        $rules = [
        'username' => 'required',
        'password' => 'required'
        ];
        
        $this->validate($this->request->post(), $rules);
        
        //以下是验证通过的情况下
        return $this->json(['status' => 'validation passes.']);
    }
    
    public function doRegister(){
        
    }
}