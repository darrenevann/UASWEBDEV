<html>

<body>

    Selamat Berjumpa :
    <?php
    if (isset($_REQUEST["name"])) {
        $nama = $_REQUEST["name"];
    }
    if (!empty($nama)) {
        echo $nama;
    }
    ?> <br>

    Email anda adalah :
    <?php
    if (isset($_REQUEST["email"])) {
        echo $_REQUEST["email"];
    }
    ?>

</body>

</html>