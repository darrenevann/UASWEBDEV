<!DOCTYPE html>
<html>
<?php
include "bagiankode/head.php"
?>

<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}
?>

<body class="sb-nav-fixed">
    <?php
    include "bagiankode/menunav.php"
        ?>

    <div id="layoutSidenav">
        <?php
        include "bagiankode/menu.php"
            ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Data Peserta Skripsi</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <?php
    include("includes/config.php");

    if (isset($_POST["Simpan"])) {
        $mhs_NPM = $_POST['mhs_NPM'];
        $peserta_SEMT = $_POST['peserta_SEMT'];
        $peserta_THAKD = $_POST['peserta_THAKD'];
        $peserta_TGLDAFTAR = $_POST['peserta_TGLDAFTAR'];
        $peserta_JUDUL = $_POST['peserta_JUDUL'];

        if (isset($_FILES['peserta_DOKUMEN']) && $_FILES['peserta_DOKUMEN']['name'] != "") {
            $peserta_DOKUMEN = $_FILES['peserta_DOKUMEN']['name'];
            $tmp = $_FILES['peserta_DOKUMEN']['tmp_name'];
            move_uploaded_file($tmp, 'dokumen/' . $peserta_DOKUMEN);
        } else {
            $peserta_DOKUMEN = "";
        }
        mysqli_query($conn, "INSERT INTO peserta VALUES ('$mhs_NPM', '$peserta_SEMT', '$peserta_THAKD', '$peserta_TGLDAFTAR', '$peserta_JUDUL', '$peserta_DOKUMEN')");
    }

    $datamhs = mysqli_query($conn, "SELECT * FROM mahasiswa");
    if (isset($_POST["kirim"])) {
        $search = $_POST["search"];
        $query = mysqli_query($conn, "SELECT * FROM peserta WHERE mhs_NPM LIKE '%$search%'");
    } else {
        $query = mysqli_query($conn, "SELECT * FROM peserta");
    }
    ?>

    <div class="container mt-5">
        <h2 class="mb-4">Input Data Peserta Skripsi</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="mhs_NPM" class="col-sm-2 col-form-label">NPM Mahasiswa</label>
                <div class="col-sm-10">
                    <select class="form-control select2" id="mhs_NPM" name="mhs_NPM" required>
                        <option value="">-- Pilih NPM Mahasiswa --</option>
                        <?php while ($row = mysqli_fetch_array($datamhs)) { ?>
                            <option value="<?php echo $row['mhs_NPM']; ?>">
                                <?php echo $row['mhs_NPM']; ?> - <?php echo $row['mhs_Nama']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="peserta_SEMT" class="col-sm-2 col-form-label">Semester</label>
                <div class="col-sm-10">
                    <select class="form-control" id="peserta_SEMT" name="peserta_SEMT" required>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="peserta_THAKD" class="col-sm-2 col-form-label">Tahun Akademik</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="peserta_THAKD" name="peserta_THAKD" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="peserta_TGLDAFTAR" class="col-sm-2 col-form-label">Tanggal Daftar</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="peserta_TGLDAFTAR" name="peserta_TGLDAFTAR" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="peserta_JUDUL" class="col-sm-2 col-form-label">Judul Skripsi</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="peserta_JUDUL" name="peserta_JUDUL" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="peserta_DOKUMEN" class="col-sm-2 col-form-label">Upload Gambar</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="peserta_DOKUMEN" name="peserta_DOKUMEN"
                        accept=".jpg,.jpeg,.png ">
                    <small class="text-muted">Format gambar: JPG, JPEG, PNG</small>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <input type="submit" name="Simpan" value="Simpan" class="btn btn-success">
                    <input type="reset" name="Batal" value="Batal" class="btn btn-danger">
                </div>
            </div>
        </form>

        <hr class="mt-4 mb-4">

        <form method="POST">
            <div class="form-group row mt-5 mb-3">
                <label for="search" class="col-sm-2 col-form-label">Cari NPM Mahasiswa</label>
                <div class="col-sm-6">
                    <input type="text" name="search" class="form-control" id="search" value="<?php if (isset($_POST["search"])) {
                        echo $_POST["search"];
                    } ?>" placeholder="Masukan NPM">
                </div>
                <input type="submit" name="kirim" value="Cari" class="col-sm-1 btn btn-primary">
            </div>
        </form>

        <h3>Daftar Peserta Skripsi</h3>
        <table class="table table-bordered table-striped table-hover mt-3">
            <tr class="table-success">
                <th>NPM</th>
                <th>Semester</th>
                <th>Tahun Akademik</th>
                <th>Tanggal Daftar</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th colspan="2" style="text-align: center;">Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_array($query)) { ?>
                <tr>
                    <td><?php echo $row['mhs_NPM']; ?></td>
                    <td><?php echo $row['peserta_SEMT']; ?></td>
                    <td><?php echo $row['peserta_THAKD']; ?></td>
                    <td><?php echo $row['peserta_TGLDAFTAR']; ?></td>
                    <td><?php echo $row['peserta_JUDUL']; ?></td>
                    <td>
                        <?php if ($row['peserta_DOKUMEN'] == "") {
                            echo "<img src='images/UNTAR.jpg' width='400' height='300'/>";
                        } else { ?>
                            <img src="dokumen/<?php echo $row['peserta_DOKUMEN']; ?>" width="400" height="300"
                                class="img-responsive" />
                        <?php } ?>
                    </td>
                    <td>
                        <a href="editpeserta.php?ubahNPM=<?php echo $row["mhs_NPM"] ?>" class="btn btn-success"
                            title="EDIT">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                    </td>
                    <td>
                        <a href="hapuspeserta.php?hapuspeserta=<?php echo $row["mhs_NPM"] ?>" class="btn btn-danger"
                            title="HAPUS">
                            <i class="bi bi-trash"></i>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>

</body>
</div>
<?php include "bagiankode/footer.php"; ?>
<?php include "bagiankode/jsscript.php"; ?>

</html>