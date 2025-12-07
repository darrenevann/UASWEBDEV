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
                    <h1 class="mt-4">Edit Penasihat Akademik</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Penasihat Akademik</li>
                    </ol>

                    <head>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <?php
                    include("includes/config.php");

        
                    $NPM = $_GET["ubahpenasihat"];
                    $edit = mysqli_query($conn, "SELECT * FROM penasihat WHERE mhs_NPM= '$NPM'");
                    $row_edit = mysqli_fetch_array($edit);

                    $editmhs = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE mhs_NPM='$NPM'");
                    $row_edit2 = mysqli_fetch_array($editmhs);
                    if (isset($_POST['Ubah'])) {
                        $mhs_NPM = $_POST['npmMHS'];
                        $dosen_NIDN = $_POST['nidnDOSEN'];
                        $penasihat_KET = $_POST['penasihatKET'];
                        
                        $penasihat_FILE = $_FILES['penasihatFILE']['name'];
                        $dokumen_tmp = $_FILES['penasihatFILE']['tmp_name'];

                        if (!empty($penasihat_FILE)) {
        
                            move_uploaded_file($dokumen_tmp, 'images/' . $penasihat_FILE);
                            mysqli_query($conn, "UPDATE penasihat SET 
                                mhs_NPM='$mhs_NPM', 
                                dosen_NIDN='$dosen_NIDN', 
                                penasihat_FILE='$penasihat_FILE', 
                                penasihat_KET='$penasihat_KET' 
                                WHERE mhs_NPM='$NPM'");
                        } else {
        
                            mysqli_query($conn, "UPDATE penasihat SET 
                                mhs_NPM='$mhs_NPM', 
                                dosen_NIDN='$dosen_NIDN', 
                                penasihat_KET='$penasihat_KET' 
                                WHERE mhs_NPM='$NPM'");
                        }
                        
                        header("location:inputpenasihat.php");
                    }

    
                    if (isset($_POST["kirim"])) {
                        $search = $_POST["search"];
                        $query = mysqli_query($conn, "SELECT * FROM penasihat,dosen,mahasiswa
                            WHERE mahasiswa.mhs_NPM = penasihat.mhs_NPM AND penasihat.dosen_NIDN = 
                            dosen.dosen_NIDN AND penasihat.mhs_NPM LIKE '%$search%'");
                    } else {
                        $query = mysqli_query($conn, "SELECT * FROM penasihat,dosen,mahasiswa
                            WHERE mahasiswa.mhs_NPM = penasihat.mhs_NPM AND penasihat.dosen_NIDN = dosen.dosen_NIDN");
                    }
                    $datamhs = mysqli_query($conn, "select * from mahasiswa");
                    ?>

                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10">
                            
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row mb-3 mt-5">
                                    <div class="row mb-3">
                                        <label for="npmMHS" class="col-sm-2 col-form-label">NPM Mahasiswa</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="npmMHS" name="npmMHS">
                                                <option value="<?php echo $row_edit['mhs_NPM']; ?>">
                                                    <?php echo $row_edit['mhs_NPM']; ?> - 
                                                    <?php echo isset($row_edit2["mhs_Nama"]) ? $row_edit2["mhs_Nama"] : ''; ?>
                                                </option>
                                                <?php while ($row = mysqli_fetch_array($datamhs)) { ?>
                                                    <option value="<?php echo $row["mhs_NPM"] ?>">
                                                        <?php echo $row["mhs_NPM"] ?> - 
                                                        <?php echo $row["mhs_Nama"] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <label for="nidnDOSEN" class="col-sm-2 col-form-label">NIDN Dosen</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nidnDOSEN" name="nidnDOSEN"
                                                placeholder="NIDN Dosen" value="<?php echo $row_edit["dosen_NIDN"] ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <label for="penasihatFILE" class="col-sm-2 col-form-label">Unggah Dokumen</label>
                                        <div class="col-sm-10">
                                            <p class="mb-1">File saat ini: <?php echo $row_edit["penasihat_FILE"] ?></p>
                                            <input type="file" class="form-control" id="penasihatFILE" name="penasihatFILE">
                                            <p class="help-block text-danger small">*Biarkan kosong jika tidak ingin mengubah file</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <label for="penasihatKET" class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="penasihatKET" name="penasihatKET"
                                                placeholder="Keterangan" value="<?php echo $row_edit["penasihat_KET"] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-2"></div>
                                    <div class="col-10">
                                        <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                        <a href="inputpenasihat.php" class="btn btn-danger">Batal</a>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-12">
                                    <div class="jumbotron mt-5 mb-3">
                                        <h1 class="display-5">Daftar Penasihat Akademik</h1>
                                    </div>

                                    <form method="POST">
                                        <div class="form-group row mt-5 mb-3">
                                            <label for="search" class="col-sm-2">Cari NPM</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="search" class="form-control" id="search" 
                                                    value="<?php if (isset($_POST["search"])) { echo $_POST["search"]; } ?>" 
                                                    placeholder="Cari Mahasiswa">
                                            </div>
                                            <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                        </div>
                                    </form>

                                    <table class="table table-success table-striped">
                                        <tr class="info">
                                            <th>NPM Mahasiswa</th>
                                            <th>NIDN</th>
                                            <th>Nama Dosen</th>
                                            <th>Dokumen</th>
                                            <th>Keterangan</th>
                                            <th colspan="2" style="text-align: center;">Aksi</th>
                                        </tr>

                                        <?php while ($row = mysqli_fetch_array($query)) { ?>
                                            <tr class="danger">
                                                <td><?php echo $row['mhs_NPM']; ?></td>
                                                <td><?php echo $row['dosen_NIDN']; ?></td>
                                                <td><?php echo $row['dosen_Nama']; ?></td>
                                                <td>
                                                    <?php if ($row['penasihat_FILE'] == "") {
                                                        echo "<img src='images/noimage.png' width='88'/>";
                                                    } else { ?>
                                                        <img src="images/<?php echo $row['penasihat_FILE']; ?>" width="88" class="img-responsive" />
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $row['penasihat_KET']; ?></td>

                                                <td>
                                                    <a href="editpenasihat.php?ubahpenasihat=<?php echo $row["mhs_NPM"] ?>" class="btn btn-success" title="EDIT">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="hapuspenasihat.php?hapuspenasihat=<?php echo $row["mhs_NPM"] ?>" class="btn btn-danger" title="HAPUS" onclick="return confirm('Hapus data?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
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