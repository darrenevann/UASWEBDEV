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
                    <h1 class="mt-4">Manajemen Berita</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Berita</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        if (isset($_POST["Simpan"])) {
                            $kategoriID = $_POST['kategoriID'];
                            $beritaJudul = $_POST['beritaJudul'];
                            $beritaIsi = $_POST['beritaIsi'];
                            $beritaTgl = $_POST['beritaTgl'];
                            $namaFile = $_FILES['beritaFoto']['name'];
                            $lokasiFile = $_FILES['beritaFoto']['tmp_name'];
                            
                            if (!file_exists('dokumen')) { mkdir('dokumen', 0777, true); }
                            
                            $folderTujuan = "dokumen/" . $namaFile;
                            $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                            if (!empty($namaFile) && ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png")) {
                                echo "<script>alert('File harus format Gambar (JPG/PNG)!');</script>";
                            } else {
                                move_uploaded_file($lokasiFile, $folderTujuan);
                                
                                mysqli_query($conn, "INSERT INTO berita 
                                (kategoriID, beritaJudul, beritaIsi, beritaTgl, beritaFoto) 
                                VALUES ('$kategoriID', '$beritaJudul', '$beritaIsi', '$beritaTgl', '$namaFile')");
                                
                                header("Location: inputberita.php");
                            }
                        }

                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            $query = mysqli_query($conn, "SELECT b.*, k.kategoriNama 
                                FROM berita b
                                JOIN kategori_berita k ON b.kategoriID = k.kategoriID
                                WHERE b.beritaJudul LIKE '%$search%'");
                        } else {
                            $query = mysqli_query($conn, "SELECT b.*, k.kategoriNama 
                                FROM berita b
                                JOIN kategori_berita k ON b.kategoriID = k.kategoriID
                                ORDER BY b.beritaTgl DESC");
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Kategori</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="kategoriID" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <?php
                                                    $sql_kat = mysqli_query($conn, "SELECT * FROM kategori_berita");
                                                    while ($r = mysqli_fetch_array($sql_kat)) {
                                                        echo "<option value='".$r['kategoriID']."'>".$r['kategoriNama']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Judul Berita</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="beritaJudul" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Isi Berita</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="beritaIsi" rows="5" required></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Tanggal Publish</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="beritaTgl" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Foto Utama</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="beritaFoto" accept=".jpg,.jpeg,.png" required>
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
                                    <h1 class="display-5">Daftar Berita</h1>
                                </div>

                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label class="col-sm-2">Cari Berita</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" placeholder="Judul Berita">
                                        </div>
                                        <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                    </div>
                                </form>

                                <table class="table table-success table-striped">
                                    <tr class="info">
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Foto</th>
                                        <th colspan="2" class="text-center">Aksi</th>
                                    </tr>

                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr class="danger">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['kategoriNama']; ?></td>
                                            <td><?php echo $row['beritaJudul']; ?></td>
                                            <td><?php echo $row['beritaTgl']; ?></td>
                                            <td>
                                                <?php if (!empty($row['beritaFoto'])) { ?>
                                                    <img src="dokumen/<?php echo $row['beritaFoto']; ?>" width="80" style="border-radius: 5px;">
                                                <?php } else { echo "-"; } ?>
                                            </td>

                                            <td>
                                                <a href="editberita.php?id=<?php echo $row['beritaID'] ?>" class="btn btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="hapusberita.php?id=<?php echo $row['beritaID'] ?>" class="btn btn-danger" onclick="return confirm('Hapus berita ini?')" title="HAPUS">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </body>
                </div>
            </main>
            <?php include "bagiankode/footer.php"; ?>
        </div>
    </div>
    <?php include "bagiankode/jsscript.php"?>