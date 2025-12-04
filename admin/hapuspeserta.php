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

    if (isset($_GET['hapuspeserta'])) {

        $NPM = $_GET["hapuspeserta"];
        mysqli_query($conn, "DELETE FROM peserta WHERE mhs_NPM = '$NPM'");
        echo "<script>alert('Data Berhasil Dihapus');
        document.location='inputpeserta.php'</script>";
    }
?>