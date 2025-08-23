<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class GolonganController extends Controller
{
    public function index()
    {
        $data['title'] = 'Golongan';

        $data['golongan'] = DB::select("
            SELECT
                ID_GOLONGAN,
                NAMA_GOLONGAN,
                CREATED_AT,
                UPDATED_AT
            FROM md_golongan
            WHERE DELETED_AT IS NULL
              AND DELETED_BY IS NULL
            ORDER BY ID_GOLONGAN DESC
        ");

        return view('layouts.header', $data)
            . view('master.golongan', $data)
            . view('layouts.footer');
    }

    public function save(Request $req)
    {
        $req->validate([
            'id_golongan' => 'nullable|integer',
            'nama_golongan' => 'nullable|string|max:50',
        ]);

        try {
            $isUpdate = filled($req->input('id_golongan'));
            $idGolongan = $isUpdate ? (int) $req->input('id_golongan') : null;

            $payload = [
                'NAMA_GOLONGAN' => $req->input('nama_golongan'),
            ];

            if ($isUpdate) {
                $payload['UPDATED_AT'] = now();
                $payload['UPDATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_golongan')
                    ->where('ID_GOLONGAN', $idGolongan)
                    ->update($payload);
            } else {
                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_golongan')->insert($payload);
            }

            return redirect('golongan')->with(
                'resp_msg',
                $isUpdate ? 'Berhasil mengubah golongan.' : 'Berhasil menambahkan golongan.'
            );

        } catch (Exception $e) {
            return redirect('golongan')->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::table('md_golongan')
                ->where('ID_GOLONGAN', (int) $id)
                ->update([
                    'DELETED_AT' => now(),
                    'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
                ]);

            return redirect('golongan')->with('resp_msg', 'Berhasil menghapus golongan.');
        } catch (Exception $e) {
            return redirect('golongan')->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
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

        $conds = ["mg.DELETED_AT IS NULL", "mg.DELETED_BY IS NULL"];
        if ($search !== '') {
            $s = addcslashes($search, "%_");
            $conds[] = "(mg.NAMA_GOLONGAN LIKE '%$s%')";
        }
        $where = 'WHERE ' . implode(' AND ', $conds);

        $map = [
            'NAMA_GOLONGAN' => 'mg.NAMA_GOLONGAN',
            'CREATED_AT' => 'mg.CREATED_AT',
        ];
        $orderParts = ['mg.ID_GOLONGAN DESC'];
        if ($valOrder && isset($map[$valOrder])) {
            $orderParts[] = $map[$valOrder] . ' ' . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }
        $orderBy = 'ORDER BY ' . implode(', ', array_unique($orderParts));

        $results = DB::select("
            SELECT
                mg.ID_GOLONGAN,
                mg.NAMA_GOLONGAN,
                mg.CREATED_AT
            FROM md_golongan mg
            $where
            $orderBy
            LIMIT $start, $perPage
        ");

        $Tot = DB::selectOne("
            SELECT COUNT(1) AS TOTAL
            FROM md_golongan mg
            $where
        ")->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'NAMA_GOLONGAN' => $item->NAMA_GOLONGAN,
                'ACTION_BUTTON' => '
                    <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->ID_GOLONGAN . '`)">
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
