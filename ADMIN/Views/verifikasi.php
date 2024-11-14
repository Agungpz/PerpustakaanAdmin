<?php
require_once '../core/Database.php';
require_once '../Models/peminjaman.php';
require_once '../Controller/peminjamancontroller.php';

$peminjamanController = new PeminjamanController();

// Ambil ID dan aksi dari URL
$id = $_GET['id'];
$aksi = $_GET['aksi'];

// Ambil data peminjaman berdasarkan ID
$peminjaman = $peminjamanController->getPeminjamanById($id);

// Ubah status berdasarkan aksi yang diterima
if ($aksi === 'verif_peminjaman') {
    $peminjamanController->updateStatus($id, 'sedang dipinjam');
} elseif ($aksi === 'verif_pengembalian') {
    $tanggal_kembalian = strtotime($peminjaman['tanggal_kembalian']);
    $tanggal_sekarang = strtotime(date('Y-m-d'));

    if ($tanggal_sekarang > $tanggal_kembalian) {
        $peminjamanController->updateStatus($id, 'Telat Mengembalikan');
    } else {
        $peminjamanController->updateStatus($id, 'sudah dikembalikan');
    }
}

// Redirect kembali ke halaman utama
header('Location: viewpeminjaman.php');
exit;
