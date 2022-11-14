<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mlogin');
        $this->load->model('Msharing');
        $this->load->model('Msop');
        $this->load->model('Mik_visual');
        if ($this->session->userdata('logged') != TRUE) {
            $url = base_url('login');
            redirect($url);
        };
    }

    public function index()
    {
        $data["Users"] = $this->Mlogin->get_all_users();
        $data["Sharing"] = $this->Msharing->get_all_sharing();
        $data["Sop"] = $this->Msop->get_all_sop();
        $data["ik_visual"] = $this->Mik_visual->get_all_ik_visual();

        $this->load->view('View_dasboard', $data);
    }
}
