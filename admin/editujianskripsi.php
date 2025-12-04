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
                    <h1 class="mt-4">Edit Jadwal Ujian</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Ujian Skripsi</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include "includes/config.php";

                        // --- AMBIL DATA LAMA ---
                        if (isset($_GET['id'])) {
                            $id_ujian = $_GET['id'];
                            $query_edit = mysqli_query($conn, "SELECT * FROM ujian WHERE id_ujian = '$id_ujian'");
                            $row_edit = mysqli_fetch_array($query_edit);
                        }

                        // --- PROSES UPDATE ---
                        if (isset($_POST["Ubah"])) {
                            $id_lama = $_POST['idUjian'];
                            $mhs_NPM = $_POST['mhsNPM'];
                            $tgl_ujian = $_POST['tglUjian'];
                            $jam_ujian = $_POST['jamUjian'];
                            
                            $foto_lama = $_POST['fotoLama'];
                            
                            // Cek File Baru
                            $namaFileBaru = $_FILES['fotoUjian']['name'];
                            $lokasiFile = $_FILES['fotoUjian']['tmp_name'];

                            if (!empty($namaFileBaru)) {
                                // JIKA GANTI FOTO
                                $folderTujuan = "dokumen/" . $namaFileBaru;
                                $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                                // Validasi JPG
                                if ($fileType != "jpg") {
                                    echo "<script>alert('Gagal! Format file harus JPG.');</script>";
                                } else {
                                    // Hapus foto lama fisik jika ada
                                    if (!empty($foto_lama) && file_exists("dokumen/" . $foto_lama)) {
                                        unlink("dokumen/" . $foto_lama);
                                    }

                                    move_uploaded_file($lokasiFile, $folderTujuan);

                                    // Update DB dengan foto baru
                                    mysqli_query($conn, "UPDATE ujian SET 
                                        mhs_NPM = '$mhs_NPM',
                                        tgl_ujian = '$tgl_ujian',
                                        jam_ujian = '$jam_ujian',
                                        foto_ujian = '$namaFileBaru'
                                        WHERE id_ujian = '$id_lama'");

                                    echo "<script>alert('Data berhasil diubah'); document.location='inputujianskripsi.php'</script>";
                                }
                            } else {
                                // JIKA TIDAK GANTI FOTO
                                mysqli_query($conn, "UPDATE ujian SET 
                                    mhs_NPM = '$mhs_NPM',
                                    tgl_ujian = '$tgl_ujian',
                                    jam_ujian = '$jam_ujian'
                                    WHERE id_ujian = '$id_lama'");
                                
                                echo "<script>alert('Data berhasil diubah'); document.location='inputujianskripsi.php'</script>";
                            }
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="jumbotron mt-5 mb-3">
                                    <h3>Edit Data Ujian</h3>
                                </div>

                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idUjian" value="<?php echo $row_edit['id_ujian']; ?>">
                                    <input type="hidden" name="fotoLama" value="<?php echo $row_edit['foto_ujian']; ?>">

                                    <div class="row mb-3">
                                        <label for="mhsNPM" class="col-sm-2 col-form-label">Mahasiswa</label>
                                        <div class="col-sm-10">
                                            <select class="form-control select2" name="mhsNPM" required>
                                                <option value="">-- Pilih Mahasiswa --</option>
                                                <?php
                                                // Tampilkan mahasiswa yang ada di tabel BIMBINGAN
                                                $sql_mhs = mysqli_query($conn, "SELECT DISTINCT b.mhs_NPM, m.mhs_Nama 
                                                                                FROM bimbingan b 
                                                                                JOIN mahasiswa m ON b.mhs_NPM = m.mhs_NPM");
                                                while ($row_mhs = mysqli_fetch_array($sql_mhs)) {
                                                    // Logic Selected
                                                    $selected = ($row_mhs['mhs_NPM'] == $row_edit['mhs_NPM']) ? "selected" : "";
                                                    
                                                    echo "<option value='" . $row_mhs['mhs_NPM'] . "' $selected>" . $row_mhs['mhs_Nama'] . " (" . $row_mhs['mhs_NPM'] . ")</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tglUjian" class="col-sm-2 col-form-label">Tanggal Ujian</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="tglUjian" value="<?php echo $row_edit['tgl_ujian']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="jamUjian" class="col-sm-2 col-form-label">Waktu (Jam)</label>
                                        <div class="col-sm-10">
                                            <input type="time" class="form-control" name="jamUjian" value="<?php echo $row_edit['jam_ujian']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="fotoUjian" class="col-sm-2 col-form-label">Foto Ujian (JPG)</label>
                                        <div class="col-sm-10">
                                            <p class="mb-1 text-muted">
                                                File saat ini: 
                                                <?php 
                                                    if(!empty($row_edit['foto_ujian'])) {
                                                        echo $row_edit['foto_ujian']; 
                                                    } else {
                                                        echo "Tidak ada foto";
                                                    }
                                                ?>
                                            </p>
                                            <input type="file" class="form-control" name="fotoUjian" accept=".jpg">
                                            <small class="text-danger">*Biarkan kosong jika tidak ingin mengganti foto.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <a href="inputujianskripsi.php" class="btn btn-danger">Batal</a>
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