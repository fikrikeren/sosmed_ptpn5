<!-- sharing -->
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
                "url": "<?php echo site_url('Sharing/ajax_list') ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],

        });

    });


    function add_sharing() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add sharing'); // Set Title to Bootstrap modal title
    }

    <?php if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') { ?>

        function edit_sharing(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Add edit'); // Set Title to Bootstrap modal title
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('Sharing/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    $('#id_sharing').val(data.id_sharing)
                    $('#judul').val(data.judul)
                    $('#keterangan').val(data.keterangan)
                    $('#kategori').val(data.id_kategori)
                    $('#gambar').val(data.gambar)
                    $('#file').val(data.file)
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit sharing'); // Set title to Bootstrap modal title

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
        try {
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

                url = "<?php echo site_url('Sharing/ajax_add') ?>";
            } else {

                <?php if ($this->session->userdata('access') == 'superadmin' || $this->session->userdata('access') == 'admin') { ?>
                    url = "<?php echo site_url('Sharing/ajax_update') ?>";
                    file.append('id_sharing', data.get('id_sharing'));
                <?php } ?>
            }



            file.append('judul', data.get('judul'));
            file.append('keterangan', data.get('keterangan'));
            file.append('kategori', data.get('kategori'));
            file.append('userfile', data.get('userfile'));
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

                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable

                }
            });
        } catch (error) {
            alert(error);
        } finally {
            // change button back
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
        }
    }

    function Delete_Sharing(id) {
        if (confirm('apakah di hapus?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('sharing/ajax_delete') ?>/" + id,
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
                    alert(textStatus);
                }
            });
        }
    }

    function detail_sharing(id) {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#detail').modal('show'); // show bootstrap modal
        $('.modal-title').text('Close');

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('Sharing/Ajax_detail/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // console.log(data);
                // $('#id_sharing').val(data.id_sharing)
                $('#detail-gambar').attr("src", "<?= site_url("Uploads/Sharing/") ?>" + data.gambar);
                $('#detail-download-gambar').attr("href", "<?= site_url("Uploads/Sharing/") ?>" + data.gambar);
                $('#detail-download-file').attr("href", "<?= site_url("Uploads/Sharing/") ?>" + data.file);
                $('#detail-judul').text("Judul : " + data.judul)
                $('#detail-keterangan').text("Keterangan : " + data.keterangan)
                $('#detail-pengirim').text("Pengirim : " + data.nama)
                $('#detail-kategori').text("Kategori : " + data.nama_kategori)
                $('#detail-waktu').text("Waktu : " + data.waktu)
                $('#detail-kode-unit').text("Kode unit : " + data.kodeunit)
                $('#detail-sap').text("SAP : " + data.sap)
                // $('#gambar').val(data.gambar)
                // $('#file').val(data.file)

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        }); // Set Title to Bootstrap modal title
    }
</script>