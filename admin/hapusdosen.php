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

    if (isset($_GET['hapusdosen'])) {

        $dosen_NIDN = $_GET["hapusdosen"];
        mysqli_query($conn, "DELETE FROM dosen WHERE dosen_NIDN = '$dosen_NIDN'");
        echo "<script>alert('Data Berhasil Dihapus');
        document.location='inputdosen.php'</script>";
    }
?>