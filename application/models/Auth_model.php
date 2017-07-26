<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    /*
     * @author zulyantara <zulyantara@gmail.com>
     * @copyright copyright 2016 zulyantara
     */

    function __construct()
    {
        parent::__construct();
    }

    function validate($username, $userpassword)
    {
        // $hashAndSalt = $this->get_password($username);
        // $password_hash = password_hash($userpassword, PASSWORD_BCRYPT, array("cost" => 12));

        $this->db->select("user_id, user_name, user_password, user_level");
        $this->db->where('user_name', $username);
        $query = $this->db->get('user');

        $row_user = $query->num_rows() > 0 ? $query->row() : FALSE;
        if ($row_user !== FALSE)
        {
            if (password_verify($userpassword,$row_user->user_password))
            {
                return $query->row();
                // return array("user_id"=>$row_user->user_id,"user_name"=>$row_user->user_name,"user_email"=>$row_user->user_email);
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    function get_profil()
    {
        $this->db->select("profil_nama,profil_alamat,profil_telp,profil_kota");
        $qry = $this->db->get("profil");
        return $qry->num_rows() > 0 ? $qry->row() : FALSE;
    }

    function get_data_by_id($id)
    {
        $sql = "select * from user where user_id=".$id;
        $qry = $this->db->query($sql);
        return ($qry->num_rows() > 0) ? $qry->row() : FALSE;
    }

    function update_password($data = array())
    {
        $id = $data["txt_user_id"];
        $password = password_hash($data["txt_new_password"], PASSWORD_BCRYPT, array('cost'=>12));
        $date = "'".date("Y-m-d H:i:s")."'";
        $user_id = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;

        $sql = "UPDATE user SET user_password='".$password."',user_update_date=".$date.",user_update_user=".$user_id." WHERE user_id=".$id;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function check_password($data = array())
    {
        $id = $data["txt_user_id"];
        $password = $data["txt_old_password"];

        $sql = "SELECT user_password, count(*) AS jml FROM user WHERE user_id = ".$id;
        $qry = $this->db->query($sql);
        $row = $qry->row();

        if (password_verify($password,$row->user_password))
        {
            return $qry->row();
            // return array("user_id"=>$row_user->user_id,"user_name"=>$row_user->user_name,"user_email"=>$row_user->user_email);
        }
        else
        {
            return FALSE;
        }
    }

    function insert_user($data)
    {
        $user_name = $data["user_name"];
        $user_password = password_hash($data["user_password"], PASSWORD_BCRYPT, array('cost'=>12));
        $user_email = $data["user_email"];
        $user_level = $data["user_level"];
        $user_insert_date = date("Y-m-d H:i:s");
        $user_insert_user = $this->session->userdata("isLoggedIn") === TRUE ? $this->session->userdata("userId") : 0;

        $sql = "INSERT INTO user(user_name,user_password,user_email,user_level,user_insert_date,user_insert_user) VALUES('".$user_name."','".$user_password."','".$user_email."',".$user_level.",'".$user_insert_date."',".$user_insert_user.")";
        $qry = $this->db->query($sql);

        return $this->db->affected_rows();
    }

    function delete_user($id)
    {
        $sql = "DELETE FROM user WHERE user_id=".$id;
        $qry = $this->db->query($sql);
        return $this->db->affected_rows();
    }

    function check_duplicate($parameter){
        $sql = "SELECT COUNT(*) as jml FROM user WHERE ".$parameter;

        //echo $sql;exit;

        $qry = $this->db->query($sql);
        return $qry->row();
    }
}

/* End of file auth_model.php */
/* Location: ./application/controllers/auth_model.php */
