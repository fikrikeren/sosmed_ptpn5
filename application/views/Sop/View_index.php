<?php $this->load->view("Komponen/Header") ?>
<?php $this->load->view("Komponen/Navbar") ?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">SELAMAT DATANG,<?php echo $this->session->userdata('nama'); ?>
                        </h3>
                        <h6 class="font-weight-normal mb-0">Sebagai <span class="text-primary"><?php echo $this->session->userdata('access'); ?></span></h6>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="justify-content-end d-flex">
                            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                    <a class="dropdown-item" href="#">January - March</a>
                                    <a class="dropdown-item" href="#">March - June</a>
                                    <a class="dropdown-item" href="#">June - August</a>
                                    <a class="dropdown-item" href="#">August - November</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <h3>Sop Data</h3>
                                    <br />
                                    <button class="btn btn-success" onclick="add_sop()"><i class="glyphicon glyphicon-plus"></i> Add sop</button>
                                    <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                                    <br />
                                    <br />
                                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Judul</th>
                                                <th>Keterangan</th>
                                                <th>Pengirim</th>
                                                <th>Kategori</th>
                                                <th>Waktu</th>
                                                <th style="width:125px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Judul</th>
                                                <th>Keterangan</th>
                                                <th>Pengirim</th>
                                                <th>Kategori</th>
                                                <th>Waktu</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">From Sop</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input id="id_sop" type="hidden" name="id_sop" />
                    <div class="form-body">
                        <!-- Judul -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Judul</label>
                            <div class="col-md-9">
                                <input id="judul" name="judul" placeholder="Judul" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!-- Jenis -->
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3">Jenis</label>
                            <div class="col-md-9">
                                <select id="jenis" name="jenis" class="form-control">
                                    <option disabled>Pilih Jenis SOP</option>
                                    <option value="sop" selected="selected">SOP</option>
                                    <option value="ik_visual">IK Visual</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <!-- Kategori -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori</label>
                            <div class="col-md-9">
                                <select id="kategori" name="kategori" class="form-control">
                                    <?php foreach ($query as $bidang) : ?>
                                        <option value="<?php echo $bidang->id_kategori; ?>">
                                            <?php echo $bidang->nama_kategori; ?></option>
                                    <?php endforeach ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!-- File -->
                        <div id="field_file" class="form-group">
                            <label class="control-label col-md-3">File</label>
                            <div class="col-md-9">
                                <input id="file" name="userfile" placeholder="file" class="form-control" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!-- Gambar -->
                        <!-- <div id="field_gambar" class="form-group d-none">
                            <label class="control-label col-md-3">Gambar</label>
                            <div class="col-md-9">
                                <input id="gambar" name="usergambar" placeholder="file" class="form-control" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <!-- Keterangan -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan</label>
                            <div class="col-md-9">
                                <textarea id="keterangan" name="keterangan" placeholder="keteranagn" class="form-control" style="display: block">
                                </textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save();" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Bootstrap modal -->
<div class="modal fade" id="detail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Close</h3>
            </div>
            <div class="card text-center">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a id="detail-download-file" target="_blank" class="card-link" href="#">Download document (file)</a>
                        </li>
                        <li class="list-group-item">
                            <h5 id="detail-judul" class="card-title">Ini Judul</h5>
                        </li>
                        <li class="list-group-item">
                            <p id="detail-keterangan" class="card-text">Ini keterangan</p>
                        </li>
                        <li class="list-group-item">
                            <p id="detail-pengirim" class="card-text">Ini Pengirim</p>
                        </li>
                        <li class="list-group-item">
                            <p id="detail-kategori" class="card-text">Ini kategori</p>
                        </li>
                        <li class="list-group-item">
                            <p id="detail-waktu" class="card-text">Ini waktu</p>
                        </li>
                        <!-- View more -->
                        <div class="collapse" id="collapseExample">
                            <li class="list-group-item">
                                <p id="detail-kode-unit" class="card-text">Ini kode unit</p>
                            </li>
                            <li class="list-group-item">
                                <p id="detail-sap" class="card-text">Ini sap</p>
                            </li>
                        </div>
                        <li class="list-group-item">
                            <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                view more...
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>



<?php $this->load->view("Komponen/Js") ?>
<?php $this->load->view("Komponen/Ajax_sop") ?>