<!DOCTYPE html>
<html>
<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}
?>

<?php
include "bagiankode/head.php";
?>

<body class="sb-nav-fixed">
    <?php
    include "bagiankode/menunav.php";
    ?>
    <div id="layoutSidenav">
        <?php
        include "bagiankode/menu.php";
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title>Edit Bimbingan</title>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        // AMBIL DATA LAMA BERDASARKAN ID
                        if (isset($_GET['id'])) {
                            $id_bimbingan = $_GET['id'];
                            $edit = mysqli_query($conn, "SELECT * FROM bimbingan WHERE id_bimbingan = '$id_bimbingan'");
                            $row_edit = mysqli_fetch_array($edit);
                        }

                        // LOGIC UPDATE DATA
                        if (isset($_POST["Ubah"])) {
                            $id_lama = $_POST['idBimbingan']; // hidden input
                            $dosen_NIDN = $_POST['dosenNIDN'];
                            $mhs_NPM = $_POST['mhsNPM'];
                            $tgl_bimbingan = $_POST['tglBimbingan'];
                            $isi_bimbingan = $_POST['isiBimbingan'];

                            // Cek apakah ada file baru diupload
                            $namaFileBaru = $_FILES['fileDokumen']['name'];
                            $lokasiFile = $_FILES['fileDokumen']['tmp_name'];

                            if (!empty($namaFileBaru)) {
                                // JIKA GANTI FILE
                                $folderTujuan = "dokumen/" . $namaFileBaru;
                                $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                                if ($fileType != "pdf") {
                                    echo "<script>alert('File baru harus format PDF!');</script>";
                                } else {
                                    move_uploaded_file($lokasiFile, $folderTujuan);

                                    // Update dengan file baru
                                    mysqli_query($conn, "UPDATE bimbingan SET 
                                        dosen_NIDN='$dosen_NIDN',
                                        mhs_NPM='$mhs_NPM',
                                        tgl_bimbingan='$tgl_bimbingan',
                                        isi_bimbingan='$isi_bimbingan',
                                        file_bimbingan='$namaFileBaru'
                                        WHERE id_bimbingan='$id_lama'");

                                    echo "<script>alert('Data & File Berhasil Diubah'); document.location='inputbimbinganskripsi.php'</script>";
                                }
                            } else {
                                // JIKA TIDAK GANTI FILE (Update data teks saja)
                                mysqli_query($conn, "UPDATE bimbingan SET 
                                    dosen_NIDN='$dosen_NIDN',
                                    mhs_NPM='$mhs_NPM',
                                    tgl_bimbingan='$tgl_bimbingan',
                                    isi_bimbingan='$isi_bimbingan'
                                    WHERE id_bimbingan='$id_lama'");
                                
                                echo "<script>alert('Data Berhasil Diubah'); document.location='inputbimbinganskripsi.php'</script>";
                            }
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="jumbotron mt-5 mb-3">
                                    <h3>Edit Data Bimbingan</h3>
                                </div>

                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idBimbingan" value="<?php echo $row_edit['id_bimbingan']; ?>">

                                    <div class="row mb-3 mt-3">

                                        <div class="row mb-3">
                                            <label for="dosenNIDN" class="col-sm-2 col-form-label">Dosen Pembimbing</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="dosenNIDN">
                                                    <option value="">-- Pilih Dosen --</option>
                                                    <?php
                                                    $sql_dosen = mysqli_query($conn, "SELECT * FROM dosen");
                                                    while ($row_dosen = mysqli_fetch_array($sql_dosen)) {
                                                        // Logic agar terpilih otomatis (SELECTED)
                                                        $selected = ($row_dosen['dosen_NIDN'] == $row_edit['dosen_NIDN']) ? "selected" : "";
                                                        echo "<option value='" . $row_dosen['dosen_NIDN'] . "' $selected>" . $row_dosen['dosen_Nama'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="mhsNPM" class="col-sm-2 col-form-label">Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="mhsNPM">
                                                    <option value="">-- Pilih Mahasiswa --</option>
                                                    <?php
                                                    // Ambil dari tabel PESERTA sesuai logic input
                                                    $sql_mhs = mysqli_query($conn, "SELECT p.mhs_NPM, m.mhs_Nama, p.peserta_JUDUL 
                                                                                    FROM peserta p 
                                                                                    JOIN mahasiswa m ON p.mhs_NPM = m.mhs_NPM");
                                                    
                                                    // Fallback
                                                    if (!$sql_mhs) {
                                                        $sql_mhs = mysqli_query($conn, "SELECT mhs_NPM, mhs_Nama FROM mahasiswa");
                                                    }

                                                    while ($row_mhs = mysqli_fetch_array($sql_mhs)) {
                                                        // Logic agar terpilih otomatis (SELECTED)
                                                        $selected = ($row_mhs['mhs_NPM'] == $row_edit['mhs_NPM']) ? "selected" : "";
                                                        
                                                        $judul = isset($row_mhs['peserta_JUDUL']) ? " - " . $row_mhs['peserta_JUDUL'] : "";
                                                        echo "<option value='" . $row_mhs['mhs_NPM'] . "' $selected>" . $row_mhs['mhs_Nama'] . $judul . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tglBimbingan" class="col-sm-2 col-form-label">Tanggal</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tglBimbingan" value="<?php echo $row_edit['tgl_bimbingan']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="isiBimbingan" class="col-sm-2 col-form-label">Isi Bimbingan</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="isiBimbingan" rows="3"><?php echo $row_edit['isi_bimbingan']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fileDokumen" class="col-sm-2 col-form-label">File PDF</label>
                                            <div class="col-sm-10">
                                                <p class="text-muted mb-1">File saat ini: 
                                                    <?php 
                                                        if(!empty($row_edit['file_bimbingan'])) {
                                                            echo "<b>".$row_edit['file_bimbingan']."</b>";
                                                        } else {
                                                            echo "Tidak ada file";
                                                        }
                                                    ?>
                                                </p>
                                                <input type="file" class="form-control" name="fileDokumen" accept=".pdf">
                                                <small class="text-danger">*Biarkan kosong jika tidak ingin mengganti file.</small>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <a href="inputbimbinganskripsi.php" class="btn btn-danger">Batal</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
                    </body>
                </div>
            </main>
            <?php
            include "bagiankode/footer.php";
            ?>
        </div>
    </div>
    <?php
    include "bagiankode/jsscript.php";
    ?>
</body>

</html>