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
                    <h1 class="mt-4">Edit Berita</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Berita</li>
                    </ol>

                    <body>
                        <?php
                        include("includes/config.php");

                        // Ambil Data Lama
                        if (isset($_GET['id'])) {
                            $beritaID = $_GET['id'];
                            $query_edit = mysqli_query($conn, "SELECT * FROM berita WHERE beritaID = '$beritaID'");
                            $row_edit = mysqli_fetch_array($query_edit);
                        }

                        // --- UPDATE DATA ---
                        if (isset($_POST["Ubah"])) {
                            $id_lama = $_POST['beritaID'];
                            $kategoriID = $_POST['kategoriID'];
                            $beritaJudul = $_POST['beritaJudul'];
                            $beritaIsi = $_POST['beritaIsi'];
                            $beritaTgl = $_POST['beritaTgl'];
                            $file_lama = $_POST['fileLama'];

                            // Cek File Baru
                            $namaFileBaru = $_FILES['beritaFoto']['name'];
                            $lokasiFile = $_FILES['beritaFoto']['tmp_name'];

                            if (!empty($namaFileBaru)) {
                                $folderTujuan = "dokumen/" . $namaFileBaru;
                                // Hapus file lama fisik
                                if (!empty($file_lama) && file_exists("dokumen/" . $file_lama)) {
                                    unlink("dokumen/" . $file_lama);
                                }
                                move_uploaded_file($lokasiFile, $folderTujuan);

                                mysqli_query($conn, "UPDATE berita SET 
                                    kategoriID = '$kategoriID', beritaJudul = '$beritaJudul', 
                                    beritaIsi = '$beritaIsi', beritaTgl = '$beritaTgl', 
                                    beritaFoto = '$namaFileBaru' WHERE beritaID = '$id_lama'");
                            } else {
                                mysqli_query($conn, "UPDATE berita SET 
                                    kategoriID = '$kategoriID', beritaJudul = '$beritaJudul', 
                                    beritaIsi = '$beritaIsi', beritaTgl = '$beritaTgl' 
                                    WHERE beritaID = '$id_lama'");
                            }
                            echo "<script>alert('Berita berhasil diubah'); document.location='inputberita.php'</script>";
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="beritaID" value="<?php echo $row_edit['beritaID']; ?>">
                                    <input type="hidden" name="fileLama" value="<?php echo $row_edit['beritaFoto']; ?>">

                                    <div class="row mb-3 mt-5">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Kategori</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="kategoriID">
                                                    <?php
                                                    $sql_kat = mysqli_query($conn, "SELECT * FROM kategori_berita");
                                                    while ($r = mysqli_fetch_array($sql_kat)) {
                                                        $selected = ($r['kategoriID'] == $row_edit['kategoriID']) ? "selected" : "";
                                                        echo "<option value='".$r['kategoriID']."' $selected>".$r['kategoriNama']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Judul</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="beritaJudul" value="<?php echo $row_edit['beritaJudul']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Isi</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="beritaIsi" rows="5"><?php echo $row_edit['beritaIsi']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Tanggal</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="beritaTgl" value="<?php echo $row_edit['beritaTgl']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Foto</label>
                                            <div class="col-sm-10">
                                                <p class="mb-1">Saat ini: <?php echo $row_edit['beritaFoto']; ?></p>
                                                <input type="file" class="form-control" name="beritaFoto" accept=".jpg,.jpeg,.png">
                                                <small class="text-danger">*Biarkan kosong jika tidak ganti foto</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <a href="inputberita.php" class="btn btn-danger">Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </body>
                </div>
            </main>
            <?php include "bagiankode/footer.php"; ?>
        </div>
    </div>
    <?php include "bagiankode/jsscript.php"; ?>
</body>
</html>