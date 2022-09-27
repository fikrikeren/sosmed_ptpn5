<!-- plugins:js -->
<script src="<?php echo base_url() ?>assets/template/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="<?php echo base_url() ?>assets/template/vendors/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url() ?>assets/template/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="<?php echo base_url() ?>assets/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="<?php echo base_url() ?>assets/template/js/dataTables.select.min.js"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="<?php echo base_url() ?>assets/template/js/off-canvas.js"></script>
<script src="<?php echo base_url() ?>assets/template/js/hoverable-collapse.js"></script>
<script src="<?php echo base_url() ?>assets/template/js/template.js"></script>
<script src="<?php echo base_url() ?>assets/template/js/settings.js"></script>
<script src="<?php echo base_url() ?>assets/template/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="<?php echo base_url() ?>assets/template/js/dashboard.js"></script>
<script src="<?php echo base_url() ?>assets/template/js/Chart.roundedBarCharts.js"></script>
<!-- End custom js for this page-->

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
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable

        // ajax adding data to database
        let formdata = document.querySelector('#form');
        let data = new FormData(formdata);
        let file = new FormData();

        var url;

        if (save_method == 'add') {
            url = "<?php echo site_url('Sharing/ajax_add') ?>";
        } else {
            <?php if ($this->session->userdata('access') == 'superadmin' && $this->session->userdata('access') == 'admin') { ?>
                url = "<?php echo site_url('Sharing/ajax_update') ?>";
                file.append('id_sharing', data.get('id_sharing'));
            <?php } ?>
        }

        console.log(save_method, url)

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
    }

    function Delete_Sharing(id) {
        if (confirm('apakah di hapus?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('sharing/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
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

    function detail_sharing() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#detail').modal('show'); // show bootstrap modal
        $('.modal-title').text('Detail'); // Set Title to Bootstrap modal title
    }
</script>







</body>

</html>