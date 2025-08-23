<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class KotaController extends Controller
{
    public function index()
    {
        $data['title'] = 'Kota';

        $data['kota'] = DB::select("
            SELECT ID_KOTA, NAMA_KOTA, CREATED_AT, UPDATED_AT
            FROM md_kota
            WHERE DELETED_AT IS NULL
              AND DELETED_BY IS NULL
            ORDER BY ID_KOTA DESC
        ");

        return view('layouts.header', $data)
            . view('master.kota', $data)
            . view('layouts.footer');
    }

    public function save(Request $req)
    {
        $req->validate([
            'id_kota' => 'nullable|integer',
            'nama_kota' => 'required|string|max:100',
        ]);

        try {
            $isUpdate = filled($req->input('id_kota'));
            $idKota = $isUpdate ? (int) $req->input('id_kota') : null;

            $payload = [
                'NAMA_KOTA' => $req->input('nama_kota'),
            ];

            if ($isUpdate) {
                $payload['UPDATED_AT'] = now();
                $payload['UPDATED_BY'] = auth()->user()->name ?? 'Super Admin';
                DB::table('md_kota')->where('ID_KOTA', $idKota)->update($payload);
            } else {
                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';
                DB::table('md_kota')->insert($payload);
            }

            return redirect('kota')->with('resp_msg', $isUpdate ? 'Berhasil mengubah kota.' : 'Berhasil menambahkan kota.');
        } catch (Exception $e) {
            return redirect('kota')->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::table('md_kota')->where('ID_KOTA', (int)$id)->update([
                'DELETED_AT' => now(),
                'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
            ]);

            return redirect('kota')->with('resp_msg', 'Berhasil menghapus kota.');
        } catch (Exception $e) {
            return redirect('kota')->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function get_all_data(Request $req)
    {
        $orderData = $req->input('order')[0]['column'] ?? '';
        $orderType = strtoupper($req->input('order')[0]['dir'] ?? 'DESC');
        $valOrder = $req->input('columns')[$orderData]['data'] ?? '';
        $search = trim((string)($req->input('search')['value'] ?? ''));
        $start = (int)$req->input('start', 0);
        $perPage = (int)$req->input('length', 10);

        $conds = ["DELETED_AT IS NULL", "DELETED_BY IS NULL"];
        if ($search !== '') {
            $s = addcslashes($search, "%_");
            $conds[] = "(NAMA_KOTA LIKE '%$s%')";
        }
        $where = 'WHERE ' . implode(' AND ', $conds);

        $orderBy = 'ORDER BY ID_KOTA DESC';
        if ($valOrder) {
            $orderBy = "ORDER BY $valOrder " . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }

        $results = DB::select("
            SELECT ID_KOTA, NAMA_KOTA, CREATED_AT
            FROM md_kota
            $where
            $orderBy
            LIMIT $start, $perPage
        ");

        $Tot = DB::selectOne("
            SELECT COUNT(1) AS TOTAL
            FROM md_kota
            $where
        ")->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'NAMA_KOTA' => $item->NAMA_KOTA,
                'ACTION_BUTTON' => '
                    <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->ID_KOTA . '`)">
                        <i class="fa fa-trash-o"></i>
                    </button>
                ',
            ];
        }

        return response([
            'status_code' => 200,
            'status_message' => 'Data berhasil diambil!',
            'draw' => (int)$req->input('draw'),
            'recordsFiltered' => $Tot,
            'recordsTotal' => $Tot,
            'data' => $data,
        ], 200);
    }
}
