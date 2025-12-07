<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darren Evan Nathanael</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'includes/frontmenu.php'; ?>

    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/gedung-untar.jpg" class="d-block w-100" alt="..."
                    style="height: 750px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Universitas Tarumanagara Kampus 1</h5>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/gedunguntar2.jpg" class="d-block w-100" alt="..."
                    style="height: 750px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Universitas Tarumanagara Kampus 2</h5>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container">
        
        <div class="row atas mt-5">
            
            <div class="col-sm-8">
                <h3 class="mb-4">Data Peserta Skripsi Terbaru</h3>
                <?php
                $queryPeserta = mysqli_query($conn, "SELECT p.*, m.mhs_Nama 
                                                    FROM peserta p 
                                                    JOIN mahasiswa m ON p.mhs_NPM = m.mhs_NPM 
                                                    ORDER BY p.peserta_TGLDAFTAR DESC LIMIT 3");

                if (mysqli_num_rows($queryPeserta) > 0) {
                    while ($row = mysqli_fetch_assoc($queryPeserta)) {
                        $fileDokumen = $row['peserta_DOKUMEN'];
                        $pathFoto = "../admin/dokumen/" . $fileDokumen;
                        
                        if (!empty($fileDokumen) && file_exists($pathFoto)) {
                            $fotoTampil = $pathFoto;
                        } else {
                            $fotoTampil = "images/tes.jpeg";
                        }
                        ?>
                        <div class="d-flex mb-4 border-bottom pb-3">
                            <div class="flex-shrink-0">
                                <img src="<?= $fotoTampil ?>" alt="Dokumen Skripsi"
                                    style="width: 120px; height: 120px; object-fit: cover;" class="rounded">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mt-0 fw-bold"><?= $row['peserta_JUDUL'] ?></h5>

                                <p class="mb-1 text-primary">
                                    <?= $row['mhs_Nama'] ?> (<?= $row['mhs_NPM'] ?>)
                                </p>

                                <p class="mb-1 small text-muted">
                                    Semester: <?= $row['peserta_SEMT'] ?> |
                                    Th. Akad: <?= $row['peserta_THAKD'] ?> |
                                    Tgl Daftar: <?= date('d F Y', strtotime($row['peserta_TGLDAFTAR'])) ?>
                                </p>

                                <p class="card-text">
                                    Mahasiswa ini sedang melakukan penyusunan skripsi dengan judul
                                    "<?= $row['peserta_JUDUL'] ?>".
                                </p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-warning'>Belum ada data peserta skripsi yang terdaftar.</div>";
                }
                ?>
            </div>

            <div class="col-sm-4">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Jadwal Ujian Skripsi</h5>
                            <small>Terbaru</small>
                        </div>
                        <p class="mb-1">Berikut adalah jadwal ujian sidang skripsi mahasiswa.</p>
                    </a>

                    <?php
                    $queryJadwal = mysqli_query($conn, "SELECT u.tgl_ujian, u.jam_ujian, m.mhs_Nama, m.mhs_NPM, d.dosen_Nama 
                                                      FROM ujian u
                                                      JOIN mahasiswa m ON u.mhs_NPM = m.mhs_NPM
                                                      LEFT JOIN bimbingan b ON u.mhs_NPM = b.mhs_NPM
                                                      LEFT JOIN dosen d ON b.dosen_NIDN = d.dosen_NIDN
                                                      ORDER BY u.tgl_ujian DESC LIMIT 3");

                    if (mysqli_num_rows($queryJadwal) > 0) {
                        while ($row = mysqli_fetch_assoc($queryJadwal)) {
                            $tglUjian = date('d M Y', strtotime($row['tgl_ujian']));
                            $jamUjian = date('H:i', strtotime($row['jam_ujian']));
                            
                            if(!empty($row['dosen_Nama'])) {
                                $namaDosen = $row['dosen_Nama'];
                            } else {
                                $namaDosen = "Pembimbing belum ditentukan";
                            }
                            ?>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= $row['mhs_Nama'] ?></h5>
                                    <small class="text-muted"><?= $tglUjian ?></small>
                                </div>

                                <p class="mb-1">Pembimbing: <?= $namaDosen ?></p>

                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> Pukul <?= $jamUjian ?> WIB
                                </small>
                            </a>
                            <?php
                        }
                    } else {
                        echo '<a href="#" class="list-group-item list-group-item-action">Belum ada jadwal ujian.</a>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <h1 class="mt-5 mb-4" style="text-align: center">Galeri Dokumentasi Ujian Skripsi</h1>
        <div class="galerifoto row g-4 justify-content-center">
            <?php
            $queryGaleri = mysqli_query($conn, "SELECT u.foto_ujian, p.peserta_JUDUL 
                                              FROM ujian u
                                              JOIN peserta p ON u.mhs_NPM = p.mhs_NPM
                                              LIMIT 6");

            if (mysqli_num_rows($queryGaleri) > 0) {
                while ($row = mysqli_fetch_assoc($queryGaleri)) {
                    $namaFile = $row['foto_ujian'];
                    $pathFile = "../admin/dokumen/" . $namaFile;

                    if (!empty($namaFile) && file_exists($pathFile)) {
                        $srcGambar = $pathFile;
                    } else {
                        $srcGambar = "images/tes.jpeg";
                    }

                    $judul = $row['peserta_JUDUL'];
                    ?>
                    <figure class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="card h-100 shadow-sm border-0">
                            <img style="width: 100%; height: 250px; object-fit: cover;" src="<?= $srcGambar ?>"
                                class="figure-img img-fluid rounded-top mb-0" alt="Foto Ujian">

                            <figcaption class="figure-caption p-3 text-center bg-white border rounded-bottom">
                                <?= $judul ?>
                            </figcaption>
                        </div>
                    </figure>
                    <?php
                }
            } else {
                echo "<div class='col-12 text-center alert alert-info'>Belum ada dokumentasi ujian yang diunggah.</div>";
            }
            ?>
        </div>

        <div class="row tengah mt-5">
            <h3 class="mb-3 text-center border-bottom pb-2">Galeri Alumni Universitas Tarumanagara</h3>
            <?php
            $qKegiatan = mysqli_query($conn, "SELECT * FROM galeri ORDER BY tanggal DESC LIMIT 3");

            if (mysqli_num_rows($qKegiatan) > 0) {
                while ($rowG = mysqli_fetch_assoc($qKegiatan)) {
                    $fotoG = "../admin/dokumen/" . $rowG['nama_file'];
                    
                    if (!file_exists($fotoG) || empty($rowG['nama_file'])) {
                         $fotoG = "images/tes.jpeg";
                    }
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img src="<?= $fotoG ?>" class="card-img-top" alt="Kegiatan" style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $rowG['judul_foto'] ?></h5>
                                <p class="card-text small text-muted">
                                    <?= substr($rowG['deskripsi'], 0, 90) ?>...
                                </p>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-primary">
                                    <?= date('d M Y', strtotime($rowG['tanggal'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div class='col-12 text-center'>Belum ada data kegiatan.</div>";
            }
            ?>
        </div>

        <div class="row atas mt-5 mb-5">
            
            <div class="col-sm-4">
                <div class="p-3 bg-light border rounded h-100">
                    <h4 class="mb-3">Penerima Beasiswa</h4>
                    <p class="text-muted small">Selamat kepada mahasiswa berikut:</p>

                    <ul class="list-group list-group-flush bg-transparent">
                        <?php
                        $qPenerima = mysqli_query($conn, "SELECT m.mhs_Nama, s.nama_sumber, p.nama_periode, bp.nominal 
                                                        FROM beasiswa_penerima bp 
                                                        JOIN mahasiswa m ON bp.mhs_NPM = m.mhs_NPM 
                                                        JOIN beasiswa_sumber s ON bp.sumber_id = s.sumber_id
                                                        JOIN beasiswa_periode p ON bp.periode_id = p.periode_id
                                                        ORDER BY bp.id_beasiswa DESC LIMIT 5");

                        if (mysqli_num_rows($qPenerima) > 0) {
                            while ($p = mysqli_fetch_assoc($qPenerima)) {
                                $nominal = "Rp " . number_format($p['nominal'], 0, ',', '.');
                                echo "<li class='list-group-item bg-transparent ps-0 py-2 border-bottom'>";
                                echo "<div>🎓 <strong>" . $p['mhs_Nama'] . "</strong></div>";
                                echo "<div class='small text-muted'>" . $p['nama_sumber'] . " (" . $p['nama_periode'] . ")</div>";
                                echo "<div class='small text-success fw-bold'>Nominal: " . $nominal . "</div>";
                                echo "</li>";
                            }
                        } else {
                            echo "<li class='list-group-item bg-transparent ps-0'>Belum ada penerima.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="row h-100">
                    
                    <div class="col-sm-6 mb-3">
                        <div class="card h-100 border-success">
                            <div class="card-header bg-success text-white">Mitra Beasiswa</div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <?php
                                    $qBea = mysqli_query($conn, "SELECT nama_sumber FROM beasiswa_sumber LIMIT 5");
                                    while ($b = mysqli_fetch_assoc($qBea)) {
                                        echo "<li class='mb-2'>📌 " . $b['nama_sumber'] . "</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <?php
                        $npmSaya = '825240062';
                        $qProfil = mysqli_query($conn, "SELECT admin_NAME, admin_NPM, admin_FOTO FROM admin WHERE admin_NPM='$npmSaya'");
                        $data = mysqli_fetch_assoc($qProfil);

                        if(!empty($data['admin_FOTO'])) {
                            $fotoProfil = "../admin/dokumen/" . $data['admin_FOTO'];
                        } else {
                            $fotoProfil = "images/tes.jpeg";
                        }
                        ?>
                        <div class="card h-100 border-primary">
                            <div class="card-header bg-primary text-white text-center">Profil Pengembang</div>
                            <div class="card-body text-center">
                                <img src="<?= $fotoProfil ?>" class="rounded-circle mb-3 border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <h5 class="fw-bold mb-0"><?= $data['admin_NAME'] ?></h5>
                                <p class="text-primary fw-bold"><?= $data['admin_NPM'] ?></p>
                                <p class="fw-bold mb-0">Sistem Informasi Kelas B</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
    </div>
</body>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

</html>