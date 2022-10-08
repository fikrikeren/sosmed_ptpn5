<!-- Ik_visual -->
<script type="text/javascript">
    var save_method; //for save method string
    var table;

    $(document).ready(function() {

        //datatables
        table = $('#table').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('Ik_visual/ajax_list') ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],

        });

    });


    function add_Ik_visual() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add ikvisual'); // Set Title to Bootstrap modal title
    }

    <?php if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') { ?>

        function edit_Ik_visual(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Add edit'); // Set Title to Bootstrap modal title
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('Ik_visual/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    $('#id_ik').val(data.id_ik)
                    $('#judul').val(data.judul)
                    $('#alamat').val(data.alamat)
                    // $('#keterangan').val(data.keterangan)
                    $('#kategori').val(data.id_kategori)
                    $('#gambar').val(data.gambar)
                    // $('#file').val(data.file)
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit Ik_visual'); // Set title to Bootstrap modal title

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }
    <?php } ?>

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function save() {
        // * Atas
        try {
            // * Atas
            $('#btnSave').text('saving...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable

            // Validasi
            // * Judul
            if ($('#judul').val() === "") {
                throw new Error("Judul harus diisi");
            }

            // ajax adding data to database
            let formdata = document.querySelector('#form');
            let data = new FormData(formdata);
            let file = new FormData();

            var url;

            if (save_method == 'add') {
                url = "<?php echo site_url('Ik_visual/ajax_add') ?>";
            } else {

                <?php if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') { ?>
                    url = "<?php echo site_url('Ik_visual/ajax_update') ?>";
                    file.append('id_ik', data.get('id_ik'));
                <?php } ?>
            }

            file.append('judul', data.get('judul'));
            file.append('alamat', data.get('alamat'));
            // file.append('keterangan', data.get('keterangan'));
            file.append('kategori', data.get('kategori'));
            // file.append('userfile', data.get('userfile'));
            file.append('usergambar', data.get('usergambar'));

            $.ajax({
                contentType: false,
                processData: false,
                url: url,
                type: "POST",
                data: file,
                // dataType: "JSON",
                success: function(data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });

            // * bawah
        } catch (error) {
            alert(error);
        } finally {
            // change button back
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
        }
        // * Bawah
    }

    function Delete_Ik_visual(id) {
        if (confirm('apakah di hapus?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('Ik_visual/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == false) {
                        alert(data.massage);
                    }
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

    function detail_Ik_visual() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#detail').modal('show'); // show bootstrap modal
        $('.modal-title').text('Detail'); // Set Title to Bootstrap modal title
    }

    // const fieldJenis = $("#jenis");
    // fieldJenis.change((e) => {
    //     // console.log(e.target.value);
    //     let valueJenis = e.target.value;
    //     if (valueJenis === "ik_visual") {
    //         $("#field_gambar").removeClass("d-none")
    //         $("#field_alamat").removeClass("d-none")
    //         $("#field_file").addClass("d-none")
    //         // alert("ik_visual")
    //     } else if (valueJenis === "Ik_visual") {
    //         $("#field_file").removeClass("d-none")
    //         $("#field_gambar").addClass("d-none")
    //         $("#field_alamat").addClass("d-none")
    //         // alert("Ik_visual")
    //     }
    // });
</script>