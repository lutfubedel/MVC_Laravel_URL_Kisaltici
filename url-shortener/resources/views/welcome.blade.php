<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Kısaltıcı</title>
    <!-- Modern tipografi için Google Font 'Roboto'yu içe aktar -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Temel sıfırlama ve arka plan rengi */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        /* Konteyner stilizasyonu, merkezleme ve gölge efekti */
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        /* Başlık stili */
        h1 {
            margin-bottom: 20px;
            font-weight: 500;
            color: #4a4a4a;
        }
        /* Form düzeni ve stilizasyonu */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        /* Giriş alanı stilizasyonu */
        input[type="text"] {
            padding: 12px;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.2s;
        }
        /* Giriş alanı odaklanma durumu */
        input[type="text"]:focus {
            border-color: #4CAF50;
        }
        /* Buton stilizasyonu */
        button[type="submit"], #copyButton {
            padding: 12px 20px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }
        /* Buton hover durumu */
        button[type="submit"]:hover, #copyButton:hover {
            background-color: #45a049;
        }
        /* Paragraf stili */
        p {
            margin-top: 20px;
            word-wrap: break-word;
        }
        /* Bağlantı etiketi stili */
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        /* Bağlantı etiketi hover durumu */
        a:hover {
            text-decoration: underline;
        }
        /* Hata mesajı stilizasyonu */
        .error {
            color: #f00;
            margin-top: 20px;
        }
        /* Kopyalama mesajı stilizasyonu */
        #copyMessage {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>URL Kısaltıcı</h1>
        <!-- URL gönderimi için form -->
        <form action="{{ route('shorten') }}" method="POST">
            @csrf
            <input type="text" name="url" placeholder="URL'nizi girin" required>
            <button type="submit">Kısalt</button>
        </form>

        <!-- Kısaltılmış URL mevcutsa göster -->
        @if (session('shortened_url'))
            <p>
                Kısaltılmış URL: <a id="shortenedUrl" href="{{ session('shortened_url') }}">{{ session('shortened_url') }}</a>
            </p>
            <button id="copyButton" onclick="copyToClipboard()">Panoya Kopyala</button>
            <span id="copyMessage"></span>
        @endif

        <!-- Hatalar mevcutsa göster -->
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Panoya kopyalama işlevi için JavaScript -->
    <script>
        function copyToClipboard() {
            var copyText = document.getElementById("shortenedUrl").href;
            navigator.clipboard.writeText(copyText).then(function() {
                var copyMessage = document.getElementById("copyMessage");
                copyMessage.textContent = "URL panoya kopyalandı!";
                setTimeout(function() {
                    copyMessage.textContent = "";
                }, 2000);
            }, function(err) {
                console.error('Metin kopyalanamadı: ', err);
            });
        }
    </script>
</body>
</html>
