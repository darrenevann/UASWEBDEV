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
                    <h1 class="mt-4">Edit Galeri</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Galeri Alumni</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        // Ambil Data Lama
                        if (isset($_GET['id'])) {
                            $id_galeri = $_GET['id'];
                            $query_edit = mysqli_query($conn, "SELECT * FROM galeri WHERE id_galeri = '$id_galeri'");
                            $row_edit = mysqli_fetch_array($query_edit);
                        }

                        // --- UPDATE ---
                        if (isset($_POST["Ubah"])) {
                            $id_lama = $_POST['idGaleri'];
                            $judul = $_POST['judul'];
                            $deskripsi = $_POST['deskripsi'];
                            $tanggal = $_POST['tanggal'];
                            $file_lama = $_POST['fileLama'];

                            $namaFileBaru = $_FILES['fotoGaleri']['name'];
                            $lokasiFile = $_FILES['fotoGaleri']['tmp_name'];

                            if (!empty($namaFileBaru)) {
                                $folderTujuan = "dokumen/" . $namaFileBaru;
                            
                                if (!empty($file_lama) && file_exists("dokumen/" . $file_lama)) {
                                    unlink("dokumen/" . $file_lama);
                                }
                                move_uploaded_file($lokasiFile, $folderTujuan);

                                mysqli_query($conn, "UPDATE galeri SET 
                                    judul_foto = '$judul', deskripsi = '$deskripsi', 
                                    tanggal = '$tanggal', nama_file = '$namaFileBaru' 
                                    WHERE id_galeri = '$id_lama'");
                            } else {
                                mysqli_query($conn, "UPDATE galeri SET 
                                    judul_foto = '$judul', deskripsi = '$deskripsi', 
                                    tanggal = '$tanggal' 
                                    WHERE id_galeri = '$id_lama'");
                            }
                            echo "<script>alert('Galeri berhasil diubah'); document.location='inputgaleri.php'</script>";
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idGaleri" value="<?php echo $row_edit['id_galeri']; ?>">
                                    <input type="hidden" name="fileLama" value="<?php echo $row_edit['nama_file']; ?>">

                                    <div class="row mb-3 mt-5">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Judul Foto</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="judul" value="<?php echo $row_edit['judul_foto']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Deskripsi</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="deskripsi" rows="3"><?php echo $row_edit['deskripsi']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Tanggal</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tanggal" value="<?php echo $row_edit['tanggal']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Foto</label>
                                            <div class="col-sm-10">
                                                <p class="mb-1">File saat ini: <?php echo $row_edit['nama_file']; ?></p>
                                                <input type="file" class="form-control" name="fotoGaleri" accept=".jpg,.jpeg,.png">
                                                <small class="text-danger">*Biarkan kosong jika tidak ganti foto</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <a href="inputgaleri.php" class="btn btn-danger">Batal</a>
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