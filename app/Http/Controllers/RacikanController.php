<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\ObatAlkes;
use App\Models\Signa;
use App\Models\Resep;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RacikanController extends Controller
{
    public $obat = 'obat';
    public function index()
    {
        $data['obats'] = ObatAlkes::all();
        $data['signas'] = Signa::all();
        return view('racikan', $data);
    }

    public function show()
    {
        return ObatAlkes::all();
    }

    public function store()
    {
        $req = request()->all();
        $validator = Validator::make(request()->all(), [
            'obat.*' => 'required',
            'signa' => 'required',
            'qty.*' => 'required|numeric',
            'rck_nama' => 'required'
        ]);
        if ($validator->fails() == true) {
            return json_encode(['true' => false, 'message' => 'Lengkapi Form Resep']);
        } else {
            $numStok = 0;
            for ($i = 0; $i <= count($req['obat']); $i++) {
                $stokObat = ObatAlkes::where('obatalkes_kode', $req['obat'][$i])->first();
                if ($req['qty'][$i] < $stokObat->stok) {
                    $numStok += 1;
                }
            }
            if ($numStok == count($req['obat'])) {
                $resepKode = 'RCK' . microtime(true);
                $dataResep = [
                    'resep_kode' => $resepKode,
                    'signa_kode' => request()->all()['signa'],
                    'jenis' => 'racikan',
                    'nama' => $req['rck_nama']
                ];
                Resep::create($dataResep);
                for ($k = 0; $k < count($req['obat']); $k++) {
                    $whereObat = ObatAlkes::where('obatalkes_kode', $req['obat'][$k])->first();
                    $dataObat = [
                        'obatalkes_kode' => $req['obat'][$k],
                        'resep_kode' => $resepKode,
                        'qty' => $req['qty'][$k]
                    ];
                    Obat::create($dataObat);
                    $stokPakai = $whereObat->stok - $req['qty'][$k];
                    ObatAlkes::where('obatalkes_kode', $req['obat'][$k])->update(['stok' => $stokPakai]);
                }
                return json_encode(['true' => true, 'message' => 'Data resep berhasil di simpan']);
            } else {
                return json_encode(['true' => false, 'message' => 'Stok obat kurang, kurangi quantity']);
            }
        }
    }
}
