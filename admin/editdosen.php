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
                    <h1 class="mt-4">Edit Dosen</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Dosen</li>
                    </ol>

                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title>Edit Data Dosen</title>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>
                        <?php
                        include("includes/config.php");

                        $NIDN = $_GET["ubahdosen"];
                        $edit = mysqli_query($conn, "SELECT * FROM dosen WHERE dosen_NIDN= '$NIDN'");
                        $row_edit = mysqli_fetch_array($edit);

                        if (isset($_POST['Ubah'])) {
                            $dosen_NIDN = $_POST['dosenNIDN'];
                            $dosen_NIK = $_POST['dosenNIK'];
                            $dosen_Nama = $_POST['dosenNAMA'];
                            $dosen_Ket = $_POST['dosenKET'];

                            mysqli_query($conn, "UPDATE dosen 
                                SET dosen_NIDN='$dosen_NIDN', 
                                    dosen_NIK='$dosen_NIK', 
                                    dosen_Nama='$dosen_Nama',
                                    dosen_Ket='$dosen_Ket'
                                WHERE dosen_NIDN='$NIDN'");
                            
                            // Redirect kembali ke inputdosen
                            header("location:inputdosen.php");
                        }

                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            $query = mysqli_query($conn, "SELECT * FROM dosen 
                                WHERE dosen_NIDN LIKE '%$search%' OR dosen_Nama LIKE '%$search%'");
                        } else {
                            $query = mysqli_query($conn, "SELECT * FROM dosen");
                        }
                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">

                                <form method="POST">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label for="dosenNIDN" class="col-sm-2 col-form-label">NIDN Dosen</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="dosenNIDN" name="dosenNIDN"
                                                    value="<?php echo $row_edit["dosen_NIDN"] ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="dosenNIK" class="col-sm-2 col-form-label">NIK Dosen</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="dosenNIK" name="dosenNIK"
                                                    value="<?php echo $row_edit["dosen_NIK"] ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="dosenNAMA" class="col-sm-2 col-form-label">Nama Dosen</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="dosenNAMA" name="dosenNAMA"
                                                    value="<?php echo $row_edit["dosen_Nama"] ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="dosenKET" class="col-sm-2 col-form-label">Keterangan</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="dosenKET" name="dosenKET"
                                                    value="<?php echo $row_edit["dosen_Ket"] ?>">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <a href="inputdosen.php" class="btn btn-danger">Batal</a>
                                        </div>
                                    </div>
                                </form>

                                <div class="jumbotron mt-5 mb-3">
                                    <h1 class="display-5">Daftar Dosen</h1>
                                </div>

                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label for="search" class="col-sm-2">Cari NIDN / Nama Dosen</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" id="search"
                                                value="<?php if (isset($_POST['search'])) echo $_POST['search']; ?>"
                                                placeholder="Masukkan NIDN / Nama Dosen">
                                        </div>
                                        <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                    </div>
                                </form>

                                <table class="table table-success table-striped">
                                    <tr class="info">
                                        <th>NIDN</th>
                                        <th>NIK</th>
                                        <th>Nama Dosen</th>
                                        <th>Keterangan</th>
                                        <th colspan="2" style="text-align: center;">Aksi</th>
                                    </tr>

                                    <?php while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr class="danger">
                                            <td><?php echo $row['dosen_NIDN']; ?></td>
                                            <td><?php echo $row['dosen_NIK']; ?></td>
                                            <td><?php echo $row['dosen_Nama']; ?></td>
                                            <td><?php echo $row['dosen_Ket']; ?></td>

                                            <td>
                                                <a href="editdosen.php?ubahdosen=<?php echo $row["dosen_NIDN"] ?>" class="btn btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="hapusdosen.php?hapusdosen=<?php echo $row["dosen_NIDN"] ?>" class="btn btn-danger" title="HAPUS" onclick="return confirm('Hapus data?')">
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