<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}

include("includes/config.php");

if (isset($_GET['id'])) {
    $id_galeri = $_GET["id"];
    
    // Ambil info file
    $query_file = mysqli_query($conn, "SELECT nama_file FROM galeri WHERE id_galeri = '$id_galeri'");
    $data_file = mysqli_fetch_array($query_file);
    
    // Hapus file fisik
    if(!empty($data_file['nama_file'])) {
        $path = "dokumen/" . $data_file['nama_file'];
        if(file_exists($path)) unlink($path);
    }

    // Hapus data
    mysqli_query($conn, "DELETE FROM galeri WHERE id_galeri = '$id_galeri'");
    
    echo "<script>alert('Foto Berhasil Dihapus');
    document.location='inputgaleri.php'</script>";
}
?>