<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ik_visual extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mik_visual');
        if ($this->session->userdata('logged') != TRUE) {
            $url = base_url('login');
            redirect($url);
        };

        $config['upload_path']          = './Uploads/ik_visual/';
        $config['allowed_types'] = 'gif|jpg|png';

        $this->load->library('upload', $config);
    }

    public function index()
    {
        $this->db->from('kategorisop');
        $this->db->select('*');
        $query = $this->db->get()->result();
        $this->load->view('Ik_visual/view_index', compact('query'));
    }

    public function ajax_list()
    {
        $list = $this->Mik_visual->get_datatables();


        $data = array();
        $no = $_POST['start'];
        foreach ($list as $data_ikvisual) {
            $no++;
            $row = array();
            $row[] = $data_ikvisual->judul;
            $row[] = $data_ikvisual->alamat;
            $row[] = $data_ikvisual->thumbnail;
            $row[] = $data_ikvisual->sap;
            $row[] = $data_ikvisual->waktu;
            $row[] = $data_ikvisual->kodeunit;

            //add html for action
            $action = '';
            if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') {
                $action .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_Ik_visual(' . "'" . $data_ikvisual->id_ik . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            }
            $action .= '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="Delete_Ik_visual(' . "'" . $data_ikvisual->id_ik . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            $action .= '<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Detail" onclick="detail_Ik_visual(' . "'" . $data_ikvisual->id_ik . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Detail</a>';

            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mik_visual->count_all(),
            "recordsFiltered" => $this->Mik_visual->count_filtered(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    private function do_gambar()
    {
        // $config['upload_path']          = './Uploads/';
        // $config['allowed_types']        = 'gif|jpg|png';

        // $this->load->library('upload', $config);

        if ($this->input->post('id_sop')) {

            $sop = $this->Mik_visual->get_by_id($this->input->post('id_sop'));

            $path = './Uploads/ik_visual/' . $sop->gambar;

            if (file_exists($path)) {
                unlink($path);
            }
        }

        if (!$this->upload->do_upload('usergambar')) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            // $data = array('upload_data' => $this->upload->data('usergambar'));
            $data = $this->upload->data('file_name');
            // $namegambar = $data['file_name'];
            return $data;
        }
    }

    public function ajax_edit($id)
    {
        // harus kedua-nya false
        if (
            $this->session->userdata('access') != 'superadmin' &&
            $this->session->userdata('access') != 'admin'
        ) {
            $url = base_url('sop');
            header('Content-Type: application/json');
            echo json_encode($url);
            return;
        };

        $data = $this->Mik_visual->get_by_id($id);

        echo json_encode($data);
    }

    public function Ajax_detail($id)
    {
        $data = $this->Mik_visual->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {

        $gambar = $this->do_gambar();
        $data = array(
            'judul' => $this->input->post('judul'),
            'alamat' => $this->input->post('alamat'),
            // 'keterangan' => $this->input->post('keterangan'),
            'sap' => $this->session->get_userdata('user')['user'],
            'thumbnail' => $gambar,
            'kodeunit' => $this->session->userdata('kodeunit'),
            'id_kategori' => $this->input->post('kategori'),
        );

        header('Content-Type: application/json');
        if ($this->Mik_visual->save($data)) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function ajax_update()
    {

        if ($this->session->userdata('access') != 'superadmin' && $this->session->userdata('access') != 'admin') {
            $url = base_url('sop');
            // var_dump($url);
            // die;
            header('Content-Type: application/json');
            echo json_encode($url);
            return;
        };

        $gambar = $this->do_gambar();


        $data = array(
            'judul' => $this->input->post('judul'),
            'alamat' => $this->input->post('alamat'),
            // 'keterangan' => $this->input->post('keterangan'),
            'thumbnail'  => $gambar,
            'id_kategori' => $this->input->post('kategori'),
        );


        $this->Mik_visual->update(array('id_ik' => $this->input->post('id_ik')), $data);

        header('Content-Type: application/json');
        echo  json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $ik = $this->Mik_visual->get_by_id($id);

        // ? superadmin, bisa hapus semua data
        // ? admin, bisa hapus semua data
        // ? user, hanya bisa menghapus data sendiri

        if ($this->session->userdata('access') === "user") {
            // * Check apakah data yang dihapus milik user tersebut
            // * Jika tidak, lempar error
            if ($ik->sap && $ik->sap !== $this->session->get_userdata('user')['user']) {
                header('Content-Type: application/json');
                echo json_encode(array("status" => FALSE, "massage" => "bukan data anda"));
                return;
            };
        }

        // $path_file = './Uploads/data_ikvisual/' . $ik->file;
        // $path_gambar = './Uploads/data_ikvisual/' . $ik->gambar;
        $path_thumbnail = './Uploads/ik_visual/' . $ik->thumbnail;

        if ($ik->thumbnail != null && file_exists($path_thumbnail)) {
            unlink($path_thumbnail);
        }

        $this->Mik_visual->delete_by_id($id);

        header('Content-Type: application/json');
        echo json_encode(array("status" => TRUE));
    }
}
