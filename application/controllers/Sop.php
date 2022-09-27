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
    }

    public function index()
    {
        $this->db->from('kategori');
        $this->db->select('*');
        $query = $this->db->get()->result();
        $this->load->view('sop/view_index', compact('query'));
    }

    // serveside
    // https://mbahcoding.com/tutorial/php/codeigniter/codeigniter-ajax-crud-using-bootstrap-modals-and-datatable.html
    public function ajax_list()
    {
        $list = $this->Msop->get_datatables();
        // echo '<pre>' . var_export($list, true) . '</pre>';
        // die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $data_sop) {
            $no++;
            $row = array();
            $row[] = $data_sop->judul;
            $row[] = $data_sop->keterangan;
            $row[] = $data_sop->nama;
            // $row[] = $data_sop->gambar;
            // $row[] = $data_sop->file;
            $row[] = $data_sop->nama_kategori;
            $row[] = $data_sop->waktu;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_sop(' . "'" . $data_sop->id_sop . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_sop(' . "'" . $data_sop->id_sop . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                    <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Detail" onclick="detail_sop(' . "'" . $data_sop->id_sop . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Detail</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Msop->count_all(),
            "recordsFiltered" => $this->Msop->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->Msop->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'judul' => $this->input->post('judul'),
            'keterangan' => $this->input->post('keterangan'),
            'id_kategori' => $this->input->post('kategori'),
        );
        $insert = $this->Msop->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = array(
            'judul' => $this->input->post('judul'),
            'keterangan' => $this->input->post('keterangan'),
            'id_kategori' => $this->input->post('kategori'),

        );
        $this->Msop->update(array('id_sop' => $this->input->post('id_sop')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->Msop->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
}
