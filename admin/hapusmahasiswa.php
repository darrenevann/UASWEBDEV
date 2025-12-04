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

    if (isset($_GET['hapusmahasiswa'])) {

        $NPM = $_GET["hapusmahasiswa"];
        mysqli_query($conn, "DELETE FROM mahasiswa WHERE mhs_NPM = '$NPM'");
        echo "<script>alert('Data Berhasil Dihapus');
        document.location='inputmhs.php'</script>";
    }
?>