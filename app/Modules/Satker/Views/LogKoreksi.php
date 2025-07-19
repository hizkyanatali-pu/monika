<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Rekap Dokumen PK</title>

<style>
    .kop-rekap {
        text-align: center;
        font-weight: bold;
    }

    .tabel-logkoreksi {
        margin-top: 60px;
    }

    th {
            background-color: #ffd60a;
            color: #000;
            border: 1px solid black;

        }

    td {
        border: 1px solid black;
        font-family: Arial;
        /* text-align: center; */
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto
    }

</style>

<body>
    <h3 class="kop-rekap"> Log Koreksi Dokumen PK</h3>

    <table class="tabel-logkoreksi" width="100%">
        <tr>
            <th>Tanggal</th>
            <th>Pesan Koreksi</th>
            <th>Koreksi Oleh</th>
        </tr>
        <?php foreach($datakoreksi as $value) {
            if(!empty($value['pesan'])) { 
                ?>   
                <tr>
                    <td><?php echo $value['tanggal'] ?></td>
                    <td><?php echo $value['pesan'] ?></td>
                    <td><?php echo $value['koreksi_by'] ?></td>
                </tr>
        <?php } } ?>
    </table>
</body>
</head>
</html>