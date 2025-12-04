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

<?php include "bagiankode/head.php"; ?>

<body class="sb-nav-fixed">
    <?php include "bagiankode/menunav.php"; ?>
    
    <div id="layoutSidenav">
        <?php include "bagiankode/menu.php"; ?>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Penerima Beasiswa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Beasiswa</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        // --- 1. AMBIL DATA LAMA ---
                        if (isset($_GET['id'])) {
                            $id_beasiswa = $_GET['id'];
                            $query_edit = mysqli_query($conn, "SELECT * FROM beasiswa_penerima WHERE id_beasiswa = '$id_beasiswa'");
                            $row_edit = mysqli_fetch_array($query_edit);
                        }

                        // --- 2. PROSES UPDATE ---
                        if (isset($_POST["Ubah"])) {
                            $id_lama = $_POST['idBeasiswa'];
                            $mhs_NPM = $_POST['mhsNPM'];
                            $sumber_id = $_POST['sumberID'];
                            $periode_id = $_POST['periodeID'];
                            $nominal = $_POST['nominal'];
                            
                            $file_lama = $_POST['fileLama'];
                            
                            // Cek File Baru
                            $namaFileBaru = $_FILES['berkas']['name'];
                            $lokasiFile = $_FILES['berkas']['tmp_name'];

                            if (!empty($namaFileBaru)) {
                                // JIKA GANTI FILE
                                $folderTujuan = "dokumen/" . $namaFileBaru;
                                $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                                // Validasi PDF
                                if ($fileType != "pdf") {
                                    echo "<script>alert('Gagal! Berkas harus format PDF.');</script>";
                                } else {
                                    // Hapus file lama fisik jika ada
                                    if (!empty($file_lama) && file_exists("dokumen/" . $file_lama)) {
                                        unlink("dokumen/" . $file_lama);
                                    }

                                    move_uploaded_file($lokasiFile, $folderTujuan);

                                    // Update DB dengan file baru
                                    mysqli_query($conn, "UPDATE beasiswa_penerima SET 
                                        mhs_NPM = '$mhs_NPM',
                                        sumber_id = '$sumber_id',
                                        periode_id = '$periode_id',
                                        nominal = '$nominal',
                                        berkas_syarat = '$namaFileBaru'
                                        WHERE id_beasiswa = '$id_lama'");

                                    echo "<script>alert('Data berhasil diubah'); document.location='inputbeasiswa.php'</script>";
                                }
                            } else {
                                // JIKA TIDAK GANTI FILE
                                mysqli_query($conn, "UPDATE beasiswa_penerima SET 
                                    mhs_NPM = '$mhs_NPM',
                                    sumber_id = '$sumber_id',
                                    periode_id = '$periode_id',
                                    nominal = '$nominal'
                                    WHERE id_beasiswa = '$id_lama'");
                                
                                echo "<script>alert('Data berhasil diubah'); document.location='inputbeasiswa.php'</script>";
                            }
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="jumbotron mt-5 mb-3">
                                    <h3>Edit Data Pengajuan</h3>
                                </div>

                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idBeasiswa" value="<?php echo $row_edit['id_beasiswa']; ?>">
                                    <input type="hidden" name="fileLama" value="<?php echo $row_edit['berkas_syarat']; ?>">

                                    <div class="row mb-3 mt-3">

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="mhsNPM" required>
                                                    <option value="">-- Pilih Mahasiswa --</option>
                                                    <?php
                                                    $sql_mhs = mysqli_query($conn, "SELECT mhs_NPM, mhs_Nama FROM mahasiswa");
                                                    while ($r = mysqli_fetch_array($sql_mhs)) {
                                                        // Logic Selected
                                                        $selected = ($r['mhs_NPM'] == $row_edit['mhs_NPM']) ? "selected" : "";
                                                        echo "<option value='".$r['mhs_NPM']."' $selected>".$r['mhs_NPM']." - ".$r['mhs_Nama']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Sumber Dana</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="sumberID" required>
                                                    <option value="">-- Pilih Sumber --</option>
                                                    <?php
                                                    $sql_sumber = mysqli_query($conn, "SELECT * FROM beasiswa_sumber");
                                                    while ($r = mysqli_fetch_array($sql_sumber)) {
                                                        $selected = ($r['sumber_id'] == $row_edit['sumber_id']) ? "selected" : "";
                                                        echo "<option value='".$r['sumber_id']."' $selected>".$r['nama_sumber']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Periode</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="periodeID" required>
                                                    <option value="">-- Pilih Periode --</option>
                                                    <?php
                                                    // Tampilkan semua periode (termasuk yang tidak aktif, takutnya data lama pakai periode lama)
                                                    $sql_per = mysqli_query($conn, "SELECT * FROM beasiswa_periode");
                                                    while ($r = mysqli_fetch_array($sql_per)) {
                                                        $selected = ($r['periode_id'] == $row_edit['periode_id']) ? "selected" : "";
                                                        echo "<option value='".$r['periode_id']."' $selected>".$r['nama_periode']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Nominal</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="nominal" value="<?php echo $row_edit['nominal']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Berkas (PDF)</label>
                                            <div class="col-sm-10">
                                                <p class="mb-1 text-muted">
                                                    File saat ini: 
                                                    <?php 
                                                        if(!empty($row_edit['berkas_syarat'])) {
                                                            echo "<b>".$row_edit['berkas_syarat']."</b>";
                                                        } else {
                                                            echo "Tidak ada file";
                                                        }
                                                    ?>
                                                </p>
                                                <input type="file" class="form-control" name="berkas" accept=".pdf">
                                                <small class="text-danger">*Biarkan kosong jika tidak ingin mengganti file.</small>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <a href="inputbeasiswa.php" class="btn btn-danger">Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
                        <script> $(document).ready(function() { $('.select2').select2(); }); </script>
                    </body>
                </div>
            </main>
            <?php include "bagiankode/footer.php"; ?>
        </div>
    </div>
    <?php include "bagiankode/jsscript.php"; ?>
</body>
</html>