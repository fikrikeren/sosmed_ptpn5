<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sharing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Msharing');
        if ($this->session->userdata('logged') != TRUE) {
            $url = base_url('login');
            redirect($url);
        };



        $config['upload_path']          = './Uploads/sharing';
        $config['allowed_types'] = 'pdf|docx|xls|ppt|PDF|gif|jpg|png';

        $this->load->library('upload', $config);
    }

    public function index()
    {
        $this->db->from('kategori');
        $this->db->select('*');
        $query = $this->db->get()->result();
        $this->load->view('sharing/view_index', compact('query'));
    }

    public function ajax_list()
    {
        $list = $this->Msharing->get_datatables();

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $data_sharing) {
            $no++;
            $row = array();
            $row[] = $data_sharing->judul;
            $row[] = $data_sharing->keterangan;
            $row[] = $data_sharing->nama;
            $row[] = $data_sharing->nama_kategori;
            $row[] = $data_sharing->waktu;

            //add html for action
            $action = '';
            if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') {
                $action .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_sharing(' . "'" . $data_sharing->id_sharing . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            }
            $action .= '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="Delete_Sharing(' . "'" . $data_sharing->id_sharing . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            $action .= '<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Detail" onclick="detail_sharing(' . "'" . $data_sharing->id_sharing . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Detail</a>';

            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Msharing->count_all(),
            "recordsFiltered" => $this->Msharing->count_filtered(),
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

        if ($this->input->post('id_sharing')) {

            $sharing = $this->Msharing->get_by_id($this->input->post('id_sharing'));

            $path = './Uploads/sharing' . $sharing->gambar;

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

    private function do_file()
    {
        if ($this->input->post('id_sharing')) {

            $sharing = $this->Msharing->get_by_id($this->input->post('id_sharing'));

            $path = './Uploads/sharing' . $sharing->file;

            if (file_exists($path)) {
                unlink($path);
            }
        }

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            return false;
        } else {
            // $data = array('upload_data' => $this->upload->data('userfile'));
            $data = $this->upload->data('file_name');
            // $namefile = $data['file_name'];
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
            $url = base_url('Sharing');
            header('Content-Type: application/json');
            echo json_encode($url);
            return;
        };

        $data = $this->Msharing->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {

        $file = $this->do_file();
        $gambar = $this->do_gambar();

        $data = array(
            'judul' => $this->input->post('judul'),
            'keterangan' => $this->input->post('keterangan'),
            'sap' => $this->session->get_userdata('user')['user'],
            // 'kodeunit' => $this->session->get_userdata('user')['kodeunit'],
            'gambar' => $gambar,
            'file' => $file,
            'id_kategori' => $this->input->post('kategori'),

        );
        header('Content-Type: application/json');
        if ($this->Msharing->save($data)) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function ajax_update()
    {
        if ($this->session->userdata('access') != 'superadmin' && $this->session->userdata('access') != 'admin') {
            $url = base_url('Sharing');
            // var_dump($url);
            // die;
            header('Content-Type: application/json');
            echo json_encode($url);
            return;
        };

        $file = $this->do_file();
        $gambar = $this->do_gambar();

        $data = array(
            'judul' => $this->input->post('judul'),
            'keterangan' => $this->input->post('keterangan'),
            'gambar'  => $gambar,
            'file'  => $file,
            'id_kategori' => $this->input->post('kategori'),

        );
        $this->Msharing->update(array('id_sharing' => $this->input->post('id_sharing')), $data);

        header('Content-Type: application/json');
        echo  json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $sharing = $this->Msharing->get_by_id($id);

        if ($sharing->sap !== $this->session->get_userdata('user')['user']) {
            header('Content-Type: application/json');
            echo json_encode(array("status" => FALSE, "massage" => "bukan data anda"));

            return;
        };

        // $path_file = './Uploads/data_sharing/' . $sharing->file;
        // $path_gambar = './Uploads/data_sharing/' . $sharing->gambar;
        $path_file = './Uploads/sharing/' . $sharing->file;
        $path_gambar = './Uploads/sharing/' . $sharing->gambar;

        if ($sharing->file != null && file_exists($path_file)) {
            unlink($path_file);
        }
        if ($sharing->gambar != null && file_exists($path_gambar)) {
            unlink($path_gambar);
        }

        $this->Msharing->delete_by_id($id);

        header('Content-Type: application/json');
        echo json_encode(array("status" => TRUE));
    }
}
