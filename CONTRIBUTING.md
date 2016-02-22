# Aturan Umum

Aplikasi ini dikelompokkan dalam _module_. Setiap _module_ memiliki nama menggunakan [_CamelCase_](https://en.wikipedia.org/wiki/CamelCase). Jika beberapa _module_ tergabung dalam satu topik yang sama (misal: `Transkrip`), gunakan topik tersebut menjadi kata pertama dalam nama _module_ (misal: `TranskripRequest` dan `TranskripManage`.

Kelompokkanlah controller, view, model, config, nama tabel, migration script Anda menggunakan nama _module_ atau topik yang Anda buat. Contohnya:

Gunakan nama _module_ untuk mengelompokkan controller dan view. Gunakan nama _module_ atau topik untuk mengelompokkan model, config, nama tabel pada basis data, serta migration script (pilihlah mana yang paling tepat, sesuai kasus Anda). Contohnya:

* Controller: `controllers/TranskripRequest.php`, `controllers/TranskripManage.php`
* View: `views/TranskripRequest/*.php`, `views/TranskripManage/*.php`
* Model (opsional): `models/Transkrip/*_model.php`
* Config file (opsional): `config/Transkrip.php`
* Nama tabel (opsional): `Transkrip`
* Migration script (opsional): `migrations/20160222120000_Transkrip_initial.php`

# Hak Akses

Hak akses setiap _module_ diatur pada file `config/auth.php` (tidak terekam Git, contoh ambil dari `config/auth-dev.php`). Tambahkan _module_ Anda ke dalam array config "module", dan berikan hak akses ke role yang sesuai. Contoh:

```php
$config['modules'] = array(
    'TranskripRequest' => array('mahasiswa.ftis'),
    'TranskripManage' => array('tu.ftis')
);
```

Jika diperlukan, tambahkan role baru pada array config "roles". Setiap elemen array memetakan role dengan alamat email yang tergabung dalam role tersebut, dengan notasi [regular expression](http://php.net/manual/en/reference.pcre.pattern.syntax.php).

# Autentikasi

Setiap _module_ **wajib memeriksa hak akses sebelum ditampilkan**. Hal tersebut dilakukan dengan cara memanfaatkan template berikut pada controller:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NamaPage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
    }

    // ... implementasikan method-method Anda yang lain di sini...
}
```

# View

Setiap view menggunakan template yang menampilkan nama _module_, menu navigasi, serta _flash message_ (bila diperlukan). Untuk itu, setiap _view_ membutuhkan parameter `currentModule`, selain parameter-parameter lainnya. Untuk sebagian besar kasus, Anda bisa menggunakan fungsi `get_class()` jika memanggil view dari controller. Berikut adalah cara memanggil view minimal:

```php
$this->load->view('NamaPage/main', array('currentModule' => get_class()));
```

View Anda memanfaatkan HTML framework Zurb Foundation, dan mengandung template menu utama serta _flash message_ (jika Ada). Oleh karena itu, gunakan kode berikut untuk memulai membuat view Anda:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= $currentModule ?></title>
        <link rel="stylesheet" href="/public/foundation-6/css/foundation.css" />
        <link rel="stylesheet" href="/public/foundation-6/css/app.css" />
    </head>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>

        <!-- Tulislah isi view Anda di sini. -->

        <script src="/public/foundation-6/js/vendor/jquery.min.js"></script>
        <script src="/public/foundation-6/js/vendor/what-input.min.js"></script>
        <script src="/public/foundation-6/js/foundation.min.js"></script>
        <script src="/public/foundation-6/js/app.js"></script>
    </body>
</html>
```