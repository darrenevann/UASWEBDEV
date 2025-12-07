<!DOCTYPE html>
<html lang="id">
<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}
include "bagiankode/head.php";
?>

<body class="sb-nav-fixed">
    <?php include "bagiankode/menunav.php"; ?>

    <div id="layoutSidenav">
        <?php include "bagiankode/menu.php"; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Pengaturan Profil</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Settings</li>
                    </ol>

                    <?php
                    include("includes/config.php");
                    $userID = $_SESSION['useremail'];

                    if (isset($_POST['Simpan'])) {
                        $nama = $_POST['adminNama'];
                        $npm = $_POST['adminNPM'];

                        $namaFile = $_FILES['adminFoto']['name'];
                        $lokasiFile = $_FILES['adminFoto']['tmp_name'];

                        if (!empty($namaFile)) {
                            if (!file_exists('dokumen')) {
                                mkdir('dokumen', 0777, true);
                            }

                            $folderTujuan = "dokumen/" . $namaFile;
                            $fileType = strtolower(pathinfo($folderTujuan, PATHINFO_EXTENSION));

                            if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png") {
                                echo "<script>alert('Format foto harus JPG atau PNG!');</script>";
                            } else {
                                move_uploaded_file($lokasiFile, $folderTujuan);

                                $queryUpdate = mysqli_query($conn, "UPDATE admin SET 
                                    admin_NAME = '$nama',
                                    admin_NPM = '$npm',
                                    admin_FOTO = '$namaFile'
                                    WHERE admin_USER = '$userID'");
                            }
                        } else {
                            $queryUpdate = mysqli_query($conn, "UPDATE admin SET 
                                admin_NAME = '$nama',
                                admin_NPM = '$npm'
                                WHERE admin_USER = '$userID'");
                        }

                        if ($queryUpdate) {
                        } else {
                            echo "<script>alert('Gagal update data!');</script>";
                        }
                    }

                    $query = mysqli_query($conn, "SELECT * FROM admin WHERE admin_USER = '$userID'");
                    $data = mysqli_fetch_array($query);
                    ?>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-user-edit me-1"></i>
                            Edit Data Diri Anda
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Email (User)</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control"
                                            value="<?php echo $data['admin_USER']; ?>" readonly
                                            style="background-color: #e9ecef;">
                                        <small class="text-muted">Username tidak dapat diubah.</small>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">NPM</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="adminNPM"
                                            value="<?php echo isset($data['admin_NPM']) ? $data['admin_NPM'] : ''; ?>"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="adminNama"
                                            value="<?php echo isset($data['admin_NAME']) ? $data['admin_NAME'] : ''; ?>"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Foto Profil</label>
                                    <div class="col-sm-6">
                                        <?php if (!empty($data['admin_FOTO'])) { ?>
                                            <img src="dokumen/<?php echo $data['admin_FOTO']; ?>" width="100px"
                                                class="mb-2 img-thumbnail">
                                            <br>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="adminFoto"
                                            accept=".jpg,.jpeg,.png">
                                        <small class="text-danger">*Biarkan kosong jika tidak ingin ganti foto.</small>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-6">
                                        <input type="submit" class="btn btn-primary" value="Simpan Perubahan"
                                            name="Simpan">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </main>
            <?php include "bagiankode/footer.php"; ?>
        </div>
    </div>
    <?php include "bagiankode/jsscript.php"; ?>
</body>

</html>