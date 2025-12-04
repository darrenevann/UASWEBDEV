<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}
?>

<?php
include("includes/config.php");

if (isset($_GET['hapuspenasihat'])) {

    $NPM = $_GET["hapuspenasihat"];
    mysqli_query($conn, "DELETE FROM penasihat WHERE mhs_NPM = '$NPM'");
    echo "<script>alert('Data Berhasil Dihapus');
        document.location='inputpenasihat.php'</script>";

    $mhs_NPM = $_POST['npmMHS'];
    $dosen_NIDN = $_POST['nidnDOSEN'];
    $penasihat_FILE = $_FILES['penasihatFILE']['name'];
    $dokumen_tmp = $_FILES['penasihatFILE']['tmp_name'];
    move_uploaded_file($dokumen_tmp, 'images/' . $penasihat_FILE);
    $penasihat_KET = $_POST['penasihatKET'];


    mysqli_query($conn, "insert into penasihat values ('$mhs_NPM', '$dosen_NIDN', '$penasihat_FILE', '$penasihat_KET') ");
    header("Location: inputpenasihat.php");
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