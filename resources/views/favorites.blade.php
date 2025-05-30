<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookEase - My Favorites</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f8f8f8;
            margin: 0; /* Menambahkan ini agar tidak ada margin default browser */
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* Menambahkan beberapa style header dari contoh saya sebelumnya jika kamu suka */
            background-color: #fff;
            padding: 15px 30px; /* Sesuaikan padding header */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom:30px;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }
        nav a.active {
            color: #c0392b;
            /* Mungkin tambahkan border-bottom seperti contoh saya agar lebih jelas aktifnya */
            /* border-bottom: 2px solid #c0392b; */
            /* padding-bottom: 5px; */
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #c0392b;
        }
        /* Container untuk konten utama agar tidak terlalu lebar */
        .container {
            max-width: 900px; /* Atau lebar lain yang kamu inginkan */
            margin: 0 auto; /* Tengah */
            padding: 0 15px; /* Padding kiri kanan container */
        }
        .card {
            display: flex;
            background: #eee; /* Kamu menggunakan #eee, saya sebelumnya #fff */
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            align-items: center;
            padding: 10px;
            /* box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Optional: shadow dari contoh saya */
        }
        .card img {
            width: 130px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px; /* Menambahkan margin kanan pada gambar */
        }
        .card-content {
            flex: 1;
            margin-left: 20px; /* Ini ada, tapi jika gambar sudah margin-right, ini mungkin dobel */
                               /* Pilih salah satu atau sesuaikan nilainya */
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333; /* Sedikit menggelapkan warna judul */
            margin-bottom: 5px; /* Spasi bawah judul */
        }
        .card-details {
            margin-top: 5px;
            color: #555;
            font-size: 0.9em; /* Sedikit mengecilkan detail */
        }
        /* Penyesuaian untuk delete button agar di kanan */
        .delete-btn-container {
            margin-left: auto; /* Mendorong tombol ke kanan */
            padding-left: 15px; /* Spasi antara konten dan tombol */
        }
        .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px; /* Sedikit padding agar mudah diklik */
        }
        .delete-btn img {
            width: 24px;
            height: 24px;
            display: block; /* Untuk memastikan padding bekerja baik */
        }
        /* Style untuk flash messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            text-align: center;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .no-favorites {
            padding: 20px;
            text-align: center;
            color: #777;
            background-color: #fff; /* Atau #eee agar senada card */
            border-radius: 8px;
            /* box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Optional */
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">CookEase</div>
        <nav>
            <a href="/dashboard">Home</a>
            <a href="/favorites" class="active">Favorites</a>
            <a href="/meal-plan">Meal Plan</a>
            <a href="/chatbot">Cooking Ast</a>
            <a href="/settings">Settings</a>
        </nav>
    </header>

    <div class="container"> {{-- Membungkus konten utama dengan container --}}
        @isset($viewing_for_user_id)
            <p style="text-align: center; font-style: italic; margin-bottom: 20px; color: #777;">
                Menampilkan favorit untuk User ID: {{ $viewing_for_user_id }} (Mode Tes)
            </p>
        @endisset

        <h2 style="margin-top: 30px;">My Favorite Recipes</h2>

        {{-- Menampilkan pesan flash dari session --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('info')) {{-- Menambahkan pesan info jika ada --}}
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif


        {{-- Menggunakan @forelse untuk menangani kasus jika $favorites kosong --}}
        @forelse($favorites as $recipeId => $recipeData)
            <div class="card">
                <img src="{{ $recipeData['image'] ?: 'https://placehold.co/130x100/e0e0e0/777?text=No+Image' }}" alt="{{ htmlspecialchars($recipeData['title']) }}">
                <div class="card-content">
                    <div class="card-title">{{ htmlspecialchars($recipeData['title']) }}</div>
                    <div class="card-details">
                        <span>🔥 {{ $recipeData['cal'] ?? 'N/A' }} cal</span> &nbsp;&nbsp; • &nbsp;&nbsp; <span>⏱ {{ $recipeData['time'] ?? 'N/A' }} Min</span>
                    </div>
                </div>
                <div class="delete-btn-container">
                    {{-- Form action sekarang menggunakan route 'favorites.destroy' --}}
                    {{-- $recipeId adalah recipe_id yang dikirim dari controller sebagai key array $favorites --}}
                    <form method="POST" action="{{ route('favorites.destroy', $recipeId) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" title="Hapus dari favorit">
                            <img src="https://cdn-icons-png.flaticon.com/512/1214/1214428.png" alt="Delete">
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-favorites">
                <p>
                    @isset($viewing_for_user_id)
                        User ID {{ $viewing_for_user_id }} tidak memiliki resep favorit saat ini.
                    @else
                        Anda tidak memiliki resep favorit saat ini.
                    @endisset
                </p>
                {{-- Kamu bisa tambahkan link ke halaman untuk menambah favorit jika ada, misal: --}}
                {{-- <p><a href="{{ route('test.recipes') }}">Coba Tambah Beberapa Resep ke Favorit</a></p> --}}
            </div>
        @endforelse
    </div> {{-- Penutup container --}}
</body>
</html>