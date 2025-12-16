<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Utama Fine Boga Style</title>
    @vite(['resources/css/index.css'])
</head>
<body>
    <header>
        <nav>
            <div class="logo">PLER JAVIER</div>
            <ul class="nav-links">
                <li><a href="index.html">Beranda</a></li>
                <li><a href="resep.html">Resep Dapur</a></li>
                </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Kualitas Bumbu Terbaik</h1>
            <p>Rasa otentik untuk masakan Anda. Bergabunglah dengan ribuan pelanggan kami.</p>
            <div id="hero-buttons" style="margin-top: 20px;">
                <a href="#products" class="btn">Lihat Produk</a>
            </div>
        </div>
    </section>

    <section id="products">
        <div class="section-title">
            <h2>Katalog Produk</h2>
            <div class="line"></div>
        </div>
        <div class="product-grid" id="product-container">
            <p style="text-align:center; width:100%;">Memuat produk...</p>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h4>Tentang Kami</h4>
                <p>Penyedia bumbu dan bahan pangan berkualitas sejak 1990.</p>
            </div>
            <div class="footer-col">
                <h4>Link Cepat</h4>
                <ul>
                    <li><a href="index.html">Beranda</a></li>
                    <li><a href="resep.html">Resep</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright"><p>&copy; 2025 Pler Javier. All Rights Reserved.</p></div>
    </footer>
</body>
</html>