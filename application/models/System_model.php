<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of studentModel
 *
 * @author YANIX-MRML
 */
class System_model extends CI_Model{
    //put your code here
    private $password_min_length = 6;
    private $username_min_length = 3;
    
    public function _construct(){
        parent::_construct();
    }
    
    /********************* Time Functions ************************/
    
    public function getCurrentDate(){
        $dateNow = new DateTime();
        return date_format($dateNow,"Y-m-d");
    }
    
    public function getTimeNow(){
        $query = $this->db->query("SELECT NOW()");
        if($query->num_rows() > 0){
                return $query->row_array();
        }
        return FALSE;
    }
    
        /******************* Update/Change Password ***************/
    
    public function changePassword($user_id,$old_password,$new_password){
        if(is_numeric($user_id) && strlen($new_password)>=$this->password_min_length
           && strlen($old_password)>=$this->password_min_length){
            $sql1 = "CALL change_password(?,?,?,@out_status);";
            $query1 = $this->db->query($sql1,array($old_password,$new_password,$user_id));
            $sql2 = "SELECT @out_status;";
            $query2 = $this->db->query($sql2);
            if($query2->num_rows() > 0){
                return $query2->row_array();
            }
        }
        return FALSE;
    }
    
    /****************** Login / Session ******************/
    
    public function validateUser($username, $password){
        if(strlen($username) >=$this->username_min_length && strlen($password)>=$this->password_min_length){
            $sql = "SELECT DISTINCT u.* FROM user  u WHERE u.username = ? AND u.password = PASSWORD(?) ORDER BY u.date_created DESC";
            $query = $this->db->query($sql,array($username,$password));
            if($query->num_rows() > 0){
                $r =  $query->row_array();
                if(count($r)>0){
                    $sql = "UPDATE user u SET u.last_login = NOW() WHERE u.user_id = ?";
                    $query = $this->db->query($sql,intval($r["user_id"]));
                    return $r;
                }
            }
        }
        return FALSE;
    }
    
    public function setSession($id,$value){
        $CI =& get_instance();
        $CI->session->set_userdata($id, $value);
    }
    
    public function hasLoggedIn(){
        $CI =& get_instance();
        return ($CI->session->userdata('account') &&
                isset($_SESSION['a_id']));
    }

    public function logout(){
        $CI =& get_instance();
        $CI->session->sess_destroy();
        unset($_SESSION['a_id']);
        session_destroy();
    }
    
}
?>
