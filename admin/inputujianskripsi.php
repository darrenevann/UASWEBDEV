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
                        <li class="breadcrumb-item active">Dashboard / Ujian Skripsi</li>
                    </ol>

                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title>Input Jadwal Ujian</title>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        if (isset($_POST["Simpan"])) {
                            $mhs_NPM = $_POST['mhsNPM'];
                            $tgl_ujian = $_POST['tglUjian'];
                            $jam_ujian = $_POST['jamUjian'];
                            $namaFile = $_FILES['fotoUjian']['name'];
                            $lokasiFile = $_FILES['fotoUjian']['tmp_name'];
                            
                            if (!file_exists('dokumen')) {
                                mkdir('dokumen', 0777, true);
                            }
                            
                            $folderTujuan = "dokumen/" . $namaFile;
                            $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                            if (!empty($namaFile) && ($fileType != "jpg" && $fileType != "jpeg")) {
                                echo "<script>alert('Format file harus JPG atau JPEG!');</script>";
                            } else {
                                move_uploaded_file($lokasiFile, $folderTujuan);
                                
                                mysqli_query($conn, "INSERT INTO ujian (mhs_NPM, tgl_ujian, jam_ujian, foto_ujian) 
                                VALUES ('$mhs_NPM', '$tgl_ujian', '$jam_ujian', '$namaFile')");
                                
                                header("Location: inputujianskripsi.php");
                            }
                        }

                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            $query = mysqli_query($conn, "SELECT DISTINCT u.*, m.mhs_Nama, d.dosen_Nama 
                                FROM ujian u
                                JOIN mahasiswa m ON u.mhs_NPM = m.mhs_NPM
                                JOIN bimbingan b ON u.mhs_NPM = b.mhs_NPM
                                JOIN dosen d ON b.dosen_NIDN = d.dosen_NIDN
                                WHERE m.mhs_Nama LIKE '%$search%'");
                        } else {
                            $query = mysqli_query($conn, "SELECT DISTINCT u.*, m.mhs_Nama, d.dosen_Nama 
                                FROM ujian u
                                JOIN mahasiswa m ON u.mhs_NPM = m.mhs_NPM
                                JOIN bimbingan b ON u.mhs_NPM = b.mhs_NPM
                                JOIN dosen d ON b.dosen_NIDN = d.dosen_NIDN");
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label for="mhsNPM" class="col-sm-2 col-form-label">Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="mhsNPM" required>
                                                    <option value="">-- Pilih Mahasiswa Peserta Ujian --</option>
                                                    <?php
                                                    $sql_mhs = mysqli_query($conn, "SELECT DISTINCT b.mhs_NPM, m.mhs_Nama 
                                                                                    FROM bimbingan b 
                                                                                    JOIN mahasiswa m ON b.mhs_NPM = m.mhs_NPM");
                                                    while ($row_mhs = mysqli_fetch_array($sql_mhs)) {
                                                        echo "<option value='" . $row_mhs['mhs_NPM'] . "'>" . $row_mhs['mhs_Nama'] . " (" . $row_mhs['mhs_NPM'] . ")</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <small class="text-muted">Dosen pembimbing akan otomatis terdeteksi dari data bimbingan.</small>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tglUjian" class="col-sm-2 col-form-label">Tanggal Ujian</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tglUjian" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="jamUjian" class="col-sm-2 col-form-label">Waktu (Jam)</label>
                                            <div class="col-sm-10">
                                                <input type="time" class="form-control" name="jamUjian" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fotoUjian" class="col-sm-2 col-form-label">Foto Ujian (JPG)</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="fotoUjian" accept=".jpg,.jpeg" required>
                                                <small class="text-danger">*Wajib format JPG</small>
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
                                    <h1 class="display-5">Jadwal Ujian Skripsi</h1>
                                </div>

                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label for="search" class="col-sm-2">Cari Mahasiswa</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" id="search"
                                                value="<?php if (isset($_POST['search'])) echo $_POST['search']; ?>"
                                                placeholder="Nama Mahasiswa">
                                        </div>
                                        <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                    </div>
                                </form>

                                <table class="table table-success table-striped">
                                    <tr class="info">
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Dosen Pembimbing</th> <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Foto</th>
                                        <th colspan="2" style="text-align: center;">Aksi</th>
                                    </tr>

                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr class="danger">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['mhs_Nama']; ?></td>
                                            <td><?php echo $row['dosen_Nama']; ?></td> <td><?php echo $row['tgl_ujian']; ?></td>
                                            <td><?php echo $row['jam_ujian']; ?></td>
                                            <td>
                                                <?php if (!empty($row['foto_ujian'])) { ?>
                                                    <img src="dokumen/<?php echo $row['foto_ujian']; ?>" width="80" style="border-radius: 5px;">
                                                <?php } else {
                                                    echo "-";
                                                } ?>
                                            </td>

                                            <td>
                                                <a href="editujianskripsi.php?id=<?php echo $row['id_ujian'] ?>" class="btn btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="hapusujianskripsi.php?id=<?php echo $row['id_ujian'] ?>" class="btn btn-danger" title="HAPUS" onclick="return confirm('Yakin hapus data?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
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