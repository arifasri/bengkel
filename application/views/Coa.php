        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Charge Of Account</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Charge of Account</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah Coa</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>COA</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="compose" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Tambah COA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                            <div class="form-group">
                                <label>Kode COA</label>
                                <input type="text" name="coa" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Nama COA</label>
                                <input type="text" name="nama" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tipe COA</label>
                                <select name="tipe" class="form-control">
                                <option value="Header">Header</option>
                                <option value="Detail">Detail</option>
                              </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-submit">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="delete" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Konfirmasi?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-del-confirm">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
                $(".btn-show-add").on("click",function(){
                    jQuery("input[name=coa]").val("");
                    jQuery("input[name=nama]").val("");
                    jQuery("select[name=tipe]").val("");
                    jQuery("#compose .modal-title").html("Tambah COA");
                    jQuery("#compose-form").attr("action","<?=base_url("Coa/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("Coa/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "coa": jQuery("input[name=coa]").val(),
                        "nama": jQuery("input[name=nama]").val(),
                        "tipe": jQuery("select[name=tipe]").val()
                    }

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=coa]").val("");
                                jQuery("input[name=nama]").val("");
                                jQuery("select[name=tipe]").val("");
    
                                jQuery("#compose").modal('toggle');
                                jQuery("#data").DataTable().ajax.reload(null,true);
    
                                Swal.fire(
                                    'Berhasil',
                                    data.msg,
                                    'success'
                                )
                            } else {
                                Swal.fire(
                                    'Gagal',
                                    data.msg,
                                    'error'
                                )
                            }
                        }
                    });
                });

                $('body').on("click",".btn-delete",function() {
                    var id = jQuery(this).attr("data-id");
                    var name = jQuery(this).attr("data-nama");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+name+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>coa/delete/"+id,function(data){
                        if(data.status) {
                            jQuery("#delete").modal("toggle");
                            jQuery("#data").DataTable().ajax.reload(null,true);
                            Swal.fire(
                                'Berhasil',
                                data.msg,
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'gagal',
                                data.msg,
                                'error'
                            )
                        }
                    })
                }

                $("body").on("click",".btn-edit",function(){
                    var id = jQuery(this).attr("data-id");
                    var coa = jQuery(this).attr("data-coa");
                    var Nama = jQuery(this).attr("data-nama");
                    var tipe = jQuery(this).attr("data-tipe");

                    jQuery("#compose .modal-title").html("Edit Coa");
                    jQuery("#compose-form").attr("action","<?=base_url();?>coa/update/"+id);
                    jQuery("input[name=coa]").val(coa);
                    jQuery("input[name=nama]").val(Nama);
                    jQuery("select[name=tipe]").val(tipe);

                    jQuery("#compose").modal("toggle");
                });

        </script>