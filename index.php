<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Diskon</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #555;
        }
        .btn-primary {
            background: #007bff;
            border: none;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .card {
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
        }
        .alert {
            margin-top: 20px;
            border-radius: 5px;
        }
        .nota {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .nota h4 {
            text-align: center;
            margin-bottom: 15px;
        }
        .nota p {
            margin: 5px 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Website Diskon</h1>
        <?php
        // Fungsi untuk menghitung diskon
        function hitungDiskon($quantity) {
            if ($quantity > 4 && $quantity < 7) {
                return 0.4; // Diskon 40%
            } elseif ($quantity >= 7) {
                return 0.48; // Diskon 48%
            }
            return 0; // Tidak ada diskon
        }

        // Proses form pembelian
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['payment'])) {
            $item = $_POST['item'];
            $quantity = $_POST['quantity'];
            $price = 0;

            // Menentukan harga barang
            switch ($item) {
                case 'barang1':
                    $price = 100000;
                    break;
                case 'barang2':
                    $price = 200000;
                    break;
                case 'barang3':
                    $price = 300000;
                    break;
            }

            // Menghitung total harga sebelum diskon
            $totalBeforeDiscount = $price * $quantity;

            // Menghitung diskon
            $discount = hitungDiskon($quantity);
            $totalAfterDiscount = $totalBeforeDiscount * (1 - $discount);

            // Menampilkan hasil
            echo "<div class='card'>
                    <div class='card-body'>
                        <p><strong>Barang:</strong> $item</p>
                        <p><strong>Jumlah Barang:</strong> $quantity</p>
                        <p><strong>Total Sebelum Diskon:</strong> Rp " . number_format($totalBeforeDiscount, 0, ',', '.') . "</p>
                        <p><strong>Diskon:</strong> " . ($discount * 100) . "%</p>
                        <p><strong>Total Setelah Diskon:</strong> Rp " . number_format($totalAfterDiscount, 0, ',', '.') . "</p>
                        <form id='paymentForm' method='POST'>
                            <input type='hidden' name='item' value='$item'>
                            <input type='hidden' name='quantity' value='$quantity'>
                            <input type='hidden' name='totalAfterDiscount' value='$totalAfterDiscount'>
                            <div class='form-group'>
                                <label for='payment'>Jumlah Pembayaran</label>
                                <input type='number' class='form-control' id='payment' name='payment' required>
                            </div>
                            <button type='submit' class='btn btn-primary'>Hitung Kembalian</button>
                        </form>
                    </div>
                  </div>";
        }

        // Proses form pembayaran
        elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment'])) {
            $item = $_POST['item'];
            $quantity = $_POST['quantity'];
            $totalAfterDiscount = $_POST['totalAfterDiscount'];
            $payment = $_POST['payment'];
            $change = $payment - $totalAfterDiscount;

            // Menampilkan nota kembalian
            echo "<div class='card'>
                    <div class='card-body'>
                        <div class='nota'>
                            <h4>Nota Kembalian</h4>
                            <p><strong>Barang:</strong> $item</p>
                            <p><strong>Jumlah Barang:</strong> $quantity</p>
                            <p><strong>Total Setelah Diskon:</strong> Rp " . number_format($totalAfterDiscount, 0, ',', '.') . "</p>
                            <p><strong>Jumlah Pembayaran:</strong> Rp " . number_format($payment, 0, ',', '.') . "</p>";

            if ($change >= 0) {
                echo "<p class='alert alert-success'><strong>Kembalian:</strong> Rp " . number_format($change, 0, ',', '.') . "</p>";
            } else {
                echo "<p class='alert alert-danger'><strong>Pembayaran Kurang:</strong> Rp " . number_format(abs($change), 0, ',', '.') . "</p>";
            }

            echo "</div>
                    <a href='index.php' class='btn btn-primary'>Kembali</a>
                  </div>
                </div>";
        }

        // Form awal pemilihan barang
        else {
            echo "<form action='index.php' method='POST'>
                    <div class='form-group'>
                        <label for='item'>Pilih Barang</label>
                        <select class='form-control' id='item' name='item'>
                            <option value='barang1'>Barang 1 - Rp 100.000</option>
                            <option value='barang2'>Barang 2 - Rp 200.000</option>
                            <option value='barang3'>Barang 3 - Rp 300.000</option>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for='quantity'>Jumlah Barang</label>
                        <input type='number' class='form-control' id='quantity' name='quantity' min='1' required>
                    </div>
                    <button type='submit' class='btn btn-primary'>Beli</button>
                  </form>";
        }
        ?>
    </div>

    <!-- jQuery Script untuk Animasi -->
    <script>
        $(document).ready(function() {
            // Animasi saat form pembayaran muncul
            $('#paymentForm').hide().fadeIn(1000);

            // Validasi input pembayaran
            $('#paymentForm').submit(function(e) {
                let payment = $('#payment').val();
                let totalAfterDiscount = <?php echo $totalAfterDiscount ?? 0; ?>;
                if (payment < totalAfterDiscount) {
                    alert('Pembayaran tidak boleh kurang dari total harga!');
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>