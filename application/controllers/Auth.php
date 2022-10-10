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
				$user = $validate_ps->row_array();
				$this->session->set_userdata('logged', TRUE);
				$this->session->set_userdata('user', $sap);
				$this->session->set_userdata('id',  $user['id']);
				$this->session->set_userdata('nama', $user['nama']);
				$this->session->set_userdata('kodeunit', $user['kodeunit']);

				if ($user['level'] == '1') { //superadmin
					$this->session->set_userdata('access', 'superadmin');
				} else if ($user['level'] == '2') { //admin
					$this->session->set_userdata('access', 'admin');
				} else { //user
					$this->session->set_userdata('access', 'user');
				}

				redirect('home');
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
