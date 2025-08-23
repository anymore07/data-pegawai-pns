<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class PegawaiController extends Controller
{
    public function index()
    {
        $data['title'] = 'Pegawai';
        $data['pegawai'] = DB::select("
  SELECT p.*,
         g.NAMA_GOLONGAN,
         e.NAMA_ESELON,
         j.NAMA_JABATAN,
         u.NAMA_UNIT,
         a.ALAMAT,
         k.NAMA_KOTA AS TEMPAT_TUGAS
  FROM tb_pegawai p
  LEFT JOIN md_golongan   g ON p.ID_GOLONGAN   = g.ID_GOLONGAN
  LEFT JOIN md_eselon     e ON p.ID_ESELON     = e.ID_ESELON
  LEFT JOIN md_jabatan    j ON p.ID_JABATAN    = j.ID_JABATAN
  LEFT JOIN md_unit_kerja u ON p.ID_UNIT_KERJA = u.ID_UNIT_KERJA
  LEFT JOIN (
      SELECT ta.*
      FROM tb_alamat ta
      JOIN (
          SELECT NIP, MAX(ID_ALAMAT) AS ID_ALAMAT
          FROM tb_alamat
          WHERE DELETED_AT IS NULL
          GROUP BY NIP
      ) last ON last.NIP = ta.NIP AND last.ID_ALAMAT = ta.ID_ALAMAT
      WHERE ta.DELETED_AT IS NULL
  ) a ON a.NIP = p.NIP
  LEFT JOIN md_kota k ON p.TEMPAT_TUGAS = k.ID_KOTA
  WHERE p.DELETED_AT IS NULL
    AND p.DELETED_BY IS NULL
  ORDER BY p.NIP DESC
");


        // Ambil data master untuk dropdown
        $data['golongan'] = DB::table('md_golongan')->whereNull('DELETED_AT')->get();
        $data['eselon'] = DB::table('md_eselon')->whereNull('DELETED_AT')->get();
        $data['jabatan'] = DB::table('md_jabatan')->whereNull('DELETED_AT')->get();
        $data['unit_kerja'] = DB::table('md_unit_kerja')->whereNull('DELETED_AT')->get();
        $data['kota'] = DB::table('md_kota')->whereNull('DELETED_AT')->get(); // <- Tambahkan ini

        return view('layouts.header', $data)
            . view('pegawai', $data)
            . view('layouts.footer');
    }


    public function save(Request $req)
    {
        $req->validate([
            'nip' => 'required|string|max:50',
            'nama_pegawai' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'id_golongan' => 'nullable|integer',
            'id_eselon' => 'nullable|integer',
            'id_jabatan' => 'nullable|integer',
            'id_unit_kerja' => 'nullable|integer',
            'tempat_tugas' => 'nullable|string|max:100',
            'agama' => 'nullable|string|max:100',
            'no_telepon' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
        ]);

        try {
            $nip = $req->input('nip');
            $oldNip = $req->input('old_nip');

            $payload = [
                'NAMA_PEGAWAI' => $req->input('nama_pegawai'),
                'TEMPAT_LAHIR' => $req->input('tempat_lahir'),
                'TGL_LAHIR' => $req->input('tgl_lahir'),
                'JENIS_KELAMIN' => $req->input('jenis_kelamin'),
                'ID_GOLONGAN' => $req->input('id_golongan'),
                'ID_ESELON' => $req->input('id_eselon'),
                'ID_JABATAN' => $req->input('id_jabatan'),
                'ID_UNIT_KERJA' => $req->input('id_unit_kerja'),
                'TEMPAT_TUGAS' => $req->input('tempat_tugas'),
                'AGAMA' => $req->input('agama'),
                'NO_TELEPON' => $req->input('no_telepon'),
                'NPWP' => $req->input('npwp'),
            ];

            if ($req->hasFile('foto')) {
                $file = $req->file('foto');
                $payload['FOTO'] = base64_encode(file_get_contents($file->getRealPath()));
            }

            if ($oldNip) {
                // ===================== UPDATE =====================
                // Kalau ganti NIP → cek apakah NIP baru sudah dipakai
                if ($oldNip != $nip) {
                    $cekNip = DB::table(
                        'tb_pegawai'
                    )->where('NIP', $nip)->first();
                    if ($cekNip) {
                        return redirect('pegawai')->with('err_msg', 'Gagal mengubah: NIP sudah digunakan!');
                    }
                }

                DB::table('tb_pegawai')
                    ->where('NIP', $oldNip)
                    ->update(array_merge($payload, [
                        'NIP' => $nip, // pastikan tetap update NIP baru
                        'UPDATED_AT' => now(),
                        'UPDATED_BY' => auth()->user()->name ?? 'Super Admin',
                    ]));

                $msg = 'Berhasil mengubah data pegawai.';
            } else {
                // ===================== INSERT =====================
                $cekNip = DB::table('tb_pegawai')->where('NIP', $nip)->first();
                if ($cekNip) {
                    return redirect('pegawai')->with('err_msg', 'Gagal menambahkan: NIP sudah digunakan!');
                }

                $payload['NIP'] = $nip;
                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('tb_pegawai')->insert($payload);

                $msg = 'Berhasil menambahkan data pegawai.';
            }

            return redirect('pegawai')->with('resp_msg', $msg);

        } catch (Exception $e) {
            return redirect('pegawai')->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }




    public function delete($nip)
    {
        try {
            DB::table('tb_pegawai')
                ->where('NIP', $nip)
                ->update([
                    'DELETED_AT' => now(),
                    'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
                ]);

            return redirect('pegawai')->with('resp_msg', 'Berhasil menghapus pegawai.');
        } catch (Exception $e) {
            return redirect('pegawai')->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function get_all_data(Request $req)
    {
        $orderData = $req->input('order')[0]['column'] ?? '';
        $orderType = strtoupper($req->input('order')[0]['dir'] ?? 'DESC');
        $valOrder = $req->input('columns')[$orderData]['data'] ?? '';
        $search = trim((string) ($req->input('search')['value'] ?? ''));
        $start = (int) $req->input('start', 0);
        $perPage = (int) $req->input('length', 10);

        // === Filters (dari front-end) ===
        $f_jk = $req->input('filter_jk');          // 'L' | 'P' | ''
        $f_golongan = $req->input('filter_golongan');    // ID_GOLONGAN | ''
        $f_eselon = $req->input('filter_eselon');      // ID_ESELON | ''
        $f_jabatan = $req->input('filter_jabatan');     // ID_JABATAN | ''
        $f_tempat = $req->input('filter_tempat_tugas'); // ID_KOTA | ''

        // gunakan bindings biar aman dari SQL injection
        $conds = ["p.DELETED_AT IS NULL", "p.DELETED_BY IS NULL"];
        $binds = [];

        if ($search !== '') {
            $conds[] = "(p.NIP LIKE ? OR p.NAMA_PEGAWAI LIKE ?)";
            $binds[] = "%{$search}%";
            $binds[] = "%{$search}%";
        }
        if ($f_jk !== null && $f_jk !== '') {
            $conds[] = "p.JENIS_KELAMIN = ?";
            $binds[] = $f_jk;
        }
        if ($f_golongan !== null && $f_golongan !== '') {
            $conds[] = "p.ID_GOLONGAN = ?";
            $binds[] = $f_golongan;
        }
        if ($f_eselon !== null && $f_eselon !== '') {
            $conds[] = "p.ID_ESELON = ?";
            $binds[] = $f_eselon;
        }
        if ($f_jabatan !== null && $f_jabatan !== '') {
            $conds[] = "p.ID_JABATAN = ?";
            $binds[] = $f_jabatan;
        }
        if ($f_tempat !== null && $f_tempat !== '') {
            // p.TEMPAT_TUGAS menyimpan ID_KOTA
            $conds[] = "p.TEMPAT_TUGAS = ?";
            $binds[] = $f_tempat;
        }

        $where = 'WHERE ' . implode(' AND ', $conds);

        $orderParts = ['p.NIP DESC'];
        if ($valOrder) {
            $orderParts[] = $valOrder . ' ' . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }
        $orderBy = 'ORDER BY ' . implode(', ', array_unique($orderParts));

        // Data rows
        $sql = "
SELECT 
    p.*, 
    g.NAMA_GOLONGAN, 
    e.NAMA_ESELON, 
    j.NAMA_JABATAN, 
    u.NAMA_UNIT, 
    a.ALAMAT,
    k.NAMA_KOTA AS TEMPAT_TUGAS,
    p.TEMPAT_TUGAS AS ID_KOTA
FROM tb_pegawai p
LEFT JOIN md_golongan   g ON p.ID_GOLONGAN   = g.ID_GOLONGAN
LEFT JOIN md_eselon     e ON p.ID_ESELON     = e.ID_ESELON
LEFT JOIN md_jabatan    j ON p.ID_JABATAN    = j.ID_JABATAN
LEFT JOIN md_unit_kerja u ON p.ID_UNIT_KERJA = u.ID_UNIT_KERJA
/* === ALAMAT TERBARU SAJA === */
LEFT JOIN (
    SELECT ta.*
    FROM tb_alamat ta
    JOIN (
        SELECT NIP, MAX(ID_ALAMAT) AS ID_ALAMAT
        FROM tb_alamat
        WHERE DELETED_AT IS NULL
        GROUP BY NIP
    ) last ON last.NIP = ta.NIP AND last.ID_ALAMAT = ta.ID_ALAMAT
    WHERE ta.DELETED_AT IS NULL
) a ON a.NIP = p.NIP
LEFT JOIN md_kota       k ON k.ID_KOTA       = p.TEMPAT_TUGAS
$where
$orderBy
LIMIT $start, $perPage
";

        $results = DB::select($sql, $binds);

        // Total (tanpa LIMIT) — cukup di tb_pegawai, karena filter by FK ada di kolom p.*
        $sqlCount = "SELECT COUNT(1) AS TOTAL FROM tb_pegawai p $where";
        $Tot = DB::selectOne($sqlCount, $binds)->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'NIP' => $item->NIP,
                'NAMA_PEGAWAI' => $item->NAMA_PEGAWAI,
                'TEMPAT_LAHIR' => $item->TEMPAT_LAHIR,
                'TGL_LAHIR' => $item->TGL_LAHIR,
                'JENIS_KELAMIN' => $item->JENIS_KELAMIN,
                'GOLONGAN' => $item->NAMA_GOLONGAN,
                'ESELON' => $item->NAMA_ESELON,
                'JABATAN' => $item->NAMA_JABATAN,
                'UNIT_KERJA' => $item->NAMA_UNIT,
                'TEMPAT_TUGAS' => $item->TEMPAT_TUGAS ?? '-', // ini nama kota
                'AGAMA' => $item->AGAMA,
                'NO_TELEPON' => $item->NO_TELEPON,
                'NPWP' => $item->NPWP,
                'ALAMAT' => $item->ALAMAT,
                'FOTO' => $item->FOTO,
                'ACTION_BUTTON' => '
                <button type="button" class="btn btn-info" onclick="window.location.href=\'pegawai/alamat/' . $item->NIP . '\'"> <i class="fa fa-home"></i></button>
                <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->NIP . '`)">
                    <i class="fa fa-trash-o"></i>
                </button>
            ',
            ];
        }

        return response([
            'status_code' => 200,
            'status_message' => 'Data berhasil diambil!',
            'draw' => (int) $req->input('draw'),
            'recordsFiltered' => $Tot,
            'recordsTotal' => $Tot,
            'data' => $data,
        ], 200);
    }


    public function export_excel(Request $req)
    {
        // ambil filter dari request
        $f_jk = $req->input('filter_jk');
        $f_golongan = $req->input('filter_golongan');
        $f_eselon = $req->input('filter_eselon');
        $f_jabatan = $req->input('filter_jabatan');
        $f_tempat = $req->input('filter_tempat_tugas');

        // WHERE + bindings (aman dari injection)
        $conds = ["p.DELETED_AT IS NULL", "p.DELETED_BY IS NULL"];
        $binds = [];

        if (!empty($f_jk)) {
            $conds[] = "p.JENIS_KELAMIN = ?";
            $binds[] = $f_jk;
        }
        if (!empty($f_golongan)) {
            $conds[] = "p.ID_GOLONGAN   = ?";
            $binds[] = $f_golongan;
        }
        if (!empty($f_eselon)) {
            $conds[] = "p.ID_ESELON     = ?";
            $binds[] = $f_eselon;
        }
        if (!empty($f_jabatan)) {
            $conds[] = "p.ID_JABATAN    = ?";
            $binds[] = $f_jabatan;
        }
        if (!empty($f_tempat)) {
            $conds[] = "p.TEMPAT_TUGAS  = ?";
            $binds[] = $f_tempat;
        }

        $where = 'WHERE ' . implode(' AND ', $conds);

        $sql = "
SELECT 
    p.NIP, p.NAMA_PEGAWAI, p.TEMPAT_LAHIR, p.TGL_LAHIR, p.JENIS_KELAMIN,
    g.NAMA_GOLONGAN, e.NAMA_ESELON, j.NAMA_JABATAN, u.NAMA_UNIT,
    k.NAMA_KOTA AS TEMPAT_TUGAS, p.AGAMA, p.NO_TELEPON, p.NPWP, a.ALAMAT
FROM tb_pegawai p
LEFT JOIN md_golongan   g ON p.ID_GOLONGAN   = g.ID_GOLONGAN
LEFT JOIN md_eselon     e ON p.ID_ESELON     = e.ID_ESELON
LEFT JOIN md_jabatan    j ON p.ID_JABATAN    = j.ID_JABATAN
LEFT JOIN md_unit_kerja u ON p.ID_UNIT_KERJA = u.ID_UNIT_KERJA
/* === ALAMAT TERBARU SAJA === */
LEFT JOIN (
    SELECT ta.*
    FROM tb_alamat ta
    JOIN (
        SELECT NIP, MAX(ID_ALAMAT) AS ID_ALAMAT
        FROM tb_alamat
        WHERE DELETED_AT IS NULL
        GROUP BY NIP
    ) last ON last.NIP = ta.NIP AND last.ID_ALAMAT = ta.ID_ALAMAT
    WHERE ta.DELETED_AT IS NULL
) a ON a.NIP = p.NIP
LEFT JOIN md_kota       k ON p.TEMPAT_TUGAS  = k.ID_KOTA
$where
ORDER BY p.NIP DESC
";

        $rows = DB::select($sql, $binds);

        // Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header yang tampil di Excel (urutan kolom)
        $headers = [
            'NIP',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Golongan',
            'Eselon',
            'Jabatan',
            'Unit Kerja',
            'Tempat Tugas',
            'Agama',
            'No Telp',
            'NPWP',
            'Alamat'
        ];

        // Mapping header -> field di SELECT
        $map = [
            'NIP' => 'NIP',
            'Nama' => 'NAMA_PEGAWAI',
            'Tempat Lahir' => 'TEMPAT_LAHIR',
            'Tanggal Lahir' => 'TGL_LAHIR',
            'Jenis Kelamin' => 'JENIS_KELAMIN',
            'Golongan' => 'NAMA_GOLONGAN',
            'Eselon' => 'NAMA_ESELON',
            'Jabatan' => 'NAMA_JABATAN',
            'Unit Kerja' => 'NAMA_UNIT',
            'Tempat Tugas' => 'TEMPAT_TUGAS',   // alias nama kota
            'Agama' => 'AGAMA',
            'No Telp' => 'NO_TELEPON',
            'NPWP' => 'NPWP',
            'Alamat' => 'ALAMAT',
        ];

        // Tulis header + bold
        $col = 1;
        foreach ($headers as $h) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $h);
            $col++;
        }
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);

        $row = 2;
        foreach ($rows as $r) {
            $col = 1;
            foreach ($headers as $h) {
                $field = $map[$h];
                $val = $r->$field ?? '';

                // Transform ringan
                if ($field === 'JENIS_KELAMIN') {
                    $val = ($val === 'L') ? 'Laki-laki' : (($val === 'P') ? 'Perempuan' : '');
                }
                if ($field === 'TGL_LAHIR' && !empty($val)) {
                    try {
                        $val = \Carbon\Carbon::parse($val)->format('Y-m-d');
                    } catch (\Exception $e) {
                    }
                }

                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cell, $val);
                $col++;
            }
            $row++;
        }

        // Auto width kolom
        foreach (range(1, count($headers)) as $c) {
            $sheet->getColumnDimensionByColumn($c)->setAutoSize(true);
        }

        // Output file
        $fileName = "pegawai_export_" . date('Ymd_His') . ".xlsx";
        $writer = new Xlsx($spreadsheet);

        // header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        $writer->save('php://output');
        exit;
    }
}
