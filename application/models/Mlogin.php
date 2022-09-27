<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mlogin extends CI_Model
{

    function query_validasi_sap($sap)
    {
        $result = $this->db->query("SELECT * FROM user WHERE sap='$sap' LIMIT 1");
        return $result;
    }

    function query_validasi_password($sap, $password)
    {
        $result = $this->db->query("SELECT * FROM user WHERE sap='$sap' and password='$password' LIMIT 1");
        // var_dump($result);
        // die;
        return $result;
    }
}
