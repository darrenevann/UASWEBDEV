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
        $id_ujian = $_GET["id"];
        
        $query_file = mysqli_query($conn, "SELECT foto_ujian FROM ujian WHERE id_ujian = '$id_ujian'");
        $data_file = mysqli_fetch_array($query_file);
        
        if(!empty($data_file['foto_ujian'])) {
            $file_path = "dokumen/" . $data_file['foto_ujian'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        mysqli_query($conn, "DELETE FROM ujian WHERE id_ujian = '$id_ujian'");
        
        echo "<script>alert('Data Berhasil Dihapus');
        document.location='inputujianskripsi.php'</script>";
    }
?>