        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Bukti Kas Masuk</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url("dashboard");?>">Dashboard</a></li>
                            <li class="active">Bukti Kas Masuk</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm btn-show-add" data-toggle="modal" data-target="#compose"><i class="fa fa-plus"></i> Tambah BKM</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
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
                        <h5 class="modal-title" id="largeModalLabel">Tambah BKM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="compose-form">
                            <div class="form-group">
                                <label>Nama Customer</label>
                                <input type="text" name="customer" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Kode Akun</label>
                                <select name="akunbkm" class="form-control coa">
                                <option value="">-- Pilih --</option>
                                <?php
                                foreach($coa as $akun) {
                                    echo '<option value="'.$akun->coa.'">'.$akun->nama.'</option>
                                    ';
                                }
                                ?>
                            </select>
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
                    jQuery("select[name=akunbkm]").val("");
                    jQuery("input[name=uraian]").val("");
                    jQuery("input[name=jumlah]").val("");
                    jQuery("input[name=date]").val("");
                    jQuery("#compose .modal-title").html("Tambah BKM");
                    jQuery("#compose-form").attr("action","<?=base_url("Bkm/insert");?>");
                });

                $("#data").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "autoWidth":true,
                    "order": [],
                    "ajax": {"url": "<?=base_url("Bkm/json");?>"}
                });


                $('.btn-submit').on("click",function(){
                    var form = {
                        "customer": jQuery("input[name=customer]").val(),
                        "akunbkm": jQuery("select[name=akunbkm]").val(),
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
                                jQuery("select[name=akunbkm]").val("");
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
                    jQuery.getJSON("<?=base_url();?>Bkm/delete/"+id,function(data){
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
                    var customer = jQuery(this).attr("data-customer");
                    var akunbkm = jQuery(this).attr("data-akunbkm");
                    var uraian = jQuery(this).attr("data-uraian");
                    var jumlah = jQuery(this).attr("data-jumlah");
                    var date = jQuery(this).attr("data-date");

                    jQuery("#compose .modal-title").html("Edit BKM");
                    jQuery("#compose-form").attr("action","<?=base_url();?>Bkm/update/"+id);
                    jQuery("input[name=customer]").val(uraian);
                    jQuery("select[name=akunbkm]").val(akunbkm);
                    jQuery("input[name=uraian]").val(uraian);
                    jQuery("input[name=jumlah]").val(jumlah);
                    jQuery("input[name=date]").val(date);
                    

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