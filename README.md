# URL Kısaltma Uygulaması

Bu proje, Laravel framework kullanarak geliştirilmiş bir URL kısaltma uygulamasıdır. Kullanıcılar uzun URL'leri kısa, kolayca paylaşılabilir bağlantılara dönüştürebilirler. Kısa URL'yi ziyaret eden biri, otomatik olarak orijinal URL'ye yönlendirilir.

## Özellikler

- Uzun URL'leri kısa ve benzersiz URL'lere dönüştürme
- URL'leri kısaltmadan önce doğrulama
- Bir URL'nin daha önce kısaltılmış olup olmadığını kontrol etme
- Kısa URL'lerden orijinal URL'lere yönlendirme

## Gereksinimler

- PHP >= 7.3
- Composer
- MySQL

## Kurulum

1. Depoyu klonlayın:

    ```bash
    git clone https://github.com/lutfubedel/MVC_Laravel_URL_Kisaltici.git
    cd MVC_Laravel_URL_Kisaltici
    ```

2. Bağımlılıkları yükleyin:

    ```bash
    composer create-project --prefer-dist laravel/laravel url-shortener
    ```

3.Veritabanı Ayarları:

   `url-shortener`  projesine geçin ve `.env` dosyasındaki veritabanı ayarlarını yapın:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=url_shortener
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. Migration ve Model Oluşturma:
    URL'leri saklamak için bir  `urls` tablosu oluşturun:
    ```bash
    php artisan make:model Url -m
    ```
    `database/migrations/{timestamp}_create_urls_table.php` dosyasını şu şekilde düzenleyin:
     ```bash
    public function up()
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('original_url');
            $table->string('short_url')->unique();
            $table->timestamps();
        });
    }
    ```
    Migrasyonları çalıştırın:
   ```bash
    php artisan migrate
    ```
   
6. Route ve Controller Oluşturma
    URL kısaltma işlemleri için bir `UrlController` oluşturun:
    ```bash
    php artisan make:controller UrlController
    ```
    `routes/web.php` dosyasını şu şekilde düzenleyin:
    ```bash
    use App\Http\Controllers\UrlController;

    Route::get('/', [UrlController::class, 'index']);
    Route::post('/shorten', [UrlController::class, 'shorten'])->name('shorten');
    Route::get('/{shortUrl}', [UrlController::class, 'redirect']);

    ```

7. Controller İşlemleri
    `UrlController` içinde fonksiyonları tanımlayın:
    ```bash
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Url;
    use Illuminate\Support\Str;
    
    class UrlController extends Controller
    {
        public function index()
        {
            return view('welcome');
        }
    
        public function shorten(Request $request)
        {
            $request->validate([
                'url' => 'required|url'
            ]);
    
            $originalUrl = $request->input('url');
            $url = Url::where('original_url', $originalUrl)->first();
    
            if ($url) {
                return redirect('/')->with('short_url', url($url->short_url));
            }
    
            $shortUrl = $this->generateUniqueShortUrl();
    
            Url::create([
                'original_url' => $originalUrl,
                'short_url' => $shortUrl
            ]);
    
            return redirect('/')->with('short_url', url($shortUrl));
        }
    
        public function redirect($shortUrl)
        {
            $url = Url::where('short_url', $shortUrl)->firstOrFail();
            return redirect($url->original_url);
        }
    
        private function generateUniqueShortUrl()
        {
            do {
                $shortUrl = Str::random(12);
            } while (Url::where('short_url', $shortUrl)->exists());
    
            return $shortUrl;
        }
    }

    ```

8. View Oluşturma
   `resources/views/welcome.blade.php` dosyasını oluşturun:
   ```bash
   <!DOCTYPE html>
    <html>
    <head>
        <title>URL Kısaltma</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    </head>
    <body>
        <div class="flex items-center justify-center h-screen">
            <div class="text-center">
                <h1 class="text-3xl mb-6">URL Kısaltma Uygulaması</h1>
                @if (session('short_url'))
                    <div class="mb-6">
                        <a href="{{ session('short_url') }}" class="text-blue-500">{{ session('short_url') }}</a>
                    </div>
                @endif
                <form method="POST" action="{{ route('shorten') }}">
                    @csrf
                    <input type="text" name="url" class="border p-2 mb-4 w-full" placeholder="Uzun URL'i buraya yapıştırın">
                    @error('url')
                        <div class="text-red-500 mb-4">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="bg-blue-500 text-white p-2 w-full">Kısalt</button>
                </form>
            </div>
        </div>
    </body>
    </html>

    ```
9. URL Doğrulama ve Yönlendirme
    URL'in geçerli olup olmadığını kontrol etmek için Laravel'in `validate` metodu kullanılır.
    Kısa URL'in benzersiz olup olmadığını kontrol etmek için `Str::random` fonksiyonu ile 12 karakterli rastgele bir dize oluşturulur.
    Kısa URL'e girildiğinde orijinal URL'e yönlendirme yapılır.

10. Projeyi Çalıştırma
    Son olarak projeyi çalıştırın:
    ```bash
    php artisan serve
    ```
## Kullanım

1. Ana sayfada, uzun bir URL'i metin giriş alanına yapıştırın.
2. "Kısalt" düğmesine tıklayın.
3. URL geçerliyse, giriş alanının altında kısaltılmış URL'i göreceksiniz.
4. Orijinal URL'ye erişmek için kısaltılmış URL'i kullanın.

## Proje Yapısı

- `app/Http/Controllers/UrlController.php`: URL kısaltma ve yönlendirme işlemlerini yönetir.
- `app/Models/Url.php`: URL modeli.
- `database/migrations/*_create_urls_table.php`: `urls` tablosu için migrasyon dosyası.
- `resources/views/welcome.blade.php`: Uygulamanın ana görünümü.

## URL Doğrulama

URL'ler, Laravel'in `validate` metoduyla `url` kuralı kullanılarak doğrulanır ve doğru formatta olup olmadıkları kontrol edilir.

## Kısa URL Oluşturma

Kısa URL'ler, `Str::random` metodu kullanılarak 12 karakterlik bir dizi oluşturularak üretilir. Bu dizi büyük harfler, küçük harfler, rakamlar, tire ve alt tire içerir. Oluşturulan dizi, veritabanında benzersiz olup olmadığı kontrol edilir.

## Örnek

`https://example.com/very/long/url` adresini kısaltmak için, giriş alanına yapıştırın ve "Kısalt" düğmesine tıklayın. Örneğin, `http://localhost/AbCdEfGhIjKl` gibi bir kısa URL alabilirsiniz. `http://localhost/AbCdEfGhIjKl` adresini ziyaret ettiğinizde orijinal URL'ye yönlendirileceksiniz.

## Ekran Görüntüleri

Ana Sayfa:

![Ana Sayfa](images/image1.png)

Kısaltılmış URL:

![Kısaltılmış URL](images/image2.png)

## Lisans

Bu proje MIT Lisansı ile lisanslanmıştır.
