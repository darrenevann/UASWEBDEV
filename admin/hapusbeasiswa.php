<?php
ob_start();
session_start();
if (!isset($_SESSION['useremail'])) {
    header("location:login.php");
    exit();
}

include("includes/config.php");

if (isset($_GET['id'])) {
    $id_beasiswa = $_GET["id"];
    
    $query_file = mysqli_query($conn, "SELECT berkas_syarat FROM beasiswa_penerima WHERE id_beasiswa = '$id_beasiswa'");
    $data_file = mysqli_fetch_array($query_file);
    
    if(!empty($data_file['berkas_syarat'])) {
        $file_path = "dokumen/" . $data_file['berkas_syarat'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    mysqli_query($conn, "DELETE FROM beasiswa_penerima WHERE id_beasiswa = '$id_beasiswa'");
    
    echo "<script>alert('Data Berhasil Dihapus');
    document.location='inputbeasiswa.php'</script>";
}
?>