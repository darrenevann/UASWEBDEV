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
                        <title>Data Bimbingan</title>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        // --- LOGIC SIMPAN DATA ---
                        if (isset($_POST["Simpan"])) {
                            $dosen_NIDN = $_POST['dosenNIDN'];
                            $mhs_NPM = $_POST['mhsNPM'];
                            $tgl_bimbingan = $_POST['tglBimbingan'];
                            $isi_bimbingan = $_POST['isiBimbingan'];

                            // Logic Upload File
                            $namaFile = $_FILES['fileDokumen']['name'];
                            $lokasiFile = $_FILES['fileDokumen']['tmp_name'];
                            
                            // Pastikan folder dokumen ada
                            if (!file_exists('dokumen')) {
                                mkdir('dokumen', 0777, true);
                            }
                            
                            $folderTujuan = "dokumen/" . $namaFile;
                            
                            // Validasi sederhana (PDF only)
                            $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));
                            
                            if (!empty($namaFile) && $fileType != "pdf") {
                                echo "<script>alert('File harus format PDF!');</script>";
                            } else {
                                move_uploaded_file($lokasiFile, $folderTujuan);
                                
                                mysqli_query($conn, "INSERT INTO bimbingan (dosen_NIDN, mhs_NPM, tgl_bimbingan, isi_bimbingan, file_bimbingan) 
                                VALUES ('$dosen_NIDN', '$mhs_NPM', '$tgl_bimbingan', '$isi_bimbingan', '$namaFile')");
                                
                                header("Location: inputbimbinganskripsi.php");
                            }
                        }

                        // --- LOGIC PENCARIAN & TAMPIL DATA ---
                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            // Query Join dengan pencarian
                            $query = mysqli_query($conn, "SELECT b.*, d.dosen_Nama, m.mhs_Nama 
                                FROM bimbingan b
                                JOIN dosen d ON b.dosen_NIDN = d.dosen_NIDN
                                JOIN mahasiswa m ON b.mhs_NPM = m.mhs_NPM
                                WHERE m.mhs_Nama LIKE '%$search%' OR b.isi_bimbingan LIKE '%$search%'");
                        } else {
                            // Query Join standar
                            $query = mysqli_query($conn, "SELECT b.*, d.dosen_Nama, m.mhs_Nama 
                                FROM bimbingan b
                                JOIN dosen d ON b.dosen_NIDN = d.dosen_NIDN
                                JOIN mahasiswa m ON b.mhs_NPM = m.mhs_NPM");
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label for="dosenNIDN" class="col-sm-2 col-form-label">Dosen Pembimbing</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="dosenNIDN" required>
                                                    <option value="">-- Pilih Dosen --</option>
                                                    <?php
                                                    $sql_dosen = mysqli_query($conn, "SELECT * FROM dosen");
                                                    while ($row_dosen = mysqli_fetch_array($sql_dosen)) {
                                                        echo "<option value='" . $row_dosen['dosen_NIDN'] . "'>" . $row_dosen['dosen_Nama'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="mhsNPM" class="col-sm-2 col-form-label">Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="mhsNPM" required>
                                                    <option value="">-- Pilih Mahasiswa (Peserta) --</option>
                                                    <?php
                                                    // PERBAIKAN LOGIC: Mengambil data dari tabel 'peserta' (sesuai nama file inputpeserta)
                                                    // Pastikan tabel Anda namanya 'peserta' atau 'peserta_skripsi'
                                                    // Jika error, ganti 'peserta' di bawah menjadi nama tabel yang benar di database Anda.
                                                    $sql_mhs = mysqli_query($conn, "SELECT p.mhs_NPM, m.mhs_Nama, p.peserta_JUDUL 
                                                                                    FROM peserta p 
                                                                                    JOIN mahasiswa m ON p.mhs_NPM = m.mhs_NPM");
                                                    
                                                    // Fallback jika tabel peserta belum ada/kosong, ambil dari mahasiswa langsung (opsional)
                                                    if (!$sql_mhs) {
                                                        $sql_mhs = mysqli_query($conn, "SELECT mhs_NPM, mhs_Nama FROM mahasiswa");
                                                    }

                                                    while ($row_mhs = mysqli_fetch_array($sql_mhs)) {
                                                        // Cek apakah kolom judul ada (jika pakai fallback)
                                                        $judul = isset($row_mhs['peserta_JUDUL']) ? " - " . $row_mhs['peserta_JUDUL'] : "";
                                                        echo "<option value='" . $row_mhs['mhs_NPM'] . "'>" . $row_mhs['mhs_Nama'] . $judul . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="tglBimbingan" class="col-sm-2 col-form-label">Tanggal</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tglBimbingan" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="isiBimbingan" class="col-sm-2 col-form-label">Isi Bimbingan</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="isiBimbingan" rows="3" placeholder="Masukkan ringkasan bimbingan" required></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fileDokumen" class="col-sm-2 col-form-label">File Dokumen (PDF)</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="fileDokumen" accept=".pdf" required>
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
                                    <h1 class="display-5">Daftar Bimbingan</h1>
                                </div>

                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label for="search" class="col-sm-2">Cari Data</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" id="search"
                                                value="<?php if (isset($_POST['search'])) echo $_POST['search']; ?>"
                                                placeholder="Cari Nama Mahasiswa / Isi Bimbingan">
                                        </div>
                                        <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                    </div>
                                </form>

                                <table class="table table-success table-striped">
                                    <tr class="info">
                                        <th>No</th>
                                        <th>Nama Dosen</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Tanggal</th>
                                        <th>Isi Bimbingan</th>
                                        <th>File</th>
                                        <th colspan="2" style="text-align: center;">Aksi</th>
                                    </tr>

                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr class="danger">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['dosen_Nama']; ?></td>
                                            <td><?php echo $row['mhs_Nama']; ?></td>
                                            <td><?php echo $row['tgl_bimbingan']; ?></td>
                                            <td><?php echo $row['isi_bimbingan']; ?></td>
                                            <td>
                                                <?php if (!empty($row['file_bimbingan'])) { ?>
                                                    <a href="dokumen/<?php echo $row['file_bimbingan']; ?>" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                                    </a>
                                                <?php } else {
                                                    echo "-";
                                                } ?>
                                            </td>

                                            <td>
                                                <a href="editbimbinganskripsi.php?id=<?php echo $row['id_bimbingan'] ?>" class="btn btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="hapusbimbinganskripsi.php?id=<?php echo $row['id_bimbingan'] ?>" class="btn btn-danger" title="HAPUS" onclick="return confirm('Yakin hapus data?')">
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