<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class JabatanController extends Controller
{
    public function index()
    {
        $data['title'] = 'Jabatan';

        $data['jabatan'] = DB::select("
            SELECT
                ID_JABATAN,
                NAMA_JABATAN,
                CREATED_AT,
                UPDATED_AT
            FROM md_jabatan
            WHERE DELETED_AT IS NULL
              AND DELETED_BY IS NULL
            ORDER BY ID_JABATAN DESC
        ");

        return view('layouts.header', $data)
            . view('master.jabatan', $data)
            . view('layouts.footer');
    }
    public function save(Request $req)
    {
        $req->validate([
            'id_jabatan' => 'nullable|integer',
            'nama_jabatan' => 'nullable|string|max:255',
        ]);

        try {
            $isUpdate = filled($req->input('id_jabatan'));
            $idJabatan = $isUpdate ? (int) $req->input('id_jabatan') : null;

            $payload = [
                'NAMA_JABATAN' => $req->input('nama_jabatan'),
            ];

            if ($isUpdate) {
                $payload['UPDATED_AT'] = now();
                $payload['UPDATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_jabatan')
                    ->where('ID_JABATAN', $idJabatan)
                    ->update($payload);
            } else {
                $last = DB::table('md_jabatan')
                    ->orderBy('ID_JABATAN', 'desc')
                    ->first();

                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('md_jabatan')->insert($payload);
            }

            return redirect('jabatan')->with(
                'resp_msg',
                $isUpdate ? 'Berhasil mengubah jabatan.' : 'Berhasil menambahkan jabatan.'
            );

        } catch (Exception $e) {
            return redirect('jabatan')->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            DB::table('md_jabatan')
                ->where('ID_JABATAN', (int) $id)
                ->update([
                    'DELETED_AT' => now(),
                    'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
                ]);

            return redirect('jabatan')->with('resp_msg', 'Berhasil menghapus jabatan.');
        } catch (Exception $e) {
            return redirect('jabatan')->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
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

        $conds = ["mj.DELETED_AT IS NULL", "mj.DELETED_BY IS NULL"];
        if ($search !== '') {
            $s = addcslashes($search, "%_");
            $conds[] = "(mj.NAMA_JABATAN LIKE '%$s%')";
        }
        $where = 'WHERE ' . implode(' AND ', $conds);

        $map = [
            'NAMA_JABATAN' => 'mj.NAMA_JABATAN',
            'CREATED_AT' => 'mj.CREATED_AT',
        ];
        $orderParts = ['mj.ID_JABATAN DESC'];
        if ($valOrder && isset($map[$valOrder])) {
            $orderParts[] = $map[$valOrder] . ' ' . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }
        $orderBy = 'ORDER BY ' . implode(', ', array_unique($orderParts));

        $results = DB::select("
        SELECT
            mj.ID_JABATAN,
            mj.NAMA_JABATAN,
            mj.CREATED_AT
        FROM md_jabatan mj
        $where
        $orderBy
        LIMIT $start, $perPage
    ");

        $Tot = DB::selectOne("
        SELECT COUNT(1) AS TOTAL
        FROM md_jabatan mj
        $where
    ")->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'NAMA_JABATAN' => $item->NAMA_JABATAN,
                'ACTION_BUTTON' => '
                <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->ID_JABATAN . '`)">
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
