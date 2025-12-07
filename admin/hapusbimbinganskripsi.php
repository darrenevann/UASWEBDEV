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

    if (isset($_GET['id'])) {
        $id_bimbingan = $_GET["id"];
        
        $query_file = mysqli_query($conn, "SELECT file_bimbingan FROM bimbingan WHERE id_bimbingan = '$id_bimbingan'");
        $data_file = mysqli_fetch_array($query_file);
        
        if(!empty($data_file['file_bimbingan'])) {
            $file_path = "dokumen/" . $data_file['file_bimbingan'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        mysqli_query($conn, "DELETE FROM bimbingan WHERE id_bimbingan = '$id_bimbingan'");
        
        echo "<script>alert('Data Berhasil Dihapus');
        document.location='inputbimbinganskripsi.php'</script>";
    }
?>