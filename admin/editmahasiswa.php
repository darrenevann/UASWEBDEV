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
                    <h1 class="mt-4">Edit Mahasiswa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard / Mahasiswa</li>
                    </ol>

                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <title></title>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>

                        <?php
                        include("includes/config.php");

                        $NPM = $_GET["ubahmhs"];
                        $edit = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE mhs_NPM= '$NPM'");
                        $row_edit = mysqli_fetch_array($edit);

                        if (isset($_POST['Ubah'])) {

                            $mhs_NPM = $_POST['npmMHS'];
                            $mhs_Nama = $_POST['namaMHS'];
                            $mhs_Alamat = $_POST['alamatMHS'];
                            $mhs_DOB = $_POST['dobMHS'];
                            $mhs_KET = $_POST['ketMHS'];

                            mysqli_query($conn, "UPDATE mahasiswa SET 
                                mhs_NPM='$mhs_NPM',
                                mhs_Nama='$mhs_Nama',
                                mhs_Alamat='$mhs_Alamat',
                                mhs_DOB='$mhs_DOB',
                                mhs_KET='$mhs_KET'
                                WHERE mhs_NPM='$NPM'");
                            
                            // Redirect kembali ke halaman input/list mahasiswa
                            header("location:inputmhs.php");
                        }

                        if (isset($_POST["kirim"])) {
                            $search = $_POST["search"];
                            $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE mhs_NPM LIKE '%$search%'");
                        } else {
                            $query = mysqli_query($conn, "SELECT * FROM mahasiswa");
                        }

                        ?>

                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mb-3 mt-5">

                                        <div class="row mb-3">
                                            <label for="npmMHS" class="col-sm-2 col-form-label">NPM Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="npmMHS" name="npmMHS"
                                                    value="<?php echo $row_edit['mhs_NPM']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="namaMHS" class="col-sm-2 col-form-label">Nama Mahasiswa</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="namaMHS" name="namaMHS"
                                                    value="<?php echo $row_edit['mhs_Nama']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="alamatMHS" class="col-sm-2 col-form-label">Alamat</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="alamatMHS" name="alamatMHS"
                                                    value="<?php echo $row_edit['mhs_Alamat']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="dobMHS" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" id="dobMHS" name="dobMHS"
                                                    value="<?php echo $row_edit['mhs_DOB']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="ketMHS" class="col-sm-2 col-form-label">Keterangan</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="ketMHS" name="ketMHS"
                                                    value="<?php echo $row_edit['mhs_Ket']; ?>">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <div class="col-2"></div>
                                        <div class="col-10">
                                            <input type="submit" class="btn btn-success" value="Ubah" name="Ubah">
                                            <input type="reset" class="btn btn-danger" value="Batal" name="Batal">
                                        </div>
                                    </div>
                                </form>

                                <div class="row">
                                    <div class="col-1"></div>
                                    <div class="col-10">

                                        <div class="jumbotron mt-5 mb-3">
                                            <h1 class="display-5">Daftar Mahasiswa</h1>
                                        </div>

                                        <form method="POST">
                                            <div class="form-group row mt-5 mb-3">
                                                <label for="search" class="col-sm-2">Cari NPM Mahasiswa</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="search" class="form-control" id="search"
                                                        value="<?php if (isset($_POST['search'])) echo $_POST['search']; ?>"
                                                        placeholder="Masukkan NPM Mahasiswa">
                                                </div>
                                                <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
                                            </div>
                                        </form>

                                        <table class="table table-success table-striped">
                                            <tr class="info">
                                                <th>NPM</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Keterangan</th>
                                                <th colspan="2" style="text-align: center;">Aksi</th>
                                            </tr>

                                            <?php while ($row = mysqli_fetch_array($query)) { ?>
                                                <tr class="danger">
                                                    <td><?php echo $row['mhs_NPM']; ?></td>
                                                    <td><?php echo $row['mhs_Nama']; ?></td>
                                                    <td><?php echo $row['mhs_Alamat']; ?></td>
                                                    <td><?php echo $row['mhs_DOB']; ?></td>
                                                    <td><?php echo $row['mhs_Ket']; ?></td>

                                                    <td>
                                                        <a href="editmahasiswa.php?ubahmhs=<?php echo $row["mhs_NPM"] ?>" class="btn btn-success">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    </td>

                                                    <td>
                                                        <a href="hapusmhs.php?hapusmhs=<?php echo $row["mhs_NPM"] ?>" class="btn btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                        </table>

                                        <div class="1"></div>

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