<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sop extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Msop');
        if ($this->session->userdata('logged') != TRUE) {
            $url = base_url('login');
            redirect($url);
        };

        $config['upload_path']          = './Uploads/sop/';
        $config['allowed_types'] = 'pdf|docx|xls|ppt|PDF';

        $this->load->library('upload', $config);
    }

    public function index()
    {
        $this->db->from('kategorisop');
        $this->db->select('*');
        $query = $this->db->get()->result();
        $this->load->view('sop/view_index', compact('query'));
    }

    public function ajax_list()
    {
        $list = $this->Msop->get_datatables();


        $data = array();
        $no = $_POST['start'];
        foreach ($list as $datasop) {
            $no++;
            $row = array();
            $row[] = $datasop->judul;
            $row[] = $datasop->keterangan;
            $row[] = $datasop->nama;
            $row[] = $datasop->nama_kategori;
            $row[] = $datasop->waktu;

            //add html for action
            $action = '';
            if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') {
                $action .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_sop(' . "'" . $datasop->id_sop . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            }
            $action .= '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="Delete_sop(' . "'" . $datasop->id_sop . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            $action .= '<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Detail" onclick=" detail_Sop(' . "'" . $datasop->id_sop . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Detail</a>';

            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Msop->count_all(),
            "recordsFiltered" => $this->Msop->count_filtered(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    // private function do_gambar()
    // {
    //     // $config['upload_path']          = './Uploads/';
    //     // $config['allowed_types']        = 'gif|jpg|png';

    //     // $this->load->library('upload', $config);

    //     if ($this->input->post('id_sop')) {

    //         $sop = $this->Msop->get_by_id($this->input->post('id_sop'));

    //         $path = './Uploads/sop/' . $sop->gambar;

    //         if (file_exists($path)) {
    //             unlink($path);
    //         }
    //     }

    //     if (!$this->upload->do_upload('usergambar')) {
    //         $error = array('error' => $this->upload->display_errors());
    //         return false;
    //     } else {
    //         // $data = array('upload_data' => $this->upload->data('usergambar'));
    //         $data = $this->upload->data('file_name');
    //         // $namegambar = $data['file_name'];
    //         return $data;
    //     }
    // }

    private function do_file()
    {
        if ($this->input->post('id_sop')) {

            $sop = $this->Msop->get_by_id($this->input->post('id_sop'));

            $path = './Uploads/sop/' . $sop->file;

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
            $url = base_url('sop');
            // var_dump($url);
            // die;
            header('Content-Type: application/json');
            echo json_encode($url);
            return;
        };

        $data = $this->Msop->get_by_id($id);

        echo json_encode($data);
    }

    // detail user
    public function Ajax_detail($id)
    {
        $data = $this->Msop->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {

        $file = $this->do_file();
        // $gambar = $this->do_gambar();

        $data = array(
            'judul' => $this->input->post('judul'),
            'keterangan' => $this->input->post('keterangan'),
            'sap' => $this->session->get_userdata('user')['user'],
            'kodeunit' => $this->session->userdata('kodeunit'),
            // 'gambar' => $gambar,
            'file' => $file,
            'id_kategori' => $this->input->post('kategori'),
        );

        header('Content-Type: application/json');
        if ($this->Msop->save($data)) {
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function ajax_update()
    {
        if ($this->session->userdata('access') != 'superadmin' && $this->session->userdata('access') != 'admin') {
            $url = base_url('sop');
            header('Content-Type: application/json');
            echo json_encode($url);
            return;
        };

        $file = $this->do_file();
        // $gambar = $this->do_gambar();

        $data = array(
            'judul' => $this->input->post('judul'),
            'keterangan' => $this->input->post('keterangan'),
            // 'gambar'  => $gambar,
            'file'  => $file,
            'id_kategori' => $this->input->post('kategori'),

        );
        $this->Msop->update(array('id_sop' => $this->input->post('id_sop')), $data);

        header('Content-Type: application/json');
        echo  json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $sop = $this->Msop->get_by_id($id);

        if ($this->session->userdata('access') === "user") {
            // * Check apakah data yang dihapus milik user tersebut
            // * Jika tidak, lempar error
            if ($sop->sap && $sop->sap !== $this->session->get_userdata('user')['user']) {
                header('Content-Type: application/json');
                echo json_encode(array("status" => FALSE, "massage" => "bukan data anda"));
                return;
            };
        }


        // $path_file = './Uploads/datasop/' . $sop->file;
        // $path_gambar = './Uploads/datasop/' . $sop->gambar;
        $path_file = './Uploads/sop/' .  $sop->file;
        // $path_gambar = './Uploads/sop/' . $sop->gambar;


        if ($sop->file != null && file_exists($path_file)) {
            unlink($path_file);
        }

        // if ($sop->gambar != null && file_exists($path_gambar)) {
        //     unlink($path_gambar);
        // }

        $this->Msop->delete_by_id($id);

        header('Content-Type: application/json');
        echo json_encode(array("status" => TRUE));
    }
}
