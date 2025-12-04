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
                    <h1 class="mt-4">Manajemen Galeri Foto</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Galeri</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        // --- LOGIC SIMPAN (UPLOAD) ---
                        if (isset($_POST["Simpan"])) {
                            $judul = $_POST['judul'];
                            $deskripsi = $_POST['deskripsi'];
                            $tanggal = $_POST['tanggal'];

                            // Upload Foto
                            $namaFile = $_FILES['fotoGaleri']['name'];
                            $lokasiFile = $_FILES['fotoGaleri']['tmp_name'];
                            
                            if (!file_exists('dokumen')) { mkdir('dokumen', 0777, true); }
                            $folderTujuan = "dokumen/" . $namaFile;
                            $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                            if (!empty($namaFile) && ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png")) {
                                echo "<script>alert('Format harus JPG/PNG!');</script>";
                            } else {
                                move_uploaded_file($lokasiFile, $folderTujuan);
                                
                                mysqli_query($conn, "INSERT INTO galeri (judul_foto, deskripsi, tanggal, nama_file) 
                                VALUES ('$judul', '$deskripsi', '$tanggal', '$namaFile')");
                                
                                header("Location: inputgaleri.php");
                            }
                        }

                        // --- LOGIC TAMPIL ---
                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            $query = mysqli_query($conn, "SELECT * FROM galeri WHERE judul_foto LIKE '%$search%'");
                        } else {
                            $query = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tanggal DESC");
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Judul Foto</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="judul" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Deskripsi Singkat</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Tanggal</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">File Foto</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="fotoGaleri" accept=".jpg,.jpeg,.png" required>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Simpan" name="Simpan">
                                            <input type="reset" class="btn btn-danger" value="Batal" name="Batal">
                                        </div>
                                    </div>
                                </form>

                                <div class="jumbotron mt-5 mb-3">
                                    <h1 class="display-5">Daftar Galeri</h1>
                                </div>

                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label class="col-sm-2">Cari Foto</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" placeholder="Judul Foto">
                                        </div>
                                        <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                    </div>
                                </form>

                                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                                    <?php while ($row = mysqli_fetch_array($query)) { ?>
                                        <div class="col">
                                            <div class="card h-100">
                                                <?php if (!empty($row['nama_file'])) { ?>
                                                    <img src="dokumen/<?php echo $row['nama_file']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                <?php } ?>
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo $row['judul_foto']; ?></h5>
                                                    <p class="card-text small text-muted"><?php echo $row['tanggal']; ?></p>
                                                    <p class="card-text"><?php echo $row['deskripsi']; ?></p>
                                                </div>
                                                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                                                    <a href="editgaleri.php?id=<?php echo $row['id_galeri'] ?>" class="btn btn-sm btn-success w-100 me-1">Edit</a>
                                                    <a href="hapusgaleri.php?id=<?php echo $row['id_galeri'] ?>" class="btn btn-sm btn-danger w-100 ms-1" onclick="return confirm('Hapus foto ini?')">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

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