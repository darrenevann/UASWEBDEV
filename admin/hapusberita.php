<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}

include("includes/config.php");

if (isset($_GET['id'])) {
    $beritaID = $_GET["id"];
    

    $query_file = mysqli_query($conn, "SELECT beritaFoto FROM berita WHERE beritaID = '$beritaID'");
    $data_file = mysqli_fetch_array($query_file);
    
    if(!empty($data_file['beritaFoto'])) {
        $file_path = "dokumen/" . $data_file['beritaFoto'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    mysqli_query($conn, "DELETE FROM berita WHERE beritaID = '$beritaID'");
    
    echo "<script>alert('Data Berita Berhasil Dihapus');
    document.location='inputberita.php'</script>";
}
?>