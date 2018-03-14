<?php
namespace Admin2\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class AdminController extends Controller {
    public function index(){
        // var_dump('s');exit();
        $this->display('Index/404');
    }
}