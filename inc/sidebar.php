<div class="sidebar">
    <div class="brand">
        <img src="images/logo.png" alt="Logo">
        <h4>Dpmptsp Kota Kupang</h4>
        <small>Dashboard Arsip</small>
    </div>
    <a href="index.php" class="<?= ($page=='dashboard') ? 'active' : '' ?>"><i class="fa fa-home"></i> Dashboard</a>
    <a href="dokumen.php" class="<?= ($page=='dokumen') ? 'active' : '' ?>"><i class="fa fa-folder"></i> Dokumen</a>
    <a href="kategori.php" class="<?= ($page=='kategori') ? 'active' : '' ?>"><i class="fa fa-tags"></i> Kategori</a>
    <a href="file_manager.php" class="<?= ($page=='file_manager') ? 'active' : '' ?>"><i class="fa fa-file"></i> File Manager</a>
    <a href="user.php" class="<?= ($page=='user') ? 'active' : '' ?>"><i class="fa fa-users"></i> User</a>
    <div class="logout">
        <a href="logout.php" class="btn btn-danger w-100"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
