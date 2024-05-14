<h1 align="center" id="title">Kurumi Framework</h1>

<p align="center">
    <img src="./.image/logo.jpg" alt="kurumi"/>
</p>

<hr><br>

## 📕 Pengenalan Framework

**kurumi** adalah framework sederhana (iseng 🗿) yang namanya terinspirasi dari karakter anime `Date A Live` yaitu `Tokisaki Kurumi`.

kami membuat framework ini hanya sebatas, atau bisa dibilang untuk latihan kami belajar pemograman `PHP` terutama untuk `OOP` nya.

jadi intinya **jangan gunakan** framework ini untuk project real (project besar), tapi gunakan untuk project kecil-kecilan, pribadi, belajar atau project
yang budgetnya kecil banget tapi minta fiturnya banyak (bercanda) 🗿

#### Kapan release versi 1?

entah lah 🗿, yang jelas framework waifu saya ini masih dalam tahap pengembangan dan pengujian.

tapi nanti bakal ada kok versi ekperiment nya.

## 😋 Fitur Framework istriku 
**Fitur-Fitur yang tersedia**

- [Routing](#-routing)
- [Kurumi Template Engine](#-kurumi-template-engine)

## ☣ Routing

Ya... seperti `routing` pada umumnya si 🗿, bedanya
routing ini dibuat secara `native` jadi jangan terlalu
berharap ada yang special..., harus nya merasa cemas si 🗿
karena routing ini dibuat secara `native` jadi soal `scurity`
ngak terlalu menjamin hehe 👉👈 🗿 
(ya.. emang karna dari awal fw ini dibuat hanya iseng).


kira-kira seperti inilah sosok dari routing nya,
dan cara penggunaan nya secara sederhana.

`PATH: routes/web.php`

```php
use Kurumi\Routes\Route;

Route::get('/', function() {
    return view('home/index');
});

Route::run();
```

jadi, semua routing bisa kamu tulis di sini.

#### Penjelasan

```php 
use Kurumi\Routes\Route; 
```
ini namespace dari object `Route`, dan memiliki beberapa static method
diantarnya: 
 `GET`, `POST` dan `RUN`.

```php
Route::get('/', function() {
    return view('home/index', ["istriku" => "kurumi"]);
});
```
diparameter pertama yaitu `path/uri: string` dan parameter kedua `callable/function`.
jadi diparameter pertama kamu bisa nentuin `path` nya mau tampil dimana,
jadi gini disitukan saya nentuin `path` nya '/' yang dalam arti lain halaman root atau halaman home,
jadi method `GET` akan jalan dan menampilkan sesuatu ketika halaman nya berada pada `path` yang
sudah tadi tentukan.

```php
return view('home/index', ["istriku" => "kurumi"]);
```
function `view()` yang fungsinya untuk menampilkan halaman.
function `view()` memiliki 2 parameter:
<br>
<br>
`path: string`
<br>
jalur tempat file yang akan digunakan/ditampilkan, secara default function `view()` path nya mengarah 
pada folder `resources/views`, jadi pastikan file yang akan kamu pakai atau tampilkan berada pada folder itu.
<br>
ouh iya untuk nama file nya harus ada nama/extension kurumi nya contoh
`index.kurumi.php` kalau tidak ada filenya akan ditolak.
<br>
<br>
`data: array`
<br>
ini untuk mengirimkan sebuah data pada file yang kamu tentukan tadi, datanya harus berupa `array associative`
karna key nya akan dijadikan sebuah `variable` di file yang kamu tentukan.
contoh:
<br>
```php
["istriku" => "kurumi"]
```
maka dalam file `home/index` datanya bisa diambil/dipakai seperti ini.
<br>
```php
<p>ini waifuku {{ $istriku }} 😋</p>
```
<br>

```php
Route::run();
```
method ini fungsinya untuk menjalankan `route` yang tadi telah ditentukan.
<br>
