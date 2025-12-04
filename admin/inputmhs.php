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
                        <title>Input Mahasiswa</title>
                        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
                        <link rel="stylesheet"
                            href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
                        <link rel="stylesheet" type="text/css"
                            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
                    </head>

                    <body>

                        <?php
                        include("includes/config.php");


                        if (isset($_POST["Simpan"])) {


                            if (isset($_REQUEST['mhs_NPM'])) {
                                $mhs_NPM = $_REQUEST['mhs_NPM'];
                            }
                            if (!empty($mhs_NPM)) {
                                $mhs_NPM = $_POST['mhs_NPM'];
                            } else {
                                ?>
                                <h1>Maaf Anda Salah Input</h1>
                                <?php
                                die("Anda Harus Mengisi NPM Mahasiswa");
                            }


                            $mhs_NPM = $_POST['mhs_NPM'];
                            $mhs_Nama = $_POST['mhs_Nama'];
                            $mhs_Alamat = $_POST['mhs_Alamat'];
                            $mhs_DOB = $_POST['mhs_DOB'];
                            $mhs_Ket = $_POST['mhs_Ket'];


                            mysqli_query($conn, "INSERT INTO mahasiswa VALUES ('$mhs_NPM', '$mhs_Nama', '$mhs_Alamat', '$mhs_DOB', '$mhs_Ket')");
                            header("Location: inputmhs.php");
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
                                <form method="POST">
                                    <div class="row mb-3 mt-5">
                                        <div class="row mb-3">
                                            <label for="mhs_NPM" class="col-sm-2 col-form-label">NPM</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="mhs_NPM" name="mhs_NPM"
                                                    placeholder="Masukkan NPM Mahasiswa">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="mhs_Nama" class="col-sm-2 col-form-label">Nama</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="mhs_Nama" name="mhs_Nama"
                                                    placeholder="Nama Mahasiswa">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="mhs_Alamat" class="col-sm-2 col-form-label">Alamat</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="mhs_Alamat"
                                                    name="mhs_Alamat" placeholder="Alamat Mahasiswa">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="mhs_DOB" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" id="mhs_DOB" name="mhs_DOB">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="mhs_Ket" class="col-sm-2 col-form-label">Keterangan</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="mhs_Ket" name="mhs_Ket"
                                                    placeholder="Keterangan">
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
                                    <h1 class="display-5">Daftar Mahasiswa</h1>
                                </div>


                                <form method="POST">
                                    <div class="form-group row mt-5 mb-3">
                                        <label for="search" class="col-sm-2">Cari NPM</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="search" class="form-control" id="search"
                                                value="<?php if (isset($_POST['search']))
                                                    echo $_POST['search']; ?>"
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
                                        <th colspan="2" style="text-align:center;">Aksi</th>
                                    </tr>


                                    <?php while ($row = mysqli_fetch_array($query)) { ?>
                                        <tr class="danger">
                                            <td><?php echo $row['mhs_NPM']; ?></td>
                                            <td><?php echo $row['mhs_Nama']; ?></td>
                                            <td><?php echo $row['mhs_Alamat']; ?></td>
                                            <td><?php echo $row['mhs_DOB']; ?></td>
                                            <td><?php echo $row['mhs_Ket']; ?></td>


                                            <td>
                                                <a href="editmahasiswa.php?ubahmhs=<?php echo $row['mhs_NPM']; ?>"
                                                    class="btn btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="hapusmahasiswa.php?hapusmahasiswa=<?php echo $row['mhs_NPM']; ?>"
                                                    class="btn btn-danger" title="HAPUS">
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