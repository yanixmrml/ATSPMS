<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee
 *
 * @author YANIX-MRML
 */
class Login extends CI_Controller{
    //put your code here
    
    
    function _construct(){
        parent::_construct();
    }
    
    function index(){
        $data["webTitle"] = "ATS - PMS Dashboard";
        $data["page_javascript"] = "assets/js/dashboard-login.js";
        if($this->system_model->hasLoggedIn()){
            redirect("dashboard/home");     
        }else{
            $data["auth"] = $this->tools->mc_encrypt($this->input->ip_address(),ENCRYPTION_KEY);
            $this->load->view("login/login_page",$data);
        }
    }
    
    function login_authentication(){
        if($_POST){
            $auth = $this->tools->mc_decrypt($this->input->post("auth"),ENCRYPTION_KEY);
            if($auth==$this->input->ip_address()){
                $nowTime = $this->system_model->getTimeNow();
                $message = "Account not found. Authentication failed.";
                if(!$this->settings->offline){
                    $dashboardAccount = $this->system_model->validateUser($_POST['username'],$_POST['password']);
                    $this->setUserSession($dashboardAccount);
                    if($dashboardAccount){
                            if(intval($dashboardAccount['status'])){
                                $this->system_model->setSession('account', array("user_id"=>$dashboardAccount['user_id'],"lastname"=>$dashboardAccount['lastname'],
                                            "firstname"=>$dashboardAccount['firstname'],
                                            "username"=>$dashboardAccount['username'],"email_address"=>$dashboardAccount['email_address'],"access_level"=>$dashboardAccount['access_level'],
                                            "contact_number"=>$dashboardAccount['contact_number'],"status"=>$dashboardAccount['status'],"date_created"=>$dashboardAccount['date_created'],
                                            "last_login"=>$dashboardAccount['last_login'],"picture"=>$dashboardAccount['picture']));
                                $_SESSION['a_id'] = $dashboardAccount["user_id"];
                                redirect("dashboard/home");
                            }else{
                                $message = "Your account is suspended. Contact the administrator immediately.";
                            }
                    }else{
                        $message = "Invalid username or password.";
                    }
                    //$data["auth"] = $this->tools->mc_encrypt($this->input->ip_address(),ENCRYPTION_KEY);
                    //$this->load->view("login/login_page",$data);
                }else{
                    $message = $this->settings->offline_message;
                }
                $_SESSION['message'] = $message;
                redirect("login");
            }
        }else{
            redirect("login");
        }
    }
    
    function setUserSession($dashboardAccount){
        $this->system_model->setSession('account', array("user_id"=>$dashboardAccount['user_id'],"lastname"=>$dashboardAccount['lastname'],
                                            "firstname"=>$dashboardAccount['firstname'],
                                            "username"=>$dashboardAccount['username'],"email_address"=>$dashboardAccount['email_address'],"access_level"=>$dashboardAccount['access_level'],
                                            "contact_number"=>$dashboardAccount['contact_number'],"status"=>$dashboardAccount['status'],"date_created"=>$dashboardAccount['date_created'],
                                            "last_login"=>$dashboardAccount['last_login'],"picture"=>$dashboardAccount['picture']));
    }
}

?>