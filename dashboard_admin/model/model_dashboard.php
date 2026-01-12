<?php
// Ambil data statistik menggunakan function
$resultAsisten = pg_query($conn, "SELECT fn_total_asisten() AS total_asisten");
$resultMagang = pg_query($conn, "SELECT fn_total_magang() AS total_magang");
$resultGallery = pg_query($conn, "SELECT fn_total_gallery() AS total_gallery");
$resultMemberDosen = pg_query($conn, "SELECT fn_total_member_dosen() AS total_member_dosen");
$resultMemberMahasiswa = pg_query($conn, "SELECT fn_total_member_mahasiswa() AS total_member_mahasiswa");
$resultPartners = pg_query($conn, "SELECT fn_total_partners() AS total_partners");
$resultPeminjaman = pg_query($conn, "SELECT fn_total_peminjaman() AS total_peminjaman");
$resultProducts = pg_query($conn, "SELECT fn_total_products() AS total_products");

$totalAsisten = pg_fetch_assoc($resultAsisten);
$totalMagang = pg_fetch_assoc($resultMagang);
$totalGallery = pg_fetch_assoc($resultGallery);
$totalMemberDosen = pg_fetch_assoc($resultMemberDosen);
$totalMemberMahasiswa = pg_fetch_assoc($resultMemberMahasiswa);
$totalPartners = pg_fetch_assoc($resultPartners);
$totalPeminjaman = pg_fetch_assoc($resultPeminjaman);
$totalProducts = pg_fetch_assoc($resultProducts);

// Ringkasan sistem - data pending
$pendingMagang = pg_fetch_assoc(pg_query($conn, "SELECT fn_pending_magang() AS pending_magang"));
$pendingAsisten = pg_fetch_assoc(pg_query($conn, "SELECT fn_pending_asisten() AS pending_asisten"));
$pendingPeminjaman = pg_fetch_assoc(pg_query($conn, "SELECT fn_pending_peminjaman() AS pending_peminjaman"));
?>