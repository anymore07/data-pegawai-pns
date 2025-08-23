<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class EselonController extends Controller
{
    public function index()
    {
        $data['title'] = 'Eselon';

        $data['eselon'] = DB::select("
            SELECT
                ID_ESELON,
                NAMA_ESELON,
                CREATED_AT,
                UPDATED_AT
            FROM md_eselon
            WHERE DELETED_AT IS NULL
              AND DELETED_BY IS NULL
            ORDER BY ID_ESELON DESC
        ");

        return view('layouts.header', $data)
            . view('master.eselon', $data)
            . view('layouts.footer');
    }

    public function save(Request $req)
    {
        $req->validate([
            'id_eselon' => 'nullable|integer',
            'nama_eselon' => 'nullable|string|max:50',
        ]);

        try {
            $isUpdate = filled($req->input('id_eselon'));
            $idEselon = $isUpdate ? (int) $req->input('id_eselon') : null;

            $payload = [
                'NAMA_ESELON' => $req->input('nama_eselon'),
            ];

            if ($isUpdate) {
                $payload['UPDATED_AT'] = now();
                $payload['UPDATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_eselon')
                    ->where('ID_ESELON', $idEselon)
                    ->update($payload);
            } else {
                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_eselon')->insert($payload);
            }

            return redirect('eselon')->with(
                'resp_msg',
                $isUpdate ? 'Berhasil mengubah eselon.' : 'Berhasil menambahkan eselon.'
            );

        } catch (Exception $e) {
            return redirect('eselon')->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::table('md_eselon')
                ->where('ID_ESELON', (int) $id)
                ->update([
                    'DELETED_AT' => now(),
                    'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
                ]);

            return redirect('eselon')->with('resp_msg', 'Berhasil menghapus eselon.');
        } catch (Exception $e) {
            return redirect('eselon')->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
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

        $conds = ["me.DELETED_AT IS NULL", "me.DELETED_BY IS NULL"];
        if ($search !== '') {
            $s = addcslashes($search, "%_");
            $conds[] = "(me.NAMA_ESELON LIKE '%$s%')";
        }
        $where = 'WHERE ' . implode(' AND ', $conds);

        $map = [
            'NAMA_ESELON' => 'me.NAMA_ESELON',
            'CREATED_AT' => 'me.CREATED_AT',
        ];
        $orderParts = ['me.ID_ESELON DESC'];
        if ($valOrder && isset($map[$valOrder])) {
            $orderParts[] = $map[$valOrder] . ' ' . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }
        $orderBy = 'ORDER BY ' . implode(', ', array_unique($orderParts));

        $results = DB::select("
            SELECT
                me.ID_ESELON,
                me.NAMA_ESELON,
                me.CREATED_AT
            FROM md_eselon me
            $where
            $orderBy
            LIMIT $start, $perPage
        ");

        $Tot = DB::selectOne("
            SELECT COUNT(1) AS TOTAL
            FROM md_eselon me
            $where
        ")->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'NAMA_ESELON' => $item->NAMA_ESELON,
                'ACTION_BUTTON' => '
                    <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->ID_ESELON . '`)">
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
