<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\ObatAlkes;
use App\Models\Resep;
use App\Models\Signa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NonRacikanController extends Controller
{
    public function index()
    {

        $data['obats'] = ObatAlkes::all();
        $data['signas'] = Signa::all();
        return view('nonracik', $data);
    }

    public function store()
    {
        $req = request()->all();
        $validator = Validator::make(request()->all(), [
            'obat' => 'required',
            'signa' => 'required',
            'qty' => 'required|numeric'
        ]);
        if ($validator->fails() == true) {
            return json_encode(['true' => false, 'message' => 'Lengkapi Form Resep']);
        } else {
            $stokObat = ObatAlkes::where('obatalkes_kode', $req['obat'])->first();
            if ($req['qty'] < $stokObat->stok) {
                $resepKode = 'NRCK' . microtime(true);
                $dataResep = [
                    'resep_kode' => $resepKode,
                    'signa_kode' => request()->all()['signa'],
                    'jenis' => 'non racikan'
                ];
                $dataObat = [
                    'obatalkes_kode' => $req['obat'],
                    'resep_kode' => $resepKode,
                    'qty' => $req['qty']
                ];
                Resep::create($dataResep);
                Obat::create($dataObat);
                $stokPakai = $stokObat->stok - $req['qty'];
                ObatAlkes::where('obatalkes_kode', $req['obat'])->update(['stok' => $stokPakai]);
                return json_encode(['true' => true, 'message' => 'Data resep berhasil di simpan']);
            } else {
                return json_encode(['true' => false, 'message' => 'Stok obat kurang, kurangi quantity']);
            }
        }
    }
}
