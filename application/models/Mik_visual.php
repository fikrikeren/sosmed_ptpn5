<?php

use LDAP\Result;

defined('BASEPATH') or exit('No direct script access allowed');

class Mik_visual extends CI_Model
{

    var $table = 'ik_visual';
    var $column_order = array(null, 'judul',  'id_kategori', 'alamat', 'thumbnail', 'waktu', 'kodeunit'); //set column field database for datatable orderable
    var $column_search = array('judul',  'id_kategori', 'alamat', 'thumbnail', 'waktu', 'kodeunit'); //set column field database for datatable searchable 
    var $order = array('judul' => 'asc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->from($this->table);
        $this->db->select('ik_visual.*, kategorisop.nama_kategori, user.nama');
        $this->db->join('user', 'ik_visual.sap = user.sap', 'left');
        $this->db->join('kategorisop', 'ik_visual.id_kategori = kategorisop.id_kategori', 'left');
        // $this->db->join('ik_visual', 'ik_visual.id_kategori = kategori.id_kategori', 'left outer');
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->join('user', 'ik_visual.sap = user.sap', 'left');
        $this->db->join('kategorisop', 'ik_visual.id_kategori = kategorisop.id_kategori', 'left');
        $this->db->where('id_ik', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        // var_dump($this->table, $data, $where);
        // die();
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id_ik', $id);
        $this->db->delete($this->table);
    }

    public function get_all_ik_visual()
    {
        return $this->db->count_all_results("ik_visual");
    }
}
