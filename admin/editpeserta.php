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
                    <h1 class="mt-4">Edit Peserta Skripsi</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Peserta Skripsi</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include "includes/config.php";

                        $NPM_original = $_GET['ubahNPM'];

                        // --- LOGIC UPDATE ---
                        if (isset($_POST["Ubah"])) {
                            $mhs_NPM = $_POST['mhs_NPM'];
                            $peserta_SEMT = $_POST['peserta_SEMT'];
                            $peserta_THAKD = $_POST['peserta_THAKD'];
                            $peserta_TGLDAFTAR = $_POST['peserta_TGLDAFTAR'];
                            $peserta_JUDUL = $_POST['peserta_JUDUL'];

                            $NPM_original_dari_form = $_POST['NPM_original'];
                            $file_lama = $_POST['file_lama'];

                            // Cek apakah ada file baru
                            if (isset($_FILES['peserta_DOKUMEN']) && $_FILES['peserta_DOKUMEN']['name'] != "") {
                                $peserta_DOKUMEN = $_FILES['peserta_DOKUMEN']['name'];
                                $tmp = $_FILES['peserta_DOKUMEN']['tmp_name'];
                                
                                // Pastikan folder dokumen ada
                                if (!file_exists('dokumen')) {
                                    mkdir('dokumen', 0777, true);
                                }
                                
                                move_uploaded_file($tmp, 'dokumen/' . $peserta_DOKUMEN);
                            } else {
                                $peserta_DOKUMEN = $file_lama;
                            }

                            mysqli_query($conn, "UPDATE peserta SET 
                                mhs_NPM = '$mhs_NPM', 
                                peserta_SEMT = '$peserta_SEMT', 
                                peserta_THAKD = '$peserta_THAKD', 
                                peserta_TGLDAFTAR = '$peserta_TGLDAFTAR', 
                                peserta_JUDUL = '$peserta_JUDUL', 
                                peserta_DOKUMEN = '$peserta_DOKUMEN' 
                                WHERE mhs_NPM = '$NPM_original_dari_form'");

                            echo "<script>alert('Data berhasil diubah');
                            document.location='inputpeserta.php'</script>";
                        }

                        // --- LOGIC FETCH DATA ---
                        $query_edit = mysqli_query($conn, "SELECT * FROM peserta WHERE mhs_NPM = '$NPM_original'");
                        $row_edit = mysqli_fetch_array($query_edit);

                        $datamhs = mysqli_query($conn, "SELECT * FROM mahasiswa");

                        $query_mhs_spesifik = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE mhs_NPM = '$NPM_original'");
                        $row_mhs_spesifik = mysqli_fetch_array($query_mhs_spesifik);
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="jumbotron mt-5 mb-3">
                                    <h3>Form Edit Peserta</h3>
                                </div>

                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="file_lama" value="<?php echo $row_edit['peserta_DOKUMEN']; ?>">
                                    <input type="hidden" name="NPM_original" value="<?php echo $NPM_original; ?>">

                                    <div class="row mb-3">
                                        <label for="mhs_NPM" class="col-sm-2 col-form-label">NPM Mahasiswa</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" id="mhs_NPM" name="mhs_NPM" required>
                                                <option value="<?php echo $row_mhs_spesifik['mhs_NPM']; ?>" selected>
                                                    <?php echo $row_mhs_spesifik['mhs_NPM']; ?> - <?php echo $row_mhs_spesifik['mhs_Nama']; ?>
                                                </option>

                                                <?php while ($row_mhs = mysqli_fetch_array($datamhs)) {
                                                    if ($row_mhs['mhs_NPM'] != $NPM_original) {
                                                ?>
                                                        <option value="<?php echo $row_mhs['mhs_NPM']; ?>">
                                                            <?php echo $row_mhs['mhs_NPM']; ?> - <?php echo $row_mhs['mhs_Nama']; ?>
                                                        </option>
                                                <?php
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="peserta_SEMT" class="col-sm-2 col-form-label">Semester</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="peserta_SEMT" name="peserta_SEMT" required>
                                                <option value="">-- Pilih Semester --</option>
                                                <option value="Ganjil" <?php if ($row_edit['peserta_SEMT'] == 'Ganjil') echo 'selected'; ?>>
                                                    Ganjil
                                                </option>
                                                <option value="Genap" <?php if ($row_edit['peserta_SEMT'] == 'Genap') echo 'selected'; ?>>
                                                    Genap
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="peserta_THAKD" class="col-sm-2 col-form-label">Tahun Akademik</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="peserta_THAKD" name="peserta_THAKD" required
                                                value="<?php echo $row_edit['peserta_THAKD']; ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="peserta_TGLDAFTAR" class="col-sm-2 col-form-label">Tanggal Daftar</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="peserta_TGLDAFTAR" name="peserta_TGLDAFTAR" required
                                                value="<?php echo $row_edit['peserta_TGLDAFTAR']; ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="peserta_JUDUL" class="col-sm-2 col-form-label">Judul Skripsi</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="peserta_JUDUL" name="peserta_JUDUL" required
                                                value="<?php echo $row_edit['peserta_JUDUL']; ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="peserta_DOKUMEN" class="col-sm-2 col-form-label">Upload Gambar</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" id="peserta_DOKUMEN" name="peserta_DOKUMEN" accept=".jpg,.jpeg,.png">
                                            <small class="text-muted">
                                                File saat ini: 
                                                <?php 
                                                    if(!empty($row_edit['peserta_DOKUMEN'])) {
                                                        echo $row_edit['peserta_DOKUMEN']; 
                                                    } else {
                                                        echo "Tidak ada file";
                                                    }
                                                ?> 
                                                <br>
                                                <span class="text-danger">*Kosongkan jika tidak ingin mengubah gambar.</span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
                                            <a href="inputpeserta.php" class="btn btn-danger">Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('.select2').select2();
                            });
                        </script>
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