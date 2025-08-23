<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlamatController extends Controller
{
    public function index($nip)
    {
        $data['title'] = 'Alamat Pegawai';
        $data['nip'] = $nip;

        $data['pegawai'] = DB::table('tb_pegawai')->where('NIP', $nip)->first();

        return view('layouts.header', $data)
            . view('alamat', $data)
            . view('layouts.footer');
    }

    public function save(Request $req)
    {
        $req->validate([
            'id_alamat' => 'nullable|integer',
            'nip' => 'required|string|max:50',
            'alamat' => 'required|string',
        ]);

        try {
            $isUpdate = filled($req->input('id_alamat'));
            $idAlamat = $isUpdate ? (int) $req->input('id_alamat') : null;

            $payload = [
                'NIP' => $req->input('nip'),
                'ALAMAT' => $req->input('alamat'),
            ];

            if ($isUpdate) {
                $payload['UPDATED_AT'] = now();
                $payload['UPDATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('tb_alamat')->where('ID_ALAMAT', $idAlamat)->update($payload);
            } else {
                $payload['CREATED_AT'] = now();
                $payload['CREATED_BY'] = auth()->user()->name ?? 'Super Admin';

                DB::table('tb_alamat')->insert($payload);
            }

            return redirect()->back()->with(
                'resp_msg',
                $isUpdate ? 'Berhasil mengubah alamat.' : 'Berhasil menambahkan alamat.'
            );

        } catch (Exception $e) {
            return redirect()->back()->with('err_msg', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::table('tb_alamat')->where('ID_ALAMAT', (int) $id)->update([
                'DELETED_AT' => now(),
                'DELETED_BY' => auth()->user()->name ?? 'Super Admin',
            ]);

            return redirect()->back()->with('resp_msg', 'Berhasil menghapus alamat.');
        } catch (Exception $e) {
            return redirect()->back()->with('err_msg', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function get_all_data(Request $req, $nip)
    {
        $orderData = $req->input('order')[0]['column'] ?? '';
        $orderType = strtoupper($req->input('order')[0]['dir'] ?? 'DESC');
        $valOrder = $req->input('columns')[$orderData]['data'] ?? '';
        $search = trim((string) ($req->input('search')['value'] ?? ''));
        $start = (int) $req->input('start', 0);
        $perPage = (int) $req->input('length', 10);

        $conds = ["NIP = '$nip'", "DELETED_AT IS NULL", "DELETED_BY IS NULL"];
        if ($search !== '') {
            $s = addcslashes($search, "%_");
            $conds[] = "(ALAMAT LIKE '%$s%')";
        }
        $where = 'WHERE ' . implode(' AND ', $conds);

        $map = [
            'ALAMAT' => 'ALAMAT',
        ];
        $orderParts = ['ID_ALAMAT DESC'];
        if ($valOrder && isset($map[$valOrder])) {
            $orderParts[] = $map[$valOrder] . ' ' . ($orderType === 'ASC' ? 'ASC' : 'DESC');
        }
        $orderBy = 'ORDER BY ' . implode(', ', array_unique($orderParts));

        $results = DB::select("
            SELECT *
            FROM tb_alamat
            $where
            $orderBy
            LIMIT $start, $perPage
        ");

        $Tot = DB::selectOne("
            SELECT COUNT(1) AS TOTAL
            FROM tb_alamat
            $where
        ")->TOTAL ?? 0;

        $data = [];
        foreach ($results as $item) {
            $parse = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
            $data[] = [
                'ALAMAT' => $item->ALAMAT,
                'ACTION_BUTTON' => '
                    <button type="button" class="btn btn-warning" onclick="openModal(`' . $parse . '`)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteModal(`' . $item->ID_ALAMAT . '`)">
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
