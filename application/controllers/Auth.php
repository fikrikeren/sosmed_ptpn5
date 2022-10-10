<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mlogin', 'Mlogin');
	}

	function index()
	{
		if ($this->session->userdata('logged') != TRUE) {
			$this->load->view('view_login');
		} else {
			$url = base_url('home');
			redirect($url);
		};
	}

	function autentikasi()
	{
		$sap = $this->input->post('sap');
		$password = $this->input->post('pass');
		$validasi_sap = $this->Mlogin->query_validasi_sap($sap);
		if ($validasi_sap->num_rows() > 0) {
			$validate_ps = $this->Mlogin->query_validasi_password($sap, $password);
			if ($validate_ps->num_rows() > 0) {
				$x = $validate_ps->row_array();
				if ($x['user_status'] == '0') {
					$this->session->set_userdata('logged', TRUE);
					$this->session->set_userdata('user', $sap);
					
					$id = $x['id'];
					if ($x['level'] == '1') { //superadmin
						$name = $x['nama'];
						$this->session->set_userdata('access', 'superadmin');
						$this->session->set_userdata('id', $id);
						$this->session->set_userdata('nama', $name);
						redirect('home');
					} else if ($x['level'] == '2') { //admin
						$name = $x['nama'];
						$this->session->set_userdata('access', 'admin');
						$this->session->set_userdata('id', $id);
						$this->session->set_userdata('nama', $name);
						redirect('home');
					} else if ($x['level'] == '0') { //user
						$name = $x['nama'];
						$this->session->set_userdata('access', 'user');
						$this->session->set_userdata('id', $id);
						$this->session->set_userdata('nama', $name);
						redirect('home');
					}
				} else {

					redirect('Auth');
				}
			} else {
				redirect('Auth');
			}
		} else {

			redirect('Auth');
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		$url = base_url('Auth');
		redirect($url);
	}
}
