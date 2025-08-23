<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $data['title'] = 'Unit Kerja';

        $data['unit_kerja'] = DB::select("
            SELECT
                uk.ID_UNIT_KERJA,
                uk.NAMA_UNIT,
                k.NAMA_KOTA AS LOKASI,
                uk.PARENT_ID,
                parent.NAMA_UNIT AS PARENT_NAME,
                uk.CREATED_AT,
                uk.UPDATED_AT
            FROM md_unit_kerja uk
            LEFT JOIN md_kota k ON k.ID_KOTA = uk.LOKASI
            LEFT JOIN md_unit_kerja parent ON parent.ID_UNIT_KERJA = uk.PARENT_ID
            WHERE uk.DELETED_AT IS NULL
              AND uk.DELETED_BY IS NULL
            ORDER BY uk.ID_UNIT_KERJA DESC
        ");

         $data['kota'] = DB::table('md_kota')
        ->whereNull('DELETED_AT')
        ->whereNull('DELETED_BY')
        ->orderBy('NAMA_KOTA')
        ->get();
        
        return view('layouts.header', $data)
            . view('master.unit_kerja', $data)
            . view('layouts.footer');
    }

    public function save(Request $req)
    {
        $req->validate([
            'id_unit_kerja' => 'nullable|integer',
            'nama_unit' => 'required|string|max:255',
            'lokasi' => 'nullable|integer', // disimpan sebagai ID_KOTA
            'parent_id' => 'nullable|integer',
        ]);

        try {
            $isUpdate = filled($req->input('id_unit_kerja'));
            $idUnit = $isUpdate ? (int) $req->input('id_unit_kerja') : null;

            $payload = [
                'NAMA_UNIT' => $req->input('nama_unit'),
                'LOKASI' => $req->input('lokasi') ?: null, // simpan ID_KOTA
                'PARENT_ID' => $req->input('parent_id') ?: null,
            ];

            if ($isUpdate) {
                $payload['UPDATED_AT'] = now();
                $payload['UPDATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_unit_kerja')
                    ->where('ID_UNIT_KERJA', $idUnit)
                    ->update($payload);
            } else {
                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_unit_kerja')->insert($payload);
            }

            return redirect('unit-kerja')->with(
                'resp_msg',
                $isUpdate ? 'Berhasil mengubah unit kerja.' : 'Berhasil menambahkan unit kerja.'
            );

        } catch (Exception $e) {
            return redirect('unit-kerja')->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::table('md_unit_kerja')
                ->where('ID_UNIT_KERJA', (int) $id)
                ->update([
                    'DELETED_AT' => now(),
                    'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
                ]);

            return redirect('unit-kerja')->with('resp_msg', 'Berhasil menghapus unit kerja.');
        } catch (Exception $e) {
            return redirect('unit-kerja')->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
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

        $conds = ["uk.DELETED_AT IS NULL", "uk.DELETED_BY IS NULL"];
        if ($search !== '') {
            $s = addcslashes($search, "%_");
            $conds[] = "(uk.NAMA_UNIT LIKE '%$s%' OR k.NAMA_KOTA LIKE '%$s%')";
        }
        $where = 'WHERE ' . implode(' AND ', $conds);

        $map = [
            'NAMA_UNIT' => 'uk.NAMA_UNIT',
            'LOKASI' => 'k.NAMA_KOTA',
            'CREATED_AT' => 'uk.CREATED_AT',
        ];
        $orderParts = ['uk.ID_UNIT_KERJA DESC'];
        if ($valOrder && isset($map[$valOrder])) {
            $orderParts[] = $map[$valOrder] . ' ' . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }
        $orderBy = 'ORDER BY ' . implode(', ', array_unique($orderParts));

        $results = DB::select("
            SELECT
                uk.ID_UNIT_KERJA,
                uk.NAMA_UNIT,
                k.NAMA_KOTA AS LOKASI,
                uk.PARENT_ID,
                parent.NAMA_UNIT AS PARENT_NAME,
                uk.CREATED_AT
            FROM md_unit_kerja uk
            LEFT JOIN md_kota k ON k.ID_KOTA = uk.LOKASI
            LEFT JOIN md_unit_kerja parent ON parent.ID_UNIT_KERJA = uk.PARENT_ID
            $where
            $orderBy
            LIMIT $start, $perPage
        ");

        $Tot = DB::selectOne("
            SELECT COUNT(1) AS TOTAL
            FROM md_unit_kerja uk
            LEFT JOIN md_kota k ON k.ID_KOTA = uk.LOKASI
            $where
        ")->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'NAMA_UNIT' => $item->NAMA_UNIT,
                'LOKASI' => $item->LOKASI ?? '-',
                'PARENT' => $item->PARENT_NAME ?? '-',
                'ACTION_BUTTON' => '
                    <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->ID_UNIT_KERJA . '`)">
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
}
