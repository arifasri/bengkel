        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bukti Kas Keluar</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Bukti Kas Keluar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah BKK</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor</th>
                                    <th>Akun</th>
                                    <th>Uraian</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah BKK</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                            <div class="form-group">
                                <label>Nama Vendor</label>
                                <input type="text" name="customer" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Kode Akun</label>
                                <input type="text" name="akunbkk" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" name="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Uraian</label>
                                <input type="text" name="uraian" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" id="jumlah" value="0">
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
                    jQuery("input[name=customer]").val("");
                    jQuery("input[name=akunbkk]").val("");
                    jQuery("input[name=uraian]").val("");
                    jQuery("input[name=jumlah]").val("");
                    jQuery("input[name=date]").val("");
                    jQuery("#compose .modal-title").html("Tambah BKK");
                    jQuery("#compose-form").attr("action","<?=base_url("Bkk/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("Bkk/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "customer": jQuery("input[name=customer]").val(),
                        "akunbkk": jQuery("input[name=akunbkk]").val(),
                        "uraian": jQuery("input[name=uraian]").val(),
                        "jumlah": jQuery("input[name=jumlah]").val(),
                        "date": jQuery("input[name=date]").val()
                    }

                    var action = jQuery("#compose-form").attr("action");

                    jQuery.ajax({
                        url: action,
                        method: "POST",
                        data: form,
                        dataType: "json",
                        success: function(data){
                            if(data.status) {
                                jQuery("input[name=customer]").val("");
                                jQuery("input[name=akunbkk]").val("");
                                jQuery("input[name=uraian]").val("");
                                jQuery("input[name=jumlah]").val("");
                                jQuery("input[name=date]").val("");
    
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
                    var uraian = jQuery(this).attr("data-uraian");
                    jQuery("#delete .modal-body").html("Anda yakin ingin menghapus <b>"+uraian+"</b>");
                    jQuery("#delete").modal("toggle");

                    jQuery("#delete .btn-del-confirm").attr("onclick","deleteData("+id+")");
                })

                function deleteData(id) {
                    jQuery.getJSON("<?=base_url();?>Bkk/delete/"+id,function(data){
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
                                'Berhasil',
                                data.msg,
                                'success'
                            )
                        }
                    })
                }

                $("body").on("click",".btn-edit",function(){
                    var id = jQuery(this).attr("data-id");
                    var akunbkk = jQuery(this).attr("data-akunbkk");
                    var uraian = jQuery(this).attr("data-uraian");
                    var jumlah = jQuery(this).attr("data-jumlah");

                    jQuery("#compose .modal-title").html("Edit BKk");
                    jQuery("#compose-form").attr("action","<?=base_url();?>Bkk/update/"+id);
                    jQuery("input[name=akunbkk]").val(akunbkk);
                    jQuery("input[name=uraian]").val(uraian);
                    jQuery("input[name=jumlah]").val(jumlah);

                    jQuery("#compose").modal("toggle");
                });

        </script>
         <script>
    // ... Your existing script ...

    // Add this datepicker initialization code
    $(document).ready(function() {
        $('input[name="date"]').datepicker({
            format: 'yyyy-mm-dd', // You can change the format according to your needs
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>