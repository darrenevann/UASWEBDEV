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
                    <h1 class="mt-4">Pendaftaran Beasiswa</h1>
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

                        // --- LOGIC SIMPAN DATA ---
                        if (isset($_POST["Simpan"])) {
                            $mhs_NPM = $_POST['mhsNPM'];
                            $sumber_id = $_POST['sumberID'];
                            $periode_id = $_POST['periodeID'];
                            $nominal = $_POST['nominal'];
                            $status = "Diajukan"; // Default status

                            // Upload Berkas
                            $namaFile = $_FILES['berkas']['name'];
                            $lokasiFile = $_FILES['berkas']['tmp_name'];
                            
                            if (!file_exists('dokumen')) { mkdir('dokumen', 0777, true); }
                            $folderTujuan = "dokumen/" . $namaFile;
                            $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                            if (!empty($namaFile) && $fileType != "pdf") {
                                echo "<script>alert('Berkas harus format PDF!');</script>";
                            } else {
                                move_uploaded_file($lokasiFile, $folderTujuan);
                                
                                mysqli_query($conn, "INSERT INTO beasiswa_penerima 
                                (mhs_NPM, sumber_id, periode_id, nominal, berkas_syarat, status_pengajuan) 
                                VALUES ('$mhs_NPM', '$sumber_id', '$periode_id', '$nominal', '$namaFile', '$status')");
                                
                                header("Location: inputbeasiswa.php");
                            }
                        }

                        // --- LOGIC TAMPIL DATA (JOIN 3 TABEL) ---
                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            $query = mysqli_query($conn, "SELECT bp.*, m.mhs_Nama, s.nama_sumber, p.nama_periode
                                FROM beasiswa_penerima bp
                                JOIN mahasiswa m ON bp.mhs_NPM = m.mhs_NPM
                                JOIN beasiswa_sumber s ON bp.sumber_id = s.sumber_id
                                JOIN beasiswa_periode p ON bp.periode_id = p.periode_id
                                WHERE m.mhs_Nama LIKE '%$search%' OR s.nama_sumber LIKE '%$search%'");
                        } else {
                            $query = mysqli_query($conn, "SELECT bp.*, m.mhs_Nama, s.nama_sumber, p.nama_periode
                                FROM beasiswa_penerima bp
                                JOIN mahasiswa m ON bp.mhs_NPM = m.mhs_NPM
                                JOIN beasiswa_sumber s ON bp.sumber_id = s.sumber_id
                                JOIN beasiswa_periode p ON bp.periode_id = p.periode_id");
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select2" name="mhsNPM" required>
                                                    <option value="">-- Pilih Mahasiswa --</option>
                                                    <?php
                                                    $sql_mhs = mysqli_query($conn, "SELECT mhs_NPM, mhs_Nama FROM mahasiswa");
                                                    while ($r = mysqli_fetch_array($sql_mhs)) {
                                                        echo "<option value='".$r['mhs_NPM']."'>".$r['mhs_NPM']." - ".$r['mhs_Nama']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Sumber Beasiswa</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="sumberID" required>
                                                    <option value="">-- Pilih Sumber Dana --</option>
                                                    <?php
                                                    $sql_sumber = mysqli_query($conn, "SELECT * FROM beasiswa_sumber");
                                                    while ($r = mysqli_fetch_array($sql_sumber)) {
                                                        echo "<option value='".$r['sumber_id']."'>".$r['nama_sumber']."</option>";
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
                                                    $sql_per = mysqli_query($conn, "SELECT * FROM beasiswa_periode WHERE status_aktif='Aktif'");
                                                    while ($r = mysqli_fetch_array($sql_per)) {
                                                        echo "<option value='".$r['periode_id']."'>".$r['nama_periode']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Nominal Pengajuan</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="nominal" placeholder="Contoh: 5000000" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label">Berkas Syarat (PDF)</label>
                                            <div class="col-sm-10">
                                                <input type="file" class="form-control" name="berkas" accept=".pdf" required>
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
                                    <h1 class="display-5">Data Penerima Beasiswa</h1>
                                </div>

                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label class="col-sm-2">Cari Data</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" placeholder="Nama Mahasiswa / Sumber Beasiswa">
                                        </div>
                                        <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                    </div>
                                </form>

                                <table class="table table-success table-striped">
                                    <tr class="info">
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Sumber Dana</th>
                                        <th>Periode</th>
                                        <th>Nominal</th>
                                        <th>Berkas</th>
                                        <th colspan="2" class="text-center">Aksi</th>
                                    </tr>

                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr class="danger">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['mhs_Nama']; ?></td>
                                            <td><?php echo $row['nama_sumber']; ?></td>
                                            <td><?php echo $row['nama_periode']; ?></td>
                                            <td>Rp <?php echo number_format($row['nominal'],0,',','.'); ?></td>
                                            <td>
                                                <?php if (!empty($row['berkas_syarat'])) { ?>
                                                    <a href="dokumen/<?php echo $row['berkas_syarat']; ?>" target="_blank" class="btn btn-sm btn-info">PDF</a>
                                                <?php } else { echo "-"; } ?>
                                            </td>

                                            <td>
                                                <a href="editbeasiswa.php?id=<?php echo $row['id_beasiswa'] ?>" class="btn btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="hapusbeasiswa.php?id=<?php echo $row['id_beasiswa'] ?>" class="btn btn-danger" onclick="return confirm('Hapus data?')" title="HAPUS">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
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