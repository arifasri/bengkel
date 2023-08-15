<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bkk_pdf</title>

    <style>
        body {
            font-size: 16px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .header h1 {
            text-decoration: underline;
            font-size: 22px;
        }

        .table {
            margin-top: 18px;
            border-collapse: collapse;
        }

        .table tr,td,th {
            border: 1px solid #000;
            padding: 3px 9px
        }
        

    </style>
</head>
<body>
    <div class="header">
        <h1>BUKTI KAS KELUAR</h1>
    </div>

   <!-- <table width="100%" style="margin-top: 24px">
        <tr>
            <td width="50%" valign="top" style="border:none;padding:0">
                <table>
                    <tr>
                        <td style="border:none;padding: 0 9px 3px 0" valign="top">KODE AKUN</td>
                        <td style="border:none;padding: 0 9px 3px 0" valign="top">:</td>
                        <td style="border:none;padding: 0 9px 3px 0" valign="top"><?=$fetch->coa;?></td>
                    </tr>
                    <tr>
                        <td style="border:none;padding: 0 9px 3px 0" valign="top">NAMA AKUN</td>
                        <td style="border:none;padding: 0 9px 3px 0" valign="top">:</td>
                        <td style="border:none;padding: 0 9px 3px 0" valign="top"><?=$fetch->nama;?></td>
                    </tr>
    
                </table>
            </td>
            <td width="50%" valign="top" style="border:none;padding:0">
                <table style="float:right">
                    <tr>
                        <td style="border:none" valign="top">Tanggal</td>
                        <td style="border:none" valign="top">:</td>
                        <td style="border:none" valign="top"><?=date("d/m/Y",strtotime($fetch->date));?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    -->
    <table class="table" width="100%">
    <tr>
                <td rowspan="2" >Diterima dari:<br> <?=($fetch->customer);?></td>
                <td rowspan="2" style="text-align:center">BUKTI KAS KELUAR</td>
                <td >Tanggal :</td>
    </tr>
    <tr><td > <?=date("d/m/Y",strtotime($fetch->date));?></td></tr>
    </table>    
    <table class="table" width="100%">
        <thead>
            
            <tr>
                <th>#</th>
                <th>Perkiraan</th>
                <th colspan="2" >Uraian</th>
                <th >Jumlah</th>
             <!--   <th>Sub-Total</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach($details as $detail) {
                $i++;
            ?>

                <tr>
                    <td style="text-align:center"><?=$i;?></td>
                    <td ><?=$detail->akunbkk;?></td>
                    <td colspan="2" style="text-align:center"><?=($detail->uraian);?></td>
                    <td style="text-align:right"><?=rupiah($detail->jumlah);?></td>
                </tr>

            <?php
            }
            ?>
        </tbody>
    </table>
    <table class="table" width="100%">
            <tr>
                <td width="20%" rowspan="2">CATATAN:</td>
                <td>Pembukuan</td>
                <td>Mengetahui</td>
                <td>Kasir</td>
                <td>Penerima</td>
            </tr>
            <tr >
                <td ><br><br><br></td>
                <td ><br><br><br></td>
                <td ><br><br><br></td>
                <td ><br><br><br></td>
            </tr>
        </table>
</body>
</html>