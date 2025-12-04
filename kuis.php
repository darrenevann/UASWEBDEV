<!-- NO. 1 -->
<?php
$teks = "Latihan 1";
$bilang1 = 35; //tambah ;
$bilang2 = $bilang1 + 30;
echo $teks;
echo "<br>Jumlah Bilangan = $bilang2";
?>

<!-- NO. 2 -->
<?php
echo "<br><br>";
function FA(){
    $F = "Test1";
    $A = "Test2";
    //$FA = $F + $A; 
    echo $F,"<br>";
    echo $A,"<br>";
    echo "Gabungan F dan A = $F + $A<br>";
    //echo "Hasil penjumlahan F dan A = $FA<br>"; 
}
FA();
$F = 10;
$A = 25;
$FA = 0;
echo "Hasil penjumlahan F dan A = $FA<br>";
$FA = $F + $A;
echo "Hasil penjumlahan F dan A = $FA";
?>

<!-- NO. 3 -->
<?php
echo "<br><br>";
$nilai = 100;
if ($nilai > 79){
    echo "Nilaimu adalah $nilai (A)";
} else if ($nilai > 69){
    echo "Nilaimu adalah $nilai (B)";
} else if ($nilai > 55){
    echo "Nilaimu adalah $nilai (C)";
} else{
    echo "Nilaimu adalah $nilai (D)";
}
?>