<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleToolController extends Controller
{

    private const DEFAULT_ARTICLE = <<<'TEXT'
Dalam kehidupan suatu negara, pendidikan memegang peranan yang amat
penting untuk menjamin kelangsungan hidup negara dan bangsa, karena pendidikan
merupakan wahana untuk meningkatkan dan mengembangkan kualitas sumber daya
manusia. Seiring dengan perkembangan teknologi komputer dan teknologi informasi,
sekolah-sekolah di Indonesia sudah waktunya mengembangkan Sistem Informasi
manajemennya agar mampu mengikuti perubahan jaman.

SISKO mampu memberikan kemudahan pihak pengelola menjalankan
kegiatannya dan meningkatkan kredibilitas dan akuntabilitas sekolah dimata siswa,
orang tua siswa, dan masyakat umumnya.Penerapan teknologi informasi untuk
menunjang proses pendidikan telah menjadi kebutuhan bagi lembaga pendidikan di
Indonesia. Pemanfaatan teknologi informasi ini sangat dibutuhkan untuk
meningkatkan efisiensi dan produktivitas bagi manajemen pendidikan. Keberhasilan
dalam peningkatan efisiensi dan produktivitas bagi manajemen pendidikan akan ikut
menentukan kelangsungan hidup lembaga pendidikan itu sendiri. Dengan kata lain
menunda penerapan teknologi informasi dalam lembaga pendidikan berarti menunda
kelancaran pendidikan dalam menghadapi persaingan global.

Pemanfaatan teknologi informasi diperuntukkan bagi peningkatan kinerja
lembaga pendidikan dalam upayanya meningkatkan kualitas Sumber Daya Manusia
Indonesia. Guru dan pengurus sekolah tidak lagi disibukkan oleh pekerjaan-pekerjaan
operasional, yang sesungguhnya dapat digantikan oleh komputer. Dengan demikian
dapat memberikan keuntungan dalam efisien waktu dan tenaga.

Penghematan waktu dan kecepatan penyajian informasi akibat penerapan
teknologi informasi tersebut akan memberikan kesempatan kepada guru dan pengurus
sekolah untuk meningkatkan kualitas komunikasi dan pembinaan kepada siswa.
Dengan demikian siswa akan merasa lebih dimanusiakan dalam upaya
mengembangkan kepribadian dan pengetahuannya.

Sebagai contoh yang paling utama adalah sistem penjadwalan yang harus
dilakukan setiap awal semester. Biasanya membutuhkan waktu lama untuk menyusun
penjadwalan, Dengan SISKO dapat selesai dalam waktu singkat. Untuk
mempermudah bagian administrasi kurikulum sekolah, SISKO menyediakan fasilitas
istimewa yang merupakan inti dari sistem kurikulum sekolah yaitu membantu dalam
pembuatan penjadwalan mata pelajaran sekolah yang dapat diproses tidak lebih lama
dari 10 menit. Administrator hanya akan memasukkan kondisi dari masing-masing
guru yang akan mengajar baik itu dalam 1 minggu seorang guru dapat mengajar berapa
jam, selain itu dapat juga melakukan pemesanan tempat dan penempatan hari libur
masing-masing guru dalam 1 minggu masa mengajar. Setelah semua kondisi
dimasukkan, sistem akan memproses semua data tersebut sehingga menghasilkan
jadwal yang optimal dan dapat langsung dipakai karena sistem akan mendeteksi
sehingga tidak akan ada jadwal yang bertumpukan satu dengan yang lainnya.

Setelah semua kondisi dimasukkan, sistem akan memproses semua data
tersebut sehingga menghasilkan jadwal yang optimal dan dapat langsung dipakai
karena sistem akan mendeteksi sehingga tidak akan ada jadwal yang bertumpukan satu
dengan yang lainnya. Setelah permasalahan penjadwalan dapat ditangani dengan baik,
hal yang tidak kalah pentingnya adalah memasukkan data siswa.

Program SISKO telah menyediakan fasilitas untuk penanganan penilaian
siswa yang secara langsung memasukkan nilai ke dalam raport dan siap dicetak. Untuk
sistem penilaian siswa, yang dapat melakukan pengisian hanya Guru yang mengajar
mata pelajaran. Sistem penilaian telah disesuaikan dengan KBK sehingga masing masing 
guru dapat memasukkan deskripsi narasi dari mata pelajaran. Untuk
menampilkan data penilaian dapat disesuaikan kembali dengan kebijaksanaan dari
masing-masing lembaga pendidikan apakah ingin menampilkan data nilai akhir siswa
maupun menampilkan data nilai siswa setiap kali mengadakan test ataupun tugas
tertentu.

Selain Modul untuk penjadwalan dan Modul Penilaian siswa, SISKO juga
memberikan fasilitas untuk bagian administrasi keuangan sekolah dalam hal
pembayaran SPP siswa. Bagian administrasi dapat langsung mengecek siapa siswa
yang mempunyai tunggakan SPP dan untuk detail histori pembayaran SPP dari
masing-masing siswa dapat dicetak seperti mencetak buku tabungan di bank sehingga
mempermudah pekerjaan pihak administrasi keuangan. Administrasi keuangan dapat
langsung melakukan pengaturan data pembayaran masing-masing siswa sesuai
dengan kebutuhan dan dapat diubah sewaktu-waktu apabila ada kenaikan pembayaran SPP. 
Apabila siswa tersebut akan melakukan pembayaran, petugas dapat langsung
memasukkan data. Hal sama juga dapat dilakukan untuk Data pembayaran
Sumbangan Sukarela dan Tabungan Karyawisata
TEXT;

    public function index()
    {
        $data = [
            'title' => 'Alat Olah Artikel',
            'article' => self::DEFAULT_ARTICLE,
            'result' => null,
            'mode' => null,
        ];

        return
            view('layouts.header', $data) .
            view('article_tool', $data) .
            view('layouts.footer');
    }


    public function run(Request $request)
    {
        $request->validate([
            'article' => 'required|string',
            'mode' => 'required|in:search,replace,sort',
        ]);

        $article = (string) $request->input('article');
        $mode = (string) $request->input('mode');
        $result = null;

        if ($mode === 'search') {
            $request->validate(['keyword' => 'required|string']);
            $keyword = (string) $request->input('keyword');
            $pattern = '/\b' . preg_quote($keyword, '/') . '\b/ui';
            $count = preg_match_all($pattern, $article, $m);
            $result = ['type' => 'search', 'keyword' => $keyword, 'count' => $count];
        } elseif ($mode === 'replace') {
            $request->validate(['from' => 'required|string', 'to' => 'required|string']);
            $from = (string) $request->input('from');
            $to = (string) $request->input('to');
            $pattern = '/\b' . preg_quote($from, '/') . '\b/ui';
            $replaced = preg_replace($pattern, $to, $article);
            $result = [
                'type' => 'replace',
                'from' => $from,
                'to' => $to,
                'preview' => $this->firstDiffPreview($article, $replaced),
                'article' => $replaced
            ];
            $article = $replaced;
        } else { // sort
            $lower = mb_strtolower($article, 'UTF-8');
            $clean = preg_replace('/[^\p{L}\p{N}\s-]+/u', ' ', $lower);
            $tokens = preg_split('/\s+/u', trim($clean), -1, PREG_SPLIT_NO_EMPTY);
            $unique = array_values(array_unique($tokens));
            sort($unique, SORT_NATURAL | SORT_FLAG_CASE);
            $result = ['type' => 'sort', 'words' => $unique, 'total' => count($unique)];
        }

        $data = [
            'title' => 'Alat Olah Artikel',
            'article' => $article,
            'result' => $result,
            'mode' => $mode,
        ];

        return
            view('layouts.header', $data) .
            view('article_tool', $data) .
            view('layouts.footer');
    }


    // Buat cuplikan perubahan pertama (untuk preview replace)
    private function firstDiffPreview(string $old, string $new, int $radius = 60): string
    {
        $len = min(strlen($old), strlen($new));
        $i = 0;
        for (; $i < $len; $i++) {
            if ($old[$i] !== $new[$i])
                break;
        }
        $start = max(0, $i - $radius);
        $snippetOld = substr($old, $start, $radius * 2);
        $snippetNew = substr($new, $start, $radius * 2);
        return "Sebelum: …" . $snippetOld . "…\nSesudah: …" . $snippetNew . "…";
    }
}
